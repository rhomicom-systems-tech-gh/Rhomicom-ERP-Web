<?php
$canAdd = test_prmssns($dfltPrvldgs[11], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[12], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[13], $mdlNm);

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
                    echo deleteAccbAccnt($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Account Report Classification */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canEdt) {
                    echo deleteAccntRptClsfctn($pKeyID);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Account Details
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdAccbGrpOrgID = isset($_POST['sbmtdAccbGrpOrgID']) ? (int) cleanInputData($_POST['sbmtdAccbGrpOrgID']) : -1;
                $sbmtdAccountID = isset($_POST['sbmtdAccountID']) ? (int) cleanInputData($_POST['sbmtdAccountID']) : -1;
                $acbAccountNum = isset($_POST['acbAccountNum']) ? cleanInputData($_POST['acbAccountNum']) : "";
                $acbAccountNumDesc = isset($_POST['acbAccountNumDesc']) ? cleanInputData($_POST['acbAccountNumDesc']) : '';
                $acbPrntAccountNumID = isset($_POST['acbPrntAccountNumID']) ? (int) cleanInputData($_POST['acbPrntAccountNumID']) : -1;
                $acbPrntAccountNum = isset($_POST['acbPrntAccountNum']) ? cleanInputData($_POST['acbPrntAccountNum']) : "";

                $acbAcntCurncy = isset($_POST['acbAcntCurncy']) ? cleanInputData($_POST['acbAcntCurncy']) : '';
                $acbAcntCurncyID = getPssblValID($acbAcntCurncy, getLovID("Currencies"));
                $acbAcntIsEnabled = isset($_POST['acbAcntIsEnabled']) ? (cleanInputData($_POST['acbAcntIsEnabled']) === "YES" ? TRUE : FALSE) : FALSE;
                $acbAcntIsPrntAcnt = isset($_POST['acbAcntIsPrntAcnt']) ? (cleanInputData($_POST['acbAcntIsPrntAcnt']) === "YES" ? TRUE : FALSE) : FALSE;
                $acbAcntIsContraAcnt = isset($_POST['acbAcntIsContraAcnt']) ? (cleanInputData($_POST['acbAcntIsContraAcnt']) === "YES" ? TRUE : FALSE) : FALSE;
                $acbAcntIsRetErngsAcnt = isset($_POST['acbAcntIsRetErngsAcnt']) ? (cleanInputData($_POST['acbAcntIsRetErngsAcnt']) === "YES" ? TRUE : FALSE) : FALSE;
                $acbAcntIsNetIncmAcnt = isset($_POST['acbAcntIsNetIncmAcnt']) ? (cleanInputData($_POST['acbAcntIsNetIncmAcnt']) === "YES" ? TRUE : FALSE) : FALSE;
                $acbAcntIsSuspnsAcnt = isset($_POST['acbAcntIsSuspnsAcnt']) ? (cleanInputData($_POST['acbAcntIsSuspnsAcnt']) === "YES" ? TRUE : FALSE) : FALSE;
                $acbAcntHsSubldgrAcnt = isset($_POST['acbAcntHsSubldgrAcnt']) ? (cleanInputData($_POST['acbAcntHsSubldgrAcnt']) === "YES" ? TRUE : FALSE) : FALSE;

                $acbAcntAcntType = isset($_POST['acbAcntAcntType']) ? cleanInputData($_POST['acbAcntAcntType']) : '';
                $acbAcntAcntClsfctn = isset($_POST['acbAcntAcntClsfctn']) ? cleanInputData($_POST['acbAcntAcntClsfctn']) : '';

                $acbAcntCtrlAcntID = isset($_POST['acbAcntCtrlAcntID']) ? (int) cleanInputData($_POST['acbAcntCtrlAcntID']) : 1;
                $acbAcntCtrlAcnt = isset($_POST['acbAcntCtrlAcnt']) ? cleanInputData($_POST['acbAcntCtrlAcnt']) : '';
                $acbAcntMppdAcntID = isset($_POST['acbAcntMppdAcntID']) ? (int) cleanInputData($_POST['acbAcntMppdAcntID']) : -1;
                $acbAcntMppdAcnt = isset($_POST['acbAcntMppdAcnt']) ? (int) cleanInputData($_POST['acbAcntMppdAcnt']) : 0;

                $accntSgmnt1ValID = isset($_POST['accntSgmnt1ValID']) ? (int) cleanInputData($_POST['accntSgmnt1ValID']) : -1;
                $accntSgmnt2ValID = isset($_POST['accntSgmnt2ValID']) ? (int) cleanInputData($_POST['accntSgmnt2ValID']) : -1;
                $accntSgmnt3ValID = isset($_POST['accntSgmnt3ValID']) ? (int) cleanInputData($_POST['accntSgmnt3ValID']) : -1;
                $accntSgmnt4ValID = isset($_POST['accntSgmnt4ValID']) ? (int) cleanInputData($_POST['accntSgmnt4ValID']) : -1;
                $accntSgmnt5ValID = isset($_POST['accntSgmnt5ValID']) ? (int) cleanInputData($_POST['accntSgmnt5ValID']) : -1;
                $accntSgmnt6ValID = isset($_POST['accntSgmnt6ValID']) ? (int) cleanInputData($_POST['accntSgmnt6ValID']) : -1;
                $accntSgmnt7ValID = isset($_POST['accntSgmnt7ValID']) ? (int) cleanInputData($_POST['accntSgmnt7ValID']) : -1;
                $accntSgmnt8ValID = isset($_POST['accntSgmnt8ValID']) ? (int) cleanInputData($_POST['accntSgmnt8ValID']) : -1;
                $accntSgmnt9ValID = isset($_POST['accntSgmnt9ValID']) ? (int) cleanInputData($_POST['accntSgmnt9ValID']) : -1;
                $accntSgmnt10ValID = isset($_POST['accntSgmnt10ValID']) ? (int) cleanInputData($_POST['accntSgmnt10ValID']) : -1;

                $exitErrMsg = "";
                if ($acbAccountNum == "" || $acbAccountNumDesc == "") {
                    $exitErrMsg .= "Please enter Account Number and Description!<br/>";
                }
                if ($acbAcntAcntType == "") {
                    $exitErrMsg .= "Please select an account Type!<br/>";
                }
                if ($acbAcntIsRetErngsAcnt == true && $acbAcntIsPrntAcnt == true) {
                    $exitErrMsg .= "A Parent account cannot be used as Retained Earnings Account!<br/>";
                }
                if ($acbAcntIsRetErngsAcnt == true && $acbAcntIsContraAcnt == true) {
                    $exitErrMsg .= "A contra account cannot be used as Retained Earnings Account!<br/>";
                }
                if ($acbAcntIsRetErngsAcnt == true && $acbAcntIsEnabled == false) {
                    $exitErrMsg .= "A Retained Earnings Account cannot be disabled!<br/>";
                }

                if ($acbAcntIsSuspnsAcnt == true && $acbAcntAcntType != "A -ASSET") {
                    $exitErrMsg .= "The account type of the Suspense Account must be ASSET<br/>";
                }

                if ($acbAcntIsRetErngsAcnt == true && $acbAcntAcntType != "EQ-EQUITY") {
                    $exitErrMsg .= "The account type of a Retained Earnings Account must be NET WORTH<br/>";
                }
                if ($acbAcntIsNetIncmAcnt == true && $acbAcntIsPrntAcnt == true) {
                    $exitErrMsg .= "A Parent account cannot be used as Net Income Account!<br/>";
                }
                if ($acbAcntIsNetIncmAcnt == true && $acbAcntIsContraAcnt == true) {
                    $exitErrMsg .= "A contra account cannot be used as Net Income Account!<br/>";
                }
                if ($acbAcntIsNetIncmAcnt == true && $acbAcntIsEnabled == false) {
                    $exitErrMsg .= "A Net Income Account cannot be disabled!<br/>";
                }
                if ($acbAcntIsNetIncmAcnt == true && $acbAcntAcntType != "EQ-EQUITY") {
                    $exitErrMsg .= "The account type of a Net Income Account must be NET WORTH<br/>";
                }
                if ($acbAcntIsRetErngsAcnt == true && $acbAcntIsNetIncmAcnt == true) {
                    $exitErrMsg .= "Same Account cannot be Retained Earnings and Net Income at same time!<br/>";
                }
                if ($acbAcntIsRetErngsAcnt == true && $acbAcntHsSubldgrAcnt == true) {
                    $exitErrMsg .= "Retained Earnings account cannot have sub-ledgers!<br/>";
                }
                if ($acbAcntIsNetIncmAcnt == true && $acbAcntHsSubldgrAcnt == true) {
                    $exitErrMsg .= "Net Income account cannot have sub-ledgers!<br/>";
                }
                if ($acbAcntIsContraAcnt == true && $acbAcntHsSubldgrAcnt == true) {
                    $exitErrMsg .= "The system does not support Sub-Ledgers on Contra-Accounts!<br/>";
                }
                if ($acbAcntIsPrntAcnt == true && $acbAcntHsSubldgrAcnt == true) {
                    $exitErrMsg .= "Parent Account cannot have sub-ledgers!<br/>";
                }
                if ($acbAcntCtrlAcntID > 0 && $acbAcntHsSubldgrAcnt == true) {
                    $exitErrMsg .= "The system does not support Control Accounts reporting to other Control Account!<br/>";
                }
                if ($acbAcntCtrlAcntID > 0 && $acbPrntAccountNumID > 0) {
                    $exitErrMsg .= "An Account with a Control Account cannot have a Parent Account as well!<br/>";
                }
                if ($acbAcntCurncyID <= 0) {
                    $exitErrMsg .= "Account Currency Cannot be Empty!<br/>";
                }
                if ($acbAcntMppdAcntID > 0) {
                    if (getAccntType($acbAcntMppdAcntID) != trim(substr($acbAcntAcntType, 0, 2))) {
                        $exitErrMsg .= "Account Type does not match that of the Mapped Account<br/>";
                    }
                }
                if ($acbPrntAccountNumID > 0) {
                    if (getAccntType($acbPrntAccountNumID) != trim(substr($acbAcntAcntType, 0, 2))) {
                        $exitErrMsg .= "Account Type does not match that of the Parent Account<br/>";
                    }
                }
                $oldAccntID = getAccntID($acbAccountNum, $orgID);
                if ($oldAccntID > 0 && $oldAccntID != $sbmtdAccountID) {
                    $exitErrMsg .= "New Account Number is already in use in this Organization!<br/>";
                }
                $oldAccntID2 = getAccntID($acbAccountNumDesc, $orgID);
                if ($oldAccntID2 > 0 && $oldAccntID2 != $sbmtdAccountID) {
                    $exitErrMsg .= "New Account Name/Description is already in use in this Organization!<br/>";
                }
                $accntType = "";
                if ($acbAcntAcntType != "") {
                    $accntType = trim(substr($acbAcntAcntType, 0, 2));
                }
                $oldCmbntnID = getAccountCmbntnID($orgID, $accntSgmnt1ValID, $accntSgmnt2ValID, $accntSgmnt3ValID, $accntSgmnt4ValID,
                        $accntSgmnt5ValID, $accntSgmnt6ValID, $accntSgmnt7ValID, $accntSgmnt8ValID, $accntSgmnt9ValID, $accntSgmnt10ValID);
                if ($oldCmbntnID > 0 && $oldCmbntnID != $sbmtdAccountID) {
                    $exitErrMsg .= "This combination of Segment Values is already present in this Organization!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAccountID'] = $sbmtdAccountID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($sbmtdAccountID <= 0) {
                    createChrt($orgID, $acbAccountNum, $acbAccountNumDesc, $acbAccountNumDesc, $acbAcntIsContraAcnt, $acbPrntAccountNumID,
                            $accntType, $acbAcntIsPrntAcnt, $acbAcntIsEnabled, $acbAcntIsRetErngsAcnt, $acbAcntIsNetIncmAcnt, 100,
                            $acbAcntHsSubldgrAcnt, $acbAcntCtrlAcntID, $acbAcntCurncyID, $acbAcntIsSuspnsAcnt, $acbAcntAcntClsfctn,
                            $accntSgmnt1ValID, $accntSgmnt2ValID, $accntSgmnt3ValID, $accntSgmnt4ValID, $accntSgmnt5ValID,
                            $accntSgmnt6ValID, $accntSgmnt7ValID, $accntSgmnt8ValID, $accntSgmnt9ValID, $accntSgmnt10ValID,
                            $acbAcntMppdAcntID);
                    $sbmtdAccountID = getAccntID($acbAccountNum, $orgID);
                } else if ($sbmtdAccountID > 0) {
                    updateChrtDet($orgID, $sbmtdAccountID, $acbAccountNum, $acbAccountNumDesc, $acbAccountNumDesc, $acbAcntIsContraAcnt,
                            $acbPrntAccountNumID, $accntType, $acbAcntIsPrntAcnt, $acbAcntIsEnabled, $acbAcntIsRetErngsAcnt,
                            $acbAcntIsNetIncmAcnt, 100, $acbAcntHsSubldgrAcnt, $acbAcntCtrlAcntID, $acbAcntCurncyID, $acbAcntIsSuspnsAcnt,
                            $acbAcntAcntClsfctn, $accntSgmnt1ValID, $accntSgmnt2ValID, $accntSgmnt3ValID, $accntSgmnt4ValID,
                            $accntSgmnt5ValID, $accntSgmnt6ValID, $accntSgmnt7ValID, $accntSgmnt8ValID, $accntSgmnt9ValID,
                            $accntSgmnt10ValID, $acbAcntMppdAcntID);
                }
                $afftctd = 0;
                $slctdAccntClsfctns = isset($_POST['slctdAccntClsfctns']) ? cleanInputData($_POST['slctdAccntClsfctns']) : '';
                if (trim($slctdAccntClsfctns, "|~") != "") {
                    //Save Account Segments
                    $variousRows = explode("|", trim($slctdAccntClsfctns, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 3) {
                            $clsfctnID = (int) (cleanInputData1($crntRow[0]));
                            $majCtgrName = cleanInputData1($crntRow[1]);
                            $minCtgrName = cleanInputData1($crntRow[2]);
                            $oldClsfctnID = get_AccntRptClsfctnID($majCtgrName, $minCtgrName, $sbmtdAccountID);
                            if ($majCtgrName != "" || $minCtgrName != "") {
                                if ($oldClsfctnID <= 0) {
                                    //Insert
                                    $clsfctnID = getNewAccntRptClsfLnID();
                                    $afftctd += createAccntRptClsfctn($clsfctnID, $majCtgrName, $minCtgrName, $sbmtdAccountID);
                                } else if ($oldClsfctnID > 0) {
                                    $afftctd += updateAccntRptClsfctn($oldClsfctnID, $majCtgrName, $minCtgrName, $sbmtdAccountID);
                                }
                            }
                        }
                    }
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdAccountID'] = $sbmtdAccountID;
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Account Successfully Saved!<br/>" . $afftctd . " Report Classification(s) Saved Successfully!";
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 101) {
                //Import Accounts
                $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
                session_write_close();
                $affctd = 0;
                if ($dataToSend != "") {
                    $variousRows = explode("|", $dataToSend);
                    $total = count($variousRows);
                    for ($z = 0; $z < $total; $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 26) {
                            $sbmtdAccbGrpOrgID = -1;
                            $acbAccountNum = trim(cleanInputData1($crntRow[0]));
                            $acbAccountNumDesc = trim((cleanInputData1($crntRow[1])));
                            $acbAcntAcntType = trim(cleanInputData1($crntRow[3]));
                            $acbPrntAccountNum = trim(cleanInputData1($crntRow[4]));
                            $acbPrntAccountNumID = getAccntID($acbPrntAccountNum, $orgID);
                            $acbAcntIsPrntAcnt = strtoupper(trim(cleanInputData1($crntRow[5]))) === "YES" ? TRUE : FALSE;
                            $acbAcntIsRetErngsAcnt = strtoupper(trim(cleanInputData1($crntRow[6]))) === "YES" ? TRUE : FALSE;
                            $acbAcntIsNetIncmAcnt = strtoupper(trim(cleanInputData1($crntRow[7]))) === "YES" ? TRUE : FALSE;
                            $acbAcntIsContraAcnt = strtoupper(trim(cleanInputData1($crntRow[8]))) === "YES" ? TRUE : FALSE;
                            $acbAcntHsSubldgrAcnt = strtoupper(trim(cleanInputData1($crntRow[10]))) === "YES" ? TRUE : FALSE;
                            $acbAcntCtrlAcnt = trim(cleanInputData1($crntRow[11]));
                            $acbAcntCtrlAcntID = getAccntID($acbAcntCtrlAcnt, $orgID);
                            $acbAcntCurncy = trim(cleanInputData1($crntRow[12]));
                            $acbAcntCurncyID = getPssblValID($acbAcntCurncy, getLovID("Currencies"));
                            $acbAcntIsSuspnsAcnt = strtoupper(trim(cleanInputData1($crntRow[13]))) === "YES" ? TRUE : FALSE;
                            $acbAcntIsEnabled = TRUE;
                            $acbAcntAcntClsfctn = trim(cleanInputData1($crntRow[14]));

                            $accntSgmnt1ValID = getSegmentValID(trim(cleanInputData1($crntRow[15])), $orgID);
                            $accntSgmnt2ValID = getSegmentValID(trim(cleanInputData1($crntRow[16])), $orgID);
                            $accntSgmnt3ValID = getSegmentValID(trim(cleanInputData1($crntRow[17])), $orgID);
                            $accntSgmnt4ValID = getSegmentValID(trim(cleanInputData1($crntRow[18])), $orgID);
                            $accntSgmnt5ValID = getSegmentValID(trim(cleanInputData1($crntRow[19])), $orgID);
                            $accntSgmnt6ValID = getSegmentValID(trim(cleanInputData1($crntRow[20])), $orgID);
                            $accntSgmnt7ValID = getSegmentValID(trim(cleanInputData1($crntRow[21])), $orgID);
                            $accntSgmnt8ValID = getSegmentValID(trim(cleanInputData1($crntRow[22])), $orgID);
                            $accntSgmnt9ValID = getSegmentValID(trim(cleanInputData1($crntRow[23])), $orgID);
                            $accntSgmnt10ValID = getSegmentValID(trim(cleanInputData1($crntRow[24])), $orgID);
                            $acbAcntMppdAcnt = trim(cleanInputData1($crntRow[25]));
                            $acbAcntMppdAcntID = getAccntID($acbAcntMppdAcnt, $orgID);

                            if ($z == 0) {
                                if (strtoupper($acbAccountNum) == strtoupper("Account Number**") && strtoupper($acbAccountNumDesc) == strtoupper("Account Name**") && strtoupper($acbAcntMppdAcnt) == strtoupper("Mapped Group Org Account No.")) {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                    //.strtoupper($number) ."|". strtoupper($processName) ."|". strtoupper($isEnbld1 == "IS ENABLED?");
                                    $arr_content['msgcount'] = $total;
                                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_accntsimprt_progress.rho",
                                            json_encode($arr_content));
                                    break;
                                }
                            }

                            $exitErrMsg = "";
                            if ($acbAccountNum == "" || $acbAccountNumDesc == "") {
                                $exitErrMsg .= "Please enter Account Number and Description!<br/>";
                            }
                            if ($acbAcntAcntType == "") {
                                $exitErrMsg .= "Please select an account Type!<br/>";
                            }
                            if ($acbAcntIsRetErngsAcnt == true && $acbAcntIsPrntAcnt == true) {
                                $exitErrMsg .= "A Parent account cannot be used as Retained Earnings Account!<br/>";
                            }
                            if ($acbAcntIsRetErngsAcnt == true && $acbAcntIsContraAcnt == true) {
                                $exitErrMsg .= "A contra account cannot be used as Retained Earnings Account!<br/>";
                            }
                            if ($acbAcntIsRetErngsAcnt == true && $acbAcntIsEnabled == false) {
                                $exitErrMsg .= "A Retained Earnings Account cannot be disabled!<br/>";
                            }

                            if ($acbAcntIsSuspnsAcnt == true && $acbAcntAcntType != "A -ASSET") {
                                $exitErrMsg .= "The account type of the Suspense Account must be ASSET<br/>";
                            }

                            if ($acbAcntIsRetErngsAcnt == true && $acbAcntAcntType != "EQ-EQUITY") {
                                $exitErrMsg .= "The account type of a Retained Earnings Account must be NET WORTH<br/>";
                            }
                            if ($acbAcntIsNetIncmAcnt == true && $acbAcntIsPrntAcnt == true) {
                                $exitErrMsg .= "A Parent account cannot be used as Net Income Account!<br/>";
                            }
                            if ($acbAcntIsNetIncmAcnt == true && $acbAcntIsContraAcnt == true) {
                                $exitErrMsg .= "A contra account cannot be used as Net Income Account!<br/>";
                            }
                            if ($acbAcntIsNetIncmAcnt == true && $acbAcntIsEnabled == false) {
                                $exitErrMsg .= "A Net Income Account cannot be disabled!<br/>";
                            }
                            if ($acbAcntIsNetIncmAcnt == true && $acbAcntAcntType != "EQ-EQUITY") {
                                $exitErrMsg .= "The account type of a Net Income Account must be NET WORTH<br/>";
                            }
                            if ($acbAcntIsRetErngsAcnt == true && $acbAcntIsNetIncmAcnt == true) {
                                $exitErrMsg .= "Same Account cannot be Retained Earnings and Net Income at same time!<br/>";
                            }
                            if ($acbAcntIsRetErngsAcnt == true && $acbAcntHsSubldgrAcnt == true) {
                                $exitErrMsg .= "Retained Earnings account cannot have sub-ledgers!<br/>";
                            }
                            if ($acbAcntIsNetIncmAcnt == true && $acbAcntHsSubldgrAcnt == true) {
                                $exitErrMsg .= "Net Income account cannot have sub-ledgers!<br/>";
                            }
                            if ($acbAcntIsContraAcnt == true && $acbAcntHsSubldgrAcnt == true) {
                                $exitErrMsg .= "The system does not support Sub-Ledgers on Contra-Accounts!<br/>";
                            }
                            if ($acbAcntIsPrntAcnt == true && $acbAcntHsSubldgrAcnt == true) {
                                $exitErrMsg .= "Parent Account cannot have sub-ledgers!<br/>";
                            }
                            if ($acbAcntCtrlAcntID > 0 && $acbAcntHsSubldgrAcnt == true) {
                                $exitErrMsg .= "The system does not support Control Accounts reporting to other Control Account!<br/>";
                            }
                            if ($acbAcntCtrlAcntID > 0 && $acbPrntAccountNumID > 0) {
                                $exitErrMsg .= "An Account with a Control Account cannot have a Parent Account as well!<br/>";
                            }
                            if ($acbAcntCurncyID <= 0) {
                                $exitErrMsg .= "Account Currency Cannot be Empty!<br/>";
                            }
                            if ($acbAcntMppdAcntID > 0) {
                                if (getAccntType($acbAcntMppdAcntID) != trim(substr($acbAcntAcntType, 0, 2))) {
                                    $exitErrMsg .= "Account Type does not match that of the Mapped Account<br/>";
                                }
                            }
                            if ($acbPrntAccountNumID > 0) {
                                if (getAccntType($acbPrntAccountNumID) != trim(substr($acbAcntAcntType, 0, 2))) {
                                    $exitErrMsg .= "Account Type does not match that of the Parent Account<br/>";
                                }
                            }
                            $oldAccntID = getAccntID($acbAccountNum, $orgID);
                            $sbmtdAccountID = $oldAccntID;
                            if ($oldAccntID > 0 && $oldAccntID != $sbmtdAccountID) {
                                $exitErrMsg .= "New Account Number is already in use in this Organization!<br/>";
                            }
                            $oldAccntID2 = getAccntID($acbAccountNumDesc, $orgID);
                            if ($oldAccntID2 > 0 && $oldAccntID2 != $sbmtdAccountID) {
                                $exitErrMsg .= "New Account Name/Description is already in use in this Organization!<br/>";
                            }
                            $accntType = "";
                            if ($acbAcntAcntType != "") {
                                $accntType = trim(substr($acbAcntAcntType, 0, 2));
                            }
                            $oldCmbntnID = getAccountCmbntnID($orgID, $accntSgmnt1ValID, $accntSgmnt2ValID, $accntSgmnt3ValID,
                                    $accntSgmnt4ValID, $accntSgmnt5ValID, $accntSgmnt6ValID, $accntSgmnt7ValID, $accntSgmnt8ValID,
                                    $accntSgmnt9ValID, $accntSgmnt10ValID);
                            if ($oldCmbntnID > 0 && $oldCmbntnID != $sbmtdAccountID) {
                                $exitErrMsg .= "This combination of Segment Values is already present in this Organization!<br/>";
                            }

                            if ($exitErrMsg == "") {
                                if ($sbmtdAccountID <= 0) {
                                    $affctd += createChrt($orgID, $acbAccountNum, $acbAccountNumDesc, $acbAccountNumDesc,
                                            $acbAcntIsContraAcnt, $acbPrntAccountNumID, $accntType, $acbAcntIsPrntAcnt, $acbAcntIsEnabled,
                                            $acbAcntIsRetErngsAcnt, $acbAcntIsNetIncmAcnt, 100, $acbAcntHsSubldgrAcnt, $acbAcntCtrlAcntID,
                                            $acbAcntCurncyID, $acbAcntIsSuspnsAcnt, $acbAcntAcntClsfctn, $accntSgmnt1ValID,
                                            $accntSgmnt2ValID, $accntSgmnt3ValID, $accntSgmnt4ValID, $accntSgmnt5ValID, $accntSgmnt6ValID,
                                            $accntSgmnt7ValID, $accntSgmnt8ValID, $accntSgmnt9ValID, $accntSgmnt10ValID, $acbAcntMppdAcntID);
                                    $sbmtdAccountID = getAccntID($acbAccountNum, $orgID);
                                } else if ($sbmtdAccountID > 0) {
                                    $affctd += updateChrtDet($orgID, $sbmtdAccountID, $acbAccountNum, $acbAccountNumDesc,
                                            $acbAccountNumDesc, $acbAcntIsContraAcnt, $acbPrntAccountNumID, $accntType, $acbAcntIsPrntAcnt,
                                            $acbAcntIsEnabled, $acbAcntIsRetErngsAcnt, $acbAcntIsNetIncmAcnt, 100, $acbAcntHsSubldgrAcnt,
                                            $acbAcntCtrlAcntID, $acbAcntCurncyID, $acbAcntIsSuspnsAcnt, $acbAcntAcntClsfctn,
                                            $accntSgmnt1ValID, $accntSgmnt2ValID, $accntSgmnt3ValID, $accntSgmnt4ValID, $accntSgmnt5ValID,
                                            $accntSgmnt6ValID, $accntSgmnt7ValID, $accntSgmnt8ValID, $accntSgmnt9ValID, $accntSgmnt10ValID,
                                            $acbAcntMppdAcntID);
                                }
                            }
                        }
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . $affctd . " out of " . $total . " Account(s) imported.";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Accounts...Please Wait..." . ($z + 1) . " out of " . $total . " Account(s) imported.";
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_accntsimprt_progress.rho",
                                json_encode($arr_content));
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_accntsimprt_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 102) {
                //Checked Importing Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_accntsimprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => null, "message" => null));
                }
            } else if ($actyp == 2) {
                //Save Journal Entries
                var_dump($_POST);
                exit();
            } else if ($actyp == 3) {
                //Export Accounts
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array("Account Number**", "Account Name**", "Account Description", "Account Type**", "Parent Account Name",
                        "Is Parent?(YES/NO)", "Is Retained Earnings?(YES/NO)", "Is Net Income Account?(YES/NO)", "Is Contra Account?(YES/NO)",
                        "Reporting Line No.", "Has SubLedgers?(YES/NO)", "Control Account Name", "Account Currency Code**",
                        "Is Suspense Account?(YES/NO)", "Account Classification", "Segment 1 Value", "Segment 2 Value", "Segment 3 Value", "Segment 4 Value"
                        , "Segment 5 Value", "Segment 6 Value", "Segment 7 Value", "Segment 8 Value", "Segment 9 Value", "Segment 10 Value",
                        "Mapped Group Org Account No.");
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/AccntChartExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/AccntChartExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Accounts Chart Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccntChartexprt_progress.rho",
                                json_encode($arr_content));

                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_ChartsToExprt($orgID, $limit_size);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        $crntRw = array("" . $row[0], $row[1], $row[2], getFullAcntType($row[3]), $row[4], cnvrtBitStrToYN($row[5]), cnvrtBitStrToYN($row[6]),
                            cnvrtBitStrToYN($row[7]), cnvrtBitStrToYN($row[8]), $row[9], cnvrtBitStrToYN($row[10]), $row[11],
                            $row[12], cnvrtBitStrToYN($row[13]), $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21],
                            $row[22], $row[23], $row[24], $row[25]);
                        fputcsv($opndfile, $crntRw);
                        //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                    1) . " out of " . $total . " Account(s) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Accounts...Please Wait..." . ($z +
                                    1) . " out of " . $total . " Account(s) exported.</span>";
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccntChartexprt_progress.rho",
                                json_encode($arr_content));
                        $z++;
                    }
                    if ($z == 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccntChartexprt_progress.rho",
                            json_encode($arr_content)
                        );
                    }
                    fclose($opndfile);
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccntChartexprt_progress.rho",
                            json_encode($arr_content));
                }
            } else if ($actyp == 4) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccntChartexprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => 0, "message" => '<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Not Started</span>'));
                }
            }
        } else {
            if ($vwtyp == 0) {
                //Chart of Accounts
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Chart of Accounts</span>
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
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Basic_ChrtTtls($srchFor, $srchIn, $orgID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_Basic_ChrtDet($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-6";

                    //Auto-Correct Gl Imbalances
                    $reportTitle1 = "Auto-Correct Gl Imbalances";
                    $reportName1 = "Auto-Correct Gl Imbalances";
                    $rptID1 = getRptID($reportName1);
                    ?> 
                    <form id='accbAcntChrtForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID"/>                     
                        <fieldset class="">
                            <legend class="basic_person_lg1" style="color: #003245">CHART OF ACCOUNTS</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                if ($canAdd === true) {
                                    ?>   
                                    <div class="col-md-4" style="padding:0px 1px 0px 15px !important;">                    
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getAccountsDetForm(-1, 'New Accounts Detail Information', 'ShowDialog', function () {
                                                                    getAccbAcntChrt('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');
                                                                });">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New GL Account
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="exprtAccntChart();" style="">
                                            <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Export
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="importAccntChart();" style="">
                                            <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Import
                                        </button>
                                    </div>
                                    <?php
                                } else {
                                    $colClassType1 = "col-md-2";
                                    $colClassType2 = "col-md-6";
                                }
                                ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="accbAcntChrtSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncAccbAcntChrt(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                        <input id="accbAcntChrtPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbAcntChrt('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbAcntChrt('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbAcntChrtSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "");
                                            $srchInsArrys = array("All", "Account Number", "Parent Account Details");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="accbAcntChrtDsplySze" style="min-width:70px !important;">                            
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
                                                <a href="javascript:getAccbAcntChrt('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getAccbAcntChrt('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>        
                            <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                                <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                    <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon"  style="<?php echo $breadCrmbBckclr; ?>">
                                                <span style="font-weight:bold;<?php echo $forecolors; ?>">A + E:</span>
                                            </label>
                                            <?php
                                            $aedffrc = get_COA_AESum($orgID);
                                            $style1 = "color:green;";
                                            ?>
                                            <input class="form-control" id="allVmsGLIntrfcsImbalsAmt" type = "text" placeholder="0.00" value="<?php
                                            echo number_format($aedffrc, 2);
                                            ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon" style="<?php echo $breadCrmbBckclr; ?>">
                                                <span style="font-weight:bold;<?php echo $forecolors; ?>">L + EQ + R:</span>
                                            </label>
                                            <?php
                                            $lerdffrc = get_COA_CRLSum($orgID);
                                            $style1 = "color:green;";
                                            ?>
                                            <input class="form-control" id="allVmsGLIntrfcsImbalsAmt" type = "text" placeholder="0.00" value="<?php
                                            echo number_format($lerdffrc, 2);
                                            ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon"  style="<?php echo $breadCrmbBckclr; ?>">
                                                <span style="font-weight:bold;<?php echo $forecolors; ?>">Difference:</span>
                                            </label>
                                            <?php
                                            $dffrc = $aedffrc - $lerdffrc;
                                            $style1 = "color:green;";
                                            if (abs($dffrc) != 0) {
                                                $style1 = "color:red;";
                                            }
                                            ?>
                                            <input class="form-control" id="allVmsGLIntrfcsImbalsAmt" type = "text" placeholder="0.00" value="<?php
                                            echo number_format($dffrc, 2);
                                            ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-1" style="padding:5px 1px 0px 1px !important;">                
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID1; ?>');" title="Auto-Correct GL Imbalance">
                                            <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">                                            
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="accbAcntChrtHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:25px;width:25px;">No.</th>
                                                <th style="max-width:20px;width:20px;">...</th>
                                                <th>Account Number/Name</th>	
                                                <th>Account Type</th>
                                                <th style="max-width:25px;width:25px;">CUR.</th>	
                                                <th style="text-align:right;display:none;">Debit Balance</th>
                                                <th style="text-align:right;display:none;">Credit Balance</th>
                                                <th style="text-align:right;max-width:100px;width:100px;">Net Balance</th>
                                                <th style="min-width:120px;width:120px;">Bals. Date</th>
                                                <th style="max-width:60px;width:60px;">Enabled?</th>
                                                <?php if ($canDel === true) { ?>
                                                    <th style="max-width:20px;width:20px;">...</th>
                                                <?php } ?>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th style="max-width:20px;width:20px;">...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="accbAcntChrtHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Account" 
                                                                onclick="getAccountsDetForm(<?php echo $row[0]; ?>, 'Accounts Detail Information', 'ShowDialog', function () {
                                                                                                    getAccbAcntChrt('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');
                                                                                                });" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                                <?php
                                                                if ($canAdd === true) {
                                                                    ?>                                
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                                                        <?php
                                                        echo str_replace(" ", "&nbsp;", str_replace("   ", " ", $row[3]));
                                                        ?>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[5]; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $row[17]; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;display:none;"><?php
                                                        echo number_format((float) $row[13], 2);
                                                        ?>
                                                    </td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;display:none;"><?php
                                                        echo number_format((float) $row[14], 2);
                                                        ?>
                                                    </td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><a href="javascript:getAccbTransSrchDet('<?php echo $row[1]; ?>', 'Account Number', true, false, '', '', 'View Account Transactions', 'ShowDialog', function () {});"><?php
                                                            echo number_format((float) $row[16], 2);
                                                            ?></a>
                                                    </td>
                                                    <td class="lovtd" style=""><?php echo $row[12]; ?></td> 
                                                    <td class="lovtd"><?php echo $row[15] == "1" ? "YES" : "NO"; ?></td>                                                      
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Account" onclick="delAccbAcntChrt('accbAcntChrtHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="accbAcntChrtHdrsRow<?php echo $cntr; ?>_HdrID" name="accbAcntChrtHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|accb.accb_chart_of_accnts|accnt_id"),
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
                                    </table>
                                </div>                     
                            </div>
                        </fieldset>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 1) {
                //echo "Accounts Details Form";
                $sbmtdAccountID = isset($_POST['sbmtdAccountID']) ? $_POST['sbmtdAccountID'] : -1;
                if (!$canAdd || ($sbmtdAccountID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdAccountID;
                $accntSgmnt1ValID = -1;
                $accntSgmnt2ValID = -1;
                $accntSgmnt3ValID = -1;
                $accntSgmnt4ValID = -1;
                $accntSgmnt5ValID = -1;
                $accntSgmnt6ValID = -1;
                $accntSgmnt7ValID = -1;
                $accntSgmnt8ValID = -1;
                $accntSgmnt9ValID = -1;
                $accntSgmnt10ValID = -1;
                $acbAccountNum = "";
                $acbAccountNumDesc = "";
                $acbPrntAccountNumID = -1;
                $acbPrntAccountNum = "";
                $acbAcntIsEnabled = "1";
                $acbAcntIsPrntAcnt = "0";
                $acbAcntIsContraAcnt = "0";
                $acbAcntIsRetErngsAcnt = "0";
                $acbAcntIsNetIncmAcnt = "0";
                $acbAcntIsSuspnsAcnt = "0";
                $acbAcntHsSubldgrAcnt = "0";
                $acbAcntAcntType = "";
                $acbAcntAcntClsfctn = "";
                $acbAcntCtrlAcntID = -1;
                $acbAcntCtrlAcnt = "";
                $acbAcntMppdAcntID = -1;
                $acbAcntMppdAcnt = "";
                $acbAcntCurncyID = -1;
                $acbAcntCurncy = "";
                $sbmtdAccbGrpOrgID = getGrpOrgID();
                $result1 = get_One_Chrt_Det($sbmtdAccountID);
                while ($row1 = loc_db_fetch_array($result1)) {
                    $sbmtdAccountID = (int) $row1[0];
                    $acbAccountNum = $row1[1];
                    $acbAccountNumDesc = $row1[2];
                    $acbPrntAccountNumID = (int) $row1[5];
                    $acbPrntAccountNum = $row1[6];
                    $acbAcntIsEnabled = $row1[17];
                    $acbAcntIsPrntAcnt = $row1[14];
                    $acbAcntIsContraAcnt = $row1[4];
                    $acbAcntIsRetErngsAcnt = $row1[19];
                    $acbAcntIsNetIncmAcnt = $row1[20];
                    $acbAcntIsSuspnsAcnt = $row1[28];
                    $acbAcntHsSubldgrAcnt = $row1[23];
                    $acbAcntAcntType = getFullAcctType($row1[13]);
                    $acbAcntAcntClsfctn = $row1[29];
                    $acbAcntCtrlAcntID = (int) $row1[24];
                    $acbAcntCtrlAcnt = $row1[24];
                    $acbAcntMppdAcntID = (int) $row1[40];
                    $acbAcntMppdAcnt = $row1[41];
                    $acbAcntCurncyID = (int) $row1[26];
                    $acbAcntCurncy = $row1[27];
                    $accntSgmnt1ValID = (int) $row1[30];
                    $accntSgmnt2ValID = (int) $row1[31];
                    $accntSgmnt3ValID = (int) $row1[32];
                    $accntSgmnt4ValID = (int) $row1[33];
                    $accntSgmnt5ValID = (int) $row1[34];
                    $accntSgmnt6ValID = (int) $row1[35];
                    $accntSgmnt7ValID = (int) $row1[36];
                    $accntSgmnt8ValID = (int) $row1[37];
                    $accntSgmnt9ValID = (int) $row1[38];
                    $accntSgmnt10ValID = (int) $row1[39];
                }
                ?>
                <form class="form-horizontal" id="accbAccntsDetForm">
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-4" style="padding:0px 0px 0px 0px !important;">
                            <div class="" style="padding:0px 0px 0px 0px;float:left !important;"> 
                                <input type="hidden" class="form-control" aria-label="..." id="sbmtdAccbGrpOrgID" value="<?php echo $sbmtdAccbGrpOrgID; ?>">
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveAccountsDetForm();"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button> 
                            </div>
                        </div>                    
                    </div>
                    <div class="row">
                        <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbAccountNum" class="control-label col-lg-4">Account Number:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="acbAccountNum" name="acbAccountNum" value="<?php echo $acbAccountNum; ?>" style="width:100%;">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accntSgmnt1ValID" name="accntSgmnt1ValID" value="<?php echo $accntSgmnt1ValID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accntSgmnt2ValID" name="accntSgmnt2ValID" value="<?php echo $accntSgmnt2ValID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accntSgmnt3ValID" name="accntSgmnt3ValID" value="<?php echo $accntSgmnt3ValID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accntSgmnt4ValID" name="accntSgmnt4ValID" value="<?php echo $accntSgmnt4ValID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accntSgmnt5ValID" name="accntSgmnt5ValID" value="<?php echo $accntSgmnt5ValID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accntSgmnt6ValID" name="accntSgmnt6ValID" value="<?php echo $accntSgmnt6ValID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accntSgmnt7ValID" name="accntSgmnt7ValID" value="<?php echo $accntSgmnt7ValID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accntSgmnt8ValID" name="accntSgmnt8ValID" value="<?php echo $accntSgmnt8ValID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accntSgmnt9ValID" name="accntSgmnt9ValID" value="<?php echo $accntSgmnt9ValID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="accntSgmnt10ValID" name="accntSgmnt10ValID" value="<?php echo $accntSgmnt10ValID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcntSgmtBrkdwnForm(<?php echo $sbmtdAccountID; ?>, 2, 'accntSgmnt', 'acbAccountNum', 'acbAccountNumDesc');">
                                                        <span class="glyphicon glyphicon-th-list"></span>&nbsp;Account Segments
                                                    </label>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $acbAccountNum; ?></span>
                                                <?php
                                            }
                                            ?>
                                            <input type="hidden" class="form-control" aria-label="..." id="sbmtdAccountID" name="sbmtdAccountID" value="<?php echo $sbmtdAccountID; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbAccountNumDesc" class="control-label col-lg-4">Account Description:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdt === true) { ?>
                                                <textarea class="form-control" aria-label="..." id="acbAccountNumDesc" name="acbAccountNumDesc" style="width:100%;" cols="3" rows="3"><?php echo $acbAccountNumDesc; ?></textarea>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $acbAccountNumDesc; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbAcntAcntClsfctn" class="control-label col-lg-4">Account Classification:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdt === true) { ?>
                                                <select class="form-control" id="acbAcntAcntClsfctn" onchange="">
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "", "",
                                                        "", "", "", "", "", "", "", "",
                                                        "", "", "", "", "", "", "", "",
                                                        "", "", "", "");
                                                    $valuesArrys = array("Cash and Cash Equivalents",
                                                        "Operating Activities.Sale of Goods",
                                                        "Operating Activities.Sale of Services",
                                                        "Operating Activities.Other Income Sources",
                                                        "Operating Activities.Cost of Sales",
                                                        "Operating Activities.Net Income",
                                                        "Operating Activities.Depreciation Expense",
                                                        "Operating Activities.Amortization Expense",
                                                        "Operating Activities.Gain on Sale of Asset"/* NEGATE */,
                                                        "Operating Activities.Loss on Sale of Asset",
                                                        "Operating Activities.Other Non-Cash Expense",
                                                        "Operating Activities.Accounts Receivable"/* NEGATE */,
                                                        "Operating Activities.Bad Debt Expense"/* NEGATE */,
                                                        "Operating Activities.Prepaid Expenses"/* NEGATE */,
                                                        "Operating Activities.Inventory"/* NEGATE */,
                                                        "Operating Activities.Accounts Payable",
                                                        "Operating Activities.Accrued Expenses",
                                                        "Operating Activities.Taxes Payable",
                                                        "Operating Activities.Operating Expense"/* NEGATE */,
                                                        "Operating Activities.General and Administrative Expense"/* NEGATE */,
                                                        "Investing Activities.Asset Sales/Purchases"/* NEGATE */,
                                                        "Investing Activities.Equipment Sales/Purchases"/* NEGATE */,
                                                        "Financing Activities.Capital/Stock",
                                                        "Financing Activities.Long Term Debts",
                                                        "Financing Activities.Short Term Debts",
                                                        "Financing Activities.Equity Securities",
                                                        "Financing Activities.Dividends Declared"/* NEGATE */,
                                                        "");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($acbAcntAcntClsfctn == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $acbAcntAcntClsfctn; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbAcntCurncy" class="control-label col-lg-4">Account Currency:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="acbAcntCurncy" name="acbAcntCurncy" value="<?php echo $acbAcntCurncy; ?>" readonly="true">
                                                    <input type="hidden" class="form-control" aria-label="..." id="acbAcntCurncyID" name="acbAcntCurncyID" value="<?php echo $acbAcntCurncyID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $acbAcntCurncyID; ?>', 'acbAcntCurncy', '', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $acbAcntCurncy; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbAcntIsEnabled" class="control-label col-lg-4">Is Enabled?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($acbAcntIsEnabled == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdt === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="acbAcntIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="acbAcntIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($acbAcntIsEnabled == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;">
                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbAcntIsPrntAcnt" class="control-label col-lg-4">Parent Account?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($acbAcntIsPrntAcnt == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdt === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="acbAcntIsPrntAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="acbAcntIsPrntAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($acbAcntIsPrntAcnt == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbAcntIsContraAcnt" class="control-label col-lg-4">Contra Account?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($acbAcntIsContraAcnt == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdt === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="acbAcntIsContraAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="acbAcntIsContraAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($acbAcntIsContraAcnt == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbAcntIsRetErngsAcnt" class="control-label col-lg-4">Retained Earnings A/c?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($acbAcntIsRetErngsAcnt == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdt === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="acbAcntIsRetErngsAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="acbAcntIsRetErngsAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($acbAcntIsRetErngsAcnt == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>   
                                    <div class="form-group form-group-sm col-md-12" style="padding:5px 3px 0px 3px !important;">
                                        <label for="acbAcntAcntType" class="control-label col-lg-4">Account Type:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdt === true) { ?>
                                                <select class="form-control" id="acbAcntAcntType" onchange="">
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "");
                                                    $valuesArrys = array("A -ASSET", "EQ-EQUITY", "L -LIABILITY", "R -REVENUE", "EX-EXPENSE");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if ($acbAcntAcntType == $valuesArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $acbAcntAcntType; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbPrntAccountNum" class="control-label col-lg-4">Parent Account:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="acbPrntAccountNum" name="acbPrntAccountNum" value="<?php echo $acbPrntAccountNum; ?>" readonly="true">
                                                    <input type="hidden" class="form-control" aria-label="..." id="acbPrntAccountNumID" name="acbPrntAccountNumID" value="<?php echo $acbPrntAccountNumID; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="acbAcntAcntTypeShrt" name="acbAcntAcntTypeShrt" value="<?php
                                                    echo trim(substr($acbAcntAcntType, 0, 2));
                                                    ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Parent Accounts', 'allOtherInputOrgID', 'acbAcntAcntTypeShrt', '', 'radio', true, '<?php echo $acbPrntAccountNumID; ?>', 'acbPrntAccountNumID', 'acbPrntAccountNum', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $acbPrntAccountNum; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbAcntIsNetIncmAcnt" class="control-label col-lg-4">Net Income Account?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($acbAcntIsNetIncmAcnt == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdt === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="acbAcntIsNetIncmAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="acbAcntIsNetIncmAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($acbAcntIsNetIncmAcnt == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbAcntIsSuspnsAcnt" class="control-label col-lg-4">Suspense Account?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($acbAcntIsSuspnsAcnt == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdt === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="acbAcntIsSuspnsAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="acbAcntIsSuspnsAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($acbAcntIsSuspnsAcnt == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbAcntHsSubldgrAcnt" class="control-label col-lg-4">Has Subledgers?:</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($acbAcntHsSubldgrAcnt == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdt === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="acbAcntHsSubldgrAcnt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="acbAcntHsSubldgrAcnt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else { ?>
                                                <span><?php echo ($acbAcntHsSubldgrAcnt == "1" ? "YES" : "NO"); ?></span>
                                            <?php }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:5px 3px 0px 3px !important;">
                                        <label for="acbAcntCtrlAcnt" class="control-label col-lg-4">Control Account:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="acbAcntCtrlAcnt" name="acbAcntCtrlAcnt" value="<?php echo $acbAcntCtrlAcnt; ?>" readonly="true">
                                                    <input type="hidden" class="form-control" aria-label="..." id="acbAcntCtrlAcntID" name="acbAcntCtrlAcntID" value="<?php echo $acbAcntCtrlAcntID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Control Accounts', 'allOtherInputOrgID', 'acbAcntAcntTypeShrt', '', 'radio', true, '<?php echo $acbAcntCtrlAcntID; ?>', 'acbAcntCtrlAcntID', 'acbAcntCtrlAcnt', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $acbAcntCtrlAcnt; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="acbAcntMppdAcnt" class="control-label col-lg-4">Mapped Group Account:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="acbAcntMppdAcnt" name="acbAcntMppdAcnt" value="<?php echo $acbAcntMppdAcnt; ?>" readonly="true">
                                                    <input type="hidden" class="form-control" aria-label="..." id="acbAcntMppdAcntID" name="acbAcntMppdAcntID" value="<?php echo $acbAcntMppdAcntID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'sbmtdAccbGrpOrgID', '', '', 'radio', true, '<?php echo $acbAcntMppdAcntID; ?>', 'acbAcntMppdAcntID', 'acbAcntMppdAcnt', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $acbAcntMppdAcnt; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <?php
                        $nwRowHtml = urlencode("<tr id=\"acbRptClsfctnsRow__WWW123WWW\" class=\"hand_cursor\">
                                                    <td class=\"lovtd\">New
                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"acbRptClsfctnsRow_WWW123WWW_ClsfctnID\" value=\"-1\">
                                                    </td>
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-left:0px !important;\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"acbRptClsfctnsRow_WWW123WWW_MajClsfctn\" value=\"\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '', '', 'acbRptClsfctnsRow_WWW123WWW_MajClsfctn', 'clear', 0, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-left:0px !important;\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"acbRptClsfctnsRow_WWW123WWW_MinClsfctn\" value=\"\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '', '', 'acbRptClsfctnsRow_WWW123WWW_MinClsfctn', 'clear', 0, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                    </td>
                                                      <td class=\"lovtd\">
                                                          <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAcbRptClsfctn('acbRptClsfctnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Report Classification\">
                                                              <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                          </button>
                                                      </td>
                                                      <td class=\"lovtd\">&nbsp;</td>
                                                </tr>");
                        ?>
                        <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                            <fieldset class="basic_person_fs">
                                <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('acbRptClsfctnsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Report Classification
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAccountsDetForm();" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save
                                    </button>
                                </div>
                                <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <table class="table table-striped table-bordered table-responsive" id="acbRptClsfctnsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:30px;width:30px;">No.</th>
                                                <th>Main Reporting Category</th>
                                                <th>Sub-Reporting Category</th>
                                                <th style="max-width:20px;width:20px;">...</th>
                                                <th style="max-width:20px;width:20px;">...</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $resultClsfctn = get_One_AccntRptClsfctns($sbmtdAccountID);
                                            $cntr = 0;
                                            $curIdx = 0;
                                            $lmtSze = 100;
                                            while ($row1 = loc_db_fetch_array($resultClsfctn)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="acbRptClsfctnsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="acbRptClsfctnsRow<?php echo $cntr; ?>_ClsfctnID" value="<?php echo $row1[0]; ?>">
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdt === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-left:0px !important;">
                                                                <div class="input-group"  style="width:100%;">
                                                                    <input type="text" class="form-control" aria-label="..." id="acbRptClsfctnsRow<?php echo $cntr; ?>_MajClsfctn" value="<?php echo $row1[1]; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '<?php echo $row1[1]; ?>', '', 'acbRptClsfctnsRow<?php echo $cntr; ?>_MajClsfctn', 'clear', 0, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row1[1]; ?></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdt === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-left:0px !important;">
                                                                <div class="input-group"  style="width:100%;">
                                                                    <input type="text" class="form-control" aria-label="..." id="acbRptClsfctnsRow<?php echo $cntr; ?>_MinClsfctn" value="<?php echo $row1[2]; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Classifications', '', '', '', 'radio', true, '<?php echo $row1[2]; ?>', '', 'acbRptClsfctnsRow<?php echo $cntr; ?>_MinClsfctn', 'clear', 0, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row1[2]; ?></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canDel === true) { ?>
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcbRptClsfctn('acbRptClsfctnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Report Classifications">
                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canVwRcHstry === true) { ?>
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row1[0] . "|accb.accb_account_clsfctns|account_clsfctn_id"),
                                                                            $smplTokenWord1));
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
                </form>
                <?php
            } else if ($vwtyp == 2) {
                $sbmtdAccountID = isset($_POST['sbmtdAccountID']) ? cleanInputData($_POST['sbmtdAccountID']) : -1;
                $accntSgmnt1ValID = -1;
                $accntSgmnt2ValID = -1;
                $accntSgmnt3ValID = -1;
                $accntSgmnt4ValID = -1;
                $accntSgmnt5ValID = -1;
                $accntSgmnt6ValID = -1;
                $accntSgmnt7ValID = -1;
                $accntSgmnt8ValID = -1;
                $accntSgmnt9ValID = -1;
                $accntSgmnt10ValID = -1;

                $slctdSgmntValIDs = isset($_POST['slctdSgmntValIDs']) ? cleanInputData($_POST['slctdSgmntValIDs']) : '';
                $sgValElmntIDPrfx = isset($_POST['sgValElmntIDPrfx']) ? cleanInputData($_POST['sgValElmntIDPrfx']) : '';
                $accntNumElmntID = isset($_POST['accntNumElmntID']) ? cleanInputData($_POST['accntNumElmntID']) : '';
                $accntNameElmntID = isset($_POST['accntNameElmntID']) ? cleanInputData($_POST['accntNameElmntID']) : '';
                if (trim($slctdSgmntValIDs, "|") != "") {
                    $crntRow = explode("|", trim($slctdSgmntValIDs, "|"));
                    if (count($crntRow) == 10) {
                        $accntSgmnt1ValID = (int) (cleanInputData1($crntRow[0]));
                        $accntSgmnt2ValID = (int) (cleanInputData1($crntRow[1]));
                        $accntSgmnt3ValID = (int) (cleanInputData1($crntRow[2]));
                        $accntSgmnt4ValID = (int) (cleanInputData1($crntRow[3]));
                        $accntSgmnt5ValID = (int) (cleanInputData1($crntRow[4]));
                        $accntSgmnt6ValID = (int) (cleanInputData1($crntRow[5]));
                        $accntSgmnt7ValID = (int) (cleanInputData1($crntRow[6]));
                        $accntSgmnt8ValID = (int) (cleanInputData1($crntRow[7]));
                        $accntSgmnt9ValID = (int) (cleanInputData1($crntRow[8]));
                        $accntSgmnt10ValID = (int) (cleanInputData1($crntRow[9]));
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="accntSgmntsBrkDwnTable" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th style="max-width:20px;width:20px;text-align: center;">No.</th>
                                    <th style="max-width:70px;width:70px;">Segment Name</th>
                                    <th>Segment Value/Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                $rwcnt = get_SegmnetsTtl($orgID);
                                for ($cntr = 0; $cntr < $rwcnt; $cntr++) {
                                    $sgmntNumber = ($cntr + 1);
                                    $prntSgmntNumber = -1;
                                    $result = get_One_SegmentDet($sgmntNumber, $orgID);
                                    $sgmntID = -1;
                                    $sgmntName = "";
                                    $sgmntClsfctn = "";
                                    $dpndntSgmntID = -1;
                                    if ($rw = loc_db_fetch_array($result)) {
                                        $sgmntID = (int) $rw[0];
                                        $sgmntName = $rw[1];
                                        $sgmntClsfctn = $rw[2];
                                        $dpndntSgmntID = (int) $rw[3];
                                        $prntSgmntNumber = (int) $rw[4];
                                    }
                                    $segVID = -1;
                                    switch ($sgmntNumber) {
                                        case 1:
                                            $segVID = $accntSgmnt1ValID;
                                            break;
                                        case 2:
                                            $segVID = $accntSgmnt2ValID;
                                            break;
                                        case 3:
                                            $segVID = $accntSgmnt3ValID;
                                            break;
                                        case 4:
                                            $segVID = $accntSgmnt4ValID;
                                            break;
                                        case 5:
                                            $segVID = $accntSgmnt5ValID;
                                            break;
                                        case 6:
                                            $segVID = $accntSgmnt6ValID;
                                            break;
                                        case 7:
                                            $segVID = $accntSgmnt7ValID;
                                            break;
                                        case 8:
                                            $segVID = $accntSgmnt8ValID;
                                            break;
                                        case 9:
                                            $segVID = $accntSgmnt9ValID;
                                            break;
                                        case 10:
                                            $segVID = $accntSgmnt10ValID;
                                            break;
                                    }
                                    $dpndntSgmntValID = getSegmentDpndntValID($segVID);
                                    $segVal = getSegmentVal($segVID);
                                    $segValDesc = getSegmentValDesc($segVID);
                                    $childSgmntNumber = getSegmentChildSegNum($sgmntID);
                                    ?>
                                    <tr id="accntSgmntsBrkDwn_<?php echo ($cntr); ?>">                                    
                                        <td class="lovtd" style="max-width:20px;width:20px;text-align: center;"><span><?php echo $sgmntNumber; ?></span></td>
                                        <td class="lovtd">
                                            <span><?php echo $sgmntName; ?></span>
                                            <input type="hidden" class="form-control" aria-label="..." id="accntSgmntsBrkDwn<?php echo $cntr; ?>_SegmentID" value="<?php echo $sgmntID; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="accntSgmntsBrkDwn<?php echo $cntr; ?>_SegmentNum" value="<?php echo $sgmntNumber; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="accntSgmntsBrkDwn<?php echo $cntr; ?>_DpndntSegmentID" value="<?php echo $dpndntSgmntID; ?>" style="width:100% !important;">    
                                            <input type="hidden" class="form-control" aria-label="..." id="accntSgmntsBrkDwn<?php echo $cntr; ?>_IsEnabled" value="1" style="width:100% !important;">    
                                            <input type="hidden" class="form-control" aria-label="..." id="accntSgmntsBrkDwn<?php echo $cntr; ?>_DpndntSegmentValID" value="<?php echo $dpndntSgmntValID; ?>" style="width:100% !important;">                                                                                              
                                        </td>
                                        <td class="lovtd">
                                            <?php if ($canEdt === true) { ?>
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-left:0px !important;">
                                                    <div class="input-group"  style="width:100%;">
                                                        <input type="text" class="form-control" aria-label="..." id="accntSgmntsBrkDwn<?php echo $cntr; ?>_SegVal" value="<?php echo $segVal . "." . $segValDesc; ?>" readonly="true">
                                                        <input type="hidden" class="form-control" aria-label="..." id="accntSgmntsBrkDwn<?php echo $cntr; ?>_SegValID" value="<?php echo $segVID; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="accntSgmntsBrkDwn<?php echo $cntr; ?>_SegValue" value="<?php echo $segVal; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="accntSgmntsBrkDwn<?php echo $cntr; ?>_SegValueDesc" value="<?php echo $segValDesc; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="clearAcntSgmtBrkdwn('accntSgmntsBrkDwn_<?php echo ($cntr); ?>');" data-toggle="tooltip" title="Clear Segment Value">
                                                            <span class="glyphicon glyphicon-remove"></span>
                                                        </label>
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getSegmentValuesForm(<?php echo $sgmntID; ?>, 'All Segment Values', 'ShowDialog', function () {
                                                                                            var a = 123;
                                                                                        });" data-toggle="tooltip" title="Create New Segment Value">
                                                            <span class="glyphicon glyphicon-plus"></span>
                                                        </label>
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Account Segment Value IDs',
                                                                                                'accntSgmntsBrkDwn<?php echo $cntr; ?>_SegmentID', 'accntSgmntsBrkDwn<?php echo $cntr; ?>_IsEnabled',
                                                                                                '<?php
                                                        if ($prntSgmntNumber > 0) {
                                                            echo 'accntSgmntsBrkDwn' . ($prntSgmntNumber - 1) . '_SegValID';
                                                        } else {
                                                            echo '';
                                                        }
                                                        ?>', 'radio', true, '<?php echo $segVID; ?>', 'accntSgmntsBrkDwn<?php echo $cntr; ?>_SegValID', 'accntSgmntsBrkDwn<?php echo $cntr; ?>_SegVal', 'clear', 0, '', function () {
                                                                                                    $('#accntSgmntsBrkDwn<?php echo $cntr; ?>_SegValue').val($('#accntSgmntsBrkDwn<?php echo $cntr; ?>_SegVal').val().split(':')[0]);
                                                                                                    $('#accntSgmntsBrkDwn<?php echo $cntr; ?>_SegValueDesc').val($('#accntSgmntsBrkDwn<?php echo $cntr; ?>_SegVal').val().split(':')[1]);
                                                                                                    $('#accntSgmntsBrkDwn<?php echo $cntr; ?>_SegVal').val($('#accntSgmntsBrkDwn<?php echo $cntr; ?>_SegVal').val().replace(/(:)/g, '.'));
                                                        <?php
                                                        if ($childSgmntNumber > 0) {
                                                            ?>
                                                                                                        clearAcntSgmtBrkdwn('accntSgmntsBrkDwn_<?php echo ($childSgmntNumber - 1); ?>');
                                                        <?php } ?>
                                                                                                });">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <span class=""><?php echo $segVal . "." . $segValDesc; ?></span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div style="float:right;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="applyNewAcntSgmtBrkdwn('myFormsModalx', '<?php echo $sgValElmntIDPrfx; ?>', '<?php echo $accntNumElmntID; ?>', '<?php echo $accntNameElmntID; ?>');">Apply Selection</button>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
}
?>