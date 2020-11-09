<?php
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$qInvalidOnly = "false";
$qUnathrzdOnly = "false";
$date = new DateTime($gnrlTrnsDteDMYHMS);
$date->modify('-24 month');
$qStrtDte = $date->format('d-M-Y') . " 00:00:00";
$date = new DateTime($gnrlTrnsDteDMYHMS);
$date->modify('+24 month');
$qEndDte = $date->format('d-M-Y') . " 23:59:59";

$srchFor = "";
$srchIn = "";
//var_dump($_POST);
if (isset($_POST['qInvalidOnly'])) {
    $qInvalidOnly = cleanInputData($_POST['qInvalidOnly']);
}
if (isset($_POST['qUnathrzdOnly'])) {
    $qUnathrzdOnly = cleanInputData($_POST['qUnathrzdOnly']);
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

if (isset($_POST['searchfor'])) {
    $srchFor = cleanInputData($_POST['searchfor']);
}

if (isset($_POST['searchin'])) {
    $srchIn = cleanInputData($_POST['searchin']);
}
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}
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
            if ($vwtyp == 0) {
                //Search All Transactions
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Payment Transactions Search</span>
				</li>
                               </ul>
                              </div>";

                $total = get_Total_Trns($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_Pay_Trns($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte);
                $cntr = 0;
                ?> 
                <form id='allPayRnTrnsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                        <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allPayRnTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllPayRnTrns(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allPayRnTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPayRnTrns('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPayRnTrns('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <span class="input-group-addon">In</span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allPayRnTrnsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Person No.", "Person Name", "Item Name",
                                        "Transaction Date", "Transaction Description");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allPayRnTrnsDsplySze" style="min-width:65px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "",
                                        "", "", "");
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
                                    <input class="form-control" size="16" type="text" id="allPayRnTrnsStrtDate" name="allPayRnTrnsStrtDate" value="<?php
                                    echo substr($qStrtDte, 0, 11);
                                    ?>" placeholder="Start Date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div></div>
                            <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text"  id="allPayRnTrnsEndDate" name="allPayRnTrnsEndDate" value="<?php
                                    echo substr($qEndDte, 0, 11);
                                    ?>" placeholder="End Date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div></div>                            
                        </div>
                        <div class="col-lg-2" style="padding:0px 1px 0px 1px !important;">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllPayRnTrns('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllPayRnTrns('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allPayRnTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>		
                                        <th>ID No.</th>
                                        <th style="min-width: 110px;">Person Name</th>
                                        <th>Item Name</th>
                                        <th>Item Type</th>
                                        <th style="text-align:right;">UOM.</th>
                                        <th style="text-align:right;">Transaction Amount</th>
                                        <th>Transaction Date</th>
                                        <th>Transaction Type</th>
                                        <th>Transaction Description</th>
                                        <th>Validity</th>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $trnsID = $row[0];
                                        $trnsLocIDNo = $row[10];
                                        $trnsPrsnNm = $row[11];
                                        $trnsItmNm = $row[12];
                                        $trnsUOM = $row[14];
                                        $trnsAmnt = (float) $row[3];
                                        $trnsDate = $row[4];
                                        $trnsType = $row[6];
                                        $trnsDesc = $row[7];
                                        $trnsSource = $row[5];
                                        $trnsVldty = $row[13];
                                        $trnsItmType= $row[15];
                                        $cntr += 1;
                                        ?>
                                        <tr id="allPayRnTrnsHdrsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <?php echo $trnsLocIDNo; ?>
                                                <input type="hidden" id="allPayRnTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allPayRnTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $trnsID; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $trnsPrsnNm; ?></td>
                                            <td class="lovtd"><?php echo $trnsItmNm; ?></td>
                                            <td class="lovtd"><?php echo $trnsItmType; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo $trnsUOM; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php
                                                echo number_format($trnsAmnt, 2);
                                                ?>
                                            </td>
                                            <td class="lovtd"><?php echo $trnsDate; ?></td>
                                            <td class="lovtd"><?php echo $trnsType; ?></td>
                                            <td class="lovtd"><?php echo $trnsDesc." [".$trnsSource."]"; ?></td>
                                            <td class="lovtd"><?php echo $trnsVldty; ?></td>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                    echo urlencode(encrypt1(($trnsID . "|pay.pay_itm_trnsctns|pay_trns_id"), $smplTokenWord1));
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
        }
    }
}
?>