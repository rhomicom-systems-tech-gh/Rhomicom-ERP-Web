<?php
$mdlACAorPMS = isset($_POST['mdl']) ? cleanInputData($_POST['mdl']) : "ACA";
$vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
$vPsblVal1 = getPssblValDesc($vPsblValID1);
$vPsblValID3 = -1;
$vPsblVal3 = "";
$canAdd = true;
$canEdt = true;
$canDel = true;
$canVoid = false;
$canApprove = false;
$canVwRcHstry = false;
$menuItems = array("Summary Financial Info.", "Registration", "Results", "Virtual Classroom Platform");
$menuImages = array("invcBill.png", "settings.png", "dashboard220.png", "moodle-logo.jpg");
$menuLinks = array(
    "grp=80&typ=1&vtyp=0", "grp=110&typ=1&vtyp=0&mdl=$mdlACAorPMS",
    "grp=110&typ=1&vtyp=10&mdl=$mdlACAorPMS", $vPsblVal3
);
$vwtyp1 = 0;
//echo $vwtyp1;
$mdlNm = "Learning/Performance Management";
$ModuleName = $mdlNm;
$pageHtmlID = "prsnDataPage";
$canview = test_prmssns("View Self-Service", "Self Service");

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$crntOrgName = getOrgName($orgID);
$usrID = $_SESSION['USRID'];
$uName = $_SESSION['UNAME'];

$vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "999";
$qstr = "";
$dsply = "";
$actyp = "";
$srchFor = "";
$srchIn = "Name";
$PKeyID = -1;
$fltrTypValue = "All";
$fltrTyp = "Relation Type";
$gnrlTrnsDteDMYHMS = getFrmtdDB_Date_time();
$gnrlTrnsDteYMDHMS = cnvrtDMYTmToYMDTm($gnrlTrnsDteDMYHMS);
$gnrlTrnsDteYMD = substr($gnrlTrnsDteYMDHMS, 0, 10);
$gnrlTrnsDteDMY = substr($gnrlTrnsDteDMYHMS, 0, 11);

$acaPrmSnsRstl = getAcaPgPrmssns($prsnid, $orgID, $usrID);
$fnccurid = $acaPrmSnsRstl[0];
$fnccurnm = getPssblValNm($fnccurid);
$brnchLocID = $acaPrmSnsRstl[1];
$brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $brnchLocID);
$acsCntrlGrpID = $acaPrmSnsRstl[2];
/* 
  $accbFSRptStoreID = isset($_POST['accbFSRptStoreID']) ? (int) cleanInputData($_POST['accbFSRptStoreID']) : -1;
  $selectedStoreID = (int) $_SESSION['SELECTED_SALES_STOREID'];
  if ($accbFSRptStoreID > 0 && getUserStorePkeyID($accbFSRptStoreID) > 0) {
  $selectedStoreID = $accbFSRptStoreID;
  $_SESSION['SELECTED_SALES_STOREID'] = $selectedStoreID;
  }
  if ($selectedStoreID <= 0) {
  $selectedStoreID = $acaPrmSnsRstl[3];
  $_SESSION['SELECTED_SALES_STOREID'] = $selectedStoreID;
  }
  $acsCntrlGrpNm = getStoreNm($selectedStoreID); 
*/
$canview = ($acaPrmSnsRstl[4] >= 1) ? true : false;
$canViewAcdmcs = ($acaPrmSnsRstl[5] >= 1) ? true : false;
$canViewAssSht = ($acaPrmSnsRstl[6] >= 1) ? true : false;
$canViewAsgnmnt = ($acaPrmSnsRstl[7] >= 1) ? true : false;
$canViewGroups = ($acaPrmSnsRstl[8] >= 1) ? true : false;
$canViewPositns = ($acaPrmSnsRstl[9] >= 1) ? true : false;
$canViewPeriods = ($acaPrmSnsRstl[10] >= 1) ? true : false;
$canViewRptTyps = ($acaPrmSnsRstl[11] >= 1) ? true : false;
$vwOnlySelfShts =  ($acaPrmSnsRstl[12] >= 1) ? true : false;
$vwOnlySelfRpts =  ($acaPrmSnsRstl[13] >= 1) ? true : false;

$canVwRcHstry = ($acaPrmSnsRstl[17] >= 1) ? true : false;
$canViewSQL = ($acaPrmSnsRstl[18] >= 1) ? true : false;
$courseLabelACA = $acaPrmSnsRstl[19];
$sbjctLabelACA = $acaPrmSnsRstl[20];
$groupLabelACA = $acaPrmSnsRstl[21];
$customMdlNm = $acaPrmSnsRstl[22];
$courseLabelPMS = $acaPrmSnsRstl[23];
$sbjctLabelPMS = $acaPrmSnsRstl[24];
$groupLabelPMS = $acaPrmSnsRstl[25];

$courseLabel = ($mdlACAorPMS == "PMS") ? $courseLabelPMS : $courseLabelACA;
$sbjctLabel = ($mdlACAorPMS == "PMS") ? $sbjctLabelPMS : $sbjctLabelACA;
$groupLabel = ($mdlACAorPMS == "PMS") ? $groupLabelPMS : $groupLabelACA;

$courseLOV = "ACA Courses";
$sbjctLOV = "ACA Subjects";
$moduleType = "Course";
$moduleType2 = "Subject";
$moduleType2Wght = "Credit Hours";
$canViewrpts = true;
$courseLabelAddon = "Registration";
$menuItems[2] = "Report Cards";
$menuItems[1] = "My Registrations";
$breadCrumbNm = "My Academics";
$breadCrumbNm2 = "Academics Portal";
if ($mdlACAorPMS == "PMS") {
    $courseLOV = "PMS Objectives";
    $sbjctLOV = "PMS Tasks";
    $moduleType = "Objective";
    $moduleType2 = "Task";
    $courseLabelAddon = "Assignment";
    $moduleType2Wght = "Weight";
    $menuItems[2] = "Score Cards";
    $menuItems[1] = "My Task Assignments";
    $breadCrumbNm = "My Appraisals";
    $breadCrumbNm2 = "Appraisal Portal";
    if (strpos($customMdlNm, "Academ") !== FALSE) {
        $customMdlNm = "Performance Appraisal System";
    }
    if ($courseLabel == "") {
        $courseLabel = "Objective";
    }
    if ($sbjctLabel == "") {
        $sbjctLabel = "Task";
    }
    if ($groupLabel == "") {
        $groupLabel = "Department";
    }
}

if ($courseLabel == "") {
    $courseLabel = "Programme";
}

if ($sbjctLabel == "") {
    $sbjctLabel = "Subject";
}

if ($groupLabel == "") {
    $groupLabel = "Class";
}

