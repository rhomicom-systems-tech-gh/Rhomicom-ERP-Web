<?php

function creatAdminAccnt()
{
    global $smplTokenWord;
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO sec.sec_users(usr_password, person_id, is_suspended, is_pswd_temp, 
	failed_login_atmpts, user_name, last_login_atmpt_time, last_pswd_chng_time, 
valid_start_date, valid_end_date, created_by, creation_date, last_update_by, last_update_date)  
VALUES (md5('" . loc_db_escape_string(encrypt("admin", $smplTokenWord)) . "'), -1, FALSE, FALSE, 0, 'admin', '" .
        $dateStr . "', '" . $dateStr . "', '" . $dateStr . "', '4000-12-31 00:00:00', -1, '" .
        $dateStr . "',-1, '" . $dateStr . "');";
    executeSQLNoParams($sqlStr);

    $uID = getUserID("admin");
    $pID = getPersonID("RHO0002012");

    if ($pID <= 0) {
        $createUnkwnPrsn1 = "INSERT INTO prs.prsn_names_nos(local_id_no, first_name, sur_name, other_names, title, 
            created_by, creation_date, last_update_by, last_update_date)
    VALUES ('RHO0002012', 'SYSTEM', 'SETUP', 'USER', 'Mr.', " . $uID . ", '" . $dateStr . "', " . $uID . ", '" . $dateStr . "')";
        executeSQLNoParams($createUnkwnPrsn1);
        $pID = getPersonID("RHO0002012");
    }

    //Update userID
    $updtUsr = "UPDATE sec.sec_users SET 
            person_id = " . $pID . ", created_by = " . $uID . ", last_update_by = " . $uID .
        " WHERE (user_id = " . $uID . ")";
    executeSQLNoParams($updtUsr);
    $pID1 = getPersonID("RHO0002017");

    if ($pID1 <= 0) {
        $createUnkwnPrsn1 = "INSERT INTO prs.prsn_names_nos(local_id_no, first_name, sur_name, other_names, title, 
            created_by, creation_date, last_update_by, last_update_date)
    VALUES ('RHO0002017', 'SYSTEM', 'AUTHORIZER', 'TRANSACTIONS', 'Mr.', " . $uID . ", '" . $dateStr . "', " . $uID . ", '" . $dateStr . "')";
        executeSQLNoParams($createUnkwnPrsn1);
        $pID1 = getPersonID("RHO0002017");
    }
    $updtSQL = "UPDATE prs.prsn_names_nos SET org_id=-1 WHERE local_id_no IN ('RHO0002012','RHO0002017');";
    executeSQLNoParams($updtSQL);
}

