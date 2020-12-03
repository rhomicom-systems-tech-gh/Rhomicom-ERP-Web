<?php
/* $dte1=DateTime::createFromFormat('d-M-Y H:i:s','01-Jan-2019 12:14:56');
  echo DateTime::createFromFormat('d-M-Y H:i:s', $dte1->format('d-M-Y')." 00:00:00")->modify('+1 day')->format('d-M-Y H:i:s')."<br/>";
  echo DateTime::createFromFormat('d-M-Y H:i:s', $dte1->format('d-M-Y')." 00:00:00")->modify('+1 month')->format('d-M-Y H:i:s')."<br/>";
  echo DateTime::createFromFormat('d-M-Y H:i:s', $dte1->format('d-M-Y H:i:s'))->modify('+1 hour')->format('d-M-Y H:i:s')."<br/>"; */
$qstr = "";
$dsply = "";
$actyp = "";
$PKeyID = -1;
$vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "0";
$usrID = $_SESSION['USRID'];
$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$error = "";
$searchAll = true;

$srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
$srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'All';
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Published";
$isMaster = isset($_POST['isMaster']) ? cleanInputData($_POST['isMaster']) : "0";
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
    $srchFor = str_replace("%%", "%", $srchFor);
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
$vPsblValID1 = getEnbldLkPssblValID("Configured System Type%", getLovID("All Other General Setups"));
$configuredSysTyp = getPssblValDesc($vPsblValID1);
if ($configuredSysTyp == "") {
    $configuredSysTyp = 'Enterprise Resource Planning';
}

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$cntrsRslt = getHmPgIndicators($prsnid);
$inbxCntr = $cntrsRslt[0];
$forumCntr = $cntrsRslt[1];
$artclCntr = $cntrsRslt[2];
$mdlsCntr = $cntrsRslt[3];
$nwFileName = $myImgFileName;
$prmSnsRstl = getHomePgPrmssns($prsnid);
$lnkdFirmID = $prmSnsRstl[0];
$canViewSelfsrvc = ($prmSnsRstl[1] >= 1) ? true : false;
$canViewEvote = ($prmSnsRstl[2] >= 1) ? true : false;
$canViewElearn = ($prmSnsRstl[3] >= 1) ? true : false;
$canViewAcntng = ($prmSnsRstl[4] >= 1) ? true : false;
$canViewPrsn = ($prmSnsRstl[5] >= 1) ? true : false;
$canViewIntrnlPay = ($prmSnsRstl[6] >= 1) ? true : false;
$canViewSales = ($prmSnsRstl[7] >= 1) ? true : false;
$canViewVsts = ($prmSnsRstl[8] >= 1) ? true : false;
$canViewEvnts = ($prmSnsRstl[9] >= 1) ? true : false;
$canViewHotel = ($prmSnsRstl[10] >= 1) ? true : false;
$canViewClnc = ($prmSnsRstl[11] >= 1) ? true : false;
$canViewBnkng = ($prmSnsRstl[12] >= 1) ? true : false;
$canViewPrfmnc = ($prmSnsRstl[13] >= 1) ? true : false;
$canViewProjs = ($prmSnsRstl[14] >= 1) ? true : false;
$canViewVMS = ($prmSnsRstl[15] >= 1) ? true : false;
$canViewAgnt = false; // ($prmSnsRstl[16] >= 1) ? true : 
$canViewATrckr = false; //($prmSnsRstl[17] >= 1) ? true : 
$canViewSysAdmin = ($prmSnsRstl[18] >= 1) ? true : false;
$canViewOrgStp = ($prmSnsRstl[19] >= 1) ? true : false;
$canViewLov = ($prmSnsRstl[20] >= 1) ? true : false;
$canViewWkf = ($prmSnsRstl[21] >= 1) ? true : false;
$canViewArtclAdmn = ($prmSnsRstl[22] >= 1) ? true : false;
$canViewRpts = ($prmSnsRstl[23] >= 1) ? true : false;
$acaPrfmncType = $prmSnsRstl[24];
$canViewHlpDsk = ($prmSnsRstl[27] >= 1) ? true : false;
$createMainAppLnk = (strtoupper($prmSnsRstl[25]) == "YES") ? true : false;
$isSelfRgstrAllwd = strtoupper($prmSnsRstl[26]);
$startOfDayDate = substr(getStartOfDayDMYHMS(), 0, 11);

