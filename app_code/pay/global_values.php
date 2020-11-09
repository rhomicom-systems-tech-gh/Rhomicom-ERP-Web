<?php
$canAdd = test_prmssns($dfltPrvldgs[35], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[36], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[37], $mdlNm);
$canVwRcHstry = test_prmssns($dfltPrvldgs[7], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Global Value */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteGBV($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Global Value Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteGBVLn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Global Value
                header("content-type:application/json");
                //var_dump($_POST);
                //exit();
                $payGlblValsID = isset($_POST['payGlblValsID']) ? (int) cleanInputData($_POST['payGlblValsID']) : -1;
                $payGlblValsName = isset($_POST['payGlblValsName']) ? cleanInputData($_POST['payGlblValsName']) : "";
                $payGlblValsDesc = isset($_POST['payGlblValsDesc']) ? cleanInputData($_POST['payGlblValsDesc']) : "";
                $payGlblValsCritType = isset($_POST['payGlblValsCritType']) ? cleanInputData($_POST['payGlblValsCritType']) : "";

                $payGlblValsIsEnbld = isset($_POST['payGlblValsIsEnbld']) ? (cleanInputData($_POST['payGlblValsIsEnbld']) == "YES" ? TRUE : FALSE) : FALSE;
                $slctdGlobalVals = isset($_POST['slctdGlobalVals']) ? cleanInputData($_POST['slctdGlobalVals']) : "";

                $exitErrMsg = "";
                if ($payGlblValsName == "") {
                    $exitErrMsg .= "Please enter Global Value Name!<br/>";
                }
                if ($payGlblValsCritType == "") {
                    $exitErrMsg .= "Please enter Global Value Type!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['payGlblValsID'] = $payGlblValsID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldID = getGBVID($payGlblValsName, $orgID);
                if (($oldID <= 0 || $oldID == $payGlblValsID)) {
                    if ($payGlblValsID <= 0) {
                        createGBVHdr($orgID, $payGlblValsName, $payGlblValsDesc, $payGlblValsCritType, $payGlblValsIsEnbld);
                        $payGlblValsID = getGBVID($payGlblValsName, $orgID);
                    } else {
                        updateGBVHdr($payGlblValsID, $payGlblValsName, $payGlblValsDesc, $payGlblValsCritType, $payGlblValsIsEnbld);
                    }

                    $afftctd = 0;
                    $afftctd1 = 0;
                    $afftctd2 = 0;
                    if (trim($slctdGlobalVals, "|~") != "") {
                        $variousRows = explode("|", trim($slctdGlobalVals, "|"));
                        for ($y = 0; $y < count($variousRows); $y++) {
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 7) {
                                $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_CrtriaType = cleanInputData1($crntRow[1]);
                                $ln_CrtriaID = (int) cleanInputData1($crntRow[2]);
                                $ln_CrtriaNm = cleanInputData1($crntRow[3]);
                                $ln_GBVAmnt = (float) cleanInputData1($crntRow[4]);
                                $ln_StrtDte = cleanInputData1($crntRow[5]);
                                if (trim($ln_StrtDte) === "" || strpos($ln_StrtDte, "1899") !== FALSE || strlen(trim($ln_StrtDte)) != 20) {
                                    $ln_StrtDte = $gnrlTrnsDteDMYHMS;
                                }
                                $ln_EndDte = cleanInputData1($crntRow[6]);
                                if (trim($ln_EndDte) === "") {
                                    $ln_EndDte = "31-Dec-4000 23:59:59";
                                }
                                $errMsg = "";
                                $ln_OldTrnsLnID = getGBVLnID($payGlblValsID, $ln_CrtriaID, $ln_CrtriaType, $ln_StrtDte);
                                //var_dump("ln_OldTrnsLnID:" . $ln_OldTrnsLnID . ":ln_TrnsLnID:" . $ln_TrnsLnID . ":ln_CrtriaID:" . $ln_CrtriaID . ":ln_CrtriaType:" . $ln_CrtriaType . ":ln_StrtDte:" . $ln_StrtDte . "");
                                if ($ln_TrnsLnID <= 0 && $ln_OldTrnsLnID <= 0 && ($ln_CrtriaID > 0 || $ln_CrtriaType == "Person Type")) {
                                    $afftctd += createGBVLn($payGlblValsID, $ln_CrtriaID, $ln_CrtriaType, $ln_StrtDte, $ln_EndDte,
                                            $ln_GBVAmnt);
                                } else if ($ln_TrnsLnID > 0 && ($ln_CrtriaID > 0 || $ln_CrtriaType == "Person Type")) {
                                    $afftctd += updateGBVLn($ln_TrnsLnID, $ln_CrtriaID, $ln_CrtriaType, $ln_StrtDte, $ln_EndDte, $ln_GBVAmnt);
                                }
                            }
                        }
                    }

                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Global Value Successfully Saved!"
                                . "<br/>" . $afftctd . " Global Value Line(s) Saved Successfully!"
                                . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Global Value Successfully Saved!"
                                . "<br/>" . $afftctd . " Global Value Line(s) Saved Successfully!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['payGlblValsID'] = $payGlblValsID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Global Value Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['payGlblValsID'] = $payGlblValsID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0) {
                $pkID = isset($_POST['sbmtdGBVID']) ? $_POST['sbmtdGBVID'] : -1;
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Global Values</span>
				</li>
                               </ul>
                              </div>";

                $total = get_Total_GBV($srchFor, $srchIn, $orgID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_Basic_GBV($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>
                <form id='payGlblValsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php if ($canAdd === true) { ?> 
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 0px 0px 0px !important;"> 
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePayGlblValsForm(-1, 1);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Bulk/Mass Pay Run">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Global Value
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="savePayGlblValsForm();" style="width:100% !important;">
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
                                <input class="form-control" id="payGlblValsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncPayGlblVals(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="payGlblValsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getPayGlblVals('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getPayGlblVals('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="payGlblValsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Global Value Name", "Global Value Description");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="payGlblValsDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "", "");
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
                                        <a class="rhopagination" href="javascript:getPayGlblVals('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getPayGlblVals('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row"> 
                        <div  class="col-md-3" style="padding:0px 1px 0px 15px !important;">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="payGlblValsTable" cellspacing="0" width="100%" style="width:100% !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Global Value Set Name</th>
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
                                            <tr id="payGlblValsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="payGlblValsRow<?php echo $cntr; ?>_GBVID" value="<?php echo $row[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="payGlblValsRow<?php echo $cntr; ?>_GBVNm" value="<?php echo $row[1]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayGlblVals('payGlblValsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Set">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row[0] . "|pay.pay_global_values_hdr|global_val_id"),
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
                                <div class="container-fluid" id="payGlblValsDetailInfo">
                                    <?php
                                    $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                                    if ($pkID > 0) {
                                        $result1 = get_OneBasic_GBV($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $payGlblValsID = $row1[0];
                                            $payGlblValsName = $row1[1];
                                            $payGlblValsDesc = $row1[2];
                                            $payGlblValsIsEnbld = $row1[3];
                                            $payGlblValsCritType = $row1[4];
                                            ?>
                                            <div class="row">
                                                <div  class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payGlblValsName" class="control-label col-lg-4">Pay Run Name:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="payGlblValsName" name="payGlblValsName" value="<?php echo $payGlblValsName; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="payGlblValsID" name="payGlblValsID" value="<?php echo $payGlblValsID; ?>">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $payGlblValsName; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payGlblValsDesc" class="control-label col-lg-4">Description:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="payGlblValsDesc" name="payGlblValsDesc" value="<?php echo $payGlblValsDesc; ?>" style="width:100% !important;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $payGlblValsDesc; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div> 
                                                    </fieldset>
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:6px 0px 0px 0px !important;">
                                                            <label for="payGlblValsStatus" class="control-label col-md-4">Status:</label>
                                                            <div  class="col-md-4">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <?php
                                                                        $payGlblValsIsEnbldChkd = "";
                                                                        if ($payGlblValsIsEnbld == "1") {
                                                                            $payGlblValsIsEnbldChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="payGlblValsIsEnbld" name="payGlblValsIsEnbld" <?php echo $payGlblValsIsEnbldChkd; ?>>
                                                                        Is Enabled
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payGlblValsCritType" class="control-label col-md-4">Criteria Type:</label>
                                                            <div  class="col-md-8"> 
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="payGlblValsCritType" style="width:100% !important;">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "", "", "");
                                                                    $srchInsArrys = array("Divisions/Groups", "Grade", "Job", "Position", "Site/Location",
                                                                        "Person Type");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($payGlblValsCritType == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;"> 
                                                    <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                                                        <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                        <?php
                                                                        if ($canEdt) {
                                                                            $nwRowHtml2 = "<tr id=\"payGlblValsRunDetsRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#payGlblValsRunDetsTable tr').index(this));\">"
                                                                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                                                    . "<td class=\"lovtd\"  style=\"\">  
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"payGlblValsRunDetsRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"payGlblValsRunDetsRow_WWW123WWW_CrtriaType\" style=\"width:100% !important;\" onchange=\"payGlblValsGrpChange('payGlblValsRunDetsRow_WWW123WWW_CrtriaNm', 'payGlblValsRunDetsRow_WWW123WWW_CrtriaID');\">";

                                                                            $valslctdArry = array("", "", "", "", "", "");
                                                                            $srchInsArrys = array("Divisions/Groups", "Grade", "Job", "Position",
                                                                                "Site/Location", "Person Type");
                                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                $nwRowHtml2 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                                            }
                                                                            $nwRowHtml2 .= "</select>                                            
                                                                                        </td>         
                                                                                        <td class=\"lovtd\">
                                                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"payGlblValsRunDetsRow_WWW123WWW_CrtriaNm\" name=\"payGlblValsRunDetsRow_WWW123WWW_CrtriaNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"payGlblValsRunDetsRow_WWW123WWW_CrtriaID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                                <label id=\"payGlblValsRunDetsRow_WWW123WWW_GrpNmLbl\" class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getNoticeLovsTblr('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payGlblValsRunDetsRow_WWW123WWW_CrtriaID', 'payGlblValsRunDetsRow_WWW123WWW_CrtriaNm', 'clear', 1, '', 'payGlblValsRunDetsRow_WWW123WWW_CrtriaType');\">
                                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                </label>
                                                                                            </div> 
                                                                                        </td> 
                                                                                        <td class=\"lovtd\" style=\"text-align: right;\">
                                                                                            <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"payGlblValsRunDetsRow_WWW123WWW_GBVAmnt\" name=\"payGlblValsRunDetsRow_WWW123WWW_GBVAmnt\" value=\"0.00\" style=\"width:100% !important;font-weight:bold;font-size:12px;text-align: right;\">
                                                                                        </td>   
                                                                                        <td class=\"lovtd\" style=\"\">
                                                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"payGlblValsRunDetsRow_WWW123WWW_StrtDte\" value=\"\" style=\"width:100%;\">
                                                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                            </div>                                                 
                                                                                        </td>
                                                                                        <td class=\"lovtd\" style=\"\">
                                                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"payGlblValsRunDetsRow_WWW123WWW_EndDte\" value=\"\" style=\"width:100%;\">
                                                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                            </div>                                                
                                                                                        </td>";
                                                                            if ($canDel === true && $canEdt === true) {
                                                                                $nwRowHtml2 .= "<td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPayGlblValsLn('payGlblValsRunDetsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Line\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>";
                                                                            }
                                                                            if ($canVwRcHstry === true) {
                                                                                $nwRowHtml2 .= "<td class=\"lovtd\">&nbsp;</td>";
                                                                            }
                                                                            $nwRowHtml2 .= "</tr>";
                                                                            $nwRowHtml2 = urlencode($nwRowHtml2);
                                                                            ?>
                                                                            <div class="col-md-2" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                                <button id="addNwJrnlBatchDetBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewPayGlblValsRows('payGlblValsRunDetsTable', 0, '<?php echo $nwRowHtml2; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Detailed Transaction Line">
                                                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>  
                                                                            </div>               
                                                                        <?php } ?>
                                                                        <div class="col-md-7" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                            <div class="col-md-10" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="payGlblValsDtSrchFor" type = "text" placeholder="Search For" value="<?php
                                                                                    echo trim(str_replace("%", " ", $srchFor));
                                                                                    ?>" onkeyup="enterKeyFuncPayGlblValsDt(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                                                    <input id="payGlblValsDtPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getPayGlblValsDt('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                                                        <span class="glyphicon glyphicon-remove"></span>
                                                                                    </label>
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getPayGlblValsDt('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                                                        <span class="glyphicon glyphicon-search"></span>
                                                                                    </label>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="payGlblValsDtSrchIn">
                                                                                        <?php
                                                                                        $valslctdArry = array("");
                                                                                        $srchInsArrys = array("Criteria Type/Name");

                                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                            if ($srchIn == $srchInsArrys[$z]) {
                                                                                                $valslctdArry[$z] = "selected";
                                                                                            }
                                                                                            ?>
                                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="payGlblValsDtDsplySze" style="min-width:70px !important;">                            
                                                                                        <?php
                                                                                        $valslctdArry = array("", "", "", "", "", "", "", "",
                                                                                            "", "", "", "");
                                                                                        $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500,
                                                                                            1000, 5000, 10000, 10000000);
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
                                                                            <div class="col-md-2" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                                <nav aria-label="Page navigation">
                                                                                    <ul class="pagination" style="margin: 0px !important;">
                                                                                        <li>
                                                                                            <a class="rhopagination" href="javascript:getPayGlblValsDt('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>');" aria-label="Previous">
                                                                                                <span aria-hidden="true">&laquo;</span>
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a class="rhopagination" href="javascript:getPayGlblValsDt('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>');" aria-label="Next">
                                                                                                <span aria-hidden="true">&raquo;</span>
                                                                                            </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </nav>
                                                                            </div>
                                                                        </div>                    
                                                                    </div> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="onePayGlblValsLnsTblSctn"> 
                                                        <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                            <div id="payGlblValsRunDets" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <table class="table table-striped table-bordered table-responsive" id="payGlblValsRunDetsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No.</th>
                                                                                    <th>Criteria Type</th>
                                                                                    <th>Criteria Name</th>
                                                                                    <th style="text-align: right;">Value Amount</th>
                                                                                    <th>Valid From</th>
                                                                                    <th style="">Valid Till</th>
                                                                                    <th>...</th>
                                                                                    <?php if ($canVwRcHstry) { ?>
                                                                                        <th>...</th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>   
                                                                                <?php
                                                                                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                                                                                $total = get_Total_GBVDet($srchFor, $srchIn, $pkID);
                                                                                if ($pageNo > ceil($total / $lmtSze)) {
                                                                                    $pageNo = 1;
                                                                                } else if ($pageNo < 1) {
                                                                                    $pageNo = ceil($total / $lmtSze);
                                                                                }

                                                                                $curIdx = $pageNo - 1;
                                                                                $resultRw = get_One_GBVDet($srchFor, $srchIn, $curIdx,
                                                                                        $lmtSze, $pkID);
                                                                                $cntr = 0;
                                                                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                    $trsctnLineID = (float) $rowRw[0];
                                                                                    $trsctnLineCritrTyp = $rowRw[1];
                                                                                    $trsctnLineCritrID = (float) $rowRw[2];
                                                                                    $trsctnLineCritrNm = $rowRw[3];
                                                                                    $trsctnLineAmnt = (float) $rowRw[4];
                                                                                    $trsctnLineStrtDte = $rowRw[5];
                                                                                    $trsctnLineEndDte = $rowRw[6];
                                                                                    $cntr += 1;
                                                                                    ?>
                                                                                    <tr id="payGlblValsRunDetsRow_<?php echo $cntr; ?>">                                    
                                                                                        <td class="lovtd">
                                                                                            <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                                                                        </td>                                            
                                                                                        <td class="lovtd"  style="">  
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="payGlblValsRunDetsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaType" style="width:100% !important;" onchange="payGlblValsGrpChange('payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaNm', 'payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaID');">
                                                                                                <?php
                                                                                                $valslctdArry = array("", "", "", "", "", "");
                                                                                                $srchInsArrys = array("Divisions/Groups", "Grade",
                                                                                                    "Job", "Position", "Site/Location", "Person Type");
                                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                    if ($trsctnLineCritrTyp == $srchInsArrys[$z]) {
                                                                                                        $valslctdArry[$z] = "selected";
                                                                                                    }
                                                                                                    ?>
                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>                                            
                                                                                        </td>         
                                                                                        <td class="lovtd">
                                                                                            <div class="input-group" style="width:100% !important;">
                                                                                                <input type="text" class="form-control" aria-label="..." id="payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaNm" name="payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaNm" value="<?php echo $trsctnLineCritrNm; ?>" readonly="true" style="width:100% !important;">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaID" value="<?php echo $trsctnLineCritrID; ?>" style="width:100% !important;">
                                                                                                <label id="payGlblValsRunDetsRow<?php echo $cntr; ?>_GrpNmLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovsTblr('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaID', 'payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaNm', 'clear', 1, '', 'payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaType');">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div> 
                                                                                        </td> 
                                                                                        <td class="lovtd" style="text-align: right;">
                                                                                            <input type="text" class="form-control jbDetDbt" aria-label="..." id="payGlblValsRunDetsRow<?php echo $cntr; ?>_GBVAmnt" name="payGlblValsRunDetsRow<?php echo $cntr; ?>_GBVAmnt" value="<?php
                                                                                            echo number_format($trsctnLineAmnt, 2);
                                                                                            ?>" style="width:100% !important;font-weight:bold;font-size:12px;text-align: right;">
                                                                                        </td>   
                                                                                        <td class="lovtd" style="">
                                                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                                <input class="form-control" size="16" type="text" id="payGlblValsRunDetsRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $trsctnLineStrtDte; ?>" style="width:100%;">
                                                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                            </div>                                                 
                                                                                        </td>
                                                                                        <td class="lovtd" style="">
                                                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                                <input class="form-control" size="16" type="text" id="payGlblValsRunDetsRow<?php echo $cntr; ?>_EndDte" value="<?php echo $trsctnLineEndDte; ?>" style="width:100%;">
                                                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                            </div>                                                
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayGlblValsLn('payGlblValsRunDetsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Line">
                                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                        </td>
                                                                                        <?php
                                                                                        if ($canVwRcHstry === true) {
                                                                                            ?>
                                                                                            <td class="lovtd">
                                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                echo urlencode(encrypt1(($trsctnLineID . "|pay.pay_global_values_det|value_det_id"),
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
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $pkID = isset($_POST['sbmtdGBVID']) ? $_POST['sbmtdGBVID'] : -1;

                $payGlblValsID = -1;
                $payGlblValsName = "";
                $payGlblValsDesc = "";
                $payGlblValsIsEnbld = "0";
                $payGlblValsCritType = "";
                if ($pkID > 0) {
                    $result1 = get_OneBasic_GBV($pkID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $payGlblValsID = $row1[0];
                        $payGlblValsName = $row1[1];
                        $payGlblValsDesc = $row1[2];
                        $payGlblValsIsEnbld = $row1[3];
                        $payGlblValsCritType = $row1[4];
                    }
                }
                ?>
                <div class="row">
                    <div  class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="payGlblValsName" class="control-label col-lg-4">Pay Run Name:</label>
                                <div  class="col-lg-8">
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="payGlblValsName" name="payGlblValsName" value="<?php echo $payGlblValsName; ?>" style="width:100% !important;">
                                        <input type="hidden" class="form-control" aria-label="..." id="payGlblValsID" name="payGlblValsID" value="<?php echo $payGlblValsID; ?>">
                                    <?php } else {
                                        ?>
                                        <span><?php echo $payGlblValsName; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="payGlblValsDesc" class="control-label col-lg-4">Description:</label>
                                <div  class="col-lg-8">
                                    <?php
                                    if ($canEdt === true) {
                                        ?>
                                        <input type="text" class="form-control" aria-label="..." id="payGlblValsDesc" name="payGlblValsDesc" value="<?php echo $payGlblValsDesc; ?>" style="width:100% !important;">
                                    <?php } else {
                                        ?>
                                        <span><?php echo $payGlblValsDesc; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div> 
                        </fieldset>
                    </div>
                    <div  class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:6px 0px 0px 0px !important;">
                                <label for="payGlblValsStatus" class="control-label col-md-4">Status:</label>
                                <div  class="col-md-4">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <?php
                                            $payGlblValsIsEnbldChkd = "";
                                            if ($payGlblValsIsEnbld == "1") {
                                                $payGlblValsIsEnbldChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" onclick="" id="payGlblValsIsEnbld" name="payGlblValsIsEnbld" <?php echo $payGlblValsIsEnbldChkd; ?>>
                                            Is Enabled
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="payGlblValsCritType" class="control-label col-md-4">Criteria Type:</label>
                                <div  class="col-md-8"> 
                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payGlblValsCritType" style="width:100% !important;">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "");
                                        $srchInsArrys = array("Divisions/Groups", "Grade", "Job", "Position", "Site/Location", "Person Type");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($payGlblValsCritType == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;"> 
                        <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                            <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                            <?php
                                            if ($canEdt) {
                                                $nwRowHtml2 = "<tr id=\"payGlblValsRunDetsRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#payGlblValsRunDetsTable tr').index(this));\">"
                                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                        . "<td class=\"lovtd\"  style=\"\">  
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"payGlblValsRunDetsRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select rqrdFld\" id=\"payGlblValsRunDetsRow_WWW123WWW_CrtriaType\" style=\"width:100% !important;\" onchange=\"payGlblValsGrpChange('payGlblValsRunDetsRow_WWW123WWW_CrtriaNm', 'payGlblValsRunDetsRow_WWW123WWW_CrtriaID');\">";

                                                $valslctdArry = array("", "", "", "", "", "");
                                                $srchInsArrys = array("Divisions/Groups", "Grade", "Job", "Position", "Site/Location", "Person Type");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml2 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml2 .= "</select>                                            
                                                                                        </td>         
                                                                                        <td class=\"lovtd\">
                                                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"payGlblValsRunDetsRow_WWW123WWW_CrtriaNm\" name=\"payGlblValsRunDetsRow_WWW123WWW_CrtriaNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"payGlblValsRunDetsRow_WWW123WWW_CrtriaID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                                <label id=\"payGlblValsRunDetsRow_WWW123WWW_GrpNmLbl\" class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getNoticeLovsTblr('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payGlblValsRunDetsRow_WWW123WWW_CrtriaID', 'payGlblValsRunDetsRow_WWW123WWW_CrtriaNm', 'clear', 1, '', 'payGlblValsRunDetsRow_WWW123WWW_CrtriaType');\">
                                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                </label>
                                                                                            </div> 
                                                                                        </td> 
                                                                                        <td class=\"lovtd\" style=\"text-align: right;\">
                                                                                            <input type=\"text\" class=\"form-control jbDetDbt rqrdFld\" aria-label=\"...\" id=\"payGlblValsRunDetsRow_WWW123WWW_GBVAmnt\" name=\"payGlblValsRunDetsRow_WWW123WWW_GBVAmnt\" value=\"0.00\" style=\"width:100% !important;font-weight:bold;font-size:12px;text-align: right;\">
                                                                                        </td>   
                                                                                        <td class=\"lovtd\" style=\"\">
                                                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"payGlblValsRunDetsRow_WWW123WWW_StrtDte\" value=\"\" style=\"width:100%;\">
                                                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                            </div>                                                 
                                                                                        </td>
                                                                                        <td class=\"lovtd\" style=\"\">
                                                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"payGlblValsRunDetsRow_WWW123WWW_EndDte\" value=\"\" style=\"width:100%;\">
                                                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                            </div>                                                
                                                                                        </td>";
                                                if ($canDel === true && $canEdt === true) {
                                                    $nwRowHtml2 .= "<td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPayGlblValsLn('payGlblValsRunDetsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Line\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>";
                                                }
                                                if ($canVwRcHstry === true) {
                                                    $nwRowHtml2 .= "<td class=\"lovtd\">&nbsp;</td>";
                                                }
                                                $nwRowHtml2 .= "</tr>";
                                                $nwRowHtml2 = urlencode($nwRowHtml2);
                                                ?>
                                                <div class="col-md-2" style="padding:0px 0px 0px 0px !important;float:left;">
                                                    <button id="addNwJrnlBatchDetBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewPayGlblValsRows('payGlblValsRunDetsTable', 0, '<?php echo $nwRowHtml2; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Detailed Transaction Line">
                                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>  
                                                </div>               
                                            <?php } ?>
                                            <div class="col-md-7" style="padding:0px 0px 0px 0px !important;float:left;">
                                                <div class="col-md-10" style="padding:0px 0px 0px 0px !important;float:left;">
                                                    <div class="input-group">
                                                        <input class="form-control" id="payGlblValsDtSrchFor" type = "text" placeholder="Search For" value="<?php
                                                        echo trim(str_replace("%", " ", $srchFor));
                                                        ?>" onkeyup="enterKeyFuncPayGlblValsDt(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                        <input id="payGlblValsDtPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getPayGlblValsDt('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                            <span class="glyphicon glyphicon-remove"></span>
                                                        </label>
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getPayGlblValsDt('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                            <span class="glyphicon glyphicon-search"></span>
                                                        </label>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="payGlblValsDtSrchIn">
                                                            <?php
                                                            $valslctdArry = array("");
                                                            $srchInsArrys = array("Criteria Type/Name");

                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                if ($srchIn == $srchInsArrys[$z]) {
                                                                    $valslctdArry[$z] = "selected";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="payGlblValsDtDsplySze" style="min-width:70px !important;">                            
                                                            <?php
                                                            $valslctdArry = array("", "", "", "", "", "", "", "", "", "", "", "");
                                                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000, 5000, 10000, 10000000);
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
                                                <div class="col-md-2" style="padding:0px 0px 0px 0px !important;float:left;">
                                                    <nav aria-label="Page navigation">
                                                        <ul class="pagination" style="margin: 0px !important;">
                                                            <li>
                                                                <a class="rhopagination" href="javascript:getPayGlblValsDt('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>');" aria-label="Previous">
                                                                    <span aria-hidden="true">&laquo;</span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="rhopagination" href="javascript:getPayGlblValsDt('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>');" aria-label="Next">
                                                                    <span aria-hidden="true">&raquo;</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>                    
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="onePayGlblValsLnsTblSctn"> 
                            <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                <div id="payGlblValsRunDets" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-bordered table-responsive" id="payGlblValsRunDetsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Criteria Type</th>
                                                        <th>Criteria Name</th>
                                                        <th style="text-align: right;">Value Amount</th>
                                                        <th>Valid From</th>
                                                        <th style="">Valid Till</th>
                                                        <th>...</th>
                                                        <?php if ($canVwRcHstry) { ?>
                                                            <th>...</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>   
                                                    <?php
                                                    $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                                                    $total = get_Total_GBVDet($srchFor, $srchIn, $pkID);
                                                    if ($pageNo > ceil($total / $lmtSze)) {
                                                        $pageNo = 1;
                                                    } else if ($pageNo < 1) {
                                                        $pageNo = ceil($total / $lmtSze);
                                                    }

                                                    $curIdx = $pageNo - 1;
                                                    $resultRw = get_One_GBVDet($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                                                    $cntr = 0;
                                                    while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                        $trsctnLineID = (float) $rowRw[0];
                                                        $trsctnLineCritrTyp = $rowRw[1];
                                                        $trsctnLineCritrID = (float) $rowRw[2];
                                                        $trsctnLineCritrNm = $rowRw[3];
                                                        $trsctnLineAmnt = (float) $rowRw[4];
                                                        $trsctnLineStrtDte = $rowRw[5];
                                                        $trsctnLineEndDte = $rowRw[6];
                                                        $cntr += 1;
                                                        ?>
                                                        <tr id="payGlblValsRunDetsRow_<?php echo $cntr; ?>">                                    
                                                            <td class="lovtd">
                                                                <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                                            </td>                                            
                                                            <td class="lovtd"  style="">  
                                                                <input type="hidden" class="form-control" aria-label="..." id="payGlblValsRunDetsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaType" style="width:100% !important;" onchange="payGlblValsGrpChange('payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaNm', 'payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaID');">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "", "", "");
                                                                    $srchInsArrys = array("Divisions/Groups", "Grade", "Job", "Position", "Site/Location",
                                                                        "Person Type");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($trsctnLineCritrTyp == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>                                            
                                                            </td>         
                                                            <td class="lovtd">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaNm" name="payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaNm" value="<?php echo $trsctnLineCritrNm; ?>" readonly="true" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaID" value="<?php echo $trsctnLineCritrID; ?>" style="width:100% !important;">
                                                                    <label id="payGlblValsRunDetsRow<?php echo $cntr; ?>_GrpNmLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovsTblr('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaID', 'payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaNm', 'clear', 1, '', 'payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaType');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div> 
                                                            </td> 
                                                            <td class="lovtd" style="text-align: right;">
                                                                <input type="text" class="form-control jbDetDbt rqrdFld" aria-label="..." id="payGlblValsRunDetsRow<?php echo $cntr; ?>_GBVAmnt" name="payGlblValsRunDetsRow<?php echo $cntr; ?>_GBVAmnt" value="<?php
                                                                echo number_format($trsctnLineAmnt, 2);
                                                                ?>" style="width:100% !important;font-weight:bold;font-size:12px;text-align: right;">
                                                            </td>   
                                                            <td class="lovtd" style="">
                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                    <input class="form-control" size="16" type="text" id="payGlblValsRunDetsRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $trsctnLineStrtDte; ?>" style="width:100%;">
                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>                                                 
                                                            </td>
                                                            <td class="lovtd" style="">
                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                    <input class="form-control" size="16" type="text" id="payGlblValsRunDetsRow<?php echo $cntr; ?>_EndDte" value="<?php echo $trsctnLineEndDte; ?>" style="width:100%;">
                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>                                                
                                                            </td>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayGlblValsLn('payGlblValsRunDetsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Line">
                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                            <?php
                                                            if ($canVwRcHstry === true) {
                                                                ?>
                                                                <td class="lovtd">
                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                    echo urlencode(encrypt1(($trsctnLineID . "|pay.pay_global_values_det|value_det_id"), $smplTokenWord1));
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
                <?php
            } else if ($vwtyp == 101) {
                $pkID = isset($_POST['sbmtdGBVID']) ? $_POST['sbmtdGBVID'] : -1;
                //var_dump($_POST);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="payGlblValsRunDetsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Criteria Type</th>
                                    <th>Criteria Name</th>
                                    <th style="text-align: right;">Value Amount</th>
                                    <th>Valid From</th>
                                    <th style="">Valid Till</th>
                                    <th>...</th>
                                    <?php if ($canVwRcHstry) { ?>
                                        <th>...</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>   
                                <?php
                                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                                $total = get_Total_GBVDet($srchFor, $srchIn, $pkID);
                                if ($pageNo > ceil($total / $lmtSze)) {
                                    $pageNo = 1;
                                } else if ($pageNo < 1) {
                                    $pageNo = ceil($total / $lmtSze);
                                }

                                $curIdx = $pageNo - 1;
                                $resultRw = get_One_GBVDet($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                                $cntr = 0;
                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                    $trsctnLineID = (float) $rowRw[0];
                                    $trsctnLineCritrTyp = $rowRw[1];
                                    $trsctnLineCritrID = (float) $rowRw[2];
                                    $trsctnLineCritrNm = $rowRw[3];
                                    $trsctnLineAmnt = (float) $rowRw[4];
                                    $trsctnLineStrtDte = $rowRw[5];
                                    $trsctnLineEndDte = $rowRw[6];
                                    $cntr += 1;
                                    ?>
                                    <tr id="payGlblValsRunDetsRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd">
                                            <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                        </td>                                            
                                        <td class="lovtd"  style="">  
                                            <input type="hidden" class="form-control" aria-label="..." id="payGlblValsRunDetsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                            <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaType" style="width:100% !important;" onchange="payGlblValsGrpChange('payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaNm', 'payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaID');">
                                                <?php
                                                $valslctdArry = array("", "", "", "", "", "");
                                                $srchInsArrys = array("Divisions/Groups", "Grade", "Job", "Position", "Site/Location", "Person Type");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($trsctnLineCritrTyp == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>                                            
                                        </td>         
                                        <td class="lovtd">
                                            <div class="input-group" style="width:100% !important;">
                                                <input type="text" class="form-control" aria-label="..." id="payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaNm" name="payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaNm" value="<?php echo $trsctnLineCritrNm; ?>" readonly="true" style="width:100% !important;">
                                                <input type="hidden" class="form-control" aria-label="..." id="payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaID" value="<?php echo $trsctnLineCritrID; ?>" style="width:100% !important;">
                                                <label id="payGlblValsRunDetsRow<?php echo $cntr; ?>_GrpNmLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovsTblr('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaID', 'payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaNm', 'clear', 1, '', 'payGlblValsRunDetsRow<?php echo $cntr; ?>_CrtriaType');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div> 
                                        </td> 
                                        <td class="lovtd" style="text-align: right;">
                                            <input type="text" class="form-control jbDetDbt rqrdFld" aria-label="..." id="payGlblValsRunDetsRow<?php echo $cntr; ?>_GBVAmnt" name="payGlblValsRunDetsRow<?php echo $cntr; ?>_GBVAmnt" value="<?php
                                            echo number_format($trsctnLineAmnt, 2);
                                            ?>" style="width:100% !important;font-weight:bold;font-size:12px;text-align: right;">
                                        </td>   
                                        <td class="lovtd" style="">
                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                <input class="form-control" size="16" type="text" id="payGlblValsRunDetsRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $trsctnLineStrtDte; ?>" style="width:100%;">
                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>                                                 
                                        </td>
                                        <td class="lovtd" style="">
                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                <input class="form-control" size="16" type="text" id="payGlblValsRunDetsRow<?php echo $cntr; ?>_EndDte" value="<?php echo $trsctnLineEndDte; ?>" style="width:100%;">
                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>                                                
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayGlblValsLn('payGlblValsRunDetsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Line">
                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                echo urlencode(encrypt1(($trsctnLineID . "|pay.pay_global_values_det|value_det_id"), $smplTokenWord1));
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
                <script type="text/javascript">
                    $("#payGlblValsDtPageNo").val(<?php echo $pageNo; ?>);
                </script>
                <?php
            } else if ($vwtyp == 3) {
                //Test SQL
                header("content-type:application/json");
                $payGlblValsSQL = isset($_POST['payGlblValsSQL']) ? cleanInputData($_POST['payGlblValsSQL']) : "";
                $payGlblValsUnitPrc = isset($_POST['payGlblValsUnitPrc']) ? (float) cleanInputData($_POST['payGlblValsUnitPrc']) : 0.00;
                $payGlblValsQty = isset($_POST['payGlblValsQty']) ? (float) cleanInputData($_POST['payGlblValsQty']) : 0.00;
                $errMsg = "";
                $CalcItemValue = 0.00;
                $boolRes = isTxCdeSQLValid($payGlblValsSQL, $payGlblValsUnitPrc, $payGlblValsQty, $CalcItemValue);
                $arr_content['CalcItemValue'] = $CalcItemValue;
                if (!$boolRes) {
                    $errMsg .= "SQL is NOT valid!";
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-close\" aria-hidden=\"true\"></i>ERROR:" . $errMsg . "</span>";
                } else {
                    $errMsg = "SUCCESS";
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i>" . $errMsg . ": " . number_format($CalcItemValue,
                                    5) . "</span>";
                }
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}    