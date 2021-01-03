<?php
$canAdd = test_prmssns($dfltPrvldgs[13], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[14], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[15], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";

$exprtFileNmPrt = "CourseObjctvs";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Doc Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo delete_AcaCourses($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Complaints/Observations
                header("content-type:application/json");
                $slctdCourseIDs = isset($_POST['slctdCourseIDs']) ? cleanInputData($_POST['slctdCourseIDs']) : "";
                $exitErrMsg = "";
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdCourseIDs, "|~") != "") {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdCourseIDs, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 6) {
                            $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_Type = cleanInputData1($crntRow[1]);
                            $ln_CourseCode = cleanInputData1($crntRow[2]);
                            $ln_CourseNm = cleanInputData1($crntRow[3]);
                            $ln_CourseDesc = cleanInputData1($crntRow[4]);
                            $ln_IsEnbld = (cleanInputData1($crntRow[5]) == "YES") ? TRUE : FALSE;
                            $errMsg = "";
                            if ($ln_Type === "" || $ln_CourseCode === "" || $ln_CourseNm === "") {
                                $errMsg = "Row " . ($y + 1) . ":- Record Type, Code and Name are all required Fields!<br/>";
                            }
                            if ($errMsg === "") {
                                if ($ln_TrnsLnID <= 0) {
                                    $ln_TrnsLnID = getNew_AcaCoursesID();
                                    $afftctd += create_AcaCourses($ln_TrnsLnID, $ln_Type, $ln_CourseCode, $ln_CourseNm, $ln_CourseDesc, $ln_IsEnbld);
                                } else if ($ln_TrnsLnID > 0) {
                                    $afftctd += update_AcaCourses($ln_TrnsLnID, $ln_Type, $ln_CourseCode, $ln_CourseNm, $ln_CourseDesc, $ln_IsEnbld);
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
                    }
                }

                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Course/Objective(s) Successfully Saved!"
                        . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Course/Objective(s) Successfully Saved!";
                }
                $arr_content['percent'] = 100;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 1001) {
                //Export
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array("Type**", "Programme/Objective Code**", "Programme/Objective Name**", "Programme/Objective Description", "ENABLED?");
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
                    $result = get_AcaCoursesExprt(0, $limit_size, "%", "Name");
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        //"" . ($z + 1), 
                        $crntRw = array($row[0], $row[1], $row[2], $row[3], $row[4]);
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
                    if ($z <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                            json_encode($arr_content)
                        );
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
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&mdl=$mdlACAorPMS');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Courses/Objectives</span>
				</li>
                               </ul>
                              </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Description';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_Total_AcaCourses($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_AcaCourses($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-5";
                $colClassType3 = "col-md-5";
?>
                <form id='acaCoursesForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                    <fieldset class="">
                        <legend class="basic_person_lg1" style="color: #003245">ALL COURSES/OBJECTIVES</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType1 = "col-md-2";
                            $colClassType2 = "col-md-5";
                            $colClassType3 = "col-md-10";
                            ?>
                            <?php
                            if ($canAdd === true) {
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-4";
                                $colClassType3 = "col-md-6";

                                $nwRowHtml31 = "<tr id=\"acaCoursesHdrsRow__WWW123WWW\">                                    
                                                    <td class=\"lovtd\">New</td>
                                                        <td class=\"lovtd\">
                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select rqrdFld\" id=\"acaCoursesHdrsRow_WWW123WWW_Type\" style=\"width:100% !important;\">";
                                $valslctdArry = array("");
                                $srchInsArrys = array("Course");
                                if ($mdlACAorPMS == "PMS") {
                                    $valslctdArry = array("");
                                    $srchInsArrys = array("Objective");
                                }
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    $nwRowHtml31 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                }
                                $nwRowHtml31 .= "</select>
                                            </td>    
                                            <td class=\"lovtd\">
                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"acaCoursesHdrsRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"acaCoursesHdrsRow_WWW123WWW_CourseCode\" name=\"acaCoursesHdrsRow_WWW123WWW_CourseCode\" value=\"\" style=\"width:100% !important;text-align: left;\">                                                                                                 
                                            </td> 
                                            <td class=\"lovtd\">
                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"acaCoursesHdrsRow_WWW123WWW_CourseNm\" name=\"acaCoursesHdrsRow_WWW123WWW_CourseNm\" value=\"\" style=\"width:100% !important;text-align: left;\">                                                    
                                            </td>
                                            <td class=\"lovtd\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"acaCoursesHdrsRow_WWW123WWW_CourseDesc\" name=\"acaCoursesHdrsRow_WWW123WWW_CourseDesc\" value=\"\" style=\"width:100% !important;text-align: left;\">                                                    
                                            </td>                                          
                                            <td class=\"lovtd\" style=\"text-align:center;\">
                                                <div class=\"form-group form-group-sm\">
                                                    <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                        <label class=\"form-check-label\">
                                                            <input type=\"checkbox\" class=\"form-check-input\" id=\"acaCoursesHdrsRow_WWW123WWW_IsEnbld\" name=\"acaCoursesHdrsRow_WWW123WWW_IsEnbld\" checked=\"true\">
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>";
                                if ($canDel === true) {
                                    $nwRowHtml31 .= "<td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Course/Objective\" onclick=\"delAcaCourses('acaCoursesHdrsRow__WWW123WWW');\" style=\"padding:2px !important;\" style=\"padding:2px !important;\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">
                                                        </button>
                                                    </td>";
                                }
                                if ($canVwRcHstry === true) {
                                    $nwRowHtml31 .= "<td class=\"lovtd\">&nbsp;</td>";
                                }
                                $nwRowHtml31 .= "</tr>";
                                $nwRowHtml33 = urlencode($nwRowHtml31);
                            ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewAcaCoursesRows('acaCoursesHdrsTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add New Course/Objective">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New <?php echo $courseLabel; ?>
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAcaCoursesForm();" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="exprtCourseObjctv();" data-toggle="tooltip" title="Export <?php echo $courseLabel; ?>">
                                        <img src="cmn_images/document_export.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="" data-toggle="tooltip" title="Import <?php echo $courseLabel; ?>">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Import
                                    </button>
                                </div>
                            <?php } ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="acaCoursesSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                    echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                    ?>" onkeyup="enterKeyFuncAcaCourses(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS; ?>')">
                                    <input id="acaCoursesPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <input id="sbmtdScmRtrnSrcDocID" type="hidden" value="-1">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaCourses('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaCourses('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaCoursesSrchIn">
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
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaCoursesDsplySze" style="min-width:70px !important;">
                                        <?php
                                        $valslctdArry = array(
                                            "", "", "", "",
                                            "", "", "", ""
                                        );
                                        $dsplySzeArry = array(
                                            1, 5, 10, 15, 30,
                                            50, 100, 500, 1000, 1000000
                                        );
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
                                            <a href="javascript:getAcaCourses('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getAcaCourses('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="acaCoursesHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:35px;width:35px;">No.</th>
                                            <th style="max-width:155px;width:125px;">Type</th>
                                            <th style="max-width:155px;width:155px;">Course/Objective Code</th>
                                            <th style="max-width:305px;width:305px;">Course/Objective Name</th>
                                            <th>Course/Objective Description</th>
                                            <th style="max-width:75px;width:75px;">Enabled?</th>
                                            <?php if ($canDel === true) { ?>
                                                <th style="max-width:30px;width:30px;">...</th>
                                            <?php } ?>
                                            <?php
                                            if ($canVwRcHstry === true) {
                                            ?>
                                                <th style="max-width:30px;width:30px;">...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            $trsctnLnID = (float) $row[0];
                                            $trsctnLnCode = $row[1];
                                            $trsctnLnName = $row[2];
                                            $trsctnLnDesc = $row[3];
                                            $trsctnLnIsEnbld = $row[4];
                                            $trsctnLnRecType = $row[5];
                                            $cntr += 1;
                                            $statusColor = "#000000";
                                            $statusBckgrdColor = "";
                                            if ($trsctnLnIsEnbld == "1") {
                                                $statusBckgrdColor = "#00FF00";
                                            } else {
                                                $statusBckgrdColor = "#FF0000";
                                            }
                                        ?>
                                            <tr id="acaCoursesHdrsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd">
                                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="acaCoursesHdrsRow<?php echo $cntr; ?>_Type" style="width:100% !important;">
                                                        <?php
                                                        //$valslctdArry = array("", "");
                                                        //$srchInsArrys = array("Course", "Objective"); 
                                                        $valslctdArry = array("");
                                                        $srchInsArrys = array("Course");
                                                        if ($mdlACAorPMS == "PMS") {
                                                            $valslctdArry = array("");
                                                            $srchInsArrys = array("Objective");
                                                        }
                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            if ($trsctnLnRecType == $srchInsArrys[$z]) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                        ?>
                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td class="lovtd">
                                                    <input type="hidden" class="form-control" aria-label="..." id="acaCoursesHdrsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                    <?php
                                                    if ($canEdt === true) {
                                                    ?>
                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="acaCoursesHdrsRow<?php echo $cntr; ?>_CourseCode" name="acaCoursesHdrsRow<?php echo $cntr; ?>_CourseCode" value="<?php echo $trsctnLnCode; ?>" style="width:100% !important;">
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnCode; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canEdt === true) {
                                                    ?>
                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="acaCoursesHdrsRow<?php echo $cntr; ?>_CourseNm" name="acaCoursesHdrsRow<?php echo $cntr; ?>_CourseNm" value="<?php echo $trsctnLnName; ?>" style="width:100% !important;">
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnName; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <input type="text" class="form-control" aria-label="..." id="acaCoursesHdrsRow<?php echo $cntr; ?>_CourseDesc" name="acaCoursesHdrsRow<?php echo $cntr; ?>_CourseDesc" value="<?php echo $trsctnLnDesc; ?>" style="width:100% !important;text-align: left;">
                                                </td>
                                                <td class="lovtd" style="text-align:center;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($trsctnLnIsEnbld == "1") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <div class="form-group form-group-sm">
                                                        <div class="form-check" style="font-size: 12px !important;">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" id="acaCoursesHdrsRow<?php echo $cntr; ?>_IsEnbld" name="acaCoursesHdrsRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <?php
                                                if ($canDel === true) {
                                                ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Courses/Objectives" onclick="delAcaCourses('acaCoursesHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php
                                                if ($canVwRcHstry === true) {
                                                ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                echo urlencode(encrypt1(($row[0] . "|aca.aca_courses|course_id"),
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
                            </div>
                        </div>
                    </fieldset>
                </form>
<?php
            } else if ($vwtyp == 3) {
            }
        }
    }
}
?>