function createAdminRole()
{
    $uID = getUserID("admin");
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO sec.sec_roles(role_name, valid_start_date, valid_end_date, created_by,  
creation_date, last_update_by, last_update_date) VALUES ('System Administrator', '" .
        $dateStr . "', '4000-12-31 00:00:00', " . $uID . ", '" . $dateStr . "', " . $uID . ", '" . $dateStr . "')";
    executeSQLNoParams($sqlStr);
}

function asgnAdmnRoleToAdmn()
{
    //Assigns the System Administrator responsibility to the Admin Account
    $uID = getUserID("admin");
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO sec.sec_users_n_roles (user_id, role_id, valid_start_date, valid_end_date, created_by, 
creation_date, last_update_by, last_update_date) VALUES (" . getUserID("admin") . ", " .
        getRoleID("System Administrator") . ", '" . $dateStr .
        "', '4000-12-31 00:00:00', " . $uID . ", '" . $dateStr . "', " . $uID . ", '" . $dateStr . "')";
    executeSQLNoParams($sqlStr);
}

function doesUserHaveThisRole($username, $rolename)
{
    $sqlStr = "SELECT user_id FROM sec.sec_users_n_roles WHERE ((user_id = " .
        getUserID($username) . ") AND (role_id = " . getRoleID($rolename) .
        ") AND (now() between to_timestamp(valid_start_date,'YYYY-MM-DD HH24:MI:SS') AND " .
        "to_timestamp(valid_end_date,'YYYY-MM-DD HH24:MI:SS')))";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function checkB4LgnRequireMents()
{
    //global $smplTokenWord;
    $lvid = getLovID("Security Keys");
    $apKey = getEnbldPssblValDesc(
        "AppKey",
        $lvid
    );
    if ($apKey != "" && $lvid > 0) {
        //$smplTokenWord = $apKey;
    } else if ($lvid <= 0) {
        $apKey = "ROMeRRTRREMhbnsdGeneral KeyZzfor Rhomi|com Systems "
            . "Tech. !Ltd Enterpise/Organization @763542ERPorbjkSOFTWARE"
            . "asdbhi68103weuikTESTfjnsdfRSTLU../";
        //$smplTokenWord = $apKey;
        createLovNm("Security Keys", "Security Keys", false, "", "SYS", true);
        $lvid = getLovID("Security Keys");
        if ($lvid > 0) {
            createPssblValsForLov($lvid, "AppKey", $apKey, true, get_all_OrgIDs());
        }
    }

    if (getUserID("admin") <= 0) {
        creatAdminAccnt();
        if (getRoleID("System Administrator") <= 0) {
            createAdminRole();
        }
        if (doesUserHaveThisRole("admin", "System Administrator") === false) {
            asgnAdmnRoleToAdmn();
        }
    }
}

function chcAftrScsflLgnRqnt($usrNm, $pswd, &$msg)
{
    if (isAccntSuspended($usrNm) === true) {
        $msg = "This account has been suspended!<br/>Contact your System Administrator!";
        return "logout";
    }
    if (isUserAccntLckd($usrNm) === true) {
        unlockUsrAccnt($usrNm);
    } else {
        unlockUsrAccntConditnl($usrNm);
    }
    if (isPswdTmp($usrNm)) {
        $msg = "Your are using a Temporary Password!\nPlease change your password now!";
        $_SESSION['MUST_CHNG_PWD'] = "1";
        return "change password";
    }
    if (isPswdExpired($usrNm)) {
        $msg = "Your Password has Expired!\nPlease change your Password now!";
        $_SESSION['MUST_CHNG_PWD'] = "1";
        return "change password";
    }
    if (doesPswdCmplxtyMeetPlcy($pswd, $usrNm, $msg) === false) {
        $msg .= "Your password's complexity does not meet\nthe " .
            "current password policy requirements!\nPlease change " .
            "your password!";
        $_SESSION['MUST_CHNG_PWD'] = "1";
        return "change password";
    }

    $in_org_id = getUserOrgID($usrNm);
    $uID = getUserID($usrNm);
    if ($in_org_id <= 0) {
        $in_org_id = getMinOrgID();
        //Update person Org ID
        $updtUsr = "UPDATE prs.prsn_names_nos SET org_id = " . $in_org_id . " WHERE (person_id = " . getUserPrsnID($usrNm) . ")";
        executeSQLNoParams($updtUsr);
    }
    selfAssignSSRoles($uID);
    if ($_SESSION['ROLE_SET_IDS'] == "") {
        $result1 = get_Users_Roles($uID, "%", "Role Name", 0, 10000000);
        $selectedRoles = "";
        while ($row = loc_db_fetch_array($result1)) {
            $selectedRoles .= $row[0] . ";";
        }
        $in_org_nm = getOrgName($in_org_id);
        $_SESSION['ROLE_SET_IDS'] = rtrim($selectedRoles, ";");
        $_SESSION['ORG_NAME'] = $in_org_nm;
        $_SESSION['ORG_ID'] = $in_org_id;
        //echo "Load Inbox";
    }
    $_SESSION['MUST_CHNG_PWD'] = "0";
    if (is_User_SelfOnly($uID) === true) {
        return "select self";
    }
    return "select role";
}

function get_Users_Roles($usrID, $searchFor, $searchIn, $offset, $limit_size)
{
    //global ;//
    $wherecls = "";
    if ($searchIn === "Role Name") {
        $wherecls = " AND (b.role_name ilike '" . loc_db_escape_string($searchFor) . "')"; //mb_convert_encoding(htmlentities(), "UTF-8", "ASCII"))
    }
    $sqlStr = "SELECT a.role_id mt, b.role_name 
FROM sec.sec_users_n_roles a LEFT OUTER JOIN sec.sec_roles b ON (a.role_id = b.role_id) 
WHERE ((now() between to_timestamp(a.valid_start_date,'YYYY-MM-DD HH24:MI:SS') AND 
to_timestamp(a.valid_end_date,'YYYY-MM-DD HH24:MI:SS')) 
AND (now() between to_timestamp(b.valid_start_date,'YYYY-MM-DD HH24:MI:SS') AND " .
        "to_timestamp(b.valid_end_date,'YYYY-MM-DD HH24:MI:SS')) AND (a.user_id = " . $usrID .
        ")$wherecls) ORDER BY b.role_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function isUserAccntLckd($username)
{
    $prm = get_CurPlcy_Mx_Fld_lgns();
    $sqlStr = "SELECT user_name FROM sec.sec_users WHERE lower(user_name) = lower('"
        . loc_db_escape_string($username) . "') and failed_login_atmpts >=$prm";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {

        return true;
    } else {
        return false;
    }
}

function shdUnlckAccnt($username)
{
    $sqlStr = "SELECT user_name " .
        " FROM sec.sec_users WHERE lower(user_name) = lower('" . loc_db_escape_string($username) . "')
                                     and age(now(), to_timestamp(last_login_atmpt_time, 'YYYY-MM-DD HH24:MI:SS')) " .
        ">= interval '" . get_CurPlcy_Auto_Unlck_tme() . " minute'";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function isAccntSuspended($username)
{
    $sqlStr = "SELECT user_name FROM sec.sec_users 
                            WHERE lower(user_name) = lower('" . loc_db_escape_string($username) . "') and is_suspended='t'";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function isPswdTmp($username)
{
    $sqlStr = "SELECT user_name FROM sec.sec_users 
                            WHERE lower(user_name) = lower('" . loc_db_escape_string($username) . "') and is_pswd_temp='t'";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function isPswdExpired($username)
{
    $sqlStr = "SELECT user_name" .
        " FROM sec.sec_users WHERE lower(user_name) = lower('" . loc_db_escape_string($username) . "')
                                    and age(now(), to_timestamp(last_pswd_chng_time, 'YYYY-MM-DD HH24:MI:SS')) " .
        ">= interval '" . get_CurPlcy_Pwd_Exp_Days() . " days'";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function unlockUsrAccnt($username)
{
    //Set failed_login_atmpts in sec.sec_users to 0
    $sqlStr = "UPDATE sec.sec_users SET failed_login_atmpts = 0 
                            WHERE (lower(user_name) = lower('" . loc_db_escape_string($username) . "'))";
    $result = executeSQLNoParams($sqlStr);
}

function unlockUsrAccntConditnl($username)
{
    //Set failed_login_atmpts in sec.sec_users to 0
    $sqlStr = "UPDATE sec.sec_users SET failed_login_atmpts = 0 
                            WHERE ((lower(user_name) = lower('" . loc_db_escape_string($username) . "')) 
                                    AND (failed_login_atmpts <> 0))";
    $result = executeSQLNoParams($sqlStr);
}

function updtFailedLgnCnt($username)
{
    $sqlStr = "UPDATE sec.sec_users SET failed_login_atmpts = failed_login_atmpts + 1  
              WHERE (lower(user_name) = lower('" . loc_db_escape_string($username) . "'))";
    $result = executeSQLNoParams($sqlStr);
}

function updtLastLgnAttmpTme($username, $lgn_time)
{
    $sqlStr = "UPDATE sec.sec_users SET last_login_atmpt_time = '" . $lgn_time .
        "' WHERE (lower(user_name) = lower('" . loc_db_escape_string($username) . "'))";
    $result = executeSQLNoParams($sqlStr);
}

function get_login_number($username, $login_time, $mach_details)
{
    //Gets the last login attempt time
    $sqlStr = "SELECT login_number FROM sec.sec_track_user_logins WHERE ((user_id = " .
        getUserID($username) . ") AND (login_time = '" . $login_time . "') AND (host_mach_details = '" .
        loc_db_escape_string($mach_details) . "'))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function chckNUpdateStaleLgns($userid)
{
    $sqlStr = "UPDATE sec.sec_track_user_logins SET  logout_time=last_active_time 
            WHERE user_id = " . $userid . " and  age(now(), to_timestamp((CASE WHEN last_active_time ='' THEN " .
        "to_char(now(),'YYYY-MM-DD HH24:MI:SS') ELSE last_active_time END), 'YYYY-MM-DD HH24:MI:SS')) " .
        ">= interval '" . get_CurPlcy_SessnTime() . " second' and  logout_time='' and last_active_time!='' and was_lgn_atmpt_succsful='t'";
    execUpdtInsSQL($sqlStr);
    $sqlStr = "UPDATE sec.sec_track_user_logins SET  logout_time=to_char(now(), 'YYYY-MM-DD HH24:MI:SS'),
last_active_time=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')
            WHERE user_id = " . $userid . " and logout_time='' and last_active_time ='' and was_lgn_atmpt_succsful='t'";
    execUpdtInsSQL($sqlStr);
}

function adminForceLogoutLgns($userid)
{
    $sqlStr = "UPDATE sec.sec_track_user_logins SET  logout_time=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')
            WHERE user_id = " . $userid . " and logout_time='' and was_lgn_atmpt_succsful='t'";
    execUpdtInsSQL($sqlStr);
}

function recordSuccflLogin($username, $machdet)
{
    global $app_version;
    global $ftp_base_db_fldr;
    global $logNxtLine;
    global $lgn_num;
    $dateStr = getDB_Date_time();
    $mach_details = $machdet;
    $ssnID = session_id();
    $userid = getUserID($username);
    chckNUpdateStaleLgns($userid);
    $lgnExst = doesActiveLgnExist($userid);
    if ($lgnExst > 0) {
        recordFailedLogin($username, $machdet, "Attempted Multiple Simultaneous Logons");
        return FALSE;
    } else {
        $sqlStr = "INSERT INTO sec.sec_track_user_logins(user_id, 
                            login_time, logout_time, host_mach_details, was_lgn_atmpt_succsful, app_vrsn, web_session_id, last_active_time, lgn_remarks) " .
            "VALUES (" . $userid . ", '" . $dateStr . "', '', '" .
            loc_db_escape_string($mach_details) . "', TRUE, '" . $app_version .
            "', '" . loc_db_escape_string($ssnID) . "', '" . loc_db_escape_string($dateStr) . "', '')";
        executeSQLNoParams($sqlStr);
        updtLastLgnAttmpTme($username, $dateStr);
        $prsnid = getUserPrsnID($username);
        $lgn_num = get_login_number($username, $dateStr, $mach_details);
        $fullnm = getPrsnFullNm($prsnid) . " (" . getPersonLocID($prsnid) . ")";
        $_SESSION['UNAME'] = $username;
        $_SESSION['USRID'] = getUserID($username);
        $_SESSION['LGN_NUM'] = $lgn_num;
        $_SESSION['ORG_ID'] = getUserOrgID($username);
        $_SESSION['PRSN_ID'] = $prsnid;
        $_SESSION['PRSN_FNAME'] = $fullnm;
        recopyPrflPic($username, $lgn_num);
        $txt = "Username:" . $username . "|Login Number:" . $lgn_num . "|Person:" . $fullnm;
        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho", $txt . $logNxtLine, FILE_APPEND | LOCK_EX);
        createWelcomeMsg($username);
        return TRUE;
    }
}

function doesActiveLgnExist($userid)
{
    $isMltplAllwdID = getEnbldPssblValID("Allow Multiple Same User Logons", getLovID("All Other General Setups"));
    $isMltplAllwd = getPssblValDesc($isMltplAllwdID);
    if (strtoupper($isMltplAllwd) != "YES" && $isMltplAllwdID > 0) {
        $sqlStr = "SELECT login_number FROM sec.sec_track_user_logins WHERE ((user_id = " .
            $userid . " and was_lgn_atmpt_succsful='t') AND (now() between to_timestamp(login_time,'YYYY-MM-DD HH24:MI:SS') "
            . "and to_timestamp((CASE WHEN logout_time='' THEN '4000-12-31 23:59:59' ELSE logout_time END), 'YYYY-MM-DD HH24:MI:SS')))";
        //echo $sqlStr;
        $result = executeSQLNoParams($sqlStr);
        while ($row = loc_db_fetch_array($result)) {
            return (float) $row[0];
        }
        return -1;
    } else {
        return -1;
    }
}

function recopyPrflPic($username, $lgn_num)
{
    global $ftp_base_db_fldr;
    global $logNxtLine;
    global $smplTokenWord1;
    global $myImgFileName;
    global $fullTmpDest;
    global $fldrPrfx;
    global $tmpDest;
    $prsnid = getUserPrsnID($username);
    $strlFileNm = getPersonImg($prsnid);
    $extnsn = "png";
    if (trim($strlFileNm) != "") {
        $temp = explode(".", $strlFileNm);
        $extnsn = end($temp);
    }
    $nwFileName = encrypt1($prsnid . session_id() . $lgn_num, $smplTokenWord1) . '.' . $extnsn;
    $fullTmpDest = $fldrPrfx . $tmpDest . $nwFileName;
    $ftp_src = $ftp_base_db_fldr . "/Person/$prsnid" . '.' . $extnsn;
    $txt = ""; // "Source:" . $ftp_src . "|Dest:" . $fullTmpDest."<br/>";
    if (file_exists($ftp_src) && !file_exists($fullTmpDest)) {
        copy("$ftp_src", "$fullTmpDest");
        //$txt .= "<br/>HAS";
    } else if (!file_exists($fullTmpDest)) {
        $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
        copy("$ftp_src", "$fullTmpDest");
        /* if (!copy("$ftp_src", "$fullTmpDest")) {
          $errors = error_get_last();
          echo "COPY ERROR: " . $errors['type'];
          echo "<br />\n" . $errors['message'];
          echo $fullTmpDest . "<br/>ERROR COPYING";
          echo $ftp_src . "<br/>";
          } */
        //$txt .= "<br/>HAS NOT";
    }
    $myImgFileName = $nwFileName;
    $_SESSION['FILES_NAME_PRFX'] = $myImgFileName;
    $txt .= "Username:" . $username . "|Login Number:" . $lgn_num . "|myImgFileName:" . $myImgFileName . "|strlFileNm11:" . trim($strlFileNm) . "|extnsn:" . $extnsn;
    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho", $txt . $logNxtLine, FILE_APPEND | LOCK_EX);
}

function recopyOrgLogo($orgid, $lgn_num)
{
    global $ftp_base_db_fldr;
    global $logNxtLine;
    global $smplTokenWord1;
    global $orgLogoFileName;
    global $fullTmpDest;
    global $fldrPrfx;
    global $tmpDest;
    global $app_image1;
    set_error_handler("rhoErrorHandler3");
    try {
        $strlFileNm = getOrgLogo($orgid);
        $extnsn = "png";
        if (trim($strlFileNm) != "") {
            $temp = explode(".", $strlFileNm);
            $extnsn = end($temp);
        } else {
            $strlFileNm = $orgid . ".png";
        }
        $nwFileName = encrypt1($strlFileNm . session_id(), $smplTokenWord1) . '.' . $extnsn;
        $fullTmpDest = $fldrPrfx . $tmpDest . $nwFileName;
        $fullRptDest = $fldrPrfx . "dwnlds/amcharts_2100/images/" . $strlFileNm;
        $fullRptDestPnG = $fldrPrfx . "dwnlds/amcharts_2100/images/" . $orgid . ".png";
        $ftp_src = $ftp_base_db_fldr . "/Org/" . $strlFileNm;
        $txt = "";
        $orgLogoFileName =  $app_image1;
        if (file_exists($ftp_src) && !file_exists($fullTmpDest)) {
            copy("$ftp_src", "$fullTmpDest");
            $orgLogoFileName = $tmpDest . $nwFileName;
        }
        logSessionErrs($fullRptDest);
        if (file_exists($ftp_src) && !file_exists($fullRptDest)) {
            copy("$ftp_src", "$fullRptDest");
        }
        if (file_exists($ftp_src) && !file_exists($fullRptDestPnG)) {
            copy("$ftp_src", "$fullRptDestPnG");
        }
        $_SESSION['ORG_LOGO_FILE_NAME'] = $orgLogoFileName;
        $txt .= "orgLogoFileName:" . $orgLogoFileName . "|strlFileNm11:" . trim($strlFileNm) . "|extnsn:" . $extnsn;
        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho", $txt . $logNxtLine, FILE_APPEND | LOCK_EX);
    } catch (Exception $ex) {
        $errMsg = "An error occurred. <br/>" . $ex->getMessage() . " Invalid Person Set SQL Query!";
        return FALSE;
    }
}

function recordFailedLogin($username, $machdet, $msg = "")
{
    global $app_version;
    $dateStr = getDB_Date_time();
    $mach_details = $machdet;
    $ssnID = session_id();
    $sqlStr = "INSERT INTO sec.sec_track_user_logins(user_id, login_time, 
    logout_time, host_mach_details, was_lgn_atmpt_succsful, app_vrsn, web_session_id, last_active_time, lgn_remarks) " .
        "VALUES (" . getUserID($username) . ", '" . $dateStr . "', '', '" .
        loc_db_escape_string($mach_details) . "', FALSE, '" . $app_version .
        "', '" . loc_db_escape_string($ssnID) . "', '" . loc_db_escape_string($dateStr) .
        "', '" . loc_db_escape_string($msg) . "')";
    executeSQLNoParams($sqlStr);
    if (strpos($msg, "Simultaneous") === FALSE) {
        updtFailedLgnCnt($username);
    }
    updtLastLgnAttmpTme($username, $dateStr);
}

function checkLogin($unm, $paswd, $machdet, &$msg)
{
    $inUsrID = getUserID($unm);
    $inUnm = getUserName($inUsrID);
    if ($inUsrID <= 0) {
        return "Invalid Username or Password!";
    }
    if (isAccntSuspended($inUnm) === true) {
        return "This account has been suspended!\nContact your System Administrator!";
    }
    if (
        isUserAccntLckd($inUnm) === true &&
        shdUnlckAccnt($inUnm) === false
    ) {
        return "Your account has been Locked!\nContact your System Administrator!";
    }
    if (isLoginInfoCorrct($inUnm, $paswd)) {
        //Update successful logins table
        $lgnScfl = recordSuccflLogin($inUnm, $machdet);
        if ($lgnScfl === TRUE) {
            return chcAftrScsflLgnRqnt($inUnm, $paswd, $msg);
        } else {
            return "Simultaneous Logons not Permitted!";
        }
    } else {
        //Update failed logins table
        recordFailedLogin($inUnm, $machdet, "Invalid Username or Password!");
        return "Invalid Username or Password!";
    }
}

function kh_getUserIP()
{
    //$client = @$_SERVER['HTTP_CLIENT_IP'];
    //$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = @$_SERVER['REMOTE_ADDR'];
    $remtHst = ""; // @$_SERVER['REMOTE_HOST'];
    /* if (filter_var($client, FILTER_VALIDATE_IP)) {
      $ip = $client;
      } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
      $ip = $forward;
      } else {
      $ip = $remote;
      } */
    $ip = $remote;
    return "IP:" . $ip; // . '/Name:' . $remtHst;
}
