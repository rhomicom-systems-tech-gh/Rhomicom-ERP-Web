<?php
$canAdd = test_prmssns($dfltPrvldgs[14], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[15], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);

$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Account */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteTrns_AmntBrkdwn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                
            }
        } else {
            if ($vwtyp == 0) {
                //Search for Transactions
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Transactions Search</span>
                            </li>
                           </ul>
                          </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date (DESC)";
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
                    $total = get_Total_Transactions($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte, $qShwPstdOnly, $qShwIntrfc, $qLowVal, $qHighVal);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_Transactions($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte, $qShwPstdOnly, $qShwIntrfc, $qLowVal,
                            $qHighVal, $sortBy);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-4";
                    ?> 
                    <form id='accbTransSrchForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">SEARCH TRANSACTIONS</legend>
                            <div class="row" style="margin-bottom:0px;">                                
                                <div class="col-md-6" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="accbTransSrchSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncAccbTransSrch(event, '', '#allmodules', 'grp=6&typ=1&pg=4&vtyp=0')">
                                        <input id="accbTransSrchPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbTransSrch('clear', '#allmodules', 'grp=6&typ=1&pg=4&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbTransSrch('', '#allmodules', 'grp=6&typ=1&pg=4&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbTransSrchSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "");
                                            $srchInsArrys = array("All", "Account Number", "Account Name", "Transaction Description",
                                                "Transaction Date", "Batch Name", "Transaction ID");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbTransSrchDsplySze" style="min-width:70px !important;">                            
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
                                <div class="col-md-2">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" style="margin: 0px !important;">
                                            <li>
                                                <a href="javascript:getAccbTransSrch('previous', '#allmodules', 'grp=6&typ=1&pg=4&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getAccbTransSrch('next', '#allmodules', 'grp=6&typ=1&pg=4&vtyp=0');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <?php
                                            $shwIntrfcChkd = "";
                                            if ($qShwIntrfc == true) {
                                                $shwIntrfcChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" onclick="getAccbTransSrch('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbTransSrchShwIntrfc" name="accbTransSrchShwIntrfc"  <?php echo $shwIntrfcChkd; ?>>
                                            Show Interface
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <?php
                                            $shwPstdOnlyChkd = "";
                                            if ($qShwPstdOnly == true) {
                                                $shwPstdOnlyChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" onclick="getAccbTransSrch('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbTransSrchShwPstdOnly" name="accbTransSrchShwPstdOnly"  <?php echo $shwPstdOnlyChkd; ?>>
                                            Show Only Posted
                                        </label>
                                    </div>  
                                </div>
                            </div> 
                            <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                                <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                    <div class="col-md-5" style="padding:0px 1px 0px 1px !important;">
                                        <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                <input class="form-control" size="16" type="text" id="accbTransSrchStrtDate" name="accbTransSrchStrtDate" value="<?php
                                                echo substr($qStrtDte, 0, 11);
                                                ?>" placeholder="Start Date">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                <input class="form-control" size="16" type="text"  id="accbTransSrchEndDate" name="accbTransSrchEndDate" value="<?php
                                                echo substr($qEndDte, 0, 11);
                                                ?>" placeholder="End Date">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>                            
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon">
                                                <span class="glyphicon glyphicon-sort-by-order"></span>
                                            </label>
                                            <input class="form-control" id="accbTransSrchLowVal" type = "number" placeholder="Low Value" value="<?php
                                            echo $qLowVal;
                                            ?>" onkeyup="enterKeyFuncAccbTransSrch(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                            <label class="btn btn-primary btn-file input-group-addon">
                                                <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                                            </label>
                                            <input class="form-control" id="accbTransSrchHighVal" type = "number" placeholder="High Value" value="<?php
                                            echo $qHighVal;
                                            ?>" onkeyup="enterKeyFuncAccbTransSrch(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        </div>
                                    </div>   
                                    <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbTransSrchSortBy" style="width:100% !important;">
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
                                    <div class="col-md-1" style="padding:0px 1px 0px 1px !important;">
                                        <button type="button" class="btn btn-default" onclick="funcHtmlToExcel('accbTransSrchHdrsTable');" style="">
                                            <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                    </div> 
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="accbTransSrchHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:25px;width:25px;">No.</th>
                                                <th style="max-width:20px;width:20px;">...</th>
                                                <th style="max-width:58px;width:58px;">Trans. ID</th>
                                                <th style="min-width:200px;width:200px;">Account Number/Name</th>	
                                                <th>Transaction Description (Reference Doc. No.)</th>
                                                <th style="max-width:35px;width:35px;">CUR.</th>	
                                                <th style="text-align:right;">Debit</th>
                                                <th style="text-align:right;">Credit</th>
                                                <th style="min-width:90px;width:90px;">Transaction Date</th>
                                                <th style="max-width:150px;width:150px;">Batch Name/Source</th>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th style="max-width:20px;width:20px;">...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ttlTrsctnDbtAmnt = 0;
                                            $ttlTrsctnCrdtAmnt = 0;
                                            $ttlTrsctnNetAmnt = 0;
                                            $rcHstryTblNm = "";
                                            $rcHstryPKeyColNm = "";
                                            $rcHstryPKeyColVal = "";
                                            $intrcfID = -1;
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;

                                                $rcHstryTblNm = "accb.accb_trnsctn_details";
                                                $rcHstryPKeyColNm = "transctn_id";
                                                $rcHstryPKeyColVal = $row[0];
                                                $batchSrc = $row[13];
                                                $intrcfID = (float) $row[24];
                                                if ($intrcfID > 0) {
                                                    $rcHstryPKeyColNm = "interface_id";
                                                    $rcHstryPKeyColVal = $intrcfID;
                                                    if (strpos($batchSrc, "Internal Payments") !== FALSE) {
                                                        $rcHstryTblNm = "pay.pay_gl_interface";
                                                    } else if (strpos($batchSrc, "Banking") !== FALSE) {
                                                        $rcHstryTblNm = "mcf.mcf_gl_interface";
                                                    } else if (strpos($batchSrc, "Vault") !== FALSE) {
                                                        $rcHstryTblNm = "vms.vms_gl_interface";
                                                    } else {
                                                        $rcHstryTblNm = "scm.scm_gl_interface";
                                                    }
                                                }
                                                $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + ((float) $row[4]);
                                                $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + ((float) $row[5]);
                                                //$ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + ((float) $row[10]);
                                                $isPosted = ($row[12] == "1") ? "true" : "false";
                                                ?>
                                                <tr id="accbTransSrchHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                    <td class="lovtd">
                                                        <input type="hidden" id="accbTransSrchHdrsRow<?php echo $cntr; ?>_HdrID" name="accbTransSrchHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        <?php if ($row[25] != ",") { ?>
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Interface Table Breakdown" 
                                                                    onclick="getAccbTransSrchDet(<?php echo $row[0]; ?>, 'Transaction ID', <?php echo $isPosted; ?>, true, '<?php
                                                                    echo substr($qStrtDte, 0, 11);
                                                                    ?>', '<?php echo substr($qEndDte, 0, 11); ?>', 'Breakdown of Source Transactions', 'ShowDialog', function () {});" style="padding:2px !important;" style="padding:2px !important;"> 
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                            </button>
                                                        <?php } else { ?>
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" 
                                                                    onclick="getAccbCashBreakdown(<?php echo $row[0]; ?>, 'ShowDialog', 'Transaction Amount Breakdown', 'VIEW', '<?php echo $defaultBrkdwnLOV; ?>', '', '');" style="padding:2px !important;" style="padding:2px !important;"> 
                                                                <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                            </button>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo str_replace(" [-199]", "", $row[0] . " [" . $row[24] . "]"); ?></td>
                                                    <td class="lovtd">
                                                        <?php
                                                        echo str_replace("()", "", $row[1] . "." . $row[2] . " (" . $row[23] . ")");
                                                        ?>
                                                    </td>
                                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo $row[3]; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $fnccurnm; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                        echo number_format((float) $row[4], 2);
                                                        ?>
                                                    </td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                        echo number_format((float) $row[5], 2);
                                                        ?>
                                                    </td>
                                                    <td class="lovtd" style=""><?php echo $row[6]; ?></td> 
                                                    <td class="lovtd" style=""><?php
                                                        echo $row[11] . " [" . ($row[12] == "1" ? "<span style=\"color:green;font-weight:bold;\">Posted</span>"
                                                                    : "<span style=\"color:red;font-weight:bold;\">Not Posted (" . $row[7] . ")</span>") . "]";
                                                        ?></td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" 
                                                                    onclick="getRecHstry('<?php
                                                                    echo urlencode(encrypt1(($rcHstryPKeyColVal . "|" . $rcHstryTblNm . "|" . $rcHstryPKeyColNm),
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
                                        <tfoot>                                                            
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>TOTALS:</th>
                                                <th style=""><?php echo $fnccurnm; ?></th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"mySrchTrnsDbtsTtlBtn\">" . number_format($ttlTrsctnDbtAmnt,
                                                            2, '.', ',') . "</span>";
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbDbtsTtlVal" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                                </th>
                                                <th style="text-align: right;">
                                                    <?php
                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"mySrchTrnsCrdtsTtlBtn\">" . number_format($ttlTrsctnCrdtAmnt,
                                                            2, '.', ',') . "</span>";
                                                    ?>
                                                    <input type="hidden" id="myCptrdJbCrdtsTtlVal" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                                </th>
                                                <th style="">&nbsp;</th>                                           
                                                <th style="">&nbsp;</th>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                <?php } ?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>                     
                            </div>
                        </fieldset>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 1) {
                //Search for Transactions Dialog
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'All';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date (DESC)";
                $qShwPstdOnly = true;
                if (isset($_POST['qShwPstdOnly'])) {
                    $qShwPstdOnly = cleanInputData($_POST['qShwPstdOnly']) === "true" ? true : false;
                }
                $qShwIntrfc = true;
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
                $total = get_Total_Transactions($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte, $qShwPstdOnly, $qShwIntrfc, $qLowVal, $qHighVal);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_Transactions($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte, $qShwPstdOnly, $qShwIntrfc, $qLowVal,
                        $qHighVal, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-4";
                ?> 
                <form id='accbTransSrchDiagForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="">
                        <div class="row" style="margin-bottom:0px;">                                
                            <div class="col-md-6" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="accbTransSrchDiagSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAccbTransSrchDiag(event, '', '#myFormsModalLxBody', 'grp=6&typ=1&pg=4&vtyp=1')">
                                    <input id="accbTransSrchDiagPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbTransSrchDiag('clear', '#myFormsModalLxBody', 'grp=6&typ=1&pg=4&vtyp=1');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbTransSrchDiag('', '#myFormsModalLxBody', 'grp=6&typ=1&pg=4&vtyp=1');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbTransSrchDiagSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "");
                                        $srchInsArrys = array("All", "Account Number", "Account Name", "Transaction Description", "Transaction Date", "Batch Name",
                                            "Transaction ID");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbTransSrchDiagDsplySze" style="min-width:70px !important;">                            
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
                            <div class="col-md-2">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getAccbTransSrchDiag('previous', '#myFormsModalLxBody', 'grp=6&typ=1&pg=4&vtyp=1');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getAccbTransSrchDiag('next', '#myFormsModalLxBody', 'grp=6&typ=1&pg=4&vtyp=1');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <?php
                                        $shwIntrfcChkd = "";
                                        if ($qShwIntrfc == true) {
                                            $shwIntrfcChkd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getAccbTransSrchDiag('', '#myFormsModalLxBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbTransSrchDiagShwIntrfc" name="accbTransSrchDiagShwIntrfc"  <?php echo $shwIntrfcChkd; ?>>
                                        Show Interface
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <?php
                                        $shwPstdOnlyChkd = "";
                                        if ($qShwPstdOnly == true) {
                                            $shwPstdOnlyChkd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getAccbTransSrchDiag('', '#myFormsModalLxBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="accbTransSrchDiagShwPstdOnly" name="accbTransSrchDiagShwPstdOnly"  <?php echo $shwPstdOnlyChkd; ?>>
                                        Show Only Posted
                                    </label>
                                </div>  
                            </div>
                        </div>  
                        <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                            <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                <div class="col-md-5" style="padding:0px 1px 0px 1px !important;">
                                    <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" size="16" type="text" id="accbTransSrchDiagStrtDate" name="accbTransSrchDiagStrtDate" value="<?php
                                            echo substr($qStrtDte, 0, 11);
                                            ?>" placeholder="Start Date">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" size="16" type="text"  id="accbTransSrchDiagEndDate" name="accbTransSrchDiagEndDate" value="<?php
                                            echo substr($qEndDte, 0, 11);
                                            ?>" placeholder="End Date">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>                            
                                </div>
                                <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-primary btn-file input-group-addon">
                                            <span class="glyphicon glyphicon-sort-by-order"></span>
                                        </label>
                                        <input class="form-control" id="accbTransSrchDiagLowVal" type = "number" placeholder="Low Value" value="<?php
                                        echo $qLowVal;
                                        ?>" onkeyup="enterKeyFuncAccbTransSrchDiag(event, '', '#myFormsModalLxBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    </div>
                                </div>   
                                <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-primary btn-file input-group-addon">
                                            <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                                        </label>
                                        <input class="form-control" id="accbTransSrchDiagHighVal" type = "number" placeholder="High Value" value="<?php
                                        echo $qHighVal;
                                        ?>" onkeyup="enterKeyFuncAccbTransSrchDiag(event, '', '#myFormsModalLxBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbTransSrchDiagSortBy">
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
                        </div>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="accbTransSrchDiagHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:25px;width:25px;">No.</th>
                                            <th style="max-width:58px;width:58px;">Trans. ID</th>
                                            <th style="min-width:200px;width:200px;">Account Number/Name</th>	
                                            <th>Transaction Description (Reference Doc. No.)</th>
                                            <th style="max-width:35px;width:35px;">CUR.</th>	
                                            <th style="text-align:right;">Debit</th>
                                            <th style="text-align:right;">Credit</th>
                                            <th style="min-width:90px;width:90px;">Transaction Date</th>
                                            <th style="max-width:150px;width:150px;">Batch Name/Source</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th style="max-width:20px;width:20px;">...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ttlTrsctnDbtAmnt = 0;
                                        $ttlTrsctnCrdtAmnt = 0;
                                        $ttlTrsctnNetAmnt = 0;
                                        $rcHstryTblNm = "";
                                        $rcHstryPKeyColNm = "";
                                        $rcHstryPKeyColVal = "";
                                        $intrcfID = -1;
                                        while ($row = loc_db_fetch_array($result)) {
                                            $cntr += 1;

                                            $rcHstryTblNm = "accb.accb_trnsctn_details";
                                            $rcHstryPKeyColNm = "transctn_id";
                                            $rcHstryPKeyColVal = $row[0];
                                            $batchSrc = $row[13];
                                            $intrcfID = (float) $row[24];
                                            if ($intrcfID > 0) {
                                                $rcHstryPKeyColNm = "interface_id";
                                                $rcHstryPKeyColVal = $intrcfID;
                                                if (strpos($batchSrc, "Internal Payments") !== FALSE) {
                                                    $rcHstryTblNm = "pay.pay_gl_interface";
                                                } else if (strpos($batchSrc, "Banking") !== FALSE) {
                                                    $rcHstryTblNm = "mcf.mcf_gl_interface";
                                                } else if (strpos($batchSrc, "Vault") !== FALSE) {
                                                    $rcHstryTblNm = "vms.vms_gl_interface";
                                                } else {
                                                    $rcHstryTblNm = "scm.scm_gl_interface";
                                                }
                                            }
                                            $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + ((float) $row[4]);
                                            $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + ((float) $row[5]);
                                            //$ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + ((float) $row[10]);
                                            ?>
                                            <tr id="accbTransSrchDiagHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td> 
                                                <td class="lovtd" style="word-wrap: break-word;"><?php echo str_replace(" [-199]", "", $row[0] . " [" . $row[24] . "]"); ?></td>
                                                <td class="lovtd">
                                                    <?php
                                                    echo str_replace("()", "", $row[1] . "." . $row[2] . " (" . $row[23] . ")");
                                                    ?>
                                                </td>
                                                <td class="lovtd" style="word-wrap: break-word;"><?php echo $row[3]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $fnccurnm; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[4], 2);
                                                    ?>
                                                </td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[5], 2);
                                                    ?>
                                                </td>
                                                <td class="lovtd" style=""><?php echo $row[6]; ?></td> 
                                                <td class="lovtd" style=""><?php
                                                    echo $row[11] . " [" . ($row[12] == "1" ? "<span style=\"color:green;font-weight:bold;\">Posted</span>"
                                                                : "<span style=\"color:red;font-weight:bold;\">Not Posted (" . $row[7] . ")</span>") . "]";
                                                    ?></td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" 
                                                                onclick="getRecHstry('<?php
                                                                echo urlencode(encrypt1(($rcHstryPKeyColVal . "|" . $rcHstryTblNm . "|" . $rcHstryPKeyColNm),
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
                                    <tfoot>                                                            
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>TOTALS:</th>
                                            <th style=""><?php echo $fnccurnm; ?></th>
                                            <th style="text-align: right;">
                                                <?php
                                                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"mySrchTrnsDbtsTtlBtn\">" . number_format($ttlTrsctnDbtAmnt,
                                                        2, '.', ',') . "</span>";
                                                ?>
                                                <input type="hidden" id="myCptrdJbDbtsTtlVal" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                            </th>
                                            <th style="text-align: right;">
                                                <?php
                                                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"mySrchTrnsCrdtsTtlBtn\">" . number_format($ttlTrsctnCrdtAmnt,
                                                        2, '.', ',') . "</span>";
                                                ?>
                                                <input type="hidden" id="myCptrdJbCrdtsTtlVal" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                            </th>
                                            <th style="">&nbsp;</th>                                           
                                            <th style="">&nbsp;</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th style="max-width:20px;width:20px;">&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>                     
                        </div>
                    </fieldset>
                </form>
                <?php
            } else if ($vwtyp == 2) {
                //Transactions Breakdown Dialog "EDIT"; // 
                $error = "";
                $sbmtdTrnsID = isset($_POST['sbmtdTrnsID']) ? cleanInputData($_POST['sbmtdTrnsID']) : -1;
                $vtypActn = isset($_POST['vtypActn']) ? cleanInputData($_POST['vtypActn']) : 'VIEW';
                $trnsAmntElmntID = isset($_POST['trnsAmntElmntID']) ? cleanInputData($_POST['trnsAmntElmntID']) : '';
                $trnsAmtBrkdwnSaveElID = isset($_POST['trnsAmtBrkdwnSaveElID']) ? cleanInputData($_POST['trnsAmtBrkdwnSaveElID']) : '';
                $slctdBrkdwnLines = isset($_POST['slctdBrkdwnLines']) ? cleanInputData($_POST['slctdBrkdwnLines']) : '';
                $sbmtdlovName = isset($_POST['sbmtdlovName']) ? cleanInputData($_POST['sbmtdlovName']) : $defaultBrkdwnLOV;
                $lovID = getLovID($sbmtdlovName);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-4";
                $mkReadOnly = "";
                if ($vtypActn == "VIEW") {
                    $mkReadOnly = "readonly=\"true\"";
                }
                ?> 
                <form id='accbTransBrkdwnDiagForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="">
                        <?php if ($vtypActn == "EDIT") { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-group-sm" style="margin-top:5px;">
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;padding:2px 0px 0px 5px !important;">Breakdown Type:&nbsp;</label>
                                        </div>
                                        <div class = "col-md-9" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="accbTransBrkdwnDiagLovNm" name="accbTransBrkdwnDiagLovNm" value="<?php echo $sbmtdlovName; ?>" readonly="true">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', '', 'accbTransBrkdwnDiagLovNm', 'clear', 1, ' and tbl1.b ilike \'%breakdown%\'', function () {
                                                                                getAccbCashBreakdown(<?php echo $sbmtdTrnsID; ?>, 'ReloadDialog', 'Transaction Amount Breakdown', '<?php echo $vtypActn; ?>', '<?php echo $sbmtdlovName; ?>', '<?php echo $trnsAmntElmntID; ?>', '<?php echo $trnsAmtBrkdwnSaveElID; ?>');
                                                                            });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="accbTransBrkdwnDiagHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:25px;width:25px;">No.</th>	
                                            <th>Transaction Description (Reference Doc. No.)</th>
                                            <th style="text-align:right;">QTY / MULTIPLIER</th>
                                            <th style="text-align:right;">Unit Amount</th>
                                            <th style="text-align:right;">Total Amount</th>
                                            <?php if ($canEdt === true) { ?>
                                                <th style="max-width:20px;width:20px;">...</th>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th style="max-width:20px;width:20px;">...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ttlTrsctnAmnt = 0;
                                        if (trim($slctdBrkdwnLines, "|~") != "") {
                                            $variousRows = explode("|", trim($slctdBrkdwnLines, "|"));
                                            for ($z = 0; $z < count($variousRows); $z++) {
                                                $crntRow = explode("~", $variousRows[$z]);
                                                if (count($crntRow) == 6) {
                                                    $brkdwnDetID = (float) (cleanInputData1($crntRow[0]));
                                                    $brkdwnPValID = (float) (cleanInputData1($crntRow[1]));
                                                    $brkdwnDesc = cleanInputData1($crntRow[2]);
                                                    $brkdwnQty = (float) (cleanInputData1($crntRow[3]));
                                                    $brkdwnUVal = (float) (cleanInputData1($crntRow[4]));
                                                    $brkdwnTtlVal = (float) (cleanInputData1($crntRow[5]));
                                                    $cntr += 1;
                                                    $rcHstryTblNm = "accb.accb_trnsctn_amnt_breakdown";
                                                    $rcHstryPKeyColNm = "transaction_id";
                                                    $rcHstryPKeyColVal = $brkdwnDetID;
                                                    $ttlTrsctnAmnt = $ttlTrsctnAmnt + $brkdwnTtlVal;
                                                    ?>
                                                    <tr id="accbTransBrkdwnDiagHdrsRow_<?php echo $cntr; ?>">                                    
                                                        <td class="lovtd"><?php echo ($cntr); ?></td> 
                                                        <td class="lovtd">
                                                            <textarea class="form-control rqrdFld acbBrkdwnDesc" rows="1" cols="20" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" name="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" <?php echo $mkReadOnly; ?> style="text-align:left !important;width:100%;"  onkeypress="gnrlFldKeyPress(event, 'accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc', 'accbTransBrkdwnDiagHdrsTable', 'acbBrkdwnDesc');"><?php echo $brkdwnDesc; ?></textarea>  
                                                            <input type="hidden" class="form-control" aria-label="..." id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnLnID" value="<?php echo $brkdwnDetID; ?>" style="width:100% !important;">   
                                                            <input type="hidden" class="form-control" aria-label="..." id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_LnkdPValID" value="<?php echo $brkdwnPValID; ?>" style="width:100% !important;"> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <input class="form-control rqrdFld acbBrkdwnQTY" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnQTY" type = "text" placeholder="0" value="<?php
                                                            echo number_format($brkdwnQty, 0);
                                                            ?>" <?php echo $mkReadOnly; ?> style="text-align:right;font-size:16px;font-weight:bold;width:100%;" onchange="calcAllAccbCashBrkdwn();" onkeypress="gnrlFldKeyPress(event, 'accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnQTY', 'accbTransBrkdwnDiagHdrsTable', 'acbBrkdwnQTY');">
                                                        </td>
                                                        <td class="lovtd">
                                                            <input class="form-control rqrdFld acbBrkdwnUVl" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnUnitVal" type = "text" placeholder="0" value="<?php
                                                            echo number_format($brkdwnUVal, 2);
                                                            ?>" <?php echo $mkReadOnly; ?> style="text-align:right;font-size:16px;font-weight:bold;width:100%;" onchange="calcAllAccbCashBrkdwn();" onkeypress="gnrlFldKeyPress(event, 'accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnUnitVal', 'accbTransBrkdwnDiagHdrsTable', 'acbBrkdwnUVl');">
                                                        </td>
                                                        <td class="lovtd">
                                                            <input class="form-control acbBrkdwnTtl" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnTtl" type = "text" placeholder="0" value="<?php
                                                            echo number_format($brkdwnTtlVal, 0);
                                                            ?>" readonly="true" style="text-align:right;font-size:16px;font-weight:bold;color:blue;width:100%;" onkeypress="gnrlFldKeyPress(event, 'accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnTtl', 'accbTransBrkdwnDiagHdrsTable', 'acbBrkdwnTtl');">
                                                        </td> 
                                                        <?php if ($canEdt === true) { ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbTrnsAmntBrkdwn('accbTransBrkdwnDiagHdrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Amount Breakdown">
                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        <?php } ?>
                                                        <?php if ($canVwRcHstry === true) {
                                                            ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" 
                                                                        onclick="getRecHstry('<?php
                                                                        echo urlencode(encrypt1(($rcHstryPKeyColVal . "|" . $rcHstryTblNm . "|" . $rcHstryPKeyColNm),
                                                                                        $smplTokenWord1));
                                                                        ?>');" style="padding:2px !important;">
                                                                    <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        <?php } ?>   
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        } else {
                                            $result = get_Trns_AmntBrkdwn($sbmtdTrnsID, $lovID, $vtypActn);
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;
                                                $rcHstryTblNm = "accb.accb_trnsctn_amnt_breakdown";
                                                $rcHstryPKeyColNm = "transaction_id";
                                                $rcHstryPKeyColVal = $row[0];
                                                $ttlTrsctnAmnt = $ttlTrsctnAmnt + ((float) $row[4]);
                                                ?>
                                                <tr id="accbTransBrkdwnDiagHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($cntr); ?></td> 
                                                    <td class="lovtd">
                                                        <textarea class="form-control rqrdFld acbBrkdwnDesc" rows="1" cols="20" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" name="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc" <?php echo $mkReadOnly; ?> style="text-align:left !important;width:100%;"  onkeypress="gnrlFldKeyPress(event, 'accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnDesc', 'accbTransBrkdwnDiagHdrsTable', 'acbBrkdwnDesc');"><?php echo $row[1]; ?></textarea>  
                                                        <input type="hidden" class="form-control" aria-label="..." id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnLnID" value="<?php echo $row[0]; ?>" style="width:100% !important;">   
                                                        <input type="hidden" class="form-control" aria-label="..." id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_LnkdPValID" value="<?php echo $row[5]; ?>" style="width:100% !important;"> 
                                                    </td>
                                                    <td class="lovtd">
                                                        <input class="form-control rqrdFld acbBrkdwnQTY" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnQTY" type = "text" placeholder="0" value="<?php
                                                        echo number_format((float) $row[2], 0);
                                                        ?>" <?php echo $mkReadOnly; ?> style="text-align:right;font-size:16px;font-weight:bold;width:100%;" onchange="calcAllAccbCashBrkdwn();" onkeypress="gnrlFldKeyPress(event, 'accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnQTY', 'accbTransBrkdwnDiagHdrsTable', 'acbBrkdwnQTY');">
                                                    </td>
                                                    <td class="lovtd">
                                                        <input class="form-control rqrdFld acbBrkdwnUVl" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnUnitVal" type = "text" placeholder="0" value="<?php
                                                        echo number_format((float) $row[3], 2);
                                                        ?>" <?php echo $mkReadOnly; ?> style="text-align:right;font-size:16px;font-weight:bold;width:100%;" onchange="calcAllAccbCashBrkdwn();" onkeypress="gnrlFldKeyPress(event, 'accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnUnitVal', 'accbTransBrkdwnDiagHdrsTable', 'acbBrkdwnUVl');">
                                                    </td>
                                                    <td class="lovtd">
                                                        <input class="form-control acbBrkdwnTtl" id="accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnTtl" type = "text" placeholder="0" value="<?php
                                                        echo number_format((float) $row[4], 0);
                                                        ?>" readonly="true" style="text-align:right;font-size:16px;font-weight:bold;color:blue;width:100%;" onkeypress="gnrlFldKeyPress(event, 'accbTransBrkdwnDiagHdrsRow<?php echo $cntr; ?>_BrkdwnTtl', 'accbTransBrkdwnDiagHdrsTable', 'acbBrkdwnTtl');">
                                                    </td> 
                                                    <?php if ($canEdt === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbTrnsAmntBrkdwn('accbTransBrkdwnDiagHdrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Amount Breakdown">
                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" 
                                                                    onclick="getRecHstry('<?php
                                                                    echo urlencode(encrypt1(($rcHstryPKeyColVal . "|" . $rcHstryTblNm . "|" . $rcHstryPKeyColNm),
                                                                                    $smplTokenWord1));
                                                                    ?>');" style="padding:2px !important;">
                                                                <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                <?php } ?>   
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>                                                            
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>TOTALS:</th>
                                            <th style="text-align: right;">
                                                <?php
                                                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myTransAmtBrkdwnTtlBtn\">" . number_format($ttlTrsctnAmnt,
                                                        2, '.', ',') . "</span>";
                                                ?>
                                                <input type="hidden" id="myTransAmtBrkdwnTtlVal" value="<?php echo $ttlTrsctnAmnt; ?>">
                                            </th>
                                            <?php if ($canEdt === true) { ?>
                                                <th style="max-width:20px;width:20px;">&nbsp;</th>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th style="max-width:20px;width:20px;">&nbsp;</th>
                <?php } ?>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>                     
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="float:right;">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <?php if ($vtypActn == "EDIT") { ?>
                                        <button type="button" class="btn btn-primary" onclick="applyNewAccbCashBrkdwn('myFormsModaly', '<?php echo $trnsAmntElmntID; ?>', '<?php echo $trnsAmtBrkdwnSaveElID; ?>');">Apply Total</button>
                <?php } ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <?php
            }
        }
    }
}
?>