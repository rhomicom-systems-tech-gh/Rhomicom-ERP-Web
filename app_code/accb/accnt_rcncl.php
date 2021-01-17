<?php
$canview = test_prmssns($dfltPrvldgs[36], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);
$defaultBrkdwnLOV = "";

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Reconciliation Hdr */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo delBnkStmntTrans($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                header("content-type:application/json");
                //Mark Unmark Selected Trans as Selected
                $rcnclAccntID = isset($_POST['rcnclAccntID']) ? (int) cleanInputData($_POST['rcnclAccntID']) : -1;
                $slctdLineTrans = isset($_POST['slctdLineTrans']) ? cleanInputData($_POST['slctdLineTrans']) : '';

                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdLineTrans, "|~") != "") {
                    $variousRows = explode("|", trim($slctdLineTrans, "|"));
                    for ($y = 0; $y < count($variousRows); $y++) {
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 3) {
                            $ln_TransLineID = (float) (cleanInputData1($crntRow[0]));
                            $ln_IsRcncld = (cleanInputData1($crntRow[1]));
                            $ln_AccntID = cleanInputData1($crntRow[2]);
                            if ($ln_TransLineID > 0) {
                                $afftctd1 += changeReconciledStatus($ln_TransLineID, $ln_IsRcncld);
                            }
                        }
                    }
                }
                $arr_content['percent'] = 100;
                $arr_content['rcnclAccntID'] = $rcnclAccntID;
                $arr_content['message'] = $afftctd1 . " Transaction Status(es) Successfully Modified";
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 2) {
                header("content-type:application/json");
                //Mark Unmark Selected Imported Trans as Selected
                $rcnclAccntID = isset($_POST['rcnclAccntID']) ? (int) cleanInputData($_POST['rcnclAccntID']) : -1;
                $slctdLineTrans = isset($_POST['slctdLineTrans']) ? cleanInputData($_POST['slctdLineTrans']) : '';

                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdLineTrans, "|~") != "") {
                    $variousRows = explode("|", trim($slctdLineTrans, "|"));
                    for ($y = 0; $y < count($variousRows); $y++) {
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 3) {
                            $ln_TransLineID = (float) (cleanInputData1($crntRow[0]));
                            $ln_IsRcncld = (cleanInputData1($crntRow[1]));
                            $ln_AccntID = cleanInputData1($crntRow[2]);
                            if ($ln_TransLineID > 0) {
                                $afftctd1 += changeImprtdReconciledStatus($ln_TransLineID, $ln_IsRcncld);
                            }
                        }
                    }
                }
                $arr_content['percent'] = 100;
                $arr_content['rcnclAccntID'] = $rcnclAccntID;
                $arr_content['message'] = $afftctd1 . " Transaction Status(es) Successfully Modified";
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 901) {
                //Import Bank Statement                
                $rcnclAccntID = isset($_POST['rcnclAccntID']) ? (int) cleanInputData($_POST['rcnclAccntID']) : -1;
                $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : '';
                $accbEndFSRptDte = isset($_POST['accbEndFSRptDte']) ? cleanInputData($_POST['accbEndFSRptDte']) : '';
                $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
                $rcnclImprtHdrID = -1;
                session_write_close();
                $rcnclImprtHdrID = get_MxImprtdBnkStmntID($rcnclAccntID, $accbStrtFSRptDte, $accbEndFSRptDte);
                $affctd = 0;
                $imprtRecCntr = 0;
                if ($rcnclImprtHdrID > 0) {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 0% Completed...Imported Transactions already exist for the Period Selected!<br/>Please delete that imported set first!</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbBnkStmtimport_progress.rho", json_encode($arr_content));
                    exit();
                } else {
                    $rcnclImprtHdrID = get_MaxBnkStmntHdrID() + 1;
                }

                $accbImprtDebits = 0;
                $accbImprtCredits = 0;
                $accbImprtRunBals = 0;
                $accbOpngNetAmnt = 0;
                $accbOpngDebits = 0;
                $accbOpngCredits = 0;
                $accbClsngNetAmnt = 0;
                $accbClsngDebits = 0;
                $accbClsngCredits = 0;

                if ($dataToSend != "") {
                    $variousRows = explode("|", $dataToSend);
                    $total = count($variousRows);
                    for ($z = 0; $z < $total; $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 7) {
                            $imprtTrnsDate = ltrim(trim(cleanInputData1($crntRow[0])), '\'');
                            $imprtValueDate = ltrim(trim(cleanInputData1($crntRow[1])), '\'');
                            $imprtReference = ltrim(trim(cleanInputData1($crntRow[2])), '\'');
                            $imprtDebits = trim(cleanInputData1($crntRow[3]));
                            $imprtCredits = trim(cleanInputData1($crntRow[4]));
                            $imprtRunBals = trim(cleanInputData1($crntRow[5]));
                            $imprtRemarks = trim(cleanInputData1($crntRow[6]));

                            if ($z == 0) {
                                if (
                                    strtoupper($imprtTrnsDate) == strtoupper("Transaction Date (DD-MMM-YYYY)")
                                    && strtoupper($imprtReference) == strtoupper("Reference")
                                    && strtoupper($imprtCredits) == strtoupper("Credits")
                                ) {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                    $arr_content['msgcount'] = $total;
                                    file_put_contents(
                                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbBnkStmtimport_progress.rho",
                                        json_encode($arr_content)
                                    );
                                    break;
                                }
                            } else {
                                if ($imprtTrnsDate == "") {
                                    $imprtTrnsDate = substr($gnrlTrnsDteDMYHMS, 0, 11);
                                }

                                if ($imprtValueDate == "") {
                                    $imprtValueDate = substr($gnrlTrnsDteDMYHMS, 0, 11);
                                }

                                $imprtTrnsDate = rho_getDateInDMY($imprtTrnsDate);
                                $imprtValueDate = rho_getDateInDMY($imprtValueDate);

                                $accbImprtDebits = (float) $imprtDebits;
                                $accbImprtCredits = (float) $imprtCredits;
                                $accbImprtRunBals = (float) $imprtRunBals;
                                if ($z == 1) {
                                    $accbOpngNetAmnt = $accbImprtRunBals + $accbImprtDebits - $accbImprtCredits;
                                    if ($accbOpngNetAmnt > 0) {
                                        $accbOpngCredits = $accbOpngNetAmnt;
                                        $accbOpngDebits = 0;
                                    } else {
                                        $accbOpngDebits = abs($accbOpngNetAmnt);
                                        $accbOpngCredits = 0;
                                    }

                                    $accbClsngDebits = $accbOpngDebits;
                                    $accbClsngCredits = $accbOpngCredits;
                                }
                                $accbClsngDebits = $accbClsngDebits + $accbImprtDebits;
                                $accbClsngCredits = $accbClsngCredits + $accbImprtCredits;
                            }
                            $exitErrMsg = "";
                            if ($imprtTrnsDate == "" || $imprtRemarks == "") {
                                $exitErrMsg .= "Row " . ($z + 1) . ": Please enter Transaction Date and Remark!<br/>";
                            }
                            if ($accbImprtDebits == 0 && $accbImprtCredits == 0) {
                                $exitErrMsg .= "Row " . ($z + 1) . ": Please enter Debit or Credit Amount!<br/>";
                            }
                            if (trim($exitErrMsg) !== "") {
                                $arr_content['percent'] = 100;
                                $arr_content['accbAstHdrID'] = $accbAstHdrID;
                                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                                file_put_contents(
                                    $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbBnkStmtimport_progress.rho",
                                    json_encode($arr_content)
                                );
                                break;
                            }
                            $imprtRecCntr++;
                            $affctd +=  createBnkStmntTrans(
                                $rcnclAccntID,
                                $accbStrtFSRptDte,
                                $accbEndFSRptDte,
                                $rcnclImprtHdrID,
                                $imprtTrnsDate,
                                $imprtValueDate,
                                $imprtReference,
                                $accbImprtDebits,
                                $accbImprtCredits,
                                $accbImprtRunBals,
                                $imprtRemarks,
                                $imprtRecCntr,
                                $accbOpngDebits,
                                $accbOpngCredits,
                                $accbClsngDebits,
                                $accbClsngCredits
                            );
                        }
                        if ($z == ($total - 1)) {
                            execUpdtInsSQL("UPDATE accb.accb_trans_to_reconcile SET clsng_dbt_amount=" . $accbClsngDebits .
                                ",clsng_crdt_amount=" . $accbClsngCredits .
                                ",clsng_net_amount=" . ($accbClsngCredits - $accbClsngDebits) .
                                " WHERE import_hdr_runid = " . $rcnclImprtHdrID);
                        }
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . $affctd . " out of " . $total . " Asset(s) processed.";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Assets...Please Wait..." . ($z + 1) . " out of " . $total . " Asset(s) processed.";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbBnkStmtimport_progress.rho",
                            json_encode($arr_content)
                        );
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbBnkStmtimport_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 902) {
                //Checked Importing Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbBnkStmtimport_progress.rho";
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
            } else if ($actyp == 903) {
                //Export Bank Statement
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                $rcnclAccntID = isset($_POST['rcnclAccntID']) ? (int) cleanInputData($_POST['rcnclAccntID']) : -1;
                $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : '';
                $accbEndFSRptDte = isset($_POST['accbEndFSRptDte']) ? cleanInputData($_POST['accbEndFSRptDte']) : '';
                session_write_close();
                if ($accbStrtFSRptDte != "") {
                    $accbStrtFSRptDte = cnvrtDMYToYMD($accbStrtFSRptDte);
                }
                if ($accbEndFSRptDte != "") {
                    $accbEndFSRptDte = cnvrtDMYToYMD($accbEndFSRptDte);
                }
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array(
                        "Transaction Date (DD-MMM-YYYY)", "Value Date (DD-MMM-YYYY)", "Reference", "Debits",
                        "Credits", "Running Balance", "Remarks"
                    );
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/AccbBnkStmtExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/AccbBnkStmtExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!...Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbBnkStmtExprt_progress.rho",
                            json_encode($arr_content)
                        );

                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_ImprtdBnkStmntRpt($rcnclAccntID, $accbStrtFSRptDte, $accbEndFSRptDte);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        $crntRw = array(
                            "'" . $row[14], "'" . $row[23], "'" . $row[4], $row[5], $row[6], $row[17], $row[3]
                        );
                        fputcsv($opndfile, $crntRw);
                        //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                1) . " out of " . $total . " Transaction(s) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Transactions...Please Wait..." . ($z +
                                1) . " out of " . $total . " Transaction(s) exported.</span>";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbBnkStmtExprt_progress.rho",
                            json_encode($arr_content)
                        );
                        $z++;
                    }
                    if ($z <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbBnkStmtExprt_progress.rho",
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
                    file_put_contents(
                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbBnkStmtExprt_progress.rho",
                        json_encode($arr_content)
                    );
                }
            } else if ($actyp == 904) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbBnkStmtExprt_progress.rho";
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
            if ($vwtyp == 0 || $vwtyp == 10 || $vwtyp == 20) {
                $canAdd = test_prmssns($dfltPrvldgs[14], $mdlNm);
                $canEdt = test_prmssns($dfltPrvldgs[15], $mdlNm);
                $canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);
                $canVoid = test_prmssns($dfltPrvldgs[16], $mdlNm);
                $canPost = test_prmssns($dfltPrvldgs[21], $mdlNm);
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$vwtyp');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Account Reconciliation</span>
			    </li>
                           </ul>
                          </div>";
                if ($vwtyp == 0) {
                    echo $cntent;
                }
                $ymdtme = substr($gnrlTrnsDteDMYHMS, 2, 9);
                //echo $ymdtme;
                $ymdtme1 = getDB_Date_TmIntvlAddSub("01" . $ymdtme, "1 month", "Add");
                $ymdtme2 = getDB_Date_TmIntvlAddSub($ymdtme1, "1 day", "Subtract");
                $ymdtme3 = "01" . $ymdtme;
                $accbStrtFSRptDte = isset($_POST['accbStrtFSRptDte']) ? cleanInputData($_POST['accbStrtFSRptDte']) : substr($ymdtme3, 0, 11);
                $accbStrtFSRptDte1 = $accbStrtFSRptDte;
                $accbFSRptDte = isset($_POST['accbFSRptDte']) ? cleanInputData($_POST['accbFSRptDte']) : substr($ymdtme2, 0, 11);
                $accbFSRptDte1 = $accbFSRptDte;
                $nwRowHtml2 = "<tr id=\"oneJrnlBatchDetRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneJrnlBatchDetLinesTable tr').index(this));\">"
                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                    . "<td class=\"lovtd\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_AccountID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">    
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsSmryLnID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_SlctdAmtBrkdwns\" value=\"\" style=\"width:100% !important;\"> 
                                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_AccountNm\" name=\"oneJrnlBatchDetRow_WWW123WWW_AccountNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchDetRow_WWW123WWW_AccountID', 'oneJrnlBatchDetRow_WWW123WWW_AccountNm', 'clear', 1, '', function () {

                                                                                                                });\">
                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                        </div>                                              
                                                                    </td>                                          
                                                                    <td class=\"lovtd\"  style=\"\">
                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDesc\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_LineDesc\" name=\"oneJrnlBatchDetRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_LineDesc', 'oneJrnlBatchDetLinesTable', 'jbDetDesc');\">                                                    
                                                                    </td>                                                  
                                                                    <td class=\"lovtd\">
                                                                        <div class=\"\" style=\"width:100% !important;\">
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm\" name=\"oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm\" value=\"" . $fnccurnm . "\" readonly=\"true\" style=\"width:100% !important;\">
                                                                            <label class=\"btn btn-primary btn-file\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                                            $('#oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm1').html($('#oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm').val());
                                                                                                        });\">
                                                                                <span class=\"\" id=\"oneJrnlBatchDetRow_WWW123WWW_TrnsCurNm1\">" . $fnccurnm . "</span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetDbt\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_DebitAmnt\" name=\"oneJrnlBatchDetRow_WWW123WWW_DebitAmnt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_DebitAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchDetTtl();\">                                                    
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetCrdt\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_CreditAmnt\" name=\"oneJrnlBatchDetRow_WWW123WWW_CreditAmnt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_CreditAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetCrdt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllJrnlBatchDetTtl();\">                                                    
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Denominational Breakdown\" 
                                                                                    onclick=\"getAccbCashBreakdown(-1, 'ShowDialog', 'Transaction Amount Breakdown', 'EDIT', '" . $defaultBrkdwnLOV . "', 'oneJrnlBatchDetRow_WWW123WWW_DebitAmnt', 'oneJrnlBatchDetRow_WWW123WWW_SlctdAmtBrkdwns');\" style=\"padding:2px !important;\" style=\"padding:2px !important;\"> 
                                                                                <img src=\"cmn_images/cash_breakdown.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                            </button>
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100% !important;\">
                                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneJrnlBatchDetRow_WWW123WWW_TransDte\" value=\"" . $accbFSRptDte1 . " 12:00:00\">
                                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                            </div>                                                        
                                                                    </td>                                         
                                                                    <td class=\"lovtd\"  style=\"\">
                                                                        <input type=\"text\" class=\"form-control jbDetRfDc\" aria-label=\"...\" id=\"oneJrnlBatchDetRow_WWW123WWW_RefDoc\" name=\"oneJrnlBatchDetRow_WWW123WWW_RefDoc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneJrnlBatchDetRow_WWW123WWW_RefDoc', 'oneJrnlBatchDetLinesTable', 'jbDetRfDc');\">                                                    
                                                                    </td>";
                if ($canDel === true && $canEdt === true) {
                    $nwRowHtml2 .= "<td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbJrnlBatchDetLn('oneJrnlBatchDetRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Journal Line\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>";
                }
                if ($canVwRcHstry === true) {
                    $nwRowHtml2 .= "<td class=\"lovtd\">&nbsp;</td>";
                }
                $nwRowHtml2 .= "</tr>";
                $nwRowHtml2 = urlencode($nwRowHtml2);

                $startRunng = isset($_POST['startRunng']) ? (int) cleanInputData($_POST['startRunng']) : 0;
                $qShwSmmry = isset($_POST['accbFSRptShwSmmry']) ? (cleanInputData($_POST['accbFSRptShwSmmry']) === "YES" ? TRUE : FALSE) : TRUE;
                $qShwHideUnBalsd = isset($_POST['accbFSRptShwUnBalsd']) ? (cleanInputData($_POST['accbFSRptShwUnBalsd']) === "YES" ? TRUE : FALSE) : FALSE;
                $qShwHideUnmtchd = isset($_POST['accbFSRptShwUnmtchd']) ? (cleanInputData($_POST['accbFSRptShwUnmtchd']) === "YES" ? TRUE : FALSE) : FALSE;
                $qShwHideVoided = isset($_POST['accbFSRptShwVoided']) ? (cleanInputData($_POST['accbFSRptShwVoided']) === "YES" ? TRUE : FALSE) : TRUE;
                $qShwHideUnrcncld = isset($_POST['accbFSRptShwUnrcncld']) ? (cleanInputData($_POST['accbFSRptShwUnrcncld']) === "YES" ? TRUE : FALSE) : FALSE;

                $dfltCheckAccountID = get_DfltCheckAcnt($orgID);
                $accbFSRptMaxAcntLvl = isset($_POST['accbFSRptMaxAcntLvl']) ? (int) cleanInputData($_POST['accbFSRptMaxAcntLvl']) : 1;
                $accbFSRptSbmtdAccountID = isset($_POST['accbFSRptSbmtdAccountID']) ? (int) cleanInputData($_POST['accbFSRptSbmtdAccountID'])
                    : $dfltCheckAccountID;
                $accbFSRptAcntNum = isset($_POST['accbFSRptAcntNum']) ? cleanInputData($_POST['accbFSRptAcntNum']) : "";
                if ($accbFSRptSbmtdAccountID > 0) {
                    $accbFSRptAcntNum = getAccntNum($accbFSRptSbmtdAccountID) . "." . getAccntName($accbFSRptSbmtdAccountID);
                }
                $accbFSRptSgmnt1ValID = isset($_POST['accbFSRptSgmnt1ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt1ValID']) : -1;
                $accbFSRptSgmnt2ValID = isset($_POST['accbFSRptSgmnt2ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt2ValID']) : -1;
                $accbFSRptSgmnt3ValID = isset($_POST['accbFSRptSgmnt3ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt3ValID']) : -1;
                $accbFSRptSgmnt4ValID = isset($_POST['accbFSRptSgmnt4ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt4ValID']) : -1;
                $accbFSRptSgmnt5ValID = isset($_POST['accbFSRptSgmnt5ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt5ValID']) : -1;
                $accbFSRptSgmnt6ValID = isset($_POST['accbFSRptSgmnt6ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt6ValID']) : -1;
                $accbFSRptSgmnt7ValID = isset($_POST['accbFSRptSgmnt7ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt7ValID']) : -1;
                $accbFSRptSgmnt8ValID = isset($_POST['accbFSRptSgmnt8ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt8ValID']) : -1;
                $accbFSRptSgmnt9ValID = isset($_POST['accbFSRptSgmnt9ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt9ValID']) : -1;
                $accbFSRptSgmnt10ValID = isset($_POST['accbFSRptSgmnt10ValID']) ? (int) cleanInputData($_POST['accbFSRptSgmnt10ValID']) : -1;


                $shwSmmryChkd = "";
                if ($qShwSmmry == true) {
                    $shwSmmryChkd = "checked=\"true\"";
                }
                $shwIntrfcsParam = ($qShwSmmry) ? "Yes" : "No";
                $shwHideUnrcncldChkd = "";
                if ($qShwHideUnrcncld == true) {
                    $shwHideUnrcncldChkd = "checked=\"true\"";
                }
                $shwHideVoidedChkd = "";
                if ($qShwHideVoided == true) {
                    $shwHideVoidedChkd = "checked=\"true\"";
                }
                $shwHideUnmtchdChkd = "";
                if ($qShwHideUnmtchd == true) {
                    $shwHideUnmtchdChkd = "checked=\"true\"";
                }
                $shwHideUnBalsdChkd = "";
                if ($qShwHideUnBalsd == true) {
                    $shwHideUnBalsdChkd = "checked=\"true\"";
                }

                if ($accbStrtFSRptDte != "") {
                    $accbStrtFSRptDte = cnvrtDMYToYMD($accbStrtFSRptDte);
                }
                if ($accbFSRptDte != "") {
                    $accbFSRptDte = cnvrtDMYToYMD($accbFSRptDte);
                }

                $fsrptRunID = -1;
                if ($vwtyp == 0) {
?>
                    <div class="row">
                        <?php
                        if ($startRunng == 1 && $accbFSRptSbmtdAccountID > 0) {
                            $fsrptRunID = getNewFSRptRunID();
                            $strSql = "select accb.populate_gl_statement2(" . $fsrptRunID . ", "
                                . $accbFSRptSbmtdAccountID . ",'" . $shwIntrfcsParam . "', '" . $accbStrtFSRptDte .
                                "', '" . $accbFSRptDte . "', "
                                . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1);";
                            $result = executeSQLNoParams($strSql);
                        }
                        /**
                         * 1. Click Btn to export import template
                         * Narration, ref number, Debit amount, credit amount, date, Bals afta Trans
                         * 2. Click Btn to import template and send user to imported statement page
                         * 3. Click Btn to auto-match imported statement and system generated statement
                         * using ticked criteria and send user to reconciliation statement page
                         * 4. Also main system statement page shd color green reconcilde/matched lines
                         */
                        ?>
                        <div class="col-md-12" style="padding:0px 15px 0px 15px;">
                            <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                    <li class="active"><a data-toggle="tabajxaccrcncl" data-rhodata="" href="#accbRcnclGlStatemtLines" id="accbRcnclGlStatemtLinestab">Main Report</a></li>
                                    <li class=""><a data-toggle="tabajxaccrcncl" data-rhodata="" href="#accbRcnclImprtdTrnsLines" id="accbRcnclImprtdTrnsLinestab">Imported Account Statement</a></li>
                                    <li class=""><a data-toggle="tabajxaccrcncl" data-rhodata="" href="#accbRcnclUnMtchdTrnsLines" id="accbRcnclUnMtchdTrnsLinestab">Statement of Reconciliation</a></li>
                                    <li class=""><a data-toggle="tabajxaccrcncl" data-rhodata="" href="#accbRcnclJrnlTrnsLines" id="accbRcnclJrnlTrnsLinestab">Corrective Journal Entries</a></li>
                                </ul>
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneAccbFSRptTblSctn">
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="accbRcnclGlStatemtLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row">
                                                <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivFSRpt">
                                                    <form class="form-horizontal" id="accbFSRptForm">
                                                        <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                            <legend class="basic_person_lg">
                                                                Parameters<a class="rhopagination" href="javascript:shwHideFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                                                    <span aria-hidden="true">&laquo;</span>
                                                                </a>
                                                            </legend>
                                                            <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwSmmry" name="accbFSRptShwSmmry" <?php echo $shwSmmryChkd; ?>>
                                                                        Show Interface Trns.
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwUnrcncld" name="accbFSRptShwUnrcncld" <?php echo $shwHideUnrcncldChkd; ?>>
                                                                        Show Only Unreconciled
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwVoided" name="accbFSRptShwVoided" <?php echo $shwHideVoidedChkd; ?>>
                                                                        Hide Voided Transactions
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwUnmtchd" name="accbFSRptShwUnmtchd" <?php echo $shwHideUnmtchdChkd; ?>>
                                                                        Un-Matched Amounts
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="accbFSRptShwUnBalsd" name="accbFSRptShwUnBalsd" <?php echo $shwHideUnBalsdChkd; ?>>
                                                                        Possible Unbalanced Entries
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                                                        <label style="margin-bottom:0px !important;">From Date:</label>
                                                                    </div>
                                                                    <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                        <input class="form-control" size="16" type="text" id="accbStrtFSRptDte" name="accbStrtFSRptDte" value="<?php echo $accbStrtFSRptDte1; ?>" placeholder="From Date">
                                                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                                                        <label style="margin-bottom:0px !important;">To Date:</label>
                                                                    </div>
                                                                    <div class="col-md-6 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 0px 0px 0px !important;">
                                                                        <input class="form-control" size="16" type="text" id="accbFSRptDte" name="accbFSRptDte" value="<?php echo $accbFSRptDte1; ?>" placeholder="To Date:">
                                                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group" style="padding:5px 1px 0px 1px !important;">
                                                                    <label for="accbFSRptAcntNum" class="control-label col-md-12" style="padding:5px 1px 0px 1px !important;display:none;">GL Account:</label>
                                                                    <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" aria-label="..." id="accbFSRptAcntNum" name="accbFSRptAcntNum" value="<?php echo $accbFSRptAcntNum; ?>" style="width:100%;" readonly="true">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt1ValID" name="accbFSRptSgmnt1ValID" value="<?php echo $accbFSRptSgmnt1ValID; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt2ValID" name="accbFSRptSgmnt2ValID" value="<?php echo $accbFSRptSgmnt2ValID; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt3ValID" name="accbFSRptSgmnt3ValID" value="<?php echo $accbFSRptSgmnt3ValID; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt4ValID" name="accbFSRptSgmnt4ValID" value="<?php echo $accbFSRptSgmnt4ValID; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt5ValID" name="accbFSRptSgmnt5ValID" value="<?php echo $accbFSRptSgmnt5ValID; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt6ValID" name="accbFSRptSgmnt6ValID" value="<?php echo $accbFSRptSgmnt6ValID; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt7ValID" name="accbFSRptSgmnt7ValID" value="<?php echo $accbFSRptSgmnt7ValID; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt8ValID" name="accbFSRptSgmnt8ValID" value="<?php echo $accbFSRptSgmnt8ValID; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt9ValID" name="accbFSRptSgmnt9ValID" value="<?php echo $accbFSRptSgmnt9ValID; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt10ValID" name="accbFSRptSgmnt10ValID" value="<?php echo $accbFSRptSgmnt10ValID; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSgmnt" name="accbFSRptSgmnt" value="">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="accbFSRptAcntNum1" name="accbFSRptAcntNum1" value="">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="rcnclAccntID" name="rcnclAccntID" value="-1">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="rcnclAccntNm" name="accbFSRptAcntNum1" value="">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="nwRowHtml2" name="nwRowHtml2" value="<?php echo $nwRowHtml2; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbFSRptSbmtdAccountID', 'accbFSRptAcntNum', 'clear', 1, '', function () {});">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" class="form-control" aria-label="..." id="accbFSRptSbmtdAccountID" name="accbFSRptSbmtdAccountID" value="<?php echo $accbFSRptSbmtdAccountID; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;text-align: left;" onclick="getAccbFSRptRpts(1, '#allmodules', 'grp=6&typ=1&pg=19&vtyp=0');">
                                                                    <img src="cmn_images/98.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Generate Report&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                </button>
                                                            </div>
                                                            <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=6&typ=1&pg=19&vtyp=0');">
                                                                    <img src="cmn_images/undo_256.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </div>
                                                            <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                                                    <img src="cmn_images/image007.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </div>
                                                            <div class="col-md-10" style="padding:5px 1px 0px 1px !important;">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;text-align: left;" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'rcnclAccntID', 'rcnclAccntNm', 'clear', 1, '', function () {
                                                                                moveSelectedTrans();
                                                                            });">
                                                                    <img src="cmn_images/98.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Move Selected Trns. from A/c&nbsp;
                                                                </button>
                                                            </div>
                                                            <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Get Bank Statement Import Excel Template">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="exportToRcncl();">
                                                                    <img src="cmn_images/image007.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </div>
                                                            <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;text-align: left;" onclick="markSelectedTransRcncld();">
                                                                    <img src="cmn_images/98.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Mark/Unmark selected as Reconciled&nbsp;
                                                                </button>
                                                            </div>
                                                            <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;text-align: left;" onclick="importToRcncl();">
                                                                    <img src="cmn_images/98.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Import Bank Statement from File
                                                                </button>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                                <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivFSRpt">
                                                    <form class="form-horizontal" id="accbFSRptDetForm">
                                                        <table class="table table-striped table-bordered table-responsive" id="accbFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                            <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                                <a id="rightDivFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                    <span aria-hidden="true">&raquo;</span>
                                                                </a><?php echo $accbFSRptAcntNum; ?> ACCOUNT STATEMENT FROM <?php echo strtoupper($accbStrtFSRptDte1); ?> TO <?php echo strtoupper($accbFSRptDte1); ?>
                                                            </caption>
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                    <th style="max-width:20px;width:20px;">No.</th>
                                                                    <th style="min-width:250px !important;">Transaction Description</th>
                                                                    <th style="max-width:70px;width:70px;">Ref. Doc. No.</th>
                                                                    <th style="text-align: right;">Debit Amount</th>
                                                                    <th style="text-align: right;">Credit Amount</th>
                                                                    <th style="text-align: right;">Running Balance</th>
                                                                    <th style="max-width:70px;width:70px;">Transaction Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $cntr = 0;
                                                                $maxNoRows = 0;
                                                                $resultRw = null;
                                                                if ($fsrptRunID > 0) {
                                                                    $resultRw = get_GLStmntRpt($fsrptRunID, $accbStrtFSRptDte, $accbFSRptDte);
                                                                    $maxNoRows = loc_db_num_rows($resultRw);
                                                                }
                                                                $ttlTrsctnDbtAmnt = 0;
                                                                $ttlTrsctnCrdtAmnt = 0;
                                                                $ttlTrsctnNetAmnt = 0;
                                                                while ($cntr < $maxNoRows) {
                                                                    $rowNumber = 0;
                                                                    $trsctnAcntID = -1;
                                                                    $trsctnAcntNm = "";
                                                                    $trsctnDbtAmnt = 0;
                                                                    $trsctnCrdtAmnt = 0;
                                                                    $trsctnNetAmnt = 0;
                                                                    $trsctnLineDate = "";
                                                                    $trsctnIsRcncld = "0";
                                                                    $trsctnBatchStatus = "VALID";
                                                                    $trsctnSrcBatchID = -1;
                                                                    $trsctnRefNumber = "";
                                                                    $isParent = "0";
                                                                    $hsSbldgr = "0";
                                                                    $numStyle1 = "text-align:right;";
                                                                    $nameStyle1 = "";
                                                                    $rowStyle = "background-color: #ffcccb";
                                                                    if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                        $rowNumber = (float) $rowRw[0];
                                                                        $trsctnAcntID = -1;
                                                                        $trsctnAcntNm = "";
                                                                        $trsctnAcntDesc = "";
                                                                        if ($rowNumber > 1) {
                                                                            $trsctnAcntID = (int) $rowRw[19];
                                                                            $trsctnAcntNm = trim($rowRw[1]) . "." . trim($rowRw[2]);
                                                                            $trsctnAcntDesc = $rowRw[3];
                                                                            $trsctnRefNumber = $rowRw[4];
                                                                            $trsctnIsRcncld = $rowRw[20];
                                                                            $trsctnBatchStatus = $rowRw[21];
                                                                            $trsctnSrcBatchID = (float) $rowRw[22];
                                                                        } else {
                                                                            $rowStyle = "";
                                                                            $numStyle1 = "text-align:right;font-weight:bold;";
                                                                            $nameStyle1 = "font-weight:bold;";
                                                                        }
                                                                        $trsctnDbtAmnt = (float) $rowRw[5];
                                                                        $trsctnCrdtAmnt = (float) $rowRw[6];
                                                                        $trsctnNetAmnt = (float) $rowRw[17];
                                                                        $trsctnLineDate = $rowRw[14];
                                                                        $trsctnLineID = (float) $rowRw[18];
                                                                        $ttlTrsctnDbtAmnt = (float) $rowRw[11];
                                                                        $ttlTrsctnCrdtAmnt = (float) $rowRw[12];
                                                                        $ttlTrsctnNetAmnt = (float) $rowRw[13];
                                                                        if ($trsctnIsRcncld == "1") {
                                                                            $rowStyle = "background-color: #BFFF00";
                                                                        }
                                                                    }
                                                                    $cntr += 1;
                                                                    if ($qShwHideUnrcncld == true && $trsctnIsRcncld == "1") {
                                                                        continue;
                                                                    }
                                                                    if ($qShwHideVoided == true && ($trsctnBatchStatus == "VOID" || $trsctnSrcBatchID > 0)) {
                                                                        continue;
                                                                    }
                                                                ?>
                                                                    <tr id="oneAccbFSRptRow_<?php echo $cntr; ?>" class="hand_cursor" style="<?php echo $rowStyle; ?>">
                                                                        <td class="lovtd">
                                                                            <input type="checkbox" name="oneAccbFSRptRow<?php echo $cntr; ?>_CheckBox" value="oneAccbFSRptRow_<?php echo $cntr; ?>">
                                                                        </td>
                                                                        <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>
                                                                        <td class="lovtd" style="<?php echo $nameStyle1; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_TransLineID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsParent" value="<?php echo $isParent; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNum" value="<?php echo trim($rowRw[1]); ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_AccntNm" value="<?php echo trim($trsctnAcntNm); ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_LineDesc" value="<?php echo trim($trsctnAcntDesc); ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_DbtAmnt" value="<?php echo $trsctnDbtAmnt; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_CrdtAmnt" value="<?php echo $trsctnCrdtAmnt; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_TrnsDte" value="<?php echo trim($trsctnLineDate); ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbFSRptRow<?php echo $cntr; ?>_IsRcncld" value="<?php echo trim($trsctnIsRcncld); ?>" style="width:100% !important;">
                                                                            <span><?php echo $trsctnAcntDesc; ?></span>
                                                                        </td>
                                                                        <td class="lovtd" style="">
                                                                            <span><?php echo $trsctnRefNumber; ?></span>
                                                                        </td>
                                                                        <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                            <span><?php echo number_format($trsctnDbtAmnt, 2); ?></span>
                                                                        </td>
                                                                        <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                            <span><?php echo number_format($trsctnCrdtAmnt, 2); ?></span>
                                                                        </td>
                                                                        <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                            <span><?php echo number_format($trsctnNetAmnt, 2); ?></span>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <span><?php echo $trsctnLineDate; ?></span>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th style="">&nbsp;</th>
                                                                    <th style="">&nbsp;</th>
                                                                    <th>TOTALS:</th>
                                                                    <th style="">&nbsp;</th>
                                                                    <th style="text-align: right;">
                                                                        <?php
                                                                        echo "<span style=\"color:blue;font-weight:bold;font-size:14px;\" id=\"myCptrdJbDbtsTtlBtn\">" . number_format(
                                                                            $ttlTrsctnDbtAmnt,
                                                                            2,
                                                                            '.',
                                                                            ','
                                                                        ) . "</span>";
                                                                        ?>
                                                                        <input type="hidden" id="myCptrdJbDbtsTtlVal" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                                                    </th>
                                                                    <th style="text-align: right;">
                                                                        <?php
                                                                        echo "<span style=\"color:blue;font-weight:bold;font-size:14px;\" id=\"myCptrdJbCrdtsTtlBtn\">" . number_format(
                                                                            $ttlTrsctnCrdtAmnt,
                                                                            2,
                                                                            '.',
                                                                            ','
                                                                        ) . "</span>";
                                                                        ?>
                                                                        <input type="hidden" id="myCptrdJbCrdtsTtlVal" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                                                    </th>
                                                                    <th style="text-align: right;">
                                                                        <?php
                                                                        if ($ttlTrsctnNetAmnt <= 0) {
                                                                            echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format(
                                                                                $ttlTrsctnNetAmnt,
                                                                                2,
                                                                                '.',
                                                                                ','
                                                                            ) . "</span>";
                                                                        } else {
                                                                            echo "<span style=\"color:green;font-weight:bold;font-size:14px;\" id=\"myCptrdJbNetTtlBtn\">" . number_format(
                                                                                $ttlTrsctnNetAmnt,
                                                                                2,
                                                                                '.',
                                                                                ','
                                                                            ) . "</span>";
                                                                        }
                                                                        ?>
                                                                        <input type="hidden" id="myCptrdJbNetTtlVal" value="<?php echo $ttlTrsctnNetAmnt; ?>">
                                                                    </th>
                                                                    <th style="">&nbsp;</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="accbRcnclImprtdTrnsLines" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                        <?php } ?>
                                        <?php if ($vwtyp == 0 || $vwtyp == 10) {
                                            $qShwHideNarratn = isset($_POST['accbImprtdFSRptShwNarratn']) ? (cleanInputData($_POST['accbImprtdFSRptShwNarratn']) === "YES" ? TRUE : FALSE) : FALSE;
                                            $shwHideNarratnChkd = "";
                                            if ($qShwHideNarratn == true) {
                                                $shwHideNarratnChkd = "checked=\"true\"";
                                            }
                                            $qShwHideTransDte = isset($_POST['accbImprtdFSRptShwTransDte']) ? (cleanInputData($_POST['accbImprtdFSRptShwTransDte']) === "YES" ? TRUE : FALSE) : FALSE;
                                            $shwHideTransDteChkd = "";
                                            if ($qShwHideTransDte == true) {
                                                $shwHideTransDteChkd = "checked=\"true\"";
                                            }
                                            $qShwHideNetAmnt = isset($_POST['accbImprtdFSRptShwNetAmnt']) ? (cleanInputData($_POST['accbImprtdFSRptShwNetAmnt']) === "YES" ? TRUE : FALSE) : TRUE;
                                            $shwHideNetAmntChkd = "";
                                            if ($qShwHideNetAmnt == true) {
                                                $shwHideNetAmntChkd = "checked=\"true\"";
                                            }
                                            $qShwHideRefNum = isset($_POST['accbImprtdFSRptShwRefNum']) ? (cleanInputData($_POST['accbImprtdFSRptShwRefNum']) === "YES" ? TRUE : FALSE) : TRUE;
                                            $shwHideRefNumChkd = "";
                                            if ($qShwHideRefNum == true) {
                                                $shwHideRefNumChkd = "checked=\"true\"";
                                            }
                                        ?>
                                            <div class="row">
                                                <div class="col-md-3" style="padding:0px 1px 0px 15px;" id="leftDivImprtdFSRpt">
                                                    <form class="form-horizontal" id="accbImprtdFSRptForm">
                                                        <fieldset class="basic_person_fs1" style="padding: 0px 5px 5px 5px !important;">
                                                            <legend class="basic_person_lg">
                                                                Parameters<a class="rhopagination" href="javascript:shwHideImprtdFSRptDivs('hide');" aria-label="hide" style="float:right;padding: 0px 15px 0px 15px !important;">
                                                                    <span aria-hidden="true">&laquo;</span>
                                                                </a>
                                                            </legend>
                                                            <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="accbImprtdFSRptShwUnrcncld" name="accbImprtdFSRptShwUnrcncld" <?php echo $shwHideUnrcncldChkd; ?>>
                                                                        Show Only Unreconciled
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;text-align: left;" onclick="getAccbImprtdFSRptRpts(1, '#accbRcnclImprtdTrnsLines', 'grp=6&typ=1&pg=19&vtyp=10');">
                                                                    <img src="cmn_images/98.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Retrieve Statement&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                </button>
                                                            </div>
                                                            <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Reset Report">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="resetAccbFSRptRpts('#allmodules', 'grp=6&typ=1&pg=19&vtyp=0');">
                                                                    <img src="cmn_images/undo_256.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </div>
                                                            <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Excel Export">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="funcHtmlToExcel('accbFSRptTable');">
                                                                    <img src="cmn_images/image007.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </div>
                                                            <div class="col-md-8" style="padding:5px 1px 0px 1px !important;">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;text-align: left;" onclick="moveSelectedImprtdTrans();">
                                                                    <img src="cmn_images/98.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Move Selected Trns.&nbsp;
                                                                </button>
                                                            </div>
                                                            <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Delete Imported Bank Statement for the Period">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="delBnkStmntTrans();">
                                                                    <img src="cmn_images/delete.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </div>
                                                            <div class="col-md-2" style="padding:5px 1px 0px 1px !important;" title="Get Bank Statement Import Excel Template">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;" onclick="exportToRcncl();">
                                                                    <img src="cmn_images/image007.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </div>
                                                            <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;text-align: left;" onclick="markSelectedImprtdTransRcncld();">
                                                                    <img src="cmn_images/98.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Mark/Unmark selected as Reconciled&nbsp;
                                                                </button>
                                                            </div>
                                                            <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;text-align: left;" onclick="importToRcncl();">
                                                                    <img src="cmn_images/98.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Import Bank Statement from File
                                                                </button>
                                                            </div>
                                                            <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:100% !important;text-align: left;" onclick="autoRcnclWthImprtd();">
                                                                    <img src="cmn_images/98.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Auto-Reconcile using ff Parameters
                                                                </button>
                                                            </div>
                                                            <div class="col-md-12" style="padding:5px 1px 0px 1px !important;">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="accbImprtdFSRptShwTransDte" name="accbImprtdFSRptShwTransDte" <?php echo $shwHideTransDteChkd; ?>>
                                                                        Transaction Date
                                                                    </label>
                                                                </div>
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="accbImprtdFSRptShwNetAmnt" name="accbImprtdFSRptShwNetAmnt" <?php echo $shwHideNetAmntChkd; ?>>
                                                                        Net Amount
                                                                    </label>
                                                                </div>
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="accbImprtdFSRptShwRefNum" name="accbImprtdFSRptShwRefNum" <?php echo $shwHideRefNumChkd; ?>>
                                                                        Reference Number
                                                                    </label>
                                                                </div>
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="accbImprtdFSRptShwNarratn" name="accbImprtdFSRptShwNarratn" <?php echo $shwHideNarratnChkd; ?>>
                                                                        Narration
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                                <div class="col-md-9" style="padding:0px 15px 0px 15px;" id="rightDivImprtdFSRpt">
                                                    <form class="form-horizontal" id="accbImprtdFSRptDetForm">
                                                        <table class="table table-striped table-bordered table-responsive" id="accbImprtdFSRptTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                            <caption class="basic_person_lg" style="padding:5px 15px 5px 15px;font-weight:bold;font-size: 18px;">
                                                                <a id="rightDivImprtdFSRptBtn" class="rhopagination hideNotice" href="javascript:shwHideImprtdFSRptDivs('show');" aria-label="Show" style="float:left;padding: 0px 15px 0px 15px !important;">
                                                                    <span aria-hidden="true">&raquo;</span>
                                                                </a><?php echo $accbFSRptAcntNum; ?> IMPORTED STATEMENT FROM <?php echo strtoupper($accbStrtFSRptDte1); ?> TO <?php echo strtoupper($accbFSRptDte1); ?>
                                                            </caption>
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                    <th style="max-width:30px;width:30px;">No.</th>
                                                                    <th style="min-width:250px !important;">Transaction Description</th>
                                                                    <th style="max-width:70px;width:70px;">Ref. Doc. No.</th>
                                                                    <th style="text-align: right;">Debit Amount</th>
                                                                    <th style="text-align: right;">Credit Amount</th>
                                                                    <th style="text-align: right;">Running Balance</th>
                                                                    <th style="max-width:80px;width:80px;">Transaction Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $rowNumber = 0;
                                                                $trsctnAcntID = -1;
                                                                $trsctnAcntNm = "";
                                                                $trsctnDbtAmnt = 0;
                                                                $trsctnCrdtAmnt = 0;
                                                                $trsctnNetAmnt = 0;
                                                                $trsctnLineDate = "";
                                                                $trsctnLineID = -1;
                                                                $trsctnIsRcncld = "0";
                                                                $trsctnBatchStatus = "VALID";
                                                                $trsctnSrcBatchID = -1;
                                                                $trsctnRefNumber = "";
                                                                $isParent = "0";
                                                                $hsSbldgr = "0";
                                                                $numStyle1 = "text-align:right;";
                                                                $nameStyle1 = "";
                                                                $rowStyle = "background-color: #ffcccb";
                                                                $accbFSRptRcnclImprtHdrID = -1;
                                                                $cntr = 0;
                                                                $maxNoRows = 0;
                                                                $resultRw = null;
                                                                $resultRw = get_ImprtdBnkStmntRpt($accbFSRptSbmtdAccountID, $accbStrtFSRptDte, $accbFSRptDte);
                                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                                $ttlTrsctnDbtAmnt = 0;
                                                                $ttlTrsctnCrdtAmnt = 0;
                                                                $ttlTrsctnNetAmnt = 0;
                                                                while ($cntr < $maxNoRows) {
                                                                    if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                        $rowNumber = (float) $rowRw[0];
                                                                        if ($cntr <= 0) {
                                                                            $trsctnAcntDesc = "OPENING BALANCE";
                                                                            $trsctnDbtAmnt = (float) $rowRw[8];
                                                                            $trsctnCrdtAmnt = (float) $rowRw[9];
                                                                            $trsctnNetAmnt = (float) $rowRw[10];
                                                                            $rowStyle = "";
                                                                            $numStyle1 = "text-align:right;font-weight:bold;";
                                                                            $nameStyle1 = "font-weight:bold;";
                                                                ?>
                                                                            <tr id="oneAccbImprtdFSRptRow_<?php echo $cntr; ?>" class="hand_cursor" style="<?php echo $rowStyle; ?>">
                                                                                <td class="lovtd">
                                                                                    <input type="checkbox" name="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_CheckBox" value="oneAccbImprtdFSRptRow_<?php echo $cntr; ?>">
                                                                                </td>
                                                                                <td class="lovtd"><span>&nbsp;</span></td>
                                                                                <td class="lovtd" style="<?php echo $nameStyle1; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_TransLineID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_IsParent" value="<?php echo $isParent; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_AccntNum" value="<?php echo trim($rowRw[1]); ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_AccntNm" value="<?php echo trim($trsctnAcntNm); ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_LineDesc" value="<?php echo trim($trsctnAcntDesc); ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_DbtAmnt" value="<?php echo $trsctnDbtAmnt; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_CrdtAmnt" value="<?php echo $trsctnCrdtAmnt; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_TrnsDte" value="<?php echo trim($trsctnLineDate); ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_IsRcncld" value="<?php echo trim($trsctnIsRcncld); ?>" style="width:100% !important;">
                                                                                    <span><?php echo $trsctnAcntDesc; ?></span>
                                                                                </td>
                                                                                <td class="lovtd" style="">
                                                                                    <span><?php echo $trsctnRefNumber; ?></span>
                                                                                </td>
                                                                                <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                    <span><?php echo number_format($trsctnDbtAmnt, 2); ?></span>
                                                                                </td>
                                                                                <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                    <span><?php echo number_format($trsctnCrdtAmnt, 2); ?></span>
                                                                                </td>
                                                                                <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                                    <span><?php echo number_format($trsctnNetAmnt, 2); ?></span>
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <span><?php echo $trsctnLineDate; ?></span>
                                                                                </td>
                                                                            </tr>
                                                                    <?php
                                                                        }
                                                                        $numStyle1 = "text-align:right;";
                                                                        $nameStyle1 = "";
                                                                        $rowStyle = "background-color: #ffcccb";
                                                                        $trsctnLineDate = $rowRw[14];
                                                                        $trsctnLineID = (float) $rowRw[18];
                                                                        $trsctnDbtAmnt = (float) $rowRw[5];
                                                                        $trsctnCrdtAmnt = (float) $rowRw[6];
                                                                        $trsctnNetAmnt = (float) $rowRw[17];
                                                                        $ttlTrsctnDbtAmnt = (float) $rowRw[11];
                                                                        $ttlTrsctnCrdtAmnt = (float) $rowRw[12];
                                                                        $ttlTrsctnNetAmnt = (float) $rowRw[13];

                                                                        $trsctnAcntID = (int) $rowRw[19];
                                                                        $trsctnAcntNm = trim($rowRw[1]) . "." . trim($rowRw[2]);
                                                                        $trsctnAcntDesc = $rowRw[3];
                                                                        $trsctnRefNumber = $rowRw[4];
                                                                        $trsctnIsRcncld = $rowRw[20];
                                                                        $trsctnBatchStatus = $rowRw[21];
                                                                        $trsctnSrcBatchID = (float) $rowRw[22];
                                                                        $accbFSRptRcnclImprtHdrID = (float) $rowRw[24];
                                                                    }

                                                                    $cntr += 1;
                                                                    if ($qShwHideUnrcncld == true && $trsctnIsRcncld == "1") {
                                                                        continue;
                                                                    }
                                                                    if ($qShwHideVoided == true && ($trsctnBatchStatus == "VOID" || $trsctnSrcBatchID > 0)) {
                                                                        continue;
                                                                    }
                                                                    if ($trsctnIsRcncld == "1") {
                                                                        $rowStyle = "background-color: #BFFF00";
                                                                    }

                                                                    ?>
                                                                    <tr id="oneAccbImprtdFSRptRow_<?php echo $cntr; ?>" class="hand_cursor" style="<?php echo $rowStyle; ?>">
                                                                        <td class="lovtd">
                                                                            <input type="checkbox" name="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_CheckBox" value="oneAccbImprtdFSRptRow_<?php echo $cntr; ?>">
                                                                        </td>
                                                                        <td class="lovtd"><span><?php echo ($rowNumber); ?></span></td>
                                                                        <td class="lovtd" style="<?php echo $nameStyle1; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_TransLineID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_IsParent" value="<?php echo $isParent; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_AccntNum" value="<?php echo trim($rowRw[1]); ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_AccntNm" value="<?php echo trim($trsctnAcntNm); ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_LineDesc" value="<?php echo trim($trsctnAcntDesc); ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_DbtAmnt" value="<?php echo $trsctnDbtAmnt; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_CrdtAmnt" value="<?php echo $trsctnCrdtAmnt; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_TrnsDte" value="<?php echo trim($trsctnLineDate); ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneAccbImprtdFSRptRow<?php echo $cntr; ?>_IsRcncld" value="<?php echo trim($trsctnIsRcncld); ?>" style="width:100% !important;">
                                                                            <span><?php echo $trsctnAcntDesc; ?></span>
                                                                        </td>
                                                                        <td class="lovtd" style="">
                                                                            <span><?php echo $trsctnRefNumber; ?></span>
                                                                        </td>
                                                                        <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                            <span><?php echo number_format($trsctnDbtAmnt, 2); ?></span>
                                                                        </td>
                                                                        <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                            <span><?php echo number_format($trsctnCrdtAmnt, 2); ?></span>
                                                                        </td>
                                                                        <td class="lovtd" style="<?php echo $numStyle1; ?>">
                                                                            <span><?php echo number_format($trsctnNetAmnt, 2); ?></span>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <span><?php echo $trsctnLineDate; ?></span>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th style="">&nbsp;</th>
                                                                    <th style="">&nbsp;</th>
                                                                    <th>TOTALS:</th>
                                                                    <th style="">&nbsp;</th>
                                                                    <th style="text-align: right;">
                                                                        <?php
                                                                        echo "<span style=\"color:blue;font-weight:bold;font-size:14px;\" id=\"myImprtdJbDbtsTtlBtn\">" . number_format(
                                                                            $ttlTrsctnDbtAmnt,
                                                                            2,
                                                                            '.',
                                                                            ','
                                                                        ) . "</span>";
                                                                        ?>
                                                                        <input type="hidden" id="myImprtdJbDbtsTtlVal" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                                                    </th>
                                                                    <th style="text-align: right;">
                                                                        <?php
                                                                        echo "<span style=\"color:blue;font-weight:bold;font-size:14px;\" id=\"myImprtdJbCrdtsTtlBtn\">" . number_format(
                                                                            $ttlTrsctnCrdtAmnt,
                                                                            2,
                                                                            '.',
                                                                            ','
                                                                        ) . "</span>";
                                                                        ?>
                                                                        <input type="hidden" id="myImprtdJbCrdtsTtlVal" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                                                    </th>
                                                                    <th style="text-align: right;">
                                                                        <?php
                                                                        if ($ttlTrsctnNetAmnt <= 0) {
                                                                            echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myImprtdJbNetTtlBtn\">" . number_format(
                                                                                $ttlTrsctnNetAmnt,
                                                                                2,
                                                                                '.',
                                                                                ','
                                                                            ) . "</span>";
                                                                        } else {
                                                                            echo "<span style=\"color:green;font-weight:bold;font-size:14px;\" id=\"myImprtdJbNetTtlBtn\">" . number_format(
                                                                                $ttlTrsctnNetAmnt,
                                                                                2,
                                                                                '.',
                                                                                ','
                                                                            ) . "</span>";
                                                                        }
                                                                        ?>
                                                                        <input type="hidden" id="myImprtdJbNetTtlVal" value="<?php echo $ttlTrsctnNetAmnt; ?>">
                                                                    </th>
                                                                    <th style="">&nbsp;</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        <input id="accbFSRptRcnclImprtHdrID" type="hidden" value="<?php echo $accbFSRptRcnclImprtHdrID; ?>">
                                                    </form>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($vwtyp == 0) { ?>
                                        </div>
                                        <div id="accbRcnclUnMtchdTrnsLines" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                        <?php } ?>
                                        <?php if ($vwtyp == 0 || $vwtyp == 20) { ?>

                                        <?php }
                                        if ($vwtyp == 0) { ?>
                                        </div>
                                        <div id="accbRcnclJrnlTrnsLines" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php
                                                    $lmtSze = isset($_POST['accbJrnlBatchDsplySze']) ? cleanInputData($_POST['accbJrnlBatchDsplySze'])
                                                        : 50;
                                                    $sbmtdJrnlBatchID = isset($_POST['sbmtdJrnlBatchID']) ? (float) cleanInputData($_POST['sbmtdJrnlBatchID'])
                                                        : -1;
                                                    if (!$canAdd || ($sbmtdJrnlBatchID > 0 && !$canEdt)) {
                                                        restricted();
                                                        exit();
                                                    }
                                                    $orgnlJrnlBatchID = $sbmtdJrnlBatchID;
                                                    $rqStatus = "Not Posted";
                                                    $rqstatusColor = "red";
                                                    $rqstVldty = "VALID";
                                                    $rqstVldtyColor = "green";
                                                    $autoPostStatus = "Not Monitored";
                                                    $autoPostStatusColor = "black";
                                                    $dte = date('ymd');
                                                    $gnrtdTrnsNo = "";
                                                    $gnrtdTrnsDate = $gnrlTrnsDteDMYHMS; //date('d-M-Y H:i:s');
                                                    $gnrtdTrnsDate1 = $gnrlTrnsDteYMDHMS; //date('Y-m-d H:i:s');
                                                    $crncyID = $fnccurid;
                                                    $crncyIDNm = $fnccurnm;
                                                    $voidedJrnlBatchID = -1;
                                                    $jrnlBatchDesc = "";
                                                    $jrnlBatchRvrslRsn = "";
                                                    $jrnlBatchNetAmnt = 0;
                                                    $jrnlBatchDbtAmnt = 0;
                                                    $jrnlBatchCrdtAmnt = 0;
                                                    $jrnlBatchDfltBalsAcntID = 0;
                                                    $jrnlBatchDfltBalsAcnt = "";
                                                    $jrnlBatchCreationDate = $gnrtdTrnsDate;
                                                    $jrnlBatchSource = "Manual";
                                                    $jrnlBatchDfltCurID = $fnccurid;
                                                    $jrnlBatchDfltCurNm = $fnccurnm;
                                                    $jrnlBatchDfltTrnsDte = $jrnlBatchCreationDate;
                                                    $mkReadOnly = "";
                                                    $mkRmrkReadOnly = "";
                                                    if ($sbmtdJrnlBatchID > 0) {
                                                        //Important! Must Check if One also has prmsn to Edit brought Trns Hdr ID
                                                        $result = get_One_BatchDet($sbmtdJrnlBatchID);
                                                        if ($row = loc_db_fetch_array($result)) {
                                                            $rqStatus = ($row[3] == "1") ? "Posted" : "Not Posted";
                                                            $rqstVldty = $row[6];
                                                            $autoPostStatus = $row[7];
                                                            $voidedJrnlBatchID = (float) $row[12];
                                                            $gnrtdTrnsNo = $row[1];
                                                            $jrnlBatchDesc = $row[2];
                                                            $jrnlBatchRvrslRsn = $row[15];
                                                            $jrnlBatchDfltCurID = $row[16];
                                                            $jrnlBatchDfltCurNm = $row[17];
                                                            $jrnlBatchDfltTrnsDte = $row[18];
                                                            $jrnlBatchDbtAmnt = get_Batch_DbtSum($sbmtdJrnlBatchID);
                                                            $jrnlBatchCrdtAmnt = get_Batch_CrdtSum($sbmtdJrnlBatchID);
                                                            $jrnlBatchNetAmnt = abs($jrnlBatchDbtAmnt - $jrnlBatchCrdtAmnt);
                                                            $jrnlBatchDfltBalsAcntID = (float) $row[13];
                                                            $jrnlBatchDfltBalsAcnt = $row[14];
                                                            $jrnlBatchCreationDate = $row[4];
                                                            $jrnlBatchSource = $row[5];
                                                            if ($jrnlBatchDfltCurID <= 0) {
                                                                $jrnlBatchDfltCurID = $fnccurid;
                                                                $jrnlBatchDfltCurNm = $fnccurnm;
                                                                $jrnlBatchDfltTrnsDte = $jrnlBatchCreationDate;
                                                            }
                                                            if ($rqStatus == "Not Posted") {
                                                                $rqstatusColor = "red";
                                                                if ($voidedJrnlBatchID <= 0) {
                                                                    $mkReadOnly = "";
                                                                    $mkRmrkReadOnly = "";
                                                                } else {
                                                                    $mkReadOnly = "readonly=\"true\"";
                                                                    $mkRmrkReadOnly = "";
                                                                }
                                                            } else {
                                                                $canEdt = FALSE;
                                                                $rqstatusColor = "green";
                                                                $mkReadOnly = "readonly=\"true\"";
                                                                if ($rqStatus != "Posted" && $voidedJrnlBatchID <= 0) {
                                                                    $mkRmrkReadOnly = "readonly=\"true\"";
                                                                }
                                                            }
                                                            if ($rqstVldty == "VALID") {
                                                                $rqstVldtyColor = "green";
                                                            } else {
                                                                $rqstVldtyColor = "red";
                                                            }
                                                            if ($autoPostStatus == "Not Monitored") {
                                                                $autoPostStatusColor = "black";
                                                            } else {
                                                                $autoPostStatusColor = "green";
                                                            }
                                                            if ($voidedJrnlBatchID > 0) {
                                                                $jrnlBatchDesc = $jrnlBatchRvrslRsn;
                                                            }
                                                        }
                                                    } else {
                                                        //$sbmtdJrnlBatchID = getNewJrnlBatchID();
                                                        $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                                                        if ($usrTrnsCode == "") {
                                                            $usrTrnsCode = "XX";
                                                        }
                                                        $userAccntName = getGnrlRecNm("sec.sec_users", "user_id", "user_name", $usrID);
                                                        $gnrtdTrnsNo1 = substr($userAccntName, 0, 4) . "-" . $usrTrnsCode . "-" . $dte . "-";
                                                        $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad((getRecCount_LstNum(
                                                                "accb.accb_trnsctn_batches",
                                                                "batch_name",
                                                                "batch_id",
                                                                $gnrtdTrnsNo1 . "%"
                                                            ) + 1),
                                                            3,
                                                            '0',
                                                            STR_PAD_LEFT
                                                        );
                                                        /* createBatch($orgID, $gnrtdTrnsNo, $jrnlBatchDesc, "Manual", "VALID",
                                                      $voidedJrnlBatchID, "0", $jrnlBatchDfltBalsAcntID, $jrnlBatchRvrslRsn,
                                                      $jrnlBatchDfltCurID, $jrnlBatchDfltTrnsDte);
                                                      $sbmtdJrnlBatchID = getBatchID($gnrtdTrnsNo, $orgID); */
                                                    }
                                                    $style1 = "color:green;";
                                                    if ($jrnlBatchNetAmnt != 0) {
                                                        $style1 = "color:red;";
                                                    }
                                                    if (strpos($jrnlBatchSource, "Manual") === FALSE) {
                                                        $canEdt = FALSE;
                                                        $mkReadOnly = "readonly=\"true\"";
                                                        $mkRmrkReadOnly = "readonly=\"true\"";
                                                    }
                                                    $reportTitle = "Journal Entry Batch";
                                                    $reportName = "Journal Entry Batch";
                                                    $rptID = getRptID($reportName);
                                                    $prmID1 = getParamIDUseSQLRep("{:batch_id}", $rptID);
                                                    $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                    $trnsID = $sbmtdJrnlBatchID;
                                                    $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                                                    $paramStr = urlencode($paramRepsNVals);

                                                    $reportTitle1 = "Post GL Transaction Batches-Web";
                                                    $reportName1 = "Post GL Transaction Batches-Web";
                                                    $rptID1 = getRptID($reportName1);
                                                    $prmID11 = getParamIDUseSQLRep("{:p_batch_id}", $rptID1);
                                                    $paramRepsNVals1 = $prmID11 . "~" . $sbmtdJrnlBatchID . "|-130~" . $reportTitle1 . "|-190~HTML";
                                                    $paramStr1 = urlencode($paramRepsNVals1);
                                                    ?>
                                                    <form class="form-horizontal" id="oneJrnlBatchEDTForm">
                                                        <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                                                            <!--<legend class="basic_person_lg">Transaction Header Information</legend>-->
                                                            <div class="row" style="margin-top:5px;">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2">
                                                                            <label style="margin-bottom:0px !important;">No.:</label>
                                                                        </div>
                                                                        <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="sbmtdTempltLovID" name="sbmtdTempltLovID" value="-1" readonly="true">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="sbmtdTempltUsrID" name="sbmtdTempltUsrID" value="<?php echo $usrID; ?>" readonly="true">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="sbmtdTempltTrnsCount" name="sbmtdTempltTrnsCount" value="2" readonly="true">
                                                                            <input type="text" class="form-control" aria-label="..." id="sbmtdJrnlBatchID" name="sbmtdJrnlBatchID" value="<?php echo $sbmtdJrnlBatchID; ?>" readonly="true">
                                                                            <input class="form-control" type="hidden" id="voidedJrnlBatchID" value="<?php echo $voidedJrnlBatchID; ?>" />
                                                                        </div>
                                                                        <div class="col-md-7" style="padding:0px 15px 0px 1px;">
                                                                            <input type="text" class="form-control" aria-label="..." id="jrnlBatchNum" name="jrnlBatchNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-md-2">
                                                                            <label style="margin-bottom:0px !important;">Date:</label>
                                                                        </div>
                                                                        <div class="col-md-10">
                                                                            <div class="col-md-3" style="padding:0px 0px 0px 0px !important;">
                                                                                <label class="btn btn-info btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $jrnlBatchDfltCurNm; ?>', 'jrnlBatchDfltCurNm', '', 'clear', 0, '', function () {
                                                                                        $('#jrnlBatchAmountCrncy').html($('#jrnlBatchDfltCurNm').val());
                                                                                    });">
                                                                                    <span class="" style="font-size: 20px !important;" id="jrnlBatchAmountCrncy"><?php echo $jrnlBatchDfltCurNm; ?></span>
                                                                                </label>
                                                                                <input type="hidden" id="jrnlBatchDfltCurNm" value="<?php echo $jrnlBatchDfltCurNm; ?>">
                                                                            </div>
                                                                            <div class="col-md-9 input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 0px 0px 0px !important;">
                                                                                <input class="form-control" size="16" type="text" id="jrnlBatchDfltTrnsDte" name="jrnlBatchDfltTrnsDte" value="<?php echo $jrnlBatchDfltTrnsDte; ?>" placeholder="Transactions Date">
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-md-2">
                                                                            <label style="margin-bottom:0px !important;">Validity:</label>
                                                                        </div>
                                                                        <div class="col-md-4" style="padding:0px 1px 0px 15px;">
                                                                            <input type="text" class="form-control" aria-label="..." id="rqstVldty" name="rqstVldty" value="<?php echo $rqstVldty; ?>" readonly="true" style="font-weight:bold;color:<?php echo $rqstVldtyColor; ?>">
                                                                        </div>
                                                                        <div class="col-md-6" style="padding:0px 15px 0px 1px;">
                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;width:100% !important;" id="myJrnlBatchStatusBtn"><span style="font-weight:bold;height:30px;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:30px;"><?php echo $rqStatus; ?></span></button>
                                                                            <input type="hidden" class="form-control" aria-label="..." id="autoPostStatus" name="autoPostStatus" value="<?php echo $autoPostStatus; ?>" readonly="true" style="font-weight:bold;color:<?php echo $autoPostStatusColor; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2">
                                                                            <label style="margin-bottom:0px !important;">Remarks:</label>
                                                                        </div>
                                                                        <div class="col-md-10">
                                                                            <div class="input-group" style="width:100%;">
                                                                                <textarea class="form-control rqrdFld" rows="5" cols="20" id="jrnlBatchDesc" name="jrnlBatchDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $jrnlBatchDesc; ?></textarea>
                                                                                <input class="form-control" type="hidden" id="jrnlBatchDesc1" value="<?php echo $jrnlBatchDesc; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('jrnlBatchDesc');" style="max-width:30px;width:30px;">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <div class="col-md-12">
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file input-group-addon" style="<?php echo $breadCrmbBckclr; ?>;min-width:27% !important;width:27% !important;">
                                                                                    <span style="font-weight:bold;<?php echo $forecolors; ?>">Total Debits:&nbsp;&nbsp;</span>
                                                                                </label>
                                                                                <input class="form-control" id="jrnlBatchDbtAmnt" type="text" placeholder="0.00" value="<?php
                                                                                                                                                                        echo number_format($jrnlBatchDbtAmnt, 2);
                                                                                                                                                                        ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>;width:100%;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-md-12">
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file input-group-addon" style="<?php echo $breadCrmbBckclr; ?>;min-width:27% !important;width:27% !important;">
                                                                                    <span style="font-weight:bold;<?php echo $forecolors; ?>">Total Credits:&nbsp;</span>
                                                                                </label>
                                                                                <input class="form-control" id="jrnlBatchCrdtAmnt" type="text" placeholder="0.00" value="<?php
                                                                                                                                                                            echo number_format($jrnlBatchCrdtAmnt, 2);
                                                                                                                                                                            ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>;width:100%;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-md-12">
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file input-group-addon" style="<?php echo $breadCrmbBckclr; ?>;min-width:27% !important;width:27% !important;">
                                                                                    <span style="font-weight:bold;<?php echo $forecolors; ?>">Difference:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                                                </label>
                                                                                <input class="form-control" id="jrnlBatchNetAmnt" type="text" placeholder="0.00" value="<?php
                                                                                                                                                                        echo number_format($jrnlBatchNetAmnt, 2);
                                                                                                                                                                        ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>;width:100%;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset class="">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                                                        <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                                            <?php if ($canEdt) { ?>
                                                                                                <button id="addNwJrnlBatchDetBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="insertNewJrnlBatcRows('oneJrnlBatchDetLinesTable', 0, '<?php echo $nwRowHtml2; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Detailed Transaction Line">
                                                                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                </button>
                                                                                            <?php } ?>
                                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getOneJrnlBatchDocsForm(<?php echo $sbmtdJrnlBatchID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title="Attached Documents">
                                                                                                <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getOneJrnlBatchForm(<?php echo $sbmtdJrnlBatchID; ?>, 11, 'ReloadDialog', -1, '', '#accbRcnclJrnlTrnsLines');"><img src="cmn_images/refresh.bmp" style="left: 0.01%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                                                                <img src="cmn_images/pdf.png" style="left: 0.01%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                Print
                                                                                            </button>
                                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbJrnlBatchDsplySze" style="margin-bottom: -3px;height:30px;max-width:70px !important;display:inline-block;" onchange="getOneJrnlBatchForm(<?php echo $sbmtdJrnlBatchID; ?>, 1, 'ReloadDialog', -1, '', '#accbRcnclJrnlTrnsLines');" data-toggle="tooltip" title="No. of Records to Display">
                                                                                                <?php
                                                                                                $valslctdArry = array(
                                                                                                    "", "", "", "", "", "",
                                                                                                    "", ""
                                                                                                );
                                                                                                $dsplySzeArry = array(
                                                                                                    1, 5, 10, 15, 30, 50, 100,
                                                                                                    500, 1000, 50000, 1000000000
                                                                                                );
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
                                                                                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                                                                            <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                                                                                                <?php
                                                                                                if ($rqStatus == "Not Posted") {
                                                                                                ?>
                                                                                                    <?php
                                                                                                    if ($voidedJrnlBatchID <= 0) {
                                                                                                    ?>
                                                                                                        <?php if ($canEdt) { ?>
                                                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveJrnlBatchForm('<?php echo $fnccurnm; ?>', 0, -1, -1, '', -1, '', 'TBALS');"><img src="cmn_images/FloppyDisk.png" style="left: 0.01%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                                                                                        <?php } ?>
                                                                                                    <?php
                                                                                                    }
                                                                                                    if ($canPost && $sbmtdJrnlBatchID > 0) {
                                                                                                    ?>
                                                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveJrnlBatchForm('<?php echo $fnccurnm; ?>', 5,<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>', -1, '', 'TBALS');"><img src="cmn_images/98.png" style="left: 0.01%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Post Batch&nbsp;</button>
                                                                                                    <?php
                                                                                                    } else if ($sbmtdJrnlBatchID > 0) {
                                                                                                    ?>
                                                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="bootbox.alert({title: 'System Alert!', size: 'small', message: 'Permission Denied!'});"><img src="cmn_images/98.png" style="left: 0.01%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Post Batch&nbsp;</button>
                                                                                                    <?php
                                                                                                    }
                                                                                                } else if ($rqStatus == "Posted") {
                                                                                                    ?>
                                                                                                    <button id="fnlzeRvrslJrnlBatchBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveJrnlRvrsBatchForm('<?php echo $fnccurnm; ?>', 1, -1, '');"><img src="cmn_images/90.png" style="left: 0.01%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Transaction&nbsp;</button>
                                                                                                <?php
                                                                                                }
                                                                                                ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneJrnlBatchLnsTblSctn">
                                                                        <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                                            <div id="jrnlBatchDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <table class="table table-striped table-bordered table-responsive" id="oneJrnlBatchDetLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th>No.</th>
                                                                                                    <th style="min-width:220px;">GL Transaction Account</th>
                                                                                                    <th style="min-width:250px;">Narration/Remarks</th>
                                                                                                    <th>CUR.</th>
                                                                                                    <th style="text-align: right;">Debit Amount</th>
                                                                                                    <th style="text-align: right;">Credit Amount</th>
                                                                                                    <th style="max-width:20px;width:20px;">...</th>
                                                                                                    <th>Transaction Date</th>
                                                                                                    <th style="max-width:60px;width:60px;">Ref. Doc. No.</th>
                                                                                                    <th style="max-width:20px;width:20px;">...</th>
                                                                                                    <?php
                                                                                                    if ($canVwRcHstry === true) {
                                                                                                    ?>
                                                                                                        <th style="max-width:20px;width:20px;">...</th>
                                                                                                    <?php } ?>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <?php
                                                                                                $cntr = 0;
                                                                                                $resultRw = get_One_Batch_Trns(
                                                                                                    $sbmtdJrnlBatchID,
                                                                                                    $lmtSze
                                                                                                );
                                                                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                                                                $ttlTrsctnDbtAmnt = 0;
                                                                                                $ttlTrsctnCrdtAmnt = 0;
                                                                                                $ttlTrsctnNetAmnt = 0;
                                                                                                $ornlMkReadOnly = $mkReadOnly;
                                                                                                $trnsBrkDwnVType = "VIEW";
                                                                                                while ($cntr < $maxNoRows) {
                                                                                                    $trsctnLineID = -1;
                                                                                                    $trsctnSmryLineID = -1;
                                                                                                    $trsctnLineDesc = "";
                                                                                                    $trsctnLineRefDoc = "";
                                                                                                    $funcCurID = -1;
                                                                                                    $funcCurNm = "";
                                                                                                    $trsctnDbtAmnt = 0;
                                                                                                    $trsctnCrdtAmnt = 0;
                                                                                                    $trsctnNetAmnt = 0;
                                                                                                    $entrdCurID = -1;
                                                                                                    $entrdAmnt = 0.00;
                                                                                                    $entrdCurNm = "";
                                                                                                    $trsctnAcntID = -1;
                                                                                                    $trsctnAcntNm = -1;
                                                                                                    $acntCrncyRate = 0;
                                                                                                    $funcCrncyRate = 0;
                                                                                                    $trsctnLineStatus = "0";
                                                                                                    $trsctnLineDate = "";
                                                                                                    if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                                        $trsctnLineID = (float) $rowRw[0];
                                                                                                        $trsctnSmryLineID = (float) $rowRw[24];
                                                                                                        if ($trsctnSmryLineID > 0) {
                                                                                                            $canEdt = FALSE;
                                                                                                            $mkReadOnly = "readonly=\"true\"";
                                                                                                        } else {
                                                                                                            $mkReadOnly = $ornlMkReadOnly;
                                                                                                        }
                                                                                                        if ($mkReadOnly != "") {
                                                                                                            $trnsBrkDwnVType = "VIEW";
                                                                                                        } else {
                                                                                                            $trnsBrkDwnVType = "EDIT";
                                                                                                        }
                                                                                                        $trsctnLineDesc = $rowRw[3];
                                                                                                        $trsctnLineRefDoc = $rowRw[20];
                                                                                                        $funcCurID = (int) $rowRw[7];
                                                                                                        $funcCurNm = $rowRw[21];
                                                                                                        $trsctnDbtAmnt = (float) $rowRw[4];
                                                                                                        $trsctnCrdtAmnt = (float) $rowRw[5];
                                                                                                        $trsctnNetAmnt = (float) $rowRw[10];
                                                                                                        $entrdCurID = (int) $rowRw[14];
                                                                                                        $entrdAmnt = (float) $rowRw[12];
                                                                                                        $entrdCurNm = $rowRw[13];
                                                                                                        $trsctnAcntID = $rowRw[9];
                                                                                                        $trsctnAcntNm = $rowRw[1] . "." . $rowRw[2];
                                                                                                        $acntCrncyRate = (float) $rowRw[19];
                                                                                                        $funcCrncyRate = (float) $rowRw[18];
                                                                                                        $trsctnLineStatus = $rowRw[11];
                                                                                                        $trsctnLineDate = $rowRw[6];

                                                                                                        $ttlTrsctnDbtAmnt = $ttlTrsctnDbtAmnt + $trsctnDbtAmnt;
                                                                                                        $ttlTrsctnCrdtAmnt = $ttlTrsctnCrdtAmnt + $trsctnCrdtAmnt;
                                                                                                        $ttlTrsctnNetAmnt = $ttlTrsctnNetAmnt + $trsctnNetAmnt;
                                                                                                        $isPosted = ($row[11] == "1") ? "true" : "false";
                                                                                                    }
                                                                                                    $cntr += 1;
                                                                                                ?>
                                                                                                    <tr id="oneJrnlBatchDetRow_<?php echo $cntr; ?>">
                                                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                                                                        <td class="lovtd">
                                                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountID" value="<?php echo $trsctnAcntID; ?>" style="width:100% !important;">
                                                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsSmryLnID" value="<?php echo $trsctnSmryLineID; ?>" style="width:100% !important;">
                                                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_SlctdAmtBrkdwns" value="" style="width:100% !important;">
                                                                                                            <?php
                                                                                                            if ($canEdt === true) {
                                                                                                            ?>
                                                                                                                <div class="input-group" style="width:100% !important;">
                                                                                                                    <input type="text" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountNm" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountNm" value="<?php echo $trsctnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountID', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_AccountNm', 'clear', 1, '', function () {

                                                                                                                        });">
                                                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                            <?php } else { ?>
                                                                                                                <span><?php echo $trsctnAcntNm; ?></span>
                                                                                                            <?php } ?>
                                                                                                        </td>
                                                                                                        <td class="lovtd" style="">
                                                                                                            <?php
                                                                                                            if ($canEdt === true) {
                                                                                                            ?>
                                                                                                                <input type="text" class="form-control rqrdFld jbDetDesc" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_LineDesc" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_LineDesc', 'oneJrnlBatchDetLinesTable', 'jbDetDesc');">
                                                                                                            <?php } else { ?>
                                                                                                                <span><?php echo $trsctnLineDesc; ?></span>
                                                                                                            <?php } ?>
                                                                                                        </td>
                                                                                                        <td class="lovtd">
                                                                                                            <div class="" style="width:100% !important;">
                                                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $funcCurNm; ?>" readonly="true" style="width:100% !important;">
                                                                                                                <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                                                        $('#oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                                                                    });">
                                                                                                                    <span class="" id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $funcCurNm; ?></span>
                                                                                                                </label>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                        <td class="lovtd">
                                                                                                            <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_DebitAmnt" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_DebitAmnt" value="<?php
                                                                                                                                                                                                                                                                                                            echo number_format($trsctnDbtAmnt, 2);
                                                                                                                                                                                                                                                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_DebitAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchDetTtl();">
                                                                                                        </td>
                                                                                                        <td class="lovtd">
                                                                                                            <input type="text" class="form-control rqrdFld jbDetCrdt" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_CreditAmnt" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_CreditAmnt" value="<?php
                                                                                                                                                                                                                                                                                                                echo number_format(
                                                                                                                                                                                                                                                                                                                    $trsctnCrdtAmnt,
                                                                                                                                                                                                                                                                                                                    2
                                                                                                                                                                                                                                                                                                                );
                                                                                                                                                                                                                                                                                                                ?>" onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_CreditAmnt', 'oneJrnlBatchDetLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllJrnlBatchDetTtl();">
                                                                                                        </td>
                                                                                                        <td class="lovtd">
                                                                                                            <?php
                                                                                                            if ($rowRw[22] != ",") {
                                                                                                            ?>
                                                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Interface Table Breakdown" onclick="getAccbTransSrchDet(<?php echo $trsctnLineID; ?>, 'Transaction ID', <?php echo $isPosted; ?>, true, '', '', 'Breakdown of Source Transactions', 'ShowDialog', function () {});" style="padding:2px !important;" style="padding:2px !important;">
                                                                                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                                </button>
                                                                                                            <?php } else { ?>
                                                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Denominational Breakdown" onclick="getAccbCashBreakdown(<?php echo $trsctnLineID; ?>, 'ShowDialog', 'Transaction Amount Breakdown', '<?php echo $trnsBrkDwnVType; ?>', '<?php echo $defaultBrkdwnLOV; ?>', '', '');" style="padding:2px !important;" style="padding:2px !important;">
                                                                                                                    <img src="cmn_images/cash_breakdown.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                                </button>
                                                                                                            <?php } ?>
                                                                                                        </td>
                                                                                                        <td class="lovtd">
                                                                                                            <?php
                                                                                                            if ($canEdt === true) {
                                                                                                            ?>
                                                                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100% !important;">
                                                                                                                    <input class="form-control" size="16" type="text" id="oneJrnlBatchDetRow<?php echo $cntr; ?>_TransDte" value="<?php echo $trsctnLineDate; ?>">
                                                                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                                </div>
                                                                                                            <?php } else { ?>
                                                                                                                <span><?php echo $trsctnLineDate; ?></span>
                                                                                                            <?php } ?>
                                                                                                        </td>
                                                                                                        <td class="lovtd" style="">
                                                                                                            <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneJrnlBatchDetRow<?php echo $cntr; ?>_RefDoc" name="oneJrnlBatchDetRow<?php echo $cntr; ?>_RefDoc" value="<?php echo $trsctnLineRefDoc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneJrnlBatchDetRow<?php echo $cntr; ?>_RefDoc', 'oneJrnlBatchDetLinesTable', 'jbDetRfDc');">
                                                                                                        </td>
                                                                                                        <td class="lovtd">
                                                                                                            <?php
                                                                                                            if ($canDel === true && $canEdt === true) {
                                                                                                            ?>
                                                                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbJrnlBatchDetLn('oneJrnlBatchDetRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Journal Line">
                                                                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                                                </button>
                                                                                                            <?php } ?>
                                                                                                        </td>
                                                                                                        <?php
                                                                                                        if ($canVwRcHstry === true) {
                                                                                                        ?>
                                                                                                            <td class="lovtd">
                                                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                                                        echo urlencode(encrypt1(($trsctnLineID . "|accb.accb_trnsctn_details|transctn_id"),
                                                                                                                                                                                                                                                                            $smplTokenWord1
                                                                                                                                                                                                                                                                        ));
                                                                                                                                                                                                                                                                        ?>');" style="padding:2px !important;">
                                                                                                                    <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                                </button>
                                                                                                            </td>
                                                                                                        <?php } ?>
                                                                                                    </tr>
                                                                                                <?php
                                                                                                }
                                                                                                $mkReadOnly = $ornlMkReadOnly;
                                                                                                ?>
                                                                                            </tbody>
                                                                                            <tfoot>
                                                                                                <tr>
                                                                                                    <th>&nbsp;</th>
                                                                                                    <th>&nbsp;</th>
                                                                                                    <th>TOTALS:</th>
                                                                                                    <th style=""><?php echo $crncyIDNm; ?></th>
                                                                                                    <th style="text-align: right;">
                                                                                                        <?php
                                                                                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbDbtsTtlBtn\">" . number_format(
                                                                                                            $ttlTrsctnDbtAmnt,
                                                                                                            2,
                                                                                                            '.',
                                                                                                            ','
                                                                                                        ) . "</span>";
                                                                                                        ?>
                                                                                                        <input type="hidden" id="myCptrdJbDbtsTtlVal2" value="<?php echo $ttlTrsctnDbtAmnt; ?>">
                                                                                                    </th>
                                                                                                    <th style="text-align: right;">
                                                                                                        <?php
                                                                                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdJbCrdtsTtlBtn\">" . number_format(
                                                                                                            $ttlTrsctnCrdtAmnt,
                                                                                                            2,
                                                                                                            '.',
                                                                                                            ','
                                                                                                        ) . "</span>";
                                                                                                        ?>
                                                                                                        <input type="hidden" id="myCptrdJbCrdtsTtlVal2" value="<?php echo $ttlTrsctnCrdtAmnt; ?>">
                                                                                                    </th>
                                                                                                    <th style="">&nbsp;</th>
                                                                                                    <th style="">&nbsp;</th>
                                                                                                    <th style="">&nbsp;</th>
                                                                                                    <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                                                    <?php
                                                                                                    if ($canVwRcHstry === true) {
                                                                                                    ?>
                                                                                                        <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                                                                    <?php } ?>
                                                                                                </tr>
                                                                                            </tfoot>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
<?php
                                        }
                                    }
                                }
                            }
                        }

?>