<?php
$noticesCMSIdx = 17;


$allMdlslovID = getLovID("All Enabled Modules");
//"My Leave of Absence",
$menuItems = array(
    "Personal Stuff", "My Financials", "My Events & Attendance",
    "Elections Centre", "e-Learning", "My Bookings", "My Appointments",
    "My Academics", "Summary Dashboard", "Notices & Announcements",
    "Reports / Processes", "Help Desk", "My Appraisals"
);
$menuImages = array(
    "person.png", "invcBill.png", "calendar2.png",
    "election.png", "addresses_wbg_64x64.png", "rent1.png", "medical.png",
    "education.png", "dashboard220.png", "Notebook.png",
    "settings.png", "helpdesk1.png", "pms1.jpg"
);
$menuLinks = array(
    "grp=50&typ=1&vtyp=999", "grp=80&typ=1", "grp=150&typ=1",
    "grp=140&typ=1", "grp=130&typ=1", "grp=120&typ=1", "grp=90&typ=1",
    "grp=110&typ=1&mdl=ACA", "grp=70&typ=1", "grp=40&typ=1",
    "grp=9&typ=1", "grp=60&typ=1", "grp=110&typ=1&mdl=PMS"
);

$mdlNms = array(
    "Self Service", "Self Service", "Self Service",
    "Self Service", "Self Service", "Self Service", "Self Service",
    "Self Service", "Self Service",
    "Self Service", "Self Service", "Self Service", "Self Service"
);

$dfltPrvldgs = array(
    "View Self-Service",
    "View Internal Payments",
    "View Events/Attendances",
    "View Elections",
    "View E-Library",
    "View My Bookings",
    "View My Appointments",
    "View My Performance", "View Self-Service", "View Self-Service", "View Self-Service", "View Help Desk",
    "View My Appraisal"
);

$prmSnsRstl = getSelfPgPrmssns($prsnid);
$lnkdFirmID = $prmSnsRstl[0];
$canViewSelfsrvc = ($prmSnsRstl[1] >= 1) ? true : false;
$canViewEvote = ($prmSnsRstl[2] >= 1) ? true : false;
if (getEnbldPssblValID("e-Voting", $allMdlslovID) <= 0) {
    $canViewEvote = false;
}
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
$canViewAgnt = false; //($prmSnsRstl[16] >= 1) ? true :
$canViewATrckr = false; //($prmSnsRstl[17] >= 1) ? true : 
$canViewSysAdmin = ($prmSnsRstl[18] >= 1) ? true : false;
$canViewOrgStp = ($prmSnsRstl[19] >= 1) ? true : false;
$canViewLov = ($prmSnsRstl[20] >= 1) ? true : false;
$canViewWkf = ($prmSnsRstl[21] >= 1) ? true : false;
$canViewArtclAdmn = ($prmSnsRstl[22] >= 1) ? true : false;
$canViewRpts = ($prmSnsRstl[23] >= 1) ? true : false;
$canViewHlpDsk =  ($prmSnsRstl[27] >= 1) ? true : false;
$canViewAppraisal = ($prmSnsRstl[28] >= 1) ? true : false;
$canview = $canViewSelfsrvc || $canViewPrsn;
$cntent = "";
// && $canview === true
if ($lgn_num > 0) {
    $grpcntr = 0;
    $appCntr = 0;
?>
    <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">All Self-Service Apps</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Self-Service Apps</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content" style="padding: 10px 5px 10px 5px !important;">
        <div class="container-fluid">
            <?php
            for ($i = 0; $i < count($menuItems); $i++) {
                $iToUse = $i;
                if ($i == 8) {
                    $i = 12;
                } else if ($i >= 9) {
                    $i = $i - 1;
                }
                $customMdlNm = $menuItems[$i];
                if (($i == 0 || $i >= 8) && $canview == FALSE) {
                    $i = $iToUse;
                    continue;
                } else if (($i == 1) && $canViewIntrnlPay == FALSE) {
                    $i = $iToUse;
                    continue;
                } else if (($i == 2) && $canViewEvnts == FALSE) {
                    $i = $iToUse;
                    continue;
                } else if (($i == 3) && $canViewEvote == FALSE) {
                    $i = $iToUse;
                    continue;
                } else if (($i == 4) && $canViewElearn == FALSE) {
                    $i = $iToUse;
                    continue;
                } else if (($i == 5) && $canViewHotel == FALSE) {
                    $i = $iToUse;
                    continue;
                } else if (($i == 6) && $canViewVsts == FALSE) {
                    $i = $iToUse;
                    continue;
                } else if (($i == 7) && $canViewPrfmnc == FALSE) {
                    continue;
                } else if (($i == 12) && $canViewAppraisal == FALSE) {
                    $i = $iToUse;
                    continue;
                } else if (($i == 11) && $canViewHlpDsk == FALSE) {
                    $i = $iToUse;
                    continue;
                }
                /* if ($i == 0) {
                  $customMdlNm = getEnbldPssblValDesc("Basic Person Data", getLovID("Customized Module Names"));
                  if (trim($customMdlNm) == "") {
                  $customMdlNm = $menuItems[$i];
                  }
                  } else if ($i == 1) {
                  $customMdlNm = getEnbldPssblValDesc("Internal Payments", getLovID("Customized Module Names"));
                  if (trim($customMdlNm) == "") {
                  $customMdlNm = $menuItems[$i];
                  }
                  } else if ($i == 2) {
                  $customMdlNm = getEnbldPssblValDesc("Events and Attendance", getLovID("Customized Module Names"));
                  if (trim($customMdlNm) == "") {
                  $customMdlNm = $menuItems[$i];
                  }
                  } else if ($i == 6) {
                  $customMdlNm = getEnbldPssblValDesc("Sales and Inventory", getLovID("Customized Module Names"));
                  if (trim($customMdlNm) == "") {
                  $customMdlNm = $menuItems[$i];
                  }
                  } else if ($i == 9) {
                  $customMdlNm = getEnbldPssblValDesc("Learning/Performance Management", getLovID("Customized Module Names"));
                  if (trim($customMdlNm) == "") {
                  $customMdlNm = $menuItems[$i];
                  }
                  }
                  $menuItems[$i] = $customMdlNm; */
                if ($grpcntr == 0) {
                    $cntent .= "<div class=\"row\">";
                }
                if ($i == $noticesCMSIdx) {
                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#$noticesElmntNm', '$menuLinks[$i]');\">
            <img src=\"../cmn_images/$menuImages[$i]\" style=\"margin:5px; padding-right: 1em; height:78px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2 btn-block\" style=\"min-width:100% !important;width:100% !important;\">&nbsp;</span>
            <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
        </button>
    </div>";
                } else {
                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', '$menuLinks[$i]');\">
            <img src=\"../cmn_images/$menuImages[$i]\" style=\"margin:5px; padding-right: 1em; height:78px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2 btn-block\" style=\"min-width:100% !important;width:100% !important;\">&nbsp;</span>
            <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
        </button>
    </div>";
                }
                $appCntr += 1;
                if ($grpcntr == 3 || $iToUse == count($menuItems) - 1) {
                    $cntent .= "</div>";
                    $grpcntr = 0;
                } else {
                    $grpcntr = $grpcntr + 1;
                }
                $i = $iToUse;
                $iToUse++;
            }
            echo $cntent;
            ?>
        </div><!-- /.container-fluid -->
    </section>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#appsMdlsCnt").html("<?php echo $appCntr; ?>");
        });
    </script>

<?php
}
?>