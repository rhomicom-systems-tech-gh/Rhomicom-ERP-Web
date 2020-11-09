<?php
$canAdd = test_prmssns($dfltPrvldgs[74], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[75], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[76], $mdlNm);
$canVwRcHstry = test_prmssns($dfltPrvldgs[32], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete UOM */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteUOMStp($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                header("content-type:application/json");
                $invUOMStpID = isset($_POST['invUOMStpID']) ? (int) cleanInputData($_POST['invUOMStpID']) : -1;
                $invUOMStpName = isset($_POST['invUOMStpName']) ? cleanInputData($_POST['invUOMStpName']) : "";
                $invUOMStpDesc = isset($_POST['invUOMStpDesc']) ? cleanInputData($_POST['invUOMStpDesc']) : "";

                $invUOMStpIsEnbld = isset($_POST['invUOMStpIsEnbld']) ? (cleanInputData($_POST['invUOMStpIsEnbld']) == "YES" ? TRUE : FALSE) : FALSE;
                $exitErrMsg = "";
                if ($invUOMStpName == "") {
                    $exitErrMsg .= "Please enter UOM Name!<br/>";
                }
                if ($invUOMStpDesc == "") {
                    $exitErrMsg .= "Please enter UOM Description!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['invUOMStpID'] = $invUOMStpID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldID = getGnrlRecID("inv.unit_of_measure", "uom_name", "uom_id", $invUOMStpName, $orgID);
                if (($oldID <= 0 || $oldID == $invUOMStpID)) {
                    if ($invUOMStpID <= 0) {
                        createUOMStp($orgID, $invUOMStpName, $invUOMStpDesc, $invUOMStpIsEnbld);
                        $invUOMStpID = getGnrlRecID("inv.unit_of_measure", "uom_name", "uom_id", $invUOMStpName, $orgID);
                    } else {
                        updateUOMStp($invUOMStpID, $invUOMStpName, $invUOMStpDesc, $invUOMStpIsEnbld);
                    }
                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>UOM Successfully Saved!"
                                . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>UOM Successfully Saved!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['invUOMStpID'] = $invUOMStpID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New UOM Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['invUOMStpID'] = $invUOMStpID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0) {
                $pkID = isset($_POST['sbmtdPrdtCgtryID']) ? $_POST['sbmtdPrdtCgtryID'] : -1;
                $subPgNo = isset($_POST['subPgNo']) ? (int) $_POST['subPgNo'] : 0;
                if ($subPgNo == 0) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Unit of Measures</span>
				</li>
                               </ul>
                              </div>";
                    $total = get_Total_UOMStp($srchFor, $srchIn, $orgID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_Basic_UOMStp($srchFor, $srchIn, $curIdx, $lmtSze);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='invUOMStpForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                    <?php if ($canAdd === true) { ?> 
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 0px 0px 0px !important;"> 
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneInvUOMStpForm(-1, 0);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Unit of Measure">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New UOM
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveInvUOMStpForm();" style="width:100% !important;">
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
                                    <input class="form-control" id="invUOMStpSrchFor" type = "text" placeholder="Search For" value="<?php
                                           echo trim(str_replace("%", " ", $srchFor));
                                           ?>" onkeyup="enterKeyFuncInvUOMStp(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="invUOMStpPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getInvUOMStp('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getInvUOMStp('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="invUOMStpSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("UOM Name", "UOM Description");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                    <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="invUOMStpDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getInvUOMStp('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getInvUOMStp('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                        <div class="row"> 
                            <div  class="col-md-6" style="padding:0px 1px 0px 15px !important;">
                                <fieldset class="basic_person_fs">                                        
                                    <table class="table table-striped table-bordered table-responsive" id="invUOMStpTable" cellspacing="0" width="100%" style="width:100% !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>UOM Name</th>
                                                <th>UOM Description</th>
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
                                                <tr id="invUOMStpRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $row[1]; ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="invUOMStpRow<?php echo $cntr; ?>_UOMStpID" value="<?php echo $row[0]; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="invUOMStpRow<?php echo $cntr; ?>_UOMStpNm" value="<?php echo $row[1]; ?>">
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[2]; ?> </td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delInvUOMStp('invUOMStpRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Category">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                        <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                    echo urlencode(encrypt1(($row[0] . "|inv.unit_of_measure|uom_id"), $smplTokenWord1));
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
                            <div  class="col-md-6" style="padding:0px 15px 0px 1px !important">
                                <fieldset class="basic_person_fs" style="padding-top:2px !important;">
                                    <div class="container-fluid" id="invUOMStpDetailInfo">
                                        <?php
                                    }
                                    if ($subPgNo >= 0) {
                                        $invUOMStpID = -1;
                                        $invUOMStpName = "";
                                        $invUOMStpDesc = "";
                                        $invUOMStpIsEnbld = "1";
                                        $mkReadOnly = "";
                                        $result1 = get_One_UOMStp_Det($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $invUOMStpID = $row1[0];
                                            $invUOMStpName = $row1[1];
                                            $invUOMStpDesc = $row1[2];
                                            $invUOMStpIsEnbld = $row1[3];
                                        }
                                        if ($subPgNo == 0 || $subPgNo == 1) {
                                            ?>
                                            <div class="row">
                                                <div  class="col-md-12" style="padding:0px 1px 0px 0px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="invUOMStpName" class="control-label col-lg-4">UOM Name:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="invUOMStpName" name="invUOMStpName" value="<?php echo $invUOMStpName; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="invUOMStpID" name="invUOMStpID" value="<?php echo $invUOMStpID; ?>">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $invUOMStpName; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="invUOMStpDesc" class="control-label col-lg-4">UOM Description:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="invUOMStpDesc" name="invUOMStpDesc" value="<?php echo $invUOMStpDesc; ?>" style="width:100% !important;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $invUOMStpDesc; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:6px 0px 0px 0px !important;">
                                                            <label for="invUOMStpIsEnbld" class="control-label col-md-4">&nbsp;</label>
                                                            <div  class="col-md-8">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <?php
                                                                        $invUOMStpIsEnbldChkd = "";
                                                                        if ($invUOMStpIsEnbld == "1") {
                                                                            $invUOMStpIsEnbldChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="invUOMStpIsEnbld" name="invUOMStpIsEnbld"  <?php echo $invUOMStpIsEnbldChkd; ?>>
                                                                        Enabled?
                                                                    </label>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    if ($subPgNo == 0) {
                                        ?>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 3) {
                
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}    