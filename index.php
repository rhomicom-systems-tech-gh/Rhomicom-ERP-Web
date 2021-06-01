<?php
if (!isset($_SESSION['LGN_NUM'])) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_startup_errors', true);
ini_set("display_errors", TRUE);
ini_set("html_errors", TRUE);
require 'app_code/cmncde/connect_pg.php';
require 'xlsxwriter.class.php';
$isMntnceMode = ($putInMntnceMode == "YES") ? TRUE : FALSE;
$mstChngPwd = isset($_GET['cp']) ? (int) cleanInputData($_GET['cp']) : '0';
$shdGoConfig = isset($_POST['shdGoConfig']) ? (int) ($_POST['shdGoConfig']) : 0;
if ($shdGoConfig === 1005) {
    $actyp = isset($_POST['actyp']) ? (int) ($_POST['actyp']) : 0;
    if ($actyp <= 0) {
        require 'header1.php';
    }
    require 'rho_config.php';
    exit();
}
if ($mstChngPwd === 2) {
    require 'header1.php';
    require 'rho_config.php';
    exit();
}
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

    //var_dump($_SESSION);
    //var_dump($_POST);
    $type = 0;
    $group = 0;
    $pgNo = 0;
    $cnfgFileExists = file_exists($superAdminConfigFilePath);
    if ($cnfgFileExists === FALSE || is_dir($superAdminConfigFilePath)) {
        require 'header1.php';
        require 'rho_config.php';
        exit();
    }
    $conn = getConn();
    if ($conn === FALSE) {
        require 'header1.php';
        require 'rho_config.php';
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
                //header("Location: index.php");
                //exit();
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

    require 'app_code/cmncde/globals.php';
    require 'app_code/cmncde/admin_funcs.php';
    require 'loginController.php';

    $gDcrpt = isset($_GET['g']) ? cleanInputData($_GET['g']) : '';
    $progressPrcnt = isset($_POST['gtp']) ? cleanInputData($_POST['gtp']) : '';
    if ($progressPrcnt == "GETPROGRESS") {
        echo $_SESSION['PROGRESS_PRCNT'];
        exit();
    } else if ($progressPrcnt == "GETRSLT") {
        echo $_SESSION['PROGRESS_RSLT'];
        exit();
    }

    $screenwdth = isset($_POST['screenwdth']) ? cleanInputData($_POST['screenwdth']) : $_SESSION['SCREEN_WIDTH'];
    $_SESSION['SCREEN_WIDTH'] = $screenwdth;
    $gUNM = '';
    $notAllowed = "";
    $error = "";
    $qryStr = "";
    $usrID = (float) $_SESSION['USRID'];
    $usrName = $_SESSION['UNAME'];
    $prsnid = (float) $_SESSION['PRSN_ID'];
    $lgn_num = (float) $_SESSION['LGN_NUM'];
    $orgID = (int) $_SESSION['ORG_ID'];
    $orgName = $_SESSION['ORG_NAME'];
    $orgLogoFileName = $_SESSION['ORG_LOGO_FILE_NAME'];
    $myImgFileName = $_SESSION['FILES_NAME_PRFX'];
    $blnFlExst = file_exists($fldrPrfx . $tmpDest . $myImgFileName);
    if ($lgn_num > 0 && $usrID > 0 && !$blnFlExst) {
        recopyPrflPic($usrName, $lgn_num);
        $myImgFileName = $_SESSION['FILES_NAME_PRFX'];
    }
    $app_image1 = "cmn_images/" . $app_image1;
    $blnFlExst2 = trim($orgLogoFileName) === "" ? false : file_exists($fldrPrfx . $orgLogoFileName);
    if ($lgn_num > 0 && $usrID > 0 && !$blnFlExst2) {
        recopyOrgLogo($orgID, $lgn_num);
        $orgLogoFileName = $_SESSION['ORG_LOGO_FILE_NAME'];
    }
    if ($orgID > 0) {
        $app_name = $orgName;
        $app_image1 = $orgLogoFileName;
    }
    $isWflMailAllwdID = getEnbldPssblValID("Allow Workflow Emails", getLovID("All Other General Setups"));
    $isWflMailAllwd = getPssblValDesc($isWflMailAllwdID);
    $formArray = array();
    if ($gDcrpt != '') {
        $gDcrpt = decrypt($gDcrpt, $smplTokenWord1);
        $arrayParams = explode("|", $gDcrpt);
        if (count($arrayParams) == 7) {
            $numChars = intval($arrayParams[1]);
            $numChars1 = intval($arrayParams[5]);

            $numTxt = ($arrayParams[0]);
            $numTxt1 = ($arrayParams[6]);

            $expDte = $arrayParams[2];
            if (getDB_Date_time() >= $expDte || strlen($numTxt) != $numChars || strlen($numTxt1) != $numChars1) {
                sessionInvalid();
                exit();
            }
            $qryStr = $arrayParams[3];
            $gUNM = $arrayParams[4];
        } else {
            sessionInvalid();
            exit();
        }
    } else if (isset($HTTP_RAW_POST_DATA) && count($_POST) <= 0) {
        $formArray = json_decode($HTTP_RAW_POST_DATA, true);
        //var_dump($formArray);
        $group = isset($formArray['grp']) ? $formArray['grp'] : 0;
        $type = isset($formArray['typ']) ? $formArray['typ'] : 0;
        $qryStr = isset($formArray['q']) ? cleanInputData($formArray['q']) : '';
        $pgNo = isset($formArray['pg']) ? cleanInputData($formArray['pg']) : 0;
    } else {
        $group = isset($_POST['grp']) ? cleanInputData($_POST['grp']) : 0;
        $type = isset($_POST['typ']) ? cleanInputData($_POST['typ']) : 0;
        $qryStr = isset($_POST['q']) ? cleanInputData($_POST['q']) : '';
        $pgNo = isset($_POST['pg']) ? cleanInputData($_POST['pg']) : 0;
        if ($mstChngPwd === 1) {
            $qryStr = "changepassword";
        }
    }
    if ($group > 0 && $type > 0) {
        if ($group == 1) {
            if ($type == 1) {
                require 'app_code/cmncde/rho_shapes.php';
            } else if ($type == 2) {
                require 'app_code/cmncde/rho_headline.php';
            } else if ($type == 3) {
                require 'app_code/cmncde/rho_slogan.php';
            } else if ($type == 4) {
                require 'app_code/cmncde/selfServiceMenu.php';
            } else if ($type == 5) {
                require 'app_code/cmncde/rho_welcome.php';
            } else if ($type == 6) {
                require 'app_code/cmncde/login.php';
            } else if ($type == 7) {
                require 'app_code/cmncde/my_pswd_chnge.php';
            } else if ($type == 8) {
                require 'app_code/cmncde/my_roles.php';
            } else if ($type == 9) {
                require 'app_code/cmncde/rho_header.php';
            } else if ($type == 10) {
                require 'app_code/cmncde/myInbx.php';
            } else if ($type == 11) {
                require 'app_code/cmncde/rho_qry_infos.php';
            } else if ($type == 12) {
                //require 'app_code/cmncde/top_menu.php';
            } else if ($type == 13) {
                require 'app_code/cmncde/dashboard_data.php';
            } else if ($type == 14) {
                require 'app/ux/cmnFileUpload.php';
            } else {
                header('location: index.php');
            }
        } else if ($group == 2) {
            if ($type == 1) {
                require 'app_code/cmncde/lovDialogs.php';
            } else if ($type == 2) {
                require 'app_code/cmncde/lovDialogs_mcf.php';
            }
        } else if ($group == 3) {
            //System Administration
            if ($type == 1) {
                require 'app_code/sec/sys_admin_intro.php';
            }
        } else if ($group == 4) {
            //General Setup
            if ($type == 1) {
                require 'app_code/gst/gst_intro.php';
            }
        } else if ($group == 5) {
            //Organisation Setup
            if ($type == 1) {
                require 'app_code/org/org_intro.php';
            }
        } else if ($group == 6) {
            //Accounting
            if ($type == 1) {
                require "app_code/accb/accb_funcs.php";
                require 'app_code/accb/accounting.php';
            }
        } else if ($group == 7) {
            //Internal Payments
            if ($type == 1) {
                require 'app_code/pay/int_pymnts_intro.php';
            }
        } else if ($group == 8) {
            //Basic Person Data
            if ($type == 1) {
                require 'app_code/prs/prs_intro.php';
            }
        } else if ($group == 9) {
            //Reports And Processes        
            if ($type == 1) {
                require 'app_code/rpt/rpts_intro.php';
            }
        } else if ($group == 10) {
            //Alerts Manager
            if ($type == 1) {
                require 'app_code/alrt/alrt_intro.php';
            }
        } else if ($group == 11) {
            //Workflow Manager
            if ($type == 1) {
                require 'app_code/wkf/wkf_intro.php';
            }
        } else if ($group == 12) {
            //Inventory
            if ($type == 1) {
                require "app_code/inv/inv_funcs.php";
                require 'app_code/inv/inv_intro.php';
            }
        } else if ($group == 13) {
            //Projects
            if ($type == 1) {
                require 'app_code/proj/proj_intro.php';
            }
        } else if ($group == 14) {
            //var_dump($_POST);
            //Clinic/Hospital
            if ($type == 1) {
                require 'app_code/hosp/hosp_mgr.php';
            }
        } else if ($group == 15) {
            //var_dump($_POST);
            //Academics
            if ($type == 1) {
                require 'app_code/aca/aca_intro.php';
            }
        } else if ($group == 16) {
            //var_dump($_POST);
            //Events & Attendance
            if ($type == 1) {
                require "app_code/inv/inv_funcs.php";
                require "app_code/hotl/hotl_funcs.php";
                require 'app_code/attn/attn_intro.php';
            }
        } else if ($group == 17) {
            //var_dump($_POST);
            //Banking/Microfinance
            if ($type == 1) {
                require 'app_code/mcf/mcf_intro.php';
            }
        } else if ($group == 18) {
            //var_dump($_POST);
            //Hospitality Management
            if ($type == 1) {
                require "app_code/inv/inv_funcs.php";
                require "app_code/hotl/hotl_funcs.php";
                require 'app_code/hotl/hotl_intro.php';
            }
        } else if ($group == 19) {
            //var_dump($_POST);
            //Self-Service
            if ($type == 1) {
                require 'app_code/self/self_intro.php';
            } else if ($type == 2) {
                require 'app_code/self/my_prsnl_data.php';
                //require 'ext-5/index.html';
            } else if ($type == 3) {
                require 'app_code/self/my_intnl_pay.php';
            } else if ($type == 4) {
                require 'app_code/self/my_pay_docs.php';
            } else if ($type == 5) {
                require 'app_code/self/my_htl_books.php';
            } else if ($type == 6) {
                require 'app_code/self/my_evnts_attn.php';
            } else if ($type == 7) {
                require 'app_code/self/my_acdmc_data.php';
            } else if ($type == 8) {
                require 'app_code/self/my_clnc_data.php';
            } else if ($type == 9) {
                require 'app_code/self/my_bank_data.php';
            } else if ($type == 10) {
                require 'app_code/evote/evote_intro.php';
            } else if ($type == 11) {
                require 'app_code/self/self_service_setups.php';
            } else if ($type == 12) {
                require 'app_code/elearn/elearn_intro.php';
            }
        } else if ($group == 20) {
            //Basic Person Data
            if ($type == 1) {
                //require 'app_code/epay/epay_intro.php';
            } else if ($type == 2) {
                require 'app_code/epay/lovDialogs.php';
            }
        } else if ($group == 21) {
            //Personalle Data
            if ($type == 1) {
                require 'app_code/prs/my_personal_data.php';
            }
        } else if ($group == 25) {
            //Vault Management
            if ($type == 1) {
                require 'app_code/vms/vms_intro.php';
            }
        } else if ($group == 26) {
            //Agent Registry
            if ($type == 1) {
                require 'app_code/agnt/agnt_intro.php';
            }
        } else if ($group == 27) {
            //Agent Registry
            if ($type == 1) {
                require 'app_code/atrckr/atrckr_intro.php';
            }
        } else if ($group == 40) {
            //Personalle Data
            if ($type == 1) {
                require 'app_code/cmncde/' . $homepgfile;
            } else if ($type == 2) {
                require 'app_code/cmncde/myInbx.php';
            } else if ($type == 3) {
                require 'app_code/cmncde/all_notices.php';
            } else if ($type == 4) {
                require 'app_code/cmncde/dashboard_data.php';
            } else if ($type == 5) {
                require 'app_code/cmncde/all_modules_menu.php';
            } else if ($type == 401) {
                session_write_close();
                $gnrlTrnsDteDMYHMS = getFrmtdDB_Date_time();
                $gnrlTrnsDteYMDHMS = cnvrtDMYTmToYMDTm($gnrlTrnsDteDMYHMS);
                $gnrlTrnsDteYMD = substr($gnrlTrnsDteYMDHMS, 0, 10);
                $ymdtme = substr($gnrlTrnsDteDMYHMS, 0, 11);
                $ymdtme1 = getDB_Date_TmIntvlAddSub($ymdtme, "6 Month", "Subtract");
                //echo $ymdtme1;
                $ymdtme2 = getDB_Date_TmIntvlAddSub(getDB_Date_TmIntvlAddSub("01" . substr($gnrlTrnsDteDMYHMS, 2, 9), "1 month", "Add"), "1 day", "Subtract");
                $ymdtme3 = "01" . substr($ymdtme1, 2, 9);
                $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                $qShwSmmry = FALSE;
                $accbFSRptAcntTypes = "R-REVENUE/EX-EXPENSE";
                $accbFSRptPrdType = isset($_POST['accbFSRptPrdType']) ? cleanInputData($_POST['accbFSRptPrdType']) : "Monthly";
                $accbFSRptMaxAcntLvl = 100;
                $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID']) : -1;
                $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
                if ($accbFSRptSbmtdAccountID > 0) {
                    $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
                }
                $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3, 0, 11);
                $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                $accbFSRptDte1 = $accbFSRptDte;
                $accbFSRptSgmnt1ValID = isset($_POST['accbFSRptSgmnt1ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt1ValID']) : -1;
                $accbFSRptSgmnt2ValID = isset($_POST['accbFSRptSgmnt2ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt2ValID']) : -1;
                $accbFSRptSgmnt3ValID = isset($_POST['accbFSRptSgmnt3ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt3ValID']) : -1;
                $accbFSRptSgmnt4ValID = isset($_POST['accbFSRptSgmnt4ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt4ValID']) : -1;
                $accbFSRptSgmnt5ValID = isset($_POST['accbFSRptSgmnt5ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt5ValID']) : -1;
                $accbFSRptSgmnt6ValID = isset($_POST['accbFSRptSgmnt6ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt6ValID']) : -1;
                $accbFSRptSgmnt7ValID = isset($_POST['accbFSRptSgmnt7ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt7ValID']) : -1;
                $accbFSRptSgmnt8ValID = isset($_POST['accbFSRptSgmnt8ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt8ValID']) : -1;
                $accbFSRptSgmnt9ValID = isset($_POST['accbFSRptSgmnt9ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt9ValID']) : -1;
                $accbFSRptSgmnt10ValID = isset($_POST['accbFSRptSgmnt10ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt10ValID']) : -1;


                $shwSmmryChkd = "";
                if ($qShwSmmry == true) {
                    $shwSmmryChkd = "checked=\"true\"";
                }
                $fsrptRunID = getMinFSRptRunID();
                if ($fsrptRunID <= 0) {
                    $fsrptRunID = getNewFSRptRunID();
                    if ($accbStrtFSRptDte != "") {
                        $accbStrtFSRptDte = cnvrtDMYToYMD($accbStrtFSRptDte);
                    }
                    if ($accbFSRptDte != "") {
                        $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                    }
                    $strSql = "select accb.populate_prd_by_prd_bals( " . $fsrptRunID . ", '"
                        . $accbFSRptAcntTypes . "', '" . $accbStrtFSRptDte .
                        "', '" . $accbFSRptDte . "', '"
                        . $accbFSRptPrdType . "', " . $accbFSRptMaxAcntLvl . ", "
                        . $accbFSRptSbmtdAccountID . ","
                        . $accbFSRptSgmnt1ValID . ", " . $accbFSRptSgmnt2ValID . ", "
                        . $accbFSRptSgmnt3ValID . ", " . $accbFSRptSgmnt4ValID . ", "
                        . $accbFSRptSgmnt5ValID . ", " . $accbFSRptSgmnt6ValID . ", "
                        . $accbFSRptSgmnt7ValID . ", " . $accbFSRptSgmnt8ValID . ", "
                        . $accbFSRptSgmnt9ValID . ", " . $accbFSRptSgmnt10ValID . ", "
                        . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1);";
                    //echo $strSql;
                    $result = executeSQLNoParams($strSql);
                }
            } else {
                restricted();
            }
        } else if ($group == 41) {
            //Help Desk 
            if ($type == 1) {
                require 'app_code/hlpd/hlpd_intro.php';
            } else {
                restricted();
            }
        } else if ($group == 50){
            //Catholic Church Sacrament
            if ($type == 1) {
                require 'app_code/ccs/ccs_intro.php';
            }            
        } 
        else {
            header('location: index.php');
        }
        exit();
    } else {
        if ($notAllowed == "") {
            if ($lgn_num <= 0) {
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
                    if ($login_result === "select role" || $login_result === "select self") {
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
                } else if ($qryStr == "changepassword" || $_SESSION['MUST_CHNG_PWD'] == "1") {
                    require 'header.php';
                    require 'chngpwd.php';
                    exit();
                } else if ($qryStr == "forgotpwd") {
                    require 'header.php';
                    require 'frgtpwd.php';
                    exit();
                } else if ($qryStr == "logout") {
                    $error = "";
                    require 'header.php';
                    require 'login.php';
                } else if ($qryStr == "timeout") {
                    logoutActions();
                    $error = "Session Timed Out! Please login again!";
                    require 'header.php';
                    require 'login.php';
                } else {
                    $error = "";
                    require 'header.php';
                    require 'login.php';
                }
            } else {
                if ($qryStr == "logout") {
                    logoutActions();
                    $error = "Successfully Logged Out";
                    require 'header.php';
                    require 'login.php';
                    exit();
                } else if ($qryStr == "timeout") {
                    logoutActions();
                    $error = "Session Timed Out! Please login again!";
                    require 'header.php';
                    require 'login.php';
                } else if ($qryStr == "changepassword" || $_SESSION['MUST_CHNG_PWD'] == "1") {
                    require 'header.php';
                    require 'chngpwd.php';
                    exit();
                } else {
                    require 'header.php';
                    require 'app.php';
                }
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
}
?>