<?php
$dateStr = getDB_Date_time();
$pkID = $PKeyID;

$prsnid = $_SESSION['PRSN_ID'];

//echo "HI";

$prsnJob = getPrsnJobNm($prsnid);

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        //echo $_POST['dataToSend'];
        //var_dump($_POST);

        if ($qstr == "DELETE") {
            //var_dump($_POST);
            $bkTypCodeInUse = isDiagnsListInActiveUse($PKeyID);
            //check loan status -> Incomplete, Rejected and Withdrawn CAN BE DELETED
            if ($bkTypCodeInUse) {
                echo "SORRY! Diagnosis is in use";
                exit();
            } else {
                $rowCnt = deleteCreditDiagnsLists($PKeyID);
                if ($rowCnt > 0) {
                    echo "Diagnosis Record Deleted Successfully";
                } else {
                    echo "Failed to Delete Diagnosis Record";
                }
                exit();
            }
        } else if ($qstr == "UPDATE") {
             $slctdDiagnsLists = isset($_POST['slctdDiagnsLists']) ? cleanInputData($_POST['slctdDiagnsLists']) : '';

            $dateStr = getDB_Date_time();
            $recCntInst = 0;
            $recCntUpdt = 0;

            if (trim($slctdDiagnsLists, "|~") != "") {
                
                $variousRows = explode("|", trim($slctdDiagnsLists, "|"));
                for ($z = 0; $z < count($variousRows); $z++) {
                    $crntRow = explode("~", $variousRows[$z]);
                    if (count($crntRow) == 6) { 
                        
                        $diseaseId = (int) (cleanInputData1($crntRow[0]));
                        $diagnsListNm  = cleanInputData1($crntRow[1]);
                        $diagnsListDesc = cleanInputData1($crntRow[2]);
                        $diagnsListSymtms = cleanInputData1($crntRow[3]);
                        $diagnsListPssblTrtmntMdctn = cleanInputData1($crntRow[4]);
                        $diagnsListIsEnabled = cleanInputData1($crntRow[5]);

                        if ($diseaseId > 0) {
                            $recCntUpdt = $recCntUpdt + updateCreditDiagnsLists($diseaseId, $diagnsListNm, $diagnsListDesc, $diagnsListSymtms, $diagnsListPssblTrtmntMdctn, $diagnsListIsEnabled, $dateStr);
                        } else {
                            $diseaseId = getDiagnsListID();
                            $recCntInst = $recCntInst + createCreditDiagnsLists($diseaseId, $diagnsListNm, $diagnsListDesc, $diagnsListSymtms, $diagnsListPssblTrtmntMdctn, $diagnsListIsEnabled, $dateStr);
                        }

                    }
                }

                echo "<span style='color:green;font-weight:bold !important;'><i>$recCntInst record(s) inserted</br>$recCntUpdt record(s) updated</i></span>";
                exit();
            } else {
                echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                . 'Please provide one Diagnosis Record before saving!<br/></div>';
                exit();
            }
        } else {
            if (1 == 1) {
                //var_dump($_POST);
                $canAddDiagnsList = test_prmssns($dfltPrvldgs[20], $mdlNm);
                $canEdtDiagnsList = test_prmssns($dfltPrvldgs[21], $mdlNm);
                $canDelDiagnsList = test_prmssns($dfltPrvldgs[22], $mdlNm);

                $error = "";
                $searchAll = true;
                $isEnabledOnly = false;
                if (isset($_POST['isEnabled'])) {
                    $isEnabledOnly = cleanInputData($_POST['isEnabled']);
                }


                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }

                if ($vwtyp == 0) {//3
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&mdl=Clinic/Hospital');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">List of Diagnosis</span>
				</li>
                               </ul>
                              </div>";

                    $total = getCreditDiagnsListsTblTtl($isEnabledOnly, $srchFor, $srchIn, $searchAll);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = getCreditDiagnsListsTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $searchAll, $sortBy);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-1";
                    ?>
                    <form id='allDiagnsListsForm' action='' method='post' accept-charset='UTF-8'>
                        <!--<fieldset class="basic_person_fs5">-->
                        <legend class="basic_person_lg1" style="color: #003245">SERVICE TYPES</legend>                
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <div class="row rhoRowMargin" style="margin-bottom:10px;">
                            <?php
                            if ($canAddDiagnsList === true) {
                                $nwRowHtml = urlencode("<tr id=\"allDiagnsListsRow__WWW123WWW\">"
                                                                . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                                . "<td class=\"lovtd\">
                                                            <input type=\"text\" style=\"width:100% !important;\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allDiagnsListsRow_WWW123WWW_DiagnsListNm\" name=\"allDiagnsListsRow_WWW123WWW_DiagnsListNm\" value=\"\">   
                                                            <input type=\"hidden\"  class=\"form-control\" aria-label=\"...\" id=\"allDiagnsListsRow_WWW123WWW_DiagnsListID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                                        </td>                                             
                                                        <td class=\"lovtd\">
                                                                <input type=\"text\" style=\"width:100% !important;\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allDiagnsListsRow_WWW123WWW_DiagnsListDesc\" name=\"allDiagnsListsRow_WWW123WWW_DiagnsListDesc\" value=\"\">                                                               
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                <textarea style=\"width:100% !important;\" class=\"form-control\" aria-label=\"...\" id=\"allDiagnsListsRow_WWW123WWW_DiagnsListSymtms\"></textarea>
                                                            </div>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                                <textarea style=\"width:100% !important;\" class=\"form-control\" aria-label=\"...\" id=\"allDiagnsListsRow_WWW123WWW_DiagnsListPssblTrtmntMdctn\" name=\"allDiagnsListsRow_WWW123WWW_DiagnsListPssblTrtmntMdctn\" ></textarea>                                                          
                                                        </td>
                                                        <td class=\"lovtd\">       
                                                            <select class=\"form-control\" aria-label=\"...\" id=\"allDiagnsListsRow_WWW123WWW_DiagnsListIsEnabled\" name=\"allDiagnsListsRow_WWW123WWW_DiagnsListIsEnabled\">
                                                                <option value=\"1\" selected>Yes</option>
                                                                <option value=\"0\" >No</option>														
                                                            </select>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delDiagnsList('allDiagnsListsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Service\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                    </tr>");
                                ?>   
                                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;"> 
                                            <?php if ($canAddDiagnsList === true) { ?>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allDiagnsListsTable', 0, '<?php echo $nwRowHtml; ?>');">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                New Diagnosis
                                            </button>
                                            <?php } ?>
                                        </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-2";
                                $colClassType2 = "col-lg-3";
                                $colClassType3 = "col-lg-1";
                            }
                            ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="allDiagnsListsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllDiagnsLists(event, '', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital')">
                                    <input id="allDiagnsListsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllDiagnsLists('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllDiagnsLists('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allDiagnsListsSrchIn">
                    <?php
                    $valslctdArry = array("", "", "", "");
                    $srchInsArrys = array("Diagnosis Description", "Diagnosis Name", "Symptoms", "Treatment Medications");
                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                        if ($srchIn == $srchInsArrys[$z]) {
                            $valslctdArry[$z] = "selected";
                        }
                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                    <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allDiagnsListsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="<?php echo $colClassType3; ?>" style="padding: 5px 1px 0px 15px !important">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                    <?php
                    $nonAprvdChekd = "";
                    if ($isEnabledOnly == "true") {
                        $nonAprvdChekd = "checked=\"true\"";
                    }
                    ?>
                                        <input type="checkbox" class="form-check-input" onclick="getAllDiagnsLists('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');" id="allDiagnsListsIsEnabled" name="allDiagnsListsIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                        Enabled?
                                    </label>
                                </div>                             
                            </div>
                            <div class="<?php echo $colClassType3; ?>" style="width:10% !important;">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getAllDiagnsLists('previous', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getAllDiagnsLists('next', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>  
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 3px !important;"> 
                                <div style="float:right !important;">
                                    <?php if ($canAddDiagnsList === true) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveDiagnsList();" data-toggle="tooltip" data-placement="bottom" title="Save DiagnsList">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save Diagnosis
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>               
                        <div class="row" style="padding:0px 15px 0px 15px !important">                        
                            <div  class="col-md-12" style="padding:0px 1px 0px 1px !important">
                                <fieldset class="basic_person_fs" style="padding-top:5px !important;">
                                    <div class="" id="allDiagnsListsDetailInfo">
                                        <div class="row" id="allDiagnsListsDetailInfo" style="padding:0px 15px 0px 15px !important">
                                                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                                        <table class="table table-striped table-bordered table-responsive" id="allDiagnsListsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Diagnosis</th>
                                                                    <th>Description</th>
                                                                    <th>Symptoms</th>
                                                                    <th>Treatment Medications</th>
                                                                    <th>Is Enabled</th>
                                                                    <th>&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <?php
                                                            if ($total > 0) {
                                                                ?>
                                                            <tbody>
                                                                <?php
                                                                $cntr = 0;
                                                                while ($row2 = loc_db_fetch_array($result)) {
                                                                    $cntr += 1;
                                                                    $ttlOptnsScore = 0;
                                                                    ?>
                                                                    <tr id="allDiagnsListsRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                        <td class="lovtd">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="allDiagnsListsRow<?php echo $cntr; ?>_DiagnsListID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                                            <input type="text" style="width:100% !important;" class="form-control rqrdFld" aria-label="..." id="allDiagnsListsRow<?php echo $cntr; ?>_DiagnsListNm" name="allDiagnsListsRow<?php echo $cntr; ?>_DiagnsListNm" value="<?php echo $row2[1]; ?>">                                                               
                                                                        </td>                                             
                                                                        <td class="lovtd"> 
                                                                            <input type="text" style="width:100% !important;" class="form-control rqrdFld" aria-label="..." id="allDiagnsListsRow<?php echo $cntr; ?>_DiagnsListDesc" name="allDiagnsListsRow<?php echo $cntr; ?>_DiagnsListDesc" value="<?php echo $row2[2]; ?>">
                                                                        </td>
                                                                        <td class="lovtd"> 
                                                                            <div class="input-group"  style="width:100%;">
                                                                                <textarea style="width:100% !important;" class="form-control" aria-label="..." id="allDiagnsListsRow<?php echo $cntr; ?>_DiagnsListSymtms" name="allDiagnsListsRow<?php echo $cntr; ?>_DiagnsListSymtms"><?php echo $row2[3]; ?></textarea>
                                                                            </div>
                                                                        </td>
                                                                        <td class="lovtd"> 
                                                                            <textarea style="width:100% !important;" class="form-control rqrdFld" aria-label="..."  id="allDiagnsListsRow<?php echo $cntr; ?>_DiagnsListPssblTrtmntMdctn" name="allDiagnsListsRow<?php echo $cntr; ?>_DiagnsListPssblTrtmntMdctn"><?php echo $row2[4]; ?></textarea>
                                                                        </td>
                                                                        <td class="lovtd">  
                                                                            <select class="form-control" aria-label="..." id="allDiagnsListsRow<?php echo $cntr; ?>_DiagnsListIsEnabled" name="allDiagnsListsRow<?php echo $cntr; ?>_DiagnsListIsEnabled">
                                                                                <?php
                                                                                $sltdYes = "";
                                                                                $sltdNo = "";
                                                                                if ($row2[5] == "1") {
                                                                                    $sltdYes = "selected";
                                                                                } else if ($row2[5] == "0") {
                                                                                    $sltdNo = "selected";
                                                                                }
                                                                                ?>
                                                                                <option value="1" <?php echo $sltdYes; ?>>Yes</option>
                                                                                <option value="0" <?php echo $sltdNo; ?>>No</option>    
                                                                            </select>		
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php if ($canDelDiagnsList === true) { ?>
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delDiagnsList('allDiagnsListsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Service">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                            </tbody>
                                                            <?php
                                                            } else {
                                                                ?>
                                                                <tfoot>
                                                                    <span>No Results Found</span>
                                                                </tfoot>
                                                                <?php
                                                            }
                                                            ?>
                                                        </table>                        
                                                    </div>                
                                                </div>              
                                        </div>                                   
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <!--</fieldset>-->
                    </form>
                    <?php
                }
            }
        }
    }
}