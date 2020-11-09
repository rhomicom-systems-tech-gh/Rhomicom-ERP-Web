<?php
$canRunRpts = test_prmssns($dfltPrvldgs[8], $mdlNm);
$canDelRptRuns = test_prmssns($dfltPrvldgs[9], $mdlNm);
$canVwOthrsRuns = test_prmssns($dfltPrvldgs[10], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Schedule */
                $canDelSchdl = test_prmssns($dfltPrvldgs[9], $mdlNm);
                $schdlID = isset($_POST['schdlID']) ? cleanInputData($_POST['schdlID']) : -1;
                $rptDesc = isset($_POST['rptDesc']) ? cleanInputData($_POST['rptDesc']) : "";
                if ($canDelSchdl) {
                    $affctd1 = deleteGnrlRecs($schdlID, "rpt.rpt_run_schdules", "schedule_id", $rptDesc);
                    $affctd2 = deleteGnrlRecs($schdlID, "rpt.rpt_run_schdule_params", "schedule_id", $rptDesc);
                    if ($affctd1 > 0) {
                        $dsply = "Successfully Deleted the ff Records-";
                        $dsply .= "<br/>$affctd1 Report Schedule(s)!";
                        $dsply .= "<br/>$affctd2 Report Schedule Parameter(s)!";
                        echo "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                    } else {
                        $dsply = "No Record Deleted!";
                        echo "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                    }
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                header("content-type:application/json");
                $allSchdlsSchdlID = isset($_POST['allSchdlsSchdlID']) ? (float) cleanInputData($_POST['allSchdlsSchdlID']) : -1;
                $allSchdlsRptID = isset($_POST['allSchdlsRptID']) ? (float) cleanInputData($_POST['allSchdlsRptID']) : -1;
                $allSchdlsIntrvl = isset($_POST['allSchdlsIntrvl']) ? (float) cleanInputData($_POST['allSchdlsIntrvl']) : -5;
                $allSchdlsStrtDte = isset($_POST['allSchdlsStrtDte']) ? cleanInputData($_POST['allSchdlsStrtDte']) : '';
                $allSchdlsIntvlUom = isset($_POST['allSchdlsIntvlUom']) ? cleanInputData($_POST['allSchdlsIntvlUom']) : '';
                $allSchdlsRnAtHr = isset($_POST['allSchdlsRnAtHr']) ? cleanInputData($_POST['allSchdlsRnAtHr']) : 'NO';
                $slctdSchdlParams = isset($_POST['slctdSchdlParams']) ? cleanInputData($_POST['slctdSchdlParams']) : '';
                $dtetm = cnvrtDMYTmToYMDTm($allSchdlsStrtDte);
                $oldSchldID = get_SchduleID($usrID, $allSchdlsRptID, $dtetm);
                $allSchdlsRnAtHrBool = $allSchdlsRnAtHr == "NO" ? FALSE : TRUE;
                $errMsg = "";
                if ($dtetm != "" && $allSchdlsIntvlUom != "" && $allSchdlsRptID > 0 && ($oldSchldID <= 0 || $oldSchldID == $allSchdlsSchdlID)) {
                    if ($allSchdlsSchdlID <= 0) {
                        createPrcsSchdl($allSchdlsRptID, $dtetm, $allSchdlsIntvlUom, $allSchdlsIntrvl, $allSchdlsRnAtHrBool);
                        $allSchdlsSchdlID = get_SchduleID($usrID, $allSchdlsRptID, $dtetm);
                    } else {
                        updatePrcsSchdl($allSchdlsSchdlID, $allSchdlsRptID, $dtetm, $allSchdlsIntvlUom, $allSchdlsIntrvl, $allSchdlsRnAtHrBool);
                    }
                    //Save Schedule Paramters
                    $affctd = 0;
                    $variousRows = explode("|", trim($slctdSchdlParams, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 3) {
                            //$schdlPramID = (float) cleanInputData1($crntRow[0]);
                            $paramID = (float) cleanInputData1($crntRow[1]);
                            $paramVal = cleanInputData1($crntRow[2]);
                            $oldSchdlPramID = get_SchduleParamID($allSchdlsSchdlID, $paramID);
                            //echo $paramID . ":" . $paramVal . ":" . $oldSchdlPramID . ":" . $schdlPramID."{}";
                            if ($oldSchdlPramID > 0) {
                                $schdlPramID = $oldSchdlPramID;
                            }
                            if ($oldSchdlPramID <= 0 && $paramID > 0) {
                                $affctd += createPrcsSchdlParms(-1, $allSchdlsSchdlID, $paramID, $paramVal);
                            } else {
                                $affctd += updatePrcsSchdlParms($oldSchdlPramID, $paramID, $paramVal);
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Report Schedule Successfully Saved!<br/>" . $affctd . " Report Parameter Values Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                $useDiag = isset($_POST['useDiag']) ? $_POST['useDiag'] : 0;
                if ($useDiag <= 0) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Scheduled Runs</span>
				</li>
                               </ul>
                              </div>";
                }
                //Scheduled Reports/Processes                
                $pkID = isset($_POST['sbmtdSchdlID']) ? $_POST['sbmtdSchdlID'] : -1;
                $sbmtdRptID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                $total = get_ASchdlRunsTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_ASchdlRuns($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType3 = "col-lg-5";
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?>
                <form id='allSchdlsForm' action='' method='post' accept-charset='UTF-8'>
                    <input type="hidden" id="nwRptSchdlRptID" name="nwRptSchdlRptID" value="-1">
                    <input type="hidden" id="nwRptSchdlRptNm" name="nwRptSchdlRptNm" value="">
                    <input type="hidden" id="shdSchdlUseDiag" name="shdSchdlUseDiag" value="<?php echo $useDiag; ?>">
                    <div class="row rhoRowMargin" style="padding:0px 15px 0px 15px !important;">                        
                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 3px 0px 3px !important;">     
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Reports and Processes', '', '', '', 'radio', true, '', 'nwRptSchdlRptID', 'nwRptSchdlRptNm', 'clear', 0, '', function () {
                                        getOneSchdlsNwForm();
                                    });" data-toggle="tooltip" data-placement="bottom" title="Schedule a Selected Report/Process">
                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">New Schedule
                            </button>
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveOneSchdlsForm();" data-toggle="tooltip" data-placement="bottom" title="Schedule Selected Report/Process">
                                <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                        </div>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allSchdlsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllSchdls(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allSchdlsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSchdls('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSchdls('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSchdlsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "");
                                    $srchInsArrys = array("Report Name", "Start Date", "Repeat Interval", "Created By");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSchdlsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllSchdls('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllSchdls('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row" style="padding:0px 15px 0px 15px !important;"> 
                        <div  class="col-lg-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allSchdlsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Scheduled By</th>
                                            <th>Report Name (Schedule ID)</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($pkID <= 0 && $cntr <= 0) {
                                                $pkID = $row[0];
                                                $sbmtdRptID = $row[1];
                                            }
                                            $cntr += 1;
                                            ?>
                                            <tr id="allSchdlsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[6]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allSchdlsRow<?php echo $cntr; ?>_RptID" value="<?php echo $row[1]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="allSchdlsRow<?php echo $cntr; ?>_SchdlID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd"><?php echo $row[2] . " (" . $row[0] . ")"; ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delOneSchdl('allSchdlsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Report/Process">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>                        
                            </fieldset>
                        </div>                        
                        <div  class="col-lg-8" style="padding:0px 1px 0px 1px !important">
                            <div class="rho-container-fluid" id="allSchdlsDetailInfo">
                                <?php
                                if ($pkID > 0) {
                                    $sbmtdSchdlID = $pkID;
                                    $result1 = get_OneSchdlRun($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        ?>
                                        <div class="row" style="padding:0px 15px 0px 15px !important;">
                                            <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="allSchdlsRptID" class="control-label col-lg-4">Schedule ID:</label>
                                                        <div  class="col-lg-8">
                                                            <span><?php echo $row1[0]; ?></span>
                                                            <input type="hidden" class="form-control" aria-label="..." id="allSchdlsRptID" value="<?php echo $row1[1]; ?>">
                                                            <input type="hidden" class="form-control" aria-label="..." id="allSchdlsSchdlID" value="<?php echo $row1[0]; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="allSchdlsRptNm" class="control-label col-lg-4">Report Name:</label>
                                                        <div  class="col-lg-8">
                                                            <span><?php echo $row1[2]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="allSchdlsStrtDte" class="control-label col-lg-4">Start Date:</label>
                                                        <div class="col-lg-8">
                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                <input class="form-control rqrdFld" size="16" type="text" id="allSchdlsStrtDte" name="allSchdlsStrtDte" value="<?php echo $row1[3]; ?>" readonly="">
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="allSchdlsIntrvl" class="control-label col-lg-9">Repeat Interval (After End of Prior Run):</label>
                                                        <div class="col-lg-3">
                                                            <input type="number" min="-999999999999" max="999999999999" class="form-control rqrdFld" aria-label="..." id="allSchdlsIntrvl" name="allSchdlsIntrvl" value="<?php echo $row1[4]; ?>" style="width:100%;">
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="allSchdlsIntvlUom" class="control-label col-lg-8">UOM:</label>
                                                        <div class="col-lg-4">
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="allSchdlsIntvlUom" name="allSchdlsIntvlUom">
                                                                <?php
                                                                $valslctdArry = array("", "", "", "", "", "", "");
                                                                $srchInsArrys = array("Second(s)", "Minute(s)", "Hour(s)", "Day(s)", "Week(s)", "Month(s)", "Year(s)");
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($row1[5] == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>                                                        
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="allSchdlsRnAtHr" class="control-label col-lg-7">Run Only at Hour Specified?:</label>
                                                        <div  class="col-lg-5">
                                                            <?php
                                                            $chkdYes = "";
                                                            $chkdNo = "checked=\"\"";
                                                            if ($row1[6] == "1") {
                                                                $chkdNo = "";
                                                                $chkdYes = "checked=\"\"";
                                                            }
                                                            ?>
                                                            <label class="radio-inline"><input type="radio" name="allSchdlsRnAtHr" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                            <label class="radio-inline"><input type="radio" name="allSchdlsRnAtHr" value="NO" <?php echo $chkdNo; ?>>NO</label>                                                                
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="row" style="padding:0px 15px 0px 15px !important;">                                                             
                                            <table class="table table-striped table-bordered table-responsive" id="allSchdlPrmsTable" cellspacing="0" width="100%" style="width:100%;min-width: 330px;">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Parameter Name</th>
                                                        <th style="min-width:180px !important;">Parameter Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $rptID = $sbmtdRptID;
                                                    $pkID = $rptID;
                                                    if ($canRunRpts === true) {
                                                        
                                                    } else {
                                                        exit();
                                                    }

                                                    $reportName = getGnrlRecNm("rpt.rpt_reports", "report_id", "report_name", $pkID);
                                                    $rptOutPut = getGnrlRecNm("rpt.rpt_reports", "report_id", "output_type", $pkID);
                                                    $rptOrntn = getGnrlRecNm("rpt.rpt_reports", "report_id", "portrait_lndscp", $pkID);
                                                    $result = get_AllParams($pkID);
                                                    $sysParaIDs = array("-130", "-140", "-150", "-160", "-170", "-180", "-190", "-200");
                                                    $sysParaNames = array("Report Title:", "Cols Nos To Group or Width & Height (Px) for Charts:",
                                                        "Cols Nos To Count or Use in Charts:", "Columns To Sum:", "Columns To Average:",
                                                        "Columns To Format Numerically:", "Report Output Formats", "Report Orientations");

                                                    $cntr = 0;
                                                    $curIdx = 0;
                                                    while ($row = loc_db_fetch_array($result)) {
                                                        $cntr += 1;
                                                        $isrqrd = "";
                                                        if ($row[4] == "1") {
                                                            $isrqrd = "rqrdFld";
                                                        }
                                                        $nwval1 = $row[3];
                                                        if ($sbmtdSchdlID > 0) {
                                                            $nwval1 = get_SchdldParamVal($sbmtdSchdlID, $row[0]);
                                                        }
                                                        $lovnm = $row[6];
                                                        $dataTyp = $row[7];
                                                        $dtFrmt = $row[8];
                                                        ?>
                                                        <tr id="allSchdlPrmsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                            <td class="lovtd"><?php echo $row[1]; ?>
                                                                <input type="hidden" class="form-control" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $row[0]; ?>">
                                                                <input type="hidden" class="form-control" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_SchdlParamID" value="-1">
                                                            </td>
                                                            <td class="lovtd">
                                                                <?php if ($lovnm !== "") { ?>
                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                        <div class="input-group"  style="width:100%;">
                                                                            <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                } else if ($dataTyp == "DATE") {
                                                                    $dtFrmtCls = "form_date_tme";
                                                                    if ($dtFrmt == "yyyy-MM-dd") {
                                                                        $dtFrmt = "yyyy-mm-dd";
                                                                        $dtFrmt1 = "yyyy-mm-dd";
                                                                        $dtFrmtCls = "form_date1";
                                                                    } else if ($dtFrmt == "yyyy-MM-dd HH:mm:ss") {
                                                                        $dtFrmt = "yyyy-mm-dd hh:ii:ss";
                                                                        $dtFrmt1 = "yyyy-mm-dd hh:ii:ss";
                                                                        $dtFrmtCls = "form_date_tme1";
                                                                    } else if ($dtFrmt == "dd-MMM-yyyy HH:mm:ss") {
                                                                        $dtFrmt = "dd-M-yyyy hh:ii:ss";
                                                                        $dtFrmt1 = "yyyy-mm-dd hh:ii:ss";
                                                                        $dtFrmtCls = "form_date_tme";
                                                                    } else if ($dtFrmt == "dd-MMM-yyyy") {
                                                                        $dtFrmt = "dd-M-yyyy";
                                                                        $dtFrmt1 = "yyyy-mm-dd";
                                                                        $dtFrmtCls = "form_date";
                                                                    } else {
                                                                        $dtFrmt1 = "yyyy-mm-dd hh:ii:ss";
                                                                        $dtFrmt = "dd-M-yyyy hh:ii:ss";
                                                                    }
                                                                    ?>                                            
                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                        <div class="input-group date <?php echo $dtFrmtCls; ?>" data-date="" data-date-format="<?php echo $dtFrmt; ?>" data-link-field="dtp_input2" data-link-format="<?php echo $dtFrmt1; ?>" style="width:100%;">
                                                                            <input class="form-control <?php echo $isrqrd ?>" size="16" type="text" id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" name="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" readonly="">
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        </div>                                                                
                                                                    </div>
                                                                    <?php
                                                                } else if ($dataTyp == "NUMBER") {
                                                                    ?>                                            
                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                        <input type="number" min="-999999999999" max="999999999999" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" name="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" style="width:100%;">
                                                                    </div>
                                                                    <?php
                                                                } else {
                                                                    ?>                                            
                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                        <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" style="width:100% !important;">
                                                                    </div>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    $result1 = get_Rpt_ColsToAct($pkID);
                                                    $colNoVals = array("", "", "", "", "", "", "", "");
                                                    $colsCnt = loc_db_num_fields($result1);
                                                    while ($row1 = loc_db_fetch_array($result1)) {
                                                        for ($d = 0; $d < $colsCnt; $d++) {
                                                            if ($sbmtdSchdlID > 0) {
                                                                $colNoVals[$d] = get_SchdldParamVal($sbmtdSchdlID, $sysParaIDs[$d]);
                                                            } else {
                                                                $colNoVals[$d] = $row1[$d];
                                                            }
                                                        }
                                                    }
                                                    for ($d = 0; $d < count($colNoVals); $d++) {
                                                        if ($sysParaIDs[$d] !== "-190" && $sysParaIDs[$d] !== "-130") {
                                                            continue;
                                                        }
                                                        $cntr ++;
                                                        $isrqrd = "";
                                                        $nwval1 = $colNoVals[$d];
                                                        ?>
                                                        <tr id="allSchdlPrmsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                            <td class="lovtd"><?php echo $sysParaNames[$d]; ?><input type="hidden" class="form-control" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $sysParaIDs[$d]; ?>"></td>
                                                            <td class="lovtd">  
                                                                <?php
                                                                if ($sysParaIDs[$d] == "-190") {
                                                                    $lovnm = "Report Output Formats";
                                                                    ?>
                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                        <div class="input-group"  style="width:100%;">
                                                                            <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                } else if ($sysParaIDs[$d] == "-200") {
                                                                    $lovnm = "Report Orientations";
                                                                    ?>
                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                        <div class="input-group"  style="width:100%;">
                                                                            <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                } else {
                                                                    ?>                                            
                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                        <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>" style="width:100% !important;">
                                                                    </div>
                                                                <?php }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
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
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 6) {
                //One Scheduled Report/Processe
                $pkID = isset($_POST['sbmtdSchdlID']) ? $_POST['sbmtdSchdlID'] : -1;
                $sbmtdRptID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                if ($pkID > 0) {
                    $sbmtdSchdlID = $pkID;
                    $result1 = get_OneSchdlRun($pkID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        ?>
                        <div class="row" style="padding:0px 15px 0px 15px !important;">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="allSchdlsRptID" class="control-label col-lg-4">Schedule ID:</label>
                                        <div  class="col-lg-8">
                                            <span><?php echo $row1[0]; ?></span>
                                            <input type="hidden" class="form-control" aria-label="..." id="allSchdlsRptID" value="<?php echo $row1[1]; ?>">
                                            <input type="hidden" class="form-control" aria-label="..." id="allSchdlsSchdlID" value="<?php echo $row1[0]; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="allSchdlsRptNm" class="control-label col-lg-4">Report Name:</label>
                                        <div  class="col-lg-8">
                                            <span><?php echo $row1[2]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="allSchdlsStrtDte" class="control-label col-lg-4">Start Date:</label>
                                        <div class="col-lg-8">
                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                <input class="form-control rqrdFld" size="16" type="text" id="allSchdlsStrtDte" name="allSchdlsStrtDte" value="<?php echo $row1[3]; ?>" readonly="">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                    <!---<fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                    </fieldset>-->
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="allSchdlsIntrvl" class="control-label col-lg-9">Repeat Interval (After End of Prior Run):</label>
                                        <div class="col-lg-3">
                                            <input type="number" min="-999999999999" max="999999999999" class="form-control rqrdFld" aria-label="..." id="allSchdlsIntrvl" name="allSchdlsIntrvl" value="<?php echo $row1[4]; ?>" style="width:100%;">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="allSchdlsIntvlUom" class="control-label col-lg-8">UOM:</label>
                                        <div class="col-lg-4">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="allSchdlsIntvlUom" name="allSchdlsIntvlUom">
                                                <?php
                                                $valslctdArry = array("", "", "", "", "", "", "");
                                                $srchInsArrys = array("Second(s)", "Minute(s)", "Hour(s)", "Day(s)", "Week(s)", "Month(s)", "Year(s)");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($row1[5] == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>                                                        
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="allSchdlsRnAtHr" class="control-label col-lg-7">Run Only at Hour Specified?:</label>
                                        <div  class="col-lg-5">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($row1[6] == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <label class="radio-inline"><input type="radio" name="allSchdlsRnAtHr" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                            <label class="radio-inline"><input type="radio" name="allSchdlsRnAtHr" value="NO" <?php echo $chkdNo; ?>>NO</label>                                                                
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row" style="padding:0px 15px 0px 15px !important;">                                                             
                            <table class="table table-striped table-bordered table-responsive" id="allSchdlPrmsTable" cellspacing="0" width="100%" style="width:100%;min-width: 330px;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Parameter Name</th>
                                        <th style="min-width:180px !important;">Parameter Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rptID = $sbmtdRptID;
                                    $pkID = $rptID;
                                    if ($canRunRpts === true) {
                                        
                                    } else {
                                        exit();
                                    }

                                    $reportName = getGnrlRecNm("rpt.rpt_reports", "report_id", "report_name", $pkID);
                                    $rptOutPut = getGnrlRecNm("rpt.rpt_reports", "report_id", "output_type", $pkID);
                                    $rptOrntn = getGnrlRecNm("rpt.rpt_reports", "report_id", "portrait_lndscp", $pkID);
                                    $result = get_AllParams($pkID);
                                    $sysParaIDs = array("-130", "-140", "-150", "-160", "-170", "-180", "-190", "-200");
                                    $sysParaNames = array("Report Title:", "Cols Nos To Group or Width & Height (Px) for Charts:",
                                        "Cols Nos To Count or Use in Charts:", "Columns To Sum:", "Columns To Average:",
                                        "Columns To Format Numerically:", "Report Output Formats", "Report Orientations");

                                    $cntr = 0;
                                    $curIdx = 0;
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        $isrqrd = "";
                                        if ($row[4] == "1") {
                                            $isrqrd = "rqrdFld";
                                        }
                                        $nwval1 = $row[3];
                                        if ($sbmtdSchdlID > 0) {
                                            $nwval1 = get_SchdldParamVal($sbmtdSchdlID, $row[0]);
                                        }
                                        $lovnm = $row[6];
                                        $dataTyp = $row[7];
                                        $dtFrmt = $row[8];
                                        ?>
                                        <tr id="allSchdlPrmsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $row[1]; ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $row[0]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_SchdlParamID" value="-1">
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($lovnm !== "") { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } else if ($dataTyp == "DATE") {
                                                    $dtFrmtCls = "form_date_tme";
                                                    if ($dtFrmt == "yyyy-MM-dd") {
                                                        $dtFrmt = "yyyy-mm-dd";
                                                        $dtFrmt1 = "yyyy-mm-dd";
                                                        $dtFrmtCls = "form_date1";
                                                    } else if ($dtFrmt == "yyyy-MM-dd HH:mm:ss") {
                                                        $dtFrmt = "yyyy-mm-dd hh:ii:ss";
                                                        $dtFrmt1 = "yyyy-mm-dd hh:ii:ss";
                                                        $dtFrmtCls = "form_date_tme1";
                                                    } else if ($dtFrmt == "dd-MMM-yyyy HH:mm:ss") {
                                                        $dtFrmt = "dd-M-yyyy hh:ii:ss";
                                                        $dtFrmt1 = "yyyy-mm-dd hh:ii:ss";
                                                        $dtFrmtCls = "form_date_tme";
                                                    } else if ($dtFrmt == "dd-MMM-yyyy") {
                                                        $dtFrmt = "dd-M-yyyy";
                                                        $dtFrmt1 = "yyyy-mm-dd";
                                                        $dtFrmtCls = "form_date";
                                                    } else {
                                                        $dtFrmt1 = "yyyy-mm-dd hh:ii:ss";
                                                        $dtFrmt = "dd-M-yyyy hh:ii:ss";
                                                    }
                                                    ?>                                            
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <div class="input-group date <?php echo $dtFrmtCls; ?>" data-date="" data-date-format="<?php echo $dtFrmt; ?>" data-link-field="dtp_input2" data-link-format="<?php echo $dtFrmt1; ?>" style="width:100%;">
                                                            <input class="form-control <?php echo $isrqrd ?>" size="16" type="text" id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" name="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" readonly="">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>                                                                
                                                    </div>
                                                    <?php
                                                } else if ($dataTyp == "NUMBER") {
                                                    ?>                                            
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <input type="number" min="-999999999999" max="999999999999" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" name="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" style="width:100%;">
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>                                            
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" style="width:100% !important;">
                                                    </div>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    $result1 = get_Rpt_ColsToAct($pkID);
                                    $colNoVals = array("", "", "", "", "", "", "", "");
                                    $colsCnt = loc_db_num_fields($result1);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        for ($d = 0; $d < $colsCnt; $d++) {
                                            if ($sbmtdSchdlID > 0) {
                                                $colNoVals[$d] = get_SchdldParamVal($sbmtdSchdlID, $sysParaIDs[$d]);
                                            } else {
                                                $colNoVals[$d] = $row1[$d];
                                            }
                                        }
                                    }
                                    for ($d = 0; $d < count($colNoVals); $d++) {
                                        if ($sysParaIDs[$d] !== "-190" && $sysParaIDs[$d] !== "-130") {
                                            continue;
                                        }
                                        $cntr ++;
                                        $isrqrd = "";
                                        $nwval1 = $colNoVals[$d];
                                        ?>
                                        <tr id="allSchdlPrmsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $sysParaNames[$d]; ?><input type="hidden" class="form-control" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $sysParaIDs[$d]; ?>"></td>
                                            <td class="lovtd">  
                                                <?php
                                                if ($sysParaIDs[$d] == "-190") {
                                                    $lovnm = "Report Output Formats";
                                                    ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } else if ($sysParaIDs[$d] == "-200") {
                                                    $lovnm = "Report Orientations";
                                                    ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>                                            
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>" style="width:100% !important;">
                                                    </div>
                                                <?php }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php
                    }
                } else {
                    ?>
                    <span>No Results Found</span>

                    <?php
                }
            } else if ($vwtyp == 7) {
                //New Scheduled Report/Processe Form
                $sbmtdRptID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                $pkID = $sbmtdRptID;
                if ($pkID > 0) {
                    $sbmtdSchdlID = -1;

                    $rptID = $sbmtdRptID;
                    $pkID = $rptID;
                    if ($canRunRpts === true) {
                        
                    } else {
                        exit();
                    }

                    $reportName = getGnrlRecNm("rpt.rpt_reports", "report_id", "report_name", $pkID);
                    $rptOutPut = getGnrlRecNm("rpt.rpt_reports", "report_id", "output_type", $pkID);
                    $rptOrntn = getGnrlRecNm("rpt.rpt_reports", "report_id", "portrait_lndscp", $pkID);
                    $result = get_AllParams($pkID);
                    $sysParaIDs = array("-130", "-140", "-150", "-160", "-170", "-180", "-190", "-200");
                    $sysParaNames = array("Report Title:", "Cols Nos To Group or Width & Height (Px) for Charts:",
                        "Cols Nos To Count or Use in Charts:", "Columns To Sum:", "Columns To Average:",
                        "Columns To Format Numerically:", "Report Output Formats", "Report Orientations");

                    $cntr = 0;
                    $curIdx = 0;
                    ?>
                    <div class="row" style="padding:0px 15px 0px 15px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;"> 
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="allSchdlsRptID" class="control-label col-lg-4">Schedule ID:</label>
                                    <div  class="col-lg-8">
                                        <span>-1</span>
                                        <input type="hidden" class="form-control" aria-label="..." id="allSchdlsRptID" value="<?php echo $sbmtdRptID; ?>">
                                        <input type="hidden" class="form-control" aria-label="..." id="allSchdlsSchdlID" value="-1">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="allSchdlsRptNm" class="control-label col-lg-4">Report Name:</label>
                                    <div  class="col-lg-8">
                                        <span><?php echo $reportName; ?></span>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="allSchdlsStrtDte" class="control-label col-lg-4">Start Date:</label>
                                    <div class="col-lg-8">
                                        <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                            <input class="form-control rqrdFld" size="16" type="text" id="allSchdlsStrtDte" name="allSchdlsStrtDte" value="" readonly="">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="allSchdlsIntrvl" class="control-label col-lg-9">Repeat Interval (After End of Prior Run):</label>
                                    <div class="col-lg-3">
                                        <input type="number" min="-999999999999" max="999999999999" class="form-control rqrdFld" aria-label="..." id="allSchdlsIntrvl" name="allSchdlsIntrvl" value="" style="width:100%;">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="allSchdlsIntvlUom" class="control-label col-lg-8">UOM:</label>
                                    <div class="col-lg-4">
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="allSchdlsIntvlUom" name="allSchdlsIntvlUom">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "");
                                            $srchInsArrys = array("Second(s)", "Minute(s)", "Hour(s)", "Day(s)", "Week(s)", "Month(s)", "Year(s)");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>                                                        
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="allSchdlsRnAtHr" class="control-label col-lg-7">Run Only at Hour Specified?:</label>
                                    <div  class="col-lg-5">
                                        <?php
                                        $chkdYes = "";
                                        $chkdNo = "checked=\"\"";
                                        ?>
                                        <label class="radio-inline"><input type="radio" name="allSchdlsRnAtHr" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                        <label class="radio-inline"><input type="radio" name="allSchdlsRnAtHr" value="NO" <?php echo $chkdNo; ?>>NO</label>                                                                
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="row" style="padding:0px 15px 0px 15px !important;">                                                             
                        <table class="table table-striped table-bordered table-responsive" id="allSchdlPrmsTable" cellspacing="0" width="100%" style="width:100%;min-width: 330px;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Parameter Name</th>
                                    <th style="min-width:180px !important;">Parameter Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = loc_db_fetch_array($result)) {
                                    $cntr += 1;
                                    $isrqrd = "";
                                    if ($row[4] == "1") {
                                        $isrqrd = "rqrdFld";
                                    }
                                    $nwval1 = $row[3];
                                    $lovnm = $row[6];
                                    $dataTyp = $row[7];
                                    $dtFrmt = $row[8];
                                    ?>
                                    <tr id="allSchdlPrmsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                        <td class="lovtd"><?php echo $row[1]; ?>
                                            <input type="hidden" class="form-control" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $row[0]; ?>">
                                            <input type="hidden" class="form-control" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_SchdlParamID" value="<?php echo $row[0]; ?>">
                                        </td>
                                        <td class="lovtd">
                                            <?php if ($lovnm !== "") { ?>
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <div class="input-group"  style="width:100%;">
                                                        <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php
                                            } else if ($dataTyp == "DATE") {
                                                $dtFrmtCls = "form_date_tme";
                                                if ($dtFrmt == "yyyy-MM-dd") {
                                                    $dtFrmt = "yyyy-mm-dd";
                                                    $dtFrmt1 = "yyyy-mm-dd";
                                                    $dtFrmtCls = "form_date1";
                                                } else if ($dtFrmt == "yyyy-MM-dd HH:mm:ss") {
                                                    $dtFrmt = "yyyy-mm-dd hh:ii:ss";
                                                    $dtFrmt1 = "yyyy-mm-dd hh:ii:ss";
                                                    $dtFrmtCls = "form_date_tme1";
                                                } else if ($dtFrmt == "dd-MMM-yyyy HH:mm:ss") {
                                                    $dtFrmt = "dd-M-yyyy hh:ii:ss";
                                                    $dtFrmt1 = "yyyy-mm-dd hh:ii:ss";
                                                    $dtFrmtCls = "form_date_tme";
                                                } else if ($dtFrmt == "dd-MMM-yyyy") {
                                                    $dtFrmt = "dd-M-yyyy";
                                                    $dtFrmt1 = "yyyy-mm-dd";
                                                    $dtFrmtCls = "form_date";
                                                } else {
                                                    $dtFrmt1 = "yyyy-mm-dd hh:ii:ss";
                                                    $dtFrmt = "dd-M-yyyy hh:ii:ss";
                                                }
                                                ?>                                            
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <div class="input-group date <?php echo $dtFrmtCls; ?>" data-date="" data-date-format="<?php echo $dtFrmt; ?>" data-link-field="dtp_input2" data-link-format="<?php echo $dtFrmt1; ?>" style="width:100%;">
                                                        <input class="form-control <?php echo $isrqrd ?>" size="16" type="text" id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" name="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" readonly="">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>                                                                
                                                </div>
                                                <?php
                                            } else if ($dataTyp == "NUMBER") {
                                                ?>                                            
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <input type="number" min="-999999999999" max="999999999999" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" name="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" style="width:100%;">
                                                </div>
                                                <?php
                                            } else {
                                                ?>                                            
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" style="width:100% !important;">
                                                </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                $result1 = get_Rpt_ColsToAct($pkID);
                                $colNoVals = array("", "", "", "", "", "", "", "");
                                $colsCnt = loc_db_num_fields($result1);
                                while ($row1 = loc_db_fetch_array($result1)) {
                                    for ($d = 0; $d < $colsCnt; $d++) {
                                        $colNoVals[$d] = $row1[$d];
                                    }
                                }
                                for ($d = 0; $d < count($colNoVals); $d++) {
                                    if ($sysParaIDs[$d] !== "-190" && $sysParaIDs[$d] !== "-130") {
                                        continue;
                                    }
                                    $cntr ++;
                                    $isrqrd = "";
                                    ?>
                                    <tr id="allSchdlPrmsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                        <td class="lovtd"><?php echo $sysParaNames[$d]; ?><input type="hidden" class="form-control" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $sysParaIDs[$d]; ?>"></td>
                                        <td class="lovtd">  
                                            <?php
                                            if ($sysParaIDs[$d] == "-190") {
                                                $lovnm = "Report Output Formats";
                                                ?>
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <div class="input-group"  style="width:100%;">
                                                        <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php
                                            } else if ($sysParaIDs[$d] == "-200") {
                                                $lovnm = "Report Orientations";
                                                ?>
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <div class="input-group"  style="width:100%;">
                                                        <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>                                            
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="allSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>" style="width:100% !important;">
                                                </div>
                                            <?php }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                }
            }
        }
    }
}
?>