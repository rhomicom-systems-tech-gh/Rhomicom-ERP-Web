<?php
$canAdd = test_prmssns($dfltPrvldgs[7], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[8], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[9], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Facility Type */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteSrvsTyp($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Facility */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteSrvsTypLn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Price */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deletePriceLn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Tax Codes
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $hotlSrvsTypID = isset($_POST['hotlSrvsTypID']) ? (int) cleanInputData($_POST['hotlSrvsTypID']) : -1;
                $hotlSrvsTypName = isset($_POST['hotlSrvsTypName']) ? cleanInputData($_POST['hotlSrvsTypName']) : "";
                $hotlSrvsTypDesc = isset($_POST['hotlSrvsTypDesc']) ? cleanInputData($_POST['hotlSrvsTypDesc']) : "";
                $hotlSrvsTypType = isset($_POST['hotlSrvsTypType']) ? cleanInputData($_POST['hotlSrvsTypType']) : "";
                $hotlSrvsTypLnkdSalesItmID = isset($_POST['hotlSrvsTypLnkdSalesItmID']) ? (float) cleanInputData($_POST['hotlSrvsTypLnkdSalesItmID'])
                            : -1;
                $hotlSrvsTypLnkdItmTaxID = get_InvItemTaxID($hotlSrvsTypLnkdSalesItmID);
                $hotlSrvsTypLnkdPnltyItmID = isset($_POST['hotlSrvsTypLnkdPnltyItmID']) ? (float) cleanInputData($_POST['hotlSrvsTypLnkdPnltyItmID'])
                            : -1;
                $hotlSrvsTypRqrPnlty = isset($_POST['hotlSrvsTypRqrPnlty']) ? (float) cleanInputData($_POST['hotlSrvsTypRqrPnlty']) : 0;

                $hotlSrvsTypPnltyPrd = isset($_POST['hotlSrvsTypPnltyPrd']) ? (float) cleanInputData($_POST['hotlSrvsTypPnltyPrd']) : 0;

                $hotlSrvsTypIsEnbld = isset($_POST['hotlSrvsTypIsEnbld']) ? (cleanInputData($_POST['hotlSrvsTypIsEnbld']) == "YES" ? TRUE : FALSE)
                            : FALSE;
                $hotlSrvsTypMltplyAdlts = isset($_POST['hotlSrvsTypMltplyAdlts']) ? (cleanInputData($_POST['hotlSrvsTypMltplyAdlts']) == "YES"
                            ? TRUE : FALSE) : FALSE;
                $hotlSrvsTypMltplyChldrn = isset($_POST['hotlSrvsTypMltplyChldrn']) ? (cleanInputData($_POST['hotlSrvsTypMltplyChldrn']) == "YES"
                            ? TRUE : FALSE) : FALSE;
                $slctdSrvsTypDetIDs = isset($_POST['slctdSrvsTypDetIDs']) ? cleanInputData($_POST['slctdSrvsTypDetIDs']) : "";
                $slctdSrvsTypPriceIDs = isset($_POST['slctdSrvsTypPriceIDs']) ? cleanInputData($_POST['slctdSrvsTypPriceIDs']) : "";

                $exitErrMsg = "";
                if ($hotlSrvsTypName == "") {
                    $exitErrMsg .= "Please enter Facility Type Name!<br/>";
                }
                if ($hotlSrvsTypType == "") {
                    $exitErrMsg .= "Please enter Facility Type!<br/>";
                }
                if ($hotlSrvsTypLnkdSalesItmID <= 0) {
                    $exitErrMsg .= "Please enter Linked Sales Item!<br/>";
                }
                if ($hotlSrvsTypRqrPnlty > 0) {
                    if ($hotlSrvsTypLnkdPnltyItmID <= 0) {
                        $exitErrMsg .= 'Linked Penalty Item cannot be empty if Penalty is Required!';
                    }
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['hotlSrvsTypID'] = $hotlSrvsTypID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldID = getSrvsTypID($hotlSrvsTypName, $orgID);
                if (($oldID <= 0 || $oldID == $hotlSrvsTypID)) {
                    if ($hotlSrvsTypID <= 0) {
                        createSrvsTyp($orgID, $hotlSrvsTypName, $hotlSrvsTypDesc, $hotlSrvsTypLnkdSalesItmID, $hotlSrvsTypIsEnbld,
                                $hotlSrvsTypType, $hotlSrvsTypLnkdPnltyItmID, $hotlSrvsTypPnltyPrd, $hotlSrvsTypRqrPnlty,
                                $hotlSrvsTypMltplyAdlts, $hotlSrvsTypMltplyChldrn);
                        $hotlSrvsTypID = getSrvsTypID($hotlSrvsTypName, $orgID);
                    } else {
                        updateSrvsTyp($hotlSrvsTypID, $hotlSrvsTypName, $hotlSrvsTypDesc, $hotlSrvsTypLnkdSalesItmID, $hotlSrvsTypIsEnbld,
                                $hotlSrvsTypType, $hotlSrvsTypLnkdPnltyItmID, $hotlSrvsTypPnltyPrd, $hotlSrvsTypRqrPnlty,
                                $hotlSrvsTypMltplyAdlts, $hotlSrvsTypMltplyChldrn);
                    }

                    $afftctd = 0;
                    $afftctd1 = 0;
                    $afftctd2 = 0;
                    if (trim($slctdSrvsTypDetIDs, "|~") != "") {
                        //Save Petty Cash Double Entry Lines
                        $variousRows = explode("|", trim($slctdSrvsTypDetIDs, "|"));
                        //echo count($variousRows);
                        for ($y = 0; $y < count($variousRows); $y++) {
                            //var_dump($crntRow);
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 7) {
                                $ln_FcltyLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_AssetItmID = (float) cleanInputData1($crntRow[1]);
                                $ln_LineName = cleanInputData1($crntRow[2]);
                                $ln_LineDesc = cleanInputData1($crntRow[3]);
                                $ln_IsEnabled = (cleanInputData1($crntRow[4]) == "YES") ? TRUE : FALSE;
                                $ln_MaxRntOuts = (float) cleanInputData1($crntRow[5]);
                                $ln_NeedsClng = (cleanInputData1($crntRow[6]) == "YES") ? TRUE : FALSE;
                                $errMsg = "";
                                if ($ln_LineName === "") {
                                    $errMsg = "Row " . ($y + 1) . ":- Facility Description and Name are all required Fields!<br/>";
                                }
                                $oldFcltyLnID = getRoomID($ln_LineName, $orgID);
                                if ($errMsg === "") {
                                    if ($ln_FcltyLnID <= 0 && $oldFcltyLnID <= 0) {
                                        $afftctd += createRoom($hotlSrvsTypID, $ln_LineName, $ln_LineDesc, $ln_IsEnabled, $ln_MaxRntOuts,
                                                $ln_NeedsClng, 0, $ln_AssetItmID);
                                    } else if ($ln_FcltyLnID === $oldFcltyLnID || $oldFcltyLnID <= 0) {
                                        $afftctd += updateRoom($ln_FcltyLnID, $ln_LineName, $ln_LineDesc, $ln_IsEnabled, $ln_MaxRntOuts,
                                                $ln_NeedsClng, 0, $ln_AssetItmID);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }

                    if (trim($slctdSrvsTypPriceIDs, "|~") != "") {
                        //Save Petty Cash Double Entry Lines
                        $variousRows = explode("|", trim($slctdSrvsTypPriceIDs, "|"));
                        //echo count($variousRows);
                        for ($y = 0; $y < count($variousRows); $y++) {
                            //var_dump($crntRow);
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 5) {
                                $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_StrtDte = cleanInputData1($crntRow[1]);
                                $ln_EndDte = cleanInputData1($crntRow[2]);
                                $ln_PrcLsTx = (float) cleanInputData1($crntRow[3]);
                                $ln_IsEnbld = (cleanInputData1($crntRow[4]) == "YES") ? TRUE : FALSE;
                                $errMsg = "";
                                if ($ln_StrtDte === "" || $ln_EndDte === "") {
                                    $errMsg = "Row " . ($y + 1) . ":- Start and End Date are all required Fields!<br/>";
                                }
                                $oldTrnsLnID = getPriceID($ln_StrtDte, $ln_EndDte, $hotlSrvsTypID);
                                if ($errMsg === "") {
                                    if ($ln_TrnsLnID <= 0 && $oldTrnsLnID <= 0) {
                                        $afftctd1 += createSpecialPrice($hotlSrvsTypID, $ln_StrtDte, $ln_EndDte, $ln_PrcLsTx, $ln_IsEnbld,
                                                $hotlSrvsTypLnkdItmTaxID);
                                    } else if ($ln_TrnsLnID === $oldTrnsLnID || $oldTrnsLnID <= 0) {
                                        $afftctd1 += updateSpecialPrice($ln_TrnsLnID, $ln_StrtDte, $ln_EndDte, $ln_PrcLsTx, $ln_IsEnbld,
                                                $hotlSrvsTypLnkdItmTaxID);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }
                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Facility Type Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Facility(ies) Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd1 . " Seasonal Price(s) Saved!"
                                . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Facility Type Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Facility(ies) Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd1 . " Seasonal Price(s) Saved!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['hotlSrvsTypID'] = $hotlSrvsTypID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Facility Type Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['hotlSrvsTypID'] = $hotlSrvsTypID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                header("content-type:application/json");
                $ln_RoomID = isset($_POST['ln_RoomID']) ? (int) cleanInputData($_POST['ln_RoomID']) : -1;
                $changType = isset($_POST['changType']) ? cleanInputData($_POST['changType']) : "IsClean";
                $changValue = isset($_POST['changValue']) ? cleanInputData($_POST['changValue']) : "0";
                $afftctd = 0;
                if ($changType === "IsClean") {
                    $isdirty = $changValue === "1" ? true : false;
                    $afftctd += updateRoomCleanStatus($ln_RoomID, $isdirty);
                } else if ($changType === "IsEnabled") {
                    $isblckd = $changValue === "1" ? true : false;
                    $afftctd += updateRoomBlckdStatus($ln_RoomID, $isblckd);
                }

                $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Record(s) Updated!";
                $arr_content['percent'] = 100;
                $arr_content['ln_RoomID'] = $ln_RoomID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            }
        } else {
            if ($vwtyp == 0 || $vwtyp == 1 || $vwtyp == 2) {
                $pkID = isset($_POST['sbmtdSrvsTypID']) ? $_POST['sbmtdSrvsTypID'] : -1;
                $actionTxt = isset($_POST['actionTxt']) ? $_POST['actionTxt'] : "PasteDirect";
                $destElmntID = isset($_POST['destElmntID']) ? $_POST['destElmntID'] : "hotlSrvsTypDetailInfo";
                $titleMsg = isset($_POST['titleMsg']) ? $_POST['titleMsg'] : "";
                $titleElementID = isset($_POST['titleElementID']) ? $_POST['titleElementID'] : "";
                $modalBodyID = isset($_POST['modalBodyID']) ? $_POST['modalBodyID'] : "";
                if ($vwtyp == 0) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Facility Types</span>
				</li>
                               </ul>
                              </div>";

                    $total = get_Ttl_SrvsTyps($srchFor, $srchIn, $orgID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_SrvcTyps($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='hotlSrvsTypForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php if ($canAdd === true) { ?> 
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 0px 0px 0px !important;"> 
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneHotlSrvsTypForm(-1, 1);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Facility/Service Type">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Service
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveHotlSrvsTypForm('<?php echo $actionTxt; ?>', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');" style="width:100% !important;">
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
                                    <input class="form-control" id="hotlSrvsTypSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncHotlSrvsTyp(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="hotlSrvsTypPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getHotlSrvsTyp('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getHotlSrvsTyp('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="hotlSrvsTypSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "");
                                        $srchInsArrys = array("Facility Type Description", "Facility Type Name", "Facility/Activity Name");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="hotlSrvsTypDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getHotlSrvsTyp('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getHotlSrvsTyp('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
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
                                    <table class="table table-striped table-bordered table-responsive" id="hotlSrvsTypTable" cellspacing="0" width="100%" style="width:100% !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Facility Type Name</th>
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
                                                <tr id="hotlSrvsTypRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $row[1]; ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="hotlSrvsTypRow<?php echo $cntr; ?>_SrvsTypID" value="<?php echo $row[0]; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="hotlSrvsTypRow<?php echo $cntr; ?>_SrvsTypNm" value="<?php echo $row[1]; ?>">
                                                    </td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delHotlSrvsTyp('hotlSrvsTypRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Facility">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|hotl.service_types|service_type_id"),
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
                                    <div class="container-fluid" id="hotlSrvsTypDetailInfo">
                                        <?php
                                    }
                                    $hotlSrvsTypID = -1;
                                    $hotlSrvsTypName = "";
                                    $hotlSrvsTypDesc = "";
                                    $hotlSrvsTypType = "";
                                    $hotlSrvsTypIsEnbld = "0";
                                    $hotlSrvsTypMltplyAdlts = "0";
                                    $hotlSrvsTypMltplyChldrn = "0";
                                    $hotlSrvsTypLnkdSalesItmID = -1;
                                    $hotlSrvsTypLnkdSalesItmNm = "";
                                    $hotlSrvsTypLnkdSalesItmPrice = 0.00;
                                    $hotlSrvsTypLnkdPnltyItmID = -1;
                                    $hotlSrvsTypLnkdPnltyItmNm = "";
                                    $hotlSrvsTypLnkdPnltyItmPrice = 0.00;
                                    $hotlSrvsTypRqrPnlty = 0.00;
                                    $hotlSrvsTypPnltyPrd = 0.00;
                                    if ($pkID > 0) {
                                        $hotlSrvsTypID = $pkID;
                                        $result1 = get_One_ServTypeDt($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $hotlSrvsTypID = $row1[0];
                                            $hotlSrvsTypName = $row1[1];
                                            $hotlSrvsTypDesc = $row1[2];
                                            $hotlSrvsTypType = $row1[5];
                                            $hotlSrvsTypIsEnbld = $row1[3];
                                            $hotlSrvsTypMltplyAdlts = $row1[9];
                                            $hotlSrvsTypMltplyChldrn = $row1[10];
                                            $hotlSrvsTypLnkdSalesItmID = $row1[4];
                                            $hotlSrvsTypLnkdSalesItmNm = $row1[11];
                                            $hotlSrvsTypLnkdSalesItmPrice = $row1[13];
                                            $hotlSrvsTypLnkdPnltyItmID = $row1[6];
                                            $hotlSrvsTypLnkdPnltyItmNm = $row1[12];
                                            $hotlSrvsTypLnkdPnltyItmPrice = $row1[14];
                                            $hotlSrvsTypRqrPnlty = $row1[7];
                                            $hotlSrvsTypPnltyPrd = $row1[8];
                                        }
                                    }
                                    if ($vwtyp != 2) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="hotlSrvsTypName" class="control-label col-lg-4">Facility Name:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <input type="text" class="form-control" aria-label="..." id="hotlSrvsTypName" name="hotlSrvsTypName" value="<?php echo $hotlSrvsTypName; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="hotlSrvsTypID" name="hotlSrvsTypID" value="<?php echo $hotlSrvsTypID; ?>">
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $hotlSrvsTypName; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="hotlSrvsTypDesc" class="control-label col-lg-4">Description:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <input type="text" class="form-control" aria-label="..." id="hotlSrvsTypDesc" name="hotlSrvsTypDesc" value="<?php echo $hotlSrvsTypDesc; ?>" style="width:100% !important;min-height:40px !important;height:40px !important;">
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $hotlSrvsTypDesc; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="hotlSrvsTypType" class="control-label col-lg-4">Facility Type:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="hotlSrvsTypType" style="min-width:70px !important;">                            
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "", "");
                                                                    $dsplySzeArry = array("Room/Hall", "Field/Yard", "Restaurant Table", "Gym/Sport Subscription",
                                                                        "Rental Item");
                                                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                        if ($hotlSrvsTypType == $dsplySzeArry[$y]) {
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
                                                                <span><?php echo $hotlSrvsTypType; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>                       
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="hotlSrvsTypLnkdSalesItmNm" class="control-label col-md-4">Sales Item:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <input class="form-control" id="hotlSrvsTypLnkdSalesItmNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" value="<?php echo $hotlSrvsTypLnkdSalesItmNm; ?>" readonly="true"/>
                                                                <input type="hidden" id="hotlSrvsTypLnkdSalesItmID" value="<?php echo $hotlSrvsTypLnkdSalesItmID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'hotlSrvsTypLnkdSalesItmID', 'hotlSrvsTypLnkdSalesItmNm', 'clear', 1, '', function () {});">
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
                                                        <label for="hotlSrvsTypIsEnbld" class="control-label col-lg-6">Is Enabled?:</label>
                                                        <div  class="col-lg-6">
                                                            <?php
                                                            $chkdYes = "";
                                                            $chkdNo = "checked=\"\"";
                                                            if ($hotlSrvsTypIsEnbld == "1") {
                                                                $chkdNo = "";
                                                                $chkdYes = "checked=\"\"";
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <label class="radio-inline"><input type="radio" name="hotlSrvsTypIsEnbld" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                <label class="radio-inline"><input type="radio" name="hotlSrvsTypIsEnbld" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo ($hotlSrvsTypIsEnbld == "1" ? "YES" : "NO"); ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>      
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="hotlSrvsTypMltplyAdlts" class="control-label col-lg-6">Multiply No. of Days by No. of Adults:</label>
                                                        <div  class="col-lg-6">
                                                            <?php
                                                            $chkdYes = "";
                                                            $chkdNo = "checked=\"\"";
                                                            if ($hotlSrvsTypMltplyAdlts == "1") {
                                                                $chkdNo = "";
                                                                $chkdYes = "checked=\"\"";
                                                            }
                                                            ?>
                                                            <?php if ($canEdt === true) { ?>
                                                                <label class="radio-inline"><input type="radio" name="hotlSrvsTypMltplyAdlts" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                <label class="radio-inline"><input type="radio" name="hotlSrvsTypMltplyAdlts" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                            <?php } else { ?>
                                                                <span><?php echo ($hotlSrvsTypMltplyAdlts == "1" ? "YES" : "NO"); ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="hotlSrvsTypMltplyChldrn" class="control-label col-lg-6">Multiply No. of Days by No. of Children:</label>
                                                        <div  class="col-lg-6">
                                                            <?php
                                                            $chkdYes = "";
                                                            $chkdNo = "checked=\"\"";
                                                            if ($hotlSrvsTypMltplyChldrn == "1") {
                                                                $chkdNo = "";
                                                                $chkdYes = "checked=\"\"";
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <label class="radio-inline"><input type="radio" name="hotlSrvsTypMltplyChldrn" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                <label class="radio-inline"><input type="radio" name="hotlSrvsTypMltplyChldrn" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo ($hotlSrvsTypMltplyChldrn == "1" ? "YES" : "NO"); ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>                        
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="hotlSrvsTypLnkdPnltyItmNm" class="control-label col-md-4">Penalty Item:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <input class="form-control" id="hotlSrvsTypLnkdPnltyItmNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" value="<?php echo $hotlSrvsTypLnkdPnltyItmNm; ?>" readonly="true"/>
                                                                <input type="hidden" id="hotlSrvsTypLnkdPnltyItmID" value="<?php echo $hotlSrvsTypLnkdPnltyItmID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'hotlSrvsTypLnkdPnltyItmID', 'hotlSrvsTypLnkdPnltyItmNm', 'clear', 1, '', function () {});">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-12" style="padding:0px 0px 0px 1px !important;">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="col-md-3" style="padding: 5px 2px 0px 15px !important;">
                                                            <label style="margin-bottom:0px !important;">Require No Show Penalty of:</label>
                                                        </div>
                                                        <div class="col-md-1" style="padding: 0px 2px 0px 2px !important;">
                                                            <input type="number" class="form-control" aria-label="..." id="hotlSrvsTypRqrPnlty" name="hotlSrvsTypRqrPnlty" value="<?php echo $hotlSrvsTypRqrPnlty; ?>">
                                                        </div>
                                                        <div class="col-md-3" style="padding: 5px 2px 0px 2px !important;">
                                                            <label style="margin-bottom:0px !important;"> night(s) when cancellation is:</label>
                                                        </div>
                                                        <div class="col-md-1" style="padding: 0px 2px 0px 2px !important;">
                                                            <input type="number" class="form-control" aria-label="..." id="hotlSrvsTypPnltyPrd" name="hotlSrvsTypPnltyPrd" value="<?php echo $hotlSrvsTypPnltyPrd; ?>">
                                                        </div>
                                                        <div class="col-md-4" style="padding: 5px 15px 0px 2px !important;">
                                                            <label style="margin-bottom:0px !important;"> and below day(s) to Start Date.</label>
                                                        </div>
                                                    </div> 
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                                    <li class="active"><a data-toggle="tabajxfcltytyp" data-rhodata="" href="#hotlSrvsTypDetLines" id="hotlSrvsTypDetLinestab">Facilities</a></li>
                                                    <li class=""><a data-toggle="tabajxfcltytyp" data-rhodata="" href="#hotlSrvsTypExtraInfo" id="hotlSrvsTypExtraInfotab">Seasonal Special Prices</a></li>
                                                </ul>  
                                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <?php
                                                            }
                                                            if ($vwtyp == 0 || $vwtyp == 1) {
                                                                $srchFor = "%";
                                                                $srchIn = "Name";
                                                                $pageNo = 1;
                                                                $lmtSze = 30;
                                                            }
                                                            $total = get_ttl_rooms($hotlSrvsTypID, $srchIn, $srchFor);
                                                            if ($pageNo > ceil($total / $lmtSze)) {
                                                                $pageNo = 1;
                                                            } else if ($pageNo < 1) {
                                                                $pageNo = ceil($total / $lmtSze);
                                                            }
                                                            $curIdx = $pageNo - 1;
                                                            $resultRw = get_rooms($hotlSrvsTypID, $curIdx, $lmtSze, $srchIn, $srchFor);
                                                            if ($vwtyp != 2) {
                                                                $nwRowHtml331 = "<tr id=\"oneHotlSrvsTypSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneHotlSrvsTypSmryLinesTable tr').index(this));\">                                    
                                                                                        <td class=\"lovtd\"><span>New</span></td>                                              
                                                                                        <td class=\"lovtd\"  style=\"\">  
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneHotlSrvsTypSmryRow_WWW123WWW_FcltyLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneHotlSrvsTypSmryRow_WWW123WWW_AssetItmID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetRfDc\" aria-label=\"...\" id=\"oneHotlSrvsTypSmryRow_WWW123WWW_LineName\" name=\"oneHotlSrvsTypSmryRow_WWW123WWW_LineName\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneHotlSrvsTypSmryRow_WWW123WWW_LineName', 'oneHotlSrvsTypSmryLinesTable', 'jbDetRfDc');\">
                                                                                        </td> 
                                                                                        <td class=\"lovtd\">
                                                                                            <input type=\"text\" class=\"form-control jbDetDesc\" aria-label=\"...\" id=\"oneHotlSrvsTypSmryRow_WWW123WWW_LineDesc\" name=\"oneHotlSrvsTypSmryRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;text-align: left;\" onkeypress=\"gnrlFldKeyPress(event, 'oneHotlSrvsTypSmryRow_WWW123WWW_LineDesc', 'oneHotlSrvsTypSmryLinesTable', 'jbDetDesc');\">                                                    
                                                                                        </td>                                           
                                                                                        <td class=\"lovtd\" style=\"text-align:center;\">
                                                                                            <div class=\"form-group form-group-sm\">
                                                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                                    <label class=\"form-check-label\">
                                                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"oneHotlSrvsTypSmryRow_WWW123WWW_IsEnabled\" name=\"oneHotlSrvsTypSmryRow_WWW123WWW_IsEnabled\">
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                            <div style=\"background-color:#00FF00;border-radius:3px;padding:1px;\">
                                                                                                <span style=\"color:#000000\">AVAILABLE</span>
                                                                                            </div>                                                  
                                                                                        </td>    
                                                                                        <td class=\"lovtd\" style=\"text-align: right;\">
                                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate\" aria-label=\"...\" id=\"oneHotlSrvsTypSmryRow_WWW123WWW_MaxRntOuts\" name=\"oneHotlSrvsTypSmryRow_WWW123WWW_MaxRntOuts\" value=\"0\" onkeypress=\"gnrlFldKeyPress(event, 'oneHotlSrvsTypSmryRow_WWW123WWW_MaxRntOuts', 'oneHotlSrvsTypSmryLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\">                                                    
                                                                                        </td> 
                                                                                        <td class=\"lovtd\">
                                                                                            <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneHotlSrvsTypSmryRow_WWW123WWW_ActvRntOuts\" name=\"oneHotlSrvsTypSmryRow_WWW123WWW_ActvRntOuts\" value=\"0\" onkeypress=\"gnrlFldKeyPress(event, 'oneHotlSrvsTypSmryRow_WWW123WWW_ActvRntOuts', 'oneHotlSrvsTypSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;background-color:#00FF00;color:#000000;\" readonly=\"true\">                                                    
                                                                                        </td>                                           
                                                                                        <td class=\"lovtd\" style=\"text-align:center;\">
                                                                                            <div class=\"form-group form-group-sm\">
                                                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                                    <label class=\"form-check-label\">
                                                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"oneHotlSrvsTypSmryRow_WWW123WWW_NeedsClng\" name=\"oneHotlSrvsTypSmryRow_WWW123WWW_NeedsClng\">
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td> 
                                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneHotlSrvsTypSmryRow_WWW123WWW_AssetDesc\" name=\"oneHotlSrvsTypSmryRow_WWW123WWW_AssetDesc\" value=\"\" style=\"width:100% !important;\">
                                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneHotlSrvsTypSmryRow_WWW123WWW_AssetItmID', 'oneHotlSrvsTypSmryRow_WWW123WWW_AssetDesc', 'clear', 0, '', function () {});\">
                                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" id=\"oneHotlSrvsTypSmryRow_WWW123WWW_MRecsBtn\" 
                                                                                                    onclick=\"\" style=\"padding:2px !important;\" title=\"Asset's Measured Records\"> 
                                                                                                <img src=\"cmn_images/accounts_mn.jpg\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                          
                                                                                            </button>
                                                                                        </td>
                                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delHotlSrvsTypLne('oneHotlSrvsTypSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Facility\">
                                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                            </button>
                                                                                        </td>
                                                                                    </tr>";
                                                                $nwRowHtml33 = urlencode($nwRowHtml331);

                                                                $nwRowHtml332 = "<tr id=\"oneHotlSrvsTypPricesRow__WWW123WWW\">                                       
                                                                                            <td class=\"lovtd\"><span>New</span></td>                                              
                                                                                            <td class=\"lovtd\"  style=\"\"> 
                                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneHotlSrvsTypPricesRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100% !important;\">
                                                                                                        <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneHotlSrvsTypPricesRow_WWW123WWW_StrtDte\" value=\"\">
                                                                                                        <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                                        <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                                </div>
                                                                                            </td> 
                                                                                            <td class=\"lovtd\" style=\"text-align: right;\">
                                                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100% !important;\">
                                                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneHotlSrvsTypPricesRow_WWW123WWW_EndDte\" value=\"\">
                                                                                                    <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                                </div>
                                                                                            </td> 
                                                                                            <td class=\"lovtd\" style=\"text-align:right;\">
                                                                                                <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneHotlSrvsTypPricesRow_WWW123WWW_PrcLsTx\" name=\"oneHotlSrvsTypPricesRow_WWW123WWW_PrcLsTx\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneHotlSrvsTypSmryRow_WWW123WWW_PrcLsTx', 'oneHotlSrvsTypPricesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\">   
                                                                                            </td>                                          
                                                                                            <td class=\"lovtd\" style=\"text-align:center;\">
                                                                                                <div class=\"form-group form-group-sm\">
                                                                                                    <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                                        <label class=\"form-check-label\">
                                                                                                            <input type=\"checkbox\" class=\"form-check-input\" id=\"oneHotlSrvsTypPricesRow_WWW123WWW_IsEnbld\" name=\"oneHotlSrvsTypPricesRow_WWW123WWW_IsEnbld\">
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td> 
                                                                                            <td class=\"lovtd\" style=\"text-align:right;\">
                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneHotlSrvsTypPricesRow_WWW123WWW_SellPrc\" name=\"oneHotlSrvsTypPricesRow_WWW123WWW_SellPrc\" value=\"0.00\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">   
                                                                                            </td>
                                                                                            <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delHotlSrvsTypPrice('oneHotlSrvsTypPricesRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Facility Price\">
                                                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                                </button>
                                                                                            </td>  
                                                                                        </tr>";
                                                                $nwRowHtml32 = urlencode($nwRowHtml332);
                                                                ?> 
                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                    <div class="col-md-4" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <input type="hidden" id="nwSalesDocLineHtm" value="<?php echo $nwRowHtml33; ?>">
                                                                            <button id="addNwScmHotlSrvsTypSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewHotlSrvsTypRows('oneHotlSrvsTypSmryLinesTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Facility">
                                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>  
                                                                            <button id="addNwScmHotlSrvsTypPriceBtn" type="button" class="btn btn-default hideNotice" style="margin-bottom: 1px;height:30px;" onclick="insertNewHotlSrvsTypRows('oneHotlSrvsTypPricesTable', 0, '<?php echo $nwRowHtml32; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Special Price">
                                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button> 
                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveHotlSrvsTypForm('ReloadDialog', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                                                        <?php } ?>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneHotlSrvsTypForm(<?php echo $hotlSrvsTypID; ?>, 1, 'ReloadDialog', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>    
                                                                    </div>
                                                                    <div class="col-md-6 fcltyTypDetNav" style="padding:0px 15px 0px 15px !important;">
                                                                        <div class="input-group">
                                                                            <input class="form-control" id="hotlSrvsTypDetSrchFor" type = "text" placeholder="Search For" value="<?php
                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                            ?>" onkeyup="enterKeyFuncHotlSrvsTypDet(event, '', '#hotlSrvsTypDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdSrvsTypID=<?php echo $hotlSrvsTypID; ?>');">
                                                                            <input id="hotlSrvsTypDetPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getHotlSrvsTypDet('clear', '#hotlSrvsTypDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdSrvsTypID=<?php echo $hotlSrvsTypID; ?>');">
                                                                                <span class="glyphicon glyphicon-remove"></span>
                                                                            </label>
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getHotlSrvsTypDet('', '#hotlSrvsTypDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdSrvsTypID=<?php echo $hotlSrvsTypID; ?>');">
                                                                                <span class="glyphicon glyphicon-search"></span>
                                                                            </label>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="hotlSrvsTypDetSrchIn">
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
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="hotlSrvsTypDetDsplySze" style="min-width:70px !important;">                            
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
                                                                    <div class="col-md-2 fcltyTypDetNav">
                                                                        <nav aria-label="Page navigation">
                                                                            <ul class="pagination" style="margin: 0px !important;">
                                                                                <li>
                                                                                    <a class="rhopagination" href="javascript:getHotlSrvsTypDet('previous', '#hotlSrvsTypDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdSrvsTypID=<?php echo $hotlSrvsTypID; ?>');" aria-label="Previous">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a class="rhopagination" href="javascript:getHotlSrvsTypDet('next', '#hotlSrvsTypDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdSrvsTypID=<?php echo $hotlSrvsTypID; ?>');" aria-label="Next">
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
                                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneHotlSrvsTypLnsTblSctn"> 
                                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                        <div id="hotlSrvsTypDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                        <?php } ?>
                                                        <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                            <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                                <table class="table table-striped table-bordered table-responsive" id="oneHotlSrvsTypSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="max-width:30px;width:30px;">No.</th>
                                                                            <th style="min-width:80px;">Facility Name</th>
                                                                            <th style="min-width:120px;">Facility Description</th>
                                                                            <th style="max-width:60px;width:60px;text-align: center;">Enabled?</th>
                                                                            <th style="min-width:80px;width:80px;text-align: center;">Facility Status.</th>
                                                                            <th style="max-width:70px;width:70px;text-align: center;">Max No. of Concurrent Rent Outs</th>
                                                                            <th style="max-width:60px;width:60px;text-align: center;">No. of Active Rent-Outs</th>
                                                                            <th style="max-width:60px;width:60px;text-align: center;">Needs Cleaning?</th>
                                                                            <th style="min-width:100px;">Linked Asset Number</th>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>   
                                                                        <?php
                                                                        $mkReadOnly = "";
                                                                        $cntr = 0;
                                                                        while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                            $fcltyLnID = (float) $rowRw[0];
                                                                            $fcltyLnNm = $rowRw[1];
                                                                            $fcltyLnDesc = $rowRw[2];
                                                                            $fcltyLnIsEnbld = $rowRw[3];
                                                                            $fcltyLnStatus = $rowRw[4];
                                                                            $fcltyLnMaxRentOuts = (float) $rowRw[5];
                                                                            $fcltyLnActvRentOuts = (float) $rowRw[6];
                                                                            $fcltyLnNeedsCleang = $rowRw[7];
                                                                            $fcltyLnAssetItmID = (float) $rowRw[9];
                                                                            $fcltyLnAssetItmNm = $rowRw[10];
                                                                            $cntr += 1;
                                                                            $statusColor = "#000000";
                                                                            $statusBckgrdColor = "";
                                                                            if ($fcltyLnNeedsCleang == "1") {
                                                                                $statusBckgrdColor = "#FFA500";
                                                                            }
                                                                            if ($fcltyLnStatus == "FULLY ISSUED OUT") {
                                                                                $statusBckgrdColor = "#FF0000";
                                                                            } else if ($fcltyLnStatus == "PARTIALLY ISSUED OUT") {
                                                                                $statusBckgrdColor = "#FFC0CB";
                                                                            } else if ($fcltyLnStatus == "OVERLOADED") {
                                                                                $statusBckgrdColor = "#8B0000";
                                                                                $statusColor = "#FFFFFF";
                                                                            } else {
                                                                                $statusBckgrdColor = "#00FF00";
                                                                            }
                                                                            ?>
                                                                            <tr id="oneHotlSrvsTypSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneHotlSrvsTypSmryLinesTable tr').index(this));">                                    
                                                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>                                              
                                                                                <td class="lovtd"  style="">  
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_FcltyLnID" value="<?php echo $fcltyLnID; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_AssetItmID" value="<?php echo $fcltyLnAssetItmID; ?>" style="width:100% !important;">  
                                                                                    <?php
                                                                                    if ($canEdt === true) {
                                                                                        ?>
                                                                                        <input type="text" class="form-control rqrdFld jbDetRfDc" aria-label="..." id="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_LineName" name="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_LineName" value="<?php echo $fcltyLnNm; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_LineName', 'oneHotlSrvsTypSmryLinesTable', 'jbDetRfDc');">
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $trsctnLnDesc; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td> 
                                                                                <td class="lovtd">
                                                                                    <input type="text" class="form-control jbDetDesc" aria-label="..." id="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_LineDesc" name="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $fcltyLnDesc; ?>" style="width:100% !important;text-align: left;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_LineDesc', 'oneHotlSrvsTypSmryLinesTable', 'jbDetDesc');">                                                    
                                                                                </td>                                           
                                                                                <td class="lovtd" style="text-align:center;">
                                                                                    <?php
                                                                                    $isChkd = "";
                                                                                    if ($fcltyLnIsEnbld == "1") {
                                                                                        $isChkd = "checked=\"true\"";
                                                                                    }
                                                                                    ?>
                                                                                    <div class="form-group form-group-sm">
                                                                                        <div class="form-check" style="font-size: 12px !important;">
                                                                                            <label class="form-check-label">
                                                                                                <input type="checkbox" class="form-check-input" id="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_IsEnabled" name="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?>>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <div style="background-color:<?php echo $statusBckgrdColor; ?>;border-radius:3px;padding:1px;">
                                                                                        <span style="color:<?php echo $statusColor; ?>"><?php echo $fcltyLnStatus; ?></span>
                                                                                    </div>                                                  
                                                                                </td>    
                                                                                <td class="lovtd" style="text-align: right;">
                                                                                    <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_MaxRntOuts" name="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_MaxRntOuts" value="<?php
                                                                                    echo $fcltyLnMaxRentOuts;
                                                                                    ?>" onkeypress="gnrlFldKeyPress(event, 'oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_MaxRntOuts', 'oneHotlSrvsTypSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;">                                                    
                                                                                </td> 
                                                                                <td class="lovtd">
                                                                                    <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_ActvRntOuts" name="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_ActvRntOuts" value="<?php
                                                                                    echo $fcltyLnActvRentOuts;
                                                                                    ?>" onkeypress="gnrlFldKeyPress(event, 'oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_ActvRntOuts', 'oneHotlSrvsTypSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;background-color:<?php echo $statusBckgrdColor; ?>;color:<?php echo $statusColor; ?>;" readonly="true">                                                    
                                                                                </td>                                           
                                                                                <td class="lovtd" style="text-align:center;">
                                                                                    <?php
                                                                                    $isChkd = "";
                                                                                    if ($fcltyLnNeedsCleang == "1") {
                                                                                        $isChkd = "checked=\"true\"";
                                                                                    }
                                                                                    ?>
                                                                                    <div class="form-group form-group-sm">
                                                                                        <div class="form-check" style="font-size: 12px !important;">
                                                                                            <label class="form-check-label">
                                                                                                <input type="checkbox" class="form-check-input" id="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_NeedsClng" name="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_NeedsClng" <?php echo $isChkd ?>>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </td> 
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <div class="input-group" style="width:100% !important;">
                                                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_AssetDesc" name="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_AssetDesc" value="<?php echo $fcltyLnAssetItmNm; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?>>
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Numbers', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $fcltyLnAssetItmID; ?>', 'oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_AssetItmID', 'oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_AssetDesc', 'clear', 0, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <button type="button" class="btn btn-default btn-sm" id="oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_MRecsBtn" 
                                                                                            onclick="" style="padding:2px !important;" title="Asset's Measured Records"> 
                                                                                        <img src="cmn_images/accounts_mn.jpg" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                          
                                                                                    </button>
                                                                                </td>
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delHotlSrvsTypLne('oneHotlSrvsTypSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Facility">
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
                                                                            <th>&nbsp;</th>
                                                                            <th>&nbsp;</th>
                                                                            <th>&nbsp;</th>
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
                                                        <script>
                                                            $("#hotlSrvsTypDetPageNo").val(<?php echo $pageNo; ?>);
                                                        </script>
                                                        <?php if ($vwtyp != 2) { ?>
                                                        </div>
                                                        <div id="hotlSrvsTypExtraInfo" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                                            <div class="row"  style="padding:0px 15px 0px 15px;">
                                                                <div class="col-md-12" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-right: 0px !important;">
                                                                    <table class="table table-striped table-bordered table-responsive" id="oneHotlSrvsTypPricesTable" cellspacing="0" width="100%" style="width:100%;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>No.</th>
                                                                                <th>From Date</th>
                                                                                <th>To Date</th>
                                                                                <th style="text-align:right;">Price Less Tax</th>
                                                                                <th style="text-align:center;">Is Enabled?</th>
                                                                                <th style="text-align:right;">Selling Price</th>
                                                                                <th>...</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>   
                                                                            <?php
                                                                            $cntr = 0;
                                                                            $resultRw = get_room_prices($hotlSrvsTypID);
                                                                            $ttlTrsctnEntrdAmnt = 0;
                                                                            $trnsBrkDwnVType = "VIEW";
                                                                            if ($mkReadOnly == "") {
                                                                                $trnsBrkDwnVType = "EDIT";
                                                                            }
                                                                            $scmRealInvcGrndTtl = 0;
                                                                            while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                $trsctnLnID = (float) $rowRw[0];
                                                                                $trsctnLnStrtDte = $rowRw[1];
                                                                                $trsctnLnEndDte = $rowRw[2];
                                                                                $trsctnLnPrcLsTx = (float) $rowRw[3];
                                                                                $trsctnLnSellPrc = (float) $rowRw[5];
                                                                                $trsctnLnIsEnbld = $rowRw[4];
                                                                                $cntr += 1;
                                                                                $style4 = "";
                                                                                $style5 = "";
                                                                                ?>
                                                                                <tr id="oneHotlSrvsTypPricesRow_<?php echo $cntr; ?>">                                       
                                                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                                    <td class="lovtd"  style=""> 
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneHotlSrvsTypPricesRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                                        <?php
                                                                                        if ($canEdt === true) {
                                                                                            ?>
                                                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                                                <input class="form-control" size="16" type="text" id="oneHotlSrvsTypPricesRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $trsctnLnStrtDte; ?>">
                                                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                            </div> 
                                                                                        <?php } else {
                                                                                            ?>
                                                                                            <span><?php echo $trsctnLnStrtDte; ?></span>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td> 
                                                                                    <td class="lovtd" style="text-align: right;">
                                                                                        <?php
                                                                                        if ($canEdt === true) {
                                                                                            ?>
                                                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                                                <input class="form-control" size="16" type="text" id="oneHotlSrvsTypPricesRow<?php echo $cntr; ?>_EndDte" value="<?php echo $trsctnLnEndDte; ?>">
                                                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                            </div> 
                                                                                        <?php } else { ?>
                                                                                            <span><?php echo $trsctnLnEndDte; ?></span>
                                                                                        <?php } ?>
                                                                                    </td> 
                                                                                    <td class="lovtd" style="text-align:right;">
                                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneHotlSrvsTypPricesRow<?php echo $cntr; ?>_PrcLsTx" name="oneHotlSrvsTypPricesRow<?php echo $cntr; ?>_PrcLsTx" value="<?php
                                                                                        echo number_format($trsctnLnPrcLsTx, 2);
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneHotlSrvsTypSmryRow<?php echo $cntr; ?>_PrcLsTx', 'oneHotlSrvsTypPricesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;">   
                                                                                    </td>                                          
                                                                                    <td class="lovtd" style="text-align:center;">
                                                                                        <?php
                                                                                        $isChkd = "";
                                                                                        if ($trsctnLnIsEnbld == "1") {
                                                                                            $isChkd = "checked=\"true\"";
                                                                                        }
                                                                                        ?>
                                                                                        <div class="form-group form-group-sm">
                                                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                                                <label class="form-check-label">
                                                                                                    <input type="checkbox" class="form-check-input" id="oneHotlSrvsTypPricesRow<?php echo $cntr; ?>_IsEnbld" name="oneHotlSrvsTypPricesRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd ?>>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td> 
                                                                                    <td class="lovtd" style="text-align:right;">
                                                                                        <input type="text" class="form-control" aria-label="..." id="oneHotlSrvsTypPricesRow<?php echo $cntr; ?>_SellPrc" name="oneHotlSrvsTypPricesRow<?php echo $cntr; ?>_SellPrc" value="<?php
                                                                                        echo number_format($trsctnLnSellPrc, 2);
                                                                                        ?>" style="width:100% !important;text-align: right;" readonly="true">   
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delHotlSrvsTypPrice('oneHotlSrvsTypPricesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Facility Price">
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
                                        <?php
                                    }

                                    if ($vwtyp == 0) {
                                        ?>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}    