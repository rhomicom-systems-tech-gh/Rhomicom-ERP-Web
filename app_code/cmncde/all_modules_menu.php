<?php
$noticesCMSIdx = 17;


$allMdlslovID = getLovID("All Enabled Modules");

$menuItems = array(
    "Personal Records", "Bills/Payments", "Events & Attendance",
    "Elections Centre", "e-Learning", "Accounting",
    "Sales & Inventory", "Hospitality Management", "Visits & Appointments",
    "Academics Management", "Projects Management", "Clinic & Hospital",
    "Banking", "Vault Management", "Agent Registry", "Asset Tracking",
    "Summary Dashboard", "Notices & Content Management",
    "Reports / Processes", "Help Desk", "Sacrament",
    "Performance Appraisal System"
    
);
$menuImages = array(
    "person.png", "invcBill.png", "calendar2.png",
    "election.png", "addresses_wbg_64x64.png", "GL-256.png",
    "Inventory.png", "rent1.png", "Calander.png",
    "education.png", "engineer.png", "medical.png",
    "bank_256.png", "secure_icon.png", "registry-icon.png", "delivery-icon.png",
    "dashboard220.png", "Notebook.png",
    "settings.png", "helpdesk1.png", "sacrament.jpg", "pms1.jpg"
    
);
$menuLinks = array(
    "grp=8&typ=1", "grp=7&typ=1", "grp=16&typ=1",
    "grp=19&typ=10", "grp=19&typ=12", "grp=6&typ=1",
    "grp=12&typ=1", "grp=18&typ=1", "grp=14&typ=1",
    "grp=15&typ=1&mdl=ACA", "grp=13&typ=1", "grp=14&typ=1&mdl=Clinic/Hospital",
    "grp=17&typ=1", "grp=25&typ=1", "grp=26&typ=1", "grp=27&typ=1",
    "grp=40&typ=4", "grp=40&typ=3&vtyp=1",
    "grp=9&typ=1", "grp=41&typ=1", "grp=50&typ=1", "grp=15&typ=1&mdl=PMS" 
    
);
$mdlNms = array(
    "Basic Person Data", "Self Service", "Self Service",
    "Self Service", "Self Service", "Accounting",
    "Stores And Inventory Manager", "Hospitality Management", "Visits and Appointments",
    "Learning/Performance Management", "Projects Management", "Clinic/Hospital",
    "Banking", "Vault Management", "Agent Registry", "Asset Tracking",
    "Self Service", "Self Service",
    "Reports And Processes", "Self Service", "Catholic Church Sacrament", "Learning/Performance Management"
    
);

$dfltPrvldgs = array(
    "View Person", "View Internal Payments", "View Events/Attendances",
    "View Elections", "View E-Library", "View Accounting",
    "View Inventory Manager", "View Hospitality Manager", "View Visits and Appointments",
    "View Learning/Performance Management", "View Projects Management", "View Clinic/Hospital",
    "View Banking", "View Vault Management", "View Agent Registry", "View Asset Tracking",
    "View Self-Service", "View Self-Service",
    "View Reports And Processes", "View Self-Service", "View Person", "View Learning/Performance Management",
    
);


$prmSnsRstl = getHomePgPrmssns($prsnid);
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
$canViewAgnt =  false; //($prmSnsRstl[16] >= 1) ? true :
$canViewATrckr = false; //($prmSnsRstl[17] >= 1) ? true : 
$canViewSysAdmin = ($prmSnsRstl[18] >= 1) ? true : false;
$canViewOrgStp = ($prmSnsRstl[19] >= 1) ? true : false;
$canViewLov = ($prmSnsRstl[20] >= 1) ? true : false;
$canViewWkf = ($prmSnsRstl[21] >= 1) ? true : false;
$canViewArtclAdmn = ($prmSnsRstl[22] >= 1) ? true : false;
$canViewRpts = ($prmSnsRstl[23] >= 1) ? true : false;
$canViewHlpDsk = ($prmSnsRstl[27] >= 1) ? true : false;
$canViewAppraisal = ($prmSnsRstl[28] >= 1) ? true : false;
$canViewSacrament = ($prmSnsRstl[29] >= 1) ? true : false;
$canview = $canViewSelfsrvc || $canViewPrsn;
//$canViewSacrament = true;
$cntent = "<div>
				<ul class=\"breadcrumb\" style=\"$breadCrmbBckclr\">
					<li onclick=\"openATab('#home', 'grp=40&typ=1');\">
                                                <i class=\"fa fa-home\" aria-hidden=\"true\"></i>
						<span style=\"text-decoration:none;\">Home</span>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>
					<li onclick=\"openATab('#allmodules', 'grp=40&typ=5');\">
						<span style=\"text-decoration:none;\">All Modules&nbsp;</span>
					</li>
				</ul>
			</div>";
