<?php
//echo str_replace("_RHOMICOM221_", "://", str_replace("//", "/", str_replace("://", "_RHOMICOM221_", str_replace("index.php", "", rhoUrl()))));
$_SESSION['ERROR_MSG'] = "";
if (!isset($_SESSION['UNAME'])) {
    $_SESSION['UNAME'] = "GUEST";
}
if (!isset($_SESSION['USRID'])) {
    $_SESSION['USRID'] = -1;
}
if (!isset($_SESSION['LGN_NUM'])) {
    $_SESSION['LGN_NUM'] = -1;
}
if (!isset($_SESSION['ORG_NAME'])) {
    $_SESSION['ORG_NAME'] = "";
}
if (!isset($_SESSION['ORG_LOGO_FILE_NAME'])) {
    $_SESSION['ORG_LOGO_FILE_NAME'] = "";
}
if (!isset($_SESSION['ORG_ID'])) {
    $_SESSION['ORG_ID'] = -1;
}
if (!isset($_SESSION['ROOT_FOLDER'])) {
    $_SESSION['ROOT_FOLDER'] = "";
}
if (!isset($_SESSION['PRV_OFFST'])) {
    $_SESSION['PRV_OFFST'] = 0;
}
if (!isset($_SESSION['FILES_NAME_PRFX'])) {
    $_SESSION['FILES_NAME_PRFX'] = "";
}
if (!isset($_SESSION['ROLE_SET_IDS'])) {
    $_SESSION['ROLE_SET_IDS'] = "";
}

if (!isset($_SESSION['CUR_TAB'])) {
    $_SESSION['CUR_TAB'] = "C1";
}
if (!isset($_SESSION['CUR_RPT_FILES'])) {
    $_SESSION['CUR_RPT_FILES'] = "";
}
if (!isset($_SESSION['CUR_IFRM_SRC'])) {
    $_SESSION['CUR_IFRM_SRC'] = "";
}

if (!isset($_SESSION['CUR_PRSN_ID'])) {
    $_SESSION['CUR_PRSN_ID'] = -1;
}

if (!isset($_SESSION['PRSN_ID'])) {
    $_SESSION['PRSN_ID'] = -1;
}
if (!isset($_SESSION['MUST_CHNG_PWD'])) {
    $_SESSION['MUST_CHNG_PWD'] = "0";
}
if (!isset($_SESSION['PRSN_FNAME'])) {
    $_SESSION['PRSN_FNAME'] = "";
}
/**
 * PROSPECTIVE MEMBER SESSION VARIABLES
 */
if (!isset($_SESSION['UNAMEPM'])) {
    $_SESSION['UNAMEPM'] = "";
}
if (!isset($_SESSION['USRIDPM'])) {
    $_SESSION['USRIDPM'] = -1;
}

if (!isset($_SESSION['PRSN_ID_PM'])) {
    $_SESSION['PRSN_ID_PM'] = -1;
}

if (!isset($_SESSION['PRSN_FNAME_PM'])) {
    $_SESSION['PRSN_FNAME_PM'] = "";
}
if (!isset($_SESSION['SCREEN_WIDTH'])) {
    $_SESSION['SCREEN_WIDTH'] = 0;
}
if (!isset($_SESSION['PROGRESS_PRCNT'])) {
    $_SESSION['PROGRESS_PRCNT'] = 0;
}
if (!isset($_SESSION['PROGRESS_RSLT'])) {
    $_SESSION['PROGRESS_RSLT'] = "";
}

if (!isset($_SESSION['SUPERADMINCONFIGPSWD2'])) {
    $_SESSION['SUPERADMINCONFIGPSWD2'] = "";
}
if (!isset($_SESSION['SELECTED_SALES_STOREID'])) {
    $_SESSION['SELECTED_SALES_STOREID'] = -1;
}
//echo str_replace("index.php", "", rhoUrl());
$radomNo = rand(0, 999999999);
$app_version = "V1 P41";
$app_org = "Rhomicom Systems Tech. Ltd.";
$about_app = "About Rhomicom";
$about_url = "http://rhomicom.com";
$base_dir = "";
$logNxtLine = PHP_EOL . "-------------------------Next Msg:-------------------------" . PHP_EOL;
$showAboutRho = "0";
$introWdth = 1050;
$subArtWdth = 524;
$base_folder = "";

$page_title = "";
$homepgfile = "home.php";
$browserPDFCmd = "google-chrome"; //chromium-browser
$rhoAPIUrl = "http://rho-api:3000";
$app_url = "";
$flxcde_url = "";
$admin_email = "";
$admin_name = "";
$smplPwd = "";
$jsCssFileVrsn = "";
$noticesElmntNm = '';
$smplTokenWord = "";
$smplTokenWord1 = "";
$database = "";
$currentPgConn;
$db_folder = "";
$fldrPrfx = "";
$tmpDest = '';
$pemDest = '';
$ftp_base_db_fldr = "";
$softwareLincenseCode = "";
$superAdminConfigPswd = "";
$db_pwd = '';
$db_usr = "";
$postgre_db_pwd = '';
$port = "";
$host = "";
$app_name = "";
$system_name = "";
$app_cstmr = "";
$app_cstmr_url = "";
$app_slogan = "";
$app_image1 = "";
$app_favicon = "";
$bckcolors = "";
$bckcolorsChngPwd = "";
$bckcolors_home = "";
$bckcolorOnly = "";
$bckcolorOnly1 = "";
$bckcolorOnly2 = "";
$bckcolorshv = "";
$forecolors = "";
$bckcolors1 = "";
$bckcolors2 = "";
$breadCrmbBckclr = "";
$loginPgNotice = "";
$goBackButtonMsg = "";
$placeHolder1 = "";
$loginTitle = "";
$website_btn_txt = "";
$instructions = "";
$abt_portal = "";
$introToPrtlArtBody = "";
$usefulLnksArtBody = "";
$aboutRho = "";
//MySQLi Database Functions
$mysql_db_name = "";
$mysql_db_user = "";
$mysql_db_pass = "";
$mysql_db_host = "";
$mysql_db_port = "";
$putInMntnceMode = "NO";


$smplTokenWordRhoKey = "92o2oG@-RhOmIxewARBe1ERPs58WEBrteC21This is a General Key for Rhoemi|coChm SysGtemHs "
    . "Tech. !Ltd Web/Mobile Portal @7612364BANKINGGhdfjwegSolutionsyr782L36429orbjkasdbhi";

