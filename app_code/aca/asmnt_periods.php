<?php
$canAdd = test_prmssns($dfltPrvldgs[19], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[20], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[21], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
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
                    echo delete_AcaPeriods($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Complaints/Observations
                header("content-type:application/json");
                $slctdPeriodIDs = isset($_POST['slctdPeriodIDs']) ? cleanInputData($_POST['slctdPeriodIDs']) : "";
                $exitErrMsg = "";
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdPeriodIDs, "|~") != "") {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdPeriodIDs, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 8) {
                            $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_Type = cleanInputData1($crntRow[1]);
                            $ln_PeriodNm = cleanInputData1($crntRow[2]);
                            $ln_PeriodDesc = cleanInputData1($crntRow[3]);
                            $ln_StrtDte = cleanInputData1($crntRow[4]);
                            if ($ln_StrtDte === "") {
                                $ln_StrtDte = $gnrlTrnsDteDMY;
                            }
                            $ln_EndDte = cleanInputData1($crntRow[5]);
                            if ($ln_EndDte === "") {
                                $ln_EndDte = "31-Dec-4000";
                            }
                            $ln_Status = cleanInputData1($crntRow[6]);
                            if ($ln_Status == "") {
                                $ln_Status = "Never Opened";
                            }
                            $ln_PeriodNumber = (float) (cleanInputData1($crntRow[7]));
                            $errMsg = "";
                            if ($ln_Type === "" || $ln_PeriodNm === "") {
                                $errMsg = "Row " . ($y + 1) . ":- Period Type and Name are all required Fields!<br/>";
                            }
                            if ($errMsg === "") {
                                if ($ln_TrnsLnID <= 0) {
                                    $ln_TrnsLnID = getNew_AcaPeriodsID();
                                    $afftctd += create_AcaPeriods($ln_TrnsLnID, $ln_PeriodNm, $ln_PeriodDesc, $ln_StrtDte, $ln_EndDte, $ln_Status, $ln_Type, $ln_PeriodNumber);
                                } else if ($ln_TrnsLnID > 0) {
                                    $afftctd += update_AcaPeriods($ln_TrnsLnID, $ln_PeriodNm, $ln_PeriodDesc, $ln_StrtDte, $ln_EndDte, $ln_Status, $ln_Type,$ln_PeriodNumber);
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
                    }
                }

                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Assessment Period(s) Successfully Saved!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Assessment Period(s) Successfully Saved!";
                }
                $arr_content['percent'] = 100;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&mdl=$mdlACAorPMS');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Assessment Periods</span>
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
                $total = get_Total_AcaPeriods($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_AcaPeriods($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-5";
                $colClassType3 = "col-md-5";
                ?> 
                <form id='acaPeriodsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">ALL ASSESSMENT PERIODS</legend>
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

                                $nwRowHtml31 = "<tr id=\"acaPeriodsHdrsRow__WWW123WWW\">                                    
                                                    <td class=\"lovtd\">New</td>
                                                        <td class=\"lovtd\">
                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select rqrdFld\" id=\"acaPeriodsHdrsRow_WWW123WWW_Type\" style=\"width:100% !important;\">";
                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                $srchInsArrys = array("Semester", "Term", "Trimester", "Year", "Half-Year", "Quarter", "Month", "Other");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    $nwRowHtml31 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                }
                                $nwRowHtml31 .= "</select>
                                            </td>      
                                            <td class=\"lovtd\">
                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"acaPeriodsHdrsRow_WWW123WWW_PeriodNumber\" name=\"acaPeriodsHdrsRow_WWW123WWW_PeriodNumber\" value=\"1\" style=\"width:100% !important;\">     
                                            </td>    
                                            <td class=\"lovtd\">
                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"acaPeriodsHdrsRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"acaPeriodsHdrsRow_WWW123WWW_PeriodNm\" name=\"acaPeriodsHdrsRow_WWW123WWW_PeriodNm\" value=\"\" style=\"width:100% !important;text-align: left;\">                                                                                                 
                                            </td> 
                                            <td class=\"lovtd\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"acaPeriodsHdrsRow_WWW123WWW_PeriodDesc\" name=\"acaPeriodsHdrsRow_WWW123WWW_PeriodDesc\" value=\"\" style=\"width:100% !important;text-align: left;\">                                                    
                                            </td> 
                                            <td class=\"lovtd\">
                                                        <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\" style=\"width:100%;\">
                                                            <input class=\"form-control\" size=\"16\" type=\"text\" id=\"acaPeriodsHdrsRow_WWW123WWW_StrtDte\" name=\"acaPeriodsHdrsRow_WWW123WWW_StrtDte\" value=\"\" readonly=\"\">
                                                            <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                            <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                        </div>                                                   
                                            </td>
                                            <td class=\"lovtd\">
                                                        <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\" style=\"width:100%;\">
                                                            <input class=\"form-control\" size=\"16\" type=\"text\" id=\"acaPeriodsHdrsRow_WWW123WWW_EndDte\" name=\"acaPeriodsHdrsRow_WWW123WWW_EndDte\" value=\"\" readonly=\"\">
                                                            <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                            <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                        </div>                                                     
                                            </td>
                                            <td class=\"lovtd\" style=\"text-align:center;\">&nbsp;</td>
                                            <td class=\"lovtd\" style=\"text-align:center;\">&nbsp;</td>";
                                if ($canDel === true) {
                                    $nwRowHtml31 .= "<td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Assessment Period\" onclick=\"delAcaPeriods('acaPeriodsHdrsRow__WWW123WWW');\" style=\"padding:2px !important;\" style=\"padding:2px !important;\">
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
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewAcaPeriodsRows('acaPeriodsHdrsTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add New Assessment Period">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Assessment Period
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAcaPeriodsForm();" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        SAVE
                                    </button> 
                                </div>
                            <?php } ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="acaPeriodsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAcaPeriods(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                    <input id="acaPeriodsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <input id="sbmtdScmRtrnSrcDocID" type = "hidden" value="-1">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaPeriods('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaPeriods('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaPeriodsSrchIn">
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
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaPeriodsDsplySze" style="min-width:70px !important;">                            
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
                                            <a href="javascript:getAcaPeriods('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getAcaPeriods('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div> 
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="acaPeriodsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:30px;width:30px;">No.</th>
                                            <th style="max-width:75px;width:75px;">Type</th>
                                            <th style="max-width:55px;width:55px;">Period Number</th>
                                            <th>Period Name</th>
                                            <th>Period Description</th>
                                            <th style="max-width:150px;width:150px;">Start Date</th>
                                            <th style="max-width:150px;width:150px;">End Date</th>
                                            <th style="max-width:65px;width:65px;text-align: center;">Status</th>
                                            <th style="max-width:80px;width:80px;text-align: center;">Change Status</th>
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
                                        $reportTitle = "Period Close Process";
                                        $reportName = "Period Close Process";
                                        $rptID = getRptID($reportName);
                                        $prmID1 = getParamIDUseSQLRep("{:orgID}", $rptID);
                                        $prmID2 = getParamIDUseSQLRep("{:closing_dte}", $rptID);

                                        $reportTitle1 = "Reversal of Posted Period Close Process";
                                        $reportName1 = "Reversal of Posted Period Close Process";
                                        $rptID1 = getRptID($reportName1);
                                        $prmID11 = getParamIDUseSQLRep("{:orgID}", $rptID1);
                                        $prmID12 = getParamIDUseSQLRep("{:closing_dte}", $rptID1);
                                        while ($row = loc_db_fetch_array($result)) {
                                            $trsctnLnID = (float) $row[0];
                                            $trsctnLnName = $row[1];
                                            $trsctnLnDesc = $row[2];
                                            $trsctnLnStrtDte = $row[3];
                                            $trsctnLnEndDte = $row[4];
                                            $trsctnLnRecType = $row[5];
                                            $trsctnLnStatus = $row[6];
                                            $trsctnLnPrdNum =(float) $row[7];
                                            $cntr += 1;
                                            $statusColor = "#000000";
                                            $statusBckgrdColor = "";
                                            ?>
                                            <tr id="acaPeriodsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>  
                                                <td class="lovtd">
                                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="acaPeriodsHdrsRow<?php echo $cntr; ?>_Type" style="width:100% !important;">
                                                        <?php
                                                        $valslctdArry = array("", "", "", "", "", "", "", "");
                                                        $srchInsArrys = array("Semester", "Term", "Trimester", "Year", "Half-Year", "Quarter", "Month", "Other");
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
                                                    <?php
                                                    if ($canEdt === true) {
                                                        ?>
                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="acaPeriodsHdrsRow<?php echo $cntr; ?>_PeriodNumber" name="acaPeriodsHdrsRow<?php echo $cntr; ?>_PeriodNumber" value="<?php echo $trsctnLnPrdNum; ?>" style="width:100% !important;">     
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnPrdNum; ?></span>
                                                    <?php } ?>                                             
                                                </td>  
                                                <td class="lovtd">
                                                    <input type="hidden" class="form-control" aria-label="..." id="acaPeriodsHdrsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                    <?php
                                                    if ($canEdt === true) {
                                                        ?>
                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="acaPeriodsHdrsRow<?php echo $cntr; ?>_PeriodNm" name="acaPeriodsHdrsRow<?php echo $cntr; ?>_PeriodNm" value="<?php echo $trsctnLnName; ?>" style="width:100% !important;">     
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnName; ?></span>
                                                    <?php } ?>                                             
                                                </td> 
                                                <td class="lovtd">
                                                    <input type="text" class="form-control" aria-label="..." id="acaPeriodsHdrsRow<?php echo $cntr; ?>_PeriodDesc" name="acaPeriodsHdrsRow<?php echo $cntr; ?>_PeriodDesc" value="<?php echo $trsctnLnDesc; ?>" style="width:100% !important;text-align: left;">                                                    
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdt === true) { ?>
                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100%;">
                                                            <input class="form-control" size="16" type="text" id="acaPeriodsHdrsRow<?php echo $cntr; ?>_StrtDte" name="acaPeriodsHdrsRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $trsctnLnStrtDte; ?>" readonly="">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>  
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnStrtDte; ?></span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdt === true) { ?>
                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100%;">
                                                            <input class="form-control" size="16" type="text" id="acaPeriodsHdrsRow<?php echo $cntr; ?>_EndDte" name="acaPeriodsHdrsRow<?php echo $cntr; ?>_EndDte" value="<?php echo $trsctnLnEndDte; ?>" readonly="">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div> 
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnEndDte; ?></span>
                                                    <?php } ?>                                                         
                                                </td>                                            
                                                <td class="lovtd" style="text-align:center;">
                                                    <input type="hidden" class="form-control" aria-label="..." id="acaPeriodsHdrsRow<?php echo $cntr; ?>_Status" value="<?php echo $trsctnLnStatus; ?>" style="width:100% !important;">
                                                    <?php echo $trsctnLnStatus; ?>
                                                </td> 
                                                <td class="lovtd">
                                                    <?php
                                                    $paramRepsNVals = $prmID1 . "~" . $orgID . "|" . $prmID2 . "~" . $row[4] . "|-130~" . $reportTitle . "|-190~HTML";
                                                    $paramStr = urlencode($paramRepsNVals);

                                                    $paramRepsNVals1 = $prmID11 . "~" . $orgID . "|" . $prmID12 . "~" . $row[4] . "|-130~" . $reportTitle1 . "|-190~HTML";
                                                    $paramStr1 = urlencode($paramRepsNVals1);

                                                    $btnTxt = "";
                                                    $btnTxtImg = "";
                                                    if ($trsctnLnStatus == "Never Opened") {
                                                        $btnTxt = "Open";
                                                        $btnTxtImg = "accounts_mn.jpg";
                                                    } else if ($trsctnLnStatus == "Open") {
                                                        $btnTxt = "Close";
                                                        $btnTxtImg = "90.png";
                                                    } else if ($trsctnLnStatus == "Closed") {
                                                        $btnTxt = "Re-Open";
                                                        $btnTxtImg = "undo_256.png";
                                                    }
                                                    if ($canEdt === true) {
                                                        ?>
                                                        <button type="button" class="btn btn-default btn-md" data-toggle="tooltip" data-placement="bottom" title="Action on Period" onclick="actOnPeriodStatus('acaPeriodsHdrsRow_<?php echo $cntr; ?>');" style="padding:5px !important;width:100%;">
                                                            <img src="cmn_images/<?php echo $btnTxtImg; ?>" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php echo $btnTxt; ?>
                                                        </button>
                                                    <?php } else { ?>
                                                        <span>&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td> 
                                                <?php
                                                if ($canDel === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Assessment Periods" onclick="delAcaPeriods('acaPeriodsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php
                                                if ($canVwRcHstry === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($trsctnLnID . "|aca.aca_assessment_periods|assmnt_period_id"),
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