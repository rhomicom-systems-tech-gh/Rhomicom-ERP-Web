<?php
$dateStr = getDB_Date_time();
$pkID = $PKeyID;

$prsnid = $_SESSION['PRSN_ID'];

//var_dump($_POST);
$canAddTechSetup = test_prmssns($dfltPrvldgs[29], $mdlNm);
$canEdtTechSetup = test_prmssns($dfltPrvldgs[30], $mdlNm);
$canDelTechSetup = test_prmssns($dfltPrvldgs[31], $mdlNm);

$prsnJob = getPrsnJobNm($prsnid);

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        //echo $_POST['dataToSend'];
        //var_dump($_POST);

        if ($qstr == "JAVASCRIPT") {
            //var_dump($_POST);
            
            
            $bkTypCodeInUse = isTechSetupInActiveUse($PKeyID);
            //check loan status -> Incomplete, Rejected and Withdrawn CAN BE DELETED
            if ($bkTypCodeInUse) {
                echo "SORRY! Lab Investigation is in use";
                exit();
            } else {
                $rowCnt = deleteCreditTechSetups($PKeyID);
                if ($rowCnt > 0) {
                    echo "Lab Investigation Record Deleted Successfully";
                } else {
                    echo "Failed to Delete Lab Investigation Record";
                }
                exit();
            }
        } else if ($qstr == "PHP") {
             $sortCol = isset($_POST['sortCol']) ? cleanInputData($_POST['sortCol']) : '';
             $tableName = isset($_POST['tableNm']) ? cleanInputData($_POST['tableNm']) : '';
             $primaryKeyField = isset($_POST['pKeyField']) ? cleanInputData($_POST['pKeyField']) : '';
             $dpndntTable = isset($_POST['dpndntTable']) ? cleanInputData($_POST['dpndntTable']) : '';
             $fxnName = isset($_POST['fxnNm']) ? cleanInputData($_POST['fxnNm']) : '';
             $foreignKey = isset($_POST['foreignKey']) ? cleanInputData($_POST['foreignKey']) : '';
             $selectStmntFxnName = isset($_POST['selectStmntFxnName']) ? cleanInputData($_POST['selectStmntFxnName']) : '';
             $formType = isset($_POST['formType']) ? cleanInputData($_POST['formType']) : 'tabular';
             $noOfCols = isset($_POST['noOfCols']) ? cleanInputData($_POST['noOfCols']) : 1;
             //$getPKNextValFxnName = isset($_POST['getPKNextValFxnName']) ? cleanInputData($_POST['getPKNextValFxnName']) : '';
             
             $tblColRowList = isset($_POST['tblColRowList']) ? cleanInputData($_POST['tblColRowList']) : '';
             
             $script_output = "";
             $insertScript = "";
             $insertColRows = "";
             $updateColRows = "";
             $delColRow = "";
             $formUpdateRows = "";
                
             $insertParams = "";
             $insertCols = "";
             $insertData = "";
             
             $updateScript = "";
             $updateData = "";
             
             $deleteScript = "";

            $dateStr = getDB_Date_time();
            $recCntInst = 0;
            $recCntUpdt = 0;

            if (trim($tblColRowList, "|~") != "") {
                
                $variousRows = explode("|", trim($tblColRowList, "|"));
                $insertColRows = $variousRows;
                $updateColRows = $variousRows;
                $delColRow = $variousRows;
                $searchColRows = $variousRows;
                $formUpdateRows = $variousRows;
                
                //INSERT
                for ($z = 0; $z < count($insertColRows); $z++) {
                    $crntRow = explode("~", $insertColRows[$z]);
                    if (count($crntRow) == 10) { 
                        
                        $colName =  cleanInputData1($crntRow[0]);
                        $dataType  = cleanInputData1($crntRow[4]);
                        $updateExclude  = cleanInputData1($crntRow[1]);
                        $searchAllowed = cleanInputData1($crntRow[2]);
                        $searchInLabel = cleanInputData1($crntRow[3]);
                        
                        if($dataType == "number"){
                            $insertData.= ",$".$colName; 
                        } else {
                           $insertData.=  ",'\" . loc_db_escape_string(\$$colName) . \"'";
                        }
                        
                        $insertParams = $insertParams.",$".$colName;
                        $insertCols = $insertCols.",".$colName;
                    }
                }
                
                $insertParams = trim($insertParams,",");
                $insertCols = trim($insertCols,",");
                $insertData =  trim($insertData,",");
                
                $insertScript = "function insert".ucfirst($fxnName)."($insertParams) {";
                $insertScript = $insertScript."<br/>";
                $insertScript = $insertScript."\$insSQL = \"INSERT INTO $tableName(";
                $insertScript = $insertScript."<br/>";
                $insertScript = $insertScript.$insertCols.")";
                $insertScript = $insertScript."<br/>";
                $insertScript = $insertScript."VALUES(";
                $insertScript = $insertScript."<br/>";
                $insertScript = $insertScript.$insertData.")\";";
                $insertScript = $insertScript."<br/>";
                $insertScript = $insertScript."<br/>";
                $insertScript = $insertScript."return execUpdtInsSQL(\$insSQL);";
                $insertScript = $insertScript."<br/>";
                $insertScript = $insertScript."}";
                
                //UPDATE
                $rc = 0;
                $ttlRcs = count($updateColRows);
                $rngRcCnt = 0;
                for ($z = 0; $z < $ttlRcs; $z++) {
                    $crntRow = explode("~", $updateColRows[$z]);
                    if (count($crntRow) == 10) { 
                        
                        $rngRcCnt = $rngRcCnt + 1;
                        $colName =  cleanInputData1($crntRow[0]);
                        $dataType  = cleanInputData1($crntRow[4]);
                        $updateExclude  = cleanInputData1($crntRow[1]);
                        $searchAllowed = cleanInputData1($crntRow[2]);
                        $searchInLabel = cleanInputData1($crntRow[3]);
                        
                        if($updateExclude == "No"){
                            if($dataType == "number"){
                                $updateData.= "$colName = $".$colName.","; 
                            } else {
                               $updateData.=  "$colName = '\" . loc_db_escape_string(\$$colName) . \"',";
                            }
                        }
                    }
                }
                
                
                $updateData = trim($updateData,",");
                
                $updateScript = "function update".ucfirst($fxnName)."($insertParams) {";
                $updateScript = $updateScript."<br/>";
                $updateScript = $updateScript."\$updtSQL = \"UPDATE $tableName SET";
                $updateScript = $updateScript."<br/>";
                $updateScript = $updateScript.$updateData;
                $updateScript = $updateScript."<br/>";
                $updateScript = $updateScript." WHERE $primaryKeyField = \$$primaryKeyField\";";
                $updateScript = $updateScript."<br/>";
                $updateScript = $updateScript."<br/>";
                $updateScript = $updateScript."return execUpdtInsSQL(\$updtSQL);";
                $updateScript = $updateScript."<br/>";
                $updateScript = $updateScript."}";
                
                //DELETE
                $deleteScript = "function delete".ucfirst($fxnName)."(\$$primaryKeyField) {";
                $deleteScript = $deleteScript."<br/>";
                $deleteScript = $deleteScript."\$delSQL = \"DELETE FROM $tableName WHERE $primaryKeyField = \$$primaryKeyField\";";
                $deleteScript = $deleteScript."<br/>";
                $deleteScript = $deleteScript."<br/>";
                $deleteScript = $deleteScript."return execUpdtInsSQL(\$delSQL);";
                $deleteScript = $deleteScript."<br/>";
                $deleteScript = $deleteScript."}";
                
                //GET PK SERIAL ID
                
                $pkNextValScript = "function get".ucfirst($primaryKeyField)."() {";
                $pkNextValScript = $pkNextValScript."<br/>";
                $pkNextValScript = $pkNextValScript."&nbsp;&nbsp;&nbsp;&nbsp;\$sqlStr = \"SELECT nextval('{$tableName}_{$primaryKeyField}_seq');\";";
                $pkNextValScript = $pkNextValScript."<br/>";
                $pkNextValScript = $pkNextValScript."&nbsp;&nbsp;&nbsp;&nbsp;\$result = executeSQLNoParams(\$sqlStr);";
                $pkNextValScript = $pkNextValScript."<br/>";
                $pkNextValScript = $pkNextValScript."&nbsp;&nbsp;&nbsp;&nbsp;while(\$row = loc_db_fetch_array(\$result)) {";
                $pkNextValScript = $pkNextValScript."<br/>";
                $pkNextValScript = $pkNextValScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return \$row[0];";
                $pkNextValScript = $pkNextValScript."<br/>";
                $pkNextValScript = $pkNextValScript."&nbsp;&nbsp;&nbsp;&nbsp;}";
                $pkNextValScript = $pkNextValScript."<br/>";
                $pkNextValScript = $pkNextValScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return -1;";
                $pkNextValScript = $pkNextValScript."<br/>";
                $pkNextValScript = $pkNextValScript."}";
                
                //IS PRIMARY KEY IN ACTIVE USE - BEFORE DELETE FUNCTION
                $pkNextInActiveUseScript = "";
                
                    $pkNextInActiveUseScript = "function is".ucfirst($primaryKeyField)."_InActiveUse(\$$primaryKeyField) {";
                    $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                    $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;\$cnt = 0;";
                    $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                    if($dpndntTable != "" && $foreignKey != ""){
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;\$sqlStr = \"SELECT count(*) FROM {$dpndntTable} WHERE {$foreignKey} = \$$primaryKeyField\";";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;\$result = executeSQLNoParams(\$sqlStr);";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;while(\$row = loc_db_fetch_array(\$result)) {";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if((int) \$row[0] > 0) {";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$cnt += 1;";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if((int)\$cnt > 0) {";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return true;";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;} else {";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return false;";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}";
                        $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                    }
                    $pkNextInActiveUseScript = $pkNextInActiveUseScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return false;";
                    $pkNextInActiveUseScript = $pkNextInActiveUseScript."<br/>";
                    $pkNextInActiveUseScript = $pkNextInActiveUseScript."}";
                
                
                //GET TABLE DATA
                $selectAllCols = trim($insertCols,",");
                               
                $selectTableDataScript = "function get".ucfirst($fxnName)."Data($$primaryKeyField) {";
                $selectTableDataScript = $selectTableDataScript."<br/>";
                $selectTableDataScript = $selectTableDataScript."&nbsp;&nbsp;&nbsp;&nbsp;\$sqlStr = \"SELECT $selectAllCols FROM $tableName WHERE {$primaryKeyField} = \$$primaryKeyField\";";
                $selectTableDataScript = $selectTableDataScript."<br/>";
                $selectTableDataScript = $selectTableDataScript."&nbsp;&nbsp;&nbsp;&nbsp;\$result = executeSQLNoParams(\$sqlStr);";
                $selectTableDataScript = $selectTableDataScript."<br/>";
                $selectTableDataScript = $selectTableDataScript."&nbsp;&nbsp;&nbsp;&nbsp;return \$result;";
                $selectTableDataScript = $selectTableDataScript."<br/>";
                $selectTableDataScript = $selectTableDataScript."}";
                
                
                //GET TABULAR REPORT DATA
                
                $whrClsScript = "";
                $srchAlwdCnt = 0;
                                
                //SEARCH CONSTRUCTION
                for ($z = 0; $z < count($searchColRows); $z++) {
                    $crntRow = explode("~", $searchColRows[$z]);
                    if (count($crntRow) == 10) { 
                        
                        $colName =  cleanInputData1($crntRow[0]);
                        $dataType  = cleanInputData1($crntRow[4]);
                        $updateExclude  = cleanInputData1($crntRow[1]);
                        $searchAllowed = cleanInputData1($crntRow[2]);
                        $searchInLabel = cleanInputData1($crntRow[3]);
                        
                        if($searchAllowed == "Yes"){
                            $srchAlwdCnt = $srchAlwdCnt + 1;
                            if($srchAlwdCnt > 1){
                                $whrClsScript.= " else if(\$searchIn == \"$searchInLabel\"){"; 
                                $whrClsScript.="<br/>";
                                if($dataType == "number"){
                                    $whrClsScript.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$whrcls = \" AND ($colName = \$searchFor)\";"; 
                                } else {
                                   $whrClsScript.=  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$whrcls = \" AND ($colName ilike '\" . loc_db_escape_string(\$searchFor) . \"')\";";
                                }
                                $whrClsScript.="<br/>";
                                $whrClsScript.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}"; 
                            } else {
                                $whrClsScript.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; if(\$searchIn == \"$searchInLabel\"){"; 
                                 $whrClsScript.="<br/>";
                                if($dataType == "number"){
                                    $whrClsScript.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$whrcls = \" AND ($colName = \$searchFor)\";"; 
                                } else {
                                   $whrClsScript.=  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$whrcls = \" AND ($colName ilike '\" . loc_db_escape_string(\$searchFor) . \"')\";";
                                }
                                 $whrClsScript.="<br/>";
                                $whrClsScript.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}"; 
                            }
                            
                        }
                    }
                }
                
                if($sortCol == ""){
                    $sortCol = " 1 DESC ";
                }
                
                $tabularReportDataScript = "function get".ucfirst($fxnName)."RptTbl(\$searchFor, \$searchIn, \$offset, \$limit_size, \$searchAll, \$sortBy) {";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."&nbsp;&nbsp;&nbsp;&nbsp;\$extra1 = \"\";";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."&nbsp;&nbsp;&nbsp;&nbsp;if (\$searchAll == true) {";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$extra1 = \"1 = 1\";";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."&nbsp;&nbsp;&nbsp;&nbsp;}";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."&nbsp;&nbsp;&nbsp;&nbsp;\$strSql = \"\";";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."&nbsp;&nbsp;&nbsp;&nbsp;\$whrcls = \"\";";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."$whrClsScript";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."&nbsp;&nbsp;&nbsp;\$strSql = \"SELECT $insertCols";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."&nbsp;&nbsp;&nbsp;FROM $tableName WHERE (1 = 1 AND (\" . \$extra1 . \")\" . \$whrcls .";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."&nbsp;&nbsp;&nbsp;\") ORDER BY $sortCol LIMIT \" . \$limit_size .";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."&nbsp;&nbsp;&nbsp;\" OFFSET \" . abs(\$offset * \$limit_size);";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."&nbsp;&nbsp;\$result = executeSQLNoParams(\$strSql);";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."&nbsp;&nbsp;return \$result;";
                $tabularReportDataScript = $tabularReportDataScript."<br/>";
                $tabularReportDataScript = $tabularReportDataScript."}";
                
                
                //GET TABULAR REPORT DATA TOTAL
                $tabularReportTtlDataScript = "function get".ucfirst($fxnName)."RptTblTtl(\$searchFor, \$searchIn, \$searchAll) {";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp;&nbsp;&nbsp;\$extra1 = \"\";";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp;&nbsp;&nbsp;if (\$searchAll == true) {";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$extra1 = \"1 = 1\";";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp;&nbsp;&nbsp;}";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp;&nbsp;&nbsp;\$strSql = \"\";";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp;&nbsp;&nbsp;\$whrcls = \"\";";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."$whrClsScript";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp;&nbsp;\$strSql = \"SELECT count(1)";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp;&nbsp;FROM $tableName WHERE (1 = 1 AND (\" . \$extra1 . \")\" . \$whrcls .\")\";";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp;\$result = executeSQLNoParams(\$strSql);";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp; while (\$row = loc_db_fetch_array(\$result)) {";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp;return \$row[0];";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp;}";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."&nbsp;&nbsp;return 0;";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."<br/>";
                $tabularReportTtlDataScript = $tabularReportTtlDataScript."}";
                
                
                //TABULAR UPDATE
                //get Required field column count
                $elmntIdFldRows = $formUpdateRows;
                $elmntIdFldCnt = 0;
                 for ($z = 0; $z < count($elmntIdFldRows); $z++) {
                    $crntRow = explode("~", $elmntIdFldRows[$z]);
                    if (count($crntRow) == 10) { 
                        $elementID = cleanInputData1($crntRow[5]);
                        if($elementID != ""){
                            $elmntIdFldCnt = $elmntIdFldCnt + 1;
                        }
                    }
                 }
                
                $frmUpdtRcCnt = count($formUpdateRows);
                $tblUpdateScripts = "";
                if($formType =="tabular"){
                    $tblUpdateScripts = "\$slctd".ucfirst($fxnName)." = isset(\$_POST['slctd".ucfirst($fxnName)."']) ? cleanInputData(\$_POST['slctd".ucfirst($fxnName)."']) : '';";
                    $tblUpdateScripts .= "<br/>";
                    $tblUpdateScripts .= "global \$usrID;<br/>";
                    $tblUpdateScripts .= "\$dateStr = getDB_Date_time();<br/>";
                    $tblUpdateScripts .= "\$created_by = \$usrID;<br/>";
                    $tblUpdateScripts .= "\$last_update_by = \$usrID;<br/>";
                    $tblUpdateScripts .= "\$creation_date = \$dateStr;<br/>";
                    $tblUpdateScripts .= "\$last_update_date = \$dateStr;<br/>";
                    $tblUpdateScripts .= "\$recCntInst = 0;<br/>\$recCntUpdt = 0;<br/>";
                    $tblUpdateScripts .= "if (trim(\$slctd".ucfirst($fxnName).", \"|~\") != \"\") {<br/>";
                    $tblUpdateScripts .= "\$variousRows = explode(\"|\", trim(\$slctd".ucfirst($fxnName).", \"|\"));<br/>";
                    $tblUpdateScripts .= "for (\$z = 0; \$z < count(\$variousRows); \$z++) {<br/>";
                    $tblUpdateScripts .= "\$crntRow = explode(\"~\", \$variousRows[\$z]);<br/>";
                    $tblUpdateScripts .= "if (count(\$crntRow) == $elmntIdFldCnt) { <br/>";
                    
                    $myCnt = 0;
                    $pkCol = "";
                    for ($z = 0; $z < count($formUpdateRows); $z++) {
                        $crntRow = explode("~", $formUpdateRows[$z]);
                        if (count($crntRow) == 10) { 

                            $colName =  cleanInputData1($crntRow[0]);
                            $dataType  = cleanInputData1($crntRow[4]);
                            $updateExclude  = cleanInputData1($crntRow[1]);
                            $searchAllowed = cleanInputData1($crntRow[2]);
                            $searchInLabel = cleanInputData1($crntRow[3]);
                            $elementID = cleanInputData1($crntRow[5]);
                            $elementLabel = cleanInputData1($crntRow[6]);
                            $elementType = cleanInputData1($crntRow[7]);
                            $lovID = (int) cleanInputData1($crntRow[8]);
                            $rqrdFld = cleanInputData1($crntRow[9]);
                            
                            if($primaryKeyField == $colName){//PK 
                                $pkCol = "\$$colName";
                            }
                            
                            //OBTAIN COLUMNS
                            if($colName == "created_by" || $colName == "last_update_by" || $colName == "creation_date" || $colName == "last_update_date"){
                                $myCnt = $myCnt;
                            } else {
                                if($dataType == "number"){
                                    $tblUpdateScripts .= "\$$colName = (int)(cleanInputData1(\$crntRow[$myCnt])); <br/>";
                                } else {
                                    $tblUpdateScripts .= "\$$colName = cleanInputData1(\$crntRow[$myCnt]); <br/>";
                                }
                                $myCnt = $myCnt + 1;
                            }
                            
                        }
                    }
                    
                    $tblUpdateScripts .= "if ($pkCol > 0) {<br/>";
                    $tblUpdateScripts .= "\$recCntUpdt = \$recCntUpdt + update".ucfirst($fxnName)."($insertParams);<br/>";
                    $tblUpdateScripts .= "} else {<br/>";
                    $tblUpdateScripts .= "$pkCol = get".ucfirst($primaryKeyField)."();<br/>";
                    $tblUpdateScripts .= "\$recCntInst = \$recCntInst + insert".ucfirst($fxnName)."($insertParams);<br/>";
                    $tblUpdateScripts .= "}<br/>";
                    $tblUpdateScripts .= "}<br/>";
                    $tblUpdateScripts .= "}<br/>";
                    
                    $tblUpdateScripts .= "echo \"&lt;span style='color:green;font-weight:bold !important;'&gt;&lt;i&gt;\$recCntInst record(s) inserted&lt;/br&gt;\$recCntUpdt record(s) updated&lt;/i&gt;&lt;/span&gt;\";<br/>";
                    $tblUpdateScripts .= "exit();<br/>";
                    $tblUpdateScripts .= " } else {<br/>";
                    $tblUpdateScripts .= " echo '&lt;div&gt;&lt;img src=\"cmn_images/error.gif\" style=\"float:left;margin-right:5px;width:30px;height:30px;\"/&gt;'<br/>";
                    $tblUpdateScripts .= " . 'Please provide one Lab Investigation Record before saving!&lt;br/&gt;&lt;/div&gt;';<br/>"; 
                    $tblUpdateScripts .= "exit();<br/>";
                    $tblUpdateScripts .= "}<br/>";
                    
                    
                    //TABULAR DELETE
                    $tblDeleteScripts = "";
                    $tblDeleteScripts .= "\$recInUse = is".ucfirst($primaryKeyField)."_InActiveUse(\$PKeyID);<br/>";
                    $tblDeleteScripts .= "if (\$recInUse) {<br/>";
                    $tblDeleteScripts .= "&nbsp;&nbsp;&nbsp;&nbsp;echo \"SORRY! Record is in use\";<br/>";
                    $tblDeleteScripts .= "&nbsp;&nbsp;&nbsp;&nbsp;exit();<br/>";
                    $tblDeleteScripts .= "} else {<br/>";
                    $tblDeleteScripts .= "&nbsp;&nbsp;&nbsp;&nbsp;\$rowCnt = delete".ucfirst($fxnName)."(\$PKeyID);<br/>";
                    $tblDeleteScripts .= "if (\$rowCnt > 0) {<br/>";
                    $tblDeleteScripts .= "&nbsp;&nbsp;&nbsp;&nbsp;echo \"Record Deleted Successfully\";<br/>";
                    $tblDeleteScripts .= "&nbsp;&nbsp;} else {<br/>";
                    $tblDeleteScripts .= "&nbsp;&nbsp;&nbsp;&nbsp;echo \"Failed to Delete Record\";<br/>";
                    $tblDeleteScripts .= "&nbsp;&nbsp;}<br/>";
                    $tblDeleteScripts .= "&nbsp;&nbsp;exit();<br/>";
                    $tblDeleteScripts .= "}<br/>";
                              
                    
                } else {//FORM UPDATE
                    
                }
                
                
                
                
                
                
                //TABULAR INSERT
                
                
                echo "PHP SCRIPTS<br/>";
                echo "INSERT SCRIPT<br/>".$insertScript."<br/><br/>UPDATE SCRIPT<br/>".$updateScript."<br/><br/>DELETE SCRIPT<br/>".$deleteScript 
                        ."<br/><br/>GET NEXTPK VAL SCRIPT<br/>".$pkNextValScript."<br/><br/>PRE-DELETION CHECKS SCRIPT<br/>".$pkNextInActiveUseScript
                        ."<br/><br/>TABLE DATA SCRIPT<br/>".$selectTableDataScript."<br/><br/>TABULAR REPORT DATA<br/>".$tabularReportDataScript
                        ."<br/><br/>TABULAR REPORT TOTAL DATA<br/>".$tabularReportTtlDataScript."<br/><br/>TABULAR UPDATE<br/>".$tblUpdateScripts
                        ."<br/><br/>TABULAR DELETE<br/>".$tblDeleteScripts;
                
                echo "<br/>JAVASCRIPTS<br/>";
                
                echo "FORM CONTROLS<br/>";
                exit();
            } else {
                echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                . 'Please provide one Lab Investigation Record before saving!<br/></div>';
                exit();
            }
        } else {
            if ($vwtyp == 0) {
                $error = "";
                $searchAll = true;
                $isEnabledOnly = false;
                if (isset($_POST['isEnabled'])) {
                    $isEnabledOnly = cleanInputData($_POST['isEnabled']);
                }


                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }

                if ($vwtyp == 0) {//3
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&mdl=Clinic/Hospital');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Tech Setup</span>
				</li>
                               </ul>
                              </div>";

                   
                    $curIdx = $pageNo - 1;
                    
                    ?>
                    <form id='allTechSetupsForm' class="form-horizontal" action='' method='post' accept-charset='UTF-8'>
                        <!--<fieldset class="basic_person_fs5">-->
                        <legend class="basic_person_lg1" style="color: #003245">SCRIPT SETUPS</legend>                
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <div class="row rhoRowMargin" style="margin-bottom:10px;">
                            <?php
                            
                                $nwRowHtml = urlencode("<tr id=\"allTechSetupsRow__WWW123WWW\">"
                                                                . "<td class=\"lovtd\">
                                                            <input type=\"text\" style=\"width:100% !important;\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allTechSetupsRow_WWW123WWW_ColNm\" name=\"allTechSetupsRow_WWW123WWW_ColNm\" value=\"\" >       
                                                        </td>                                             
                                                        <td class=\"lovtd\">
                                                            <select class=\"form-control rqrdFld\" id=\"allTechSetupsRow_WWW123WWW_DataType\">
                                                                <option value=\"text\" selected=\"selected\">Text</option>
                                                                <option value=\"number\">number</option>
                                                            </select>                   
                                                        </td>                                             
                                                        <td class=\"lovtd\">
                                                            <select class=\"form-control rqrdFld\" id=\"allTechSetupsRow_WWW123WWW_UpdateExclude\">
                                                                <option value=\"No\" selected=\"selected\">No</option>
                                                                <option value=\"Yes\">Yes</option>
                                                            </select>                   
                                                        </td>                                        
                                                        <td class=\"lovtd\">
                                                            <select class=\"form-control\" id=\"allTechSetupsRow_WWW123WWW_SearchAllowed\">
                                                                <option value=\"No\" selected=\"selected\">No</option>
                                                                <option value=\"Yes\">Yes</option>
                                                            </select>                   
                                                        </td>                                        
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" style=\"width:100% !important;\" class=\"form-control\" aria-label=\"...\" id=\"allTechSetupsRow_WWW123WWW_SearchInLabel\" name=\"allTechSetupsRow_WWW123WWW_SearchInLabel\" value=\"\" >       
                                                        </td>                                        
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" style=\"width:100% !important;\" class=\"form-control\" aria-label=\"...\" id=\"allTechSetupsRow_WWW123WWW_ElementID\" name=\"allTechSetupsRow_WWW123WWW_SearchInLabel\" value=\"\" >       
                                                        </td>                                        
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" style=\"width:100% !important;\" class=\"form-control\" aria-label=\"...\" id=\"allTechSetupsRow_WWW123WWW_ElementLabel\" name=\"allTechSetupsRow_WWW123WWW_SearchInLabel\" value=\"\" >       
                                                        </td>                                        
                                                        <td class=\"lovtd\">
                                                            <select class=\"form-control\" id=\"allTechSetupsRow_WWW123WWW_ElementType\">
                                                                <option value=\"textbox\" selected=\"selected\">TextBox</option>
                                                                <option value=\"number\">Number Field</option>
                                                                <option value=\"date\">Date Field</option>
                                                                <option value=\"textarea\">Text Area</option>
                                                                <option value=\"valuelist\">Value List</option>                                                                
                                                            </select>                   
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <div class=\"input-group\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allTechSetupsRow_WWW123WWW_LovNm\" value=\"\" readonly=\"true\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allTechSetupsRow_WWW123WWW_LovID\" value=\"-1\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', 'allTechSetupsRow_WWW123WWW_LovID', 'allTechSetupsRow_WWW123WWW_LovNm', 'clear', 0, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </td>                                        
                                                        <td class=\"lovtd\">
                                                            <select class=\"form-control\" id=\"allTechSetupsRow_WWW123WWW_Rqrd\">
                                                                <option value=\"Yes\" selected=\"selected\">Yes</option>
                                                                <option value=\"No\">No</option>
                                                            </select>                   
                                                        </td>
                                                        <td class=\"lovtd\" style=\"width: 10px !important; max-width: 10px !important;\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delTechSetup('allTechSetupsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Investigation\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                    </tr>");
                                ?>
                            <div class="col-md-6" style="padding:0px 1px 0px 15px !important;"> 
                                <div style="float:left !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allTechSetupsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Item">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;New Row
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding:0px 15px 0px 3px !important;"> 
                                <div style="float:right !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="generateSetupScript();" data-toggle="tooltip" data-placement="bottom" title="Save TechSetup">
                                        <img src="cmn_images/RSS-2.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Generate Script
                                    </button>
                                </div>
                            </div>
                        </div>  
                        <div class="row" style="margin-bottom:5px !important;"><!-- ROW 1 -->
                            <div class="col-lg-12">
                                    <div class="col-lg-4">  
                                        <div class="form-group form-group-sm">
                                            <label for="tableNm" class="control-label col-md-4">Table Name:</label>
                                            <div class="col-md-8">
                                                <input class="form-control rqrdFld" id="tableNm" type = "text" placeholder="" value=""/>                                                                                                                                            
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="pKeyField" class="control-label col-md-4" >Primary Key Col:</label>
                                            <div  class="col-md-8">
                                               <input class="form-control rqrdFld" id="pKeyField" type = "text" placeholder="" value=""/>    
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="sortCol" class="control-label col-md-4">Sort Column:</label>
                                            <div class="col-md-8">
                                                <input class="form-control rqrdFld" id="sortCol" type = "text" placeholder="" value=""/>                                                                                                                                            
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group form-group-sm">
                                            <label for="fxnNm" class="control-label col-md-4" >Function Name:</label>
                                            <div  class="col-md-8">
                                               <input class="form-control rqrdFld" id="fxnNm" type = "text" placeholder="" value=""/>    
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="dpndntTable" class="control-label col-md-4">Foreign Table:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control rqrdFld" id="dpndntTable" type = "text" placeholder="" value=""/>    
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="foreignKey" class="control-label col-md-4">Foreign Key:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control rqrdFld" id="foreignKey" type = "text" placeholder="" value=""/>    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group form-group-sm">
                                            <label for="formType" class="control-label col-md-4" >Form Type:</label>
                                            <div  class="col-md-8">
                                                <select class="form-control" id="formType">
                                                    <option value="tabular" selected="selected">Tabular</option>
                                                    <option value="non-tabular">Non-Tabular</option>
                                                </select>  
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="noOfCols" class="control-label col-md-4" >Tabular Col No.:</label>
                                            <div  class="col-md-8">
                                               <input class="form-control rqrdFld" id="noOfCols" type = "number" placeholder="" value="" min="1"/>    
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" style="padding:0px 15px 0px 15px !important">                        
                            <div  class="col-md-12" style="padding:0px 1px 0px 1px !important">
                                <div class="" id="allTechSetupsDetailInfo">
                                    <div class="row" id="allTechSetupsDetailInfo" style="padding:0px 15px 0px 15px !important">
                                            <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                                <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                                    <table class="table table-striped table-bordered table-responsive" id="allTechSetupsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>Table Column Name</th>
                                                                <th>Data Type</th>
                                                                <th>Exclude From Update</th>
                                                                <th>Search Allowed</th>
                                                                <th>SearchIn Label</th>
                                                                <th>Element ID</th>
                                                                <th>Element Label</th>
                                                                <th>Element Type</th>
                                                                <th>LOV</th>
                                                                <th>Required</br>Field</th>
                                                                <th style="width: 40px !important; max-width: 40px !important;">&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>                        
                                                </div>                
                                            </div>              
                                    </div>                                   
                                </div>
                            </div>
                        </div>       
                        <div class="row" style="margin-bottom:5px !important;" id="outputScriptID">
                        </div>
                        <!--</fieldset>-->
                    </form>
                    <?php
                }
            }
        }
    }
}