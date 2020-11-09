<?php

$menuItems = array("Users & their Roles", "Roles & Priviledges",
    "Modules & Priviledges", "Extra Info Labels", "Security Policies", "Server Settings",
    "Track User Logins", "Audit Trail Tables", /* "News Items/Notices", */ "Load all Modules Requirements", "Standard Reports");
$menuImages = array("reassign_users.png", "groupings.png", "Folder.png", "info_ico2.gif",
    "login.jpg", "antenna1.png", "53.png", "safe-icon.png", /* "notes06.gif", */ "98.png", "report-icon-png.png");

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
    /* 22 */ "Add Notices", "Edit Notices", "Delete Notices", "View Notices Admin");
$vwtyp = "0";
$qstr = "";
$dsply = "";
$actyp = "";
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

$srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
$srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'All';

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

if (strpos($srchFor, "%") === FALSE) {
    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
    $srchFor = str_replace("%%", "%", $srchFor);
}
$cntent = "<div>
				<ul class=\"breadcrumb\" style=\"$breadCrmbBckclr\">
					<li onclick=\"openATab('#home', 'grp=40&typ=1');\">
                                                <i class=\"fa fa-home\" aria-hidden=\"true\"></i>
						<span style=\"text-decoration:none;\">Home</span>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>
					<li onclick=\"openATab('#allmodules', 'grp=40&typ=5');\">
						<span style=\"text-decoration:none;\">All Modules&nbsp;</span>
					</li>";
$canview = test_prmssns($dfltPrvldgs[0], $mdlNm) || ($pgNo == 1 && $vwtyp == 4 && test_prmssns("View Self-Service", "Self Service"));

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=3&typ=1');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">System Administration Menu</span>
					</li>";
        if ($pgNo == 0) {
            $cntent .= "</ul></div>
              <div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
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
                } else if ($i == 9 && test_prmssns($dfltPrvldgs[1], $mdlNm) == FALSE) {
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
            require 'users_n_roles.php';
        } else if ($pgNo == 2) {
            require "roles_n_prvdgs.php";
        } else if ($pgNo == 3) {
            require "mdls_n_prvldgs.php";
        } else if ($pgNo == 4) {
            require "extr_inf_lbls.php";
        } else if ($pgNo == 5) {
            require "sec_plycs.php";
        } else if ($pgNo == 6) {
            require "srvr_sttngs.php";
        } else if ($pgNo == 7) {
            //Get User Logins
            require 'user_lgns.php';
        } else if ($pgNo == 8) {
            require "adt_trail.php";
        } else if ($pgNo == 9) {
            loadMdlsNthrRolesNLovs();
        } else if ($pgNo == 10) {
            require "sec_rpts.php";
        } else {
            restricted();
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
    return execUpdtInsSQL($sqlStr);
}

