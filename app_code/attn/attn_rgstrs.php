<?php
$canAdd = test_prmssns($dfltPrvldgs[8], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[9], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[10], $mdlNm);
$canVwRcHstry = test_prmssns($dfltPrvldgs[7], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Register Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteAttnRgstr($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Register Record */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteAttnRgstrDLn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Register HeadCount  */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteAtndncMtrc($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 4) {
                /* Delete Register Cost  */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteAttnCostLn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                /* Delete Register Time Breakdown  */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteAttnTimeLn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Attendance Register
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdAttnRegisterID = isset($_POST['sbmtdAttnRegisterID']) ? (int) cleanInputData($_POST['sbmtdAttnRegisterID']) : -1;
                $attnRegisterTmTblID = isset($_POST['attnRegisterTmTblID']) ? (int) cleanInputData($_POST['attnRegisterTmTblID']) : -1;
                $attnRegisterTmTblDetID = isset($_POST['attnRegisterTmTblDetID']) ? (int) cleanInputData($_POST['attnRegisterTmTblDetID']) : -1;
                $attnRegisterEvntID = isset($_POST['attnRegisterEvntID']) ? (int) cleanInputData($_POST['attnRegisterEvntID']) : -1;
                $attnRegisterName = isset($_POST['attnRegisterName']) ? cleanInputData($_POST['attnRegisterName']) : "";
                $attnRegisterDesc = isset($_POST['attnRegisterDesc']) ? cleanInputData($_POST['attnRegisterDesc']) : "";
                $attnRegisterTmTblNm = isset($_POST['attnRegisterTmTblNm']) ? cleanInputData($_POST['attnRegisterTmTblNm']) : "";
                $attnRegisterEvntNm = isset($_POST['attnRegisterEvntNm']) ? cleanInputData($_POST['attnRegisterEvntNm']) : "";
                $attnRegisterStrtDte = isset($_POST['attnRegisterStrtDte']) ? cleanInputData($_POST['attnRegisterStrtDte']) : "";
                $attnRegisterEndDte = isset($_POST['attnRegisterEndDte']) ? cleanInputData($_POST['attnRegisterEndDte']) : "";

                $slctdIndvdlAtnds = isset($_POST['slctdIndvdlAtnds']) ? cleanInputData($_POST['slctdIndvdlAtnds']) : "";
                $slctdAtndHdCnts = isset($_POST['slctdAtndHdCnts']) ? cleanInputData($_POST['slctdAtndHdCnts']) : "";
                $slctdAtndEvntCosts = isset($_POST['slctdAtndEvntCosts']) ? cleanInputData($_POST['slctdAtndEvntCosts']) : "";

                $exitErrMsg = "";
                if ($attnRegisterName == "") {
                    $exitErrMsg .= "Please enter Register Name!<br/>";
                }
                if ($attnRegisterDesc == "") {
                    $exitErrMsg .= "Please enter Register Description!<br/>";
                }
                if ($attnRegisterTmTblID <= 0) {
                    $exitErrMsg .= "Please enter Linked Time Table!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAttnRegisterID'] = $sbmtdAttnRegisterID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldID = getAttnRgstrID($attnRegisterName, $orgID);
                if (($oldID <= 0 || $oldID == $sbmtdAttnRegisterID)) {
                    if ($sbmtdAttnRegisterID <= 0) {
                        createAttnRgstr($orgID, $attnRegisterName, $attnRegisterDesc, $attnRegisterTmTblID, $attnRegisterTmTblDetID, $attnRegisterStrtDte, $attnRegisterEndDte);
                        $sbmtdAttnRegisterID = getAttnRgstrID($attnRegisterName, $orgID);
                    } else {
                        updateAttnRgstr($sbmtdAttnRegisterID, $attnRegisterName, $attnRegisterDesc, $attnRegisterTmTblID, $attnRegisterTmTblDetID, $attnRegisterStrtDte, $attnRegisterEndDte);
                    }

                    $afftctd = 0;
                    $afftctd1 = 0;
                    $afftctd2 = 0;
                    if (trim($slctdIndvdlAtnds, "|~") != "" && $sbmtdAttnRegisterID >= 0) {
                        //Save Individual Attendance Lines
                        $variousRows = explode("|", trim($slctdIndvdlAtnds, "|"));
                        //echo count($variousRows);
                        for ($y = 0; $y < count($variousRows); $y++) {
                            //var_dump($crntRow);
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 11) {
                                $ln_RgstrDetID = (float) (cleanInputData1($crntRow[0]));
                                $ln_DetType = cleanInputData1($crntRow[1]);
                                $ln_DetPrsnID = (float) (cleanInputData1($crntRow[2]));
                                $ln_DetCstmrID = (float) (cleanInputData1($crntRow[3]));
                                $ln_DetSpnsrID = (float) (cleanInputData1($crntRow[4]));
                                $ln_LineName = cleanInputData1($crntRow[5]);
                                $ln_LineDesc = cleanInputData1($crntRow[6]);
                                $ln_SlctdAmtBrkdwns = cleanInputData1($crntRow[7]);
                                $ln_SlctdPointsScrd = cleanInputData1($crntRow[8]);
                                $ln_IsPrsnt = (cleanInputData1($crntRow[9]) == "YES") ? TRUE : FALSE;
                                $ln_NoPrsns = (float) cleanInputData1($crntRow[10]);
                                $errMsg = "";
                                if ($ln_LineName === "" || $ln_DetType == "") {
                                    $errMsg = "Row " . ($y + 1) . ":-Attendee Type and Name are all required Fields!<br/>";
                                }
                                if ($errMsg === "") {
                                    if ($ln_RgstrDetID <= 0) {
                                        $afftctd += createAttnRgstrDetLn($sbmtdAttnRegisterID, $ln_DetPrsnID, "", "", $ln_IsPrsnt, $ln_LineDesc, $ln_LineName, $ln_NoPrsns, $ln_DetCstmrID, $ln_DetType, $ln_DetSpnsrID);
                                    } else if ($ln_RgstrDetID > 0) {
                                        $afftctd += updtAttnRgstrDetLn($ln_RgstrDetID, $sbmtdAttnRegisterID, $ln_DetPrsnID, "", "", $ln_IsPrsnt, $ln_LineDesc, $ln_LineName, $ln_NoPrsns, $ln_DetCstmrID, $ln_DetType, $ln_DetSpnsrID);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }

                    if (trim($slctdAtndHdCnts, "|~") != "" && $sbmtdAttnRegisterID >= 0) {
                        //Save Petty Cash Double Entry Lines
                        $variousRows = explode("|", trim($slctdAtndHdCnts, "|"));
                        //echo count($variousRows);
                        for ($y = 0; $y < count($variousRows); $y++) {
                            //var_dump($crntRow);
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 7) {
                                $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_PsblValID = (float) (cleanInputData1($crntRow[1]));
                                $ln_RsltMtrcID = (float) (cleanInputData1($crntRow[2]));
                                $ln_RsltTyp = cleanInputData1($crntRow[3]);
                                $ln_MtrcNm = cleanInputData1($crntRow[4]);
                                $ln_Result = cleanInputData1($crntRow[5]);
                                $ln_Comment = cleanInputData1($crntRow[6]);
                                $errMsg = "";
                                if ($ln_MtrcNm === "") {
                                    $errMsg = "Row " . ($y + 1) . ":- Metric Name is a required Field!<br/>";
                                }
                                if ($errMsg === "") {
                                    if ($ln_TrnsLnID <= 0) {
                                        $ln_TrnsLnID = getNewMtrcCntLnID();
                                        $afftctd1 += createAttnMtrcCnt($ln_TrnsLnID, $sbmtdAttnRegisterID, $ln_MtrcNm, $ln_Comment, $ln_Result, $ln_PsblValID);
                                    } else if ($ln_TrnsLnID > 0) {
                                        $afftctd1 += updateAttnMtrcCnt($ln_TrnsLnID, $ln_MtrcNm, $ln_Comment, $ln_Result, $ln_PsblValID);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }
                    if (trim($slctdAtndEvntCosts, "|~") != "" && $sbmtdAttnRegisterID >= 0) {
                        //Save Petty Cash Double Entry Lines
                        $variousRows = explode("|", trim($slctdAtndEvntCosts, "|"));
                        //echo count($variousRows);
                        for ($y = 0; $y < count($variousRows); $y++) {
                            //var_dump($crntRow);
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 11) {
                                $ln_CostLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_SrcDcID = (float) (cleanInputData1($crntRow[1]));
                                $ln_GLBatchID = (float) (cleanInputData1($crntRow[2]));
                                $ln_AccountID1 = (float) (cleanInputData1($crntRow[3]));
                                $ln_AccountID2 = (float) (cleanInputData1($crntRow[4]));
                                $ln_CostCtgry = cleanInputData1($crntRow[5]);
                                $ln_LineDesc = cleanInputData1($crntRow[6]);
                                $ln_NoOfDays = (float) cleanInputData1($crntRow[7]);
                                $ln_NoPrsns = (float) cleanInputData1($crntRow[8]);
                                $ln_UnitCost = (float) cleanInputData1($crntRow[9]);
                                $ln_SrcDocType = cleanInputData1($crntRow[10]);
                                $errMsg = "";
                                if ($ln_MtrcNm === "") {
                                    $errMsg = "Row " . ($y + 1) . ":- Metric Name is a required Field!<br/>";
                                }
                                if ($errMsg === "") {
                                    if ($ln_CostLnID <= 0) {
                                        //$ln_TrnsLnID = getNewAttnCstLnID();
                                        $afftctd2 += createAttnCostLn($sbmtdAttnRegisterID, $ln_SrcDcID, $ln_SrcDocType, $ln_LineDesc, $ln_NoPrsns, $ln_NoOfDays, $ln_UnitCost, $ln_CostCtgry);
                                    } else if ($ln_CostLnID > 0) {
                                        $afftctd2 += updtAttnCostLn($ln_CostLnID, $sbmtdAttnRegisterID, $ln_SrcDcID, $ln_SrcDocType, $ln_LineDesc, $ln_NoPrsns, $ln_NoOfDays, $ln_UnitCost, $ln_CostCtgry);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }
                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Attendance  Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Attendee Record(s) Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd1 . " Attendance Head Count(s) Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd2 . " Attendance Cost(s) Saved!"
                                . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Attendance  Successfully Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Attendee Record(s) Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd1 . " Attendance Head Count(s) Saved!"
                                . "<br/><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd2 . " Attendance Cost(s) Saved!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAttnRegisterID'] = $sbmtdAttnRegisterID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New ERegister Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAttnRegisterID'] = $sbmtdAttnRegisterID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0 || $vwtyp == 1 || $vwtyp == 2) {
                $pkID = isset($_POST['sbmtdAttnRegisterID']) ? $_POST['sbmtdAttnRegisterID'] : -1;
                if ($vwtyp == 0) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Attendance Registers</span>
				</li>
                               </ul>
                              </div>";

                    $total = get_Total_AttnRgstr($srchFor, $srchIn, $orgID, false);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_Basic_AttnRgstr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, false);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='attnRegisterForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php if ($canAdd === true) { ?> 
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 0px 0px 0px !important;"> 
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAttnRegisterForm(-1, 1);;" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="New Attendance Register">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Register
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAttnRegisterForm();" style="width:100% !important;">
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
                                    <input class="form-control" id="attnRegisterSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAttnRegister(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="attnRegisterPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnRegister('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnRegister('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attnRegisterSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "");
                                        $srchInsArrys = array("Register Name", "Register Description", "Register Number", "Event Date");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attnRegisterDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getAttnRegister('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttnRegister('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
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
                                    <table class="table table-striped table-bordered table-responsive" id="attnRegisterTable" cellspacing="0" width="100%" style="width:100% !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Register Name</th>
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
                                                <tr id="attnRegisterRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $row[1]; ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnRegisterRow<?php echo $cntr; ?>_RegisterID" value="<?php echo $row[0]; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnRegisterRow<?php echo $cntr; ?>_RegisterNm" value="<?php echo $row[1]; ?>">
                                                    </td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnRegister('attnRegisterRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Register">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|attn.attn_attendance_recs_hdr|recs_hdr_id"),
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
                                    <div class="container-fluid" id="attnRegisterDetailInfo">
                                        <?php
                                    }
                                    $attnRegisterID = -1;
                                    $attnRegisterName = "";
                                    $attnRegisterDesc = "";

                                    $attnRegisterTmTblID = -1;
                                    $attnRegisterTmTblNm = "";
                                    $attnRegisterTmTblDetID = -1;
                                    $attnRegisterEvntID = -1;
                                    $attnRegisterEvntNm = "";

                                    $attnRegisterStrtDte = "";
                                    $attnRegisterEndDte = "";
                                    $mkReadOnly = "";
                                    if ($pkID > 0) {
                                        $attnRegisterID = $pkID;
                                        $result1 = get_One_AttnRgstrDet($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $attnRegisterID = (float) $row1[0];
                                            $attnRegisterName = $row1[1];
                                            $attnRegisterDesc = $row1[2];

                                            $attnRegisterTmTblID = (float) $row1[3];
                                            $attnRegisterTmTblNm = $row1[4];
                                            $attnRegisterTmTblDetID = (float) $row1[5];
                                            $attnRegisterEvntID = (float) $row1[9];
                                            $attnRegisterEvntNm = $row1[6];

                                            $attnRegisterStrtDte = $row1[7];
                                            $attnRegisterEndDte = $row1[8];
                                        }
                                    } else {
                                        $dte = date('ymd');
                                        $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                                        if ($usrTrnsCode == "") {
                                            $usrTrnsCode = "XX";
                                        }
                                        $userAccntName = getGnrlRecNm("sec.sec_users", "user_id", "user_name", $usrID);
                                        $gnrtdTrnsNo1 = "RGSTR-" . $usrTrnsCode . "-" . $dte . "-";
                                        $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad((getRecCount_LstNum("attn.attn_attendance_recs_hdr", "recs_hdr_name", "recs_hdr_id",
                                                                $gnrtdTrnsNo1 . "%") + 1), 3, '0', STR_PAD_LEFT);
                                        $attnRegisterName = $gnrtdTrnsNo;
                                    }
                                    if ($vwtyp != 2) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">No./Name:</label>
                                                        </div>
                                                        <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                                            <input type="text" class="form-control" aria-label="..." id="sbmtdAttnRegisterID" name="sbmtdAttnRegisterID" value="<?php echo $attnRegisterID; ?>" readonly="true">
                                                            <input type="hidden" id="attnRegisterTmTblID" value="<?php echo $attnRegisterTmTblID; ?>">
                                                            <input type="hidden" id="attnRegisterTmTblDetID" value="<?php echo $attnRegisterTmTblDetID; ?>">
                                                            <input type="hidden" id="attnRegisterEvntID" value="<?php echo $attnRegisterEvntID; ?>">
                                                        </div>
                                                        <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                                            <input type="text" class="form-control" aria-label="..." id="attnRegisterName" name="attnRegisterName" value="<?php echo $attnRegisterName; ?>" readonly="true">
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="input-group"  style="width:100%;">
                                                                <textarea class="form-control rqrdFld" rows="3" cols="20" id="attnRegisterDesc" name="attnRegisterDesc" <?php echo $mkReadOnly; ?> style="text-align:left !important;"><?php echo $attnRegisterDesc; ?></textarea>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('attnRegisterDesc');" style="max-width:30px;width:30px;">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>                     
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="attnRegisterTmTblNm" class="control-label col-md-4">Time Table:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <input class="form-control rqrdFld" id="attnRegisterTmTblNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" value="<?php echo $attnRegisterTmTblNm; ?>" readonly="true"/>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Time Tables', 'allOtherInputOrgID', '', '', 'radio', true, '', 'attnRegisterTmTblID', 'attnRegisterTmTblNm', 'clear', 1, '', function () {});">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div  class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Event:</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="input-group"  style="width:100%;">
                                                                <textarea class="form-control" rows="3" cols="20" id="attnRegisterEvntNm" name="attnRegisterEvntNm" <?php echo $mkReadOnly; ?> style="text-align:left !important;"><?php echo $attnRegisterEvntNm; ?></textarea>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Time Table Event Lines', 'RHO_SPEC_1', 'attnRegisterTmTblID', '', 'radio', true, '', 'attnRegisterTmTblDetID', 'attnRegisterEvntNm', 'clear', 1, '', function () {});">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Start Date:</label>
                                                        </div>
                                                        <div class="col-md-8 input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 15px 0px 15px !important;">
                                                            <input class="form-control" size="16" type="text" id="attnRegisterStrtDte" name="attnRegisterStrtDte" value="<?php echo $attnRegisterStrtDte; ?>" placeholder="Start Date" <?php echo $mkReadOnly; ?>>
                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">End Date:</label>
                                                        </div>
                                                        <div class="col-md-8 input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 15px 0px 15px !important;">
                                                            <input class="form-control" size="16" type="text" id="attnRegisterEndDte" name="attnRegisterEndDte" value="<?php echo $attnRegisterEndDte; ?>" placeholder="End Date" <?php echo $mkReadOnly; ?>>
                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                                    <li class="active"><a data-toggle="tabajxattnrgstr" data-rhodata="" href="#attnRegisterDetLines" id="attnRegisterDetLinestab">Mark Individuals</a></li>
                                                    <li class=""><a data-toggle="tabajxattnrgstr" data-rhodata="" href="#attnRegisterHeadCount" id="attnRegisterHeadCounttab">Record Head Count</a></li>
                                                    <li class=""><a data-toggle="tabajxattnrgstr" data-rhodata="" href="#attnRgstrEvntCost" id="attnRgstrEvntCosttab">Event Costing</a></li>
                                                </ul>  
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
                                                            $evntPrcsCnt = get_One_EvntTtlPrices($attnRegisterEvntID);
                                                            $total = get_Total_AttnRgstr_DetLns($srchFor, $srchIn, $attnRegisterID);
                                                            if ($pageNo > ceil($total / $lmtSze)) {
                                                                $pageNo = 1;
                                                            } else if ($pageNo < 1) {
                                                                $pageNo = ceil($total / $lmtSze);
                                                            }
                                                            $curIdx = $pageNo - 1;
                                                            $resultRw = get_One_AttnRgstr_DetLns($srchFor, $srchIn, $curIdx, $lmtSze,
                                                                    $attnRegisterID);
                                                            if ($vwtyp != 2) {
                                                                $nwRowHtml331 = "<tr id=\"oneAttnRegisterSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneAttnRegisterSmryLinesTable tr').index(this));\">                                    
                                                                                        <td class=\"lovtd\"><span>New</span></td>                                              
                                                                                        <td class=\"lovtd\">
                                                                                    <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAttnRegisterSmryRow_WWW123WWW_DetType\" style=\"width:100% !important;\">";
                                                                $brghtStr = "";
                                                                $isDynmyc = FALSE;
                                                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Visitor Classifications"), $isDynmyc, -1, "", "");
                                                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                    $nwRowHtml331 .= "<option value=\"" . $titleRow[0] . "\">" . $titleRow[0] . "</option>";
                                                                }
                                                                $nwRowHtml331 .= "</select>
                                                                                </td>                                              
                                                                                <td class=\"lovtd\"  style=\"\">  
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRegisterSmryRow_WWW123WWW_RgstrDetID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRegisterSmryRow_WWW123WWW_DetPrsnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRegisterSmryRow_WWW123WWW_DetCstmrID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRegisterSmryRow_WWW123WWW_DetSpnsrID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRegisterSmryRow_WWW123WWW_SlctdAmtBrkdwns\" value=\"\" style=\"width:100% !important;\"> 
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRegisterSmryRow_WWW123WWW_SlctdPointsScrd\" value=\"\" style=\"width:100% !important;\"> 
                                                                                    <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetRfDc\" aria-label=\"...\" id=\"oneAttnRegisterSmryRow_WWW123WWW_LineName\" name=\"oneAttnRegisterSmryRow_WWW123WWW_LineName\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnRegisterSmryRow_WWW123WWW_LineName', 'oneAttnRegisterSmryLinesTable', 'jbDetRfDc');\">
                                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getCstmrSpplrForm(-1, 'Create/Edit Customer', 'ShowDialog', function () {}, 'oneAttnRegisterSmryRow_WWW123WWW_DetSpnsrID');\" data-toggle=\"tooltip\" title=\"Create/Edit Supplier\">
                                                                                            <span class=\"glyphicon glyphicon-plus\"></span>
                                                                                        </label>
                                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getAttnRgstrLovPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAttnRegisterSmryRow__WWW123WWW', 'oneAttnRegisterSmryRow_WWW123WWW_LineName', 'clear', 0, '', function () {});\">
                                                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </td>                                           
                                                                                <td class=\"lovtd\" style=\"text-align:center;\">
                                                                                    <div class=\"form-group form-group-sm\">
                                                                                        <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                                            <label class=\"form-check-label\">
                                                                                                <input type=\"checkbox\" class=\"form-check-input\" id=\"oneAttnRegisterSmryRow_WWW123WWW_IsPrsnt\" name=\"oneAttnRegisterSmryRow_WWW123WWW_IsPrsnt\">
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                    <button type=\"button\" class=\"btn btn-default btn-sm\" id=\"oneAttnRegisterSmryRow_WWW123WWW_TmDetsBtn\" 
                                                                                            onclick=\"getAttnRgstrBreakdown(-1, 'ShowDialog', 'Attendance Time Details', 'EDIT', 'oneAttnRegisterSmryRow_WWW123WWW_SlctdAmtBrkdwns');\" style=\"padding:2px !important;\" title=\"Attendance Time Details\"> 
                                                                                        <img src=\"cmn_images/chcklst4.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                         
                                                                                    </button>
                                                                                </td>";
                                                                if ($evntPrcsCnt > 0 && $attnRegisterTmTblDetID > 0) {
                                                                    $nwRowHtml331 .= "<td class=\"lovtd\" style=\"text-align: center;\">
                                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" id=\"oneAttnRegisterSmryRow_WWW123WWW_PointsScrdBtn\" 
                                                                                                onclick=\"\" style=\"padding:2px !important;\" title=\"Attendance Points Scored\"> 
                                                                                            <img src=\"cmn_images/check_all.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                        
                                                                                        </button>
                                                                                    </td>
                                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" id=\"oneAttnRegisterSmryRow_WWW123WWW_InvoiceBtn\" 
                                                                                                onclick=\"\" style=\"padding:2px !important;\" title=\"Attached Attendance Invoices\"> 
                                                                                            <img src=\"cmn_images/invcBill.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                         
                                                                                        </button>
                                                                                    </td>    
                                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRegisterSmryRow_WWW123WWW_LnkdFirmNm\" name=\"oneAttnRegisterSmryRow_WWW123WWW_LnkdFirmNm\" value=\"\" style=\"width:100% !important;\" readonly=\"true\">
                                                                                            <input type=\"hidden\" id=\"oneAttnRegisterSmryRow_WWW123WWW_Clsftn\" value=\"Customer\">
                                                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getCstmrSpplrForm(-1, 'Create/Edit Customer', 'ShowDialog', function () {}, 'oneAttnRegisterSmryRow_WWW123WWW_DetSpnsrID');\" data-toggle=\"tooltip\" title=\"Create/Edit Supplier\">
                                                                                                <span class=\"glyphicon glyphicon-plus\"></span>
                                                                                            </label>
                                                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', 'oneAttnRegisterSmryRow_WWW123WWW_Clsftn', 'radio', true, '', 'oneAttnRegisterSmryRow_WWW123WWW_DetSpnsrID', 'oneAttnRegisterSmryRow_WWW123WWW_LnkdFirmNm', 'clear', 1, '', function () {
                                                                                                        //getInvRcvblsAcntInfo();
                                                                                                    });\" data-toggle=\"tooltip\" title=\"Existing Client/Vendor\">
                                                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </td>  
                                                                                    <td class=\"lovtd\" style=\"text-align: right;\">
                                                                                        <input type=\"text\" class=\"form-control jbDetAccRate\" aria-label=\"...\" id=\"oneAttnRegisterSmryRow_WWW123WWW_AmntBlld\" name=\"oneAttnRegisterSmryRow_WWW123WWW_AmntBlld\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnRegisterSmryRow_WWW123WWW_AmntBlld', 'oneAttnRegisterSmryLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">                                                    
                                                                                    </td> 
                                                                                    <td class=\"lovtd\">
                                                                                        <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneAttnRegisterSmryRow_WWW123WWW_AmntPaid\" name=\"oneAttnRegisterSmryRow_WWW123WWW_AmntPaid\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnRegisterSmryRow_WWW123WWW_AmntPaid', 'oneAttnRegisterSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">                                                    
                                                                                    </td>";
                                                                } else {
                                                                    $nwRowHtml331 .= "<td class=\"lovtd\">
                                                                                        <input type=\"text\" class=\"form-control jbDetDesc\" aria-label=\"...\" id=\"oneAttnRegisterSmryRow_WWW123WWW_LineDesc\" name=\"oneAttnRegisterSmryRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;text-align: left;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnRegisterSmryRow_WWW123WWW_LineDesc', 'oneAttnRegisterSmryLinesTable', 'jbDetDesc');\">                                                    
                                                                                    </td> 
                                                                                    <td class=\"lovtd\">
                                                                                        <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneAttnRegisterSmryRow_WWW123WWW_NoPrsns\" name=\"oneAttnRegisterSmryRow_WWW123WWW_NoPrsns\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnRegisterSmryRow_WWW123WWW_NoPrsns', 'oneAttnRegisterSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\">                                                    
                                                                                    </td>";
                                                                }
                                                                $nwRowHtml331 .= "<td class=\"lovtd\" style=\"text-align: center;\">
                                                                                    <button type=\"button\" class=\"btn btn-default btn-sm\" id=\"oneAttnRegisterSmryRow_WWW123WWW_AttchdDocsBtn\" 
                                                                                            onclick=\"\" style=\"padding:2px !important;\" title=\"Attached Documents\"> 
                                                                                        <img src=\"cmn_images/adjunto.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                          
                                                                                    </button>
                                                                                </td>
                                                                                <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttnRegisterLne('oneAttnRegisterSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Attendance Record\">
                                                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                    </button>
                                                                                </td>
                                                                                    </tr>";
                                                                $nwRowHtml33 = urlencode($nwRowHtml331);
                                                                $nwRowHtml332 = "<tr id=\"oneAttnRgstrHeadCntRow__WWW123WWW\">                                       
                                                                                    <td class=\"lovtd\"><span>New</span></td>                                              
                                                                                    <td class=\"lovtd\"  style=\"\"> 
                                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrHeadCntRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrHeadCntRow_WWW123WWW_PsblValID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrHeadCntRow_WWW123WWW_RsltMtrcID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrHeadCntRow_WWW123WWW_RsltTyp\" value=\"\" style=\"width:100% !important;\">
                                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrHeadCntRow_WWW123WWW_MtrcNm\" name=\"oneAttnRgstrHeadCntRow_WWW123WWW_MtrcNm\" value=\"\" style=\"width:100% !important;text-align: left;\">
                                                                                    </td> 
                                                                                    <td class=\"lovtd\" style=\"text-align:right;\">
                                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrHeadCntRow_WWW123WWW_Result\" name=\"oneAttnRgstrHeadCntRow_WWW123WWW_Result\" value=\"\" style=\"width:100% !important;text-align: right;\">   
                                                                                    </td>
                                                                                    <td class=\"lovtd\" style=\"text-align:right;\">
                                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrHeadCntRow_WWW123WWW_Comment\" name=\"oneAttnRgstrHeadCntRow_WWW123WWW_Comment\" value=\"\" style=\"width:100% !important;text-align: right;\">   
                                                                                    </td>
                                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttnRgstrHdCnt('oneAttnRgstrHeadCntRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Register Head Count\">
                                                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>";
                                                                $nwRowHtml32 = urlencode($nwRowHtml332);
                                                                $nwRowHtml333 = "<tr id=\"oneAttnRgstrEvntCostRow__WWW123WWW\">                                       
                                                                                            <td class=\"lovtd\"><span>New</span></td>                                              
                                                                                            <td class=\"lovtd\">
                                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_CostLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_SrcDcID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_GLBatchID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_AccountID1\" value=\"-1\" style=\"width:100% !important;\">
                                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_AccountID2\" value=\"-1\" style=\"width:100% !important;\">  
                                                                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_CostCtgry\" style=\"width:100% !important;\">";
                                                                $brghtStr = "";
                                                                $isDynmyc = FALSE;
                                                                $titleRslt = getLovValues("%", "Both", 0, 100,
                                                                        $brghtStr,
                                                                        getLovID("Event Cost Categories"),
                                                                        $isDynmyc, -1, "", "");
                                                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                    $selectedTxt = "";
                                                                    $nwRowHtml333 .= "<option value=\"" . $titleRow[0] . "\" " . $selectedTxt . ">" . $titleRow[0] . "</option>";
                                                                }
                                                                $nwRowHtml333 .= "</select>
                                                                                            </td>  
                                                                                            <td class=\"lovtd\">
                                                                                                <input type=\"text\" class=\"form-control jbDetDesc\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_LineDesc\" name=\"oneAttnRgstrEvntCostRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;text-align: left;\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow_WWW123WWW_LineDesc', 'oneAttnRgstrEvntCostLinesTable', 'jbDetDesc');\">                                                    
                                                                                            </td>  
                                                                                            <td class=\"lovtd\">
                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_SrcDocNum\" name=\"oneAttnRgstrEvntCostRow_WWW123WWW_SrcDocNum\" value=\"\" style=\"width:100% !important;text-align: left;\" readonly=\"true\">                                                    
                                                                                            </td>   
                                                                                            <td class=\"lovtd\" style=\"text-align: right;\">
                                                                                                <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_NoOfDays\" name=\"oneAttnRgstrEvntCostRow_WWW123WWW_NoOfDays\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow_WWW123WWW_NoOfDays', 'oneAttnRgstrEvntCostLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\">                                                    
                                                                                            </td> 
                                                                                            <td class=\"lovtd\">
                                                                                                <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_NoPrsns\" name=\"oneAttnRgstrEvntCostRow_WWW123WWW_NoPrsns\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow_WWW123WWW_NoPrsns', 'oneAttnRgstrEvntCostLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">                                                    
                                                                                            </td> 
                                                                                            <td class=\"lovtd\">
                                                                                                <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_UnitCost\" name=\"oneAttnRgstrEvntCostRow_WWW123WWW_UnitCost\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow_WWW123WWW_UnitCost', 'oneAttnRgstrEvntCostLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">                                                    
                                                                                            </td>  
                                                                                            <td class=\"lovtd\">
                                                                                                <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_TtlAmnt\" name=\"oneAttnRgstrEvntCostRow_WWW123WWW_TtlAmnt\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow_WWW123WWW_TtlAmnt', 'oneAttnRgstrEvntCostLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">                                                    
                                                                                            </td>    
                                                                                            <td class=\"lovtd\">
                                                                                                <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_SrcDocType\" name=\"oneAttnRgstrEvntCostRow_WWW123WWW_SrcDocType\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow_WWW123WWW_SrcDocType', 'oneAttnRgstrEvntCostLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">                                                    
                                                                                            </td>
                                                                                            <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                                <button type=\"button\" class=\"btn btn-default btn-sm\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_CreateAccntBtn\" 
                                                                                                        onclick=\"\" style=\"padding:2px !important;\" title=\"Create Accounting\"> 
                                                                                                    <img src=\"cmn_images/accounts_mn.jpg\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                          
                                                                                                </button>
                                                                                            </td>
                                                                                            <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                                <button type=\"button\" class=\"btn btn-default btn-sm\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_RvrsAccntBtn\" 
                                                                                                        onclick=\"\" style=\"padding:2px !important;\" title=\"Reverse Accounting\"> 
                                                                                                    <img src=\"cmn_images/90.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                          
                                                                                                </button>
                                                                                            </td>   
                                                                                            <td class=\"lovtd\">
                                                                                                <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneAttnRgstrEvntCostRow_WWW123WWW_GLBatchNm\" name=\"oneAttnRgstrEvntCostRow_WWW123WWW_GLBatchNm\" value=\"\" onkeypress=\"gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow_WWW123WWW_GLBatchNm', 'oneAttnRgstrEvntCostLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\">                                                    
                                                                                            </td>
                                                                                            <td class=\"lovtd\" style=\"text-align: center;\">
                                                                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttnRgstrCostLn('oneAttnRgstrEvntCostRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Event Cost\">
                                                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                                </button>
                                                                                            </td> 
                                                                                        </tr>";
                                                                $nwRowHtml31 = urlencode($nwRowHtml333);
                                                                ?> 
                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                    <div class="col-md-4" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            ?>
                                                                            <input type="hidden" id="nwSalesDocLineHtm" value="<?php echo $nwRowHtml33; ?>">
                                                                            <button id="addNwAttnRegisterSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewAttnRegisterRows('oneAttnRegisterSmryLinesTable', 5, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Attendance Record">
                                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>  
                                                                            <button id="addNwAttnRegisterHdCntBtn" type="button" class="btn btn-default hideNotice" style="margin-bottom: 1px;height:30px;" onclick="insertNewAttnRegisterRows('oneAttnRgstrHeadCntTable', 0, '<?php echo $nwRowHtml32; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Head Count Record">
                                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>  
                                                                            <button id="addNwAttnRgstrEvntCostBtn" type="button" class="btn btn-default hideNotice" style="margin-bottom: 1px;height:30px;" onclick="insertNewAttnRegisterRows('oneAttnRgstrEvntCostTable', 5, '<?php echo $nwRowHtml31; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Event Cost">
                                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAttnRegisterForm();"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                                                        <?php } ?>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAttnRegisterForm(<?php echo $attnRegisterID; ?>, 1);"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>    
                                                                    </div>
                                                                    <div class="col-md-6 attnRegisterDetNav" style="padding:0px 15px 0px 15px !important;">
                                                                        <div class="input-group">
                                                                            <input class="form-control" id="attnRegisterDetSrchFor" type = "text" placeholder="Search For" value="<?php
                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                            ?>" onkeyup="enterKeyFuncAttnRegisterDet(event, '', '#attnRegisterDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAttnRegisterID=<?php echo $attnRegisterID; ?>');">
                                                                            <input id="attnRegisterDetPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnRegisterDet('clear', '#attnRegisterDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAttnRegisterID=<?php echo $attnRegisterID; ?>');">
                                                                                <span class="glyphicon glyphicon-remove"></span>
                                                                            </label>
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnRegisterDet('', '#attnRegisterDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAttnRegisterID=<?php echo $attnRegisterID; ?>');">
                                                                                <span class="glyphicon glyphicon-search"></span>
                                                                            </label>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="attnRegisterDetSrchIn">
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
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="attnRegisterDetDsplySze" style="min-width:70px !important;">                            
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
                                                                    <div class="col-md-2 attnRegisterDetNav">
                                                                        <nav aria-label="Page navigation">
                                                                            <ul class="pagination" style="margin: 0px !important;">
                                                                                <li>
                                                                                    <a class="rhopagination" href="javascript:getAttnRegisterDet('previous', '#attnRegisterDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAttnRegisterID=<?php echo $attnRegisterID; ?>');" aria-label="Previous">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a class="rhopagination" href="javascript:getAttnRegisterDet('next', '#attnRegisterDetLines', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=2&sbmtdAttnRegisterID=<?php echo $attnRegisterID; ?>');" aria-label="Next">
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
                                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAttnRegisterLnsTblSctn"> 
                                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                        <div id="attnRegisterDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                        <?php } ?>
                                                        <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                            <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                                <table class="table table-striped table-bordered table-responsive" id="oneAttnRegisterSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="max-width:30px;width:30px;">No.</th>
                                                                            <th style="max-width:70px;width:70px;">Type</th>
                                                                            <th style="min-width:120px;">Name/Description of Attendee/Visitor</th>
                                                                            <th style="max-width:50px;width:50px;text-align: center;">Present?</th>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                            <?php
                                                                            if ($evntPrcsCnt > 0 && $attnRegisterTmTblDetID > 0) {
                                                                                ?>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                                <th style="min-width:100px;">Linked Firm/Sponsor</th>
                                                                                <th style="max-width:90px;width:90px;text-align: right;">Amount Billed</th>
                                                                                <th style="max-width:90px;width:90px;text-align: right;">Amount Paid</th>
                                                                            <?php } else { ?>
                                                                                <th style="min-width:120px;">Comments/Remarks Visit Purpose</th>
                                                                                <th style="max-width:70px;width:70px;text-align: right;">No. of Persons</th>
                                                                            <?php } ?>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>   
                                                                        <?php
                                                                        $mkReadOnly = "";
                                                                        $cntr = 0;
                                                                        $vtypActn = ($canEdt === true) ? "EDIT" : "VIEW";
                                                                        while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                            $attnRgstrDetID = (float) $rowRw[0];
                                                                            $attnRgstrDetPrsnID = (float) $rowRw[2];
                                                                            $attnRgstrDetPrsnLocID = $rowRw[3];
                                                                            $attnRgstrDetPrsnNm = $rowRw[4];
                                                                            $attnRgstrDetIsPrsnt = ($rowRw[7] === 'TRUE') ? '1' : '0';
                                                                            $attnRgstrDetCmmnts = $rowRw[8];
                                                                            $attnRgstrDetType = $rowRw[9];
                                                                            $attnRgstrDetNoPrsns = (float) $rowRw[10];
                                                                            $attnRgstrDetNm = $rowRw[4];
                                                                            $attnRgstrDetCstmrID = (float) $rowRw[12];
                                                                            $attnRgstrDetSpnsrID = (float) $rowRw[13];
                                                                            $attnRgstrDetSpnsrNm = $rowRw[14];
                                                                            $attnRgstrDetAmntBlld = (float) $rowRw[15];
                                                                            $attnRgstrDetAmntPaid = (float) $rowRw[16];
                                                                            $cntr += 1;
                                                                            $statusColor = "#000000";
                                                                            $statusBckgrdColor = "";

                                                                            if ($attnRgstrDetAmntPaid >= $attnRgstrDetAmntBlld) {
                                                                                $statusBckgrdColor = "#00FF00";
                                                                            } else if ($attnRgstrDetAmntBlld > $attnRgstrDetAmntPaid) {
                                                                                $statusBckgrdColor = "#FF0000";
                                                                            } else {
                                                                                $statusBckgrdColor = "#DDDDDD";
                                                                            }
                                                                            ?>
                                                                            <tr id="oneAttnRegisterSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAttnRegisterSmryLinesTable tr').index(this));">                                    
                                                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                <td class="lovtd">
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_DetType" style="width:100% !important;">
                                                                                        <?php
                                                                                        $brghtStr = "";
                                                                                        $isDynmyc = FALSE;
                                                                                        $titleRslt = getLovValues("%", "Both", 0, 100,
                                                                                                $brghtStr,
                                                                                                getLovID("Visitor Classifications"),
                                                                                                $isDynmyc, -1, "", "");
                                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                                            $selectedTxt = "";
                                                                                            if ($titleRow[0] == $attnRgstrDetType) {
                                                                                                $selectedTxt = "selected";
                                                                                            }
                                                                                            ?>
                                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </td>                                              
                                                                                <td class="lovtd"  style="">  
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_RgstrDetID" value="<?php echo $attnRgstrDetID; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_DetPrsnID" value="<?php echo $attnRgstrDetPrsnID; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_DetCstmrID" value="<?php echo $attnRgstrDetCstmrID; ?>" style="width:100% !important;"> 
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_DetSpnsrID" value="<?php echo $attnRgstrDetSpnsrID; ?>" style="width:100% !important;">  
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;">    
                                                                                    <?php
                                                                                    if ($canEdt === true) {
                                                                                        ?>
                                                                                        <div class="input-group" style="width:100% !important;">
                                                                                            <input type="text" class="form-control rqrdFld jbDetRfDc" aria-label="..." id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_LineName" name="oneAttnRegisterSmryRow<?php echo $cntr; ?>_LineName" value="<?php echo $attnRgstrDetNm; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAttnRegisterSmryRow<?php echo $cntr; ?>_LineName', 'oneAttnRegisterSmryLinesTable', 'jbDetRfDc');">
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Customer', 'ShowDialog', function () {}, 'oneAttnRegisterSmryRow<?php echo $cntr; ?>_DetSpnsrID');" data-toggle="tooltip" title="Create/Edit Supplier">
                                                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                                            </label>
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnRgstrLovPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneAttnRegisterSmryRow_<?php echo $cntr; ?>', 'oneAttnRegisterSmryRow<?php echo $cntr; ?>_LineName', 'clear', 0, '', function () {});">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $attnRgstrDetNm; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>                                           
                                                                                <td class="lovtd" style="text-align:center;">
                                                                                    <?php
                                                                                    $isChkd = "";
                                                                                    if ($attnRgstrDetIsPrsnt == "1") {
                                                                                        $isChkd = "checked=\"true\"";
                                                                                    }
                                                                                    ?>
                                                                                    <div class="form-group form-group-sm">
                                                                                        <div class="form-check" style="font-size: 12px !important;">
                                                                                            <label class="form-check-label">
                                                                                                <input type="checkbox" class="form-check-input" id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_IsPrsnt" name="oneAttnRegisterSmryRow<?php echo $cntr; ?>_IsPrsnt" <?php echo $isChkd ?>>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <button type="button" class="btn btn-default btn-sm" id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_TmDetsBtn" 
                                                                                            onclick="getAttnRgstrBreakdown(<?php echo $attnRgstrDetID; ?>, 'ShowDialog', 'Attendance Time Details', '<?php echo $vtypActn; ?>', 'oneAttnRegisterSmryRow<?php echo $cntr; ?>_SlctdAmtBrkdwns');" style="padding:2px !important;" title="Attendance Time Details"> 
                                                                                        <img src="cmn_images/chcklst4.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                         
                                                                                    </button>
                                                                                </td>
                                                                                <?php
                                                                                if ($evntPrcsCnt > 0 && $attnRegisterTmTblDetID > 0) {
                                                                                    ?>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default btn-sm" id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_PointsScrdBtn" 
                                                                                                onclick="" style="padding:2px !important;" title="Attendance Points Scored"> 
                                                                                            <img src="cmn_images/check_all.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                        
                                                                                        </button>
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default btn-sm" id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_InvoiceBtn" 
                                                                                                onclick="" style="padding:2px !important;" title="Attached Attendance Invoices"> 
                                                                                            <img src="cmn_images/invcBill.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                         
                                                                                        </button>
                                                                                    </td>    
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <div class="input-group" style="width:100% !important;">
                                                                                            <input type="text" class="form-control" aria-label="..." id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_LnkdFirmNm" name="oneAttnRegisterSmryRow<?php echo $cntr; ?>_LnkdFirmNm" value="<?php echo $attnRgstrDetSpnsrNm; ?>" style="width:100% !important;" readonly="true">
                                                                                            <input type="hidden" id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_Clsftn" value="Customer">
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Customer', 'ShowDialog', function () {}, 'oneAttnRegisterSmryRow<?php echo $cntr; ?>_DetSpnsrID');" data-toggle="tooltip" title="Create/Edit Supplier">
                                                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                                            </label>
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', 'oneAttnRegisterSmryRow<?php echo $cntr; ?>_Clsftn', 'radio', true, '', 'oneAttnRegisterSmryRow<?php echo $cntr; ?>_DetSpnsrID', 'oneAttnRegisterSmryRow<?php echo $cntr; ?>_LnkdFirmNm', 'clear', 1, '', function () {
                                                                                                                                //getInvRcvblsAcntInfo();
                                                                                                                            });" data-toggle="tooltip" title="Existing Client/Vendor">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </td>  
                                                                                    <td class="lovtd" style="text-align: right;">
                                                                                        <input type="text" class="form-control jbDetAccRate" aria-label="..." id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_AmntBlld" name="oneAttnRegisterSmryRow<?php echo $cntr; ?>_AmntBlld" value="<?php
                                                                                        echo $attnRgstrDetAmntBlld;
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAttnRegisterSmryRow<?php echo $cntr; ?>_AmntBlld', 'oneAttnRegisterSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;background-color:<?php echo $statusBckgrdColor; ?>;color:<?php echo $statusColor; ?>;" readonly="true">                                                    
                                                                                    </td> 
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_AmntPaid" name="oneAttnRegisterSmryRow<?php echo $cntr; ?>_AmntPaid" value="<?php
                                                                                        echo $attnRgstrDetAmntPaid;
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAttnRegisterSmryRow<?php echo $cntr; ?>_AmntPaid', 'oneAttnRegisterSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;background-color:<?php echo $statusBckgrdColor; ?>;color:<?php echo $statusColor; ?>;" readonly="true">                                                    
                                                                                    </td> 
                                                                                <?php } else { ?>
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control jbDetDesc" aria-label="..." id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_LineDesc" name="oneAttnRegisterSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $attnRgstrDetCmmnts; ?>" style="width:100% !important;text-align: left;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAttnRegisterSmryRow<?php echo $cntr; ?>_LineDesc', 'oneAttnRegisterSmryLinesTable', 'jbDetDesc');">                                                    
                                                                                    </td> 
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_NoPrsns" name="oneAttnRegisterSmryRow<?php echo $cntr; ?>_NoPrsns" value="<?php
                                                                                        echo $attnRgstrDetNoPrsns;
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAttnRegisterSmryRow<?php echo $cntr; ?>_NoPrsns', 'oneAttnRegisterSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>                                                    
                                                                                    </td>
                                                                                <?php } ?>
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <button type="button" class="btn btn-default btn-sm" id="oneAttnRegisterSmryRow<?php echo $cntr; ?>_AttchdDocsBtn" 
                                                                                            onclick="" style="padding:2px !important;" title="Attached Documents"> 
                                                                                        <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                          
                                                                                    </button>
                                                                                </td>
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnRegisterLne('oneAttnRegisterSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Attendance Record">
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
                                                                            <?php
                                                                            if ($evntPrcsCnt > 0 && $attnRegisterTmTblDetID > 0) {
                                                                                ?>
                                                                                <th>&nbsp;</th>
                                                                                <th style="">&nbsp;</th>                                           
                                                                                <th style="">&nbsp;</th>                                           
                                                                                <th style="">&nbsp;</th>
                                                                                <th style="">&nbsp;</th>
                                                                            <?php } else { ?>
                                                                                <th style="">&nbsp;</th>
                                                                                <th style="">&nbsp;</th>
                                                                            <?php } ?>
                                                                            <th style="">&nbsp;</th>
                                                                            <th style="">&nbsp;</th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            $("#attnRegisterDetPageNo").val(<?php echo $pageNo; ?>);
                                                        </script>
                                                        <?php if ($vwtyp != 2) { ?>
                                                        </div>
                                                        <div id="attnRegisterHeadCount" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                                            <div class="row" style="padding:0px 15px 0px 15px;">
                                                                <div class="col-md-12" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-right: 0px !important;">
                                                                    <table class="table table-striped table-bordered table-responsive" id="oneAttnRgstrHeadCntTable" cellspacing="0" width="100%" style="width:100%;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>No.</th>
                                                                                <th>Attendance Metric Name/Description</th>
                                                                                <th style="text-align:left;max-width: 100px;width: 100px;">Result</th>
                                                                                <th>Comment</th>
                                                                                <th>...</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>   
                                                                            <?php
                                                                            $cntr = 0;
                                                                            $lovNm = getGnrlRecNm("attn.attn_attendance_events", "event_id",
                                                                                    "attnd_metric_lov_nm", $attnRegisterEvntID);
                                                                            $resultRw = get_One_AttnRgstr_MtrcLns($attnRegisterID, $lovNm,
                                                                                    $canEdt, $attnRegisterEvntID);
                                                                            while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                $trsctnLnID = (float) $rowRw[0];
                                                                                $trsctnLnMtrcNm = $rowRw[1];
                                                                                $trsctnLnResult = $rowRw[2];
                                                                                $trsctnLnComment = $rowRw[3];
                                                                                $trsctnLnPsblValID = (float) $rowRw[4];
                                                                                $trsctnLnRsltTyp = $rowRw[5];
                                                                                $trsctnLnRsltMtrcID = (float) $rowRw[6];
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr id="oneAttnRgstrHeadCntRow_<?php echo $cntr; ?>">                                       
                                                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                                    <td class="lovtd"  style=""> 
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAttnRgstrHeadCntRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAttnRgstrHeadCntRow<?php echo $cntr; ?>_PsblValID" value="<?php echo $trsctnLnPsblValID; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAttnRgstrHeadCntRow<?php echo $cntr; ?>_RsltMtrcID" value="<?php echo $trsctnLnRsltMtrcID; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAttnRgstrHeadCntRow<?php echo $cntr; ?>_RsltTyp" value="<?php echo $trsctnLnRsltTyp; ?>" style="width:100% !important;">
                                                                                        <?php
                                                                                        if ($canEdt === true) {
                                                                                            ?>
                                                                                            <input type="text" class="form-control" aria-label="..." id="oneAttnRgstrHeadCntRow<?php echo $cntr; ?>_MtrcNm" name="oneAttnRgstrHeadCntRow<?php echo $cntr; ?>_MtrcNm" value="<?php
                                                                                            echo $trsctnLnMtrcNm;
                                                                                            ?>" style="width:100% !important;text-align: left;">   
                                                                                               <?php } else {
                                                                                                   ?>
                                                                                            <span><?php echo $trsctnLnMtrcNm; ?></span>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td> 
                                                                                    <td class="lovtd" style="text-align:right;">
                                                                                        <input type="text" class="form-control" aria-label="..." id="oneAttnRgstrHeadCntRow<?php echo $cntr; ?>_Result" name="oneAttnRgstrHeadCntRow<?php echo $cntr; ?>_Result" value="<?php echo $trsctnLnResult; ?>" style="width:100% !important;text-align: right;">   
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align:right;">
                                                                                        <input type="text" class="form-control" aria-label="..." id="oneAttnRgstrHeadCntRow<?php echo $cntr; ?>_Comment" name="oneAttnRgstrHeadCntRow<?php echo $cntr; ?>_Comment" value="<?php echo $trsctnLnComment; ?>" style="width:100% !important;text-align: right;">   
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnRgstrHdCnt('oneAttnRgstrHeadCntRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Register Head Count">
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
                                                                                <th style="">&nbsp;</th>                                           
                                                                                <th style="">&nbsp;</th>                                           
                                                                                <th style="">&nbsp;</th>
                                                                                <th style="">&nbsp;</th>
                                                                                <th style="">&nbsp;</th>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="attnRgstrEvntCost" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                                            <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                                <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                                    <table class="table table-striped table-bordered table-responsive" id="oneAttnRgstrEvntCostTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                                <th style="max-width:80px;width:80px;">Category</th>
                                                                                <th style="min-width:150px;">Event Cost Description</th>
                                                                                <th style="max-width:80px;width:80px;">Source Document</th>
                                                                                <th style="max-width:60px;width:60px;text-align: right;">No. of Days</th>
                                                                                <th style="min-width:60px;width:60px;text-align: right;">No. of Persons</th>
                                                                                <th style="min-width:60px;width:60px;text-align: right;">Cost Per Person</th>
                                                                                <th style="min-width:100px;width:100px;text-align: right;">Amount</th>
                                                                                <th style="min-width:60px;width:60px;text-align: left;">Source Doc. Type</th>
                                                                                <th style="min-width:30px;width:30px;text-align: center;">...</th>
                                                                                <th style="min-width:30px;width:30px;text-align: center;">...</th>
                                                                                <th style="min-width:60px;width:60px;text-align: left;">GL Batch Name</th>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>   
                                                                            <?php
                                                                            $mkReadOnly = "";
                                                                            $cntr = 0;
                                                                            if ($vwtyp == 0 || $vwtyp == 1) {
                                                                                $srchFor = "%";
                                                                                $srchIn = "Description";
                                                                                $pageNo = 1;
                                                                                $lmtSze = 30;
                                                                            }
                                                                            $total = get_Total_AttnCostLns($srchFor, $srchIn,
                                                                                    $attnRegisterID);
                                                                            if ($pageNo > ceil($total / $lmtSze)) {
                                                                                $pageNo = 1;
                                                                            } else if ($pageNo < 1) {
                                                                                $pageNo = ceil($total / $lmtSze);
                                                                            }
                                                                            $curIdx = $pageNo - 1;
                                                                            $resultRw = get_One_AttnCostLns($srchFor, $srchIn, $curIdx,
                                                                                    $lmtSze, $attnRegisterID);
                                                                            while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                $attnCostLnID = (float) $rowRw[0];
                                                                                $attnCostLnSrcDcNum = $rowRw[2];
                                                                                $attnCostLnSrcDcID = (float) $rowRw[3];
                                                                                $attnCostLnSrcDcTyp = $rowRw[4];
                                                                                $attnCostLnCmmnts = $rowRw[5];
                                                                                $attnCostLnNoPrsns = (float) $rowRw[6];
                                                                                $attnCostLnNoDays = (float) $rowRw[7];
                                                                                $attnCostLnUnitCost = (float) $rowRw[8];
                                                                                $attnCostLnTtlAmnt = (float) $rowRw[9];
                                                                                $attnCostLnClsfctn = $rowRw[10];
                                                                                $attnCostLnGLBatchID = (float) $rowRw[11];
                                                                                $attnCostLnGLBatchNm = $rowRw[12];
                                                                                $attnCostLnIncrsDcrs1 = $rowRw[13];
                                                                                $attnCostLnAccountID1 = (float) $rowRw[14];
                                                                                $attnCostLnAccountNm1 = $rowRw[15];
                                                                                $attnCostLnIncrsDcrs2 = $rowRw[16];
                                                                                $attnCostLnAccountID2 = (float) $rowRw[17];
                                                                                $attnCostLnAccountNm2 = $rowRw[18];
                                                                                $attnCostLnEvntCostHngng = $rowRw[19];
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr id="oneAttnRgstrEvntCostRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneAttnRgstrEvntCostLinesTable tr').index(this));">                                    
                                                                                    <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                    <td class="lovtd">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_CostLnID" value="<?php echo $attnCostLnID; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_SrcDcID" value="<?php echo $attnCostLnSrcDcID; ?>" style="width:100% !important;"> 
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_GLBatchID" value="<?php echo $attnCostLnGLBatchID; ?>" style="width:100% !important;"> 
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $attnCostLnAccountID1; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_AccountID2" value="<?php echo $attnCostLnAccountID2; ?>" style="width:100% !important;">  
                                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_CostCtgry" style="width:100% !important;">
                                                                                            <?php
                                                                                            $brghtStr = "";
                                                                                            $isDynmyc = FALSE;
                                                                                            $titleRslt = getLovValues("%", "Both", 0, 100,
                                                                                                    $brghtStr,
                                                                                                    getLovID("Event Cost Categories"),
                                                                                                    $isDynmyc, -1, "", "");
                                                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                                                $selectedTxt = "";
                                                                                                if ($titleRow[0] == $attnCostLnClsfctn) {
                                                                                                    $selectedTxt = "selected";
                                                                                                }
                                                                                                ?>
                                                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </td>  
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control jbDetDesc" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_LineDesc" name="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $attnCostLnCmmnts; ?>" style="width:100% !important;text-align: left;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_LineDesc', 'oneAttnRgstrEvntCostLinesTable', 'jbDetDesc');">                                                    
                                                                                    </td>  
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_SrcDocNum" name="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_SrcDocNum" value="<?php echo $attnCostLnSrcDcNum; ?>" style="width:100% !important;text-align: left;" readonly="true">                                                    
                                                                                    </td>   
                                                                                    <td class="lovtd" style="text-align: right;">
                                                                                        <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_NoOfDays" name="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_NoOfDays" value="<?php
                                                                                        echo $attnCostLnNoDays;
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_NoOfDays', 'oneAttnRgstrEvntCostLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;">                                                    
                                                                                    </td> 
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_NoPrsns" name="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_NoPrsns" value="<?php
                                                                                        echo $attnCostLnNoPrsns;
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_NoPrsns', 'oneAttnRgstrEvntCostLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" readonly="true">                                                    
                                                                                    </td> 
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_UnitCost" name="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_UnitCost" value="<?php
                                                                                        echo $attnCostLnUnitCost;
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_UnitCost', 'oneAttnRgstrEvntCostLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" readonly="true">                                                    
                                                                                    </td>  
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_TtlAmnt" name="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_TtlAmnt" value="<?php
                                                                                        echo $attnCostLnTtlAmnt;
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_TtlAmnt', 'oneAttnRgstrEvntCostLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" readonly="true">                                                    
                                                                                    </td>    
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_SrcDocType" name="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_SrcDocType" value="<?php
                                                                                        echo $attnCostLnSrcDcTyp;
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_SrcDocType', 'oneAttnRgstrEvntCostLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" readonly="true">                                                    
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default btn-sm" id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_CreateAccntBtn" 
                                                                                                onclick="" style="padding:2px !important;" title="Create Accounting"> 
                                                                                            <img src="cmn_images/accounts_mn.jpg" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                          
                                                                                        </button>
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default btn-sm" id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_RvrsAccntBtn" 
                                                                                                onclick="" style="padding:2px !important;" title="Reverse Accounting"> 
                                                                                            <img src="cmn_images/90.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                          
                                                                                        </button>
                                                                                    </td>   
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_GLBatchNm" name="oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_GLBatchNm" value="<?php
                                                                                        echo $attnCostLnGLBatchNm;
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneAttnRgstrEvntCostRow<?php echo $cntr; ?>_GLBatchNm', 'oneAttnRgstrEvntCostLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" readonly="true">                                                    
                                                                                    </td>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnRgstrCostLn('oneAttnRgstrEvntCostRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Event Cost">
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
                                                                                <th style="">&nbsp;</th>
                                                                                <th style="">&nbsp;</th>
                                                                                <th style="">&nbsp;</th>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                $("#attnRgstrEvntCostPageNo").val(<?php echo $pageNo; ?>);
                                                            </script>
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
                //Attendance Days Breakdown Dialog "EDIT"; // 
                $error = "";
                $attnRecID = isset($_POST['attnRecID']) ? cleanInputData($_POST['attnRecID']) : -1;
                $vtypActn = isset($_POST['vtypActn']) ? cleanInputData($_POST['vtypActn']) : 'VIEW';
                $trnsAmtBrkdwnSaveElID = isset($_POST['trnsAmtBrkdwnSaveElID']) ? cleanInputData($_POST['trnsAmtBrkdwnSaveElID']) : '';
                $slctdBrkdwnLines = isset($_POST['slctdBrkdwnLines']) ? cleanInputData($_POST['slctdBrkdwnLines']) : '';
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-4";
                $mkReadOnly = "";
                if ($vtypActn == "VIEW") {
                    $mkReadOnly = "readonly=\"true\"";
                    $canEdt = false;
                }
                ?> 
                <form id='accbTransBrkdwnDiagForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="">
                        <?php if ($vtypActn == "EDIT") { ?>
                            <div class="row">
                                <div class="col-md-12">
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="accbTransBrkdwnDiagHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:25px;width:25px;">No.</th>
                                            <th style="max-width:50px;width:50px;text-align: center;">Present?</th>
                                            <th style="max-width:120px;width:120px;">Date/Time In</th>
                                            <th style="max-width:120px;width:120px;">Date/Time Out</th>
                                            <th style="min-width:120px;">Comments/Remarks Visit Purpose</th>
                                            <th style="max-width:50px;width:50px;text-align: center;">From Main Register?</th>
                                            <?php if ($canEdt === true) { ?>
                                                <th style="max-width:20px;width:20px;">...</th>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th style="max-width:20px;width:20px;">...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ttlTrsctnAmnt = 0;
                                        if (trim($slctdBrkdwnLines, "|~") != "") {
                                            $variousRows = explode("|", trim($slctdBrkdwnLines, "|"));
                                            for ($z = 0; $z < count($variousRows); $z++) {
                                                $crntRow = explode("~", $variousRows[$z]);
                                                if (count($crntRow) == 6) {
                                                    $brkdwnDetID = (float) (cleanInputData1($crntRow[0]));
                                                    $trsctnLnPrsnt = (cleanInputData1($crntRow[1]));
                                                    $trsctnLnStrtDte = (cleanInputData1($crntRow[2]));
                                                    $trsctnLnEndDte = (cleanInputData1($crntRow[3]));
                                                    $trsctnLnCmmnt = (cleanInputData1($crntRow[4]));
                                                    $trsctnLnFrmMain = (cleanInputData1($crntRow[5]));
                                                    $cntr += 1;
                                                    $rcHstryTblNm = "accb.accb_trnsctn_amnt_breakdown";
                                                    $rcHstryPKeyColNm = "transaction_id";
                                                    $rcHstryPKeyColVal = $brkdwnDetID;
                                                    $ttlTrsctnAmnt = $ttlTrsctnAmnt + $brkdwnTtlVal;
                                                    ?>
                                                    <tr id="accbTransBrkdwnDiagHdrsRow_<?php echo $cntr; ?>">                                    
                                                        <td class="lovtd"><?php echo ($cntr); ?></td>                                            
                                                        <td class="lovtd" style="text-align:center;">
                                                            <?php
                                                            $isChkd = "";
                                                            if ($trsctnLnPrsnt == "TRUE") {
                                                                $isChkd = "checked=\"true\"";
                                                            }
                                                            ?>
                                                            <div class="form-group form-group-sm">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_IsPresent" name="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_IsPresent" <?php echo $isChkd ?>>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>                                              
                                                        <td class="lovtd"  style="">  
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                    <input class="form-control" size="16" type="text" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $trsctnLnStrtDte; ?>">
                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div> 
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $trsctnLnStrtDte; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td> 
                                                        <td class="lovtd" style="text-align: right;">
                                                            <?php
                                                            if ($canEdt === true) {
                                                                ?>
                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                    <input class="form-control" size="16" type="text" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_EndDte" value="<?php echo $trsctnLnEndDte; ?>">
                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div> 
                                                            <?php } else { ?>
                                                                <span><?php echo $trsctnLnEndDte; ?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="lovtd">
                                                            <textarea class="form-control rqrdFld acbBrkdwnDesc" rows="1" cols="20" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" name="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" <?php echo $mkReadOnly; ?> style="text-align:left !important;width:100%;"  onkeypress="gnrlFldKeyPress(event, 'accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc', 'accbTransBrkdwnDiagHdrsTable', 'acbBrkdwnDesc');"><?php echo $trsctnLnCmmnt; ?></textarea>  
                                                            <input type="hidden" class="form-control" aria-label="..." id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnLnID" value="<?php echo $row[0]; ?>" style="width:100% !important;">
                                                        </td> 
                                                        <td class="lovtd">
                                                            <span><?php echo $trsctnLnFrmMain; ?></span>
                                                        </td> 
                                                        <?php if ($canEdt === true) { ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnRgstrTmBrkdwn('accbTransBrkdwnDiagHdrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Amount Breakdown">
                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        <?php } ?>
                                                        <?php if ($canVwRcHstry === true) { ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" 
                                                                        onclick="getRecHstry('<?php
                                                                        echo urlencode(encrypt1(($rcHstryPKeyColVal . "|" . $rcHstryTblNm . "|" . $rcHstryPKeyColNm),
                                                                                        $smplTokenWord1));
                                                                        ?>');" style="padding:2px !important;">
                                                                    <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        <?php } ?> 
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        } else if ($attnRecID > 0) {
                                            $result = get_One_AttnRgstr_Times("%", "Person Name", 0, 100000000, $attnRecID);
                                            while ($row = loc_db_fetch_array($result)) {
                                                $brkdwnDetID = (float) $row[0];
                                                $trsctnLnStrtDte = $row[2];
                                                $trsctnLnEndDte = $row[3];
                                                $trsctnLnCmmnt = $row[5];
                                                $trsctnLnPrsnt = $row[4];
                                                $trsctnLnFrmMain = $row[7];
                                                $cntr += 1;
                                                IF ($trsctnLnFrmMain == "NO") {
                                                    $rcHstryTblNm = "attn.attn_attendance_recs_times";
                                                    $rcHstryPKeyColNm = "attnd_det_rec_id";
                                                    $rcHstryPKeyColVal = $row[0];
                                                } else {
                                                    $rcHstryTblNm = "attn.attn_attendance_recs";
                                                    $rcHstryPKeyColNm = "attnd_rec_id";
                                                    $rcHstryPKeyColVal = $row[1];
                                                }
                                                ?>
                                                <tr id="accbTransBrkdwnDiagHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($cntr); ?></td>                                           
                                                    <td class="lovtd" style="text-align:center;">
                                                        <?php
                                                        $isChkd = "";
                                                        if ($trsctnLnPrsnt == "TRUE") {
                                                            $isChkd = "checked=\"true\"";
                                                        }
                                                        ?>
                                                        <div class="form-group form-group-sm">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_IsPresent" name="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_IsPresent" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>                                              
                                                    <td class="lovtd"  style="">  
                                                        <?php
                                                        if ($canEdt === true) {
                                                            ?>
                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                <input class="form-control" size="16" type="text" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $trsctnLnStrtDte; ?>">
                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div> 
                                                        <?php } else {
                                                            ?>
                                                            <span><?php echo $trsctnLnStrtDte; ?></span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td> 
                                                    <td class="lovtd" style="text-align: right;">
                                                        <?php
                                                        if ($canEdt === true) {
                                                            ?>
                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                <input class="form-control" size="16" type="text" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_EndDte" value="<?php echo $trsctnLnEndDte; ?>">
                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div> 
                                                        <?php } else { ?>
                                                            <span><?php echo $trsctnLnEndDte; ?></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="lovtd">
                                                        <textarea class="form-control rqrdFld acbBrkdwnDesc" rows="1" cols="20" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" name="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" <?php echo $mkReadOnly; ?> style="text-align:left !important;width:100%;"  onkeypress="gnrlFldKeyPress(event, 'accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc', 'accbTransBrkdwnDiagHdrsTable', 'acbBrkdwnDesc');"><?php echo $trsctnLnCmmnt; ?></textarea>  
                                                        <input type="hidden" class="form-control" aria-label="..." id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnLnID" value="<?php echo $row[0]; ?>" style="width:100% !important;"> 
                                                    </td> 
                                                    <td class="lovtd">
                                                        <span><?php echo $trsctnLnFrmMain; ?></span>
                                                    </td> 
                                                    <?php if ($canEdt === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnRgstrTmBrkdwn('accbTransBrkdwnDiagHdrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Amount Breakdown">
                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" 
                                                                    onclick="getRecHstry('<?php
                                                                    echo urlencode(encrypt1(($rcHstryPKeyColVal . "|" . $rcHstryTblNm . "|" . $rcHstryPKeyColNm),
                                                                                    $smplTokenWord1));
                                                                    ?>');" style="padding:2px !important;">
                                                                <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>   
                                                </tr>
                                                <?php
                                            }
                                        }
                                        if ($vtypActn != "VIEW") {
                                            $i = 1;
                                            while ($i <= 5) {
                                                $brkdwnDetID = -1;
                                                $trsctnLnPrsnt = "FALSE";
                                                $trsctnLnStrtDte = "";
                                                $trsctnLnEndDte = "";
                                                $trsctnLnCmmnt = "";
                                                $trsctnLnFrmMain = "NO";
                                                $cntr += 1;
                                                $i += 1;
                                                ?>
                                                <tr id="accbTransBrkdwnDiagHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($cntr); ?></td>                                            
                                                    <td class="lovtd" style="text-align:center;">
                                                        <?php
                                                        $isChkd = "";
                                                        if ($trsctnLnPrsnt == "TRUE") {
                                                            $isChkd = "checked=\"true\"";
                                                        }
                                                        ?>
                                                        <div class="form-group form-group-sm">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_IsPresent" name="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_IsPresent" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>                                              
                                                    <td class="lovtd"  style="">  
                                                        <?php
                                                        if ($canEdt === true) {
                                                            ?>
                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                <input class="form-control" size="16" type="text" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $trsctnLnStrtDte; ?>">
                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div> 
                                                        <?php } else {
                                                            ?>
                                                            <span><?php echo $trsctnLnStrtDte; ?></span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td> 
                                                    <td class="lovtd" style="text-align: right;">
                                                        <?php
                                                        if ($canEdt === true) {
                                                            ?>
                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                <input class="form-control" size="16" type="text" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_EndDte" value="<?php echo $trsctnLnEndDte; ?>">
                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div> 
                                                        <?php } else { ?>
                                                            <span><?php echo $trsctnLnEndDte; ?></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="lovtd">
                                                        <textarea class="form-control rqrdFld acbBrkdwnDesc" rows="1" cols="20" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" name="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" <?php echo $mkReadOnly; ?> style="text-align:left !important;width:100%;"  onkeypress="gnrlFldKeyPress(event, 'accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc', 'accbTransBrkdwnDiagHdrsTable', 'acbBrkdwnDesc');"><?php echo $trsctnLnCmmnt; ?></textarea>  
                                                        <input type="hidden" class="form-control" aria-label="..." id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnLnID" value="<?php echo $row[0]; ?>" style="width:100% !important;"> 
                                                    </td> 
                                                    <td class="lovtd">
                                                        <span><?php echo $trsctnLnFrmMain; ?></span>
                                                    </td> 
                                                    <?php if ($canEdt === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttnRgstrTmBrkdwn('accbTransBrkdwnDiagHdrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Amount Breakdown">
                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            &nbsp;
                                                        </td>
                                                    <?php } ?> 
                                                </tr>
                                                <?php
                                            }
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
                                            <?php if ($canEdt === true) { ?>
                                                <th style="max-width:20px;width:20px;">&nbsp;</th>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th style="max-width:20px;width:20px;">&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>                     
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="float:right;">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <?php if ($vtypActn == "EDIT") { ?>
                                        <button type="button" class="btn btn-primary" onclick="applyNewAttnRgstrBrkdwn('myFormsModaly', '<?php echo $trnsAmtBrkdwnSaveElID; ?>');">Apply Details</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <?php
            }
        }
    }
}    