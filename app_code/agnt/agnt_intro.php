<?php

$menuItems = array("Agent Profiles", "Terminated Affiliations", "Reported Suspicious Activities", "System Configurations", "Standard Reports", "All Agents Search");
$menuImages = array("staffs.png", "90.png", "spyspray.png", "settings.png", "report-icon-png.png", "search.png");

$mdlNm = "Agent Registry";
$ModuleName = $mdlNm;

$dfltPrvldgs = array("View Agent Registry",
    /* 1 */ "View Agent Profiles", "View Terminated Affiliations", "View Suspicious Activities", "View System Configurations",
    /* 5 */ "View SQL", "View Record History", "View All Agent Information",
    /* 8 */ "View Registered EMIs", "View ID Formats", "Merge Agents", "View Only My Affiliated Agents Information",
    /* 12 */ "Add Agent Profile", "Edit Agent Profile", "Delete Agent Profile",
    /* 15 */ "Add Agent Affiliation", "Edit Agent Affiliation", "Terminate Agent Affiliation",
    /* 18 */ "Report Suspicious Activity", "Withdraw Reported Suspicious Activity");
$canview = test_prmssns($dfltPrvldgs[0], $ModuleName);
$caneditRpts = test_prmssns($dfltPrvldgs[6], $ModuleName);
$canaddRpts = test_prmssns($dfltPrvldgs[5], $ModuleName);
$candelRpts = test_prmssns($dfltPrvldgs[7], $ModuleName);
$vwtyp = "0";
$qstr = "";
$dsply = "";
$actyp = "";
$srchFor = "";
$srchIn = "Name";
$PKeyID = -1;
$sortBy = "ID ASC";
if (isset($_POST['searchfor'])) {
    $srchFor = cleanInputData($_POST['searchfor']);
}
if (isset($_POST['searchin'])) {
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
if (isset($_POST['sortBy'])) {
    $sortBy = cleanInputData($_POST['sortBy']);
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
						<span style=\"text-decoration:none;\">All Modules&nbsp;</span><span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>
					<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
						<span style=\"text-decoration:none;\">Registry Menu</span>
					</li>";
if ($lgn_num > 0 && $canview === true) {
    if ($pgNo == 0) {
        $cntent .= "
                                       </ul>
                                     </div>" . "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
      <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                    font-weight:normal;\">This is where all EMI Agent Information in the Country are Managed!. The module has the ff areas:</span>
                    </div>
      <p>";
        $grpcntr = 0;
        for ($i = 0; $i < count($menuItems); $i++) {
            $No = $i + 1;
            if ($i < 4) {
                if (test_prmssns($dfltPrvldgs[$i + 1], $mdlNm) == FALSE) {
                    continue;
                }
            }
            if ($grpcntr == 0) {
                $cntent .= "<div class=\"row\">";
            }

            $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block\" style=\"min-height:175px;height:173px;margin-bottom:5px;\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=0');\">
            <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px auto; height:78px; width:auto; position: relative; vertical-align: middle;float:none;\">
            <br/>
            <span class=\"wordwrap3\">" . ($menuItems[$i]) . "</span>
            <br/>&nbsp;
        </button>
            </div>";
            /* $cntent .= "<div class=\"col-md-3 colmd3special2\">
              <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=0');\">
              <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
              <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
              </button>
              </div>"; */

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
        //Agent Profiles   
        require 'agnt_profiles.php';
    } else if ($pgNo == 2) {
        //Terminated Affiliations   
        require "agnt_trmntd.php";
    } else if ($pgNo == 3) {
        //Report Runner   
        require "agnt_suspcs.php";
    } else if ($pgNo == 4) {
        //Report Runner   
        require "agnt_sttngs.php";
    }else if ($pgNo == 5) {
        //Report Runner   
        require "agnt_rpts.php";
    }else if ($pgNo == 6) {
        //Report Runner   
        require "agnt_search.php";
    } else {
        restricted();
    }
} else {
    restricted();
}
?>