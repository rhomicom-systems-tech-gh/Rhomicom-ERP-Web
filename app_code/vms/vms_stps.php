<?php
$canAddAthrzr = test_prmssns($dfltPrvldgs[34], $mdlNm);
$canEdtAthrzr = test_prmssns($dfltPrvldgs[35], $mdlNm);
$canDelAthrzr = test_prmssns($dfltPrvldgs[36], $mdlNm);
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$srchFor = "";
$srchIn = "";
$RoutingID = -1;
$qNotSentToGl = true;
$qUnbalncdOnly = false;
$qUsrGnrtd = false;

$qLowVal = 0;
$qHighVal = 0;
$qStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 00:00:00";
$qEndDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 23:59:59";

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
if (isset($_POST['qNotSentToGl'])) {
    $qNotSentToGl = cleanInputData($_POST['qNotSentToGl']) === "true" ? true : false;
}
if (isset($_POST['qUnbalncdOnly'])) {
    $qUnbalncdOnly = cleanInputData($_POST['qUnbalncdOnly']) === "true" ? true : false;
}
if (isset($_POST['qUsrGnrtd'])) {
    $qUsrGnrtd = cleanInputData($_POST['qUsrGnrtd']) === "true" ? true : false;
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

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Site */
                $canDelSite = test_prmssns($dfltPrvldgs[24], $mdlNm);
                $sbmtdSiteID = isset($_POST['sbmtdSiteID']) ? cleanInputData($_POST['sbmtdSiteID']) : -1;
                $siteNm = isset($_POST['siteNm']) ? cleanInputData($_POST['siteNm']) : "";
                if ($canDelSite) {
                    echo deleteSiteLoc($sbmtdSiteID, $siteNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Vault */
                $canDelVlt = test_prmssns($dfltPrvldgs[27], $mdlNm);
                $sbmtdVltID = isset($_POST['sbmtdVltID']) ? cleanInputData($_POST['sbmtdVltID']) : -1;
                $vltNm = isset($_POST['vltNm']) ? cleanInputData($_POST['vltNm']) : "";
                if ($canDelVlt) {
                    echo deleteVault($sbmtdVltID, $vltNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 201) {
                /* Delete Vault User */
                $canEdtVlt = test_prmssns($dfltPrvldgs[26], $mdlNm);
                $lineID = isset($_POST['lineID']) ? cleanInputData($_POST['lineID']) : -1;
                $usrNm = isset($_POST['usrNm']) ? cleanInputData($_POST['usrNm']) : "";
                if ($canDelVlt) {
                    echo deleteVaultUser($sbmtdLineID, $usrNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Cage/Till */
                $canDelCage = test_prmssns($dfltPrvldgs[30], $mdlNm);
                $lineID = isset($_POST['lineID']) ? cleanInputData($_POST['lineID']) : -1;
                $cageNm = isset($_POST['cageNm']) ? cleanInputData($_POST['cageNm']) : "";
                if ($canDelCage) {
                    echo deleteCageTill($lineID, $cageNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 4) {
                /* Delete Item */
                $canDelItm = test_prmssns($dfltPrvldgs[33], $mdlNm);
                $sbmtdItmID = isset($_POST['sbmtdItmID']) ? cleanInputData($_POST['sbmtdItmID']) : -1;
                $itemNm = isset($_POST['itemNm']) ? cleanInputData($_POST['itemNm']) : "";
                if ($canDelItm) {
                    echo deleteVMSItm($sbmtdItmID, $itemNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 401) {
                /* Delete Item Stores */
                $canEdtItm = test_prmssns($dfltPrvldgs[32], $mdlNm);
                $sbmtdStckID = isset($_POST['sbmtdStckID']) ? cleanInputData($_POST['sbmtdStckID']) : -1;
                $stockNm = isset($_POST['stockNm']) ? cleanInputData($_POST['stockNm']) : "";
                if ($canEdtItm) {
                    echo deleteVMSItmStore($sbmtdStckID, $stockNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 402) {
                /* Delete Item UoM */
                $canEdtItm = test_prmssns($dfltPrvldgs[32], $mdlNm);
                $sbmtdLineID = isset($_POST['sbmtdLineID']) ? cleanInputData($_POST['sbmtdLineID']) : -1;
                $uomNm = isset($_POST['uomNm']) ? cleanInputData($_POST['uomNm']) : "";
                if ($canEdtItm) {
                    echo deleteVMSItmUom($sbmtdLineID, $uomNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 403) {
                /* Delete Item Interactions */
                $canEdtItm = test_prmssns($dfltPrvldgs[32], $mdlNm);
                $sbmtdLineID = isset($_POST['sbmtdLineID']) ? cleanInputData($_POST['sbmtdLineID']) : -1;
                $drugNm = isset($_POST['drugNm']) ? cleanInputData($_POST['drugNm']) : "";
                if ($canEdtItm) {
                    echo deleteVMSItmIntrctn($sbmtdLineID, $drugNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                $athrzrNm = isset($_POST['athrzrNm']) ? (int) (cleanInputData($_POST['athrzrNm'])) : -1;
                $pKeyID = isset($_POST['pKeyID']) ? (int) (cleanInputData($_POST['pKeyID'])) : -1;
                echo deleteApprvrLmt($pKeyID, $athrzrNm);
            } else if ($actyp == 6) {
                //Delete Customer/Supplier
                $cstmrNm = isset($_POST['cstmrNm']) ? (int) (cleanInputData($_POST['cstmrNm'])) : -1;
                $pKeyID = isset($_POST['pKeyID']) ? (int) (cleanInputData($_POST['pKeyID'])) : -1;
                echo deleteCstmr($pKeyID, $cstmrNm);
            } else if ($actyp == 601) {
                //Delete Customer/Supplier Site
                $siteNm = isset($_POST['siteNm']) ? (int) (cleanInputData($_POST['siteNm'])) : -1;
                $pKeyID = isset($_POST['pKeyID']) ? (int) (cleanInputData($_POST['pKeyID'])) : -1;
                echo deleteCstmrSite($pKeyID, $siteNm);
            } else if ($actyp == 7) {
                /* Delete Interface Transaction */
                $canDelTrns = test_prmssns($dfltPrvldgs[48], $mdlNm);
                $slctdIntrfcIDs = isset($_POST['slctdIntrfcIDs']) ? cleanInputData($_POST['slctdIntrfcIDs']) : '';
                $variousRows = explode("|", trim($slctdIntrfcIDs, "|"));
                $affctd1 = 0;
                if ($canDelTrns) {
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 2) {
                            $intrfcID = (float) cleanInputData1($crntRow[0]);
                            $intrfcIDDesc = cleanInputData1($crntRow[1]);
                            $affctd1 += deleteVMSTrnsGLIntFcLn($intrfcID, $intrfcIDDesc);
                        }
                    }
                    if ($affctd1 > 0) {
                        $dsply = "Successfully Deleted the ff Records-";
                        $dsply .= "<br/>$affctd1 Interface Transaction(s)!";
                        echo "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                    } else {
                        $dsply = "No Record Deleted!";
                        echo "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                    }
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                header("content-type:application/json");
                $sbmtdSiteID = isset($_POST['sbmtdSiteID']) ? (float) cleanInputData($_POST['sbmtdSiteID']) : -1;
                $siteBrnchNm = isset($_POST['siteBrnchNm']) ? cleanInputData($_POST['siteBrnchNm']) : '';
                $siteBrnchDesc = isset($_POST['siteBrnchDesc']) ? cleanInputData($_POST['siteBrnchDesc']) : '';
                $siteBrnchType = isset($_POST['siteBrnchType']) ? cleanInputData($_POST['siteBrnchType']) : '';
                $grpType = isset($_POST['grpType']) ? cleanInputData($_POST['grpType']) : '';
                $allwdGroupNm = isset($_POST['allwdGroupNm']) ? cleanInputData($_POST['allwdGroupNm']) : '';
                $allwdGroupID = isset($_POST['allwdGroupID']) ? cleanInputData($_POST['allwdGroupID']) : '';
                $allwdGrpVal = $allwdGroupID;
                if ($grpType == "Person Types") {
                    $grpTypLovID = getLovID("Person Types");
                    $allwdGrpVal = getPssblValID($allwdGroupID, $grpTypLovID);
                }
                if (($grpType == "Everyone" && floatval($allwdGrpVal) >= 0) || ($grpType != "Everyone" && floatval($allwdGrpVal) <= 0)) {
                    $arr_content['percent'] = 100;
                    $arr_content['sitelocid'] = $sbmtdSiteID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Invalid Allowed Group Type and Name!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $isSiteEnbld = isset($_POST['isSiteEnbld']) ? cleanInputData($_POST['isSiteEnbld']) : 'NO';
                $oldSiteID = getSiteLocID($siteBrnchNm);
                $siteTypesLovID = getLovID("Site Types");
                $siteTypeID = getPssblValID($siteBrnchType, $siteTypesLovID);
                if ($siteTypeID <= 0) {
                    createPssblValsForLov1($siteTypesLovID, $siteBrnchType, $siteBrnchType, "1", "," . $orgID . ",");
                    $siteTypeID = getPssblValID($siteBrnchType, $siteTypesLovID);
                }
                $isSiteEnbldVal = ($isSiteEnbld == "NO") ? "0" : "1";
                $errMsg = "";
                if ($siteBrnchNm != "" && $siteBrnchDesc != "" && ($oldSiteID <= 0 || $oldSiteID == $sbmtdSiteID)) {
                    if ($sbmtdSiteID <= 0) {
                        createSiteLoc($siteBrnchNm, $siteBrnchDesc, $siteTypeID, $orgID, $isSiteEnbldVal, $grpType, $allwdGrpVal);
                        $sbmtdSiteID = getSiteLocID($siteBrnchNm);
                    } else {
                        updateSiteLoc($sbmtdSiteID, $siteBrnchNm, $siteBrnchDesc, $siteTypeID, $orgID, $isSiteEnbldVal, $grpType, $allwdGrpVal);
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['sitelocid'] = $sbmtdSiteID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Site Successfully Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['sitelocid'] = $sbmtdSiteID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                header("content-type:application/json");
                $sbmtdVltID = isset($_POST['sbmtdVltID']) ? (float) cleanInputData($_POST['sbmtdVltID']) : -1;
                $vaultNm = isset($_POST['vaultNm']) ? cleanInputData($_POST['vaultNm']) : '';
                $vaultDesc = isset($_POST['vaultDesc']) ? cleanInputData($_POST['vaultDesc']) : '';
                $vaultAddress = isset($_POST['vaultAddress']) ? cleanInputData($_POST['vaultAddress']) : '';
                $lnkdSiteID = isset($_POST['lnkdSiteID']) ? (float) cleanInputData($_POST['lnkdSiteID']) : -1;
                $lnkdGLAccountID = isset($_POST['lnkdGLAccountID']) ? (float) cleanInputData($_POST['lnkdGLAccountID']) : -1;
                $vltMngrsPrsnID = isset($_POST['vltMngrsPrsnID']) ? (float) cleanInputData($_POST['vltMngrsPrsnID']) : -1;
                $grpType = isset($_POST['grpType']) ? cleanInputData($_POST['grpType']) : '';
                $allwdGroupNm = isset($_POST['allwdGroupNm']) ? cleanInputData($_POST['allwdGroupNm']) : '';
                $allwdGroupID = isset($_POST['allwdGroupID']) ? cleanInputData($_POST['allwdGroupID']) : '';
                $allwdGrpVal = $allwdGroupID;
                if ($grpType == "Person Types") {
                    $grpTypLovID = getLovID("Person Types");
                    $allwdGrpVal = getPssblValID($allwdGroupID, $grpTypLovID);
                }
                if (($grpType == "Everyone" && floatval($allwdGrpVal) >= 0) || ($grpType != "Everyone" && floatval($allwdGrpVal) <= 0)) {
                    $arr_content['percent'] = 100;
                    $arr_content['vltid'] = $sbmtdVltID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Invalid Allowed Group Type and Name!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $isVltEnbld = isset($_POST['isVltEnbld']) ? cleanInputData($_POST['isVltEnbld']) : 'NO';
                $isSalesAllwd = isset($_POST['isSalesAllwd']) ? cleanInputData($_POST['isSalesAllwd']) : 'NO';
                $oldVaultID = getVaultID($vaultNm);
                $isVltEnbldVal = ($isVltEnbld == "NO") ? "0" : "1";
                $isSalesAllwdVal = ($isSalesAllwd == "NO") ? "0" : "1";
                $errMsg = "";
                if ($vaultNm != "" && $vaultDesc != "" && ($oldVaultID <= 0 || $oldVaultID == $sbmtdVltID)) {
                    if ($sbmtdVltID <= 0) {
                        createVault($vaultNm, $vaultDesc, $vaultAddress, $isSalesAllwdVal, $vltMngrsPrsnID, $orgID, $isVltEnbldVal, $lnkdGLAccountID, $lnkdSiteID, $grpType, $allwdGrpVal);
                        $sbmtdVltID = getVaultID($vaultNm);
                    } else {
                        updateVault($sbmtdVltID, $vaultNm, $vaultDesc, $vaultAddress, $isSalesAllwdVal, $vltMngrsPrsnID, $orgID, $isVltEnbldVal, $lnkdGLAccountID, $lnkdSiteID, $grpType, $allwdGrpVal);
                    }
                    //Save Vault Users
                    $afftctd = 0;
                    $slctdUsers = isset($_POST['slctdUsers']) ? cleanInputData($_POST['slctdUsers']) : '';
                    if (trim($slctdUsers, "|~") != "") {
                        $variousRows = explode("|", trim($slctdUsers, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 4) {
                                $vltUsrLineID = (float) (cleanInputData1($crntRow[0]));
                                $vltUsrID = (float) cleanInputData1($crntRow[1]);
                                $usrStartDte = cleanInputData1($crntRow[2]);
                                $usrEndDte = cleanInputData1($crntRow[3]);
                                $oldVltUsrLineID = getVaultUsrLineID($vltUsrID, $sbmtdVltID);
                                if ($oldVltUsrLineID <= 0 && $vltUsrLineID <= 0) {
                                    //Insert
                                    $afftctd += createVaultUser($vltUsrID, $sbmtdVltID, $usrStartDte, $usrEndDte);
                                } else if ($vltUsrLineID > 0) {
                                    $afftctd += updateVaultUser($vltUsrLineID, $vltUsrID, $sbmtdVltID, $usrStartDte, $usrEndDte);
                                }
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['vltid'] = (float) $sbmtdVltID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Vault Successfully Saved!<br/>" . $afftctd . " Vault User(s) Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['vltid'] = (float) $sbmtdVltID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 3) {
                header("content-type:application/json");
                $cageLineID = isset($_POST['cageLineID']) ? (float) cleanInputData($_POST['cageLineID']) : -1;
                $cageShelfID = isset($_POST['cageShelfID']) ? (float) cleanInputData($_POST['cageShelfID']) : -1;
                $cageShelfNm = isset($_POST['cageShelfNm']) ? cleanInputData($_POST['cageShelfNm']) : '';
                $cageDesc = isset($_POST['cageDesc']) ? cleanInputData($_POST['cageDesc']) : '';
                $cageVltID = isset($_POST['cageVltID']) ? (float) cleanInputData($_POST['cageVltID']) : -1;
                $cageOwnersCstmrID = isset($_POST['cageOwnersCstmrID']) ? (float) cleanInputData($_POST['cageOwnersCstmrID']) : -1;
                $lnkdGLAccountID = isset($_POST['lnkdGLAccountID']) ? (float) cleanInputData($_POST['lnkdGLAccountID']) : -1;
                $cageMngrsPrsnID = isset($_POST['cageMngrsPrsnID']) ? (float) cleanInputData($_POST['cageMngrsPrsnID']) : -1;
                $mngrsWithdrawlLmt = isset($_POST['mngrsWithdrawlLmt']) ? (float) cleanInputData($_POST['mngrsWithdrawlLmt']) : 0;
                $mngrsDepositLmt = isset($_POST['mngrsDepositLmt']) ? (float) cleanInputData($_POST['mngrsDepositLmt']) : 0;
                $grpType = isset($_POST['grpType']) ? cleanInputData($_POST['grpType']) : '';
                $allwdGroupNm = isset($_POST['allwdGroupNm']) ? cleanInputData($_POST['allwdGroupNm']) : '';
                $allwdGroupID = isset($_POST['allwdGroupID']) ? cleanInputData($_POST['allwdGroupID']) : '';
                $allwdGrpVal = $allwdGroupID;
                if ($grpType == "Person Types") {
                    $grpTypLovID = getLovID("Person Types");
                    $allwdGrpVal = getPssblValID($allwdGroupID, $grpTypLovID);
                }
                if (($grpType == "Everyone" && floatval($allwdGrpVal) >= 0) || ($grpType != "Everyone" && floatval($allwdGrpVal) <= 0)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Invalid Allowed Group Type and Name!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $dfltItemType = isset($_POST['dfltItemType']) ? cleanInputData($_POST['dfltItemType']) : '';
                $dfltItemState = isset($_POST['dfltItemState']) ? cleanInputData($_POST['dfltItemState']) : '';
                $isCageEnbld = isset($_POST['isCageEnbld']) ? cleanInputData($_POST['isCageEnbld']) : 'NO';
                $oldCageLineID = getCageTillID($cageShelfNm, $cageVltID);
                $shlfLovID = getLovID("Shelves");
                $oldShelfID = getPssblValID($cageShelfNm, $shlfLovID);
                if ($oldShelfID <= 0) {
                    createPssblValsForLov1($shlfLovID, $cageShelfNm, $cageDesc, "1", "," . $orgID . ",");
                    $cageShelfID = getPssblValID($cageShelfNm, $shlfLovID);
                }
                $isCageEnbldVal = ($isCageEnbld == "NO") ? "0" : "1";
                $errMsg = "";
                if ($cageShelfNm != "" && $cageDesc != "" && ($oldCageLineID <= 0 || $oldCageLineID == $cageLineID)) {
                    if ($cageLineID <= 0) {
                        createCageTill($orgID, $cageShelfID, $cageVltID, $cageShelfNm, $cageDesc, $cageOwnersCstmrID, $grpType, $allwdGrpVal, $lnkdGLAccountID, $cageMngrsPrsnID, $dfltItemState, $mngrsWithdrawlLmt, $mngrsDepositLmt, $dfltItemType, $isCageEnbldVal);
                        $cageLineID = getCageTillID($cageShelfNm, $cageVltID);
                    } else {
                        updateCageTill($cageLineID, $cageShelfID, $cageVltID, $cageShelfNm, $cageDesc, $cageOwnersCstmrID, $grpType, $allwdGrpVal, $lnkdGLAccountID, $cageMngrsPrsnID, $dfltItemState, $mngrsWithdrawlLmt, $mngrsDepositLmt, $dfltItemType, $isCageEnbldVal);
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Vault Cage Successfully Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 4) {
                //Items
                header("content-type:application/json");
                $sbmtdItmID = isset($_POST['sbmtdItmID']) ? (float) cleanInputData($_POST['sbmtdItmID']) : -1;
                $invItemNm = isset($_POST['invItemNm']) ? cleanInputData($_POST['invItemNm']) : '';
                $invItemDesc = isset($_POST['invItemDesc']) ? cleanInputData($_POST['invItemDesc']) : '';
                $invTmpltID = isset($_POST['invTmpltID']) ? (float) cleanInputData($_POST['invTmpltID']) : -1;
                $invItemType = isset($_POST['invItemType']) ? cleanInputData($_POST['invItemType']) : '';
                $invItemCtgryID = isset($_POST['invItemCtgryID']) ? (float) cleanInputData($_POST['invItemCtgryID']) : -1;
                $invTxCodeID = isset($_POST['invTxCodeID']) ? (float) cleanInputData($_POST['invTxCodeID']) : -1;
                $invBaseUomID = isset($_POST['invBaseUomID']) ? (float) cleanInputData($_POST['invBaseUomID']) : -1;
                $invDscntCodeID = isset($_POST['invDscntCodeID']) ? (float) cleanInputData($_POST['invDscntCodeID']) : -1;
                $invChrgCodeID = isset($_POST['invChrgCodeID']) ? (float) cleanInputData($_POST['invChrgCodeID']) : -1;
                $invMinItmQty = isset($_POST['invMinItmQty']) ? (float) cleanInputData($_POST['invMinItmQty']) : 0;
                $invMaxItmQty = isset($_POST['invMaxItmQty']) ? (float) cleanInputData($_POST['invMaxItmQty']) : 0;
                $invValCrncyID = isset($_POST['invValCrncyID']) ? (float) cleanInputData($_POST['invValCrncyID']) : -1;
                $invPriceLessTax = isset($_POST['invPriceLessTax']) ? (float) cleanInputData($_POST['invPriceLessTax']) : 0;
                $invSllngPrice = isset($_POST['invSllngPrice']) ? (float) cleanInputData($_POST['invSllngPrice']) : 0;
                $invNwPrftAmnt = isset($_POST['invNwPrftAmnt']) ? cleanInputData($_POST['invNwPrftAmnt']) : 0;
                $invNewSllngPrice = isset($_POST['invNewSllngPrice']) ? cleanInputData($_POST['invNewSllngPrice']) : 0;
                $invPrftMrgnPrcnt = isset($_POST['invPrftMrgnPrcnt']) ? cleanInputData($_POST['invPrftMrgnPrcnt']) : 0;
                $invPrftMrgnAmnt = isset($_POST['invPrftMrgnAmnt']) ? cleanInputData($_POST['invPrftMrgnAmnt']) : 0;
                $invAssetAcntID = isset($_POST['invAssetAcntID']) ? (float) cleanInputData($_POST['invAssetAcntID']) : -1;
                $invCogsAcntID = isset($_POST['invCogsAcntID']) ? (float) cleanInputData($_POST['invCogsAcntID']) : -1;
                $invSRvnuAcntID = isset($_POST['invSRvnuAcntID']) ? (float) cleanInputData($_POST['invSRvnuAcntID']) : -1;
                $invSRetrnAcntID = isset($_POST['invSRetrnAcntID']) ? (float) cleanInputData($_POST['invSRetrnAcntID']) : -1;
                $invPRetrnAcntID = isset($_POST['invPRetrnAcntID']) ? (float) cleanInputData($_POST['invPRetrnAcntID']) : -1;
                $invExpnsAcntID = isset($_POST['invExpnsAcntID']) ? (float) cleanInputData($_POST['invExpnsAcntID']) : -1;
                $invItmOthrDesc = isset($_POST['invItmOthrDesc']) ? cleanInputData($_POST['invItmOthrDesc']) : '';
                $invItmExtrInfo = isset($_POST['invItmExtrInfo']) ? cleanInputData($_POST['invItmExtrInfo']) : '';
                $invItmGnrcNm = isset($_POST['invItmGnrcNm']) ? cleanInputData($_POST['invItmGnrcNm']) : '';
                $invItmTradeNm = isset($_POST['invItmTradeNm']) ? cleanInputData($_POST['invItmTradeNm']) : '';
                $invItmUslDsge = isset($_POST['invItmUslDsge']) ? cleanInputData($_POST['invItmUslDsge']) : '';
                $invItmMaxDsge = isset($_POST['invItmMaxDsge']) ? cleanInputData($_POST['invItmMaxDsge']) : '';
                $invItmCntrIndctns = isset($_POST['invItmCntrIndctns']) ? cleanInputData($_POST['invItmCntrIndctns']) : '';
                $invItmFoodIntrctns = isset($_POST['invItmFoodIntrctns']) ? cleanInputData($_POST['invItmFoodIntrctns']) : '';

                $isItmEnbld = isset($_POST['isItmEnbld']) ? cleanInputData($_POST['isItmEnbld']) : 'NO';
                $isPlnngEnbld = isset($_POST['isPlnngEnbld']) ? cleanInputData($_POST['isPlnngEnbld']) : 'NO';
                $autoLoadInVms = isset($_POST['autoLoadInVms']) ? cleanInputData($_POST['autoLoadInVms']) : 'NO';
                $oldItemID = getVMSItmID($invItemNm);
                $isItmEnbldVal = ($isItmEnbld == "NO") ? "0" : "1";
                $isPlnngEnbldVal = ($isPlnngEnbld == "NO") ? "0" : "1";
                $autoLoadInVmsVal = ($autoLoadInVms == "NO") ? "0" : "1";
                $errMsg = "";
                if ($invItemNm != "" && $invItemDesc != "" && ($oldItemID <= 0 || $oldItemID == $sbmtdItmID)) {
                    if ($sbmtdItmID <= 0) {
                        createVMSItm($invItemNm, $invItemDesc, $invItemCtgryID, $orgID, $isItmEnbldVal, $invSllngPrice, $invCogsAcntID, $invAssetAcntID, $invSRvnuAcntID, $invSRetrnAcntID, $invPRetrnAcntID, $invExpnsAcntID, $invTxCodeID, $invDscntCodeID, $invChrgCodeID, $invMinItmQty, $invMaxItmQty, $isPlnngEnbldVal, $invItemType, "", $invItmExtrInfo, $invItmOthrDesc, $invBaseUomID, $invTmpltID, $invItmGnrcNm, $invItmTradeNm, $invItmUslDsge, $invItmMaxDsge, $invItmCntrIndctns, $invItmFoodIntrctns, $invPriceLessTax, $invValCrncyID, $autoLoadInVmsVal);
                        $sbmtdItmID = getVMSItmID($invItemNm);
                    } else {
                        updateVMSItem($sbmtdItmID, $invItemNm, $invItemDesc, $invItemCtgryID, $orgID, $isItmEnbldVal, $invSllngPrice, $invCogsAcntID, $invAssetAcntID, $invSRvnuAcntID, $invSRetrnAcntID, $invPRetrnAcntID, $invExpnsAcntID, $invTxCodeID, $invDscntCodeID, $invChrgCodeID, $invMinItmQty, $invMaxItmQty, $isPlnngEnbldVal, $invItemType, "", $invItmExtrInfo, $invItmOthrDesc, $invBaseUomID, $invTmpltID, $invItmGnrcNm, $invItmTradeNm, $invItmUslDsge, $invItmMaxDsge, $invItmCntrIndctns, $invItmFoodIntrctns, $invPriceLessTax, $invValCrncyID, $autoLoadInVmsVal);
                    }
                    //Save Item Stores
                    $afftctd = 0;
                    $slctdItmStores = isset($_POST['slctdItmStores']) ? cleanInputData($_POST['slctdItmStores']) : '';
                    if (trim($slctdItmStores, "|~") != "") {
                        $variousRows = explode("|", trim($slctdItmStores, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 6) {
                                $lnStockID = (float) (cleanInputData1($crntRow[0]));
                                $lnStoreID = (float) cleanInputData1($crntRow[1]);
                                $lnShelves = cleanInputData1($crntRow[2]);
                                $lnShelveIDs = cleanInputData1($crntRow[3]);
                                $lnStrtDte = cleanInputData1($crntRow[4]);
                                $lnEndDte = cleanInputData1($crntRow[5]);
                                if ($lnStrtDte != "") {
                                    $lnStrtDte = cnvrtDMYTmToYMDTm($lnStrtDte);
                                } else {
                                    $lnStrtDte = getDB_Date_time();
                                }
                                if ($lnEndDte != "") {
                                    $lnEndDte = cnvrtDMYTmToYMDTm($lnEndDte);
                                }
                                $oldStockID = getVMSItemStockID($sbmtdItmID, $lnStoreID);
                                if ($oldStockID <= 0 && $lnStoreID > 0) {
                                    //Insert
                                    $afftctd += createVMSItemStore($sbmtdItmID, $lnStoreID, $lnShelves, $orgID, $lnStrtDte, $lnEndDte, $lnShelveIDs);
                                } else if ($lnStockID > 0) {
                                    $afftctd += updateVMSItemStore($oldStockID, $sbmtdItmID, $lnStoreID, $lnShelves, $orgID, $lnStrtDte, $lnEndDte, $lnShelveIDs);
                                }
                            }
                        }
                    }
                    //Save Item UOMs
                    $afftctd1 = 0;
                    $slctdItmUOMs = isset($_POST['slctdItmUOMs']) ? cleanInputData($_POST['slctdItmUOMs']) : '';
                    if (trim($slctdItmUOMs, "|~") != "") {
                        $variousRows = explode("|", trim($slctdItmUOMs, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 6) {
                                $lnLineID = (float) (cleanInputData1($crntRow[0]));
                                $lnUOMID = (float) cleanInputData1($crntRow[1]);
                                $lnCnvrsnFctr = (float) cleanInputData1($crntRow[2]);
                                $lnSortOrder = (float) cleanInputData1($crntRow[3]);
                                $lnSllngPrice = (float) cleanInputData1($crntRow[4]);
                                $lnPrcLsTx = (float) cleanInputData1($crntRow[5]);
                                $oldLnLineID = getVMSItemUomID($sbmtdItmID, $lnUOMID);
                                if ($oldLnLineID <= 0 && $lnUOMID > 0 && $lnUOMID != $invBaseUomID) {
                                    //Insert
                                    $afftctd1 += createVMSItemUom($sbmtdItmID, $lnUOMID, $lnCnvrsnFctr, $lnSortOrder, $lnPrcLsTx, $lnSllngPrice);
                                } else if ($oldLnLineID > 0 && $lnUOMID > 0 && $lnUOMID != $invBaseUomID) {
                                    $afftctd1 += updateVMSItemUom($oldLnLineID, $sbmtdItmID, $lnUOMID, $lnCnvrsnFctr, $lnSortOrder, $lnPrcLsTx, $lnSllngPrice);
                                }
                            }
                        }
                    }
                    //Save Item Interactions
                    $afftctd2 = 0;
                    $slctdItmIntrctns = isset($_POST['slctdItmIntrctns']) ? cleanInputData($_POST['slctdItmIntrctns']) : '';
                    if (trim($slctdItmIntrctns, "|~") != "") {
                        $variousRows = explode("|", trim($slctdItmStores, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 4) {
                                $lnLineID = (float) (cleanInputData1($crntRow[0]));
                                $lnDrugID = (float) cleanInputData1($crntRow[1]);
                                $lnIntrctn = cleanInputData1($crntRow[2]);
                                $lnAction = cleanInputData1($crntRow[3]);
                                $oldLineID = getVMSItemIntrctnID($sbmtdItmID, $lnDrugID);
                                if ($oldLineID <= 0 && $lnDrugID > 0) {
                                    //Insert
                                    $afftctd2 += createVMSItemIntrctn($sbmtdItmID, $lnDrugID, $lnIntrctn, $lnAction);
                                } else if ($oldLineID > 0) {
                                    $afftctd2 += updateVMSItemIntrctn($oldLineID, $sbmtdItmID, $lnDrugID, $lnIntrctn, $lnAction);
                                }
                            }
                        }
                    }
                    $nwImgLoc = "";
                    uploadDaImageItem($sbmtdItmID, $nwImgLoc);
                    $arr_content['percent'] = 100;
                    $arr_content['itemid'] = (float) $sbmtdItmID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Item Successfully Saved!<br/>" . $afftctd . " Item Store(s) Saved!<br/>" . $afftctd1 . " Item UoM(s) Saved!<br/>" . $afftctd2 . " Interaction(s) Saved!<br/>" . $nwImgLoc;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['itemid'] = (float) $sbmtdItmID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 5) {
                //Save Authorizer
                $afftctd = 0;
                $slctdVmsAthrzrs = isset($_POST['slctdVmsAthrzrs']) ? cleanInputData($_POST['slctdVmsAthrzrs']) : '';
                if (trim($slctdVmsAthrzrs, "|~") != "") {
                    //Save Persons
                    $variousRows = explode("|", trim($slctdVmsAthrzrs, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 8) {
                            $authrzrLmtID = (int) (cleanInputData1($crntRow[0]));
                            $authrzrLocID = cleanInputData1($crntRow[1]);
                            $authrzrPrsnID = getPersonID($authrzrLocID);
                            $siteID = (int) (cleanInputData1($crntRow[2]));
                            $trnsType = cleanInputData1($crntRow[3]);
                            $crncyNm = cleanInputData1($crntRow[4]);
                            $crncyID = getPssblValID($crncyNm, getLovID("Currencies"));
                            $minAmnt = (float) cleanInputData1($crntRow[5]);
                            $maxAmnt = (float) (cleanInputData1($crntRow[6]));
                            $isEnbld = cleanInputData1($crntRow[7]) == "YES" ? "1" : "0";
                            $oldAuthrzrLmtID = getAuthrzrLmtID($authrzrPrsnID, $siteID, $trnsType, $crncyID, $minAmnt, $maxAmnt);
                            if ($oldAuthrzrLmtID <= 0 && $authrzrLmtID <= 0) {
                                //Insert
                                $afftctd += createApprvrLmt($authrzrPrsnID, $crncyID, $minAmnt, $maxAmnt, $isEnbld, $orgID, $siteID, $trnsType);
                            } else if ($authrzrLmtID > 0) {
                                $afftctd += updateApprvrLmt($authrzrLmtID, $authrzrPrsnID, $crncyID, $minAmnt, $maxAmnt, $isEnbld, $siteID, $trnsType);
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $afftctd; ?>Authorizer Limits Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Save Authorizer Limits!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 6) {
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
                        createCstmr($cstmrSpplrNm, $cstmrSpplrDesc, $cstmrSpplrClsfctn, $cstmrSpplrType, $orgID, $cstmrLbltyAcntID, $cstmrRcvblsAcntID, $cstmrSpplrLnkdPrsnID, $cstmrSpplrGender, $cstmrSpplrDOB, $isCstmrEnbldVal, $cstmrCmpnyBrandNm, $cstmrOrgType, $cstmrRegNum, $cstmrDateIncprtd, $cstmrIncprtnType, $cstmrVatNumber, $cstmrTinNumber, $cstmrSsnitRegNum, $cstmrNumEmployees, $cstmrDescSrvcs, $cstmrListOfSrvcs);
                        $sbmtdCstmrSpplrID = getCstmrID($cstmrSpplrNm, $orgID);
                    } else {
                        updateCstmr($sbmtdCstmrSpplrID, $cstmrSpplrNm, $cstmrSpplrDesc, $cstmrSpplrClsfctn, $cstmrSpplrType, $orgID, $cstmrLbltyAcntID, $cstmrRcvblsAcntID, $cstmrSpplrLnkdPrsnID, $cstmrSpplrGender, $cstmrSpplrDOB, $isCstmrEnbldVal, $cstmrCmpnyBrandNm, $cstmrOrgType, $cmpnyRegNum, $cstmrDateIncprtd, $cstmrIncprtnType, $cstmrVatNumber, $cstmrTinNumber, $cstmrSsnitRegNum, $cstmrNumEmployees, $cstmrDescSrvcs, $cstmrListOfSrvcs);
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
            } else if ($actyp == 601) {
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
                        createCstmrSite($sbmtdCstmrSpplrID, $csSiteCntctPrsn, $csSiteCntctNos, $csSiteEmailAdrs, $csSiteName, $csSiteDesc, $csSiteBankNm, $csSiteBrnchNm, $csSiteAcntNum, $csSiteWthTxCodeID, $csSiteDscntCodeID, $csSiteBllngAddress, $csSiteShpngAddress, $csSiteSwftCode, $csSiteCountry, $csSiteIDType, $csSiteIDNum, $csSiteDateIsd, $csSiteExpryDate, $csSiteOtherInfo, $isCsSiteEnbldVal, $csSiteIbanCode, $accntCurID);
                        $sbmtdSiteID = getCstmrSiteID($csSiteName, $sbmtdCstmrSpplrID);
                    } else {
                        updateCstmrSite($sbmtdSiteID, $sbmtdCstmrSpplrID, $csSiteCntctPrsn, $csSiteCntctNos, $csSiteEmailAdrs, $csSiteName, $csSiteDesc, $csSiteBankNm, $csSiteBrnchNm, $csSiteAcntNum, $csSiteWthTxCodeID, $csSiteDscntCodeID, $csSiteBllngAddress, $csSiteShpngAddress, $csSiteSwftCode, $csSiteCountry, $csSiteIDType, $csSiteIDNum, $csSiteDateIsd, $csSiteExpryDate, $csSiteOtherInfo, $isCsSiteEnbldVal, $csSiteIbanCode, $accntCurID);
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
            } else if ($actyp == 7) {
                header("content-type:application/json");
                $glIntrfcTrnsID = isset($_POST['glIntrfcTrnsID']) ? (float) cleanInputData($_POST['glIntrfcTrnsID']) : -1;
                $glIntrfcTrnsDate = isset($_POST['glIntrfcTrnsDate']) ? cleanInputData($_POST['glIntrfcTrnsDate']) : '';
                $glIntrfcTrnsDesc = isset($_POST['glIntrfcTrnsDesc']) ? cleanInputData($_POST['glIntrfcTrnsDesc']) : '';
                $intrfcAccntID = isset($_POST['intrfcAccntID']) ? (float) cleanInputData($_POST['intrfcAccntID']) : -1;
                $incrsDcrs = isset($_POST['incrsDcrs']) ? cleanInputData($_POST['incrsDcrs']) : "";
                $enteredCrncyNm = isset($_POST['enteredCrncyNm']) ? cleanInputData($_POST['enteredCrncyNm']) : "";
                $enteredCrncyID = getPssblValID($enteredCrncyNm, getLovID("Currencies"));
                $enteredAmount = isset($_POST['enteredAmount']) ? (float) cleanInputData($_POST['enteredAmount']) : 0;
                $funcCrncyRate = isset($_POST['funcCrncyRate']) ? (float) cleanInputData($_POST['funcCrncyRate']) : 0;
                $accntCrncyRate = isset($_POST['accntCrncyRate']) ? (float) cleanInputData($_POST['accntCrncyRate']) : 0;
                $funcCrncyAmount = isset($_POST['funcCrncyAmount']) ? (float) cleanInputData($_POST['funcCrncyAmount']) : 0;
                $accntCrncyAmount = isset($_POST['accntCrncyAmount']) ? (float) cleanInputData($_POST['accntCrncyAmount']) : 0;
                $accntCurrID = (float) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $intrfcAccntID);
                $accntCurrNm = getPssblValNm($accntCurrID);
                $funcCurrID = getOrgFuncCurID($orgID);
                $funcCrncyNm = getPssblValNm($funcCurrID);
                $errMsg = "";
                //var_dump($_POST);
                //echo $glIntrfcTrnsDesc . ":" . $glIntrfcTrnsDate . ":" . $incrsDcrs . ":" . $intrfcAccntID . ":" . $enteredAmount . ":" . $enteredCrncyID;
                if ($glIntrfcTrnsDesc != "" && $glIntrfcTrnsDate != "" && $incrsDcrs != "" && $intrfcAccntID > 0 && $enteredAmount != 0 && $enteredCrncyID > 0) {
                    $netAmnt = (float) dbtOrCrdtAccntMultiplier($intrfcAccntID, substr($incrsDcrs, 0, 1)) * (float) $funcCrncyAmount;
                    if (!isTransPrmttd($intrfcAccntID, $glIntrfcTrnsDate, $netAmnt, $errMsg)) {
                        $arr_content['percent'] = 100;
                        $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Accounting not Allowed!<br/>$errMsg</span>";
                        echo json_encode($arr_content);
                        exit();
                    }
                    $netamnt = dbtOrCrdtAccntMultiplier($intrfcAccntID, substr($incrsDcrs, 0, 1)) * (float) $funcCrncyAmount;
                    $dateStr = getFrmtdDB_Date_time();

                    if (dbtOrCrdtAccnt($intrfcAccntID, substr($incrsDcrs, 0, 1)) == "Debit") {
                        if ($glIntrfcTrnsID <= 0) {
                            createVMSTrnsGLIntFcLn($intrfcAccntID, $glIntrfcTrnsDesc, $funcCrncyAmount, $glIntrfcTrnsDate, $funcCurrID, 0, $netamnt, "Imbalance Correction", -1, -1, $dateStr, "", "USR", $enteredAmount, $enteredCrncyID, $accntCrncyAmount, $accntCurrID, $funcCrncyRate, $accntCrncyRate);
                        } else {
                            updateVMSTrnsGLIntFcLn($glIntrfcTrnsID, $intrfcAccntID, $glIntrfcTrnsDesc, $funcCrncyAmount, $glIntrfcTrnsDate, $funcCurrID, 0, $netamnt, "Imbalance Correction", -1, -1, $dateStr, "", "USR", $enteredAmount, $enteredCrncyID, $accntCrncyAmount, $accntCurrID, $funcCrncyRate, $accntCrncyRate);
                        }
                    } else {
                        if ($glIntrfcTrnsID <= 0) {
                            createVMSTrnsGLIntFcLn($intrfcAccntID, $glIntrfcTrnsDesc, 0, $glIntrfcTrnsDate, $funcCurrID, $funcCrncyAmount, $netamnt, "Imbalance Correction", -1, -1, $dateStr, "", "USR", $enteredAmount, $enteredCrncyID, $accntCrncyAmount, $accntCurrID, $funcCrncyRate, $accntCrncyRate);
                        } else {
                            updateVMSTrnsGLIntFcLn($glIntrfcTrnsID, $intrfcAccntID, $glIntrfcTrnsDesc, $funcCrncyAmount, $glIntrfcTrnsDate, $funcCurrID, 0, $netamnt, "Imbalance Correction", -1, -1, $dateStr, "", "USR", $enteredAmount, $enteredCrncyID, $accntCrncyAmount, $accntCurrID, $funcCrncyRate, $accntCrncyRate);
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Interface Transaction Successfully Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                if ($srcMenu == "Banking") {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                        <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                } else {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                }
                echo $cntent . "<li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">VMS Administration</span>
				</li>
                               </ul>
                              </div>";
                $vaultTrns = array("Branches/Agencies", "Vaults", "Cages", "Stockable Item List", "Authorizers and Limits", "Vendors and Clients", "GL Interface Table");
                $vaultTrnsImgs = array("bank_256.png", "vault_folder_icon.png", "safe-icon.png", "assets1.jpg", "user_shield_filled1600.png", "customer.jpg", "GL-256.png");
                $cntent = "";
                $grpcntr = 0;
                for ($i = 0; $i < count($vaultTrns); $i++) {
                    $No = $i + 1;
                    if (test_prmssns($dfltPrvldgs[$i + 1], $mdlNm) == FALSE) {
                        continue;
                    }
                    if ($grpcntr == 0) {
                        $cntent .= "<div class=\"row\">";
                    }

                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block\" style=\"min-height:175px;height:173px;margin-bottom:5px;\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=4&vtyp=$No&srcMenu=$srcMenu');\">
            <img src=\"cmn_images/$vaultTrnsImgs[$i]\" style=\"margin:5px auto; height:78px; width:auto; position: relative; vertical-align: middle;float:none;\">
            <br/>
            <span class=\"wordwrap3\">" . ($vaultTrns[$i]) . "</span>
            <br/>&nbsp;
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
            } else if ($vwtyp == 1) {
                //Branches/Agencies
                $canAddLoc = test_prmssns($dfltPrvldgs[22], $mdlNm);
                $canEdtLoc = test_prmssns($dfltPrvldgs[23], $mdlNm);
                $canDelLoc = test_prmssns($dfltPrvldgs[24], $mdlNm);
                if ($srcMenu == "Banking") {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                        <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                } else {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                }
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">VMS Administration</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">All Branches/Agencies</span>
				</li>
                               </ul>
                              </div>";
                $total = get_SitesLocsTtl(-1, -1, $srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_SitesLocs(-1, -1, $srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-4";
                $prm = get_CurPlcy_Mx_Fld_lgns();
                ?>
                <form id='allVmsLocsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddLoc === true) {
                            ?> 
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsBrnchsForm(-1, 'ShowDialog');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Branch/Agency
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-5";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allVmsLocsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllVmsLocs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                <input id="allVmsLocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsLocs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsLocs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsLocsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Site Name", "Site Description");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsLocsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllVmsLocs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllVmsLocs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allVmsLocsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Branch Name / Description</th>
                                        <th>Allowed Group Type</th>
                                        <th>Allowed Group Name</th>
                                        <th>Site Type</th>
                                        <th>Enabled?</th>
                                        <th>&nbsp;</th>
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
                                        <tr id="allVmsLocsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>   
                                            <td class="lovtd">
                                                <?php
                                                echo str_replace(" (" . $row[1] . ")", "", $row[1] . " (" . $row[2] . ")");
                                                ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allVmsLocsRow<?php echo $cntr; ?>_SiteID" value="<?php echo $row[0]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[7]; ?></td>
                                            <td class="lovtd"><?php echo $row[8]; ?></td>
                                            <td class="lovtd"><?php echo $row[6]; ?></td>
                                            <td class="lovtd">
                                                <?php
                                                $isChkd = "";
                                                if ($row[3] == "Yes") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <div class="form-group form-group-sm">
                                                    <div class="form-check" style="font-size: 12px !important;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" id="allVmsLocsRow<?php echo $cntr; ?>_IsEnabled" name="allCstmrsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?> disabled="true">
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneVmsBrnchsForm(<?php echo $row[0]; ?>, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canDelLoc === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsBrnchs('allVmsLocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Branch">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|org.org_sites_locations|location_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
            } else if ($vwtyp == 101) {
                //New Branch Form
                $canEdtBrnch = test_prmssns($dfltPrvldgs[22], $mdlNm) || test_prmssns($dfltPrvldgs[23], $mdlNm);
                $sbmtdSiteID = isset($_POST['sbmtdSiteID']) ? cleanInputData($_POST['sbmtdSiteID']) : -1;

                $siteBrnchNm = "";
                $siteBrnchDesc = "";
                $siteBrnchType = "";
                $grpType = "";
                $allwdGroupName = "";
                $allwdGroupID = -1;
                $isEnbld = "No";
                if ($sbmtdSiteID > 0) {
                    $result = get_OneSiteLocDet($sbmtdSiteID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdSiteID = (float) $row[0];
                        $siteBrnchNm = $row[1];
                        $siteBrnchDesc = $row[2];
                        $siteBrnchType = $row[5];
                        $grpType = $row[6];
                        $allwdGroupName = $row[7];
                        $allwdGroupID = (float) $row[8];
                        $isEnbld = $row[3];
                    }
                }
                ?>
                <form class="form-horizontal" id='branchStpForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0px 15px !important;">
                            <div class="col-md-6" style="padding: 0px 5px 0px 5px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="siteBrnchNm" class="control-label">Site Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtBrnch === true) { ?> 
                                                <input type="text" name="siteBrnchNm" id="siteBrnchNm" class="form-control rqrdFld" value="<?php echo $siteBrnchNm; ?>" style="width:100% !important;">
                                                <input type="hidden" name="sbmtdSiteID" id="sbmtdSiteID" class="form-control" value="<?php echo $sbmtdSiteID; ?>">
                                            <?php } else { ?>
                                                <span><?php echo $siteBrnchNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="siteBrnchDesc" class="control-label">Site Description:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                            <?php if ($canEdtBrnch === true) { ?>
                                                <textarea rows="6" name="siteBrnchDesc" id="siteBrnchDesc" class="form-control rqrdFld"><?php echo $siteBrnchDesc; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $siteBrnchDesc; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-md-6" style="padding: 1px !important;">
                                <div class="form-group" >
                                    <div class="col-md-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            &nbsp;
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                <label for="isSiteEnbld" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdtBrnch === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($isEnbld == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isSiteEnbld" id="isSiteEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">                                           
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="grpType" class="control-label">Allowed Group Type:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtBrnch === true) { ?>
                                                <select class="form-control" id="grpType" onchange="grpTypMcfChangeV();">                                                        
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                                    $valuesArrys = array("Everyone", "Divisions/Groups",
                                                        "Grade", "Job", "Position", "Site/Location", "Person Type", "Single Person");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($grpType == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $grpType; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top:5px !important;">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="allwdGroupNm" class="control-label">Allowed Group Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtBrnch === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="allwdGroupName" value="<?php echo $allwdGroupName; ?>" readonly="">
                                                    <input type="hidden" id="allwdGroupID" value="<?php echo $allwdGroupID; ?>">
                                                    <label disabled="true" id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', '', 'radio', true, '', 'allwdGroupID', 'allwdGroupName', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $allwdGroupName; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="siteBrnchType" class="control-label">Site Type:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtBrnch === true) { ?>                                   
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" name="siteBrnchType" id="siteBrnchType" class="form-control rqrdFld" value="<?php echo $siteBrnchType; ?>" readonly="true" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Site Types', '', '', '', 'radio', true, '', 'siteBrnchType', 'siteBrnchType', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $siteBrnchType; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:3px 0px 3px 0px;"></div>                       
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdtBrnch === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveVmsBrnchsForm();">Save Changes</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            } else if ($vwtyp == 2) {
                //Vaults
                $canAddVlt = test_prmssns($dfltPrvldgs[25], $mdlNm);
                $canEdtVlt = test_prmssns($dfltPrvldgs[26], $mdlNm);
                $canDelVlt = test_prmssns($dfltPrvldgs[27], $mdlNm);
                if ($srcMenu == "Banking") {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                        <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                } else {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                }
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">VMS Administration</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">All Vaults</span>
				</li>
                               </ul>
                              </div>";
                $total = get_SitesVaultsTtl(-1, -1, $srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_SitesVaults(-1, -1, $srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-4";
                $prm = get_CurPlcy_Mx_Fld_lgns();
                ?>
                <form id='allVmsVltsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddVlt === true) {
                            ?> 
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsVltsForm(-1);">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Vault
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-5";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allVmsVltsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllVmsVlts(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                <input id="allVmsVltsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsVlts('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsVlts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsVltsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Vault Name", "Vault Description", "Site Name");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsVltsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllVmsVlts('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllVmsVlts('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allVmsVltsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Vault Name / Description</th>
                                        <th>Managed By</th>
                                        <th style="text-align:right;">CUR.</th>
                                        <th style="text-align:right;">Total Vault Balance</th>
                                        <th style="text-align:right;">Posted Account Balance</th>
                                        <th>Vault Account</th>
                                        <th>Branch / Agency</th>
                                        <th>Enabled?</th>
                                        <th>&nbsp;</th>
                                        <?php if ($canDelVlt === true) { ?>
                                            <th>&nbsp;</th>
                                        <?php } ?>
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
                                        <tr id="allVmsVltsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td> 
                                            <td class="lovtd">
                                                <?php
                                                echo str_replace(" (" . $row[1] . ")", "", $row[1] . " (" . $row[2] . ")");
                                                ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allVmsVltsRow<?php echo $cntr; ?>_VltID" value="<?php echo $row[0]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="allVmsVltsRow<?php echo $cntr; ?>_SiteID" value="<?php echo $row[12]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[5]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight: bold;"><?php echo $row[8]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                echo number_format((float) $row[11], 2);
                                                ?>
                                            </td>
                                            <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                echo number_format((float) $row[10], 2);
                                                ?>
                                            </td>
                                            <td class="lovtd"><?php echo $row[7]; ?></td>
                                            <td class="lovtd"><?php echo $row[13]; ?></td>
                                            <td class="lovtd" style="text-align: center;">
                                                <?php
                                                $isChkd = "";
                                                if ($row[3] == "Yes") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <div class="form-group form-group-sm">
                                                    <div class="form-check" style="font-size: 12px !important;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" id="allVmsVltsRow<?php echo $cntr; ?>_IsEnabled" name="allCstmrsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?> disabled="true">
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneVmsVltsForm(<?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canDelVlt === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsVlts('allVmsVltsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Vault">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|inv.inv_itm_subinventories|subinv_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
            } else if ($vwtyp == 201) {
                //New Vault Form
                $canEdtVlt = test_prmssns($dfltPrvldgs[25], $mdlNm) || test_prmssns($dfltPrvldgs[26], $mdlNm);
                $canAddCage = test_prmssns($dfltPrvldgs[28], $mdlNm);
                $canEdtCage = test_prmssns($dfltPrvldgs[29], $mdlNm);
                $canDelCage = test_prmssns($dfltPrvldgs[30], $mdlNm);
                $sbmtdVltID = isset($_POST['sbmtdVltID']) ? (float) cleanInputData($_POST['sbmtdVltID']) : -1;
                $sbmtdSiteID = isset($_POST['sbmtdSiteID']) ? (float) cleanInputData($_POST['sbmtdSiteID']) : -1;
                $vaultNm = "";
                $vaultDesc = "";
                $vaultAddress = "";
                $lnkdSiteID = -1;
                $lnkdSiteNm = "";
                $lnkdGLAccountNm = "";
                $lnkdGLAccountID = -1;
                $grpType = "";
                $allwdGroupName = "";
                $allwdGroupID = -1;

                $vltMngrsPrsnID = -1;
                $vltMngrsName = "";
                $isSalesAllwd = "No";
                $isEnbld = "No";
                if ($sbmtdVltID > 0) {
                    $result = get_OneVaultDet($sbmtdVltID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdVltID = (float) $row[0];
                        $vaultNm = $row[1];
                        $vaultDesc = $row[2];
                        $vaultAddress = $row[3];
                        $lnkdSiteID = (float) $row[4];
                        $lnkdSiteNm = $row[5];
                        $lnkdGLAccountNm = $row[7];
                        $lnkdGLAccountID = (float) $row[6];
                        $grpType = $row[9];
                        $allwdGroupName = $row[8];
                        $allwdGroupID = (float) $row[10];

                        $vltMngrsPrsnID = (float) $row[11];
                        $vltMngrsName = $row[12];
                        $isSalesAllwd = $row[13];
                        $isEnbld = $row[14];
                    }
                } else if ($sbmtdSiteID > 0) {
                    $lnkdSiteID = $sbmtdSiteID;
                    $lnkdSiteNm = getGnrlRecNm("org.org_sites_locations", "location_id", "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $sbmtdSiteID);
                }
                ?>
                <form class="form-horizontal" id='vaultStpForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0px 15px !important;">
                            <div class="col-md-6" style="padding: 0px 5px 0px 5px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="vaultNm" class="control-label">Vault Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtVlt === true) { ?> 
                                                <input type="text" name="vaultNm" id="vaultNm" class="form-control rqrdFld" value="<?php echo $vaultNm; ?>" style="width:100% !important;">
                                                <input type="hidden" name="sbmtdVltID" id="sbmtdVltID" class="form-control" value="<?php echo $sbmtdVltID; ?>">
                                            <?php } else { ?>
                                                <span><?php echo $vaultNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="vaultDesc" class="control-label">Description:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                            <?php if ($canEdtVlt === true) { ?>
                                                <textarea rows="3" name="vaultDesc" id="vaultDesc" class="form-control rqrdFld"><?php echo $vaultDesc; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $vaultDesc; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="vaultAddress" class="control-label">Address:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                            <?php if ($canEdtVlt === true) { ?>
                                                <textarea rows="3" name="vaultAddress" id="vaultAddress" class="form-control"><?php echo $vaultAddress; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $vaultAddress; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="lnkdSiteNm" class="control-label">Site Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtVlt === true) { ?>                                   
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" name="lnkdSiteNm" id="lnkdSiteNm" class="form-control rqrdFld" value="<?php echo $lnkdSiteNm; ?>" readonly="true" style="width:100% !important;">
                                                    <input type="hidden" name="lnkdSiteID" id="lnkdSiteID" class="form-control" value="<?php echo $lnkdSiteID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', '', '', '', 'radio', true, '', 'lnkdSiteID', 'lnkdSiteNm', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $lnkdSiteNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-md-6" style="padding: 1px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="lnkdGLAccountNm" class="control-label">Linked GL Account:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtVlt === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="lnkdGLAccountNm" id="lnkdGLAccountNm" class="form-control" value="<?php echo $lnkdGLAccountNm; ?>" readonly="true">
                                                    <input type="hidden" name="lnkdGLAccountID" id="lnkdGLAccountID" class="form-control" value="<?php echo $lnkdGLAccountID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'lnkdGLAccountID', 'lnkdGLAccountNm', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $lnkdGLAccountNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <div class="col-md-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            &nbsp;
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                <label for="isVltEnbld" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdtVlt === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($isEnbld == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isVltEnbld" id="isVltEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">                                           
                                            <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                <label for="isSalesAllwd" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdtVlt === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($isSalesAllwd == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isSalesAllwd" id="isSalesAllwd" <?php echo $isChkd . " " . $isRdOnly; ?>>Sales Allowed?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="grpType" class="control-label">Allowed Group Type:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtVlt === true) { ?>
                                                <select class="form-control" id="grpType" onchange="grpTypMcfChangeV();">                                                        
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                                    $valuesArrys = array("Everyone", "Divisions/Groups",
                                                        "Grade", "Job", "Position", "Site/Location", "Person Type", "Single Person");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($grpType == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $grpType; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="allwdGroupNm" class="control-label">Allowed Group Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtVlt === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="allwdGroupName" value="<?php echo $allwdGroupName; ?>" readonly="">
                                                    <input type="hidden" id="allwdGroupID" value="<?php echo $allwdGroupID; ?>">
                                                    <label disabled="true" id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', '', 'radio', true, '', 'allwdGroupID', 'allwdGroupName', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $allwdGroupName; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="vltMngrsName" class="control-label">Vault Manager's Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtVlt === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="vltMngrsName" id="vltMngrsName" class="form-control" value="<?php echo $vltMngrsName; ?>" readonly="true">
                                                    <input type="hidden" name="vltMngrsPrsnID" id="vltMngrsPrsnID" class="form-control" value="<?php echo $vltMngrsPrsnID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'vltMngrsPrsnID', 'vltMngrsName', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div> 
                                            <?php } else { ?>
                                                <span><?php echo $vltMngrsName; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:3px 0px 3px 0px;"></div>
                        <?php if ($sbmtdVltID > 0) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                        <li class="active"><a data-toggle="tabajxstores" data-rhodata="" href="#vaultCages" id="vaultCagestab" style="padding: 3px 10px !important;">Shelves/Cages</a></li>
                                        <li><a data-toggle="tabajxstores" data-rhodata="" href="#vaultUsers" id="vaultUserstab" style="padding: 3px 10px !important;">Manage Users</a></li>
                                    </ul>
                                    <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneStoreDetTblSctn"> 
                                        <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                            <div id="vaultCages" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php if ($canAddCage === true) { ?>
                                                            <button id="addNwVaultBtn1" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsCgFormV(-1, 'Vaults', <?php echo $sbmtdVltID; ?>, <?php echo $lnkdSiteID; ?>);" data-toggle="tooltip" data-placement="bottom" title = "New Vault Cage/Shelf">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Add Vault Cage/Shelf
                                                            </button>
                                                        <?php } ?>
                                                        <button id="refreshVltBtn1" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsVltsForm(<?php echo $sbmtdVltID; ?>, 'ReloadDialog', 'FROMBRNCH', <?php echo $lnkdSiteID; ?>);" data-toggle="tooltip" data-placement="bottom" title = "Reload Vault Cage/Shelf">
                                                            <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Refresh
                                                        </button>
                                                    </div>                                            
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="oneStoreCagesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:50px;width:50px;">No.</th>
                                                                    <th>Name</th>
                                                                    <th>Description</th>
                                                                    <th style="max-width:75px;width:75px;">Enabled?</th>
                                                                    <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                    <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                    <?php if ($canDelCage === true) { ?>
                                                                        <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                    <?php } ?>
                                                                    <?php if ($canVwRcHstry === true) { ?>
                                                                        <th>...</th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $rslt = get_OneVaultCages($sbmtdVltID);
                                                                $cntrCg = 0;
                                                                while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                    $cntrCg++;
                                                                    $cgName = $rwCage[15];
                                                                    $cgDesc = $rwCage[16];
                                                                    $cageEnbld = $rwCage[7];
                                                                    $clientMgnrNm = -1;
                                                                    ?>
                                                                    <tr id="oneStoreCagesRow_<?php echo $cntrCg; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($rwCage[0]); ?></span></td>
                                                                        <td class="lovtd"><span><?php echo $cgName; ?></span></td>
                                                                        <td class="lovtd"><span><?php echo $cgDesc; ?></span></td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <?php
                                                                            $isChkd = "";
                                                                            if ($cageEnbld == "1") {
                                                                                $isChkd = "checked=\"true\"";
                                                                            }
                                                                            ?>
                                                                            <div class="form-group form-group-sm">
                                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                                    <label class="form-check-label">
                                                                                        <input type="checkbox" class="form-check-input" id="oneStoreCagesRow<?php echo $cntrCg; ?>_IsEnabled" name="oneStoreCagesRow<?php echo $cntrCg; ?>_IsEnabled" <?php echo $isChkd ?> disabled="true">
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Statistics" onclick="chckMyTillPosV('ShowDialog', 'grp=25&typ=1&pg=1&vtyp=3&isFrmBnkng=1&sbmtdCageID=<?php echo $rwCage[2]; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                <img src="cmn_images/statistics_32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneVmsCgFormV(<?php echo $rwCage[2]; ?>, 'Vaults');" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <?php if ($canDelCage === true) { ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsCgV('oneStoreCagesRow_<?php echo $cntrCg; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Cage">
                                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <?php if ($canVwRcHstry === true) { ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($rwCage[2] . "|inv.inv_shelf|line_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                                            </div>                                    
                                            <div id="vaultUsers" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">
                                                <?php
                                                $nwRowHtml = urlencode("<tr id=\"oneStoreUsersRow__WWW123WWW\">"
                                                        . "<td class=\"lovtd\"><span>New</span></td>"
                                                        . "<td class=\"lovtd\">
                                                                            <div class=\"form-group form-group-sm col-md-12\">
                                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneStoreUsersRow_WWW123WWW_UsrNm\" value=\"\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneStoreUsersRow_WWW123WWW_UsrID\" value=\"-1\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneStoreUsersRow_WWW123WWW_LineID\" value=\"-1\">
                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Users', '', '', '', 'radio', true, '-1', 'oneStoreUsersRow_WWW123WWW_UsrID', 'oneStoreUsersRow_WWW123WWW_UsrNm', 'clear', 1, '');\">
                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class=\"lovtd\"> 
                                                                            <div class=\"form-group form-group-sm col-md-12\">
                                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneStoreUsersRow_WWW123WWW_StrtDte\" value=\"\" readonly=\"true\">
                                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                </div>                                                                
                                                                            </div>
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <div class=\"form-group form-group-sm col-md-12\">
                                                                                <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                                    <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneStoreUsersRow_WWW123WWW_EndDte\" value=\"\" readonly=\"true\">
                                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                                </div>                                                                
                                                                            </div>
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delVmsVltUsrs('oneStoreUsersRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete User\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>");
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php if ($canEdtVlt === true) { ?>
                                                            <button id="addNwVaultBtn2" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('oneStoreUsersTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Vault User">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Add Vault User
                                                            </button>
                                                        <?php } ?>
                                                        <button id="refreshVltBtn2" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsVltsForm(<?php echo $sbmtdVltID; ?>, 'ReloadDialog', 'FROMBRNCH', <?php echo $lnkdSiteID; ?>);" data-toggle="tooltip" data-placement="bottom" title = "Reload Vault Cage/Shelf">
                                                            <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Refresh
                                                        </button>
                                                    </div>                                            
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="oneStoreUsersTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:50px;width:50px;">No.</th>
                                                                    <th >User Name</th>
                                                                    <th >Start Date</th>
                                                                    <th >End Date</th>
                                                                    <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                    <?php if ($canVwRcHstry === true) { ?>
                                                                        <th>...</th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $rslt = get_OneVaultUsers($sbmtdVltID);
                                                                $cntrUsr = 0;
                                                                while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                    $cntrUsr++;
                                                                    $usName = $rwCage[2];
                                                                    $usStartDte = $rwCage[3];
                                                                    $usEndDte = $rwCage[4];
                                                                    ?>
                                                                    <tr id="oneStoreUsersRow_<?php echo $cntrUsr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($rwCage[0]); ?></span></td>
                                                                        <td class="lovtd">
                                                                            <?php if ($canEdtVlt === true) { ?>
                                                                                <div class="form-group form-group-sm col-md-12">
                                                                                    <div class="input-group"  style="width:100%;">
                                                                                        <input type="text" class="form-control" aria-label="..." id="oneStoreUsersRow<?php echo $cntrUsr; ?>_UsrNm" value="<?php echo $usName; ?>">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneStoreUsersRow<?php echo $cntrUsr; ?>_UsrID" value="<?php echo $rwCage[1]; ?>">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneStoreUsersRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $rwCage[5]; ?>">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Users', '', '', '', 'radio', true, '<?php echo $rwCage[1]; ?>', 'oneStoreUsersRow<?php echo $cntrUsr; ?>_UsrID', 'oneStoreUsersRow<?php echo $cntrUsr; ?>_UsrNm', 'clear', 1, '');">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span><?php echo $usName; ?></span>
                                                                            <?php } ?> 
                                                                        </td>
                                                                        <td class="lovtd">                                                                            
                                                                            <?php if ($canEdtVlt === true) { ?>
                                                                                <div class="form-group form-group-sm col-md-12">
                                                                                    <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                        <input class="form-control" size="16" type="text" id="oneStoreUsersRow<?php echo $cntrUsr; ?>_StrtDte" value="<?php echo $usStartDte; ?>" readonly="true">
                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                    </div>                                                                
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span><?php echo $usStartDte; ?></span>
                                                                            <?php } ?> 
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php if ($canEdtVlt === true) { ?>
                                                                                <div class="form-group form-group-sm col-md-12">
                                                                                    <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                        <input class="form-control" size="16" type="text" id="oneStoreUsersRow<?php echo $cntrUsr; ?>_EndDte" value="<?php echo $usEndDte; ?>" readonly="true">
                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                    </div>                                                                
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <span><?php echo $usEndDte; ?></span>
                                                                            <?php } ?>  
                                                                        </td>
                                                                        <?php if ($canEdtVlt === true) { ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsVltUsrs('oneStoreUsersRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete User">
                                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <?php if ($canVwRcHstry === true) { ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($rwCage[5] . "|inv.inv_user_subinventories|line_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdtVlt === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveVmsVltsForm(<?php echo $sbmtdSiteID; ?>);">Save Changes</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            } else if ($vwtyp == 3) {
                //Cages
                $canAddCage = test_prmssns($dfltPrvldgs[28], $mdlNm);
                $canEdtCage = test_prmssns($dfltPrvldgs[29], $mdlNm);
                $canDelCage = test_prmssns($dfltPrvldgs[30], $mdlNm);
                if ($srcMenu == "Banking") {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                        <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                } else {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                }
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">VMS Administration</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">All Cages</span>
				</li>
                               </ul>
                              </div>";
                $total = get_VaultCagesTtl(-1, -1, $srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_VaultCages(-1, -1, $srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-4";
                $prm = get_CurPlcy_Mx_Fld_lgns();
                ?>
                <form id='allVmsCgsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddCage === true) {
                            ?> 
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsCgFormV(-1);">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Cage
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-5";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allVmsCgsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllVmsCgsV(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                <input id="allVmsCgsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsCgsV('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsCgsV('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsCgsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Cage Name", "Cage Description", "Vault Name");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsCgsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllVmsCgsV('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllVmsCgsV('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allVmsCgsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>&nbsp;</th>
                                        <th>Cage Name / Description (Client Owner)</th>
                                        <th>Managed By</th>
                                        <th style="text-align:right;">CUR.</th>
                                        <th style="text-align:right;">Cage Balance</th>
                                        <th style="text-align:right;">Posted Account Balance</th>
                                        <th>Cage Account</th>
                                        <th>Enabled?</th>
                                        <th>&nbsp;</th>
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
                                        <tr id="allVmsCgsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>                                            
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to View Cage Item Balances" onclick="chckMyTillPosV('ShowDialog', 'grp=25&typ=1&pg=1&vtyp=3&isFrmBnkng=1&sbmtdCageID=<?php echo $row[0]; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/statistics_32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd">
                                                <?php
                                                echo $row[16] . " - " . $row[14] . " - " . str_replace(" (" . $row[1] . ")", "", $row[1] . " (" . $row[2] . ")") . " " . $row[6];
                                                ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allVmsCgsRow<?php echo $cntr; ?>_LineID" value="<?php echo $row[0]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="allVmsCgsRow<?php echo $cntr; ?>_CageID" value="<?php echo $row[4]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="allVmsCgsRow<?php echo $cntr; ?>_VltID" value="<?php echo $row[13]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="allVmsCgsRow<?php echo $cntr; ?>_SiteID" value="<?php echo $row[15]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[12]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight: bold;"><?php echo $row[17]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                echo number_format((float) $row[20], 2);
                                                ?>
                                            </td>
                                            <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                echo number_format((float) $row[19], 2);
                                                ?>
                                            </td>
                                            <td class="lovtd"><?php echo $row[10]; ?></td>
                                            <td class="lovtd" style="text-align: center;">
                                                <?php
                                                $isChkd = "";
                                                if ($row[3] == "Yes") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <div class="form-group form-group-sm">
                                                    <div class="form-check" style="font-size: 12px !important;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" id="allVmsCgsRow<?php echo $cntr; ?>_IsEnabled" name="allCstmrsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?> disabled="true">
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneVmsCgFormV(<?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canDelCage === true) { ?>
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsCgV('allVmsCgsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete User">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                <?php } else { ?>
                                                    &nbsp;
                                                <?php } ?>
                                            </td>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|inv.inv_shelf|line_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
            } else if ($vwtyp == 301) {
                //New Cage Form
                $canEdtCage = test_prmssns($dfltPrvldgs[28], $mdlNm);
                $sbmtdCageID = isset($_POST['sbmtdCageID']) ? cleanInputData($_POST['sbmtdCageID']) : -1;
                $cageVltID = isset($_POST['cageVltID']) ? (float) cleanInputData($_POST['cageVltID']) : -1;
                $cageLineID = $sbmtdCageID;
                $cageShelfNm = "";
                $cageShelfID = -1;
                $cageVltNm = "";
                $cageDesc = "";
                $cageOwnersCstmrID = -1;
                $cageOwnersCstmrNm = "";
                $lnkdGLAccountNm = "";
                $lnkdGLAccountID = -1;
                $grpType = "";
                $allwdGroupName = "";
                $allwdGroupID = -1;
                $cageMngrsPrsnID = -1;
                $cageMngrsName = "";
                $mngrsWithdrawlLmt = 0;
                $mngrsDepositLmt = 0;
                $dfltItemType = "";
                $dfltItemState = "";
                $isEnbld = "No";
                if ($sbmtdCageID > 0) {
                    $result = get_OneCageDet($sbmtdCageID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdCageID = (float) $row[0];
                        $cageShelfID = (float) $row[4];
                        $cageLineID = $sbmtdCageID;
                        $cageShelfNm = $row[1];
                        $cageVltID = (float) $row[13];
                        $cageVltNm = $row[14];
                        $cageDesc = $row[2];
                        $cageOwnersCstmrID = (float) $row[5];
                        $cageOwnersCstmrNm = $row[6];
                        $lnkdGLAccountNm = $row[10];
                        $lnkdGLAccountID = (float) $row[9];
                        $grpType = $row[7];
                        $allwdGroupName = $row[21];
                        $allwdGroupID = (float) $row[8];
                        $cageMngrsPrsnID = (float) $row[11];
                        $cageMngrsName = $row[12];
                        $mngrsWithdrawlLmt = (float) $row[22];
                        $mngrsDepositLmt = (float) $row[23];
                        $dfltItemType = $row[24];
                        $dfltItemState = $row[25];
                        $isEnbld = $row[3];
                    }
                } else if ($cageVltID > 0) {
                    $cageVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $cageVltID);
                }
                ?>
                <form class="form-horizontal" id='mcfTillStpForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="cageShelfNm" class="control-label">Vault Cage Name:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>                                   
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" name="cageShelfNm" id="cageShelfNm" class="form-control rqrdFld" value="<?php echo $cageShelfNm; ?>" style="width:100% !important;">
                                                    <input type="hidden" name="cageLineID" id="cageLineID" class="form-control" value="<?php echo $cageLineID; ?>">
                                                    <input type="hidden" name="cageShelfID" id="cageShelfID" class="form-control" value="<?php echo $cageShelfID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Shelves', '', '', '', 'radio', true, '', 'cageShelfNm', 'cageDesc', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $cageShelfNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="cageDesc" class="control-label">Description:</label>
                                        </div>
                                        <div class="col-md-8">     
                                            <?php if ($canEdtCage === true) { ?>
                                                <textarea rows="2" name="cageDesc" id="cageDesc" class="form-control rqrdFld"><?php echo $cageDesc; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $cageDesc; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="cageVltNm" class="control-label">Linked Vault Name:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>                                   
                                                <div class="input-group" style="width:100% !important;">
                                                    <input type="text" name="cageVltNm" id="cageVltNm" class="form-control rqrdFld" value="<?php echo $cageVltNm; ?>" readonly="true" style="width:100% !important;">
                                                    <input type="hidden" name="cageVltID" id="cageVltID" class="form-control" value="<?php echo $cageVltID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vaults', '', '', '', 'radio', true, '', 'cageVltID', 'cageVltNm', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $cageVltNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="cageOwnersCstmrNm" class="control-label">Client Owner:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="cageOwnersCstmrNm" id="cageOwnersCstmrNm" class="form-control" value="<?php echo $cageOwnersCstmrNm; ?>" readonly="true">
                                                    <input type="hidden" name="cageOwnersCstmrID" id="cageOwnersCstmrID" class="form-control" value="<?php echo $cageOwnersCstmrID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', '', 'radio', true, '', 'cageOwnersCstmrID', 'cageOwnersCstmrNm', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $cageOwnersCstmrNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="lnkdGLAccountNm1" class="control-label">Linked GL Account:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="lnkdGLAccountNm1" id="lnkdGLAccountNm1" class="form-control rqrdFld" value="<?php echo $lnkdGLAccountNm; ?>" readonly="true">
                                                    <input type="hidden" name="lnkdGLAccountID1" id="lnkdGLAccountID1" class="form-control" value="<?php echo $lnkdGLAccountID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'lnkdGLAccountID1', 'lnkdGLAccountNm1', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $lnkdGLAccountNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            &nbsp;
                                        </div>
                                        <div class="col-md-4">
                                            <div class="checkbox">
                                                <label for="isCageEnbld" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdtCage === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($isEnbld == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isCageEnbld" id="isCageEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="grpType" class="control-label">Allowed Group Type:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>
                                                <select class="form-control" id="grpType" onchange="grpTypMcfChangeV();">                                                        
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                                    $valuesArrys = array("Everyone", "Divisions/Groups",
                                                        "Grade", "Job", "Position", "Site/Location", "Person Type", "Single Person");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($grpType == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $grpType; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="allwdGroupNm" class="control-label">Allowed Group Name:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="allwdGroupName" value="<?php echo $allwdGroupName; ?>" readonly="">
                                                    <input type="hidden" id="allwdGroupID" value="<?php echo $allwdGroupID; ?>">
                                                    <label disabled="true" id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', '', 'radio', true, '', 'allwdGroupID', 'allwdGroupName', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $allwdGroupName; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="cageMngrsName" class="control-label">Cage/Till Manager's Name:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="cageMngrsName" id="cageMngrsName" class="form-control" value="<?php echo $cageMngrsName; ?>" readonly="true">
                                                    <input type="hidden" name="cageMngrsPrsnID" id="cageMngrsPrsnID" class="form-control" value="<?php echo $cageMngrsPrsnID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'cageMngrsPrsnID', 'cageMngrsName', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div> 
                                            <?php } else { ?>
                                                <span><?php echo $cageMngrsName; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="mngrsWithdrawlLmt" class="control-label">Manager's Withdrawal Limit:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>                                   
                                                <input type="text" name="mngrsWithdrawlLmt" id="mngrsWithdrawlLmt" class="form-control" value="<?php echo $mngrsWithdrawlLmt; ?>">                                                        
                                            <?php } else { ?>
                                                <span><?php echo $mngrsWithdrawlLmt; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="mngrsDepositLmt" class="control-label">Manager's Deposit Limit:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>                                 
                                                <input type="text" name="mngrsDepositLmt" id="mngrsDepositLmt" class="form-control" value="<?php echo $mngrsDepositLmt; ?>">                                                        
                                            <?php } else { ?>
                                                <span><?php echo $mngrsDepositLmt; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="dfltItemType" class="control-label">Default Item Type:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>
                                                <select class="form-control rqrdFld" id="dfltItemType">
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "");
                                                    $valuesArrys = array("Merchandise Inventory", "Non-Merchandise Inventory",
                                                        "Fixed Assets", "Expense Item", "Services", "VaultItem-Cash", "VaultItem-NonCash");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($dfltItemType == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $dfltItemType; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="dfltItemState" class="control-label">Default Item State:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtCage === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="dfltItemState" id="dfltItemState" class="form-control rqrdFld" value="<?php echo $dfltItemState; ?>" readonly="true">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', 'dfltItemState', '', 'clear', 0, '', function () {
                                                                var aa112 = 1;
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div> 
                                            <?php } else { ?>
                                                <span><?php echo $dfltItemState; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>                               
                        </div>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdtCage === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveVmsCgFormV();">Save Changes</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            } else if ($vwtyp == 4) {
                //Stockable Item List
                $canAddItm = test_prmssns($dfltPrvldgs[31], $mdlNm);
                $canEdtItm = test_prmssns($dfltPrvldgs[32], $mdlNm);
                $canDelItm = test_prmssns($dfltPrvldgs[33], $mdlNm);
                if ($srcMenu == "Banking") {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                        <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                } else {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                }
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">VMS Administration</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">All Item List</span>
				</li>
                               </ul>
                              </div>";
                $total = get_VMSItemsTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_VMSItems($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-4";
                ?>
                <form id='allVmsItmsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allVmsItmsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllVmsItms(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                <input id="allVmsItmsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsItms('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsItms('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsItmsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Name/Description", "Category", "Type");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsItmsDsplySze" style="min-width:70px !important;">                            
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
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsItmsSortBy">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Value", "Last Created");
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
                                        <a class="rhopagination" href="javascript:getAllVmsItms('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllVmsItms('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>                   
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                        <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <?php if ($canAddItm === true) { ?>                   
                                <button type="button" class="btn btn-default btn-sm" onclick="getOneVmsItmsForm(-1, 'ShowDialog');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Vault Item
                                </button>
                            <?php } ?> 
                            <button type="button" class="btn btn-default btn-sm" onclick="">
                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                Export Vault Items
                            </button>                          
                            <?php if ($canAddItm) { ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="">
                                    <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Import Vault Items
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allVmsItmsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Item Code/Name</th>
                                        <th>Type / Category / Description</th>
                                        <th>Qty</th>
                                        <th>UOM</th>
                                        <th>CUR.</th>
                                        <th>Unit Value</th>
                                        <th>Total Value</th>
                                        <th>Enabled?</th>
                                        <th>&nbsp;</th>
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
                                        <tr id="allVmsItmsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <?php
                                                echo str_replace(" (" . $row[1] . ")", "", $row[1] . " (" . $row[2] . ")");
                                                ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allVmsItmsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $row[0]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[18] . " - " . $row[31] . " (" . $row[2] . ")"; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo number_format((float) $row[19], 2); ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;color:black;font-weight:bold;" onclick="getGnrlUOMBrkdwnForm(-1, 23, <?php echo $row[0]; ?>, <?php echo $row[19]; ?>, '<?php echo $row[33]; ?>');" data-toggle="tooltip" data-placement="bottom" title="View QTY Breakdown"><?php echo $row[23]; ?></button>
                                            </td>
                                            <td class="lovtd"><?php echo $row[33]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo number_format((float) $row[30], 2); ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php
                                                echo number_format((float) $row[19] * (float) $row[30], 2);
                                                ?>
                                            </td>                                            
                                            <td class="lovtd">
                                                <?php
                                                $isChkd = "";
                                                if ($row[13] == "1") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <div class="form-group form-group-sm">
                                                    <div class="form-check" style="font-size: 12px !important;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" id="allVmsItmsRow<?php echo $cntr; ?>_IsEnabled" name="allCstmrsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?> disabled="true">
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneVmsItmsForm(<?php echo $row[0]; ?>, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canDelItm === true) { ?>
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsItms('allVmsItmsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                <?php } ?>
                                            </td>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|inv.inv_itm_list|item_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
            } else if ($vwtyp == 401) {
                //New Vault Item Form
                $canEdtItm = test_prmssns($dfltPrvldgs[31], $mdlNm) || test_prmssns($dfltPrvldgs[32], $mdlNm);
                $sbmtdItmID = isset($_POST['sbmtdItmID']) ? cleanInputData($_POST['sbmtdItmID']) : -1;

                $invItemNm = "";
                $invItemDesc = "";

                $invTmpltNm = "";
                $invTmpltID = -1;
                $invItemType = "";
                $invItemCtgry = "";
                $invItemCtgryID = -1;
                $invBaseUom = "";
                $invBaseUomID = -1;

                $invTxCodeID = -1;
                $invTxCodeName = "";
                $invDscntCodeID = -1;
                $invDscntCodeName = "";
                $invChrgCodeID = -1;
                $invChrgCodeName = "";
                $isPlnngEnbld = "No";
                $autoLoadInVms = "Yes";
                $isEnbld = "No";
                $invMinItmQty = 0;
                $invMaxItmQty = 0;

                $invValCrncyID = -1;
                $invValCrncyNm = "";
                $invPriceLessTax = 0;
                $invSllngPrice = 0;

                $invNwPrftAmnt = 0;
                $invLtstCostPrice = 0;
                $invNewSllngPrice = 0;
                $invPrftMrgnPrcnt = 0;
                $invPrftMrgnAmnt = 0;

                $invAssetAcntID = -1;
                $invAssetAcntNm = "";
                $invCogsAcntID = -1;
                $invCogsAcntNm = "";
                $invSRvnuAcntID = -1;

                $invSRvnuAcntNm = "";
                $invSRetrnAcntID = -1;
                $invSRetrnAcntNm = "";
                $invPRetrnAcntID = -1;
                $invPRetrnAcntNm = "";
                $invExpnsAcntID = -1;
                $invExpnsAcntNm = "";
                $invItmExtrInfo = "";
                $invItmOthrDesc = "";

                $invItmGnrcNm = "";
                $invItmTradeNm = "";
                $invItmUslDsge = "";
                $invItmMaxDsge = "";
                $invItmCntrIndctns = "";
                $invItmFoodIntrctns = "";
                $nwFileName = "";
                $oldFileNm = "";
                $extension = "png";
                if ($sbmtdItmID > 0) {
                    $result = get_OneVMSItems($sbmtdItmID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdItmID = (float) $row[0];
                        $invItemNm = $row[1];
                        $invItemDesc = $row[2];

                        $invTmpltNm = $row[4];
                        $invTmpltID = (float) $row[3];
                        $invItemType = $row[5];
                        $invItemCtgry = $row[7];
                        $invItemCtgryID = (float) $row[6];
                        $invBaseUom = $row[9];
                        $invBaseUomID = (float) $row[8];

                        $invTxCodeID = (float) $row[10];
                        $invTxCodeName = $row[11];
                        $invDscntCodeID = (float) $row[12];
                        $invDscntCodeName = $row[13];
                        $invChrgCodeID = (float) $row[14];
                        $invChrgCodeName = $row[15];
                        $isPlnngEnbld = ($row[16] == "1") ? "Yes" : "No";
                        $autoLoadInVms = ($row[17] == "1") ? "Yes" : "No";
                        $isEnbld = ($row[18] == "1") ? "Yes" : "No";
                        $invMinItmQty = (float) $row[19];
                        $invMaxItmQty = (float) $row[20];

                        $invValCrncyID = (float) $row[21];
                        $invValCrncyNm = $row[22];
                        $invPriceLessTax = (float) $row[23];
                        $invSllngPrice = (float) $row[24];

                        $invNwPrftAmnt = 0;
                        $invLtstCostPrice = getHgstUnitCostPrice($sbmtdItmID);
                        $invNewSllngPrice = 0;
                        $invPrftMrgnPrcnt = 0;
                        $invPrftMrgnAmnt = 0;

                        $invAssetAcntID = (float) $row[25];
                        $invAssetAcntNm = $row[26];
                        $invCogsAcntID = (float) $row[27];
                        $invCogsAcntNm = $row[28];
                        $invSRvnuAcntID = (float) $row[29];

                        $invSRvnuAcntNm = $row[30];
                        $invSRetrnAcntID = (float) $row[31];
                        $invSRetrnAcntNm = $row[32];
                        $invPRetrnAcntID = (float) $row[33];
                        $invPRetrnAcntNm = $row[34];
                        $invExpnsAcntID = (float) $row[35];
                        $invExpnsAcntNm = $row[36];
                        $invItmExtrInfo = $row[37];
                        $invItmOthrDesc = $row[38];

                        $invItmGnrcNm = $row[39];
                        $invItmTradeNm = $row[40];
                        $invItmUslDsge = $row[41];
                        $invItmMaxDsge = $row[42];
                        $invItmCntrIndctns = $row[43];
                        $invItmFoodIntrctns = $row[44];

                        $temp = explode(".", $row[46]);
                        $oldFileNm = $temp[0];
                        $extension = end($temp);
                        $nwFileName = encrypt1($row[46], $smplTokenWord1) . "." . $extension;
                    }
                }
                $ftp_src = $ftp_base_db_fldr . "/Inv/" . $sbmtdItmID . "." . $extension;
                $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                if (file_exists($ftp_src) && !is_dir($ftp_src)) {
                    copy("$ftp_src", "$fullPemDest");
                } else if (!file_exists($fullPemDest)) {
                    $ftp_src = $fldrPrfx . 'cmn_images/actions_document_preview.png';
                    copy("$ftp_src", "$fullPemDest");
                }
                $nwFileName = $tmpDest . $nwFileName;
                ?>
                <form class="form-horizontal" id='vaultItmStpForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0px 15px !important;">
                            <div class="col-md-6" style="padding: 0px 5px 0px 5px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="invItemNm" class="control-label">Item Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdtItm === true) { ?> 
                                                <input type="text" name="invItemNm" id="invItemNm" class="form-control rqrdFld" value="<?php echo $invItemNm; ?>" style="width:100% !important;">
                                                <input type="hidden" name="sbmtdItmID" id="sbmtdItmID" class="form-control" value="<?php echo $sbmtdItmID; ?>">
                                            <?php } else { ?>
                                                <span><?php echo $invItemNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="invItemDesc" class="control-label">Item Description:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                            <?php if ($canEdtItm === true) { ?>
                                                <textarea rows="5" name="invItemDesc" id="invItemDesc" class="form-control rqrdFld"><?php echo $invItemDesc; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $invItemDesc; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <div class="col-md-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            &nbsp;
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                <label for="isItmEnbld" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdtItm === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($isEnbld == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isItmEnbld" id="isItmEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">                                           
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-md-6" style="padding: 1px !important;">                                
                                <div class="col-md-8">
                                    <fieldset class="basic_person_fs1" style="min-height: 50px !important;"><!--<legend class="basic_person_lg">Item's Picture</legend>-->
                                        <div style="margin-bottom: 5px;margin-top:5px !important;">
                                            <img src="<?php echo $nwFileName; ?>" alt="..." id="img1ItmTest" class="img-rounded center-block img-responsive" style="height: 150px !important; width: auto !important;">                                            
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <?php if ($canEdtItm === true) { ?>
                                        <div class="form-group form-group-sm" style="margin-bottom: 10px;margin-top:10px !important;">
                                            <div class="col-md-12" style="padding:1px !important;">                                               
                                                <label class="btn btn-primary btn-file" style="width:100% !important;min-width: 100% !important;">
                                                    Browse... <input type="file" id="daItemPicture" name="daItemPicture" onchange="changeImgSrc(this, '#img1ItmTest', '#img1ItmSrcLoc');" class="btn btn-default"  style="display: none;">
                                                </label>                                                                
                                            </div>
                                            <div class="col-md-12" style="padding:1px !important;">
                                                <input type="text" class="form-control" aria-label="..." id="img1ItmSrcLoc" value="">    
                                            </div>
                                            <div class="col-md-12" style="padding:1px !important;">
                                                <button type="button" class="btn btn-default" data-dismiss="modal" style="width:100% !important;">Close</button>
                                                <?php if ($canEdtItm === true) { ?>
                                                    <button type="button" class="btn btn-success" onclick="saveVmsItmsForm();" style="width:100% !important;">Save Changes</button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:3px 0px 3px 0px;"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                    <li class="active"><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsGnrl" id="invItmsGnrltab" style="padding: 3px 10px !important;">General</a></li>
                                    <li><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsGl" id="invItmsGltab" style="padding: 3px 10px !important;">GL Accounts</a></li>
                                    <li><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsStores" id="invItmsStorestab" style="padding: 3px 10px !important;display:none;">Stores/WH</a></li>
                                    <li><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsExtInfo" id="invItmsExtInfotab" style="padding: 3px 10px !important;display:none;">Extra Info.</a></li>
                                    <li><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsUOM" id="invItmsUOMtab" style="padding: 3px 10px !important;">UOMs</a></li>
                                    <li><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsDrugLbl" id="invItmsDrugLbltab" style="padding: 3px 10px !important;display:none;">Drug Labels</a></li>
                                    <li><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsDrugIntrctns" id="invItmsDrugIntrctnstab" style="padding: 3px 10px !important;display:none;">Drug Interactions</a></li>
                                </ul>
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneInvItmDetTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="invItmsGnrl" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invTmpltNm" class="control-label">Apply Template:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <div class="input-group">
                                                                        <input type="text" name="invTmpltNm" id="invTmpltNm" class="form-control" value="<?php echo $invTmpltNm; ?>" readonly="true">
                                                                        <input type="hidden" name="invTmpltID" id="invTmpltID" class="form-control" value="<?php echo $invTmpltID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Item Templates', '', '', '', 'radio', true, '', 'invTmpltID', 'invTmpltNm', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invTmpltNm; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invItemType" class="control-label">Item Type:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>
                                                                    <select class="form-control" id="invItemType" onchange="">                                                        
                                                                        <?php
                                                                        $valslctdArry = array("", "", "", "", "", "", "");
                                                                        $valuesArrys = array("Merchandise Inventory", "Non-Merchandise Inventory",
                                                                            "Fixed Assets", "Expense Item", "Services", "VaultItem-Cash", "VaultItem-NonCash");
                                                                        for ($z = 0; $z < count($valuesArrys); $z++) {
                                                                            if ($invItemType == $valuesArrys[$z]) {
                                                                                $valslctdArry[$z] = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invItemType; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invItemCtgry" class="control-label">Category:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="invItemCtgry" value="<?php echo $invItemCtgry; ?>" readonly="">
                                                                        <input type="hidden" id="invItemCtgryID" value="<?php echo $invItemCtgryID; ?>">
                                                                        <label id="invItemCtgryLbl" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Categories', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invItemCtgryID', 'invItemCtgry', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invItemCtgry; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invBaseUom" class="control-label">Base UOM:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <div class="input-group">
                                                                        <input type="text" name="invBaseUom" id="invBaseUom" class="form-control" value="<?php echo $invBaseUom; ?>" readonly="true">
                                                                        <input type="hidden" name="invBaseUomID" id="invBaseUomID" class="form-control" value="<?php echo $invBaseUomID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Unit Of Measures', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invBaseUomID', 'invBaseUom', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div> 
                                                                <?php } else { ?>
                                                                    <span><?php echo $invBaseUom; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invTxCodeName" class="control-label">Tax Code:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" name="invTxCodeName" id="invTxCodeName" class="form-control rqrdFld" value="<?php echo $invTxCodeName; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" name="invTxCodeID" id="invTxCodeID" class="form-control" value="<?php echo $invTxCodeID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', '', '', '', 'radio', true, '', 'invTxCodeID', 'invTxCodeName', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invTxCodeName; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invDscntCodeName" class="control-label">Discount Code:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" name="invDscntCodeName" id="invDscntCodeName" class="form-control rqrdFld" value="<?php echo $invDscntCodeName; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" name="invDscntCodeID" id="invDscntCodeID" class="form-control" value="<?php echo $invDscntCodeID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', '', '', '', 'radio', true, '', 'invDscntCodeID', 'invDscntCodeName', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invDscntCodeName; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invChrgCodeName" class="control-label">Charge Code:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" name="invChrgCodeName" id="invChrgCodeName" class="form-control rqrdFld" value="<?php echo $invChrgCodeName; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" name="invChrgCodeID" id="invChrgCodeID" class="form-control" value="<?php echo $invChrgCodeID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Charges', '', '', '', 'radio', true, '', 'invChrgCodeID', 'invChrgCodeName', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invChrgCodeName; ?></span>
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
                                                                    <label for="isPlnngEnbld" class="control-label">
                                                                        <?php
                                                                        $isChkd = "";
                                                                        $isRdOnly = "disabled=\"true\"";
                                                                        if ($canEdtItm === true) {
                                                                            $isRdOnly = "";
                                                                        }
                                                                        if ($isPlnngEnbld == "Yes") {
                                                                            $isChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" name="isPlnngEnbld" id="isPlnngEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Is Planning Enabled?</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                                    <label for="autoLoadInVms" class="control-label">
                                                                        <?php
                                                                        $isChkd = "";
                                                                        $isRdOnly = "disabled=\"true\"";
                                                                        if ($canEdtItm === true) {
                                                                            $isRdOnly = "";
                                                                        }
                                                                        if ($autoLoadInVms == "Yes") {
                                                                            $isChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" name="autoLoadInVms" id="autoLoadInVms" <?php echo $isChkd . " " . $isRdOnly; ?>>Auto-Load in VMS?</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invMinItmQty" class="control-label">Planning Qtys:</label>
                                                            </div>
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <input type="number" name="invMinItmQty" id="invMinItmQty" class="form-control" value="<?php echo $invMinItmQty; ?>" placeholder="Min Qty.">                                                                        
                                                                <?php } else { ?>
                                                                    <span><?php echo $invMinItmQty; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <input type="number" name="invMaxItmQty" id="invMaxItmQty" class="form-control" value="<?php echo $invMaxItmQty; ?>" placeholder="Max Qty.">                                                                        
                                                                <?php } else { ?>
                                                                    <span><?php echo $invMaxItmQty; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invValCrncyNm" class="control-label">Value Currency:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" name="invValCrncyNm" id="invValCrncyNm" class="form-control rqrdFld" value="<?php echo $invValCrncyNm; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" name="invValCrncyID" id="invValCrncyID" class="form-control" value="<?php echo $invValCrncyID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'invValCrncyID', 'invValCrncyNm', 'clear', 0, '', function () {
                                                                                    var aa112 = 1;
                                                                                });"> 
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invValCrncyNm; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invPriceLessTax" class="control-label">Price Less Tax & Charges:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <input type="number" name="invPriceLessTax" id="invPriceLessTax" class="form-control" value="<?php echo $invPriceLessTax; ?>" placeholder="0.00">                                                                        
                                                                <?php } else { ?>
                                                                    <span><?php echo $invPriceLessTax; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invSllngPrice" class="control-label">Selling Price:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <input type="number" name="invSllngPrice" id="invSllngPrice" class="form-control" value="<?php echo $invSllngPrice; ?>" placeholder="0.00">                                                                        
                                                                <?php } else { ?>
                                                                    <span><?php echo $invSllngPrice; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invLtstCostPrice" class="control-label">Latest Cost Price:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <input type="number" name="invLtstCostPrice" id="invLtstCostPrice" class="form-control" value="<?php echo $invLtstCostPrice; ?>" placeholder="0.00">                                                                        
                                                                <?php } else { ?>
                                                                    <span><?php echo $invLtstCostPrice; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invNwPrftAmnt" class="control-label">New Profit Amount:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <input type="number" name="invNwPrftAmnt" id="invNwPrftAmnt" class="form-control" value="<?php echo $invNwPrftAmnt; ?>" placeholder="0.00">                                                                        
                                                                <?php } else { ?>
                                                                    <span><?php echo $invNwPrftAmnt; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invNewSllngPrice" class="control-label">New Selling Price:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <input type="number" name="invNewSllngPrice" id="invNewSllngPrice" class="form-control" value="<?php echo $invNewSllngPrice; ?>" placeholder="0.00">                                                                        
                                                                <?php } else { ?>
                                                                    <span><?php echo $invNewSllngPrice; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invPrftMrgnPrcnt" class="control-label">Profit Margin(%):</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <input type="number" name="invPrftMrgnPrcnt" id="invPrftMrgnPrcnt" class="form-control" value="<?php echo $invPrftMrgnPrcnt; ?>" placeholder="0.00">                                                                        
                                                                <?php } else { ?>
                                                                    <span><?php echo $invPrftMrgnPrcnt; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invPrftMrgnAmnt" class="control-label">Profit Margin:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <input type="number" name="invPrftMrgnAmnt" id="invPrftMrgnAmnt" class="form-control" value="<?php echo $invPrftMrgnAmnt; ?>" placeholder="0.00">                                                                        
                                                                <?php } else { ?>
                                                                    <span><?php echo $invPrftMrgnAmnt; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="" class="control-label">&nbsp</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdtItm === true) { ?>                                   
                                                                    <button type="button" class="btn btn-primary" onclick="saveVmsVltsForm();">Overwrite Selling Price</button>                                                                        
                                                                <?php } else { ?>
                                                                    <span>&nbsp</span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>   
                                        <div id="invItmsGl" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invAssetAcntNm" class="control-label">Inventory/Asset Account:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdtItm === true) { ?>                                   
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invAssetAcntNm" id="invAssetAcntNm" class="form-control rqrdFld" value="<?php echo $invAssetAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invAssetAcntID" id="invAssetAcntID" class="form-control" value="<?php echo $invAssetAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invAssetAcntID', 'invAssetAcntNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });"> 
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invAssetAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invCogsAcntNm" class="control-label">Cost of Goods Sold:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdtItm === true) { ?>                                   
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invCogsAcntNm" id="invCogsAcntNm" class="form-control" value="<?php echo $invCogsAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invCogsAcntID" id="invCogsAcntID" class="form-control" value="<?php echo $invCogsAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Contra Revenue Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invCogsAcntID', 'invCogsAcntNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });"> 
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invCogsAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invSRvnuAcntNm" class="control-label">Sales Revenue:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdtItm === true) { ?>                                   
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invSRvnuAcntNm" id="invSRvnuAcntNm" class="form-control rqrdFld" value="<?php echo $invSRvnuAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invSRvnuAcntID" id="invSRvnuAcntID" class="form-control" value="<?php echo $invSRvnuAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Revenue Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invSRvnuAcntID', 'invSRvnuAcntNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });"> 
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invSRvnuAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invSRetrnAcntNm" class="control-label">Sales Return:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdtItm === true) { ?>                                   
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invSRetrnAcntNm" id="invSRetrnAcntNm" class="form-control" value="<?php echo $invSRetrnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invSRetrnAcntID" id="invSRetrnAcntID" class="form-control" value="<?php echo $invSRetrnAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Contra Revenue Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invSRetrnAcntID', 'invSRetrnAcntNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });"> 
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invSRetrnAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invPRetrnAcntNm" class="control-label">Purchase Returns Account:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdtItm === true) { ?>                                   
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invPRetrnAcntNm" id="invPRetrnAcntNm" class="form-control" value="<?php echo $invPRetrnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invPRetrnAcntID" id="invPRetrnAcntID" class="form-control" value="<?php echo $invPRetrnAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Contra Expense Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invPRetrnAcntID', 'invPRetrnAcntNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });"> 
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invPRetrnAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invExpnsAcntNm" class="control-label">Expense Account:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdtItm === true) { ?>                                   
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invExpnsAcntNm" id="invExpnsAcntNm" class="form-control rqrdFld" value="<?php echo $invExpnsAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invExpnsAcntID" id="invExpnsAcntID" class="form-control" value="<?php echo $invExpnsAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Expense Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invExpnsAcntID', 'invExpnsAcntNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });"> 
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invExpnsAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="invItmsStores" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;display:none;"> 
                                            <?php
                                            $nwRowHtml = urlencode("<tr id=\"oneStoreUsersRow__WWW123WWW\">"
                                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                                    . "<td class=\"lovtd\">
                                                        <div class=\"\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_StoreNm\" value=\"\" style=\"width:100%;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_StoreID\" value=\"-1\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_StockID\" value=\"-1\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Stores', '', '', '', 'radio', true, '', 'oneItmStoresRow_WWW123WWW_StoreID', 'oneItmStoresRow_WWW123WWW_StoreNm', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <div class=\"\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_ShlvNm\" value=\"\" style=\"width:100%;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_ShlvIDs\" value=\"\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Store Shelves', 'oneItmStoresRow_WWW123WWW_StoreID', '', '', 'check', true, '', 'oneItmStoresRow_WWW123WWW_ShlvIDs', 'oneItmStoresRow_WWW123WWW_ShlvNm', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\"> 
                                                        <div class=\"\" style=\"width:100% !important;\">
                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\">
                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneItmStoresRow_WWW123WWW_StrtDte\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>                                                                
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <div class=\"\">
                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneItmStoresRow_WWW123WWW_EndDte\" value=\"\" readonly=\"true\" style=\"width:100%;\">
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>                                                                
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delVmsItmStores('oneItmStoresRow_WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Item Store\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                        </button>
                                                    </td>");
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php if ($canEdtItm === true) { ?>
                                                        <button id="addNwVaultBtn1" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('oneItmStoresTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Item Store">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add Store
                                                        </button>
                                                    <?php } ?>
                                                    <button id="refreshVltBtn1" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsItmsForm(<?php echo $sbmtdItmID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Item Details">
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
                                                                <th >Store Name</th>
                                                                <th >Shelves</th>
                                                                <th >Start Date</th>
                                                                <th >End Date</th>
                                                                <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                <?php if ($canVwRcHstry === true) { ?>
                                                                    <th>...</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $rslt = get_OneVMSItemStores($sbmtdItmID);
                                                            $cntrUsr = 0;
                                                            while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                $cntrUsr++;
                                                                $storeName = $rwCage[1];
                                                                $shelves = $rwCage[2];
                                                                $storeStrtDte = $rwCage[3];
                                                                $storeEndDte = $rwCage[4];
                                                                ?>
                                                                <tr id="oneItmStoresRow_<?php echo $cntrUsr; ?>">                                    
                                                                    <td class="lovtd"><span><?php echo ($rwCage[0]); ?></span></td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdtItm === true) { ?>
                                                                            <div class="">
                                                                                <div class="input-group"  style="width:100%;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_StoreNm" value="<?php echo $storeName; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_StoreID" value="<?php echo $rwCage[5]; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_StockID" value="<?php echo $rwCage[7]; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Stores', '', '', '', 'radio', true, '<?php echo $storeName; ?>', 'oneItmStoresRow<?php echo $cntrUsr; ?>_StoreID', 'oneItmStoresRow<?php echo $cntrUsr; ?>_StoreNm', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $storeName; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdtItm === true) { ?>
                                                                            <div class="">
                                                                                <div class="input-group"  style="width:100%;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_ShlvNm" value="<?php echo $shelves; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_ShlvIDs" value="<?php echo $rwCage[6]; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Store Shelves', 'oneItmStoresRow<?php echo $cntrUsr; ?>_StoreID', '', '', 'check', true, '<?php echo $shelves; ?>', 'oneItmStoresRow<?php echo $cntrUsr; ?>_ShlvIDs', 'oneItmStoresRow<?php echo $cntrUsr; ?>_ShlvNm', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $shelves; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">                                                                            
                                                                        <?php if ($canEdtItm === true) { ?>
                                                                            <div class="">
                                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                    <input class="form-control" size="16" type="text" id="oneItmStoresRow<?php echo $cntrUsr; ?>_StrtDte" value="<?php echo $storeStrtDte; ?>" readonly="true">
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>                                                                
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $storeStrtDte; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdtItm === true) { ?>
                                                                            <div class="">
                                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                    <input class="form-control" size="16" type="text" id="oneItmStoresRow<?php echo $cntrUsr; ?>_EndDte" value="<?php echo $storeEndDte; ?>" readonly="true">
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>                                                                
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $storeEndDte; ?></span>
                                                                        <?php } ?>  
                                                                    </td>
                                                                    <?php if ($canEdtItm === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsItmStores('oneItmStoresRow<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item Store">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($canVwRcHstry === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($rwCage[7] . "|inv.inv_stock|stock_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                                        </div>
                                        <div id="invItmsExtInfo" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;display:none;">                                             
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmExtrInfo" class="control-label">Extra Information:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                                        <?php if ($canEdtItm === true) { ?>
                                                            <textarea rows="4" name="invItmExtrInfo" id="invItmExtrInfo" class="form-control"><?php echo $invItmExtrInfo; ?></textarea>
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmExtrInfo; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmOthrDesc" class="control-label">Other Description:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                                        <?php if ($canEdtItm === true) { ?>
                                                            <textarea rows="6" name="invItmOthrDesc" id="invItmOthrDesc" class="form-control"><?php echo $invItmOthrDesc; ?></textarea>
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmOthrDesc; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="invItmsUOM" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">                                             
                                            <?php
                                            $nwRowHtml = urlencode("<tr id=\"oneItmUOMsRow__WWW123WWW\">"
                                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                                    . "<td class=\"lovtd\">
                                                            <div class=\"\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_UOMNm\" value=\"\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_UOMID\" value=\"\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_LineID\" value=\"\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Unit Of Measures', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneItmUOMsRow_WWW123WWW_UOMID', 'oneItmUOMsRow_WWW123WWW_UOMNm', 'clear', 1, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                            <div class=\"\">                                                                                
                                                                <input type=\"number\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_CnvrsnFctr\" value=\"\">
                                                            </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                            <div class=\"\">                                                                                
                                                                <input type=\"number\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_SortOrdr\" value=\"\">
                                                            </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                            <div class=\"\">                                                                                
                                                                <input type=\"number\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_SllgnPrce\" value=\"\">
                                                            </div>  
                                                    </td>
                                                     <td class=\"lovtd\">
                                                            <div class=\"\">                                                                                
                                                                <input type=\"number\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_PriceLsTx\" value=\"\">
                                                            </div>   
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delVmsItmUoMs('oneItmUOMsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Item UOM\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                        </button>
                                                    </td>");
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php if ($canEdtItm === true) { ?>
                                                        <button id="addNwVaultBtn2" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('oneItmUOMsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Item UOM">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add UOM
                                                        </button>
                                                    <?php } ?>
                                                    <button id="refreshVltBtn2" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsItmsForm(<?php echo $sbmtdItmID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Item Details">
                                                        <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Refresh
                                                    </button>
                                                </div>                                            
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneItmUOMsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:50px;width:50px;">No.</th>
                                                                <th >UOM</th>
                                                                <th >Conversion Factor</th>
                                                                <th >Sort Order</th>
                                                                <th >Selling Price</th>
                                                                <th >Price Less Tax</th>
                                                                <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                <?php if ($canVwRcHstry === true) { ?>
                                                                    <th>...</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $rslt = get_OneVMSItemUOMs($sbmtdItmID);
                                                            $cntrUsr = 0;
                                                            while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                $cntrUsr++;
                                                                $itmUOM1 = $rwCage[1];
                                                                $itmUOMLineID = $rwCage[4];
                                                                $itmUOMID = $rwCage[5];
                                                                $uomCnvrsnFctr = $rwCage[2];
                                                                $uomSortOrdr = (float) $rwCage[3];
                                                                $uomSllngPrice = (float) $rwCage[6];
                                                                $uomPriceLsTx = (float) $rwCage[7];
                                                                ?>
                                                                <tr id="oneItmUOMsRow_<?php echo $cntrUsr; ?>">                                    
                                                                    <td class="lovtd"><span><?php echo ($rwCage[0]); ?></span></td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdtItm === true) { ?>
                                                                            <div class="">
                                                                                <div class="input-group"  style="width:100%;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_UOMNm" value="<?php echo $itmUOM1; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_UOMID" value="<?php echo $itmUOMID; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $itmUOMLineID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Unit Of Measures', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $itmUOMID; ?>', 'oneItmUOMsRow<?php echo $cntrUsr; ?>_UOMID', 'oneItmUOMsRow<?php echo $cntrUsr; ?>_UOMNm', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $itmUOM1; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdtItm === true) { ?>
                                                                            <div class="">                                                                                
                                                                                <input type="number" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_CnvrsnFctr" value="<?php echo $uomCnvrsnFctr; ?>">
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $uomCnvrsnFctr; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">                                                                            
                                                                        <?php if ($canEdtItm === true) { ?>
                                                                            <div class="">                                                                                
                                                                                <input type="number" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_SortOrdr" value="<?php echo $uomSortOrdr; ?>">
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $uomSortOrdr; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">                                                                            
                                                                        <?php if ($canEdtItm === true) { ?>
                                                                            <div class="">                                                                                
                                                                                <input type="number" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_SllgnPrce" value="<?php echo $uomSllngPrice; ?>">
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $uomSllngPrice; ?></span>
                                                                        <?php } ?>   
                                                                    </td>
                                                                    <td class="lovtd">                                                                            
                                                                        <?php if ($canEdtItm === true) { ?>
                                                                            <div class="">                                                                                
                                                                                <input type="number" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_PriceLsTx" value="<?php echo $uomPriceLsTx; ?>">
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $uomPriceLsTx; ?></span>
                                                                        <?php } ?>   
                                                                    </td>
                                                                    <?php if ($canEdtItm === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsItmUoMs('oneItmUOMsRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item UOM">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($canVwRcHstry === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($rwCage[4] . "|inv.itm_uoms|itm_uom_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                                        </div>
                                        <div id="invItmsDrugLbl" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;display:none;">                                                                                            
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmGnrcNm" class="control-label">Generic Name:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdtItm === true) { ?>                                   
                                                            <input type="text" name="invItmGnrcNm" id="invItmGnrcNm" class="form-control" value="<?php echo $invItmGnrcNm; ?>">                                                                        
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmGnrcNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>                                                                                            
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmTradeNm" class="control-label">Trade Name:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdtItm === true) { ?>                                   
                                                            <input type="text" name="invItmTradeNm" id="invItmTradeNm" class="form-control" value="<?php echo $invItmTradeNm; ?>">                                                                        
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmTradeNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmUslDsge" class="control-label">Usual Dosage:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                                        <?php if ($canEdtItm === true) { ?>
                                                            <textarea rows="3" name="invItmUslDsge" id="invItmUslDsge" class="form-control"><?php echo $invItmUslDsge; ?></textarea>
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmUslDsge; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmMaxDsge" class="control-label">Maximum Dosage:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                                        <?php if ($canEdtItm === true) { ?>
                                                            <textarea rows="3" name="invItmMaxDsge" id="invItmMaxDsge" class="form-control"><?php echo $invItmMaxDsge; ?></textarea>
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmMaxDsge; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmCntrIndctns" class="control-label">Contraindications:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                                        <?php if ($canEdtItm === true) { ?>
                                                            <textarea rows="3" name="invItmCntrIndctns" id="invItmCntrIndctns" class="form-control"><?php echo $invItmCntrIndctns; ?></textarea>
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmCntrIndctns; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmFoodIntrctns" class="control-label">Food Interactions:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                                                        <?php if ($canEdtItm === true) { ?>
                                                            <textarea rows="3" name="invItmFoodIntrctns" id="invItmFoodIntrctns" class="form-control"><?php echo $invItmFoodIntrctns; ?></textarea>
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmFoodIntrctns; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="invItmsDrugIntrctns" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;display:none;">                                                                                         
                                            <?php
                                            $nwRowHtml = "<tr id=\"oneItmDrugIntrctnsRow__WWW123WWW\">"
                                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                                    . "<td class=\"lovtd\">
                                                                            <div class=\"\">
                                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneItmDrugIntrctnsRow_WWW123WWW_DrugNm\" value=\"\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmDrugIntrctnsRow_WWW123WWW_DrugID\" value=\"-1\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmDrugIntrctnsRow_WWW123WWW_LineID\" value=\"\">
                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', '', '', '', 'radio', true, '', 'oneItmDrugIntrctnsRow_WWW123WWW_DrugID', 'oneItmDrugIntrctnsRow_WWW123WWW_DrugNm', 'clear', 1, '');\">
                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                            <div class=\"\">
                                                                                <textarea class=\"form-control\" aria-label=\"...\" id=\"oneItmDrugIntrctnsRow_WWW123WWW_Intrctn\" rows=\"3\"></textarea>                                                                                    
                                                                            </div>
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                            <div class=\"\">
                                                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneItmDrugIntrctnsRow_WWW123WWW_Action\">";

                                            $valslctdArry = array("", "", "", "");
                                            $srchInsArrys = array("Disallow if combination found", "Warn if combination found", "Disallow if combination not found", "Warn if combination not found");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                $nwRowHtml .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                            }
                                            $nwRowHtml .= "</select>                                                                
                                                                            </div>
                                                                    </td>
                                                                        <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delVmsVltDrgIntrctns('oneItmDrugIntrctnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Drug Interaction\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>";
                                            $nwRowHtml = urlencode($nwRowHtml);
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php if ($canEdtItm === true) { ?>
                                                        <button id="addNwVaultBtn3" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('oneItmDrugIntrctnsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Drug Interaction">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add Drug Interaction
                                                        </button>
                                                    <?php } ?>
                                                    <button id="refreshVltBtn3" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsItmsForm(<?php echo $sbmtdItmID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Item Details">
                                                        <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Refresh
                                                    </button>
                                                </div>                                            
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneItmDrugIntrctnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:50px;width:50px;">No.</th>
                                                                <th >Drug</th>
                                                                <th >Interaction Effect</th>
                                                                <th >Action</th>
                                                                <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                <?php if ($canVwRcHstry === true) { ?>
                                                                    <th>...</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $rslt = get_OneVMSItemDrgIntrctns($sbmtdItmID);
                                                            $cntrUsr = 0;
                                                            while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                $cntrUsr++;
                                                                $drugName = $rwCage[1];
                                                                $intrctnEffct = $rwCage[2];
                                                                $actionDrug = $rwCage[3];
                                                                $scndDrugID = $rwCage[4];
                                                                $drugIntrctnID = $rwCage[5];
                                                                ?>
                                                                <tr id="oneItmDrugIntrctnsRow_<?php echo $cntrUsr; ?>">                                    
                                                                    <td class="lovtd"><span><?php echo ($rwCage[0]); ?></span></td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdtItm === true) { ?>
                                                                            <div class="">
                                                                                <div class="input-group"  style="width:100%;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_DrugNm" value="<?php echo $drugName; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_DrugID" value="<?php echo $scndDrugID; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $drugIntrctnID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', '', '', '', 'radio', true, '<?php echo $scndDrugID; ?>', 'oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_DrugID', 'oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_DrugNm', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $drugName; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdtItm === true) { ?>
                                                                            <div class="">
                                                                                <textarea class="form-control" aria-label="..." id="oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_Intrctn" rows="3"><?php echo $intrctnEffct; ?></textarea>                                                                                    
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $intrctnEffct; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <td class="lovtd">                                                                            
                                                                        <?php if ($canEdtItm === true) { ?>
                                                                            <div class="">
                                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_Action">
                                                                                    <?php
                                                                                    $valslctdArry = array("", "", "", "");
                                                                                    $srchInsArrys = array("Disallow if combination found", "Warn if combination found", "Disallow if combination not found", "Warn if combination not found");
                                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                        if ($actionDrug == $srchInsArrys[$z]) {
                                                                                            $valslctdArry[$z] = "selected";
                                                                                        }
                                                                                        ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                    <?php } ?>
                                                                                </select>                                                                
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $actionDrug; ?></span>
                                                                        <?php } ?> 
                                                                    </td>
                                                                    <?php if ($canEdtItm === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsVltDrgIntrctns('oneItmDrugIntrctnsRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Drug Interaction">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($canVwRcHstry === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($rwCage[5] . "|inv.inv_drug_interactions|drug_intrctn_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdtItm === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveVmsItmsForm();">Save Changes</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            } else if ($vwtyp == 5) {
                //Authorizers and Limits
                if ($srcMenu == "Banking") {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                        <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                } else {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                }
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">VMS Administration</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Authorizers & their Limits</span>
				</li>
                               </ul>
                              </div>";
                $total = get_ApprvrLmtsTtl($orgID, $srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_ApprvrLmts($orgID, $srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType3 = "col-lg-4";
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?>
                <form id='vmsAthrzrsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddAthrzr || $canEdtAthrzr) {
                            $nwRowHtml = "<tr id=\"vmsAthrzrsRow__WWW123WWW\" role=\"row\">
                                            <td class=\"lovtd\">
                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                    <span>New</span>
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"vmsAthrzrsRow_WWW123WWW_LimitID\" value=\"-1\">
                                                </div>
                                            </td>    
                                            <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delVmsAthrzr('vmsAthrzrsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Authorizer\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button>
                                            </td>                                              
                                            <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                        <div class=\"input-group\" style=\"width:100%;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"vmsAthrzrsRow_WWW123WWW_AthrzrNm\" value=\"\" readonly=\"true\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"vmsAthrzrsRow_WWW123WWW_LocID\" value=\"\">
                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '', 'vmsAthrzrsRow_WWW123WWW_LocID', 'vmsAthrzrsRow_WWW123WWW_AthrzrNm', 'clear', 0, '');\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                            </label>
                                                        </div>
                                                    </div>                                                                                                         
                                            </td>
                                            <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                        <div class=\"input-group\" style=\"width:100%;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"vmsAthrzrsRow_WWW123WWW_SiteNm\" value=\"\" readonly=\"true\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"vmsAthrzrsRow_WWW123WWW_SiteID\" value=\"-1\">
                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', '', '', '', 'radio', true, '', 'vmsAthrzrsRow_WWW123WWW_SiteID', 'vmsAthrzrsRow_WWW123WWW_SiteNm', 'clear', 0, '');\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                            </label>
                                                        </div>
                                                    </div>                                                                                                         
                                            </td>
                                            <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                        <div class=\"input-group\" style=\"width:100%;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"vmsAthrzrsRow_WWW123WWW_TrnsTyp\" value=\"\" readonly=\"true\">
                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Transaction Types', '', '', '', 'radio', true, '', 'vmsAthrzrsRow_WWW123WWW_TrnsTyp', '', 'clear', 0, '');\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                            </label>
                                                        </div>
                                                    </div>                                                                                                         
                                            </td>
                                            <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                        <div class=\"input-group\" style=\"width:100%;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"vmsAthrzrsRow_WWW123WWW_CrncyNm\" value=\"\" readonly=\"true\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"vmsAthrzrsRow_WWW123WWW_CrncyID\" value=\"\">
                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'vmsAthrzrsRow_WWW123WWW_CrncyNm', '', 'clear', 0, '');\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                            </label>
                                                        </div>
                                                    </div>                                                                                                         
                                            </td>
                                            <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                        <input type=\"number\" min=\"0\" max=\"9999999999999999999\" class=\"form-control\" aria-label=\"...\" id=\"vmsAthrzrsRow_WWW123WWW_MinAmnt\" name=\"vmsAthrzrsRow_WWW123WWW_MinAmnt\" value=\"0\" style=\"width:100%;\">
                                                    </div>                                                                                                         
                                            </td>
                                            <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                        <input type=\"number\" min=\"0\" max=\"9999999999999999999\" class=\"form-control\" aria-label=\"...\" id=\"vmsAthrzrsRow_WWW123WWW_MaxAmnt\" name=\"vmsAthrzrsRow_WWW123WWW_MaxAmnt\" value=\"0\" style=\"width:100%;\">
                                                    </div>                                                                                                         
                                            </td>
                                            <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\">
                                                        <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                            <label class=\"form-check-label\">
                                                                <input type=\"checkbox\" class=\"form-check-input\" id=\"vmsAthrzrsRow_WWW123WWW_IsEnbld\" name=\"vmsAthrzrsRow_WWW123WWW_IsEnbld\">
                                                            </label>
                                                        </div>
                                                    </div>                                                                                                         
                                            </td>";
                            if ($canVwRcHstry === true) {
                                $nwRowHtml .= "<td class=\"lovtd\">&nbsp;</td>";
                            }
                            $nwRowHtml .= "</tr>";
                            $nwRowHtml = urlencode($nwRowHtml);
                            ?> 
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <?php if ($canAddAthrzr) { ?>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('vmsAthrzrsTable', 0, '<?php echo $nwRowHtml; ?>');">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Authorizer
                                        </button>
                                    <?php } ?>
                                    <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 5px;" onclick="saveVmsAthrzr('#allmodules', 'grp=25&typ=1&pg=4&vtyp=5');">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        SAVE
                                    </button>
                                </div>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-3";
                            $colClassType2 = "col-lg-5";
                            $colClassType3 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allVmsAthrzrsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllVmsAthrzrs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                <input id="allVmsAthrzrsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsAthrzrs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsAthrzrs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsAthrzrsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "");
                                    $srchInsArrys = array("Authorizer Name", "Site", "Transaction Type", "Currency");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsAthrzrsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllVmsAthrzrs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllVmsAthrzrs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>                    
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="vmsAthrzrsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                                <thead>
                                    <tr>
                                        <th style="max-width: 35px !important;width: 30px !important;">No.</th> 
                                        <th style="max-width: 35px !important;width: 30px !important;">&nbsp;</th>
                                        <th>Authorizer</th>
                                        <th>Branch/Location</th>
                                        <th>Transaction Type</th>
                                        <th style="max-width: 85px !important;width: 80px !important;">Currency</th>
                                        <th>Min Amount</th>
                                        <th>Max Amount</th>
                                        <th style="max-width: 75px !important;width: 75px !important;">Enabled?</th>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        $isUsed = $row[11];
                                        ?>
                                        <tr id="vmsAthrzrsRow_<?php echo $cntr; ?>">
                                            <td class="lovtd">
                                                <div class="form-group form-group-sm" style="width:100% !important;">
                                                    <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="vmsAthrzrsRow<?php echo $cntr; ?>_LimitID" value="<?php echo $row[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="vmsAthrzrsRow<?php echo $cntr; ?>_LocID" value="<?php echo $row[1]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="vmsAthrzrsRow<?php echo $cntr; ?>_InUse" value="<?php echo $row[11]; ?>">
                                                </div>
                                            </td>
                                            <td class="lovtd">    
                                                <?php if ($canDelAthrzr === true && $isUsed <= 0) { ?>
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsAthrzr('vmsAthrzrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Authorizer">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                <?php } else { ?>
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="" data-toggle="tooltip" data-placement="bottom" title="">
                                                        <?php echo $isUsed; ?>
                                                    </button>
                                                <?php } ?>  
                                            </td> 
                                            <td class="lovtd">
                                                <?php if ($canEdtAthrzr === true && $isUsed <= 0) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control" aria-label="..." id="vmsAthrzrsRow<?php echo $cntr; ?>_AthrzrNm" value="<?php echo $row[2]; ?>" readonly="true">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '<?php echo $row[1]; ?>', 'vmsAthrzrsRow<?php echo $cntr; ?>_LocID', 'vmsAthrzrsRow<?php echo $cntr; ?>_AthrzrNm', 'clear', 0, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="text" class="form-control" aria-label="..." value="<?php echo $row[2]; ?>" readonly="true" style="width:100%;">
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtAthrzr === true && $isUsed <= 0) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control" aria-label="..." id="vmsAthrzrsRow<?php echo $cntr; ?>_SiteNm" value="<?php echo $row[4]; ?>" readonly="true">
                                                            <input type="hidden" class="form-control" aria-label="..." id="vmsAthrzrsRow<?php echo $cntr; ?>_SiteID" value="<?php echo $row[3]; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', '', '', '', 'radio', true, '<?php echo $row[3]; ?>', 'vmsAthrzrsRow<?php echo $cntr; ?>_SiteID', 'vmsAthrzrsRow<?php echo $cntr; ?>_SiteNm', 'clear', 0, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="text" class="form-control" aria-label="..." value="<?php echo $row[4]; ?>" readonly="true" style="width:100%;">
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtAthrzr === true && $isUsed <= 0) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control" aria-label="..." id="vmsAthrzrsRow<?php echo $cntr; ?>_TrnsTyp" value="<?php echo $row[5]; ?>" readonly="true">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Transaction Types', '', '', '', 'radio', true, '<?php echo $row[5]; ?>', 'vmsAthrzrsRow<?php echo $cntr; ?>_TrnsTyp', '', 'clear', 0, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="text" class="form-control" aria-label="..." value="<?php echo $row[5]; ?>" readonly="true" style="width:100%;">
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtAthrzr === true && $isUsed <= 0) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control" aria-label="..." id="vmsAthrzrsRow<?php echo $cntr; ?>_CrncyNm" value="<?php echo $row[7]; ?>" readonly="true">
                                                            <input type="hidden" class="form-control" aria-label="..." id="vmsAthrzrsRow<?php echo $cntr; ?>_CrncyID" value="<?php echo $row[7]; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $row[7]; ?>', 'vmsAthrzrsRow<?php echo $cntr; ?>_CrncyNm', '', 'clear', 0, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="text" class="form-control" aria-label="..." value="<?php echo $row[7]; ?>" readonly="true" style="width:100%;">
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtAthrzr === true && $isUsed <= 0) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <input type="number" min="0" max="9999999999999999999" class="form-control" aria-label="..." id="vmsAthrzrsRow<?php echo $cntr; ?>_MinAmnt" name="vmsAthrzrsRow<?php echo $cntr; ?>_MinAmnt" value="<?php echo $row[8]; ?>" style="width:100%;">
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="text" class="form-control" aria-label="..." value="<?php echo $row[8]; ?>" readonly="true" style="width:100%;">
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtAthrzr === true && $isUsed <= 0) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <input type="number" min="0" max="9999999999999999999" class="form-control" aria-label="..." id="vmsAthrzrsRow<?php echo $cntr; ?>_MaxAmnt" name="vmsAthrzrsRow<?php echo $cntr; ?>_MaxAmnt" value="<?php echo $row[9]; ?>" style="width:100%;">
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="text" class="form-control" aria-label="..." value="<?php echo $row[9]; ?>" readonly="true" style="width:100%;">
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php
                                                $isChkd = "";
                                                if ($row[10] == "1") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                if ($canEdtAthrzr === true) {
                                                    ?>
                                                    <div class="form-group form-group-sm">
                                                        <div class="form-check" style="font-size: 12px !important;">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" id="vmsAthrzrsRow<?php echo $cntr; ?>_IsEnbld" name="vmsAthrzrsRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <span class=""><?php echo ($row[10] == "1" ? "Yes" : "No"); ?></span>
                                                <?php } ?>                                                         
                                            </td>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|vms.vms_authorizers_limit|authorizer_limit_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
            } else if ($vwtyp == 6) {
                //Vendors and Clients                
                $canAddCstmr = test_prmssns($dfltPrvldgs[49], $mdlNm);
                $canEdtCstmr = test_prmssns($dfltPrvldgs[50], $mdlNm);
                $canDelCstmr = test_prmssns($dfltPrvldgs[51], $mdlNm);
                if ($srcMenu == "Banking") {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                        <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                } else {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                }
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">VMS Administration</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">All Vendors and Clients</span>
				</li>
                               </ul>
                              </div>";
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
                                <input class="form-control" id="allCstmrsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllCstmrs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                <input id="allCstmrsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
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
                            <?php if ($canAddCstmr === true) { ?>                   
                                <button type="button" class="btn btn-default btn-sm" onclick="getOneCstmrsForm(-1, 'ShowDialog');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Vendor/Client
                                </button>
                            <?php } ?>                            
                            <?php if ($canAddCstmr) { ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="">
                                    <img src="cmn_images/groupings.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Import Defined Persons
                                </button>
                            <?php } ?>  
                            <button type="button" class="btn btn-default btn-sm" onclick="">
                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                Export Clients/Vendors
                            </button>                          
                            <?php if ($canAddCstmr) { ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="">
                                    <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Import Clients/Vendors
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
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
                                        <?php if ($canDelCstmr === true) { ?>
                                            <th>&nbsp;</th>
                                        <?php } ?>
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
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneCstmrsForm(<?php echo $row[0]; ?>, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canDelCstmr === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delCstmrs('allCstmrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete User">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|scm.scm_cstmr_suplr|cust_sup_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
            } else if ($vwtyp == 601) {
                //New Customer/Client Form
                $canEdtCstmr = test_prmssns($dfltPrvldgs[49], $mdlNm) || test_prmssns($dfltPrvldgs[50], $mdlNm);
                $sbmtdCstmrSpplrID = isset($_POST['sbmtdCstmrSpplrID']) ? cleanInputData($_POST['sbmtdCstmrSpplrID']) : -1;

                $cstmrSpplrNm = "";
                $cstmrSpplrDesc = "";

                $cstmrSpplrType = "";
                $cstmrSpplrClsfctn = "";
                $cstmrSpplrLnkdPrsn = "";
                $cstmrSpplrLnkdPrsnID = -1;
                $cstmrSpplrGender = "";
                $cstmrSpplrDOB = "";
                $cstmrLbltyAcntID = -1;
                $cstmrLbltyAcntNm = "";
                $cstmrRcvblsAcntID = -1;
                $cstmrRcvblsAcntNm = "";
                $isEnbld = "No";
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
                        $nwFileName = encrypt1($row[25], $smplTokenWord1) . "." . $extension;
                    }
                }
                $ftp_src = $ftp_base_db_fldr . "/Cstmr/" . $sbmtdCstmrSpplrID . "." . $extension;
                $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                if (file_exists($ftp_src) && !is_dir($ftp_src)) {
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
                                                <?php if ($canEdtCstmr === true) { ?> 
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
                                            <?php if ($canEdtCstmr === true) { ?>
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
                                            <?php if ($canEdtCstmr === true) { ?>
                                                <select class="form-control" id="cstmrSpplrType" onchange="">                                                        
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
                                            <?php if ($canEdtCstmr === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="cstmrSpplrClsfctn" value="<?php echo $cstmrSpplrClsfctn; ?>" readonly="">
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
                                            <?php if ($canEdtCstmr === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="cstmrSpplrLnkdPrsn" value="<?php echo $cstmrSpplrLnkdPrsn; ?>" readonly="">
                                                    <input type="hidden" id="cstmrSpplrLnkdPrsnID" value="<?php echo $cstmrSpplrLnkdPrsnID; ?>">
                                                    <label id="cstmrSpplrLnkdPrsnLbl" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', '', '', '', 'radio', true, '<?php echo $cstmrSpplrLnkdPrsnID; ?>', 'cstmrSpplrLnkdPrsnID', 'cstmrSpplrLnkdPrsn', 'clear', 1, '');">
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
                                            <?php if ($canEdtCstmr === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="cstmrSpplrGender" value="<?php echo $cstmrSpplrGender; ?>" readonly="">
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
                                            <?php if ($canEdtCstmr === true) { ?>
                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100%;">
                                                    <input class="form-control" size="16" type="text" id="cstmrSpplrDOB" value="<?php echo $cstmrSpplrDOB; ?>" readonly="true">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $cstmrSpplrDOB; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" >
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
                                                    if ($canEdtCstmr === true) {
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
                                    <fieldset class="basic_person_fs1" style="min-height: 50px !important;"><!--<legend class="basic_person_lg">Item's Picture</legend>-->
                                        <div style="margin-bottom: 5px;margin-top:5px !important;">
                                            <img src="<?php echo $nwFileName; ?>" alt="..." id="img1CstmrTest" class="img-rounded center-block img-responsive" style="height: 150px !important; width: auto !important;">                                            
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <?php if ($canEdtCstmr === true) { ?>
                                        <div class="form-group form-group-sm" style="margin-bottom: 10px;margin-top:10px !important;">
                                            <div class="col-md-12" style="padding:1px !important;">                                               
                                                <label class="btn btn-primary btn-file" style="width:100% !important;min-width: 100% !important;">
                                                    Browse... <input type="file" id="daItemPicture" name="daCstmrPicture" onchange="changeImgSrc(this, '#img1CstmrTest', '#img1CstmrSrcLoc');" class="btn btn-default"  style="display: none;">
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
                        <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:3px 0px 3px 0px;"></div>
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
                                                                <?php if ($canEdtCstmr === true) { ?>                                   
                                                                    <div class="input-group">
                                                                        <input type="text" name="cstmrLbltyAcntNm" id="cstmrLbltyAcntNm" class="form-control" value="<?php echo $cstmrLbltyAcntNm; ?>" readonly="true">
                                                                        <input type="hidden" name="cstmrLbltyAcntID" id="cstmrLbltyAcntID" class="form-control" value="<?php echo $cstmrLbltyAcntID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Liability Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'cstmrLbltyAcntID', 'cstmrLbltyAcntNm', 'clear', 0, '', function () {
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
                                                                <?php if ($canEdtCstmr === true) { ?>                                   
                                                                    <div class="input-group">
                                                                        <input type="text" name="cstmrRcvblsAcntNm" id="cstmrLbltyAcntNm" class="form-control" value="<?php echo $cstmrRcvblsAcntNm; ?>" readonly="true">
                                                                        <input type="hidden" name="cstmrRcvblsAcntID" id="cstmrLbltyAcntID" class="form-control" value="<?php echo $cstmrRcvblsAcntID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'cstmrRcvblsAcntID', 'cstmrRcvblsAcntNm', 'clear', 0, '', function () {
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
                                                                <?php if ($canEdtCstmr === true) { ?>                                   
                                                                    <input type="text" name="cstmrCmpnyBrandNm" id="cstmrCmpnyBrandNm" class="form-control" value="<?php echo $cstmrCmpnyBrandNm; ?>" >                                                                        
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
                                                                <?php if ($canEdtCstmr === true) { ?>                                   
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
                                                                <?php if ($canEdtCstmr === true) { ?>                                   
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
                                                                <?php if ($canEdtCstmr === true) { ?>
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100%;">
                                                                        <input class="form-control" size="16" type="text" id="cstmrSpplrDOB" value="<?php echo $cstmrDateIncprtd; ?>" readonly="true">
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
                                                                <?php if ($canEdtCstmr === true) { ?>                                   
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
                                                                <?php if ($canEdtCstmr === true) { ?>                                   
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
                                                                <?php if ($canEdtCstmr === true) { ?>                                   
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
                                                                <?php if ($canEdtCstmr === true) { ?>                                   
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
                                                                <?php if ($canEdtCstmr === true) { ?>                                   
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
                                                                <?php if ($canEdtCstmr === true) { ?>                                   
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
                                                            <?php if ($canEdtCstmr === true) { ?>
                                                                <button id="addNwVaultBtn1" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('oneCstmrSrvcsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Service Offered">
                                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Add Service Offered
                                                                </button>
                                                            <?php } ?>
                                                            <button id="refreshVltBtn1" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneCstmrsForm(<?php echo $sbmtdCstmrSpplrID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Item Details">
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
                                                                        <th >Service Offered</th>
                                                                        <?php if ($canEdtCstmr === true) { ?>
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
                                                                                    <?php if ($canEdtCstmr === true) { ?>
                                                                                        <div class="">
                                                                                            <div class="input-group"  style="width:100%;">
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
                                                                                <?php if ($canEdtCstmr === true) { ?>
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
                                                    <?php if ($canEdtCstmr === true) { ?>
                                                        <button id="addNwVaultBtn2" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneCstmrSitesForm(-1, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title = "New Site">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add Site
                                                        </button>
                                                    <?php } ?>
                                                    <button id="refreshVltBtn2" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneCstmrsForm(<?php echo $sbmtdCstmrSpplrID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Customer/Supplier Details">
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
                                                                <th >Site Name</th>
                                                                <th >Billing Address</th>
                                                                <th >Shipping Address</th>
                                                                <th >Contact Person</th>
                                                                <th >Contact Nos.</th>
                                                                <th >Email</th>                                                                
                                                                <?php if ($canEdtCstmr === true) { ?>
                                                                    <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                    <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                <?php } ?>
                                                                <?php if ($canVwRcHstry === true) { ?>
                                                                    <th>...</th>
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
                                                                    <?php if ($canEdtCstmr === true) { ?>
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
                                                                    <?php if ($canVwRcHstry === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($rwCage[1] . "|scm.scm_cstmr_suplr_sites|cust_sup_site_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdtCstmr === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveCstmrsForm();">Save Changes</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            } else if ($vwtyp == 602) {
                //New Customer/Supplier Site Form
                $canEdtSite = test_prmssns($dfltPrvldgs[50], $mdlNm);
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
                        <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:3px 0px 3px 0px;"></div>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdtSite === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveCstmrSitesForm();">Save Changes</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            } else if ($vwtyp == 7) {
                //GL Interface Table
                $canSendToGl = test_prmssns($dfltPrvldgs[46], $mdlNm);
                $canAddCrctnTrns = test_prmssns($dfltPrvldgs[47], $mdlNm);
                $canDelCrctnTrns = test_prmssns($dfltPrvldgs[48], $mdlNm);
                if ($srcMenu == "Banking") {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                        <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                } else {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                }
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">VMS Administration</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">GL Interface Table</span>
				</li>
                               </ul>
                              </div>";
                $total = get_VMSGlIntrfcTtl($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte, $qNotSentToGl, $qUnbalncdOnly, $qUsrGnrtd, $qLowVal, $qHighVal);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_VMSGlIntrfc(
                        $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte, $qNotSentToGl, $qUnbalncdOnly, $qUsrGnrtd, $qLowVal, $qHighVal
                );
                $cntr = 0;
                ?> 
                <form id='allVmsGLIntrfcsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                        <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allVmsGLIntrfcsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllVmsGLIntrfcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                <input id="allVmsGLIntrfcsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsGLIntrfcs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <span class="input-group-addon">In</span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsGLIntrfcsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Account Name", "Account Number", "Source",
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
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsGLIntrfcsDsplySze" style="min-width:65px !important;">                            
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
                                    <input class="form-control" size="16" type="text" id="allVmsGLIntrfcsStrtDate" name="allVmsGLIntrfcsStrtDate" value="<?php
                                    echo substr($qStrtDte, 0, 11);
                                    ?>" placeholder="Start Date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div></div>
                            <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text"  id="allVmsGLIntrfcsEndDate" name="allVmsGLIntrfcsEndDate" value="<?php
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
                                        <a class="rhopagination" href="javascript:getAllVmsGLIntrfcs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllVmsGLIntrfcs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                        <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <?php
                                        $notToGlChekd = "";
                                        if ($qNotSentToGl == true) {
                                            $notToGlChekd = "checked=\"true\"";
                                        }
                                        $notBalcdChekd = "";
                                        if ($qUnbalncdOnly == true) {
                                            $notBalcdChekd = "checked=\"true\"";
                                        }
                                        $usrTrnsChekd = "";
                                        if ($qUsrGnrtd == true) {
                                            $usrTrnsChekd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getAllVmsGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" id="allVmsGLIntrfcsSntToGl" name="allVmsGLIntrfcsSntToGl" <?php echo $notToGlChekd; ?>>
                                        Transactions Not Sent to GL
                                    </label>
                                </div>                            
                            </div>
                            <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" onclick="getAllVmsGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" id="allVmsGLIntrfcsUnbalncd" name="allVmsGLIntrfcsUnbalncd"  <?php echo $notBalcdChekd; ?>>
                                        Possible Unbalanced Trns.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" onclick="getAllVmsGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" id="allVmsGLIntrfcsUsrTrns" name="allVmsGLIntrfcsUsrTrns"  <?php echo $usrTrnsChekd; ?>>
                                        User Generated Trns.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group">
                                    <label class="btn btn-primary btn-file input-group-addon">
                                        <span class="glyphicon glyphicon-sort-by-order"></span>
                                    </label>
                                    <input class="form-control" id="allVmsGLIntrfcsLowVal" type = "number" placeholder="Low Value" value="<?php
                                    echo $qLowVal;
                                    ?>" onkeyup="enterKeyFuncAllVmsGLIntrfcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                </div>
                            </div>   
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group">
                                    <label class="btn btn-primary btn-file input-group-addon">
                                        <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                                    </label>
                                    <input class="form-control" id="allVmsGLIntrfcsHighVal" type = "number" placeholder="High Value" value="<?php
                                    echo $qHighVal;
                                    ?>" onkeyup="enterKeyFuncAllVmsGLIntrfcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-4" style="padding:2px 1px 2px 1px !important;">
                                <div class="input-group">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');">
                                        <span style="font-weight:bold;">Imbalance Amount</span>
                                    </label>
                                    <?php
                                    $dffrc = (float) getVMSGLIntrfcDffrnc($orgID);
                                    $style1 = "color:green;";
                                    if (abs($dffrc) != 0) {
                                        $style1 = "color:red;";
                                    }
                                    ?>
                                    <input class="form-control" id="allVmsGLIntrfcsImbalsAmt" type = "text" placeholder="0.00" value="<?php
                                    echo number_format($dffrc, 2);
                                    ?>" readonly="true" style="font-weight:bold;<?php echo $style1; ?>">
                                </div>
                            </div>   
                            <div class="col-md-8" style="padding:2px 1px 2px 1px !important;">
                                <?php if ($canAddCrctnTrns && abs($dffrc) != 0) { ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="getOneVMSGLIntrfcForm(-1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Add Correction Trns.
                                    </button>
                                <?php } ?>                           
                                <?php if ($canDelCrctnTrns) { ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="delSlctdVMSIntrfcLines();">
                                        <img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Void/Delete
                                    </button>
                                <?php } ?>  
                                <?php /* if ($canSendToGl) { ?>
                                  <button type="button" class="btn btn-default btn-sm" onclick="getOneVmsTrnsForm(-1, '<?php echo $trnsType; ?>', 20, 'ShowDialog',<?php echo $vwtyp; ?>, '<?php echo $srcMenu; ?>');">
                                  <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                  Auto-Correct Wrong Transfers
                                  </button>
                                  <?php } */ ?>                            
                                <?php
                                if ($canSendToGl) {
                                    $reportTitle = "Journal Import from VMS Module-Web";
                                    $reportName = "Journal Import from VMS Module-Web";
                                    $rptID = getRptID($reportName);
                                    $prmID1 = getParamIDUseSQLRep("{:glbatch_name}", $rptID);
                                    $prmID2 = getParamIDUseSQLRep("{:intrfc_tbl_name}", $rptID);
                                    $glBtchNm = "%Vault%";
                                    $pIntrfcTblNm = "vms.vms_gl_interface";
                                    $paramRepsNVals = $prmID1 . "~" . $glBtchNm . "|" . $prmID2 . "~" . $pIntrfcTblNm . "|-130~" . $reportTitle . "|-190~PDF";
                                    $paramStr = urlencode($paramRepsNVals);
                                    ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID; ?>');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Send Outstanding Trns. to GL
                                    </button>
                                <?php } ?>                       
                                <?php
                                if ($canSendToGl) {
                                    $reportTitle = "Post GL Transaction Batches-Web";
                                    $reportName = "Post GL Transaction Batches-Web";
                                    $rptID = getRptID($reportName);
                                    $paramRepsNVals = "-130~" . $reportTitle . "|-190~PDF";
                                    $paramStr = urlencode($paramRepsNVals);
                                    ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID; ?>');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Post GL Transactions
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
                <form id='allVmsGLIntrfcsHdrsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allVmsGLIntrfcsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>No.</th>		
                                        <th>Account</th>
                                        <th>Remark/Narration</th>
                                        <th style="text-align:right;">CUR.</th>
                                        <th style="text-align:right;min-width: 100px;width: 100px;">Debit Amount</th>
                                        <th style="text-align:right;min-width: 100px;width: 100px;">Credit Amount</th>
                                        <th>Transaction Date</th>
                                        <th>Source</th>
                                        <th>GL Batch Name</th>
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
                                        <tr id="allVmsGLIntrfcsHdrsRow_<?php echo $cntr; ?>">
                                            <td class="lovtd">
                                                <input type="checkbox" name="allVmsGLIntrfcsHdrsRow<?php echo $cntr; ?>_CheckBox" value="<?php echo $row[0] . ";" . $row[1]; ?>">
                                                <input type="hidden" value="<?php echo $row[0]; ?>" id="allVmsGLIntrfcsHdrsRow<?php echo $cntr; ?>_AccntID">
                                                <input type="hidden" value="<?php echo $row[11]; ?>" id="allVmsGLIntrfcsHdrsRow<?php echo $cntr; ?>_IntrfcID">
                                            </td>                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $row[1] . "." . $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[3]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo $row[15]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php echo number_format((float) $row[5], 2); ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php echo number_format((float) $row[6], 2); ?></td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo $row[8] . "-" . $row[14]; ?></td>
                                            <td class="lovtd"><?php echo $row[10]; ?></td>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[11] . "|vms.vms_gl_interface|interface_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
            } else if ($vwtyp == 701) {
                //New Interface Transaction Form
                $canEdtIntrfc = test_prmssns($dfltPrvldgs[47], $mdlNm);
                $sbmtdIntrfcID = isset($_POST['sbmtdIntrfcID']) ? cleanInputData($_POST['sbmtdIntrfcID']) : -1;
                $slctdIntrfcID = isset($_POST['slctdIntrfcID']) ? cleanInputData($_POST['slctdIntrfcID']) : -1;
                $intrfcTrnsDesc = "";
                $intrfcTrnsDate = "";
                $incrsDcrs = "";
                $accntID = -1;
                $glBatchID = -1;
                $accntNum = "";
                $accntName = "";
                $enteredAmount = 0;
                $enteredCrncyID = -1;
                $enteredCrncyNm = "";
                $funcCrncyRate = 0;
                $accntCrncyRate = 0;
                $funcCrncyAmount = 0;
                $accntCrncyAmount = 0;
                $funcCurrID = -1;
                $funcCrncyNm = "";
                $accntCrncyNm = "";
                $trnsSource = "USR";
                $mkReadOnly = "";
                if ($sbmtdIntrfcID > 0) {
                    $result = get_OneVMSGlIntrfcDet($sbmtdIntrfcID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdIntrfcID = (float) $row[11];
                        $glBatchID = (float) $row[9];
                        $intrfcTrnsDesc = $row[3];
                        $intrfcTrnsDate = $row[4];
                        $accntID = (float) $row[0];
                        $accntNum = $row[1];
                        $accntName = $row[2];
                        $dbtAmnt = (float) $row[5];
                        $crdtAmnt = (float) $row[6];
                        $dbtOrCrdt = "C";
                        if (abs($dbtAmnt) > abs($crdtAmnt)) {
                            $dbtOrCrdt = "D";
                        }
                        if ($dbtOrCrdt == "C") {
                            $incrsDcrs = incrsOrDcrsAccnt($accntID, "Credit");
                            $funcCrncyAmount = $crdtAmnt;
                        } else {
                            $incrsDcrs = incrsOrDcrsAccnt($accntID, "Debit");
                            $funcCrncyAmount = $dbtAmnt;
                        }
                        $enteredAmount = (float) $row[18];
                        $enteredCrncyID = (float) $row[19];
                        $enteredCrncyNm = $row[20];
                        $funcCrncyRate = (float) $row[24];
                        $accntCrncyRate = (float) $row[25];
                        $accntCrncyAmount = (float) $row[21];
                        $funcCurrID = (float) $row[12];
                        $funcCrncyNm = $row[15];
                        $accntCurrID = (float) $row[22];
                        //getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $accntID);
                        $accntCrncyNm = $row[23];
                        //getPssblValNm($accntCurrID);
                        $trnsSource = $row[16];
                        if ($trnsSource == "SYS" || $glBatchID > 0) {
                            $canEdtIntrfc = FALSE;
                            $mkReadOnly = "readonly=\"true\"";
                        }
                    }
                } else {
                    $dfrnce = round(getVMSGLIntrfcDffrnc($orgID), 2);
                    if ($dfrnce == 0) {
                        echo "<div id='rho_form'><H1 style=\"text-align:center; color:red;\">NO IMBALANCE!!!</H1>
                                    <p style=\"text-align:center; color:red;\"><b><i>Sorry, There's no Imbalance to correct! Thank You!</i></b></p>
                                 </div>";
                        exit();
                    }
                }
                ?>
                <form class="form-horizontal" id='addGLIntrfcsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="glIntrfcTrnsDate" class="control-label">Transaction Date:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtIntrfc === true) { ?>                                                
                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss">
                                                    <input class="form-control" size="16" type="text" id="glIntrfcTrnsDate" value="<?php echo $intrfcTrnsDate; ?>" readonly="true">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $intrfcTrnsDate; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="glIntrfcTrnsDesc" class="control-label">Transaction Description:</label>
                                        </div>
                                        <div class="col-md-8">     
                                            <?php if ($canEdtIntrfc === true) { ?>
                                                <textarea rows="2" name="glIntrfcTrnsDesc" id="glIntrfcTrnsDesc" class="form-control rqrdFld"><?php echo $intrfcTrnsDesc; ?></textarea>
                                                <input type="hidden" name="glIntrfcTrnsID" id="glIntrfcTrnsID" class="form-control" value="<?php echo $sbmtdIntrfcID; ?>">                                                       
                                            <?php } else { ?>
                                                <span><?php echo $intrfcTrnsDesc; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="incrsDcrs" class="control-label">Action:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtIntrfc === true) { ?>
                                                <select class="form-control" id="incrsDcrs">                                                        
                                                    <?php
                                                    $valslctdArry = array("", "");
                                                    $valuesArrys = array("Increase", "Decrease");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if (strtoupper($incrsDcrs) == strtoupper($valuesArrys[$z])) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $incrsDcrs; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="intrfcGLAccountNm" class="control-label">GL Account:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtIntrfc === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="intrfcGLAccountNm" id="intrfcGLAccountNm" class="form-control" value="<?php echo $accntNum . "." . $accntName; ?>" readonly="true">
                                                    <input type="hidden" name="intrfcAccntID" id="intrfcAccntID" class="form-control" value="<?php echo $accntID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'intrfcAccntID', 'intrfcGLAccountNm', 'clear', 0, '', function () {
                                                                afterVMSIntrfcItemSlctn();
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $accntNum . "." . $accntName; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">                                    
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="enteredCrncyNm" class="control-label">Entered Amount:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtIntrfc === true) { ?>  
                                                <div class="input-group">                                    
                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="enteredCrncyNm" name="enteredCrncyNm" value="<?php echo $enteredCrncyNm; ?>" readonly="true" style="width:60px;max-width:60px;">    
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $enteredCrncyNm; ?>', 'enteredCrncyNm', '', 'clear', 0, '', function () {
                                                                afterVMSIntrfcItemSlctn();
                                                            });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label> 
                                                    <input class="form-control rqrdFld vmsTtlAmt" type="text" id="enteredAmount" value="<?php
                                                    echo number_format($enteredAmount, 2);
                                                    ?>"  style="font-weight:bold;" onchange="afterVMSIntrfcItemSlctn();"/>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $enteredCrncyNm . " " . number_format($enteredAmount, 2); ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="funcCrncyRate" class="control-label">Func. Curr. Rate:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtIntrfc === true) { ?>                                 
                                                <input type="number" name="funcCrncyRate" id="funcCrncyRate" class="form-control rqrdFld" value="<?php echo $funcCrncyRate; ?>">                                                        
                                            <?php } else { ?>
                                                <span><?php echo $funcCrncyRate; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="accntCrncyRate" class="control-label">Accnt. Curr. Rate:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtIntrfc === true) { ?>                                 
                                                <input type="number" name="accntCrncyRate" id="accntCrncyRate" class="form-control rqrdFld" value="<?php echo $accntCrncyRate; ?>">                                                        
                                            <?php } else { ?>
                                                <span><?php echo $accntCrncyRate; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">                                    
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="funcCrncyAmount" class="control-label">Func. Curr. Amnt:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtIntrfc === true) { ?>  
                                                <div class="input-group">  
                                                    <label class="btn btn-primary btn-file input-group-addon" id="funcCrncyNm"> 
                                                        <?php echo $funcCrncyNm; ?>
                                                    </label> 
                                                    <input class="form-control" type="text" id="funcCrncyAmount" value="<?php
                                                    echo number_format($funcCrncyAmount, 2);
                                                    ?>"  style="font-weight:bold;" onchange="fmtAsNumber('funcCrncyAmount');" readonly="true"/>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $funcCrncyNm . " " . number_format($funcCrncyAmount, 2); ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">                                    
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="accntCrncyAmount" class="control-label">Accnt. Curr. Amnt:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtIntrfc === true) { ?>  
                                                <div class="input-group">   
                                                    <label class="btn btn-primary btn-file input-group-addon" id="accntCrncyNm"> 
                                                        <?php echo $accntCrncyNm; ?>
                                                    </label>
                                                    <input class="form-control" type="text" id="accntCrncyAmount" value="<?php
                                                    echo number_format($accntCrncyAmount, 2);
                                                    ?>"  style="font-weight:bold;" onchange="fmtAsNumber('accntCrncyAmount');" readonly="true"/>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $accntCrncyNm . " " . number_format($accntCrncyAmount, 2); ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>                               
                        </div>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdtIntrfc === true && $trnsSource != "SYS" && $glBatchID <= 0) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveVMSGLIntrfcForm();">Save Changes</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            } else if ($vwtyp == 702) {
                header("content-type:application/json");
                $intrfcAccntID = isset($_POST['intrfcAccntID']) ? (float) cleanInputData($_POST['intrfcAccntID']) : -1;
                $trnsDate = isset($_POST['glIntrfcTrnsDate']) ? cleanInputData($_POST['glIntrfcTrnsDate']) : "";
                $funcCrncyRate = isset($_POST['funcCrncyRate']) ? cleanInputData($_POST['funcCrncyRate']) : "";
                $accntCrncyRate = isset($_POST['accntCrncyRate']) ? cleanInputData($_POST['accntCrncyRate']) : "";
                $enteredCrncyNm = isset($_POST['enteredCrncyNm']) ? cleanInputData($_POST['enteredCrncyNm']) : "";
                $enteredAmount = isset($_POST['enteredAmount']) ? (float) cleanInputData($_POST['enteredAmount']) : 0;
                $accntCurrID = (float) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $intrfcAccntID);
                $accntCurrNm = getPssblValNm($accntCurrID);
                $enteredCrncyID = getPssblValID($enteredCrncyNm, getLovID("Currencies"));

                $funcCurrID = getOrgFuncCurID($orgID);
                $funcCrncyNm = getPssblValNm($funcCurrID);
                if ($funcCrncyRate == 1 || $funcCrncyRate == 0) {
                    $funcCrncyRate = get_LtstExchRate($enteredCrncyID, $funcCurrID, $trnsDate);
                }
                if ($accntCrncyRate == 1 || $accntCrncyRate == 0) {
                    $accntCrncyRate = get_LtstExchRate($enteredCrncyID, $accntCurrID, $trnsDate);
                }
                $funcCrncyAmount = ($funcCrncyRate * $enteredAmount);
                $accntCrncyAmount = ($accntCrncyRate * $enteredAmount);
                $arr_content['FuncCrncyRate'] = $funcCrncyRate;
                $arr_content['AccntCrncyRate'] = $accntCrncyRate;
                $arr_content['FuncCrncyNm'] = $funcCrncyNm;
                $arr_content['FuncCrncyAmount'] = $funcCrncyAmount;
                $arr_content['AccntCrncyNm'] = $accntCurrNm;
                $arr_content['AccntCrncyAmount'] = $accntCrncyAmount;
                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                echo json_encode($arr_content);
                exit();
            }
        }
    }
}    