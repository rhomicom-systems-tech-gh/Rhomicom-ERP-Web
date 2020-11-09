<?php

$qryStr = $_POST['q'];
$UID = isset($_POST['UID']) ? $_POST['UID'] : -1;
$error = "";
$usrID = isset($_SESSION['USRID']) ? $_SESSION['USRID'] : -1;
$nwUID = -1;
$lgInName = isset($_SESSION['UNAME']) ? $_SESSION['UNAME'] : '';
$uname = isset($_POST['username']) ? $_POST['username'] : '';
if ($lgInName == "GUEST") {
    $_SESSION['UNAME'] = "";
    $_SESSION['USRID'] = -1;
    $usrID = -1;
    $lgInName = '';  /**/
}
//echo $lgInName."XXX";
$newpswd = isset($_POST['newpassword']) ? $_POST['newpassword'] : '';
/* if (array_key_exists('lgn_num', get_defined_vars())) {
  if ($lgn_num > 0) { */
$errMsg = "";
if (($usrID > 0 && $newpswd != $smplPwd) || ($uname != '' && $newpswd != '' && $newpswd != $smplPwd && $qryStr === "do_change")) {
    set_time_limit(300);
    if ($qryStr === "do_change") {
        $uname = $_POST['username'];
        $nwUID = getUserID($uname);
        if ($nwUID <= 0 && getPersonID($uname) > 0) {
            checkNCreateUser($uname, $errMsg);
        }
        $oldpswd = $_POST['oldpassword'];
        $rptpswd = $_POST['rptpassword'];
        if ($rptpswd !== $newpswd) {
            $error = "New Passwords don't Match!";
        } else if ($lgInName != '' && strtolower($uname) == strtolower($lgInName) && isLoginInfoCorrct($uname, $oldpswd) == false) {
            $error = "Old password is Invalid!";
        } else if ($lgInName != '' && strtolower($uname) == strtolower($lgInName) && isPswdInRcntHstry($newpswd, $nwUID) == true) {
            $error = "The new password is in your last " . get_CurPlcy_DsllwdPswdCnt() .
                    " password history!\nPlease provide a different password!";
        } else if ($lgInName != '' && strtolower($uname) == strtolower($lgInName) && $oldpswd == $newpswd) {
            $error = "New Password is same as your Old Password!";
        } else if (doesPswdCmplxtyMeetPlcy($newpswd, $uname, $error) == true) {
            $isTmp = "FALSE";
            if ($lgInName == '' || strtolower($uname) == strtolower($lgInName)) {
                storeOldPassword($nwUID, $newpswd);
            } else {
                $isTmp = "TRUE";
            }
            if ($lgInName == '' || strtolower($uname) == strtolower($lgInName) || test_prmssns($dfltPrvldgs[16], "System Administration") || test_prmssns($dfltPrvldgs[17], "System Administration")) {
                changeUserPswd($nwUID, $newpswd, $isTmp);
                if ($lgInName != '' && strtolower($uname) != strtolower($lgInName)) {
                    echo"<div style=\"background-color:#e3e3e3;border: 1px solid #999;padding:10px;max-width:300px;\" class=\"rho-postcontent rho-postcontent-0 clearfix\"> ";
                    $prsnID = getUserPrsnID($uname);
                    $to = getPrsnEmail($prsnID);
                    //$to = "richarda.mensah@gmail.com";
                    $nameto = getPrsnFullNm($prsnID);
                    $subject = $app_name . " PASSWORD CHANGE";
                    $message = "Hello $nameto <br/><br/>Your Login Details have been changed as follows:"
                            . "<br/><br/>"
                            . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Username: $uname" .
                            "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Password: " . $newpswd .
                            "<br/>Please login immediately to change it!<br/>Thank you!";
                    $errMsg = "";
                    sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", "", "");
                    echo $errMsg;
                    echo "</div>";
                } else {
                    $in_org_id = getUserOrgID($uname);
                    $uID = getUserID($uname);
                    if ($lgInName == '') {
                        $machdet = kh_getUserIP();
                        recordSuccflLogin($uname, $machdet);
                        $msg = "";
                        chcAftrScsflLgnRqnt($uname, $newpswd, $msg);
                        $usrID = $_SESSION['USRID'];
                        $user_Name = $_SESSION['UNAME'];
                        $orgID = $_SESSION['ORG_ID'];
                        $lgn_num = $_SESSION['LGN_NUM'];
                        $ifrmeSrc = $_SESSION['CUR_IFRM_SRC'];
                        if ($lgn_num <= 0) {
                            $_SESSION['ORG_NAME'] = "$app_name";
                        } else {
                            $_SESSION['ORG_NAME'] = getOrgName($orgID);
                        }
                        $org_name = $_SESSION['ORG_NAME'];
                        $ssnRoles = $_SESSION['ROLE_SET_IDS'];
                        $Role_Set_IDs = explode(";", $ssnRoles);
                        $ModuleName = "";
                    } else {
                        if ($_SESSION['ROLE_SET_IDS'] == "" && $in_org_id > 0) {
                            selfAssignSSRoles($uID);
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
                    }
                }
            } else {
                $error .= "<br/>You don't have permission to perform this action!";
            }
        } else {
            $error .= "<br/>E.g of an Acceptable Password is " . $smplPwd;
        }
        if ($error !== "") {
            echo "<span style=\"color:red !important;\">" . $error . "</span>";
        }
        //echo $uname . $oldpswd . $newpswd . $rptpswd . "";
    } elseif ($qryStr === "auto_chng") {
        //header("Location: index.php?")
    } else {
        echo "<span style=\"color:red !important;\">" . $error . "</span>";
    }
} else if ($uname != '') {
    echo "<span style=\"color:red !important;\">Please fill all Required Fields!<br/>Do not use the example password $smplPwd</span>";
} else {
    //echo "Please Login First!"; 
    restricted();
}
?>
