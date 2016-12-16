<?php

$menuItems = array("Users & their Roles", "Roles & Priviledges",
    "Modules & Priviledges", "Extra Info Labels", "Security Policies", "Server Settings",
    "Track User Logins", "Audit Trail Tables", /*"News Items/Articles",*/ "Load all Modules Requirements");
$menuImages = array("reassign_users.png", "groupings.png", "Folder.png", "info_ico2.gif",
    "login.jpg", "antenna1.png", "user-mapping.ico", "safe-icon.png", /*"notes06.gif",*/ "98.png");

$mdlNm = "System Administration";
$ModuleName = $mdlNm;
$pageHtmlID = "sysAdminPage";
$dfltPrvldgs = array("View System Administration", "View Users & their Roles",
    /* 2 */ "View Roles & their Priviledges", "View Registered Modules & their Priviledges",
    /* 4 */ "View Security Policies", "View Server Settings", "View User Logins",
    /* 7 */ "View Audit Trail Tables", "Add New Users & their Roles", "Edit Users & their Roles",
    /* 10 */ "Add New Roles & their Priviledges", "Edit Roles & their Priviledges",
    /* 12 */ "Add New Security Policies", "Edit Security Policies", "Add New Server Settings",
    /* 15 */ "Edit Server Settings", "Set manual password for users",
    /* 17 */ "Send System Generated Passwords to User Mails",
    /* 18 */ "View SQL", "View Record History", "Add/Edit Extra Info Labels", "Delete Extra Info Labels",
    /* 22 */ "Add Articles","Edit Articles","Delete Articles");
$canview = test_prmssns($dfltPrvldgs[0], $mdlNm) || ($pgNo == 9 && test_prmssns("View Self-Service", "Self Service"));
$vwtyp = "0";
$qstr = "";
$dsply = "";
$actyp = "";
$srchFor = "";
$srchIn = "Name";
$PKeyID = -1;
if (isset($formArray)) {
    if (count($formArray) > 0) {
        $vwtyp = isset($formArray['vtyp']) ? cleanInputData($formArray['vtyp']) : "0";
        $qstr = isset($formArray['q']) ? cleanInputData($formArray['q']) : '';
    } else {
        $vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "0";
    }
} else {
    $vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "0";
}

if (isset($_POST['PKeyID'])) {
    $PKeyID = cleanInputData($_POST['PKeyID']);
}

if (isset($_POST['query'])) {
    $srchFor = cleanInputData($_POST['query']);
} else if (isset($_POST['searchfor'])) {
    $srchFor = cleanInputData($_POST['searchfor']);
}

if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}

if (isset($_POST['queryIn'])) {
    $srchIn = cleanInputData($_POST['queryIn']);
} else if (isset($_POST['searchin'])) {
    $srchIn = cleanInputData($_POST['searchin']);
}

if (isset($_POST['q'])) {
    $qstr = cleanInputData($_POST['q']);
}

if (isset($_POST['vtyp'])) {
    $vwtyp = cleanInputData($_POST['vtyp']);
}
if (isset($_POST['actyp'])) {
    $actyp = cleanInputData($_POST['actyp']);
}
$qStrtDte = "";
$qEndDte = "";
$artCategory = "";
$isMaster = "0";
if (isset($_POST['qStrtDte'])) {
    $qStrtDte = cleanInputData($_POST['qStrtDte']);
    if (strlen($qStrtDte) == 19) {
        $qStrtDte = substr($qStrtDte, 0, 10) . " 00:00:00";
    } else {
        $qStrtDte = "";
    }
}

if (isset($_POST['qEndDte'])) {
    $qEndDte = cleanInputData($_POST['qEndDte']);
    if (strlen($qEndDte) == 19) {
        $qEndDte = substr($qEndDte, 0, 10) . " 23:59:59";
    } else {
        $qEndDte = "";
    }
}

if (isset($_POST['artCategory'])) {
    $artCategory = cleanInputData($_POST['artCategory']);
}

if (isset($_POST['isMaster'])) {
    $isMaster = cleanInputData($_POST['isMaster']);
}

