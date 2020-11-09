<?php
$canAddRoles = test_prmssns($dfltPrvldgs[10], $mdlNm);
$canEdtRoles = test_prmssns($dfltPrvldgs[11], $mdlNm);
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Role Name";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                $slctdAllRoles = isset($_POST['slctdAllRoles']) ? cleanInputData($_POST['slctdAllRoles']) : '';
                $affctdRws = 0;
                //var_dump($_POST);
                if (trim($slctdAllRoles, "|~") != "") {
                    $variousRows = explode("|", trim($slctdAllRoles, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 5) {
                            $inptRoleID = (int) (cleanInputData1($crntRow[0]));
                            $inptRoleNm = (cleanInputData1($crntRow[1]));
                            $canAdmin = (cleanInputData1($crntRow[2]));
                            $canAssign = ($canAdmin == "YES") ? "1" : "0";
                            $vldStrtDte = cleanInputData1($crntRow[3]);
                            $vldEndDte = cleanInputData1($crntRow[4]);
                            $oldRoleID = getRoleID($inptRoleNm);
                            if ($oldRoleID <= 0 && $inptRoleID <= 0) {
                                $affctdRws += createRole($inptRoleNm, $vldStrtDte, $vldEndDte, $canAssign);
                            } else if ($inptRoleID > 0 && ($oldRoleID == $inptRoleID || $oldRoleID <= 0)) {
                                $affctdRws += updateRole($inptRoleID, $inptRoleNm, $vldStrtDte, $vldEndDte, $canAssign);
                            }
                        }
                    }
                }
                echo ("<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $affctdRws . " Role(s) Saved!");
                exit();
            } else if ($actyp == 2) {
                $rlPrvldgSbmtdRoleID = isset($_POST['rlPrvldgSbmtdRoleID']) ? (int) cleanInputData($_POST['rlPrvldgSbmtdRoleID']) : -1;
                $slctdRolesPrvldgs = isset($_POST['slctdRolesPrvldgs']) ? cleanInputData($_POST['slctdRolesPrvldgs']) : '';
                $affctdRws = 0;
                //var_dump($_POST);
                if (trim($slctdRolesPrvldgs, "|~") != "" && $rlPrvldgSbmtdRoleID > 0) {
                    $variousRows = explode("|", trim($slctdRolesPrvldgs, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 4) {
                            $inptPrvldgID = (int) (cleanInputData1($crntRow[0]));
                            $inptPrvldgNm = (cleanInputData1($crntRow[1]));
                            $vldStrtDte = cleanInputData1($crntRow[2]);
                            $vldEndDte = cleanInputData1($crntRow[3]);
                            if (hsRoleEverHdThisPrvldg($rlPrvldgSbmtdRoleID, $inptPrvldgID) == false) {
                                $affctdRws += asgnPrvlgToRole($inptPrvldgID, $rlPrvldgSbmtdRoleID, $vldStrtDte, $vldEndDte);
                            } else {
                                $affctdRws += updateRolesPrticulrPrvldg($inptPrvldgID, $rlPrvldgSbmtdRoleID, $vldStrtDte, $vldEndDte);
                            }
                        }
                    }
                }
                echo ("<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $affctdRws . " Role Priviledge(s) Saved!");
                exit();
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Roles List</span>
					</li>
                                       </ul>
                                     </div>";
                $total = get_Mdl_RolesTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_Mdl_Roles($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?>
                <form id='allRolesForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddRoles === true) {
                            $nwRowHtml = "<tr id=\"allRolesEdtRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\">New</td>"
                                    . "<td class=\"lovtd\"><div class=\"form-group form-group-sm col-md-12\">"
                                    . "<input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allRolesEdtRow_WWW123WWW_RoleNm\" name=\"allRolesEdtRow_WWW123WWW_RoleNm\" value=\"\" style=\"width:100%\">"
                                    . "<input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allRolesEdtRow_WWW123WWW_RoleID\" name=\"allRolesEdtRow_WWW123WWW_RoleID\" value=\"-1\">"
                                    . "</div>"
                                    . "</td>"
                                    . "<td class=\"lovtd\"><div class=\"form-group form-group-sm col-md-12\">
                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%\">
                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"allRolesEdtRow_WWW123WWW_StrtDte\" name=\"allRolesEdtRow_WWW123WWW_StrtDte\" value=\"\" readonly=\"\">
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                </div>                                                                
                                                            </div></td>"
                                    . "<td class=\"lovtd\"><div class=\"form-group form-group-sm col-md-12\">
                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%\">
                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"allRolesEdtRow_WWW123WWW_EndDte\" name=\"allRolesEdtRow_WWW123WWW_EndDte\" value=\"\" readonly=\"\">
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                </div>                                                                
                                                            </div></td>"
                                    . "<td class=\"lovtd\"><div class=\"form-group form-group-sm col-md-12\">
                                                        <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"allRolesEdtRow_WWW123WWW_CanAdmn\" name=\"allRolesEdtRow_WWW123WWW_CanAdmn\">
                                                                <option value=\"NO\">NO</option>
                                                                <option value=\"YES\">YES</option>
                                                        </select>                                                               
                                                    </div>                                                        
                                            </td>"
                                    . "<td class=\"lovtd\">&nbsp;</td>";
                            if ($canVwRcHstry === true) {
                                $nwRowHtml .= "<td class=\"lovtd\">&nbsp;</td>";
                            }
                            $nwRowHtml .= "</tr>";
                            $nwRowHtml = urlencode($nwRowHtml);
                            ?> 
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 1px !important;">     
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allRolesTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100%;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Role
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAllRolesForm();" style="width:100%;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save Roles
                                    </button>
                                </div>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-6";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allRolesSrchFor" name="allRolesSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllRoles(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                <input id="allRolesPageNo" name="allRolesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRoles('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRoles('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType1; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allRolesSrchIn">
                                <?php
                                $valslctdArry = array("", "", "");
                                $srchInsArrys = array("Role Name", "Priviledge Name", "Owner Module");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                                </select>-->
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRolesDsplySze" name="allRolesDsplySze" style="min-width:70px !important;">                            
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
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRolesSortBy" name="allRolesSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Role Name", "Start Date", "End Date");
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
                                        <a class="rhopagination" href="javascript:getAllRoles('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllRoles('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allRolesTable" cellspacing="0" width="100%" style="width:100%;min-width: 800px;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Role Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Can Mini-Admins Assign?</th>
                                        <th>...</th>
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
                                        <tr id="allRolesEdtRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <?php if ($canEdtRoles === true) { ?>
                                                    <div class="form-group form-group-sm col-md-12">
                                                        <input type="text" class="form-control" aria-label="..." id="allRolesEdtRow<?php echo $cntr; ?>_RoleNm" name="allRolesEdtRow<?php echo $cntr; ?>_RoleNm" value="<?php echo $row[1]; ?>" style="width:100%;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="allRolesEdtRow<?php echo $cntr; ?>_RoleID" name="allRolesEdtRow<?php echo $cntr; ?>_RoleID" value="<?php echo $row[0]; ?>">
                                                    </div>
                                                <?php } else { ?>
                                                    <span><?php echo $row[1]; ?></span>
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtRoles === true) { ?>
                                                    <div class="form-group form-group-sm col-md-12">
                                                        <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                            <input class="form-control" size="16" type="text" id="allRolesEdtRow<?php echo $cntr; ?>_StrtDte" name="allRolesEdtRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $row[2]; ?>" readonly="">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>                                                                
                                                    </div>
                                                <?php } else { ?>
                                                    <span><?php echo $row[2]; ?></span>
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtRoles === true) { ?>
                                                    <div class="form-group form-group-sm col-md-12">
                                                        <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                            <input class="form-control" size="16" type="text" id="allRolesEdtRow<?php echo $cntr; ?>_EndDte" name="allRolesEdtRow<?php echo $cntr; ?>_EndDte" value="<?php echo $row[3]; ?>" readonly="">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>                                                                
                                                    </div>
                                                <?php } else { ?>
                                                    <span><?php echo $row[3]; ?></span>
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtRoles === true) { ?>
                                                    <div class="form-group form-group-sm col-md-12">
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="allRolesEdtRow<?php echo $cntr; ?>_CanAdmn" name="allRolesEdtRow<?php echo $cntr; ?>_CanAdmn">
                                                            <?php
                                                            $valslctdArry = array("", "", "");
                                                            $srchInsArrys = array("YES", "NO");
                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                if (($row[4] == '1' ? "YES" : "NO") == $srchInsArrys[$z]) {
                                                                    $valslctdArry[$z] = "selected";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                            <?php } ?>
                                                        </select>                                                               
                                                    </div>
                                                <?php } else { ?>
                                                    <span><?php echo ($row[4] == '1' ? "YES" : "NO"); ?></span>
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneRoleForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'rolePrvldgsForm', 'View/Edit Priviledges for Role (<?php echo $row[1]; ?>)', <?php echo $row[0]; ?>, 1, <?php echo $pgNo ?>, 'clear');" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|sec.sec_roles|role_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                $pkID = isset($_POST['sbmtdRoleID']) ? $_POST['sbmtdRoleID'] : -1;
                ?>
                <form id='rolePrvldgsForm' action='' method='post' accept-charset='UTF-8'>                   
                    <?php
                    $total = get_Role_PrvldgsTtl($srchFor, $srchIn, $pkID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result1 = get_Role_Prvldgs($srchFor, $srchIn, $curIdx, $lmtSze, $pkID, $sortBy);
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-4";
                    ?>
                    <div class="row">
                        <fieldset class=""><!--<legend class="basic_person_lg">Priviledges for Role</legend>-->
                            <div class="row">
                                <?php
                                if ($canEdtRoles === true) {
                                    $nwRowHtml = "<tr id=\"rolePrvldgsEdtRow__WWW123WWW\">"
                                            . "<td class=\"lovtd\">New</td>"
                                            . "<td class=\"lovtd\"><div class=\"form-group form-group-sm col-md-12\">"
                                            . "<div class=\"input-group\"  style=\"width:100%\">"
                                            . "<input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"rolePrvldgsEdtRow_WWW123WWW_PrvldgNm\" value=\"\">"
                                            . "<input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rolePrvldgsEdtRow_WWW123WWW_PrvldgID\" value=\"-1\">"
                                            . "<input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rolePrvldgsEdtRow_WWW123WWW_DfltRowID\" value=\"-1\">"
                                            . "<label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'System Priviledges', '', '', '', 'radio', true, '', 'rolePrvldgsEdtRow_WWW123WWW_PrvldgID', 'rolePrvldgsEdtRow_WWW123WWW_PrvldgNm', 'clear', 1, '');\">"
                                            . "<span class=\"glyphicon glyphicon-th-list\"></span>"
                                            . "</label>"
                                            . "</div>"
                                            . "</div>"
                                            . "</td>"
                                            . "<td class=\"lovtd\">&nbsp;</td>"
                                            . "<td class=\"lovtd\"><div class=\"form-group form-group-sm col-md-12\">
                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%\">
                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"rolePrvldgsEdtRow_WWW123WWW_StrtDte\" value=\"\" readonly=\"\">
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                </div>                                                                
                                                            </div>
                                                   </td>"
                                            . "<td class=\"lovtd\">
                                                <div class=\"form-group form-group-sm col-md-12\">
                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%\">
                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"rolePrvldgsEdtRow_WWW123WWW_EndDte\" value=\"\" readonly=\"\">
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                </div>                                                                
                                                </div>
                                               </td>";
                                    if ($canVwRcHstry === true) {
                                        $nwRowHtml .= "<td class=\"lovtd\">&nbsp;</td>";
                                    }
                                    $nwRowHtml .= "</tr>";
                                    $nwRowHtml = urlencode($nwRowHtml);
                                    ?> 
                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;">     
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('rolePrvldgsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100%;">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                New Priviledge
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveRolesPrvldgsForm();" style="width:100%;">
                                                <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Save Priviledges
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    $colClassType1 = "col-lg-3";
                                    $colClassType2 = "col-lg-3";
                                }
                                ?>
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="rolePrvldgsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncOneRole(event, 'myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'rolePrvldgsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>, '');">
                                        <input id="rolePrvldgsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <input id="rlPrvldgSbmtdRoleID" type = "hidden" value="<?php echo $pkID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getOneRoleForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'rolePrvldgsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>, 'clear');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getOneRoleForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'rolePrvldgsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>, '');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType1; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="rolePrvldgsDsplySze" style="min-width:70px !important;">                            
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
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="rolePrvldgsSortBy">
                                            <?php
                                            $valslctdArry = array("", "", "","");
                                            $srchInsArrys = array("Priviledge Name", "Owner Module", "Start Date", "End Date");
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
                                                <a class="rhopagination" href="javascript:getOneRoleForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'rolePrvldgsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>,'previous');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="rhopagination" href="javascript:getOneRoleForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'rolePrvldgsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>,'next');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="rolePrvldgsTable" cellspacing="0" width="100%" style="width:100%;min-width: 800px !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Priviledge Name</th>
                                                <th>Owner Module</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th>...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cntr = 0;
                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="rolePrvldgsEdtRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtRoles === true) { ?>
                                                            <div class="form-group form-group-sm col-md-12">                                                                
                                                                <input type="text" class="form-control" aria-label="..." id="rolePrvldgsEdtRow<?php echo $cntr; ?>_PrvldgNm" name="rolePrvldgsEdtRow<?php echo $cntr; ?>_PrvldgNm" value="<?php echo $row1[2]; ?>" style="width:100%;" readonly="">
                                                                <input type="hidden" class="form-control" aria-label="..." id="rolePrvldgsEdtRow<?php echo $cntr; ?>_PrvldgID" name="rolePrvldgsEdtRow<?php echo $cntr; ?>_PrvldgID" value="<?php echo $row1[1]; ?>">
                                                                <input type="hidden" class="form-control" aria-label="..." id="rolePrvldgsEdtRow<?php echo $cntr; ?>_DfltRowID" name="rolePrvldgsEdtRow<?php echo $cntr; ?>_DfltRowID" value="<?php echo $row1[0]; ?>">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $row1[2]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd"><span><?php echo $row1[3]; ?></span></td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtRoles === true) { ?>
                                                            <div class="form-group form-group-sm col-md-12">
                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                    <input class="form-control" size="16" type="text" id="rolePrvldgsEdtRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $row1[4]; ?>" readonly="">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>                                                                
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $row1[4]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtRoles === true) { ?>
                                                            <div class="form-group form-group-sm col-md-12">
                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                    <input class="form-control" size="16" type="text" id="rolePrvldgsEdtRow<?php echo $cntr; ?>_EndDte" value="<?php echo $row1[5]; ?>" readonly="">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>                                                                
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $row1[5]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row1[0] . "|sec.sec_roles_n_prvldgs|dflt_row_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                        </fieldset>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 2) {
                
            } else if ($vwtyp == 3) {
                
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}