<?php
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
$curIdx = 0;
$orgID = $_SESSION['ORG_ID'];
$usrID = $_SESSION['USRID'];
$prsnid = $_SESSION['PRSN_ID'];

$vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
$vPsblVal1 = getPssblValDesc($vPsblValID1);
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
            } else if ($actyp == 2) {
            }
        } else {
            $cstmrID = getGnrlRecNm("scm.scm_cstmr_suplr", "lnkd_prsn_id", "cust_sup_id", $prsnid);
            $cstmrID1 = getGnrlRecNm("sec.sec_users", "user_id", "customer_id", $usrID);
            if ($cstmrID == "") {
                $cstmrID = -1234567;
            }
            if ($cstmrID1 == "" || $cstmrID1 == -1) {
                $cstmrID1 = -1234567;
            }
            $cstmrID = $cstmrID . "," . $cstmrID1;
            if ($vwtyp == 0) {
                $fnlColorAmntDffrnc = 0;
                $pkID = isset($_POST['sbmtdMspyID']) ? $_POST['sbmtdMspyID'] : -1;
                $sbmtdMspyID = -1;
                $sbmtdPyReqID = -1;
                echo $cntent . "<li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Bills & Pay Slips</span>
				</li>
                               </ul>
                              </div>";
                $total = get_MyPyRnsTtl($srchFor, $srchIn, $prsnid);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_MyPyRnsTblr($srchFor, $srchIn, $curIdx, $lmtSze, $prsnid);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
?>
                <form id='myPayRnsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;display:none;">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="" style="width:100% !important;">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Payment
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveMyPayRnyForm();" style="width:100% !important;">
                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Save
                                </button>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="myPayRnsSrchFor" type="text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncMyPayRns(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="myPayRnsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getMyPayRns('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getMyPayRns('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="myPayRnsSrchIn">
                                    <?php
                                    $valslctdArry = array("");
                                    $srchInsArrys = array("Name/Number");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                    ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="myPayRnsDsplySze" style="min-width:70px !important;">
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
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getMyPayRns('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getMyPayRns('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row" style="padding:0px 15px 0px 15px !important">
                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">
                                <table class="table table-striped table-bordered table-responsive" id="myPayRnsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Batch Name / Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($pkID <= 0 && $cntr <= 0) {
                                                $pkID = $row[1];
                                                $sbmtdMspyID = $pkID;
                                                $sbmtdPyReqID = $row[0];
                                            }
                                            $cntr += 1;
                                        ?>
                                            <tr id="myPayRnsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                <td><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td><?php echo $row[2]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="myPayRnsRow<?php echo $cntr; ?>_PyReqID" value="<?php echo $row[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="myPayRnsRow<?php echo $cntr; ?>_MspyID" value="<?php echo $row[1]; ?>">
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                        <div class="col-md-9" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                <div class="container-fluid" id="myPayRnsDetailInfo">
                                    <?php
                                    $locIDNo = getPersonLocID($prsnid);
                                    $reportName5 = "Customized Pay Slip (Sample 1)-Per Run";
                                    $reportTitle5 = "Pay Slip";
                                    $rptID5 = getRptID($reportName5);
                                    $prmID501 = getParamIDUseSQLRep("{:IDNos}", $rptID5);
                                    $prmID502 = getParamIDUseSQLRep("{:documentTitle}", $rptID5);
                                    $prmID503 = getParamIDUseSQLRep("{:orgID}", $rptID5);
                                    $prmID504 = getParamIDUseSQLRep("{:pay_run_id}", $rptID5);
                                    $paramRepsNVals5 = $prmID501 . "~'" . $locIDNo . "'|" . $prmID502 . "~" . $reportTitle5 . "|" . $prmID503 . "~" . $orgID . "|" . $prmID504 . "~" . $sbmtdMspyID . "|-130~" . $reportTitle5 . "|-190~PDF";
                                    $paramStr5 = urlencode($paramRepsNVals5);

                                    $result1 = get_MyPyHdrDet($sbmtdPyReqID, $sbmtdMspyID, $prsnid);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                    ?>
                                        <div class="row">
                                            <div class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;min-height:95px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myPayRnNm" class="control-label col-lg-4">Batch Name:</label>
                                                        <div class="col-lg-8">
                                                            <span><?php echo $row1[2]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myPayRnDesc" class="control-label col-lg-4">Batch Description:</label>
                                                        <div class="col-lg-8">
                                                            <span><?php echo $row1[3]; ?></span>
                                                        </div>
                                                    </div>
                                                    <?php if ($row1[2] != "CUMULATIVE BALANCES") { ?>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPayRnDesc" class="control-label col-lg-4">Print Slips:</label>
                                                            <div class="col-lg-8">
                                                                <button type="button" class="btn btn-default" style="" onclick="getSilentRptsRnSts(<?php echo $rptID5; ?>, -1, '<?php echo $paramStr5; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Pay Run Results Slip">
                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:12px; width:auto; position: relative; vertical-align: middle;">&nbsp;&nbsp;Results Slip&nbsp;&nbsp;&nbsp;
                                                                </button>
                                                                <button type="button" class="btn btn-default" style="" onclick="printPayPOSRcpt(<?php echo $sbmtdMspyID; ?>);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="POS Receipt">
                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Receipt
                                                                </button>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;min-height:95px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myPayRnDate" class="control-label col-lg-5">Date:</label>
                                                        <div class="col-lg-7">
                                                            <span><?php echo $row1[6]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myPayRnDesc" class="control-label col-lg-5">Batch Status:</label>
                                                        <div class="col-lg-7">
                                                            <span><?php echo $row1[5]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myPayRnNetAmnt" class="control-label col-lg-5" style="color:blue;">Net Amount (GHS):</label>
                                                        <div class="col-lg-7">
                                                            <span id="myPyRnTotal1"><?php echo "0.00"; ?></span>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <fieldset class="basic_person_fs">
                                                    <?php
                                                    $hideTable = "";
                                                    if ($vPsblVal1 == "NARHBT_COLLEGE_APP_1" && !($sbmtdMspyID <= 0 && $sbmtdPyReqID <= 0) && !$canVwQckPay) {
                                                        $hideTable = "hideNotice";
                                                    }
                                                    ?>
                                                    <table class="table table-striped table-bordered table-responsive <?php echo  $hideTable ?>" id="myPayRnLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Item Name</th>
                                                                <th style="text-align: right;">Amount (GHS)</th>
                                                                <th>Effective Date</th>
                                                                <th>Line Description</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $result2 = null;
                                                            if ($sbmtdMspyID <= 0 && $sbmtdPyReqID <= 0) {
                                                                $result2 = get_CumltiveBals($prsnid);
                                                            } else {
                                                                $result2 = get_MyPyRnsDt($sbmtdPyReqID, $sbmtdMspyID, $prsnid);
                                                            }
                                                            $cntr = 0;
                                                            $curIdx = 0;
                                                            $brghtTotal = 0;
                                                            $prpsdTtlSpnColor = "black";
                                                            $itmTypCnt = getBatchItmTypCnt($sbmtdPyReqID, $sbmtdMspyID, $prsnid);
                                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                                $cntr += 1;
                                                                $dsplyAmount = getBatchNetAmnt($itmTypCnt, $row2[11], $row2[6], $row2[10], $row2[7], $brghtTotal, $prpsdTtlSpnColor);
                                                                //getBatchNetAmnt($itmTypCnt, $row2[11], $row2[6], $row2[10], $row2[7], $brghtTotal, $prpsdTtlSpnColor)
                                                                if ($vPsblVal1 == "NARHBT_COLLEGE_APP_1" && $row1[2] != "CUMULATIVE BALANCES" && !$canVwQckPay) {
                                                                    continue;
                                                                }
                                                            ?>
                                                                <tr id="myPayRnLinesRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                    <td class="lovtd"><?php echo $row2[6]; ?></td>
                                                                    <td class="lovtd" style="text-align: right;"><?php echo $dsplyAmount; ?></td>
                                                                    <td class="lovtd"><span><?php echo $row2[8]; ?></span></td>
                                                                    <td class="lovtd"><span><?php echo $row2[9]; ?></span></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <th style="text-align: right;">Net Amount (GHS):</th>
                                                                <th style="text-align: right;"><?php echo "<span style=\"color:$prpsdTtlSpnColor;\">" . number_format($brghtTotal, 2, '.', ',') . "</span>"; ?></th>
                                                                <th>&nbsp;</th>
                                                                <th>&nbsp;</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                    <script type="text/javascript">
                                                        $(document).ready(function() {
                                                            $("#myPyRnTotal1").html('<?php echo "<span style=\"color:$prpsdTtlSpnColor;font-weight:bold;font-size:12px;\">" . number_format($brghtTotal, 2, '.', ',') . "</span>"; ?>');
                                                        });
                                                    </script>
                                                </fieldset>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 1) {
                //var_dump($_POST);
                $curIdx = 0;
                $fnlColorAmntDffrnc = 0;
                $sbmtdMspyID = isset($_POST['sbmtdMspyID']) ? $_POST['sbmtdMspyID'] : -1;
                $sbmtdPyReqID = isset($_POST['sbmtdPyReqID']) ? $_POST['sbmtdPyReqID'] : -1;
                $sbmtdPrsnSetMmbrID = isset($_POST['sbmtdPrsnSetMmbrID']) ? $_POST['sbmtdPrsnSetMmbrID'] : -1;

                $locIDNo = getPersonLocID($prsnid);
                $reportName5 = "Customized Pay Slip (Sample 1)-Per Run";
                $reportTitle5 = "Pay Slip";
                $rptID5 = getRptID($reportName5);
                $prmID501 = getParamIDUseSQLRep("{:IDNos}", $rptID5);
                $prmID502 = getParamIDUseSQLRep("{:documentTitle}", $rptID5);
                $prmID503 = getParamIDUseSQLRep("{:orgID}", $rptID5);
                $prmID504 = getParamIDUseSQLRep("{:pay_run_id}", $rptID5);
                $paramRepsNVals5 = $prmID501 . "~'" . $locIDNo . "'|" . $prmID502 . "~" . $reportTitle5 . "|" . $prmID503 . "~" . $orgID . "|" . $prmID504 . "~" . $sbmtdMspyID . "|-130~" . $reportTitle5 . "|-190~PDF";
                $paramStr5 = urlencode($paramRepsNVals5);
            ?>
                <?php
                if ($sbmtdPrsnSetMmbrID <= 0) {
                    $sbmtdPrsnSetMmbrID = $prsnid;
                }
                $result1 = get_MyPyHdrDet($sbmtdPyReqID, $sbmtdMspyID, $sbmtdPrsnSetMmbrID);
                while ($row1 = loc_db_fetch_array($result1)) {
                ?>
                    <div class="row">
                        <div class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;min-height:95px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="myPayRnNm" class="control-label col-lg-4">Batch Name:</label>
                                    <div class="col-lg-8">
                                        <span><?php echo $row1[2]; ?></span>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="myPayRnDesc" class="control-label col-lg-4">Batch Description:</label>
                                    <div class="col-lg-8">
                                        <span><?php echo $row1[3]; ?></span>
                                    </div>
                                </div>
                                <?php if ($row1[2] != "CUMULATIVE BALANCES") { ?>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPayRnDesc" class="control-label col-lg-4">Print Slips:</label>
                                        <div class="col-lg-8">
                                            <button type="button" class="btn btn-default" style="" onclick="getSilentRptsRnSts(<?php echo $rptID5; ?>, -1, '<?php echo $paramStr5; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Pay Run Results Slip">
                                                <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:12px; width:auto; position: relative; vertical-align: middle;">&nbsp;&nbsp;Results Slip&nbsp;&nbsp;&nbsp;
                                            </button>
                                            <button type="button" class="btn btn-default" style="" onclick="printPayPOSRcpt(<?php echo $sbmtdMspyID; ?>);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="POS Receipt">
                                                <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Receipt
                                            </button>
                                        </div>
                                    </div>
                                <?php } ?>
                            </fieldset>
                        </div>
                        <div class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;min-height:95px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="myPayRnDate" class="control-label col-lg-5">Date:</label>
                                    <div class="col-lg-7">
                                        <span><?php echo $row1[6]; ?></span>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="myPayRnDesc" class="control-label col-lg-5">Batch Status:</label>
                                    <div class="col-lg-7">
                                        <span><?php echo $row1[5]; ?></span>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="myPayRnNetAmnt" class="control-label col-lg-5" style="color:blue;">Net Amount (GHS):</label>
                                    <div class="col-lg-7">
                                        <span id="myPyRnTotal1"><?php echo "0.00"; ?></span>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                            <fieldset class="basic_person_fs">
                                <?php
                                $hideTable = "";
                                if ($vPsblVal1 == "NARHBT_COLLEGE_APP_1" && !($sbmtdMspyID <= 0 && $sbmtdPyReqID <= 0) && !$canVwQckPay) {
                                    $hideTable = "hideNotice";
                                }
                                ?>
                                <table class="table table-striped table-bordered table-responsive <?php echo  $hideTable ?>" id="myPayRnLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Item Name</th>
                                            <th>Classification</th>
                                            <th style="text-align: right;">Amount (GHS)</th>
                                            <th>Effective Date</th>
                                            <th>Line Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result2 = null;
                                        if ($sbmtdMspyID <= 0 && $sbmtdPyReqID <= 0) {
                                            $result2 = get_CumltiveBals($sbmtdPrsnSetMmbrID);
                                        } else {
                                            $result2 = get_MyPyRnsDt($sbmtdPyReqID, $sbmtdMspyID, $sbmtdPrsnSetMmbrID);
                                        }
                                        $cntr = 0;
                                        $brghtTotal = 0;
                                        $prpsdTtlSpnColor = "black";
                                        $itmTypCnt = getBatchItmTypCnt($sbmtdPyReqID, $sbmtdMspyID, $sbmtdPrsnSetMmbrID);
                                        while ($row2 = loc_db_fetch_array($result2)) {
                                            $cntr += 1;
                                            $dsplyAmount = getBatchNetAmnt($itmTypCnt, $row2[11], $row2[6], $row2[10], $row2[7], $brghtTotal, $prpsdTtlSpnColor);
                                            //getBatchNetAmnt($itmTypCnt, $row2[11], $row2[6], $row2[10], $row2[7], $brghtTotal, $prpsdTtlSpnColor)
                                            //getBatchNetAmnt($itmTypCnt, $row2[11], $row2[6], $row2[10], $row2[7], $brghtTotal, $prpsdTtlSpnColor);
                                            if ($vPsblVal1 == "NARHBT_COLLEGE_APP_1" && $row1[2] != "CUMULATIVE BALANCES" && !$canVwQckPay) {
                                                continue;
                                            }
                                        ?>
                                            <tr id="myPayRnLinesRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row2[6]; ?></td>
                                                <td class="lovtd"><?php echo $row2[11]; ?></td>
                                                <td class="lovtd" style="text-align: right;"><?php echo $dsplyAmount; ?></td>
                                                <td class="lovtd"><span><?php echo $row2[8]; ?></span></td>
                                                <td class="lovtd"><span><?php echo $row2[9]; ?></span></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th style="text-align: right;">Net Amount (<?php echo $fnccurnm; ?>):</th>
                                            <th style="text-align: right;"><?php echo "<span style=\"color:$prpsdTtlSpnColor;\">" . number_format($brghtTotal, 2, '.', ',') . "</span>"; ?></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div><input type="hidden" id="myPyRnTotal2" value="<?php echo urlencode("<span style=\"color:$prpsdTtlSpnColor;font-weight:bold;font-size:12px;\">" . number_format($brghtTotal, 2, '.', ',') . "</span>"); ?>"></div>
                            </fieldset>
                        </div>
                    </div>
                <?php
                }
                ?>
            <?php
            } else if ($vwtyp == 2) {
                //My Receivable Invoices
                $sbmtdRcvblInvcID = isset($_POST['sbmtdRcvblInvcID']) ? $_POST['sbmtdRcvblInvcID'] : -1;
                $sbmtdSalesInvcID = isset($_POST['sbmtdSalesInvcID']) ? $_POST['sbmtdSalesInvcID'] : -1;
                echo $cntent . "<li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Customer Invoices</span>
				</li>
                               </ul>
                              </div>";
                $shwUnpstdOnly = FALSE;
                $total = get_RcvblsDocHdrTtl($srchFor, $srchIn, $orgID, $shwUnpstdOnly, $cstmrID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_RcvblsDocHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $shwUnpstdOnly, $cstmrID);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-5";
                $colClassType3 = "col-lg-5";
            ?>
                <form id='myRcvblInvcsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="myRcvblInvcsSrchFor" type="text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncMyRcvblInvcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="myRcvblInvcsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getMyRcvblInvcs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getMyRcvblInvcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="myRcvblInvcsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                    $srchInsArrys = array(
                                        "Document Number", "Document Description",
                                        "Document Classification", "Customer Name", "Customer's Doc. Number",
                                        "Source Doc Number", "Currency", "Approval Status"
                                    );

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                    ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="myRcvblInvcsDsplySze" style="min-width:70px !important;">
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
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getMyRcvblInvcs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getMyRcvblInvcs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row" style="padding:0px 15px 0px 15px !important">
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">
                                <table class="table table-striped table-bordered table-responsive" id="myRcvblInvcsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Document Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($sbmtdRcvblInvcID <= 0 && $cntr <= 0) {
                                                $sbmtdRcvblInvcID = $row[0];
                                                if ($row[6] == "Sales Invoice") {
                                                    $sbmtdSalesInvcID = $row[5];
                                                } else {
                                                    $sbmtdSalesInvcID = 1;
                                                }
                                            }
                                            $cntr += 1;
                                            $spnColor4 = "";
                                            if ((float) $row[3] <= 0 && $row[4] == "Approved") {
                                                $spnColor4 = "lime";
                                            } else if ($row[4] == "Approved") {
                                                $spnColor4 = "#FF9191";
                                            } else if ($row[4] == "Cancelled") {
                                                $spnColor4 = "#ABABAB";
                                            } else if ($row[4] == "Not Validated") {
                                                $spnColor4 = "#e5a110";
                                            } else {
                                                $spnColor4 = "#44d6d6";
                                            }
                                        ?>
                                            <tr id="myRcvblInvcsRow_<?php echo $cntr; ?>" class="hand_cursor" style="background-color:<?php echo $spnColor4; ?>">
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo str_pad($row[0], 7, "0", STR_PAD_LEFT); ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="myRcvblInvcsRow<?php echo $cntr; ?>_RcvblID" value="<?php echo $row[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="myRcvblInvcsRow<?php echo $cntr; ?>_SalesID" value="<?php echo ($row[6] == "Sales Invoice" ? $row[5] : -1); ?>">
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                        <div class="col-md-10" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                <div class="container-fluid" id="myRcvblInvcsDetailInfo">
                                    <?php
                                    if ($sbmtdRcvblInvcID > 0) {
                                        $result1 = get_One_RcvblsDocHdr($sbmtdRcvblInvcID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $curr = $row1[25];
                                            $salesDocTyp = $row1[8];
                                            $rcvblDocTyp = $row1[5];
                                    ?>
                                            <div class="row">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                    <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcDocTyp" class="control-label col-lg-4 formtd">Doc. Type:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[5]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcNum" class="control-label col-lg-4 formtd">Number:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[4] . " (" . $row1[0] . ")"; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcDate" class="control-label col-lg-4 formtd">Date:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[1]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcCstmr" class="control-label col-lg-4 formtd">Customer:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[10]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcCstmrSite" class="control-label col-lg-4 formtd">Site:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[12]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcLnkdEvnt" class="control-label col-lg-4 formtd">Linked Event:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo ($row1[31]); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcEvntCtgry" class="control-label col-lg-4 formtd">Category:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[28]; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcCstmrDoc" class="control-label col-lg-5 formtd">Client's Doc. No.:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[22]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcPayMthd" class="control-label col-lg-5 formtd">Pay Method:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[18]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcPayTrms" class="control-label col-lg-5 formtd">Pay Terms:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[16]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcSrcDocTyp" class="control-label col-lg-5 formtd">Source Doc. Type:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[8]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcSrcDocNum" class="control-label col-lg-5 formtd">Source Doc. No.:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[26]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcGLBatch" class="control-label col-lg-5 formtd">GL Batch:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[21]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcCrtdBy" class="control-label col-lg-5 formtd">Created By:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[3]; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcTtl" class="control-label col-lg-7 formtd">Invoice Total:</label>
                                                            <div class="col-lg-5 formtd">
                                                                <span><?php echo number_format($row1[15], 2, '.', ','); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcAmntPaid" class="control-label col-lg-7 formtd" style="color:blue;">Amount Paid:</label>
                                                            <div class="col-lg-5 formtd">
                                                                <span><?php echo number_format($row1[19], 2, '.', ','); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcNetAmnt" class="control-label col-lg-7 formtd" style="color:blue;">Outstanding Amount:</label>
                                                            <div class="col-lg-5 formtd">
                                                                <?php
                                                                $netAmnt = (float) $row1[15] - (float) $row1[19];
                                                                $spnColor = (round($netAmnt, 2) <= 0) ? "lime" : "#FF9191";
                                                                ?>
                                                                <span style="padding:5px;background-color:<?php echo $spnColor; ?>;"><?php echo number_format($netAmnt, 2, '.', ','); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcAvlblPrpyAmnt" class="control-label col-lg-7 formtd" style="color:blue;">Un-Applied Amount:</label>
                                                            <div class="col-lg-5 formtd">
                                                                <?php
                                                                $netAmnt1 = 0;
                                                                $spnColor1 = "";
                                                                if ($row1[5] == "Customer Advance Payment") {
                                                                    $netAmnt1 = (float) $row1[19] - (float) $row1[30];
                                                                    $spnColor1 = (round($netAmnt1, 2) <= 0) ? "lime" : "#FF9191";
                                                                }
                                                                ?>
                                                                <span style="padding:5px;background-color:<?php echo $spnColor1; ?>;"><?php echo number_format(0, 2, '.', ','); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcCurr" class="control-label col-lg-7 formtd">Currency:</label>
                                                            <div class="col-lg-5 formtd">
                                                                <span><?php echo $row1[25]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcClsfctn" class="control-label col-lg-4 formtd">Doc. Classification:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[23]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myRcvblInvcStatus" class="control-label col-lg-7 formtd">Doc. Status:</label>
                                                            <div class="col-lg-5 formtd">
                                                                <?php
                                                                $spnColor2 = "";
                                                                if ($row1[13] == "Approved") {
                                                                    $spnColor2 = "lime";
                                                                } else if ($row1[13] == "Cancelled") {
                                                                    $spnColor2 = "#FF9191";
                                                                } else if ($row1[13] == "Not Validated") {
                                                                    $spnColor2 = "#e5a110";
                                                                } else {
                                                                    $spnColor2 = "#44d6d6";
                                                                }
                                                                ?>
                                                                <span style="padding:5px;font-weight:bold;background-color:<?php echo $spnColor2; ?>;"><?php echo $row1[13]; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="row">
                                                <fieldset class="basic_person_fs" style="padding:2px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 5px 0px 5px !important;">
                                                        <label for="myRcvblInvcDesc" class="control-label col-lg-2 formtd">Description:</label>
                                                        <div class="col-lg-10 formtd">
                                                            <span><?php echo $row1[6]; ?></span>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-9" style="padding:0px 2px 0px 0px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="myRcvblInvcLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <?php if ($sbmtdSalesInvcID <= 0) { ?>
                                                                    <th>Item Type</th>
                                                                <?php } ?>
                                                                <th>Item Description</th>
                                                                <?php if ($sbmtdSalesInvcID > 0) { ?>
                                                                    <th style="text-align: right;">Qty</th>
                                                                    <th>UOM</th>
                                                                    <th style="text-align: right;">Unit Price (<?php echo $curr; ?>)</th>
                                                                <?php } ?>
                                                                <th style="text-align: right;">Amount (<?php echo $curr; ?>)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $result2 = NULL;
                                                            if ($sbmtdSalesInvcID > 0) {
                                                                $result2 = get_One_SalesDcLines($sbmtdSalesInvcID);
                                                            } else if ($sbmtdRcvblInvcID > 0) {
                                                                $result2 = get_RcvblsDocLines($sbmtdRcvblInvcID);
                                                            }
                                                            $cntr = 0;
                                                            $curIdx = 0;
                                                            $brghtTotal = 0;
                                                            if ($result2 !== NULL) {
                                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                                    $cntr += 1;
                                                                    $itemType = "";
                                                                    $itemDesc = "";
                                                                    $itmQty = 0;
                                                                    $itmUom = 0;
                                                                    $itmUntPrc = 0;
                                                                    $itmAmount = 0;
                                                                    $lnkdPrsnID = 0;
                                                                    $lnkdPrsnNm = "";
                                                                    if ($sbmtdSalesInvcID > 0) {
                                                                        $itemType = "";
                                                                        $itemDesc = $row2[25];
                                                                        $lnkdPrsnID = (float) $row2[23];
                                                                        if ($lnkdPrsnID > 0) {
                                                                            $lnkdPrsnNm = " for " . $row2[24];
                                                                        }
                                                                        $itmQty = number_format($row2[2], 2, '.', ',');
                                                                        $itmUom = $row2[18];
                                                                        $itmUntPrc = number_format($row2[3], 2, '.', ',');
                                                                        $itmAmount = number_format($row2[4], 2, '.', ',');
                                                                    } else if ($sbmtdRcvblInvcID > 0) {
                                                                        $itemType = $row2[1];
                                                                        $itemDesc = $row2[2];
                                                                        $itmQty = 0;
                                                                        $itmUom = 0;
                                                                        $itmUntPrc = 0;
                                                                        $itmAmount = number_format($row2[3], 2, '.', ',');
                                                                    }
                                                            ?>
                                                                    <tr id="myRcvblInvcLinesRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <?php if ($sbmtdSalesInvcID <= 0) { ?>
                                                                            <td class="lovtd"><?php echo $itemType; ?></td>
                                                                        <?php } ?>
                                                                        <td class="lovtd"><span><?php echo $itemDesc . $lnkdPrsnNm; ?></span></td>
                                                                        <?php if ($sbmtdSalesInvcID > 0) { ?>
                                                                            <td class="lovtd" style="text-align: right;"><?php echo $itmQty; ?></td>
                                                                            <td class="lovtd"><span><?php echo $itmUom; ?></span></td>
                                                                            <td class="lovtd" style="text-align: right;"><?php echo $itmUntPrc; ?></td>
                                                                        <?php } ?>
                                                                        <td class="lovtd" style="text-align: right;"><span><?php echo $itmAmount; ?></span></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-3" style="padding:0px 0px 0px 2px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="myRcvblInvcSmryTable" cellspacing="0" width="100%" style="width:100%;min-width: 100px;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Summary Item</th>
                                                                <th style="text-align: right;">Amount (<?php echo $curr; ?>)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $result3 = NULL;
                                                            if ($sbmtdSalesInvcID > 0) {
                                                                $result3 = get_SalesDocSmryLns($sbmtdSalesInvcID, $salesDocTyp);
                                                            } else if ($sbmtdRcvblInvcID > 0) {
                                                                $result3 = get_RcvblsDocSmryLns($sbmtdRcvblInvcID, $rcvblDocTyp);
                                                            }
                                                            $cntr = 0;
                                                            $curIdx = 0;
                                                            if ($result3 !== NULL) {
                                                                while ($row3 = loc_db_fetch_array($result3)) {
                                                                    $cntr += 1;
                                                                    $itemDesc = $row3[1];
                                                                    $itmAmount = number_format($row3[2], 2, '.', ',');
                                                                    $spnColor3 = "";
                                                                    if ($itemDesc == "Outstanding Balance" && (float) $row3[2] <= 0) {
                                                                        $spnColor3 = "background-color:lime;";
                                                                    } else if ($itemDesc == "Outstanding Balance") {
                                                                        $spnColor3 = "background-color:#FF9191;";
                                                                    }
                                                            ?>
                                                                    <tr id="myRcvblInvcLinesRow_<?php echo $cntr; ?>" class="hand_cursor" style="<?php echo $spnColor3; ?>">
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <td class="lovtd">
                                                                            <span><?php echo $itemDesc; ?></span>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: right;"><span><?php echo $itmAmount; ?></span></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <span>No Results Found</span>
                                    <?php }
                                    ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 3) {
                //My Receivable Invoices Detail
                $sbmtdRcvblInvcID = isset($_POST['sbmtdRcvblInvcID']) ? $_POST['sbmtdRcvblInvcID'] : -1;
                $sbmtdSalesInvcID = isset($_POST['sbmtdSalesInvcID']) ? $_POST['sbmtdSalesInvcID'] : -1;

                if ($sbmtdRcvblInvcID > 0) {
                    $result1 = get_One_RcvblsDocHdr($sbmtdRcvblInvcID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $curr = $row1[25];
                        $salesDocTyp = $row1[8];
                        $rcvblDocTyp = $row1[5];
                ?>
                        <div class="row">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcDocTyp" class="control-label col-lg-4 formtd">Doc. Type:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[5]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcNum" class="control-label col-lg-4 formtd">Number:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[4] . " (" . $row1[0] . ")"; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcDate" class="control-label col-lg-4 formtd">Date:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[1]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcCstmr" class="control-label col-lg-4 formtd">Customer:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[10]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcCstmrSite" class="control-label col-lg-4 formtd">Site:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[12]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcLnkdEvnt" class="control-label col-lg-4 formtd">Linked Event:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo ($row1[31]); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcEvntCtgry" class="control-label col-lg-4 formtd">Category:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[28]; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcCstmrDoc" class="control-label col-lg-5 formtd">Client's Doc. No.:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[22]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcPayMthd" class="control-label col-lg-5 formtd">Pay Method:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[18]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcPayTrms" class="control-label col-lg-5 formtd">Pay Terms:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[16]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcSrcDocTyp" class="control-label col-lg-5 formtd">Source Doc. Type:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[8]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcSrcDocNum" class="control-label col-lg-5 formtd">Source Doc. No.:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[26]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcGLBatch" class="control-label col-lg-5 formtd">GL Batch:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[21]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcCrtdBy" class="control-label col-lg-5 formtd">Created By:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[3]; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcTtl" class="control-label col-lg-7 formtd">Invoice Total:</label>
                                        <div class="col-lg-5 formtd">
                                            <span><?php echo number_format($row1[15], 2, '.', ','); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcAmntPaid" class="control-label col-lg-7 formtd" style="color:blue;">Amount Paid:</label>
                                        <div class="col-lg-5 formtd">
                                            <span><?php echo number_format($row1[19], 2, '.', ','); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcNetAmnt" class="control-label col-lg-7 formtd" style="color:blue;">Outstanding Amount:</label>
                                        <div class="col-lg-5 formtd">
                                            <?php
                                            $netAmnt = (float) $row1[15] - (float) $row1[19];
                                            $spnColor = (round($netAmnt, 2) <= 0) ? "lime" : "#FF9191";
                                            ?>
                                            <span style="padding:5px;background-color:<?php echo $spnColor; ?>;"><?php echo number_format($netAmnt, 2, '.', ','); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcAvlblPrpyAmnt" class="control-label col-lg-7 formtd" style="color:blue;">Un-Applied Amount:</label>
                                        <div class="col-lg-5 formtd">
                                            <?php
                                            $netAmnt1 = 0;
                                            $spnColor1 = "";
                                            if ($row1[5] == "Customer Advance Payment") {
                                                $netAmnt1 = (float) $row1[19] - (float) $row1[30];
                                                $spnColor1 = (round($netAmnt1, 2) <= 0) ? "lime" : "#FF9191";
                                            }
                                            ?>
                                            <span style="padding:5px;background-color:<?php echo $spnColor1; ?>;"><?php echo number_format(0, 2, '.', ','); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcCurr" class="control-label col-lg-7 formtd">Currency:</label>
                                        <div class="col-lg-5 formtd">
                                            <span><?php echo $row1[25]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcClsfctn" class="control-label col-lg-4 formtd">Doc. Classification:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[23]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myRcvblInvcStatus" class="control-label col-lg-7 formtd">Doc. Status:</label>
                                        <div class="col-lg-5 formtd">
                                            <?php
                                            $spnColor2 = "";
                                            if ($row1[13] == "Approved") {
                                                $spnColor2 = "lime";
                                            } else if ($row1[13] == "Cancelled") {
                                                $spnColor2 = "#FF9191";
                                            } else if ($row1[13] == "Not Validated") {
                                                $spnColor2 = "#e5a110";
                                            } else {
                                                $spnColor2 = "#44d6d6";
                                            }
                                            ?>
                                            <span style="padding:5px;font-weight:bold;background-color:<?php echo $spnColor2; ?>;"><?php echo $row1[13]; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row">
                            <fieldset class="basic_person_fs" style="padding:2px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 5px 0px 5px !important;">
                                    <label for="myRcvblInvcDesc" class="control-label col-lg-2 formtd">Description:</label>
                                    <div class="col-lg-10 formtd">
                                        <span><?php echo $row1[6]; ?></span>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row">
                            <div class="col-md-9" style="padding:0px 2px 0px 0px !important;">
                                <table class="table table-striped table-bordered table-responsive" id="myRcvblInvcLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($sbmtdSalesInvcID <= 0) { ?>
                                                <th>Item Type</th>
                                            <?php } ?>
                                            <th>Item Description</th>
                                            <?php if ($sbmtdSalesInvcID > 0) { ?>
                                                <th style="text-align: right;">Qty</th>
                                                <th>UOM</th>
                                                <th style="text-align: right;">Unit Price (<?php echo $curr; ?>)</th>
                                            <?php } ?>
                                            <th style="text-align: right;">Amount (<?php echo $curr; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result2 = NULL;
                                        if ($sbmtdSalesInvcID > 0) {
                                            $result2 = get_One_SalesDcLines($sbmtdSalesInvcID);
                                        } else if ($sbmtdRcvblInvcID > 0) {
                                            $result2 = get_RcvblsDocLines($sbmtdRcvblInvcID);
                                        }
                                        $cntr = 0;
                                        $curIdx = 0;
                                        $brghtTotal = 0;
                                        if ($result2 !== NULL) {
                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                $cntr += 1;
                                                $itemType = "";
                                                $itemDesc = "";
                                                $itmQty = 0;
                                                $itmUom = 0;
                                                $itmUntPrc = 0;
                                                $itmAmount = 0;
                                                $lnkdPrsnID = 0;
                                                $lnkdPrsnNm = "";
                                                if ($sbmtdSalesInvcID > 0) {
                                                    $itemType = "";
                                                    $itemDesc = $row2[25];
                                                    $lnkdPrsnID = (float) $row2[23];
                                                    if ($lnkdPrsnID > 0) {
                                                        $lnkdPrsnNm = " for " . $row2[24];
                                                    }
                                                    $itmQty = number_format($row2[2], 2, '.', ',');
                                                    $itmUom = $row2[18];
                                                    $itmUntPrc = number_format($row2[3], 2, '.', ',');
                                                    $itmAmount = number_format($row2[4], 2, '.', ',');
                                                } else if ($sbmtdRcvblInvcID > 0) {
                                                    $itemType = $row2[1];
                                                    $itemDesc = $row2[2];
                                                    $itmQty = 0;
                                                    $itmUom = 0;
                                                    $itmUntPrc = 0;
                                                    $itmAmount = number_format($row2[3], 2, '.', ',');
                                                }
                                        ?>
                                                <tr id="myRcvblInvcLinesRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <?php if ($sbmtdSalesInvcID <= 0) { ?>
                                                        <td class="lovtd"><?php echo $itemType; ?></td>
                                                    <?php } ?>
                                                    <td class="lovtd"><span><?php echo $itemDesc . $lnkdPrsnNm; ?></span></td>
                                                    <?php if ($sbmtdSalesInvcID > 0) { ?>
                                                        <td class="lovtd" style="text-align: right;"><?php echo $itmQty; ?></td>
                                                        <td class="lovtd"><span><?php echo $itmUom; ?></span></td>
                                                        <td class="lovtd" style="text-align: right;"><?php echo $itmUntPrc; ?></td>
                                                    <?php } ?>
                                                    <td class="lovtd" style="text-align: right;"><span><?php echo $itmAmount; ?></span></td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-3" style="padding:0px 0px 0px 2px !important;">
                                <table class="table table-striped table-bordered table-responsive" id="myRcvblInvcSmryTable" cellspacing="0" width="100%" style="width:100%;min-width: 100px;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Summary Item</th>
                                            <th style="text-align: right;">Amount (<?php echo $curr; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result3 = NULL;
                                        if ($sbmtdSalesInvcID > 0) {
                                            $result3 = get_SalesDocSmryLns($sbmtdSalesInvcID, $salesDocTyp);
                                        } else if ($sbmtdRcvblInvcID > 0) {
                                            $result3 = get_RcvblsDocSmryLns($sbmtdRcvblInvcID, $rcvblDocTyp);
                                        }
                                        $cntr = 0;
                                        $curIdx = 0;
                                        if ($result3 !== NULL) {
                                            while ($row3 = loc_db_fetch_array($result3)) {
                                                $cntr += 1;
                                                $itemDesc = $row3[1];
                                                $itmAmount = number_format($row3[2], 2, '.', ',');
                                                $spnColor3 = "";
                                                if ($itemDesc == "Outstanding Balance" && (float) $row3[2] <= 0) {
                                                    $spnColor3 = "background-color:lime;";
                                                } else if ($itemDesc == "Outstanding Balance") {
                                                    $spnColor3 = "background-color:#FF9191;";
                                                }
                                        ?>
                                                <tr id="myRcvblInvcLinesRow_<?php echo $cntr; ?>" class="hand_cursor" style="<?php echo $spnColor3; ?>">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <span><?php echo $itemDesc; ?></span>
                                                    </td>
                                                    <td class="lovtd" style="text-align: right;"><span><?php echo $itmAmount; ?></span></td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <span>No Results Found</span>
                <?php
                }
            } else if ($vwtyp == 4) {
                //My Payable Invoices
                $sbmtdPyblInvcID = isset($_POST['sbmtdPyblInvcID']) ? $_POST['sbmtdPyblInvcID'] : -1;
                echo $cntent . "<li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Supplier Invoices</span>
				</li>
                               </ul>
                              </div>";
                $shwUnpstdOnly = FALSE;
                $total = get_PyblsDocHdrTtl($srchFor, $srchIn, $orgID, $shwUnpstdOnly, $cstmrID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_PyblsDocHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $shwUnpstdOnly, $cstmrID);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-5";
                $colClassType3 = "col-lg-5";
                ?>
                <form id='myPyblInvcsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="myPyblInvcsSrchFor" type="text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncMyPyblInvcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="myPyblInvcsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getMyPyblInvcs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getMyPyblInvcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="myPyblInvcsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                    $srchInsArrys = array(
                                        "Document Number", "Document Description",
                                        "Document Classification", "Supplier Name", "Supplier's Invoice Number",
                                        "Source Doc Number", "Currency", "Approval Status"
                                    );

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                    ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="myPyblInvcsDsplySze" style="min-width:70px !important;">
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
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getMyPyblInvcs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getMyPyblInvcs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row" style="padding:0px 15px 0px 15px !important">
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">
                                <table class="table table-striped table-bordered table-responsive" id="myPyblInvcsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Document Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($sbmtdPyblInvcID <= 0 && $cntr <= 0) {
                                                $sbmtdPyblInvcID = $row[0];
                                            }
                                            $cntr += 1;
                                            $spnColor4 = "";
                                            if ((float) $row[3] <= 0 && $row[4] == "Approved") {
                                                $spnColor4 = "lime";
                                            } else if ($row[4] == "Approved") {
                                                $spnColor4 = "#FF9191";
                                            } else if ($row[4] == "Cancelled") {
                                                $spnColor4 = "#ABABAB";
                                            } else if ($row[4] == "Not Validated") {
                                                $spnColor4 = "#e5a110";
                                            } else {
                                                $spnColor4 = "#44d6d6";
                                            }
                                        ?>
                                            <tr id="myPyblInvcsRow_<?php echo $cntr; ?>" class="hand_cursor" style="background-color:<?php echo $spnColor4; ?>">
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo str_pad($row[0], 7, "0", STR_PAD_LEFT); ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="myPyblInvcsRow<?php echo $cntr; ?>_PyblID" value="<?php echo $row[0]; ?>">
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                        <div class="col-md-10" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                <div class="container-fluid" id="myPyblInvcsDetailInfo">
                                    <?php
                                    if ($sbmtdPyblInvcID > 0) {
                                        $result1 = get_One_PyblsDocHdr($sbmtdPyblInvcID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $curr = $row1[25];
                                            $pyblDocTyp = $row1[5];
                                    ?>
                                            <div class="row">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                    <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcDocTyp" class="control-label col-lg-4 formtd">Doc. Type:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[5]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcNum" class="control-label col-lg-4 formtd">Number:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[4] . " (" . $row1[0] . ")"; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcDate" class="control-label col-lg-4 formtd">Date:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[1]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcCstmr" class="control-label col-lg-4 formtd">Supplier:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[10]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcCstmrSite" class="control-label col-lg-4 formtd">Site:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[12]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcLnkdEvnt" class="control-label col-lg-4 formtd">Linked Event:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo ($row1[31]); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcEvntCtgry" class="control-label col-lg-4 formtd">Category:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[28]; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcCstmrDoc" class="control-label col-lg-5 formtd">Supplier's Doc. No.:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[22]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcPayMthd" class="control-label col-lg-5 formtd">Pay Method:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[18]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcPayTrms" class="control-label col-lg-5 formtd">Pay Terms:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[16]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcSrcDocTyp" class="control-label col-lg-5 formtd">Source Doc. Type:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[8]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcSrcDocNum" class="control-label col-lg-5 formtd">Source Doc. No.:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[26]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcGLBatch" class="control-label col-lg-5 formtd">GL Batch:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[21]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcCrtdBy" class="control-label col-lg-5 formtd">Created By:</label>
                                                            <div class="col-lg-7 formtd">
                                                                <span><?php echo $row1[3]; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcTtl" class="control-label col-lg-7 formtd">Invoice Total:</label>
                                                            <div class="col-lg-5 formtd">
                                                                <span><?php echo number_format($row1[15], 2, '.', ','); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcAmntPaid" class="control-label col-lg-7 formtd" style="color:blue;">Amount Paid:</label>
                                                            <div class="col-lg-5 formtd">
                                                                <span><?php echo number_format($row1[19], 2, '.', ','); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcNetAmnt" class="control-label col-lg-7 formtd" style="color:blue;">Outstanding Amount:</label>
                                                            <div class="col-lg-5 formtd">
                                                                <?php
                                                                $netAmnt = (float) $row1[15] - (float) $row1[19];
                                                                $spnColor = (round($netAmnt, 2) <= 0) ? "lime" : "#FF9191";
                                                                ?>
                                                                <span style="padding:5px;background-color:<?php echo $spnColor; ?>;"><?php echo number_format($netAmnt, 2, '.', ','); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcAvlblPrpyAmnt" class="control-label col-lg-7 formtd" style="color:blue;">Un-Applied Amount:</label>
                                                            <div class="col-lg-5 formtd">
                                                                <?php
                                                                $netAmnt1 = 0;
                                                                $spnColor1 = "";
                                                                if ($row1[5] == "Supplier Advance Payment") {
                                                                    $netAmnt1 = (float) $row1[19] - (float) $row1[30];
                                                                    $spnColor1 = (round($netAmnt1, 2) <= 0) ? "lime" : "#FF9191";
                                                                }
                                                                ?>
                                                                <span style="padding:5px;background-color:<?php echo $spnColor1; ?>;"><?php echo number_format(0, 2, '.', ','); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcCurr" class="control-label col-lg-7 formtd">Currency:</label>
                                                            <div class="col-lg-5 formtd">
                                                                <span><?php echo $row1[25]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcClsfctn" class="control-label col-lg-4 formtd">Doc. Classification:</label>
                                                            <div class="col-lg-8 formtd">
                                                                <span><?php echo $row1[23]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="myPyblInvcStatus" class="control-label col-lg-7 formtd">Doc. Status:</label>
                                                            <div class="col-lg-5 formtd">
                                                                <?php
                                                                $spnColor2 = "";
                                                                if ($row1[13] == "Approved") {
                                                                    $spnColor2 = "lime";
                                                                } else if ($row1[13] == "Cancelled") {
                                                                    $spnColor2 = "#FF9191";
                                                                } else if ($row1[13] == "Not Validated") {
                                                                    $spnColor2 = "#e5a110";
                                                                } else {
                                                                    $spnColor2 = "#44d6d6";
                                                                }
                                                                ?>
                                                                <span style="padding:5px;font-weight:bold;background-color:<?php echo $spnColor2; ?>;"><?php echo $row1[13]; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="row">
                                                <fieldset class="basic_person_fs" style="padding:2px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 5px 0px 5px !important;">
                                                        <label for="myPyblInvcDesc" class="control-label col-lg-2 formtd">Description:</label>
                                                        <div class="col-lg-10 formtd">
                                                            <span><?php echo $row1[6]; ?></span>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-9" style="padding:0px 2px 0px 0px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="myPyblInvcLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Item Type</th>
                                                                <th>Item Description</th>
                                                                <th style="text-align: right;">Amount (<?php echo $curr; ?>)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $result2 = get_PyblsDocLines($sbmtdPyblInvcID);
                                                            $cntr = 0;
                                                            $curIdx = 0;
                                                            $brghtTotal = 0;
                                                            if ($result2 !== NULL) {
                                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                                    $cntr += 1;
                                                                    $itemType = $row2[1];
                                                                    $itemDesc = $row2[2];
                                                                    $itmQty = 0;
                                                                    $itmUom = 0;
                                                                    $itmUntPrc = 0;
                                                                    $itmAmount = number_format($row2[3], 2, '.', ',');
                                                            ?>
                                                                    <tr id="myPyblInvcLinesRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <td class="lovtd"><?php echo $itemType; ?></td>
                                                                        <td class="lovtd"><span><?php echo $itemDesc; ?></span></td>
                                                                        <td class="lovtd" style="text-align: right;"><span><?php echo $itmAmount; ?></span></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-3" style="padding:0px 0px 0px 2px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="myPyblInvcSmryTable" cellspacing="0" width="100%" style="width:100%;min-width: 100px;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Summary Item</th>
                                                                <th style="text-align: right;">Amount (<?php echo $curr; ?>)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $result3 = get_PyblsDocSmryLns($sbmtdPyblInvcID, $pyblDocTyp);
                                                            $cntr = 0;
                                                            $curIdx = 0;
                                                            if ($result3 !== NULL) {
                                                                while ($row3 = loc_db_fetch_array($result3)) {
                                                                    $cntr += 1;
                                                                    $itemDesc = $row3[1];
                                                                    $itmAmount = number_format($row3[2], 2, '.', ',');
                                                                    $spnColor3 = "";
                                                                    if ($itemDesc == "Outstanding Balance" && (float) $row3[2] <= 0) {
                                                                        $spnColor3 = "background-color:lime;";
                                                                    } else if ($itemDesc == "Outstanding Balance") {
                                                                        $spnColor3 = "background-color:#FF9191;";
                                                                    }
                                                            ?>
                                                                    <tr id="myPyblInvcLinesRow_<?php echo $cntr; ?>" class="hand_cursor" style="<?php echo $spnColor3; ?>">
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <td class="lovtd">
                                                                            <span><?php echo $itemDesc; ?></span>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: right;"><span><?php echo $itmAmount; ?></span></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <span>No Results Found</span>
                                    <?php }
                                    ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 5) {
                //My Payable Invoices Detail
                $sbmtdPyblInvcID = isset($_POST['sbmtdPyblInvcID']) ? $_POST['sbmtdPyblInvcID'] : -1;

                if ($sbmtdPyblInvcID > 0) {
                    $result1 = get_One_PyblsDocHdr($sbmtdPyblInvcID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $curr = $row1[25];
                        $pyblDocTyp = $row1[5];
                ?>
                        <div class="row">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcDocTyp" class="control-label col-lg-4 formtd">Doc. Type:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[5]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcNum" class="control-label col-lg-4 formtd">Number:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[4] . " (" . $row1[0] . ")"; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcDate" class="control-label col-lg-4 formtd">Date:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[1]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcCstmr" class="control-label col-lg-4 formtd">Supplier:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[10]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcCstmrSite" class="control-label col-lg-4 formtd">Site:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[12]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcLnkdEvnt" class="control-label col-lg-4 formtd">Linked Event:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo ($row1[31]); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcEvntCtgry" class="control-label col-lg-4 formtd">Category:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[28]; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcCstmrDoc" class="control-label col-lg-5 formtd">Supplier's Doc. No.:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[22]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcPayMthd" class="control-label col-lg-5 formtd">Pay Method:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[18]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcPayTrms" class="control-label col-lg-5 formtd">Pay Terms:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[16]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcSrcDocTyp" class="control-label col-lg-5 formtd">Source Doc. Type:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[8]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcSrcDocNum" class="control-label col-lg-5 formtd">Source Doc. No.:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[26]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcGLBatch" class="control-label col-lg-5 formtd">GL Batch:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[21]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcCrtdBy" class="control-label col-lg-5 formtd">Created By:</label>
                                        <div class="col-lg-7 formtd">
                                            <span><?php echo $row1[3]; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcTtl" class="control-label col-lg-7 formtd">Invoice Total:</label>
                                        <div class="col-lg-5 formtd">
                                            <span><?php echo number_format($row1[15], 2, '.', ','); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcAmntPaid" class="control-label col-lg-7 formtd" style="color:blue;">Amount Paid:</label>
                                        <div class="col-lg-5 formtd">
                                            <span><?php echo number_format($row1[19], 2, '.', ','); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcNetAmnt" class="control-label col-lg-7 formtd" style="color:blue;">Outstanding Amount:</label>
                                        <div class="col-lg-5 formtd">
                                            <?php
                                            $netAmnt = (float) $row1[15] - (float) $row1[19];
                                            $spnColor = (round($netAmnt, 2) <= 0) ? "lime" : "#FF9191";
                                            ?>
                                            <span style="padding:5px;background-color:<?php echo $spnColor; ?>;"><?php echo number_format($netAmnt, 2, '.', ','); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcAvlblPrpyAmnt" class="control-label col-lg-7 formtd" style="color:blue;">Un-Applied Amount:</label>
                                        <div class="col-lg-5 formtd">
                                            <?php
                                            $netAmnt1 = 0;
                                            $spnColor1 = "";
                                            if ($row1[5] == "Supplier Advance Payment") {
                                                $netAmnt1 = (float) $row1[19] - (float) $row1[30];
                                                $spnColor1 = (round($netAmnt1, 2) <= 0) ? "lime" : "#FF9191";
                                            }
                                            ?>
                                            <span style="padding:5px;background-color:<?php echo $spnColor1; ?>;"><?php echo number_format(0, 2, '.', ','); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcCurr" class="control-label col-lg-7 formtd">Currency:</label>
                                        <div class="col-lg-5 formtd">
                                            <span><?php echo $row1[25]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcClsfctn" class="control-label col-lg-4 formtd">Doc. Classification:</label>
                                        <div class="col-lg-8 formtd">
                                            <span><?php echo $row1[23]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="myPyblInvcStatus" class="control-label col-lg-7 formtd">Doc. Status:</label>
                                        <div class="col-lg-5 formtd">
                                            <?php
                                            $spnColor2 = "";
                                            if ($row1[13] == "Approved") {
                                                $spnColor2 = "lime";
                                            } else if ($row1[13] == "Cancelled") {
                                                $spnColor2 = "#FF9191";
                                            } else if ($row1[13] == "Not Validated") {
                                                $spnColor2 = "#e5a110";
                                            } else {
                                                $spnColor2 = "#44d6d6";
                                            }
                                            ?>
                                            <span style="padding:5px;font-weight:bold;background-color:<?php echo $spnColor2; ?>;"><?php echo $row1[13]; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row">
                            <fieldset class="basic_person_fs" style="padding:2px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 5px 0px 5px !important;">
                                    <label for="myPyblInvcDesc" class="control-label col-lg-2 formtd">Description:</label>
                                    <div class="col-lg-10 formtd">
                                        <span><?php echo $row1[6]; ?></span>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row">
                            <div class="col-md-9" style="padding:0px 2px 0px 0px !important;">
                                <table class="table table-striped table-bordered table-responsive" id="myPyblInvcLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Item Type</th>
                                            <th>Item Description</th>
                                            <th style="text-align: right;">Amount (<?php echo $curr; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result2 = get_PyblsDocLines($sbmtdPyblInvcID);
                                        $cntr = 0;
                                        $curIdx = 0;
                                        $brghtTotal = 0;
                                        if ($result2 !== NULL) {
                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                $cntr += 1;
                                                $itemType = $row2[1];
                                                $itemDesc = $row2[2];
                                                $itmQty = 0;
                                                $itmUom = 0;
                                                $itmUntPrc = 0;
                                                $itmAmount = number_format($row2[3], 2, '.', ',');
                                        ?>
                                                <tr id="myPyblInvcLinesRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $itemType; ?></td>
                                                    <td class="lovtd"><span><?php echo $itemDesc; ?></span></td>
                                                    <td class="lovtd" style="text-align: right;"><span><?php echo $itmAmount; ?></span></td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-3" style="padding:0px 0px 0px 2px !important;">
                                <table class="table table-striped table-bordered table-responsive" id="myPyblInvcSmryTable" cellspacing="0" width="100%" style="width:100%;min-width: 100px;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Summary Item</th>
                                            <th style="text-align: right;">Amount (<?php echo $curr; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result3 = get_PyblsDocSmryLns($sbmtdPyblInvcID, $pyblDocTyp);
                                        $cntr = 0;
                                        $curIdx = 0;
                                        if ($result3 !== NULL) {
                                            while ($row3 = loc_db_fetch_array($result3)) {
                                                $cntr += 1;
                                                $itemDesc = $row3[1];
                                                $itmAmount = number_format($row3[2], 2, '.', ',');
                                                $spnColor3 = "";
                                                if ($itemDesc == "Outstanding Balance" && (float) $row3[2] <= 0) {
                                                    $spnColor3 = "background-color:lime;";
                                                } else if ($itemDesc == "Outstanding Balance") {
                                                    $spnColor3 = "background-color:#FF9191;";
                                                }
                                        ?>
                                                <tr id="myPyblInvcLinesRow_<?php echo $cntr; ?>" class="hand_cursor" style="<?php echo $spnColor3; ?>">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <span><?php echo $itemDesc; ?></span>
                                                    </td>
                                                    <td class="lovtd" style="text-align: right;"><span><?php echo $itmAmount; ?></span></td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <span>No Results Found</span>
<?php
                }
            } else if ($vwtyp == 6) {
            }
        }
    }
}
