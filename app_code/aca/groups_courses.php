<?php
$canAdd = test_prmssns($dfltPrvldgs[25], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[26], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[27], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
$exprtFileNmPrt = "AssessGroups";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Assessment Group */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo delete_AcaClasses($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Class Course */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo delete_AcaClasseCrses($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Course Subject */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo delete_AcaClasseSbjcts($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Assessment Group
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $acaClassesID = isset($_POST['acaClassesID']) ? (int) cleanInputData($_POST['acaClassesID']) : -1;
                $acaClassesLnkdLnkdDivID = isset($_POST['acaClassesLnkdLnkdDivID']) ? (int) cleanInputData($_POST['acaClassesLnkdLnkdDivID']) : -1;
                $acaClassesNxtClassID = isset($_POST['acaClassesNxtClassID']) ? (int) cleanInputData($_POST['acaClassesNxtClassID']) : -1;
                $acaClassesName = isset($_POST['acaClassesName']) ? cleanInputData($_POST['acaClassesName']) : "";
                $acaClassesDesc = isset($_POST['acaClassesDesc']) ? cleanInputData($_POST['acaClassesDesc']) : "";
                $acaClassesType = isset($_POST['acaClassesType']) ? cleanInputData($_POST['acaClassesType']) : "";
                $acaClassesGrpFcltrPosNm = isset($_POST['acaClassesGrpFcltrPosNm']) ? cleanInputData($_POST['acaClassesGrpFcltrPosNm']) : "";
                $acaClassesGrpRepPosNm = isset($_POST['acaClassesGrpRepPosNm']) ? cleanInputData($_POST['acaClassesGrpRepPosNm']) : "";
                $acaClassesSbjctFcltrPosNm = isset($_POST['acaClassesSbjctFcltrPosNm']) ? cleanInputData($_POST['acaClassesSbjctFcltrPosNm']) : "";
                $acaClassesIsEnbld = isset($_POST['acaClassesIsEnbld']) ? (cleanInputData($_POST['acaClassesIsEnbld']) == "YES" ? TRUE : FALSE) : FALSE;
                $lnkdAssessID = -1;
                $exitErrMsg = "";
                if ($acaClassesName == "") {
                    $exitErrMsg .= "Please enter Assessment Group Name!<br/>";
                }
                if ($acaClassesType == "") {
                    $exitErrMsg .= "Please enter Assessment Group Type!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['acaClassesID'] = $acaClassesID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldID = get_AcaClassesID($acaClassesName, $orgID);
                //echo "OLD:" . $oldID . "::" . $acaClassesID;
                if (($oldID <= 0 || $oldID == $acaClassesID)) {
                    if ($acaClassesID <= 0) {
                        $acaClassesID = getNew_AcaClassesID();
                        create_AcaClasses(
                            $acaClassesID,
                            $acaClassesName,
                            $acaClassesDesc,
                            $acaClassesType,
                            $acaClassesLnkdLnkdDivID,
                            $acaClassesIsEnbld,
                            $lnkdAssessID,
                            $acaClassesGrpFcltrPosNm,
                            $acaClassesGrpRepPosNm,
                            $acaClassesSbjctFcltrPosNm,
                            $acaClassesNxtClassID
                        );
                    } else {
                        update_AcaClasses(
                            $acaClassesID,
                            $acaClassesName,
                            $acaClassesDesc,
                            $acaClassesType,
                            $acaClassesLnkdLnkdDivID,
                            $acaClassesIsEnbld,
                            $lnkdAssessID,
                            $acaClassesGrpFcltrPosNm,
                            $acaClassesGrpRepPosNm,
                            $acaClassesSbjctFcltrPosNm,
                            $acaClassesNxtClassID
                        );
                    }

                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Assessment Group Successfully Saved!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Assessment Group Successfully Saved!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['acaClassesID'] = $acaClassesID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Assessment Group Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['acaClassesID'] = $acaClassesID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 1001) {
                //Export
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array(
                        "Group Name**", "Group Description", "Group Type**", "Next Level Group Name",
                        "Group Facilitator Pos. Name", "Group Rep. Pos. Name", "Subject/Target Facilitator Pos. Name",
                        "ENABLED?", "Programme/Objective Code**", "Programme/Objective Name", "Min Weight/Credit Hrs", "Max Weight/Credit Hrs",
                        "Subject/Target Code", "Subject/Target Name", "Core/Elective", "Weight/Credit Hours", "Period Type",
                        "Period Number"
                    );
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/" . $exprtFileNmPrt . "Exprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/" . $exprtFileNmPrt . "Exprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                            json_encode($arr_content)
                        );
                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_AcaClassesExprt(0, $limit_size, "%", "Name");
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        //"" . ($z + 1), 
                        $crntRw = array(
                            $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17]
                        );
                        fputcsv($opndfile, $crntRw);
                        //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                1) . " out of " . $total . " Record(s) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Records...Please Wait..." . ($z +
                                1) . " out of " . $total . " Record(s) exported.</span>";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                            json_encode($arr_content)
                        );
                        $z++;
                    }
                    fclose($opndfile);
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents(
                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                        json_encode($arr_content)
                    );
                }
            } else if ($actyp == 1002) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => 0, "message" => '<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Not Started</span>'));
                }
            } else if ($actyp == 11) {
                //Assessment Courses
                /* Course/Programme/Objective */
                $classCoursePkeyID = isset($_POST['classCoursePkeyID']) ? cleanInputData($_POST['classCoursePkeyID']) : -1;
                $srcForm = isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
                $sbmtdClassID = isset($_POST['sbmtdClassID']) ? cleanInputData($_POST['sbmtdClassID']) : -1;

                $classCrseID = isset($_POST['classCrseID']) ? cleanInputData($_POST['classCrseID']) : -1;
                $classCrseCode = isset($_POST['classCrseCode']) ? cleanInputData($_POST['classCrseCode']) : "";
                $classCrseName = isset($_POST['classCrseName']) ? cleanInputData($_POST['classCrseName']) : "";
                $classCrseIsEnbld = isset($_POST['classCrseIsEnbld']) ? cleanInputData($_POST['classCrseIsEnbld']) : "NO";
                $classCrseIsEnbld1 = isset($_POST['classCrseIsEnbld']) ? (cleanInputData($_POST['classCrseIsEnbld']) == "YES" ? TRUE : FALSE) : FALSE;
                $classCrseMinWeight = isset($_POST['classCrseMinWeight']) ? cleanInputData($_POST['classCrseMinWeight']) : "";
                $classCrseMaxWeight = isset($_POST['classCrseMaxWeight']) ? cleanInputData($_POST['classCrseMaxWeight']) : "";
                $clssCrseLnNm = str_replace("." . $classCrseCode, "", $classCrseCode . "." . $classCrseName);
                $cntrRndm = getRandomNum(5000, 9999);
                $affctRws = 0;
                if ($sbmtdClassID > 0 && $classCrseCode != "" && $classCrseName != "") {
                    if ($classCrseID <= 0) {
                        $classCrseID = getNew_AcaCoursesID();
                        create_AcaCourses($classCrseID, $moduleType, $classCrseCode, $classCrseName, $classCrseName, true);
                    } else if ($classCrseID > 0) {
                        update_AcaCourses1($classCrseID, $moduleType, $classCrseCode, $classCrseName);
                    }
                    $oldPKey = get_AcaClasseCrseID($classCrseID, $sbmtdClassID);
                    if ($classCoursePkeyID > 0 && ($oldPKey == $classCoursePkeyID || $oldPKey <= 0)) {
                        $affctRws += update_AcaClasseCrses($classCoursePkeyID, $sbmtdClassID, $classCrseID, $classCrseIsEnbld1, $classCrseMinWeight, $classCrseMaxWeight);
                    } else if ($classCoursePkeyID <= 0 && $oldPKey <= 0) {
                        $classCoursePkeyID = getNew_AcaClasseCrsesID();
                        $affctRws += create_AcaClasseCrses($classCoursePkeyID, $sbmtdClassID, $classCrseID, $classCrseIsEnbld1, $classCrseMinWeight, $classCrseMaxWeight);
                    }
                    if ($affctRws > 0) {
                        $cntr = $cntrRndm;
?>
                        <tr id="oneAcaClassesCrsesRow_<?php echo $cntr; ?>">
                            <td class="lovtd">
                                <span>&nbsp;</span>
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_CrseLnID" value="<?php echo $classCoursePkeyID; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_CrseID" value="<?php echo $classCrseID; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_CrseCode" value="<?php echo $classCrseCode; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_CrseName" value="<?php echo $classCrseName; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_IsEnbld" value="<?php echo $classCrseIsEnbld; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_LineName" value="<?php echo $clssCrseLnNm; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_MinWeight" value="<?php echo $classCrseMinWeight; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_MaxWeight" value="<?php echo $classCrseMaxWeight; ?>" style="width:100% !important;">
                            </td>
                            <td class="lovtd" style="">
                                <span><?php echo $clssCrseLnNm; ?></span>
                            </td>
                            <td class="lovtd" style="">
                                <span>&nbsp;</span>
                            </td>
                            <td class="lovtd" style="">
                                <span>&nbsp;</span>
                            </td>
                            <td class="lovtd" style="text-align: center;">
                                <button type="button" class="btn btn-default btn-sm" id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_CrsesBtn" onclick="getClassCrseForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'acaEdtCourseForm', 'oneAcaClassesCrsesRow_<?php echo $cntr; ?>', 'Edit <?php echo $courseLabel; ?>', 20, 'EDIT', <?php echo $classCoursePkeyID; ?>, <?php echo $sbmtdClassID; ?>);" style="padding:2px !important;" title="Edit <?php echo $courseLabel; ?>">
                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                            <td class="lovtd" style="text-align: center;">
                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaClassesLne('oneAcaClassesCrsesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete <?php echo $courseLabel; ?>">
                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                        </tr>
                    <?php
                    } else {
                        echo "Error:Failed to Save any Data!";
                    }
                } else {
                    echo "Error:Invalid or Incomplete Data!";
                }
            } else if ($actyp == 12) {
                //Assessment Courses
                /* Course/Programme/Objective */
                $crseSbjctPkeyID = isset($_POST['crseSbjctPkeyID']) ? cleanInputData($_POST['crseSbjctPkeyID']) : -1;
                $srcForm = isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
                $sbmtdClassID = isset($_POST['sbmtdClassID']) ? cleanInputData($_POST['sbmtdClassID']) : -1;
                $sbmtdCrseID = isset($_POST['sbmtdCrseID']) ? cleanInputData($_POST['sbmtdCrseID']) : -1;

                $crseSbjctID = isset($_POST['crseSbjctID']) ? cleanInputData($_POST['crseSbjctID']) : -1;
                $crseSbjctCode = isset($_POST['crseSbjctCode']) ? cleanInputData($_POST['crseSbjctCode']) : "";
                $crseSbjctName = isset($_POST['crseSbjctName']) ? cleanInputData($_POST['crseSbjctName']) : "";
                $crseSbjctIsEnbld = isset($_POST['crseSbjctIsEnbld']) ? cleanInputData($_POST['crseSbjctIsEnbld']) : "NO";
                $crseSbjctIsEnbld1 = isset($_POST['crseSbjctIsEnbld']) ? (cleanInputData($_POST['crseSbjctIsEnbld']) == "YES" ? TRUE : FALSE) : FALSE;
                $crseSbjct_Weight = isset($_POST['crseSbjct_Weight']) ? (int) cleanInputData($_POST['crseSbjct_Weight']) : 0;
                $crseSbjct_Type = isset($_POST['crseSbjct_Type']) ? cleanInputData($_POST['crseSbjct_Type']) : "";
                $crseSbjct_PeriodNum = isset($_POST['crseSbjct_PeriodNum']) ? (float) cleanInputData($_POST['crseSbjct_PeriodNum']) : 0;
                $crseSbjct_PeriodType = isset($_POST['crseSbjct_PeriodType']) ? cleanInputData($_POST['crseSbjct_PeriodType']) : "";
                $crseSbjctLnNm = str_replace("." . $crseSbjctCode, "", $crseSbjctCode . "." . $crseSbjctName);
                $cntrRndm = getRandomNum(5000, 9999);
                $affctRws = 0;
                if ($sbmtdClassID > 0 && $crseSbjctCode != "" && $crseSbjctName != "") {
                    if ($crseSbjctID <= 0) {
                        $crseSbjctID = getNew_AcaSbjctsID();
                        create_AcaSbjcts($crseSbjctID, $moduleType2, $crseSbjctCode, $crseSbjctName, $crseSbjctName, true);
                    } else if ($crseSbjctID > 0) {
                        update_AcaSbjcts1($crseSbjctID, $moduleType2, $crseSbjctCode, $crseSbjctName);
                    }
                    $oldPKey = get_AcaClasseSbjctID($crseSbjctID, $sbmtdCrseID, $sbmtdClassID);
                    if ($crseSbjctPkeyID > 0 && ($oldPKey == $crseSbjctPkeyID || $oldPKey <= 0)) {
                        $affctRws += update_AcaClasseSbjcts($crseSbjctPkeyID, $sbmtdCrseID, $crseSbjctID, $sbmtdClassID, $crseSbjct_Type, $crseSbjct_Weight, $crseSbjctIsEnbld1, $crseSbjct_PeriodType, $crseSbjct_PeriodNum);
                    } else if ($crseSbjctPkeyID <= 0 && $oldPKey <= 0) {
                        $crseSbjctPkeyID = getNew_AcaClasseSbjctID();
                        $affctRws += create_AcaClasseSbjcts($crseSbjctPkeyID, $sbmtdCrseID, $crseSbjctID, $sbmtdClassID, $crseSbjct_Type, $crseSbjct_Weight, $crseSbjctIsEnbld1, $crseSbjct_PeriodType, $crseSbjct_PeriodNum);
                    }
                    if ($affctRws > 0) {
                        $cntr = $cntrRndm;
                    ?>
                        <tr id="oneAcaClassesSbjctsRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAcaClassesSbjctsTable tr').index(this));">
                            <td class="lovtd"><span>New</span>
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_SbjctLnID" value="<?php echo $crseSbjctPkeyID; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_SbjctID" value="<?php echo $crseSbjctID; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_SbjctCode" value="<?php echo $crseSbjctCode; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_SbjctNm" value="<?php echo $crseSbjctName; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_IsEnbld" value="<?php echo $crseSbjctIsEnbld; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_SbjctType" value="<?php echo $crseSbjct_Type; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_Weight" value="<?php echo $crseSbjct_Weight; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_PrdTyp" value="<?php echo $crseSbjct_PeriodType; ?>" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_PrdNum" value="<?php echo $crseSbjct_PeriodNum; ?>" style="width:100% !important;">
                            </td>
                            <td class="lovtd" style="">
                                <span><?php echo $crseSbjctLnNm; ?></span>
                            </td>
                            <td class="lovtd">
                                <span><?php echo $crseSbjct_Type; ?></span>
                            </td>
                            <td class="lovtd">
                                <span><?php echo $crseSbjct_Weight; ?></span>
                            </td>
                            <td class="lovtd">
                                <span><?php echo $crseSbjct_Type; ?></span>
                            </td>
                            <td class="lovtd">
                                <span><?php echo $crseSbjct_Weight; ?></span>
                            </td>
                            <td class="lovtd">
                                <span><?php echo $crseSbjct_PeriodType; ?></span>
                            </td>
                            <td class="lovtd">
                                <span><?php echo $crseSbjct_PeriodNum; ?></span>
                            </td>
                            <td class="lovtd">
                                <span>&nbsp;</span>
                            </td>
                            <td class="lovtd" style="text-align: center;">
                                <button type="button" class="btn btn-default btn-sm" id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_SbjctsBtn" onclick="getClassCrseSbjctForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'acaEdtCourseSbjctForm', 'oneAcaClassesSbjctsRow_<?php echo $cntrRndm; ?>', 'Edit <?php echo $courseLabel; ?>', 21, 'EDIT', <?php echo $crseSbjctPkeyID; ?>, <?php echo $sbmtdCrseID; ?>, <?php echo $sbmtdClassID; ?>);" style="padding:2px !important;" title="Edit <?php echo $sbjctLabel; ?>">
                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                            <td class="lovtd" style="text-align: center;">
                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaClassesLne('oneAcaClassesSbjctsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete <?php echo $sbjctLabel; ?>">
                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                        </tr>
                    <?php
                    } else {
                        echo "Error:Failed to Save any Data!";
                    }
                } else {
                    echo "Error:Invalid or Incomplete Data!";
                }
            }
        } else {
            if ($vwtyp == 0 || $vwtyp == 1 || $vwtyp == 2 || $vwtyp == 3) {
                $pkID = isset($_POST['sbmtdClassesID']) ? $_POST['sbmtdClassesID'] : -1;
                $actionTxt = isset($_POST['actionTxt']) ? $_POST['actionTxt'] : "PasteDirect";
                $destElmntID = isset($_POST['destElmntID']) ? $_POST['destElmntID'] : "acaClassesDetailInfo";
                $titleMsg = isset($_POST['titleMsg']) ? $_POST['titleMsg'] : "";
                $titleElementID = isset($_POST['titleElementID']) ? $_POST['titleElementID'] : "";
                $modalBodyID = isset($_POST['modalBodyID']) ? $_POST['modalBodyID'] : "";
                $sbmtdCrseLineID = isset($_POST['sbmtdCrseLineID']) ? $_POST['sbmtdCrseLineID'] : -1;
                if ($vwtyp == 0) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">$groupLabels / $courseLabels</span>
				</li>
                               </ul>
                              </div>";

                    $total = get_Total_AcaClasses($srchFor, $srchIn);

                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_AcaClasses($srchFor, $srchIn, $curIdx, $lmtSze);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='acaClassesForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php if ($canAdd === true) { ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 0px 0px 0px !important;">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAcaClassesForm(-1, 1);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Facility/Service Type">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Group/Class
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAcaClassesForm('<?php echo $actionTxt; ?>', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');" style="width:100% !important;">
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
                                    <input class="form-control" id="acaClassesSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                    echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                    ?>" onkeyup="enterKeyFuncAcaClasses(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="acaClassesPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaClasses('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaClasses('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaClassesSrchIn">
                                        <?php
                                        $valslctdArry = array("");
                                        $srchInsArrys = array("Description");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaClassesDsplySze" style="min-width:70px !important;">
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
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAcaClasses('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAcaClasses('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:1px 0px 3px 0px;">
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="padding:0px 1px 0px 15px !important;">
                                <fieldset class="basic_person_fs">
                                    <table class="table table-striped table-bordered table-responsive" id="acaClassesTable" cellspacing="0" width="100%" style="width:100% !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th><?php echo $groupLabel; ?> Name</th>
                                                <th>...</th>
                                                <?php if ($canVwRcHstry) { ?>
                                                    <th>...</th>
                                                <?php } ?>
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
                                                <tr id="acaClassesRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $row[1]; ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="acaClassesRow<?php echo $cntr; ?>_ClassesID" value="<?php echo $row[0]; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="acaClassesRow<?php echo $cntr; ?>_ClassesNm" value="<?php echo $row[1]; ?>">
                                                    </td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaClasses('acaClassesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Class/Group">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                    echo urlencode(encrypt1(($row[0] . "|aca.aca_classes|class_id"),
                                                                                                                                                                                                                        $smplTokenWord1
                                                                                                                                                                                                                    ));
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
                            <div class="col-md-9" style="padding:0px 15px 0px 1px !important">
                                <fieldset class="basic_person_fs" style="padding-top:2px !important;">
                                    <div class="container-fluid" id="acaClassesDetailInfo">
                                    <?php
                                }
                                $acaClassesID = -1;
                                $acaClassesName = "";
                                $acaClassesDesc = "";
                                $acaClassesType = "";
                                $acaClassesIsEnbld = "1";
                                $acaClassesLnkdLnkdDivID = -1;
                                $acaClassesGrpFcltrPosNm = "";
                                $acaClassesGrpRepPosNm = "";
                                $acaClassesSbjctFcltrPosNm = "";
                                $acaClassesNxtClassID = -1;
                                $acaClassesNxtClassNm = "";
                                if ($pkID > 0) {
                                    $acaClassesID = $pkID;
                                    $result1 = get_OneAcaClasseDet($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $acaClassesID = $row1[0];
                                        $acaClassesName = $row1[1];
                                        $acaClassesDesc = $row1[2];
                                        $acaClassesType = $row1[3];
                                        $acaClassesIsEnbld = $row1[4];
                                        $acaClassesLnkdLnkdDivID = (int) $row1[5];
                                        $acaClassesGrpFcltrPosNm = $row1[6];
                                        $acaClassesGrpRepPosNm = $row1[7];
                                        $acaClassesSbjctFcltrPosNm = $row1[8];
                                        $acaClassesNxtClassID = (int) $row1[9];
                                        $acaClassesNxtClassNm = $row1[10];
                                    }
                                }
                                if ($vwtyp != 2 && $vwtyp != 3) {
                                    ?>
                                        <div class="row">
                                            <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                <fieldset class="basic_person_fs123" style="padding-top:10px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaClassesName" class="control-label col-lg-4"><?php echo $groupLabel; ?> Name:</label>
                                                        <div class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <div class="input-group">
                                                                    <input class="form-control rqrdFld" id="acaClassesName" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" value="<?php echo $acaClassesName; ?>" />
                                                                    <input type="hidden" class="form-control" aria-label="..." id="acaClassesID" name="acaClassesID" value="<?php echo $acaClassesID; ?>">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="acaClassesLnkdLnkdDivID" name="acaClassesLnkdLnkdDivID" value="<?php echo $acaClassesLnkdLnkdDivID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Divisions/Groups', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaClassesLnkdLnkdDivID', 'acaClassesName', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else {
                                                            ?>
                                                                <span><?php echo $acaClassesName; ?></span>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaClassesDesc" class="control-label col-lg-4">Description:</label>
                                                        <div class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <textarea class="form-control" rows="2" cols="20" id="acaClassesDesc" name="acaClassesDesc" style="text-align:left !important;width:100% !important;"><?php echo $acaClassesDesc; ?></textarea>
                                                            <?php } else {
                                                            ?>
                                                                <span><?php echo $acaClassesDesc; ?></span>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaClassesType" class="control-label col-lg-4">Group Type:</label>
                                                        <div class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="acaClassesType" style="min-width:70px !important;width:100% !important;">
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Divisions or Group Types"), $isDynmyc, -1, "", "");
                                                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                        $selectedTxt = "";
                                                                        if ($titleRow[0] == $acaClassesType) {
                                                                            $selectedTxt = "selected";
                                                                        }
                                                                    ?>
                                                                        <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <span><?php echo $acaClassesType; ?></span>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaClassesNxtClassNm" class="control-label col-lg-4">Next Level <?php echo $groupLabel; ?> Name:</label>
                                                        <div class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <div class="input-group">
                                                                    <input class="form-control" id="acaClassesNxtClassNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" value="<?php echo $acaClassesNxtClassNm; ?>" readonly="true" />
                                                                    <input type="hidden" class="form-control" aria-label="..." id="acaClassesNxtClassID" name="acaClassesNxtClassID" value="<?php echo $acaClassesNxtClassID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Groups', 'allOtherInputOrgID', 'acaClassesType', '', 'radio', true, '', 'acaClassesNxtClassID', 'acaClassesNxtClassNm', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else {
                                                            ?>
                                                                <span><?php echo $acaClassesNxtClassNm; ?></span>
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
                                                        <label for="acaClassesGrpFcltrPosNm" class="control-label col-lg-4"><?php echo $groupLabel; ?> Facilitator Pos. Name:</label>
                                                        <div class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <div class="input-group">
                                                                    <input class="form-control" id="acaClassesGrpFcltrPosNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" value="<?php echo $acaClassesGrpFcltrPosNm; ?>" />
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Positions', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaClassesGrpFcltrPosID', 'acaClassesGrpFcltrPosNm', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else {
                                                            ?>
                                                                <span><?php echo $acaClassesGrpFcltrPosNm; ?></span>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaClassesGrpRepPosNm" class="control-label col-lg-4"><?php echo $groupLabel; ?> Rep. Pos. Name:</label>
                                                        <div class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <div class="input-group">
                                                                    <input class="form-control" id="acaClassesGrpRepPosNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" value="<?php echo $acaClassesGrpRepPosNm; ?>" />
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Positions', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaClassesGrpRepPosID', 'acaClassesGrpRepPosNm', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else {
                                                            ?>
                                                                <span><?php echo $acaClassesGrpRepPosNm; ?></span>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaClassesSbjctFcltrPosNm" class="control-label col-lg-4"><?php echo $sbjctLabel; ?> Facilitator Pos. Name:</label>
                                                        <div class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <div class="input-group">
                                                                    <input class="form-control" id="acaClassesSbjctFcltrPosNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" value="<?php echo $acaClassesSbjctFcltrPosNm; ?>" />
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Positions', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaClassesSbjctFcltrPosID', 'acaClassesSbjctFcltrPosNm', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else {
                                                            ?>
                                                                <span><?php echo $acaClassesSbjctFcltrPosNm; ?></span>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaClassesIsEnbld" class="control-label col-lg-6">Is Enabled?:</label>
                                                        <div class="col-lg-6">
                                                            <?php
                                                            $chkdYes = "";
                                                            $chkdNo = "checked=\"\"";
                                                            if ($acaClassesIsEnbld == "1") {
                                                                $chkdNo = "";
                                                                $chkdYes = "checked=\"\"";
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <label class="radio-inline"><input type="radio" name="acaClassesIsEnbld" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                <label class="radio-inline"><input type="radio" name="acaClassesIsEnbld" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                            <?php } else {
                                                            ?>
                                                                <span><?php echo ($acaClassesIsEnbld == "1" ? "YES" : "NO"); ?></span>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="padding:0px!important;">
                                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;border-top:1px solid #eee !important;border-radius: 2px;">
                                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                            <?php
                                                        }
                                                        /* if ($vwtyp == 0 || $vwtyp == 1) {
                                                              $srchFor = "%";
                                                              $srchIn = "Name";
                                                              $pageNo = 1;
                                                              $lmtSze = 30;
                                                              }
                                                              $total = get_Ttl_AcaClasseCrses($acaClassesID, $srchIn, $srchFor);
                                                              if ($pageNo > ceil($total / $lmtSze)) {
                                                              $pageNo = 1;
                                                              } else if ($pageNo < 1) {
                                                              $pageNo = ceil($total / $lmtSze);
                                                              }
                                                              $curIdx = $pageNo - 1; */
                                                        $cntrRndm = getRandomNum(5000, 9999);

                                                        $clssCrseLnID = $sbmtdCrseLineID;
                                                        if ($acaClassesID <= 0) {
                                                            $acaClassesID = (float) getGnrlRecNm("aca.aca_classes_n_thr_crses", "clss_crse_id", "class_id", $sbmtdCrseLineID);
                                                        }
                                                        $clssCrseID = (float) getGnrlRecNm("aca.aca_classes_n_thr_crses", "clss_crse_id", "course_id", $sbmtdCrseLineID);
                                                        $clssCrseSbjctID = -1;

                                                        $resultRw = get_AcaClasseCrses($acaClassesID, 0, 10000, "Name", "%");
                                                        if ($vwtyp != 2 && $vwtyp != 3) {
                                                            ?>
                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                        <?php
                                                                        if ($canEdt === true && $acaClassesID > 0) {
                                                                        ?>
                                                                            <button id="addNwScmAcaClassesCrseBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getClassCrseForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'acaEdtCourseForm', 'oneAcaClassesCrsesRow_<?php echo $cntrRndm; ?>', 'Add <?php echo $courseLabel; ?>', 20, 'ADD', <?php echo $clssCrseID; ?>, <?php echo $acaClassesID; ?>);" data-toggle="tooltip" data-placement="bottom" title="New <?php echo $courseLabel; ?> for <?php echo $groupLabel; ?>">
                                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add <?php echo $courseLabel; ?>
                                                                            </button>
                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAcaClassesForm('PasteDirect', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');">
                                                                                <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;
                                                                            </button>
                                                                        <?php } ?>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAcaClassesForm(<?php echo $acaClassesID; ?>, 1, 'PasteDirect', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="exprtAssessGrps();" data-toggle="tooltip" title="Export <?php echo $courseLabel; ?>">
                                                                            <img src="cmn_images/document_export.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="" data-toggle="tooltip" title="Import <?php echo $courseLabel; ?>">
                                                                            <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            Import
                                                                        </button>
                                                                    </div>
                                                                    <!--<div class="col-md-6 fcltyTypDetNav" style="padding:0px 15px 0px 15px !important;">
                                                                        <div class="input-group">
                                                                            <input class="form-control" id="acaClassesDetSrchFor" type = "text" placeholder="Search For" value="<?php
                                                                                                                                                                                echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                                                ?>" onkeyup="enterKeyFuncAcaClassesDet(event, '', '#acaClassesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdClassesID=<?php echo $acaClassesID; ?>');">
                                                                            <input id="acaClassesDetPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaClassesDet('clear', '#acaClassesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdClassesID=<?php echo $acaClassesID; ?>');">
                                                                                <span class="glyphicon glyphicon-remove"></span>
                                                                            </label>
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaClassesDet('', '#acaClassesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdClassesID=<?php echo $acaClassesID; ?>');">
                                                                                <span class="glyphicon glyphicon-search"></span>
                                                                            </label>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="acaClassesDetSrchIn">
                                                                    <?php
                                                                    $valslctdArry = array("");
                                                                    $srchInsArrys = array("Name");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($srchIn == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                    ?>
                                                                                                                                                                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                            </select>
                                                                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="acaClassesDetDsplySze" style="min-width:70px !important;">                            
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
                                                                    <div class="col-md-2 fcltyTypDetNav">
                                                                        <nav aria-label="Page navigation">
                                                                            <ul class="pagination" style="margin: 0px !important;">
                                                                                <li>
                                                                                    <a class="rhopagination" href="javascript:getAcaClassesDet('previous', '#acaClassesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdClassesID=<?php echo $acaClassesID; ?>');" aria-label="Previous">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a class="rhopagination" href="javascript:getAcaClassesDet('next', '#acaClassesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdClassesID=<?php echo $acaClassesID; ?>');" aria-label="Next">
                                                                                        <span aria-hidden="true">&raquo;</span>
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </nav>
                                                                    </div>-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAcaClassesLnsTblSctn">
                                                    <div class="tab-content" style="padding:4px !important;">
                                                        <div id="acaClassesDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                        <?php }
                                                        if ($vwtyp != 3) { ?>
                                                            <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                                <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                                    <table class="table table-striped table-bordered table-responsive" id="oneAcaClassesCrsesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                                <th style="min-width:80px;"><?php echo $courseLabel; ?> Code/Name</th>
                                                                                <th style="min-width:80px;">Current <?php echo $courseLabel; ?> Facilitator</th>
                                                                                <th style="min-width:80px;">Current <?php echo $courseLabel; ?> Representative</th>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $mkReadOnly = "";
                                                                            $cntr = 0;
                                                                            while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                $clssCrseLnID = (float) $rowRw[0];
                                                                                if ($cntr == 0) {
                                                                                    $clssCrseID = (float) $rowRw[1];
                                                                                }
                                                                                $clssCrseIsEnbld = $rowRw[4];
                                                                                $clssCrseCode = $rowRw[2];
                                                                                $clssCrseName = $rowRw[3];
                                                                                $clssCrseLnNm = $rowRw[5];
                                                                                $clssCrseMinWeight = (float) $rowRw[6];
                                                                                $clssCrseMaxWeight = (float) $rowRw[7];
                                                                                $clssCrseFcltrNm = $rowRw[8];
                                                                                $clssCrseRepNm = $rowRw[9];
                                                                                $cntr += 1;
                                                                            ?>
                                                                                <tr id="oneAcaClassesCrsesRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAcaClassesCrsesTable tr').index(this));">
                                                                                    <td class="lovtd">
                                                                                        <span><?php echo ($cntr); ?></span>
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_CrseLnID" value="<?php echo $clssCrseLnID; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_CrseID" value="<?php echo $clssCrseID; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_CrseCode" value="<?php echo $clssCrseCode; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_CrseName" value="<?php echo $clssCrseName; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_IsEnbld" value="<?php echo $clssCrseIsEnbld; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_LineName" value="<?php echo $clssCrseLnNm; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_MinWeight" value="<?php echo $clssCrseMinWeight; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_MaxWeight" value="<?php echo $clssCrseMaxWeight; ?>" style="width:100% !important;">
                                                                                    </td>
                                                                                    <td class="lovtd" style="">
                                                                                        <span><?php echo $clssCrseLnNm; ?></span>
                                                                                    </td>
                                                                                    <td class="lovtd" style="">
                                                                                        <span><?php echo $clssCrseFcltrNm; ?></span>
                                                                                    </td>
                                                                                    <td class="lovtd" style="">
                                                                                        <span><?php echo $clssCrseRepNm; ?></span>
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default btn-sm" id="oneAcaClassesCrsesRow<?php echo $cntr; ?>_CrsesBtn" onclick="getClassCrseForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'acaEdtCourseForm', 'oneAcaClassesCrsesRow_<?php echo $cntr; ?>', 'Edit <?php echo $courseLabel; ?>', 20, 'EDIT', <?php echo $clssCrseID; ?>, <?php echo $acaClassesID; ?>);" style="padding:2px !important;" title="Edit <?php echo $courseLabel; ?>">
                                                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaClassesLne('oneAcaClassesCrsesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete <?php echo $courseLabel; ?>">
                                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-12" id="acaCrseSbjctsDetailInfo" style="padding:0px 2px 0px 2px !important;">
                                                                <?php } ?>
                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                    <?php
                                                                    if ($canEdt === true && $clssCrseID > 0) {
                                                                    ?>
                                                                        <button id="addNwScmAcaClassesSbjctBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getClassCrseSbjctForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'acaEdtCourseSbjctForm', 'oneAcaClassesSbjctsRow_<?php echo $cntrRndm; ?>', 'Add <?php echo $courseLabel; ?>', 21, 'ADD', <?php echo $clssCrseSbjctID; ?>, <?php echo $clssCrseID; ?>, <?php echo $acaClassesID; ?>);" data-toggle="tooltip" data-placement="bottom" title="New <?php echo $sbjctLabel; ?> for <?php echo $groupLabel; ?>">
                                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add <?php echo $sbjctLabel; ?>
                                                                        </button>
                                                                    <?php } ?>
                                                                </div>
                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                    <table class="table table-striped table-bordered table-responsive" id="oneAcaClassesSbjctsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                                <th style="min-width:120px;"><?php echo $sbjctLabel; ?> Code/Name</th>
                                                                                <th style="max-width:50px;width:50px;">Core/ Elective</th>
                                                                                <th style="max-width:50px;width:50px;"><?php echo $moduleType2Wght; ?></th>
                                                                                <th style="max-width:60px;width:60px;">Period Type</th>
                                                                                <th style="max-width:50px;width:50px;">Period No.</th>
                                                                                <th style="min-width:120px;">Current <?php echo $sbjctLabel; ?> Facilitator</th>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $resultRw2 = get_AcaClasseSbjcts($acaClassesID, $clssCrseID, 0, 10000, "Name", "%");
                                                                            $mkReadOnly = "";
                                                                            $cntr = 0;
                                                                            $curIdx = 0;
                                                                            while ($rowRw = loc_db_fetch_array($resultRw2)) {
                                                                                $clssSbjctLnID = (float) $rowRw[0];
                                                                                $clssSbjctID = (float) $rowRw[2];
                                                                                $clssSbjctCode = $rowRw[6];
                                                                                $clssSbjctNm = $rowRw[7];
                                                                                $clssSbjctLnNm = $rowRw[9];
                                                                                $clssSbjctCoreElect = $rowRw[4];
                                                                                $clssSbjctIsEnbld = $rowRw[8];
                                                                                $clssSbjctWeight = (float) $rowRw[5];
                                                                                $clssSbjctLnPrdTyp = $rowRw[10];
                                                                                $clssSbjctLnPrdNum = (float) $rowRw[11];
                                                                                $clssSbjctFcltrNm = $rowRw[12];
                                                                                $cntr += 1;
                                                                                $clssCrseSbjctID = $clssSbjctLnID;
                                                                            ?>
                                                                                <tr id="oneAcaClassesSbjctsRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAcaClassesSbjctsTable tr').index(this));">
                                                                                    <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_SbjctLnID" value="<?php echo $clssSbjctLnID; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_SbjctID" value="<?php echo $clssSbjctID; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_SbjctCode" value="<?php echo $clssSbjctCode; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_SbjctNm" value="<?php echo $clssSbjctNm; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_IsEnbld" value="<?php echo $clssSbjctIsEnbld; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_SbjctType" value="<?php echo $clssSbjctCoreElect; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_Weight" value="<?php echo $clssSbjctWeight; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_PrdTyp" value="<?php echo $clssSbjctLnPrdTyp; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_PrdNum" value="<?php echo $clssSbjctLnPrdNum; ?>" style="width:100% !important;">
                                                                                    </td>
                                                                                    <td class="lovtd" style="">
                                                                                        <span><?php echo $clssSbjctLnNm; ?></span>
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <span><?php echo $clssSbjctCoreElect; ?></span>
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <span><?php echo $clssSbjctWeight; ?></span>
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <span><?php echo $clssSbjctLnPrdTyp; ?></span>
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <span><?php echo $clssSbjctLnPrdNum; ?></span>
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <span><?php echo $clssSbjctFcltrNm; ?></span>
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default btn-sm" id="oneAcaClassesSbjctsRow<?php echo $cntr; ?>_SbjctsBtn" onclick="getClassCrseSbjctForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'acaEdtCourseSbjctForm', 'oneAcaClassesSbjctsRow_<?php echo $cntr; ?>', 'Edit <?php echo $courseLabel; ?>', 21, 'EDIT', <?php echo $clssCrseSbjctID; ?>, <?php echo $clssCrseID; ?>, <?php echo $acaClassesID; ?>);" style="padding:2px !important;" title="Edit <?php echo $sbjctLabel; ?>">
                                                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaClassesSbjcts('oneAcaClassesSbjctsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete <?php echo $sbjctLabel; ?>">
                                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                                                                                <th style="">&nbsp;</th>
                                                                                <th style="">&nbsp;</th>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                                <?php if ($vwtyp != 3) { ?>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                $("#acaClassesDetPageNo").val(<?php echo $pageNo; ?>);
                                                            </script>
                                                        <?php }
                                                                if ($vwtyp != 2 && $vwtyp != 3) { ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                                                }
                                                                if ($vwtyp == 0) {
                                    ?>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                <?php
                                                                }
                                                            } else if ($vwtyp == 4) {
                                                            } else if ($vwtyp == 20) {
                                                                /* Add Course Form */
                                                                $classCoursePkeyID = isset($_POST['classCoursePkeyID']) ? cleanInputData($_POST['classCoursePkeyID']) : -1;
                                                                $sbmtdClassID = isset($_POST['sbmtdClassID']) ? cleanInputData($_POST['sbmtdClassID']) : -1;
                                                                $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
                ?>
                <form class="form-horizontal" id="acaEdtCourseForm" style="padding:5px 20px 5px 20px;">
                    <div class="row">
                        <div class="form-group form-group-sm">
                            <label for="classCrseCode" class="control-label col-md-4"><?php echo $courseLabel; ?> Code:</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="classCrseCode" value="">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="classCrseID" value="-1">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="sbmtdClassID" value="<?php echo $sbmtdClassID; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $courseLOV; ?>', '', '', '', 'radio', true, '', 'classCrseID', 'classCrseCode', 'clear', 0, '', function () {
                                                afterCrseSelect();
                                            });">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="classCrseName" class="control-label col-md-4"><?php echo $courseLabel; ?> Name:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="classCrseName" value="">
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="classCrseName" class="control-label col-md-4">Min. Total <?php echo $moduleType2Wght; ?>:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" aria-label="..." id="classCrseMinWeight" value="">
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="classCrseName" class="control-label col-md-4">Max. Total <?php echo $moduleType2Wght; ?>:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" aria-label="..." id="classCrseMaxWeight" value="">
                            </div>
                        </div>
                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <label for="classCrseIsEnbld" class="control-label col-lg-6">Is Enabled?:</label>
                            <div class="col-lg-6">
                                <?php
                                                                $chkdNo = ""; //checked=\"\"
                                                                $chkdYes = "";
                                ?>
                                <label class="radio-inline"><input type="radio" name="classCrseIsEnbld" id="classCrseIsEnbldYES" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                <label class="radio-inline"><input type="radio" name="classCrseIsEnbld" id="classCrseIsEnbldNO" value="NO" <?php echo $chkdNo; ?>>NO</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveClassCrseForm('myFormsModal', '<?php echo $classCoursePkeyID; ?>',<?php echo $sbmtdClassID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
                    </div>
                </form>
            <?php
                                                            } else if ($vwtyp == 21) {
                                                                /* Add Subject Form */
                                                                $crseSbjctPkeyID = isset($_POST['crseSbjctPkeyID']) ? cleanInputData($_POST['crseSbjctPkeyID']) : -1;
                                                                $sbmtdCrseID = isset($_POST['sbmtdCrseID']) ? cleanInputData($_POST['sbmtdCrseID']) : -1;
                                                                $sbmtdClassID = isset($_POST['sbmtdClassID']) ? cleanInputData($_POST['sbmtdClassID']) : -1;
                                                                $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
            ?>
                <form class="form-horizontal" id="acaEdtCourseForm" style="padding:5px 20px 5px 20px;">
                    <div class="row">
                        <div class="form-group form-group-sm">
                            <label for="crseSbjctCode" class="control-label col-md-4"><?php echo $sbjctLabel; ?> Code:</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="crseSbjctCode" value="">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="crseSbjctID" value="-1">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="sbmtdClassID" value="<?php echo $sbmtdClassID; ?>">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="sbmtdCrseID" value="<?php echo $sbmtdCrseID; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $sbjctLOV; ?>', '', '', '', 'radio', true, '', 'crseSbjctID', 'crseSbjctCode', 'clear', 0, '', function () {
                                                afterSbjctSelect();
                                            });">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="crseSbjctName" class="control-label col-md-4"><?php echo $sbjctLabel; ?> Name:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="crseSbjctName" value="">
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="crseSbjct_Type" class="control-label col-md-4">Core/Elective:</label>
                            <div class="col-md-8">
                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="crseSbjct_Type" style="width:100% !important;">
                                    <?php
                                                                $valslctdArry = array("", "");
                                                                $srchInsArrys = array("Core", "Elective");
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="crseSbjct_Weight" class="control-label col-md-4"><?php echo $moduleType2Wght; ?>:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" aria-label="..." id="crseSbjct_Weight" value="">
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="crseSbjct_PeriodType" class="control-label col-md-4">Period Type:</label>
                            <div class="col-md-8">
                                <select data-placeholder="Select..." class="form-control chosen-select" id="crseSbjct_PeriodType" style="width:100% !important;">
                                    <?php
                                                                $valslctdArry = array("", "", "", "", "", "", "", "", "");
                                                                $srchInsArrys = array("", "Semester", "Term", "Trimester", "Year", "Half-Year", "Quarter", "Month", "Other");
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="crseSbjct_PeriodNum" class="control-label col-md-4">Period Number:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" aria-label="..." id="crseSbjct_PeriodNum" value="1">
                            </div>
                        </div>
                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <label for="crseSbjct_IsEnbld" class="control-label col-lg-6">Is Enabled?:</label>
                            <div class="col-lg-6">
                                <?php
                                                                $chkdNo = ""; //checked=\"\"
                                                                $chkdYes = "";
                                ?>
                                <label class="radio-inline"><input type="radio" name="crseSbjctIsEnbld" id="crseSbjctIsEnbldYES" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                <label class="radio-inline"><input type="radio" name="crseSbjctIsEnbld" id="crseSbjctIsEnbldNO" value="NO" <?php echo $chkdNo; ?>>NO</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveClassCrseSbjctForm('myFormsModal', '<?php echo $crseSbjctPkeyID; ?>',<?php echo $sbmtdCrseID; ?>,<?php echo $sbmtdClassID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
                    </div>
                </form>
<?php
                                                            }
                                                        }
                                                    }
                                                }
