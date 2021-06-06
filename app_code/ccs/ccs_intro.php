<?php

$usrID = $_SESSION['USRID'];
$prsnID = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];

/*echo $ftp_base_db_fldr; 
echo $fldrPrfx;
echo $app_url; */

$dateStr = getDB_Date_time();
//global $usrID; 
//session_start();
$menuItems = array("BoG Core Risk Indicators", "Mof Core Risk Indicators", "NIC Core Risk Indicators", "NPRA Core Risk Indicators", "SEC Core Risk Indicators",
    "Ghana Stock Exchange Risk Indicators", "FSC Periods", "FSC Categories & Indicators", "Regulated Institutions", "FSC Reports");
$menuImages = array("main_menu.jpg", "main_menu.jpg", "main_menu.jpg", "main_menu.jpg", "main_menu.jpg",
    "main_menu.jpg", "Calander.png", "invoice.ico", "Home.png", "report-icon-png.png");
$vwtyp1 = 0;
//echo $vwtyp1;
$mdlNm = "Catholic Church Sacrament";
$ModuleName = $mdlNm;

$dfltPrvldgs = array(
        "View Sacrament", 
		/* 1 */ "Add Sacrament",
        /* 2 */ "Edit Sacrament", "Delete Sacrament"
    );

$canview = test_prmssns("View Sacrament", "Catholic Church Sacrament"); //true;

$sqlStr = "";
$vwtyp = "0";
$qstr = "";
$dsply = "";
$actyp = "";
$srctyp = "";
$hdrid = "";
$trnsid = 0;
$action = "";
$srchFor = "";
$srchIn = "Name";
$PKeyID = -1;
$status = "";
$periodId = "";
$qStrtDte = "";
$qEndDte = "";
$sctnid = 0;
$catId = 0;




if (isset($formArray)) {
    if (count($formArray) > 0) {
        $vwtyp = isset($formArray['vtyp']) ? cleanInputData($formArray['vtyp']) : "0";
        $qstr = isset($formArray['q']) ? cleanInputData($formArray['q']) : '';
        $srctyp = isset($formArray['srctyp']) ? cleanInputData($formArray['srctyp']) : "0";
    } else {
        $vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "0";
        $srctyp = isset($_POST['srctyp']) ? cleanInputData($_POST['srctyp']) : "0";
    }
} else {
    $vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "0";
    $srctyp = isset($_POST['srctyp']) ? cleanInputData($_POST['srctyp']) : "0";
}

if (isset($_POST['hdrid'])) {
    $hdrid = cleanInputData($_POST['hdrid']);
}

if (isset($_POST['pKeyID'])) {
    $PKeyID = cleanInputData($_POST['pKeyID']);
}

if (isset($_POST['PKeyID'])) {
    $PKeyID = cleanInputData($_POST['PKeyID']);
}

if (isset($_POST['searchfor'])) {
    $srchFor = cleanInputData($_POST['searchfor']);
}

if (isset($_POST['searchin'])) {
    $srchIn = cleanInputData($_POST['searchin']);
}

if (isset($_POST['q'])) {
    $qstr = cleanInputData($_POST['q']);
}

if (isset($_POST['vtyp'])) {
    $vwtyp = cleanInputData($_POST['vtyp']);
}
if (isset($_POST['actyp'])) {
    $actyp = cleanInputData($_POST['actyp']);
}
if (isset($_POST['pg'])) {
    $pgNo = cleanInputData($_POST['pg']);
}

if (isset($_POST['status'])) {
    $status = cleanInputData($_POST['status']);
}

if (isset($_POST['qStrtDte'])) {
    $qStrtDte = cleanInputData($_POST['qStrtDte']);
}

if (isset($_POST['qEndDte'])) {
    $qEndDte = cleanInputData($_POST['qEndDte']);
}

$method = $_SERVER['REQUEST_METHOD'];
$requestBody = file_get_contents('php://input');