if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}
$cntent = "<div>
				<ul class=\"breadcrumb\" style=\"$breadCrmbBckclr\">
					<li onclick=\"openATab('#home', 'grp=40&typ=1');\">
                                                <i class=\"fa fa-home\" aria-hidden=\"true\"></i>
						<span style=\"text-decoration:none;\">Home</span>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>
					<li onclick=\"openATab('#allmodules', 'grp=40&typ=5');\">
						<span style=\"text-decoration:none;\">All Modules</span><span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                $inptUsrID = cleanInputData($_POST['pKeyID']);
                echo deleteUser($inptUsrID);
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                
            } else if ($actyp == 2) {
                //User Roles
                //var_dump($_POST);
                header("content-type:application/json");
                $rowsToUpdte = json_decode($_POST['rows'], true);
                if (is_multi($rowsToUpdte) === FALSE) {
                    $inptUsrID = cleanInputData($rowsToUpdte['UserID']);
                    $DefaultRowID = cleanInputData($rowsToUpdte['DefaultRowID']);
                    $RoleID = cleanInputData($rowsToUpdte['RoleID']);
                    $StartDate = cleanInputData($rowsToUpdte['StartDate']);
                    $EndDate = cleanInputData($rowsToUpdte['EndDate']);

                    if ($DefaultRowID <= 0) {
                        $DefaultRowID = getUsrIDHvThsRoleID($inptUsrID, $RoleID);
                        if ($DefaultRowID <= 0) {
                            asgnRoleToUserWthDte($inptUsrID, $RoleID, $StartDate, $EndDate);
                        } else {
                            updtRoleToUserWthDte($DefaultRowID, $StartDate, $EndDate);
                        }
                    } else {
                        updtRoleToUserWthDte($DefaultRowID, $StartDate, $EndDate);
                    }
                } else {
                    for ($i = 0; $i < count($rowsToUpdte); $i++) {
                        $rowToUpdte = $rowsToUpdte[$i];
                        $inptUsrID = cleanInputData($rowToUpdte['UserID']);
                        $DefaultRowID = cleanInputData($rowToUpdte['DefaultRowID']);
                        $RoleID = cleanInputData($rowToUpdte['RoleID']);
                        $StartDate = cleanInputData($rowToUpdte['StartDate']);
                        $EndDate = cleanInputData($rowToUpdte['EndDate']);
                        //getUsrIDHvThsRoleID
                        if ($DefaultRowID <= 0) {
                            $DefaultRowID = getUsrIDHvThsRoleID($inptUsrID, $RoleID);
                            if ($DefaultRowID <= 0) {
                                asgnRoleToUserWthDte($inptUsrID, $RoleID, $StartDate, $EndDate);
                            } else {
                                updtRoleToUserWthDte($DefaultRowID, $StartDate, $EndDate);
                            }
                        } else {
                            updtRoleToUserWthDte($DefaultRowID, $StartDate, $EndDate);
                        }
                    }
                }


                echo json_encode(array(
                    'success' => true, 'message' => 'Saved Successfully', 'data' => array('src' => 'Role(s) Successfully Saved!'),
                    'total' => '1',
                    'errors' => ''
                ));
            } else if ($actyp == 3) {
                //Articles
                //var_dump($_POST);
                header("content-type:application/json");
                //categoryCombo
                $inptUserID = cleanInputData($_POST['userID']);
                $prsnLocID = cleanInputData($_POST['prsnLocID']);
                $userAccntName = cleanInputData($_POST['userAccntName']);
                $vldtyStrtDte = cleanInputData($_POST['vldtyStrtDte']);
                $vldtyEndDte = cleanInputData($_POST['vldtyEndDte']);

                if ($vldtyStrtDte != "") {
                    $vldtyStrtDte = cnvrtDMYTmToYMDTm($vldtyStrtDte);
                }
                if ($vldtyEndDte != "") {
                    $vldtyEndDte = cnvrtDMYTmToYMDTm($vldtyEndDte);
                }
                $oldUserID = getGnrlRecID2("sec.sec_users", "user_name", "user_id", $userAccntName);
                $ownrID = getPersonID($prsnLocID);
                $cstmrID = -1;
                //var_dump($vldtyEndDte."<br/>");
                //var_dump($vldtyStrtDte);
                //exit();
                if ($userAccntName != "" && $prsnLocID != "" && ($oldUserID <= 0 || $oldUserID == $inptUserID)) {
                    if ($inptUserID <= 0) {
                        $pwd = getRandomPswd();
                        createUser($userAccntName, $ownrID, $vldtyStrtDte, $vldtyEndDte, $pwd, $cstmrID);
                    } else {
                        updateUser($inptUserID, $userAccntName, $ownrID, $vldtyStrtDte, $vldtyEndDte, $cstmrID);
                    }
                    echo json_encode(array(
                        'success' => true,
                        'message' => 'Saved Successfully',
                        'data' => array('src' => 'User Successfully Saved!'),
                        'total' => '1',
                        'errors' => ''
                    ));
                    exit();
                } else {
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Save Failed!',
                        'data' => array('src' => 'Failed to Save User!'),
                        'total' => '1',
                        'errors' => '1'
                    ));
                    exit();
                }
            } else if ($actyp == 4) {
                //Lock/Unlock User
                $inptUsrID = cleanInputData($_POST['pKeyID']);
                $status = cleanInputData($_POST['nwStatus']);
                $fldAttmpts = 0;
                if ($status == "LOCK") {
                    $fldAttmpts = get_CurPlcy_Mx_Fld_lgns() + 1;
                } else {
                    $fldAttmpts = 0;
                }
                echo chngUsrLockStatus($inptUsrID, $fldAttmpts);
            } else if ($actyp == 5) {
                //Suspend/Unsuspend User
                $inptUsrID = cleanInputData($_POST['pKeyID']);
                $status = strtoupper(cleanInputData($_POST['nwStatus']));
                if ($status == "SUSPEND") {
                    echo chngUsrSuspensionStatus($inptUsrID, "TRUE");
                } else {
                    echo chngUsrSuspensionStatus($inptUsrID, "FALSE");
                }
            }
        } else {
            if ($pgNo == 0) {
                $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=8&typ=1');\">
						<span style=\"text-decoration:none;\">SysAdmin Menu</span>
					</li>
                                       </ul>
                                     </div>" . "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
      <!--<h4>WELCOME TO THE SYSTEM ADMINISTRATION</h4>-->
      <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                    font-weight:normal;\">This is where all user account settings and Site Information Setups are done. The module has the ff areas:</span>
                    </div>
      <p>";

                $grpcntr = 0;
                for ($i = 0; $i < count($menuItems); $i++) {
                    $No = $i + 1;
                    if ($i == 0 && test_prmssns($dfltPrvldgs[1], $mdlNm) == FALSE) {
                        continue;
                    } else if ($i == 1 && test_prmssns($dfltPrvldgs[2], $mdlNm) == FALSE) {
                        continue;
                    } else if ($i == 2 && test_prmssns($dfltPrvldgs[3], $mdlNm) == FALSE) {
                        continue;
                    } else if ($i == 3 && test_prmssns($dfltPrvldgs[3], $mdlNm) == FALSE) {
                        continue;
                    } else if ($i == 4 && test_prmssns($dfltPrvldgs[4], $mdlNm) == FALSE) {
                        continue;
                    } else if ($i == 5 && test_prmssns($dfltPrvldgs[5], $mdlNm) == FALSE) {
                        continue;
                    } else if ($i == 6 && test_prmssns($dfltPrvldgs[6], $mdlNm) == FALSE) {
                        continue;
                    } else if ($i == 7 && test_prmssns($dfltPrvldgs[7], $mdlNm) == FALSE) {
                        continue;
                    } else if ($i == 8 && test_prmssns($dfltPrvldgs[11], $mdlNm) == FALSE) {
                        continue;
                    }
                    if ($grpcntr == 0) {
                        $cntent .= "<div class=\"row\">";
                    }
                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=3&typ=1&pg=$No&vtyp=0');\">
            <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
        </button>
            </div>";
                    if ($grpcntr == 3) {
                        $cntent .= "</div>";
                        $grpcntr = 0;
                    } else {
                        $grpcntr = $grpcntr + 1;
                    }
                }
                $cntent .= "
      </p>
    </div>";
                echo $cntent;
            } else if ($pgNo == 1) {
                //Get Users
                if ($vwtyp == 0) {

                    $total = get_UsersTtl($srchFor, $srchIn);

                    $pageNo = isset($_POST['page']) ? $_POST['page'] : 1;
                    $lmtSze = isset($_POST['limit']) ? $_POST['limit'] : 1;
                    $start = isset($_POST ['start']) ? $_POST['start'] : 0;

                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_UsersTblr($srchFor, $srchIn, $curIdx, $lmtSze);
                    $userss = array();
                    $cntr = 0;
                    $prm = get_CurPlcy_Mx_Fld_lgns();
                    while ($row = loc_db_fetch_array($result)) {
                        $chckd = ($cntr == 0) ? TRUE : FALSE;
                        $users = array(
                            'checked' => var_export($chckd, TRUE),
                            'UserID' => $row[9],
                            'RowNum' => ($curIdx * $lmtSze) + ($cntr + 1),
                            'PersonLocID' => $row[10],
                            'PersonName' => $row[1],
                            'UserName' => $row[0],
                            'StartDate' => $row[2],
                            'EndDate' => $row[3],
                            'LastPswdChange' => $row[8],
                            'LastLgnAttmpt' => $row[7],
                            'FailedLgnAttmpts' => $row[6],
                            'ActiveRoles' => $row[11],
                            'IsAccntSuspended' => var_export(($row[4] == '1' ? TRUE : FALSE), TRUE),
                            'IsPasswordTemp' => var_export(($row[5] == '1' ? TRUE : FALSE), TRUE),
                            'IsAccountLocked' => var_export(($row[6] >= $prm ? TRUE : FALSE), TRUE),
                            'IsPswdExpired' => var_export(($row[12] == '1' ? TRUE : FALSE), TRUE));
                        $userss[] = $users;
                        $cntr++;
                    } echo json_encode(array('success' => true,
                        'total' => $total,
                        'rows' => $userss));
                } else if ($vwtyp == 1) {
                    //Get LOV Possible Values 
                    //$brghtsqlStr = "";
                    //$is_dynamic = FALSE;
                    $pkID = isset($_POST['pkUserID']) ? $_POST['pkUserID'] : -1;

                    $total = get_TtlUsersRoles($srchFor, $srchIn, $pkID);
                    //$total = getTtlLovValues($srchFor, $srchIn, $brghtsqlStr, $pkID, $is_dynamic, -1, "", "");
                    $pageNo = isset($_POST['page']) ? $_POST['page'] : 1;
                    $lmtSze = isset($_POST['limit']) ? $_POST['limit'] : 1;
                    $start = isset($_POST['start']) ? $_POST['start'] : 0;

                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    }
                    $curIdx = $pageNo - 1;

                    //
                    $result = get_UsersRoles($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                    $lovsDts = array();
                    $cntr = 0;
                    while ($row = loc_db_fetch_array($result)) {
                        //$chckd = FALSE;
                        $lovsDt = array(
                            'DefaultRowID' => $row[4],
                            'RoleID' => $row[3],
                            'UserID' => $pkID,
                            'RowNum' => ($curIdx * $lmtSze) + ($cntr + 1),
                            'RoleName' => $row[0],
                            'StartDate' => $row[1],
                            'EndDate' => $row[2]);
                        $lovsDts[] = $lovsDt;
                        $cntr++;
                    }

                    echo json_encode(array('success' => true,
                        'total' => $total,
                        'rows' => $lovsDts));
                }
            } else if ($pgNo == 2) {
                //require "roles_n_prvdgs.php";
            } else if ($pgNo == 3) {
                //require "mdls_n_prvldgs.php";
            } else if ($pgNo == 4) {
                //require "extr_inf_lbls.php";
            } else if ($pgNo == 5) {
                //require "sec_plycs.php";
            } else if ($pgNo == 6) {
                //require "srvr_sttngs.php";
            } else if ($pgNo == 7) {
                //Get Users
                if ($vwtyp == 0) {
                    $pageNo = isset($_POST['page']) ? $_POST['page'] : 1;
                    $lmtSze = isset($_POST['limit']) ? $_POST['limit'] : 1;
                    $start = isset($_POST ['start']) ? $_POST['start'] : 0;
                    $shwFld = isset($_POST ['qShwFailedOnly']) ? cleanInputData($_POST['qShwFailedOnly']) : true;
                    $shw_sccfl = isset($_POST ['qShwSccflOnly']) ? cleanInputData($_POST['qShwSccflOnly']) : true;
                    $total = get_UserLgnsTtl($srchFor, $srchIn, $shwFld, $shw_sccfl);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_UserLgns($srchFor, $srchIn, $curIdx, $lmtSze, $shwFld, $shw_sccfl);
                    $parentArry = array();
                    $cntr = 0;
                    while ($row = loc_db_fetch_array($result)) {
                        $chckd = ($cntr == 0) ? TRUE : FALSE;
                        $childArray = array(
                            'checked' => var_export($chckd, TRUE),
                            'UserID' => $row[5],
                            'RowNum' => ($curIdx * $lmtSze) + ($cntr + 1),
                            'UserName' => $row[0],
                            'LoginTime' => $row[1],
                            'LogoutTime' => $row[2],
                            'MachineDetails' => $row[3],
                            'LoginNumber' => $row[6],
                            'WasLgnAttmpSuccfl' => ($row[4] == '1' ? "TRUE" : "FALSE"));
                        $parentArry[] = $childArray;
                        $cntr++;
                    } echo json_encode(array('success' => true,
                        'total' => $total,
                        'rows' => $parentArry));
                }
            } else if ($pgNo == 8) {
                //require "adt_trail.php";
            } else if ($pgNo == 9) {
                //require "adt_trail.php";
                loadMdlsNthrRolesNLovs();
            } else {
                restricted();
            }
        }
    } else {
        restricted();
    }
}

