<?php
$canAddRecs = test_prmssns($dfltPrvldgs[5], $mdlNm);
$canEdtRecs = test_prmssns($dfltPrvldgs[6], $mdlNm);
$canDelRecs = test_prmssns($dfltPrvldgs[7], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Process Runner */
                $canDelRnnr = test_prmssns($dfltPrvldgs[1], $mdlNm);
                $rnnrID = isset($_POST['rnnrID']) ? cleanInputData($_POST['rnnrID']) : -1;
                $rnnrIDDesc = isset($_POST['rnnrIDDesc']) ? cleanInputData($_POST['rnnrIDDesc']) : "";
                if ($canDelRecs) {
                    $affctd1 = deleteGnrlRecs($rnnrID, "rpt.rpt_prcss_rnnrs", "prcss_rnnr_id", $rnnrIDDesc);
                    if ($affctd1 > 0) {
                        $dsply = "Successfully Deleted the ff Records-";
                        $dsply .= "<br/>$affctd1 Process Runner(s)!";
                        echo "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                    } else {
                        $dsply = "No Record Deleted!";
                        echo "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                    }
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Process Runners
                header("content-type:application/json");
                $slctdPrcsRnnrs = isset($_POST['slctdPrcsRnnrs']) ? cleanInputData($_POST['slctdPrcsRnnrs']) : '';
                $errMsg = "";
                if ($slctdPrcsRnnrs != "") {
                    //Save Process Runners
                    $affctd = 0;
                    $variousRows = explode("|", trim($slctdPrcsRnnrs, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 5) {
                            $rnnrID = (float) cleanInputData1($crntRow[0]);
                            $rnnrNm = cleanInputData1($crntRow[1]);
                            $rnnrDesc = cleanInputData1($crntRow[2]);
                            $rnnrFileNm = cleanInputData1($crntRow[3]);
                            $rnnrPrty = cleanInputData1($crntRow[4]);
                            $lstActvTm = "2013-01-01 00:00:00";
                            $old_id = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", $rnnrNm);
                            if ($old_id > 0) {
                                $rnnrID = $old_id;
                            }
                            if ($rnnrID > 0) {
                                $affctd += updatePrcsRnnrNm($rnnrID, $rnnrNm, $rnnrDesc, $rnnrFileNm, $rnnrPrty);
                            } else {
                                $affctd += createPrcsRnnr($rnnrNm, $rnnrDesc, $lstActvTm, "Not Running", $rnnrPrty, $rnnrFileNm);
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $affctd . " Process Runner(s) Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                header("content-type:application/json");
                $canDelRnnr = test_prmssns($dfltPrvldgs[1], $mdlNm);
                $rnnrID = isset($_POST['rnnrID']) ? cleanInputData($_POST['rnnrID']) : -1;
                $shdStart = isset($_POST['shdStart']) ? (float) cleanInputData($_POST['shdStart']) : 0;
                $rnnrNm = isset($_POST['rnnrNm']) ? cleanInputData($_POST['rnnrNm']) : "REQUESTS LISTENER PROGRAM-JAVA";
                if ($rnnrNm == "GET_MAIN_RUNNER") {
                    $rnnrNm = "REQUESTS LISTENER PROGRAM-JAVA";
                    $rnnrID = (int) getGnrlRecNm2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", $rnnrNm);
                }
                $isRnng = isRunnrRnng($rnnrNm);
                $rptRunID = -1;
                $errMsg = "";
                if ($isRnng === FALSE && $shdStart == 1) {
                    updatePrcsRnnrCmd($rnnrNm, "0");
                    $rnnrPrcsFile = getGnrlRecNm("rpt.rpt_prcss_rnnrs", "prcss_rnnr_id", "executbl_file_nm", $rnnrID);
                    $rnnrPrcsFile = $ftp_base_db_fldr . str_replace("\\", "/", $rnnrPrcsFile);
                    //PHP Command to start jar file
                    $strArgs = "\"" . $host . "\" " .
                        "\"" . $port . "\" " .
                        "\"" . $db_usr . "\" " .
                        "\"" . $db_pwd . "\" " .
                        "\"" . $database . "\" " .
                        "\"" . $rnnrNm . "\" " .
                        "\"" . $rptRunID . "\" " .
                        "\"" . $ftp_base_db_fldr . "/bin" . "\" " .
                        "WEB" . " " .
                        "\"" . $ftp_base_db_fldr . "\" " .
                        "\"" . $app_url . "\"";
                    $cmd = "java -jar " . $rnnrPrcsFile . " " . $strArgs;
                    //"java -Xms64m -Xmx4096m -jar "
                    $rptRunID = getRandomNum(9999999, 99999999);
                    $logfilenm = $ftp_base_db_fldr . "/Logs/cmnd_line_logs_" . $rptRunID . "_" . getDB_Date_timeYYMDHMS() . ".txt";
                    logSessionErrs(str_replace($db_pwd, "*****************", $cmd));
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Process Runner Start Successfully attempted!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    session_write_close();

                    $rhoAPIhost = parse_url($rhoAPIUrl, PHP_URL_HOST);
                    $rhoAPIport = parse_url($rhoAPIUrl, PHP_URL_PORT);

                    $errno = 0;
                    $errstr = "";
                    set_error_handler("rhoErrorHandler3");
                    $rc = @fsockopen($rhoAPIhost, $rhoAPIport, $errno, $errstr, 1);
                    if (is_resource($rc)) {
                        $rslt = rhoPOSTToAPI(
                            $rhoAPIUrl . '/startJavaRunner',
                            array(
                                'rnnrPrcsFile' => $rnnrPrcsFile,
                                'strArgs' => $strArgs
                            )
                        );
                    } else {
                        execInBackground($cmd, $logfilenm);
                    }
                    logSessionErrs($logfilenm);
                    exit();
                } else if ($shdStart == 0) {
                    updatePrcsRnnrCmd($rnnrNm, "1");
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Process Runner Stop Successfully attempted!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else if ($isRnng) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Process Runner already Running!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Unknown Error Occurred!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 3) {
                sleep(5);
                $isRnng = isRunnrRnng($rnnrNm);
                $cntr = 0;
                do {
                    sleep(5);
                    $isRnng = isRunnrRnng($rnnrNm);
                    $cntr++;
                } while ($isRnng === FALSE || $cntr <= 4);
                if ($isRnng === TRUE) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Process Runner Successfully Started!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Failed to start Process Runner!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 4) {

                sleep(10);
                $isRnng = isRunnrRnng($rnnrNm);
                $cntr = 0;
                do {
                    sleep(10);
                    $isRnng = isRunnrRnng($rnnrNm);
                    $cntr++;
                } while ($isRnng === TRUE || $cntr <= 4);
                if ($isRnng === FALSE) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Process Runner Successfully Stopped!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Failed to stop Process Runner!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Process/Reports Runners</span>
				</li>
                               </ul>
                              </div>";

                //echo "Process Runners";
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
                $mstAutoRefresh = isset($_POST['mstAutoRefresh']) ? (float) cleanInputData($_POST['mstAutoRefresh']) : 0;
                $canAddRecs = test_prmssns($dfltPrvldgs[1], $mdlNm);
                $canEdtRecs = test_prmssns($dfltPrvldgs[1], $mdlNm);
                $canDelRecs = test_prmssns($dfltPrvldgs[1], $mdlNm);
                $curIdx = 0;
                $result = get_PrcsRnnrs();
                $cntr = 0;
                $colClassType1 = "col-lg-4";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-2";
                $nwRowHtml = "<tr id=\"allPrcsRnnrsRow__WWW123WWW\">"
                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                    . "<td class=\"lovtd\">
                                <textarea class=\"form-control rqrdFld\" row=\"7\" col=\"20\" id=\"allPrcsRnnrsRow_WWW123WWW_RnnrNm\" name=\"allPrcsRnnrsRow_WWW123WWW_RnnrNm\" style=\"width:100% !important;min-height: 80px;height: 80px;\"></textarea>
                            </td>                                             
                            <td class=\"lovtd\">
                                <textarea class=\"form-control rqrdFld\" row=\"7\" col=\"20\" id=\"allPrcsRnnrsRow_WWW123WWW_RnnrDesc\" name=\"allPrcsRnnrsRow_WWW123WWW_RnnrDesc\" style=\"width:100% !important;min-height: 80px;height: 80px;\"></textarea>
                            </td>                                            
                            <td class=\"lovtd\"> 
                               <textarea class=\"form-control\" row=\"7\" col=\"20\" id=\"allPrcsRnnrsRow_WWW123WWW_LstActvTme\" name=\"allPrcsRnnrsRow_WWW123WWW_LstActvTme\" readonly=\"true\" style=\"width:100% !important;min-height: 80px;height: 80px;\"></textarea>
                           </td>                                            
                            <td class=\"lovtd\">     
                                <textarea class=\"form-control\" row=\"7\" col=\"10\" id=\"allPrcsRnnrsRow_WWW123WWW_LastStatus\" name=\"allPrcsRnnrsRow_WWW123WWW_LastStatus\" readonly=\"true\" style=\"width:100% !important;min-height: 80px;height: 80px;\"></textarea>
                            </td>                                            
                            <td class=\"lovtd\">  
                                <textarea class=\"form-control rqrdFld\" row=\"7\" col=\"20\" id=\"allPrcsRnnrsRow_WWW123WWW_FileNm\" name=\"allPrcsRnnrsRow_WWW123WWW_FileNm\" style=\"width:100% !important;min-height: 80px;height: 80px;\"></textarea>
                            </td>                                            
                            <td class=\"lovtd\">
                                <select class=\"form-control\" id=\"allPrcsRnnrsRow_WWW123WWW_Priorty\">";
                $valslctdArry = array("", "", "", "", "");
                $valuesArrys = array(
                    "1-Highest",
                    "2-AboveNormal",
                    "3-Normal",
                    "4-BelowNormal",
                    "5-Lowest"
                );
                for ($z = 0; $z < count($valuesArrys); $z++) {
                    $nwRowHtml .= "<option value=\"" . $valuesArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $valuesArrys[$z] . "</option>";
                }
                $nwRowHtml .= "</select>
                            </td> 
                            <td class=\"lovtd\">
                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delBankBrnchStp('allBnkBrnchsStpRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Process Runner\">
                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                </button>
                            </td>
                            <td class=\"lovtd\">
                                &nbsp;
                            </td>
                          </tr>";
                $nwRowHtml = urlencode($nwRowHtml);
?>
                <form id='allPrcsRnnrsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-12" style="padding:2px 1px 2px 1px !important;">
                                <?php if ($canAddRecs) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="insertNewRowBe4('allPrcsRnnrsTable', 0, '<?php echo $nwRowHtml; ?>');">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Add Runner
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="savePrcsRnnrs();">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save Runners
                                    </button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="openATab('#allmodules', 'grp=9&typ=1&pg=4&vtyp=0');">
                                    <img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Refresh Runners
                                </button>
                                <?php if ($mstAutoRefresh <= 0) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="startRnnrsRfrsh();">
                                        <img src="cmn_images/clock.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Auto-Refresh
                                    </button>
                                <?php } ?>
                                <?php if ($mstAutoRefresh > 0) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="stopRnnrsRfrsh();">
                                        <img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Stop Auto-Refresh
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allPrcsRnnrsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Runner Name</th>
                                        <th style="min-width:250px;width:250px;">Runner Description</th>
                                        <th style="min-width:100px;width:100px;">Last Time Active</th>
                                        <th style="min-width:150px;width:150px;">Last Status</th>
                                        <th>Executable File Name</th>
                                        <th style="min-width:100px;width:100px;">Priority</th>
                                        <?php if ($canDelRecs === TRUE) { ?>
                                            <th>&nbsp;</th>
                                        <?php } ?>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        $isRnng = isRunnrRnng($row[1]);
                                        $stsCss = "background-color: #ddd;";
                                        if ($isRnng === true) {
                                            $stsCss = "background-color: lime;";
                                        }
                                    ?>
                                        <tr id="allPrcsRnnrsRow_<?php echo $cntr; ?>">
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <?php
                                                if ($canEdtRecs === TRUE) {
                                                ?>
                                                    <textarea class="form-control rqrdFld" row="7" col="20" id="allPrcsRnnrsRow<?php echo $cntr; ?>_RnnrNm" name="allPrcsRnnrsRow<?php echo $cntr; ?>_RnnrNm" readonly="true" style="width:100% !important;min-height: 80px;height: 80px;"><?php echo $row[1]; ?></textarea>
                                                <?php
                                                } else {
                                                    echo $row[1];
                                                }
                                                ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allPrcsRnnrsRow<?php echo $cntr; ?>_RnnrID" value="<?php echo $row[0]; ?>">
                                            </td>
                                            <td class="lovtd">
                                                <?php
                                                if ($canEdtRecs === TRUE) {
                                                ?>
                                                    <textarea class="form-control rqrdFld" row="7" col="20" id="allPrcsRnnrsRow<?php echo $cntr; ?>_RnnrDesc" name="allPrcsRnnrsRow<?php echo $cntr; ?>_RnnrDesc" readonly="true" style="width:100% !important;min-height: 80px;height: 80px;"><?php echo $row[2]; ?></textarea>
                                                <?php
                                                } else {
                                                    echo $row[2];
                                                }
                                                ?>
                                            </td>
                                            <td class="lovtd">
                                                <?php
                                                if ($canEdtRecs === TRUE) {
                                                ?>
                                                    <textarea class="form-control" row="7" col="20" id="allPrcsRnnrsRow<?php echo $cntr; ?>_LstActvTme" name="allPrcsRnnrsRow<?php echo $cntr; ?>_LstActvTme" readonly="true" style="width:100% !important;min-height: 80px;height: 80px;<?php echo $stsCss; ?>"><?php echo $row[3]; ?></textarea>
                                                <?php
                                                } else {
                                                    echo $row[3];
                                                }
                                                ?>
                                            </td>
                                            <td class="lovtd">
                                                <?php
                                                if ($canEdtRecs === TRUE) {
                                                ?>
                                                    <textarea class="form-control" row="7" col="10" id="allPrcsRnnrsRow<?php echo $cntr; ?>_LastStatus" name="allPrcsRnnrsRow<?php echo $cntr; ?>_LastStatus" readonly="true" style="width:100% !important;min-height: 80px;height: 80px;<?php echo $stsCss; ?>"><?php echo $row[4]; ?></textarea>
                                                <?php
                                                } else {
                                                    echo $row[4];
                                                }
                                                ?>
                                            </td>
                                            <td class="lovtd">
                                                <?php
                                                if ($canEdtRecs === TRUE) {
                                                ?>
                                                    <textarea class="form-control rqrdFld" row="7" col="20" id="allPrcsRnnrsRow<?php echo $cntr; ?>_FileNm" name="allPrcsRnnrsRow<?php echo $cntr; ?>_FileNm" readonly="true" style="width:100% !important;min-height: 80px;height: 80px;"><?php echo $row[5]; ?></textarea>
                                                <?php
                                                } else {
                                                    echo $row[5];
                                                }
                                                ?>
                                            </td>
                                            <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;">
                                                <?php
                                                if ($canEdtRecs === TRUE) {
                                                ?>
                                                    <select class="form-control" id="allPrcsRnnrsRow<?php echo $cntr; ?>_Priorty">
                                                        <?php
                                                        $valslctdArry = array("", "", "", "", "");
                                                        $valuesArrys = array(
                                                            "1-Highest",
                                                            "2-AboveNormal",
                                                            "3-Normal",
                                                            "4-BelowNormal",
                                                            "5-Lowest"
                                                        );

                                                        for ($z = 0; $z < count($valuesArrys); $z++) {
                                                            if (strtoupper($row[6]) == strtoupper($valuesArrys[$z])) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                        ?>
                                                            <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php
                                                } else {
                                                    echo $row[6];
                                                }
                                                ?>
                                            </td>
                                            <?php if ($canDelRecs === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPrcsRnnrs('allPrcsRnnrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Process Runner">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <td class="lovtd">
                                                <?php
                                                if ($canEdtRecs === true && strpos($row[1], "REQUESTS LISTENER PROGRAM") !== FALSE) {
                                                    if ($isRnng === false) {
                                                ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="startProcessRunner('allPrcsRnnrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" title="Start Request Listener">
                                                            <img src="cmn_images/98.png" style="left: 0.5%; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <input type="hidden" class="form-control" aria-label="..." id="allPrcsRnnrsRow<?php echo $cntr; ?>_ShdStart" value="1">
                                                    <?php } else { ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="startProcessRunner('allPrcsRnnrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" title="Stop Request Listener">
                                                            <img src="cmn_images/90.png" style="left: 0.5%; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <input type="hidden" class="form-control" aria-label="..." id="allPrcsRnnrsRow<?php echo $cntr; ?>_ShdStart" value="0">
                                                <?php
                                                    }
                                                }
                                                ?>
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
        }
    }
}
?>