// && $canview === true
if ($lgn_num > 0) {
    $grpcntr = 0;
    $appCntr = 0;
    $cntent .= "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em; padding:5px 10px 15px 10px;border:1px solid #ccc;\">";
    for ($i = 0; $i < count($menuItems); $i++) {
        $iToUse = $i;
        if ($i == 10) {
            $i = 21;
        } else if ($i >= 11) {
            $i = $i - 1;
        }
        //echo " i is ". $i ." ";
        $customMdlNm = $menuItems[$i];
        //echo $menuItems[$i];
        if ($i > 0) {
            if ($i == 1 && $canViewIntrnlPay == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 2 && $canViewEvnts == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 3 && $canViewEvote == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 4 && $canViewElearn == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 5 && $canViewAcntng == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 6 && $canViewSales == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 7 && $canViewHotel == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 8 && $canViewVsts == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 9 && $canViewPrfmnc == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 21 && $canViewAppraisal == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 10 && $canViewProjs == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 11 && $canViewClnc == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 12 && $canViewBnkng == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 13 && $canViewVMS == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 14 && $canViewAgnt == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 15 && $canViewATrckr == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 16 && $canview == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 17 && $canViewArtclAdmn == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 18 && $canViewRpts == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 20 && $canViewSacrament == FALSE) {
                $i = $iToUse;
                continue;
            } else if ($i == 19 && $canViewHlpDsk == FALSE) {
                $i = $iToUse;
                continue;
            }
        }
        if ($i == 0) {
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
        $menuItems[$i] = $customMdlNm;
        if ($grpcntr == 0) {
            $cntent .= "<div class=\"row\">";
        }
        if ($i == $noticesCMSIdx) {
            $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#$noticesElmntNm', '$menuLinks[$i]');\">
            <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
        </button>
    </div>";
        } else {
            $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', '$menuLinks[$i]');\">
            <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
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
    echo "</div>" . $cntent;


    $menuItemsAdmn = array(
        "System Administration", "Organization Setup",
        "Value Lists Setup", "Workflow Administration"
    );
    $menuImagesAdmn = array(
        "ma-logo.png", "Home.png",
        "viewIcon.png", "bb_flow.gif"
    );
    $menuLinksAdmn = array(
        "grp=3&typ=1", "grp=5&typ=1",
        "grp=4&typ=1", "grp=11&typ=1"
    );
    $mdlNmsAdmn = array(
        "System Administration", "Organization Setup",
        "General Setup", "Workflow Manager"
    );

    $dfltPrvldgsAdmn = array(
        "View System Administration",
        "View Organization Setup", "View General Setup",
        "View Workflow Manager"
    );

    if ($canViewSysAdmin || $canViewOrgStp || $canViewLov || $canViewWkf) {
        $canview = test_prmssns($dfltPrvldgsAdmn[0], $mdlNmsAdmn[0]);
        $grpcntrAdmn = 0;
        $appCntrAdmn = 0;
        $cntentAdmn = "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 14px; padding:5px 10px 5px 10px;border:1px solid #ccc;margin-top:10px;\">";
        for ($i = 0; $i < count($menuItemsAdmn); $i++) {
            if ($i == 0 && $canViewSysAdmin == FALSE) {
                continue;
            } else if ($i == 1 && $canViewOrgStp == FALSE) {
                continue;
            } else if ($i == 2 && $canViewLov == FALSE) {
                continue;
            } else if ($i == 3 && $canViewWkf == FALSE) {
                continue;
            } else if ($i == 4 && $canViewArtclAdmn == FALSE) {
                continue;
            } else if ($i == 5 && $canViewRpts == FALSE) {
                continue;
            }
            if ($grpcntrAdmn == 0) {
                $cntentAdmn .= "<div class=\"row\">";
            }
            $cntentAdmn .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', '$menuLinksAdmn[$i]');\">
            <img src=\"cmn_images/$menuImagesAdmn[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2\">" . ($menuItemsAdmn[$i]) . "</span>
        </button>
    </div>";
            $appCntrAdmn += 1;
            if ($grpcntrAdmn == 3 || $i == count($menuItemsAdmn) - 1) {
                $cntentAdmn .= "</div>";
                $grpcntrAdmn = 0;
            } else {
                $grpcntrAdmn = $grpcntrAdmn + 1;
            }
        }
        echo "</div>" . $cntentAdmn;
    }
?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#appsMdlsCnt").html("<?php echo $appCntr; ?>");
        });
    </script>
<?php
}
?>