if (strpos($srchFor, "%") === FALSE) {
    $srchFor .= " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}


$canAdd = test_prmssns("Add Sacrament", "Catholic Church Sacrament"); //test_prmssns($dfltPrvldgs[8], $mdlNm);
$canEdt = test_prmssns("Edit Sacrament", "Catholic Church Sacrament"); //test_prmssns($dfltPrvldgs[9], $mdlNm);
$canDel = test_prmssns("Delete Sacrament", "Catholic Church Sacrament"); //$canEdt;
$canVwRcHstry = true; //test_prmssns("View Record History", $mdlNm);
$pkID = $PKeyID;
global $usrID;
global $orgID;

$cntent = "<div>
				<ul class=\"breadcrumb\" style=\"$breadCrmbBckclr\">
					<li onclick=\"openATab('#home', 'grp=40&typ=1');\">
                                                <i class=\"fa fa-home\" aria-hidden=\"true\"></i>
						<span style=\"text-decoration:none;\">Home</span>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>
					<li onclick=\"openATab('#allmodules', 'grp=40&typ=5');\">
						<span style=\"text-decoration:none;\">All Modules&nbsp;</span>
					</li>";


$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Last Created";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {//Details
                $rowCnt = deleteCathSacrament($PKeyID);
                if ($rowCnt > 0) {
                    echo "Sacrament Deleted Successfully";
                } else {
                    echo "Failed to Delete Sacrament";
                }
                exit();
            } else if ($actyp == 500) {//Plan Setup
                $rowCnt = deleteWitness($PKeyID);
                if ($rowCnt > 0) {
                    echo "Line Deleted Successfully";
                } else {
                    echo "Failed to Delete Line";
                }
                exit();
            }
        } else if ($qstr == "REVERSE") {
            if ($actyp == 1) {//Details
                $rowCnt = updateCathSacramentStatus($PKeyID, 'Incomplete');
                if ($rowCnt > 0) {
                    echo "Credit Analysis Reversed";
                } else {
                    echo "Failed to Reverse Credit Analysis";
                }
                exit();
            }
        } else if($qstr =="IMPORT AND EXPORT"){
            if ($srctyp == 5) {
                //Import Indicator Values
                header('Content-Type: application/json');
                $errMsg = "About to start importation of SACRAMENT";
                $arr_content['percent'] = 2;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> Message!<br/>$errMsg</span>";
                $arr_content['msgcount'] = "";

                $dataToSend = trim(cleanInputData($_POST['dataToSendBZ']), "|~");
                $dataToSendFC = trim(cleanInputData($_POST['dataToSendFC']), "|~");
                $dataToSendCF = trim(cleanInputData($_POST['dataToSendCF']), "|~");
                $dataToSendHM = trim(cleanInputData($_POST['dataToSendHM']), "|~");
                $dataToSendHMW = trim(cleanInputData($_POST['dataToSendHMW']), "|~");

                //var_dump($dataToSend);
                session_write_close();
                $affctd = 0;
                $errUploadMsg = "";
                $errUploadMsgFC = "";
                $errUploadMsgCF = "";
                $errUploadMsgHM = "";
                $errUploadMsgHMW = "";
                $errUploadMsgBZ = "";
                                
                $variousRows = array(); //BAPTISM
                $variousRowsFC = array(); //FIRST COMMUNION
                $variousRowsCF = array(); //CONFIRMATION
                $variousRowsHM = array(); //HOLY MATRIMONY
                $variousRowsHMW = array(); //HOLY MATRIMONY WITNESS
                                
                $total = 0; //BAPTISM
                $totalFC = 0; //FIRST COMMUNION                
                $totalCF = 0; //CONFIRMATION
                $totalHM = 0; //HOLY MATRIMONY
                $totalHMW = 0; //HOLY MATRIMONY WITNESS
                
                $tSumTotal = 0;
                
                //BAPTISM
                if ($dataToSend != "") {
                    $variousRows = explode("|", $dataToSend);
                    $total = count($variousRows);
                }

                //FIRST COMMUNION
                if ($dataToSendFC != "") {
                    $variousRowsFC = explode("|", $dataToSendFC);
                    $totalFC = count($variousRowsFC);
                }

                //CONFIRMATION                
                if ($dataToSendCF != "") {
                    $variousRowsCF = explode("|", $dataToSendCF);
                    $totalCF = count($variousRowsCF);
                }
                
                //HOLY MATRIMONY
                if ($dataToSendHM != "") {
                    $variousRowsHM = explode("|", $dataToSendHM);
                    $totalHM = count($variousRowsHM);
                }
                
                //HOLY MATRIMONY WITNESSES
                if ($dataToSendHMW != "") {
                    $variousRowsHMW = explode("|", $dataToSendHMW);
                    $totalHMW = count($variousRowsHMW);
                }
                
                $tSumTotal = $total + $totalFC + $totalCF + $totalHM + $totalHMW;

                if ($total > 0) {
                    for ($z = 0; $z < $total; $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 16) {
                            $transactionNo = cleanInputData1($crntRow[0]);
                            $otherNames = trim((cleanInputData1($crntRow[1])));
                            $lastName = trim(cleanInputData1($crntRow[2]));
                            $title = cleanInputData1($crntRow[3]);
                            $gender = trim(cleanInputData1($crntRow[4]));
                            $dob = cleanInputData1($crntRow[5]);
                            $pob = cleanInputData1($crntRow[6]);
                            $baptismDate = cleanInputData1($crntRow[7]);
                            $baptismPlace = cleanInputData1($crntRow[8]);
                            $baptismMode = cleanInputData1($crntRow[9]);
                            $minister = cleanInputData1($crntRow[10]);
                            $godParent = cleanInputData1($crntRow[11]);
                            $nameOfFather = cleanInputData1($crntRow[12]);
                            $nameOfMother = cleanInputData1($crntRow[13]);
                            $religionOfFather = cleanInputData1($crntRow[14]);
                            $religionOfMother = cleanInputData1($crntRow[15]);
							
                            if ($z == 0) {
                                if (strtoupper($transactionNo) == "BAPTISM NO" && strtoupper($otherNames) == "FIRST NAME" && strtoupper($lastName) == "SURNAME" && strtoupper($title) == "TITLE" 
                                    && strtoupper($gender) == "GENDER" && strtoupper($dob) == "DATE OF BIRTH" && strtoupper($pob) == "PLACE OF BIRTH" && strtoupper($baptismDate) == "DATE OF BAPTISM" 
                                    && strtoupper($baptismPlace) == "PLACE OF BAPTISM" && strtoupper($baptismMode) == "BAPTISM MODE" && strtoupper($minister) == "MINISTER" && strtoupper($godParent) == "GODPARENT"
                                    && strtoupper($nameOfFather) == "FATHER" && strtoupper($nameOfMother) == "MOTHER" && strtoupper($religionOfFather) == "RELIGION OF FATHER" 
                                    && strtoupper($religionOfMother) == "RELIGION OF MOTHER") {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!" . $transactionNo . $otherNames . $lastName . $title . $gender;
                                    $arr_content['msgcount'] = $total;
                                    //$arr_content['cstmerrors'] = "<span style=\"color:red;\">".$errUploadMsg."</i></span>";
                                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                                    break;
                                }
                            }

                            $cnt = 0;
	
                            if (!($transactionNo)) {//BAPTISM NO
                                $errUploadMsg = $errUploadMsg . "</br>" . "Cell A" . ($z + 1) . " data import failed! BAPTISM NO must exist";
                                $cnt = $cnt + 1;
                            }

                            if (!($otherNames)) {
                                $errUploadMsg = $errUploadMsg . "</br>" . "Cell B" . ($z + 1) . " data import failed! No FIRST NAME provided";
                                $cnt = $cnt + 1;
                            }

                            if (!($lastName)) {
                                $errUploadMsg = $errUploadMsg . "</br>" . "Cell C" . ($z + 1) . " data import failed! No SURNAME provided";
                                $cnt = $cnt + 1;
                            }

                            /*if (!($dob)) {
                                $errUploadMsg = $errUploadMsg . "</br>" . "Cell F" . ($z + 1) . " data import failed! No DATE OF BIRTH provided";
                                $cnt = $cnt + 1;
                            }*/

                            if ($minister) {
                                if (trim($minister) == "") {
                                    $errUploadMsg = $errUploadMsg . "</br>" . "Cell K" . ($z + 1) . " data import failed! No MINISTER provided";
                                    $cnt = $cnt + 1;
                                } else {
                                    if ($cnt <= 0) { //IF ALL COLUMN REQUIREMENTS ARE MET	

                                        $rcExstCnt = doesBptsmExists($transactionNo);
                                        if($rcExstCnt > 0){
                                            //UPDATE
                                            $bptsmID = getBptsmIDFromSysCode($transactionNo);
                                            $affctd += updateCathBaptismImport($bptsmID, $transactionNo, $baptismDate, $baptismPlace, $baptismMode, $minister, $godParent, $lastName, 
                                                    $otherNames, $title, $gender, $dob, $pob, $nameOfFather, $religionOfFather, $nameOfMother, $religionOfMother, 
                                                    $usrID, $dateStr);	
                                        } else {
                                            //INSERT
                                            $bptsmID = getCathBaptismID();
                                            $affctd += insertCathBaptismImport($bptsmID, $transactionNo, $baptismDate, $baptismPlace, $baptismMode, $minister, $godParent, $lastName, 
                                                    $otherNames, $title, $gender, $dob, $pob, $nameOfFather, $religionOfFather, $nameOfMother, $religionOfMother,  
                                                    $usrID, $dateStr, $orgID);

                                        }

                                    }
				}
                            } else {
                                $errUploadMsg = $errUploadMsg . "</br>" . "Cell K" . ($z + 1) . " data import failed! No MINISTER provided";
                                $cnt = $cnt + 1;
                            }
                        }
                        $percent = round((($z + 1) / $tSumTotal) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing BAPTISM...Please Wait..." . ($z + 1) . " out of " . $total . " Record(s) imported.";
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['cstmerrors'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                }
                
                //FIRST COMMUNION
                $affctdFC = 0;
                $errMsg = "About to start importation of FIRST COMMUNION";
                $arr_content['percent'] = 2;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> Message!<br/>$errMsg</span>";
                $arr_content['msgcount'] = "";
                if ($totalFC > 0) {
                    for ($z = 0; $z < $totalFC; $z++) {
                        $crntRowFC = explode("~", $variousRowsFC[$z]);
                        if (count($crntRowFC) == 4) {
                            $transactionNoFC = cleanInputData1($crntRowFC[0]);
                            $firstCommMinisterFC = trim((cleanInputData1($crntRowFC[1])));
                            $firstCommDateFC = trim(cleanInputData1($crntRowFC[2]));
                            $firstCommPlaceFC = cleanInputData1($crntRowFC[3]);
							
                            if ($z == 0) {
                                if (strtoupper($transactionNoFC) == "BAPTISM NO" && strtoupper($firstCommMinisterFC) == "MINISTER" 
					&& strtoupper($firstCommDateFC) == "DATE OF FIRST COMMUNION" && strtoupper($firstCommPlaceFC) == "PLACE OF FIRST COMMUNION") {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!" . $transactionNoFC . $firstCommMinisterFC . $firstCommDateFC . $firstCommPlaceFC;
                                    $arr_content['msgcount'] = $totalFC;
                                    //$arr_content['cstmerrors'] = "<span style=\"color:red;\">".$errUploadMsgFC."</i></span>";
                                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                                    break;
                                }
                            }

                            $cnt = 0;
	
                            /*if (!($transactionNoFC)) {//BAPTISM NO
                                $errUploadMsgFC = $errUploadMsgFC . "</br>" . "Cell A" . ($z + 1) . " data import failed! BAPTISM NO must exist";
                                $cnt = $cnt + 1;
                            }*/

                            if ($transactionNoFC) {
                                if (trim($transactionNoFC) == "") {
                                    $errUploadMsgFC = $errUploadMsgFC . "</br>" . "Cell A" . ($z + 1) . " data import failed! No BAPTISM NO must exist";
                                    $cnt = $cnt + 1;
                                } else {
				    if ($cnt <= 0) { //IF ALL COLUMN REQUIREMENTS ARE MET
                                        $rcExstCnt = doesBptsmExists($transactionNoFC);
                                        if($rcExstCnt > 0){
                                                //UPDATE
                                                $bptsmID = getBptsmIDFromSysCode($transactionNoFC);
                                                $affctdFC += updateCathFirstCommunionImport($bptsmID, $firstCommMinisterFC, $firstCommDateFC, $firstCommPlaceFC,  $usrID, $dateStr);
                                        } else {
                                                $errUploadMsgFC = $errUploadMsgFC . "</br>" . "Cell A" . ($z + 1) . " data import failed! BAPTISM NO is invalid for FIRST COMMUNION exist";
                                                $cnt = $cnt + 1;
                                        }
                                        
                                    }
				}
                            } else {
                                $errUploadMsgFC = $errUploadMsgFC . "</br>" . "Cell A" . ($z + 1) . " data import failed! No BAPTISM NO provided";
                                $cnt = $cnt + 1;
                            }
                        }
                        $percent = round((($z + 1 + $total) / $tSumTotal) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing FIRST COMMUNION...Please Wait..." . ($z + 1) . " out of " . $totalFC . " Record(s) imported.";
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['cstmerrors'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                }

                //CONFIRMATION
                $affctdCF = 0;
                $errMsg = "About to start importation of CONFIRMATIONS";
                $arr_content['percent'] = 2;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> Message!<br/>$errMsg</span>";
                $arr_content['msgcount'] = "";
                if ($totalCF > 0) {
                    for ($z = 0; $z < $totalCF; $z++) {
                        $crntRowCF = explode("~", $variousRowsCF[$z]);
                        if (count($crntRowCF) == 6) {
                            $transactionNoCF = cleanInputData1($crntRowCF[0]);
                            $cnfrmtnNameCF = trim((cleanInputData1($crntRowCF[1])));
                            $cnfrmtnGodParentCF = trim(cleanInputData1($crntRowCF[2]));
                            $cnfrmtnMinisterCF = cleanInputData1($crntRowCF[3]);
			    $cnfrmtnPlaceCF = trim(cleanInputData1($crntRowCF[4]));
                            $cnfrmtnDateCF = cleanInputData1($crntRowCF[5]);
							
                            if ($z == 0) {
                                if (strtoupper($transactionNoCF) == "BAPTISM NO" && strtoupper($cnfrmtnNameCF) == "CONFIRMATION NAME" 
                                        && strtoupper($cnfrmtnGodParentCF) == "GODPARENT" && strtoupper($cnfrmtnMinisterCF) == "MINISTER"
                                        && strtoupper($cnfrmtnPlaceCF) == "CONFIRMATION PLACE" && strtoupper($cnfrmtnDateCF) == "DATE OF CONFIRMATION") {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!" . $transactionNoCF . $cnfrmtnNameCF . $cnfrmtnGodParentCF . $cnfrmtnMinisterCF;
                                    $arr_content['msgcount'] = $totalCF;
                                    //$arr_content['cstmerrors'] = "<span style=\"color:red;\">".$errUploadMsgCF."</i></span>";
                                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                                    break;
                                }
                            }

                            $cnt = 0;
	
                            /*if (!($transactionNoCF)) {//BAPTISM NO
                                $errUploadMsgCF = $errUploadMsgCF . "</br>" . "Cell A" . ($z + 1) . " data import failed! BAPTISM NO must exist";
                                $cnt = $cnt + 1;
                            }*/

                            if ($transactionNoCF) {
                                if (trim($transactionNoCF) == "") {
                                    $errUploadMsgCF = $errUploadMsgCF . "</br>" . "Cell A" . ($z + 1) . " data import failed! No BAPTISM NO must exist";
                                    $cnt = $cnt + 1;
                                } else {
				    if ($cnt <= 0) { //IF ALL COLUMN REQUIREMENTS ARE MET	
										
                                        $rcExstCnt = doesBptsmExists($transactionNoCF);
                                        if($rcExstCnt > 0){
                                            //UPDATE
                                            $bptsmID = getBptsmIDFromSysCode($transactionNoCF);
                                            $affctdCF += updateCathConfirmationImport($bptsmID, $cnfrmtnNameCF, $cnfrmtnGodParentCF, $cnfrmtnMinisterCF, $cnfrmtnPlaceCF, $cnfrmtnDateCF, $usrID, $dateStr);
                                        } else {
                                            $errUploadMsgCF = $errUploadMsgCF . "</br>" . "Cell A" . ($z + 1) . " data import failed! BAPTISM NO is invalid for CONFIRMATION exist";
                                            $cnt = $cnt + 1;
                                        }
                                        
                                    }
				}
                            } else {
                                $errUploadMsgCF = $errUploadMsgCF . "</br>" . "Cell A" . ($z + 1) . " data import failed! No BAPTISM NO provided";
                                $cnt = $cnt + 1;
                            }
                        }
                        $percent = round((($z + 1 + $total + $totalFC) / $tSumTotal) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing CONFIRMATION...Please Wait..." . ($z + 1) . " out of " . $totalCF . " Record(s) imported.";
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['cstmerrors'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                }
                
                //HOLY MATRIMONY
                $affctdHM = 0;
                $errMsg = "About to start importation of HOLY MATRIMONY";
                $arr_content['percent'] = 2;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> Message!<br/>$errMsg</span>";
                $arr_content['msgcount'] = "";
                if ($totalHM > 0) {
                    for ($z = 0; $z < $totalHM; $z++) {
                        $crntRowHM = explode("~", $variousRowsHM[$z]);
                        if (count($crntRowHM) == 16) {
                            $transactionNoHM = cleanInputData1($crntRowHM[0]);
                            $mtrmnyPlaceHM = trim((cleanInputData1($crntRowHM[1])));
                            $mtrmnyDateHM = trim(cleanInputData1($crntRowHM[2]));
                            $mtrmnyChurchHM = cleanInputData1($crntRowHM[3]);
			    $mtrmnyMinisterHM = trim(cleanInputData1($crntRowHM[4]));
                            $mtrmnyDispensationHM = cleanInputData1($crntRowHM[5]);
                            $mtrmnyLocalSpouseBaptismNoHM = cleanInputData1($crntRowHM[6]);
                            $extSpouseOtherNamesHM = cleanInputData1($crntRowHM[7]);
                            $extSpouseLastNameHM = cleanInputData1($crntRowHM[8]);
                            $extSpouseGenderHM = cleanInputData1($crntRowHM[9]);
                            $extSpouseDobHM = cleanInputData1($crntRowHM[10]);
                            $extSpousePobHM = cleanInputData1($crntRowHM[11]);
                            $extSpouseNameOfFatherHM = cleanInputData1($crntRowHM[12]);
                            $extSpouseNameOfMotherHM = cleanInputData1($crntRowHM[13]);
                            $extSpouseBaptismDateHM = cleanInputData1($crntRowHM[14]);
                            $extSpouseBaptismPlaceHM = cleanInputData1($crntRowHM[15]);
							
                            if ($z == 0) {
                                if (strtoupper($transactionNoHM) == "BAPTISM NO" && strtoupper($mtrmnyPlaceHM) == "MATRIMONY PLACE" 
                                        && strtoupper($mtrmnyDateHM) == "MATRIMONY DATE" && strtoupper($mtrmnyChurchHM) == "CHURCH"
                                        && strtoupper($mtrmnyMinisterHM) == "MINISTER" && strtoupper($mtrmnyDispensationHM) == "DISPENSATION"
                                        && strtoupper($mtrmnyLocalSpouseBaptismNoHM) == "LOCAL SPOUSE BAPTISM NUMBER" && strtoupper($extSpouseOtherNamesHM) == "EXT-SPOUSE FIRST NAME"
                                        && strtoupper($extSpouseLastNameHM) == "EXT-SPOUSE SURNAME" && strtoupper($extSpouseGenderHM) == "EXT-SPOUSE GENDER"
                                        && strtoupper($extSpouseDobHM) == "EXT-SPOUSE DATE OF BIRTH" && strtoupper($extSpousePobHM) == "EXT-SPOUSE PLACE OF BIRTH"
                                        && strtoupper($extSpouseNameOfFatherHM) == "EXT-SPOUSE FATHER" && strtoupper($extSpouseNameOfMotherHM) == "EXT-SPOUSE MOTHER"
                                        && strtoupper($extSpouseBaptismDateHM) == "EXT-SPOUSE BAPTISM DATE" && strtoupper($extSpouseBaptismPlaceHM) == "EXT-SPOUSE BAPTISM PLACE") {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!" . $transactionNoHM . $mtrmnyPlaceHM . $mtrmnyDateHM . $mtrmnyChurchHM;
                                    $arr_content['msgcount'] = $totalHM;
                                    //$arr_content['cstmerrors'] = "<span style=\"color:red;\">".$errUploadMsgHM."</i></span>";
                                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                                    break;
                                }
                            }

                            $cnt = 0;
	
                            /*if (!($transactionNoHM)) {//BAPTISM NO
                                $errUploadMsgHM = $errUploadMsgHM . "</br>" . "Cell A" . ($z + 1) . " data import failed! BAPTISM NO must exist";
                                $cnt = $cnt + 1;
                            }*/

                            if ($transactionNoHM) {
                                if (trim($transactionNoHM) == "") {
                                    $errUploadMsgHM = $errUploadMsgHM . "</br>" . "Cell A" . ($z + 1) . " data import failed! No BAPTISM NO must exist";
                                    $cnt = $cnt + 1;
                                } else {
				    if ($cnt <= 0) { //IF ALL COLUMN REQUIREMENTS ARE MET	
										
                                        $rcExstCnt = doesBptsmExists($transactionNoHM);
                                        if($rcExstCnt > 0){
                                            //UPDATE
                                            $bptsmID = getBptsmIDFromSysCode($transactionNoHM);

                                            $affctdHM += updateCathHolyMatrimonyImport($bptsmID, $mtrmnyPlaceHM, $mtrmnyDateHM, $mtrmnyChurchHM, $mtrmnyMinisterHM, $mtrmnyDispensationHM, 
                                                    $mtrmnyLocalSpouseBaptismNoHM, $extSpouseLastNameHM, $extSpouseOtherNamesHM, $extSpouseGenderHM, $extSpouseDobHM, $extSpousePobHM, 
                                                    $extSpouseNameOfFatherHM, $extSpouseNameOfMotherHM, $extSpouseBaptismDateHM, $extSpouseBaptismPlaceHM,  $usrID, $dateStr);

                                        } else {
                                            $errUploadMsgHM = $errUploadMsgHM . "</br>" . "Cell A" . ($z + 1) . " data import failed! BAPTISM NO is invalid for HOLY MATRIMONY exist";
                                            $cnt = $cnt + 1;
                                        }
                                        
                                    }
				}
                            } else {
                                $errUploadMsgHM = $errUploadMsgHM . "</br>" . "Cell A" . ($z + 1) . " data import failed! No BAPTISM NO provided";
                                $cnt = $cnt + 1;
                            }
                        } else {
                            $errUploadMsgHM = $errUploadMsgHM . "</br>" . "not 16 records but ".count($crntRowHM);
                            $cnt = $cnt + 1;
                        }
                        $percent = round((($z + 1 + $total + $totalFC + $totalCF) / $tSumTotal) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing HOLY MATRIMONY...Please Wait..." . ($z + 1) . " out of " . $totalHM . " Record(s) imported.";
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['cstmerrors'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                }
                
                //HOLY MATRIMONY WITNESS
                $errMsg = "About to start importation of HOLY MATRIMONY WITNESSES";
                $arr_content['percent'] = 2;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> Message!<br/>$errMsg</span>";
                $arr_content['msgcount'] = "";

                $affctdHMW = 0;
                //$errUploadMsgHMW = "";
                if ($totalHMW > 0) {
                    for ($z = 0; $z < $totalHMW; $z++) {
                        $crntRowHMW = explode("~", $variousRowsHMW[$z]);
                            if (count($crntRowHMW) == 3) {
                                $transactionNoHMW = cleanInputData1($crntRowHMW[0]);
                                $witnessHMW = trim(cleanInputData1($crntRowHMW[1]));
                                $witnessForHMW = cleanInputData1($crntRowHMW[2]);
                            }

                            if ($z == 0) {
                                if (strtoupper($transactionNoHMW) == "BAPTISM NO" && strtoupper($witnessHMW) == "WITNESS NAME" && strtoupper($witnessForHMW) == "WITNESS FOR") {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Invalid Worksheet HOLY MATRIMONY WITNESSES!";
                                    $arr_content['msgcount'] = $totalHMW;
                                    //$arr_content['cstmerrors'] = "<span style=\"color:red;\">".$errUploadMsgHMW."</i></span>";
                                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                                    break;
                                }
                            }

                            $cnt = 0;

                            if ($transactionNoHMW) {
                                if (trim($transactionNoHMW) == "") {
                                    $errUploadMsgHMW = $errUploadMsgHMW . "</br>" . "Cell A" . ($z + 1) . " data import failed! No BAPTISM NO must exist";
                                    $cnt = $cnt + 1;
                                } else {
                                    if ($cnt <= 0) { //IF ALL COLUMN REQUIREMENTS ARE MET	
										
                                        $rcExstCnt = doesBptsmExists($transactionNoHMW);
                                        if($rcExstCnt > 0){
                                            //UPDATE
                                            $bptsmID = getBptsmIDFromSysCode($transactionNoHMW);
                                            
                                            $wtnsRecExtCnt = checkWtnessExstnc($transactionNoHMW, $witnessHMW);
                                            if($wtnsRecExtCnt > 0){
                                                //update
                                                $affctdHMW += updateWitnessImport($bptsmID, $witnessHMW, $witnessForHMW, $usrID, $dateStr);
                                            } else {
                                                //insert
                                                $affctdHMW += insertWitnessImport($bptsmID, $witnessHMW, $witnessForHMW, $usrID, $dateStr);
                                            }

                                        } else {
                                            $errUploadMsgHMW = $errUploadMsgHMW . "</br>" . "Cell A" . ($z + 1) . " data import failed! BAPTISM NO is invalid for HOLY MATRIMONY WITNESSES exist";
                                            $cnt = $cnt + 1;
                                        }
                                        
                                    }
				}
                            } else {
				$errUploadMsgHMW = $errUploadMsgHMW . "</br>" . "Cell A" . ($z + 1) . " data import failed! No BAPTISM NO provided";
                                $cnt = $cnt + 1;
                            }

                        $percent = round((($z + 1 +  + $total + $totalFC + $totalCF + $totalHM) / $tSumTotal) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
			   if(trim($errUploadMsgFC) === ""){
                                $errUploadMsgFC = "<span style=\"color:red;\"><i>" . $errUploadMsgFC . "</i></span>";
                            } else {
                                $errUploadMsgFC = "</br><span style=\"color:red;\">ERRORS: <i>" . $errUploadMsgFC . "</i></span>";
                            }
							
			    if(trim($errUploadMsgCF) === ""){
                                $errUploadMsgCF = "<span style=\"color:red;\"><i>" . $errUploadMsgCF . "</i></span>";
                            } else {
                                $errUploadMsgCF = "</br><span style=\"color:red;\">ERRORS: <i>" . $errUploadMsgCF . "</i></span>";
                            }
							
			    if(trim($errUploadMsgHM) === ""){
                                $errUploadMsgHM = "<span style=\"color:red;\"><i>" . $errUploadMsgHM . "</i></span>";
                            } else {
                                $errUploadMsgHM = "</br><span style=\"color:red;\">ERRORS: <i>" . $errUploadMsgHM . "</i></span>";
                            }
							
                            if(trim($errUploadMsgHMW) === ""){
                                $errUploadMsgHMW = "<span style=\"color:red;\"><i>" . $errUploadMsgHMW . "</i></span>";
                            } else {
                                $errUploadMsgHMW = "</br><span style=\"color:red;\">ERRORS: <i>" . $errUploadMsgHMW . "</i></span>";
                            }

                            if(trim($errUploadMsg) === ""){
                                $errUploadMsg = "<span style=\"color:red;\"><i>" . $errUploadMsg . "</i></span>";
                            } else {
                                $errUploadMsg = "</br><span style=\"color:red;\">ERRORS: <i>" . $errUploadMsg . "</i></span>";
                            }

                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!...".
                                    "</br>BAPTISM: " . $affctd . " out of " . $total . " Record(s) imported.". $errUploadMsg . "</br>".
                                    "</br>FIRST COMMUNION: " . $affctdFC . " out of " . $totalFC . " Record(s) imported.". $errUploadMsgFC . "</br>".
                                    "</br>CONFIRMATION: " . $affctdCF . " out of " . $totalCF . " Record(s) imported.". $errUploadMsgCF . "</br>".
                                    "</br>HOLY MATRIMONY: " . $affctdHM . " out of " . $totalHM . " Record(s) imported.". $errUploadMsgHM . "</br>".
                                    "</br>WITNESSES: " . $affctdHMW . " out of " . $totalHMW . " Record(s) imported.". $errUploadMsgHMW;
                            $arr_content['msgcount'] = $totalHMW;
                            //$arr_content['cstmerrors'] = "<span style=\"color:red;\">" . $errUploadMsg . "</i></span>";
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing WITNESSES...Please Wait..." . ($z + 1) . " out of " . $totalHMW . " Record(s) imported.";
                            //$arr_content['cstmerrors'] = "<span style=\"color:red;\">" . $errUploadMsg . "</i></span>";
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['cstmerrors'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho", json_encode($arr_content));
                }
                exit();
            } 
            else if ($srctyp == 6) {
                //Checked Importing Process Status
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prsnlcstmrimprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        unlink($file);
                    }
                } else {
                    echo json_encode(array("percent" => null, "message" => null));
                }
                exit();
            } 
            else if ($srctyp == 7) {
                //Export SACRAMENT
                //header('Content-Type: application/json');
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                $searchAll = true;

                $srchFor = isset($_POST['pSrchFor']) ? cleanInputData($_POST['pSrchFor']) : '';
                $srchIn = isset($_POST['pSrchIn']) ? cleanInputData($_POST['pSrchIn']) : 'Both';
                $sortBy = isset($_POST['pSortBy']) ? cleanInputData($_POST['pSortBy']) : 'Baptism No.';

                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $dteNmHis = date('His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/sacrament_" . $dteNmHis . "_" . $rndm . ".xlsx"; 

                    $dwnldUrl = $app_url . "dwnlds/tmp/sacrament_" . $dteNmHis . "_" . $rndm . ".xlsx";

                    //if ($limit_size <= 0) {
                    if ($limit_size < 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Indicator Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_SacramentExprt_progress.rho", json_encode($arr_content));

                        //fclose($opndfile);
                        exit();
                    }

                    $z = 0;
                    $j = 0;
                    $v = 0;
                    $u = 0;
                    $w = 0;
                    $y = 0;
                    
                    
                    $writer = new XLSXWriter();
                    
                    if($inptNum == 1 || $limit_size == 0){
                        //WRITE TO SHEET 1 - BAPTIZM
                        $hdngsBZ = array(
                        "BAPTISM NO" => "string", "FIRST NAME" => "string", "SURNAME" => "string", "TITLE" => "string", "GENDER" => "string", "DATE OF BIRTH" => "string", 
                        "PLACE OF BIRTH" => "string", "DATE OF BAPTISM" => "string",  "PLACE OF BAPTISM" => "string", "BAPTISM MODE" => "string",  "MINISTER" => "string",  
                        "GODPARENT" => "string", "FATHER" => "string", "MOTHER" => "string", "RELIGION OF FATHER" => "string", "RELIGION OF MOTHER" => "string");
                         $writer->writeSheetHeader('Baptism', $hdngsBZ, $suppress_header_row = false);
                         
                         //WRITE TO WORKSHEET 2 - FIRST COMMUNION
                         $hdngsFC = array(
                        "BAPTISM NO" => "string", "MINISTER" => "string", "DATE OF FIRST COMMUNION" => "string", "PLACE OF FIRST COMMUNION" => "string");
                        $writer->writeSheetHeader('1st Communion', $hdngsFC, $suppress_header_row = false);
                        
                        //WRITE TO SHEET 3 - CONFIRATION
                        $hdngsCF = array(
                        "BAPTISM NO" => "string", "CONFIRMATION NAME" => "string", "GODPARENT" => "string", "MINISTER" => "string", "CONFIRMATION PLACE" => "string", "DATE OF CONFIRMATION" => "string");
                        $writer->writeSheetHeader('Confirmation', $hdngsCF, $suppress_header_row = false);
                        
                        //WRITE TO WORKSHEET 4 - HOLY MATRIMONY
                        $hdngsHM = array(
                        "BAPTISM NO" => "string", "MATRIMONY PLACE" => "string", "MATRIMONY DATE" => "string", "CHURCH" => "string", "MINISTER" => "string", "DISPENSATION" => "string",
                        "LOCAL SPOUSE BAPTISM NUMBER" => "string", "EXT-SPOUSE FIRST NAME" => "string", "EXT-SPOUSE SURNAME" => "string", "EXT-SPOUSE GENDER" => "string",
                        "EXT-SPOUSE DATE OF BIRTH" => "string", "EXT-SPOUSE PLACE OF BIRTH" => "string", "EXT-SPOUSE FATHER" => "string", "EXT-SPOUSE MOTHER" => "string",
                        "EXT-SPOUSE BAPTISM DATE" => "string", "EXT-SPOUSE BAPTISM PLACE" => "string");
                        $writer->writeSheetHeader('Holy Matrimony', $hdngsHM, $suppress_header_row = false);
                        
                        //WRITE TO WORKSHEET 5 - WITNESSES
                        $hdngsHMW = array(
                            "BAPTISM NO" => "string", "WITNESS NAME" => "string", "WITNESS FOR" => "string");
                        $writer->writeSheetHeader('Holy Matrimony Witnesses', $hdngsHMW, $suppress_header_row = false);
                        
                        $nwFileNm = $fldrPrfx . "dwnlds/tmp/template_" . $dteNmHis . "_" . $rndm . ".xlsx";
                        $dwnldUrl = $app_url . "dwnlds/tmp/template_" . $dteNmHis . "_" . $rndm . ".xlsx";
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Indicator Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_SacramentExprt_progress.rho", json_encode($arr_content));

                        $writer->writeToFile($nwFileNm);
                        exit();
                    }
                    
                    //WRITE TO WORKSHEET 1 - BAPTISM
                    $hdngsBZ = array(
                        "BAPTISM NO" => "string", "FIRST NAME" => "string", "SURNAME" => "string", "TITLE" => "string", "GENDER" => "string", "DATE OF BIRTH" => "string", 
                        "PLACE OF BIRTH" => "string", "DATE OF BAPTISM" => "string",  "PLACE OF BAPTISM" => "string", "BAPTISM MODE" => "string",  "MINISTER" => "string",  
                        "GODPARENT" => "string", "FATHER" => "string", "MOTHER" => "string", "RELIGION OF FATHER" => "string", "RELIGION OF MOTHER" => "string");
                    
                    $writer->writeSheetHeader('Baptism', $hdngsBZ, $suppress_header_row = false);

                    $resultBZ = get_CathSacrament($srchFor, $srchIn, 0, $limit_size, $sortBy);
                    $totalBZ = loc_db_num_rows($resultBZ);
                    
                    $stylesBZ = array( ['halign'=>'none'],['halign'=>'none'],['halign'=>'none'],['halign'=>'none'],['halign'=>'none'],['halign'=>'none'],['halign'=>'none'],['halign'=>'none'],
                                        ['halign'=>'none'],['halign'=>'none'],['halign'=>'none'],['halign'=>'none'],['halign'=>'none'],['halign'=>'none'],['halign'=>'none'],['halign'=>'none']);

                    //$fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($resultBZ)) {
                        //$crntRw = array($row[0], $row[1], $row[2], $row[3], $row[4]);
                        $writer->writeSheetRow('Baptism', array($row[17], $row[2], $row[1], $row[16], $row[14], $row[6], 
                                                                $row[5], $row[7], $row[8], $row[10], $row[9], 
                                                                $row[11], $row[3], $row[4], $row[12], $row[13]), $stylesBZ);

                        $percent = round((($z + 1) / $totalBZ) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z + 1) . " out of " . $totalBZ . " Record(s) exported.</span>";
                            $arr_content['msgcount'] = $totalBZ;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Reports/Processes...Please Wait..." . ($z + 1) . " out of " . $totalBZ . " Record(s) exported.</span>";
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_SacramentExprt_progress.rho", json_encode($arr_content)); 
                        $z++;
                        $j++;
                    }

                    //WRITE TO WORKSHEET 2 - 1ST COMMUNION
                    $hdngsFC = array(
                        "BAPTISM NO" => "string", "MINISTER" => "string", "DATE OF FIRST COMMUNION" => "string", "PLACE OF FIRST COMMUNION" => "string");

                    $writer->writeSheetHeader('1st Communion', $hdngsFC, $suppress_header_row = false);
                    //$resultDrvrs = get_PsbIndctrTrnsDriversExport($srchFor, $srchIn, $hdrid, $limit_size);
                    
                    $resultFC = get_FirstCommunionExport($srchFor, $srchIn,  0, $limit_size, $sortBy);
                    $totalFC = loc_db_num_rows($resultFC);
                    while ($row = loc_db_fetch_array($resultFC)) {
                        $writer->writeSheetRow('1st Communion', array($row[5], $row[2], $row[3], $row[4]));
                        $v++;
                    }
                    
                    //WRITE TO WORKSHEET 3 - CONFIRMATION
                    $hdngsCF = array(
                        "BAPTISM NO" => "string", "CONFIRMATION NAME" => "string", "GODPARENT" => "string", "MINISTER" => "string", "CONFIRMATION PLACE" => "string", "DATE OF CONFIRMATION" => "string");

                    $writer->writeSheetHeader('Confirmation', $hdngsCF, $suppress_header_row = false);
                    //$resultDrvrs = get_PsbIndctrTrnsDriversExport($srchFor, $srchIn, $hdrid, $limit_size);
                    
                    $resultCF = get_ConfirmationExport($srchFor, $srchIn,  0, $limit_size, $sortBy);
                    $totalCF = loc_db_num_rows($resultCF);
                    while ($row = loc_db_fetch_array($resultCF)) {
                        $writer->writeSheetRow('Confirmation', array($row[7], $row[2], $row[3], $row[4], $row[5], $row[6]));
                        $u++;
                    }
                    
                    //WRITE TO WORKSHEET 4 - HOLY MATRIMONY
                    $hdngsHM = array(
                        "BAPTISM NO" => "string", "MATRIMONY PLACE" => "string", "MATRIMONY DATE" => "string", "CHURCH" => "string", "MINISTER" => "string", "DISPENSATION" => "string",
                        "LOCAL SPOUSE BAPTISM NUMBER" => "string", "EXT-SPOUSE FIRST NAME" => "string", "EXT-SPOUSE SURNAME" => "string", "EXT-SPOUSE GENDER" => "string",
                        "EXT-SPOUSE DATE OF BIRTH" => "string", "EXT-SPOUSE PLACE OF BIRTH" => "string", "EXT-SPOUSE FATHER" => "string", "EXT-SPOUSE MOTHER" => "string",
                        "EXT-SPOUSE BAPTISM DATE" => "string", "EXT-SPOUSE BAPTISM PLACE" => "string");

                    $writer->writeSheetHeader('Holy Matrimony', $hdngsHM, $suppress_header_row = false);
                    //$resultDrvrs = get_PsbIndctrTrnsDriversExport($srchFor, $srchIn, $hdrid, $limit_size);
                    
                    $resultHM = get_HolyMtrmnyExport($srchFor, $srchIn,  0, $limit_size, $sortBy);
                    $totalHM = loc_db_num_rows($resultHM);
                    while ($row = loc_db_fetch_array($resultHM)) {
                        $locSpouseBptsmNo = getGnrlRecNm("ccs.baptism", "bptsm_id", "bptsm_sys_code", $row[8]);
                        $writer->writeSheetRow('Holy Matrimony', array($row[17], $row[11], $row[12], $row[14], $row[13], $row[15],
                                                                        $locSpouseBptsmNo, $row[2], $row[3], $row[16], 
                                                                        $row[4], $row[5], $row[6], $row[7],
                                                                        $row[9], $row[10]));
                        $w++;
                    }
                    
                    //WRITE TO WORKSHEET 5 - WITNESSES
                    $hdngsHMW = array(
                        "BAPTISM NO" => "string", "WITNESS NAME" => "string", "WITNESS FOR" => "string");

                    $writer->writeSheetHeader('Holy Matrimony Witnesses', $hdngsHMW, $suppress_header_row = false);
                    
                    $resultHMW = get_HolyMtrmnyWitnessExport($srchFor, $srchIn,  0, $limit_size, $sortBy);
                    $totalHMW = loc_db_num_rows($resultHMW);
                    while ($row = loc_db_fetch_array($resultHMW)) {
                        $writer->writeSheetRow('Holy Matrimony Witnesses', array($row[4], $row[2], $row[3]));
                        $y++;
                    }
                    
                    $writer->writeToFile($nwFileNm);
                    exit;
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_SacramentExprt_progress.rho", json_encode($arr_content)); 
                }
                exit();
            } 
            else if ($srctyp == 8) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_SacramentExprt_progress.rho"; 
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        unlink($file);
                    }
                } else {
                    echo json_encode(array("percent" => 0, "message" => '<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Not Started</span>'));
                }
                exit();
            } 
        }
        else if ($qstr == "UPDATE") {
            //var_dump($_POST);
            if ($actyp == 1) {//Header
                $bptsmID = isset($_POST['bptsmID']) ? cleanInputData($_POST['bptsmID']) : -1;
                $transactionNo = isset($_POST['transactionNo']) ? cleanInputData($_POST['transactionNo']) : "";
                $baptismDate = isset($_POST['baptismDate']) ? cleanInputData($_POST['baptismDate']) : "";
                $baptismPlace = isset($_POST['baptismPlace']) ? cleanInputData($_POST['baptismPlace']) : "";
                $baptismMode = isset($_POST['baptismMode']) ? cleanInputData($_POST['baptismMode']) : "";
                $minister = isset($_POST['minister']) ? cleanInputData($_POST['minister']) : "";
                $godParent = isset($_POST['godParent']) ? cleanInputData($_POST['godParent']) : "";
                $lastName = isset($_POST['lastName']) ? cleanInputData($_POST['lastName']) : "";
                $otherNames = isset($_POST['otherNames']) ? cleanInputData($_POST['otherNames']) : "";
                $title = isset($_POST['title']) ? cleanInputData($_POST['title']) : "";
                $gender = isset($_POST['gender']) ? cleanInputData($_POST['gender']) : "";
                $dob = isset($_POST['dob']) ? cleanInputData($_POST['dob']) : "";
                $pob = isset($_POST['pob']) ? cleanInputData($_POST['pob']) : "";
                $nameOfFather = isset($_POST['nameOfFather']) ? cleanInputData($_POST['nameOfFather']) : "";
                $religionOfFather = isset($_POST['religionOfFather']) ? cleanInputData($_POST['religionOfFather']) : "";
                $nameOfMother = isset($_POST['nameOfMother']) ? cleanInputData($_POST['nameOfMother']) : "";
                $religionOfMother = isset($_POST['religionOfMother']) ? cleanInputData($_POST['religionOfMother']) : "";
                
                $frstCommunionId = isset($_POST['frstCommunionId']) ? cleanInputData($_POST['frstCommunionId']) : -1;
                $firstCommMinister = isset($_POST['firstCommMinister']) ? cleanInputData($_POST['firstCommMinister']) : "";
                $firstCommDate = isset($_POST['firstCommDate']) ? cleanInputData($_POST['firstCommDate']) : "";
                $firstCommPlace = isset($_POST['firstCommPlace']) ? cleanInputData($_POST['firstCommPlace']) : "";
                
                $cnfrmtnId = isset($_POST['cnfrmtnId']) ? cleanInputData($_POST['cnfrmtnId']) : -1;
                $cnfrmtnName = isset($_POST['cnfrmtnName']) ? cleanInputData($_POST['cnfrmtnName']) : "";
                $cnfrmtnGodParent = isset($_POST['cnfrmtnGodParent']) ? cleanInputData($_POST['cnfrmtnGodParent']) : "";
                $cnfrmtnMinister = isset($_POST['cnfrmtnMinister']) ? cleanInputData($_POST['cnfrmtnMinister']) : "";
                $cnfrmtnPlace = isset($_POST['cnfrmtnPlace']) ? cleanInputData($_POST['cnfrmtnPlace']) : "";
                $cnfrmtnDate = isset($_POST['cnfrmtnDate']) ? cleanInputData($_POST['cnfrmtnDate']) : "";
                
                $mtrmnyID = isset($_POST['mtrmnyID']) ? cleanInputData($_POST['mtrmnyID']) : -1;
                $mtrmnyPlace = isset($_POST['mtrmnyPlace']) ? cleanInputData($_POST['mtrmnyPlace']) : "";
                $mtrmnyDate = isset($_POST['mtrmnyDate']) ? cleanInputData($_POST['mtrmnyDate']) : "";
                $mtrmnyChurch = isset($_POST['mtrmnyChurch']) ? cleanInputData($_POST['mtrmnyChurch']) : "";
                $mtrmnyMinister = isset($_POST['mtrmnyMinister']) ? cleanInputData($_POST['mtrmnyMinister']) : "";
                $mtrmnyDispensation = isset($_POST['mtrmnyDispensation']) ? cleanInputData($_POST['mtrmnyDispensation']) : "";
                
                $mtrmnyLocalSpouseBaptismId = isset($_POST['mtrmnyLocalSpouseBaptismId']) ? cleanInputData($_POST['mtrmnyLocalSpouseBaptismId']) : -1;
                $extSpouseLastName = isset($_POST['extSpouseLastName']) ? cleanInputData($_POST['extSpouseLastName']) : "";
                $extSpouseOtherNames = isset($_POST['extSpouseOtherNames']) ? cleanInputData($_POST['extSpouseOtherNames']) : "";
                $extSpouseGender = isset($_POST['extSpouseGender']) ? cleanInputData($_POST['extSpouseGender']) : "";
                $extSpouseDob = isset($_POST['extSpouseDob']) ? cleanInputData($_POST['extSpouseDob']) : "";
                $extSpousePob = isset($_POST['extSpousePob']) ? cleanInputData($_POST['extSpousePob']) : "";
                
                $extSpouseNameOfFather = isset($_POST['extSpouseNameOfFather']) ? cleanInputData($_POST['extSpouseNameOfFather']) : "";
                $extSpouseNameOfMother = isset($_POST['extSpouseNameOfMother']) ? cleanInputData($_POST['extSpouseNameOfMother']) : "";
                $extSpouseBaptismDate = isset($_POST['extSpouseBaptismDate']) ? cleanInputData($_POST['extSpouseBaptismDate']) : "";
                $extSpouseBaptismPlace = isset($_POST['extSpouseBaptismPlace']) ? cleanInputData($_POST['extSpouseBaptismPlace']) : "";     

                
		$optn =  isset($_POST['optn']) ? cleanInputData($_POST['optn']) : '0';
                
                $waybillStatus = "";
                $cnt4 = 0;
                $btchNo = "";
                
                if ($transactionNo == "" || $baptismDate == "" || $baptismPlace == "" || $minister == "" || $lastName == "" || $otherNames == "" || $gender == ""
                        || $dob == "" || $pob == "" || $nameOfFather == "" || $nameOfMother == "") {
                    echo '<span style="color:red;font-weight:bold !important;">Please complete all required fields before saving!<br/></span>';
                    exit();
                } else {
                    
                    if ($baptismDate != "") {
                        $baptismDate = cnvrtDMYToYMD($baptismDate);
                    } 
                    if ($firstCommDate != "") {
                        $firstCommDate = cnvrtDMYToYMD($firstCommDate);
                    } 
                    
                    if ($cnfrmtnDate != "") {
                        $cnfrmtnDate = cnvrtDMYToYMD($cnfrmtnDate);
                    }
                    
                    if ($dob != "") {
                        $dob = cnvrtDMYToYMD($dob);
                    }
                    
                    if ($mtrmnyDate != "") {
                        $mtrmnyDate = cnvrtDMYToYMD($mtrmnyDate);
                    }
                    
                    if ($extSpouseDob != "") {
                        $extSpouseDob = cnvrtDMYToYMD($extSpouseDob);
                    }
                    
                    if ($extSpouseBaptismDate != "") {
                        $extSpouseBaptismDate = cnvrtDMYToYMD($extSpouseBaptismDate);
                    }
                    
                    

                    if ($bptsmID <= 0) {//CREATE
                        $bptsmID = getCathBaptismID();
                        $frstCommunionId = getCathFirstCommunionID();
                        $cnfrmtnId = getCathConfirmationID();
                        $mtrmnyID = getCathHolyMatrimonyID();
                        $waybillStatus = "Incomplete";               
                        
                        $rsltCnt = insertCathSacrament($bptsmID, $transactionNo, $baptismDate, $baptismPlace, $baptismMode,
                                $minister, $godParent, $lastName, $otherNames, $title, $gender, $dob, $pob, $nameOfFather, $religionOfFather, $nameOfMother, $religionOfMother,
                                $frstCommunionId, $firstCommMinister, $firstCommDate, $firstCommPlace, 
                                $cnfrmtnId, $cnfrmtnName, $cnfrmtnGodParent, $cnfrmtnMinister, $cnfrmtnPlace, $cnfrmtnDate, 
                                $mtrmnyID, $mtrmnyPlace, $mtrmnyDate, $mtrmnyChurch, $mtrmnyMinister, $mtrmnyDispensation, 
                                $mtrmnyLocalSpouseBaptismId, $extSpouseLastName, $extSpouseOtherNames, $extSpouseGender, $extSpouseDob, $extSpousePob, 
                                $extSpouseNameOfFather, $extSpouseNameOfMother, $extSpouseBaptismDate, $extSpouseBaptismPlace, $usrID, $dateStr, $orgID);
                        if($rsltCnt > 0){
                            echo json_encode(array("bptsmID" => $bptsmID, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                        } else {
                            echo '<span style="color:red;font-weight:bold !important;">Saving Failed!<br/></span>';
                        }
                        exit();
                    } else {//UPDATE
                        $rsltCnt = updateCathSacrament($bptsmID, $transactionNo, $baptismDate, $baptismPlace, $baptismMode,
                                $minister, $godParent, $lastName, $otherNames, $title, $gender, $dob, $pob, $nameOfFather, $religionOfFather, $nameOfMother, $religionOfMother,
                                $frstCommunionId, $firstCommMinister, $firstCommDate, $firstCommPlace, 
                                $cnfrmtnId, $cnfrmtnName, $cnfrmtnGodParent, $cnfrmtnMinister, $cnfrmtnPlace, $cnfrmtnDate, 
                                $mtrmnyID, $mtrmnyPlace, $mtrmnyDate, $mtrmnyChurch, $mtrmnyMinister, $mtrmnyDispensation, 
                                $mtrmnyLocalSpouseBaptismId, $extSpouseLastName, $extSpouseOtherNames, $extSpouseGender, $extSpouseDob, $extSpousePob, 
                                $extSpouseNameOfFather, $extSpouseNameOfMother, $extSpouseBaptismDate, $extSpouseBaptismPlace, $usrID, $dateStr);
                        
                        if($optn === "1"){
                            //$rtnCnt = updateCathSacramentStatus($bptsmID, 'Finalized');
                            if($rtnCnt > 0){
                                echo json_encode(array("bptsmID" => $bptsmID, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Finalized</span>"));
                            } else {
                                echo '<span style="color:red;font-weight:bold !important;">Failed to Finalize!<br/></span>';
                            }
                        } else {
                            if($rsltCnt > 0){
                                echo json_encode(array("bptsmID" => $bptsmID, "dspMsg" => "<span style='color:green; font-weight:bold !important;'>Successfully Saved</span>"));
                            } else {
                                echo '<span style="color:red;font-weight:bold !important;">Saving Failed!<br/></span>';
                            }
                        }
                        exit();
                    }
                }
            } 
            else if ($actyp == 500){
                $slctdItmPymntPlansSetup = isset($_POST['slctdItmPymntPlansSetup']) ? cleanInputData($_POST['slctdItmPymntPlansSetup']) : "";

                $vldtyUpdtCnt = 0;
                $rsltCrtCnt = 0;
                $rsltUpdtCnt = 0;

                if (1 > 0) {
                    $dateStr = getDB_Date_time();
                    $recCntInst = 0;
                    $recCntUpdt = 0;

                    if (trim($slctdItmPymntPlansSetup, "|~") != "") {

                        $variousRows = explode("|", trim($slctdItmPymntPlansSetup, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 4) {
                                $wtnssID = (int) (cleanInputData1($crntRow[0]));
                                $witness = cleanInputData1($crntRow[1]);
				$witnessFor = cleanInputData1($crntRow[2]);   
                                $mtrmnyID = (int)cleanInputData1($crntRow[3]); 
                                

                                if ($wtnssID > 0) {
                                    $recCntUpdt = $recCntUpdt + updateWitness($wtnssID, $witness, $witnessFor, $mtrmnyID, $usrID, $dateStr);
                                } else {
                                    $wtnssID = getWitnessID();
                                    $recCntInst = $recCntInst + insertWitness($wtnssID, $witness, $witnessFor, $mtrmnyID, $usrID, $dateStr);
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
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Sacrament</span>
				</li>
                               </ul>
                              </div>";
                //Stockable Item List
                $total = get_CathSacramentTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_CathSacrament($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-4";
                ?>
                <form id='allCathSacramentForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allCathSacramentSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllCathSacrament(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allCathSacramentPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCathSacrament('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCathSacrament('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allCathSacramentSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Full Name","Last Name", "First Name", "Place of Baptism","Minister");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allCathSacramentDsplySze" style="min-width:70px !important;">                            
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
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allCathSacramentSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "", "");
                                    $srchInsArrys = array("Last Created", "Last Name", "First Name", "Baptism No.");
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
                                        <a class="rhopagination" href="javascript:getAllCathSacrament('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllCathSacrament('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
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
                                <button type="button" class="btn btn-default btn-sm" onclick="getOneCathSacramentForm(-1,  1,  'ShowDialog');" data-toogle="tooltip" data-placement="bottom" title="Add New Sacrament">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Sacrament
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 0px; height: 30px !important;" onclick="imprtSacrament();" data-toggle="tooltip" data-placement="bottom" title="Import Sacrament">
                                    <img src="cmn_images/upload_csv.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Import
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 0px; max-height: 30px !important;" onclick="exprtSacrament();" data-toggle="tooltip" data-placement="bottom" title="Export Sacrament">
                                    <img src="cmn_images/Import-Excel.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Export
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allCathSacramentTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>&nbsp;</th>
                                        <th>Baptism No.</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Gender</th>
                                        <th>Place of Birth</th>
                                        <th>Date of Birth</th>
                                        <th>Baptism Date</th>
                                        <th>Baptism Place</th>
					<!--<th>Status</th>-->
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
                                        <tr id="allCathSacramentRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneCathSacramentForm(<?php echo $row[0]; ?>, 1, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd" style="font-weight:bold;color:blue;">
                                                <?php echo $row[17]; ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allCathSacramentRow<?php echo $cntr; ?>_BptsmID" value="<?php echo $row[0]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $row[14]; ?></td>
                                            <td class="lovtd" style="font-weight:bold;"><?php echo  $row[5]; ?></td>
                                            <td class="lovtd" style="font-weight:bold;color:blue;"><?php echo $row[6]; ?></td>                                            
                                            <td class="lovtd"><?php echo $row[7]; ?></td>  
                                            <td class="lovtd"><?php echo $row[8]; ?></td>
					    <!--<td class="lovtd"><?php echo $row[15]; ?></td>-->
                                            <?php if ($canDel === true) { ?>
                                                <td class="lovtd">
						    <?php if ($row[15] == "Incomplete") { ?>
                                                    	<button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delCathSacrament('allCathSacramentRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item">
                                                        	<img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    	</button>
						    <?php } else { ?>
                                                        &nbsp;
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                    echo urlencode(encrypt1(($row[0] . "|scm.scm_cnsmr_credit_analys|cnsmr_credit_id"),
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
                </form>
                <?php
            }
            else if ($vwtyp == 1) {//Order Form (Header/Details)
                //echo "Order Form (Header/Details)";

                /* Add */
                //BAPTISM
                $bptsmID = -1;
                $title = "";
                $lastName = "";
                $middleNames = "";
                $firstName = "";
                $nameOfFather = "";
                $nameOfMother = "";
                $pob = "";
                $dob = "";
                $baptismDate = "";
                $baptismPlace = "";
                $minister = "";
                $baptismMode = "";
                $godParent = "";
                $fatherReligion = "";
                $motherReligion = "";
                $gender = "";
                $orgID = "";
                
                //FIRST COMMUNION
                //CONFIRMATION
                //HOLY MATRIMONY
                $mtrmnyID = -1;
                $mtrmnyPlace = "";
                $mtrmnyDate = "";
                $mtrmnyChurch = "";
                $mtrmnyChurchId = "";
                $mtrmnyMinister = "";
                $mtrmnyDispensation ="";
                $mtrmnyLocalSpouse ="";
                $mtrmnyLocalSpouseBaptsmPlace = "";
                $mtrmnyLocalSpouseBaptsmDate = "";
                $mtrmnyLocalSpouseBaptismId = -1;
                $extSpouseLastName = "";
                $extSpouseOtherNames = "";
                $extSpouseTitle = "";
                $extSpouseTitleId = "";
                $extSpouseGender = "";
                $extSpouseDob ="";
                $extSpousePob ="";
                $extSpouseNameOfFather = "";
                $extSpouseNameOfMother = "";
                $extSpouseBaptismDate ="";
                $extSpouseBaptismPlace="";
                
                
                $periodAtWorkplace = "";
                $periodUomAtWorkplace = "Year(s)";
                $guarantorEmail = "";
                $ttlPrdtPrice = 0.00;
                $noOfPymnts = "";
                $otherNames = 0.00;
                $mnthlyRpymnts = 0.00;
                $initDpstType = "Automatic";
                $baptismModeId = -1;
                $ttlEarnings = 0.00;
                $planType = "";
                
                $rpymntClr = "blue";
		$invHdrId = -1;
                $invHdrNo = "";
                $storeID = -1;
                $storeNm = "";
                
                $trnsStatus = "Incomplete";
                //$detCnt = 0; 
                $chqReportName = "";
                
                $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                }
                $dte = date('ymd');

                $docTypPrfx = 'CCS';
                $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                
                $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("ccs.baptism", "bptsm_sys_code",
                                                                "bptsm_id", $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);
                
                
                $transactionNo = $gnrtdTrnsNo;
                
                $frstCommunionId = -1;
                $firstCommMinister = "";
                $firstCommDate = "";
                $firstCommPlace = "";
                
                $cnfrmtnId = -1;
                $cnfrmtnName = "";
                $cnfrmtnGodParent = "";
                $cnfrmtnMinister = "";
                $cnfrmtnPlace = "";
                $cnfrmtnDate = "";

                
                $result = get_CathSacramentDet($pkID);
                while ($row = loc_db_fetch_array($result)) {
                    $bptsmID = $row[0];
                    $title = $row[1];
                    $lastName = $row[2];
                    $middleNames = $row[3];
                    $firstName =$row[4];
                    $nameOfFather = $row[5];
                    $nameOfMother = $row[6];
                    $pob = $row[7];
                    $dob = $row[8];
                    $baptismDate = $row[9];
                    $baptismPlace = $row[10];
                    $minister = $row[11];
                    $baptismMode = $row[12];
                    $godParent = $row[13];
                    $fatherReligion = $row[14];
                    $motherReligion = $row[15];
                    $gender = $row[16];
                    $orgID = $row[17];
		    $trnsStatus = $row[18];
                    $transactionNo = $row[19];
		    /*$invHdrId = (int)getGnrlRecNm("scm.scm_cnsmr_credit_analys", "cnsmr_credit_id", "src_invc_hdr_id", $bptsmID);*/
                }
                
                $resultFC = get_FirstCommunion($pkID);
                while($rowFC = loc_db_fetch_array($resultFC)){
                    $frstCommunionId = $rowFC[0];
                    $firstCommMinister = $rowFC[2];
                    $firstCommDate = $rowFC[3];
                    $firstCommPlace = $rowFC[4];
                }
                
                $resultCF = get_Confirmation($pkID);
                while($rowCF = loc_db_fetch_array($resultCF)){
                    $cnfrmtnId = $rowCF[0];
                    $cnfrmtnName = $rowCF[2];
                    $cnfrmtnGodParent = $rowCF[3];
                    $cnfrmtnMinister = $rowCF[4];
                    $cnfrmtnPlace = $rowCF[5];
                    $cnfrmtnDate = $rowCF[6];
                }
                
                $resultHM = get_HolyMtrmnyDetails($pkID);
                while($rowHM = loc_db_fetch_array($resultHM)){
                    $mtrmnyID = $rowHM[0];
                    $mtrmnyPlace = $rowHM[11];
                    $mtrmnyDate = $rowHM[12];
                    $mtrmnyMinister = $rowHM[13];
                    $mtrmnyChurch = $rowHM[14];
                    $mtrmnyChurchId = $rowHM[14];
                    $mtrmnyDispensation = $rowHM[15];
                    $mtrmnyLocalSpouseBaptismId = (int)$rowHM[8];
                    $extSpouseOtherNames = $rowHM[2];
                    $extSpouseLastName = $rowHM[3];
                    $extSpouseGender = $rowHM[16];
                    $extSpouseDob =$rowHM[4];
                    $extSpousePob =$rowHM[5];
                    $extSpouseNameOfFather = $rowHM[6];
                    $extSpouseNameOfMother = $rowHM[7];
                    $extSpouseBaptismDate =$rowHM[9];
                    $extSpouseBaptismPlace=$rowHM[10];
                }
                
                if($mnthlyRpymnts > $baptismPlace){
                    $rpymntClr = "red";
                }
                
                $resultLSD = get_HolyMtrmnyLocalSpouseDetails($mtrmnyLocalSpouseBaptismId);
                while($rowLSD = loc_db_fetch_array($resultLSD)){
                    $mtrmnyLocalSpouse =$rowLSD[2];
                    $mtrmnyLocalSpouseBaptsmPlace = $rowLSD[3];
                    $mtrmnyLocalSpouseBaptsmDate = $rowLSD[4];
                }
                
                $chqReportName = "ChequePoint General Waybill";	

                $detCnt = 0; //getCnsmrCrdtItemCount($bptsmID);
                $sbmtdTrnsHdrID = $pkID;
                $voidedTrnsHdrID = -1;
                $rqstatusColor = "red";
                $ttlColor = "blue";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                
                if($detCnt > 0){
                    $mkReadOnly = "readonly";
                }

                $trnsTtl = 0.00;

                ?>
                <div class="row" style="margin: 0px 0px 0px 0px !important;" >
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/>                    
                </div>                    
                <div class="">
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv" style="border:none !important; padding-top:0px !important;"> 
                                <div class="tab-content">
                                    <div id="prflCMHomeEDT" class="tab-pane fadein active" style="border:none !important;">  
                                        <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
                                            <div class="col-md-4" style="padding:0px 1px 0px 0px !important;float:left;">
                                                <!--<button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                    <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                                </button-->
                                            </div>
					    <div class="col-md-4" style="padding:0px 10px 0px 10px !important;"> 
                                                
                                            </div>
                                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
						<div style="float:right;">
                                                <?php if ($trnsStatus == "Incomplete" || $trnsStatus == "Rejected") { ?>                                                    
                                                    <button id="svSacramentBtn" type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveCathSacrament(0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button> 
                                                    
                                                    <?php
                                                } if ($detCnt > 0 && $trnsStatus == "Incomplete") { ?>                                                    
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveCathSacrament(1);"><img src="cmn_images/valid_1.jpg" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize&nbsp;</button> 

                                                    <?php
                                                } else if ($trnsStatus == "Finalized") {
                                                    
                                                    
                                                    $reportTitle = "Waybill";
                                                    $reportName = $chqReportName;
                                                    $rptID = getRptID($reportName);
                                                    $prmID1 = getParamIDUseSQLRep("{:waybillHdrId}", $rptID);
                                                    $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                    //$invcID = $sbmtdTrnsHdrID;
                                                    $paramRepsNVals = $prmID1 . "~" . $bptsmID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                                    $paramStr = urlencode($paramRepsNVals);
                                                    
                                                    if ($trnsStatus == "Finalized" && $invHdrNo == "") { ?>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="reverseCathSacrament(<?php echo $bptsmID; ?>);"><img src="cmn_images/back_2.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                        Reverse&nbsp;
                                                    </button>
						    <?php if ($invHdrNo == "") { ?>
                                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Sales Invoice', 'NO', 'SALES', <?php echo $bptsmID; ?>);" data-toggle="tooltip" data-placement="bottom" title="Add New Sales Invoice">
                                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        SI
                                                    </button> 
                                                    <?php } ?>
                                                    <!--<button type="button" class="btn btn-default btn-sm" style="height:30px;" title="Print Waybill" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');">
                                                        <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                        Waybill
                                                    </button>-->
                                                    <?php } 
                                                }
                                                ?>
						</div>
                                            </div>
                                        </div>                                          
                                        <form class="form-horizontal" id="cnsmrCrdtAnalysisForm">
                                            <button class="paccordion active" id="baptismDiv">BAPTISM</button>                                          
                                            <div class="row" class="pBaptism"  style="display: block !important;padding:10px !important;"><!-- ROW 3 -->
                                                <div class="col-lg-12">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg" style="font-size: 13px !important;text-align: left !important;color:#4682B4 !important;">Details</legend>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="transactionNo" class="control-label col-md-4">Baptism No:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="transactionNo" type = "text" placeholder="" value="<?php echo $transactionNo; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="baptismDate" class="control-label col-md-4">Baptism Date:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="baptismDate" value="<?php echo $baptismDate; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="baptismPlace" class="control-label col-md-4">Baptism Place:</label>
                                                                <div  class="col-md-8">
                                                                    <input type="text" class="form-control" aria-label="..." id="baptismPlace" value="<?php echo $baptismPlace; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="baptismMode" class="control-label col-md-4">Baptism Mode</label>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" id="baptismMode" >
                                                                        <?php 

                                                                        $sltdImmersion = "";
                                                                        $sltdSprinkling = "";
                                                                        $sltdPouring = "";

                                                                        if($baptismMode == "Immersion"){
                                                                            $sltdImmersion = "selected=\"selected\"";
                                                                        } else if($baptismMode == "Sprinkling"){
                                                                            $sltdSprinkling = "selected=\"selected\"";
                                                                        } else {
                                                                            $sltdPouring = "selected=\"selected\"";
                                                                        }

                                                                        ?>
                                                                        <option value="Immersion" <?php echo $sltdImmersion; ?>>Immersion</option>
                                                                        <option value="Sprinkling" <?php echo $sltdSprinkling; ?>>Sprinkling</option>
                                                                        <option value="Pouring" <?php echo $sltdPouring; ?>>Pouring</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="minister" class="control-label col-md-4">Minister:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="minister" type = "text" placeholder="" value="<?php echo $minister; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="godParent" class="control-label col-md-4">God Parent:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="godParent" type = "text" placeholder="" value="<?php echo $godParent; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row" class="pBaptism" style="display: block !important;padding:0px 10px 10px 10px !important;"><!-- ROW 1 -->
                                                <div class="col-lg-12">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg" style="font-size: 13px !important;text-align: left !important;color:#4682B4 !important;">Bio Data</legend>
                                                        <div class="col-lg-4">
                                                            <input class="form-control" id="bptsmID" type = "hidden" placeholder="Baptism ID" value="<?php echo $bptsmID; ?>"/>
                                                            <div class="form-group form-group-sm">
                                                                <label for="lastName" class="control-label col-md-4">Last Name:</label>
                                                                <div  class="col-md-8">
                                                                    <input type="text" class="form-control" aria-label="..." id="lastName" value="<?php echo $lastName; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="otherNames" class="control-label col-md-4">Other Names:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="otherNames" type = "text" placeholder="" value="<?php echo $firstName; ?>"/> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="title" class="control-label col-md-4">Title:</label>
                                                                <div class="col-md-8">
                                                                    <select class="form-control" id="title" name="title">
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Person Titles"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $title) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                        ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="gender" class="control-label col-md-4">Gender</label>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" id="gender" >
                                                                        <?php 

                                                                        $sltdMale = "";
                                                                        $sltdFemale = "";

                                                                        if($gender == "Male"){
                                                                            $sltdMale = "selected=\"selected\"";
                                                                        } else {
                                                                            $sltdFemale = "selected=\"selected\"";
                                                                        } 

                                                                        ?>
                                                                        <option value="Male" <?php echo $sltdMale; ?>>Male</option>
                                                                        <option value="Female" <?php echo $sltdFemale; ?>>Female</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="dob" class="control-label col-md-4">Date of Birth:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="dob" value="<?php echo $dob; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="pob" class="control-label col-md-4">Place of Birth:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="pob" type = "text" placeholder="" value="<?php echo $pob; ?>"/> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row" class="pBaptism"  style="display: block !important;padding:0px 10px 10px 10px !important;"><!-- ROW 2 -->
                                                <div class="col-lg-12">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg" style="font-size: 13px !important;text-align: left !important;color:#4682B4 !important;">Parents</legend>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="nameOfFather" class="control-label col-md-4">Father's Name:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="nameOfFather" type = "text" placeholder="" value="<?php echo $nameOfFather; ?>"/> 
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="religionOfFather" class="control-label col-md-4">Father Religion:</label>
                                                                <div class="col-md-8">
                                                                    <select class="form-control" id="religionOfFather" name="religionOfFather">
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Religions"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $fatherReligion) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                        ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="nameOfMother" class="control-label col-md-4">Mother's Name:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="nameOfMother" type = "text" placeholder="" value="<?php echo $nameOfMother; ?>" <?php echo $mkReadOnly; ?>/> 
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="religionOfMother" class="control-label col-md-4">Mother Religion:</label>
                                                                <div class="col-md-8">
                                                                    <select class="form-control" id="religionOfMother" name="religionOfMother">
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Religions"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $motherReligion) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                        ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <button class="paccordion">1ST COMMUNION</button>
                                            <div class="row" class="ppanel" style="display: none !important;padding:10px !important;"><!-- ROW 4 -->
                                                <div class="col-lg-12">
                                                    <!--<fieldset class="basic_person_fs5"><legend class="basic_person_lg" style="font-size: 13px !important;text-align: left !important;color:#4682B4 !important;">1ST COMMUNION</legend>-->
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="firstCommMinister" class="control-label col-md-4">Minister:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="frstCommunionId" type = "hidden" placeholder="" value="<?php echo $frstCommunionId; ?>"/>   
                                                                    <input class="form-control" id="firstCommMinister" type = "text" placeholder="" value="<?php echo $firstCommMinister; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="firstCommDate" class="control-label col-md-4">Date:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="firstCommDate" value="<?php echo $firstCommDate; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="firstCommPlace" class="control-label col-md-4" >Place:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="firstCommPlace" type = "text" placeholder="" value="<?php echo $firstCommPlace; ?>"/> 
                                                                </div>
                                                            </div>  
                                                        </div>                                                            
                                                    <!--</fieldset>-->
                                                </div>
                                            </div>
                                            <button class="paccordion">CONFIRMATION</button>
                                            <div class="row" class="ppanel" style="display: none !important;padding:10px !important;"><!-- ROW 5 -->
                                                <div class="col-lg-12">
                                                    <!--<fieldset class="basic_person_fs5"><legend class="basic_person_lg" style="font-size: 13px !important;text-align: left !important;color:#4682B4 !important;">CONFIRMATION</legend>-->
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="cnfrmtnName" class="control-label col-md-4">Name:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="cnfrmtnId" type = "hidden" placeholder="" value="<?php echo $cnfrmtnId; ?>"/>
                                                                    <input class="form-control" id="cnfrmtnName" type = "text" placeholder="" value="<?php echo $cnfrmtnName; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="cnfrmtnGodParent" class="control-label col-md-4">God Parent:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="cnfrmtnGodParent" type = "text" placeholder="" value="<?php echo $cnfrmtnGodParent; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="cnfrmtnMinister" class="control-label col-md-4">Minister:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="cnfrmtnMinister" type = "text" placeholder="" value="<?php echo $cnfrmtnMinister; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="cnfrmtnPlace" class="control-label col-md-4">Place:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="cnfrmtnPlace" type = "text" placeholder="" value="<?php echo $cnfrmtnPlace; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="cnfrmtnDate" class="control-label col-md-4">Date:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="cnfrmtnDate" value="<?php echo $cnfrmtnDate; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>                                                            
                                                            </div>  
                                                        </div>                                                            
                                                    <!--</fieldset>-->
                                                </div>
                                            </div>
                                            <button class="paccordion">HOLY MATRIMONY - Details</button>
                                            <div class="row" class="ppanel" style="display: none !important;padding:10px !important;"><!-- ROW 6 -->
                                                <div class="col-lg-12">
                                                    <!--<fieldset class="basic_person_fs5"><legend class="basic_person_lg" style="font-size: 13px !important;text-align: left !important;color:#4682B4 !important;">HOLY MATRIMONY - Details</legend>-->
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="mtrmnyPlace" class="control-label col-md-4">Place:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="mtrmnyID" type = "hidden" placeholder="" value="<?php echo $mtrmnyID; ?>"/>    
                                                                    <input class="form-control" id="mtrmnyPlace" type = "text" placeholder="" value="<?php echo $mtrmnyPlace; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="mtrmnyDate" class="control-label col-md-4">Date:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="mtrmnyDate" value="<?php echo $mtrmnyDate; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="mtrmnyMinister" class="control-label col-md-4">Minister:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="mtrmnyMinister" type = "text" placeholder="" value="<?php echo $mtrmnyMinister; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="mtrmnyChurch" class="control-label col-md-4">Church:</label>
                                                                <div  class="col-md-8">
                                                                    <input type="text" class="form-control" aria-label="..." id="mtrmnyChurch" value="<?php echo $mtrmnyChurch; ?>">
                                                                    <!--<div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="mtrmnyChurch" value="" readonly="">
                                                                        <input type="hidden" id="mtrmnyChurchId" value="">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Religion', '', '', '', 'radio', true, '', 'mtrmnyChurchId', 'mtrmnyChurch', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>-->
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="mtrmnyDispensation" class="control-label col-md-4">Dispensation:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="mtrmnyDispensation" type = "text" placeholder="" value="<?php echo $mtrmnyDispensation; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="mtrmnyWitness" class="control-label col-md-4">Witness:</label>
                                                                <div class="col-md-8">
                                                                    <button type="button" class="btn btn-sm btn-primary" style="width:100% !important;" onclick="getWitnessForm(<?php echo $mtrmnyID; ?>, 'ShowDialog');">Witness List</button>                                                                                                                                           
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="mtrmnyLocalSpouse" class="control-label col-md-4">Spouse (Local):</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="mtrmnyLocalSpouse" value="<?php echo $mtrmnyLocalSpouse; ?>" readonly="">
                                                                        <input type="hidden" id="mtrmnyLocalSpouseBaptismId" value="<?php echo $mtrmnyLocalSpouseBaptismId; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Baptism ID Numbers', '', '', '', 'radio', true, '', 'mtrmnyLocalSpouseBaptismId', 'mtrmnyLocalSpouse', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="localSpouseBaptismPlace" class="control-label col-md-4">Baptism Place:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="localSpouseBaptismPlace" type = "text" placeholder="" value="<?php echo $mtrmnyLocalSpouseBaptsmPlace; ?>" readonly=""/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="localSpouseBaptismDate" class="control-label col-md-4">Baptism Date:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="localSpouseBaptismDate" type = "text" placeholder="" value="<?php echo $mtrmnyLocalSpouseBaptsmDate; ?>" readonly=""/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                        </div>                                                            
                                                    <!--</fieldset>-->
                                                </div>
                                            </div>
                                            <button class="paccordion">HOLY MATRIMONY - External Spouse</button>
                                            <div class="row" class="ppanel" style="display: none !important;padding:10px !important;"><!-- ROW 7 -->
                                                <div class="col-lg-12">
                                                    <!--<fieldset class="basic_person_fs5"><legend class="basic_person_lg" style="font-size: 13px !important;text-align: left !important;color:#4682B4 !important;">HOLY MATRIMONY - External Spouse</legend>-->
                                                        <div class="col-lg-4">
                                                            <input class="form-control" id="spouseMtrmnyID" type = "hidden" placeholder="Matrimony ID" value="<?php echo $bptsmID; ?>"/>
                                                            <div class="form-group form-group-sm">
                                                                <label for="extSpouseLastName" class="control-label col-md-4">Last Name:</label>
                                                                <div  class="col-md-8">
                                                                    <input type="text" class="form-control" aria-label="..." id="extSpouseLastName" value="<?php echo $extSpouseLastName; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="extSpouseOtherNames" class="control-label col-md-4">Other Names:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="extSpouseOtherNames" type = "text" placeholder="" value="<?php echo $extSpouseOtherNames; ?>"/> 
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="extSpouseGender" class="control-label col-md-4">Gender</label>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" id="extSpouseGender" >
                                                                        <?php 

                                                                        $sltdMale = "";
                                                                        $sltdFemale = "";

                                                                        if($extSpouseGender == "Male"){
                                                                            $sltdMale = "selected=\"selected\"";
                                                                        } else {
                                                                            $sltdFemale = "selected=\"selected\"";
                                                                        } 

                                                                        ?>
                                                                        <option value="Male" <?php echo $sltdMale; ?>>Male</option>
                                                                        <option value="Female" <?php echo $sltdFemale; ?>>Female</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm" style="display:none !important;">
                                                                <label for="extSpouseTitle" class="control-label col-md-4">Title:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="extSpouseTitle" value="<?php echo ""; ?>" readonly>
                                                                        <input type="hidden" id="extSpouseTitleId" value="<?php echo ""; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Titles', '', '', '', 'radio', true, '', 'extSpouseTitleId', 'extSpouseTitle', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="extSpouseDob" class="control-label col-md-4">Date of Birth:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="extSpouseDob" value="<?php echo $extSpouseDob; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="extSpousePob" class="control-label col-md-4">Place of Birth:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="extSpousePob" type = "text" placeholder="" value="<?php echo $extSpousePob; ?>"/> 
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="extSpouseNameOfFather" class="control-label col-md-4">Father's Name:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="extSpouseNameOfFather" type = "text" placeholder="" value="<?php echo $extSpouseNameOfFather; ?>"/> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4"> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="extSpouseNameOfMother" class="control-label col-md-4">Mother's Name:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="extSpouseNameOfMother" type = "text" placeholder="" value="<?php echo $extSpouseNameOfMother; ?>" <?php echo $mkReadOnly; ?>/> 
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="extSpouseBaptismDate" class="control-label col-md-4">Baptism Date:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="extSpouseBaptismDate" value="<?php echo $extSpouseBaptismDate; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="extSpouseBaptismPlace" class="control-label col-md-4">Baptism Place:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="extSpouseBaptismPlace" type = "text" placeholder="" value="<?php echo $extSpouseBaptismPlace; ?>" <?php echo $mkReadOnly; ?>/> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <!--</fieldset>-->
                                                </div>
                                            </div>
                                        </form>  
                                    </div>
                                    <div id="prflCMDataEDT" class="tab-pane fade" style="border:none !important;"></div>
                                    <div id="prflCMAttchmntEDT" class="tab-pane fade" style="border:none !important;"></div>      
                                </div>                        
                            </div>                         
                        </div>                
                    </div>          
                </div>
                <?php
            } 
            else if ($vwtyp == 500) {
                ?>
                <div class="row"><!-- ROW 1 -->
                    <div class="col-lg-12">  
                        <div class="row" id="allItmPymntPlansSetupDetailInfo" style="padding:0px 15px 0px 15px !important">
                            <?php
                            $trnsStatus = "Incomplete";
                            $itmID = -1;
                            $mtrmnt_id = isset($_POST['sbmtdMtrmntID']) ? cleanInputData($_POST['sbmtdMtrmntID']) : -1;
                            if (1 > 0) {
                                $result2 = getItmPymntPlansSetupTbl($mtrmnt_id);
                                ?>
                                <div class="row" style="padding:0px 15px 0px 15px !important">
                                    <legend class="basic_person_lg1" style="color: #003245">WITNESSES</legend>
                                    <?php
                                    if (2 > 1) {
                                        $nwRowHtml = urlencode("<tr id=\"allItmPymntPlansSetupRow__WWW123WWW\">"
                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>
                                                <td class=\"lovtd\">
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allItmPymntPlansSetupRow_WWW123WWW_WtnssID\" value=\"-1\" style=\"width:100% !important;\">                                                                         
                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmPymntPlansSetupRow_WWW123WWW_Witness\" name=\"allItmPymntPlansSetupRow_WWW123WWW_Witness\" value=\"\">                                                                        
                                                </td>
                                                <td class=\"lovtd\">       
                                                    <select class=\"form-control\" aria-label=\"...\" id=\"allItmPymntPlansSetupRow_WWW123WWW_WitnessFor\" name=\"allItmPymntPlansSetupRow_WWW123WWW_WitnessFor\">
                                                        <option value=\"Man\" selected>Man</option>
                                                        <option value=\"Woman\" >Woman</option>														
                                                    </select>
                                                </td>
                                                <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"deleteItmPymntPlansSetup('allItmPymntPlansSetupRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Witness\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button>
                                                </td>
                                            </tr>");
                                      
                                    ?>
                                    <div class="col-md-6" style="padding:0px 1px 0px 3px !important;">
                                        <?php if ($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allItmPymntPlansSetupTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add New Witness">
                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;New Witness
                                            </button>
                                        <?php } ?>
                                    </div>
                                    <input type="hidden" class="form-control" aria-label="..." id="recCnt" name="recCnt" value="">
                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdMtrmntID" name="sbmtdMtrmntID" value="<?php echo $mtrmnt_id; ?>">
                                    <div class="col-md-6" style="padding:0px 1px 0px 3px !important;"> 
                                        <div style="float:right !important;">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveWitnessForm(<?php echo $mtrmnt_id; ?>);" data-toggle="tooltip" data-placement="bottom" title="Save Witnesses">
                                                <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                        <table class="table table-striped table-bordered table-responsive" id="allItmPymntPlansSetupTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Name</th>
                                                    <th>Witness For</th>
                                                    <th>...</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cntr = 0;
                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                    $cntr += 1;
                                                    ?>
                                                    <tr id="allItmPymntPlansSetupRow_<?php echo $cntr; ?>">                                    
                                                        <td class="lovtd"><span><?php echo $cntr; ?></span></td>
                                                        <td class="lovtd">
                                                            <input type="hidden" class="form-control" aria-label="..." id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_WtnssID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                                                         
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_Witness" name="allItmPymntPlansSetupRow<?php echo $cntr; ?>_Witness" value="<?php echo $row2[2]; ?>">                                                                        
                                                        </td>
                                                        <td class="lovtd">  
                                                            <select class="form-control" aria-label="..." id="allItmPymntPlansSetupRow<?php echo $cntr; ?>_WitnessFor" name="allItmPymntPlansSetupRow<?php echo $cntr; ?>_WitnessFor">
                                                                <?php
                                                                $sltdMan = "";
                                                                $sltdWoman = "";
                                                                if ($row2[3] == "Man") {
                                                                    $sltdMan = "selected";
                                                                } else if ($row2[3] == "Woman") {
                                                                    $sltdWoman = "selected";
                                                                }
                                                                ?>
                                                                <option value="Man" <?php echo $sltdMan; ?>>Man</option>
                                                                <option value="Woman" <?php echo $sltdWoman; ?>>Woman</option>    
                                                            </select>		
                                                        </td>
                                                        <?php if ($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="deleteOneWitness('allItmPymntPlansSetupRow_<?php echo $cntr; ?>', '<?php echo $row2[0]; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Witness">
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
            }
        }
    }
}

function get_CathSacrament($searchFor, $searchIn, $offset, $limit_size, $sortBy)
{
    global $orgID;
    $whereClause = "";
    $strSql = "";
    $ordrBy = "";
    if ($sortBy == "Last Name") {
        $ordrBy = "last_name DESC, first_name DESC";
    } else if ($sortBy == "First Name") {
        $ordrBy = "first_name DESC";
    } else if ($sortBy == "Baptism No.") {
        $ordrBy = "bptsm_sys_code ASC";
    } else {
        $ordrBy = "last_update_by DESC";
    }


    if ($searchIn == "Full Name") {
        $whereClause = " and (last_name ilike '%" . loc_db_escape_string($searchFor) .
                "%' OR first_name ilike '%" . loc_db_escape_string($searchFor) .  "%' OR middle_names ilike '%" . loc_db_escape_string($searchFor) .          
            "%')";
    } else if ($searchIn == "Last Name") {
        $whereClause = " and (last_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "First Name") {
        $whereClause = " and (first_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Place of Baptism") {
        $whereClause = " and (place_of_baptism ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Minister") {
        $whereClause = " and (minister_full_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    }
    
    $strSql = "SELECT bptsm_id, last_name, first_name, father_full_name, mother_full_name, pob, /*5*/
                case when dob is null then '' when dob = '' then '' else to_char(to_timestamp(dob,'yyyy-mm-dd'),'DD-Mon-YYYY') end dob, /*6*/
                case when date_of_baptism is null then '' when date_of_baptism = '' then '' else to_char(to_timestamp(date_of_baptism,'yyyy-mm-dd'),'DD-Mon-YYYY') end date_of_baptism, /*7*/
                place_of_baptism, minister_full_name, mode, godparent_full_name, /*11*/
                father_religion, mother_religion, gender, status, title, bptsm_sys_code /*17*/
             FROM ccs.baptism " .
             " WHERE ((org_id = " . $orgID . " )$whereClause) ORDER BY $ordrBy LIMIT " . $limit_size .
             " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CathSacramentTtl($searchFor, $searchIn)
{
    global $orgID;
    $whereClause = "";
    $strSql = "";

    if ($searchIn == "Full Name") {
        $whereClause = " and (last_name ilike '%" . loc_db_escape_string($searchFor) .
                "%' OR first_name ilike '%" . loc_db_escape_string($searchFor) .  "%' OR middle_names ilike '%" . loc_db_escape_string($searchFor) .          
            "%')";
    } else if ($searchIn == "Last Name") {
        $whereClause = " and (last_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "First Name") {
        $whereClause = " and (first_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Place of Baptism") {
        $whereClause = " and (place_of_baptism ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Minister") {
        $whereClause = " and (minister_full_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    }

    $strSql = "select count(1) 
            from (SELECT 1 FROM ccs.baptism " .
        "WHERE ((org_id = " . $orgID . " )$whereClause))TBL1";
    // and a.item_type not ilike 'VaultItem%'
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_CathSacramentDet($bptsm_id)
{
    $strSql = "SELECT bptsm_id, title, last_name, middle_names, first_name, father_full_name, mother_full_name, pob, 
        case when dob is null then '' when dob = '' then '' else to_char(to_timestamp(dob,'yyyy-mm-dd'),'DD-Mon-YYYY')  end dob, 
        case when date_of_baptism is null then '' when date_of_baptism = '' then '' else to_char(to_timestamp(date_of_baptism,'yyyy-mm-dd'),'DD-Mon-YYYY')  end date_of_baptism,
        place_of_baptism, minister_full_name, mode, godparent_full_name, father_religion, mother_religion, 
        gender, org_id, status, bptsm_sys_code
                FROM ccs.baptism
  WHERE bptsm_id = $bptsm_id";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_FirstCommunion($bptsm_id)
{
    $strSql = "SELECT frst_communion_id, bptsm_id, minister_full_name, 
        case when communion_date is null then '' when communion_date = '' then '' else to_char(to_timestamp(communion_date,'yyyy-mm-dd'),'DD-Mon-YYYY')  end communion_date, 
        place_of_first_communion
	FROM ccs.first_communion
    WHERE bptsm_id = $bptsm_id";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_FirstCommunionExport($searchFor, $searchIn, $offset, $limit_size, $sortBy)
{
    global $orgID;
    $whereClause = "";
    $strSql = "";
    $ordrBy = "";
    if ($sortBy == "Last Name") {
        $ordrBy = "last_name DESC, first_name DESC";
    } else if ($sortBy == "First Name") {
        $ordrBy = "first_name DESC";
    } else if ($sortBy == "Baptism No.") {
        $ordrBy = "bptsm_sys_code ASC";
    } else {
        $ordrBy = "y.last_update_by DESC";
    }


    if ($searchIn == "Full Name") {
        $whereClause = " and (last_name ilike '%" . loc_db_escape_string($searchFor) .
                "%' OR first_name ilike '%" . loc_db_escape_string($searchFor) .  "%' OR middle_names ilike '%" . loc_db_escape_string($searchFor) .          
            "%')";
    } else if ($searchIn == "Last Name") {
        $whereClause = " and (last_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "First Name") {
        $whereClause = " and (first_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Place of Baptism") {
        $whereClause = " and (place_of_baptism ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Minister") {
        $whereClause = " and (y.minister_full_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    }
    
    $strSql = "SELECT frst_communion_id, x.bptsm_id, x.minister_full_name, /*2*/
        case when communion_date is null then '' when communion_date = '' then '' else to_char(to_timestamp(communion_date,'yyyy-mm-dd'),'DD-Mon-YYYY')  end communion_date, /*3*/
        place_of_first_communion,  bptsm_sys_code /*5*/
	FROM ccs.first_communion x full outer join ccs.baptism y
    ON x.bptsm_id = y.bptsm_id 
             WHERE ((org_id = " . $orgID . " )$whereClause) ORDER BY $ordrBy LIMIT " . $limit_size .
             " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Confirmation($bptsm_id)
{
    $strSql = "SELECT cnfrmtn_id, bptsm_id, confirmation_name, godparent_full_name, confirmation_minister, place_of_confirmation, 
        case when date_of_confirmation is null then '' when date_of_confirmation = '' then '' else to_char(to_timestamp(date_of_confirmation,'yyyy-mm-dd'),'DD-Mon-YYYY') end date_of_confirmation
	FROM ccs.confirmation
    WHERE bptsm_id = $bptsm_id";
    
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_ConfirmationExport($searchFor, $searchIn, $offset, $limit_size, $sortBy)
{
    global $orgID;
    $whereClause = "";
    $strSql = "";
    $ordrBy = "";
    if ($sortBy == "Last Name") {
        $ordrBy = "last_name DESC, first_name DESC";
    } else if ($sortBy == "First Name") {
        $ordrBy = "first_name DESC";
    } else if ($sortBy == "Baptism No.") {
        $ordrBy = "bptsm_sys_code ASC";
    } else {
        $ordrBy = "y.last_update_by DESC";
    }

    if ($searchIn == "Full Name") {
        $whereClause = " and (last_name ilike '%" . loc_db_escape_string($searchFor) .
                "%' OR first_name ilike '%" . loc_db_escape_string($searchFor) .  "%' OR middle_names ilike '%" . loc_db_escape_string($searchFor) .          
            "%')";
    } else if ($searchIn == "Last Name") {
        $whereClause = " and (last_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "First Name") {
        $whereClause = " and (first_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Place of Baptism") {
        $whereClause = " and (place_of_baptism ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Minister") {
        $whereClause = " and (y.minister_full_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    }
    
    $strSql = "SELECT cnfrmtn_id, x.bptsm_id, confirmation_name, /*2*/ 
        x.godparent_full_name, confirmation_minister, place_of_confirmation, /*5*/ 
        case when date_of_confirmation is null then '' when date_of_confirmation = '' then '' else to_char(to_timestamp(date_of_confirmation,'yyyy-mm-dd'),'DD-Mon-YYYY') end date_of_confirmation, /*6*/
        bptsm_sys_code /*7*/
	FROM ccs.confirmation x full outer join ccs.baptism y
    ON x.bptsm_id = y.bptsm_id
             WHERE ((org_id = " . $orgID . " )$whereClause) ORDER BY $ordrBy LIMIT " . $limit_size .
             " OFFSET " . abs($offset * $limit_size);
    
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_HolyMtrmnyDetails($bptsm_id)
{
    $strSql = "SELECT matrimony_id, bptsm_id, /*1*/
        spouse_first_name, spouse_surname, 
        case when spouse_dob is null then '' when spouse_dob = '' then '' else to_char(to_timestamp(spouse_dob,'yyyy-mm-dd'),'DD-Mon-YYYY')  end spouse_dob, 
        spouse_pob, father_of_spouse, mother_of_spouse, /*7*/
        coalesce(bptsm_id_spouse,-1), 
        case when spouse_baptism_date is null then '' when spouse_baptism_date = '' then '' else to_char(to_timestamp(spouse_baptism_date,'yyyy-mm-dd'),'DD-Mon-YYYY') end spouse_baptism_date, 
        spouse_baptism_place, /*10*/
        matrimony_place, 
        case when matrimony_date is null then '' when matrimony_date = '' then '' else to_char(to_timestamp(matrimony_date,'yyyy-mm-dd'),'DD-Mon-YYYY')  end matrimony_date, 
        minister, church, dispensation, spouse_gender /*16*/
	FROM ccs.holy_matrimony
  WHERE bptsm_id = $bptsm_id";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_HolyMtrmnyExport($searchFor, $searchIn, $offset, $limit_size, $sortBy)
{
    global $orgID;
    $whereClause = "";
    $strSql = "";
    $ordrBy = "";
    if ($sortBy == "Last Name") {
        $ordrBy = "last_name DESC, first_name DESC";
    } else if ($sortBy == "First Name") {
        $ordrBy = "first_name DESC";
    } else if ($sortBy == "Baptism No.") {
        $ordrBy = "bptsm_sys_code ASC";
    } else {
        $ordrBy = "y.last_update_by DESC";
    }


    if ($searchIn == "Full Name") {
        $whereClause = " and (last_name ilike '%" . loc_db_escape_string($searchFor) .
                "%' OR first_name ilike '%" . loc_db_escape_string($searchFor) .  "%' OR middle_names ilike '%" . loc_db_escape_string($searchFor) .          
            "%')";
    } else if ($searchIn == "Last Name") {
        $whereClause = " and (last_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "First Name") {
        $whereClause = " and (first_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Place of Baptism") {
        $whereClause = " and (place_of_baptism ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Minister") {
        $whereClause = " and (y.minister_full_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    }
    
    $strSql = "SELECT matrimony_id, x.bptsm_id, /*1*/
        spouse_first_name, spouse_surname, /*3*/
        case when spouse_dob is null then '' when spouse_dob = '' then '' else to_char(to_timestamp(spouse_dob,'yyyy-mm-dd'),'DD-Mon-YYYY')  end spouse_dob,  /*4*/
        spouse_pob, father_of_spouse, mother_of_spouse, /*7*/
        coalesce(bptsm_id_spouse,-1) bptsm_id_spouse,  /*8*/
        case when spouse_baptism_date is null then '' when spouse_baptism_date = '' then '' else to_char(to_timestamp(spouse_baptism_date,'yyyy-mm-dd'),'DD-Mon-YYYY') end spouse_baptism_date, /*9*/
        spouse_baptism_place, /*10*/
        matrimony_place, /*11*/
        case when matrimony_date is null then '' when matrimony_date = '' then '' else to_char(to_timestamp(matrimony_date,'yyyy-mm-dd'),'DD-Mon-YYYY')  end matrimony_date, /*12*/
        x.minister, church, dispensation, spouse_gender, bptsm_sys_code /*17*/
	FROM ccs.holy_matrimony x full outer join ccs.baptism y
    ON x.bptsm_id = y.bptsm_id
             WHERE ((org_id = " . $orgID . " )$whereClause) ORDER BY $ordrBy LIMIT " . $limit_size .
             " OFFSET " . abs($offset * $limit_size);
    
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_HolyMtrmnyWitness($mtrmny_id)
{
    $strSql = "SELECT wtnss_id, matrimony_id, witness_name, witness_for
	FROM ccs.matrimony_witness
  WHERE matrimony_id = $mtrmny_id";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_HolyMtrmnyWitnessExport($searchFor, $searchIn, $offset, $limit_size, $sortBy)
{
    global $orgID;
    $whereClause = "";
    $strSql = "";
    $ordrBy = "";
    if ($sortBy == "Last Name") {
        $ordrBy = "last_name DESC, first_name DESC";
    } else if ($sortBy == "First Name") {
        $ordrBy = "first_name DESC";
    } else if ($sortBy == "Baptism No.") {
        $ordrBy = "bptsm_sys_code ASC";
    } else {
        $ordrBy = "z.last_update_by DESC";
    }


    if ($searchIn == "Full Name") {
        $whereClause = " and (last_name ilike '%" . loc_db_escape_string($searchFor) .
                "%' OR first_name ilike '%" . loc_db_escape_string($searchFor) .  "%' OR middle_names ilike '%" . loc_db_escape_string($searchFor) .          
            "%')";
    } else if ($searchIn == "Last Name") {
        $whereClause = " and (last_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "First Name") {
        $whereClause = " and (first_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Place of Baptism") {
        $whereClause = " and (place_of_baptism ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Minister") {
        $whereClause = " and (z.minister_full_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    }
        
    $strSql = "SELECT wtnss_id, x.matrimony_id, witness_name, witness_for, bptsm_sys_code /*4*/
	FROM ccs.matrimony_witness x inner join ccs.holy_matrimony y ON x.matrimony_id = y.matrimony_id
        full outer join ccs.baptism z ON y.bptsm_id = z.bptsm_id
             WHERE ((org_id = " . $orgID . " )$whereClause) ORDER BY $ordrBy LIMIT " . $limit_size .
             " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_HolyMtrmnyLocalSpouseDetails($loc_spouse_bptsm_id)
{
    $strSql = "SELECT title, middle_names, bptsm_sys_code||' ('||first_name||' '||last_name||')' full_name, /*2*/
        case when date_of_baptism is null then '' when date_of_baptism = '' then '' else to_char(to_timestamp(date_of_baptism,'yyyy-mm-dd'),'DD-Mon-YYYY')  end date_of_baptism, 
        place_of_baptism
        FROM ccs.baptism
  WHERE bptsm_id = $loc_spouse_bptsm_id";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getItmPymntPlansSetupTbl($mtrmnt_id)
{
    $strSql = "SELECT wtnss_id, matrimony_id, witness_name, witness_for
	FROM ccs.matrimony_witness WHERE matrimony_id = $mtrmnt_id
        ORDER BY wtnss_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getCathBaptismID()
{
    $strSql = "select nextval('ccs.baptism_bptsm_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function getCathFirstCommunionID()
{
    $strSql = "select nextval('ccs.first_communion_frst_communion_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function getCathConfirmationID()
{
    $strSql = "select nextval('ccs.confirmation_cnfrmtn_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function getCathHolyMatrimonyID()
{
    $strSql = "select nextval('ccs.holy_matrimony_matrimony_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function insertCathSacrament($bptsmID, $transactionNo, $baptismDate, $baptismPlace, $baptismMode,
                                $minister, $godParent, $lastName, $otherNames, $title, $gender, $dob, $pob, $nameOfFather, $religionOfFather, $nameOfMother, $religionOfMother,
                                $frstCommunionId, $firstCommMinister, $firstCommDate, $firstCommPlace, 
                                $cnfrmtnId, $cnfrmtnName, $cnfrmtnGodParent, $cnfrmtnMinister, $cnfrmtnPlace, $cnfrmtnDate, 
                                $mtrmnyID, $mtrmnyPlace, $mtrmnyDate, $mtrmnyChurch, $mtrmnyMinister, $mtrmnyDispensation, 
                                $mtrmnyLocalSpouseBaptismId, $extSpouseLastName, $extSpouseOtherNames, $extSpouseGender, $extSpouseDob, $extSpousePob, 
                                $extSpouseNameOfFather, $extSpouseNameOfMother, $extSpouseBaptismDate, $extSpouseBaptismPlace, $usrID, $dateStr, $orgID){
    $insSQLBZ = "INSERT INTO ccs.baptism(
                bptsm_id, bptsm_sys_code, date_of_baptism, place_of_baptism, mode,
                minister_full_name, godparent_full_name, last_name, 
                first_name, title, gender, dob, pob,  father_full_name, father_religion, mother_full_name, mother_religion, 
                org_id, created_by, creation_date, last_update_by, last_update_date, status)
                VALUES($bptsmID, '" . loc_db_escape_string($transactionNo) . "', '$baptismDate', '" . loc_db_escape_string($baptismPlace) . "', '$baptismMode',
                            '" . loc_db_escape_string($minister) . "', '" . loc_db_escape_string($godParent) . "', '" . loc_db_escape_string($lastName) . "', 
                            '" . loc_db_escape_string($otherNames) . "', '$title', '$gender', '$dob', '" . loc_db_escape_string($pob) . "', '" . loc_db_escape_string($nameOfFather) . "', 
                            '" . loc_db_escape_string($religionOfFather) . "', '" . loc_db_escape_string($nameOfMother) . "', '" . loc_db_escape_string($religionOfMother) . "', 
                            $orgID, $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "', 'Incomplete')";
    
    //var_dump($insSQLBZ);
    
    $insSQLFC = "INSERT INTO ccs.first_communion(
	frst_communion_id, bptsm_id, minister_full_name, communion_date, place_of_first_communion, created_by, creation_date, last_update_by, last_update_date)
	VALUES ($frstCommunionId, $bptsmID, '" . loc_db_escape_string($firstCommMinister) . "', '$firstCommDate', '" . loc_db_escape_string($firstCommPlace) . "',  
            $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    
    $insSQLCF = "INSERT INTO ccs.confirmation(
	cnfrmtn_id, bptsm_id, confirmation_name, godparent_full_name, confirmation_minister, place_of_confirmation, date_of_confirmation, 
        created_by, creation_date, last_update_by, last_update_date)
	VALUES ($cnfrmtnId, $bptsmID, '" . loc_db_escape_string($cnfrmtnName) . "', '" . loc_db_escape_string($cnfrmtnGodParent) . "', '" . loc_db_escape_string($cnfrmtnMinister) . "',
            '" . loc_db_escape_string($cnfrmtnPlace) . "', '$cnfrmtnDate', $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    
    $insSQLHM = "INSERT INTO ccs.holy_matrimony(
	matrimony_id, bptsm_id, matrimony_place, matrimony_date, minister, church, dispensation, bptsm_id_spouse,
        spouse_first_name, spouse_surname, spouse_gender, spouse_dob, spouse_pob, father_of_spouse, mother_of_spouse, 
        spouse_baptism_date, spouse_baptism_place, 
        created_by, creation_date, last_update_by, last_update_date)
	VALUES ($mtrmnyID, $bptsmID, '" . loc_db_escape_string($mtrmnyPlace) . "', '$mtrmnyDate', '" . loc_db_escape_string($mtrmnyMinister) . "', '" 
            . loc_db_escape_string($mtrmnyChurch) . "', '" . loc_db_escape_string($mtrmnyDispensation) . "', $mtrmnyLocalSpouseBaptismId, 
            '" . loc_db_escape_string($extSpouseOtherNames) . "', '" . loc_db_escape_string($extSpouseLastName) . "', '$extSpouseGender', '$extSpouseDob', '" 
            . loc_db_escape_string($extSpousePob) . "', '" . loc_db_escape_string($extSpouseNameOfFather) . "', '" . loc_db_escape_string($extSpouseNameOfMother) . "', 
                '$extSpouseBaptismDate', '" . loc_db_escape_string($extSpouseBaptismPlace) . "', $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";

    $rcBZ = execUpdtInsSQL($insSQLBZ);
    if($rcBZ > 0){
        execUpdtInsSQL($insSQLFC);
        execUpdtInsSQL($insSQLCF);
        return execUpdtInsSQL($insSQLHM);
    }
    
    return 0;   
}

function updateCathSacrament($bptsmID, $transactionNo, $baptismDate, $baptismPlace, $baptismMode,
                                $minister, $godParent, $lastName, $otherNames, $title, $gender, $dob, $pob, $nameOfFather, $religionOfFather, $nameOfMother, $religionOfMother,
                                $frstCommunionId, $firstCommMinister, $firstCommDate, $firstCommPlace, 
                                $cnfrmtnId, $cnfrmtnName, $cnfrmtnGodParent, $cnfrmtnMinister, $cnfrmtnPlace, $cnfrmtnDate, 
                                $mtrmnyID, $mtrmnyPlace, $mtrmnyDate, $mtrmnyChurch, $mtrmnyMinister, $mtrmnyDispensation, 
                                $mtrmnyLocalSpouseBaptismId, $extSpouseLastName, $extSpouseOtherNames, $extSpouseGender, $extSpouseDob, $extSpousePob, 
                                $extSpouseNameOfFather, $extSpouseNameOfMother, $extSpouseBaptismDate, $extSpouseBaptismPlace, $usrID, $dateStr){
    $updtSQLBZ = "UPDATE ccs.baptism SET
                bptsm_sys_code = '" . loc_db_escape_string($transactionNo) . "', 
                date_of_baptism = '$baptismDate', 
                place_of_baptism = '" . loc_db_escape_string($baptismPlace) . "', 
                mode = '$baptismMode',
                minister_full_name = '" . loc_db_escape_string($minister) . "', 
                godparent_full_name = '" . loc_db_escape_string($godParent) . "', 
                last_name = '" . loc_db_escape_string($lastName) . "', 
                first_name = '" . loc_db_escape_string($otherNames) . "', 
                title = '$title', 
                gender = '$gender', 
                dob = '$dob', pob = '" . loc_db_escape_string($pob) . "',  
                father_full_name = '" . loc_db_escape_string($nameOfFather) . "', 
                father_religion = '" . loc_db_escape_string($religionOfFather) . "', 
                mother_full_name = '" . loc_db_escape_string($nameOfMother) . "', 
                mother_religion = '" . loc_db_escape_string($religionOfMother) . "', 
                last_update_by = $usrID, 
                last_update_date = '" . $dateStr . "'
             WHERE bptsm_id = $bptsmID";
    
    $updtSQLFC = "UPDATE ccs.first_communion SET
            minister_full_name = '" . loc_db_escape_string($firstCommMinister) . "', 
            communion_date = '$firstCommDate', 
            place_of_first_communion = '" . loc_db_escape_string($firstCommPlace) . "', 
            last_update_by = $usrID, last_update_date = '" . $dateStr . "' 
        WHERE frst_communion_id = $frstCommunionId";
    
    //var_dump($updtSQLFC);
    
    $updtSQLCF = "UPDATE ccs.confirmation SET
            confirmation_name = '" . loc_db_escape_string($cnfrmtnName) . "', 
            godparent_full_name = '" . loc_db_escape_string($cnfrmtnGodParent) . "', 
            confirmation_minister = '" . loc_db_escape_string($cnfrmtnMinister) . "', 
            place_of_confirmation = '" . loc_db_escape_string($cnfrmtnPlace) . "', 
            date_of_confirmation = '$cnfrmtnDate', 
            last_update_by = $usrID, last_update_date = '" . $dateStr . "'
        WHERE cnfrmtn_id = $cnfrmtnId";
    
    $updtSQLHM = "UPDATE ccs.holy_matrimony SET
            matrimony_place = '" . loc_db_escape_string($mtrmnyPlace) . "', 
            matrimony_date = '$mtrmnyDate', 
            minister = '" . loc_db_escape_string($mtrmnyMinister) . "', 
            church = '" . loc_db_escape_string($mtrmnyChurch) . "', 
            dispensation = '" . loc_db_escape_string($mtrmnyDispensation) . "', 
            bptsm_id_spouse = $mtrmnyLocalSpouseBaptismId,
            spouse_first_name = '" . loc_db_escape_string($extSpouseOtherNames) . "', 
            spouse_surname = '" . loc_db_escape_string($extSpouseLastName) . "', 
            spouse_gender = '$extSpouseGender', 
            spouse_dob = '$extSpouseDob', 
            spouse_pob = '" . loc_db_escape_string($extSpousePob) . "', 
            father_of_spouse = '" . loc_db_escape_string($extSpouseNameOfFather) . "', 
            mother_of_spouse = '" . loc_db_escape_string($extSpouseNameOfMother) . "', 
            spouse_baptism_date = '$extSpouseBaptismDate', 
            spouse_baptism_place = '" . loc_db_escape_string($extSpouseBaptismPlace) . "', 
            last_update_by = $usrID, last_update_date = '" . $dateStr . "' 
        WHERE matrimony_id = $mtrmnyID";
    
    $rcBZ = execUpdtInsSQL($updtSQLBZ);
    if($rcBZ > 0){
        execUpdtInsSQL($updtSQLFC);
        execUpdtInsSQL($updtSQLCF);
        return execUpdtInsSQL($updtSQLHM);
    }
    
    return 0;
}

function insertCathBaptismImport($bptsmID, $transactionNo, $baptismDate, $baptismPlace, $baptismMode, $minister, $godParent, $lastName, $otherNames, $title, $gender, $dob, $pob, 
                                $nameOfFather, $religionOfFather, $nameOfMother, $religionOfMother,  $usrID, $dateStr, $orgID){
    if($baptismDate != ""){
        $baptismDate = cnvrtDMYToYMD($baptismDate);
    }
    
    if($dob != ""){
        $dob = cnvrtDMYToYMD($dob);
    }
    
    
    
    $insSQLBZ = "INSERT INTO ccs.baptism(
                bptsm_id, bptsm_sys_code, date_of_baptism, place_of_baptism, mode,
                minister_full_name, godparent_full_name, last_name, 
                first_name, title, gender, dob, pob,  father_full_name, father_religion, mother_full_name, mother_religion, 
                org_id, created_by, creation_date, last_update_by, last_update_date, status)
                VALUES($bptsmID, '" . loc_db_escape_string($transactionNo) . "', '$baptismDate', '" . loc_db_escape_string($baptismPlace) . "', '$baptismMode',
                            '" . loc_db_escape_string($minister) . "', '" . loc_db_escape_string($godParent) . "', '" . loc_db_escape_string($lastName) . "', 
                            '" . loc_db_escape_string($otherNames) . "', '$title', '$gender', '$dob', '" . loc_db_escape_string($pob) . "', '" . loc_db_escape_string($nameOfFather) . "', 
                            '" . loc_db_escape_string($religionOfFather) . "', '" . loc_db_escape_string($nameOfMother) . "', '" . loc_db_escape_string($religionOfMother) . "', 
                            $orgID, $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "', 'Incomplete')";
    
    
    $insSQLFC = "INSERT INTO ccs.first_communion(bptsm_id, created_by, creation_date, last_update_by, last_update_date)
	VALUES ($bptsmID, $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    
    $insSQLCF = "INSERT INTO ccs.confirmation(bptsm_id, created_by, creation_date, last_update_by, last_update_date)
	VALUES ($bptsmID, $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    
    $insSQLHM = "INSERT INTO ccs.holy_matrimony(bptsm_id, created_by, creation_date, last_update_by, last_update_date)
	VALUES ($bptsmID, $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";

    $rcBZ = execUpdtInsSQL($insSQLBZ);
    if($rcBZ > 0){
        execUpdtInsSQL($insSQLFC);
        execUpdtInsSQL($insSQLCF);
        return execUpdtInsSQL($insSQLHM);
    }
    return 0;   
}

function updateCathBaptismImport($bptsmID, $transactionNo, $baptismDate, $baptismPlace, $baptismMode, $minister, $godParent, $lastName, $otherNames, $title, $gender, $dob, $pob, 
                                $nameOfFather, $religionOfFather, $nameOfMother, $religionOfMother, $usrID, $dateStr){
    if($baptismDate != ""){
        $baptismDate = cnvrtDMYToYMD($baptismDate);
    }
    
    if($dob != ""){
        $dob = cnvrtDMYToYMD($dob);
    }
    
    $updtSQLBZ = "UPDATE ccs.baptism SET
                bptsm_sys_code = '" . loc_db_escape_string($transactionNo) . "', 
                date_of_baptism = '$baptismDate', 
                place_of_baptism = '" . loc_db_escape_string($baptismPlace) . "', 
                mode = '$baptismMode',
                minister_full_name = '" . loc_db_escape_string($minister) . "', 
                godparent_full_name = '" . loc_db_escape_string($godParent) . "', 
                last_name = '" . loc_db_escape_string($lastName) . "', 
                first_name = '" . loc_db_escape_string($otherNames) . "', 
                title = '$title', 
                gender = '$gender', 
                dob = '$dob', pob = '" . loc_db_escape_string($pob) . "',  
                father_full_name = '" . loc_db_escape_string($nameOfFather) . "', 
                father_religion = '" . loc_db_escape_string($religionOfFather) . "', 
                mother_full_name = '" . loc_db_escape_string($nameOfMother) . "', 
                mother_religion = '" . loc_db_escape_string($religionOfMother) . "', 
                last_update_by = $usrID, 
                last_update_date = '" . $dateStr . "'
             WHERE bptsm_id = $bptsmID";
    
    $rcBZ = execUpdtInsSQL($updtSQLBZ);
    return $rcBZ;
}

function updateCathFirstCommunionImport($bptsmID, $firstCommMinister, $firstCommDate, $firstCommPlace,  $usrID, $dateStr){  
    if($firstCommDate != ""){
        $firstCommDate = cnvrtDMYToYMD($firstCommDate);
    }
    
     $updtSQL = "UPDATE ccs.first_communion SET
            minister_full_name = '" . loc_db_escape_string($firstCommMinister) . "', 
            communion_date = '$firstCommDate', 
            place_of_first_communion = '" . loc_db_escape_string($firstCommPlace) . "', 
            last_update_by = $usrID, last_update_date = '" . $dateStr . "' 
        WHERE bptsm_id = $bptsmID";
    
    return execUpdtInsSQL($updtSQL);
}

function updateCathConfirmationImport($bptsmID, $cnfrmtnName, $cnfrmtnGodParent, $cnfrmtnMinister, $cnfrmtnPlace, $cnfrmtnDate,  $usrID, $dateStr){  
    
    if($cnfrmtnDate != ""){
        $cnfrmtnDate = cnvrtDMYToYMD($cnfrmtnDate);
    }
    
     $updtSQL = "UPDATE ccs.confirmation SET
            confirmation_name = '" . loc_db_escape_string($cnfrmtnName) . "', 
            godparent_full_name = '" . loc_db_escape_string($cnfrmtnGodParent) . "', 
            confirmation_minister = '" . loc_db_escape_string($cnfrmtnMinister) . "', 
            place_of_confirmation = '" . loc_db_escape_string($cnfrmtnPlace) . "', 
            date_of_confirmation = '$cnfrmtnDate', 
            last_update_by = $usrID, last_update_date = '" . $dateStr . "'
         WHERE bptsm_id = $bptsmID";
    
    return execUpdtInsSQL($updtSQL);
}

function updateCathHolyMatrimonyImport($bptsmID, $mtrmnyPlace, $mtrmnyDate, $mtrmnyChurch, $mtrmnyMinister, $mtrmnyDispensation, 
                                $mtrmnyLocalSpouseBaptismNo, $extSpouseLastName, $extSpouseOtherNames, $extSpouseGender, $extSpouseDob, $extSpousePob, 
                                $extSpouseNameOfFather, $extSpouseNameOfMother, $extSpouseBaptismDate, $extSpouseBaptismPlace,  $usrID, $dateStr){  
    
    if($mtrmnyDate != ""){
        $mtrmnyDate = cnvrtDMYToYMD($mtrmnyDate);
    }
    
    if($extSpouseDob != ""){
        $extSpouseDob = cnvrtDMYToYMD($extSpouseDob);
    }
    
    if($extSpouseBaptismDate != ""){
        $extSpouseBaptismDate = cnvrtDMYToYMD($extSpouseBaptismDate);
    }
    
    $mtrmnyLocalSpouseBaptismId = -1;
    if($mtrmnyLocalSpouseBaptismNo != ""){
        $mtrmnyLocalSpouseBaptismId = getBptsmIDFromSysCode($mtrmnyLocalSpouseBaptismNo);
    }
    
    
     $updtSQL = "UPDATE ccs.holy_matrimony SET
            matrimony_place = '" . loc_db_escape_string($mtrmnyPlace) . "', 
            matrimony_date = '$mtrmnyDate', 
            minister = '" . loc_db_escape_string($mtrmnyMinister) . "', 
            church = '" . loc_db_escape_string($mtrmnyChurch) . "', 
            dispensation = '" . loc_db_escape_string($mtrmnyDispensation) . "', 
            bptsm_id_spouse = $mtrmnyLocalSpouseBaptismId,
            spouse_first_name = '" . loc_db_escape_string($extSpouseOtherNames) . "', 
            spouse_surname = '" . loc_db_escape_string($extSpouseLastName) . "', 
            spouse_gender = '$extSpouseGender', 
            spouse_dob = '$extSpouseDob', 
            spouse_pob = '" . loc_db_escape_string($extSpousePob) . "', 
            father_of_spouse = '" . loc_db_escape_string($extSpouseNameOfFather) . "', 
            mother_of_spouse = '" . loc_db_escape_string($extSpouseNameOfMother) . "', 
            spouse_baptism_date = '$extSpouseBaptismDate', 
            spouse_baptism_place = '" . loc_db_escape_string($extSpouseBaptismPlace) . "', 
            last_update_by = $usrID, last_update_date = '" . $dateStr . "' 
         WHERE bptsm_id = $bptsmID";
    
    return execUpdtInsSQL($updtSQL);
}

function insertWitnessImport($bptsmID, $witness_name, $witness_for, $usrID, $dateStr)
{
    /*$delSQL1 = "DELETE FROM ccs.matrimony_witness WHERE matrimony_id = (SELECT matrimony_id FROM ccs.holy_matrimony"
            . " WHERE bptsm_id = $bptsmID)";

    execUpdtInsSQL($delSQL1);*/
    
    $mtrmnyID = getMtrmnyIDFromBaptismID($bptsmID);
    
    $insSQL = "INSERT INTO ccs.matrimony_witness(
	matrimony_id, witness_name, witness_for, created_by, creation_date, last_update_by, last_update_date)
	VALUES ($mtrmnyID, '" . loc_db_escape_string($witness_name) . "', '" . loc_db_escape_string($witness_for) . "', $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";

    return execUpdtInsSQL($insSQL);
}

function updateWitnessImport($bptsmID, $witness_name, $witness_for, $usrID, $dateStr)
{
    $mtrmnyID = getMtrmnyIDFromBaptismID($bptsmID);
    
    $updtSQL = "UPDATE ccs.matrimony_witness
	SET witness_for = '" . loc_db_escape_string($witness_for) . "', 
            last_update_by = $usrID, last_update_date = '" . $dateStr . "'
        WHERE witness_name = '" . loc_db_escape_string($witness_name) . "'
        AND matrimony_id = $mtrmnyID";

    return execUpdtInsSQL($updtSQL);
}

function getMtrmnyIDFromBaptismID($bptsmID)
{
    $sqlStr = "SELECT matrimony_id FROM ccs.holy_matrimony WHERE bptsm_id = $bptsmID";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function insertWitness($wtnss_id, $witness_name, $witness_for, $mtrmnyID, $usrID, $dateStr)
{
    global $orgID;
    $insSQL = "INSERT INTO ccs.matrimony_witness(
	wtnss_id, matrimony_id, witness_name, witness_for, created_by, creation_date, last_update_by, last_update_date)
	VALUES ($wtnss_id, $mtrmnyID, '" . loc_db_escape_string($witness_name) . "', '$witness_for', $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";

    return execUpdtInsSQL($insSQL);
}

function updateWitness($wtnss_id, $witness_name, $witness_for, $mtrmnyID, $usrID, $dateStr)
{
    $updtSQL = "UPDATE ccs.matrimony_witness
   SET witness_name='" . loc_db_escape_string($witness_name) . "', 
       witness_for = '$witness_for', 
       last_update_by=$usrID, 
       last_update_date='" . $dateStr . "'
    WHERE wtnss_id = $wtnss_id";

    return execUpdtInsSQL($updtSQL);
}

function deleteWitness($wtnss_id)
{
    $delSQL1 = "DELETE FROM ccs.matrimony_witness WHERE wtnss_id = $wtnss_id";

    return execUpdtInsSQL($delSQL1);
}

function getWitnessID()
{
    $sqlStr = "SELECT nextval('ccs.matrimony_witness_wtnss_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function deleteCathSacrament($bptsm_id){
    $delSQL1 = "DELETE FROM ccs.confirmation WHERE bptsm_id = $bptsm_id";
    $delSQL2 = "DELETE FROM ccs.first_communion WHERE bptsm_id = $bptsm_id";
    $delSQL3 = "DELETE FROM ccs.matrimony_witness WHERE matrimony_id = (SELECT matrimony_id FROM ccs.holy_matrimony WHERE bptsm_id = $bptsm_id)";
    $delSQL4 = "DELETE FROM ccs.holy_matrimony WHERE bptsm_id = $bptsm_id";
    $delSQL5 = "DELETE FROM ccs.baptism WHERE bptsm_id = $bptsm_id";
    
    execUpdtInsSQL($delSQL1);
    execUpdtInsSQL($delSQL2);
    execUpdtInsSQL($delSQL3);
    execUpdtInsSQL($delSQL4);
    return execUpdtInsSQL($delSQL5);
}

function doesBptsmExists($bptsm_sys_code){
    $sqlStr = "SELECT count(1) FROM ccs.baptism WHERE bptsm_sys_code = '" . loc_db_escape_string($bptsm_sys_code) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
}

function getBptsmIDFromSysCode($bptsm_sys_code){
    $sqlStr = "SELECT bptsm_id FROM ccs.baptism WHERE bptsm_sys_code = '" . loc_db_escape_string($bptsm_sys_code) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function checkWtnessExstnc($bptsm_sys_code, $witness){
    $sqlStr = "SELECT COUNT(z.*)
        FROM ccs.baptism x, ccs.holy_matrimony y, ccs.matrimony_witness z
        where x.bptsm_id = y.bptsm_id
        and y.matrimony_id = z.matrimony_id
        AND z.witness_name = '" . loc_db_escape_string($witness) . "'
        AND bptsm_sys_code = '" . loc_db_escape_string($bptsm_sys_code) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

?>
<style>    
    .paccordion {
      background-color: #eee;
      color: #444;
      cursor: pointer;
      padding: 10px;
      width: 100%;
      border: none;
      text-align: left;
      outline: none;
      font-size: 15px;
      transition: 0.4s;
      border-radius: 4px;
    }

    button.active, .paccordion:hover {
      background-color: #B0C4DE;
    }
    
    button.active {
      color: white;
    }

    .paccordion:after {
      content: '\002B';
      color: #777;
      font-weight: bold;
      float: right;
      margin-left: 5px;
    }

    button.active:after {
      content: "\2212";
    }

    .ppanel {
      padding: 10px;
      display: none !important;
      overflow: hidden;
      transition: max-height 0.2s ease-out;
    }
</style>
<script>
$(document).ready(function(){
    var acc = document.getElementsByClassName("paccordion");
    var i;
    
    for(i = 0; i < acc.length; i++){
        acc[i].addEventListener("click", function(event){
            event.preventDefault();
             this.classList.toggle("active");
             
            if($(this).attr("id") !== "baptismDiv"){
                
                var panel = this.nextElementSibling;

                if (panel.style.display === "block") {
                    panel.style.display = "none";
                  } else {
                    panel.style.display = "block";
                  }
               
            } else {
                var panel1 = this.nextElementSibling;
                var panel2 = panel1.nextElementSibling;
                var panel3 = panel2.nextElementSibling;
                
                if (panel1.style.display === "block") {
                    panel1.style.display = "none";
                    panel2.style.display = "none";
                    panel3.style.display = "none";
                  } else {
                    panel1.style.display = "block";
                    panel2.style.display = "block";
                    panel3.style.display = "block";
                  }
            }
        });
    }
});
</script>

