<?php
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
$vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : 0;
$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$canAddTrns = true;
$canEdtTrns = true;
$qNotSentToGl = true;
$qUnbalncdOnly = false;
$qUsrGnrtd = false;
$qStrtDte = "01-Jan-1900 00:00:00";
$qEndDte = "31-Dec-4000 23:59:59";
$qNwStrtDte = date('d-M-Y H:i:s');
$qLowVal = 0;
$qHighVal = 0;
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);
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

if (isset($_POST['qNwStrtDte'])) {
    $qNwStrtDte = cleanInputData($_POST['qNwStrtDte']);
    if (strlen($qNwStrtDte) == 11) {
        $qNwStrtDte = substr($qNwStrtDte, 0, 11) . " 00:00:00";
    } else {
        $qNwStrtDte = date('d-M-Y H:i:s');
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
        if ($qstr == "DELETEDOC") {
            if ($actyp == 1) {
                /* Delete Attachment */
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $docTrnsNum = isset($_POST['docTrnsNum']) ? cleanInputData($_POST['docTrnsNum']) : -1;
                if ($canEdtTrns) {
                    echo deleteMCFDoc($attchmentID, $docTrnsNum);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATEDOC") {
            if ($actyp == 1) {
                //Upload Attachement
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdHdrID = isset($_POST['sbmtdHdrID']) ? cleanInputData($_POST['sbmtdHdrID']) : -1;
                $pAcctID = isset($_POST['pAcctID']) ? cleanInputData($_POST['pAcctID']) : -1;
                if (!($canEdtTrns || $canAddTrns)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }

                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $docFileType = isset($_POST['docFileType']) ? cleanInputData($_POST['docFileType']) : "";
                $docTrsType = isset($_POST['docTrsType']) ? cleanInputData($_POST['docTrsType']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                if ($sbmtdHdrID <= 0 && $pAcctID > 0 && $docTrsType != "") {
                    $dateStr = getDB_Date_time();
                    //check existence of account transaction                    
                    createInitAccountTrns($pAcctID, $dateStr, $docTrsType);
                    $sbmtdHdrID = getInitAccountTrnsID($pAcctID, $dateStr);
                }
                $pkID = $sbmtdHdrID;
                if ($sbmtdHdrID > 0 && $docCtrgrName != "" && $docFileType != "" && $docTrsType != "") {
                    if ($attchmentID > 0) {
                        uploadDaMCFTrnsDoc($attchmentID, $docTrsType, $docFileType, $nwImgLoc, $errMsg);
                    } else {
                        $attchmentID = getNewMCFDocID();
                        createMCFDoc($attchmentID, $sbmtdHdrID, $docCtrgrName, "", $docFileType, $docTrsType);
                        uploadDaMCFTrnsDoc($attchmentID, $docTrsType, $docFileType, $nwImgLoc, $errMsg);
                    }
                    $arr_content['attchID'] = $attchmentID;
                    $arr_content['NwTrnsId'] = $sbmtdHdrID;
                    if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                        $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                    } else {
                        $doc_src = "";
                        if ($docTrsType == "Individual Customers" || $docTrsType == "Corporate Customers" || $docTrsType == "Customer Groups" || $docTrsType == "Other Persons") {
                            if ($docFileType == "Picture") {
                                $doc_src = $ftp_base_db_fldr . "/Mcf/Customers/Picture/" . $nwImgLoc;
                            } else if ($docFileType == "Signature") {
                                $doc_src = $ftp_base_db_fldr . "/Mcf/Customers/Signature/" . $nwImgLoc;
                            } else if ($docFileType == "Thumbprint") {
                                $doc_src = $ftp_base_db_fldr . "/Mcf/Customers/Thumbprint/" . $nwImgLoc;
                            } else {
                                $doc_src = $ftp_base_db_fldr . "/Mcf/Customers/Attachment/" . $nwImgLoc;
                            }
                        } else {
                            $doc_src = $ftp_base_db_fldr . "/Mcf/Transactions/" . $nwImgLoc;
                        }
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
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Incompleted Data Supplied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } else if ($qstr == "DELETECONF") {
            if ($actyp == 2) {
                /* Delete Currency */
                $canDelCrncy = test_prmssns($dfltPrvldgs[177], $mdlNm);
                $crncyID = isset($_POST['crncyID']) ? cleanInputData($_POST['crncyID']) : -1;
                $crncyNm = isset($_POST['crncyNm']) ? cleanInputData($_POST['crncyNm']) : "";
                if ($canDelCrncy) {
                    echo deleteCurrency($crncyID, $crncyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Currency Denomination */
                $canDelCrncy = test_prmssns($dfltPrvldgs[177], $mdlNm);
                $denomID = isset($_POST['denomID']) ? cleanInputData($_POST['denomID']) : -1;
                $denomNm = isset($_POST['denomNm']) ? cleanInputData($_POST['denomNm']) : "";
                if ($canDelCrncy) {
                    echo deleteCurrencyDenom($denomID, $crncyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 4) {
                /* Delete Bank */
                $canDelBnk = test_prmssns($dfltPrvldgs[195], $mdlNm);
                $bankID = isset($_POST['bankID']) ? cleanInputData($_POST['bankID']) : -1;
                $bankNm = isset($_POST['bankNm']) ? cleanInputData($_POST['bankNm']) : "";
                if ($canDelBnk) {
                    echo deleteBank($bankID, $bankNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                /* Delete Bank Branch */
                $canDelBnk = test_prmssns($dfltPrvldgs[199], $mdlNm);
                $branchID = isset($_POST['branchID']) ? cleanInputData($_POST['branchID']) : -1;
                $branchNm = isset($_POST['branchNm']) ? cleanInputData($_POST['branchNm']) : "";
                if ($canDelBnk) {
                    echo deleteBankBranch($branchID, $branchNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 6) {
                /* Delete Rate */
                $canDelExRate = test_prmssns($dfltPrvldgs[185], $mdlNm);
                $rateID = isset($_POST['rateID']) ? cleanInputData($_POST['rateID']) : -1;
                $rateIDDesc = isset($_POST['rateIDDesc']) ? cleanInputData($_POST['rateIDDesc']) : "";
                if ($canDelExRate) {
                    $affctd1 = deleteRate($rateID, $rateIDDesc);
                    if ($affctd1 > 0) {
                        $dsply = "Successfully Deleted the ff Records-";
                        $dsply .= "<br/>$affctd1 Exchange Rate(s)!";
                        echo "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                    } else {
                        $dsply = "No Record Deleted!";
                        echo "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                    }
                } else {
                    restricted();
                }
            } else if ($actyp == 7) {
                /* Delete Rate */
                $canDelExRate = test_prmssns($dfltPrvldgs[185], $mdlNm);
                $slctdRateIDs = isset($_POST['slctdRateIDs']) ? cleanInputData($_POST['slctdRateIDs']) : '';
                $variousRows = explode("|", trim($slctdRateIDs, "|"));
                $affctd1 = 0;
                if ($canDelExRate) {
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 2) {
                            $rateID = (float) cleanInputData1($crntRow[0]);
                            $rateIDDesc = cleanInputData1($crntRow[1]);
                            $affctd1 += deleteRate($rateID, $rateIDDesc);
                        }
                    }
                    if ($affctd1 > 0) {
                        $dsply = "Successfully Deleted the ff Records-";
                        $dsply .= "<br/>$affctd1 Exchange Rate(s)!";
                        echo "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                    } else {
                        $dsply = "No Record Deleted!";
                        echo "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                    }
                } else {
                    restricted();
                }
            } else if ($actyp == 8) {
                /* Delete Cage/Till */
                $canDelCage = test_prmssns($dfltPrvldgs[181], $mdlNm);
                $lineID = isset($_POST['lineID']) ? cleanInputData($_POST['lineID']) : -1;
                $cageNm = isset($_POST['cageNm']) ? cleanInputData($_POST['cageNm']) : "";
                if ($canDelCage) {
                    echo deleteCageTill($lineID, $cageNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 9) {
                /* Delete Interface Transaction */
                $canDelTrns = test_prmssns($dfltPrvldgs[214], $mdlNm);
                $slctdIntrfcIDs = isset($_POST['slctdIntrfcIDs']) ? cleanInputData($_POST['slctdIntrfcIDs']) : '';
                $variousRows = explode("|", trim($slctdIntrfcIDs, "|"));
                $affctd1 = 0;
                if ($canDelTrns) {
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 2) {
                            $intrfcID = (float) cleanInputData1($crntRow[0]);
                            $intrfcIDDesc = cleanInputData1($crntRow[1]);
                            $affctd1 += deleteMCFTrnsGLIntFcLn($intrfcID, $intrfcIDDesc);
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
            } else if ($actyp == 10) {
                /* Delete Cage/Till */
                $canDelDfltAcnt = test_prmssns($dfltPrvldgs[191], $mdlNm);
                $lineID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $dfltAcntDesc = isset($_POST['dfltAcntDesc']) ? cleanInputData($_POST['dfltAcntDesc']) : "";
                if ($canDelDfltAcnt) {
                    echo deleteDfltAcnt($lineID, $dfltAcntDesc);
                } else {
                    restricted();
                }
            } else if ($actyp == 11) {
                /* Delete LOAN CLASSIFICATION */
                $canDelDfltAcnt = test_prmssns($dfltPrvldgs[255], $mdlNm);
                $lineID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;

                if ($canDelDfltAcnt) {
                    echo deleteLoanClsfctn($lineID);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATECONF") {
            if ($actyp == 2) {
                header("content-type:application/json");
                $crncyID = isset($_POST['crncyID']) ? (float) cleanInputData($_POST['crncyID']) : '';
                $crncyISOCode = isset($_POST['crncyISOCode']) ? cleanInputData($_POST['crncyISOCode']) : '';
                $crncyDesc = isset($_POST['crncyDesc']) ? cleanInputData($_POST['crncyDesc']) : '';
                $mppdCrncyNm = isset($_POST['mppdCrncyNm']) ? cleanInputData($_POST['mppdCrncyNm']) : '';
                $mppdCrncyID = getPssblValID($mppdCrncyNm, getLovID("Currencies"));
                $isCrncyEnbld = isset($_POST['isCrncyEnbld']) ? cleanInputData($_POST['isCrncyEnbld']) : 'NO';
                $slctdDenoms = isset($_POST['slctdDenoms']) ? cleanInputData($_POST['slctdDenoms']) : '';
                $oldCrncyID = getCrncyID($crncyISOCode, $orgID);
                $isCrncyEnbldBool = $isCrncyEnbld == "NO" ? FALSE : TRUE;
                $errMsg = "";
                if ($crncyISOCode != "" && $crncyDesc != "" && ($oldCrncyID <= 0 || $oldCrncyID == $crncyID)) {
                    if ($crncyID <= 0) {
                        createCurrency($orgID, $crncyISOCode, $crncyDesc, $isCrncyEnbld, $mppdCrncyID);
                        $crncyID = getCrncyID($crncyISOCode, $orgID);
                    } else {
                        updateCurrency($crncyID, $crncyISOCode, $crncyDesc, $isCrncyEnbld, $mppdCrncyID);
                    }
                    //Save Currency Denominations
                    $affctd = 0;
                    $variousRows = explode("|", trim($slctdDenoms, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 6) {
                            $denomID = (float) cleanInputData1($crntRow[0]);
                            $denomNm = cleanInputData1($crntRow[1]);
                            $denomType = cleanInputData1($crntRow[2]);
                            $denomVal = (float) cleanInputData1($crntRow[3]);
                            $islnEnbld = cleanInputData1($crntRow[4]);
                            $lnkdItmID = (float) cleanInputData1($crntRow[5]);
                            if ($denomID <= 0) {
                                $affctd += createCurrencyDenom($crncyID, $denomVal, $denomType, $denomNm, $orgID, $islnEnbld, $lnkdItmID);
                            } else {
                                $affctd += updateCurrencyDenom($denomID, $denomVal, $denomType, $denomNm, $islnEnbld, $lnkdItmID);
                            }
                        }
                    }

                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Currency Successfully Saved!<br/>" . $affctd . " Denominations Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 3) {
                header("content-type:application/json");
                $oneBnkDetID = isset($_POST['oneBnkDetID']) ? (float) cleanInputData($_POST['oneBnkDetID']) : '';
                $oneBnkDetCode = isset($_POST['oneBnkDetCode']) ? cleanInputData($_POST['oneBnkDetCode']) : '';
                $oneBnkDetName = isset($_POST['oneBnkDetName']) ? cleanInputData($_POST['oneBnkDetName']) : '';
                $oneBnkDetCntct = isset($_POST['oneBnkDetCntct']) ? cleanInputData($_POST['oneBnkDetCntct']) : '';
                $oneBnkDetEmail = isset($_POST['oneBnkDetEmail']) ? cleanInputData($_POST['oneBnkDetEmail']) : '';
                $oneBnkDetISOCntryCode = isset($_POST['oneBnkDetISOCntryCode']) ? cleanInputData($_POST['oneBnkDetISOCntryCode']) : '';
                $oneBnkDetChkDgts = isset($_POST['oneBnkDetChkDgts']) ? cleanInputData($_POST['oneBnkDetChkDgts']) : '';
                $oneBnkDetSwiftCode = isset($_POST['oneBnkDetSwiftCode']) ? cleanInputData($_POST['oneBnkDetSwiftCode']) : '';
                $oneBnkDetFax = isset($_POST['oneBnkDetFax']) ? cleanInputData($_POST['oneBnkDetFax']) : '';
                $oneBnkDetPstl = isset($_POST['oneBnkDetPstl']) ? cleanInputData($_POST['oneBnkDetPstl']) : '';
                $oneBnkDetRes = isset($_POST['oneBnkDetRes']) ? cleanInputData($_POST['oneBnkDetRes']) : '';

                $isBnkEnbld = isset($_POST['isBnkEnbld']) ? cleanInputData($_POST['isBnkEnbld']) : 'NO';
                $slctdBranches = isset($_POST['slctdBranches']) ? cleanInputData($_POST['slctdBranches']) : '';
                $oldBankID = getGnrlRecID2("mcf.mcf_all_banks", "bank_code", "bank_id", $oneBnkDetCode);
                $oldBankID1 = getGnrlRecID2("mcf.mcf_all_banks", "bank_code", "bank_id", $oneBnkDetName);
                $isBnkEnbldVal = $isBnkEnbld == "NO" ? "0" : "1";
                $errMsg = "";
                if ($oneBnkDetCode != "" && $oneBnkDetName != "" && ($oldBankID <= 0 || $oldBankID == $oneBnkDetID) && ($oldBankID1 <= 0 || $oldBankID1 == $oneBnkDetID)) {
                    if ($oneBnkDetID <= 0) {
                        createBank($orgID, $oneBnkDetCode, $oneBnkDetName, $oneBnkDetRes, $oneBnkDetPstl, $oneBnkDetCntct, $oneBnkDetEmail, $oneBnkDetFax, $oneBnkDetISOCntryCode, $oneBnkDetChkDgts, $oneBnkDetSwiftCode, $isBnkEnbldVal);
                        $oneBnkDetID = getGnrlRecID2("mcf.mcf_all_banks", "bank_code", "bank_id", $oneBnkDetCode);
                    } else {
                        updateBank($oneBnkDetID, $oneBnkDetCode, $oneBnkDetName, $oneBnkDetRes, $oneBnkDetPstl, $oneBnkDetCntct, $oneBnkDetEmail, $oneBnkDetFax, $oneBnkDetISOCntryCode, $oneBnkDetChkDgts, $oneBnkDetSwiftCode, $isBnkEnbldVal);
                    }
                    //Save Bank Branches
                    //var_dump($_POST);
                    $affctd = 0;
                    $variousRows = explode("|", trim($slctdBranches, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 9) {
                            $branchID = (float) cleanInputData1($crntRow[0]);
                            $branchCode = cleanInputData1($crntRow[1]);
                            $branchNm = cleanInputData1($crntRow[2]);
                            $pstlAdrs = cleanInputData1($crntRow[3]);
                            $resAdrs = cleanInputData1($crntRow[4]);
                            $cntctNos = cleanInputData1($crntRow[5]);
                            $swftCode = cleanInputData1($crntRow[6]);
                            $islnEnbld = cleanInputData1($crntRow[7]);
                            $lnBnkID = (float) cleanInputData1($crntRow[8]);
                            $faxNo = "";
                            $islnEnbldVal = $islnEnbld == "NO" ? "0" : "1";
                            if ($branchID <= 0) {
                                $affctd += createBankBranch($orgID, $branchNm, $branchCode, $pstlAdrs, $cntctNos, $faxNo, $resAdrs, $oneBnkDetID, $branchNm, $swftCode, $islnEnbldVal);
                            } else {
                                $affctd += updateBankBranch($branchID, $branchNm, $branchCode, $pstlAdrs, $cntctNos, $faxNo, $resAdrs, $oneBnkDetID, $branchNm, $swftCode, $islnEnbldVal);
                            }
                        }
                    }

                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Bank Successfully Saved!<br/>" . $affctd . " Bank Branches Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 4) {
                header("content-type:application/json");
                $slctdRateIDs = isset($_POST['slctdRateIDs']) ? cleanInputData($_POST['slctdRateIDs']) : '';
                $errMsg = "";
                if ($slctdRateIDs != "") {
                    //Save Exchange Rates
                    $affctd = 0;
                    $variousRows = explode("|", trim($slctdRateIDs, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 3) {
                            $rateID = (float) cleanInputData1($crntRow[0]);
                            $rateValue = (float) cleanInputData1($crntRow[1]);
                            $rateValue1 = (float) cleanInputData1($crntRow[1]);
                            if ($rateID > 0) {
                                $affctd += updtRateValue($rateID, $rateValue, $rateValue1);
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $affctd . " Exchange Rate Value(s) Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 5) {
                header("content-type:application/json");
                $newRateDate = isset($_POST['newRateDate']) ? cleanInputData($_POST['newRateDate']) : '';
                $errMsg = "";
                if ($newRateDate != "") {
                    //Create Blank/Default Exchange Rates
                    $affctd = 0;
                    $funCurID = getOrgFuncCurID($orgID);
                    $funcCurCode = getPssblValNm($funCurID);
                    $result = get_ExchgCurrencies($funcCurCode);
                    while ($row = loc_db_fetch_array($result)) {
                        if (doesRateExst($newRateDate, $row[1], $funcCurCode) == false) {
                            $affctd += createRate($newRateDate, $row[1], $row[0], $funcCurCode, $funCurID, 1.0000, 1.0000);
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $affctd . " Exchange Rate Line(s) Created!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 6) {
                header("content-type:application/json");
                $newRateDate = isset($_POST['newRateDate']) ? cleanInputData($_POST['newRateDate']) : '';
                $errMsg = "";
                if ($newRateDate != "" && checkForInternetConnection() === true) {
                    //Download Exchange Rates from https://docs.openexchangerates.org/
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_URL, "https://openexchangerates.org/api/historical/" . cnvrtDMYToYMD($newRateDate) . ".json?app_id=5dba57b2d47b4a11b4e5a020522de567");
                    $result1 = curl_exec($ch);
                    curl_close($ch);

                    $currencyRates = json_decode($result1);
                    $affctd = 0;
                    $funCurID = getOrgFuncCurID($orgID);
                    $funcCurCode = getPssblValNm($funCurID);
                    $result = get_ExchgCurrencies($funcCurCode);

                    $fromCurID = -1;
                    $toCurID = $funCurID;
                    $rateVals = json_decode(json_encode($currencyRates->rates), True);
                    $rateVal = (float) $rateVals[$funcCurCode];
                    $baseToFuncCurRate = $rateVal;
                    $dateStr = $newRateDate;
                    while ($row = loc_db_fetch_array($result)) {
                        $fromCurID = getPssblValID($row[1], getLovID("Currencies"));
                        $rateID = doesRateExst1($dateStr, $row[1], $funcCurCode);
                        $rateVal = (float) $rateVals[$row[1]];
                        if ($rateVal > 0) {
                            $rateVal = ($baseToFuncCurRate / $rateVal);
                            if ($rateID <= 0) {
                                $affctd += createRate($newRateDate, $row[1], $fromCurID, $funcCurCode, $toCurID, $rateVal, 0.96 * $rateVal);
                            } else {
                                $affctd += updtRateValue($rateID, $rateVal, 0.96 * $rateVal);
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $affctd . " Exchange Rate Line(s) Created!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Ensure that a Valid New Rates Date has been provided and that the Internet Connection is Working!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 7) {
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
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Cage/Till Successfully Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 8) {
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
                            createMCFTrnsGLIntFcLn($intrfcAccntID, $glIntrfcTrnsDesc, $funcCrncyAmount, $glIntrfcTrnsDate, $funcCurrID, 0, $netamnt, "Imbalance Correction", -1, -1, $dateStr, "", "USR", $enteredAmount, $enteredCrncyID, $accntCrncyAmount, $accntCurrID, $funcCrncyRate, $accntCrncyRate);
                        } else {
                            updateMCFTrnsGLIntFcLn($glIntrfcTrnsID, $intrfcAccntID, $glIntrfcTrnsDesc, $funcCrncyAmount, $glIntrfcTrnsDate, $funcCurrID, 0, $netamnt, "Imbalance Correction", -1, -1, $dateStr, "", "USR", $enteredAmount, $enteredCrncyID, $accntCrncyAmount, $accntCurrID, $funcCrncyRate, $accntCrncyRate);
                        }
                    } else {
                        if ($glIntrfcTrnsID <= 0) {
                            createMCFTrnsGLIntFcLn($intrfcAccntID, $glIntrfcTrnsDesc, 0, $glIntrfcTrnsDate, $funcCurrID, $funcCrncyAmount, $netamnt, "Imbalance Correction", -1, -1, $dateStr, "", "USR", $enteredAmount, $enteredCrncyID, $accntCrncyAmount, $accntCurrID, $funcCrncyRate, $accntCrncyRate);
                        } else {
                            updateMCFTrnsGLIntFcLn($glIntrfcTrnsID, $intrfcAccntID, $glIntrfcTrnsDesc, 0, $glIntrfcTrnsDate, $funcCurrID, $funcCrncyAmount, $netamnt, "Imbalance Correction", -1, -1, $dateStr, "", "USR", $enteredAmount, $enteredCrncyID, $accntCrncyAmount, $accntCurrID, $funcCrncyRate, $accntCrncyRate);
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
            } else if ($actyp == 9) {
                //Save Default Account
                $afftctd = 0;
                $slctdDfltAcnts = isset($_POST['slctdDfltAcnts']) ? cleanInputData($_POST['slctdDfltAcnts']) : '';
                if (trim($slctdDfltAcnts, "|~") != "") {
                    //Save Persons
                    $variousRows = explode("|", trim($slctdDfltAcnts, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 6) {
                            $lineID = (int) (cleanInputData1($crntRow[0]));
                            $systemType = cleanInputData1($crntRow[1]);
                            $trnsSubType = (cleanInputData1($crntRow[2]));
                            $glAccountID = (float) cleanInputData1($crntRow[3]);
                            $cstmrAccntID = (float) cleanInputData1($crntRow[4]);
                            $isEnbld = cleanInputData1($crntRow[5]) == "YES" ? "1" : "0";
                            $oldLineID = getDfltAcntLineID($systemType, $trnsSubType);
                            if ($oldLineID <= 0 && $lineID <= 0) {
                                //Insert
                                $afftctd += createMcfDfltAcnt($systemType, $trnsSubType, $glAccountID, $cstmrAccntID, $isEnbld);
                            } else if ($lineID > 0) {
                                $afftctd += updateMcfDfltAcnt($lineID, $systemType, $trnsSubType, $glAccountID, $cstmrAccntID, $isEnbld);
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $afftctd; ?> Default Accounts Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"> Failed to Save Default Accounts!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 10) {
                //Save Global Variables
                $afftctd = 0;
                $slctdDfltGlobalVars = isset($_POST['slctdDfltGlobalVars']) ? cleanInputData($_POST['slctdDfltGlobalVars']) : '';
                if (trim($slctdDfltGlobalVars, "|~") != "") {
                    //Save Persons
                    $variousRows = explode("|", trim($slctdDfltGlobalVars, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 3) {
                            $lineID = (int) (cleanInputData1($crntRow[0]));
                            $VarValue = cleanInputData1($crntRow[1]);
                            $VarDataType = (float) (cleanInputData1($crntRow[2]));
                            $afftctd += updateMcfDfltGlobalVars($lineID, $VarValue, $VarDataType);
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $afftctd; ?> Default Global Variable Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"> Failed to Save Default Global Variable!</span>
                        </div>
                    </div>
                    <?php
                }
            } else if ($actyp == 11) {
                //Save Loan Classifications
                $afftctd = 0;
                $slctdLoanClsfctns = isset($_POST['slctdLoanClsfctns']) ? cleanInputData($_POST['slctdLoanClsfctns']) : '';
                if (trim($slctdLoanClsfctns, "|~") != "") {
                    //Save Persons
                    $variousRows = explode("|", trim($slctdLoanClsfctns, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 6) {
                            $lineID = (int) (cleanInputData1($crntRow[0]));
                            $ClsfctnNm = cleanInputData1($crntRow[1]);
                            $RangeLow = (float) (cleanInputData1($crntRow[2]));
                            $RangeHigh = (float) cleanInputData1($crntRow[3]);
                            $PrvsnPrcnt = (float) cleanInputData1($crntRow[4]);
                            $isEnbld = cleanInputData1($crntRow[5]) == "YES" ? "1" : "0";
                            $oldLineID = getLoanClsfctnLineID($ClsfctnNm);
                            if ($oldLineID <= 0 && $lineID <= 0) {
                                //Insert
                                $afftctd += createMcfLoanClsfctn($ClsfctnNm, $PrvsnPrcnt, $RangeLow, $RangeHigh, $isEnbld);
                            } else if ($lineID > 0) {
                                $afftctd += updateMcfLoanClsfctn($lineID, $ClsfctnNm, $PrvsnPrcnt, $RangeLow, $RangeHigh, $isEnbld);
                            }
                        }
                    }
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $afftctd; ?> Loan Classification Saved Successfully!</span>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                        <div class="row" style="float:none;width:100%;text-align: center;">
                            <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"> Failed to Save Loan Classification!</span>
                        </div>
                    </div>
                    <?php
                }
            }
        } else {
            if ($vwtyp == 0) {
                if ($subPgNo == 1.1) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8');\">
					<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span><span style=\"text-decoration:none;\"> Configuration Menu</span>
                                    </li>
                                    <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.1');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">GL Interface</span>
                                    </li>
                                   </ul>
                                  </div>";
                    //GL Interface Table
                    $canSendToGl = test_prmssns($dfltPrvldgs[214], $mdlNm);
                    $canAddCrctnTrns = test_prmssns($dfltPrvldgs[214], $mdlNm);
                    $canDelCrctnTrns = test_prmssns($dfltPrvldgs[214], $mdlNm);
                    $total = get_MCFGlIntrfcTtl($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte, $qNotSentToGl, $qUnbalncdOnly, $qUsrGnrtd, $qLowVal, $qHighVal);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_MCFGlIntrfc(
                            $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte, $qNotSentToGl, $qUnbalncdOnly, $qUsrGnrtd, $qLowVal, $qHighVal
                    );
                    $cntr = 0;
                    ?> 
                    <form id='allGLIntrfcsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                            <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="allGLIntrfcsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAllGLIntrfcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                    <input id="allGLIntrfcsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllGLIntrfcs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group">
                                    <span class="input-group-addon">In</span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allGLIntrfcsSrchIn">
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
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allGLIntrfcsDsplySze" style="min-width:65px !important;">                            
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
                                        <input class="form-control" size="16" type="text" id="allGLIntrfcsStrtDate" name="allGLIntrfcsStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div></div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="allGLIntrfcsEndDate" name="allGLIntrfcsEndDate" value="<?php
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
                                            <a class="rhopagination" href="javascript:getAllGLIntrfcs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllGLIntrfcs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Next">
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
                                            <input type="checkbox" class="form-check-input" onclick="getAllGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" id="allGLIntrfcsSntToGl" name="allGLIntrfcsSntToGl" <?php echo $notToGlChekd; ?>>
                                            Transactions Not Sent to GL
                                        </label>
                                    </div>                            
                                </div>
                                <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" onclick="getAllGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" id="allGLIntrfcsUnbalncd" name="allGLIntrfcsUnbalncd"  <?php echo $notBalcdChekd; ?>>
                                            Possible Unbalanced Trns.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" onclick="getAllGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" id="allGLIntrfcsUsrTrns" name="allGLIntrfcsUsrTrns"  <?php echo $usrTrnsChekd; ?>>
                                            User Generated Trns.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-primary btn-file input-group-addon">
                                            <span class="glyphicon glyphicon-sort-by-order"></span>
                                        </label>
                                        <input class="form-control" id="allGLIntrfcsLowVal" type = "number" placeholder="Low Value" value="<?php
                                        echo $qLowVal;
                                        ?>" onkeyup="enterKeyFuncAllGLIntrfcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                    </div>
                                </div>   
                                <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-primary btn-file input-group-addon">
                                            <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                                        </label>
                                        <input class="form-control" id="allGLIntrfcsHighVal" type = "number" placeholder="High Value" value="<?php
                                        echo $qHighVal;
                                        ?>" onkeyup="enterKeyFuncAllGLIntrfcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                <div class="col-md-4" style="padding:2px 1px 2px 1px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');">
                                            <span style="font-weight:bold;">Imbalance Amount</span>
                                        </label>
                                        <?php
                                        $dffrc = (float) getMCFGLIntrfcDffrnc($orgID);
                                        $style1 = "color:green;";
                                        if (abs($dffrc) != 0) {
                                            $style1 = "color:red;";
                                        }
                                        ?>
                                        <input class="form-control" id="allGLIntrfcsImbalsAmt" type = "text" placeholder="0.00" value="<?php
                                        echo number_format($dffrc, 2);
                                        ?>" readonly="true" style="font-weight:bold;<?php echo $style1; ?>">
                                    </div>
                                </div>   
                                <div class="col-md-8" style="padding:2px 1px 2px 1px !important;">
                                    <?php if ($canAddCrctnTrns && abs($dffrc) != 0) { ?>
                                        <button type="button" class="btn btn-default btn-sm" onclick="getOneMCFGLIntrfcForm(-1);">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Add Correction Trns.
                                        </button>
                                    <?php } ?>                           
                                    <?php if ($canDelCrctnTrns) { ?>
                                        <button type="button" class="btn btn-default btn-sm" onclick="delSlctdMCFIntrfcLines();">
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
                                        $reportTitle = "Journal Import from Banking Module-Web";
                                        $reportName = "Journal Import from Banking Module-Web";
                                        $rptID = getRptID($reportName);
                                        $prmID1 = getParamIDUseSQLRep("{:glbatch_name}", $rptID);
                                        $prmID2 = getParamIDUseSQLRep("{:intrfc_tbl_name}", $rptID);
                                        $glBtchNm = "%Banking%";
                                        $pIntrfcTblNm = "mcf.mcf_gl_interface";
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
                    <form id='allGLIntrfcsHdrsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allGLIntrfcsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
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
                                            <tr id="allGLIntrfcsHdrsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd">
                                                    <input type="checkbox" name="allGLIntrfcsHdrsRow<?php echo $cntr; ?>_CheckBox" value="<?php echo $row[0] . ";" . $row[1]; ?>">
                                                    <input type="hidden" value="<?php echo $row[0]; ?>" id="allGLIntrfcsHdrsRow<?php echo $cntr; ?>_AccntID">
                                                    <input type="hidden" value="<?php echo $row[11]; ?>" id="allGLIntrfcsHdrsRow<?php echo $cntr; ?>_IntrfcID">
                                                </td>                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1] . ": " . $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[3]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo $row[15]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php echo number_format((float) $row[5], 2); ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php echo number_format((float) $row[6], 2); ?></td>
                                                <td class="lovtd"><?php echo $row[4]; ?></td>
                                                <td class="lovtd"><?php echo $row[8] . " - " . $row[14]; ?></td>
                                                <td class="lovtd"><?php echo $row[10]; ?></td>
                                                <td class="lovtd">                                                     
                                                    <button type="button" class="btn btn-default btn-sm" onclick="getOneMCFGLIntrfcForm(<?php echo $row[11]; ?>, 'allGLIntrfcsHdrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="View Details" style="padding:2px !important;">
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                    <?php if ($row[16] != "SYS" && ((float) $row[9]) <= 0) { ?>  
                                                    <?php } else { ?>
                                                    <?php } ?>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[11] . "|mcf.mcf_gl_interface|interface_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                } else if ($subPgNo == 1.2) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span><span style=\"text-decoration:none;\"> Configuration Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.2');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Currencies</span>
				</li>
                               </ul>
                              </div>";
                    //echo "Currencies";
                    $canAddCrncy = test_prmssns($dfltPrvldgs[175], $mdlNm);
                    $canEdtCrncy = test_prmssns($dfltPrvldgs[176], $mdlNm);
                    $canDelCrncy = test_prmssns($dfltPrvldgs[177], $mdlNm);
                    $total = get_CurrenciesTtl($srchFor, $srchIn, $orgID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_Currencies($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='allCrncysForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php if ($canAddCrncy === true) { ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneCrncyStpForm(-1);" style="width:100% !important;">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Currency
                                        </button>
                                    </div>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-4";
                                $colClassType2 = "col-lg-4";
                                $colClassType3 = "col-lg-4";
                            }
                            ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="allCrncysSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAllCrncys(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                    <input id="allCrncysPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCrncys('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCrncys('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allCrncysSrchIn">
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
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allCrncysDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getAllCrncys('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllCrncys('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>   
                    </div>
                    <div class="row" style="padding:0px 15px 0px 15px !important"> 
                        <div class="col-md-5" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allCrncysTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Currency ISO Code</th>
                                            <th>Description</th>
                                            <th>Mapped LOV Currency</th>
                                            <th>...</th>
                                            <th>&nbsp;</th>                                       
                                            <?php if ($canDelCrncy === true) { ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $usesSQL = "0";
                                        $sbmtdCrncyID = -1;
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($sbmtdCrncyID <= 0 && $cntr <= 0) {
                                                $sbmtdCrncyID = $row[0];
                                            }
                                            $cntr += 1;
                                            ?>
                                            <tr id="allCrncysRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allCrncysRow<?php echo $cntr; ?>_CrncyID" value="<?php echo $row[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="allCrncysRow<?php echo $cntr; ?>_CrncyNm" value="<?php echo $row[2]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <?php echo $row[1]; ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php echo $row[5]; ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($row[3] == "YES") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>   
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <div class="form-check" style="font-size: 12px !important;">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" id="allCrncysRow<?php echo $cntr; ?>_IsEnbld" name="allCrncysRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd . " " . $isRdOnly; ?> >
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneCrncyStpForm(<?php echo $row[0]; ?>);" data-toggle="tooltip" data-placement="bottom" title="View Details">
                                                        <img src="cmn_images/edit32.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canDelCrncy === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delCrncyStp('allCrncysRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Currency">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_currencies|crncy_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                            </fieldset>
                        </div>                        
                        <div  class="col-md-7" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:5px !important;">
                                <div class="" id="allCrncysDetailInfo">
                                    <?php
                                    $srchFor = "%";
                                    $srchIn = "Name";
                                    $pageNo = 1;
                                    $lmtSze = 10;
                                    $vwtyp = 1;
                                    if ($sbmtdCrncyID > 0) {
                                        $result2 = get_CurrencyDenoms($sbmtdCrncyID);
                                        ?>
                                        <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                            <div class="col-md-12" style="padding:0px 1px 0px 1px !important">
                                                <table class="table table-striped table-bordered table-responsive" id="crncyDenomsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>Denomination</th>
                                                            <th>Type</th>
                                                            <th>Value</th>
                                                            <th>Enabled?</th>
                                                            <th>Linked Stock Item</th>
                                                            <th>&nbsp;</th>
                                                            <?php if ($canVwRcHstry === true) { ?>
                                                                <th>...</th>
                                                            <?php } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $cntr = 0;
                                                        while ($row2 = loc_db_fetch_array($result2)) {
                                                            $cntr += 1;
                                                            ?>
                                                            <tr id="crncyDenomsRow_<?php echo $cntr; ?>">                                    
                                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                <td class="lovtd">
                                                                    <span><?php echo $row2[2]; ?></span>
                                                                    <input type="hidden" class="form-control" aria-label="..." id="crncyDenomsRow<?php echo $cntr; ?>_DenomID" value="<?php echo $row2[0]; ?>"> 
                                                                    <input type="hidden" class="form-control" aria-label="..." id="crncyDenomsRow<?php echo $cntr; ?>_DenomNm" value="<?php echo $row2[2]; ?>">                                             
                                                                </td>                                             
                                                                <td class="lovtd">  
                                                                    <span><?php echo $row2[1]; ?></span>
                                                                </td>                                      
                                                                <td class="lovtd">  
                                                                    <span><?php echo number_format((float) $row2[3], 2); ?></span>
                                                                </td>                                           
                                                                <td class="lovtd">
                                                                    <?php
                                                                    $isChkd = "";
                                                                    $isRdOnly = "disabled=\"true\"";
                                                                    if ($row2[4] == "YES") {
                                                                        $isChkd = "checked=\"true\"";
                                                                    }
                                                                    ?>   
                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                        <div class="form-check" style="font-size: 12px !important;">
                                                                            <label class="form-check-label">
                                                                                <input type="checkbox" class="form-check-input" id="crncyDenomsRow<?php echo $cntr; ?>_IsEnbld" name="crncyDenomsRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd . " " . $isRdOnly; ?> >
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>                                         
                                                                <td class="lovtd">  
                                                                    <span><?php echo $row2[6]; ?></span>
                                                                    <input type="hidden" class="form-control" aria-label="..." id="crncyDenomsRow<?php echo $cntr; ?>_LnkdItmID" value="<?php echo $row2[5]; ?>" style="width:100% !important;">                                                                                                              
                                                                </td>
                                                                <td class="lovtd">
                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delDenomStp('crncyDenomsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Person">
                                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                    </button>
                                                                </td>
                                                                <?php if ($canVwRcHstry === true) { ?>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row2[0] . "|mcf.mcf_currency_denominations|crncy_denom_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                                    <?php
                                } else {
                                    ?>
                                    <span>No Results Found</span>
                                    <?php
                                }
                                ?>
                            </fieldset>
                        </div>
                    </div>
                    </form>
                    <?php
                } else if ($subPgNo == 1.3) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span><span style=\"text-decoration:none;\"> Configuration Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.3');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Teller Tills</span>
				</li>
                               </ul>
                              </div>";
                    $canAddCage = test_prmssns($dfltPrvldgs[179], $mdlNm);
                    $canEdtCage = test_prmssns($dfltPrvldgs[180], $mdlNm);
                    $canDelCage = test_prmssns($dfltPrvldgs[181], $mdlNm);
                    //echo "Teller Tills";
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
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsCgForm(-1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Teller Till
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
                                    ?>" onkeyup="enterKeyFuncAllVmsCgs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                    <input id="allVmsCgsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsCgs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsCgs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
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
                                            <a class="rhopagination" href="javascript:getAllVmsCgs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllVmsCgs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Next">
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
                                            <th style="text-align:center;">Enabled?</th>
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
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to View Cage Item Balances" onclick="chckMyTillPos('ShowDialog', 'grp=25&typ=1&pg=1&vtyp=3&isFrmBnkng=1&sbmtdCageID=<?php echo $row[0]; ?>');" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/dashboard220.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                                                <td class="lovtd" style="text-align:center;">
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
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneVmsCgForm(<?php echo $row[0]; ?>);" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canDelCage === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsCg('allVmsCgsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete User">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
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
                } else if ($subPgNo == 1.4) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span><span style=\"text-decoration:none;\"> Configuration Menu</span>
                                    </li>
                                    <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.4');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">Exchange Rates</span>
                                    </li>
                                </ul>
                               </div>";
                    //echo "Exchange Rates";
                    $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                    $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                    $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
                    $canAddExRt = test_prmssns($dfltPrvldgs[183], $mdlNm);
                    $canEdtExRt = test_prmssns($dfltPrvldgs[184], $mdlNm);
                    $canDelExRt = test_prmssns($dfltPrvldgs[185], $mdlNm);
                    $total = get_Total_Rates($srchFor, $srchIn, $qStrtDte, $qEndDte);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_Rates($srchFor, $srchIn, $qStrtDte, $qEndDte, $curIdx, $lmtSze);
                    $cntr = 0;
                    $colClassType1 = "col-lg-4";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-2";
                    ?>
                    <form id='allExchRatesForm' action='' method='post' accept-charset='UTF-8'>                        
                        <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                            <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">                                
                                <div class="input-group">
                                    <input class="form-control" id="allExchRatesSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAllExchRates(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="allExchRatesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllExchRates('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllExchRates('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allExchRatesSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "");
                                        $srchInsArrys = array("Currency From", "Currency To", "Multiply By");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allExchRatesDsplySze" style="min-width:70px !important;">                            
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
                                        <input class="form-control" size="16" type="text" id="allExchRatesStrtDate" name="allExchRatesStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="allExchRatesEndDate" name="allExchRatesEndDate" value="<?php
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
                                            <a class="rhopagination" href="javascript:getAllExchRates('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllExchRates('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                <div class="col-md-4" style="padding:2px 1px 2px 1px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">                                           
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllExchRates('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                            <span style="font-weight:bold;">New Rates Date:</span>
                                        </label>
                                        <input class="form-control" size="16" type="text" id="allExchRatesNewDate" name="allExchRatesNewDate" value="<?php
                                        echo substr($qNwStrtDte, 0, 11);
                                        ?>" placeholder="New Rates Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>   
                                <div class="col-md-8" style="padding:2px 1px 2px 1px !important;">
                                    <?php if ($canAddExRt) { ?>                                                            
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="saveExchRates('', '#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.4&vtyp=0', 5);">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Create Rates
                                        </button>                    
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="saveExchRates('', '#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.4&vtyp=0', 6);">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Auto-Download Rates
                                        </button>                    
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="saveExchRates('', '#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.4&vtyp=0', 4);">
                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Save Rates
                                        </button>
                                    <?php } ?>                           
                                    <?php if ($canDelExRt) { ?>
                                        <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 0px;" onclick="delSlctdExchRates();">
                                            <img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Delete
                                        </button>
                                    <?php } ?> 
                                </div>
                            </div>
                        </div>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allExchRatesTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <?php if ($canDelExRt === TRUE) { ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                            <th>No.</th>
                                            <th>Rate Date</th>
                                            <th style="max-width:75px !important;width:75px !important;">Source Currency</th>
                                            <th>Currency Code Meaning</th>
                                            <th style="max-width:75px !important;width:75px !important;">Destination Currency</th>
                                            <th>Currency Code Meaning</th>
                                            <th style="text-align:right;">Multiply Source Currency by (When Selling)</th>
                                            <th style="text-align:right;">Multiply Destination Currency by (When Buying)</th>
                                            <?php if ($canDelExRt === TRUE) { ?>
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
                                            <tr id="allExchRatesRow_<?php echo $cntr; ?>">
                                                <?php if ($canDelExRt === TRUE) { ?>
                                                    <td class="lovtd">
                                                        <input type="checkbox" name="allExchRatesRow<?php echo $cntr; ?>_CheckBox" value="<?php echo $row[0] . ";" . $row[1]; ?>">
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canEdtExRt === TRUE) {
                                                        ?>
                                                        <input class="form-control" size="16" type="text" id="allExchRatesRow<?php echo $cntr; ?>_RateDate" name="allExchRatesRow<?php echo $cntr; ?>_RateDate" value="<?php echo $row[1]; ?>" readonly="true" style="width:100% !important;">
                                                        <?php
                                                    } else {
                                                        echo $row[1];
                                                    }
                                                    ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allExchRatesRow<?php echo $cntr; ?>_RateID" value="<?php echo $row[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="allExchRatesRow<?php echo $cntr; ?>_FromID" value="<?php echo $row[3]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="allExchRatesRow<?php echo $cntr; ?>_ToID" value="<?php echo $row[6]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canEdtExRt === TRUE) {
                                                        ?>
                                                        <input class="form-control rqrdFld" size="16" type="text" id="allExchRatesRow<?php echo $cntr; ?>_FromCur" name="allExchRatesRow<?php echo $cntr; ?>_FromCur" value="<?php echo $row[2]; ?>" readonly="true" style="width:100% !important;">
                                                        <?php
                                                    } else {
                                                        echo $row[2];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canEdtExRt === TRUE) {
                                                        ?>
                                                        <input class="form-control" size="16" type="text" id="allExchRatesRow<?php echo $cntr; ?>_FromCurMng" name="allExchRatesRow<?php echo $cntr; ?>_FromCurMng" value="<?php echo $row[4]; ?>" readonly="true" style="width:100% !important;">
                                                        <?php
                                                    } else {
                                                        echo $row[4];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canEdtExRt === TRUE) {
                                                        ?>
                                                        <input class="form-control rqrdFld" size="16" type="text" id="allExchRatesRow<?php echo $cntr; ?>_ToCur" name="allExchRatesRow<?php echo $cntr; ?>_ToCur" value="<?php echo $row[5]; ?>" readonly="true" style="width:100% !important;">
                                                        <?php
                                                    } else {
                                                        echo $row[5];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canEdtExRt === TRUE) {
                                                        ?>
                                                        <input class="form-control" size="16" type="text" id="allExchRatesRow<?php echo $cntr; ?>_ToCurMng" name="allExchRatesRow<?php echo $cntr; ?>_ToCurMng" value="<?php echo $row[7]; ?>" readonly="true" style="width:100% !important;">
                                                        <?php
                                                    } else {
                                                        echo $row[7];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;">
                                                    <?php
                                                    if ($canEdtExRt === TRUE) {
                                                        ?>
                                                        <input class="form-control rqrdFld mcfExRate" size="16" type="number" id="allExchRatesRow<?php echo $cntr; ?>_ExRate" name="allExchRatesRow<?php echo $cntr; ?>_ExRate" value="<?php
                                                        echo number_format((float) $row[8], 15);
                                                        ?>" style="width:100% !important;text-align:right;font-weight: bold;color:blue;" onkeypress="mcfCnfExRateKeyPress(event, 'allExchRatesRow_<?php echo $cntr; ?>');">
                                                               <?php
                                                           } else {
                                                               echo number_format((float) $row[8], 15);
                                                           }
                                                           ?>
                                                </td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;">
                                                    <?php
                                                    if ($canEdtExRt === TRUE) {
                                                        ?>
                                                        <input class="form-control rqrdFld mcfExRate" size="16" type="number" id="allExchRatesRow<?php echo $cntr; ?>_ExRate1" name="allExchRatesRow<?php echo $cntr; ?>_ExRate1" value="<?php
                                                        echo number_format((float) $row[10], 15);
                                                        ?>" style="width:100% !important;text-align:right;font-weight: bold;color:blue;" onkeypress="mcfCnfExRateKeyPress(event, 'allExchRatesRow_<?php echo $cntr; ?>');">
                                                               <?php
                                                           } else {
                                                               echo number_format((float) $row[10], 15);
                                                           }
                                                           ?>
                                                </td>
                                                <?php if ($canDelExRt === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delExchRate('allExchRatesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Rate">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_exchange_rates|rate_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                } else if ($subPgNo == 1.5) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span><span style=\"text-decoration:none;\"> Configuration Menu</span>
                                    </li>
                                    <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.5');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">Default Transaction Accounts</span>
                                    </li>
                                </ul>
                              </div>";
                    //echo "Default Transaction Accounts";
                    $canAddDfltAcnt = test_prmssns($dfltPrvldgs[189], $mdlNm);
                    $canEdtDfltAcnt = test_prmssns($dfltPrvldgs[190], $mdlNm);
                    $canDelDfltAcnt = test_prmssns($dfltPrvldgs[191], $mdlNm);
                    $total = get_DfltMCFAccntsTtl($srchFor, $srchIn, $orgID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_DfltMCFAccnts($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                    $cntr = 0;
                    $colClassType1 = "col-lg-4";
                    $colClassType2 = "col-lg-2";
                    $colClassType3 = "col-lg-2";
                    ?>
                    <form id='allDfltAcntsForm' action='' method='post' accept-charset='UTF-8'>                        
                        <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">         
                            <?php
                            if ($canAddDfltAcnt || $canEdtDfltAcnt) {
                                $nwRowHtml = "<tr id=\"allDfltAcntsRow__WWW123WWW\" role=\"row\">
                                                    <td class=\"lovtd\">
                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <span>New</span>
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <select class=\"form-control rqrdFld\" id=\"allDfltAcntsRow_WWW123WWW_SystemType\" onchange=\"\">";
                                $valslctdArry = array("", "", "");
                                $srchInsArrys = array("Money Transfer", "Utility Payments", "Banker's Draft");
                                $valsArrys = array("MONEY_TRANSFER", "UTILITY", "BNKRS_DRAFT");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    $nwRowHtml .= "<option value=\"" . $valsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                }
                                $nwRowHtml .= "</select></div></div>";
                                $nwRowHtml .= "<input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allDfltAcntsRow_WWW123WWW_PkeyID\" value=\"-1\">
                                                </td>
                                                <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                        <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allDfltAcntsRow_WWW123WWW_TypeDesc\" value=\"\">
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\">                                                     
                                                    <div class=\"input-group\">
                                                        <input class=\"form-control\" id=\"allDfltAcntsRow_WWW123WWW_GLAcntNm\" style=\"font-size: 13px !important;font-weight: bold !important;\" placeholder=\"Enter GL Account Number\" type = \"text\" min=\"0\" placeholder=\"\" value=\"\"/>
                                                        <input type=\"hidden\" id=\"allDfltAcntsRow_WWW123WWW_GLAcntID\" value=\"-1\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', '', '', '', 'radio', true, '', 'allDfltAcntsRow_WWW123WWW_GLAcntID', 'allDfltAcntsRow_WWW123WWW_GLAcntNm', 'clear', 1, '', function () {});\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\">
                                                    <div class=\"input-group\">
                                                        <input class=\"form-control\" id=\"allDfltAcntsRow_WWW123WWW_CstmrAcntNm\" style=\"font-size: 13px !important;font-weight: bold !important;\" placeholder=\"Enter Account Number\" type = \"text\" min=\"0\" placeholder=\"\" value=\"\"/>
                                                        <input type=\"hidden\" id=\"allDfltAcntsRow_WWW123WWW_CstmrAcntID\" value=\"\">
                                                        <input type=\"hidden\" id=\"allDfltAcntsRow_WWW123WWW_CstmrAcntRawTxt\" value=\"\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Accounts', '', '', '', 'radio', true, '', 'allDfltAcntsRow_WWW123WWW_CstmrAcntID', 'allDfltAcntsRow_WWW123WWW_CstmrAcntRawTxt', 'clear', 1, '', function () {
                                                                    $('#allDfltAcntsRow_WWW123WWW_CstmrAcntNm').val($('#allDfltAcntsRow_WWW123WWW_CstmrAcntRawTxt').val().split(' [')[0]);
                                                                });\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\" style=\"text-align:center !important;\">
                                                    <div class=\"form-group form-group-sm\">
                                                        <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                            <label class=\"form-check-label\">
                                                                <input type=\"checkbox\" class=\"form-check-input\" id=\"allDfltAcntsRow_WWW123WWW_IsEnbld\" name=\"allDfltAcntsRow_WWW123WWW_IsEnbld\">
                                                            </label>
                                                        </div>
                                                    </div>                                                        
                                                </td>
                                                <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delDfltAcnts('allDfltAcntsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Default Account\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button> 
                                                </td>
                                                <td class=\"lovtd\"> 
                                                   &nbsp;
                                                </td>
                                            </tr>";
                                $nwRowHtml = urlencode($nwRowHtml);
                                ?> 
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                    <?php if ($canAddDfltAcnt) { ?>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allDfltAcntsTable', 0, '<?php echo $nwRowHtml; ?>');">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Default Account
                                        </button>
                                    <?php } ?>
                                    <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 5px;" onclick="saveDfltAcnts('#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.5');">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        SAVE
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-5";
                                $colClassType2 = "col-lg-5";
                                $colClassType3 = "col-lg-2";
                            }
                            ?>    
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;">                                
                                <div class="input-group">
                                    <input class="form-control" id="allDfltAcntsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAllDfltAcnts(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                    <input id="allDfltAcntsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllDfltAcnts('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllDfltAcnts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allDfltAcntsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Transaction Type", "All Fields");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allDfltAcntsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;">                                
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllDfltAcnts('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllDfltAcnts('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allDfltAcntsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th style="max-width:100px;">System Transaction Type</th>
                                            <th style="max-width:150px;">Transaction Sub-Type</th>
                                            <th>Default GL Account</th>
                                            <th>Default Customer Account</th>
                                            <th style="max-width: 75px !important;width: 75px !important;">Enabled?</th>
                                            <th style="max-width: 35px !important;width: 30px !important;">&nbsp;</th>
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
                                            <tr id="allDfltAcntsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtDfltAcnt) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <select class="form-control rqrdFld" id="allDfltAcntsRow<?php echo $cntr; ?>_SystemType" onchange="">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "");
                                                                    $srchInsArrys = array("Money Transfer", "Utility Payments", "Banker's Draft");
                                                                    $valsArrys = array("MONEY_TRANSFER", "UTILITY", "BNKRS_DRAFT");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($row[1] == $valsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $valsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        echo $row[1];
                                                    }
                                                    ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allDfltAcntsRow<?php echo $cntr; ?>_PkeyID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtDfltAcnt) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="allDfltAcntsRow<?php echo $cntr; ?>_TypeDesc" value="<?php echo $row[7]; ?>">
                                                        </div>
                                                        <?php
                                                    } else {
                                                        echo $row[7];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtDfltAcnt) { ?>                                                        
                                                        <div class="input-group">
                                                            <input class="form-control" id="allDfltAcntsRow<?php echo $cntr; ?>_GLAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $row[4]; ?>" readonly="true"/>
                                                            <input type="hidden" id="allDfltAcntsRow<?php echo $cntr; ?>_GLAcntID" value="<?php echo $row[3]; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', '', '', '', 'radio', true, '', 'allDfltAcntsRow<?php echo $cntr; ?>_GLAcntID', 'allDfltAcntsRow<?php echo $cntr; ?>_GLAcntNm', 'clear', 1, '', function () {});">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        echo $row[4];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtDfltAcnt) {
                                                        ?>
                                                        <div class="input-group">
                                                            <input class="form-control" id="allDfltAcntsRow<?php echo $cntr; ?>_CstmrAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter Account Number" type = "text" min="0" placeholder="" value="<?php echo $row[5]; ?>" readonly="true"/>
                                                            <input type="hidden" id="allDfltAcntsRow<?php echo $cntr; ?>_CstmrAcntID" value="<?php echo $row[2]; ?>">
                                                            <input type="hidden" id="allDfltAcntsRow<?php echo $cntr; ?>_CstmrAcntRawTxt" value="<?php echo ''; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Accounts', '', '', '', 'radio', true, '', 'allDfltAcntsRow<?php echo $cntr; ?>_CstmrAcntID', 'allDfltAcntsRow<?php echo $cntr; ?>_CstmrAcntRawTxt', 'clear', 1, '', function () {
                                                                                                    $('#allDfltAcntsRow<?php echo $cntr; ?>_CstmrAcntNm').val($('#allDfltAcntsRow<?php echo $cntr; ?>_CstmrAcntRawTxt').val().split(' [')[0]);
                                                                                                });">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        echo $row[5];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="lovtd" style="text-align:center !important;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($row[8] == "1") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    if ($canEdtDfltAcnt === true) {
                                                        ?>
                                                        <div class="form-group form-group-sm">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="allDfltAcntsRow<?php echo $cntr; ?>_IsEnbld" name="allDfltAcntsRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo ($row[8] == "1" ? "Yes" : "No"); ?></span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">    
                                                    <?php if ($canDelDfltAcnt === true) { ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delDfltAcnts('allDfltAcntsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Default Account">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } else { ?>
                                                        &nbsp;
                                                    <?php } ?>  
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_trans_dflt_accnts|dflt_accnt_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                } else if ($subPgNo == 1.6) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span><span style=\"text-decoration:none;\"> Configuration Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.6');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">All Banks and Branches</span>
				</li>
                               </ul>
                              </div>";
                    $canAddBnk = test_prmssns($dfltPrvldgs[193], $mdlNm);
                    $canEdtBnk = test_prmssns($dfltPrvldgs[194], $mdlNm);
                    $canDelBnk = test_prmssns($dfltPrvldgs[195], $mdlNm);
                    $total = get_AllBanksTtl($srchFor, $srchIn, $orgID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_AllBanks($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <form id='allBnksForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php if ($canAddBnk === true) { ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneBankStpForm(-1);" style="width:100% !important;">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Bank
                                        </button>
                                    </div>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-4";
                                $colClassType2 = "col-lg-4";
                                $colClassType3 = "col-lg-4";
                            }
                            ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="allBnksSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAllBnks(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                    <input id="allBnksPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBnks('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBnks('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allBnksSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Bank Name", "Contact Information");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allBnksDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getAllBnks('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllBnks('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>  
                        <div class="row" style="padding:0px 15px 0px 15px !important"> 
                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important">
                                <fieldset class="basic_person_fs">                                        
                                    <table class="table table-striped table-bordered table-responsive" id="allBnksTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Bank Code</th>
                                                <th>Bank Name</th>                                   
                                                <?php if ($canEdtBnk === true) { ?>
                                                    <th>&nbsp;</th>
                                                <?php } ?>                                   
                                                <?php if ($canDelBnk === true) { ?>
                                                    <th>&nbsp;</th>
                                                <?php } ?>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th>...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sbmtdBankID = -1;
                                            while ($row = loc_db_fetch_array($result)) {
                                                if ($sbmtdBankID <= 0 && $cntr <= 0) {
                                                    $sbmtdBankID = $row[0];
                                                }
                                                $cntr += 1;
                                                ?>
                                                <tr id="allBnksRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <?php echo $row[2]; ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="allBnksRow<?php echo $cntr; ?>_BankID" value="<?php echo $row[0]; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="allBnksRow<?php echo $cntr; ?>_BankNm" value="<?php echo $row[1]; ?>">
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php echo $row[1]; ?>
                                                    </td>
                                                    <?php if ($canEdtBnk === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneBankStpForm(<?php echo $row[0]; ?>)" data-toggle="tooltip" data-placement="bottom" title="Edit Bank">
                                                                <img src="cmn_images/edit32.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($canDelBnk === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delBankStp('allBnksRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Bank">
                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_all_banks|bank_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                                </fieldset>
                            </div>                        
                            <div  class="col-md-8" style="padding:0px 1px 0px 1px !important">
                                <fieldset class="basic_person_fs" style="padding-top:5px !important;">
                                    <div class="" id="allBnksDetailInfo">
                                        <div class="row" id="allBnksHdrInfo" style="padding:0px 15px 0px 15px !important">
                                            <?php
                                            $result1 = get_OneBankDet($sbmtdBankID);
                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                ?>
                                                <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;"> 
                                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="oneBnkDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Bank Code:</label>
                                                            <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                                <span><?php echo $row1[1]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="oneBnkDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Bank Name:</label>
                                                            <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                                <span><?php echo $row1[2]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="oneBnkDetCntct" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Contact Nos:</label>
                                                            <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                                <span><?php echo $row1[5]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="oneBnkDetEmail" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Email:</label>
                                                            <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                                <span><?php echo $row1[6]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="oneBnkDetISOCntryCode" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">ISO Country Code:</label>
                                                            <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                                <span><?php echo $row1[13]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="oneBnkDetChkDgts" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Check Digits:</label>
                                                            <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                                <span><?php echo $row1[14]; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">         
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="oneBnkDetSwiftCode" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Swift Code:</label>
                                                            <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                                <span><?php echo $row1[15]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="oneBnkDetFax" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Fax Nos:</label>
                                                            <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                                <span><?php echo $row1[7]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="oneBnkDetPstl" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Postal Address:</label>
                                                            <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                                <span><?php echo $row1[4]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="oneBnkDetRes" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Residential Address:</label>
                                                            <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                                <span><?php echo $row1[3]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <div  class="col-lg-4" style="padding:0px 15px 0px 1px !important;">&nbsp;</div>
                                                            <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                                <div class="checkbox">
                                                                    <label for="isBnkEnbld" class="control-label">
                                                                        <?php
                                                                        $isChkd = "";
                                                                        $isRdOnly = "disabled=\"true\"";
                                                                        if ($row1[16] == "1") {
                                                                            $isChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" name="isBnkEnbld" id="isBnkEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            <?php } ?>
                                        </div>
                                        <div class="row" id="allBnkBrnchsDetailInfo" style="padding:0px 15px 0px 15px !important">
                                            <?php
                                            /* &vtyp=<?php echo $vwtyp; ?> */
                                            $srchFor = "%";
                                            $srchIn = "Name";
                                            $pageNo = 1;
                                            $lmtSze = 10;
                                            $vwtyp = 1;
                                            if ($sbmtdBankID > 0) {
                                                $total = get_AllBankBrnchsTtl($srchFor, $srchIn, $sbmtdBankID);
                                                if ($pageNo > ceil($total / $lmtSze)) {
                                                    $pageNo = 1;
                                                } else if ($pageNo < 1) {
                                                    $pageNo = ceil($total / $lmtSze);
                                                }
                                                $curIdx = $pageNo - 1;
                                                $result2 = get_AllBankBrnchs($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdBankID);
                                                ?>
                                                <div class="row" style="padding:0px 15px 0px 15px !important">
                                                    <?php
                                                    $colClassType1 = "col-lg-4";
                                                    $colClassType2 = "col-lg-4";
                                                    $colClassType3 = "col-lg-4";
                                                    ?>
                                                    <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="input-group">
                                                            <input class="form-control" id="allBnkBrnchsSrchFor" type = "text" placeholder="Search For" value="<?php
                                                            echo trim(str_replace("%", " ", $srchFor));
                                                            ?>" onkeyup="enterKeyFuncAllBnkBrnchs(event, '', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');">
                                                            <input id="allBnkBrnchsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBnkBrnchs('clear', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </label>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBnkBrnchs('', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');">
                                                                <span class="glyphicon glyphicon-search"></span>
                                                            </label> 
                                                        </div>
                                                    </div>
                                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                            <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allBnkBrnchsSrchIn">
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
                                                            </select>-->
                                                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="allBnkBrnchsDsplySze" style="min-width:70px !important;">                            
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
                                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                        <nav aria-label="Page navigation">
                                                            <ul class="pagination" style="margin: 0px !important;">
                                                                <li>
                                                                    <a class="rhopagination" href="javascript:getAllBnkBrnchs('previous', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');" aria-label="Previous">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="rhopagination" href="javascript:getAllBnkBrnchs('next', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');" aria-label="Next">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </nav>
                                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdBankID" name="sbmtdBankID" value="<?php echo $sbmtdBankID; ?>">
                                                    </div>
                                                </div>
                                                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                                        <table class="table table-striped table-bordered table-responsive" id="allBnkBrnchsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Branch Code</th>
                                                                    <th>Branch Name</th>
                                                                    <th>Residential Address</th>
                                                                    <th>Contact Nos.</th>
                                                                    <th>SWIFT CODE</th>
                                                                    <th>&nbsp;</th>
                                                                    <?php if ($canVwRcHstry === true) { ?>
                                                                        <th>...</th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $cntr = 0;
                                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="allBnkBrnchsRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                        <td class="lovtd">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsRow<?php echo $cntr; ?>_BankID" value="<?php echo $row2[1]; ?>" readonly="true">
                                                                            <span><?php echo $row2[3]; ?></span>
                                                                            <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsRow<?php echo $cntr; ?>_BranchID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsRow<?php echo $cntr; ?>_BranchNm" value="<?php echo $row2[2]; ?>" style="width:100% !important;">                                                 
                                                                        </td>                                             
                                                                        <td class="lovtd">  
                                                                            <span><?php echo $row2[2]; ?></span>
                                                                        </td>                                           
                                                                        <td class="lovtd">  
                                                                            <span><?php echo $row2[4]; ?></span>
                                                                        </td>                                            
                                                                        <td class="lovtd">  
                                                                            <span><?php echo $row2[6]; ?></span>
                                                                        </td>                                            
                                                                        <td class="lovtd">  
                                                                            <span><?php echo $row2[13]; ?></span>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delBankBrnchStp('allBnkBrnchsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Branch">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <?php if ($canVwRcHstry === true) { ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row2[0] . "|mcf.mcf_bank_branches|branch_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                                                <?php
                                            } else {
                                                ?>
                                                <span>No Results Found</span>
                                                <?php
                                            }
                                            ?> 
                                        </div>                                   
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                    <?php
                } else if ($subPgNo == 1.61) {
                    $sbmtdBankID = isset($_POST['sbmtdBankID']) ? cleanInputData($_POST['sbmtdBankID']) : -1;
                    $canAddBnk = test_prmssns($dfltPrvldgs[193], $mdlNm);
                    $canEdtBnk = test_prmssns($dfltPrvldgs[194], $mdlNm);
                    $canDelBnk = test_prmssns($dfltPrvldgs[195], $mdlNm);
                    ?>
                    <div class="row" id="allBnksHdrInfo" style="padding:0px 15px 0px 15px !important">
                        <?php
                        $result1 = get_OneBankDet($sbmtdBankID);
                        while ($row1 = loc_db_fetch_array($result1)) {
                            ?>
                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;"> 
                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Bank Code:</label>
                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <span><?php echo $row1[1]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Bank Name:</label>
                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <span><?php echo $row1[2]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetCntct" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Contact Nos:</label>
                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <span><?php echo $row1[5]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetEmail" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Email:</label>
                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <span><?php echo $row1[6]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetISOCntryCode" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">ISO Country Code:</label>
                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <span><?php echo $row1[13]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetChkDgts" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Check Digits:</label>
                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <span><?php echo $row1[14]; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">            
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetSwiftCode" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Swift Code:</label>
                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <span><?php echo $row1[15]; ?></span>
                                        </div>
                                    </div>                                                 
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetFax" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Fax Nos:</label>
                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <span><?php echo $row1[7]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetPstl" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Postal Address:</label>
                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <span><?php echo $row1[4]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetRes" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Residential Address:</label>
                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <span><?php echo $row1[3]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <div  class="col-lg-4" style="padding:0px 15px 0px 1px !important;">&nbsp;</div>
                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <div class="checkbox">
                                                <label for="isBnkEnbld" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($row1[16] == "1") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isBnkEnbld" id="isBnkEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <?php } ?>
                    </div>
                    <div class="row" id="allBnkBrnchsDetailInfo" style="padding:0px 15px 0px 15px !important">
                        <?php
                        $srchFor = "%";
                        $srchIn = "Name";
                        $pageNo = 1;
                        $lmtSze = 10;
                        $vwtyp = 1;
                        if ($sbmtdBankID > 0) {
                            $total = get_AllBankBrnchsTtl($srchFor, $srchIn, $sbmtdBankID);
                            if ($pageNo > ceil($total / $lmtSze)) {
                                $pageNo = 1;
                            } else if ($pageNo < 1) {
                                $pageNo = ceil($total / $lmtSze);
                            }
                            $curIdx = $pageNo - 1;
                            $result2 = get_AllBankBrnchs($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdBankID);
                            ?>
                            <div class="row" style="padding:0px 15px 0px 15px !important">
                                <?php
                                $colClassType1 = "col-lg-4";
                                $colClassType2 = "col-lg-4";
                                $colClassType3 = "col-lg-4";
                                ?>
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="allBnkBrnchsSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncAllBnkBrnchs(event, '', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');">
                                        <input id="allBnkBrnchsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBnkBrnchs('clear', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBnkBrnchs('', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allBnkBrnchsSrchIn">
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
                                        </select>-->
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="allBnkBrnchsDsplySze" style="min-width:70px !important;">                            
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
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" style="margin: 0px !important;">
                                            <li>
                                                <a class="rhopagination" href="javascript:getAllBnkBrnchs('previous', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="rhopagination" href="javascript:getAllBnkBrnchs('next', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdBankID" name="sbmtdBankID" value="<?php echo $sbmtdBankID; ?>">
                                </div>
                            </div>
                            <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                    <table class="table table-striped table-bordered table-responsive" id="allBnkBrnchsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Branch Code</th>
                                                <th>Branch Name</th>
                                                <th>Residential Address</th>
                                                <th>Contact Nos.</th>
                                                <th>SWIFT CODE</th>
                                                <th>&nbsp;</th>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th>...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cntr = 0;
                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="allBnkBrnchsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                    <td class="lovtd">
                                                        <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsRow<?php echo $cntr; ?>_BankID" value="<?php echo $row2[1]; ?>" readonly="true">
                                                        <span><?php echo $row2[3]; ?></span>
                                                        <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsRow<?php echo $cntr; ?>_BranchID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">  
                                                        <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsRow<?php echo $cntr; ?>_BranchNm" value="<?php echo $row2[2]; ?>" style="width:100% !important;">                                             
                                                    </td>                                             
                                                    <td class="lovtd">  
                                                        <span><?php echo $row2[2]; ?></span>
                                                    </td>                                           
                                                    <td class="lovtd">  
                                                        <span><?php echo $row2[4]; ?></span>
                                                    </td>                                            
                                                    <td class="lovtd">  
                                                        <span><?php echo $row2[6]; ?></span>
                                                    </td>                                            
                                                    <td class="lovtd">  
                                                        <span><?php echo $row2[13]; ?></span>
                                                    </td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delBankBrnchStp('allBnkBrnchsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Branch">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row2[0] . "|mcf.mcf_bank_branches|branch_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                            <?php
                        } else {
                            ?>
                            <span>No Results Found</span>
                            <?php
                        }
                        ?>
                    </div>                        
                    <?php
                } else if ($subPgNo == 1.62) {
                    $sbmtdBankID = isset($_POST['sbmtdBankID']) ? cleanInputData($_POST['sbmtdBankID']) : -1;
                    $canAddBnk = test_prmssns($dfltPrvldgs[197], $mdlNm);
                    $canEdtBnk = test_prmssns($dfltPrvldgs[198], $mdlNm);
                    $canDelBnk = test_prmssns($dfltPrvldgs[199], $mdlNm);
                    if ($sbmtdBankID > 0) {
                        $total = get_AllBankBrnchsTtl($srchFor, $srchIn, $sbmtdBankID);
                        if ($pageNo > ceil($total / $lmtSze)) {
                            $pageNo = 1;
                        } else if ($pageNo < 1) {
                            $pageNo = ceil($total / $lmtSze);
                        }
                        $curIdx = $pageNo - 1;
                        $result2 = get_AllBankBrnchs($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdBankID);
                        ?>
                        <div class="row" style="padding:0px 15px 0px 15px !important">
                            <?php
                            $colClassType1 = "col-lg-4";
                            $colClassType2 = "col-lg-4";
                            $colClassType3 = "col-lg-4";
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="allBnkBrnchsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAllBnkBrnchs(event, '', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');">
                                    <input id="allBnkBrnchsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBnkBrnchs('clear', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBnkBrnchs('', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allBnkBrnchsSrchIn">
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
                                    </select>-->
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allBnkBrnchsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllBnkBrnchs('previous', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllBnkBrnchs('next', '#allBnkBrnchsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.62"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                                <input type="hidden" class="form-control" aria-label="..." id="sbmtdBankID" name="sbmtdBankID" value="<?php echo $sbmtdBankID; ?>">
                            </div>
                        </div>
                        <div class="row" style="padding:0px 15px 0px 15px !important">                  
                            <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                <table class="table table-striped table-bordered table-responsive" id="allBnkBrnchsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Branch Code</th>
                                            <th>Branch Name</th>
                                            <th>Residential Address</th>
                                            <th>Contact Nos.</th>
                                            <th>SWIFT CODE</th>
                                            <th>&nbsp;</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row2 = loc_db_fetch_array($result2)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="allBnkBrnchsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsRow<?php echo $cntr; ?>_BankID" value="<?php echo $row2[1]; ?>" readonly="true">
                                                    <span><?php echo $row2[3]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsRow<?php echo $cntr; ?>_BranchID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                    <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsRow<?php echo $cntr; ?>_BranchNm" value="<?php echo $row2[2]; ?>" style="width:100% !important;">                                              
                                                </td>                                             
                                                <td class="lovtd">  
                                                    <span><?php echo $row2[2]; ?></span>
                                                </td>                                          
                                                <td class="lovtd">  
                                                    <span><?php echo $row2[4]; ?></span>
                                                </td>                                            
                                                <td class="lovtd">  
                                                    <span><?php echo $row2[6]; ?></span>
                                                </td>                                            
                                                <td class="lovtd">  
                                                    <span><?php echo $row2[13]; ?></span>
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delBankBrnchStp('allBnkBrnchsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Branch">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row2[0] . "|mcf.mcf_bank_branches|branch_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                        <?php
                    } else {
                        ?>
                        <span>No Results Found</span>
                        <?php
                    }
                } else if ($subPgNo == 1.63) {
                    /* &vtyp=<?php echo $vwtyp; ?> */
                    $canEdtBnk = test_prmssns($dfltPrvldgs[198], $mdlNm);
                    $sbmtdBankID = isset($_POST['sbmtdBankID']) ? cleanInputData($_POST['sbmtdBankID']) : -1;
                    if ($sbmtdBankID > 0) {
                        $total = get_AllBankBrnchsTtl($srchFor, $srchIn, $sbmtdBankID);
                        if ($pageNo > ceil($total / $lmtSze)) {
                            $pageNo = 1;
                        } else if ($pageNo < 1) {
                            $pageNo = ceil($total / $lmtSze);
                        }
                        $curIdx = $pageNo - 1;
                        $result2 = get_AllBankBrnchs($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdBankID);
                        ?>
                        <div class="row" style="padding:0px 15px 0px 15px !important">
                            <?php
                            if ($canEdtBnk === true) {
                                $colClassType1 = "col-lg-5";
                                $colClassType2 = "col-lg-3";
                                $colClassType3 = "col-lg-2";
                                $nwRowHtml = "<tr id=\"allBnkBrnchsStpRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allBnkBrnchsStpRow_WWW123WWW_BankID\" value=\"$sbmtdBankID\" readonly=\"true\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allBnkBrnchsStpRow_WWW123WWW_BranchID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                                                                 
                                                            <input type=\"text\" name=\"allBnkBrnchsStpRow_WWW123WWW_BrnchCode\" id=\"allBnkBrnchsStpRow_WWW123WWW_BrnchCode\" class=\"form-control rqrdFld\" value=\"\" style=\"width:100% !important;\" style=\"width:100% !important;\">
                                                        </td>                                             
                                                        <td class=\"lovtd\">
                                                                <input type=\"text\" name=\"allBnkBrnchsStpRow_WWW123WWW_BranchNm\" id=\"allBnkBrnchsStpRow_WWW123WWW_BranchNm\" class=\"form-control rqrdFld\" value=\"\" style=\"width:100% !important;\" style=\"width:100% !important;\"> 
                                                        </td>                                            
                                                        <td class=\"lovtd\"> 
                                                                <textarea name=\"allBnkBrnchsStpRow_WWW123WWW_PstlAdrs\" id=\"allBnkBrnchsStpRow_WWW123WWW_PstlAdrs\" class=\"form-control\" style=\"width:100% !important;\"></textarea> 
                                                        </td>                                            
                                                        <td class=\"lovtd\">     
                                                                <textarea name=\"allBnkBrnchsStpRow_WWW123WWW_ResAdrs\" id=\"allBnkBrnchsStpRow_WWW123WWW_ResAdrs\" class=\"form-control\" style=\"width:100% !important;\"></textarea>  
                                                        </td>                                            
                                                        <td class=\"lovtd\">  
                                                                <input type=\"text\" name=\"allBnkBrnchsStpRow_WWW123WWW_CntctNos\" id=\"allBnkBrnchsStpRow_WWW123WWW_CntctNos\" class=\"form-control\" value=\"\" style=\"width:100% !important;\" style=\"width:100% !important;\"> 
                                                        </td>                                            
                                                        <td class=\"lovtd\"> 
                                                                <input type=\"text\" name=\"allBnkBrnchsStpRow_WWW123WWW_Swft\" id=\"allBnkBrnchsStpRow_WWW123WWW_Swft\" class=\"form-control\" value=\"\" style=\"width:100% !important;\" style=\"width:100% !important;\"> 
                                                        </td>                                           
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"allBnkBrnchsStpRow_WWW123WWW_IsEnbld\" name=\"allBnkBrnchsStpRow_WWW123WWW_IsEnbld\">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delBankBrnchStp('allBnkBrnchsStpRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Denomination\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                        <td class=\"lovtd\"> 
                                                           &nbsp;
                                                        </td>
                                                      </tr>";
                                $nwRowHtml = urlencode($nwRowHtml);
                                ?> 
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allBnkBrnchsStpTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Bank Branch">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> New Branch
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveBankStpForm('', '#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.6');" data-toggle="tooltip" data-placement="bottom" title="Save Bank Details">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Save Bank Details
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-4";
                                $colClassType2 = "col-lg-4";
                                $colClassType3 = "col-lg-4";
                            }
                            ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="allBnkBrnchsStpSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAllBnkBrnchs(event, '', '#allBnkBrnchsStpDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.63"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>', 1);">
                                    <input id="allBnkBrnchsStpPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBnkBrnchs('clear', '#allBnkBrnchsStpDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.63"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>', 1);">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBnkBrnchs('', '#allBnkBrnchsStpDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.63"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>', 1);">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allBnkBrnchsSrchIn">
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
                                    </select>-->
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allBnkBrnchsStpDsplySze" style="min-width:70px !important;">                            
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
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllBnkBrnchs('previous', '#allBnkBrnchsStpDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.63"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>',1);" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllBnkBrnchs('next', '#allBnkBrnchsStpDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.63"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>',1);" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                                <input type="hidden" class="form-control" aria-label="..." id="sbmtdBankID" name="sbmtdBankID" value="<?php echo $sbmtdBankID; ?>">
                            </div>
                        </div>
                        <div class="row" style="padding:0px 15px 0px 15px !important">                  
                            <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                <table class="table table-striped table-bordered table-responsive" id="allBnkBrnchsStpTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Branch Code</th>
                                            <th>Branch Name</th>
                                            <th>Postal Address</th>
                                            <th>Residential Address</th>
                                            <th>Contact Nos.</th>
                                            <th>SWIFT CODE</th>
                                            <th style="text-align: center;">Enabled?</th>
                                            <th>&nbsp;</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row2 = loc_db_fetch_array($result2)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="allBnkBrnchsStpRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsStpRow<?php echo $cntr; ?>_BankID" value="<?php echo $row2[1]; ?>" readonly="true">
                                                    <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsStpRow<?php echo $cntr; ?>_BranchID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                                                                                                                                                 
                                                    <?php if ($canEdtBnk === true) { ?>
                                                        <input type="text" name="allBnkBrnchsStpRow<?php echo $cntr; ?>_BrnchCode" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_BrnchCode" class="form-control rqrdFld" value="<?php
                                                        echo $row2[3];
                                                        ?>" style="width:100% !important;" style="width:100% !important;">                                                                
                                                           <?php } else { ?>
                                                        <span><?php echo $row2[3]; ?></span>
                                                    <?php } ?> 
                                                </td>                                             
                                                <td class="lovtd">  
                                                    <?php if ($canEdtBnk === true) { ?>
                                                        <input type="text" name="allBnkBrnchsStpRow<?php echo $cntr; ?>_BranchNm" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_BranchNm" class="form-control rqrdFld" value="<?php
                                                        echo $row2[2];
                                                        ?>" style="width:100% !important;" style="width:100% !important;">                                                                
                                                           <?php } else { ?>
                                                        <span><?php echo $row2[2]; ?></span> 
                                                        <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsStpRow<?php echo $cntr; ?>_BranchNm" value="<?php echo $row2[2]; ?>" style="width:100% !important;">
                                                    <?php } ?> 
                                                </td>                                            
                                                <td class="lovtd">                                                                
                                                    <?php if ($canEdtBnk === true) { ?>
                                                        <textarea name="allBnkBrnchsStpRow<?php echo $cntr; ?>_PstlAdrs" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_PstlAdrs" class="form-control" style="width:100% !important;"><?php
                                                            echo $row2[5];
                                                            ?></textarea>                                                               
                                                    <?php } else { ?>
                                                        <span><?php echo $row2[5]; ?></span>
                                                    <?php } ?>
                                                </td>                                            
                                                <td class="lovtd">                                                                
                                                    <?php if ($canEdtBnk === true) { ?>
                                                        <textarea name="allBnkBrnchsStpRow<?php echo $cntr; ?>_ResAdrs" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_ResAdrs" class="form-control" style="width:100% !important;"><?php
                                                            echo $row2[4];
                                                            ?>
                                                        </textarea>                                                               
                                                    <?php } else { ?>
                                                        <span><?php echo $row2[4]; ?></span>
                                                    <?php } ?>
                                                </td>                                            
                                                <td class="lovtd">  
                                                    <?php if ($canEdtBnk === true) { ?>
                                                        <input type="text" name="allBnkBrnchsStpRow<?php echo $cntr; ?>_CntctNos" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_CntctNos" class="form-control" value="<?php
                                                        echo $row2[6];
                                                        ?>" style="width:100% !important;" style="width:100% !important;">                                                                
                                                           <?php } else { ?>
                                                        <span><?php echo $row2[6]; ?></span>
                                                    <?php } ?> 
                                                </td>                                            
                                                <td class="lovtd">  
                                                    <?php if ($canEdtBnk === true) { ?>
                                                        <input type="text" name="allBnkBrnchsStpRow<?php echo $cntr; ?>_Swft" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_Swft" class="form-control" value="<?php
                                                        echo $row2[13];
                                                        ?>" style="width:100% !important;" style="width:100% !important;">                                                                
                                                           <?php } else { ?>
                                                        <span><?php echo $row2[13]; ?></span>
                                                    <?php } ?> 
                                                </td>                                           
                                                <td class="lovtd" style="text-align: center;">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($row2[14] == "1") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    if ($canEdtBnk === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    ?>   
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <div class="form-check" style="font-size: 12px !important;">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_IsEnbld" name="allBnkBrnchsStpRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd . " " . $isRdOnly; ?> >
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delBankBrnchStp('allBnkBrnchsStpRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Branch">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row2[0] . "|mcf.mcf_bank_branches|branch_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                        <?php
                    } else {
                        ?>
                        <span>No Results Found</span>
                        <?php
                    }
                } else if ($subPgNo == 1.7) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span><span style=\"text-decoration:none;\"> Configuration Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.7');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Close of Business Processes</span>
				</li>
                               </ul>
                              </div>";
                    $canRunEOD = test_prmssns($dfltPrvldgs[203], $mdlNm);
                    $total = get_EODsTtl($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_EODs($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte);
                    $cntr = 0;
                    $colClassType1 = "col-lg-4";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-2";
                    ?>
                    <form id='allEODPrcsForm' action='' method='post' accept-charset='UTF-8'>                        
                        <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                            <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">                                
                                <div class="input-group">
                                    <input class="form-control" id="allEODPrcsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAllEODPrcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                    <input id="allEODPrcsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllEODPrcs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllEODPrcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allEODPrcsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("EOD Record ID", "All Fields");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allEODPrcsDsplySze" style="min-width:70px !important;">                            
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
                                        <input class="form-control" size="16" type="text" id="allEODPrcsStrtDate" name="allEODPrcsStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="allEODPrcsEndDate" name="allEODPrcsEndDate" value="<?php
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
                                            <a class="rhopagination" href="javascript:getAllEODPrcs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllEODPrcs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                <div class="col-md-4" style="padding:2px 1px 2px 1px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">                                           
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllEODPrcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');">
                                            <span style="font-weight:bold;">Next Start of Day Date:</span>
                                        </label>
                                        <input class="form-control" size="16" type="text" id="allEODPrcsNewDate" name="allEODPrcsNewDate" value="<?php
                                        echo substr($gnrlTrnsDteDMYHMS, 0, 11);
                                        ?>" placeholder="Next Start of Day Date" readonly="true">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>   
                                <div class="col-md-8" style="padding:2px 1px 2px 1px !important;">
                                    <?php
                                    if ($canRunEOD) {
                                        $reportTitle = "Automatic Database Backup-Linux";
                                        $reportName = "Automatic Database Backup-Linux";
                                        $rptID = getRptID($reportName);
                                        $prmID1 = getParamIDUseSQLRep("{:backup_dir}", $rptID);
                                        $prmID2 = getParamIDUseSQLRep("{:dbase_name}", $rptID);
                                        $bckpDir = $ftp_base_db_fldr . "/DB_Backups";
                                        $pDbNm = $database;
                                        $paramRepsNVals = $prmID1 . "~" . $bckpDir . "|" . $prmID2 . "~" . $pDbNm . "|-130~" . $reportTitle . "|-190~PDF";
                                        $paramStr = urlencode($paramRepsNVals);

                                        $reportTitle1 = "End of Day Process Runs";
                                        $reportName1 = "End of Day Process Runs";
                                        $rptID1 = getRptID($reportName1);
                                        $paramRepsNVals1 = "-130~" . $reportTitle1 . "|-190~PDF";
                                        $paramStr1 = urlencode($paramRepsNVals1);

                                        $reportTitle2 = "End of Month Process Runs";
                                        $reportName2 = "End of Month Process Runs";
                                        $rptID2 = getRptID($reportName2);
                                        $paramRepsNVals2 = "-130~" . $reportTitle2 . "|-190~PDF";
                                        $paramStr2 = urlencode($paramRepsNVals2);

                                        $reportTitle3 = "End of Year Process Runs";
                                        $reportName3 = "End of Year Process Runs";
                                        $rptID3 = getRptID($reportName3);
                                        $paramRepsNVals3 = "-130~" . $reportTitle3 . "|-190~PDF";
                                        $paramStr3 = urlencode($paramRepsNVals3);
                                        /* getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');--> */
                                        ?>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID; ?>');">
                                            <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" data-placement="bottom" title="Take Full Backup">
                                            Backup
                                        </button>                    
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID1; ?>');">
                                            <img src="cmn_images/appoint.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" data-placement="bottom" title="Run End of Day">
                                            End of Day
                                        </button>                    
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID2; ?>');">
                                            <img src="cmn_images/calendar2.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" data-placement="bottom" title="Run End of Month">
                                            End of Month
                                        </button>                   
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID3; ?>');">
                                            <img src="cmn_images/Calander.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" data-placement="bottom" title="Run End of Year">
                                            End of Year
                                        </button>
                                    <?php } ?> 
                                </div>
                            </div>
                        </div>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allEODPrcsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>EOD Record ID</th>
                                            <th>Last Time Active</th>
                                            <th>Current Start of Day Date</th>
                                            <th>Next Start of Day Date</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                            <th>Log Files</th>
                                            <th>Run By</th>
                                            <th>Run Source</th>
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
                                            <tr id="allEODPrcsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd">
                                                    <?php
                                                    echo $row[0] . " [" . $row[1] . "]";
                                                    ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allEODPrcsRow<?php echo $cntr; ?>_EODRecID" value="<?php echo $row[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="allEODPrcsRow<?php echo $cntr; ?>_EODRunID" value="<?php echo $row[1]; ?>">
                                                </td>
                                                <td class="lovtd"><?php echo $row[3]; ?></td>
                                                <td class="lovtd"><?php echo $row[6]; ?></td>
                                                <td class="lovtd"><?php echo $row[9]; ?></td>
                                                <td class="lovtd"><?php echo $row[4]; ?></td>
                                                <td class="lovtd"><?php echo $row[7]; ?></td>
                                                <td class="lovtd"><?php echo "Log Files"; ?></td>
                                                <td class="lovtd"><?php echo "Run By"; ?></td>
                                                <td class="lovtd"><?php echo "Run Source"; ?></td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_cob_trns_records|cob_record_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                } else if ($subPgNo == 1.8) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span><span style=\"text-decoration:none;\"> Configuration Menu</span>
                                    </li>
                                    <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.8');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">Default Global Variables</span>
                                    </li>
                                </ul>
                              </div>";
                    //echo "Default Transaction Accounts";
                    $canAddDfltGlobalVar = test_prmssns($dfltPrvldgs[257], $mdlNm);
                    $canEdtDfltGlobalVar = test_prmssns($dfltPrvldgs[258], $mdlNm);
                    $total = get_DfltMCFGlobalVarsTtl($srchFor, $srchIn, $orgID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_DfltMCFGlobalVars($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                    $cntr = 0;
                    $colClassType1 = "col-lg-3";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-3";
                    ?>
                    <form id='allDfltGlobalVarsForm' action='' method='post' accept-charset='UTF-8'>                        
                        <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">         
                            <?php
                            if ($canAddDfltGlobalVar || $canEdtDfltGlobalVar) {
                                ?> 
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                    <?php if ($canAddDfltGlobalVar) { ?>
                                        <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 5px;" onclick="saveDfltGlobalVars('#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.8');">
                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            SAVE
                                        </button>
                                    <?php } ?>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-5";
                                $colClassType2 = "col-lg-5";
                                $colClassType3 = "col-lg-2";
                            }
                            ?>    
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;">                                
                                <div class="input-group">
                                    <input class="form-control" id="allDfltGlobalVarsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAllDfltGlobalVars(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                    <input id="allDfltGlobalVarsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllDfltGlobalVars('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllDfltGlobalVars('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allDfltGlobalVarsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Variable Name", "All Fields");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allDfltGlobalVarsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;">                                
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllDfltGlobalVars('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllDfltGlobalVars('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allDfltGlobalVarsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th >Variable Name</th>
                                            <th >Value</th>
                                            <th>Data Type</th>
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
                                            <tr id="allDfltGlobalVarsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd">
                                                    <?php echo $row[1];
                                                    ?>
                                                </td>												
                                                <td class="lovtd">
                                                    <?php if ($canEdtDfltGlobalVar) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="allDfltGlobalVarsRow<?php echo $cntr; ?>_VarValue" value="<?php echo $row[2]; ?>">
                                                        </div>
                                                        <?php
                                                    } else {
                                                        echo $row[2];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtDfltGlobalVar) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <select class="form-control rqrdFld" id="allDfltGlobalVarsRow<?php echo $cntr; ?>_VarDataType" onchange="">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "");
                                                                    $srchInsArrys = array("Text", "Number", "Date");
                                                                    $valsArrys = array("Text", "Number", "Date");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($row[3] == $valsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $valsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        echo $row[1];
                                                    }
                                                    ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allDfltGlobalVarsRow<?php echo $cntr; ?>_PkeyID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_global_variables|global_var_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                } else if ($subPgNo == 1.9) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span><span style=\"text-decoration:none;\"> Configuration Menu</span>
                                    </li>
                                    <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.9');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">Loan Classifications</span>
                                    </li>
                                </ul>
                              </div>";
                    //echo "Default Transaction Accounts";
                    $canAddLoanClsfctn = test_prmssns($dfltPrvldgs[253], $mdlNm);
                    $canEdtLoanClsfctn = test_prmssns($dfltPrvldgs[254], $mdlNm);
                    $canDelLoanClsfctn = test_prmssns($dfltPrvldgs[255], $mdlNm);
                    $total = get_LoanClsfctnsTtl($srchFor, $srchIn, $orgID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_LoanClsfctns($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                    $cntr = 0;
                    $colClassType1 = "col-lg-4";
                    $colClassType2 = "col-lg-2";
                    $colClassType3 = "col-lg-2";
                    ?>
                    <form id='allLoanClsfctnsForm' action='' method='post' accept-charset='UTF-8'>                        
                        <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">         
                            <?php
                            if ($canAddLoanClsfctn || $canEdtLoanClsfctn) {
                                $nwRowHtml = "<tr id=\"allLoanClsfctnsRow__WWW123WWW\" role=\"row\">
                                                    <td class=\"lovtd\">
                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                            <span>New</span>
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                                                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                                                        <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allLoanClsfctnsRow_WWW123WWW_ClsfctnNm\" value=\"\">
                                                                                                                </div>
                                                                                                        </td>";

                                $nwRowHtml .= "</div>";
                                $nwRowHtml .= "<input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allLoanClsfctnsRow_WWW123WWW_PkeyID\" value=\"-1\">
                                                </td>
                                                <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                        <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allLoanClsfctnsRow_WWW123WWW_RangeLow\" value=\"\">
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                        <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allLoanClsfctnsRow_WWW123WWW_RangeHigh\" value=\"\">
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                        <input type=\"number\" min=\"0\" max=\"100\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allLoanClsfctnsRow_WWW123WWW_PrvsnPrcnt\" value=\"\">
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\" style=\"text-align:center !important;\">
                                                    <div class=\"form-group form-group-sm\">
                                                        <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                            <label class=\"form-check-label\">
                                                                <input type=\"checkbox\" class=\"form-check-input\" id=\"allLoanClsfctnsRow_WWW123WWW_IsEnbld\" name=\"allLoanClsfctnsRow_WWW123WWW_IsEnbld\">
                                                            </label>
                                                        </div>
                                                    </div>                                                        
                                                </td>
                                                <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delLoanClsfctns('allLoanClsfctnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Loan Classification\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button> 
                                                </td>
                                            </tr>";
                                $nwRowHtml = urlencode($nwRowHtml);
                                ?> 
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                    <?php if ($canAddLoanClsfctn) { ?>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allLoanClsfctnsTable', 0, '<?php echo $nwRowHtml; ?>');">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Loan Classification
                                        </button>
                                    <?php } ?>
                                    <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 5px;" onclick="saveLoanClsfctns('#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.9');">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        SAVE
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-5";
                                $colClassType2 = "col-lg-5";
                                $colClassType3 = "col-lg-2";
                            }
                            ?>    
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;">                                
                                <div class="input-group">
                                    <input class="form-control" id="allLoanClsfctnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAllLoanClsfctns(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                    <input id="allLoanClsfctnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllLoanClsfctns('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllLoanClsfctns('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allLoanClsfctnsSrchIn">
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
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allLoanClsfctnsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;">                                
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllLoanClsfctns('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllLoanClsfctns('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&subPgNo=<?php echo $subPgNo; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allLoanClsfctnsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th style="max-width:300px;">Name</th>
                                            <th style="max-width:150px;">Range Low</th>
                                            <th style="max-width:150px;">Range High</th>
                                            <th style="max-width:150px;">Provision Percent</th>
                                            <th style="max-width: 75px !important;width: 75px !important;">Enabled?</th>
                                            <th style="max-width: 35px !important;width: 30px !important;">&nbsp;</th>
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
                                            <tr id="allLoanClsfctnsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtLoanClsfctn) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="allLoanClsfctnsRow<?php echo $cntr; ?>_ClsfctnNm" value="<?php echo $row[1]; ?>">
                                                        </div>
                                                        <?php
                                                    } else {
                                                        echo $row[1];
                                                    }
                                                    ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allLoanClsfctnsRow<?php echo $cntr; ?>_PkeyID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtLoanClsfctn) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allLoanClsfctnsRow<?php echo $cntr; ?>_RangeLow" value="<?php echo $row[2]; ?>">
                                                        </div>
                                                        <?php
                                                    } else {
                                                        echo $row[2];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtLoanClsfctn) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" min="0" class="form-control rqrdFld" aria-label="..." id="allLoanClsfctnsRow<?php echo $cntr; ?>_RangeHigh" value="<?php echo $row[3]; ?>">
                                                        </div>
                                                        <?php
                                                    } else {
                                                        echo $row[3];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtLoanClsfctn) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" min="0" max="100" class="form-control rqrdFld" aria-label="..." id="allLoanClsfctnsRow<?php echo $cntr; ?>_PrvsnPrcnt" value="<?php echo $row[4]; ?>">
                                                        </div>
                                                        <?php
                                                    } else {
                                                        echo $row[4];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="lovtd" style="text-align:center !important;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($row[5] == "1") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    if ($canEdtLoanClsfctn === true) {
                                                        ?>
                                                        <div class="form-group form-group-sm">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="allLoanClsfctnsRow<?php echo $cntr; ?>_IsEnbld" name="allLoanClsfctnsRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo ($row[5] == "1" ? "Yes" : "No"); ?></span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">    
                                                    <?php if ($canDelLoanClsfctn === true) { ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delLoanClsfctns('allLoanClsfctnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Loan Classification">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } else { ?>
                                                        &nbsp;
                                                    <?php } ?>  
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|mcf.mcf_loan_classifications_setup|loan_clsfctn_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
            } else if ($vwtyp == 1) {
                if ($subPgNo == 1.2) {
                    //Denominations Table
                    $sbmtdCrncyID = isset($_POST['sbmtdCrncyID']) ? cleanInputData($_POST['sbmtdCrncyID']) : -1;
                    $srchFor = "%";
                    $srchIn = "Name";
                    $pageNo = 1;
                    $lmtSze = 100;
                    $curIdx = 0;
                    $vwtyp = 1;
                    if ($sbmtdCrncyID > 0) {
                        $result2 = get_CurrencyDenoms($sbmtdCrncyID);
                        ?>
                        <div class="row" style="padding:0px 15px 0px 15px !important">                  
                            <div class="col-md-12" style="padding:0px 1px 0px 1px !important">
                                <table class="table table-striped table-bordered table-responsive" id="crncyDenomsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Denomination</th>
                                            <th>Type</th>
                                            <th>Value</th>
                                            <th>Enabled?</th>
                                            <th>Linked Stock Item</th>
                                            <th>&nbsp;</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row2 = loc_db_fetch_array($result2)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="crncyDenomsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="crncyDenomsRow<?php echo $cntr; ?>_DenomID" value="<?php echo $row2[0]; ?>">  
                                                    <input type="hidden" class="form-control" aria-label="..." id="crncyDenomsRow<?php echo $cntr; ?>_DenomNm" value="<?php echo $row2[2]; ?>">                                             
                                                </td>                                             
                                                <td class="lovtd">  
                                                    <span><?php echo $row2[1]; ?></span>
                                                </td>                                      
                                                <td class="lovtd">  
                                                    <span><?php echo number_format((float) $row2[3], 2); ?></span>
                                                </td>                                           
                                                <td class="lovtd">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($row2[4] == "YES") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>   
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <div class="form-check" style="font-size: 12px !important;">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" id="crncyDenomsRow<?php echo $cntr; ?>_IsEnbld" name="crncyDenomsRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd . " " . $isRdOnly; ?> >
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>                                         
                                                <td class="lovtd">  
                                                    <span><?php echo $row2[6]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="crncyDenomsRow<?php echo $cntr; ?>_LnkdItmID" value="<?php echo $row2[5]; ?>" style="width:100% !important;">                                                                                                              
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delDenomStp('crncyDenomsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Denomination">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row2[0] . "|mcf.mcf_currency_denominations|crncy_denom_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                        <?php
                    }
                }
            } else if ($vwtyp == 2) {
                if ($subPgNo == 1.1) {
                    //New Interface Transaction Form
                    $canEdtIntrfc = test_prmssns($dfltPrvldgs[214], $mdlNm);
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
                        $result = get_OneMCFGlIntrfcDet($sbmtdIntrfcID);
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
                            if ($glBatchID > 0) {
                                //$trnsSource == "SYS" || 
                                $canEdtIntrfc = FALSE;
                                $mkReadOnly = "readonly=\"true\"";
                            }
                        }
                    } else {
                        $dfrnce = round(getMCFGLIntrfcDffrnc($orgID), 2);
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
                                                                                            afterMCFIntrfcItemSlctn();
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
                                                                                            afterMCFIntrfcItemSlctn();
                                                                                        });"> 
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label> 
                                                        <input class="form-control rqrdFld vmsTtlAmt" type="text" id="enteredAmount" value="<?php
                                                        echo number_format($enteredAmount, 2);
                                                        ?>"  style="font-weight:bold;" onchange="afterMCFIntrfcItemSlctn();"/>
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
                                <?php if ($canEdtIntrfc === true && $glBatchID <= 0) { //&& $trnsSource != "SYS"  ?>
                                    <button type="button" class="btn btn-primary" onclick="saveMCFGLIntrfcForm();">Save Changes</button>
                                <?php } ?>
                            </div>
                        </div>
                    </form>                    
                    <?php
                } else if ($subPgNo == 1.2) {
                    //New Currency/Denominations Form
                    $canEdtCrncy = test_prmssns($dfltPrvldgs[176], $mdlNm);
                    $sbmtdCrncyID = isset($_POST['sbmtdCrncyID']) ? cleanInputData($_POST['sbmtdCrncyID']) : -1;
                    $isoCode = "";
                    $description = "";
                    $isEnbld = "YES";
                    $mppdCrncyID = 1;
                    $mppdCrncyNm = "";
                    if ($sbmtdCrncyID > 0) {
                        $result = get_OneCrncyDet($sbmtdCrncyID);
                        while ($row = loc_db_fetch_array($result)) {
                            $sbmtdCrncyID = (float) $row[0];
                            $isoCode = $row[2];
                            $description = $row[1];
                            $isEnbld = $row[3];
                            $mppdCrncyID = (float) $row[4];
                            $mppdCrncyNm = $row[5];
                        }
                    }
                    ?>
                    <form class="form-horizontal" id='mcfCrncyStpForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-7">
                                                <label for="crncyISOCode" class="control-label">Currency ISO Code:</label>
                                            </div>
                                            <div class="col-md-5">
                                                <?php if ($canEdtCrncy === true) { ?>
                                                    <input type="text" name="crncyISOCode" id="crncyISOCode" class="form-control" value="<?php echo $isoCode; ?>">
                                                    <input type="hidden" name="crncyID" id="crncyID" class="form-control" value="<?php echo $sbmtdCrncyID; ?>">
                                                <?php } else { ?>
                                                    <span><?php echo $isoCode; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4">
                                                <label for="crncyDesc" class="control-label">Currency Description:</label>
                                            </div>
                                            <div class="col-md-8">     
                                                <?php if ($canEdtCrncy === true) { ?>
                                                    <textarea rows="2" name="crncyDesc" id="crncyDesc" class="form-control"><?php echo $description; ?></textarea>
                                                <?php } else { ?>
                                                    <span><?php echo $description; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row" style="margin-bottom:1px !important;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-7">
                                                <label for="mppdCrncyNm" class="control-label">Mapped LOV Currency:</label>
                                            </div>
                                            <div class="col-md-5">
                                                <?php if ($canEdtCrncy === true) { ?>                                   
                                                    <div class="input-group">
                                                        <input type="text" name="mppdCrncyNm" id="mppdCrncyNm" class="form-control" value="<?php echo $mppdCrncyNm; ?>" readonly="true">
                                                        <input type="hidden" name="mppdCrncyID" id="mppdCrncyID" class="form-control" value="<?php echo $mppdCrncyID; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'mppdCrncyNm', '', 'clear', 0, '', function () {
                                                                                            var aa112 = 1;
                                                                                        });"> 
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                <?php } else { ?>
                                                    <span><?php echo $mppdCrncyNm; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4">
                                                &nbsp;
                                            </div>
                                            <div class="col-md-4">
                                                <div class="checkbox">
                                                    <label for="isCrncyEnbld" class="control-label">
                                                        <?php
                                                        $isChkd = "";
                                                        $isRdOnly = "disabled=\"true\"";
                                                        if ($canEdtCrncy === true) {
                                                            $isRdOnly = "";
                                                        }
                                                        if ($isEnbld == "YES") {
                                                            $isChkd = "checked=\"true\"";
                                                        }
                                                        ?>
                                                        <input type="checkbox" name="isCrncyEnbld" id="isCrncyEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 15px 5px 15px;"></div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-md-12">
                                        <?php
                                        if ($canEdtCrncy === true) {
                                            $nwRowHtml = "<tr id=\"crncyDenomsStpRow__WWW123WWW\">"
                                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                    . "<td class=\"lovtd\"> 
                                                                <input type=\"text\" name=\"crncyDenomsStpRow_WWW123WWW_DenomNm\" id=\"crncyDenomsStpRow_WWW123WWW_DenomNm\" class=\"form-control\" value=\"\" style=\"width:100% !important;\" style=\"width:100% !important;\">  
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"crncyDenomsStpRow_WWW123WWW_DenomID\" value=\"-1\" style=\"width:100% !important;\">                                              
                                                        </td>                                             
                                                        <td class=\"lovtd\">
                                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"crncyDenomsStpRow_WWW123WWW_DenomType\">";

                                            $valslctdArry = array("", "");
                                            $srchInsArrys = array("Note", "Coin");

                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                $nwRowHtml .= "<option value=\"$srchInsArrys[$z]\" $valslctdArry[$z]>$srchInsArrys[$z]</option>";
                                            }
                                            $nwRowHtml .= "</select>
                                                        </td>                                      
                                                        <td class=\"lovtd\" style=\"text-align: right;\">  
                                                                <input type=\"number\" name=\"crncyDenomsStpRow_WWW123WWW_DenomVal\" id=\"crncyDenomsStpRow_WWW123WWW_DenomVal\" class=\"form-control\" value=\"0.00\" style=\"width:100% !important;\" style=\"width:100% !important;\">  
                                                        </td>                                           
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"crncyDenomsStpRow_WWW123WWW_IsEnbld\" name=\"crncyDenomsStpRow_WWW123WWW_IsEnbld\">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>                                         
                                                        <td class=\"lovtd\">                                  
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"text\" name=\"crncyDenomsStpRow_WWW123WWW_LnkdItmNm\" id=\"crncyDenomsStpRow_WWW123WWW_LnkdItmNm\" class=\"form-control\" value=\"\" readonly=\"true\" style=\"width:100% !important;\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"crncyDenomsStpRow_WWW123WWW_LnkdItmID\" value=\"-1\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Linked Stock Items for Denominations', 'allOtherInputOrgID', 'mppdCrncyNm', '', 'radio', true, '', 'crncyDenomsStpRow_WWW123WWW_LnkdItmID', 'crncyDenomsStpRow_WWW123WWW_LnkdItmNm', 'clear', 0, '', function () {
                                                                            var aa112 = 1;
                                                                        });\"> 
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>                                                                                                            
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delDenomStp('crncyDenomsStpRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Denomination\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                        <td class=\"lovtd\"> 
                                                           &nbsp;
                                                        </td>
                                                    </tr>";
                                            $nwRowHtml = urlencode($nwRowHtml);
                                            ?> 
                                            <div class="" style="float:left !important;padding-left: 1px;">
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('crncyDenomsStpTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Denomination">
                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Add Denomination
                                                </button>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveCrncyStpForm('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&subPgNo=1.2');" data-toggle="tooltip" data-placement="bottom" title = "Saves Currency">
                                                    <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Save Currency
                                                </button>
                                            </div>
                                        <?php } else { ?>                                        
                                            <label class="control-label">Denominations Created:</label>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-responsive" id="crncyDenomsStpTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Denomination</th>
                                                    <th>Type</th>
                                                    <th style="text-align: right;">Value</th>
                                                    <th style="text-align: center;">Enabled?</th>
                                                    <th>Linked Stock Item</th>
                                                    <?php if ($canEdtCrncy) { ?>
                                                        <th>&nbsp;</th>
                                                    <?php } ?>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <th>...</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cntr = 0;
                                                $srchFor = "%";
                                                $srchIn = "Name";
                                                $pageNo = 1;
                                                $lmtSze = 100;
                                                $curIdx = 0;
                                                $result2 = get_CurrencyDenoms($sbmtdCrncyID);
                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                    $cntr += 1;
                                                    ?>
                                                    <tr id="crncyDenomsStpRow_<?php echo $cntr; ?>">                                    
                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                        <td class="lovtd"> 
                                                            <?php if ($canEdtCrncy === true) { ?> 
                                                                <input type="text" name="crncyDenomsStpRow<?php echo $cntr; ?>_DenomNm" id="crncyDenomsStpRow<?php echo $cntr; ?>_DenomNm" class="form-control" value="<?php
                                                                echo $row2[2];
                                                                ?>" style="width:100% !important;" style="width:100% !important;">                                                                
                                                                   <?php } else { ?>
                                                                <span><?php echo $row2[2]; ?></span> 
                                                                <input type="hidden" name="crncyDenomsStpRow<?php echo $cntr; ?>_DenomNm" id="crncyDenomsStpRow<?php echo $cntr; ?>_DenomNm" class="form-control" value="<?php
                                                                echo $row2[2];
                                                                ?>"> 
                                                                   <?php } ?> 
                                                            <input type="hidden" class="form-control" aria-label="..." id="crncyDenomsStpRow<?php echo $cntr; ?>_DenomID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
                                                        </td>                                             
                                                        <td class="lovtd">
                                                            <?php if ($canEdtCrncy === true) { ?> 
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="crncyDenomsStpRow<?php echo $cntr; ?>_DenomType">
                                                                    <?php
                                                                    $valslctdArry = array("", "");
                                                                    $srchInsArrys = array("Note", "Coin");

                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($row2[1] == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <span><?php echo $row2[1]; ?></span>
                                                            <?php } ?> 
                                                        </td>                                      
                                                        <td class="lovtd" style="text-align: right;">  
                                                            <?php if ($canEdtCrncy === true) { ?> 
                                                                <input type="number" name="crncyDenomsStpRow<?php echo $cntr; ?>_DenomVal" id="crncyDenomsStpRow<?php echo $cntr; ?>_DenomVal" class="form-control" value="<?php
                                                                echo number_format((float) $row2[3], 2);
                                                                ?>" style="width:100% !important;" style="width:100% !important;">                                                                
                                                                   <?php } else { ?>
                                                                <span><?php echo number_format((float) $row2[3], 2); ?></span>
                                                            <?php } ?> 
                                                        </td>                                           
                                                        <td class="lovtd" style="text-align: center;">
                                                            <?php
                                                            $isChkd = "";
                                                            $isRdOnly = "disabled=\"true\"";
                                                            if ($row2[4] == "YES") {
                                                                $isChkd = "checked=\"true\"";
                                                            }
                                                            if ($canEdtCrncy === true) {
                                                                $isRdOnly = "";
                                                            }
                                                            ?>   
                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" id="crncyDenomsStpRow<?php echo $cntr; ?>_IsEnbld" name="crncyDenomsStpRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd . " " . $isRdOnly; ?> >
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>                                         
                                                        <td class="lovtd">
                                                            <?php if ($canEdtCrncy === true) { ?>                                   
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" name="crncyDenomsStpRow<?php echo $cntr; ?>_LnkdItmNm" id="crncyDenomsStpRow<?php echo $cntr; ?>_LnkdItmNm" class="form-control" value="<?php echo $row2[6]; ?>" readonly="true" style="width:100% !important;" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="crncyDenomsStpRow<?php echo $cntr; ?>_LnkdItmID" value="<?php echo $row2[5]; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Linked Stock Items for Denominations', 'allOtherInputOrgID', 'mppdCrncyNm', '', 'radio', true, '', 'crncyDenomsStpRow<?php echo $cntr; ?>_LnkdItmID', 'crncyDenomsStpRow<?php echo $cntr; ?>_LnkdItmNm', 'clear', 0, '', function () {
                                                                                                            var aa112 = 1;
                                                                                                        });"> 
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span><?php echo $row2[6]; ?></span>
                                                            <?php } ?>                                                                                                               
                                                        </td>                                                    
                                                        <?php if ($canEdtCrncy) { ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delDenomStp('crncyDenomsStpRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Denomination">
                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        <?php } ?>
                                                        <?php if ($canVwRcHstry === true) { ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row2[0] . "|mcf.mcf_currency_denominations|crncy_denom_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                    </form>                    
                    <?php
                } else if ($subPgNo == 1.3) {
                    //New Teller Till Form
                    $canEdtCage = test_prmssns($dfltPrvldgs[180], $mdlNm);
                    $sbmtdCageID = isset($_POST['sbmtdCageID']) ? cleanInputData($_POST['sbmtdCageID']) : -1;

                    $cageLineID = $sbmtdCageID;
                    $cageShelfNm = "";
                    $cageShelfID = -1;
                    $cageVltID = -1;
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
                    }
                    ?>
                    <form class="form-horizontal" id='mcfTillStpForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4">
                                                <label for="cageShelfNm" class="control-label">Cage/Till Name:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <?php if ($canEdtCage === true) { ?>                                   
                                                    <div class="input-group" style="width:100% !important;">
                                                        <input type="text" name="cageShelfNm" id="cageShelfNm" class="form-control rqrdFld" value="<?php echo $cageShelfNm; ?>" style="width:100% !important;">
                                                        <input type="hidden" name="cageLineID" id="cageLineID" class="form-control" value="<?php echo $cageLineID; ?>">
                                                        <input type="hidden" name="cageShelfID" id="cageLineID" class="form-control" value="<?php echo $cageShelfID; ?>">
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
                                                <label for="lnkdGLAccountNm" class="control-label">Linked GL Account:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <?php if ($canEdtCage === true) { ?>                                   
                                                    <div class="input-group">
                                                        <input type="text" name="lnkdGLAccountNm" id="lnkdGLAccountNm" class="form-control" value="<?php echo $lnkdGLAccountNm; ?>" readonly="true">
                                                        <input type="hidden" name="lnkdGLAccountID" id="lnkdGLAccountID" class="form-control" value="<?php echo $lnkdGLAccountID; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', '', '', '', 'radio', true, '', 'lnkdGLAccountID', 'lnkdGLAccountNm', 'clear', 0, '', function () {
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
                                                    <select class="form-control" id="grpType" onchange="grpTypMcfChange();">                                                        
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
                                                    <input type="number" name="mngrsWithdrawlLmt" id="mngrsWithdrawlLmt" class="form-control" value="<?php echo $mngrsWithdrawlLmt; ?>">                                                        
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
                                                    <input type="number" name="mngrsDepositLmt" id="mngrsDepositLmt" class="form-control" value="<?php echo $mngrsDepositLmt; ?>">                                                        
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
                                                    <select class="form-control" id="dfltItemType">
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
                                                        <input type="text" name="dfltItemState" id="dfltItemState" class="form-control" value="<?php echo $dfltItemState; ?>" readonly="true">
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
                                    <button type="button" class="btn btn-primary" onclick="saveVmsCgForm();">Save Changes</button>
                                <?php } ?>
                            </div>
                        </div>
                    </form>                    
                    <?php
                } else if ($subPgNo == 1.6) {
                    //New Bank Form
                    $canEdtBnk = test_prmssns($dfltPrvldgs[194], $mdlNm);
                    $sbmtdBankID = isset($_POST['sbmtdBankID']) ? cleanInputData($_POST['sbmtdBankID']) : -1;
                    $bnkCode = "";
                    $bnkName = "";
                    $contactNos = "";
                    $bnkEmail = "";
                    $bnkCntryCode = "";
                    $bnkChkDgts = "";
                    $bnkSwftCode = "";
                    $bnkFxNo = "";
                    $bnkPstlAdrs = "";
                    $bnkResAdrs = "";
                    $isEnbld = "1";
                    if ($sbmtdBankID > 0) {
                        $result = get_OneBankDet($sbmtdBankID);
                        while ($row = loc_db_fetch_array($result)) {
                            $sbmtdBankID = (float) $row[0];
                            $bnkCode = $row[1];
                            $bnkName = $row[2];
                            $contactNos = $row[5];
                            $bnkEmail = $row[6];
                            $bnkCntryCode = $row[13];
                            $bnkChkDgts = $row[14];
                            $bnkSwftCode = $row[15];
                            $bnkFxNo = $row[7];
                            $bnkPstlAdrs = $row[4];
                            $bnkResAdrs = $row[3];
                            $isEnbld = $row[16];
                        }
                    }
                    ?>
                    <form class="form-horizontal" id='mcfCrncyStpForm' action='' method='post' accept-charset='UTF-8'>                        
                        <div class="row" id="allBnksHdrInfo" style="padding:0px 15px 0px 15px !important">
                            <fieldset class="basic_person_fs" style="padding:10px 3px 5px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;"> 
                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetCode" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Bank Code:</label>
                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <?php if ($canEdtBnk === true) { ?>
                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="oneBnkDetCode" name="oneBnkDetCode" value="<?php echo $bnkCode; ?>" style="width:100%;">
                                                <input type="hidden" class="form-control" aria-label="..." id="oneBnkDetID" name="oneBnkDetID" value="<?php echo $sbmtdBankID; ?>">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $bnkCode; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Bank Name:</label>
                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <?php if ($canEdtBnk === true) { ?>
                                                <textarea class="form-control rqrdFld" aria-label="..." id="oneBnkDetName" name="oneBnkDetName" style="width:100%;" cols="5" rows="3"><?php echo $bnkName; ?></textarea>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $bnkName; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetCntct" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Contact Nos:</label>
                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <?php if ($canEdtBnk === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="oneBnkDetCntct" name="oneBnkDetCntct" value="<?php echo $contactNos; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $contactNos; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetEmail" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Email:</label>
                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <?php if ($canEdtBnk === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="oneBnkDetEmail" name="oneBnkDetEmail" value="<?php echo $bnkEmail; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $bnkEmail; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetISOCntryCode" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">ISO Country Code:</label>
                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <?php if ($canEdtBnk === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="oneBnkDetISOCntryCode" name="oneBnkDetISOCntryCode" value="<?php echo $bnkCntryCode; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $bnkCntryCode; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetChkDgts" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Check Digits:</label>
                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <?php if ($canEdtBnk === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="oneBnkDetChkDgts" name="oneBnkDetChkDgts" value="<?php echo $bnkChkDgts; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $bnkChkDgts; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">                                                    
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetSwiftCode" class="control-label col-lg-3" style="padding:0px 15px 0px 1px !important;">Swift Code:</label>
                                        <div class="col-lg-9" style="padding:0px 15px 0px 1px !important;">
                                            <?php if ($canEdtBnk === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="oneBnkDetSwiftCode" name="oneBnkDetSwiftCode" value="<?php echo $bnkSwftCode; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $bnkSwftCode; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>                                                    
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetFax" class="control-label col-lg-3" style="padding:0px 15px 0px 1px !important;">Fax Nos:</label>
                                        <div class="col-lg-9" style="padding:0px 15px 0px 1px !important;">
                                            <?php if ($canEdtBnk === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="oneBnkDetFax" name="oneBnkDetFax" value="<?php echo $bnkFxNo; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $bnkFxNo; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetPstl" class="control-label col-lg-3" style="padding:0px 15px 0px 1px !important;">Postal Address:</label>
                                        <div  class="col-lg-9" style="padding:0px 15px 0px 1px !important;">
                                            <?php if ($canEdtBnk === true) { ?>
                                                <textarea class="form-control" aria-label="..." id="oneBnkDetPstl" name="oneBnkDetPstl" style="width:100%;" cols="5" rows="3"><?php echo $bnkPstlAdrs; ?></textarea>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $bnkPstlAdrs; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="oneBnkDetRes" class="control-label col-lg-3" style="padding:0px 15px 0px 1px !important;">Residential Address:</label>
                                        <div  class="col-lg-9" style="padding:0px 15px 0px 1px !important;">
                                            <?php if ($canEdtBnk === true) { ?>
                                                <textarea class="form-control" aria-label="..." id="oneBnkDetRes" name="oneBnkDetRes" style="width:100%;" cols="5" rows="3"><?php echo $bnkResAdrs; ?></textarea>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $bnkResAdrs; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <div  class="col-lg-3" style="padding:0px 15px 0px 1px !important;">&nbsp;</div>
                                        <div  class="col-lg-9" style="padding:0px 15px 0px 1px !important;">
                                            <div class="checkbox">
                                                <label for="isBnkEnbld" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdtBnk === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($isEnbld == "1") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isBnkEnbld" id="isBnkEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row" id="allBnkBrnchsStpDetailInfo" style="padding:0px 15px 0px 15px !important">
                            <?php
                            /* &vtyp=<?php echo $vwtyp; ?> */
                            $srchFor = "%";
                            $srchIn = "Name";
                            $pageNo = 1;
                            $lmtSze = 10;
                            $vwtyp = 1;
                            //if ($sbmtdBankID > 0) {
                            $total = get_AllBankBrnchsTtl($srchFor, $srchIn, $sbmtdBankID);
                            if ($pageNo > ceil($total / $lmtSze)) {
                                $pageNo = 1;
                            } else if ($pageNo < 1) {
                                $pageNo = ceil($total / $lmtSze);
                            }
                            $curIdx = $pageNo - 1;
                            $result2 = get_AllBankBrnchs($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdBankID);
                            ?>
                            <div class="row" style="padding:0px 15px 0px 15px !important">
                                <?php
                                if ($canEdtBnk === true) {
                                    $colClassType1 = "col-lg-5";
                                    $colClassType2 = "col-lg-3";
                                    $colClassType3 = "col-lg-2";
                                    $nwRowHtml = "<tr id=\"allBnkBrnchsStpRow__WWW123WWW\">"
                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                            . "<td class=\"lovtd\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allBnkBrnchsStpRow_WWW123WWW_BankID\" value=\"$sbmtdBankID\" readonly=\"true\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allBnkBrnchsStpRow_WWW123WWW_BranchID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                                                                 
                                                            <input type=\"text\" name=\"allBnkBrnchsStpRow_WWW123WWW_BrnchCode\" id=\"allBnkBrnchsStpRow_WWW123WWW_BrnchCode\" class=\"form-control rqrdFld\" value=\"\" style=\"width:100% !important;\" style=\"width:100% !important;\">
                                                        </td>                                             
                                                        <td class=\"lovtd\">
                                                                <input type=\"text\" name=\"allBnkBrnchsStpRow_WWW123WWW_BranchNm\" id=\"allBnkBrnchsStpRow_WWW123WWW_BranchNm\" class=\"form-control rqrdFld\" value=\"\" style=\"width:100% !important;\" style=\"width:100% !important;\"> 
                                                        </td>                                            
                                                        <td class=\"lovtd\"> 
                                                                <textarea name=\"allBnkBrnchsStpRow_WWW123WWW_PstlAdrs\" id=\"allBnkBrnchsStpRow_WWW123WWW_PstlAdrs\" class=\"form-control\" style=\"width:100% !important;\"></textarea> 
                                                        </td>                                            
                                                        <td class=\"lovtd\">     
                                                                <textarea name=\"allBnkBrnchsStpRow_WWW123WWW_ResAdrs\" id=\"allBnkBrnchsStpRow_WWW123WWW_ResAdrs\" class=\"form-control\" style=\"width:100% !important;\"></textarea>  
                                                        </td>                                            
                                                        <td class=\"lovtd\">  
                                                                <input type=\"text\" name=\"allBnkBrnchsStpRow_WWW123WWW_CntctNos\" id=\"allBnkBrnchsStpRow_WWW123WWW_CntctNos\" class=\"form-control\" value=\"\" style=\"width:100% !important;\" style=\"width:100% !important;\"> 
                                                        </td>                                            
                                                        <td class=\"lovtd\"> 
                                                                <input type=\"text\" name=\"allBnkBrnchsStpRow_WWW123WWW_Swft\" id=\"allBnkBrnchsStpRow_WWW123WWW_Swft\" class=\"form-control\" value=\"\" style=\"width:100% !important;\" style=\"width:100% !important;\"> 
                                                        </td>                                           
                                                        <td class=\"lovtd\" style=\"text-align: center;\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"allBnkBrnchsStpRow_WWW123WWW_IsEnbld\" name=\"allBnkBrnchsStpRow_WWW123WWW_IsEnbld\">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delBankBrnchStp('allBnkBrnchsStpRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Denomination\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                        <td class=\"lovtd\"> 
                                                           &nbsp;
                                                        </td>
                                                      </tr>";
                                    $nwRowHtml = urlencode($nwRowHtml);
                                    ?> 
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allBnkBrnchsStpTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Bank Branch">
                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> New Branch
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveBankStpForm('', '#allmodules', 'grp=17&typ=1&pg=8&subPgNo=1.6');" data-toggle="tooltip" data-placement="bottom" title="Save Bank Details">
                                            <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Save Bank Details
                                        </button>
                                    </div>
                                    <?php
                                } else {
                                    $colClassType1 = "col-lg-4";
                                    $colClassType2 = "col-lg-4";
                                    $colClassType3 = "col-lg-4";
                                }
                                ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="allBnkBrnchsStpSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncAllBnkBrnchs(event, '', '#allBnkBrnchsStpDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.63"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>', 1);">
                                        <input id="allBnkBrnchsStpPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBnkBrnchs('clear', '#allBnkBrnchsStpDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.63"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>', 1);">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBnkBrnchs('', '#allBnkBrnchsStpDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.63"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>', 1);">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allBnkBrnchsSrchIn">
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
                                        </select>-->
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="allBnkBrnchsStpDsplySze" style="min-width:70px !important;">                            
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
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" style="margin: 0px !important;">
                                            <li>
                                                <a class="rhopagination" href="javascript:getAllBnkBrnchs('previous', '#allBnkBrnchsStpDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.63"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>',1);" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="rhopagination" href="javascript:getAllBnkBrnchs('next', '#allBnkBrnchsStpDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo "1.63"; ?>&sbmtdBankID=<?php echo $sbmtdBankID; ?>',1);" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdBankID" name="sbmtdBankID" value="<?php echo $sbmtdBankID; ?>">
                                </div>
                            </div>
                            <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                    <table class="table table-striped table-bordered table-responsive" id="allBnkBrnchsStpTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Branch Code</th>
                                                <th>Branch Name</th>
                                                <th>Postal Address</th>
                                                <th>Residential Address</th>
                                                <th>Contact Nos.</th>
                                                <th>SWIFT CODE</th>
                                                <th style="text-align: center;">Enabled?</th>
                                                <th>&nbsp;</th>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th>...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cntr = 0;
                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="allBnkBrnchsStpRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                    <td class="lovtd">
                                                        <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsStpRow<?php echo $cntr; ?>_BankID" value="<?php echo $row2[1]; ?>" readonly="true">
                                                        <input type="hidden" class="form-control" aria-label="..." id="allBnkBrnchsStpRow<?php echo $cntr; ?>_BranchID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                                                                                                                                                 
                                                        <?php if ($canEdtBnk === true) { ?>
                                                            <input type="text" name="allBnkBrnchsStpRow<?php echo $cntr; ?>_BrnchCode" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_BrnchCode" class="form-control" value="<?php
                                                            echo $row2[3];
                                                            ?>" style="width:100% !important;" style="width:100% !important;">                                                                
                                                               <?php } else { ?>
                                                            <span><?php echo $row2[3]; ?></span>
                                                        <?php } ?> 
                                                    </td>                                             
                                                    <td class="lovtd">  
                                                        <?php if ($canEdtBnk === true) { ?>
                                                            <input type="text" name="allBnkBrnchsStpRow<?php echo $cntr; ?>_BranchNm" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_BranchNm" class="form-control" value="<?php
                                                            echo $row2[2];
                                                            ?>" style="width:100% !important;" style="width:100% !important;">                                                                
                                                               <?php } else { ?>
                                                            <span><?php echo $row2[2]; ?></span>
                                                        <?php } ?> 
                                                    </td>                                            
                                                    <td class="lovtd">                                                                
                                                        <?php if ($canEdtBnk === true) { ?>
                                                            <textarea name="allBnkBrnchsStpRow<?php echo $cntr; ?>_PstlAdrs" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_PstlAdrs" class="form-control" style="width:100% !important;"><?php
                                                                echo $row2[5];
                                                                ?></textarea>                                                               
                                                        <?php } else { ?>
                                                            <span><?php echo $row2[5]; ?></span>
                                                        <?php } ?>
                                                    </td>                                            
                                                    <td class="lovtd">                                                                
                                                        <?php if ($canEdtBnk === true) { ?>
                                                            <textarea name="allBnkBrnchsStpRow<?php echo $cntr; ?>_ResAdrs" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_ResAdrs" class="form-control" style="width:100% !important;"><?php
                                                                echo $row2[4];
                                                                ?></textarea>                                                               
                                                        <?php } else { ?>
                                                            <span><?php echo $row2[4]; ?></span>
                                                        <?php } ?>
                                                    </td>                                            
                                                    <td class="lovtd">  
                                                        <?php if ($canEdtBnk === true) { ?>
                                                            <input type="text" name="allBnkBrnchsStpRow<?php echo $cntr; ?>_CntctNos" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_CntctNos" class="form-control" value="<?php
                                                            echo $row2[6];
                                                            ?>" style="width:100% !important;" style="width:100% !important;">                                                                
                                                               <?php } else { ?>
                                                            <span><?php echo $row2[6]; ?></span>
                                                        <?php } ?> 
                                                    </td>                                            
                                                    <td class="lovtd">  
                                                        <?php if ($canEdtBnk === true) { ?>
                                                            <input type="text" name="allBnkBrnchsStpRow<?php echo $cntr; ?>_Swft" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_Swft" class="form-control" value="<?php
                                                            echo $row2[13];
                                                            ?>" style="width:100% !important;" style="width:100% !important;">                                                                
                                                               <?php } else { ?>
                                                            <span><?php echo $row2[13]; ?></span>
                                                        <?php } ?> 
                                                    </td>                                           
                                                    <td class="lovtd" style="text-align: center;">
                                                        <?php
                                                        $isChkd = "";
                                                        $isRdOnly = "disabled=\"true\"";
                                                        if ($row2[14] == "1") {
                                                            $isChkd = "checked=\"true\"";
                                                        }
                                                        if ($canEdtBnk === true) {
                                                            $isRdOnly = "";
                                                        }
                                                        ?>   
                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="allBnkBrnchsStpRow<?php echo $cntr; ?>_IsEnbld" name="allBnkBrnchsStpRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd . " " . $isRdOnly; ?> >
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delBankBrnchStp('allBnkBrnchsStpRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Branch">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row2[0] . "|mcf.mcf_bank_branches|branch_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                            <?php
                            /* } else {
                              ?>
                              <span>No Results Found</span>
                              <?php
                              } */
                            ?> 
                        </div> 
                    </form>                    
                    <?php
                }
            } else if ($vwtyp == 3) {
                if ($subPgNo == 1.1) {
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
            } else if ($vwtyp == 140) {
                /* All Attached Documents */
                $sbmtdHdrID = isset($_POST['sbmtdHdrID']) ? cleanInputData($_POST['sbmtdHdrID']) : -1;
                $pAcctID = isset($_POST['pAcctID']) ? cleanInputData($_POST['pAcctID']) : -1;
                $trnsType = isset($_POST['docType']) ? cleanInputData($_POST['docType']) : "";
                if (!$canAddTrns || ($sbmtdHdrID > 0 && !$canEdtTrns)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdHdrID;
                $total = get_Total_MCFAttachments($srchFor, $pkID, $trnsType);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_MCFAttachments($srchFor, $curIdx, $lmtSze, $pkID, $trnsType, $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>       
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdMCFDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = "<tr id=\"attchdMCFDocsRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                    . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdMCFDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdMCFDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdMCFDocsRow_WWW123WWW_DocCtgryNm', 'attchdMCFDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdMCFDocsRow_WWW123WWW_AttchdMCFDocsID\" value=\"-1\" style=\"\">
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdMCFDocsRow_WWW123WWW_TrnsType\" value=\"$trnsType\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"attchdMCFDocsRow_WWW123WWW_FileType\" style=\"min-width:70px !important;\">";
                            $valslctdArry = array("", "", "", "", "", "", "", "");
                            $dsplySzeArry = array("Signature", "Thumbprint", "Cheque", "Picture", "Deposit Slip", "Receipt", "Memo", "Other");
                            for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                $nwRowHtml .= "<option value=\"$dsplySzeArry[$y]\" $valslctdArry[$y]>$dsplySzeArry[$y]</option>";
                            }
                            $nwRowHtml .= "</select>
                                              </div>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToMcfDocs('attchdMCFDocsRow_WWW123WWW_DocFile','attchdMCFDocsRow_WWW123WWW_AttchdMCFDocsID','attchdMCFDocsRow_WWW123WWW_DocCtgryNm','attchdMCFDocsRow_WWW123WWW_FileType','attchdMCFDocsRow_WWW123WWW_TrnsType'," . $pkID . ");\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdMcfDoc('attchdMCFDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                            <td class=\"lovtd\"> 
                                               &nbsp;
                                            </td>
                                        </tr>";
                            $nwRowHtml = urlencode($nwRowHtml);
                            ?> 
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdMCFDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdMCFDocsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAttchdMcfDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdHdrID=<?php echo $sbmtdHdrID; ?>&docType=<?php echo $trnsType; ?>&subPgNo=140.1&pAcctID=<?php echo $pAcctID; ?>');">
                                    <input id="attchdMCFDocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <input id="attchdMCFDocsNwTrnsId" type = "hidden" value="<?php echo $sbmtdHdrID; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdMcfDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdHdrID=<?php echo $sbmtdHdrID; ?>&docType=<?php echo $trnsType; ?>&subPgNo=140.1&pAcctID=<?php echo $pAcctID; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdMcfDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdHdrID=<?php echo $sbmtdHdrID; ?>&docType=<?php echo $trnsType; ?>&subPgNo=140.1&pAcctID=<?php echo $pAcctID; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>

                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdMCFDocsDsplySze" style="min-width:70px !important;">                            
                                        <?php
                                        $valslctdArry = array("", "", "", "", "",
                                            "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 30, 50,
                                            100, 500, 1000);
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
                                            <a class="rhopagination" href="javascript:getAttchdMcfDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdHdrID=<?php echo $sbmtdHdrID; ?>&docType=<?php echo $trnsType; ?>&subPgNo=140.1&pAcctID=<?php echo $pAcctID; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdMcfDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdHdrID=<?php echo $sbmtdHdrID; ?>&docType=<?php echo $trnsType; ?>&subPgNo=140.1&pAcctID=<?php echo $pAcctID; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdMCFDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Doc. Name/Description</th>
                                            <th>File Type</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row2 = loc_db_fetch_array($result2)) {
                                            $cntr += 1;
                                            $doc_src = $ftp_base_db_fldr . "/Vms/" . $row2[3];
                                            $doc_src = "";
                                            $docTrsType = $row2[4];
                                            $docFileType = $row2[5];
                                            if ($docTrsType == "Individual Customers" || $docTrsType == "Corporate Customers" || $docTrsType == "Customer Groups" || $docTrsType == "Other Persons") {
                                                if ($docFileType == "Picture") {
                                                    $doc_src = $ftp_base_db_fldr . "/Mcf/Customers/Picture/" . $row2[3];
                                                } else if ($docFileType == "Signature") {
                                                    $doc_src = $ftp_base_db_fldr . "/Mcf/Customers/Signature/" . $row2[3];
                                                } else if ($docFileType == "Thumbprint") {
                                                    $doc_src = $ftp_base_db_fldr . "/Mcf/Customers/Thumbprint/" . $row2[3];
                                                } else {
                                                    $doc_src = $ftp_base_db_fldr . "/Mcf/Customers/Attachment/" . $row2[3];
                                                }
                                            } else {
                                                $doc_src = $ftp_base_db_fldr . "/Mcf/Transactions/" . $row2[3];
                                            }
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                            ?>
                                            <tr id="attchdMCFDocsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">                                                                   
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdMCFDocsRow<?php echo $cntr; ?>_AttchdMCFDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
                                                </td>
                                                <td class="lovtd">                                                                   
                                                    <span><?php echo $row2[5]; ?></span>                                           
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
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdMcfDoc('attchdMCFDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row2[0] . "|mcf.mcf_doc_attchmnts|attchmnt_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                </fieldset>         
                <?php
            }
        }
    }
}
?>
