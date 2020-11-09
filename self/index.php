<?php
if (!isset($_SESSION['LGN_NUM'])) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_startup_errors', true);
ini_set("display_errors", TRUE);
ini_set("html_errors", TRUE);

require '../app_code/cmncde/connect_pg.php';
$isMntnceMode = ($putInMntnceMode == "YES") ? TRUE : FALSE;
if ($isMntnceMode) {
?>
    <!DOCTYPE html>
    <html class="gr__mackeycreativelab_com">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>Performing Maintenance</title>
        <meta name="description" content="Performing maintenance...">
        <link rel="shortcut icon" href="<?php echo $app_favicon; ?>" type="image/x-icon" />
        <style type="text/css">
            /* Reset */
            html,
            body,
            div,
            span,
            applet,
            object,
            iframe,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            p,
            blockquote,
            pre,
            a,
            abbr,
            acronym,
            address,
            big,
            cite,
            code,
            del,
            dfn,
            em,
            img,
            ins,
            kbd,
            q,
            s,
            samp,
            small,
            strike,
            strong,
            sub,
            sup,
            tt,
            var,
            b,
            u,
            i,
            center,
            dl,
            dt,
            dd,
            ol,
            ul,
            li,
            fieldset,
            form,
            label,
            legend,
            table,
            caption,
            tbody,
            tfoot,
            thead,
            tr,
            th,
            td,
            article,
            aside,
            canvas,
            details,
            embed,
            figure,
            figcaption,
            footer,
            header,
            hgroup,
            menu,
            nav,
            output,
            ruby,
            section,
            summary,
            time,
            mark,
            audio,
            video {
                margin: 0;
                padding: 0;
                border: 0;
                font-size: 100%;
                font: inherit;
                vertical-align: baseline;
            }

            /* HTML5 display-role reset for older browsers */
            article,
            aside,
            details,
            figcaption,
            figure,
            footer,
            header,
            hgroup,
            menu,
            nav,
            section {
                display: block;
            }

            body {
                line-height: 1;
            }

            ol,
            ul {
                list-style: none;
            }

            blockquote,
            q {
                quotes: none;
            }

            blockquote:before,
            blockquote:after,
            q:before,
            q:after {
                content: '';
                content: none;
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
            }

            html {
                font-size: 16px;
            }

            body {
                text-align: center;
                padding: 150px;
            }

            h1 {
                font-size: 40px;
                font-weight: bold;
                margin-bottom: 1rem;
            }

            p {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            body {
                font: 20px Helvetica, sans-serif;
                color: #333;
            }

            #article {
                display: block;
                text-align: left;
                width: 650px;
                margin: 0 auto;
            }

            a {
                color: red;
                text-decoration: none;
            }

            a:hover {
                color: blue;
                text-decoration: none;
            }
        </style>
    </head>

    <body data-gr-c-s-loaded="true">
        <div id="article">
            <h1 style="color:<?php echo $bckcolorOnly1; ?>">Our site is getting a little tune-up and some love.</h1>
            <div>
                <p>We apologize for the inconvenience, but we're performing some maintenance. You can still contact us at <a href="mailto:"><?php echo $admin_email; ?></a>. We'll be back up soon!</p>
                <p style="color:<?php echo $bckcolorOnly2; ?>">— <?php echo $app_cstmr; ?></p>
            </div>
        </div>
    </body>

    </html>
<?php
} else {
    // set to the user defined error handler
    $old_error_handler = set_error_handler("rhoErrorHandler");
    $formArray = array();
    $gDcrpt = isset($_GET['g']) ? cleanInputData($_GET['g']) : '';
    $gVrfy = isset($_GET['vrfy']) ? cleanInputData($_GET['vrfy']) : '';
    $group = isset($_POST['grp']) ? (int) cleanInputData($_POST['grp']) : 0;
    $type = isset($_POST['typ']) ? (int) cleanInputData($_POST['typ']) : 0;
    $qryStr = isset($_POST['q']) ? cleanInputData($_POST['q']) : '';
    $pgNo = isset($_POST['pg']) ? (int) cleanInputData($_POST['pg']) : 0;
    $shdGoConfig = isset($_POST['shdGoConfig']) ? (int) ($_POST['shdGoConfig']) : 0;

    if ($shdGoConfig === 1005) {
        $actyp = isset($_POST['actyp']) ? (int) ($_POST['actyp']) : 0;
        require 'srvr_self/base_files/404.php';
        exit();
    }
    $cnfgFileExists = file_exists($superAdminConfigFilePath);
    if ($cnfgFileExists === FALSE) {
        require 'srvr_self/base_files/404.php';
        exit();
    }
    $conn = getConn();
    if ($conn === FALSE) {
        require 'srvr_self/base_files/404.php';
        exit();
    }
    if (!isset($_SESSION['SESSION_TIMEOUT'])) {
        $_SESSION['SESSION_TIMEOUT'] = get_CurPlcy_SessnTmOut();
    }
    if (isset($_SESSION['LAST_ACTIVITY'])) {
        $sessnTimeOut = floatval($_SESSION['SESSION_TIMEOUT']);
        $lgn_num = (float) $_SESSION['LGN_NUM'];
        $lstActvty = $_SESSION['LAST_ACTIVITY'];
        $dt = new DateTime("@$lstActvty");  // convert UNIX timestamp to PHP DateTime
        $lstActvtyStr = $dt->format('Y-m-d H:i:s');
        if ((time() - $lstActvty) > $sessnTimeOut && $lgn_num > 0) {
            //echo $sessnTimeOut ."::". $lgn_num ."::".(time() - $lstActvty);
            execSsnUpdtInsSQL("UPDATE sec.sec_track_user_logins SET last_active_time='"
                . loc_db_escape_string($lstActvtyStr) .
                "', logout_time='"
                . loc_db_escape_string($lstActvtyStr) .
                "' WHERE login_number=" . $lgn_num);
            destroySession();
            //var_dump($_POST);
            if (count($_POST) <= 0) {
                $lgn_num = -1;
                $notAllowed = "";
            } else {
                sessionInvalid();
                exit();
            }
        } else {
            if ((time() - $lstActvty) >= 50) {
                execSsnUpdtInsSQL("UPDATE sec.sec_track_user_logins SET last_active_time='"
                    . loc_db_escape_string($lstActvtyStr) .
                    "', logout_time='' WHERE login_number=" . $lgn_num);
            }
            $_SESSION['LAST_ACTIVITY'] = time();
            $lstActvty = $_SESSION['LAST_ACTIVITY'];
        }
    } else {
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    require '../app_code/cmncde/globals.php';
    require '../app_code/cmncde/admin_funcs.php';
    require 'srvr_self/base_files/selfLgnCntrlr.php';

    $noticesElmntNm = "allmodules";
    $screenwdth = isset($_POST['screenwdth']) ? cleanInputData($_POST['screenwdth']) : $_SESSION['SCREEN_WIDTH'];
    $_SESSION['SCREEN_WIDTH'] = $screenwdth;
    $notAllowed = "";
    $error = "";
    $usrID = (float) $_SESSION['USRID'];
    $usrName = $_SESSION['UNAME'];
    $prsnid = (float) $_SESSION['PRSN_ID'];
    //$prsnName = $_SESSION['PRSN_FNAME'];
    $lgn_num = (float) $_SESSION['LGN_NUM'];
    $orgID = (int) $_SESSION['ORG_ID'];
    $orgName = $_SESSION['ORG_NAME'];
    $myImgFileName = $_SESSION['FILES_NAME_PRFX'];
    $blnFlExst = file_exists($fldrPrfx . $tmpDest . $myImgFileName);
    if ($lgn_num > 0 && $usrID > 0 && !$blnFlExst) {
        recopyPrflPic($usrName, $lgn_num);
        $myImgFileName = $_SESSION['FILES_NAME_PRFX'];
    } else if ($lgn_num <= 0 || $usrID <= 0) {
        $_SESSION['UNAME'] = "GUEST";
        $usrName = $_SESSION['UNAME'];
        recopyPrflPic("GUEST", $lgn_num);
        $myImgFileName = $_SESSION['FILES_NAME_PRFX'];
    }

    $app_image1 = "cmn_images/" . $app_image1;
    $orgLogoFileName = $_SESSION['ORG_LOGO_FILE_NAME'];
    $blnFlExst2 = trim($orgLogoFileName) === "" ? false : file_exists($fldrPrfx . $orgLogoFileName);
    if ($lgn_num > 0 && $usrID > 0 && !$blnFlExst2) {
        recopyOrgLogo($orgID, $lgn_num);
        $orgLogoFileName = $_SESSION['ORG_LOGO_FILE_NAME'];
    } else if ($lgn_num <= 0 || $usrID <= 0) {
        $orgLogoFileName =  $app_image1;
        $_SESSION['ORG_LOGO_FILE_NAME'] = $orgLogoFileName;
    }
    if ($orgID > 0) {
        $app_name = $orgName;
        $app_image1 = $orgLogoFileName;
    }

    $isWflMailAllwdID = getEnbldPssblValID("Allow Workflow Emails", getLovID("All Other General Setups"));
    $isWflMailAllwd = getPssblValDesc($isWflMailAllwdID);
    if ($gDcrpt != '') {
        $gDcrpt1 = decrypt($gDcrpt, $smplTokenWord1);
        $arrayParams = explode("|", $gDcrpt1);
        if (count($arrayParams) == 8) {
            $numChars = intval($arrayParams[1]);
            $numChars1 = intval($arrayParams[6]);

            $numTxt = ($arrayParams[0]);
            $numTxt1 = ($arrayParams[7]);

            $rqstDate = $arrayParams[2];
            $expDte = $arrayParams[4];
            $qryStr = $arrayParams[3];
            $gUNM = $arrayParams[5];
            if (getDB_Date_time() >= $expDte || strlen($numTxt) != $numChars || strlen($numTxt1) != $numChars1 || $qryStr != 'changeselfpassword') {
                sessionInvalid();
                exit();
            }
            $group = 210;
            $type = 707;
        } else {
            sessionInvalid();
            exit();
        }
    } else if ($gVrfy != '') {
        $gVrfy1 = decrypt($gVrfy, $smplTokenWord1);
        $arrayParams = explode("|", $gVrfy1);
        if (count($arrayParams) == 8) {
            $numChars = intval($arrayParams[1]);
            $numChars1 = intval($arrayParams[6]);

            $numTxt = ($arrayParams[0]);
            $numTxt1 = ($arrayParams[7]);

            $rqstDate = $arrayParams[2];
            $expDte = $arrayParams[4];
            $qryStr = $arrayParams[3];
            $gUNM = $arrayParams[5];
            if (getDB_Date_time() >= $expDte || strlen($numTxt) != $numChars || strlen($numTxt1) != $numChars1 || $qryStr != 'verifyyouremail') {
                sessionInvalid();
                exit();
            }
            $group = 210;
            $type = 708;
        } else {
            sessionInvalid();
            exit();
        }
    }
    if ($_SESSION['MUST_CHNG_PWD'] == "1" && $qryStr != "logout" && !($group == 200 && $type == 3)) {
        $group = 210;
        $type = 709;
        if (count($_POST) > 0) {
            $type = 5;
        }
    }
    if (($group == 0 && $type == 0 && $notAllowed == "") || ($group == 210 && $type == 707) || ($group == 210 && $type == 708) || ($group == 210 && $type == 709)) {
        if (file_exists($fldrPrfx . 'srvr_self/base_files/' . $homepgfile)) {
            require 'srvr_self/base_files/' . $homepgfile;
        } else {
            require 'srvr_self/base_files/home.php';
        }
    } else if ($group > 0 && $type > 0) {
        if ($group == 1) {
            if ($type == 11) {
                require 'srvr_self/base_files/rho_qry_infos.php';
            } else {
                header('location: index.php');
            }
        } else if ($group == 2) {
            if ($type == 1) {
                //require '../app_code/cmncde/lovDialogs.php';
                require 'srvr_self/base_files/lovDialogs.php';
            } else if ($type == 2) {
                require '../app_code/cmncde/lovDialogs_mcf.php';
            }
        } else if ($group == 40) {
            if ($type == 1) {
                require 'srvr_self/articles.php';
            }
        } else if ($group == 42) {
            if ($type == 1) {
                require 'srvr_self/all_modules_menu.php';
            }
        } else if ($group == 9) {
            if ($type == 1) {
                require 'srvr_self/self_rpts.php';
            } else if ($type == 2) {
                require 'srvr_self/schld_runs.php';
            }
        } else if ($group == 45) {
            if ($type == 1) {
                require 'srvr_self/myinbox.php';
            }
        } else if ($group == 50) {
            if ($type == 1) {
                require 'srvr_self/profile.php';
            } else if ($type == 2) {
                require 'srvr_self/leave_rqsts.php';
            } else if ($type == 3) {
                require 'srvr_self/grade_rqsts.php';
            }
        } else if ($group == 60) {
            if ($type == 1) {
                require 'srvr_self/help_desk.php';
            }
        } else if ($group == 70) {
            if ($type == 1) {
                require 'srvr_self/dashboard.php';
            }
        } else if ($group == 80) {
            if ($type == 1) {
                require 'srvr_self/bills_payments.php';
            }
        } else if ($group == 90) {
            if ($type == 1) {
                require 'srvr_self/appointments.php';
            }
        } else if ($group == 110) {
            if ($type == 1) {
                require 'srvr_self/performance.php';
            }
        } else if ($group == 120) {
            if ($type == 1) {
                require 'srvr_self/hotel_bookings.php';
            }
        } else if ($group == 130) {
            if ($type == 1) {
                require 'srvr_self/elearning.php';
            }
        } else if ($group == 140) {
            if ($type == 1) {
                require 'srvr_self/evoting.php';
            }
        } else if ($group == 150) {
            if ($type == 1) {
                require 'srvr_self/events.php';
            }
        } else if ($group == 200) {
            if ($type == 1) {
                $login_result = "";
                $uname = "";
                $pswd = "";
                if (isset($_POST['err'])) {
                    $error = cleanInputData($_POST['err']);
                }
                if (isset($_POST['usrnm']) && isset($_POST['pwd'])) {
                    $uname = cleanInputData($_POST['usrnm']);
                    $pswd = cleanInputData($_POST['pwd']);
                }
                if ($uname != "" && $pswd != "") {
                    $brwsr = getBrowser();
                    $macDet = "";
                    if (isset($_POST['machdet'])) {
                        $macDet = cleanInputData($_POST['machdet']);
                    }
                    $machdetail = $macDet . "/" . kh_getUserIP() . "/Browser:" . $brwsr['name'] . " v" . $brwsr['version'] . "/OS:" . $brwsr['platform'] . "/Session ID:" . session_id();
                    $msg = "";
                    if (getUserID("admin") <= 0) {
                        loadSysAdminMdl();
                    }
                    checkB4LgnRequireMents();
                    $login_result = checkLogin($uname, $pswd, $machdetail, $msg);
                    if ($login_result === "select role") {
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
                        echo $login_result;
                        exit();
                    } else if ($login_result === "change password" || $_SESSION['MUST_CHNG_PWD'] == "1") {
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
                        echo $login_result;
                        exit();
                    } else if ($login_result === "logout") {
                        logoutActions();
                        $error = "Successfully Logged Out";
                        echo $error;
                        exit();
                    } else {
                        $error = $login_result;
                        echo $error;
                        exit();
                    }
                }
            } else
            if ($type == 2 && $qryStr == "logout") {
                logoutActions();
                $error = "Successfully Logged Out";
                require 'srvr_self/base_files/' . $homepgfile;
            } else if ($type == 3) {
                $qryStr = $_POST['q'];
                $UID = isset($_POST['UID']) ? $_POST['UID'] : -1;
                $error = "";
                $usrID = isset($_SESSION['USRID']) ? $_SESSION['USRID'] : -1;
                $nwUID = -1;
                $lgInName = isset($_SESSION['UNAME']) ? $_SESSION['UNAME'] : '';
                $uname = isset($_POST['username']) ? $_POST['username'] : '';
                $newpswd = isset($_POST['newpassword']) ? $_POST['newpassword'] : '';
                if ($lgInName === "GUEST") {
                    $lgInName = "";
                }
                $errMsg = "";
                if (($usrID > 0 && $newpswd != $smplPwd) || ($uname != '' && $newpswd != '' && $newpswd != $smplPwd && $qryStr === "do_change")) {
                    set_time_limit(300);
                    if ($qryStr === "do_change") {
                        $uname = $_POST['username'];
                        $nwUID = getUserID($uname);
                        /* if ($nwUID <= 0 && getPersonID($uname) > 0) {
                          checkNCreateUser($uname, $errMsg);
                          } */
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
                            if ($lgInName == '' || strtolower($uname) == strtolower($lgInName)) {
                                $myCrptedLnk = isset($_POST['oldpassword']) ? cleanInputData($_POST['oldpassword']) : '';
                                if ($myCrptedLnk != '' && $lgInName == '') {
                                    $gDcrpt1 = decrypt($myCrptedLnk, $smplTokenWord1);
                                    $arrayParams = explode("|", $gDcrpt1);
                                    if (count($arrayParams) == 8) {
                                        $numChars = intval($arrayParams[1]);
                                        $numChars1 = intval($arrayParams[6]);

                                        $numTxt = ($arrayParams[0]);
                                        $numTxt1 = ($arrayParams[7]);

                                        $rqstDate = $arrayParams[2];
                                        $expDte = $arrayParams[4];
                                        $qryStr = $arrayParams[3];
                                        $gUNM = $arrayParams[5];
                                        if (getDB_Date_time() >= $expDte || strlen($numTxt) != $numChars || strlen($numTxt1) != $numChars1 || $qryStr != 'changeselfpassword' || $gUNM != $uname) {
                                            $error = "Invalid Session!";
                                        }
                                    } else {
                                        $error = "Invalid Session!";
                                    }
                                }
                                if ($error === "") {
                                    changeUserPswd($nwUID, $newpswd, $isTmp);
                                }
                                if ($lgInName != '' && strtolower($uname) != strtolower($lgInName)) {
                                    echo "<div style=\"background-color:#e3e3e3;border: 1px solid #999;padding:10px;max-width:300px;\" class=\"rho-postcontent rho-postcontent-0 clearfix\"> ";
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
            } else if ($type == 4) {
                $qryStr = $_POST['q'];
                $UID = isset($_POST['UID']) ? $_POST['UID'] : -1;
                $error = "";
                $usrID = isset($_SESSION['USRID']) ? $_SESSION['USRID'] : -1;
                $nwUID = -1;
                $lgInName = "";
                $pFirstName = isset($_POST['pFirstName']) ? $_POST['pFirstName'] : '';
                $pSurName = isset($_POST['pSurName']) ? $_POST['pSurName'] : '';
                $pEmail = isset($_POST['pEmail']) ? $_POST['pEmail'] : '';
                $uname = $pEmail;
                $pPhone = isset($_POST['pPhone']) ? $_POST['pPhone'] : '';
                $pPassword = isset($_POST['pPassword']) ? $_POST['pPassword'] : '';
                $pCnfrmPassword = isset($_POST['pCnfrmPassword']) ? $_POST['pCnfrmPassword'] : '';
                $pAgreeTerms = isset($_POST['pAgreeTerms']) ? $_POST['pAgreeTerms'] : '';
                $errMsg = "";
                $oldpswd = "";
                $newpswd = $pPassword;
                $rptpswd = $pCnfrmPassword;
                if (($usrID > 0 && $pPassword != $smplPwd) || ($uname != '' && $newpswd != '' && $pPassword != $smplPwd && $qryStr === "registernew")) {
                    set_time_limit(300);
                    if ($qryStr === "registernew") {
                        if ($rptpswd !== $newpswd) {
                            $error = "New Passwords don't Match!";
                        } else if (doesPswdCmplxtyMeetPlcy($newpswd, $uname, $error) == true) {
                            $nwUID = getUserID($uname);
                            $nwPrsnID = getPersonIDUseMail($uname);
                            if ($nwPrsnID <= 0) {
                                $locIDTextBox = "";
                                $loc_id = getNewLocIDNumberGnrl($locIDTextBox, "APLCNT-");
                                createPrsnBasicGnrl(
                                    $pFirstName,
                                    $pSurName,
                                    "",
                                    "",
                                    $loc_id,
                                    $orgID,
                                    "",
                                    "",
                                    "",
                                    "",
                                    "",
                                    "",
                                    "",
                                    $pEmail,
                                    "",
                                    $pPhone,
                                    "",
                                    "",
                                    "",
                                    "",
                                    -1,
                                    -1,
                                    "",
                                    ""
                                );
                                $nwPrsnID = getPersonIDUseMail($uname);
                            }
                            if ($nwUID <= 0 && $nwPrsnID > 0) {
                                $errMsg = "";
                                $res = checkNCreateUserUsgMail($uname, $errMsg);
                                $nwUID = getUserID($uname);
                                $error .= $res === TRUE ? "" : $errMsg;
                            }
                            if ($error === "" && $nwUID > 0 && $nwPrsnID > 0) {
                                storeOldPassword($nwUID, $newpswd);
                                echo changeUserPswd($nwUID, $newpswd, "FALSE");
                                $in_org_id = getUserOrgID($uname);
                                $uID = getUserID($uname);
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
                                echo sendEmailVerifyLink($uname);
                            } else {
                                $error = "ERROR:" . $error;
                                exit();
                            }
                        } else {
                            $error .= "<br/>E.g of an Acceptable Password is " . $smplPwd;
                        }
                        if ($error !== "") {
                            echo "<span style=\"color:red !important;\">" . $error . "</span>";
                        }
                    } else {
                        echo "<span style=\"color:red !important;\">" . $error . "</span>";
                    }
                } else if ($uname != '') {
                    echo "<span style=\"color:red !important;\">Please fill all Required Fields!<br/>Do not use the example password $smplPwd</span>";
                } else {
                    restricted();
                }
            } else {
                restricted();
            }
        } else if ($group == 210) {
            if ($type == 1) {
                require 'srvr_self/self_intro.php';
            } else if ($type >= 2 && $type <= 5) {
                require 'srvr_self/base_files/login.php';
            } else {
                restricted();
            }
        } else {
            restricted();
        }
    } else {
        echo "<html>
        <head>
            <link rel=\"stylesheet\" type=\"text/css\" href=\"cmn_scrpts/rho_form.css?v=12\" />        
            </head>
            <body><div id=\"rho_form\" style=\"width: 500px;position: absolute; top:20%; bottom: 20%; left: 20%; right: 20%; margin: auto;\">
            <div class='rho_form44' style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    background-color:#e3e3e3;border: 1px solid #999;padding:20px 30px 30px 20px;\"> 
            " . $notAllowed . " not Supported! <br/> Please upgrade to the latest version! 
                    <br/> Alternatively, you can download and Install the ff Recommended Browsers...
                    <ul>
                    <li><a href=\"https://www.google.com/chrome/browser/desktop/index.html\">Google Chrome</a></li>
                    <li><a href=\"https://www.mozilla.org/en-US/firefox/new/\">Firefox</a></li>
                    <li><a href=\"index.php\">Or You can click here to Try Again!</a></li>
                    </ul>
                    </div></div></body><html>";
        exit();
    }
}
?>