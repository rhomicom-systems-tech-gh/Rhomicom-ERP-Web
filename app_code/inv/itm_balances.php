<?php
$canview = test_prmssns($dfltPrvldgs[7], $mdlNm);
$canRn4Othrs = !test_prmssns($dfltPrvldgs[72], $mdlNm);
$finStmtItems = array("Item Balances", "Stock Balances", "Consignment Balances", "Bin Card Report", "Incorrect Balances",
    "Money Received Report (Payments)", "Money Received Report (Documents)", "Items Sold/Issued Report");
$finStmtImages = array("report-icon-png.png", "report-icon-png.png", "report-icon-png.png", "report-icon-png.png", "report-icon-png.png",
    "report-icon-png.png", "report-icon-png.png", "report-icon-png.png");
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                
            }
        } else {
            $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Sales/Item Balance Reports</span>
				</li>";
            if ($vwtyp == 0) {
                $cntent .= "</ul>
                              </div>";

                $cntent .= "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
                    <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                        <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                        font-weight:normal;\">Sales/Inventory Reports:</span>
                    </div>
        <p>";
                $grpcntr = 0;
                for ($i = 0; $i < count($finStmtItems); $i++) {
                    $No = ($i + 1) * 10;
                    if ($i == 0) {
                        
                    }
                    if ($grpcntr == 0) {
                        $cntent .= "<div class=\"row\">";
                    }

                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$No');\">
            <img src=\"cmn_images/$finStmtImages[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2\">" . ($finStmtItems[$i]) . "</span>
        </button>
            </div>";
                    if ($grpcntr == 3) {
                        $cntent .= "</div>";
                        $grpcntr = 0;
                    } else {
                        $grpcntr = $grpcntr + 1;
                    }
                }
                $cntent .= "
      </p>
    </div>";
                echo $cntent;
            } else if ($vwtyp == 10) {
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Item Balances</span>
				</li>
                               </ul>
                              </div>";
                echo $cntent;
                ?>
                <form class="form-horizontal" id="accbFSRptForm">
                    <div class="row">
                        <?php
                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                        //echo $ymdtme;
                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");

                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                        $accbFSRptItemCodeID = isset($_POST['accbFSRptItemCodeID']) ? (int) cleanInputData($_POST['accbFSRptItemCodeID']) : -1;
                        $accbFSRptItemCode = isset($_POST['accbFSRptItemCode']) ? cleanInputData($_POST['accbFSRptItemCode']) : "";
                        $accbFSRptStoreID = isset($_POST['accbFSRptStoreID']) ? (int) cleanInputData($_POST['accbFSRptStoreID']) : $selectedStoreID;
                        $accbFSRptStore = isset($_POST['accbFSRptStore']) ? cleanInputData($_POST['accbFSRptStore']) : $acsCntrlGrpNm;
                        $accbFSRptCtgryID = isset($_POST['accbFSRptCtgryID']) ? (int) cleanInputData($_POST['accbFSRptCtgryID']) : -1;
                        $accbFSRptCtgry = isset($_POST['accbFSRptCtgry']) ? cleanInputData($_POST['accbFSRptCtgry']) : "";
                        $accbFSRptItemType = isset($_POST['accbFSRptItemType']) ? cleanInputData($_POST['accbFSRptItemType']) : "";
                        $accbFSRptQTYType = isset($_POST['accbFSRptQTYType']) ? cleanInputData($_POST['accbFSRptQTYType']) : "";
                        $accbFSRptMinQTY = isset($_POST['accbFSRptMinQTY']) ? (float) cleanInputData($_POST['accbFSRptMinQTY']) : 1;
                        $accbFSRptMaxQTY = isset($_POST['accbFSRptMaxQTY']) ? (float) cleanInputData($_POST['accbFSRptMaxQTY']) : 9999999999;
                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                        $accbFSRptDte1 = $accbFSRptDte;
                        $fsrptRunID = -1;
                        if ($startRunng == 1) {
                            $fsrptRunID = getNewFSRptRunID();
                            /* if ($accbFSRptDte != "") {
                              $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                              } */
                            $strSql = "select inv.populate_item_bals(" . $fsrptRunID . ",
                                                  " . $accbFSRptItemCodeID . ",
                                                  " . $accbFSRptStoreID . ",
                                                  " . $accbFSRptCtgryID . ",
                                                  '" . loc_db_escape_string($accbFSRptItemType) . "',
                                                  '" . loc_db_escape_string($accbFSRptQTYType) . "',
                                                  " . $accbFSRptMinQTY . ",
                                                  " . $accbFSRptMaxQTY . ",
                                                  '" . $accbFSRptDte . "',
                                                  " . $usrID . ",
                                                  to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                                                  " . $orgID . ",-1);";
                            $result = executeSQLNoParams($strSql);
                        }
                        ?>
                        <div class="col-md-3" style="padding:5px 1px 0px 15px;" id="leftDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <legend class="basic_person_lg">
                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </legend>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;color:red;font-style: italic;">NB: Not Restricting your search using these parameters can cause your report to run for hours unending!!!</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="accbFSRptItemCode" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Code/Name:</label>
                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptItemCode" name="accbFSRptItemCode" value="<?php echo $accbFSRptItemCode; ?>" style="width:100%;" readonly="true">
                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptItemCodeID" name="accbFSRptItemCodeID" value="<?php echo $accbFSRptItemCodeID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptItemCodeID', 'accbFSRptItemCode', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="accbFSRptStore" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Store:</label>
                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptStore" name="accbFSRptStore" value="<?php echo $accbFSRptStore; ?>" style="width:100%;" readonly="true">
                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptStoreID" name="accbFSRptStoreID" value="<?php echo $accbFSRptStoreID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Users\' Sales Stores', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '', 'accbFSRptStoreID', 'accbFSRptStore', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="accbFSRptCtgry" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Category:</label>
                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptCtgry" name="accbFSRptCtgry" value="<?php echo $accbFSRptCtgry; ?>" style="width:100%;" readonly="true">
                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptCtgryID" name="accbFSRptCtgryID" value="<?php echo $accbFSRptCtgryID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Categories', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptCtgryID', 'accbFSRptCtgry', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="accbFSRptItemType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Item Type:</label>
                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptItemType">
                                                <?php
                                                $valslctdArry = array("", "", "", "", "");
                                                $srchInsArrys = array("", "Merchandise Inventory", "Non-Merchandise Inventory", "Fixed Assets",
                                                    "Expense Item");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($accbFSRptItemType == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="accbFSRptQTYType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">QTY Type:</label>
                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptQTYType">
                                                <?php
                                                $valslctdArry = array("", "", "", "", "");
                                                $srchInsArrys = array("", "Total", "Reservations", "Available");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($accbFSRptQTYType == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">QTY From:</label>
                                        </div>
                                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <input type="number" min="0" max="9999999999" class="form-control" aria-label="..." id="accbFSRptMinQTY" name="accbFSRptMinQTY" value="<?php echo $accbFSRptMinQTY; ?>">
                                        </div>
                                        <div class="col-md-1" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">&nbsp;to:&nbsp;</label>
                                        </div>
                                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                            <input type="number" min="0" max="9999999999" class="form-control" aria-label="..." id="accbFSRptMaxQTY" name="accbFSRptMaxQTY" value="<?php echo $accbFSRptMaxQTY; ?>">
                                        </div>
                                    </div>
                                </div> 
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">As At Date:</label>
                                        </div>
                                        <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="As At Date:">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=12&typ=1&pg=9&vtyp=10');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Generate Report
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=12&typ=1&pg=9&vtyp=10');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbFSRptTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="accbRcnclGlStatemtLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row"> 
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                <span aria-hidden="true">&raquo;</span>
                                                            </a> ITEM BALANCES AS AT <?php echo strtoupper($accbFSRptDte1); ?>
                                                        </caption>
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:40px;width:40px;">No.</th>
                                                                <th style="max-width:250px !important;">Item Code/Description</th>
                                                                <th style="text-align: right;">Category</th>
                                                                <th style="max-width:50px;width:50px;">Base UOM</th>
                                                                <th style="text-align: right;min-width:70px;">Total QTY</th>
                                                                <th style="text-align: right;min-width:70px;">Reservations</th>
                                                                <th style="text-align: right;min-width:70px;">Available QTY</th>
                                                                <th style="max-width:50px;width:50px;">CUR.</th>
                                                                <th style="text-align: right;min-width:120px;">Total Cost</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $maxNoRows = 0;
                                                            $resultRw = null;
                                                            if ($fsrptRunID > 0) {
                                                                $resultRw = get_ItemBalsRpt($fsrptRunID, $accbFSRptDte);
                                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                            }
                                                            $ttlTrsctnDbtAmnt = 0;
                                                            $ttlTrsctnCrdtAmnt = 0;
                                                            $ttlTrsctnNetAmnt = 0;
                                                            $trsctnbals_crny = "";
                                                            while ($cntr < $maxNoRows) {
                                                                $rowNumber = 0;
                                                                $trsctnItmNm = "";
                                                                $trsctnUomNm = "";
                                                                $trsctnCtgryNm = "";
                                                                $trsctnstock_tot_qty = 0;
                                                                $trsctnstoc_rsrv = 0;
                                                                $trsctnstoc_avlbl = 0;
                                                                $trsctnstock_tot_cost = 0;
                                                                $trsctnbals_date = "";
                                                                $numStyle1 = "text-align:right;";
                                                                $nameStyle1 = "";
                                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                    $rowNumber = (float) $rowRw[0];
                                                                    $trsctnItmNm = str_replace(" ", "&nbsp;", $rowRw[1]);
                                                                    $trsctnCtgryNm = str_replace(" ", "&nbsp;", $rowRw[2]);
                                                                    $trsctnUomNm = str_replace(" ", "&nbsp;", $rowRw[3]);
                                                                    $trsctnstock_tot_qty = (float) $rowRw[4];
                                                                    $trsctnstoc_rsrv = (float) $rowRw[5];
                                                                    $trsctnstoc_avlbl = (float) $rowRw[6];
                                                                    $trsctnstock_tot_cost = (float) $rowRw[7];
                                                                    $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnstock_tot_cost;
                                                                    $trsctnbals_date = $rowRw[8];
                                                                    $trsctnbals_crny = $rowRw[9];
                                                                    $isParent = "0";
                                                                    $hsSbldgr = "0";
                                                                    if ($isParent == "1" || $hsSbldgr == "1") {
                                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                                        $nameStyle1 = "font-weight:bold;";
                                                                    }
                                                                }
                                                                $cntr += 1;
                                                                ?>
                                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $trsctnItmNm; ?>" >
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemID" value="-1" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="0" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemCode" value="" style="width:100% !important;">  
                                                                        <span><?php echo $trsctnItmNm; ?></span>             
                                                                    </td>
                                                                    <td class="lovtd" style="">
                                                                        <span><?php echo $trsctnCtgryNm; ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;">
                                                                        <span><?php echo $trsctnUomNm; ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                        <span><?php echo number_format($trsctnstock_tot_qty); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                        <span><?php echo number_format($trsctnstoc_rsrv); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                        <span><?php echo number_format($trsctnstoc_avlbl); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;">
                                                                        <span><?php echo $trsctnbals_crny; ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;">
                                                                        <span><?php echo number_format($trsctnstock_tot_cost, 2); ?></span>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th style="max-width:40px;width:40px;">&nbsp;</th>
                                                                <th style="max-width:250px !important;">TOTALS:</th>
                                                                <th style="text-align: right;">&nbsp;</th>
                                                                <th style="max-width:50px;width:50px;">&nbsp;</th>
                                                                <th style="text-align: right;min-width:70px;">&nbsp;</th>
                                                                <th style="text-align: right;min-width:70px;">&nbsp;</th>
                                                                <th style="text-align: right;min-width:70px;">&nbsp;</th>
                                                                <th style="max-width:50px;width:50px;"><?php echo $trsctnbals_crny; ?></th>
                                                                <th class="lovtd" style="text-align:right;font-weight:bold;color:red;">
                                                                    <span><?php echo number_format($ttlTrsctnNetAmnt, 2); ?></span>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 20) {
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Stock Balances</span>
				</li>
                               </ul>
                              </div>";
                echo $cntent;
                ?>
                <form class="form-horizontal" id="accbFSRptForm">
                    <div class="row">
                        <?php
                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                        //echo $ymdtme;
                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                        $ymdtme3 = "01" . $ymdtme;

                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                        $accbFSRptItemCodeID = isset($_POST['accbFSRptItemCodeID']) ? (int) cleanInputData($_POST['accbFSRptItemCodeID']) : -1;
                        $accbFSRptItemCode = isset($_POST['accbFSRptItemCode']) ? cleanInputData($_POST['accbFSRptItemCode']) : "";
                        $accbFSRptStoreID = isset($_POST['accbFSRptStoreID']) ? (int) cleanInputData($_POST['accbFSRptStoreID']) : $selectedStoreID;
                        $accbFSRptStore = isset($_POST['accbFSRptStore']) ? cleanInputData($_POST['accbFSRptStore']) : $acsCntrlGrpNm;
                        $accbFSRptCtgryID = isset($_POST['accbFSRptCtgryID']) ? (int) cleanInputData($_POST['accbFSRptCtgryID']) : -1;
                        $accbFSRptCtgry = isset($_POST['accbFSRptCtgry']) ? cleanInputData($_POST['accbFSRptCtgry']) : "";
                        $accbFSRptItemType = isset($_POST['accbFSRptItemType']) ? cleanInputData($_POST['accbFSRptItemType']) : "";
                        $accbFSRptQTYType = isset($_POST['accbFSRptQTYType']) ? cleanInputData($_POST['accbFSRptQTYType']) : "";
                        $accbFSRptMinQTY = isset($_POST['accbFSRptMinQTY']) ? (float) cleanInputData($_POST['accbFSRptMinQTY']) : 1;
                        $accbFSRptMaxQTY = isset($_POST['accbFSRptMaxQTY']) ? (float) cleanInputData($_POST['accbFSRptMaxQTY']) : 9999999999;
                        $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3, 0, 11);
                        $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                        $accbFSRptDte1 = $accbFSRptDte;
                        $fsrptRunID = -1;
                        if ($startRunng == 1) {
                            $fsrptRunID = getNewFSRptRunID();
                            /* if ($accbFSRptDte != "") {
                              $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                              } */

                            $strSql = "select inv.populate_stock_bals(" . $fsrptRunID . ",
                                                  " . $accbFSRptItemCodeID . ",
                                                  " . $accbFSRptStoreID . ",
                                                  " . $accbFSRptCtgryID . ",
                                                  '" . loc_db_escape_string($accbFSRptItemType) . "',
                                                  '" . loc_db_escape_string($accbFSRptQTYType) . "',
                                                  " . $accbFSRptMinQTY . ",
                                                  " . $accbFSRptMaxQTY . ",
                                                  '" . $accbFSRptDte . "',
                                                  " . $usrID . ",
                                                  to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                                                  " . $orgID . ",-1);";
                            $result = executeSQLNoParams($strSql);
                        }
                        ?>
                        <div class="col-md-3" style="padding:5px 1px 0px 15px;" id="leftDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <legend class="basic_person_lg">
                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </legend>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;color:red;font-style: italic;">NB: Not Restricting your search using these parameters can cause your report to run for hours unending!!!</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="accbFSRptItemCode" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Code/Name:</label>
                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptItemCode" name="accbFSRptItemCode" value="<?php echo $accbFSRptItemCode; ?>" style="width:100%;" readonly="true">
                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptItemCodeID" name="accbFSRptItemCodeID" value="<?php echo $accbFSRptItemCodeID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptItemCodeID', 'accbFSRptItemCode', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="accbFSRptStore" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Store:</label>
                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptStore" name="accbFSRptStore" value="<?php echo $accbFSRptStore; ?>" style="width:100%;" readonly="true">
                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptStoreID" name="accbFSRptStoreID" value="<?php echo $accbFSRptStoreID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Users\' Sales Stores', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '', 'accbFSRptStoreID', 'accbFSRptStore', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="accbFSRptCtgry" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Category:</label>
                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptCtgry" name="accbFSRptCtgry" value="<?php echo $accbFSRptCtgry; ?>" style="width:100%;" readonly="true">
                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptCtgryID" name="accbFSRptCtgryID" value="<?php echo $accbFSRptCtgryID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Categories', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptCtgryID', 'accbFSRptCtgry', 'clear', 1, '', function () {});">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="accbFSRptItemType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Item Type:</label>
                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptItemType">
                                                <?php
                                                $valslctdArry = array("", "", "", "", "");
                                                $srchInsArrys = array("", "Merchandise Inventory", "Non-Merchandise Inventory", "Fixed Assets",
                                                    "Expense Item");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($accbFSRptItemType == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="accbFSRptQTYType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">QTY Type:</label>
                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptQTYType">
                                                <?php
                                                $valslctdArry = array("", "", "", "", "");
                                                $srchInsArrys = array("", "Total", "Reservations", "Available");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($accbFSRptQTYType == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">QTY From:</label>
                                        </div>
                                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <input type="number" min="0" max="9999999999" class="form-control" aria-label="..." id="accbFSRptMinQTY" name="accbFSRptMinQTY" value="<?php echo $accbFSRptMinQTY; ?>">
                                        </div>
                                        <div class="col-md-1" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">&nbsp;to:&nbsp;</label>
                                        </div>
                                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                            <input type="number" min="0" max="9999999999" class="form-control" aria-label="..." id="accbFSRptMaxQTY" name="accbFSRptMaxQTY" value="<?php echo $accbFSRptMaxQTY; ?>">
                                        </div>
                                    </div>
                                </div> 
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;">As At Date:</label>
                                        </div>
                                        <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="As At Date:">
                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=12&typ=1&pg=9&vtyp=20');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Generate Report
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=12&typ=1&pg=9&vtyp=20');">
                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbFSRptTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="accbRcnclGlStatemtLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row"> 
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                <span aria-hidden="true">&raquo;</span>
                                                            </a> STOCK BALANCES AS AT <?php echo strtoupper($accbFSRptDte1); ?>
                                                        </caption>
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:40px;width:40px;">No.</th>
                                                                <th style="max-width:250px !important;">Item Code/Description</th>
                                                                <th style="text-align: right;">Category</th>
                                                                <th style="text-align: right;">Store</th>
                                                                <th style="max-width:50px;width:50px;">Base UOM</th>
                                                                <th style="text-align: right;min-width:70px;">Total QTY</th>
                                                                <th style="text-align: right;min-width:70px;">Reservations</th>
                                                                <th style="text-align: right;min-width:70px;">Available QTY</th>
                                                                <th style="max-width:50px;width:50px;">CUR.</th>
                                                                <th style="text-align: right;min-width:120px;">Total Cost</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $maxNoRows = 0;
                                                            $resultRw = null;
                                                            if ($fsrptRunID > 0) {
                                                                $resultRw = get_StockBalsRpt($fsrptRunID, $accbFSRptDte);
                                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                            }
                                                            $ttlTrsctnDbtAmnt = 0;
                                                            $ttlTrsctnCrdtAmnt = 0;
                                                            $ttlTrsctnNetAmnt = 0;
                                                            $trsctnbals_crny = "";
                                                            while ($cntr < $maxNoRows) {
                                                                $rowNumber = 0;
                                                                $trsctnItmNm = "";
                                                                $trsctnUomNm = "";
                                                                $trsctnCtgryNm = "";
                                                                $trsctnstock_tot_qty = 0;
                                                                $trsctnstoc_rsrv = 0;
                                                                $trsctnstoc_avlbl = 0;
                                                                $trsctnstock_tot_cost = 0;
                                                                $trsctnbals_date = "";
                                                                $numStyle1 = "text-align:right;";
                                                                $nameStyle1 = "";
                                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                    $rowNumber = (float) $rowRw[0];
                                                                    $trsctnItmNm = str_replace(" ", "&nbsp;", $rowRw[1]);
                                                                    $trsctnCtgryNm = str_replace(" ", "&nbsp;", $rowRw[2]);
                                                                    $trsctnUomNm = str_replace(" ", "&nbsp;", $rowRw[3]);
                                                                    $trsctnstock_tot_qty = (float) $rowRw[4];
                                                                    $trsctnstoc_rsrv = (float) $rowRw[5];
                                                                    $trsctnstoc_avlbl = (float) $rowRw[6];
                                                                    $trsctnstock_tot_cost = (float) $rowRw[7];
                                                                    $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnstock_tot_cost;
                                                                    $trsctnbals_date = $rowRw[8];
                                                                    $trsctnbals_crny = $rowRw[9];
                                                                    $trsctnbals_storenm = $rowRw[10];
                                                                    $isParent = "0";
                                                                    $hsSbldgr = "0";
                                                                    if ($isParent == "1" || $hsSbldgr == "1") {
                                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                                        $nameStyle1 = "font-weight:bold;";
                                                                    }
                                                                }
                                                                $cntr += 1;
                                                                ?>
                                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $trsctnItmNm; ?>" >
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemID" value="-1" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="0" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemCode" value="" style="width:100% !important;">  
                                                                        <span><?php echo $trsctnItmNm; ?></span>             
                                                                    </td>
                                                                    <td class="lovtd" style="">
                                                                        <span><?php echo $trsctnCtgryNm; ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="">
                                                                        <span><?php echo $trsctnbals_storenm; ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;">
                                                                        <span><?php echo $trsctnUomNm; ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                        <span><?php echo number_format($trsctnstock_tot_qty); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                        <span><?php echo number_format($trsctnstoc_rsrv); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                        <span><?php echo number_format($trsctnstoc_avlbl); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;">
                                                                        <span><?php echo $trsctnbals_crny; ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;">
                                                                        <span><?php echo number_format($trsctnstock_tot_cost, 2); ?></span>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th style="max-width:40px;width:40px;">&nbsp;</th>
                                                                <th style="max-width:250px !important;">TOTALS:</th>
                                                                <th style="text-align: right;">&nbsp;</th>
                                                                <th style="text-align: right;">&nbsp;</th>
                                                                <th style="max-width:50px;width:50px;">&nbsp;</th>
                                                                <th style="text-align: right;min-width:70px;">&nbsp;</th>
                                                                <th style="text-align: right;min-width:70px;">&nbsp;</th>
                                                                <th style="text-align: right;min-width:70px;">&nbsp;</th>
                                                                <th style="max-width:50px;width:50px;"><?php echo $trsctnbals_crny; ?></th>
                                                                <th class="lovtd" style="text-align:right;font-weight:bold;color:red;">
                                                                    <span><?php echo number_format($ttlTrsctnNetAmnt, 2); ?></span>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        </form>
                                        <?php
                                    } else if ($vwtyp == 30) {
                                        $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Consignment Balances</span>
				</li>
                               </ul>
                              </div>";
                                        echo $cntent;
                                        ?>
                                        <form class="form-horizontal" id="accbFSRptForm">
                                            <div class="row">
                                                <?php
                                                $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                                                //echo $ymdtme;
                                                $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                                                $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                                                $ymdtme3 = "01" . $ymdtme;
                                                $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                                                $accbFSRptItemCodeID = isset($_POST['accbFSRptItemCodeID']) ? (int) cleanInputData($_POST['accbFSRptItemCodeID']) : -1;
                                                $accbFSRptItemCode = isset($_POST['accbFSRptItemCode']) ? cleanInputData($_POST['accbFSRptItemCode']) : "";
                                                $accbFSRptStoreID = isset($_POST['accbFSRptStoreID']) ? (int) cleanInputData($_POST['accbFSRptStoreID']) : $selectedStoreID;
                                                $accbFSRptStore = isset($_POST['accbFSRptStore']) ? cleanInputData($_POST['accbFSRptStore']) : $acsCntrlGrpNm;
                                                $accbFSRptCtgryID = isset($_POST['accbFSRptCtgryID']) ? (int) cleanInputData($_POST['accbFSRptCtgryID']) : -1;
                                                $accbFSRptCtgry = isset($_POST['accbFSRptCtgry']) ? cleanInputData($_POST['accbFSRptCtgry']) : "";
                                                $accbFSRptItemType = isset($_POST['accbFSRptItemType']) ? cleanInputData($_POST['accbFSRptItemType']) : "";
                                                $accbFSRptQTYType = isset($_POST['accbFSRptQTYType']) ? cleanInputData($_POST['accbFSRptQTYType']) : "";
                                                $accbFSRptMinQTY = isset($_POST['accbFSRptMinQTY']) ? (float) cleanInputData($_POST['accbFSRptMinQTY']) : 1;
                                                $accbFSRptMaxQTY = isset($_POST['accbFSRptMaxQTY']) ? (float) cleanInputData($_POST['accbFSRptMaxQTY']) : 9999999999;
                                                $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3, 0, 11);
                                                $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                                                $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                                                $accbFSRptDte1 = $accbFSRptDte;
                                                $fsrptRunID = -1;
                                                if ($startRunng == 1) {
                                                    $fsrptRunID = getNewFSRptRunID();
                                                    /* if ($accbFSRptDte != "") {
                                                      $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                                                      } */

                                                    $strSql = "select inv.populate_cnsgn_bals(" . $fsrptRunID . ",
                                                  " . $accbFSRptItemCodeID . ",
                                                  " . $accbFSRptStoreID . ",
                                                  " . $accbFSRptCtgryID . ",
                                                  '" . loc_db_escape_string($accbFSRptItemType) . "',
                                                  '" . loc_db_escape_string($accbFSRptQTYType) . "',
                                                  " . $accbFSRptMinQTY . ",
                                                  " . $accbFSRptMaxQTY . ",
                                                  '" . $accbFSRptDte . "',
                                                  " . $usrID . ",
                                                  to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                                                  " . $orgID . ",-1);";
                                                    $result = executeSQLNoParams($strSql);
                                                }
                                                ?>
                                                <div class="col-md-3" style="padding:5px 1px 0px 15px;" id="leftDivFSRpt">
                                                    <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                        <legend class="basic_person_lg">
                                                            Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                                                <span aria-hidden="true">&laquo;</span>
                                                            </a>
                                                        </legend>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;color:red;font-style: italic;">NB: Not Restricting your search using these parameters can cause your report to run for hours unending!!!</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="accbFSRptItemCode" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Code/Name:</label>
                                                                <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="accbFSRptItemCode" name="accbFSRptItemCode" value="<?php echo $accbFSRptItemCode; ?>" style="width:100%;" readonly="true">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="accbFSRptItemCodeID" name="accbFSRptItemCodeID" value="<?php echo $accbFSRptItemCodeID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptItemCodeID', 'accbFSRptItemCode', 'clear', 1, '', function () {});">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="accbFSRptStore" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Store:</label>
                                                                <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="accbFSRptStore" name="accbFSRptStore" value="<?php echo $accbFSRptStore; ?>" style="width:100%;" readonly="true">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="accbFSRptStoreID" name="accbFSRptStoreID" value="<?php echo $accbFSRptStoreID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Users\' Sales Stores', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '', 'accbFSRptStoreID', 'accbFSRptStore', 'clear', 1, '', function () {});">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="accbFSRptCtgry" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Category:</label>
                                                                <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="accbFSRptCtgry" name="accbFSRptCtgry" value="<?php echo $accbFSRptCtgry; ?>" style="width:100%;" readonly="true">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="accbFSRptCtgryID" name="accbFSRptCtgryID" value="<?php echo $accbFSRptCtgryID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Categories', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptCtgryID', 'accbFSRptCtgry', 'clear', 1, '', function () {});">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="accbFSRptItemType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Item Type:</label>
                                                                <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptItemType">
                                                                        <?php
                                                                        $valslctdArry = array("", "", "", "", "");
                                                                        $srchInsArrys = array("", "Merchandise Inventory", "Non-Merchandise Inventory",
                                                                            "Fixed Assets",
                                                                            "Expense Item");
                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                            if ($accbFSRptItemType == $srchInsArrys[$z]) {
                                                                                $valslctdArry[$z] = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="accbFSRptQTYType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">QTY Type:</label>
                                                                <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptQTYType">
                                                                        <?php
                                                                        $valslctdArry = array("", "", "", "");
                                                                        $srchInsArrys = array("Total", "Reservations", "Available");
                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                            if ($accbFSRptQTYType == $srchInsArrys[$z]) {
                                                                                $valslctdArry[$z] = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                    <label style="margin-bottom:0px !important;">QTY From:</label>
                                                                </div>
                                                                <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                                                    <input type="number" min="0" max="9999999999" class="form-control" aria-label="..." id="accbFSRptMinQTY" name="accbFSRptMinQTY" value="<?php echo $accbFSRptMinQTY; ?>">
                                                                </div>
                                                                <div class="col-md-1" style="padding:5px 1px 0px 1px !important;">
                                                                    <label style="margin-bottom:0px !important;">&nbsp;to:&nbsp;</label>
                                                                </div>
                                                                <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                                                    <input type="number" min="0" max="9999999999" class="form-control" aria-label="..." id="accbFSRptMaxQTY" name="accbFSRptMaxQTY" value="<?php echo $accbFSRptMaxQTY; ?>">
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <div  class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                    <label style="margin-bottom:0px !important;">As At Date:</label>
                                                                </div>
                                                                <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                    <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="As At Date:">
                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=12&typ=1&pg=9&vtyp=30');">
                                                                <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Generate Report
                                                            </button>
                                                        </div>
                                                        <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=12&typ=1&pg=9&vtyp=30');">
                                                                <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </div>
                                                        <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                                                    <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                        <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbFSRptTblSctn"> 
                                                            <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                                <div id="accbRcnclGlStatemtLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                                    <div class="row"> 
                                                                        <div class="col-md-12">
                                                                            <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                                                <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                                                    <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                                        <span aria-hidden="true">&raquo;</span>
                                                                                    </a> CONSIGNMENT BALANCES AS AT <?php echo strtoupper($accbFSRptDte1); ?>
                                                                                </caption>
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th style="max-width:40px;width:40px;">No.</th>
                                                                                        <th style="max-width:250px !important;">Item Code/Description</th>
                                                                                        <th style="text-align: left;">Category</th>
                                                                                        <th style="text-align: left;min-width:120px;">Store</th>
                                                                                        <th style="text-align: left;">Consignment No.</th>
                                                                                        <th style="max-width:50px;width:50px;">Base UOM</th>
                                                                                        <th style="text-align: right;min-width:70px;">Total QTY</th>
                                                                                        <th style="text-align: right;min-width:70px;">Reservations</th>
                                                                                        <th style="text-align: right;min-width:70px;">Available QTY</th>
                                                                                        <th style="max-width:50px;width:50px;">CUR.</th>
                                                                                        <th style="text-align: right;min-width:120px;">Total Cost</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>   
                                                                                    <?php
                                                                                    $cntr = 0;
                                                                                    $maxNoRows = 0;
                                                                                    $resultRw = null;
                                                                                    if ($fsrptRunID > 0) {
                                                                                        $resultRw = get_CnsgnBalsRpt($fsrptRunID, $accbFSRptDte);
                                                                                        $maxNoRows = loc_db_num_rows($resultRw);
                                                                                    }
                                                                                    $ttlTrsctnDbtAmnt = 0;
                                                                                    $ttlTrsctnCrdtAmnt = 0;
                                                                                    $ttlTrsctnNetAmnt = 0;
                                                                                    $trsctnbals_crny = "";
                                                                                    while ($cntr < $maxNoRows) {
                                                                                        $rowNumber = 0;
                                                                                        $trsctnItmNm = "";
                                                                                        $trsctnUomNm = "";
                                                                                        $trsctnCtgryNm = "";
                                                                                        $trsctnstock_tot_qty = 0;
                                                                                        $trsctnstoc_rsrv = 0;
                                                                                        $trsctnstoc_avlbl = 0;
                                                                                        $trsctnstock_tot_cost = 0;
                                                                                        $trsctnbals_date = "";
                                                                                        $numStyle1 = "text-align:right;";
                                                                                        $nameStyle1 = "";
                                                                                        if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                            $rowNumber = (float) $rowRw[0];
                                                                                            $trsctnItmNm = str_replace(" ", "&nbsp;", $rowRw[1]);
                                                                                            $trsctnCtgryNm = str_replace(" ", "&nbsp;", $rowRw[2]);
                                                                                            $trsctnUomNm = str_replace(" ", "&nbsp;", $rowRw[3]);
                                                                                            $trsctnstock_tot_qty = (float) $rowRw[4];
                                                                                            $trsctnstoc_rsrv = (float) $rowRw[5];
                                                                                            $trsctnstoc_avlbl = (float) $rowRw[6];
                                                                                            $trsctnstock_tot_cost = (float) $rowRw[7];
                                                                                            $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnstock_tot_cost;
                                                                                            $trsctnbals_date = $rowRw[8];
                                                                                            $trsctnbals_crny = $rowRw[9];
                                                                                            $trsctnbals_storenm = $rowRw[10];
                                                                                            $trsctnbals_cnsgnid = $rowRw[11];
                                                                                            $isParent = "0";
                                                                                            $hsSbldgr = "0";
                                                                                            if ($isParent == "1" || $hsSbldgr == "1") {
                                                                                                $numStyle1 = "text-align:right;font-weight:bold;";
                                                                                                $nameStyle1 = "font-weight:bold;";
                                                                                            }
                                                                                        }
                                                                                        $cntr += 1;
                                                                                        ?>
                                                                                        <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                                                                            <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                                                            <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $trsctnItmNm; ?>" >
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemID" value="-1" style="width:100% !important;">  
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="0" style="width:100% !important;">  
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemCode" value="" style="width:100% !important;">  
                                                                                                <span><?php echo $trsctnItmNm; ?></span>             
                                                                                            </td>
                                                                                            <td class="lovtd" style="">
                                                                                                <span><?php echo $trsctnCtgryNm; ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="">
                                                                                                <span><?php echo $trsctnbals_storenm; ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="">
                                                                                                <span><?php echo $trsctnbals_cnsgnid; ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="text-align:left;font-weight:bold;">
                                                                                                <span><?php echo $trsctnUomNm; ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                <span><?php echo number_format($trsctnstock_tot_qty); ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                <span><?php echo number_format($trsctnstoc_rsrv); ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                <span><?php echo number_format($trsctnstoc_avlbl); ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="text-align:right;font-weight:bold;">
                                                                                                <span><?php echo $trsctnbals_crny; ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;">
                                                                                                <span><?php
                                                                                                    echo number_format($trsctnstock_tot_cost, 2);
                                                                                                    ?></span>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </tbody>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <th style="max-width:40px;width:40px;">&nbsp;</th>
                                                                                        <th style="max-width:250px !important;">TOTALS:</th>
                                                                                        <th style="text-align: right;">&nbsp;</th>
                                                                                        <th style="text-align: right;">&nbsp;</th>
                                                                                        <th style="text-align: right;">&nbsp;</th>
                                                                                        <th style="max-width:50px;width:50px;">&nbsp;</th>
                                                                                        <th style="text-align: right;min-width:70px;">&nbsp;</th>
                                                                                        <th style="text-align: right;min-width:70px;">&nbsp;</th>
                                                                                        <th style="text-align: right;min-width:70px;">&nbsp;</th>
                                                                                        <th style="max-width:50px;width:50px;"><?php echo $trsctnbals_crny; ?></th>
                                                                                        <th class="lovtd" style="text-align:right;font-weight:bold;color:red;">
                                                                                            <span><?php echo number_format($ttlTrsctnNetAmnt, 2); ?></span>
                                                                                        </th>
                                                                                    </tr>
                                                                                </tfoot>
                                                                            </table>
                                                                        </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                                </form>
                                                                <?php
                                                            } else if ($vwtyp == 40) {
                                                                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Bin Card Report</span>
			    </li>
                           </ul>
                          </div>";
                                                                echo $cntent;
                                                                ?>
                                                                <form class="form-horizontal" id="accbFSRptForm">
                                                                    <div class="row">
                                                                        <?php
                                                                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                                                                        //echo $ymdtme;
                                                                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                                                                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                                                                        $ymdtme3 = "01" . $ymdtme;
                                                                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                                                                        $accbFSRptItemCodeID = isset($_POST['accbFSRptItemCodeID']) ? (int) cleanInputData($_POST['accbFSRptItemCodeID']) : -1;
                                                                        $accbFSRptItemCode = isset($_POST['accbFSRptItemCode']) ? cleanInputData($_POST['accbFSRptItemCode']) : "";
                                                                        $accbFSRptStoreID = isset($_POST['accbFSRptStoreID']) ? (int) cleanInputData($_POST['accbFSRptStoreID']) : $selectedStoreID;
                                                                        $accbFSRptStore = isset($_POST['accbFSRptStore']) ? cleanInputData($_POST['accbFSRptStore']) : $acsCntrlGrpNm;
                                                                        $accbFSRptCtgryID = isset($_POST['accbFSRptCtgryID']) ? (int) cleanInputData($_POST['accbFSRptCtgryID']) : -1;
                                                                        $accbFSRptCtgry = isset($_POST['accbFSRptCtgry']) ? cleanInputData($_POST['accbFSRptCtgry']) : "";
                                                                        $accbFSRptItemType = isset($_POST['accbFSRptItemType']) ? cleanInputData($_POST['accbFSRptItemType']) : "";
                                                                        $accbFSRptQTYType = isset($_POST['accbFSRptQTYType']) ? cleanInputData($_POST['accbFSRptQTYType']) : "";
                                                                        $accbFSRptMinQTY = isset($_POST['accbFSRptMinQTY']) ? (float) cleanInputData($_POST['accbFSRptMinQTY']) : 1;
                                                                        $accbFSRptMaxQTY = isset($_POST['accbFSRptMaxQTY']) ? (float) cleanInputData($_POST['accbFSRptMaxQTY']) : 9999999999;
                                                                        $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3, 0, 11);
                                                                        $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                                                                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                                                                        $accbFSRptDte1 = $accbFSRptDte;
                                                                        $fsrptRunID = -1;
                                                                        if ($startRunng == 1) {
                                                                            $fsrptRunID = getNewFSRptRunID();
                                                                            $strSql = "select inv.populate_bincard_rpt(" . $fsrptRunID . ",
                                                                                                " . $accbFSRptItemCodeID . ",
                                                                                                " . $accbFSRptStoreID . ",
                                                                                                " . $accbFSRptCtgryID . ",
                                                                                                '" . loc_db_escape_string($accbFSRptItemType) . "',
                                                                                                '" . loc_db_escape_string($accbFSRptQTYType) . "',
                                                                                                " . $accbFSRptMinQTY . ",
                                                                                                " . $accbFSRptMaxQTY . ",
                                                                                                '" . $accbStrtFSRptDte . "',
                                                                                                '" . $accbFSRptDte . "',
                                                                                                " . $usrID . ",
                                                                                                to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                                                                                                " . $orgID . ",-1);";
                                                                            $result = executeSQLNoParams($strSql);
                                                                        }
                                                                        ?>
                                                                        <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">
                                                                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                                                <legend class="basic_person_lg">
                                                                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                    </a>
                                                                                </legend>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;color:red;font-style: italic;">NB: Not Restricting your search using these parameters can cause your report to run for hours unending!!!</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptItemCode" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Code/Name:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <div class="input-group">
                                                                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptItemCode" name="accbFSRptItemCode" value="<?php echo $accbFSRptItemCode; ?>" style="width:100%;" readonly="true">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptItemCodeID" name="accbFSRptItemCodeID" value="<?php echo $accbFSRptItemCodeID; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptItemCodeID', 'accbFSRptItemCode', 'clear', 1, '', function () {});">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptStore" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Store:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <div class="input-group">
                                                                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptStore" name="accbFSRptStore" value="<?php echo $accbFSRptStore; ?>" style="width:100%;" readonly="true">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptStoreID" name="accbFSRptStoreID" value="<?php echo $accbFSRptStoreID; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Users\' Sales Stores', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '', 'accbFSRptStoreID', 'accbFSRptStore', 'clear', 1, '', function () {});">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12" style="display:none;">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptCtgry" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Category:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <div class="input-group">
                                                                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptCtgry" name="accbFSRptCtgry" value="<?php echo $accbFSRptCtgry; ?>" style="width:100%;" readonly="true">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptCtgryID" name="accbFSRptCtgryID" value="<?php echo $accbFSRptCtgryID; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Categories', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptCtgryID', 'accbFSRptCtgry', 'clear', 1, '', function () {});">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12" style="display:none;">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptItemType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Item Type:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptItemType">
                                                                                                <?php
                                                                                                $valslctdArry = array("", "", "", "",
                                                                                                    "");
                                                                                                $srchInsArrys = array("", "Merchandise Inventory",
                                                                                                    "Non-Merchandise Inventory",
                                                                                                    "Fixed Assets", "Expense Item");
                                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                    if ($accbFSRptItemType == $srchInsArrys[$z]) {
                                                                                                        $valslctdArry[$z] = "selected";
                                                                                                    }
                                                                                                    ?>
                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12" style="display:none;">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptQTYType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">QTY Type:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptQTYType">
                                                                                                <?php
                                                                                                $valslctdArry = array("", "", "", "");
                                                                                                $srchInsArrys = array("Total", "Reservations",
                                                                                                    "Available");
                                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                    if ($accbFSRptQTYType == $srchInsArrys[$z]) {
                                                                                                        $valslctdArry[$z] = "selected";
                                                                                                    }
                                                                                                    ?>
                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                                            <label style="margin-bottom:0px !important;">QTY From:</label>
                                                                                        </div>
                                                                                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                                                                            <input type="number" min="0" max="9999999999" class="form-control" aria-label="..." id="accbFSRptMinQTY" name="accbFSRptMinQTY" value="<?php echo $accbFSRptMinQTY; ?>">
                                                                                        </div>
                                                                                        <div class="col-md-1" style="padding:5px 1px 0px 1px !important;">
                                                                                            <label style="margin-bottom:0px !important;">&nbsp;to:&nbsp;</label>
                                                                                        </div>
                                                                                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                                                                            <input type="number" min="0" max="9999999999" class="form-control" aria-label="..." id="accbFSRptMaxQTY" name="accbFSRptMaxQTY" value="<?php echo $accbFSRptMaxQTY; ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div> 
                                                                                <div  class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                                            <label style="margin-bottom:0px !important;">From Date:</label>
                                                                                        </div>
                                                                                        <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                                            <input class="form-control" size="16" type="text" id="accbStrtFSRptDte" name="accbStrtFSRptDte" value="<?php echo $accbStrtFSRptDte1; ?>" placeholder="From Date">
                                                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div  class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                                            <label style="margin-bottom:0px !important;">To Date:</label>
                                                                                        </div>
                                                                                        <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=12&typ=1&pg=9&vtyp=40');">
                                                                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        Generate Report
                                                                                    </button>
                                                                                </div> 
                                                                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=12&typ=1&pg=9&vtyp=40');">
                                                                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </div>
                                                                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </div> 
                                                                            </fieldset>
                                                                        </div>
                                                                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                                                                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px;">
                                                                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                                                <span aria-hidden="true">&raquo;</span>
                                                                                            </a>BIN CARD STATEMENT FROM <?php echo strtoupper($accbStrtFSRptDte1); ?> TO <?php echo strtoupper($accbFSRptDte1); ?>
                                                                                        </caption>
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th style="max-width:40px;width:40px;">No.</th>
                                                                                                <th style="max-width:250px !important;">Item Code/Description</th>
                                                                                                <th style="max-width:250px !important;">Store</th>
                                                                                                <th style="min-width:120px !important;">Transaction Type</th>
                                                                                                <th style="max-width:70px;width:70px;">Base UOM</th>
                                                                                                <th style="text-align: right;">QTY Transacted</th>
                                                                                                <th style="text-align: right;min-width: 70px;">Calculated QTY Balance</th>
                                                                                                <!--<th style="text-align: right;">Reservations</th>-->
                                                                                                <th style="text-align: right;min-width: 70px;">Day's Closing Available QTY</th>
                                                                                                <th style="max-width:120px;width:120px;">Balance Date</th>
                                                                                                <th style="max-width:250px !important;">Remarks</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>   
                                                                                            <?php
                                                                                            $cntr = 0;
                                                                                            $maxNoRows = 0;
                                                                                            $resultRw = null;
                                                                                            if ($fsrptRunID > 0) {
                                                                                                $resultRw = get_BinCardRpt($fsrptRunID, $accbStrtFSRptDte, $accbFSRptDte);
                                                                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                                                            }
                                                                                            $ttlTrsctnDbtAmnt = 0;
                                                                                            $ttlTrsctnCrdtAmnt = 0;
                                                                                            $ttlTrsctnNetAmnt = 0;
                                                                                            while ($cntr < $maxNoRows) {
                                                                                                $rowNumber = 0;
                                                                                                $trsctnItmNm = "";
                                                                                                $trsctnUomNm = "";
                                                                                                $trsctnCtgryNm = "";
                                                                                                $trsctnstock_tot_qty = 0;
                                                                                                $trsctnstoc_rsrv = 0;
                                                                                                $trsctnstoc_avlbl = 0;
                                                                                                $trsctnstock_tot_cost = 0;
                                                                                                $trsctnbals_date = "";
                                                                                                $trsctnbals_trnstyp = "";
                                                                                                $numStyle1 = "text-align:right;";
                                                                                                $nameStyle1 = "";
                                                                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                                    $rowNumber = (float) $rowRw[0];
                                                                                                    $trsctnItmNm = str_replace(" ", "&nbsp;", $rowRw[1]);
                                                                                                    $trsctnbals_storenm = str_replace(" ", "&nbsp;", $rowRw[2]);
                                                                                                    $trsctnbals_trnstyp = $rowRw[3];
                                                                                                    $trsctnstock_tot_qty = (float) $rowRw[4];
                                                                                                    $trsctnstoc_rsrv = (float) $rowRw[13];
                                                                                                    $trsctnstoc_avlbl = (float) $rowRw[6];
                                                                                                    $trsctnstock_tot_cost = (float) $rowRw[7];
                                                                                                    $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnstock_tot_cost;
                                                                                                    $trsctnUomNm = str_replace(" ", "&nbsp;", $rowRw[9]);
                                                                                                    $trsctnbals_rmrks = $rowRw[10];
                                                                                                    $trsctnbals_date = $rowRw[8];
                                                                                                    //$trsctnbals_cnsgnid = $rowRw[11];
                                                                                                    $isParent = "0";
                                                                                                    $hsSbldgr = "0";
                                                                                                    if ($isParent == "1" || $hsSbldgr == "1") {
                                                                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                                                                        $nameStyle1 = "font-weight:bold;";
                                                                                                    }
                                                                                                }
                                                                                                $cntr += 1;
                                                                                                ?>
                                                                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                                                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" title="<?php echo $trsctnItmNm; ?>" >
                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemID" value="-1" style="width:100% !important;">  
                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="0" style="width:100% !important;">  
                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemCode" value="" style="width:100% !important;">  
                                                                                                        <span><?php echo $trsctnItmNm; ?></span>             
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="">
                                                                                                        <span><?php echo $trsctnbals_storenm; ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="">
                                                                                                        <span><?php echo $trsctnbals_trnstyp; ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="text-align:left;font-weight:bold;">
                                                                                                        <span><?php echo $trsctnUomNm; ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                        <span><?php echo number_format($trsctnstock_tot_qty); ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;">
                                                                                                        <span><?php echo number_format($trsctnstoc_rsrv); ?></span>
                                                                                                    </td>
                                                                                                    <!--<td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                        <span><?php echo number_format($trsctnstoc_avlbl); ?></span>
                                                                                                    </td>-->
                                                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;">
                                                                                                        <span><?php echo number_format($trsctnstock_tot_cost); ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="text-align:left;font-weight:bold;">
                                                                                                        <span><?php echo $trsctnbals_date; ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="text-align:left;font-weight:bold;">
                                                                                                        <span><?php echo $trsctnbals_rmrks; ?></span>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                <?php
                                                            } else if ($vwtyp == 50) {
                                                                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Incorrect Stock/Consignment Balances</span>
				</li>
                               </ul>
                              </div>";
                                                                echo $cntent;
                                                                ?>
                                                                <form class="form-horizontal" id="accbFSRptForm">
                                                                    <div class="row">
                                                                        <?php
                                                                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                                                                        //echo $ymdtme;
                                                                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                                                                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                                                                        $ymdtme3 = "01" . $ymdtme;
                                                                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                                                                        $accbFSRptItemCodeID = isset($_POST['accbFSRptItemCodeID']) ? (int) cleanInputData($_POST['accbFSRptItemCodeID']) : -1;
                                                                        $accbFSRptItemCode = isset($_POST['accbFSRptItemCode']) ? cleanInputData($_POST['accbFSRptItemCode']) : "";
                                                                        $accbFSRptStoreID = isset($_POST['accbFSRptStoreID']) ? (int) cleanInputData($_POST['accbFSRptStoreID']) : $selectedStoreID;
                                                                        $accbFSRptStore = isset($_POST['accbFSRptStore']) ? cleanInputData($_POST['accbFSRptStore']) : $acsCntrlGrpNm;
                                                                        $accbFSRptCtgryID = isset($_POST['accbFSRptCtgryID']) ? (int) cleanInputData($_POST['accbFSRptCtgryID']) : -1;
                                                                        $accbFSRptCtgry = isset($_POST['accbFSRptCtgry']) ? cleanInputData($_POST['accbFSRptCtgry']) : "";
                                                                        $accbFSRptItemType = isset($_POST['accbFSRptItemType']) ? cleanInputData($_POST['accbFSRptItemType']) : "";
                                                                        $accbFSRptQTYType = isset($_POST['accbFSRptQTYType']) ? cleanInputData($_POST['accbFSRptQTYType']) : "";
                                                                        $accbFSRptMinQTY = isset($_POST['accbFSRptMinQTY']) ? (float) cleanInputData($_POST['accbFSRptMinQTY']) : 1;
                                                                        $accbFSRptMaxQTY = isset($_POST['accbFSRptMaxQTY']) ? (float) cleanInputData($_POST['accbFSRptMaxQTY']) : 9999999999;
                                                                        $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3, 0, 11);
                                                                        $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                                                                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                                                                        $accbFSRptDte1 = $accbFSRptDte;
                                                                        $fsrptRunID = -1;
                                                                        if ($startRunng == 1) {
                                                                            $fsrptRunID = getNewFSRptRunID();
                                                                            if ($accbStrtFSRptDte != "") {
                                                                                $accbStrtFSRptDte = cnvrtDMYToYMD($accbStrtFSRptDte);
                                                                            }
                                                                            if ($accbFSRptDte != "") {
                                                                                $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                                                                            }
                                                                            $strSql = "select inv.populate_wrng_bals(" . $fsrptRunID . ",
                                                  " . $accbFSRptItemCodeID . ",
                                                  " . $accbFSRptStoreID . ",
                                                  " . $accbFSRptCtgryID . ",
                                                  '" . loc_db_escape_string($accbFSRptItemType) . "',
                                                  '" . loc_db_escape_string($accbFSRptQTYType) . "',
                                                  " . $accbFSRptMinQTY . ",
                                                  " . $accbFSRptMaxQTY . ",
                                                  '" . $accbFSRptDte . "',
                                                  " . $usrID . ",
                                                  to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                                                  " . $orgID . ",-1);";
                                                                            $result = executeSQLNoParams($strSql);
                                                                        }
                                                                        //Auto-Correct Gl Imbalances
                                                                        $reportTitle1 = "Auto-Correct Inventory Imbalances";
                                                                        $reportName1 = "Auto-Correct Inventory Imbalances";
                                                                        $rptID1 = getRptID($reportName1);
                                                                        ?>
                                                                        <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">
                                                                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                                                <legend class="basic_person_lg">
                                                                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                    </a>
                                                                                </legend>
                                                                                <div class="col-md-12" style="display:none;">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptItemCode" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Code/Name:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <div class="input-group">
                                                                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptItemCode" name="accbFSRptItemCode" value="<?php echo $accbFSRptItemCode; ?>" style="width:100%;" readonly="true">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptItemCodeID" name="accbFSRptItemCodeID" value="<?php echo $accbFSRptItemCodeID; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptItemCodeID', 'accbFSRptItemCode', 'clear', 1, '', function () {});">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12" style="display:none;">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptStore" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Store:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <div class="input-group">
                                                                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptStore" name="accbFSRptStore" value="<?php echo $accbFSRptStore; ?>" style="width:100%;" readonly="true">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptStoreID" name="accbFSRptStoreID" value="<?php echo $accbFSRptStoreID; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Users\' Sales Stores', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '', 'accbFSRptStoreID', 'accbFSRptStore', 'clear', 1, '', function () {});">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12" style="display:none;">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptCtgry" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Category:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <div class="input-group">
                                                                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptCtgry" name="accbFSRptCtgry" value="<?php echo $accbFSRptCtgry; ?>" style="width:100%;" readonly="true">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptCtgryID" name="accbFSRptCtgryID" value="<?php echo $accbFSRptCtgryID; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Categories', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptCtgryID', 'accbFSRptCtgry', 'clear', 1, '', function () {});">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12" style="display:none;">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptItemType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Item Type:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptItemType">
                                                                                                <?php
                                                                                                $valslctdArry = array("", "", "", "",
                                                                                                    "");
                                                                                                $srchInsArrys = array("", "Merchandise Inventory",
                                                                                                    "Non-Merchandise Inventory",
                                                                                                    "Fixed Assets",
                                                                                                    "Expense Item");
                                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                    if ($accbFSRptItemType == $srchInsArrys[$z]) {
                                                                                                        $valslctdArry[$z] = "selected";
                                                                                                    }
                                                                                                    ?>
                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12" style="display:none;">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptQTYType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">QTY Type:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptQTYType">
                                                                                                <?php
                                                                                                $valslctdArry = array("", "", "", "",
                                                                                                    "");
                                                                                                $srchInsArrys = array("", "Total",
                                                                                                    "Reservations", "Available");
                                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                    if ($accbFSRptQTYType == $srchInsArrys[$z]) {
                                                                                                        $valslctdArry[$z] = "selected";
                                                                                                    }
                                                                                                    ?>
                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12" style="display:none;">
                                                                                    <div class="form-group">
                                                                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                                            <label style="margin-bottom:0px !important;">QTY From:</label>
                                                                                        </div>
                                                                                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                                                                            <input type="number" min="0" max="9999999999" class="form-control" aria-label="..." id="accbFSRptMinQTY" name="accbFSRptMinQTY" value="<?php echo $accbFSRptMinQTY; ?>">
                                                                                        </div>
                                                                                        <div class="col-md-1" style="padding:5px 1px 0px 1px !important;">
                                                                                            <label style="margin-bottom:0px !important;">&nbsp;to:&nbsp;</label>
                                                                                        </div>
                                                                                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                                                                            <input type="number" min="0" max="9999999999" class="form-control" aria-label="..." id="accbFSRptMaxQTY" name="accbFSRptMaxQTY" value="<?php echo $accbFSRptMaxQTY; ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div> 
                                                                                <div  class="col-md-12" style="display:none;">
                                                                                    <div class="form-group">
                                                                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                                            <label style="margin-bottom:0px !important;">As At Date:</label>
                                                                                        </div>
                                                                                        <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">           
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=12&typ=1&pg=9&vtyp=50');">
                                                                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        Generate Report
                                                                                    </button>
                                                                                </div>
                                                                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">                
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID1; ?>');" title="Auto-Correct Inventory Imbalance">
                                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">                                            
                                                                                    </button>
                                                                                </div>
                                                                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=12&typ=1&pg=9&vtyp=50');">
                                                                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </div>
                                                                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </div> 
                                                                            </fieldset>
                                                                        </div>
                                                                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                                                                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px;">
                                                                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                                                <span aria-hidden="true">&raquo;</span>
                                                                                            </a> INCORRECT STOCK/CONSIGNMENT BALANCES AS AT <?php echo strtoupper($accbFSRptDte1); ?>
                                                                                        </caption>
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th style="max-width:40px;width:40px;">No.</th>
                                                                                                <th style="max-width:250px !important;">Item Code/Description</th>
                                                                                                <th style="max-width:70px;width:70px;">Base UOM</th>
                                                                                                <th style="">Store</th>
                                                                                                <th style="text-align: right;">CS Total QTY</th>
                                                                                                <th style="text-align: right;">CS Reservations</th>
                                                                                                <th style="text-align: right;">CS Available QTY</th>
                                                                                                <th style="text-align: right;">STCK Total QTY</th>
                                                                                                <th style="text-align: right;">STCK Reservations</th>
                                                                                                <th style="text-align: right;">STCK Available QTY</th>
                                                                                                <th style="max-width:110px;width:110px;">Balance Date</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>   
                                                                                            <?php
                                                                                            $cntr = 0;
                                                                                            $maxNoRows = 0;
                                                                                            $resultRw = null;
                                                                                            if ($fsrptRunID > 0) {
                                                                                                $resultRw = get_InvWrngBalsRpt($fsrptRunID, $accbFSRptDte);
                                                                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                                                            }
                                                                                            $ttlTrsctnDbtAmnt = 0;
                                                                                            $ttlTrsctnCrdtAmnt = 0;
                                                                                            $ttlTrsctnNetAmnt = 0;
                                                                                            while ($cntr < $maxNoRows) {
                                                                                                $rowNumber = 0;
                                                                                                $trsctnItmNm = "";
                                                                                                $trsctnUomNm = "";
                                                                                                $trsctnStoreNm = "";
                                                                                                $trsctnconsg_tot_qty = 0;
                                                                                                $trsctnconsg_rsrv = 0;
                                                                                                $trsctnconsg_avlbl = 0;
                                                                                                $trsctnstock_tot_qty = 0;
                                                                                                $trsctnstoc_rsrv = 0;
                                                                                                $trsctnstoc_avlbl = 0;
                                                                                                $trsctnbals_date = "";
                                                                                                $numStyle1 = "text-align:right;";
                                                                                                $nameStyle1 = "";
                                                                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                                    $rowNumber = (float) $rowRw[0];
                                                                                                    $trsctnItmNm = str_replace(" ", "&nbsp;", $rowRw[1]);
                                                                                                    $trsctnUomNm = str_replace(" ", "&nbsp;", $rowRw[2]);
                                                                                                    $trsctnStoreNm = str_replace(" ", "&nbsp;", $rowRw[3]);
                                                                                                    $trsctnconsg_tot_qty = (float) $rowRw[4];
                                                                                                    $trsctnconsg_rsrv = (float) $rowRw[5];
                                                                                                    $trsctnconsg_avlbl = (float) $rowRw[6];
                                                                                                    $trsctnstock_tot_qty = (float) $rowRw[7];
                                                                                                    $trsctnstoc_rsrv = (float) $rowRw[8];
                                                                                                    $trsctnstoc_avlbl = (float) $rowRw[9];
                                                                                                    $trsctnbals_date = $rowRw[10];
                                                                                                    $isParent = "0";
                                                                                                    $hsSbldgr = "0";
                                                                                                    if ($isParent == "1" || $hsSbldgr == "1") {
                                                                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                                                                        $nameStyle1 = "font-weight:bold;";
                                                                                                    }
                                                                                                }
                                                                                                $cntr += 1;
                                                                                                ?>
                                                                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                                                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $trsctnItmNm; ?>" >
                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemID" value="-1" style="width:100% !important;">  
                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="0" style="width:100% !important;">  
                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemCode" value="" style="width:100% !important;">  
                                                                                                        <span><?php echo $trsctnItmNm; ?></span>             
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="">
                                                                                                        <span><?php echo $trsctnUomNm; ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="">
                                                                                                        <span><?php echo $trsctnStoreNm; ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                        <span><?php echo number_format($trsctnconsg_tot_qty); ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                        <span><?php echo number_format($trsctnconsg_rsrv); ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                        <span><?php echo number_format($trsctnconsg_avlbl); ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                        <span><?php echo number_format($trsctnstock_tot_qty); ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                        <span><?php echo number_format($trsctnstoc_rsrv); ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                        <span><?php echo number_format($trsctnstoc_avlbl); ?></span>
                                                                                                    </td>
                                                                                                    <td class="lovtd" style="">
                                                                                                        <span><?php echo $trsctnbals_date; ?></span>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                <?php
                                                            } else if ($vwtyp == 60) {
                                                                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                                                <span style=\"text-decoration:none;\">Money Received Report (Payments)</span>
                                                                            </li>
                                                                           </ul>
                                                                          </div>";
                                                                echo $cntent;
                                                                ?>
                                                                <form class="form-horizontal" id="accbFSRptForm">
                                                                    <div class="row">
                                                                        <?php
                                                                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                                                                        //echo $ymdtme;
                                                                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                                                                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                                                                        $ymdtme3 = "01" . $ymdtme;
                                                                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                                                                        $accbFSRptRptType = "Money Received Report (Payments Received)";
                                                                        $accbFSRptDocType = isset($_POST['accbFSRptDocType']) ? cleanInputData($_POST['accbFSRptDocType']) : "Sales Invoice";
                                                                        $accbFSRptSortBy = isset($_POST['accbFSRptSortBy']) ? cleanInputData($_POST['accbFSRptSortBy']) : "TOTAL AMOUNT";
                                                                        $accbFSRptCreatedByID = isset($_POST['accbFSRptCreatedByID']) ? (int) cleanInputData($_POST['accbFSRptCreatedByID']) : -1;
                                                                        $accbFSRptCreatedBy = isset($_POST['accbFSRptCreatedBy']) ? cleanInputData($_POST['accbFSRptCreatedBy']) : "";
                                                                        if ($canRn4Othrs === false) {
                                                                            $accbFSRptCreatedByID = $usrID;
                                                                            $accbFSRptCreatedBy = getUserName($usrID);
                                                                        }
                                                                        $accbFSRptUseCreationDte = isset($_POST['accbFSRptUseCreationDte']) ? (cleanInputData($_POST['accbFSRptUseCreationDte']) === "YES"
                                                                                    ? "1" : "0") : "1";
                                                                        $shwSmmryChkd = "";
                                                                        if ($accbFSRptUseCreationDte == "1") {
                                                                            $shwSmmryChkd = "checked=\"true\"";
                                                                        }

                                                                        $accbFSRptItemCodeID = isset($_POST['accbFSRptItemCodeID']) ? (int) cleanInputData($_POST['accbFSRptItemCodeID']) : -1;
                                                                        $accbFSRptItemCode = isset($_POST['accbFSRptItemCode']) ? cleanInputData($_POST['accbFSRptItemCode']) : "";
                                                                        $accbFSRptStoreID = isset($_POST['accbFSRptStoreID']) ? (int) cleanInputData($_POST['accbFSRptStoreID']) : $selectedStoreID;
                                                                        $accbFSRptStore = isset($_POST['accbFSRptStore']) ? cleanInputData($_POST['accbFSRptStore']) : $acsCntrlGrpNm;
                                                                        $accbFSRptCtgryID = isset($_POST['accbFSRptCtgryID']) ? (int) cleanInputData($_POST['accbFSRptCtgryID']) : -1;
                                                                        $accbFSRptCtgry = isset($_POST['accbFSRptCtgry']) ? cleanInputData($_POST['accbFSRptCtgry']) : "";
                                                                        $accbFSRptItemType = isset($_POST['accbFSRptItemType']) ? cleanInputData($_POST['accbFSRptItemType']) : "";
                                                                        $accbFSRptQTYType = isset($_POST['accbFSRptQTYType']) ? cleanInputData($_POST['accbFSRptQTYType']) : "";
                                                                        $accbFSRptMinQTY = isset($_POST['accbFSRptMinQTY']) ? (float) cleanInputData($_POST['accbFSRptMinQTY']) : 1;
                                                                        $accbFSRptMaxQTY = isset($_POST['accbFSRptMaxQTY']) ? (float) cleanInputData($_POST['accbFSRptMaxQTY']) : 9999999999;
                                                                        $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3, 0, 11);
                                                                        $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                                                                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                                                                        $accbFSRptDte1 = $accbFSRptDte;
                                                                        $fsrptRunID = -1;
                                                                        if ($startRunng == 1) {
                                                                            $fsrptRunID = getNewFSRptRunID();
                                                                            /* if ($accbFSRptDte != "") {
                                                                              $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                                                                              } */
                                                                            $strSql = "select scm.populate_money_rcvd(" . $fsrptRunID . ",
                                                                                            '" . loc_db_escape_string($accbFSRptRptType) . "',
                                                                                            '" . loc_db_escape_string($accbFSRptDocType) . "',
                                                                                            '" . loc_db_escape_string($accbFSRptSortBy) . "',
                                                                                            " . $accbFSRptCreatedByID . ",
                                                                                            '" . loc_db_escape_string($accbFSRptUseCreationDte) . "',
                                                                                            '" . $accbStrtFSRptDte . "',
                                                                                                '" . $accbFSRptDte . "',
                                                                                                " . $usrID . ",
                                                                                                to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                                                                                                " . $orgID . ",-1);";
                                                                            //echo $strSql;
                                                                            $result = executeSQLNoParams($strSql);
                                                                        }
                                                                        ?>
                                                                        <div class="col-md-3" style="padding:5px 1px 0px 15px;" id="leftDivFSRpt">
                                                                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                                                <legend class="basic_person_lg">
                                                                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                    </a>
                                                                                </legend>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;color:red;font-style: italic;">NB: Not Restricting your search using these parameters can cause your report to run for hours unending!!!</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                                    <div class="form-check" style="font-size: 12px !important;">
                                                                                        <label class="form-check-label">
                                                                                            <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptUseCreationDte" name="accbFSRptUseCreationDte" <?php echo $shwSmmryChkd; ?>>
                                                                                            Use Creation Date
                                                                                        </label>
                                                                                    </div>                            
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptDocType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Document Type:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptDocType">
                                                                                                <?php
                                                                                                $valslctdArry = array("", "", "", "", "", "");
                                                                                                $srchInsArrys = array("Pro-Forma Invoice", "Sales Order", "Sales Invoice", "Internal Item Request",
                                                                                                    "Item Issue-Unbilled", "Sales Return");
                                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                    if ($accbFSRptDocType == $srchInsArrys[$z]) {
                                                                                                        $valslctdArry[$z] = "selected";
                                                                                                    }
                                                                                                    ?>
                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>				 
                                                                                <div  class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                                            <label style="margin-bottom:0px !important;">From Date:</label>
                                                                                        </div>
                                                                                        <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                                            <input class="form-control" size="16" type="text" id="accbStrtFSRptDte" name="accbStrtFSRptDte" value="<?php echo $accbStrtFSRptDte1; ?>" placeholder="From Date">
                                                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div  class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                                            <label style="margin-bottom:0px !important;">To Date:</label>
                                                                                        </div>
                                                                                        <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptCreatedBy" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Created By:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <div class="input-group">
                                                                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptCreatedBy" name="accbFSRptCreatedBy" value="<?php echo $accbFSRptCreatedBy; ?>" style="width:100%;" readonly="true">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptCreatedByID" name="accbFSRptCreatedByID" value="<?php echo $accbFSRptCreatedByID; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Users', '', '', '', 'radio', true, '', 'accbFSRptCreatedByID', 'accbFSRptCreatedBy', 'clear', 1, '', function () {});">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="accbFSRptSortBy" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Sort By:</label>
                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptSortBy">
                                                                                                <?php
                                                                                                $valslctdArry = array("", "");
                                                                                                $srchInsArrys = array("QTY", "TOTAL AMOUNT");
                                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                    if ($accbFSRptSortBy == $srchInsArrys[$z]) {
                                                                                                        $valslctdArry[$z] = "selected";
                                                                                                    }
                                                                                                    ?>
                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=12&typ=1&pg=9&vtyp=60');">
                                                                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        Generate Report
                                                                                    </button>
                                                                                </div>
                                                                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=12&typ=1&pg=9&vtyp=60');">
                                                                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </div>
                                                                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </div>
                                                                            </fieldset>
                                                                        </div>
                                                                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                                                                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbFSRptTblSctn"> 
                                                                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                                                        <div id="accbRcnclGlStatemtLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                                                            <div class="row"> 
                                                                                                <div class="col-md-12">
                                                                                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                                                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                                                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                                                                <span aria-hidden="true">&raquo;</span>
                                                                                                            </a><?php echo strtoupper($accbFSRptRptType); ?> FROM <?php echo strtoupper($accbStrtFSRptDte1); ?> TO <?php echo strtoupper($accbFSRptDte1); ?>
                                                                                                        </caption>
                                                                                                        <thead>
                                                                                                            <tr>
                                                                                                                <th style="max-width:40px;width:40px;">No.</th>
                                                                                                                <th style="max-width:250px !important;">Document No.</th>
                                                                                                                <th style="text-align: right;min-width:70px;">Invoice Amount</th>
                                                                                                                <th style="text-align: right;min-width:70px;">Discount Amount</th>
                                                                                                                <th style="text-align: right;min-width:70px;">Amount Paid</th>
                                                                                                                <th style="text-align: right;min-width:70px;">Outstanding Amount</th>
                                                                                                                <th style="text-align: left;min-width:120px;">Payment Date</th>
                                                                                                            </tr>
                                                                                                        </thead>
                                                                                                        <tbody>   
                                                                                                            <?php
                                                                                                            $cntr = 0;
                                                                                                            $maxNoRows = 0;
                                                                                                            $resultRw = null;
                                                                                                            if ($fsrptRunID > 0) {
                                                                                                                $resultRw = get_PymtsMoneyRcvd($fsrptRunID, $accbStrtFSRptDte, $accbFSRptDte);
                                                                                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                                                                            }
                                                                                                            $ttlTrsctnDbtAmnt = 0;
                                                                                                            $ttlTrsctnCrdtAmnt = 0;
                                                                                                            $ttlTrsctnNetAmnt = 0;
                                                                                                            $ttlTrsctnOutsAmnt = 0;
                                                                                                            $trsctnbals_crny = "";
                                                                                                            while ($cntr < $maxNoRows) {
                                                                                                                $rowNumber = 0;
                                                                                                                $trsctnItmNm = "";
                                                                                                                $trsctnUomNm = "";
                                                                                                                $trsctnCtgryNm = "";
                                                                                                                $trsctnstock_tot_qty = 0;
                                                                                                                $trsctnstoc_rsrv = 0;
                                                                                                                $trsctnstoc_avlbl = 0;
                                                                                                                $trsctnstock_tot_cost = 0;
                                                                                                                $trsctnbals_date = "";
                                                                                                                $numStyle1 = "text-align:right;";
                                                                                                                $nameStyle1 = "";
                                                                                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                                                    $rowNumber = (float) $rowRw[0];
                                                                                                                    $trsctnDocNum = str_replace(" ", "&nbsp;", $rowRw[1]);
                                                                                                                    $trsctnInvc_amnt = (float) $rowRw[2];
                                                                                                                    $trsctnDscnt_amnt = (float) $rowRw[3];
                                                                                                                    $trsctnAmnt_paid = (float) $rowRw[4];
                                                                                                                    $trsctnOutstand_amnt = (float) $rowRw[5];
                                                                                                                    $trsctnbals_date = $rowRw[6];

                                                                                                                    $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + $trsctnInvc_amnt;
                                                                                                                    $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + $trsctnDscnt_amnt;
                                                                                                                    $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnAmnt_paid;
                                                                                                                    $ttlTrsctnOutsAmnt = $ttlTrsctnOutsAmnt + $trsctnOutstand_amnt;
                                                                                                                    $isParent = "0";
                                                                                                                    $hsSbldgr = "0";
                                                                                                                    if ($isParent == "1" || $hsSbldgr == "1") {
                                                                                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                                                                                        $nameStyle1 = "font-weight:bold;";
                                                                                                                    }
                                                                                                                }
                                                                                                                $cntr += 1;
                                                                                                                ?>
                                                                                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                                                                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                                                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $trsctnDocNum; ?>" >
                                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemID" value="-1" style="width:100% !important;">  
                                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="0" style="width:100% !important;">  
                                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemCode" value="" style="width:100% !important;">  
                                                                                                                        <span><?php echo $trsctnDocNum; ?></span>             
                                                                                                                    </td>
                                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                                        <span><?php echo number_format($trsctnInvc_amnt, 2); ?></span>
                                                                                                                    </td>
                                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                                        <span><?php echo number_format($trsctnDscnt_amnt, 2); ?></span>
                                                                                                                    </td>
                                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                                        <span><?php echo number_format($trsctnAmnt_paid, 2); ?></span>
                                                                                                                    </td>
                                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                                        <span><?php echo number_format($trsctnOutstand_amnt, 2); ?></span>
                                                                                                                    </td>
                                                                                                                    <td class="lovtd" style="text-align:left;font-weight:bold;">
                                                                                                                        <span><?php echo $trsctnbals_date; ?></span>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <?php
                                                                                                            }
                                                                                                            ?>
                                                                                                        </tbody>
                                                                                                        <tfoot>
                                                                                                            <tr>
                                                                                                                <th style="max-width:40px;width:40px;">&nbsp;</th>
                                                                                                                <th style="max-width:250px !important;">TOTALS:</th>
                                                                                                                <th style="text-align: right;">&nbsp;</th>
                                                                                                                <th style="text-align: right;">&nbsp;</th>
                                                                                                                <th class="lovtd" style="text-align:right;font-weight:bold;color:red;">
                                                                                                                    <span><?php
                                                                                                                        echo number_format($ttlTrsctnNetAmnt, 2);
                                                                                                                        ?></span>
                                                                                                                </th>
                                                                                                                <th style="text-align: right;">&nbsp;</th>
                                                                                                                <th style="text-align: right;">&nbsp;</th>
                                                                                                            </tr>
                                                                                                        </tfoot>
                                                                                                    </table>
                                                                                                </div>
                                                                                                </fieldset>
                                                                                            </div>
                                                                                        </div>
                                                                                        </form>
                                                                                        <?php
                                                                                    } else if ($vwtyp == 70) {
                                                                                        $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                                                <span style=\"text-decoration:none;\">Money Received Report (Documents)</span>
                                                                            </li>
                                                                           </ul>
                                                                          </div>";
                                                                                        echo $cntent;
                                                                                        ?>
                                                                                        <form class="form-horizontal" id="accbFSRptForm">
                                                                                            <div class="row">
                                                                                                <?php
                                                                                                $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                                                                                                //echo $ymdtme;
                                                                                                $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                                                                                                $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                                                                                                $ymdtme3 = "01" . $ymdtme;
                                                                                                $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                                                                                                $accbFSRptRptType = "Money Received Report (Documents Created)";
                                                                                                $accbFSRptDocType = isset($_POST['accbFSRptDocType']) ? cleanInputData($_POST['accbFSRptDocType']) : "Sales Invoice";
                                                                                                $accbFSRptSortBy = isset($_POST['accbFSRptSortBy']) ? cleanInputData($_POST['accbFSRptSortBy']) : "TOTAL AMOUNT";
                                                                                                $accbFSRptCreatedByID = isset($_POST['accbFSRptCreatedByID']) ? (int) cleanInputData($_POST['accbFSRptCreatedByID'])
                                                                                                            : -1;
                                                                                                $accbFSRptCreatedBy = isset($_POST['accbFSRptCreatedBy']) ? cleanInputData($_POST['accbFSRptCreatedBy'])
                                                                                                            : "";
                                                                                                if ($canRn4Othrs === false) {
                                                                                                    $accbFSRptCreatedByID = $usrID;
                                                                                                    $accbFSRptCreatedBy = getUserName($usrID);
                                                                                                }
                                                                                                $accbFSRptUseCreationDte = isset($_POST['accbFSRptUseCreationDte']) ? (cleanInputData($_POST['accbFSRptUseCreationDte'])
                                                                                                        === "YES" ? "1" : "0") : "1";
                                                                                                $shwSmmryChkd = "";
                                                                                                if ($accbFSRptUseCreationDte == "1") {
                                                                                                    $shwSmmryChkd = "checked=\"true\"";
                                                                                                }

                                                                                                $accbFSRptItemCodeID = isset($_POST['accbFSRptItemCodeID']) ? (int) cleanInputData($_POST['accbFSRptItemCodeID'])
                                                                                                            : -1;
                                                                                                $accbFSRptItemCode = isset($_POST['accbFSRptItemCode']) ? cleanInputData($_POST['accbFSRptItemCode']) : "";
                                                                                                $accbFSRptStoreID = isset($_POST['accbFSRptStoreID']) ? (int) cleanInputData($_POST['accbFSRptStoreID'])
                                                                                                            : $selectedStoreID;
                                                                                                $accbFSRptStore = isset($_POST['accbFSRptStore']) ? cleanInputData($_POST['accbFSRptStore']) : $acsCntrlGrpNm;
                                                                                                $accbFSRptCtgryID = isset($_POST['accbFSRptCtgryID']) ? (int) cleanInputData($_POST['accbFSRptCtgryID'])
                                                                                                            : -1;
                                                                                                $accbFSRptCtgry = isset($_POST['accbFSRptCtgry']) ? cleanInputData($_POST['accbFSRptCtgry']) : "";
                                                                                                $accbFSRptItemType = isset($_POST['accbFSRptItemType']) ? cleanInputData($_POST['accbFSRptItemType']) : "";
                                                                                                $accbFSRptQTYType = isset($_POST['accbFSRptQTYType']) ? cleanInputData($_POST['accbFSRptQTYType']) : "";
                                                                                                $accbFSRptMinQTY = isset($_POST['accbFSRptMinQTY']) ? (float) cleanInputData($_POST['accbFSRptMinQTY']) : 1;
                                                                                                $accbFSRptMaxQTY = isset($_POST['accbFSRptMaxQTY']) ? (float) cleanInputData($_POST['accbFSRptMaxQTY']) : 9999999999;
                                                                                                $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3,
                                                                                                                0, 11);
                                                                                                $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                                                                                                $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2,
                                                                                                                0, 11);
                                                                                                $accbFSRptDte1 = $accbFSRptDte;
                                                                                                $fsrptRunID = -1;
                                                                                                if ($startRunng == 1) {
                                                                                                    $fsrptRunID = getNewFSRptRunID();
                                                                                                    /* if ($accbFSRptDte != "") {
                                                                                                      $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                                                                                                      } */
                                                                                                    $strSql = "select scm.populate_money_rcvd(" . $fsrptRunID . ",
                                                                                            '" . loc_db_escape_string($accbFSRptRptType) . "',
                                                                                            '" . loc_db_escape_string($accbFSRptDocType) . "',
                                                                                            '" . loc_db_escape_string($accbFSRptSortBy) . "',
                                                                                            " . $accbFSRptCreatedByID . ",
                                                                                            '" . loc_db_escape_string($accbFSRptUseCreationDte) . "',
                                                                                            '" . $accbStrtFSRptDte . "',
                                                                                                '" . $accbFSRptDte . "',
                                                                                                " . $usrID . ",
                                                                                                to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                                                                                                " . $orgID . ",-1);";
                                                                                                    //echo $strSql;
                                                                                                    $result = executeSQLNoParams($strSql);
                                                                                                }
                                                                                                ?>
                                                                                                <div class="col-md-3" style="padding:5px 1px 0px 15px;" id="leftDivFSRpt">
                                                                                                    <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                                                                        <legend class="basic_person_lg">
                                                                                                            Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                                                                                                <span aria-hidden="true">&laquo;</span>
                                                                                                            </a>
                                                                                                        </legend>
                                                                                                        <div class="col-md-12">
                                                                                                            <div class="form-group">
                                                                                                                <label class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;color:red;font-style: italic;">NB: Not Restricting your search using these parameters can cause your report to run for hours unending!!!</label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                                                                <label class="form-check-label">
                                                                                                                    <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptUseCreationDte" name="accbFSRptUseCreationDte" <?php echo $shwSmmryChkd; ?>>
                                                                                                                    Use Creation Date
                                                                                                                </label>
                                                                                                            </div>                            
                                                                                                        </div>
                                                                                                        <div class="col-md-12">
                                                                                                            <div class="form-group">
                                                                                                                <label for="accbFSRptDocType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Document Type:</label>
                                                                                                                <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptDocType">
                                                                                                                        <?php
                                                                                                                        $valslctdArry = array("", "", "", "", "", "");
                                                                                                                        $srchInsArrys = array("Pro-Forma Invoice", "Sales Order", "Sales Invoice",
                                                                                                                            "Internal Item Request",
                                                                                                                            "Item Issue-Unbilled", "Sales Return");
                                                                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                                            if ($accbFSRptDocType == $srchInsArrys[$z]) {
                                                                                                                                $valslctdArry[$z] = "selected";
                                                                                                                            }
                                                                                                                            ?>
                                                                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                                        <?php } ?>
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>				 
                                                                                                        <div  class="col-md-12">
                                                                                                            <div class="form-group">
                                                                                                                <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                                                                    <label style="margin-bottom:0px !important;">From Date:</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                                                                    <input class="form-control" size="16" type="text" id="accbStrtFSRptDte" name="accbStrtFSRptDte" value="<?php echo $accbStrtFSRptDte1; ?>" placeholder="From Date">
                                                                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div  class="col-md-12">
                                                                                                            <div class="form-group">
                                                                                                                <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                                                                    <label style="margin-bottom:0px !important;">To Date:</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                                                                    <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                                                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-12">
                                                                                                            <div class="form-group">
                                                                                                                <label for="accbFSRptCreatedBy" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Created By:</label>
                                                                                                                <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                                                    <div class="input-group">
                                                                                                                        <input type="text" class="form-control" aria-label="..." id="accbFSRptCreatedBy" name="accbFSRptCreatedBy" value="<?php echo $accbFSRptCreatedBy; ?>" style="width:100%;" readonly="true">
                                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="accbFSRptCreatedByID" name="accbFSRptCreatedByID" value="<?php echo $accbFSRptCreatedByID; ?>">
                                                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Users', '', '', '', 'radio', true, '', 'accbFSRptCreatedByID', 'accbFSRptCreatedBy', 'clear', 1, '', function () {});">
                                                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                                                        </label>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-12">
                                                                                                            <div class="form-group">
                                                                                                                <label for="accbFSRptSortBy" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Sort By:</label>
                                                                                                                <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptSortBy">
                                                                                                                        <?php
                                                                                                                        $valslctdArry = array("", "");
                                                                                                                        $srchInsArrys = array("QTY", "TOTAL AMOUNT");
                                                                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                                            if ($accbFSRptSortBy == $srchInsArrys[$z]) {
                                                                                                                                $valslctdArry[$z] = "selected";
                                                                                                                            }
                                                                                                                            ?>
                                                                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                                        <?php } ?>
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=12&typ=1&pg=9&vtyp=70');">
                                                                                                                <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                                Generate Report
                                                                                                            </button>
                                                                                                        </div>
                                                                                                        <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=12&typ=1&pg=9&vtyp=70');">
                                                                                                                <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                            </button>
                                                                                                        </div>
                                                                                                        <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                                                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                            </button>
                                                                                                        </div>
                                                                                                    </fieldset>
                                                                                                </div>
                                                                                                <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                                                                                                    <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                                                                        <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbFSRptTblSctn"> 
                                                                                                            <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                                                                                <div id="accbRcnclGlStatemtLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                                                                                    <div class="row"> 
                                                                                                                        <div class="col-md-12">
                                                                                                                            <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                                                                                                <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                                                                                                    <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                                                                                        <span aria-hidden="true">&raquo;</span>
                                                                                                                                    </a><?php echo strtoupper($accbFSRptRptType); ?> FROM <?php echo strtoupper($accbStrtFSRptDte1); ?> TO <?php echo strtoupper($accbFSRptDte1); ?>
                                                                                                                                </caption>
                                                                                                                                <thead>
                                                                                                                                    <tr>
                                                                                                                                        <th style="max-width:40px;width:40px;">No.</th>
                                                                                                                                        <th style="max-width:250px !important;">Document No.</th>
                                                                                                                                        <th style="text-align: right;min-width:70px;">Invoice Amount</th>
                                                                                                                                        <th style="text-align: right;min-width:70px;">Discount Amount</th>
                                                                                                                                        <th style="text-align: right;min-width:70px;">Amount Paid</th>
                                                                                                                                        <th style="text-align: right;min-width:70px;">Outstanding Amount</th>
                                                                                                                                        <th style="text-align: left;min-width:120px;">Creation Date</th>
                                                                                                                                    </tr>
                                                                                                                                </thead>
                                                                                                                                <tbody>   
                                                                                                                                    <?php
                                                                                                                                    $cntr = 0;
                                                                                                                                    $maxNoRows = 0;
                                                                                                                                    $resultRw = null;
                                                                                                                                    if ($fsrptRunID > 0) {
                                                                                                                                        $resultRw = get_SalesMoneyRcvd($fsrptRunID, $accbStrtFSRptDte,
                                                                                                                                                $accbFSRptDte);
                                                                                                                                        $maxNoRows = loc_db_num_rows($resultRw);
                                                                                                                                    }
                                                                                                                                    $ttlTrsctnDbtAmnt = 0;
                                                                                                                                    $ttlTrsctnCrdtAmnt = 0;
                                                                                                                                    $ttlTrsctnNetAmnt = 0;
                                                                                                                                    $ttlTrsctnOutsAmnt = 0;
                                                                                                                                    $trsctnbals_crny = "";
                                                                                                                                    while ($cntr < $maxNoRows) {
                                                                                                                                        $rowNumber = 0;
                                                                                                                                        $trsctnItmNm = "";
                                                                                                                                        $trsctnUomNm = "";
                                                                                                                                        $trsctnCtgryNm = "";
                                                                                                                                        $trsctnstock_tot_qty = 0;
                                                                                                                                        $trsctnstoc_rsrv = 0;
                                                                                                                                        $trsctnstoc_avlbl = 0;
                                                                                                                                        $trsctnstock_tot_cost = 0;
                                                                                                                                        $trsctnbals_date = "";
                                                                                                                                        $numStyle1 = "text-align:right;";
                                                                                                                                        $nameStyle1 = "";
                                                                                                                                        if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                                                                            $rowNumber = (float) $rowRw[0];
                                                                                                                                            $trsctnDocNum = str_replace(" ", "&nbsp;", $rowRw[1]);
                                                                                                                                            $trsctnInvc_amnt = (float) $rowRw[2];
                                                                                                                                            $trsctnDscnt_amnt = (float) $rowRw[3];
                                                                                                                                            $trsctnAmnt_paid = (float) $rowRw[4];
                                                                                                                                            $trsctnOutstand_amnt = (float) $rowRw[5];
                                                                                                                                            $trsctnbals_date = $rowRw[6];

                                                                                                                                            $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + $trsctnInvc_amnt;
                                                                                                                                            $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + $trsctnDscnt_amnt;
                                                                                                                                            $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnAmnt_paid;
                                                                                                                                            $ttlTrsctnOutsAmnt = $ttlTrsctnOutsAmnt + $trsctnOutstand_amnt;
                                                                                                                                            $isParent = "0";
                                                                                                                                            $hsSbldgr = "0";
                                                                                                                                            if ($isParent == "1" || $hsSbldgr == "1") {
                                                                                                                                                $numStyle1 = "text-align:right;font-weight:bold;";
                                                                                                                                                $nameStyle1 = "font-weight:bold;";
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                        $cntr += 1;
                                                                                                                                        ?>
                                                                                                                                        <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                                                                                                                            <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                                                                                                            <td class="lovtd" style="<?php echo $nameStyle1; ?>max-width:350px !important;white-space: nowrap; width: 450px; overflow: hidden;text-overflow: ellipsis;" title="<?php echo $trsctnDocNum; ?>" >
                                                                                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemID" value="-1" style="width:100% !important;">  
                                                                                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="0" style="width:100% !important;">  
                                                                                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemCode" value="" style="width:100% !important;">  
                                                                                                                                                <span><?php echo $trsctnDocNum; ?></span>             
                                                                                                                                            </td>
                                                                                                                                            <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                                                                <span><?php echo number_format($trsctnInvc_amnt, 2); ?></span>
                                                                                                                                            </td>
                                                                                                                                            <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                                                                <span><?php echo number_format($trsctnDscnt_amnt, 2); ?></span>
                                                                                                                                            </td>
                                                                                                                                            <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                                                                <span><?php echo number_format($trsctnAmnt_paid, 2); ?></span>
                                                                                                                                            </td>
                                                                                                                                            <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                                                                <span><?php echo number_format($trsctnOutstand_amnt, 2); ?></span>
                                                                                                                                            </td>
                                                                                                                                            <td class="lovtd" style="text-align:left;font-weight:bold;">
                                                                                                                                                <span><?php echo $trsctnbals_date; ?></span>
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                        <?php
                                                                                                                                    }
                                                                                                                                    ?>
                                                                                                                                </tbody>
                                                                                                                                <tfoot>
                                                                                                                                    <tr>
                                                                                                                                        <th style="max-width:40px;width:40px;">&nbsp;</th>
                                                                                                                                        <th style="max-width:250px !important;">TOTALS:</th>
                                                                                                                                        <th class="lovtd" style="text-align:right;font-weight:bold;color:red;">
                                                                                                                                            <span><?php
                                                                                                                                                echo number_format($ttlTrsctnDbtAmnt, 2);
                                                                                                                                                ?></span>
                                                                                                                                        </th>
                                                                                                                                        <th class="lovtd" style="text-align:right;font-weight:bold;color:red;">
                                                                                                                                            <span><?php
                                                                                                                                                echo number_format($ttlTrsctnCrdtAmnt, 2);
                                                                                                                                                ?></span>
                                                                                                                                        </th>
                                                                                                                                        <th class="lovtd" style="text-align:right;font-weight:bold;color:red;">
                                                                                                                                            <span><?php
                                                                                                                                                echo number_format($ttlTrsctnNetAmnt, 2);
                                                                                                                                                ?></span>
                                                                                                                                        </th>
                                                                                                                                        <th class="lovtd" style="text-align:right;font-weight:bold;color:red;">
                                                                                                                                            <span><?php
                                                                                                                                                echo number_format($ttlTrsctnOutsAmnt, 2);
                                                                                                                                                ?></span>
                                                                                                                                        </th>
                                                                                                                                        <th style="text-align: right;">&nbsp;</th>
                                                                                                                                    </tr>
                                                                                                                                </tfoot>
                                                                                                                            </table>
                                                                                                                        </div>
                                                                                                                        </fieldset>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                </form>
                                                                                                                <?php
                                                                                                            } else if ($vwtyp == 80) {
                                                                                                                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                                                <span style=\"text-decoration:none;\">Items Sold/Issued Report</span>
                                                                            </li>
                                                                           </ul>
                                                                          </div>";
                                                                                                                echo $cntent;
                                                                                                                ?>
                                                                                                                <form class="form-horizontal" id="accbFSRptForm">
                                                                                                                    <div class="row">
                                                                                                                        <?php
                                                                                                                        $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                                                                                                                        //echo $ymdtme;
                                                                                                                        $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                                                                                                                        $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                                                                                                                        $ymdtme3 = "01" . $ymdtme;
                                                                                                                        $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng'])
                                                                                                                                    : 0;
                                                                                                                        $accbFSRptRptType = "Items Sold/Issued Report";
                                                                                                                        $accbFSRptDocType = isset($_POST['accbFSRptDocType']) ? cleanInputData($_POST['accbFSRptDocType'])
                                                                                                                                    : "Sales Invoice";
                                                                                                                        $accbFSRptSortBy = isset($_POST['accbFSRptSortBy']) ? cleanInputData($_POST['accbFSRptSortBy'])
                                                                                                                                    : "TOTAL AMOUNT";
                                                                                                                        $accbFSRptCreatedByID = isset($_POST['accbFSRptCreatedByID']) ? (int) cleanInputData($_POST['accbFSRptCreatedByID'])
                                                                                                                                    : -1;
                                                                                                                        $accbFSRptCreatedBy = isset($_POST['accbFSRptCreatedBy']) ? cleanInputData($_POST['accbFSRptCreatedBy'])
                                                                                                                                    : "";
                                                                                                                        if ($canRn4Othrs === false) {
                                                                                                                            $accbFSRptCreatedByID = $usrID;
                                                                                                                            $accbFSRptCreatedBy = getUserName($usrID);
                                                                                                                        }
                                                                                                                        $accbFSRptUseCreationDte = isset($_POST['accbFSRptUseCreationDte']) ? (cleanInputData($_POST['accbFSRptUseCreationDte'])
                                                                                                                                === "YES" ? "1" : "0") : "1";
                                                                                                                        $shwSmmryChkd = "";
                                                                                                                        if ($accbFSRptUseCreationDte == "1") {
                                                                                                                            $shwSmmryChkd = "checked=\"true\"";
                                                                                                                        }

                                                                                                                        $accbFSRptItemCodeID = isset($_POST['accbFSRptItemCodeID']) ? (int) cleanInputData($_POST['accbFSRptItemCodeID'])
                                                                                                                                    : -1;
                                                                                                                        $accbFSRptItemCode = isset($_POST['accbFSRptItemCode']) ? cleanInputData($_POST['accbFSRptItemCode'])
                                                                                                                                    : "";
                                                                                                                        $accbFSRptStoreID = isset($_POST['accbFSRptStoreID']) ? (int) cleanInputData($_POST['accbFSRptStoreID'])
                                                                                                                                    : $selectedStoreID;
                                                                                                                        $accbFSRptStore = isset($_POST['accbFSRptStore']) ? cleanInputData($_POST['accbFSRptStore'])
                                                                                                                                    : $acsCntrlGrpNm;
                                                                                                                        $accbFSRptCtgryID = isset($_POST['accbFSRptCtgryID']) ? (int) cleanInputData($_POST['accbFSRptCtgryID'])
                                                                                                                                    : -1;
                                                                                                                        $accbFSRptCtgry = isset($_POST['accbFSRptCtgry']) ? cleanInputData($_POST['accbFSRptCtgry'])
                                                                                                                                    : "";
                                                                                                                        $accbFSRptItemType = isset($_POST['accbFSRptItemType']) ? cleanInputData($_POST['accbFSRptItemType'])
                                                                                                                                    : "";
                                                                                                                        $accbFSRptQTYType = isset($_POST['accbFSRptQTYType']) ? cleanInputData($_POST['accbFSRptQTYType'])
                                                                                                                                    : "";
                                                                                                                        $accbFSRptMinQTY = isset($_POST['accbFSRptMinQTY']) ? (float) cleanInputData($_POST['accbFSRptMinQTY'])
                                                                                                                                    : 1;
                                                                                                                        $accbFSRptMaxQTY = isset($_POST['accbFSRptMaxQTY']) ? (float) cleanInputData($_POST['accbFSRptMaxQTY'])
                                                                                                                                    : 9999999999;
                                                                                                                        $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte'])
                                                                                                                                    : substr($ymdtme3, 0, 11);
                                                                                                                        $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                                                                                                                        $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte'])
                                                                                                                                    : substr($ymdtme2, 0, 11);
                                                                                                                        $accbFSRptDte1 = $accbFSRptDte;
                                                                                                                        $fsrptRunID = -1;
                                                                                                                        if ($startRunng == 1) {
                                                                                                                            $fsrptRunID = getNewFSRptRunID();
                                                                                                                            /* if ($accbFSRptDte != "") {
                                                                                                                              $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                                                                                                                              } */
                                                                                                                            $strSql = "select scm.populate_money_rcvd(" . $fsrptRunID . ",
                                                                                            '" . loc_db_escape_string($accbFSRptRptType) . "',
                                                                                            '" . loc_db_escape_string($accbFSRptDocType) . "',
                                                                                            '" . loc_db_escape_string($accbFSRptSortBy) . "',
                                                                                            " . $accbFSRptCreatedByID . ",
                                                                                            '" . loc_db_escape_string($accbFSRptUseCreationDte) . "',
                                                                                            '" . $accbStrtFSRptDte . "',
                                                                                                '" . $accbFSRptDte . "',
                                                                                                " . $usrID . ",
                                                                                                to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                                                                                                " . $orgID . ",-1);";
                                                                                                                            //echo $strSql;
                                                                                                                            $result = executeSQLNoParams($strSql);
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                        <div class="col-md-3" style="padding:5px 1px 0px 15px;" id="leftDivFSRpt">
                                                                                                                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                                                                                                <legend class="basic_person_lg">
                                                                                                                                    Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                                                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                                                                    </a>
                                                                                                                                </legend>
                                                                                                                                <div class="col-md-12">
                                                                                                                                    <div class="form-group">
                                                                                                                                        <label class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;color:red;font-style: italic;">NB: Not Restricting your search using these parameters can cause your report to run for hours unending!!!</label>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                                                                                    <div class="form-check" style="font-size: 12px !important;">
                                                                                                                                        <label class="form-check-label">
                                                                                                                                            <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptUseCreationDte" name="accbFSRptUseCreationDte" <?php echo $shwSmmryChkd; ?>>
                                                                                                                                            Use Creation Date
                                                                                                                                        </label>
                                                                                                                                    </div>                            
                                                                                                                                </div>
                                                                                                                                <div class="col-md-12">
                                                                                                                                    <div class="form-group">
                                                                                                                                        <label for="accbFSRptDocType" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Document Type:</label>
                                                                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptDocType">
                                                                                                                                                <?php
                                                                                                                                                $valslctdArry = array("", "", "", "",
                                                                                                                                                    "", "");
                                                                                                                                                $srchInsArrys = array("Pro-Forma Invoice",
                                                                                                                                                    "Sales Order", "Sales Invoice", "Internal Item Request",
                                                                                                                                                    "Item Issue-Unbilled", "Sales Return");
                                                                                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                                                                    if ($accbFSRptDocType == $srchInsArrys[$z]) {
                                                                                                                                                        $valslctdArry[$z] = "selected";
                                                                                                                                                    }
                                                                                                                                                    ?>
                                                                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                                                                <?php } ?>
                                                                                                                                            </select>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>				 
                                                                                                                                <div  class="col-md-12">
                                                                                                                                    <div class="form-group">
                                                                                                                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                                                                                            <label style="margin-bottom:0px !important;">From Date:</label>
                                                                                                                                        </div>
                                                                                                                                        <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                                                                                            <input class="form-control" size="16" type="text" id="accbStrtFSRptDte" name="accbStrtFSRptDte" value="<?php echo $accbStrtFSRptDte1; ?>" placeholder="From Date">
                                                                                                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div  class="col-md-12">
                                                                                                                                    <div class="form-group">
                                                                                                                                        <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                                                                                                                            <label style="margin-bottom:0px !important;">To Date:</label>
                                                                                                                                        </div>
                                                                                                                                        <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                                                                                            <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                                                                                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-12">
                                                                                                                                    <div class="form-group">
                                                                                                                                        <label for="accbFSRptCreatedBy" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Created By:</label>
                                                                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                                                                            <div class="input-group">
                                                                                                                                                <input type="text" class="form-control" aria-label="..." id="accbFSRptCreatedBy" name="accbFSRptCreatedBy" value="<?php echo $accbFSRptCreatedBy; ?>" style="width:100%;" readonly="true">
                                                                                                                                                <input type="hidden" class="form-control" aria-label="..." id="accbFSRptCreatedByID" name="accbFSRptCreatedByID" value="<?php echo $accbFSRptCreatedByID; ?>">
                                                                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Users', '', '', '', 'radio', true, '', 'accbFSRptCreatedByID', 'accbFSRptCreatedBy', 'clear', 1, '', function () {});">
                                                                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                                                                </label>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-12">
                                                                                                                                    <div class="form-group">
                                                                                                                                        <label for="accbFSRptSortBy" class="control-label col-md-4" style="padding:5px 1px 0px 1px !important;">Sort By:</label>
                                                                                                                                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important;">
                                                                                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbFSRptSortBy">
                                                                                                                                                <?php
                                                                                                                                                $valslctdArry = array("", "");
                                                                                                                                                $srchInsArrys = array("QTY", "TOTAL AMOUNT");
                                                                                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                                                                    if ($accbFSRptSortBy == $srchInsArrys[$z]) {
                                                                                                                                                        $valslctdArry[$z] = "selected";
                                                                                                                                                    }
                                                                                                                                                    ?>
                                                                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                                                                <?php } ?>
                                                                                                                                            </select>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">           
                                                                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=12&typ=1&pg=9&vtyp=80');">
                                                                                                                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                                                        Generate Report
                                                                                                                                    </button>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">           
                                                                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=12&typ=1&pg=9&vtyp=80');">
                                                                                                                                        <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                                                    </button>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">           
                                                                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                                                                                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                                                    </button>
                                                                                                                                </div>
                                                                                                                            </fieldset>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                                                                                                                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                                                                                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbFSRptTblSctn"> 
                                                                                                                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                                                                                                        <div id="accbRcnclGlStatemtLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                                                                                                            <div class="row"> 
                                                                                                                                                <div class="col-md-12">
                                                                                                                                                    <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                                                                                                                        <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                                                                                                                            <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                                                                                                                <span aria-hidden="true">&raquo;</span>
                                                                                                                                                            </a><?php echo strtoupper($accbFSRptRptType); ?> FROM <?php echo strtoupper($accbStrtFSRptDte1); ?> TO <?php echo strtoupper($accbFSRptDte1); ?>
                                                                                                                                                        </caption>
                                                                                                                                                        <thead>
                                                                                                                                                            <tr>
                                                                                                                                                                <th style="max-width:40px;width:40px;">No.</th> 
                                                                                                                                                                <th style="max-width:200px !important;width:200px !important;">Item Code/Desc.</th>
                                                                                                                                                                <th style="min-width:250px !important;">Document Numbers</th>
                                                                                                                                                                <th style="text-align: right;min-width:70px;">QTY</th>
                                                                                                                                                                <th style="max-width:50px;width:50px;">UOM</th> 
                                                                                                                                                                <th style="text-align: right;min-width:70px;">Unit Price</th>
                                                                                                                                                                <th style="text-align: right;min-width:70px;">Total Amount</th>
                                                                                                                                                                <th style="max-width:50px;width:50px;">CUR.</th> 
                                                                                                                                                            </tr>
                                                                                                                                                        </thead>
                                                                                                                                                        <tbody>   
                                                                                                                                                            <?php
                                                                                                                                                            $cntr = 0;
                                                                                                                                                            $maxNoRows = 0;
                                                                                                                                                            $resultRw = null;
                                                                                                                                                            if ($fsrptRunID > 0) {
                                                                                                                                                                $resultRw = get_ItemsSold($fsrptRunID,
                                                                                                                                                                        $accbStrtFSRptDte, $accbFSRptDte);
                                                                                                                                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                                                                                                                            }
                                                                                                                                                            $ttlTrsctnDbtAmnt = 0;
                                                                                                                                                            $ttlTrsctnCrdtAmnt = 0;
                                                                                                                                                            $trsctnbals_crny = "";
                                                                                                                                                            while ($cntr < $maxNoRows) {
                                                                                                                                                                $rowNumber = 0;
                                                                                                                                                                $trsctnItmNm = "";
                                                                                                                                                                $trsctnUomNm = "";
                                                                                                                                                                $trsctnCrncyNm = "";
                                                                                                                                                                $trsctnDocNum = "";
                                                                                                                                                                $trsctnstock_tot_qty = 0;
                                                                                                                                                                $trsctnInvc_amnt = 0;
                                                                                                                                                                $trsctnDscnt_amnt = 0;
                                                                                                                                                                $numStyle1 = "text-align:right;";
                                                                                                                                                                $nameStyle1 = "";
                                                                                                                                                                if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                                                                                                    $rowNumber = (float) $rowRw[0];
                                                                                                                                                                    $trsctnItmNm = str_replace(" ",
                                                                                                                                                                            "&nbsp;", $rowRw[1]);
                                                                                                                                                                    $trsctnUomNm = $rowRw[3];
                                                                                                                                                                    $trsctnCrncyNm = $rowRw[9];
                                                                                                                                                                    $trsctnDocNum = str_replace(" ",
                                                                                                                                                                            "&nbsp;", $rowRw[2]);
                                                                                                                                                                    $trsctnstock_tot_qty = (float) $rowRw[4];
                                                                                                                                                                    $trsctnInvc_amnt = (float) $rowRw[5];
                                                                                                                                                                    $trsctnDscnt_amnt = (float) $rowRw[6];

                                                                                                                                                                    $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt
                                                                                                                                                                            + $trsctnInvc_amnt;
                                                                                                                                                                    $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt
                                                                                                                                                                            + $trsctnDscnt_amnt;
                                                                                                                                                                    $isParent = "0";
                                                                                                                                                                    $hsSbldgr = "0";
                                                                                                                                                                    if ($isParent == "1" || $hsSbldgr == "1") {
                                                                                                                                                                        $numStyle1 = "text-align:right;font-weight:bold;";
                                                                                                                                                                        $nameStyle1 = "font-weight:bold;";
                                                                                                                                                                    }
                                                                                                                                                                }
                                                                                                                                                                $cntr += 1;
                                                                                                                                                                ?>
                                                                                                                                                                <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                                                                                                                                                    <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>    
                                                                                                                                                                    <td class="lovtd" style="text-align:left;max-width:200px !important;width:200px !important; word-break: break-all;"> 
                                                                                                                                                                        <span><?php echo $trsctnItmNm; ?></span>             
                                                                                                                                                                    </td>    
                                                                                                                                                                    <td class="lovtd" style="<?php echo $nameStyle1; ?>">
                                                                                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemID" value="-1" style="width:100% !important;">  
                                                                                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="0" style="width:100% !important;">  
                                                                                                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_ItemCode" value="" style="width:100% !important;"> 
                                                                                                                                                                        <span><?php echo $trsctnDocNum; ?></span>             
                                                                                                                                                                    </td>
                                                                                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                                                                                        <span><?php echo number_format($trsctnstock_tot_qty); ?></span>
                                                                                                                                                                    </td>
                                                                                                                                                                    <td class="lovtd" style="text-align:left;font-weight:bold;">
                                                                                                                                                                        <span><?php echo $trsctnUomNm; ?></span>
                                                                                                                                                                    </td>
                                                                                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                                                                                        <span><?php echo number_format($trsctnInvc_amnt, 2); ?></span>
                                                                                                                                                                    </td>
                                                                                                                                                                    <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                                                                                                        <span><?php echo number_format($trsctnDscnt_amnt, 2); ?></span>
                                                                                                                                                                    </td>
                                                                                                                                                                    <td class="lovtd" style="text-align:left;font-weight:bold;">
                                                                                                                                                                        <span><?php echo $trsctnCrncyNm; ?></span>
                                                                                                                                                                    </td>
                                                                                                                                                                </tr>
                                                                                                                                                                <?php
                                                                                                                                                            }
                                                                                                                                                            ?>
                                                                                                                                                        </tbody>
                                                                                                                                                        <tfoot>
                                                                                                                                                            <tr>
                                                                                                                                                                <th style="max-width:40px;width:40px;">&nbsp;</th>
                                                                                                                                                                <th style="">TOTALS:</th>
                                                                                                                                                                <th style="text-align: right;">&nbsp;</th>
                                                                                                                                                                <th style="text-align: right;">&nbsp;</th>
                                                                                                                                                                <th style="text-align: right;">&nbsp;</th>
                                                                                                                                                                <th style="text-align: right;">&nbsp;</th>
                                                                                                                                                                <th class="lovtd" style="text-align:right;font-weight:bold;color:red;">
                                                                                                                                                                    <span><?php
                                                                                                                                                                        echo number_format($ttlTrsctnCrdtAmnt,
                                                                                                                                                                                2);
                                                                                                                                                                        ?></span>
                                                                                                                                                                </th>
                                                                                                                                                                <th style="text-align: right;">&nbsp;</th>
                                                                                                                                                            </tr>
                                                                                                                                                        </tfoot>
                                                                                                                                                    </table>
                                                                                                                                                </div>
                                                                                                                                                </fieldset>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        </form>
                                                                                                                                        <?php
                                                                                                                                    }
                                                                                                                                }
                                                                                                                            }
                                                                                                                        }
                                                                                                                        ?>