$superAdminConfigFilePathLoc = str_replace("/mailer", "", str_replace("/wsdls", "", str_replace("/xchange", "", str_replace("/self", "", str_replace("/dwnlds", "", dirname($_SERVER['SCRIPT_FILENAME'])))))) . "/dwnlds/pem/superAdminConfigFilePathLoc.rhocnfg";
$superAdminConfigFilePath = "/opt/apache/adbs/superAdminConfigFile.rhocnfg";
if (file_exists($superAdminConfigFilePathLoc)) {
    $text = file_get_contents($superAdminConfigFilePathLoc);
    $dcdedTxt = decrypt($text, $smplTokenWordRhoKey);
    $superAdminConfigFilePath = $dcdedTxt;
} else {
    file_put_contents($superAdminConfigFilePathLoc, encrypt1($superAdminConfigFilePath, $smplTokenWordRhoKey));
}
if (file_exists($superAdminConfigFilePath)) {
    $text = file_get_contents($superAdminConfigFilePath);
    $obj = json_decode($text);
    $page_title = $obj->page_title;
    $app_url = $obj->app_url;
    $flxcde_url = $obj->flxcde_url;
    $app_name = $obj->app_name;
    $system_name = $obj->system_name;
    $app_cstmr = $obj->app_cstmr;
    $app_cstmr_url = $obj->app_cstmr_url;
    $app_org = $obj->app_cstmr;
    $about_app = "About App";
    $about_url = $obj->app_cstmr_url;
    $app_slogan = $obj->app_slogan;
    $admin_email = $obj->admin_email;
    $admin_name = $obj->admin_name;
    $jsCssFileVrsn = $obj->jsCssFileVrsn;
    $noticesElmntNm = $obj->noticesElmntNm;
    $smplPwd = $obj->smplPwd;
    $host = $obj->host;
    $database = decrypt($obj->database, $smplTokenWordRhoKey);
    $db_usr = decrypt($obj->db_usr, $smplTokenWordRhoKey);
    $db_pwd = decrypt($obj->db_pwd, $smplTokenWordRhoKey);
    $port = $obj->port;
    $postgre_db_pwd = decrypt($obj->postgre_db_pwd, $smplTokenWordRhoKey);
    $db_folder = $obj->db_folder;
    $fldrPrfx = $obj->fldrPrfx;
    $tmpDest = $obj->tmpDest;
    $pemDest = $obj->pemDest;
    $ftp_base_db_fldr = $obj->ftp_base_db_fldr;
    $mysql_db_name = decrypt($obj->mysql_db_name, $smplTokenWordRhoKey);
    $mysql_db_user = decrypt($obj->mysql_db_user, $smplTokenWordRhoKey);
    $mysql_db_pass = decrypt($obj->mysql_db_pass, $smplTokenWordRhoKey);
    $mysql_db_host = $obj->mysql_db_host;
    $mysql_db_port = $obj->mysql_db_port;
    $superAdminConfigPswd = decrypt($obj->superAdminConfigPswd, $smplTokenWordRhoKey);
    $superAdminConfigFilePath = $obj->superAdminConfigFilePath;
    $softwareLincenseCode = decrypt($obj->softwareLincenseCode, $smplTokenWordRhoKey);
    $smplTokenWord1 = decrypt($obj->smplTokenWord1, $smplTokenWordRhoKey);
    $app_image1 = $obj->app_image1;
    $app_favicon = $obj->app_favicon;
    $bckcolorOnly = $obj->bckcolorOnly;
    $bckcolorOnly1 = $obj->bckcolorOnly1;
    $bckcolorOnly2 = $obj->bckcolorOnly2;
    $bckcolorshv = $obj->bckcolorshv;
    $forecolors = $obj->forecolors;
    $bckcolors1 = $obj->bckcolors1;
    $bckcolors2 = $obj->bckcolors2;
    $goBackButtonMsg = $obj->goBackButtonMsg;
    $placeHolder1 = $obj->placeHolder1;
    $loginTitle = $obj->loginTitle;
    $website_btn_txt = $obj->website_btn_txt;
    $bckcolors = $obj->bckcolors;
    $bckcolorsChngPwd = $obj->bckcolorsChngPwd;
    $bckcolors_home = $obj->bckcolors_home;
    $breadCrmbBckclr = $obj->breadCrmbBckclr;
    $loginPgNotice = $obj->loginPgNotice;
    $instructions = $obj->instructions;
    $introToPrtlArtBody = $obj->introToPrtlArtBody;
    $ltstNewArtBody = $obj->ltstNewArtBody;
    $usefulLnksArtBody = $obj->usefulLnksArtBody;
    $aboutRho = $obj->aboutRho;
    $homepgfile = isset($obj->homepgfile) ? $obj->homepgfile : "home.php";
    $browserPDFCmd = isset($obj->browserPDFCmd) ? $obj->browserPDFCmd : $browserPDFCmd;
    $rhoAPIUrl = isset($obj->rhoAPIUrl) ? $obj->rhoAPIUrl : $rhoAPIUrl;
    $putInMntnceMode = isset($obj->putInMntnceMode) ? $obj->putInMntnceMode : "NO";
    $smplTokenWord = decrypt($obj->smplTokenWord, $smplTokenWordRhoKey);
    $abt_portal = $obj->abt_portal;
    if (!isset($_SESSION['SUPERADMINCONFIGPSWD'])) {
        $_SESSION['SUPERADMINCONFIGPSWD'] = encrypt($superAdminConfigPswd, $smplTokenWord1);
    }
} else {
    $page_title = "Rhomicom Systems Tech. Ltd. - Partnership Portal";
    $app_url = str_replace("_RHOMICOM221_", "://", str_replace("//", "/", str_replace("://", "_RHOMICOM221_", str_replace("index.php", "", rhoUrl()))));
    $flxcde_url = $app_url . "flexcode/";
    $admin_email = "support@rhomicom.com";
    $admin_name = "Rhomicom Portal Administrator";
    $smplPwd = "AoP12@35";
    $jsCssFileVrsn = "20180813_114";
    $noticesElmntNm = 'allnotices';
    //More than 62 Characters Recommended
    $smplTokenWord = "CARBTC GH eRRTRhbnsdGeneral Key for RhComi|com Saystems "
        . "Tech. !Ltd Enbterpise/Organitzation @7635l42orbSjkasdbhi68103CweuikfjBnsdf";
    $smplTokenWord1 = "xewARBe19Tecfs58rteC21This is a General Key for Rhoemi|coChm SysGtemHs "
        . "Tech. !Ltd Web/Mobile Portal @7612364kjebGhdfjwegSolutionsyr782L36429orbjkasdbhi";
    $database = "obaa_live";
    $db_folder = "adbs";
    $base_folder = "";
    $fldrPrfx = str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']); //'/var/www/html/rho/';
    $tmpDest = 'dwnlds/tmp/';
    $pemDest = 'dwnlds/pem/';
    $ftp_base_db_fldr = "/home/apache/" . $db_folder;
    $softwareLincenseCode = "Rhomicom Systems T3ch. Ltd. ERP/B@nk!nG SYSTEM Web-Based Solution WebConfig p@SswOrD!";
    $superAdminConfigPswd = "Password1";
    $db_pwd = 'Password1';
    $db_usr = "postgres";
    $postgre_db_pwd = 'Password1';
    $port = "5432";
    $host = "localhost";
    $app_name = "Rhomicom Systems Tech. Ltd.";
    $app_image1 = "3.png";
    $system_name = "Rhomi ERP/Banking Systems";
    $app_cstmr = "Carbtec Microfinance Ltd"; //Clients Name
    $app_cstmr_url = "http://rhomicom.com";
    $app_slogan = "Building Dreams";
    $app_favicon = "Icon.ico";
    $bckcolors = "background-repeat: repeat;background-color: #336578 !important;background-image:url('cmn_images/bkg10.jpeg'); background-size:100% 100%;";
    $bckcolorsChngPwd = "background-color: #FFFFFF !important;background: url('cmn_images/bkg10.jpeg') no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;";
    $bckcolors_home = "background-repeat: repeat;background-color: #003245 !important;background-size:100% 100%;";
    $bckcolorOnly = "#00779D";
    $bckcolorOnly1 = "#003245";
    $bckcolorOnly2 = "#5a8f9d";
    $bckcolorshv = "background-color: #5a8f9d !important;"; //#00AACF
    $forecolors = "color:#FFFFFF !important;";
    $bckcolors1 = "#FFF";
    $bckcolors2 = "background-color: #00779D !important;";
    $breadCrmbBckclr = "
    border: 1px solid #336578;
    background-color: #003245;
    background-image: -moz-linear-gradient(top, #336578, #003245);
    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#336578), to(#003245));
    background-image: -webkit-linear-gradient(top, #336578, #003245);
    background-image: -o-linear-gradient(top, #336578, #003245);
    background-image: linear-gradient(to bottom, #336578, #003245);
    filter: progid:dximagetransform.microsoft.gradient(startColorstr='#336578', endColorstr='#003245', GradientType=0);";

    $loginPgNotice = "<p style=\"font-family: Tahoma;font-size:14px;\">Rhomicom Head Quarters, Achimota-ABC, Accra-Ghana</p>";

    $goBackButtonMsg = "Go Back to Rhomicom Website";
    $placeHolder1 = "Username or ID No.";
    $loginTitle = "LOGIN TO THE PARTNERSHIP PORTAL";
    $website_btn_txt = "Rhomicom Website";

    $instructions = "<ol type=\"1\" start=\"1\"><li>Click on Request for New password</li>
<li>Enter your ID number and Click Send Password Reset Link</li>
<li>Check your email (registered) for a mail from System Administrator ($admin_email)</li>
<li>In the mail, click on \"<span style=\"text-decoration:underline;color:lime;\">Click on this link to RESET it!</span>\" and then enter your New Password!</li>
</ol>";
    $abt_portal = "The IT system accessible to all Staff Members of Banking Department through the use of personal identification. It has the ff features:
    <ul style=\"list-style-type: square;text-align:left;padding-left: 80px;color:lime;\">
    <li>Forums/Notices, Banking, Payments etc.</li>
    </ul><br/>
<span style=\"float:left;text-align:left;font-style:italic;font-family:times;font-weight:bold;color:cyan;\">System Info: $system_name $app_version</span>";
    $introToPrtlArtBody = "<div class=\"\" style=\"font-family:Arial !important; padding-left:20px\">
                    <h3><a href=\"javascript: showNoticeDetails({:articleID},'Notices/Announcements');\" style=\"font-weight:bold;text-decoration:underline;\">INTRODUCTION TO THE PORTAL</a></h3>
                                <p style=\"line-height: 162%;\">
                                <a href=\"$app_cstmr_url\" target=\"_blank\">
                                <img style=\"float:right; height:125px; margin-top:-5px; margin-left:10px;\" alt=\"MEMBERS\" src=\"cmn_images/members.png\" >
                                </a>
                                <a href=\"$app_cstmr_url\" target=\"_blank\">
                                <img style=\"float:left; height:125px;  margin-top:-5px;margin-right:1px;\" alt=\"ORG LOGO\" src=\"cmn_images/3.png\" />
                                </a>
                                This is the Information Resource Centre for all Staff of the " . $app_cstmr . ". 
                                It is the central depot for all Notices, Announcements, Notices and Research Papers. A member who logs in can view all directly Related Records held by the Institution.
                                Course Bills and Dues Payments can be checked from here.
                                CPD Points, Examination Scores as well as all other Information on Seminars and Workshops attended are available here. 
                                Annual dues subscriptions can be paid to designated banks and the pay-in-slip submitted on this platform. 
                                Voting and Checking of Election Results can all be done from here...{:RMS} <br/>There is also a live and vibrant forum here where members can share knowledge
            and expertise on areas of importance. Notices and research papers can be
            submitted to the Institution via this platform as well.{:RME}</p></div>";
    $ltstNewArtBody = "<div class=\"\" style=\"float:left;padding-left:20px;margin:5px;min-height:170px;line-height: 162%;font-family:Arial !important;font-size:13px !important;\">
                                <h3><a href=\"javascript: showNoticeDetails({:articleID},'Latest News');\" style=\"font-weight:bold;text-decoration:underline;\">LATEST NEWS AND HIGHLIGHTS</a></h3>
                                <ul style=\"list-style-image: url(cmn_images/rho_arrow2.png);list-style-position:outside;padding-left:40px;\">
                                    <li style=\"list-style-image: url(cmn_images/new.gif) !important;\"><a href=\"#\">User friendly Interface.</a></li>
                                    <li>It can run on a variety of computer hardware and network configuration.</li>
                                    <li>It employs a centralized robust database as a repository of information.</li>
                                    <li>Data Import/Export to Excel and Word.</li>
                                    <li>Supports multiple users.</li>
                                </ul>
                                </div>";
    $usefulLnksArtBody = "<div class=\"\" style=\"float:left;padding-left:20px;margin:5px;min-height:170px;line-height: 162%;font-family:Arial !important;font-size:13px !important;\">
                                <h3><a href=\"javascript: showNoticeDetails({:articleID},'Useful Links');\" style=\"font-weight:bold;text-decoration:underline;\">USEFUL QUICK LINKS & RESOURCES</a></h3>
                                <ul style=\"list-style-image: url(cmn_images/rho_arrow2.png);list-style-position:outside;list-style-type:circle;padding-left:40px;\">
                                <li style=\"list-style-image: url(cmn_images/new.gif) !important;\">Database backup and restore from application.</li>
                                <li>Robust application and information security management system.</li>
                                <li>In-built Templates for performing common tasks.</li>
                                <li>Easily Customized and Expandable to meet specific business needs</li>
                                <li>Display reports in Word, Html and PDF formats.</li>
                                </ul>
                        </div>";

    $aboutRho = "
    <div id=\"rho_form\" style=\"min-height:477px;max-width:{:introWdth}px;font-family:Tahoma !important;\"> 
<fieldset style=\"padding:5px;\"><h2 style=\"padding-left:25px;\">
Welcome to the Rhomicom Enterprise Management System Suite of Applications/Solutions!</h2>
<div class=\"rho-postcontent rho-postcontent-0 clearfix\">
<div style=\"float:left;padding:1px;margin-bottom:5px;\"><div style=\"float:left\">
<p>
<a href=\"$about_url\" target=\"_blank\">
    <img style=\"float:right; height:100px; margin-left:10px;\" alt=\"RHOMICOM LOGO\" src=\"cmn_images/rho.png\" >
</a>
<a href=\"$about_url\" target=\"_blank\">
    <img style=\"float:left; height:100px; margin-right:1px;\" alt=\"POSTGRE LOGO\" src=\"cmn_images/investor-icon.png\" />
</a>
Rhomicom has developed an Enterprise Resource Planning (ERP) System which automates
the management of information across an entire organization and facilitates information
flow between all business functions inside the organization. The ERP System is a
suite of the following applications: Organization Manager, Personnel Manager, Internal
Payments (Payroll/Dues & Contributions) Manager, Reports And Processes Manager,
Finance/Accounting Manager, Procurement Manager and Stores And Inventory Manager. This software
is user friendly, can be used by persons with little or no IT background after a
short training and also supports the five (5) characteristics of a Management Information
System which are: Timeliness, Accuracy, Consistency, Completeness and Relevance.
</p></div>
<div class=\"rho_form1\" style=\"float:left;padding-left:10px;padding-bottom:5px;margin:5px;max-width:49%;min-height:170px;background-color:<?php echo $bckcolors1; ?>;\">
<h3>&nbsp;&nbsp;&nbsp;KEY FEATURES OF OUR ERP SYSTEM:</h3>
<ul style=\"list-style-image: url('cmn_images/a_rt.gif');list-style-position:outside;padding-left:40px;\">
<li>User friendly Interface.</li>
<li>It can run on a variety of computer hardware and network configuration.</li>
<li>It employs a centralized robust database as a repository of information.</li>
<li>Data Import/Export to Excel and Word.</li>
<li>Supports multiple users.</li>
</ul>
</div>    
<div class=\"rho_form1\" style=\"float:left;padding-left:10px;padding-bottom:5px;margin:5px;max-width:49%;min-height:170px;background-color:<?php echo $bckcolors1; ?>;\">
<h3>&nbsp;&nbsp;&nbsp;KEY FEATURES OF OUR ERP SYSTEM:</h3>
<ul style=\"list-style-image: url('cmn_images/a_rt.gif');list-style-position:outside;padding-left:40px;\">
<li>Database backup and restore from application.</li>
<li>Robust application and information security management system.</li>
<li>In-built Templates for performing common tasks.</li>
<li>Easily Customized and Expandable to meet specific business needs</li>
<li>Display reports in Word, Html and PDF formats.</li>
</ul>
</div>    
</div>
</div></fieldset></div>";


    //MySQLi Database Functions
    $mysql_db_name = "demo_flexcodesdk";
    $mysql_db_user = "root";
    $mysql_db_pass = "Password1";
    $mysql_db_host = "localhost";
    $mysql_db_port = "3306";
    if (!isset($_SESSION['SUPERADMINCONFIGPSWD'])) {
        $_SESSION['SUPERADMINCONFIGPSWD'] = encrypt($superAdminConfigPswd, $smplTokenWord1);
    }
}

$aboutOrgArtBody = "<div class=\"rho_form1\" style=\"float:left;padding-left:20px;margin:5px;max-width:{:introWdth}px;min-height:170px;line-height: 162%;font-family:Arial !important;font-size:13px !important;\">
                " . $aboutRho . "</div>";
$ssnRoles = $_SESSION['ROLE_SET_IDS'];
$bio_base_path = $flxcde_url;
$time_limit_reg = "15";
$time_limit_ver = "10";


$ModuleName = "";
$pemDestCust = $tmpDest;

//$database_type = "PostgreSQL";
//$database_Version = "10";
//$themeType = "ext-theme-crisp";
//$year = date('Y');
//$fulldte = date('d-M-Y H:i:s');
//$script_folder = "scripts7";
//$lgn_image = "data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==";
//$app_image = "3.png";

function destroySession()
{
    //Get all downloaded files and delete
    global $fldrPrfx;
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $lgn_num;
    global $logNxtLine;
    $dwnldFiles = isset($_SESSION['CUR_RPT_FILES']) ? $_SESSION['CUR_RPT_FILES'] : "";
    $osNm = substr(php_uname(), 0, 7);
    if ($dwnldFiles != "") {
        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho", PHP_EOL . $dwnldFiles . $logNxtLine, FILE_APPEND | LOCK_EX);

        $dwnldFilesArry = explode("|", $dwnldFiles);
        for ($i = 0; $i < count($dwnldFilesArry); $i++) {
            file_put_contents(
                $ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho",
                PHP_EOL . $dwnldFilesArry[$i] . $logNxtLine,
                FILE_APPEND | LOCK_EX
            );
            if ($osNm == "Windows") {
                $cmd = "del " . $dwnldFilesArry[$i];
                execInBckgrndWndws($cmd);
            } else {
                $cmd = "rm " . $dwnldFilesArry[$i];
                execInBckgrndUnix($cmd);
            }
        }
    }
    if ($osNm == "Windows") {
        $cmd = "forfiles -p \"" . $fldrPrfx . $tmpDest . "\" -s -m *.* -d -5 -c \"cmd /c del @path\"";
        execInBckgrndWndws($cmd);
    } else {
        $cmd = "find " . $fldrPrfx . "" . $tmpDest . "* -type f -maxdepth 1 -mmin +30 -name '*.*' -exec rm -f {} \;";
        execInBckgrndUnix($cmd);
        $cmd = "find " . $fldrPrfx . "dwnlds/amcharts_2100/samples/* -type f -maxdepth 1 -mmin +30 -name '*.html' -exec rm -f {} \;";
        execInBckgrndUnix($cmd);
    }
    $_SESSION['UNAME'] = "";
    $_SESSION['USRID'] = -1;
    $_SESSION['LGN_NUM'] = -1;
    $_SESSION['ORG_NAME'] = "";
    $_SESSION['ORG_ID'] = -1;
    $_SESSION['ROOT_FOLDER'] = "";
    $_SESSION['ROLE_SET_IDS'] = "";
    $_SESSION['MUST_CHNG_PWD'] = "0";
    session_destroy();   // destroy session data in storage
    session_unset();     // unset $_SESSION variable for the runtime	
}

function sessionInvalid()
{
    echo "
<div id='rho_form'><H1 style=\"text-align:center; color:red;\">INVALID SESSION!!!</H1>
<p style=\"text-align:center; color:red;\"><span style=\"font-weight:bold;font-style:italic;\">
Sorry, your session has become Invalid. <br/>
Click <a class=\"rho-button\" style=\"text-decoraction:underline;color:blue;\" 
href=\"javascript: window.location='index.php';\">here to login again!</a></span></p></div>";
}

function rhoUrl()
{
    return sprintf(
        "%s://%s%s/",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        (isset($_SERVER['REQUEST_URI']) && (strpos($_SERVER['REQUEST_URI'], ".php") === FALSE)) ? $_SERVER['REQUEST_URI'] : dirname($_SERVER['REQUEST_URI'])
    );
}

function rhoBaseUrl()
{
    return sprintf(
        "%s://%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME']
    );
}

function execSsnUpdtInsSQL($inSQL, $extrMsg = "")
{
    $conn = getConn();
    $result = loc_db_query($conn, $inSQL);
    if (!$result) {
        $txt = "@" . date("d-M-Y H:i:s") . PHP_EOL . "An error occurred. <br/> " . loc_db_result_error($result) . PHP_EOL . $inSQL;
        echo $txt;
        logSsnErrs($txt . PHP_EOL . $inSQL);
    } else {
        /* $extrMsg != '' */
        $txt = $extrMsg . "@" . date("d-M-Y H:i:s");
        logSsnErrs($txt . PHP_EOL . $inSQL);
    }
    loc_db_close($conn);
    return loc_db_affected_rows($result);
}

function logSsnErrs($txt)
{
    global $ftp_base_db_fldr;
    global $lgn_num;
    global $logNxtLine;
    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho", $txt . $logNxtLine, FILE_APPEND | LOCK_EX);
}

function get_CurPlcy_SessnTmOut()
{
    $sqlStr = "SELECT session_timeout FROM 
    sec.sec_security_policies WHERE is_default = 't'";
    $result = executeSQLMain($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return 300;
}

function getDB_Date_timeYYMDHMS()
{
    $sqlStr = "select to_char(now(), 'YYYYMMDDHH24MISS')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function executeSQLMain($selSQL)
{
    $conn = getConn();
    $result = loc_db_query($conn, $selSQL);
    //echo "<br/>SQL--".$selSQL;
    if (!$result) {
        logSessionErrs("An error occurred. <br/> " . loc_db_result_error($result) . "<br/>" . $selSQL);
        echo "An error occurred. <br/> " . loc_db_result_error($result);
    }
    loc_db_close($conn);
    return $result;
    //$conn
}

function execInBackground($cmd, $logfilenm = "")
{
    global $ftp_base_db_fldr;
    $output = "";
    if ($logfilenm == "") {
        $logfilenm = $ftp_base_db_fldr . "/Logs/cmnd_line_logs_rndm_" . getDB_Date_timeYYMDHMS() . ".txt";
    }
    //file_put_contents($logfilenm, PHP_EOL . $cmd . PHP_EOL, FILE_APPEND | LOCK_EX);
    if (substr(php_uname(), 0, 7) == "Windows") {
        pclose(popen("start /B " . $cmd, "r"));
    } else {
        //exec($cmd . " > /dev/null &");
        //var_dump($logfilenm);
        //logSessionErrs($cmd . " > " . $logfilenm . " &");
        $output = exec($cmd . " > " . $logfilenm . " &");
    }
    return $output;
}

function shellExecInBackground($scriptDIR, $cmd, $logfilenm = "")
{
    global $ftp_base_db_fldr;
    $output = "";
    if ($logfilenm == "") {
        $logfilenm = $ftp_base_db_fldr . "/Logs/cmnd_line_logs_rndm_" . getDB_Date_timeYYMDHMS() . ".txt";
    }
    //file_put_contents($logfilenm, PHP_EOL . $cmd . PHP_EOL, FILE_APPEND | LOCK_EX);
    if (substr(php_uname(), 0, 7) == "Windows") {
        $output = execInBckgrndWndws($cmd);
    } else {
        $old_path = getcwd();
        file_put_contents($logfilenm, PHP_EOL . getcwd() . PHP_EOL, FILE_APPEND | LOCK_EX);
        chdir($scriptDIR);
        file_put_contents($logfilenm, PHP_EOL . getcwd() . PHP_EOL, FILE_APPEND | LOCK_EX);
        $output = shell_exec($cmd);
        chdir($old_path);
        file_put_contents($logfilenm, PHP_EOL . getcwd() . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    return $output;
}

function execInBckgrndWndws($cmd)
{
    pclose(popen("start /B " . $cmd, "r"));
    return "Success";
}

function execInBckgrndUnix($cmd)
{
    global $ftp_base_db_fldr;
    global $lgn_num;
    global $logNxtLine;
    //file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho", PHP_EOL . $cmd . $logNxtLine, FILE_APPEND | LOCK_EX);

    exec($cmd . " > " . $ftp_base_db_fldr . "/bin/log_files/" . $lgn_num . "_lgout.rho");
    return "Success";
}

// error handler function
function rhoErrorHandler($errno, $errstr, $errfile, $errline)
{
    global $ftp_base_db_fldr;
    global $lgn_num;
    global $logNxtLine;
    //echo "RHOMICOM ERROR: [$errno] $errstr<br />\n Error occured on line $errline in file $errfile";
    /*file_put_contents(
        $ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho",
        PHP_EOL . "RHOMICOM ERROR: [$errno] $errstr<br />\n This Rhomicom Error happened on line $errline in file $errfile" . $logNxtLine,
        FILE_APPEND | LOCK_EX
    );*/
    $txt = "RHOMICOM ERROR: [$errno] $errstr<br />\n This Rhomicom Error happened on line $errline in file $errfile";
    logSessionErrs($txt);
    $msg = str_replace("ERROR:", "", str_replace("pg_query():", "", $errstr));
    if (strpos($msg, 'CONTEXT:') !== FALSE) {
        $msg = substr($msg, 0, (((int) strpos($msg, 'CONTEXT:')) + 1));
    }
    $errTxt = $msg . " <br>Visit Track User Logins for Error Details!<br>";
    //set_error_handler("rhoErrorHandler2");
    //trigger_error("RHO ERROR:" . $errTxt, E_USER_ERROR);
    $group = isset($_POST['grp']) ? (int) cleanInputData($_POST['grp']) : 0;
    //$cpGet = isset($_GET['cp']) ? (int) cleanInputData($_GET['cp']) : 0;
    error_reporting(0);
    if (!empty($_POST)) {
        echo ("<span style=\"color:red;font-weight:bold;\">" . "RHO ERROR:" . $errTxt . "</span>");
    }
    throw new RuntimeException("");
    //<br />\n This Rhomicom Error happened on line $errline in file $errfile
    /* if (!(error_reporting() & $errno)) {
      // This error code is not included in error_reporting, so let it fall
      // through to the standard PHP error handler
      return false;
      }

      switch ($errno) {
      case E_USER_ERROR:
      echo "<b>RHOMICOM ERROR</b> [$errno] $errstr<br />\n";
      echo "  Fatal error on line $errline in file $errfile";
      echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
      echo "Aborting...<br />\n";
      exit(1);
      break;

      case E_USER_WARNING:
      echo "<b>RHOMICOM WARNING</b> [$errno] $errstr<br />\n";
      break;

      case E_USER_NOTICE:
      echo "<b>RHOMICOM NOTICE</b> [$errno] $errstr<br />\n";
      break;

      default:
      echo "RHOMICOM UNKNOWN: [$errno] $errstr<br />\n";
      }

      // Don't execute PHP internal error handler */
    //return true;
}

function rhoErrorHandler2($errno, $errstr, $errfile, $errline)
{
    // This error code is not included in error_reporting, so let it fall
    // through to the standard PHP error handler
    $txt = "RHOMICOM ERROR: [$errno] $errstr<br />\n This Rhomicom Error happened on line $errline in file $errfile";
    logSessionErrs($txt);
    return false;
}

function rhoErrorHandler3($errno, $errstr, $errfile, $errline)
{
    //No echoing of error
    $txt = "RHOMICOM ERROR: [$errno] $errstr<br />\n This Rhomicom Error happened on line $errline in file $errfile";
    logSessionErrs($txt);
    return true;
}
function getConn()
{
    global $database;
    global $db_pwd;
    global $db_usr;
    global $port;
    global $host;
    global $currentPgConn;
    $conn_string = "host=$host port=$port dbname=$database user=$db_usr password=$db_pwd";
    try {
        $conn = pg_connect($conn_string);
        $currentPgConn = $conn;
        return $conn;
    } catch (\Exception $e) {
        logSessionErrs($e->getMessage());
        return FALSE;
    }
}

function loc_db_escape_string($str)
{
    /*global $currentPgConn;
    if (!isset($currentPgConn)) {
        $currentPgConn = getConn();
    }
    if (isset($currentPgConn)) {
        return pg_escape_string($currentPgConn, $str);
    } else {
        return pg_escape_string($str);
    }*/
    return pg_escape_string($str);
}

function loc_db_affected_rows($result)
{
    return pg_affected_rows($result);
}

function loc_db_query($conn, $inSQL)
{
    try {
        return pg_query($conn, $inSQL);
    } catch (Exception $e) {
        $txt = "@" . date("d-M-Y H:i:s") . PHP_EOL . "An error occurred." . PHP_EOL . $inSQL;
        logSessionErrs($txt);
        throw new Exception($e->getMessage());
        //return NULL;
    }
}

function loc_db_close($conn)
{
    pg_close($conn);
}

function loc_db_fetch_array($result)
{
    return pg_fetch_array($result);
}

function loc_db_num_rows($result)
{
    return pg_num_rows($result);
}

function loc_db_result_error($result)
{
    return pg_result_error($result);
}

function loc_db_field_name($result, $c)
{
    return pg_field_name($result, $c);
}

function loc_db_num_fields($result)
{
    return pg_num_fields($result);
}

function getMySQLiConn()
{
    global $mysql_db_name;
    global $mysql_db_pass;
    global $mysql_db_user;
    global $mysql_db_host;
    global $mysql_db_port;
    $mysql_conn = mysqli_connect($mysql_db_host, $mysql_db_user, $mysql_db_pass, $mysql_db_name, $mysql_db_port);
    if (!$mysql_conn) {
        echo mysqli_errno($mysql_conn) . ": " . mysqli_error($mysql_conn) . "\n";
        die("Connection for user $mysql_db_user refused!");
    }
    return $mysql_conn;
}

function mysq_db_escape_string($str)
{
    $mysql_conn = getMySQLiConn();
    return mysqli_escape_string($mysql_conn, $str);
}

function mysq_db_affected_rows($mysql_conn)
{
    return mysqli_affected_rows($mysql_conn);
}

function mysq_db_query($conn, $inSQL)
{
    return mysqli_query($conn, $inSQL);
}

function mysq_db_close($conn)
{
    mysqli_close($conn);
}

function mysq_db_fetch_array($result)
{
    return mysqli_fetch_array($result, MYSQLI_NUM);
}

function mysq_db_num_rows($result)
{
    return mysqli_num_rows($result);
}

function mysq_db_num_fields($result)
{
    return mysqli_num_fields($result);
}

function executeMySQLQry($selSQL, $extrMsg = "")
{
    global $ftp_base_db_fldr;
    global $lgn_num;
    global $logNxtLine;
    $conn = getMySQLiConn();
    $result = mysq_db_query($conn, $selSQL);
    //echo "<br/>SQL--".$selSQL;
    if (!$result) {
        $txt = "@" . date("d-M-Y H:i:s") . PHP_EOL . "An error occurred. <br/> " . PHP_EOL . $selSQL;
        echo $txt;
        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho", $txt . PHP_EOL . $selSQL . $logNxtLine, FILE_APPEND | LOCK_EX);
    } else if ($extrMsg != '') {
        $txt = $extrMsg . "@" . date("d-M-Y H:i:s");
        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho", $txt . PHP_EOL . $selSQL . $logNxtLine, FILE_APPEND | LOCK_EX);
    }
    mysq_db_close($conn);
    return $result;
    //$conn
}

function execMySQLiUpdtInsSQL($inSQL, $extrMsg = '', $src = 0)
{
    global $ftp_base_db_fldr;
    global $lgn_num;
    global $logNxtLine;
    $rsltCntr = 0;
    $conn = getMySQLiConn();
    $result = mysq_db_query($conn, $inSQL);
    if (!$result) {
        $txt = "@" . date("d-M-Y H:i:s") . PHP_EOL . "An error occurred. <br/> " . PHP_EOL . $inSQL;
        echo $txt;
        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho", $txt . PHP_EOL . $inSQL . $logNxtLine, FILE_APPEND | LOCK_EX);
    } else if ($src <= 0) {
        /* $extrMsg != '' */
        $rsltCntr = mysq_db_affected_rows($conn);
        $txt = $extrMsg . "@" . date("d-M-Y H:i:s");
        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho", $txt . PHP_EOL . $inSQL . $logNxtLine, FILE_APPEND | LOCK_EX);
    }
    mysq_db_close($conn);
    if ($src == 0) {
        if (trim(strtoupper(substr($inSQL, 0, 11))) == 'DELETE FROM') {
            storeAdtTrailInfo($inSQL, 1, $extrMsg);
        } else if (trim(strtoupper(substr($inSQL, 0, 6))) == 'UPDATE') {
            storeAdtTrailInfo($inSQL, 0, $extrMsg);
        }
    }
    return $rsltCntr;
}

function decrypt($inpt, $key)
{
    //try {
    $fnl_str = "";
    $charset1 = array(
        "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K",
        "L",
        "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X",
        "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l",
        "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x",
        "y", "z"
    );
    $charset2 = str_split(getNewKey($key), 1);
    /* $charset2 = array("e", "q", "0", "P", "3", "i", "D", "O", "V", "8", "E", "6",
      "B", "Z", "A", "W", "5", "g", "G", "F", "H", "u", "t", "s",
      "C", "K", "d", "p", "r", "w", "z", "x", "a", "c", "1", "m",
      "I", "f", "Q", "L", "v", "Y", "j", "S", "R", "o", "J", "4",
      "9", "h", "7", "M", "b", "X", "k", "N", "l", "n", "2", "y",
      "T", "U"); */

    $wldChars = array(
        "`", "¬", "!", "\"", "£", "$", "%", "^", "&", "*", "(",
        ")",
        "-", "_", "=", "+", "{", "[", "]", "}", ":", ";", "@", "'",
        "#", "~", "/", "?", ">", ".", "<", ",", "\\", "|", " "
    );
    $wldChrLen = count($wldChars);
    //$charset2Len = count($charset1);
    //$charset2Len = count($charset2);

    $inptLen = strlen($inpt);
    for ($i = $inptLen - 1; $i >= 0; $i--) {
        $tst_str = substr($inpt, $i, 1);
        if ($tst_str == "_") {
            continue;
        }
        $j = findCharIndx($tst_str, $charset2);
        if ($j == -1) {
            $fnl_str .= $tst_str;
        } else {
            if ($i < $inptLen - 1) {
                if (substr($inpt, $i + 1, 1) == "_" && $j < $wldChrLen) {
                    $fnl_str .= $wldChars[$j];
                } else {
                    $fnl_str .= $charset1[$j];
                }
            } else {
                $fnl_str .= $charset1[$j];
            }
        }
    }
    $nwStr1 = substr($fnl_str, 0, 4);
    $nwStr2 = substr($fnl_str, 4, 4);
    $stringLn = (int) $nwStr2 - (int) $nwStr1;
    $nwStr3 = substr($fnl_str, 8, $stringLn);
    return $nwStr3;
    //return $fnl_str;
    /* } catch (\Exception $e) {
      return $inpt;
      } */
}

function getNewKey($key)
{
    $charset1 = str_split($key, 1);
    $charset2 = array(
        "e", "q", "0", "P", "3", "i", "D", "O", "V", "8", "E", "6",
        "B", "Z", "A", "W", "5", "g", "G", "F", "H", "u", "t", "s",
        "C", "K", "d", "p", "r", "w", "z", "x", "a", "c", "1", "m",
        "I", "f", "Q", "L", "v", "Y", "j", "S", "R", "o", "J", "4",
        "9", "h", "7", "M", "b", "X", "k", "N", "l", "n", "2", "y",
        "T", "U"
    );
    $wldChars = array(
        "`", "¬", "!", "\"", "£", "$", "%", "^", "&", "*", "(", ")",
        "-", "_", "=", "+", "{", "[", "]", "}", ":", ";", "@", "'",
        "#", "~", "/", "?", ">", ".", "<", ",", "\\", "|", " "
    );
    $keyLength = count($charset1);
    $newKey = "";
    for ($i = $keyLength - 1; $i >= 0; $i--) {
        if (findCharIndx($charset1[$i], $wldChars) > -1) {
            continue;
        }
        if (strpos($newKey, $charset1[$i]) === FALSE) {
            $newKey .= $charset1[$i];
        }
        if (strlen($newKey) >= 62) {
            break;
        }
    }

    if (strlen($newKey) < 62) {
        $keyLength = count($charset2);
        for ($i = $keyLength - 1; $i >= 0; $i--) {
            if (strpos($newKey, $charset2[$i]) === FALSE) {
                $newKey .= $charset2[$i];
            }
            if (strlen($newKey) >= 62) {
                break;
            }
        }
    }
    return $newKey;
}

function getRandomPswd()
{
    $charset1 = array(
        "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L",
        "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X",
        "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l",
        "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x",
        "y", "z"
    );
    $charset2 = array(
        "e", "q", "0", "P", "3", "i", "D", "O", "V", "8", "E", "6",
        "B", "Z", "A", "W", "5", "g", "G", "F", "H", "u", "t", "s",
        "C", "K", "d", "p", "r", "w", "z", "x", "a", "c", "1", "m",
        "I", "f", "Q", "L", "v", "Y", "j", "S", "R", "o", "J", "4",
        "9", "h", "7", "M", "b", "X", "k", "N", "l", "n", "2", "y",
        "T", "U"
    );
    $wldChars = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $pswd = "";

    $idx = -1;
    for ($i = 1; $i < 10; $i++) {
        if ($i == 1 || $i == 4 || $i == 7) {
            $idx = rand(0, count($charset1) - 1);
            $pswd .= $charset1[$idx];
        } else if ($i == 2 || $i == 5) {
            $idx = rand(0, count($charset2) - 1);
            $pswd .= $charset2[$idx];
        } else if ($i == 3 || $i == 6) {
            $idx = rand(0, count($wldChars) - 1);
            $pswd .= $wldChars[$idx];
        }
    }
    return $pswd;
}

function getRandomTxt($numChars)
{
    $charset1 = array(
        "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L",
        "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X",
        "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l",
        "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x",
        "y", "z"
    );
    $charset2 = array(
        "e", "q", "0", "P", "3", "i", "D", "O", "V", "8", "E", "6",
        "B", "Z", "A", "W", "5", "g", "G", "F", "H", "u", "t", "s",
        "C", "K", "d", "p", "r", "w", "z", "x", "a", "c", "1", "m",
        "I", "f", "Q", "L", "v", "Y", "j", "S", "R", "o", "J", "4",
        "9", "h", "7", "M", "b", "X", "k", "N", "l", "n", "2", "y",
        "T", "U"
    );
    $wldChars = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $pswd = "";

    $idx = -1;
    $setNum = 1;
    for ($i = 1; $i <= $numChars; $i++) {
        $setNum = rand(0, 4);
        if ($setNum == 1) {
            $idx = rand(0, count($charset1) - 1);
            $pswd .= $charset1[$idx];
        } else if ($setNum == 2) {
            $idx = rand(0, count($charset2) - 1);
            $pswd .= $charset2[$idx];
        } else if ($setNum == 3) {
            $idx = rand(0, count($wldChars) - 1);
            $pswd .= $wldChars[$idx];
        } else {
            $idx = rand(0, count($charset2) - 1);
            $pswd .= $charset2[$idx];
        }
    }
    return $pswd;
}

function getRandomNum($numLow, $numHigh)
{
    return rand($numLow, $numHigh);
}

function findArryIdx($arry1, $srch)
{
    for ($i = 0; $i < count($arry1); $i++) {
        if ($arry1[$i] == $srch) {
            return $i;
        }
    }
    return -1;
}

function findCharIndx($inp_char, $inpArry)
{
    $arrlength = count($inpArry);
    for ($i = 0; $i < $arrlength; $i++) {
        if ($inpArry[$i] == $inp_char) {
            return $i;
        }
    }
    return -1;
}

function encrypt1($inpt, $key)
{
    try {
        $numChars = rand(1000, 5999);
        $numChars1 = rand(6000, 9000);
        $encrptdLen = str_pad(strlen($inpt) + $numChars, 4, "0", STR_PAD_LEFT);
        $encrptdLen1 = str_pad(strlen($inpt) + $numChars1, 4, "0", STR_PAD_LEFT);

        /* $numChars2 = rand(5, 9);
          $nwTxt = getRandomTxt($numChars);
          $nwTxt1 = getRandomTxt($numChars1);
          $nwTxt2 = getRandomTxt($numChars2);
          $expDate = str_replace(" ", "", getDB_Date_time()); */

        $inpt = $numChars . $encrptdLen . $inpt . $numChars1 . $encrptdLen1;
        $fnl_str = "";
        $charset1 = array(
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L",
            "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X",
            "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l",
            "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x",
            "y", "z"
        );
        $charset2 = str_split(getNewKey($key), 1);
        //exp
        $wldChars = array(
            "`", "¬", "!", "\"", "£", "$", "%", "^", "&", "*", "(",
            ")",
            "-", "_", "=", "+", "{", "[", "]", "}", ":", ";", "@", "'",
            "#", "~", "/", "?", ">", ".", "<", ",", "\\", "|", " "
        );
        for ($i = strlen($inpt) - 1; $i >= 0; $i--) {
            $tst_str = substr($inpt, $i, 1);
            $j = findCharIndx($tst_str, $charset1);
            if ($j == -1) {
                $k = findCharIndx($tst_str, $wldChars);
                if ($k == -1) {
                    $fnl_str .= $tst_str;
                } else {
                    $fnl_str .= $charset2[$k] . "_";
                }
            } else {
                $fnl_str .= $charset2[$j];
            }
        }
        return $fnl_str;
    } catch (Exception $e) {
        return $inpt;
    }
}

function encrypt($inpt, $key)
{
    try {
        $numChars = 5433; //rand(1000, 5999);
        $numChars1 = 8279; //(6000, 9000);
        $encrptdLen = str_pad(strlen($inpt) + $numChars, 4, "0", STR_PAD_LEFT);
        $encrptdLen1 = str_pad(strlen($inpt) + $numChars1, 4, "0", STR_PAD_LEFT);

        /* $numChars2 = rand(5, 9);
          $nwTxt = getRandomTxt($numChars);
          $nwTxt1 = getRandomTxt($numChars1);
          $nwTxt2 = getRandomTxt($numChars2);
          $expDate = str_replace(" ", "", getDB_Date_time()); */

        $inpt = $numChars . $encrptdLen . $inpt . $numChars1 . $encrptdLen1;
        $fnl_str = "";
        $charset1 = array(
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L",
            "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X",
            "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l",
            "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x",
            "y", "z"
        );
        $charset2 = str_split(getNewKey($key), 1);
        //exp
        $wldChars = array(
            "`", "¬", "!", "\"", "£", "$", "%", "^", "&", "*", "(",
            ")",
            "-", "_", "=", "+", "{", "[", "]", "}", ":", ";", "@", "'",
            "#", "~", "/", "?", ">", ".", "<", ",", "\\", "|", " "
        );
        for ($i = strlen($inpt) - 1; $i >= 0; $i--) {
            $tst_str = substr($inpt, $i, 1);
            $j = findCharIndx($tst_str, $charset1);
            if ($j == -1) {
                $k = findCharIndx($tst_str, $wldChars);
                if ($k == -1) {
                    $fnl_str .= $tst_str;
                } else {
                    $fnl_str .= $charset2[$k] . "_";
                }
            } else {
                $fnl_str .= $charset2[$j];
            }
        }
        return $fnl_str;
    } catch (Exception $e) {
        return $inpt;
    }
}

function encrypt2($inpt, $key)
{
    try {
        $numChars = 123456;
        $numChars1 = 789012;
        $encrptdLen = str_pad(strlen($inpt) + $numChars, 6, "0", STR_PAD_LEFT);
        $encrptdLen1 = str_pad(strlen($inpt) + $numChars1, 6, "0", STR_PAD_LEFT);

        $inpt = $numChars . $encrptdLen . $inpt . $numChars1 . $encrptdLen1;
        $fnl_str = "";
        $charset1 = array(
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L",
            "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X",
            "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l",
            "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x",
            "y", "z"
        );
        $charset2 = str_split(getNewKey($key), 1);
        $wldChars = array(
            "`", "¬", "!", "\"", "£", "$", "%", "^", "&", "*", "(",
            ")",
            "-", "_", "=", "+", "{", "[", "]", "}", ":", ";", "@", "'",
            "#", "~", "/", "?", ">", ".", "<", ",", "\\", "|", " "
        );
        for ($i = strlen($inpt) - 1; $i >= 0; $i--) {
            $tst_str = substr($inpt, $i, 1);
            $j = findCharIndx($tst_str, $charset1);
            if ($j == -1) {
                $k = findCharIndx($tst_str, $wldChars);
                if ($k == -1) {
                    $fnl_str .= $tst_str;
                } else {
                    $fnl_str .= $charset2[$k] . "_";
                }
            } else {
                $fnl_str .= $charset2[$j];
            }
        }
        return $fnl_str;
    } catch (Exception $e) {
        return $inpt;
    }
}

function cleanInputData($data)
{
    return $data;
}

function cleanInputData1($data)
{
    return trim(str_replace("{:;:;}", "|", str_replace("{-;-;}", "~", $data)));
}

function cleanInputData2($data)
{
    return trim(str_replace("{!;!;}", "#", $data));
}

function cleanOutputData($data)
{
    return trim(htmlentities(strip_tags($data)));
}

function is_multi($a)
{
    foreach ($a as $v) {
        if (is_array($v)) {
            return true;
        }
    }
    return false;
}

function logSessionErrs($txt)
{
    global $ftp_base_db_fldr;
    global $lgn_num;
    global $logNxtLine;
    $_SESSION['ERROR_MSG'] = $txt;
    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num.rho", $txt . $logNxtLine, FILE_APPEND | LOCK_EX);
}
