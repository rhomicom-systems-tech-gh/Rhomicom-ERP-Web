<?php
$canAdd = test_prmssns($dfltPrvldgs[8], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[9], $mdlNm);
$canDel = $canEdt;
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);
$pkID = $PKeyID;
$dateStr = getDB_Date_time();
global $usrID;
global $orgID;

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Last Created";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {//Details
                $rowCnt = deleteCnsmrCrdtAnalysis($PKeyID);
                if ($rowCnt > 0) {
                    echo "Credit Analysis Deleted Successfully";
                } else {
                    echo "Failed to Delete Credit Analysis";
                }
                exit();
            } else if ($actyp == 2) {//Details
                $cnsmrCreditId = isset($_POST['cnsmrCreditId']) ? cleanInputData($_POST['cnsmrCreditId']) : -1;
                $noOfPymnt = isset($_POST['noOfPymnt']) ? cleanInputData($_POST['noOfPymnt']) : 1;
                
                $rowCnt = deleteCreditItems($PKeyID, $cnsmrCreditId, $noOfPymnt, $usrID, $dateStr);
                if ($rowCnt > 0) {
                    echo "Line Deleted Successfully";
                } else {
                    echo "Failed to Delete Line";
                }
                exit();
            } else if ($actyp == 4) {//Details
                $rowCnt = deletePostDtdChqs($PKeyID);
                if ($rowCnt > 0) {
                    echo "Line Deleted Successfully";
                } else {
                    echo "Failed to Delete Line";
                }
                exit();
            } else if ($actyp == 500) {//Plan Setup
                $rowCnt = deleteItemPymntPlansSetup($PKeyID);
                if ($rowCnt > 0) {
                    echo "Line Deleted Successfully";
                } else {
                    echo "Failed to Delete Line";
                }
                exit();
            }
        } else if ($qstr == "REVERSE") {
            if ($actyp == 1) {//Details
                $rowCnt = updateCnsmrCrdtAnalysisStatus($PKeyID, 'Incomplete');
                if ($rowCnt > 0) {
                    echo "Credit Analysis Reversed";
                } else {
                    echo "Failed to Reverse Credit Analysis";
                }
                exit();
            }
        } else if ($qstr == "UPDATE") {
            //var_dump($_POST);
            if ($actyp == 1) {//Header
                $cnsmrCreditID = isset($_POST['cnsmrCreditID']) ? cleanInputData($_POST['cnsmrCreditID']) : -1;
                $transactionNo = isset($_POST['transactionNo']) ? cleanInputData($_POST['transactionNo']) : "";
                $custSupId = isset($_POST['custSupId']) ? cleanInputData($_POST['custSupId']) : -1;
                $salaryIncome = isset($_POST['salaryIncome']) ? cleanInputData($_POST['salaryIncome']) : 0.00;
                
                $fuelAllowance = isset($_POST['fuelAllowance']) ? cleanInputData($_POST['fuelAllowance']) : 0.00;
                $rentAllowance = isset($_POST['rentAllowance']) ? cleanInputData($_POST['rentAllowance']) : 0.00;
                $clothingAllowance = isset($_POST['clothingAllowance']) ? cleanInputData($_POST['clothingAllowance']) : 0.00;
                $otherAllowances = isset($_POST['otherAllowances']) ? cleanInputData($_POST['otherAllowances']) : 0.00;
                $loanDeductions = isset($_POST['loanDeductions']) ? cleanInputData($_POST['loanDeductions']) : 0.00;
                $trnsDate = isset($_POST['trnsDate']) ? cleanInputData($_POST['trnsDate']) : date('d-M-Y');
                $pymntOption = isset($_POST['pymntOption']) ? cleanInputData($_POST['pymntOption']) : "";
                $guarantorName = isset($_POST['guarantorName']) ? cleanInputData($_POST['guarantorName']) : "";
                $guarantorContactNos = isset($_POST['guarantorContactNos']) ? cleanInputData($_POST['guarantorContactNos']) : "";
                $guarantorOccupation = isset($_POST['guarantorOccupation']) ? cleanInputData($_POST['guarantorOccupation']) : "";
                $guarantorPlaceOfWork = isset($_POST['guarantorPlaceOfWork']) ? cleanInputData($_POST['guarantorPlaceOfWork']) : "";
                $periodAtWorkplace = isset($_POST['periodAtWorkplace']) ? cleanInputData($_POST['periodAtWorkplace']) : "";
                $periodUomAtWorkplace = isset($_POST['periodUomAtWorkplace']) ? cleanInputData($_POST['periodUomAtWorkplace']) : "Year(s)";
                
                $guarantorEmail = isset($_POST['guarantorEmail']) ? cleanInputData($_POST['guarantorEmail']) : "";
                $ttlPrdtPrice = isset($_POST['ttlPrdtPrice']) ? cleanInputData($_POST['ttlPrdtPrice']) : 0.00;
                $noOfPymnts = isset($_POST['noOfPymnts']) ? cleanInputData($_POST['noOfPymnts']) : 0;
                $ttlInitialDeposit = isset($_POST['ttlInitialDeposit']) ? cleanInputData($_POST['ttlInitialDeposit']) : 0.00;
                $mnthlyRpymnts = isset($_POST['mnthlyRpymnts']) ? cleanInputData($_POST['mnthlyRpymnts']) : 0.00;
                $initDpstType = isset($_POST['initDpstType']) ? cleanInputData($_POST['initDpstType']) : "Automatic";
                $marketerPersonId = isset($_POST['marketerPersonId']) ? cleanInputData($_POST['marketerPersonId']) : -1;

		$optn =  isset($_POST['optn']) ? cleanInputData($_POST['optn']) : '0';
		$salesStoreNmID = isset($_POST['salesStoreNmID']) ? cleanInputData($_POST['salesStoreNmID']) : -1;
                
                $waybillStatus = "";
                $cnt4 = 0;
                $btchNo = "";

                if ($custSupId == -1) {
                    echo '<span style="color:red;font-weight:bold !important;">Please complete all required fields before saving!<br/></span>';
                    exit();
                } else {
                    
                    if ($trnsDate != "") {
                        $trnsDate = cnvrtDMYToYMD($trnsDate);
                    } 

                    /*$result4 = executeSQLNoParams("SELECT count(*) FROM (SELECT DISTINCT order_no FROM chqbkos.chqbkos_chqbook_orders_header WHERE bank_code = '$bankCode' AND order_status = 'Processed'  AND substr(date_received,1,10) = '$rcptDte')tbl1");
                    while ($row = loc_db_fetch_array($result4)) {
                        $cnt4 = (int)$row[0];
                    }

                    if($cnt4 == 0){
                        echo '<span style="color:red;font-weight:bold !important;">No Batch Number exists for this waybill<br/></span>';
                        exit();
                    }*/

                    if ($cnsmrCreditID <= 0) {//CREATE
                        $cnsmrCreditID = getCnsmrCrdtAnalysisID();
                        $waybillStatus = "Incomplete";
                        $rsltCnt = insertCnsmrCrdtAnalysis($cnsmrCreditID, $custSupId, $salaryIncome, $fuelAllowance, 
                                    $rentAllowance, $clothingAllowance, $otherAllowances, $loanDeductions, $trnsDate, 
                                                $marketerPersonId, $pymntOption, $guarantorName, $guarantorContactNos, 
                                                $guarantorOccupation, $guarantorPlaceOfWork, $periodAtWorkplace, $periodUomAtWorkplace, 
                                    $guarantorEmail, $noOfPymnts, $initDpstType, $ttlInitialDeposit, $usrID, $dateStr, $transactionNo, $orgID, $salesStoreNmID);
                        if($rsltCnt > 0){
                            echo json_encode(array("cnsmrCreditID" => $cnsmrCreditID, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                        } else {
                            echo '<span style="color:red;font-weight:bold !important;">Saving Failed!<br/></span>';
                        }
                        exit();
                    } else {//UPDATE
                        $rsltCnt = updateCnsmrCrdtAnalysis($cnsmrCreditID, $custSupId, $salaryIncome, $fuelAllowance, 
                                    $rentAllowance, $clothingAllowance, $otherAllowances, $loanDeductions, $trnsDate, 
                                                $marketerPersonId, $pymntOption, $guarantorName, $guarantorContactNos, 
                                                $guarantorOccupation, $guarantorPlaceOfWork, $periodAtWorkplace, $periodUomAtWorkplace, 
                                    $guarantorEmail, $noOfPymnts, $initDpstType, $ttlInitialDeposit, $usrID, $dateStr, $salesStoreNmID);
                        
                        if($optn === "1"){
                            $rtnCnt = updateCnsmrCrdtAnalysisStatus($cnsmrCreditID, 'Finalized');
                            if($rtnCnt > 0){
                                echo json_encode(array("cnsmrCreditID" => $cnsmrCreditID, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Finalized</span>"));
                            } else {
                                echo '<span style="color:red;font-weight:bold !important;">Failed to Finalize!<br/></span>';
                            }
                        } else {
                            if($rsltCnt > 0){
                                echo json_encode(array("cnsmrCreditID" => $cnsmrCreditID, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                            } else {
                                echo '<span style="color:red;font-weight:bold !important;">Saving Failed!<br/></span>';
                            }
                        }
                        exit();
                    }
                }
            } 
            else if ($actyp == 2) {//Details
                $creditItmId = isset($_POST['creditItmId']) ? cleanInputData($_POST['creditItmId']) : -1;
                $cnsmrCreditId = isset($_POST['cnsmrCreditId']) ? cleanInputData($_POST['cnsmrCreditId']) : -1;
                $noOfPymnt = isset($_POST['noOfPymnt']) ? cleanInputData($_POST['noOfPymnt']) : 1;
                $itemId = isset($_POST['itemId']) ? cleanInputData($_POST['itemId']) : -1;
                $vendorId = isset($_POST['vendorId']) ? cleanInputData($_POST['vendorId']) : -1;
                $itmPymntPlanId = isset($_POST['itmPymntPlanId']) ? cleanInputData($_POST['itmPymntPlanId']) : -1;
                $qty = isset($_POST['qty']) ? cleanInputData($_POST['qty']) : 1;
                $unitSellingPrice = isset($_POST['unitSellingPrice']) ? cleanInputData($_POST['unitSellingPrice']) : 0.00;
                $itmPlanInitDeposit = isset($_POST['itmPlanInitDeposit']) ? cleanInputData($_POST['itmPlanInitDeposit']) : 0.00;
                
                
                
                
                if ($itemId == "-1" || $vendorId == "-1" || $itmPymntPlanId == "-1" || $qty <= 0) {
                    echo '<span style="color:red;font-weight:bold !important;">Please complete all required fields before saving!<br/></span>';
                    exit();
                } else {

                    if ($creditItmId <= 0) {//CREATE
                        $creditItmId = getCreditItemsID();
                        $rsltCnt = insertCreditItems($creditItmId, $cnsmrCreditId, $itemId, $vendorId, $itmPymntPlanId, $qty, $unitSellingPrice, 
                            $itmPlanInitDeposit, $usrID, $dateStr, $noOfPymnt);
                        if ($rsltCnt > 0) {
                            echo json_encode(array("creditItmId" => $creditItmId, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Credit Item Saved Successfully</span>"));
                        } else {
                            echo '<span style="color:red;font-weight:bold !important;">Failed to Save Details!<br/></span>';
                        }
                        exit();
                    } else {//UPDATE
                        $rsltCnt = updateCreditItems($creditItmId, $cnsmrCreditId, $itemId, $vendorId, $itmPymntPlanId, $qty, $unitSellingPrice, 
                            $itmPlanInitDeposit, $usrID, $dateStr, $noOfPymnt);
                        if ($rsltCnt > 0) {
                            echo json_encode(array("creditItmId" => creditItmId, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Credit Item Updated Successfully</span>"));
                        } else {
                            echo '<span style="color:red;font-weight:bold !important;">Failed to Update Details!<br/></span>';
                        }
                        exit();
                    }
                }
            } 
            else if ($actyp == 4) {//Details
                $postdatedChqId = isset($_POST['postdatedChqId']) ? cleanInputData($_POST['postdatedChqId']) : -1;
                $cnsmrCreditId = isset($_POST['cnsmrCreditId']) ? cleanInputData($_POST['cnsmrCreditId']) : -1;
                $chqNo = isset($_POST['chqNo']) ? cleanInputData($_POST['chqNo']) : "";
                $chqIssuerName = isset($_POST['chqIssuerName']) ? cleanInputData($_POST['chqIssuerName']) : "";
                $chqBank = isset($_POST['chqBank']) ? cleanInputData($_POST['chqBank']) : "";
                $amount = isset($_POST['amount']) ? cleanInputData($_POST['amount']) : 1;
                

                if ($chqNo == "" || $chqIssuerName == "" || $chqBank == "" || $amount <= 0) {
                    echo '<span style="color:red;font-weight:bold !important;">Please complete all required fields before saving!<br/></span>';
                    exit();
                } else {

                    if ($postdatedChqId <= 0) {//CREATE
                        $postdatedChqId = getPostDtdChqsID();
                        $rsltCnt = insertPostDtdChqs($postdatedChqId, $cnsmrCreditId, $chqNo, $chqIssuerName, $chqBank, $amount, $usrID, $dateStr);
                        if ($rsltCnt > 0) {
                            echo json_encode(array("postdatedChqId" => $postdatedChqId, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Cheque Details Saved Successfully</span>"));
                        } else {
                            echo '<span style="color:red;font-weight:bold !important;">Failed to Save Details!<br/></span>';
                        }
                        exit();
                    } else {//UPDATE
                        $rsltCnt = updatePostDtdChqs($postdatedChqId, $cnsmrCreditId, $chqNo, $chqIssuerName, $chqBank, $amount, $usrID, $dateStr);
                        if ($rsltCnt > 0) {
                            echo json_encode(array("postdatedChqId" => $postdatedChqId, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Cheque Details Updated Successfully</span>"));
                        } else {
                            echo '<span style="color:red;font-weight:bold !important;">Failed to Update Details!<br/></span>';
                        }
                        exit();
                    }
                }
            } else if ($actyp == 500){
                $slctdItmPymntPlansSetup = isset($_POST['slctdItmPymntPlansSetup']) ? cleanInputData($_POST['slctdItmPymntPlansSetup']) : "";

                $vldtyUpdtCnt = 0;
                $rsltCrtCnt = 0;
                $rsltUpdtCnt = 0;

                if (1 > 0) {
                    $dateStr = getDB_Date_time();
                    $recCntInst = 0;
                    $recCntUpdt = 0;

                    if (trim($slctdItmPymntPlansSetup, "|~") != "") {

                        $variousRows = explode("|", trim($slctdItmPymntPlansSetup, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 9) {
                                $itmPymntPlansSetupID = (int) (cleanInputData1($crntRow[0]));
                                $planName = cleanInputData1($crntRow[1]);
				$planPriceType = cleanInputData1($crntRow[2]);
                                $planPrice = (float)cleanInputData1($crntRow[3]);
                                $noOfPymnts = (float) cleanInputData1($crntRow[4]);
				$initDpstType = cleanInputData1($crntRow[5]);
                                $initDpst = (float) cleanInputData1($crntRow[6]);
				$orderNo = (float)cleanInputData1($crntRow[7]);
                                $isEnbld = cleanInputData1($crntRow[8]);
                                
                                

                                if ($itmPymntPlansSetupID > 0) {
                                    $recCntUpdt = $recCntUpdt + updateItemPymntPlansSetup($itmPymntPlansSetupID, $planName, $noOfPymnts, $planPriceType, $planPrice, $initDpstType, $initDpst, $orderNo, $isEnbld, $usrID, $dateStr);
                                } else {
                                    $itmPymntPlansSetupID = getItemPymntPlansSetupID();
                                    $recCntInst = $recCntInst + insertItemPymntPlansSetup($itmPymntPlansSetupID, $planName, $noOfPymnts, $planPriceType, $planPrice, $initDpstType, $initDpst, $orderNo, $isEnbld, $usrID, $dateStr);
                                }
                            }
                        }
                    }

                    echo json_encode(array("recCntInst" => $recCntInst, "recCntUpdt" => $recCntUpdt));
                    exit();
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . '<br/>Please complete all required fields before proceeding!<br/></div>';
                    exit();
                }
            } 
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Credit Analysis</span>
				</li>
                               </ul>
                              </div>";
                //Stockable Item List
                $total = get_CnsmrCrdtAnalysisTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_CnsmrCrdtAnalysis($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-4";
                ?>
                <form id='allCnsmrCrdtAnalysisForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allCnsmrCrdtAnalysisSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllCnsmrCrdtAnalysis(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allCnsmrCrdtAnalysisPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCnsmrCrdtAnalysis('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCnsmrCrdtAnalysis('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allCnsmrCrdtAnalysisSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Customer Name", "Transaction No.", "Marketer");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allCnsmrCrdtAnalysisDsplySze" style="min-width:70px !important;">                            
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
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allCnsmrCrdtAnalysisSortBy">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Last Created", "Value");
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
                        <div class="<?php echo $colClassType1; ?>">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllCnsmrCrdtAnalysis('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllCnsmrCrdtAnalysis('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>                   
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                        <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <?php if ($canAdd === true) { ?>                   
                                <button type="button" class="btn btn-default btn-sm" onclick="getOneCnsmrCrdtAnalysisForm(-1,  1,  'ShowDialog');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Analysis
                                </button>
				<button type="button" class="btn btn-default btn-sm" onclick="getOneItmPymntPlansSetupForm(-1, 500, 'ShowDialog');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Payment Plans Setup
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allCnsmrCrdtAnalysisTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>&nbsp;</th>
                                        <th>Customer Name</th>
                                        <th>Transaction No.</th>
                                        <th>Total Product</br>Price(GHS)</th>
                                        <th>Affordability</br>(GHS)</th>
                                        <th>No. Of</br>Payment</th>
                                        <th>Monthly</br>Repayment</br>(GHS)</th>
                                        <th>Transaction</br>Date</th>
                                        <th>Marketer</th>
					<th>Status</th>
                                        <th>&nbsp;</th>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        ?>
                                        <tr id="allCnsmrCrdtAnalysisRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneCnsmrCrdtAnalysisForm(<?php echo $row[0]; ?>, 1, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd"> 
                                                <?php echo $row[1]; ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allCnsmrCrdtAnalysisRow<?php echo $cntr; ?>_CnsmrCreditID" value="<?php echo $row[0]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php echo number_format((float) $row[3], 2); ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php echo number_format((float) $row[4], 2); ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo  $row[5]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php echo number_format((float) $row[6], 2); ?></td>                                            
                                            <td class="lovtd"><?php echo $row[7]; ?></td>  
                                            <td class="lovtd"><?php echo $row[8]; ?></td>
					    <td class="lovtd"><?php echo $row[27]; ?></td>
                                            <?php if ($canDel === true) { ?>
                                                <td class="lovtd">
						    <?php if ($row[27] == "Incomplete") { ?>
                                                    	<button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delCnsmrCrdtAnalysis('allCnsmrCrdtAnalysisRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item">
                                                        	<img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    	</button>
						    <?php } else { ?>
                                                        &nbsp;
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                    echo urlencode(encrypt1(($row[0] . "|scm.scm_cnsmr_credit_analys|cnsmr_credit_id"),
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
                </form>
                <?php
            }
            else if ($vwtyp == 1) {//Order Form (Header/Details)
                //echo "Order Form (Header/Details)";

                /* Add */
                $cnsmrCreditID = -1;
                $custSupId = -1;
                $custSupName = "";
                $salaryIncome = 0.00;
                $fuelAllowance = 0.00;
                $rentAllowance = 0.00;
                $clothingAllowance = 0.00;
                $otherAllowances = 0.00;
                $debtServiceRatio = 0.00;
                $loanDeductions = 0.00;
                $affordabilityAmnt = 0.00;
                $trnsDate = date("d-M-Y");
                $marketerPerson = "";
                $pymntOption = "";
                $guarantorName = "";
                $guarantorContactNos = "";
                $guarantorOccupation = "";
                $guarantorPlaceOfWork = "";
                $periodAtWorkplace = "";
                $periodUomAtWorkplace = "Year(s)";
                $guarantorEmail = "";
                $ttlPrdtPrice = 0.00;
                $noOfPymnts = "";
                $ttlInitialDeposit = 0.00;
                $mnthlyRpymnts = 0.00;
                $initDpstType = "Automatic";
                $marketerPersonId = -1;
                $ttlEarnings = 0.00;
                $planType = "";
                
                $rpymntClr = "blue";
		$invHdrId = -1;
                $invHdrNo = "";
                $storeID = -1;
                $storeNm = "";
                
                
                
                
                $trnsStatus = "Incomplete";
                //$detCnt = 0; 
                $chqReportName = "";
                
                $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                }
                $dte = date('ymd');

                $docTypPrfx = 'CCA';
                $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("scm.scm_cnsmr_credit_analys", "transaction_no",
                                                                "cnsmr_credit_id", $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);	
                $transactionNo = $gnrtdTrnsNo;
                
                

                $result = get_CnsmrCrdtAnalysisDet($pkID);
                while ($row = loc_db_fetch_array($result)) {
                    $cnsmrCreditID = $row[0];
                    $custSupId = (int)$row[1];
                    $custSupName = $row[2];
                    $salaryIncome = (float)$row[3];
                    $fuelAllowance =(float)$row[4];
                    $rentAllowance = (float)$row[5];
                    $clothingAllowance = (float)$row[6];
                    $otherAllowances = (float)$row[7];
                    $debtServiceRatio = (float)$row[8];
                    $loanDeductions = (float)$row[9];
                    $affordabilityAmnt = (float)$row[10];
                    $trnsDate = $row[11];
                    $marketerPerson = $row[12];
                    $pymntOption = $row[13];
                    $guarantorName = $row[14];
                    $guarantorContactNos = $row[15];
                    $guarantorOccupation = $row[16];
                    $guarantorPlaceOfWork = $row[17];
                    $periodAtWorkplace = $row[18];
                    $periodUomAtWorkplace = $row[19];
                    $guarantorEmail = $row[20];
                    $ttlPrdtPrice = (float)$row[21];
                    $noOfPymnts = (float)$row[22];
                    $ttlInitialDeposit = (float)$row[23];
                    $mnthlyRpymnts = (float)$row[24];
                    $initDpstType = $row[25];
                    $marketerPersonId = (int)$row[26];
                    $transactionNo = $row[27];
                    $ttlEarnings =  $salaryIncome + $fuelAllowance + $rentAllowance +
                            $clothingAllowance + $otherAllowances;
		    $trnsStatus = $row[28];
		    $invHdrId = (int)getGnrlRecNm("scm.scm_cnsmr_credit_analys", "cnsmr_credit_id", "src_invc_hdr_id", $cnsmrCreditID);
                    $invHdrNo = getGnrlRecNm("scm.scm_sales_invc_hdr", "invc_hdr_id", "invc_number", $invHdrId);
                    $storeID = (int)getGnrlRecNm("scm.scm_cnsmr_credit_analys", "cnsmr_credit_id", "src_store_id", $cnsmrCreditID);
                    $storeNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $storeID);
                }
                
                if($mnthlyRpymnts > $affordabilityAmnt){
                    $rpymntClr = "red";
                }
                
                
                
                
                $chqReportName = "ChequePoint General Waybill";	

                $detCnt = getCnsmrCrdtItemCount($cnsmrCreditID);
                $sbmtdTrnsHdrID = $pkID;
                $voidedTrnsHdrID = -1;
                $rqstatusColor = "red";
                $ttlColor = "blue";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                
                if($detCnt > 0){
                    $mkReadOnly = "readonly";
                }

                $trnsTtl = 0.00;

                ?>
                <div class="row" style="margin: 0px 0px 0px 0px !important;" >
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/>                    
                </div>                    
                <div class="">
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv" style="border:none !important; padding-top:0px !important;"> 
                                <div class="tab-content">
                                    <div id="prflCMHomeEDT" class="tab-pane fadein active" style="border:none !important;">  
                                        <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
                                            <div class="col-md-4" style="padding:0px 1px 0px 15px !important;float:left;">
                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                    <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                                </button>
						<?php if ($invHdrNo != "") { ?>
                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                    <span style="font-weight:bold;">Sales Invoice: </span><span style="color:red;font-weight: bold;"><?php echo $invHdrNo; ?></span>
                                                </button>
                                                <?php } ?>
                                            </div>
					    <div class="col-md-4" style="padding:0px 10px 0px 10px !important;"> 
                                                <div class="form-group">
                                                    <label for="salesStoreNm" class="control-label col-md-4" style="padding:5px 10px 0px 13px !important;">Sales Store:</label>
                                                    <div  class="col-md-8" style="padding:0px 23px 0px 11px !important;">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="salesStoreNm" name="salesStoreNm" value="<?php echo $storeNm; ?>" readonly="true">
                                                            <input class="form-control" type="hidden" id="salesStoreNmID" value="<?php echo $storeID; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sales Stores', 'allOtherInputOrgID', '', '', 'radio', true, '', 'salesStoreNmID', 'salesStoreNm', 'clear', 0, '');" data-toggle="tooltip" title="">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
						<div style="float:right;">
                                                <?php if ($trnsStatus == "Incomplete" || $trnsStatus == "Rejected") { ?>                                                    
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveCnsmrCrdtAnalysis(0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button> 
                                                    
                                                    <?php
                                                } if ($detCnt > 0 && $trnsStatus == "Incomplete") { ?>                                                    
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveCnsmrCrdtAnalysis(1);"><img src="cmn_images/valid_1.jpg" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize&nbsp;</button> 

                                                    <?php
                                                } else if ($trnsStatus == "Finalized") {
                                                    
                                                    
                                                    $reportTitle = "Waybill";
                                                    $reportName = $chqReportName;
                                                    $rptID = getRptID($reportName);
                                                    $prmID1 = getParamIDUseSQLRep("{:waybillHdrId}", $rptID);
                                                    $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                    //$invcID = $sbmtdTrnsHdrID;
                                                    $paramRepsNVals = $prmID1 . "~" . $cnsmrCreditID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                                    $paramStr = urlencode($paramRepsNVals);
                                                    
                                                    if ($trnsStatus == "Finalized" && $invHdrNo == "") { ?>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="reverseCnsmrCrdtAnalysis(<?php echo $cnsmrCreditID; ?>);"><img src="cmn_images/back_2.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                        Reverse&nbsp;
                                                    </button>
						    <?php if ($invHdrNo == "") { ?>
                                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Sales Invoice', 'NO', 'SALES', <?php echo $cnsmrCreditID; ?>);" data-toggle="tooltip" data-placement="bottom" title="Add New Sales Invoice">
                                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        SI
                                                    </button> 
                                                    <?php } ?>
                                                    <!--<button type="button" class="btn btn-default btn-sm" style="height:30px;" title="Print Waybill" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');">
                                                        <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                        Waybill
                                                    </button>-->
                                                    <?php } 
                                                }
                                                ?>
						</div>
                                            </div>
                                        </div>                                          
                                        <form class="form-horizontal" id="cnsmrCrdtAnalysisForm">
                                            <div class="row"><!-- ROW 1 -->
                                                <div class="col-lg-12">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Transaction Header</legend>
                                                        <div class="col-lg-4">
                                                            <input class="form-control" id="cnsmrCreditID" type = "hidden" placeholder="Credit ID" value="<?php echo $cnsmrCreditID; ?>"/>
                                                            <div class="form-group form-group-sm">
                                                                <label for="transactionNo" class="control-label col-md-4">Transaction No:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="transactionNo" type = "text" placeholder="" value="<?php echo $transactionNo; ?>" readonly/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="trnsDate" class="control-label col-md-4">Date:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="trnsDate" value="<?php echo $trnsDate; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="custSupName" class="control-label col-md-4">Customer:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="custSupName" value="<?php echo $custSupName; ?>" readonly>
                                                                        <input type="hidden" id="custSupId" value="<?php echo $custSupId; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customers', '', '', '', 'radio', true, '', 'custSupId', 'custSupName', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="marketerPerson" class="control-label col-md-4">Marketer:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="marketerPerson" value="<?php echo $marketerPerson; ?>" readonly>
                                                                        <input type="hidden" id="marketerPersonId" value="<?php echo $marketerPersonId; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'YMP Marketers', '', '', '', 'radio', true, '', 'marketerPersonId', 'marketerPerson', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="pymntOption" class="control-label col-md-4">Payment Optn:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="pymntOption" value="<?php echo $pymntOption; ?>" readonly>
                                                                        <input type="hidden" id="pymntOptionID" value="<?php echo $pymntOption; ?>">  
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'YMP Payment Options', '', '', '', 'radio', true, '', 'pymntOptionID', 'pymntOption', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="ttlInitialDeposit" class="control-label col-md-4">Initial Deposit:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="ttlInitialDeposit" type = "number" min="0.00" placeholder="" value="<?php echo $ttlInitialDeposit; ?>" readonly/> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="ttlPrdtPrice" class="control-label col-md-4" style="color:blue;font-weight: bold;">Total Amount:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="ttlPrdtPrice" type = "number" min="0.00" placeholder="" value="<?php echo $ttlPrdtPrice; ?>" readonly/> 
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="noOfPymnts" class="control-label col-md-4" style="color:blue;font-weight: bold;">No. Of Payments:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="noOfPymnts" type = "number" min="0" placeholder="" value="<?php echo $noOfPymnts; ?>" <?php echo $mkReadOnly; ?>/> 
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="mnthlyRpymnts" class="control-label col-md-4" style="color:<?php echo $rpymntClr; ?>;font-weight: bold;">Repayment(GHS):</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="mnthlyRpymnts" type = "number" min="0.00" style="color:<?php echo $rpymntClr; ?>;font-weight: bold;" placeholder="" value="<?php echo $mnthlyRpymnts; ?>" readonly/> 
                                                                </div>
                                                            </div> 
                                                        </div>                                                            
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row"><!-- ROW 2 -->
                                                <div class="col-lg-12">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Income, Allowances and Loans</legend>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="salaryIncome" class="control-label col-md-4">Salary Income:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="salaryIncome" type = "number" min="0.00" placeholder="" value="<?php echo $salaryIncome; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="fuelAllowance" class="control-label col-md-4">Fuel Alwnc:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="fuelAllowance" type = "number" min="0.00" placeholder="" value="<?php echo $fuelAllowance; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="rentAllowance" class="control-label col-md-4">Rent Alwnc:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="rentAllowance" type = "number" min="0.00" placeholder="" value="<?php echo $rentAllowance; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="clothingAllowance" class="control-label col-md-4">Clothing Alwnc:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="clothingAllowance" type = "number" min="0.00" placeholder="" value="<?php echo $clothingAllowance; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="otherAllowances" class="control-label col-md-4">Other Alwncs:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="otherAllowances" type = "number" min="0.00" placeholder="" value="<?php echo $otherAllowances; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="ttlEarnings" class="control-label col-md-4" style="color:green;font-weight: bold;">Total Earnings:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="ttlEarnings" type = "number" min="0.00" style="color:green;font-weight: bold;" placeholder="" value="<?php echo $ttlEarnings; ?>" readonly/> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="debtServiceRatio" class="control-label col-md-4" style="color:blue;font-weight: bold;">Service Ratio:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="debtServiceRatio" type = "number" min="0.00" placeholder="" value="<?php echo $debtServiceRatio; ?>" readonly/> 
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="loanDeductions" class="control-label col-md-4" style="color:green;font-weight: bold;">Loan Deductn:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="loanDeductions" type = "number" min="0.00" style="color:green;font-weight: bold;" placeholder="" value="<?php echo $loanDeductions; ?>"/> 
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="affordabilityAmnt" class="control-label col-md-4" style="color:blue;font-weight: bold;">Affordability:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="affordabilityAmnt" type = "number" min="0.00" placeholder="" value="<?php echo $affordabilityAmnt; ?>" readonly/> 
                                                                </div>
                                                            </div>  
                                                        </div>                                                            
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row"><!-- ROW 3 -->
                                                <div class="col-lg-12">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Guarantor Details</legend>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="guarantorName" class="control-label col-md-4">Full Name:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="guarantorName" type = "text" placeholder="" value="<?php echo $guarantorName; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="guarantorContactNos" class="control-label col-md-4">Contact Nos.:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="guarantorContactNos" type = "text" placeholder="" value="<?php echo $guarantorContactNos; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="guarantorOccupation" class="control-label col-md-4">Occupation:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="guarantorOccupation" value="<?php echo $guarantorOccupation; ?>" readonly>
                                                                        <input type="hidden" id="guarantorOccupationID" value="<?php echo $guarantorOccupation; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Jobs/Professions/Occupations', '', '', '', 'radio', true, '', 'guarantorOccupationID', 'guarantorOccupation', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="guarantorPlaceOfWork" class="control-label col-md-4">Work Place:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="guarantorPlaceOfWork" value="<?php echo $guarantorPlaceOfWork; ?>" readonly>
                                                                        <input type="hidden" id="guarantorPlaceOfWorkID" value="<?php echo $guarantorPlaceOfWork; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Companies/Work Places', '', '', '', 'radio', true, '', 'guarantorPlaceOfWorkID', 'guarantorPlaceOfWork', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="periodAtWorkplace" class="control-label col-md-4">Work Period:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group col-md-12">
                                                                        <div  class="col-md-4" style="padding-left:0px !important;">
                                                                            <input class="form-control" id="periodAtWorkplace" type = "number" min="0" placeholder="" value="<?php echo $periodAtWorkplace; ?>"/>
                                                                        </div>
                                                                        <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                                            <select class="form-control" id="periodUomAtWorkplace" >
                                                                                <?php 
                                                                                
                                                                                $sltdMnths = "";
                                                                                $sltdYrs = "";
                                                                                
                                                                                if($periodUomAtWorkplace == "Month(s)"){
                                                                                    $sltdMnths = "selected=\"selected\"";
                                                                                } else {
                                                                                    $sltdYrs = "selected=\"selected\"";
                                                                                }
                                                                                
                                                                                ?>
                                                                                <option value="Month(s)" <?php echo $sltdMnths; ?>>Month(s)</option>
                                                                                <option value="Year(s)" <?php echo $sltdYrs; ?>>Year(s)</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>                                                            
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="guarantorEmail" class="control-label col-md-4">Email Address:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="guarantorEmail" type = "text" placeholder="" value="<?php echo $guarantorEmail; ?>"/> 
                                                                </div>
                                                            </div> 
                                                        </div>                                                            
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row"><!-- ROW 4 -->
                                                <div class="col-lg-12">
                                                    <fieldset class="basic_person_fs1" style="margin-top:5px !important;"><legend class="basic_person_lg">Credit Items</legend>
                                                        <div class="row"><!-- ROW 2 -->
                                                            <div class="col-lg-12">
                                                                <div  class="col-md-12">
                                                                    <div class="row"><!-- ROW 3 -->
                                                                        <div class="col-lg-12">
                                                                            <div style="float:left; margin-bottom: 5px !important;">
                                                                                <?php
                                                                                if ($trnsStatus == "Incomplete" || $trnsStatus == "Received" || $trnsStatus == "Rejected") {
                                                                                    ?>
                                                                                    <div  class="col-md-4" style="padding-left: 0px !important;">
                                                                                        <button type="button" class="btn btn-default btn-bg" onclick="viewCreditItemsRecForm(-1,'Add Credit Items');"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Add Item</button>   
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                                ?>                                                                                
                                                                            </div>                                           
                                                                        </div>
                                                                    </div>
                                                                    <table id="disbmntDetTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                        <thead>
                                                                            <tr>
                                                                                <!--<th>...</th>-->
                                                                                <th>...</th>
                                                                                <th>No.</th>
                                                                                <th>Product</th>
                                                                                <th>Vendor</th>
                                                                                <th>Payment Plan</th>
                                                                                <th>Quantity</th>
                                                                                <th>Selling</br>Price</th>
                                                                                <th>Initial</br>Deposit</th>
                                                                                <th>...</th>
                                                                                <th>...</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="disbmntDetTblTbody">
                                                                            <?php
                                                                            $reportTitle = "Cheque Book";
                                                                            $reportName = "Teller Transaction Receipt";
                                                                            $rptID = getRptID($reportName);
                                                                            $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                                                                            $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);

                                                                            $cnta = 0;
                                                                            $result2 = get_CreditItems($pkID);
                                                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                                                $cnta = $cnta + 1;
                                                                                $creditItmId = $row2[0];
                                                                                $prdtDesc = $row2[2];
                                                                                $prdtVendor = $row2[4];
                                                                                $pymntPlan = $row2[6];
                                                                                $prdtQty = $row2[7];
                                                                                $sllnPrice = $row2[9];
                                                                                $initDpst = $row2[8];
                                                                                
                                                                                $invcID = $creditItmId;
                                                                                $paramRepsNVals = $prmID1 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                                                                $paramStr = urlencode($paramRepsNVals);
                                                                                ?>

                                                                                <tr id="disbmntDetTblAddRow_<?php echo $row2[0]; ?>">
                                                                                    <!--<td class="lovtd"><button type="button" title="Print Cheque Book" class="btn btn-default btn-sm" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="padding:2px !important;">
                                                                                        <img src="cmn_images/printer-icon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button></td>-->
                                                                                    <?php if ($trnsStatus == "Incomplete") { ?>
                                                                                    <td class="lovtd"><button type="button" title="Edit Cheque Book" class="btn btn-default btn-sm" onclick="viewCreditItemsRecForm(<?php echo $creditItmId; ?>,'Edit Credit Item');" style="padding:2px !important;">
                                                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button></td>
                                                                                    <?php } else { ?>
                                                                                        <td class="lovtd">...</td>    
                                                                                    <?php } ?>
                                                                                    <td class="lovtd"><?php echo $cnta; ?></td>
                                                                                    <td class="lovtd"><?php echo $prdtDesc; ?></td>
                                                                                    <td class="lovtd"><?php echo $prdtVendor; ?></td>
                                                                                    <td class="lovtd"><?php echo $pymntPlan; ?></td>
                                                                                    <td class="lovtd"><?php echo $prdtQty; ?></td>
                                                                                    <td class="lovtd"><?php echo $sllnPrice; ?></td>
                                                                                    <td class="lovtd"><?php echo $initDpst; ?></td>
                                                                                    <td class="lovtd">
                                                                                        <?php
                                                                                        if ($trnsStatus == "Incomplete" || $trnsStatus == "Finalized") {
                                                                                            ?>
                                                                                            <button type="button" title="View QRC Code" class="btn btn-default btn-sm" onclick="viewWaybillQrCodeForm(<?php echo $creditItmId; ?>)" style="padding:2px !important;">
                                                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                            <?php
                                                                                        } else {
                                                                                            echo "...";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <?php
                                                                                        if ($trnsStatus == "Incomplete" && $canDel === true) {
                                                                                            ?>
                                                                                            <button type="button" title="Delete Line" class="btn btn-default btn-sm" onclick="deleteCreditItem(<?php echo $creditItmId; ?>,<?php echo $cnsmrCreditID; ?>, <?php echo $noOfPymnts; ?>);" style="padding:2px !important;">
                                                                                                <img src="cmn_images/delete_img.gif" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                            <?php
                                                                                        } else {
                                                                                            echo "...";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td style="display:none;"><?php echo $creditItmId; ?></td>
                                                                                </tr>                                                                                    
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </fieldset>
                                                </div>
                                            </div>  
                                            <?php if ($pymntOption !== "Cash") { ?>
                                            <div class="row"><!-- ROW 5 -->
                                                <div class="col-lg-12">
                                                    <fieldset class="basic_person_fs1" style="margin-top:5px !important;"><legend class="basic_person_lg">Post-Dated Cheques</legend>
                                                        <div class="row"><!-- ROW 2 -->
                                                            <div class="col-lg-12">
                                                                <div  class="col-md-12">
                                                                    <div class="row"><!-- ROW 3 -->
                                                                        <div class="col-lg-12">
                                                                            <div style="float:left; margin-bottom: 5px !important;">
                                                                                <?php
                                                                                if ($trnsStatus == "Incomplete" || $trnsStatus == "Received" || $trnsStatus == "Rejected") {
                                                                                    ?>
                                                                                    <button type="button" class="btn btn-default btn-sm" onclick="viewPostdatedChqRecForm(-1,'Add Cheque Data');"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Add Cheque</button>   
                                                                                    <?php
                                                                                }
                                                                                ?>                                                                                
                                                                            </div>                                           
                                                                        </div>
                                                                    </div>
                                                                    <table id="disbmntDetTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                        <thead>
                                                                            <tr>
                                                                                <!--<th>...</th>-->
                                                                                <th>...</th>
                                                                                <th>No.</th>
                                                                                <th>Cheque No.</th>
                                                                                <th>Issuer</th>
                                                                                <th>Bank Name</th>
                                                                                <th>Amount</th>
                                                                                <th>...</th>
                                                                                <th>...</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="disbmntDetTblTbody">
                                                                            <?php
                                                                            $reportTitle = "Cheque Book";
                                                                            $reportName = "Teller Transaction Receipt";
                                                                            $rptID = getRptID($reportName);
                                                                            $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                                                                            $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);

                                                                            $cnta = 0;
                                                                            $result3 = get_PostDtdChqs($pkID);
                                                                            while ($row2 = loc_db_fetch_array($result3)) {
                                                                                $cnta = $cnta + 1;
                                                                                $postdated_chq_id= $row2[0];
                                                                                $chq_no= $row2[1];
                                                                                $chq_issuer_name = $row2[2];
                                                                                $chq_bank = $row2[3];  
                                                                                $amount = $row2[4];
                                                                                
                                                                                $invcID = $postdated_chq_id;
                                                                                $paramRepsNVals = $prmID1 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                                                                $paramStr = urlencode($paramRepsNVals);
                                                                                ?>

                                                                                <tr id="disbmntDetTblAddRow_<?php echo $row2[0]; ?>">
                                                                                    <!--<td class="lovtd"><button type="button" title="Print Cheque Book" class="btn btn-default btn-sm" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="padding:2px !important;">
                                                                                        <img src="cmn_images/printer-icon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button></td>-->
                                                                                    <?php if ($trnsStatus == "Incomplete") { ?>
                                                                                    <td class="lovtd"><button type="button" title="Edit Cheque Book" class="btn btn-default btn-sm" onclick="viewPostdatedChqRecForm(<?php echo $postdated_chq_id; ?>,'Edit Cheque Data');" style="padding:2px !important;">
                                                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button></td>
                                                                                    <?php } else { ?>
                                                                                        <td class="lovtd">...</td>    
                                                                                    <?php } ?>
                                                                                    <td class="lovtd"><?php echo $cnta; ?></td>
                                                                                    <td class="lovtd"><?php echo $chq_no; ?></td>
                                                                                    <td class="lovtd"><?php echo $chq_issuer_name; ?></td>
                                                                                    <td class="lovtd"><?php echo $chq_bank; ?></td>
                                                                                    <td class="lovtd"><?php echo $amount; ?></td>
                                                                                    <td class="lovtd">
                                                                                        <?php
                                                                                        if ($trnsStatus == "Incomplete" || $trnsStatus == "Finalized") {
                                                                                            ?>
                                                                                            <button type="button" title="View QRC Code" class="btn btn-default btn-sm" onclick="viewWaybillQrCodeForm(<?php echo $postdated_chq_id; ?>)" style="padding:2px !important;">
                                                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                            <?php
                                                                                        } else {
                                                                                            echo "...";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <?php
                                                                                        if ($trnsStatus == "Incomplete" && $canDel === true) {
                                                                                            ?>
                                                                                            <button type="button" title="Delete Line" class="btn btn-default btn-sm" onclick="deletePostdatedChqId(<?php echo $postdated_chq_id; ?>,<?php echo $cnsmrCreditID; ?>);" style="padding:2px !important;">
                                                                                                <img src="cmn_images/delete_img.gif" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                            <?php
                                                                                        } else {
                                                                                            echo "...";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td style="display:none;"><?php echo $creditItmId; ?></td>
                                                                                </tr>                                                                                    
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </fieldset>
                                                </div>
                                            </div>   
                                            <?php  } ?>
                                        </form>  
                                    </div>
                                    <div id="prflCMDataEDT" class="tab-pane fade" style="border:none !important;"></div>
                                    <div id="prflCMAttchmntEDT" class="tab-pane fade" style="border:none !important;"></div>      
                                </div>                        
                            </div>                         
                        </div>                
                    </div>          
                </div>
                <?php
            } 
            else if ($vwtyp == 2) {//CREDIT ITEMS
                
                $creditItmId = isset($_POST['creditItmId']) ? cleanInputData($_POST['creditItmId']) : -1;
                $cnsmrCreditId = isset($_POST['cnsmrCreditID']) ? cleanInputData($_POST['cnsmrCreditID']) : -1;
                $noOfPymnt = isset($_POST['noOfPymnt']) ? cleanInputData($_POST['noOfPymnt']) : 1;
                
                $result = getCreditItemsDet($creditItmId);
                $itemId  = -1;
                $itemDesc  = "";
                $vendorId = -1;
                $vendorName = "";
                $itmPymntPlanId = -1;
                $planName = "";
                $unitSellingPrice = 0.00;
                $qty = "";
                $itmPlanInitDeposit = "";
                
                $chqBkTotal = "";
                $trnsStatus = "";

                //$result = get_SignatoryDets($acctSignId, $acctID);
                while ($row2 = loc_db_fetch_array($result)) {
                    $creditItmId = $row2[0];
                    $itemId  = $row2[1];
                    $itemDesc  = $row2[2];
                    $vendorId = $row2[3];
                    $vendorName = $row2[4];
                    $itmPymntPlanId = $row2[5];
                    $planName = $row2[6];
                    $qty = $row2[7];
                    $unitSellingPrice = $row2[9];
                    $itmPlanInitDeposit = $row2[8];
                }
                ?>
                <form class="form-horizontal" id="creditItemsForm">
                    <input class="form-control" id="frmCreditItmId" type = "hidden" placeholder="Credit ID" value="<?php echo $creditItmId; ?>"/>
                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                    <div class="row"><!-- ROW 1 -->
                        <div class="col-lg-12">
                            <div style="float:right; margin-bottom: 5px !important;">
                                <?php
                                if ($trnsStatus == "" || $trnsStatus == "Incomplete" || $trnsStatus == "Received" || $trnsStatus == "Rejected") {
                                    ?>
                                    <button id="svCreditItmBtn" type="button" class="btn btn-default btn-sm" onclick="saveCreditItem();"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save</button>
                                    <?php
                                }
                                ?>                                                                                
                            </div>                                           
                        </div>
                    </div>
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmItemName" class="control-label col-md-4">Product:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="..." id="frmItemName" value="<?php echo $itemDesc; ?>" readonly>
                                        <input type="hidden" id="frmItemId" value="<?php echo $itemId; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'gnrlOrgID', '', '', 'radio', true, '', 'frmItemId', 'frmItemName', 'clear', 1, '');">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmVendorName" class="control-label col-md-4">Vendor:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="..." id="frmVendorName" value="<?php echo $vendorName; ?>" readonly>
                                        <input type="hidden" id="frmVendorId" value="<?php echo $vendorId; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Suppliers', 'gnrlOrgID', '', '', 'radio', true, '', 'frmVendorId', 'frmVendorName', 'clear', 1, '');">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmPlanName" class="control-label col-md-4">Payment Plan:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="..." id="frmPlanName" value="<?php echo $planName; ?>" readonly>
                                        <input type="hidden" id="frmItmPymntPlanId" value="<?php echo $itmPymntPlanId; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'YMP Item Payment Plans', 'gnrlOrgID', 'frmItemId', 'noOfPymnts', 'radio', true, '', 'frmItmPymntPlanId', 'frmPlanName', 'clear', 1, '', function(){
                                            getInvItmPaymentPlanDets();
                                        });">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmQty" class="control-label col-md-4">Quantity:</label>
                                <div  class="col-md-8">
                                    <input type="number" min="1" class="form-control" aria-label="..." id="frmQty" value="<?php echo $qty; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmUnitSellingPrice" class="control-label col-md-4">Selling Price:</label>
                                <div  class="col-md-8">
                                    <input type="number" min="1" class="form-control" aria-label="..." id="frmUnitSellingPrice" value="<?php echo $unitSellingPrice; ?>" readonly="readonly">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmItmPlanInitDeposit" class="control-label col-md-4">Initial Deposit:</label>
                                <div  class="col-md-8">
                                    <input type="number" min="0" class="form-control" aria-label="..." id="frmItmPlanInitDeposit" value="<?php echo $itmPlanInitDeposit; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php
            }
            else if ($vwtyp == 3) {
                
                $itmPlanSllnPriceArry = array();
                $itmPymntPlanId = isset($_POST['itmPymntPlanId']) ? cleanInputData($_POST['itmPymntPlanId']) : -1;
                $result = getItmPlanSllnPrice($itmPymntPlanId);

                while ($row = loc_db_fetch_array($result)) {
                    $itmPlanSllnPriceArry = array("itemSellingPrice" => $row[0], "initialDeposit" => $row[1]);
                }

                $response = array("itmPlanSllnPriceArry" => $itmPlanSllnPriceArry);

                echo json_encode($response);
                exit();
            }
            else if ($vwtyp == 4) {//POST-DATED CHEQUE ITEMS
                
                $postdatedChqId = isset($_POST['postdatedChqId']) ? cleanInputData($_POST['postdatedChqId']) : -1;
                $cnsmrCreditId = isset($_POST['cnsmrCreditID']) ? cleanInputData($_POST['cnsmrCreditID']) : -1;
                
                $result = get_PostDtdChqsDet($postdatedChqId);
                $chqNo  = "";
                $chqIssuerName  = "";
                $chqBank = "";
                $amount = 0.00;
                
                $chqBkTotal = "";
                $trnsStatus = "";

                //$result = get_SignatoryDets($acctSignId, $acctID);
                while ($row2 = loc_db_fetch_array($result)) {
                    $postdatedChqId = $row2[0];
                    $chqNo  = $row2[1];
                    $chqIssuerName  = $row2[2];
                    $chqBank = $row2[3];
                    $amount = $row2[4];
                }
                ?>
                <form class="form-horizontal" id="postdatedChqForm">
                    <input class="form-control" id="frmPostdatedChqId" type = "hidden" placeholder="Cheque ID" value="<?php echo $postdatedChqId; ?>"/>
                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                    <div class="row"><!-- ROW 1 -->
                        <div class="col-lg-12">
                            <div style="float:right; margin-bottom: 5px !important;">
                                <?php
                                if ($trnsStatus == "" || $trnsStatus == "Incomplete" || $trnsStatus == "Received" || $trnsStatus == "Rejected") {
                                    ?>
                                    <button id="svPostdatedChqBtn" type="button" class="btn btn-default btn-sm" onclick="savePostdatedChq();"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save</button>
                                    <?php
                                }
                                ?>                                                                                
                            </div>                                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmChqNo" class="control-label col-md-4">Cheque No:</label>
                                <div  class="col-md-8">
                                    <input type="test" class="form-control" aria-label="..." id="frmChqNo" value="<?php echo $chqNo; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmChqIssuerName" class="control-label col-md-4">Issuer:</label>
                                <div  class="col-md-8">
                                    <input type="text" class="form-control" aria-label="..." id="frmChqIssuerName" value="<?php echo $chqIssuerName; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmChqBank" class="control-label col-md-4">Bank:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="..." id="frmChqBank" value="<?php echo $chqBank; ?>" readonly>
                                        <input type="hidden" id="frmChqBankId" value="<?php echo $chqBank; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Banks', 'gnrlOrgID', '', '', 'radio', true, '', 'frmChqBankId', 'frmChqBank', 'clear', 1, '');">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmAmount" class="control-label col-md-4">Cheque Amount:</label>
                                <div  class="col-md-8">
                                    <input type="number" min="1" class="form-control" aria-label="..." id="frmAmount" value="<?php echo $amount; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 500) {
                ?>
                <div class="row"><!-- ROW 1 -->
                    <div class="col-lg-12">  
                        <div class="row" id="allItmPymntPlansSetupDetailInfo" style="padding:0px 15px 0px 15px !important">
                            <?php
                            $trnsStatus = "Incomplete";
                            $canAddItmPymntPlansSetup = test_prmssns($dfltPrvldgs[8], $mdlNm);
                            $canEdtItmPymntPlansSetup = test_prmssns($dfltPrvldgs[8], $mdlNm);
                            $canDelItmPymntPlansSetup = test_prmssns($dfltPrvldgs[8], $mdlNm);

                            $searchAll = true;
                            $isEnabledOnly = false;
                            if (isset($_POST['isEnabled'])) {
                                $isEnabledOnly = cleanInputData($_POST['isEnabled']);
                            }

                            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Plan Name';
                            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 50;
                            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
                            if (strpos($srchFor, "%") === FALSE) {
                                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                                $srchFor = str_replace("%%", "%", $srchFor);
                            }

                            $itmID = isset($_POST['sbmtdItmPymntPlansSetupITEMID']) ? cleanInputData($_POST['sbmtdItmPymntPlansSetupITEMID']) : -1;
                            $sbmtdItmDesc = isset($_POST['sbmtdItmDesc']) ? cleanInputData($_POST['sbmtdItmDesc']) : '';


                            if (1 > 0) {
                                $total = getItmPymntPlansSetupTblTtl($isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll);

                                if ($pageNo > ceil($total / $lmtSze)) {
                                    $pageNo = 1;
                                } else if ($pageNo < 1) {
                                    $pageNo = ceil($total / $lmtSze);
                                }
                                $curIdx = $pageNo - 1;
                                $result2 = getItmPymntPlansSetupTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                                ?>
                                <div class="row" style="padding:0px 15px 0px 15px !important">
                                    <legend class="basic_person_lg1" style="color: #003245">PAYMENT PLANS SETUP</legend>
                                    <?php
                                    if ($canEdtItmPymntPlansSetup === true) {
                                        //$colClassType1 = "col-lg-2";
                                        $colClassType1 = "col-lg-6";
                                        $colClassType2 = "col-lg-3";
                                        $colClassType3 = "col-lg-4";
                                        $nwRowHtml = urlencode("<tr id=\"allItmPymntPlansSetupRow__WWW123WWW\">"
                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>
                                                <td class=\"lovtd\">
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allItmPymntPlansSetupRow_WWW123WWW_ItmPymntPlansSetupID\" value=\"-1\" style=\"width:100% !important;\">                                                                         
                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmPymntPlansSetupRow_WWW123WWW_PlanName\" name=\"allItmPymntPlansSetupRow_WWW123WWW_PlanName\" value=\"\">                                                                        
                                                </td>
                                                <td class=\"lovtd\">
                                                    <div class=\"input-group\">
                                                        <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmPymntPlansSetupRow_WWW123WWW_PlanPriceType\" value=\"\" readonly>
                                                        <input type=\"hidden\" id=\"allItmPymntPlansSetupRow_WWW123WWW_PlanPriceTypeID\" value=\"\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'YMP Payment Plan Amount Type', '', '', '', 'radio', true, '', 'allItmPymntPlansSetupRow_WWW123WWW_PlanPriceTypeID', 'allItmPymntPlansSetupRow_WWW123WWW_PlanPriceType', 'clear', 1, '');\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\">
                                                    <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmPymntPlansSetupRow_WWW123WWW_PlanPrice\" name=\"allItmPymntPlansSetupRow_WWW123WWW_PlanPrice\" value=\"\">
                                                </td>                                             
                                                <td class=\"lovtd\">
                                                    <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmPymntPlansSetupRow_WWW123WWW_NoOfPymnts\" name=\"allItmPymntPlansSetupRow_WWW123WWW_NoOfPymnts\" value=\"\">                                                               
                                                </td>
                                                    <td class=\"lovtd\">
                                                        <div class=\"input-group\">
                                                            <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmPymntPlansSetupRow_WWW123WWW_InitDpstType\" value=\"\" readonly>
                                                            <input type=\"hidden\" id=\"allItmPymntPlansSetupRow_WWW123WWW_InitDpstTypeID\" value=\"\">
                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'YMP Payment Plan Amount Type', '', '', '', 'radio', true, '', 'allItmPymntPlansSetupRow_WWW123WWW_InitDpstTypeID', 'allItmPymntPlansSetupRow_WWW123WWW_InitDpstType', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                <td class=\"lovtd\">
                                                        <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmPymntPlansSetupRow_WWW123WWW_InitDpst\" name=\"allItmPymntPlansSetupRow_WWW123WWW_InitDpst\" value=\"\">                                                               
                                                </td>
                                                    <td class=\"lovtd\">
                                                        <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmPymntPlansSetupRow_WWW123WWW_OrderNo\" name=\"allItmPymntPlansSetupRow_WWW123WWW_OrderNo\" value=\"\">                                                               
                                                    </td>
                                                <td class=\"lovtd\">       
                                                    <select class=\"form-control\" aria-label=\"...\" id=\"allItmPymntPlansSetupRow_WWW123WWW_IsEnbld\" name=\"allItmPymntPlansSetupRow_WWW123WWW_IsEnbld\">
                                                        <option value=\"Yes\" selected>Yes</option>
                                                        <option value=\"No\" >No</option>														
                                                    </select>
                                                </td>
                                                <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"deleteItmPymntPlansSetup('allItmPymntPlansSetupRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Plan\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button>
                                                </td>
                                            </tr>");
                                        ?>
                                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">
                                            <?php if ($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allItmPymntPlansSetupTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Payment Plan">
                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;New Plan
                                                </button>
                                            <?php } ?>
                                        </div>
                                        <?php
                                    } else {
                                        $colClassType1 = "col-lg-3";
                                        $colClassType2 = "col-lg-6";
                                        $colClassType3 = "col-lg-6";
                                        /* $colClassType1 = "col-lg-3";
                                          $colClassType2 = "col-lg-3";
                                          $colClassType3 = "col-lg-3"; */
                                    }
                                    ?>
                                    <!--<div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                        <div class="input-group">
                                            <input class="form-control" id="allItmPymntPlansSetupSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" 
                                                   onkeyup="enterKeyFuncAllItmPymntPlansSetup(event, '', '#allItmPymntPlansSetupDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItmPymntPlansSetupITEMID=<?php echo $itmID; ?>');">
                                            <input id="allItmPymntPlansSetupPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllItmPymntPlansSetup('clear', '#allItmPymntPlansSetupDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItmPymntPlansSetupITEMID=<?php echo $itmID; ?>');">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllItmPymntPlansSetup('', '#allItmPymntPlansSetupDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItmPymntPlansSetupITEMID=<?php echo $itmID; ?>');">
                                                <span class="glyphicon glyphicon-search"></span>
                                            </label> 
                                        </div>
                                    </div>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="allItmPymntPlansSetupSrchIn">
                                    <?php
                                    $valslctdArry = array("");
                                    $srchInsArrys = array("Plan Name");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="allItmPymntPlansSetupDsplySze" style="min-width:70px !important;">                            
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
                                                <a class="rhopagination" href="javascript:getAllItmPymntPlansSetup('previous', '#allItmPymntPlansSetupDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItmPymntPlansSetupITEMID=<?php echo $itmID; ?>');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="rhopagination" href="javascript:getAllItmPymntPlansSetup('next', '#allItmPymntPlansSetupDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItmPymntPlansSetupITEMID=<?php echo $itmID; ?>');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>-->


                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdItmDesc" name="recCnt" value="<?php echo $sbmtdItmDesc; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="recCnt" name="recCnt" value="">
                                    <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdItmPymntPlansSetupITEMID" name="sbmtdItmPymntPlansSetupITEMID" value="<?php echo $itmID; ?>">
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;"> 
                                        <div style="float:right !important;">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveItmPymntPlansSetup();" data-toggle="tooltip" data-placement="bottom" title="Save Payment Plan(s)">
                                                <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                        <table class="table table-striped table-bordered table-responsive" id="allItmPymntPlansSetupTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Plan Name</th>
                                                    <th>Plan Price Type</th>
                                                    <th>Plan Price</th>
                                                    <th>No. Of Payments</th>
                                                    <th>Initial Deposit Type</th>
                                                    <th>Initial Deposit</th>
                                                    <th>Order No</th>
                                                    <th>Is Enabled</th>
                                                    <th>...</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cntr = 0;
                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                    $cntr += 1;
                                                    ?>
                                                    <tr id="allItmPymntPlansSetupRow_<?php echo $cntr; ?>">                                    
                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                        <td class="lovtd">
                                                            <input type="hidden" class="form-control" aria-label="..." id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_ItmPymntPlansSetupID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                                                         
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_PlanName" name="allItmPymntPlansSetupRow<?php echo $cntr; ?>_PlanName" value="<?php echo $row2[1]; ?>">                                                                        
                                                        </td>
                                                        <td class="lovtd">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_PlanPriceType" value="<?php echo $row2[6]; ?>" readonly>
                                                                <input type="hidden" id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_PlanPriceTypeID" value="<?php echo $row2[6]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'YMP Payment Plan Amount Type', '', '', '', 'radio', true, '', 'allItmPymntPlansSetupRow<?php echo $cntr; ?>_PlanPriceTypeID', 'allItmPymntPlansSetupRow<?php echo $cntr; ?>_PlanPriceType', 'clear', 1, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd"> 
                                                            <input type="number" min="1" class="form-control rqrdFld" aria-label="..." id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_PlanPrice" name="allItmPymntPlansSetupRow<?php echo $cntr; ?>_PlanPrice" value="<?php echo $row2[3]; ?>"> 
                                                        </td>
                                                        <td class="lovtd"> 
                                                            <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_NoOfPymnts" name="allItmPymntPlansSetupRow<?php echo $cntr; ?>_NoOfPymnts" value="<?php echo $row2[2]; ?>">
                                                        </td>   
                                                        <td class="lovtd">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_InitDpstType" value="<?php echo $row2[7]; ?>" readonly>
                                                                <input type="hidden" id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_InitDpstTypeID" value="<?php echo $row2[7]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'YMP Payment Plan Amount Type', '', '', '', 'radio', true, '', 'allItmPymntPlansSetupRow<?php echo $cntr; ?>_InitDpstTypeID', 'allItmPymntPlansSetupRow<?php echo $cntr; ?>_InitDpstType', 'clear', 1, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd">
                                                            <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_InitDpst" name="allItmPymntPlansSetupRow<?php echo $cntr; ?>_InitDpst" value="<?php echo $row2[5]; ?>">                                                               
                                                        </td>
                                                        <td class="lovtd">
                                                            <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_OrderNo" name="allItmPymntPlansSetupRow<?php echo $cntr; ?>_OrderNo" value="<?php echo $row2[8]; ?>">                                                               
                                                        </td>
                                                        <td class="lovtd">  
                                                            <select class="form-control" aria-label="..." id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_IsEnbld" name="allItmPymntPlansSetupRow<?php echo $cntr; ?>_IsEnbld">
                                                                <?php
                                                                $sltdYes = "";
                                                                $sltdNo = "";
                                                                if ($row2[4] == "Yes") {
                                                                    $sltdYes = "selected";
                                                                } else if ($row2[4] == "No") {
                                                                    $sltdNo = "selected";
                                                                }
                                                                ?>
                                                                <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                                                <option value="No" <?php echo $sltdNo; ?>>No</option>    
                                                            </select>		
                                                        </td>
                                                        <?php if ($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="deleteItmPymntPlansSetup('allItmPymntPlansSetupRow_<?php echo $cntr; ?>', '<?php echo $row2[0]; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Payment Plan">
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
                                    </div>                
                                </div>
                                <?php
                            } else {
                                ?>
                                <span>No Results Found</span>
                                <?php
                            }
                            ?> 
                        </div>  
                    </div>
                </div>        
                <?php
            }
        }
    }
}

