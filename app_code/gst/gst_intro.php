<?php
$menuItems = array("LOVs Setup");
$menuImages = array("chcklst4.png");

$mdlNm = "General Setup";
$ModuleName = $mdlNm;

$dfltPrvldgs = array("View General Setup", "View Value List Names"
    /* 2 */, "View possible values", "Add Value List Names", "Edit Value List Names"
    /* 5 */, "Delete Value List Names", "Add Possible Values", "Edit Possible Values"
    , "Delete Possible Values", "View Record History", "View SQL");

$canview = test_prmssns($dfltPrvldgs[0], $mdlNm);
$vwtyp = "0";
$qstr = "";
$dsply = "";
$actyp = "";
$srchFor = "";
$srchIn = "Name";
$PKeyID = -1;
$sortBy = "ID ASC";
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
if (isset($_POST['sortBy'])) {
    $sortBy = cleanInputData($_POST['sortBy']);
}
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}

$cntent = "<div>
				<ul class=\"breadcrumb\" style=\"$breadCrmbBckclr\">
					<li onclick=\"openATab('#home', 'grp=40&typ=1');\">
                                                <i class=\"fa fa-home\" aria-hidden=\"true\"></i>
						<span style=\"text-decoration:none;\">Home</span>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>
					<li onclick=\"openATab('#allmodules', 'grp=40&typ=5');\">
						<span style=\"text-decoration:none;\">All Modules&nbsp;</span><span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>";
