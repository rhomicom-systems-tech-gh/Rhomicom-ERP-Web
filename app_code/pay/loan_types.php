<?php
$canAdd = test_prmssns($dfltPrvldgs[48], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[49], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[50], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Transaction Types Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel === true) {
                    echo deleteTransTyps($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Transaction Types Classification */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel === true) {
                    echo deletePayTypClsfctn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Transaction Types Transaction
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdPayTransTypsID = isset($_POST['sbmtdPayTransTypsID']) ? (float) cleanInputData($_POST['sbmtdPayTransTypsID']) : -1;
                $lnkdPayTransTypsID = isset($_POST['lnkdPayTransTypsID']) ? (float) cleanInputData($_POST['lnkdPayTransTypsID']) : -1;
                $payTransTypsName = isset($_POST['payTransTypsName']) ? cleanInputData($_POST['payTransTypsName']) : "";
                $payTransTypsDesc = isset($_POST['payTransTypsDesc']) ? cleanInputData($_POST['payTransTypsDesc']) : '';
                $payTransTyp = isset($_POST['payTransTyp']) ? cleanInputData($_POST['payTransTyp']) : '';
                $payTransTypsPrd = isset($_POST['payTransTypsPrd']) ? (float) cleanInputData($_POST['payTransTypsPrd']) : 0;
                $payTransPeriodTyp = isset($_POST['payTransPeriodTyp']) ? cleanInputData($_POST['payTransPeriodTyp']) : "";

                $payTransTypsIntrst = isset($_POST['payTransTypsIntrst']) ? (float) cleanInputData($_POST['payTransTypsIntrst']) : 0;
                $payTransTypsIntrstTyp = isset($_POST['payTransTypsIntrstTyp']) ? cleanInputData($_POST['payTransTypsIntrstTyp']) : "";
                $payTransTypsRepay = isset($_POST['payTransTypsRepay']) ? (float) cleanInputData($_POST['payTransTypsRepay']) : 0;
                $payTransTypsRepayTyp = isset($_POST['payTransTypsRepayTyp']) ? cleanInputData($_POST['payTransTypsRepayTyp']) : "";
                $payTransTypsPFrmlr = isset($_POST['payTransTypsPFrmlr']) ? cleanInputData($_POST['payTransTypsPFrmlr']) : "";

                $payTransTypsNetAmntFmlr = isset($_POST['payTransTypsNetAmntFmlr']) ? cleanInputData($_POST['payTransTypsNetAmntFmlr']) : "";
                $payTransTypsMxAmntFrmlr = isset($_POST['payTransTypsMxAmntFrmlr']) ? cleanInputData($_POST['payTransTypsMxAmntFrmlr']) : "";
                $payTransTypsMinAmntFrmlr = isset($_POST['payTransTypsMinAmntFrmlr']) ? cleanInputData($_POST['payTransTypsMinAmntFrmlr']) : "";
                $payTransTypsEnfrcMx = ((isset($_POST['payTransTypsEnfrcMx']) ? cleanInputData($_POST['payTransTypsEnfrcMx']) : "NO") == "YES") ? true : false;

                $payTransTypsIsEnbld = isset($_POST['payTransTypsIsEnbld']) ? cleanInputData($_POST['payTransTypsIsEnbld']) : "YES";
                $payIsEnbld = ($payTransTypsIsEnbld == "YES") ? true : false;
                $payTransTypsItmStID = isset($_POST['payTransTypsItmStID']) ? (int) cleanInputData($_POST['payTransTypsItmStID']) : -1;
                $payTransTypsMnItmID = isset($_POST['payTransTypsMnItmID']) ? (int) cleanInputData($_POST['payTransTypsMnItmID']) : -1;
                $payTransCshAcntID = isset($_POST['payTransCshAcntID']) ? (int) cleanInputData($_POST['payTransCshAcntID']) : -1;
                $payTransAssetAcntID = isset($_POST['payTransAssetAcntID']) ? (int) cleanInputData($_POST['payTransAssetAcntID']) : -1;
                $payTransRcvblAcntID = isset($_POST['payTransRcvblAcntID']) ? (int) cleanInputData($_POST['payTransRcvblAcntID']) : -1;
                $payTransLbltyAcntID = isset($_POST['payTransLbltyAcntID']) ? (int) cleanInputData($_POST['payTransLbltyAcntID']) : -1;
                $payTransRvnuAcntID = isset($_POST['payTransRvnuAcntID']) ? (int) cleanInputData($_POST['payTransRvnuAcntID']) : -1;
                $slctdTypClsfctns = isset($_POST['slctdTypClsfctns']) ? cleanInputData($_POST['slctdTypClsfctns']) : '';
                $payTransTypsMnBalsItmID = isset($_POST['payTransTypsMnBalsItmID']) ? (float) cleanInputData($_POST['payTransTypsMnBalsItmID']) : -1;
                $payTransTypsMnBalsItmNm = isset($_POST['payTransTypsMnBalsItmNm']) ? cleanInputData($_POST['payTransTypsMnBalsItmNm']) : '';

                if (strlen($payTransTypsDesc) > 299) {
                    $payTransTypsDesc = substr($payTransTypsDesc, 0, 299);
                }
                $exitErrMsg = "";
                if ($payTransTypsName == "") {
                    $exitErrMsg .= "Please enter Transaction Type Name!<br/>";
                }
                if ($payTransTyp == "") {
                    $exitErrMsg .= "Transaction Type cannot be empty!<br/>";
                }
                if ($payTransTyp == "SETTLEMENT" && ($lnkdPayTransTypsID <= 0 || $payTransTypsMnBalsItmID <= 0)) {
                    $exitErrMsg .= "Linked Loan Type and Balance Item cannot be empty for Loan Settlements!<br/>";
                }
                if (($payTransTypsItmStID <= 0 || $payTransTypsMnItmID <= 0) && $payTransTyp != "INVESTMENT") {
                    $exitErrMsg .= "Item Set and Main Item cannot be empty!<br/>";
                }
                if (($payTransCshAcntID <= 0 || $payTransAssetAcntID <= 0 || $payTransRcvblAcntID <= 0 || $payTransLbltyAcntID <= 0 || $payTransRvnuAcntID <= 0) && $payTransTyp == "INVESTMENT") {
                    $exitErrMsg .= "All GL Accounts cannot be empty!<br/>";
                }
                if ((!$canEdt && !$canAdd)) {
                    $exitErrMsg .= "Sorry you don't have permission to perform this action";
                }
                $oldTrnasTypID = getGnrlRecID("pay.loan_pymnt_invstmnt_typs", "item_type_name", "item_type_id", $payTransTypsName, $orgID);
                if ($oldTrnasTypID > 0 && $oldTrnasTypID != $sbmtdPayTransTypsID) {
                    $exitErrMsg .= "New Document Number/Name is already in use in this Organization!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdPayTransTypsID'] = $sbmtdPayTransTypsID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $afftctd = 0;
                $afftctd1 = 0;
                if ($sbmtdPayTransTypsID <= 0) {
                    $afftctd += createTransTyps(
                        $orgID,
                        $payTransTypsName,
                        $payTransTypsDesc,
                        $payTransTyp,
                        $payTransTypsItmStID,
                        $payTransTypsMnItmID,
                        $payTransCshAcntID,
                        $payTransAssetAcntID,
                        $payTransRcvblAcntID,
                        $payTransLbltyAcntID,
                        $payTransRvnuAcntID,
                        $payTransTypsPrd,
                        $payTransPeriodTyp,
                        $payIsEnbld,
                        $payTransTypsPFrmlr,
                        $payTransTypsIntrst,
                        $payTransTypsIntrstTyp,
                        $payTransTypsRepay,
                        $payTransTypsRepayTyp,
                        $payTransTypsNetAmntFmlr,
                        $payTransTypsMxAmntFrmlr,
                        $payTransTypsEnfrcMx,
                        $lnkdPayTransTypsID,
                        $payTransTypsMinAmntFrmlr,
                        $payTransTypsMnBalsItmID
                    );
                    $sbmtdPayTransTypsID = getGnrlRecID("pay.loan_pymnt_invstmnt_typs", "item_type_name", "item_type_id", $payTransTypsName, $orgID);
                } else if ($sbmtdPayTransTypsID > 0) {
                    $afftctd += updtTransTyps(
                        $sbmtdPayTransTypsID,
                        $payTransTypsName,
                        $payTransTypsDesc,
                        $payTransTyp,
                        $payTransTypsItmStID,
                        $payTransTypsMnItmID,
                        $payTransCshAcntID,
                        $payTransAssetAcntID,
                        $payTransRcvblAcntID,
                        $payTransLbltyAcntID,
                        $payTransRvnuAcntID,
                        $payTransTypsPrd,
                        $payTransPeriodTyp,
                        $payIsEnbld,
                        $payTransTypsPFrmlr,
                        $payTransTypsIntrst,
                        $payTransTypsIntrstTyp,
                        $payTransTypsRepay,
                        $payTransTypsRepayTyp,
                        $payTransTypsNetAmntFmlr,
                        $payTransTypsMxAmntFrmlr,
                        $payTransTypsEnfrcMx,
                        $lnkdPayTransTypsID,
                        $payTransTypsMinAmntFrmlr,
                        $payTransTypsMnBalsItmID
                    );
                }
                if (trim($slctdTypClsfctns, "|~") != "") {
                    //Save Account Segments
                    $variousRows = explode("|", trim($slctdTypClsfctns, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 5) {
                            $clsfctnID = (int) (cleanInputData1($crntRow[0]));
                            $clsfctnNum = (int) (cleanInputData1($crntRow[1]));
                            $majCtgrName = cleanInputData1($crntRow[2]);
                            $minCtgrName = cleanInputData1($crntRow[3]);
                            $ln_IsEnabled = cleanInputData1($crntRow[4]) == "YES" ? true : false;
                            if ($majCtgrName != "") {
                                if ($clsfctnID <= 0) {
                                    //Insert
                                    $afftctd1 += createPayTypClsfctn($sbmtdPayTransTypsID, $majCtgrName, $minCtgrName, $clsfctnNum, $ln_IsEnabled);
                                } else if ($clsfctnID > 0) {
                                    $afftctd1 += updtPayTypClsfctn($clsfctnID, $sbmtdPayTransTypsID, $majCtgrName, $minCtgrName, $clsfctnNum, $ln_IsEnabled);
                                }
                            }
                        }
                    }
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Transaction Type Successfully Saved!"
                        . "<br/>" . $afftctd1 . " Transaction Type Classification(s) Saved Successfully!"
                        . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Transaction Type Successfully Saved!"
                        . "<br/>" . $afftctd1 . " Transaction Type Classification(s) Saved Successfully!";
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdPayTransTypsID'] = $sbmtdPayTransTypsID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 2) {
            }
        } else if ($qstr == "VOID") {
            if ($actyp == 1) {
            }
        } else {
            if ($vwtyp == 0) {
                //Transaction Types Transactions
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Loan, Payment and Investment Types</span>
                            </li>
                           </ul>
                          </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                $qShwUnpstdOnly = false;
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Total_PayTransTyps($srchFor, $srchIn, $orgID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_PayTransTyps($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-3";
?>
                    <form id='payTransTypsForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                        <fieldset class="">
                            <legend class="basic_person_lg1" style="color: #003245">TRANSACTION TYPES</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php if ($canAdd === true) { ?>
                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;">
                                        <?php if ($canAdd === true) { ?>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePayTransTypsForm(-1, 1, 'ShowDialog', 'Transaction Types Payments');">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                New Transaction Type
                                            </button>
                                        <?php } ?>
                                    </div>
                                <?php
                                } else {
                                    $colClassType1 = "col-md-2";
                                    $colClassType2 = "col-md-4";
                                }
                                ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="payTransTypsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                            ?>" onkeyup="enterKeyFuncPayTransTyps(event, '', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                        <input id="payTransTypsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getPayTransTyps('clear', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getPayTransTyps('', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType2; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="payTransTypsSrchIn">
                                            <?php
                                            $valslctdArry = array("");
                                            $srchInsArrys = array("Name");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                            ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="payTransTypsDsplySze" style="min-width:70px !important;">
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
                                <div class="<?php echo $colClassType1; ?>">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" style="margin: 0px !important;">
                                            <li>
                                                <a href="javascript:getPayTransTyps('previous', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getPayTransTyps('next', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="payTransTypsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:30px;width:30px;">No.</th>
                                                <th style="max-width:30px;width:30px;">...</th>
                                                <th style="max-width:150px;width:150px;">Transaction Type</th>
                                                <th>Transaction Type Name</th>
                                                <th>Transaction Type Description</th>
                                                <?php if ($canDel === true) { ?>
                                                    <th style="max-width:30px;width:30px;">...</th>
                                                <?php } ?>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th style="max-width:30px;width:30px;">...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;
                                            ?>
                                                <tr id="payTransTypsHdrsRow_<?php echo $cntr; ?>">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Account" onclick="getOnePayTransTypsForm(<?php echo $row[0]; ?>, 1, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[3]; ?></td>
                                                    <td class="lovtd"><?php echo $row[1]; ?></td>
                                                    <td class="lovtd"><?php echo $row[2]; ?></td>
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delPayTransTyps('payTransTypsHdrsRow_<?php echo $cntr; ?>')" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="payTransTypsHdrsRow<?php echo $cntr; ?>_HdrID" name="payTransTypsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                    ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                    echo urlencode(encrypt1(($row[0] . "|pay.loan_pymnt_invstmnt_typs|item_type_id"), $smplTokenWord1));
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
                        </fieldset>
                    </form>
                <?php
                }
            } else if ($vwtyp == 1) {
                //New Transaction Types Form
                $sbmtdPayTransTypsID = isset($_POST['sbmtdPayTransTypsID']) ? cleanInputData($_POST['sbmtdPayTransTypsID']) : -1;
                $payTransTypsName = "";
                $payTransTypsDesc = "";
                $payTransTyp = "LOAN";
                $payTransTypsPrd = 0;
                $payTransPeriodTyp = "Days";
                $payTransTypsItmStID = -1;
                $payTransTypsItmStNm = "";
                $payTransTypsMnItmID = -1;
                $payTransTypsMnItmNm = "";

                $payTransTypsMnBalsItmNm = "";
                $payTransTypsMnBalsItmID = -1;
                $payTransCshAcntID = -1;
                $payTransCshAcntNm = "";
                $payTransAssetAcntID = -1;
                $payTransAssetAcntNm = "";
                $payTransRcvblAcntID = -1;
                $payTransRcvblAcntNm = "";
                $payTransLbltyAcntID = -1;
                $payTransLbltyAcntNm = "";
                $payTransRvnuAcntID = -1;
                $payTransRvnuAcntNm = "";
                $payTransTypsIsEnbld = "1";
                $payTransTypsIntrst = 0;
                $payTransTypsIntrstTyp = "% Per Annum";
                $payTransTypsRepay = 0;
                $payTransTypsRepayTyp = "Months";
                $payTransTypsPFrmlr = "Select 0";
                $payTransTypsNetAmntFmlr = "Select 0";
                $payTransTypsMxAmntFrmlr = "Select 0";
                $payTransTypsEnfrcMx = "0";
                $payTransTypsMinAmntFrmlr = "Select 0";
                $lnkdPayTransTypsID = -1;
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if ($sbmtdPayTransTypsID > 0) {
                    $result = get_One_PayTransTyps($sbmtdPayTransTypsID);
                    if ($row = loc_db_fetch_array($result)) {
                        $payTransTypsName = $row[1];
                        $payTransTypsDesc = $row[2];
                        $payTransTyp = $row[3];
                        $payTransTypsPrd = (float) $row[8];
                        $payTransPeriodTyp = $row[9];
                        $payTransTypsItmStID = (int) $row[4];
                        $payTransTypsItmStNm = $row[5];
                        $payTransTypsMnItmID = (int) $row[6];
                        $payTransTypsMnItmNm = $row[7];
                        $payTransCshAcntID = (int) $row[10];
                        $payTransCshAcntNm = $row[11];
                        $payTransAssetAcntID = (int) $row[12];
                        $payTransAssetAcntNm = $row[13];
                        $payTransRcvblAcntID = (int) $row[14];
                        $payTransRcvblAcntNm = $row[15];
                        $payTransLbltyAcntID = (int) $row[16];
                        $payTransLbltyAcntNm = $row[17];
                        $payTransRvnuAcntID = (int) $row[18];
                        $payTransRvnuAcntNm = $row[19];
                        $payTransTypsIsEnbld = $row[20];
                        $payTransTypsIntrst = (float) $row[22];
                        $payTransTypsIntrstTyp = $row[23];
                        $payTransTypsRepay = (float) $row[24];
                        $payTransTypsRepayTyp = $row[25];
                        $payTransTypsPFrmlr = $row[21];
                        $payTransTypsNetAmntFmlr = $row[26];
                        $payTransTypsMxAmntFrmlr = $row[27];
                        $payTransTypsEnfrcMx = $row[28];
                        $payTransTypsMinAmntFrmlr = $row[29];
                        $lnkdPayTransTypsID = (float) $row[30];
                        $payTransTypsMnBalsItmID = (float) $row[31];
                        $payTransTypsMnBalsItmNm = $row[32];
                    }
                }
                $payTransTypHide1 = "";
                $payTransTypHide2 = "hideNotice";
                $payTransTypHide3 = "hideNotice";
                $payTransTypHide4 = "hideNotice";
                if ($payTransTyp != "INVESTMENT") {
                    $payTransTypHide1 = "hideNotice";
                    $payTransTypHide2 = "";
                }
                if ($payTransTyp == "LOAN") {
                    $payTransTypHide3 = "hideNotice";
                    $payTransTypHide4 = "";
                }
                if ($payTransTyp == "SETTLEMENT") {
                    $payTransTypHide3 = "";
                    $payTransTypHide4 = "hideNotice";
                }
                ?>
                <form class="form-horizontal" id="onePayTransTypsEDTForm">
                    <div class="row" style="margin-top:5px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Transaction Type:</label>
                                </div>
                                <div class="col-md-8">
                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payTransTyp" style="width:100% !important;" onchange="shwHideTrnsTypsDivs();">
                                        <?php
                                        $valslctdArry = array("", "", "", "");
                                        $srchInsArrys = array("LOAN", "PAYMENT", "INVESTMENT", "SETTLEMENT");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($payTransTyp == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Type Name:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="payTransTypsName" name="payTransTypsName" value="<?php echo $payTransTypsName; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdPayTransTypsID" name="sbmtdPayTransTypsID" value="<?php echo $sbmtdPayTransTypsID; ?>" readonly="true">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Description:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group" style="width:100%;">
                                        <textarea class="form-control rqrdFld" rows="2" cols="20" id="payTransTypsDesc" name="payTransTypsDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $payTransTypsDesc; ?></textarea>
                                        <input class="form-control" type="hidden" id="payTransTypsDesc1" value="<?php echo $payTransTypsDesc; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('payTransTypsDesc');" style="max-width:30px;width:30px;">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="padding:6px 0px 0px 0px !important;">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">&nbsp;</label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <?php
                                            $payTransTypsIsEnbldChkd = "";
                                            if ($payTransTypsIsEnbld == "1") {
                                                $payTransTypsIsEnbldChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" onclick="" id="payTransTypsIsEnbld" name="payTransTypsIsEnbld" <?php echo $payTransTypsIsEnbldChkd; ?>>
                                            Enabled?
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <?php
                                            $payTransTypsEnfrcMxChkd = "";
                                            if ($payTransTypsEnfrcMx == "1") {
                                                $payTransTypsEnfrcMxChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" onclick="" id="payTransTypsEnfrcMx" name="payTransTypsEnfrcMx" <?php echo $payTransTypsEnfrcMxChkd; ?>>
                                            Enforce Max Amount?
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row <?php echo $payTransTypHide1; ?>" style="margin-top:5px;" id="payTransIvstmntDiv">
                        <div class="col-md-12" style="border:1px solid #ddd;border-radius:5px;padding:5px 15px !important;">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Period/Duration:</label>
                                </div>
                                <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                    <input type="text" class="form-control trnTypPrdNum" aria-label="..." id="payTransTypsPrd" name="payTransTypsPrd" value="<?php echo $payTransTypsPrd; ?>">
                                </div>
                                <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payTransPeriodTyp" style="width:100% !important;">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "");
                                        $srchInsArrys = array("Day(s)", "Week(s)", "Month(s)", "Quarter(s)", "Half-Year(s)", "Year(s)");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($payTransPeriodTyp == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payTransCshAcntNm" class="control-label col-md-4">Cash Account:</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="payTransCshAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type="text" min="0" placeholder="" value="<?php echo $payTransCshAcntNm; ?>" readonly="true" />
                                        <input type="hidden" id="payTransCshAcntID" value="<?php echo $payTransCshAcntID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payTransCshAcntID', 'payTransCshAcntNm', 'clear', 1, '', function () {});">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payTransAssetAcntNm" class="control-label col-md-4">Principal Receivable Account:</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="payTransAssetAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type="text" min="0" placeholder="" value="<?php echo $payTransAssetAcntNm; ?>" readonly="true" />
                                        <input type="hidden" id="payTransAssetAcntID" value="<?php echo $payTransAssetAcntID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payTransAssetAcntID', 'payTransAssetAcntNm', 'clear', 1, '', function () {});">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payTransRcvblAcntNm" class="control-label col-md-4">Interest Receivable Account:</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="payTransRcvblAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type="text" min="0" placeholder="" value="<?php echo $payTransRcvblAcntNm; ?>" readonly="true" />
                                        <input type="hidden" id="payTransRcvblAcntID" value="<?php echo $payTransRcvblAcntID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payTransRcvblAcntID', 'payTransRcvblAcntNm', 'clear', 1, '', function () {});">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payTransLbltyAcntNm" class="control-label col-md-4">Deferred Interest Account:</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="payTransLbltyAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type="text" min="0" placeholder="" value="<?php echo $payTransLbltyAcntNm; ?>" readonly="true" />
                                        <input type="hidden" id="payTransLbltyAcntID" value="<?php echo $payTransLbltyAcntID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Liability Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payTransLbltyAcntID', 'payTransLbltyAcntNm', 'clear', 1, '', function () {});">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payTransRvnuAcntNm" class="control-label col-md-4">Interest Revenue Account:</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="payTransRvnuAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type="text" min="0" placeholder="" value="<?php echo $payTransRvnuAcntNm; ?>" readonly="true" />
                                        <input type="hidden" id="payTransRvnuAcntID" value="<?php echo $payTransRvnuAcntID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Revenue Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payTransRvnuAcntID', 'payTransRvnuAcntNm', 'clear', 1, '', function () {});">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row <?php echo $payTransTypHide2; ?>" style="margin-top:5px;" id="payTransItemsDiv">
                        <div class="col-md-12" style="border:1px solid #ddd;border-radius:5px;padding:5px 15px !important;">
                            <div class="form-group">
                                <label for="payTransTypsItmStNm" class="control-label col-md-4">Item Set:</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="payTransTypsItmStNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Item Sets for Payments" type="text" value="<?php echo $payTransTypsItmStNm; ?>" readonly="true" />
                                        <input type="hidden" id="payTransTypsItmStID" value="<?php echo $payTransTypsItmStID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Item Sets for Payments(Enabled)', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payTransTypsItmStID', 'payTransTypsItmStNm', 'clear', 1, '', function () {

                                                });">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payTransTypsMnItmNm" class="control-label col-md-4">Main Item for Amount:</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="payTransTypsMnItmNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Amount Item" type="text" value="<?php echo $payTransTypsMnItmNm; ?>" readonly="true" />
                                        <input type="hidden" id="payTransTypsMnItmID" value="<?php echo $payTransTypsMnItmID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Pay Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payTransTypsMnItmID', 'payTransTypsMnItmNm', 'clear', 1, '', function () {

                                                });">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="<?php echo $payTransTypHide3; ?>" id="payTransItemSettlDiv">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Linked Loan Type:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="lnkdPayTransTypsID" name="lnkdPayTransTypsID" style="width:100% !important;">
                                            <?php
                                            $lqlovNm = "Internal Pay Loan Types";
                                            $cnt = 0;
                                            $brghtStr = "";
                                            $isDynmyc = true;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID($lqlovNm), $isDynmyc, $orgID, "", "");
                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                $selectedTxt = "";
                                                if (((int) $titleRow[0]) == $lnkdPayTransTypsID) {
                                                    $selectedTxt = "selected";
                                                }
                                            ?>
                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[1]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="payTransTypsMnBalsItmNm" class="control-label col-md-4">Linked Loan Main Balance Item:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="payTransTypsMnBalsItmNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Main Balance Item" type="text" value="<?php echo $payTransTypsMnBalsItmNm; ?>" readonly="true" />
                                            <input type="hidden" id="payTransTypsMnBalsItmID" value="<?php echo $payTransTypsMnBalsItmID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Balance Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payTransTypsMnBalsItmID', 'payTransTypsMnBalsItmNm', 'clear', 1, '', function () {});">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Interest Rate (%):</label>
                                </div>
                                <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                    <input type="text" class="form-control trnTypPrdNum" aria-label="..." id="payTransTypsIntrst" name="payTransTypsIntrst" value="<?php echo $payTransTypsIntrst; ?>">
                                </div>
                                <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payTransTypsIntrstTyp" style="width:100% !important;">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("% Per Annum", "% Flat Rate");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($payTransTypsIntrstTyp == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="<?php echo $payTransTypHide4; ?>" id="payTransItemLoansDiv">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Repayment Period:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control trnTypPrdNum" aria-label="..." id="payTransTypsRepay" name="payTransTypsRepay" value="<?php echo $payTransTypsRepay; ?>">
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="payTransTypsRepayTyp" style="width:100% !important;">
                                            <?php
                                            $valslctdArry = array("", "");
                                            $srchInsArrys = array("Installments", "Months");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($payTransTypsRepayTyp == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                            ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Periodic Deduction Formula:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group" style="width:100%;">
                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="payTransTypsPFrmlr" name="payTransTypsPFrmlr" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $payTransTypsPFrmlr; ?></textarea>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('payTransTypsPFrmlr');" style="max-width:30px;width:30px;">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Net Amount Formula:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group" style="width:100%;">
                                        <textarea class="form-control rqrdFld" rows="2" cols="20" id="payTransTypsNetAmntFmlr" name="payTransTypsNetAmntFmlr" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $payTransTypsNetAmntFmlr; ?></textarea>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('payTransTypsNetAmntFmlr');" style="max-width:30px;width:30px;">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Min Amount Formula:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group" style="width:100%;">
                                        <textarea class="form-control rqrdFld" rows="2" cols="20" id="payTransTypsMinAmntFrmlr" name="payTransTypsMinAmntFrmlr" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $payTransTypsMinAmntFrmlr; ?></textarea>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('payTransTypsMinAmntFrmlr');" style="max-width:30px;width:30px;">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Max Amount Formula:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group" style="width:100%;">
                                        <textarea class="form-control rqrdFld" rows="2" cols="20" id="payTransTypsMxAmntFrmlr" name="payTransTypsMxAmntFrmlr" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $payTransTypsMxAmntFrmlr; ?></textarea>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('payTransTypsMxAmntFrmlr');" style="max-width:30px;width:30px;">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $nwRowHtml1 = "<tr id=\"payTrnTypClsfctnsRow__WWW123WWW\" class=\"hand_cursor\">
                                                    <td class=\"lovtd\">New
                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"payTrnTypClsfctnsRow_WWW123WWW_ClsfctnID\" value=\"-1\">
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"payTrnTypClsfctnsRow_WWW123WWW_OrdrNum\" value=\"1\" style=\"width:100% !important;margin-left:0px !important;\">
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"payTrnTypClsfctnsRow_WWW123WWW_MajClsfctn\" value=\"\" style=\"width:100% !important;margin-left:0px !important;\">
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"payTrnTypClsfctnsRow_WWW123WWW_MinClsfctn\" value=\"\" style=\"width:100% !important;margin-left:0px !important;\">
                                                    </td>
                                                    <td class=\"lovtd\" style=\"text-align: center;\">";
                            $isChkd = "checked=\"true\"";
                            $nwRowHtml1 .= "<div class=\"form-group form-group-sm\">
                                                        <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                            <label class=\"form-check-label\">
                                                                <input type=\"checkbox\" class=\"form-check-input\" id=\"payTrnTypClsfctnsRow_WWW123WWW_IsEnbld\" name=\"payTrnTypClsfctnsRow_WWW123WWW_IsEnbld\" " . $isChkd . ">
                                                            </label>
                                                        </div>
                                                    </div>                                                        
                                            </td>
                                                      <td class=\"lovtd\">
                                                          <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPayTrnTypClsfctn('payTrnTypClsfctnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Classification\">
                                                              <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                          </button>
                                                      </td>
                                                      <td class=\"lovtd\">&nbsp;</td>
                                                </tr>";
                            $nwRowHtml = urlencode($nwRowHtml1);
                            ?>
                            <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs">
                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('payTrnTypClsfctnsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Classification
                                        </button>
                                    </div>
                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <table class="table table-striped table-bordered table-responsive" id="payTrnTypClsfctnsTable" cellspacing="0" width="100%" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="max-width:30px;width:30px;">No.</th>
                                                    <th style="max-width:60px;width:60px;">Display Number</th>
                                                    <th style="max-width:120px;width:120px;">Classification Name</th>
                                                    <th>Classification Description</th>
                                                    <th style="max-width: 75px !important;width: 75px !important;text-align: center;">Enabled?</th>
                                                    <th style="max-width:20px;width:20px;">...</th>
                                                    <th style="max-width:20px;width:20px;">...</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $resultClsfctn = get_One_PayTypClsfctns($sbmtdPayTransTypsID);
                                                $cntr = 0;
                                                $curIdx = 0;
                                                $lmtSze = 100;
                                                while ($row1 = loc_db_fetch_array($resultClsfctn)) {
                                                    $cntr += 1;
                                                    $isEnabled = $row1[4];
                                                ?>
                                                    <tr id="payTrnTypClsfctnsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?>
                                                            <input type="hidden" class="form-control" aria-label="..." id="payTrnTypClsfctnsRow<?php echo $cntr; ?>_ClsfctnID" value="<?php echo $row1[0]; ?>">
                                                        </td>
                                                        <td class="lovtd">
                                                            <input type="text" class="form-control" aria-label="..." id="payTrnTypClsfctnsRow<?php echo $cntr; ?>_OrdrNum" value="<?php echo (int) $row1[3]; ?>" style="width:100% !important;margin-left:0px !important;">
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdt === true) { ?>
                                                                <input type="text" class="form-control" aria-label="..." id="payTrnTypClsfctnsRow<?php echo $cntr; ?>_MajClsfctn" value="<?php echo $row1[1]; ?>" style="width:100% !important;margin-left:0px !important;">
                                                            <?php } else { ?>
                                                                <span class=""><?php echo $row1[1]; ?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdt === true) { ?>
                                                                <input type="text" class="form-control" aria-label="..." id="payTrnTypClsfctnsRow<?php echo $cntr; ?>_MinClsfctn" value="<?php echo $row1[2]; ?>" style="width:100% !important;margin-left:0px !important;">
                                                            <?php } else { ?>
                                                                <span class=""><?php echo $row1[2]; ?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="lovtd" style="text-align: center;">
                                                            <?php
                                                            $isChkd = "";
                                                            if ($isEnabled == "1") {
                                                                $isChkd = "checked=\"true\"";
                                                            }
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <div class="form-group form-group-sm">
                                                                    <div class="form-check" style="font-size: 12px !important;">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox" class="form-check-input" id="payTrnTypClsfctnsRow<?php echo $cntr; ?>_IsEnbld" name="payTrnTypClsfctnsRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd ?>>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span class=""><?php echo ($isEnabled == "1" ? "Yes" : "No"); ?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canDel === true) { ?>
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayTrnTypClsfctn('payTrnTypClsfctnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Classifications">
                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canVwRcHstry === true) { ?>
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                        echo urlencode(encrypt1(($row1[0] . "|pay.loan_pymnt_typ_clsfctn|typ_clsfctn_id"),
                                                                                                                                                                                                                            $smplTokenWord1
                                                                                                                                                                                                                        ));
                                                                                                                                                                                                                        ?>');" style="padding:2px !important;">
                                                                    <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:5px;">
                        <div class="col-md-12">
                            <div class="col-md-6" style="padding:0px 0px 0px 0px !important;float:left;">
                            </div>
                            <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <?php if ($canEdt) { ?>
                                        <button type="button" class="btn btn-default" style="" onclick="savePayTransTypsForm('<?php echo $fnccurnm; ?>', 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 2) {
            } else if ($vwtyp == 4) {
                //Get Selected Filter Details
                header("content-type:application/json");
                $payTrnsRqstsItmTypID = isset($_POST['payTrnsRqstsItmTypID']) ? (int) cleanInputData($_POST['payTrnsRqstsItmTypID']) : "";
                $payTrnsRqstsType = isset($_POST['payTrnsRqstsType']) ? cleanInputData($_POST['payTrnsRqstsType']) : "";
                $arr_content['FilterOptions'] = loadTypClsfctnOptions($payTrnsRqstsItmTypID, $payTrnsRqstsType);
                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 5) {
                //Get Selected Filter Details
                header("content-type:application/json");
                $payTrnsRqstsItmTypID = isset($_POST['payTrnsRqstsItmTypID']) ? (float) cleanInputData($_POST['payTrnsRqstsItmTypID']) : -1;
                $payTrnsRqstsType = isset($_POST['payTrnsRqstsType']) ? cleanInputData($_POST['payTrnsRqstsType']) : "";
                $lnkdPayTrnsRqstsID = isset($_POST['lnkdPayTrnsRqstsID']) ? (float) cleanInputData($_POST['lnkdPayTrnsRqstsID']) : -1;
                $payTrnsRqstsPrsnID = isset($_POST['payTrnsRqstsPrsnID']) ? (float) cleanInputData($_POST['payTrnsRqstsPrsnID']) : -1;
                //$payTrnsRqstsDpndtItmTypID = (float) getGnrlRecNm("pay.loan_pymnt_invstmnt_typs", "item_type_id", "lnkd_loan_type_id", $payTrnsRqstsItmTypID);
                $firstPayTrnsRqstsID = -1;
                $arr_content['FilterOptions'] = loadTypRqstsOptions($payTrnsRqstsItmTypID, $payTrnsRqstsPrsnID, $firstPayTrnsRqstsID);
                if ($lnkdPayTrnsRqstsID <= 0) {
                    $lnkdPayTrnsRqstsID = $firstPayTrnsRqstsID;
                }
                $arr_content['DefaultAmnt'] = getLoanTypRqstsMxAmnt($payTrnsRqstsItmTypID, $lnkdPayTrnsRqstsID, $payTrnsRqstsPrsnID);
                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 20) {
            }
            ?>
<?php
        }
    }
}
?>