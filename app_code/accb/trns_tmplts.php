<?php
$canAdd = test_prmssns($dfltPrvldgs[14], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[15], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);

$dfltPrvldgs2 = array(
    /* 111 */
    "View Expense Vouchers", "Add Expense Vouchers", "Edit Expense Vouchers", "Delete Expense Vouchers",
    /* 115 */ "View Income Vouchers", "Add Income Vouchers", "Edit Income Vouchers", "Delete Income Vouchers",
    /* 119 */ "View Fund Management", "Add Fund Management", "Edit Fund Management", "Delete Fund Management",
);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Template */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteTmpltTrns($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } elseif ($actyp == 2) {
                /* Delete Template Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteTmpltTrnsDetLn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } elseif ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Transaction Template
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $accbTrnsTmpltID      = isset($_POST['accbTrnsTmpltID']) ? (float)cleanInputData($_POST['accbTrnsTmpltID']) : -1;
                $accbTrnsTmpltName    = isset($_POST['accbTrnsTmpltName']) ? cleanInputData($_POST['accbTrnsTmpltName']) : "";
                $accbTrnsTmpltDesc    = isset($_POST['accbTrnsTmpltDesc']) ? cleanInputData($_POST['accbTrnsTmpltDesc']) : '';
                $accbTrnsTmpltDocTyp  = isset($_POST['accbTrnsTmpltDocTyp']) ? cleanInputData($_POST['accbTrnsTmpltDocTyp']) : '';
                $slctdTransLines      = isset($_POST['slctdTransLines']) ? cleanInputData($_POST['slctdTransLines']) : '';
                $slctdTransUsersLines = isset($_POST['slctdTransUsersLines']) ? cleanInputData($_POST['slctdTransUsersLines']) : '';
                $accbTrnsTmpltEnbld = isset($_POST['accbTrnsTmpltEnbld']) ? cleanInputData($_POST['accbTrnsTmpltEnbld']) : "YES";
                $accbTrnsIsEnbld = ($accbTrnsTmpltEnbld == "YES") ? true : false;

                $exitErrMsg = "";
                if ($accbTrnsTmpltName == "") {
                    $exitErrMsg .= "Please enter Template Name!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent']         = 100;
                    $arr_content['accbTrnsTmpltID'] = $accbTrnsTmpltID;
                    $arr_content['message']         = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($accbTrnsTmpltID <= 0) {
                    createTmplt($orgID, $accbTrnsTmpltName, $accbTrnsTmpltDesc, $accbTrnsTmpltDocTyp, $accbTrnsIsEnbld);
                    $accbTrnsTmpltID = getGnrlRecID("accb.accb_trnsctn_templates_hdr", "template_name", "template_id", $accbTrnsTmpltName, $orgID);
                } elseif ($accbTrnsTmpltID > 0) {
                    updateTmplt($accbTrnsTmpltID, $accbTrnsTmpltName, $accbTrnsTmpltDesc, $accbTrnsTmpltDocTyp, $accbTrnsIsEnbld);
                }
                $afftctd  = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdTransLines, "|~") != "" && $accbTrnsTmpltID >= 0) {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdTransLines, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 4) {
                            $ln_TrnsLnID   = (float)(cleanInputData1($crntRow[0]));
                            $ln_LineDesc   = cleanInputData1($crntRow[1]);
                            $ln_IncrsDcrs1 = strtoupper(cleanInputData1($crntRow[2]));
                            $ln_AccountID1 = (int)cleanInputData1($crntRow[3]);
                            $errMsg        = "";
                            if ($ln_LineDesc != "" && $ln_IncrsDcrs1 != "" && $ln_AccountID1 > 0) {
                                if ($ln_TrnsLnID <= 0) {
                                    $afftctd += createTmpltTrns($ln_AccountID1, $ln_LineDesc, $accbTrnsTmpltID, substr($ln_IncrsDcrs1, 0, 1));
                                } else {
                                    $afftctd += updateTmpltTrns(
                                        $ln_AccountID1,
                                        $ln_LineDesc,
                                        $accbTrnsTmpltID,
                                        substr($ln_IncrsDcrs1, 0, 1),
                                        $ln_TrnsLnID
                                    );
                                }
                            }
                        }
                    }
                }
                if (trim($slctdTransUsersLines, "|~") != "" && $accbTrnsTmpltID >= 0) {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdTransUsersLines, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 4) {
                            $ln_TrnsLnID = (float)(cleanInputData1($crntRow[0]));
                            $ln_UserID   = cleanInputData1($crntRow[1]);
                            $ln_StrtDte  = substr(trim(cleanInputData1($crntRow[2])) == "" ? $gnrlTrnsDteDMYHMS : cleanInputData1($crntRow[2]), 0, 11) . " 00:00:00";
                            $ln_EndDte   = substr(
                                trim(cleanInputData1($crntRow[3])) == "" ? "31-Dec-4000 23:59:59" : cleanInputData1($crntRow[3]),
                                0,
                                11
                            ) . " 23:59:59";
                            $errMsg = "";
                            if ($ln_UserID > 0) {
                                if ($ln_TrnsLnID <= 0) {
                                    $afftctd1 += createTmpltUsr($ln_UserID, $accbTrnsTmpltID, $ln_StrtDte, $ln_EndDte);
                                } else {
                                    $afftctd2 = 0;
                                    $afftctd2 += changeTmpltUsrVldStrDate($ln_TrnsLnID, $ln_StrtDte);
                                    $afftctd2 += changeTmpltUsrVldEndDate($ln_TrnsLnID, $ln_EndDte);
                                    $afftctd1 += ($afftctd2 / 2);
                                }
                            }
                        }
                    }
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Transaction Template Successfully Saved!"
                        . "<br/>" . $afftctd . " Template Line(s) Saved Successfully!"
                        . "<br/>" . $afftctd1 . " Template User(s) Saved Successfully!"
                        . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Transaction Template Successfully Saved!"
                        . "<br/>" . $afftctd . " Template Line(s) Saved Successfully!"
                        . "<br/>" . $afftctd1 . " Template User(s) Saved Successfully!";
                }
                $arr_content['percent']         = 100;
                $arr_content['accbTrnsTmpltID'] = $accbTrnsTmpltID;
                $arr_content['message']         = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            }
        } else {
            if ($vwtyp == 999) {
                //echo "hello";
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=999');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Simplified Transaction Vouchers</span>
				</li>";
                $menuItems  = array("Payment/Expense Vouchers", "Receipts/Income Vouchers", "Fund Management", "Template Setups", "Investment Type Setups");
                $menuImages = array("bills.png", "rcvbls1.jpg", "investments_prdt.png", "templates.jpg", "services_prdt.jpg");
                $menuLinks  = array(
                    "openATab('#allmodules', 'grp=$group&typ=$type&pg=72&vtyp=0&inTmpltTyp=EXPNS');",
                    "openATab('#allmodules', 'grp=$group&typ=$type&pg=72&vtyp=0&inTmpltTyp=INCM');",
                    "openATab('#allmodules', 'grp=$group&typ=$type&pg=70&vtyp=0');",
                    "openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');",
                    "openATab('#allmodules', 'grp=$group&typ=$type&pg=71&vtyp=0');"
                );
                $cntent .= " </ul></div>
                <div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;padding:10px 15px 15px 20px;border:1px solid #ccc;\">
                    <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                    font-weight:normal;\">From here you can perform a quick sale or create full blown invoice documents! The module has the ff areas:</span>
                    </div>
                <p>";
                $grpcntr = 0;
                for ($i = 0; $i < count($menuItems); $i++) {
                    $No = $i + 1;
                    if (($i == 3 || $i == 4) && $canViewTrnsTmplts === false) {
                        continue;
                    } elseif ($i == 0 && test_prmssns($dfltPrvldgs2[0], $mdlNm) === false) {
                        continue;
                    } elseif ($i == 1 && test_prmssns($dfltPrvldgs2[4], $mdlNm) === false) {
                        continue;
                    } elseif ($i == 2 && test_prmssns($dfltPrvldgs2[8], $mdlNm) === false) {
                        continue;
                    }
                    if ($grpcntr == 0) {
                        $cntent .= "<div class=\"row\">";
                    }
                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
                                    <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"$menuLinks[$i]\">
                                        <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
                                        <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
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
            } elseif ($vwtyp == 0 || $vwtyp == 1) {
                $sbmtdTrnsTmpltID = -1;
                if ($vwtyp == 0) {
                    $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=999');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Simplified Transaction Vouchers</span>
                            </li>";
                    echo $cntent . "
                            <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Transaction Templates</span>
                            </li>
                           </ul>
                          </div>";
                    $error          = "";
                    $searchAll      = true;
                    $srchFor        = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                    $srchIn         = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                    $pageNo         = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                    $lmtSze         = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
                    $sortBy         = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                    $qShwUsrOnly    = false;
                    $qShwUnpstdOnly = false;
                    if (isset($_POST['qShwUsrOnly'])) {
                        $qShwUsrOnly = cleanInputData($_POST['qShwUsrOnly']) === "true" ? true : false;
                    }
                    if (isset($_POST['qShwUnpstdOnly'])) {
                        $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                    }
                    if (strpos($srchFor, "%") === false) {
                        $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                        $srchFor = str_replace("%%", "%", $srchFor);
                    }
                    $total = get_Total_Tmplts($srchFor, $srchIn, $orgID);
                    //echo $total;
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } elseif ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx        = $pageNo - 1;
                    $result        = get_Basic_Tmplt($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                    $cntr          = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-3"; ?>
                    <form id='accbTmpltsForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                        <fieldset class="">
                            <legend class="basic_person_lg1" style="color: #003245">TRANSACTION TEMPLATES</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                if ($canAdd === true) {
                                ?>
                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAccbTmpltsForm(-1, 1);">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Template
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAccbTmpltsForm();" style="width:100% !important;">
                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Save
                                        </button>
                                    </div>
                                <?php
                                } else {
                                    $colClassType1 = "col-md-2";
                                    $colClassType2 = "col-md-4";
                                } ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="accbTmpltsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                        echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAccbTmplts(event, '', '#allmodules', 'grp=6&typ=1&pg=7&vtyp=0')">
                                        <input id="accbTmpltsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbTmplts('clear', '#allmodules', 'grp=6&typ=1&pg=7&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbTmplts('', '#allmodules', 'grp=6&typ=1&pg=7&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType2; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbTmpltsSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "");
                                            $srchInsArrys = array("All", "Template Name", "Template Description");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                } ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>>
                                                    <?php echo $srchInsArrys[$z]; ?>
                                                </option>
                                            <?php
                                            } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbTmpltsDsplySze" style="min-width:70px !important;">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "", "");
                                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000, 1000000);
                                            for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                if ($lmtSze == $dsplySzeArry[$y]) {
                                                    $valslctdArry[$y] = "selected";
                                                } else {
                                                    $valslctdArry[$y] = "";
                                                } ?>
                                                <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>>
                                                    <?php echo $dsplySzeArry[$y]; ?>
                                                </option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType1; ?>">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" style="margin: 0px !important;">
                                            <li>
                                                <a href="javascript:getAccbTmplts('previous', '#allmodules', 'grp=6&typ=1&pg=7&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getAccbTmplts('next', '#allmodules', 'grp=6&typ=1&pg=7&vtyp=0');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3" style="padding:0px 1px 0px 15px !important;">
                                    <fieldset class="basic_person_fs">
                                        <table class="table table-striped table-bordered table-responsive" id="accbTmpltsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="max-width:25px;width:25px;">No.</th>
                                                    <th>Template Name</th>
                                                    <?php if ($canDel === true) { ?>
                                                        <th style="max-width:25px;width:25px;">...</th>
                                                    <?php } ?>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <th style="max-width:25px;width:25px;">...</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                while ($row = loc_db_fetch_array($result)) {
                                                    if ($cntr == 0) {
                                                        $sbmtdTrnsTmpltID = (float)$row[0];
                                                    }
                                                    $cntr += 1; ?>
                                                    <tr id="accbTmpltsHdrsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?>
                                                        </td>
                                                        <td class="lovtd"><?php echo $row[1]; ?>
                                                        </td>
                                                        <?php if ($canDel === true) { ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Template" onclick="delAccbTmplts('accbTmpltsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;">
                                                                    <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                                <input type="hidden" id="accbTmpltsHdrsRow<?php echo $cntr; ?>_HdrID" name="accbTmpltsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                                <input type="hidden" id="accbTmpltsHdrsRow<?php echo $cntr; ?>_HdrNm" name="accbTmpltsHdrsRow<?php echo $cntr; ?>_HdrNm" value="<?php echo $row[1]; ?>">
                                                            </td>
                                                        <?php } ?>
                                                        <?php
                                                        if ($canVwRcHstry === true) {
                                                        ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                        echo urlencode(encrypt1(
                                                                                                                                                                                                                            ($row[0] . "|accb.accb_trnsctn_templates_hdr|template_id"),
                                                                                                                                                                                                                            $smplTokenWord1
                                                                                                                                                                                                                        )); ?>');" style="padding:2px !important;">
                                                                    <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        <?php
                                                        } ?>
                                                    </tr>
                                                <?php
                                                } ?>
                                            </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                                <div class="col-md-9" style="padding:0px 15px 0px 1px !important">
                                    <fieldset class="basic_person_fs" style="padding-top:2px !important;">
                                        <div class="container-fluid" id="accbTrnsTmpltsDetailInfo">
                                        <?php
                                    }
                                    $pkID                = isset($_POST['sbmtdTrnsTmpltID']) ? $_POST['sbmtdTrnsTmpltID'] : $sbmtdTrnsTmpltID;
                                    $accbTrnsTmpltID     = -1;
                                    $accbTrnsTmpltName   = "";
                                    $accbTrnsTmpltDesc   = "";
                                    $accbTrnsTmpltDocTyp = "None";
                                    $accbTrnsTmpltEnbld  = "1";
                                    if ($pkID > 0) {
                                        $result1 = get_One_Tmplt($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $accbTrnsTmpltID     = $row1[0];
                                            $accbTrnsTmpltName   = $row1[1];
                                            $accbTrnsTmpltDesc   = $row1[2];
                                            $accbTrnsTmpltDocTyp = $row1[5];
                                            $accbTrnsTmpltEnbld  = $row1[6];
                                        }
                                    } ?>
                                        <div class="row">
                                            <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="accbTrnsTmpltName" class="control-label col-lg-4">Template
                                                            Name:</label>
                                                        <div class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <input type="text" class="form-control" aria-label="..." id="accbTrnsTmpltName" name="accbTrnsTmpltName" value="<?php echo $accbTrnsTmpltName; ?>" style="width:100% !important;">
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <span><?php echo $accbTrnsTmpltName; ?></span>
                                                            <?php
                                                            } ?>
                                                            <input type="hidden" class="form-control" aria-label="..." id="accbTrnsTmpltID" name="accbTrnsTmpltID" value="<?php echo $accbTrnsTmpltID; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="accbTrnsTmpltDocTyp" class="control-label col-lg-4">Template
                                                            Type:</label>
                                                        <div class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="accbTrnsTmpltDocTyp" style="min-width:100% !important;">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "");
                                                                    $dsplySzeArry = array("None", "Expense Voucher", "Income Voucher");
                                                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                        if ($accbTrnsTmpltDocTyp == $dsplySzeArry[$y]) {
                                                                            $valslctdArry[$y] = "selected";
                                                                        } else {
                                                                            $valslctdArry[$y] = "";
                                                                        } ?>
                                                                        <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?>
                                                                        </option>
                                                                    <?php
                                                                    } ?>
                                                                </select>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <span><?php echo $accbTrnsTmpltDocTyp; ?></span>
                                                            <?php
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <label for="accbTrnsTmpltDesc" class="control-label col-lg-4">Description:</label>
                                                        <div class="col-lg-8">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <input type="text" class="form-control" aria-label="..." id="accbTrnsTmpltDesc" name="accbTrnsTmpltDesc" value="<?php echo $accbTrnsTmpltDesc; ?>" style="width:100% !important;">
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <span><?php echo $accbTrnsTmpltDesc; ?></span>
                                                            <?php
                                                            } ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">&nbsp;</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <?php
                                                                    $accbTrnsTmpltEnbldChkd = "";
                                                                    if ($accbTrnsTmpltEnbld == "1") {
                                                                        $accbTrnsTmpltEnbldChkd = "checked=\"true\"";
                                                                    }
                                                                    ?>
                                                                    <input type="checkbox" class="form-check-input" onclick="" id="accbTrnsTmpltEnbld" name="accbTrnsTmpltEnbld" <?php echo $accbTrnsTmpltEnbldChkd; ?>>
                                                                    Enabled?
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                <?php
                                                $nwRowHtml33 = "<tr id=\"accbTmpltsLinesTblRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#accbTmpltsLinesTable tr').index(this));\">
                                                                            <td class=\"lovtd\">New</td>
                                                                            <td class=\"lovtd\"  style=\"\">
                                                                                    <input type=\"text\" class=\"form-control rqrdFld jbDetDesc\" aria-label=\"...\" id=\"accbTmpltsLinesTblRow_WWW123WWW_LineDesc\" name=\"accbTmpltsLinesTblRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'accbTmpltsLinesTblRow_WWW123WWW_LineDesc', 'accbTmpltsLinesTable', 'jbDetDesc');\">
                                                                             </td>
                                                                            <td class=\"lovtd\">
                                                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"accbTmpltsLinesTblRow_WWW123WWW_IncrsDcrs1\" style=\"width:100% !important;\">";
                                                $valslctdArry = array("", "");
                                                $srchInsArrys = array("Increase", "Decrease");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml33 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                }
                                                $nwRowHtml33 .= "</select>
                                                                </td>
                                                                <td class = \"lovtd\">
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbTmpltsLinesTblRow_WWW123WWW_AccountID1\" value=\"-1\" style=\"width:100% !important;\">
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbTmpltsLinesTblRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"accbTmpltsLinesTblRow_WWW123WWW_AccountNm1\" name=\"accbTmpltsLinesTblRow_WWW123WWW_AccountNm1\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTmpltsLinesTblRow_WWW123WWW_AccountID1', 'accbTmpltsLinesTblRow_WWW123WWW_AccountNm1', 'clear', 1, '', function () {

                                                                                                });\">
                                                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                        </label>
                                                                                    </div>
                                                                            </td>";
                                                if ($canDel === true) {
                                                    $nwRowHtml33 .= "<td class=\"lovtd\">
                                                                                    <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Line\" onclick=\"delAccbTmpltTrans('accbTmpltsLinesTblRow__WWW123WWW');\" style=\"padding:2px !important;\" style=\"padding:2px !important;\">
                                                                                        <img src=\"cmn_images/no.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">
                                                                                    </button>
                                                                                    <input type=\"hidden\" id=\"accbTmpltsLinesTblRow_WWW123WWW_HdrID\" name=\"accbTmpltsHdrsRow_WWW123WWW_HdrID\" value=\"\">
                                                                                </td>";
                                                }
                                                if ($canVwRcHstry === true) {
                                                    $nwRowHtml33 .= "<td class=\"lovtd\">&nbsp;</td>";
                                                }
                                                $nwRowHtml33 .= "</tr>";
                                                $nwRowHtml33 = urlencode($nwRowHtml33);
                                                $nwRowHtml1  = $nwRowHtml33; ?>
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="col-md-8" style="padding:0px 0px 0px 0px !important;float:left;">
                                                        <?php if ($canEdt) { ?>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="insertNewAccbTmpltsRows('accbTmpltsLinesTable', 0, '<?php echo $nwRowHtml1; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Transaction Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Add New Transaction Line
                                                            </button>
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getOneAccbTmpltsForm(<?php echo $pkID ?>, 1);"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                <table class="table table-striped table-bordered table-responsive" id="accbTmpltsLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="max-width:25px;width:25px;text-align: center;">No.</th>
                                                            <th>Transaction Description</th>
                                                            <th>Increase/ Decrease</th>
                                                            <th>Transaction Account</th>
                                                            <?php
                                                            if ($canDel === true) {
                                                            ?>
                                                                <th style="max-width:25px;width:25px;">...</th>
                                                            <?php
                                                            } ?>
                                                            <?php
                                                            if ($canVwRcHstry === true) {
                                                            ?>
                                                                <th style="max-width:25px;width:25px;">...</th>
                                                            <?php
                                                            } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $cntr    = 0;
                                                        $curIdx  = 0;
                                                        $result2 = get_One_Tmplt_Trns($pkID);
                                                        while ($row2 = loc_db_fetch_array($result2)) {
                                                            $trsctnLineID   = (float)$row2[0];
                                                            $trsctnLineDesc = $row2[4];
                                                            $trnsIncrsDcrs1 = $row2[1] == "I" ? "Increase" : "Decrease";
                                                            $trsctnAcntID   = $row2[5];
                                                            $trsctnAcntNm   = $row2[2] . "." . $row2[3];
                                                            $cntr += 1; ?>
                                                            <tr id="accbTmpltsLinesTblRow_<?php echo $cntr; ?>">
                                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?>
                                                                </td>
                                                                <td class="lovtd" style="">
                                                                    <?php
                                                                    if ($canEdt === true) {
                                                                    ?>
                                                                        <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="accbTmpltsLinesTblRow<?php echo $cntr; ?>_LineDesc" name="accbTmpltsLinesTblRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" onkeypress="gnrlFldKeyPress(event, 'accbTmpltsUsersTblRow<?php echo $cntr; ?>_LineDesc', 'oneJrnlBatchEditLinesTable', 'jbDetDesc');">
                                                                    <?php
                                                                    } else { ?>
                                                                        <span><?php echo $trsctnLineDesc; ?></span>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="lovtd">
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbTmpltsLinesTblRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
                                                                        <?php
                                                                        $valslctdArry = array("", "");
                                                                        $srchInsArrys = array("Increase", "Decrease");
                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                            if ($trnsIncrsDcrs1 == $srchInsArrys[$z]) {
                                                                                $valslctdArry[$z] = "selected";
                                                                            } ?>
                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>>
                                                                                <?php echo $srchInsArrys[$z]; ?>
                                                                            </option>
                                                                        <?php
                                                                        } ?>
                                                                    </select>
                                                                </td>
                                                                <td class="lovtd">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="accbTmpltsLinesTblRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="accbTmpltsLinesTblRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                    <?php
                                                                    if ($canEdt === true) {
                                                                    ?>
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input type="text" class="form-control" aria-label="..." id="accbTmpltsLinesTblRow<?php echo $cntr; ?>_AccountNm1" name="accbTmpltsLinesTblRow<?php echo $cntr; ?>_AccountNm1" value="<?php echo $trsctnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbTmpltsLinesTblRow<?php echo $cntr; ?>_AccountID1', 'accbTmpltsLinesTblRow<?php echo $cntr; ?>_AccountNm1', 'clear', 1, '', function () {

                                                                                    });">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    <?php
                                                                    } else { ?>
                                                                        <span><?php echo $trsctnAcntNm; ?></span>
                                                                    <?php } ?>
                                                                </td>
                                                                <?php
                                                                if ($canDel === true) {
                                                                ?>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delAccbTmpltTrans('accbTmpltsLinesTblRow_<?php echo $cntr; ?>');" style="padding:2px !important;">
                                                                            <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                        <input type="hidden" id="accbTmpltsLinesTblRow<?php echo $cntr; ?>_HdrID" name="accbTmpltsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $trsctnLineID; ?>">
                                                                    </td>
                                                                <?php
                                                                } ?>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                ?>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                echo urlencode(encrypt1(
                                                                                                                                                                                                                                    ($trsctnLineID . "|accb.accb_trnsctn_templates_det|detail_id"),
                                                                                                                                                                                                                                    $smplTokenWord1
                                                                                                                                                                                                                                )); ?>');" style="padding:2px !important;">
                                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                <?php
                                                                } ?>
                                                            </tr>
                                                        <?php
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="padding:5px 0px 0px 0px !important;">
                                                <?php
                                                $nwRowHtml33 = "<tr id=\"accbTmpltsUsersTblRow__WWW123WWW\">
                                                                                <td class=\"lovtd\">New</td>
                                                                                <td class=\"lovtd\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbTmpltsUsersTblRow_WWW123WWW_UserID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbTmpltsUsersTblRow_WWW123WWW_PrsnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbTmpltsUsersTblRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                    <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"accbTmpltsUsersTblRow_WWW123WWW_UserName\" name=\"accbTmpltsUsersTblRow_WWW123WWW_UserName\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Users', '', '', '', 'radio', true, '', 'accbTmpltsUsersTblRow_WWW123WWW_UserID', 'accbTmpltsUsersTblRow_WWW123WWW_UserName', 'clear', 1, '', function () {});\">
                                                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </td>
                                                                                <td class=\"lovtd\">&nbsp;</td>
                                                                                <td class=\"lovtd\">
                                                                                    <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                                        <input class=\"form-control\" size=\"16\" type=\"text\" id=\"accbTmpltsUsersTblRow_WWW123WWW_StrtDte\" value=\"" . $gnrlTrnsDteDMYHMS . "\" readonly=\"true\" style=\"width:100%;\">
                                                                                        <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                                        <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                    </div>
                                                                                </td>
                                                                                <td class=\"lovtd\">
                                                                                    <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                                        <input class=\"form-control\" size=\"16\" type=\"text\" id=\"accbTmpltsUsersTblRow_WWW123WWW_EndDte\" value=\"\" readonly=\"true\" style=\"width:100%;\">
                                                                                        <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                                        <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                    </div>
                                                                                </td>";
                                                if ($canVwRcHstry === true) {
                                                    $nwRowHtml33 .= "<td class=\"lovtd\">&nbsp;</td>";
                                                }
                                                $nwRowHtml33 .= "</tr>";
                                                $nwRowHtml33 = urlencode($nwRowHtml33);
                                                $nwRowHtml1  = $nwRowHtml33; ?>
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="col-md-8" style="padding:0px 0px 0px 0px !important;float:left;">
                                                        <?php if ($canEdt) { ?>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="insertNewAccbTmpltUsrsRows('accbTmpltsUsersTable', 0, '<?php echo $nwRowHtml1; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Transaction Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Add New User
                                                            </button>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                <table class="table table-striped table-bordered table-responsive" id="accbTmpltsUsersTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="max-width:25px;width:25px;text-align: center;">No.</th>
                                                            <th>User Name</th>
                                                            <th>Person</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <?php
                                                            if ($canVwRcHstry === true) {
                                                            ?>
                                                                <th style="max-width:25px;width:25px;">...</th>
                                                            <?php
                                                            } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $cntr    = 0;
                                                        $curIdx  = 0;
                                                        $result3 = get_One_Tmplt_Usrs($pkID);
                                                        while ($row3 = loc_db_fetch_array($result3)) {
                                                            $trsctnLineID  = (float)$row3[4];
                                                            $trsctnUsrNm   = $row3[0];
                                                            $trsctnPrsnNm  = $row3[1];
                                                            $trsctnPrsnID  = $row3[3];
                                                            $trsctnUsrID   = $row3[2];
                                                            $trsctnStrtDte = $row3[5];
                                                            $trsctnEndDte  = $row3[6];
                                                            $cntr += 1; ?>
                                                            <tr id="accbTmpltsUsersTblRow_<?php echo $cntr; ?>">
                                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?>
                                                                </td>
                                                                <td class="lovtd">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="accbTmpltsUsersTblRow<?php echo $cntr; ?>_UserID" value="<?php echo $trsctnUsrID; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="accbTmpltsUsersTblRow<?php echo $cntr; ?>_PrsnID" value="<?php echo $trsctnPrsnID; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="accbTmpltsUsersTblRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                    <?php
                                                                    if ($canEdt === true) {
                                                                    ?>
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input type="text" class="form-control" aria-label="..." id="accbTmpltsUsersTblRow<?php echo $cntr; ?>_UserName" name="accbTmpltsUsersTblRow<?php echo $cntr; ?>_UserName" value="<?php echo $trsctnUsrNm; ?>" readonly="true" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Users', '', '', '', 'radio', true, '', 'accbTmpltsUsersTblRow<?php echo $cntr; ?>_UserID', 'accbTmpltsUsersTblRow<?php echo $cntr; ?>_UserName', 'clear', 1, '', function () {});">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    <?php
                                                                    } else { ?>
                                                                        <span><?php echo $trsctnUsrNm; ?></span>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="lovtd" style=""><span><?php echo $trsctnPrsnNm; ?></span>
                                                                </td>
                                                                <td class="lovtd">
                                                                    <?php
                                                                    if ($canEdt === true) {
                                                                    ?>
                                                                        <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                            <input class="form-control" size="16" type="text" id="accbTmpltsUsersTblRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $trsctnStrtDte; ?>" readonly="true" style="width:100%;">
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        </div>
                                                                    <?php
                                                                    } else { ?>
                                                                        <span><?php echo $trsctnStrtDte; ?></span>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="lovtd">
                                                                    <?php
                                                                    if ($canEdt === true) {
                                                                    ?>
                                                                        <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                            <input class="form-control" size="16" type="text" id="accbTmpltsUsersTblRow<?php echo $cntr; ?>_EndDte" value="<?php echo $trsctnEndDte; ?>" readonly="true" style="width:100%;">
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        </div>
                                                                    <?php
                                                                    } else { ?>
                                                                        <span><?php echo $trsctnEndDte; ?></span>
                                                                    <?php } ?>
                                                                </td>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                ?>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                echo urlencode(encrypt1(
                                                                                                                                                                                                                                    ($trsctnLineID . "|accb.accb_trnsctn_templates_usrs|row_id"),
                                                                                                                                                                                                                                    $smplTokenWord1
                                                                                                                                                                                                                                )); ?>');" style="padding:2px !important;">
                                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                <?php
                                                                } ?>
                                                            </tr>
                                                        <?php
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <?php if ($vwtyp == 0) { ?>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>
                    </form>
<?php
                                        }
                                    } elseif ($vwtyp == 10) {
                                        //Expense Vouchers
                                        //Tabular Display
                                    } elseif ($vwtyp == 101) {
                                        //Expense Vouchers
                                        //One Form Display
                                    } elseif ($vwtyp == 11) {
                                        //Income Vouchers
                                        //Tabular Display
                                    } elseif ($vwtyp == 111) {
                                        //Income Vouchers
                                        //One Form Display
                                    } elseif ($vwtyp == 12) {
                                        require "fund_mngmnt.php";
                                    }
                                }
                            }
                        }
