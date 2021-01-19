<?php

$menuItems = array("Organization Setup");
$menuImages = array("BuildingManagement.png");

$mdlNm = "Organization Setup";
$ModuleName = $mdlNm;

$dfltPrvldgs = array(
    "View Organization Setup",
    "View Org Details", "View Divisions/Groups", "View Sites/Locations",
    /* 4 */ "View Jobs", "View Grades", "View Positions", "View Benefits",
    /* 8 */ "View Pay Items", "View Remunerations", "View Working Hours",
    /* 11 */ "View Gathering Types", "View SQL", "View Record History",
    /* 14 */ "Add Org Details", "Edit Org Details",
    /* 16 */ "Add Divisions/Groups", "Edit Divisions/Groups", "Delete Divisions/Groups",
    /* 19 */ "Add Sites/Locations", "Edit Sites/Locations", "Delete Sites/Locations",
    /* 22 */ "Add Jobs", "Edit Jobs", "Delete Jobs",
    /* 25 */ "Add Grades", "Edit Grades", "Delete Grades",
    /* 28 */ "Add Positions", "Edit Positions", "Delete Positions"
);

$canview = test_prmssns($dfltPrvldgs[0], $ModuleName);

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
    if ($pgNo == 0) {
        $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
						<span style=\"text-decoration:none;\">Organization Setup Menu</span>
					</li>
                                       </ul>
                                     </div>" . "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
      <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                    font-weight:normal;\">This is where Organizations are defined and basic Data about the Organisation Captured. The module has the ff areas:</span>
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
        require "org_setups.php";
    } else {
        restricted();
    }
}

