<?php
$canAdd = test_prmssns($dfltPrvldgs[91], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[92], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[93], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$srcMenu = isset($_POST['srcMenu']) ? cleanInputData($_POST['srcMenu']) : "";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                //Delete Customer/Supplier
                $cstmrNm = isset($_POST['cstmrNm']) ? (int) (cleanInputData($_POST['cstmrNm'])) : -1;
                $pKeyID = isset($_POST['pKeyID']) ? (int) (cleanInputData($_POST['pKeyID'])) : -1;
                echo deleteCstmr($pKeyID, $cstmrNm);
            } else if ($actyp == 2) {
                //Delete Customer/Supplier Site
                $siteNm = isset($_POST['siteNm']) ? (int) (cleanInputData($_POST['siteNm'])) : -1;
                $pKeyID = isset($_POST['pKeyID']) ? (int) (cleanInputData($_POST['pKeyID'])) : -1;
                echo deleteCstmrSite($pKeyID, $siteNm);
            } else if ($actyp == 5) {
                /* Delete Attachment */
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $docTrnsNum = isset($_POST['docTrnsNum']) ? cleanInputData($_POST['docTrnsNum']) : -1;
                if ($canEdt) {
                    echo deleteCstmrDoc($attchmentID, $docTrnsNum);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Customers and Services
                header("content-type:application/json");
                $sbmtdCstmrSpplrID = isset($_POST['sbmtdCstmrSpplrID']) ? (float) cleanInputData($_POST['sbmtdCstmrSpplrID']) : -1;
                $cstmrSpplrNm = isset($_POST['cstmrSpplrNm']) ? cleanInputData($_POST['cstmrSpplrNm']) : '';
                $cstmrSpplrDesc = isset($_POST['cstmrSpplrDesc']) ? cleanInputData($_POST['cstmrSpplrDesc']) : '';
                $cstmrSpplrType = isset($_POST['cstmrSpplrType']) ? cleanInputData($_POST['cstmrSpplrType']) : '';
                $cstmrSpplrClsfctn = isset($_POST['cstmrSpplrClsfctn']) ? cleanInputData($_POST['cstmrSpplrClsfctn']) : "";
                $cstmrSpplrLnkdPrsnID = isset($_POST['cstmrSpplrLnkdPrsnID']) ? (float) cleanInputData($_POST['cstmrSpplrLnkdPrsnID']) : -1;
                $cstmrSpplrDOB = isset($_POST['cstmrSpplrDOB']) ? cleanInputData($_POST['cstmrSpplrDOB']) : "";
                $cstmrSpplrGender = isset($_POST['cstmrSpplrGender']) ? cleanInputData($_POST['cstmrSpplrGender']) : '';
                $isCstmrEnbld = isset($_POST['isCstmrEnbld']) ? cleanInputData($_POST['isCstmrEnbld']) : 'NO';
                $cstmrLbltyAcntID = isset($_POST['cstmrLbltyAcntID']) ? (float) cleanInputData($_POST['cstmrLbltyAcntID']) : -1;
                $cstmrRcvblsAcntID = isset($_POST['cstmrRcvblsAcntID']) ? (float) cleanInputData($_POST['cstmrRcvblsAcntID']) : -1;
                $cstmrCmpnyBrandNm = isset($_POST['cstmrCmpnyBrandNm']) ? cleanInputData($_POST['cstmrCmpnyBrandNm']) : "";
                $cstmrOrgType = isset($_POST['cstmrOrgType']) ? cleanInputData($_POST['cstmrOrgType']) : "";
                $cstmrRegNum = isset($_POST['cstmrRegNum']) ? cleanInputData($_POST['cstmrRegNum']) : "";
                $cstmrDateIncprtd = isset($_POST['cstmrDateIncprtd']) ? cleanInputData($_POST['cstmrDateIncprtd']) : "";
                $cstmrIncprtnType = isset($_POST['cstmrIncprtnType']) ? cleanInputData($_POST['cstmrIncprtnType']) : "";
                $cstmrVatNumber = isset($_POST['cstmrVatNumber']) ? cleanInputData($_POST['cstmrVatNumber']) : "";
                $cstmrTinNumber = isset($_POST['cstmrTinNumber']) ? cleanInputData($_POST['cstmrTinNumber']) : "";
                $cstmrSsnitRegNum = isset($_POST['cstmrSsnitRegNum']) ? cleanInputData($_POST['cstmrSsnitRegNum']) : "";
                $cstmrListOfSrvcs = isset($_POST['cstmrListOfSrvcs']) ? cleanInputData($_POST['cstmrListOfSrvcs']) : "";
                $cstmrDescSrvcs = isset($_POST['cstmrDescSrvcs']) ? cleanInputData($_POST['cstmrDescSrvcs']) : "";
                $cstmrNumEmployees = isset($_POST['cstmrNumEmployees']) ? (float) cleanInputData($_POST['cstmrNumEmployees']) : 0;
                $isCstmrEnbldVal = ($isCstmrEnbld == "NO") ? "0" : "1";
                if ($cstmrSpplrDOB != "") {
                    $cstmrSpplrDOB = cnvrtDMYToYMD($cstmrSpplrDOB);
                }
                if ($cstmrDateIncprtd != "") {
                    $cstmrDateIncprtd = cnvrtDMYToYMD($cstmrDateIncprtd);
                }
                $errMsg = "";
                $oldCstmrID = getCstmrID($cstmrSpplrNm, $orgID);
                if ($cstmrSpplrNm != "" && $cstmrSpplrDesc != "" && ($oldCstmrID <= 0 || $oldCstmrID == $sbmtdCstmrSpplrID)) {
                    if ($sbmtdCstmrSpplrID <= 0) {
                        createCstmr(
                            $cstmrSpplrNm,
                            $cstmrSpplrDesc,
                            $cstmrSpplrClsfctn,
                            $cstmrSpplrType,
                            $orgID,
                            $cstmrLbltyAcntID,
                            $cstmrRcvblsAcntID,
                            $cstmrSpplrLnkdPrsnID,
                            $cstmrSpplrGender,
                            $cstmrSpplrDOB,
                            $isCstmrEnbldVal,
                            $cstmrCmpnyBrandNm,
                            $cstmrOrgType,
                            $cstmrRegNum,
                            $cstmrDateIncprtd,
                            $cstmrIncprtnType,
                            $cstmrVatNumber,
                            $cstmrTinNumber,
                            $cstmrSsnitRegNum,
                            $cstmrNumEmployees,
                            $cstmrDescSrvcs,
                            $cstmrListOfSrvcs
                        );
                        $sbmtdCstmrSpplrID = getCstmrID($cstmrSpplrNm, $orgID);
                        $cstmrSiteCnt = (int) getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_supplier_id", "count(cust_sup_site_id)", $sbmtdCstmrSpplrID);
                        if ($cstmrSiteCnt <= 0) {
                            createCstmrSite(
                                $sbmtdCstmrSpplrID,
                                "To be Specified",
                                "",
                                "",
                                "HEAD OFFICE-" . $cstmrSpplrNm,
                                "HEAD OFFICE-" . $cstmrSpplrNm,
                                "",
                                "",
                                "",
                                -1,
                                -1,
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "1",
                                "",
                                $fnccurid
                            );
                        }
                    } else {
                        updateCstmr(
                            $sbmtdCstmrSpplrID,
                            $cstmrSpplrNm,
                            $cstmrSpplrDesc,
                            $cstmrSpplrClsfctn,
                            $cstmrSpplrType,
                            $orgID,
                            $cstmrLbltyAcntID,
                            $cstmrRcvblsAcntID,
                            $cstmrSpplrLnkdPrsnID,
                            $cstmrSpplrGender,
                            $cstmrSpplrDOB,
                            $isCstmrEnbldVal,
                            $cstmrCmpnyBrandNm,
                            $cstmrOrgType,
                            $cstmrRegNum,
                            $cstmrDateIncprtd,
                            $cstmrIncprtnType,
                            $cstmrVatNumber,
                            $cstmrTinNumber,
                            $cstmrSsnitRegNum,
                            $cstmrNumEmployees,
                            $cstmrDescSrvcs,
                            $cstmrListOfSrvcs
                        );
                        $cstmrSiteCnt = (int) getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_supplier_id", "count(cust_sup_site_id)", $sbmtdCstmrSpplrID);
                        if ($cstmrSiteCnt <= 0) {
                            createCstmrSite(
                                $sbmtdCstmrSpplrID,
                                "To be Specified",
                                "",
                                "",
                                "HEAD OFFICE-" . $cstmrSpplrNm,
                                "HEAD OFFICE-" . $cstmrSpplrNm,
                                "",
                                "",
                                "",
                                -1,
                                -1,
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "1",
                                "",
                                $fnccurid
                            );
                        }
                    }
                    $nwImgLoc = "";
                    uploadDaImageCstmr($sbmtdCstmrSpplrID, $nwImgLoc);
                    $arr_content['percent'] = 100;
                    $arr_content['cstmrid'] = (float) $sbmtdCstmrSpplrID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Customer/Supplier Successfully Saved!<br/>" . $nwImgLoc;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['cstmrid'] = (float) $sbmtdCstmrSpplrID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                //Customer/Supplier Sites
                header("content-type:application/json");
                $sbmtdCstmrSpplrID = isset($_POST['sbmtdCstmrSpplrID']) ? (float) cleanInputData($_POST['sbmtdCstmrSpplrID']) : -1;
                $sbmtdSiteID = isset($_POST['sbmtdSiteID']) ? (float) cleanInputData($_POST['sbmtdSiteID']) : -1;
                $csSiteName = isset($_POST['csSiteName']) ? cleanInputData($_POST['csSiteName']) : '';
                $csSiteDesc = isset($_POST['csSiteDesc']) ? cleanInputData($_POST['csSiteDesc']) : '';
                $csSiteBllngAddress = isset($_POST['csSiteBllngAddress']) ? cleanInputData($_POST['csSiteBllngAddress']) : '';
                $csSiteShpngAddress = isset($_POST['csSiteShpngAddress']) ? cleanInputData($_POST['csSiteShpngAddress']) : "";
                $csSiteCntctPrsn = isset($_POST['csSiteCntctPrsn']) ? cleanInputData($_POST['csSiteCntctPrsn']) : "";
                $csSiteCntctNos = isset($_POST['csSiteCntctNos']) ? cleanInputData($_POST['csSiteCntctNos']) : "";
                $csSiteEmailAdrs = isset($_POST['csSiteEmailAdrs']) ? cleanInputData($_POST['csSiteEmailAdrs']) : '';
                $isCsSiteEnbld = isset($_POST['isCsSiteEnbld']) ? cleanInputData($_POST['isCsSiteEnbld']) : 'NO';
                $csSiteWthTxCodeID = isset($_POST['csSiteWthTxCodeID']) ? (float) cleanInputData($_POST['csSiteWthTxCodeID']) : -1;
                $csSiteDscntCodeID = isset($_POST['csSiteDscntCodeID']) ? (float) cleanInputData($_POST['csSiteDscntCodeID']) : -1;
                $csSiteCountry = isset($_POST['csSiteCountry']) ? cleanInputData($_POST['csSiteCountry']) : "";
                $csSiteIDType = isset($_POST['csSiteIDType']) ? cleanInputData($_POST['csSiteIDType']) : "";
                $csSiteIDNum = isset($_POST['csSiteIDNum']) ? cleanInputData($_POST['csSiteIDNum']) : "";
                $csSiteDateIsd = isset($_POST['csSiteDateIsd']) ? cleanInputData($_POST['csSiteDateIsd']) : "";
                $csSiteExpryDate = isset($_POST['csSiteExpryDate']) ? cleanInputData($_POST['csSiteExpryDate']) : "";
                $csSiteOtherInfo = isset($_POST['csSiteOtherInfo']) ? cleanInputData($_POST['csSiteOtherInfo']) : "";
                $csSiteBankNm = isset($_POST['csSiteBankNm']) ? cleanInputData($_POST['csSiteBankNm']) : "";
                $csSiteBrnchNm = isset($_POST['csSiteBrnchNm']) ? cleanInputData($_POST['csSiteBrnchNm']) : "";
                $csSiteAcntNum = isset($_POST['csSiteAcntNum']) ? cleanInputData($_POST['csSiteAcntNum']) : "";
                $csSiteCrncy = isset($_POST['csSiteCrncy']) ? cleanInputData($_POST['csSiteCrncy']) : "";
                $accntCurID = getPssblValID($csSiteCrncy, getLovID("Currencies"));
                $csSiteSwftCode = isset($_POST['csSiteSwftCode']) ? cleanInputData($_POST['csSiteSwftCode']) : "";
                $csSiteIbanCode = isset($_POST['csSiteIbanCode']) ? cleanInputData($_POST['csSiteIbanCode']) : "";
                $isCsSiteEnbldVal = ($isCsSiteEnbld == "NO") ? "0" : "1";
                $errMsg = "";
                $oldSiteID = getCstmrSiteID($csSiteName, $sbmtdCstmrSpplrID);
                if ($csSiteName != "" && $csSiteCntctPrsn != "" && ($oldSiteID <= 0 || $oldSiteID == $sbmtdSiteID)) {
                    if ($sbmtdSiteID <= 0) {
                        createCstmrSite(
                            $sbmtdCstmrSpplrID,
                            $csSiteCntctPrsn,
                            $csSiteCntctNos,
                            $csSiteEmailAdrs,
                            $csSiteName,
                            $csSiteDesc,
                            $csSiteBankNm,
                            $csSiteBrnchNm,
                            $csSiteAcntNum,
                            $csSiteWthTxCodeID,
                            $csSiteDscntCodeID,
                            $csSiteBllngAddress,
                            $csSiteShpngAddress,
                            $csSiteSwftCode,
                            $csSiteCountry,
                            $csSiteIDType,
                            $csSiteIDNum,
                            $csSiteDateIsd,
                            $csSiteExpryDate,
                            $csSiteOtherInfo,
                            $isCsSiteEnbldVal,
                            $csSiteIbanCode,
                            $accntCurID
                        );
                        $sbmtdSiteID = getCstmrSiteID($csSiteName, $sbmtdCstmrSpplrID);
                    } else {
                        updateCstmrSite(
                            $sbmtdSiteID,
                            $sbmtdCstmrSpplrID,
                            $csSiteCntctPrsn,
                            $csSiteCntctNos,
                            $csSiteEmailAdrs,
                            $csSiteName,
                            $csSiteDesc,
                            $csSiteBankNm,
                            $csSiteBrnchNm,
                            $csSiteAcntNum,
                            $csSiteWthTxCodeID,
                            $csSiteDscntCodeID,
                            $csSiteBllngAddress,
                            $csSiteShpngAddress,
                            $csSiteSwftCode,
                            $csSiteCountry,
                            $csSiteIDType,
                            $csSiteIDNum,
                            $csSiteDateIsd,
                            $csSiteExpryDate,
                            $csSiteOtherInfo,
                            $isCsSiteEnbldVal,
                            $csSiteIbanCode,
                            $accntCurID
                        );
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['cstmrsiteid'] = (float) $sbmtdSiteID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Customer/Supplier Site Successfully Saved!";
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['cstmrsiteid'] = (float) $sbmtdSiteID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 20) {
                //Upload Attachement
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdAccbCstmrsID = isset($_POST['sbmtdAccbCstmrsID']) ? cleanInputData($_POST['sbmtdAccbCstmrsID']) : -1;
                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdAccbCstmrsID;
                if ($attchmentID > 0) {
                    uploadDaCstmrDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewJrnlBatchDocID();
                    createCstmrDoc($attchmentID, $pkID, $docCtrgrName, "");
                    uploadDaCstmrDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/FirmsDocs/" . $nwImgLoc;
                    $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                    if (file_exists($doc_src)) {
                        //file exists!
                    } else {
                        //file does not exist.
                        $doc_src_encrpt = "None";
                    }
                    $arr_content['crptpath'] = $doc_src_encrpt;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                }
                echo json_encode($arr_content);
                exit();
            }
        } else {
            if ($vwtyp == 0) {
                //Customers/Suppliers
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Business Partners/Firms</span>
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
                $qShwUsrOnly = false;
                $qShwUnpstdOnly = false;
                if (isset($_POST['qShwUsrOnly'])) {
                    $qShwUsrOnly = cleanInputData($_POST['qShwUsrOnly']) === "true" ? true : false;
                }
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Basic_CstmrTtl($srchFor, $srchIn);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_Basic_Cstmr($srchFor, $srchIn, $curIdx, $lmtSze);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-4";
?>
                    <form id='allCstmrsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-5";
                            ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="allCstmrsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                    echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                    ?>" onkeyup="enterKeyFuncAllCstmrs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <input id="allCstmrsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCstmrs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCstmrs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allCstmrsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "");
                                        $srchInsArrys = array("Customer/Supplier Description", "Customer/Supplier Name", "Customer/Supplier Type", "Linked Person");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allCstmrsDsplySze" style="min-width:70px !important;">
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
                                            <a class="rhopagination" href="javascript:getAllCstmrs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllCstmrs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Next">
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
                                    <button type="button" class="btn btn-default btn-sm" onclick="getCstmrSpplrForm(-1, 'View Customer/Supplier', 'ShowDialog', function () {
                                                getAllCstmrs('', '#allmodules', 'grp=6&typ=1&pg=13&vtyp=0');
                                            });">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Vendor/Client
                                    </button>
                                <?php } ?>
                                <?php if ($canAdd) { ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="">
                                        <img src="cmn_images/groupings.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Import Defined Persons
                                    </button>
                                <?php } ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="">
                                    <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Export Clients/Vendors
                                </button>
                                <?php if ($canAdd) { ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Import Clients/Vendors
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allCstmrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Vendor/Client Name</th>
                                            <th>Type / Classification / Description</th>
                                            <th>Linked Person</th>
                                            <th>Establishment/Birth Date</th>
                                            <th>No. of Sites</th>
                                            <th>Default Liability Account</th>
                                            <th>Default Receivable Account</th>
                                            <th style="text-align: center;">Enabled?</th>
                                            <th>&nbsp;</th>
                                            <?php if ($canDel === true) { ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            $cntr += 1;
                                        ?>
                                            <tr id="allCstmrsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd">
                                                    <?php echo $row[1]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allCstmrsRow<?php echo $cntr; ?>_CstmrID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd"><?php echo str_replace("()", "", $row[4] . " - " . $row[3] . " (" . $row[2] . ")"); ?></td>
                                                <td class="lovtd"><?php echo $row[24]; ?></td>
                                                <td class="lovtd"><?php echo $row[10]; ?></td>
                                                <td class="lovtd" style="text-align:center;"><?php echo $row[23]; ?></td>
                                                <td class="lovtd"><?php echo $row[25]; ?></td>
                                                <td class="lovtd"><?php echo $row[26]; ?></td>
                                                <td class="lovtd" style="text-align: center;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($row[11] == "1") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <div class="form-group form-group-sm">
                                                        <div class="form-check" style="font-size: 12px !important;">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" id="allCstmrsRow<?php echo $cntr; ?>_IsEnabled" name="allCstmrsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?> disabled="true">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getCstmrSpplrForm(<?php echo $row[0]; ?>, 'View Customer/Supplier', 'ShowDialog', function () {
                                                                getAllCstmrs('', '#allmodules', 'grp=6&typ=1&pg=13&vtyp=0');
                                                            });" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canDel === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delCstmrs('allCstmrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Trading Partner">
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
                    </form>
                <?php
                }
            } else if ($vwtyp == 1) {
                //New Customer/Client Form
                //$canEdt = test_prmssns($dfltPrvldgs[49], $mdlNm) || test_prmssns($dfltPrvldgs[50], $mdlNm);
                $sbmtdCstmrSpplrID = isset($_POST['sbmtdCstmrSpplrID']) ? (float)cleanInputData($_POST['sbmtdCstmrSpplrID']) : -1;

                $cstmrSpplrNm = "";
                $cstmrSpplrDesc = "";

                $cstmrSpplrType = "";
                $cstmrSpplrClsfctn = "";
                $cstmrSpplrLnkdPrsn = "";
                $cstmrSpplrLnkdPrsnID = -1;
                $cstmrSpplrGender = "Not Applicable";
                $cstmrSpplrDOB = "";
                $cstmrLbltyAcntID = get_DfltPyblAcnt($orgID);
                $cstmrLbltyAcntNm =  getAccntNum($cstmrLbltyAcntID) . "." . getAccntName($cstmrLbltyAcntID);
                $cstmrRcvblsAcntID = get_DfltRcvblAcnt($orgID);
                $cstmrRcvblsAcntNm =  getAccntNum($cstmrRcvblsAcntID) . "." . getAccntName($cstmrRcvblsAcntID);
                $isEnbld = "Yes";
                $cstmrCmpnyBrandNm = "";
                $cstmrOrgType = "";
                $cstmrRegNum = "";
                $cstmrDateIncprtd = "";
                $cstmrIncprtnType = "";
                $cstmrVatNumber = "";
                $cstmrTinNumber = "";
                $cstmrSsnitRegNum = "";
                $cstmrNumEmployees = "";

                $cstmrDescSrvcs = "";
                $cstmrListOfSrvcs = "";
                $nwFileName = "";
                $oldFileNm = "";
                $extension = "png";
                if ($sbmtdCstmrSpplrID > 0) {
                    $result = get_OneCstmr($sbmtdCstmrSpplrID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdCstmrSpplrID = (float) $row[0];
                        $cstmrSpplrNm = $row[1];
                        $cstmrSpplrDesc = $row[2];

                        $cstmrSpplrType = $row[4];
                        $cstmrSpplrClsfctn = $row[3];
                        $cstmrSpplrLnkdPrsn = $row[22];
                        $cstmrSpplrLnkdPrsnID = (float) $row[7];
                        $cstmrSpplrGender = $row[8];
                        $cstmrSpplrDOB = $row[9];
                        $cstmrLbltyAcntID = (float) $row[5];
                        $cstmrLbltyAcntNm = $row[23];
                        $cstmrRcvblsAcntID = (float) $row[6];
                        $cstmrRcvblsAcntNm = $row[24];
                        $isEnbld = ($row[10] == "1") ? "Yes" : "No";
                        $cstmrCmpnyBrandNm = $row[11];
                        $cstmrOrgType = $row[12];
                        $cstmrRegNum = $row[13];
                        $cstmrDateIncprtd = $row[14];
                        $cstmrIncprtnType = $row[15];
                        $cstmrVatNumber = $row[16];
                        $cstmrTinNumber = $row[17];
                        $cstmrSsnitRegNum = $row[18];
                        $cstmrNumEmployees = (float) $row[19];

                        $cstmrDescSrvcs = $row[20];
                        $cstmrListOfSrvcs = $row[21];

                        $temp = explode(".", $row[25]);
                        $oldFileNm = $temp[0];
                        $extension = end($temp);
                        if ($extension == "") {
                            $extension = "png";
                        }
                        $nwFileName = encrypt1($row[25], $smplTokenWord1) . "." . $extension;
                    }
                }
                $ftp_src = $ftp_base_db_fldr . "/Cstmr/" . $sbmtdCstmrSpplrID . "." . $extension;
                if ($sbmtdCstmrSpplrID <= 0) {
                    $ftp_src = $fldrPrfx . 'cmn_images/actions_document_preview.png';
                    $nwFileName = "-1.png";
                }
                $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                if (file_exists($ftp_src)) {
                    copy("$ftp_src", "$fullPemDest");
                } else if (!file_exists($fullPemDest)) {
                    $ftp_src = $fldrPrfx . 'cmn_images/actions_document_preview.png';
                    copy("$ftp_src", "$fullPemDest");
                }
                $nwFileName = $tmpDest . $nwFileName;
                ?>
                <form class="form-horizontal" id='vaultCstmrStpForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0px 15px !important;">
                            <div class="col-md-4" style="padding: 0px 5px 0px 5px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="cstmrSpplrNm" class="control-label">Number / Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <div class="col-md-12" style="padding: 0px 0px 0px 0px !important;">
                                                <input type="number" name="sbmtdCstmrSpplrID" id="sbmtdCstmrSpplrID" class="form-control" value="<?php echo $sbmtdCstmrSpplrID; ?>" readonly="true">
                                            </div>
                                            <div class="col-md-12" style="padding: 0px 0px 0px 0px !important;">
                                                <?php if ($canEdt === true) { ?>
                                                    <input type="text" name="cstmrSpplrNm" id="cstmrSpplrNm" class="form-control rqrdFld" value="<?php echo $cstmrSpplrNm; ?>" style="width:100% !important;">
                                                <?php } else { ?>
                                                    <span><?php echo $cstmrSpplrNm; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="cstmrSpplrDesc" class="control-label">Description:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>
                                                <textarea rows="3" name="cstmrSpplrDesc" id="cstmrSpplrDesc" class="form-control rqrdFld"><?php echo $cstmrSpplrDesc; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $cstmrSpplrDesc; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="cstmrSpplrType" class="control-label">Type:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>
                                                <select class="form-control rqrdFld" id="cstmrSpplrType" onchange="">
                                                    <?php
                                                    $valslctdArry = array("", "", "");
                                                    $valuesArrys = array("Customer", "Supplier", "Customer/Supplier");
                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($cstmrSpplrType == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                    ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $cstmrSpplrType; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" style="padding: 0px 5px 0px 5px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="cstmrSpplrClsfctn" class="control-label">Classification:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="cstmrSpplrClsfctn" value="<?php echo $cstmrSpplrClsfctn; ?>" readonly="">
                                                    <label id="cstmrSpplrClsfctnLbl" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer Classifications', '', '', '', 'radio', true, '', 'cstmrSpplrClsfctn', '', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $cstmrSpplrClsfctn; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="cstmrSpplrLnkdPrsn" class="control-label">Linked Person:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="cstmrSpplrLnkdPrsn" value="<?php echo $cstmrSpplrLnkdPrsn; ?>" readonly="">
                                                    <input type="hidden" id="cstmrSpplrLnkdPrsnID" value="<?php echo $cstmrSpplrLnkdPrsnID; ?>">
                                                    <label id="cstmrSpplrLnkdPrsnLbl" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', '', '', '', 'radio', true, '<?php echo $cstmrSpplrLnkdPrsnID; ?>', 'cstmrSpplrLnkdPrsnID', 'cstmrSpplrLnkdPrsn', 'clear', 1, '', function(){afterCstmrLnkdPrsnSlct();});">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $cstmrSpplrLnkdPrsn; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="cstmrSpplrGender" class="control-label">Gender:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="cstmrSpplrGender" value="<?php echo $cstmrSpplrGender; ?>" readonly="">
                                                    <label id="invItemCtgryLbl" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Gender', '', '', '', 'radio', true, '', 'cstmrSpplrGender', '', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $cstmrSpplrGender; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="cstmrSpplrDOB" class="control-label">Date Established:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100%;">
                                                    <input class="form-control rqrdFld" size="16" type="text" id="cstmrSpplrDOB" value="<?php echo $cstmrSpplrDOB; ?>" readonly="true">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $cstmrSpplrDOB; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            &nbsp;
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                <label for="isCstmrEnbld" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdt === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($isEnbld == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isCstmrEnbld" id="isCstmrEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" style="padding: 1px !important;">
                                <div class="col-md-8">
                                    <fieldset class="basic_person_fs1" style="min-height: 50px !important;">
                                        <!--<legend class="basic_person_lg">Item's Picture</legend>-->
                                        <div style="margin-bottom: 5px;margin-top:5px !important;">
                                            <img src="<?php echo $nwFileName; ?>" alt="..." id="img1CstmrTest" class="img-rounded center-block img-responsive" style="height: 150px !important; width: auto !important;">
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <?php if ($canEdt === true) { ?>
                                        <div class="form-group form-group-sm" style="margin-bottom: 10px;margin-top:10px !important;">
                                            <div class="col-md-12" style="padding:1px !important;">
                                                <label class="btn btn-primary btn-file" style="width:100% !important;min-width: 100% !important;">
                                                    Browse... <input type="file" id="daCstmrPicture" name="daCstmrPicture" onchange="changeImgSrc(this, '#img1CstmrTest', '#img1CstmrSrcLoc');" class="btn btn-default" style="display: none;">
                                                </label>
                                            </div>
                                            <div class="col-md-12" style="padding:1px !important;">
                                                <input type="text" class="form-control" aria-label="..." id="img1CstmrSrcLoc" value="">
                                            </div>
                                            <div class="col-md-12" style="padding:1px !important;">
                                                <button type="button" class="btn btn-default" data-dismiss="modal" style="width:100% !important;">Close</button>
                                                <button type="button" class="btn btn-success" onclick="saveCstmrsForm();" style="width:100% !important;">Save Changes</button>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:3px 0px 3px 0px;">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                    <li class="active"><a data-toggle="tabajxvmscstmrs" data-rhodata="" href="#vmsCstmrsGnrl" id="vmsCstmrsGnrltab" style="padding: 3px 10px !important;">General</a></li>
                                    <li><a data-toggle="tabajxvmscstmrs" data-rhodata="" href="#vmsCstmrsSites" id="vmsCstmrsSitestab" style="padding: 3px 10px !important;">Customer Sites</a></li>
                                </ul>
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneCstmrDetTblSctn">
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="vmsCstmrsGnrl" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="cstmrLbltyAcntNm" class="control-label">Liability Account:</label>
                                                            </div>
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <div class="input-group">
                                                                        <input type="text" name="cstmrLbltyAcntNm" id="cstmrLbltyAcntNm" class="form-control rqrdFld" value="<?php echo $cstmrLbltyAcntNm; ?>" readonly="true">
                                                                        <input type="hidden" name="cstmrLbltyAcntID" id="cstmrLbltyAcntID" class="form-control" value="<?php echo $cstmrLbltyAcntID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Liability Accounts', '', '', '', 'radio', true, '', 'cstmrLbltyAcntID', 'cstmrLbltyAcntNm', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $cstmrLbltyAcntNm; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="cstmrRcvblsAcntNm" class="control-label">Receivables Account:</label>
                                                            </div>
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <div class="input-group">
                                                                        <input type="text" name="cstmrRcvblsAcntNm" id="cstmrRcvblsAcntNm" class="form-control rqrdFld" value="<?php echo $cstmrRcvblsAcntNm; ?>" readonly="true">
                                                                        <input type="hidden" name="cstmrRcvblsAcntID" id="cstmrRcvblsAcntID" class="form-control" value="<?php echo $cstmrRcvblsAcntID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', '', '', '', 'radio', true, '', 'cstmrRcvblsAcntID', 'cstmrRcvblsAcntNm', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $cstmrRcvblsAcntNm; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="cstmrCmpnyBrandNm" class="control-label">Company Brand Name:</label>
                                                            </div>
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="text" name="cstmrCmpnyBrandNm" id="cstmrCmpnyBrandNm" class="form-control" value="<?php echo $cstmrCmpnyBrandNm; ?>">
                                                                <?php } else { ?>
                                                                    <span><?php echo $cstmrCmpnyBrandNm; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="cstmrOrgType" class="control-label">Type of Organisation:</label>
                                                            </div>
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="text" name="cstmrOrgType" id="cstmrOrgType" class="form-control" value="<?php echo $cstmrOrgType; ?>">
                                                                <?php } else { ?>
                                                                    <span><?php echo $cstmrOrgType; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="cstmrRegNum" class="control-label">Company Registration No.:</label>
                                                            </div>
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="text" name="cstmrRegNum" id="cstmrRegNum" class="form-control" value="<?php echo $cstmrRegNum; ?>" placeholder="">
                                                                <?php } else { ?>
                                                                    <span><?php echo $cstmrRegNum; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="cstmrDateIncprtd" class="control-label">Date of Incorporation:</label>
                                                            </div>
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100%;">
                                                                        <input class="form-control" size="16" type="text" id="cstmrDateIncprtd" value="<?php echo $cstmrDateIncprtd; ?>" readonly="true">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $cstmrDateIncprtd; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="cstmrIncprtnType" class="control-label">Type of Incorporation:</label>
                                                            </div>
                                                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="text" name="cstmrIncprtnType" id="cstmrIncprtnType" class="form-control" value="<?php echo $cstmrIncprtnType; ?>" placeholder="">
                                                                <?php } else { ?>
                                                                    <span><?php echo $cstmrIncprtnType; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="cstmrVatNumber" class="control-label">VAT Number:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="text" name="cstmrVatNumber" id="cstmrVatNumber" class="form-control" value="<?php echo $cstmrVatNumber; ?>" placeholder="">
                                                                <?php } else { ?>
                                                                    <span><?php echo $cstmrVatNumber; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="cstmrTinNumber" class="control-label">TIN Number:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="text" name="cstmrTinNumber" id="cstmrTinNumber" class="form-control" value="<?php echo $cstmrTinNumber; ?>" placeholder="">
                                                                <?php } else { ?>
                                                                    <span><?php echo $cstmrTinNumber; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="cstmrSsnitRegNum" class="control-label">SSNIT Reg. Number:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="text" name="cstmrSsnitRegNum" id="cstmrSsnitRegNum" class="form-control" value="<?php echo $cstmrSsnitRegNum; ?>" placeholder="">
                                                                <?php } else { ?>
                                                                    <span><?php echo $cstmrSsnitRegNum; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="cstmrNumEmployees" class="control-label">No. of Employees:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="number" name="cstmrNumEmployees" id="cstmrNumEmployees" class="form-control" value="<?php echo $cstmrNumEmployees; ?>" placeholder="0">
                                                                <?php } else { ?>
                                                                    <span><?php echo $cstmrNumEmployees; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="cstmrDescSrvcs" class="control-label">General Description of Services Offered:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <textarea name="cstmrDescSrvcs" id="cstmrDescSrvcs" class="form-control" rows="5"><?php echo $cstmrDescSrvcs; ?></textarea>
                                                                <?php } else { ?>
                                                                    <span><?php echo $cstmrDescSrvcs; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php
                                                    $nwRowHtml = "<tr id=\"oneCstmrSrvcsRow__WWW123WWW\">"
                                                        . "<td class=\"lovtd\"><span>New</span></td>"
                                                        . "<td class=\"lovtd\">
                                                        <div class=\"\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneCstmrSrvcsRow_WWW123WWW_SrvcNm\" value=\"\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'List of Professional Services', '', '', '', 'radio', true, '', 'oneCstmrSrvcsRow_WWW123WWW_SrvcNm', '', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delCstmrsSrvcOffrd('oneCstmrSrvcsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Service Offered\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                        </button>
                                                    </td>";
                                                    $nwRowHtml = urlencode($nwRowHtml);
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php if ($canEdt === true) {
                                                            ?>
                                                                <button id="addNwVaultBtn5" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('oneCstmrSrvcsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Service Offered">
                                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Add Service Offered
                                                                </button>
                                                            <?php } ?>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneCstmrsDocsForm(<?php echo $sbmtdCstmrSpplrID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title="Attached Documents">
                                                                <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <button id="refreshVltBtn5" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCstmrSpplrForm(<?php echo $sbmtdCstmrSpplrID; ?>, 'View Customer/Supplier', 'ReloadDialog', function () {
                                                                        getAllCstmrs('', '#allmodules', 'grp=6&typ=1&pg=13&vtyp=0&srcMenu=');
                                                                    });" data-toggle="tooltip" data-placement="bottom" title="Reload Details">
                                                                <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Refresh
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table table-striped table-bordered table-responsive" id="oneCstmrSrvcsTable" cellspacing="0" width="100%" style="max-width:400px;min-width: 300px !important;">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="max-width:50px;width:50px;">No.</th>
                                                                        <th>Service Offered</th>
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                        <?php } ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $cntrUsr = 0;
                                                                    if (trim($cstmrListOfSrvcs, "|") != "") {
                                                                        $rwCage = explode("|", trim($cstmrListOfSrvcs, "|"));
                                                                        for ($z = 0; $z < count($rwCage); $z++) {
                                                                            $cntrUsr++;
                                                                            $srvcName = $rwCage[$z];
                                                                    ?>
                                                                            <tr id="oneCstmrSrvcsRow_<?php echo $cntrUsr; ?>">
                                                                                <td class="lovtd"><span><?php echo ($cntrUsr); ?></span></td>
                                                                                <td class="lovtd">
                                                                                    <?php
                                                                                    if ($canEdt === true) {
                                                                                    ?>
                                                                                        <div class="">
                                                                                            <div class="input-group" style="width:100%;">
                                                                                                <input type="text" class="form-control" aria-label="..." id="oneCstmrSrvcsRow<?php echo $cntrUsr; ?>_SrvcNm" value="<?php echo $srvcName; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'List of Professional Services', '', '', '', 'radio', true, '<?php echo $srvcName; ?>', 'oneCstmrSrvcsRow<?php echo $cntrUsr; ?>_SrvcNm', '', 'clear', 1, '');">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php } else { ?>
                                                                                        <span><?php echo $srvcName; ?></span>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <?php
                                                                                if ($canEdt === true) {
                                                                                ?>
                                                                                    <td class="lovtd">
                                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delCstmrsSrvcOffrd('oneCstmrSrvcsRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Service Offered">
                                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                <?php } ?>
                                                                            </tr>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="vmsCstmrsSites" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php if ($canEdt === true) { ?>
                                                        <button id="addNwVaultBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneCstmrSitesForm(-1, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="New Site">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add Site
                                                        </button>
                                                    <?php } ?>
                                                    <button id="refreshVltBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCstmrSpplrForm(<?php echo $sbmtdCstmrSpplrID; ?>, 'View Customers/Suppliers', 'ReloadDialog', function () {
                                                                getAllCstmrs('', '#allmodules', 'grp=6&typ=1&pg=13&vtyp=0&srcMenu=');});" data-toggle="tooltip" data-placement="bottom" title="Reload Customer/Supplier Details">
                                                        <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Refresh
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneItmStoresTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:50px;width:50px;">No.</th>
                                                                <th>Site Name</th>
                                                                <th>Billing Address</th>
                                                                <th>Shipping Address</th>
                                                                <th>Contact Person</th>
                                                                <th>Contact Nos.</th>
                                                                <th>Email</th>
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                    <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $rslt = get_OneCstmrSites($sbmtdCstmrSpplrID);
                                                            $cntrUsr = 0;
                                                            while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                $cntrUsr++;
                                                                $siteID = (float) $rwCage[1];
                                                                $siteName = $rwCage[2];
                                                                $bllngAddrs = $rwCage[9];
                                                                $shpngAddrs = $rwCage[10];
                                                                $cntctPrsn = $rwCage[11];
                                                                $cntctNos = $rwCage[12];
                                                                $cntctEmail = $rwCage[13];
                                                            ?>
                                                                <tr id="oneCstmrSitesRow_<?php echo $cntrUsr; ?>">
                                                                    <td class="lovtd"><span><?php echo ($rwCage[0]); ?></span></td>
                                                                    <td class="lovtd">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneCstmrSitesRow<?php echo $cntrUsr; ?>_SiteID" value="<?php echo $siteID; ?>">
                                                                        <span><?php echo $siteName; ?></span>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo $bllngAddrs; ?></span>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo $shpngAddrs; ?></span>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo $cntctPrsn; ?></span>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo $cntctNos; ?></span>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo $cntctEmail; ?></span>
                                                                    </td>
                                                                    <?php if ($canEdt === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneCstmrSitesForm(<?php echo $siteID; ?>, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="View/Edit Site">
                                                                                <img src="cmn_images/edit32.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delCstmrsSites('oneCstmrSitesRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Site">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdt === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveCstmrsForm();">Save Changes</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 2) {
                //New Customer/Supplier Site Form
                $canEdtSite = $canEdt;
                $sbmtdSiteID = isset($_POST['sbmtdSiteID']) ? cleanInputData($_POST['sbmtdSiteID']) : -1;

                $csSiteName = "";
                $csSiteDesc = "";
                $csSiteBllngAddress = "";
                $csSiteShpngAddress = "";
                $csSiteCntctPrsn = "";
                $csSiteCntctNos = "";
                $csSiteEmailAdrs = "";
                $csSiteWthTxCodeID = -1;
                $csSiteWthTxCode = "";
                $csSiteDscntCodeID = -1;
                $csSiteDscntCode = "";
                $csSiteCountry = "";
                $csSiteIDType = "";
                $csSiteIDNum = "";
                $csSiteDateIsd = "";
                $csSiteExpryDate = "";
                $csSiteOtherInfo = "";
                $csSiteBankNm = "";
                $csSiteBrnchNm = "";
                $csSiteAcntNum = "";
                $csSiteCrncy = "";
                $csSiteSwftCode = "";
                $csSiteIbanCode = "";
                $isEnbld = "No";
                if ($sbmtdSiteID > 0) {
                    $result = get_OneCstmrSitesDt($sbmtdSiteID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdSiteID = (float) $row[0];
                        $csSiteName = $row[1];
                        $csSiteDesc = $row[2];
                        $csSiteBllngAddress = $row[8];
                        $csSiteShpngAddress = $row[9];
                        $csSiteCntctPrsn = $row[10];
                        $csSiteCntctNos = $row[11];
                        $csSiteEmailAdrs = $row[12];
                        $csSiteWthTxCodeID = (float) $row[6];
                        $csSiteWthTxCode = $row[24];
                        $csSiteDscntCodeID = (float) $row[7];
                        $csSiteDscntCode = $row[25];
                        $csSiteCountry = $row[14];
                        $csSiteIDType = $row[15];
                        $csSiteIDNum = $row[16];
                        $csSiteDateIsd = $row[17];
                        $csSiteExpryDate = $row[18];
                        $csSiteOtherInfo = $row[19];
                        $csSiteBankNm = $row[3];
                        $csSiteBrnchNm = $row[4];
                        $csSiteAcntNum = $row[5];
                        $csSiteCrncy = $row[23];
                        $csSiteSwftCode = $row[13];
                        $csSiteIbanCode = $row[21];
                        $isEnbld = $row[20];
                    }
                }
            ?>
                <form class="form-horizontal" id='cstmrSiteForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0px 15px !important;">
                            <div class="col-md-6" style="padding: 0px 5px 0px 5px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteName" class="control-label">Site Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <input type="text" name="csSiteName" id="csSiteName" class="form-control rqrdFld" value="<?php echo $csSiteName; ?>" style="width:100% !important;">
                                                <input type="hidden" name="sbmtdSiteID" id="sbmtdSiteID" class="form-control" value="<?php echo $sbmtdSiteID; ?>">
                                            <?php } else { ?>
                                                <span><?php echo $csSiteName; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteDesc" class="control-label">Site Description:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <textarea rows="3" name="csSiteDesc" id="csSiteDesc" class="form-control"><?php echo $csSiteDesc; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteDesc; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            &nbsp;
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                <label for="isCsSiteEnbld" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdtSite === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($isEnbld == "1") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isCsSiteEnbld" id="isCsSiteEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteBllngAddress" class="control-label">Billing Address:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <textarea rows="3" name="csSiteBllngAddress" id="csSiteBllngAddress" class="form-control"><?php echo $csSiteBllngAddress; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteBllngAddress; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteShpngAddress" class="control-label">Ship to Address:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <textarea rows="3" name="csSiteShpngAddress" id="csSiteShpngAddress" class="form-control"><?php echo $csSiteShpngAddress; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteShpngAddress; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteCntctPrsn" class="control-label">Contact Person:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <input type="text" name="csSiteCntctPrsn" id="csSiteCntctPrsn" class="form-control rqrdFld" value="<?php echo $csSiteCntctPrsn; ?>" style="width:100% !important;">
                                            <?php } else { ?>
                                                <span><?php echo $csSiteCntctPrsn; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteCntctNos" class="control-label">Contact Nos:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <input type="text" name="csSiteCntctNos" id="csSiteCntctNos" class="form-control" value="<?php echo $csSiteCntctNos; ?>" style="width:100% !important;">
                                            <?php } else { ?>
                                                <span><?php echo $csSiteCntctNos; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteEmailAdrs" class="control-label">Email:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <input type="text" name="csSiteEmailAdrs" id="csSiteEmailAdrs" class="form-control" value="<?php echo $csSiteEmailAdrs; ?>" style="width:100% !important;">
                                            <?php } else { ?>
                                                <span><?php echo $csSiteEmailAdrs; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteWthTxCode" class="control-label">Witholding Tax Code:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" name="csSiteWthTxCode" id="csSiteWthTxCode" class="form-control" value="<?php echo $csSiteWthTxCode; ?>" readonly="true" style="width:100% !important;">
                                                    <input type="hidden" name="csSiteWthTxCodeID" id="csSiteWthTxCodeID" class="form-control" value="<?php echo $csSiteWthTxCodeID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', '', '', '', 'radio', true, '', 'csSiteWthTxCodeID', 'csSiteWthTxCode', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteWthTxCode; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteDscntCode" class="control-label">Special Discount Code:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" name="csSiteDscntCode" id="csSiteDscntCode" class="form-control" value="<?php echo $csSiteDscntCode; ?>" readonly="true" style="width:100% !important;">
                                                    <input type="hidden" name="csSiteDscntCodeID" id="csSiteDscntCodeID" class="form-control" value="<?php echo $csSiteDscntCodeID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', '', '', '', 'radio', true, '', 'csSiteDscntCodeID', 'csSiteDscntCode', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteDscntCode; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding: 1px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteCountry" class="control-label">Country:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" name="csSiteCountry" id="csSiteCountry" class="form-control" value="<?php echo $csSiteCountry; ?>" readonly="true">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Countries', '', '', '', 'radio', true, '', 'csSiteCountry', '', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteCountry; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteIDType" class="control-label">ID Type:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" name="csSiteIDType" id="csSiteIDType" class="form-control" value="<?php echo $csSiteIDType; ?>" readonly="true">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'National ID Types', '', '', '', 'radio', true, '', 'csSiteIDType', '', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteIDType; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteIDNum" class="control-label">ID Number:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <input type="text" name="csSiteIDNum" id="csSiteIDNum" class="form-control" value="<?php echo $csSiteIDNum; ?>" style="width:100% !important;">
                                            <?php } else { ?>
                                                <span><?php echo $csSiteIDNum; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteDateIsd" class="control-label">Date Issued:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100%;">
                                                    <input class="form-control" size="16" type="text" id="csSiteDateIsd" value="<?php echo $csSiteDateIsd; ?>" readonly="true">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteDateIsd; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteExpryDate" class="control-label">Expiry Date:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100%;">
                                                    <input class="form-control" size="16" type="text" id="csSiteExpryDate" value="<?php echo $csSiteExpryDate; ?>" readonly="true">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteExpryDate; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteOtherInfo" class="control-label">Other Information:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <textarea rows="3" name="csSiteOtherInfo" id="csSiteOtherInfo" class="form-control"><?php echo $csSiteOtherInfo; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteOtherInfo; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteBankNm" class="control-label">Bank:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" name="csSiteBankNm" id="csSiteBankNm" class="form-control" value="<?php echo $csSiteBankNm; ?>" readonly="true">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Banks', '', '', '', 'radio', true, '', 'csSiteBankNm', '', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteBankNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteBrnchNm" class="control-label">Branch:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" name="csSiteBrnchNm" id="csSiteBrnchNm" class="form-control" value="<?php echo $csSiteBrnchNm; ?>" readonly="true">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Bank Branches', '', '', '', 'radio', true, '', 'csSiteBrnchNm', '', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteBrnchNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteAcntNum" class="control-label">Account Number:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <input type="text" name="csSiteAcntNum" id="csSiteAcntNum" class="form-control" value="<?php echo $csSiteAcntNum; ?>" style="width:100% !important;">
                                            <?php } else { ?>
                                                <span><?php echo $csSiteAcntNum; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteCrncy" class="control-label">Currency:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" name="csSiteCrncy" id="csSiteCrncy" class="form-control" value="<?php echo $csSiteCrncy; ?>" readonly="true">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'csSiteCrncy', '', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $csSiteCrncy; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteSwftCode" class="control-label">SWIFT/BIC:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <input type="text" name="csSiteSwftCode" id="csSiteSwftCode" class="form-control" value="<?php echo $csSiteSwftCode; ?>" style="width:100% !important;">
                                            <?php } else { ?>
                                                <span><?php echo $csSiteSwftCode; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="csSiteIbanCode" class="control-label">IBAN:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtSite === true) { ?>
                                                <input type="text" name="csSiteIbanCode" id="csSiteIbanCode" class="form-control" value="<?php echo $csSiteIbanCode; ?>" style="width:100% !important;">
                                            <?php } else { ?>
                                                <span><?php echo $csSiteIbanCode; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:3px 0px 3px 0px;">
                        </div>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdtSite === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveCstmrSitesForm();">Save Changes</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 5) {
                //Get Person's Details
                header("content-type:application/json");
                $cstmrSpplrLnkdPrsnID = isset($_POST['cstmrSpplrLnkdPrsnID']) ? (float) cleanInputData($_POST['cstmrSpplrLnkdPrsnID']) : -1;
                $cstmrSpplrGender = getGnrlRecNm("prs.prsn_names_nos", "person_id", "gender", $cstmrSpplrLnkdPrsnID);
                $cstmrSpplrDOB = getGnrlRecNm(
                    "prs.prsn_names_nos",
                    "person_id",
                    "(CASE WHEN coalesce(date_of_birth,'') !='' THEN to_char(to_timestamp(date_of_birth,'YYYY-MM-DD'),'DD-Mon-YYYY') ELSE '' END)",
                    $cstmrSpplrLnkdPrsnID
                );
                $arr_content['cstmrSpplrGender'] = $cstmrSpplrGender;
                $arr_content['cstmrSpplrDOB'] = $cstmrSpplrDOB;
                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 6) {
                //Get Person's Details
                header("content-type:application/json");
                $smplVchrSpplrID = isset($_POST['smplVchrSpplrID']) ? (float) cleanInputData($_POST['smplVchrSpplrID']) : -1;
                $smplVchrSpplrSiteID = isset($_POST['smplVchrSpplrSiteID']) ? (float) cleanInputData($_POST['smplVchrSpplrSiteID']) : -1;
                $smplVchrRefNum = isset($_POST['smplVchrRefNum']) ? cleanInputData($_POST['smplVchrRefNum']) : "";
                $vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
                $vPsblVal1 = getPssblValDesc($vPsblValID1);

                $smplVchrSpplrSiteID = (float)getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_supplier_id", "max(cust_sup_site_id)", $smplVchrSpplrID);
                $cstmrSpplrLnkdPrsnID = (float)getGnrlRecNm("scm.scm_cstmr_suplr", "cust_sup_id", "max(lnkd_prsn_id)", $smplVchrSpplrID);
                if ($vPsblVal1 == "TAKBG_SWLFR_APP_1") {
                    $smplVchrRefNum = getGnrlRecNm("prs.prsn_extra_data", "person_id", "data_col1", $cstmrSpplrLnkdPrsnID);
                }
                $arr_content['smplVchrSpplrSiteID'] = $smplVchrSpplrSiteID;
                $arr_content['smplVchrRefNum'] = $smplVchrRefNum;
                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 20) {
                /* All Attached Documents */
                $sbmtdAccbCstmrsID = isset($_POST['sbmtdAccbCstmrsID']) ? cleanInputData($_POST['sbmtdAccbCstmrsID']) : -1;
                if (!$canAdd || ($sbmtdAccbCstmrsID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdAccbCstmrsID;
                $total = get_Total_CstmrAttachments($srchFor, $pkID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_CstmrAttachments($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
            ?>
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdCstmrsDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"attchdCstmrsDocsRow__WWW123WWW\">"
                                . "<td class=\"lovtd\"><span>New</span></td>"
                                . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdCstmrsDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdCstmrsDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdCstmrsDocsRow_WWW123WWW_DocCtgryNm', 'attchdCstmrsDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdCstmrsDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToCstmrsDocs('attchdCstmrsDocsRow_WWW123WWW_DocFile','attchdCstmrsDocsRow_WWW123WWW_AttchdDocsID','attchdCstmrsDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdCstmrsDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Upload Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdCstmrsDoc('attchdCstmrsDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdCstmrsDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdCstmrsDocsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                            ?>" onkeyup="enterKeyFuncAttchdCstmrsDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbCstmrsID=<?php echo $sbmtdAccbCstmrsID; ?>', 'ReloadDialog');">
                                    <input id="attchdCstmrsDocsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdCstmrsDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbCstmrsID=<?php echo $sbmtdAccbCstmrsID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdCstmrsDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbCstmrsID=<?php echo $sbmtdAccbCstmrsID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdCstmrsDocsDsplySze" style="min-width:70px !important;">
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
                                            <a class="rhopagination" href="javascript:getAttchdCstmrsDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbCstmrsID=<?php echo $sbmtdAccbCstmrsID; ?>','ReloadDialog');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdCstmrsDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdAccbCstmrsID=<?php echo $sbmtdAccbCstmrsID; ?>','ReloadDialog');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdCstmrsDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Doc. Name/Description</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row2 = loc_db_fetch_array($result2)) {
                                            $cntr += 1;
                                            $doc_src = $ftp_base_db_fldr . "/FirmsDocs/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                        ?>
                                            <tr id="attchdCstmrsDocsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdCstmrsDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    if ($doc_src_encrpt == "None") {
                                                    ?>
                                                        <span style="font-weight: bold;color:#FF0000;">
                                                            <?php
                                                            echo "File Not Found!";
                                                            ?>
                                                        </span>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="doAjax('grp=1&typ=11&q=Download&fnm=<?php echo $doc_src_encrpt; ?>', '', 'Redirect', '', '', '');" data-toggle="tooltip" data-placement="bottom" title="Download Document">
                                                            <img src="cmn_images/dwldicon.png" style="height:15px; width:auto; position: relative; vertical-align: middle;"> Download
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdCstmrsDoc('attchdCstmrsDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </fieldset>
<?php
            }
        }
    }
}
?>