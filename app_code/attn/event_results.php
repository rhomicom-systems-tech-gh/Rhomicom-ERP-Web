<?php
$canAdd = test_prmssns($dfltPrvldgs[20], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[21], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[22], $mdlNm);
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Doc Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteActvtyRslt1($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Activity/Event Result
                header("content-type:application/json");
                $sbmtdAttnEventID = isset($_POST['sbmtdAttnEventID']) ? (float) cleanInputData($_POST['sbmtdAttnEventID']) : -1;
                $sbmtdRegisterID = isset($_POST['sbmtdRegisterID']) ? (float) cleanInputData($_POST['sbmtdRegisterID']) : -1;
                $slctdAttnResultIDs = isset($_POST['slctdAttnResultIDs']) ? cleanInputData($_POST['slctdAttnResultIDs']) : "";
                $exitErrMsg = "";
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdAttnResultIDs, "|~") != "") {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdAttnResultIDs, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 10) {
                            $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_EventID = (float) cleanInputData1($crntRow[1]);
                            if ($ln_EventID <= 0) {
                                $ln_EventID = $sbmtdAttnEventID;
                            }
                            $ln_MetricID = (float) cleanInputData1($crntRow[2]);
                            $ln_RgstrID = (float) cleanInputData1($crntRow[3]);
                            if ($ln_RgstrID <= 0) {
                                $ln_RgstrID = $sbmtdRegisterID;
                            }
                            $ln_MtrcNm = cleanInputData1($crntRow[4]);
                            $ln_Result = cleanInputData1($crntRow[5]);
                            $ln_StrtDte = cleanInputData1($crntRow[6]);
                            $ln_EndDte = cleanInputData1($crntRow[7]);
                            $ln_LineDesc = cleanInputData1($crntRow[8]);
                            $ln_AutoCalc = (cleanInputData1($crntRow[9]) == "YES") ? TRUE : FALSE;
                            if (trim($ln_StrtDte) === "" && trim($ln_EndDte) === "") {
                                $ln_StrtDte = $gnrlTrnsDteDMYHMS;
                                $ln_EndDte = $ln_StrtDte;
                            } else if (trim($ln_StrtDte) === "" && trim($ln_EndDte) != "") {
                                $ln_StrtDte = $ln_EndDte;
                            } else if (trim($ln_StrtDte) != "" && trim($ln_EndDte) === "") {
                                $ln_EndDte = $ln_StrtDte;
                            }
                            $errMsg = "";
                            if ($ln_MtrcNm === "" || $ln_Result === "") {
                                $errMsg = "Row " . ($y + 1) . ":- Activity/Event Result Name and Description are all required Fields!<br/>";
                            }
                            if ($errMsg === "") {
                                $ln_StrtDte = cnvrtAllToDMYTm($ln_StrtDte);
                                $ln_EndDte = cnvrtAllToDMYTm($ln_EndDte);
                                if ($ln_TrnsLnID <= 0) {
                                    $ln_TrnsLnID = getNewRsltLnID();
                                    $afftctd += createActvtyRslt1($ln_TrnsLnID, $ln_EventID, $ln_MetricID, $ln_LineDesc, $ln_Result, $ln_StrtDte, $ln_EndDte, $ln_AutoCalc, $ln_RgstrID, $ln_MtrcNm);
                                } else if ($ln_TrnsLnID > 0) {
                                    $afftctd += updateActvtyRslt1($ln_TrnsLnID, $ln_EventID, $ln_MetricID, $ln_LineDesc, $ln_Result, $ln_StrtDte, $ln_EndDte, $ln_AutoCalc, $ln_RgstrID, $ln_MtrcNm);
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
                    }
                }

                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Activity/Event Result(s) Successfully Saved!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Activity/Event Result(s) Successfully Saved!";
                }
                $arr_content['percent'] = 100;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            }
        } else {
            if ($vwtyp == 0) {
                //Search for Transactions
                $sbmtdAttnEventID = isset($_POST['sbmtdAttnEventID']) ? (float) cleanInputData($_POST['sbmtdAttnEventID']) : -1;
                $sbmtdRegisterID = isset($_POST['sbmtdRegisterID']) ? (float) cleanInputData($_POST['sbmtdRegisterID']) : -1;
                $sbmtdPasteArea = "allmodules";
                $sbmtdReadOnly = isset($_POST['sbmtdReadOnly']) ? cleanInputData($_POST['sbmtdReadOnly']) : "NO";
                $showAutoCalc = "";
                if ($sbmtdAttnEventID <= 0 && $sbmtdRegisterID <= 0) {
                    $showAutoCalc = "display:none;";
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Activities/Event Results</span>
                            </li>
                           </ul>
                          </div>";
                } else {
                    $sbmtdPasteArea = "myFormsModalBodyLg";
                }
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'All';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 50;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date (DESC)";
                $qShwUnpstdOnly = true;
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                if ($qShwUnpstdOnly == true) {
                    $canEdt = false;
                }
                $qShwPstdOnly = true;
                if (isset($_POST['qShwPstdOnly'])) {
                    $qShwPstdOnly = cleanInputData($_POST['qShwPstdOnly']) === "true" ? true : false;
                }
                $qShwIntrfc = false;
                if (isset($_POST['qShwIntrfc'])) {
                    $qShwIntrfc = cleanInputData($_POST['qShwIntrfc']) === "true" ? true : false;
                }
                $qLowVal = 0;
                $qHighVal = 0;
                $date = new DateTime($gnrlTrnsDteYMD);
                $date->modify('-24 month');
                $qStrtDte = $date->format('d-M-Y') . " 00:00:00";
                $date = new DateTime($gnrlTrnsDteYMD);
                $date->modify('+24 month');
                $qEndDte = $date->format('d-M-Y') . " 23:59:59";
                /* $qStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 00:00:00";
                  $qEndDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 23:59:59"; */
                if (isset($_POST['qLowVal'])) {
                    $qLowVal = (float) cleanInputData($_POST['qLowVal']);
                }
                if (isset($_POST['qHighVal'])) {
                    $qHighVal = (float) cleanInputData($_POST['qHighVal']);
                }
                if (isset($_POST['qStrtDte'])) {
                    $qStrtDte = cleanInputData($_POST['qStrtDte']);
                    if (strlen($qStrtDte) == 11) {
                        $qStrtDte = substr($qStrtDte, 0, 11) . " 00:00:00";
                    } else {
                        $qStrtDte = "01-Jan-1900 00:00:00";
                    }
                }
                if (isset($_POST['qEndDte'])) {
                    $qEndDte = cleanInputData($_POST['qEndDte']);
                    if (strlen($qEndDte) == 11) {
                        $qEndDte = substr($qEndDte, 0, 11) . " 23:59:59";
                    } else {
                        $qEndDte = "31-Dec-4000 23:59:59";
                    }
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Total_ActvtyRslts1($srchFor, $srchIn, $sbmtdAttnEventID, $sbmtdRegisterID, $qStrtDte, $qEndDte);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_One_ActvtyRslts1($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdAttnEventID, $sbmtdRegisterID, $qStrtDte,
                            $qEndDte, $sortBy);
                    /* $total = get_Total_AttnRgstr_SrchLns($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte);
                      if ($pageNo > ceil($total / $lmtSze)) {
                      $pageNo = 1;
                      } else if ($pageNo < 1) {
                      $pageNo = ceil($total / $lmtSze);
                      }
                      $curIdx = $pageNo - 1;
                      $result = get_AttnRgstr_SrchLns($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte, $sortBy); */
                    $cntr = 0;
                    ?> 
                    <form id='attnResultsForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <fieldset class="">
                            <?php if ($sbmtdAttnEventID <= 0 && $sbmtdRegisterID <= 0) { ?>
                                <legend class="basic_person_lg1" style="color: #003245">ALL EVENT ACTIVITIES/RESULTS</legend>
                            <?php } ?>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                $mailTo = "";
                                $brghtStr = "";
                                $isDynmyc = FALSE;
                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Default Mail Recipients"), $isDynmyc, -1, "", "");
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $mailTo .= $titleRow[0] . ";";
                                }
                                if ($mailTo == "") {
                                    $mailTo = $admin_email;
                                } else {
                                    $mailTo = trim(cleanInputData($mailTo), ";, ");
                                }
                                if ($canAdd === true) {
                                    $colClassType1 = "col-md-2";
                                    $colClassType2 = "col-md-4";
                                    $colClassType3 = "col-md-6";

                                    $nwRowHtml31 = "<tr id=\"attnResultsHdrsRow__WWW123WWW\">                                    
                                                        <td class=\"lovtd\">New</td>    
                                                        <td class=\"lovtd\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attnResultsHdrsRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attnResultsHdrsRow_WWW123WWW_EventID\" value=\"" . $sbmtdAttnEventID . "\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attnResultsHdrsRow_WWW123WWW_MetricID\" value=\"\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attnResultsHdrsRow_WWW123WWW_RgstrID\" value=\"" . $sbmtdRegisterID . "\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attnResultsHdrsRow_WWW123WWW_Mail\" value=\"" . urlencode($mailTo) . "\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attnResultsHdrsRow_WWW123WWW_CCMail\" value=\"" . urlencode($admin_email) . "\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attnResultsHdrsRow_WWW123WWW_Sbjct\" value=\"\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attnResultsHdrsRow_WWW123WWW_Body\" value=\"\" style=\"width:100% !important;\"> 
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attnResultsHdrsRow_WWW123WWW_Attchmnts\" value=\"\" style=\"width:100% !important;\"> 
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <textarea class=\"form-control\" aria-label=\"...\" id=\"attnResultsHdrsRow_WWW123WWW_MtrcNm\" name=\"attnResultsHdrsRow_WWW123WWW_MtrcNm\" style=\"width:100%;resize:vertical;\" cols=\"7\" rows=\"1\"></textarea>
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"popUpDisplayHtml('attnResultsHdrsRow_WWW123WWW_MtrcNm');\" style=\"max-width:30px;width:30px;\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>                                            
                                                        </td>   
                                                        <td class=\"lovtd\">
                                                            
                                                            <input  class=\"form-control\" size=\"16\" list=\"attnResultsHdrsRow_WWW123WWW_ResultOptns\" id=\"attnResultsHdrsRow_WWW123WWW_Result\" name=\"attnResultsHdrsRow_WWW123WWW_Result\" value=\"\" style=\"width:100% !important;\">
                                                            <datalist id=\"attnResultsHdrsRow_WWW123WWW_ResultOptns\">";
                                    $valslctdArry = array("", "", "", "", "", "");
                                    $srchInsArrys = array("Done", "Completed", "In Progress", "Processing", "Failed", "Incomplete");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        $nwRowHtml31 .= "<option value=\"" . $srchInsArrys[$z] . "\">";
                                    }
                                    $nwRowHtml31 .= "</datalist>                
                                                        </td>                                           
                                                        <td class=\"lovtd\" style=\"text-align:center;" . $showAutoCalc . "\">
                                                            <div class=\"form-group form-group-sm\">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"attnResultsHdrsRow_WWW123WWW_AutoCalc\" name=\"attnResultsHdrsRow_WWW123WWW_AutoCalc\">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>                                              
                                                        <td class=\"lovtd\"  style=\"\">
                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100% !important;\">
                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"attnResultsHdrsRow_WWW123WWW_StrtDte\" value=\"\">
                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>
                                                        </td> 
                                                        <td class=\"lovtd\" style=\"text-align: right;\">
                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100% !important;\">
                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"attnResultsHdrsRow_WWW123WWW_EndDte\" value=\"\">
                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div> 
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <textarea class=\"form-control\" aria-label=\"...\" id=\"attnResultsHdrsRow_WWW123WWW_LineDesc\" name=\"attnResultsHdrsRow_WWW123WWW_LineDesc\" style=\"width:100%;resize:vertical;\" cols=\"7\" rows=\"1\"></textarea>
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"popUpDisplayHtml('attnResultsHdrsRow_WWW123WWW_LineDesc');\" style=\"max-width:30px;width:30px;\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>";
                                    if ($canDel === true) {
                                        $nwRowHtml31 .= "<td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Activity/Event Result\" onclick=\"delAttnResults('attnResultsHdrsRow__WWW123WWW');\" style=\"padding:2px !important;\" style=\"padding:2px !important;\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">
                                                        </button>
                                                    </td>";
                                    }
                                    if ($canVwRcHstry === true) {
                                        $nwRowHtml31 .= "<td class=\"lovtd\">&nbsp;</td>";
                                    }
                                    $nwRowHtml31 .= "<td class=\"lovtd\">&nbsp;</td>";
                                    $nwRowHtml31 .= "</tr>";
                                    $nwRowHtml33 = urlencode($nwRowHtml31);
                                    ?>   
                                    <div class="col-md-1" style="padding:0px 1px 0px 15px !important;">                      
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewAttnResultsRows('attnResultsHdrsTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add New Activity/Event Result">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%;height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAttnResultsForm(<?php echo $sbmtdAttnEventID; ?>, <?php echo $sbmtdRegisterID; ?>);" data-toggle="tooltip" data-placement="bottom" title="Save Activity/Event Result">
                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%;height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button> 
                                    </div>
                                <?php } ?>                                
                                <div class="col-md-5" style="padding:0px 1px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="attnResultsSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncAttnResults(event, '', '#<?php echo $sbmtdPasteArea; ?>', 'grp=16&typ=1&pg=9&vtyp=0&sbmtdAttnEventID=<?php echo $sbmtdAttnEventID; ?>&sbmtdRegisterID=<?php echo $sbmtdRegisterID; ?>')">
                                        <input id="attnResultsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnResults('clear', '#<?php echo $sbmtdPasteArea; ?>', 'grp=16&typ=1&pg=9&vtyp=0&sbmtdAttnEventID=<?php echo $sbmtdAttnEventID; ?>&sbmtdRegisterID=<?php echo $sbmtdRegisterID; ?>');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnResults('', '#<?php echo $sbmtdPasteArea; ?>', 'grp=16&typ=1&pg=9&vtyp=0&sbmtdAttnEventID=<?php echo $sbmtdAttnEventID; ?>&sbmtdRegisterID=<?php echo $sbmtdRegisterID; ?>');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="attnResultsSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "");
                                            $srchInsArrys = array("All",
                                                "Metric Name", "Start Date",
                                                "End Date", "Event Result",
                                                "Comment");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="attnResultsDsplySze" style="min-width:70px !important;">                           
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
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="attnResultsSortBy" style="width:100% !important;">
                                            <?php
                                            $valslctdArry = array("", "");
                                            $srchInsArrys = array("Date (DESC)", "Date (ASC)");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($sortBy == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1" style="padding:0px 1px 0px 1px !important;">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" style="margin: 0px !important;">
                                            <li>
                                                <a href="javascript:getAttnResults('previous', '#<?php echo $sbmtdPasteArea; ?>', 'grp=16&typ=1&pg=9&vtyp=0&sbmtdAttnEventID=<?php echo $sbmtdAttnEventID; ?>&sbmtdRegisterID=<?php echo $sbmtdRegisterID; ?>');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getAttnResults('next', '#<?php echo $sbmtdPasteArea; ?>', 'grp=16&typ=1&pg=9&vtyp=0&sbmtdAttnEventID=<?php echo $sbmtdAttnEventID; ?>&sbmtdRegisterID=<?php echo $sbmtdRegisterID; ?>');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="col-md-5" style="padding:0px 1px 0px 1px !important;">
                                    <div class="col-xs-5" style="padding:0px 1px 0px 0px !important;">
                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" size="16" type="text" id="attnResultsStrtDate" name="attnResultsStrtDate" value="<?php
                                            echo substr($qStrtDte, 0, 11);
                                            ?>" placeholder="Start Date">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-5" style="padding:0px 1px 0px 0px !important;">
                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" size="16" type="text"  id="attnResultsEndDate" name="attnResultsEndDate" value="<?php
                                            echo substr($qEndDte, 0, 11);
                                            ?>" placeholder="End Date">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div> 
                                    <div class="col-xs-1" style="padding:0px 1px 0px 1px !important;">
                                        <button type="button" class="btn btn-default" onclick="funcHtmlToExcel('attnResultsHdrsTable');" style="">
                                            <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                    </div>   
                                    <div class="col-xs-1" style="padding:0px 1px 0px 0px !important;">
                                        <div class = "form-check" style = "font-size: 12px !important;">
                                            <label class = "form-check-label">
                                                <?php
                                                $shwUnpstdOnlyChkd = "";
                                                if ($qShwUnpstdOnly == true) {
                                                    $shwUnpstdOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getAttnResults('', '#<?php echo $sbmtdPasteArea; ?>', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAttnEventID=<?php echo $sbmtdAttnEventID; ?>&sbmtdRegisterID=<?php echo $sbmtdRegisterID; ?>');" id="attnResultsReadOnly" name="attnResultsReadOnly"  <?php echo $shwUnpstdOnlyChkd; ?>> View
                                            </label>
                                        </div>     
                                    </div>                           
                                </div>
                            </div> 
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="attnResultsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:35px;width:35px;">No.</th>
                                                <th style="max-width:220px;width:220px;">Activity/Event Result Name/Description</th>
                                                <th style="max-width:170px;width:170px;">Activity/Event Result</th>
                                                <th style="max-width:55px;width:55px;text-align: center;<?php echo $showAutoCalc; ?>">Auto-Calc?</th>
                                                <th style="max-width:120px;width:120px;">From Date</th>
                                                <th style="max-width:120px;width:120px;">To Date</th>
                                                <th>Comment/Remark</th>
                                                <?php if ($canDel === true) { ?>
                                                    <th style="max-width:30px;width:30px;">...</th>
                                                <?php } ?>
                                                <?php
                                                if ($canVwRcHstry === true) {
                                                    ?>
                                                    <th style="max-width:30px;width:30px;">...</th>
                                                <?php } ?>
                                                <th style="max-width:30px;width:30px;">...</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = loc_db_fetch_array($result)) {
                                                $trsctnLnID = (float) $row[0];
                                                $trsctnLnEventID = (float) $row[1];
                                                $trsctnLnMetricID = (float) $row[2];
                                                $trsctnLnMtrcNm = $row[3];
                                                $trsctnLnResult = $row[4];
                                                $trsctnLnStrtDte = $row[5];
                                                $trsctnLnEndDte = $row[6];
                                                $trsctnLnCmmnt = $row[7];
                                                $trsctnLnAutoCalc = $row[8];
                                                $trsctnLnRgstrID = (float) $row[9];
                                                $mailSubject = $app_cstmr . " Activity/Event on " . $trsctnLnStrtDte;
                                                $bulkMessageBody = "<span style=\"color:blue;font-weight:bold;text-decoration:underline;font-style:italic;\">Activity/Event:</span><br/>"
                                                        . $trsctnLnMtrcNm
                                                        . "<br/><br/><span style=\"color:blue;font-weight:bold;text-decoration:underline;font-style:italic;\">Result:</span><br/>"
                                                        . $trsctnLnResult
                                                        . "<br/><br/><span style=\"color:blue;font-weight:bold;text-decoration:underline;font-style:italic;\">Details:</span><br/>"
                                                        . $trsctnLnCmmnt;
                                                $cntr += 1;
                                                ?>
                                                <tr id="attnResultsHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                    <td class="lovtd">
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnResultsHdrsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnResultsHdrsRow<?php echo $cntr; ?>_EventID" value="<?php echo $trsctnLnEventID; ?>" style="width:100% !important;"> 
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnResultsHdrsRow<?php echo $cntr; ?>_MetricID" value="<?php echo $trsctnLnMetricID; ?>" style="width:100% !important;"> 
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnResultsHdrsRow<?php echo $cntr; ?>_RgstrID" value="<?php echo $trsctnLnRgstrID; ?>" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnResultsHdrsRow<?php echo $cntr; ?>_Mail" value="<?php echo urlencode($mailTo); ?>" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnResultsHdrsRow<?php echo $cntr; ?>_CCMail" value="<?php echo urlencode($admin_email); ?>" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnResultsHdrsRow<?php echo $cntr; ?>_Sbjct" value="<?php echo urlencode($mailSubject); ?>" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnResultsHdrsRow<?php echo $cntr; ?>_Body" value="<?php echo urlencode($bulkMessageBody); ?>" style="width:100% !important;"> 
                                                        <input type="hidden" class="form-control" aria-label="..." id="attnResultsHdrsRow<?php echo $cntr; ?>_Attchmnts" value="" style="width:100% !important;"> 
                                                        <?php
                                                        if ($canEdt === true) {
                                                            ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <div class="input-group"  style="width:100%;">
                                                                    <textarea class="form-control" aria-label="..." id="attnResultsHdrsRow<?php echo $cntr; ?>_MtrcNm" name="attnResultsHdrsRow<?php echo $cntr; ?>_MtrcNm" style="width:100%;resize:vertical;" cols="7" rows="1"><?php echo $trsctnLnMtrcNm; ?></textarea>
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplayHtml('attnResultsHdrsRow<?php echo $cntr; ?>_MtrcNm');" style="max-width:30px;width:30px;">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $trsctnLnMtrcNm; ?></span>
                                                        <?php } ?>                                             
                                                    </td>   
                                                    <td class="lovtd">
                                                        <?php
                                                        if ($canEdt === true) {
                                                            ?>
                                                            <input  class="form-control" size="16" list="attnResultsHdrsRow<?php echo $cntr; ?>_ResultOptns" id="attnResultsHdrsRow<?php echo $cntr; ?>_Result" name="attnResultsHdrsRow<?php echo $cntr; ?>_Result" value="<?php echo $trsctnLnResult; ?>" style="width:100% !important;">
                                                            <datalist id="attnResultsHdrsRow<?php echo $cntr; ?>_ResultOptns">
                                                                <?php
                                                                $valslctdArry = array("", "", "", "", "", "");
                                                                $srchInsArrys = array("Done", "Completed", "In Progress", "Processing", "Failed", "Incomplete");
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($trsctnLnResult == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                            </datalist>
                                                        <?php } else { ?>
                                                            <span><?php echo $trsctnLnResult; ?></span>
                                                        <?php } ?>                                             
                                                    </td>                                           
                                                    <td class="lovtd" style="text-align:center;<?php echo $showAutoCalc; ?>">
                                                        <?php
                                                        $isChkd = "";
                                                        if ($trsctnLnAutoCalc == "1") {
                                                            $isChkd = "checked=\"true\"";
                                                        }
                                                        ?>
                                                        <div class="form-group form-group-sm">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="attnResultsHdrsRow<?php echo $cntr; ?>_AutoCalc" name="attnResultsHdrsRow<?php echo $cntr; ?>_AutoCalc" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>                                              
                                                    <td class="lovtd"  style="">  
                                                        <?php
                                                        if ($canEdt === true) {
                                                            ?>
                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                <input class="form-control" size="16" type="text" id="attnResultsHdrsRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $trsctnLnStrtDte; ?>">
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
                                                                <input class="form-control" size="16" type="text" id="attnResultsHdrsRow<?php echo $cntr; ?>_EndDte" value="<?php echo $trsctnLnEndDte; ?>">
                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div> 
                                                        <?php } else { ?>
                                                            <span><?php echo $trsctnLnEndDte; ?></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdt === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <div class="input-group"  style="width:100%;">
                                                                    <textarea class="form-control" aria-label="..." id="attnResultsHdrsRow<?php echo $cntr; ?>_LineDesc" name="attnResultsHdrsRow<?php echo $cntr; ?>_LineDesc" style="width:100%;resize:vertical;" cols="7" rows="1"><?php echo $trsctnLnCmmnt; ?></textarea>
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplayHtml('attnResultsHdrsRow<?php echo $cntr; ?>_LineDesc');" style="max-width:30px;width:30px;">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $trsctnLnCmmnt; ?></span>
                                                        <?php } ?>
                                                    </td>
                                                    <?php
                                                    if ($canDel === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Activity/Event Result" onclick="delAttnResults('attnResultsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|attn.attn_attendance_events_rslts|evnt_rslt_id"),
                                                                            $smplTokenWord1));
                                                            ?>');" style="padding:2px !important;">
                                                                <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Send Email" onclick="sendGeneralMessage('Email', 'attnResultsHdrsRow<?php echo $cntr; ?>_Mail', 'attnResultsHdrsRow<?php echo $cntr; ?>_CCMail', 'attnResultsHdrsRow<?php echo $cntr; ?>_Sbjct', 'attnResultsHdrsRow<?php echo $cntr; ?>_Body', 'attnResultsHdrsRow<?php echo $cntr; ?>_Attchmnts');" style="padding:2px !important;">
                                                            <img src="cmn_images/Mail.png" style="height:20px; width:auto; position: relative; vertical-align: middle;padding-right: 3px;">
                                                        </button>
                                                    </td>
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
                }
            } else if ($vwtyp == 1) {
                
            } else if ($vwtyp == 2) {
                
            }
        }
    }
}
?>