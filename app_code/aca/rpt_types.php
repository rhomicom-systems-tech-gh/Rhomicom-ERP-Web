<?php
$canAdd = test_prmssns($dfltPrvldgs[16], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[17], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[18], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 1;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
$exprtFileNmPrt = "AssessTypes";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Assessment Type */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo delete_AcaAssessTypes($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Assessment Type Column */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo delete_AcaAssessCols($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Assessment Types
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $acaAssessTypesID = isset($_POST['acaAssessTypesID']) ? (int) cleanInputData($_POST['acaAssessTypesID']) : -1;
                $acaAssessTypesName = isset($_POST['acaAssessTypesName']) ? cleanInputData($_POST['acaAssessTypesName']) : "";
                $acaAssessTypesDesc = isset($_POST['acaAssessTypesDesc']) ? cleanInputData($_POST['acaAssessTypesDesc']) : "";
                $acaAssessTypesType = isset($_POST['acaAssessTypesType']) ? cleanInputData($_POST['acaAssessTypesType']) : "";
                $acaAssessTypesLevel = isset($_POST['acaAssessTypesLevel']) ? cleanInputData($_POST['acaAssessTypesLevel']) : "";
                $acaAssessTypesLnkdAssessID = isset($_POST['acaAssessTypesLnkdAssessID']) ? (float) cleanInputData($_POST['acaAssessTypesLnkdAssessID']) : -1;
                $acaAssessTypesGrdScaleID = isset($_POST['acaAssessTypesGrdScaleID']) ? (float) cleanInputData($_POST['acaAssessTypesGrdScaleID']) : -1;

                $acaAssessTypesIsEnbld = isset($_POST['acaAssessTypesIsEnbld']) ? (cleanInputData($_POST['acaAssessTypesIsEnbld']) == "YES" ? TRUE : FALSE) : FALSE;
                $slctdColumnIDs = isset($_POST['slctdColumnIDs']) ? cleanInputData($_POST['slctdColumnIDs']) : "";

                $exitErrMsg = "";
                if ($acaAssessTypesName == "") {
                    $exitErrMsg .= "Please enter Assessment Type Name!<br/>";
                }
                if ($acaAssessTypesType == "") {
                    $exitErrMsg .= "Please enter Assessment Type!<br/>";
                }
                if ($acaAssessTypesLevel == "") {
                    $exitErrMsg .= "Please enter Assessment Level!<br/>";
                }
                if ($acaAssessTypesGrdScaleID <= 0) {
                    $exitErrMsg .= "Please select a Grade Scale!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['acaAssessTypesID'] = $acaAssessTypesID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldID = get_AssessTypesID($acaAssessTypesName, $orgID);
                if (($oldID <= 0 || $oldID == $acaAssessTypesID)) {
                    if ($acaAssessTypesID <= 0) {
                        $acaAssessTypesID = getNew_AcaAssessTypesID();
                        create_AcaAssessTypes($acaAssessTypesID, $acaAssessTypesName, $acaAssessTypesDesc, $acaAssessTypesType, $acaAssessTypesLevel,
                                $acaAssessTypesIsEnbld, $acaAssessTypesLnkdAssessID, $acaAssessTypesGrdScaleID);
                        //$acaAssessTypesID = get_AssessTypesID($acaAssessTypesName, $orgID);
                    } else {
                        update_AcaAssessTypes($acaAssessTypesID, $acaAssessTypesName, $acaAssessTypesDesc, $acaAssessTypesType, $acaAssessTypesLevel,
                                $acaAssessTypesIsEnbld, $acaAssessTypesLnkdAssessID, $acaAssessTypesGrdScaleID);
                    }
                    $afftctd = 0;
                    $afftctd1 = 0;
                    $afftctd2 = 0;
                    if (trim($slctdColumnIDs, "|~") != "") {
                        $variousRows = explode("|", trim($slctdColumnIDs, "|"));
                        //echo count($variousRows);
                        for ($y = 0; $y < count($variousRows); $y++) {
                            //var_dump($crntRow);
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 15) {
                                $ln_RecLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_LineName = cleanInputData1($crntRow[1]);
                                $ln_LineDesc = cleanInputData1($crntRow[2]);
                                $ln_HdrText = cleanInputData1($crntRow[3]);
                                $ln_SectionLoc = cleanInputData1($crntRow[4]);
                                $ln_DataType = cleanInputData1($crntRow[5]);
                                $ln_DataLength = (float) cleanInputData1($crntRow[6]);
                                if ($ln_DataType == "Text" && $ln_DataLength <= 0) {
                                    $ln_DataLength = 200;
                                } else if ($ln_DataType != "Text") {
                                    $ln_DataLength = 30;
                                }
                                $ln_IsEnbld = (cleanInputData1($crntRow[7]) == "YES") ? TRUE : FALSE;
                                $ln_SQLFormular = cleanInputData1($crntRow[8]);
                                $ln_IsFormular = ($ln_SQLFormular == "") ? FALSE : TRUE;
                                $ln_ColNum = (int) cleanInputData1($crntRow[10]);
                                $ln_MinValue = (float) cleanInputData1($crntRow[11]);
                                $ln_MaxValue = (float) cleanInputData1($crntRow[12]);
                                $ln_IsDsplyd = (cleanInputData1($crntRow[13]) == "YES") ? TRUE : FALSE;
                                $ln_CSStyle = cleanInputData1($crntRow[14]);
                                $errMsg = "";
                                if ($ln_HdrText === "" || $ln_DataType == "") {
                                    $errMsg = "Row " . ($y + 1) . ":- Column Header Text and Data Type are all required Fields!<br/>";
                                }
                                $oldColLnID = get_AssessTypesColID($ln_LineName, $orgID);
                                if ($errMsg === "") {
                                    if ($ln_RecLnID <= 0 && $oldColLnID <= 0) {
                                        $ln_RecLnID = getNew_AcaAssessColsID();
                                        $afftctd += create_AcaAssessCols($ln_RecLnID, $acaAssessTypesID, $ln_LineName, $ln_LineDesc, $ln_HdrText, $ln_IsFormular, $ln_SQLFormular, $ln_SectionLoc, $ln_DataType, $ln_DataLength, $ln_IsEnbld, $ln_ColNum, $ln_MinValue, $ln_MaxValue, $ln_IsDsplyd, $ln_CSStyle);
                                    } else if ($ln_RecLnID === $oldColLnID || $oldColLnID <= 0) {
                                        $afftctd += update_AcaAssessCols($ln_RecLnID, $acaAssessTypesID, $ln_LineName, $ln_LineDesc, $ln_HdrText, $ln_IsFormular, $ln_SQLFormular, $ln_SectionLoc, $ln_DataType, $ln_DataLength, $ln_IsEnbld, $ln_ColNum, $ln_MinValue, $ln_MaxValue, $ln_IsDsplyd, $ln_CSStyle);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }

                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Assessment Type Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Column(s) Saved!"
                                . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Assessment Type Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Column(s) Saved!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['acaAssessTypesID'] = $acaAssessTypesID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Assessment Type Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['acaAssessTypesID'] = $acaAssessTypesID;
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
                    $hdngs = array("Assessment Name**", "Description", "Assessment Type**", "Assessment Level**", "Linked Assessment Name",
                        "Linked Grade Scale Name**", "ENABLED?", "Data Storage Col. No.**", "Column Name**", "Column Description",
                        "Header Text/Label**", "Section Located**", "Column Type**", "Min. Value", "Max. Value", "SQL Formula", "HTML/CSS Wrap",
                        "DISPLAYED?");
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
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                                json_encode($arr_content));
                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_AcaAssessTypesExprt(0, $limit_size, "%", "Description");
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        //"" . ($z + 1), 
                        $crntRw = array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10]
                            , $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17]);
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
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                                json_encode($arr_content));
                        $z++;
                    }
                    fclose($opndfile);
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                            json_encode($arr_content));
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
            if ($vwtyp == 0 || $vwtyp == 1 || $vwtyp == 2) {
                $pkID = isset($_POST['sbmtdAssessTypesID']) ? $_POST['sbmtdAssessTypesID'] : -1;
                $actionTxt = isset($_POST['actionTxt']) ? $_POST['actionTxt'] : "PasteDirect";
                $destElmntID = isset($_POST['destElmntID']) ? $_POST['destElmntID'] : "acaAssessTypesDetailInfo";
                $titleMsg = isset($_POST['titleMsg']) ? $_POST['titleMsg'] : "";
                $titleElementID = isset($_POST['titleElementID']) ? $_POST['titleElementID'] : "";
                $modalBodyID = isset($_POST['modalBodyID']) ? $_POST['modalBodyID'] : "";
                if ($vwtyp == 0) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&mdl=$mdlACAorPMS');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Assessment Types</span>
				</li>
                               </ul>
                              </div>";

                    $total = get_Total_AcaAssessTypes($srchFor, $srchIn);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_AcaAssessTypes($srchFor, $srchIn, $curIdx, $lmtSze);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='acaAssessTypesForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php if ($canAdd === true) { ?> 
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 0px 0px 0px !important;"> 
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAcaAssessTypesForm(-1, 1);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Assessment Type">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Assessment
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAcaAssessTypesForm('<?php echo $actionTxt; ?>', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');" style="width:100% !important;">
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
                                    <input class="form-control" id="acaAssessTypesSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAcaAssessTypes(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                    <input id="acaAssessTypesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaAssessTypes('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaAssessTypes('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaAssessTypesSrchIn">
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
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaAssessTypesDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getAcaAssessTypes('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAcaAssessTypes('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                        <div class="row"> 
                            <div  class="col-md-12" style="padding:0px 15px 0px 15px !important;">
                                <fieldset class="basic_person_fs123">                                        
                                    <table class="table table-striped table-bordered table-responsive" id="acaAssessTypesHdrsTable" cellspacing="0" width="100%" style="width:100% !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Assessment Type</th>
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
                                                <tr id="acaAssessTypesRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $row[1]; ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="acaAssessTypesRow<?php echo $cntr; ?>_AssessTypesID" value="<?php echo $row[0]; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="acaAssessTypesRow<?php echo $cntr; ?>_AssessTypesNm" value="<?php echo $row[1]; ?>">
                                                    </td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaAssessTypes('acaAssessTypesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Assessment Type">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|aca.aca_assessment_types|assmnt_typ_id"),
                                                                            $smplTokenWord1));
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
                            <div  class="col-md-12" style="padding:0px 15px 0px 15px !important">
                                <div class="container-fluid" id="acaAssessTypesDetailInfo">
                                    <?php
                                }
                                $acaAssessTypesID = -1;
                                $acaAssessTypesName = "";
                                $acaAssessTypesDesc = "";
                                $acaAssessTypesType = "";
                                $acaAssessTypesIsEnbld = "1";
                                $acaAssessTypesLevel = "";
                                $acaAssessTypesLnkdAssessID = -1;
                                $acaAssessTypesLnkdAssessNm = "";
                                $acaAssessTypesGrdScaleID = -1;
                                $acaAssessTypesGrdScaleNm = "";
                                if ($pkID > 0) {
                                    $acaAssessTypesID = $pkID;
                                    $result1 = get_AcaAssessTypeDet($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $acaAssessTypesID = $row1[0];
                                        $acaAssessTypesName = $row1[1];
                                        $acaAssessTypesDesc = $row1[2];
                                        $acaAssessTypesType = $row1[3];
                                        $acaAssessTypesIsEnbld = $row1[4];
                                        $acaAssessTypesLevel = $row1[5];
                                        $acaAssessTypesLnkdAssessID = $row1[6];
                                        $acaAssessTypesLnkdAssessNm = $row1[7];
                                        $acaAssessTypesGrdScaleID = $row1[8];
                                        $acaAssessTypesGrdScaleNm = $row1[9];
                                    }
                                }
                                if ($vwtyp != 2) {
                                    ?>
                                    <div class="row">
                                        <fieldset class="basic_person_fs" style="padding-top:2px !important;">
                                            <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                <fieldset class="basic_person_fs123" style=""> 
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaAssessTypesName" class="control-label col-lg-4">Assessment Name:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="acaAssessTypesName" name="acaAssessTypesName" value="<?php echo $acaAssessTypesName; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="acaAssessTypesID" name="acaAssessTypesID" value="<?php echo $acaAssessTypesID; ?>">
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $acaAssessTypesName; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaAssessTypesDesc" class="control-label col-lg-4">Description:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <textarea class="form-control" rows="3" cols="20" id="acaAssessTypesDesc" name="acaAssessTypesDesc" style="text-align:left !important;width:100% !important;"><?php echo $acaAssessTypesDesc; ?></textarea>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $acaAssessTypesDesc; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaAssessTypesType" class="control-label col-lg-4">Assessment Type:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="acaAssessTypesType" style="min-width:70px !important;">                            
                                                                    <?php
                                                                    $valslctdArry = array("", "");
                                                                    $dsplySzeArry = array("Assessment Sheet Per Group", "Summary Report Per Person");
                                                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                        if ($acaAssessTypesType == $dsplySzeArry[$y]) {
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
                                                                <span><?php echo $acaAssessTypesType; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div  class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                <fieldset class="basic_person_fs123" style="">                       
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaAssessTypesLevel" class="control-label col-md-4">Assessment Level:</label>
                                                        <div  class="col-md-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="acaAssessTypesLevel" style="min-width:70px !important;">                            
                                                                    <?php
                                                                    $valslctdArry = array("", "");
                                                                    $dsplySzeArry = array("Course/Objective", "Subject/Target");
                                                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                        if ($acaAssessTypesLevel == $dsplySzeArry[$y]) {
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
                                                                <span><?php echo $acaAssessTypesLevel; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>                        
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaAssessTypesLnkdAssessNm" class="control-label col-md-4">Linked Assessment:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <input class="form-control" id="acaAssessTypesLnkdAssessNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" value="<?php echo $acaAssessTypesLnkdAssessNm; ?>" readonly="true"/>
                                                                <input type="hidden" id="acaAssessTypesLnkdAssessID" value="<?php echo $acaAssessTypesLnkdAssessID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Types', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaAssessTypesLnkdAssessID', 'acaAssessTypesLnkdAssessNm', 'clear', 1, '', function () {});">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaAssessTypesGrdScaleNm" class="control-label col-md-4">Linked Grade Scale:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <input class="form-control" id="acaAssessTypesGrdScaleNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" value="<?php echo $acaAssessTypesGrdScaleNm; ?>" readonly="true"/>
                                                                <input type="hidden" id="acaAssessTypesGrdScaleID" value="<?php echo $acaAssessTypesGrdScaleID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Grade Scales/Schemes', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaAssessTypesGrdScaleID', 'acaAssessTypesGrdScaleNm', 'clear', 1, '', function () {});">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>                            
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaAssessTypesIsEnbld" class="control-label col-lg-6">Is Enabled?:</label>
                                                        <div  class="col-lg-6">
                                                            <?php
                                                            $chkdYes = "";
                                                            $chkdNo = "checked=\"\"";
                                                            if ($acaAssessTypesIsEnbld == "1") {
                                                                $chkdNo = "";
                                                                $chkdYes = "checked=\"\"";
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <label class="radio-inline"><input type="radio" name="acaAssessTypesIsEnbld" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                <label class="radio-inline"><input type="radio" name="acaAssessTypesIsEnbld" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo ($acaAssessTypesIsEnbld == "1" ? "YES" : "NO"); ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>   
                                                </fieldset>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                            <div class="custDiv" style="padding:0px !important;min-height: 30px !important;border-top: 1px solid #eee !important;"> 
                                                <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php
                                                        }
                                                        if ($vwtyp == 0 || $vwtyp == 1) {
                                                            $srchFor = "%";
                                                            $srchIn = "Name";
                                                            $pageNo = 1;
                                                            $lmtSze = 30;
                                                        } else {
                                                            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                                                        }
                                                        $total = get_Ttl_AcaAssessCols($acaAssessTypesID, $srchIn, $srchFor);
                                                        if ($pageNo > ceil($total / $lmtSze)) {
                                                            $pageNo = 1;
                                                        } else if ($pageNo < 1) {
                                                            $pageNo = ceil($total / $lmtSze);
                                                        }
                                                        $curIdx = $pageNo - 1;
                                                        $resultRw = get_AcaAssessCols($acaAssessTypesID, $curIdx, $lmtSze, $srchIn, $srchFor);
                                                        if ($vwtyp != 2) {
                                                            $nwRowHtml332 = "<tr id=\"oneAcaAssessTypesSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneAcaAssessTypesSmryLinesTable tr').index(this));\">                                    
                                                                                        <td class=\"lovtd\" style=\"\"><span>New</span></td>
                                                                                        <td class=\"lovtd\">
                                                                                            <input min-rhodata=\"1\" max-rhodata=\"50\" type=\"text\" class=\"form-control assesScoreNum rqrdFld\" aria-label=\"...\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_ColNum\" name=\"oneAcaAssessTypesSmryRow_WWW123WWW_ColNum\" value=\"1\" style=\"width:100% !important;text-align: left;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAcaAssessTypesSmryRow_WWW123WWW_ColNum', 'oneAcaAssessTypesSmryLinesTable', 'assesScoreNum');\" onblur=\"vldtAssessColNumFld('oneAcaAssessTypesSmryRow_WWW123WWW_ColNum');\">                                                    
                                                                                        </td> 
                                                                                        <td class=\"lovtd\"  style=\"\">  
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_RecLnID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetRfDc\" aria-label=\"...\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_LineName\" name=\"oneAcaAssessTypesSmryRow_WWW123WWW_LineName\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAcaAssessTypesSmryRow_WWW123WWW_LineName', 'oneAcaAssessTypesSmryLinesTable', 'jbDetRfDc');\">
                                                                                        </td> 
                                                                                        <td class=\"lovtd\">
                                                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                                                    <textarea class=\"form-control\" aria-label=\"...\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_LineDesc\" name=\"oneAcaAssessTypesSmryRow_WWW123WWW_LineDesc\" style=\"width:100%;resize:vertical;\" cols=\"7\" rows=\"1\"></textarea>
                                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"popUpDisplay('oneAcaAssessTypesSmryRow_WWW123WWW_LineDesc');\" style=\"max-width:30px;width:30px;\">
                                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td> 
                                                                                        <td class=\"lovtd\">
                                                                                            <input type=\"text\" class=\"form-control jbDetDesc rqrdFld\" aria-label=\"...\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_HdrText\" name=\"oneAcaAssessTypesSmryRow_WWW123WWW_HdrText\" value=\"\" style=\"width:100% !important;text-align: left;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAcaAssessTypesSmryRow_WWW123WWW_HdrText', 'oneAcaAssessTypesSmryLinesTable', 'jbDetDesc');\">                                                    
                                                                                        </td> 
                                                                                        <td class=\"lovtd\">
                                                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select rqrdFld\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_SectionLoc\" style=\"width:100% !important;\">";
                                                            $valslctdArry = array("", "", "");
                                                            $srchInsArrys = array("01-Header", "02-Detail", "03-Footer");
                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                $nwRowHtml332 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                            }
                                                            $nwRowHtml332 .= "</select>
                                                                                        </td>  
                                                                                        <td class=\"lovtd\">
                                                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select rqrdFld\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_DataType\" style=\"width:100% !important;\">";
                                                            $valslctdArry = array("", "", "", "");
                                                            $srchInsArrys = array("Number", "Text", "Date", "LastToCompute");
                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                $nwRowHtml332 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                            }
                                                            $nwRowHtml332 .= "</select>
                                                                                        </td>  
                                                                                        <td class=\"lovtd\" style=\"text-align: right;display:none;\">
                                                                                            <input type=\"text\" class=\"form-control jbDetAccRate\" aria-label=\"...\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_DataLength\" name=\"oneAcaAssessTypesSmryRow_WWW123WWW_DataLength\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAcaAssessTypesSmryRow_WWW123WWW_DataLength', 'oneAcaAssessTypesSmryLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\">                                                    
                                                                                        </td> 
                                                                                        <td class=\"lovtd\" style=\"text-align: right;\">
                                                                                            <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_MinValue\" name=\"oneAcaAssessTypesSmryRow_WWW123WWW_MinValue\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAcaAssessTypesSmryRow_WWW123WWW_MinValue', 'oneAcaAssessTypesSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\">                                                    
                                                                                        </td> 
                                                                                        <td class=\"lovtd\" style=\"text-align: right;\">
                                                                                            <input type=\"text\" class=\"form-control jbDetCrdt\" aria-label=\"...\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_MaxValue\" name=\"oneAcaAssessTypesSmryRow_WWW123WWW_MaxValue\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAcaAssessTypesSmryRow_WWW123WWW_MaxValue', 'oneAcaAssessTypesSmryLinesTable', 'jbDetCrdt');\" style=\"width:100% !important;text-align: right;\">                                                    
                                                                                        </td>                                           
                                                                                        <td class=\"lovtd\" style=\"text-align:center;display:none;\">
                                                                                            <div class=\"form-group form-group-sm\">
                                                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                                    <label class=\"form-check-label\">
                                                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_IsFormular\" name=\"oneAcaAssessTypesSmryRow_WWW123WWW_IsFormular\">
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>                                             
                                                                                        <td class=\"lovtd\" style=\"text-align:center;display:none;\">
                                                                                            <div class=\"form-group form-group-sm\">
                                                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                                    <label class=\"form-check-label\">
                                                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_IsEnabled\" name=\"oneAcaAssessTypesSmryRow_WWW123WWW_IsEnabled\" checked=\"true\">
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td class=\"lovtd\">
                                                                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                                    <div class=\"input-group\"  style=\"width:100%;\">
                                                                                                        <textarea class=\"form-control\" aria-label=\"...\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_SQLFormular\" name=\"oneAcaAssessTypesSmryRow_WWW123WWW_SQLFormular\" style=\"width:100%;resize:vertical;\" cols=\"7\" rows=\"1\"></textarea>
                                                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"popUpDisplay('oneAcaAssessTypesSmryRow_WWW123WWW_SQLFormular');\" style=\"max-width:30px;width:30px;\">
                                                                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td class=\"lovtd\">
                                                                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                                    <div class=\"input-group\"  style=\"width:100%;\">
                                                                                                        <textarea class=\"form-control\" aria-label=\"...\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_CSStyle\" name=\"oneAcaAssessTypesSmryRow_WWW123WWW_CSStyle\" style=\"width:100%;resize:vertical;\" cols=\"7\" rows=\"1\"><span style=\"color:black;font-weight:normal;\">{:p_col_value}</span></textarea>
                                                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"popUpDisplay('oneAcaAssessTypesSmryRow_WWW123WWW_CSStyle');\" style=\"max-width:30px;width:30px;\">
                                                                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                        </td>                                             
                                                                                        <td class=\"lovtd\" style=\"text-align:center;\">
                                                                                            <div class=\"form-group form-group-sm\">
                                                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                                    <label class=\"form-check-label\">
                                                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"oneAcaAssessTypesSmryRow_WWW123WWW_IsDsplyd\" name=\"oneAcaAssessTypesSmryRow_WWW123WWW_IsDsplyd\" checked=\"true\">
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAcaAssessTypesLne('oneAcaAssessTypesSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Assessment Column\">
                                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                            </button>
                                                                                        </td>  
                                                                                        </tr>";
                                                            $nwRowHtml32 = urlencode($nwRowHtml332);
                                                            ?> 
                                                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                <div class="col-md-4" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                    <?php
                                                                    if ($canEdt === true) {
                                                                        ?>
                                                                        <input type="hidden" id="nwSalesDocLineHtm" value="<?php echo $nwRowHtml32; ?>">
                                                                        <button id="addNwScmAcaAssessTypesSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAcaAssessTypesRows('oneAcaAssessTypesSmryLinesTable', 0, '<?php echo $nwRowHtml32; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Column Definition">
                                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAcaAssessTypesForm('ReloadDialog', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');">
                                                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="exprtAssessTypes();" data-toggle="tooltip" title="Export Assessment Columns">
                                                                            <img src="cmn_images/document_export.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button> 
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="" data-toggle="tooltip" title="Import Assessment Columns">
                                                                            <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            Import
                                                                        </button>
                                                                    <?php } ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAcaAssessTypesForm(<?php echo $acaAssessTypesID; ?>, 1, 'PasteDirect', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>    
                                                                </div>
                                                                <div class="col-md-6 fcltyTypDetNav" style="padding:0px 15px 0px 15px !important;">
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="acaAssessTypesDetSrchFor" type = "text" placeholder="Search For" value="<?php
                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                        ?>" onkeyup="enterKeyFuncAcaAssessTypesDet(event, '', '#acaAssessTypesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAssessTypesID=<?php echo $acaAssessTypesID; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                                                        <input id="acaAssessTypesDetPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaAssessTypesDet('clear', '#acaAssessTypesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAssessTypesID=<?php echo $acaAssessTypesID; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                                                            <span class="glyphicon glyphicon-remove"></span>
                                                                        </label>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaAssessTypesDet('', '#acaAssessTypesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAssessTypesID=<?php echo $acaAssessTypesID; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                                                            <span class="glyphicon glyphicon-search"></span>
                                                                        </label>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="acaAssessTypesDetSrchIn">
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
                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="acaAssessTypesDetDsplySze" style="min-width:70px !important;">                            
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
                                                                                <a class="rhopagination" href="javascript:getAcaAssessTypesDet('previous', '#acaAssessTypesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAssessTypesID=<?php echo $acaAssessTypesID; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                                                                    <span aria-hidden="true">&laquo;</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a class="rhopagination" href="javascript:getAcaAssessTypesDet('next', '#acaAssessTypesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAssessTypesID=<?php echo $acaAssessTypesID; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
                                                                                    <span aria-hidden="true">&raquo;</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </nav>
                                                                </div>                  
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAcaAssessTypesLnsTblSctn"> 
                                                <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                    <div id="acaAssessTypesDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                    <?php } ?>
                                                    <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                        <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                            <table class="table table-striped table-bordered table-responsive" id="oneAcaAssessTypesSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="max-width:25px;width:25px;">No.</th>
                                                                        <th style="max-width:40px;width:40px;">Data Storage Col. No. [1-50]</th>
                                                                        <th style="min-width:60px;">Column Name</th>
                                                                        <th style="min-width:100px;">Column Description</th>
                                                                        <th style="min-width:70px;">Header Text/Label</th>
                                                                        <th style="min-width:70px;">Section Located</th>
                                                                        <th style="max-width:50px;width:50px;">Column Type</th>
                                                                        <th style="max-width:40px;width:40px;display:none;">Data Length</th>
                                                                        <th style="max-width:40px;width:40px;">Min. Value</th>
                                                                        <th style="max-width:40px;width:40px;">Max. Value</th>
                                                                        <th style="max-width:60px;width:60px;text-align: center;display:none;">Formula Based?</th>
                                                                        <th style="max-width:25px;width:25px;text-align: center;display:none;">...</th>
                                                                        <th style="min-width:120px;">SQL Formula</th>
                                                                        <th style="min-width:120px;">HTML/CSS Style Wrap</th>
                                                                        <th style="max-width:50px;width:50px;text-align: center;">Displayed?</th>
                                                                        <th style="max-width:25px;width:25px;text-align: center;">...</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>   
                                                                    <?php
                                                                    $mkReadOnly = "";
                                                                    $cntr = 0;
                                                                    while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                        $recLnID = (float) $rowRw[0];
                                                                        $recLnNm = $rowRw[1];
                                                                        $recLnDesc = $rowRw[2];
                                                                        $recLnHdrText = $rowRw[3];
                                                                        $recLnSection = $rowRw[4];
                                                                        $recLnDataTyp = $rowRw[5];
                                                                        $recLnDataLength = (float) $rowRw[6];
                                                                        $recLnIsFrmlar = $rowRw[7];
                                                                        $recLnColFrmlar = $rowRw[8];
                                                                        $recLnIsEnbld = $rowRw[9];
                                                                        $recLnColNum = (int) $rowRw[10];
                                                                        $recLnMinVal = (float) $rowRw[11];
                                                                        $recLnMaxVal = (float) $rowRw[12];
                                                                        $recLnIsDsplyd = $rowRw[13];
                                                                        $htmlCssStyle = $rowRw[14];
                                                                        $cntr += 1;
                                                                        $statusColor = "#000000";
                                                                        $statusBckgrdColor = "";
                                                                        ?>
                                                                        <tr id="oneAcaAssessTypesSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAcaAssessTypesSmryLinesTable tr').index(this));">                                    
                                                                            <td class="lovtd" style=""><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>  
                                                                            <td class="lovtd">
                                                                                <input min-rhodata="1" max-rhodata="50" type="text" class="form-control assesScoreNum rqrdFld" aria-label="..." id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_ColNum" name="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_ColNum" value="<?php echo $recLnColNum; ?>" style="width:100% !important;text-align: left;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_ColNum', 'oneAcaAssessTypesSmryLinesTable', 'assesScoreNum');" onblur="vldtAssessColNumFld('oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_ColNum');">                                                    
                                                                            </td>                                               
                                                                            <td class="lovtd"  style="">  
                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_RecLnID" value="<?php echo $recLnID; ?>" style="width:100% !important;">
                                                                                <?php
                                                                                if ($canEdt === true) {
                                                                                    ?>
                                                                                    <input type="text" class="form-control rqrdFld jbDetRfDc" aria-label="..." id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_LineName" name="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_LineName" value="<?php echo $recLnNm; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_LineName', 'oneAcaAssessTypesSmryLinesTable', 'jbDetRfDc');">
                                                                                <?php } else {
                                                                                    ?>
                                                                                    <span><?php echo $trsctnLnDesc; ?></span>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td class="lovtd">
                                                                                <div class="form-group form-group-sm" style="width:100% !important;">
                                                                                    <div class="input-group"  style="width:100%;">
                                                                                        <textarea class="form-control" aria-label="..." id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_LineDesc" style="width:100%;resize:vertical;" cols="7" rows="1"><?php echo $recLnDesc; ?></textarea>
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_LineDesc');" style="max-width:30px;width:30px;">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </td> 
                                                                            <td class="lovtd">
                                                                                <input type="text" class="form-control jbDetDesc rqrdFld" aria-label="..." id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_HdrText" name="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_HdrText" value="<?php echo $recLnHdrText; ?>" style="width:100% !important;text-align: left;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_HdrText', 'oneAcaAssessTypesSmryLinesTable', 'jbDetDesc');">                                                    
                                                                            </td> 
                                                                            <td class="lovtd">
                                                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_SectionLoc" style="width:100% !important;">
                                                                                    <?php
                                                                                    $valslctdArry = array("", "", "");
                                                                                    $srchInsArrys = array("01-Header", "02-Detail", "03-Footer");
                                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                        if ($recLnSection == $srchInsArrys[$z]) {
                                                                                            $valslctdArry[$z] = "selected";
                                                                                        }
                                                                                        ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </td>  
                                                                            <td class="lovtd">
                                                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_DataType" style="width:100% !important;">
                                                                                    <?php
                                                                                    $valslctdArry = array("", "", "", "");
                                                                                    $srchInsArrys = array("Number", "Text", "Date", "LastToCompute");
                                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                        if ($recLnDataTyp == $srchInsArrys[$z]) {
                                                                                            $valslctdArry[$z] = "selected";
                                                                                        }
                                                                                        ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </td>  
                                                                            <td class="lovtd" style="text-align: right;display:none;">
                                                                                <input type="text" class="form-control jbDetAccRate" aria-label="..." id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_DataLength" name="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_DataLength" value="<?php
                                                                                echo $recLnDataLength;
                                                                                ?>" onkeypress="gnrlFldKeyPress(event, 'oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_DataLength', 'oneAcaAssessTypesSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;">                                                    
                                                                            </td>   
                                                                            <td class="lovtd" style="text-align: right;">
                                                                                <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_MinValue" name="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_MinValue" value="<?php
                                                                                echo $recLnMinVal;
                                                                                ?>" onkeypress="gnrlFldKeyPress(event, 'oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_MinValue', 'oneAcaAssessTypesSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;">                                                    
                                                                            </td>   
                                                                            <td class="lovtd" style="text-align: right;">
                                                                                <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_MaxValue" name="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_MaxValue" value="<?php
                                                                                echo $recLnMaxVal;
                                                                                ?>" onkeypress="gnrlFldKeyPress(event, 'oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_MaxValue', 'oneAcaAssessTypesSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;">                                                    
                                                                            </td>                                           
                                                                            <td class="lovtd" style="text-align:center;display:none;">
                                                                                <?php
                                                                                $isChkd = "";
                                                                                if ($recLnIsFrmlar == "1") {
                                                                                    $isChkd = "checked=\"true\"";
                                                                                }
                                                                                ?>
                                                                                <div class="form-group form-group-sm">
                                                                                    <div class="form-check" style="font-size: 12px !important;">
                                                                                        <label class="form-check-label">
                                                                                            <input type="checkbox" class="form-check-input" id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_IsFormular" name="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_IsFormular" <?php echo $isChkd ?>>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>                                             
                                                                            <td class="lovtd" style="text-align:center;display:none;">
                                                                                <?php
                                                                                $isChkd = "";
                                                                                if ($recLnIsEnbld == "1") {
                                                                                    $isChkd = "checked=\"true\"";
                                                                                }
                                                                                ?>
                                                                                <div class="form-group form-group-sm">
                                                                                    <div class="form-check" style="font-size: 12px !important;">
                                                                                        <label class="form-check-label">
                                                                                            <input type="checkbox" class="form-check-input" id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_IsEnabled" name="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?>>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td class="lovtd">
                                                                                <?php if ($canEdt === true) { ?>
                                                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                                                        <div class="input-group"  style="width:100%;">
                                                                                            <textarea class="form-control" aria-label="..." id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_SQLFormular" name="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_SQLFormular" style="width:100%;resize:vertical;" cols="7" rows="1"><?php echo $recLnColFrmlar; ?></textarea>
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_SQLFormular');" style="max-width:30px;width:30px;">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <span class=""><?php echo $recLnColFrmlar; ?></span>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td class="lovtd">
                                                                                <?php if ($canEdt === true) { ?>
                                                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                                                        <div class="input-group"  style="width:100%;">
                                                                                            <textarea class="form-control" aria-label="..." id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_CSStyle" name="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_CSStyle" style="width:100%;resize:vertical;" cols="7" rows="1"><?php echo $htmlCssStyle; ?></textarea>
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_CSStyle');" style="max-width:30px;width:30px;">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <span class=""><?php echo $htmlCssStyle; ?></span>
                                                                                <?php } ?>
                                                                            </td>                                             
                                                                            <td class="lovtd" style="text-align:center;">
                                                                                <?php
                                                                                $isChkd = "";
                                                                                if ($recLnIsDsplyd == "1") {
                                                                                    $isChkd = "checked=\"true\"";
                                                                                }
                                                                                ?>
                                                                                <div class="form-group form-group-sm">
                                                                                    <div class="form-check" style="font-size: 12px !important;">
                                                                                        <label class="form-check-label">
                                                                                            <input type="checkbox" class="form-check-input" id="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_IsDsplyd" name="oneAcaAssessTypesSmryRow<?php echo $cntr; ?>_IsDsplyd" <?php echo $isChkd ?>>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td class="lovtd" style="text-align: center;">
                                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaAssessTypesLne('oneAcaAssessTypesSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Assessment Column">
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
                                                                        <th>&nbsp;</th>
                                                                        <th>&nbsp;</th>
                                                                        <th>&nbsp;</th>
                                                                        <th>&nbsp;</th>
                                                                        <th>&nbsp;</th>
                                                                        <th style="display:none;">&nbsp;</th>
                                                                        <th>&nbsp;</th>
                                                                        <th style="">&nbsp;</th>                                           
                                                                        <th style="display:none;">&nbsp;</th>                                           
                                                                        <th style="display:none;">&nbsp;</th>
                                                                        <th style="">&nbsp;</th>
                                                                        <th style="">&nbsp;</th>
                                                                        <th style="">&nbsp;</th>
                                                                        <th style="">&nbsp;</th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        $("#acaAssessTypesDetPageNo").val(<?php echo $pageNo; ?>);
                                                    </script>
                                                    <?php if ($vwtyp != 2) { ?>
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
                            </div>
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}    