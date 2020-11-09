<?php
$usrID = $_SESSION['USRID'];
$srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
$srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$chkOrRadio = isset($_POST['chkOrRadio']) ? cleanInputData($_POST['chkOrRadio']) : '';
$mustSelSth = isset($_POST['mustSelSth']) ? cleanInputData($_POST['mustSelSth']) : '';
$selvals = isset($_POST['selvals']) ? cleanInputData($_POST['selvals']) : '-1';
$lovNm = isset($_POST['lovNm']) ? cleanInputData($_POST['lovNm']) : '';
$criteriaID = isset($_POST['criteriaID']) ? cleanInputData($_POST['criteriaID']) : '';
$criteriaID2 = isset($_POST['criteriaID2']) ? cleanInputData($_POST['criteriaID2']) : '';
$criteriaID3 = isset($_POST['criteriaID3']) ? cleanInputData($_POST['criteriaID3']) : '';

$criteriaIDVal = isset($_POST['criteriaIDVal']) ? cleanInputData($_POST['criteriaIDVal']) : -1;
$criteriaID2Val = isset($_POST['criteriaID2Val']) ? cleanInputData($_POST['criteriaID2Val']) : '';
$criteriaID3Val = isset($_POST['criteriaID3Val']) ? cleanInputData($_POST['criteriaID3Val']) : '';

$psblValIDElmntID = isset($_POST['psblValIDElmntID']) ? cleanInputData($_POST['psblValIDElmntID']) : '';
$valueElmntID = isset($_POST['valElmntID']) ? cleanInputData($_POST['valElmntID']) : '';
$descElemntID = isset($_POST['descElmntID']) ? cleanInputData($_POST['descElmntID']) : '';
$modalElementID = isset($_POST['modalElementID']) ? cleanInputData($_POST['modalElementID']) : '';
$lovModalTitle = isset($_POST['lovModalTitle']) ? cleanInputData($_POST['lovModalTitle']) : '';
$lovModalBody = isset($_POST['lovModalBody']) ? cleanInputData($_POST['lovModalBody']) : '';
$addtnlWhere = isset($_POST['addtnlWhere']) ? cleanInputData($_POST['addtnlWhere']) : '';
/* if (urlencode(urldecode($addtnlWhere)) === $addtnlWhere) {
  $addtnlWhere = urldecode($addtnlWhere);
  } */
