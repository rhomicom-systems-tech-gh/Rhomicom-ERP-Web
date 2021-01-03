<?php
$canAdd = test_prmssns($dfltPrvldgs[16], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[17], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[18], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
$exprtFileNmPrt = "GradeScales";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Grade Scale */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo delete_AcaGradeScales($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Grade Scale Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo delete_AcaGradeScalesLn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Grade Scales
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $acaGradeScalesID = isset($_POST['acaGradeScalesID']) ? (int) cleanInputData($_POST['acaGradeScalesID']) : -1;
                $acaGradeScalesName = isset($_POST['acaGradeScalesName']) ? cleanInputData($_POST['acaGradeScalesName']) : "";
                $acaGradeScalesDesc = isset($_POST['acaGradeScalesDesc']) ? cleanInputData($_POST['acaGradeScalesDesc']) : "";
                $acaGradeScalesIsEnbld = isset($_POST['acaGradeScalesIsEnbld']) ? (cleanInputData($_POST['acaGradeScalesIsEnbld']) == "YES" ? TRUE : FALSE) : FALSE;
                $slctdColumnIDs = isset($_POST['slctdColumnIDs']) ? cleanInputData($_POST['slctdColumnIDs']) : "";

                $exitErrMsg = "";
                if ($acaGradeScalesName == "") {
                    $exitErrMsg .= "Please enter Grade Scale Name!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['acaGradeScalesID'] = $acaGradeScalesID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldID = get_GradeScalesID($acaGradeScalesName, $orgID);
                if (($oldID <= 0 || $oldID == $acaGradeScalesID)) {
                    if ($acaGradeScalesID <= 0) {
                        $acaGradeScalesID = getNew_AcaGradeScalesID();
                        create_AcaGradeScales($acaGradeScalesID, $acaGradeScalesName, $acaGradeScalesDesc, $acaGradeScalesIsEnbld);
                        //$acaGradeScalesID = get_GradeScalesID($acaGradeScalesName, $orgID);
                    } else {
                        update_AcaGradeScales($acaGradeScalesID, $acaGradeScalesName, $acaGradeScalesDesc, $acaGradeScalesIsEnbld);
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
                            if (count($crntRow) == 6) {
                                $ln_RecLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_LineName = cleanInputData1($crntRow[1]);
                                $ln_LineDesc = cleanInputData1($crntRow[2]);
                                $ln_GradeGPA = (float) cleanInputData1($crntRow[3]);
                                $ln_GradeMin = (float) cleanInputData1($crntRow[4]);
                                $ln_GradeMax = (float) cleanInputData1($crntRow[5]);
                                $errMsg = "";
                                if ($ln_LineName === "") {
                                    $errMsg = "Row " . ($y + 1) . ":- Grade Code/Name is a required Field!<br/>";
                                }
                                $oldColLnID = get_GradeScalesLnID($ln_LineName, $acaGradeScalesID);
                                if ($errMsg === "") {
                                    if ($ln_RecLnID <= 0 && $oldColLnID <= 0) {
                                        $ln_RecLnID = getNew_AcaGradeScalesLnID();
                                        $afftctd += create_AcaGradeScalesLn($acaGradeScalesID, $acaGradeScalesName, $acaGradeScalesDesc, $acaGradeScalesIsEnbld,
                                                $ln_RecLnID, $ln_LineName, $ln_LineDesc, $ln_GradeGPA, $ln_GradeMin, $ln_GradeMax);
                                    } else if ($ln_RecLnID === $oldColLnID || $oldColLnID <= 0) {
                                        $afftctd += update_AcaGradeScalesLn($acaGradeScalesID, $acaGradeScalesName, $acaGradeScalesDesc, $acaGradeScalesIsEnbld,
                                                $ln_RecLnID, $ln_LineName, $ln_LineDesc, $ln_GradeGPA, $ln_GradeMin, $ln_GradeMax);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }

                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Grade Scale Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Grade Code(s) Saved!"
                                . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Grade Scale Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Grade Code(s) Saved!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['acaGradeScalesID'] = $acaGradeScalesID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Grade Scale Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['acaGradeScalesID'] = $acaGradeScalesID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 1001) {
                //Export Persons
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array("Grade Scale Name**", "Grade Scale Description", "ENABLED?", "Grade Name**", "Grade Description",
                        "GPA VALUE", "Grade Band Min. Value", "Grade Band Max. Value");
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
                    $result = get_AcaGradeScaleLinesExprt(0, $limit_size, "%", "Name");
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        //"" . ($z + 1), 
                        $crntRw = array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6],
                            $row[7]);
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
                    if ($z == 0) {
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
                $pkID = isset($_POST['sbmtdGradeScalesID']) ? $_POST['sbmtdGradeScalesID'] : -1;
                $actionTxt = isset($_POST['actionTxt']) ? $_POST['actionTxt'] : "PasteDirect";
                $destElmntID = isset($_POST['destElmntID']) ? $_POST['destElmntID'] : "acaGradeScalesDetailInfo";
                $titleMsg = isset($_POST['titleMsg']) ? $_POST['titleMsg'] : "";
                $titleElementID = isset($_POST['titleElementID']) ? $_POST['titleElementID'] : "";
                $modalBodyID = isset($_POST['modalBodyID']) ? $_POST['modalBodyID'] : "";
                if ($vwtyp == 0) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&mdl=$mdlACAorPMS');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Grade Scales/Systems</span>
				</li>
                               </ul>
                              </div>";

                    $total = get_Total_AcaGradeScales($srchFor, $srchIn);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_AcaGradeScales($srchFor, $srchIn, $curIdx, $lmtSze);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='acaGradeScalesForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php if ($canAdd === true) { ?> 
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 0px 0px 0px !important;"> 
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAcaGradeScalesForm(-1, 1);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Grade Scale">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Scale
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAcaGradeScalesForm('<?php echo $actionTxt; ?>', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');" style="width:100% !important;">
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
                                    <input class="form-control" id="acaGradeScalesSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAcaGradeScales(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                    <input id="acaGradeScalesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaGradeScales('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaGradeScales('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaGradeScalesSrchIn">
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
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaGradeScalesDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getAcaGradeScales('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAcaGradeScales('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                        <div class="row"> 
                            <div  class="col-md-3" style="padding:0px 1px 0px 15px !important;">
                                <fieldset class="basic_person_fs">                                        
                                    <table class="table table-striped table-bordered table-responsive" id="acaGradeScalesHdrsTable" cellspacing="0" width="100%" style="width:100% !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Grade Scale</th>
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
                                                <tr id="acaGradeScalesRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $row[1]; ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="acaGradeScalesRow<?php echo $cntr; ?>_GradeScalesID" value="<?php echo $row[0]; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="acaGradeScalesRow<?php echo $cntr; ?>_GradeScalesNm" value="<?php echo $row[1]; ?>">
                                                    </td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaGradeScales('acaGradeScalesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Grade Scale">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|aca.aca_grade_scales|scale_id"),
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
                            <div  class="col-md-9" style="padding:0px 15px 0px 1px !important">
                                <fieldset class="basic_person_fs" style="padding-top:2px !important;">
                                    <div class="container-fluid" id="acaGradeScalesDetailInfo">
                                        <?php
                                    }
                                    $acaGradeScalesID = -1;
                                    $acaGradeScalesName = "";
                                    $acaGradeScalesDesc = "";
                                    $acaGradeScalesIsEnbld = "1";
                                    if ($pkID > 0) {
                                        $acaGradeScalesID = $pkID;
                                        $result1 = get_AcaGradeScaleDet($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $acaGradeScalesID = $row1[0];
                                            $acaGradeScalesName = $row1[1];
                                            $acaGradeScalesDesc = $row1[2];
                                            $acaGradeScalesIsEnbld = $row1[3];
                                        }
                                    }
                                    if ($vwtyp != 2) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaGradeScalesName" class="control-label col-lg-4">Grade Scale Name:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="acaGradeScalesName" name="acaGradeScalesName" value="<?php echo $acaGradeScalesName; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="acaGradeScalesID" name="acaGradeScalesID" value="<?php echo $acaGradeScalesID; ?>">
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $acaGradeScalesName; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaGradeScalesIsEnbld" class="control-label col-lg-6">Is Enabled?:</label>
                                                        <div  class="col-lg-6">
                                                            <?php
                                                            $chkdYes = "";
                                                            $chkdNo = "checked=\"\"";
                                                            if ($acaGradeScalesIsEnbld == "1") {
                                                                $chkdNo = "";
                                                                $chkdYes = "checked=\"\"";
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <label class="radio-inline"><input type="radio" name="acaGradeScalesIsEnbld" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                <label class="radio-inline"><input type="radio" name="acaGradeScalesIsEnbld" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo ($acaGradeScalesIsEnbld == "1" ? "YES" : "NO"); ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>   
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="acaGradeScalesDesc" class="control-label col-lg-4">Description:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <textarea class="form-control" rows="2" cols="20" id="acaGradeScalesDesc" name="acaGradeScalesDesc" style="text-align:left !important;width:100% !important;"><?php echo $acaGradeScalesDesc; ?></textarea>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $acaGradeScalesDesc; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
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
                                                            }
                                                            $total = get_Total_AcaGradeScaleLines($acaGradeScalesID, $srchIn, $srchFor);
                                                            if ($pageNo > ceil($total / $lmtSze)) {
                                                                $pageNo = 1;
                                                            } else if ($pageNo < 1) {
                                                                $pageNo = ceil($total / $lmtSze);
                                                            }
                                                            $curIdx = $pageNo - 1;
                                                            $resultRw = get_AcaGradeScaleLines($acaGradeScalesID, $curIdx, $lmtSze, $srchIn, $srchFor);
                                                            if ($vwtyp != 2) {
                                                                $nwRowHtml332 = "<tr id=\"oneAcaGradeScalesSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneAcaGradeScalesSmryLinesTable tr').index(this));\">                                    
                                                                                        <td class=\"lovtd\" style=\"\"><span>New</span></td> 
                                                                                        <td class=\"lovtd\"  style=\"\">  
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAcaGradeScalesSmryRow_WWW123WWW_RecLnID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetRfDc\" aria-label=\"...\" id=\"oneAcaGradeScalesSmryRow_WWW123WWW_LineName\" name=\"oneAcaGradeScalesSmryRow_WWW123WWW_LineName\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAcaGradeScalesSmryRow_WWW123WWW_LineName', 'oneAcaGradeScalesSmryLinesTable', 'jbDetRfDc');\">
                                                                                        </td> 
                                                                                        <td class=\"lovtd\">
                                                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                                                    <textarea class=\"form-control\" aria-label=\"...\" id=\"oneAcaGradeScalesSmryRow_WWW123WWW_LineDesc\" name=\"oneAcaGradeScalesSmryRow_WWW123WWW_LineDesc\" style=\"width:100%;resize:vertical;\" cols=\"7\" rows=\"1\"></textarea>
                                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"popUpDisplay('oneAcaGradeScalesSmryRow_WWW123WWW_LineDesc');\" style=\"max-width:30px;width:30px;\">
                                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>  
                                                                                        <td class=\"lovtd\">
                                                                                            <input type=\"text\" class=\"form-control jbDetGPA rqrdFld\" aria-label=\"...\" id=\"oneAcaGradeScalesSmryRow_WWW123WWW_GradeGPA\" name=\"oneAcaGradeScalesSmryRow_WWW123WWW_GradeGPA\" value=\"\" style=\"width:100% !important;text-align: left;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAcaGradeScalesSmryRow_WWW123WWW_GradeGPA', 'oneAcaGradeScalesSmryLinesTable', 'jbDetGPA');\">                                                    
                                                                                        </td>                                                 
                                                                                        <td class=\"lovtd\">
                                                                                            <input type=\"text\" class=\"form-control jbDetMin rqrdFld\" aria-label=\"...\" id=\"oneAcaGradeScalesSmryRow_WWW123WWW_GradeMin\" name=\"oneAcaGradeScalesSmryRow_WWW123WWW_GradeMin\" value=\"\" style=\"width:100% !important;text-align: left;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAcaGradeScalesSmryRow_WWW123WWW_GradeMin', 'oneAcaGradeScalesSmryLinesTable', 'jbDetMin');\">                                                    
                                                                                        </td>                                                 
                                                                                        <td class=\"lovtd\">
                                                                                            <input type=\"text\" class=\"form-control jbDetMax rqrdFld\" aria-label=\"...\" id=\"oneAcaGradeScalesSmryRow_WWW123WWW_GradeMax\" name=\"oneAcaGradeScalesSmryRow_WWW123WWW_GradeMax\" value=\"\" style=\"width:100% !important;text-align: left;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAcaGradeScalesSmryRow_WWW123WWW_GradeMax', 'oneAcaGradeScalesSmryLinesTable', 'jbDetMax');\">                                                    
                                                                                        </td> 
                                                                                            <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAcaGradeScalesLne('oneAcaGradeScalesSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Grade\">
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
                                                                            <button id="addNwScmAcaGradeScalesSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAcaGradeScalesRows('oneAcaGradeScalesSmryLinesTable', 0, '<?php echo $nwRowHtml32; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Grade Definition">
                                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAcaGradeScalesForm('ReloadDialog', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');">
                                                                                <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="exprtGrdScale();" data-toggle="tooltip" title="Export Grade Scale">
                                                                                <img src="cmn_images/document_export.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button> 
                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="" data-toggle="tooltip" title="Import Grade Scale">
                                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                Import
                                                                            </button>
                                                                        <?php } ?>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAcaGradeScalesForm(<?php echo $acaGradeScalesID; ?>, 1, 'PasteDirect', '<?php echo $destElmntID; ?>', '<?php echo $titleMsg; ?>', '<?php echo $titleElementID; ?>', '<?php echo $modalBodyID; ?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>    
                                                                    </div>
                                                                    <div class="col-md-6 fcltyTypDetNav" style="padding:0px 15px 0px 15px !important;">
                                                                        <div class="input-group">
                                                                            <input class="form-control" id="acaGradeScalesDetSrchFor" type = "text" placeholder="Search For" value="<?php
                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                            ?>" onkeyup="enterKeyFuncAcaGradeScalesDet(event, '', '#acaGradeScalesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdGradeScalesID=<?php echo $acaGradeScalesID; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                                                            <input id="acaGradeScalesDetPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaGradeScalesDet('clear', '#acaGradeScalesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdGradeScalesID=<?php echo $acaGradeScalesID; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                                                                <span class="glyphicon glyphicon-remove"></span>
                                                                            </label>
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaGradeScalesDet('', '#acaGradeScalesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdGradeScalesID=<?php echo $acaGradeScalesID; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                                                                <span class="glyphicon glyphicon-search"></span>
                                                                            </label>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="acaGradeScalesDetSrchIn">
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
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="acaGradeScalesDetDsplySze" style="min-width:70px !important;">                            
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
                                                                                    <a class="rhopagination" href="javascript:getAcaGradeScalesDet('previous', '#acaGradeScalesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdGradeScalesID=<?php echo $acaGradeScalesID; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a class="rhopagination" href="javascript:getAcaGradeScalesDet('next', '#acaGradeScalesDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdGradeScalesID=<?php echo $acaGradeScalesID; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
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
                                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAcaGradeScalesLnsTblSctn"> 
                                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                        <div id="acaGradeScalesDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                        <?php } ?>
                                                        <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                            <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                                <table class="table table-striped table-bordered table-responsive" id="oneAcaGradeScalesSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="max-width:30px;width:30px;">No.</th>
                                                                            <th style="min-width:60px;">Grade Name</th>
                                                                            <th style="min-width:60px;">Grade Description</th>
                                                                            <th style="max-width:80px;width:80px;">GPA Value</th>
                                                                            <th style="max-width:80px;width:80px;">Grade Band Min. Value</th>
                                                                            <th style="max-width:80px;width:80px;">Grade Band Max. Value</th>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>   
                                                                        <?php
                                                                        $mkReadOnly = "";
                                                                        $cntr = 0;
                                                                        while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                            $recLnID = (float) $rowRw[0];
                                                                            $recLnNm = $rowRw[1];
                                                                            $recLnGPA = (float) $rowRw[2];
                                                                            $recLnMin = (float) $rowRw[3];
                                                                            $recLnMax = (float) $rowRw[4];
                                                                            $recLnDesc = $rowRw[5];
                                                                            $cntr += 1;
                                                                            $statusColor = "#000000";
                                                                            $statusBckgrdColor = "";
                                                                            ?>
                                                                            <tr id="oneAcaGradeScalesSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAcaGradeScalesSmryLinesTable tr').index(this));">                                    
                                                                                <td class="lovtd" style=""><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                <td class="lovtd"  style="">  
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_RecLnID" value="<?php echo $recLnID; ?>" style="width:100% !important;">
                                                                                    <?php
                                                                                    if ($canEdt === true) {
                                                                                        ?>
                                                                                        <input type="text" class="form-control rqrdFld jbDetRfDc" aria-label="..." id="oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_LineName" name="oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_LineName" value="<?php echo $recLnNm; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_LineName', 'oneAcaGradeScalesSmryLinesTable', 'jbDetRfDc');">
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $recLnNm; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                                                        <div class="input-group"  style="width:100%;">
                                                                                            <textarea class="form-control" aria-label="..." id="oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_LineDesc" style="width:100%;resize:vertical;" cols="7" rows="1"><?php echo $recLnDesc; ?></textarea>
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_LineDesc');" style="max-width:30px;width:30px;">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>  
                                                                                <td class="lovtd">
                                                                                    <input type="text" class="form-control jbDetGPA rqrdFld" aria-label="..." id="oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_GradeGPA" name="oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_GradeGPA" value="<?php echo $recLnGPA; ?>" style="width:100% !important;text-align: left;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_GradeGPA', 'oneAcaGradeScalesSmryLinesTable', 'jbDetGPA');">                                                    
                                                                                </td>                                                 
                                                                                <td class="lovtd">
                                                                                    <input type="text" class="form-control jbDetMin rqrdFld" aria-label="..." id="oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_GradeMin" name="oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_GradeMin" value="<?php echo $recLnMin; ?>" style="width:100% !important;text-align: left;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_GradeMin', 'oneAcaGradeScalesSmryLinesTable', 'jbDetMin');">                                                    
                                                                                </td>                                                 
                                                                                <td class="lovtd">
                                                                                    <input type="text" class="form-control jbDetMax rqrdFld" aria-label="..." id="oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_GradeMax" name="oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_GradeMax" value="<?php echo $recLnMax; ?>" style="width:100% !important;text-align: left;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAcaGradeScalesSmryRow<?php echo $cntr; ?>_GradeMax', 'oneAcaGradeScalesSmryLinesTable', 'jbDetMax');">                                                    
                                                                                </td>
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcaGradeScalesLne('oneAcaGradeScalesSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Grade">
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
                                                                            <th style="">&nbsp;</th> 
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            $("#acaGradeScalesDetPageNo").val(<?php echo $pageNo; ?>);
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
                                </fieldset>
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