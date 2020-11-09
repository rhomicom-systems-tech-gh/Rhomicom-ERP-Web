<?php

$menuItems = array("Summary Dashboard", "Projects", "Account Setups", "Equipment / Resources", "Project Search"
);
$menuImages = array("dashboard220.png", "Project-Management.png", "GL-256.png", "ma-logo.png", "CustomIcon.png");

$mdlNm = "Projects Management";
$ModuleName = $mdlNm;

$dfltPrvldgs = array(
    "View Projects Management",
    /* 1 */ "View Projects", "View Accounts Setup",
    /* 3 */ "View Resources Setup", "View SQL", "View Record History",
    /* 6 */ "Add Projects", "Edit Projects", "Delete Projects",
    /* 9 */ "Add Accounts", "Edit Accounts", "Delete Accounts",
    /* 12 */ "Add Resource", "Edit Resource", "Delete Resource",
    /* 15 */ "View Project Costs", "Edit Project Costs");

$canview = test_prmssns($dfltPrvldgs[0], $ModuleName);

$vwtyp = "0";
$qstr = "";
$dsply = "";
$actyp = "";
$srchFor = "";
$srchIn = "Name";
$PKeyID = -1;
$sortBy = "ID ASC";
if (isset($_POST['PKeyID'])) {
    $PKeyID = cleanInputData($_POST['PKeyID']);
}
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
						<span style=\"text-decoration:none;\">Projects Management Menu</span>
					</li>
                                       ";
if ($lgn_num > 0 && $canview === true) {
    if ($pgNo == 0) {
        $cntent .= "</ul>
                                     </div>" . "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
      <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                    font-weight:normal;\">This module helps you to manage your organization's Projects and their Associated Costs! The module has the ff areas:</span>
                    </div>
      <p>";
        $grpcntr = 0;
        for ($i = 0; $i < count($menuItems); $i++) {
            $No = $i + 1;
            if ($i == 0) {
                
            }
            if ($grpcntr == 0) {
                $cntent .= "<div class=\"row\">";
            }

            $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=0');\">
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
        require "smmry_dshbrd.php";
    } else if ($pgNo == 2) {
        require "projects.php";
    } else if ($pgNo == 3) {
        require "acnt_setups.php";
    } else if ($pgNo == 4) {
        require "equip_rsrcs.php";
    } else if ($pgNo == 5) {
        require "proj_srch.php";
    } else {
        restricted();
    }
}
?>
