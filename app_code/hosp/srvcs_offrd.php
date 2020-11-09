<?php
$dateStr = getDB_Date_time();
$pkID = $PKeyID;

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
//echo "HI";

$prsnJob = getPrsnJobNm($prsnid);

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        //echo $_POST['dataToSend'];
        //var_dump($_POST);

        if ($qstr == "DELETE") {
            //var_dump($_POST);
            $bkTypCodeInUse = isSrvcOffrdInActiveUse($PKeyID);
            //check loan status -> Incomplete, Rejected and Withdrawn CAN BE DELETED
            if ($bkTypCodeInUse) {
                echo "SORRY! Service Type is in use";
                exit();
            } else {
                $rowCnt = deleteCreditSrvcOffrds($PKeyID);
                if ($rowCnt > 0) {
                    echo "Service Type Record Deleted Successfully";
                } else {
                    echo "Failed to Delete Service Type Record";
                }
                exit();
            }
        } else if ($qstr == "UPDATE") {
             $slctdSrvcOffrds = isset($_POST['slctdSrvcOffrds']) ? cleanInputData($_POST['slctdSrvcOffrds']) : '';

            $dateStr = getDB_Date_time();
            $recCntInst = 0;
            $recCntUpdt = 0;
            $errCnt = 0;
            $errMsgVLDTN = "";
            $chkRtrnType = true;
            $chkRtrnNm = true;
            $cnta = 0;

            if (trim($slctdSrvcOffrds, "|~") != "") {
                
                $variousRows = explode("|", trim($slctdSrvcOffrds, "|"));
                
                $variousRowsVLDTN = $variousRows;
                for ($y = 0; $y < count($variousRowsVLDTN); $y++) {
                    $crntRowVLDTN = explode("~", $variousRowsVLDTN[$y]);
                    if (count($crntRowVLDTN) == 7) { 
                        
                        $typeIdVLDTN = (int) (cleanInputData1($crntRowVLDTN[0]));
                        $srvcOffrdNmVLDTN  = cleanInputData1($crntRowVLDTN[1]);
                        $srvcOffrdDescVLDTN = cleanInputData1($crntRowVLDTN[2]);
                        $srvcOffrdItmIDVLDTN = (int) cleanInputData1($crntRowVLDTN[3]);
                        $srvcOffrdSysVLDTN = cleanInputData1($crntRowVLDTN[4]);
                        $srvcOffrdIsEnabledVLDTN = cleanInputData1($crntRowVLDTN[5]);
                        $teleEnabledVLDTN = cleanInputData1($crntRowVLDTN[6]);                        
                                               
                        $chkRtrnType = checkExistenceOfServiceType($typeIdVLDTN, $srvcOffrdSysVLDTN);
                        if($chkRtrnType){
                            $chkRtrnNm = checkExistenceOfServiceNm($typeIdVLDTN, $srvcOffrdNmVLDTN);
                            if($chkRtrnNm){
                                $errMsgVLDTN = $errMsgVLDTN.((int)$errCnt+1).". <span style='color:blue'>Service Name </span> ".$srvcOffrdNmVLDTN." <span style='color:blue'> & System Code</span> ".$srvcOffrdSysVLDTN." <span style='color:blue'>exit!</span><br/>";
                            } else {
                                $errMsgVLDTN = $errMsgVLDTN.((int)$errCnt+1).". <span style='color:blue'>System Code</span> ".$srvcOffrdSysVLDTN." <span style='color:blue'>exits!</span><br/>";
                            } 
                            $errCnt = $errCnt + 1;
                        } else {                        
                            $chkRtrnNm = checkExistenceOfServiceNm($typeIdVLDTN, $srvcOffrdNmVLDTN);
                            if($chkRtrnNm){
                                $errCnt = $errCnt + 1;
                                $errMsgVLDTN = $errMsgVLDTN.((int)$errCnt+1).". <span style='color:blue'>Service Name</span> ".$srvcOffrdNmVLDTN." <span style='color:blue'>exits!</span><br/>";
                            }   
                        }
                    }
                }
                
                if($errCnt > 0){
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/><span style="color:red !important; font-weight:bold!important;">'
                    . 'Validation Errors Exists!<br/>'.$errMsgVLDTN.'</span><span style="font-weight:bold !important;">Failed Update/Insert</span></div>';
                    exit();
                }
                
                for ($z = 0; $z < count($variousRows); $z++) {
                    $crntRow = explode("~", $variousRows[$z]);
                    if (count($crntRow) == 7) { 
                        
                        $typeId = (int) (cleanInputData1($crntRow[0]));
                        $srvcOffrdNm  = cleanInputData1($crntRow[1]);
                        $srvcOffrdDesc = cleanInputData1($crntRow[2]);
                        $srvcOffrdItmID = (int) cleanInputData1($crntRow[3]);
                        $srvcOffrdSys = cleanInputData1($crntRow[4]);
                        $srvcOffrdIsEnabled = cleanInputData1($crntRow[5]);
                        $teleEnabled = cleanInputData1($crntRow[6]);       
                        $IsTeleEnabled = true;
                        if ($teleEnabled == "NO") {
                                $IsTeleEnabled = false;
                        }
                        
                        if ($typeId > 0) {
                            $recCntUpdt = $recCntUpdt + updateCreditSrvcOffrds($typeId, $srvcOffrdNm, $srvcOffrdDesc, $srvcOffrdItmID, $srvcOffrdSys, $srvcOffrdIsEnabled, $dateStr, $IsTeleEnabled);
                        } else {
                            $typeId = getSrvcOffrdID();
                            $recCntInst = $recCntInst + createCreditSrvcOffrds($typeId, $srvcOffrdNm, $srvcOffrdDesc, $srvcOffrdItmID, $srvcOffrdSys, $srvcOffrdIsEnabled, $dateStr, $IsTeleEnabled);
                        }

                    }
                }

                echo "<span style='color:green;font-weight:bold !important;'><i>$recCntInst record(s) inserted</br>$recCntUpdt record(s) updated</i></span>";
                exit();
            } else {
                echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;color:red !important; font-weight:bold!important;"/>'
                . 'Please provide one Service Type Record before saving!<br/></div>';
                exit();
            }
        } else {
            if (1 == 1) {
                //var_dump($_POST);
                $canAddSrvcOffrd = test_prmssns($dfltPrvldgs[14], $mdlNm);
                $canEdtSrvcOffrd = test_prmssns($dfltPrvldgs[15], $mdlNm);
                $canDelSrvcOffrd = test_prmssns($dfltPrvldgs[16], $mdlNm);
                $canSetupExtraSrvsData = test_prmssns($dfltPrvldgs[33], $mdlNm);

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
                                    <span style=\"text-decoration:none;\">Service Types</span>
				</li>
                               </ul>
                              </div>";

                    $total = getCreditSrvcOffrdsTblTtl($isEnabledOnly, $srchFor, $srchIn, $searchAll);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = getCreditSrvcOffrdsTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $searchAll, $sortBy);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-1";
                    ?>
                    <form id='allSrvcOffrdsForm' action='' method='post' accept-charset='UTF-8'>
                        <!--<fieldset class="basic_person_fs5">-->
                        <legend class="basic_person_lg1" style="color: #003245">SERVICE TYPES</legend>                
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <div class="row rhoRowMargin" style="margin-bottom:10px;">
                            <?php
                            if ($canAddSrvcOffrd === true) {
                                $nwRowHtml = urlencode("<tr id=\"allSrvcOffrdsRow__WWW123WWW\">"
                                                                . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                                . "<td class=\"lovtd\"><span class=\"normaltd\">&nbsp;</span></td>"
                                                                . "<td class=\"lovtd\">
                                                            <input type=\"text\" style=\"width:100% !important;\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allSrvcOffrdsRow_WWW123WWW_SrvcOffrdNm\" name=\"allSrvcOffrdsRow_WWW123WWW_SrvcOffrdNm\" value=\"\">   
                                                            <input type=\"hidden\"  class=\"form-control\" aria-label=\"...\" id=\"allSrvcOffrdsRow_WWW123WWW_SrvcOffrdID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                                        </td>                                             
                                                        <td class=\"lovtd\">
                                                                <input type=\"text\" style=\"width:100% !important;\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allSrvcOffrdsRow_WWW123WWW_SrvcOffrdDesc\" name=\"allSrvcOffrdsRow_WWW123WWW_SrvcOffrdDesc\" value=\"\">                                                               
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allSrvcOffrdsRow_WWW123WWW_SrvcOffrdItm\" value=\"\" readonly=\"true\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allSrvcOffrdsRow_WWW123WWW_SrvcOffrdItmID\" value=\"-1\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Services', 'gnrlOrgID', '', '', 'radio', true, '', 'allSrvcOffrdsRow_WWW123WWW_SrvcOffrdItmID', 'allSrvcOffrdsRow_WWW123WWW_SrvcOffrdItm', 'clear', 0, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                                <input type=\"text\" style=\"width:100% !important;\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allSrvcOffrdsRow_WWW123WWW_SrvcOffrdSys\" name=\"allSrvcOffrdsRow_WWW123WWW_SrvcOffrdSys\" value=\"\">                                                               
                                                        </td>
                                                        <td class=\"lovtd\">       
                                                            <select class=\"form-control\" aria-label=\"...\" id=\"allSrvcOffrdsRow_WWW123WWW_SrvcOffrdIsEnabled\" name=\"allSrvcOffrdsRow_WWW123WWW_SrvcOffrdIsEnabled\">
                                                                <option value=\"1\" selected>Yes</option>
                                                                <option value=\"0\" >No</option>														
                                                            </select>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;text-align:center;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"allSrvcOffrdsRow_WWW123WWW_TelemedicineEnabled\" name=\"allSrvcOffrdsRow_WWW123WWW_TelemedicineEnabled\">
                                                                    </label>
                                                                </div>
                                                            </div>                                                       
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delSrvcOffrd('allSrvcOffrdsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Service\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                    </tr>");
                                ?>   
                                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allSrvcOffrdsTable', 0, '<?php echo $nwRowHtml; ?>');">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                New Service Type
                                            </button>
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
                                    <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">
                                    <input class="form-control" id="allSrvcOffrdsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllSrvcOffrds(event, '', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital')">
                                    <input id="allSrvcOffrdsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSrvcOffrds('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSrvcOffrds('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allSrvcOffrdsSrchIn">
                    <?php
                    $valslctdArry = array("", "", "", "");
                    $srchInsArrys = array("Service Type Description", "Service Type Name", "Inventory Item/Service", "System Code");
                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                        if ($srchIn == $srchInsArrys[$z]) {
                            $valslctdArry[$z] = "selected";
                        }
                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                    <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allSrvcOffrdsDsplySze" style="min-width:70px !important;">                            
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
                                        <input type="checkbox" class="form-check-input" onclick="getAllSrvcOffrds('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');" id="allSrvcOffrdsIsEnabled" name="allSrvcOffrdsIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                        Enabled?
                                    </label>
                                </div>                             
                            </div>
                            <div class="<?php echo $colClassType3; ?>" style="width:10% !important;">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getAllSrvcOffrds('previous', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getAllSrvcOffrds('next', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>  
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 3px !important;"> 
                                <div style="float:right !important;">
                                    <?php if ($canAddSrvcOffrd === true) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveSrvcOffrd();" data-toggle="tooltip" data-placement="bottom" title="Save SrvcOffrd">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save Service Type
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>               
                        <div class="row" style="padding:0px 15px 0px 15px !important">                        
                            <div  class="col-md-12" style="padding:0px 1px 0px 1px !important">
                                <fieldset class="basic_person_fs" style="padding-top:5px !important;">
                                    <div class="" id="allSrvcOffrdsDetailInfo">
                                        <div class="row" id="allSrvcOffrdsDetailInfo" style="padding:0px 15px 0px 15px !important">
                                                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                                        <table class="table table-striped table-bordered table-responsive" id="allSrvcOffrdsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:40px !important; width:40px !important;">No.</th>
                                                                    <th style="max-width:30px !important; width:30px !important;">&nbsp;</th>
                                                                    <th>Service Type</th>
                                                                    <th>Description</th>
                                                                    <th>Cost Item</th>
                                                                    <th style="max-width:110px !important; width:110px !important;">System Code</th>
                                                                    <th style="max-width:90px !important; width:90px !important;">Is Enabled</th>
                                                                    <th style="max-width:90px !important; width:90px !important;">Telemedicine<br/>Enabled?</th>
                                                                    <th style="max-width:30px !important; width:30px !important;">&nbsp;</th>
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
                                                                    $systemCodesRdOnly = "";
                                                                    $rqrdFld = "rqrdFld";
                                                                    if($row2[4] == "MC-0001" || $row2[4] == "IA-0001" || $row2[4] == "LI-0001" || $row2[4] == "PH-0001" || $row2[4] == "RD-0001"
                                                                            || $row2[4] == "VS-0001"){
                                                                        $systemCodesRdOnly = "readonly='readonly'";
                                                                        $rqrdFld = "";
                                                                    } 
                                                                    ?>
                                                                    <tr id="allSrvcOffrdsRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                        <td class="lovtd">
                                                                            <?php  if($row2[4] !== "VS-0001" && $canSetupExtraSrvsData === true){ ?>
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="openATab('#allmodules', 'grp=14&typ=1&pg=102&vtyp=0&mdl=Clinic/Hospital&srvsTypeId=<?php echo $row2[0]; ?>&srcType=<?php echo $row2[4]; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Service">
                                                                                <img src="cmn_images/settings.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                            <?php  } ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                                            <input type="text" style="width:100% !important;" <?php echo $systemCodesRdOnly; ?> class="form-control <?php echo $rqrdFld; ?>" aria-label="..." id="allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdNm" name="allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdNm" value="<?php echo $row2[1]; ?>">                                                               
                                                                        </td>                                             
                                                                        <td class="lovtd"> 
                                                                            <input type="text" style="width:100% !important;" class="form-control rqrdFld" aria-label="..." id="allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdDesc" name="allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdDesc" value="<?php echo $row2[2]; ?>">
                                                                        </td>
                                                                        <td class="lovtd"> 
                                                                            <div class="input-group"  style="width:100%;">
                                                                                <input type="text" style="width:100% !important;" readonly="readonly" class="form-control" aria-label="..." id="allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdItm" name="allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdItm" value="<?php echo $row2[6]; ?>">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdItmID" value="<?php echo $row2[3]; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Services', 'gnrlOrgID', '', '', 'radio', true, '', 'allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdItmID', 'allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdItm', 'clear', 0, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td class="lovtd"> 
                                                                            <input type="text" style="width:100% !important;" <?php echo $systemCodesRdOnly; ?> class="form-control <?php echo $rqrdFld; ?>" aria-label="..." maxlength="7"  id="allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdSys" name="allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdSys" value="<?php echo $row2[4]; ?>">
                                                                        </td>
                                                                        <td class="lovtd">  
                                                                            <select class="form-control" aria-label="..." <?php echo $systemCodesRdOnly; ?> id="allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdIsEnabled" name="allSrvcOffrdsRow<?php echo $cntr; ?>_SrvcOffrdIsEnabled">
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
                                                                                <?php  if(!($row2[4] == "MC-0001" || $row2[4] == "IA-0001" || $row2[4] == "LI-0001" || $row2[4] == "PH-0001" || $row2[4] == "RD-0001"
                                                                                        || $row2[4] == "VS-0001")){ ?>
                                                                                <option value="0" <?php echo $sltdNo; ?>>No</option>    
                                                                                <?php } ?>
                                                                            </select>		
                                                                        </td>
                                                                        <td class="lovtd" style="text-align:center;">
                                                                            <?php
                                                                            $isChkd2 = "";
                                                                            if ($row2[7] == "1") {
                                                                                    $isChkd2 = "checked=\"true\"";
                                                                            }
                                                                            ?>
                                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                                    <label class="form-check-label">
                                                                                        <input type="checkbox" class="form-check-input" id="allSrvcOffrdsRow<?php echo $cntr; ?>_TelemedicineEnabled" name="allSrvcOffrdsRow<?php echo $cntr; ?>_TelemedicineEnabled" <?php echo $isChkd2 ?>>
                                                                                    </label>
                                                                                </div>
                                                                            </div>	
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php  if(!($row2[4] == "MC-0001" || $row2[4] == "IA-0001" || $row2[4] == "LI-0001" || $row2[4] == "PH-0001" || $row2[4] == "RD-0001"
                                                                                        || $row2[4] == "VS-0001") && $canDelSrvcOffrd == true){ ?>
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSrvcOffrd('allSrvcOffrdsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Service">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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