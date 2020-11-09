<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Core Banking Menu</span>
                                        </li>";
    if (strpos($subPgNo, "3.1") !== FALSE) {
        $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&q=SUBMENUS&pg=3&subPgNo=3.1.1');\">
                            <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                            <span style=\"text-decoration:none;\">Teller Operations</span>
                    </li>";
    }
    $usrID = $_SESSION['USRID'];
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $qStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 00:00:00";
    $qEndDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 23:59:59";
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
    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $prsnid;
    $canVwRcHstry = test_prmssns("View Record History", $mdlNm);

    if ($pkID > 0) {
        $prsnid = $_SESSION['PRSN_ID'];
        if ($subPgNo == "3.1.1") {
            //TELLER OPERATIONS
            echo $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.1');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Withdrawal Transactions</span>
                                 </li></div>";
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
            $trnsType = "WITHDRAWAL";

            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canAddTrns = test_prmssns($dfltPrvldgs[93], $mdlNm);
                $total = get_CustAcctTransactionsTtl($srchFor, $srchIn, $orgID, $searchAll, $trnsType, "", $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_CustAcctTransactions($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $trnsType, "", $qStrtDte, $qEndDte);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-2";
                ?> 
                <form id='mcfAcntTrnsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">WITHDRAWAL TRANSACTIONS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            if ($canAddTrns === true) {
                                ?>   
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'WITHDRAWAL TRANSACTION', 14, '<?php echo $subPgNo; ?>', 0, 'ADD', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Withdrawal
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-3";
                            }
                            ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="mcfAcntTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.1', 'mcfAcntTrns')">
                                    <input id="mcfAcntTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.1', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.1', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "");
                                        $srchInsArrys = array("All", "Account Number", "Account Title", "Transaction Date", "Transaction Type", "Status", "Narration", "Contact Information", "Transaction Number");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="mcfAcntTrnsStrtDate" name="mcfAcntTrnsStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="mcfAcntTrnsEndDate" name="mcfAcntTrnsEndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>                            
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.1','mcfAcntTrns');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.1','mcfAcntTrns');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                        
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allMcfTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canAddTrns === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <th>...</th>
                                            <th>Trans. Type - No.</th>		
                                            <th style="min-width:130px;width:130px;">Account Number</th>
                                            <th style="min-width:130px;width:130px;">Account Title</th>
                                            <th style="min-width:130px;width:130px;">Trans. Date</th>
                                            <th>CUR.</th>
                                            <th style="text-align:right;">Amount</th>
                                            <th>Status</th>
                                            <th>...</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            /**/
                                            $cntr += 1;
                                            ?>
                                            <tr id="allMcfTrnsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canAddTrns === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Transaction" 
                                                                onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'WITHDRAWAL TRANSACTION', 14, '<?php echo $subPgNo; ?>', 0, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delMCFTrnsHdr('allMcfTrnsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                    <input type="hidden" id="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd"><?php
                                                    echo trim(str_replace(" - - ", " ", $row[5] . " - " . $row[12] . " - " . $row[13] . " - " . $row[14]), ' ');
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[11]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $row[10]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[6], 2);
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[7]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'WITHDRAWAL TRANSACTION', 14, '<?php echo $subPgNo; ?>', 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_cust_account_transactions|acct_trns_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
            }
        } else if ($subPgNo == "3.1.2") {//DEPOSIT TRANSACTIONS
            echo $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Deposit Transactions</span>
                                 </li></div>";
            $error = "";
            $searchAll = true;
            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
            //$trnsType = isset($_POST['trnsType']) ? cleanInputData($_POST['trnsType']) : "DEPOSIT";
            $trnsType = "DEPOSIT";
            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }
            if ($vwtyp == 0) {
                $canAddTrns = test_prmssns($dfltPrvldgs[97], $mdlNm);
                /* echo $cntent . "<li>
                  <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                  <span style=\"text-decoration:none;\">Data Administration</span>
                  </li>
                  </ul>
                  </div>"; */
                $total = get_CustAcctTransactionsTtl($srchFor, $srchIn, $orgID, $searchAll, $trnsType, "", $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_CustAcctTransactions($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $trnsType, "", $qStrtDte, $qEndDte);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-2";
                ?> 
                <form id='mcfAcntTrnsForm' action='' method='post' accept-charset='UTF-8'>
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">DEPOSIT TRANSACTIONS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            if ($canAddTrns === true) {
                                ?>   
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Savings Product', 14, '<?php echo $subPgNo; ?>', 0, 'ADD', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Deposit
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-3";
                            }
                            ?>                        
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="mcfAcntTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                    <input id="mcfAcntTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "");
                                        $srchInsArrys = array("All", "Account Number", "Account Title", "Transaction Date", "Transaction Type", "Status", "Narration", "Contact Information", "Transaction Number");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="mcfAcntTrnsStrtDate" name="mcfAcntTrnsStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="mcfAcntTrnsEndDate" name="mcfAcntTrnsEndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>                            
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                  
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allMcfTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canAddTrns === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <th>...</th>
                                            <th>Trans. Type - No.</th>		
                                            <th style="min-width:130px;width:130px;">Account Number</th>
                                            <th style="min-width:130px;width:130px;">Account Title</th>
                                            <th style="min-width:130px;width:130px;">Trans. Date</th>
                                            <th>CUR.</th>
                                            <th style="text-align:right;">Amount</th>
                                            <th>Status</th>
                                            <th>...</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            /**/
                                            $cntr += 1;
                                            ?>
                                            <tr id="allMcfTrnsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canAddTrns === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Transaction" 
                                                                onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'DEPOSIT TRANSACTION', 14, '<?php echo $subPgNo; ?>', 0, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delMCFTrnsHdr('allMcfTrnsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                    <input type="hidden" id="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd"><?php
                                                    echo trim(str_replace(" - - ", " ", $row[5] . " - " . $row[12] . " - " . $row[13] . " - " . $row[14]), ' ');
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[11]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $row[10]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[6], 2);
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[7]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'DEPOSIT TRANSACTION', 14, '<?php echo $subPgNo; ?>', 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_cust_account_transactions|acct_trns_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
            }
        } else if ($subPgNo == "3.1.3") {
            //FOREX SALES
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.3');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Forex Sales</span>
                                        </li></ul></div>";
        } else if ($subPgNo == "3.1.4") {
            //FOREX PURCHASES
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.4');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Forex Purchases</span>
                                        </li></ul></div>";
        } else if ($subPgNo == "3.1.5") {
            //ALL ACCOUNT TRANSACTIONS
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.5');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Loan Repayments</span>
                                        </li></ul></div>";
            echo "Loan Repayments";
        } else if ($subPgNo == "3.1.6") {
            //MONEY TRANSFERS
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.6');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Money Transfers</span>
                                        </li></ul></div>";

            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
            $trnsType = "MONEY TRANSFER";

            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canAddTrns = test_prmssns($dfltPrvldgs[113], $mdlNm);
                $total = get_CustAcctTransactionsTtl($srchFor, $srchIn, $orgID, $searchAll, $trnsType, "", $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_CustAcctTransactions($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $trnsType, "", $qStrtDte, $qEndDte);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-2";
                ?> 
                <form id='mcfAcntTrnsForm' action='' method='post' accept-charset='UTF-8'>
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">MONEY TRANSFER TRANSACTIONS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            if ($canAddTrns === true) {
                                ?>   
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Savings Product', 14, '<?php echo $subPgNo; ?>', 0, 'ADD', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Money Transfer
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-3";
                            }
                            ?>                        
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="mcfAcntTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                    <input id="mcfAcntTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "");
                                        $srchInsArrys = array("All", "Account Number", "Account Title", "Transaction Date", "Transaction Type", "Status", "Narration", "Contact Information", "Transaction Number");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="mcfAcntTrnsStrtDate" name="mcfAcntTrnsStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="mcfAcntTrnsEndDate" name="mcfAcntTrnsEndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>                            
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                  
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allMcfTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canAddTrns === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <th>...</th>
                                            <th>Trans. Type - No.</th>		
                                            <th style="min-width:130px;width:130px;">Account Number</th>
                                            <th style="min-width:130px;width:130px;">Account Title</th>
                                            <th style="min-width:130px;width:130px;">Trans. Date</th>
                                            <th>CUR.</th>
                                            <th style="text-align:right;">Amount</th>
                                            <th>Status</th>
                                            <th>...</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            /**/
                                            $cntr += 1;
                                            ?>
                                            <tr id="allMcfTrnsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canAddTrns === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Transaction" 
                                                                onclick="" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delMCFTrnsHdr('allMcfTrnsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                    <input type="hidden" id="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd"><?php
                                                    echo trim(str_replace(" - - ", " ", $row[5] . " - " . $row[12] . " - " . $row[13] . " - " . $row[14]), ' ');
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[11]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $row[10]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[6], 2);
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[7]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'DEPOSIT TRANSACTION', 14, '<?php echo $subPgNo; ?>', 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_cust_account_transactions|acct_trns_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
            }
        } else if ($subPgNo == "3.1.7") {
            //UTILITY PAYMENTS
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.7');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Utility Payments</span>
                                        </li></ul></div>";
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
            $trnsType = "UTILITY PAYMENTS";

            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canAddTrns = test_prmssns($dfltPrvldgs[117], $mdlNm);
                $total = get_CustAcctTransactionsTtl($srchFor, $srchIn, $orgID, $searchAll, $trnsType, "", $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_CustAcctTransactions($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $trnsType, "", $qStrtDte, $qEndDte);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-2";
                ?> 
                <form id='mcfAcntTrnsForm' action='' method='post' accept-charset='UTF-8'>
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">UTILITY PAYMENT TRANSACTIONS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            if ($canAddTrns === true) {
                                ?>   
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Savings Product', 14, '<?php echo $subPgNo; ?>', 0, 'ADD', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Utility Payment
                                        <!--Form must have 
                                        1.Utility Payment Type (E.g. ECG, DSTV etc)
                                        2.Teller's Action is always Receipts
                                        3.Accept only Cash in all Currencies with Exchg Rate
                                        -->
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-3";
                            }
                            ?>                        
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="mcfAcntTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                    <input id="mcfAcntTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "");
                                        $srchInsArrys = array("All", "Account Number", "Account Title", "Transaction Date", "Transaction Type", "Status", "Narration", "Contact Information", "Transaction Number");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="mcfAcntTrnsStrtDate" name="mcfAcntTrnsStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="mcfAcntTrnsEndDate" name="mcfAcntTrnsEndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>                            
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                  
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allMcfTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canAddTrns === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <th>...</th>
                                            <th>Trans. Type - No.</th>		
                                            <th style="min-width:130px;width:130px;">Account Number</th>
                                            <th style="min-width:130px;width:130px;">Account Title</th>
                                            <th style="min-width:130px;width:130px;">Trans. Date</th>
                                            <th>CUR.</th>
                                            <th style="text-align:right;">Amount</th>
                                            <th>Status</th>
                                            <th>...</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            /**/
                                            $cntr += 1;
                                            ?>
                                            <tr id="allMcfTrnsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canAddTrns === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Transaction" 
                                                                onclick="" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delMCFTrnsHdr('allMcfTrnsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                    <input type="hidden" id="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd"><?php
                                                    echo trim(str_replace(" - - ", " ", $row[5] . " - " . $row[12] . " - " . $row[13] . " - " . $row[14]), ' ');
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[11]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $row[10]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[6], 2);
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[7]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'DEPOSIT TRANSACTION', 14, '<?php echo $subPgNo; ?>', 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_cust_account_transactions|acct_trns_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
            }
        } else if ($subPgNo == "3.1.8") {
            //BANKER'S DRAFT
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.8');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Banker's Draft</span>
                                        </li></ul></div>";
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
            $trnsType = "BANKERS DRAFT";

            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canAddTrns = test_prmssns($dfltPrvldgs[121], $mdlNm);
                $total = get_CustAcctTransactionsTtl($srchFor, $srchIn, $orgID, $searchAll, $trnsType, "", $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_CustAcctTransactions($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $trnsType, "", $qStrtDte, $qEndDte);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-3";
                ?> 
                <form id='mcfAcntTrnsForm' action='' method='post' accept-charset='UTF-8'>
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">BANKER'S DRAFT TRANSACTIONS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType3 = "col-lg-4";
                            if ($canAddTrns === true) {
                                ?>   
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Savings Product', 14, '3.1.8', 0, 'ADD', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Take Deposit 
                                    </button>                   
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Savings Product', 14, '3.1.8', 1, 'ADD', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Issue Banker's Draft
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-5";
                            }
                            ?>                        
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="mcfAcntTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                    <input id="mcfAcntTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsDsplySze" style="min-width:50px !important;">                            
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
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="mcfAcntTrnsStrtDate" name="mcfAcntTrnsStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="mcfAcntTrnsEndDate" name="mcfAcntTrnsEndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>                            
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                  
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allMcfTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canAddTrns === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <th>...</th>
                                            <th>Trans. Type - No.</th>		
                                            <th style="min-width:130px;width:130px;">Account Number</th>
                                            <th style="min-width:130px;width:130px;">Account Title</th>
                                            <th style="min-width:130px;width:130px;">Trans. Date</th>
                                            <th>CUR.</th>
                                            <th style="text-align:right;">Amount</th>
                                            <th>Status</th>
                                            <th>...</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            /**/
                                            $cntr += 1;
                                            ?>
                                            <tr id="allMcfTrnsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canAddTrns === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Transaction" 
                                                                onclick="" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delMCFTrnsHdr('allMcfTrnsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                    <input type="hidden" id="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd"><?php
                                                    echo trim(str_replace(" - - ", " ", $row[5] . " - " . $row[12] . " - " . $row[13] . " - " . $row[14]), ' ');
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[11]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $row[10]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[6], 2);
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[7]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'DEPOSIT TRANSACTION', 14, '<?php echo $subPgNo; ?>', 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_cust_account_transactions|acct_trns_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
            }
        } else if ($subPgNo == "3.1.9") {
            //ALL ACCOUNT TRANSACTIONS
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.9');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">All Account Transactions</span>
                                        </li></ul></div>";
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
            $trnsType = "ALL";

            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canAddTrns = test_prmssns($dfltPrvldgs[93], $mdlNm);
                $searchAll = test_prmssns($dfltPrvldgs[212], $mdlNm);
                $total = get_CustAcctTransactionsTtl($srchFor, $srchIn, $orgID, $searchAll, $trnsType, "", $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_CustAcctTransactions($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $trnsType, "", $qStrtDte, $qEndDte);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                //$colClassType2 = "col-lg-8";
                ?> 
                <form id='mcfAcntTrnsForm' action='' method='post' accept-charset='UTF-8'>
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">ALL CUSTOMER ACCOUNT TRANSACTIONS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            ?>                        
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="mcfAcntTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                    <input id="mcfAcntTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "");
                                        $srchInsArrys = array("All", "Account Number", "Account Title", "Transaction Date", "Transaction Type", "Status", "Narration", "Contact Information", "Transaction Number");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="col-lg-4" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="mcfAcntTrnsStrtDate" name="mcfAcntTrnsStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="mcfAcntTrnsEndDate" name="mcfAcntTrnsEndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>                            
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                  
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allMcfTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canAddTrns === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <th>...</th>
                                            <th>Trans. Type - No.</th>		
                                            <th style="min-width:130px;width:130px;">Account Number</th>
                                            <th style="min-width:130px;width:130px;">Account Title</th>
                                            <th style="min-width:130px;width:130px;">Trans. Date</th>
                                            <th>CUR.</th>
                                            <th style="text-align:right;">Amount</th>
                                            <th>Status</th>
                                            <th>...</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            /**/
                                            $cntr += 1;
                                            $subPgNo1 = "3.1.1";
                                            if ($row[5] == "DEPOSIT") {
                                                $subPgNo1 = "3.1.2";
                                            } else {
                                                
                                            }
                                            ?>
                                            <tr id="allMcfTrnsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canAddTrns === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Transaction" 
                                                                onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', '<?php echo $row[5]; ?>', 14, '<?php echo $subPgNo1; ?>', 0, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delMCFTrnsHdr('allMcfTrnsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                    <input type="hidden" id="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd"><?php
                                                    echo trim(str_replace(" - - ", " ", $row[5] . " - " . $row[12] . " - " . $row[13] . " - " . $row[14]), ' ');
                                                    ?>
                                                </td>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[11]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $row[10]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[6], 2);
                                                    ?>
                                                </td>
                                                <td class="lovtd"><?php echo $row[7]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'DEPOSIT TRANSACTION', 14, '<?php echo $subPgNo1; ?>', 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_cust_account_transactions|acct_trns_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
            }
        } else if ($subPgNo == "3.1.11") {
            //BULK CUSTOMER TRANSACTIONS
            echo $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&vtyp=0&subPgNo=$subPgNo');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Bulk/Batch Transactions</span>
                             </li>
                          </div>";
            $error = "";
            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = "All";
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }
            if ($vwtyp == 0) {
                $canAddTrns = test_prmssns($dfltPrvldgs[293], $mdlNm);
                $canEdtTrns = test_prmssns($dfltPrvldgs[294], $mdlNm);
                $canDelTrns = test_prmssns($dfltPrvldgs[295], $mdlNm);
                $total = get_BulkTransactionsTtl($srchFor, $srchIn, $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_BulkTransactions($srchFor, $srchIn, $curIdx, $lmtSze, $qStrtDte, $qEndDte);
                $cntr = 0;
                ?> 
                <form id='mcfAcntTrnsForm' action='' method='post' accept-charset='UTF-8'>
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">BULK/BATCH TRANSACTIONS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType1 = "col-md-2";
                            $colClassType2 = "col-md-3";
                            $reportTitle1 = "Process Batch Transaction";
                            $reportName1 = "Process Batch Transaction";
                            $rptID1 = getRptID($reportName1);
                            $prmID11 = getParamIDUseSQLRep("{:batch_id}", $rptID1);
                            //$prmID31 = getParamIDUseSQLRep("{:p_msg_rtng_id}", $rptID1);
                            $prmID21 = getParamIDUseSQLRep("{:documentTitle}", $rptID1);
                            $trnsID1 = -1;
                            $paramRepsNVals1 = $prmID11 . "~" . $trnsID1 . "|" . $prmID21 . "~" . $reportTitle1 . "|-130~" . $reportTitle1 . "|-190~PDF";
                            $paramStr1 = urlencode($paramRepsNVals1);
                            if ($canAddTrns === true) {
                                ?>   
                                <div class="col-md-4" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'gnrlVmsOrgID', '', '', 'radio', true, '', 'mcfAcntTrnsPrsnID', '', 'clear', 1, '', function () {
                                                                    var sbmtdRltnPrsnID = typeof $('#mcfAcntTrnsPrsnID').val() === 'undefined' ? '' : $('#mcfAcntTrnsPrsnID').val();
                                                                    getOneBulkTrnsForm(-1, 0, 'ShowDialog', sbmtdRltnPrsnID);
                                                                });" data-toggle="tooltip" data-placement="bottom" title = "New Bulk/Batch Transaction">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New
                                    </button>                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID1; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Process All Authorized Batches">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Process Batches
                                    </button>                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneBulkTrnsForm(-1, 0, 'ShowDialog', -1, 1);" data-toggle="tooltip" data-placement="bottom" title = "New Cash-less Batch">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Cashless
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-5";
                            }
                            ?>                      
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="mcfAcntTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns');">
                                    <input id="mcfAcntTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <input id="mcfAcntTrnsPrsnID" type = "hidden" value="-1">                                    
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsDsplySze" style="min-width:50px !important;">                            
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
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="mcfAcntTrnsStrtDate" name="mcfAcntTrnsStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="mcfAcntTrnsEndDate" name="mcfAcntTrnsEndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>                            
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                  
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allMcfTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canEdtTrns === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <th>...</th>
                                            <th style="min-width:130px;width:130px;">Batch No.</th>		
                                            <th style="min-width:130px;width:130px;">Relations Manager</th>
                                            <th style="min-width:130px;width:130px;">Trans. Date</th>
                                            <th>CUR.</th>
                                            <th style="text-align:right;">Amount</th>
                                            <th>Narration</th>
                                            <th>Status</th>
                                            <th>...</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            /**/
                                            $cntr += 1;
                                            ?>
                                            <tr id="allMcfTrnsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canEdtTrns === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Transaction" 
                                                                onclick="getOneBulkTrnsForm(<?php echo $row[0]; ?>, 0, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <?php if ($canDelTrns === true) { ?>
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delBatchTrnsHdr('allMcfTrnsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <input type="hidden" id="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                    <?php } ?>
                                                </td>                                                
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[7]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $row[21]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[8], 2);
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[5]; ?></td>
                                                <td class="lovtd"><?php echo $row[9]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction" onclick="getOneBulkTrnsForm(<?php echo $row[0]; ?>, 0, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_bulk_trns_hdr|bulk_trns_hdr_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
            }
        } else if ($subPgNo == "3.2") {
            //Uncleared Transactions
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
            $vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : 0;
            $prsnid = $_SESSION['PRSN_ID'];
            $orgID = $_SESSION['ORG_ID'];
            $qNotCleared = false;
            $qStrtDte = "01-Jan-1900 00:00:00";
            $qEndDte = "31-Dec-4000 23:59:59";
            $qLowVal = 0;
            $qHighVal = 0;
            if (isset($_POST['qNotCleared'])) {
                $qNotCleared = cleanInputData($_POST['qNotCleared']) === "true" ? true : false;
            }
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
            echo $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.2');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Uncleared Transactions</span>
                            </li></ul></div>";
            $canClearTrns = test_prmssns($dfltPrvldgs[264], $mdlNm);
            $total = get_UnclearedTrnsTtl($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte, $qNotCleared, $qLowVal, $qHighVal);
            if ($pageNo > ceil($total / $lmtSze)) {
                $pageNo = 1;
            } else if ($pageNo < 1) {
                $pageNo = ceil($total / $lmtSze);
            }
            $curIdx = $pageNo - 1;
            $result = get_UnclearedTrns($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte, $qNotCleared, $qLowVal, $qHighVal);
            $cntr = 0;
            ?> 
            <form id='allUnclrdTrnsForm' action='' method='post' accept-charset='UTF-8'>
                <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                    <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                        <div class="input-group">
                            <input class="form-control" id="allUnclrdTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                            echo trim(str_replace("%", " ", $srchFor));
                            ?>" onkeyup="enterKeyFuncAllUnclrdTrns(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                            <input id="allUnclrdTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllUnclrdTrns('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllUnclrdTrns('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');">
                                <span class="glyphicon glyphicon-search"></span>
                            </label> 
                        </div>
                    </div>
                    <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                        <div class="input-group">
                            <span class="input-group-addon">In</span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="allUnclrdTrnsSrchIn">
                                <?php
                                $valslctdArry = array("", "", "", "");
                                $srchInsArrys = array("All Fields", "Cheque Number", "Bank/Branch", "Beneficiary");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                            </select>
                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="allUnclrdTrnsDsplySze" style="min-width:65px !important;">                            
                                <?php
                                $valslctdArry = array("", "", "", "", "", "",
                                    "", "");
                                $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100,
                                    500, 1000);
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
                    <div class="col-lg-4" style="padding:0px 1px 0px 1px !important;">
                        <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="allUnclrdTrnsStrtDate" name="allUnclrdTrnsStrtDate" value="<?php
                                echo substr($qStrtDte, 0, 11);
                                ?>" placeholder="Start Date">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div></div>
                        <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text"  id="allUnclrdTrnsEndDate" name="allUnclrdTrnsEndDate" value="<?php
                                echo substr($qEndDte, 0, 11);
                                ?>" placeholder="End Date">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>                            
                    </div>
                    <div class="col-lg-2" style="padding:0px 1px 0px 1px !important;">
                        <nav aria-label="Page navigation">
                            <ul class="pagination" style="margin: 0px !important;">
                                <li>
                                    <a class="rhopagination" href="javascript:getAllUnclrdTrns('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getAllUnclrdTrns('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                    <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <label class="btn btn-primary btn-file input-group-addon">
                                    <span class="glyphicon glyphicon-sort-by-order"></span>Amount Range From:
                                </label>
                                <input class="form-control" id="allUnclrdTrnsLowVal" type = "number" placeholder="Low Value" value="<?php
                                echo $qLowVal;
                                ?>" onkeyup="enterKeyFuncAllUnclrdTrns(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                            </div>
                        </div>   
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <label class="btn btn-primary btn-file input-group-addon">
                                    <span class="glyphicon glyphicon-sort-by-order-alt"></span> To:
                                </label>
                                <input class="form-control" id="allUnclrdTrnsHighVal" type = "number" placeholder="High Value" value="<?php
                                echo $qHighVal;
                                ?>" onkeyup="enterKeyFuncAllUnclrdTrns(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                            </div>
                        </div>
                        <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                            <div class="form-check" style="font-size: 12px !important;">
                                <label class="form-check-label">
                                    <?php
                                    $notClrdChekd = "";
                                    if ($qNotCleared == true) {
                                        $notClrdChekd = "checked=\"true\"";
                                    }
                                    ?>
                                    <input type="checkbox" class="form-check-input" onclick="getAllUnclrdTrns('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" id="allUnclrdTrnsNtClrd" name="allUnclrdTrnsNtClrd" <?php echo $notClrdChekd; ?>>
                                    Yet to Clear
                                </label>
                            </div>                            
                        </div>  
                        <div class="col-md-5" style="padding:2px 1px 2px 1px !important;">
                            <input type="hidden" name="trnsGlAcId" id="trnsGlAcId" value="-1">
                            <?php if ($canClearTrns) { ?>
                                <!--<button type="button" class="btn btn-default btn-sm" onclick="" data-toggle="tooltip" title="Auto-Clear All Transactions Due">
                                    <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Auto-Clear All Transactions Due
                                </button>
                                <button type="button" class="btn btn-default btn-sm" onclick="" data-toggle="tooltip" title="New Uncleared Transaction">
                                    <img src="cmn_images/plus_32.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Uncleared Transaction
                                </button>-->
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form>
            <form id='allUnclrdTrnsHdrsForm' action='' method='post' accept-charset='UTF-8'>
                <div class="row"> 
                    <div  class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="allUnclrdTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>No.</th>
                                    <th>Type</th>
                                    <th class="extnl">Bank/Branch (In-House Beneficiary)</th>
                                    <th>CHQ. No.</th>
                                    <th>CHQ. Date</th>
                                    <th>CUR.</th>
                                    <th>CHQ. Amount</th>
                                    <th>Rate</th>
                                    <th>Value Date</th>
                                    <th style="text-align:center;">Status</th>
                                    <th>Date Cleared</th>
                                    <th>&nbsp;</th>
                                    <?php if ($canVwRcHstry === true) { ?>
                                        <th>...</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = loc_db_fetch_array($result)) {
                                    /**/
                                    $cntr += 1;
                                    ?>
                                    <tr id="allUnclrdTrnsHdrsRow_<?php echo $cntr; ?>">  
                                        <td class="lovtd" style="text-align:center;">
                                            <input type="checkbox" name="allUnclrdTrnsHdrsRow<?php echo $cntr; ?>_CheckBox" value="<?php echo $row[0] . ";" . $row[1]; ?>">
                                            <input type="hidden" value="<?php echo $row[1]; ?>" id="allUnclrdTrnsHdrsRow<?php echo $cntr; ?>_AccntTrnsID">
                                            <input type="hidden" value="<?php echo $row[0]; ?>" id="allUnclrdTrnsHdrsRow<?php echo $cntr; ?>_TrnsChqID">
                                        </td>                            
                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                        <td class="lovtd"><?php echo $row[9]; ?></td>
                                        <td class="lovtd"><?php echo str_replace("-  (", "(", $row[3] . " - " . $row[5] . " (" . $row[22] . ")"); ?></td>
                                        <td class="lovtd"><?php echo $row[6]; ?></td>
                                        <td class="lovtd"><?php echo $row[10]; ?></td>
                                        <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo $row[14]; ?></td>
                                        <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php echo number_format((float) $row[7], 2); ?></td>
                                        <td class="lovtd"><?php echo number_format((float) $row[15], 5); ?></td>
                                        <td class="lovtd"><?php echo $row[8]; ?></td>
                                        <td class="lovtd" style="text-align:center;">
                                            <?php
                                            $notClrdChekd = "";
                                            if ($row[19] == "1") {
                                                $notClrdChekd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" name="allUnclrdTrnsHdrsRow<?php echo $cntr; ?>_Status" value="<?php echo $row[19]; ?>" <?php echo $notClrdChekd; ?> disabled="true">
                                        </td>       
                                        <td class="lovtd"><?php echo $row[20]; ?></td>
                                        <td class="lovtd">
                                            <?php if ($canClearTrns && $row[19] != "1") { ?>
                                                <button type="button" class="btn btn-default btn-sm" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Cheque Clearing Accounts', '', '', '', 'radio', true, '', 'trnsGlAcId', '', 'clear', 1, '', function () {
                                                                                clrUnclrMCFTrnsRqst(<?php echo $row[0]; ?>, 'Clear', 'Clearing');
                                                                            });" data-toggle="tooltip" title="Auto-Clear Selected Transaction">
                                                    <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                                <?php
                                            } else if ($canClearTrns && $row[19] == "1") {
                                                ?>
                                                <button type="button" class="btn btn-default btn-sm" onclick="clrUnclrMCFTrnsRqst(<?php echo $row[0]; ?>, 'UnClear', 'UnClearing');" data-toggle="tooltip" title="UnClear Selected Transaction">
                                                    <img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_cust_account_trns_cheques|trns_cheque_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
        } else if ($subPgNo == "3.3") {
            //Miscellaneous GL Account Transactions
            echo $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.3');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Miscellaneous GL Account Transactions</span>
                            </li></ul></div>";
            $error = "";
            $searchAll = test_prmssns($dfltPrvldgs[212], $mdlNm);

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = 'All';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
            $trnsType = "ALL";
            $subTrnsType = "MISC_TRANS";
            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canAddTrns = test_prmssns($dfltPrvldgs[135], $mdlNm);
                $total = get_CustAcctTransactionsTtl($srchFor, $srchIn, $orgID, $searchAll, $trnsType, $subTrnsType, $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_CustAcctTransactions($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $trnsType, $subTrnsType, $qStrtDte, $qEndDte);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='mcfAcntTrnsForm' action='' method='post' accept-charset='UTF-8'>
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">MISCELLANEOUS TRANSACTIONS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType3 = "col-md-3";
                            if ($canAddTrns === true) {
                                ?>   
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'MISCELLANEOUS TRANSACTION', 14, '3.3', 0, 'ADD', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="exprtMiscTrns();" style="">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Export
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="importMiscTrns();" style="">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Import
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-5";
                            }
                            ?>                        
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="mcfAcntTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                    <input id="mcfAcntTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsDsplySze" style="min-width:50px !important;">                            
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
                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="mcfAcntTrnsStrtDate" name="mcfAcntTrnsStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="mcfAcntTrnsEndDate" name="mcfAcntTrnsEndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>                            
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=<?php echo $subPgNo; ?>','mcfAcntTrns');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                  
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allMcfTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canAddTrns === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <th>...</th>
                                            <th>Trans. Type - No.</th>		
                                            <th style="min-width:130px;width:130px;">Account Number</th>
                                            <th style="min-width:130px;width:130px;">Account Title</th>
                                            <th style="min-width:130px;width:130px;">Trans. Date</th>
                                            <th>CUR.</th>
                                            <th style="text-align:right;">Amount</th>
                                            <th>Status</th>
                                            <th>...</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            /**/
                                            $cntr += 1;
                                            ?>
                                            <tr id="allMcfTrnsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canAddTrns === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Transaction" 
                                                                onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'MISCELLANEOUS TRANSACTION', 14, '<?php echo $subPgNo; ?>', 0, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delMCFTrnsHdr('allMcfTrnsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                    <input type="hidden" id="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd"><?php
                                                    echo trim(str_replace(" - - ", " ", $row[5] . " - " . $row[12] . " - " . $row[13] . " - " . $row[14]), ' ');
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[11]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $row[10]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[6], 2);
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[7]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'DEPOSIT TRANSACTION', 14, '<?php echo $subPgNo; ?>', 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_cust_account_transactions|acct_trns_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
            }
        } else if ($subPgNo == "3.5") {
            //Standing Orders
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "All Fields";
            $vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : 0;
            $prsnid = $_SESSION['PRSN_ID'];
            $orgID = $_SESSION['ORG_ID'];
            $qRecurring = false;
            $qNonRecurring = false;
            $qNonExecuted = false;
            $qStrtDte = "01-Jan-1900 00:00:00";
            $qEndDte = "31-Dec-4000 23:59:59";
            $qLowVal = 0;
            $qHighVal = 0;
            if (isset($_POST['qRecurring'])) {
                $qRecurring = cleanInputData($_POST['qRecurring']) === "true" ? true : false;
            }
            if (isset($_POST['qNonRecurring'])) {
                $qNonRecurring = cleanInputData($_POST['qNonRecurring']) === "true" ? true : false;
            }
            if (isset($_POST['qNonExecuted'])) {
                $qNonExecuted = cleanInputData($_POST['qNonExecuted']) === "true" ? true : false;
            }
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
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.5');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Account Transfers & Standing Orders</span>
                                        </li></ul></div>";
            $canAddOrdrs = test_prmssns($dfltPrvldgs[139], $mdlNm);
            $canExecOrdrs = test_prmssns($dfltPrvldgs[134], $mdlNm);
            $total = get_AcntTrnsfrsTtl($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte, $qRecurring, $qNonRecurring, $qLowVal, $qHighVal, $qNonExecuted);
            if ($pageNo > ceil($total / $lmtSze)) {
                $pageNo = 1;
            } else if ($pageNo < 1) {
                $pageNo = ceil($total / $lmtSze);
            }
            $curIdx = $pageNo - 1;
            $result = get_AcntTrnsfrs($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte, $qRecurring, $qNonRecurring, $qLowVal, $qHighVal, $qNonExecuted);
            $cntr = 0;
            ?> 
            <form id='allStdngOrdrsForm' action='' method='post' accept-charset='UTF-8'>
                <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                    <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                        <div class="input-group">
                            <input class="form-control" id="allStdngOrdrsSrchFor" type = "text" placeholder="Search For" value="<?php
                            echo trim(str_replace("%", " ", $srchFor));
                            ?>" onkeyup="enterKeyFuncAllStdngOrdrs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                            <input id="allStdngOrdrsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllStdngOrdrs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllStdngOrdrs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');">
                                <span class="glyphicon glyphicon-search"></span>
                            </label> 
                        </div>
                    </div>
                    <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                        <div class="input-group">
                            <span class="input-group-addon">In</span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="allStdngOrdrsSrchIn">
                                <?php
                                $valslctdArry = array("", "", "");
                                $srchInsArrys = array("Account Number/Name", "Bank/Branch", "All Fields");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                            </select>
                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="allStdngOrdrsDsplySze" style="min-width:65px !important;">                            
                                <?php
                                $valslctdArry = array("", "", "", "", "", "",
                                    "", "");
                                $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100,
                                    500, 1000);
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
                    <div class="col-lg-4" style="padding:0px 1px 0px 1px !important;">
                        <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="allStdngOrdrsStrtDate" name="allStdngOrdrsStrtDate" value="<?php
                                echo substr($qStrtDte, 0, 11);
                                ?>" placeholder="Start Date">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div></div>
                        <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text"  id="allStdngOrdrsEndDate" name="allStdngOrdrsEndDate" value="<?php
                                echo substr($qEndDte, 0, 11);
                                ?>" placeholder="End Date">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>                            
                    </div>
                    <div class="col-lg-2" style="padding:0px 1px 0px 1px !important;">
                        <nav aria-label="Page navigation">
                            <ul class="pagination" style="margin: 0px !important;">
                                <li>
                                    <a class="rhopagination" href="javascript:getAllStdngOrdrs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getAllStdngOrdrs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                    <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <label class="btn btn-primary btn-file input-group-addon">
                                    <span class="glyphicon glyphicon-sort-by-order"></span>Amount Range From:
                                </label>
                                <input class="form-control" id="allStdngOrdrsLowVal" type = "number" placeholder="Low Value" value="<?php
                                echo $qLowVal;
                                ?>" onkeyup="enterKeyFuncAllStdngOrdrs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                            </div>
                        </div>   
                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <label class="btn btn-primary btn-file input-group-addon">
                                    <span class="glyphicon glyphicon-sort-by-order-alt"></span> To:
                                </label>
                                <input class="form-control" id="allStdngOrdrsHighVal" type = "number" placeholder="High Value" value="<?php
                                echo $qHighVal;
                                ?>" onkeyup="enterKeyFuncAllStdngOrdrs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                            </div>
                        </div>
                        <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                            <div class="form-check" style="font-size: 12px !important;">
                                <label class="form-check-label">
                                    <?php
                                    $recurringChekd = "";
                                    if ($qRecurring == true) {
                                        $recurringChekd = "checked=\"true\"";
                                    }
                                    ?>
                                    <input type="checkbox" class="form-check-input" onclick="getAllStdngOrdrs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" id="allStdngOrdrsIsRcrng" name="allStdngOrdrsIsRcrng" <?php echo $recurringChekd; ?>>
                                    Recurring Transfers
                                </label>
                            </div>                            
                        </div>
                        <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                            <div class="form-check" style="font-size: 12px !important;">
                                <label class="form-check-label">
                                    <?php
                                    $nonRecurringChekd = "";
                                    if ($qNonRecurring == true) {
                                        $nonRecurringChekd = "checked=\"true\"";
                                    }
                                    ?>
                                    <input type="checkbox" class="form-check-input" onclick="getAllStdngOrdrs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" id="allStdngOrdrsIsNotRcrng" name="allStdngOrdrsIsNotRcrng" <?php echo $nonRecurringChekd; ?>>
                                    Non-Recurring
                                </label>
                            </div>                            
                        </div>
                        <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                            <div class="form-check" style="font-size: 12px !important;">
                                <label class="form-check-label">
                                    <?php
                                    $notExecutedChkd = "";
                                    if ($qNonExecuted == true) {
                                        $notExecutedChkd = "checked=\"true\"";
                                    }
                                    ?>
                                    <input type="checkbox" class="form-check-input" onclick="getAllStdngOrdrs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" id="allStdngOrdrsIsNotExctd" name="allStdngOrdrsIsNotExctd" <?php echo $notExecutedChkd; ?>>
                                    Not Executed
                                </label>
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                        <div class="col-md-8" style="padding:2px 1px 2px 1px !important;">
                            <?php if ($canAddOrdrs) { ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="getOneAcntTrnsfrForm(-1, '<?php echo $trnsType; ?>', 'ShowDialog');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Transfer/Order
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form>
            <form id='allStdngOrdrsHdrsForm' action='' method='post' accept-charset='UTF-8'>
                <div class="row"> 
                    <div  class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="allStdngOrdrsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>&nbsp;</th>
                                    <th>Source Account Number/Title</th>
                                    <th>Type</th>
                                    <th>Beneficiary Account/Wallet Number/Name</th>
                                    <!--<th>Destination Bank/Branch</th>-->
                                    <th>Frequency</th>
                                    <th>Duration</th>
                                    <th style="text-align:right;">(CUR.) Amount</th>
                                    <!--<th style="text-align:right;">Rate</th>-->
                                    <th>Status</th>
                                    <th style="text-align:center;">No. of Executions</th>
                                    <th>&nbsp;</th>
                                    <?php if ($canVwRcHstry === true) { ?>
                                        <th>...</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = loc_db_fetch_array($result)) {
                                    /**/
                                    $cntr += 1;
                                    ?>
                                    <tr id="allStdngOrdrsHdrsRow_<?php echo $cntr; ?>">                   
                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneAcntTrnsfrForm(<?php echo $row[0]; ?>, 'Transfer/Order', 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                        <td class="lovtd">
                                            <?php echo $row[26] . " - " . $row[27]; ?>
                                            <input type="hidden" value="<?php echo $row[0]; ?>" id="allStdngOrdrsHdrsRow<?php echo $cntr; ?>_StndngOrdrID">
                                            <input type="hidden" value="<?php echo $row[1]; ?>" id="allStdngOrdrsHdrsRow<?php echo $cntr; ?>_SrcAcntID">
                                        </td>
                                        <td class="lovtd"><?php echo $row[2] . " - " . $row[4]; ?></td>
                                        <td class="lovtd"><?php echo $row[3] . " - " . $row[14]; ?></td>
                                        <!--<td class="lovtd"><?php echo $row[11] . " - " . $row[13]; ?></td>-->
                                        <td class="lovtd"><?php echo "Every " . $row[6] . " " . $row[7] . "(s)"; ?></td>
                                        <td class="lovtd"><?php echo $row[8] . " - " . $row[9]; ?></td>
                                        <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php
                                            echo $row[18] . " " . number_format((float) $row[5], 2);
                                            ?>
                                        </td>
                                        <!--<td class="lovtd"><?php echo number_format((float) $row[19], 4); ?></td>-->
                                        <td class="lovtd" style="font-weight:bold;"><?php echo $row[28]; ?></td>
                                        <td class="lovtd"><?php echo "<span style=\"color:green;font-weight:bold;\">Success:" . $row[20] . "</span><br/><span style=\"color:red;font-weight:bold;\">Incomplete:" . $row[21] . "</span>"; ?></td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="delAcntTrnsfrHdr('allStdngOrdrsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_standing_orders|stndn_order_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
    }
}
?>
