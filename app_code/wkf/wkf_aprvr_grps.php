<?php
$canAddWkfGrp = test_prmssns($dfltPrvldgs[13], $mdlNm);
$canEdtWkfGrp = test_prmssns($dfltPrvldgs[14], $mdlNm);
$canDelWkfGrp = test_prmssns($dfltPrvldgs[15], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $grpNm = isset($_POST['grpNm']) ? cleanInputData($_POST['grpNm']) : "";
                if ($pKeyID > 0) {
                    echo deleteWkfApprvrGrps($pKeyID, $grpNm);
                }
            } else if ($actyp == 2) {
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $apprvNm = isset($_POST['apprvNm']) ? cleanInputData($_POST['apprvNm']) : "";
                if ($pKeyID > 0) {
                    echo deleteWkfGrpMembers($pKeyID, $apprvNm);
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Approver Group
                header("content-type:application/json");
                $orgid = $_SESSION['ORG_ID'];
                $wkfGrpID = isset($_POST['wkfGrpID']) ? (int) cleanInputData($_POST['wkfGrpID']) : -1;
                $wkfGrpNm = isset($_POST['wkfGrpNm']) ? cleanInputData($_POST['wkfGrpNm']) : "";
                $wkfGrpLinkedFirmID = isset($_POST['wkfGrpLinkedFirmID']) ? cleanInputData($_POST['wkfGrpLinkedFirmID']) : -1;
                $wkfGrpIsEnbld = isset($_POST['wkfGrpIsEnbld']) ? cleanInputData($_POST['wkfGrpIsEnbld']) : "";
                $wkfGrpDesc = isset($_POST['wkfGrpDesc']) ? cleanInputData($_POST['wkfGrpDesc']) : '';
                $slctdGrpMembers = isset($_POST['slctdGrpMembers']) ? cleanInputData($_POST['slctdGrpMembers']) : '';

                $isenbld = ($wkfGrpIsEnbld == "YES") ? "1" : "0";
                $oldGrpID = getGnrlRecID2("wkf.wkf_apprvr_groups", "group_name", "apprvr_group_id", $wkfGrpNm);
                if ($wkfGrpNm != "" && ($oldGrpID <= 0 || $oldGrpID == $wkfGrpID)) {
                    if ($wkfGrpID <= 0) {
                        createWkfApprvrGrps($wkfGrpNm, $wkfGrpDesc, $wkfGrpLinkedFirmID, $isenbld);
                        $wkfGrpID = getGnrlRecID2("wkf.wkf_apprvr_groups", "group_name", "apprvr_group_id", $wkfGrpNm);
                    } else {
                        updateWkfApprvrGrps($wkfGrpID, $wkfGrpNm, $wkfGrpDesc, $wkfGrpLinkedFirmID, $isenbld);
                    }
                    $affctdMembrs = 0;
                    if ($wkfGrpID > 0) {
                        if (trim($slctdGrpMembers, "|~") != "") {
                            //Save Group Members
                            $variousRows = explode("|", trim($slctdGrpMembers, "|"));
                            for ($z = 0; $z < count($variousRows); $z++) {
                                $crntRow = explode("~", $variousRows[$z]);
                                if (count($crntRow) == 3) {
                                    $memberID = (float) (cleanInputData1($crntRow[0]));
                                    $prsnLocID = cleanInputData1($crntRow[1]);
                                    $inptPrsnID = getPersonID($prsnLocID);
                                    $isEnbld = cleanInputData1($crntRow[2]);
                                    $isEnbld1 = ($isEnbld == "YES") ? "1" : "0";
                                    $oldMemberID = getGnrlRecIDExtr1("wkf.wkf_apprvr_group_members", "''||person_id", "''||apprvr_group_id",
                                            "member_id", $inptPrsnID, $wkfGrpID);
                                    //getGnrlRecID("wkf.wkf_apprvr_group_members", "''||person_id", "member_id", $inptPrsnID, $wkfGrpID);

                                    if ($prsnLocID != "") {
                                        if ($oldMemberID <= 0 && $memberID <= 0) {
                                            $affctdMembrs += createWkfGrpMembers($wkfGrpID, $inptPrsnID, $isEnbld1);
                                        } else if ($memberID > 0) {
                                            $affctdMembrs += updateWkfGrpMembers($memberID, $inptPrsnID, $isEnbld1);
                                        } else if ($oldMemberID > 0) {
                                            $affctdMembrs += updateWkfGrpMembers($memberID, $inptPrsnID, $isEnbld1);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['wkfGrpID'] = $wkfGrpID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Approver Group Successfully Saved!<br/>" . $affctdMembrs . " Group Members Added!";
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['wkfGrpID'] = -1;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Approver Group Exists <br/>or Data Supplied is Incomplete!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0) {
                $pkID = isset($_POST['sbmtdGrpID']) ? $_POST['sbmtdGrpID'] : -1;
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
						<span style=\"text-decoration:none;\">Workflow Manager Menu</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Approver Groups</span>
				</li>
                               </ul>
                              </div>";
                $total = get_WkfApprvrGrpsTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_WkfApprvrGrps($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>
                <form id='allWkfGrpsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddWkfGrp === true) {
                            ?> 
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneWkfGrpForm(-1, 2);" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Group
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveWkfAprvrGrp();" style="width:100% !important;">
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
                                <input class="form-control" id="allWkfGrpsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%",
                                        " ", $srchFor)); ?>" onkeyup="enterKeyFuncWkfGrps(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allWkfGrpsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllWkfGrps('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllWkfGrps('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allWkfGrpsSrchIn">
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
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allWkfGrpsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllWkfGrps('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllWkfGrps('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row" style="padding:0px 15px 0px 15px !important"> 
                        <div  class="col-lg-3" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allWkfGrpsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Approver Group Name</th>
                                            <?php
                                            if ($canDelWkfGrp === true) {
                                                ?>
                                                <th>&nbsp;</th>
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
                                            <tr id="allWkfGrpsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?><input type="hidden" class="form-control" aria-label="..." id="allWkfGrpsRow<?php echo $cntr; ?>_GrpID" value="<?php echo $row[0]; ?>"></td>                                                
                                                <?php
                                                if ($canDelWkfGrp === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delWkfAprvrGrp('allWkfGrpsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Approver Group">
                                                            <img src="cmn_images/delete.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                        <div  class="col-lg-9" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                <div class="container-fluid" id="wkfGrpsDetailInfo">
                                    <?php
                                    if ($pkID > 0) {
                                        $result1 = get_WkfApprvrGrpDet($pkID);
                                        $sbmtdGrpID = $pkID;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            ?>
                                            <div class="row">
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;"> 
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="wkfGrpNm" class="control-label col-lg-4">Group Name:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtWkfGrp === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="wkfGrpNm" name="wkfGrpNm" value="<?php echo $row1[1]; ?>" style="width:100%;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="wkfGrpID" name="wkfGrpID" value="<?php echo $row1[0]; ?>">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[1]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="wkfGrpLinkedFirm" class="control-label col-lg-4">Linked Firm:</label>
                                                            <div  class="col-lg-8">
                        <?php if ($canEdtWkfGrp === true) { ?>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="wkfGrpLinkedFirm" value="<?php echo $row1[4]; ?>">
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <input type="hidden" id="wkfGrpLinkedFirmID" value="<?php echo $row1[3]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $row1[3]; ?>', 'wkfGrpLinkedFirmID', 'wkfGrpLinkedFirm', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[4]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="wkfGrpIsEnbld" class="control-label col-lg-6">Enabled?:</label>
                                                            <div class="col-lg-6">
                                                                <?php
                                                                $chkdYes = "";
                                                                $chkdNo = "checked=\"\"";
                                                                if ($row1[5] == "1") {
                                                                    $chkdNo = "";
                                                                    $chkdYes = "checked=\"\"";
                                                                }
                                                                ?>
                                                                <?php if ($canEdtWkfGrp === true) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="wkfGrpIsEnbld" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="wkfGrpIsEnbld" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo ($row1[5] == "1" ? "YES" : "NO"); ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class = "basic_person_fs">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="wkfGrpDesc" class="control-label col-lg-3">Description:</label>
                                                            <div  class="col-lg-9">
                                                                <?php if ($canEdtWkfGrp === true) { ?>
                                                                    <textarea class="form-control" aria-label="..." id="wkfGrpDesc" name="wkfGrpDesc" style="width:100%;" cols="5" rows="4"><?php echo $row1[2]; ?></textarea>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[2]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>                                            
                                            <div class="row">
                                                <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs"> 
                                                        <?php
                                                        if ($canEdtWkfGrp === true) {
                                                            $nwRowHtml = "<tr id=\"wkfGrpMmbrsRow__WWW123WWW\">"
                                                                    . "<td class=\"lovtd\"><span class=\"\">New</span></td>"
                                                                    . "<td class=\"lovtd\">
                                                                             <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"wkfGrpMmbrsRow_WWW123WWW_PrsnNm\" value=\"\" style=\"width:100% !important;\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"wkfGrpMmbrsRow_WWW123WWW_PrsnLocID\" value=\"\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"wkfGrpMmbrsRow_WWW123WWW_MmbrID\" value=\"-1\">
                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '', 'wkfGrpMmbrsRow_WWW123WWW_PrsnLocID', 'wkfGrpMmbrsRow_WWW123WWW_PrsnNm', 'clear', 0, '');\">
                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                    </label>
                                                                             </div>
                                                                        </td>
                                                                       <td class=\"lovtd\"> 
                                                                           <div class=\"form-group form-group-sm \">
                                                                                         <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                             <label class=\"form-check-label\">
                                                                                                 <input type=\"checkbox\" class=\"form-check-input\" id=\"wkfGrpMmbrsRow_WWW123WWW_Enabled\" name=\"wkfGrpMmbrsRow_WWW123WWW_Enabled\">
                                                                                             </label>
                                                                                         </div>
                                                                           </div>
                                                                        </td>                                                                        
                                                                        <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delWkfAprvrGrpPrsn('wkfGrpMmbrsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Group Member\">
                                                                                <img src=\"cmn_images/delete.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>
                                                                     </tr>";
                                                            $nwRowHtml = urlencode($nwRowHtml);
                                                            ?>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('wkfGrpMmbrsTable', 0, '<?php echo $nwRowHtml; ?>');">
                                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Add Approver Group Member
                                                            </button>
                        <?php } ?>
                                                        <table class="table table-striped table-bordered table-responsive" id="wkfGrpMmbrsTable" cellspacing="0" width="100%" style="width:100%;min-width: 600px;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th style="min-width: 250px;">Person Name (ID)</th>
                                                                    <th>Enabled?</th> 
                                                                    <?php
                                                                    if ($canDelWkfGrp === true) {
                                                                        ?>
                                                                        <th>&nbsp;</th>
                        <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $result2 = get_WkfGrpMembers($pkID);
                                                                $cntr = 0;
                                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="wkfGrpMmbrsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                                        <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                        <td class="lovtd">
                            <?php if ($canEdtWkfGrp === true) { ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="wkfGrpMmbrsRow<?php echo $cntr; ?>_PrsnNm" value="<?php echo $row2[2] . " (" . $row2[1] . ")"; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="wkfGrpMmbrsRow<?php echo $cntr; ?>_PrsnLocID" value="<?php echo $row2[1]; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="wkfGrpMmbrsRow<?php echo $cntr; ?>_MmbrID" value="<?php echo $row2[0]; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '<?php echo $row2[1]; ?>', 'wkfGrpMmbrsRow<?php echo $cntr; ?>_PrsnLocID', 'wkfGrpMmbrsRow<?php echo $cntr; ?>_PrsnNm', 'clear', 0, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span class=""><?php echo $row2[2]; ?></span>
                                                                            <?php } ?>                                                         
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            $isChkd = "";
                                                                            if ($row2[3] == "1") {
                                                                                $isChkd = "checked=\"true\"";
                                                                            }
                                                                            if ($canEdtWkfGrp === true) {
                                                                                ?>
                                                                                <div class="form-group form-group-sm ">
                                                                                    <div class="form-check" style="font-size: 12px !important;">
                                                                                        <label class="form-check-label">
                                                                                            <input type="checkbox" class="form-check-input" id="wkfGrpMmbrsRow<?php echo $cntr; ?>_Enabled" name="wkfGrpMmbrsRow<?php echo $cntr; ?>_Enabled" <?php echo $isChkd ?>>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span class=""><?php echo $row2[3]; ?></span>
                                                                        <?php } ?>                                                         
                                                                        </td>
                                                                        <?php
                                                                        if ($canDelWkfGrp === true) {
                                                                            ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delWkfAprvrGrpPrsn('wkfGrpMmbrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Group Member">
                                                                                    <img src="cmn_images/delete.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                //lov detail on tr click
                $curIdx = 0;
                $pkID = isset($_POST['sbmtdGrpID']) ? $_POST['sbmtdGrpID'] : -1;
                if ($pkID > 0) {
                    $result1 = get_WkfApprvrGrpDet($pkID);
                    $sbmtdGrpID = $pkID;
                    while ($row1 = loc_db_fetch_array($result1)) {
                        ?>
                        <div class="row">
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;"> 
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="wkfGrpNm" class="control-label col-lg-4">Group Name:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtWkfGrp === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="wkfGrpNm" name="wkfGrpNm" value="<?php echo $row1[1]; ?>" style="width:100%;">
                                                <input type="hidden" class="form-control" aria-label="..." id="wkfGrpID" name="wkfGrpID" value="<?php echo $row1[0]; ?>">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[1]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="wkfGrpLinkedFirm" class="control-label col-lg-4">Linked Firm:</label>
                                        <div  class="col-lg-8">
                        <?php if ($canEdtWkfGrp === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="wkfGrpLinkedFirm" value="<?php echo $row1[4]; ?>" readonly="true">
                                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                    <input type="hidden" id="wkfGrpLinkedFirmID" value="<?php echo $row1[3]; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $row1[3]; ?>', 'wkfGrpLinkedFirmID', 'wkfGrpLinkedFirm', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[4]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="wkfGrpIsEnbld" class="control-label col-lg-6">Enabled?:</label>
                                        <div class="col-lg-6">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($row1[5] == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdtWkfGrp === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="wkfGrpIsEnbld" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="wkfGrpIsEnbld" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($row1[5] == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class = "basic_person_fs">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="wkfGrpDesc" class="control-label col-lg-3">Description:</label>
                                        <div  class="col-lg-9">
                                            <?php if ($canEdtWkfGrp === true) { ?>
                                                <textarea class="form-control" aria-label="..." id="wkfGrpDesc" name="wkfGrpDesc" style="width:100%;" cols="5" rows="4"><?php echo $row1[2]; ?></textarea>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[2]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>                                            
                        <div class="row">
                            <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs"> 
                                    <?php
                                    if ($canEdtWkfGrp === true) {
                                        $nwRowHtml = "<tr id=\"wkfGrpMmbrsRow__WWW123WWW\">"
                                                . "<td class=\"lovtd\"><span class=\"\">New</span></td>"
                                                . "<td class=\"lovtd\">
                                                                             <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"wkfGrpMmbrsRow_WWW123WWW_PrsnNm\" value=\"\" style=\"width:100% !important;\" readonly=\"true\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"wkfGrpMmbrsRow_WWW123WWW_PrsnLocID\" value=\"\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"wkfGrpMmbrsRow_WWW123WWW_MmbrID\" value=\"-1\">
                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '', 'wkfGrpMmbrsRow_WWW123WWW_PrsnLocID', 'wkfGrpMmbrsRow_WWW123WWW_PrsnNm', 'clear', 0, '');\">
                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                    </label>
                                                                             </div>
                                                                        </td>
                                                                       <td class=\"lovtd\"> 
                                                                           <div class=\"form-group form-group-sm \">
                                                                                         <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                             <label class=\"form-check-label\">
                                                                                                 <input type=\"checkbox\" class=\"form-check-input\" id=\"wkfGrpMmbrsRow_WWW123WWW_Enabled\" name=\"wkfGrpMmbrsRow_WWW123WWW_Enabled\">
                                                                                             </label>
                                                                                         </div>
                                                                           </div>
                                                                        </td>                                                                        
                                                                        <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delWkfAprvrGrpPrsn('wkfGrpMmbrsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Group Member\">
                                                                                <img src=\"cmn_images/delete.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>
                                                                     </tr>";
                                        $nwRowHtml = urlencode($nwRowHtml);
                                        ?>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('wkfGrpMmbrsTable', 0, '<?php echo $nwRowHtml; ?>');">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Add Approver Group Member
                                        </button>
                        <?php } ?>
                                    <table class="table table-striped table-bordered table-responsive" id="wkfGrpMmbrsTable" cellspacing="0" width="100%" style="width:100%;min-width: 600px;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th style="min-width: 250px;">Person Name (ID)</th>
                                                <th>Enabled?</th> 
                                                <?php
                                                if ($canDelWkfGrp === true) {
                                                    ?>
                                                    <th>&nbsp;</th>
                        <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result2 = get_WkfGrpMembers($pkID);
                                            $cntr = 0;
                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="wkfGrpMmbrsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                    <td class="lovtd">
                            <?php if ($canEdtWkfGrp === true) { ?>
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="wkfGrpMmbrsRow<?php echo $cntr; ?>_PrsnNm" value="<?php echo $row2[2] . " (" . $row2[1] . ")"; ?>" style="width:100% !important;" readonly="true">
                                                                <input type="hidden" class="form-control" aria-label="..." id="wkfGrpMmbrsRow<?php echo $cntr; ?>_PrsnLocID" value="<?php echo $row2[1]; ?>">
                                                                <input type="hidden" class="form-control" aria-label="..." id="wkfGrpMmbrsRow<?php echo $cntr; ?>_MmbrID" value="<?php echo $row2[0]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '<?php echo $row2[1]; ?>', 'wkfGrpMmbrsRow<?php echo $cntr; ?>_PrsnLocID', 'wkfGrpMmbrsRow<?php echo $cntr; ?>_PrsnNm', 'clear', 0, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row2[2]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php
                                                        $isChkd = "";
                                                        if ($row2[3] == "1") {
                                                            $isChkd = "checked=\"true\"";
                                                        }
                                                        if ($canEdtWkfGrp === true) {
                                                            ?>
                                                            <div class="form-group form-group-sm ">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" id="wkfGrpMmbrsRow<?php echo $cntr; ?>_Enabled" name="wkfGrpMmbrsRow<?php echo $cntr; ?>_Enabled" <?php echo $isChkd ?>>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row2[3]; ?></span>
                                                    <?php } ?>                                                         
                                                    </td>
                                                    <?php
                                                    if ($canDelWkfGrp === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delWkfAprvrGrpPrsn('wkfGrpMmbrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Group Member">
                                                                <img src="cmn_images/delete.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <span>No Results Found</span>
                    <?php
                }
            } else if ($vwtyp == 2) {
                //New Org Form
                $curIdx = 0;
                $pkID = -1;
                if ($canAddWkfGrp === true) {
                    
                } else {
                    exit();
                }
                ?>
                <div class="row">
                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;"> 
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="wkfGrpNm" class="control-label col-lg-4">Group Name:</label>
                                <div  class="col-lg-8">
                                    <input type="text" class="form-control" aria-label="..." id="wkfGrpNm" name="wkfGrpNm" value="" style="width:100%;">
                                    <input type="hidden" class="form-control" aria-label="..." id="wkfGrpID" name="wkfGrpID" value="">
                                    <input type="hidden" class="form-control" aria-label="..." id="isNew123" name="isNew123" value="5">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="wkfGrpLinkedFirm" class="control-label col-lg-4">Linked Firm:</label>
                                <div  class="col-lg-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="..." id="wkfGrpLinkedFirm" value="">
                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                        <input type="hidden" id="wkfGrpLinkedFirmID" value="-1">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '', 'wkfGrpLinkedFirmID', 'wkfGrpLinkedFirm', 'clear', 1, '');">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="wkfGrpIsEnbld" class="control-label col-lg-6">Enabled?:</label>
                                <div class="col-lg-6">
                                    <label class="radio-inline"><input type="radio" name="wkfGrpIsEnbld" value="YES">YES</label>
                                    <label class="radio-inline"><input type="radio" name="wkfGrpIsEnbld" value="NO" checked="true">NO</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class = "basic_person_fs">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="wkfGrpDesc" class="control-label col-lg-3">Description:</label>
                                <div  class="col-lg-9">
                                    <textarea class="form-control" aria-label="..." id="wkfGrpDesc" name="wkfGrpDesc" style="width:100%;" cols="5" rows="4"></textarea>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>                                            
                <div class="row">
                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class="basic_person_fs"> 
                            <?php
                            if ($canEdtWkfGrp === true) {
                                $nwRowHtml = "<tr id=\"wkfGrpMmbrsRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                                                             <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"wkfGrpMmbrsRow_WWW123WWW_PrsnNm\" value=\"\" style=\"width:100% !important;\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"wkfGrpMmbrsRow_WWW123WWW_PrsnLocID\" value=\"\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"wkfGrpMmbrsRow_WWW123WWW_MmbrID\" value=\"-1\">
                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '', 'wkfGrpMmbrsRow_WWW123WWW_PrsnLocID', 'wkfGrpMmbrsRow_WWW123WWW_PrsnNm', 'clear', 0, '');\">
                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                    </label>
                                                                             </div>
                                                                        </td>
                                                                       <td class=\"lovtd\"> 
                                                                           <div class=\"form-group form-group-sm \">
                                                                                         <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                             <label class=\"form-check-label\">
                                                                                                 <input type=\"checkbox\" class=\"form-check-input\" id=\"wkfGrpMmbrsRow_WWW123WWW_Enabled\" name=\"wkfGrpMmbrsRow_WWW123WWW_Enabled\">
                                                                                             </label>
                                                                                         </div>
                                                                           </div>
                                                                        </td>                                                                        
                                                                        <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delWkfAprvrGrpPrsn('wkfGrpMmbrsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Group Member\">
                                                                                <img src=\"cmn_images/delete.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>
                                                                     </tr>";
                                $nwRowHtml = urlencode($nwRowHtml);
                                ?>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('wkfGrpMmbrsTable', 0, '<?php echo $nwRowHtml; ?>');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Add Approver Group Member
                                </button>
                <?php } ?>
                            <table class="table table-striped table-bordered table-responsive" id="wkfGrpMmbrsTable" cellspacing="0" width="100%" style="width:100%;min-width: 600px;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th style="min-width: 250px;">Person Name (ID)</th>
                                        <th>Enabled?</th> 
                                        <?php
                                        if ($canDelWkfGrp === true) {
                                            ?>
                                            <th>&nbsp;</th>
                <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cntr = 0;
                                    $cntr += 1;
                                    ?>
                                    <tr id="wkfGrpMmbrsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                        <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                        <td class="lovtd">
                                            <div class="input-group" style="width:100% !important;">
                                                <input type="text" class="form-control" aria-label="..." id="wkfGrpMmbrsRow<?php echo $cntr; ?>_PrsnNm" value="" style="width:100% !important;">
                                                <input type="hidden" class="form-control" aria-label="..." id="wkfGrpMmbrsRow<?php echo $cntr; ?>_PrsnLocID" value="">
                                                <input type="hidden" class="form-control" aria-label="..." id="wkfGrpMmbrsRow<?php echo $cntr; ?>_MmbrID" value="-1">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '', 'wkfGrpMmbrsRow<?php echo $cntr; ?>_PrsnLocID', 'wkfGrpMmbrsRow<?php echo $cntr; ?>_PrsnNm', 'clear', 0, '');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="lovtd">
                                            <?php
                                            $isChkd = "";
                                            ?>
                                            <div class="form-group form-group-sm ">
                                                <div class="form-check" style="font-size: 12px !important;">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" id="wkfGrpMmbrsRow<?php echo $cntr; ?>_Enabled" name="wkfGrpMmbrsRow<?php echo $cntr; ?>_Enabled" <?php echo $isChkd ?>>
                                                    </label>
                                                </div>
                                            </div>                                                       
                                        </td>
                                        <?php
                                        if ($canDelWkfGrp === true) {
                                            ?>
                                            <td>
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delWkfAprvrGrpPrsn('wkfGrpMmbrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Group Member">
                                                    <img src="cmn_images/delete.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                <?php } ?>
                                    </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 3) {
                
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}
    