function asgnRoleToUserWthDte($uID, $roleID, $strtDate, $endDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO sec.sec_users_n_roles (user_id, role_id, valid_start_date, valid_end_date, created_by, 
creation_date, last_update_by, last_update_date) VALUES (" . $uID . ", " .
            $roleID . ", '" . $strtDate .
            "', '$endDate', " . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr . "')";
    executeSQLNoParams($sqlStr);
}

function updtRoleToUserWthDte($rowID, $strtDate, $endDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE sec.sec_users_n_roles "
            . "SET valid_start_date='$strtDate', 
                valid_end_date='$endDate', 
                last_update_by=$usrID, last_update_date='" . $dateStr . "' WHERE dflt_row_id = " . $rowID;
    //echo $sqlStr;
    executeSQLNoParams($sqlStr);
}

function getUsrIDHvThsRoleID($user_ID, $role_ID) {
    $sqlStr = "SELECT dflt_row_id FROM sec.sec_users_n_roles WHERE ((user_id = $user_ID) 
        AND (role_id = $role_ID))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_UsersRoles($searchFor, $searchIn, $offset, $limit_size, $pkID) {
    $wherecls = "";
    $strSql = "";
    if ($searchIn == "Role Name") {
        $wherecls = " and (b.role_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else {
        $wherecls = " and (b.role_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "SELECT b.role_name, 
    a.valid_start_date, a.valid_end_date, a.role_id, a.dflt_row_id " .
            "FROM sec.sec_users_n_roles a, sec.sec_roles b "
            . "WHERE ((a.role_id = b.role_id) AND (a.user_id = " . $pkID .
            ")$wherecls) ORDER BY 1 LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_TtlUsersRoles($searchFor, $searchIn, $pkID) {
    $wherecls = "";
    $strSql = "";
    if ($searchIn == "Role Name") {
        $wherecls = " and (b.role_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else {
        $wherecls = " and (b.role_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "SELECT count(1) " .
            "FROM sec.sec_users_n_roles a, sec.sec_roles b "
            . "WHERE ((a.role_id = b.role_id) AND (a.user_id = " . $pkID .
            ")$wherecls)";


    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_UsersTblr($searchFor, $searchIn, $offset, $limit_size) {

    $wherecls = "";
    $strSql = "";
    if ($searchIn == "Owned By") {
        $wherecls = " and (concat(b.sur_name, ', ', b.first_name, ' ', " +
                "b.other_names) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "User Name") {
        $wherecls = " and (a.user_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Role Name") {
        $wherecls = " and (d.role_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }

    $strSql = "SELECT distinct a.user_name, trim(b.title || ' ' || b.sur_name || ', ' || b.first_name " .
            "|| ' ' || b.other_names) fullname, a.valid_start_date, a.valid_end_date, "
            . "CASE WHEN a.is_suspended THEN '1' ELSE '0' END, "
            . "CASE WHEN a.is_pswd_temp THEN '1' ELSE '0' END, "
            . "a.failed_login_atmpts, a.last_login_atmpt_time, a.last_pswd_chng_time, "
            . "a.user_id, b.local_id_no, (Select count(1) from sec.sec_users_n_roles z where a.user_id = z.user_id "
            . "and to_char(now(), 'YYYY-MM-DD HH24:MI:SS') between z.valid_start_date and z.valid_end_date) active_roles,"
            . "CASE WHEN age(now(), to_timestamp(last_pswd_chng_time, 'YYYY-MM-DD HH24:MI:SS')) " .
            ">= interval '" . get_CurPlcy_Pwd_Exp_Days() . " days' THEN '1' ELSE '0' END is_pswd_exprd "
            . "FROM ((sec.sec_users a " .
            "LEFT OUTER JOIN prs.prsn_names_nos b ON (a.person_id = b.person_id)) LEFT OUTER JOIN " .
            "sec.sec_users_n_roles c ON a.user_id = c.user_id) LEFT OUTER JOIN sec.sec_roles d " .
            "ON d.role_id = c.role_id " .
            "WHERE (1=1$wherecls) ORDER BY a.user_id LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_UsersTtl($searchFor, $searchIn) {
    $wherecls = "";
    $strSql = "";
    if ($searchIn == "Owned By") {
        $wherecls = " and (concat(b.sur_name, ', ', b.first_name, ' ', " +
                "b.other_names) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "User Name") {
        $wherecls = " and (a.user_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Role Name") {
        $wherecls = " and (d.role_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }

    $strSql = "SELECT count(1) FROM (SELECT distinct a.user_name, trim(b.title || ' ' || b.sur_name || ', ' || b.first_name " .
            "|| ' ' || b.other_names) fullname, a.valid_start_date, a.valid_end_date, "
            . "CASE WHEN a.is_suspended THEN '1' ELSE '0' END, "
            . "CASE WHEN a.is_pswd_temp THEN '1' ELSE '0' END, "
            . "a.failed_login_atmpts, a.last_login_atmpt_time, a.last_pswd_chng_time, "
            . "a.user_id, b.person_id "
            . "FROM ((sec.sec_users a " .
            "LEFT OUTER JOIN prs.prsn_names_nos b ON (a.person_id = b.person_id)) LEFT OUTER JOIN " .
            "sec.sec_users_n_roles c ON a.user_id = c.user_id) LEFT OUTER JOIN sec.sec_roles d " .
            "ON d.role_id = c.role_id " .
            "WHERE (1=1$wherecls)) tbl1";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_UserLgns($searchFor, $searchIn, $offset, $limit_size, $shw_faild, $shw_sccfl) {
    $wherecls = "";
    $strSql = "";
    $optional_str1 = "";
    $optional_str2 = "";
    if ($shw_sccfl == false || $shw_faild == false) {
        if ($shw_sccfl == true) {
            $optional_str1 = " AND (a.was_lgn_atmpt_succsful = TRUE)";
        } else {
            $optional_str1 = " AND (a.was_lgn_atmpt_succsful = FALSE)";
        }
    }

    if ($searchIn == "Login Number") {
        $wherecls = " and ((''||a.login_number) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "User Name") {
        if ($searchFor == "") {
            $optional_str2 = " OR (b.user_name IS NULL)";
        }
        $wherecls = " and (b.user_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Login Time") {
        $wherecls = " and (to_char(to_timestamp(a.login_time,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Logout Time") {
        if ($searchFor == "") {
            $optional_str2 = " OR (a.logout_time IS NULL)";
        }
        $wherecls = " and (to_char(to_timestamp(a.logout_time,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Machine Details") {
        $wherecls = " and (a.host_mach_details ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }

    $strSql = "SELECT b.user_name, a.login_time, a.logout_time, a.host_mach_details, " .
            "CASE WHEN a.was_lgn_atmpt_succsful THEN '1' ELSE '0' END, a.user_id, a.login_number "
            . "FROM sec.sec_track_user_logins a " .
            " LEFT OUTER JOIN sec.sec_users b ON a.user_id = b.user_id " .
            "WHERE (1=1" . $wherecls . "" . $optional_str1 . "" . $optional_str2 .
            ") ORDER BY a.login_number DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_UserLgnsTtl($searchFor, $searchIn, $shw_faild, $shw_sccfl) {
    $wherecls = "";
    $strSql = "";
    $optional_str1 = "";
    $optional_str2 = "";
    if ($shw_sccfl == false || $shw_faild == false) {
        if ($shw_sccfl == true) {
            $optional_str1 = " AND (a.was_lgn_atmpt_succsful = TRUE)";
        } else {
            $optional_str1 = " AND (a.was_lgn_atmpt_succsful = FALSE)";
        }
    }

    if ($searchIn == "Login Number") {
        $wherecls = " and ((''||a.login_number) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "User Name") {
        if ($searchFor == "") {
            $optional_str2 = " OR (b.user_name IS NULL)";
        }
        $wherecls = " and (b.user_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Login Time") {
        $wherecls = " and (to_char(to_timestamp(a.login_time,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Logout Time") {
        if ($searchFor == "") {
            $optional_str2 = " OR (a.logout_time IS NULL)";
        }
        $wherecls = " and (to_char(to_timestamp(a.logout_time,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Machine Details") {
        $wherecls = " and (a.host_mach_details ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }

    $strSql = "SELECT count(1) FROM sec.sec_track_user_logins a " .
            " LEFT OUTER JOIN sec.sec_users b ON a.user_id = b.user_id " .
            "WHERE (1=1" . $wherecls . "" . $optional_str1 . "" . $optional_str2 . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createUser($username, $ownrID, $in_strDte, $in_endDte, $pwd, $cstmrID) {
    global $usrID;
    global $smplTokenWord;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO sec.sec_users(usr_password, person_id, is_suspended, is_pswd_temp, " .
            "failed_login_atmpts, user_name, last_login_atmpt_time, last_pswd_chng_time, valid_start_date, " .
            "valid_end_date, created_by, creation_date, last_update_by, last_update_date, customer_id) " .
            "VALUES (md5('" . encrypt($pwd, $smplTokenWord) . "'), " . $ownrID . ", FALSE, TRUE, 0, '" .
            loc_db_escape_string($username) . "', '" . $dateStr . "', '" . $dateStr . "', '" . $in_strDte . "', '" . $in_endDte .
            "', " . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr . "', " . $cstmrID . ")";
    execUpdtInsSQL($insSQL);
}

function updateUser($user_id, $username, $ownrID, $in_strDte, $in_endDte, $cstmrID) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE sec.sec_users SET person_id = " . $ownrID . ", customer_id = " . $cstmrID .
            ", valid_start_date = '" .
            $in_strDte . "', valid_end_date = '" . $in_endDte . "', last_update_by = " .
            $usrID . ", last_update_date = '" . $dateStr . "', user_name = '" . loc_db_escape_string($username) .
            "' WHERE(user_id = " . $user_id . ")";
    execUpdtInsSQL($insSQL);
}

function chngUsrLockStatus($userID, $failedAttempts) {
    //Set failed_login_atmpts in sec.sec_users to 0
    $insSQL = "UPDATE sec.sec_users SET failed_login_atmpts = $failedAttempts 
                            WHERE (user_id = " . $userID . ")";
    $affctd = execUpdtInsSQL($insSQL);
    if ($affctd > 0) {
        $dsply = "Successfully Updated the ff Records-";
        $dsply .= "<br/>$affctd User Account!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Updated!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function chngUsrSuspensionStatus($userID, $nwStatus) {
    //Set failed_login_atmpts in sec.sec_users to 0
    $insSQL = "UPDATE sec.sec_users SET is_suspended = $nwStatus 
                            WHERE (user_id = " . $userID . ")";
    //echo $insSQL;
    $affctd = execUpdtInsSQL($insSQL);
    if ($affctd > 0) {
        $dsply = "Successfully Updated the ff Records-";
        $dsply .= "<br/>$affctd User Account!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Updated!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteUser($userID) {
    $selSQL = "Select count(1) from sec.sec_track_user_logins WHERE user_id = " . $userID;
    $result = executeSQLNoParams($selSQL);
    $lgnsCnt = 0;
    $affctd = 0;
    $affctd1 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $lgnsCnt = $row[0];
    }
    if ($lgnsCnt <= 0) {
        $insSQL = "DELETE FROM sec.sec_users_n_roles WHERE user_id = " . $userID;
        $affctd += execUpdtInsSQL($insSQL);
        $insSQL1 = "DELETE FROM sec.sec_users WHERE user_id = " . $userID;
        $affctd1 += execUpdtInsSQL($insSQL1);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 User(s)!";
        $dsply .= "<br/>$affctd User Role(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!<br/>$lgnsCnt Login(s) exist!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}
?>
                             























