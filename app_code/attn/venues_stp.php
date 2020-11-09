<?php
$canAdd = test_prmssns($dfltPrvldgs[17], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[18], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[19], $mdlNm);
$canVwRcHstry = test_prmssns($dfltPrvldgs[7], $mdlNm);

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
                    echo deleteVenue($pKeyID, $pKeyNm);
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
                $attnVenueID = isset($_POST['attnVenueID']) ? (int) cleanInputData($_POST['attnVenueID']) : -1;
                $attnVenueNoOfPrsns = isset($_POST['attnVenueNoOfPrsns']) ? (float) cleanInputData($_POST['attnVenueNoOfPrsns']) : 0;
                $attnVenueName = isset($_POST['attnVenueName']) ? cleanInputData($_POST['attnVenueName']) : "";
                $attnVenueDesc = isset($_POST['attnVenueDesc']) ? cleanInputData($_POST['attnVenueDesc']) : "";
                $attnVenueType = isset($_POST['attnVenueType']) ? cleanInputData($_POST['attnVenueType']) : "";
                $attnVenueIsEnbld = isset($_POST['attnVenueIsEnbld']) ? (cleanInputData($_POST['attnVenueIsEnbld']) == "YES" ? TRUE : FALSE)
                            : FALSE;
                $exitErrMsg = "";
                if ($attnVenueName == "") {
                    $exitErrMsg .= "Please enter Venue Name!<br/>";
                }
                if ($attnVenueType == "") {
                    $exitErrMsg .= "Please enter Venue Classification!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['attnVenueID'] = $attnVenueID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldID = getVenueID($attnVenueName, $orgID);
                if (($oldID <= 0 || $oldID == $attnVenueID)) {
                    if ($attnVenueID <= 0) {
                        createVenue($orgID, $attnVenueName, $attnVenueDesc, $attnVenueType, $attnVenueIsEnbld, $attnVenueNoOfPrsns);
                        $attnVenueID = getVenueID($attnVenueName, $orgID);
                    } else {
                        updateVenue($attnVenueID, $attnVenueName, $attnVenueDesc, $attnVenueType, $attnVenueIsEnbld, $attnVenueNoOfPrsns);
                    }
                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Attendance Venue Successfully Saved!"
                                . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Attendance Venue Successfully Saved!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['attnVenueID'] = $attnVenueID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Attendance Venue Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['attnVenueID'] = $attnVenueID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0 || $vwtyp == 1 || $vwtyp == 2) {
                $pkID = isset($_POST['sbmtdVenueID']) ? $_POST['sbmtdVenueID'] : -1;
                if ($vwtyp == 0) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Venues Setup</span>
				</li>
                               </ul>
                              </div>";

                    $total = get_Total_Venues($srchFor, $srchIn, $orgID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_Basic_Venues($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='attnVenueForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php if ($canAdd === true) { ?> 
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 0px 0px 0px !important;"> 
                                    <div class="col-md-5">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAttnVenueForm(-1, 1);;" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Event Venue">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Venue
                                        </button>
                                    </div>
                                    <div class="col-md-7">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAttnVenueForm();" style="width:100% !important;">
                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Save
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getAttnVenue('', '#allmodules', 'grp=16&typ=1&pg=5&vtyp=0');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
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
                                    <input class="form-control" id="attnVenueSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAttnVenue(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="attnVenuePageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnVenue('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnVenue('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attnVenueSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Venue Name", "Venue Description");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attnVenueDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getAttnVenue('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttnVenue('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
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
                                    <table class="table table-striped table-bordered table-responsive" id="attnVenueTable" cellspacing="0" width="100%" style="width:100% !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Venue Name</th>
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
                                                <tr id="attnVenueRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $row[1]; ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnVenueRow<?php echo $cntr; ?>_CodeID" value="<?php echo $row[0]; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnVenueRow<?php echo $cntr; ?>_CodeNm" value="<?php echo $row[1]; ?>">
                                                    </td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnVenue('attnVenueRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Venue">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|attn.attn_event_venues|venue_id"),
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
                                    <div class="container-fluid" id="attnVenueDetailInfo">
                                        <?php
                                    }
                                    $attnVenueID = -1;
                                    $attnVenueName = "";
                                    $attnVenueDesc = "";
                                    $attnVenueType = "";
                                    $attnVenueIsEnbld = "0";
                                    $attnVenueNoOfPrsns = 0;
                                    if ($pkID > 0) {
                                        $result1 = get_One_VnuDet($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $attnVenueID = $row1[0];
                                            $attnVenueName = $row1[1];
                                            $attnVenueDesc = $row1[2];
                                            $attnVenueType = $row1[3];
                                            $attnVenueIsEnbld = $row1[5];
                                            $attnVenueNoOfPrsns = (int) $row1[4];
                                        }
                                    }
                                    ?>
                                    <div class="row">
                                        <div  class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                            <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <label for="attnVenueName" class="control-label col-lg-4">Venue Name:</label>
                                                    <div  class="col-lg-8">
                                                        <?php
                                                        if ($canEdt === true) {
                                                            ?>
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="attnVenueName" name="attnVenueName" value="<?php echo $attnVenueName; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="attnVenueID" name="attnVenueID" value="<?php echo $attnVenueID; ?>">
                                                        <?php } else {
                                                            ?>
                                                            <span><?php echo $attnVenueName; ?></span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <label for="attnVenueDesc" class="control-label col-lg-4">Description:</label>
                                                    <div  class="col-lg-8">
                                                        <?php
                                                        if ($canEdt === true) {
                                                            ?>
                                                            <input type="text" class="form-control" aria-label="..." id="attnVenueDesc" name="attnVenueDesc" value="<?php echo $attnVenueDesc; ?>" style="width:100% !important;">
                                                        <?php } else {
                                                            ?>
                                                            <span><?php echo $attnVenueDesc; ?></span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <label for="attnVenueType" class="control-label col-lg-4">Classification:</label>
                                                    <div  class="col-lg-8">
                                                        <?php
                                                        if ($canEdt === true) {
                                                            ?>
                                                            <div class="input-group">
                                                                <input class="form-control rqrdFld" id="attnVenueType" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Classification" type = "text" value="<?php echo $attnVenueType; ?>" readonly="true"/>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Liability Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'attnVenueType', '', 'clear', 1, '', function () {});">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $attnVenueType; ?></span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="col-md-4">
                                                        <label style="margin-bottom:0px !important;">Max No. of Persons:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding:0px 1px 0px 15px;">
                                                        <input type="number" class="form-control" aria-label="..." id="attnVenueNoOfPrsns" name="attnVenueNoOfPrsns" value="<?php echo $attnVenueNoOfPrsns; ?>">
                                                    </div>
                                                </div>                           
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <label for="attnVenueIsEnbld" class="control-label col-lg-6">Is Enabled?:</label>
                                                    <div  class="col-lg-6">
                                                        <?php
                                                        $chkdYes = "";
                                                        $chkdNo = "checked=\"\"";
                                                        if ($attnVenueIsEnbld == "1") {
                                                            $chkdNo = "";
                                                            $chkdYes = "checked=\"\"";
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($canEdt === true) {
                                                            ?>
                                                            <label class="radio-inline"><input type="radio" name="attnVenueIsEnbld" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                            <label class="radio-inline"><input type="radio" name="attnVenueIsEnbld" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                            <?php } else {
                                                                ?>
                                                            <span><?php echo ($attnVenueIsEnbld == "1" ? "YES" : "NO"); ?></span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div> 
                                            </fieldset>
                                        </div>
                                    </div>
                                    <?php ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 3) {
                
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}    