function updtRoleToUserWthDte($rowID, $strtDate, $endDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE sec.sec_users_n_roles "
            . "SET valid_start_date='$strtDate', 
                valid_end_date='$endDate', 
                last_update_by=$usrID, last_update_date='" . $dateStr . "' WHERE dflt_row_id = " . $rowID;
    //echo $sqlStr;
    execUpdtInsSQL($sqlStr);
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

function get_UsersRoles($searchFor, $searchIn, $offset, $limit_size, $pkID, $sortBy, $extrWhere = "") {
    $wherecls = "";
    $strSql = "";
    $ordrBy = "";
    if ($sortBy == "Role Name") {
        $ordrBy = "ORDER BY b.role_name";
    } else if ($sortBy == "Start Date") {
        $ordrBy = "ORDER BY a.valid_start_date,a.role_id";
    } else {
        $ordrBy = "ORDER BY a.valid_end_date,a.role_id";
    }
    if ($searchIn == "Role Name") {
        $wherecls = " and (b.role_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else {
        $wherecls = " and (b.role_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "SELECT b.role_name, 
    to_char(to_timestamp(a.valid_start_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') valid_start_date, 
    to_char(to_timestamp(a.valid_end_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') valid_end_date, 
    a.role_id, 
    a.dflt_row_id " .
            "FROM sec.sec_users_n_roles a, sec.sec_roles b "
            . "WHERE ((a.role_id = b.role_id) AND (a.user_id = " . $pkID .
            ")$wherecls" . "$extrWhere) " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_TtlUsersRoles($searchFor, $searchIn, $pkID, $extrWhere = "") {
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
        $wherecls = " and (concat(b.title || ' ' || b.sur_name, ', ', b.first_name, ' ', " .
                "b.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
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
            ">= interval '" . get_CurPlcy_Pwd_Exp_Days() . " days' THEN '1' ELSE '0' END is_pswd_exprd , "
            . "a.modules_needed, a.customer_id,  scm.get_cstmr_splr_name(a.customer_id) cstmr_nm, a.last_update_date  "
            . "FROM ((sec.sec_users a " .
            "LEFT OUTER JOIN prs.prsn_names_nos b ON (a.person_id = b.person_id)) LEFT OUTER JOIN " .
            "sec.sec_users_n_roles c ON a.user_id = c.user_id) LEFT OUTER JOIN sec.sec_roles d " .
            "ON d.role_id = c.role_id " .
            "WHERE (1=1$wherecls) ORDER BY a.last_update_date DESC, a.user_id LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_UsersTtl($searchFor, $searchIn) {
    $wherecls = "";
    $strSql = "";
    if ($searchIn == "Owned By") {
        $wherecls = " and (concat(b.title || ' ' || b.sur_name, ', ', b.first_name, ' ', " .
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

function get_OneUser($pkeyID) {

    $strSql = "SELECT distinct a.user_name, "
            . "trim(b.title || ' ' || b.sur_name || ', ' || b.first_name || ' ' || b.other_names) fullname, "
            . " to_char(to_timestamp(a.valid_start_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') valid_start_date, "
            . " to_char(to_timestamp(a.valid_end_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') valid_end_date, "
            . "CASE WHEN a.is_suspended THEN '1' ELSE '0' END, "
            . "CASE WHEN a.is_pswd_temp THEN '1' ELSE '0' END, "
            . "a.failed_login_atmpts, "
            . "to_char(to_timestamp(a.last_login_atmpt_time, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') last_login_atmpt_time, "
            . "to_char(to_timestamp(a.last_pswd_chng_time, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') last_pswd_chng_time, "
            . "age(now(), to_timestamp(last_pswd_chng_time, 'YYYY-MM-DD HH24:MI:SS')) password_age, "
            . "a.user_id, "
            . "b.local_id_no, "
            . "(Select count(1) from sec.sec_users_n_roles z where a.user_id = z.user_id and to_char(now(), 'YYYY-MM-DD HH24:MI:SS') between z.valid_start_date and z.valid_end_date) active_roles,"
            . "CASE WHEN age(now(), to_timestamp(last_pswd_chng_time, 'YYYY-MM-DD HH24:MI:SS')) >= interval '" . get_CurPlcy_Pwd_Exp_Days() . " days' THEN '1' ELSE '0' END is_pswd_exprd , "
            . "a.modules_needed, a.customer_id,  scm.get_cstmr_splr_name(a.customer_id) cstmr_nm "
            . "FROM ((sec.sec_users a " .
            "LEFT OUTER JOIN prs.prsn_names_nos b ON (a.person_id = b.person_id)) LEFT OUTER JOIN " .
            "sec.sec_users_n_roles c ON a.user_id = c.user_id) LEFT OUTER JOIN sec.sec_roles d " .
            "ON d.role_id = c.role_id " .
            "WHERE a.user_id=" . $pkeyID;

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_UserLgns($searchFor, $searchIn, $offset, $limit_size, $shw_faild, $shw_sccfl, $shw_actv) {
    $wherecls = "";
    $strSql = "";
    $optional_str1 = "";
    $optional_str2 = "";
    if ($shw_sccfl === "true") {
        $optional_str1 .= " AND (a.was_lgn_atmpt_succsful = TRUE)";
    }
    if ($shw_faild === "true") {
        $optional_str1 .= " AND (a.was_lgn_atmpt_succsful = FALSE)";
    }
    if ($shw_actv === "true") {
        $optional_str1 .= " AND (a.was_lgn_atmpt_succsful = TRUE AND a.logout_time='')";
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

    $strSql = "SELECT b.user_name, "
            . "to_char(to_timestamp(a.login_time,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') login_time, "
            . "CASE WHEN a.logout_time='' THEN a.logout_time ELSE to_char(to_timestamp(a.logout_time,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END logout_time, "
            . "a.host_mach_details, " .
            "CASE WHEN a.was_lgn_atmpt_succsful THEN 'YES' ELSE 'NO' END, "
            . "a.user_id, a.login_number, CASE WHEN a.last_active_time='' THEN a.last_active_time "
            . "ELSE to_char(to_timestamp(a.last_active_time,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END last_active_time, "
            . "a.lgn_remarks "
            . "FROM sec.sec_track_user_logins a " .
            " LEFT OUTER JOIN sec.sec_users b ON a.user_id = b.user_id " .
            "WHERE (1=1" . $wherecls . "" . $optional_str1 . "" . $optional_str2 .
            ") ORDER BY a.login_number DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_UserLgnsTtl($searchFor, $searchIn, $shw_faild, $shw_sccfl, $shw_actv) {
    $wherecls = "";
    $strSql = "";
    $optional_str1 = "";
    $optional_str2 = "";
    if ($shw_sccfl === "true") {
        $optional_str1 .= " AND (a.was_lgn_atmpt_succsful = TRUE)";
    }
    if ($shw_faild === "true") {
        $optional_str1 .= " AND (a.was_lgn_atmpt_succsful = FALSE)";
    }
    if ($shw_actv === "true") {
        $optional_str1 .= " AND (a.was_lgn_atmpt_succsful = TRUE AND a.logout_time='')";
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

function forceLgOutUserLgns($userid, $inptUsrNm = "") {
    $lgnsCnt = 0;
    $affctd = 0;
    if ($lgnsCnt <= 0) {
        $insSQL = "UPDATE sec.sec_track_user_logins SET logout_time=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')
            WHERE user_id = " . $userid . " and logout_time = '' and was_lgn_atmpt_succsful = 't'";
        $affctd += execUpdtInsSQL($insSQL, "User Name:" . $inptUsrNm);
    }
    if ($affctd > 0) {
        $dsply = "Successfully Updated the ff Records-";
        $dsply .= "<br/>$affctd User Logon(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Updated!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
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

function createRole($rolename, $in_strDte, $in_endDte, $canAssign) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $endDate = "4000-12-31 23:59:59";
    if (strlen($in_strDte) < 19) {
        $in_strDte = $dateStr;
    } else {
        $in_strDte = cnvrtDMYTmToYMDTm($in_strDte);
    }
    if (strlen($in_endDte) < 19) {
        $in_endDte = $endDate;
    } else {
        $in_endDte = cnvrtDMYTmToYMDTm($in_endDte);
    }
    $insSQL = "INSERT INTO sec.sec_roles(role_name, valid_start_date, " .
            "valid_end_date, created_by, creation_date, last_update_by, last_update_date, can_mini_admins_assign) " .
            "VALUES ('" . $rolename . "', '" . $in_strDte . "', '" . $in_endDte .
            "', " . $usrID . ", '" . $dateStr . "', " . $usrID .
            ", '" . $dateStr . "', '" . cnvrtBoolToBitStr($canAssign) . "')";
    return execUpdtInsSQL($insSQL);
}

function createPolicy($plcy_nm, $faild_lgns
, $expry_days, $auto_unlck, $rqr_caps, $rqr_smll, $rqr_dgts, $rqr_wild
, $rgrmnt_cmdntns, $is_dflt, $old_pwd_cnt, $min_pwd_len, $max_pwd_len
, $max_no_recs, $allw_rptn, $allw_unm, $sessn_tme) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO sec.sec_security_policies(" .
            "policy_name, max_failed_lgn_attmpts, pswd_expiry_days, " .
            "auto_unlocking_time_mins, pswd_require_caps, pswd_require_small, " .
            "pswd_require_dgt, pswd_require_wild, pswd_reqrmnt_combntns, is_default, " .
            "created_by, creation_date, last_update_by, last_update_date, " .
            "old_pswd_cnt_to_disallow, pswd_min_length, pswd_max_length, max_no_recs_to_dsply, " .
            "allow_repeating_chars, allow_usrname_in_pswds, session_timeout) " .
            "VALUES ('" . $plcy_nm . "', " . $faild_lgns . ", " . $expry_days . ", " .
            "" . $auto_unlck . ", " . $rqr_caps . ", " . $rqr_smll . ", " .
            "" . $rqr_dgts . ", " . $rqr_wild . ", '" . loc_db_escape_string($rgrmnt_cmdntns) . "', " . $is_dflt . ", " .
            "" . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr . "', " .
            "" . $old_pwd_cnt . ", " . $min_pwd_len . ", " . $max_pwd_len . ", " . $max_no_recs . ", " .
            "" . $allw_rptn . ", " . $allw_unm . ", " . $sessn_tme . ")";
    return execUpdtInsSQL($insSQL);
}

function createEml_Svr($smtp_nm, $mail_unme
, $mail_pwd, $port_no, $is_dflt, $actv_drctry, $ftpserver, $ftpunm, $ftppswd, $ftpport, $ftpdir, $enfc, $pgdir, $bckpdir, $comPrt, $baudRate,
        $timeOut, $ftpHomeDir, $srvrStnSmtpIP) {
    global $usrID;
    global $smplTokenWord;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO sec.sec_email_servers(" .
            "smtp_client, mail_user_name, mail_password, " .
            "smtp_port, is_default, actv_drctry_domain_name, " .
            "created_by, creation_date, last_update_by, last_update_date, " .
            "ftp_server_url, ftp_user_name, ftp_user_pswd, " .
            "ftp_port, ftp_app_sub_directory, enforce_ftp, " .
            "pg_dump_dir, backup_dir, com_port, baud_rate, timeout, ftp_user_start_directory, inhouse_smtp_ip) " .
            "VALUES ('" . loc_db_escape_string($smtp_nm) . "', '" . loc_db_escape_string($mail_unme) .
            "', '" . loc_db_escape_string(encrypt1($mail_pwd, $smplTokenWord)) . "', " .
            "" . $port_no . ", '" . $is_dflt . "', '" . $actv_drctry . "', " .
            "" . $usrID . ", '" . $dateStr . "', " .
            $usrID . ", '" . $dateStr .
            "', '" . loc_db_escape_string($ftpserver) . "', '" . loc_db_escape_string($ftpunm) .
            "', '" . loc_db_escape_string($encrypt1($ftppswd, $smplTokenWord)) . "', " . $ftpport .
            ", '" . loc_db_escape_string($ftpdir) . "', '" . cnvrtBoolToBitStr($enfc) . "','" . loc_db_escape_string($pgdir) .
            "','" . loc_db_escape_string($bckpdir) . "','" . loc_db_escape_string($comPrt) .
            "','" . loc_db_escape_string($baudRate) . "','" . loc_db_escape_string($timeOut) .
            "','" . loc_db_escape_string($ftpHomeDir) . "','" . loc_db_escape_string($srvrStnSmtpIP) . "')";
    return execUpdtInsSQL($insSQL);
}

function asgnPrvlgToRole($prvldg_id, $role_id, $in_strDte, $in_endDte) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $endDate = "4000-12-31 23:59:59";
    if (strlen($in_strDte) < 19) {
        $in_strDte = $dateStr;
    } else {
        $in_strDte = cnvrtDMYTmToYMDTm($in_strDte);
    }
    if (strlen($in_endDte) < 19) {
        $in_endDte = $endDate;
    } else {
        $in_endDte = cnvrtDMYTmToYMDTm($in_endDte);
    }
    $insSQL = "INSERT INTO sec.sec_roles_n_prvldgs(role_id, prvldg_id, valid_start_date, valid_end_date, created_by, " .
            "creation_date, last_update_by, last_update_date) VALUES (" . $role_id . ", " . $prvldg_id . ", '" .
            $in_strDte . "', '" . $in_endDte . "', " . $usrID . ", '" . $dateStr .
            "', " . $usrID . ", '" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function updateRolesPrticulrPrvldg($prvldgID, $roleID, $in_strDte, $in_endDte) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $endDate = "4000-12-31 23:59:59";
    if (strlen($in_strDte) < 19) {
        $in_strDte = $dateStr;
    } else {
        $in_strDte = cnvrtDMYTmToYMDTm($in_strDte);
    }
    if (strlen($in_endDte) < 19) {
        $in_endDte = $endDate;
    } else {
        $in_endDte = cnvrtDMYTmToYMDTm($in_endDte);
    }
    $sqlStr = "UPDATE sec.sec_roles_n_prvldgs SET valid_start_date = '" .
            $in_strDte . "', valid_end_date = '" . $in_endDte . "', last_update_by = " .
            $usrID . ", last_update_date = '" . $dateStr . "' " .
            "WHERE((prvldg_id = " . $prvldgID . ") AND (role_id = " . $roleID . "))";
    //var_dump($sqlStr);
    return execUpdtInsSQL($sqlStr);
}

function asgnMdlToPlcy($plcy_id, $mdl_id, $nwvalue, $nwactions) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO sec.sec_audit_trail_tbls_to_enbl(policy_id, module_id, action_typs_to_track, enable_tracking, created_by, " .
            "creation_date, last_update_by, last_update_date) VALUES (" . $plcy_id . ", " . $mdl_id .
            ", '" . loc_db_escape_string($nwactions) . "', " . $nwvalue . ", " . $usrID . ", '" . $dateStr .
            "', " . $usrID . ", '" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function updateRole($roleID, $rolename, $in_strDte, $in_endDte, $canAssign) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $endDate = "4000-12-31 23:59:59";
    if (strlen($in_strDte) < 19) {
        $in_strDte = $dateStr;
    } else {
        $in_strDte = cnvrtDMYTmToYMDTm($in_strDte);
    }
    if (strlen($in_endDte) < 19) {
        $in_endDte = $endDate;
    } else {
        $in_endDte = cnvrtDMYTmToYMDTm($in_endDte);
    }
    $insSQL = "UPDATE sec.sec_roles SET role_name = '" . loc_db_escape_string($rolename) .
            "', valid_start_date = '" . $in_strDte . "', valid_end_date = '" . $in_endDte . "', last_update_by = " .
            $usrID . ", last_update_date = '" . $dateStr . "', can_mini_admins_assign = '"
            . cnvrtBoolToBitStr($canAssign) . "' " .
            "WHERE(role_id = " . $roleID . ")";
    return execUpdtInsSQL($insSQL);
}

function undefaultAllPlcys() {
    global $usrID;
    //Set is_default to false for all policies where it is true
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE sec.sec_security_policies SET is_default = FALSE, last_update_by = " .
            $usrID . ", last_update_date = '" . $dateStr . "' WHERE (is_default = TRUE)";
    return execUpdtInsSQL($insSQL);
}

function updatePlcy($plcyID, $plcy_nm, $faild_lgns
, $expry_days, $auto_unlck, $rqr_caps, $rqr_smll, $rqr_dgts, $rqr_wild
, $rgrmnt_cmdntns, $is_dflt, $old_pwd_cnt, $min_pwd_len, $max_pwd_len
, $max_no_recs, $allw_rptn, $allw_unm, $sessn_tme) {
    global $usrID;
    //Set is_default to false for all policies where it is true
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE sec.sec_security_policies " .
            "SET policy_name='" . loc_db_escape_string($plcy_nm) . "', max_failed_lgn_attmpts=" .
            $faild_lgns . ", pswd_expiry_days=" . $expry_days . ", " .
            "auto_unlocking_time_mins=" . $auto_unlck . ", pswd_require_caps=" . $rqr_caps . ", pswd_require_small=" . $rqr_smll . ", " .
            "pswd_require_dgt=" . $rqr_dgts . ", pswd_require_wild=" . $rqr_wild . ", pswd_reqrmnt_combntns='" . $rgrmnt_cmdntns . "', " .
            "is_default=" . $is_dflt . ", last_update_by=" . $usrID . ", " .
            "last_update_date='" . $dateStr . "', old_pswd_cnt_to_disallow=" . $old_pwd_cnt . ", pswd_min_length=" . $min_pwd_len . ", " .
            "pswd_max_length=" . $max_pwd_len . ", max_no_recs_to_dsply=" . $max_no_recs . ", allow_repeating_chars=" . $allw_rptn . ", " .
            "allow_usrname_in_pswds=" . $allw_unm . ", session_timeout =" . $sessn_tme .
            " WHERE (policy_id = " . $plcyID . ")";
    return execUpdtInsSQL($sqlStr);
}

function enbldisableTracking($plcyID, $mdlID, $nwvalue) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE sec.sec_audit_trail_tbls_to_enbl SET enable_tracking = " . $nwvalue .
            ", last_update_by = " . $usrID .
            ", last_update_date = '" . $dateStr . "' " .
            "WHERE((policy_id = " . $plcyID . ") AND (module_id = " . $mdlID . "))";
    return execUpdtInsSQL($sqlStr);
}

function updateActnsToTrack($plcyID, $mdlID, $nwvalue, $nwactions) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE sec.sec_audit_trail_tbls_to_enbl SET enable_tracking = " .
            $nwvalue . ", action_typs_to_track = '" . loc_db_escape_string($nwactions) . "', last_update_by = " .
            $usrID . ", last_update_date = '" . $dateStr . "' " .
            "WHERE((policy_id = " . $plcyID . ") AND (module_id = " . $mdlID . "))";
    return execUpdtInsSQL($sqlStr);
}

function undefaultAllEmlSvrs() {
    global $usrID;
    //Set is_default to false for all Email Servers where it is true
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE sec.sec_email_servers SET is_default = FALSE, last_update_by = " .
            $usrID . ", last_update_date = '" . $dateStr . "' WHERE (is_default = TRUE)";
    return execUpdtInsSQL($sqlStr);
}

function updateEmlSvrs($svrID, $smtp_nm, $mail_unme
, $mail_pwd, $port_no, $is_dflt, $actv_drctry, $ftpserver, $ftpunm, $ftppswd, $ftpport, $ftpdir, $enfc
, $pgdir, $bckpdir, $comPrt, $baudRate, $timeOut, $ftpHomeDir, $srvrStnSmtpIP) {
    global $usrID;
    global $smplTokenWord;
    //Update Server Info
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE sec.sec_email_servers " .
            "SET smtp_client='" . loc_db_escape_string($smtp_nm) .
            "', mail_user_name='" . loc_db_escape_string($mail_unme) .
            "', mail_password='" . loc_db_escape_string(encrypt1($mail_pwd, $smplTokenWord)) .
            "', smtp_port=" . $port_no .
            ", is_default='" . $is_dflt .
            "', actv_drctry_domain_name = '" . loc_db_escape_string($actv_drctry) .
            "', last_update_by=" . $usrID . ", " .
            "last_update_date='" . $dateStr .
            "', ftp_server_url='" . loc_db_escape_string($ftpserver) .
            "', ftp_user_name='" . loc_db_escape_string($ftpunm) .
            "', ftp_user_pswd='" . loc_db_escape_string(encrypt1($ftppswd, $smplTokenWord)) .
            "', ftp_port=" . $ftpport . ", ftp_app_sub_directory = '" . loc_db_escape_string($ftpdir) .
            "', enforce_ftp = '" . cnvrtBoolToBitStr($enfc) .
            "', pg_dump_dir = '" . loc_db_escape_string($pgdir) .
            "', backup_dir = '" . loc_db_escape_string($bckpdir) . "', com_port = '" . loc_db_escape_string($comPrt) .
            "', baud_rate = '" . loc_db_escape_string($baudRate) . "', timeout = '" . loc_db_escape_string($timeOut) .
            "', ftp_user_start_directory = '" . loc_db_escape_string($ftpHomeDir) .
            "', inhouse_smtp_ip = '" . loc_db_escape_string($srvrStnSmtpIP) . "' WHERE (server_id = " . $svrID . ")";
    return execUpdtInsSQL($sqlStr);
}

/**/

function chngUsrLockStatus($userID, $failedAttempts, $inptUsrNm = "") {
    //Set failed_login_atmpts in sec.sec_users to 0
    $insSQL = "UPDATE sec.sec_users SET failed_login_atmpts = $failedAttempts 
                            WHERE (user_id = " . $userID . ")";
    $affctd = execUpdtInsSQL($insSQL, "User Name:" . $inptUsrNm);
    if ($affctd > 0) {
        $dsply = "Successfully Updated the ff Records-";
        $dsply .= "<br/>$affctd User Account!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Updated!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function chngUsrSuspensionStatus($userID, $nwStatus, $inptUsrNm = "") {
    //Set failed_login_atmpts in sec.sec_users to 0
    $insSQL = "UPDATE sec.sec_users SET is_suspended = $nwStatus 
                            WHERE (user_id = " . $userID . ")";
    //echo $insSQL;
    $affctd = execUpdtInsSQL($insSQL, "User Name:" . $inptUsrNm);
    if ($affctd > 0) {
        $dsply = "Successfully Updated the ff Records-";
        $dsply .= "<br/>$affctd User Account!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Updated!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteUser($userID, $inptUsrNm = "") {
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
        $affctd += execUpdtInsSQL($insSQL, "User Name:" . $inptUsrNm);
        $insSQL1 = "DELETE FROM sec.sec_users WHERE user_id = " . $userID;
        $affctd1 += execUpdtInsSQL($insSQL1, "User Name:" . $inptUsrNm);
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

function get_Mdl_Roles($searchFor, $searchIn, $offset, $limit_size, $sortBy) {
    $wherecls = "";
    $ordrBy = "";
    if ($sortBy == "Role Name") {
        $ordrBy = "ORDER BY a.role_name";
    } else if ($sortBy == "Start Date") {
        $ordrBy = "ORDER BY a.valid_start_date,a.role_id";
    } else {
        $ordrBy = "ORDER BY a.valid_end_date,a.role_id";
    }
    if ($searchIn === "Role Name") {
        $wherecls = "a.role_name ilike '" . loc_db_escape_string($searchFor) . "'";
    } else {
        $wherecls = "a.role_name ilike '" . loc_db_escape_string($searchFor) . "'";
    }

    $sqlStr = "SELECT a.role_id mt, a.role_name,  
to_char(to_timestamp(a.valid_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') start_date
, to_char(to_timestamp(a.valid_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') end_date,
a.can_mini_admins_assign 
FROM sec.sec_roles a  " .
            "WHERE ($wherecls) $ordrBy LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_Mdl_RolesTtl($searchFor, $searchIn) {
    $wherecls = "";
    if ($searchIn === "Role Name") {
        $wherecls = "a.role_name ilike '" . loc_db_escape_string($searchFor) . "'";
    } else {
        $wherecls = "a.role_name ilike '" . loc_db_escape_string($searchFor) . "'";
    }
    $sqlStr = "SELECT count(1) 
FROM sec.sec_roles a " .
            "WHERE ($wherecls)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_Role_Prvldgs($searchFor, $searchIn, $offset, $limit_size, $pkID, $sortBy) {
    $ordrBy = "";
    if ($sortBy == "Role Name") {
        $ordrBy = "ORDER BY a.role_name";
    } else if ($sortBy == "Start Date") {
        $ordrBy = "ORDER BY a.valid_start_date,a.role_id";
    } else {
        $ordrBy = "ORDER BY a.valid_end_date,a.role_id";
    }
    $wherecls = "(b.prvldg_name ilike '" . loc_db_escape_string($searchFor) . "' or c.module_name ilike '" . loc_db_escape_string($searchFor) . "') and ";
    $sqlStr = "SELECT a.dflt_row_id mt, b.prvldg_id mt, b.prvldg_name priviledge_name, c.module_name source_module, 
      to_char(to_timestamp(a.valid_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') start_date
, to_char(to_timestamp(a.valid_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') end_date
, c.module_id mt, a.role_id mt  " .
            "FROM sec.sec_roles_n_prvldgs a, sec.sec_prvldgs b, sec.sec_modules c WHERE " .
            "($wherecls(a.prvldg_id = b.prvldg_id) AND (b.module_id = c.module_id) AND (a.role_id = " . $pkID . ")) "
            . $ordrBy . " LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_Role_PrvldgsTtl($searchFor, $searchIn, $pkID) {
    $wherecls = "(b.prvldg_name ilike '" . loc_db_escape_string($searchFor) . "' or c.module_name ilike '" . loc_db_escape_string($searchFor) . "') and ";
    $sqlStr = "SELECT count(1)  " .
            "FROM sec.sec_roles_n_prvldgs a, sec.sec_prvldgs b, sec.sec_modules c WHERE " .
            "($wherecls(a.prvldg_id = b.prvldg_id) AND (b.module_id = c.module_id) AND (a.role_id = " . $pkID . "))";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AllMdls($searchFor, $searchIn, $offset, $limit_size, $sortBy) {
    $wherecls = "a.module_name ilike '" . loc_db_escape_string($searchFor) . "' or b.prvldg_name ilike '" . loc_db_escape_string($searchFor) . "'";
    $ordrBy = "";
    if ($sortBy == "Module Name") {
        $ordrBy = "ORDER BY a.module_name";
    } else {
        $ordrBy = "ORDER BY a.module_id";
    }
    $sqlStr = "SELECT distinct a.module_id mt, a.module_name, a.module_desc description, 
        to_char(to_timestamp(a.date_added,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') date_added
, a.audit_trail_tbl_name audit_trail_table
FROM sec.sec_modules a LEFT OUTER JOIN sec.sec_prvldgs b 
ON a.module_id = b.module_id
 WHERE (($wherecls)) $ordrBy LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_AllMdlsTtl($searchFor, $searchIn) {
    $wherecls = "a.module_name ilike '" . loc_db_escape_string($searchFor) . "' or b.prvldg_name ilike '" . loc_db_escape_string($searchFor) . "'";

    $sqlStr = "SELECT count(distinct a.module_id) 
FROM sec.sec_modules a LEFT OUTER JOIN sec.sec_prvldgs b 
ON a.module_id = b.module_id
 WHERE (($wherecls))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AllMdls_Prvldgs($searchFor, $searchIn, $offset, $limit_size, $pkID, $sortBy) {
    $wherecls = "b.prvldg_name ilike '" . loc_db_escape_string($searchFor) . "' and ";
    $ordrBy = "ORDER BY b.prvldg_name";
    $sqlStr = "SELECT b.prvldg_name priviledge_name, b.prvldg_id mt, c.module_id mt
        FROM sec.sec_prvldgs b, sec.sec_modules c 
        WHERE ($wherecls(b.module_id = c.module_id) AND (c.module_id = " . $pkID . ")) $ordrBy LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_AllMdls_PrvldgsTtl($searchFor, $searchIn, $pkID) {
    $wherecls = "b.prvldg_name ilike '" . loc_db_escape_string($searchFor) . "' and ";
    $sqlStr = "SELECT count(1) 
        FROM sec.sec_prvldgs b, sec.sec_modules c 
        WHERE ($wherecls(b.module_id = c.module_id) AND (c.module_id = " . $pkID . "))";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AllSbgrps($searchFor, $searchIn, $offset, $limit_size, $pkID, $sortBy) {
    $wherecls = "(b.sub_group_name ilike '" . loc_db_escape_string($searchFor) .
            "' or b.main_table_name ilike '" . loc_db_escape_string($searchFor) .
            "' or b.row_pk_col_name ilike '" . loc_db_escape_string($searchFor) .
            "') and ";
    $ordrBy = "ORDER BY b.table_id";
    $sqlStr = "SELECT b.sub_group_name, b.main_table_name , b.row_pk_col_name, 
      to_char(to_timestamp(b.date_added,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') mt, 
b.table_id mt, c.module_id mt FROM sec.sec_module_sub_groups b, sec.sec_modules c 
WHERE ($wherecls(b.module_id = c.module_id) AND (c.module_id = " . $pkID . ")) $ordrBy LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_AllSbgrpsTtl($searchFor, $searchIn, $pkID) {
    $wherecls = "(b.sub_group_name ilike '" . loc_db_escape_string($searchFor) .
            "' or b.main_table_name ilike '" . loc_db_escape_string($searchFor) .
            "' or b.row_pk_col_name ilike '" . loc_db_escape_string($searchFor) .
            "') and ";

    $sqlStr = "SELECT count(1) FROM sec.sec_module_sub_groups b, sec.sec_modules c 
WHERE ($wherecls(b.module_id = c.module_id) AND (c.module_id = " . $pkID . "))";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AllSbgrpsLbls($searchFor, $searchIn, $offset, $limit_size, $pkID, $sortBy) {
    $wherecls = "(b.pssbl_value ilike '" . loc_db_escape_string($searchFor) .
            "') and ";
    $ordrBy = "ORDER BY b.pssbl_value";
    $sqlStr = "SELECT b.pssbl_value possible_value, 
        a.is_enabled, 
        b.pssbl_value_id mt, c.value_list_id mt, a.comb_info_id mt 
        FROM sec.sec_allwd_other_infos a, gst.gen_stp_lov_values b, gst.gen_stp_lov_names c 
        WHERE ($wherecls(b.value_list_id = c.value_list_id) AND (b.pssbl_value_id = a.other_info_id) 
        AND (a.table_id = " . $pkID . ")) $ordrBy LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_AllSbgrpsLblsTtl($searchFor, $searchIn, $pkID) {
    $wherecls = "(b.pssbl_value ilike '" . loc_db_escape_string($searchFor) .
            "') and ";
    $sqlStr = "SELECT count(1) 
        FROM sec.sec_allwd_other_infos a, gst.gen_stp_lov_values b, gst.gen_stp_lov_names c 
        WHERE ($wherecls(b.value_list_id = c.value_list_id) AND (b.pssbl_value_id = a.other_info_id) 
        AND (a.table_id = " . $pkID . "))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function doesTableHvThsExtrInfoLbl($tblID, $pssblVlID) {
    $sqlStr = "SELECT comb_info_id FROM sec.sec_allwd_other_infos WHERE ((table_id = " .
            $tblID . ") AND (other_info_id = " . $pssblVlID .
            "))";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function createAllwdExtraInfos($tblID, $pssblValID, $isEnbld) {
    global $usrID;
    $insSQL = "INSERT INTO sec.sec_allwd_other_infos(" .
            "table_id, other_info_id, is_enabled, created_by, " .
            "creation_date, last_update_by, last_update_date) " .
            "VALUES (" . $tblID . ", " . $pssblValID . ", '" .
            cnvrtBoolToBitStr($isEnbld) . "', " . $usrID .
            ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function enblDsblAllwdExtraInfos($combntnID, $isEnbld) {
    global $usrID;
    $insSQL = "UPDATE sec.sec_allwd_other_infos " .
            " SET is_enabled = '" . cnvrtBoolToBitStr($isEnbld) . "', last_update_by = " . $usrID .
            ", last_update_date = to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE ((comb_info_id = " . $combntnID . "))";
    return execUpdtInsSQL($insSQL);
}

function deleteAllwdExtraInfos($combntnID) {
    $insSQL = "DELETE FROM sec.sec_allwd_other_infos " .
            "WHERE ((comb_info_id = " . $combntnID . "))";
    return execUpdtInsSQL($insSQL);
}

function get_Plcy_Mdls($pkID) {
    $sqlStr = "SELECT a.module_id mt, a.module_name, 
        a.audit_trail_tbl_name audit_trail_table_name, 
        CASE WHEN b.enable_tracking='t' THEN 'TRUE' ELSE 'FALSE' END \"enable_tracking?\", 
        b.action_typs_to_track action_types_to_track, COALESCE(b.dflt_row_id,-1) mt
        FROM sec.sec_modules a LEFT OUTER JOIN 
        sec.sec_audit_trail_tbls_to_enbl b ON ((a.module_id = b.module_id) AND (b.policy_id = " .
            $pkID . ")) ORDER BY a.module_name";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_SecPlcysTblr($searchFor, $searchIn, $offset, $limit_size) {

    $wherecls = "policy_name ilike '" .
            loc_db_escape_string($searchFor) . "'";

    $sqlStr = "SELECT policy_id mt, policy_name " .
            "FROM sec.sec_security_policies " .
            "WHERE ($wherecls) ORDER BY policy_name 
 LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_SecPlcysTblrTtl($searchFor, $searchIn) {
    $wherecls = "policy_name ilike '" .
            loc_db_escape_string($searchFor) . "'";
    $sqlStr = "SELECT count(1) " .
            "FROM sec.sec_security_policies " .
            "WHERE ($wherecls)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_SecPlcysDet($pkID) {
    $sqlStr = "SELECT policy_id mt, policy_name, 
        max_failed_lgn_attmpts max_failed_login_attempts, 
        pswd_expiry_days password_expiry_days, " .
            "auto_unlocking_time_mins \"auto_unlocking_time(mins)\", 
        CASE WHEN pswd_require_caps='t' THEN 'TRUE' ELSE 'FALSE' END password_requires_caps, 
        CASE WHEN pswd_require_small='t' THEN 'TRUE' ELSE 'FALSE' END password_requires_small_letters,
        CASE WHEN pswd_require_dgt='t' THEN 'TRUE' ELSE 'FALSE' END password_requires_digits, 
        CASE WHEN pswd_require_wild='t' THEN 'TRUE' ELSE 'FALSE' END password_requires_wild_characters, 
        pswd_reqrmnt_combntns password_requirement_combinations, 
        CASE WHEN is_default='t' THEN 'TRUE' ELSE 'FALSE' END \"is_default?\", 
        old_pswd_cnt_to_disallow old_password_count_to_disallow, 
        pswd_min_length password_minimum_length, 
        pswd_max_length password_maximum_length, 
        max_no_recs_to_dsply max_no_of_records_to_display, 
        CASE WHEN allow_repeating_chars='t' THEN 'TRUE' ELSE 'FALSE' END allow_repeating_characters, 
        CASE WHEN allow_usrname_in_pswds='t' THEN 'TRUE' ELSE 'FALSE' END allow_username_in_passwords, 
        session_timeout \"session_timeout(Seconds)\"
        FROM sec.sec_security_policies 
        WHERE (policy_id = $pkID)";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_SrvrStngsTblr($searchFor, $searchIn, $offset, $limit_size) {

    $wherecls = "1=1";
    if ($searchIn === "SMTP CLIENT") {
        $wherecls = "smtp_client ilike '" .
                loc_db_escape_string($searchFor) . "'";
    } else if ($searchIn === "SENDER's USER NAME") {
        $wherecls = "mail_user_name ilike '" .
                loc_db_escape_string($searchFor) . "'";
    }

    $sqlStr = "SELECT server_id mt, smtp_client " .
            "FROM sec.sec_email_servers " .
            "WHERE ($wherecls) ORDER BY smtp_client 
 LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_SrvrStngsTblrTtl($searchFor, $searchIn) {

    $wherecls = "1=1";
    if ($searchIn === "SMTP CLIENT") {
        $wherecls = "smtp_client ilike '" .
                loc_db_escape_string($searchFor) . "'";
    } else if ($searchIn === "SENDER's USER NAME") {
        $wherecls = "mail_user_name ilike '" .
                loc_db_escape_string($searchFor) . "'";
    }

    $sqlStr = "SELECT count(1) " .
            "FROM sec.sec_email_servers " .
            "WHERE ($wherecls)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_SrvrStngsDet($pkID) {
    $sqlStr = "SELECT server_id, smtp_client, mail_user_name, mail_password, smtp_port, 
       CASE WHEN is_default='t' THEN 'Yes' ELSE 'No' END \"Is Default?\", 
       actv_drctry_domain_name, ftp_server_url, ftp_user_name, 
       ftp_user_pswd, ftp_port, ftp_app_sub_directory, 
       CASE WHEN enforce_ftp='1' THEN 'Yes' ELSE 'No' END \"Enforce FTP?\",
       pg_dump_dir, backup_dir, com_port, baud_rate, timeout, sms_param1, 
       sms_param2, sms_param3, sms_param4, sms_param5, sms_param6, sms_param7, 
       sms_param8, sms_param9, sms_param10, ftp_user_start_directory, inhouse_smtp_ip 
       FROM sec.sec_email_servers 
        WHERE (server_id = $pkID)";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_MdlsAdtTrls($searchWord, $searchIn, $subcurIdx, $sublmtSze, $pkID) {
    $numFrmat = "999999999999999999999999999999";
    $whereClsFrmts = array("trim(to_char(a.login_number,'" . $numFrmat . "'))",
        "b.user_name", "a.action_details", "a.action_type",
        "to_char(to_timestamp(a.action_time,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')", "c.host_mach_details");
    $ordrByClsFrmts = array("a.login_number", "b.user_name", "a.action_details", "a.action_type", "a.action_time", "c.host_mach_details");
    $sortOrder = array("DESC", "ASC", "ASC", "ASC", "DESC", "ASC");
    $frmt_to_use = 0;
    $optional_str = "";
    if ($searchIn == "Login Number") {
        $frmt_to_use = 0;
    } else if ($searchIn == "User Name") {
        $frmt_to_use = 1;
    } else if ($searchIn == "Action Details") {
        $frmt_to_use = 2;
    } else if ($searchIn == "Action Type") {
        $frmt_to_use = 3;
    } else if ($searchIn == "Date/Time") {
        $frmt_to_use = 4;
    } else if ($searchIn == "Machine Used") {
        $frmt_to_use = 5;
    }

    if ($searchWord == "") {
        $optional_str = " OR (" . $ordrByClsFrmts[$frmt_to_use] . " IS NULL)";
    }
    $ModuleAdtTbl = getGnrlRecNm('sec.sec_modules', 'module_id', 'audit_trail_tbl_name', $pkID);
    if ($ModuleAdtTbl == "") {
        $sqlStr = "SELECT '1' FROM sec.sec_users where 1=2";
    } else {
        $sqlStr = "SELECT b.user_name, a.action_type, a.action_details, 
      to_char(to_timestamp(a.action_time,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
, c.host_mach_details, a.user_id mt, a.login_number, a.dflt_row_id FROM " . $ModuleAdtTbl . " a " .
                "LEFT OUTER JOIN sec.sec_users b ON a.user_id = b.user_id " .
                "LEFT OUTER JOIN sec.sec_track_user_logins c ON a.login_number = c.login_number " .
                "WHERE ((" . $whereClsFrmts[$frmt_to_use] . " ilike '" . loc_db_escape_string($searchWord) .
                "')" . $optional_str . ") ORDER BY " . $ordrByClsFrmts[$frmt_to_use] .
                " " . $sortOrder[$frmt_to_use] . ", a.action_time DESC LIMIT " . $sublmtSze . " OFFSET " . abs($subcurIdx * $sublmtSze);
    }
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_MdlsAdtTrlsTtls($searchWord, $searchIn, $pkID) {
    $numFrmat = "999999999999999999999999999999";
    $whereClsFrmts = array("trim(to_char(a.login_number,'" . $numFrmat . "'))",
        "b.user_name", "a.action_details", "a.action_type",
        "to_char(to_timestamp(a.action_time,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')", "c.host_mach_details");
    $ordrByClsFrmts = array("a.login_number", "b.user_name", "a.action_details", "a.action_type", "a.action_time", "c.host_mach_details");

    $frmt_to_use = 0;
    $optional_str = "";
    if ($searchIn == "Login Number") {
        $frmt_to_use = 0;
    } else if ($searchIn == "User Name") {
        $frmt_to_use = 1;
    } else if ($searchIn == "Action Details") {
        $frmt_to_use = 2;
    } else if ($searchIn == "Action Type") {
        $frmt_to_use = 3;
    } else if ($searchIn == "Date/Time") {
        $frmt_to_use = 4;
    } else if ($searchIn == "Machine Used") {
        $frmt_to_use = 5;
    }

    if ($searchWord == "") {
        $optional_str = " OR (" . $ordrByClsFrmts[$frmt_to_use] . " IS NULL)";
    }
    $ModuleAdtTbl = getGnrlRecNm('sec.sec_modules', 'module_id', 'audit_trail_tbl_name', $pkID);
    if ($ModuleAdtTbl == "") {
        $sqlStr = "SELECT '1' FROM sec.sec_users where 1=2";
    } else {
        $sqlStr = "SELECT count(1) FROM " . $ModuleAdtTbl . " a " .
                "LEFT OUTER JOIN sec.sec_users b ON a.user_id = b.user_id " .
                "LEFT OUTER JOIN sec.sec_track_user_logins c ON a.login_number = c.login_number " .
                "WHERE ((" . $whereClsFrmts[$frmt_to_use] . " ilike '" . loc_db_escape_string($searchWord) .
                "')" . $optional_str . ")";
    }
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getEmlSvrID($svr_name) {
    //Example server name 'smtp.gmail.com'
    $sqlStr = "select server_id from sec.sec_email_servers where lower(smtp_client) = lower('" .
            loc_db_escape_string($svr_name) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getPlcyID($plcy_name) {
    //Example policy name 'Standard ISO Password Policy'
    $sqlStr = "select policy_id from sec.sec_security_policies where lower(policy_name) = lower('" .
            loc_db_escape_string($plcy_name) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function doesChkdRqrmntsMeetCmbntn($capsYesCheckBox, $smallYesCheckBox, $wildYesCheckBox, $digitsYesCheckBox, $combinatnsComboBox) {
    $cntr = 0;
    if ($capsYesCheckBox == "TRUE") {
        $cntr += 1;
    }
    if ($smallYesCheckBox == "TRUE") {
        $cntr += 1;
    }
    if ($wildYesCheckBox == "TRUE") {
        $cntr += 1;
    }
    if ($digitsYesCheckBox == "TRUE") {
        $cntr += 1;
    }
    if ($cntr == 0 && $combinatnsComboBox != "NONE") {
        return false;
    } else if ($cntr == 1 && $combinatnsComboBox != "ANY 1") {
        return false;
    } else if ($cntr == 2 && $combinatnsComboBox != "ANY 1" && $combinatnsComboBox != "ANY 2") {
        return false;
    } else if ($cntr == 3 && $combinatnsComboBox != "ANY 1" && $combinatnsComboBox != "ANY 2" && $combinatnsComboBox != "ANY 3") {
        return false;
    } else if ($cntr == 4 && $combinatnsComboBox == "NONE") {
        return false;
    }
    return true;
}

function hasPlcyEvrHdThsMdl($inp_plcy_id, $inp_mdl_id) {
    //Checks whether a given policy has a given module
    $sqlStr = "SELECT policy_id FROM sec.sec_audit_trail_tbls_to_enbl WHERE ((policy_id = " .
            $inp_plcy_id . ") AND (module_id = " . $inp_mdl_id . "))";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return true;
    }
    return false;
}

function hsRoleEverHdThisPrvldg($inp_role_id, $inp_prvldg_id) {
//Checks whether a given role 'system administrator' has a given priviledge
    $sqlStr = "SELECT role_id FROM sec.sec_roles_n_prvldgs WHERE ((prvldg_id = " .
            $inp_prvldg_id . ") AND (role_id = " . $inp_role_id .
            "))";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}
?>
                             























