<?php

$dateStr = getDB_Date_time();
$pkID = $PKeyID;

$prsnid = $_SESSION['PRSN_ID'];

$prsnJob = getPrsnJobNm($prsnid);
$qStrtDte = "01-Jan-1900 00:00:00";
$qEndDte = "31-Dec-4000 23:59:59";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
    if (1 == 1) {
            //var_dump($_POST);
            //$canAddHstrcMdclCnsltn = test_prmssns($dfltPrvldgs[16], $mdlNm);
            //$canEdtMCRecs = test_prmssns($dfltPrvldgs[17], $mdlNm);
            //$canDelHstrcMdclCnsltn = test_prmssns($dfltPrvldgs[18], $mdlNm);
            
            

            $error = "";
            $searchAll = true;
            $isEnabledOnly = false;
            if (isset($_POST['isEnabled'])) {
                $isEnabledOnly = cleanInputData($_POST['isEnabled']);
            }
            
            $patientPrsnID = isset($_POST['patientPrsnID']) ? cleanInputData($_POST['patientPrsnID']) : -1;
            $patientNm = getGnrlRecNm("prs.prsn_names_nos", "person_id", "trim(title || ' ' || sur_name ||', ' || first_name || ' ' || other_names)||' ('||local_id_no||')'", $patientPrsnID);

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
            
            $statusSrchIn = isset($_POST['statusSrchIn']) ? cleanInputData($_POST['statusSrchIn']) : "Open";
            
            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {//3
                /*$total = getCreditHstrcMdclCnsltnsTblTtl($isEnabledOnly, $srchFor, $srchIn, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = getCreditHstrcMdclCnsltnsTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $searchAll, $sortBy);
                $cntr = 0;*/
                
                
                $total = get_PatientHstrcAppointmentsTtl($patientPrsnID, $qStrtDte, $qEndDte, $statusSrchIn/* , $branchSrchIn */, $srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_PatientHstrcAppointments($patientPrsnID, $qStrtDte, $qEndDte, $statusSrchIn/* , $branchSrchIn */, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll);
                $cntr = 0;
                
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-1";

                ?>
                <form id='allHstrcMdclCnsltnsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--<fieldset class="basic_person_fs5">-->
                    <legend class="basic_person_lg1" style="color: #003245">HISTORIC APPOINTMENTS</legend>                
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <div class="row rhoRowMargin" style="margin-bottom:10px;">
                        <div class="col-lg-1">
                            <div class="input-group">
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allHstrcMdclCnsltnsDsplySze" style="min-width:70px !important;">                            
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
                        <div class="col-lg-4">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a href="javascript:getHstrcPatientHstrcMdclCnsltns(<?php echo $patientPrsnID; ?>, '<?php echo $patientNm; ?>', 'previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital', -1);" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;&nbsp;Previous</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a style="margin-left: 5px !important;" href="javascript:getHstrcPatientHstrcMdclCnsltns(<?php echo $patientPrsnID; ?>, '<?php echo $patientNm; ?>', 'next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital', -1);" aria-label="Next">
                                            <span aria-hidden="true">&raquo;&nbsp;Next</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div> 
                        <div class="col-lg-7">&nbsp;</div>
                    </div>               
                    <div class="row" style="padding:0px 15px 0px 15px !important"> 
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allHstrcMdclCnsltnsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Appointment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sbmtdHstrcMdclCnsltnID = -1;
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($sbmtdHstrcMdclCnsltnID <= 0 && $cntr <= 0) {
                                                $sbmtdHstrcMdclCnsltnID = $row[0];
                                            }

                                            $mainSvsOffrd = getGnrlRecNm("hosp.srvs_types", "type_id", "type_name", $row[4]);

                                            $cntr += 1;
                                            ?>
                                            <tr id="allHstrcMdclCnsltnsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allHstrcMdclCnsltnsRow<?php echo $cntr; ?>_HstrcMdclCnsltnID" value="<?php echo $row[0]; ?>">
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>                        
                            </fieldset>
                        </div>                        
                        <div  class="col-md-10" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:5px !important;">
                                <div class="" id="allHstrcMdclCnsltnsDetailInfo">
                                    <div class="row" id="allHstrcMdclCnsltnsHdrInfo" style="padding:0px 15px 0px 15px !important">
                                        <?php
                                        $pkID = $sbmtdHstrcMdclCnsltnID;
                                        
                                        $actvTabCnsltn = "active";
                                        $actvTabInvstgn = "";
                                        $actvTabPhrmcy = "";
                                        $actvTabVitals = "";
                                        $actvTabAdmissions = "";
                                        $hideNotice = "hideNotice";

                                        //GET SERVICE TYPE MAIN SERVICE PROVIDER GROUP
                                        $PHMainSrvsPrvdrGrpID = getSrvsTypeMainPrvdrGrp("PH-0001");//PHARMACY
                                        $PHMainSrvsTypeID = getSrvsTypeIDFromSysCode("PH-0001");
                                        $LIMainSrvsPrvdrGrpID = getSrvsTypeMainPrvdrGrp("LI-0001");//LAB
                                        $LIMainSrvsTypeID = getSrvsTypeIDFromSysCode("LI-0001");
                                        $IAMainSrvsPrvdrGrpID = getSrvsTypeMainPrvdrGrp("IA-0001");//ADMISSIONS  
                                        $IAMainSrvsTypeID = getSrvsTypeIDFromSysCode("IA-0001");

                                        $lnkdAppntmntDate = date("d-M-Y");

                                        $voidedTrnsHdrID = -1;

                                        $rqstatusColor = "red";
                                        $ttlColor = "blue";
                                        $mkReadOnly = "";
                                        $mkRmrkReadOnly = "";
                                        $mkReadOnlyDsbld = "";

                                        $sbmtdTrnsHdrID = $pkID;

                                        $srcPgNo = isset($_POST['srcPgNo']) ? $_POST['srcPgNo'] : 3;

                                        $shdDplct = isset($_POST['shdDplct']) ? $_POST['shdDplct'] : '0';
                                        $srcCaller = isset($_POST['srcCaller']) ? $_POST['srcCaller'] : 'NORMAL';
                                        if (trim($srcCaller) === "") {
                                            $srcCaller = 'NORMAL';
                                        }
                                        $sbmtdRptID = -1;
                                        $sbmtdRptIDDsply = -1;
                                        $rptsRptNm = "";
                                        $$rqstatusColor = "red";
                                        $trnsStatus = "Open";
                                        $sbmtdPersonID = -1;
                                        $officer = getPrsnFullNm($prsnid);

                                        $cnsltnID = -1;
                                        $patientIDNo = "";
                                        $patientNm = "";
                                        $gender = "";
                                        $age = "";
                                        $bloodGroup = "";
                                        $sickleGenotype = "";
                                        $vstType = "";
                                        $vstNo = "";
                                        $appntmntNo = "";
                                        $sponsor = "Normal";
                                        $appntmntDate = "";
                                        $cardNo = "";
                                        $cardExpiryDate = "";
                                        $cardNoAndExpiry = "";
                                        $patientCmplt = "";
                                        $checkInDate = "";
                                        $checkOutDate = "";

                                        $physicalExam = "";
                                        $cnsltnCmnts = "";
                                        $patientPersonID = -1;
                                        $prsnImgLoc = "";
                                        $appntmntRmks = "";
                                        $vstId = -1;

                                        $docAdmsnInstructions = "";
                                        $docAdmsnCheckInDate = "";//date("d-M-Y");
                                        $docAdmsnCheckInNoOfDays = "";

                                        $ttlSrvsBillPymnt = "0.00";
                                        $ttlSrvsBill = "0.00";

                                        $resultAppntmntIDs = getAppntmntInvcIDs($pkID);

                                        while ($rowBP = loc_db_fetch_array($resultAppntmntIDs)) {
                                            $rsltw = get_One_SalesInvcAmounts($rowBP[0]);
                                            if ($rw = loc_db_fetch_array($rsltw)) {
                                                $ttlSrvsBill = $ttlSrvsBill + (float) $rw[0];
                                                $ttlSrvsBillPymnt = $ttlSrvsBillPymnt + $rw[1];
                                            }
                                        }

                                        //$sbmtdScmSalesInvcID = getGnrlRecNm("scm.scm_sales_invc_hdr", "other_mdls_doc_id", "invc_hdr_id", $docNum);



                                        $resultCnsltn = getAppointmentCnsltn($pkID);

                                        if($vwtyp == 5){
                                            $cnsltnID = getInhouseAdmsnCnsltnID($pkID);
                                            if($cnsltnID > 0){
                                                $resultCnsltn = getCnsltnDetails($cnsltnID);
                                            }
                                        }

                                        while ($rowCnstl = loc_db_fetch_array($resultCnsltn)) {
                                            $cnsltnID = $rowCnstl[0];
                                            $patientCmplt = $rowCnstl[1];
                                            $physicalExam = $rowCnstl[2];
                                            $cnsltnCmnts = $rowCnstl[3];
                                            $docAdmsnInstructions = $rowCnstl[5];
                                            $docAdmsnCheckInDate = $rowCnstl[6];
                                            $docAdmsnCheckInNoOfDays = $rowCnstl[7];
                                        }

                                        if($docAdmsnCheckInNoOfDays == 0 || $docAdmsnCheckInNoOfDays == "0"){
                                            $docAdmsnCheckInNoOfDays = "";
                                        }

                                        $dsplyDcElmnts = "";
                                        $dsplyOthrElmnts = "display:none";
                                        if ($cnsltnID <= 0) {
                                            $dsplyDcElmnts = "display:none";
                                            $dsplyOthrElmnts = "";
                                        }

                                        if ($vwtyp == 5) {
                                            $dsplyDcElmnts = "display:none";
                                            $dsplyOthrElmnts = "";
                                        }

                                        $result = get_AppointmentDataDet($pkID);

                                        while ($row = loc_db_fetch_array($result)) {
                                            $patientIDNo = $row[14];
                                            $patientNm = $row[2];
                                            $gender = $row[15];
                                            $age = getCustAge($row[16]) . " Years " . getMonthAge($row[16]) . " Months";
                                            $bloodGroup = get_PrsExtrData($row[19], "1");
                                            $sickleGenotype = get_PrsExtrData($row[19], "2");
                                            $vstType = $row[17];
                                            $vstNo = $row[18];
                                            $appntmntNo = $row[1];
                                            $sponsor = get_PrsExtrData($row[19], "6");
                                            $appntmntDate = $row[3];
                                            $cardNo = get_PrsExtrData($row[19], "7");
                                            $cardExpiryDate = get_PrsExtrData($row[19], "7");
                                            $patientPersonID = $row[19];
                                            $prsnImgLoc = $row[20];
                                            $checkInDate = $row[21];
                                            $checkOutDate = $row[9];
                                            $officer = $row[6];
                                            $appntmntRmks = $row[8];
                                            $trnsStatus = $row[7];
                                            $vstId =  $row[10];
                                        }
                                        
                                        $rcrdExst = prsn_Record_Exist($pkID);

                                        $nwFileName = "";
                                        $temp = explode(".", $prsnImgLoc);
                                        $extension = end($temp);
                                        if (trim($extension) == "") {
                                            $extension = "png";
                                        }
                                        $ecnptDFlNm = encrypt1($prsnImgLoc, $smplTokenWord1);
                                        $nwFileName = $ecnptDFlNm . "." . $extension;
                                        $ftp_src = "";
                                        if ($patientPersonID <= 0) {
                                            $ftp_src = $ftp_base_db_fldr . "/Person/Request/" . $patientPersonID . "." . $extension;
                                            if (!($rcrdExst == true && $chngRqstExst > 0 && file_exists($ftp_src))) {
                                                $ftp_src = $ftp_base_db_fldr . "/Person/" . $patientPersonID . "." . $extension;
                                            }
                                        } else {
                                            $ftp_src = $ftp_base_db_fldr . "/Person/" . $patientPersonID . "." . $extension;
                                        }

                                        //echo $ftp_src;
                                        ///home/rhoportal/ecng/Person/cLSKLnmKKLSmRFTNnYBvuJQBKnALQJKZKXVXucWKmTcRLTc.png
                                        $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                                        if (file_exists($ftp_src)) {
                                            copy("$ftp_src", "$fullPemDest");
                                        } else if (!file_exists($fullPemDest)) {
                                            $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                                            copy("$ftp_src", "$fullPemDest");
                                        }
                                        $nwFileName = $tmpDest . $nwFileName;
                                        
                                        ?>
                                        <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;">                                              
                                            <div class="row">
                                                <div  class="col-md-5" style="padding:0px 3px 0px 3px !important;"> 
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;display:none !important;">
                                                        <label for="patientIDNo" class="control-label col-lg-4">Patient ID:</label>
                                                        <div  class="col-lg-8">
                                                            <input type="text" id="srcPgNo" class="form-control"value="<?php echo $srcPgNo; ?>"/>
                                                            <input type="text" class="form-control" aria-label="..." id="patientIDNo" name="patientIDNo" value="<?php echo $patientIDNo; ?>" style="width:100%;" readonly="readonly">   
                                                            <input type="hidden" class="form-control" aria-label="..." id="cnsltnID" name="cnsltnID" value="<?php echo $cnsltnID; ?>" style="width:100%;"> 
                                                            <input type="hidden" class="form-control" id="appntmntID"  placeholder="Appointment ID" value="<?php echo $pkID; ?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="patientNm" class="control-label col-lg-4">Patient Name:</label>
                                                        <div  class="col-lg-8">
                                                            <input type="text" class="form-control" aria-label="..." id="patientNm" name="patientNm" value="<?php echo $patientNm; ?>" style="width:100%;" readonly="readonly">   
                                                        </div>
                                                    </div> 
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="gender" class="control-label col-lg-4">Gender/Age:</label>
                                                        <div  class="col-lg-8">
                                                            <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="gender" name="gender" value="<?php echo $gender; ?>" style="width:100%;" readonly="readonly">     
                                                            </div>
                                                            <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="patientAge" name="patientAge" value="<?php echo $age; ?>" style="width:100%;" readonly="readonly">    
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="bloodGroup" class="control-label col-lg-4">Blood Group/Genotype:</label>
                                                        <div  class="col-lg-8">
                                                            <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="bloodGroup" name="bloodGroup" value="<?php echo $bloodGroup; ?>" style="width:100%;" readonly="readonly">   
                                                            </div>
                                                            <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="sickleGenotype" name="sickleGenotype" value="<?php echo $sickleGenotype; ?>" style="width:100%;" readonly="readonly">   
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="vstType" class="control-label col-lg-4">Visit Type/No.:</label>
                                                        <div  class="col-lg-8">
                                                            <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="vstType" name="vstType" value="<?php echo $vstType; ?>" style="width:100%;" readonly="readonly">   
                                                            </div>
                                                            <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="vstNo" name="vstNo" value="<?php echo $vstNo; ?>" style="width:100%;" readonly="readonly">   
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="appntmntNo" class="control-label col-lg-4">Appointment Date/No.:</label>
                                                        <div  class="col-lg-8">
                                                            <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="appntmntDate" name="appntmntDate" value="<?php echo $appntmntDate; ?>" style="width:100%;" readonly="readonly">   
                                                            </div>
                                                            <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="appntmntNo" name="appntmntNo" value="<?php echo $appntmntNo; ?>" style="width:100%;" readonly="readonly">   
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="appntmntNo" class="control-label col-lg-4">Total Bill/Payment:</label>
                                                        <div  class="col-lg-8">
                                                            <div class="col-md-2" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="thisCrncy" name="thisCrncy" value="GHS" style="width:100%;font-weight:bold !important;font-size:16px !important;" readonly="readonly">   
                                                            </div>
                                                            <div class="col-md-5" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="ttlInvoice" name="ttlInvoice" value="<?php echo number_format($ttlSrvsBill,2); ?>" style="width:100%;color:red;font-weight:bold !important;font-size:16px !important;" readonly="readonly">   
                                                            </div>
                                                            <div class="col-md-5" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="ttlPymnt" name="ttlPymnt" value="<?php echo number_format($ttlSrvsBillPymnt,2); ?>" style="width:100%;color:blue;font-weight:bold !important;font-size:16px !important;" readonly="readonly">   
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div  class="col-md-5" style="padding:0px 3px 0px 3px !important;"> 
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="sponsor" class="control-label col-lg-4">Sponsor:</label>
                                                        <div  class="col-lg-8">
                                                            <input type="text" class="form-control" aria-label="..." id="sponsor" name="sponsor" value="<?php echo $sponsor; ?>" style="width:100%;" readonly="readonly">   
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="cardNo" class="control-label col-lg-4">Card No./Expiry Date:</label>
                                                        <div  class="col-lg-8">
                                                            <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="cardNo" name="cardNo" value="<?php echo $cardNo; ?>" style="width:100%;" readonly="readonly">   
                                                            </div>
                                                            <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="expiryDate" name="expiryDate" value="<?php echo $cardExpiryDate; ?>" style="width:100%;" readonly="readonly">   
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="checkInDate" class="control-label col-lg-4">Check In/Out Date:</label>
                                                        <div  class="col-lg-8">
                                                            <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="checkInDate" name="checkInDate" value="<?php echo $checkInDate; ?>" style="width:100%;" readonly="readonly">   
                                                            </div>
                                                            <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="checkOutDate" name="checkOutDate" value="<?php echo $checkOutDate; ?>" style="width:100%;" readonly="readonly">   
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="officer" class="control-label col-lg-4">Service Provider:</label>
                                                        <div  class="col-lg-8">
                                                            <input type="text" class="form-control" aria-label="..." id="officer" name="officer" value="<?php echo $officer; ?>" style="width:100%;" readonly="readonly">   
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="appntmntRmks" class="control-label col-lg-4">Appointment Remarks:</label>
                                                        <div  class="col-lg-8">
                                                            <textarea class="form-control" aria-label="..." id="appntmntRmks" name="appntmntRmks" rows="3" style="width:100%;" readonly="readonly" ><?php echo $appntmntRmks; ?></textarea> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div  class="col-md-2" style="padding:0px 3px 0px 3px !important;"> 
                                                    <!--<fieldset class="basic_person_fs1"><legend class="basic_person_lg">Patient Picture</legend>-->
                                                    <div style="margin-bottom: 10px;">
                                                        <img src="<?php echo $nwFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 190px !important; width: auto !important;">                                            
                                                    </div>                                       
                                                    <!--</fieldset>-->
                                                </div>
                                            </div>
                                            <div class="row"  style="padding:1px 15px 5px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div> 
                                            <div class="row"  style="padding:1px 15px 1px 15px !important;">
                                                <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;">
                                                    <li class="<?php echo $actvTabCnsltn ?>"><a data-toggle="hstrctabajxrptdet" data-rhodata="" href="#cnsltnMainTbPageHstrc" id="cnsltnMainTbPageHstrctab">Consultation</a></li>
                                                    <li class="<?php echo $actvTabInvstgn ?>"><a data-toggle="hstrctabajxrptdet" data-rhodata="" href="#invstgtnTbPageHstrc" id="invstgtnTbPageHstrctab">Investigations</a></li>
                                                    <li class="<?php echo $actvTabPhrmcy ?>"><a data-toggle="hstrctabajxrptdet" data-rhodata="" href="#medicationTbPageHstrc" id="medicationTbPageHstrctab">Medication</a></li>
                                                    <li class="<?php echo $actvTabVitals ?>"><a data-toggle="hstrctabajxrptdet" data-rhodata="" href="#vitalsTbPageHstrc" id="vitalsTbPageHstrctab">Vital Statistics</a></li>
                                                    <li class="<?php echo $actvTabAdmissions ?>"><a data-toggle="hstrctabajxrptdet" data-rhodata="" href="#inHouseAdmsnTbPageHstrc" id="inHouseAdmsnTbPageHstrctab">Admissions</a></li>
                                                </ul>                                    
                                                <div class="row">                  
                                                    <div class="col-md-12">
                                                        <div class="custDiv"> 
                                                            <div class="tab-content">
                                                                <div id="cnsltnMainTbPageHstrc" class="tab-pane fadein <?php echo $actvTabCnsltn ?>" style="border:none !important;">
                                                                    <div class="row" style="max-height: 245px !important;">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="patientCmplnt" class="control-label">Patient Complaint:</label>
                                                                                <div  class="">
                                                                                    <span><?php echo $patientCmplt; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="physicalExam" class="control-label">Physical Examination:</label>
                                                                                <div  class="">
                                                                                    <span><?php echo $physicalExam; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="row"> 
                                                                                <div  class="col-md-12" style="max-height: 203px !important;overflow-y: auto;">
                                                                                    <table class="table table-striped table-bordered table-responsive" id="diagnosisTable" cellspacing="0" width="100%" style="width:100%;">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>No.</th>
                                                                                                <th style="min-width:200px;">Diagnosis</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                        <?php
                                        $cntr = 0;
                                        $curIdx = 0;
                                        $result3 = get_AllDiagnosis($cnsltnID);
                                        $rptRlID = -1;
                                        while ($row3 = loc_db_fetch_array($result3)) {
                                            $cntr += 1;
                                            ?>
                                                                                                <tr id="diagnosisRow_<?php echo $cntr; ?>">                                    
                                                                                                    <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                                    <td class="lovtd">
                                                                                                        <span><?php echo $row3[2]; ?></span>
                                                                                                        <input type="hidden" class="form-control rqrdFld" aria-label="..." id="diagnosisRow<?php echo $cntr; ?>_DiseaseNm" value="<?php echo $row3[2]; ?>" readonly="true">
                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="diagnosisRow<?php echo $cntr; ?>_DiseaseID" value="<?php echo $row3[1]; ?>">
                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="diagnosisRow<?php echo $cntr; ?>_DiagID" value="<?php echo $row3[0]; ?>">
                                                                                                    </td> 
                                                                                                </tr>
                                                                                                    <?php
                                                                                                }
                                                                                                ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>                     
                                                                            </div> 
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-top:10px !important;">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="cnsltnCmnts" class="control-label">Direct Questions/Comments:</label>
                                                                                <div  class="">
                                                                                     <span><?php echo $cnsltnCmnts; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="invstgtnTbPageHstrc" class="tab-pane fadein <?php echo $hideNotice; ?> <?php echo $actvTabInvstgn; ?>" style="border:none !important;">   
                                                                    <div class="row"> 
                                                                        <div  class="col-md-12">
                                                                            <table class="table table-striped table-bordered table-responsive" id="invstgtnTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px !important;">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>No.</th>
                                                                                        <th style="min-width:68px !important;">Requested Lab Item</th>
                                                                                        <th>Doctor's Comment</th>
                                                                                        <th>In-house?</th>
                                                                                        <th>Lab Results</th>
                                                                                        <th>Lab Comments</th>
                                                                                        <th>Lab Location</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                        <?php
                                        $cntr = 0;
                                        //echo $cnsltnID;
                                        $invstgtnID = -1;

                                        $result3 = get_AllInvestigations($cnsltnID, $pkID);

                                        while ($row3 = loc_db_fetch_array($result3)) {
                                            $cntr += 1;
                                            $invstgtnID = $row3[0];
                                            ?>
                                                                                        <tr id="invstgtnRow_<?php echo $cntr; ?>">                                    
                                                                                            <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                            <td class="lovtd"><span><?php echo $row3[2] . " (" . $row3[7] . ")"; ?>
                                                                                                </span>
                                                                                            </td>
                                                                                            <td class="lovtd"><span><?php echo $row3[3]; ?>
                                                                                                </span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="text-align:center;">
                                                                                                    <span><?php echo ($row3[12] == "1" ? "Yes" : "No"); ?></span>                                                        
                                                                                            </td>
                                                                                            <td class="lovtd">
                                                                                                <textarea class="form-control rqrdFld" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_LabRslt" style="width:100% !important;<?php echo $dsplyOthrElmnts; ?>"><?php echo $row3[4]; ?></textarea>
                                                                                                <span style=""><?php echo $row3[4]; ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd">
                                                                                                <textarea class="form-control" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_LabCmnts" style="width:100% !important;<?php echo $dsplyOthrElmnts; ?>"><?php echo $row3[5]; ?></textarea>
                                                                                                <span style=""><?php echo $row3[5]; ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd">
                                                                                                <textarea class="form-control" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_LabLoc" style="width:100% !important;<?php echo $dsplyOthrElmnts; ?>"><?php echo $row3[6]; ?></textarea>
                                                                                                <span style=""><?php echo $row[6]; ?></span>
                                                                                            </td>
                                                                                        </tr>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>                     
                                                                    </div>  
                                                                </div>
                                                                <div id="medicationTbPageHstrc" class="tab-pane fadein <?php echo $hideNotice; ?> <?php echo $actvTabPhrmcy; ?>" style="border:none !important;">
                                                                    <div class="row"> 
                                                                        <div  class="col-md-12">
                                                                            <table class="table table-striped table-bordered table-responsive" id="medicationTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px !important;">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>No.</th>
                                                                                        <th style="min-width:150px;">Drug</th>
                                                                                        <th style="min-width:310px;">Dosage</th>
                                                                                        <th style="min-width:70px;">Form</th>
                                                                                        <th>Substitute</br>Allowed?</th>
                                                                                        <th style="min-width:150px;">Doctor's Comments</th>
                                                                                        <th>Dispense?</th>
                                                                                        <th style="min-width:150px;">Pharmacist Comments</th>
                                                                                        <th style="min-width:100px;">Admin Times</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $cntr = 0;
                                                                                    $curIdx = 0;
                                                                                    $lmtSze = 0;
                                                                                    $result2 = get_AllMedications($cnsltnID, $pkID);
                                                                                    $prmID = -1;
                                                                                    $instruction = "";
                                                                                    while ($row2 = loc_db_fetch_array($result2)) {
                                                                                        $cntr += 1;
                                                                                        $instruction = $row2[3] . " " . $row2[4] . " " . $row2[5] . " times a " . $row2[6] . " for " . $row2[7] . " " . $row2[8];
                                                                                        ?>
                                                                                        <tr id="medicationRow_<?php echo $cntr; ?>">                                    
                                                                                            <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>                                               
                                                                                            <td class="lovtd"><span><?php echo $row2[2]; ?></span>                                                         
                                                                                            </td>
                                                                                            <td class="lovtd"><span><?php echo $instruction; ?></span>                                                         
                                                                                            </td>                                                                                              
                                                                                            <td class="lovtd"><span><?php echo $row2[9]; ?></span>                                                        
                                                                                            </td>  
                                                                                            <td class="lovtd" style="text-align:center;"><span><?php echo ($row2[10] == "1" ? "Yes" : "No"); ?></span>                                                         
                                                                                            </td>                                            
                                                                                            <td class="lovtd"><span><?php echo $row2[11]; ?></span>                                                         
                                                                                            </td>
                                                                                            <td class="lovtd" style="text-align:center;"><span><?php echo ($row2[12] == "1" ? "Yes" : "No"); ?></span>                                                         
                                                                                            </td>                                               
                                                                                            <td class="lovtd"><span><?php echo $row2[13]; ?></span>                                                         
                                                                                            </td>                                                                                             
                                                                                            <td class="lovtd"><span><?php echo $row2[14]; ?></span>                                                        
                                                                                            </td>
                                                                                        </tr>
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>                     
                                                                    </div> 
                                                                </div>                                                    
                                                                <div id="vitalsTbPageHstrc" class="tab-pane fadein <?php echo $hideNotice; ?> <?php echo $actvTabVitals; ?>" style="border:none !important;">
                                                                                        <?php
                                                                                        $vstID = (int) getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "vst_id", $pkID);
                                                                                        $result = get_VisitVitalsData($vstID);
                                                                                        $weight = "";
                                                                                        $height = "";
                                                                                        $bmi = "";
                                                                                        $bmi_status = "";
                                                                                        $bp_systlc = "";
                                                                                        $bp_diastlc = "";
                                                                                        $bp_status = "";
                                                                                        $pulse = "";
                                                                                        $body_tmp = "";
                                                                                        $tmp_loc = "";
                                                                                        $resptn = "";
                                                                                        $oxgn_satn = "";
                                                                                        $head_circum = "";
                                                                                        $waist_circum = "";
                                                                                        $bowel_actn = "";
                                                                                        $cmnts = "";


                                                                                        while ($row2 = loc_db_fetch_array($result)) {
                                                                                            $weight = $row2[1];
                                                                                            $height = $row2[2];
                                                                                            $bmi = $row2[3];
                                                                                            $bmi_status = $row2[4];
                                                                                            $bp_systlc = $row2[5];
                                                                                            $bp_diastlc = $row2[6];
                                                                                            $bp_status = $row2[7];
                                                                                            $pulse = $row2[8];
                                                                                            $body_tmp = $row2[9];
                                                                                            $tmp_loc = $row2[10];
                                                                                            $resptn = (int)$row2[11] == 0 ? "" : $row2[11];
                                                                                            $oxgn_satn = (int)$row2[12] == 0 ? "" : $row2[12];
                                                                                            $head_circum = (int)$row2[13] == 0 ? "" : $row2[13];
                                                                                            $waist_circum = (int)$row2[14] == 0 ? "" : $row2[14];
                                                                                            $bowel_actn = $row2[15];
                                                                                            $cmnts = $row2[16];
                                                                                        }
                                                                                        ?>
                                                                    <div class='row' style="padding-left:0px !important;">
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >Weight:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $weight; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >Height:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $height; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                    <div class='row'  style="padding-left:0px !important;">
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >Body Mass Index:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $bmi; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >BMI Status:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $bmi_status; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class='row'  style="padding-left:0px !important;">
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >BP Systolic:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $bp_systlc; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >BP Diastolic:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $bp_diastlc; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                    <div class='row' style="padding-left:0px !important;">
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >BP Status:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $bp_status; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >Pulse:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $pulse; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                    <div class='row' style="padding-left:0px !important;">
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >Body Temperature:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $body_tmp; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >Temperature Location:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $tmp_loc; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class='row' style="padding-left:0px !important;">
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >Respiration:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $resptn; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >Oxygen Saturation:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $oxgn_satn; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class='row' style="padding-left:0px !important;">
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >Head Circumference:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $head_circum; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div  class="col-md-6">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntNo" class="control-label col-md-6" >Waist Circumference:</label>
                                                                                <div  class="col-md-6">
                                                                                    <span><?php echo $waist_circum; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class='row' style="padding-left:0px !important;">
                                                                        <div  class="col-md-12">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntCmnts" class="control-label col-md-3" >Bowel Action:</label>
                                                                                <div  class="col-md-9">
                                                                                    <span><?php echo $bowel_actn; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class='row' style="padding-left:0px !important;">
                                                                        <div  class="col-md-12">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="frmAppntmntCmnts" class="control-label col-md-3" >Remarks:</label>
                                                                                <div  class="col-md-9">
                                                                                    <span><?php echo $cmnts; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="inHouseAdmsnTbPageHstrc" class="tab-pane fadein <?php echo $hideNotice; ?> <?php echo $actvTabAdmissions; ?>" style="border:none !important;">
                                                                    <div class="row" style="max-height: 245px !important;">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="docAdmsnCheckInDate" class="control-label">Check-In Date:</label>
                                                                                <div  class=""><span><?php echo $docAdmsnCheckInDate; ?></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="docAdmsnCheckInNoOfDays" class="control-label">Number of Days:</label>
                                                                                <div  class=""><span><?php echo $docAdmsnCheckInNoOfDays; ?></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="docAdmsnInstructions" class="control-label">Doctor's Instructions:</label>
                                                                                <div  class=""><span><?php echo $patientCmplt; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="sltdOrgID" name="sltdOrgID" value="<?php echo $orgID; ?>" readonly="true">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="hotlChckinFcltyTypeClinic" name="hotlChckinFcltyTypeClinic" value="Room/Hall" readonly="true">
                                                                            <div class="row"> 
                                                                                <div  class="col-md-12" style="max-height: 274px !important;overflow-y: auto;">
                                                                                    <table class="table table-striped table-bordered table-responsive" id="allInptntAdmsnsTable" cellspacing="0" width="100%" style="width:100%;">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>No.</th>
                                                                                                <th style="max-width:130px;width:130px;">Check-In Date</th>
                                                                                                <th style="max-width:130px;width:130px;">Check-Out Date</th>
                                                                                                <th style="min-width:120px;">Facility Type</th>
                                                                                                <th style="min-width:120px;">Room</th>
                                                                                                <th>Document No.</th>
                                                                                                <th>&nbsp;</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            $cntr = 0;
                                                                                            $curIdx = 0;
                                                                                            $result3 = get_AllInhouseAdmissions($vstID);
                                                                                            $rptRlID = -1;
                                                                                            $refChckInId = -1;
                                                                                            while ($row3 = loc_db_fetch_array($result3)) {
                                                                                                $cntr += 1;
                                                                                                $refChckInId = $row3[8];
                                                                                                ?>
                                                                                                 <tr id="allInptntAdmsnsRow_<?php echo $cntr; ?>">                                    
                                                                                                    <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                                    <td class="lovtd">
                                                                                                        <span style=""><?php echo $row3[1]; ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd"> 
                                                                                                        <span style=""><?php echo $row3[2]; ?></span>
                                                                                                    </td> 
                                                                                                    <td class="lovtd"> 
                                                                                                        <span style=""><?php echo $row3[3]; ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd"> 
                                                                                                        <span style=""><?php echo $row3[4]; ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd"><?php echo $row3[5]; ?></td>
                                                                                                    <td class="lovtd">
                                                                                                    <?php if ($row3[5] == "" && $row3[1] != "" && $row3[2] != "" && $row3[3] != "" && $row3[4] != "") { ?>
                                                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneHotlChckinDocForm2(<?php echo $refChckInId; ?>, 3, 'ShowDialog', 'Check-In', 'CHECK-IN', 'allmodules', 'Room/Hall',<?php echo $row3[6]; ?>,<?php echo $row3[7]; ?>,<?php echo $row3[0]; ?>);" data-toggle="tooltip" data-placement="bottom" title="Create Check-In Document">
                                                                                                            <img src="cmn_images/98.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                                        </button>
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
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>                                  
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <!--</fieldset>-->
                </form>
                <?php
            }
            else if($vwtyp == 1){
                $sbmtdHstrcMdclCnsltnID = isset($_POST['sbmtdHstrcMdclCnsltnID']) ? cleanInputData($_POST['sbmtdHstrcMdclCnsltnID']) : -1;
                ?>
                <div class="row" id="allHstrcMdclCnsltnsHdrInfo" style="padding:0px 15px 0px 15px !important">
                    <?php
                    $pkID = $sbmtdHstrcMdclCnsltnID;

                    $actvTabCnsltn = "active";
                    $actvTabInvstgn = "";
                    $actvTabPhrmcy = "";
                    $actvTabVitals = "";
                    $actvTabAdmissions = "";
                    $hideNotice = "hideNotice";

                    //GET SERVICE TYPE MAIN SERVICE PROVIDER GROUP
                    $PHMainSrvsPrvdrGrpID = getSrvsTypeMainPrvdrGrp("PH-0001");//PHARMACY
                    $PHMainSrvsTypeID = getSrvsTypeIDFromSysCode("PH-0001");
                    $LIMainSrvsPrvdrGrpID = getSrvsTypeMainPrvdrGrp("LI-0001");//LAB
                    $LIMainSrvsTypeID = getSrvsTypeIDFromSysCode("LI-0001");
                    $IAMainSrvsPrvdrGrpID = getSrvsTypeMainPrvdrGrp("IA-0001");//ADMISSIONS  
                    $IAMainSrvsTypeID = getSrvsTypeIDFromSysCode("IA-0001");

                    $lnkdAppntmntDate = date("d-M-Y");

                    $voidedTrnsHdrID = -1;

                    $rqstatusColor = "red";
                    $ttlColor = "blue";
                    $mkReadOnly = "";
                    $mkRmrkReadOnly = "";
                    $mkReadOnlyDsbld = "";

                    $sbmtdTrnsHdrID = $pkID;

                    $srcPgNo = isset($_POST['srcPgNo']) ? $_POST['srcPgNo'] : 3;

                    $shdDplct = isset($_POST['shdDplct']) ? $_POST['shdDplct'] : '0';
                    $srcCaller = isset($_POST['srcCaller']) ? $_POST['srcCaller'] : 'NORMAL';
                    if (trim($srcCaller) === "") {
                        $srcCaller = 'NORMAL';
                    }
                    $sbmtdRptID = -1;
                    $sbmtdRptIDDsply = -1;
                    $rptsRptNm = "";
                    $$rqstatusColor = "red";
                    $trnsStatus = "Open";
                    $sbmtdPersonID = -1;
                    $officer = getPrsnFullNm($prsnid);

                    $cnsltnID = -1;
                    $patientIDNo = "";
                    $patientNm = "";
                    $gender = "";
                    $age = "";
                    $bloodGroup = "";
                    $sickleGenotype = "";
                    $vstType = "";
                    $vstNo = "";
                    $appntmntNo = "";
                    $sponsor = "Normal";
                    $appntmntDate = "";
                    $cardNoAndExpiry = "";
                    $patientCmplt = "";
                    $checkInDate = "";
                    $checkOutDate = "";

                    $physicalExam = "";
                    $cnsltnCmnts = "";
                    $patientPersonID = -1;
                    $prsnImgLoc = "";
                    $appntmntRmks = "";
                    $vstId = -1;

                    $docAdmsnInstructions = "";
                    $docAdmsnCheckInDate = "";//date("d-M-Y");
                    $docAdmsnCheckInNoOfDays = "";

                    $ttlSrvsBillPymnt = "0.00";
                    $ttlSrvsBill = "0.00";

                    $resultAppntmntIDs = getAppntmntInvcIDs($pkID);

                    while ($rowBP = loc_db_fetch_array($resultAppntmntIDs)) {
                        $rsltw = get_One_SalesInvcAmounts($rowBP[0]);
                        if ($rw = loc_db_fetch_array($rsltw)) {
                            $ttlSrvsBill = $ttlSrvsBill + (float) $rw[0];
                            $ttlSrvsBillPymnt = $ttlSrvsBillPymnt + $rw[1];
                        }
                    }

                    //$sbmtdScmSalesInvcID = getGnrlRecNm("scm.scm_sales_invc_hdr", "other_mdls_doc_id", "invc_hdr_id", $docNum);



                    $resultCnsltn = getAppointmentCnsltn($pkID);

                    if($vwtyp == 5){
                        $cnsltnID = getInhouseAdmsnCnsltnID($pkID);
                        if($cnsltnID > 0){
                            $resultCnsltn = getCnsltnDetails($cnsltnID);
                        }
                    }

                    while ($rowCnstl = loc_db_fetch_array($resultCnsltn)) {
                        $cnsltnID = $rowCnstl[0];
                        $patientCmplt = $rowCnstl[1];
                        $physicalExam = $rowCnstl[2];
                        $cnsltnCmnts = $rowCnstl[3];
                        $docAdmsnInstructions = $rowCnstl[5];
                        $docAdmsnCheckInDate = $rowCnstl[6];
                        $docAdmsnCheckInNoOfDays = $rowCnstl[7];
                    }

                    if($docAdmsnCheckInNoOfDays == 0 || $docAdmsnCheckInNoOfDays == "0"){
                        $docAdmsnCheckInNoOfDays = "";
                    }

                    $dsplyDcElmnts = "";
                    $dsplyOthrElmnts = "display:none";
                    if ($cnsltnID <= 0) {
                        $dsplyDcElmnts = "display:none";
                        $dsplyOthrElmnts = "";
                    }

                    if ($vwtyp == 5) {
                        $dsplyDcElmnts = "display:none";
                        $dsplyOthrElmnts = "";
                    }

                    $result = get_AppointmentDataDet($pkID);

                    while ($row = loc_db_fetch_array($result)) {
                        $patientIDNo = $row[14];
                        $patientNm = $row[2];
                        $gender = $row[15];
                        $age = getCustAge($row[16]) . " Years " . getMonthAge($row[16]) . " Months";
                        $bloodGroup = get_PrsExtrData($row[19], "1");
                        $sickleGenotype = get_PrsExtrData($row[19], "2");
                        $vstType = $row[17];
                        $vstNo = $row[18];
                        $appntmntNo = $row[1];
                        $sponsor = get_PrsExtrData($row[19], "6");
                        $appntmntDate = $row[3];
                        $cardNo = get_PrsExtrData($row[19], "7");
                        $cardExpiryDate = get_PrsExtrData($row[19], "7");
                        $patientPersonID = $row[19];
                        $prsnImgLoc = $row[20];
                        $checkInDate = $row[21];
                        $checkOutDate = $row[9];
                        $officer = $row[6];
                        $appntmntRmks = $row[8];
                        $trnsStatus = $row[7];
                        $vstId =  $row[10];
                    }

                    $rcrdExst = prsn_Record_Exist($pkID);

                    $nwFileName = "";
                    $temp = explode(".", $prsnImgLoc);
                    $extension = end($temp);
                    if (trim($extension) == "") {
                        $extension = "png";
                    }
                    $ecnptDFlNm = encrypt1($prsnImgLoc, $smplTokenWord1);
                    $nwFileName = $ecnptDFlNm . "." . $extension;
                    $ftp_src = "";
                    if ($patientPersonID <= 0) {
                        $ftp_src = $ftp_base_db_fldr . "/Person/Request/" . $patientPersonID . "." . $extension;
                        if (!($rcrdExst == true && $chngRqstExst > 0 && file_exists($ftp_src))) {
                            $ftp_src = $ftp_base_db_fldr . "/Person/" . $patientPersonID . "." . $extension;
                        }
                    } else {
                        $ftp_src = $ftp_base_db_fldr . "/Person/" . $patientPersonID . "." . $extension;
                    }

                    //echo $ftp_src;
                    ///home/rhoportal/ecng/Person/cLSKLnmKKLSmRFTNnYBvuJQBKnALQJKZKXVXucWKmTcRLTc.png
                    $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                    if (file_exists($ftp_src)) {
                        copy("$ftp_src", "$fullPemDest");
                    } else if (!file_exists($fullPemDest)) {
                        $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                        copy("$ftp_src", "$fullPemDest");
                    }
                    $nwFileName = $tmpDest . $nwFileName;

                    ?>
                    <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;">                                              
                        <div class="row">
                            <div  class="col-md-5" style="padding:0px 3px 0px 3px !important;"> 
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;display:none !important;">
                                    <label for="patientIDNo" class="control-label col-lg-4">Patient ID:</label>
                                    <div  class="col-lg-8">
                                        <input type="text" id="srcPgNo" class="form-control"value="<?php echo $srcPgNo; ?>"/>
                                        <input type="text" class="form-control" aria-label="..." id="patientIDNo" name="patientIDNo" value="<?php echo $patientIDNo; ?>" style="width:100%;" readonly="readonly">   
                                        <input type="hidden" class="form-control" aria-label="..." id="cnsltnID" name="cnsltnID" value="<?php echo $cnsltnID; ?>" style="width:100%;"> 
                                        <input type="hidden" class="form-control" id="appntmntID"  placeholder="Appointment ID" value="<?php echo $pkID; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="patientNm" class="control-label col-lg-4">Patient Name:</label>
                                    <div  class="col-lg-8">
                                        <input type="text" class="form-control" aria-label="..." id="patientNm" name="patientNm" value="<?php echo $patientNm; ?>" style="width:100%;" readonly="readonly">   
                                    </div>
                                </div> 
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="gender" class="control-label col-lg-4">Gender/Age:</label>
                                    <div  class="col-lg-8">
                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="gender" name="gender" value="<?php echo $gender; ?>" style="width:100%;" readonly="readonly">     
                                        </div>
                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="patientAge" name="patientAge" value="<?php echo $age; ?>" style="width:100%;" readonly="readonly">    
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="bloodGroup" class="control-label col-lg-4">Blood Group/Genotype:</label>
                                    <div  class="col-lg-8">
                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="bloodGroup" name="bloodGroup" value="<?php echo $bloodGroup; ?>" style="width:100%;" readonly="readonly">   
                                        </div>
                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="sickleGenotype" name="sickleGenotype" value="<?php echo $sickleGenotype; ?>" style="width:100%;" readonly="readonly">   
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="vstType" class="control-label col-lg-4">Visit Type/No.:</label>
                                    <div  class="col-lg-8">
                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="vstType" name="vstType" value="<?php echo $vstType; ?>" style="width:100%;" readonly="readonly">   
                                        </div>
                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="vstNo" name="vstNo" value="<?php echo $vstNo; ?>" style="width:100%;" readonly="readonly">   
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="appntmntNo" class="control-label col-lg-4">Appointment Date/No.:</label>
                                    <div  class="col-lg-8">
                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="appntmntDate" name="appntmntDate" value="<?php echo $appntmntDate; ?>" style="width:100%;" readonly="readonly">   
                                        </div>
                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="appntmntNo" name="appntmntNo" value="<?php echo $appntmntNo; ?>" style="width:100%;" readonly="readonly">   
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="appntmntNo" class="control-label col-lg-4">Total Bill/Payment:</label>
                                    <div  class="col-lg-8">
                                        <div class="col-md-2" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="thisCrncy" name="thisCrncy" value="GHS" style="width:100%;font-weight:bold !important;font-size:16px !important;" readonly="readonly">   
                                        </div>
                                        <div class="col-md-5" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="ttlInvoice" name="ttlInvoice" value="<?php echo number_format($ttlSrvsBill,2); ?>" style="width:100%;color:red;font-weight:bold !important;font-size:16px !important;" readonly="readonly">   
                                        </div>
                                        <div class="col-md-5" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="ttlPymnt" name="ttlPymnt" value="<?php echo number_format($ttlSrvsBillPymnt,2); ?>" style="width:100%;color:blue;font-weight:bold !important;font-size:16px !important;" readonly="readonly">   
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div  class="col-md-5" style="padding:0px 3px 0px 3px !important;"> 
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="sponsor" class="control-label col-lg-4">Sponsor:</label>
                                    <div  class="col-lg-8">
                                        <input type="text" class="form-control" aria-label="..." id="sponsor" name="sponsor" value="<?php echo $sponsor; ?>" style="width:100%;" readonly="readonly">   
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="cardNo" class="control-label col-lg-4">Card No./Expiry Date:</label>
                                    <div  class="col-lg-8">
                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="cardNo" name="cardNo" value="<?php echo $cardNo; ?>" style="width:100%;" readonly="readonly">   
                                        </div>
                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="expiryDate" name="expiryDate" value="<?php echo $cardExpiryDate; ?>" style="width:100%;" readonly="readonly">   
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="checkInDate" class="control-label col-lg-4">Check In/Out Date:</label>
                                    <div  class="col-lg-8">
                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="checkInDate" name="checkInDate" value="<?php echo $checkInDate; ?>" style="width:100%;" readonly="readonly">   
                                        </div>
                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                            <input type="text" class="form-control" aria-label="..." id="checkOutDate" name="checkOutDate" value="<?php echo $checkOutDate; ?>" style="width:100%;" readonly="readonly">   
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="officer" class="control-label col-lg-4">Service Provider:</label>
                                    <div  class="col-lg-8">
                                        <input type="text" class="form-control" aria-label="..." id="officer" name="officer" value="<?php echo $officer; ?>" style="width:100%;" readonly="readonly">   
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="appntmntRmks" class="control-label col-lg-4">Appointment Remarks:</label>
                                    <div  class="col-lg-8">
                                        <textarea class="form-control" aria-label="..." id="appntmntRmks" name="appntmntRmks" rows="3" style="width:100%;" readonly="readonly" ><?php echo $appntmntRmks; ?></textarea> 
                                    </div>
                                </div>
                            </div>
                            <div  class="col-md-2" style="padding:0px 3px 0px 3px !important;"> 
                                <!--<fieldset class="basic_person_fs1"><legend class="basic_person_lg">Patient Picture</legend>-->
                                <div style="margin-bottom: 10px;">
                                    <img src="<?php echo $nwFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 190px !important; width: auto !important;">                                            
                                </div>                                       
                                <!--</fieldset>-->
                            </div>
                        </div>
                        <div class="row"  style="padding:1px 15px 5px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div> 
                        <div class="row"  style="padding:1px 15px 1px 15px !important;">
                            <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;">
                                <li class="<?php echo $actvTabCnsltn ?>"><a data-toggle="hstrctabajxrptdet" data-rhodata="" href="#cnsltnMainTbPageHstrc" id="cnsltnMainTbPageHstrctab">Consultation</a></li>
                                <li class="<?php echo $actvTabInvstgn ?>"><a data-toggle="hstrctabajxrptdet" data-rhodata="" href="#invstgtnTbPageHstrc" id="invstgtnTbPageHstrctab">Investigations</a></li>
                                <li class="<?php echo $actvTabPhrmcy ?>"><a data-toggle="hstrctabajxrptdet" data-rhodata="" href="#medicationTbPageHstrc" id="medicationTbPageHstrctab">Medication</a></li>
                                <li class="<?php echo $actvTabVitals ?>"><a data-toggle="hstrctabajxrptdet" data-rhodata="" href="#vitalsTbPageHstrc" id="vitalsTbPageHstrctab">Vital Statistics</a></li>
                                <li class="<?php echo $actvTabAdmissions ?>"><a data-toggle="hstrctabajxrptdet" data-rhodata="" href="#inHouseAdmsnTbPageHstrc" id="inHouseAdmsnTbPageHstrctab">Admissions</a></li>
                            </ul>                                    
                            <div class="row">                  
                                <div class="col-md-12">
                                    <div class="custDiv"> 
                                        <div class="tab-content">
                                            <div id="cnsltnMainTbPageHstrc" class="tab-pane fadein <?php echo $actvTabCnsltn ?>" style="border:none !important;">
                                                <div class="row" style="max-height: 245px !important;">
                                                    <div class="col-md-4">
                                                        <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="patientCmplnt" class="control-label">Patient Complaint:</label>
                                                            <div  class="">
                                                                <span><?php echo $patientCmplt; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="physicalExam" class="control-label">Physical Examination:</label>
                                                            <div  class="">
                                                                <span><?php echo $physicalExam; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="row"> 
                                                            <div  class="col-md-12" style="max-height: 203px !important;overflow-y: auto;">
                                                                <table class="table table-striped table-bordered table-responsive" id="diagnosisTable" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th style="min-width:200px;">Diagnosis</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                    <?php
                    $cntr = 0;
                    $curIdx = 0;
                    $result3 = get_AllDiagnosis($cnsltnID);
                    $rptRlID = -1;
                    while ($row3 = loc_db_fetch_array($result3)) {
                        $cntr += 1;
                        ?>
                                                                            <tr id="diagnosisRow_<?php echo $cntr; ?>">                                    
                                                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                <td class="lovtd">
                                                                                    <span><?php echo $row3[2]; ?></span>
                                                                                    <input type="hidden" class="form-control rqrdFld" aria-label="..." id="diagnosisRow<?php echo $cntr; ?>_DiseaseNm" value="<?php echo $row3[2]; ?>" readonly="true">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="diagnosisRow<?php echo $cntr; ?>_DiseaseID" value="<?php echo $row3[1]; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="diagnosisRow<?php echo $cntr; ?>_DiagID" value="<?php echo $row3[0]; ?>">
                                                                                </td> 
                                                                            </tr>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>                     
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:10px !important;">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="cnsltnCmnts" class="control-label">Direct Questions/Comments:</label>
                                                            <div  class="">
                                                                 <span><?php echo $cnsltnCmnts; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="invstgtnTbPageHstrc" class="tab-pane fadein <?php echo $hideNotice; ?> <?php echo $actvTabInvstgn; ?>" style="border:none !important;">   
                                                <div class="row"> 
                                                    <div  class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="invstgtnTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th style="min-width:68px !important;">Requested Lab Item</th>
                                                                    <th>Doctor's Comment</th>
                                                                    <th>In-house?</th>
                                                                    <th>Lab Results</th>
                                                                    <th>Lab Comments</th>
                                                                    <th>Lab Location</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                    <?php
                    $cntr = 0;
                    //echo $cnsltnID;
                    $invstgtnID = -1;

                    $result3 = get_AllInvestigations($cnsltnID, $pkID);

                    while ($row3 = loc_db_fetch_array($result3)) {
                        $cntr += 1;
                        $invstgtnID = $row3[0];
                        ?>
                                                                    <tr id="invstgtnRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                        <td class="lovtd"><span><?php echo $row3[2] . " (" . $row3[7] . ")"; ?>
                                                                            </span>
                                                                        </td>
                                                                        <td class="lovtd"><span><?php echo $row3[3]; ?>
                                                                            </span>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align:center;">
                                                                                <span><?php echo ($row3[12] == "1" ? "Yes" : "No"); ?></span>                                                        
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <textarea class="form-control rqrdFld" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_LabRslt" style="width:100% !important;<?php echo $dsplyOthrElmnts; ?>"><?php echo $row3[4]; ?></textarea>
                                                                            <span style=""><?php echo $row3[4]; ?></span>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <textarea class="form-control" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_LabCmnts" style="width:100% !important;<?php echo $dsplyOthrElmnts; ?>"><?php echo $row3[5]; ?></textarea>
                                                                            <span style=""><?php echo $row3[5]; ?></span>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <textarea class="form-control" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_LabLoc" style="width:100% !important;<?php echo $dsplyOthrElmnts; ?>"><?php echo $row3[6]; ?></textarea>
                                                                            <span style=""><?php echo $row[6]; ?></span>
                                                                        </td>
                                                                    </tr>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                            </tbody>
                                                        </table>
                                                    </div>                     
                                                </div>  
                                            </div>
                                            <div id="medicationTbPageHstrc" class="tab-pane fadein <?php echo $hideNotice; ?> <?php echo $actvTabPhrmcy; ?>" style="border:none !important;">
                                                <div class="row"> 
                                                    <div  class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="medicationTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th style="min-width:150px;">Drug</th>
                                                                    <th style="min-width:310px;">Dosage</th>
                                                                    <th style="min-width:70px;">Form</th>
                                                                    <th>Substitute</br>Allowed?</th>
                                                                    <th style="min-width:150px;">Doctor's Comments</th>
                                                                    <th>Dispense?</th>
                                                                    <th style="min-width:150px;">Pharmacist Comments</th>
                                                                    <th style="min-width:100px;">Admin Times</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $cntr = 0;
                                                                $curIdx = 0;
                                                                $lmtSze = 0;
                                                                $result2 = get_AllMedications($cnsltnID, $pkID);
                                                                $prmID = -1;
                                                                $instruction = "";
                                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                                    $cntr += 1;
                                                                    $instruction = $row2[3] . " " . $row2[4] . " " . $row2[5] . " times a " . $row2[6] . " for " . $row2[7] . " " . $row2[8];
                                                                    ?>
                                                                    <tr id="medicationRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>                                               
                                                                        <td class="lovtd"><span><?php echo $row2[2]; ?></span>                                                         
                                                                        </td>
                                                                        <td class="lovtd"><span><?php echo $instruction; ?></span>                                                         
                                                                        </td>                                                                                              
                                                                        <td class="lovtd"><span><?php echo $row2[9]; ?></span>                                                        
                                                                        </td>  
                                                                        <td class="lovtd" style="text-align:center;"><span><?php echo ($row2[10] == "1" ? "Yes" : "No"); ?></span>                                                         
                                                                        </td>                                            
                                                                        <td class="lovtd"><span><?php echo $row2[11]; ?></span>                                                         
                                                                        </td>
                                                                        <td class="lovtd" style="text-align:center;"><span><?php echo ($row2[12] == "1" ? "Yes" : "No"); ?></span>                                                         
                                                                        </td>                                               
                                                                        <td class="lovtd"><span><?php echo $row2[13]; ?></span>                                                         
                                                                        </td>                                                                                             
                                                                        <td class="lovtd"><span><?php echo $row2[14]; ?></span>                                                        
                                                                        </td>
                                                                    </tr>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                            </tbody>
                                                        </table>
                                                    </div>                     
                                                </div> 
                                            </div>                                                    
                                            <div id="vitalsTbPageHstrc" class="tab-pane fadein <?php echo $hideNotice; ?> <?php echo $actvTabVitals; ?>" style="border:none !important;">
                                                                    <?php
                                                                    $vstID = (int) getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "vst_id", $pkID);
                                                                    $result = get_VisitVitalsData($vstID);
                                                                    $weight = "";
                                                                    $height = "";
                                                                    $bmi = "";
                                                                    $bmi_status = "";
                                                                    $bp_systlc = "";
                                                                    $bp_diastlc = "";
                                                                    $bp_status = "";
                                                                    $pulse = "";
                                                                    $body_tmp = "";
                                                                    $tmp_loc = "";
                                                                    $resptn = "";
                                                                    $oxgn_satn = "";
                                                                    $head_circum = "";
                                                                    $waist_circum = "";
                                                                    $bowel_actn = "";
                                                                    $cmnts = "";


                                                                    while ($row2 = loc_db_fetch_array($result)) {
                                                                        $weight = $row2[1];
                                                                        $height = $row2[2];
                                                                        $bmi = $row2[3];
                                                                        $bmi_status = $row2[4];
                                                                        $bp_systlc = $row2[5];
                                                                        $bp_diastlc = $row2[6];
                                                                        $bp_status = $row2[7];
                                                                        $pulse = $row2[8];
                                                                        $body_tmp = $row2[9];
                                                                        $tmp_loc = $row2[10];
                                                                        $resptn = $row2[11];
                                                                        $oxgn_satn = $row2[12];
                                                                        $head_circum = $row2[13];
                                                                        $waist_circum = $row2[14];
                                                                        $bowel_actn = $row2[15];
                                                                        $cmnts = $row2[16];
                                                                    }
                                                                    ?>
                                                <div class='row' style="padding-left:0px !important;">
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >Weight:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $weight; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >Height:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $height; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class='row'  style="padding-left:0px !important;">
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >Body Mass Index:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $bmi; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >BMI Status:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $bmi_status; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row'  style="padding-left:0px !important;">
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >BP Systolic:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $bp_systlc; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >BP Diastolic:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $bp_diastlc; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class='row' style="padding-left:0px !important;">
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >BP Status:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $bp_status; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >Pulse:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $pulse; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class='row' style="padding-left:0px !important;">
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >Body Temperature:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $body_tmp; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >Temperature Location:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $tmp_loc; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row' style="padding-left:0px !important;">
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >Respiration:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $resptn; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >Oxygen Saturation:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $oxgn_satn; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row' style="padding-left:0px !important;">
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >Head Circumference:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $head_circum; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntNo" class="control-label col-md-6" >Waist Circumference:</label>
                                                            <div  class="col-md-6">
                                                                <span><?php echo $waist_circum; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row' style="padding-left:0px !important;">
                                                    <div  class="col-md-12">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntCmnts" class="control-label col-md-3" >Bowel Action:</label>
                                                            <div  class="col-md-9">
                                                                <span><?php echo $bowel_actn; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row' style="padding-left:0px !important;">
                                                    <div  class="col-md-12">
                                                        <div class="form-group form-group-sm">
                                                            <label for="frmAppntmntCmnts" class="control-label col-md-3" >Remarks:</label>
                                                            <div  class="col-md-9">
                                                                <span><?php echo $cmnts; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="inHouseAdmsnTbPageHstrc" class="tab-pane fadein <?php echo $hideNotice; ?> <?php echo $actvTabAdmissions; ?>" style="border:none !important;">
                                                <div class="row" style="max-height: 245px !important;">
                                                    <div class="col-md-4">
                                                        <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="docAdmsnCheckInDate" class="control-label">Check-In Date:</label>
                                                            <div  class=""><span><?php echo $docAdmsnCheckInDate; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="docAdmsnCheckInNoOfDays" class="control-label">Number of Days:</label>
                                                            <div  class=""><span><?php echo $docAdmsnCheckInNoOfDays; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="docAdmsnInstructions" class="control-label">Doctor's Instructions:</label>
                                                            <div  class=""><span><?php echo $patientCmplt; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="hidden" class="form-control" aria-label="..." id="sltdOrgID" name="sltdOrgID" value="<?php echo $orgID; ?>" readonly="true">
                                                        <input type="hidden" class="form-control" aria-label="..." id="hotlChckinFcltyTypeClinic" name="hotlChckinFcltyTypeClinic" value="Room/Hall" readonly="true">
                                                        <div class="row"> 
                                                            <div  class="col-md-12" style="max-height: 274px !important;overflow-y: auto;">
                                                                <table class="table table-striped table-bordered table-responsive" id="allInptntAdmsnsTable" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th style="max-width:130px;width:130px;">Check-In Date</th>
                                                                            <th style="max-width:130px;width:130px;">Check-Out Date</th>
                                                                            <th style="min-width:120px;">Facility Type</th>
                                                                            <th style="min-width:120px;">Room</th>
                                                                            <th>Document No.</th>
                                                                            <th>&nbsp;</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $cntr = 0;
                                                                        $curIdx = 0;
                                                                        $result3 = get_AllInhouseAdmissions($vstID);
                                                                        $rptRlID = -1;
                                                                        $refChckInId = -1;
                                                                        while ($row3 = loc_db_fetch_array($result3)) {
                                                                            $cntr += 1;
                                                                            $refChckInId = $row3[8];
                                                                            ?>
                                                                             <tr id="allInptntAdmsnsRow_<?php echo $cntr; ?>">                                    
                                                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                <td class="lovtd">
                                                                                    <span style=""><?php echo $row3[1]; ?></span>
                                                                                </td>
                                                                                <td class="lovtd"> 
                                                                                    <span style=""><?php echo $row3[2]; ?></span>
                                                                                </td> 
                                                                                <td class="lovtd"> 
                                                                                    <span style=""><?php echo $row3[3]; ?></span>
                                                                                </td>
                                                                                <td class="lovtd"> 
                                                                                    <span style=""><?php echo $row3[4]; ?></span>
                                                                                </td>
                                                                                <td class="lovtd"><?php echo $row3[5]; ?></td>
                                                                                <td class="lovtd">
                                                                                <?php if ($row3[5] == "" && $row3[1] != "" && $row3[2] != "" && $row3[3] != "" && $row3[4] != "") { ?>
                                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneHotlChckinDocForm2(<?php echo $refChckInId; ?>, 3, 'ShowDialog', 'Check-In', 'CHECK-IN', 'allmodules', 'Room/Hall',<?php echo $row3[6]; ?>,<?php echo $row3[7]; ?>,<?php echo $row3[0]; ?>);" data-toggle="tooltip" data-placement="bottom" title="Create Check-In Document">
                                                                                        <img src="cmn_images/98.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div> 
                <?php
            }
        }
    }
}

