<?php
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
$curIdx = 0;
$orgID = $_SESSION['ORG_ID'];
$usrID = $_SESSION['USRID'];
$prsnid = $_SESSION['PRSN_ID'];
$canAddPrsSet = test_prmssns($dfltPrvldgs[14], $mdlNm);
$canEdtPrsSet = test_prmssns($dfltPrvldgs[15], $mdlNm);
$canDelPrsSet = test_prmssns($dfltPrvldgs[16], $mdlNm);

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                if ($canDelPrsSet === FALSE) {
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
                $prsnSetNm = getPrsStName($pKeyID);
                if (isPrsStInUse($pKeyID) == true) {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Deletion Failed! <br/>This Person Set has either been assigned to a Mass Pay or a Survey hence cannot be DELETED!</span>
                        </div>
                    </div>
                    <?php
                    exit();
                } else {
                    echo deletePersonSet($pKeyID, $prsnSetNm);
                }
            } else if ($actyp == 2) {
                //"Removing Set Persons...";  
                if ($canEdtPrsSet === FALSE) {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Deletion Failed! Permission Denied!</span>
                        </div>
                    </div>
                    <?php
                    exit();
                }
                $prsnLocID = isset($_POST['prsnLocID']) ? cleanInputData($_POST['prsnLocID']) : -1;
                $prsnSetDetID = isset($_POST['prsnSetDetID']) ? cleanInputData($_POST['prsnSetDetID']) : -1;
                echo deletePersonSetDet($prsnSetDetID, $prsnLocID);
            } else if ($actyp == 3) {
                if ($canEdtPrsSet === FALSE) {
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
                echo deletePersonSetRole($pKeyID, "");
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                $prsnSetID = isset($_POST['prsnSetID']) ? cleanInputData($_POST['prsnSetID']) : '';
                $prsnSetNm = isset($_POST['prsnSetNm']) ? cleanInputData($_POST['prsnSetNm']) : '';
                $prsnSetDesc = isset($_POST['prsnSetDesc']) ? cleanInputData($_POST['prsnSetDesc']) : '';
                $prsnSetUsesSQL = isset($_POST['prsnSetUsesSQL']) ? cleanInputData($_POST['prsnSetUsesSQL']) : 'NO';
                $prsnSetEnbld = isset($_POST['prsnSetEnbld']) ? cleanInputData($_POST['prsnSetEnbld']) : 'NO';
                $prsnSetIsDflt = isset($_POST['prsnSetIsDflt']) ? cleanInputData($_POST['prsnSetIsDflt']) : 'NO';
                $prsnSetSQL = isset($_POST['prsnSetSQL']) ? cleanInputData($_POST['prsnSetSQL']) : '';
                $slctdPrsnSetRoles = isset($_POST['slctdPrsnSetRoles']) ? cleanInputData($_POST['slctdPrsnSetRoles']) : '';
                $oldPrsnSetID = getPrsStID($prsnSetNm, $orgID);
                $prsnSetUsesSQLBool = $prsnSetUsesSQL == "NO" ? FALSE : TRUE;
                $prsnSetEnbldBool = $prsnSetEnbld == "NO" ? FALSE : TRUE;
                $prsnSetIsDfltBool = $prsnSetIsDflt == "NO" ? FALSE : TRUE;
                if ($prsnSetUsesSQLBool == FALSE) {
                    $prsnSetSQL = "";
                }

                $errMsg = "";
                if (($prsnSetUsesSQLBool == TRUE && $prsnSetSQL != "")) {
                    if (isPrsnSetSQLValid($prsnSetSQL, $errMsg) === FALSE) {
                        ?>
                        <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                            <div class="row" style="float:none;width:100%;text-align: center;">
                                <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to save Person Set!<br/><?php echo $errMsg; ?></span>
                            </div>
                        </div>
                        <?php
                        exit();
                    }
                }
                if ($prsnSetNm != "" &&
                        ($oldPrsnSetID <= 0 || $oldPrsnSetID == $prsnSetID) && (($prsnSetUsesSQLBool == TRUE && $prsnSetSQL != "") || ($prsnSetUsesSQLBool == FALSE))) {
                    if ($prsnSetID <= 0) {
                        createPrsSt($orgID, $prsnSetNm, $prsnSetDesc, $prsnSetEnbldBool, $prsnSetSQL, $prsnSetIsDfltBool, $prsnSetUsesSQLBool);
                        $prsnSetID = getPrsStID($prsnSetNm, $orgID);
                    } else {
                        updatePersonSet($prsnSetID, $prsnSetNm, $prsnSetDesc, $prsnSetEnbldBool, $prsnSetSQL, $prsnSetIsDfltBool, $prsnSetUsesSQLBool);
                    }
                    //Save Role Sets
                    $variousRows = explode("|", trim($slctdPrsnSetRoles, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 3) {
                            $payRoleID = cleanInputData1($crntRow[0]);
                            $inptRoleNm = cleanInputData1($crntRow[1]);
                            $inptRoleID = cleanInputData1($crntRow[2]);
                            if (doesPrsnSetHvRole($prsnSetID, $inptRoleID) <= 0) {
                                createPayRole(-1, $prsnSetID, $inptRoleID);
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Person Set Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to save Person Set!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 2) {
                //"Saving Set Persons...";
                $sbmtdPrsnSetHdrID = isset($_POST['sbmtdPrsnSetHdrID']) ? cleanInputData($_POST['sbmtdPrsnSetHdrID']) : '';
                $slctdPrsnSetPrsns = isset($_POST['slctdPrsnSetPrsns']) ? cleanInputData($_POST['slctdPrsnSetPrsns']) : '';

                if (trim($slctdPrsnSetPrsns, "|~") != "" && $sbmtdPrsnSetHdrID > 0) {
                    //Save Persons
                    $variousRows = explode("|", trim($slctdPrsnSetPrsns, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 2) {
                            $prsnLocID = cleanInputData1($crntRow[0]);
                            $inptPrsnNm = cleanInputData1($crntRow[1]);
                            $inptPrsnID = getPersonID($prsnLocID);
                            if (doesPrsStHvPrs($sbmtdPrsnSetHdrID, $inptPrsnID) === FALSE) {
                                createPersonSetDet($sbmtdPrsnSetHdrID, $inptPrsnID);
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Person Added Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Add Person!</span>
                        </div>
                    </div>
                    <?php
                }
            }
        } else {            
            if ($vwtyp == 0) {
                $sbmtdPrsnSetHdrID = isset($_POST['sbmtdPrsnSetHdrID']) ? $_POST['sbmtdPrsnSetHdrID'] : -1;
                echo $cntent . "<li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Person Sets</span>
				</li>
                               </ul>
                              </div>";
                $total = get_PrsnSetsTtl($srchFor, $srchIn, $orgID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_PrsnSets($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>
                <form id='allPrsSetsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php if ($canAddPrsSet === true) { ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePrsSetForm(-1, 3);" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Person Set
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
                                <input class="form-control" id="allPrsSetsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllPrsSets(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMdl=<?php echo $srcMdl; ?>')">
                                <input id="allPrsSetsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrsSets('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMdl=<?php echo $srcMdl; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrsSets('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMdl=<?php echo $srcMdl; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allPrsSetsSrchIn">
                                    <?php
                                    $valslctdArry = array("");
                                    $srchInsArrys = array("Name");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allPrsSetsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllPrsSets('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMdl=<?php echo $srcMdl; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllPrsSets('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMdl=<?php echo $srcMdl; ?>');" aria-label="Next">
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
                            <table class="table table-striped table-bordered table-responsive" id="allPrsSetsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Person Set Name</th>   
                                        <th style="text-align:center;">Enabled?</th>   
                                        <th style="text-align:center;">Is Default?</th> 
                                        <th>&nbsp;</th>                                       
                                        <?php if ($canDelPrsSet === true) { ?>
                                            <th>&nbsp;</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $usesSQL = "0";
                                    while ($row = loc_db_fetch_array($result)) {
                                        if ($sbmtdPrsnSetHdrID <= 0 && $cntr <= 0) {
                                            $sbmtdPrsnSetHdrID = $row[0];
                                            $usesSQL = $row[6];
                                        }
                                        $cntr += 1;
                                        ?>
                                        <tr id="allPrsSetsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $row[1]; ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allPrsSetsRow<?php echo $cntr; ?>_PrsSetID" value="<?php echo $row[0]; ?>">
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
                                                            <input type="checkbox" class="form-check-input" id="allPrsSetsRow<?php echo $cntr; ?>_IsEnbld" name="allPrsSetsRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd . " " . $isRdOnly; ?> >
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd" style="text-align:center;">
                                                <?php
                                                $isChkd = "";
                                                $isRdOnly = "disabled=\"true\"";
                                                if ($row[5] == "1") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>   
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <div class="form-check" style="font-size: 12px !important;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" id="allPrsSetsRow<?php echo $cntr; ?>_IsDflt" name="allPrsSetsRow<?php echo $cntr; ?>_IsDflt" <?php echo $isChkd . " " . $isRdOnly; ?> >
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOnePrsSetForm(<?php echo $row[0]; ?>, 2);" data-toggle="tooltip" data-placement="bottom" title="View Details">
                                                    <img src="cmn_images/kghostview.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canDelPrsSet === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPersonSet('allPrsSetsRow_<?php echo $cntr; ?>', '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMdl=<?php echo $srcMdl; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Person Set">
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
                            <div class="" id="allPrsSetsDetailInfo">
                                <?php
                                $srchFor = "%";
                                $srchIn = "Name";
                                $pageNo = 1;
                                $lmtSze = 10;
                                $vwtyp = 1;
                                if ($sbmtdPrsnSetHdrID > 0) {
                                    $total = get_PrsnSetsDtTtl($srchFor, $srchIn, $sbmtdPrsnSetHdrID);
                                    if ($pageNo > ceil($total / $lmtSze)) {
                                        $pageNo = 1;
                                    } else if ($pageNo < 1) {
                                        $pageNo = ceil($total / $lmtSze);
                                    }

                                    $curIdx = $pageNo - 1;
                                    $result2 = get_PrsnSetsDt($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdPrsnSetHdrID);
                                    ?>
                                    <div class="row">
                                        <?php
                                        if ($canEdtPrsSet === true && $usesSQL != "1") {
                                            $colClassType1 = "col-lg-2";
                                            $colClassType2 = "col-lg-3";
                                            $colClassType3 = "col-lg-4";
                                            $nwRowHtml = urlencode("<tr id=\"prsSetPrsnsRow__WWW123WWW\">"
                                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                    . "<td class=\"lovtd\">
                                                                    <div class=\"input-group\">
                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"prsSetPrsnsRow_WWW123WWW_PrsnLocID\" name=\"prsSetPrsnsRow_WWW123WWW_PrsnLocID\" value=\"\" readonly=\"true\">
                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', 'prsSetOrgID', '', '', 'radio', true, '', 'prsSetPrsnsRow_WWW123WWW_PrsnLocID', 'prsSetPrsnsRow_WWW123WWW_PrsnNm', 'clear', 1, '');\">
                                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                        </label>
                                                                    </div>
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"prsSetPrsnsRow_WWW123WWW_PrsnSetDetID\" value=\"-1\" style=\"width:100% !important;\">                                              
                                                            </td>                                             
                                                            <td class=\"lovtd\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"prsSetPrsnsRow_WWW123WWW_PrsnNm\" name=\"prsSetPrsnsRow_WWW123WWW_PrsnNm\" value=\"\" readonly=\"true\">                                                               
                                                            </td>
                                                                <td class=\"lovtd\">
                                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPrsnSetPrsn('prsSetPrsnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Person\">
                                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                    </button>
                                                                </td>
                                        </tr>");
                                            ?> 
                                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">     
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('prsSetPrsnsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Person">
                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="savePrsnSetPrsns('', '#allPrsSetsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrsnSetHdrID=<?php echo $sbmtdPrsnSetHdrID; ?>');" data-toggle="tooltip" data-placement="bottom" title="Save Persons">
                                                    <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </div>
                                            <?php
                                        } else {
                                            $colClassType1 = "col-lg-4";
                                            $colClassType2 = "col-lg-4";
                                            $colClassType3 = "col-lg-4";
                                        }
                                        ?>
                                        <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                            <div class="input-group">
                                                <input class="form-control" id="prsSetPrsnsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncPrsSetPrsns(event, '', '#allPrsSetsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrsnSetHdrID=<?php echo $sbmtdPrsnSetHdrID; ?>');">
                                                <input id="prsSetPrsnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrsSetPrsns('clear', '#allPrsSetsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrsnSetHdrID=<?php echo $sbmtdPrsnSetHdrID; ?>');">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </label>
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrsSetPrsns('', '#allPrsSetsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrsnSetHdrID=<?php echo $sbmtdPrsnSetHdrID; ?>');">
                                                    <span class="glyphicon glyphicon-search"></span>
                                                </label> 
                                            </div>
                                        </div>
                                        <div class="<?php echo $colClassType2; ?>">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                <!--<select data-placeholder="Select..." class="form-control chosen-select" id="prsSetPrsnsSrchIn">
                                                <?php
                                                $valslctdArry = array("");
                                                $srchInsArrys = array("Name");

                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($srchIn == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                                                                                                                                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                                </select>-->
                                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                <select data-placeholder="Select..." class="form-control chosen-select" id="prsSetPrsnsDsplySze" style="min-width:70px !important;">                            
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
                                        <div class="<?php echo $colClassType2; ?>">
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination" style="margin: 0px !important;">
                                                    <li>
                                                        <a class="rhopagination" href="javascript:getAllPrsSetPrsns('previous', '#allPrsSetsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrsnSetHdrID=<?php echo $sbmtdPrsnSetHdrID; ?>');" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="rhopagination" href="javascript:getAllPrsSetPrsns('next', '#allPrsSetsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrsnSetHdrID=<?php echo $sbmtdPrsnSetHdrID; ?>');" aria-label="Next">
                                                            <span aria-hidden="true">&raquo;</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                            <input type="hidden" class="form-control" aria-label="..." id="prsSetOrgID" name="prsSetOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" class="form-control" aria-label="..." id="sbmtdPrsnSetHdrID" name="sbmtdPrsnSetHdrID" value="<?php echo $sbmtdPrsnSetHdrID; ?>">
                                        </div>
                                    </div>
                                    <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                        <div class="col-md-12" style="padding:0px 1px 0px 1px !important">
                                            <table class="table table-striped table-bordered table-responsive" id="prsSetPrsnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Person ID</th>
                                                        <th>Full Name</th>
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
                                                        <tr id="prsSetPrsnsRow_<?php echo $cntr; ?>">                                    
                                                            <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                            <td class="lovtd">
                                                                <input type="hidden" class="form-control" aria-label="..." id="prsSetPrsnsRow<?php echo $cntr; ?>_PrsnLocID" name="prsSetPrsnsRow<?php echo $cntr; ?>_PrsnLocID" value="<?php echo $row2[1]; ?>" readonly="true">
                                                                <span><?php echo $row2[1]; ?></span>
                                                                <input type="hidden" class="form-control" aria-label="..." id="prsSetPrsnsRow<?php echo $cntr; ?>_PrsnSetDetID" value="<?php echo $row2[3]; ?>" style="width:100% !important;">                                              
                                                            </td>                                             
                                                            <td class="lovtd">  
                                                                <span><?php echo $row2[2]; ?></span>
                                                                <input type="hidden" class="form-control" aria-label="..." id="prsSetPrsnsRow<?php echo $cntr; ?>_PrsnNm" name="prsSetPrsnsRow<?php echo $cntr; ?>_PrsnNm" value="<?php echo $row2[2]; ?>" readonly="true">
                                                            </td>
                                                            <?php if ($usesSQL != "1") { ?>
                                                                <td class="lovtd">
                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPrsnSetPrsn('prsSetPrsnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Person">
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
                                <?php
                            } else {
                                ?>
                                <span>No Results Found</span>
                                <?php
                            }
                            ?>
                        </fieldset>
                    </div>
                </div>
                </form>
                <?php
            } else if ($vwtyp == 1) {
                $sbmtdPrsnSetHdrID = isset($_POST['sbmtdPrsnSetHdrID']) ? $_POST['sbmtdPrsnSetHdrID'] : -1;
                if ($sbmtdPrsnSetHdrID > 0) {
                    $total = get_PrsnSetsDtTtl($srchFor, $srchIn, $sbmtdPrsnSetHdrID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result2 = get_PrsnSetsDt($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdPrsnSetHdrID);
                    $usesSQL = getGnrlRecNm("pay.pay_prsn_sets_hdr", "prsn_set_hdr_id", "uses_sql", $sbmtdPrsnSetHdrID);
                    ?>
                    <div class="row">
                        <?php
                        if ($canEdtPrsSet === true && $usesSQL != "1") {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            $colClassType3 = "col-lg-4";
                            $nwRowHtml = urlencode("<tr id=\"prsSetPrsnsRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                    . "<td class=\"lovtd\">
                                                                    <div class=\"input-group\">
                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"prsSetPrsnsRow_WWW123WWW_PrsnLocID\" name=\"prsSetPrsnsRow_WWW123WWW_PrsnLocID\" value=\"\" readonly=\"true\">
                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', 'prsSetOrgID', '', '', 'radio', true, '', 'prsSetPrsnsRow_WWW123WWW_PrsnLocID', 'prsSetPrsnsRow_WWW123WWW_PrsnNm', 'clear', 1, '');\">
                                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                        </label>
                                                                    </div>
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"prsSetPrsnsRow_WWW123WWW_PrsnSetDetID\" value=\"-1\" style=\"width:100% !important;\">                                              
                                                            </td>                                             
                                                            <td class=\"lovtd\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"prsSetPrsnsRow_WWW123WWW_PrsnNm\" name=\"prsSetPrsnsRow_WWW123WWW_PrsnNm\" value=\"\" readonly=\"true\">                                                               
                                                            </td>
                                                                <td class=\"lovtd\">
                                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPrsnSetPrsn('prsSetPrsnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Person\">
                                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                    </button>
                                                                </td>
                                        </tr>");
                            ?> 
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">     
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('prsSetPrsnsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Person">
                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="savePrsnSetPrsns('', '#allPrsSetsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrsnSetHdrID=<?php echo $sbmtdPrsnSetHdrID; ?>');" data-toggle="tooltip" data-placement="bottom" title="Save Persons">
                                    <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-4";
                            $colClassType2 = "col-lg-4";
                            $colClassType3 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="prsSetPrsnsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncPrsSetPrsns(event, '', '#allPrsSetsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrsnSetHdrID=<?php echo $sbmtdPrsnSetHdrID; ?>');">
                                <input id="prsSetPrsnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrsSetPrsns('clear', '#allPrsSetsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrsnSetHdrID=<?php echo $sbmtdPrsnSetHdrID; ?>');">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrsSetPrsns('', '#allPrsSetsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrsnSetHdrID=<?php echo $sbmtdPrsnSetHdrID; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <!--<select data-placeholder="Select..." class="form-control chosen-select" id="prsSetPrsnsSrchIn">
                                <?php
                                $valslctdArry = array("");
                                $srchInsArrys = array("Name");

                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                                                                                                                                                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                                </select>-->
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="prsSetPrsnsDsplySze" style="min-width:70px !important;">                            
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
                        <div class="<?php echo $colClassType2; ?>">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllPrsSetPrsns('previous', '#allPrsSetsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrsnSetHdrID=<?php echo $sbmtdPrsnSetHdrID; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllPrsSetPrsns('next', '#allPrsSetsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrsnSetHdrID=<?php echo $sbmtdPrsnSetHdrID; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <input type="hidden" class="form-control" aria-label="..." id="prsSetOrgID" name="prsSetOrgID" value="<?php echo $orgID; ?>">
                            <input type="hidden" class="form-control" aria-label="..." id="sbmtdPrsnSetHdrID" name="sbmtdPrsnSetHdrID" value="<?php echo $sbmtdPrsnSetHdrID; ?>">
                        </div>
                    </div>
                    <div class="row" style="padding:0px 15px 0px 15px !important">                  
                        <div class="col-md-12" style="padding:0px 1px 0px 1px !important">
                            <table class="table table-striped table-bordered table-responsive" id="prsSetPrsnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Person ID</th>
                                        <th>Full Name</th>
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
                                        <tr id="prsSetPrsnsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                            <td class="lovtd">
                                                <input type="hidden" class="form-control" aria-label="..." id="prsSetPrsnsRow<?php echo $cntr; ?>_PrsnLocID" name="prsSetPrsnsRow<?php echo $cntr; ?>_PrsnLocID" value="<?php echo $row2[1]; ?>" readonly="true">
                                                <span><?php echo $row2[1]; ?></span>
                                                <input type="hidden" class="form-control" aria-label="..." id="prsSetPrsnsRow<?php echo $cntr; ?>_PrsnSetDetID" value="<?php echo $row2[3]; ?>" style="width:100% !important;">                                              
                                            </td>                                             
                                            <td class="lovtd">  
                                                <span><?php echo $row2[2]; ?></span>
                                                <input type="hidden" class="form-control" aria-label="..." id="prsSetPrsnsRow<?php echo $cntr; ?>_PrsnNm" name="prsSetPrsnsRow<?php echo $cntr; ?>_PrsnNm" value="<?php echo $row2[2]; ?>" readonly="true">

                                            </td>
                                            <?php if ($usesSQL != "1") { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPrsnSetPrsn('prsSetPrsnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Person">
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
                    <?php
                } else {
                    ?>
                    <span>No Results Found</span>
                    <?php
                }
            } else if ($vwtyp == 2) {
                //Prsn Set Detail                
                $sbmtdPrsnSetHdrID = isset($_POST['sbmtdPrsnSetHdrID']) ? $_POST['sbmtdPrsnSetHdrID'] : -1;
                $result = get_PrsnSetDetail($sbmtdPrsnSetHdrID);
                while ($row = loc_db_fetch_array($result)) {
                    ?>
                    <form class="form-horizontal" id='prsSetDetailsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="col-md-4">
                                    <label for="prsnSetNm" class="control-label">Person Set Name:</label>
                                </div>
                                <div class="col-md-8">
                                    <?php if ($canEdtPrsSet === true) { ?>
                                        <input type="text" name="prsnSetNm" id="prsnSetNm" class="form-control" value="<?php echo $row[1]; ?>">
                                        <input type="hidden" name="prsnSetID" id="prsnSetID" class="form-control" value="<?php echo $sbmtdPrsnSetHdrID; ?>">
                                    <?php } else { ?>
                                        <span><?php echo $row[1]; ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="col-md-4">
                                    <label for="prsnSetDesc" class="control-label">Person Set Description:</label>
                                </div>
                                <div class="col-md-8">     
                                    <?php if ($canEdtPrsSet === true) { ?>
                                        <textarea rows="2" name="prsnSetDesc" id="prsnSetDesc" class="form-control"><?php echo $row[2]; ?></textarea>
                                    <?php } else { ?>
                                        <span><?php echo $row[2]; ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="col-md-4">
                                    <div class="checkbox">
                                        <label for="prsnSetUsesSQL" class="control-label">
                                            <?php
                                            $isChkd = "";
                                            $isRdOnly = "disabled=\"true\"";
                                            if ($canEdtPrsSet === true) {
                                                $isRdOnly = "";
                                            }
                                            if ($row[3] == "YES") {
                                                $isChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" name="prsnSetUsesSQL" id="prsnSetUsesSQL" <?php echo $isChkd . " " . $isRdOnly; ?>>Uses SQL</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="checkbox">
                                        <label for="prsnSetEnbld" class="control-label">
                                            <?php
                                            $isChkd = "";
                                            $isRdOnly = "disabled=\"true\"";
                                            if ($canEdtPrsSet === true) {
                                                $isRdOnly = "";
                                            }
                                            if ($row[6] == "YES") {
                                                $isChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" name="prsnSetEnbld" id="prsnSetEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="checkbox">
                                        <label for="prsnSetIsDflt" class="control-label">
                                            <?php
                                            $isChkd = "";
                                            $isRdOnly = "disabled=\"true\"";
                                            if ($canEdtPrsSet === true) {
                                                $isRdOnly = "";
                                            }
                                            if ($row[5] == "YES") {
                                                $isChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" name="prsnSetIsDflt"  id="prsnSetIsDflt" <?php echo $isChkd . " " . $isRdOnly; ?>>Default Person Set?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">                                
                                <div class="col-md-12" style="margin-bottom:5px;">
                                    <label for="prsnSetSQL" class="control-label">Person Set SQL Query: </label>
                                </div>
                                <div class="col-md-12" style="margin-bottom:5px;">
                                    <?php if ($canEdtPrsSet === true) { ?>
                                        <textarea rows="10" name="prsnSetSQL" id="prsnSetSQL" class="form-control"><?php echo $row[4]; ?></textarea>
                                    <?php } else { ?>
                                        <span><?php echo $row[4]; ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="col-md-12">
                                    <?php
                                    if ($canEdtPrsSet === true) {
                                        $nwRowHtml = urlencode("<tr id=\"prsSetAlwdRlsRow__WWW123WWW\">"
                                                . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                . "<td class=\"lovtd\">
                                                                <div class=\"input-group\" style=\"width:100% !important;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"prsSetAlwdRlsRow_WWW123WWW_RoleNm\" name=\"prsSetAlwdRlsRow_WWW123WWW_RoleNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"prsSetAlwdRlsRow_WWW123WWW_RoleID\" value=\"-1\" style=\"width:100% !important;\">                                              
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'User Roles', '', '', '', 'radio', true, '', 'prsSetAlwdRlsRow_WWW123WWW_RoleID', 'prsSetAlwdRlsRow_WWW123WWW_RoleNm', 'clear', 1, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"prsSetAlwdRlsRow_WWW123WWW_PayRoleID\" value=\"-1\" style=\"width:100% !important;\">                                                             
                                                            </td>  
                                                            <td class=\"lovtd\">
                                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRoleSet('prsSetAlwdRlsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Role Set\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                </button>
                                                            </td>
                                        </tr>");
                                        ?> 
                                        <div class="" style="float:right !important;padding-right: 1px;">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('prsSetAllwdRolesTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Role">
                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add Role Set
                                            </button>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="savePrsnSetForm('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&srcMdl=<?php echo $srcMdl; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Saves Person Set">
                                                <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Save Person Set
                                            </button>
                                        </div>
                                    <?php } else { ?>                                        
                                        <label class="control-label">Permitted / Allowed Role Sets:</label>
                                    <?php } ?>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="prsSetAllwdRolesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Role Set Name</th>
                                                <?php if ($canEdtPrsSet === true) { ?>
                                                    <th>&nbsp;</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cntr = 0;
                                            $result2 = get_AllRoles1($sbmtdPrsnSetHdrID);
                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="prsSetAlwdRlsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                    <td class="lovtd">
                                                        <span><?php echo $row2[1]; ?></span>
                                                        <input type="hidden" class="form-control" aria-label="..." id="prsSetAlwdRlsRow<?php echo $cntr; ?>_PayRoleID" value="<?php echo $row2[2]; ?>" style="width:100% !important;">                                              
                                                    </td>     
                                                    <?php if ($canEdtPrsSet === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRoleSet('prsSetAlwdRlsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Role Set">
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
            } else if ($vwtyp == 3) {
                //New Person Set
                ?>              
                <form class="form-horizontal" id='prsSetDetailsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-md-4">
                                <label for="prsnSetNm" class="control-label">Person Set Name:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="prsnSetNm" id="prsnSetNm" class="form-control" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-md-4">
                                <label for="prsnSetDesc" class="control-label">Person Set Description:</label>
                            </div>
                            <div class="col-md-8">   
                                <textarea rows="2" name="prsnSetDesc" id="prsnSetDesc" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label for="prsnSetUsesSQL" class="control-label">
                                        <?php
                                        $isChkd = "";
                                        $isRdOnly = "";
                                        ?>
                                        <input type="checkbox" name="prsnSetUsesSQL" id="prsnSetUsesSQL" <?php echo $isChkd . " " . $isRdOnly; ?>>Uses SQL</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label for="prsnSetEnbld" class="control-label">
                                        <?php
                                        $isChkd = "";
                                        $isRdOnly = "";
                                        ?>
                                        <input type="checkbox" name="prsnSetEnbld" id="prsnSetEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label for="prsnSetIsDflt" class="control-label">
                                        <?php
                                        $isChkd = "";
                                        $isRdOnly = "";
                                        ?>
                                        <input type="checkbox" name="prsnSetIsDflt"  id="prsnSetIsDflt" <?php echo $isChkd . " " . $isRdOnly; ?>>Default Person Set?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">                                
                            <div class="col-md-12" style="margin-bottom:5px;">
                                <label for="prsnSetSQL" class="control-label">Person Set SQL Query: </label>
                            </div>
                            <div class="col-md-12" style="margin-bottom:5px;">
                                <textarea rows="10" name="prsnSetSQL" id="prsnSetSQL" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-md-12">
                                <?php
                                $nwRowHtml = urlencode("<tr id=\"prsSetAlwdRlsRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                                                <div class=\"input-group\" style=\"width:100% !important;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"prsSetAlwdRlsRow_WWW123WWW_RoleNm\" name=\"prsSetAlwdRlsRow_WWW123WWW_RoleNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"prsSetAlwdRlsRow_WWW123WWW_RoleID\" value=\"-1\" style=\"width:100% !important;\">                                              
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'User Roles', '', '', '', 'radio', true, '', 'prsSetAlwdRlsRow_WWW123WWW_RoleID', 'prsSetAlwdRlsRow_WWW123WWW_RoleNm', 'clear', 1, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"prsSetAlwdRlsRow_WWW123WWW_PayRoleID\" value=\"-1\" style=\"width:100% !important;\">                                                             
                                                            </td>  
                                                            <td class=\"lovtd\">
                                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRoleSet('prsSetAlwdRlsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Role Set\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                </button>
                                                            </td>
                                        </tr>");
                                ?> 
                                <div class="" style="float:right !important;padding-right: 1px;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('prsSetAllwdRolesTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Role">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add Role Set
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="savePrsnSetForm('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&srcMdl=<?php echo $srcMdl; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Saves Person Set">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Save Person Set
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="prsSetAllwdRolesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Role Set Name</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        $cntr += 1;
                                        ?>
                                        <tr id="prsSetAlwdRlsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                            <td class="lovtd">
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="prsSetAlwdRlsRow<?php echo $cntr; ?>_RoleNm" name="prsSetAlwdRlsRow<?php echo $cntr; ?>_RoleNm" value="" readonly="true" style="width:100% !important;">
                                                    <input type="hidden" class="form-control" aria-label="..." id="prsSetAlwdRlsRow<?php echo $cntr; ?>_RoleID" value="-1" style="width:100% !important;">                                              
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'User Roles', '', '', '', 'radio', true, '', 'prsSetAlwdRlsRow<?php echo $cntr; ?>_RoleID', 'prsSetAlwdRlsRow<?php echo $cntr; ?>_RoleNm', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div> 
                                                <input type="hidden" class="form-control" aria-label="..." id="prsSetAlwdRlsRow<?php echo $cntr; ?>_PayRoleID" value="" style="width:100% !important;">                                              
                                            </td> 
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRoleSet('prsSetAlwdRlsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Role Set">
                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                        </tr>
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
