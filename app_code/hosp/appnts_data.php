<?php
$canAddADRecs = test_prmssns($dfltPrvldgs[11], $mdlNm);//unused
$canEdtADRecs = test_prmssns($dfltPrvldgs[12], $mdlNm);
$canDelADRecs = test_prmssns($dfltPrvldgs[13], $mdlNm); //unused
$canExportADRecs = false;//test_prmssns($dfltPrvldgs[999], $mdlNm);
$canImportADRecs = false;//test_prmssns($dfltPrvldgs[999], $mdlNm); 

//MEDICAL CONSULTATION
$canAddMCRecs = test_prmssns($dfltPrvldgs[35], $mdlNm); //unused
$canEdtMCRecs = test_prmssns($dfltPrvldgs[36], $mdlNm);
$canDelMCRecs = test_prmssns($dfltPrvldgs[37], $mdlNm);

//VITAL STATISTICS
$canAddVSRecs = test_prmssns($dfltPrvldgs[0], $mdlNm);//unused
$canEdtVSRecs = test_prmssns($dfltPrvldgs[0], $mdlNm);//unused
$canDelVSRecs = test_prmssns($dfltPrvldgs[0], $mdlNm);//unused

//LAB INVESTIGATIONS
$canAddLIRecs = test_prmssns($dfltPrvldgs[23], $mdlNm);//unused
$canEdtLIRecs = test_prmssns($dfltPrvldgs[24], $mdlNm);//unused
$canDelLIRecs = test_prmssns($dfltPrvldgs[25], $mdlNm);//unused

//PRESCRIPTIONS
$canAddDPRecs = test_prmssns($dfltPrvldgs[38], $mdlNm);//unused
$canEdtDPRecs = test_prmssns($dfltPrvldgs[39], $mdlNm);//unused
$canDelDPRecs = test_prmssns($dfltPrvldgs[40], $mdlNm);//unused


$usrID = $_SESSION['USRID'];
$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$qStrtDte = "01-Jan-1900 00:00:00";
$qEndDte = "31-Dec-4000 23:59:59";

$lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
$pkID = $PKeyID;

$prsnBranchID = get_HospPerson_BranchID($prsnid);
$prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", "pasn.get_prsn_siteid($prsnid)");

$branchSrchIn = isset($_POST['branchSrchIn']) ? cleanInputData($_POST['branchSrchIn']) : $prsnBranchID;
$statusSrchIn = isset($_POST['statusSrchIn']) ? cleanInputData($_POST['statusSrchIn']) : "All Statuses";
$crdtTypeSrchIn = isset($_POST['crdtTypeSrchIn']) ? cleanInputData($_POST['crdtTypeSrchIn']) : "All Service Types";



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

if ($branchSrchIn == -1) {
    
} else {
    $prsnBranchID = $branchSrchIn;
}

$error = "";
$searchAll = true;

$srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
$srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
    $srchFor = str_replace("%%", "%", $srchFor);
}

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "CHECK-IN") {
            $rowCnt = checkInAppointment($PKeyID);
            if ($rowCnt > 0) {
                echo "Check-In Successful";
            } else {
                echo "Check-In Failed";
            }
            exit();
        } 
        else if ($qstr == "CHECK-OUT") {
            $rowCnt = checkOutAppointment($PKeyID);
            if ($rowCnt > 0) {
                echo "Check-Out Successful";
            } else {
                echo "Check-Out Failed";
            }
            exit();
        } 
        else if ($qstr == "CANCEL-APPOINTMENT") {
            $rowCnt = cancelAppointment($PKeyID);
            if ($rowCnt > 0) {
                echo "Appointment Cancelled Successful";
            } else {
                echo "Appointment Cancellation Failed";
            }
            exit();
        }
        else if ($qstr == "REOPEN-APPOINTMENT") {
            $rowCnt = reopenAppointment($PKeyID);
            if ($rowCnt > 0) {
                echo "Appointment Re-Opened Successful";
            } else {
                echo "Appointment Re-Open Failed";
            }
            exit();
        }
        else if ($qstr == "DELETE") {
            if ($actyp == 1) {//VITALS
                $recInUse = isVtl_id_InActiveUse($PKeyID);
                if ($recInUse) {
                    echo "SORRY! Record is in use";
                    exit();
                } else {
                    $rowCnt = deleteVitals($PKeyID);
                if ($rowCnt > 0) {
                    echo "Record Deleted Successfully";
                  } else {
                    echo "Failed to Delete Record";
                  }
                  exit();
                }
            } else if ($actyp == 2) {//CONSULTATION
                $sctn = isset($_POST['sctn']) ? cleanInputData($_POST['sctn']) : 0;
                $diagID = isset($_POST['diagID']) ? cleanInputData($_POST['diagID']) : 0;
                if($sctn == 1){//DIAGNOSIS
                    $recInUse = isDiag_id_InActiveUse($diagID);
                    if ($recInUse) {
                        echo "SORRY! Diagnosis Record is in use";
                        exit();
                    } else {
                        $rowCnt = deleteDiagnosis($diagID);
                    if ($rowCnt > 0) {
                        echo "Diagnosis Record Deleted Successfully";
                      } else {
                        echo "Failed to Delete Diagnosis Record diagID=".$diagID;
                      }
                      exit();
                    } 
                } else {
                   $recInUse = isCnsltn_id_InActiveUse($PKeyID);
                    if ($recInUse) {
                        echo "SORRY! Record is in use";
                        exit();
                    } else {
                        $rowCnt = deleteConsultation($PKeyID);
                    if ($rowCnt > 0) {
                        echo "Record Deleted Successfully";
                      } else {
                        echo "Failed to Delete Record";
                      }
                      exit();
                    } 
                }
                
            } else if ($actyp == 3) {
                $rowCnt = deleteAppntmntDataItems($PKeyID);
                if ($rowCnt > 0) {
                    echo "Line Deleted Successfully";
                } else {
                    echo "Failed to Delete Line";
                }
                exit();
            } 
            else if ($actyp == 4) {//Lab
                $recInUse = isInvstgtn_id_InActiveUse($PKeyID);
                if ($recInUse) {
                    echo "SORRY! Record is in use";
                    exit();
                } else {
                    $rowCnt = deleteInvstgtn($PKeyID);
                if ($rowCnt > 0) {
                    echo "Record Deleted Successfully";
                  } else {
                    echo "Failed to Delete Record";
                  }
                  exit();
                }
            } else if ($actyp == 5) {//Pharmacy
                $recInUse = isPrscptn_id_InActiveUse($PKeyID);
                if ($recInUse) {
                    echo "SORRY! Record is in use";
                    exit();
                } else {
                    $rowCnt = deleteMedication($PKeyID);
                if ($rowCnt > 0) {
                    echo "Record Deleted Successfully";
                  } else {
                    echo "Failed to Delete Record";
                  }
                  exit();
                }
            } else if ($actyp == 6) {//IN-HOUSE ADMISSION
                $recInUse = isAdmsn_id_InActiveUse($PKeyID);
                if ($recInUse) {
                    echo "SORRY! Record is in use";
                    exit();
                } else {
                    $rowCnt = deleteInptntAdmsn($PKeyID);
                if ($rowCnt > 0) {
                    echo "Record Deleted Successfully";
                  } else {
                    echo "Failed to Delete Record";
                  }
                  exit();
                }
            } 
            else if ($actyp == 7){//RADIOLOGY REQUESTS
                
            } else if ($actyp == 8){//CONSULTATION-LINKED RECOMMENDED SERVICES
                $rcmddSrvsID = isset($_POST['rcmddSrvsID']) ? cleanInputData($_POST['rcmddSrvsID']) : -1;
                $recInUse = isRcmd_srv_id_InActiveUse($rcmddSrvsID);
                if ($recInUse) {
                    echo "SORRY! Failed to delete Recommended Service because data exists";
                    exit();
                } else {
                    $rowCnt = deleteRcmdSrvs($rcmddSrvsID);
                if ($rowCnt > 0) {
                    echo "Record Deleted Successfully";
                  } else {
                    echo "Failed to Delete Record";
                  }
                  exit();
                }
            }
        } 
        else if ($qstr == "UPDATE") {
            if ($actyp == 1) {//VITALS
                global $usrID;
                $dateStr = getDB_Date_time();
                $created_by = $usrID;
                $last_update_by = $usrID;
                $creation_date = $dateStr;
                $last_update_date = $dateStr;
                
                $recCntInst = 0;
                $recCntUpdt = 0;
                
                $vtl_id = isset($_POST['frmVitalsID']) ? (int)cleanInputData($_POST['frmVitalsID']) : -1;
                $appntmnt_id = isset($_POST['frmAppntmntID']) ? (int)cleanInputData($_POST['frmAppntmntID']) : -1;
                $weight = isset($_POST['frmVitalsWeight']) ? (float)cleanInputData($_POST['frmVitalsWeight']) : 0;
                $height = isset($_POST['frmVitalsHeight']) ? (float)cleanInputData($_POST['frmVitalsHeight']) : 0;
                $bp_systlc = isset($_POST['frmVitalsBPSystolic']) ? (float)cleanInputData($_POST['frmVitalsBPSystolic']) : 0;
                $bp_diastlc = isset($_POST['frmVitalsBPDiastolic']) ? (float)cleanInputData($_POST['frmVitalsBPDiastolic']) : 0;
                $pulse = isset($_POST['frmVitalsPulse']) ? (float)cleanInputData($_POST['frmVitalsPulse']) : 0;
                $resptn = isset($_POST['frmVitalsRsprtn']) ? cleanInputData($_POST['frmVitalsRsprtn']) : 0;
                if($resptn == ''){
                    $resptn = 0;
                }
                $body_tmp = isset($_POST['frmVitalsBodyTemp']) ? (float)cleanInputData($_POST['frmVitalsBodyTemp']) : 0;
                $oxgn_satn = isset($_POST['frmVitalsOxygenStrtn']) ? cleanInputData($_POST['frmVitalsOxygenStrtn']) : 0;
                if($oxgn_satn == ''){
                    $oxgn_satn = 0;
                }
                $head_circum = isset($_POST['frmVitalsHeadCircm']) ? cleanInputData($_POST['frmVitalsHeadCircm']) : 0; 
                if($head_circum == ''){
                    $head_circum = 0;
                }
                $waist_circum = isset($_POST['frmVitalsWaistCircm']) ? cleanInputData($_POST['frmVitalsWaistCircm']) : 0;
                if($waist_circum == ''){
                    $waist_circum = 0;
                }
                $bmi = isset($_POST['frmVitalsBMI']) ? cleanInputData($_POST['frmVitalsBMI']) : 0;
                if($bmi == ''){
                    $bmi = 0;
                }
                $bmi_status = isset($_POST['frmVitalsBMIStatus']) ? cleanInputData($_POST['frmVitalsBMIStatus']) : '';
                $bowel_actn = isset($_POST['frmVitalsBowelAction']) ? cleanInputData($_POST['frmVitalsBowelAction']) : '';
                $cmnts = isset($_POST['frmVitalsCmnts']) ? cleanInputData($_POST['frmVitalsCmnts']) : '';
                $tmp_loc = isset($_POST['frmVitalsTempLoc']) ? cleanInputData($_POST['frmVitalsTempLoc']) : '';
                $bp_status = isset($_POST['frmVitalsPBStatus']) ? cleanInputData($_POST['frmVitalsPBStatus']) : '';
                

                if($weight <= 0 || $height <= 0 || $bp_systlc <= 0 || $bp_diastlc <= 0 
				|| $pulse <= 0 || $body_tmp <= 0 || $tmp_loc == ''){    
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . 'Please complete all required field before saving!<br/></div>';
                    exit();
                } else {
                    if ($vtl_id > 0) {
                         $recCntUpdt = updateVitals($vtl_id,$appntmnt_id,$weight,$height,$bp_systlc,$bp_diastlc,$pulse,$resptn,$body_tmp,$oxgn_satn,$head_circum,$waist_circum,
                                 $bmi,$bmi_status,$bowel_actn,$cmnts,$created_by,$creation_date,$last_update_by,$last_update_date,$tmp_loc,$bp_status);

                        if($recCntUpdt > 0){
                            echo json_encode(array("vtl_id" => $vtl_id, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                        } else {
                            echo '<span style="color:red;font-weight:bold !important;">Saving Failed!<br/></span>';
                        }
                    } else {
                        $vtl_id = (int)getVtl_id();
                        $recCntInst = insertVitals($vtl_id,$appntmnt_id,$weight,$height,$bp_systlc,$bp_diastlc,$pulse,$resptn,$body_tmp,$oxgn_satn,$head_circum,$waist_circum,
                                $bmi,$bmi_status,$bowel_actn,$cmnts,$created_by,$creation_date,$last_update_by,$last_update_date,$tmp_loc,$bp_status);

                         if($recCntInst > 0){
                            echo json_encode(array("vtl_id" => $vtl_id, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                        } else {
                            echo '<span style="color:red;font-weight:bold !important;">Saving Failed!<br/></span>';
                        }
                    }
                }
                exit();
            } 
            else if ($actyp == 2) {//CONSULTATION
                $slctdDiagnosis = isset($_POST['slctdDiagnosis']) ? cleanInputData($_POST['slctdDiagnosis']) : '';
                
                $cnsltn_id = isset($_POST['cnsltnID']) ? cleanInputData($_POST['cnsltnID']) : -1;
                $appntmnt_id = isset($_POST['appntmntID']) ? cleanInputData($_POST['appntmntID']) : -1;
                $patient_complaints = isset($_POST['patientCmplnt']) ? cleanInputData($_POST['patientCmplnt']) : '';
                $physical_examination = isset($_POST['physicalExam']) ? cleanInputData($_POST['physicalExam']) : '';
                $cmnts = isset($_POST['cnsltnCmnts']) ? cleanInputData($_POST['cnsltnCmnts']) : '';       
                $admission_cmnts = isset($_POST['docAdmsnInstructions']) ? cleanInputData($_POST['docAdmsnInstructions']) : '';
                $admission_checkin_date = isset($_POST['docAdmsnCheckInDate']) ? cleanInputData($_POST['docAdmsnCheckInDate']) : '';
                $admission_no_of_days = isset($_POST['docAdmsnCheckInNoOfDays']) ? cleanInputData($_POST['docAdmsnCheckInNoOfDays']) : 0;
                
                if($admission_checkin_date != ""){
                    $admission_checkin_date = cnvrtDMYToYMD($admission_checkin_date);
                }
                
                if($admission_no_of_days == ""){
                    $admission_no_of_days = 0;
                }
  
                global $usrID;
                $dateStr = getDB_Date_time();
                $created_by = $usrID;
                $last_update_by = $usrID;
                $creation_date = $dateStr;
                $last_update_date = $dateStr;
                
                $mErrMsg = "";
                $rtrnMsg = "";
                
                $recCntInst = 0;
                $recCntUpdt = 0;
                $recCntInst1 = 0;
                $recCntUpdt1 = 0;
                
                $errCnt = 0;
                
                if($patient_complaints == ""){    
                    $mErrMsg.= 'Please provide patient complaint!<br/>';
                } else {
                    if ($cnsltn_id > 0) {
                         $recCntUpdt = updateConsultation($cnsltn_id, $appntmnt_id, $patient_complaints, $physical_examination, $cmnts, $created_by, $creation_date, 
                                 $last_update_by, $last_update_date, $admission_cmnts, $admission_checkin_date, $admission_no_of_days);
                
                        if($recCntUpdt > 0){
                            $rtrnMsg.= 'Consultation Successfully Saved<br/>';
                        } else {
                            $mErrMsg.= 'Saving Failed!<br/>';
                            $errCnt = $errCnt + 1;
                        }
                        
                    } else {
                        $cnsltn_id = getCnsltn_id();
                        $recCntInst = insertConsultation($cnsltn_id, $appntmnt_id, $patient_complaints, $physical_examination, $cmnts, $created_by, $creation_date, 
                                $last_update_by, $last_update_date, $admission_cmnts, $admission_checkin_date, $admission_no_of_days);
              
                         if($recCntInst > 0){
                            $rtrnMsg.= 'Consultation Successfully Saved<br/>';
                        } else {
                            $mErrMsg.= 'Saving Failed!<br/>';
                            $errCnt = $errCnt + 1;
                        }
                    }
                }

                
                if (trim($slctdDiagnosis, "|~") != "") {
                    $variousRows = explode("|", trim($slctdDiagnosis, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 4) {
                            $diag_id = cleanInputData1($crntRow[0]);
                            $disease_id = (int) (cleanInputData1($crntRow[1]));
                            //$cnsltn_id = (int) (cleanInputData1($crntRow[2]));
                            $cmnts = cleanInputData1($crntRow[3]);
                            if ($diag_id > 0) {
                                $recCntUpdt1 = $recCntUpdt1 + updateDiagnosis($diag_id, $disease_id, $created_by, $creation_date, $last_update_by, $last_update_date, $cnsltn_id, $cmnts);
                            } else {
                                $diag_id = getDiag_id();
                                $recCntInst1 = $recCntInst1 + insertDiagnosis($diag_id, $disease_id, $created_by, $creation_date, $last_update_by, $last_update_date, $cnsltn_id, $cmnts);
                            }
                        }
                    }
                    
                   $rtrnMsg.= "<i>$recCntInst1 diagnosis record(s) inserted</br>$recCntUpdt1 diagnosis record(s) updated</i><br/>";
                } else {
                    $mErrMsg.= 'Please provide one Diagnosis Record before saving!<br/>';
                    $errCnt = $errCnt + 1;
                }          
                
                if($errCnt > 0){
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/><span style="color:red;font-weight:bold !important;">'
                       .$mErrMsg.
                         '</span><br/></div>';
                } else {
                    echo json_encode(array("appntmnt_id" => $appntmnt_id, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>".$rtrnMsg."</span>")); 
                }
                
                 exit();
            } 
            else if ($actyp == 3) {//APPOINTMENT DATA ITEMS
                $slctdAppntmntDataItems = isset($_POST['slctdAppntmntDataItems']) ? cleanInputData($_POST['slctdAppntmntDataItems']) : "";

                $vldtyUpdtCnt = 0;
                $rsltCrtCnt = 0;
                $rsltUpdtCnt = 0;

                if (1 > 0) {
                    $dateStr = getDB_Date_time();
                    $recCntInst = 0;
                    $recCntUpdt = 0;

                    if (trim($slctdAppntmntDataItems, "|~") != "") {

                        $variousRows = explode("|", trim($slctdAppntmntDataItems, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 5) {
                                $appntmntDataItemsID = (int) (cleanInputData1($crntRow[0]));
                                $appntmntID = (int) (cleanInputData1($crntRow[1]));
                                $itemID = (int) cleanInputData1($crntRow[2]);
                                $qty = (float) cleanInputData1($crntRow[3]);
                                $cmnts = cleanInputData1($crntRow[4]);

                                if ($appntmntDataItemsID > 0) {
                                    $recCntUpdt = $recCntUpdt + updateAppntmntDataItems($appntmntDataItemsID, $appntmntID, $itemID, $qty, $cmnts, $usrID, $dateStr);
                                } else {
                                    $appntmntDataItemsID = getAppntmntDataItemsID();
                                    $recCntInst = $recCntInst + insertAppntmntDataItems($appntmntDataItemsID, $appntmntID, $itemID, $qty, $cmnts, $usrID, $dateStr);
                                }
                            }
                        }
                    }

                    echo json_encode(array("recCntInst" => $recCntInst, "recCntUpdt" => $recCntUpdt));
                    exit();
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . '<br/>Please complete all required fields before proceeding!<br/></div>';
                    exit();
                }
            } 
            else if ($actyp == 4) {//INVESTIGATION
                $slctdInvstgtn = isset($_POST['slctdInvstgtn']) ? cleanInputData($_POST['slctdInvstgtn']) : '';
                global $usrID;
                $dateStr = getDB_Date_time();
                $created_by = $usrID;
                $last_update_by = $usrID;
                $creation_date = $dateStr;
                $last_update_date = $dateStr;
                $recCntInst = 0;
                $recCntUpdt = 0;
                if (trim($slctdInvstgtn, "|~") != "") {
                    $variousRows = explode("|", trim($slctdInvstgtn, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 9) {
                            $invstgtn_id = (int) (cleanInputData1($crntRow[0]));
                            $cnsltn_id = (int) (cleanInputData1($crntRow[1]));
                            $doc_cmnts = cleanInputData1($crntRow[2]);
                            $lab_cmnts = cleanInputData1($crntRow[3]);
                            $lab_loc = cleanInputData1($crntRow[4]);
                            $lab_results = cleanInputData1($crntRow[5]);
                            $dest_appntmnt_id = (int) (cleanInputData1($crntRow[6]));
                            $invstgtn_list_id = (int) (cleanInputData1($crntRow[7]));
                            $do_inhouse = cleanInputData1($crntRow[8]);
                            $doInhs = true;
                            if ($do_inhouse == "NO") {
                                    $doInhs = false;
                            }
                            if ($invstgtn_id > 0) {
                                $recCntUpdt = $recCntUpdt + updateInvstgtn($invstgtn_id, $cnsltn_id, $created_by, $creation_date, $last_update_by, $last_update_date, $doc_cmnts, $lab_cmnts, $lab_loc, $lab_results, $dest_appntmnt_id, $invstgtn_list_id, $doInhs);
                            
                                $itm_id = getInvstgtnSrvsItmId($invstgtn_id);
                                if($doInhs){
                                    $cntX = checkUnlinkedAppntmntDataItmExistince($itm_id, $dest_appntmnt_id, $invstgtn_id);
                                    if($cntX <= 0 && $itm_id > 0){
                                        $ttlQty = 1; //($dose_qty * $dsge_freqncy_no * $tot_duratn);
                                        $appntmntDataItemsID = getAppntmntDataItemsID();
                                        $uomID = "(SELECT inv.get_invitm_uom_id($itm_id))";
                                        insertAppntmntDataItems($appntmntDataItemsID, $dest_appntmnt_id, $itm_id, $ttlQty, "", $usrID, $dateStr, $uomID, $invstgtn_id);
                                    }
                                } else {
                                    if($itm_id > 0){
                                        deleteAppntmntDataItem($itm_id, $dest_appntmnt_id, $invstgtn_id); 
                                    }
                                }
                            } else {
                                $invstgtn_id = getInvstgtn_id();
                                $ct = insertInvstgtn($invstgtn_id, $cnsltn_id, $created_by, $creation_date, $last_update_by, $last_update_date, $doc_cmnts, $lab_cmnts, $lab_loc, $lab_results, $dest_appntmnt_id, $invstgtn_list_id, $doInhs);
                            
                                $recCntInst = $recCntInst + $ct;
                                if($ct > 0) {
                                    if($doInhs){
                                        $itm_id = getInvstgtnSrvsItmId($invstgtn_id);
                                        
                                        $cntX = checkUnlinkedAppntmntDataItmExistince($itm_id, $dest_appntmnt_id, $invstgtn_id);
                                        if($cntX <= 0 && $itm_id > 0){
                                            $ttlQty = 1; //($dose_qty * $dsge_freqncy_no * $tot_duratn);
                                            $appntmntDataItemsID = getAppntmntDataItemsID();
                                            $uomID = "(SELECT inv.get_invitm_uom_id($itm_id))";
                                            insertAppntmntDataItems($appntmntDataItemsID, $dest_appntmnt_id, $itm_id, $ttlQty, "", $usrID, $dateStr, $uomID);
                                        }
                                    }
                                }
                                $ct = 0;
                            }
                        }
                    }
                    echo "<span style='color:green;font-weight:bold !important;'><i>$recCntInst record(s) inserted</br>$recCntUpdt record(s) updated</i></span>";
                    exit();
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . 'Please provide one Lab Investigation Record before saving!<br/></div>';
                    exit();
                }
            } 
            else if ($actyp == 4.1) {//SAVE AND FINALIZE INVESTIGATION
                $slctdInvstgtn = isset($_POST['slctdInvstgtn']) ? cleanInputData($_POST['slctdInvstgtn']) : '';
                global $usrID;
                $dateStr = getDB_Date_time();
                $created_by = $usrID;
                $last_update_by = $usrID;
                $creation_date = $dateStr;
                $last_update_date = $dateStr;
                $recCntInst = 0;
                $recCntUpdt = 0;
                if (trim($slctdInvstgtn, "|~") != "") {
                    $variousRows = explode("|", trim($slctdInvstgtn, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 9) {
                            $invstgtn_id = (int) (cleanInputData1($crntRow[0]));
                            $cnsltn_id = (int) (cleanInputData1($crntRow[1]));
                            $doc_cmnts = cleanInputData1($crntRow[2]);
                            $lab_cmnts = cleanInputData1($crntRow[3]);
                            $lab_loc = cleanInputData1($crntRow[4]);
                            $lab_results = cleanInputData1($crntRow[5]);
                            $dest_appntmnt_id = (int) (cleanInputData1($crntRow[6]));
                            $invstgtn_list_id = (int) (cleanInputData1($crntRow[7]));
                            $do_inhouse = cleanInputData1($crntRow[8]);
                            $doInhs = true;
                            if ($do_inhouse == "NO") {
                                    $doInhs = false;
                            }
                            if ($invstgtn_id > 0) {
                                $recCntUpdt = $recCntUpdt + updateInvstgtn($invstgtn_id, $cnsltn_id, $created_by, $creation_date, $last_update_by, $last_update_date, $doc_cmnts, $lab_cmnts, $lab_loc, $lab_results, $dest_appntmnt_id, $invstgtn_list_id, $doInhs);
                            
                                $itm_id = getInvstgtnSrvsItmId($invstgtn_id);
                                if($doInhs){
                                    $cntX = checkUnlinkedAppntmntDataItmExistince($itm_id, $dest_appntmnt_id, $invstgtn_id);
                                    if($cntX <= 0 && $itm_id > 0){
                                        $ttlQty = 1; //($dose_qty * $dsge_freqncy_no * $tot_duratn);
                                        $appntmntDataItemsID = getAppntmntDataItemsID();
                                        $uomID = "(SELECT inv.get_invitm_uom_id($itm_id))";
                                        insertAppntmntDataItems($appntmntDataItemsID, $dest_appntmnt_id, $itm_id, $ttlQty, "", $usrID, $dateStr, $uomID, $invstgtn_id);
                                    }
                                } else {
                                    if($itm_id > 0){
                                        deleteAppntmntDataItem($itm_id, $dest_appntmnt_id, $invstgtn_id); 
                                    }
                                }
                            } else {
                                $invstgtn_id = getInvstgtn_id();
                                $ct = insertInvstgtn($invstgtn_id, $cnsltn_id, $created_by, $creation_date, $last_update_by, $last_update_date, $doc_cmnts, $lab_cmnts, $lab_loc, $lab_results, $dest_appntmnt_id, $invstgtn_list_id, $doInhs);
                            
                                $recCntInst = $recCntInst + $ct;
                                if($ct > 0) {
                                    if($doInhs){
                                        $itm_id = getInvstgtnSrvsItmId($invstgtn_id);
                                        
                                        $cntX = checkUnlinkedAppntmntDataItmExistince($itm_id, $dest_appntmnt_id, $invstgtn_id);
                                        if($cntX <= 0 && $itm_id > 0){
                                            $ttlQty = 1; //($dose_qty * $dsge_freqncy_no * $tot_duratn);
                                            $appntmntDataItemsID = getAppntmntDataItemsID();
                                            $uomID = "(SELECT inv.get_invitm_uom_id($itm_id))";
                                            insertAppntmntDataItems($appntmntDataItemsID, $dest_appntmnt_id, $itm_id, $ttlQty, "", $usrID, $dateStr, $uomID);
                                        }
                                    }
                                }
                                $ct = 0;
                            }
                        }
                    }
                    
                    
                    /*FINALIZE GOES HERE*/
                    if(1 == 1){
                        $recCntInstFNLZ = 0;
                        $recCntUpdtFNLZ = 0;

                        $vst_id = isset($_POST['vstId']) ? (int)cleanInputData($_POST['vstId']) : -1;
                        $appntmnt_id = isset($_POST['frmAppntmntID']) ? (int)cleanInputData($_POST['frmAppntmntID']) : -1;
                        $appntmnt_date = isset($_POST['frmAppntmntDate']) ? cleanInputData($_POST['frmAppntmntDate']) : date("d-M-Y H:i:s");
                        $srvs_type_id = isset($_POST['frmSrvsTypeId']) ? cleanInputData($_POST['frmSrvsTypeId']) : -1;
                        $prvdr_type = isset($_POST['frmPrvdrType']) ? cleanInputData($_POST['frmPrvdrType']) : 'G';
                        $cmnts = isset($_POST['frmAppntmntCmnts']) ? cleanInputData($_POST['frmAppntmntCmnts']) : '';
                        $lnkdSrvsTypeCode = isset($_POST['lnkdSrvsTypeCode']) ? cleanInputData($_POST['lnkdSrvsTypeCode']) : '';

                        $cnsltnAppntmnt_id = isset($_POST['cnsltnAppntmnt_id']) ? (int)cleanInputData($_POST['cnsltnAppntmnt_id']) : -1;
                        $srvsTypeSysCode = getSrvsTypeCodeFromID($srvs_type_id);
                        $frmCnsltnID = isset($_POST['frmCnsltnID']) ? cleanInputData($_POST['frmCnsltnID']) : -1;


                        $srvs_prvdr_prsn_id = -1;
                        $prvdr_grp_id = -1; 

                        $frmSrvsPrvdrId = isset($_POST['frmSrvsPrvdrId']) ? (int)cleanInputData($_POST['frmSrvsPrvdrId']) : -1;
                        if($prvdr_type == 'G'){
                            $prvdr_grp_id = $frmSrvsPrvdrId;
                        } else {
                            $srvs_prvdr_prsn_id = $frmSrvsPrvdrId;
                        }

                        if ($appntmnt_date != "") {
                            $appntmnt_date = cnvrtDMYTmToYMDTm($appntmnt_date);
                        } 

                        if($srvs_type_id == -1 || $appntmnt_date == "" || $frmSrvsPrvdrId == -1 || $prvdr_type == ""){    
                            echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                            . 'Please complete all required field before saving!<br/></div>';
                            exit();
                        } else {
                            $cnsltnID = $frmCnsltnID; //getAppntmntCnsltnID($appntmnt_id);
                            $srvsTypeItmId = (int)getGnrlRecNm("hosp.srvs_types", "type_id", "itm_id", $srvs_type_id);

                            if($lnkdSrvsTypeCode != ''){
                                if($lnkdSrvsTypeCode == "LI-0001"){//investigation
                                    $labCnt = getCnsltnLabOrRadialogyCnt($cnsltnID, "Lab");
                                    $rdlgyCnt = getCnsltnLabOrRadialogyCnt($cnsltnID, "Radiology");
                                    $recCntInstFNLZLB = 0;
                                    $recCntInstFNLZRD = 0;
                                    if($labCnt > 0){

                                        if($cnsltnID > 0){
                                            $rcExts = cnsltnLabInvstgnExist($cnsltnID, $cnsltnAppntmnt_id);
                                            if($rcExts){
                                                $appntmnt_id = getAppntmnt_id();
                                                $recCntInstFNLZLB = insertAppntmnt($appntmnt_id,$vst_id,$appntmnt_date,$srvs_type_id,$prvdr_type,$srvs_prvdr_prsn_id,$prvdr_grp_id,$cmnts,$created_by,$creation_date,$last_update_by,$last_update_date);

                                                execUpdtInsSQL("UPDATE hosp.invstgtn x SET dest_appntmnt_id = $appntmnt_id WHERE cnsltn_id = $cnsltnID AND dest_appntmnt_id = $cnsltnAppntmnt_id AND invstgtn_list_id IN "
                                                            . "( SELECT invstgtn_list_id FROM hosp.invstgtn_list WHERE x.invstgtn_list_id = invstgtn_list_id AND invstgtn_type = 'Lab')");

                                                if($srvsTypeItmId > 0 && $recCntInstFNLZLB > 0){
                                                    $ttlQty = 1;
                                                    $appntmntDataItemsID = getAppntmntDataItemsID();
                                                    $uomID = "(SELECT inv.get_invitm_uom_id($srvsTypeItmId))";
                                                    insertAppntmntDataItems($appntmntDataItemsID, $appntmnt_id, $srvsTypeItmId, $ttlQty, "", $usrID, $dateStr, $uomID, -1);
                                                }
                                            }
                                        }
                                    }

                                    if($rdlgyCnt > 0){
                                        $rdlgySysCode = "RD-0001";
                                        $rdlgy_srvs_type_id = getSrvsTypeIDFromSysCode($rdlgySysCode);
                                        $rdlgy_prvdr_grp_id = getSrvsTypeMainPrvdrGrp($rdlgySysCode);

                                        if($cnsltnID > 0){
                                            $rcExtsRD = cnsltnRadiologyInvstgnExist($cnsltnID, $cnsltnAppntmnt_id);
                                            if($rcExtsRD){
                                                $appntmnt_idRD = getAppntmnt_id();
                                                $recCntInstFNLZRD = insertAppntmnt($appntmnt_idRD,$vst_id,$appntmnt_date,$rdlgy_srvs_type_id,$prvdr_type,$srvs_prvdr_prsn_id,$rdlgy_prvdr_grp_id,$cmnts,$created_by,$creation_date,$last_update_by,$last_update_date);

                                                execUpdtInsSQL("UPDATE hosp.invstgtn x SET dest_appntmnt_id = $appntmnt_idRD WHERE cnsltn_id = $cnsltnID AND dest_appntmnt_id = $cnsltnAppntmnt_id AND invstgtn_list_id IN "
                                                    . "( SELECT invstgtn_list_id FROM hosp.invstgtn_list WHERE x.invstgtn_list_id = invstgtn_list_id AND invstgtn_type = 'Radiology')");

                                                //CHECK AND CREATE APPOINTMENT ITEMS FOR RADIOLOGY
                                                $srvsTypeItmIdRD = (int)getGnrlRecNm("hosp.srvs_types", "type_id", "itm_id", $rdlgy_srvs_type_id);

                                                if($srvsTypeItmIdRD > 0 && $recCntInstFNLZRD > 0){
                                                    $ttlQty = 1;
                                                    $appntmntDataItemsIDRD = getAppntmntDataItemsID();
                                                    $uomIDRD = "(SELECT inv.get_invitm_uom_id($srvsTypeItmIdRD))";
                                                    insertAppntmntDataItems($appntmntDataItemsIDRD, $appntmnt_idRD, $srvsTypeItmIdRD, $ttlQty, "", $usrID, $dateStr, $uomIDRD, -1);
                                                }

                                            }
                                        }
                                    }
                                    
                                    $rtnNoMsg = "";
                                    
                                    $recCntInstFNLZ = $recCntInstFNLZRD + $recCntInstFNLZLB;
                                    if($recCntInstFNLZ > 0){
                                        $rtnNoMsg = "Appointment Scheduled Successfully"; 
                                    } else {
                                        $rtnNoMsg = "Sorry! Nothing to Schedule!"; 
                                    }
                                    echo "<span style='color:green;font-weight:bold !important;'><i>$recCntInst record(s) inserted</br>$recCntUpdt record(s) updated"
                                            . "</br>$rtnNoMsg</i></span>";
                                    exit();
                                }
                            }
                        }
                    } 
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . 'Please provide one Lab Investigation Record before saving!<br/></div>';
                    exit();
                }
            } 
            else if ($actyp == 5) {//PHARMACY
                $slctdMedication = isset($_POST['slctdMedication']) ? cleanInputData($_POST['slctdMedication']) : '';
                global $usrID;
                $dateStr = getDB_Date_time();
                $created_by = $usrID;
                $last_update_by = $usrID;
                $creation_date = $dateStr;
                $last_update_date = $dateStr;
                $recCntInst = 0;
                $recCntUpdt = 0;
                if (trim($slctdMedication, "|~") != "") {
                    $variousRows = explode("|", trim($slctdMedication, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 17) {
                            $prscptn_id = (int) (cleanInputData1($crntRow[0]));
                            $cnsltn_id = (int) (cleanInputData1($crntRow[1]));
                            $doc_cmnts = cleanInputData1($crntRow[2]);
                            $pharm_cmnts = cleanInputData1($crntRow[3]);
                            $dspnsd_status = cleanInputData1($crntRow[4]);
                            $isDspnsd = true;
                            if ($dspnsd_status == "NO") {
                                    $isDspnsd = false;
                            }
                            $dest_appntmnt_id = (int) (cleanInputData1($crntRow[5]));
                            $itm_id = (int) (cleanInputData1($crntRow[6]));
                            $dose_qty = (int) (cleanInputData1($crntRow[7]));
                            $dose_uom = cleanInputData1($crntRow[8]);
                            $dsge_freqncy_no = (int) (cleanInputData1($crntRow[9]));
                            $dsge_freqncy_uom = cleanInputData1($crntRow[10]);
                            $tot_duratn = (int) (cleanInputData1($crntRow[11]));
                            $tot_duratn_uom = cleanInputData1($crntRow[12]);
                            $dose_form = cleanInputData1($crntRow[13]);
                            $admin_times = cleanInputData1($crntRow[14]);
                            $sub_allowed = cleanInputData1($crntRow[15]);
                            $isSubbAlwd = true;
                            if ($sub_allowed == "NO") {
                                    $isSubbAlwd = false;
                            }
                            $int_prscrbr_prsn_id = (int) (cleanInputData1($crntRow[16]));
                            if ($prscptn_id > 0) {
                                $recCntUpdt = $recCntUpdt + updateMedication($prscptn_id, $cnsltn_id, $created_by, $creation_date, $last_update_by, $last_update_date, $doc_cmnts, 
                                        $pharm_cmnts, $isDspnsd, $dest_appntmnt_id, $itm_id, $dose_qty, $dose_uom, $dsge_freqncy_no, $dsge_freqncy_uom, $tot_duratn, 
                                        $tot_duratn_uom, $dose_form, $admin_times, $isSubbAlwd, $int_prscrbr_prsn_id);
                                
                                if($isDspnsd){
                                    $cntX = checkUnlinkedAppntmntDataItmExistince($itm_id, $dest_appntmnt_id, $prscptn_id);
                                    if($cntX <= 0){
                                        $ttlQty = ($dose_qty * $dsge_freqncy_no * $tot_duratn);
                                        $appntmntDataItemsID = getAppntmntDataItemsID();
                                        $uomID = "(SELECT inv.get_invitm_uom_id($itm_id))";
                                        insertAppntmntDataItems($appntmntDataItemsID, $dest_appntmnt_id, $itm_id, $ttlQty, "", $usrID, $dateStr, $uomID, $prscptn_id);
                                    }
                                } else {
                                   deleteAppntmntDataItem($itm_id, $dest_appntmnt_id, $prscptn_id); 
                                }
                                
                            } else {
                                $prscptn_id = getPrscptn_id();
                                
                                $ct = insertMedication($prscptn_id, $cnsltn_id, $created_by, $creation_date, $last_update_by, $last_update_date, $doc_cmnts, 
                                        $pharm_cmnts, $isDspnsd, $dest_appntmnt_id, $itm_id, $dose_qty, $dose_uom, $dsge_freqncy_no, $dsge_freqncy_uom, $tot_duratn, 
                                        $tot_duratn_uom, $dose_form, $admin_times, $isSubbAlwd, $int_prscrbr_prsn_id);
                                $recCntInst = $recCntInst + $ct;
                                
                                
                                if($ct > 0) {
                                    if($isDspnsd){
                                        $cntX = 1; //checkUnlinkedAppntmntDataItmExistince($itm_id, $dest_appntmnt_id, $prscptn_id);
                                        if($cntX <= 0){
                                            $ttlQty = ($dose_qty * $dsge_freqncy_no * $tot_duratn);
                                            $appntmntDataItemsID = getAppntmntDataItemsID();
                                            $uomID = "(SELECT inv.get_invitm_uom_id($itm_id))";
                                            insertAppntmntDataItems($appntmntDataItemsID, $dest_appntmnt_id, $itm_id, $ttlQty, "", $usrID, $dateStr, $uomID, $prscptn_id);
                                        }
                                    }
                                }
                                $ct = 0;
                            }
                        }
                    }
                    echo "<span style='color:green;font-weight:bold !important;'><i>$recCntInst record(s) inserted</br>$recCntUpdt record(s) updated</i></span>";
                    exit();
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . 'Please provide one Medication Record before saving!<br/></div>';
                    exit();
                }
            } 
            else if ($actyp == 5.1) {//PHARMACY SAVE AND FINALIZE
                $slctdMedication = isset($_POST['slctdMedication']) ? cleanInputData($_POST['slctdMedication']) : '';
                global $usrID;
                $dateStr = getDB_Date_time();
                $created_by = $usrID;
                $last_update_by = $usrID;
                $creation_date = $dateStr;
                $last_update_date = $dateStr;
                $recCntInst = 0;
                $recCntUpdt = 0;
                if (trim($slctdMedication, "|~") != "") {
                    $variousRows = explode("|", trim($slctdMedication, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 17) {
                            $prscptn_id = (int) (cleanInputData1($crntRow[0]));
                            $cnsltn_id = (int) (cleanInputData1($crntRow[1]));
                            $doc_cmnts = cleanInputData1($crntRow[2]);
                            $pharm_cmnts = cleanInputData1($crntRow[3]);
                            $dspnsd_status = cleanInputData1($crntRow[4]);
                            $isDspnsd = true;
                            if ($dspnsd_status == "NO") {
                                    $isDspnsd = false;
                            }
                            $dest_appntmnt_id = (int) (cleanInputData1($crntRow[5]));
                            $itm_id = (int) (cleanInputData1($crntRow[6]));
                            $dose_qty = (int) (cleanInputData1($crntRow[7]));
                            $dose_uom = cleanInputData1($crntRow[8]);
                            $dsge_freqncy_no = (int) (cleanInputData1($crntRow[9]));
                            $dsge_freqncy_uom = cleanInputData1($crntRow[10]);
                            $tot_duratn = (int) (cleanInputData1($crntRow[11]));
                            $tot_duratn_uom = cleanInputData1($crntRow[12]);
                            $dose_form = cleanInputData1($crntRow[13]);
                            $admin_times = cleanInputData1($crntRow[14]);
                            $sub_allowed = cleanInputData1($crntRow[15]);
                            $isSubbAlwd = true;
                            if ($sub_allowed == "NO") {
                                    $isSubbAlwd = false;
                            }
                            $int_prscrbr_prsn_id = (int) (cleanInputData1($crntRow[16]));
                            if ($prscptn_id > 0) {
                                $recCntUpdt = $recCntUpdt + updateMedication($prscptn_id, $cnsltn_id, $created_by, $creation_date, $last_update_by, $last_update_date, $doc_cmnts, 
                                        $pharm_cmnts, $isDspnsd, $dest_appntmnt_id, $itm_id, $dose_qty, $dose_uom, $dsge_freqncy_no, $dsge_freqncy_uom, $tot_duratn, 
                                        $tot_duratn_uom, $dose_form, $admin_times, $isSubbAlwd, $int_prscrbr_prsn_id);
                                
                                if($isDspnsd){
                                    $cntX = checkUnlinkedAppntmntDataItmExistince($itm_id, $dest_appntmnt_id, $prscptn_id);
                                    if($cntX <= 0){
                                        $ttlQty = ($dose_qty * $dsge_freqncy_no * $tot_duratn);
                                        $appntmntDataItemsID = getAppntmntDataItemsID();
                                        $uomID = "(SELECT inv.get_invitm_uom_id($itm_id))";
                                        insertAppntmntDataItems($appntmntDataItemsID, $dest_appntmnt_id, $itm_id, $ttlQty, "", $usrID, $dateStr, $uomID, $prscptn_id);
                                    }
                                } else {
                                   deleteAppntmntDataItem($itm_id, $dest_appntmnt_id, $prscptn_id); 
                                }
                                
                            } else {
                                $prscptn_id = getPrscptn_id();
                                
                                $ct = insertMedication($prscptn_id, $cnsltn_id, $created_by, $creation_date, $last_update_by, $last_update_date, $doc_cmnts, 
                                        $pharm_cmnts, $isDspnsd, $dest_appntmnt_id, $itm_id, $dose_qty, $dose_uom, $dsge_freqncy_no, $dsge_freqncy_uom, $tot_duratn, 
                                        $tot_duratn_uom, $dose_form, $admin_times, $isSubbAlwd, $int_prscrbr_prsn_id);
                                $recCntInst = $recCntInst + $ct;
                                
                                
                                if($ct > 0) {
                                    if($isDspnsd){
                                        $cntX = 1; //checkUnlinkedAppntmntDataItmExistince($itm_id, $dest_appntmnt_id, $prscptn_id);
                                        if($cntX <= 0){
                                            $ttlQty = ($dose_qty * $dsge_freqncy_no * $tot_duratn);
                                            $appntmntDataItemsID = getAppntmntDataItemsID();
                                            $uomID = "(SELECT inv.get_invitm_uom_id($itm_id))";
                                            insertAppntmntDataItems($appntmntDataItemsID, $dest_appntmnt_id, $itm_id, $ttlQty, "", $usrID, $dateStr, $uomID, $prscptn_id);
                                        }
                                    }
                                }
                                $ct = 0;
                            }
                        }
                    }
                    
                     /*FINALIZE GOES HERE*/
                    if(1 == 1){
                        $recCntInstFNLZ = 0;
                        $recCntUpdtFNLZ = 0;

                        $vst_id = isset($_POST['vstId']) ? (int)cleanInputData($_POST['vstId']) : -1;
                        $appntmnt_id = isset($_POST['frmAppntmntID']) ? (int)cleanInputData($_POST['frmAppntmntID']) : -1;
                        $appntmnt_date = isset($_POST['frmAppntmntDate']) ? cleanInputData($_POST['frmAppntmntDate']) : date("d-M-Y H:i:s");
                        $srvs_type_id = isset($_POST['frmSrvsTypeId']) ? cleanInputData($_POST['frmSrvsTypeId']) : -1;
                        $prvdr_type = isset($_POST['frmPrvdrType']) ? cleanInputData($_POST['frmPrvdrType']) : 'G';
                        $cmnts = isset($_POST['frmAppntmntCmnts']) ? cleanInputData($_POST['frmAppntmntCmnts']) : '';
                        $lnkdSrvsTypeCode = isset($_POST['lnkdSrvsTypeCode']) ? cleanInputData($_POST['lnkdSrvsTypeCode']) : '';

                        $cnsltnAppntmnt_id = isset($_POST['cnsltnAppntmnt_id']) ? (int)cleanInputData($_POST['cnsltnAppntmnt_id']) : -1;
                        $srvsTypeSysCode = getSrvsTypeCodeFromID($srvs_type_id);

                        $frmCnsltnID = isset($_POST['frmCnsltnID']) ? cleanInputData($_POST['frmCnsltnID']) : -1;

                        $srvs_prvdr_prsn_id = -1;
                        $prvdr_grp_id = -1; 

                        $frmSrvsPrvdrId = isset($_POST['frmSrvsPrvdrId']) ? (int)cleanInputData($_POST['frmSrvsPrvdrId']) : -1;
                        if($prvdr_type == 'G'){
                            $prvdr_grp_id = $frmSrvsPrvdrId;
                        } else {
                            $srvs_prvdr_prsn_id = $frmSrvsPrvdrId;
                        }

                        if ($appntmnt_date != "") {
                            $appntmnt_date = cnvrtDMYTmToYMDTm($appntmnt_date);
                        } 

                        if($srvs_type_id == -1 || $appntmnt_date == "" || $frmSrvsPrvdrId == -1 || $prvdr_type == ""){    
                            echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                            . 'Please complete all required field before saving!<br/></div>';
                            exit();
                        } else {
                            $cnsltnID = $frmCnsltnID; //getAppntmntCnsltnID($appntmnt_id);
                            $srvsTypeItmId = (int)getGnrlRecNm("hosp.srvs_types", "type_id", "itm_id", $srvs_type_id);

                            if($lnkdSrvsTypeCode != ''){
                                $rtnNoMsg = ""; 
                                if ($cnsltnID > 0){
                                    if($lnkdSrvsTypeCode == "PH-0001"){//pharmacy medication
                                        $rcExts = cnsltnPrescriptionExist($cnsltnID, $cnsltnAppntmnt_id);
                                        if($rcExts){
                                            $appntmnt_id = getAppntmnt_id(); //NEW 
                                            $recCntInstFNLZ = insertAppntmnt($appntmnt_id,$vst_id,$appntmnt_date,$srvs_type_id,$prvdr_type,$srvs_prvdr_prsn_id,$prvdr_grp_id,$cmnts,$created_by,$creation_date,$last_update_by,$last_update_date);

                                            if($recCntInstFNLZ > 0){
                                                //$dest_appntmnt_id = $appntmnt_id;
                                                execUpdtInsSQL("UPDATE hosp.prscptn SET dest_appntmnt_id = $appntmnt_id WHERE cnsltn_id = $cnsltnID AND dest_appntmnt_id = $cnsltnAppntmnt_id");

                                                if($srvsTypeItmId > 0){
                                                    $ttlQty = 1;
                                                    $appntmntDataItemsID = getAppntmntDataItemsID();
                                                    $uomID = "(SELECT inv.get_invitm_uom_id($srvsTypeItmId))";
                                                    insertAppntmntDataItems($appntmntDataItemsID, $appntmnt_id, $srvsTypeItmId, $ttlQty, "", $usrID, $dateStr, $uomID, -1);
                                                }
                                            }
                                        }
                                        
                                    } 
                                }

                                if($srvsTypeItmId > 0 && $recCntInstFNLZ > 0){
                                    $ttlQty = 1;
                                    $appntmntDataItemsID = getAppntmntDataItemsID();
                                    $uomID = "(SELECT inv.get_invitm_uom_id($srvsTypeItmId))";
                                    insertAppntmntDataItems($appntmntDataItemsID, $appntmnt_id, $srvsTypeItmId, $ttlQty, "", $usrID, $dateStr, $uomID, -1);     
                                }

                                if($recCntInstFNLZ > 0){
                                    $rtnNoMsg = "Appointment Scheduled Successfully"; 
                                } else {
                                    $rtnNoMsg = "Sorry! Nothing to Schedule!"; 
                                }
                                echo "<span style='color:green;font-weight:bold !important;'><i>$recCntInst record(s) inserted</br>$recCntUpdt record(s) updated"
                                        . "</br>$rtnNoMsg</i></span>";
                                exit();
                            }
                        }
                    } 
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . 'Please provide one Medication Record before saving!<br/></div>';
                    exit();
                }
            } 
            else if ($actyp == 6) {//IN-HOUSE ADMISSIONS
                $slctdInptntAdmsn = isset($_POST['slctdInptntAdmsn']) ? cleanInputData($_POST['slctdInptntAdmsn']) : '';
                //global $usrID;
                
                $dateStr = getDB_Date_time();
                $created_by = $usrID;
                $last_update_by = $usrID;
                $creation_date = $dateStr;
                $last_update_date = $dateStr;
                $recCntInst = 0;
                $recCntUpdt = 0;
                if (trim($slctdInptntAdmsn, "|~") != "") {
                    $variousRows = explode("|", trim($slctdInptntAdmsn, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 8) {
                            $admsn_id = (int) (cleanInputData1($crntRow[0]));
                            $cnsltn_id = (int) (cleanInputData1($crntRow[1]));
                            $facility_type_id = (int) (cleanInputData1($crntRow[2]));
                            $room_id = (int) (cleanInputData1($crntRow[3]));
                            $dest_appntmnt_id = (int) (cleanInputData1($crntRow[4]));
                            $checkin_date = cleanInputData1($crntRow[5]);
                            if($checkin_date != ""){
                                $checkin_date = cnvrtDMYToYMD($checkin_date);
                            }
                            $checkout_date = cleanInputData1($crntRow[6]);
                            if($checkout_date != ""){
                                $checkout_date = cnvrtDMYToYMD($checkout_date);
                            }
                            $ref_check_in_id = (int) (cleanInputData1($crntRow[7]));
                            /*echo "admsn_id".$admsn_id;
                            exit();*/
                            if ($admsn_id > 0) {
                                $recCntUpdt = $recCntUpdt + updateInptntAdmsn($admsn_id, $cnsltn_id, $facility_type_id, $room_id, $dest_appntmnt_id, $checkin_date, $checkout_date, $ref_check_in_id, $created_by, $creation_date, $last_update_by, $last_update_date);
                            } else {
                                $admsn_id = getAdmsn_id();
                                
                                $recCntInst = $recCntInst + insertInptntAdmsn($admsn_id, $cnsltn_id, $facility_type_id, $room_id, $dest_appntmnt_id, $checkin_date, $checkout_date, $ref_check_in_id, $created_by, $creation_date, $last_update_by, $last_update_date);
                            }
                        }
                    }
                    echo "<span style='color:green;font-weight:bold !important;'><i>$recCntInst record(s) inserted</br>$recCntUpdt record(s) updated</i></span>";
                    exit();
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . 'Please provide one Record before saving!<br/></div>';
                    exit();
                }
            }
            else if ($actyp == 7){//RADIOLOGY REQUESTS
                
            } else if ($actyp == 8){//CONSULTATION-LINKED RECOMMENDED SERVICES
                
            }
            else if ($actyp == 100){
                $appntmntID = isset($_POST['appntmntID']) ? cleanInputData($_POST['appntmntID']) : -1;
                $srcType = isset($_POST['srcType']) ? cleanInputData($_POST['srcType']) : "";
                
                $addtnlSrvsDataCol1 = isset($_POST['addtnlSrvsDataCol1']) ? cleanInputData($_POST['addtnlSrvsDataCol1']) : "";
                $addtnlSrvsDataCol2 = isset($_POST['addtnlSrvsDataCol2']) ? cleanInputData($_POST['addtnlSrvsDataCol2']) : "";
                $addtnlSrvsDataCol3 = isset($_POST['addtnlSrvsDataCol3']) ? cleanInputData($_POST['addtnlSrvsDataCol3']) : "";
                $addtnlSrvsDataCol4 = isset($_POST['addtnlSrvsDataCol4']) ? cleanInputData($_POST['addtnlSrvsDataCol4']) : "";
                $addtnlSrvsDataCol5 = isset($_POST['addtnlSrvsDataCol5']) ? cleanInputData($_POST['addtnlSrvsDataCol5']) : "";
                $addtnlSrvsDataCol6 = isset($_POST['addtnlSrvsDataCol6']) ? cleanInputData($_POST['addtnlSrvsDataCol6']) : "";
                $addtnlSrvsDataCol7 = isset($_POST['addtnlSrvsDataCol7']) ? cleanInputData($_POST['addtnlSrvsDataCol7']) : "";
                $addtnlSrvsDataCol8 = isset($_POST['addtnlSrvsDataCol8']) ? cleanInputData($_POST['addtnlSrvsDataCol8']) : "";
                $addtnlSrvsDataCol9 = isset($_POST['addtnlSrvsDataCol9']) ? cleanInputData($_POST['addtnlSrvsDataCol9']) : "";
                $addtnlSrvsDataCol10 = isset($_POST['addtnlSrvsDataCol10']) ? cleanInputData($_POST['addtnlSrvsDataCol10']) : "";
                $addtnlSrvsDataCol11 = isset($_POST['addtnlSrvsDataCol11']) ? cleanInputData($_POST['addtnlSrvsDataCol11']) : "";
                $addtnlSrvsDataCol12 = isset($_POST['addtnlSrvsDataCol12']) ? cleanInputData($_POST['addtnlSrvsDataCol12']) : "";
                $addtnlSrvsDataCol13 = isset($_POST['addtnlSrvsDataCol13']) ? cleanInputData($_POST['addtnlSrvsDataCol13']) : "";
                $addtnlSrvsDataCol14 = isset($_POST['addtnlSrvsDataCol14']) ? cleanInputData($_POST['addtnlSrvsDataCol14']) : "";
                $addtnlSrvsDataCol15 = isset($_POST['addtnlSrvsDataCol15']) ? cleanInputData($_POST['addtnlSrvsDataCol15']) : "";
                $addtnlSrvsDataCol16 = isset($_POST['addtnlSrvsDataCol16']) ? cleanInputData($_POST['addtnlSrvsDataCol16']) : "";
                $addtnlSrvsDataCol17 = isset($_POST['addtnlSrvsDataCol17']) ? cleanInputData($_POST['addtnlSrvsDataCol17']) : "";
                $addtnlSrvsDataCol18 = isset($_POST['addtnlSrvsDataCol18']) ? cleanInputData($_POST['addtnlSrvsDataCol18']) : "";
                $addtnlSrvsDataCol19 = isset($_POST['addtnlSrvsDataCol19']) ? cleanInputData($_POST['addtnlSrvsDataCol19']) : "";
                $addtnlSrvsDataCol20 = isset($_POST['addtnlSrvsDataCol20']) ? cleanInputData($_POST['addtnlSrvsDataCol20']) : "";
                $addtnlSrvsDataCol21 = isset($_POST['addtnlSrvsDataCol21']) ? cleanInputData($_POST['addtnlSrvsDataCol21']) : "";
                $addtnlSrvsDataCol22 = isset($_POST['addtnlSrvsDataCol22']) ? cleanInputData($_POST['addtnlSrvsDataCol22']) : "";
                $addtnlSrvsDataCol23 = isset($_POST['addtnlSrvsDataCol23']) ? cleanInputData($_POST['addtnlSrvsDataCol23']) : "";
                $addtnlSrvsDataCol24 = isset($_POST['addtnlSrvsDataCol24']) ? cleanInputData($_POST['addtnlSrvsDataCol24']) : "";
                $addtnlSrvsDataCol25 = isset($_POST['addtnlSrvsDataCol25']) ? cleanInputData($_POST['addtnlSrvsDataCol25']) : "";
                $addtnlSrvsDataCol26 = isset($_POST['addtnlSrvsDataCol26']) ? cleanInputData($_POST['addtnlSrvsDataCol26']) : "";
                $addtnlSrvsDataCol27 = isset($_POST['addtnlSrvsDataCol27']) ? cleanInputData($_POST['addtnlSrvsDataCol27']) : "";
                $addtnlSrvsDataCol28 = isset($_POST['addtnlSrvsDataCol28']) ? cleanInputData($_POST['addtnlSrvsDataCol28']) : "";
                $addtnlSrvsDataCol29 = isset($_POST['addtnlSrvsDataCol29']) ? cleanInputData($_POST['addtnlSrvsDataCol29']) : "";
                $addtnlSrvsDataCol30 = isset($_POST['addtnlSrvsDataCol30']) ? cleanInputData($_POST['addtnlSrvsDataCol30']) : "";
                $addtnlSrvsDataCol31 = isset($_POST['addtnlSrvsDataCol31']) ? cleanInputData($_POST['addtnlSrvsDataCol31']) : "";
                $addtnlSrvsDataCol32 = isset($_POST['addtnlSrvsDataCol32']) ? cleanInputData($_POST['addtnlSrvsDataCol32']) : "";
                $addtnlSrvsDataCol33 = isset($_POST['addtnlSrvsDataCol33']) ? cleanInputData($_POST['addtnlSrvsDataCol33']) : "";
                $addtnlSrvsDataCol34 = isset($_POST['addtnlSrvsDataCol34']) ? cleanInputData($_POST['addtnlSrvsDataCol34']) : "";
                $addtnlSrvsDataCol35 = isset($_POST['addtnlSrvsDataCol35']) ? cleanInputData($_POST['addtnlSrvsDataCol35']) : "";
                $addtnlSrvsDataCol36 = isset($_POST['addtnlSrvsDataCol36']) ? cleanInputData($_POST['addtnlSrvsDataCol36']) : "";
                $addtnlSrvsDataCol37 = isset($_POST['addtnlSrvsDataCol37']) ? cleanInputData($_POST['addtnlSrvsDataCol37']) : "";
                $addtnlSrvsDataCol38 = isset($_POST['addtnlSrvsDataCol38']) ? cleanInputData($_POST['addtnlSrvsDataCol38']) : "";
                $addtnlSrvsDataCol39 = isset($_POST['addtnlSrvsDataCol39']) ? cleanInputData($_POST['addtnlSrvsDataCol39']) : "";
                $addtnlSrvsDataCol40 = isset($_POST['addtnlSrvsDataCol40']) ? cleanInputData($_POST['addtnlSrvsDataCol40']) : "";
                $addtnlSrvsDataCol41 = isset($_POST['addtnlSrvsDataCol41']) ? cleanInputData($_POST['addtnlSrvsDataCol41']) : "";
                $addtnlSrvsDataCol42 = isset($_POST['addtnlSrvsDataCol42']) ? cleanInputData($_POST['addtnlSrvsDataCol42']) : "";
                $addtnlSrvsDataCol43 = isset($_POST['addtnlSrvsDataCol43']) ? cleanInputData($_POST['addtnlSrvsDataCol43']) : "";
                $addtnlSrvsDataCol44 = isset($_POST['addtnlSrvsDataCol44']) ? cleanInputData($_POST['addtnlSrvsDataCol44']) : "";
                $addtnlSrvsDataCol45 = isset($_POST['addtnlSrvsDataCol45']) ? cleanInputData($_POST['addtnlSrvsDataCol45']) : "";
                $addtnlSrvsDataCol46 = isset($_POST['addtnlSrvsDataCol46']) ? cleanInputData($_POST['addtnlSrvsDataCol46']) : "";
                $addtnlSrvsDataCol47 = isset($_POST['addtnlSrvsDataCol47']) ? cleanInputData($_POST['addtnlSrvsDataCol47']) : "";
                $addtnlSrvsDataCol48 = isset($_POST['addtnlSrvsDataCol48']) ? cleanInputData($_POST['addtnlSrvsDataCol48']) : "";
                $addtnlSrvsDataCol49 = isset($_POST['addtnlSrvsDataCol49']) ? cleanInputData($_POST['addtnlSrvsDataCol49']) : "";
                $addtnlSrvsDataCol50 = isset($_POST['addtnlSrvsDataCol50']) ? cleanInputData($_POST['addtnlSrvsDataCol50']) : "";
                
                //$dateStr = getDB_Date_time();
                $addDtaSvd = saveAddtnlSrvsData($appntmntID, $srcType, $addtnlSrvsDataCol1, $addtnlSrvsDataCol2, $addtnlSrvsDataCol3, $addtnlSrvsDataCol4, $addtnlSrvsDataCol5, $addtnlSrvsDataCol6, $addtnlSrvsDataCol7, $addtnlSrvsDataCol8, $addtnlSrvsDataCol9, $addtnlSrvsDataCol10, $addtnlSrvsDataCol11, $addtnlSrvsDataCol12, $addtnlSrvsDataCol13, $addtnlSrvsDataCol14, $addtnlSrvsDataCol15, $addtnlSrvsDataCol16, $addtnlSrvsDataCol17, $addtnlSrvsDataCol18, $addtnlSrvsDataCol19, $addtnlSrvsDataCol20, $addtnlSrvsDataCol21, $addtnlSrvsDataCol22, $addtnlSrvsDataCol23, $addtnlSrvsDataCol24, $addtnlSrvsDataCol25, $addtnlSrvsDataCol26, $addtnlSrvsDataCol27, $addtnlSrvsDataCol28, $addtnlSrvsDataCol29, $addtnlSrvsDataCol30, $addtnlSrvsDataCol31, $addtnlSrvsDataCol32, $addtnlSrvsDataCol33, $addtnlSrvsDataCol34, $addtnlSrvsDataCol35, $addtnlSrvsDataCol36, $addtnlSrvsDataCol37, $addtnlSrvsDataCol38, $addtnlSrvsDataCol39, $addtnlSrvsDataCol40, $addtnlSrvsDataCol41, $addtnlSrvsDataCol42, $addtnlSrvsDataCol43, $addtnlSrvsDataCol44, $addtnlSrvsDataCol45, $addtnlSrvsDataCol46, $addtnlSrvsDataCol47, $addtnlSrvsDataCol48, $addtnlSrvsDataCol49, $addtnlSrvsDataCol50);
                if($addDtaSvd){
                     echo '<div>Data Saved Successfully</div>';     
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                                . 'Failed to save Additional Data!<br/></div>';
                }    
                exit();
            }
            else if ($actyp == 101){//Recommended Service Appointment
                
                global $usrID;
                $dateStr = getDB_Date_time();
                $created_by = $usrID;
                $last_update_by = $usrID;
                $creation_date = $dateStr;
                $last_update_date = $dateStr;
                
                $recCntInst = 0;
                $recCntUpdt = 0;
                
                //var_dump($_POST);
                $src_appntmnt_id = isset($_POST['frmAppntmntID']) ? (int)cleanInputData($_POST['frmAppntmntID']) : -1;
                $cnsltn_id = isset($_POST['frmCnsltnID']) ? (int)cleanInputData($_POST['frmCnsltnID']) : -1;
                $appntmnt_date = isset($_POST['frmAppntmntDate']) ? cleanInputData($_POST['frmAppntmntDate']) : date("d-M-Y H:i:s");
                $srvs_type_id = isset($_POST['frmSrvsTypeId']) ? cleanInputData($_POST['frmSrvsTypeId']) : -1;
                $prvdr_type = 'G';
                $cmnts = isset($_POST['frmAppntmntCmnts']) ? cleanInputData($_POST['frmAppntmntCmnts']) : '';
                $srvs_prvdr_cmnts = "";
                
                $vst_id = (int)getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "vst_id", $src_appntmnt_id);
                
                $srvsTypeSysCode = getSrvsTypeCodeFromID($srvs_type_id);
                                
                $srvs_prvdr_prsn_id = -1;
                $prvdr_grp_id = getSrvsTypeMainPrvdrGrp($srvsTypeSysCode); 
                
                if ($appntmnt_date != "") {
                    $appntmnt_date = cnvrtDMYTmToYMDTm($appntmnt_date);
                } 

                if($srvs_type_id == -1 || $cmnts == ""){    
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . 'Please complete all required field before saving!<br/></div>';
                    exit();
                } else {
                    if ($src_appntmnt_id > 0) {
                        
                        $appntmnt_id = getAppntmnt_id();
                        $recCntInst = insertAppntmnt($appntmnt_id,$vst_id,$appntmnt_date,$srvs_type_id,$prvdr_type,$srvs_prvdr_prsn_id,$prvdr_grp_id,$cmnts,$created_by,$creation_date,$last_update_by,$last_update_date);

                        if($recCntInst > 0){
                             
                             //INSERT INTO RECOMMENDED SERVICE TABLES
                            $rcmd_srv_id = getRcmd_srv_id();
                            $dest_appntmnt_id = $appntmnt_id;
                            $recCntInst1 = insertRcmdSrvs($rcmd_srv_id,$cnsltn_id,$srvs_type_id,$created_by,$creation_date,$last_update_by,$last_update_date,
                                    $cmnts,$srvs_prvdr_cmnts,$dest_appntmnt_id);

                            //RETURN VARIABLE AND ACCESS FROM SAVE JAVASCRIPT FUNCTION
                            if($recCntInst1 > 0){
                                echo json_encode(array("destAppntmntID" => $appntmnt_id, "rcmdSrvID" => $rcmd_srv_id, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                            } else  {
                                echo '<span style="color:red;font-weight:bold !important;">Scheduling Failed!<br/>Failed to Create Recommended Service record</span>';
                            }
                             
                        } else {
                            echo '<span style="color:red;font-weight:bold !important;">Scheduling Failed!<br/></span>';
                        }
                    }
                }
                exit();
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&mdl=Clinic/Hospital');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Appointments Data</span>
				</li>
                               </ul>
                              </div>";

                $total = get_AllAppointmentsTtl($qStrtDte, $qEndDte, $crdtTypeSrchIn, $statusSrchIn/* , $branchSrchIn */, $srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_AllAppointments($qStrtDte, $qEndDte, $crdtTypeSrchIn, $statusSrchIn/* , $branchSrchIn */, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                
                ?> 
                <form id='dataAdminForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">APPOINTMENT DATA</legend>
                        <div class="row" style="margin-bottom:1px;">
                            <div class="col-lg-4" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="dataAdminSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&mdl=Clinic/Hospital')">
                                    <input id="dataAdminPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getHospData('clear', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&mdl=Clinic/Hospital')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getHospData('', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&mdl=Clinic/Hospital')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSrchIn">
                <?php
                $valslctdArry = array("", "");
                $srchInsArrys = array("Appointment Number", "ID/Full Name");
                for ($z = 0; $z < count($srchInsArrys); $z++) {
                    if ($srchIn == $srchInsArrys[$z]) {
                        $valslctdArry[$z] = "selected";
                    }
                    ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminDsplySze" style="min-width:70px !important;">                            
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
                            <div class="col-lg-2">
                                <div class="input-group">                        
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSortBy">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "");
                                        $srchInsArrys = array("Date Added DESC", "Appointment Number ASC", "Appointment Status ASC");
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
                            <div class="col-lg-2">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getHospData('previous', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&mdl=Clinic/Hospital');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getHospData('next', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&mdl=Clinic/Hospital');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:2px;padding:1px 15px 2px 15px !important">   
                            <div  class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                <div class="col-md-4" style="float:left;padding:0px 15px 0px 0px !important;">
                                    <div class="btn-group" style="margin-bottom: 1px;">
                                        <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;" onclick="">
                                            <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Excel
                                        </button>
                                        <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                <?php if ($canExportADRecs === true) { ?> 
                                                <li>
                                                    <a href="javascript:openATab('#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=3&mdl=Clinic/Hospital')">
                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Export Visits
                                                    </a>
                                                </li>
                                            <?php } if ($canImportADRecs === true) { ?> 
                                                <li>
                                                    <a href="javascript:imprtLoanApplctns();">
                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Import Visits
                                                    </a>
                                                </li>
                                            <?php } ?> 
                                        </ul>
                                    </div>                              
                                </div>
                                <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                    <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" size="16" type="text" id="dataAdminStrtDate" name="dataAdminStrtDate" value="<?php
                                            echo substr($qStrtDte, 0, 11);
                                            ?>" placeholder="Start Date">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div></div>
                                    <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" size="16" type="text"  id="dataAdminEndDate" name="dataAdminEndDate" value="<?php
                                            echo substr($qEndDte, 0, 11);
                                            ?>" placeholder="End Date">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div></div>                            
                                </div>                            
                                <div class="col-md-4" style="float:right !important;">
                                    <div class="col-lg-12" style="margin-bottom: 1px;"><!-- style="padding: 5px 1px 0px 15px !important">-->
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>                                        
                                            <select class="form-control" id="dataAdminStatusSrchIn" onchange="javascript:getHospData('', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&mdl=Clinic/Hospital');">
                                                <?php
                                                $selectedTxtAS = "";
                                                $selectedTxtOpen = "";
                                                $selectedTxtClosed = "";
                                                $selectedTxtInProgress = "";
                                                $selectedTxtCancelled = "";
                                                if ($statusSrchIn == "All Statuses") {
                                                    $selectedTxtAS = "selected";
                                                } else if ($statusSrchIn == "Scheduled") {
                                                    $selectedTxtOpen = "selected";
                                                } else if ($statusSrchIn == "Completed") {
                                                    $selectedTxtClosed = "selected";
                                                } else if ($statusSrchIn == "In Progress") {
                                                    $selectedTxtInProgress = "selected";
                                                } else if ($statusSrchIn == "Cancelled") {
                                                    $selectedTxtCancelled = "selected";
                                                }
                                                ?>
                                                <option value="All Statuses" <?php echo $selectedTxtAS; ?> >All Statuses</option>
                                                <option value="Scheduled" <?php echo $selectedTxtOpen; ?>>Scheduled</option>
                                                <option value="Completed" <?php echo $selectedTxtClosed; ?>>Completed</option>
                                                <option value="In Progress" <?php echo $selectedTxtInProgress; ?>>In Progress</option>
                                                <option value="Cancelled" <?php echo $selectedTxtCancelled; ?>>Cancelled</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                          
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="indCustTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Appointment No.</th>                                        
                                            <th>Patient Name</th>
                                            <th>Appointment Date</th>
                                            <th>Service Type</th>
                                            <th>Provider<br/>Type</th>
                                            <th>Service Provider</th>
                                            <th>Status</th>
                                            <?php if ($canEdtADRecs === true) { ?>
                                                <th>...</th>
                                            <?php } if ($canEdtADRecs === true && $canGenSalesInvoice) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <?php if($canViewAppntmntDataItms) { ?>
                                                <th>...</th>
                                             <?php } ?>
                                            <th>...</th>
                                            <!--<th>...</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            while ($row = loc_db_fetch_array($result)) {
                                                $srvsTypeSysCode = getSrvsTypeCode((int) $row[0]);
                                                $srvsTypeSysNm = getSrvsTypeNm((int) $row[0]);
                                                $modalElmntID = "myFormsModalLgHZ"; //"myFormsModalLg";
                                                $modalElmntBodyID = "myFormsModalLgHZBody"; //"myFormsModalBodyLgZ";
                                                $modalElmntTitleID = "myFormsModalLgHZTitle"; //"myFormsModalTitleLg";
                                                $frmTitle = "Process Appointment";
                                                if ($srvsTypeSysCode == 'VS-0001') {
                                                    $vwtyp = 2;
                                                    $modalElmntID = "myFormsModaly";
                                                    $modalElmntBodyID = "myFormsModalyBody";
                                                    $modalElmntTitleID = "myFormsModalyTitle";
                                                    $frmTitle = "Vitals of ";
                                                } else if ($srvsTypeSysCode == 'MC-0001') {
                                                    $vwtyp = 1;
                                                    $frmTitle = "Medical Consultation for ";
                                                } else if ($srvsTypeSysCode == 'LI-0001') {
                                                    $vwtyp = 3;
                                                    $frmTitle = "Lab Investigations for ";
                                                } else if ($srvsTypeSysCode == 'RD-0001') {
                                                    $vwtyp = 3;
                                                    $frmTitle = "Radiology Investigations for ";
                                                } else if ($srvsTypeSysCode == 'PH-0001') {
                                                    $vwtyp = 4;
                                                    $frmTitle = "Prescriptions for ";
                                                } else if ($srvsTypeSysCode == 'IA-0001') {
                                                    $vwtyp = 5;
                                                    $frmTitle = "Admission of ";
                                                } else {
                                                    $vwtyp = 99;
                                                    $frmTitle = "$srvsTypeSysNm Appointment for ";
                                                }
                                                
                                                
                                                $appntmntStatusColor = "style='color:red !important;font-weight:bold;'";
                                                if($row[7] == "In Progress"){
                                                    $appntmntStatusColor = "style='color:blue !important;font-weight:bold;'";
                                                } else if($row[7] == "Completed"){
                                                    $appntmntStatusColor = "style='color:green !important;font-weight:bold;'";
                                                } else if($row[7] == "Cancelled"){
                                                    $appntmntStatusColor = "style='font-weight:bold;'";
                                                }

                                                $cntr += 1;
                                                ?>
                                            <tr id="indCustTableRow<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[3]; ?></td>
                                                <td class="lovtd"><?php echo $row[4]; ?></td>
                                                <td class="lovtd"><?php echo $row[5]; ?></td>
                                                <td class="lovtd"><?php echo $row[6]; ?></td>
                                                <td class="lovtd" <?php echo $appntmntStatusColor; ?>><?php echo $row[7]; ?></td>
                                                <?php if ($canEdtADRecs === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Open Appointment" 
                                                                onclick="getHospDetailsForm('<?php echo $modalElmntID; ?>', '<?php echo $modalElmntBodyID; ?>', '<?php echo $modalElmntTitleID; ?>', '<?php echo $frmTitle; ?> <?php echo $row[2] . " - " . $row[1]; ?>', <?php echo $pgNo; ?>, <?php echo $vwtyp; ?>, 'EDIT', <?php echo $row[0]; ?>, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow<?php echo $cntr; ?>', 3);" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/98.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } if ($canEdtADRecs === true && $canGenSalesInvoice) {  ?>                                    
                                                    <td class="lovtd">
                                                        <?php if($row[15] == "") { ?>
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Process Appointment" 
                                                                onclick="getHospDetailsFormCheckedIn('<?php echo $modalElmntID; ?>', '<?php echo $modalElmntBodyID; ?>', '<?php echo $modalElmntTitleID; ?>', '<?php echo $frmTitle; ?> <?php echo $row[2] . " - " . $row[1]; ?>', <?php echo $pgNo; ?>, <?php echo $vwtyp; ?>, 'EDIT', <?php echo $row[0]; ?>, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow<?php echo $cntr; ?>', 3);" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/cstmrs1.jpg" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                                <?php if($canViewAppntmntDataItms) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Appointment Items" onclick="getOneAppntmntDataItemsForm(<?php echo $row[0]; ?>, 3, 'ShowDialog', '<?php echo $row[1]; ?>', '<?php echo $row[7]; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Appointment" 
                                                            onclick="getHospDetailsForm('<?php echo $modalElmntID; ?>', '<?php echo $modalElmntBodyID; ?>', '<?php echo $modalElmntTitleID; ?>', '<?php echo $frmTitle; ?> <?php echo $row[2] . " - " . $row[1]; ?>', <?php echo $pgNo; ?>, <?php echo $vwtyp; ?>, 'VIEW', <?php echo $row[0]; ?>, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow<?php echo $cntr; ?>', 3);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                    </fieldset>
                </form>
                                        <?php
                                    } 
            else if ($vwtyp == 1 || $vwtyp == 3 || $vwtyp == 4 || $vwtyp == 5 || $vwtyp == 99) {//CONSULTATION, INVESTIGATIONS, PHARMACY, IN-PATIENT ADMISSIONS MC-0001
                if ($vwtypActn == "EDIT" || $vwtypActn == "VIEW" || $vwtypActn == "ADD") {
                    /* Read Only */

                    $chkIn = isset($_POST['chkIn']) ? cleanInputData1($_POST['chkIn']) : 'N';

                    if($chkIn == "Y"){
                        $rwCnt = checkInAppointment($pkID);
                        if ($rwCnt <= 0) {
                            echo "Check-In Failed";
                            exit();
                        } 
                    }
                    
                    $actvTabCnsltn = "";
                    $actvTabInvstgn = "";
                    $actvTabPhrmcy = "";
                    $actvTabVitals = "";
                    $actvTabAdmissions = "";
                    $actvTabCnsltnRcmddSrvs = "";
                    $actvTabAddtnlData = "";
                    $hideNotice = "hideNotice";

                    if ($vwtyp == 1) {
                        $actvTabCnsltn = "active";
                        $actvTabInvstgn = "";
                        $actvTabPhrmcy = "";
                        $actvTabVitals = "";
                        $actvTabAdmissions = "";
                        $actvTabAddtnlData = "";
                    } else if ($vwtyp == 3) {
                        $actvTabCnsltn = "";
                        $actvTabInvstgn = "active";
                        $actvTabPhrmcy = "";
                        $actvTabVitals = "";
                        $actvTabAdmissions = "";
                        $actvTabAddtnlData = "";
                        $hideNotice = "";
                    } else if ($vwtyp == 4) {
                        $actvTabCnsltn = "";
                        $actvTabInvstgn = "";
                        $actvTabPhrmcy = "active";
                        $actvTabVitals = "";
                        $actvTabAdmissions = "";
                        $actvTabAddtnlData = "";
                        $hideNotice = "";
                    } else if ($vwtyp == 5) {
                        $actvTabCnsltn = "";
                        $actvTabInvstgn = "";
                        $actvTabPhrmcy = 
                        $actvTabVitals = "";
                        $actvTabAdmissions = "active";
                        $actvTabAddtnlData = "";
                        $hideNotice = "";
                    } else if ($vwtyp == 99) {//Additional Data
                        $actvTabCnsltn = "";
                        $actvTabInvstgn = "";
                        $actvTabPhrmcy = 
                        $actvTabVitals = "";
                        $actvTabAdmissions = "";
                        $actvTabAddtnlData = "active in";
                        $hideNotice = "";
                    }
                    
                    //GET SERVICE TYPE MAIN SERVICE PROVIDER GROUP
                    $PHMainSrvsPrvdrGrpID = getSrvsTypeMainPrvdrGrp("PH-0001");//PHARMACY
                    $PHMainSrvsTypeID = getSrvsTypeIDFromSysCode("PH-0001");
                    $LIMainSrvsPrvdrGrpID = getSrvsTypeMainPrvdrGrp("LI-0001");//LAB
                    $LIMainSrvsTypeID = getSrvsTypeIDFromSysCode("LI-0001");
                    $IAMainSrvsPrvdrGrpID = getSrvsTypeMainPrvdrGrp("IA-0001");//ADMISSIONS  
                    $IAMainSrvsTypeID = getSrvsTypeIDFromSysCode("IA-0001");
                    $RDMainSrvsPrvdrGrpID = getSrvsTypeMainPrvdrGrp("RD-0001");//RADIOLOGY
                    
                    $lnkdAppntmntDate = date("d-M-Y");

                    $voidedTrnsHdrID = -1;

                    $rqstatusColor = "red";
                    $ttlColor = "blue";
                    
                    $mkReadOnly = "";
                    $mkRmrkReadOnly = "";
                    $mkReadOnlyDsbld = "";
                    $rqrdFld = "rqrdFld";
                    
                    

                    $sbmtdTrnsHdrID = $pkID;

                    $srcPgNo = isset($_POST['srcPgNo']) ? $_POST['srcPgNo'] : 3;

                    $shdDplct = isset($_POST['shdDplct']) ? $_POST['shdDplct'] : '0';
                    $srcCaller = isset($_POST['srcCaller']) ? $_POST['srcCaller'] : 'NORMAL';
                    if (trim($srcCaller) === "") {
                        $srcCaller = 'NORMAL';
                    }
                    $sbmtdRptID = -1;
                    $sbmtdRptIDDsply = -1;
                    $rptsRptNm = "";
                    $$rqstatusColor = "red";
                    $trnsStatus = "Open";
                    $sbmtdPersonID = -1;
                    $officer = getPrsnFullNm($prsnid);

                    $cnsltnID = -1;
                    $patientIDNo = "";
                    $patientNm = "";
                    $gender = "";
                    $age = "";
                    $bloodGroup = "";
                    $sickleGenotype = "";
                    $vstType = "";
                    $vstNo = "";
                    $appntmntNo = "";
                    $sponsor = "";
                    $cardNo = "";
                    $cardExpiryDate = "";
                    $appntmntDate = "";
                    $cardNoAndExpiry = "";
                    $patientCmplt = "";
                    $checkInDate = "";
                    $checkOutDate = "";
                    $appntmntSrvsTypeSysCode = "";
                    $appntmntSrvsTypeName = "";
                    $addDataLabel = "Additional Data";

                    $physicalExam = "";
                    $cnsltnCmnts = "";
                    $patientPersonID = -1;
                    $prsnImgLoc = "";
                    $appntmntRmks = "";
                    $vstId = -1;
                    
                    $docAdmsnInstructions = "";
                    $docAdmsnCheckInDate = "";//date("d-M-Y");
                    $docAdmsnCheckInNoOfDays = "";
                    
                    $ttlSrvsBillPymnt = "0.00";
                    $ttlSrvsBill = "0.00";
                    
                    $resultAppntmntIDs = getAppntmntInvcIDs($pkID);
                    
                    while ($rowBP = loc_db_fetch_array($resultAppntmntIDs)) {
                        $rsltw = get_One_SalesInvcAmounts($rowBP[0]);
                        if ($rw = loc_db_fetch_array($rsltw)) {
                            $ttlSrvsBill = $ttlSrvsBill + (float) $rw[0];
                            $ttlSrvsBillPymnt = $ttlSrvsBillPymnt + $rw[1];
                        }
                    }
                                     
                    $resultCnsltn = getAppointmentCnsltn($pkID);
                    
                    if($vwtyp == 5){
                        /*$xCnsltnID = getInhouseAdmsnCnsltnID($pkID);
                        if($xCnsltnID > 0){
                            $xResultCnsltn = getCnsltnDetails($xCnsltnID);
                            
                            while ($rowCnstl = loc_db_fetch_array($xResultCnsltn)) {
                                //$cnsltnID = $rowCnstl[0];
                                //$patientCmplt = $rowCnstl[1];
                                //$physicalExam = $rowCnstl[2];
                                //$cnsltnCmnts = $rowCnstl[3];
                                $docAdmsnInstructions = $rowCnstl[5];
                                $docAdmsnCheckInDate = $rowCnstl[6];
                                $docAdmsnCheckInNoOfDays = $rowCnstl[7];
                            }
                        }*/
                        
                        $mkReadOnly = "readonly=\"true\"";
                                                
                        $rsltAppntmntAdmsn = getAppntmntAdmsnDetails($pkID);
                        //$rsltAppntmntAdmsnCnt = loc_db_num_rows($rsltAppntmntAdmsn);
                        while ($rowAA = loc_db_fetch_array($rsltAppntmntAdmsn)) {
                            //$cnsltnID = $rowCnstl[0];
                            //$patientCmplt = $rowCnstl[1];
                            //$physicalExam = $rowCnstl[2];
                            //$cnsltnCmnts = $rowCnstl[3];
                            $docAdmsnInstructions = $rowAA[1];
                            $docAdmsnCheckInDate = $rowAA[2];
                            $docAdmsnCheckInNoOfDays = $rowAA[3];
                        }
                        
                        /*$result33 = get_AllInhouseAdmissions($cnsltnID, $pkID);
                        
                        while($row33 = loc_db_fetch_array($result33)){
                            $rsltw33 = get_One_SalesInvcAmounts($row33[10]);
                            while ($rw33 = loc_db_fetch_array($rsltw33)) {
                                $ttlSrvsBill = $ttlSrvsBill + (float) $rw33[0];
                                $ttlSrvsBillPymnt = $ttlSrvsBillPymnt + $rw33[1];
                            }
                        }*/
                    }
                    
                    while ($rowCnstl = loc_db_fetch_array($resultCnsltn)) {
                        $cnsltnID = $rowCnstl[0];
                        $patientCmplt = $rowCnstl[1];
                        $physicalExam = $rowCnstl[2];
                        $cnsltnCmnts = $rowCnstl[3];
                        $docAdmsnInstructions = $rowCnstl[5];
                        $docAdmsnCheckInDate = $rowCnstl[6];
                        $docAdmsnCheckInNoOfDays = $rowCnstl[7];
                    }
                    
                    if($docAdmsnCheckInNoOfDays == 0 || $docAdmsnCheckInNoOfDays == "0"){
                        $docAdmsnCheckInNoOfDays = "";
                    }

                    $dsplyDcElmnts = "";
                    $dsplyOthrElmnts = "display:none";
                    if ($cnsltnID <= 0) {
                        $dsplyDcElmnts = "display:none";
                        $dsplyOthrElmnts = "";
                    }
                    
                    if ($vwtyp == 5) {
                        $dsplyDcElmnts = "display:none";
                        $dsplyOthrElmnts = "";
                    }
                    
                    $result = get_AppointmentDataDet($pkID);

                    while ($row = loc_db_fetch_array($result)) {
                        $patientIDNo = $row[14];
                        $patientNm = $row[2];
                        $gender = $row[15];
                        $age = getCustAge($row[16]) . " Years " . getMonthAge($row[16]) . " Months";
                        $bloodGroup = get_PrsExtrData($row[19], "1");
                        $sickleGenotype = get_PrsExtrData($row[19], "2");
                        $vstType = $row[17];
                        $vstNo = $row[18];
                        $appntmntNo = $row[1];
                        $sponsor = get_PrsExtrData($row[19], "6");
                        $appntmntDate = $row[3];
                        $cardNo = get_PrsExtrData($row[19], "7");
                        $cardExpiryDate = get_PrsExtrData($row[19], "7");
                        $patientPersonID = $row[19];
                        $prsnImgLoc = $row[20];
                        $checkInDate = $row[21];
                        $checkOutDate = $row[9];
                        $officer = $row[6];
                        $appntmntRmks = $row[8];
                        $trnsStatus = $row[7];
                        $vstId =  $row[10];
                        $appntmntSrvsTypeSysCode =  $row[22];
                        $appntmntSrvsTypeName =  $row[23];
                    }
                    
                    

                    if ($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") {
                        $mkReadOnly = "readonly=\"true\"";
                        $mkRmrkReadOnly = "readonly=\"true\"";
                        $mkReadOnlyDsbld = "disabled=\"true\"";
                        $rqrdFld = "";
                    }
                    
                    if($appntmntSrvsTypeSysCode == "MC-0001"){
                        $addDataLabel = "Additional Consultation Data";
                    } else if($appntmntSrvsTypeSysCode == "IA-0001" || $appntmntSrvsTypeSysCode == "PH-0001" || $appntmntSrvsTypeSysCode == "VS-0001"
                            || $appntmntSrvsTypeSysCode == "LI-0001" || $appntmntSrvsTypeSysCode == "RD-0001"){
                        $addDataLabel = "Additional Data";
                    } else {
                        $addDataLabel = $appntmntSrvsTypeName;
                    }

                    //getGnrlRecNm("prs.prsn_names_nos", "person_id", "trim(title || ' ' || sur_name ||', ' || first_name || ' ' || other_names)||' ('||local_id_no||')'", $branch_id);

                    if ($pkID > 0) {
                        $sbmtdRptID = $pkID;


                        if (2 > 1) {

                            $rcrdExst = prsn_Record_Exist($pkID);

                            $nwFileName = "";
                            $temp = explode(".", $prsnImgLoc);
                            $extension = end($temp);
                            if (trim($extension) == "") {
                                $extension = "png";
                            }
                            $ecnptDFlNm = encrypt1($prsnImgLoc, $smplTokenWord1);
                            $nwFileName = $ecnptDFlNm . "." . $extension;
                            $ftp_src = "";
                            if ($patientPersonID <= 0) {
                                $ftp_src = $ftp_base_db_fldr . "/Person/Request/" . $patientPersonID . "." . $extension;
                                if (!($rcrdExst == true && $chngRqstExst > 0 && file_exists($ftp_src))) {
                                    $ftp_src = $ftp_base_db_fldr . "/Person/" . $patientPersonID . "." . $extension;
                                }
                            } else {
                                $ftp_src = $ftp_base_db_fldr . "/Person/" . $patientPersonID . "." . $extension;
                            }

                            //echo $ftp_src;
                            ///home/rhoportal/ecng/Person/cLSKLnmKKLSmRFTNnYBvuJQBKnALQJKZKXVXucWKmTcRLTc.png
                            $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                            if (file_exists($ftp_src)) {
                                copy("$ftp_src", "$fullPemDest");
                            } else if (!file_exists($fullPemDest)) {
                                $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                                copy("$ftp_src", "$fullPemDest");
                            }
                            $nwFileName = $tmpDest . $nwFileName;
                            ?>
                            <form id="medicalForm">
                                <div class="row">
                                    <div class="col-md-6" style="padding:0px 20px 0px 20px !important;">
                                        <div class="" style="float:left;">
                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                            </button>
                                            <?php if ($pkID > 0 && $vwtypActn == "EDIT") { ?>
                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', 'Medical Consultation for <?php echo $patientNm . " - " . $appntmntNo; ?> ', 3, <?php echo $vwtyp; ?>, 'EDIT', <?php echo $pkID; ?>, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1', 3);" data-toggle="tooltip" title="Reload Appointment">
                                                    <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            <?php } ?>
                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getHstrcPatientHospDetailsForm(<?php echo $patientPersonID; ?>, 'myFormsModalLgZH', 'myFormsModalLgZHBody', 'myFormsModalLgZHTitle', 'Historic Appointments for <?php echo $patientNm; ?>', 101, 0, 'VIEW', <?php echo $pkID; ?>, 'allPrvdrGroupsTable', 'allPrvdrGroupsTblAddRow1', 3);" data-toggle="tooltip" title="Historic Medical Records">
                                                <img src="cmn_images/initiate.png" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                            <!--<button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getHospDetailsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Medical Consultation for <?php echo $patientNm . " - " . $appntmntNo; ?> ', 3, <?php echo $vwtyp; ?>, 'EDIT', <?php echo $pkID; ?>, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1', 3);" data-toggle="tooltip" title="Previous">
                                                <img src="cmn_images/Backward.png" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getHospDetailsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Medical Consultation for <?php echo $patientNm . " - " . $appntmntNo; ?> ', 3, <?php echo $vwtyp; ?>, 'EDIT', <?php echo $pkID; ?>, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1', 3);" data-toggle="tooltip" title="Next">
                                                <img src="cmn_images/Forward.png" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                            </button>-->
                                        </div>
                                    </div>                                 
                                    <div class="col-md-6" style="padding-right: 0px !important;">
                                        <div class="" style="float:right;padding:0px 0px 0px 20px !important;">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px; max-height:30px !important;" onclick="getOneFscDocsForm_Gnrl(<?php echo $pkID; ?>,'APPOINTMENTS', 140, 'Appointment Attached Documents');" data-toggle="tooltip" data-placement="bottom" title = "Appointment Attached Documents">
                                                <img src="cmn_images/adjunto.png"  style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                            <?php if ($checkInDate == ""  && $vwtypActn != "VIEW") { ?>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="checkInAppointment(<?php echo $pkID; ?>,<?php echo $vwtyp; ?>)" style="width:100% !important;">
                                                    <img src="cmn_images/cstmrs1.jpg" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    Check-In
                                                </button>
                                            <?php } if ($checkOutDate == "" && $vwtypActn != "VIEW" && $canAddRecsVNA) { ?>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="newAppointment(<?php echo $vstId; ?>,'myFormsModalLgHZ','myFormsModalLgHZBody', 'myFormsModalLgHZTitle', 3, '', <?php echo $cnsltnID; ?>);" style="width:100% !important;">
                                                <img src="cmn_images/clock.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                New Appointment
                                            </button>
                                            <?php } if ($checkInDate !== "" && $checkOutDate == ""  && $vwtypActn != "VIEW") { ?>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="checkOutAppointment(<?php echo $pkID; ?>,<?php echo $vwtyp; ?>);" style="width:100% !important;">
                                                    <!--<img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    -->Check-Out
                                                </button>
                                            <?php if (/*$cnsltnID > 0 &&*/ $vwtyp == 1) {  ?>
                                            
                                            <?php if ($checkInDate !== "" && $checkOutDate == "" && ($vwtypActn == "ADD" || $vwtypActn == "EDIT")) { ?>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveConsultation();" style="width:100% !important;">
                                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    Save
                                                </button>
                                            <?php } }} ?>
                                            <?php if ($trnsStatus == "Completed"  && $vwtypActn != "VIEW") { ?>
                                            <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="reopenAppointment(<?php echo $pkID; ?>,<?php echo $vwtyp; ?>)" style="width:100% !important;">
                                                Re-Open
                                            </button>
                                            <?php } ?>
                                            <?php if($canViewAppntmntDataItms) { ?>
                                            <button type="button" class="btn btn-default" data-toggle="tooltip" style="margin-bottom: 5px; <?php echo $dsplyDcElmnts; ?>" data-placement="bottom" title="View Appointment Items" onclick="getOneAppntmntDataItemsForm(<?php echo $pkID; ?>, 3, 'ShowDialog', '<?php echo $appntmntNo; ?>', '<?php echo $trnsStatus; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                             </button> 
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div  class="col-md-5" style="padding:0px 3px 0px 3px !important;"> 
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;display:none !important;">
                                            <label for="patientIDNo" class="control-label col-lg-4">Patient ID:</label>
                                            <div  class="col-lg-8">
                                                <input type="text" id="srcPgNo" class="form-control"value="<?php echo $srcPgNo; ?>"/>
                                                <input type="text" class="form-control" aria-label="..." id="patientIDNo" name="patientIDNo" value="<?php echo $patientIDNo; ?>" style="width:100%;" readonly="readonly">   
                                                <input type="hidden" class="form-control" aria-label="..." id="cnsltnID" name="cnsltnID" value="<?php echo $cnsltnID; ?>" style="width:100%;"> 
                                                <input type="hidden" class="form-control" id="appntmntID"  placeholder="Appointment ID" value="<?php echo $pkID; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="patientNm" class="control-label col-lg-4">Patient Name:</label>
                                            <div  class="col-lg-8">
                                                <input type="text" class="form-control" aria-label="..." id="patientNm" name="patientNm" value="<?php echo $patientNm; ?>" style="width:100%;" readonly="readonly">   
                                            </div>
                                        </div> 
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="gender" class="control-label col-lg-4">Gender/Age:</label>
                                            <div  class="col-lg-8">
                                                <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="gender" name="gender" value="<?php echo $gender; ?>" style="width:100%;" readonly="readonly">     
                                                </div>
                                                <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="patientAge" name="patientAge" value="<?php echo $age; ?>" style="width:100%;" readonly="readonly">    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="bloodGroup" class="control-label col-lg-4">Blood Group/Genotype:</label>
                                            <div  class="col-lg-8">
                                                <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="bloodGroup" name="bloodGroup" value="<?php echo $bloodGroup; ?>" style="width:100%;" readonly="readonly">   
                                                </div>
                                                <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="sickleGenotype" name="sickleGenotype" value="<?php echo $sickleGenotype; ?>" style="width:100%;" readonly="readonly">   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="vstType" class="control-label col-lg-4">Visit Type/No.:</label>
                                            <div  class="col-lg-8">
                                                <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="vstType" name="vstType" value="<?php echo $vstType; ?>" style="width:100%;" readonly="readonly">   
                                                </div>
                                                <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="vstNo" name="vstNo" value="<?php echo $vstNo; ?>" style="width:100%;" readonly="readonly">   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="appntmntNo" class="control-label col-lg-4">Appointment Date/No.:</label>
                                            <div  class="col-lg-8">
                                                <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="appntmntDate" name="appntmntDate" value="<?php echo $appntmntDate; ?>" style="width:100%;" readonly="readonly">   
                                                </div>
                                                <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="appntmntNo" name="appntmntNo" value="<?php echo $appntmntNo; ?>" style="width:100%;" readonly="readonly">   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="appntmntNo" class="control-label col-lg-4">Total Bill/Payment:</label>
                                            <div  class="col-lg-8">
                                                <div class="col-md-2" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="thisCrncy" name="thisCrncy" value="GHS" style="width:100%;font-weight:bold !important;font-size:16px !important;" readonly="readonly">   
                                                </div>
                                                <div class="col-md-5" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="ttlInvoice" name="ttlInvoice" value="<?php echo number_format($ttlSrvsBill,2); ?>" style="width:100%;color:red;font-weight:bold !important;font-size:16px !important;" readonly="readonly">   
                                                </div>
                                                <div class="col-md-5" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="ttlPymnt" name="ttlPymnt" value="<?php echo number_format($ttlSrvsBillPymnt,2); ?>" style="width:100%;color:blue;font-weight:bold !important;font-size:16px !important;" readonly="readonly">   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div  class="col-md-5" style="padding:0px 3px 0px 3px !important;"> 
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="sponsor" class="control-label col-lg-4">Sponsor:</label>
                                            <div  class="col-lg-8">
                                                <input type="text" class="form-control" aria-label="..." id="sponsor" name="sponsor" value="<?php echo $sponsor; ?>" style="width:100%;" readonly="readonly">   
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="cardNo" class="control-label col-lg-4">Card No./Expiry Date:</label>
                                            <div  class="col-lg-8">
                                                <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="cardNo" name="cardNo" value="<?php echo $cardNo; ?>" style="width:100%;" readonly="readonly">   
                                                </div>
                                                <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="expiryDate" name="expiryDate" value="<?php echo $cardExpiryDate; ?>" style="width:100%;" readonly="readonly">   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="checkInDate" class="control-label col-lg-4">Check In/Out Date:</label>
                                            <div  class="col-lg-8">
                                                <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="checkInDate" name="checkInDate" value="<?php echo $checkInDate; ?>" style="width:100%;" readonly="readonly">   
                                                </div>
                                                <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                    <input type="text" class="form-control" aria-label="..." id="checkOutDate" name="checkOutDate" value="<?php echo $checkOutDate; ?>" style="width:100%;" readonly="readonly">   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="officer" class="control-label col-lg-4">Service Provider:</label>
                                            <div  class="col-lg-8">
                                                <input type="text" class="form-control" aria-label="..." id="officer" name="officer" value="<?php echo $officer; ?>" style="width:100%;" readonly="readonly">   
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <label for="appntmntRmks" class="control-label col-lg-4">Appointment Remarks:</label>
                                            <div  class="col-lg-8">
                                                <textarea class="form-control" aria-label="..." id="appntmntRmks" name="appntmntRmks" rows="3" style="width:100%;" readonly="readonly" ><?php echo $appntmntRmks; ?></textarea> 
                                            </div>
                                        </div>
                                    </div>
                                    <div  class="col-md-2" style="padding:0px 3px 0px 3px !important;"> 
                                        <!--<fieldset class="basic_person_fs1"><legend class="basic_person_lg">Patient Picture</legend>-->
                                        <div style="margin-bottom: 10px;">
                                            <img src="<?php echo $nwFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 190px !important; width: auto !important;">                                            
                                        </div>                                       
                                        <!--</fieldset>-->
                                    </div>
                                </div>
                                <div class="row"  style="padding:1px 15px 5px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div> 
                                <div class="row"  style="padding:1px 15px 1px 15px !important;">
                                    <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;" id="appntmntDataTabs">
                                        <?php if ($vwtyp == 1) { ?>
                                        <li class="<?php echo $actvTabCnsltn ?>"><a data-toggle="tabajxrptdet" data-rhodata="" href="#cnsltnMainTbPage" id="cnsltnMainTbPagetab">Consultation</a></li>
                                        <?php } if ($vwtyp == 3 || $vwtyp == 1) { ?>
                                        <li class="<?php echo $actvTabInvstgn ?>"><a data-toggle="tabajxrptdet" data-rhodata="" href="#invstgtnTbPage" id="invstgtnTbPagetab">Investigations</a></li>
                                        <?php } if ($vwtyp == 4 || $vwtyp == 1) { ?>
                                        <li class="<?php echo $actvTabPhrmcy ?>"><a data-toggle="tabajxrptdet" data-rhodata="" href="#medicationTbPage" id="medicationTbPagetab">Medication</a></li>
                                        <?php } if ($vwtyp == 1) { ?>
                                        <li class="<?php echo $actvTabVitals ?>"><a data-toggle="tabajxrptdet" data-rhodata="" href="#vitalsTbPage" id="vitalsTbPagetab">Vital Statistics</a></li>
                                        <?php } if ($vwtyp == 5 || $vwtyp == 1) { ?>
                                        <li class="<?php echo $actvTabAdmissions ?>"><a data-toggle="tabajxrptdet" data-rhodata="" href="#inHouseAdmsnTbPage" id="inHouseAdmsnTbPagetab">Admissions</a></li>
                                        <?php } if ($vwtyp == 1) {  ?>
                                        <li class="<?php echo $actvTabCnsltnRcmddSrvs ?>"><a data-toggle="tabajxrptdet" data-rhodata="grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&mdl=Clinic/Hospital&vtyp=8&cnsltnID=<?php echo $cnsltnID; ?>&appntmntID=<?php echo $pkID; ?>" href="#rcmddSrvsTbPage" id="rcmddSrvsTbPagetab">Recommended Services</a></li>
                                        <?php } if($vwtyp == 1 || $vwtyp == 3 || $vwtyp == 4 || $vwtyp == 5) { ?> 
                                        <li class="<?php echo $actvTabAddtnlData ?>"><a data-toggle="tabajxrptdet" data-rhodata="grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=102&mdl=Clinic/Hospital&q=ADTNL-DATA-FORM&vtyp=1&appntmntID=<?php echo $pkID; ?>&formType=<?php echo $appntmntSrvsTypeSysCode; ?>&vtypActn=<?php echo $vwtypActn; ?>" href="#prfBCOPAddPrsnDataEDT" id="prfBCOPAddPrsnDataEDTtab"><?php echo $addDataLabel; ?></a></li>
                                        <?php } ?> 
                                    </ul>                                    
                                    <div class="row">                  
                                        <div class="col-md-12">
                                            <div class="custDiv"> 
                                                <div class="tab-content">
                                                    <div id="cnsltnMainTbPage" class="tab-pane fadein <?php echo $actvTabCnsltn ?>" style="border:none !important;">
                                                        <div class="row" style="max-height: 245px !important;">
                                                            <div class="col-md-4">
                                                                <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="patientCmplnt" class="control-label">Patient Complaint:</label>
                                                                    <div  class="">
                            <?php if ($canEdtADRecs === true) { ?>
                                                                            <textarea class="form-control <?php echo $rqrdFld; ?>" <?php echo $mkReadOnly; ?> aria-label="..." id="patientCmplnt" name="patientCmplnt" style="width:100%;" cols="9" rows="12"><?php echo $patientCmplt; ?></textarea>
                            <?php } else {
                                ?>
                                                                            <span><?php echo $patientCmplt; ?></span>
                                <?php
                            }
                            ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="physicalExam" class="control-label">Physical Examination:</label>
                                                                    <div  class="">
                                                                        <?php if ($canEdtADRecs === true) { ?>
                                                                            <textarea class="form-control" <?php echo $mkReadOnly; ?> aria-label="..." id="physicalExam" name="physicalExam" style="width:100%;" cols="9" rows="12"><?php echo $physicalExam; ?></textarea>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo $physicalExam; ?></span>
                                <?php
                            }
                            ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                        <?php
                                                                        if ($canEdtADRecs === true) {
                                                                            $nwRowHtml = "<tr id=\"diagnosisRow__WWW123WWW\">
                                                                <td class=\"lovtd\"><span class=\"\">New</span></td>                                                                                                  
                                                                <td class=\"lovtd\">
                                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"diagnosisRow_WWW123WWW_DiseaseNm\" value=\"\" readonly=\"true\">
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"diagnosisRow_WWW123WWW_DiseaseID\" value=\"-1\">
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"diagnosisRow_WWW123WWW_DiagID\" value=\"-1\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Diagnosis Types', '', '', '', 'radio', true, '', 'diagnosisRow_WWW123WWW_DiseaseID', 'diagnosisRow_WWW123WWW_DiseaseNm', 'clear', 0, '');\">
                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>                                                       
                                                                </td> 
                                                                    <td class=\"lovtd\">
                                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delDiagnosis('diagnosisRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Record\">
                                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                        </button>
                                                                    </td>
                                                                </tr>";
                                                                            $nwRowHtml = urlencode($nwRowHtml);
                                                                            if ($checkInDate !== "" && $checkOutDate == "") {
                                                                                ?>
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-top:5px;" onclick="insertNewRowBe4('diagnosisTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Diagnosis">
                                                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;&nbsp;New Diagnosis
                                                                                </button> 
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?> 
                                                                <div class="row"> 
                                                                    <div  class="col-md-12" style="max-height: 203px !important;overflow-y: auto;">
                                                                        <table class="table table-striped table-bordered table-responsive" id="diagnosisTable" cellspacing="0" width="100%" style="width:100%;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No.</th>
                                                                                    <th style="min-width:200px;">Diagnosis</th>
                                                                                    <?php
                                                                                    
                                                                                    if ($canDelADRecs === true) {
                                                                                        ?>
                                                                                        <?php if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                                                        <th>&nbsp;</th>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                            <?php
                            $cntr = 0;
                            $curIdx = 0;
                            $result3 = get_AllDiagnosis($cnsltnID);
                            $rptRlID = -1;
                            while ($row3 = loc_db_fetch_array($result3)) {
                                $cntr += 1;
                                ?>
                                                                                    <tr id="diagnosisRow_<?php echo $cntr; ?>">                                    
                                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                        <td class="lovtd">
                                                                                            <span><?php echo $row3[2]; ?></span>
                                                                                            <input type="hidden" class="form-control rqrdFld" aria-label="..." id="diagnosisRow<?php echo $cntr; ?>_DiseaseNm" value="<?php echo $row3[2]; ?>" readonly="true">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="diagnosisRow<?php echo $cntr; ?>_DiseaseID" value="<?php echo $row3[1]; ?>">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="diagnosisRow<?php echo $cntr; ?>_DiagID" value="<?php echo $row3[0]; ?>">
                                                                                        </td>   
                                                                                    <?php
                                                                                    if ($canDelADRecs === true) {
                                                                                        ?>
                                                                                            <?php if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                                                            <td class="lovtd">
                                                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delDiagnosis('diagnosisRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Diagnosis">
                                                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                                </button>
                                                                                            </td>
                                                                                            <?php } ?>
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
                                                        <div class="row" style="margin-top:10px !important;">
                                                            <div class="col-md-12">
                                                                <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="cnsltnCmnts" class="control-label">Comments:</label>
                                                                    <div  class="">
                            <?php if ($canEdtADRecs === true) { ?>
                                                                            <textarea class="form-control" <?php echo $mkReadOnly; ?> aria-label="..." id="cnsltnCmnts" name="cnsltnCmnts" style="width:100%;" cols="9" rows="4"><?php echo $cnsltnCmnts; ?></textarea>
                            <?php } else {
                                ?>
                                                                            <span><?php echo $cnsltnCmnts; ?></span>
                                <?php
                            }
                            ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="invstgtnTbPage" class="tab-pane fadein <?php echo $hideNotice; ?> <?php echo $actvTabInvstgn; ?>" style="border:none !important;">  
                                                                        <?php
                                                                        if ($canEdtADRecs === true) {
                                                                            $nwRowHtml = "<tr id=\"invstgtnRow__WWW123WWW\">                                    
                                                                                <td class=\"lovtd\"><span>New</span></td>
                                                                                <td class=\"lovtd\">
                                                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                        <div class=\"input-group\"  style=\"width:100%;\">
                                                                                            <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"invstgtnRow_WWW123WWW_RqstNm\" value=\"\" readonly=\"true\">
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"invstgtnRow_WWW123WWW_RqstID\" value=\"-1\">
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"invstgtnRow_WWW123WWW_InvstgtnID\" value=\"-1\">
                                                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Investigation Types', '', '', '', 'radio', true, '', 'invstgtnRow_WWW123WWW_RqstID', 'invstgtnRow_WWW123WWW_RqstNm', 'clear', 0, '');\">
                                                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div> 
                                                                                    <span style=\"$dsplyOthrElmnts;\">&nbsp;</span>
                                                                                </td>
                                                                                <td class=\"lovtd\">
                                                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important; $dsplyDcElmnts;\">
                                                                                        <textarea class=\"form-control\" aria-label=\"...\" id=\"invstgtnRow_WWW123WWW_DocCmnts\" style=\"width:100% !important;\"></textarea>
                                                                                    </div>
                                                                                    <span style=\"$dsplyOthrElmnts;\">&nbsp;</span>
                                                                                </td>
                                                                                <td class=\"lovtd\">
                                                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;$dsplyOthrElmnts;\">
                                                                                            <div class=\"form-check\" style=\"font-size: 12px !important;text-align:center;\">
                                                                                                <label class=\"form-check-label\">
                                                                                                    <input type=\"checkbox\" class=\"form-check-input\" id=\"invstgtnRow_WWW123WWW_DoInhouse\" name=\"invstgtnRow_WWW123WWW_DoInhouse\">
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <span style=\"$dsplyDcElmnts\"></span>
                                                                                </td>
                                                                                <td class=\"lovtd\">  
                                                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important; $dsplyOthrElmnts;\">
                                                                                        <textarea class=\"form-control\" aria-label=\"...\" id=\"invstgtnRow_WWW123WWW_LabRslt\" style=\"width:100% !important;\"></textarea>
                                                                                    </div>
                                                                                    <span style=\"$dsplyDcElmnts;\">&nbsp;</span>
                                                                                </td>
                                                                                <td class=\"lovtd\">  
                                                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important; $dsplyOthrElmnts;\">
                                                                                        <textarea class=\"form-control\" aria-label=\"...\" id=\"invstgtnRow_WWW123WWW_LabCmnts\" style=\"width:100% !important;\"></textarea>
                                                                                    </div>
                                                                                    <span style=\"$dsplyDcElmnts;\">&nbsp;</span>
                                                                                </td>
                                                                                <td class=\"lovtd\">  
                                                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important; $dsplyOthrElmnts;\">
                                                                                        <textarea class=\"form-control\" aria-label=\"...\" id=\"invstgtnRow_WWW123WWW_LabLoc\" style=\"width:100% !important;\"></textarea>
                                                                                    </div>
                                                                                    <span style=\"$dsplyDcElmnts;\">&nbsp;</span>
                                                                                </td>
                                                                                <td class=\"lovtd\"><span>&nbsp;</span></td>
                                                                                <td class=\"lovtd\">
                                                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRptPrgm('invstgtnRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Record\">
                                                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                    </button>
                                                                                </td>
                                                                            </tr>";
                                                                            $nwRowHtml = urlencode($nwRowHtml);
                                                                            if ($checkInDate !== "" && $checkOutDate == "") {
                                                                                ?>
                                                                
                                                                <div class="row">
                                                                        <div class="col-md-6">
                                                                    <?php
                                                                    if ($checkInDate !== "" && $checkOutDate == "") {
                                                                        ?>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('invstgtnTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Investigation">
                                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;&nbsp;New Investigation
                                                                        </button> 
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveInvestigation();" style="width:100% !important;">
                                                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            Save
                                                                        </button>
                                                                        <button id="invstgtnBtn" type="button" class="btn btn-default" data-toggle="tooltip" style="margin-bottom: 5px; <?php echo $dsplyDcElmnts; ?>" data-placement="bottom" title="Schedule Appointment" style="margin-bottom: 5px;" 
                                                                                onclick="saveNFinalizeInvestigation(<?php echo $vstId; ?>, <?php echo $LIMainSrvsTypeID; ?>, 'LI-0001', <?php echo $LIMainSrvsPrvdrGrpID; ?>, <?php echo $cnsltnID; ?>, '<?php echo $lnkdAppntmntDate; ?>');" style="width:100% !important;">
                                                                            <img src="cmn_images/clock.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Finalize
                                                                        </button>
                                                                    <?php } ?>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="" style="float:right;">
                                                                                <?php if($canViewAppntmntDataItms) { ?>
                                                                                <button type="button" class="btn btn-default" data-toggle="tooltip" style="margin-bottom: 5px; <?php echo $dsplyOthrElmnts; ?>" data-placement="bottom" title="View Appointment Items" onclick="getOneAppntmntDataItemsForm(<?php echo $pkID; ?>, 3, 'ShowDialog', '<?php echo $appntmntNo; ?>', '<?php echo $trnsStatus; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                                   <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                                <?php } if((getAppntmntBillVisit($vstId) == "1" || getAppntmntBillVisit($vstId) == "Y") && $canGenSalesInvoice) { ?>
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px; <?php echo $dsplyOthrElmnts; ?>" onclick="getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Sales Invoice', 'NO', 'SALES',-1, <?php echo $pkID; ?>);" style="">
                                                                                    <img src="cmn_images/sale.jpg" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    Invoice
                                                                                </button>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                            }
                                                        }
                                                        ?> 
                                                        <div class="row"> 
                                                            <div  class="col-md-12">
                                                                <table class="table table-striped table-bordered table-responsive" id="invstgtnTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px !important;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th style="min-width:300px !important;width:300px !important;">Requested Lab Item</th>
                                                                            <th>Doctor's Comment</th>
                                                                            <th>In-house?</th>
                                                                            <th>Lab Results</th>
                                                                            <th>Lab Comments</th>
                                                                            <th>Lab Location</th>
                                                                            <th style="max-width:70px;width:70px;<?php echo $dsplyDcElmnts; ?>">Sent?</th>
                                                                            <th>&nbsp;</th>
                                                                            <?php if ($canDelADRecs === true) { ?>
                                                                                <?php if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                                                <th>&nbsp;</th>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $cntr = 0;
                                                                    //echo $cnsltnID;
                                                                    $invstgtnID = -1;

                                                                    $result3 = get_AllInvestigations($cnsltnID, $pkID);

                                                                    while ($row3 = loc_db_fetch_array($result3)) {
                                                                        $cntr += 1;
                                                                        $invstgtnID = $row3[0];
                                                                        ?>
                                                                            <tr id="invstgtnRow_<?php echo $cntr; ?>">                                    
                                                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                <td class="lovtd">
                                                                                    <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row3[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                        <span style=""><?php echo $row3[2] . " (" . $row3[7] . ")"; ?></span>
                                                                                    <?php } else { ?>
                                                                                    <div class="input-group"  style="width:100%;<?php echo $dsplyDcElmnts; ?>">
                                                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_RqstNm" value="<?php echo $row3[2] . " (" . $row3[7] . ")"; ?>" readonly="true">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_RqstID" value="<?php echo $row3[1]; ?>">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Investigation Types', '', '', '', 'radio', true, '', 'invstgtnRow<?php echo $cntr; ?>_RqstID', 'invstgtnRow<?php echo $cntr; ?>_RqstNm', 'clear', 0, '');">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_InvstgtnID" value="<?php echo $invstgtnID; ?>">
                                                                                    </div>
                                                                                    <span style="<?php echo $dsplyOthrElmnts; ?>"><?php echo $row3[2] . " (" . $row3[7] . ")"; ?></span>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row3[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                        <span style=""><?php echo $row3[3]; ?></span>
                                                                                    <?php } else { ?>
                                                                                    <textarea class="form-control" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_DocCmnts" style="width:100% !important;<?php echo $dsplyDcElmnts; ?>"><?php echo $row3[3]; ?></textarea>
                                                                                    <span style="<?php echo $dsplyOthrElmnts; ?>"><?php echo $row3[3]; ?></span>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td class="lovtd" style="text-align:center;">
                                                                                            <?php
                                                                                            $isChkd = "";
                                                                                            if ($row3[12] == "1") {
                                                                                                $isChkd = "checked=\"true\"";
                                                                                            }
                                                                                                ?>
                                                                                        <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row3[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                            <span style=""><?php echo ($row3[12] == "1" ? "Yes" : "No"); ?></span>
                                                                                        <?php } else { ?>
                                                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important; <?php echo $dsplyOthrElmnts; ?>">
                                                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                                                <label class="form-check-label">
                                                                                                    <input type="checkbox" class="form-check-input" id="invstgtnRow<?php echo $cntr; ?>_DoInhouse" name="invstgtnRow<?php echo $cntr; ?>_DoInhouse" <?php echo $isChkd ?>>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <span style="<?php echo $dsplyDcElmnts; ?>"><?php echo ($row3[12] == "1" ? "Yes" : "No"); ?></span>   
                                                                                         <?php } ?>
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row3[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                            <span style=""><?php echo $row3[4]; ?></span>
                                                                                    <?php } else { ?>
                                                                                    <textarea class="form-control" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_LabRslt" style="width:100% !important;<?php echo $dsplyOthrElmnts; ?>"><?php echo $row3[4]; ?></textarea>
                                                                                    <span style="<?php echo $dsplyDcElmnts; ?>"><?php echo $row3[4]; ?></span>
                                                                                     <?php } ?>
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row3[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                            <span style=""><?php echo $row3[5]; ?></span>
                                                                                        <?php } else { ?>
                                                                                    <textarea class="form-control" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_LabCmnts" style="width:100% !important;<?php echo $dsplyOthrElmnts; ?>"><?php echo $row3[5]; ?></textarea>
                                                                                    <span style="<?php echo $dsplyDcElmnts; ?>"><?php echo $row3[5]; ?></span>
                                                                                     <?php } ?>
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row3[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                            <span style=""><?php echo $row3[6]; ?></span>
                                                                                        <?php } else { ?>
                                                                                    <textarea class="form-control" aria-label="..." id="invstgtnRow<?php echo $cntr; ?>_LabLoc" style="width:100% !important;<?php echo $dsplyOthrElmnts; ?>"><?php echo $row3[6]; ?></textarea>
                                                                                    <span style="<?php echo $dsplyDcElmnts; ?>"><?php echo $row3[6]; ?></span>
                                                                                     <?php } ?>
                                                                                </td>
                                                                                <td style="text-align: center !important;<?php echo $dsplyDcElmnts; ?>">
                                                                                    <?php 
                                                                                        $isSent = "Yes"; 
                                                                                        $isSentClr = "color:green; font-weight:bold;"; 
                                                                                        if($row3[8] == $pkID){
                                                                                            $isSent = "No"; 
                                                                                            $isSentClr = "color:red; font-weight:bold;"; 
                                                                                        }                                                                                    
                                                                                    ?>
                                                                                    <span style="<?php echo $isSentClr; ?>"><?php echo $isSent; ?></span>
                                                                                </td>
                                                                                <td class="lovd">
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px; max-height:30px !important;" onclick="getOneFscDocsForm_Gnrl(<?php echo $row3[0]; ?>,'INVESTIGATIONS', 140, 'Investigations Attached Documents');" data-toggle="tooltip" data-placement="bottom" title = "Investigations Attached Documents">
                                                                                        <img src="cmn_images/adjunto.png"  style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                                <?php
                                                                                if ($canDelADRecs === true) {
                                                                                    ?>
                                                                                    <?php if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                                                    <td class="lovtd">
                                                                                         <?php if ($row3[12] == "0") { ?>
                                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delInvestigation('invstgtnRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Record">
                                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                         <?php } ?>
                                                                                    </td>
                                                                                    <?php } ?>
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
                                                    <div id="medicationTbPage" class="tab-pane fadein <?php echo $hideNotice; ?> <?php echo $actvTabPhrmcy; ?>" style="border:none !important;">
                                                                        <?php
                                                                        if ($canEdtADRecs === true) {
                                                                            $nwRowHtml = "<tr id=\"medicationRow__WWW123WWW\">
                                                        <td class=\"lovtd\"><span class=\"\">New</span></td>                                               
                                                        <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"medicationRow_WWW123WWW_DrugItm\" value=\"\" readonly=\"true\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"medicationRow_WWW123WWW_DrugItmID\" value=\"-1\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Pharmacy Items', '', '', '', 'radio', true, '', 'medicationRow_WWW123WWW_DrugItmID', 'medicationRow_WWW123WWW_DrugItm', 'clear', 1, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"medicationRow_WWW123WWW_MedicationID\" value=\"-1\">
                                                                </div>
                                                            </div>
                                                            <span style=\"$dsplyOthrElmnts\"></span>                                                   
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                <div class=\"input-group\" style=\"width:100%\">
                                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"medicationRow_WWW123WWW_Instruction\" value=\"\" readonly=\"true\" style=\"width:100 !important;\">         
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getDosageForm('100','medicationRow_WWW123WWW_Instruction');\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"medicationRow_WWW123WWW_DoseQty\" value=\"\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"medicationRow_WWW123WWW_DoseQtyUOM\" value=\"\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"medicationRow_WWW123WWW_FrqncyNo\" value=\"\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"medicationRow_WWW123WWW_FrqncyUOM\" value=\"\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"medicationRow_WWW123WWW_DrtnNo\" value=\"\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"medicationRow_WWW123WWW_DrtnUOM\" value=\"\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <span style=\"$dsplyOthrElmnts\"></span> 
                                                        </td>                                                                                              
                                                        <td class=\"lovtd\">
                                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                    <select data-placeholder=\"Select...\" class=\"form-control chosen-select rqrdFld\" id=\"medicationRow_WWW123WWW_DoseForm\">";
                                                                            $brghtStr = "";
                                                                            $isDynmyc = FALSE;
                                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Dosage Methods"), $isDynmyc, -1, "", "");
                                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                                $selectedTxt = "";
                                                                                if($titleRow[0] == "Oral"){
                                                                                    $selectedTxt = "selected";
                                                                                }
                                                                                $nwRowHtml .= "<option value=\"$titleRow[0]\" $selectedTxt>$titleRow[0]</option>";
                                                                            }

                                                                            $nwRowHtml .= "</select>
                                                                </div>
                                                                <span style=\"$dsplyOthrElmnts\"></span>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;$dsplyDcElmnts;\">
                                                                    <div class=\"form-check\" style=\"font-size: 12px !important;text-align:center;\">
                                                                        <label class=\"form-check-label\">
                                                                            <input type=\"checkbox\" class=\"form-check-input\" id=\"medicationRow_WWW123WWW_SubAllowed\" name=\"medicationRow_WWW123WWW_SubAllowed\">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <span style=\"$dsplyOthrElmnts\"></span>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;$dsplyDcElmnts;\">
                                                                    <textarea class=\"form-control\" aria-label=\"...\" id=\"medicationRow_WWW123WWW_DocCmnts\" style=\"width:100% !important;\"></textarea>
                                                                </div>
                                                                <span style=\"$dsplyOthrElmnts\"></span>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;$dsplyOthrElmnts;\">
                                                                    <div class=\"form-check\" style=\"font-size: 12px !important;text-align:center;\">
                                                                        <label class=\"form-check-label\">
                                                                            <input type=\"checkbox\" class=\"form-check-input\" id=\"medicationRow_WWW123WWW_IsDspnsd\" name=\"medicationRow_WWW123WWW_IsDspnsd\">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <span style=\"$dsplyDcElmnts\"></span>                                                       
                                                        </td>                                                
                                                        <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;$dsplyOthrElmnts;\">
                                                                <textarea class=\"form-control\" aria-label=\"...\" id=\"medicationRow_WWW123WWW_PhrmcyCmnts\" style=\"width:100% !important;\"></textarea>
                                                            </div>
                                                            <span style=\"$dsplyDcElmnts\"></span>                                                         
                                                        </td>                                                                                              
                                                        <td class=\"lovtd\">
                                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;$dsplyOthrElmnts;\">
                                                                    <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"medicationRow_WWW123WWW_AdminTimes\">";

                                                                            $valslctdArry = array("", "", "", "", "");
                                                                            $srchInsArrys = array("After Meals", "Before Meals");

                                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                $nwRowHtml .= "<option value=\"$srchInsArrys[$z]\" $valslctdArry[$z]>$srchInsArrys[$z]</option>";
                                                                            }
                                                                            $nwRowHtml .= "</select>
                                                                </div>
                                                                <span style=\"$dsplyDcElmnts\"></span>                                                       
                                                        </td>
                                                        <td class = \"lovtd\">&nbsp;</td>
                                                        </td>
                                                            <td class=\"lovtd\">
                                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delMedication('medicationRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Medication\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                </button>
                                                            </td>
                                                        </tr>";
                                                                            $nwRowHtml = urlencode($nwRowHtml);
                                                                            ?>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                            <?php
                                                            if ($checkInDate !== "" && $checkOutDate == "" && $canEdtADRecs) {
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('medicationTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Medication">
                                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;&nbsp;New Medication
                                                                </button> 
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveMedication(<?php echo $prsnid; ?>);" style="width:100% !important;">
                                                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Save
                                                                </button>
                                                                <button type="button" id="medicationBtn" class="btn btn-default" data-toggle="tooltip" style="margin-bottom: 5px; <?php echo $dsplyDcElmnts; ?>" data-placement="bottom" title="Schedule Appointment" style="margin-bottom: 5px;" 
                                                                        onclick="saveNFinalizeMedication(<?php echo $prsnid; ?>,<?php echo $vstId; ?>, <?php echo $PHMainSrvsTypeID; ?>, 'PH-0001', <?php echo $PHMainSrvsPrvdrGrpID; ?>, <?php echo $cnsltnID; ?>, '<?php echo $lnkdAppntmntDate; ?>');" style="width:100% !important;">
                                                                    <img src="cmn_images/clock.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Finalize
                                                                </button> 
                                                            <?php } ?>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="" style="float:right;">
                                                                        <?php if($canViewAppntmntDataItms) { ?>
                                                                        <button type="button" class="btn btn-default" data-toggle="tooltip" style="margin-bottom: 5px; <?php echo $dsplyOthrElmnts; ?>" data-placement="bottom" title="View Appointment Items" onclick="getOneAppntmntDataItemsForm(<?php echo $pkID; ?>, 3, 'ShowDialog', '<?php echo $appntmntNo; ?>', '<?php echo $trnsStatus; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                           <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                        <?php } if((getAppntmntBillVisit($vstId) == "1" || getAppntmntBillVisit($vstId) == "Y") && $canGenSalesInvoice) { ?>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px; <?php echo $dsplyOthrElmnts; ?>" onclick="getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Sales Invoice', 'NO', 'SALES',-1, <?php echo $pkID; ?>);" style="">
                                                                            <img src="cmn_images/sale.jpg" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            Invoice
                                                                        </button>
                                                                         <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?> 
                                                        <div class="row"> 
                                                            <div  class="col-md-12">
                                                                <table class="table table-striped table-bordered table-responsive" id="medicationTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px !important;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th style="min-width:150px;">Drug</th>
                                                                            <th style="min-width:310px;">Dosage</th>
                                                                            <th style="min-width:70px;">Form</th>
                                                                            <th>Substitute</br>Allowed?</th>
                                                                            <th style="min-width:150px;">Doctor's Comments</th>
                                                                            <th>Dispense?</th>
                                                                            <th style="min-width:150px;">Pharmacist Comments</th>
                                                                            <th style="min-width:100px;">Admin Times</th>
                                                                            <th style="max-width:70px;width:70px;<?php echo $dsplyDcElmnts; ?>">Sent?</th>
                                                                            <th>&nbsp;</th>
                                                                            <?php
                                                                            if ($canEdtADRecs === true) {
                                                                                ?>
                                                                                <?php if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                                                <th>&nbsp;</th>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $cntr = 0;
                                                                        $curIdx = 0;
                                                                        $lmtSze = 0;
                                                                        $result2 = get_AllMedications($cnsltnID, $pkID);
                                                                        $prmID = -1;
                                                                        $instruction = "";
                                                                        while ($row2 = loc_db_fetch_array($result2)) {
                                                                            $cntr += 1;
                                                                            $instruction = $row2[3] . " " . $row2[4] . " " . $row2[5] . " times a " . $row2[6] . " for " . $row2[7] . " " . $row2[8];
                                                                            
                                                                            ?>
                                                                            <tr id="medicationRow_<?php echo $cntr; ?>">                                    
                                                                                <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>                                               
                                                                                <td class="lovtd">
                                                                            <?php if ($canEdtADRecs === true) {
                                                                                ?>
                                                                                    
                                                                                        <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row2[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                            <span style=""><?php echo $row2[2]; ?></span>
                                                                                        <?php } else { ?>
                                                                                        <div class="form-group form-group-sm" style="width:100% !important; <?php echo $dsplyDcElmnts; ?>">
                                                                                            <div class="input-group"  style="width:100%;">
                                                                                                <input type="text" class="form-control" aria-label="..." id="medicationRow<?php echo $cntr; ?>_DrugItm" value="<?php echo $row2[2]; ?>" readonly="true">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="medicationRow<?php echo $cntr; ?>_DrugItmID" value="<?php echo $row2[1]; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Pharmacy Items', '', '', '', 'radio', true, '<?php echo $row2[2]; ?>', 'medicationRow<?php echo $cntr; ?>_DrugItmID', 'medicationRow<?php echo $cntr; ?>_DrugItm', 'clear', 1, '');">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="medicationRow<?php echo $cntr; ?>_MedicationID" value="<?php echo $row2[0]; ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                        <span style="<?php echo $dsplyOthrElmnts; ?>"><?php echo $row2[2]; ?></span>
                                                                                        <?php } ?>
                                                                                    <?php } else { ?>
                                                                                        <span><?php echo $row2[2]; ?></span>
                                                                                <?php } ?>                                                         
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                <?php if ($canEdtADRecs === true) {
                                                                                    ?>
                                                                                        <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row2[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                            <span style=""><?php echo $instruction; ?></span>
                                                                                        <?php } else { ?>
                                                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important; <?php echo $dsplyDcElmnts; ?>">
                                                                                            <div class="input-group" style="width:100%">
                                                                                                <input type="text" class="form-control" aria-label="..." id="medicationRow<?php echo $cntr; ?>_Instruction" value="<?php echo $instruction; ?>" readonly="true" style="width:100 !important;">         
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getDosageForm('100', 'medicationRow<?php echo $cntr; ?>_Instruction');">
                                                                                                    <input type="hidden" class="form-control" aria-label="..." id="medicationRow<?php echo $cntr; ?>_DoseQty" value="<?php echo $row2[3]; ?>">
                                                                                                    <input type="hidden" class="form-control" aria-label="..." id="medicationRow<?php echo $cntr; ?>_DoseQtyUOM" value="<?php echo $row2[4]; ?>">
                                                                                                    <input type="hidden" class="form-control" aria-label="..." id="medicationRow<?php echo $cntr; ?>_FrqncyNo" value="<?php echo $row2[5]; ?>">
                                                                                                    <input type="hidden" class="form-control" aria-label="..." id="medicationRow<?php echo $cntr; ?>_FrqncyUOM" value="<?php echo $row2[6]; ?>">
                                                                                                    <input type="hidden" class="form-control" aria-label="..." id="medicationRow<?php echo $cntr; ?>_DrtnNo" value="<?php echo $row2[7]; ?>">
                                                                                                    <input type="hidden" class="form-control" aria-label="..." id="medicationRow<?php echo $cntr; ?>_DrtnUOM" value="<?php echo $row2[8]; ?>">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <span style="<?php echo $dsplyOthrElmnts; ?>"><?php echo $instruction; ?></span>
                                                                                        <?php } ?>
                                                                                        <?php } else { ?>
                                                                                        <span><?php echo $instruction; ?></span>
                                                                                        <?php } ?>                                                         
                                                                                </td>                                                                                              
                                                                                <td class="lovtd">
                                                                                <?php if ($canEdtADRecs === true) {
                                                                                    ?>
                                                                                        <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row2[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                            <span style=""><?php echo $row2[9]; ?></span>
                                                                                        <?php } else { ?>
                                                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important; <?php echo $dsplyDcElmnts; ?>">
                                                                                            <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="medicationRow<?php echo $cntr; ?>_DoseForm" style="width:100% !important;">
                                                                                                <?php
                                                                                                $brghtStr = "";
                                                                                                $isDynmyc = FALSE;
                                                                                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Dosage Methods"), $isDynmyc, -1, "", "");
                                                                                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                                                    $selectedTxt = "";
                                                                                                    if ($titleRow[0] == $row2[9]) {
                                                                                                        $selectedTxt = "selected";
                                                                                                    }
                                                                                                    ?>
                                                                                                        <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                                                    <?php
                                                                                                }
                                                                                                ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <span style="<?php echo $dsplyOthrElmnts; ?>"><?php echo $row2[9]; ?></span>
                                                                                        <?php } ?>
                                                                                            <?php } else { ?>
                                                                                        <span><?php echo $row2[9]; ?></span>
                                                                                            <?php } ?>                                                         
                                                                                </td>  
                                                                                <td class="lovtd" style="text-align:center;">
                                                                                            <?php
                                                                                            $isChkd = "";
                                                                                            if ($row2[10] == "1") {
                                                                                                $isChkd = "checked=\"true\"";
                                                                                            }
                                                                                            if ($canEdtADRecs === true) {
                                                                                                ?>
                                                                                        <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row2[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                            <span style=""><?php echo ($row2[10] == "1" ? "Yes" : "No"); ?></span>
                                                                                        <?php } else { ?>
                                                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important; <?php echo $dsplyDcElmnts; ?>">
                                                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                                                <label class="form-check-label">
                                                                                                    <input type="checkbox" class="form-check-input" id="medicationRow<?php echo $cntr; ?>_SubAllowed" name="medicationRow<?php echo $cntr; ?>_SubAllowed" <?php echo $isChkd ?>>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <span style="<?php echo $dsplyOthrElmnts; ?>"><?php echo ($row2[10] == "1" ? "Yes" : "No"); ?></span>
                                                                                        <?php } ?>
                                                                                    <?php } else { ?>
                                                                                        <span><?php echo ($row2[10] == "1" ? "Yes" : "No"); ?></span>
                                                                                    <?php } ?>                                                         
                                                                                </td>                                            
                                                                                <td class="lovtd">
                                                                                <?php
                                                                                if ($canEdtADRecs === true) {
                                                                                    ?>
                                                                                        <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row2[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                            <span style=""><?php echo $row2[11]; ?></span>
                                                                                        <?php } else { ?>
                                                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important; <?php echo $dsplyDcElmnts; ?>">
                                                                                            <textarea class="form-control" aria-label="..." id="medicationRow<?php echo $cntr; ?>_DocCmnts" style="width:100% !important;"><?php echo $row2[11]; ?></textarea>
                                                                                        </div>
                                                                                        <span style="<?php echo $dsplyOthrElmnts; ?>"><?php echo $row2[11]; ?></span>
                                                                                        <?php } ?>
                                                                                    <?php } else { ?>
                                                                                        <span><?php echo $row2[11]; ?></span>
                                                                                    <?php } ?>                                                         
                                                                                </td>
                                                                                <td class="lovtd" style="text-align:center;">
                                                                                    <?php
                                                                                    $isChkd2 = "";
                                                                                    if ($row2[12] == "1") {
                                                                                        $isChkd2 = "checked=\"true\"";
                                                                                    }
                                                                                    if ($canEdtADRecs === true) {
                                                                                        ?>
                                                                                        <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row2[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                            <span style=""><?php echo ($row2[12] == "1" ? "Yes" : "No"); ?></span>
                                                                                        <?php } else { ?>
                                                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important; <?php echo $dsplyOthrElmnts; ?>">
                                                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                                                <label class="form-check-label">
                                                                                                    <input type="checkbox" class="form-check-input" id="medicationRow<?php echo $cntr; ?>_IsDspnsd" name="medicationRow<?php echo $cntr; ?>_IsDspnsd" <?php echo $isChkd2 ?>>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <span style="<?php echo $dsplyDcElmnts; ?>"><?php echo ($row2[12] == "1" ? "Yes" : "No"); ?></span>
                                                                                        <?php } ?> 
                                                                                    <?php } else { ?>
                                                                                        <span><?php echo ($row2[12] == "1" ? "Yes" : "No"); ?></span>
                                                                                    <?php } ?>                                                         
                                                                                </td>                                               
                                                                                <td class="lovtd">
                                                                                    <?php
                                                                                    if ($canEdtADRecs === true) {
                                                                                        ?>
                                                                                        <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row2[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                            <span style=""><?php echo $row2[13]; ?></span>
                                                                                        <?php } else { ?>
                                                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important; <?php echo $dsplyOthrElmnts; ?>">
                                                                                            <textarea class="form-control" aria-label="..." id="medicationRow<?php echo $cntr; ?>_PhrmcyCmnts" style="width:100% !important;"><?php echo $row2[13]; ?></textarea>
                                                                                        </div>
                                                                                        <span style="<?php echo $dsplyDcElmnts; ?>"><?php echo $row2[13]; ?></span>
                                                                                        <?php } ?>   
                                                                                    <?php } else { ?>
                                                                                        <span><?php echo $row2[13]; ?></span>
                                                                                    <?php } ?>                                                         
                                                                                </td>                                                                                             
                                                                                <td class="lovtd">
                                                                                    <?php if ($canEdtADRecs === true) {
                                                                                        ?>
                                                                                        <?php if (($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") || ($row2[12] == "1" && $cnsltnID > 0)) { ?>
                                                                                            <span style=""><?php echo $row2[14]; ?></span>
                                                                                        <?php } else { ?>
                                                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important; <?php echo $dsplyOthrElmnts; ?>">
                                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="medicationRow<?php echo $cntr; ?>_AdminTimes">
                                                                                            <?php
                                                                                            $valslctdArry = array("", "");
                                                                                            $srchInsArrys = array("After Meals", "Before Meals");

                                                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                if ($row2[14] == $srchInsArrys[$z]) {
                                                                                                    $valslctdArry[$z] = "selected";
                                                                                                }
                                                                                                ?>
                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <span style="<?php echo $dsplyDcElmnts; ?>"><?php echo $row2[14]; ?></span>
                                                                                         <?php } ?> 
                                                                                            <?php } else { ?>
                                                                                        <span><?php echo $row2[14]; ?></span>
                                                                                            <?php } ?>                                                         
                                                                                </td>
                                                                                <td style="text-align: center !important;<?php echo $dsplyDcElmnts; ?>">
                                                                                    <?php 
                                                                                        $isSent = "Yes"; 
                                                                                        $isSentClr = "color:green; font-weight:bold;"; 
                                                                                        if($row2[16] == $pkID){
                                                                                            $isSent = "No"; 
                                                                                            $isSentClr = "color:red; font-weight:bold;"; 
                                                                                        }                                                                                    
                                                                                    ?>
                                                                                    <span style="<?php echo $isSentClr; ?>"><?php echo $isSent; ?></span>
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px; max-height:30px !important;" onclick="getOneFscDocsForm_Gnrl(<?php echo $row2[0]; ?>,'PRESCRIPTIONS', 140, 'Prescriptions Attached Documents');" data-toggle="tooltip" data-placement="bottom" title = "Prescriptions Attached Documents">
                                                                                        <img src="cmn_images/adjunto.png"  style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                                            <?php
                                                                                            if ($canEdtADRecs === true) {
                                                                                                ?>
                                                                                                <?php if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                                                    <td class="lovtd">
                                                                                        <?php if ($row2[12] == "0") { ?>
                                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delMedication('medicationRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Parameter">
                                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <?php } ?>
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
                                                    <div id="vitalsTbPage" class="tab-pane fadein <?php echo $hideNotice; ?> <?php echo $actvTabVitals; ?>" style="border:none !important;">
                                                                            <?php
                                                                            $vstID = (int) getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "vst_id", $pkID);
                                                                            
                                                                            $vitalsStatus = getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "appntmnt_status", $pkID);
                                                                            $result = get_VisitVitalsData($vstID);
                                                                            $weight = "";
                                                                            $height = "";
                                                                            $bmi = "";
                                                                            $bmi_status = "";
                                                                            $bp_systlc = "";
                                                                            $bp_diastlc = "";
                                                                            $bp_status = "";
                                                                            $pulse = "";
                                                                            $body_tmp = "";
                                                                            $tmp_loc = "";
                                                                            $resptn = "";
                                                                            $oxgn_satn = "";
                                                                            $head_circum = "";
                                                                            $waist_circum = "";
                                                                            $bowel_actn = "";
                                                                            $cmnts = "";
                                                                            
                                                                            //if(!($vitalsStatus == "Scheduled" || $vitalsStatus == "In Progress")){
                                                                                while ($row2 = loc_db_fetch_array($result)) {
                                                                                    $weight = $row2[1];
                                                                                    $height = $row2[2];
                                                                                    $bmi = $row2[3];
                                                                                    $bmi_status = $row2[4];
                                                                                    $bp_systlc = $row2[5];
                                                                                    $bp_diastlc = $row2[6];
                                                                                    $bp_status = $row2[7];
                                                                                    $pulse = $row2[8];
                                                                                    $body_tmp = $row2[9];
                                                                                    $tmp_loc = $row2[10];
                                                                                    $resptn = (int)$row2[11] == 0 ? "" : $row2[11];
                                                                                    $oxgn_satn = (int)$row2[12] == 0 ? "" : $row2[12];
                                                                                    $head_circum = (int)$row2[13] == 0 ? "" : $row2[13];
                                                                                    $waist_circum = (int)$row2[14] == 0 ? "" : $row2[14];
                                                                                    $bowel_actn = $row2[15];
                                                                                    $cmnts = $row2[16];
                                                                                }
                                                                            //}
                                                                            ?>
                                                        <div class='row'>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >Weight (kg):</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $weight; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >Height (m):</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $height; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <div class='row'>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >Body Mass Index (kg/m2):</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $bmi; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >BMI Status:</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $bmi_status; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='row'>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >BP Systolic:</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $bp_systlc; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >BP Diastolic:</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $bp_diastlc; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <div class='row'>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >BP Status:</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $bp_status; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >Pulse (bpm):</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $pulse; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <div class='row'>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >Body Temperature (°C):</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $body_tmp; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >Temperature Location:</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $tmp_loc; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='row'>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >Respiration (bpm):</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $resptn; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >Oxygen Saturation:</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $oxgn_satn; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='row'>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >Head Circumference:</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $head_circum; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div  class="col-md-6">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntNo" class="control-label col-md-6" >Waist Circumference:</label>
                                                                    <div  class="col-md-6">
                                                                        <span><?php echo $waist_circum; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='row'>
                                                            <div  class="col-md-12">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntCmnts" class="control-label col-md-3" >Bowel Action:</label>
                                                                    <div  class="col-md-9">
                                                                        <span><?php echo $bowel_actn; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='row'>
                                                            <div  class="col-md-12">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="frmAppntmntCmnts" class="control-label col-md-3" >Remarks:</label>
                                                                    <div  class="col-md-9">
                                                                        <span><?php echo $cmnts; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="inHouseAdmsnTbPage" class="tab-pane fadein <?php echo $hideNotice; ?> <?php echo $actvTabAdmissions; ?>" style="border:none !important;">
                                                        <?php if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") && $canEdtADRecs) { ?>
                                                        <div class="row" style="padding-left:20px !important;">
                                                            <button type="button" id="inhouseAdmsnBtn" class="btn btn-default" data-toggle="tooltip" style="margin-bottom: 5px;margin-top:5px; <?php echo $dsplyDcElmnts; ?>" data-placement="bottom" title="Schedule Appointment" style="margin-bottom: 5px;" onclick="createLinkedCnsltnAppointment(<?php echo $vstId; ?>, <?php echo $IAMainSrvsTypeID; ?>, 'IA-0001', <?php echo $IAMainSrvsPrvdrGrpID; ?>, <?php echo $cnsltnID; ?>, '<?php echo $lnkdAppntmntDate; ?>');" style="width:100% !important;">
                                                                <img src="cmn_images/clock.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Finalize
                                                            </button>
                                                        </div>
                                                        <?php } ?>
                                                        <div class="row" style="max-height: 245px !important;">
                                                            <div class="col-md-3">
                                                                <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="docAdmsnCheckInDate" class="control-label">Check-In Date:</label>
                                                                    <div  class="">
                                                                        <?php if ($canEdtADRecs === true) { ?>
                                                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                <input style="width:100% !important;" <?php echo $mkReadOnly; ?> class="form-control" size="16" type="text" id="docAdmsnCheckInDate" name="docAdmsnCheckInDate" value="<?php echo $docAdmsnCheckInDate; ?>">
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                            </div>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo $docAdmsnCheckInDate; ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="docAdmsnCheckInNoOfDays" class="control-label">Number of Days:</label>
                                                                    <div  class="">
                                                                        <?php if ($canEdtADRecs === true) { ?>
                                                                            <input type="number" min="1" <?php echo $mkReadOnly; ?> class="form-control" aria-label="..." id="docAdmsnCheckInNoOfDays" name="docAdmsnCheckInNoOfDays" style="width:100%;" value="<?php echo $docAdmsnCheckInNoOfDays; ?>"/>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo $docAdmsnCheckInNoOfDays; ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                                    <label for="docAdmsnInstructions" class="control-label">Doctor's Instructions:</label>
                                                                    <div  class="">
                                                                        <?php if ($canEdtADRecs === true) { ?>
                                                                            <textarea class="form-control" <?php echo $mkReadOnly; ?> aria-label="..." id="docAdmsnInstructions" name="docAdmsnInstructions" style="width:100%;" cols="9" rows="6"><?php echo $docAdmsnInstructions; ?></textarea>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo $patientCmplt; ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                            <?php
                                                            if ($canEdtADRecs === true) {
                                                                $nwRowHtml = "<tr id=\"allInptntAdmsnsRow__WWW123WWW\">
                                                                    <td class=\"lovtd\"><span class=\"\">New</span></td>
                                                                    <td class=\"lovtd\"> 
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allInptntAdmsnsRow_WWW123WWW_InptntAdmsnID\" value=\"-1\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allInptntAdmsnsRow_WWW123WWW_RefCheckInId\" value=\"-1\">
                                                                        <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">
                                                                            <input style=\"width:100% !important;\" class=\"form-control rqrdFld\" size=\"16\" type=\"text\" id=\"allInptntAdmsnsRow_WWW123WWW_AdmissionDate\" name=\"allInptntAdmsnsRow_WWW123WWW_AdmissionDate\" value=\"\">
                                                                            <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                            <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                        </div>
                                                                    </td>
                                                                    <td class=\"lovtd\"> 
                                                                        <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">
                                                                            <input style=\"width:100% !important;\" class=\"form-control\" size=\"16\" type=\"text\" id=\"allInptntAdmsnsRow_WWW123WWW_CheckOutDate\" name=\"allInptntAdmsnsRow_WWW123WWW_CheckOutDate\" value=\"\">
                                                                            <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                            <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                        </div>
                                                                    </td>                                                                                                  
                                                                    <td class=\"lovtd\">
                                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allInptntAdmsnsRow_WWW123WWW_SrvcTyp\" value=\"\" readonly=\"true\">
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allInptntAdmsnsRow_WWW123WWW_SrvcTypID\" value=\"-1\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Hospitality Service Types', 'sltdOrgID', 'hotlChckinFcltyTypeClinic', '', 'radio', true, '', 'allInptntAdmsnsRow_WWW123WWW_SrvcTypID', 'allInptntAdmsnsRow_WWW123WWW_SrvcTyp', 'clear', 1, '', function () {});\">
                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>                                                       
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allInptntAdmsnsRow_WWW123WWW_RmNum\" value=\"\" readonly=\"true\">
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allInptntAdmsnsRow_WWW123WWW_RmID\" value=\"-1\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getRoomNumLovsPage('allInptntAdmsnsRow_WWW123WWW_AdmissionDate', 'allInptntAdmsnsRow_WWW123WWW_CheckOutDate', 'myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Hospitality Facility Numbers', 'allInptntAdmsnsRow_WWW123WWW_SrvcTypID', '', '', 'radio', true, '', 'allInptntAdmsnsRow_WWW123WWW_RmID', 'allInptntAdmsnsRow_WWW123WWW_RmNum', 'clear', 1, '', function () {});\">
                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>                                                       
                                                                    </td>
                                                                    <td class=\"lovtd\"></td>
                                                                    <td class=\"lovtd\"></td>
                                                                    <td class=\"lovtd\"></td>
                                                                    <td class=\"lovtd\"></td>
                                                                    <td class=\"lovtd\"></td>
                                                                    <td class=\"lovtd\">
                                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRptRole('allInptntAdmsnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Record\">
                                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                        </button>
                                                                    </td>
                                                        </tr>";
                                                                $nwRowHtml = urlencode($nwRowHtml);
                                                                if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { 
                                                                    ?>
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-top:5px; <?php echo $dsplyOthrElmnts; ?>" onclick="insertNewRowBe4('allInptntAdmsnsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Record">
                                                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;&nbsp;New Room
                                                                                </button>
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-top:5px; <?php echo $dsplyOthrElmnts; ?>" onclick="saveInptntAdmsn();" style="width:100% !important;">
                                                                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    Save
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?> 
                                                                <input type="hidden" class="form-control" aria-label="..." id="sltdOrgID" name="sltdOrgID" value="<?php echo $orgID; ?>" readonly="true">
                                                                <input type="hidden" class="form-control" aria-label="..." id="hotlChckinFcltyTypeClinic" name="hotlChckinFcltyTypeClinic" value="Room/Hall" readonly="true">
                                                                <div class="row"> 
                                                                    <div  class="col-md-12" style="max-height: 274px !important;overflow-y: auto;">
                                                                        <table class="table table-striped table-bordered table-responsive" id="allInptntAdmsnsTable" cellspacing="0" width="100%" style="width:100%;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No.</th>
                                                                                    <th style="max-width:130px;width:130px;">Check-In Date</th>
                                                                                    <th style="max-width:130px;width:130px;">Check-Out Date</th>
                                                                                    <th style="min-width:120px;">Facility Type</th>
                                                                                    <th style="min-width:120px;">Room</th>
                                                                                    <th>Doc. No.</th>
                                                                                    <th style="text-align:right !important;">Bill (GHS)</th>
                                                                                    <th style="text-align:right !important;">Payment<br/>(GHS)</th>
                                                                                    <th style="text-align:right !important;">Balance<br/>(GHS)</th>
                                                                                    <th>&nbsp;</th>
                                                                                    <?php
                                                                                    if ($canDelADRecs === true) {
                                                                                        ?>
                                                                                        <?php if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                                                        <th>&nbsp;</th>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $cntr = 0;
                                                                                $curIdx = 0;
                                                                                $result3 = get_AllInhouseAdmissions($cnsltnID, $pkID);
                                                                                $rptRlID = -1;
                                                                                $refChckInId = -1;
                                                                                while ($row3 = loc_db_fetch_array($result3)) {
                                                                                    $cntr += 1;
                                                                                    $refChckInId = $row3[8];
                                                                                    
                                                                                    $ttlInhsBillPymnt = "0.00";
                                                                                    $ttlInhsBill = "0.00";
                                                                                    $ttlInhsOutsdnPymnt = "0.00";

                                                                                    $rsltw = get_One_SalesInvcAmounts($row3[10]);
                                                                                    if ($rw = loc_db_fetch_array($rsltw)) {
                                                                                        $ttlInhsBill = $ttlInhsBill + (float) $rw[0];
                                                                                        $ttlInhsBillPymnt = $ttlInhsBillPymnt + $rw[1];
                                                                                    }
                                                                                    
                                                                                    $ttlInhsOutsdnPymnt = (float)$ttlInhsBill -  (float)$ttlInhsBillPymnt;
                                                                                    $outstndnInhsAmntColor = "color:green;";
                                                                                    if($ttlInhsOutsdnPymnt > 0){
                                                                                        $outstndnInhsAmntColor = "color:red;";
                                                                                    }
                                                                                    
                                                                                    /*if ($row3[5] !== "") {
                                                                                        $dsplyDcElmnts = "";
                                                                                        $dsplyOthrElmnts = "display:none";
                                                                                    }*/

                                                                                    ?>
                                                                                     <tr id="allInptntAdmsnsRow_<?php echo $cntr; ?>">                                    
                                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                        <td class="lovtd">
                                                                                            <?php if ($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled" || $row3[5] !== "") { ?>
                                                                                                <span style=""><?php echo $row3[1]; ?></span>
                                                                                            <?php } else { ?>
                                                                                            <span style="<?php echo $dsplyDcElmnts; ?>"><?php echo $row3[1]; ?></span>
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="allInptntAdmsnsRow<?php echo $cntr; ?>_InptntAdmsnID" value="<?php echo $row3[0]; ?>">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="allInptntAdmsnsRow<?php echo $cntr; ?>_RefCheckInId" value="<?php echo $row3[8]; ?>">
                                                                                            <div class="input-group date form_date" style="<?php echo $dsplyOthrElmnts; ?>" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                                <input style="width:100% !important;" class="form-control rqrdFld" size="16" type="text" id="allInptntAdmsnsRow<?php echo $cntr; ?>_AdmissionDate" name="allInptntAdmsnsRow<?php echo $cntr; ?>_AdmissionDate" value="<?php echo substr($row3[1],0,11); ?>"/>
                                                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                            </div>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td class="lovtd"> 
                                                                                            <?php if ($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled" || $row3[5] !== "") { ?>
                                                                                                <span style=""><?php echo $row3[2]; ?></span>
                                                                                            <?php } else { ?>
                                                                                            <span style="<?php echo $dsplyDcElmnts; ?>"><?php echo $row3[2]; ?></span>
                                                                                            <div class="input-group date form_date" style="<?php echo $dsplyOthrElmnts; ?>" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                                <input style="width:100% !important;" class="form-control rqrdFld" size="16" type="text" id="allInptntAdmsnsRow<?php echo $cntr; ?>_CheckOutDate" name="allInptntAdmsnsRow<?php echo $cntr; ?>_CheckOutDate" value="<?php echo substr($row3[2],0,11); ?>"/>
                                                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                            </div>
                                                                                            <?php } ?>
                                                                                        </td> 
                                                                                        <td class="lovtd"> 
                                                                                            <?php if ($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled" || $row3[5] !== "") { ?>
                                                                                                <span style=""><?php echo $row3[3]; ?></span>
                                                                                            <?php } else { ?>
                                                                                            <span style="<?php echo $dsplyDcElmnts; ?>"><?php echo $row3[3]; ?></span>
                                                                                            <div class="input-group"  style="width:100%;<?php echo $dsplyOthrElmnts; ?>">
                                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="allInptntAdmsnsRow<?php echo $cntr; ?>_SrvcTyp" value="<?php echo $row3[3]; ?>" readonly="true">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="allInptntAdmsnsRow<?php echo $cntr; ?>_SrvcTypID" value="<?php echo $row3[6]; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Hospitality Service Types', 'sltdOrgID', 'hotlChckinFcltyTypeClinic', '', 'radio', true, '', 'allInptntAdmsnsRow<?php echo $cntr; ?>_SrvcTypID', 'allInptntAdmsnsRow<?php echo $cntr; ?>_SrvcTyp', 'clear', 1, '', function () {});">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td class="lovtd"> 
                                                                                            <?php if ($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled" || $row3[5] !== "") { ?>
                                                                                                <span style=""><?php echo $row3[4]; ?></span>
                                                                                            <?php } else { ?>
                                                                                            <span style="<?php echo $dsplyDcElmnts; ?>"><?php echo $row3[4]; ?></span>
                                                                                            <div class="input-group"  style="width:100%;<?php echo $dsplyOthrElmnts; ?>">
                                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="allInptntAdmsnsRow<?php echo $cntr; ?>_RmNum" value="<?php echo $row3[4]; ?>" readonly="true">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="allInptntAdmsnsRow<?php echo $cntr; ?>_RmID" value="<?php echo $row3[7]; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getRoomNumLovsPage('allInptntAdmsnsRow<?php echo $cntr; ?>_AdmissionDate', 'allInptntAdmsnsRow<?php echo $cntr; ?>_CheckOutDate', 'myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Hospitality Facility Numbers', 'allInptntAdmsnsRow<?php echo $cntr; ?>_SrvcTypID', '', '', 'radio', true, '', 'allInptntAdmsnsRow<?php echo $cntr; ?>_RmID', 'allInptntAdmsnsRow<?php echo $cntr; ?>_RmNum', 'clear', 1, '', function () {});">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                             <?php } ?>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <?php if ($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled" || $row3[5] !== "") { ?>
                                                                                                <span style=""><?php echo $row3[5]; ?></span>
                                                                                            <?php } else { ?>
                                                                                            <a href="#" onclick="getOneHotlChckinDocForm2(<?php echo $refChckInId; ?>, 3, 'ShowDialog', 'Check-In', 'CHECK-IN', 'allmodules', 'Room/Hall',<?php echo $row3[6]; ?>,<?php echo $row3[7]; ?>,<?php echo $row3[0]; ?>);">
                                                                                                <?php echo $row3[5]; ?>
                                                                                            </a>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td class="lovtd" style="text-align:right !important; font-weight:bold !important;"><?php echo number_format($ttlInhsBill,2); ?></td>
                                                                                        <td class="lovtd" style="text-align:right !important;color:blue; font-weight:bold !important;"><?php echo number_format($ttlInhsBillPymnt,2); ?></td>
                                                                                        <td class="lovtd" style="text-align:right !important;font-size:14px !important; font-weight:bold !important;<?php echo $outstndnInhsAmntColor; ?>"><?php echo number_format($ttlInhsOutsdnPymnt,2); ?></td>
                                                                                        <td class="lovtd">
                                                                                        <?php if ($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled" || $row3[5] !== "") { ?>
                                                                                                <span style="">&nbsp;</span>
                                                                                        <?php } else {
                                                                                                /*<?php echo "vwtyp=>".$vwtyp."row3[5]=>".$row3[5]; ?>."row3[1]=>".$row3[1]."row3[2]=>".$row3[2]."row3[3]=>".$row3[3]."row3[4]=>".$row3[4]."vwtypActn=>".$vwtypActn.
                                                                                                        "trnsStatus=>".$trnsStatus."dsplyDcElmnts=>".$dsplyDcElmnts."dsplyOthrElmnts=>".$dsplyOthrElmnts;*/
                                                                                            ?>    
                                                                                        <?php if ($row3[5] == "" && $row3[1] != "" && $row3[2] != "" && $row3[3] != "" && $row3[4] != "") { ?>
                                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneHotlChckinDocForm2(<?php echo $refChckInId; ?>, 3, 'ShowDialog', 'Check-In', 'CHECK-IN', 'allmodules', 'Room/Hall',<?php echo $row3[6]; ?>,<?php echo $row3[7]; ?>,<?php echo $row3[0]; ?>);" data-toggle="tooltip" data-placement="bottom" title="Create Check-In Document">
                                                                                                <img src="cmn_images/98.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                        <?php } ?>
                                                                                         <?php } ?>
                                                                                        </td>
                                                                                        <?php
                                                                                        if ($canDelADRecs === true) {
                                                                                            ?>
                                                                                        <?php if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                                                        <td class="lovtd">
                                                                                        <?php if ($row3[5] == "") { ?>
                                                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delInptntAdmsn('allInptntAdmsnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Check-In">
                                                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                                </button>
                                                                                        <?php } ?>
                                                                                        </td>
                                                                                        <?php } ?>
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
                                                    <div id="rcmddSrvsTbPage" class="tab-pane fade" style="border:none !important;"></div>
                                                    <div id="prfBCOPAddPrsnDataEDT" class="tab-pane fade <?php echo $actvTabAddtnlData ?>" style="border:none !important;">
                                                        <?php
                                                        if(addtnlServiceRqrd($appntmntSrvsTypeSysCode)){
                                                           /* ADDITIONAL DATA DATA */
                                                            $formType = $appntmntSrvsTypeSysCode;
                                                            $rvsnTtlAPD = 0;
                                                            //$pkID = isset($_POST['appntmntID']) ? cleanInputData($_POST['appntmntID']) : -1;
                                                            
                                                            $mkReadOnlyAsd = "";
                                                            $mkReadOnlyAsdDsbld = "";
                                                            $trnsStatus = "Incomplete";            

                                                            $dsplyMode = "VIEW";
                                                            if (1 == 1){ //(($canAddPrsn === true && $addOrEdit == "ADD") || ($canEdtPrsn === true && $addOrEdit == "EDIT")) {
                                                                $dsplyMode = $vwtypActn; //$addOrEdit;
                                                            }
                                                            if ($pkID > 0) {
                                                                $result = get_SrvsExtrDataGrps($orgID, $formType);
                                                                $trnsStatus = getAppntmntStatus($pkID);
                                                                
                                                                //echo "trnsStatus appntmnt_data = ".$trnsStatus;
                                                                
                                                                ?>               
                                                                <form class="form-horizontal" id="adtnlSrvsDataForm">
                                                                    <input type="text" id="formTypeInpt1" value="<?php echo $formType; ?>" style="display:none !important">
                                                                    <?php if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="col-md-6">
                                                                                <div class="" style="float:left;">
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-top:5px;" onclick="saveAddtnlSrvsData(<?php echo $pkID; ?>, '<?php echo $formType; ?>');" style="width:100% !important;">
                                                                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        Save Data
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="" style="float:right;">
                                                                                    <?php if($canViewAppntmntDataItms) { ?>
                                                                                    <button type="button" class="btn btn-default" data-toggle="tooltip" style="margin-bottom: 5px; <?php echo $dsplyOthrElmnts; ?>" data-placement="bottom" title="View Appointment Items" onclick="getOneAppntmntDataItemsForm(<?php echo $pkID; ?>, 3, 'ShowDialog', '<?php echo $appntmntNo; ?>', '<?php echo $trnsStatus; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                                       <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                    <?php } if((getAppntmntBillAppntmntVisit($pkID) == "1" || getAppntmntBillAppntmntVisit($pkID) == "Y") && $canGenSalesInvoice) { ?>
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px; <?php echo $dsplyOthrElmnts; ?>" onclick="getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Sales Invoice', 'NO', 'SALES',-1, <?php echo $pkID; ?>);" style="">
                                                                                        <img src="cmn_images/sale.jpg" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        Invoice
                                                                                    </button>
                                                                                     <?php } ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php } ?>
                                                                    <?php
                                                                    while ($row = loc_db_fetch_array($result)) {
                                                                        //echo $trnsStatus;

                                                                        if($vwtypActn == "VIEW" || $trnsStatus == "Completed" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled"){
                                                                            $mkReadOnlyAsd = "readonly=\"readonly\"";
                                                                            $mkReadOnlyAsdDsbld = "disabled=\"true\"";
                                                                        }                          

                                                                        ?>
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <fieldset class="basic_person_fs4">
                                                                                    <legend class="basic_person_lg"><?php echo $row[0]; ?></legend>
                                                                                    <?php
                                                                                    $result1 = get_SrvsExtrDataGrpCols($row[0], $orgID, $formType);
                                                                                    $cntr1 = 0;
                                                                                    $gcntr1 = 0;
                                                                                    $cntr1Ttl = loc_db_num_rows($result1);
                                                                                    while ($row1 = loc_db_fetch_array($result1)) {
                                                                                        /* POSSIBLE FIELDS
                                                                                         * label
                                                                                         * textbox (for now only this)
                                                                                         * textarea (for now only this)
                                                                                         * readonly textbox with button
                                                                                         * readonly textbox with date
                                                                                         * textbox with number validation
                                                                                         */
                                                                                        if ($row1[7] == "Tabular") {
                                                                                            $vrsFieldIDs = "";
                                                                                            for ($i = 0; $i < $row1[9]; $i++) {
                                                                                                if ($i == $row1[9] - 1) {
                                                                                                    $vrsFieldIDs .= "srvsExtrTblrDtCol_" . $i;
                                                                                                } else {
                                                                                                    $vrsFieldIDs .= "srvsExtrTblrDtCol_" . $i . "|";
                                                                                                }
                                                                                            }
                                                                                            $fldVal = get_SrvsExtrData($pkID, $formType, $row1[1], $rvsnTtlAPD);
                                                                                            ?>
                                                                                            <div class="row">
                                                                                                <div  class="col-md-12">
                                                                                                    <?php if(!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getSrvsAddtnlDataForm('myFormsModalH', 'myFormsModalHBody', 'myFormsModalHTitle', 'addtnlSrvsTblrDataForm', '', 'Add/Edit Data', 12, 'ADD', -1, '<?php echo $vrsFieldIDs; ?>', <?php echo $row1[1]; ?>, 'extDataTblCol_<?php echo $row1[1]; ?>', '<?php echo $formType; ?>');">
                                                                                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                        Add Data
                                                                                                    </button>
                                                                                                    <?php } ?>
                                                                                                    <input class="form-control" id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" type = "hidden" placeholder="" value="<?php echo $fldVal; ?>"/>
                                                                                                    <table id="extDataTblCol_<?php echo $row1[1]; ?>" class="table table-striped table-bordered table-responsive extPrsnDataTblEDT"  cellspacing="0" width="100%" style="width:100%;">
                                                                                                        <thead><th>&nbsp;&nbsp;...</th>
                                                                                                        <?php
                                                                                                        $fieldHdngs = $row1[11];
                                                                                                        $arry1 = explode(",", $fieldHdngs);
                                                                                                        $cntr = count($arry1);
                                                                                                        for ($i = 0; $i < $row1[9]; $i++) {
                                                                                                            if ($i <= $cntr - 1) {
                                                                                                                ?>
                                                                                                                <th><?php echo $arry1[$i]; ?></th>
                                                                                                                <?php
                                                                                                            } else {
                                                                                                                ?>
                                                                                                                <th>&nbsp;</th>
                                                                                                                <?php
                                                                                                            }
                                                                                                        }
                                                                                                        ?>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                            <?php
                                                                                                            $arry3 = explode("|", $fldVal);
                                                                                                            $cntr3 = count($arry3);
                                                                                                            $maxsze = (int) 320 / $row1[9];
                                                                                                            if ($maxsze > 100 || $maxsze < 80) {
                                                                                                                $maxsze = 100;
                                                                                                            }
                                                                                                            for ($j = 0; $j < $cntr3; $j++) {
                                                                                                                if (trim(str_replace("~", "", $arry3[$j])) == "") {
                                                                                                                    continue;
                                                                                                                }
                                                                                                                ?>
                                                                                                                <tr id="srvsExtrTblrDtCol_<?php echo $row1[1]; ?>_Row<?php echo $j; ?>">
                                                                                                                    <td>
                                                                                                                        <?php if(!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                                                                                        <button type="button" class="btn btn-default btn-sm" onclick="getSrvsAddtnlDataForm('myFormsModalH', 'myFormsModalHBody', 'myFormsModalHTitle', 'addtnlSrvsTblrDataForm', 'srvsExtrTblrDtCol_<?php echo $row1[1]; ?>_Row<?php echo $j; ?>', 'Add/Edit Data', 12, 'EDIT', <?php echo $pkID; ?>, '<?php echo $vrsFieldIDs; ?>', <?php echo $row1[1]; ?>, 'extDataTblCol_<?php echo $row1[1]; ?>', '<?php echo $formType; ?>');" style="padding:2px !important;">
                                                                                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                                        </button>
                                                                                                                        <?php } ?>
                                                                                                                    </td>
                                                                                                                    <?php
                                                                                                                    $arry2 = explode("~", $arry3[$j]);
                                                                                                                    $cntr2 = count($arry2);
                                                                                                                    for ($i = 0; $i < $row1[9]; $i++) {
                                                                                                                        if ($i <= $cntr2 - 1) {
                                                                                                                            ?>
                                                                                                                            <td><?php echo $arry2[$i]; ?></td>
                                                                                                                        <?php } else { ?>
                                                                                                                            <td>&nbsp;</td>
                                                                                                                            <?php
                                                                                                                        }
                                                                                                                    }
                                                                                                                    ?>
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
                                                                                            if ($gcntr1 == 0) {
                                                                                                $gcntr1 += 1;
                                                                                            }
                                                                                            if (($cntr1 % 2) == 0) {
                                                                                                ?> 
                                                                                                <div class="row"> 
                                                                                                    <?php
                                                                                                }
                                                                                                ?>
                                                                                                <div class="col-md-6"> 
                                                                                                    <div class="form-group form-group-sm"> 
                                                                                                        <?php 
                                                                                                        $prsnDValPulld = get_SrvsExtrData($pkID, $formType, $row1[1], $rvsnTtlAPD);

                                                                                                        ?>
                                                                                                        <label class="control-label col-md-4"><?php echo $row1[2]; ?>:</label>
                                                                                                        <div  class="col-md-8">
                                                                                                            <?php
                                                                                                            //$prsnDValPulld = "";
                                                                                                            //$prsnDValPulld = get_SrvsExtrData($pkID, $formType, $row1[1], $rvsnTtlAPD);
                                                                                                            if ($row1[4] == "Date") {
                                                                                                                ?>                                                        
                                                                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                                                    <input <?php echo $mkReadOnlyAsd; ?> class="form-control" size="16" type="text" id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" value="<?php echo $prsnDValPulld; ?>" readonly="">
                                                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                                </div>
                                                                                                                <?php
                                                                                                            } else if ($row1[4] == "Number") {
                                                                                                                ?>
                                                                                                                <input <?php echo $mkReadOnlyAsd; ?> class="form-control" id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" type = "text" placeholder="" value="<?php echo $prsnDValPulld; ?>"/>
                                                                                                                <?php
                                                                                                            } else {
                                                                                                                if ($row1[3] == "") {
                                                                                                                    if ($row1[6] < 200) {
                                                                                                                        ?>
                                                                                                                        <input <?php echo $mkReadOnlyAsd; ?> class="form-control" id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" type = "text" placeholder="" value="<?php echo $prsnDValPulld; ?>"/>
                                                                                                                        <?php
                                                                                                                    } else {
                                                                                                                        ?>
                                                                                                                        <textarea <?php echo $mkReadOnlyAsd; ?> class="form-control" id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" cols="2" placeholder="" rows="2"><?php echo $prsnDValPulld; ?></textarea>
                                                                                                                        <?php
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    if ($row1[6] < 200) {
                                                                                                                        ?>
                                                                                                                        <div class="input-group">
                                                                                                                            <input <?php echo $mkReadOnlyAsd; ?> type="text" class="form-control" aria-label="..." id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" value="<?php echo $prsnDValPulld; ?>">  
                                                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $row1[3]; ?>', '', '', '', 'radio', true, '<?php echo $prsnDValPulld; ?>', 'valueElmntID', 'addtnlSrvsDataCol<?php echo $row1[1]; ?>', 'clear', 1, '');">
                                                                                                                                <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                        <?php
                                                                                                                    } else {
                                                                                                                        ?>
                                                                                                                        <div class="input-group">
                                                                                                                            <textarea <?php echo $mkReadOnlyAsd; ?> class="form-control" id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" cols="2" placeholder="" rows="2"><?php echo $prsnDValPulld; ?></textarea>
                                                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $row1[3]; ?>', '', '', '', 'radio', true, '<?php echo $prsnDValPulld; ?>', 'valueElmntID', 'addtnlSrvsDataCol<?php echo $row1[1]; ?>', 'clear', 1, '');">
                                                                                                                                <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                                                                                                            </label>
                                                                                                                        </div>                                                                    
                                                                                                                        <?php
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                            ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <?php
                                                                                                $cntr1 += 1;
                                                                                                if (($cntr1 % 2) == 0 || $cntr1 == ($cntr1Ttl)) {
                                                                                                    $cntr1 = 0;
                                                                                                    ?>
                                                                                                </div>
                                                                                                <?php
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    if ($gcntr1 == 1) {
                                                                                        $gcntr1 = 0;
                                                                                    }
                                                                                    ?>
                                                                                </fieldset>
                                                                            </div>
                                                                            <?php ?>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                </form>
                                                            <?php 
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php
                        }
                    }

                    //echo $cntent;
                } else {
                    echo restricted();
                }
            } 
            else if ($vwtyp == 2) {//VITALS VS-0001
                
                 $chkIn = isset($_POST['chkIn']) ? cleanInputData1($_POST['chkIn']) : 'N';
                 
                if($chkIn == "Y"){
                    $rwCnt = checkInAppointment($pkID);
                    if ($rwCnt <= 0) {
                        echo "Check-In Failed";
                        exit();
                    } 
                }
                
                $shdDplct = isset($_POST['shdDplct']) ? $_POST['shdDplct'] : '0';
                
                $frmVitalsPatientNm = "";
                $frmVitalsAppntmntNo = "";
                $checkInDate = "";
                $checkOutDate = "";
                $vstId = -1;
                $aptSts = "";
                $result1 = get_AppointmentDataDet($pkID);

                while ($row1 = loc_db_fetch_array($result1)) {
                    $frmVitalsPatientNm = $row1[2];
                    $frmVitalsAppntmntNo = $row1[1];
                    $checkInDate = $row1[21];
                    $checkOutDate = $row1[9];
                    $vstId = $row1[10];
                    $aptSts = $row1[7];
                }
                
                

                $result = get_AppntmntVitalsData($pkID);
                $vitalsID = -1;
                $appntmntStatus = getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "appntmnt_status", $pkID);
           
                $weight = "";
                $height = "";
                $bmi = "";
                $bmi_status = "";
                $bp_systlc = "";
                $bp_diastlc = "";
                $bp_status = "";
                $pulse = "";
                $body_tmp = "";
                $tmp_loc = "";
                $resptn = "";
                $oxgn_satn = "";
                $head_circum = "";
                $waist_circum = "";
                $bowel_actn = "";
                $cmnts = "";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $mkReadOnlyDsbld = "";
                $rqrdFld = "rqrdFld";


                while ($row2 = loc_db_fetch_array($result)) {
                    $vitalsID = $row2[0];
                    $weight = $row2[1];
                    $height = $row2[2];
                    $bmi = $row2[3];
                    $bmi_status = $row2[4];
                    $bp_systlc = $row2[5];
                    $bp_diastlc = $row2[6];
                    $bp_status = $row2[7];
                    $pulse = $row2[8];
                    $body_tmp = $row2[9];
                    $tmp_loc = $row2[10];
                    $resptn = (int)$row2[11] == 0 ? "" : $row2[11];
                    $oxgn_satn = (int)$row2[12] == 0 ? "" : $row2[12];
                    $head_circum = (int)$row2[13] == 0 ? "" : $row2[13];
                    $waist_circum = (int)$row2[14] == 0 ? "" : $row2[14];
                    $bowel_actn = $row2[15];
                    $cmnts = $row2[16]; 
                }
                
                if ($appntmntStatus == "Completed" || $vwtypActn == "VIEW" || $appntmntStatus == "Scheduled" || $appntmntStatus == "Cancelled") {
                    $mkReadOnly = "readonly=\"true\"";
                    $mkRmrkReadOnly = "readonly=\"true\"";
                    $mkReadOnlyDsbld = "disabled=\"true\"";
                    $rqrdFld = "";
                }
                
                ?>
                <form class="form-horizontal" id="appointmentDetForm">
                    <input class="form-control" id="frmAppntmntID" type = "hidden" placeholder="Appointment ID" value="<?php echo $pkID; ?>"/>
                    <input class="form-control" id="frmVitalsPatientNm" type = "hidden" placeholder="Appointment ID" value="<?php echo $frmVitalsPatientNm; ?>"/>
                    <input class="form-control" id="frmVitalsAppntmntNo" type = "hidden" placeholder="Appointment ID" value="<?php echo $frmVitalsAppntmntNo; ?>"/>
                    <input class="form-control" id="frmVitalsID" type = "hidden" placeholder="Vitals ID" value="<?php echo $vitalsID; ?>"/>
                    <div class='row'>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsWeight" class="control-label col-md-6" >Weight (kg):</label>
                                <div  class="col-md-6">
                                    <input type="number" min="0" class="form-control <?php echo $rqrdFld; ?>" id="frmVitalsWeight" onchange="calcBMI()" cols="2" placeholder=""  <?php echo $mkReadOnly; ?> value="<?php echo $weight; ?>"/>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsHeight" class="control-label col-md-6" >Height (m):</label>
                                <div  class="col-md-6">
                                    <input type="number" min="0" class="form-control <?php echo $rqrdFld; ?>" id="frmVitalsHeight" onchange="calcBMI()" cols="2" placeholder=""  <?php echo $mkReadOnly; ?> value="<?php echo $height; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class='row'>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsBMI" class="control-label col-md-6" >Body Mass Index (kg/m2):</label>
                                <div  class="col-md-6">
                                    <input type="number" class="form-control" id="frmVitalsBMI" placeholder=""  value="<?php echo $bmi; ?>" readonly/>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsBMIStatus" class="control-label col-md-6" >BMI Status:</label>
                                <div  class="col-md-6">
                                    <input type="text" class="form-control" id="frmVitalsBMIStatus" cols="2" placeholder=""  value="<?php echo $bmi_status; ?>" readonly/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsBPSystolic" class="control-label col-md-6" >BP Systolic:</label>
                                <div  class="col-md-6">
                                    <input type="number" min="1" onchange="computeBPStatus();" class="form-control <?php echo $rqrdFld; ?>" id="frmVitalsBPSystolic" cols="2" placeholder=""  <?php echo $mkReadOnly; ?> value="<?php echo $bp_systlc; ?>"/>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsBPDiastolic" class="control-label col-md-6" >BP Diastolic:</label>
                                <div  class="col-md-6">
                                    <input type="number" min="1" onchange="computeBPStatus();" class="form-control <?php echo $rqrdFld; ?>" id="frmVitalsBPDiastolic" cols="2" placeholder=""  <?php echo $mkReadOnly; ?> value="<?php echo $bp_diastlc; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class='row'>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsPBStatus" class="control-label col-md-6" >BP Status:</label>
                                <div  class="col-md-6">
                                    <input type="text" class="form-control" id="frmVitalsPBStatus" placeholder=""  value="<?php echo $bp_status; ?>" readonly/>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsPulse" class="control-label col-md-6" >Pulse (bpm):</label>
                                <div  class="col-md-6">
                                    <input type="number" min="0" class="form-control <?php echo $rqrdFld; ?>" id="frmVitalsPulse" cols="2" placeholder=""  <?php echo $mkReadOnly; ?> value="<?php echo $pulse; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class='row'>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsBodyTemp" class="control-label col-md-6" >Body Temperature (°C):</label>
                                <div  class="col-md-6">
                                    <input type="number" min="0" class="form-control <?php echo $rqrdFld; ?>" id="frmVitalsBodyTemp" cols="2" placeholder=""  <?php echo $mkReadOnly; ?> value="<?php echo $body_tmp; ?>"/>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsTempLoc" class="control-label col-md-6" >Temperature Location:</label>
                                <div  class="col-md-6">
                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control <?php echo $rqrdFld; ?>" id="frmVitalsTempLoc" onChange="">
                                        <option value="" disabled selected>Please Select--</option>
                                        <?php
                                        $sltdGrp = "";
                                        $sltdInd = "";
                                        if ($tmp_loc == "Forehead") {
                                            $sltdGrp = "selected";
                                        } else if ($tmp_loc == "Armpit") {
                                            $sltdInd = "selected";
                                        }
                                        ?>
                                        <option value="Forehead" <?php echo $sltdGrp; ?>>Forehead</option>
                                        <option value="Armpit" <?php echo $sltdInd; ?>>Armpit</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsRsprtn" class="control-label col-md-6" >Respiration (bpm):</label>
                                <div  class="col-md-6">
                                    <input type="number" min="0" class="form-control" id="frmVitalsRsprtn" cols="2" placeholder=""  <?php echo $mkReadOnly; ?> value="<?php echo $resptn; ?>"/>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsOxygenStrtn" class="control-label col-md-6" >Oxygen Saturation:</label>
                                <div  class="col-md-6">
                                    <input type="number" min="0" class="form-control" id="frmVitalsOxygenStrtn" cols="2" placeholder=""  <?php echo $mkReadOnly; ?> value="<?php echo $oxgn_satn; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsHeadCircm" class="control-label col-md-6" >Head Circumference:</label>
                                <div  class="col-md-6">
                                    <input type="number" min="0" class="form-control" id="frmVitalsHeadCircm" cols="2" placeholder=""  <?php echo $mkReadOnly; ?> value="<?php echo $head_circum; ?>"/>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-6">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsWaistCircm" class="control-label col-md-6" >Waist Circumference:</label>
                                <div  class="col-md-6">
                                    <input type="number" min="0" class="form-control" id="frmVitalsWaistCircm" cols="2" placeholder=""  <?php echo $mkReadOnly; ?> value="<?php echo $waist_circum; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsBowelAction" class="control-label col-md-3" >Bowel Action:</label>
                                <div  class="col-md-9">
                                    <textarea class="form-control <?php echo $rqrdFld; ?>" id="frmVitalsBowelAction" cols="2" placeholder="Bowel Action" rows="1" <?php echo $mkReadOnly; ?> ><?php echo $bowel_actn; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmVitalsCmnts" class="control-label col-md-3" >Remarks:</label>
                                <div  class="col-md-9">
                                    <textarea class="form-control" id="frmVitalsCmnts" cols="2" placeholder="Remarks" rows="1" <?php echo $mkReadOnly; ?> ><?php echo $cmnts; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"><!-- ROW BUTTON -->
                        <div class="col-lg-12">
                            <div style="float:right; margin-top: 5px !important;">
                                <?php if ($checkOutDate == "" && $canAddRecsVNA) { ?>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="newAppointment(<?php echo $vstId; ?>,'myFormsModaly','myFormsModalyBody', 'myFormsModalyTitle', 3);" style="width:100% !important;">
                                    <img src="cmn_images/clock.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Appointment
                                </button>
                                <?php } if ($checkInDate == "") { ?>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="checkInVitalsAppointment(<?php echo $pkID; ?>,<?php echo $vwtyp; ?>)" style="width:100% !important;">
                                    <img src="cmn_images/cstmrs1.jpg" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Check-In
                                </button>
                                <?php }  if ($checkInDate !== "" && $checkOutDate == "" && $vitalsID > 0) { ?>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="checkOutVitalsAppointment(<?php echo $pkID; ?>,<?php echo $vwtyp; ?>)" style="width:100% !important;">
                                    <img src="cmn_images/cstmrs1.jpg" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Transfer To Doctor
                                </button>
                                <?php } ?>
                                <?php if ($appntmntStatus == "Completed") { ?>
                                <button type="button" class="btn btn-warning" style="margin-bottom: 5px;" onclick="reopenVitalsAppointment(<?php echo $pkID; ?>,<?php echo $vwtyp; ?>)" style="width:100% !important;">
                                    Re-Open
                                </button>
                                <?php } ?>
                                <?php
                                if (($vwtypActn == "ADD" || $vwtypActn == "EDIT") && $appntmntStatus == "In Progress") {
                                    ?>
                                    <?php if ($checkInDate !== "" && $checkOutDate == "") { ?>
                                    <button id="svVitalsBtn" type="button" class="btn btn-primary btn-sm" style="margin-bottom: 5px;" onclick="saveVitals();">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Save</button>
                                    <?php } ?>
                                    <?php
                                }
                                ?>    
                            </div>                                           
                        </div>
                    </div>
                </form>
                <?php
            } 
            else if ($vwtyp == 7){//RADIOLOGY REQUESTS
                
            } 
            else if ($vwtyp == 8){//CONSULTATION-LINKED RECOMMENDED SERVICES
                $canAddRcmddSrvsMain = true;//test_prmssns($dfltPrvldgs[16], $mdlNm);
                $canEdtRcmddSrvsMain = true;//test_prmssns($dfltPrvldgs[17], $mdlNm);
                $canDelRcmddSrvsMain = true;//test_prmssns($dfltPrvldgs[18], $mdlNm);

                $error = "";
                
                $cnsltnID = isset($_POST['cnsltnID']) ? cleanInputData($_POST['cnsltnID']) : -1;
                $appntmntID = isset($_POST['appntmntID']) ? cleanInputData($_POST['appntmntID']) : -1;

                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 1000;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }

                $total = getCreditRcmddSrvsMainsTblTtl($cnsltnID, $srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = getCreditRcmddSrvsMainsTbl($cnsltnID, $srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                
                $trnsStatus = getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "appntmnt_status", $appntmntID);
                
                ?>
                <form id='allRcmddSrvsMainsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--<fieldset class="basic_person_fs5">-->
                    <!--<legend class="basic_person_lg1" style="color: #003245">RECOMMENDED SERVICES</legend>-->               
                    <input class="form-control" id="rcmdSrvsMainForm" type = "hidden" placeholder="ROW ID" value="1"/>                     
                    <div class="row rhoRowMargin" style="margin-bottom:10px;">
                            <div class="col-lg-4" style="padding:0px 1px 0px 15px !important;">     
                                <?php
                                if ($canAddRcmddSrvsMain === true) {
                                ?>
                                    <?php if (!($trnsStatus == "Completed" || /*$vwtypActn == "VIEW" ||*/ $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="viewRecommendedSrvsAppntmntsForm(<?php echo $appntmntID; ?>, 'Add Recommended Service', 'EDIT', <?php echo $cnsltnID; ?>);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Service
                                    </button>
                                        <?php
                                    } 
                                }
                                ?>
                            </div>
                        <div class="col-lg-4" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allRcmddSrvsMainsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllRcmddSrvsMains(event, '', '#rcmddSrvsTbPage', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital&cnsltnID=<?php echo $cnsltnID; ?>&appntmntID=<?php echo $appntmntID; ?>')">
                                <input id="allRcmddSrvsMainsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRcmddSrvsMains('clear', '#rcmddSrvsTbPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital&cnsltnID=<?php echo $cnsltnID; ?>&appntmntID=<?php echo $appntmntID; ?>');">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRcmddSrvsMains('', '#rcmddSrvsTbPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital&cnsltnID=<?php echo $cnsltnID; ?>&appntmntID=<?php echo $appntmntID; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRcmddSrvsMainsSrchIn">
                                <?php
                                $valslctdArry = array("");
                                $srchInsArrys = array("Service Name");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRcmddSrvsMainsDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("");
                                    $dsplySzeArry = array(1000);
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
                    </div>               
                    <div class="row" style="padding:0px 15px 0px 15px !important"> 
                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allRcmddSrvsMainsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Service Name</th>
                                            <th>Comments</th>     
                                            <?php if ($canDelRcmddSrvsMain === true) { ?>
                                                <th style="width: 10px !important; max-width: 10px !important;">&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sbmtdRcmddSrvsMainID = -1;
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($sbmtdRcmddSrvsMainID <= 0 && $cntr <= 0) {
                                                $sbmtdRcmddSrvsMainID = $row[0];
                                            }

                                            $cntr += 1;
                                            ?>
                                            <tr id="allRcmddSrvsMainsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?>
                                                    <input class="form-control" id="rcmdSrvsMainId" type = "hidden" placeholder="ROW ID" value="<?php echo $row[0]; ?>"/>  
                                                    <input type="hidden" class="form-control" aria-label="..." id="allRcmddSrvsMainsRow<?php echo $cntr; ?>_RcmddSrvsMainID" value="<?php echo $row[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="allRcmddSrvsMainsRow<?php echo $cntr; ?>_RcmddSrvsMainApptmntID" value="<?php echo $row[5]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="allRcmddSrvsMainsRow<?php echo $cntr; ?>_RcmddSrvsMainSysCode" value="<?php echo $row[3]; ?>">                                                
                                                </td>
                                                <td class="lovtd">
                                                    <?php echo $row[2]; ?>
                                                </td>
                                                    <?php if ($canDelRcmddSrvsMain === true) { ?>
                                                    <td class="lovtd">
                                                        <?php if (!($trnsStatus == "Completed" /*|| $vwtypActn == "VIEW"*/ || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="deleteRcmddSrvsMain('allRcmddSrvsMainsRow_<?php echo $cntr; ?>')" data-toggle="tooltip" data-placement="bottom" title="Delete Service">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                         <?php } ?>    
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
                        <div  class="col-md-9" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:5px !important;">
                                <div class="" id="allRcmddSrvsMainsDetailInfo">
                                    <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;">                                              
                                        <div class="row" id="allRcmddSrvsMainsHdrInfo" style="padding:0px 15px 0px 15px !important">


                                        </div>     
                                    </fieldset>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            }
            else if ($vwtyp == 100) {
                //DOSAGE FORM
                $doseQty = isset($_POST['doseQty']) ? cleanInputData($_POST['doseQty']) : '';
                $doseQtyUOM = isset($_POST['doseQtyUOM']) ? cleanInputData($_POST['doseQtyUOM']) : 'tablet(s)';
                $frqncyNo = isset($_POST['frqncyNo']) ? cleanInputData($_POST['frqncyNo']) : '';
                $frqncyUOM = isset($_POST['frqncyUOM']) ? cleanInputData($_POST['frqncyUOM']) : 'day';
                $drtnNo = isset($_POST['drtnNo']) ? cleanInputData($_POST['drtnNo']) : '';
                $drtnUOM = isset($_POST['drtnUOM']) ? cleanInputData($_POST['drtnUOM']) : 'day(s)';
                $rowID = isset($_POST['rowPrfxNm']) ? cleanInputData($_POST['rowPrfxNm']) : '';
                //var_dump($rowID);
                ?>
                <form class="form-horizontal" id="dosageForm">
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="col-md-4">
                                <label for="doseQty" class="control-label" >Dose Quantity:</label>
                            </div>
                            <div class="col-md-2" style="padding-left:0px !important;">
                                <input type="number" class="form-control rqrdFld" aria-label="..." id="doseQty" value="<?php echo $doseQty; ?>" style="width:100% !important;">
                            </div>
                            <div class="col-md-6" style="padding-left: 0px !important; padding-right: 0px !important;">
                                <div class="input-group" style="width:100% !important;">
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="doseQtyUOM" style="width:100% !important;border-radius:5px !important;">
                <?php
                $valslctdArry = array("", "", "");
                $srchInsArrys = array("tablet(s)", "millilitres(ml)", "capsule(s)");

                for ($z = 0; $z < count($srchInsArrys); $z++) {
                    if ($doseQtyUOM == $srchInsArrys[$z]) {
                        $valslctdArry[$z] = "selected";
                    }
                    ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="col-md-4">
                                <label for="frqncyNo" class="control-label" >Frequency:</label>
                            </div>
                            <div class="col-md-2" style="padding-left:0px !important;">
                                <input type="number" class="form-control rqrdFld" aria-label="..." id="frqncyNo" value="<?php echo $frqncyNo; ?>" style="width:100% !important;">
                            </div>
                            <div class="col-md-6" style="padding-left: 0px !important; padding-right: 0px !important;">
                                <div class="input-group" style="width:100% !important;">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="" style="border-radius:5px !important;height:30px !important;">
                                        &nbsp;times a&nbsp;
                                    </label>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="frqncyUOM" style="width:100% !important;border-radius:5px !important;">
                <?php
                $valslctdArry = array("", "", "");
                $srchInsArrys = array("day", "week", "month");

                for ($z = 0; $z < count($srchInsArrys); $z++) {
                    if ($frqncyUOM == $srchInsArrys[$z]) {
                        $valslctdArry[$z] = "selected";
                    }
                    ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="col-md-4">
                                <label for="drtnNo" class="control-label" >Duration:</label>
                            </div>
                            <div class="col-md-2" style="padding-left:0px !important;">
                                <input type="number" class="form-control rqrdFld" aria-label="..." id="drtnNo" value="<?php echo $drtnNo; ?>" style="width:100% !important;">
                            </div>
                            <div class="col-md-6" style="padding-left: 0px !important; padding-right: 0px !important;">
                                <div class="input-group" style="width:100% !important;">
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="drtnUOM" style="width:100% !important;border-radius:5px !important;">
                <?php
                $valslctdArry = array("", "", "");
                $srchInsArrys = array("day(s)", "week(s)", "month(s)");

                for ($z = 0; $z < count($srchInsArrys); $z++) {
                    if ($drtnUOM == $srchInsArrys[$z]) {
                        $valslctdArry[$z] = "selected";
                    }
                    ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"><!-- ROW BUTTON -->
                        <div class="col-lg-12">
                            <div style="float:right; margin-top: 5px !important;">
                                <button id="svCreditItmBtn" type="button" class="btn btn-primary btn-sm" onclick="sendDosage('<?php echo $rowID; ?>');"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">OK</button>                                                                                    
                            </div>                                           
                        </div>
                    </div>
                </form>

                                        <?php
                                    }
            else if ($vwtyp == 101) {//RECOMMENDED SERVICES DIALOG
                $appntmntID = isset($_POST['appntmntID']) ? cleanInputData($_POST['appntmntID']) : -1;
                $cnsltnID = isset($_POST['cnsltnID']) ? cleanInputData($_POST['cnsltnID']) : -1;
                                
                $appntmntDate = date("d-M-Y H:i:s");
                $srvsTypeId  = -1;
                $srvsType  = "";
                $prvdrType = "";
                
                $srvsPrvdrPrsnId = -1;
                $srvsPrvdrPrsnNm = "";
                $prvdrGrpId = -1;
                $prvdrGrp = "";
                
                $srvsPrvdrId = -1;
                $srvsPrvdr = "";
                $appntmntStatus = "Open";
                $appntmntCmnts = "";
                $appntmntNo = "";
                
                
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $mkReadOnlyDsbld = "";
                
                ?>
                <form class="form-horizontal" id="rcmddSrvsDialogForm">
                    <input class="form-control" id="frmSrcAppntmntID" type = "hidden" placeholder="Appointment ID" value="<?php echo $appntmntID; ?>"/>
                    <div class='row' style="display:none !important;">
                        <div  class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmRcmddSrvsAppntmntDate" class="control-label col-md-4">Date:</label>
                                <div  class="col-md-8">
                                    <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd hh:ii:ss">
                                        <input class="form-control" size="16" type="text" id="frmRcmddSrvsAppntmntDate" value="<?php echo $appntmntDate; ?>" readonly="">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmRcmddSrvsSrvsType" class="control-label col-md-4">Service Type:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="..." id="frmRcmddSrvsSrvsType" value="<?php echo $srvsType; ?>" readonly>
                                        <input type="hidden" id="frmRcmddSrvsSrvsTypeId" value="<?php echo $srvsTypeId; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Recommended Services', '', '', '', 'radio', true, '', 'frmRcmddSrvsSrvsTypeId', 'frmRcmddSrvsSrvsType', 'clear', 1, '', function(){
                                            $('#frmSrvsPrvdr').val('');
                                            $('#frmSrvsPrvdrId').val('-1');
                                        });">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmRcmddSrvsAppntmntCmnts" class="control-label col-md-4" >Comments:</label>
                                <div  class="col-md-8">
                                    <textarea class="form-control" id="frmRcmddSrvsAppntmntCmnts" cols="2" placeholder="Remarks" rows="4" <?php echo $mkReadOnly; ?> ><?php echo $appntmntCmnts; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"><!-- ROW BUTTON -->
                        <div class="col-lg-12">
                            <div style="float:right; margin-top: 5px !important;">
                                <?php
                                if ($appntmntID > 0 && $cnsltnID > 0){
                                    ?>
                                    <button id="svCreditItmBtn" type="button" class="btn btn-primary btn-sm" onclick="saveRcmnddSrvsAppointment(<?php echo $appntmntID; ?>, <?php echo $cnsltnID ?>);"><img src="cmn_images/initiate.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Schedule</button>
                                    <?php
                                }
                                ?>                                                                                
                            </div>                                           
                        </div>
                    </div>
                </form>
                <?php
            }
            

        }
    }
}
?>
<script type="text/javascript">
$(document).ready(function(){
	$('a[data-toggle="tabajxrptdet"]').on('show.bs.tab', function(e) {
		localStorage.setItem('activeTab', $(e.target).attr('href'));
                localStorage.setItem('activeTabRhoData', $(e.target).attr('data-rhodata'));
	});
	var activeTab = localStorage.getItem('activeTab');
        var activeTabRhoData = localStorage.getItem('activeTabRhoData');
	if(activeTab){
		//$('#appntmntDataTabs a[href="' + activeTab + '"]').tab('show');
                $(activeTab + 'tab').tab('show');
                var linkArgs = activeTabRhoData;
                
                if (activeTab.indexOf('cnsltnMainTbPage') >= 0) {
                    $('#cnsltnMainTbPage').removeClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (activeTab.indexOf('vitalsTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').removeClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (activeTab.indexOf('inHouseAdmsnTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').removeClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (activeTab.indexOf('medicationTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').removeClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (activeTab.indexOf('invstgtnTbPage') >= 0) {
                    //alert('invstgtnTbPage');
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').removeClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (activeTab.indexOf('rcmddSrvsTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').removeClass('hideNotice');
                    var rcSvId = typeof $("#rcmdSrvsMainForm").val() === 'undefined' ? -1 : $("#rcmdSrvsMainForm").val();

                    if(rcSvId <= 0){
                        openATab(activeTab, linkArgs);
                    }
                } else if (activeTab.indexOf('prfBCOPAddPrsnDataEDT') >= 0) {
                    var srcTyp = typeof $("#formTypeInpt").val() === 'undefined' ? '' : $("#formTypeInpt").val();

                    if(srcTyp === ''){
                        openATab(activeTab, linkArgs);
                    } 
                    //openATab(targ, linkArgs);
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                    $('#prfBCOPAddPrsnDataEDTPage').removeClass('hideNotice');
                }
	}
        
});
</script>