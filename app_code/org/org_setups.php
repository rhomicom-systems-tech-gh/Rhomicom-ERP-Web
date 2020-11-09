<?php
$canAddOrg = test_prmssns($dfltPrvldgs[14], $mdlNm);
$canEdtOrg = test_prmssns($dfltPrvldgs[15], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 20;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            } else if ($actyp == 2) {
                /* Delete Division/Group */
                $canDelRec = test_prmssns($dfltPrvldgs[18], $mdlNm);
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDelRec) {
                    echo deleteDiv($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Sites/Locs */
                $canDelRec = test_prmssns($dfltPrvldgs[21], $mdlNm);
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDelRec) {
                    echo deleteSite($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 4) {
                /* Delete Jobs */
                $canDelRec = test_prmssns($dfltPrvldgs[24], $mdlNm);
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDelRec) {
                    echo deleteJob($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                /* Delete Grade */
                $canDelRec = test_prmssns($dfltPrvldgs[27], $mdlNm);
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDelRec) {
                    echo deleteGrd($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 6) {
                /* Delete Position */
                $canDelRec = test_prmssns($dfltPrvldgs[30], $mdlNm);
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDelRec) {
                    echo deletePos($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 7) {
                /* Delete Account Segment */
                $canDelRec = test_prmssns($dfltPrvldgs[15], $mdlNm);
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDelRec) {
                    echo deleteSegment($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 8) {
                /* Delete Account Segment Value */
                $canDelRec = test_prmssns($dfltPrvldgs[15], $mdlNm);
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDelRec) {
                    echo deleteSgmntVal($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 9) {
                /* Delete Account Segment Value Report Classification */
                $canDelRec = test_prmssns($dfltPrvldgs[15], $mdlNm);
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDelRec) {
                    echo deleteRptClsfctn($pKeyID);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Org Details
                header("content-type:application/json");
                $orgDetOrgID = isset($_POST['orgDetOrgID']) ? (int) cleanInputData($_POST['orgDetOrgID']) : -1;
                $orgDetOrgNm = isset($_POST['orgDetOrgNm']) ? cleanInputData($_POST['orgDetOrgNm']) : "";
                $orgDetPrntOrgID = isset($_POST['orgDetPrntOrgID']) ? (int) cleanInputData($_POST['orgDetPrntOrgID']) : -1;
                $orgDetResAdrs = isset($_POST['orgDetResAdrs']) ? cleanInputData($_POST['orgDetResAdrs']) : "";
                $orgDetPosAdrs = isset($_POST['orgDetPosAdrs']) ? cleanInputData($_POST['orgDetPosAdrs']) : "";
                $orgDetEmail = isset($_POST['orgDetEmail']) ? cleanInputData($_POST['orgDetEmail']) : '';
                $orgDetWebsites = isset($_POST['orgDetWebsites']) ? cleanInputData($_POST['orgDetWebsites']) : '';
                $orgDetOrgTyp = isset($_POST['orgDetOrgTyp']) ? cleanInputData($_POST['orgDetOrgTyp']) : '';
                $orgDetOrgTypID = getPssblValID($orgDetOrgTyp, getLovID("Organisation Types"));
                $orgDetLogo = isset($_POST['orgDetLogo']) ? cleanInputData($_POST['orgDetLogo']) : '';
                $orgDetFuncCrncy = isset($_POST['orgDetFuncCrncy']) ? cleanInputData($_POST['orgDetFuncCrncy']) : "GHS";
                $orgDetFuncCrncyID = getPssblValID($orgDetFuncCrncy, getLovID("Currencies"));
                $orgDetCntctNums = isset($_POST['orgDetCntctNums']) ? cleanInputData($_POST['orgDetCntctNums']) : '';
                $orgDetIsEnabled = isset($_POST['orgDetIsEnabled']) ? cleanInputData($_POST['orgDetIsEnabled']) : "NO";
                $orgDetOrgDesc = isset($_POST['orgDetOrgDesc']) ? cleanInputData($_POST['orgDetOrgDesc']) : '';
                $orgDetOrgSlogan = isset($_POST['orgDetOrgSlogan']) ? cleanInputData($_POST['orgDetOrgSlogan']) : '';

                $orgDetNoOfSegmnts = isset($_POST['orgDetNoOfSegmnts']) ? (int) cleanInputData($_POST['orgDetNoOfSegmnts']) : 1;
                $orgDetSegDelimiter = isset($_POST['orgDetSegDelimiter']) ? cleanInputData($_POST['orgDetSegDelimiter']) : '';
                $orgDetLocSgmtNum = isset($_POST['orgDetLocSgmtNum']) ? (int) cleanInputData($_POST['orgDetLocSgmtNum']) : 0;
                $orgDetSublocSgmtNum = isset($_POST['orgDetSublocSgmtNum']) ? (int) cleanInputData($_POST['orgDetSublocSgmtNum']) : 0;
                $isenbld = false;
                if ($orgDetIsEnabled == "YES") {
                    $isenbld = true;
                }
                $oldOrgID = getOrgnstnID($orgDetOrgNm);
                if ($orgDetOrgNm != "" && $orgDetOrgTypID > 0 && $orgDetFuncCrncyID > 0 && $orgDetCntctNums != "" && ($oldOrgID <= 0 || $oldOrgID == $orgDetOrgID)) {
                    if ($orgDetOrgID <= 0) {
                        createOrg($orgDetOrgNm, $orgDetPrntOrgID, $orgDetResAdrs, $orgDetPosAdrs, $orgDetWebsites, $orgDetFuncCrncyID, $orgDetEmail,
                                $orgDetCntctNums, $orgDetOrgTypID, $isenbld, $orgDetOrgDesc, $orgDetOrgSlogan, $orgDetNoOfSegmnts,
                                $orgDetSegDelimiter, $orgDetLocSgmtNum, $orgDetSublocSgmtNum);
                        $orgDetOrgID = getOrgnstnID($orgDetOrgNm);
                    } else {
                        updateOrgDet($orgDetOrgID, $orgDetOrgNm, $orgDetPrntOrgID, $orgDetResAdrs, $orgDetPosAdrs, $orgDetWebsites,
                                $orgDetFuncCrncyID, $orgDetEmail, $orgDetCntctNums, $orgDetOrgTypID, $isenbld, $orgDetOrgDesc, $orgDetOrgSlogan,
                                $orgDetNoOfSegmnts, $orgDetSegDelimiter, $orgDetLocSgmtNum, $orgDetSublocSgmtNum);
                    }
                    //var_dump($_POST);
                    $nwImgLoc = "";
                    if ($orgDetOrgID > 0) {
                        if (isset($_FILES["daOrgPicture"])) {
                            uploadDaOrgImage($orgDetOrgID, $nwImgLoc);
                        }
                        $rowid = getGnrlRecID("scm.scm_dflt_accnts", "rho_name", "row_id", "Default Accounts", $orgDetOrgID);
                        if ($rowid <= 0) {
                            createDfltAcnts($orgDetOrgID);
                        }
                        updtOrgAccntCurrID($orgDetOrgID, $orgDetFuncCrncyID);
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['orgDetOrgID'] = $orgDetOrgID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Organisation Successfully Saved!";
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['orgDetOrgID'] = -1;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Organisation <br/>or Data Supplied is Incomplete!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                //Div/Grp
                //var_dump($_POST);
                $afftctd = 0;
                $orgDetOrgID = isset($_POST['orgDetOrgID']) ? (int) cleanInputData($_POST['orgDetOrgID']) : -1;
                $slctdDivsGrps = isset($_POST['slctdDivsGrps']) ? cleanInputData($_POST['slctdDivsGrps']) : '';
                if (trim($slctdDivsGrps, "|~") != "") {
                    //Save Div/Group
                    $variousRows = explode("|", trim($slctdDivsGrps, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 6) {
                            $divGrpID = (int) (cleanInputData1($crntRow[0]));
                            $divGrpNm = cleanInputData1($crntRow[1]);
                            $divGrpPrntID = (int) (cleanInputData1($crntRow[2]));
                            $divTypeNm = cleanInputData1($crntRow[3]);
                            $divtypID = getPssblValID($divTypeNm, getLovID("Divisions or Group Types"));
                            $divGrpDesc = cleanInputData1($crntRow[4]);
                            $isEnbld = cleanInputData1($crntRow[5]) == "Yes" ? TRUE : FALSE;
                            $oldDivGrpID = getDivGrpID($divGrpNm, $orgDetOrgID);
                            if ($divGrpNm != "" && $divtypID > 0) {
                                if ($oldDivGrpID <= 0 && $divGrpID <= 0) {
                                    //Insert
                                    $afftctd += createDiv($orgDetOrgID, $divGrpNm, $divGrpPrntID, $divtypID, $isEnbld, $divGrpDesc);
                                } else if ($divGrpID > 0) {
                                    $afftctd += updateDivDet($divGrpID, $divGrpNm, $divGrpPrntID, $divtypID, $isEnbld, $divGrpDesc);
                                }
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $afftctd; ?>Divisions/Groups Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Save Divisions/Groups!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 3) {
                //Sites/Loc
                //var_dump($_POST);
                //exit();
                $afftctd = 0;
                $orgDetOrgID = isset($_POST['orgDetOrgID']) ? (int) cleanInputData($_POST['orgDetOrgID']) : -1;
                $slctdSitesLocs = isset($_POST['slctdSitesLocs']) ? cleanInputData($_POST['slctdSitesLocs']) : '';
                if (trim($slctdSitesLocs, "|~") != "") {
                    //Save Div/Group
                    $variousRows = explode("|", trim($slctdSitesLocs, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 9) {
                            $siteLocID = (int) (cleanInputData1($crntRow[0]));
                            $siteLocNm = cleanInputData1($crntRow[1]);
                            $siteLocDesc = cleanInputData1($crntRow[2]);
                            $siteType = cleanInputData1($crntRow[3]);
                            $siteTypeID = getPssblValID($siteType, getLovID("Site Types"));
                            $allwdGrpType = cleanInputData1($crntRow[4]);
                            $allwdGrpID = cleanInputData1($crntRow[5]);
                            $lnkdDivID = (int) (cleanInputData1($crntRow[6]));
                            $isEnbld = cleanInputData1($crntRow[7]) == "Yes" ? TRUE : FALSE;
                            $prntSiteID = (int) (cleanInputData1($crntRow[8]));
                            $oldSiteLocID = getSiteID($siteLocNm, $orgDetOrgID);
                            if ($siteLocNm != "" && $siteLocDesc != "" && $allwdGrpType != "" && $siteTypeID > 0) {
                                if ($oldSiteLocID <= 0 && $siteLocID <= 0) {
                                    //Insert
                                    $afftctd += createSite($orgDetOrgID, $siteLocNm, $siteLocDesc, $isEnbld, $allwdGrpType, $allwdGrpID, $siteTypeID,
                                            $lnkdDivID, $prntSiteID);
                                } else if ($siteLocID > 0) {
                                    $afftctd += updateSiteDet($siteLocID, $siteLocNm, $siteLocDesc, $isEnbld, $allwdGrpType, $allwdGrpID, $siteTypeID,
                                            $lnkdDivID, $prntSiteID);
                                }
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $afftctd; ?>Sites/Locations Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Save Sites/Locations!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 4) {
                //Jobs
                //var_dump($_POST);
                //exit();
                $afftctd = 0;
                $orgDetOrgID = isset($_POST['orgDetOrgID']) ? (int) cleanInputData($_POST['orgDetOrgID']) : -1;
                $slctdOrgJobs = isset($_POST['slctdOrgJobs']) ? cleanInputData($_POST['slctdOrgJobs']) : '';
                if (trim($slctdOrgJobs, "|~") != "") {
                    //Save Div/Group
                    $variousRows = explode("|", trim($slctdOrgJobs, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 5) {
                            $jobID = (int) (cleanInputData1($crntRow[0]));
                            $jobNm = cleanInputData1($crntRow[1]);
                            $prntJobID = (int) (cleanInputData1($crntRow[2]));
                            $jobDesc = cleanInputData1($crntRow[3]);
                            $isEnbld = cleanInputData1($crntRow[4]) == "Yes" ? TRUE : FALSE;
                            $oldJobID = getJobID($jobNm, $orgDetOrgID);
                            if ($jobNm != "") {
                                if ($oldJobID <= 0 && $jobID <= 0) {
                                    //Insert
                                    $afftctd += createJob($orgDetOrgID, $jobNm, $prntJobID, $jobDesc, $isEnbld);
                                } else if ($jobID > 0) {
                                    $afftctd += updateJob($jobID, $jobNm, $prntJobID, $jobDesc, $isEnbld);
                                }
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $afftctd; ?>Job(s) Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Save Jobs!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 5) {
                //Grades
                //var_dump($_POST);
                //exit();
                $afftctd = 0;
                $orgDetOrgID = isset($_POST['orgDetOrgID']) ? (int) cleanInputData($_POST['orgDetOrgID']) : -1;
                $slctdOrgGrades = isset($_POST['slctdOrgGrades']) ? cleanInputData($_POST['slctdOrgGrades']) : '';
                if (trim($slctdOrgGrades, "|~") != "") {
                    //Save Div/Group
                    $variousRows = explode("|", trim($slctdOrgGrades, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 5) {
                            $gradeID = (int) (cleanInputData1($crntRow[0]));
                            $gradeNm = cleanInputData1($crntRow[1]);
                            $prntGradeID = (int) (cleanInputData1($crntRow[2]));
                            $gradeDesc = cleanInputData1($crntRow[3]);
                            $isEnbld = cleanInputData1($crntRow[4]) == "Yes" ? TRUE : FALSE;
                            $oldGradeID = getGradeID($gradeNm, $orgDetOrgID);
                            if ($gradeNm != "") {
                                if ($oldGradeID <= 0 && $gradeID <= 0) {
                                    //Insert
                                    $afftctd += createGrd($orgDetOrgID, $gradeNm, $prntGradeID, $gradeDesc, $isEnbld);
                                } else if ($gradeID > 0) {
                                    $afftctd += updateGrd($gradeID, $gradeNm, $prntGradeID, $gradeDesc, $isEnbld);
                                }
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $afftctd; ?>Grade(s) Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Save Grades!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 6) {
                //Positions
                //var_dump($_POST);
                //exit();
                $afftctd = 0;
                $orgDetOrgID = isset($_POST['orgDetOrgID']) ? (int) cleanInputData($_POST['orgDetOrgID']) : -1;
                $slctdOrgPositions = isset($_POST['slctdOrgPositions']) ? cleanInputData($_POST['slctdOrgPositions']) : '';
                if (trim($slctdOrgPositions, "|~") != "") {
                    //Save Div/Group
                    $variousRows = explode("|", trim($slctdOrgPositions, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 5) {
                            $posID = (int) (cleanInputData1($crntRow[0]));
                            $posNm = cleanInputData1($crntRow[1]);
                            $prntPosID = (int) (cleanInputData1($crntRow[2]));
                            $posDesc = cleanInputData1($crntRow[3]);
                            $isEnbld = cleanInputData1($crntRow[4]) == "Yes" ? TRUE : FALSE;
                            $oldPosID = getPosID($posNm, $orgDetOrgID);
                            if ($posNm != "") {
                                if ($oldPosID <= 0 && $posID <= 0) {
                                    //Insert
                                    $afftctd += createPos($orgDetOrgID, $posNm, $prntPosID, $posDesc, $isEnbld);
                                } else if ($posID > 0) {
                                    $afftctd += updatePos($posID, $posNm, $prntPosID, $posDesc, $isEnbld);
                                }
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $afftctd; ?>Position(s) Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Save Positions!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 7) {
                //Account Segments
                //var_dump($_POST);
                //exit();
                $afftctd = 0;
                $orgDetOrgID = isset($_POST['orgDetOrgID']) ? (int) cleanInputData($_POST['orgDetOrgID']) : -1;
                $slctdSegments = isset($_POST['slctdSegments']) ? cleanInputData($_POST['slctdSegments']) : '';
                if (trim($slctdSegments, "|~") != "") {
                    //Save Account Segments
                    $variousRows = explode("|", trim($slctdSegments, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 5 || count($crntRow) == 5) {
                            $segmentID = (int) (cleanInputData1($crntRow[0]));
                            $segmentNum = cleanInputData1($crntRow[1]);
                            $segmentName = cleanInputData1($crntRow[2]);
                            $segmentClsfctn = cleanInputData1($crntRow[3]);
                            $prntSegmntNum = (int) (cleanInputData1($crntRow[4]));
                            $oldSegID = get_SegmnetID($orgDetOrgID, $segmentNum);
                            if ($segmentName != "" && $segmentNum > 0 && $segmentNum <= 10) {
                                if ($oldSegID <= 0 && $segmentID <= 0) {
                                    $afftctd += createAcntSegment($orgDetOrgID, $segmentNum, $segmentName, $segmentClsfctn, $prntSegmntNum);
                                } else if ($segmentID > 0) {
                                    $afftctd += updtAcntSegment($segmentID, $segmentNum, $segmentName, $segmentClsfctn, $prntSegmntNum);
                                }
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $afftctd; ?>Account Segment(s) Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Save Account Segments!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 8) {
                //Save Segment Values
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $orgDetOrgID = isset($_POST['sgValOrgDetOrgID']) ? (int) cleanInputData($_POST['sgValOrgDetOrgID']) : $orgID;
                $segmentValueID = isset($_POST['segmentValueID']) ? (int) cleanInputData($_POST['segmentValueID']) : -1;
                $sbmtdSegmentID = isset($_POST['sbmtdSegmentID']) ? (int) cleanInputData($_POST['sbmtdSegmentID']) : -1;
                $segmentValue = isset($_POST['segmentValue']) ? cleanInputData($_POST['segmentValue']) : "";
                $segmentValueDesc = isset($_POST['segmentValueDesc']) ? cleanInputData($_POST['segmentValueDesc']) : '';
                $dpndntSegmentID = isset($_POST['dpndntSegmentID']) ? (int) cleanInputData($_POST['dpndntSegmentID']) : -1;
                $sbmtdSegmentClsfctn = isset($_POST['sbmtdSegmentClsfctn']) ? cleanInputData($_POST['sbmtdSegmentClsfctn']) : "";
                $prntSegmentValueID = isset($_POST['prntSegmentValueID']) ? cleanInputData($_POST['prntSegmentValueID']) : "-1";
                $prntSegmentValue = isset($_POST['prntSegmentValue']) ? cleanInputData($_POST['prntSegmentValue']) : '';
                if ($prntSegmentValueID != "" && $sbmtdSegmentID > 0) {
                    $prntSegmentValueID = getSgmntValID($prntSegmentValueID, $sbmtdSegmentID);
                } else {
                    $prntSegmentValueID = -1;
                }
                $dpndntSegmentValueID = isset($_POST['dpndntSegmentValueID']) ? cleanInputData($_POST['dpndntSegmentValueID']) : "";
                $dpndntSegmentValue = isset($_POST['dpndntSegmentValue']) ? cleanInputData($_POST['dpndntSegmentValue']) : '';
                if ($dpndntSegmentValueID != "" && $dpndntSegmentID > 0) {
                    $dpndntSegmentValueID = getSgmntValID($dpndntSegmentValueID, $dpndntSegmentID);
                } else {
                    $dpndntSegmentValueID = -1;
                }
                $sgValLnkdSiteLocID = isset($_POST['sgValLnkdSiteLocID']) ? (int) cleanInputData($_POST['sgValLnkdSiteLocID']) : -1;
                $sgValLnkdSiteLoc = isset($_POST['sgValLnkdSiteLoc']) ? cleanInputData($_POST['sgValLnkdSiteLoc']) : '';
                $sgValAllwdGrpType = isset($_POST['sgValAllwdGrpType']) ? cleanInputData($_POST['sgValAllwdGrpType']) : '';
                $sgValAllwdGrpID = isset($_POST['sgValAllwdGrpID']) ? cleanInputData($_POST['sgValAllwdGrpID']) : "-1";
                $sgValAllwdGrpValue = isset($_POST['sgValAllwdGrpValue']) ? cleanInputData($_POST['sgValAllwdGrpValue']) : '';

                $sgValIsEnabled = isset($_POST['sgValIsEnabled']) ? (cleanInputData($_POST['sgValIsEnabled']) === "YES" ? TRUE : FALSE) : FALSE;
                $sgValCmbntnsAllwd = isset($_POST['sgValCmbntnsAllwd']) ? (cleanInputData($_POST['sgValCmbntnsAllwd']) === "YES" ? TRUE : FALSE) : FALSE;
                $sgValIsPrntAcnt = isset($_POST['sgValIsPrntAcnt']) ? (cleanInputData($_POST['sgValIsPrntAcnt']) === "YES" ? TRUE : FALSE) : FALSE;
                $sgValIsContraAcnt = isset($_POST['sgValIsContraAcnt']) ? (cleanInputData($_POST['sgValIsContraAcnt']) === "YES" ? TRUE : FALSE) : FALSE;
                $sgValIsRetErngsAcnt = isset($_POST['sgValIsRetErngsAcnt']) ? (cleanInputData($_POST['sgValIsRetErngsAcnt']) === "YES" ? TRUE : FALSE) : FALSE;
                $sgValIsNetIncmAcnt = isset($_POST['sgValIsNetIncmAcnt']) ? (cleanInputData($_POST['sgValIsNetIncmAcnt']) === "YES" ? TRUE : FALSE) : FALSE;
                $sgValIsSuspnsAcnt = isset($_POST['sgValIsSuspnsAcnt']) ? (cleanInputData($_POST['sgValIsSuspnsAcnt']) === "YES" ? TRUE : FALSE) : FALSE;
                $sgValHsSubldgrAcnt = isset($_POST['sgValHsSubldgrAcnt']) ? (cleanInputData($_POST['sgValHsSubldgrAcnt']) === "YES" ? TRUE : FALSE) : FALSE;

                $sgValAcntType = isset($_POST['sgValAcntType']) ? cleanInputData($_POST['sgValAcntType']) : '';
                $sgValAcntClsfctn = isset($_POST['sgValAcntClsfctn']) ? cleanInputData($_POST['sgValAcntClsfctn']) : '';

                $sgValCtrlAcntID = isset($_POST['sgValCtrlAcntID']) ? (int) cleanInputData($_POST['sgValCtrlAcntID']) : 1;
                $sgValCtrlAcnt = isset($_POST['sgValCtrlAcnt']) ? cleanInputData($_POST['sgValCtrlAcnt']) : '';
                $sgValMppdAcntID = isset($_POST['sgValMppdAcntID']) ? (int) cleanInputData($_POST['sgValMppdAcntID']) : 0;
                $sgValMppdAcnt = isset($_POST['sgValMppdAcnt']) ? (int) cleanInputData($_POST['sgValMppdAcnt']) : 0;
                $accntCurrIDTextBox = getOrgFuncCurID($orgDetOrgID);

                $exitErrMsg = "";
                if ($segmentValue == "" || $segmentValueDesc == "") {
                    $exitErrMsg .= "Please enter Segment Value and Description!<br/>";
                }
                if ($sbmtdSegmentClsfctn == "NaturalAccount") {
                    if ($sgValAcntType == "") {
                        $exitErrMsg .= "Please select an account Type!<br/>";
                    }
                    if ($sgValIsRetErngsAcnt == true && $sgValIsPrntAcnt == true) {
                        $exitErrMsg .= "A Parent account cannot be used as Retained Earnings Account!<br/>";
                    }
                    if ($sgValIsRetErngsAcnt == true && $sgValIsContraAcnt == true) {
                        $exitErrMsg .= "A contra account cannot be used as Retained Earnings Account!<br/>";
                    }
                    if ($sgValIsRetErngsAcnt == true && $sgValIsEnabled == false) {
                        $exitErrMsg .= "A Retained Earnings Account cannot be disabled!";
                    }

                    if ($sgValIsSuspnsAcnt == true && $sgValAcntType != "A -ASSET") {
                        $exitErrMsg .= "The account type of the Suspense Account must be ASSET";
                    }

                    if ($sgValIsRetErngsAcnt == true && $sgValAcntType != "EQ-EQUITY") {
                        $exitErrMsg .= "The account type of a Retained Earnings Account must be NET WORTH";
                    }
                    if ($sgValIsNetIncmAcnt == true && $sgValIsPrntAcnt == true) {
                        $exitErrMsg .= "A Parent account cannot be used as Net Income Account!";
                    }
                    if ($sgValIsNetIncmAcnt == true && $sgValIsContraAcnt == true) {
                        $exitErrMsg .= "A contra account cannot be used as Net Income Account!";
                    }
                    if ($sgValIsNetIncmAcnt == true && $sgValIsEnabled == false) {
                        $exitErrMsg .= "A Net Income Account cannot be disabled!";
                    }
                    if ($sgValIsNetIncmAcnt == true && $sgValAcntType != "EQ-EQUITY") {
                        $exitErrMsg .= "The account type of a Net Income Account must be NET WORTH";
                    }
                    if ($sgValIsRetErngsAcnt == true && $sgValIsNetIncmAcnt == true) {
                        $exitErrMsg .= "Same Account cannot be Retained Earnings and Net Income at same time!";
                    }
                    if ($sgValIsRetErngsAcnt == true && $sgValHsSubldgrAcnt == true) {
                        $exitErrMsg .= "Retained Earnings account cannot have sub-ledgers!";
                    }
                    if ($sgValIsNetIncmAcnt == true && $sgValHsSubldgrAcnt == true) {
                        $exitErrMsg .= "Net Income account cannot have sub-ledgers!";
                    }
                    if ($sgValIsContraAcnt == true && $sgValHsSubldgrAcnt == true) {
                        $exitErrMsg .= "The system does not support Sub-Ledgers on Contra-Accounts!";
                    }
                    if ($sgValIsPrntAcnt == true && $sgValHsSubldgrAcnt == true) {
                        $exitErrMsg .= "Parent Account cannot have sub-ledgers!";
                    }
                    if ($sgValCtrlAcntID > 0 && $sgValHsSubldgrAcnt == true) {
                        $exitErrMsg .= "The system does not support Control Accounts reporting to other Control Account!";
                    }
                    if ($sgValCtrlAcntID > 0 && $prntSegmentValueID > 0) {
                        $exitErrMsg .= "An Account with a Control Account cannot have a Parent Account as well!";
                    }
                    if ($sgValMppdAcntID > 0) {
                        if (getAccntType($sgValMppdAcntID) != trim(substr($sgValAcntType, 0, 2))) {
                            $exitErrMsg .= "Account Type does not match that of the Mapped Account";
                        }
                    }
                } else if ($sbmtdSegmentClsfctn == "Currency") {
                    /* if (this.accntCurrIDTextBox.Text == "-1" || this.accntCurrIDTextBox.Text == "")
                      {
                      cmnCde.showMsg("System Currency Cannot be Empty!", 0);
                      return;
                      } */
                }
                $oldSgmntValID = getSgmntValID($segmentValue, $sbmtdSegmentID);
                if ($oldSgmntValID > 0 && $oldSgmntValID != $segmentValueID) {
                    $exitErrMsg .= "New Segment Value is already in use in this Organization!";
                }
                $oldSgmntNmID = getSgmntValDescID($segmentValueDesc, $sbmtdSegmentID);
                if ($oldSgmntNmID > 0 && $oldSgmntNmID != $segmentValueID) {
                    $exitErrMsg .= "New Segment Description is already in use in this Segment!";
                }
                $accntType = "";
                if ($sgValAcntType != "") {
                    $accntType = trim(substr($sgValAcntType, 0, 2));
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['segmentValueID'] = $segmentValueID;
                    $arr_content['sbmtdSegmentID'] = $sbmtdSegmentID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($segmentValueID <= 0) {
                    createSgmntVal($orgDetOrgID, $sbmtdSegmentID, $segmentValue, $segmentValueDesc
                            , $sgValAllwdGrpType, $sgValAllwdGrpID, $sgValIsEnabled, $prntSegmentValueID, $sgValIsContraAcnt, $accntType,
                            $sgValIsPrntAcnt, $sgValIsRetErngsAcnt, $sgValIsNetIncmAcnt, getAcctTypID($accntType), 100, $sgValHsSubldgrAcnt,
                            $sgValCtrlAcntID, $accntCurrIDTextBox, $sgValIsSuspnsAcnt, $sgValAcntClsfctn, $sgValMppdAcntID, $sgValCmbntnsAllwd,
                            $dpndntSegmentValueID, $sgValLnkdSiteLocID);
                    $segmentValueID = getSgmntValID($segmentValue, $sbmtdSegmentID);
                } else if ($segmentValueID > 0) {
                    updateSgmntVal($segmentValueID, $segmentValue, $segmentValueDesc
                            , $sgValAllwdGrpType, $sgValAllwdGrpID, $sgValIsEnabled
                            , $prntSegmentValueID, $sgValIsContraAcnt, $accntType, $sgValIsPrntAcnt,
                            $sgValIsRetErngsAcnt
                            , $sgValIsNetIncmAcnt, getAcctTypID($accntType), 100, $sgValHsSubldgrAcnt, $sgValCtrlAcntID, $accntCurrIDTextBox,
                            $sgValIsSuspnsAcnt, $sgValAcntClsfctn, $sgValMppdAcntID, $sbmtdSegmentID, $sgValCmbntnsAllwd, $dpndntSegmentValueID,
                            $sgValLnkdSiteLocID);
                }
                $afftctd = 0;
                $slctdSegmentClsfctns = isset($_POST['slctdSegmentClsfctns']) ? cleanInputData($_POST['slctdSegmentClsfctns']) : '';
                if (trim($slctdSegmentClsfctns, "|~") != "") {
                    //Save Account Segments
                    $variousRows = explode("|", trim($slctdSegmentClsfctns, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 3) {
                            $clsfctnID = (int) (cleanInputData1($crntRow[0]));
                            $majCtgrName = cleanInputData1($crntRow[1]);
                            $minCtgrName = cleanInputData1($crntRow[2]);
                            $oldClsfctnID = get_RptClsfctnID($majCtgrName, $minCtgrName, $segmentValueID);
                            if ($majCtgrName != "" || $minCtgrName != "") {
                                if ($oldClsfctnID <= 0) {
                                    //Insert
                                    $clsfctnID = getNewRptClsfLnID();
                                    $afftctd += createRptClsfctn($clsfctnID, $majCtgrName, $minCtgrName, $segmentValueID);
                                } else if ($oldClsfctnID > 0) {
                                    $afftctd += updateRptClsfctn($oldClsfctnID, $majCtgrName, $minCtgrName, $segmentValueID);
                                }
                            }
                        }
                    }
                }
                $arr_content['percent'] = 100;
                $arr_content['segmentValueID'] = $segmentValueID;
                $arr_content['sbmtdSegmentID'] = $sbmtdSegmentID;
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Segment Value Successfully Saved!<br/>" . $afftctd . " Report Classification(s) Saved Successfully!";
                echo json_encode($arr_content);
                exit();
            }
        } else {
            if ($vwtyp == 0) {
                $pkID = isset($_POST['sbmtdOrgID']) ? $_POST['sbmtdOrgID'] : -1;
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
						<span style=\"text-decoration:none;\">Organization Setup Menu</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Organization Setup</span>
				</li>
                               </ul>
                              </div>";
                $total = get_OrgLstsTblrTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_OrgLstsTblr($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>
                <form id='allOrgStpsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddOrg === true) {
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneOrgStpForm(-1, 2);" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Organization
                                    </button>
                                </div>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-5";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allOrgStpsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncOrgStps(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allOrgStpsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllOrgStps('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllOrgStps('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allOrgStpsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Organization Name", "Parent Organisation Name");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allOrgStpsDsplySze" style="min-width:70px !important;">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 20, 15, 30, 50, 100, 500, 1000);
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
                        <div class="<?php echo $colClassType1; ?>">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllOrgStps('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllOrgStps('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row" style="padding:0px 15px 0px 15px !important">
                        <div  class="col-md-2" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs99">
                                <table class="table table-striped table-bordered table-responsive" id="allOrgStpsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Organization Name</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($pkID <= 0 && $cntr <= 0) {
                                                $pkID = $row[0];
                                            }
                                            $cntr += 1;
                                            ?>
                                            <tr id="allOrgStpsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?><input type="hidden" class="form-control" aria-label="..." id="allOrgStpsRow<?php echo $cntr; ?>_OrgID" value="<?php echo $row[0]; ?>"></td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row[0] . "|org.org_details|org_id"), $smplTokenWord1));
                                                        ?>');" style="padding:2px !important;">
                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                        <div  class="col-md-10" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs99" style="padding-top:7px !important;">
                                <div class="rho-container-fluid" id="orgStpsDetailInfo">
                                    <?php
                                    if ($pkID > 0) {
                                        $result1 = get_OrgStpsDet($pkID);
                                        $sbmtdOrgID = $pkID;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            ?>
                                            <div class="row phone-only-btn" style="margin: 0px 0px 10px 0px !important;">
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgStpsDetailInfo', 'grp=5&typ=1&pg=1&vtyp=1&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Organization</button>
                                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgDivsGrpsPage', 'grp=5&typ=1&pg=1&vtyp=3&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Divisions/Groups</button>
                                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgSitesLocsPage', 'grp=5&typ=1&pg=1&vtyp=4&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Sites/Locations</button>
                                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgJobsPage', 'grp=5&typ=1&pg=1&vtyp=5&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Jobs</button>
                                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgGradesPage', 'grp=5&typ=1&pg=1&vtyp=6&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Grades</button>
                                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgPositionsPage', 'grp=5&typ=1&pg=1&vtyp=7&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Positions</button>
                                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgAcSegmentsPage', 'grp=5&typ=1&pg=1&vtyp=8&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Account Segments</button>
                                                </div>
                                            </div>
                                            <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;">
                                                <li class="active"><a data-toggle="tab" data-rhodata="&pg=1&vtyp=1&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgDetPage" id="orgDetPagetab">Organization</a></li>
                                                <li><a data-toggle="tabajxorg" data-rhodata="&pg=1&vtyp=3&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgDivsGrpsPage" id="orgDivsGrpsPagetab">Divisions/Groups</a></li>
                                                <li><a data-toggle="tabajxorg" data-rhodata="&pg=1&vtyp=4&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgSitesLocsPage" id="orgSitesLocsPagetab">Sites/Locations</a></li>
                                                <li><a data-toggle="tabajxorg" data-rhodata="&pg=1&vtyp=5&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgJobsPage" id="orgJobsPagetab">Jobs</a></li>
                                                <li><a data-toggle="tabajxorg" data-rhodata="&pg=1&vtyp=6&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgGradesPage" id="orgGradesPagetab">Grades</a></li>
                                                <li><a data-toggle="tabajxorg" data-rhodata="&pg=1&vtyp=7&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgPositionsPage" id="orgPositionsPagetab">Positions</a></li>
                                                <li><a data-toggle="tabajxorg" data-rhodata="&pg=1&vtyp=8&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgAcSegmentsPage" id="orgAcSegmentsPagetab">Account Segments</a></li>
                                            </ul>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="custDiv">
                                                        <div class="tab-content">
                                                            <div id="orgDetPage" class="tab-pane fadein active" style="border:none !important;">
                                                                <div class="row">
                                                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                        <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetOrgNm" class="control-label col-lg-4">Organization's Name:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <input type="text" class="form-control" aria-label="..." id="orgDetOrgNm" name="orgDetOrgNm" value="<?php echo $row1[1]; ?>" style="width:100%;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="orgDetOrgID" name="orgDetOrgID" value="<?php echo $row1[0]; ?>">
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[1]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetPrntNm" class="control-label col-lg-4">Parent Organisation:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <div class="input-group">
                                                                                            <input type="text" class="form-control" aria-label="..." id="orgDetPrntNm" name="orgDetPrntNm" value="<?php echo $row1[4]; ?>" readonly="true">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="orgDetPrntOrgID" name="orgDetPrntOrgID" value="<?php echo $row1[3]; ?>">
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Organisations', '', '', '', 'radio', true, '<?php echo $row1[3]; ?>', 'orgDetPrntOrgID', 'orgDetPrntNm', 'clear', 1, '');">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[4]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetResAdrs" class="control-label col-lg-4">Residential Address:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <textarea class="form-control" aria-label="..." id="orgDetResAdrs" name="orgDetResAdrs" style="width:100%;" cols="3" rows="3"><?php echo $row1[5]; ?></textarea>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[5]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetPosAdrs" class="control-label col-lg-4">Postal Address:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <textarea class="form-control" aria-label="..." id="orgDetPosAdrs" name="orgDetPosAdrs" style="width:100%;" cols="3" rows="3"><?php echo $row1[6]; ?></textarea>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[6]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetEmail" class="control-label col-lg-4">Email Addresses:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <input type="text" class="form-control" aria-label="..." id="orgDetEmail" name="orgDetEmail" value="<?php echo $row1[7]; ?>" style="width:100%;">
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[7]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetWebsites" class="control-label col-lg-4">Websites:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <input type="text" class="form-control" aria-label="..." id="orgDetWebsites" name="orgDetWebsites" value="<?php echo $row1[8]; ?>" style="width:100%;">
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[8]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetOrgTyp" class="control-label col-lg-4">Organization Type:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <div class="input-group">
                                                                                            <input type="text" class="form-control" aria-label="..." id="orgDetOrgTyp" name="orgDetOrgTyp" value="<?php echo $row1[11]; ?>" readonly="true">
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Organisation Types', '', '', '', 'radio', true, '<?php echo $row1[11]; ?>', 'orgDetOrgTyp', '', 'clear', 1, '');">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[11]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetFuncCrncy" class="control-label col-lg-4">Functional Currency:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <div class="input-group">
                                                                                            <input type="text" class="form-control" aria-label="..." id="orgDetFuncCrncy" name="orgDetFuncCrncy" value="<?php echo $row1[14]; ?>" readonly="true">
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $row1[14]; ?>', 'orgDetFuncCrncy', '', 'clear', 1, '');">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[14]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                        <fieldset class = "basic_person_fs"><legend class = "basic_person_lg">Organization's Logo</legend>
                                                                            <div style="margin-bottom: 1px;">
                                                                                <?php
                                                                                $nwFileName = "";
                                                                                $orgLogoNm = $row1[2];
                                                                                if (trim($orgLogoNm) === "") {
                                                                                    $orgLogoNm = $row1[0] . ".png";
                                                                                }
                                                                                $temp = explode(".", $orgLogoNm);
                                                                                $extension = end($temp);
                                                                                if (trim($extension) == "") {
                                                                                    $extension = "png";
                                                                                }
                                                                                $nwFileName = encrypt1($orgLogoNm, $smplTokenWord1) . "." . $extension;
                                                                                $ftp_src = $ftp_base_db_fldr . "/Org/" . $orgLogoNm;
                                                                                $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                                                                                if (file_exists($ftp_src)) {
                                                                                    copy("$ftp_src", "$fullPemDest");
                                                                                } else if (!file_exists($fullPemDest)) {
                                                                                    $ftp_src = $fldrPrfx . 'cmn_images/tools_ipwhoislookup.png';
                                                                                    copy("$ftp_src", "$fullPemDest");
                                                                                }
                                                                                $nwFileName = $tmpDest . $nwFileName;
                                                                                ?>
                                                                                <img src="<?php echo $nwFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 184px !important; width: auto !important;">
                                                                            </div>
                                                                            <div class="form-group form-group-sm">
                                                                                <div class="col-md-12">
                                                                                    <div class="input-group">
                                                                                        <label class="btn btn-primary btn-file input-group-addon">
                                                                                            Browse... <input type="file" id="daOrgPicture" name="daOrgPicture" onchange="changeImgSrc(this, '#img1Test', '#img1SrcLoc');" class="btn btn-default"  style="display: none;">
                                                                                        </label>
                                                                                        <input type = "text" class = "form-control" aria-label = "..." id = "img1SrcLoc" value = "">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:3px 3px 0px 3px !important;">
                                                                                <label for="orgDetLogo" class="control-label col-lg-4">Logo Filename:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <input type="text" class="form-control" aria-label="..." id="orgDetLogo" name="orgDetLogo" value="<?php echo $row1[2]; ?>" style="width:100%;" readonly="true">
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[2]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetCntctNums" class="control-label col-lg-4">Contact Numbers:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <input type="text" class="form-control" aria-label="..." id="orgDetCntctNums" name="orgDetCntctNums" value="<?php echo $row1[9]; ?>" style="width:100%;">
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[9]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:11px 3px 0px 3px !important;">
                                                                                <label for="orgDetIsEnabled" class="control-label col-lg-4">Is Enabled?:</label>
                                                                                <div class="col-lg-4">
                                                                                    <?php
                                                                                    $chkdYes = "";
                                                                                    $chkdNo = "checked=\"\"";
                                                                                    if ($row1[12] == "Yes") {
                                                                                        $chkdNo = "";
                                                                                        $chkdYes = "checked=\"\"";
                                                                                    }
                                                                                    ?>
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <label class="radio-inline"><input type="radio" name="orgDetIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                                        <label class="radio-inline"><input type="radio" name="orgDetIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo ($row1[12] == "Yes" ? "YES" : "NO"); ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                                <div class="col-lg-4" style="float:right;">
                                                                                    <button type="button" class="btn btn-default" onclick="saveOrgStpForm();" style="width:100% !important;margin-top:-6px !important;" data-toggle="tooltip" data-placement="bottom" title="Save Organization">
                                                                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        Save Org
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                        <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetNoOfSegmnts" class="control-label col-lg-4">No. of Account Segments:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <input type="number" class="form-control" aria-label="..." id="orgDetNoOfSegmnts" name="orgDetNoOfSegmnts" value="<?php echo $row1[17]; ?>" style="width:100%;">
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[17]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetSegDelimiter" class="control-label col-lg-4">Segment Delimiter:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="orgDetSegDelimiter" style="width:100%;">
                                                                                            <?php
                                                                                            $valslctdArry = array("", "", "", "");
                                                                                            $dsplyArry1 = array("None", "Period (.)", "hiphen(-)", "Space ( )");
                                                                                            for ($y = 0; $y < count($dsplyArry1); $y++) {
                                                                                                if ($row1[18] == $dsplyArry1[$y]) {
                                                                                                    $valslctdArry[$y] = "selected";
                                                                                                } else {
                                                                                                    $valslctdArry[$y] = "";
                                                                                                }
                                                                                                ?>
                                                                                                <option value="<?php echo $dsplyArry1[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplyArry1[$y]; ?></option>
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[18]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetLocSgmtNum" class="control-label col-lg-4">Location Segment No.:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <input type="number" class="form-control" aria-label="..." id="orgDetLocSgmtNum" name="orgDetLocSgmtNum" value="<?php echo $row1[19]; ?>" style="width:100%;">
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[19]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetSublocSgmtNum" class="control-label col-lg-4">Sub-Location Segment No.:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <input type="number" class="form-control" aria-label="..." id="orgDetSublocSgmtNum" name="orgDetSublocSgmtNum" value="<?php echo $row1[20]; ?>" style="width:100%;">
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[20]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetOrgDesc" class="control-label col-lg-2">Organization's Description:</label>
                                                                                <div  class="col-lg-10">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <textarea class="form-control" aria-label="..." id="orgDetOrgDesc" name="orgDetOrgDesc" style="width:100%;" cols="9" rows="4"><?php echo $row1[15]; ?></textarea>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[15]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="orgDetOrgSlogan" class="control-label col-lg-2">Organization's Slogan:</label>
                                                                                <div  class="col-lg-10">
                                                                                    <?php
                                                                                    if ($canEdtOrg === true) {
                                                                                        ?>
                                                                                        <textarea class="form-control" aria-label="..." id="orgDetOrgSlogan" name="orgDetOrgSlogan" style="width:100%;" cols="9" rows="4"><?php echo $row1[16]; ?></textarea>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[16]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="orgDivsGrpsPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                                            <div id="orgSitesLocsPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                                            <div id="orgJobsPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                                            <div id="orgGradesPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                                            <div id="orgPositionsPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                                            <div id="orgAcSegmentsPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <span>No Results Found</span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 1) {
                $curIdx = 0;
                $pkID = isset($_POST['sbmtdOrgID']) ? $_POST['sbmtdOrgID'] : -1;
                if ($pkID > 0) {
                    $result1 = get_OrgStpsDet($pkID);
                    $sbmtdOrgID = $pkID;
                    while ($row1 = loc_db_fetch_array($result1)) {
                        ?>
                        <div class="row phone-only-btn" style="margin: 0px 0px 10px 0px !important;">
                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgStpsDetailInfo', 'grp=5&typ=1&pg=1&vtyp=1&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Organization</button>
                                <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgDivsGrpsPage', 'grp=5&typ=1&pg=1&vtyp=3&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Divisions/Groups</button>
                                <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgSitesLocsPage', 'grp=5&typ=1&pg=1&vtyp=4&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Sites/Locations</button>
                                <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgJobsPage', 'grp=5&typ=1&pg=1&vtyp=5&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Jobs</button>
                                <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgGradesPage', 'grp=5&typ=1&pg=1&vtyp=6&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Grades</button>
                                <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgPositionsPage', 'grp=5&typ=1&pg=1&vtyp=7&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Positions</button>
                                <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#orgAcSegmentsPage', 'grp=5&typ=1&pg=1&vtyp=8&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">Account Segments</button>
                            </div>
                        </div>
                        <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;">
                            <li class="active"><a data-toggle="tab" data-rhodata="&pg=1&vtyp=1&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgDetPage" id="orgDetPagetab">Organization</a></li>
                            <li><a data-toggle="tabajxorg" data-rhodata="&pg=1&vtyp=3&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgDivsGrpsPage" id="orgDivsGrpsPagetab">Divisions/Groups</a></li>
                            <li><a data-toggle="tabajxorg" data-rhodata="&pg=1&vtyp=4&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgSitesLocsPage" id="orgSitesLocsPagetab">Sites/Locations</a></li>
                            <li><a data-toggle="tabajxorg" data-rhodata="&pg=1&vtyp=5&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgJobsPage" id="orgJobsPagetab">Jobs</a></li>
                            <li><a data-toggle="tabajxorg" data-rhodata="&pg=1&vtyp=6&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgGradesPage" id="orgGradesPagetab">Grades</a></li>
                            <li><a data-toggle="tabajxorg" data-rhodata="&pg=1&vtyp=7&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgPositionsPage" id="orgPositionsPagetab">Positions</a></li>
                            <li><a data-toggle="tabajxorg" data-rhodata="&pg=1&vtyp=8&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>" href="#orgAcSegmentsPage" id="orgAcSegmentsPagetab">Account Segments</a></li>
                        </ul>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="custDiv">
                                    <div class="tab-content">
                                        <div id="orgDetPage" class="tab-pane fadein active" style="border:none !important;">
                                            <div class="row">
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetOrgNm" class="control-label col-lg-4">Organization's Name:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="orgDetOrgNm" name="orgDetOrgNm" value="<?php echo $row1[1]; ?>" style="width:100%;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="orgDetOrgID" name="orgDetOrgID" value="<?php echo $row1[0]; ?>">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[1]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetPrntNm" class="control-label col-lg-4">Parent Organisation:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="orgDetPrntNm" name="orgDetPrntNm" value="<?php echo $row1[4]; ?>" readonly="true">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="orgDetPrntOrgID" name="orgDetPrntOrgID" value="<?php echo $row1[3]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Organisations', '', '', '', 'radio', true, '<?php echo $row1[3]; ?>', 'orgDetPrntOrgID', 'orgDetPrntNm', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[4]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetResAdrs" class="control-label col-lg-4">Residential Address:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <textarea class="form-control" aria-label="..." id="orgDetResAdrs" name="orgDetResAdrs" style="width:100%;" cols="3" rows="3"><?php echo $row1[5]; ?></textarea>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[5]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetPosAdrs" class="control-label col-lg-4">Postal Address:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <textarea class="form-control" aria-label="..." id="orgDetPosAdrs" name="orgDetPosAdrs" style="width:100%;" cols="3" rows="3"><?php echo $row1[6]; ?></textarea>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[6]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetEmail" class="control-label col-lg-4">Email Addresses:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="orgDetEmail" name="orgDetEmail" value="<?php echo $row1[7]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[7]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetWebsites" class="control-label col-lg-4">Websites:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="orgDetWebsites" name="orgDetWebsites" value="<?php echo $row1[8]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[8]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetOrgTyp" class="control-label col-lg-4">Organization Type:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="orgDetOrgTyp" name="orgDetOrgTyp" value="<?php echo $row1[11]; ?>" readonly="true">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Organisation Types', '', '', '', 'radio', true, '<?php echo $row1[11]; ?>', 'orgDetOrgTyp', '', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[11]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetFuncCrncy" class="control-label col-lg-4">Functional Currency:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="orgDetFuncCrncy" name="orgDetFuncCrncy" value="<?php echo $row1[14]; ?>" readonly="true">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $row1[14]; ?>', 'orgDetFuncCrncy', '', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[14]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class = "basic_person_fs"><legend class = "basic_person_lg">Organization's Logo</legend>
                                                        <div style="margin-bottom: 1px;">
                                                            <?php
                                                            $nwFileName = "";
                                                            $temp = explode(".", $row1[2]);
                                                            $extension = end($temp);
                                                            if (trim($extension) == "") {
                                                                $extension = "png";
                                                            }
                                                            $nwFileName = encrypt1($row1[2], $smplTokenWord1) . "." . $extension;
                                                            $ftp_src = $ftp_base_db_fldr . "/Org/" . $row1[2];
                                                            $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                                                            if (file_exists($ftp_src)) {
                                                                copy("$ftp_src", "$fullPemDest");
                                                            } else if (!file_exists($fullPemDest)) {
                                                                $ftp_src = $fldrPrfx . 'cmn_images/tools_ipwhoislookup.png';
                                                                copy("$ftp_src", "$fullPemDest");
                                                            }
                                                            $nwFileName = $tmpDest . $nwFileName;
                                                            //$radomNo = rand(1000000, 999999999);
                                                            /* $ftp_src = $ftp_base_db_fldr . "/Org/" . $row1[2];
                                                              $img_src = "dwnlds/pem/" . $row1[2];
                                                              if ($row1[2] != "") {
                                                              if (file_exists($ftp_src) && !file_exists($fldrPrfx . $img_src)) {
                                                              copy("$ftp_src", "$fldrPrfx" . "$img_src");
                                                              }

                                                              if (!file_exists($fldrPrfx . $img_src)) {
                                                              $img_src = "cmn_images/tools_ipwhoislookup.png";
                                                              }
                                                              } else {
                                                              $img_src = "cmn_images/tools_ipwhoislookup.png";
                                                              } */
                                                            ?>
                                                            <img src="<?php echo $nwFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 184px !important; width: auto !important;">
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <div class="col-md-12">
                                                                <div class="input-group">
                                                                    <label class="btn btn-primary btn-file input-group-addon">
                                                                        Browse... <input type="file" id="daOrgPicture" name="daOrgPicture" onchange="changeImgSrc(this, '#img1Test', '#img1SrcLoc');" class="btn btn-default"  style="display: none;">
                                                                    </label>
                                                                    <input type = "text" class = "form-control" aria-label = "..." id = "img1SrcLoc" value = "">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:3px 3px 0px 3px !important;">
                                                            <label for="orgDetLogo" class="control-label col-lg-4">Logo Filename:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="orgDetLogo" name="orgDetLogo" value="<?php echo $row1[2]; ?>" style="width:100%;" readonly="true">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[2]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetCntctNums" class="control-label col-lg-4">Contact Numbers:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="orgDetCntctNums" name="orgDetCntctNums" value="<?php echo $row1[9]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[9]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:11px 3px 0px 3px !important;">
                                                            <label for="orgDetIsEnabled" class="control-label col-lg-4">Is Enabled?:</label>
                                                            <div class="col-lg-4">
                                                                <?php
                                                                $chkdYes = "";
                                                                $chkdNo = "checked=\"\"";
                                                                if ($row1[12] == "Yes") {
                                                                    $chkdNo = "";
                                                                    $chkdYes = "checked=\"\"";
                                                                }
                                                                ?>
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <label class="radio-inline"><input type="radio" name="orgDetIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="orgDetIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo ($row1[12] == "Yes" ? "YES" : "NO"); ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="col-lg-4" style="float:right;">
                                                                <button type="button" class="btn btn-default" onclick="saveOrgStpForm();" style="width:100% !important;margin-top:-6px !important;" data-toggle="tooltip" data-placement="bottom" title="Save Organization">
                                                                    <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Save Org
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                                        <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetNoOfSegmnts" class="control-label col-lg-4">No. of Account Segments:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <input type="number" class="form-control" aria-label="..." id="orgDetNoOfSegmnts" name="orgDetNoOfSegmnts" value="<?php echo $row1[17]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[17]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetSegDelimiter" class="control-label col-lg-4">Segment Delimiter:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="orgDetSegDelimiter" style="width:100%;">
                                                                        <?php
                                                                        $valslctdArry = array("", "", "", "");
                                                                        $dsplyArry1 = array("None", "Period (.)", "hiphen(-)", "Space ( )");
                                                                        for ($y = 0; $y < count($dsplyArry1); $y++) {
                                                                            if ($row1[18] == $dsplyArry1[$y]) {
                                                                                $valslctdArry[$y] = "selected";
                                                                            } else {
                                                                                $valslctdArry[$y] = "";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $dsplyArry1[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplyArry1[$y]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[18]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetLocSgmtNum" class="control-label col-lg-4">Location Segment No.:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <input type="number" class="form-control" aria-label="..." id="orgDetLocSgmtNum" name="orgDetLocSgmtNum" value="<?php echo $row1[19]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[19]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetSublocSgmtNum" class="control-label col-lg-4">Sub-Location Segment No.:</label>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="orgDetSublocSgmtNum" name="orgDetSublocSgmtNum" value="<?php echo $row1[20]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[20]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetOrgDesc" class="control-label col-lg-2">Organization's Description:</label>
                                                            <div  class="col-lg-10">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <textarea class="form-control" aria-label="..." id="orgDetOrgDesc" name="orgDetOrgDesc" style="width:100%;" cols="9" rows="4"><?php echo $row1[15]; ?></textarea>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[15]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="orgDetOrgSlogan" class="control-label col-lg-2">Organization's Slogan:</label>
                                                            <div  class="col-lg-10">
                                                                <?php
                                                                if ($canEdtOrg === true) {
                                                                    ?>
                                                                    <textarea class="form-control" aria-label="..." id="orgDetOrgSlogan" name="orgDetOrgSlogan" style="width:100%;" cols="9" rows="4"><?php echo $row1[16]; ?></textarea>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[16]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="orgDivsGrpsPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                        <div id="orgSitesLocsPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                        <div id="orgJobsPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                        <div id="orgGradesPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                        <div id="orgPositionsPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                        <div id="orgAcSegmentsPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <span>No Results Found</span>
                    <?php
                }
            } else if ($vwtyp == 2) {
                //New Org Form
                $curIdx = 0;
                $pkID = -1;
                if ($canAddOrg === true) {
                    
                } else {
                    exit();
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="custDiv" style="border:none !important;">
                            <div class="tab-content">
                                <div id="orgDetPage" class="tab-pane fadein active" style="border:none !important;">
                                    <div class="row">
                                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetOrgNm" class="control-label col-lg-4">Organization's Name:</label>
                                                    <div  class="col-lg-8">
                                                        <input type="text" class="form-control" aria-label="..." id="orgDetOrgNm" name="orgDetOrgNm" value="" style="width:100%;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="orgDetOrgID" name="orgDetOrgID" value="-1">
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetPrntNm" class="control-label col-lg-4">Parent Organization:</label>
                                                    <div  class="col-lg-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="orgDetPrntNm" name="orgDetPrntNm" value="" readonly="true">
                                                            <input type="hidden" class="form-control" aria-label="..." id="orgDetPrntOrgID" name="orgDetPrntOrgID" value="-1">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Organisations', '', '', '', 'radio', true, '', 'orgDetPrntOrgID', 'orgDetPrntNm', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetResAdrs" class="control-label col-lg-4">Residential Address:</label>
                                                    <div  class="col-lg-8">
                                                        <textarea class="form-control" aria-label="..." id="orgDetResAdrs" name="orgDetResAdrs" style="width:100%;" cols="3" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetPosAdrs" class="control-label col-lg-4">Postal Address:</label>
                                                    <div  class="col-lg-8">
                                                        <textarea class="form-control" aria-label="..." id="orgDetPosAdrs" name="orgDetPosAdrs" style="width:100%;" cols="3" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetEmail" class="control-label col-lg-4">Email Addresses:</label>
                                                    <div  class="col-lg-8">
                                                        <input type="text" class="form-control" aria-label="..." id="orgDetEmail" name="orgDetEmail" value="" style="width:100%;">
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetWebsites" class="control-label col-lg-4">Websites:</label>
                                                    <div  class="col-lg-8">
                                                        <input type="text" class="form-control" aria-label="..." id="orgDetWebsites" name="orgDetWebsites" value="" style="width:100%;">
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetOrgTyp" class="control-label col-lg-4">Organization Type:</label>
                                                    <div  class="col-lg-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="orgDetOrgTyp" name="orgDetOrgTyp" value="" readonly="true">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Organisation Types', '', '', '', 'radio', true, '', 'orgDetOrgTyp', '', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetFuncCrncy" class="control-label col-lg-4">Functional Currency:</label>
                                                    <div  class="col-lg-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="orgDetFuncCrncy" name="orgDetFuncCrncy" value="" readonly="true">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'orgDetFuncCrncy', '', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                            <fieldset class = "basic_person_fs"><legend class = "basic_person_lg">Organization's Logo</legend>
                                                <div style="margin-bottom: 1px;">
                                                    <?php
                                                    $img_src = "cmn_images/tools_ipwhoislookup.png";
                                                    ?>
                                                    <img src="<?php echo $img_src; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 184px !important; width: auto !important;">
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="btn btn-primary btn-file input-group-addon">
                                                                Browse... <input type="file" id="daOrgPicture" name="daOrgPicture" onchange="changeImgSrc(this, '#img1Test', '#img1SrcLoc');" class="btn btn-default"  style="display: none;">
                                                            </label>
                                                            <input type = "text" class = "form-control" aria-label = "..." id = "img1SrcLoc" value = "">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:3px 3px 0px 3px !important;">
                                                    <label for="orgDetLogo" class="control-label col-lg-4">Logo Filename:</label>
                                                    <div  class="col-lg-8">
                                                        <input type="text" class="form-control" aria-label="..." id="orgDetLogo" name="orgDetLogo" value="" style="width:100%;" readonly="true">
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetCntctNums" class="control-label col-lg-4">Contact Numbers:</label>
                                                    <div  class="col-lg-8">
                                                        <input type="text" class="form-control" aria-label="..." id="orgDetCntctNums" name="orgDetCntctNums" value="" style="width:100%;">
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:11px 3px 0px 3px !important;">
                                                    <label for="orgDetIsEnabled" class="control-label col-lg-4">Is Enabled?:</label>
                                                    <div class="col-lg-4">
                                                        <?php
                                                        $chkdYes = "";
                                                        $chkdNo = "checked=\"\"";
                                                        ?>
                                                        <?php if ($canEdtOrg === true) {
                                                            ?>
                                                            <label class="radio-inline"><input type="radio" name="orgDetIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                            <label class="radio-inline"><input type="radio" name="orgDetIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                        <?php } else {
                                                            ?>
                                                            <span><?php echo ($row1[12] == "Yes" ? "YES" : "NO"); ?></span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-lg-4" style="float:right;">
                                                        <button type="button" class="btn btn-default" onclick="saveOrgStpForm();" style="width:100% !important;margin-top:-6px !important;" data-toggle="tooltip" data-placement="bottom" title="Save Organization">
                                                            <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Save Org
                                                        </button>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetNoOfSegmnts" class="control-label col-lg-4">No. of Account Segments:</label>
                                                    <div  class="col-lg-8">
                                                        <input type="number" class="form-control" aria-label="..." id="orgDetNoOfSegmnts" name="orgDetNoOfSegmnts" value="1" style="width:100%;">
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetSegDelimiter" class="control-label col-lg-4">Segment Delimiter:</label>
                                                    <div  class="col-lg-8">
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="orgDetSegDelimiter" style="width:100%;">
                                                            <?php
                                                            $valslctdArry = array("", "", "", "");
                                                            $dsplyArry1 = array("None", "Period (.)", "hiphen(-)", "Space ( )");
                                                            for ($y = 0; $y < count($dsplyArry1); $y++) {
                                                                ?>
                                                                <option value="<?php echo $dsplyArry1[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplyArry1[$y]; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetLocSgmtNum" class="control-label col-lg-4">Location Segment No.:</label>
                                                    <div  class="col-lg-8">
                                                        <input type="number" class="form-control" aria-label="..." id="orgDetLocSgmtNum" name="orgDetLocSgmtNum" value="0" style="width:100%;">
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetSublocSgmtNum" class="control-label col-lg-4">Sub-Location Segment No.:</label>
                                                    <div  class="col-lg-8">
                                                        <input type="number" class="form-control" aria-label="..." id="orgDetSublocSgmtNum" name="orgDetSublocSgmtNum" value="0" style="width:100%;">
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetOrgDesc" class="control-label col-lg-2">Organization's Description:</label>
                                                    <div  class="col-lg-10">
                                                        <textarea class="form-control" aria-label="..." id="orgDetOrgDesc" name="orgDetOrgDesc" style="width:100%;" cols="9" rows="4"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="orgDetOrgSlogan" class="control-label col-lg-2">Organization's Slogan:</label>
                                                    <div  class="col-lg-10">
                                                        <textarea class="form-control" aria-label="..." id="orgDetOrgSlogan" name="orgDetOrgSlogan" style="width:100%;" cols="9" rows="4"></textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 3) {
                //echo "Divs/Grps";
                //$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 5;
                $curIdx = 0;
                $canDelRec = test_prmssns($dfltPrvldgs[18], $mdlNm);
                $pkID = isset($_POST['sbmtdOrgID']) ? $_POST['sbmtdOrgID'] : -1;
                if ($pkID > 0) {
                    $sbmtdOrgID = $pkID;
                    $total = get_DivsGrpsTtl($pkID, $srchFor, $srchIn);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result1 = get_DivsGrps($pkID, $srchFor, $srchIn, $curIdx, $lmtSze);
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='divsGrpsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row">
                            <?php
                            if ($canEdtOrg === true) {
                                $nwRowHtml = urlencode("<tr id=\"divsGrpsRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"divsGrpsRow_WWW123WWW_GroupNm\" value=\"\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"divsGrpsRow_WWW123WWW_GroupID\" value=\"\">
                                              </div>
                                          </td>"
                                        . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"divsGrpsRow_WWW123WWW_PrntNm\" value=\"\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"divsGrpsRow_WWW123WWW_PrntID\" value=\"\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Divisions/Groups', '', '', '', 'radio', true, '', 'divsGrpsRow_WWW123WWW_PrntID', 'divsGrpsRow_WWW123WWW_PrntNm', 'clear', 0, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                              </div>
                                          </td>"
                                        . "<td class=\"lovtd\">
                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"divsGrpsRow_WWW123WWW_DivTypNm\" value=\"\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Divisions or Group Types', '', '', '', 'radio', true, '', 'divsGrpsRow_WWW123WWW_DivTypNm', '', 'clear', 0, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                            </div>
                                          </td>
                                          <td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                 <div class=\"input-group\"  style=\"width:100%;\">
                                                   <textarea class=\"form-control\" aria-label=\"...\" id=\"divsGrpsRow_WWW123WWW_GroupDesc\" name=\"divsGrpsRow_WWW123WWW_GroupDesc\" style=\"width:100%;resize:vertical;\" cols=\"7\" rows=\"1\"></textarea>
                                                   <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"popUpDisplay('divsGrpsRow_WWW123WWW_GroupDesc');\" style=\"max-width:30px;width:30px;\">
                                                      <span class=\"glyphicon glyphicon-th-list\"></span>
                                                   </label>
                                                 </div>
                                              </div>
                                          </td>
                                          <td class=\"lovtd\" style=\"text-align: center;\">
                                          <div class=\"form-group form-group-sm \">
                                                            <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                <label class=\"form-check-label\">
                                                                    <input type=\"checkbox\" class=\"form-check-input\" id=\"divsGrpsRow_WWW123WWW_IsEnabled\" name=\"divsGrpsRow_WWW123WWW_IsEnabled\">
                                                                </label>
                                                            </div>
                                                        </div>
                                           </td>
                                            <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delDivsGrps('divsGrpsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Division/Group\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                            </td>
                                            <td class=\"lovtd\">&nbsp;</td>
                                        </tr>");
                                ?>
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('divsGrpsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Division/Group">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveDivsGrpsForm();" data-toggle="tooltip" data-placement="bottom" title="Save Division/Group">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-4";
                                $colClassType2 = "col-lg-4";
                                $colClassType3 = "col-lg-4";
                            }
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="divsGrpsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncDivsGrps(event, '', '#orgDivsGrpsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                    <input id="divsGrpsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllDivsGrps('clear', '#orgDivsGrpsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllDivsGrps('', '#orgDivsGrpsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="divsGrpsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Division Name", "Parent Division Name");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="divsGrpsDsplySze" style="min-width:70px !important;">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 20, 30, 50, 100, 500, 1000);
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
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllDivsGrps('previous', '#orgDivsGrpsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllDivsGrps('next', '#orgDivsGrpsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="divsGrpsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Group Code/Name</th>
                                            <th>Parent Group</th>
                                            <th>Group Type</th>
                                            <th>Group Description</th>
                                            <th style="text-align: center;">Enabled?</th>
                                            <th>...</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="divsGrpsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="divsGrpsRow<?php echo $cntr; ?>_GroupNm" value="<?php echo $row1[1]; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="divsGrpsRow<?php echo $cntr; ?>_GroupID" value="<?php echo $row1[0]; ?>">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[1]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control" aria-label="..." id="divsGrpsRow<?php echo $cntr; ?>_PrntNm" value="<?php echo $row1[3]; ?>">
                                                                <input type="hidden" class="form-control" aria-label="..." id="divsGrpsRow<?php echo $cntr; ?>_PrntID" value="<?php echo $row1[2]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Divisions/Groups', '', '', '', 'radio', true, '<?php echo $row1[2]; ?>', 'divsGrpsRow<?php echo $cntr; ?>_PrntID', 'divsGrpsRow<?php echo $cntr; ?>_PrntNm', 'clear', 0, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[3]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control" aria-label="..." id="divsGrpsRow<?php echo $cntr; ?>_DivTypNm" value="<?php echo $row1[5]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Divisions or Group Types', '', '', '', 'radio', true, '<?php echo $row1[5]; ?>', 'divsGrpsRow<?php echo $cntr; ?>_DivTypNm', '', 'clear', 0, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[5]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <textarea class="form-control" aria-label="..." id="divsGrpsRow<?php echo $cntr; ?>_GroupDesc" name="divsGrpsRow<?php echo $cntr; ?>_GroupDesc" style="width:100%;resize:vertical;" cols="7" rows="1"><?php echo $row1[7]; ?></textarea>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('divsGrpsRow<?php echo $cntr; ?>_GroupDesc');" style="max-width:30px;width:30px;">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[7]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd" style="text-align: center;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($row1[8] == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    if ($canEdtOrg === true) {
                                                        ?>
                                                        <div class="form-group form-group-sm ">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="divsGrpsRow<?php echo $cntr; ?>_IsEnabled" name="divsGrpsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[8]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canDelRec === true) { ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delDivsGrps('divsGrpsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Division/Group">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row1[0] . "|org.org_divs_groups|div_id"), $smplTokenWord1));
                                                        ?>');" style="padding:2px !important;">
                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 4) {
                //echo "Sites/Locs";
                //$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 5;
                $canDelRec = test_prmssns($dfltPrvldgs[21], $mdlNm);
                $curIdx = 0;
                $pkID = isset($_POST['sbmtdOrgID']) ? $_POST['sbmtdOrgID'] : -1;
                if ($pkID > 0) {
                    $sbmtdOrgID = $pkID;
                    $total = get_SitesLocsTtl($pkID, $srchFor, $srchIn);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result1 = get_SitesLocs($pkID, $srchFor, $srchIn, $curIdx, $lmtSze);
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='sitesLocsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row">
                            <?php
                            if ($canEdtOrg === true) {
                                $nwRowHtml = "<tr id=\"sitesLocsRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"sitesLocsRow_WWW123WWW_SiteNm\" value=\"\" style=\"width:100% !important;\">
                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"sitesLocsRow_WWW123WWW_SiteID\" value=\"\">
                                              </div>
                                          </td>
                                          <td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                 <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"sitesLocsRow_WWW123WWW_SiteDesc\" value=\"\" style=\"width:100% !important;\">
                                              </div>
                                          </td>
                                          <td class=\"lovtd\">
                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                    <div class=\"input-group\"  style=\"width:100%;\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"sitesLocsRow_WWW123WWW_SiteType\" value=\"\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Site Types', '', '', '', 'radio', true, '', 'sitesLocsRow_WWW123WWW_SiteType', '', 'clear', 0, '');\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                          </td>
                                          <td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                    <div class=\"input-group\"  style=\"width:100%;\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"sitesLocsRow_WWW123WWW_PrntNm\" value=\"\">
                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"sitesLocsRow_WWW123WWW_PrntID\" value=\"\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', '', '', '', 'radio', true, '', 'sitesLocsRow_WWW123WWW_PrntID', 'sitesLocsRow_WWW123WWW_PrntNm', 'clear', 0, '');\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>
                                              </div>
                                          </td>
                                          <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <select class=\"form-control\" id=\"sitesLocsRow_WWW123WWW_GrpType\" onchange=\"grpTypOrgChange('sitesLocsRow_WWW123WWW_GrpType', 'sitesLocsRow_WWW123WWW_GrpName', 'sitesLocsRow_WWW123WWW_GrpID', 'sitesLocsRow_WWW123WWW_GrpNmLbl');\">";
                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                $valuesArrys = array("Everyone", "Divisions/Groups",
                                    "Grade", "Job", "Position", "Site/Location", "Person Type", "Single Person");
                                for ($z = 0; $z < count($valuesArrys); $z++) {
                                    $nwRowHtml .= "<option value=\"" . $valuesArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $valuesArrys[$z] . "</option>";
                                }
                                $nwRowHtml .= "</select>
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <div class=\"input-group\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"sitesLocsRow_WWW123WWW_GrpName\" value=\"\" readonly=\"true\">
                                                                <input type=\"hidden\" id=\"sitesLocsRow_WWW123WWW_GrpID\" value=\"-1\">
                                                                <label disabled=\"true\" id=\"sitesLocsRow_WWW123WWW_GrpNmLbl\" class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getNoticeLovsTblr('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'orgDetOrgID', '', '', 'radio', true, '', 'sitesLocsRow_WWW123WWW_GrpID', 'sitesLocsRow_WWW123WWW_GrpName', 'clear', 1, '', 'sitesLocsRow_WWW123WWW_GrpType');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\" style=\"display: none;\">
                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"sitesLocsRow_WWW123WWW_LnkdDivNm\" value=\"\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"sitesLocsRow_WWW123WWW_LnkdDivID\" value=\"\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Divisions/Groups', 'orgDetOrgID', '', '', 'radio', true, '', 'sitesLocsRow_WWW123WWW_LnkdDivID', 'sitesLocsRow_WWW123WWW_LnkdDivNm', 'clear', 0, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                </td>
                                          <td class=\"lovtd\" style=\"text-align: center;\">
                                          <div class=\"form-group form-group-sm \">
                                                            <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                <label class=\"form-check-label\">
                                                                    <input type=\"checkbox\" class=\"form-check-input\" id=\"sitesLocsRow_WWW123WWW_IsEnabled\" name=\"sitesLocsRow_WWW123WWW_IsEnabled\">
                                                                </label>
                                                            </div>
                                                        </div>
                                           </td>
                                           <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delSitesLocs('sitesLocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Site/Location\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                            </td>
                                            <td class=\"lovtd\">&nbsp;</td>
                                        </tr>";
                                $nwRowHtml = urlencode($nwRowHtml);
                                ?>
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('sitesLocsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Site/Location">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveSitesLocsForm();" data-toggle="tooltip" data-placement="bottom" title="Save Sites/Locations">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-4";
                                $colClassType2 = "col-lg-4";
                                $colClassType3 = "col-lg-4";
                            }
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="sitesLocsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncSitesLocs(event, '', '#orgSitesLocsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                    <input id="sitesLocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSitesLocs('clear', '#orgSitesLocsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSitesLocs('', '#orgSitesLocsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="sitesLocsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Site Name", "Site Description");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="sitesLocsDsplySze" style="min-width:70px !important;">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 20, 30, 50, 100, 500, 1000);
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
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllSitesLocs('previous', '#orgSitesLocsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllSitesLocs('next', '#orgSitesLocsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="sitesLocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 600px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Location Code/Name</th>
                                            <th>Site Description</th>
                                            <th>Site Type</th>
                                            <th>Parent Site</th>
                                            <th>Allowed Group Type</th>
                                            <th>Allowed Group Name</th>
                                            <th style="display: none;">Account Access Group</th>
                                            <th style="text-align: center;">Enabled?</th>
                                            <th>...</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="sitesLocsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="sitesLocsRow<?php echo $cntr; ?>_SiteNm" value="<?php echo $row1[1]; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="sitesLocsRow<?php echo $cntr; ?>_SiteID" value="<?php echo $row1[0]; ?>">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[1]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="sitesLocsRow<?php echo $cntr; ?>_SiteDesc" value="<?php echo $row1[2]; ?>" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[2]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control" aria-label="..." id="sitesLocsRow<?php echo $cntr; ?>_SiteType" value="<?php echo $row1[7]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Site Types', '', '', '', 'radio', true, '<?php echo $row1[7]; ?>', 'sitesLocsRow<?php echo $cntr; ?>_SiteType', '', 'clear', 0, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[7]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control" aria-label="..." id="sitesLocsRow<?php echo $cntr; ?>_PrntNm" value="<?php echo $row1[12]; ?>">
                                                                <input type="hidden" class="form-control" aria-label="..." id="sitesLocsRow<?php echo $cntr; ?>_PrntID" value="<?php echo $row1[11]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', '', '', '', 'radio', true, '<?php echo $row1[11]; ?>', 'sitesLocsRow<?php echo $cntr; ?>_PrntID', 'sitesLocsRow<?php echo $cntr; ?>_PrntNm', 'clear', 0, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[12]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <?php if ($canEdtOrg === true) { ?>
                                                            <select class="form-control" id="sitesLocsRow<?php echo $cntr; ?>_GrpType" onchange="grpTypOrgChange('sitesLocsRow<?php echo $cntr; ?>_GrpType', 'sitesLocsRow<?php echo $cntr; ?>_GrpName', 'sitesLocsRow<?php echo $cntr; ?>_GrpID', 'sitesLocsRow<?php echo $cntr; ?>_GrpNmLbl');">
                                                                <?php
                                                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                                                $valuesArrys = array("Everyone", "Divisions/Groups",
                                                                    "Grade", "Job", "Position", "Site/Location", "Person Type", "Single Person");

                                                                for ($z = 0; $z < count($valuesArrys); $z++) {
                                                                    if ($row1[4] == $valuesArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <span><?php echo $row1[4]; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                                <td class="lovtd">
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <?php if ($canEdtOrg === true) { ?>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" aria-label="..." id="sitesLocsRow<?php echo $cntr; ?>_GrpName" value="<?php echo $row1[10]; ?>" readonly="true">
                                                                <input type="hidden" id="sitesLocsRow<?php echo $cntr; ?>_GrpID" value="<?php echo $row1[5]; ?>">
                                                                <label disabled="true" id="sitesLocsRow<?php echo $cntr; ?>_GrpNmLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovsTblr('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'orgDetOrgID', '', '', 'radio', true, '', 'sitesLocsRow<?php echo $cntr; ?>_GrpID', 'sitesLocsRow<?php echo $cntr; ?>_GrpName', 'clear', 1, '', 'sitesLocsRow<?php echo $cntr; ?>_GrpType');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $row1[10]; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                                <td class="lovtd" style="display: none;">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control" aria-label="..." id="sitesLocsRow<?php echo $cntr; ?>_LnkdDivNm" value="<?php echo $row1[9]; ?>">
                                                                <input type="hidden" class="form-control" aria-label="..." id="sitesLocsRow<?php echo $cntr; ?>_LnkdDivID" value="<?php echo $row1[8]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Divisions/Groups', 'orgDetOrgID', '', '', 'radio', true, '<?php echo $row1[8]; ?>', 'sitesLocsRow<?php echo $cntr; ?>_LnkdDivID', 'sitesLocsRow<?php echo $cntr; ?>_LnkdDivNm', 'clear', 0, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[9]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd" style="text-align: center;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($row1[3] == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    if ($canEdtOrg === true) {
                                                        ?>
                                                        <div class="form-group form-group-sm ">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="sitesLocsRow<?php echo $cntr; ?>_IsEnabled" name="sitesLocsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[3]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canDelRec === true) { ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSitesLocs('sitesLocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Site/Location">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row1[0] . "|org.org_sites_locations|location_id"), $smplTokenWord1));
                                                        ?>');" style="padding:2px !important;">
                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 5) {
                //echo "Jobs";
                $canDelRec = test_prmssns($dfltPrvldgs[24], $mdlNm);
                $curIdx = 0;
                $pkID = isset($_POST['sbmtdOrgID']) ? $_POST['sbmtdOrgID'] : -1;
                if ($pkID > 0) {
                    $sbmtdOrgID = $pkID;
                    $total = get_JobsTtl($pkID, $srchFor, $srchIn);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result1 = get_Jobs($pkID, $srchFor, $srchIn, $curIdx, $lmtSze);
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='orgJobsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row">
                            <?php
                            if ($canEdtOrg === true) {
                                $nwRowHtml = urlencode("<tr id=\"orgJobsRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"orgJobsRow_WWW123WWW_JobNm\" value=\"\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"orgJobsRow_WWW123WWW_JobID\" value=\"\">
                                              </div>
                                          </td>"
                                        . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"orgJobsRow_WWW123WWW_PrntNm\" value=\"\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"orgJobsRow_WWW123WWW_PrntID\" value=\"\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Jobs', '', '', '', 'radio', true, '', 'orgJobsRow_WWW123WWW_PrntID', 'orgJobsRow_WWW123WWW_PrntNm', 'clear', 0, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                              </div>
                                          </td>
                                          <td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                 <div class=\"input-group\"  style=\"width:100%;\">
                                                   <textarea class=\"form-control\" aria-label=\"...\" id=\"orgJobsRow_WWW123WWW_JobDesc\" name=\"orgJobsRow_WWW123WWW_JobDesc\" style=\"width:100%;resize:vertical;\" cols=\"7\" rows=\"1\"></textarea>
                                                   <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"popUpDisplay('orgJobsRow_WWW123WWW_JobDesc');\" style=\"max-width:30px;width:30px;\">
                                                      <span class=\"glyphicon glyphicon-th-list\"></span>
                                                   </label>
                                                 </div>
                                              </div>
                                          </td>
                                          <td class=\"lovtd\" style=\"text-align: center;\">
                                          <div class=\"form-group form-group-sm \">
                                                        <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                <label class=\"form-check-label\">
                                                                    <input type=\"checkbox\" class=\"form-check-input\" id=\"orgJobsRow_WWW123WWW_IsEnabled\" name=\"orgJobsRow_WWW123WWW_IsEnabled\">
                                                                </label>
                                                            </div>
                                                        </div>
                                           </td>
                                           <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delOrgJobs('orgJobsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Job\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                            </td>
                                            <td class=\"lovtd\">&nbsp;</td>
                                        </tr>");
                                ?>
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('orgJobsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Job">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveOrgJobsForm();" data-toggle="tooltip" data-placement="bottom" title="Save Jobs">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-4";
                                $colClassType2 = "col-lg-4";
                                $colClassType3 = "col-lg-4";
                            }
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="orgJobsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncOrgJobs(event, '', '#orgJobsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                    <input id="orgJobsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllOrgJobs('clear', '#orgJobsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllOrgJobs('', '#orgJobsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="orgJobsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Job Name", "Parent Job Name");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="orgJobsDsplySze" style="min-width:70px !important;">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 20, 30, 50, 100, 500, 1000);
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
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllOrgJobs('previous', '#orgJobsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllOrgJobs('next', '#orgJobsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="orgJobsTable" cellspacing="0" width="100%" style="width:100%;min-width: 600px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Job Code/Name</th>
                                            <th>Parent Job</th>
                                            <th>Job Description</th>
                                            <th style="text-align: center;">Enabled?</th>
                                            <th>...</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="orgJobsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="orgJobsRow<?php echo $cntr; ?>_JobNm" value="<?php echo $row1[1]; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="orgJobsRow<?php echo $cntr; ?>_JobID" value="<?php echo $row1[0]; ?>">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[1]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control" aria-label="..." id="orgJobsRow<?php echo $cntr; ?>_PrntNm" value="<?php echo $row1[3]; ?>">
                                                                <input type="hidden" class="form-control" aria-label="..." id="orgJobsRow<?php echo $cntr; ?>_PrntID" value="<?php echo $row1[2]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Jobs', '', '', '', 'radio', true, '<?php echo $row1[2]; ?>', 'orgJobsRow<?php echo $cntr; ?>_PrntID', 'orgJobsRow<?php echo $cntr; ?>_PrntNm', 'clear', 0, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[3]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <textarea class="form-control" aria-label="..." id="orgJobsRow<?php echo $cntr; ?>_JobDesc" name="orgJobsRow<?php echo $cntr; ?>_JobDesc" style="width:100%;resize:vertical;" cols="7" rows="1"><?php echo $row1[4]; ?></textarea>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('orgJobsRow<?php echo $cntr; ?>_JobDesc');" style="max-width:30px;width:30px;">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[4]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd" style="text-align: center;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($row1[5] == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    if ($canEdtOrg === true) {
                                                        ?>
                                                        <div class="form-group form-group-sm ">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="orgJobsRow<?php echo $cntr; ?>_IsEnabled" name="orgJobsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[5]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canDelRec === true) { ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delOrgJobs('orgJobsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Job">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row1[0] . "|org.org_jobs|job_id"), $smplTokenWord1));
                                                        ?>');" style="padding:2px !important;">
                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 6) {
                //echo "Grades";
                $canDelRec = test_prmssns($dfltPrvldgs[27], $mdlNm);
                $curIdx = 0;
                $pkID = isset($_POST['sbmtdOrgID']) ? $_POST['sbmtdOrgID'] : -1;
                if ($pkID > 0) {
                    $sbmtdOrgID = $pkID;
                    $total = get_GradesTtl($pkID, $srchFor, $srchIn);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result1 = get_Grades($pkID, $srchFor, $srchIn, $curIdx, $lmtSze);
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='orgGradesForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row">
                            <?php
                            if ($canEdtOrg === true) {
                                $nwRowHtml = urlencode("<tr id=\"orgGradesRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"orgGradesRow_WWW123WWW_GradeNm\" value=\"\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"orgGradesRow_WWW123WWW_GradeID\" value=\"\">
                                              </div>
                                          </td>"
                                        . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"orgGradesRow_WWW123WWW_PrntNm\" value=\"\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"orgGradesRow_WWW123WWW_PrntID\" value=\"\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Grades', '', '', '', 'radio', true, '', 'orgGradesRow_WWW123WWW_PrntID', 'orgGradesRow_WWW123WWW_PrntNm', 'clear', 0, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                              </div>
                                          </td>
                                          <td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                 <div class=\"input-group\"  style=\"width:100%;\">
                                                   <textarea class=\"form-control\" aria-label=\"...\" id=\"orgGradesRow_WWW123WWW_GradeDesc\" name=\"orgGradesRow_WWW123WWW_GradeDesc\" style=\"width:100%;resize:vertical;\" cols=\"7\" rows=\"1\"></textarea>
                                                   <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"popUpDisplay('orgGradesRow_WWW123WWW_GradeDesc');\" style=\"max-width:30px;width:30px;\">
                                                      <span class=\"glyphicon glyphicon-th-list\"></span>
                                                   </label>
                                                 </div>
                                              </div>
                                          </td>
                                          <td class=\"lovtd\" style=\"text-align: center;\">
                                          <div class=\"form-group form-group-sm \">
                                                            <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                <label class=\"form-check-label\">
                                                                    <input type=\"checkbox\" class=\"form-check-input\" id=\"orgGradesRow_WWW123WWW_IsEnabled\" name=\"orgGradesRow_WWW123WWW_IsEnabled\">
                                                                </label>
                                                            </div>
                                                        </div>
                                           </td>
                                           <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delOrgGrades('orgGradesRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Grade\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                            </td>
                                            <td class=\"lovtd\">&nbsp;</td>
                                        </tr>");
                                ?>
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('orgGradesTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Grade">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveOrgGradesForm();" data-toggle="tooltip" data-placement="bottom" title="Save Grade">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-4";
                                $colClassType2 = "col-lg-4";
                                $colClassType3 = "col-lg-4";
                            }
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="orgGradesSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncOrgGrades(event, '', '#orgGradesPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                    <input id="orgGradesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllOrgGrades('clear', '#orgGradesPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllOrgGrades('', '#orgGradesPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="orgGradesSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Grade Name", "Grade Description");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="orgGradesDsplySze" style="min-width:70px !important;">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 20, 30, 50, 100, 500, 1000);
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
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllOrgGrades('previous', '#orgGradesPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllOrgGrades('next', '#orgGradesPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="orgGradesTable" cellspacing="0" width="100%" style="width:100%;min-width: 600px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Grade Code/Name</th>
                                            <th>Parent Grade</th>
                                            <th>Grade Description</th>
                                            <th style="text-align: center;">Enabled?</th>
                                            <th>...</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="orgGradesRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="orgGradesRow<?php echo $cntr; ?>_GradeNm" value="<?php echo $row1[1]; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="orgGradesRow<?php echo $cntr; ?>_GradeID" value="<?php echo $row1[0]; ?>">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[1]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control" aria-label="..." id="orgGradesRow<?php echo $cntr; ?>_PrntNm" value="<?php echo $row1[3]; ?>">
                                                                <input type="hidden" class="form-control" aria-label="..." id="orgGradesRow<?php echo $cntr; ?>_PrntID" value="<?php echo $row1[2]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Grades', '', '', '', 'radio', true, '<?php echo $row1[2]; ?>', 'orgGradesRow<?php echo $cntr; ?>_PrntID', 'orgGradesRow<?php echo $cntr; ?>_PrntNm', 'clear', 0, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[3]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <textarea class="form-control" aria-label="..." id="orgGradesRow<?php echo $cntr; ?>_GradeDesc" name="orgGradesRow<?php echo $cntr; ?>_GradeDesc" style="width:100%;resize:vertical;" cols="7" rows="1"><?php echo $row1[4]; ?></textarea>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('orgGradesRow<?php echo $cntr; ?>_GradeDesc');" style="max-width:30px;width:30px;">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[4]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd" style="text-align: center;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($row1[5] == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    if ($canEdtOrg === true) {
                                                        ?>
                                                        <div class="form-group form-group-sm ">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="orgGradesRow<?php echo $cntr; ?>_IsEnabled" name="orgGradesRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[5]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canDelRec === true) { ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delOrgGrades('orgGradesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Grade">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row1[0] . "|org.org_grades|grade_id"), $smplTokenWord1));
                                                        ?>');" style="padding:2px !important;">
                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 7) {
                //echo "Positions";
                $canDelRec = test_prmssns($dfltPrvldgs[30], $mdlNm);
                $curIdx = 0;
                $pkID = isset($_POST['sbmtdOrgID']) ? $_POST['sbmtdOrgID'] : -1;
                if ($pkID > 0) {
                    $sbmtdOrgID = $pkID;
                    $total = get_PosTtl($pkID, $srchFor, $srchIn);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result1 = get_Pos($pkID, $srchFor, $srchIn, $curIdx, $lmtSze);
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='orgPositionsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row">
                            <?php
                            if ($canEdtOrg === true) {
                                $nwRowHtml = urlencode("<tr id=\"orgPositionsRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"orgPositionsRow_WWW123WWW_PosNm\" value=\"\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"orgPositionsRow_WWW123WWW_PosID\" value=\"\">
                                              </div>
                                          </td>"
                                        . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"orgPositionsRow_WWW123WWW_PrntNm\" value=\"\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"orgPositionsRow_WWW123WWW_PrntID\" value=\"\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Positions', '', '', '', 'radio', true, '', 'orgPositionsRow_WWW123WWW_PrntID', 'orgPositionsRow_WWW123WWW_PrntNm', 'clear', 0, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                              </div>
                                          </td>
                                          <td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                 <div class=\"input-group\"  style=\"width:100%;\">
                                                   <textarea class=\"form-control\" aria-label=\"...\" id=\"orgPositionsRow_WWW123WWW_PosDesc\" name=\"orgPositionsRow_WWW123WWW_PosDesc\" style=\"width:100%;resize:vertical;\" cols=\"7\" rows=\"1\"></textarea>
                                                   <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"popUpDisplay('orgPositionsRow_WWW123WWW_PosDesc');\" style=\"max-width:30px;width:30px;\">
                                                      <span class=\"glyphicon glyphicon-th-list\"></span>
                                                   </label>
                                                 </div>
                                              </div>
                                          </td>
                                          <td class=\"lovtd\" style=\"text-align: center;\">
                                              <div class=\"form-group form-group-sm \">
                                                            <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                <label class=\"form-check-label\">
                                                                    <input type=\"checkbox\" class=\"form-check-input\" id=\"orgPositionsRow_WWW123WWW_IsEnabled\" name=\"orgPositionsRow_WWW123WWW_IsEnabled\">
                                                                </label>
                                                            </div>
                                              </div>
                                           </td>
                                            <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delOrgPositions('orgPositionsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Position\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                            </td>
                                            <td class=\"lovtd\">&nbsp;</td>
                                        </tr>");
                                ?>
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('orgPositionsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Position">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveOrgPositionsForm();" data-toggle="tooltip" data-placement="bottom" title="Save Position">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-4";
                                $colClassType2 = "col-lg-4";
                                $colClassType3 = "col-lg-4";
                            }
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="orgPositionsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncOrgPositions(event, '', '#orgPositionsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                    <input id="orgPositionsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllOrgPositions('clear', '#orgPositionsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllOrgPositions('', '#orgPositionsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="orgPositionsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Position Name", "Position Description");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="orgPositionsDsplySze" style="min-width:70px !important;">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 20, 30, 50, 100, 500, 1000);
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
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllOrgPositions('previous', '#orgPositionsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllOrgPositions('next', '#orgPositionsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdOrgID=<?php echo $sbmtdOrgID; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="orgPositionsTable" cellspacing="0" width="100%" style="width:100%;min-width: 600px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Position Code/Name</th>
                                            <th>Parent Position</th>
                                            <th>Position Description</th>
                                            <th style="text-align: center;">Enabled?</th>
                                            <th>...</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="orgPositionsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="orgPositionsRow<?php echo $cntr; ?>_PosNm" value="<?php echo $row1[1]; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="orgPositionsRow<?php echo $cntr; ?>_PosID" value="<?php echo $row1[0]; ?>">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[1]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control" aria-label="..." id="orgPositionsRow<?php echo $cntr; ?>_PrntNm" value="<?php echo $row1[3]; ?>">
                                                                <input type="hidden" class="form-control" aria-label="..." id="orgPositionsRow<?php echo $cntr; ?>_PrntID" value="<?php echo $row1[2]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Positions', '', '', '', 'radio', true, '<?php echo $row1[2]; ?>', 'orgPositionsRow<?php echo $cntr; ?>_PrntID', 'orgPositionsRow<?php echo $cntr; ?>_PrntNm', 'clear', 0, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[3]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <textarea class="form-control" aria-label="..." id="orgPositionsRow<?php echo $cntr; ?>_PosDesc" name="orgPositionsRow<?php echo $cntr; ?>_PosDesc" style="width:100%;resize:vertical;" cols="7" rows="1"><?php echo $row1[4]; ?></textarea>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('orgPositionsRow<?php echo $cntr; ?>_PosDesc');" style="max-width:30px;width:30px;">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[4]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd" style="text-align: center;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($row1[5] == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    if ($canEdtOrg === true) {
                                                        ?>
                                                        <div class="form-group form-group-sm ">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="orgPositionsRow<?php echo $cntr; ?>_IsEnabled" name="orgPositionsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[5]; ?></span>
                                                    <?php } ?>
                                                </td>                                      
                                                <td class="lovtd">
                                                    <?php if ($canDelRec === true) { ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delOrgPositions('orgPositionsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Position">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row1[0] . "|org.org_positions|position_id"), $smplTokenWord1));
                                                        ?>');" style="padding:2px !important;">
                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 8) {
                //echo "Account Segments";
                $canDelRec = test_prmssns($dfltPrvldgs[15], $mdlNm);
                $curIdx = 0;
                $pkID = isset($_POST['sbmtdOrgID']) ? $_POST['sbmtdOrgID'] : -1;
                if ($pkID > 0) {
                    $sbmtdOrgID = $pkID;
                    $result1 = get_Org_SegmentDet($sbmtdOrgID);
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='orgAcSegmentsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row">
                            <?php
                            if ($canEdtOrg === true) {
                                $nwRowHtml = "<tr id=\"orgAcSegmentsRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\">
                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <input type=\"number\" class=\"form-control\" aria-label=\"...\" id=\"orgAcSegmentsRow_WWW123WWW_SgmntNum\" value=\"\" style=\"width:100% !important;\">
                                                        </div>
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"orgAcSegmentsRow_WWW123WWW_SgmntID\" value=\"-1\">
                                                </td>
                                                <td class=\"lovtd\">
                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"orgAcSegmentsRow_WWW123WWW_SgmntName\" value=\"\" style=\"width:100% !important;\">
                                                        </div>
                                                </td>
                                                <td class=\"lovtd\" style=\"text-align: center;\">
                                                    &nbsp;
                                                </td>
                                                <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <select class=\"form-control\" id=\"orgAcSegmentsRow_WWW123WWW_SysClsfctn\">";
                                $valslctdArry = array("", "", "", "", "", "");
                                $valuesArrys = array("BusinessGroup", "CostCenter", "Location", "NaturalAccount", "Currency", "Other");

                                for ($z = 0; $z < count($valuesArrys); $z++) {
                                    $nwRowHtml .= "<option value=\"" . $valuesArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $valuesArrys[$z] . "</option>";
                                }
                                $nwRowHtml .= "</select>
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\">&nbsp;</td>
                                                <td class=\"lovtd\">
                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <input type=\"number\" class=\"form-control\" aria-label=\"...\" id=\"orgAcSegmentsRow_WWW123WWW_PrntSgmntNum\" value=\"-1\" style=\"width:100% !important;\">
                                                        </div>
                                                </td>
                                                <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delOrgSegments('orgAcSegmentsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Account Segment\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button>
                                                </td>
                                                <td class=\"lovtd\">&nbsp;</td>
                                            </tr>";
                                $nwRowHtml = urlencode($nwRowHtml);
                                ?>
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('orgAcSegmentsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Account Segment">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveOrgSegmentsForm();" data-toggle="tooltip" data-placement="bottom" title="Save Account Segments">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-4";
                                $colClassType2 = "col-lg-4";
                                $colClassType3 = "col-lg-4";
                            }
                            ?>
                        </div>
                        <div class="row">
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="orgAcSegmentsTable" cellspacing="0" width="100%" style="width:100%;min-width: 600px !important;">
                                    <thead>
                                        <tr>
                                            <th>Segment No.</th>
                                            <th>Segment Name/Prompt</th>
                                            <th style="text-align: center;">Natural Account Segment?</th>
                                            <th>System Classification</th>
                                            <th>Attached Values</th>
                                            <th>Parent Segment Number</th>
                                            <th>...</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="orgAcSegmentsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="number" class="form-control" aria-label="..." id="orgAcSegmentsRow<?php echo $cntr; ?>_SgmntNum" value="<?php echo $row1[1]; ?>" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[1]; ?></span>
                                                    <?php } ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="orgAcSegmentsRow<?php echo $cntr; ?>_SgmntID" value="<?php echo $row1[0]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="orgAcSegmentsRow<?php echo $cntr; ?>_SgmntName" value="<?php echo $row1[2]; ?>" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[2]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd" style="text-align: center;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($row1[3] == "NaturalAccount") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <div class="form-group form-group-sm ">
                                                        <div class="form-check" style="font-size: 12px !important;">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" id="orgAcSegmentsRow<?php echo $cntr; ?>_IsNatAct" name="orgAcSegmentsRow<?php echo $cntr; ?>_IsNatAct" <?php echo $isChkd ?> disabled="true">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="lovtd">
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <?php if ($canEdtOrg === true) { ?>
                                                            <select class="form-control" id="orgAcSegmentsRow<?php echo $cntr; ?>_SysClsfctn">
                                                                <?php
                                                                $valslctdArry = array("", "", "", "", "", "");
                                                                $valuesArrys = array("BusinessGroup", "CostCenter", "Location", "NaturalAccount", "Currency",
                                                                    "Other");

                                                                for ($z = 0; $z < count($valuesArrys); $z++) {
                                                                    if ($row1[3] == $valuesArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <span><?php echo $row1[3]; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-primary" onclick="getSegmentValuesForm(<?php echo $row1[0]; ?>, 'Segment Values', 'ShowDialog', function () {})" style="width:100% !important;">
                                                        <i class="fa fa-desktop fa-1x"></i>&nbsp;Attached Values
                                                    </button>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="number" class="form-control" aria-label="..." id="orgAcSegmentsRow<?php echo $cntr; ?>_PrntSgmntNum" value="<?php echo $row1[4]; ?>" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[4]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canDelRec === true) { ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delOrgSegments('orgAcSegmentsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Account Segment">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row1[0] . "|org.org_acnt_sgmnts|segment_id"), $smplTokenWord1));
                                                        ?>');" style="padding:2px !important;">
                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 9) {
                //Attached Values/Segment Values
                $canDelRec = test_prmssns($dfltPrvldgs[15], $mdlNm);
                $sbmtdSegmentID = isset($_POST['sbmtdSegmentID']) ? $_POST['sbmtdSegmentID'] : -1;
                $segmentValue = isset($_POST['segmentValue']) ? $_POST['segmentValue'] : '';
                $sgValOrgDetOrgID = isset($_POST['sgValOrgDetOrgID']) ? $_POST['sgValOrgDetOrgID'] : $orgID;
                //var_dump($_POST);
                if (trim($srchFor) == "%%" && $segmentValue != "") {
                    $srchFor = $segmentValue;
                    $srchIn = "Value/Description";
                }
                $pkID = -1;
                $total = get_Total_SgmntVals($srchFor, $srchIn, $sbmtdSegmentID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_Basic_SgmntVals($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdSegmentID);
                $sbmtdGrpOrgID = getGrpOrgID();
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>
                <form id='allSgmntValsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddOrg === true) {
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-md-7">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneSgmntValForm(-1, 10, <?php echo $sbmtdSegmentID; ?>);" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Segment Value
                                    </button>
                                </div>
                                <div class="col-md-5">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveSgmntValForm();" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save
                                    </button>
                                </div>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-5";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allSgmntValsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncSgmntVals(event, '', '#myFormsModalLgYBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdSegmentID=<?php echo $sbmtdSegmentID; ?>')">
                                <input id="allSgmntValsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSgmntVals('clear', '#myFormsModalLgYBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdSegmentID=<?php echo $sbmtdSegmentID; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSgmntVals('', '#myFormsModalLgYBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdSegmentID=<?php echo $sbmtdSegmentID; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSgmntValsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Value/Description", "Dependent Value");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSgmntValsDsplySze" style="min-width:70px !important;">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 20, 30, 50, 100, 500, 1000);
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
                        <div class="<?php echo $colClassType1; ?>">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllSgmntVals('previous', '#myFormsModalLgYBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdSegmentID=<?php echo $sbmtdSegmentID; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllSgmntVals('next', '#myFormsModalLgYBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdSegmentID=<?php echo $sbmtdSegmentID; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdGrpOrgID" value="<?php echo $sbmtdGrpOrgID; ?>">
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row" style="padding:0px 15px 0px 15px !important;">
                        <div  class="col-md-3" style="padding:0px 3px 0px 0px !important;">
                            <fieldset class="basic_person_fs" style="padding:1px 1px 0px 1px !important;">
                                <table class="table table-striped table-bordered table-responsive" id="allSgmntValsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th style="max-width: 200px;width:200px;">Segment Value/Description</th>
                                            <th>...</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $canDelRec = test_prmssns($dfltPrvldgs[15], $mdlNm);
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($pkID <= 0 && $cntr <= 0) {
                                                $pkID = (int) $row[0];
                                            }
                                            $cntr += 1;
                                            ?>
                                            <tr id="allSgmntValsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd" style="max-width: 200px;width:200px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                                                    <?php
                                                    echo str_replace(" ", "&nbsp;", str_replace("   ", " ", $row[3]));
                                                    ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allSgmntValsRow<?php echo $cntr; ?>_SgmntValID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canDelRec === true) { ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSgmntVals('allSgmntValsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Segment Value">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row[0] . "|org.org_segment_values|segment_value_id"),
                                                                        $smplTokenWord1));
                                                        ?>');" style="padding:2px !important;">
                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                        <div  class="col-md-9" style="padding:0px 0px 0px 15px !important">
                            <fieldset class="basic_person_fs99">
                                <div class="rho-container-fluid" id="sgmntValsDetailInfo">
                                    <?php
                                    if ($pkID > 0) {
                                        $result1 = get_SgmntValsDet($pkID);
                                        $segmentValueID = $pkID;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $segmentValueID = (int) $row1[0];
                                            $sbmtdSegmentID = (int) $row1[1];
                                            $sgValOrgDetOrgID = (int) $row1[31];
                                            $dpndntSegmentID = (int) $row1[30];
                                            $sbmtdSegmentClsfctn = $row1[32];
                                            $segmentValue = $row1[2];
                                            $segmentValueDesc = $row1[3];
                                            $prntSegmentValueID = (int) $row1[4];
                                            $prntSegmentValue = $row1[5];
                                            $dpndntSegmentValueID = (int) $row1[6];
                                            $dpndntSegmentValue = $row1[7];
                                            $prntSegmentValueN = $row1[35];
                                            $dpndntSegmentValueN = $row1[36];
                                            $sgValAllwdGrpType = $row1[8];
                                            $sgValAllwdGrpID = $row1[9];
                                            $sgValAllwdGrpValue = $row1[10];
                                            $sgValIsEnabled = $row1[11];
                                            $sgValCmbntnsAllwd = $row1[12];
                                            $sgValIsPrntAcnt = $row1[13];
                                            $sgValIsContraAcnt = $row1[14];
                                            $sgValIsRetErngsAcnt = $row1[15];
                                            $sgValIsNetIncmAcnt = $row1[16];
                                            $sgValIsSuspnsAcnt = $row1[17];
                                            $sgValHsSubldgrAcnt = $row1[18];
                                            $sgValAcntType = getFullAcctType($row1[19]);
                                            $sgValAcntClsfctn = $row1[26];
                                            $sgValCtrlAcntID = (int) $row1[22];
                                            $sgValCtrlAcnt = $row1[23];
                                            $sgValMppdAcntID = (int) $row1[27];
                                            $sgValMppdAcnt = $row1[28];
                                            $sgValLnkdSiteLocID = (int) $row1[33];
                                            $sgValLnkdSiteLoc = $row1[34];
                                            ?>
                                            <div id="orgDetPage" class="tab-pane fadein active" style="border:none !important;">
                                                <div class="row">
                                                    <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="segmentValue" class="control-label col-lg-4">Segment Value:</label>
                                                                    <div  class="col-lg-8">
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <input type="text" class="form-control" aria-label="..." id="segmentValue" name="segmentValue" value="<?php echo $segmentValue; ?>" style="width:100%;">
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo $segmentValue; ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        <input type="hidden" class="form-control" aria-label="..." id="sgValOrgDetOrgID" name="sgValOrgDetOrgID" value="<?php echo $sgValOrgDetOrgID; ?>">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="segmentValueID" name="segmentValueID" value="<?php echo $segmentValueID; ?>">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdSegmentID" name="sbmtdSegmentID" value="<?php echo $sbmtdSegmentID; ?>">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdSegmentClsfctn" name="sbmtdSegmentClsfctn" value="<?php echo $sbmtdSegmentClsfctn; ?>">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="dpndntSegmentID" name="dpndntSegmentID" value="<?php echo $dpndntSegmentID; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="segmentValueDesc" class="control-label col-lg-4">Value Description:</label>
                                                                    <div  class="col-lg-8">
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <textarea class="form-control" aria-label="..." id="segmentValueDesc" name="segmentValueDesc" style="width:100%;" cols="3" rows="2"><?php echo $segmentValueDesc; ?></textarea>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo $segmentValueDesc; ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="prntSegmentValue" class="control-label col-lg-4">Parent Segment:</label>
                                                                    <div  class="col-lg-8">
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="prntSegmentValue" name="prntSegmentValue" value="<?php echo $prntSegmentValue; ?>" readonly="true">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="prntSegmentValueID" name="prntSegmentValueID" value="<?php echo $prntSegmentValueN; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Segment Values', 'sbmtdSegmentID', '', '', 'radio', true, '<?php echo $prntSegmentValueN; ?>', 'prntSegmentValueID', 'prntSegmentValue', 'clear', 1, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo $prntSegmentValue; ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="dpndntSegmentValue" class="control-label col-lg-4">Dependent Segment:</label>
                                                                    <div  class="col-lg-8">
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="dpndntSegmentValue" name="dpndntSegmentValue" value="<?php echo $dpndntSegmentValue; ?>" readonly="true">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="dpndntSegmentValueID" name="dpndntSegmentValueID" value="<?php echo $dpndntSegmentValueN; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Segment Values', 'dpndntSegmentID', '', '', 'radio', true, '<?php echo $dpndntSegmentValueN; ?>', 'dpndntSegmentValueID', 'dpndntSegmentValue', 'clear', 1, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo $dpndntSegmentValue; ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                if ($sbmtdSegmentClsfctn == "CostCenter" || $sbmtdSegmentClsfctn == "Location") {
                                                                    ?>
                                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                        <label for="sgValLnkdSiteLoc" class="control-label col-lg-4">Linked Site/ Location:</label>
                                                                        <div  class="col-lg-8">
                                                                            <?php
                                                                            if ($canEdtOrg === true) {
                                                                                ?>
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control" aria-label="..." id="sgValLnkdSiteLoc" name="sgValLnkdSiteLoc" value="<?php echo $sgValLnkdSiteLoc; ?>" readonly="true">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="sgValLnkdSiteLocID" name="sgValLnkdSiteLocID" value="<?php echo $sgValLnkdSiteLocID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', 'sgValOrgDetOrgID', '', '', 'radio', true, '<?php echo $sgValLnkdSiteLocID; ?>', 'sgValLnkdSiteLocID', 'sgValLnkdSiteLoc', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php } else {
                                                                                ?>
                                                                                <span><?php echo $sgValLnkdSiteLoc; ?></span>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValAllwdGrpType" class="control-label col-lg-4">Allowed Group Type:</label>
                                                                    <div  class="col-lg-8">
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <select class="form-control" id="sgValAllwdGrpType" onchange="grpTypOrgChange('sgValAllwdGrpType', 'sgValAllwdGrpValue', 'sgValAllwdGrpID', 'sgValAllwdGrpNmLbl');">
                                                                                <?php
                                                                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                                                                $valuesArrys = array("Everyone", "Divisions/Groups",
                                                                                    "Grade", "Job", "Position", "Site/Location", "Person Type", "Single Person");

                                                                                for ($z = 0; $z < count($valuesArrys); $z++) {
                                                                                    if ($sgValAllwdGrpType == $valuesArrys[$z]) {
                                                                                        $valslctdArry[$z] = "selected";
                                                                                    }
                                                                                    ?>
                                                                                    <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $sgValAllwdGrpType; ?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValAllwdGrpValue" class="control-label col-lg-4">Allowed Group Name:</label>
                                                                    <div  class="col-lg-8">
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="sgValAllwdGrpValue" value="<?php echo $sgValAllwdGrpValue; ?>" readonly="true">
                                                                                <input type="hidden" id="sgValAllwdGrpID" value="<?php echo $sgValAllwdGrpID; ?>">
                                                                                <label disabled="true" id="sgValAllwdGrpNmLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovsTblr('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'orgDetOrgID', '', '', 'radio', true, '', 'sgValAllwdGrpID', 'sgValAllwdGrpValue', 'clear', 1, '', 'sgValAllwdGrpType');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $sgValAllwdGrpValue; ?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValCmbntnsAllwd" class="control-label col-lg-4">Combinations Allowed?:</label>
                                                                    <div class="col-lg-8">
                                                                        <?php
                                                                        $chkdYes = "";
                                                                        $chkdNo = "checked=\"\"";
                                                                        if ($sgValCmbntnsAllwd == "1") {
                                                                            $chkdNo = "";
                                                                            $chkdYes = "checked=\"\"";
                                                                        }
                                                                        ?>
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <label class="radio-inline"><input type="radio" name="sgValCmbntnsAllwd" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                            <label class="radio-inline"><input type="radio" name="orgDetIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo ($sgValCmbntnsAllwd == "1" ? "YES" : "NO"); ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValIsEnabled" class="control-label col-lg-4">Is Enabled?:</label>
                                                                    <div class="col-lg-8">
                                                                        <?php
                                                                        $chkdYes = "";
                                                                        $chkdNo = "checked=\"\"";
                                                                        if ($sgValIsEnabled == "1") {
                                                                            $chkdNo = "";
                                                                            $chkdYes = "checked=\"\"";
                                                                        }
                                                                        ?>
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <label class="radio-inline"><input type="radio" name="sgValIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                            <label class="radio-inline"><input type="radio" name="sgValIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo ($sgValIsEnabled == "1" ? "YES" : "NO"); ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <?php
                                                if ($sbmtdSegmentClsfctn == "NaturalAccount") {
                                                    ?> 
                                                    <div class="row">
                                                        <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValIsPrntAcnt" class="control-label col-lg-4">Parent Account?:</label>
                                                                    <div class="col-lg-8">
                                                                        <?php
                                                                        $chkdYes = "";
                                                                        $chkdNo = "checked=\"\"";
                                                                        if ($sgValIsPrntAcnt == "1") {
                                                                            $chkdNo = "";
                                                                            $chkdYes = "checked=\"\"";
                                                                        }
                                                                        ?>
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <label class="radio-inline"><input type="radio" name="sgValIsPrntAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                            <label class="radio-inline"><input type="radio" name="sgValIsPrntAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo ($sgValIsPrntAcnt == "1" ? "YES" : "NO"); ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValIsContraAcnt" class="control-label col-lg-4">Contra Account?:</label>
                                                                    <div class="col-lg-8">
                                                                        <?php
                                                                        $chkdYes = "";
                                                                        $chkdNo = "checked=\"\"";
                                                                        if ($sgValIsContraAcnt == "1") {
                                                                            $chkdNo = "";
                                                                            $chkdYes = "checked=\"\"";
                                                                        }
                                                                        ?>
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <label class="radio-inline"><input type="radio" name="sgValIsContraAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                            <label class="radio-inline"><input type="radio" name="sgValIsContraAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo ($sgValIsContraAcnt == "1" ? "YES" : "NO"); ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValIsRetErngsAcnt" class="control-label col-lg-4">Retained Earnings A/c?:</label>
                                                                    <div class="col-lg-8">
                                                                        <?php
                                                                        $chkdYes = "";
                                                                        $chkdNo = "checked=\"\"";
                                                                        if ($sgValIsRetErngsAcnt == "1") {
                                                                            $chkdNo = "";
                                                                            $chkdYes = "checked=\"\"";
                                                                        }
                                                                        ?>
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <label class="radio-inline"><input type="radio" name="sgValIsRetErngsAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                            <label class="radio-inline"><input type="radio" name="sgValIsRetErngsAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo ($sgValIsRetErngsAcnt == "1" ? "YES" : "NO"); ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValIsNetIncmAcnt" class="control-label col-lg-4">Net Income Account?:</label>
                                                                    <div class="col-lg-8">
                                                                        <?php
                                                                        $chkdYes = "";
                                                                        $chkdNo = "checked=\"\"";
                                                                        if ($sgValIsNetIncmAcnt == "1") {
                                                                            $chkdNo = "";
                                                                            $chkdYes = "checked=\"\"";
                                                                        }
                                                                        ?>
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <label class="radio-inline"><input type="radio" name="sgValIsNetIncmAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                            <label class="radio-inline"><input type="radio" name="sgValIsNetIncmAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo ($sgValIsNetIncmAcnt == "1" ? "YES" : "NO"); ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValIsSuspnsAcnt" class="control-label col-lg-4">Suspense Account?:</label>
                                                                    <div class="col-lg-8">
                                                                        <?php
                                                                        $chkdYes = "";
                                                                        $chkdNo = "checked=\"\"";
                                                                        if ($sgValIsSuspnsAcnt == "1") {
                                                                            $chkdNo = "";
                                                                            $chkdYes = "checked=\"\"";
                                                                        }
                                                                        ?>
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <label class="radio-inline"><input type="radio" name="sgValIsSuspnsAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                            <label class="radio-inline"><input type="radio" name="sgValIsSuspnsAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo ($sgValIsSuspnsAcnt == "1" ? "YES" : "NO"); ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValHsSubldgrAcnt" class="control-label col-lg-4">Has Subledger Accounts?:</label>
                                                                    <div class="col-lg-8">
                                                                        <?php
                                                                        $chkdYes = "";
                                                                        $chkdNo = "checked=\"\"";
                                                                        if ($sgValHsSubldgrAcnt == "1") {
                                                                            $chkdNo = "";
                                                                            $chkdYes = "checked=\"\"";
                                                                        }
                                                                        ?>
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <label class="radio-inline"><input type="radio" name="sgValHsSubldgrAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                            <label class="radio-inline"><input type="radio" name="sgValHsSubldgrAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo ($sgValHsSubldgrAcnt == "1" ? "YES" : "NO"); ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValAcntType" class="control-label col-lg-4">Account Type:</label>
                                                                    <div  class="col-lg-8">
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <select class="form-control" id="sgValAcntType" onchange="">
                                                                                <?php
                                                                                $valslctdArry = array("", "", "", "", "");
                                                                                $valuesArrys = array("A -ASSET", "EQ-EQUITY", "L -LIABILITY", "R -REVENUE",
                                                                                    "EX-EXPENSE");

                                                                                for ($z = 0; $z < count($valuesArrys); $z++) {
                                                                                    if ($sgValAcntType == $valuesArrys[$z]) {
                                                                                        $valslctdArry[$z] = "selected";
                                                                                    }
                                                                                    ?>
                                                                                    <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $sgValAcntType; ?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValAcntClsfctn" class="control-label col-lg-4">Classification:</label>
                                                                    <div  class="col-lg-8">
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <select class="form-control" id="sgValAcntClsfctn" onchange="">
                                                                                <?php
                                                                                $valslctdArry = array("", "", "", "", "", "", "", "",
                                                                                    "", "", "", "", "", "", "", "",
                                                                                    "", "", "", "", "", "", "", "",
                                                                                    "", "", "", "");
                                                                                $valuesArrys = array("Cash and Cash Equivalents",
                                                                                    "Operating Activities.Sale of Goods",
                                                                                    "Operating Activities.Sale of Services",
                                                                                    "Operating Activities.Other Income Sources",
                                                                                    "Operating Activities.Cost of Sales",
                                                                                    "Operating Activities.Net Income",
                                                                                    "Operating Activities.Depreciation Expense",
                                                                                    "Operating Activities.Amortization Expense",
                                                                                    "Operating Activities.Gain on Sale of Asset"/* NEGATE */,
                                                                                    "Operating Activities.Loss on Sale of Asset",
                                                                                    "Operating Activities.Other Non-Cash Expense",
                                                                                    "Operating Activities.Accounts Receivable"/* NEGATE */,
                                                                                    "Operating Activities.Bad Debt Expense"/* NEGATE */,
                                                                                    "Operating Activities.Prepaid Expenses"/* NEGATE */,
                                                                                    "Operating Activities.Inventory"/* NEGATE */,
                                                                                    "Operating Activities.Accounts Payable",
                                                                                    "Operating Activities.Accrued Expenses",
                                                                                    "Operating Activities.Taxes Payable",
                                                                                    "Operating Activities.Operating Expense"/* NEGATE */,
                                                                                    "Operating Activities.General and Administrative Expense"/* NEGATE */,
                                                                                    "Investing Activities.Asset Sales/Purchases"/* NEGATE */,
                                                                                    "Investing Activities.Equipment Sales/Purchases"/* NEGATE */,
                                                                                    "Financing Activities.Capital/Stock",
                                                                                    "Financing Activities.Long Term Debts",
                                                                                    "Financing Activities.Short Term Debts",
                                                                                    "Financing Activities.Equity Securities",
                                                                                    "Financing Activities.Dividends Declared"/* NEGATE */,
                                                                                    "");

                                                                                for ($z = 0; $z < count($valuesArrys); $z++) {
                                                                                    if ($sgValAcntClsfctn == $valuesArrys[$z]) {
                                                                                        $valslctdArry[$z] = "selected";
                                                                                    }
                                                                                    ?>
                                                                                    <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $sgValAcntClsfctn; ?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValCtrlAcnt" class="control-label col-lg-4">Control Account:</label>
                                                                    <div  class="col-lg-8">
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="sgValCtrlAcnt" name="sgValCtrlAcnt" value="<?php echo $sgValCtrlAcnt; ?>" readonly="true">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="sgValCtrlAcntID" name="sgValCtrlAcntID" value="<?php echo $sgValCtrlAcntID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Control Account Segment Values', 'sbmtdSegmentID', '', '', 'radio', true, '<?php echo $sgValCtrlAcntID; ?>', 'sgValCtrlAcntID', 'sgValCtrlAcnt', 'clear', 1, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo $sgValCtrlAcnt; ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="sgValMppdAcnt" class="control-label col-lg-4">Mapped Group Account:</label>
                                                                    <div  class="col-lg-8">
                                                                        <?php
                                                                        if ($canEdtOrg === true) {
                                                                            ?>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="sgValMppdAcnt" name="sgValMppdAcnt" value="<?php echo $sgValMppdAcnt; ?>" readonly="true">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="sgValMppdAcntID" name="sgValMppdAcntID" value="<?php echo $sgValMppdAcntID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'sbmtdGrpOrgID', '', '', 'radio', true, '<?php echo $sgValMppdAcntID; ?>', 'sgValMppdAcntID', 'sgValMppdAcnt', 'clear', 1, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo $sgValMppdAcnt; ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <?php
                                                        $nwRowHtml = urlencode("<tr id=\"allSgmntClsfctnsRow__WWW123WWW\" class=\"hand_cursor\">
                                                                                <td class=\"lovtd\">New
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allSgmntClsfctnsRow_WWW123WWW_ClsfctnID\" value=\"-1\">
                                                                                </td>
                                                                                <td class=\"lovtd\">
                                                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allSgmntClsfctnsRow_WWW123WWW_MajClsfctn\" value=\"\">
                                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '', '', 'allSgmntClsfctnsRow_WWW123WWW_MajClsfctn', 'clear', 0, '');\">
                                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                </td>
                                                                                <td class=\"lovtd\">
                                                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allSgmntClsfctnsRow_WWW123WWW_MinClsfctn\" value=\"\">
                                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '', '', 'allSgmntClsfctnsRow_WWW123WWW_MinClsfctn', 'clear', 0, '');\">
                                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                </td>
                                                                                  <td class=\"lovtd\">
                                                                                      <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delSgValRptClsfctn('allSgmntClsfctnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Report Classification\">
                                                                                          <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                      </button>
                                                                                  </td>
                                                                                  <td class=\"lovtd\">&nbsp;</td>
                                                                            </tr>");
                                                        ?>
                                                        <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <fieldset class="basic_person_fs">
                                                                <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allSgmntClsfctnsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        New Report Classification
                                                                    </button>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveSgmntValForm();" style="width:100% !important;">
                                                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        Save
                                                                    </button>
                                                                </div>
                                                                <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                    <table class="table table-striped table-bordered table-responsive" id="allSgmntClsfctnsTable" cellspacing="0" width="100%" style="width:100%;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>No.</th>
                                                                                <th>Main Reporting Category</th>
                                                                                <th>Sub-Reporting Category</th>
                                                                                <th>...</th>
                                                                                <th>...</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $resultClsfctn = get_One_RptClsfctns($segmentValueID);
                                                                            $cntr = 0;
                                                                            while ($row1 = loc_db_fetch_array($resultClsfctn)) {
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr id="allSgmntClsfctnsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?>
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="allSgmntClsfctnsRow<?php echo $cntr; ?>_ClsfctnID" value="<?php echo $row1[0]; ?>">
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <?php
                                                                                        if ($canEdtOrg === true) {
                                                                                            ?>
                                                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                                                <div class="input-group"  style="width:100%;">
                                                                                                    <input type="text" class="form-control" aria-label="..." id="allSgmntClsfctnsRow<?php echo $cntr; ?>_MajClsfctn" value="<?php echo $row1[1]; ?>">
                                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '<?php echo $row1[1]; ?>', '', 'allSgmntClsfctnsRow<?php echo $cntr; ?>_MajClsfctn', 'clear', 0, '');">
                                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php } else { ?>
                                                                                            <span class=""><?php echo $row1[1]; ?></span>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <?php
                                                                                        if ($canEdtOrg === true) {
                                                                                            ?>
                                                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                                                <div class="input-group"  style="width:100%;">
                                                                                                    <input type="text" class="form-control" aria-label="..." id="allSgmntClsfctnsRow<?php echo $cntr; ?>_MinClsfctn" value="<?php echo $row1[2]; ?>">
                                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '<?php echo $row1[2]; ?>', '', 'allSgmntClsfctnsRow<?php echo $cntr; ?>_MinClsfctn', 'clear', 0, '');">
                                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php } else { ?>
                                                                                            <span class=""><?php echo $row1[2]; ?></span>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <?php
                                                                                        if ($canDelRec === true) {
                                                                                            ?>
                                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSgValRptClsfctn('allSgmntClsfctnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Report Classifications">
                                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <?php
                                                                                        if ($canVwRcHstry === true) {
                                                                                            ?>
                                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                            echo urlencode(encrypt1(($row1[0] . "|org.org_account_clsfctns|account_clsfctn_id"),
                                                                                                            $smplTokenWord1));
                                                                                            ?>');" style="padding:2px !important;">
                                                                                                <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <span>No Results Found</span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 11) {
                //Table Row Clicked
                $canDelRec = test_prmssns($dfltPrvldgs[15], $mdlNm);
                $sbmtdSegValID = isset($_POST['sbmtdSegValID']) ? $_POST['sbmtdSegValID'] : -1;
                $pkID = $sbmtdSegValID;
                if ($pkID > 0) {
                    $result1 = get_SgmntValsDet($pkID);
                    $segmentValueID = $pkID;
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $segmentValueID = (int) $row1[0];
                        $sgValOrgDetOrgID = (int) $row1[31];
                        $sbmtdSegmentID = (int) $row1[1];
                        $dpndntSegmentID = (int) $row1[30];
                        $sbmtdSegmentClsfctn = $row1[32];
                        $segmentValue = $row1[2];
                        $segmentValueDesc = $row1[3];
                        $prntSegmentValueID = (int) $row1[4];
                        $prntSegmentValue = $row1[5];
                        $dpndntSegmentValueID = (int) $row1[6];
                        $dpndntSegmentValue = $row1[7];
                        $prntSegmentValueN = $row1[35];
                        $dpndntSegmentValueN = $row1[36];
                        $sgValAllwdGrpType = $row1[8];
                        $sgValAllwdGrpID = $row1[9];
                        $sgValAllwdGrpValue = $row1[10];
                        $sgValIsEnabled = $row1[11];
                        $sgValCmbntnsAllwd = $row1[12];
                        $sgValIsPrntAcnt = $row1[13];
                        $sgValIsContraAcnt = $row1[14];
                        $sgValIsRetErngsAcnt = $row1[15];
                        $sgValIsNetIncmAcnt = $row1[16];
                        $sgValIsSuspnsAcnt = $row1[17];
                        $sgValHsSubldgrAcnt = $row1[18];
                        $sgValAcntType = getFullAcctType($row1[19]);
                        $sgValAcntClsfctn = $row1[26];
                        $sgValCtrlAcntID = (int) $row1[22];
                        $sgValCtrlAcnt = $row1[23];
                        $sgValMppdAcntID = (int) $row1[27];
                        $sgValMppdAcnt = $row1[28];
                        $sgValLnkdSiteLocID = (int) $row1[33];
                        $sgValLnkdSiteLoc = $row1[34];
                        ?>
                        <div id="orgDetPage" class="tab-pane fadein active" style="border:none !important;">
                            <div class="row">
                                <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="segmentValue" class="control-label col-lg-4">Segment Value:</label>
                                                <div  class="col-lg-8">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <input type="text" class="form-control" aria-label="..." id="segmentValue" name="segmentValue" value="<?php echo $segmentValue; ?>" style="width:100%;">
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo $segmentValue; ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="sgValOrgDetOrgID" name="sgValOrgDetOrgID" value="<?php echo $sgValOrgDetOrgID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="segmentValueID" name="segmentValueID" value="<?php echo $segmentValueID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdSegmentID" name="sbmtdSegmentID" value="<?php echo $sbmtdSegmentID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdSegmentClsfctn" name="sbmtdSegmentClsfctn" value="<?php echo $sbmtdSegmentClsfctn; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="dpndntSegmentID" name="dpndntSegmentID" value="<?php echo $dpndntSegmentID; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="segmentValueDesc" class="control-label col-lg-4">Value Description:</label>
                                                <div  class="col-lg-8">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <textarea class="form-control" aria-label="..." id="segmentValueDesc" name="segmentValueDesc" style="width:100%;" cols="3" rows="2"><?php echo $segmentValueDesc; ?></textarea>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo $segmentValueDesc; ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="prntSegmentValue" class="control-label col-lg-4">Parent Segment:</label>
                                                <div  class="col-lg-8">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="prntSegmentValue" name="prntSegmentValue" value="<?php echo $prntSegmentValue; ?>" readonly="true">
                                                            <input type="hidden" class="form-control" aria-label="..." id="prntSegmentValueID" name="prntSegmentValueID" value="<?php echo $prntSegmentValueN; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Segment Values', 'sbmtdSegmentID', '', '', 'radio', true, '<?php echo $prntSegmentValueN; ?>', 'prntSegmentValueID', 'prntSegmentValue', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo $prntSegmentValue; ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="dpndntSegmentValue" class="control-label col-lg-4">Dependent Segment:</label>
                                                <div  class="col-lg-8">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="dpndntSegmentValue" name="dpndntSegmentValue" value="<?php echo $dpndntSegmentValue; ?>" readonly="true">
                                                            <input type="hidden" class="form-control" aria-label="..." id="dpndntSegmentValueID" name="dpndntSegmentValueID" value="<?php echo $dpndntSegmentValueN; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Segment Values', 'dpndntSegmentID', '', '', 'radio', true, '<?php echo $dpndntSegmentValueN; ?>', 'dpndntSegmentValueID', 'dpndntSegmentValue', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo $dpndntSegmentValue; ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                            if ($sbmtdSegmentClsfctn == "CostCenter" || $sbmtdSegmentClsfctn == "Location") {
                                                ?>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="sgValLnkdSiteLoc" class="control-label col-lg-4">Linked Site/ Location:</label>
                                                    <div  class="col-lg-8">
                                                        <?php
                                                        if ($canEdtOrg === true) {
                                                            ?>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" aria-label="..." id="sgValLnkdSiteLoc" name="sgValLnkdSiteLoc" value="<?php echo $sgValLnkdSiteLoc; ?>" readonly="true">
                                                                <input type="hidden" class="form-control" aria-label="..." id="sgValLnkdSiteLocID" name="sgValLnkdSiteLocID" value="<?php echo $sgValLnkdSiteLocID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', 'sgValOrgDetOrgID', '', '', 'radio', true, '<?php echo $sgValLnkdSiteLocID; ?>', 'sgValLnkdSiteLocID', 'sgValLnkdSiteLoc', 'clear', 1, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else {
                                                            ?>
                                                            <span><?php echo $sgValLnkdSiteLoc; ?></span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValAllwdGrpType" class="control-label col-lg-4">Allowed Group Type:</label>
                                                <div  class="col-lg-8">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <select class="form-control" id="sgValAllwdGrpType" onchange="grpTypOrgChange('sgValAllwdGrpType', 'sgValAllwdGrpValue', 'sgValAllwdGrpID', 'sgValAllwdGrpNmLbl');">
                                                            <?php
                                                            $valslctdArry = array("", "", "", "", "", "", "", "");
                                                            $valuesArrys = array("Everyone", "Divisions/Groups",
                                                                "Grade", "Job", "Position", "Site/Location", "Person Type", "Single Person");

                                                            for ($z = 0; $z < count($valuesArrys); $z++) {
                                                                if ($sgValAllwdGrpType == $valuesArrys[$z]) {
                                                                    $valslctdArry[$z] = "selected";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <span><?php echo $sgValAllwdGrpType; ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValAllwdGrpValue" class="control-label col-lg-4">Allowed Group Name:</label>
                                                <div  class="col-lg-8">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="sgValAllwdGrpValue" value="<?php echo $sgValAllwdGrpValue; ?>" readonly="true">
                                                            <input type="hidden" id="sgValAllwdGrpID" value="<?php echo $sgValAllwdGrpID; ?>">
                                                            <label disabled="true" id="sgValAllwdGrpNmLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovsTblr('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'orgDetOrgID', '', '', 'radio', true, '', 'sgValAllwdGrpID', 'sgValAllwdGrpValue', 'clear', 1, '', 'sgValAllwdGrpType');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo $sgValAllwdGrpValue; ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValCmbntnsAllwd" class="control-label col-lg-4">Combinations Allowed?:</label>
                                                <div class="col-lg-8">
                                                    <?php
                                                    $chkdYes = "";
                                                    $chkdNo = "checked=\"\"";
                                                    if ($sgValCmbntnsAllwd == "1") {
                                                        $chkdNo = "";
                                                        $chkdYes = "checked=\"\"";
                                                    }
                                                    ?>
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <label class="radio-inline"><input type="radio" name="sgValCmbntnsAllwd" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                        <label class="radio-inline"><input type="radio" name="orgDetIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo ($sgValCmbntnsAllwd == "1" ? "YES" : "NO"); ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValIsEnabled" class="control-label col-lg-4">Is Enabled?:</label>
                                                <div class="col-lg-8">
                                                    <?php
                                                    $chkdYes = "";
                                                    $chkdNo = "checked=\"\"";
                                                    if ($sgValIsEnabled == "1") {
                                                        $chkdNo = "";
                                                        $chkdYes = "checked=\"\"";
                                                    }
                                                    ?>
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <label class="radio-inline"><input type="radio" name="sgValIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                        <label class="radio-inline"><input type="radio" name="sgValIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo ($sgValIsEnabled == "1" ? "YES" : "NO"); ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <?php if ($sbmtdSegmentClsfctn == "NaturalAccount") { ?> 
                                <div class="row">
                                    <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValIsPrntAcnt" class="control-label col-lg-4">Parent Account?:</label>
                                                <div class="col-lg-8">
                                                    <?php
                                                    $chkdYes = "";
                                                    $chkdNo = "checked=\"\"";
                                                    if ($sgValIsPrntAcnt == "1") {
                                                        $chkdNo = "";
                                                        $chkdYes = "checked=\"\"";
                                                    }
                                                    ?>
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <label class="radio-inline"><input type="radio" name="sgValIsPrntAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                        <label class="radio-inline"><input type="radio" name="sgValIsPrntAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo ($sgValIsPrntAcnt == "1" ? "YES" : "NO"); ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValIsContraAcnt" class="control-label col-lg-4">Contra Account?:</label>
                                                <div class="col-lg-8">
                                                    <?php
                                                    $chkdYes = "";
                                                    $chkdNo = "checked=\"\"";
                                                    if ($sgValIsContraAcnt == "1") {
                                                        $chkdNo = "";
                                                        $chkdYes = "checked=\"\"";
                                                    }
                                                    ?>
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <label class="radio-inline"><input type="radio" name="sgValIsContraAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                        <label class="radio-inline"><input type="radio" name="sgValIsContraAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo ($sgValIsContraAcnt == "1" ? "YES" : "NO"); ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValIsRetErngsAcnt" class="control-label col-lg-4">Retained Earnings A/c?:</label>
                                                <div class="col-lg-8">
                                                    <?php
                                                    $chkdYes = "";
                                                    $chkdNo = "checked=\"\"";
                                                    if ($sgValIsRetErngsAcnt == "1") {
                                                        $chkdNo = "";
                                                        $chkdYes = "checked=\"\"";
                                                    }
                                                    ?>
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <label class="radio-inline"><input type="radio" name="sgValIsRetErngsAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                        <label class="radio-inline"><input type="radio" name="sgValIsRetErngsAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo ($sgValIsRetErngsAcnt == "1" ? "YES" : "NO"); ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValIsNetIncmAcnt" class="control-label col-lg-4">Net Income Account?:</label>
                                                <div class="col-lg-8">
                                                    <?php
                                                    $chkdYes = "";
                                                    $chkdNo = "checked=\"\"";
                                                    if ($sgValIsNetIncmAcnt == "1") {
                                                        $chkdNo = "";
                                                        $chkdYes = "checked=\"\"";
                                                    }
                                                    ?>
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <label class="radio-inline"><input type="radio" name="sgValIsNetIncmAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                        <label class="radio-inline"><input type="radio" name="sgValIsNetIncmAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo ($sgValIsNetIncmAcnt == "1" ? "YES" : "NO"); ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValIsSuspnsAcnt" class="control-label col-lg-4">Suspense Account?:</label>
                                                <div class="col-lg-8">
                                                    <?php
                                                    $chkdYes = "";
                                                    $chkdNo = "checked=\"\"";
                                                    if ($sgValIsSuspnsAcnt == "1") {
                                                        $chkdNo = "";
                                                        $chkdYes = "checked=\"\"";
                                                    }
                                                    ?>
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <label class="radio-inline"><input type="radio" name="sgValIsSuspnsAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                        <label class="radio-inline"><input type="radio" name="sgValIsSuspnsAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo ($sgValIsSuspnsAcnt == "1" ? "YES" : "NO"); ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValHsSubldgrAcnt" class="control-label col-lg-4">Has Subledger Accounts?:</label>
                                                <div class="col-lg-8">
                                                    <?php
                                                    $chkdYes = "";
                                                    $chkdNo = "checked=\"\"";
                                                    if ($sgValHsSubldgrAcnt == "1") {
                                                        $chkdNo = "";
                                                        $chkdYes = "checked=\"\"";
                                                    }
                                                    ?>
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <label class="radio-inline"><input type="radio" name="sgValHsSubldgrAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                        <label class="radio-inline"><input type="radio" name="sgValHsSubldgrAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo ($sgValHsSubldgrAcnt == "1" ? "YES" : "NO"); ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValAcntType" class="control-label col-lg-4">Account Type:</label>
                                                <div  class="col-lg-8">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <select class="form-control" id="sgValAcntType" onchange="">
                                                            <?php
                                                            $valslctdArry = array("", "", "", "", "");
                                                            $valuesArrys = array("A -ASSET", "EQ-EQUITY", "L -LIABILITY", "R -REVENUE", "EX-EXPENSE");

                                                            for ($z = 0; $z < count($valuesArrys); $z++) {
                                                                if ($sgValAcntType == $valuesArrys[$z]) {
                                                                    $valslctdArry[$z] = "selected";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <span><?php echo $sgValAcntType; ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValAcntClsfctn" class="control-label col-lg-4">Classification:</label>
                                                <div  class="col-lg-8">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <select class="form-control" id="sgValAcntClsfctn" onchange="">
                                                            <?php
                                                            $valslctdArry = array("", "", "", "", "", "", "", "",
                                                                "", "", "", "", "", "", "", "",
                                                                "", "", "", "", "", "", "", "",
                                                                "", "", "", "");
                                                            $valuesArrys = array("Cash and Cash Equivalents",
                                                                "Operating Activities.Sale of Goods",
                                                                "Operating Activities.Sale of Services",
                                                                "Operating Activities.Other Income Sources",
                                                                "Operating Activities.Cost of Sales",
                                                                "Operating Activities.Net Income",
                                                                "Operating Activities.Depreciation Expense",
                                                                "Operating Activities.Amortization Expense",
                                                                "Operating Activities.Gain on Sale of Asset"/* NEGATE */,
                                                                "Operating Activities.Loss on Sale of Asset",
                                                                "Operating Activities.Other Non-Cash Expense",
                                                                "Operating Activities.Accounts Receivable"/* NEGATE */,
                                                                "Operating Activities.Bad Debt Expense"/* NEGATE */,
                                                                "Operating Activities.Prepaid Expenses"/* NEGATE */,
                                                                "Operating Activities.Inventory"/* NEGATE */,
                                                                "Operating Activities.Accounts Payable",
                                                                "Operating Activities.Accrued Expenses",
                                                                "Operating Activities.Taxes Payable",
                                                                "Operating Activities.Operating Expense"/* NEGATE */,
                                                                "Operating Activities.General and Administrative Expense"/* NEGATE */,
                                                                "Investing Activities.Asset Sales/Purchases"/* NEGATE */,
                                                                "Investing Activities.Equipment Sales/Purchases"/* NEGATE */,
                                                                "Financing Activities.Capital/Stock",
                                                                "Financing Activities.Long Term Debts",
                                                                "Financing Activities.Short Term Debts",
                                                                "Financing Activities.Equity Securities",
                                                                "Financing Activities.Dividends Declared"/* NEGATE */,
                                                                "");

                                                            for ($z = 0; $z < count($valuesArrys); $z++) {
                                                                if ($sgValAcntClsfctn == $valuesArrys[$z]) {
                                                                    $valslctdArry[$z] = "selected";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <span><?php echo $sgValAcntClsfctn; ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValCtrlAcnt" class="control-label col-lg-4">Control Account:</label>
                                                <div  class="col-lg-8">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="sgValCtrlAcnt" name="sgValCtrlAcnt" value="<?php echo $sgValCtrlAcnt; ?>" readonly="true">
                                                            <input type="hidden" class="form-control" aria-label="..." id="sgValCtrlAcntID" name="sgValCtrlAcntID" value="<?php echo $sgValCtrlAcntID; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Control Account Segment Values', 'sbmtdSegmentID', '', '', 'radio', true, '<?php echo $sgValCtrlAcntID; ?>', 'sgValCtrlAcntID', 'sgValCtrlAcnt', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo $sgValCtrlAcnt; ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                <label for="sgValMppdAcnt" class="control-label col-lg-4">Mapped Group Account:</label>
                                                <div  class="col-lg-8">
                                                    <?php if ($canEdtOrg === true) { ?>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="sgValMppdAcnt" name="sgValMppdAcnt" value="<?php echo $sgValMppdAcnt; ?>" readonly="true">
                                                            <input type="hidden" class="form-control" aria-label="..." id="sgValMppdAcntID" name="sgValMppdAcntID" value="<?php echo $sgValMppdAcntID; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'sbmtdGrpOrgID', '', '', 'radio', true, '<?php echo $sgValMppdAcntID; ?>', 'sgValMppdAcntID', 'sgValMppdAcnt', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    <?php } else {
                                                        ?>
                                                        <span><?php echo $sgValMppdAcnt; ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <?php
                                    $nwRowHtml = urlencode("<tr id=\"allSgmntClsfctnsRow__WWW123WWW\" class=\"hand_cursor\">
                                                                                <td class=\"lovtd\">New
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allSgmntClsfctnsRow_WWW123WWW_ClsfctnID\" value=\"-1\">
                                                                                </td>
                                                                                <td class=\"lovtd\">
                                                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allSgmntClsfctnsRow_WWW123WWW_MajClsfctn\" value=\"\">
                                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '', '', 'allSgmntClsfctnsRow_WWW123WWW_MajClsfctn', 'clear', 0, '');\">
                                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                </td>
                                                                                <td class=\"lovtd\">
                                                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allSgmntClsfctnsRow_WWW123WWW_MinClsfctn\" value=\"\">
                                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '', '', 'allSgmntClsfctnsRow_WWW123WWW_MinClsfctn', 'clear', 0, '');\">
                                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                </td>
                                                                                  <td class=\"lovtd\">
                                                                                      <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delSgValRptClsfctn('allSgmntClsfctnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Report Classification\">
                                                                                          <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                      </button>
                                                                                  </td>
                                                                                  <td class=\"lovtd\">&nbsp;</td>
                                                                            </tr>");
                                    ?>
                                    <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <fieldset class="basic_person_fs">
                                            <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allSgmntClsfctnsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    New Report Classification
                                                </button>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveSgmntValForm();" style="width:100% !important;">
                                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    Save
                                                </button>
                                            </div>
                                            <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <table class="table table-striped table-bordered table-responsive" id="allSgmntClsfctnsTable" cellspacing="0" width="100%" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>Main Reporting Category</th>
                                                            <th>Sub-Reporting Category</th>
                                                            <th>...</th>
                                                            <th>...</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $resultClsfctn = get_One_RptClsfctns($segmentValueID);
                                                        $cntr = 0;
                                                        $curIdx = 0;
                                                        $lmtSze = 100;
                                                        while ($row1 = loc_db_fetch_array($resultClsfctn)) {
                                                            $cntr += 1;
                                                            ?>
                                                            <tr id="allSgmntClsfctnsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?>
                                                                    <input type="hidden" class="form-control" aria-label="..." id="allSgmntClsfctnsRow<?php echo $cntr; ?>_ClsfctnID" value="<?php echo $row1[0]; ?>">
                                                                </td>
                                                                <td class="lovtd">
                                                                    <?php
                                                                    if ($canEdtOrg === true) {
                                                                        ?>
                                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                                            <div class="input-group"  style="width:100%;">
                                                                                <input type="text" class="form-control" aria-label="..." id="allSgmntClsfctnsRow<?php echo $cntr; ?>_MajClsfctn" value="<?php echo $row1[1]; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '<?php echo $row1[1]; ?>', '', 'allSgmntClsfctnsRow<?php echo $cntr; ?>_MajClsfctn', 'clear', 0, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <span class=""><?php echo $row1[1]; ?></span>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="lovtd">
                                                                    <?php
                                                                    if ($canEdtOrg === true) {
                                                                        ?>
                                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                                            <div class="input-group"  style="width:100%;">
                                                                                <input type="text" class="form-control" aria-label="..." id="allSgmntClsfctnsRow<?php echo $cntr; ?>_MinClsfctn" value="<?php echo $row1[2]; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '<?php echo $row1[2]; ?>', '', 'allSgmntClsfctnsRow<?php echo $cntr; ?>_MinClsfctn', 'clear', 0, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <span class=""><?php echo $row1[2]; ?></span>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="lovtd">
                                                                    <?php
                                                                    if ($canDelRec === true) {
                                                                        ?>
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSgValRptClsfctn('allSgmntClsfctnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Report Classifications">
                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="lovtd">
                                                                    <?php
                                                                    if ($canVwRcHstry === true) {
                                                                        ?>
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                        echo urlencode(encrypt1(($row1[0] . "|org.org_account_clsfctns|account_clsfctn_id"),
                                                                                        $smplTokenWord1));
                                                                        ?>');" style="padding:2px !important;">
                                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <span>No Results Found</span>
                    <?php
                }
            } else if ($vwtyp == 10) {
                //New Form Segment Values
                $canDelRec = test_prmssns($dfltPrvldgs[15], $mdlNm);
                $sbmtdSegmentID = isset($_POST['sbmtdSegmentID']) ? $_POST['sbmtdSegmentID'] : -1;
                $res = get_SgmntInfo($sbmtdSegmentID);
                $sgValOrgDetOrgID = $orgID;
                $sbmtdSegmentClsfctn = "";
                $dpndntSegmentID = -1;
                while ($rw = loc_db_fetch_array($res)) {
                    $sgValOrgDetOrgID = (int) $rw[0];
                    $sbmtdSegmentClsfctn = $rw[2];
                    $dpndntSegmentID = (int) $rw[1];
                }
                $sbmtdSegValID = -1;
                $pkID = $sbmtdSegValID;
                $segmentValueID = -1;
                $segmentValue = "";
                $segmentValueDesc = "";
                $prntSegmentValueID = -1;
                $prntSegmentValue = "";
                $dpndntSegmentValueID = -1;
                $dpndntSegmentValue = "";
                $prntSegmentValueN = "";
                $dpndntSegmentValueN = "";
                $sgValAllwdGrpType = "Everyone";
                $sgValAllwdGrpID = "-1";
                $sgValAllwdGrpValue = "Everyone";
                $sgValIsEnabled = "1";
                $sgValCmbntnsAllwd = "1";
                $sgValIsPrntAcnt = "0";
                $sgValIsContraAcnt = "0";
                $sgValIsRetErngsAcnt = "0";
                $sgValIsNetIncmAcnt = "0";
                $sgValIsSuspnsAcnt = "0";
                $sgValHsSubldgrAcnt = "0";
                $sgValAcntType = "";
                $sgValAcntClsfctn = "";
                $sgValCtrlAcntID = -1;
                $sgValCtrlAcnt = "";
                $sgValMppdAcntID = -1;
                $sgValMppdAcnt = "";
                ?>
                <div id="orgDetPage" class="tab-pane fadein active" style="border:none !important;">
                    <div class="row">
                        <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="segmentValue" class="control-label col-lg-4">Segment Value:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtOrg === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="segmentValue" name="segmentValue" value="<?php echo $segmentValue; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $segmentValue; ?></span>
                                                <?php
                                            }
                                            ?>
                                            <input type="hidden" class="form-control" aria-label="..." id="sgValOrgDetOrgID" name="sgValOrgDetOrgID" value="<?php echo $sgValOrgDetOrgID; ?>">
                                            <input type="hidden" class="form-control" aria-label="..." id="segmentValueID" name="segmentValueID" value="<?php echo $segmentValueID; ?>">
                                            <input type="hidden" class="form-control" aria-label="..." id="sbmtdSegmentID" name="sbmtdSegmentID" value="<?php echo $sbmtdSegmentID; ?>">
                                            <input type="hidden" class="form-control" aria-label="..." id="sbmtdSegmentClsfctn" name="sbmtdSegmentClsfctn" value="<?php echo $sbmtdSegmentClsfctn; ?>">
                                            <input type="hidden" class="form-control" aria-label="..." id="dpndntSegmentID" name="dpndntSegmentID" value="<?php echo $dpndntSegmentID; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="segmentValueDesc" class="control-label col-lg-4">Value Description:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtOrg === true) { ?>
                                                <textarea class="form-control" aria-label="..." id="segmentValueDesc" name="segmentValueDesc" style="width:100%;" cols="3" rows="2"><?php echo $segmentValueDesc; ?></textarea>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $segmentValueDesc; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="prntSegmentValue" class="control-label col-lg-4">Parent Segment:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtOrg === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="prntSegmentValue" name="prntSegmentValue" value="<?php echo $prntSegmentValue; ?>" readonly="true">
                                                    <input type="hidden" class="form-control" aria-label="..." id="prntSegmentValueID" name="prntSegmentValueID" value="<?php echo $prntSegmentValueN; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Segment Values', 'sbmtdSegmentID', '', '', 'radio', true, '<?php echo $prntSegmentValueN; ?>', 'prntSegmentValueID', 'prntSegmentValue', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $prntSegmentValue; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="dpndntSegmentValue" class="control-label col-lg-4">Dependent Segment:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtOrg === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="dpndntSegmentValue" name="dpndntSegmentValue" value="<?php echo $dpndntSegmentValue; ?>" readonly="true">
                                                    <input type="hidden" class="form-control" aria-label="..." id="dpndntSegmentValueID" name="dpndntSegmentValueID" value="<?php echo $dpndntSegmentValueN; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Segment Values', 'dpndntSegmentID', '', '', 'radio', true, '<?php echo $dpndntSegmentValueN; ?>', 'dpndntSegmentValueID', 'dpndntSegmentValue', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $dpndntSegmentValue; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValAllwdGrpType" class="control-label col-lg-4">Allowed Group Type:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtOrg === true) { ?>
                                                <select class="form-control" id="sgValAllwdGrpType" onchange="grpTypOrgChange('sgValAllwdGrpType', 'sgValAllwdGrpValue', 'sgValAllwdGrpID', 'sgValAllwdGrpNmLbl');">
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                                    $valuesArrys = array("Everyone", "Divisions/Groups",
                                                        "Grade", "Job", "Position", "Site/Location", "Person Type", "Single Person");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($sgValAllwdGrpType == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $sgValAllwdGrpType; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValAllwdGrpValue" class="control-label col-lg-4">Allowed Group Name:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtOrg === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="sgValAllwdGrpValue" value="<?php echo $sgValAllwdGrpValue; ?>" readonly="true">
                                                    <input type="hidden" id="sgValAllwdGrpID" value="<?php echo $sgValAllwdGrpID; ?>">
                                                    <label disabled="true" id="sgValAllwdGrpNmLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovsTblr('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'orgDetOrgID', '', '', 'radio', true, '', 'sgValAllwdGrpID', 'sgValAllwdGrpValue', 'clear', 1, '', 'sgValAllwdGrpType');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $sgValAllwdGrpValue; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValCmbntnsAllwd" class="control-label col-lg-4">Combinations Allowed?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($sgValCmbntnsAllwd == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdtOrg === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="sgValCmbntnsAllwd" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="orgDetIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($sgValCmbntnsAllwd == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValIsEnabled" class="control-label col-lg-4">Is Enabled?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($sgValIsEnabled == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdtOrg === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="sgValIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="sgValIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($sgValIsEnabled == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <?php if ($sbmtdSegmentClsfctn == "NaturalAccount") { ?> 
                        <div class="row">
                            <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValIsPrntAcnt" class="control-label col-lg-4">Parent Account?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($sgValIsPrntAcnt == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdtOrg === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="sgValIsPrntAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="sgValIsPrntAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($sgValIsPrntAcnt == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValIsContraAcnt" class="control-label col-lg-4">Contra Account?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($sgValIsContraAcnt == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdtOrg === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="sgValIsContraAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="sgValIsContraAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($sgValIsContraAcnt == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValIsRetErngsAcnt" class="control-label col-lg-4">Retained Earnings A/c?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($sgValIsRetErngsAcnt == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdtOrg === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="sgValIsRetErngsAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="sgValIsRetErngsAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($sgValIsRetErngsAcnt == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValIsNetIncmAcnt" class="control-label col-lg-4">Net Income Account?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($sgValIsNetIncmAcnt == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdtOrg === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="sgValIsNetIncmAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="sgValIsNetIncmAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($sgValIsNetIncmAcnt == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValIsSuspnsAcnt" class="control-label col-lg-4">Suspense Account?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($sgValIsSuspnsAcnt == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdtOrg === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="sgValIsSuspnsAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="sgValIsSuspnsAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($sgValIsSuspnsAcnt == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValHsSubldgrAcnt" class="control-label col-lg-4">Has Subledger Accounts?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($sgValHsSubldgrAcnt == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdtOrg === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="sgValHsSubldgrAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="sgValHsSubldgrAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($sgValHsSubldgrAcnt == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValAcntType" class="control-label col-lg-4">Account Type:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtOrg === true) { ?>
                                                <select class="form-control" id="sgValAcntType" onchange="">
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "");
                                                    $valuesArrys = array("A -ASSET", "EQ-EQUITY", "L -LIABILITY", "R -REVENUE", "EX-EXPENSE");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($sgValAcntType == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $sgValAcntType; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValAcntClsfctn" class="control-label col-lg-4">Classification:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtOrg === true) { ?>
                                                <select class="form-control" id="sgValAcntClsfctn" onchange="">
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "", "",
                                                        "", "", "", "", "", "", "", "",
                                                        "", "", "", "", "", "", "", "",
                                                        "", "", "", "");
                                                    $valuesArrys = array("Cash and Cash Equivalents",
                                                        "Operating Activities.Sale of Goods",
                                                        "Operating Activities.Sale of Services",
                                                        "Operating Activities.Other Income Sources",
                                                        "Operating Activities.Cost of Sales",
                                                        "Operating Activities.Net Income",
                                                        "Operating Activities.Depreciation Expense",
                                                        "Operating Activities.Amortization Expense",
                                                        "Operating Activities.Gain on Sale of Asset"/* NEGATE */,
                                                        "Operating Activities.Loss on Sale of Asset",
                                                        "Operating Activities.Other Non-Cash Expense",
                                                        "Operating Activities.Accounts Receivable"/* NEGATE */,
                                                        "Operating Activities.Bad Debt Expense"/* NEGATE */,
                                                        "Operating Activities.Prepaid Expenses"/* NEGATE */,
                                                        "Operating Activities.Inventory"/* NEGATE */,
                                                        "Operating Activities.Accounts Payable",
                                                        "Operating Activities.Accrued Expenses",
                                                        "Operating Activities.Taxes Payable",
                                                        "Operating Activities.Operating Expense"/* NEGATE */,
                                                        "Operating Activities.General and Administrative Expense"/* NEGATE */,
                                                        "Investing Activities.Asset Sales/Purchases"/* NEGATE */,
                                                        "Investing Activities.Equipment Sales/Purchases"/* NEGATE */,
                                                        "Financing Activities.Capital/Stock",
                                                        "Financing Activities.Long Term Debts",
                                                        "Financing Activities.Short Term Debts",
                                                        "Financing Activities.Equity Securities",
                                                        "Financing Activities.Dividends Declared"/* NEGATE */,
                                                        "");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($sgValAcntClsfctn == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $sgValAcntClsfctn; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValCtrlAcnt" class="control-label col-lg-4">Control Account:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtOrg === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="sgValCtrlAcnt" name="sgValCtrlAcnt" value="<?php echo $sgValCtrlAcnt; ?>" readonly="true">
                                                    <input type="hidden" class="form-control" aria-label="..." id="sgValCtrlAcntID" name="sgValCtrlAcntID" value="<?php echo $sgValCtrlAcntID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Control Account Segment Values', 'sbmtdSegmentID', '', '', 'radio', true, '<?php echo $sgValCtrlAcntID; ?>', 'sgValCtrlAcntID', 'sgValCtrlAcnt', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $sgValCtrlAcnt; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <label for="sgValMppdAcnt" class="control-label col-lg-4">Mapped Group Account:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtOrg === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="sgValMppdAcnt" name="sgValMppdAcnt" value="<?php echo $sgValMppdAcnt; ?>" readonly="true">
                                                    <input type="hidden" class="form-control" aria-label="..." id="sgValMppdAcntID" name="sgValMppdAcntID" value="<?php echo $sgValMppdAcntID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'sbmtdGrpOrgID', '', '', 'radio', true, '<?php echo $sgValMppdAcntID; ?>', 'sgValMppdAcntID', 'sgValMppdAcnt', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $sgValMppdAcnt; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"allSgmntClsfctnsRow__WWW123WWW\" class=\"hand_cursor\">
                                                                                <td class=\"lovtd\">New
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allSgmntClsfctnsRow_WWW123WWW_ClsfctnID\" value=\"-1\">
                                                                                </td>
                                                                                <td class=\"lovtd\">
                                                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allSgmntClsfctnsRow_WWW123WWW_MajClsfctn\" value=\"\">
                                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '', '', 'allSgmntClsfctnsRow_WWW123WWW_MajClsfctn', 'clear', 0, '');\">
                                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                </td>
                                                                                <td class=\"lovtd\">
                                                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allSgmntClsfctnsRow_WWW123WWW_MinClsfctn\" value=\"\">
                                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '', '', 'allSgmntClsfctnsRow_WWW123WWW_MinClsfctn', 'clear', 0, '');\">
                                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                </td>
                                                                                  <td class=\"lovtd\">
                                                                                      <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delSgValRptClsfctn('allSgmntClsfctnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Report Classification\">
                                                                                          <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                      </button>
                                                                                  </td>
                                                                                  <td class=\"lovtd\">&nbsp;</td>
                                                                            </tr>");
                            ?>
                            <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs">
                                    <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allSgmntClsfctnsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Report Classification
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveSgmntValForm();" style="width:100% !important;">
                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Save
                                        </button>
                                    </div>
                                    <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <table class="table table-striped table-bordered table-responsive" id="allSgmntClsfctnsTable" cellspacing="0" width="100%" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Main Reporting Category</th>
                                                    <th>Sub-Reporting Category</th>
                                                    <th>...</th>
                                                    <th>...</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $resultClsfctn = get_One_RptClsfctns($segmentValueID);
                                                $cntr = 0;
                                                $curIdx = 0;
                                                $lmtSze = 100;
                                                while ($row1 = loc_db_fetch_array($resultClsfctn)) {
                                                    $cntr += 1;
                                                    ?>
                                                    <tr id="allSgmntClsfctnsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?>
                                                            <input type="hidden" class="form-control" aria-label="..." id="allSgmntClsfctnsRow<?php echo $cntr; ?>_ClsfctnID" value="<?php echo $row1[0]; ?>">
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrg === true) { ?>
                                                                <div class="form-group form-group-sm" style="width:100% !important;">
                                                                    <div class="input-group"  style="width:100%;">
                                                                        <input type="text" class="form-control" aria-label="..." id="allSgmntClsfctnsRow<?php echo $cntr; ?>_MajClsfctn" value="<?php echo $row1[1]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '<?php echo $row1[1]; ?>', '', 'allSgmntClsfctnsRow<?php echo $cntr; ?>_MajClsfctn', 'clear', 0, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span class=""><?php echo $row1[1]; ?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrg === true) { ?>
                                                                <div class="form-group form-group-sm" style="width:100% !important;">
                                                                    <div class="input-group"  style="width:100%;">
                                                                        <input type="text" class="form-control" aria-label="..." id="allSgmntClsfctnsRow<?php echo $cntr; ?>_MinClsfctn" value="<?php echo $row1[2]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '<?php echo $row1[2]; ?>', '', 'allSgmntClsfctnsRow<?php echo $cntr; ?>_MinClsfctn', 'clear', 0, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span class=""><?php echo $row1[2]; ?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canDelRec === true) { ?>
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSgValRptClsfctn('allSgmntClsfctnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Report Classifications">
                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canVwRcHstry === true) { ?>
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                echo urlencode(encrypt1(($row1[0] . "|org.org_account_clsfctns|account_clsfctn_id"),
                                                                                $smplTokenWord1));
                                                                ?>');" style="padding:2px !important;">
                                                                    <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php
            }
        }
    }
}
