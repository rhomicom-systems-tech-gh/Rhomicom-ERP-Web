<?php
$canAdd = test_prmssns($dfltPrvldgs[22], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[23], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[24], $mdlNm);

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
                    echo delete_AcaPosHldrs($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Complaints/Observations
                header("content-type:application/json");
                $slctdPosHldrsIDs = isset($_POST['slctdPosHldrsIDs']) ? cleanInputData($_POST['slctdPosHldrsIDs']) : "";
                $exitErrMsg = "";
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdPosHldrsIDs, "|~") != "") {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdPosHldrsIDs, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 9) {
                            $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_Type = cleanInputData1($crntRow[1]);
                            $ln_GroupID = (float) (cleanInputData1($crntRow[2]));
                            $ln_CourseID = (float) (cleanInputData1($crntRow[3]));
                            $ln_SbjctID = (float) (cleanInputData1($crntRow[4]));
                            $ln_PosID = (float) (cleanInputData1($crntRow[5]));
                            $ln_PosHldrID = (float) (cleanInputData1($crntRow[6]));
                            $ln_StrtDte = cleanInputData1($crntRow[7]);
                            if ($ln_StrtDte === "") {
                                $ln_StrtDte = $gnrlTrnsDteDMY;
                            }
                            $ln_EndDte = cleanInputData1($crntRow[8]);
                            if ($ln_EndDte === "") {
                                $ln_EndDte = "31-Dec-4000";
                            }
                            $errMsg = "";

                            if ($ln_Type === "" || $ln_PosID <= 0 || $ln_PosHldrID <= 0) {
                                $errMsg = "Row " . ($y + 1) . ":- Position and Position Holder are all required Fields!<br/>";
                            }
                            if ($errMsg === "") {
                                if ($ln_TrnsLnID <= 0) {
                                    $ln_TrnsLnID = getNew_AcaPosHldrsID();
                                    $afftctd += create_AcaPosHldrs($ln_TrnsLnID, $ln_PosHldrID, $ln_PosID, $ln_StrtDte, $ln_EndDte, $ln_GroupID, $ln_CourseID, $ln_SbjctID);
                                } else if ($ln_TrnsLnID > 0) {
                                    $afftctd += update_AcaPosHldrs($ln_TrnsLnID, $ln_PosHldrID, $ln_PosID, $ln_StrtDte, $ln_EndDte, $ln_GroupID, $ln_CourseID, $ln_SbjctID);
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
                    }
                }

                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Position Holder(s) Successfully Saved!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Position Holder(s) Successfully Saved!";
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
                                    <span style=\"text-decoration:none;\">Position Holders</span>
				</li>
                               </ul>
                              </div>";
                $error = "";
                
                $searchAll = true;
                
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn  = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'All';
                $pageNo  = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze  = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
                $sortBy  = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_Total_AcaPosHldrs($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_AcaPosHldrs($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-5";
                $colClassType3 = "col-md-5";
                ?> 
                <form id='acaPosHldrsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">ALL POSITION HOLDERS</legend>
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

                                $nwRowHtml31 = "<tr id=\"acaPosHldrsHdrsRow__WWW123WWW\">                                    
                                                <td class=\"lovtd\">New</td>
                                                <td class=\"lovtd\">
                                                        <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"acaPosHldrsHdrsRow_WWW123WWW_Type\" style=\"width:100% !important;\">";
                                $brghtStr = "";
                                $isDynmyc = FALSE;
                                $titleRslt = getLovValues("%", "Both", 0, 100,
                                        $brghtStr,
                                        getLovID("Divisions or Group Types"),
                                        $isDynmyc, -1, "", "");
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $selectedTxt = "";
                                    $nwRowHtml31 .= "<option value=\"" . $titleRow[0] . "\" " . $selectedTxt . ">" . $titleRow[0] . "</option>";
                                }
                                $nwRowHtml31 .= "</select>
                                                </td>    
                                                <td class=\"lovtd\">
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"acaPosHldrsHdrsRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                    <div class=\"input-group\" style=\"width:100%;\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"acaPosHldrsHdrsRow_WWW123WWW_GroupNm\" value=\"\">
                                                        <input type=\"hidden\" id=\"acaPosHldrsHdrsRow_WWW123WWW_GroupID\" value=\"-1\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Divisions/Groups', 'allOtherInputOrgID', 'acaPosHldrsHdrsRow_WWW123WWW_Type', '', 'radio', true, '', 'acaPosHldrsHdrsRow_WWW123WWW_GroupID', 'acaPosHldrsHdrsRow_WWW123WWW_GroupNm', 'clear', 1, '');\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>                                             
                                                </td> 
                                                <td class=\"lovtd\">
                                                    <div class=\"input-group\" style=\"width:100%;\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"acaPosHldrsHdrsRow_WWW123WWW_CourseNm\" value=\"\">
                                                        <input type=\"hidden\" id=\"acaPosHldrsHdrsRow_WWW123WWW_CourseID\" value=\"-1\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'ACA Courses', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaPosHldrsHdrsRow_WWW123WWW_CourseID', 'acaPosHldrsHdrsRow_WWW123WWW_CourseNm', 'clear', 1, '');\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>                                           
                                                </td>  
                                                <td class=\"lovtd\">
                                                    <div class=\"input-group\" style=\"width:100%;\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"acaPosHldrsHdrsRow_WWW123WWW_SbjctNm\" value=\"\" style=\"width:100%;\">
                                                        <input type=\"hidden\" id=\"acaPosHldrsHdrsRow_WWW123WWW_SbjctID\" value=\"-1\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'ACA Subjects', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaPosHldrsHdrsRow_WWW123WWW_SbjctID', 'acaPosHldrsHdrsRow_WWW123WWW_SbjctNm', 'clear', 1, '');\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>                                            
                                                </td>   
                                                <td class=\"lovtd\">
                                                    <div class=\"input-group\" style=\"width:100%;\">
                                                        <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"acaPosHldrsHdrsRow_WWW123WWW_PosNm\" value=\"\" style=\"width:100%;\">
                                                        <input type=\"hidden\" id=\"acaPosHldrsHdrsRow_WWW123WWW_PosID\" value=\"-1\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Positions', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaPosHldrsHdrsRow_WWW123WWW_PosID', 'acaPosHldrsHdrsRow_WWW123WWW_PosNm', 'clear', 1, '');\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>                                           
                                                </td>   
                                                <td class=\"lovtd\">
                                                    <div class=\"input-group\" style=\"width:100%;\">
                                                        <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"acaPosHldrsHdrsRow_WWW123WWW_PosHldrNm\" value=\"\" style=\"width:100%;\">
                                                        <input type=\"hidden\" id=\"acaPosHldrsHdrsRow_WWW123WWW_PosHldrID\" value=\"-1\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaPosHldrsHdrsRow_WWW123WWW_PosHldrID', 'acaPosHldrsHdrsRow_WWW123WWW_PosHldrNm', 'clear', 1, '');\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>                                          
                                                </td>
                                                <td class=\"lovtd\">
                                                    <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\" style=\"width:100%;\">
                                                        <input class=\"form-control\" size=\"16\" type=\"text\" id=\"acaPosHldrsHdrsRow_WWW123WWW_StrtDte\" name=\"acaPosHldrsHdrsRow_WWW123WWW_StrtDte\" value=\"\" readonly=\"\">
                                                         <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                        <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                    </div>                                                         
                                                </td>
                                                <td class=\"lovtd\">
                                                    <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\" style=\"width:100%;\">
                                                        <input class=\"form-control\" size=\"16\" type=\"text\" id=\"acaPosHldrsHdrsRow_WWW123WWW_EndDte\" name=\"acaPosHldrsHdrsRow_WWW123WWW_EndDte\" value=\"\" readonly=\"\">
                                                        <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                        <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                    </div>                                                       
                                                </td>";
                                if ($canDel === true) {
                                    $nwRowHtml31 .= "<td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Position Holder\" onclick=\"delAcaPosHldrs('acaPosHldrsHdrsRow__WWW123WWW');\" style=\"padding:2px !important;\" style=\"padding:2px !important;\">
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
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewAcaPosHldrsRows('acaPosHldrsHdrsTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add New Position Holder">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Position Holder
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAcaPosHldrsForm();" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        SAVE
                                    </button> 
                                </div>
                            <?php } ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="acaPosHldrsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAcaPosHldrs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                    <input id="acaPosHldrsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <input id="sbmtdScmRtrnSrcDocID" type = "hidden" value="-1">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaPosHldrs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcaPosHldrs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaPosHldrsSrchIn">
                                        <?php
                                        $valslctdArry = array("");
                                        $srchInsArrys = array("All");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaPosHldrsDsplySze" style="min-width:70px !important;">                            
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000, 1000000);
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
                                            <a href="javascript:getAcaPosHldrs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getAcaPosHldrs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div> 
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="acaPosHldrsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:25px;width:25px;">No.</th>
                                            <th style="max-width:85px;width:85px;">Group Type</th>
                                            <th style="max-width:125px;width:125px;">Group Name</th>
                                            <th style="max-width:125px;width:125px;">Course</th>
                                            <th style="max-width:125px;width:125px;">Subject</th>
                                            <th>Position Name</th>
                                            <th>Held By</th>
                                            <th style="max-width:108px;width:108px;">From Date</th>
                                            <th style="max-width:108px;width:108px;">To Date&nbsp;</th>
                                            <?php if ($canDel === true) { ?>
                                                <th style="max-width:20px;width:20px;">...</th>
                                            <?php } ?>
                                            <?php
                                            if ($canVwRcHstry === true) {
                                                ?>
                                                <th style="max-width:20px;width:20px;">...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            $trsctnLnID = (float) $row[0];
                                            $trsctnLnGrpID = (float) $row[8];
                                            $trsctnLnGrpType = $row[9];
                                            $trsctnLnGrpName = $row[10];
                                            $trsctnLnCourseID = (float) $row[11];
                                            $trsctnLnCourse = $row[12];
                                            $trsctnLnSbjctID = (float) $row[13];
                                            $trsctnLnSbjct = $row[14];
                                            $trsctnLnPosID = (float) $row[4];
                                            $trsctnLnPosNm = $row[5];
                                            $trsctnLnPosHldrID = (float) $row[1];
                                            $trsctnLnPosHldrNm = $row[2];
                                            $trsctnLnStrtDte = $row[6];
                                            $trsctnLnEndDte = $row[7];
                                            $cntr += 1;
                                            $statusColor = "#000000";
                                            $statusBckgrdColor = "";
                                            ?>
                                            <tr id="acaPosHldrsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>  
                                                <td class="lovtd">
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_Type" style="width:100% !important;">
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Divisions or Group Types"), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            if ($titleRow[0] == $trsctnLnGrpType) {
                                                                $selectedTxt = "selected";
                                                            }
                                                            ?>
                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>    
                                                <td class="lovtd">
                                                    <input type="hidden" class="form-control" aria-label="..." id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                    <?php
                                                    if ($canEdt === true) {
                                                        ?>
                                                        <div class="input-group" style="width:100%;">
                                                            <input type="text" class="form-control" aria-label="..." id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_GroupNm" value="<?php echo $trsctnLnGrpName; ?>">
                                                            <input type="hidden" id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_GroupID" value="<?php echo $trsctnLnGrpID; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Divisions/Groups', 'allOtherInputOrgID', 'acaPosHldrsHdrsRow<?php echo $cntr; ?>_Type', '', 'radio', true, '', 'acaPosHldrsHdrsRow<?php echo $cntr; ?>_GroupID', 'acaPosHldrsHdrsRow<?php echo $cntr; ?>_GroupNm', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnGrpName; ?></span>
                                                    <?php } ?>                                             
                                                </td> 
                                                <td class="lovtd">
                                                    <?php if ($canEdt === true) { ?>
                                                        <div class="input-group" style="width:100%;">
                                                            <input type="text" class="form-control" aria-label="..." id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_CourseNm" value="<?php echo $trsctnLnCourse; ?>">
                                                            <input type="hidden" id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_CourseID" value="<?php echo $trsctnLnCourseID; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'ACA Courses', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaPosHldrsHdrsRow<?php echo $cntr; ?>_CourseID', 'acaPosHldrsHdrsRow<?php echo $cntr; ?>_CourseNm', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnCourse; ?></span>
                                                    <?php } ?>                                            
                                                </td>  
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canEdt === true) {
                                                        ?>
                                                        <div class="input-group" style="width:100%;">
                                                            <input type="text" class="form-control" aria-label="..." id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_SbjctNm" value="<?php echo $trsctnLnSbjct; ?>" style="width:100%;">
                                                            <input type="hidden" id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_SbjctID" value="<?php echo $trsctnLnSbjctID; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'ACA Subjects', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaPosHldrsHdrsRow<?php echo $cntr; ?>_SbjctID', 'acaPosHldrsHdrsRow<?php echo $cntr; ?>_SbjctNm', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnSbjct; ?></span>
                                                    <?php } ?>                                            
                                                </td>   
                                                <td class="lovtd">
                                                    <?php if ($canEdt === true) { ?>
                                                        <div class="input-group" style="width:100%;">
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_PosNm" value="<?php echo $trsctnLnPosNm; ?>" style="width:100%;">
                                                            <input type="hidden" id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_PosID" value="<?php echo $trsctnLnPosID; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Positions', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaPosHldrsHdrsRow<?php echo $cntr; ?>_PosID', 'acaPosHldrsHdrsRow<?php echo $cntr; ?>_PosNm', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnPosNm; ?></span>
                                                    <?php } ?>                                            
                                                </td>   
                                                <td class="lovtd">
                                                    <?php if ($canEdt === true) { ?>
                                                        <div class="input-group" style="width:100%;">
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_PosHldrNm" value="<?php echo $trsctnLnPosHldrNm; ?>" style="width:100%;">
                                                            <input type="hidden" id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_PosHldrID" value="<?php echo $trsctnLnPosHldrID; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'acaPosHldrsHdrsRow<?php echo $cntr; ?>_PosHldrID', 'acaPosHldrsHdrsRow<?php echo $cntr; ?>_PosHldrNm', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnPosHldrNm; ?></span>
                                                    <?php } ?>                                            
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdt === true) { ?>
                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100%;">
                                                            <input class="form-control" size="16" type="text" id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_StrtDte" name="acaPosHldrsHdrsRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $trsctnLnStrtDte; ?>" readonly="">
                                                             <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>  
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnStrtDte; ?></span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdt === true) { ?>
                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100%;">
                                                            <input class="form-control" size="16" type="text" id="acaPosHldrsHdrsRow<?php echo $cntr; ?>_EndDte" name="acaPosHldrsHdrsRow<?php echo $cntr; ?>_EndDte" value="<?php echo $trsctnLnEndDte; ?>" readonly="">
                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div> 
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnEndDte; ?></span>
                                                    <?php } ?>                                                         
                                                </td> 
                                                <?php
                                                if ($canDel === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Position Holders" onclick="delAcaPosHldrs('acaPosHldrsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php
                                                if ($canVwRcHstry === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                echo urlencode(encrypt1(($row[0] . "|aca.aca_subjects|subject_id"), $smplTokenWord1));
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