function get_OrgLstsTblr($searchWord, $searchIn, $offset, $limit_size)
{
    $whereCls = "1=1";
    if ($searchIn == "Organization Name") {
        $whereCls = "(a.org_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Parent Organisation Name") {
        $whereCls = "((select b.org_name FROM org.org_details b where b.org_id = a.parent_org_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT a.org_id mt, a.org_name FROM org.org_details a 
    WHERE ($whereCls) ORDER BY a.org_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_OrgLstsTblrTtl($searchWord, $searchIn)
{
    $whereCls = "1=1";
    if ($searchIn == "Organization Name") {
        $whereCls = "(a.org_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Parent Organisation Name") {
        $whereCls = "((select b.org_name FROM org.org_details b where b.org_id = a.parent_org_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT count(1) FROM org.org_details a 
    WHERE ($whereCls)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OrgStpsDet($pkID)
{
    $strSql = "SELECT a.org_id mt, 
        a.org_name \"organisation's name\", 
        org_logo, a.parent_org_id mt,
       (select b.org_name FROM 
    org.org_details b where b.org_id = a.parent_org_id) parent_organisation, 
    res_addrs residential_address, pstl_addrs postal_address, 
    email_addrsses email_addresses, websites, cntct_nos contact_nos, org_typ_id mt, 
    (select c.pssbl_value from gst.gen_stp_lov_values 
    c where c.pssbl_value_id = a.org_typ_id) organisation_type, 
    CASE WHEN is_enabled='1' THEN 'Yes' ELSE 'No' END \"is_enabled?\", oprtnl_crncy_id mt, 
    (select d.pssbl_value from gst.gen_stp_lov_values 
    d where d.pssbl_value_id = a.oprtnl_crncy_id) currency_code, org_desc \"organisation's_description\"
    , org_slogan \"organisation's slogan\", no_of_accnt_sgmnts, segment_delimiter, loc_sgmnt_number,sub_loc_sgmnt_number 
    FROM org.org_details a 
    WHERE (a.org_id=$pkID) ORDER BY a.org_name";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_DivsGrps($pkID, $searchWord, $searchIn, $offset, $limit_size)
{
    $whereCls = "";
    if ($searchIn == "Division Name") {
        $whereCls = " and (a.div_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Parent Division Name") {
        $whereCls = " and ((select b.div_code_name FROM org.org_divs_groups b where b.div_id = a.prnt_div_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT a.div_id mt, a.div_code_name \"group_code/name\", a.prnt_div_id mt, (select b.div_code_name FROM 
        org.org_divs_groups b where b.div_id = a.prnt_div_id) \"parent group\", div_typ_id mt, 
        (select c.pssbl_value from gst.gen_stp_lov_values 
        c where c.pssbl_value_id = a.div_typ_id) group_type, 
        div_logo,          
        div_desc \"description/comments\",
        CASE WHEN is_enabled='1' THEN 'Yes' ELSE 'No' END \"is_enabled?\"
        FROM org.org_divs_groups a 
    WHERE ((a.org_id = $pkID)$whereCls) ORDER BY a.div_code_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_DivsGrpsTtl($pkID, $searchWord, $searchIn)
{
    $whereCls = "";
    if ($searchIn == "Division Name") {
        $whereCls = " and (a.div_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Parent Division Name") {
        $whereCls = " and ((select b.div_code_name FROM org.org_divs_groups b where b.div_id = a.prnt_div_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1) 
        FROM org.org_divs_groups a 
    WHERE ((a.org_id = $pkID)$whereCls)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_SitesLocs($pkID, $searchWord, $searchIn, $offset, $limit_size)
{
    $whereCls = "";
    if ($searchIn == "Site Name") {
        $whereCls = " and (a.location_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Site Description") {
        $whereCls = " and (a.site_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT a.location_id mt, a.location_code_name \"location_code/name\", a.site_desc \"description/comments\", 
        CASE WHEN a.is_enabled='1' THEN 'Yes' ELSE 'No' END \"is_enabled?\", 
                a.allwd_group_type, a.allwd_group_value, a.site_type_id, gst.get_pssbl_val(a.site_type_id), 
                a.lnkd_div_id, org.get_div_name(a.lnkd_div_id) div_name,
        REPLACE(org.get_criteria_name(a.allwd_group_value::bigint,a.allwd_group_type),'Everyone','') group_name,
        a.prnt_location_id, org.get_site_code_desc(a.prnt_location_id) prnt_site_nm
        FROM org.org_sites_locations a
        WHERE ((a.org_id = $pkID)$whereCls) 
        ORDER BY a.location_code_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SitesLocsTtl($pkID, $searchWord, $searchIn)
{
    $whereCls = "";
    if ($searchIn == "Site Name") {
        $whereCls = " and (a.location_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Site Description") {
        $whereCls = " and (a.site_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT count(1)  
        FROM org.org_sites_locations a
        WHERE ((a.org_id = $pkID)$whereCls)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_Jobs($pkID, $searchWord, $searchIn, $offset, $limit_size)
{
    $whereCls = "";
    if ($searchIn == "Job Name") {
        $whereCls = " and (a.job_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Parent Job Name") {
        $whereCls = " and ((select b.job_code_name FROM 
        org.org_jobs b where b.job_id = a.parnt_job_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT a.job_id mt, a.job_code_name \"job code/description\", a.parnt_job_id mt, 
        (select b.job_code_name FROM 
         org.org_jobs b where b.job_id = a.parnt_job_id) parent_job, job_comments, 
         CASE WHEN a.is_enabled='1' THEN 'Yes' ELSE 'No' END \"is_enabled?\" 
         FROM org.org_jobs a
        WHERE ((a.org_id = $pkID)$whereCls) 
        ORDER BY a.job_code_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_JobsTtl($pkID, $searchWord, $searchIn)
{
    $whereCls = "";
    if ($searchIn == "Job Name") {
        $whereCls = " and (a.job_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Parent Job Name") {
        $whereCls = " and ((select b.job_code_name FROM 
        org.org_jobs b where b.job_id = a.parnt_job_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT count(1) 
         FROM org.org_jobs a
        WHERE ((a.org_id = $pkID)$whereCls)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_Grades($pkID, $searchWord, $searchIn, $offset, $limit_size)
{
    $whereCls = "";
    if ($searchIn == "Grade Name") {
        $whereCls = " and (a.grade_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Grade Description") {
        $whereCls = " and (a.grade_comments ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT a.grade_id mt, a.grade_code_name \"grade code/name\", 
         a.parnt_grade_id mt, (select b.grade_code_name FROM 
         org.org_grades b where b.grade_id = a.parnt_grade_id) parent_grade
         , a.grade_comments, 
         CASE WHEN a.is_enabled='1' THEN 'Yes' ELSE 'No' END \"is_enabled?\" 
         FROM org.org_grades a
         WHERE ((a.org_id = $pkID)$whereCls) 
         ORDER BY a.grade_code_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_GradesTtl($pkID, $searchWord, $searchIn)
{
    $whereCls = "";
    if ($searchIn == "Grade Name") {
        $whereCls = " and (a.grade_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Grade Description") {
        $whereCls = " and (a.grade_comments ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT count(1) 
         FROM org.org_grades a
         WHERE ((a.org_id = $pkID)$whereCls)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_Pos($pkID, $searchWord, $searchIn, $offset, $limit_size)
{
    $whereCls = "";
    if ($searchIn == "Position Name") {
        $whereCls = " and (a.position_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Position Description") {
        $whereCls = " and (position_comments ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT a.position_id mt, a.position_code_name \"position code/name\"
        , a.prnt_position_id mt, (select b.position_code_name FROM 
         org.org_positions b where b.position_id = a.prnt_position_id) parent_position  
        , a.position_comments, 
        CASE WHEN a.is_enabled='1' THEN 'Yes' ELSE 'No' END \"is_enabled?\" 
        FROM org.org_positions a
        WHERE ((a.org_id = $pkID)$whereCls) 
        ORDER BY a.position_code_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PosTtl($pkID, $searchWord, $searchIn)
{
    $whereCls = "";
    if ($searchIn == "Position Name") {
        $whereCls = " and (a.position_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Position Description") {
        $whereCls = " and (position_comments ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT count(1) 
        FROM org.org_positions a
        WHERE ((a.org_id = $pkID)$whereCls)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createOrg(
    $orgnm,
    $prntID,
    $resAdrs,
    $pstlAdrs,
    $webste,
    $crncyid,
    $email,
    $contacts,
    $orgtypID,
    $isenbld,
    $orgdesc,
    $orgslogan,
    $noOfSegmnts,
    $segDelimiter,
    $locSgmtNum,
    $sublocSgmtNum
) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_details(" .
        "org_name, parent_org_id, res_addrs, pstl_addrs, " .
        "email_addrsses, websites, cntct_nos, org_typ_id, " .
        "org_logo, is_enabled, created_by, creation_date, last_update_by, " .
        "last_update_date, oprtnl_crncy_id, org_desc, org_slogan, no_of_accnt_sgmnts, segment_delimiter, loc_sgmnt_number, sub_loc_sgmnt_number) " .
        "VALUES ('" . loc_db_escape_string($orgnm) . "', " . $prntID . ", '" . loc_db_escape_string($resAdrs) .
        "', '" . loc_db_escape_string($pstlAdrs) . "', '" . loc_db_escape_string($email) . "', " .
        "'" . loc_db_escape_string($webste) . "', '" . loc_db_escape_string($contacts) .
        "', " . $orgtypID . ", '', '" . cnvrtBoolToBitStr($isenbld) . "', " .
        "" . $usrID . ", '" . $dateStr . "', " . $usrID .
        ", '" . $dateStr . "', " . $crncyid .
        ", '" . loc_db_escape_string($orgdesc) . "', '" . loc_db_escape_string($orgslogan) .
        "', " . $noOfSegmnts . ", '" . loc_db_escape_string($segDelimiter) . "', " . $locSgmtNum . ", " . $sublocSgmtNum . ")";
    $result = execUpdtInsSQL($insSQL);
    updtOrgImg(getOrgID($orgnm));
    return $result;
}

function createDiv($orgid, $divnm, $prntID, $divtypID, $isenbld, $divdesc)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_divs_groups(" .
        "org_id, div_code_name, prnt_div_id, div_typ_id, " .
        "div_logo, is_enabled, created_by, creation_date, last_update_by, " .
        "last_update_date, div_desc) " .
        "VALUES (" . $orgid . ", '" . loc_db_escape_string($divnm) . "', " . $prntID . ", " . $divtypID . ", '', '" .
        cnvrtBoolToBitStr($isenbld) . "', " . "" . $usrID . ", '" . $dateStr . "', " . $usrID .
        ", '" . $dateStr . "', '" . loc_db_escape_string($divdesc) . "')";
    $result = execUpdtInsSQL($insSQL);
    updtDivImg(getDivGrpID($divnm, $orgid));
    return $result;
}

function createSite($orgid, $sitenm, $siteDesc, $isenbld, $allwdGrp, $allwdGrpID, $siteTypeID, $dfltDivID, $prntSiteID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_sites_locations(" .
        "location_code_name, org_id, is_enabled, created_by, " .
        "creation_date, last_update_by, last_update_date, site_desc, "
        . "allwd_group_type, allwd_group_value, site_type_id, lnkd_div_id, prnt_location_id) " .
        "VALUES ('" . loc_db_escape_string($sitenm) . "', " . $orgid . ", '" . cnvrtBoolToBitStr($isenbld) . "', " .
        "" . $usrID . ", '" . $dateStr . "', " . $usrID .
        ", '" . $dateStr . "', '" . loc_db_escape_string($siteDesc) . "', '" . loc_db_escape_string($allwdGrp) .
        "', '" . loc_db_escape_string($allwdGrpID) . "', " . $siteTypeID .
        ", " . $dfltDivID .
        ", " . $prntSiteID .
        ")";
    return execUpdtInsSQL($insSQL);
}

function createJob($orgid, $jobnm, $prntJobID, $jobDesc, $isenbld)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_jobs(" .
        "job_code_name, org_id, job_comments, is_enabled, created_by, " .
        "creation_date, last_update_by, last_update_date, parnt_job_id) " .
        "VALUES ('" . loc_db_escape_string($jobnm) . "', " . $orgid . ", '" . loc_db_escape_string($jobDesc) . "', '" .
        cnvrtBoolToBitStr($isenbld) . "', " .
        "" . $usrID . ", '" . $dateStr . "', " . $usrID .
        ", '" . $dateStr . "', " . $prntJobID . ")";
    return execUpdtInsSQL($insSQL);
}

function createGrd($orgid, $grdnm, $prntGrdID, $grdDesc, $isenbld)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_grades(" .
        "grade_code_name, org_id, grade_comments, is_enabled, " .
        "created_by, creation_date, last_update_by, last_update_date, " .
        "parnt_grade_id) " .
        "VALUES ('" . loc_db_escape_string($grdnm) . "', " . $orgid . ", '" . loc_db_escape_string($grdDesc) . "', '" .
        cnvrtBoolToBitStr($isenbld) . "', " .
        "" . $usrID . ", '" . $dateStr . "', " . $usrID .
        ", '" . $dateStr . "', " . $prntGrdID . ")";
    return execUpdtInsSQL($insSQL);
}

function createPos($orgid, $posnm, $prntPosID, $posDesc, $isenbld)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_positions(" .
        "position_code_name, prnt_position_id, position_comments, " .
        "is_enabled, created_by, creation_date, last_update_by, last_update_date, " .
        "org_id) " .
        "VALUES ('" . loc_db_escape_string($posnm) .
        "', " . loc_db_escape_string($prntPosID) .
        ", '" . loc_db_escape_string($posDesc) .
        "', '" . cnvrtBoolToBitStr($isenbld) . "', " .
        "" . $usrID . ", '" . $dateStr . "', " . $usrID .
        ", '" . $dateStr . "', " . $orgid . ")";
    return execUpdtInsSQL($insSQL);
}

function createWkhr($orgid, $wkhnm, $wkhDesc, $isenbld)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_wrkn_hrs(" .
        "org_id, work_hours_name, work_hours_desc, is_enabled, " .
        "created_by, creation_date, last_update_by, last_update_date) " .
        "VALUES (" . $orgid . ", '" . loc_db_escape_string($wkhnm) .
        "',  '" . loc_db_escape_string($wkhDesc) . "', '" .
        cnvrtBoolToBitStr($isenbld) . "', " .
        "" . $usrID . ", '" . $dateStr . "', " . $usrID .
        ", '" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function createWkhrDet($wkhid, $weekday, $strtTm, $endTm)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_wrkn_hrs_details(" .
        "work_hours_id, day_of_week, dflt_nrml_start_time, dflt_nrml_close_time, " .
        "created_by, creation_date, last_update_by, last_update_date, day_of_wk_no) " .
        "VALUES (" . $wkhid . ", '" . loc_db_escape_string($weekday) .
        "',  '" . loc_db_escape_string($strtTm) . "', '" .
        loc_db_escape_string($endTm) . "', " .
        $usrID . ", '" . $dateStr . "', " . $usrID .
        ", '" . $dateStr . "', " . getDOWNum($weekday) . ")";
    return execUpdtInsSQL($insSQL);
}

function createGath($orgid, $gthnm, $gthDesc, $isenbld, $strtTm, $endTm)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_gthrng_types(" .
        "gthrng_typ_name, gthrng_typ_desc, org_id, is_enabled, " .
        "created_by, creation_date, last_update_by, last_update_date, " .
        "gath_start_time, gath_end_time) " .
        "VALUES ('" . loc_db_escape_string($gthnm) . "',  '" . loc_db_escape_string($gthDesc) .
        "', " . $orgid . ", '" . cnvrtBoolToBitStr($isenbld) . "', " .
        $usrID . ", '" . $dateStr . "', " . $usrID .
        ", '" . $dateStr . "', '" . loc_db_escape_string($strtTm) .
        "', '" . loc_db_escape_string($endTm) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateGath($gthid, $gthnm, $gthDesc, $isenbld, $strtTm, $endTm)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_gthrng_types " .
        "SET gthrng_typ_name='" . loc_db_escape_string($gthnm) .
        "', gthrng_typ_desc='" . loc_db_escape_string($gthDesc) . "', " .
        "gath_start_time='" . loc_db_escape_string($strtTm) .
        "', gath_end_time='" . loc_db_escape_string($endTm) .
        "', last_update_by=" . $usrID . ", " .
        "last_update_date='" . $dateStr . "', is_enabled = '" . cnvrtBoolToBitStr($isenbld) .
        "' WHERE gthrng_typ_id=" . $gthid;
    return execUpdtInsSQL($updtSQL);
}

function updateWkhrDet($row_id, $wkhid, $weekday, $strtTm, $endTm)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_wrkn_hrs_details " .
        "SET work_hours_id=" . $wkhid .
        ", day_of_week='" . loc_db_escape_string($weekday) . "', " .
        "dflt_nrml_start_time='" . loc_db_escape_string($strtTm) .
        "', dflt_nrml_close_time='" . loc_db_escape_string($endTm) .
        "', last_update_by=" . $usrID . ", " .
        "last_update_date='" . $dateStr . "', day_of_wk_no = " . getDOWNum($weekday) .
        " WHERE dflt_row_id=" . $row_id;
    return execUpdtInsSQL($updtSQL);
}

function updateWkhr($wkhid, $wkhnm, $wkhDesc, $isenbld)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_wrkn_hrs " .
        "SET work_hours_name='" . loc_db_escape_string($wkhnm) .
        "', work_hours_desc='" . loc_db_escape_string($wkhDesc) . "', " .
        "is_enabled='" . cnvrtBoolToBitStr($isenbld) .
        "', last_update_by=" . $usrID . ", " .
        "last_update_date='" . $dateStr . "' " .
        "WHERE work_hours_id=" . $wkhid;
    return execUpdtInsSQL($updtSQL);
}

function getDOWNum($weekday)
{
    $dynms = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    for ($i = 0; $i < 7; $i++) {
        if ($dynms[$i] == $weekday) {
            return $i + 1;
        }
    }
    return -1;
}

function updtPosPrntID($posid, $prntID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_positions SET prnt_position_id = " . $prntID .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "' " .
        "WHERE (position_id = " . $posid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updatePos($posid, $posnm, $prntPosID, $posDesc, $isenbld)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_positions " .
        "SET position_code_name='" . loc_db_escape_string($posnm) .
        "', prnt_position_id=" . $prntPosID . ", position_comments='" . loc_db_escape_string($posDesc) . "', " .
        "is_enabled='" . cnvrtBoolToBitStr($isenbld) .
        "', last_update_by=" . $usrID . ", " .
        "last_update_date='" . $dateStr . "' " .
        "WHERE position_id=" . $posid;
    return execUpdtInsSQL($updtSQL);
}

function updtGrdPrntID($grdid, $prntID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_grades SET parnt_grade_id = " . $prntID .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "' " .
        "WHERE (grade_id = " . $grdid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updateGrd($grdid, $grdnm, $prntGrdID, $grdDesc, $isenbld)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_grades " .
        "SET grade_code_name='" . loc_db_escape_string($grdnm) .
        "', grade_comments='" . loc_db_escape_string($grdDesc) . "', is_enabled='" . cnvrtBoolToBitStr($isenbld) . "', " .
        "last_update_by=" . $usrID . ", last_update_date='" . $dateStr . "', " .
        "parnt_grade_id=" . $prntGrdID . " WHERE grade_id=" . $grdid;
    return execUpdtInsSQL($updtSQL);
}

function updtJobPrntID($jobID, $prntID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_jobs SET parnt_job_id = " . $prntID .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "' " .
        "WHERE (job_id = " . $jobID . ")";
    return execUpdtInsSQL($updtSQL);
}

function updateJob($jobid, $jobnm, $prntJobID, $jobDesc, $isenbld)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_jobs " .
        "SET job_code_name='" . loc_db_escape_string($jobnm) .
        "', job_comments='" . loc_db_escape_string($jobDesc) .
        "', is_enabled='" . cnvrtBoolToBitStr($isenbld) . "', " .
        "last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr . "', " .
        "parnt_job_id=" . $prntJobID . " WHERE job_id= " . $jobid;
    return execUpdtInsSQL($updtSQL);
}

function updtOrgImg($orgID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_details SET " .
        "org_logo = '" . $orgID . ".png', " .
        "last_update_by = " . $usrID .
        ", last_update_date = '" . $dateStr . "' " .
        "WHERE(org_id = " . $orgID . ")";
    return execUpdtInsSQL($updtSQL);
}

function uploadDaOrgImage($orgid, &$nwImgLoc)
{
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    if (isset($_FILES["daOrgPicture"])) {
        //$files = multiple($_FILES);
        $flnm = $_FILES["daOrgPicture"]["name"];
        $msg .= $flnm;
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daOrgPicture"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daOrgPicture"]["error"] . "<br>";
        } else {
            $msg .= "Upload: " . $_FILES["daOrgPicture"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daOrgPicture"]["type"] . "<br>";
            $msg .= "Size: " . ($_FILES["daOrgPicture"]["size"]) . " bytes<br>";
            $msg .= "Temp file: " . $_FILES["daOrgPicture"]["tmp_name"] . "<br>";
            if ((($_FILES["daOrgPicture"]["type"] == "image/gif") || ($_FILES["daOrgPicture"]["type"] == "image/jpeg") || ($_FILES["daOrgPicture"]["type"] ==
                "image/jpg") || ($_FILES["daOrgPicture"]["type"] == "image/pjpeg") || ($_FILES["daOrgPicture"]["type"] == "image/x-png") || ($_FILES["daOrgPicture"]["type"] ==
                "image/png") || in_array($extension, $allowedExts)) && ($_FILES["daOrgPicture"]["size"] < 2000000)) {
                $nwFileName = encrypt1($orgid . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daOrgPicture"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/Org/$orgid" . "." . $extension;
                $ftp_srcPnG = $ftp_base_db_fldr . "/Org/$orgid" . ".png";
                $fullRptDest = $fldrPrfx . "dwnlds/amcharts_2100/images/" .  $orgid . "." . $extension;
                $fullRptDestPnG = $fldrPrfx . "dwnlds/amcharts_2100/images/" . $orgid . ".png";
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");
                    copy("$img_src", "$ftp_srcPnG");
                    copy("$img_src", "$fullRptDest");
                    copy("$img_src", "$fullRptDestPnG");
                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE org.org_details SET " .
                        "org_logo = '" . $orgid . "." . $extension . "', " .
                        "last_update_by = " . $usrID .
                        ", last_update_date = '" . $dateStr . "' " .
                        "WHERE(org_id = " . $orgid . ")";
                    execUpdtInsSQL($updtSQL);
                }
                $msg .= "Image Stored";
                $nwImgLoc = "$orgid" . "." . $extension;
                return TRUE;
            } else {
                $msg .= "<br/>Invalid file!<br/>File Size must be below 2MB and<br/>File Type must be in the ff:<br/>" . implode(", ", $allowedExts);
                $nwImgLoc = $msg;
            }
        }
    }
    $msg .= "<br/>Invalid file";
    $nwImgLoc = $msg;
    return FALSE;
}

function updtOrgPrntID($orgID, $prntID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_details SET parent_org_id = " . $prntID .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "' " .
        "WHERE (org_id = " . $orgID . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtOrgCrncyID($orgID, $crncyid)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_details SET last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "', oprtnl_crncy_id = " . $crncyid . " " .
        "WHERE (org_id = " . $orgID . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtOrgTypID($orgID, $orgtypID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_details SET org_typ_id = " . $orgtypID .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "' " .
        "WHERE (org_id = " . $orgID . ")";
    return execUpdtInsSQL($updtSQL);
}

function updateOrgDet(
    $orgid,
    $orgnm,
    $prntID,
    $resAdrs,
    $pstlAdrs,
    $webste,
    $crncyid,
    $email,
    $contacts,
    $orgtypID,
    $isenbld,
    $orgdesc,
    $orgslogan,
    $noOfSegmnts,
    $segDelimiter,
    $locSgmtNum,
    $sublocSgmtNum
) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_details SET " .
        "org_name = '" . loc_db_escape_string($orgnm) .
        "', parent_org_id = " . $prntID .
        ", res_addrs = '" . loc_db_escape_string($resAdrs) .
        "', pstl_addrs = '" . loc_db_escape_string($pstlAdrs) . "', " .
        "email_addrsses = '" . loc_db_escape_string($email) .
        "', websites = '" . loc_db_escape_string($webste) .
        "', cntct_nos = '" . loc_db_escape_string($contacts) .
        "', org_typ_id = " . $orgtypID . ", " .
        "is_enabled = '" . cnvrtBoolToBitStr($isenbld) .
        "', last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "', oprtnl_crncy_id = " . $crncyid .
        ", org_desc = '" . loc_db_escape_string($orgdesc) .
        "', org_slogan='" . loc_db_escape_string($orgslogan) .
        "', no_of_accnt_sgmnts = " . $noOfSegmnts .
        ", segment_delimiter = '" . loc_db_escape_string($segDelimiter) .
        "', loc_sgmnt_number = " . $locSgmtNum .
        ", sub_loc_sgmnt_number = " . $sublocSgmtNum .
        " WHERE (org_id = " . $orgid . ")";
    //var_dump($updtSQL);
    return execUpdtInsSQL($updtSQL);
}

function updtDivImg($divID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_divs_groups SET " .
        "div_logo = '" . $divID . ".png', " .
        "last_update_by = " . $usrID .
        ", last_update_date = '" . $dateStr . "' " .
        "WHERE(div_id = " . $divID . ")";
    return execUpdtInsSQL($updtSQL);
}

function uploadDaDivImage($divid, &$nwImgLoc)
{
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    if (isset($_FILES["daDivPicture"])) {
        //$files = multiple($_FILES);
        $flnm = $_FILES["daOrgPicture"]["name"];
        $msg .= $flnm;
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daDivPicture"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daDivPicture"]["error"] . "<br>";
        } else {
            $msg .= "Upload: " . $_FILES["daDivPicture"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daDivPicture"]["type"] . "<br>";
            $msg .= "Size: " . ($_FILES["daDivPicture"]["size"]) . " bytes<br>";
            $msg .= "Temp file: " . $_FILES["daDivPicture"]["tmp_name"] . "<br>";
            if ((($_FILES["daDivPicture"]["type"] == "image/gif") || ($_FILES["daDivPicture"]["type"] == "image/jpeg") || ($_FILES["daDivPicture"]["type"] ==
                "image/jpg") || ($_FILES["daDivPicture"]["type"] == "image/pjpeg") || ($_FILES["daDivPicture"]["type"] == "image/x-png") || ($_FILES["daDivPicture"]["type"] ==
                "image/png") || in_array($extension, $allowedExts)) && ($_FILES["daDivPicture"]["size"] < 2000000)) {
                $nwFileName = encrypt1($divid . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daDivPicture"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/Divs/$divid" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");

                    $dateStr = getDB_Date_time();
                    $updtSQL = $sqlStr = "UPDATE org.org_divs_groups SET " .
                        "div_logo = '" . $divid . "." . $extension . "', " .
                        "last_update_by = " . $usrID .
                        ", last_update_date = '" . $dateStr . "' " .
                        "WHERE(div_id = " . $divid . ")";
                    execUpdtInsSQL($updtSQL);
                }
                $msg .= "Image Stored";
                $nwImgLoc = "$divid" . "." . $extension;
                return TRUE;
            } else {
                $msg .= "<br/>Invalid file!<br/>File Size must be below 2MB and<br/>File Type must be in the ff:<br/>" . implode(", ", $allowedExts);
                $nwImgLoc = $msg;
            }
        }
    }
    $msg .= "<br/>Invalid file";
    $nwImgLoc = $msg;
    return FALSE;
}

function updateDivDet($divid, $divnm, $prntID, $divtypID, $isenbld, $divdesc)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_divs_groups SET " .
        "div_code_name = '" . loc_db_escape_string($divnm) .
        "', prnt_div_id = " . $prntID .
        ", div_typ_id = " . $divtypID . ", " .
        "is_enabled = '" . cnvrtBoolToBitStr($isenbld) .
        "', last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr .
        "', div_desc = '" . loc_db_escape_string($divdesc) . "' " .
        "WHERE (div_id = " . $divid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtDivPrntID($divID, $prntID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_divs_groups SET prnt_div_id = " . $prntID .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "' " .
        "WHERE (div_id = " . $divID . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtDivTypID($divID, $divtypID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_divs_groups SET div_typ_id = " . $divtypID .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "' " .
        "WHERE (div_id = " . $divID . ")";
    return execUpdtInsSQL($updtSQL);
}

function updateSiteDet($siteid, $sitenm, $siteDesc, $isenbld, $allwdGrp, $allwdGrpID, $siteTypeID, $dfltDivID, $prntSiteID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_sites_locations SET " .
        "location_code_name = '" . loc_db_escape_string($sitenm) . "', " .
        "is_enabled = '" . cnvrtBoolToBitStr($isenbld) .
        "', site_desc = '" . loc_db_escape_string($siteDesc) .
        "', last_update_by = " . $usrID .
        ", last_update_date = '" . $dateStr .
        "', allwd_group_type = '" . loc_db_escape_string($allwdGrp) .
        "', allwd_group_value = '" . loc_db_escape_string($allwdGrpID) .
        "', site_type_id = " . $siteTypeID .
        ", lnkd_div_id = " . $dfltDivID .
        ", prnt_location_id = " . $prntSiteID .
        " WHERE (location_id = " . $siteid . ")";
    //echo $updtSQL;
    return execUpdtInsSQL($updtSQL);
}

function deleteWkhDet($row_id, $wkhNm)
{
    $delSQL = "DELETE FROM org.org_wrkn_hrs_details WHERE dflt_row_id = " . $row_id;
    return execUpdtInsSQL($delSQL);
}

function deleteWkh($row_id, $wkhNm)
{
    $delSQL = "DELETE FROM org.org_wrkn_hrs WHERE work_hours_id = " . $row_id;
    return execUpdtInsSQL($delSQL);
}

function deleteGth($row_id, $gthnm)
{
    $delSQL = "DELETE FROM org.org_gthrng_types WHERE gthrng_typ_id = " . $row_id;
    return execUpdtInsSQL($delSQL);
}

function deleteDiv($divid, $divNm)
{
    $strSql = "SELECT count(a.prsn_div_id) FROM pasn.prsn_divs_groups a WHERE(a.div_id = " . $divid . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete a Division/Group assigned to Persons!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $strSql = "SELECT count(a.location_id) FROM org.org_sites_locations a WHERE(a.lnkd_div_id = " . $divid . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete a Division/Group assigned to Sites/Locations!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM org.org_divs_groups WHERE div_id = " . $divid;
    $affctd1 = execUpdtInsSQL($delSQL, "Div/Grp Name:" . $divNm);
    $updtSQL = "UPDATE org.org_divs_groups SET prnt_div_id=-1 WHERE prnt_div_id = " . $divid;
    execUpdtInsSQL($updtSQL, "Div/Grp Name:" . $divNm);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Divisions/Groups!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteOrg($orgid, $orgNm)
{
    $delSQL = "DELETE FROM org.org_divs_groups WHERE org_id = " . $orgid;
    execUpdtInsSQL($delSQL);
    $delSQL = "DELETE FROM org.org_grades WHERE org_id = " . $orgid;
    execUpdtInsSQL($delSQL);
    $delSQL = "DELETE FROM org.org_gthrng_types WHERE org_id = " . $orgid;
    execUpdtInsSQL($delSQL);
    $delSQL = "DELETE FROM org.org_jobs WHERE org_id = " . $orgid;
    execUpdtInsSQL($delSQL);
    $delSQL = "DELETE FROM org.org_pay_items WHERE org_id = " . $orgid;
    execUpdtInsSQL($delSQL);
    $delSQL = "DELETE FROM org.org_pay_items_values WHERE item_id NOT IN (select item_id from org.org_pay_items WHERE org_id = " . $orgid . ")";
    execUpdtInsSQL($delSQL);
    $delSQL = "DELETE FROM org.org_pay_itm_feeds WHERE balance_item_id NOT IN (select item_id from org.org_pay_items WHERE org_id = " . $orgid . ")";
    execUpdtInsSQL($delSQL);
    $delSQL = "DELETE FROM org.org_positions WHERE org_id = " . $orgid;
    execUpdtInsSQL($delSQL);
    $delSQL = "DELETE FROM org.org_sites_locations WHERE org_id = " . $orgid;
    execUpdtInsSQL($delSQL);
    $delSQL = "DELETE FROM org.org_wrkn_hrs WHERE org_id = " . $orgid;
    execUpdtInsSQL($delSQL);
    $delSQL = "DELETE FROM org.org_wrkn_hrs_details WHERE work_hours_id NOT IN (select work_hours_id from org.org_wrkn_hrs WHERE org_id = " . $orgid . ")";
    execUpdtInsSQL($delSQL);
    $delSQL = "DELETE FROM org.org_details WHERE org_id = " . $orgid;
    execUpdtInsSQL($delSQL);
}

function deleteSite($siteid, $siteNm)
{
    $strSql = "SELECT count(a.prsn_loc_id) FROM pasn.prsn_locations a WHERE(a.location_id = " . $siteid . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete a Site/Location assigned to Persons!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM org.org_sites_locations WHERE location_id = " . $siteid;
    $affctd1 = execUpdtInsSQL($delSQL, "Site/Loc Name:" . $siteNm);
    $updtSQL = "UPDATE org.org_sites_locations SET prnt_location_id=-1 WHERE prnt_location_id = " . $siteid;
    execUpdtInsSQL($updtSQL, "Site/Loc Name:" . $siteNm);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Sites/Locations!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteJob($jobid, $jobNm)
{
    $strSql = "SELECT count(a.row_id) FROM pasn.prsn_jobs a WHERE(a.job_id = " . $jobid . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete a Job assigned to Persons!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM org.org_jobs WHERE job_id = " . $jobid;
    $affctd1 = execUpdtInsSQL($delSQL, "Job Name:" . $jobNm);
    $updtSQL = "UPDATE org.org_jobs SET parnt_job_id=-1 WHERE parnt_job_id = " . $jobid;
    execUpdtInsSQL($updtSQL, "Job Name:" . $jobNm);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Job(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deletePos($posid, $posNm)
{
    $strSql = "SELECT count(a.row_id) FROM pasn.prsn_positions a WHERE(a.position_id = " . $posid . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete a Position assigned to Persons!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM org.org_positions WHERE position_id = " . $posid;
    $affctd1 = execUpdtInsSQL($delSQL, "Position Name:" . $posNm);
    $updtSQL = "UPDATE org.org_positions SET prnt_position_id=-1 WHERE prnt_position_id = " . $posid;
    execUpdtInsSQL($updtSQL, "Position Name:" . $posNm);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Position(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteGrd($grdid, $grdNm)
{
    $strSql = "SELECT count(a.row_id) FROM pasn.prsn_grades a WHERE(a.grade_id = " . $grdid . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete a Grade assigned to Persons!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM org.org_grades WHERE grade_id = " . $grdid;
    $affctd1 = execUpdtInsSQL($delSQL, "Grade Name:" . $grdNm);
    $updtSQL = "UPDATE org.org_grades SET parnt_grade_id=-1 WHERE parnt_grade_id = " . $grdid;
    execUpdtInsSQL($updtSQL, "Grade Name:" . $grdNm);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Grade(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getOrgnstnID($orgName)
{
    $strSQL = "SELECT org_id FROM org.org_details WHERE lower(org_name) = lower('" . loc_db_escape_string($orgName) . "')";
    $result = executeSQLNoParams($strSQL);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
    //$conn
}

function getDivGrpID($divName, $inOrgID)
{
    $strSQL = "SELECT div_id FROM org.org_divs_groups WHERE lower(div_code_name) = lower('"
        . loc_db_escape_string($divName) . "') and org_id = " . $inOrgID;
    $result = executeSQLNoParams($strSQL);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
    //$conn
}

function getSiteID($sitename, $orgid)
{
    $sqlStr = "select location_id from org.org_sites_locations where lower(location_code_name) = '" .
        loc_db_escape_string($sitename) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getJobID($jobname, $orgid)
{
    $sqlStr = "select job_id from org.org_jobs where lower(job_code_name) = '" .
        loc_db_escape_string($jobname) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getGradeID($gradename, $orgid)
{
    $sqlStr = "select grade_id from org.org_grades where lower(grade_code_name) = '" .
        loc_db_escape_string($gradename) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getPosID($posname, $orgid)
{
    $sqlStr = "select position_id from org.org_positions where lower(position_code_name) = '" .
        loc_db_escape_string($posname) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function get_One_SegmentDet($segNum, $orgid)
{
    $strSql = "SELECT segment_id, segment_name_prompt, system_clsfctn, prnt_sgmnt_number 
        FROM org.org_acnt_sgmnts a  WHERE((a.org_id = " . $orgid . " and a.segment_number = " . $segNum . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Org_SegmentDet($orgid)
{
    $strSql = "SELECT segment_id, segment_number, segment_name_prompt, system_clsfctn, prnt_sgmnt_number 
        FROM org.org_acnt_sgmnts a  WHERE((a.org_id = " . $orgid . ")) ORDER BY segment_number ASC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SegmnetID($orgid, $segNum)
{
    $strSql = "SELECT segment_id FROM org.org_acnt_sgmnts a  " .
        " WHERE((a.org_id = " . $orgid . " and a.segment_number = " . $segNum . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function createAcntSegment($orgID, $segmntNum, $segmntName, $sysClsfctn, $prntSegmentNum)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_acnt_sgmnts (
            segment_number, segment_name_prompt, system_clsfctn, 
            created_by, creation_date, last_update_by, last_update_date, 
            org_id, prnt_sgmnt_number) " .
        "VALUES (" . $segmntNum . ", '" . loc_db_escape_string($segmntName) . "', '" . loc_db_escape_string($sysClsfctn) .
        "', " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr .
        "', " . $orgID . "," . $prntSegmentNum . ")";
    return execUpdtInsSQL($insSQL);
}

function updtAcntSegment($segmntID, $segmntNum, $segmntName, $sysClsfctn, $prntSegmentNum)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE org.org_acnt_sgmnts SET 
                        segment_name_prompt='" . loc_db_escape_string($segmntName) .
        "', system_clsfctn='" . loc_db_escape_string($sysClsfctn) .
        "', last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr .
        "', prnt_sgmnt_number=" . $prntSegmentNum .
        ", segment_number=" . $segmntNum .
        " WHERE segment_id=" . $segmntID . " ";
    return execUpdtInsSQL($insSQL);
}

function get_Basic_SgmntVals($searchWord, $searchIn, $offset, $limit_size, $segmentID)
{
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Dependent Value") {
        $whrcls = " AND ((COALESCE(org.get_sgmnt_val(dpndnt_sgmnt_val_id),'')||'.'||COALESCE(org.get_sgmnt_val_desc(dpndnt_sgmnt_val_id),'')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Value/Description") {
        $whrcls = " AND (segment_value ilike '" . loc_db_escape_string($searchWord) .
            "' or segment_description ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else {
        $whrcls = " AND (segment_value ilike '" . loc_db_escape_string($searchWord) .
            "' or segment_description ilike '" . loc_db_escape_string($searchWord) .
            "' or (COALESCE(org.get_sgmnt_val(dpndnt_sgmnt_val_id),'')||'.'||COALESCE(org.get_sgmnt_val_desc(dpndnt_sgmnt_val_id),'')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $subSql = "SELECT segment_value_id,segment_value,segment_description,space||segment_value||'.'||segment_description account_number_name, is_prnt_accnt, accnt_type,accnt_typ_id, prnt_segment_value_id, control_account_id,dpndnt_sgmnt_val_id, depth, path, cycle 
      FROM suborg WHERE 1=1 " . $whrcls . " ORDER BY accnt_typ_id, path";

    $strSql = "WITH RECURSIVE suborg(segment_value_id, segment_value, segment_description, is_prnt_accnt, accnt_type, accnt_typ_id, prnt_segment_value_id, control_account_id,dpndnt_sgmnt_val_id, depth, path, cycle, space) AS 
      ( 
      SELECT a.segment_value_id, a.segment_value, a.segment_description, a.is_prnt_accnt, a.accnt_type,a.accnt_typ_id, a.prnt_segment_value_id, a.control_account_id,a.dpndnt_sgmnt_val_id, 1, ARRAY[a.segment_value||'']::character varying[], false, '' opad 
      FROM org.org_segment_values a 
        WHERE ((CASE WHEN a.prnt_segment_value_id<=0 THEN a.control_account_id ELSE a.prnt_segment_value_id END)=-1 AND (a.segment_id = " . $segmentID . ")) 
      UNION ALL        
      SELECT a.segment_value_id, a.segment_value, a.segment_description, a.is_prnt_accnt, a.accnt_type,a.accnt_typ_id, a.prnt_segment_value_id, a.control_account_id,a.dpndnt_sgmnt_val_id, sd.depth + 1, 
      path || a.segment_value, 
      a.segment_value = ANY(path), space || '      '
      FROM org.org_segment_values a, suborg AS sd 
      WHERE (((CASE WHEN a.prnt_segment_value_id<=0 THEN a.control_account_id ELSE a.prnt_segment_value_id END)=sd.segment_value_id AND NOT cycle) 
       AND (a.segment_id = " . $segmentID . "))) 
       " . $subSql . " LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_SgmntVals($searchWord, $searchIn, $segmentID)
{
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Dependent Value") {
        $whrcls = " AND ((COALESCE(org.get_sgmnt_val(dpndnt_sgmnt_val_id),'')||'.'||COALESCE(org.get_sgmnt_val_desc(dpndnt_sgmnt_val_id),'')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Value/Description") {
        $whrcls = " AND (segment_value ilike '" . loc_db_escape_string($searchWord) .
            "' or segment_description ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else {
        $whrcls = " AND (segment_value ilike '" . loc_db_escape_string($searchWord) .
            "' or segment_description ilike '" . loc_db_escape_string($searchWord) .
            "' or (COALESCE(org.get_sgmnt_val(dpndnt_sgmnt_val_id),'')||'.'||COALESCE(org.get_sgmnt_val_desc(dpndnt_sgmnt_val_id),'')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $subSql = "SELECT count(segment_value_id) 
      FROM suborg WHERE 1=1" . $whrcls . "";

    $strSql = "WITH RECURSIVE suborg(segment_value_id, segment_value, segment_description, is_prnt_accnt, accnt_type, accnt_typ_id, prnt_segment_value_id, control_account_id, dpndnt_sgmnt_val_id, depth, path, cycle, space) AS 
      ( 
      SELECT a.segment_value_id, a.segment_value, a.segment_description, a.is_prnt_accnt, a.accnt_type,a.accnt_typ_id, a.prnt_segment_value_id, a.control_account_id,a.dpndnt_sgmnt_val_id, 1, ARRAY[a.segment_value||'']::character varying[], false, '' opad 
      FROM org.org_segment_values a 
        WHERE ((CASE WHEN a.prnt_segment_value_id<=0 THEN a.control_account_id ELSE a.prnt_segment_value_id END)=-1 AND (a.segment_id = " . $segmentID . ")) 
      UNION ALL        
      SELECT a.segment_value_id, a.segment_value, a.segment_description, a.is_prnt_accnt, a.accnt_type,a.accnt_typ_id, a.prnt_segment_value_id, a.control_account_id,a.dpndnt_sgmnt_val_id, sd.depth + 1, 
      path || a.segment_value, 
      a.segment_value = ANY(path), space || '      '
      FROM org.org_segment_values a, suborg AS sd 
      WHERE (((CASE WHEN a.prnt_segment_value_id<=0 THEN a.control_account_id ELSE a.prnt_segment_value_id END)=sd.segment_value_id AND NOT cycle) 
       AND (a.segment_id = " . $segmentID . "))) 
       " . $subSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return 0;
}

function get_SgmntValsDet($segmentValID)
{
    $strSql = "SELECT a.segment_value_id, a.segment_id, a.segment_value, a.segment_description, 
       a.prnt_segment_value_id, 
       org.get_sgmnt_val(a.prnt_segment_value_id)||'.'||org.get_sgmnt_val_desc(a.prnt_segment_value_id) prnt_sgmnt_val,
       a.dpndnt_sgmnt_val_id, 
       org.get_sgmnt_val(a.dpndnt_sgmnt_val_id)||'.'||org.get_sgmnt_val_desc(a.dpndnt_sgmnt_val_id) dpndnt_sgmnt_val, 
       a.allwd_group_type, a.allwd_group_value, org.get_criteria_name(a.allwd_group_value::bigint, a.allwd_group_type) group_name, 
       a.is_enabled, a.enable_cmbntns, a.is_prnt_accnt, a.is_contra, a.is_retained_earnings, 
       a.is_net_income, a.is_suspens_accnt, a.has_sub_ledgers, a.accnt_type, a.accnt_typ_id, a.report_line_no, 
       a.control_account_id, 
       org.get_sgmnt_val(a.control_account_id)||'.'||org.get_sgmnt_val_desc(a.control_account_id) cntrl_acnt_val, 
       a.crncy_id, gst.get_pssbl_val(a.crncy_id) crncy_nm, a.account_clsfctn, 
       a.mapped_grp_accnt_id, accb.get_accnt_num(a.mapped_grp_accnt_id)||'.'||accb.get_accnt_name(a.mapped_grp_accnt_id) mpd_accnt_name, 
       b.segment_number, org.get_sgmnt_id(b.prnt_sgmnt_number) dpndnt_sgmnt_id, a.org_id, b.system_clsfctn,
       a.lnkd_site_loc_id, org.get_site_code_desc(a.lnkd_site_loc_id) lnkd_site, 
       org.get_sgmnt_val(a.prnt_segment_value_id), org.get_sgmnt_val(a.dpndnt_sgmnt_val_id)
  FROM org.org_segment_values a, org.org_acnt_sgmnts b 
  WHERE(a.segment_id = b.segment_id and a.segment_value_id = " . $segmentValID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SgmntDpndntSegID($segmentID)
{
    $strSql = "SELECT org.get_sgmnt_id(b.prnt_sgmnt_number) dpndnt_sgmnt_id
  FROM org.org_acnt_sgmnts b 
  WHERE(b.segment_id = " . $segmentID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function get_SgmntClsfctn($segmentID)
{
    $strSql = "SELECT b.system_clsfctn  
  FROM org.org_acnt_sgmnts b 
  WHERE(b.segment_id = " . $segmentID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_SgmntInfo($segmentID)
{
    $strSql = "SELECT b.org_id, org.get_sgmnt_id(b.prnt_sgmnt_number) dpndnt_sgmnt_id, b.system_clsfctn  
  FROM org.org_acnt_sgmnts b 
  WHERE(b.segment_id = " . $segmentID . ")";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_RptClsfctns($accntid)
{
    $strSql = "SELECT account_clsfctn_id, maj_rpt_ctgry, min_rpt_ctgry, 
       created_by, creation_date, last_update_by, last_update_date
  FROM org.org_account_clsfctns a WHERE(a.account_id = " . $accntid . ") ORDER BY 1";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_RptClsfctnID($majCtgrName, $minCtgrName, $accntID)
{
    $strSql = "SELECT account_clsfctn_id from org.org_account_clsfctns where account_id=" . $accntID .
        " and lower(maj_rpt_ctgry)=lower('" . loc_db_escape_string($majCtgrName) .
        "') and lower(min_rpt_ctgry)=lower('" . loc_db_escape_string($minCtgrName) . "')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getNewRptClsfLnID()
{
    $strSql = "select nextval('org.org_account_clsfctns_account_clsfctn_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function createRptClsfctn($clsfctnID, $majCtgrName, $minCtgrName, $accntID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_account_clsfctns(
            account_clsfctn_id, account_id, maj_rpt_ctgry, min_rpt_ctgry, 
            created_by, creation_date, last_update_by, last_update_date) " .
        "VALUES (" . $clsfctnID . ", " . $accntID .
        ", '" . loc_db_escape_string($majCtgrName) .
        "', '" . loc_db_escape_string($minCtgrName) .
        "', " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr .
        "')";
    return execUpdtInsSQL($insSQL);
}

function updateRptClsfctn($clsfctnID, $majCtgrName, $minCtgrName, $accntID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_account_clsfctns SET " .
        "maj_rpt_ctgry='" . loc_db_escape_string($majCtgrName) .
        "', min_rpt_ctgry='" . loc_db_escape_string($minCtgrName) .
        "',account_id=" . $accntID .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr .
        "' WHERE (account_clsfctn_id =" . $clsfctnID . ")";
    return execUpdtInsSQL($updtSQL);
}

function deleteRptClsfctn($lnID)
{
    $delSQL = "DELETE FROM org.org_account_clsfctns WHERE account_clsfctn_id = " .
        $lnID . "";
    $affctd1 = execUpdtInsSQL($delSQL);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Report Calssification(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_SgmntValAcntBals($segmentValID, $segmentNum)
{
    $strSql = "SELECT sum(net_balance) " .
        "FROM accb.accb_chart_of_accnts a " .
        "WHERE (a.accnt_seg" . $segmentNum . "_val_id = " . $segmentValID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_SgmntValSegNum($segmentValID)
{
    $strSql = "SELECT b.segment_number " .
        "FROM org.org_segment_values a, org.org_acnt_sgmnts b " .
        "WHERE (a.segment_value_id = " . $segmentValID . " and a.segment_id=b.segment_id)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_SgmntNum($segmentID)
{
    $strSql = "SELECT b.segment_number " .
        "FROM org.org_acnt_sgmnts b " .
        "WHERE (b.segment_id = " . $segmentID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getAcctTypID($accntTyp)
{
    if ($accntTyp == "A") {
        return 1;
    } else if ($accntTyp == "L") {
        return 2;
    } else if ($accntTyp == "EQ") {
        return 3;
    } else if ($accntTyp == "R") {
        return 4;
    } else if ($accntTyp == "EX") {
        return 5;
    }
    return -1;
}

function getFullAcctType($accntTyp)
{
    if ($accntTyp == "A") {
        return "A -ASSET";
    } else if ($accntTyp == "L") {
        return "L -LIABILITY";
    } else if ($accntTyp == "EQ") {
        return "EQ-EQUITY";
    } else if ($accntTyp == "R") {
        return "R -REVENUE";
    } else if ($accntTyp == "EX") {
        return "EX-EXPENSE";
    }
    return "";
}

function clearChrtRetEarns($segmentID)
{
    $updtSQL = "UPDATE org.org_segment_values " .
        "SET is_retained_earnings='0' WHERE segment_id = " . $segmentID;
    return execUpdtInsSQL($updtSQL);
}

function clearChrtNetIncome($segmntID)
{
    $updtSQL = "UPDATE org.org_segment_values " .
        "SET is_net_income='0' WHERE segment_id = " . $segmntID;
    return execUpdtInsSQL($updtSQL);
}

function clearChrtSuspns($segmntID)
{
    $updtSQL = "UPDATE org.org_segment_values " .
        "SET is_suspens_accnt='0' WHERE segment_id = " . $segmntID;
    return execUpdtInsSQL($updtSQL);
}

function createSgmntVal(
    $orgid,
    $segmentID,
    $segmentVal,
    $segmentDesc,
    $allwdGrpTyp,
    $allwdGrpVal,
    $isEnbld,
    $prntSegmentID,
    $isContra,
    $accntType,
    $isParent,
    $isRetainedErngs,
    $isNetIncome,
    $accntTypID,
    $reportLineNo,
    $hsSubLdgrs,
    $contrlAcntID,
    $crncyID,
    $isSuspenseAcnt,
    $acntClsfctn,
    $mappedAcntID,
    $enblCmbtnsCheckBox,
    $dpndntValId,
    $lnkdSiteId
) {
    global $usrID;
    if ($isRetainedErngs == true) {
        clearChrtRetEarns($segmentID);
    }
    if ($isNetIncome == true) {
        clearChrtNetIncome($segmentID);
    }
    if ($isSuspenseAcnt == true) {
        clearChrtSuspns($segmentID);
    }
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_segment_values(
            segment_id, segment_value, segment_description, 
            allwd_group_type, allwd_group_value, is_enabled, prnt_segment_value_id, 
            created_by, creation_date, last_update_by, last_update_date, 
            org_id, is_contra, accnt_type, is_prnt_accnt, is_retained_earnings, 
            is_net_income, accnt_typ_id, report_line_no, has_sub_ledgers, 
            control_account_id, crncy_id, is_suspens_accnt, account_clsfctn, 
            mapped_grp_accnt_id, enable_cmbntns, dpndnt_sgmnt_val_id, lnkd_site_loc_id) " .
        "VALUES (" . $segmentID .
        ",'" . loc_db_escape_string($segmentVal) .
        "', '" . loc_db_escape_string($segmentDesc) .
        "', '" . loc_db_escape_string($allwdGrpTyp) .
        "', '" . loc_db_escape_string($allwdGrpVal) .
        "', '" . cnvrtBoolToBitStr($isEnbld) .
        "', " . $prntSegmentID .
        ", " . $usrID .
        ", '" . $dateStr .
        "', " . $usrID .
        ", '" . $dateStr .
        "', " . $orgid .
        ", '" . cnvrtBoolToBitStr($isContra) .
        "', '" . loc_db_escape_string($accntType) .
        "', '" . cnvrtBoolToBitStr($isParent) .
        "', '" . cnvrtBoolToBitStr($isRetainedErngs) .
        "', '" . cnvrtBoolToBitStr($isNetIncome) .
        "', " . $accntTypID .
        ", " . $reportLineNo .
        ", '" . cnvrtBoolToBitStr($hsSubLdgrs) .
        "', " . $contrlAcntID .
        ", " . $crncyID .
        ", '" . cnvrtBoolToBitStr($isSuspenseAcnt) .
        "', '" . loc_db_escape_string($acntClsfctn) .
        "', " . $mappedAcntID .
        ", '" . cnvrtBoolToBitStr($enblCmbtnsCheckBox) .
        "', " . $dpndntValId . ", " . $lnkdSiteId . ")";
    return execUpdtInsSQL($insSQL);
}

function updateSgmntVal(
    $segmentValID,
    $segmentVal,
    $segmentDesc,
    $allwdGrpTyp,
    $allwdGrpVal,
    $isEnbld,
    $prntSegmentID,
    $isContra,
    $accntType,
    $isParent,
    $isRetainedErngs,
    $isNetIncome,
    $accntTypID,
    $reportLineNo,
    $hsSubLdgrs,
    $contrlAcntID,
    $crncyID,
    $isSuspenseAcnt,
    $acntClsfctn,
    $mappedAcntID,
    $segmnetID,
    $enblCmbtnsCheckBox,
    $dpndntValId,
    $lnkdSiteId
) {
    global $usrID;
    if ($isRetainedErngs == true) {
        clearChrtRetEarns($segmnetID);
    }
    if ($isNetIncome == true) {
        clearChrtNetIncome($segmnetID);
    }
    if ($isSuspenseAcnt == true) {
        clearChrtSuspns($segmnetID);
    }
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_segment_values
       SET segment_value ='" . loc_db_escape_string($segmentVal) .
        "', segment_description ='" . loc_db_escape_string($segmentDesc) .
        "', allwd_group_type ='" . loc_db_escape_string($allwdGrpTyp) .
        "', allwd_group_value ='" . loc_db_escape_string($allwdGrpVal) .
        "', is_enabled ='" . cnvrtBoolToBitStr($isEnbld) .
        "', prnt_segment_value_id =" . $prntSegmentID .
        ", last_update_by =" . $usrID .
        ", last_update_date ='" . $dateStr .
        "', is_contra ='" . cnvrtBoolToBitStr($isContra) .
        "', accnt_type ='" . loc_db_escape_string($accntType) .
        "', is_prnt_accnt ='" . cnvrtBoolToBitStr($isParent) .
        "', is_retained_earnings ='" . cnvrtBoolToBitStr($isRetainedErngs) .
        "', is_net_income ='" . cnvrtBoolToBitStr($isNetIncome) .
        "', accnt_typ_id =" . $accntTypID .
        ", report_line_no =" . $reportLineNo .
        ", has_sub_ledgers ='" . cnvrtBoolToBitStr($hsSubLdgrs) .
        "', control_account_id =" . $contrlAcntID .
        ", crncy_id =" . $crncyID .
        ", is_suspens_accnt ='" . cnvrtBoolToBitStr($isSuspenseAcnt) .
        "', account_clsfctn ='" . loc_db_escape_string($acntClsfctn) .
        "', mapped_grp_accnt_id =" . $mappedAcntID .
        ", enable_cmbntns = '" . cnvrtBoolToBitStr($enblCmbtnsCheckBox) .
        "', dpndnt_sgmnt_val_id=" . $dpndntValId .
        ", lnkd_site_loc_id=" . $lnkdSiteId . " WHERE (segment_value_id =" . $segmentValID . ")";
    return execUpdtInsSQL($updtSQL);
}

function deleteSgmntVal($segmentValID, $segmentValDesc)
{
    $segmentNum = get_SgmntValSegNum($segmentValID);
    $strSql = "SELECT count(a.accnt_id) FROM accb.accb_chart_of_accnts a WHERE(a.accnt_seg" . $segmentNum . "_val_id = " . $segmentValID . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete a Segment Value assigned to Account Combinations!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $strSql = "SELECT count(a.segment_value_id) FROM org.org_segment_values a "
        . "WHERE(a.prnt_segment_value_id= " . $segmentValID . " or a.control_account_id= " . $segmentValID . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete a Segment Value assigned to Account Combinations!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM org.org_account_clsfctns WHERE account_id = " . $segmentValID;
    $affctd2 = execUpdtInsSQL($delSQL, "Segment Value:" . $segmentValDesc);
    $delSQL = "DELETE FROM org.org_segment_values WHERE segment_value_id = " . $segmentValID;
    $affctd1 = execUpdtInsSQL($delSQL, "Segment Value:" . $segmentValDesc);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Segment Value(s)!";
        $dsply .= "<br/>$affctd2 Report Classification(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteSegment($segmentID, $segmentDesc)
{
    $segmentNum = get_SgmntNum($segmentID);
    $strSql = "SELECT count(a.accnt_id) FROM accb.accb_chart_of_accnts a, org.org_segment_values b "
        . "WHERE(a.accnt_seg" . $segmentNum . "_val_id = b.segment_value_id and b.segment_id = " . $segmentID . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete a Segment assigned to Account Combinations!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM org.org_account_clsfctns WHERE account_id IN "
        . "(Select segment_value_id FROM org.org_segment_values WHERE segment_id = " . $segmentID . ")";
    $affctd3 = execUpdtInsSQL($delSQL, "Segment Value:" . $segmentDesc);
    $delSQL = "DELETE FROM org.org_segment_values WHERE segment_id = " . $segmentID . "";
    $affctd2 = execUpdtInsSQL($delSQL, "Segment Value:" . $segmentDesc);
    $delSQL = "DELETE FROM org.org_acnt_sgmnts WHERE segment_id = " . $segmentID;
    $affctd1 = execUpdtInsSQL($delSQL, "Segment Value:" . $segmentDesc);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Segment(s)!";
        $dsply .= "<br/>$affctd2 Segment Value(s)!";
        $dsply .= "<br/>$affctd3 Report Classification(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function updateSegmentVal($segmentValID, $segmentNum, $accntID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE accb.accb_chart_of_accnts SET accnt_seg" . $segmentNum . "_val_id = " . $segmentValID .
        ", last_update_by =" . $usrID .
        ", last_update_date ='" . $dateStr .
        "' WHERE accnt_id = " . $accntID;
    return execUpdtInsSQL($updtSQL);
}

function isSgmntValInUse($segmentValID, $segmentNum)
{
    $strSql = "SELECT a.accnt_id " .
        "FROM accb.accb_chart_of_accnts a " .
        "WHERE(a.accnt_seg" . $segmentNum . "_val_id = " . $segmentValID . ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    $strSql = "SELECT a.segment_value_id " .
        "FROM org.org_segment_values a " .
        "WHERE(a.prnt_segment_value_id= " . $segmentValID .
        " or a.control_account_id= " . $segmentValID . ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function getSgmntValID($segmentVal, $segmentID)
{
    $sqlStr = "select segment_value_id from org.org_segment_values where lower(segment_value) = lower('" .
        loc_db_escape_string($segmentVal) . "') and segment_id = " . $segmentID;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getSgmntValDescID($segmentVal, $segmentID)
{
    $sqlStr = "select segment_value_id from org.org_segment_values where lower(segment_description) = lower('" .
        loc_db_escape_string($segmentVal) . "') and segment_id = " . $segmentID;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}
