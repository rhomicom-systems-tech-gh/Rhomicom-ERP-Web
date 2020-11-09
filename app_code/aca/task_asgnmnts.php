<?php
$canAdd = test_prmssns($dfltPrvldgs[28], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[29], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[30], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";


$searchAll = true;

$srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
$srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
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
$exprtFileNmPrt = "PrgrmmeRgstrtns";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
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
            } else if ($actyp == 1001) {
                //Export
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array(
                        "ID No.**", "Person Full Name", "Group Name**", "Programme/Objective Code**", "Programme/Objective Name",
                        "Assessment Period Name**", "Subject/Target Code", "Subject/Target Name", "Core/Elective", "Weight/Credit Hours"
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
                    $result = get_AcaSttngsExprt(0, $limit_size, $srchIn, $srchFor, $qShwCrntOnly);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        //"" . ($z + 1), 
                        $crntRw = array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9]);
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
                                <button type="button" class="btn btn-default btn-sm" id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_CrsesBtn" onclick="getAcaRgstratnClassForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'acaEdtRgstrClassForm', 'oneAcaRgstratnCrsesRow_<?php echo $cntr; ?>', 'Edit <?php echo $courseLabel; ?>', 20, 'EDIT', <?php echo $acaRgstrClassPkeyID; ?>, <?php echo $sbmtdRgstrPersonID; ?>);" style="padding:2px !important;" title="Edit <?php echo $courseLabel; ?>">
                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                            <td class="lovtd" style="text-align: center;">
                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaRgstratnLne('oneAcaRgstratnCrsesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete <?php echo $courseLabel; ?>">
                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                                <button type="button" class="btn btn-default btn-sm" id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_SbjctsBtn" onclick="getAcaRgstratnSbjctForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'acaEdtCourseSbjctForm', 'oneAcaRgstratnSbjctsRow_<?php echo $cntr; ?>', 'Edit <?php echo $courseLabel; ?>', 21, 'EDIT', <?php echo $sttngsSbjctLnID; ?>, <?php echo $acaRgstratnSttngsID; ?>);" style="padding:2px !important;" title="Edit <?php echo $sbjctLabel; ?>">
                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                            <td class="lovtd" style="text-align: center;">
                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaRgstratnSbjcts('oneAcaRgstratnSbjctsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete <?php echo $sbjctLabel; ?>">
                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
        } else {
            if ($vwtyp == 0 || $vwtyp == 1 || $vwtyp == 2 || $vwtyp == 3) {
                $pkID = isset($_POST['sbmtdAcaSttngsPrsnID']) ? $_POST['sbmtdAcaSttngsPrsnID'] : -1;
                $actionTxt = isset($_POST['actionTxt']) ? $_POST['actionTxt'] : "PasteDirect";
                $destElmntID = isset($_POST['destElmntID']) ? $_POST['destElmntID'] : "acaRgstratnDetailInfo";
                $titleMsg = isset($_POST['titleMsg']) ? $_POST['titleMsg'] : "";
                $titleElementID = isset($_POST['titleElementID']) ? $_POST['titleElementID'] : "";
                $modalBodyID = isset($_POST['modalBodyID']) ? $_POST['modalBodyID'] : "";
                $sbmtdAcaSttngsID = isset($_POST['sbmtdAcaSttngsID']) ? $_POST['sbmtdAcaSttngsID'] : -1;
                if ($vwtyp == 0) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">$courseLabel $courseLabelAddon</span>
				</li>
                               </ul>
                              </div>";

                    $total = get_BscPrsnTtl($srchFor, $srchIn, $orgID, $searchAll, $fltrTypValue, $fltrTyp);
                    //var_dump($_POST);
                    //echo "TT:" . $total;
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_BscPrsn($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $fltrTypValue, $fltrTyp);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-6";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='acaRgstratnForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="acaRgstratnSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                    echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                    ?>" onkeyup="enterKeyFuncAcaRgstratn(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="acaRgstratnPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaRgstratn('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaRgstratn('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaRgstratnSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                        $srchInsArrys = array(
                                            "ID/Full Name", "Full Name", "Residential Address", "Contact Information", "Linked Firm/Workplace", "Person Type",
                                            "Date of Birth", "Home Town", "Gender", "Marital Status"
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
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaRgstratnDsplySze" style="min-width:70px !important;">
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
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaRgstratnSortBy">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "");
                                        $srchInsArrys = array("ID ASC", "ID DESC", "Date Added DESC", "Date of Birth", "Full Name");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaRgstratnFilterBy" onchange="onAcaPrsnFilterByChange();">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "");
                                        $srchInsArrys = array("Relation Type", "Division/Group", "Grade", "Job", "Position", "Site/Location");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($fltrTyp == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaRgstratnFilterByVal" onchange="onAcaPrsnFltrValChange();">
                                        <?php
                                        $srchInsArrys = loadDataOptions2($fltrTyp);
                                        $valslctdArry = array_fill(0, count($srchInsArrys), "");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($fltrTypValue == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAcaRgstratn('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAcaRgstratn('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
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
                        <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;border-bottom:1px solid #ddd;">
                            <div class="col-md-6" style="padding:2px 1px 2px 1px !important;">
                                <?php if ($canAdd === true) { ?>
                                    <button type="button" class="btn btn-default" style="" onclick="getPrsnAdminCreate('Female', 'Ghanaian', 'Christianity', 'Member', 'New Enrolment', 'M');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Person">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Add Person
                                    </button>
                                    <!--<button type="button" class="btn btn-default" style="" onclick="saveAcaRgstratnForm('<?php echo $actionTxt; ?>', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save
                                    </button>-->
                                <?php } ?>
                                <button type="button" class="btn btn-default" onclick="exprtRgstrtns()" title="Export Registrations">
                                    <img src="cmn_images/document_export.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Export Registrations
                                </button>
                                <?php if ($canAdd) { ?>
                                    <button type="button" class="btn btn-default" onclick="" title="Import Registrations">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Import Registrations
                                    </button>
                                <?php } ?>
                            </div>
                            <div class="col-md-6" style="padding:2px 1px 2px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label" title="Only <?php echo $courseLabelAddon . "s"; ?> in Current Period">
                                        <?php
                                        $shwCrntOnlyChkd = "";
                                        if ($qShwCrntOnly == true) {
                                            $shwCrntOnlyChkd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getOneAcaRgstratnForm(-1, 1, 'PasteDirect', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');" id="acaRgstratnShwCrntOnly" name="acaRgstratnShwCrntOnly" <?php echo $shwCrntOnlyChkd; ?>>
                                        Show only Current/Active <?php echo $courseLabelAddon . "s"; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5" style="padding:0px 1px 0px 15px !important;">
                                <fieldset class="basic_person_fs">
                                    <table class="table table-striped table-bordered table-responsive" id="acaRgstratnTable" cellspacing="0" width="100%" style="width:100% !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>ID No.</th>
                                                <th>Full Name</th>
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
                                                    $pkID = (float) $row[0];
                                                }
                                                //border-top:1px solid #ddd;
                                                $cntr += 1;
                                            ?>
                                                <tr id="acaRgstratnRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $row[1]; ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="acaRgstratnRow<?php echo $cntr; ?>_PrsnID" value="<?php echo $row[0]; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="acaRgstratnRow<?php echo $cntr; ?>_PrsnNm" value="<?php echo $row[1]; ?>">
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[2]; ?></td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Basic Profile" onclick="getBscProfileForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'dtAdmnBscPrsnPrflForm', 'View Person Basic Profile (Direct)', <?php echo $row[0]; ?>, 0, 1, 'VIEW');" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                    echo urlencode(encrypt1(($row[0] . "|prs.prsn_names_nos|person_id"), $smplTokenWord1));
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
                            <div class="col-md-7" style="padding:0px 15px 0px 1px !important">
                                <fieldset class="basic_person_fs" style="padding-top:2px !important;">
                                    <div class="container-fluid" id="acaRgstratnDetailInfo">
                                    <?php
                                }
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
                                if ($vwtyp != 2 && $vwtyp != 3) {
                                    ?>
                                        <div class="row">
                                            <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                <fieldset class="basic_person_fs222" style="padding-top:10px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
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
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaRgstratnPrsName" class="control-label col-lg-4">Full Name:</label>
                                                        <div class="col-lg-8">
                                                            <span><?php echo $acaRgstratnPrsName; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaRgstratnPrsContacts" class="control-label col-lg-4">Contact Nos.:</label>
                                                        <div class="col-lg-8">
                                                            <span><?php echo $acaRgstratnPrsContacts; ?></span>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                <fieldset class="basic_person_fs222" style="padding-top:10px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaRgstratnPrsType" class="control-label col-lg-4">Person Type:</label>
                                                        <div class="col-lg-8">
                                                            <span><?php echo $acaRgstratnPrsType; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaRgstratnPrsEmail" class="control-label col-lg-4">Email:</label>
                                                        <div class="col-lg-8">
                                                            <span><?php echo $acaRgstratnPrsEmail; ?></span>
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
                                                              $total = get_Ttl_AcaClasseCrses($acaRgstratnID, $srchIn, $srchFor);
                                                              if ($pageNo > ceil($total / $lmtSze)) {
                                                              $pageNo = 1;
                                                              } else if ($pageNo < 1) {
                                                              $pageNo = ceil($total / $lmtSze);
                                                              }
                                                              $curIdx = $pageNo - 1; */

                                                        $cntrRndm = getRandomNum(5000, 9999);
                                                        $acaRgstratnSttngsID = $sbmtdAcaSttngsID;
                                                        $acaRgstratnSbjctID = -1;
                                                        $resultRw = get_AcaSttngsClasses($acaRgstratnPrsID, 0, 10000, "Name", "%", $qShwCrntOnly);
                                                        if ($vwtyp != 2 && $vwtyp != 3) {
                                                            ?>
                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                        <?php
                                                                        if ($canEdt === true && $acaRgstratnPrsID > 0) {
                                                                        ?>
                                                                            <button id="addNwAcaRgstratnCrseBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getAcaRgstratnClassForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'acaEdtRgstrClassForm', 'oneAcaRgstratnCrsesRow_<?php echo $cntrRndm; ?>', 'Add <?php echo $courseLabel; ?>', 20, 'ADD', -1, <?php echo $acaRgstratnPrsID; ?>);" data-toggle="tooltip" data-placement="bottom" title="New <?php echo $courseLabel; ?> for <?php echo $groupLabel; ?>">
                                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add <?php echo $courseLabel; ?>
                                                                            </button>
                                                                            <!--<button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAcaRgstratnForm('PasteDirect', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>-->
                                                                        <?php } ?>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAcaRgstratnForm(<?php echo $acaRgstratnPrsID; ?>, 1, 'PasteDirect', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');">
                                                                            <img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="printEmailFullRgstrSlp(<?php echo $acaRgstratnPrsID; ?>);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Print Registration Slip">
                                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            Print
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAcaRgstratnLnsTblSctn">
                                                    <div class="tab-content" style="padding:4px !important;">
                                                        <div id="acaRgstratnDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                        <?php }
                                                        if ($vwtyp != 3) { ?>
                                                            <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                                <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                                    <table class="table table-striped table-bordered table-responsive" id="oneAcaRgstratnCrsesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                                <th style="min-width:80px;"><?php echo $groupLabel; ?> Name</th>
                                                                                <th style="min-width:80px;"><?php echo $courseLabel; ?> Code/Name</th>
                                                                                <th style="min-width:60px;">Period</th>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
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
                                                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default" id="oneAcaRgstratnCrsesRow<?php echo $cntr; ?>_CrsesBtn" onclick="getAcaRgstratnClassForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'acaEdtRgstrClassForm', 'oneAcaRgstratnCrsesRow_<?php echo $cntr; ?>', 'Edit <?php echo $courseLabel; ?>', 20, 'EDIT', <?php echo $acaSttngsLineID; ?>, <?php echo $acaRgstratnPrsID; ?>);" style="padding:2px !important;" title="Edit <?php echo $courseLabel; ?>">
                                                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaRgstratnLne('oneAcaRgstratnCrsesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete <?php echo $courseLabel; ?>">
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
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-12" id="acaRgstratnSbjctsDetailInfo" style="padding:0px 2px 0px 2px !important;">
                                                                <?php } ?>
                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                    <?php
                                                                    if ($canEdt === true && $acaRgstratnSttngsID > 0) {
                                                                    ?>
                                                                        <button id="addNwScmAcaRgstratnSbjctBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getAcaRgstratnSbjctForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'acaEdtCourseSbjctForm', 'oneAcaRgstratnSbjctsRow_<?php echo $cntrRndm; ?>', 'Add <?php echo $courseLabel; ?>', 21, 'ADD', <?php echo $acaRgstratnSbjctID; ?>, <?php echo $acaRgstratnSttngsID; ?>);" data-toggle="tooltip" data-placement="bottom" title="New <?php echo $sbjctLabel; ?> for <?php echo $groupLabel; ?>">
                                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add <?php echo $sbjctLabel; ?>
                                                                        </button>
                                                                    <?php } ?>
                                                                </div>
                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                    <table class="table table-striped table-bordered table-responsive" id="oneAcaRgstratnSbjctsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                                <th style="min-width:120px;"><?php echo $sbjctLabel; ?> Code/Name</th>
                                                                                <th style="max-width:70px;width:70px;">Core/ Elective</th>
                                                                                <th style="max-width:70px;width:70px;"><?php echo $moduleType2Wght; ?></th>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
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
                                                                                        <button type="button" class="btn btn-default btn-sm" id="oneAcaRgstratnSbjctsRow<?php echo $cntr; ?>_SbjctsBtn" onclick="getAcaRgstratnSbjctForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'acaEdtCourseSbjctForm', 'oneAcaRgstratnSbjctsRow_<?php echo $cntr; ?>', 'Edit <?php echo $courseLabel; ?>', 21, 'EDIT', <?php echo $sttngsSbjctLnID; ?>, <?php echo $acaRgstratnSttngsID; ?>);" style="padding:2px !important;" title="Edit <?php echo $sbjctLabel; ?>">
                                                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaRgstratnSbjcts('oneAcaRgstratnSbjctsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete <?php echo $sbjctLabel; ?>">
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
                                                                                <th style="">Total <?php echo $moduleType2Wght; ?></th>
                                                                                <th style="">&nbsp;</th>
                                                                                <th style=""><?php echo $ttlWeight; ?></th>
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
                                                                $("#acaRgstratnDetPageNo").val(<?php echo $pageNo; ?>);
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
                                                                /* Add Class/Course Form */
                                                                $acaRgstrClassPkeyID = isset($_POST['acaRgstrClassPkeyID']) ? cleanInputData($_POST['acaRgstrClassPkeyID']) : -1;
                                                                $sbmtdRgstrPersonID = isset($_POST['sbmtdRgstrPersonID']) ? cleanInputData($_POST['sbmtdRgstrPersonID']) : -1;
                                                                $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
                ?>
                <form class="form-horizontal" id="acaEdtRgstrClassForm" style="padding:5px 20px 5px 20px;">
                    <div class="row">
                        <div class="form-group form-group-sm">
                            <label for="acaRgstrClassNm" class="control-label col-md-4"><?php echo $groupLabel; ?>:</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="acaRgstrClassNm" value="">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstrClassID" value="-1">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="sbmtdRgstrPersonID" value="<?php echo $sbmtdRgstrPersonID; ?>">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstrClassPkeyID" value="<?php echo $acaRgstrClassPkeyID; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Groups', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaRgstrClassID', 'acaRgstrClassNm', 'clear', 0, '', function () {
                                                /*afterCrseSelect();*/
                                            });">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="acaRgstrCrseName" class="control-label col-md-4"><?php echo $courseLabel; ?>:</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="acaRgstrCrseName" value="">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstrCrseID" value="-1">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Objectives/Courses', 'acaRgstrClassID', '', '', 'radio', true, '', 'acaRgstrCrseID', 'acaRgstrCrseName', 'clear', 0, '', function () {
                                                /*afterCrseSelect();*/
                                            });">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="acaRgstrPrdName" class="control-label col-md-4">Period:</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="acaRgstrPrdName" value="">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstrPrdID" value="-1">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Periods', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaRgstrPrdID', 'acaRgstrPrdName', 'clear', 0, '', function () {
                                                /*afterCrseSelect();*/
                                            });">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveAcaRgstratnClassForm('myFormsModal', '<?php echo $acaRgstrClassPkeyID; ?>',<?php echo $sbmtdRgstrPersonID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
                    </div>
                </form>
            <?php
                                                            } else if ($vwtyp == 21) {
                                                                /* Add Subject Form */
                                                                $sttngsSbjctPkeyID = isset($_POST['sttngsSbjctPkeyID']) ? cleanInputData($_POST['sttngsSbjctPkeyID']) : -1;
                                                                $acaRgstratnSttngsID = isset($_POST['acaRgstratnSttngsID']) ? cleanInputData($_POST['acaRgstratnSttngsID']) : -1;
                                                                $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
                                                                $acaRgstratnClassID = (float) getGnrlRecNm("aca.aca_prsns_acdmc_sttngs", "acdmc_sttngs_id", "class_id", $acaRgstratnSttngsID);
                                                                $acaRgstratnCrseID = (float) getGnrlRecNm("aca.aca_prsns_acdmc_sttngs", "acdmc_sttngs_id", "course_id", $acaRgstratnSttngsID);
            ?>
                <form class="form-horizontal" id="acaEdtRgstrSbjctForm" style="padding:5px 20px 5px 20px;">
                    <div class="row">
                        <div class="form-group form-group-sm">
                            <label for="crseSbjctCode" class="control-label col-md-4"><?php echo $sbjctLabel; ?>:</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="sttngsSbjctName" value="">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="sttngsSbjctID" value="-1">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="sttngsSbjctPkeyID" value="<?php echo $sttngsSbjctPkeyID; ?>">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstratnSttngsID" value="<?php echo $acaRgstratnSttngsID; ?>">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstratnClassID" value="<?php echo $acaRgstratnClassID; ?>">
                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="acaRgstratnCrseID" value="<?php echo $acaRgstratnCrseID; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Tasks/Subjects', 'acaRgstratnClassID', 'acaRgstratnCrseID', '', 'radio', true, '', 'sttngsSbjctID', 'sttngsSbjctName', 'clear', 0, '', function () {
                                            });">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveAcaRgstratnSbjctForm('myFormsModal', '<?php echo $sttngsSbjctPkeyID; ?>',<?php echo $acaRgstratnSttngsID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
                    </div>
                </form>
<?php
                                                            } else if ($vwtyp == 701) {
                                                                //Print Registration Slip   
                                                                $vPsblValID1 = getEnbldPssblValID("Html Registration Slip Print File Name", getLovID("All Other Performance Setups"));
                                                                $vPsblVal1 = getPssblValDesc($vPsblValID1);
                                                                if ($vPsblVal1 == "") {
                                                                    $vPsblVal1 = 'htm_rpts/rgstrtn_slip.php';
                                                                }
                                                                require $vPsblVal1;
                                                            }
                                                        }
                                                    }
                                                }