$colNoForChkBoxCmprsn = isset($_POST['colNoForChkBxCmprsn']) ? cleanInputData($_POST['colNoForChkBxCmprsn']) : 2;
$callBackFunc = isset($_POST['callBackFunc']) ? cleanInputData($_POST['callBackFunc']) : 'function () {var tstabcd=1;}';
$lovID = getLovID($lovNm);
$sqlMsg = "";
$isDynmc = "";
$orgnlSelvals = $selvals;
$selvals = "|" . $selvals . "|";
//var_dump($_POST);
//echo $srchFor;
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
    $srchFor = str_replace("%%", "%", $srchFor);
}
//echo $srchFor;
$error = "";
if (trim($lovNm) != "") {
    $lovNm = str_replace("'", "\'", $lovNm);
}
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0) {
        $total = getTtlLovValues($srchFor, $srchIn, $sqlMsg, $lovID, $isDynmc, $criteriaIDVal, $criteriaID2Val, $criteriaID3Val,
                $addtnlWhere);
        //echo ceil($total / $lmtSze);
        if ($pageNo > ceil($total / $lmtSze)) {
            $pageNo = 1;
        } else if ($pageNo < 1) {
            $pageNo = ceil($total / $lmtSze);
        }

        $curIdx = $pageNo - 1;
        $sqlMsg = "";
        $result = getLovValues($srchFor, $srchIn, $curIdx, $lmtSze, $sqlMsg, $lovID, $isDynmc, $criteriaIDVal, $criteriaID2Val,
                $criteriaID3Val, $addtnlWhere);
        ?>
        <form id='lovForm' action='' method='post' accept-charset='UTF-8'>
            <div class="row">
                <div class="col-sm-5" style="padding:0px 1px 0px 7.5px !important;">
                    <div class="input-group">
                        <input class="form-control" id="lovSrchFor" type = "text" placeholder="Search For" value="<?php
                        echo trim(str_replace("%", " ", $srchFor));
                        ?>" onkeyup="enterKeyFuncLov(event, '<?php echo $modalElementID; ?>', '<?php echo $lovModalTitle; ?>', '<?php echo $lovModalBody; ?>', '<?php echo $lovNm; ?>', '<?php echo $criteriaID; ?>', '<?php echo $criteriaID2; ?>', '<?php echo $criteriaID3; ?>', '<?php echo $chkOrRadio; ?>', true, '<?php echo $orgnlSelvals; ?>', '<?php echo $valueElmntID; ?>', '<?php echo $descElemntID; ?>', '',<?php echo $colNoForChkBoxCmprsn; ?>, '<?php
                               echo str_replace("'", "\'", $addtnlWhere);
                               ?>',<?php echo $callBackFunc ?>);">
                        <input id="lovPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                        <div class="input-group-append handCursor" onclick="getLovsPage('<?php echo $modalElementID; ?>', '<?php echo $lovModalTitle; ?>', '<?php echo $lovModalBody; ?>', '<?php echo $lovNm; ?>', '<?php echo $criteriaID; ?>', '<?php echo $criteriaID2; ?>', '<?php echo $criteriaID3; ?>', '<?php echo $chkOrRadio; ?>', true, '<?php echo $orgnlSelvals; ?>', '<?php echo $valueElmntID; ?>', '<?php echo $descElemntID; ?>', 'clear',<?php echo $colNoForChkBoxCmprsn; ?>, '<?php
                        echo str_replace("'", "\'", $addtnlWhere);
                        ?>',<?php echo $callBackFunc ?>);">
                            <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                        </div>
                        <div class="input-group-append handCursor" onclick="getLovsPage('<?php echo $modalElementID; ?>', '<?php echo $lovModalTitle; ?>', '<?php echo $lovModalBody; ?>', '<?php echo $lovNm; ?>', '<?php echo $criteriaID; ?>', '<?php echo $criteriaID2; ?>', '<?php echo $criteriaID3; ?>', '<?php echo $chkOrRadio; ?>', true, '<?php echo $orgnlSelvals; ?>', '<?php echo $valueElmntID; ?>', '<?php echo $descElemntID; ?>', '',<?php echo $colNoForChkBoxCmprsn; ?>, '<?php
                        echo str_replace("'", "\'", $addtnlWhere);
                        ?>',<?php echo $callBackFunc ?>);">
                            <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                        </div> 
                    </div>
                </div>
                <div class="col-sm-5" style="padding:0px 0px 0px 7.5px !important;">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-filter"></i></span>
                        </div>
                        <select data-placeholder="Select..." class="form-control chosen-select" id="lovSrchIn">
                            <?php
                            $valslctd1 = "";
                            $valslctd2 = "";
                            $valslctd3 = "";

                            if ($srchIn == "Value") {
                                $valslctd1 = "selected";
                            } elseif ($srchIn == "Value") {
                                $valslctd2 = "selected";
                            } else {
                                $valslctd3 = "selected";
                            }
                            ?>
                            <option value="Value" <?php echo $valslctd1; ?>>Value</option>
                            <option value="Description" <?php echo $valslctd2; ?>>Description</option>
                            <option value="Both" <?php echo $valslctd3; ?>>Both</option>
                        </select>
                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                        <select data-placeholder="Select..." class="form-control chosen-select" id="lovDsplySze">                            
                            <?php
                            $valslctdArry = array("", "", "", "", "", "", "", "");
                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500);
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
                <div class="col-sm-2" style="padding:0px 0px 0px 7.5px !important;">
                    <nav aria-label="Page navigation">
                        <ul class="pagination" style="margin: 0px !important;">
                            <li>
                                <a class="rhopagination" href="javascript:getLovsPage('<?php echo $modalElementID; ?>', '<?php echo $lovModalTitle; ?>', '<?php echo $lovModalBody; ?>', '<?php echo $lovNm; ?>', '<?php echo $criteriaID; ?>', '<?php echo $criteriaID2; ?>', '<?php echo $criteriaID3; ?>', '<?php echo $chkOrRadio; ?>', true, '<?php echo $orgnlSelvals; ?>', '<?php echo $valueElmntID; ?>', '<?php echo $descElemntID; ?>','previous',<?php echo $colNoForChkBoxCmprsn; ?>,'<?php
                                echo str_replace("'", "\'", $addtnlWhere);
                                ?>',<?php echo $callBackFunc ?>);" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li>
                                <a class="rhopagination" href="javascript:getLovsPage('<?php echo $modalElementID; ?>', '<?php echo $lovModalTitle; ?>', '<?php echo $lovModalBody; ?>', '<?php echo $lovNm; ?>', '<?php echo $criteriaID; ?>', '<?php echo $criteriaID2; ?>', '<?php echo $criteriaID3; ?>', '<?php echo $chkOrRadio; ?>', true, '<?php echo $orgnlSelvals; ?>', '<?php echo $valueElmntID; ?>', '<?php echo $descElemntID; ?>','next',<?php echo $colNoForChkBoxCmprsn; ?>,'<?php
                                echo str_replace("'", "\'", $addtnlWhere);
                                ?>',<?php echo $callBackFunc ?>);" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered" id="lovTblRO" cellspacing="0" width="100%" style="width:100%;">
                        <thead>
                            <tr>
                                <th align="center" style="text-align: center !important;">No.</th>
                                <!--<th align="center" style="max-width: 50px;text-align: center !important;">No.</th>-->
                                <th>Code &AMP; Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cntr = ((integer) $curIdx * (integer) $lmtSze);
                            while ($row = loc_db_fetch_array($result)) {
                                $cntr = (integer) $cntr + 1;
                                $chckd = "";
                                $chckID = ($row[2] == "") ? -1 : $row[2];
                                //echo $selvals;
                                if ($selvals !== "|" && strpos($selvals, "|" . $row[$colNoForChkBoxCmprsn] . "|") === false) {
                                    $chckd = "";
                                } else {
                                    $chckd = "checked";
                                }
                                if ($chkOrRadio == "check") {
                                    $chkBx = "<input type=\"checkbox\" name=\"chkbx$cntr\" value=\"$chckID;$row[0];$row[1]\" $chckd>";
                                } else {
                                    $chkBx = "<input type=\"radio\" name=\"radiobx\" value=\"$chckID;$row[0];$row[1]\" $chckd>";
                                }
                                ?>
                                <tr>
                                    <td class="lovtd" align="center" style="min-width: 60px;"><?php echo $chkBx . " (" . $cntr . ")"; ?></td>
                                    <!--<td class="lovtd" align="center" style="max-width: 50px;"><?php echo $cntr; ?></td>-->
                                    <td class="lovtd"><?php echo str_replace(" (" . $row[0] . ")", "", " " . $row[0] . " (" . $row[1] . ")"); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="" style="float:right;padding-right: 15px;">
                <button type="button" class="btn btn-default" data-dismiss="modal"  onclick="$('#<?php echo $modalElementID; ?>').modal('hide');">Close</button>
                <button type="button" class="btn btn-primary" onclick="applySlctdLov('<?php echo $modalElementID; ?>', 'lovForm', '<?php echo $valueElmntID; ?>', '<?php echo $descElemntID; ?>',<?php echo $callBackFunc ?>, '<?php echo $psblValIDElmntID; ?>');">Apply Selection</button>
            </div>
        </form>
        <?php
    }
}
?>
