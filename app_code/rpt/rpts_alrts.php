<?php
$canRunRpts = test_prmssns($dfltPrvldgs[8], $mdlNm);
$canDelAlrtRuns = test_prmssns($dfltPrvldgs[9], $mdlNm);
$canVwOthrsRuns = test_prmssns($dfltPrvldgs[10], $mdlNm);
$canAddAlrts = test_prmssns($dfltPrvldgs[12], $mdlNm);
$canEdtAlrts = test_prmssns($dfltPrvldgs[13], $mdlNm);
$canDelAlrts = test_prmssns($dfltPrvldgs[14], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $alrtNm = isset($_POST['alrtNm']) ? cleanInputData($_POST['alrtNm']) : "";
                echo deleteAlert($pKeyID, $alrtNm);
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Reports
                header("content-type:application/json");
                $orgid = $_SESSION['ORG_ID'];
                $alrtsAlertID = isset($_POST['alrtsAlertID']) ? (int) cleanInputData($_POST['alrtsAlertID']) : -1;
                $alrtsRptID = isset($_POST['alrtsRptID']) ? (int) cleanInputData($_POST['alrtsRptID']) : "";
                $alrtsAlertNm = isset($_POST['alrtsAlertNm']) ? cleanInputData($_POST['alrtsAlertNm']) : "";
                $alrtsAlrtTyp = isset($_POST['alrtsAlrtTyp']) ? cleanInputData($_POST['alrtsAlrtTyp']) : "";
                $alrtsAlertDesc = isset($_POST['alrtsAlertDesc']) ? cleanInputData($_POST['alrtsAlertDesc']) : "";
                $alrtSchdlsStrtDte = isset($_POST['alrtSchdlsStrtDte']) ? cleanInputData($_POST['alrtSchdlsStrtDte']) : '';
                $alrtsIntrvl = isset($_POST['alrtsIntrvl']) ? (int) cleanInputData($_POST['alrtsIntrvl']) : '';
                $alrtsIntvlUom = isset($_POST['alrtsIntvlUom']) ? cleanInputData($_POST['alrtsIntvlUom']) : '';
                $alrtsEndHour = isset($_POST['alrtsEndHour']) ? (int) cleanInputData($_POST['alrtsEndHour']) : '';
                $alrtsMailAttchmnts = isset($_POST['alrtsMailAttchmnts']) ? cleanInputData($_POST['alrtsMailAttchmnts']) : '';
                $alrtsMailSubject = isset($_POST['alrtsMailSubject']) ? cleanInputData($_POST['alrtsMailSubject']) : '';
                $alrtsMailBcc = isset($_POST['alrtsMailBcc']) ? cleanInputData($_POST['alrtsMailBcc']) : '';
                $alrtsMailCc = isset($_POST['alrtsMailCc']) ? cleanInputData($_POST['alrtsMailCc']) : '';
                $alrtsMailTo = isset($_POST['alrtsMailTo']) ? cleanInputData($_POST['alrtsMailTo']) : '';
                $alrtsParamSQL = isset($_POST['alrtsParamSQL']) ? cleanInputData($_POST['alrtsParamSQL']) : '';
                $alrtsRnAtHr = isset($_POST['alrtsRnAtHr']) ? cleanInputData($_POST['alrtsRnAtHr']) : '';
                $alrtsRnLnkdRpt = isset($_POST['alrtsRnLnkdRpt']) ? cleanInputData($_POST['alrtsRnLnkdRpt']) : '';
                $alrtsIsEnabled = isset($_POST['alrtsIsEnabled']) ? cleanInputData($_POST['alrtsIsEnabled']) : '';
                $alrtsMsgBody = isset($_POST['alrtsMsgBody']) ? cleanInputData($_POST['alrtsMsgBody']) : '';
                $slctdSchdlPrms = isset($_POST['slctdSchdlPrms']) ? cleanInputData($_POST['slctdSchdlPrms']) : '';
                $isenbld = false;
                $rnAtHr = false;
                $rnLnkdRpt = false;
                if ($alrtsIsEnabled == "YES") {
                    $isenbld = true;
                }
                if ($alrtsRnAtHr == "YES") {
                    $rnAtHr = true;
                }
                if ($alrtsRnLnkdRpt == "YES") {
                    $rnLnkdRpt = true;
                }
                $oldAlertID = getAlertID($alrtsAlertNm);
                if ($alrtsRptID > 0 && $alrtsAlertNm != "" && $alrtSchdlsStrtDte != "" && $alrtsMsgBody != "" && $alrtsMailTo != "" && ($oldAlertID <= 0 || $oldAlertID == $alrtsAlertID)) {
                    if ($alrtsAlertID <= 0) {
                        createAlert($alrtsAlertNm, $alrtsAlertDesc, $alrtsMailTo, $alrtsMailCc, $alrtsMsgBody, $isenbld, $alrtsAlrtTyp, $alrtsMailSubject, $alrtsMailBcc, $alrtsParamSQL, $alrtsRptID, $rnLnkdRpt, $alrtSchdlsStrtDte, $alrtsIntvlUom, $alrtsIntrvl, $rnAtHr, $alrtsMailAttchmnts, $alrtsEndHour);
                        $alrtsAlertID = getAlertID($alrtsAlertNm);
                    } else {
                        updateAlert($alrtsAlertID, $alrtsAlertNm, $alrtsAlertDesc, $alrtsMailTo, $alrtsMailCc, $alrtsMsgBody, $isenbld, $alrtsAlrtTyp, $alrtsMailSubject, $alrtsMailBcc, $alrtsParamSQL, $alrtsRptID, $rnLnkdRpt, $alrtSchdlsStrtDte, $alrtsIntvlUom, $alrtsIntrvl, $rnAtHr, $alrtsMailAttchmnts, $alrtsEndHour);
                    }
                    $affctdParams = 0;
                    if ($alrtsAlertID > 0) {
                        if (trim($slctdSchdlPrms, "|~") != "") {
                            //Save Report Parameters
                            $variousRows = explode("|", trim($slctdSchdlPrms, "|"));
                            for ($z = 0; $z < count($variousRows); $z++) {
                                $crntRow = explode("~", $variousRows[$z]);
                                if (count($crntRow) == 2) {
                                    $inptParamID = (int) (cleanInputData1($crntRow[0]));
                                    $paramVal = cleanInputData1($crntRow[1]);
                                    $oldSchdlParamID = getAlrtParamID($alrtsAlertID, $inptParamID);

                                    if ($paramVal != "" && $inptParamID > 0) {
                                        if ($oldSchdlParamID <= 0) {
                                            $affctdParams += createPrcsSchdlParms($alrtsAlertID, -1, $inptParamID, $paramVal);
                                        } else {
                                            $affctdParams += updatePrcsSchdlParms($oldSchdlParamID, $inptParamID, $paramVal);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['alrtsAlertID'] = $alrtsAlertID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Alert Successfully Saved!<br/>" . $affctdParams . " Parameters Saved!";
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['alrtsAlertID'] = -1;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Alert Exists <br/>or Data Supplied is Incomplete!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0) {
                $pkID = isset($_POST['sbmtdAlrtID']) ? $_POST['sbmtdAlrtID'] : -1;
                $sbmtdRptID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                $sbmtdAlrtID = $pkID;
                echo $cntent . "<li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Run Alerts</span>
				</li>
                               </ul>
                              </div>";
                $total = get_RptAlertsTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_RptAlerts($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType3 = "col-lg-4";
                $colClassType2 = "col-lg-3";
                ?>
                <form id='allAlrtsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddAlrts === true) {
                            ?> 
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Reports and Processes', '', '', '', 'radio', true, '-1', 'sbmtdAlertRptID', 'None', 'clear', 0, '', function () {
                                                                    getOneAlertStpForm(-1, 4, 'ShowDialog', '0');
                                                                });" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Alert
                                    </button>
                                    <input type="hidden" value="-1" id="sbmtdAlertRptID"/>
                                </div>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-3";
                            $colClassType2 = "col-lg-5";
                            $colClassType3 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allAlrtsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllAlrts(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allAlrtsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAlrts('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAlrts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allAlrtsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Alert Name", "Report Name");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allAlrtsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllAlrts('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllAlrts('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row" style="padding:0px 15px 0px 15px !important"> 
                        <div  class="col-lg-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allAlrtsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <!--<th>Report / Process Name</th>-->
                                            <th style="min-width: 150px;width:150px;">Alert Name</th>
                                            <th>&nbsp;</th>
                                            <?php if ($canEdtAlrts === true) { ?>
                                            <th>&nbsp;</th>
                                            <?php } ?>
                                            <?php if ($canAddAlrts === true) { ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                            <?php if ($canDelAlrts === true) { ?>
                                                <th>&nbsp;</th>
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
                                            <tr id="allAlrtsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <!--<td class="lovtd"><?php echo $row[2]; ?></td>-->
                                                <td class="lovtd"><?php echo $row[3]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allAlrtsRow<?php echo $cntr; ?>_RptID" value="<?php echo $row[1]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="allAlrtsRow<?php echo $cntr; ?>_AlrtID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneRptsParamsForm(<?php echo $row[1]; ?>, -1, '<?php echo $row[3]; ?>', 2, <?php echo $row[0]; ?>, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="Run Alert">
                                                        <img src="cmn_images/98.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canEdtAlrts === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneAlertStpForm(<?php echo $row[0]; ?>, 3, 'ShowDialog', '0');" data-toggle="tooltip" data-placement="bottom" title="Edit Alert">
                                                            <img src="cmn_images/edit32.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php if ($canAddAlrts === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneAlertStpForm(<?php echo $row[0]; ?>, 3, 'ShowDialog', '1');" data-toggle="tooltip" data-placement="bottom" title="Duplicate Alert">
                                                            <img src="cmn_images/Copy.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php if ($canDelAlrts === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAlrts('allAlrtsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Alert">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                        <div  class="col-lg-8" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                <div class="rho-container-fluid" id="alrtsDetailInfo">
                                    <?php
                                    if ($pkID > 0) {
                                        $sbmtdAlrtID = $pkID;
                                        $result1 = get_RptAlertsDet($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            if ($canEdtAlrts === true) {
                                                ?>
                                                <div id="alrtRunsTbPage" class="tab-pane fadein active col-md-12" style="border:none !important;"> 
                                                <?php } else { ?>
                                                    <div class="rho-container-fluid1">
                                                    <?php } ?>       
                                                    <div class="row">
                                                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;"> 
                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                <label for="alrtsRptNm" class="control-label col-lg-4">Report Name:</label>
                                                                <div  class="col-lg-8">
                                                                    <span><?php echo $row1[3]; ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                <label for="alrtsAlertNm" class="control-label col-lg-4">Alert Name:</label>
                                                                <div  class="col-lg-8">
                                                                    <span><?php echo $row1[1]; ?></span>
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>
                                                    <div class="row"  style="padding:1px 3px 1px 3px !important;"><hr style="margin:1px 0px 5px 0px;"></div>
                                                    <div class="row">
                                                        <?php
                                                        $pageNo = 1;
                                                        $lmtSze = 10;
                                                        $srchFor = "%";
                                                        $srchIn = "Report Run ID";
                                                        $colClassType1 = "col-lg-3";
                                                        $colClassType2 = "col-lg-4";
                                                        $colClassType3 = "col-lg-5";
                                                        ?>
                                                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                            <div class="input-group">
                                                                <input class="form-control" id="alrtRunsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAlrtRuns(event, '', '#alrtsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');">
                                                                <input id="alrtRunsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAlrtRuns('clear', '#alrtsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');">
                                                                    <span class="glyphicon glyphicon-remove"></span>
                                                                </label>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAlrtRuns('', '#alrtsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');">
                                                                    <span class="glyphicon glyphicon-search"></span>
                                                                </label> 
                                                            </div>
                                                        </div>
                                                        <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="alrtRunsSrchIn">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "");
                                                                    $srchInsArrys = array("Report Run ID", "Run By", "Run Date", "Run Status");

                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($srchIn == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="alrtRunsDsplySze" style="min-width:70px !important;">                            
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
                                                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 3px 0px 3px !important;">
                                                            <nav aria-label="Page navigation">
                                                                <ul class="pagination" style="margin: 0px !important;">
                                                                    <li>
                                                                        <a class="rhopagination" href="javascript:getAllAlrtRuns('previous', '#alrtsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');" aria-label="Previous">
                                                                            <span aria-hidden="true">&laquo;</span>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="rhopagination" href="javascript:getAllAlrtRuns('next', '#alrtsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');" aria-label="Next">
                                                                            <span aria-hidden="true">&raquo;</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </nav>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">       
                                                            <table class="table table-striped table-bordered table-responsive" id="alrtRunsTable" cellspacing="0" width="100%" style="width:100%;min-width: 530px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No.</th>
                                                                        <th>Report Run ID</th>
                                                                        <th style="min-width:110px !important;">Run Status</th>
                                                                        <th style="min-width:100px !important;">Last Time Active</th>
                                                                        <th>Open Output File</th>
                                                                        <th>Log Files</th>
                                                                        <th>Run By</th>
                                                                        <!--<th>Progress (%)</th>
                                                                        <th>Date Run</th>
                                                                        <th>Output Used</th>-->
                                                                        <th>&nbsp;</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $total = get_AlrtRunsTtl($pkID, $srchFor, $srchIn);
                                                                    if ($pageNo > ceil($total / $lmtSze)) {
                                                                        $pageNo = 1;
                                                                    } else if ($pageNo < 1) {
                                                                        $pageNo = ceil($total / $lmtSze);
                                                                    }

                                                                    $curIdx = $pageNo - 1;
                                                                    $result2 = get_AlrtRuns($pkID, $srchFor, $srchIn, $curIdx, $lmtSze);
                                                                    $cntr = 0;
                                                                    while ($row2 = loc_db_fetch_array($result2)) {
                                                                        $cntr += 1;

                                                                        //$chckd = FALSE; 
                                                                        $outptUsd = $row2[8];
                                                                        $sbmtdAlrtID = $row2[14];
                                                                        $sbmtdRptID = $row2[16];
                                                                        $rpt_src_encrpt = "";
                                                                        if ($outptUsd == "HTML" || $outptUsd == "COLUMN CHART" || $outptUsd == "SIMPLE COLUMN CHART" || $outptUsd == "BAR CHART" || $outptUsd == "PIE CHART" || $outptUsd == "LINE CHART") {
                                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/amcharts_2100/samples/$row2[0].html";
                                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                                            if (file_exists($rpt_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $rpt_src_encrpt = "None";
                                                                            }
                                                                        } else if ($outptUsd == "STANDARD") {
                                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].txt";
                                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                                            if (file_exists($rpt_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $rpt_src_encrpt = "None";
                                                                            }
                                                                        } else if ($outptUsd == "PDF") {
                                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].pdf";
                                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                                            if (file_exists($rpt_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $rpt_src_encrpt = "None";
                                                                            }
                                                                        } else if ($outptUsd == "MICROSOFT WORD") {
                                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].rtf";
                                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                                            if (file_exists($rpt_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $rpt_src_encrpt = "None";
                                                                            }
                                                                        } else if ($outptUsd == "MICROSOFT EXCEL") {
                                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].xls";
                                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                                            if (file_exists($rpt_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $rpt_src_encrpt = "None";
                                                                            }
                                                                        } else if ($outptUsd == "CHARACTER SEPARATED FILE (CSV)") {
                                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].csv";
                                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                                            if (file_exists($rpt_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $rpt_src_encrpt = "None";
                                                                            }
                                                                        } else {
                                                                            $rpt_src_encrpt = "None";
                                                                        }
                                                                        ?>
                                                                        <tr id="alrtRunsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                            <td class="lovtd"><a href="javascript:getOneRptsRnStsForm(<?php echo $sbmtdRptID; ?>, <?php echo $row2[0]; ?>, 3,'0', <?php echo $sbmtdAlrtID; ?>);" style="color:blue;font-weight:bold;"><?php echo $row2[0]; ?></a></td>
                                                                            <td class="lovtd"><span <?php
                                                                                $style2 = "";
                                                                                if ($row2[4] == "Not Started!") {
                                                                                    $style2 = "style=\"background-color: #E8D68C;padding:5px !important;\"";
                                                                                } else if ($row2[4] == "Preparing to Start...") {
                                                                                    $style2 = "style=\"background-color: yellow;padding:5px !important;\"";
                                                                                } else if ($row2[4] == "Running SQL...") {
                                                                                    $style2 = "style=\"background-color: lightgreen;padding:5px !important;\"";
                                                                                } else if ($row2[4] == "Formatting Output...") {
                                                                                    $style2 = "style=\"background-color: lime;padding:5px !important;\"";
                                                                                } else if ($row2[4] == "Storing Output..." || $row2[4] == "Sending Output...") {
                                                                                    $style2 = "style=\"background-color: cyan;padding:5px !important;\"";
                                                                                } else if ($row2[4] == "Completed!") {
                                                                                    $style2 = "style=\"background-color: gainsboro;padding:5px !important;\"";
                                                                                } else if (strpos($row2[4], "Error") !== FALSE) {
                                                                                    $style2 = "style=\"background-color: red;padding:5px !important;\"";
                                                                                }
                                                                                echo $style2;
                                                                                ?>><?php echo $row2[4]; ?></span></td>
                                                                            <td class="lovtd"><span <?php
                                                                                $tst = isDteTmeWthnIntrvl(cnvrtDMYTmToYMDTm($row2[10]), '40 second');
                                                                                $style2 = "";
                                                                                if ($tst == true) {
                                                                                    $style2 = "style=\"background-color: limegreen;color: white; font-size:12px;padding:2px;\"";
                                                                                }
                                                                                echo $style2;
                                                                                ?>><?php echo $row2[10]; ?></span></td>
                                                                            <td class="lovtd">
                                                                                <?php
                                                                                if ($rpt_src_encrpt == "None") {
                                                                                    ?>
                                                                                    <span style="font-weight: bold;color:#FF0000;">
                                                                                        <?php
                                                                                        echo $rpt_src_encrpt;
                                                                                        ?>
                                                                                    </span>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="doAjax('grp=1&typ=11&q=Download&fnm=<?php echo $rpt_src_encrpt; ?>', '', 'Redirect', '', '', '');" data-toggle="tooltip" data-placement="bottom" title="OPEN OUTPUT FILE">
                                                                                        <img src="cmn_images/dwldicon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Output
                                                                                    </button>
                                                                                <?php }
                                                                                ?>
                                                                            </td>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneRptsLogForm(<?php echo $row2[0]; ?>);" data-toggle="tooltip" data-placement="bottom" title="VIEW PROCESS LOG FILE INFO">
                                                                                    <img src="cmn_images/vwlog.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Log
                                                                                </button>
                                                                            </td>
                                                                            <td class="lovtd"><?php echo $row2[2]; ?></td>
                                                                            <!--<td><?php echo $row2[5]; ?></td>
                                                                            <td><span><?php echo $row2[3]; ?></span></td>
                                                                            <td><span><?php echo $row2[8]; ?></span></td>
                                                                            <td class="lovtd"><span><?php echo $row2[11]; ?></span></td>-->
                                                                            <td class="lovtd">
                                                                                <?php if ((float) $row2[15] > 0) { ?>
                                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Alert Message Details" onclick="getOneAlrtsDetForm(<?php echo $row2[15]; ?>);" style="margin: 0px !important;padding:0px 3px 2px 4px !important;">
                                                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                        <img src="cmn_images/kghostview.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                <?php } else { ?>
                                                                                    <span>&nbsp;</span>
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
                                                    <?php
                                                    if ($canEdtAlrts === true) {
                                                        ?>
                                                    </div>
                                                </div>
                                                <!--<div class="row phone-only-btn" style="margin: 0px 0px 10px 0px !important;">
                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#alrtsDetailInfo', 'grp=9&typ=1&pg=2&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');">Alert Runs</button>
                                                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#alrtSetupTbPage', 'grp=9&typ=1&pg=2&vtyp=3&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');">Alerts Setup</button>
                                                    </div>
                                                </div>
                                                <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;">
                                                    <li class="active"><a data-toggle="tab" data-rhodata="&pg=2&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>" href="#alrtRunsTbPage" id="alrtRunsTbPagetab">Alert Runs</a></li>
                                                    <li><a data-toggle="tabajxalrt" data-rhodata="&pg=2&vtyp=3&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>" href="#alrtSetupTbPage" id="alrtSetupTbPagetab">Alerts Setup</a></li>
                                                </ul>                                    
                                                <div class="row">                  
                                                    <div class="col-md-12">
                                                        <div class="custDiv"> 
                                                            <div class="tab-content">
                                                                <div id="alrtSetupTbPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>                       
                                                            </div>                         
                                                        </div>                
                                                    </div> 
                                                </div>--> 
                                            <?php } else { ?>
                                                <?php
                                            }
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
                $pkID = isset($_POST['sbmtdAlrtID']) ? $_POST['sbmtdAlrtID'] : -1;
                if ($pkID > 0) {
                    $sbmtdAlrtID = $pkID;
                    $result1 = get_RptAlertsDet($pkID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        if ($canEdtAlrts === true) {
                            ?>
                            <div id="alrtRunsTbPage" class="tab-pane fadein active col-md-12" style="border:none !important;"> 
                            <?php } else { ?>
                                <div class="rho-container-fluid1">
                                <?php } ?>       
                                <div class="row">
                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;"> 
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="alrtsRptNm" class="control-label col-lg-4">Report Name:</label>
                                            <div  class="col-lg-8">
                                                <span><?php echo $row1[3]; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="alrtsAlertNm" class="control-label col-lg-4">Alert Name:</label>
                                            <div  class="col-lg-8">
                                                <span><?php echo $row1[1]; ?></span>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                                <div class="row"  style="padding:1px 3px 1px 3px !important;"><hr style="margin:1px 0px 5px 0px;"></div>
                                <div class="row">
                                    <?php
                                    $pageNo = 1;
                                    $lmtSze = 10;
                                    $srchFor = "%";
                                    $srchIn = "Report Run ID";
                                    $colClassType1 = "col-lg-3";
                                    $colClassType2 = "col-lg-4";
                                    $colClassType3 = "col-lg-5";
                                    ?>
                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                        <div class="input-group">
                                            <input class="form-control" id="alrtRunsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAlrtRuns(event, '', '#alrtsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');">
                                            <input id="alrtRunsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAlrtRuns('clear', '#alrtsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAlrtRuns('', '#alrtsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');">
                                                <span class="glyphicon glyphicon-search"></span>
                                            </label> 
                                        </div>
                                    </div>
                                    <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="alrtRunsSrchIn">
                                                <?php
                                                $valslctdArry = array("", "", "", "");
                                                $srchInsArrys = array("Report Run ID", "Run By", "Run Date", "Run Status");

                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($srchIn == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="alrtRunsDsplySze" style="min-width:70px !important;">                            
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
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 3px 0px 3px !important;">
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination" style="margin: 0px !important;">
                                                <li>
                                                    <a class="rhopagination" href="javascript:getAllAlrtRuns('previous', '#alrtsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="rhopagination" href="javascript:getAllAlrtRuns('next', '#alrtsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">       
                                        <table class="table table-striped table-bordered table-responsive" id="alrtRunsTable" cellspacing="0" width="100%" style="width:100%;min-width: 530px;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Report Run ID</th>
                                                    <th style="min-width:110px !important;">Run Status</th>
                                                    <th style="min-width:100px !important;">Last Time Active</th>
                                                    <th>Open Output File</th>
                                                    <th>Log Files</th>
                                                    <th>Run By</th>
                                                    <!--<th>Progress (%)</th>
                                                    <th>Date Run</th>
                                                    <th>Output Used</th>-->
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total = get_AlrtRunsTtl($pkID, $srchFor, $srchIn);
                                                if ($pageNo > ceil($total / $lmtSze)) {
                                                    $pageNo = 1;
                                                } else if ($pageNo < 1) {
                                                    $pageNo = ceil($total / $lmtSze);
                                                }

                                                $curIdx = $pageNo - 1;
                                                $result2 = get_AlrtRuns($pkID, $srchFor, $srchIn, $curIdx, $lmtSze);
                                                $cntr = 0;
                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                    $cntr += 1;

                                                    //$chckd = FALSE; 
                                                    $outptUsd = $row2[8];
                                                    $sbmtdAlrtID = $row2[14];
                                                    $sbmtdRptID = $row2[16];
                                                    $rpt_src_encrpt = "";
                                                    if ($outptUsd == "HTML" || $outptUsd == "COLUMN CHART" || $outptUsd == "SIMPLE COLUMN CHART" || $outptUsd == "BAR CHART" || $outptUsd == "PIE CHART" || $outptUsd == "LINE CHART") {
                                                        $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/amcharts_2100/samples/$row2[0].html";
                                                        $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                        if (file_exists($rpt_src)) {
                                                            //file exists!
                                                        } else {
                                                            //file does not exist.
                                                            $rpt_src_encrpt = "None";
                                                        }
                                                    } else if ($outptUsd == "STANDARD") {
                                                        $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].txt";
                                                        $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                        if (file_exists($rpt_src)) {
                                                            //file exists!
                                                        } else {
                                                            //file does not exist.
                                                            $rpt_src_encrpt = "None";
                                                        }
                                                    } else if ($outptUsd == "PDF") {
                                                        $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].pdf";
                                                        $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                        if (file_exists($rpt_src)) {
                                                            //file exists!
                                                        } else {
                                                            //file does not exist.
                                                            $rpt_src_encrpt = "None";
                                                        }
                                                    } else if ($outptUsd == "MICROSOFT WORD") {
                                                        $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].rtf";
                                                        $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                        if (file_exists($rpt_src)) {
                                                            //file exists!
                                                        } else {
                                                            //file does not exist.
                                                            $rpt_src_encrpt = "None";
                                                        }
                                                    } else if ($outptUsd == "MICROSOFT EXCEL") {
                                                        $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].xls";
                                                        $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                        if (file_exists($rpt_src)) {
                                                            //file exists!
                                                        } else {
                                                            //file does not exist.
                                                            $rpt_src_encrpt = "None";
                                                        }
                                                    } else if ($outptUsd == "CHARACTER SEPARATED FILE (CSV)") {
                                                        $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].csv";
                                                        $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                        if (file_exists($rpt_src)) {
                                                            //file exists!
                                                        } else {
                                                            //file does not exist.
                                                            $rpt_src_encrpt = "None";
                                                        }
                                                    } else {
                                                        $rpt_src_encrpt = "None";
                                                    }
                                                    ?>
                                                    <tr id="alrtRunsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                        <td class="lovtd"><a href="javascript:getOneRptsRnStsForm(<?php echo $sbmtdRptID; ?>, <?php echo $row2[0]; ?>, 3,'0', <?php echo $sbmtdAlrtID; ?>);" style="color:blue;font-weight:bold;"><?php echo $row2[0]; ?></a></td>
                                                        <td class="lovtd"><span <?php
                                                            $style2 = "";
                                                            if ($row2[4] == "Not Started!") {
                                                                $style2 = "style=\"background-color: #E8D68C;padding:5px !important;\"";
                                                            } else if ($row2[4] == "Preparing to Start...") {
                                                                $style2 = "style=\"background-color: yellow;padding:5px !important;\"";
                                                            } else if ($row2[4] == "Running SQL...") {
                                                                $style2 = "style=\"background-color: lightgreen;padding:5px !important;\"";
                                                            } else if ($row2[4] == "Formatting Output...") {
                                                                $style2 = "style=\"background-color: lime;padding:5px !important;\"";
                                                            } else if ($row2[4] == "Storing Output..." || $row2[4] == "Sending Output...") {
                                                                $style2 = "style=\"background-color: cyan;padding:5px !important;\"";
                                                            } else if ($row2[4] == "Completed!") {
                                                                $style2 = "style=\"background-color: gainsboro;padding:5px !important;\"";
                                                            } else if (strpos($row2[4], "Error") !== FALSE) {
                                                                $style2 = "style=\"background-color: red;padding:5px !important;\"";
                                                            }
                                                            echo $style2;
                                                            ?>><?php echo $row2[4]; ?></span></td>
                                                        <td class="lovtd"><span <?php
                                                            $tst = isDteTmeWthnIntrvl(cnvrtDMYTmToYMDTm($row2[10]), '40 second');
                                                            $style2 = "";
                                                            if ($tst == true) {
                                                                $style2 = "style=\"background-color: limegreen;color: white; font-size:12px;padding:2px;\"";
                                                            }
                                                            echo $style2;
                                                            ?>><?php echo $row2[10]; ?></span></td>
                                                        <td class="lovtd">
                                                            <?php
                                                            if ($rpt_src_encrpt == "None") {
                                                                ?>
                                                                <span style="font-weight: bold;color:#FF0000;">
                                                                    <?php
                                                                    echo $rpt_src_encrpt;
                                                                    ?>
                                                                </span>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="doAjax('grp=1&typ=11&q=Download&fnm=<?php echo $rpt_src_encrpt; ?>', '', 'Redirect', '', '', '');" data-toggle="tooltip" data-placement="bottom" title="OPEN OUTPUT FILE">
                                                                    <img src="cmn_images/dwldicon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Output
                                                                </button>
                                                            <?php }
                                                            ?>
                                                        </td>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneRptsLogForm(<?php echo $row2[0]; ?>);" data-toggle="tooltip" data-placement="bottom" title="VIEW PROCESS LOG FILE INFO">
                                                                <img src="cmn_images/vwlog.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Log
                                                            </button>
                                                        </td>
                                                        <td class="lovtd"><?php echo $row2[2]; ?></td>
                                                        <!--<td><?php echo $row2[5]; ?></td>
                                                        <td><span><?php echo $row2[3]; ?></span></td>
                                                        <td><span><?php echo $row2[8]; ?></span></td>
                                                        <td class="lovtd"><span><?php echo $row2[11]; ?></span></td>-->
                                                        <td class="lovtd">
                                                            <?php if ((float) $row2[15] > 0) { ?>
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Alert Message Details" onclick="getOneAlrtsDetForm(<?php echo $row2[15]; ?>);" style="margin: 0px !important;padding:0px 3px 2px 4px !important;">
                                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                    <img src="cmn_images/kghostview.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            <?php } else { ?>
                                                                <span>&nbsp;</span>
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
                                <?php
                                if ($canEdtAlrts === true) {
                                    ?>
                                </div>
                            </div>
                            <!--<div class="row phone-only-btn" style="margin: 0px 0px 10px 0px !important;">
                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#alrtsDetailInfo', 'grp=9&typ=1&pg=2&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');">Alert Runs</button>
                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#alrtSetupTbPage', 'grp=9&typ=1&pg=2&vtyp=3&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>');">Alerts Setup</button>
                                </div>
                            </div>
                            <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;">
                                <li class="active"><a data-toggle="tab" data-rhodata="&pg=2&vtyp=1&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>" href="#alrtRunsTbPage" id="alrtRunsTbPagetab">Alert Runs</a></li>
                                <li><a data-toggle="tabajxalrt" data-rhodata="&pg=2&vtyp=3&sbmtdAlrtID=<?php echo $sbmtdAlrtID; ?>" href="#alrtSetupTbPage" id="alrtSetupTbPagetab">Alerts Setup</a></li>
                            </ul>                                    
                            <div class="row">                  
                                <div class="col-md-12">
                                    <div class="custDiv"> 
                                        <div class="tab-content">
                                            <div id="alrtSetupTbPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>                       
                                        </div>                         
                                    </div>                
                                </div> 
                            </div>--> 
                        <?php } else { ?>
                            <?php
                        }
                    }
                } else {
                    ?>
                    <span>No Results Found</span>
                    <?php
                }
            } else if ($vwtyp == 2) {
                //Alert Message Details Form
                $pkID = isset($_POST['sbmtdMsgSntID']) ? $_POST['sbmtdMsgSntID'] : -1;
                $result = get_OneAlertsMsgDet($pkID);
                if ($pkID > 0) {
                    ?>
                    <form id='alertDetForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row"> 
                            <div class="col-md-12">
                                <table class="gridtable" id="alertDetTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px;">
                                    <?php
                                    $output = "";
                                    $cmpID = "";
                                    $i = 0;
                                    $b = 0;
                                    $colsCnt = loc_db_num_fields($result);
                                    $msgID = -1;
                                    while ($row = loc_db_fetch_array($result)) {
                                        $style = "";
                                        $msgID = $row[1];
                                        for ($d = 0; $d < $colsCnt; $d++) {
                                            $style = "";
                                            $style2 = "";
                                            if (trim(loc_db_field_name($result, $d)) == "mt") {
                                                $style = "style=\"display:none;\"";
                                            }
                                            if ($row[$d] == 'No') {
                                                $style2 = "style=\"color:red;font-weight:bold;\"";
                                            } else if ($row[$d] == 'Yes') {
                                                $style2 = "style=\"color:#32CD32;font-weight:bold;\"";
                                            }
                                            $indx = $i - 1;
                                            $hrf1 = "";
                                            $hrf2 = "";
                                            $labl = ucwords(str_replace("_", " ", loc_db_field_name($result, $d)));
                                            if ($d >= 11) {
                                                continue;
                                            } else {
                                                $output .= "<tr $style>";
                                                $output .= "<td width=\"20%\" class=\"likeheader\">" . $labl . ":</td>";
                                            }
                                            if ($d == 14 && $row[15] == '0') {
                                                $arry1 = explode(";", $row[$d]);
                                                $output .= "<td $style>";
                                                for ($r = 0; $r < count($arry1); $r++) {
                                                    if ($arry1[$r] !== "" && $arry1[$r] != "None") {
                                                        $isadmnonly = getActionAdminOnly($row[0], $arry1[$r]);
                                                        if ($isadmnonly == '0' || $isMaster == 1) {
                                                            $webUrlDsply = getActionUrlDsplyTyp($row[0], $arry1[$r]);
                                                            $hrf1 = "<div style=\"padding:2px;float:left;\">"
                                                                    . "<button type=\"button\" class=\"btn btn-primary\""
                                                                    . " onclick=\"actionProcess('$cmpID', '$row[0]','$arry1[$r]','$webUrlDsply','$row[24]','$row[5]','$row[8]','$row[7]');\">";
                                                            $hrf2 = "</button></div>";
                                                            $output .= "$hrf1" . $arry1[$r] . "$hrf2";
                                                        }
                                                    }

                                                    if ($r == count($arry1) - 1) {
                                                        $webUrlDsply = "";
                                                        $hrf1 = "<div style=\"padding:2px;float:left;\">"
                                                                . "<button type=\"button\" class=\"btn btn-primary\" "
                                                                . "onclick=\"actionProcess('$cmpID', '$row[0]','View Attachments','$webUrlDsply','$row[24]','$row[5]','$row[8]','$row[7]');\">";
                                                        $hrf2 = "</button></div>";
                                                        $output .= "$hrf1" . 'View Attachments' . "$hrf2";
                                                    }
                                                }

                                                $output .= "</td>";
                                            } else if ($d == 14 && $row[15] != '0') {
                                                $output .= "<td width=\"70%\" $style2>$hrf1<span style=\"font-weight:bold;font-size:12px;color:green;\">NONE</span>$hrf2</td>";
                                            } else if ($d == 8) {
                                                $output .= "<td width=\"70%\" $style2>$hrf1<span style=\"font-weight:bold;font-size:12px;color:blue;\">" . strtoupper($row[$d]) . "</span>$hrf2</td>";
                                            } else {
                                                $output .= "<td width=\"70%\" $style2>$hrf1" . $row[$d] . "$hrf2</td>";
                                            }
                                            $output .= "</tr>";
                                        }
                                        $i++;
                                        $b++;
                                    }
                                    echo $output;
                                    ?>
                                </table>
                            </div>
                        </div>
                    </form>
                    <?php
                } else {
                    ?>
                    <span>No Results Found</span>

                    <?php
                }
            } else if ($vwtyp == 3) {
                $pkID = isset($_POST['sbmtdAlrtID']) ? $_POST['sbmtdAlrtID'] : -1;
                $shdDplct = isset($_POST['shdDplct']) ? $_POST['shdDplct'] : '0';
                $sbmtdAlrtID = -1;
                $sbmtdAlrtIDDsply = -1;
                $alrtsAlertNm = "";
                if ($pkID > 0) {
                    $sbmtdAlrtID = $pkID;
                    $result1 = get_RptAlertsDet($pkID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        if ($shdDplct == '0') {
                            $sbmtdAlrtIDDsply = $pkID;
                            $alrtsAlertNm = $row1[1];
                        } else {
                            $sbmtdAlrtIDDsply = -1;
                            $alrtsAlertNm = "(Duplicate) " . $row1[1];
                        }
                        $sbmtdRptID = $row1[2];
                        if ($canEdtAlrts === true) {
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAlertsForm();" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save Alert
                                    </button>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;"> 
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsRptNm" class="control-label col-lg-4">Report Name:</label>
                                    <div  class="col-lg-8">
                                        <span><?php echo $row1[3]; ?></span>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsAlertNm" class="control-label col-lg-4">Alert Name:</label>
                                    <div  class="col-lg-8">
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <input type="text" class="form-control" aria-label="..." id="alrtsAlertNm" name="alrtsAlertNm" value="<?php echo $alrtsAlertNm; ?>" style="width:100%;">
                                            <input type="hidden" class="form-control" aria-label="..." id="alrtsAlertID" name="alrtsAlertID" value="<?php echo $sbmtdAlrtIDDsply; ?>" style="width:100%;">
                                            <input type="hidden" class="form-control" aria-label="..." id="alrtsRptID" name="alrtsRptID" value="<?php echo $row1[2]; ?>" style="width:100%;">
                                        <?php } else { ?>
                                            <span><?php echo $row1[1]; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsAlrtTyp" class="control-label col-lg-4">Alert Type:</label>
                                    <div class="col-lg-8">
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="alrtsAlrtTyp" name="alrtsAlrtTyp">
                                                <?php
                                                $valslctdArry = array("", "", "");
                                                $srchInsArrys = array("Email", "SMS", "Local Inbox");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($row1[8] == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } else {
                                            ?>
                                            <span><?php echo $row1[8]; ?></span>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsAlertDesc" class="control-label col-lg-4">Description:</label>
                                    <div  class="col-lg-8">
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <input type="text" class="form-control" aria-label="..." id="alrtsAlertDesc" name="alrtsAlertDesc" value="<?php echo $row1[4]; ?>" style="width:100%;">
                                        <?php } else { ?>
                                            <span><?php echo $row1[4]; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>                                                        
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtSchdlsStrtDte" class="control-label col-lg-4">Start From:</label>
                                    <div class="col-lg-8">
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                <input class="form-control rqrdFld" size="16" type="text" id="alrtSchdlsStrtDte" name="alrtsStrtDte" value="<?php echo $row1[14]; ?>" readonly="">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div> 
                                        <?php } else { ?>
                                            <span><?php echo $row1[14]; ?></span>
                                        <?php } ?>
                                    </div>
                                </div> 
                            </div>
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsIntrvl" class="control-label col-lg-9">Repeat Interval:</label>
                                    <div class="col-lg-3">
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <input type="number" min="-999999999999" max="999999999999" class="form-control rqrdFld" aria-label="..." id="alrtsIntrvl" name="alrtsIntrvl" value="<?php echo $row1[16]; ?>" style="width:100%;">
                                        <?php } else { ?>
                                            <span><?php echo $row1[16]; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsIntvlUom" class="control-label col-lg-6">UOM:</label>
                                    <div class="col-lg-6">
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="alrtsIntvlUom" name="alrtsIntvlUom">
                                                <?php
                                                $valslctdArry = array("", "", "", "", "", "", "");
                                                $srchInsArrys = array("Second(s)", "Minute(s)", "Hour(s)", "Day(s)", "Week(s)", "Month(s)", "Year(s)");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($row1[15] == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } else { ?>
                                            <span><?php echo $row1[15]; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsEndHour" class="control-label col-lg-9">End Hour:</label>
                                    <div class="col-lg-3">
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <input type="number" min="-999999999999" max="999999999999" class="form-control rqrdFld" aria-label="..." id="alrtsEndHour" name="alrtsEndHour" value="<?php echo $row1[19]; ?>" style="width:100%;">
                                        <?php } else { ?>
                                            <span><?php echo $row1[19]; ?></span>
                                        <?php } ?></div>
                                </div>                                                        
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsRnAtHr" class="control-label col-lg-7">Run Only at Hour Specified?:</label>
                                    <div  class="col-lg-5">
                                        <?php
                                        $chkdYes = "";
                                        $chkdNo = "checked=\"\"";
                                        if ($row1[17] == "1") {
                                            $chkdNo = "";
                                            $chkdYes = "checked=\"\"";
                                        }
                                        ?>
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <label class="radio-inline"><input type="radio" name="alrtsRnAtHr" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                            <label class="radio-inline"><input type="radio" name="alrtsRnAtHr" value="NO" <?php echo $chkdNo; ?>>NO</label>                                                                
                                        <?php } else { ?>                                                            
                                            <span><?php echo ($row1[17] == "1" ? "YES" : "NO"); ?></span>                                        <?php } ?>
                                    </div>
                                </div>                                                        
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsRnLnkdRpt" class="control-label col-lg-7">Run Linked Report/Process?:</label>
                                    <div  class="col-lg-5">
                                        <?php
                                        $chkdYes = "";
                                        $chkdNo = "checked=\"\"";
                                        if ($row1[13] == "1") {
                                            $chkdNo = "";
                                            $chkdYes = "checked=\"\"";
                                        }
                                        ?>
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <label class="radio-inline"><input type="radio" name="alrtsRnLnkdRpt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                            <label class="radio-inline"><input type="radio" name="alrtsRnLnkdRpt" value="NO" <?php echo $chkdNo; ?>>NO</label>                                                                
                                        <?php } else { ?>
                                            <span><?php echo ($row1[13] == "1" ? "YES" : "NO"); ?></span>
                                        <?php } ?></div>
                                </div>                                                   
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsIsEnabled" class="control-label col-lg-7">Enabled?:</label>
                                    <div  class="col-lg-5">
                                        <?php
                                        $chkdYes = "";
                                        $chkdNo = "checked=\"\"";
                                        if ($row1[9] == "1") {
                                            $chkdNo = "";
                                            $chkdYes = "checked=\"\"";
                                        }
                                        ?>
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <label class="radio-inline"><input type="radio" name="alrtsIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                            <label class="radio-inline"><input type="radio" name="alrtsIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                        <?php } else {
                                            ?>
                                            <span><?php echo ($row1[9] == "1" ? "YES" : "NO"); ?></span>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>   
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 20px 3px !important;">
                                <label for="alrtsParamSQL" class="control-label col-lg-2">Parameter Set Generation SQL:</label>
                                <div  class="col-lg-10">
                                    <?php if ($canEdtAlrts === true) { ?>
                                        <textarea class="form-control rqrdFld" aria-label="..." id="alrtsParamSQL" name="alrtsParamSQL" style="width:100%;" cols="9" rows="9"><?php echo $row1[12]; ?></textarea>
                                    <?php } else {
                                        ?>
                                        <span><?php echo $row1[12]; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5" style="padding:0px 3px 0px 3px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsMailTo" class="control-label col-md-2">To:</label>
                                    <div  class="col-md-10">
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <div  class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                <textarea class="form-control rqrdFld" id="alrtsMailTo" name="alrtsMailTo"  cols="2" placeholder="To" rows="4"><?php echo $row1[5]; ?></textarea>
                                            </div>
                                            <div  class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                <button type="button" class="btn btn-default btn-sm" style="float:right;"><span class="glyphicon glyphicon-th-list"></span></button>
                                            </div>
                                        <?php } else {
                                            ?>
                                            <span><?php echo $row1[5]; ?></span>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>                                         
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsMailCc" class="control-label col-md-2">Cc:</label>
                                    <div  class="col-md-10">
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <div  class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                <input class="form-control" id="alrtsMailCc" name="alrtsMailCc" type = "text" placeholder="Cc" value="<?php echo $row1[6]; ?>">
                                            </div>
                                            <div  class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                <button type="button" class="btn btn-default btn-sm" style="float:right;"><span class="glyphicon glyphicon-th-list"></span></button>
                                            </div>
                                        <?php } else {
                                            ?>
                                            <span><?php echo $row1[6]; ?></span>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div> 
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsMailBcc" class="control-label col-md-2">Bcc:</label>
                                    <div  class="col-md-10">
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <div  class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                <input class="form-control" id="alrtsMailBcc" name="alrtsMailBcc" type = "text" placeholder="Bcc" value="<?php echo $row1[11]; ?>">
                                            </div>
                                            <div  class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                <button type="button" class="btn btn-default btn-sm" style="float:right;"><span class="glyphicon glyphicon-th-list"></span></button>
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
                                    <label for="alrtsMailSubject" class="control-label col-md-2">Subject:</label>
                                    <?php if ($canEdtAlrts === true) { ?>
                                        <div  class="col-md-10" style="padding:0px 1px 0px 15px !important;">
                                            <input class="form-control" id="alrtsMailSubject" name="alrtsMailSubject" type = "text" placeholder="Subject" value="<?php echo $row1[10]; ?>">
                                        </div>
                                    <?php } else {
                                        ?>
                                        <span><?php echo $row1[10]; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsMailAttchmnts" class="control-label col-md-2">Files <span class="glyphicon glyphicon-paperclip"></span>:</label>
                                    <div  class="col-md-10">
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <div  class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                <textarea class="form-control" id="alrtsMailAttchmnts" name="alrtsMailAttchmnts" cols="2" placeholder="Attachments" rows="8"><?php echo $row1[18]; ?></textarea>
                                            </div>
                                            <div  class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                <button type="button" class="btn btn-default btn-sm" style="float:right;" onclick="attchFileToAlrt();"><span class="glyphicon glyphicon-th-list"></span></button>
                                            </div>
                                        <?php } else {
                                            ?>
                                            <span><?php echo $row1[18]; ?></span>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-7" style="padding:0px 15px 0px 3px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="alrtsMsgBody" class="control-label col-xs-12">Alert Message Body:</label>
                                    <div  class="col-xs-12" style="padding:0px 1px 0px 1px !important;">
                                        <?php if ($canEdtAlrts === true) { ?>
                                            <div id="alrtsMsgBody"></div>
                                            <input type="hidden" id="alrtsMsgBodyEncded" value="<?php echo urlencode($row1[7]); ?>">
                                        <?php } else {
                                            ?>
                                            <span><?php echo $row1[7]; ?></span>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="alrtSchdlPrmsTable" cellspacing="0" width="100%" style="width:100%;min-width: 330px;">
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
                                            if ($sbmtdAlrtID > 0) {
                                                $nwval1 = get_SchdldAlrtParamVal($sbmtdAlrtID, $row[0]);
                                            }
                                            $lovnm = $row[6];
                                            $dataTyp = $row[7];
                                            $dtFrmt = $row[8];
                                            ?>
                                            <tr id="alrtSchdlPrmsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?><input type="hidden" class="form-control" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $row[0]; ?>"></td>
                                                <td class="lovtd">
                                                    <?php if ($lovnm !== "") { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
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
                                                                <input class="form-control <?php echo $isrqrd ?>" size="16" type="text" id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" name="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" readonly="">
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div>                                                                
                                                        </div>
                                                        <?php
                                                    } else if ($dataTyp == "NUMBER") {
                                                        ?>                                            
                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                            <input type="number" min="-999999999999" max="999999999999" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" name="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" style="width:100%;">
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>                                            
                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                            <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" style="width:100% !important;">
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
                                                if ($sbmtdAlrtID > 0) {
                                                    $colNoVals[$d] = get_SchdldAlrtParamVal($sbmtdAlrtID, $sysParaIDs[$d]);
                                                } else {
                                                    $colNoVals[$d] = $row1[$d];
                                                }
                                            }
                                        }
                                        for ($d = 0; $d < count($colNoVals); $d++) {
                                            $cntr ++;
                                            $isrqrd = "";
                                            ?>
                                            <tr id="alrtSchdlPrmsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $sysParaNames[$d]; ?><input type="hidden" class="form-control" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $sysParaIDs[$d]; ?>"></td>
                                                <td class="lovtd">  
                                                    <?php
                                                    if ($sysParaIDs[$d] == "-190") {
                                                        $lovnm = "Report Output Formats";
                                                        ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
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
                                                                <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>                                            
                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                            <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>" style="width:100% !important;">
                                                        </div>
                                                    <?php }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                    }
                }
            } else if ($vwtyp == 4) {
                //New Alert Form
                $sbmtdRptID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                $sbmtdRptNm = getGnrlRecNm("rpt.rpt_reports", "report_id", "report_name", $sbmtdRptID);
                if ($canEdtAlrts === true && $sbmtdRptID > 0) {
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAlertsForm();" style="width:100% !important;">
                                <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                Save Alert
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;"> 
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsRptNm" class="control-label col-lg-4">Report Name:</label>
                                <div  class="col-lg-8">
                                    <span><?php echo $sbmtdRptNm; ?></span>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsAlertNm" class="control-label col-lg-4">Alert Name:</label>
                                <div  class="col-lg-8">
                                    <input type="text" class="form-control" aria-label="..." id="alrtsAlertNm" name="alrtsAlertNm" value="" style="width:100%;">
                                    <input type="hidden" class="form-control" aria-label="..." id="alrtsAlertID" name="alrtsAlertID" value="-1" style="width:100%;">
                                    <input type="hidden" class="form-control" aria-label="..." id="isNew123" name="isNew123" value="5">
                                    <input type="hidden" class="form-control" aria-label="..." id="alrtsRptID" name="alrtsRptID" value="<?php echo $sbmtdRptID; ?>" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsAlrtTyp" class="control-label col-lg-4">Alert Type:</label>
                                <div class="col-lg-8">
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="alrtsAlrtTyp" name="alrtsAlrtTyp">
                                        <?php
                                        $valslctdArry = array("", "", "");
                                        $srchInsArrys = array("Email", "SMS", "Local Inbox");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsAlertDesc" class="control-label col-lg-4">Description:</label>
                                <div  class="col-lg-8">
                                    <input type="text" class="form-control" aria-label="..." id="alrtsAlertDesc" name="alrtsAlertDesc" value="" style="width:100%;">
                                </div>
                            </div>                                                        
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtSchdlsStrtDte" class="control-label col-lg-4">Start From:</label>
                                <div class="col-lg-8">
                                    <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                        <input class="form-control rqrdFld" size="16" type="text" id="alrtSchdlsStrtDte" name="alrtsStrtDte" value="<?php echo date('d-M-Y H:i:s'); ?>" readonly="">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div> 
                                </div>
                            </div> 
                        </div>
                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsIntrvl" class="control-label col-lg-9">Repeat Interval:</label>
                                <div class="col-lg-3">
                                    <input type="number" min="-999999999999" max="999999999999" class="form-control rqrdFld" aria-label="..." id="alrtsIntrvl" name="alrtsIntrvl" value="-1" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsIntvlUom" class="control-label col-lg-6">UOM:</label>
                                <div class="col-lg-6">
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="alrtsIntvlUom" name="alrtsIntvlUom">
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
                                <label for="alrtsEndHour" class="control-label col-lg-9">End Hour:</label>
                                <div class="col-lg-3">
                                    <input type="number" min="-999999999999" max="999999999999" class="form-control rqrdFld" aria-label="..." id="alrtsEndHour" name="alrtsEndHour" value="" style="width:100%;">
                                </div>
                            </div>                                                        
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsRnAtHr" class="control-label col-lg-7">Run Only at Hour Specified?:</label>
                                <div  class="col-lg-5">
                                    <?php
                                    $chkdYes = "";
                                    $chkdNo = "checked=\"\"";
                                    ?>
                                    <label class="radio-inline"><input type="radio" name="alrtsRnAtHr" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                    <label class="radio-inline"><input type="radio" name="alrtsRnAtHr" value="NO" <?php echo $chkdNo; ?>>NO</label>                                                                
                                </div>
                            </div>                                                        
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsRnLnkdRpt" class="control-label col-lg-7">Run Linked Report/Process?:</label>
                                <div  class="col-lg-5">
                                    <?php
                                    $chkdYes = "";
                                    $chkdNo = "checked=\"\"";
                                    ?>
                                    <label class="radio-inline"><input type="radio" name="alrtsRnLnkdRpt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                    <label class="radio-inline"><input type="radio" name="alrtsRnLnkdRpt" value="NO" <?php echo $chkdNo; ?>>NO</label>                                                                
                                </div>
                            </div>                                                   
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsIsEnabled" class="control-label col-lg-7">Enabled?:</label>
                                <div  class="col-lg-5">
                                    <?php
                                    $chkdYes = "";
                                    $chkdNo = "checked=\"\"";
                                    ?>
                                    <label class="radio-inline"><input type="radio" name="alrtsIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                    <label class="radio-inline"><input type="radio" name="alrtsIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                </div>
                            </div>   
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 20px 3px !important;">
                            <label for="alrtsParamSQL" class="control-label col-lg-2">Parameter Set Generation SQL:</label>
                            <div  class="col-lg-10">
                                <textarea class="form-control rqrdFld" aria-label="..." id="alrtsParamSQL" name="alrtsParamSQL" style="width:100%;" cols="9" rows="9"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5" style="padding:0px 3px 0px 3px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsMailTo" class="control-label col-md-2">To:</label>
                                <div  class="col-md-10">
                                    <div  class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                        <textarea class="form-control rqrdFld" id="alrtsMailTo" name="alrtsMailTo"  cols="2" placeholder="To" rows="4"></textarea>
                                    </div>
                                    <div  class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                        <button type="button" class="btn btn-default btn-sm" style="float:right;"><span class="glyphicon glyphicon-th-list"></span></button>
                                    </div>
                                </div>
                            </div>                                         
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsMailCc" class="control-label col-md-2">Cc:</label>
                                <div class="col-md-10">                                    
                                    <div  class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                        <input class="form-control" id="alrtsMailCc" name="alrtsMailCc" type = "text" placeholder="Cc" value="">
                                    </div>
                                    <div  class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                        <button type="button" class="btn btn-default btn-sm" style="float:right;"><span class="glyphicon glyphicon-th-list"></span></button>
                                    </div>                                    
                                </div>
                            </div> 
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsMailBcc" class="control-label col-md-2">Bcc:</label>
                                <div  class="col-md-10">
                                    <div  class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                        <input class="form-control" id="alrtsMailBcc" name="alrtsMailBcc" type = "text" placeholder="Bcc" value="">
                                    </div>
                                    <div  class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                        <button type="button" class="btn btn-default btn-sm" style="float:right;"><span class="glyphicon glyphicon-th-list"></span></button>
                                    </div>
                                </div>
                            </div>       
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsMailSubject" class="control-label col-md-2">Subject:</label>                                
                                <div  class="col-md-10" style="padding:0px 1px 0px 15px !important;">
                                    <input class="form-control" id="alrtsMailSubject" name="alrtsMailSubject" type = "text" placeholder="Subject" value="">
                                </div>                                
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsMailAttchmnts" class="control-label col-md-2">Files <span class="glyphicon glyphicon-paperclip"></span>:</label>
                                <div  class="col-md-10">
                                    <div  class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                        <textarea class="form-control" id="alrtsMailAttchmnts" name="alrtsMailAttchmnts" cols="2" placeholder="Attachments" rows="8"></textarea>
                                    </div>
                                    <div  class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                        <button type="button" class="btn btn-default btn-sm" style="float:right;" onclick="attchFileToAlrt();"><span class="glyphicon glyphicon-th-list"></span></button>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-7" style="padding:0px 15px 0px 3px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="alrtsMsgBody" class="control-label col-xs-12">Alert Message Body:</label>
                                <div  class="col-xs-12" style="padding:0px 1px 0px 1px !important;">
                                    <div id="alrtsMsgBody"></div>
                                    <input type="hidden" id="alrtsMsgBodyEncded" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class=" col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="alrtSchdlPrmsTable" cellspacing="0" width="100%" style="width:100%;min-width: 330px;">
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
                                        $lovnm = $row[6];
                                        $dataTyp = $row[7];
                                        $dtFrmt = $row[8];
                                        ?>
                                        <tr id="alrtSchdlPrmsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $row[1]; ?><input type="hidden" class="form-control" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $row[0]; ?>"></td>
                                            <td class="lovtd">
                                                <?php if ($lovnm !== "") { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
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
                                                            <input class="form-control <?php echo $isrqrd ?>" size="16" type="text" id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" name="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" readonly="">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>                                                                
                                                    </div>
                                                    <?php
                                                } else if ($dataTyp == "NUMBER") {
                                                    ?>                                            
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <input type="number" min="-999999999999" max="999999999999" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" name="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" style="width:100%;">
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>                                            
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" style="width:100% !important;">
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
                                        $cntr ++;
                                        $isrqrd = "";
                                        ?>
                                        <tr id="alrtSchdlPrmsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $sysParaNames[$d]; ?><input type="hidden" class="form-control" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $sysParaIDs[$d]; ?>"></td>
                                            <td class="lovtd">  
                                                <?php
                                                if ($sysParaIDs[$d] == "-190") {
                                                    $lovnm = "Report Output Formats";
                                                    ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
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
                                                            <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>                                            
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="alrtSchdlPrmsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>" style="width:100% !important;">
                                                    </div>
                                                <?php }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                } else {
                    echo restricted();
                }
            } else if ($vwtyp == 5) {
                
            } else if ($vwtyp == 6) {
                
            } else if ($vwtyp == 7) {
                
            }
        }
    }
}
?>