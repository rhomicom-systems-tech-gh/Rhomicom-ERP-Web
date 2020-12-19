<?php
$canAdd = test_prmssns($dfltPrvldgs[29], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[30], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[31], $mdlNm);
$canVwRcHstry = test_prmssns($dfltPrvldgs[23], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Doc Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteComplaint($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Complaints/Observations
                header("content-type:application/json");
                $slctdComplaintIDs = isset($_POST['slctdComplaintIDs']) ? cleanInputData($_POST['slctdComplaintIDs']) : "";
                $exitErrMsg = "";
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdComplaintIDs, "|~") != "") {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdComplaintIDs, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 7) {
                            $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_CstmrID = (float) cleanInputData1($crntRow[1]);
                            $ln_PrsnID = (float) cleanInputData1($crntRow[2]);
                            $ln_CmplntTyp = cleanInputData1($crntRow[3]);
                            $ln_LineDesc = cleanInputData1($crntRow[4]);
                            $ln_CmplnSoltn = cleanInputData1($crntRow[5]);
                            $ln_IsRslvd = (cleanInputData1($crntRow[6]) == "YES") ? TRUE : FALSE;
                            $errMsg = "";
                            if ($ln_CmplntTyp === "" || $ln_LineDesc === "") {
                                $errMsg = "Row " . ($y + 1) . ":- Complaint Type and Description are all required Fields!<br/>";
                            }
                            if ($errMsg === "") {
                                if ($ln_TrnsLnID <= 0) {
                                    $ln_TrnsLnID = getNewCmplntID();
                                    $afftctd += createComplaint($ln_TrnsLnID, $ln_PrsnID, -1, $ln_CstmrID, "", $ln_CmplntTyp, $ln_LineDesc, $ln_CmplnSoltn, $ln_IsRslvd);
                                } else if ($ln_TrnsLnID > 0) {
                                    $afftctd += updateComplaint($ln_TrnsLnID, $ln_PrsnID, -1, $ln_CstmrID,
                                            "", $ln_CmplntTyp, $ln_LineDesc, $ln_CmplnSoltn, $ln_IsRslvd);
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
                    }
                }

                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Complaint(s) Successfully Saved!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Complaint(s) Successfully Saved!";
                }
                $arr_content['percent'] = 100;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Complaints/Issues</span>
				</li>
                               </ul>
                              </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Complaint/Observation Type';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_Total_Complaints($srchFor, $srchIn, -1);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_Complaints($srchFor, $srchIn, $curIdx, $lmtSze, -1);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-5";
                $colClassType3 = "col-md-5";
                ?> 
                <form id='hotlComplntsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">ALL FACILITY COMPLAINTS/OBSERVATIONS</legend>
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

                                $nwRowHtml31 = "<tr id=\"hotlComplntsHdrsRow__WWW123WWW\">                                    
                                                <td class=\"lovtd\">New</td>    
                                                <td class=\"lovtd\">
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"hotlComplntsHdrsRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"hotlComplntsHdrsRow_WWW123WWW_CstmrID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"hotlComplntsHdrsRow_WWW123WWW_PrsnID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                    <div class=\"input-group\" style=\"width:100% !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"hotlComplntsHdrsRow_WWW123WWW_CstmrNm\" name=\"hotlComplntsHdrsRow_WWW123WWW_CstmrNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', '', 'radio', true, '', 'hotlComplntsHdrsRow_WWW123WWW_CstmrID', 'hotlComplntsHdrsRow_WWW123WWW_CstmrNm', 'clear', 1, '', function () {});\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                            </label>
                                                    </div>                                              
                                                </td>   
                                                <td class=\"lovtd\">
                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"hotlComplntsHdrsRow_WWW123WWW_CmplntTyp\" name=\"hotlComplntsHdrsRow_WWW123WWW_CmplntTyp\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Complaint/Observation Types', '', '', '', 'radio', true, '', 'hotlComplntsHdrsRow_WWW123WWW_CmplntTyp', 'hotlComplntsHdrsRow_WWW123WWW_CmplntTyp', 'clear', 1, '', function () {});\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                            </label>
                                                        </div>                                            
                                                </td>
                                                <td class=\"lovtd\">
                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"hotlComplntsHdrsRow_WWW123WWW_LineDesc\" name=\"hotlComplntsHdrsRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;text-align: left;\">                                                    
                                                </td>
                                                <td class=\"lovtd\">
                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"hotlComplntsHdrsRow_WWW123WWW_CmplnSoltn\" name=\"hotlComplntsHdrsRow_WWW123WWW_CmplnSoltn\" value=\"\" style=\"width:100% !important;text-align: left;\">                                                    
                                                </td> 
                                                <td class=\"lovtd\">
                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"hotlComplntsHdrsRow_WWW123WWW_PrsnNm\" name=\"hotlComplntsHdrsRow_WWW123WWW_PrsnNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'hotlComplntsHdrsRow_WWW123WWW_PrsnID', 'hotlComplntsHdrsRow_WWW123WWW_PrsnNm', 'clear', 1, '', function () {});\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                            </label>
                                                        </div>                                            
                                                </td>                                           
                                                <td class=\"lovtd\" style=\"text-align:center;\">
                                                    <div class=\"form-group form-group-sm\">
                                                        <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                            <label class=\"form-check-label\">
                                                                <input type=\"checkbox\" class=\"form-check-input\" id=\"hotlComplntsHdrsRow_WWW123WWW_IsRslvd\" name=\"hotlComplntsHdrsRow_WWW123WWW_IsRslvd\">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\" style=\"text-align: center;\">
                                                    <div style=\"background-color:red;border-radius:3px;padding:1px;\">
                                                        <span style=\"color:black\">PENDING</span>
                                                    </div>                                                  
                                                </td> 
                                                <td class=\"lovtd\">
                                                    <span>&nbsp;</span>
                                                </td>";
                                                if ($canDel === true) {
                                                    $nwRowHtml31 .= "<td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Complaint/Observation\" onclick=\"delHotlComplnts('hotlComplntsHdrsRow__WWW123WWW');\" style=\"padding:2px !important;\" style=\"padding:2px !important;\">
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
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewHotlComplntsRows('hotlComplntsHdrsTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add New Complaint/Observation">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        NEW COMPLAINT
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveHotlComplntsForm();" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        SAVE
                                    </button> 
                                </div>
                            <?php } ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="hotlComplntsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncHotlComplnts(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="hotlComplntsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <input id="sbmtdScmRtrnSrcDocID" type = "hidden" value="-1">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getHotlComplnts('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getHotlComplnts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="hotlComplntsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "");
                                        $srchInsArrys = array(
                                            "Complaint/Observation Type", "Customer",
                                            "Date Created", "Description",
                                            "Person To Resolve", "Status");
                                        /* , "Facility Number",
                                          "Table/Room Number" */
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="hotlComplntsDsplySze" style="min-width:70px !important;">                            
                                        <?php
                                        $valslctdArry = array("", "", "", "",
                                            "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 30,
                                            50, 100, 500, 1000, 1000000);
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
                                            <a href="javascript:getHotlComplnts('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getHotlComplnts('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div> 
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="hotlComplntsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:35px;width:35px;">No.</th>
                                            <th>Customer</th>
                                            <th>Complaint/Observation Type</th>
                                            <th>Complaint/Observation Description</th>
                                            <th>Suggested Solution</th>
                                            <th>Person To Resolve</th>
                                            <th style="max-width:75px;width:75px;">Issue Resolved?</th>
                                            <th style="max-width:75px;width:75px;">Status</th>
                                            <th>Date Created/Document Number</th>
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
                                            $trsctnLnCmplnTyp = $row[1];
                                            $trsctnLnCmplnDesc = $row[2];
                                            $trsctnLnCmplnSoltn = $row[3];
                                            $trsctnLnCstmrID = (float) $row[4];
                                            $trsctnLnCstmrName = $row[5];
                                            $trsctnLnPrsnID = (float) $row[6];
                                            $trsctnLnPrsnName = $row[7];
                                            $trsctnLnIsRslvd = $row[8];
                                            $trsctnLnStatus = $row[9];
                                            $trsctnLnDocNum = $row[10];
                                            $cntr += 1;
                                            $statusColor = "#000000";
                                            $statusBckgrdColor = "";
                                            if ($trsctnLnIsRslvd == "1") {
                                                $statusBckgrdColor = "#00FF00";
                                            } else {
                                                $statusBckgrdColor = "#FF0000";
                                            }
                                            ?>
                                            <tr id="hotlComplntsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                <td class="lovtd">
                                                    <input type="hidden" class="form-control" aria-label="..." id="hotlComplntsHdrsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                    <input type="hidden" class="form-control" aria-label="..." id="hotlComplntsHdrsRow<?php echo $cntr; ?>_CstmrID" value="<?php echo $trsctnLnCstmrID; ?>" style="width:100% !important;"> 
                                                    <input type="hidden" class="form-control" aria-label="..." id="hotlComplntsHdrsRow<?php echo $cntr; ?>_PrsnID" value="<?php echo $trsctnLnPrsnID; ?>" style="width:100% !important;"> 
                                                    <?php
                                                    if ($canEdt === true) {
                                                        ?>
                                                        <div class="input-group" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="hotlComplntsHdrsRow<?php echo $cntr; ?>_CstmrNm" name="hotlComplntsHdrsRow<?php echo $cntr; ?>_CstmrNm" value="<?php echo $trsctnLnCstmrName; ?>" readonly="true" style="width:100% !important;">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', '', 'radio', true, '', 'hotlComplntsHdrsRow<?php echo $cntr; ?>_CstmrID', 'hotlComplntsHdrsRow<?php echo $cntr; ?>_CstmrNm', 'clear', 1, '', function () {});">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>      
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnCstmrName; ?></span>
                                                    <?php } ?>                                             
                                                </td>   
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canEdt === true) {
                                                        ?>
                                                        <div class="input-group" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="hotlComplntsHdrsRow<?php echo $cntr; ?>_CmplntTyp" name="hotlComplntsHdrsRow<?php echo $cntr; ?>_CmplntTyp" value="<?php echo $trsctnLnCmplnTyp; ?>" readonly="true" style="width:100% !important;">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Complaint/Observation Types', '', '', '', 'radio', true, '', 'hotlComplntsHdrsRow<?php echo $cntr; ?>_CmplntTyp', 'hotlComplntsHdrsRow<?php echo $cntr; ?>_CmplntTyp', 'clear', 1, '', function () {});">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>      
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnCmplnTyp; ?></span>
                                                    <?php } ?>                                             
                                                </td>
                                                <td class="lovtd">
                                                    <input type="text" class="form-control" aria-label="..." id="hotlComplntsHdrsRow<?php echo $cntr; ?>_LineDesc" name="hotlComplntsHdrsRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnCmplnDesc; ?>" style="width:100% !important;text-align: left;">                                                    
                                                </td>
                                                <td class="lovtd">
                                                    <input type="text" class="form-control" aria-label="..." id="hotlComplntsHdrsRow<?php echo $cntr; ?>_CmplnSoltn" name="hotlComplntsHdrsRow<?php echo $cntr; ?>_CmplnSoltn" value="<?php echo $trsctnLnCmplnSoltn; ?>" style="width:100% !important;text-align: left;">                                                    
                                                </td> 
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canEdt === true) {
                                                        ?>
                                                        <div class="input-group" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="hotlComplntsHdrsRow<?php echo $cntr; ?>_PrsnNm" name="hotlComplntsHdrsRow<?php echo $cntr; ?>_PrsnNm" value="<?php echo $trsctnLnPrsnName; ?>" readonly="true" style="width:100% !important;">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'hotlComplntsHdrsRow<?php echo $cntr; ?>_PrsnID', 'hotlComplntsHdrsRow<?php echo $cntr; ?>_PrsnNm', 'clear', 1, '', function () {});">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>      
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnPrsnName; ?></span>
                                                    <?php } ?>                                             
                                                </td>                                           
                                                <td class="lovtd" style="text-align:center;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($trsctnLnIsRslvd == "1") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <div class="form-group form-group-sm">
                                                        <div class="form-check" style="font-size: 12px !important;">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" id="hotlComplntsHdrsRow<?php echo $cntr; ?>_IsRslvd" name="hotlComplntsHdrsRow<?php echo $cntr; ?>_IsRslvd" <?php echo $isChkd ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="lovtd" style="text-align: center;">
                                                    <div style="background-color:<?php echo $statusBckgrdColor; ?>;border-radius:3px;padding:1px;">
                                                        <span style="color:<?php echo $statusColor; ?>"><?php echo $trsctnLnStatus; ?></span>
                                                    </div>                                                  
                                                </td> 
                                                <td class="lovtd">
                                                    <span><?php echo $trsctnLnDocNum; ?></span>
                                                </td>
                                                <?php
                                                if ($canDel === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Complaint/Observation" onclick="delHotlComplnts('hotlComplntsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php
                                                if ($canVwRcHstry === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row[0] . "|hotl.cmplnts_obsvrtns|complaint_id"),
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