$menuItems1 = array(
    "New User?", "Public Announcements", "Medical Appointments",
    "Academics Portal", "All Apps", "Dashboard", "Standard Reports"
);
$menuItems2 = array(
    "My Profile", "Announcements", "Medical Appointments",
    "Academics Portal", "All Apps"
);
$menuImages = array(
    "dashboard220.png", "invoice1.png", "settings.png", "resume.png", "supervisor.jpg", "calendar2.png",
    "reports.png", "resume.png", "resume.png", "education.png", "report-icon-png.png"
);
require 'srvr_self/base_files/header1.php';
//rgba(135, 206, 235, 0.35)
$lighterColor3 = rhoHex2Rgba($bckcolorOnly1, 0.65);
$lighterColor4 = rhoHex2Rgba($bckcolorOnly1, 0.05);
?>
<style>
    body {
        font-display: fallback !important;
    }

    .bootstrap4-dialog-button.btn-block {
        display: inline-block;
        width: auto !important;
    }

    .modal-content {
        width: 105% !important;
    }

    .basic_person_lg {
        border: 1px solid #d0d0d0 !important;
        border-radius: 4px;
        padding: 7px 1px 7px 1px !important;
        margin: 0 0 5px 0 !important;
        text-align: center;
        font-weight: bold;
        font-size: 16px !important;
        color: <?php echo $bckcolorOnly1; ?> !important;
        background-color: none !important;
        background-image: linear-gradient(#fefefe, <?php echo $lighterColor4; ?>) !important;
        /*
        background-image: linear-gradient(<?php echo $bckcolorOnly1; ?>, <?php echo $lighterColor3; ?>) !important;
        border: 1px solid #336578;
        background-color: #003245;
        background-image: -moz-linear-gradient(top, #336578, #003245);
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#336578), to(#003245));
        background-image: -webkit-linear-gradient(top, #336578, #003245);
        background-image: -o-linear-gradient(top, #336578, #003245);
        background-image: linear-gradient(to bottom, #336578, #003245);
        filter: progid:dximagetransform.microsoft.gradient(startColorstr='#336578', endColorstr='#003245', GradientType=0);
        <?php echo $breadCrmbBckclr; ?>
        */
    }

    tr.highlight {
        background-color: #E0FFFF !important;
    }

    tr.selected {
        background-color: #00FFFF !important;
    }

    .colmd3special1,
    .colmd3special2 {
        position: relative;
        min-height: 1px;
        max-width: 99.9% !important;
        padding-right: 15px !important;
        padding-left: 15px !important;
    }

    .modulesButton {
        max-width: 98%;
        padding: 5px;
        min-height: 120px;
        font-size: 16px;
        /*font-family: Tahoma,"Helvetica Neue",Helvetica,Arial,sans-serif;*/
        font-weight: 400;
        color: #000;
        margin-bottom: 5px;
        margin-top: 5px;
        background: #f9f9f9;
        border: 1px solid;
        border-color: #ddd;
        border-radius: 10px;
        box-sizing: content-box;
        background-image: -webkit-linear-gradient(top, #fefefe, <?php echo $lighterColor4; ?>);
        background-image: -moz-linear-gradient(top, #fefefe, <?php echo $lighterColor4; ?>);
        background-image: -ms-linear-gradient(top, #fefefe, <?php echo $lighterColor4; ?>);
        background-image: -o-linear-gradient(top, #fefefe, <?php echo $lighterColor4; ?>);
        background-image: linear-gradient(bottom, #fefefe, <?php echo $lighterColor4; ?>);
    }

    .modulesButton:active,
    .modulesButton:hover,
    .modulesButton:focus {
        background: #fafafa;
        border: 1px solid;
        border-color: #d0d0d0;
        -webkit-box-shadow: inset 0 0 1px rgba(0, 0, 0, 0.15);
        box-shadow: inset 0 0 1px rgba(0, 0, 0, 0.15)
    }

    label:not(.form-check-label):not(.custom-file-label) {
        font-weight: 600 !important;
        /*font-family: georgia;*/
        font-size: 13px !important;
    }

    .direct-chat-text {
        background: rgba(255, 255, 255, 0.95) !important;
        color: #000000;
        font-family: Tahoma, georgia, Calibri, Helvetica, "Helvetica Neue", "Open Sans", Arial, sans-serif !important;
    }

    .direct-chat-infos {
        font-size: 11px !important;
        margin-bottom: 0px !important;
        max-height: 20px !important;
        color: #697582;
        font-weight: normal !important;
        font-family: georgia, Calibri, Helvetica, "Helvetica Neue", "Open Sans", Arial, sans-serif !important;
        font-style: italic;
    }

    .card-primary.card-outline {
        border-top: 3px solid <?php echo $bckcolorOnly1; ?>;
    }

    .lovtd {
        padding: 1px 5px 1px 5px !important;
        line-height: 1.42857143 !important;
        vertical-align: middle !important;
    }

    table.gridtable td.likeheader {
        padding: 8px;
        border-width: 1px;
        border-style: solid;
        border-color: #ccc;
        background-color: #efefef !important;
    }

    table.gridtable {
        font-family: verdana, arial, sans-serif;
        font-size: 11px;
        color: #333333;
        border-width: 1px;
        border-color: #ccc;
        border-collapse: collapse;
        border-spacing: 0;
    }

    table.gridtable th {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #ccc;
        background-color: #efefef;
    }

    .caption {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #ccc;
        background-color: #efefef;
        margin-bottom: 1px;
        font-weight: bold;
        font-size: 16px;
        font-style: italic;
        color: #333333;
    }

    table.gridtable td {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #ccc;
        background-color: #ffffff;
    }

    .row,
    hr {
        min-width: 100% !important;
    }

    .row {
        margin-bottom: 1px !important;
    }

    .nav-tabs .nav-link,
    .nav-tabs .nav-link {
        border-left: 1px solid #e9ecef !important;
        border-right: 1px solid #e9ecef !important;
        border-top: 1px solid #dee2e6 !important;
        border-bottom: none !important;
    }

    .card.card-outline-tabs .card-header a:hover {
        border-top: 3px solid <?php echo $lighterColor3; ?> !important;
    }

    .card.card-outline-tabs>.card-header a.active {
        border-top: 3px solid <?php echo $bckcolorOnly1; ?> !important;
        border-bottom: none !important;
    }

    .bootbox {
        z-index: 10099 !important;
    }

    [class*=sidebar-dark-] {
        background-color: white;
    }

    [class*=sidebar-dark] .brand-link {
        padding: 13px 1px 19px 1px !important;
        border-bottom: 1px solid #ddd !important;
    }

    .brand-text {
        color: #343a40 !important;
        max-width: 190px !important;
    }

    :not(.layout-fixed) {
        .main-sidebar {
            height: inherit;
            min-height: 50% !important;
            position: absolute;
            top: 0;
        }
    }

    .hideNotice {
        display: none !important;
    }

    .showNotice {
        display: block !important;
    }

    .sidebar {
        padding: 0px 0px 10px 0px !important;
        margin: 5px !important;
        border-radius: 5px;
        <?php echo $forecolors; ?><?php echo $bckcolors_home; ?>
    }

    .rho-card-body {
        max-height: 130px !important;
        height: 130px !important;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 0px !important;
        margin-bottom: 5px !important;
    }

    .rho-card-body2 {
        max-height: 145px !important;
        height: 145px !important;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 2px !important;
        margin-bottom: 5px !important;
    }

    .card-body {
        padding: 15px 15px 15px 15px !important;
    }

    .panel-footer {
        padding: 1px 15px 1px 15px !important;
    }

    .rho-gradient-1 {
        background-image: linear-gradient(red, #800000) !important;
    }

    /*, #3693ce to bottom right, */
    .rho-gradient-2 {
        background-image: linear-gradient(#F2C900, #ff8c00) !important;
    }

    .rho-gradient-3 {
        background-image: linear-gradient(#2ac363, #006400) !important;
    }

    .rho-gradient-4 {
        background-image: linear-gradient(#0048BA, #003245) !important;
    }

    .rounded .card {
        border-radius: .75rem;
    }

    .progress-banner:hover {
        background-position: right top;
    }

    /* Let's get this party started */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        -webkit-border-radius: 10px;
        border-radius: 10px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        -webkit-border-radius: 10px;
        border-radius: 10px;
        background: rgba(221, 221, 221, 0.8);
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
    }

    ::-webkit-scrollbar-thumb:window-inactive {
        background: rgba(221, 221, 221, 0.4);
    }

    .wordEllipsis1 {
        white-space: nowrap;
        white-space: -moz-pre-wrap;
        /* Firefox */
        white-space: -pre-wrap;
        /* Opera <7 */
        white-space: -o-pre-wrap;
        /* Opera 7 */
        word-wrap: break-word;
        /* IE */
        width: 190px;
        max-width: 190px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .wordEllipsis2 {
        white-space: nowrap;
        white-space: -moz-pre-wrap;
        /* Firefox */
        white-space: -pre-wrap;
        /* Opera <7 */
        white-space: -o-pre-wrap;
        /* Opera 7 */
        word-wrap: break-word;
        /* IE */
        width: calc(75%);
        max-width: calc(75%);
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .wordwrap3 {
        white-space: pre-wrap;
        /* CSS3 */
        white-space: -moz-pre-wrap;
        /* Firefox */
        white-space: -pre-wrap;
        /* Opera <7 */
        white-space: -o-pre-wrap;
        /* Opera 7 */
        word-wrap: break-word;
        /* IE */
        vertical-align: middle;
    }

    .user-panel {
        padding: 12px 0px 11px 0px !important;
        margin: -1px 0px 0px 0px !important;
    }

    .rqrdFld {
        background-color: #ffff99 !important;
    }

    div.input-group-text {
        min-width: 39px !important;
        width: 39px !important;
        border-left: 1px solid #ddd !important;
    }

    .rho-error {
        border: 2px solid #a94442;
        /*-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);*/
        -moz-box-shadow: 0 4px 8px 0 rgba(255, 0, 0, 0.32), 0 6px 20px 0 rgba(255, 0, 0, 0.29);
        -webkit-box-shadow: 0 4px 8px 0 rgba(255, 0, 0, 0.32), 0 6px 20px 0 rgba(255, 0, 0, 0.29);
        box-shadow: 0 4px 8px 0 rgba(255, 0, 0, 0.32), 0 6px 20px 0 rgba(255, 0, 0, 0.29);
    }

    #footer {
        padding: 7px !important;
    }

    /* Start by setting display:none to make this hidden.
       Then we position it in relation to the viewport window
       with position:fixed. Width, height, top and left speak
       speak for themselves. Background we set to 80% white with
       our animation centered, and no-repeating */
    .modalLdng {
        display: none;
        position: fixed;
        z-index: 9999 !important;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(5, 5, 5, .4) url('images/ajax-loader7.gif') 50% 50% no-repeat;
        background-size: 75px 75px;
    }

    /* When the body has the loading class, we turn
       the scrollbar off with overflow:hidden */
    body.mdlloading {
        overflow: auto;
    }

    body.mdlloadingDiag {
        overflow: auto;
    }

    body.mdlloadingDiag .modalLdng {
        display: block;
    }

    /* Anytime the body has the loading class, our
       modal element will be visible */
    body.mdlloading .modalLdng {
        display: block;
    }

    body {
        /*line-height:1.5;
        font-family:"Source Sans Pro",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";*/
        margin: 0;
        font-size: 1rem;
        font-weight: 400;
        color: #343a40;
        text-align: left;
        background-color: #fff;
        min-height: 100vh !important;
        line-height: 1.0 !important;
        font-family: "Open Sans", Montserrat, "Playfair Display", Roboto, "Proxima Nova", Poppins, Tahoma, "Helvetica Neue", Helvetica, Arial, sans-serif !important;
        overflow-x: hidden;
        overflow-y: auto;
    }

    datalist {
        display: none;
    }

    #allmodules {
        background-color: white !important;
        min-height: 87vh !important;
    }

    .handCursor {
        cursor: pointer;
        cursor: hand;
    }

    .rhopagination {
        padding: 6px 15px 5px 15px !important;
    }

    .rhopagination:hover,
    .rhoclickable:hover {
        z-index: 2;
        color: whitesmoke;
        background-color: <?php echo $lighterColor3; ?>;
        border-color: #ddd;
    }

    .pagination>li:first-child>a,
    .pagination>li:first-child>span {
        margin-left: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }

    .pagination>li:last-child>a,
    .pagination>li:last-child>span {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }

    .pagination>li>a,
    .pagination>li>span {
        position: relative;
        float: left;
        padding: 6px 12px;
        margin-left: -1px;
        line-height: 1.42857143;
        color: #337ab7;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
    }

    /*.direct-chat-messages {
        -webkit-transform: translate(0,0);
        transform: translate(0,0);
        height: 250px;
        overflow: auto;
        padding: 2px !important;
    }*/
    .rho-card {
        padding: 25px 5px !important;
        width: 100% !important;
        max-width: 100% !important;
        max-height: 200px !important;
        min-height: 155px !important;
        border-radius: 5px;
    }

    .rho-card a:hover {
        font-size: 17px;
    }

    .text-white {
        width: 100% !important;
        max-width: 100% !important;
    }

    .cardBtn i {
        /*width: 30% !important; 
        max-width: 30% !important; */
    }

    .cardBtn p {
        white-space: pre-wrap;
        /* CSS3 */
        white-space: -moz-pre-wrap;
        /* Firefox */
        white-space: -pre-wrap;
        /* Opera <7 */
        white-space: -o-pre-wrap;
        /* Opera 7 */
        word-wrap: break-word;
        /* IE */
        width: 175px;
        max-width: 175px;
        text-align: right;
        margin-top: -30px !important;
        margin-bottom: 0px !important;
        /* 
        vertical-align: middle;
        padding:1px 1px 1px 1px !important;
        overflow: hidden;
        text-overflow: ellipsis;margin-right:5px !important;*/
        /*max-height:35px !important;
        height:35px !important;
        width: 65% !important; 
        max-width: 65% !important;*/
    }

    .steps ul li.current .number {
        border: 2px solid <?php echo $bckcolorOnly1; ?>;
    }

    .steps ul li.disabled .number {
        border: 2px solid #999;
    }

    .number:hover {
        border: 2px solid <?php echo $lighterColor3; ?>;
    }

    /*current*/
    .steps ul li.current a {
        color: <?php echo $bckcolorOnly1; ?> !important;
    }

    .steps ul li.disabled a {
        color: #999 !important;
    }

    .breadcrumb-item a,
    a.rho-link,
    .text-primary {
        color: <?php echo $bckcolorOnly1; ?> !important;
    }

    /*, h1.m-0.text-dark*/
    .rho-primary {
        background-color: <?php echo $bckcolorOnly1; ?> !important;
        color: #fff !important;
    }

    .nav-link,
    .breadcrumb li.active {
        color: #343a40 !important;
    }

    .nav-link i:hover {
        color: <?php echo $lighterColor3; ?> !important;
        -ms-transform: scale(1.2, 1.2);
        /* IE 9 */
        transform: scale(1.2, 1.2);
        /* Standard syntax */
    }

    .nav-link:hover,
    .brand-text:hover,
    .breadcrumb-item a:hover,
    a.rho-link:hover,
    .text-primary:hover {
        color: <?php echo $lighterColor3; ?> !important;
    }

    .rho-primary:hover {
        background-color: <?php echo $lighterColor3; ?> !important;
        color: whitesmoke !important;
    }

    .introMsg {
        /*
        padding-left: 1px !important;
        font-size:13px;
        font-family: Tahoma,"Helvetica Neue",Helvetica,Arial,sans-serif;
        margin-bottom:10px;
        margin-top: 0px;
        box-sizing:content-box;*/
        padding: 10px;
        font-weight: bold;
        text-align: center;
        color: #343a40;
        background: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-image: -webkit-linear-gradient(top, #fefefe, #f2f2f2);
        background-image: -moz-linear-gradient(top, #fefefe, #f2f2f2);
        background-image: -ms-linear-gradient(top, #fefefe, #f2f2f2);
        background-image: -o-linear-gradient(top, #fefefe, #f2f2f2);
        background-image: linear-gradient(bottom, #fefefe, #f2f2f2);
    }

    /* Carousel base class 
    .carousel {
        margin: 0px 0px 10px 0px;
    }*/
    /* Since positioning the image, we need to help out the caption */
    .carousel-caption {
        z-index: 999;
    }

    /* Declare heights because of positioning of img element */
    .carousel,
    .carousel-item {
        background-color: #777;
        height: 330px;
        border-radius: 5px;
    }

    /*justify-content-between d-flex flex-row align-items-center*/
    .carousel-inner {
        width: 100%;
        height: 100%;
        justify-content: center;
        overflow: hidden;
        height: 330px;
        border-radius: 5px;
    }

    .carousel-inner>.carousel-item>img {
        /*position: absolute;
        top: 0;
        left: 0;
        width: auto !important;
        height: auto !important;*/
        max-width: 100% !important;
        max-height: 100% !important;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 5px;
    }

    /* Bump up size of carousel content */
    .carousel-caption p {
        margin-bottom: 20px;
        font-size: 21px;
        line-height: 1.4;
    }

    .navbar-expand .navbar-nav .nav-link {
        padding-right: 0.55rem !important;
        padding-left: 0.55rem !important;
    }

    #startOfDayDate1 {
        display: inline-block;
    }

    #startOfDayDate2 {
        display: none;
    }

    /*Phones*/
    @media (max-width: 991px) {
        .modal-dialog {
            margin: 5px calc(1.4% + 1px) !important;
            max-width: 84%;
        }

        .rho-card {
            padding: 10px 5px !important;
            min-height: 120px !important;
        }

        .carousel,
        .carousel-item,
        .carousel-inner {
            height: 280px !important;
        }

        .carousel-caption h1 {
            font-size: 20px !important;
        }

        .wordwrap1 {
            left: 30%;
        }

        .wordwrap2 {
            left: 30%;
        }

        #startOfDayDate1 {
            display: none;
        }

        #startOfDayDate2 {
            display: inline-block;
        }
    }

    /*Tablets & Small Laptops*/
    @media (min-width: 992px) and (max-width: 1266px) {

        .carousel,
        .carousel-item,
        .carousel-inner {
            height: 320px !important;
        }

        .carousel-caption h1 {
            font-size: 24px !important;
        }

        .wordEllipsis2 {
            width: calc(100%);
            max-width: calc(100%);
        }

        .wordwrap1 {
            left: 35%;
        }

        .wordwrap2 {
            left: 35%;
        }
    }
</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed" style="font-display: fallback !important;">
    <div class="wrapper">
        <!-- Navbar -->
        <?php //margin-top:5px;  
        ?>
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php" role="button"><i class="fas fa-home"></i></a>
                </li>
            </ul>
            <!-- SEARCH FORM -->
            <form class="form-inline ml-3" role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                <div class="input-group input-group-sm" style="border:1px solid #ccc !important;border-radius: 5px !important;">
                    <input class="form-control form-control-navbar" type="text" id="allnoticesSrchFor" placeholder="Search Notices..." aria-label="Search Notices..." onkeyup="enterKeyFuncNotices(event, '', '#allmodules', 'grp=40&typ=1&vtyp=0');">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="button" onclick="getAllNotices('', '#allmodules', 'grp=40&typ=1&vtyp=0');">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item" style="padding-top:6px;">
                    <em id="startOfDayDate1" style="color:<?php echo $bckcolorOnly1; ?>;font-weight:bold;"><?php echo $startOfDayDate; ?></em>
                </li>
                <li class="nav-item handCursor" onclick="openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&vtyp=6');">
                    <a class="nav-link" data-toggle="dropdown" href="javascript:;" title="Forums/Chat Rooms">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge"><?php echo $forumCntr; ?></span>
                    </a>
                </li>
                <?php if ($lgn_num > 0) { ?>
                    <li class="nav-item handCursor" onclick="openATab('#allmodules', 'grp=210&typ=5')">
                        <a class="nav-link" title="Change My Password!">
                            <i class="fas fa-cogs"></i>
                        </a>
                    </li>
                    <li class="nav-item handCursor" onclick="logOutFunc();">
                        <a class="nav-link" title="Logout!">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <?php //elevation-4  margin:5px 10px 10px 5px !important;border-radius: 5px !important;max-height:600px !important;height: 600px !important;min-height: 98% !important;  
        ?>
        <aside class="main-sidebar sidebar-dark-primary " style="border-right:1px solid #ddd;">
            <!-- Brand Logo -->
            <a href="index.php" class="brand-link" style="line-height:0.99 !important;width:100% !important;">
                <?php
                $vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
                $vPsblVal1 = getPssblValDesc($vPsblValID1);
                if ($vPsblVal1 == "JSK_MAIN_ERP_APP_1") {
                ?>
                    <img src="../cmn_images/JSK-logo-20-20_3.png" alt="Org Logo" class="brand-image img-circle elevation-3" style="opacity: .99">
                <?php } else { ?>
                    <img src="../<?php echo $app_image1; ?>" alt="Org Logo" class="brand-image img-circle elevation-3" style="opacity: .99">
                <?php } ?>
                <strong class="brand-text wordEllipsis1" style="font-size:13px !important;padding-top: 9px;font-weight: normal;display:inline-block;"><?php echo $app_name; ?></strong>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) padding:8px 5px 1px 1px !important;-->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="">
                    <div class="image handCursor" onclick="openATab('#allmodules', 'grp=50&typ=1');">
                        <img src="<?php echo $app_url . $tmpDest . $nwFileName; ?>" class="img-circle elevation-3" alt="User Image" style="height: 45px !important; width: auto !important;">
                    </div>
                    <div class="info wordEllipsis2" style="line-height:2.0 !important;">
                        <a href="javascript:openATab('#allmodules', 'grp=50&typ=1');" class="" style="<?php echo $forecolors; ?>">
                            <strong style="font-weight: normal;display:block;" id="lgnProfileUname"><?php echo $usrName; ?></strong>
                        </a>
                        <p id="startOfDayDate2" style="<?php echo $forecolors; ?>;font-weight:bold;font-style:italic;"><?php echo $startOfDayDate; ?></p>
                    </div>
                </div>
                <!-- GUEST GUEST GUEST GUEST GUEST GUEST -->
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="index.php" class="nav-link" style="<?php echo $forecolors; ?>">
                                <i class="nav-icon fas fa-home" style="<?php echo $forecolors; ?>"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <?php if ($createMainAppLnk) { ?>
                            <li class="nav-item">
                                <a href="../index.php" class="nav-link" style="<?php echo $forecolors; ?>">
                                    <i class="nav-icon fas fa-arrow-circle-left" style="<?php echo $forecolors; ?>"></i>
                                    <p>Back to Main App</p>
                                </a>
                            </li>
                        <?php }
                        if ($lgn_num > 0) { ?>
                            <li class="nav-item hideNotice">
                                <a href="javascript: logOutFunc();" class="nav-link" style="<?php echo $forecolors; ?>">
                                    <i class="nav-icon fas fa-sign-out-alt" style="<?php echo $forecolors; ?>"></i>
                                    <p>Sign-Out</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:openATab('#allmodules', 'grp=45&typ=1&vtyp=0');" class="nav-link " style="<?php echo $forecolors; ?>">
                                    <i class="nav-icon fas fa-envelope-open-text" style="<?php echo $forecolors; ?>"></i>
                                    <p>
                                        Inbox/Worklist
                                        <span class="right badge bg-success" style="background-color: lime !important;"><?php echo $inbxCntr; ?></span>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link" style="<?php echo $forecolors; ?>">
                                    <i class="nav-icon fas fa-chart-pie" style="<?php echo $forecolors; ?>"></i>
                                    <p>
                                        Charts/Reports
                                        <i class="nav-icon right fas fa-angle-left" style="<?php echo $forecolors; ?>"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="<?php echo $forecolors; ?>;padding-left:20px !important;">
                                    <li class="nav-item">
                                        <a href="javascript:openATab('#allmodules', 'grp=70&typ=1&vtyp=2');" class="nav-link " style="<?php echo $forecolors; ?>">
                                            <i class="nav-icon far fa-circle" style="<?php echo $forecolors; ?>"></i>
                                            <p>My Charts</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:openATab('#allmodules', 'grp=70&typ=1&vtyp=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                            <i class="nav-icon far fa-circle" style="<?php echo $forecolors; ?>"></i>
                                            <p>General Charts</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:openATab('#allmodules', 'grp=9&typ=1&vtyp=0');" class="nav-link " style="<?php echo $forecolors; ?>">
                                            <i class="nav-icon far fa-circle" style="<?php echo $forecolors; ?>"></i>
                                            <p>Reports/Processes</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <?php if ($canViewSelfsrvc) { ?>
                                <li class="nav-item has-treeview hideNotice">
                                    <a href="#" class="nav-link" style="<?php echo $forecolors; ?>">
                                        <i class="nav-icon fas fa-wrench" style="<?php echo $forecolors; ?>"></i>
                                        <p>
                                            Self-Service
                                            <i class="nav-icon right fas fa-angle-left" style="<?php echo $forecolors; ?>"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="<?php echo $forecolors; ?>;padding-left:20px !important;">
                                        <li class="nav-item">
                                            <a href="javascript:openATab('#allmodules', 'grp=50&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                <i class="nav-icon far fa-user" style="<?php echo $forecolors; ?>"></i>
                                                <p>My Profile</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="javascript:openATab('#allmodules', 'grp=80&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                <i class="nav-icon fas fa-money-bill-alt" style="<?php echo $forecolors; ?>"></i>
                                                <p>Bills/Payments</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="javascript:openATab('#allmodules', 'grp=150&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                <i class="nav-icon fas fa-calendar-week" style="<?php echo $forecolors; ?>"></i>
                                                <p>Events/Attendances</p>
                                            </a>
                                        </li>
                                        <?php if ($canViewEvote) { ?>
                                            <li class="nav-item ">
                                                <a href="javascript:openATab('#allmodules', 'grp=140&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <i class="nav-icon fas fa-vote-yea nav-icon" style="<?php echo $forecolors; ?>"></i>
                                                    <p>e-Voting</p>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewElearn) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=130&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <i class="nav-icon fab fa-leanpub nav-icon" style="<?php echo $forecolors; ?>"></i>
                                                    <p>e-Learning</p>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($canViewClnc) { ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=90&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <i class="nav-icon fas fa-stethoscope nav-icon" style="<?php echo $forecolors; ?>"></i>
                                                    <p>Medical Appointments</p>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php
                                        if ($canViewPrfmnc) {
                                            $customMdlNm = "Academics Portal";
                                            if (trim($acaPrfmncType) == "Objective") {
                                                $customMdlNm = "Appraisal Portal";
                                            }
                                        ?>
                                            <li class="nav-item  ">
                                                <a href="javascript:openATab('#allmodules', 'grp=110&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                                    <i class="nav-icon fas fa-graduation-cap nav-icon" style="<?php echo $forecolors; ?>"></i>
                                                    <p><?php echo $customMdlNm; ?></p>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&vtyp=5');" class="nav-link " style="<?php echo $forecolors; ?>">
                                        <i class="nav-icon fas fa-comment" style="<?php echo $forecolors; ?>"></i>
                                        <p>Comments/Feedback</p>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a href="javascript:openATab('#allmodules', 'grp=210&typ=2');" class="nav-link" style="<?php echo $forecolors; ?>">
                                    <i class="nav-icon fas fa-sign-in-alt" style="<?php echo $forecolors; ?>"></i>
                                    <p>Sign-In</p>
                                </a>
                            </li>
                            <?php if ($isSelfRgstrAllwd == "YES") { ?>
                                <li class="nav-item">
                                    <a href="javascript:openATab('#allmodules', 'grp=210&typ=4');" class="nav-link" style="<?php echo $forecolors; ?>">
                                        <i class="nav-icon fas fa-user" style="<?php echo $forecolors; ?>"></i>
                                        <p>New User? Register</p>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                        <li class="nav-item">
                            <a href="javascript:openATab('#<?php echo $noticesElmntNm; ?>', 'grp=40&typ=1&vtyp=6');" class="nav-link " style="<?php echo $forecolors; ?>">
                                <i class="nav-icon fas fa-comments" style="<?php echo $forecolors; ?>"></i>
                                <p>
                                    Forums/Chat Rooms
                                    <span class="right badge bg-danger" style="background-color: red !important;"><?php echo $forumCntr; ?></span>
                                </p>
                            </a>
                        </li>
                        <?php if ($canViewHlpDsk) { ?>
                            <li class="nav-item">
                                <a href="javascript:openATab('#allmodules', 'grp=60&typ=1');" class="nav-link " style="<?php echo $forecolors; ?>">
                                    <i class="nav-icon fas fa-headphones" style="<?php echo $forecolors; ?>"></i>
                                    <p>Help Desk</p>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" id="allmodules">
            <?php
            if ($group == 210 && ($type == 707 || $type == 708 || $type == 709)) {
                if ($type == 709) {
                    $type = 5;
                }
                require 'srvr_self/base_files/login.php';
            } else {
            ?>
                <!-- Content Header (Page header) -->
                <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Self-Service Dashboard</h1>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active">Self-Service Dashboard</li>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->
                <!-- Main content -->
                <section class="content" style="padding: 16px 0.5rem !important;">
                    <div class="container-fluid">
                        <!-- Carousel-->
                        <?php
                        $showSliderID = getEnbldPssblValID("Show Home Page Slider", getLovID("All Other General Setups"));
                        $showSlider = getPssblValDesc($showSliderID);
                        if (strtoupper($showSlider) == "YES") {
                            $total1 = get_SliderNoticeTtls($srchFor, $srchIn);
                            if ($pageNo > ceil($total1 / $lmtSze)) {
                                $pageNo = 1;
                            } else if ($pageNo < 1) {
                                $pageNo = ceil($total1 / $lmtSze);
                            }

                            $curIdx1 = $pageNo - 1;
                            $result1 = get_SliderNotices($srchFor, $srchIn, $curIdx1, $lmtSze, $sortBy);
                            $cntr1 = 0;
                            $ttlRecs1 = loc_db_num_rows($result1);
                            $isactive = "active";
                            $sliderCntnt = array();
                            if ($ttlRecs1 > 0) {
                        ?>
                                <div id="myCarousel" class="carousel slide" data-ride="carousel" style="">
                                    <ul class="carousel-indicators">
                                        <?php
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            if ($cntr1 > 0) {
                                                $isactive = "";
                                            }
                                            array_push($sliderCntnt, str_replace("{:articleID}", $row1[0], $row1[13]));
                                        ?>
                                            <li data-target="#myCarousel" data-slide-to="<?php echo $cntr1; ?>" class="<?php echo $isactive; ?>"></li>
                                        <?php
                                            $cntr1 += 1;
                                        }
                                        ?>
                                    </ul>
                                    <div class="carousel-inner" role="listbox">
                                        <?php
                                        $isactive1 = "active";
                                        for ($i = 0; $i < count($sliderCntnt); $i++) {
                                            if ($i > 0) {
                                                $isactive1 = "";
                                            }
                                        ?>
                                            <div class="carousel-item <?php echo $isactive1; ?>">
                                                <?php echo str_replace("cmn_images/", "../cmn_images/", str_replace("btn-primary", "rho-primary", $sliderCntnt[$i])); ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </a>
                                        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            <?php
                            }
                        }
                        if ($lgn_num > 0) {
                            ?>
                            <div class="row" style="margin-left:0px !important;margin-right:0px !important;">
                                <div class="col-md-12" style="padding:0px 0px 0px 0px;margin-top:10px;">
                                    <div class="introMsg" style="">Welcome, <span style="color:blue;font-weight:bold;"><?php echo strtoupper(getPrsnFullNm($prsnid) . " (" . getPersonLocID($prsnid) . ")"); ?></span> to the <?php echo $app_name; ?>!</div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- Small boxes (Stat box) -->
                        <div class="row" style="margin-top:10px;margin-left:-2px !important;margin-right:-7.5px !important;">
                            <div class="col-lg-3" style="padding-left:2px !important;">
                                <?php
                                $cstmMdlNm = "A New User?";
                                $cstmMdlNmDet = "Click to Register";
                                $cstmMdlLink = "openATab('#allmodules', 'grp=210&typ=4');";
                                if ($isSelfRgstrAllwd != "YES") {
                                    $cstmMdlNm = "Existing User?";
                                    $cstmMdlNmDet = "Click to Sign-in";
                                    $cstmMdlLink = "openATab('#allmodules', 'grp=210&typ=2');";
                                }
                                if ($lgn_num > 0) {
                                    $cstmMdlNm = "My Personal Profile";
                                    $cstmMdlNmDet = "Click to View";
                                    $cstmMdlLink = "openATab('#allmodules', 'grp=50&typ=1');";
                                }
                                ?>
                                <div class="rho-card panel progress-banner mb-4 cardBtn rho-gradient-1 handCursor">
                                    <a href="javascript:<?php echo $cstmMdlLink; ?>;">
                                        <div class="card-body text-white panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-user fa-4x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right wordEllipsis2">
                                                    <div style="margin-top:10px;"><?php echo $cstmMdlNm; ?></div>
                                                    <div class="huge">&nbsp;</div>
                                                    <div class="huge"><?php echo $cstmMdlNmDet; ?>&nbsp;<i class="fa fa-arrow-circle-right"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3" style="padding-left:2px !important;">
                                <?php
                                $cstmMdlNm = "Announcements";
                                $cstmMdlNmDet = "Click to View";
                                $cstmMdlLink = "openATab('#allmodules', 'grp=40&typ=1');";
                                ?>
                                <div class="rho-card panel progress-banner mb-4 cardBtn rho-gradient-2 handCursor">
                                    <a href="javascript:<?php echo $cstmMdlLink; ?>;">
                                        <div class="card-body text-white panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-newspaper fa-4x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right wordEllipsis2">
                                                    <div style="margin-top:10px;"><?php echo $cstmMdlNm; ?></div>
                                                    <div class="huge">&nbsp;</div>
                                                    <div class="huge"><?php echo $cstmMdlNmDet; ?>&nbsp;<i class="fa fa-arrow-circle-right"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3" style="padding-left:2px !important;">
                                <?php
                                $cstmMdlNm = "My Financials";
                                $cstmMdlNmDet = "Click to View";
                                $cstmMdlLink = "openATab('#allmodules', 'grp=80&typ=1');";
                                $cstmFontAwsme = "<i class=\"fas fa-money-bill-alt fa-4x\"></i>";
                                if ($configuredSysTyp === "School Management System") {
                                    $cstmMdlNm = "Academics Portal";
                                    $cstmMdlNmDet = "Click to View";
                                    $cstmMdlLink = "openATab('#allmodules', 'grp=110&typ=1');";
                                    $cstmFontAwsme = "<i class=\"fas fa-graduation-cap fa-4x\"></i>";
                                } else if ($configuredSysTyp === "Hospital Management System") {
                                    $cstmMdlNm = "Medical Appointment";
                                    $cstmMdlNmDet = "Click to Book";
                                    $cstmMdlLink = "openATab('#allmodules', 'grp=90&typ=1');";
                                    $cstmFontAwsme = "<i class=\"fas fa-stethoscope fa-4x\"></i>";
                                } else if ($configuredSysTyp === "Hotel Management System") {
                                    $cstmMdlNm = "My Bookings";
                                    $cstmMdlNmDet = "Click to Book";
                                    $cstmMdlLink = "openATab('#allmodules', 'grp=120&typ=1');";
                                    $cstmFontAwsme = "<i class=\"fas fa-stethoscope fa-4x\"></i>";
                                }
                                ?>
                                <div class="rho-card panel progress-banner mb-4 cardBtn rho-gradient-3 handCursor">
                                    <a href="javascript:<?php echo $cstmMdlLink; ?>;">
                                        <div class="card-body text-white panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <?php echo $cstmFontAwsme; ?>
                                                </div>
                                                <div class="col-xs-9 text-right wordEllipsis2">
                                                    <div style="margin-top:10px;"><?php echo $cstmMdlNm; ?></div>
                                                    <div class="huge">&nbsp;</div>
                                                    <div class="huge"><?php echo $cstmMdlNmDet; ?>&nbsp;<i class="fa fa-arrow-circle-right"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3" style="padding-left:2px !important;">
                                <?php
                                $cstmMdlNm = "All Applications";
                                $cstmMdlNmDet = "Click to View All";
                                $cstmMdlLink = "openATab('#allmodules', 'grp=42&typ=1')";
                                $cstmFontAwsme = "<i class=\"fas fa-desktop fa-4x\"></i>";
                                ?>
                                <div class="rho-card panel progress-banner mb-4 cardBtn rho-gradient-4 handCursor">
                                    <a href="javascript:<?php echo $cstmMdlLink; ?>;">
                                        <div class="card-body text-white panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <?php echo $cstmFontAwsme; ?>
                                                </div>
                                                <div class="col-xs-9 text-right wordEllipsis2">
                                                    <div style="margin-top:10px;"><?php echo $cstmMdlNm; ?></div>
                                                    <div class="huge">&nbsp;</div>
                                                    <div class="huge"><?php echo $cstmMdlNmDet; ?>&nbsp;<i class="fa fa-arrow-circle-right"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- ./col -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            <?php } ?>
        </div>
        <!-- /.content-wrapper -->
        <div id="footer" class="main-footer">
            <div style="min-height:20px;" style="<?php echo $bckcolors_home; ?>">
                <div class="col-md-12" style="<?php echo $bckcolors_home; ?>color:#FFF;font-family: Times;font-style: italic;font-size:12px;text-align:center;padding-top:5px;padding-bottom:5px;border-radius:5px;">
                    <p style="margin: 0px !important;">Copyright &COPY; <?php echo date('Y'); ?> <a style="color:#FFF" href="<?php echo $about_url; ?>" target="_blank"><?php echo $app_org; ?></a>.</p>
                </div>
            </div>
        </div>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <?php
    require 'srvr_self/base_files/footer1.php';

    function get_SliderNotices($searchFor, $searchIn, $offset, $limit_size, $ordrBy)
    {
        global $usrID;
        global $lgn_num;
        global $qStrtDte;
        global $qEndDte;
        global $artCategory;
        global $isMaster;
        global $prsnid;

        $extrWhr = "";
        $ordrByCls = "ORDER BY tbl1.article_id DESC";
        if ($artCategory != "" && $artCategory != "All") {
            $extrWhr = " AND (tbl1.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
        }
        if ($lgn_num > 0 && $usrID > 0) {
            $extrWhr .= " AND (tbl1.is_published = '1') 
            and (tbl1.article_category IN ('Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,tbl1.allowed_group_id, tbl1.allowed_group_type)>0)";
        } else {
            $extrWhr .= " AND (tbl1.is_published = '1') 
            and (tbl1.article_category IN ('Slider')) 
            and (tbl1.allowed_group_type='Public')";
        }
        $wherecls = " AND (tbl1.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or tbl1.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

        if ($qStrtDte != "") {
            $wherecls .= " AND (tbl1.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
        }
        if ($qEndDte != "") {
            $wherecls .= " AND (tbl1.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
        }
        if ($ordrBy == "Date Published") {
            $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
        } else if ($ordrBy == "No. of Hits") {
            $ordrByCls = "ORDER BY tbl1.hits DESC";
        } else if ($ordrBy == "Category") {
            $ordrByCls = "ORDER BY tbl1.article_category ASC";
        } else if ($ordrBy == "Title") {
            $ordrByCls = "ORDER BY tbl1.article_header ASC";
        } else {
            $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
        }
        $sqlStr = "SELECT tbl1.* FROM (SELECT a.article_id, a.article_category, a.article_header, a.header_url, a.article_body,  
       a.is_published, a.publishing_date, a.author_name, a.author_email, prs.get_prsn_loc_id(a.author_prsn_id), 
       (select count(distinct b.created_by) from self.self_articles_hits b where a.article_id = b.article_id) hits,
       a.allowed_group_type, a.allowed_group_id, a.article_intro_msg  
  FROM self.self_articles a) tbl1  
WHERE (1=1" . $extrWhr . "$wherecls) $ordrByCls LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
        //echo $sqlStr;
        $result = executeSQLNoParams($sqlStr);
        return $result;
    }

    function get_SliderNoticeTtls($searchFor, $searchIn)
    {
        global $usrID;
        global $lgn_num;
        global $qStrtDte;
        global $qEndDte;
        global $artCategory;
        global $isMaster;
        global $prsnid;
        $extrWhr = "";

        if ($artCategory != "" && $artCategory != "All") {
            $extrWhr = " AND (a.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
        }
        if ($lgn_num > 0 && $usrID > 0) {
            $extrWhr .= " AND (a.is_published = '1') 
            and (a.article_category IN ('Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,a.allowed_group_id, a.allowed_group_type)>0)";
        } else {
            $extrWhr .= " AND (a.is_published = '1') 
            and (a.article_category IN ('Slider')) 
            and (a.allowed_group_type='Public')";
        }
        $wherecls = " AND (a.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or a.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

        if ($qStrtDte != "") {
            $qStrtDte = cnvrtDMYTmToYMDTm($qStrtDte);
            $wherecls .= " AND (a.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
        }
        if ($qEndDte != "") {
            $qEndDte = cnvrtDMYTmToYMDTm($qEndDte);
            $wherecls .= " AND (a.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
        }

        $sqlStr = "SELECT count(1) 
  FROM self.self_articles a 
  WHERE (1=1" . $extrWhr . "$wherecls)";
        //echo $sqlStr;
        $result = executeSQLNoParams($sqlStr);
        while ($row = loc_db_fetch_array($result)) {
            return $row[0];
        }
        return 0;
    }
    ?>