if ($lgn_num > 0 && $canview === true) {
    if ($qstr == "DELETE") {
        if ($actyp == 1) {
            $inptValListID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
            $lovNm = isset($_POST['lovNm']) ? cleanInputData($_POST['lovNm']) : "";
            echo deleteValListNm($inptValListID, $lovNm);
        } else if ($actyp == 2) {
            $inptPssblValID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
            $psbVlNm = isset($_POST['psbVlNm']) ? cleanInputData($_POST['psbVlNm']) : "";
            echo deletePssblVal($inptPssblValID, $psbVlNm);
        }
    } else if ($qstr == "UPDATE") {
        if ($actyp == 1) {
            //LOVs
            $afftctd = 0;
            $inptValListID = isset($_POST['lovDetLovID']) ? cleanInputData($_POST['lovDetLovID']) : -1;
            $definedByCombo = isset($_POST['lovDetDfndBy']) ? cleanInputData($_POST['lovDetDfndBy']) : "USR";
            $valListName = isset($_POST['lovDetLovNm']) ? cleanInputData($_POST['lovDetLovNm']) : "";
            $valListDesc = isset($_POST['lovDetLovDesc']) ? cleanInputData($_POST['lovDetLovDesc']) : "";
            $lovSqlQuery = isset($_POST['lovDetSqlQry']) ? cleanInputData($_POST['lovDetSqlQry']) : "";
            $isLovEnabled = ((isset($_POST['lovDetIsEnabled']) ? cleanInputData($_POST['lovDetIsEnabled']) : "NO") == "YES") ? "1" : "0";
            $isLovDynamic = ((isset($_POST['lovDetIsDynmc']) ? cleanInputData($_POST['lovDetIsDynmc']) : "NO") == "YES") ? "1" : "0";
            $lovDetOrdrByCls = isset($_POST['lovDetOrdrByCls']) ? cleanInputData($_POST['lovDetOrdrByCls']) : "ORDER BY 1 ASC";
            $oldValListID = getGnrlRecID2("gst.gen_stp_lov_names", "value_list_name", "value_list_id", $valListName);

            if ($valListName != "" && $definedByCombo != "" && ($oldValListID <= 0 || $oldValListID == $inptValListID)) {
                if ($inptValListID <= 0) {
                    $afftctd += createValListNm($valListName, $valListDesc, $isLovDynamic, $lovSqlQuery, $definedByCombo, $isLovEnabled, $lovDetOrdrByCls);
                } else {
                    $afftctd += updateValListNm($inptValListID, $valListName, $valListDesc, $isLovDynamic, $lovSqlQuery, $definedByCombo, $isLovEnabled, $lovDetOrdrByCls);
                }
                ?>
                <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                    <div class="row" style="float:none;width:100%;text-align: center;">
                        <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $afftctd; ?> LOV(s) Saved Successfully!</span>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                    <div class="row" style="float:none;width:100%;text-align: center;">
                        <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Save LOV!</span>
                    </div>
                </div>
                <?php
            }
        } else if ($actyp == 2) {
            //Possible Values
            //var_dump($_POST);
            //exit();
            //header("content-type:application/json");
            //categoryCombo
            $afftctd = 0;
            $inptValListID = isset($_POST['lovDetLovID']) ? cleanInputData($_POST['lovDetLovID']) : -1;
            $slctdPsblVals = isset($_POST['slctdPsblVals']) ? cleanInputData($_POST['slctdPsblVals']) : '';
            if (trim($slctdPsblVals, "|~") != "") {
                //Save Possuble Values
                $variousRows = explode("|", trim($slctdPsblVals, "|"));
                for ($z = 0; $z < count($variousRows); $z++) {
                    $crntRow = explode("~", $variousRows[$z]);
                    //print_r($variousRows[$z]);
                    //echo "COUNT::".count($crntRow)."<br/>";
                    if (count($crntRow) == 5) {
                        $inptPssblLovValID = (int) (cleanInputData1($crntRow[0]));
                        $pssblVal = cleanInputData1($crntRow[1]);
                        $allwdOrgIDs = cleanInputData1($crntRow[2]);
                        $pssblValDesc = cleanInputData1($crntRow[3]);
                        $isPssblValEnabled = cleanInputData1($crntRow[4]) == "Yes" ? "1" : "0";
                        $oldPssblLovValID = getPssblValID2($pssblVal, $inptValListID, $pssblValDesc);

                        if ($pssblVal != "" && ($oldPssblLovValID <= 0 || $oldPssblLovValID == $inptPssblLovValID)) {
                            if ($inptPssblLovValID <= 0) {
                                $afftctd += createPssblVals($inptValListID, $pssblVal, $pssblValDesc, $isPssblValEnabled, $allwdOrgIDs);
                            } else {
                                $afftctd += updatePssblVals($inptPssblLovValID, $pssblVal, $pssblValDesc, $isPssblValEnabled, $allwdOrgIDs);
                            }
                        }
                    }
                }
                ?>
                <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                    <div class="row" style="float:none;width:100%;text-align: center;">
                        <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;"><?php echo $afftctd; ?> Possible Value(s) Saved Successfully!</span>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                    <div class="row" style="float:none;width:100%;text-align: center;">
                        <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Save Possible Value(s)!</span>
                    </div>
                </div>
                <?php
            }
        }
    } else if ($pgNo == 0) {
        $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
						<span style=\"text-decoration:none;\">Value Lists Setup Menu</span>
					</li>
                                       </ul>
                                     </div>" . "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
      <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                    font-weight:normal;\">This is where List of Possible Values for Data Fields in the Application are Captured and Managed. The module has the ff areas:</span>
                    </div>
      <p>";
        $grpcntr = 0;

        for ($i = 0; $i < count($menuItems); $i++) {
            $No = $i + 1;
            if ($i == 0) {
                
            }
            if ($grpcntr == 0) {
                $cntent .= "<div class=\"row\">";
            }

            $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=0');\">
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
    } else if ($pgNo == 1) {
        //Get LOVs
        require 'gst_setups.php';
    } else {
        restricted();
    }
} else {
    restricted();
}

function createPssblVals($lovID, $pssblVal, $pssblValDesc, $isEnbld, $allwd) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO gst.gen_stp_lov_values(" .
            "value_list_id, pssbl_value, pssbl_value_desc, " .
            "created_by, creation_date, last_update_by, last_update_date, is_enabled, allowed_org_ids) " .
            "VALUES (" . $lovID . ", '" . loc_db_escape_string($pssblVal) . "', '" . loc_db_escape_string($pssblValDesc) .
            "', " . $usrID . ", '" . $dateStr . "', " . $usrID .
            ", '" . $dateStr . "', '" . $isEnbld . "', '" . loc_db_escape_string($allwd) . "')";
    return execUpdtInsSQL($sqlStr);
}

function updatePssblVals($pssblVlID, $pssblVal, $pssblValDesc, $isEnbld, $allwd) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE gst.gen_stp_lov_values SET " .
            "pssbl_value = '" . loc_db_escape_string($pssblVal) .
            "', pssbl_value_desc = '" . loc_db_escape_string($pssblValDesc) . "', " .
            "last_update_by = " . $usrID .
            ", last_update_date = '" . $dateStr .
            "', is_enabled = '" . $isEnbld . "', " .
            "allowed_org_ids ='" . loc_db_escape_string($allwd) . "' " .
            "WHERE(pssbl_value_id = " . $pssblVlID . ")";
    return execUpdtInsSQL($sqlStr);
}

