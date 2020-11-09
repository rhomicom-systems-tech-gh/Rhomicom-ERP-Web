<?php
/* 8  "Add Visits/Appointments", "Edit Visits/Appointments", "Delete Visits/Appointments", 
 26  "Close Visit",
32 "Cancel Appointment" */
$canViewVisitAD = test_prmssns($dfltPrvldgs[2], $mdlNm);
$canDelVisitAD = test_prmssns($dfltPrvldgs[13], $mdlNm);

$canCloseVisit = test_prmssns($dfltPrvldgs[26], $mdlNm);
$canCancelAppntmnt = test_prmssns($dfltPrvldgs[32], $mdlNm);
$canExportRecs = test_prmssns($dfltPrvldgs[0], $mdlNm);
$canImportRecs = test_prmssns($dfltPrvldgs[0], $mdlNm);

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
$crdtTypeSrchIn = isset($_POST['crdtTypeSrchIn']) ? cleanInputData($_POST['crdtTypeSrchIn']) : "All Visit Types";



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
        if ($qstr == "CLOSE-VISIT") {
            $rowCnt = closeHospVisit($PKeyID);
            if ($rowCnt > 0) {
                echo "Visit Closed Successful";
            } else {
                echo "Visit Closure Failed";
            }
            exit();
        }
        else if ($qstr == "DELETE") {
            if ($actyp == 1) {
                $recInUse = isVst_id_InActiveUse($PKeyID);
                if ($recInUse) {
                    echo "SORRY! Record is in use";
                    exit();
                } else {
                    $rowCnt = deleteVisit($PKeyID);
                if ($rowCnt > 0) {
                    echo "Record Deleted Successfully";
                  } else {
                    echo "Failed to Delete Record";
                  }
                  exit();
                }  
            } else if ($actyp == 2) {
                $recInUse = isAppntmnt_id_InActiveUse($PKeyID);
                if ($recInUse) {
                    echo "SORRY! Record is in use";
                    exit();
                } else {
                    $rowCnt = deleteAppntmnt($PKeyID);
                if ($rowCnt > 0) {
                    echo "Record Deleted Successfully";
                  } else {
                    echo "Failed to Delete Record";
                  }
                  exit();
                }
                
            } else if ($actyp == 3){
                $rowCnt = deleteAppntmntDataItems($PKeyID);
                if ($rowCnt > 0) {
                    echo "Line Deleted Successfully";
                } else {
                    echo "Failed to Delete Line";
                }
                exit();
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {//Visit
                //var_dump($_POST);
                global $usrID;
                $dateStr = getDB_Date_time();
                $created_by = $usrID;
                $last_update_by = $usrID;
                $creation_date = $dateStr;
                $last_update_date = $dateStr;
                
                $recCntInst = 0;
                $recCntUpdt = 0;
                
                $vst_id = isset($_POST['vstId']) ? (int)cleanInputData($_POST['vstId']) : -1;
                $prsn_id = isset($_POST['prsnId']) ? (int)cleanInputData($_POST['prsnId']) : -1;
                $vst_date = isset($_POST['vstDate']) ? cleanInputData($_POST['vstDate']) : '';
                $cmnts = isset($_POST['cmnts']) ? cleanInputData($_POST['cmnts']) : '';
                $vst_option = isset($_POST['vstOption']) ? cleanInputData($_POST['vstOption']) : 'Hospital Visit';
                $bill_this_visit = isset($_POST['billThisVisit']) ? cleanInputData($_POST['billThisVisit']) : 'Y';
                $branch_id = isset($_POST['branchId']) ? (int)cleanInputData($_POST['branchId']) : '';
                //$trnsctn_no = isset($_POST['slctdVisit']) ? cleanInputData($_POST['slctdVisit']) : '';
                
                if ($vst_date != "") {
                    $vst_date = cnvrtDMYTmToYMDTm($vst_date);
                } 

                if($prsn_id == -1 || $vst_date == "" || $branch_id == -1){    
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . 'Please complete all required field before saving!<br/></div>';
                    exit();
                } else {
                    if ($vst_id > 0) {
                         $recCntUpdt = updateVisit($vst_id, $prsn_id, $vst_date, $cmnts, $created_by, $creation_date, $last_update_by, $last_update_date, $vst_option, $bill_this_visit, $branch_id);
                        
                        if($recCntUpdt > 0){
                            echo json_encode(array("vstId" => $vst_id, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                        } else {
                            echo '<span style="color:red;font-weight:bold !important;">Saving Failed!<br/></span>';
                        }
                    } else {
                        $vst_id = (int)getVst_id();
                        $recCntInst = insertVisit($vst_id, $prsn_id, $vst_date, $cmnts, $created_by, $creation_date, $last_update_by, $last_update_date, $vst_option, $bill_this_visit, $branch_id);
                    
                         if($recCntInst > 0){
                            echo json_encode(array("vstId" => $vst_id, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                        } else {
                            echo '<span style="color:red;font-weight:bold !important;">Saving Failed!<br/></span>';
                        }
                    }
                    exit();
                }
                
            } 
            else if($actyp == 2){//Appointment Creation
                
                global $usrID;
                $dateStr = getDB_Date_time();
                $created_by = $usrID;
                $last_update_by = $usrID;
                $creation_date = $dateStr;
                $last_update_date = $dateStr;
                
                $recCntInst = 0;
                $recCntUpdt = 0;
                  
                $vst_id = isset($_POST['vstId']) ? (int)cleanInputData($_POST['vstId']) : -1;
                $appntmnt_id = isset($_POST['frmAppntmntID']) ? (int)cleanInputData($_POST['frmAppntmntID']) : -1;
                $appntmnt_date = isset($_POST['frmAppntmntDate']) ? cleanInputData($_POST['frmAppntmntDate']) : date("d-M-Y H:i:s");
                $srvs_type_id = isset($_POST['frmSrvsTypeId']) ? cleanInputData($_POST['frmSrvsTypeId']) : -1;
                $prvdr_type = isset($_POST['frmPrvdrType']) ? cleanInputData($_POST['frmPrvdrType']) : 'G';
                $cmnts = isset($_POST['frmAppntmntCmnts']) ? cleanInputData($_POST['frmAppntmntCmnts']) : '';
                $lnkdSrvsTypeCode = isset($_POST['lnkdSrvsTypeCode']) ? cleanInputData($_POST['lnkdSrvsTypeCode']) : '';
                
                $cnsltnAppntmnt_id = isset($_POST['cnsltnAppntmnt_id']) ? (int)cleanInputData($_POST['cnsltnAppntmnt_id']) : -1;
                
                $docAdmsnCheckInDate = isset($_POST['docAdmsnCheckInDate']) ? cleanInputData($_POST['docAdmsnCheckInDate']) : '';
                $docAdmsnCheckInNoOfDays = isset($_POST['docAdmsnCheckInNoOfDays']) ? cleanInputData($_POST['docAdmsnCheckInNoOfDays']) : 1;
                
                $admnChkInDteMsg = $docAdmsnCheckInDate;
                $admnChkInNoOfDysMsg = $docAdmsnCheckInNoOfDays;
                
                $srvsTypeSysCode = getSrvsTypeCodeFromID($srvs_type_id);
                $vitalRcExt = checkExstncOfVitslsForVisit($vst_id);
                
                if($docAdmsnCheckInDate != ""){
                    $docAdmsnCheckInDate = cnvrtDMYToYMD($docAdmsnCheckInDate);
                }
                
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
                    if ($appntmnt_id > 0) {
                         $recCntUpdt = updateAppntmnt($appntmnt_id,$vst_id,$appntmnt_date,$srvs_type_id,$prvdr_type,$srvs_prvdr_prsn_id,$prvdr_grp_id,$cmnts,$created_by,$creation_date,$last_update_by,$last_update_date);
                         
                         $srvsTypeItmId = -1;
                         
                         if($prvdr_type == "G"){
                             $srvsTypeItmId = getPrvdrGrpCostItem($prvdr_grp_id);
                             if($srvsTypeItmId < 0){
                                 $srvsTypeItmId = (int)getGnrlRecNm("hosp.srvs_types", "type_id", "itm_id", $srvs_type_id);
                             }
                             
                         } else {
                            $srvsTypeItmId = (int)getGnrlRecNm("hosp.srvs_types", "type_id", "itm_id", $srvs_type_id);
                         }

                        if($recCntUpdt > 0){
                            deleteAppntmntDataItem($srvsTypeItmId, $appntmnt_id, -1);
                            $ttlQty = 1;
                            $appntmntDataItemsID = getAppntmntDataItemsID();
                            $uomID = "(SELECT inv.get_invitm_uom_id($srvsTypeItmId))";
                            insertAppntmntDataItems($appntmntDataItemsID, $appntmnt_id, $srvsTypeItmId, $ttlQty, "", $usrID, $dateStr, $uomID, -1);
         
                            echo json_encode(array("frmAppntmntID" => $appntmnt_id, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                        } else {
                            echo '<span style="color:red;font-weight:bold !important;">Saving Failed!<br/></span>';
                        }
                    } else {
                        
                        $cnsltnID = $frmCnsltnID; //getAppntmntCnsltnID($appntmnt_id);
                        
                        $srvsTypeItmId = -1;
                         
                         if($prvdr_type == "G"){
                             $srvsTypeItmId = getPrvdrGrpCostItem($prvdr_grp_id);
                             if($srvsTypeItmId < 0){
                                 $srvsTypeItmId = (int)getGnrlRecNm("hosp.srvs_types", "type_id", "itm_id", $srvs_type_id);
                             }                             
                         } else {
                            $srvsTypeItmId = (int)getGnrlRecNm("hosp.srvs_types", "type_id", "itm_id", $srvs_type_id);
                         }
                        
                        //$srvsTypeItmId = (int)getGnrlRecNm("hosp.srvs_types", "type_id", "itm_id", $srvs_type_id);
                        
                        if($lnkdSrvsTypeCode != ''){
                            if($lnkdSrvsTypeCode == "LI-0001"){//investigation
                                //CHECK INVESTIGATION TYPES
                                //$cnsltnID = $frmCnsltnID; //getAppntmntCnsltnID($appntmnt_id);

                                $labCnt = getCnsltnLabOrRadialogyCnt($cnsltnID, "Lab");
                                $rdlgyCnt = getCnsltnLabOrRadialogyCnt($cnsltnID, "Radiology");
                                $recCntInstLB = 0;
                                $recCntInstRD = 0;
                                if($labCnt > 0){
                                    
                                    if($cnsltnID > 0){
                                        $rcExts = cnsltnLabInvstgnExist($cnsltnID, $cnsltnAppntmnt_id);
                                        if($rcExts){
                                            $appntmnt_id = getAppntmnt_id();
                                            $recCntInstLB = insertAppntmnt($appntmnt_id,$vst_id,$appntmnt_date,$srvs_type_id,$prvdr_type,$srvs_prvdr_prsn_id,$prvdr_grp_id,$cmnts,$created_by,$creation_date,$last_update_by,$last_update_date);

                                            execUpdtInsSQL("UPDATE hosp.invstgtn x SET dest_appntmnt_id = $appntmnt_id WHERE cnsltn_id = $cnsltnID AND dest_appntmnt_id = $cnsltnAppntmnt_id AND invstgtn_list_id IN "
                                                        . "( SELECT invstgtn_list_id FROM hosp.invstgtn_list WHERE x.invstgtn_list_id = invstgtn_list_id AND invstgtn_type = 'Lab')");
                                           
                                            //CHECK AND CREATE APPOINTMENT ITEMS
                                            //$srvsTypeItmId = (int)getGnrlRecNm("hosp.srvs_types", "type_id", "itm_id", $srvs_type_id);

                                            if($srvsTypeItmId > 0 && $recCntInstLB > 0){
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
                                            $recCntInstRD = insertAppntmnt($appntmnt_idRD,$vst_id,$appntmnt_date,$rdlgy_srvs_type_id,$prvdr_type,$srvs_prvdr_prsn_id,$rdlgy_prvdr_grp_id,$cmnts,$created_by,$creation_date,$last_update_by,$last_update_date);

                                            execUpdtInsSQL("UPDATE hosp.invstgtn x SET dest_appntmnt_id = $appntmnt_idRD WHERE cnsltn_id = $cnsltnID AND dest_appntmnt_id = $cnsltnAppntmnt_id AND invstgtn_list_id IN "
                                                . "( SELECT invstgtn_list_id FROM hosp.invstgtn_list WHERE x.invstgtn_list_id = invstgtn_list_id AND invstgtn_type = 'Radiology')");
                                            
                                            //CHECK AND CREATE APPOINTMENT ITEMS FOR RADIOLOGY
                                            $srvsTypeItmIdRD = (int)getGnrlRecNm("hosp.srvs_types", "type_id", "itm_id", $rdlgy_srvs_type_id);

                                            if($srvsTypeItmIdRD > 0 && $recCntInstRD > 0){
                                                $ttlQty = 1;
                                                $appntmntDataItemsIDRD = getAppntmntDataItemsID();
                                                $uomIDRD = "(SELECT inv.get_invitm_uom_id($srvsTypeItmIdRD))";
                                                insertAppntmntDataItems($appntmntDataItemsIDRD, $appntmnt_idRD, $srvsTypeItmIdRD, $ttlQty, "", $usrID, $dateStr, $uomIDRD, -1);
                                            }
                                            
                                        }
                                    }
                                }

                                $recCntInst = $recCntInstRD + $recCntInstLB;
                                if($recCntInst > 0){
                                   echo json_encode(array("frmAppntmntID" => 1, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                                } else {
                                   echo '<span style="color:red;font-weight:bold !important;">Sorry! Nothing to Schedule!<br/></span>';
                                }
                                exit(); //CONFIRM IF IT SHOULD EXIST
                            } else {
                                if ($cnsltnID > 0){
                                    $rtnNoMsg = "Sorry! Appointment request for Check-In Date '.$admnChkInDteMsg. ' for '.$admnChkInNoOfDysMsg.' Day(s) already sent.!";
                                    if($lnkdSrvsTypeCode == "IA-0001") {
                                        
                                        if($docAdmsnCheckInDate != "" && $docAdmsnCheckInNoOfDays != "") {
                                            $admsn_id = getAdmsn_id();
                                            $ref_check_in_id = -1;
                                            $cnsltn_id = $cnsltnID;
                                            $dest_appntmnt_id = $appntmnt_id;
                                            $facility_type_id = -1;
                                            $room_id = -1;
                                            $checkin_date = $docAdmsnCheckInDate;
                                            $checkout_date = date('Y-m-d', strtotime($checkin_date. ' + '.$docAdmsnCheckInNoOfDays.' days')); 

                                            $rcExts = cnsltnAdmissionExist($cnsltnID, $docAdmsnCheckInDate, $checkout_date);
                                            if(!($rcExts)){
                                                $appntmnt_id = getAppntmnt_id(); //NEW 
                                                $recCntInst = insertAppntmnt($appntmnt_id,$vst_id,$appntmnt_date,$srvs_type_id,$prvdr_type,$srvs_prvdr_prsn_id,$prvdr_grp_id,$cmnts,$created_by,$creation_date,$last_update_by,$last_update_date);

                                                //UPDATE CNSLTATION
                                                execUpdtInsSQL("UPDATE hosp.medcl_cnsltn SET  admission_checkin_date = '$checkin_date', admission_no_of_days =  $docAdmsnCheckInNoOfDays,"
                                                        . "admission_cmnts = '". loc_db_escape_string($cmnts)."', last_update_by = $last_update_by, last_update_date = '$last_update_date' WHERE cnsltn_id = $cnsltnID");

                                                if($recCntInst > 0){
                                                    $dest_appntmnt_id = $appntmnt_id;
                                                    $admsn_rqst_id = getAdmsn_rqst_id();
                                                    createAppntmntAdmsnRequest($admsn_rqst_id, $appntmnt_id, $cmnts, $checkin_date, $docAdmsnCheckInNoOfDays, $created_by, $creation_date, $last_update_by,  $last_update_date);
                                                    //INSERT ADMISSION
                                                    insertInptntAdmsn($admsn_id, $cnsltn_id, $facility_type_id, $room_id, $dest_appntmnt_id, $checkin_date, $checkout_date, $ref_check_in_id, $created_by, $creation_date, $last_update_by, $last_update_date);

                                                    if($srvsTypeItmId > 0){
                                                        $ttlQty = 1;
                                                        $appntmntDataItemsID = getAppntmntDataItemsID();
                                                        $uomID = "(SELECT inv.get_invitm_uom_id($srvsTypeItmId))";
                                                        insertAppntmntDataItems($appntmntDataItemsID, $appntmnt_id, $srvsTypeItmId, $ttlQty, "", $usrID, $dateStr, $uomID, -1);
                                                    }
                                                }
                                            }
                                        }
                                        else {
                                            $rtnNoMsg = "Sorry! Nothing to Schedule!";
                                        }
                                    }
                                    else if($lnkdSrvsTypeCode == "PH-0001"){//pharmacy medication
                                        $rcExts = cnsltnPrescriptionExist($cnsltnID, $cnsltnAppntmnt_id);
                                        if($rcExts){
                                            $appntmnt_id = getAppntmnt_id(); //NEW 
                                            $recCntInst = insertAppntmnt($appntmnt_id,$vst_id,$appntmnt_date,$srvs_type_id,$prvdr_type,$srvs_prvdr_prsn_id,$prvdr_grp_id,$cmnts,$created_by,$creation_date,$last_update_by,$last_update_date);

                                            if($recCntInst > 0){
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
                                        $rtnNoMsg = "Sorry! Nothing to Schedule!"; 
                                    } 
                                }
                                
                                if($srvsTypeItmId > 0 && $recCntInst > 0){
                                    $ttlQty = 1;
                                    $appntmntDataItemsID = getAppntmntDataItemsID();
                                    $uomID = "(SELECT inv.get_invitm_uom_id($srvsTypeItmId))";
                                    insertAppntmntDataItems($appntmntDataItemsID, $appntmnt_id, $srvsTypeItmId, $ttlQty, "", $usrID, $dateStr, $uomID, -1);     
                                }

                                if($recCntInst > 0){
                                    echo json_encode(array("frmAppntmntID" => $appntmnt_id, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                                } else {
                                    echo '<span style="color:red;font-weight:bold !important;">'.$rtnNoMsg.'<br/></span>';
                                }
                                exit();
                            }   
                        } else {
                            $appntmnt_id = getAppntmnt_id(); //NEW 
                            $recCntInst = insertAppntmnt($appntmnt_id,$vst_id,$appntmnt_date,$srvs_type_id,$prvdr_type,$srvs_prvdr_prsn_id,$prvdr_grp_id,$cmnts,$created_by,$creation_date,$last_update_by,$last_update_date);
                        
                            //CREATE VITALS RECORD 
                            if(!($vitalRcExt)){
                                if($srvsTypeSysCode == "MC-0001"){
                                    $nxtAppntmnt_id = getAppntmnt_id();
                                    $vtlsSrvsTypeID = getVitalsSrvsTypeID();
                                    $vtls_mainprvdr_grp_id = getSrvsTypeMainPrvdrGrp('VS-0001');
                                    $recCntInstVtls = insertAppntmnt($nxtAppntmnt_id,$vst_id,$appntmnt_date,$vtlsSrvsTypeID,'G',-1,$vtls_mainprvdr_grp_id,'',$created_by,$creation_date,$last_update_by,$last_update_date); 
                                }
                            }
                            
                            if($srvsTypeItmId > 0 && $recCntInst > 0){
                                $ttlQty = 1;
                                $appntmntDataItemsID = getAppntmntDataItemsID();
                                $uomID = "(SELECT inv.get_invitm_uom_id($srvsTypeItmId))";
                                insertAppntmntDataItems($appntmntDataItemsID, $appntmnt_id, $srvsTypeItmId, $ttlQty, "", $usrID, $dateStr, $uomID, -1);     
                            }
                            
                            if($recCntInst > 0){
                                echo json_encode(array("frmAppntmntID" => $appntmnt_id, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                            } else {
                                echo '<span style="color:red;font-weight:bold !important;">Sorry! Failed to Schedule Appointment!<br/></span>';
                            }
                            exit();
                        }
                    }
                }
                exit();
            } else if ($actyp == 3){//Appointment Data Items             
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
                            if (count($crntRow) == 6) {
                                $appntmntDataItemsID = (int) (cleanInputData1($crntRow[0]));
				$appntmntID = (int) (cleanInputData1($crntRow[1]));
                                $itemID = (int)cleanInputData1($crntRow[2]);
				$qty = (float)cleanInputData1($crntRow[3]);
                                $cmnts = cleanInputData1($crntRow[4]);
                                $uomID = (int)cleanInputData1($crntRow[5]);

                                if ($appntmntDataItemsID > 0) {
                                    $recCntUpdt = $recCntUpdt + updateAppntmntDataItems($appntmntDataItemsID, $appntmntID, $itemID, $qty, $cmnts, $usrID, $dateStr, $uomID, -1);
                                } else {
                                    $appntmntDataItemsID = getAppntmntDataItemsID();
                                    $recCntInst = $recCntInst + insertAppntmntDataItems($appntmntDataItemsID, $appntmntID, $itemID, $qty, $cmnts, $usrID, $dateStr, $uomID, -1);
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
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&mdl=Clinic/Hospital');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Visits & Appointments</span>
				</li>
                               </ul>
                              </div>";

                $total = get_AllVisitsTtl($qStrtDte, $qEndDte, $crdtTypeSrchIn, $statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_AllVisits($qStrtDte, $qEndDte, $crdtTypeSrchIn, $statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='dataAdminForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>  
                    <input class="form-control" id="frmUpdate" type = "hidden" placeholder="formDirty" value="-1"/>
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">VISITS & APPOINTMENTS</legend>
                        <div class="row" style="margin-bottom:1px;">
                            <?php
                            if ($canAddRecsVNA === true) {
                                ?>   
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getHospDetailsForm('myFormsModalLgH', 'myFormsModalBodyLgH', 'myFormsModalTitleLgH', 'Add New Visit', 2, 1, 'ADD', -1, 'indCustTable');">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Visit
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-2";
                                $colClassType2 = "col-lg-4";
                            }
                            ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="dataAdminSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=14&typ=1&pg=2&mdl=Clinic/Hospital')">
                                    <input id="dataAdminPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getHospData('clear', '#allmodules', 'grp=14&typ=1&pg=2&mdl=Clinic/Hospital')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getHospData('', '#allmodules', 'grp=14&typ=1&pg=2&mdl=Clinic/Hospital')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "");
                                        $srchInsArrys = array("Visit Number", "ID/Full Name", "Full Name");
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
                            <div class="<?php echo $colClassType1; ?>">
                                <div class="input-group">                        
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSortBy">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "");
                                        $srchInsArrys = array("Date Added DESC", "Visit Number ASC", "Visit Status ASC");
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
                                            <a href="javascript:getHospData('previous', '#allmodules', 'grp=14&typ=1&pg=2&mdl=Clinic/Hospital');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getHospData('next', '#allmodules', 'grp=14&typ=1&pg=2'&mdl=Clinic/Hospital);" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:2px;padding:1px 15px 2px 15px !important">   
                            <div  class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                <div class="col-md-2" style="float:left;">
                                    <div class="btn-group" style="margin-bottom: 1px;">
                                        <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;" onclick="">
                                            <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Excel
                                        </button>
                                        <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                            <?php if ($canExportRecs === true) { ?> 
                                                <li>
                                                    <a href="javascript:alert(exprtLoanApplctns());">
                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Export Visits
                                                    </a>
                                                </li>
                                            <?php } if ($canImportRecs === true) { ?> 
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
                                <div class="col-md-6" style="float:right !important;">
                                    <div class="col-lg-12" style="margin-bottom: 1px;"><!-- style="padding: 5px 1px 0px 15px !important">-->
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>                                        
                                            <select class="form-control" id="dataAdminStatusSrchIn" onchange="javascript:getHospData('', '#allmodules', 'grp=14&typ=1&pg=2&mdl=Clinic/Hospital');">
                                                <?php
                                                $selectedTxtAS = "";
                                                $selectedTxtOpen = "";
                                                $selectedTxtClosed = "";
                                                if ($statusSrchIn == "All Statuses") {
                                                    $selectedTxtAS = "selected";
                                                } else if ($statusSrchIn == "Open") {
                                                    $selectedTxtOpen = "selected";
                                                } else if ($statusSrchIn == "Closed") {
                                                    $selectedTxtClosed = "selected";
                                                }
                                                ?>
                                                <option <?php echo $selectedTxtAS; ?> value="All Statuses">All Statuses</option>
                                                <option value="Open" <?php echo $selectedTxtOpen; ?>>Open</option>
                                                <option value="Closed" <?php echo $selectedTxtClosed; ?>>Closed</option>
                                            </select>                                    
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                            <select class="form-control" id="dataAdminBranchSrchIn" onchange="javascript:getHospData('', '#allmodules', 'grp=14&typ=1&pg=2&mdl=Clinic/Hospital');">
                                                <option value="All Branches">All Branches</option>
                                                <?php
                                                $brghtStr = "";
                                                $isDynmyc = FALSE;
                                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Sites/Locations New"), $isDynmyc, -1, "", "");
                                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                    $selectedTxt = "";
                                                    if ($titleRow[0] == $prsnBranchID) {
                                                        $selectedTxt = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[1]; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select> 
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                            <select class="form-control" id="dataAdminCrdtTypeSrchIn" onchange="">
                                                <?php
                                                $selectedTxtAS = "";
                                                $selectedTxtATZ = "";
                                                $selectedTxtRJT = "";
                                                $selectedTxtWKN = "";
                                                if ($crdtTypeSrchIn == "All Credit Types") {
                                                    $selectedTxtAS = "selected";
                                                } else if ($crdtTypeSrchIn == "Out-Patient") {
                                                    $selectedTxtATZ = "selected";
                                                } else if ($crdtTypeSrchIn == "In-Patient") {
                                                    $selectedTxtRJT = "selected";
                                                } else if ($crdtTypeSrchIn == "Walk-In") {
                                                    $selectedTxtWKN = "selected";
                                                }
                                                ?>
                                                <option <?php echo $selectedTxtAS; ?> value="All Visit Types">All Visit Types</option>
                                                <option value="Out-Patient" <?php echo $selectedTxtATZ; ?>>Out-Patient</option>
                                                <option value="In-Patient" <?php echo $selectedTxtRJT; ?>>In-Patient</option>
                                                <option value="Walk-In" <?php echo $selectedTxtRJT; ?>>Walk-In</option>
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
                <?php if ($canEdtRecsVNA === true) { ?>
                                                <th>...</th>
                <?php } ?>
                <?php if ($canDelRecsVNA === true) { ?>
                                                <th>...</th>
                <?php } ?>
                                            <th>Visit No.</th>                                        
                                            <th>Patient</th>
                                            <th>Visit Date</th>
                                            <th style="text-align:center !important;">Bill Visit</th>
                                            <th style="text-align:center;">Status</th>
                                            <th style="text-align:right !important;">Total Bill (GHS)</th>
                                            <th style="text-align:right !important;">Total<br/>Payment (GHS)</th>
                                            <th style="text-align:right !important;">Outstanding<br/>Payment (GHS)</th>
                                            <th>...</th>
                                            <!--<th>...</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                <?php
                while ($row = loc_db_fetch_array($result)) {
                     $vstStatusColor = "style='color:red !important;font-weight:bold;text-align:center;'";
                        if($row[7] == "Closed"){
                            $vstStatusColor = "style='color:green !important;font-weight:bold;text-align:center;'";
                        }
                        
                     $ttlVstBillPymnt = "0.00";
                     $ttlVstBill = "0.00";
                     $ttlOustanding = "0.00";
                                        
                    $resultVstAppntmntIDs = getVisitAppntmntInvcIDs($row[0]);
                    
                    while ($rowBP = loc_db_fetch_array($resultVstAppntmntIDs)) {
                        $rsltw = get_One_SalesInvcAmounts($rowBP[0]);
                        if ($rw = loc_db_fetch_array($rsltw)) {
                            $ttlVstBill = $ttlVstBill + (float) $rw[0];
                            $ttlVstBillPymnt = $ttlVstBillPymnt + $rw[1];
                        }
                    }
                    $ttlOustanding = (float)$ttlVstBill -  (float)$ttlVstBillPymnt;
                    $outstndnAmntColor = "color:green;";
                    if($ttlOustanding > 0){
                        $outstndnAmntColor = "color:red;";
                    }
                    
                    $cntr += 1;
                    ?>
                                            <tr id="indCustTableRow<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <?php if ($canEdtRecsVNA === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Visit & Appointment" 
                                                                onclick="getHospDetailsForm('myFormsModalLgH', 'myFormsModalBodyLgH', 'myFormsModalTitleLgH', 'Edit Visit & Appointment', 2, 1, 'EDIT', <?php echo $row[0]; ?>, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                    <?php if ($canDelRecsVNA === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Account" onclick="delVisit(<?php echo $row[0]; ?>)" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[3]; ?></td>
                                                <td class="lovtd" style="text-align:center !important;"><?php echo $row[4]; ?></td>
                                                <td class="lovtd" <?php echo $vstStatusColor; ?>><?php echo $row[7]; ?></td>
                                                <td class="lovtd" style="text-align:right !important; font-weight:bold !important;"><?php echo number_format($ttlVstBill,2); ?></td>
                                                <td class="lovtd" style="text-align:right !important;color:blue; font-weight:bold !important;"><?php echo number_format($ttlVstBillPymnt,2); ?></td>
                                                <td class="lovtd" style="text-align:right !important;font-size:14px !important; font-weight:bold !important;<?php echo $outstndnAmntColor; ?>"><?php echo number_format($ttlOustanding,2); ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Patient Account" 
                                                            onclick="getHospDetailsForm('myFormsModalLgH', 'myFormsModalBodyLgH', 'myFormsModalTitleLgH', 'Edit Visit & Appointment', 2, 1, 'VIEW', <?php echo $row[0]; ?>, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
            else if ($vwtyp == 1) {//VISIT DETAILS
                if ($vwtypActn == "EDIT" || $vwtypActn == "VIEW" || $vwtypActn == "ADD") {
                    /* Read Only */

                    $voidedTrnsHdrID = -1;

                    $rqstatusColor = "red";
                    $mkReadOnly = "";
                    $mkRmrkReadOnly = "";
                    $mkReadOnlyDsbld = "";

                    $sbmtdTrnsHdrID = $pkID;
                    $prsn_id = -1;
                    $prsnName = "";
                    $vst_date = date("d-M-Y H:i:s");
                    $cmnts = "";
                    $trnsStatus = "Open";
                    $vstr_type = "Out-Patient";
                    $vst_option = "Hospital Visit";
                    $vst_end_date = "";
                    $bill_this_visit = "Yes";
                    $branch_id = $prsnBranchID;
                    $branch = $prsnBranch;
                    $trnsctn_no = "";
                    $total_bill = 0.00;
                    $total_pymnt = 0.00;
                    $vstTtlOutsdnPymnt = 0.00;
                    $ttlColor = "";
                    $appntmnt_cnt = 0;
                    
                    $sponsor = "";
                    $cardNo = "";
                    $cardExpiryDate = "";
                    
                    $ttlVstBillPymnt = "0.00";
                    $ttlVstBill = "0.00";
                    
                    $cardExpired = "Yes";
                                        
                    $resultVstAppntmntIDs = getVisitAppntmntInvcIDs($pkID);
                    
                    while ($rowBP = loc_db_fetch_array($resultVstAppntmntIDs)) {
                        $rsltw = get_One_SalesInvcAmounts($rowBP[0]);
                        if ($rw = loc_db_fetch_array($rsltw)) {
                            $ttlVstBill = $ttlVstBill + (float) $rw[0];
                            $ttlVstBillPymnt = $ttlVstBillPymnt + $rw[1];
                        }
                    }

                    $result = get_VisitHdrData($pkID);

                    while ($row = loc_db_fetch_array($result)) {
                        $trnsStatus = $row[7];
                        $total_bill = $ttlVstBill; //$row[5];
                        $total_pymnt = $ttlVstBillPymnt; //$row[6];
                        $vst_end_date = $row[8]; //Conditional display
                        
                       
                        $vstTtlOutsdnPymnt = (float)$total_bill -  (float)$total_pymnt;
                         $ttlColor = "green";
                        if($vstTtlOutsdnPymnt > 0){
                             $ttlColor = "red";
                        }


                        $trnsctn_no = $row[1];
                        $vst_date = $row[3];
                        $branch_id = $row[12];
                        $branch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $branch_id);

                        $cmnts = $row[9];

                        $vstr_type = $row[10];
                        $prsn_id = $row[13];
                        $prsnName = $row[2];
                        $bill_this_visit = $row[4];
                        $appntmnt_cnt = $row[14];
                        $vst_option = $row[15];
                        
                        $sponsor = get_PrsExtrData($prsn_id, "6");
                        //$appntmntDate = $row[3];
                        $cardNo = get_PrsExtrData($prsn_id, "7");
                        $cardExpiryDate = get_PrsExtrData($prsn_id, "7");
                        
                        if($cardExpiryDate != ""){
                            if( date("Y-m-d", strtotime($cardExpiryDate)) < date("Y-m-d")){
                                $cardExpired = "Yes";
                            } else {
                                $cardExpired = "No";
                            }
                        }
                        
                        

                        if ($trnsStatus == "Closed" || $vwtypActn == "VIEW") {
                            $rqstatusColor = "green";
                            $mkReadOnly = "readonly=\"true\"";
                            $mkRmrkReadOnly = "readonly=\"true\"";
                            $mkReadOnlyDsbld = "disabled=\"true\"";
                        }
                    }
                    ?>   
                    <div class="row" style="margin: 0px 0px 10px 0px !important; display:none !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prfBCOPAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                            <!--<button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflBCOPAttchmntsEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>-->
                        </div>
                    </div>
                    <div class="">
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv" style="border:none !important; padding-top:0px !important;"> 
                                    <div class="tab-content">
                                        <div id="prflCMHomeEDT" class="tab-pane fadein active" style="border:none !important;"> 
                                            <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
                                                <div style="padding:0px 1px 0px 15px !important;float:left;">
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                        <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                                    </button>
                                                        <?php if ($pkID > 0 && ($vwtypActn == "EDIT" || $vwtypActn == "ADD")) { ?>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getHospDetailsForm('myFormsModalLgH', 'myFormsModalBodyLgH', 'myFormsModalTitleLgH', 'Edit Visit', 2,  1, 'EDIT', <?php echo $sbmtdTrnsHdrID; ?>, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1');" data-toggle="tooltip" title="Reload Transaction">
                                                            <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <?php } ?>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsTtlBillBtn">
                                                        <span style="font-weight:bold;">Total Bill (GHS): </span><span style="font-weight: bold;color:red;"><?php echo number_format($total_bill,2); ?></span>
                                                    </button>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsTtlPymntBtn">
                                                        <span style="font-weight:bold;">Total Payment (GHS): </span><span style="color:blue;font-weight: bold;"><?php echo number_format($total_pymnt,2) ?></span>
                                                    </button>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsTtlPymntBtn">
                                                        <span style="font-weight:bold;">Total Outstanding (GHS): </span><span style="color:<?php echo $ttlColor; ?>;font-weight: bold;"><?php echo number_format($vstTtlOutsdnPymnt,2) ?></span>
                                                    </button>
                                                    <?php if ($trnsStatus == "Closed") { ?>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsEndDteBtn">
                                                            <span style="font-weight:bold;">End Date: </span><span style="color:<?php echo $ttlColor; ?>;font-weight: bold;"><?php echo $vst_end_date; ?></span>
                                                        </button>
                                                    <?php } ?>
                                                </div>
                                                <div style="padding:0px 1px 0px 1px !important;float:right;">
                                                    <button type="button" class="btn btn-default" style="max-height:30px !important;" onclick="getOneFscDocsForm_Gnrl(<?php echo $pkID; ?>,'VISITS', 140, 'Visits Attached Documents');" data-toggle="tooltip" data-placement="bottom" title = "Visits Attached Documents">
                                                        <img src="cmn_images/adjunto.png"  style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                    <?php if (($trnsStatus == "Open") && $vwtypActn != "VIEW" /* && canPrsnSeeDsbmntBranchDocs($prsnID, $pkID) */) { ?>                                                    
                                                        <button type="button" class="btn btn-primary btn-sm" style="height:30px;" onclick="saveVisit();"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button> 
                                                        <?php
                                                        if ($canCloseVisit && ((float) $appntmnt_cnt > 0 && ((float) $total_bill == 0 || ((float) $total_bill > 0 && ((float) $total_bill == (float) $total_pymnt)) || $bill_this_visit == "No" || ($sponsor != "" && $cardNo != "" && $cardExpired == "No")))) {
                                                            ?>    
                                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="closeVisit(<?php echo $pkID; ?>, <?php echo $vwtyp; ?>);">
                                                                <img src="cmn_images/pay.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Close Visit&nbsp;
                                                            </button> 
                                                            <?php
                                                        }
                                                    } else if (($trnsStatus == "Closed") /* && (test_prmssns($dfltPrvldgs[150], $mdlNm) === true) && canPrsnSeeDsbmntBranchDocs($prsnID, $pkID) */) {
                                                        $reportTitle = "Withdrawal Transaction";
                                                        $reportName = "Teller Transaction Receipt";
                                                        $rptID = getRptID($reportName);
                                                        $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                                                        $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                        $invcID = $sbmtdTrnsHdrID;
                                                        $paramRepsNVals = $prmID1 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                                        $paramStr = urlencode($paramRepsNVals);
                                                        ?>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px; display:none !important;" title="Get Voucher on Thermal Receipt Paper" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');">
                                                            <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                            POS
                                                        </button> 
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;  display:none !important;" title="Get Voucher on A4" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');">
                                                            <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                            A4
                                                        </button>    
                                                            <?php if ($voidedTrnsHdrID <= 0 && $trnsStatus == "Re-Open") { ?>
                                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="reOpenVisit(<?php echo $pgNo; ?>, 1);">
                                                                <img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                Re-Open&nbsp;
                                                            </button>                                            
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>                                                                                       
                                            <form class="form-horizontal" id="visitAppointmentsForm">
                                                <div class="row"><!-- ROW 1 -->
                                                    <div class="col-lg-12">
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Visit</legend>
                                                            <div class="col-lg-4">
                                                                <input class="form-control" id="vstId" type = "hidden" placeholder="Visit ID" value="<?php echo $pkID; ?>"/>                                                        
                                                                <div class="form-group form-group-sm">
                                                                    <label for="trnsctnNO" class="control-label col-md-4">Visit No:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="trnsctnNo" type = "text" placeholder="" value="<?php echo $trnsctn_no; ?>" readonly/>                                                                                                                                            
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="vstDate" class="control-label col-md-4">Visit Date:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="vstDate" type = "text" placeholder="" value="<?php echo $vst_date; ?>" readonly/>                                                                                                                                            
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="vstBranch" class="control-label col-md-4">Branch:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input type="text" class="form-control" aria-label="..." id="branchNm" value="<?php echo $branch; ?>" readonly>
                                                                            <input type="hidden" id="branchId" value="<?php echo $branch_id; ?>"> 
                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                            <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="onDsbmntBnkBranchChange();">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="vstOption" class="control-label col-md-4">Visit Option:</label>
                                                                    <div  class="col-md-8">
                                                                        <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="vstOption">
                                                                            <option value="" disabled selected>Please Select--</option>
                                                                            <?php
                                                                            $sltdHV = "";
                                                                            $sltdHS = "";
                                                                            $sltdVC = "";
                                                                            $sltdPC = "";
                                                                            if ($vst_option == "Hospital Visit") {
                                                                                $sltdHV = "selected";
                                                                            } else if ($vst_option == "Home Service") {
                                                                                $sltdHS = "selected";
                                                                            } else if ($vst_option == "Video Consultation") {
                                                                                $sltdVC = "selected";
                                                                            } else if ($vst_option == "Phone Call") {
                                                                                $sltdPC = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="Hospital Visit" <?php echo $sltdHV; ?>>Hospital Visit</option>
                                                                            <option value="Home Service" <?php echo $sltdHS; ?>>Home Service</option>
                                                                            <option value="Video Consultation" <?php echo $sltdVC; ?>>Video Consultation</option>
                                                                            <option value="Phone Call" <?php echo $sltdPC; ?>>Phone Call</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="cmnts" class="control-label col-md-4" >Remarks:</label>
                                                                    <div  class="col-md-8">
                                                                        <textarea class="form-control" id="cmnts" cols="2" placeholder="Remarks" rows="6" <?php echo $mkReadOnly; ?> ><?php echo $cmnts; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="prsnNm" class="control-label col-md-4">Patient:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input type="text" class="form-control" aria-label="..." id="prsnNm" value="<?php echo $prsnName; ?>" readonly>
                                                                            <input type="hidden" id="prsnId" value="<?php echo $prsn_id ?>"> 
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="onHospCustomerChange();">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="billThisVisit" class="control-label col-md-4">Bill Visit:</label>
                                                                    <div  class="col-md-8">
                                                                        <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="billThisVisit">
                                                                            <?php
                                                                            $sltdYs = "";
                                                                            $sltdNo = "";
                                                                            if ($bill_this_visit == "Yes") {
                                                                                $sltdYs = "selected";
                                                                            } else if ($bill_this_visit == "No") {
                                                                                $sltdNo = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="Y" <?php echo $sltdYs; ?>>Yes</option>
                                                                            <option value="N" <?php echo $sltdNo; ?>>No</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="sponsor" class="control-label col-md-4">Sponsor:</label>
                                                                    <div  class="col-md-8">
                                                                        <input type="text" class="form-control" aria-label="..." id="patientSponsor" name="patientSponsor" value="<?php echo $sponsor; ?>" style="width:100%;" readonly="readonly">   
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="cardNo" class="control-label col-md-4">Card/Expiry Date:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                            <input type="text" class="form-control" aria-label="..." id="insrncCardNo" name="insrncCardNo" value="<?php echo $cardNo; ?>" style="width:100%;" readonly="readonly">   
                                                                        </div>
                                                                        <div class="col-md-6" style="padding-left:0px;padding-right:0px !important;">
                                                                            <input type="text" class="form-control" aria-label="..." id="insrncExpiryDate" name="insrncExpiryDate" value="<?php echo $cardExpiryDate; ?>" style="width:100%;" readonly="readonly">   
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <div class="row"><!-- ROW 2 -->
                                                    <div class="col-lg-12">
                                                        <fieldset class="basic_person_fs1" style="margin-top:5px !important;">
                                                            <legend class="basic_person_lg">Appointments</legend>
                                                            <div class="row"><!-- ROW 2 -->
                                                                <div class="col-lg-12">
                                                                    <div  class="col-md-12">
                                                                        <div class="row"><!-- ROW 3 -->
                                                                            <div class="col-lg-12">
                                                                                <div style="float:left; margin-bottom: 5px !important;">
                                                                                <?php
                                                                                if ($trnsStatus == "Open" && $vwtypActn !== "VIEW" ) {
                                                                                    ?>
                                                                                        <button type="button" class="btn btn-default btn-sm" onclick="viewAppointmentLinesForm(-1, 'Add Appointment Request','ADD');"><img src="cmn_images/clock.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">ADD APPOINTMENT</button>                                                                            
                                                                                    <?php
                                                                                }
                                                                                ?>                                                                                
                                                                                </div>                                           
                                                                            </div>
                                                                        </div>
                                                                        <table id="visitAppointmentTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>...</th>
                                                                                    <th>...</th>
                                                                                    <th>No.</th>
                                                                                    <th>Transaction No.</th>
                                                                                    <th>Appointment Date</th>
                                                                                    <th>Service Type</th>
                                                                                    <th>Provider<br/>Type</th>
                                                                                    <th>Service Provider</th>
                                                                                    <th style="min-width:85px !important; width:85px !important;">Status</th>                                                                                
                                                                                    <th style="text-align:right !important;">Bill (GHS)</th>
                                                                                    <th style="text-align:right !important;">Payment<br/>(GHS)</th>
                                                                                    <th style="text-align:right !important;">Balance<br/>(GHS)</th>
                                                                                    <th style="display:none !important;">End Date</th>
                                                                                    <?php if($canViewAppntmntDataItms) { ?>
                                                                                    <th>...</th>
                                                                                    <?php } ?>
                                                                                    <?php if ($canGenSalesInvoice && ($vwtypActn !== "VIEW" && $bill_this_visit == 'Yes')) { ?>
                                                                                    <th style="width:40px !important;max-width:40px !important;">...</th>
                                                                                    <?php } ?>
                                                                                    <th>...</th>
                                                                                    <th>...</th>
                                                                                    <th>...</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="disbmntDetTblTbody">
                                                                                <?php
                                                                                $cnta = 0;
                                                                                $appntmnt_id = -1;
                                                                                $appntmnt_status = "Scheduled";
                                                                                //$appntmnt_status_clr = "red";
                                                                                
                                                                                $result2 = get_VisitAppointments($pkID);
                                                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                                                    $cnta = $cnta + 1;
                                                                                    $appntmnt_id = $row2[0];
                                                                                    $appntmnt_status = $row2[5];   
                                                                                    
                                                                                    $ttlSrvsBillPymnt = "0.00";
                                                                                    $ttlSrvsBill = "0.00";
                                                                                    $ttlOutsdnPymnt = "0.00";

                                                                                    $resultAppntmntIDs = getAppntmntInvcIDs($appntmnt_id);

                                                                                    while ($rowBP = loc_db_fetch_array($resultAppntmntIDs)) {
                                                                                        $rsltw = get_One_SalesInvcAmounts($rowBP[0]);
                                                                                        if ($rw = loc_db_fetch_array($rsltw)) {
                                                                                            $ttlSrvsBill = $ttlSrvsBill + (float) $rw[0];
                                                                                            $ttlSrvsBillPymnt = $ttlSrvsBillPymnt + $rw[1];
                                                                                        }
                                                                                    }
                                                                                    
                                                                                    $ttlOutsdnPymnt = (float)$ttlSrvsBill -  (float)$ttlSrvsBillPymnt;
                                                                                    $outstndnAmntColor = "color:green;";
                                                                                    if($ttlOutsdnPymnt > 0){
                                                                                        $outstndnAmntColor = "color:red;";
                                                                                    }
                                                                                    
                                                                                    $appntmntStatusColor = "style='color:red !important;font-weight:bold;'";
                                                                                    if($appntmnt_status == "In Progress"){
                                                                                        $appntmntStatusColor = "style='color:blue !important;font-weight:bold;'";
                                                                                    } else if($appntmnt_status == "Completed"){
                                                                                        $appntmntStatusColor = "style='color:green !important;font-weight:bold;'";
                                                                                    } else if($appntmnt_status == "Cancelled"){
                                                                                        $appntmntStatusColor = "style='font-weight:bold;'";
                                                                                    }
                                                                                    
                                                                                    $fmVwtyp = 0;
                                                                                    $srvsTypeSysCode = getSrvsTypeCode((int)$appntmnt_id);
                                                                                    $srvsTypeSysNm = getSrvsTypeNm((int) $appntmnt_id);
                                                                                    $modalElmntID = "myFormsModalLgHZ";
                                                                                    $modalElmntBodyID = "myFormsModalLgHZBody";
                                                                                    $modalElmntTitleID = "myFormsModalLgHZTitle";
                                                                                    $frmTitle = "Process Appointment";
                                                                                    if ($srvsTypeSysCode == 'VS-0001'){
                                                                                        $fmVwtyp = 2; 
                                                                                        $modalElmntID = "myFormsModaly";
                                                                                        $modalElmntBodyID = "myFormsModalyBody";
                                                                                        $modalElmntTitleID = "myFormsModalyTitle";
                                                                                        $frmTitle = "Vitals of ";
                                                                                    } else if ($srvsTypeSysCode == 'MC-0001'){
                                                                                        $fmVwtyp = 1; 
                                                                                        $frmTitle = "Medical Consultation for ";
                                                                                    } else if ($srvsTypeSysCode == 'LI-0001'){
                                                                                        $fmVwtyp = 3; 
                                                                                        $frmTitle = "Lab Investigations for ";
                                                                                    } else if ($srvsTypeSysCode == 'PH-0001'){
                                                                                        $fmVwtyp = 4; 
                                                                                        $frmTitle = "Prescriptions for ";
                                                                                    } else if ($srvsTypeSysCode == 'IA-0001'){
                                                                                        $fmVwtyp = 5; 
                                                                                        $frmTitle = "Admission of ";
                                                                                    } else {
                                                                                        $fmVwtyp = 99;
                                                                                        $frmTitle = "$srvsTypeSysNm Appointment for ";
                                                                                    }
                                                                                ?>
                                                                                    <tr id="disbmntDetTblAddRow_<?php echo $appntmnt_id; ?>">
                                                                                        <td class="lovtd">
                                                                                            <?php
                                                                                            if ($appntmnt_status == "Open") {
                                                                                            ?>
                                                                                            <button type="button" title="Edit Appointment" class="btn btn-default btn-sm" onclick="viewAppointmentLinesForm(<?php echo $appntmnt_id; ?>, 'Edit Appointment', 'EDIT');" style="padding:2px !important;">
                                                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                            <?php
                                                                                            } else {
                                                                                                echo "...";
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <button type="button" title="View Appointment" class="btn btn-default btn-sm" onclick="viewAppointmentLinesForm(<?php echo $appntmnt_id; ?>, 'Edit Appointment', 'VIEW');" style="padding:2px !important;">
                                                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                        </td>
                                                                                        <td class="lovtd"><?php echo $cnta; ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[8]; ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[1]; ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[2]; ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[3]; ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[4]; ?></td>
                                                                                        <td class="lovtd" <?php echo $appntmntStatusColor; ?>><?php echo $row2[5]; ?></td>
                                                                                        <td class="lovtd" style="text-align:right !important; font-weight:bold !important;"><?php echo number_format($ttlSrvsBill,2); ?></td>
                                                                                        <td class="lovtd" style="text-align:right !important;color:blue; font-weight:bold !important;"><?php echo number_format($ttlSrvsBillPymnt,2); ?></td>
                                                                                        <td class="lovtd" style="text-align:right !important;font-size:14px !important; font-weight:bold !important;<?php echo $outstndnAmntColor; ?>"><?php echo number_format($ttlOutsdnPymnt,2); ?></td>
                                                                                        <td class="lovtd" style="display:none !important;"><?php echo $row2[7]; ?></td>
                                                                                        <?php if($canViewAppntmntDataItms) { ?>
                                                                                        <td class="lovtd">
                                                                                            <button type="button" title="View Items" class="btn btn-default btn-sm" onclick="getOneAppntmntDataItemsForm(<?php echo $appntmnt_id; ?>, 3, 'ShowDialog', '<?php echo $row2[8]; ?>', '<?php echo $appntmnt_status; ?>');" style="padding:2px !important;">
                                                                                                <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                        </td>
                                                                                        <?php } ?>
                                                                                        <?php if ($canGenSalesInvoice && ($vwtypActn !== "VIEW" && $bill_this_visit == 'Yes')) { ?>
                                                                                        <td class="lovtd">
                                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;width:40px !important;max-width:40px !important;" onclick="getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Sales Invoice', 'NO', 'SALES',-1, <?php echo $appntmnt_id; ?>);" style="" title="Generate Invoice">
                                                                                                <img src="cmn_images/sale.jpg" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                        </td>
                                                                                        <?php } ?>
                                                                                        <td class="lovtd">
                                                                                            <?php if ($canCancelAppntmnt && ($appntmnt_status == "Scheduled" && $vwtypActn !== "VIEW")) { ?>
                                                                                            <button type="button" title="Cancel Appointment" class="btn btn-default btn-sm" onclick="cancelAppointment(<?php echo $pkID; ?>, <?php echo $appntmnt_id; ?>);" style="padding:2px !important;">
                                                                                                <img src="cmn_images/back_2.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <?php if ($canViewVisitAD && ($vwtypActn !== "VIEW")) { ?>
                                                                                            <button type="button" title="View/Process Appointment" class="btn btn-default btn-sm" onclick="getHospDetailsForm('<?php echo $modalElmntID; ?>', '<?php echo $modalElmntBodyID; ?>', '<?php echo $modalElmntTitleID; ?>', '<?php echo $frmTitle; ?> <?php echo $prsnName." - ".$row2[8]; ?>', 3, <?php echo $fmVwtyp; ?>, 'EDIT', <?php echo $appntmnt_id; ?>, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow<?php echo $cnta; ?>');" style="padding:2px !important;">
                                                                                                <img src="cmn_images/98.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                             <?php } ?>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <?php
                                                                                            if ($canDelVisitAD && ($appntmnt_status == "Scheduled" && $row2[13] != 'VS-0001' && $vwtypActn !== "VIEW")) {
                                                                                                ?>
                                                                                                <button type="button" title="Delete Appointment" class="btn btn-default btn-sm" onclick="delVisitAppointment(<?php echo $row2[0]; ?>);" style="padding:2px !important;">
                                                                                                    <img src="cmn_images/delete_img.gif" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                </button>
                                                                                            <?php
                                                                                        } else {
                                                                                            echo "...";
                                                                                        }
                                                                                        ?>
                                                                                        </td>
                                                                                    </tr>                                                                                    
                                                                                <?php } ?>       
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div id = "prflCMDataEDT" class = "tab-pane fade" style = "border:none !important;"></div>
                                        <div id = "prflCMAttchmntEDT" class = "tab-pane fade" style = "border:none !important;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    //echo $cntent;
                }
            } 
            else if ($vwtyp == 2) {//APPOINTMENT LINE DETAILS
                $appntmntID = isset($_POST['appntmntID']) ? cleanInputData($_POST['appntmntID']) : -1;
                $lnkdSrvsTypeCode = isset($_POST['lnkdSrvsTypeCode']) ? cleanInputData($_POST['lnkdSrvsTypeCode']) : '';
                
                $lnkdCnsltnID = isset($_POST['lnkdCnsltnID']) ? cleanInputData($_POST['lnkdCnsltnID']) : -1;
                $vstID = isset($_POST['vstID']) ? cleanInputData($_POST['vstID']) : -1;
                
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
                $svsTypStyle="";
                $svsTypWidth="";
                
                if($lnkdSrvsTypeCode != ""){
                    $srvsTypeId  = (int)getGnrlRecID2("hosp.srvs_types", "sys_code", "type_id", $lnkdSrvsTypeCode);
                    $srvsType  = getGnrlRecNm("hosp.srvs_types", "type_id", "type_name||' ('||type_desc||')'", $srvsTypeId);
                    //$appntmntDate = date("d-M-Y H:i:s");
                    $prvdrType = "G";
                    $svsTypStyle ="style='display:none !important;'";
                    $svsTypWidth="style='width:100% !important;'";
                }
                

                $result = get_VisitAppointmentDets($appntmntID);
                while ($row2 = loc_db_fetch_array($result)) {
                    $appntmntDate  = $row2[1];
                    $srvsTypeId  = (int)$row2[2];
                    $srvsType  = $row2[3];
                    $prvdrType = $row2[4];
                    
                    if(strtoupper($prvdrType)== "I" ){
                        $srvsPrvdrId = (int)$row2[5];
                        $srvsPrvdr = $row2[6];
                    } else  {
                        $srvsPrvdrId = (int)$row2[7];
                        $srvsPrvdr = $row2[8];
                    }
                    
                    $appntmntStatus = $row2[9];
                    $appntmntCmnts = $row2[10];
                    $appntmntNo = $row2[13];
                    
                    if ($appntmntStatus == "Closed" || $vwtypActn == "VIEW") {
                        $mkReadOnly = "readonly=\"true\"";
                        $mkRmrkReadOnly = "readonly=\"true\"";
                        $mkReadOnlyDsbld = "disabled=\"true\"";
                    }
                }
                ?>
                <form class="form-horizontal" id="appointmentDetForm">
                    <input class="form-control" id="frmAppntmntID" type = "hidden" placeholder="Appointment ID" value="<?php echo $appntmntID; ?>"/>
                    <input class="form-control" id="frmVstID" type = "hidden" placeholder="vstID ID" value="<?php echo $vstID; ?>"/>
                    <input class="form-control" id="frmCnsltnID" type = "hidden" placeholder="vstID ID" value="<?php echo $lnkdCnsltnID; ?>"/>
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmAppntmntNo" class="control-label col-md-4" >Transaction No.:</label>
                                <div  class="col-md-8">
                                    <input type="text" class="form-control" id="frmAppntmntNo" placeholder="Transaction No."  <?php echo $mkReadOnly; ?> value="<?php echo $appntmntNo; ?>" readonly/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmAppntmntDate" class="control-label col-md-4">Date:</label>
                                <div  class="col-md-8">
                                    <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd hh:ii:ss">
                                        <input class="form-control" size="16" type="text" id="frmAppntmntDate" value="<?php echo $appntmntDate; ?>" readonly="">
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
                                <label for="frmSrvsType" class="control-label col-md-4">Service Type:</label>
                                <div  class="col-md-8">
                                    <div class="input-group" <?php echo $svsTypWidth; ?>>
                                        <input type="text" class="form-control" aria-label="..." id="frmSrvsType" value="<?php echo $srvsType; ?>" readonly>
                                        <input type="hidden" id="frmSrvsTypeId" value="<?php echo $srvsTypeId; ?>">
                                        <?php if ($lnkdSrvsTypeCode == "") { ?>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Service Types', '', '', '', 'radio', true, '', 'frmSrvsTypeId', 'frmSrvsType', 'clear', 1, '', function(){
                                            $('#frmSrvsPrvdr').val('');
                                            $('#frmSrvsPrvdrId').val('-1');
                                        });">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="form-group form-group-sm">
                        <label for="frmPrvdrType" class="control-label col-md-4">Provider Type:</label>
                        <div class="col-md-8">
                            <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="frmPrvdrType" onChange="resetFormOnPrvdrTypeChange();">
                                <option value="" disabled selected>Please Select--</option>
                                <?php
                                $sltdGrp = "";
                                $sltdInd = "";
                                if ($prvdrType == "G") {
                                    $sltdGrp = "selected";
                                } else if ($prvdrType == "I") {
                                    $sltdInd = "selected";
                                }
                                ?>
                                <option value="G" <?php echo $sltdGrp; ?>>Group</option>
                                <option value="I" <?php echo $sltdInd; ?>>Individual</option>
                            </select>
                        </div>
                    </div>
                    <div class='row'>
                        <div  class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="frmSrvsPrvdr" class="control-label col-md-4">Service Provider:</label>
                                <div  class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="..." id="frmSrvsPrvdr" value="<?php echo $srvsPrvdr; ?>" readonly>
                                        <input type="hidden" id="frmSrvsPrvdrId" value="<?php echo $srvsPrvdrId; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getServiceProvider();">
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
                                <label for="frmAppntmntCmnts" class="control-label col-md-4" >Remarks:</label>
                                <div  class="col-md-8">
                                    <textarea class="form-control" id="frmAppntmntCmnts" cols="2" placeholder="Remarks" rows="4" <?php echo $mkReadOnly; ?> ><?php echo $appntmntCmnts; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"><!-- ROW BUTTON -->
                        <div class="col-lg-12">
                            <div style="float:right; margin-top: 5px !important;">
                                <?php
                                if (!($appntmntStatus == "Closed" || $vwtypActn == "VIEW")) {
                                    if($appntmntID <= 0){
                                    ?>
                                    <button id="svCreditItmBtn" type="button" class="btn btn-primary btn-sm" onclick="saveVisitAppointment('<?php echo $lnkdSrvsTypeCode; ?>');"><img src="cmn_images/initiate.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save and Schedule</button>
                                    <?php
                                    }
                                }
                                ?>                                                                                
                            </div>                                           
                        </div>
                    </div>
                </form>
                <?php
            }
            else if ($vwtyp == 3){
                ?>
                <div class="row"><!-- ROW 1 -->
                    <div class="col-lg-12">  
                        <div class="row" id="allAppntmntDataItemsDetailInfo" style="padding:0px 15px 0px 15px !important">
                            <?php
                            $canAddAppntmntDataItems = test_prmssns($dfltPrvldgs[0], $mdlNm);
                            $canEdtAppntmntDataItems = test_prmssns($dfltPrvldgs[0], $mdlNm);
                            $canDelAppntmntDataItems = test_prmssns($dfltPrvldgs[0], $mdlNm);

                            $searchAll = true;
                            $isEnabledOnly = false;
                            if (isset($_POST['isEnabled'])) {
                                $isEnabledOnly = cleanInputData($_POST['isEnabled']);
                            }

                            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Item Desc';
                            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 50;
                            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
                            if (strpos($srchFor, "%") === FALSE) {
                                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                                $srchFor = str_replace("%%", "%", $srchFor);
                            }

                            $appntmntID = isset($_POST['sbmtdAppntmntDataItemsAppntmntID']) ? cleanInputData($_POST['sbmtdAppntmntDataItemsAppntmntID']) : -1;
                            $sbmtdTrsNo = isset($_POST['sbmtdTrsNo']) ? cleanInputData($_POST['sbmtdTrsNo']) : '';
			    $sbmtdAppntmntStatus = isset($_POST['sbmtdAppntmntStatus']) ? cleanInputData($_POST['sbmtdAppntmntStatus']) : 'Scheduled';
                            
                            $trnsStatus = $sbmtdAppntmntStatus; //getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "appntmnt_status", $appntmntID);

                            if (1 > 0) {
                                $total = getAppntmntDataItemsTblTtl($appntmntID, $srchFor, $srchIn, $searchAll);

                                if ($pageNo > ceil($total / $lmtSze)) {
                                    $pageNo = 1;
                                } else if ($pageNo < 1) {
                                    $pageNo = ceil($total / $lmtSze);
                                }
                                $curIdx = $pageNo - 1;
                                $sbmtdScmSalesInvcID = -1;
                                $trsctnLnUomID = -1;
                                $trsctnLnUomNm = "each";
                                $result2 = getAppntmntDataItemsTbl($appntmntID, $srchFor, $srchIn, $curIdx, $lmtSze, $searchAll, $sortBy);
                                ?>
                                <div class="row" style="padding:0px 15px 0px 15px !important">
                                    <!--<legend class="basic_person_lg1" style="color: #003245">APPOINTMENT ITEMS FOR <?php echo $sbmtdTrsNo; ?></legend>-->
                                    <?php
                                    if ($canEdtAppntmntDataItems === true) {
                                        //$colClassType1 = "col-lg-2";
                                        $colClassType1 = "col-lg-6";
                                        $colClassType2 = "col-lg-3";
                                        $colClassType3 = "col-lg-4";
                                        $nwRowHtml = urlencode("<tr id=\"allAppntmntDataItemsRow__WWW123WWW\">"
                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>
                                                <td class=\"lovtd\">
                                                    <div class=\"input-group\" style=\"width:100% !important;\">
							<input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allAppntmntDataItemsRow_WWW123WWW_AppntmntDataItemsID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                        <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allAppntmntDataItemsRow_WWW123WWW_ItemDesc\" value=\"\" readonly>
                                                        <input type=\"hidden\" id=\"allAppntmntDataItemsRow_WWW123WWW_ItemID\" value=\"\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getHospAppntmntDataItems('allAppntmntDataItemsRow__WWW123WWW', 'ShowDialog', 'Sales Invoice', 'false', function () {var a=1;});\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\" style=\"max-width:44px !important;\">
                                                    <input type=\"number\" style=\"width:100% !important;\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allAppntmntDataItemsRow_WWW123WWW_Qty\" name=\"allAppntmntDataItemsRow_WWW123WWW_Qty\" value=\"\">
                                                </td>                                               
                                                <td class=\"lovtd\" style=\"max-width:35px;width:35px;text-align: center;\">
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allAppntmntDataItemsRow_WWW123WWW_UomID\" value=\"-1\" style=\"width:100% !important;\">  
                                                    <div class=\"\" style=\"width:100% !important;\">
                                                        <label class=\"btn btn-primary btn-file\" onclick=\"getOneHospAppntmntDataUOMBrkdwnForm(-1, 5, 'allAppntmntDataItemsRow__WWW123WWW');\">
                                                            <span class=\"\" id=\"allAppntmntDataItemsRow_WWW123WWW_UomNm1\">Each</span>
                                                        </label>
                                                    </div>                                              
                                                </td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td class=\"lovtd\">                                                                                                                            
                                                    <textarea style=\"width:100% !important;\" class=\"form-control\" aria-label=\"...\" id=\"allAppntmntDataItemsRow_WWW123WWW_Cmnts\" name=\"allAppntmntDataItemsRow_WWW123WWW_Cmnts\"></textarea>                                                                     
                                                </td>
                                                    <td class=\"lovtd\"></td>
                                                    <td class=\"lovtd\"></td>
                                                    <td class=\"lovtd\"></td>
                                                <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"deleteAppntmntDataItems('allAppntmntDataItemsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Item\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button>
                                                </td>
                                            </tr>");
                                        ?>
                                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">
                                            <?php if (!($trnsStatus == "Completed" || /*$vwtypActn == "VIEW" ||*/ $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allAppntmntDataItemsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Item">
                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;New Item
                                                </button>
                                            <?php } ?>
                                        </div>
                                        <?php
                                    } else {
                                        $colClassType1 = "col-lg-3";
                                        $colClassType2 = "col-lg-6";
                                        $colClassType3 = "col-lg-6";
                                        /* $colClassType1 = "col-lg-3";
                                          $colClassType2 = "col-lg-3";
                                          $colClassType3 = "col-lg-3"; */
                                    }
                                    ?>

                                    <input type="hidden" class="form-control" aria-label="..." id="recCnt" name="recCnt" value="">
                                    <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdAppntmntDataItemsAppntmntID" name="sbmtdAppntmntDataItemsAppntmntID" value="<?php echo $appntmntID; ?>">
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;"> 
                                        <div style="float:right !important;">
                                            <?php if (!($trnsStatus == "Completed" || /*$vwtypActn == "VIEW" ||*/ $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAppntmntDataItems('<?php echo $sbmtdTrsNo; ?>', '<?php echo $sbmtdAppntmntStatus; ?>');" data-toggle="tooltip" data-placement="bottom" title="Save Appointment Item(s)">
                                                <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;Save
                                            </button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                        <table class="table table-striped table-bordered table-responsive" id="allAppntmntDataItemsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th style="min-width:150px;width:250px !important;">Item</th>
                                                    <th style="max-width:55px;width:55px;">Qty</th>
                                                    <th style="max-width:60px;width:60px;text-align: center;">UOM.</th>
                                                    <th style="max-width:50px;width:50px;">Unit Price<br/>(GHS)</th>
                                                    <th style="max-width:60px;width:60px;">Line Total<br/>(GHS)</th>
                                                    <th>Comments</th>
                                                    <th>Billed?</th>
                                                    <th>Paid?</th>
                                                    <th>Invoice No.</th>
                                                    <th>...</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cntr = 0;
                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                    $cntr += 1;
                                                    $sbmtdScmSalesInvcID = $row2[0];
                                                    $trsctnLnUomID = $row2[11];
                                                    $trsctnLnUomNm = getGnrlRecNm("inv.unit_of_measure", "uom_id", "uom_name", $trsctnLnUomID);
                                                    $lnTtl  = (float)$row2[3] * (float)$row2[12];
                                                    ?>
                                                    <tr id="allAppntmntDataItemsRow_<?php echo $cntr; ?>">                                    
                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                        <td class="lovtd">
                                                            <?php if ($trnsStatus == "Completed" || /*$vwtypActn == "VIEW" ||*/ $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") { ?>
                                                                <span style=""><?php echo $row2[2]; ?></span>
                                                            <?php } else { ?>
                                                            <div class="input-group" style="width:100% !important;">
								<input type="hidden" class="form-control" aria-label="..." id="allAppntmntDataItemsRow<?php echo $cntr; ?>_AppntmntDataItemsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="allAppntmntDataItemsRow<?php echo $cntr; ?>_ItemDesc" value="<?php echo $row2[2]; ?>" readonly>
                                                                <input type="hidden" id="allAppntmntDataItemsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $row2[1]; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getHospAppntmntDataItems('allAppntmntDataItemsRow_<?php echo $cntr; ?>', 'ShowDialog', 'Sales Invoice', 'false', function () {
                                                                            var a = 1;
                                                                        });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                                <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', '', '', '', 'radio', true, '', 'allAppntmntDataItemsRow<?php echo $cntr; ?>_ItemID', 'allAppntmntDataItemsRow<?php echo $cntr; ?>_ItemDesc', 'clear', 1, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="lovtd"> 
                                                            <?php if ($trnsStatus == "Completed" || /*$vwtypActn == "VIEW" ||*/ $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") { ?>
                                                                <span style=""><?php echo  $row2[3]; ?></span>
                                                            <?php } else { ?>
                                                            <input type="number" style="width:100% !important;" min="1" class="form-control rqrdFld" aria-label="..." id="allAppntmntDataItemsRow<?php echo $cntr; ?>_Qty" name="allAppntmntDataItemsRow<?php echo $cntr; ?>_Qty" value="<?php echo $row2[3]; ?>"> 
                                                            <?php } ?>
                                                        </td>                                               
                                                        <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                            <?php if ($trnsStatus == "Completed" || /*$vwtypActn == "VIEW" ||*/ $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") { ?>
                                                                <span style=""><?php echo $trsctnLnUomNm; ?></span>
                                                            <?php } else { ?>
                                                            <input type="hidden" class="form-control" aria-label="..." id="allAppntmntDataItemsRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">  
                                                            <div class="" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file" onclick="getOneHospAppntmntDataUOMBrkdwnForm(<?php echo $sbmtdScmSalesInvcID; ?>, 5, 'allAppntmntDataItemsRow_<?php echo $cntr; ?>');">
                                                                    <span class="" id="allAppntmntDataItemsRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                </label>
                                                            </div>  
                                                            <?php } ?>
                                                        </td>
                                                        <td class="lovtd" style="text-align:center !important; text-align: right !important;"><?php echo number_format($row2[12],2); ?></td>
                                                        <td class="lovtd" style="text-align:center !important;font-weight: bold !important;text-align: right !important;"><?php echo number_format($lnTtl,2); ?></td>
                                                        <td class="lovtd">  
                                                            <?php if ($trnsStatus == "Completed" || /*$vwtypActn == "VIEW" ||*/ $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") { ?>
                                                                <span style=""><?php echo $row2[4]; ?></span>
                                                            <?php } else { ?>
                                                            <textarea style="width:100% !important;" class="form-control" aria-label="..." id="allAppntmntDataItemsRow<?php echo $cntr; ?>_Cmnts" name="allAppntmntDataItemsRow<?php echo $cntr; ?>_Cmnts"><?php echo $row2[4]; ?></textarea>  
                                                            <?php } ?>
                                                        </td>
                                                        <td class="lovtd"><?php echo $row2[5]; ?></td>
                                                        <td class="lovtd"><?php echo $row2[6]; ?></td>
                                                        <td class="lovtd">
                                                            <?php if ($trnsStatus == "Completed" || /*$vwtypActn == "VIEW" ||*/ $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled") { ?>
                                                                <span style=""><?php echo $row2[8]; ?></span>
                                                            <?php } else { ?>
                                                            <a href="javascript:getOneScmSalesInvcForm(<?php echo $row2[7]; ?>, 3, 'ShowDialog', 'Sales Invoice', 'NO', 'SALES',-1, <?php echo $row2[10]; ?>);">
                                                                <?php echo $row2[8]; ?>
                                                            </a>
                                                            <?php } ?>
                                                        </td>
                                                            <td class="lovtd">
                                                                <?php if ($row2[5] == "No") { ?>
                                                                <?php if (!($trnsStatus == "Completed" || /*$vwtypActn == "VIEW" ||*/ $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="deleteAppntmntDataItems('allAppntmntDataItemsRow_<?php echo $cntr; ?>', '<?php echo $row2[0]; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item">
                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            </td>
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
                </div>        
                <?php
            }
            else if ($vwtyp == 4) {
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                $qCnsgnOnly = isset($_POST['qCnsgnOnly']) ? cleanInputData($_POST['qCnsgnOnly']) : "false";
                $sbmtdDocType = isset($_POST['sbmtdDocType']) ? cleanInputData($_POST['sbmtdDocType']) : "";
                $sbmtdItemID = isset($_POST['sbmtdItemID']) ? (int) cleanInputData($_POST['sbmtdItemID']) : -1;
                $sbmtdStoreID = isset($_POST['sbmtdStoreID']) ? (int) cleanInputData($_POST['sbmtdStoreID']) : -1;
                $sbmtdCstmrSiteID = isset($_POST['scmSalesInvcCstmrSiteID']) ? (float) cleanInputData($_POST['scmSalesInvcCstmrSiteID'])
                            : -1;
                $sbmtdCallBackFunc = isset($_POST['sbmtdCallBackFunc']) ? cleanInputData($_POST['sbmtdCallBackFunc']) : 'function(){var a=1;}';
                $sbmtdRowIDAttrb = isset($_POST['sbmtdRowIDAttrb']) ? cleanInputData($_POST['sbmtdRowIDAttrb']) : '';
                $qCnsgnOnlyB = ($qCnsgnOnly == "true") ? true : false;
                if ($sbmtdStoreID <= 0 && $qCnsgnOnlyB == false) {
                    $sbmtdStoreID = $selectedStoreID;
                }
                if ($qCnsgnOnlyB === true && $sbmtdItemID > 0) {
                    $lmtSze = 1000000;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_Total_StoreItms($srchFor, $srchIn, $orgID, $sbmtdStoreID, $sbmtdDocType, $qCnsgnOnlyB, $sbmtdItemID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_StoreItems($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $sbmtdStoreID, $sbmtdDocType, $qCnsgnOnlyB,
                        $sbmtdItemID, $sbmtdCstmrSiteID);
                $cntr = 0;
                $ttlAvlblQTY = 0;
                $ttlRsvdQTY = 0;
                $ttlQTY = 0;
                $ttlSP = 0;
                $ttlCP = 0;
                ?> 
                <form id='scmSalesInvItmsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">HOSP STORE ITEMS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType1 = "col-md-2";
                            $colClassType2 = "col-md-5";
                            $colClassType3 = "col-md-6";
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="scmSalesInvItmsSrchFor" type = "text" placeholder="Search For" value="<?php
                    echo trim(str_replace("%", " ", $srchFor));
                    ?>" onkeyup="enterKeyFuncScmSalesInvItms(event, '', '#myFormsModalLxHBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="scmSalesInvItmsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <input id="sbmtdItemID" type = "hidden" value="<?php echo $sbmtdItemID; ?>">
                                    <input id="sbmtdStoreID" type = "hidden" value="<?php echo $sbmtdStoreID; ?>">
                                    <input id="sbmtdDocType" type = "hidden" value="<?php echo $sbmtdDocType; ?>">
                                    <input id="sbmtdCallBackFunc" type = "hidden" value="<?php echo $sbmtdCallBackFunc; ?>">
                                    <input id="sbmtdRowIDAttrb" type = "hidden" value="<?php echo $sbmtdRowIDAttrb; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvItms('clear', '#myFormsModalLxHBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvItms('', '#myFormsModalLxHBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="scmSalesInvItmsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Item Code/Name", "Item Description");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="scmSalesInvItmsDsplySze" style="min-width:70px !important;">                            
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 20, 30, 50, 100, 500, 1000, 1000000);
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
                                            <a href="javascript:getScmSalesInvItms('previous', '#myFormsModalLxHBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getScmSalesInvItms('next', '#myFormsModalLxHBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div class = "<?php echo $colClassType1; ?>" style = "padding:5px 1px 0px 1px !important;">
                                <div class = "form-check" style = "font-size: 12px !important;">
                                    <label class = "form-check-label">
                                        <?php
                                        $shwCnsgnOnlyChkd = "";
                                        if ($qCnsgnOnlyB == true) {
                                            $shwCnsgnOnlyChkd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getScmSalesInvItms('', '#myFormsModalLxHBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmSalesInvItmsShwCnsgnOnly" name="scmSalesInvItmsShwCnsgnOnly"  <?php echo $shwCnsgnOnlyChkd; ?>>
                                        Show Consignments
                                    </label>
                                </div>                            
                            </div>
                        </div> 
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="scmSalesInvItmsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;max-width:30px;width:30px;">...</th>
                                            <th style="text-align:center;max-width:35px;width:35px;">No.</th>
                                            <th style="max-width:275px;width:275px;">Item Code/Description</th>
                                            <th style="text-align:right;min-width:220px;width:220px;">Category</th>
                                            <th style="text-align:center;max-width:55px;width:55px;">UOM.</th>
                                            <th style="text-align:right;">Consignment No.</th>	
                                            <th style="text-align:right;">Available QTY</th>
                                            <th style="text-align:right;display:none;">Reserved QTY</th>
                                            <th style="text-align:right;display:none;">Total QTY</th>
                                            <th style="text-align:right;">Selling Price</th>
                                            <th style="text-align:right;">Cost Price</th>
                                            <th>Store</th>
                                            <!--<th>Shelves</th>-->
                                            <th>Tax/Discount Code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            $cntr += 1;
                                            $trnsLnItmID = (float) $row[0];
                                            $trnsLnItmNm = $row[2];
                                            $trnsLnUomID = (float) $row[18];
                                            $trnsLnUomNm = $row[19];
                                            $trnsLnCnsgnNo = (float) $row[11];
                                            $trnsLnAvlblQty = (float) $row[20];
                                            $trnsLnRsvdQty = (float) $row[21];
                                            $trnsLnTtlQty = (float) $row[22];
                                            $trnsLnSellPrice = (float) $row[3];
                                            $trnsLnCostPrice = (float) $row[12];
                                            $trnsLnExpryDate = (float) $row[13];
                                            $trnsLnStckID = (float) $row[5];
                                            $trnsLnCtgryID = (float) $row[4];
                                            $trnsLnCtgryNm = $row[23];
                                            $trnsLnStoreID = (float) $row[6];
                                            $trnsLnStoreNm = $row[14];
                                            $trnsLnShelves = $row[7];
                                            $trnsLnTaxID = (float) $row[8];
                                            $trnsLnTaxNm = $row[15];
                                            $trnsLnDscntID = (float) $row[9];
                                            $trnsLnDscntNm = $row[16];
                                            $trnsLnChrgID = (float) $row[10];
                                            $trnsLnChrgNm = $row[17];
                                            $trnsLnInvAcntID = (int) $row[24];
                                            $trnsLnCogsAcntID = (int) $row[25];
                                            $trnsLnSalesRevAcntID = (int) $row[26];
                                            $trnsLnSalesRetAcntID = (int) $row[27];
                                            $trnsLnPrchsRetAcntID = (int) $row[28];
                                            $trnsLnExpnsAcntID = (int) $row[29];
                                            $trnsLnItmType = $row[30];
                                            $ttlAvlblQTY += $trnsLnAvlblQty;
                                            $ttlRsvdQTY += $trnsLnRsvdQty;
                                            $ttlQTY += $trnsLnTtlQty;
                                            $ttlSP += ($trnsLnAvlblQty * $trnsLnSellPrice);
                                            $ttlCP += ($trnsLnAvlblQty * $trnsLnCostPrice);
                                            ?>
                                            <tr id="scmSalesInvItmsHdrsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                <td class="lovtd" style="text-align:center;">
                                                    <input type="checkbox" name="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_CheckBox" value="scmSalesInvItmsHdrsRow_<?php echo $cntr; ?>">
                                                </td>                                     
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td> 
                                                <td class="lovtd"><?php echo $trnsLnItmNm; ?></td> 
                                                <td class="lovtd"><?php
                                echo $trnsLnCtgryNm . str_replace(" (" . $trnsLnCtgryNm . ")", "", " (" . $trnsLnItmType . ")");
                                ?></td>                                                                 
                                                <td class="lovtd" style="max-width:55px;width:55px;text-align: center;">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_UomID" value="<?php echo $trnsLnUomID; ?>">  
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_QTY" value="<?php echo $trnsLnAvlblQty; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trnsLnItmID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_ItmNm" value="<?php echo $trnsLnItmNm; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_UomNm" value="<?php echo $trnsLnUomNm; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_SellPrice" value="<?php echo $trnsLnSellPrice; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_CostPrice" value="<?php echo $trnsLnCostPrice; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_CnsgnNo" value="<?php echo $trnsLnCnsgnNo; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_TaxID" value="<?php echo $trnsLnTaxID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_DscntID" value="<?php echo $trnsLnDscntID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_ChrgID" value="<?php echo $trnsLnChrgID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trnsLnStoreID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_StoreNm" value="<?php echo $trnsLnStoreNm; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_InvAcntID" value="<?php echo $trnsLnInvAcntID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_CogsAcntID" value="<?php echo $trnsLnCogsAcntID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_SalesRevAcntID" value="<?php echo $trnsLnSalesRevAcntID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_SalesRetAcntID" value="<?php echo $trnsLnSalesRetAcntID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_PrchsRetAcntID" value="<?php echo $trnsLnPrchsRetAcntID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_ExpnsAcntID" value="<?php echo $trnsLnExpnsAcntID; ?>">
                                                    <div class="" style="width:100% !important;">
                                                        <label class="btn btn-default btn-file" onclick="getOneScmUOMBrkdwnForm(-1, 3, 'scmSalesInvItmsHdrsRow_<?php echo $cntr; ?>');">
                                                            <span class="" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_UomNm1"><?php echo $trnsLnUomNm; ?></span>
                                                        </label>
                                                    </div>                                              
                                                </td>
                                                <td class="lovtd" style="text-align:right;"><?php echo $trnsLnCnsgnNo; ?></td>
                                                <?php
                                                $style1 = "color:red;";
                                                if ($trnsLnAvlblQty > 0) {
                                                    $style1 = "color:green;";
                                                }
                                                ?>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;<?php echo $style1; ?>"><?php
                                echo $trnsLnAvlblQty;
                                ?>
                                                </td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;display:none;"><?php echo $trnsLnRsvdQty; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;display:none;"><?php
                                echo $trnsLnTtlQty;
                                                ?>
                                                </td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;"><?php
                                                    echo number_format($trnsLnSellPrice, 2);
                                                    ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;"><?php
                                echo number_format($trnsLnCostPrice, 2);
                                ?></td>
                                                <td class="lovtd"><?php echo $trnsLnStoreNm; ?></td>
                                                <td class="lovtd"><?php echo trim($trnsLnTaxNm . ", " . $trnsLnDscntNm . "," . $trnsLnChrgNm, ", "); ?></td>
                                            </tr>
                    <?php
                }
                ?>
                                    </tbody>
                                    <tfoot>                                                            
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>TOTALS:</th>
                                            <th style="text-align: right;">
                <?php
                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\">" . $ttlAvlblQTY . "</span>";
                ?>
                                            </th>
                                            <th style="text-align: right;display:none;">
                <?php
                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\">" . $ttlRsvdQTY . "</span>";
                ?>
                                            </th>
                                            <th style="text-align: right;display:none;">
                <?php
                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\">" . $ttlQTY . "</span>";
                ?>
                                            </th>
                                            <th style="text-align: right;">
                <?php
                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\">" . number_format($ttlSP, 2, '.', ',') . "</span>";
                ?>
                                            </th>
                                            <th style="text-align: right;">
                <?php
                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\">" . number_format($ttlCP, 2, '.', ',') . "</span>";
                ?>
                                            </th>
                                            <th style="">&nbsp;</th>                                           
                                            <!--<th style="">&nbsp;</th>                                           -->
                                            <th style="">&nbsp;</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>                     
                        </div>
                        <div class="row" style="float:right;padding-right: 15px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal"  onclick="$('#myFormsModalLxH').modal('hide');">Close</button>
                            <?php if (strpos($sbmtdDocType, "Receipt") !== FALSE) { ?>
                                <button type="button" class="btn btn-primary" onclick="applySlctdHospAppntmntDataItms('<?php echo $sbmtdRowIDAttrb; ?>', '<?php echo $qCnsgnOnly; ?>', 'oneScmCnsgnRcptSmryLinesTable', <?php echo $sbmtdCallBackFunc; ?>);">Apply Selection</button>
                            <?php } else if (strpos($sbmtdDocType, "Purchase") !== FALSE) { ?>
                                <button type="button" class="btn btn-primary" onclick="applySlctdHospAppntmntDataItms('<?php echo $sbmtdRowIDAttrb; ?>', '<?php echo $qCnsgnOnly; ?>', 'oneScmPrchsDocSmryLinesTable', <?php echo $sbmtdCallBackFunc; ?>);">Apply Selection</button>
                            <?php } else if (strpos($sbmtdDocType, "Stock") !== FALSE) { ?>
                                <button type="button" class="btn btn-primary" onclick="applySlctdHospAppntmntDataItms('<?php echo $sbmtdRowIDAttrb; ?>', '<?php echo $qCnsgnOnly; ?>', 'oneScmStockTrnsfrSmryLinesTable', <?php echo $sbmtdCallBackFunc; ?>);">Apply Selection</button>
                <?php } else { ?>
                                <button type="button" class="btn btn-primary" onclick="applySlctdHospAppntmntDataItms('<?php echo $sbmtdRowIDAttrb; ?>', '<?php echo $qCnsgnOnly; ?>', 'oneScmSalesInvcSmryLinesTable', <?php echo $sbmtdCallBackFunc; ?>);">Apply Selection</button>
                <?php } ?>
                        </div>
                    </fieldset>
                </form>
                <?php
            }
            else if ($vwtyp == 5) {
                /*if (!$canAdd && !$canEdt) {
                    restricted();
                    exit();
                }*/
                $itemID = isset($_POST['sbmtdItemID']) ? cleanInputData($_POST['sbmtdItemID']) : 0;
                $varTtlQty = isset($_POST['varTtlQty']) ? cleanInputData($_POST['varTtlQty']) : 0;
                $sbmtdRwNum = isset($_POST['sbmtdRwNum']) ? cleanInputData($_POST['sbmtdRwNum']) : -1;
                $sbmtdTblRowID = isset($_POST['sbmtdTblRowID']) ? cleanInputData($_POST['sbmtdTblRowID']) : "";
                $rowIDAttrb = isset($_POST['rowIDAttrb']) ? cleanInputData($_POST['rowIDAttrb']) : "";
                $sbmtdCrncyNm = isset($_POST['sbmtdCrncyNm']) ? cleanInputData($_POST['sbmtdCrncyNm']) : -1;
                $sbmtdCrncyID = getPssblValID($sbmtdCrncyNm, getLovID("Currencies"));
                $ttlQty = $varTtlQty;
                $nwQty = 0;
                $rmndPrtVal = $ttlQty;
                $ttlPrce = 0;
                $unitPrce = 0;
                $fnccurid = $sbmtdCrncyID;
                $fnccurnm = $sbmtdCrncyNm;
                if ($sbmtdCrncyID <= 0) {
                    $fnccurid = getOrgFuncCurID($orgID);
                    $fnccurnm = getPssblValNm($fnccurid);
                }
                ?>
                <div class="row">
                    <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRowIDAttrb" value="<?php echo $rowIDAttrb; ?>">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="oneINVQtyBrkDwnTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>UOM</th>
                                    <th style="text-align: right;">UOM QTY</th>
                                    <th style="text-align: right;">EQUIV BASE QTY</th>
                                    <th style="text-align: right;">Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                $crncyID = $fnccurid;
                                $crncyIDNm = $fnccurnm;
                                $whlPrtVal = 0;
                                $rngSum = 0;
                                $cnvrtdQty = 0;
                                $result = getUomBrkDwn($itemID);
                                while ($row = loc_db_fetch_array($result)) {
                                    $cntr++;
                                    $cnvsnFctr = $row[4];
                                    if ($rngSum == $ttlQty) {
                                        $cnvrtdQty = 0;
                                        $whlPrtVal = 0;
                                    } else {
                                        if ($rmndPrtVal >= $cnvsnFctr) {
                                            $whlPrt = (int) ($rmndPrtVal / $cnvsnFctr);
                                            $rmndPrt = $rmndPrtVal % $cnvsnFctr;
                                            if ($whlPrt > 0) {
                                                $whlPrtVal = $whlPrt;
                                                $cnvrtdQty = $whlPrtVal * $cnvsnFctr;
                                            }
                                            if ($rmndPrt > 0) {
                                                $rmndPrtVal = $rmndPrt;
                                            }
                                        } else {
                                            $cnvrtdQty = 0;
                                            $whlPrtVal = 0;
                                        }
                                        $rngSum = $rngSum + $cnvrtdQty;
                                    }
                                    if (strpos($rowIDAttrb, "CnsgnRcpt") !== FALSE || strpos($rowIDAttrb, "StockTrnsfr") !== FALSE) {
                                        $unitPrce = $cnvsnFctr * (float) ($row[7]);
                                    } else {
                                        $unitPrce = (float) ($row[5]);
                                    }
                                    $ttlPrce += $whlPrtVal * $unitPrce;
                                    $nwQty += $cnvrtdQty;
                                    ?>
                                    <tr id="oneINVQtyBrkRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                        <td class="lovtd">
                                            <span><?php echo $row[1] ?></span>
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_ItmUomID" value="<?php echo $row[2]; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_UntVal" value="<?php echo $unitPrce; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_CnvFctr" value="<?php echo $row[4]; ?>" style="width:100% !important;">                                                                                                
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <input type="text" class="form-control invUmbQty" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_BaseQty" name="oneINVQtyBrkRow<?php echo $cntr; ?>_BaseQty" value="<?php
                                                   echo number_format($whlPrtVal, 0);
                                                   ?>"  onchange="calcScmUomBrkdwnRowVal('oneINVQtyBrkRow_<?php echo $cntr; ?>');" onkeypress="invTrnsUomFormKeyPress(event, 'oneINVQtyBrkRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;">   
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <input type="text" class="form-control invUmbEqQty" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_EquivQty" name="oneINVQtyBrkRow<?php echo $cntr; ?>_EquivQty" value="<?php
                                                   echo number_format($cnvrtdQty, 0);
                                                   ?>" style="width:100% !important;text-align: right;" readonly="true"> 
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <input type="text" class="form-control invUmbTtl" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_TtlVal" name="oneINVQtyBrkRow<?php echo $cntr; ?>_TtlVal" value="<?php
                                                   echo number_format($whlPrtVal * $unitPrce, 2);
                                                   ?>" style="width:100% !important;text-align: right;" readonly="true">                                                    
                                        </td>
                                    </tr>
                <?php } ?>
                            </tbody>
                            <tfoot>                                                            
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>TOTALS:</th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\" id=\"myCptrdQtyTtlBtn\">" . number_format($nwQty, 0,
                                                '.', ',') . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdQtyTtlVal" value="<?php echo $nwQty; ?>">
                                    </th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\" id=\"myCptrdUmValsTtlBtn\">" . number_format($ttlPrce,
                                                2, '.', ',') . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdUmValsTtlVal" value="<?php echo $ttlPrce; ?>">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div style="float:right;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="applyNewINVQtyValClinicHosp(<?php echo $sbmtdRwNum; ?>, 'myFormsModalxH', '<?php echo $rowIDAttrb; ?>');">Apply Changes</button>
                        </div>
                    </div>
                </div>
                <?php
            } 
            else if ($vwtyp == 6) {
                $itemID = isset($_POST['sbmtdItemID']) ? cleanInputData($_POST['sbmtdItemID']) : 0;
                $varTtlQty = isset($_POST['varTtlQty']) ? cleanInputData($_POST['varTtlQty']) : 0;
                $sbmtdCrncyNm = isset($_POST['sbmtdCrncyNm']) ? cleanInputData($_POST['sbmtdCrncyNm']) : -1;
                $rowIDAttrb = isset($_POST['rowIDAttrb']) ? cleanInputData($_POST['rowIDAttrb']) : "";
                $sbmtdCrncyID = getPssblValID($sbmtdCrncyNm, getLovID("Currencies"));
                //var_dump($_POST);
                $ttlQty = $varTtlQty;
                $nwQty = 0;
                $rmndPrtVal = $ttlQty;
                $ttlPrce = 0;
                $unitPrce = 0;
                $fnccurid = $sbmtdCrncyID;
                $fnccurnm = $sbmtdCrncyNm;
                if ($sbmtdCrncyID <= 0) {
                    $fnccurid = getOrgFuncCurID($orgID);
                    $fnccurnm = getPssblValNm($fnccurid);
                }
                ?>
                <div class="row">
                    <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRowIDAttrb" value="<?php echo $rowIDAttrb; ?>">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="oneINVQtyBrkDwnTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>UOM</th>
                                    <th style="text-align: right;">UOM QTY</th>
                                    <th style="text-align: right;">EQUIV BASE QTY</th>
                                    <th style="text-align: right;">Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                $crncyID = $fnccurid;
                                $crncyIDNm = $fnccurnm;
                                $whlPrtVal = 0;
                                $rngSum = 0;
                                $cnvrtdQty = 0;
                                $result = getUomBrkDwn($itemID);
                                while ($row = loc_db_fetch_array($result)) {
                                    $cntr++;
                                    $cnvsnFctr = $row[4];
                                    if ($rngSum == $ttlQty) {
                                        $cnvrtdQty = 0;
                                        $whlPrtVal = 0;
                                    } else {
                                        if ($rmndPrtVal >= $cnvsnFctr) {
                                            $whlPrt = (int) ($rmndPrtVal / $cnvsnFctr);
                                            $rmndPrt = $rmndPrtVal % $cnvsnFctr;
                                            if ($whlPrt > 0) {
                                                $whlPrtVal = $whlPrt;
                                                $cnvrtdQty = $whlPrtVal * $cnvsnFctr;
                                            }
                                            if ($rmndPrt > 0) {
                                                $rmndPrtVal = $rmndPrt;
                                            }
                                        } else {
                                            $cnvrtdQty = 0;
                                            $whlPrtVal = 0;
                                        }
                                        $rngSum = $rngSum + $cnvrtdQty;
                                    }
                                    if (strpos($rowIDAttrb, "CnsgnRcpt") !== FALSE || strpos($rowIDAttrb, "StockTrnsfr") !== FALSE) {
                                        $unitPrce = $cnvsnFctr * (float) ($row[7]);
                                    } else {
                                        $unitPrce = (float) ($row[5]);
                                    }
                                    $ttlPrce += $whlPrtVal * $unitPrce;
                                    $nwQty += $cnvrtdQty;
                                    ?>
                                    <tr id="oneINVQtyBrkRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                        <td class="lovtd">
                                            <span><?php echo $row[1] ?></span>
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_ItmUomID" value="<?php echo $row[2]; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_UntVal" value="<?php echo $unitPrce; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_CnvFctr" value="<?php echo $row[4]; ?>" style="width:100% !important;">                                                                                                
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <span><?php echo number_format($whlPrtVal, 0); ?></span> 
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <span><?php echo number_format($cnvrtdQty, 0); ?></span>
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <span><?php echo number_format($whlPrtVal * $unitPrce, 2); ?></span>                                                  
                                        </td>
                                    </tr>
                <?php } ?>
                            </tbody>
                            <tfoot>                                                            
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>TOTALS:</th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\" id=\"myCptrdQtyTtlBtn\">" . number_format($nwQty, 0,
                                                '.', ',') . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdQtyTtlVal" value="<?php echo $nwQty; ?>">
                                    </th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\" id=\"myCptrdUmValsTtlBtn\">" . number_format($ttlPrce,
                                                2, '.', ',') . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdUmValsTtlVal" value="<?php echo $ttlPrce; ?>">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div style="float:right;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
                <?php
            } 
        }
    }
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.form_date').datetimepicker({
            format: "d-M-yyyy",
            language: 'en',
            weekStart: 0,
            todayBtn: true,
            autoclose: true,
            todayHighlight: true,
            keyboardNavigation: true,
            startView: 2,
            minView: 2,
            maxView: 4,
            forceParse: true
        });
    });
</script>