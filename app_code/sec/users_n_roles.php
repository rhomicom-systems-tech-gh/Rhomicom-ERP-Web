<?php
$canAddUsers = test_prmssns($dfltPrvldgs[8], $mdlNm);
$canEdtUsers = test_prmssns($dfltPrvldgs[9], $mdlNm);
$canDelUsers = test_prmssns($dfltPrvldgs[10], $mdlNm);
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                $inptUsrID = cleanInputData($_POST['pKeyID']);
                $inptUsrNm = cleanInputData($_POST['usrNm']);
                echo deleteUser($inptUsrID, $inptUsrNm);
            } else if ($actyp == 2) {
                //Delete User Role
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //User and Roles
                header("content-type:application/json");
                $inptUserID = isset($_POST['usrPrflAccountID']) ? cleanInputData($_POST['usrPrflAccountID']) : "";
                $lnkdCstmrID = isset($_POST['usrPrflLnkdCstmrID']) ? cleanInputData($_POST['usrPrflLnkdCstmrID']) : -1;
                $prsnLocID = isset($_POST['usrPrflAcntOwnerLocID']) ? cleanInputData($_POST['usrPrflAcntOwnerLocID']) : "";
                $userAccntName = isset($_POST['usrPrflAccountName']) ? cleanInputData($_POST['usrPrflAccountName']) : "";
                $vldtyStrtDte = isset($_POST['usrPrflVldtyStartDate']) ? cleanInputData($_POST['usrPrflVldtyStartDate']) : "";
                $vldtyEndDte = isset($_POST['usrPrflVldtyEndDate']) ? cleanInputData($_POST['usrPrflVldtyEndDate']) : "";
                $slctdUsrPrflRoles = isset($_POST['slctdUsrPrflRoles']) ? cleanInputData($_POST['slctdUsrPrflRoles']) : '';
                if (trim($vldtyStrtDte) == "") {
                    $vldtyStrtDte = date("d-M-Y H:i:s");
                }
                if (trim($vldtyEndDte) == "") {
                    $vldtyEndDte = "31-Dec-4000 23:59:59";
                }
                if ($vldtyStrtDte != "") {
                    $vldtyStrtDte = cnvrtDMYTmToYMDTm($vldtyStrtDte);
                }
                if ($vldtyEndDte != "") {
                    $vldtyEndDte = cnvrtDMYTmToYMDTm($vldtyEndDte);
                }
                $oldUserID = getGnrlRecID2("sec.sec_users", "user_name", "user_id", $userAccntName);
                $ownrID = getPersonID($prsnLocID);
                $cstmrID = (float) $lnkdCstmrID;
                if ($userAccntName != "" && $prsnLocID != "" && ($oldUserID <= 0 || $oldUserID == $inptUserID)) {
                    if ($inptUserID <= 0) {
                        $pwd = getRandomPswd();
                        createUser($userAccntName, $ownrID, $vldtyStrtDte, $vldtyEndDte, $pwd, $cstmrID);
                        $inptUserID = getGnrlRecID2("sec.sec_users", "user_name", "user_id", $userAccntName);
                    } else {
                        updateUser($inptUserID, $userAccntName, $ownrID, $vldtyStrtDte, $vldtyEndDte, $cstmrID);
                    }

                    $affctdRws = 0;
                    if (trim($slctdUsrPrflRoles, "|~") != "") {
                        //Save Question Answers
                        $variousRows = explode("|", trim($slctdUsrPrflRoles, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 5) {
                                $inptRoleID = (int) (cleanInputData1($crntRow[0]));
                                $inptRoleNm = (cleanInputData1($crntRow[1]));
                                $dfltRowID = (int) (cleanInputData1($crntRow[2]));
                                $vldStrtDte = cleanInputData1($crntRow[3]);
                                $vldEndDte = cleanInputData1($crntRow[4]);
                                $oldDefaultRowID = getUsrIDHvThsRoleID($inptUserID, $inptRoleID);
                                if (trim($vldStrtDte) == "") {
                                    $vldStrtDte = date("d-M-Y H:i:s");
                                }
                                if (trim($vldEndDte) == "") {
                                    $vldEndDte = "31-Dec-4000 23:59:59";
                                }
                                if ($vldStrtDte != "") {
                                    $vldStrtDte = cnvrtDMYTmToYMDTm($vldStrtDte);
                                }
                                if ($vldEndDte != "") {
                                    $vldEndDte = cnvrtDMYTmToYMDTm($vldEndDte);
                                }
                                if ($inptRoleID > 0) {
                                    if ($oldDefaultRowID <= 0) {
                                        $affctdRws += asgnRoleToUserWthDte($inptUserID, $inptRoleID, $vldStrtDte, $vldEndDte);
                                    } else {
                                        $affctdRws += updtRoleToUserWthDte($oldDefaultRowID, $vldStrtDte, $vldEndDte);
                                    }
                                }
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['usrPrflAccountID'] = $inptUserID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>User Successfully Saved!<br/>" . $affctdRws . "Role(s) Saved!";
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['usrPrflAccountID'] = -1;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New User Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                //Lock/Unlock User
                $inptUsrID = cleanInputData($_POST['pKeyID']);
                $inptUsrNm = cleanInputData($_POST['usrNm']);
                $status = cleanInputData($_POST['nwStatus']);
                $fldAttmpts = 0;
                if ($status == "LOCK") {
                    $fldAttmpts = get_CurPlcy_Mx_Fld_lgns() + 1;
                } else {
                    $fldAttmpts = 0;
                }
                echo chngUsrLockStatus($inptUsrID, $fldAttmpts, $inptUsrNm);
            } else if ($actyp == 3) {
                //Suspend/Unsuspend User
                $inptUsrID = cleanInputData($_POST['pKeyID']);
                $inptUsrNm = cleanInputData($_POST['usrNm']);
                $status = strtoupper(cleanInputData($_POST['nwStatus']));
                if ($status == "SUSPEND") {
                    echo chngUsrSuspensionStatus($inptUsrID, "TRUE", $inptUsrNm);
                } else {
                    echo chngUsrSuspensionStatus($inptUsrID, "FALSE", $inptUsrNm);
                }
            } else if ($actyp == 4) {
                //Update User Selected Roles in Session
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Users List</span>
					</li>
                                       </ul>
                                     </div>";
                $total = get_UsersTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_UsersTblr($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-4";
                $prm = get_CurPlcy_Mx_Fld_lgns();
                ?>
                <form id='allUsersForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddUsers === true) {
                            ?> 
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneUserForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'newUserForm', 'Add New User', -1, 2, <?php echo $pgNo; ?>, '')">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New User
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
                                <input class="form-control" id="allUsersSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllUsers(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allUsersPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllUsers('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllUsers('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allUsersSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("User Name", "Role Name", "Owned By");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allUsersDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllUsers('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllUsers('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allUsersTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>User Name</th>
                                        <th>Owned By</th>
                                        <th>Modules Needed</th>
                                        <th>Account Suspended?</th>
                                        <th>Password Temporary?</th>
                                        <th>Account Locked?</th>
                                        <th>Password Expired?</th>
                                        <th>Active Roles</th>
                                        <th>&nbsp;</th>
                                        <?php if ($canDelUsers === true) { ?>
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
                                        <tr id="allUsersRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <?php if ($canEdtUsers === true) { ?>
                                            <?php } ?>
                                            <td class="lovtd">
                                                <?php echo $row[0]; ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allUsersRow<?php echo $cntr; ?>_UserID" value="<?php echo $row[9]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[1] . " (" . $row[10] . ")"; ?></td>
                                            <td class="lovtd"><?php echo $row[13]; ?></td>
                                            <?php
                                            if ($canEdtUsers === true) {
                                                if ($row[4] == 1) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to Unsuspend" onclick="suspendUnsuspend('allUsersRow_<?php echo $cntr; ?>', 'Unsuspend');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/success.gif" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <span>UNSUSPEND&nbsp;&nbsp;</span>
                                                        </button>
                                                    </td>
                                                <?php } else {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to Suspend" onclick="suspendUnsuspend('allUsersRow_<?php echo $cntr; ?>', 'Suspend');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/90.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <span>SUSPEND&nbsp;&nbsp;</span>
                                                        </button>
                                                    </td>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <td class="lovtd"><?php echo ($row[4] == '1' ? "YES" : "NO"); ?></td>
                                            <?php } ?>
                                            <td class="lovtd"><?php echo ($row[5] == '1' ? "YES" : "NO"); ?></td>
                                            <?php
                                            if ($canEdtUsers === true) {
                                                if ($row[6] >= $prm) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to Unlock" onclick="lockUnlock('allUsersRow_<?php echo $cntr; ?>', 'Unlock');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/success.gif" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <span>UNLOCK&nbsp;&nbsp;</span>
                                                        </button>
                                                    </td>
                                                <?php } else {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to Lock" onclick="lockUnlock('allUsersRow_<?php echo $cntr; ?>', 'Lock');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/90.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <span>LOCK&nbsp;&nbsp;</span>
                                                        </button>
                                                    </td>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <td class="lovtd"><?php echo ($row[6] >= $prm ? "YES" : "NO"); ?></td>
                                            <?php } ?>
                                            <td class="lovtd"><?php echo ($row[12] == '1' ? "YES" : "NO"); ?></td>
                                            <td class="lovtd"><?php echo $row[11]; ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneUserForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'newUserForm', 'View/Edit User', <?php echo $row[9]; ?>, 1, <?php echo $pgNo ?>, 'clear');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canDelUsers === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delUser('allUsersRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete User">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[9] . "|sec.sec_users|user_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Role Name";
                if ($sortBy == "") {
                    $sortBy = "Role Name";
                }
                $pkID = isset($_POST['sbmtdUserID']) ? $_POST['sbmtdUserID'] : -1;
                $result = get_OneUser($pkID);
                ?>
                <form id='userRolesForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        while ($row = loc_db_fetch_array($result)) {
                            ?>
                            <fieldset class="basic_person_fs"><legend class="basic_person_lg">User Summary Information</legend>
                                <div class="col-md-4">
                                    <div class="row form-group form-group-sm">
                                        <label for="usrPrflAccountName" class="control-label col-md-4">User Name:</label>
                                        <div class="col-md-8">
                                            <?php
                                            if ($canEdtUsers === true) {
                                                ?>
                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="usrPrflAccountName" value="<?php echo $row[0]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="usrPrflAccountID" value="<?php echo $row[10]; ?>">
                                            <?php } else { ?>
                                                <span><?php echo $row[0]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div> 
                                    <div class="row form-group form-group-sm">
                                        <label for="usrPrflAcntOwner" class="control-label col-md-4">Owned By:</label>
                                        <div  class="col-md-8">
                                            <?php
                                            if ($canEdtUsers === true) {
                                                ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="usrPrflAcntOwner" value="<?php echo $row[1]; ?>" readonly="true">
                                                    <input type="hidden" class="form-control" aria-label="..." id="usrPrflAcntOwnerLocID" value="<?php echo $row[11]; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '<?php echo $row[11]; ?>', 'usrPrflAcntOwnerLocID', 'usrPrflAcntOwner', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $row[1]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>  
                                    <div class="row form-group form-group-sm">
                                        <label for="usrPrflModulesNeeded" class="control-label col-md-4">Modules Needed:</label>
                                        <div  class="col-md-8">
                                            <?php
                                            if ($canEdtUsers === true) {
                                                ?>
                                                <select class="form-control selectpicker" id="usrPrflModulesNeeded">  
                                                    <option value="" selected disabled>Please Select...</option>
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "",
                                                        "", "", "", "", "",
                                                        "", "", "", "", "",
                                                        "", "", "", "", "",
                                                        "", "", "", "", "", "");
                                                    $optionsArrys = array(
                                                        "Person Records Only",
                                                        "Point of Sale Only",
                                                        "Accounting Only",
                                                        "Person Records with Accounting Only",
                                                        "Sales with Accounting Only",
                                                        "Accounting with Payroll Only",
                                                        "Person Records + Hospitality Only",
                                                        "Person Records + Events Only",
                                                        "Basic Modules Only",
                                                        "Basic Modules + Hospitality Only",
                                                        "Basic Modules + Events Only",
                                                        "Basic Modules + Projects Only",
                                                        "Basic Modules + Appointments Only",
                                                        "Basic Modules + PMS Only",
                                                        "Basic Modules + Events + Hospitality Only",
                                                        "Basic Modules - Payroll - Person Records + Events + Hospitality Only",
                                                        "Basic Modules + Payroll - Person Records + Events + Hospitality Only",
                                                        "Basic Modules + Events + PMS Only",
                                                        "Basic Modules + Projects + PMS Only",
                                                        "Basic Modules + Projects + Hospitality Only",
                                                        "Basic Modules + Projects + Events Only",
                                                        "Basic Modules + Events + Hospitality + PMS Only",
                                                        "Basic Modules + Projects + Hospitality + PMS Only",
                                                        "Basic Modules + Events + Projects + Hospitality Only",
                                                        "Basic Modules + Events + Projects + Hospitality + PMS Only",
                                                        "All Modules");

                                                    for ($z = 0; $z < count($optionsArrys); $z++) {
                                                        if ($row[14] == $optionsArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $optionsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $optionsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $row[14]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md-4">
                                    <div class="row form-group form-group-sm">
                                        <label for="failedLgns" class="control-label col-md-6">Failed Login Attempts:</label>
                                        <div  class="col-md-6">
                                            <span><?php echo $row[6]; ?></span>
                                        </div>
                                    </div>     
                                    <div class="row form-group form-group-sm">
                                        <label for="lastLgns" class="control-label col-md-6">Last Login Attempt:</label>
                                        <div  class="col-md-6">
                                            <span><?php echo $row[7]; ?></span>
                                        </div>
                                    </div>
                                    <div class="row form-group form-group-sm">
                                        <label for="lastChnge" class="control-label col-md-6">Last Password Change:</label>
                                        <div  class="col-md-6">
                                            <span><?php echo $row[8]; ?></span>
                                        </div>
                                    </div>
                                    <div class="row form-group form-group-sm">
                                        <label for="usrPrflLnkdCstmr" class="control-label col-md-4">Customer / Supplier:</label>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="usrPrflLnkdCstmr" value="<?php echo $row[16]; ?>" readonly="true">
                                                <input type="hidden" class="form-control" aria-label="..." id="usrPrflLnkdCstmrID" value="<?php echo $row[15]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', '', '', '', 'radio', true, '<?php echo $row[15]; ?>', 'usrPrflLnkdCstmrID', 'usrPrflLnkdCstmr', 'clear', 1, '');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md-4">
                                    <div class="row form-group form-group-sm">
                                        <label for="pswwdAge" class="control-label col-md-4">Password Age:</label>
                                        <div  class="col-md-8">
                                            <span><?php echo $row[9]; ?></span>
                                        </div>
                                    </div>     
                                    <div class="row form-group form-group-sm">
                                        <label for="usrPrflVldtyStartDate" class="control-label col-md-4">Start Date:</label>
                                        <div  class="col-md-8">
                                            <?php
                                            if ($canEdtUsers === true) {
                                                ?>
                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss">
                                                    <input class="form-control" size="16" type="text" id="usrPrflVldtyStartDate" value="<?php echo $row[2]; ?>" readonly="">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $row[2]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row form-group form-group-sm">
                                        <label for="usrPrflVldtyEndDate" class="control-label col-md-4">End Date:</label>
                                        <div  class="col-md-8">
                                            <?php
                                            if ($canEdtUsers === true) {
                                                ?>
                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss">
                                                    <input class="form-control" size="16" type="text" id="usrPrflVldtyEndDate" value="<?php echo $row[3]; ?>" readonly="">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $row[3]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <?php
                    }

                    $total = get_TtlUsersRoles($srchFor, $srchIn, $pkID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result1 = get_UsersRoles($srchFor, $srchIn, $curIdx, $lmtSze, $pkID, $sortBy);
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    ?>
                    <div class="row">
                        <fieldset class=""><legend class="basic_person_lg">Assigned User Roles</legend>
                            <div class="row">
                                <?php
                                if ($canEdtUsers === true) {
                                    $nwRowHtml = urlencode("<tr id=\"usrPrflEdtRow__WWW123WWW\">"
                                            . "<td class=\"lovtd\">New</td>"
                                            . "<td class=\"lovtd\"><div class=\"form-group form-group-sm col-md-12\">"
                                            . "<div class=\"input-group\"  style=\"width:100%\">"
                                            . "<input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"usrPrflEdtRow_WWW123WWW_RoleNm\" value=\"\">"
                                            . "<input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"usrPrflEdtRow_WWW123WWW_RoleID\" value=\"-1\">"
                                            . "<input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"usrPrflEdtRow_WWW123WWW_DfltRowID\" value=\"-1\">"
                                            . "<label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'User Roles', '', '', '', 'radio', true, '', 'usrPrflEdtRow_WWW123WWW_RoleID', 'usrPrflEdtRow_WWW123WWW_RoleNm', 'clear', 1, '');\">"
                                            . "<span class=\"glyphicon glyphicon-th-list\"></span>"
                                            . "</label>"
                                            . "</div>"
                                            . "</div>"
                                            . "</td>"
                                            . "<td class=\"lovtd\"><div class=\"form-group form-group-sm col-md-12\">
                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%\">
                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"usrPrflEdtRow_WWW123WWW_StrtDte\" value=\"\" readonly=\"\">
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                </div>                                                                
                                                            </div></td>"
                                            . "<td class=\"lovtd\"><div class=\"form-group form-group-sm col-md-12\">
                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%\">
                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"usrPrflEdtRow_WWW123WWW_EndDte\" value=\"\" readonly=\"\">
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                </div>                                                                
                                                            </div>
                                               </td>
                                                <td class=\"lovtd\"> 
                                                   &nbsp;
                                                </td>"
                                            . "</tr>");
                                    ?> 
                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;">     
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('userRolesTable', 0, '<?php echo $nwRowHtml; ?>');">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                New Role
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveOneUserForm();">
                                                <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Save Roles
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    $colClassType1 = "col-lg-3";
                                    $colClassType2 = "col-lg-3";
                                }
                                ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="userRolesSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncOneUser(event, 'myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'userRolesForm', 'View/Edit User', <?php echo $pkID; ?>, 1, 1, '');">
                                        <input id="userRolesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getOneUserForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'userRolesForm', 'View/Edit User', <?php echo $pkID; ?>, 1, 1, 'clear');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getOneUserForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'userRolesForm', 'View/Edit User', <?php echo $pkID; ?>, 1, 1, '');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType1; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="userRolesDsplySze" style="min-width:70px !important;">                            
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
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="userRolesSortBy">
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
                                                <a class="rhopagination" href="javascript:getOneUserForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'userRolesForm', 'View/Edit User', <?php echo $pkID; ?>, 1, 1,'previous');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="rhopagination" href="javascript:getOneUserForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'userRolesForm', 'View/Edit User', <?php echo $pkID; ?>, 1, 1,'next');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="userRolesTable" cellspacing="0" width="100%" style="width:100%;min-width: 800px !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Role Name</th>
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
                                                <tr id="usrPrflEdtRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtUsers === true) { ?>
                                                            <div class="form-group form-group-sm col-md-12">
                                                                <div class="input-group"  style="width:100%;">
                                                                    <input type="text" class="form-control" aria-label="..." id="usrPrflEdtRow<?php echo $cntr; ?>_RoleNm" value="<?php echo $row1[0]; ?>">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="usrPrflEdtRow<?php echo $cntr; ?>_RoleID" value="<?php echo $row1[3]; ?>">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="usrPrflEdtRow<?php echo $cntr; ?>_DfltRowID" value="<?php echo $row1[4]; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'User Roles', '', '', '', 'radio', true, '<?php echo $row1[3]; ?>', 'usrPrflEdtRow<?php echo $cntr; ?>_RoleID', 'usrPrflEdtRow<?php echo $cntr; ?>_RoleNm', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $row1[0]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtUsers === true) { ?>
                                                            <div class="form-group form-group-sm col-md-12">
                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                    <input class="form-control" size="16" type="text" id="usrPrflEdtRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $row1[1]; ?>" readonly="">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>                                                                
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $row1[1]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtUsers === true) { ?>
                                                            <div class="form-group form-group-sm col-md-12">
                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                    <input class="form-control" size="16" type="text" id="usrPrflEdtRow<?php echo $cntr; ?>_EndDte" value="<?php echo $row1[2]; ?>" readonly="">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>                                                                
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $row1[2]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row1[4] . "|sec.sec_users_n_roles|dflt_row_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                ?>
                <form class="form-horizontal" id="newUserForm" style="padding:5px 20px 5px 20px;">
                    <div class="row">
                        <div class="form-group form-group-sm">
                            <label for="usrPrflAccountName" class="control-label col-md-4">User Name:</label>
                            <div  class="col-md-8">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="usrPrflAccountName" value="">
                                <input type="hidden" class="form-control" aria-label="..." id="usrPrflAccountID" value="-1">
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="usrPrflAcntOwner" class="control-label col-md-4">Owned / Used By:</label>
                            <div  class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="usrPrflAcntOwner" value="" readonly="true">
                                    <input type="hidden" class="form-control" aria-label="..." id="usrPrflAcntOwnerLocID" value="">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '', 'usrPrflAcntOwnerLocID', 'usrPrflAcntOwner', 'clear', 1, '');">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group form-group-sm">
                            <label for="usrPrflLnkdCstmr" class="control-label col-md-4">Linked Customer/Supplier:</label>
                            <div  class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="..." id="usrPrflLnkdCstmr" value="" readonly="true">
                                    <input type="hidden" class="form-control" aria-label="..." id="usrPrflLnkdCstmrID" value="">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', '', '', '', 'radio', true, '', 'usrPrflLnkdCstmrID', 'usrPrflLnkdCstmr', 'clear', 1, '');">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group form-group-sm">
                            <label for="usrPrflVldtyStartDate" class="control-label col-md-4">Start Date:</label>
                            <div class="col-md-8">
                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss">
                                    <input class="form-control" size="16" type="text" id="usrPrflVldtyStartDate" value="" readonly="">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="usrPrflVldtyEndDate" class="control-label col-md-4">End Date:</label>
                            <div class="col-md-8">
                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss">
                                    <input class="form-control" size="16" type="text" id="usrPrflVldtyEndDate" value="" readonly="">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>                
                        <div class="form-group form-group-sm">
                            <label for="usrPrflModulesNeeded" class="control-label col-md-4">Modules Needed:</label>
                            <div class="col-md-8">
                                <select class="form-control selectpicker" id="usrPrflModulesNeeded">  
                                    <option value="" selected disabled>Please Select...</option>
                                    <?php
                                    $valslctdArry = array(
                                        "", "", "", "", "",
                                        "", "", "", "", "",
                                        "", "", "", "", "",
                                        "", "", "", "", "",
                                        "", "", "", "", "", "");
                                    $optionsArrys = array(
                                        "Person Records Only",
                                        "Point of Sale Only",
                                        "Accounting Only",
                                        "Person Records with Accounting Only",
                                        "Sales with Accounting Only",
                                        "Accounting with Payroll Only",
                                        "Person Records + Hospitality Only",
                                        "Person Records + Events Only",
                                        "Basic Modules Only",
                                        "Basic Modules + Hospitality Only",
                                        "Basic Modules + Events Only",
                                        "Basic Modules + Projects Only",
                                        "Basic Modules + Appointments Only",
                                        "Basic Modules + PMS Only",
                                        "Basic Modules + Events + Hospitality Only",
                                        "Basic Modules - Payroll - Person Records + Events + Hospitality Only",
                                        "Basic Modules + Payroll - Person Records + Events + Hospitality Only",
                                        "Basic Modules + Events + PMS Only",
                                        "Basic Modules + Projects + PMS Only",
                                        "Basic Modules + Projects + Hospitality Only",
                                        "Basic Modules + Projects + Events Only",
                                        "Basic Modules + Events + Hospitality + PMS Only",
                                        "Basic Modules + Projects + Hospitality + PMS Only",
                                        "Basic Modules + Events + Projects + Hospitality Only",
                                        "Basic Modules + Events + Projects + Hospitality + PMS Only",
                                        "All Modules");

                                    for ($z = 0; $z < count($optionsArrys); $z++) {
                                        /* if ($srchIn == $srchInsArrys[$z]) {
                                          $valslctdArry[$z] = "selected";
                                          } */
                                        ?>
                                        <option value="<?php echo $optionsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $optionsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> 
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveOneUserForm();">Save Changes</button>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 3) {
                echo "Edit User Form";
            } else if ($vwtyp == 4) {
                /* User Profile */
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Role Name";
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 1000;
                echo "<div>
				<ul class=\"breadcrumb\" style=\"$breadCrmbBckclr\">
					<li onclick=\"openATab('#home', 'grp=40&typ=1');\">
                                                <i class=\"fa fa-home\" aria-hidden=\"true\"></i>
						<span style=\"text-decoration:none;\">Home</span>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>
                                        <li>
                                                <span style=\"text-decoration:none;\">User Account Profile</span>
					</li>
                                       </ul>
                                     </div>";
                $pkID = isset($_POST['sbmtdUserID']) ? $_POST['sbmtdUserID'] : -1;
                if ($pkID != $usrID) {
                    $pkID = $usrID;
                }
                $result = get_OneUser($pkID);
                ?>
                <div class="container-fluid">
                    <form id='usrPrflForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <div class="col-lg-5" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="usrPrflSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncUsrPrfl(event, '', '#profile', 'grp=3&typ=1&pg=1&vtyp=4&sbmtdUserID=<?php echo $pkID; ?>');">
                                    <input id="usrPrflPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getUsrPrfl('clear', '#profile', 'grp=3&typ=1&pg=1&vtyp=4&sbmtdUserID=<?php echo $pkID; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getUsrPrfl('', '#profile', 'grp=3&typ=1&pg=1&vtyp=4&sbmtdUserID=<?php echo $pkID; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>                                        
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="usrPrflDsplySze" style="min-width:65px !important;">                            
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
                            <div class="col-lg-3">
                                <div class="input-group">                        
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="usrPrflSortBy">
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
                            <div class="col-lg-2">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getUsrPrfl('previous', '#profile', 'grp=3&typ=1&pg=1&vtyp=4&sbmtdUserID=<?php echo $pkID; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getUsrPrfl('next', '#profile', 'grp=3&typ=1&pg=1&vtyp=4&sbmtdUserID=<?php echo $pkID; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row rhoRowMargin">
                            <?php
                            while ($row = loc_db_fetch_array($result)) {
                                ?>
                                <fieldset class=""><legend class="basic_person_lg">User Summary Information</legend>
                                    <div class="col-md-4">
                                        <div class="row form-group form-group-sm">
                                            <label for="userName" class="control-label col-md-5">User Name:</label>
                                            <div class="col-md-7">
                                                <span><?php echo $row[0]; ?></span>
                                            </div>
                                        </div> 
                                        <div class="row form-group form-group-sm">
                                            <label for="ownedBy" class="control-label col-md-5">Owned By:</label>
                                            <div  class="col-md-7">
                                                <span><?php echo $row[1]; ?></span>
                                            </div>
                                        </div>  
                                        <div class="row form-group form-group-sm">
                                            <label for="modulesNeeded" class="control-label col-md-5">Modules Needed:</label>
                                            <div  class="col-md-7">
                                                <span><?php echo $row[14]; ?></span>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row form-group form-group-sm">
                                            <label for="failedLgns" class="control-label col-md-6">Failed Login Attempts:</label>
                                            <div  class="col-md-6">
                                                <span><?php echo $row[6]; ?></span>
                                            </div>
                                        </div>     
                                        <div class="row form-group form-group-sm">
                                            <label for="lastLgns" class="control-label col-md-6">Last Login Attempt:</label>
                                            <div  class="col-md-6">
                                                <span><?php echo $row[7]; ?></span>
                                            </div>
                                        </div>
                                        <div class="row form-group form-group-sm">
                                            <label for="lastChnge" class="control-label col-md-6">Last Password Change:</label>
                                            <div  class="col-md-6">
                                                <span><?php echo $row[8]; ?></span>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row form-group form-group-sm">
                                            <label for="pswwdAge" class="control-label col-md-6">Password Age:</label>
                                            <div  class="col-md-6">
                                                <span><?php echo $row[9]; ?></span>
                                            </div>
                                        </div>     
                                        <div class="row form-group form-group-sm">
                                            <label for="userVldStrtDate" class="control-label col-md-6">Account Start Date:</label>
                                            <div  class="col-md-6">
                                                <span><?php echo $row[2]; ?></span>
                                            </div>
                                        </div>
                                        <div class="row form-group form-group-sm">
                                            <label for="userVldEndDate" class="control-label col-md-6">Account End Date:</label>
                                            <div  class="col-md-6">
                                                <span><?php echo $row[3]; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <?php
                        }
                        $extrWhere = " and (now() between to_timestamp(a.valid_start_date, 'YYYY-MM-DD HH24:MI:SS') and to_timestamp(a.valid_end_date, 'YYYY-MM-DD HH24:MI:SS'))";
                        $total = get_TtlUsersRoles($srchFor, $srchIn, $pkID, $extrWhere);
                        if ($pageNo > ceil($total / $lmtSze)) {
                            $pageNo = 1;
                        } else if ($pageNo < 1) {
                            $pageNo = ceil($total / $lmtSze);
                        }

                        $curIdx = $pageNo - 1;
                        $result1 = get_UsersRoles($srchFor, $srchIn, $curIdx, $lmtSze, $pkID, $sortBy, $extrWhere);
                        $colClassType1 = "col-lg-2";
                        $colClassType2 = "col-lg-3";
                        $colClassType3 = "col-lg-4";
                        ?>
                        <div class="row">
                            <fieldset class=""><legend class="basic_person_lg">Assigned User Roles</legend>
                                <div class="row">
                                    <div class="col-lg-5" style="padding:0px 0px 0px 1px !important;">                    
                                        <div class="form-group form-group-sm">
                                            <label for="crntOrgName" class="control-label col-md-3">Current Organisation:</label>
                                            <div  class="col-md-9">
                                                <span><?php echo $orgName; ?></span>
                                                <!--<div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="crntOrgName" value="<?php echo $orgName; ?>">
                                                    <input type="hidden" id="crntOrgID" value="<?php echo $orgID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Organisations', '', '', '', 'radio', true, '<?php echo $orgID; ?>', 'crntOrgID', 'crntOrgName', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="col-lg-2">
                                        <button type="button" class="btn btn-default btn-sm" style="width:100% !important;" onclick="checkAllBtns('usrPrflForm');">
                                            <img src="cmn_images/check_all.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                            Check All
                                        </button>
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="button" class="btn btn-default btn-sm" style="width:100% !important;" onclick="unCheckAllBtns('usrPrflForm');">
                                            <img src="cmn_images/selection_delete.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                            UnCheck All
                                        </button>
                                    </div>
                                    <div class="col-lg-3"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button></div>
                                    -->
                                    <!--<div class="col-lg-3">&nbsp;</div>
                                    <div class="col-lg-4"></div>-->
                                </div>
                                <div class="row"> 
                                    <div  class="col-md-12">
                                        <table class="table table-striped table-bordered table-responsive" id="usrPrflTable" cellspacing="0" width="100%" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <!--<th>...</th>-->
                                                    <th>No.</th>
                                                    <th>Role Name</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
	<?php if($canVwRcHstry === true){?>
		<th>...</th>
    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cntr = 0;
                                                $slctd = ";" . $_SESSION['ROLE_SET_IDS'] . ";";
                                                while ($row1 = loc_db_fetch_array($result1)) {
                                                    $cntr += 1;
                                                    $chckd = "";
                                                    if ($slctd !== ";;" && strpos($slctd, ";" . $row[3] . ";") === false) {
                                                        $chckd = "";
                                                    } else if ($slctd !== ";;") {
                                                        $chckd = "checked=\"checked\"";
                                                    }
                                                    ?>
                                                    <tr id="usrPrflRow<?php echo $cntr; ?>"> 
                                                        <!--<td><input type="checkbox" name="usrPrflChkbx<?php echo $cntr; ?>" value="<?php echo $row[3] . ";" . $row[1]; ?>" <?php echo $chckd ?>></td>-->
                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                        <?php if ($canEdtUsers === true) { ?>
                                                        <?php } ?>
                                                        <td class="lovtd"><?php echo $row1[0]; ?></td>
                                                        <td class="lovtd"><?php echo $row1[1]; ?></td>
                                                        <td class="lovtd"><?php echo $row1[2]; ?></td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row1[4] . "|sec.sec_users_n_roles|dflt_row_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                </div>
                <?php
            }
        }
    }
}