function deletePssblVal($pssblVlID, $extrInfo = "") {

    $insSQL1 = "DELETE FROM gst.gen_stp_lov_values WHERE pssbl_value_id = " . $pssblVlID;
    $affctd1 = execUpdtInsSQL($insSQL1, "Possible Value Name:" . $extrInfo);

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 LOV Possible Value(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteValListNm($lovID, $extrInfo = "") {
    $insSQL = "DELETE FROM gst.gen_stp_lov_names WHERE value_list_id = " . $lovID;
    $affctd = execUpdtInsSQL($insSQL, "LOV Name:" . $extrInfo);
    $insSQL1 = "DELETE FROM gst.gen_stp_lov_values WHERE value_list_id = " . $lovID;
    $affctd1 = execUpdtInsSQL($insSQL1, "LOV Name:" . $extrInfo);

    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd LOV(s)!";
        $dsply .= "<br/>$affctd1 LOV Possible Value(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createValListNm($lovNm, $lovDesc, $isDyn
        , $sqlQry, $dfndBy, $isEnbld, $lovDetOrdrByCls = "ORDER BY 1 ASC") {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO gst.gen_stp_lov_names(" .
            "value_list_name, value_list_desc, is_list_dynamic, " .
            "sqlquery_if_dyn, defined_by, created_by, creation_date, last_update_by, " .
            "last_update_date, is_enabled, dflt_order_by) " .
            "VALUES ('" . loc_db_escape_string($lovNm) .
            "', '" . loc_db_escape_string($lovDesc) .
            "', '" . loc_db_escape_string($isDyn) .
            "', '" . loc_db_escape_string($sqlQry) .
            "', '" . loc_db_escape_string($dfndBy) .
            "', " . $usrID . ", '" . $dateStr .
            "', " . $usrID . ", '" . $dateStr .
            "', '" . loc_db_escape_string($isEnbld) . "','" . loc_db_escape_string($lovDetOrdrByCls) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateValListNm($lovID, $lovNm, $lovDesc, $isDyn, $sqlQry, $dfndBy, $isEnbld, $lovDetOrdrByCls = "ORDER BY 1 ASC") {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE gst.gen_stp_lov_names SET "
            . "value_list_name = '" . loc_db_escape_string($lovNm) .
            "', value_list_desc = '" . loc_db_escape_string($lovDesc) .
            "', is_list_dynamic = '" . loc_db_escape_string($isDyn) .
            "', sqlquery_if_dyn = '" . loc_db_escape_string($sqlQry) .
            "', defined_by = '" . loc_db_escape_string($dfndBy) .
            "', is_enabled = '" . loc_db_escape_string($isEnbld) .
            "', last_update_by = " . $usrID .
            ", last_update_date = '" . $dateStr .
            "', dflt_order_by = '" . loc_db_escape_string($lovDetOrdrByCls) .
            "'  WHERE(value_list_id = " . $lovID . ")";
    return execUpdtInsSQL($insSQL);
}

function get_LovsPssblVals($searchFor, $searchIn, $offset, $limit_size, $pkID) {
    $wherecls = "";
    $strSql = "";
    global $usrID;
    if (isVlLstDynamic($pkID) == false) {
        if ($searchIn == "Description") {
            $wherecls = " and (a.pssbl_value_desc ilike '" .
                    loc_db_escape_string($searchFor) . "')";
        } else if ($searchIn == "Value") {
            $wherecls = " and (a.pssbl_value ilike '" .
                    loc_db_escape_string($searchFor) . "')";
        }
        $strSql = "SELECT pssbl_value_id, value_list_id, pssbl_value, pssbl_value_desc,  
       is_enabled, allowed_org_ids
  FROM gst.gen_stp_lov_values a " .
                "WHERE (value_list_id=$pkID" . "$wherecls) ORDER BY pssbl_value LIMIT " . $limit_size .
                " OFFSET " . abs($offset * $limit_size);
    } else {
        if ($searchIn == "Description") {
            $wherecls = " and (tbl1.b ilike '" .
                    loc_db_escape_string($searchFor) . "')";
        } else if ($searchIn == "Value") {
            $wherecls = " and (tbl1.a ilike '" .
                    loc_db_escape_string($searchFor) . "')";
        }
        $strSql = "SELECT -1, " . $pkID . ", tbl1.a, tbl1.b, '1', '' FROM (" . str_replace("{:prsn_id}", getUserPrsnID($usrID), getSQLForDynamicVlLst($pkID)) .
                ") tbl1 WHERE 1=1 " . $wherecls .
                " ORDER BY tbl1.a LIMIT " . $limit_size .
                " OFFSET " . abs($offset * $limit_size);
    }


    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_TtlLovsPssblVals($searchFor, $searchIn, $pkID) {
    $wherecls = "";
    $strSql = "";
    global $usrID;
    if (isVlLstDynamic($pkID) == false) {
        if ($searchIn == "Description") {
            $wherecls = " and (a.pssbl_value_desc ilike '" .
                    loc_db_escape_string($searchFor) . "')";
        } else if ($searchIn == "Value") {
            $wherecls = " and (a.pssbl_value ilike '" .
                    loc_db_escape_string($searchFor) . "')";
        }
        $strSql = "SELECT count(1)
  FROM gst.gen_stp_lov_values a " .
                "WHERE (value_list_id=$pkID" . "$wherecls)";
    } else {
        if ($searchIn == "Description") {
            $wherecls = " and (tbl1.b ilike '" .
                    loc_db_escape_string($searchFor) . "')";
        } else if ($searchIn == "Value") {
            $wherecls = " and (tbl1.a ilike '" .
                    loc_db_escape_string($searchFor) . "')";
        }
        $strSql = "SELECT count(1) FROM (" . str_replace("{:prsn_id}", getUserPrsnID($usrID), getSQLForDynamicVlLst($pkID)) .
                ") tbl1 WHERE 1=1 " . $wherecls .
                "";
    }

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_LovsTblr($searchFor, $searchIn, $offset, $limit_size) {
    $wherecls = "";
    $strSql = "";
    if ($searchIn == "LOV Description") {
        $wherecls = " and (a.value_list_desc ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "LOV Name") {
        $wherecls = " and (a.value_list_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }

    $strSql = "SELECT value_list_id, value_list_name, value_list_desc, sqlquery_if_dyn, 
       defined_by, is_list_dynamic, is_enabled FROM gst.gen_stp_lov_names a " .
            "WHERE (1=1$wherecls) ORDER BY a.creation_date DESC, value_list_id DESC, value_list_name LIMIT 

" . $limit_size . " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_LovsTtl($searchFor, $searchIn) {
    $wherecls = "";
    $strSql = "";
    if ($searchIn == "LOV Description") {
        $wherecls = " and (a.value_list_desc ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "LOV Name") {
        $wherecls = " and (a.value_list_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }

    $strSql = "SELECT count(1) FROM gst.gen_stp_lov_names a " .
            "WHERE (1=1$wherecls)";
//echo $strSql;

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_LovsDetail($valLstID) {
    $strSql = "SELECT 
        value_list_id \"LOV ID\", 
        value_list_name \"LOV Name\", 
        value_list_desc \"LOV Description\", 
        sqlquery_if_dyn \"SQL Query\", 
       defined_by \"Defined By\", 
       (CASE WHEN is_list_dynamic='1' THEN 'YES' ELSE 'NO' END) \"Is List Dynamic?\", 
       (CASE WHEN is_enabled='1' THEN 'YES' ELSE 'NO' END) \"Is Enabled?\",
       dflt_order_by
       FROM gst.gen_stp_lov_names a " .
            "WHERE (value_list_id = $valLstID)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_LovsPssblValsDet($pssblValID) {
    $strSql = "SELECT pssbl_value_id \"Possible Value ID\", 
        value_list_id \"LOV ID\", 
        pssbl_value \"Value\", 
        pssbl_value_desc \"Value Description\",  
       (CASE WHEN is_enabled='1' THEN 'YES' ELSE 'NO' END) \"Is Enabled?\", 
       allowed_org_ids \"Allowed Org IDs\"
  FROM gst.gen_stp_lov_values a " .
            "WHERE (pssbl_value_id=" . $pssblValID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}
?>
