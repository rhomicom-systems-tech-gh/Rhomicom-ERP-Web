<?php
$canAdd = test_prmssns($dfltPrvldgs[11], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[12], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[13], $mdlNm);
$canVwRcHstry = test_prmssns($dfltPrvldgs[7], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Time Table */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteTimeTable($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Time Table Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteTimeTableDLn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Tax Codes
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $attnTmeTblID = isset($_POST['attnTmeTblID']) ? (int) cleanInputData($_POST['attnTmeTblID']) : -1;
                $attnTmeTblName = isset($_POST['attnTmeTblName']) ? cleanInputData($_POST['attnTmeTblName']) : "";
                $attnTmeTblDesc = isset($_POST['attnTmeTblDesc']) ? cleanInputData($_POST['attnTmeTblDesc']) : "";
                $attnTmeTblEvntType = isset($_POST['attnTmeTblEvntType']) ? cleanInputData($_POST['attnTmeTblEvntType']) : "";
                $attnTmeTblSlotDrtn = isset($_POST['attnTmeTblSlotDrtn']) ? (float) cleanInputData($_POST['attnTmeTblSlotDrtn']) : 0;

                $attnTmeMajTmDivType = isset($_POST['attnTmeMajTmDivType']) ? cleanInputData($_POST['attnTmeMajTmDivType']) : "";
                $attnTmeMajTmStrtVal = isset($_POST['attnTmeMajTmStrtVal']) ? cleanInputData($_POST['attnTmeMajTmStrtVal']) : "";
                $attnTmeMajTmEndVal = isset($_POST['attnTmeMajTmEndVal']) ? cleanInputData($_POST['attnTmeMajTmEndVal']) : "";

                $attnTmeMinTmDivType = isset($_POST['attnTmeMinTmDivType']) ? cleanInputData($_POST['attnTmeMinTmDivType']) : "";
                $attnTmeMinTmStrtVal = isset($_POST['attnTmeMinTmStrtVal']) ? cleanInputData($_POST['attnTmeMinTmStrtVal']) : "";
                $attnTmeMinTmEndVal = isset($_POST['attnTmeMinTmEndVal']) ? cleanInputData($_POST['attnTmeMinTmEndVal']) : "";

                $attnTmeTblIsEnbld = isset($_POST['attnTmeTblIsEnbld']) ? (cleanInputData($_POST['attnTmeTblIsEnbld']) == "YES" ? TRUE : FALSE) : FALSE;
                $slctdAttnTimesLns = isset($_POST['slctdAttnTimesLns']) ? cleanInputData($_POST['slctdAttnTimesLns']) : "";

                $exitErrMsg = "";
                if ($attnTmeTblName == "") {
                    $exitErrMsg .= "Please enter Time Table Name!<br/>";
                }
                if ($attnTmeTblEvntType == "") {
                    $exitErrMsg .= "Please enter Event Classification!<br/>";
                }
                if ($attnTmeMajTmDivType == "" || $attnTmeMajTmStrtVal == "" || $attnTmeMajTmEndVal == "") {
                    $exitErrMsg .= "Please enter all Major Time Division Details!<br/>";
                }
                if ($attnTmeMinTmDivType == "" || $attnTmeMinTmStrtVal == "" || $attnTmeMinTmEndVal == "") {
                    $exitErrMsg .= "Please enter all Minor Time Division Details!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['attnTmeTblID'] = $attnTmeTblID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($attnTmeMajTmDivType == "04-Month") {
                    $attnTmeMajTmStrtVal = getMonthNum($attnTmeMajTmStrtVal);
                    $attnTmeMajTmEndVal = getMonthNum($attnTmeMajTmEndVal);
                }
                if ($attnTmeMinTmDivType == "04-Month") {
                    $attnTmeMinTmStrtVal = getMonthNum($attnTmeMinTmStrtVal);
                    $attnTmeMinTmEndVal = getMonthNum($attnTmeMinTmEndVal);
                }

                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                $oldID = getTmeTblID($attnTmeTblName, $orgID);
                if (($oldID <= 0 || $oldID == $attnTmeTblID)) {
                    if ($attnTmeTblID <= 0) {
                        $afftctd += createTimeTable($orgID, $attnTmeTblName, $attnTmeTblDesc, $attnTmeTblEvntType, $attnTmeTblIsEnbld,
                                $attnTmeTblSlotDrtn, $attnTmeMajTmDivType, $attnTmeMajTmStrtVal, $attnTmeMajTmEndVal,
                                $attnTmeMinTmDivType, $attnTmeMinTmStrtVal, $attnTmeMinTmEndVal);
                        $attnTmeTblID = getTmeTblID($attnTmeTblName, $orgID);
                    } else {
                        $afftctd += updateTimeTable($attnTmeTblID, $attnTmeTblName, $attnTmeTblDesc, $attnTmeTblEvntType, $attnTmeTblIsEnbld,
                                $attnTmeTblSlotDrtn, $attnTmeMajTmDivType, $attnTmeMajTmStrtVal, $attnTmeMajTmEndVal,
                                $attnTmeMinTmDivType, $attnTmeMinTmStrtVal, $attnTmeMinTmEndVal);
                    }
                    if (trim($slctdAttnTimesLns, "|~") != "") {
                        //Save Petty Cash Double Entry Lines
                        $variousRows = explode("|", trim($slctdAttnTimesLns, "|"));
                        //echo count($variousRows);
                        for ($y = 0; $y < count($variousRows); $y++) {
                            //var_dump($crntRow);
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 12) {
                                $ln_TmeTblLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_EvntID = (float) cleanInputData1($crntRow[1]);
                                $ln_VnuID = (float) cleanInputData1($crntRow[2]);
                                $ln_HostID = (float) cleanInputData1($crntRow[3]);
                                $ln_EvntNm = cleanInputData1($crntRow[4]);
                                $ln_MajStrt = cleanInputData1($crntRow[5]);
                                $ln_MinStrt = cleanInputData1($crntRow[6]);
                                $ln_MajEnd = cleanInputData1($crntRow[7]);
                                $ln_MinEnd = cleanInputData1($crntRow[8]);
                                $ln_VnuNm = cleanInputData1($crntRow[9]);
                                $ln_HostNm = cleanInputData1($crntRow[10]);
                                $ln_IsEnabled = (cleanInputData1($crntRow[11]) == "YES") ? TRUE : FALSE;
                                if ($attnTmeMajTmDivType == "04-Month") {
                                    $ln_MajStrt = getMonthNum($ln_MajStrt);
                                    $ln_MajEnd = getMonthNum($ln_MajEnd);
                                }
                                if ($attnTmeMinTmDivType == "04-Month") {
                                    $ln_MinStrt = getMonthNum($ln_MinStrt);
                                    $ln_MinEnd = getMonthNum($ln_MinEnd);
                                }
                                $errMsg = "";
                                if ($ln_EvntID <= 0) {
                                    $errMsg = "Row " . ($y + 1) . ":- Event Description and Name are all required Fields!<br/>";
                                }
                                if ($ln_MajStrt == "" || $ln_MinStrt == "" || $ln_MajEnd == "" || $ln_MinEnd == "") {
                                    $errMsg = "Row " . ($y + 1) . ":- Time Division Values are all required Fields!<br/>";
                                }
                                $oldTableLnID = getTmTblDtID($attnTmeTblID, $ln_EvntID, $ln_MajStrt, $ln_MinStrt, $ln_VnuID);
                                if ($errMsg === "") {
                                    if ($ln_TmeTblLnID <= 0 && $oldTableLnID <= 0) {
                                        $afftctd1 += createTimeTableDetLn($ln_TmeTblLnID, $attnTmeTblID, $ln_EvntID,
                                                $ln_MajStrt, $ln_MinStrt, $ln_IsEnabled, $ln_HostID, $ln_VnuID, $ln_MajEnd,
                                                $ln_MinEnd);
                                    } else if ($ln_TmeTblLnID === $oldTableLnID || $oldTableLnID <= 0) {
                                        $afftctd1 += updtTimeTableDetLn($ln_TmeTblLnID, $attnTmeTblID, $ln_EvntID,
                                                $ln_MajStrt, $ln_MinStrt, $ln_IsEnabled, $ln_HostID, $ln_VnuID, $ln_MajEnd,
                                                $ln_MinEnd);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }

                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Time Table Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd1 . " Time Table Line(s) Saved!"
                                . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Time Table Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd1 . " Time Table Line(s) Saved!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['attnTmeTblID'] = $attnTmeTblID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Time Table Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['attnTmeTblID'] = $attnTmeTblID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0 || $vwtyp == 1 || $vwtyp == 2) {
                $oldID = getTmeTblID("DAILY VISITORS REGISTER", $orgID);
                if ($oldID <= 0) {
                    $attnEvntStpID = getEventID("Normal Daily Visits", $orgID);
                    if ($attnEvntStpID <= 0) {
                        createEvent($orgID, "Normal Daily Visits", "Normal Daily Visits", "R",
                                true, -1, "Everyone", -1,
                                0, 0, 0,
                                "Daily Visitor", -1, "", "Attendance HeadCount Metrics",
                                "", -1, -1);
                        $attnEvntStpID = getEventID("Normal Daily Visits", $orgID);
                    }
                    createTimeTable($orgID, "DAILY VISITORS REGISTER", "DAILY VISITORS REGISTER", "Daily Visit", true,
                            30, "04-Month", "01-JAN", "12-DEC",
                            "09-Days in a Month", "Day 01", "Day 31");
                    $attnTmeTblID = getTmeTblID("DAILY VISITORS REGISTER", $orgID);
                    $ln_TmeTblLnID = getNewTmTblDtID();
                    createTimeTableDetLn($ln_TmeTblLnID, $attnTmeTblID, $attnEvntStpID,
                            "01-JAN", "Day 01", true, -1, -1, "12-DEC",
                            "Day 31");
                }
                $pkID = isset($_POST['sbmtdAttnTmeTblID']) ? $_POST['sbmtdAttnTmeTblID'] : -1;
                if ($vwtyp == 0) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Time Tables</span>
				</li>
                               </ul>
                              </div>";

                    $total = get_Total_TmeTbl($srchFor, $srchIn, $orgID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_Basic_TmeTbl($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='attnTmeTblForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php if ($canAdd === true) { ?> 
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 0px 0px 0px !important;"> 
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAttnTmeTblForm(-1, 1);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Time Table">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Time Table
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAttnTmeTblForm();" style="width:100% !important;">
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
                                    <input class="form-control" id="attnTmeTblSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAttnTmeTbl(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="attnTmeTblPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnTmeTbl('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnTmeTbl('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attnTmeTblSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Time Table Description", "Time Table Name");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attnTmeTblDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getAttnTmeTbl('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttnTmeTbl('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
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
                                    <table class="table table-striped table-bordered table-responsive" id="attnTmeTblTable" cellspacing="0" width="100%" style="width:100% !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Time Table Name</th>
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
                                                <tr id="attnTmeTblRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $row[1]; ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnTmeTblRow<?php echo $cntr; ?>_AttnTmeTblID" value="<?php echo $row[0]; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnTmeTblRow<?php echo $cntr; ?>_AttnTmeTblNm" value="<?php echo $row[1]; ?>">
                                                    </td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnTmeTbl('attnTmeTblRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Time Table">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|attn.attn_time_table_hdrs|time_table_id"),
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
                                    <div class="container-fluid" id="attnTmeTblDetailInfo">
                                        <?php
                                    }
                                    $attnTmeTblID = -1;
                                    $attnTmeTblName = "";
                                    $attnTmeTblDesc = "";
                                    $attnTmeTblEvntType = "";
                                    $attnTmeMajTmDivType = "";
                                    $attnTmeMajTmStrtVal = "";
                                    $attnTmeMajTmEndVal = "";
                                    $attnTmeMinTmDivType = "";
                                    $attnTmeMinTmStrtVal = "";
                                    $attnTmeMinTmEndVal = "";
                                    $attnTmeTblIsEnbld = "0";
                                    $attnTmeTblSlotDrtn = 0.00;
                                    if ($pkID > 0) {
                                        $attnTmeTblID = $pkID;
                                        $result1 = get_One_TmeTblHdrDet($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $attnTmeTblID = $row1[0];
                                            $attnTmeTblName = $row1[1];
                                            $attnTmeTblDesc = $row1[2];
                                            $attnTmeTblEvntType = $row1[10];
                                            $attnTmeMajTmDivType = $row1[4];
                                            $attnTmeMajTmStrtVal = $row1[5];
                                            $attnTmeMajTmEndVal = $row1[6];
                                            $attnTmeMinTmDivType = $row1[7];
                                            $attnTmeMinTmStrtVal = $row1[8];
                                            $attnTmeMinTmEndVal = $row1[9];
                                            $attnTmeTblIsEnbld = $row1[11];
                                            $attnTmeTblSlotDrtn = (float) $row1[3];
                                        }
                                    }
                                    if ($vwtyp != 2) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="attnTmeTblName" class="control-label col-lg-4">Facility Name:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <input type="text" class="form-control" aria-label="..." id="attnTmeTblName" name="attnTmeTblName" value="<?php echo $attnTmeTblName; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="attnTmeTblID" name="attnTmeTblID" value="<?php echo $attnTmeTblID; ?>">
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $attnTmeTblName; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="attnTmeTblDesc" class="control-label col-lg-4">Description:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <textarea class="form-control" rows="4" cols="20" id="attnTmeTblDesc" name="attnTmeTblDesc" style="width:100% !important;text-align:left !important;"><?php echo $attnTmeTblDesc; ?></textarea>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $attnTmeTblDesc; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>                       
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="attnTmeTblEvntType" class="control-label col-md-4">Event Classification:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <input class="form-control" id="attnTmeTblEvntType" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" value="<?php echo $attnTmeTblEvntType; ?>" readonly="true"/>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Event Classifications', '', '', '', 'radio', true, '', 'attnTmeTblEvntType', '', 'clear', 1, '', function () {});">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="col-md-4">
                                                            <label style="">Smallest Slot Duration:</label>
                                                        </div>
                                                        <div class="col-md-4" style="padding:0px 15px 0px 15px;">
                                                            <input type="number" class="form-control" aria-label="..." id="attnTmeTblSlotDrtn" name="attnTmeTblSlotDrtn" value="<?php echo $attnTmeTblSlotDrtn; ?>" style="width:100% !important;">
                                                        </div>
                                                        <div class="col-md-4" style="padding: 0px 15px 0px 15px !important;">
                                                            <div class="checkbox" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="attnTmeTblIsEnbld" class="control-label">
                                                                    <?php
                                                                    $isChkd = "";
                                                                    if ($attnTmeTblIsEnbld == "1") {
                                                                        $isChkd = "checked=\"true\"";
                                                                    }
                                                                    ?>
                                                                    <input type="checkbox" name="attnTmeTblIsEnbld" id="attnTmeTblIsEnbld" <?php echo $isChkd; ?>>Enabled?</label>
                                                            </div>
                                                        </div>
                                                    </div>   
                                                </fieldset>
                                            </div>
                                            <div  class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="attnTmeMajTmDivType" class="control-label col-lg-4">Major Time Division:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="attnTmeMajTmDivType" style="min-width:70px !important;" onchange="onTimeDivChange('MAJOR');">                            
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "", "", "", "", "", "", "", "");
                                                                    $dsplySzeArry = array("01-Year", "02-Half-Year", "03-Quarter", "04-Month",
                                                                        "05-Fortnights in a Year",
                                                                        "06-Fortnights in a Month", "07-Weeks in a Year", "08-Weeks in a Month",
                                                                        "09-Days in a Month", "10-Days in a Week", "11-Hours in a Day");

                                                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                        if ($attnTmeMajTmDivType == $dsplySzeArry[$y]) {
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
                                                                <span><?php echo $attnTmeMajTmDivType; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="attnTmeMajTmStrtVal" class="control-label col-lg-4">Start Value:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            $valslctdArry = array("", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                                                            $dsplySzeArry = explode(";",
                                                                    loadMjrTmDivs($attnTmeTblSlotDrtn, $attnTmeMajTmDivType));
                                                            if ($attnTmeMajTmDivType == "04-Month") {
                                                                $attnTmeMajTmStrtVal = substr($attnTmeMajTmStrtVal, 3);
                                                                $attnTmeMajTmEndVal = substr($attnTmeMajTmEndVal, 3);
                                                            }
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <input id="attnTmeMajTmStrtValHdn" type = "hidden" value="<?php echo $attnTmeMajTmStrtVal; ?>">
                                                                <input id="attnTmeMajTmEndValHdn" type = "hidden" value="<?php echo $attnTmeMajTmEndVal; ?>">
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="attnTmeMajTmStrtVal" style="min-width:70px !important;">                            
                                                                    <?php
                                                                    /* array("01-Year", "02-Half-Year", "03-Quarter", "04-Month",
                                                                      "05-Fortnights in a Year", "06-Fortnights in a Month", "07-Weeks in a Year", "08-Weeks in a Month",
                                                                      "09-Days in a Month", "10-Days in a Week", "11-Hours in a Day"); */
                                                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                        if ($attnTmeMajTmStrtVal == $dsplySzeArry[$y]) {
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
                                                                <span><?php echo $attnTmeMajTmStrtVal; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="attnTmeMajTmEndVal" class="control-label col-lg-4">End Value:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="attnTmeMajTmEndVal" style="min-width:70px !important;">                            
                                                                    <?php
                                                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                        if ($attnTmeMajTmEndVal == $dsplySzeArry[$y]) {
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
                                                                <span><?php echo $attnTmeMajTmEndVal; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="attnTmeMinTmDivType" class="control-label col-lg-4">Minor Time Division:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="attnTmeMinTmDivType" style="min-width:70px !important;" onchange="onTimeDivChange('MINOR');">                            
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "", "", "", "", "", "", "", "");
                                                                    $dsplySzeArry = array("02-Half-Year", "03-Quarter", "04-Month", "05-Fortnights in a Year",
                                                                        "06-Fortnights in a Month", "07-Weeks in a Year", "08-Weeks in a Month",
                                                                        "09-Days in a Month",
                                                                        "10-Days in a Week", "11-Hours in a Day", "12-Hours/Minutes in a Day");
                                                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                        if ($attnTmeMinTmDivType == $dsplySzeArry[$y]) {
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
                                                                <span><?php echo $attnTmeMinTmDivType; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="attnTmeMinTmStrtVal" class="control-label col-lg-4">Start Value:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            $valslctdArry = array("", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                                                            $dsplySzeArry = explode(";",
                                                                    loadMnrTmDivs($attnTmeTblSlotDrtn, $attnTmeMinTmDivType));
                                                            if ($attnTmeMinTmDivType == "04-Month") {
                                                                $attnTmeMinTmStrtVal = substr($attnTmeMinTmStrtVal, 3);
                                                                $attnTmeMinTmEndVal = substr($attnTmeMinTmEndVal, 3);
                                                            }
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <input id="attnTmeMinTmStrtValHdn" type = "hidden" value="<?php echo $attnTmeMinTmStrtVal; ?>">
                                                                <input id="attnTmeMinTmEndValHdn" type = "hidden" value="<?php echo $attnTmeMinTmEndVal; ?>">
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="attnTmeMinTmStrtVal" style="min-width:70px !important;">                            
                                                                    <?php
                                                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                        if ($attnTmeMinTmStrtVal == $dsplySzeArry[$y]) {
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
                                                                <span><?php echo $attnTmeMinTmStrtVal; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="attnTmeMinTmEndVal" class="control-label col-lg-4">End Value:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="attnTmeMinTmEndVal" style="min-width:70px !important;">                            
                                                                    <?php
                                                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                        if ($attnTmeMinTmEndVal == $dsplySzeArry[$y]) {
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
                                                                <span><?php echo $attnTmeMinTmEndVal; ?></span>
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
                                                                $srchIn = "Event Name";
                                                                $pageNo = 1;
                                                                $lmtSze = 30;
                                                            }
                                                            $total = get_Total_TmeTbl_DetLns($srchFor, $srchIn, $attnTmeTblID);
                                                            if ($pageNo > ceil($total / $lmtSze)) {
                                                                $pageNo = 1;
                                                            } else if ($pageNo < 1) {
                                                                $pageNo = ceil($total / $lmtSze);
                                                            }
                                                            $curIdx = $pageNo - 1;
                                                            $resultRw = get_One_TmeTbl_DetLns($srchFor, $srchIn, $curIdx, $lmtSze,
                                                                    $attnTmeTblID);
                                                            if ($vwtyp != 2) {
                                                                $nwRowHtml331 = "<tr id=\"oneAttnTmeTblSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneAttnTmeTblSmryLinesTable tr').index(this));\">                                    
                                                                                        <td class=\"lovtd\"><span>New</span></td>                                              
                                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnTmeTblSmryRow_WWW123WWW_TmeTblLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnTmeTblSmryRow_WWW123WWW_EvntID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnTmeTblSmryRow_WWW123WWW_VnuID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnTmeTblSmryRow_WWW123WWW_HostID\" value=\"-1\" style=\"width:100% !important;\">";
                                                                $nwRowHtml331 .= "<div class=\"input-group\" style=\"width:100% !important;\">
                                                                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneAttnTmeTblSmryRow_WWW123WWW_EvntNm\" name=\"oneAttnTmeTblSmryRow_WWW123WWW_EvntNm\" value=\"\" style=\"width:100% !important;\" readonly=\"true\">
                                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attendance Events', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneAttnTmeTblSmryRow_WWW123WWW_EvntID', 'oneAttnTmeTblSmryRow_WWW123WWW_EvntNm', 'clear', 0, '', function () {});\">
                                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                    </label>
                                                                                                </div> 
                                                                                        </td>
                                                                                        <td class=\"lovtd\">";
                                                                $valslctdArry = array("", "", "", "", "", "", "", "", "",
                                                                    "", "", "", "",
                                                                    "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                    "", "", "", "",
                                                                    "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                    "", "", "", "",
                                                                    "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                    "", "", "");
                                                                $srchInsArrys = explode(";",
                                                                        loadMjrTmDivs($attnTmeTblSlotDrtn, $attnTmeMajTmDivType));

                                                                $valslctdArry1 = array("", "", "", "", "", "", "", "", "",
                                                                    "", "", "", "",
                                                                    "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                    "", "", "", "",
                                                                    "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                    "", "", "", "",
                                                                    "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                    "", "", "");
                                                                $srchInsArrys1 = explode(";",
                                                                        loadMnrTmDivs($attnTmeTblSlotDrtn, $attnTmeMinTmDivType));

                                                                $nwRowHtml331 .= "<select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAttnTmeTblSmryRow_WWW123WWW_MajStrt\" style=\"width:100% !important;\">";
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    $nwRowHtml331 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                                }
                                                                $nwRowHtml331 .= "</select>
                                                                                        </td>
                                                                                        <td class=\"lovtd\">
                                                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAttnTmeTblSmryRow_WWW123WWW_MinStrt\" style=\"width:100% !important;\">";
                                                                for ($z = 0; $z < count($srchInsArrys1); $z++) {
                                                                    $nwRowHtml331 .= "<option value=\"" . $srchInsArrys1[$z] . "\" " . $valslctdArry1[$z] . ">" . $srchInsArrys1[$z] . "</option>";
                                                                }
                                                                $nwRowHtml331 .= "</select>
                                                                                        </td>
                                                                                        <td class=\"lovtd\">
                                                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAttnTmeTblSmryRow_WWW123WWW_MajEnd\" style=\"width:100% !important;\">";
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    $nwRowHtml331 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                                }
                                                                $nwRowHtml331 .= "</select>
                                                                                        </td>
                                                                                        <td class=\"lovtd\">
                                                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAttnTmeTblSmryRow_WWW123WWW_MinEnd\" style=\"width:100% !important;\">";
                                                                for ($z = 0; $z < count($srchInsArrys1); $z++) {
                                                                    $nwRowHtml331 .= "<option value=\"" . $srchInsArrys1[$z] . "\" " . $valslctdArry1[$z] . ">" . $srchInsArrys1[$z] . "</option>";
                                                                }
                                                                $nwRowHtml331 .= "</select>
                                                                                        </td> 
                                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnTmeTblSmryRow_WWW123WWW_VnuNm\" name=\"oneAttnTmeTblSmryRow_WWW123WWW_VnuNm\" value=\"\" style=\"width:100% !important;\"  readonly=\"true\">
                                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Event Venues', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAttnTmeTblSmryRow_WWW123WWW_VnuID', 'oneAttnTmeTblSmryRow_WWW123WWW_VnuNm', 'clear', 0, '', function () {});\">
                                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnTmeTblSmryRow_WWW123WWW_HostNm\" name=\"oneAttnTmeTblSmryRow_WWW123WWW_HostNm\" value=\"\" style=\"width:100% !important;\"  readonly=\"true\">
                                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAttnTmeTblSmryRow_WWW123WWW_HostID', 'oneAttnTmeTblSmryRow_WWW123WWW_HostNm', 'clear', 0, '', function () {});\">
                                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </td>                                           
                                                                                        <td class=\"lovtd\" style=\"text-align:center;\">
                                                                                            <div class=\"form-group form-group-sm\">
                                                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                                    <label class=\"form-check-label\">
                                                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"oneAttnTmeTblSmryRow_WWW123WWW_IsEnabled\" name=\"oneAttnTmeTblSmryRow_WWW123WWW_IsEnabled\">
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttnTmeTblLne('oneAttnTmeTblSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Record\">
                                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                            </button>
                                                                                        </td>
                                                                                    </tr>";
                                                                $nwRowHtml33 = urlencode($nwRowHtml331);
                                                                ?> 
                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                    <div class="col-md-4" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <input type="hidden" id="nwSalesDocLineHtm" value="<?php echo $nwRowHtml33; ?>">
                                                                            <button id="addNwScmAttnTmeTblSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAttnTmeTblRows('oneAttnTmeTblSmryLinesTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Time Table Event">
                                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button> 
                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAttnTmeTblForm();"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                                                        <?php } ?>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAttnTmeTblForm(<?php echo $attnTmeTblID; ?>, 1);"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>    
                                                                    </div>
                                                                    <div class="col-md-6 fcltyTypDetNav" style="padding:0px 15px 0px 15px !important;">
                                                                        <div class="input-group">
                                                                            <input class="form-control" id="attnTmeTblDetSrchFor" type = "text" placeholder="Search For" value="<?php
                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                            ?>" onkeyup="enterKeyFuncAttnTmeTblDet(event, '', '#attnTmeTblDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAttnTmeTblID=<?php echo $attnTmeTblID; ?>');">
                                                                            <input id="attnTmeTblDetPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnTmeTblDet('clear', '#attnTmeTblDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAttnTmeTblID=<?php echo $attnTmeTblID; ?>');">
                                                                                <span class="glyphicon glyphicon-remove"></span>
                                                                            </label>
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnTmeTblDet('', '#attnTmeTblDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAttnTmeTblID=<?php echo $attnTmeTblID; ?>');">
                                                                                <span class="glyphicon glyphicon-search"></span>
                                                                            </label>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="attnTmeTblDetSrchIn">
                                                                                <?php
                                                                                $valslctdArry = array("", "", "", "", "");
                                                                                $srchInsArrys = array("Event Name", "Host Person Name", "Venue Name",
                                                                                    "Major Time Division", "Minor Time Division");
                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                    if ($srchIn == $srchInsArrys[$z]) {
                                                                                        $valslctdArry[$z] = "selected";
                                                                                    }
                                                                                    ?>
                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="attnTmeTblDetDsplySze" style="min-width:70px !important;">                            
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
                                                                                    <a class="rhopagination" href="javascript:getAttnTmeTblDet('previous', '#attnTmeTblDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAttnTmeTblID=<?php echo $attnTmeTblID; ?>');" aria-label="Previous">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a class="rhopagination" href="javascript:getAttnTmeTblDet('next', '#attnTmeTblDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAttnTmeTblID=<?php echo $attnTmeTblID; ?>');" aria-label="Next">
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
                                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAttnTmeTblLnsTblSctn"> 
                                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                        <div id="attnTmeTblDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                        <?php } ?>
                                                        <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                            <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                                <table class="table table-striped table-bordered table-responsive" id="oneAttnTmeTblSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">No.</th>
                                                                            <th style="max-width:120px;">Event Name</th>
                                                                            <th style="max-width:80px;">Major Start Time</th>
                                                                            <th style="max-width:80px;">Minor Start Time</th>
                                                                            <th style="max-width:80px;">Major End Time</th>
                                                                            <th style="max-width:80px;">Minor End Time</th>
                                                                            <th style="min-width:80px;">Assigned Venue</th>
                                                                            <th style="min-width:80px;">Assigned Host</th>
                                                                            <th style="max-width:70px;width:70px;text-align: center;">Enabled?</th>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>   
                                                                        <?php
                                                                        $mkReadOnly = "";
                                                                        $cntr = 0;
                                                                        while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                            $tmeTblLnID = (float) $rowRw[0];
                                                                            $tmeTblLnEvntID = (float) $rowRw[1];
                                                                            $tmeTblLnEvntNm = $rowRw[2];
                                                                            $tmeTblLnMajStrt = $rowRw[3];
                                                                            $tmeTblLnMinStrt = $rowRw[4];
                                                                            $tmeTblLnMajEnd = $rowRw[10];
                                                                            $tmeTblLnMinEnd = $rowRw[11];
                                                                            $tmeTblLnIsEnbld = $rowRw[9];
                                                                            $tmeTblLnVnuID = (float) $rowRw[7];
                                                                            $tmeTblLnVnuNm = $rowRw[8];
                                                                            $tmeTblLnHostID = (float) $rowRw[5];
                                                                            $tmeTblLnHostNm = $rowRw[6];
                                                                            $cntr++;
                                                                            ?>
                                                                            <tr id="oneAttnTmeTblSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAttnTmeTblSmryLinesTable tr').index(this));">                                    
                                                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_TmeTblLnID" value="<?php echo $tmeTblLnID; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_EvntID" value="<?php echo $tmeTblLnEvntID; ?>" style="width:100% !important;">  
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_VnuID" value="<?php echo $tmeTblLnVnuID; ?>" style="width:100% !important;">  
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_HostID" value="<?php echo $tmeTblLnHostID; ?>" style="width:100% !important;">  
                                                                                    <?php
                                                                                    if ($canEdt === true) {
                                                                                        ?>
                                                                                        <div class="input-group" style="width:100% !important;">
                                                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_EvntNm" name="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_EvntNm" value="<?php echo $tmeTblLnEvntNm; ?>" style="width:100% !important;" readonly="true">
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attendance Events', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $tmeTblLnEvntID; ?>', 'oneAttnTmeTblSmryRow<?php echo $cntr; ?>_EvntID', 'oneAttnTmeTblSmryRow<?php echo $cntr; ?>_EvntNm', 'clear', 0, '', function () {});">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div> 
                                                                                    <?php } else { ?>
                                                                                        <span><?php echo $tmeTblLnEvntNm; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <?php
                                                                                    $valslctdArry = array("", "", "", "", "", "", "", "", "",
                                                                                        "", "", "", "",
                                                                                        "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                                        "", "", "", "",
                                                                                        "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                                        "", "", "", "",
                                                                                        "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                                        "", "", "");
                                                                                    $srchInsArrys = explode(";",
                                                                                            loadMjrTmDivs($attnTmeTblSlotDrtn,
                                                                                                    $attnTmeMajTmDivType));
                                                                                    if ($attnTmeMajTmDivType == "04-Month") {
                                                                                        $tmeTblLnMajStrt = substr($tmeTblLnMajStrt, 3);
                                                                                        $tmeTblLnMajEnd = substr($tmeTblLnMajEnd, 3);
                                                                                    }

                                                                                    $valslctdArry1 = array("", "", "", "", "", "", "", "", "",
                                                                                        "", "", "", "",
                                                                                        "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                                        "", "", "", "",
                                                                                        "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                                        "", "", "", "",
                                                                                        "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                                                                        "", "", "");
                                                                                    $srchInsArrys1 = explode(";",
                                                                                            loadMnrTmDivs($attnTmeTblSlotDrtn,
                                                                                                    $attnTmeMinTmDivType));

                                                                                    if ($attnTmeMinTmDivType == "04-Month") {
                                                                                        $tmeTblLnMinStrt = substr($tmeTblLnMinStrt, 3);
                                                                                        $tmeTblLnMinEnd = substr($tmeTblLnMinEnd, 3);
                                                                                    }
                                                                                    ?>
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_MajStrt" style="width:100% !important;">
                                                                                        <?php
                                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                            if ($tmeTblLnMajStrt == $srchInsArrys[$z]) {
                                                                                                $valslctdArry[$z] = "selected";
                                                                                            }
                                                                                            ?>
                                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_MinStrt" style="width:100% !important;">
                                                                                        <?php
                                                                                        for ($z = 0; $z < count($srchInsArrys1); $z++) {
                                                                                            if ($tmeTblLnMinStrt == $srchInsArrys1[$z]) {
                                                                                                $valslctdArry1[$z] = "selected";
                                                                                            }
                                                                                            ?>
                                                                                            <option value="<?php echo $srchInsArrys1[$z]; ?>" <?php echo $valslctdArry1[$z]; ?>><?php echo $srchInsArrys1[$z]; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_MajEnd" style="width:100% !important;">
                                                                                        <?php
                                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                            if ($tmeTblLnMajEnd == $srchInsArrys[$z]) {
                                                                                                $valslctdArry[$z] = "selected";
                                                                                            }
                                                                                            ?>
                                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_MinEnd" style="width:100% !important;">
                                                                                        <?php
                                                                                        for ($z = 0; $z < count($srchInsArrys1); $z++) {
                                                                                            if ($tmeTblLnMinEnd == $srchInsArrys1[$z]) {
                                                                                                $valslctdArry1[$z] = "selected";
                                                                                            }
                                                                                            ?>
                                                                                            <option value="<?php echo $srchInsArrys1[$z]; ?>" <?php echo $valslctdArry1[$z]; ?>><?php echo $srchInsArrys1[$z]; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </td> 
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <div class="input-group" style="width:100% !important;">
                                                                                        <input type="text" class="form-control" aria-label="..." id="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_VnuNm" name="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_VnuNm" value="<?php echo $tmeTblLnVnuNm; ?>" style="width:100% !important;"  readonly="true">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Event Venues', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $tmeTblLnVnuID; ?>', 'oneAttnTmeTblSmryRow<?php echo $cntr; ?>_VnuID', 'oneAttnTmeTblSmryRow<?php echo $cntr; ?>_VnuNm', 'clear', 0, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <div class="input-group" style="width:100% !important;">
                                                                                        <input type="text" class="form-control" aria-label="..." id="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_HostNm" name="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_HostNm" value="<?php echo $tmeTblLnHostNm; ?>" style="width:100% !important;"  readonly="true">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $tmeTblLnHostID; ?>', 'oneAttnTmeTblSmryRow<?php echo $cntr; ?>_HostID', 'oneAttnTmeTblSmryRow<?php echo $cntr; ?>_HostNm', 'clear', 0, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </td>                                           
                                                                                <td class="lovtd" style="text-align:center;">
                                                                                    <?php
                                                                                    $isChkd = "";
                                                                                    if ($tmeTblLnIsEnbld == "1") {
                                                                                        $isChkd = "checked=\"true\"";
                                                                                    }
                                                                                    ?>
                                                                                    <div class="form-group form-group-sm">
                                                                                        <div class="form-check" style="font-size: 12px !important;">
                                                                                            <label class="form-check-label">
                                                                                                <input type="checkbox" class="form-check-input" id="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_IsEnabled" name="oneAttnTmeTblSmryRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?>>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnTmeTblLne('oneAttnTmeTblSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Record">
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
                                                                            <th style="">&nbsp;</th>                                           
                                                                            <th style="">&nbsp;</th>
                                                                            <th style="">&nbsp;</th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            $("#attnTmeTblDetPageNo").val(<?php echo $pageNo; ?>);
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
                //Get Selected Time Division Breakdown
                header("content-type:application/json");
                $attnTmeMajTmDivType = isset($_POST['attnTmeMajTmDivType']) ? cleanInputData($_POST['attnTmeMajTmDivType']) : "";
                $attnTmeMinTmDivType = isset($_POST['attnTmeMinTmDivType']) ? cleanInputData($_POST['attnTmeMinTmDivType']) : "";
                $attnTmeSrcType = isset($_POST['attnTmeSrcType']) ? cleanInputData($_POST['attnTmeSrcType']) : "";
                $attnTmeTblSlotDrtn = isset($_POST['attnTmeTblSlotDrtn']) ? (float) cleanInputData($_POST['attnTmeTblSlotDrtn']) : 30;
                $arr_content['TimeOptions'] = "";
                if (trim($attnTmeSrcType) === "MAJOR") {
                    $arr_content['TimeOptions'] = loadMjrTmDivs($attnTmeTblSlotDrtn, $attnTmeMajTmDivType);
                } else if (trim($attnTmeSrcType) === "MINOR") {
                    $arr_content['TimeOptions'] = loadMnrTmDivs($attnTmeTblSlotDrtn, $attnTmeMinTmDivType);
                }
                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                echo json_encode($arr_content);
                exit();
            }
        }
    }
}    