$courseLabels = getStrPlural($courseLabel);
$sbjctLabels = getStrPlural($sbjctLabel);
$groupLabels = getStrPlural($groupLabel);


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
if (isset($_POST['actyp'])) {
    $actyp = cleanInputData($_POST['actyp']);
}
if (isset($_POST['fltrTypValue'])) {
    $fltrTypValue = cleanInputData($_POST['fltrTypValue']);
}
if (isset($_POST['fltrTyp'])) {
    $fltrTyp = cleanInputData($_POST['fltrTyp']);
}
$qShwCrntOnly = true;
if (isset($_POST['qShwCrntOnly'])) {
    $qShwCrntOnly = cleanInputData($_POST['qShwCrntOnly']) === "true" ? true : false;
}
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "ID ASC";
$curIdx = 0;
$assessTypeFltr = "Summary Report Per Person";
$exprtFileNmPrt = "AssessSheets";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($qstr == "DELETE") {
        if ($actyp == 1) {
        } else if ($actyp == 2) {
            /* Delete Programme Registration */
            $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
            $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
            if ($canDel) {
                echo delete_AcaRgstratn($pKeyID, $pKeyNm);
            } else {
                restricted();
            }
        } else if ($actyp == 3) {
            /* Delete Course Subject */
            $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
            $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
            if ($canDel) {
                echo delete_AcaRgstratnbjcts($pKeyID, $pKeyNm);
            } else {
                restricted();
            }
        }
    } else if ($qstr == "UPDATE") {
        if ($actyp == 1) {
        } else if ($actyp == 11) {
            //Assessment Courses
            /* Course/Programme/Objective */
            $acaRgstrClassPkeyID = isset($_POST['acaRgstrClassPkeyID']) ? (float) cleanInputData($_POST['acaRgstrClassPkeyID']) : -1;
            $sbmtdRgstrPersonID = isset($_POST['sbmtdRgstrPersonID']) ? (float) cleanInputData($_POST['sbmtdRgstrPersonID']) : -1;
            $srcForm = isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;

            $acaRgstrClassID = isset($_POST['acaRgstrClassID']) ? (int) cleanInputData($_POST['acaRgstrClassID']) : -1;
            $acaRgstrCrseID = isset($_POST['acaRgstrCrseID']) ? (int) cleanInputData($_POST['acaRgstrCrseID']) : -1;
            $acaRgstrPrdID = isset($_POST['acaRgstrPrdID']) ? (int) cleanInputData($_POST['acaRgstrPrdID']) : -1;

            $acaRgstrClassNm = isset($_POST['acaRgstrClassNm']) ? cleanInputData($_POST['acaRgstrClassNm']) : "";
            $acaRgstrCrseName = isset($_POST['acaRgstrCrseName']) ? cleanInputData($_POST['acaRgstrCrseName']) : "";
            $acaRgstrPrdName = isset($_POST['acaRgstrPrdName']) ? cleanInputData($_POST['acaRgstrPrdName']) : "";
            $acaSttngsPrdStrtDte = "";
            $acaSttngsPrdEndDte = "";

            $exitErrMsg = "";
            if ($acaRgstrClassID <= 0) {
                $exitErrMsg .= "Please select Assessment Group!<br/>";
            }
            if ($acaRgstrCrseID <= 0) {
                $exitErrMsg .= "Please select Assessment Programmer/Objective!<br/>";
            }
            if ($acaRgstrPrdID <= 0) {
                $exitErrMsg .= "Please select Assessment Period!<br/>";
            }
            $cntrRndm = getRandomNum(5000, 9999);
            $affctRws = 0;
            $affct = 0;
            if ($sbmtdRgstrPersonID > 0 && $acaRgstrClassID > 0 && $acaRgstrCrseID > 0 && $acaRgstrPrdID > 0) {
                $oldPKey = get_AcaRgstratnID($sbmtdRgstrPersonID, $acaRgstrClassID, $acaRgstrCrseID, $acaRgstrPrdID);
                if ($acaRgstrClassPkeyID > 0 && ($oldPKey == $acaRgstrClassPkeyID || $oldPKey <= 0)) {
                    $affctRws += update_AcaRgstratn($acaRgstrClassPkeyID, $sbmtdRgstrPersonID, $acaRgstrClassID, $acaRgstrCrseID, $acaRgstrPrdID);
                } else {
                    $acaRgstrClassPkeyID = getNew_AcaRgstratnID();
                    $affctRws += create_AcaRgstratn($acaRgstrClassPkeyID, $sbmtdRgstrPersonID, $acaRgstrClassID, $acaRgstrCrseID, $acaRgstrPrdID);
                }
                $pDivGrpDivID = (int)getGnrlRecNm("aca.aca_classes", "class_id", "lnkd_div_id", $acaRgstrClassID);
                $oldDivGrpPKey = getPDivGrpID($sbmtdRgstrPersonID, $pDivGrpDivID);
                if ($pDivGrpDivID > 0) {
                    $pDivGrpStartDate = getGnrlRecNm("aca.aca_assessment_periods", "assmnt_period_id", "to_char(to_timestamp(period_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY')", $acaRgstrPrdID);
                    $pDivGrpEndDate = getGnrlRecNm("aca.aca_assessment_periods", "assmnt_period_id", "to_char(to_timestamp(period_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY')", $acaRgstrPrdID);
                    if ($oldDivGrpPKey > 0) {
                        $affctRws = updatePDivGrp($oldDivGrpPKey, $pDivGrpDivID, $pDivGrpStartDate, $pDivGrpEndDate);
                    } else {
                        $affctRws = createPDivGrp($sbmtdRgstrPersonID, $pDivGrpDivID, $pDivGrpStartDate, $pDivGrpEndDate);
                    }
                }
                
                $acaPrdTyp = getGnrlRecNm("aca.aca_assessment_periods", "assmnt_period_id", "period_type", $acaRgstrPrdID);
                $acaPrdNum = (int) getGnrlRecNm("aca.aca_assessment_periods", "assmnt_period_id", "period_number", $acaRgstrPrdID);
                $ttlSbjcts = get_TtlAcaSttngsSbjcts($acaRgstrClassPkeyID, "Name", "%");
                if ($ttlSbjcts <= 0) {
                    $sbjctRslt = get_AcaClasseSbjcts($acaRgstrClassID, $acaRgstrCrseID, 0, 1000000, "Name", "%");
                    while ($sbjctRw = loc_db_fetch_array($sbjctRslt)) {
                        $acaRgstrSbjctID = (int) $sbjctRw[2];
                        $acaRgstrSbjctWght = (float) $sbjctRw[5];
                        $acaRgstrCoreElect = $sbjctRw[4];
                        $acaRgstrSbjctEnbld = $sbjctRw[8];
                        $acaRgstrPrdTyp = $sbjctRw[10];
                        $acaRgstrPrdNum = (int) $sbjctRw[11];
                        $oldPKey = get_AcaRgstratnbjctID($acaRgstrSbjctID, $acaRgstrClassPkeyID);
                        if ($oldPKey <= 0 && (($acaRgstrPrdNum == $acaPrdNum && $acaRgstrPrdTyp == $acaPrdTyp) || $acaRgstrPrdTyp == "")) {
                            $acaRgstrSbjctPkeyID = getNew_AcaRgstratnbjctID();
                            $affct += create_AcaRgstratnbjcts($acaRgstrSbjctPkeyID, $acaRgstrClassPkeyID, $acaRgstrSbjctID);
                        }
                    }
                }
                if (($affctRws + $affct) > 0) {
                    $cntr = $cntrRndm;
?>
                    <tr id="oneAcaRgstratnCrsesRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAcaRgstratnCrsesTable tr').index(this));">
                        <td class="lovtd">
                            <span><?php echo ($cntr); ?></span>
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_LnID" value="<?php echo $acaRgstrClassPkeyID; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_ClassID" value="<?php echo $acaRgstrClassID; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_CrseID" value="<?php echo $acaRgstrCrseID; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_PrdID" value="<?php echo $acaRgstrPrdID; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_ClassNm" value="<?php echo $acaRgstrClassNm; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_CrseName" value="<?php echo $acaRgstrCrseName; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_PrdNm" value="<?php echo $acaRgstrPrdName; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $acaSttngsPrdStrtDte; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_EndDte" value="<?php echo $acaSttngsPrdEndDte; ?>" style="width:100% !important;">
                        </td>
                        <td class="lovtd" style="">
                            <span><?php echo $acaRgstrClassNm; ?></span>
                        </td>
                        <td class="lovtd" style="">
                            <span><?php echo $acaRgstrCrseName; ?></span>
                        </td>
                        <td class="lovtd" style="">
                            <span><?php echo $acaRgstrPrdName; ?></span>
                        </td>
                        <td class="lovtd" style="text-align: center;">
                            <button type="button" class="btn btn-default btn-sm" id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_CrsesBtn" onclick="getAcaRgstratnClassForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'acaEdtRgstrClassForm', 'oneAcaRgstratnCrsesRow_<?php echo $cntr; ?>', 'Edit <?php echo $courseLabel; ?>', 3, 'EDIT', <?php echo $acaRgstrClassPkeyID; ?>, <?php echo $sbmtdRgstrPersonID; ?>);" style="padding:2px !important;" title="Edit <?php echo $courseLabel; ?>">
                                <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                        </td>
                        <td class="lovtd" style="text-align: center;">
                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaRgstratnLne('oneAcaRgstratnCrsesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete <?php echo $courseLabel; ?>">
                                <img src="../cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                        </td>
                    </tr>
                <?php
                } else {
                    echo "RHO-ERROR:Failed to Save any Data!";
                }
            } else {
                echo "RHO-ERROR:Invalid or Incomplete Data!";
            }
        } else if ($actyp == 12) {
            //Assessment Tasks
            /* Suject/Task */
            $sttngsSbjctPkeyID = isset($_POST['sttngsSbjctPkeyID']) ? (float) cleanInputData($_POST['sttngsSbjctPkeyID']) : -1;
            $srcForm = isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            $acaRgstratnSttngsID = isset($_POST['acaRgstratnSttngsID']) ? (float) cleanInputData($_POST['acaRgstratnSttngsID']) : -1;

            $sttngsSbjctID = isset($_POST['sttngsSbjctID']) ? (float) cleanInputData($_POST['sttngsSbjctID']) : -1;
            $sttngsSbjctName = isset($_POST['sttngsSbjctName']) ? cleanInputData($_POST['sttngsSbjctName']) : "";
            $cntrRndm = getRandomNum(5000, 9999);
            $affctRws = 0;
            if ($acaRgstratnSttngsID > 0 && $sttngsSbjctName != "") {
                $oldPKey = get_AcaRgstratnbjctID($sttngsSbjctID, $acaRgstratnSttngsID);
                if ($sttngsSbjctPkeyID > 0 && ($oldPKey == $sttngsSbjctPkeyID || $oldPKey <= 0)) {
                    $affctRws += update_AcaRgstratnbjcts($sttngsSbjctPkeyID, $acaRgstratnSttngsID, $sttngsSbjctID);
                } else if ($oldPKey <= 0 && $sttngsSbjctPkeyID <= 0) {
                    $sttngsSbjctPkeyID = getNew_AcaRgstratnbjctID();
                    $affctRws += create_AcaRgstratnbjcts($sttngsSbjctPkeyID, $acaRgstratnSttngsID, $sttngsSbjctID);
                }
                $sttngsSbjctLnID = -1;
                $sttngsSbjctLnNm = $sttngsSbjctName;
                $clssSbjctCoreElect = "";
                $clssSbjctWeight = 0;
                if ($affctRws > 0) {
                    $cntr = $cntrRndm;
                ?>
                    <tr id="oneAcaRgstratnSbjctsRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAcaRgstratnSbjctsTable tr').index(this));">
                        <td class="lovtd"><span>New</span>
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_SbjctLnID" value="<?php echo $sttngsSbjctLnID; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_SbjctID" value="<?php echo $sttngsSbjctID; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_SbjctNm" value="<?php echo $sttngsSbjctLnNm; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_SbjctType" value="<?php echo $clssSbjctCoreElect; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_Weight" value="<?php echo $clssSbjctWeight; ?>" style="width:100% !important;">
                        </td>
                        <td class="lovtd" style="">
                            <span><?php echo $sttngsSbjctLnNm; ?></span>
                        </td>
                        <td class="lovtd">
                            <span><?php echo $clssSbjctCoreElect; ?></span>
                        </td>
                        <td class="lovtd">
                            <span><?php echo $clssSbjctWeight; ?></span>
                        </td>
                        <td class="lovtd" style="text-align: center;">
                            <button type="button" class="btn btn-default btn-sm" id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_SbjctsBtn" onclick="getAcaRgstratnSbjctForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'acaEdtCourseSbjctForm', 'oneAcaRgstratnSbjctsRow_<?php echo $cntr; ?>', 'Edit <?php echo $courseLabel; ?>', 4, 'EDIT', <?php echo $sttngsSbjctLnID; ?>, <?php echo $acaRgstratnSttngsID; ?>);" style="padding:2px !important;" title="Edit <?php echo $sbjctLabel; ?>">
                                <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                        </td>
                        <td class="lovtd" style="text-align: center;">
                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaRgstratnSbjcts('oneAcaRgstratnSbjctsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete <?php echo $sbjctLabel; ?>">
                                <img src="../cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                        </td>
                    </tr>
        <?php
                } else {
                    echo "RHO-ERROR:Failed to Save any Data!";
                }
            } else {
                echo "RHO-ERROR:Invalid or Incomplete Data!";
            }
        }
    } else if ($vwtyp == "999") {
        ?>
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><?php echo $breadCrumbNm2; ?></h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                            <li class="breadcrumb-item active"><?php echo $breadCrumbNm; ?></li>
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
                $grpcntr = 0;
                $cntent = "";
                for ($i = 0; $i < count($menuItems); $i++) {
                    $No = $i + 1;
                    if ($mdlACAorPMS == "PMS" && ($i == 0 || $i == 3)) {
                        continue;
                    }

                    if ($grpcntr == 0) {
                        $cntent .= "<div class=\"row\">";
                    }
                    if ($mdlACAorPMS == "ACA" && $i == 3) {
                        $vPsblValID3 = getEnbldPssblValID("vClass URL", getLovID("All Other Performance Setups"));
                        $vPsblVal3 = getPssblValDesc($vPsblValID3);
                        if (trim($vPsblVal3) == "") {
                            $vPsblVal3 = "https://vclass.rhomicom.com";
                        }
                        $cntent .= "<div class=\"col-md-3 colmd3special2\">
            <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"window.open('" . $vPsblVal3 . "','_blank');\">
                <img src=\"../cmn_images/" . $menuImages[$i] . "\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
                <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
            </button>
                </div>";
                    } else {
                        $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', '" . $menuLinks[$i] . "');\">
            <img src=\"../cmn_images/" . $menuImages[$i] . "\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
        </button>
            </div>";
                    }
                    if ($grpcntr == 3) {
                        $cntent .= "</div>";
                        $grpcntr = 0;
                    } else {
                        $grpcntr = $grpcntr + 1;
                    }
                }
                echo $cntent;
                ?>
            </div>
        </section>
        <?php
    } else if ($vwtyp == 0 || $vwtyp == 1) {
        $vPsblValID2 = -1;
        $vPsblVal2 = "";
        if ($mdlACAorPMS == "ACA") {
            $vPsblValID2 = getEnbldPssblValID("ACA Registration Eligibility Criteria SQL (YES/NO)", getLovID("All Other Performance Setups"));
            $vPsblVal2 = getPssblValDesc($vPsblValID2);
        } else {
            $vPsblValID2 = getEnbldPssblValID("PMS Registration Eligibility Criteria SQL (YES/NO)", getLovID("All Other Performance Setups"));
            $vPsblVal2 = getPssblValDesc($vPsblValID2);
        }
        //isset($_POST['sbmtdAcaSttngsPrsnID']) ? $_POST['sbmtdAcaSttngsPrsnID'] : 
        $pkID = $prsnid;
        $actionTxt = isset($_POST['actionTxt']) ? $_POST['actionTxt'] : "PasteDirect";
        $destElmntID = isset($_POST['destElmntID']) ? $_POST['destElmntID'] : "acaRgstratnDetailInfo";
        $titleMsg = isset($_POST['titleMsg']) ? $_POST['titleMsg'] : "";
        $titleElementID = isset($_POST['titleElementID']) ? $_POST['titleElementID'] : "";
        $modalBodyID = isset($_POST['modalBodyID']) ? $_POST['modalBodyID'] : "";
        $sbmtdAcaSttngsID = isset($_POST['sbmtdAcaSttngsID']) ? $_POST['sbmtdAcaSttngsID'] : -1;
        $acaRgstratnSttngsID = $sbmtdAcaSttngsID;
        $acaRgstratnSbjctID = -1;
        $cntrRndm = getRandomNum(5000, 9999);
        $cntr = 0;
        $colClassType1 = "col-lg-2";
        $colClassType2 = "col-lg-6";
        $colClassType3 = "col-lg-4";
        if ($vwtyp == 0) {
        ?>
            <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><?php echo $menuItems[1]; ?></h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                                <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=110&typ=1&mdl=<?php echo $mdlACAorPMS; ?>');"><?php echo $breadCrumbNm; ?></a></li>
                                <li class="breadcrumb-item active"><?php echo str_replace("My ", "", $menuItems[1]); ?></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php
                    $errMsg = "";
                    if ($vPsblVal2 != "") {
                        if (!isItmValSQLValid($vPsblVal2, $prsnid, $orgID, $gnrlTrnsDteDMYHMS, -1, -1, $errMsg)) {
                            $errMsg .= "NO:SQL is NOT valid!";
                            //$ln_ValSQL = "Select 0";
                        }
                    }
                    if (strpos($errMsg, "NO:") !== FALSE) {
                        echo "<div style=\"padding:50px;color:red;font-weight:bold;font-size:20px;font-family:georgia,times;font-style:italic;\">" . str_replace("NO:", "", $errMsg) . "</div>";
                        echo "</div></section>";
                        exit();
                    } ?>
                    <form id='acaRgstratnForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row " style="margin-bottom:5px;padding:10px 15px 15px 15px !important;border-bottom:1px solid #ddd;">
                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;float:left;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label" title="Only <?php echo $courseLabelAddon . "s"; ?> in Current Period">
                                        <?php
                                        $shwCrntOnlyChkd = "";
                                        if ($qShwCrntOnly == true) {
                                            $shwCrntOnlyChkd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getOneAcaRgstratnForm(-1, 0, 'PasteDirect', 'allmodules', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');" id="acaRgstratnShwCrntOnly" name="acaRgstratnShwCrntOnly" <?php echo $shwCrntOnlyChkd; ?>>
                                        Show only Current/Active <?php echo $courseLabelAddon . "s"; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row " style="margin-bottom:5px;padding:2px 7.5px 2px 7.5px !important;border-bottom:1px solid #ddd;">
                            <div class="container-fluid" id="acaRgstratnDetailInfo">
                                <?php
                                $acaRgstratnPrsID = -1;
                                $acaRgstratnPrsLocID = "";
                                $acaRgstratnPrsName = "";
                                $acaRgstratnPrsContacts = "";
                                $acaRgstratnPrsType = "";
                                $acaRgstratnPrsEmail = "";
                                if ($pkID > 0) {
                                    $acaRgstratnPrsID = $pkID;
                                    $result1 = getAcaPrsnInfo($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $acaRgstratnPrsID = (float) $row1[0];
                                        $acaRgstratnPrsLocID = $row1[1];
                                        $acaRgstratnPrsName = $row1[2];
                                        $acaRgstratnPrsContacts = trim($row1[6] . ", " . $row1[7], ", ");
                                        $acaRgstratnPrsType = $row1[10];
                                        $acaRgstratnPrsEmail = $row1[5];
                                    }
                                }
                                ?>
                                <div class="row" style="padding-top: 10px !important;margin-bottom:5px !important;border-radius:5px;border:1px solid #eee;">
                                    <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                        <div class="form-group" style="padding:0px 0px 0px 0px !important;">
                                            <label for="acaRgstratnPrsLocID" class="control-label col-lg-4">ID No.:</label>
                                            <div class="col-lg-8">
                                                <input type="hidden" class="form-control" aria-label="..." id="acaRgstratnPrsID" name="acaRgstratnPrsID" value="<?php echo $acaRgstratnPrsID; ?>">
                                                <?php
                                                if ($canEdt === true && $pkID <= 0) {
                                                ?>
                                                    <div class="input-group">
                                                        <input class="form-control rqrdFld" id="acaRgstratnPrsLocID" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" value="<?php echo $acaRgstratnPrsLocID; ?>" />
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaRgstratnPrsID', 'acaRgstratnPrsLocID', 'clear', 1, '', function () {});">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                <?php } else {
                                                ?>
                                                    <span><?php echo $acaRgstratnPrsLocID; ?></span>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group" style="padding:0px 0px 0px 0px !important;">
                                            <label for="acaRgstratnPrsName" class="control-label col-lg-4">Full Name:</label>
                                            <div class="col-lg-8">
                                                <span><?php echo $acaRgstratnPrsName; ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group" style="padding:0px 0px 0px 0px !important;">
                                            <label for="acaRgstratnPrsContacts" class="control-label col-lg-4">Contact Nos.:</label>
                                            <div class="col-lg-8">
                                                <span><?php echo $acaRgstratnPrsContacts; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                        <div class="form-group" style="padding:0px 0px 0px 0px !important;">
                                            <label for="acaRgstratnPrsType" class="control-label col-lg-4">Person Type:</label>
                                            <div class="col-lg-8">
                                                <span><?php echo $acaRgstratnPrsType; ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group" style="padding:0px 0px 0px 0px !important;">
                                            <label for="acaRgstratnPrsEmail" class="control-label col-lg-4">Email:</label>
                                            <div class="col-lg-8">
                                                <span><?php echo $acaRgstratnPrsEmail; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;float:left;">
                                        <?php
                                        $acaRgstratnSttngsID = $sbmtdAcaSttngsID;
                                        $acaRgstratnSbjctID = -1;
                                        $resultRw = get_AcaSttngsClasses($acaRgstratnPrsID, 0, 10000, "Name", "%", $qShwCrntOnly);

                                        if ($canEdt === true && $acaRgstratnPrsID > 0) {
                                        ?>
                                            <button id="addNwAcaRgstratnCrseBtn" type="button" class="btn btn-default" style="margin-bottom:2px;" onclick="getAcaRgstratnClassForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'acaEdtRgstrClassForm', 'oneAcaRgstratnCrsesRow_<?php echo $cntrRndm; ?>', 'Add <?php echo $courseLabel; ?>', 3, 'ADD', -1, <?php echo $acaRgstratnPrsID; ?>);" data-toggle="tooltip" data-placement="bottom" title="New <?php echo $courseLabel; ?> for <?php echo $groupLabel; ?>">
                                                <img src="../cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add <?php echo $courseLabel; ?>
                                            </button>
                                            <!--<button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAcaRgstratnForm('PasteDirect', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');"><img src="../cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>-->
                                        <?php } ?>
                                        <button type="button" class="btn btn-default" style="margin-bottom:2px;" onclick="getOneAcaRgstratnForm(<?php echo $acaRgstratnPrsID; ?>, 0, 'PasteDirect', 'allmodules', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');">
                                            <img src="../cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 2px;" onclick="printEmailFullRgstrSlp(<?php echo $acaRgstratnPrsID; ?>);" data-toggle="tooltip" data-placement="bottom" title="Print Registration Slip">
                                            <img src="../cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Print
                                        </button>
                                    </div>
                                    <div class="col-md-12" id="acaRgstratnDetLines" style="padding:0px!important;">
                                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                            <table class="table table-striped table-bordered handCursor" id="oneAcaRgstratnCrsesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                <thead>
                                                    <tr>
                                                        <th style="">No.</th>
                                                        <th style="min-width:80px;"><?php echo $groupLabel; ?> Name</th>
                                                        <th style="min-width:80px;"><?php echo $courseLabel; ?> Code/Name</th>
                                                        <th style="min-width:60px;">Period</th>
                                                        <th style="text-align: center;">...</th>
                                                        <th style="text-align: center;">...</th>
                                                        <th style="text-align: center;">...</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $mkReadOnly = "";
                                                    $cntr = 0;
                                                    while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                        $acaSttngsLineID = (float) $rowRw[0];
                                                        $acaSttngsClassID = (float) $rowRw[1];
                                                        $acaSttngsCourseID = (float) $rowRw[2];
                                                        $acaSttngsPeriodID = (float) $rowRw[5];
                                                        if ($cntr == 0) {
                                                            $acaRgstratnSttngsID = (float) $rowRw[0];
                                                        }
                                                        $acaSttngsClssNm = $rowRw[4];
                                                        $acaSttngsCrseNm = $rowRw[3];
                                                        $acaSttngsPrdNm = $rowRw[6];
                                                        $acaSttngsPrdStrtDte = $rowRw[7];
                                                        $acaSttngsPrdEndDte = $rowRw[8];
                                                        $cntr += 1;
                                                    ?>
                                                        <tr id="oneAcaRgstratnCrsesRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAcaRgstratnCrsesTable tr').index(this));">
                                                            <td class="lovtd">
                                                                <span><?php echo ($cntr); ?></span>
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_LnID" value="<?php echo $acaSttngsLineID; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_ClassID" value="<?php echo $acaSttngsClassID; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_CrseID" value="<?php echo $acaSttngsCourseID; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_PrdID" value="<?php echo $acaSttngsPeriodID; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_ClassNm" value="<?php echo $acaSttngsClssNm; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_CrseName" value="<?php echo $acaSttngsCrseNm; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_PrdNm" value="<?php echo $acaSttngsPrdNm; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $acaSttngsPrdStrtDte; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_EndDte" value="<?php echo $acaSttngsPrdEndDte; ?>" style="width:100% !important;">
                                                            </td>
                                                            <td class="lovtd" style="">
                                                                <span><?php echo $acaSttngsClssNm; ?></span>
                                                            </td>
                                                            <td class="lovtd" style="">
                                                                <span><?php echo $acaSttngsCrseNm; ?></span>
                                                            </td>
                                                            <td class="lovtd" style="">
                                                                <span><?php echo $acaSttngsPrdNm; ?></span>
                                                            </td>
                                                            <td class="lovtd" style="text-align: center;">
                                                                <button type="button" class="btn btn-default" style="padding:2px !important;" onclick="printEmailFullRgstrSlp(<?php echo $acaRgstratnPrsID; ?>,<?php echo $acaSttngsLineID; ?>);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Print Registration Slip">
                                                                    <img src="../cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                            <td class="lovtd" style="text-align: center;">
                                                                <button type="button" class="btn btn-default btn-sm" id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_CrsesBtn" onclick="getAcaRgstratnClassForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'acaEdtRgstrClassForm', 'oneAcaRgstratnCrsesRow_<?php echo $cntr; ?>', 'Edit <?php echo $courseLabel; ?>', 3, 'EDIT', <?php echo $acaSttngsLineID; ?>, <?php echo $acaRgstratnPrsID; ?>);" style="padding:2px !important;" title="Edit <?php echo $courseLabel; ?>">
                                                                    <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                            <td class="lovtd" style="text-align: center;">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaRgstratnLne('oneAcaRgstratnCrsesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete <?php echo $courseLabel; ?>">
                                                                    <img src="../cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th style="">&nbsp;</th>
                                                        <th style="">&nbsp;</th>
                                                        <th style="">&nbsp;</th>
                                                        <th style="">&nbsp;</th>
                                                        <th style="">&nbsp;</th>
                                                        <th style="">&nbsp;</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="col-md-12" id="acaRgstratnSbjctsDetailInfo" style="padding:0px 0px 0px 0px !important;">
                                        <?php
                                    } ?>
                                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;float:left;">
                                            <?php
                                            if ($canEdt === true && $acaRgstratnSttngsID > 0) {
                                            ?>
                                                <button id="addNwScmAcaRgstratnSbjctBtn" type="button" class="btn btn-default" style="margin-bottom: 2px;" onclick="getAcaRgstratnSbjctForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'acaEdtCourseSbjctForm', 'oneAcaRgstratnSbjctsRow_<?php echo $cntrRndm; ?>', 'Add <?php echo $courseLabel; ?>', 4, 'ADD', <?php echo $acaRgstratnSbjctID; ?>, <?php echo $acaRgstratnSttngsID; ?>);" data-toggle="tooltip" data-placement="bottom" title="New <?php echo $sbjctLabel; ?> for <?php echo $groupLabel; ?>">
                                                    <img src="../cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add <?php echo $sbjctLabel; ?>
                                                </button>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;float:left;">
                                            <table class="table table-striped table-bordered" id="oneAcaRgstratnSbjctsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                <thead>
                                                    <tr>
                                                        <th style="">No.</th>
                                                        <th style="min-width:120px;"><?php echo $sbjctLabel; ?> Code/Name</th>
                                                        <th style="">Core/ Elective</th>
                                                        <th style=""><?php echo $moduleType2Wght; ?></th>
                                                        <th style="text-align: center;">...</th>
                                                        <th style="text-align: center;">...</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $resultRw2 = get_AcaSttngsSbjcts($acaRgstratnSttngsID, 0, 10000, "Name", "%");
                                                    $mkReadOnly = "";
                                                    $cntr = 0;
                                                    $curIdx = 0;
                                                    $ttlWeight = 0;
                                                    while ($rowRw = loc_db_fetch_array($resultRw2)) {
                                                        $sttngsSbjctLnID = (float) $rowRw[0];
                                                        $sttngsSbjctID = (float) $rowRw[1];
                                                        $sttngsSbjctLnNm = $rowRw[4];
                                                        $clssSbjctCoreElect = $rowRw[2];
                                                        $clssSbjctWeight = (float) $rowRw[3];
                                                        $ttlWeight += $clssSbjctWeight;
                                                        $cntr += 1;
                                                    ?>
                                                        <tr id="oneAcaRgstratnSbjctsRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAcaRgstratnSbjctsTable tr').index(this));">
                                                            <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_SbjctLnID" value="<?php echo $sttngsSbjctLnID; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_SbjctID" value="<?php echo $sttngsSbjctID; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_SbjctNm" value="<?php echo $sttngsSbjctLnNm; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_SbjctType" value="<?php echo $clssSbjctCoreElect; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_Weight" value="<?php echo $clssSbjctWeight; ?>" style="width:100% !important;">
                                                            </td>
                                                            <td class="lovtd" style="">
                                                                <span><?php echo $sttngsSbjctLnNm; ?></span>
                                                            </td>
                                                            <td class="lovtd">
                                                                <span><?php echo $clssSbjctCoreElect; ?></span>
                                                            </td>
                                                            <td class="lovtd">
                                                                <span><?php echo $clssSbjctWeight; ?></span>
                                                            </td>
                                                            <td class="lovtd" style="text-align: center;">
                                                                <button type="button" class="btn btn-default btn-sm" id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_SbjctsBtn" onclick="getAcaRgstratnSbjctForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'acaEdtCourseSbjctForm', 'oneAcaRgstratnSbjctsRow_<?php echo $cntr; ?>', 'Edit <?php echo $courseLabel; ?>', 4, 'EDIT', <?php echo $sttngsSbjctLnID; ?>, <?php echo $acaRgstratnSttngsID; ?>);" style="padding:2px !important;" title="Edit <?php echo $sbjctLabel; ?>">
                                                                    <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                            <td class="lovtd" style="text-align: center;">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaRgstratnSbjcts('oneAcaRgstratnSbjctsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete <?php echo $sbjctLabel; ?>">
                                                                    <img src="../cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th style="">Total <?php echo $moduleType2Wght; ?></th>
                                                        <th style="">&nbsp;</th>
                                                        <th style=""><?php echo $ttlWeight; ?></th>
                                                        <th style="">&nbsp;</th>
                                                        <th style="">&nbsp;</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <?php
                                        if ($vwtyp == 0) { ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        <?php
                                        }
                                    } else if ($vwtyp == 3) {
                                        /* Add Class/Course Form */
                                        $acaRgstrClassPkeyID = isset($_POST['acaRgstrClassPkeyID']) ? cleanInputData($_POST['acaRgstrClassPkeyID']) : -1;
                                        $sbmtdRgstrPersonID = isset($_POST['sbmtdRgstrPersonID']) ? cleanInputData($_POST['sbmtdRgstrPersonID']) : -1;
                                        $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
        ?>
        <form class="form-horizontal" id="acaEdtRgstrClassForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="acaRgstrClassNm" class="control-label col-md-4"><?php echo $groupLabel; ?>:</label>
                        <div class="col-md-8">
                            <div class="input-group" style="width:100%;">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="acaRgstrClassNm" value="">
                                <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstrClassID" value="-1">
                                <input type="hidden" class="form-control rqrdFld" aria-label="..." id="sbmtdRgstrPersonID" value="<?php echo $sbmtdRgstrPersonID; ?>">
                                <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstrClassPkeyID" value="<?php echo $acaRgstrClassPkeyID; ?>">
                                <div class="input-group-append handCursor" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Groups', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaRgstrClassID', 'acaRgstrClassNm', 'clear', 0, '', function () {
                        /*afterCrseSelect();*/
                    });">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="acaRgstrCrseName" class="control-label col-md-4"><?php echo $courseLabel; ?>:</label>
                        <div class="col-md-8">
                            <div class="input-group" style="width:100%;">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="acaRgstrCrseName" value="">
                                <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstrCrseID" value="-1">
                                <div class="input-group-append handCursor" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Objectives/Courses', 'acaRgstrClassID', '', '', 'radio', true, '', 'acaRgstrCrseID', 'acaRgstrCrseName', 'clear', 0, '', function () {
                        /*afterCrseSelect();*/
                    });">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="acaRgstrPrdName" class="control-label col-md-4">Period:</label>
                        <div class="col-md-8">
                            <div class="input-group" style="width:100%;">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="acaRgstrPrdName" value="">
                                <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstrPrdID" value="-1">
                                <div class="input-group-append handCursor" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Periods', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaRgstrPrdID', 'acaRgstrPrdName', 'clear', 0, '', function () {
                        /*afterCrseSelect();*/
                    });">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div style="float:right;padding-right:7.5px !important;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveAcaRgstratnClassForm('myFormsModalx', '<?php echo $acaRgstrClassPkeyID; ?>',<?php echo $sbmtdRgstrPersonID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    <?php
                                    } else if ($vwtyp == 4) {
                                        /* Add Subject Form */
                                        $sttngsSbjctPkeyID = isset($_POST['sttngsSbjctPkeyID']) ? cleanInputData($_POST['sttngsSbjctPkeyID']) : -1;
                                        $acaRgstratnSttngsID = isset($_POST['acaRgstratnSttngsID']) ? cleanInputData($_POST['acaRgstratnSttngsID']) : -1;
                                        $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
                                        $acaRgstratnClassID = (float) getGnrlRecNm("aca.aca_prsns_acdmc_sttngs", "acdmc_sttngs_id", "class_id", $acaRgstratnSttngsID);
                                        $acaRgstratnCrseID = (float) getGnrlRecNm("aca.aca_prsns_acdmc_sttngs", "acdmc_sttngs_id", "course_id", $acaRgstratnSttngsID);
    ?>
        <form class="form-horizontal" id="acaEdtRgstrSbjctForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="crseSbjctCode" class="control-label col-md-4"><?php echo $sbjctLabel; ?>:</label>
                        <div class="col-md-8">
                            <div class="input-group" style="width:100%;">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="sttngsSbjctName" value="">
                                <input type="hidden" class="form-control rqrdFld" aria-label="..." id="sttngsSbjctID" value="-1">
                                <input type="hidden" class="form-control rqrdFld" aria-label="..." id="sttngsSbjctPkeyID" value="<?php echo $sttngsSbjctPkeyID; ?>">
                                <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstratnSttngsID" value="<?php echo $acaRgstratnSttngsID; ?>">
                                <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstratnClassID" value="<?php echo $acaRgstratnClassID; ?>">
                                <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstratnCrseID" value="<?php echo $acaRgstratnCrseID; ?>">
                                <div class="input-group-append handCursor" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Tasks/Subjects', 'acaRgstratnClassID', 'acaRgstratnCrseID', '', 'radio', true, '', 'sttngsSbjctID', 'sttngsSbjctName', 'clear', 0, '', function () {
                    });">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="">
                <div class="col-md-12">
                    <div style="float:right;padding-right:7.5px !important;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveAcaRgstratnSbjctForm('myFormsModalx', '<?php echo $sttngsSbjctPkeyID; ?>',<?php echo $acaRgstratnSttngsID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    <?php
                                    } else if ($vwtyp == 10) {
                                        $canAdd = false;
                                        $canEdt = false;
                                        $vPsblValID2 = -1;
                                        $vPsblVal2 = "";
                                        if ($mdlACAorPMS == "ACA") {
                                            $vPsblValID2 = getEnbldPssblValID("ACA Results Display Criteria SQL (YES/NO)", getLovID("All Other Performance Setups"));
                                            $vPsblVal2 = getPssblValDesc($vPsblValID2);
                                        } else {
                                            $vPsblValID2 = getEnbldPssblValID("PMS Results Display Criteria SQL (YES/NO)", getLovID("All Other Performance Setups"));
                                            $vPsblVal2 = getPssblValDesc($vPsblValID2);
                                        }
                                        //isset($_POST['sbmtdAcaSttngsPrsnID']) ? $_POST['sbmtdAcaSttngsPrsnID'] : 
                                        $pkID = $prsnid;
                                        $error = "";
                                        $searchAll = true;
                                        $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                                        $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                                        $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                                        $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
                                        $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                                        $qShwSelfOnly = true;
                                        if (strpos($srchFor, "%") === FALSE) {
                                            $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                                            $srchFor = str_replace("%%", "%", $srchFor);
                                        } //$shwSelfOnly
                                        $total = get_Total_AssessSheets($srchFor, $srchIn, $orgID, $qShwSelfOnly, $assessTypeFltr);
                                        if ($pageNo > ceil($total / $lmtSze)) {
                                            $pageNo = 1;
                                        } else if ($pageNo < 1) {
                                            $pageNo = ceil($total / $lmtSze);
                                        }
                                        $curIdx = $pageNo - 1;
                                        $result = get_AssessSheets($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwSelfOnly, $assessTypeFltr);
                                        $cntr = 0;
                                        $colClassType1 = "col-md-2";
                                        $colClassType2 = "col-md-4";
                                        $colClassType3 = "col-md-6";

                                        $reportTitle1 = "Auto-Generate and Compute Report/Score Cards";
                                        $reportName1 = "Auto-Generate and Compute Report/Score Cards";
                                        $rptID1 = getRptID($reportName1);
    ?>
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><?php echo $menuItems[2]; ?></h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                            <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=110&typ=1&mdl=<?php echo $mdlACAorPMS; ?>');"><?php echo $breadCrumbNm; ?></a></li>
                            <li class="breadcrumb-item active"><?php echo str_replace("My ", "", $menuItems[2]); ?></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?php
                                        $errMsg = "";
                                        if ($vPsblVal2 != "") {
                                            if (!isItmValSQLValid($vPsblVal2, $prsnid, $orgID, $gnrlTrnsDteDMYHMS, -1, -1, $errMsg)) {
                                                $errMsg .= "NO:SQL is NOT valid!";
                                                //$ln_ValSQL = "Select 0";
                                            }
                                        }
                                        if (strpos($errMsg, "NO:") !== FALSE) {
                                            echo "<div style=\"padding:50px;color:red;font-weight:bold;font-size:20px;font-family:georgia,times;font-style:italic;\">" . str_replace("NO:", "", $errMsg) . "</div>";
                                            echo "</div></section>";
                                            exit();
                                        } ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="rptCardsTabs" style="margin-top:1px !important;" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="reportCardMainListtab" data-toggle="tabajxreportcard" href="#reportCardMainList" role="tab" aria-controls="custom-tabs-two-home" aria-selected="false"><i class="fas fa-user"></i> Summary List</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="reportCardDetListtab" data-toggle="tabajxreportcard" href="#reportCardDetList" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false"><i class="far fa-user"></i> Detailed List</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body" style="padding:0px !important;min-height: 30px !important;">
                                <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                    <div id="reportCardMainList" class="tab-pane fadein active" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                        <form id='reportCardsForm' action='' method='post' accept-charset='UTF-8'>
                                            <!--ROW ID-->
                                            <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                                            <fieldset class="">
                                                <div class="row" style="margin-bottom:0px;">
                                                    <?php
                                                    $colClassType1 = "col-md-2";
                                                    $colClassType2 = "col-md-4";
                                                    $colClassType3 = "col-md-8";
                                                    ?>
                                                    <div class="<?php echo $colClassType3; ?>">
                                                        <div class="input-group">
                                                            <input class="form-control" id="reportCardsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                            ?>" onkeyup="enterKeyFuncReportCards(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=10&mdl=<?php echo $mdlACAorPMS; ?>')">
                                                            <input id="reportCardsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                                            <div class="input-group-append handCursor" onclick="getReportCards('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=10&mdl=<?php echo $mdlACAorPMS; ?>');">
                                                                <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                                                            </div>
                                                            <div class="input-group-append handCursor" onclick="getReportCards('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=10&mdl=<?php echo $mdlACAorPMS; ?>');">
                                                                <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                                                            </div>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                                            </div>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="reportCardsSrchIn">
                                                                <?php
                                                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                                                $srchInsArrys = array(
                                                                    "All", "Assessment Sheet Name", "Assessment Group", "Assessment Type",
                                                                    "Programme/Objective", "Subject/Task", "Assessment Period", "Administrator"
                                                                );
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($srchIn == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="reportCardsDsplySze" style="min-width:70px !important;">
                                                                <?php
                                                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                                                $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000, 1000000);
                                                                for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                    if ($lmtSze == $dsplySzeArry[$y]) {
                                                                        $valslctdArry[$y] = "selected";
                                                                    } else {
                                                                        $valslctdArry[$y] = "";
                                                                    }
                                                                ?>
                                                                    <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1" style="display:inline-block;padding:0px 0px 0px 15px !important;">
                                                        <nav aria-label="Page navigation">
                                                            <ul class="pagination" style="margin: 0px !important;">
                                                                <li>
                                                                    <a href="javascript:getReportCards('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=10&mdl=<?php echo $mdlACAorPMS; ?>');" aria-label="Previous">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:getReportCards('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=10&mdl=<?php echo $mdlACAorPMS; ?>');" aria-label="Next">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </nav>
                                                    </div>
                                                    <?php if ($vwOnlySelfRpts == false) { ?>
                                                        <div class="col-md-1" style="display:inline-block;padding:5px 2px 0px 0px !important;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label" title="Only Self-Created">
                                                                    <?php
                                                                    $shwSelfOnlyChkd = "";
                                                                    if ($qShwSelfOnly == true) {
                                                                        $shwSelfOnlyChkd = "checked=\"true\"";
                                                                    }
                                                                    ?>
                                                                    <input type="checkbox" class="form-check-input" onclick="getReportCards('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS; ?>');" id="reportCardsShwUsrOnly" name="reportCardsShwUsrOnly" <?php echo $shwSelfOnlyChkd; ?>>
                                                                    Self-Created
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered" id="reportCardsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="">No.</th>
                                                                    <th style="">...</th>
                                                                    <th style="">...</th>
                                                                    <th style="">Report Card Name</th>
                                                                    <th style="">Sheet Type</th>
                                                                    <th style="">Group</th>
                                                                    <th style="display:none;">Programme/Objective</th>
                                                                    <th style="display:none;">Subject/Task</th>
                                                                    <th style="">Period</th>
                                                                    <th style="">Person Being Assessed</th>
                                                                    <th style="text-align:right;min-width:80px;">Subject Count</th>
                                                                    <th style="min-width:80px;">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $reportCardID = -1;
                                                                $reportCardNm = "";
                                                                while ($row = loc_db_fetch_array($result)) {
                                                                    $cntr += 1;
                                                                    if ($cntr == 1) {
                                                                        $reportCardID = (float) $row[0];
                                                                        $reportCardNm = $row[1];
                                                                    }
                                                                    $reportCardID1 = (float) $row[0];
                                                                    $reportCardNm1 = $row[1];
                                                                    $reportCardClassID = (float) $row[2];
                                                                    $reportCardClassNm = $row[3];
                                                                    $reportCardTypeID = (float) $row[4];
                                                                    $reportCardTypeNm = $row[5];
                                                                    $reportCardCrseID = (float) $row[6];
                                                                    $reportCardCrseNm = $row[7];
                                                                    $reportCardsbjctID = (float) $row[8];
                                                                    $reportCardsbjctNm = $row[9];
                                                                    $reportCardPrdID = (float) $row[10];
                                                                    $reportCardPrdNm = $row[11];
                                                                    $reportCardAdminID = (float) $row[12];
                                                                    $reportCardAdminNm = $row[13];
                                                                    $reportCardPrsnCount = (float) $row[19];
                                                                    $reportCardstatus = $row[16];
                                                                    $reportCrdHdrAsdPrsID = (float) $row[17];
                                                                    $reportCrdHdrAsdPrsNm = $row[18];
                                                                    $statusStyle = "color:green;font-weight:bold;";
                                                                    if ($reportCardstatus === "Closed") {
                                                                        $statusStyle = "color:red;font-weight:bold;";
                                                                    }
                                                                ?>
                                                                    <tr id="reportCardsHdrsRow_<?php echo $cntr; ?>">
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Edit Report Card" onclick="getReportCardDets('clear', '#reportCardDetList', 'grp=110&typ=1&vtyp=11&assessSbmtdSheetID=<?php echo $reportCardID1; ?>&assessSbmtdSheetNm=<?php echo urlencode($reportCardNm1); ?>&mdl=<?php echo $mdlACAorPMS; ?>', <?php echo $reportCardID1; ?>);" style="width:100% !important;">
                                                                                <img src="../cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" onclick="printEmailFullTermRpt(<?php echo $reportCardID1; ?>);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Print Report">
                                                                                <img src="../cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd"><?php echo $reportCardNm1; ?></td>
                                                                        <td class="lovtd"><?php echo $reportCardTypeNm; ?></td>
                                                                        <td class="lovtd"><?php echo $reportCardClassNm; ?></td>
                                                                        <td class="lovtd" style="display:none;"><?php echo $reportCardCrseNm; ?></td>
                                                                        <td class="lovtd" style="display:none;"><?php echo $reportCardsbjctNm; ?></td>
                                                                        <td class="lovtd"><?php echo $reportCardPrdNm; ?></td>
                                                                        <td class="lovtd"><?php echo $reportCrdHdrAsdPrsNm; ?></td>
                                                                        <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                                                                                                                    echo number_format($reportCardPrsnCount, 0);
                                                                                                                                                    ?>
                                                                        </td>
                                                                        <td class="lovtd" style="<?php echo $statusStyle; ?>"><?php echo $reportCardstatus; ?></td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                        <input type="hidden" id="reportCardID" name="reportCardID" value="<?php echo $reportCardID; ?>">
                                                        <input type="hidden" id="reportCardNm" name="reportCardNm" value="<?php echo $reportCardNm; ?>">
                                                        <input type="hidden" id="reportCardHdnTabNm" name="reportCardHdnTabNm" value="asShtDetlsInfo">
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div id="reportCardDetList" class="tab-pane fadein" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php
                                    } else if ($vwtyp == 11) {
                                        $mkReadOnly = "";
                                        $mkRmrkReadOnly = "";
                                        $assessSbmtdSheetID = isset($_POST['assessSbmtdSheetID']) ? $_POST['assessSbmtdSheetID'] : -1;
                                        $assessSbmtdSheetNm = isset($_POST['assessSbmtdSheetNm']) ? $_POST['assessSbmtdSheetNm'] : "";
                                        $pkID = $assessSbmtdSheetID;
                                        $assessTypeID = (int) getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assessment_type_id", $assessSbmtdSheetID);
                                        $reportCardstatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                                        if ($reportCardstatus === "Closed") {
                                            $canEdt = false;
                                        }
                                        $canEdt = false;
                                        $academicSttngID = -1;
                                        $cntr = 0;
    ?>
        <form id='reportCrdHdrForm' action='' method='post' accept-charset='UTF-8'>
            <fieldset class="">
                <div class="row" style="margin-bottom:0px;">
                    <?php if ($canAdd === true) {
                    ?>
                        <div class="col-md-6" style="padding:0px 15px 0px 15px !important;">
                            <button type="button" class="btn btn-default" onclick="getReportCardDets('clear', '#reportCardDetList', 'grp=110&typ=1&vtyp=11&mdl=<?php echo $mdlACAorPMS; ?>');">
                                <img src="../cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                            <button type="button" class="btn btn-default" onclick="printEmailFullTermRpt(<?php echo $assessSbmtdSheetID; ?>);" data-toggle="tooltip" data-placement="bottom" title="Print Report">
                                <img src="../cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                Print
                            </button>
                        </div>
                    <?php
                                        }
                    ?>
                    <div class="col-md-6" style="padding:0px 15px 0px 15px !important;">
                        <div class="input-group" style="width:100% !important;">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><span style="font-weight:bold;">Selected Report Card:</span></span>
                            </div>
                            <input type="hidden" class="form-control" aria-label="..." id="assessSbmtdSheetID" value="<?php echo $assessSbmtdSheetID; ?>" style="width:100% !important;">
                            <input type="hidden" class="form-control" aria-label="..." id="assessSbmtdSheetType" value="<?php echo $assessTypeFltr; ?>" style="width:100% !important;">
                            <input type="text" class="form-control" aria-label="..." id="assessSbmtdSheetNm" name="assessSbmtdSheetNm" value="<?php echo $assessSbmtdSheetNm; ?>" readonly="true" style="width:100% !important;">
                        </div>
                    </div>
                </div>
                <div class="row" style="padding:1px 15px 1px 15px !important;">
                    <hr style="margin:1px 0px 3px 0px;">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" style="margin-top:1px !important;display:none">
                                        <li class="active"><a data-toggle="tabajxasShtdetls" data-rhodata="" href="#asShtDetlsInfo" id="asShtDetlsInfotab">HEADER INFORMATION</a></li>
                                        <li class=""><a data-toggle="tabajxasShtdetls" data-rhodata="" href="#asShtDetlsTrans" id="asShtDetlsTranstab">DETAILS</a></li>
                                        <li class=""><a data-toggle="tabajxasShtdetls" data-rhodata="" href="#asShtDetlsPMRecs" id="asShtDetlsPMRecstab">FOOTER INFORMATION</a></li>
                                    </ul>
                                    <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                        <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                            <div id="asShtDetlsInfo" class="" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                                <div class="container-fluid" id="reportCardHdrDetailInfo">
                                                    <?php
                                                    $reportCrdHdrID = -1;
                                                    $reportCrdHdrName = "";
                                                    $reportCrdHdrClassID = -1;
                                                    $reportCrdHdrClassNm = "";
                                                    $reportCrdHdrTypeID = -1;
                                                    $reportCrdHdrTypeNm = "";
                                                    $reportCrdHdrType = $assessTypeFltr;
                                                    $reportCrdHdrCrseID = -1;
                                                    $reportCrdHdrCrseNm = "";
                                                    $reportCrdHdrSbjctID = -1;
                                                    $reportCrdHdrSbjctNm = "";
                                                    $reportCrdHdrPeriodID = -1;
                                                    $reportCrdHdrPeriodNm = "";
                                                    $reportCrdHdrPrsnID = $prsnid;
                                                    $reportCrdHdrPrsnNm = getPrsnFullNm($prsnid);
                                                    $reportCrdHdrDesc = "";
                                                    $reportCrdHdrStatus = "";
                                                    $reportCrdHdrAsdPrsID = -1;
                                                    $reportCrdHdrAsdPrsNm = "";
                                                    if ($pkID > 0) {
                                                        $result1 = get_One_AssessSheetHdr($pkID);
                                                        while ($row1 = loc_db_fetch_array($result1)) {
                                                            $reportCrdHdrID = (float) $row1[0];
                                                            $reportCrdHdrName = $row1[1];
                                                            $reportCrdHdrClassID = (int) $row1[2];
                                                            $reportCrdHdrClassNm = $row1[3];
                                                            $reportCrdHdrTypeID = (int) $row1[4];
                                                            $reportCrdHdrTypeNm = $row1[5];
                                                            $reportCrdHdrCrseID = (int) $row1[6];
                                                            $reportCrdHdrCrseNm = $row1[7];
                                                            $reportCrdHdrSbjctID = (int) $row1[8];
                                                            $reportCrdHdrSbjctNm = $row1[9];
                                                            $reportCrdHdrPeriodID = (float) $row1[10];
                                                            $reportCrdHdrPeriodNm = $row1[11];
                                                            $reportCrdHdrPrsnID = (float) $row1[12];
                                                            $reportCrdHdrPrsnNm = $row1[13];
                                                            $reportCrdHdrDesc = $row1[16];
                                                            $reportCrdHdrType = $row1[14];
                                                            $reportCrdHdrStatus = $row1[17];
                                                            $reportCrdHdrAsdPrsID = (float) $row1[18];
                                                            $reportCrdHdrAsdPrsNm = $row1[19];
                                                            $statusStyle = "padding:5px;color:green;font-weight:bold;";
                                                            if ($reportCrdHdrStatus === "Closed") {
                                                                $statusStyle = "padding:5px;color:red;font-weight:bold;";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                            <fieldset class="basic_person_fs123" style="padding-top:10px !important;">
                                                                <div class="form-group form-group-sm col-md-12 hideNotice" style="padding:0px 0px 0px 0px !important;">
                                                                    <label for="reportCrdHdrName" class="control-label col-lg-4">Sheet Name:</label>
                                                                    <div class="col-lg-8">
                                                                        <input type="text" class="form-control" aria-label="..." id="reportCrdHdrName" name="reportCrdHdrName" value="<?php echo $reportCrdHdrName; ?>" style="width:100% !important;" readonly="true">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="reportCrdHdrID" name="reportCrdHdrID" value="<?php echo $reportCrdHdrID; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12 hideNotice" style="padding:0px 0px 0px 0px !important;">
                                                                    <label for="reportCrdHdrDesc" class="control-label col-lg-4">Description:</label>
                                                                    <div class="col-lg-8">
                                                                        <textarea class="form-control" rows="3" cols="20" id="reportCrdHdrDesc" name="reportCrdHdrDesc" style="text-align:left !important;" readonly="true"><?php echo $reportCrdHdrDesc; ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12 hideNotice" style="padding:0px 0px 0px 0px !important;">
                                                                    <label for="reportCrdHdrTypeNm" class="control-label col-md-4">Assessment Type:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="reportCrdHdrTypeNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Assessment Type" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrTypeNm; ?>" readonly="true" />
                                                                        <input type="hidden" id="reportCrdHdrTypeID" value="<?php echo $reportCrdHdrTypeID; ?>">
                                                                        <input type="hidden" id="reportCrdHdrType" value="<?php echo $reportCrdHdrType; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                    <label for="reportCrdHdrPeriodNm" class="control-label col-md-4">Assessment Period:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="reportCrdHdrPeriodNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrPeriodNm; ?>" readonly="true" />
                                                                        <input type="hidden" id="reportCrdHdrPeriodID" value="<?php echo $reportCrdHdrPeriodID; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                    <label for="reportCrdHdrPrsnNm" class="control-label col-md-4">Main Assessor:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="reportCrdHdrPrsnNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrPrsnNm; ?>" readonly="true" />
                                                                        <input type="hidden" id="reportCrdHdrPrsnID" value="<?php echo $reportCrdHdrPrsnID; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                    <label for="reportCrdHdrStatus" class="control-label col-lg-4">Assessment Status:</label>
                                                                    <div class="col-lg-8">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="reportCrdHdrStatus" style="min-width:70px !important;">
                                                                                <?php
                                                                                $valslctdArry = array("", "");
                                                                                $dsplySzeArry = array("Open for Editing", "Closed");
                                                                                for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                                    if ($reportCrdHdrStatus == $dsplySzeArry[$y]) {
                                                                                        $valslctdArry[$y] = "selected";
                                                                                    } else {
                                                                                        $valslctdArry[$y] = "";
                                                                                    }
                                                                                ?>
                                                                                    <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        <?php } else { ?>
                                                                            <span style="<?php echo $statusStyle; ?>"><?php echo $reportCrdHdrStatus; ?></span>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                            <fieldset class="basic_person_fs123" style="padding-top:10px !important;">
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                    <label for="reportCrdHdrAsdPrsNm" class="control-label col-md-4">Person Assessed:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="reportCrdHdrAsdPrsNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrAsdPrsNm; ?>" readonly="true" />
                                                                        <input type="hidden" id="reportCrdHdrAsdPrsID" value="<?php echo $reportCrdHdrAsdPrsID; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                    <label for="reportCrdHdrClassNm" class="control-label col-md-4">Assessment Group:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="reportCrdHdrClassNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Assessment Group" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrClassNm; ?>" readonly="true" />
                                                                        <input type="hidden" id="reportCrdHdrClassID" value="<?php echo $reportCrdHdrClassID; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                    <label for="reportCrdHdrCrseNm" class="control-label col-md-4">Programme/Objective:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="reportCrdHdrCrseNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrCrseNm; ?>" readonly="true" />
                                                                        <input type="hidden" id="reportCrdHdrCrseID" value="<?php echo $reportCrdHdrCrseID; ?>">
                                                                        <input type="hidden" id="reportCrdHdrCrseRecType" value="<?php echo $moduleType; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;display:none;">
                                                                    <label for="reportCrdHdrSbjctNm" class="control-label col-md-4">Task/Subject:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="reportCrdHdrSbjctNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrSbjctNm; ?>" readonly="true" />
                                                                        <input type="hidden" id="reportCrdHdrSbjctID" value="<?php echo $reportCrdHdrSbjctID; ?>">
                                                                        <input type="hidden" id="reportCrdHdrSbjctRecType" value="<?php echo $moduleType2; ?>">
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <?php
                                                        $result1 = get_AssessShtGrpCols("01-Header", $assessTypeID);
                                                        $cntr1 = 0;
                                                        $gcntr1 = 0;
                                                        $cntr1Ttl = loc_db_num_rows($result1);
                                                        if ($cntr1Ttl > 0) {
                                                        ?>
                                                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                                    <?php
                                                                    while ($row1 = loc_db_fetch_array($result1)) {
                                                                        if ($gcntr1 == 0) {
                                                                            $gcntr1 += 1;
                                                                        }
                                                                        if (($cntr1 % 2) == 0) {
                                                                    ?>
                                                                            <div class="row">
                                                                            <?php
                                                                        }
                                                                            ?>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group form-group-sm">
                                                                                    <label class="control-label col-md-4"><?php echo $row1[2]; ?>:</label>
                                                                                    <div class="col-md-8">
                                                                                        <?php
                                                                                        $columnID = (int) $row1[0];
                                                                                        $columnNo = (int) $row1[15];
                                                                                        $prsnDValPulld = get_AssessShtColVal($assessSbmtdSheetID, $academicSttngID, $columnNo);
                                                                                        $isRqrdFld = ($row1[12] === "1") ? "rqrdFld" : "";
                                                                                        if ($row1[13] == "1" || $canEdt === false) {
                                                                                            echo str_replace("{:p_col_value}", $prsnDValPulld, $row1[19]);
                                                                                        } else {
                                                                                            if ($row1[4] == "Date") {
                                                                                        ?>
                                                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                                    <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" size="16" type="text" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>">
                                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                </div>
                                                                                            <?php
                                                                                            } else if ($row1[4] == "Number") {
                                                                                            ?>
                                                                                                <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                                                            <?php
                                                                                            } else {
                                                                                            ?>
                                                                                                <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                                                        <?php
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                            $cntr1 += 1;
                                                                            if (($cntr1 % 2) == 0 || $cntr1 == ($cntr1Ttl)) {
                                                                                $cntr1 = 0;
                                                                            ?>
                                                                            </div>
                                                                    <?php
                                                                            }
                                                                        }
                                                                        if ($gcntr1 == 1) {
                                                                            $gcntr1 = 0;
                                                                        }
                                                                    ?>
                                                                </fieldset>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php ?>
                                                </div>
                                            </div>
                                            <div id="asShtDetlsTrans" class="" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                                <?php
                                                $academicSttngID = -1;
                                                $mkReadOnly = "";
                                                $mkRmrkReadOnly = "";
                                                $assessSbmtdSheetID = isset($_POST['assessSbmtdSheetID']) ? $_POST['assessSbmtdSheetID'] : -1;
                                                $assessSbmtdSheetNm = isset($_POST['assessSbmtdSheetNm']) ? $_POST['assessSbmtdSheetNm'] : "";
                                                $pkID = $assessSbmtdSheetID;
                                                $assessTypeID = (int) getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assessment_type_id", $assessSbmtdSheetID);
                                                $reportCardstatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                                                if ($reportCardstatus === "Closed") {
                                                    $canEdt = false;
                                                }
                                                $cntr = 0;
                                                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                                                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                                                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                                                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 1000000;
                                                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                                                $searchAll = true;
                                                $fltrTyp = isset($_POST['acaRgstratnFilterBy']) ? cleanInputData($_POST['acaRgstratnFilterBy']) : "Relation Type";
                                                $fltrTypValue = isset($_POST['acaRgstratnFilterByVal']) ? cleanInputData($_POST['acaRgstratnFilterByVal']) : "All";

                                                $qShwCrntOnly = true;
                                                if (isset($_POST['qShwCrntOnly'])) {
                                                    $qShwCrntOnly = cleanInputData($_POST['qShwCrntOnly']) === "true" ? true : false;
                                                }

                                                if (strpos($srchFor, "%") === FALSE) {
                                                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                                                    $srchFor = str_replace("%%", "%", $srchFor);
                                                }
                                                $total = get_ReportCardLnsTtl($srchFor, $srchIn, $assessSbmtdSheetID);
                                                //var_dump($_POST);
                                                //echo "TT:" . $total;
                                                if ($pageNo > ceil($total / $lmtSze)) {
                                                    $pageNo = 1;
                                                } else if ($pageNo < 1) {
                                                    $pageNo = ceil($total / $lmtSze);
                                                }
                                                $extra4Where = " and a.person_id IN (select y1. from aca.aca_assmnt_col_vals y1 where )";
                                                $curIdx = $pageNo - 1;
                                                $result = get_ReportCardLns($srchFor, $srchIn, $curIdx, $lmtSze, $assessSbmtdSheetID);
                                                $cntr = 0;
                                                $vwtyp = 2;
                                                ?>
                                                <div class="basic_person_fs123" style="padding-top:2px !important;">
                                                    <div class="row">
                                                        <div class="col-md-12" style="display:none;">
                                                            <div class="col-md-3" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getReportCrdHdr('', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS; ?>');"><img src="../cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                            </div>
                                                            <div class="col-md-7" style="display:none;">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="reportCrdHdrSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                                        ?>" onkeyup="enterKeyFuncReportCrdHdr(event, '', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS; ?>')">
                                                                    <input id="reportCrdHdrPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getReportCrdHdr('clear', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS; ?>')">
                                                                        <span class="glyphicon glyphicon-remove"></span>
                                                                    </label>
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getReportCrdHdr('', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS; ?>');">
                                                                        <span class="glyphicon glyphicon-search"></span>
                                                                    </label>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="reportCrdHdrSrchIn">
                                                                        <?php
                                                                        $valslctdArry = array("", "");
                                                                        $srchInsArrys = array($courseLabel . " Name", $sbjctLabel . " Name");
                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                            if ($srchIn == $srchInsArrys[$z]) {
                                                                                $valslctdArry[$z] = "selected";
                                                                            }
                                                                        ?>
                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="reportCrdHdrDsplySze" style="min-width:70px !important;">
                                                                        <?php
                                                                        $valslctdArry = array("", "", "", "", "", "", "", "");
                                                                        $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                                                        for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                            if ($lmtSze == $dsplySzeArry[$y]) {
                                                                                $valslctdArry[$y] = "selected";
                                                                            } else {
                                                                                $valslctdArry[$y] = "";
                                                                            }
                                                                        ?>
                                                                            <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2" style="display:none;">
                                                                <nav aria-label="Page navigation">
                                                                    <ul class="pagination" style="margin: 0px !important;">
                                                                        <li>
                                                                            <a class="rhopagination" href="javascript:getReportCrdHdr('previous', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS; ?>');" aria-label="Previous">
                                                                                <span aria-hidden="true">&laquo;</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="rhopagination" href="javascript:getReportCrdHdr('next', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS; ?>');" aria-label="Next">
                                                                                <span aria-hidden="true">&raquo;</span>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </nav>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding:1px 15px 1px 15px !important;display:none;">
                                                        <hr style="margin:1px 0px 3px 0px;">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table table-striped table-bordered" id="oneReportCardTransLinesTable" cellspacing="0" width="100%" style="width:100% !important;">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="min-width:30px;">No.-[Rec. ID]</th>
                                                                        <th style="min-width:175px !important;"><?php echo $courseLabel; ?></th>
                                                                        <th style="min-width:175px !important;"><?php echo $sbjctLabel; ?></th>
                                                                        <?php
                                                                        $colWidth = "max-width:90px !important;width:90px !important;";
                                                                        $resultHdr = get_AssessShtGrpCols("02-Detail", $assessTypeID);
                                                                        $colscntr1 = 0;
                                                                        $ttlColS = loc_db_num_rows($resultHdr);
                                                                        $colsIDs = array_fill(0, $ttlColS, -1);
                                                                        $colNos = array_fill(0, $ttlColS, 1);
                                                                        $colsNames = array_fill(0, $ttlColS, "");
                                                                        $colsTypes = array_fill(0, $ttlColS, "");
                                                                        $colsIsFrmlr = array_fill(0, $ttlColS, "1");
                                                                        $colMinVals = array_fill(0, $ttlColS, 0);
                                                                        $colMaxVals = array_fill(0, $ttlColS, 0);
                                                                        $colsIsDsplyd = array_fill(0, $ttlColS, "1");
                                                                        $colsHtmlCss = array_fill(0, $ttlColS, "");
                                                                        $colsValSums = array_fill(0, $ttlColS, "");
                                                                        while ($rwHdr = loc_db_fetch_array($resultHdr)) {
                                                                            $colsIDs[$colscntr1] = (int) $rwHdr[0];
                                                                            $colsNames[$colscntr1] = $rwHdr[2];
                                                                            $colsTypes[$colscntr1] = $rwHdr[4];
                                                                            $colsIsFrmlr[$colscntr1] = $rwHdr[13];
                                                                            $colNos[$colscntr1] = (int) $rwHdr[15];
                                                                            $colMinVals[$colscntr1] = (int) $rwHdr[16];
                                                                            $colMaxVals[$colscntr1] = (int) $rwHdr[17];
                                                                            $colsIsDsplyd[$colscntr1] = $rwHdr[18];
                                                                            $colsHtmlCss[$colscntr1] = $rwHdr[19];
                                                                            $colscntr1++;
                                                                        }
                                                                        $colscntr = 0;
                                                                        while ($colscntr < count($colsNames)) {
                                                                            $tdStyle = "";
                                                                            if ($colsIsDsplyd[$colscntr] == "0") {
                                                                                $tdStyle = "display:none;";
                                                                            }
                                                                            if ($colsTypes[$colscntr] == "Number") {
                                                                                $tdStyle .= "text-align: right;";
                                                                            } else {
                                                                                $tdStyle .= "text-align: center;";
                                                                            }
                                                                        ?>
                                                                            <th style="<?php echo $tdStyle . $colWidth; ?>"><?php echo $colsNames[$colscntr]; ?></th>
                                                                        <?php
                                                                            $colscntr++;
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    while ($row = loc_db_fetch_array($result)) {
                                                                        if ($pkID <= 0 && $cntr <= 0) {
                                                                            $pkID = (float) $row[0];
                                                                        }
                                                                        $academicSttngID = (float) $row[1];
                                                                        //border-top:1px solid #ddd;
                                                                        $cntr += 1;
                                                                    ?>
                                                                        <tr id="reportCrdColRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr) . "-[" . $row[0] . "]"; ?></td>
                                                                            <td class="lovtd"><?php echo $row[5]; ?>
                                                                                <input type="hidden" class="form-control" aria-label="..." id="reportCrdColRow<?php echo $cntr; ?>_LineID" value="<?php echo $row[0]; ?>">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="reportCrdColRow<?php echo $cntr; ?>_PrsnID" value="<?php echo $row[4]; ?>">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="reportCrdColRow<?php echo $cntr; ?>_PrsnNm" value="<?php echo $row[6]; ?>">
                                                                            </td>
                                                                            <td class="lovtd"><?php echo $row[6]; ?></td>
                                                                            <?php
                                                                            $colscntr = 0;
                                                                            while ($colscntr < count($colsIDs)) {
                                                                                $columnID = (int) $colsIDs[$colscntr];
                                                                                $columnNo = (int) $colNos[$colscntr];
                                                                                $prsnDValPulld = $row[7 + $columnNo];
                                                                                $isRqrdFld = "rqrdFld";
                                                                                $tdClass = "lovtd555";
                                                                                $tdStyle = "padding: 0px !important;";
                                                                                if ($colsIsFrmlr[$colscntr] == "1" || $canEdt === false) {
                                                                                    $tdClass = "lovtd";
                                                                                    $tdStyle = "";
                                                                                }
                                                                                if ($colsIsDsplyd[$colscntr] == "0") {
                                                                                    $tdStyle = "display:none;";
                                                                                }
                                                                                if ($colsTypes[$colscntr] == "Number") {
                                                                                    $tdStyle .= "text-align: right;";
                                                                                    $colsValSums[$colscntr] = ((float) $colsValSums[$colscntr]) + ((float) $prsnDValPulld);
                                                                                } else {
                                                                                    $tdStyle .= "text-align: center;";
                                                                                }
                                                                                $minValRhoData = $colMinVals[$colscntr];
                                                                                $maxValRhoData = $colMaxVals[$colscntr];
                                                                            ?>
                                                                                <td class="<?php echo $tdClass; ?>" style="text-align: right;<?php echo $tdStyle . $colWidth; ?>">
                                                                                    <?php
                                                                                    if ($colsIsFrmlr[$colscntr] == "1" || $canEdt === false) {
                                                                                        echo str_replace("{:p_col_value}", $prsnDValPulld, $colsHtmlCss[$colscntr]);
                                                                                    } else {
                                                                                        if ($colsTypes[$colscntr] == "Date") {
                                                                                    ?>
                                                                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                                <input class="form-control reportCrdColRow<?php echo $cntr; ?> assesScoreM assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" size="16" data-rhodata="" type="text" id="reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>" style="width:100% !important;">
                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                            </div>
                                                                                        <?php
                                                                                        } else if ($colsTypes[$colscntr] == "Number") {
                                                                                        ?>
                                                                                            <input class="form-control reportCrdColRow<?php echo $cntr; ?> assesScoreM assesScoreNum assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" id="reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" min-rhodata="<?php echo $minValRhoData; ?>" max-rhodata="<?php echo $maxValRhoData; ?>" type="text" placeholder="" value="<?php echo number_format(((float) $prsnDValPulld), 2); ?>" style="width:100% !important;text-align: right;margin:0px !important;" onblur="vldtAssessColNumFld('reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>');" onkeypress="gnrlFldKeyPress(event, 'reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>', 'oneReportCardTransLinesTable', 'assesScore<?php echo $columnNo; ?>');" />
                                                                                        <?php
                                                                                        } else {
                                                                                        ?>
                                                                                            <input class="form-control reportCrdColRow<?php echo $cntr; ?> assesScoreM assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" id="reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" data-rhodata="" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" style="width:100% !important;text-align: right;margin:0px !important;" />
                                                                                    <?php
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                            <?php
                                                                                $colscntr++;
                                                                            }
                                                                            ?>
                                                                        </tr>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <?php
                                                                        $ttlFooters = ($colscntr + 3);
                                                                        for ($z = 0; $z < $ttlFooters; $z++) {
                                                                            $tdStyle = "";
                                                                            $colscntr = $z - 3;
                                                                            if ($colscntr >= 0) {
                                                                                if ($colsIsDsplyd[$colscntr] == "0") {
                                                                                    $tdStyle = "display:none;";
                                                                                }
                                                                                if ($colsTypes[$colscntr] == "Number") {
                                                                                    $tdStyle .= "text-align: right;";
                                                                                    $colsValSums[$colscntr] = number_format(((float) $colsValSums[$colscntr]), 2);
                                                                                } else {
                                                                                    $tdStyle .= "text-align: center;";
                                                                                    $colsValSums[$colscntr] = "&nbsp;";
                                                                                }
                                                                        ?>
                                                                                <th style="<?php echo $tdStyle; ?>"><?php echo $colsValSums[$colscntr]; ?></th>
                                                                            <?php } else {
                                                                            ?>
                                                                                <th style="<?php echo $tdStyle; ?>">&nbsp;</th>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php ?>
                                            </div>
                                            <div id="asShtDetlsPMRecs" class="" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                                <?php
                                                //Footer
                                                $mkReadOnly = "";
                                                $mkRmrkReadOnly = "";
                                                $assessSbmtdSheetID = isset($_POST['assessSbmtdSheetID']) ? $_POST['assessSbmtdSheetID'] : -1;
                                                $assessSbmtdSheetNm = isset($_POST['assessSbmtdSheetNm']) ? $_POST['assessSbmtdSheetNm'] : "";
                                                $assessTypeID = (int) getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assessment_type_id", $assessSbmtdSheetID);
                                                $reportCardstatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                                                if ($reportCardstatus === "Closed") {
                                                    $canEdt = false;
                                                }
                                                $academicSttngID = -1;
                                                $pkID = $assessSbmtdSheetID;
                                                $cntr = 0;
                                                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                                                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                                                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                                                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
                                                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                                                if (strpos($srchFor, "%") === FALSE) {
                                                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                                                    $srchFor = str_replace("%%", "%", $srchFor);
                                                }
                                                $total = 0;
                                                if ($pageNo > ceil($total / $lmtSze)) {
                                                    $pageNo = 1;
                                                } else if ($pageNo < 1) {
                                                    $pageNo = ceil($total / $lmtSze);
                                                }
                                                $curIdx = $pageNo - 1;
                                                $result = null;
                                                $cntr = 0;
                                                $vwtyp = 3;
                                                $result1 = get_AssessShtGrpCols("03-Footer", $assessTypeID);
                                                $cntr1 = 0;
                                                $gcntr1 = 0;
                                                $cntr1Ttl = loc_db_num_rows($result1);
                                                if ($cntr1Ttl > 0) {
                                                ?>
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <?php
                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                $cntr1 += 1;
                                                                $columnID = (int) $row1[0];
                                                                $columnNo = (int) $row1[15];
                                                                $prsnDValPulld = get_AssessShtColVal($assessSbmtdSheetID, $academicSttngID, $columnNo);
                                                                //get_PrsExtrData($pkID, $row1[1]);
                                                                $isRqrdFld = ($row1[12] === "1") ? "rqrdFld" : "";
                                                                $tdStyle1 = "";
                                                                $tdStyle2 = "";
                                                                if ($row1[18] == "0") {
                                                                    $tdStyle1 = "display:none;";
                                                                }
                                                                if ($row1[4] == "Number") {
                                                                    $tdStyle2 .= "text-align: right;";
                                                                    $prsnDValPulld = number_format(((float) $prsnDValPulld), 2);
                                                                } else {
                                                                    $tdStyle2 .= "text-align: left;";
                                                                }
                                                            ?>
                                                                <div class="row" style="<?php echo $tdStyle1; ?>">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group form-group-sm">
                                                                            <label class="control-label col-md-4"><?php echo $row1[2]; ?>:</label>
                                                                            <div class="col-md-8">
                                                                                <?php
                                                                                if ($row1[13] == "1" || $canEdt === false) {
                                                                                    echo str_replace("{:p_col_value}", $prsnDValPulld, $row1[19]);
                                                                                } else {
                                                                                    if ($row1[4] == "Date") {
                                                                                ?>
                                                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                            <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" size="16" type="text" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>">
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                        </div>
                                                                                    <?php
                                                                                    } else if ($row1[4] == "Number") {
                                                                                    ?>
                                                                                        <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                                                <?php
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </fieldset>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </fieldset>
        </form>
<?php
                                    }
                                }

                                function getAcaPgPrmssns($prsnID, $orgid, $usrID)
                                {
                                    global $ssnRoles;
                                    $mdlNm = "Learning/Performance Management";
                                    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                                    $sqlStr = "Select (select oprtnl_crncy_id from org.org_details where org_id = $orgid), "
                                        . "pasn.get_prsn_siteid(" . $prsnID . "), "
                                        . "pasn.get_prsn_divid_of_spctype(" . $prsnID . ", 'Access Control Group'),"
                                        . "scm.getUserStoreID(" . $usrID . ", " . $orgid . "), "
                                        . "sec.test_prmssns('View Summary Reports', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "') vwInvntry, "
                                        . "sec.test_prmssns('View Learning/Performance Management', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View Assessment Sheets', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View Task Assignment Setups', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View Groups/Courses/Subjects', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View Position Holders', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View Assessment Periods', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View Assessment Reports Types', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View Only Self-Created Sheets', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View Only Self-Created Summary Reports', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View Summary Reports', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View Summary Reports', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View Summary Reports', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View Record History', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
                                        . "sec.test_prmssns('View SQL', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Course/Objective/Programme Label ACA%', gst.get_lov_id('All Other Performance Setups'))), 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Subject/Task Label ACA%', gst.get_lov_id('All Other Performance Setups'))), 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Group Label ACA%', gst.get_lov_id('All Other Performance Setups'))),
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Learning/Performance Management', gst.get_lov_id('Customized Module Names'))), 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Course/Objective/Programme Label PMS%', gst.get_lov_id('All Other Performance Setups'))), 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Subject/Task Label PMS%', gst.get_lov_id('All Other Performance Setups'))), 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Group Label PMS%', gst.get_lov_id('All Other Performance Setups')))";
                                    //echo $sqlStr;
                                    $result = executeSQLNoParams($sqlStr);
                                    while ($row = loc_db_fetch_array($result)) {
                                        $rslts[0] = ((int) $row[0]);
                                        $rslts[1] = ((int) $row[1]);
                                        $rslts[2] = ((int) $row[2]);
                                        $rslts[3] = ((int) $row[3]);
                                        $rslts[4] = ((int) $row[4]);
                                        $rslts[5] = ((int) $row[5]);
                                        $rslts[6] = ((int) $row[6]);
                                        $rslts[7] = ((int) $row[7]);
                                        $rslts[8] = ((int) $row[8]);
                                        $rslts[9] = ((int) $row[9]);
                                        $rslts[10] = ((int) $row[10]);
                                        $rslts[11] = ((int) $row[11]);
                                        $rslts[12] = ((int) $row[12]);
                                        $rslts[13] = ((int) $row[13]);
                                        $rslts[14] = ((int) $row[14]);
                                        $rslts[15] = ((int) $row[15]);
                                        $rslts[16] = ((int) $row[16]);
                                        $rslts[17] = ((int) $row[17]);
                                        $rslts[18] = ((int) $row[18]);
                                        $rslts[19] = ($row[19]);
                                        $rslts[20] = ($row[20]);
                                        $rslts[21] = ($row[21]);
                                        $rslts[22] = ($row[22]);
                                        $rslts[23] = ($row[23]);
                                        $rslts[24] = ($row[24]);
                                        $rslts[25] = ($row[25]);
                                    }
                                    return $rslts;
                                }

                                function getAcaPrsnInfo($inPrsnID)
                                {
                                    execUpdtInsSQL("DELETE FROM aca.aca_prsns_ac_sttngs_sbjcts y
                                                                             WHERE y.ac_sttngs_sbjcts_id IN (select tbl1.max_pkey_id
                                                                                        from (
                                                                                                 select b.person_id,
                                                                                                        a.subject_id,
                                                                                                        count(a.ac_sttngs_sbjcts_id),
                                                                                                        min(a.ac_sttngs_sbjcts_id) min_pkey_id,
                                                                                                        max(a.ac_sttngs_sbjcts_id) max_pkey_id
                                                                                                 from aca.aca_prsns_ac_sttngs_sbjcts a,
                                                                                                      aca.aca_prsns_acdmc_sttngs b
                                                                                                 where a.acdmc_sttngs_id = b.acdmc_sttngs_id
                                                                                                 group by b.person_id,
                                                                                                          a.subject_id
                                                                                                 having count(a.ac_sttngs_sbjcts_id) > 1) tbl1)");
                                    $strSql = "SELECT a.person_id, a.local_id_no, trim(a.title || ' ' || a.sur_name || " .
                                        "', ' || a.first_name || ' ' || a.other_names) fullname,
                                                                        gender, date_of_birth, email, cntct_no_tel, 
                                                                  cntct_no_mobl, cntct_no_fax, nationality, 
                                                                  b.prsn_type, b.prn_typ_asgnmnt_rsn, " .
                                        "b.further_details, b.valid_start_date, b.valid_end_date  " .
                                        "FROM prs.prsn_names_nos a "
                                        . "LEFT OUTER JOIN pasn.prsn_prsntyps b " .
                                        "ON (a.person_id = b.person_id and "
                                        . "b.prsntype_id = (SELECT MAX(c.prsntype_id) from pasn.prsn_prsntyps c where c.person_id = a.person_id)) " .
                                        "WHERE ((a.person_id = " . $inPrsnID . "))";
                                    //echo $strSql;
                                    $result = executeSQLNoParams($strSql);
                                    return $result;
                                }

                                //TASK ASSIGNMENTS
                                function get_AcaSttngsClasses($acaPrsnID, $offset, $limit_size, $searchIn, $searchWord, $qShwCrntOnly)
                                {
                                    $whereClause = " and (b.class_name ilike '" . loc_db_escape_string($searchWord) .
                                        "' or aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                    if ($qShwCrntOnly === true) {
                                        $whereClause .= " and a.acdmc_period_id IN (Select y.acdmc_period_id from aca.aca_prsns_acdmc_sttngs y,
        aca.aca_assessment_periods z where z.assmnt_period_id = y.acdmc_period_id 
        and y.person_id = " . $acaPrsnID . " ORDER BY z.period_start_date DESC LIMIT 1 OFFSET 0)";
                                        /* $whereClause .= " and now() between to_timestamp(c.period_start_date,'YYYY-MM-DD 00:00:00') "
          . "and to_timestamp(c.period_end_date,'YYYY-MM-DD 23:59:59')"; */
                                    }
                                    $strSql = "SELECT a.acdmc_sttngs_id, a.class_id, a.course_id, aca.get_coursenm(a.course_id), "
                                        . "b.class_name, a.acdmc_period_id, c.assmnt_period_name, c.period_start_date, c.period_end_date " .
                                        "FROM aca.aca_prsns_acdmc_sttngs a, aca.aca_classes b, aca.aca_assessment_periods c " .
                                        "WHERE a.class_id = b.class_id and c.assmnt_period_id = a.acdmc_period_id 
            and a.person_id = " . $acaPrsnID . $whereClause .
                                        " ORDER BY c.period_start_date DESC LIMIT " . $limit_size .
                                        " OFFSET " . (abs($offset * $limit_size));
                                    //echo $strSql;
                                    $result = executeSQLNoParams($strSql);
                                    return $result;
                                }

                                function get_AcaSttngsExprt($offset, $limit_size, $searchIn, $searchWord, $qShwCrntOnly)
                                {
                                    $hdngs = array(
                                        "ID No.**", "Person Full Name", "Group Name**", "Programme/Objective Code**", "Programme/Objective Name",
                                        "Assessment Period Name**", "Subject/Target Code", "Subject/Target Name", "Core/Elective", "Weight/Credit Hours"
                                    );
                                    $strSql = "";
                                    $extrWhere = "";
                                    $whrcls = " and (aca.get_class_nm(a.class_id) ilike '" . loc_db_escape_string($searchWord) .
                                        "' or aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                    if ($qShwCrntOnly === true) {
                                        $whrcls .= " and a.acdmc_period_id IN (Select y.acdmc_period_id from aca.aca_prsns_acdmc_sttngs y,
            aca.aca_assessment_periods z where z.assmnt_period_id = y.acdmc_period_id 
            and y.person_id = a.person_id ORDER BY z.period_start_date DESC LIMIT 1 OFFSET 0)";
                                        /* $whereClause .= " and now() between to_timestamp(c.period_start_date,'YYYY-MM-DD 00:00:00') "
              . "and to_timestamp(d.period_end_date,'YYYY-MM-DD 23:59:59')"; */
                                    }
                                    $strSql = "SELECT '''' || prs.get_prsn_loc_id(a.person_id), prs.get_prsn_name(a.person_id), aca.get_class_nm(a.class_id),
        aca.get_coursecode(a.course_id), aca.get_coursenm(a.course_id), d.assmnt_period_name,
        aca.get_subjectcode(b.subject_id), aca.get_subjectnm(b.subject_id), c.core_or_elective, c.weight_or_credit_hrs 
  FROM aca.aca_prsns_acdmc_sttngs a, aca.aca_prsns_ac_sttngs_sbjcts b, 
  aca.aca_crsrs_n_thr_sbjcts c, aca.aca_assessment_periods d " .
                                        "WHERE(a.acdmc_sttngs_id = b.acdmc_sttngs_id and a.class_id=c.class_id "
                                        . "and b.subject_id=c.subject_id and a.course_id=c.course_id and d.assmnt_period_id = a.acdmc_period_id " . $whrcls . $extrWhere .
                                        ") ORDER BY prs.get_prsn_loc_id(a.person_id) LIMIT " . $limit_size .
                                        " OFFSET " . (abs($offset * $limit_size));
                                    $result = executeSQLNoParams($strSql);
                                    return $result;
                                }

                                function get_AcaSttngsSbjcts($sttngID, $offset, $limit_size, $searchIn, $searchWord)
                                {
                                    $whereClause = " and (aca.get_subjectnm(a.subject_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                    $strSql = "SELECT a.ac_sttngs_sbjcts_id, a.subject_id, c.core_or_elective, c.weight_or_credit_hrs, "
                                        . "aca.get_subjectnm(a.subject_id) subject_name " .
                                        "FROM aca.aca_prsns_ac_sttngs_sbjcts a, aca.aca_prsns_acdmc_sttngs b, aca.aca_crsrs_n_thr_sbjcts c, aca.aca_assessment_periods d " .
                                        "WHERE a.acdmc_sttngs_id = b.acdmc_sttngs_id and b.class_id = c.class_id "
                                        . "and b.course_id = c.course_id and a.subject_id=c.subject_id "
                                        . "and b.acdmc_period_id=d.assmnt_period_id "
                                        //. "and (d.period_type = c.period_type or coalesce(c.period_type,'') = '') "
                                        //. "and (d.period_number = c.period_number or coalesce(c.period_number,1) = 1) "
                                        . "and a.acdmc_sttngs_id = " . $sttngID .
                                        " " . $whereClause . " ORDER BY aca.get_subjectnm(a.subject_id) LIMIT " . $limit_size . " OFFSET " . (abs($offset * $limit_size));
                                    $result = executeSQLNoParams($strSql);
                                    return $result;
                                }

                                function get_TtlAcaSttngsSbjcts($sttngID, $searchIn, $searchWord)
                                {
                                    $whereClause = " and (aca.get_subjectnm(a.subject_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                    $strSql = "SELECT count(1) " .
                                        "FROM aca.aca_prsns_ac_sttngs_sbjcts a, aca.aca_prsns_acdmc_sttngs b, aca.aca_crsrs_n_thr_sbjcts c, aca.aca_assessment_periods d " .
                                        "WHERE a.acdmc_sttngs_id = b.acdmc_sttngs_id and b.class_id = c.class_id "
                                        . "and b.course_id = c.course_id and a.subject_id=c.subject_id "
                                        . "and b.acdmc_period_id=d.assmnt_period_id "
                                        . "and (d.period_type = c.period_type or coalesce(c.period_type,'') = '') "
                                        . "and (d.period_number = c.period_number or coalesce(c.period_number,1) = 1) "
                                        . "and a.acdmc_sttngs_id = " . $sttngID . " " . $whereClause;
                                    $result = executeSQLNoParams($strSql);
                                    if (loc_db_num_rows($result) > 0) {
                                        $row = loc_db_fetch_array($result);
                                        return (float) $row[0];
                                    }
                                    return 0;
                                }

                                function getNew_AcaRgstratnID()
                                {
                                    $strSql = "select nextval('aca.aca_prsns_acdmc_sttngs_acdmc_sttngs_id_seq')";
                                    $result = executeSQLNoParams($strSql);
                                    if (loc_db_num_rows($result) > 0) {
                                        $row = loc_db_fetch_array($result);
                                        return (float) $row[0];
                                    }
                                    return -1;
                                }

                                function get_AcaRgstratnID($in_prsnID, $classid, $courseID, $periodID)
                                {
                                    $strSql = "select acdmc_sttngs_id from aca.aca_prsns_acdmc_sttngs a "
                                        . "where a.course_id = " . $courseID .
                                        " and class_id = " . $classid .
                                        " and person_id = " . $in_prsnID .
                                        " and acdmc_period_id = " . $periodID;
                                    $result = executeSQLNoParams($strSql);
                                    if (loc_db_num_rows($result) > 0) {
                                        $row = loc_db_fetch_array($result);
                                        return (float) $row[0];
                                    }
                                    return -1;
                                }

                                function create_AcaRgstratn($sttngsID, $in_prsnID, $classid, $courseID, $periodID)
                                {
                                    global $usrID;
                                    $insSQL = "INSERT INTO aca.aca_prsns_acdmc_sttngs(
	acdmc_sttngs_id, person_id, class_id, acdmc_period_id, course_id, created_by, creation_date, 
        last_update_by, last_update_date) " .
                                        "VALUES (" . $sttngsID .
                                        ", " . $in_prsnID .
                                        ", " . $classid .
                                        ", " . $periodID .
                                        ", " . $courseID .
                                        ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
                                        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'))";
                                    return execUpdtInsSQL($insSQL);
                                }

                                function update_AcaRgstratn($sttngsID, $in_prsnID, $classid, $courseID, $periodID)
                                {
                                    global $usrID;
                                    $updtSQL = "UPDATE aca.aca_prsns_acdmc_sttngs SET " .
                                        "class_id=" . $classid .
                                        ", course_id=" . $courseID .
                                        ", person_id = " . $in_prsnID .
                                        ", acdmc_period_id = " . $periodID .
                                        ", last_update_by = " . $usrID .
                                        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (acdmc_sttngs_id = " . $sttngsID . ")";
                                    return execUpdtInsSQL($updtSQL);
                                }

                                function delete_AcaRgstratn($pKeyID, $pkeyDesc)
                                {
                                    $selSQL1 = "Select count(1) from aca.aca_assmnt_col_vals 
    where acdmc_sttngs_id = " . $pKeyID . "";
                                    $result1 = executeSQLNoParams($selSQL1);
                                    $trnsCnt1 = 0;
                                    while ($row = loc_db_fetch_array($result1)) {
                                        $trnsCnt1 = (float) $row[0];
                                    }
                                    if (($trnsCnt1) > 0) {
                                        $dsply = "No Record Deleted<br/>Cannot delete Registrations used in Score Cards!";
                                        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                                    }
                                    $acaRgstrClassID = (int)getGnrlRecNm("aca.aca_prsns_acdmc_sttngs", "acdmc_sttngs_id", "class_id", $pKeyID);
                                    $sbmtdRgstrPersonID = (int)getGnrlRecNm("aca.aca_prsns_acdmc_sttngs", "acdmc_sttngs_id", "person_id", $pKeyID);
                                    $pDivGrpDivID = (int)getGnrlRecNm("aca.aca_classes", "class_id", "lnkd_div_id", $acaRgstrClassID);
                                    $oldDivGrpPKey = getPDivGrpID($sbmtdRgstrPersonID, $pDivGrpDivID);
                                    $delSQL = "DELETE FROM aca.aca_prsns_ac_sttngs_sbjcts WHERE acdmc_sttngs_id = " . $pKeyID . "";
                                    $affctd0 = execUpdtInsSQL($delSQL, $pkeyDesc);
                                    $delSQL = "DELETE FROM aca.aca_prsns_acdmc_sttngs WHERE acdmc_sttngs_id = " . $pKeyID . "";
                                    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
                                    if ($oldDivGrpPKey > 0) {
                                        deletePDivGrp($oldDivGrpPKey);
                                    }
                                    if ($affctd1 > 0) {
                                        $dsply = "";
                                        $dsply .= "<br/>Successfully Executed the ff-";
                                        $dsply .= "<br/>Deleted $affctd1 Assignment(s)!";
                                        $dsply .= "<br/>Deleted $affctd0 Detail(s)!";
                                        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                                    } else {
                                        $dsply = "No Record Deleted";
                                        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                                    }
                                }

                                function getNew_AcaRgstratnbjctID()
                                {
                                    $strSql = "select nextval('aca.aca_prsns_ac_sttngs_sbjcts_ac_sttngs_sbjcts_id_seq')";
                                    $result = executeSQLNoParams($strSql);
                                    if (loc_db_num_rows($result) > 0) {
                                        $row = loc_db_fetch_array($result);
                                        return (float) $row[0];
                                    }
                                    return -1;
                                }

                                function get_AcaRgstratnbjctID($sbjctID, $sttngsID)
                                {
                                    $strSql = "select ac_sttngs_sbjcts_id from aca.aca_prsns_ac_sttngs_sbjcts a "
                                        . "where a.subject_id = " . $sbjctID . " and a.acdmc_sttngs_id = " . $sttngsID;
                                    $result = executeSQLNoParams($strSql);
                                    if (loc_db_num_rows($result) > 0) {
                                        $row = loc_db_fetch_array($result);
                                        return (float) $row[0];
                                    }
                                    return -1;
                                }

                                function create_AcaRgstratnbjcts($sttngsSbjctID, $sttngsID, $sbjctID)
                                {
                                    global $usrID;
                                    $insSQL = "INSERT INTO aca.aca_prsns_ac_sttngs_sbjcts(
	ac_sttngs_sbjcts_id, acdmc_sttngs_id, subject_id, created_by, creation_date) " .
                                        "VALUES (" . $sttngsSbjctID .
                                        ", " . $sttngsID .
                                        ", " . $sbjctID .
                                        ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'))";
                                    //echo $insSQL;
                                    return execUpdtInsSQL($insSQL);
                                }

                                function update_AcaRgstratnbjcts($sttngsSbjctID, $sttngsID, $sbjctID)
                                {
                                    //global $usrID;
                                    $updtSQL = "UPDATE aca.aca_prsns_ac_sttngs_sbjcts SET " .
                                        "acdmc_sttngs_id=" . $sttngsID .
                                        ", subject_id=" . $sbjctID .
                                        " WHERE (ac_sttngs_sbjcts_id =" . $sttngsSbjctID . ")";
                                    //echo $updtSQL;
                                    return execUpdtInsSQL($updtSQL);
                                }

                                function delete_AcaRgstratnbjcts($pKeyID, $pkeyDesc)
                                {
                                    $selSQL1 = "Select count(1) from aca.aca_assmnt_col_vals 
                                    where acdmc_sttngs_id IN (Select z.acdmc_sttngs_id from aca.aca_prsns_ac_sttngs_sbjcts z 
                                    where z.ac_sttngs_sbjcts_id = " . $pKeyID . ")";
                                    $result1 = executeSQLNoParams($selSQL1);
                                    $trnsCnt1 = 0;
                                    while ($row = loc_db_fetch_array($result1)) {
                                        $trnsCnt1 = (float) $row[0];
                                    }
                                    if (($trnsCnt1) > 0) {
                                        $dsply = "No Record Deleted<br/>Cannot delete Registrations used in Score Cards!";
                                        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                                    }
                                    $delSQL = "DELETE FROM aca.aca_prsns_ac_sttngs_sbjcts WHERE ac_sttngs_sbjcts_id = " . $pKeyID . "";
                                    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
                                    if ($affctd1 > 0) {
                                        $dsply = "";
                                        $dsply .= "<br/>Successfully Executed the ff-";
                                        $dsply .= "<br/>Deleted $affctd1 Subject/Task(s)!";
                                        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                                    } else {
                                        $dsply = "No Record Deleted";
                                        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                                    }
                                }

                                function get_AcaClasseSbjcts($classID, $courseID, $offset, $limit_size, $searchIn, $searchWord)
                                {
                                    $whereClause = " and (b.subject_name ilike '" . loc_db_escape_string($searchWord) .
                                        "' or b.subject_code ilike '" . loc_db_escape_string($searchWord) . "')";
                                    $strSql = "SELECT a.crse_sbjct_id, a.course_id, a.subject_id, a.class_id, a.core_or_elective, a.weight_or_credit_hrs, "
                                        . "b.subject_code, b.subject_name, a.is_enabled, "
                                        . "REPLACE(b.subject_code || ' (' || b.subject_name || ')', ' (' || b.subject_code || ')', '') subject_name, "
                                        . "a.period_type, a.period_number, 
                                                aca.get_pos_hldr_prs_nm(-1, c.lnkd_div_id, -1, a.subject_id, c.sbjct_fcltr_pos_name) " .
                                        "FROM aca.aca_crsrs_n_thr_sbjcts a, aca.aca_subjects b, aca.aca_classes c " .
                                        "WHERE a.subject_id = b.subject_id and a.class_id=c.class_id and a.course_id = " . $courseID .
                                        " and a.class_id = " . $classID . $whereClause .
                                        " ORDER BY a.core_or_elective, b.subject_name LIMIT " . $limit_size .
                                        " OFFSET " . (abs($offset * $limit_size));
                                    //echo $strSql
                                    $result = executeSQLNoParams($strSql);
                                    return $result;
                                }

                                function get_Ttl_AcaClasseSbjcts($classID, $courseID, $searchIn, $searchWord)
                                {
                                    $whereClause = " and (b.subject_name ilike '" . loc_db_escape_string($searchWord) .
                                        "' or b.subject_code ilike '" . loc_db_escape_string($searchWord) . "')";
                                    $strSql = "SELECT count(1) " .
                                        "FROM aca.aca_crsrs_n_thr_sbjcts a, aca.aca_subjects b  " .
                                        "WHERE a.subject_id = b.subject_id and a.course_id = " . $courseID .
                                        " and a.class_id = " . $classID . $whereClause . "";
                                    $result = executeSQLNoParams($strSql);
                                    if (loc_db_num_rows($result) > 0) {
                                        $row = loc_db_fetch_array($result);
                                        return (float) $row[0];
                                    }
                                    return 0;
                                }

                                function isItmValSQLValid($itemSQL, $prsn_id, $org_id, $dateStr, $p_itm_typ_id, $p_request_id, &$errMsg)
                                {
                                    set_error_handler("rhoErrorHandler3");
                                    try {
                                        if ($dateStr != "") {
                                            $dateStr = cnvrtDMYTmToYMDTm($dateStr);
                                        }
                                        if (strlen($dateStr) > 10) {
                                            $dateStr = substr($dateStr, 0, 10);
                                        }
                                        $nwSQL = str_replace("{:request_id}", $p_request_id, str_replace("{:item_typ_id}", $p_itm_typ_id, str_replace("{:pay_date}", $dateStr, str_replace("{:org_id}", $org_id, str_replace("{:person_id}", $prsn_id, $itemSQL)))));

                                        $result = executeSQLNoParams($nwSQL);
                                        if ($result !== NULL && strpos(strtoupper($_SESSION['ERROR_MSG']), "ERROR") === FALSE) {
                                            while ($row = loc_db_fetch_array($result)) {
                                                $errMsg .= $row[0];
                                            }
                                            return true;
                                        } else {
                                            $errMsg .= $_SESSION['ERROR_MSG'] . "<br/>";
                                            return false;
                                        }
                                    } catch (\Exception $ex) {
                                        $errMsg .= "An error occurred. <br/>" . $ex->getMessage() . " Invalid Value SQL Query!<br/>";
                                        return FALSE;
                                    }
                                }


                                //ASSESSMENT SHEETS
                                function get_AssessSheets($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwSelfOnly, $assessTypeFltr = "Assessment Sheet Per Group")
                                {
                                    global $usrID;
                                    global $prsnid;
                                    $strSql = "";
                                    $whrcls = "";
                                    $selfOnlyCls = "";
                                    if ($shwSelfOnly) {
                                        $selfOnlyCls = " and (a.assessed_person_id = " . $prsnid . ")";
                                    }
                                    if ($searchIn == "Assessment Sheet Name") {
                                        $whrcls = " and (a.assess_sheet_name ilike '" . loc_db_escape_string($searchWord) .
                                            "')";
                                    } else if ($searchIn == "Assessment Group") {
                                        $whrcls = " and (aca.get_class_nm(a.class_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                    } else if ($searchIn == "Assessment Type") {
                                        $whrcls = " and (aca.get_assesstypnm(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_assesstyp(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                    } else if ($searchIn == "Programme/Objective") {
                                        $whrcls = " and (aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "')";
                                    } else if ($searchIn == "Subject/Task") {
                                        $whrcls = " and (aca.get_subjectnm(a.subject_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "')";
                                    } else if ($searchIn == "Assessment Period") {
                                        $whrcls = " and (aca.get_period_nm(a.academic_period_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "')";
                                    } else if ($searchIn == "Administrator") {
                                        $whrcls = " and (prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' ilike '" . loc_db_escape_string($searchWord) . "')";
                                    } else if ($searchIn == "Person Assessed") {
                                        $whrcls = " and (prs.get_prsn_name(a.assessed_person_id) || ' (' || prs.get_prsn_loc_id(a.assessed_person_id)||')' ilike '" . loc_db_escape_string($searchWord) . "')";
                                    } else {
                                        $whrcls = " and (prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' ilike '" . loc_db_escape_string($searchWord) .
                                            "' or prs.get_prsn_name(a.assessed_person_id) || ' (' || prs.get_prsn_loc_id(a.assessed_person_id)||')' ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_subjectnm(a.subject_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_assesstypnm(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_assesstyp(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_class_nm(a.class_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "' or a.assess_sheet_name ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_period_nm(a.academic_period_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                    }

                                    $strSql = "SELECT a.assess_sheet_hdr_id, a.assess_sheet_name, 
                a.class_id, aca.get_class_nm(a.class_id) class_nm,
                a.assessment_type_id, aca.get_assesstypnm(a.assessment_type_id), 
                a.course_id, aca.get_coursenm(a.course_id), 
                a.subject_id, aca.get_subjectnm(a.subject_id),
                a.academic_period_id, aca.get_period_nm(a.academic_period_id),
                a.tutor_person_id, prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' full_nm, 
                aca.get_assesstyp(a.assessment_type_id), aca.get_assess_prsn_cnt(a.assess_sheet_hdr_id), a.assess_sheet_status,
                a.assessed_person_id, prs.get_prsn_name(a.assessed_person_id) || ' (' || prs.get_prsn_loc_id(a.assessed_person_id)||')' stu_full_nm,
                aca.get_assess_sbjct_cnt(a.assess_sheet_hdr_id)
            FROM aca.aca_assess_sheet_hdr a 
            WHERE((a.org_id = " . $orgID . " and aca.get_assesstyp(a.assessment_type_id)='" . $assessTypeFltr . "' and a.assess_sheet_status='Closed')" . $whrcls . $selfOnlyCls .
                                        ") ORDER BY a.assess_sheet_hdr_id DESC LIMIT " . $limit_size .
                                        " OFFSET " . abs($offset * $limit_size);
                                    $result = executeSQLNoParams($strSql);
                                    return $result;
                                }

                                function get_Total_AssessSheets($searchWord, $searchIn, $orgID, $shwSelfOnly, $assessTypeFltr = "Assessment Sheet Per Group")
                                {
                                    global $usrID;
                                    global $prsnid;
                                    $strSql = "";
                                    $whrcls = "";
                                    $selfOnlyCls = "";
                                    if ($shwSelfOnly) {
                                        $selfOnlyCls = " and (a.assessed_person_id = " . $prsnid . ")";
                                    }
                                    if ($searchIn == "Assessment Sheet Name") {
                                        $whrcls = " and (a.assess_sheet_name ilike '" . loc_db_escape_string($searchWord) .
                                            "')";
                                    } else if ($searchIn == "Assessment Group") {
                                        $whrcls = " and (aca.get_class_nm(a.class_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                    } else if ($searchIn == "Assessment Type") {
                                        $whrcls = " and (aca.get_assesstypnm(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_assesstyp(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                    } else if ($searchIn == "Programme/Objective") {
                                        $whrcls = " and (aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "')";
                                    } else if ($searchIn == "Subject/Task") {
                                        $whrcls = " and (aca.get_subjectnm(a.subject_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "')";
                                    } else if ($searchIn == "Assessment Period") {
                                        $whrcls = " and (aca.get_period_nm(a.academic_period_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "')";
                                    } else if ($searchIn == "Administrator") {
                                        $whrcls = " and (prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' ilike '" . loc_db_escape_string($searchWord) . "')";
                                    } else if ($searchIn == "Person Assessed") {
                                        $whrcls = " and (prs.get_prsn_name(a.assessed_person_id) || ' (' || prs.get_prsn_loc_id(a.assessed_person_id)||')' ilike '" . loc_db_escape_string($searchWord) . "')";
                                    } else {
                                        $whrcls = " and (prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' ilike '" . loc_db_escape_string($searchWord) .
                                            "' or prs.get_prsn_name(a.assessed_person_id) || ' (' || prs.get_prsn_loc_id(a.assessed_person_id)||')' ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_subjectnm(a.subject_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_assesstypnm(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_assesstyp(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_class_nm(a.class_id) ilike '" . loc_db_escape_string($searchWord) .
                                            "' or a.assess_sheet_name ilike '" . loc_db_escape_string($searchWord) .
                                            "' or aca.get_period_nm(a.academic_period_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                    }

                                    $strSql = "SELECT count(1)
            FROM aca.aca_assess_sheet_hdr a 
            WHERE((a.org_id = " . $orgID . " and aca.get_assesstyp(a.assessment_type_id)='" . $assessTypeFltr . "' and a.assess_sheet_status='Closed')" . $whrcls . $selfOnlyCls .
                                        ")";
                                    $result = executeSQLNoParams($strSql);
                                    while ($row = loc_db_fetch_array($result)) {
                                        return $row[0];
                                    }
                                    return 0;
                                }

                                function get_One_AssessSheetHdr($hdrID)
                                {
                                    $strSql = "SELECT a.assess_sheet_hdr_id, a.assess_sheet_name, 
                a.class_id, aca.get_class_nm(a.class_id) class_nm,
                a.assessment_type_id, aca.get_assesstypnm(a.assessment_type_id), 
                a.course_id, aca.get_coursenm(a.course_id), 
                a.subject_id, aca.get_subjectnm(a.subject_id),
                a.academic_period_id, aca.get_period_nm(a.academic_period_id),
                a.tutor_person_id, prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' full_nm, 
                aca.get_assesstyp(a.assessment_type_id), aca.get_assess_prsn_cnt(a.assess_sheet_hdr_id), 
                a.assess_sheet_desc, a.assess_sheet_status,
                a.assessed_person_id, prs.get_prsn_name(a.assessed_person_id) || ' (' || prs.get_prsn_loc_id(a.assessed_person_id)||')' stu_full_nm 
            FROM aca.aca_assess_sheet_hdr a  " .
                                        "WHERE((a.assess_sheet_hdr_id = " . $hdrID . " and a.assess_sheet_status='Closed'))";
                                    $result = executeSQLNoParams($strSql);
                                    return $result;
                                }

                                function get_One_AssessSheetHdr1($hdrID)
                                {
                                    $strSql = "SELECT a.assess_sheet_hdr_id, a.assess_sheet_name, 
                a.class_id, aca.get_class_nm(a.class_id) class_nm,
                a.assessment_type_id, aca.get_assesstypnm(a.assessment_type_id), 
                (CASE WHEN a.course_id<=0 THEN aca.get_max_courseid(a.assess_sheet_hdr_id) ELSE a.course_id END), 
                aca.get_coursenm((CASE WHEN a.course_id<=0 THEN aca.get_max_courseid(a.assess_sheet_hdr_id) ELSE a.course_id END)), 
                a.subject_id, aca.get_subjectnm(a.subject_id),
                a.academic_period_id, aca.get_period_nm(a.academic_period_id),
                a.tutor_person_id, prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' full_nm, 
                aca.get_assesstyp(a.assessment_type_id), aca.get_assess_prsn_cnt(a.assess_sheet_hdr_id), 
                a.assess_sheet_desc, a.assess_sheet_status,
                a.assessed_person_id, prs.get_prsn_name(a.assessed_person_id) stu_full_nm ,
                prs.get_prsn_loc_id(a.assessed_person_id) loc_id, prs.get_prsn_gender(a.assessed_person_id) gender, 
                to_char(to_timestamp(aca.get_period_end(a.academic_period_id), 'YYYY-MM-DD'), 'DD-Mon-YYYY') prd_end, 
                to_char(to_timestamp(aca.get_next_prd_start(a.academic_period_id), 'YYYY-MM-DD'), 'DD-Mon-YYYY') prd_start
            FROM aca.aca_assess_sheet_hdr a  " .
                                        "WHERE((a.assess_sheet_hdr_id = " . $hdrID . "))";
                                    $result = executeSQLNoParams($strSql);
                                    return $result;
                                }

                                function get_AssessShtPrsnsTtl($searchFor, $searchIn, $assessShtHdrID)
                                {
                                    $strSql = "";
                                    $whrcls = "";
                                    if ($searchIn == "ID/Full Name") {
                                        $whrcls = " AND (y3.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(y3.title || ' ' || y3.sur_name || " .
                                            "', ' || y3.first_name || ' ' || y3.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
                                    } else if ($searchIn == "Full Name") {
                                        $whrcls = " AND (trim(y3.title || ' ' || y3.sur_name || " .
                                            "', ' || y3.first_name || ' ' || y3.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
                                    }
                                    $strSql = "SELECT count(y1.ass_col_val_id) " .
                                        "FROM aca.aca_assmnt_col_vals y1,
     aca.aca_prsns_acdmc_sttngs y2,
     prs.prsn_names_nos y3,
     aca.aca_assess_sheet_hdr y4
WHERE y1.acdmc_sttngs_id = y2.acdmc_sttngs_id
  and y2.person_id = y3.person_id
  and y1.assess_sheet_hdr_id = y4.assess_sheet_hdr_id and y1.assess_sheet_hdr_id=" . $assessShtHdrID . $whrcls . "";
                                    $result = executeSQLNoParams($strSql);
                                    while ($row = loc_db_fetch_array($result)) {
                                        return $row[0];
                                    }
                                    return 0;
                                }

                                function get_AssessShtPrsns($searchFor, $searchIn, $offset, $limit_size, $assessShtHdrID)
                                {
                                    $strSql = "";
                                    $whrcls = "";
                                    if ($searchIn == "ID/Full Name") {
                                        $whrcls = " AND (y3.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(y3.title || ' ' || y3.sur_name || " .
                                            "', ' || y3.first_name || ' ' || y3.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
                                    } else if ($searchIn == "Full Name") {
                                        $whrcls = " AND (trim(y3.title || ' ' || y3.sur_name || " .
                                            "', ' || y3.first_name || ' ' || y3.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
                                    }
                                    $strSql = "SELECT y1.ass_col_val_id,
       y1.acdmc_sttngs_id,
       y1.assess_sheet_hdr_id,
       '1',
       y3.person_id,
       y3.local_id_no,
       trim(y3.title || ' ' || y3.sur_name || ', ' || y3.first_name || ' ' || y3.other_names) fullname,
       y4.assessment_type_id,
       y1.data_col1,
       y1.data_col2,
       y1.data_col3,
       y1.data_col4,
       y1.data_col5,
       y1.data_col6,
       y1.data_col7,
       y1.data_col8,
       y1.data_col9,
       y1.data_col10,
       y1.data_col11,
       y1.data_col12,
       y1.data_col13,
       y1.data_col14,
       y1.data_col15,
       y1.data_col16,
       y1.data_col17,
       y1.data_col18,
       y1.data_col19,
       y1.data_col20,
       y1.data_col21,
       y1.data_col22,
       y1.data_col23,
       y1.data_col24,
       y1.data_col25,
       y1.data_col26,
       y1.data_col27,
       y1.data_col28,
       y1.data_col29,
       y1.data_col30,
       y1.data_col31,
       y1.data_col32,
       y1.data_col33,
       y1.data_col34,
       y1.data_col35,
       y1.data_col36,
       y1.data_col37,
       y1.data_col38,
       y1.data_col39,
       y1.data_col40,
       y1.data_col41,
       y1.data_col42,
       y1.data_col43,
       y1.data_col44,
       y1.data_col45,
       y1.data_col46,
       y1.data_col47,
       y1.data_col48,
       y1.data_col49,
       y1.data_col50
FROM aca.aca_assmnt_col_vals y1,
     aca.aca_prsns_acdmc_sttngs y2,
     prs.prsn_names_nos y3,
     aca.aca_assess_sheet_hdr y4
WHERE y1.acdmc_sttngs_id = y2.acdmc_sttngs_id
  and y2.person_id = y3.person_id
  and y1.assess_sheet_hdr_id = y4.assess_sheet_hdr_id and y1.assess_sheet_hdr_id=" . $assessShtHdrID . $whrcls . " ORDER BY y3.local_id_no LIMIT " . $limit_size .
                                        " OFFSET " . abs($offset * $limit_size);
                                    //echo $strSql;
                                    $result = executeSQLNoParams($strSql);
                                    return $result;
                                }

                                function get_ReportCardLnsTtl($searchFor, $searchIn, $assessShtHdrID)
                                {
                                    $strSql = "";
                                    $whrcls = "";
                                    global $courseLabel;
                                    global $sbjctLabel;
                                    if ($searchIn == $courseLabel . " Name") {
                                        $whrcls = " AND (aca.get_coursenm(y1.course_id) ilike '" . loc_db_escape_string($searchFor) . "')";
                                    } else if ($searchIn == $sbjctLabel . " Name") {
                                        $whrcls = " AND (aca.get_subjectnm(y1.subject_id) ilike '" . loc_db_escape_string($searchFor) . "')";
                                    }
                                    $strSql = "SELECT count(y1.ass_col_val_id) " .
                                        "FROM aca.aca_assmnt_col_vals y1,
     aca.aca_prsns_acdmc_sttngs y2,
     aca.aca_assess_sheet_hdr y4
WHERE y1.acdmc_sttngs_id = y2.acdmc_sttngs_id
  and y1.assess_sheet_hdr_id = y4.assess_sheet_hdr_id 
  and y1.assess_sheet_hdr_id=" . $assessShtHdrID . $whrcls . "";
                                    $result = executeSQLNoParams($strSql);
                                    while ($row = loc_db_fetch_array($result)) {
                                        return $row[0];
                                    }
                                    return 0;
                                }

                                function get_ReportCardLns($searchFor, $searchIn, $offset, $limit_size, $assessShtHdrID)
                                {
                                    $strSql = "";
                                    $whrcls = "";
                                    global $courseLabel;
                                    global $sbjctLabel;
                                    if ($searchIn == $courseLabel . " Name") {
                                        $whrcls = " AND (aca.get_coursenm(y1.course_id) ilike '" . loc_db_escape_string($searchFor) . "')";
                                    } else if ($searchIn == $sbjctLabel . " Name") {
                                        $whrcls = " AND (aca.get_subjectnm(y1.subject_id) ilike '" . loc_db_escape_string($searchFor) . "')";
                                    }
                                    $strSql = "SELECT y1.ass_col_val_id,
       y1.acdmc_sttngs_id,
       y1.assess_sheet_hdr_id,
       '1',
       y2.person_id,
      (CASE WHEN y1.subject_id<=0 THEN aca.get_coursenm(y1.course_id) ELSE '' END),
      (CASE WHEN y1.subject_id>0 THEN aca.get_subjectnm(y1.subject_id) ELSE '' END),
       y4.assessment_type_id,
       y1.data_col1,
       y1.data_col2,
       y1.data_col3,
       y1.data_col4,
       y1.data_col5,
       y1.data_col6,
       y1.data_col7,
       y1.data_col8,
       y1.data_col9,
       y1.data_col10,
       y1.data_col11,
       y1.data_col12,
       y1.data_col13,
       y1.data_col14,
       y1.data_col15,
       y1.data_col16,
       y1.data_col17,
       y1.data_col18,
       y1.data_col19,
       y1.data_col20,
       y1.data_col21,
       y1.data_col22,
       y1.data_col23,
       y1.data_col24,
       y1.data_col25,
       y1.data_col26,
       y1.data_col27,
       y1.data_col28,
       y1.data_col29,
       y1.data_col30,
       y1.data_col31,
       y1.data_col32,
       y1.data_col33,
       y1.data_col34,
       y1.data_col35,
       y1.data_col36,
       y1.data_col37,
       y1.data_col38,
       y1.data_col39,
       y1.data_col40,
       y1.data_col41,
       y1.data_col42,
       y1.data_col43,
       y1.data_col44,
       y1.data_col45,
       y1.data_col46,
       y1.data_col47,
       y1.data_col48,
       y1.data_col49,
       y1.data_col50
FROM aca.aca_assmnt_col_vals y1,
     aca.aca_prsns_acdmc_sttngs y2,
     aca.aca_assess_sheet_hdr y4
WHERE y1.acdmc_sttngs_id = y2.acdmc_sttngs_id
  and y1.assess_sheet_hdr_id = y4.assess_sheet_hdr_id 
  and y1.assess_sheet_hdr_id=" . $assessShtHdrID . $whrcls .
                                        " ORDER BY aca.get_coursenm(y1.course_id), aca.get_subjectnm(y1.subject_id)  LIMIT " . $limit_size .
                                        " OFFSET " . abs($offset * $limit_size);
                                    //echo $strSql;
                                    $result = executeSQLNoParams($strSql);
                                    return $result;
                                }

                                function get_ReportCardLns1($searchFor, $searchIn, $offset, $limit_size, $assessShtHdrID)
                                {
                                    $strSql = "";
                                    $whrcls = "";
                                    global $courseLabel;
                                    global $sbjctLabel;
                                    if ($searchIn == $courseLabel . " Name") {
                                        $whrcls = " AND (aca.get_coursenm(y1.course_id) ilike '" . loc_db_escape_string($searchFor) . "')";
                                    } else if ($searchIn == $sbjctLabel . " Name") {
                                        $whrcls = " AND (aca.get_subjectnm(y1.subject_id) ilike '" . loc_db_escape_string($searchFor) . "')";
                                    }
                                    $strSql = "SELECT y1.ass_col_val_id,
       y1.acdmc_sttngs_id,
       y1.assess_sheet_hdr_id,
       '1',
       y2.person_id,
      (CASE WHEN y1.subject_id<=0 THEN aca.get_coursenm(y1.course_id) ELSE '' END),
      (CASE WHEN y1.subject_id>0 THEN aca.get_subjectnm(y1.subject_id) ELSE '' END),
       y4.assessment_type_id,
       y1.data_col1,
       y1.data_col2,
       y1.data_col3,
       y1.data_col4,
       y1.data_col5,
       y1.data_col6,
       y1.data_col7,
       y1.data_col8,
       y1.data_col9,
       y1.data_col10,
       y1.data_col11,
       y1.data_col12,
       y1.data_col13,
       y1.data_col14,
       y1.data_col15,
       y1.data_col16,
       y1.data_col17,
       y1.data_col18,
       y1.data_col19,
       y1.data_col20,
       y1.data_col21,
       y1.data_col22,
       y1.data_col23,
       y1.data_col24,
       y1.data_col25,
       y1.data_col26,
       y1.data_col27,
       y1.data_col28,
       y1.data_col29,
       y1.data_col30,
       y1.data_col31,
       y1.data_col32,
       y1.data_col33,
       y1.data_col34,
       y1.data_col35,
       y1.data_col36,
       y1.data_col37,
       y1.data_col38,
       y1.data_col39,
       y1.data_col40,
       y1.data_col41,
       y1.data_col42,
       y1.data_col43,
       y1.data_col44,
       y1.data_col45,
       y1.data_col46,
       y1.data_col47,
       y1.data_col48,
       y1.data_col49,
       y1.data_col50
FROM aca.aca_assmnt_col_vals y1,
     aca.aca_prsns_acdmc_sttngs y2,
     aca.aca_assess_sheet_hdr y4
WHERE y1.acdmc_sttngs_id = y2.acdmc_sttngs_id
  and y1.assess_sheet_hdr_id = y4.assess_sheet_hdr_id 
  and y1.subject_id>0
  and y1.assess_sheet_hdr_id=" . $assessShtHdrID . $whrcls .
                                        " ORDER BY aca.get_coursenm(y1.course_id), aca.get_subjectnm(y1.subject_id)  LIMIT " . $limit_size .
                                        " OFFSET " . abs($offset * $limit_size);
                                    //echo $strSql;
                                    $result = executeSQLNoParams($strSql);
                                    return $result;
                                }

                                function get_AssessShtGrpCols($grpnm, $assesstyp_ID)
                                {
                                    $strSql = "SELECT column_id, column_name, column_header_text, '', 
                data_type, section_located, data_length, '', 
                assmnt_typ_id, 0, column_name, '','0' is_required, is_formula_column, column_formular, column_no,
                col_min_val, col_max_val, is_dsplyd, html_css_style
            FROM aca.aca_assessment_columns 
            WHERE section_located = '" . loc_db_escape_string($grpnm) .
                                        "' and assmnt_typ_id = " . $assesstyp_ID .
                                        " ORDER BY column_name, column_id";
                                    //logSessionErrs($strSql);
                                    $result = executeSQLNoParams($strSql);
                                    return $result;
                                }

                                function get_AssessShtColVal($pkID, $sttngID, $colNum)
                                {
                                    $colNms = array(
                                        "data_col1", "data_col2", "data_col3", "data_col4",
                                        "data_col5", "data_col6", "data_col7", "data_col8", "data_col9", "data_col10",
                                        "data_col11", "data_col12", "data_col13", "data_col14", "data_col15", "data_col16",
                                        "data_col17", "data_col18", "data_col19", "data_col20", "data_col21", "data_col22",
                                        "data_col23", "data_col24", "data_col25", "data_col26", "data_col27", "data_col28",
                                        "data_col29", "data_col30", "data_col31", "data_col32", "data_col33", "data_col34",
                                        "data_col35", "data_col36", "data_col37", "data_col38", "data_col39", "data_col40",
                                        "data_col41", "data_col42", "data_col43", "data_col44", "data_col45", "data_col46",
                                        "data_col47", "data_col48", "data_col49", "data_col50"
                                    );

                                    $strSql = "SELECT " . $colNms[$colNum - 1] . " 
        FROM aca.aca_assmnt_col_vals a WHERE ((assess_sheet_hdr_id = " . $pkID . " and acdmc_sttngs_id = " . $sttngID . "))";
                                    $result = executeSQLNoParams($strSql);
                                    while ($row = loc_db_fetch_array($result)) {
                                        return $row[0];
                                    }
                                    return "";
                                    //aca.get_col_data_type(" . $pkID . ", " . $colNum . ")='Number
                                }

                                function get_AssessShtColValOLD($pkID, $sttngID, $columnID)
                                {
                                    $strSql = "SELECT (CASE WHEN is_val_a_number='1' THEN ''|| public.chartonumeric(col_value_2) ELSE col_value_2 END)
        FROM aca.aca_prsn_assmnt_col_vals a WHERE ((assess_sheet_hdr_id = " . $pkID .
                                        " and acdmc_sttngs_id = " . $sttngID .
                                        " and column_id = " . $columnID . "))";
                                    $result = executeSQLNoParams($strSql);
                                    while ($row = loc_db_fetch_array($result)) {
                                        return $row[0];
                                    }
                                    return "";
                                }

                                function getNew_AssessShtHdrID()
                                {
                                    $strSql = "select nextval('aca.aca_assess_sheet_hdr_assess_sheet_hdr_id_seq')";
                                    $result = executeSQLNoParams($strSql);
                                    if (loc_db_num_rows($result) > 0) {
                                        $row = loc_db_fetch_array($result);
                                        return (float) $row[0];
                                    }
                                    return -1;
                                }

                                function get_AssessShtHdrID($assessShtName, $orgid)
                                {
                                    $strSql = "select assess_sheet_hdr_id from aca.aca_assess_sheet_hdr where lower(assess_sheet_name) = lower('"
                                        . loc_db_escape_string($assessShtName) . "') and org_id = " . $orgid;
                                    $result = executeSQLNoParams($strSql);
                                    if (loc_db_num_rows($result) > 0) {
                                        $row = loc_db_fetch_array($result);
                                        return (float) $row[0];
                                    }
                                    return -1;
                                }

                                function getPDivGrpID($prsnID, $divGrpID)
                                {
                                    $sqlStr = "SELECT prsn_div_id from pasn.prsn_divs_groups where (div_id = " . $divGrpID . " AND person_id = $prsnID)";
                                    $result = executeSQLNoParams($sqlStr);
                                    while ($row = loc_db_fetch_array($result)) {
                                        return (float) $row[0];
                                    }
                                    return -1;
                                }

                                function createPDivGrp($prsnID, $divGrpID, $pDivGrpStartDate, $pDivGrpEndDate)
                                {
                                    global $usrID;
                                    $dateStr = getDB_Date_time();
                                    $insSQL = "INSERT INTO pasn.prsn_divs_groups(person_id, div_id, valid_start_date, valid_end_date, created_by, creation_date,
                                  last_update_by, last_update_date)
                                            VALUES (" . $prsnID . ","
                                        . $divGrpID . ",'"
                                        . cnvrtDMYToYMD($pDivGrpStartDate) . "','"
                                        . cnvrtDMYToYMD($pDivGrpEndDate) . "',"
                                        . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
                                    return execUpdtInsSQL($insSQL);
                                }

                                function updatePDivGrp($pkeyID, $divGrpID, $pDivGrpStartDate, $pDivGrpEndDate)
                                {
                                    global $usrID;
                                    $dateStr = getDB_Date_time();
                                    $updtSQL = "UPDATE pasn.prsn_divs_groups
        SET div_id=" . $divGrpID .
                                        ", valid_start_date='" . cnvrtDMYToYMD($pDivGrpStartDate) .
                                        "', valid_end_date='" . cnvrtDMYToYMD($pDivGrpEndDate) .
                                        "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
                                        "' WHERE prsn_div_id = " . $pkeyID;
                                    return execUpdtInsSQL($updtSQL, "Update of Division/Group");
                                }

                                function deletePDivGrp($pkeyID)
                                {
                                    $insSQL = "DELETE FROM pasn.prsn_divs_groups WHERE prsn_div_id = " . $pkeyID;
                                    $affctd1 = execUpdtInsSQL($insSQL, "Remove Person's Division/Group");
                                    if ($affctd1 > 0) {
                                        $dsply = "Successfully Deleted the ff Records-";
                                        $dsply .= "<br/>$affctd1 Division/Group(s)!";
                                        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                                    } else {
                                        $dsply = "No Record Deleted";
                                        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                                    }
                                }

?>