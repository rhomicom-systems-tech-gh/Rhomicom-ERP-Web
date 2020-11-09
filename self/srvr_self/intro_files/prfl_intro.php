<?php

function getLtstRecPkID($tblNm, $pkeyCol) {
    $sqlStr = "select " . $pkeyCol . " from " . $tblNm . " ORDER BY 1 DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return ((float) $row[0]) + 1;
    }
    return 1000;
}

function getLtstPrsnIDNo() {
    global $orgID;
    $sqlStr = "select count(person_id) from prs.prsn_names_nos WHERE org_id=" . $orgID . "";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return str_pad((((float) $row[0]) + 1) . "", 4, '0', STR_PAD_LEFT);
    }
    return "0001";
}

function getLtstPrsnIDNoInPrfx($prfxTxt) {
    global $orgID;
    $sqlStr = "select count(person_id) from prs.prsn_names_nos WHERE org_id=" . $orgID .
            " and local_id_no ilike '" . loc_db_escape_string($prfxTxt) . "%'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return str_pad((((float) $row[0]) + 1) . "", 4, '0', STR_PAD_LEFT);
    }
    return "0001";
}

function getLastPrsnIDNo() {
    global $orgID;
    $sqlStr = "select (chartonumeric(local_id_no) + 1) from prs.prsn_names_nos 
        WHERE org_id=" . $orgID . "
      ORDER BY chartonumeric(local_id_no) DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return str_pad((((float) $row[0]) + 1) . "", 5, '0', STR_PAD_LEFT);
    }
    return "00001";
}

function getNewLocIDNumber($locIDTextBox, $iDPrfxComboBox) {
    $tst = "0001";
    if (getEnbldPssblValID("Yes", getLovID("Person ID No. Prefix Determines ID Serial No.")) > 0) {
        if ($iDPrfxComboBox === "") {
            $tst = getLastPrsnIDNo();
        } else {
            $tst = getLtstPrsnIDNoInPrfx($iDPrfxComboBox);
        }
    } else {
        if ($iDPrfxComboBox === "") {
            $tst = getLastPrsnIDNo();
        } else {
            $tst = getLtstPrsnIDNo();
        }
    }
    if (strlen($tst) < 4) {
        $tst = str_pad($tst, 4, '0', STR_PAD_LEFT);
    }
    return $iDPrfxComboBox . $tst;
}

function get_LtstPrsnType($pkID) {
    $strSql = "SELECT prsn_type 
            FROM pasn.prsn_prsntyps WHERE ((person_id =  $pkID)) 
                ORDER BY valid_end_date DESC, valid_start_date DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return '';
}

function get_BscPrsnTtl($searchFor, $searchIn, $orgID, $searchAll, $fltrTypValue, $fltrTyp, $extra4 = "") {
    $extra1 = "";
    $extra2 = "";
    $extra3 = "";
    $aldPrsTyp = getAllwdPrsnTyps();
    $aldPrsTyp = "'" . trim($aldPrsTyp, "'") . "'";
    if ($aldPrsTyp != "'All'") {
        $extra3 = " and ((SELECT z.prsn_type FROM pasn.prsn_prsntyps z WHERE (z.person_id = a.person_id) 
ORDER BY z.valid_end_date DESC, z.valid_start_date DESC LIMIT 1 OFFSET 0) IN (" . $aldPrsTyp . "))";
    }

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }
    if ($fltrTypValue == "All") {
        $extra2 = " and 1 = 1";
    } else {
        if ($fltrTyp == "Relation Type") {
            $extra2 = " and ((SELECT z.prsn_type FROM pasn.prsn_prsntyps z WHERE (z.person_id = a.person_id) 
ORDER BY z.valid_end_date DESC, z.valid_start_date DESC LIMIT 1 OFFSET 0)='" . $fltrTypValue . "')";
        } else if ($fltrTyp == "Division/Group") {
            $extra2 = " and (EXISTS(SELECT w.div_code_name FROM pasn.prsn_divs_groups z, org.org_divs_groups w 
WHERE (z.person_id = a.person_id and w.div_id = z.div_id and w.div_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Job") {
            $extra2 = " and (EXISTS(SELECT w.job_code_name FROM pasn.prsn_jobs z, org.org_jobs w 
WHERE (z.person_id = a.person_id and w.job_id = z.job_id and w.job_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Grade") {
            $extra2 = " and (EXISTS(SELECT w.grade_code_name FROM pasn.prsn_grades z, org.org_grades w 
WHERE (z.person_id = a.person_id and w.grade_id = z.grade_id and w.grade_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Position") {
            $extra2 = " and (EXISTS(SELECT w.position_code_name FROM pasn.prsn_positions z, org.org_positions w 
WHERE (z.person_id = a.person_id and w.position_id = z.position_id and w.position_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        }
    }
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "ID/Full Name") {
        $whrcls = " AND (a.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(a.title || ' ' || a.sur_name || " .
                "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Full Name") {
        $whrcls = " AND (trim(a.title || ' ' || a.sur_name || " .
                "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Residential Address") {
        $whrcls = " AND (a.res_address ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Contact Information") {
        $whrcls = " AND (a.pstl_addrs ilike '" . loc_db_escape_string($searchFor) .
                "' or a.email ilike '" . loc_db_escape_string($searchFor) .
                "' or a.cntct_no_tel ilike '" . loc_db_escape_string($searchFor) .
                "' or a.cntct_no_mobl ilike '" . loc_db_escape_string($searchFor) .
                "' or a.cntct_no_fax ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Linked Firm/Workplace") {
        $whrcls = " AND (scm.get_cstmr_splr_name(a.lnkd_firm_org_id) ilike '" . loc_db_escape_string($searchFor) .
                "' or scm.get_cstmr_splr_site_name(a.lnkd_firm_site_id) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Person Type") {
        $whrcls = " AND ((Select g.prsn_type || ' ' || g.prn_typ_asgnmnt_rsn "
                . "|| ' ' || g.further_details from pasn.prsn_prsntyps g "
                . "where g.person_id=a.person_id ORDER BY g.valid_start_date DESC "
                . "LIMIT 1 OFFSET 0) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Date of Birth") {
        $whrcls = " AND (to_char(to_timestamp(a.date_of_birth,"
                . "'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Home Town") {
        $whrcls = " AND (a.hometown ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Gender") {
        $whrcls = " AND (a.gender ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Marital Status") {
        $whrcls = " AND (a.marital_status ilike '" . loc_db_escape_string($searchFor) .
                "')";
    }

    $strSql = "SELECT count(1) " .
            "FROM prs.prsn_names_nos a "
            . "LEFT OUTER JOIN pasn.prsn_prsntyps b " .
            "ON (a.person_id = b.person_id and "
            . "b.prsntype_id = (SELECT MAX(c.prsntype_id) from pasn.prsn_prsntyps c where c.person_id = a.person_id)) " .
            "WHERE ((a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . $extra2 . $extra3 . $extra4 .
            ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_BscPrsn($searchFor, $searchIn, $offset, $limit_size, $orgID, $searchAll, $sortBy
        , $fltrTypValue, $fltrTyp, $extra4 = "") {
    $extra1 = "";
    $extra2 = "";
    $extra3 = "";
    $aldPrsTyp = getAllwdPrsnTyps();
    $aldPrsTyp = "'" . trim($aldPrsTyp, "'") . "'";
    if ($aldPrsTyp != "'All'") {
        $extra3 = " and ((SELECT z.prsn_type FROM pasn.prsn_prsntyps z WHERE (z.person_id = a.person_id) 
ORDER BY z.valid_end_date DESC, z.valid_start_date DESC LIMIT 1 OFFSET 0) IN (" . $aldPrsTyp . "))";
    }

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }
    if ($fltrTypValue == "All") {
        $extra2 = " and 1 = 1";
    } else {
        if ($fltrTyp == "Relation Type") {
            $extra2 = " and ((SELECT z.prsn_type FROM pasn.prsn_prsntyps z WHERE (z.person_id = a.person_id) 
ORDER BY z.valid_end_date DESC, z.valid_start_date DESC LIMIT 1 OFFSET 0)='" . $fltrTypValue . "')";
        } else if ($fltrTyp == "Division/Group") {
            $extra2 = " and (EXISTS(SELECT w.div_code_name FROM pasn.prsn_divs_groups z, org.org_divs_groups w 
WHERE (z.person_id = a.person_id and w.div_id = z.div_id and w.div_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Job") {
            $extra2 = " and (EXISTS(SELECT w.job_code_name FROM pasn.prsn_jobs z, org.org_jobs w 
WHERE (z.person_id = a.person_id and w.job_id = z.job_id and w.job_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Grade") {
            $extra2 = " and (EXISTS(SELECT w.grade_code_name FROM pasn.prsn_grades z, org.org_grades w 
WHERE (z.person_id = a.person_id and w.grade_id = z.grade_id and w.grade_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Position") {
            $extra2 = " and (EXISTS(SELECT w.position_code_name FROM pasn.prsn_positions z, org.org_positions w 
WHERE (z.person_id = a.person_id and w.position_id = z.position_id and w.position_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        }
    }
    $strSql = "";
    $whrcls = "";
    $ordrBy = "";
    if ($searchIn == "ID/Full Name") {
        $whrcls = " AND (a.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(a.title || ' ' || a.sur_name || " .
                "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Full Name") {
        $whrcls = " AND (trim(a.title || ' ' || a.sur_name || " .
                "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Residential Address") {
        $whrcls = " AND (a.res_address ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Contact Information") {
        $whrcls = " AND (a.pstl_addrs ilike '" . loc_db_escape_string($searchFor) .
                "' or a.email ilike '" . loc_db_escape_string($searchFor) .
                "' or a.cntct_no_tel ilike '" . loc_db_escape_string($searchFor) .
                "' or a.cntct_no_mobl ilike '" . loc_db_escape_string($searchFor) .
                "' or a.cntct_no_fax ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Linked Firm/Workplace") {
        $whrcls = " AND (scm.get_cstmr_splr_name(a.lnkd_firm_org_id) ilike '" . loc_db_escape_string($searchFor) .
                "' or scm.get_cstmr_splr_site_name(a.lnkd_firm_site_id) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Person Type") {
        $whrcls = " AND ((Select g.prsn_type || ' ' || g.prn_typ_asgnmnt_rsn "
                . "|| ' ' || g.further_details from pasn.prsn_prsntyps g "
                . "where g.person_id=a.person_id ORDER BY g.valid_start_date DESC "
                . "LIMIT 1 OFFSET 0) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Date of Birth") {
        $whrcls = " AND (to_char(to_timestamp(a.date_of_birth,"
                . "'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Home Town") {
        $whrcls = " AND (a.hometown ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Gender") {
        $whrcls = " AND (a.gender ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Marital Status") {
        $whrcls = " AND (a.marital_status ilike '" . loc_db_escape_string($searchFor) .
                "')";
    }

    if ($sortBy == "Date Added DESC") {
        $ordrBy = "a.creation_date DESC";
    } else if ($sortBy == "Date of Birth") {
        $ordrBy = "a.date_of_birth ASC";
    } else if ($sortBy == "Full Name") {
        $ordrBy = "trim(a.sur_name || " .
                "', ' || a.first_name || ' ' || a.other_names) ASC";
    } else if ($sortBy == "ID ASC") {
        $ordrBy = "a.local_id_no ASC";
    } else if ($sortBy == "ID DESC") {
        $ordrBy = "a.local_id_no DESC";
    } else {
        $ordrBy = "a.local_id_no ASC";
    }

    $strSql = "SELECT a.person_id, a.local_id_no, trim(a.title || ' ' || a.sur_name || " .
            "', ' || a.first_name || ' ' || a.other_names) fullname, "
            . "COALESCE(a.img_location,''), a.first_name, a.sur_name, a.other_names,
                gender, marital_status,date_of_birth,
          place_of_birth, religion, res_address, pstl_addrs, email, cntct_no_tel, 
          cntct_no_mobl, cntct_no_fax, COALESCE(img_location,''), hometown, nationality, 
          lnkd_firm_org_id, 
          CASE WHEN lnkd_firm_org_id <=0 THEN new_company ELSE scm.get_cstmr_splr_name(lnkd_firm_org_id) END, 
          lnkd_firm_site_id, 
          CASE WHEN lnkd_firm_site_id <=0 THEN new_company_loc ELSE scm.get_cstmr_splr_site_name(lnkd_firm_site_id) END, 
          a.title, 
          b.prsn_type, b.prn_typ_asgnmnt_rsn, " .
            "b.further_details, b.valid_start_date, b.valid_end_date  " .
            "FROM prs.prsn_names_nos a "
            . "LEFT OUTER JOIN pasn.prsn_prsntyps b " .
            "ON (a.person_id = b.person_id and "
            . "b.prsntype_id = (SELECT MAX(c.prsntype_id) from pasn.prsn_prsntyps c where c.person_id = a.person_id)) " .
            "WHERE ((a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . $extra2 . $extra3 . $extra4 .
            ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_BscPrsnExprt($searchFor, $searchIn, $offset, $limit_size, $orgID, $searchAll, $sortBy
        , $fltrTypValue, $fltrTyp, $extra4 = "") {
    $extra1 = "";
    $extra2 = "";
    $extra3 = "";
    $aldPrsTyp = getAllwdPrsnTyps();
    $aldPrsTyp = "'" . trim($aldPrsTyp, "'") . "'";
    if ($aldPrsTyp != "'All'") {
        $extra3 = " and ((SELECT z.prsn_type FROM pasn.prsn_prsntyps z WHERE (z.person_id = a.person_id) 
ORDER BY z.valid_end_date DESC, z.valid_start_date DESC LIMIT 1 OFFSET 0) IN (" . $aldPrsTyp . "))";
    }
    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }
    if ($fltrTypValue == "All") {
        $extra2 = " and 1 = 1";
    } else {
        if ($fltrTyp == "Relation Type") {
            $extra2 = " and ((SELECT z.prsn_type FROM pasn.prsn_prsntyps z WHERE (z.person_id = a.person_id) 
ORDER BY z.valid_end_date DESC, z.valid_start_date DESC LIMIT 1 OFFSET 0)='" . $fltrTypValue . "')";
        } else if ($fltrTyp == "Division/Group") {
            $extra2 = " and (EXISTS(SELECT w.div_code_name FROM pasn.prsn_divs_groups z, org.org_divs_groups w 
WHERE (z.person_id = a.person_id and w.div_id = z.div_id and w.div_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Job") {
            $extra2 = " and (EXISTS(SELECT w.job_code_name FROM pasn.prsn_jobs z, org.org_jobs w 
WHERE (z.person_id = a.person_id and w.job_id = z.job_id and w.job_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Grade") {
            $extra2 = " and (EXISTS(SELECT w.grade_code_name FROM pasn.prsn_grades z, org.org_grades w 
WHERE (z.person_id = a.person_id and w.grade_id = z.grade_id and w.grade_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Position") {
            $extra2 = " and (EXISTS(SELECT w.position_code_name FROM pasn.prsn_positions z, org.org_positions w 
WHERE (z.person_id = a.person_id and w.position_id = z.position_id and w.position_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        }
    }
    $strSql = "";
    $whrcls = "";
    $ordrBy = "";
    if ($searchIn == "ID/Full Name") {
        $whrcls = " AND (a.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(a.title || ' ' || a.sur_name || " .
                "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Full Name") {
        $whrcls = " AND (trim(a.title || ' ' || a.sur_name || " .
                "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Residential Address") {
        $whrcls = " AND (a.res_address ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Contact Information") {
        $whrcls = " AND (a.pstl_addrs ilike '" . loc_db_escape_string($searchFor) .
                "' or a.email ilike '" . loc_db_escape_string($searchFor) .
                "' or a.cntct_no_tel ilike '" . loc_db_escape_string($searchFor) .
                "' or a.cntct_no_mobl ilike '" . loc_db_escape_string($searchFor) .
                "' or a.cntct_no_fax ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Linked Firm/Workplace") {
        $whrcls = " AND (scm.get_cstmr_splr_name(a.lnkd_firm_org_id) ilike '" . loc_db_escape_string($searchFor) .
                "' or scm.get_cstmr_splr_site_name(a.lnkd_firm_site_id) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Person Type") {
        $whrcls = " AND ((Select g.prsn_type || ' ' || g.prn_typ_asgnmnt_rsn "
                . "|| ' ' || g.further_details from pasn.prsn_prsntyps g "
                . "where g.person_id=a.person_id ORDER BY g.valid_start_date DESC "
                . "LIMIT 1 OFFSET 0) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Date of Birth") {
        $whrcls = " AND (to_char(to_timestamp(a.date_of_birth,"
                . "'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Home Town") {
        $whrcls = " AND (a.hometown ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Gender") {
        $whrcls = " AND (a.gender ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Marital Status") {
        $whrcls = " AND (a.marital_status ilike '" . loc_db_escape_string($searchFor) .
                "')";
    }

    if ($sortBy == "Date Added DESC") {
        $ordrBy = "a.creation_date DESC";
    } else if ($sortBy == "Date of Birth") {
        $ordrBy = "a.date_of_birth ASC";
    } else if ($sortBy == "Full Name") {
        $ordrBy = "trim(a.sur_name || " .
                "', ' || a.first_name || ' ' || a.other_names) ASC";
    } else if ($sortBy == "ID ASC") {
        $ordrBy = "a.local_id_no ASC";
    } else if ($sortBy == "ID DESC") {
        $ordrBy = "a.local_id_no DESC";
    } else {
        $ordrBy = "a.local_id_no ASC";
    }


    $strSql = "SELECT '''' || a.local_id_no, a.title, a.first_name, a.sur_name, a.other_names,
                gender, marital_status,'''' || to_char(to_timestamp(a.date_of_birth,'YYYY-MM-DD'),'DD-Mon-YYYY') date_of_birth,
          place_of_birth, hometown, religion, res_address, pstl_addrs, email, '''' || a.cntct_no_tel, '''' || a.cntct_no_mobl, 
          '''' || a.cntct_no_fax, nationality, COALESCE(img_location,''), 
          b.prsn_type, b.prn_typ_asgnmnt_rsn, b.further_details, 
          '''' || to_char(to_timestamp(b.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') valid_start_date, 
          '''' || to_char(to_timestamp(b.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') valid_end_date, 
          CASE WHEN lnkd_firm_org_id <=0 THEN new_company ELSE scm.get_cstmr_splr_name(lnkd_firm_org_id) END, 
          CASE WHEN lnkd_firm_site_id <=0 THEN new_company_loc ELSE scm.get_cstmr_splr_site_name(lnkd_firm_site_id) END,
          (SELECT tbl1.prsntyphstry
        FROM (SELECT z.person_id,
                     STRING_AGG(z.prsn_type || '~' || z.prn_typ_asgnmnt_rsn || '~' || to_char(to_timestamp(z.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') || '~' ||
                                to_char(to_timestamp(z.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), '|' ORDER BY z.valid_start_date DESC) prsntyphstry
              FROM pasn.prsn_prsntyps z
              where z.person_id = a.person_id
              group by z.person_id) tbl1) prsntyphstry, 
          (SELECT tbl2.divsgrps
        FROM (SELECT z2.person_id,
                     STRING_AGG(org.get_div_name(z2.div_id) || '~' || to_char(to_timestamp(z2.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') || '~' ||
                                to_char(to_timestamp(z2.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), '|' ORDER BY z2.valid_start_date DESC) divsgrps
              FROM pasn.prsn_divs_groups z2
              where z2.person_id = a.person_id
              group by z2.person_id) tbl2) divsgrps, 
          (SELECT tbl3.spvsrs
        FROM (SELECT z3.person_id,
                     STRING_AGG(prs.get_prsn_loc_id(z3.supervisor_prsn_id) || '~' || to_char(to_timestamp(z3.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') || '~' ||
                                to_char(to_timestamp(z3.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), '|' ORDER BY z3.valid_start_date DESC) spvsrs
              FROM pasn.prsn_supervisors z3
              where z3.person_id = a.person_id
              group by z3.person_id) tbl3) spvsrs, 
          (SELECT tbl4.siteslocs
        FROM (SELECT z4.person_id,
                     STRING_AGG(org.get_site_name(z4.location_id) || '~' || to_char(to_timestamp(z4.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') || '~' ||
                                to_char(to_timestamp(z4.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), '|' ORDER BY z4.valid_start_date DESC) siteslocs
              FROM pasn.prsn_locations z4
              where z4.person_id = a.person_id
              group by z4.person_id) tbl4) siteslocs, 
          (SELECT tbl5.jobs
        FROM (SELECT z5.person_id,
                     STRING_AGG(org.get_job_name(z5.job_id) || '~' || to_char(to_timestamp(z5.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') || '~' ||
                                to_char(to_timestamp(z5.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), '|' ORDER BY z5.valid_start_date DESC) jobs
              FROM pasn.prsn_jobs z5
              where z5.person_id = a.person_id
              group by z5.person_id) tbl5) jobs, 
          (SELECT tbl6.grades
        FROM (SELECT z6.person_id,
                     STRING_AGG(org.get_grade_name(z6.grade_id) || '~' || to_char(to_timestamp(z6.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') || '~' ||
                                to_char(to_timestamp(z6.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), '|' ORDER BY z6.valid_start_date DESC) grades
              FROM pasn.prsn_grades z6
              where z6.person_id = a.person_id
              group by z6.person_id) tbl6) grades, 
          (SELECT tbl7.positions
        FROM (SELECT z7.person_id,
                     STRING_AGG(org.get_pos_name(z7.position_id) || '~' || coalesce(org.get_div_name(z7.div_id),'') || '~' || to_char(to_timestamp(z7.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') || '~' ||
                                to_char(to_timestamp(z7.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), '|' ORDER BY z7.valid_start_date DESC) positions
              FROM pasn.prsn_positions z7
              where z7.person_id = a.person_id
              group by z7.person_id) tbl7) positions, 
          (SELECT tbl8.educbkgrd
        FROM (SELECT z8.person_id,
                     STRING_AGG(course_name || '~' || school_institution || '~' || school_location || '~' || cert_obtained|| '~' ||  cert_type || '~' ||
                                date_cert_awarded || '~' ||  to_char(to_timestamp(z8.course_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') || '~' ||to_char(to_timestamp(z8.course_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), '|' ORDER BY z8.date_cert_awarded DESC) educbkgrd
              FROM prs.prsn_education z8
              where z8.person_id = a.person_id
              group by z8.person_id) tbl8) educbkgrd, 
          (SELECT tbl9.wrkexprnc
        FROM (SELECT z9.person_id,
                     STRING_AGG(job_name_title || '~' || institution_name || '~' || job_location || '~' || job_description|| '~' ||  feats_achvments || '~' ||
                                to_char(to_timestamp(z9.job_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') || '~' ||  to_char(to_timestamp(z9.job_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), '|' ORDER BY z9.job_start_date DESC) wrkexprnc
              FROM prs.prsn_work_experience z9
              where z9.person_id = a.person_id
              group by z9.person_id) tbl9) wrkexprnc, 
          (SELECT tbl10.skillsnature
        FROM (SELECT z10.person_id,
                     STRING_AGG(languages || '~' || hobbies || '~' || interests || '~' || conduct || '~' ||  attitude || '~' ||
                                to_char(to_timestamp(z10.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') || '~' ||  to_char(to_timestamp(z10.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), '|' ORDER BY z10.valid_start_date DESC) skillsnature
              FROM prs.prsn_skills_nature z10
              where z10.person_id = a.person_id 
              group by z10.person_id) tbl10) skillsnature, 
          (SELECT tbl11.ntnlIDCards
        FROM (SELECT z11.person_id,
                     STRING_AGG(nationality || '~' || national_id_typ || '~' || id_number || '~' || date_issued || '~' ||  expiry_date || '~' ||
                                other_info, '|' ORDER BY z11.date_issued DESC) ntnlIDCards
              FROM prs.prsn_national_ids z11
              where z11.person_id = a.person_id 
              group by z11.person_id) tbl11) ntnlIDCards, 
            data_col1, data_col2, data_col3, data_col4, 
            data_col5, data_col6, data_col7, data_col8, data_col9, data_col10, 
            data_col11, data_col12, data_col13, data_col14, data_col15, data_col16, 
            data_col17, data_col18, data_col19, data_col20, data_col21, data_col22, 
            data_col23, data_col24, data_col25, data_col26, data_col27, data_col28, 
            data_col29, data_col30, data_col31, data_col32, data_col33, data_col34, 
            data_col35, data_col36, data_col37, data_col38, data_col39, data_col40, 
            data_col41, data_col42, data_col43, data_col44, data_col45, data_col46, 
            data_col47, data_col48, data_col49, data_col50  
            FROM prs.prsn_names_nos a LEFT OUTER JOIN pasn.prsn_prsntyps b 
            ON (a.person_id = b.person_id and b.prsntype_id = (SELECT MAX(c.prsntype_id) from pasn.prsn_prsntyps c where c.person_id = a.person_id))
            LEFT OUTER JOIN prs.prsn_extra_data c ON (a.person_id = c.person_id)
           WHERE ((a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . $extra2 . $extra3 . $extra4 . ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_BscPrsnDetail($person_id) {
    $strSql = "SELECT  
                COALESCE(a.img_location,'') \"Person's Picture\", 
                a.person_id \"Person ID\", "
            . "a.local_id_no \"ID No. \", "
            . "trim(a.title || ' ' || a.sur_name || " .
            "', ' || a.first_name || ' ' || a.other_names) \"Full Name \",
                a.gender \"Gender \", 
                a.marital_status \"Marital Status \", 
                to_char(to_timestamp(a.date_of_birth,'YYYY-MM-DD'),'DD-Mon-YYYY') \"Date of Birth \",
          a.place_of_birth \"Place of Birth \", 
          a.religion \"Religion \", 
          a.res_address \"Residential Address \", 
          a.pstl_addrs \"Postal Address \", 
          a.email \"Email \", 
          a.cntct_no_tel \"Tel No. \", 
          a.cntct_no_mobl \"Mobile No. \", 
          a.cntct_no_fax \"Fax \", 
          a.hometown \"Home Town \", 
          a.nationality \"Nationality \", 
          a.lnkd_firm_org_id mt, 
          scm.get_cstmr_splr_name(a.lnkd_firm_org_id) \"Workplace/Firm \", 
          a.lnkd_firm_site_id mt, 
          scm.get_cstmr_splr_site_name(a.lnkd_firm_site_id) \"Site/Branch \", 
          b.prsn_type \"Relation Type\", 
          b.prn_typ_asgnmnt_rsn \"Cause of Relation\", " .
            "b.further_details \"Further Details\", "
            . "to_char(to_timestamp(b.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"Start Date \", "
            . "to_char(to_timestamp(b.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"End Date \" " .
            "FROM prs.prsn_names_nos a "
            . "LEFT OUTER JOIN pasn.prsn_prsntyps b " .
            "ON (a.person_id = b.person_id and "
            . "b.prsntype_id = (SELECT MAX(c.prsntype_id) from pasn.prsn_prsntyps c where c.person_id = a.person_id)) " .
            "WHERE (a.person_id = $person_id)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllwdPrsnTyps() {
    $strSql = "select a.pssbl_value_desc from gst.gen_stp_lov_values a, gst.gen_stp_lov_names b, sec.sec_roles c
WHERE a.value_list_id = b.value_list_id and a.pssbl_value = c.role_name 
and b.value_list_name = 'Allowed Person Types for Roles' and a.is_enabled='1' 
and c.role_id IN (" . concatCurRoleIDs() . ") ORDER BY a.pssbl_value_id LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_FilterValues($fltrTyp, $orgID) {
    $result = null;
    if ($fltrTyp == "Position") {
        //Positions
        $result = executeSQLNoParams("Select position_code_name from org.org_positions where org_id=" . $orgID);
    } else if ($fltrTyp == "Division/Group") {
        //Div Groups
        $result = executeSQLNoParams("Select div_code_name from org.org_divs_groups where org_id=" . $orgID);
    } else if ($fltrTyp == "Grade") {
        //Grade
        $result = executeSQLNoParams("Select grade_code_name from org.org_grades where org_id=" . $orgID);
    } else if ($fltrTyp == "Job") {
        //Job
        $result = executeSQLNoParams("Select job_code_name from org.org_jobs where org_id=" . $orgID);
    } else {
        //Person Types
        $aldPrsTyp = getAllwdPrsnTyps();
        $extra3 = "";
        $aldPrsTyp = "'" . trim($aldPrsTyp, "'") . "'";
        if ($aldPrsTyp != "'All'") {
            $extra3 = " and pssbl_value IN (" . $aldPrsTyp . ")";
        }
        $result = getAllEnbldPssblVals("Person Types", $extra3);
    }
    return $result;
}

function getAllEnbldPssblVals($lovNm, $extrWhr) {
    global $orgID;
    $sqlStr = "select pssbl_value from gst.gen_stp_lov_values " .
            "WHERE is_enabled='1' and value_list_id = " . getLovID($lovNm) .
            " and allowed_org_ids ilike '%," . $orgID .
            ",%'" . $extrWhr . " ORDER BY 1";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function endOldPrsnTypes($prsnid, $nwStrtDte) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE pasn.prsn_prsntyps " .
            "SET last_update_by=" . $usrID . ", " .
            "last_update_date='" . $dateStr . "', valid_end_date='" . $nwStrtDte . "' " .
            "WHERE ((person_id=" . $prsnid .
            ") and (to_timestamp((CASE WHEN char_length(coalesce(valid_end_date,''))>0 THEN valid_end_date ELSE '1900-01-01' END) || ' 23:59:59' ,'YYYY-MM-DD HH24:MI:SS') " .
            ">= to_timestamp('" . $nwStrtDte . " 00:00:00','YYYY-MM-DD HH24:MI:SS')))";
    //echo $updtSQL;
    execUpdtInsSQL($updtSQL);
}

function checkPrsnType($prsnid, $prsntyp, $nwStrtDte, &$rowID) {
    $strSql = "SELECT prsntype_id " .
            "FROM pasn.prsn_prsntyps WHERE ((person_id = " . $prsnid .
            ") and (((prsn_type = '" . loc_db_escape_string($prsntyp) .
            "') and (to_timestamp(valid_start_date || ' 00:00:00','YYYY-MM-DD HH24:MI:SS') " .
            ">= to_timestamp('" . $nwStrtDte . "','YYYY-MM-DD HH24:MI:SS'))) or (to_timestamp(valid_start_date || ' 00:00:00','YYYY-MM-DD HH24:MI:SS') " .
            "= to_timestamp('" . $nwStrtDte . "','YYYY-MM-DD HH24:MI:SS'))))";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $rowID = $row[0];
        return true;
    }
    return false;
}

function createPrsnsType($prsnid, $rsn, $date1, $date2, $futhDet, $prsntyp) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO pasn.prsn_prsntyps(" .
            "person_id, prn_typ_asgnmnt_rsn, valid_start_date, valid_end_date, " .
            "created_by, creation_date, last_update_by, last_update_date, " .
            "further_details, prsn_type)" .
            "VALUES (" . $prsnid . ", '" . loc_db_escape_string($rsn) .
            "', '" . loc_db_escape_string($date1) . "', '" . loc_db_escape_string($date2) . "', " .
            "" . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr . "', " .
            "'" . loc_db_escape_string($futhDet) . "', '" . loc_db_escape_string($prsntyp) . "')";
    execUpdtInsSQL($insSQL);
}

function updtPrsnsType($rowid, $prsnid, $rsn, $date1, $date2, $futhDet, $prsntyp) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE pasn.prsn_prsntyps " .
            "SET person_id=" . $prsnid . ", prn_typ_asgnmnt_rsn='" . loc_db_escape_string($rsn) .
            "', valid_start_date='" . loc_db_escape_string($date1) .
            "', valid_end_date='" . loc_db_escape_string($date2) . "', " .
            "last_update_by=" . $usrID . ", last_update_date='" . $dateStr . "', " .
            "further_details='" . loc_db_escape_string($futhDet) .
            "', prsn_type='" . loc_db_escape_string($prsntyp) . "' " .
            "WHERE prsntype_id= " . $rowid;
    execUpdtInsSQL($updtSQL);
}

function createPrsnBasic($frstnm, $surname, $othnm, $title
        , $loc_id, $orgid, $gender, $marsts, $dob, $pob, $rlgn, $resaddrs, $pstladrs, $email, $tel, $mobl, $fax, $hometwn, $ntnlty, $imgLoc, $lnkdFrmID, $lnkdFrmSiteID, $lnkdFirmNm, $lnkdFirmSiteName) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO prs.prsn_names_nos(" .
            "created_by, creation_date, last_update_by, last_update_date, " .
            "first_name, sur_name, other_names, title, local_id_no, org_id, " .
            "gender, marital_status, date_of_birth, place_of_birth, religion, " .
            "res_address, pstl_addrs, email, cntct_no_tel, cntct_no_mobl, " .
            "cntct_no_fax, hometown, nationality, img_location, lnkd_firm_org_id, 
            lnkd_firm_site_id, new_company, new_company_loc)" .
            "VALUES (" . $usrID . ", '" . $dateStr . "', " .
            $usrID . ", '" . $dateStr . "', '" . loc_db_escape_string($frstnm) . "', " .
            "'" . loc_db_escape_string($surname) . "', '" . loc_db_escape_string($othnm) .
            "', '" . loc_db_escape_string($title) . "', '" . loc_db_escape_string($loc_id) .
            "', " . $orgid . ", '" . loc_db_escape_string($gender) . "', " .
            "'" . loc_db_escape_string($marsts) . "', '" . $dob .
            "', '" . loc_db_escape_string($pob) . "', '" . loc_db_escape_string($rlgn) .
            "', '" . loc_db_escape_string($resaddrs) . "', " .
            "'" . loc_db_escape_string($pstladrs) . "', '" . loc_db_escape_string($email) .
            "', '" . loc_db_escape_string($tel) . "', '" . loc_db_escape_string($mobl) .
            "', '" . loc_db_escape_string($fax) . "', '" . loc_db_escape_string($hometwn) .
            "', '" . loc_db_escape_string($ntnlty) . "', '" . loc_db_escape_string($imgLoc) .
            "', " . $lnkdFrmID . ", " . $lnkdFrmSiteID .
            ", '" . loc_db_escape_string($lnkdFirmNm) . "', '" . loc_db_escape_string($lnkdFirmSiteName) . "')";
    execUpdtInsSQL($insSQL);
}

function updatePrsnBasic($prsnid, $frstnm, $surname, $othnm, $title
        , $loc_id, $orgid, $gender, $marsts, $dob, $pob, $rlgn, $resaddrs, $pstladrs, $email, $tel, $mobl, $fax, $hometwn, $ntnlty, $lnkdFrmID, $lnkdFrmSiteID, $lnkdFirmNm, $lnkdFirmSiteName) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE prs.prsn_names_nos " .
            "SET last_update_by=" . $usrID . ", " .
            "last_update_date='" . $dateStr .
            "', first_name='" . loc_db_escape_string($frstnm) .
            "', sur_name='" . loc_db_escape_string($surname) .
            "', other_names='" . loc_db_escape_string($othnm) .
            "', title='" . loc_db_escape_string($title) .
            "', local_id_no='" . loc_db_escape_string($loc_id) .
            "', org_id=" . $orgid . ", gender='" . loc_db_escape_string($gender) .
            "', marital_status='" . loc_db_escape_string($marsts) . "', date_of_birth='" . $dob .
            "', place_of_birth='" . loc_db_escape_string($pob) .
            "', religion='" . loc_db_escape_string($rlgn) .
            "', res_address='" . loc_db_escape_string($resaddrs) .
            "', pstl_addrs='" . loc_db_escape_string($pstladrs) .
            "', email='" . loc_db_escape_string($email) .
            "', cntct_no_tel='" . loc_db_escape_string($tel) .
            "', cntct_no_mobl='" . loc_db_escape_string($mobl) .
            "', cntct_no_fax='" . loc_db_escape_string($fax) .
            "', hometown='" . loc_db_escape_string($hometwn) .
            "', nationality='" . loc_db_escape_string($ntnlty) .
            "', lnkd_firm_org_id=" . $lnkdFrmID .
            ", lnkd_firm_site_id=" . $lnkdFrmSiteID .
            ", new_company='" . loc_db_escape_string($lnkdFirmNm) .
            "', new_company_loc='" . loc_db_escape_string($lnkdFirmSiteName) .
            "' WHERE person_id=" . $prsnid;
    execUpdtInsSQL($updtSQL);
}

function createPrsnBasicSelf($prsnID, $frstnm, $surname, $othnm, $title
        , $loc_id, $orgid, $gender, $marsts, $dob, $pob, $rlgn, $resaddrs, $pstladrs, $email, $tel, $mobl, $fax, $hometwn, $ntnlty, $imgLoc, $lnkdFrmID, $lnkdFrmSiteID, $lnkdFirmNm, $lnkdFirmSiteName) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO self.self_prsn_names_nos(person_id, " .
            "created_by, creation_date, last_update_by, last_update_date, " .
            "first_name, sur_name, other_names, title, local_id_no, org_id, " .
            "gender, marital_status, date_of_birth, place_of_birth, religion, " .
            "res_address, pstl_addrs, email, cntct_no_tel, cntct_no_mobl, " .
            "cntct_no_fax, hometown, nationality, img_location, lnkd_firm_org_id, 
            lnkd_firm_site_id, new_company, new_company_loc)" .
            "VALUES (" . $prsnID . ", " . $usrID . ", '" . $dateStr . "', " .
            $usrID . ", '" . $dateStr . "', '" . loc_db_escape_string($frstnm) . "', " .
            "'" . loc_db_escape_string($surname) . "', '" . loc_db_escape_string($othnm) .
            "', '" . loc_db_escape_string($title) . "', '" . loc_db_escape_string($loc_id) .
            "', " . $orgid . ", '" . loc_db_escape_string($gender) . "', " .
            "'" . loc_db_escape_string($marsts) . "', '" . $dob .
            "', '" . loc_db_escape_string($pob) . "', '" . loc_db_escape_string($rlgn) .
            "', '" . loc_db_escape_string($resaddrs) . "', " .
            "'" . loc_db_escape_string($pstladrs) . "', '" . loc_db_escape_string($email) .
            "', '" . loc_db_escape_string($tel) . "', '" . loc_db_escape_string($mobl) .
            "', '" . loc_db_escape_string($fax) . "', '" . loc_db_escape_string($hometwn) .
            "', '" . loc_db_escape_string($ntnlty) . "', '" . loc_db_escape_string($imgLoc) .
            "', " . $lnkdFrmID . ", " . $lnkdFrmSiteID .
            ", '" . loc_db_escape_string($lnkdFirmNm) . "', '" . loc_db_escape_string($lnkdFirmSiteName) . "')";
    execUpdtInsSQL($insSQL);
}

function updatePrsnBasicSelf($prsnid, $frstnm, $surname, $othnm, $title, $loc_id, $orgid, $gender, $marsts, $dob, $pob, $rlgn, $resaddrs, $pstladrs, $email, $tel, $mobl, $fax, $hometwn, $ntnlty,
        $lnkdFrmID, $lnkdFrmSiteID, $lnkdFirmNm, $lnkdFirmSiteName) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE self.self_prsn_names_nos " .
            "SET last_update_by=" . $usrID . ", " .
            "last_update_date='" . $dateStr .
            "', first_name='" . loc_db_escape_string($frstnm) .
            "', sur_name='" . loc_db_escape_string($surname) .
            "', other_names='" . loc_db_escape_string($othnm) .
            "', title='" . loc_db_escape_string($title) .
            "', local_id_no='" . loc_db_escape_string($loc_id) .
            "', org_id=" . $orgid . ", gender='" . loc_db_escape_string($gender) .
            "', marital_status='" . loc_db_escape_string($marsts) . "', date_of_birth='" . $dob .
            "', place_of_birth='" . loc_db_escape_string($pob) .
            "', religion='" . loc_db_escape_string($rlgn) .
            "', res_address='" . loc_db_escape_string($resaddrs) .
            "', pstl_addrs='" . loc_db_escape_string($pstladrs) .
            "', email='" . loc_db_escape_string($email) .
            "', cntct_no_tel='" . loc_db_escape_string($tel) .
            "', cntct_no_mobl='" . loc_db_escape_string($mobl) .
            "', cntct_no_fax='" . loc_db_escape_string($fax) .
            "', hometown='" . loc_db_escape_string($hometwn) .
            "', nationality='" . loc_db_escape_string($ntnlty) .
            "', lnkd_firm_org_id=" . $lnkdFrmID .
            ", lnkd_firm_site_id=" . $lnkdFrmSiteID .
            ", new_company='" . loc_db_escape_string($lnkdFirmNm) .
            "', new_company_loc='" . loc_db_escape_string($lnkdFirmSiteName) .
            "' WHERE person_id=" . $prsnid;
    execUpdtInsSQL($updtSQL);
}

function uploadDaImage($prsnid, &$nwImgLoc) {
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    if (isset($_FILES["daPrsnPicture"])) {
        //$files = multiple($_FILES);
        $flnm = $_FILES["daPrsnPicture"]["name"];
        $msg .= $flnm;
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daPrsnPicture"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daPrsnPicture"]["error"] . "<br>";
        } else {
            $msg .= "Upload: " . $_FILES["daPrsnPicture"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daPrsnPicture"]["type"] . "<br>";
            $msg .= "Size: " . ($_FILES["daPrsnPicture"]["size"]) . " bytes<br>";
            $msg .= "Temp file: " . $_FILES["daPrsnPicture"]["tmp_name"] . "<br>";
            if ((($_FILES["daPrsnPicture"]["type"] == "image/gif") ||
                    ($_FILES["daPrsnPicture"]["type"] == "image/jpeg") ||
                    ($_FILES["daPrsnPicture"]["type"] == "image/jpg") ||
                    ($_FILES["daPrsnPicture"]["type"] == "image/pjpeg") ||
                    ($_FILES["daPrsnPicture"]["type"] == "image/x-png") ||
                    ($_FILES["daPrsnPicture"]["type"] == "image/png") ||
                    in_array($extension, $allowedExts)) &&
                    ($_FILES["daPrsnPicture"]["size"] < 2000000)) {
                $nwFileName = encrypt1($prsnid . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daPrsnPicture"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/Person/$prsnid" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");

                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE prs.prsn_names_nos " .
                            "SET last_update_by=" . $usrID . ", " .
                            "last_update_date='" . $dateStr .
                            "', img_location = '" . $prsnid . "." . $extension . "' WHERE person_id=" . $prsnid;
                    execUpdtInsSQL($updtSQL);
                }
                $msg .= "Image Stored";
                $nwImgLoc = "$prsnid" . "." . $extension;
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

function uploadDaImageExcel($prsnid, $inputImgName, &$nwImgLoc) {
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array("gif", "jpeg", "jpg", "png", "bmp");
    if ($inputImgName != "") {
        //$files = multiple($_FILES);
        $flnm = $inputImgName;
        $msg .= $flnm;
        $temp = explode(".", $flnm);
        $extension = end($temp);
        $img_src = $inputImgName;
        $msg .= "Upload: " . $inputImgName . "<br>";
        $msg .= "Type: " . $inputImgName . "<br>";
        $msg .= "Size: " . $inputImgName . " bytes<br>";
        $msg .= "Temp file: " . $inputImgName . "<br>";
        if (in_array($extension, $allowedExts)) {
            //$nwFileName = encrypt1($prsnid . "." . $extension, $smplTokenWord1) . "." . $extension;
            //$img_src = $fldrPrfx . $tmpDest . "$nwFileName";
            //copy($inputImgName, $img_src);
            $ftp_src = $ftp_base_db_fldr . "/Person/$prsnid" . "." . $extension;
            if (file_exists($img_src)) {
                copy("$img_src", "$ftp_src");
                $dateStr = getDB_Date_time();
                $updtSQL = "UPDATE prs.prsn_names_nos " .
                        "SET last_update_by=" . $usrID . ", " .
                        "last_update_date='" . $dateStr .
                        "', img_location = '" . $prsnid . "." . $extension . "' WHERE person_id=" . $prsnid;
                execUpdtInsSQL($updtSQL);
            }
            $msg .= "Image Stored";
            $nwImgLoc = "$prsnid" . "." . $extension;
            return TRUE;
        } else {
            $msg .= "<br/>Invalid file!<br/>File Size must be below 2MB and<br/>File Type must be in the ff:<br/>" . implode(", ", $allowedExts);
            $nwImgLoc = $msg;
        }
    }
    $msg .= "<br/>Invalid file";
    $nwImgLoc = $msg;
    return FALSE;
}

function uploadDaImageSelf($prsnid, &$nwImgLoc) {
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    if (isset($_FILES["daPrsnPicture"])) {
        //$files = multiple($_FILES);
        $flnm = $_FILES["daPrsnPicture"]["name"];
        $msg .= $flnm;
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daPrsnPicture"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daPrsnPicture"]["error"] . "<br>";
        } else {
            $msg .= "Upload: " . $_FILES["daPrsnPicture"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daPrsnPicture"]["type"] . "<br>";
            $msg .= "Size: " . ($_FILES["daPrsnPicture"]["size"]) . " bytes<br>";
            $msg .= "Temp file: " . $_FILES["daPrsnPicture"]["tmp_name"] . "<br>";
            if ((($_FILES["daPrsnPicture"]["type"] == "image/gif") ||
                    ($_FILES["daPrsnPicture"]["type"] == "image/jpeg") ||
                    ($_FILES["daPrsnPicture"]["type"] == "image/jpg") ||
                    ($_FILES["daPrsnPicture"]["type"] == "image/pjpeg") ||
                    ($_FILES["daPrsnPicture"]["type"] == "image/x-png") ||
                    ($_FILES["daPrsnPicture"]["type"] == "image/png") ||
                    in_array($extension, $allowedExts)) &&
                    ($_FILES["daPrsnPicture"]["size"] < 2000000)) {
                $nwFileName = encrypt1($prsnid . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daPrsnPicture"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/Person/Request/$prsnid" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");

                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE self.self_prsn_names_nos " .
                            "SET last_update_by=" . $usrID . ", " .
                            "last_update_date='" . $dateStr .
                            "', img_location = '" . $prsnid . "." . $extension . "' WHERE person_id=" . $prsnid;
                    execUpdtInsSQL($updtSQL);
                }
                $msg .= "Image Stored";
                $nwImgLoc = "$prsnid" . "." . $extension;
                return TRUE;
            } else {
                $msg .= "<br/>Invalid file";
                $nwImgLoc = $msg;
            }
        }
    }
    $msg .= "<br/>Invalid file!<br/>File Size must be below 2MB and<br/>File Type must be in the ff:<br/>" . implode(", ", $allowedExts);
    $nwImgLoc = $msg;
    return FALSE;
}

function uploadDaDocSelf($attchmntID, &$nwImgLoc, &$errMsg) {
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array('png', 'jpg', 'gif', 'jpeg', 'bmp', 'pdf', 'xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'csv');

    if (isset($_FILES["daPrsnAttchmnt"])) {
        $flnm = $_FILES["daPrsnAttchmnt"]["name"];
        //$msg .= $flnm;
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daPrsnAttchmnt"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daPrsnAttchmnt"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["daPrsnAttchmnt"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daPrsnAttchmnt"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["daPrsnAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
            //$msg .= "Temp file: " . $_FILES["daPrsnAttchmnt"]["tmp_name"] . "<br>";
            if ((($_FILES["daPrsnAttchmnt"]["type"] == "image/gif") ||
                    ($_FILES["daPrsnAttchmnt"]["type"] == "image/jpeg") ||
                    ($_FILES["daPrsnAttchmnt"]["type"] == "image/jpg") ||
                    ($_FILES["daPrsnAttchmnt"]["type"] == "image/pjpeg") ||
                    ($_FILES["daPrsnAttchmnt"]["type"] == "image/x-png") ||
                    ($_FILES["daPrsnAttchmnt"]["type"] == "image/png") ||
                    in_array($extension, $allowedExts)) &&
                    ($_FILES["daPrsnAttchmnt"]["size"] < 6000000)) {
                $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daPrsnAttchmnt"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/PrsnDocs/Request/$attchmntID" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");

                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE self.self_prsn_doc_attchmnts
                            SET file_name='" . $attchmntID . "." . $extension .
                            "', last_update_by=" . $usrID .
                            ", last_update_date='" . $dateStr .
                            "' WHERE attchmnt_id=" . $attchmntID;
                    execUpdtInsSQL($updtSQL);
                }
                $msg .= "Document Stored Successfully!<br/>";
                $nwImgLoc = "$attchmntID" . "." . $extension;
                $errMsg = $msg;
                return TRUE;
            } else {
                $msg .= "Invalid file!<br/>File Size must be below 6MB and<br/>File Type must be in the ff:<br/>" . implode(", ", $allowedExts);
                $nwImgLoc = $msg;
                $errMsg = $msg;
            }
        }
    }
    $msg .= "<br/>Invalid file";
    $nwImgLoc = $msg;
    $errMsg = $msg;
    return FALSE;
}

function uploadDaDoc($attchmntID, &$nwImgLoc, &$errMsg) {
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array('png', 'jpg', 'gif', 'jpeg', 'bmp', 'pdf', 'xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'csv');

    if (isset($_FILES["daPrsnAttchmnt"])) {
        $flnm = $_FILES["daPrsnAttchmnt"]["name"];
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daPrsnAttchmnt"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daPrsnAttchmnt"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["daPrsnAttchmnt"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daPrsnAttchmnt"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["daPrsnAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
            //$msg .= "Temp file: " . $_FILES["daPrsnAttchmnt"]["tmp_name"] . "<br>";
            if ((($_FILES["daPrsnAttchmnt"]["type"] == "image/gif") ||
                    ($_FILES["daPrsnAttchmnt"]["type"] == "image/jpeg") ||
                    ($_FILES["daPrsnAttchmnt"]["type"] == "image/jpg") ||
                    ($_FILES["daPrsnAttchmnt"]["type"] == "image/pjpeg") ||
                    ($_FILES["daPrsnAttchmnt"]["type"] == "image/x-png") ||
                    ($_FILES["daPrsnAttchmnt"]["type"] == "image/png") ||
                    in_array($extension, $allowedExts)) &&
                    ($_FILES["daPrsnAttchmnt"]["size"] < 6000000)) {
                $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daPrsnAttchmnt"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/PrsnDocs/$attchmntID" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");

                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE prs.prsn_doc_attchmnts
                            SET file_name='" . $attchmntID . "." . $extension .
                            "', last_update_by=" . $usrID .
                            ", last_update_date='" . $dateStr .
                            "' WHERE attchmnt_id=" . $attchmntID;
                    execUpdtInsSQL($updtSQL);
                }
                $msg .= "Document Stored Successfully!<br/>";
                $nwImgLoc = "$attchmntID" . "." . $extension;
                $errMsg = $msg;
                return TRUE;
            } else {
                $msg .= "Invalid file!<br/>File Size must be below 6MB and<br/>File Type must be in the ff:<br/>" . implode(", ", $allowedExts);
                $nwImgLoc = $msg;
                $errMsg = $msg;
            }
        }
    }
    $msg .= "<br/>Invalid file";
    $nwImgLoc = $msg;
    $errMsg = $msg;
    return FALSE;
}

function prsn_ChngRqst_Exist($pkID) {
    $sqlStr = "select 1 from self.self_prsn_chng_rqst WHERE (person_id=$pkID)
           and rqst_status in ('Initiated','Requires Approval')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function prsn_Record_Exist($pkID) {
    $sqlStr = "select person_id FROM self.self_prsn_names_nos WHERE (person_id=$pkID)";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function prsn_AddtnlRecord_Exist($pkID) {
    $sqlStr = "select extra_data_id FROM prs.prsn_extra_data WHERE (person_id=$pkID "
            . "and (coalesce(data_col1,'')!='' or coalesce(data_col2,'')!=''"
            . " or coalesce(data_col3,'')!='' or coalesce(data_col4,'')!=''"
            . " or coalesce(data_col5,'')!='' or coalesce(data_col6,'')!=''"
            . " or coalesce(data_col7,'')!='' or coalesce(data_col8,'')!=''"
            . " or coalesce(data_col9,'')!='' or coalesce(data_col10,'')!=''"
            . " or coalesce(data_col11,'')!='' or coalesce(data_col12,'')!=''"
            . " or coalesce(data_col13,'')!='' or coalesce(data_col14,'')!=''"
            . " or coalesce(data_col15,'')!='' or coalesce(data_col16,'')!=''"
            . " or coalesce(data_col17,'')!='' or coalesce(data_col18,'')!=''"
            . " or coalesce(data_col19,'')!='' or coalesce(data_col20,'')!=''"
            . " or coalesce(data_col21,'')!='' or coalesce(data_col22,'')!=''"
            . " or coalesce(data_col23,'')!='' or coalesce(data_col24,'')!=''"
            . " or coalesce(data_col25,'')!='' or coalesce(data_col26,'')!=''"
            . " or coalesce(data_col27,'')!='' or coalesce(data_col28,'')!=''"
            . " or coalesce(data_col29,'')!='' or coalesce(data_col30,'')!=''"
            . " or coalesce(data_col31,'')!='' or coalesce(data_col32,'')!=''"
            . " or coalesce(data_col33,'')!='' or coalesce(data_col34,'')!=''"
            . " or coalesce(data_col35,'')!='' or coalesce(data_col36,'')!=''"
            . " or coalesce(data_col37,'')!='' or coalesce(data_col38,'')!=''"
            . " or coalesce(data_col39,'')!='' or coalesce(data_col40,'')!=''"
            . " or coalesce(data_col41,'')!='' or coalesce(data_col42,'')!=''"
            . " or coalesce(data_col43,'')!='' or coalesce(data_col44,'')!=''"
            . " or coalesce(data_col45,'')!='' or coalesce(data_col46,'')!=''"
            . " or coalesce(data_col47,'')!='' or coalesce(data_col48,'')!=''"
            . " or coalesce(data_col49,'')!='' or coalesce(data_col50,'')!=''))";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_RqstStatus($pkID) {
    $sqlStr = "select rqst_status from self.self_prsn_chng_rqst WHERE (person_id=$pkID)
           and rqst_id = (select coalesce(max(rqst_id),0) from self.self_prsn_chng_rqst WHERE (person_id=$pkID))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 'Approved';
}

function get_RqstStatusUsngID($pkID) {
    $sqlStr = "select rqst_status from self.self_prsn_chng_rqst WHERE rqst_id = " . $pkID;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 'Approved';
}

function get_RqstID($pkID) {
    $sqlStr = "select coalesce(max(rqst_id),0) from self.self_prsn_chng_rqst WHERE (person_id=$pkID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_SelfPrsnDet($pkID) {
    $strSql = "SELECT a.person_id mt, local_id_no \"ID No.\", COALESCE(img_location,'') \"Person's Picture\", 
          title, first_name, sur_name \"surname\", other_names, org.get_org_name(org_id) organisation, 
          gender, marital_status, 
          to_char(to_timestamp(date_of_birth,'YYYY-MM-DD'),'DD-Mon-YYYY') \"Date of Birth\", 
          place_of_birth, religion, 
          res_address residential_address, pstl_addrs postal_address, email, 
          cntct_no_tel tel, cntct_no_mobl mobile, 
          cntct_no_fax fax, hometown, nationality, 
          (CASE WHEN lnkd_firm_org_id>0 THEN 
          scm.get_cstmr_splr_name(lnkd_firm_org_id)
              ELSE 
              new_company
              END) \"Linked Firm/ Workplace \", 
          (CASE WHEN lnkd_firm_org_id>0 THEN 
           scm.get_cstmr_splr_site_name(lnkd_firm_site_id)
              ELSE 
              new_company_loc
              END) \"Branch \", 
            b.prsn_type \"Relation Type\", 
            b.prn_typ_asgnmnt_rsn \"Cause of Relation\", 
            b.further_details \"Further Details\", 
            to_char(to_timestamp(b.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"Start Date \", 
            to_char(to_timestamp(b.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"End Date \",
            lnkd_firm_org_id 
            FROM self.self_prsn_names_nos a 
            LEFT OUTER JOIN pasn.prsn_prsntyps b 
            ON (a.person_id = b.person_id and 
           b.prsntype_id = (SELECT MAX(c.prsntype_id) from pasn.prsn_prsntyps c where c.person_id = a.person_id)) 
    WHERE (a.person_id=$pkID)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PrsnDet($pkID) {
    $strSql = "SELECT a.person_id mt, local_id_no \"ID No.\", 
       CASE WHEN COALESCE(img_location,'')='' THEN a.person_id ||'.png' ELSE COALESCE(img_location,'') END \"Person's Picture\", 
          title, first_name, sur_name \"surname\", other_names, org.get_org_name(org_id) organisation, 
          gender, marital_status, 
          to_char(to_timestamp(date_of_birth,'YYYY-MM-DD'),'DD-Mon-YYYY') \"Date of Birth\", 
          place_of_birth, religion, 
          res_address residential_address, pstl_addrs postal_address, email, 
          cntct_no_tel tel, cntct_no_mobl mobile, 
          cntct_no_fax fax, hometown, nationality, 
          (CASE WHEN lnkd_firm_org_id>0 THEN 
          scm.get_cstmr_splr_name(lnkd_firm_org_id)
              ELSE 
              new_company
              END) \"Linked Firm/ Workplace \", 
          (CASE WHEN lnkd_firm_org_id>0 THEN 
           scm.get_cstmr_splr_site_name(lnkd_firm_site_id)
              ELSE 
              new_company_loc
              END) \"Branch \", 
          b.prsn_type \"Relation Type\", 
          b.prn_typ_asgnmnt_rsn \"Cause of Relation\", 
            b.further_details \"Further Details\", 
            to_char(to_timestamp(b.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"Start Date \", 
            to_char(to_timestamp(b.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"End Date \",
            lnkd_firm_org_id
            FROM prs.prsn_names_nos a  
            LEFT OUTER JOIN pasn.prsn_prsntyps b 
            ON (a.person_id = b.person_id and 
           b.prsntype_id = (SELECT MAX(c.prsntype_id) from pasn.prsn_prsntyps c where c.person_id = a.person_id))  
    WHERE (a.person_id=$pkID)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllNtnlty($pkID) {
    $strSql = "SELECT ntnlty_id mt, nationality \"Country\", national_id_typ national_id_type, 
        id_number, date_issued, expiry_date, other_info other_information 
          FROM prs.prsn_national_ids WHERE ((person_id = $pkID))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllNtnlty_Self($pkID) {
    $strSql = "SELECT ntnlty_id mt, nationality \"Country\", national_id_typ national_id_type, 
        id_number, date_issued, expiry_date, other_info other_information 
          FROM self.self_prsn_national_ids WHERE ((person_id = $pkID))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNtnltyID($prsnID, $country, $idtype) {
    //Example priviledge 'View Security Module'
    $sqlStr = "SELECT ntnlty_id from prs.prsn_national_ids where (lower(nationality) = '" .
            loc_db_escape_string(strtolower($country)) . "' AND lower(national_id_typ) = '" .
            loc_db_escape_string(strtolower($idtype)) . "' AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNtnltySelfID($prsnID, $country, $idtype) {
    //Example priviledge 'View Security Module'
    $sqlStr = "SELECT ntnlty_id from self.self_prsn_national_ids where (lower(nationality) = '" .
            loc_db_escape_string(strtolower($country)) . "' AND lower(national_id_typ) = '" .
            loc_db_escape_string(strtolower($idtype)) . "' AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getAllRltvs($pkID) {
    $strSql = "SELECT a.local_id_no \"Relative's ID No.\", 
        trim(a.title || ' ' || a.sur_name || 
           ', ' || a.first_name || ' ' || a.other_names) \"Relative's full_name\", 
            b.relationship_type, b.relative_prsn_id mt, b.rltv_id mt
               FROM prs.prsn_relatives b 
                   LEFT OUTER JOIN prs.prsn_names_nos a 
                   ON b.relative_prsn_id = a.person_id 
                   WHERE ((b.person_id = $pkID)) ORDER BY b.relationship_type, a.local_id_no";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PrsnTypes($pkID) {
    $strSql = "SELECT person_id mt, prsn_type person_type, 
        prn_typ_asgnmnt_rsn reason_for_this_person_type, 
        further_details, 
to_char(to_timestamp(valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') start_date, 
to_char(to_timestamp(valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') end_date
            FROM pasn.prsn_prsntyps WHERE ((person_id =  $pkID)) 
                ORDER BY valid_end_date DESC, valid_start_date DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllPrsnTypsRpt($prsnid) {
    $strSql = "SELECT distinct prsn_type \" Relationship Type \", prn_typ_asgnmnt_rsn \" Relationship Type Reason \", further_details \" Further Details   \", " .
            "CASE WHEN valid_start_date!='' THEN to_char(to_timestamp(valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') ELSE '' END \"Start Date  \", " .
            "CASE WHEN valid_end_date!='' THEN REPLACE(to_char(to_timestamp(valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'),'31-Dec-4000','') ELSE '' END \"End Date    \", " .
            "valid_end_date mt, valid_start_date mt " .
            "FROM pasn.prsn_prsntyps WHERE ((person_id = " . $prsnid .
            ")) ORDER BY valid_end_date DESC, valid_start_date DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllSkillsRpt($prsnid) {
    $strSql = "SELECT languages \"Languages     \", 
        trim(hobbies || ', ' || interests, ', ') \"Hobbies/Interests     \", 
        trim(conduct || ', ' || attitude, ', ') \"Conduct/Attitude     \", 
        to_char(to_timestamp(valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"From        \", 
        REPLACE(to_char(to_timestamp(valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), '31-Dec-4000','') \"To          \", skills_id mt " .
            "FROM prs.prsn_skills_nature WHERE ((person_id = " . $prsnid .
            ")) ORDER BY valid_end_date DESC, valid_start_date DESC, skills_id DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllWrkExpRpt($prsnid) {
    $strSql = "SELECT job_name_title \"Job Title         \", 
        institution_name || ' ' || job_location \"Institution                    \", 
       to_char(to_timestamp(job_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \" Start Date    \", 
REPLACE(to_char(to_timestamp(job_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), '31-Dec-4000','') \" End Date    \", 
job_description || ' ' || feats_achvments \"Remarks           \", wrk_exprnc_id mt 
                  FROM prs.prsn_work_experience WHERE ((person_id = " . $prsnid .
            ")) ORDER BY job_end_date DESC, job_start_date DESC, wrk_exprnc_id DESC";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllNtnltyRpt($prsnid) {
    $strSql = "SELECT nationality \" Country   \", national_id_typ \" ID Type      \", id_number \" ID Number       \",
      ntnlty_id mt, date_issued \" Date Issued \", expiry_date \" Expiry Date \", other_info \" Other Information     \"
                  FROM prs.prsn_national_ids WHERE ((person_id = " . $prsnid .
            "))";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllEducRpt($prsnid) {
    $strSql = "SELECT course_name \" Course Name       \", school_institution || ' ' || school_location \" School/Institution                     \", 
       to_char(to_timestamp(course_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') mt, 
       to_char(to_timestamp(course_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') mt1, 
       cert_obtained || ' (' || cert_type || ')' \" Certificate Obtained  \", date_cert_awarded \" Date Obtained  \", educ_id mt 
                  FROM prs.prsn_education WHERE ((person_id = " . $prsnid .
            ")) ORDER BY course_end_date DESC, course_start_date DESC, educ_id DESC";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllSitesRpts($prsnid) {
    $strSql = "SELECT a.location_id mt, b.location_code_name \" Branch Name                  \", 
to_char(to_timestamp(a.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \" Start Date    \", 
REPLACE(to_char(to_timestamp(a.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'),'31-Dec-4000','') \" End Date    \", 
a.prsn_loc_id mt, gst.get_pssbl_val(b.site_type_id) site_type 
FROM pasn.prsn_locations a, org.org_sites_locations b WHERE ((a.location_id = b.location_id) and (a.person_id = " . $prsnid .
            ")) ORDER BY a.valid_end_date DESC, a.valid_start_date DESC, a.prsn_loc_id DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllDivsRpts($prsnid) {
    $strSql = "SELECT org.get_div_name(a.div_id) \" Group Name                   \", 
to_char(to_timestamp(valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \" Start Date    \", 
REPLACE(to_char(to_timestamp(valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'),'31-Dec-4000','') \" End Date    \", a.prsn_div_id mt, 
org.get_div_type(a.div_id) \" Group Type              \" 
                FROM pasn.prsn_divs_groups a WHERE ((person_id = " . $prsnid .
            ") and (now() between to_timestamp(valid_start_date|| ' 00:00:00','YYYY-MM-DD HH24:MI:SS')
                  AND to_timestamp(valid_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS'))) ORDER BY valid_end_date DESC, valid_start_date DESC";


    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllJobsRpt($prsnid) {
    $strSql = "SELECT a.job_id mt, b.job_code_name \" Job                          \", 
to_char(to_timestamp(a.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \" Start Date    \", 
REPLACE(to_char(to_timestamp(a.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'),'31-Dec-4000','') \" End Date    \", row_id mt1 
            FROM pasn.prsn_jobs a, org.org_jobs b WHERE ((a.job_id = b.job_id) and (a.person_id = " . $prsnid .
            ")) ORDER BY a.valid_end_date DESC, a.valid_start_date DESC,a.row_id DESC";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllGradesRpt($prsnid) {
    $strSql = "SELECT a.grade_id mt, b.grade_code_name \" Grade                        \",
to_char(to_timestamp(a.valid_start_date, 'YYYY-MM-DD'),'DD-Mon-YYYY') \" Start Date    \", 
REPLACE(to_char(to_timestamp(a.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'),'31-Dec-4000','') \" End Date    \", row_id mt1 
            FROM pasn.prsn_grades a, org.org_grades b WHERE ((a.grade_id = b.grade_id) and (a.person_id = " . $prsnid .
            ")) ORDER BY a.valid_end_date DESC, a.valid_start_date DESC,a.row_id DESC";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllPositionsRpt($prsnid) {
    $strSql = "SELECT a.position_id mt, b.position_code_name \" Position                     \",
to_char(to_timestamp(a.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \" Start Date    \", 
REPLACE(to_char(to_timestamp(a.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'),'31-Dec-4000','') \" End Date    \", a.row_id mt1, a.div_id mt2,
(select REPLACE(y.div_code_name || '.' || y.div_desc, '.' || y.div_code_name,'') from org.org_divs_groups y where y.div_id=a.div_id) div 
            FROM pasn.prsn_positions a, org.org_positions b WHERE ((a.position_id = b.position_id) and (a.person_id = " . $prsnid .
            ")) ORDER BY a.valid_end_date DESC, a.valid_start_date DESC, a.row_id DESC";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllRltvsRpt($prsnid) {
    $strSql = "SELECT a.local_id_no \" Relative's ID No. \", trim(a.title || ' ' || a.sur_name || 
                ', ' || a.first_name || ' ' || a.other_names) \" Relative's Full Name                 \", 
                b.relationship_type \" Relation Type           \", b.relative_prsn_id mt, b.rltv_id mt 
                 FROM prs.prsn_relatives b LEFT OUTER JOIN prs.prsn_names_nos a ON b.relative_prsn_id = a.person_id WHERE ((b.person_id = " . $prsnid .
            ")) ORDER BY a.local_id_no DESC ";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_EducBkgrd($pkID) {
    $strSql = "SELECT educ_id mt, course_name, school_institution \"school/institution\", 
        school_location, 
       to_char(to_timestamp(course_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') start_date, 
        to_char(to_timestamp(course_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') end_date, 
        cert_obtained certificate_obtained, cert_type certificate_type, 
        date_cert_awarded date_awarded  
      FROM prs.prsn_education a WHERE ((person_id = $pkID)) 
      ORDER BY a.course_end_date DESC, a.course_start_date DESC, 1 DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_WrkBkgrd($pkID) {
    $strSql = "SELECT wrk_exprnc_id mt, job_name_title \"job name/title\", institution_name, job_location, 
        to_char(to_timestamp(job_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') start_date, 
        to_char(to_timestamp(job_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') end_date, job_description, 
        feats_achvments \"feats/achievements\"
        FROM prs.prsn_work_experience a 
        WHERE ((person_id = $pkID)) 
        ORDER BY a.job_end_date DESC, a.job_start_date DESC, 1 DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SkillNature($pkID) {
    $strSql = "SELECT skills_id mt, languages, hobbies, interests, 
       conduct, attitude, to_char(to_timestamp(valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"valid_start_date\", 
        to_char(to_timestamp(valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"valid_end_date\"
        FROM prs.prsn_skills_nature a WHERE ((person_id = $pkID)) 
        ORDER BY a.valid_end_date DESC, a.valid_start_date DESC, 1 DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_EducBkgrdSelf($pkID) {
    $strSql = "SELECT educ_id mt, course_name, school_institution \"school/institution\", 
        school_location, 
       to_char(to_timestamp(course_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') start_date, 
        to_char(to_timestamp(course_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') end_date, 
        cert_obtained certificate_obtained, cert_type certificate_type, 
        date_cert_awarded date_awarded  
      FROM self.self_prsn_education a WHERE ((person_id = $pkID)) 
      ORDER BY a.course_end_date DESC, a.course_start_date DESC, 1 DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_WrkBkgrdSelf($pkID) {
    $strSql = "SELECT wrk_exprnc_id mt, job_name_title \"job name/title\", institution_name, job_location, 
        to_char(to_timestamp(job_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') start_date, 
        to_char(to_timestamp(job_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') end_date, job_description, 
        feats_achvments \"feats/achievements\"
        FROM self.self_prsn_work_experience a 
        WHERE ((person_id = $pkID)) 
        ORDER BY a.job_end_date DESC, a.job_start_date DESC, 1 DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SkillNatureSelf($pkID) {
    $strSql = "SELECT skills_id mt, languages, hobbies, interests, 
       conduct, attitude, to_char(to_timestamp(valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"valid_start_date\", 
        to_char(to_timestamp(valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"valid_end_date\"
        FROM self.self_prsn_skills_nature a WHERE ((person_id = $pkID)) 
        ORDER BY a.valid_end_date DESC, a.valid_start_date DESC, 1 DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PrsExtrDataGrpCols($grpnm, $org_ID) {
    $strSql = "SELECT extra_data_cols_id, column_no, column_label, attchd_lov_name, 
       column_data_type, column_data_category, data_length, 
       CASE WHEN data_dsply_type='T' THEN 'Tabular' ELSE 'Detail' END, 
       org_id, no_cols_tblr_dsply, col_order, csv_tblr_col_nms,is_required 
        FROM prs.prsn_extra_data_cols 
        WHERE column_data_category= '" . loc_db_escape_string($grpnm) .
            "' and org_id = " . $org_ID . " and column_label !='' ORDER BY col_order, column_no, extra_data_cols_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllPrsExtrDataCols($org_ID) {
    $strSql = "SELECT extra_data_cols_id, column_no, column_label, attchd_lov_name, 
       column_data_type, column_data_category, data_length, 
       CASE WHEN data_dsply_type='T' THEN 'Tabular' ELSE 'Detail' END, 
       org_id, no_cols_tblr_dsply, col_order, csv_tblr_col_nms, is_required 
        FROM prs.prsn_extra_data_cols 
        WHERE org_id = " . $org_ID . " ORDER BY column_no";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PrsExtrDataGrpCols1($colNum, $org_ID) {
    $strSql = "SELECT extra_data_cols_id, column_no, column_label, attchd_lov_name, 
       column_data_type, column_data_category, data_length, 
       CASE WHEN data_dsply_type='T' THEN 'Tabular' ELSE 'Detail' END, 
       org_id, no_cols_tblr_dsply, col_order, csv_tblr_col_nms 
        FROM prs.prsn_extra_data_cols 
        WHERE column_no= '" . $colNum .
            "' and org_id = " . $org_ID . " ORDER BY col_order, column_no, extra_data_cols_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PrsExtrDataGrps($org_ID) {
    $strSql = "SELECT column_data_category, MIN(extra_data_cols_id) , MIN(col_order)  
        FROM prs.prsn_extra_data_cols 
        WHERE org_id =$org_ID and column_label !='' 
            GROUP BY column_data_category ORDER BY 3, 2, 1";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PrsExtrData($pkID, $colNum = "1") {
    $colNms = array("data_col1", "data_col2", "data_col3", "data_col4",
        "data_col5", "data_col6", "data_col7", "data_col8", "data_col9", "data_col10",
        "data_col11", "data_col12", "data_col13", "data_col14", "data_col15", "data_col16",
        "data_col17", "data_col18", "data_col19", "data_col20", "data_col21", "data_col22",
        "data_col23", "data_col24", "data_col25", "data_col26", "data_col27", "data_col28",
        "data_col29", "data_col30", "data_col31", "data_col32", "data_col33", "data_col34",
        "data_col35", "data_col36", "data_col37", "data_col38", "data_col39", "data_col40",
        "data_col41", "data_col42", "data_col43", "data_col44", "data_col45", "data_col46",
        "data_col47", "data_col48", "data_col49", "data_col50");
    $strSql = "SELECT " . $colNms[$colNum - 1] . ", extra_data_id 
  FROM prs.prsn_extra_data a WHERE ((person_id = $pkID))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_PrsExtrData_Self($pkID, $colNum = "1") {
    $colNms = array("data_col1", "data_col2", "data_col3", "data_col4",
        "data_col5", "data_col6", "data_col7", "data_col8", "data_col9", "data_col10",
        "data_col11", "data_col12", "data_col13", "data_col14", "data_col15", "data_col16",
        "data_col17", "data_col18", "data_col19", "data_col20", "data_col21", "data_col22",
        "data_col23", "data_col24", "data_col25", "data_col26", "data_col27", "data_col28",
        "data_col29", "data_col30", "data_col31", "data_col32", "data_col33", "data_col34",
        "data_col35", "data_col36", "data_col37", "data_col38", "data_col39", "data_col40",
        "data_col41", "data_col42", "data_col43", "data_col44", "data_col45", "data_col46",
        "data_col47", "data_col48", "data_col49", "data_col50");
    $strSql = "SELECT " . $colNms[$colNum - 1] . ", extra_data_id 
  FROM self.self_prsn_extra_data a WHERE ((person_id = $pkID))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_DivsGrps($pkID) {
    $strSql = "SELECT a.prsn_div_id mt, a.div_id mt, org.get_div_name(a.div_id) group_name, 
        to_char(to_timestamp(valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') start_date, 
to_char(to_timestamp(valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') end_date, 
COALESCE((select b.div_typ_id from org.org_divs_groups b where a.div_id = b.div_id),-1) mt,
gst.get_pssbl_val(COALESCE((select b.div_typ_id from org.org_divs_groups b where a.div_id = b.div_id),-1)) group_type
       FROM pasn.prsn_divs_groups a WHERE ((person_id = $pkID)) 
           ORDER BY a.valid_end_date DESC, a.valid_start_date DESC, 1 DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SitesLocs($pkID) {
    $strSql = "SELECT a.prsn_loc_id mt, a.location_id mt, 
(select REPLACE(b.location_code_name || '.' || b.site_desc, '.' || b.location_code_name,'') from org.org_sites_locations b where b.location_id = a.location_id) site_name,       
to_char(to_timestamp(valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') start_date, 
to_char(to_timestamp(valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') end_date,
gst.get_pssbl_val(COALESCE((select b.site_type_id from org.org_sites_locations b where a.location_id = b.location_id),-1)) site_type  
            FROM pasn.prsn_locations a 
            WHERE ((person_id = $pkID))
                ORDER BY a.valid_end_date DESC, a.valid_start_date DESC, 1 DESC";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Spvsrs($pkID) {
    $strSql = "SELECT row_id mt, supervisor_prsn_id mt, 
        prs.get_prsn_loc_id(supervisor_prsn_id) id_of_supervisor,
        prs.get_prsn_name(supervisor_prsn_id) name_of_supervisor,
        to_char(to_timestamp(valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') start_date, 
to_char(to_timestamp(valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') end_date
      FROM pasn.prsn_supervisors a WHERE ((person_id = $pkID))
                ORDER BY a.valid_end_date DESC, a.valid_start_date DESC, 1 DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Jobs($pkID) {
    $strSql = "SELECT a.row_id mt, a.job_id mt, 
        (select b.job_code_name from org.org_jobs b where b.job_id = a.job_id) job_name,
        to_char(to_timestamp(valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') start_date, 
to_char(to_timestamp(valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') end_date 
      FROM pasn.prsn_jobs a
       WHERE ((person_id = $pkID)) 
                ORDER BY a.valid_end_date DESC, a.valid_start_date DESC, 1 DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Grades($pkID) {
    $strSql = "SELECT a.row_id mt, a.grade_id mt, 
        (select b.grade_code_name from org.org_grades b where b.grade_id = a.grade_id) grade_name,
        to_char(to_timestamp(valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') start_date, 
to_char(to_timestamp(valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') end_date
     FROM pasn.prsn_grades a WHERE ((person_id = $pkID))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Pos($pkID) {
    $strSql = "SELECT a.row_id mt, a.position_id mt, 
        (select b.position_code_name from org.org_positions b where b.position_id = a.position_id) pos_name,
        to_char(to_timestamp(valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') start_date, 
        to_char(to_timestamp(valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') end_date,
        a.div_id, org.get_div_name(a.div_id)
      FROM pasn.prsn_positions a WHERE ((person_id = $pkID))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

/* function getAllwdExtInfosNVals($searchWord, $searchIn, $offset, $limit_size, &$brghtsqlStr, $tblID, $row_id_val, $valTbl, $Org_id) {
  $strSql = "";
  $whrCls = "";

  if ($searchIn == "Value") {
  $whrCls = " AND (tbl1.othr_inf ilike '" .
  loc_db_escape_string($searchWord) . "' or tbl1.othr_inf IS NULL) ";
  } else if ($searchIn == "Extra Info Label") {
  $whrCls = " AND (tbl1.other_info_label ilike '" .
  loc_db_escape_string($searchWord) . "' or tbl1.other_info_category ilike '" .
  loc_db_escape_string($searchWord) . "') ";
  }
  $strSql = "SELECT tbl1.* FROM (SELECT b.pssbl_value other_info_category,
  COALESCE((select c.other_info_label from " . $valTbl . " c " .
  "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))), b.pssbl_value) other_info_label,
  COALESCE((select c.other_info_value from " . $valTbl . " c " .
  "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))),'') othr_inf, " .
  "a.comb_info_id, a.table_id, COALESCE((select c.dflt_row_id from " . $valTbl . " c " .
  "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))),-1) othr_inf_row_id " .
  "FROM sec.sec_allwd_other_infos a " .
  "LEFT OUTER JOIN gst.gen_stp_lov_values b ON (a.other_info_id = b.pssbl_value_id) " .
  "WHERE((a.is_enabled = '1')  AND (a.table_id = " . $tblID . ") AND (b.allowed_org_ids like '%," . $Org_id .
  ",%') AND (((select c.other_info_value from " . $valTbl . " c " .
  "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))) ilike '" .
  loc_db_escape_string($searchWord) . "') OR ((select c.other_info_value from " . $valTbl . " c " .
  "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))) is null))) " .
  " UNION
  SELECT c.other_info_category, c.other_info_label, c.other_info_value othr_inf, 99999999 comb_info_id, -1 table_id, c.dflt_row_id from " . $valTbl .
  " c  WHERE c.tbl_othr_inf_combntn_id<=0 and c.row_pk_id_val = " . $row_id_val . ") tbl1 WHERE 1=1" . $whrCls .
  " ORDER BY tbl1.comb_info_id LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);

  $result = executeSQLNoParams($strSql);
  $brghtsqlStr = $strSql;
  return $result;
  } */

function getPrsnsInvolved($srchCrtr, $grpType, $grpNm, $grpID, $cstmrID, $cstmrSiteID) {
    global $orgID;
    $dateStr = getDB_Date_time();
    $extrWhr = "";
    if ($srchCrtr == "contains") {
        $srchCrtr = "%" . str_replace("'", "''", $grpNm) . "%";
    } else if ($srchCrtr == "is equal to") {
        $srchCrtr = str_replace("'", "''", $grpNm);
    } else {
        $srchCrtr = str_replace("'", "''", $grpNm) . "%";
    }
    if ($cstmrID > 0) {
        $extrWhr .= " and (Select distinct z.lnkd_firm_org_id From prs.prsn_names_nos z where z.person_id=a.person_id)=" . $cstmrID;
    }
    if ($cstmrSiteID > 0) {
        $extrWhr .= " and (Select distinct z.lnkd_firm_site_id From prs.prsn_names_nos z where z.person_id=a.person_id)=" . $cstmrSiteID;
    }

    $grpSQL = "";
    if ($grpType == "Divisions/Groups") {
        $grpSQL = "Select distinct a.person_id From pasn.prsn_divs_groups a Where ((a.div_id IN " .
                "(select z.div_id from org.org_divs_groups z where z.div_code_name ilike '" . $srchCrtr . "')) and (to_timestamp('" . $dateStr .
                "','YYYY-MM-DD HH24:MI:SS') between to_timestamp(a.valid_start_date|| ' 00:00:00','YYYY-MM-DD HH24:MI:SS') " .
                "AND to_timestamp(a.valid_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS'))" . $extrWhr . ") ORDER BY a.person_id";
    } else if ($grpType == "Grade") {
        $grpSQL = "Select distinct a.person_id From pasn.prsn_grades a Where ((a.grade_id IN " .
                "(select z.grade_id from org.org_grades z where z.grade_code_name ilike '" . $srchCrtr . "')) and (to_timestamp('" . $dateStr .
                "','YYYY-MM-DD HH24:MI:SS') between to_timestamp(a.valid_start_date|| ' 00:00:00','YYYY-MM-DD HH24:MI:SS') " .
                "AND to_timestamp(a.valid_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS'))" . $extrWhr . ") ORDER BY a.person_id";
    } else if ($grpType == "Job") {
        $grpSQL = "Select distinct a.person_id From pasn.prsn_jobs a Where ((a.job_id IN " .
                "(select z.job_id from org.org_jobs z where z.job_code_name ilike '" . $srchCrtr . "')) and (to_timestamp('" . $dateStr .
                "','YYYY-MM-DD HH24:MI:SS') between to_timestamp(a.valid_start_date|| ' 00:00:00','YYYY-MM-DD HH24:MI:SS') " .
                "AND to_timestamp(a.valid_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS'))" . $extrWhr . ") ORDER BY a.person_id";
    } else if ($grpType == "Position") {
        $grpSQL = "Select distinct a.person_id From pasn.prsn_positions a Where ((a.position_id IN " .
                "(select z.position_id from org.org_positions z where z.position_code_name ilike '" . $srchCrtr . "')) and (to_timestamp('" . $dateStr .
                "','YYYY-MM-DD HH24:MI:SS') between to_timestamp(a.valid_start_date|| ' 00:00:00','YYYY-MM-DD HH24:MI:SS') " .
                "AND to_timestamp(a.valid_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS'))" . $extrWhr . ") ORDER BY a.person_id";
    } else if ($grpType == "Site/Location") {
        $grpSQL = "Select distinct a.person_id From pasn.prsn_locations a Where ((a.location_id IN " .
                "(select z.location_id from org.org_sites_locations z where z.location_code_name ilike '" . $srchCrtr . "')) and (to_timestamp('" . $dateStr .
                "','YYYY-MM-DD HH24:MI:SS') between to_timestamp(a.valid_start_date|| ' 00:00:00','YYYY-MM-DD HH24:MI:SS') " .
                "AND to_timestamp(a.valid_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS'))" . $extrWhr . ") ORDER BY a.person_id";
    } else if ($grpType == "Person Type") {
        $grpSQL = "Select distinct a.person_id From pasn.prsn_prsntyps a, prs.prsn_names_nos b " .
                "Where ((a.person_id = b.person_id) and (b.org_id = " . $orgID . ") and (a.prsn_type ilike '" .
                $srchCrtr . "') and (to_timestamp('" . $dateStr .
                "','YYYY-MM-DD HH24:MI:SS') between to_timestamp(a.valid_start_date|| ' 00:00:00','YYYY-MM-DD HH24:MI:SS') " .
                "AND to_timestamp(a.valid_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS'))" . $extrWhr . ") ORDER BY a.person_id";
    } else if ($grpType == "Everyone") {
        $grpSQL = "Select distinct a.person_id From prs.prsn_names_nos a Where ((a.org_id = " . $orgID . ")" . $extrWhr . ") ORDER BY a.person_id";
    } else {
        $grpSQL = "Select distinct a.person_id From prs.prsn_names_nos a Where ((a.person_id = " . $grpID . ")" . $extrWhr . ") ORDER BY a.person_id";
    }
    $prsnIDs = array();
    $result = executeSQLNoParams($grpSQL);
    while ($row = loc_db_fetch_array($result)) {
        array_push($prsnIDs, $row[0]);
    }
    return $prsnIDs;
}

function getCstmrSpplrEmails($cstmrID) {
    $sqlStr = "select string_agg(a.email,',') from scm.scm_cstmr_suplr_sites a where a.cust_supplier_id = " .
            $cstmrID . " and a.email IS NOT NULL and a.email !=''";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getCstmrSpplrMobiles($cstmrID) {
    $sqlStr = "select string_agg(a.contact_nos,',') from scm.scm_cstmr_suplr_sites a where a.cust_supplier_id = " .
            $cstmrID . " and a.contact_nos IS NOT NULL and a.contact_nos !=''";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function insert_ChangeRequest($pkID) {
    global $usrID;
    $datestr = getDB_Date_time();
    $dsply = "";

    $insSQL = "insert into self.self_prsn_chng_rqst
        (rqst_date, person_id, rqst_status, created_by, creation_date, last_update_by, last_update_date)
VALUES('$datestr', $pkID, 'Requires Approval', $usrID, '$datestr', $usrID, '$datestr')";

    $affctd = execUpdtInsSQL($insSQL);

    return $affctd;
}

function createQckCstmrSpplr($ctmrNm) {
    global $usrID;
    global $orgID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO scm.scm_cstmr_suplr(
            cust_sup_name, created_by, creation_date, last_update_by, last_update_date, 
            cust_sup_desc, cust_sup_clssfctn, cust_or_sup, org_id, 
            dflt_pybl_accnt_id, dflt_rcvbl_accnt_id, lnkd_prsn_id, person_gender, 
            dob_estblshmnt, is_enabled, firm_brand_name, type_of_organisation, 
            company_reg_num, date_of_incorptn, type_of_incorporation, vat_number, 
            tin_number, ssnit_reg_number, no_of_emplyees, description_of_services, 
            list_of_services)" .
            "VALUES ('" . loc_db_escape_string($ctmrNm) .
            "'," . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr .
            "', '" . loc_db_escape_string($ctmrNm) .
            "', 'Organisation','Customer'," . $orgID . ",-1,-1,-1,'Not Applicable','1970-01-01','1',"
            . "'','','','','','','','',0,'','')";
    execUpdtInsSQL($insSQL);
}

function createQckCstmrSpplrSite($siteNm, $cstmrID, $ctmrNm) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO scm.scm_cstmr_suplr_sites(
            cust_supplier_id, contact_person_name, contact_nos, email, created_by, 
            creation_date, last_update_by, last_update_date, site_name, site_desc, 
            bank_name, bank_branch, bank_accnt_number, wth_tax_code_id, discount_code_id, 
            billing_address, ship_to_address, swift_code, 
            nationality, national_id_typ, id_number, date_issued, expiry_date, 
            other_info, is_enabled, iban_number, accnt_cur_id)" .
            "VALUES (" . $cstmrID . ", '" . loc_db_escape_string($ctmrNm) .
            "','',''," . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr .
            "', '" . loc_db_escape_string($siteNm) .
            "', '" . loc_db_escape_string($siteNm) .
            "','','','',-1,-1,'','','','','','','','','','1','',-1)";
    execUpdtInsSQL($insSQL);
}

function updateNtnlID($pkeyID, $country, $idtype, $idnum, $dteissued, $expirydte, $othrinfo) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE prs.prsn_national_ids
                    SET nationality='" . loc_db_escape_string($country) . "', 
                    id_number = '" . loc_db_escape_string($idnum) . "',
                    last_update_by = $usrID, 
                    last_update_date = '$dateStr', 
                    national_id_typ = '" . loc_db_escape_string($idtype) . "',
                    date_issued = '" . loc_db_escape_string($dteissued) . "', 
                    expiry_date = '" . loc_db_escape_string($expirydte) . "', 
                    other_info = '" . loc_db_escape_string($othrinfo) . "'
                    WHERE ntnlty_id = $pkeyID";
    return execUpdtInsSQL($updtSQL, "Update of National ID");
}

function updateNtnlIDSelf($pkeyID, $country, $idtype, $idnum, $dteissued, $expirydte, $othrinfo) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE self.self_prsn_national_ids
                    SET nationality='" . loc_db_escape_string($country) . "', 
                    id_number = '" . loc_db_escape_string($idnum) . "',
                    last_update_by = $usrID, 
                    last_update_date = '$dateStr', 
                    national_id_typ = '" . loc_db_escape_string($idtype) . "',
                    date_issued = '" . loc_db_escape_string($dteissued) . "', 
                    expiry_date = '" . loc_db_escape_string($expirydte) . "', 
                    other_info = '" . loc_db_escape_string($othrinfo) . "'
                    WHERE ntnlty_id = $pkeyID";
    return execUpdtInsSQL($updtSQL, "Self Update of National ID");
}

function createNtnlID($prsnID, $country, $idtype, $idnum, $dteissued, $expirydte, $othrinfo) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO prs.prsn_national_ids(
            person_id, nationality, id_number, created_by, creation_date, 
            last_update_by, last_update_date, national_id_typ, 
            date_issued, expiry_date, other_info) VALUES ($prsnID
            , '" . loc_db_escape_string($country) . "', '" . loc_db_escape_string($idnum) . "'
            , $usrID, '$dateStr', $usrID, '$dateStr', '" . loc_db_escape_string($idtype) . "'
            , '" . loc_db_escape_string($dteissued) . "', 
            '" . loc_db_escape_string($expirydte) . "', 
            '" . loc_db_escape_string($othrinfo) . "')";
    return execUpdtInsSQL($insSQL);
}

function createNtnlIDSelf($prsnID, $country, $idtype, $idnum, $dteissued, $expirydte, $othrinfo) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO self.self_prsn_national_ids(
            person_id, nationality, id_number, created_by, creation_date, 
            last_update_by, last_update_date, national_id_typ, 
            date_issued, expiry_date, other_info) VALUES ($prsnID
            , '" . loc_db_escape_string($country) . "', '" . loc_db_escape_string($idnum) . "'
            , $usrID, '$dateStr', $usrID, '$dateStr', '" . loc_db_escape_string($idtype) . "'
            , '" . loc_db_escape_string($dteissued) . "', 
            '" . loc_db_escape_string($expirydte) . "', 
            '" . loc_db_escape_string($othrinfo) . "')";
    return execUpdtInsSQL($insSQL);
}

function deleteNtnlID($pkeyID) {
    $insSQL = "DELETE FROM prs.prsn_national_ids WHERE ntnlty_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Remove a National ID");

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 National ID(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteNtnlIDSelf($pkeyID) {
    $insSQL = "DELETE FROM self.self_prsn_national_ids WHERE ntnlty_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Self Removal of National ID");

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 National ID(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createPrsnExtrData($prsnID, $data_col) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO prs.prsn_extra_data(
            person_id, data_col1, data_col2, data_col3, data_col4, 
            data_col5, data_col6, data_col7, data_col8, data_col9, data_col10, 
            data_col11, data_col12, data_col13, data_col14, data_col15, data_col16, 
            data_col17, data_col18, data_col19, data_col20, data_col21, data_col22, 
            data_col23, data_col24, data_col25, data_col26, data_col27, data_col28, 
            data_col29, data_col30, data_col31, data_col32, data_col33, data_col34, 
            data_col35, data_col36, data_col37, data_col38, data_col39, data_col40, 
            data_col41, data_col42, data_col43, data_col44, data_col45, data_col46, 
            data_col47, data_col48, data_col49, data_col50, created_by, creation_date, 
            last_update_by, last_update_date)  
            VALUES($prsnID, '" . loc_db_escape_string($data_col[1]) .
            "', '" . loc_db_escape_string($data_col[2]) . "', '" . loc_db_escape_string($data_col[3]) .
            "', '" . loc_db_escape_string($data_col[4]) . "', '" . loc_db_escape_string($data_col[5]) .
            "', '" . loc_db_escape_string($data_col[6]) . "', '" . loc_db_escape_string($data_col[7]) .
            "', '" . loc_db_escape_string($data_col[8]) . "', '" . loc_db_escape_string($data_col[9]) .
            "', '" . loc_db_escape_string($data_col[10]) . "', '" . loc_db_escape_string($data_col[11]) .
            "', '" . loc_db_escape_string($data_col[12]) . "', '" . loc_db_escape_string($data_col[13]) .
            "', '" . loc_db_escape_string($data_col[14]) . "', '" . loc_db_escape_string($data_col[15]) .
            "', '" . loc_db_escape_string($data_col[16]) . "', '" . loc_db_escape_string($data_col[17]) .
            "', '" . loc_db_escape_string($data_col[18]) . "', '" . loc_db_escape_string($data_col[19]) .
            "', '" . loc_db_escape_string($data_col[20]) . "', '" . loc_db_escape_string($data_col[21]) .
            "', '" . loc_db_escape_string($data_col[22]) . "', '" . loc_db_escape_string($data_col[23]) .
            "', '" . loc_db_escape_string($data_col[24]) . "', '" . loc_db_escape_string($data_col[25]) .
            "', '" . loc_db_escape_string($data_col[26]) . "', '" . loc_db_escape_string($data_col[27]) .
            "', '" . loc_db_escape_string($data_col[28]) . "', '" . loc_db_escape_string($data_col[29]) .
            "', '" . loc_db_escape_string($data_col[30]) . "', '" . loc_db_escape_string($data_col[31]) .
            "', '" . loc_db_escape_string($data_col[32]) . "', '" . loc_db_escape_string($data_col[33]) .
            "', '" . loc_db_escape_string($data_col[34]) . "', '" . loc_db_escape_string($data_col[35]) .
            "', '" . loc_db_escape_string($data_col[36]) . "', '" . loc_db_escape_string($data_col[37]) .
            "', '" . loc_db_escape_string($data_col[38]) . "', '" . loc_db_escape_string($data_col[39]) .
            "', '" . loc_db_escape_string($data_col[40]) . "', '" . loc_db_escape_string($data_col[41]) .
            "', '" . loc_db_escape_string($data_col[42]) . "', '" . loc_db_escape_string($data_col[43]) .
            "', '" . loc_db_escape_string($data_col[44]) . "', '" . loc_db_escape_string($data_col[45]) .
            "', '" . loc_db_escape_string($data_col[46]) . "', '" . loc_db_escape_string($data_col[47]) .
            "', '" . loc_db_escape_string($data_col[48]) . "', '" . loc_db_escape_string($data_col[49]) .
            "', '" . loc_db_escape_string($data_col[50]) . "', $usrID, '$dateStr', $usrID, '$dateStr')";
    return execUpdtInsSQL($insSQL);
}

function createPrsnExtrDataSelf($prsnID, $data_col) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO self.self_prsn_extra_data (
            person_id, data_col1, data_col2, data_col3, data_col4, 
            data_col5, data_col6, data_col7, data_col8, data_col9, data_col10, 
            data_col11, data_col12, data_col13, data_col14, data_col15, data_col16, 
            data_col17, data_col18, data_col19, data_col20, data_col21, data_col22, 
            data_col23, data_col24, data_col25, data_col26, data_col27, data_col28, 
            data_col29, data_col30, data_col31, data_col32, data_col33, data_col34, 
            data_col35, data_col36, data_col37, data_col38, data_col39, data_col40, 
            data_col41, data_col42, data_col43, data_col44, data_col45, data_col46, 
            data_col47, data_col48, data_col49, data_col50, created_by, creation_date, 
            last_update_by, last_update_date)  
            VALUES($prsnID, '" . loc_db_escape_string($data_col[1]) .
            "', '" . loc_db_escape_string($data_col[2]) . "', '" . loc_db_escape_string($data_col[3]) .
            "', '" . loc_db_escape_string($data_col[4]) . "', '" . loc_db_escape_string($data_col[5]) .
            "', '" . loc_db_escape_string($data_col[6]) . "', '" . loc_db_escape_string($data_col[7]) .
            "', '" . loc_db_escape_string($data_col[8]) . "', '" . loc_db_escape_string($data_col[9]) .
            "', '" . loc_db_escape_string($data_col[10]) . "', '" . loc_db_escape_string($data_col[11]) .
            "', '" . loc_db_escape_string($data_col[12]) . "', '" . loc_db_escape_string($data_col[13]) .
            "', '" . loc_db_escape_string($data_col[14]) . "', '" . loc_db_escape_string($data_col[15]) .
            "', '" . loc_db_escape_string($data_col[16]) . "', '" . loc_db_escape_string($data_col[17]) .
            "', '" . loc_db_escape_string($data_col[18]) . "', '" . loc_db_escape_string($data_col[19]) .
            "', '" . loc_db_escape_string($data_col[20]) . "', '" . loc_db_escape_string($data_col[21]) .
            "', '" . loc_db_escape_string($data_col[22]) . "', '" . loc_db_escape_string($data_col[23]) .
            "', '" . loc_db_escape_string($data_col[24]) . "', '" . loc_db_escape_string($data_col[25]) .
            "', '" . loc_db_escape_string($data_col[26]) . "', '" . loc_db_escape_string($data_col[27]) .
            "', '" . loc_db_escape_string($data_col[28]) . "', '" . loc_db_escape_string($data_col[29]) .
            "', '" . loc_db_escape_string($data_col[30]) . "', '" . loc_db_escape_string($data_col[31]) .
            "', '" . loc_db_escape_string($data_col[32]) . "', '" . loc_db_escape_string($data_col[33]) .
            "', '" . loc_db_escape_string($data_col[34]) . "', '" . loc_db_escape_string($data_col[35]) .
            "', '" . loc_db_escape_string($data_col[36]) . "', '" . loc_db_escape_string($data_col[37]) .
            "', '" . loc_db_escape_string($data_col[38]) . "', '" . loc_db_escape_string($data_col[39]) .
            "', '" . loc_db_escape_string($data_col[40]) . "', '" . loc_db_escape_string($data_col[41]) .
            "', '" . loc_db_escape_string($data_col[42]) . "', '" . loc_db_escape_string($data_col[43]) .
            "', '" . loc_db_escape_string($data_col[44]) . "', '" . loc_db_escape_string($data_col[45]) .
            "', '" . loc_db_escape_string($data_col[46]) . "', '" . loc_db_escape_string($data_col[47]) .
            "', '" . loc_db_escape_string($data_col[48]) . "', '" . loc_db_escape_string($data_col[49]) .
            "', '" . loc_db_escape_string($data_col[50]) . "', $usrID, '$dateStr', $usrID, '$dateStr')";
    return execUpdtInsSQL($insSQL);
}

function updatePrsnExtrData($prsnID, $data_col) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE prs.prsn_extra_data 
   SET data_col1='" . loc_db_escape_string($data_col[1]) .
            "', data_col2='" . loc_db_escape_string($data_col[2]) . "', data_col3='" . loc_db_escape_string($data_col[3]) .
            "', data_col4='" . loc_db_escape_string($data_col[4]) . "', data_col5='" . loc_db_escape_string($data_col[5]) .
            "', data_col6='" . loc_db_escape_string($data_col[6]) . "', data_col7='" . loc_db_escape_string($data_col[7]) .
            "', data_col8='" . loc_db_escape_string($data_col[8]) . "', data_col9='" . loc_db_escape_string($data_col[9]) .
            "', data_col10='" . loc_db_escape_string($data_col[10]) . "', data_col11='" . loc_db_escape_string($data_col[11]) .
            "', data_col12='" . loc_db_escape_string($data_col[12]) . "', data_col13='" . loc_db_escape_string($data_col[13]) .
            "', data_col14='" . loc_db_escape_string($data_col[14]) . "', data_col15='" . loc_db_escape_string($data_col[15]) .
            "', data_col16='" . loc_db_escape_string($data_col[16]) . "', data_col17='" . loc_db_escape_string($data_col[17]) .
            "', data_col18='" . loc_db_escape_string($data_col[18]) . "', data_col19='" . loc_db_escape_string($data_col[19]) .
            "', data_col20='" . loc_db_escape_string($data_col[20]) . "', data_col21='" . loc_db_escape_string($data_col[21]) .
            "', data_col22='" . loc_db_escape_string($data_col[22]) . "', data_col23='" . loc_db_escape_string($data_col[23]) .
            "', data_col24='" . loc_db_escape_string($data_col[24]) . "', data_col25='" . loc_db_escape_string($data_col[25]) .
            "', data_col26='" . loc_db_escape_string($data_col[26]) . "', data_col27='" . loc_db_escape_string($data_col[27]) .
            "', data_col28='" . loc_db_escape_string($data_col[28]) . "', data_col29='" . loc_db_escape_string($data_col[29]) .
            "', data_col30='" . loc_db_escape_string($data_col[30]) . "', data_col31='" . loc_db_escape_string($data_col[31]) .
            "', data_col32='" . loc_db_escape_string($data_col[32]) . "', data_col33='" . loc_db_escape_string($data_col[33]) .
            "', data_col34='" . loc_db_escape_string($data_col[34]) . "', data_col35='" . loc_db_escape_string($data_col[35]) .
            "', data_col36='" . loc_db_escape_string($data_col[36]) . "', data_col37='" . loc_db_escape_string($data_col[37]) .
            "', data_col38='" . loc_db_escape_string($data_col[38]) . "', data_col39='" . loc_db_escape_string($data_col[39]) .
            "', data_col40='" . loc_db_escape_string($data_col[40]) . "', data_col41='" . loc_db_escape_string($data_col[41]) .
            "', data_col42='" . loc_db_escape_string($data_col[42]) . "', data_col43='" . loc_db_escape_string($data_col[43]) .
            "', data_col44='" . loc_db_escape_string($data_col[44]) . "', data_col45='" . loc_db_escape_string($data_col[45]) .
            "', data_col46='" . loc_db_escape_string($data_col[46]) . "', data_col47='" . loc_db_escape_string($data_col[47]) .
            "', data_col48='" . loc_db_escape_string($data_col[48]) . "', data_col49='" . loc_db_escape_string($data_col[49]) .
            "', data_col50='" . loc_db_escape_string($data_col[50]) . "', last_update_by=$usrID,  
        last_update_date='$dateStr' WHERE person_id=$prsnID";
    //echo $updtSQL;
    return execUpdtInsSQL($updtSQL, "Extra Person Data Update");
}

function updatePrsnExtrDataSelf($prsnID, $data_col) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE self.self_prsn_extra_data 
   SET data_col1='" . loc_db_escape_string($data_col[1]) .
            "', data_col2='" . loc_db_escape_string($data_col[2]) . "', data_col3='" . loc_db_escape_string($data_col[3]) .
            "', data_col4='" . loc_db_escape_string($data_col[4]) . "', data_col5='" . loc_db_escape_string($data_col[5]) .
            "', data_col6='" . loc_db_escape_string($data_col[6]) . "', data_col7='" . loc_db_escape_string($data_col[7]) .
            "', data_col8='" . loc_db_escape_string($data_col[8]) . "', data_col9='" . loc_db_escape_string($data_col[9]) .
            "', data_col10='" . loc_db_escape_string($data_col[10]) . "', data_col11='" . loc_db_escape_string($data_col[11]) .
            "', data_col12='" . loc_db_escape_string($data_col[12]) . "', data_col13='" . loc_db_escape_string($data_col[13]) .
            "', data_col14='" . loc_db_escape_string($data_col[14]) . "', data_col15='" . loc_db_escape_string($data_col[15]) .
            "', data_col16='" . loc_db_escape_string($data_col[16]) . "', data_col17='" . loc_db_escape_string($data_col[17]) .
            "', data_col18='" . loc_db_escape_string($data_col[18]) . "', data_col19='" . loc_db_escape_string($data_col[19]) .
            "', data_col20='" . loc_db_escape_string($data_col[20]) . "', data_col21='" . loc_db_escape_string($data_col[21]) .
            "', data_col22='" . loc_db_escape_string($data_col[22]) . "', data_col23='" . loc_db_escape_string($data_col[23]) .
            "', data_col24='" . loc_db_escape_string($data_col[24]) . "', data_col25='" . loc_db_escape_string($data_col[25]) .
            "', data_col26='" . loc_db_escape_string($data_col[26]) . "', data_col27='" . loc_db_escape_string($data_col[27]) .
            "', data_col28='" . loc_db_escape_string($data_col[28]) . "', data_col29='" . loc_db_escape_string($data_col[29]) .
            "', data_col30='" . loc_db_escape_string($data_col[30]) . "', data_col31='" . loc_db_escape_string($data_col[31]) .
            "', data_col32='" . loc_db_escape_string($data_col[32]) . "', data_col33='" . loc_db_escape_string($data_col[33]) .
            "', data_col34='" . loc_db_escape_string($data_col[34]) . "', data_col35='" . loc_db_escape_string($data_col[35]) .
            "', data_col36='" . loc_db_escape_string($data_col[36]) . "', data_col37='" . loc_db_escape_string($data_col[37]) .
            "', data_col38='" . loc_db_escape_string($data_col[38]) . "', data_col39='" . loc_db_escape_string($data_col[39]) .
            "', data_col40='" . loc_db_escape_string($data_col[40]) . "', data_col41='" . loc_db_escape_string($data_col[41]) .
            "', data_col42='" . loc_db_escape_string($data_col[42]) . "', data_col43='" . loc_db_escape_string($data_col[43]) .
            "', data_col44='" . loc_db_escape_string($data_col[44]) . "', data_col45='" . loc_db_escape_string($data_col[45]) .
            "', data_col46='" . loc_db_escape_string($data_col[46]) . "', data_col47='" . loc_db_escape_string($data_col[47]) .
            "', data_col48='" . loc_db_escape_string($data_col[48]) . "', data_col49='" . loc_db_escape_string($data_col[49]) .
            "', data_col50='" . loc_db_escape_string($data_col[50]) . "', last_update_by=$usrID,  
        last_update_date='$dateStr' WHERE person_id=$prsnID";
    return execUpdtInsSQL($updtSQL, "Self Extra Person Data Update");
}

function getPDivGrpID($prsnID, $divGrpID) {
    $sqlStr = "SELECT prsn_div_id from pasn.prsn_divs_groups where (div_id = " . $divGrpID . " AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createPDivGrp($prsnID, $divGrpID, $pDivGrpStartDate, $pDivGrpEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO pasn.prsn_divs_groups(person_id, div_id, valid_start_date, valid_end_date, created_by, creation_date,
                                  last_update_by, last_update_date)
                                            VALUES (" . $prsnID . ","
            . $divGrpID . ",'"
            . cnvrtDMYToYMD($pDivGrpStartDate) . "','"
            . cnvrtDMYToYMD($pDivGrpEndDate) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function updatePDivGrp($pkeyID, $divGrpID, $pDivGrpStartDate, $pDivGrpEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE pasn.prsn_divs_groups
        SET div_id=" . $divGrpID .
            ", valid_start_date='" . cnvrtDMYToYMD($pDivGrpStartDate) .
            "', valid_end_date='" . cnvrtDMYToYMD($pDivGrpEndDate) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "' WHERE prsn_div_id = " . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Update of Division/Group");
}

function deletePDivGrp($pkeyID) {
    $insSQL = "DELETE FROM pasn.prsn_divs_groups WHERE prsn_div_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Remove Person's Division/Group");

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Division/Group(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getPSiteLocID($prsnID, $siteLocID) {
    $sqlStr = "SELECT prsn_loc_id from pasn.prsn_locations where (location_id = " . $siteLocID . " AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createPSiteLoc($prsnID, $siteLocID, $pSiteLocStartDate, $pSiteLocEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO pasn.prsn_locations(person_id, location_id, valid_start_date, valid_end_date, created_by, creation_date,
                                  last_update_by, last_update_date)
                                            VALUES (" . $prsnID . ","
            . $siteLocID . ",'"
            . cnvrtDMYToYMD($pSiteLocStartDate) . "','"
            . cnvrtDMYToYMD($pSiteLocEndDate) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function updatePSiteLoc($pkeyID, $siteLocID, $pSiteLocStartDate, $pSiteLocEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE pasn.prsn_locations
        SET location_id=" . $siteLocID .
            ", valid_start_date='" . cnvrtDMYToYMD($pSiteLocStartDate) .
            "', valid_end_date='" . cnvrtDMYToYMD($pSiteLocEndDate) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "' WHERE prsn_loc_id = " . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Update of Site/Location");
}

function deletePSiteLoc($pkeyID) {
    $insSQL = "DELETE FROM pasn.prsn_locations WHERE prsn_loc_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Remove Person's Site/Location");

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Site/Location(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getPGradeID($prsnID, $gradeID) {
    $sqlStr = "SELECT row_id from pasn.prsn_grades where (grade_id = " . $gradeID . " AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createPGrade($prsnID, $gradeID, $pGradeStartDate, $pGradeEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO pasn.prsn_grades(person_id, grade_id, valid_start_date, valid_end_date, created_by, creation_date,
                                  last_update_by, last_update_date)
                                            VALUES (" . $prsnID . ","
            . $gradeID . ",'"
            . cnvrtDMYToYMD($pGradeStartDate) . "','"
            . cnvrtDMYToYMD($pGradeEndDate) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function updatePGrade($pkeyID, $gradeID, $pGradeStartDate, $pGradeEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE pasn.prsn_grades
        SET grade_id=" . $gradeID .
            ", valid_start_date='" . cnvrtDMYToYMD($pGradeStartDate) .
            "', valid_end_date='" . cnvrtDMYToYMD($pGradeEndDate) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "' WHERE row_id = " . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Update of Grade");
}

function deletePGrade($pkeyID) {
    $insSQL = "DELETE FROM pasn.prsn_grades WHERE row_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Remove Person's Grade");

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Grade(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getPSuprvsrID($prsnID, $suprvsrID) {
    $sqlStr = "SELECT row_id from pasn.prsn_supervisors where (supervisor_prsn_id = " . $suprvsrID . " AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createPSuprvsr($prsnID, $suprvsrID, $pSuprvsrStartDate, $pSuprvsrEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO pasn.prsn_supervisors(person_id, supervisor_prsn_id, valid_start_date, valid_end_date, created_by, creation_date,
                                  last_update_by, last_update_date)
                                            VALUES (" . $prsnID . ","
            . $suprvsrID . ",'"
            . cnvrtDMYToYMD($pSuprvsrStartDate) . "','"
            . cnvrtDMYToYMD($pSuprvsrEndDate) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function updatePSuprvsr($pkeyID, $suprvsrID, $pSuprvsrStartDate, $pSuprvsrEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE pasn.prsn_supervisors
        SET supervisor_prsn_id=" . $suprvsrID .
            ", valid_start_date='" . cnvrtDMYToYMD($pSuprvsrStartDate) .
            "', valid_end_date='" . cnvrtDMYToYMD($pSuprvsrEndDate) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "' WHERE row_id = " . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Update of Supervisor");
}

function deletePSuprvsr($pkeyID) {
    $insSQL = "DELETE FROM pasn.prsn_supervisors WHERE row_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Remove Person's Supervisor");

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Supervisor(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getPJobID($prsnID, $jobID) {
    $sqlStr = "SELECT row_id from pasn.prsn_jobs where (job_id = " . $jobID . " AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createPJob($prsnID, $jobID, $pJobStartDate, $pJobEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO pasn.prsn_jobs(person_id, job_id, valid_start_date, valid_end_date, created_by, creation_date,
                                  last_update_by, last_update_date)
                                            VALUES (" . $prsnID . ","
            . $jobID . ",'"
            . cnvrtDMYToYMD($pJobStartDate) . "','"
            . cnvrtDMYToYMD($pJobEndDate) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function updatePJob($pkeyID, $jobID, $pJobStartDate, $pJobEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE pasn.prsn_jobs
        SET job_id=" . $jobID .
            ", valid_start_date='" . cnvrtDMYToYMD($pJobStartDate) .
            "', valid_end_date='" . cnvrtDMYToYMD($pJobEndDate) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "' WHERE row_id = " . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Update of Job");
}

function deletePJob($pkeyID) {
    $insSQL = "DELETE FROM pasn.prsn_jobs WHERE row_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Remove Person's Job");

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Job(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getPPositionID($prsnID, $positionID, $positionDivID) {
    $sqlStr = "SELECT row_id from pasn.prsn_positions where (position_id = " . $positionID . " AND person_id = $prsnID AND div_id=" . $positionDivID . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createPPosition($prsnID, $positionID, $pPositionStartDate, $pPositionEndDate, $div_id) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO pasn.prsn_positions(person_id, position_id, valid_start_date, valid_end_date, created_by, creation_date,
                                  last_update_by, last_update_date, div_id)
                                            VALUES (" . $prsnID . ","
            . $positionID . ",'"
            . cnvrtDMYToYMD($pPositionStartDate) . "','"
            . cnvrtDMYToYMD($pPositionEndDate) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "'," . $div_id . ")";
    return execUpdtInsSQL($insSQL);
}

function updatePPosition($pkeyID, $positionID, $pPositionStartDate, $pPositionEndDate, $div_id) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE pasn.prsn_positions
        SET position_id=" . $positionID .
            ", valid_start_date='" . cnvrtDMYToYMD($pPositionStartDate) .
            "', valid_end_date='" . cnvrtDMYToYMD($pPositionEndDate) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "', div_id=" . $div_id . " WHERE row_id = " . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Update of Position");
}

function deletePPosition($pkeyID) {
    $insSQL = "DELETE FROM pasn.prsn_positions WHERE row_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Remove Person's Position");

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Position(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getEducSelfID($prsnID, $crseNm, $schoolNm) {
    $sqlStr = "SELECT educ_id from self.self_prsn_education where (lower(course_name) = '" .
            loc_db_escape_string(strtolower($crseNm)) . "' AND lower(school_institution) = '" .
            loc_db_escape_string(strtolower($schoolNm)) . "' AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getEducID($prsnID, $crseNm, $schoolNm) {
    $sqlStr = "SELECT educ_id from prs.prsn_education where (lower(course_name) = '" .
            loc_db_escape_string(strtolower($crseNm)) . "' AND lower(school_institution) = '" .
            loc_db_escape_string(strtolower($schoolNm)) . "' AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function updateEduc($pkeyID, $myCourseName, $mySchoolInstitution, $mySchoolLocation, $myCertObtained, $myCourseStartDate, $myCourseEndDate, $myDateCertAwarded, $myCertTyp) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE prs.prsn_education 
        SET course_name='" . loc_db_escape_string($myCourseName) .
            "', school_institution='" . loc_db_escape_string($mySchoolInstitution) .
            "', school_location='" . loc_db_escape_string($mySchoolLocation) .
            "', cert_obtained='" . loc_db_escape_string($myCertObtained) .
            "', course_start_date='" . cnvrtDMYToYMD($myCourseStartDate) .
            "', course_end_date='" . cnvrtDMYToYMD($myCourseEndDate) .
            "', date_cert_awarded='" . loc_db_escape_string($myDateCertAwarded) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "', cert_type='" . loc_db_escape_string($myCertTyp) .
            "' WHERE educ_id = " . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Update of Educ Bkgrnd");
}

function updateEducSelf($pkeyID, $myCourseName, $mySchoolInstitution, $mySchoolLocation, $myCertObtained, $myCourseStartDate, $myCourseEndDate, $myDateCertAwarded, $myCertTyp) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE self.self_prsn_education 
        SET course_name='" . loc_db_escape_string($myCourseName) .
            "', school_institution='" . loc_db_escape_string($mySchoolInstitution) .
            "', school_location='" . loc_db_escape_string($mySchoolLocation) .
            "', cert_obtained='" . loc_db_escape_string($myCertObtained) .
            "', course_start_date='" . cnvrtDMYToYMD($myCourseStartDate) .
            "', course_end_date='" . cnvrtDMYToYMD($myCourseEndDate) .
            "', date_cert_awarded='" . loc_db_escape_string($myDateCertAwarded) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "', cert_type='" . loc_db_escape_string($myCertTyp) .
            "' WHERE educ_id = " . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Self-Update of Educ Bkgrnd");
}

function createEduc($prsnID, $myCourseName, $mySchoolInstitution, $mySchoolLocation, $myCertObtained, $myCourseStartDate, $myCourseEndDate, $myDateCertAwarded, $myCertTyp) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO prs.prsn_education(
                                            person_id, course_name, school_institution, school_location, 
                                            cert_obtained, course_start_date, course_end_date, date_cert_awarded,
                                            created_by, creation_date, last_update_by, last_update_date, 
                                            cert_type)
                                            VALUES (" . $prsnID . ",'"
            . loc_db_escape_string($myCourseName) . "','"
            . loc_db_escape_string($mySchoolInstitution) . "','"
            . loc_db_escape_string($mySchoolLocation) . "','"
            . loc_db_escape_string($myCertObtained) . "','"
            . cnvrtDMYToYMD($myCourseStartDate) . "','"
            . cnvrtDMYToYMD($myCourseEndDate) . "','"
            . loc_db_escape_string($myDateCertAwarded) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "','"
            . loc_db_escape_string($myCertTyp) . "')";
    return execUpdtInsSQL($insSQL);
}

function createEducSelf($prsnID, $myCourseName, $mySchoolInstitution, $mySchoolLocation, $myCertObtained, $myCourseStartDate, $myCourseEndDate, $myDateCertAwarded, $myCertTyp) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO self.self_prsn_education(
                                            person_id, course_name, school_institution, school_location, 
                                            cert_obtained, course_start_date, course_end_date, date_cert_awarded,
                                            created_by, creation_date, last_update_by, last_update_date, 
                                            cert_type)
                                            VALUES (" . $prsnID . ",'"
            . loc_db_escape_string($myCourseName) . "','"
            . loc_db_escape_string($mySchoolInstitution) . "','"
            . loc_db_escape_string($mySchoolLocation) . "','"
            . loc_db_escape_string($myCertObtained) . "','"
            . cnvrtDMYToYMD($myCourseStartDate) . "','"
            . cnvrtDMYToYMD($myCourseEndDate) . "','"
            . loc_db_escape_string($myDateCertAwarded) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "','"
            . loc_db_escape_string($myCertTyp) . "')";
    return execUpdtInsSQL($insSQL);
}

function deleteEduc($pkeyID) {
    $insSQL = "DELETE FROM prs.prsn_education WHERE educ_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Remove an Educ Bkgrnd");

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Educ Bkgrnd(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteEducSelf($pkeyID) {
    $insSQL = "DELETE FROM self.self_prsn_education WHERE educ_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Self Removal of Educ Bkgrnd");

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Educ Bkgrnd(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getWorkSelfID($prsnID, $jobNm, $instNm) {
    $sqlStr = "SELECT wrk_exprnc_id from self.self_prsn_work_experience where (lower(job_name_title) = '" .
            loc_db_escape_string(strtolower($jobNm)) . "' AND lower(institution_name) = '" .
            loc_db_escape_string(strtolower($instNm)) . "' AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getWorkID($prsnID, $jobNm, $instNm) {
    $sqlStr = "SELECT wrk_exprnc_id from prs.prsn_work_experience where (lower(job_name_title) = '" .
            loc_db_escape_string(strtolower($jobNm)) . "' AND lower(institution_name) = '" .
            loc_db_escape_string(strtolower($instNm)) . "' AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function updateWork($pkeyID, $myJobNameTitle, $myInstitutionName, $myJobLocation, $myJobStartDate, $myJobEndDate, $myJobDescription, $myFeatsAchvments) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE prs.prsn_work_experience "
            . "SET job_name_title='" . loc_db_escape_string($myJobNameTitle) .
            "', institution_name='" . loc_db_escape_string($myInstitutionName) .
            "', job_location='" . loc_db_escape_string($myJobLocation) .
            "', job_start_date='" . cnvrtDMYToYMD($myJobStartDate) .
            "', job_end_date='" . cnvrtDMYToYMD($myJobEndDate) .
            "', job_description='" . loc_db_escape_string($myJobDescription) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "', feats_achvments='" . loc_db_escape_string($myFeatsAchvments) .
            "' WHERE wrk_exprnc_id = " . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Update of Work Bkgrnd");
}

function updateWorkSelf($pkeyID, $myJobNameTitle, $myInstitutionName, $myJobLocation, $myJobStartDate, $myJobEndDate, $myJobDescription, $myFeatsAchvments) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE self.self_prsn_work_experience "
            . "SET job_name_title='" . loc_db_escape_string($myJobNameTitle) .
            "', institution_name='" . loc_db_escape_string($myInstitutionName) .
            "', job_location='" . loc_db_escape_string($myJobLocation) .
            "', job_start_date='" . cnvrtDMYToYMD($myJobStartDate) .
            "', job_end_date='" . cnvrtDMYToYMD($myJobEndDate) .
            "', job_description='" . loc_db_escape_string($myJobDescription) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "', feats_achvments='" . loc_db_escape_string($myFeatsAchvments) .
            "' WHERE wrk_exprnc_id = " . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Self-Update of Work Bkgrnd");
}

function createWork($prsnID, $myJobNameTitle, $myInstitutionName, $myJobLocation, $myJobStartDate, $myJobEndDate, $myJobDescription, $myFeatsAchvments) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO prs.prsn_work_experience(
                                                    person_id, job_name_title, institution_name, job_location, 
                                                    job_start_date, job_end_date, job_description, feats_achvments,  
                                                    created_by, creation_date, last_update_by, last_update_date)
                                            VALUES (" . $prsnID . ",'"
            . loc_db_escape_string($myJobNameTitle) . "','"
            . loc_db_escape_string($myInstitutionName) . "','"
            . loc_db_escape_string($myJobLocation) . "','"
            . cnvrtDMYToYMD($myJobStartDate) . "','"
            . cnvrtDMYToYMD($myJobEndDate) . "','"
            . loc_db_escape_string($myJobDescription) . "','"
            . loc_db_escape_string($myFeatsAchvments) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function createWorkSelf($prsnID, $myJobNameTitle, $myInstitutionName, $myJobLocation, $myJobStartDate, $myJobEndDate, $myJobDescription, $myFeatsAchvments) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO self.self_prsn_work_experience(
                                        person_id, job_name_title, institution_name, job_location, 
                                        job_start_date, job_end_date, job_description, feats_achvments,  
                                        created_by, creation_date, last_update_by, last_update_date)
                                            VALUES (" . $prsnID . ",'"
            . loc_db_escape_string($myJobNameTitle) . "','"
            . loc_db_escape_string($myInstitutionName) . "','"
            . loc_db_escape_string($myJobLocation) . "','"
            . cnvrtDMYToYMD($myJobStartDate) . "','"
            . cnvrtDMYToYMD($myJobEndDate) . "','"
            . loc_db_escape_string($myJobDescription) . "','"
            . loc_db_escape_string($myFeatsAchvments) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function deleteWork($pkeyID) {
    $insSQL = "DELETE FROM prs.prsn_work_experience WHERE wrk_exprnc_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Remove an Work Bkgrnd");

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Work Bkgrnd(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteWorkSelf($pkeyID) {
    $insSQL = "DELETE FROM self.self_prsn_work_experience WHERE wrk_exprnc_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Self Removal of Work Bkgrnd");

    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Work Bkgrnd(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getSkillsSelfID($prsnID, $vldStrtDate, $vldEndDate) {
    $sqlStr = "SELECT skills_id from self.self_prsn_skills_nature where "
            . "((to_timestamp('"
            . loc_db_escape_string($vldStrtDate) . "','DD-Mon-YYYY') between to_timestamp(valid_start_date,'YYYY-MM-DD') and "
            . "to_timestamp(valid_end_date,'YYYY-MM-DD')) OR (to_timestamp('"
            . loc_db_escape_string($vldEndDate) . "','DD-Mon-YYYY') between to_timestamp(valid_start_date,'YYYY-MM-DD') and "
            . "to_timestamp(valid_end_date,'YYYY-MM-DD')) AND person_id = $prsnID)";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getSkillsID($prsnID, $vldStrtDate, $vldEndDate) {
    $sqlStr = "SELECT skills_id from prs.prsn_skills_nature where "
            . "((to_timestamp('"
            . loc_db_escape_string($vldStrtDate) . "','DD-Mon-YYYY') between to_timestamp(valid_start_date,'YYYY-MM-DD') and "
            . "to_timestamp(valid_end_date,'YYYY-MM-DD')) OR (to_timestamp('"
            . loc_db_escape_string($vldEndDate) . "','DD-Mon-YYYY') between to_timestamp(valid_start_date,'YYYY-MM-DD') and "
            . "to_timestamp(valid_end_date,'YYYY-MM-DD'))) AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function updateSkills($pkeyID, $languages, $hobbies, $interests, $conduct, $attitude, $vldStrtDate, $vldEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE prs.prsn_skills_nature 
   SET languages='" . loc_db_escape_string($languages) .
            "', hobbies='" . loc_db_escape_string($hobbies) .
            "', interests='" . loc_db_escape_string($interests) .
            "', conduct='" . loc_db_escape_string($conduct) .
            "', attitude='" . loc_db_escape_string($attitude) .
            "', valid_start_date='" . cnvrtDMYToYMD($vldStrtDate) .
            "', valid_end_date='" . cnvrtDMYToYMD($vldEndDate) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "' WHERE skills_id=" . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Update of Skill");
}

function updateSkillsSelf($pkeyID, $languages, $hobbies, $interests, $conduct, $attitude, $vldStrtDate, $vldEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE self.self_prsn_skills_nature
   SET languages='" . loc_db_escape_string($languages) .
            "', hobbies='" . loc_db_escape_string($hobbies) .
            "', interests='" . loc_db_escape_string($interests) .
            "', conduct='" . loc_db_escape_string($conduct) .
            "', attitude='" . loc_db_escape_string($attitude) .
            "', valid_start_date='" . cnvrtDMYToYMD($vldStrtDate) .
            "', valid_end_date='" . cnvrtDMYToYMD($vldEndDate) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "' WHERE skills_id=" . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Self-Update of Skill");
}

function createSkills($prsnID, $languages, $hobbies, $interests, $conduct, $attitude, $vldStrtDate, $vldEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO prs.prsn_skills_nature(
            person_id, languages, hobbies, interests, conduct, attitude, 
            valid_start_date, valid_end_date, created_by, creation_date, 
            last_update_by, last_update_date)
             VALUES (" . $prsnID . ",'"
            . loc_db_escape_string($languages) . "','"
            . loc_db_escape_string($hobbies) . "','"
            . loc_db_escape_string($interests) . "','"
            . loc_db_escape_string($conduct) . "','"
            . loc_db_escape_string($attitude) . "','"
            . cnvrtDMYToYMD($vldStrtDate) . "','"
            . cnvrtDMYToYMD($vldEndDate) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function createSkillsSelf($prsnID, $languages, $hobbies, $interests, $conduct, $attitude, $vldStrtDate, $vldEndDate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO self.self_prsn_skills_nature(
            person_id, languages, hobbies, interests, conduct, attitude, 
            valid_start_date, valid_end_date, created_by, creation_date, 
            last_update_by, last_update_date)
             VALUES (" . $prsnID . ",'"
            . loc_db_escape_string($languages) . "','"
            . loc_db_escape_string($hobbies) . "','"
            . loc_db_escape_string($interests) . "','"
            . loc_db_escape_string($conduct) . "','"
            . loc_db_escape_string($attitude) . "','"
            . cnvrtDMYToYMD($vldStrtDate) . "','"
            . cnvrtDMYToYMD($vldEndDate) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function deleteSkills($pkeyID) {
    $insSQL = "DELETE FROM prs.prsn_skills_nature WHERE skills_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Remove a Skill");
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Skill(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteSkillsSelf($pkeyID) {
    $insSQL = "DELETE FROM self.self_prsn_skills_nature WHERE skills_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Self Removal of skills_id");
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Skill(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getAttachmentDocs() {
    global $prsnid;

    $sqlStr = "SELECT attchmnt_id, file_name, attchmnt_desc
  FROM self.self_prsn_doc_attchmnts WHERE 1=1 AND file_name != '' AND person_id = " . $prsnid;

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getApprovedAttachmentDocs() {
    global $prsnid;

    $sqlStr = "SELECT attchmnt_id, file_name, attchmnt_desc
  FROM prs.prsn_doc_attchmnts WHERE 1=1 AND file_name != '' AND person_id = " . $prsnid;

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_Attachments($searchWord, $offset, $limit_size, $hdrID, &$attchSQL) {
    $strSql = "SELECT a.attchmnt_id, a.person_id, a.attchmnt_desc, a.file_name " .
            "FROM prs.prsn_doc_attchmnts a " .
            "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
            "' and a.person_id = " . $hdrID . ") ORDER BY a.attchmnt_id LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    $attchSQL = $strSql;
    return $result;
}

function get_Total_Attachments($searchWord, $hdrID) {
    $strSql = "SELECT count(1) " .
            "FROM prs.prsn_doc_attchmnts a " .
            "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
            "' and a.person_id = " . $hdrID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AttachmentsSelf($searchWord, $offset, $limit_size, $hdrID, &$attchSQL) {
    $strSql = "SELECT a.attchmnt_id, a.person_id, a.attchmnt_desc, a.file_name " .
            "FROM self.self_prsn_doc_attchmnts a " .
            "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
            "' and a.person_id = " . $hdrID . ") ORDER BY a.attchmnt_id LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    $attchSQL = $strSql;
    return $result;
}

function get_Total_AttachmentsSelf($searchWord, $hdrID) {
    $strSql = "SELECT count(1) " .
            "FROM self.self_prsn_doc_attchmnts a " .
            "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
            "' and a.person_id = " . $hdrID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getNewPrsnDocIDSelf() {
    $strSql = "select nextval('self.self_prsn_doc_attchmnts_attchmnt_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createPrsnDocSelf($attchmnt_id, $person_id, $attchmnt_desc, $file_name) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO self.self_prsn_doc_attchmnts(
            attchmnt_id, person_id, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date)
             VALUES (" . $attchmnt_id . ", " . $person_id . ", '"
            . loc_db_escape_string($attchmnt_desc) . "', '"
            . loc_db_escape_string($file_name) . "', "
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function updatePrsnDocFlNmSelf($attchmnt_id, $file_name) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE self.self_prsn_doc_attchmnts SET file_name='"
            . loc_db_escape_string($file_name) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
    return execUpdtInsSQL($insSQL);
}

function updatePrsnDocFlNm($attchmnt_id, $file_name) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE prs.prsn_doc_attchmnts SET file_name='"
            . loc_db_escape_string($file_name) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
    return execUpdtInsSQL($insSQL);
}

function getNewPrsnDocID() {
    $strSql = "select nextval('prs.prsn_doc_attchmnts_attchmnt_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createPrsnDoc($attchmnt_id, $person_id, $attchmnt_desc, $file_name) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO prs.prsn_doc_attchmnts(
            attchmnt_id, person_id, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date)
             VALUES (" . $attchmnt_id . ", " . $person_id . ",'"
            . loc_db_escape_string($attchmnt_desc) . "','"
            . loc_db_escape_string($file_name) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function deletePrsnDoc($pkeyID) {
    $insSQL = "DELETE FROM prs.prsn_doc_attchmnts WHERE attchmnt_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Remove an Attached Document");
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Attached Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deletePrsnDocSelf($pkeyID) {
    $insSQL = "DELETE FROM self.self_prsn_doc_attchmnts WHERE attchmnt_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Self Removal of an Attached Document");
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Attached Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createExtrDataCol($colno, $collabel, $lovnm, $datatyp
        , $catgry, $lngth, $dsplytyp, $orgid, $tblrnumcols, $ordr, $csvTblColNms, $isrqrd) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO prs.prsn_extra_data_cols(
            column_no, column_label, attchd_lov_name, 
            column_data_type, column_data_category, data_length, data_dsply_type, 
            org_id, no_cols_tblr_dsply, col_order, csv_tblr_col_nms, created_by, creation_date, 
            last_update_by, last_update_date,is_required)" .
            "VALUES (" . $colno .
            ", '" . loc_db_escape_string($collabel) .
            "', '" . loc_db_escape_string($lovnm) .
            "', '" . loc_db_escape_string($datatyp) .
            "', '" . loc_db_escape_string($catgry) .
            "', " . loc_db_escape_string($lngth) .
            ", '" . loc_db_escape_string($dsplytyp) .
            "', " . $orgid .
            ", " . $tblrnumcols . ", " . $ordr .
            ", '" . loc_db_escape_string($csvTblColNms) .
            "', " . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr .
            "', '" . cnvrtBoolToBitStr($isrqrd) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateExtrDataCol($colno, $collabel, $lovnm, $datatyp
        , $catgry, $lngth, $dsplytyp, $orgid, $tblrnumcols, $rowid, $ordr, $csvTblColNms, $isrqrd) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE prs.prsn_extra_data_cols SET 
            column_no=" . $colno .
            ", column_label='" . loc_db_escape_string($collabel) .
            "', attchd_lov_name='" . loc_db_escape_string($lovnm) .
            "', column_data_type='" . loc_db_escape_string($datatyp) .
            "', column_data_category='" . loc_db_escape_string($catgry) .
            "', data_length=" . loc_db_escape_string($lngth) .
            ", data_dsply_type='" . loc_db_escape_string($dsplytyp) .
            "', org_id=" . $orgid .
            ", no_cols_tblr_dsply=" . $tblrnumcols .
            ", col_order=" . $ordr .
            ", csv_tblr_col_nms='" . loc_db_escape_string($csvTblColNms) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "', is_required='" . cnvrtBoolToBitStr($isrqrd)
            . "' WHERE extra_data_cols_id = " . $rowid;
    return execUpdtInsSQL($updtSQL);
}

function deleteExtrDataCol($pkeyID, $extrInfo = "") {
    $insSQL = "DELETE FROM prs.prsn_extra_data_cols WHERE extra_data_cols_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Additional Data Column:" . $extrInfo);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Additional Data Column(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getRqstAttchMnts($prsnid) {
    global $ftp_base_db_fldr;
    $sqlStr = "SELECT string_agg(REPLACE(a.attchmnt_desc,';',','),';') attchmnt_desc, 
string_agg(REPLACE('" . $ftp_base_db_fldr . "/PrsnDocs/Request/' || a.file_name,';',','),';') file_name 
  FROM self.self_prsn_doc_attchmnts a 
  WHERE person_id=" . $prsnid;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updatePrsDataChangeReq($srcDocID, $nwvalue) {
    global $usrID;
    $datestr = getDB_Date_time();

    $updSQL = "UPDATE self.self_prsn_chng_rqst
            SET rqst_status='$nwvalue',
                last_update_by = $usrID,
                last_update_date = '$datestr'
            WHERE rqst_id = $srcDocID";
    $affctd = execUpdtInsSQL($updSQL);
    return $affctd;
}

function dataChngReqMsgActns($routingID = -1, $inptSlctdRtngs = "", $actionToPrfrm = "Initiate", $srcDocID = -1, $srcDocType = "Personal Records Change") {
    global $app_url;
    global $admin_name;
    $userID = $_SESSION['USRID'];
    $user_Name = $_SESSION['UNAME'];
    $rtngMsgID = -1;
    $affctd = 0;
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    $affctd4 = 0;
    $curPrsnsLevel = -123456789;
    $msgTitle = "";
    $msgBdy = "";
    $nwPrsnLocID = isset($_POST['toPrsLocID']) ? cleanInputData($_POST['toPrsLocID']) : "";
    $apprvrCmmnts = isset($_POST['actReason']) ? cleanInputData($_POST['actReason']) : "";
    $fromPrsnID = getUserPrsnID($user_Name);
    $usrFullNm = getPrsnFullNm($fromPrsnID);
    $msg = "";
    $dsply = "";
    $msg_id = -1;
    $appID = -1;
    $attchmnts = "";
    $reqestDte = getFrmtdDB_Date_time();

    $srcdoctyp = $srcDocType;
    $srcdocid = $srcDocID;

    $reportTitle = "Send Outstanding Bulk Messages";
    $reportName = "Send Outstanding Bulk Messages";
    $rptID = getRptID($reportName);
    $prmID = getParamIDUseSQLRep("{:msg_batch_id}", $rptID);
    $msgBatchID = -1;
    //session_write_close();
    if ($routingID <= 0 && $inptSlctdRtngs == "") {
        if ($actionToPrfrm == "Initiate" && $srcDocID > 0) {
            $msg_id = getWkfMsgID();
            $appID = getAppID('Personal Records Change', 'Basic Person Data');
//Requestor
            $prsnid = $fromPrsnID;
            $fullNm = $usrFullNm;
            $prsnLocID = getPersonLocID($prsnid);

//Message Header & Details
            $msghdr = "$fullNm ($prsnLocID) Requests for Changes in Personal Records";
            $msgbody = "PERSONAL RECORDS CHANGE REQUEST ON ($reqestDte):- "
                    . "A request for Change in Personal Information has been submitted by $fullNm ($prsnLocID) "
                    . "<br/>Please open the attached Work Document and attend to this Request.
                      <br/>Thank you.";
            $msgtyp = "Work Document";
            $msgsts = "0";
            $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", $srcDocType . " Hierarchy"); //Get hierarchy ID
            $rslt = getRqstAttchMnts($prsnid);
            $attchmnts = ""; //Get Attachments
            $attchmnts_desc = ""; //Get Attachments
            while ($rw = loc_db_fetch_array($rslt)) {
                $attchmnts = $rw[1];
                $attchmnts_desc = $rw[0];
            }

            createWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
//Get Hierarchy Members
            $result = getNextApprvrsInMnlHrchy($hrchyid, $curPrsnsLevel);
            $prsnsFnd = 0;
            $lastPrsnID = "|";
            $msgBatchID = getMsgBatchID();
            $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
            while ($row = loc_db_fetch_array($result)) {
                $toPrsnID = (float) $row[0];
                $prsnsFnd++;
                if ($toPrsnID > 0) {
                    routWkfMsg($msg_id, $prsnid, $toPrsnID, $userID, 'Initiated', 'Open;Reject;Request for Information;Approve');
                    $dsply = '<div style="text-align:center;font-weight:bold;font-size:18px;color:blue;position:relative;top:50%;transform:translateY(-50%);">CONGRATULATIONS!</br>Your request has been submitted successfully for Approval.</br>
                        A notification will be sent to you on approval of your request. Thank you!</div>';
                    $msg = $dsply;
                    //Begin Email Sending Process                    
                    $result1 = getEmlDetailsB4Actn($srcdoctyp, $srcdocid);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $frmID = $toPrsnID;
                        if (strpos($lastPrsnID, "|" . $frmID . "|") !== FALSE) {
                            $lastPrsnID .= $frmID . "|";
                            continue;
                        }
                        $lastPrsnID .= $frmID . "|";
                        $subject = $row1[1];
                        $actSoFar = $row1[3];
                        if ($actSoFar == "") {
                            $actSoFar = "&nbsp;&nbsp;NONE";
                        }
                        $msgPart = "<span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ACTIONS TAKEN SO FAR:</span><br/>" . $actSoFar . "<br/> <span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ORIGINAL MESSAGE:</span><br/>&nbsp;&nbsp;" . $row1[2];
                        $docType = $srcDocType;
                        $to = getPrsnEmail($frmID);
                        $nameto = getPrsnFullNm($frmID);
                        if ($docType != "" && $docType != "Login") {
                            $message = "Dear $nameto, <br/><br/>A notification has been sent to your account in the Portal as follows:"
                                    . "<br/><br/>"
                                    . $msgPart .
                                    "<br/><br/>Kindly <a href=\""
                                    . $app_url . "\">Login via this Link</a> to <strong>VIEW and ACT</strong> on it!<br/>Thank you for your cooperation!<br/><br/>Best Regards,<br/>" . $admin_name;
                            $errMsg = "";
                            createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "", "Email");
                        }
                    }
                }
            }
            if ($prsnsFnd <= 0) {
                $dsply .= "<br/>|ERROR|-No Approval Hierarchy Found";
                $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
            } else {
                //Update Request Status to In Process
                updatePrsDataChangeReq($srcdocid, "Approval Initiated");
            }
        } else {
            $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Generated";
            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
        }
    } else {
        if ($routingID > 0) {
            $oldMsgbodyAddOn = "";
            $reslt1 = getWkfMsgRtngData($routingID);
            while ($row = loc_db_fetch_array($reslt1)) {
                $rtngMsgID = (float) $row[0];
                $msg_id = $rtngMsgID;
                $curPrsnsLevel = (float) $row[18];
                $isActionDone = $row[9];
                $oldMsgbodyAddOn = $row[17];
//$rtngMsgID = (float) getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "msg_id", $routingID);
//$curPrsnsLevel = (float) getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "to_prsns_hrchy_level", $routingID);
//$isActionDone = getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "is_action_done", $routingID);
            }
            $row = NULL;

            $reslt2 = getWkfMsgHdrData($rtngMsgID);
            while ($row = loc_db_fetch_array($reslt2)) {
                $msgTitle = $row[1]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "msg_hdr", $rtngMsgID);
                $msgBdy = $row[2]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "msg_body", $rtngMsgID);
                $srcDocID = (float) $row[10]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "src_doc_id", $rtngMsgID);
                $srcDocType = $row[9]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "src_doc_type", $rtngMsgID);
                $orgnlPrsnUsrID = (float) $row[3]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "created_by", $rtngMsgID);
                $hrchyid = (float) $row[5];
                $appID = (float) $row[7];
                $attchmnts = $row[13];
                $attchmnts_desc = $row[14]; //Get Attachments
            }
            $srcdoctyp = $srcDocType;
            $srcdocid = $srcDocID;
            $orgnlPrsnID = getUserPrsnID1($orgnlPrsnUsrID);
            if ($isActionDone == '0') {
                if ($actionToPrfrm == "Open") {
                    echo prsnDataRODsply1($orgnlPrsnID);
                } else if ($actionToPrfrm == "Reject") {
                    $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Rejected", "None", $userID);
//$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                    $datestr = getFrmtdDB_Date_time();
                    $msgbodyAddOn = "";
                    $msgbodyAddOn .= "REJECTION ON $datestr:- This document has been Rejected by $usrFullNm with the ff Message:<br/>";
                    $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                    $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                    $msgbodyAddOn .= $oldMsgbodyAddOn;

                    updateWkfMsgStatus($rtngMsgID, "1", $userID);
                    updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

//Message Header & Details
                    $msghdr = "REJECTED - " . $msgTitle;
                    $msgbody = $msgBdy; //$msgbodyAddOn. "ORIGINAL MESSAGE :<br/><br/>" .
                    $msgtyp = "Informational";
                    $msgsts = "0";
//$msg_id = getWkfMsgID();
                    $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                    $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, "Initiated", "Acknowledge;Open", 1, $msgbodyAddOn);
                    $affctd4 += updatePrsDataChangeReq($srcdocid, "Rejected");

                    //Begin Email Sending Process                    
                    $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
                    $lastPrsnID = "|";
                    $msgBatchID = getMsgBatchID();
                    $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
                    while ($row = loc_db_fetch_array($result)) {
                        $frmID = $row[0];
                        if (strpos($lastPrsnID, "|" . $frmID . "|") !== FALSE || $frmID == $fromPrsnID) {
                            $lastPrsnID .= $frmID . "|";
                            continue;
                        }
                        $lastPrsnID .= $frmID . "|";
                        $subject = $row[1];
                        $actSoFar = $row[3];
                        if ($actSoFar == "") {
                            $actSoFar = "&nbsp;&nbsp;NONE";
                        }
                        $msgPart = "<span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ACTIONS TAKEN SO FAR:</span><br/>" . $actSoFar . "<br/> <span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ORIGINAL MESSAGE:</span><br/>&nbsp;&nbsp;" . $row[2];
                        $docType = $srcDocType;
                        $to = getPrsnEmail($frmID);
                        $nameto = getPrsnFullNm($frmID);
                        if ($docType != "" && $docType != "Login") {
                            $message = "Dear $nameto, <br/><br/>A notification has been sent to your account in the Portal as follows:"
                                    . "<br/><br/>"
                                    . $msgPart .
                                    "<br/><br/>Kindly <a href=\""
                                    . $app_url . "\">Login via this Link</a> to <strong>VIEW and ACT</strong> on it!<br/>Thank you for your cooperation!<br/><br/>Best Regards,<br/>" . $admin_name;
                            $errMsg = "";
                            createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "", "Email");
                            //sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", "", "", $admin_name);
                        }
                    }
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Rejected!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
                        //$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to Original Sender!";
                        $dsply .= "<br/>$affctd4 Request Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Rejected";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                } else if ($actionToPrfrm == "Request for Information") {
                    $nwPrsnID = getPersonID($nwPrsnLocID);
//$nwPrsnFullNm = getPrsnFullNm($nwPrsnID);
                    $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Information Requested", "None", $userID);
//$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                    $datestr = getFrmtdDB_Date_time();
                    $msgbodyAddOn = "";
                    $msgbodyAddOn .= "INFORMATION REQUESTED ON $datestr:- A requested for Information has been generated by $usrFullNm with the ff Message:<br/>";
                    $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                    $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                    $msgbodyAddOn .= $oldMsgbodyAddOn;

                    updateWkfMsgStatus($rtngMsgID, "1", $userID);
                    updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

//Message Header & Details
                    $msghdr = "INFORMATION REQUEST - " . $msgTitle;
                    $msgbody = $msgBdy; //"ORIGINAL MESSAGE :<br/><br/>" . 
                    $msgtyp = "Work Document";
                    $msgsts = "0";
//$msg_id = getWkfMsgID();
                    $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                    $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nwPrsnID, $userID, "Initiated", "Respond;Open", $curPrsnsLevel, $msgbodyAddOn);
//$affctd4+=updatePrsDataChangeReq($srcdocid, "Rejected");
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Information Requested!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
//$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to New Person!";
// $dsply .= "<br/>$affctd4 Appointment Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                } else if ($actionToPrfrm == "Respond") {
                    $nwPrsnID = getPersonID($nwPrsnLocID);
//$nwPrsnFullNm = getPrsnFullNm($nwPrsnID);
                    $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Response Given", "None", $userID);
//$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                    $datestr = getFrmtdDB_Date_time();
                    $msgbodyAddOn = "";
                    $msgbodyAddOn .= "RESPONSE TO INFORMATION REQUESTED ON $datestr:- A response to an Information Request has been given by $usrFullNm with the ff Message:<br/>";
                    $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                    $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                    $msgbodyAddOn .= $oldMsgbodyAddOn;

                    updateWkfMsgStatus($rtngMsgID, "1", $userID);
                    updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

//Message Header & Details
                    $msghdr = "RESPONSE TO INFORMATION REQUEST - " . $msgTitle;
                    $msgbody = $msgBdy; //"ORIGINAL MESSAGE :<br/><br/>" . 
                    $msgtyp = "Work Document";
                    $msgsts = "0";
//$msg_id = getWkfMsgID();
                    $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                    $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nwPrsnID, $userID, "Initiated", 'Open;Reject;Request for Information;Approve', $curPrsnsLevel, $msgbodyAddOn);
//$affctd4+=updatePrsDataChangeReq($srcdocid, "Rejected");
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Response Given!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
//$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to New Person!";
// $dsply .= "<br/>$affctd4 Appointment Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                } else if ($actionToPrfrm == "Acknowledge") {
                    $nwPrsnID = getPersonID($nwPrsnLocID);
//$nwPrsnFullNm = getPrsnFullNm($nwPrsnID);
                    $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Acknowledged", "None", $userID);
//$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                    $datestr = getFrmtdDB_Date_time();
                    $msgbodyAddOn = "";
                    $msgbodyAddOn .= "MESSAGE ACKNOWLEDGED ON $datestr:- An acknowledgement of the message has been given by $usrFullNm <br/><br/>";
//$msgbodyAddOn.=$apprvrCmmnts . "<br/><br/>";
                    $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);

                    updateWkfMsgStatus($rtngMsgID, "1", $userID);
                    updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Acknowledged!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
//$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
//$dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to New Person!";
// $dsply .= "<br/>$affctd4 Appointment Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                } else if ($actionToPrfrm == "Approve") {
                    $nxtPrsnsRslt = getNextApprvrsInMnlHrchy($hrchyid, $curPrsnsLevel);
                    $prsnsFnd = 0;
                    $lastPrsnID = "|";
                    $msgbodyAddOn = "";
                    while ($row = loc_db_fetch_array($nxtPrsnsRslt)) {
                        $nxtPrsnID = (float) $row[0];
                        $newStatus = "Reviewed";
                        $nxtStatus = "Open;Reject;Request for Information;Approve";
                        $nxtApprvr = "Next Approver";
                        if ($prsnsFnd == 0) {
                            $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, $newStatus, $nxtStatus, $userID);
                            $datestr = getFrmtdDB_Date_time();
                            $msgbodyAddOn .= strtoupper($newStatus) . " ON $datestr:- This document has been $newStatus by $usrFullNm <br/><br/>";
                            $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                            $msgbodyAddOn .= $oldMsgbodyAddOn;
                            updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);
                            $msghdr = $msgTitle;
                            $msgbody = $msgBdy;
                            $msgtyp = "Work Document";
                            $msgsts = "0";
                            $curPrsnsLevel += 1;
                            $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                        }
                        $prsnsFnd++;
                        if ($nxtPrsnID > 0) {
                            $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nxtPrsnID, $userID, $newStatus, $nxtStatus, $curPrsnsLevel, $msgbodyAddOn);
                        }
                        if ($prsnsFnd == 1) {
                            $affctd4 += updatePrsDataChangeReq($srcdocid, $newStatus);
                        }
                    }
                    if ($prsnsFnd <= 0) {
                        $newStatus = "Approved";
                        $nxtStatus = "None;Acknowledge";
                        $nxtApprvr = "Original Person";
                        $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, $newStatus, $nxtStatus, $userID);
                        $datestr = getFrmtdDB_Date_time();
                        $msgbodyAddOn = "";
                        $msgbodyAddOn .= strtoupper($newStatus) . " ON $datestr:- This document has been $newStatus by $usrFullNm <br/><br/>";
                        $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                        $msgbodyAddOn .= $oldMsgbodyAddOn;
                        updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);
                        updateWkfMsgStatus($rtngMsgID, "1", $userID);
                        $msghdr = "APPROVED - " . $msgTitle;
                        $msgbody = $msgBdy;
                        $msgtyp = "Informational";
                        $msgsts = "0";
                        $curPrsnsLevel += 1;
                        $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                        $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, $newStatus, $nxtStatus, $curPrsnsLevel, $msgbodyAddOn);

                        $dsply .= trnsfrRecsFrmSelfToPrs($orgnlPrsnID);
                        $affctd4 += updatePrsDataChangeReq($srcdocid, $newStatus);

                        //Begin Email Sending Process                    
                        $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
                        $lastPrsnID = "|";
                        $msgBatchID = getMsgBatchID();
                        $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
                        while ($row = loc_db_fetch_array($result)) {
                            $frmID = $orgnlPrsnID;
                            if (strpos($lastPrsnID, "|" . $frmID . "|") !== FALSE) {
                                $lastPrsnID .= $frmID . "|";
                                continue;
                            }
                            $lastPrsnID .= $frmID . "|";
                            $subject = $row[1];
                            $actSoFar = $row[3];
                            if ($actSoFar == "") {
                                $actSoFar = "&nbsp;&nbsp;NONE";
                            }
                            $msgPart = "<span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ACTIONS TAKEN SO FAR:</span><br/>" . $actSoFar . "<br/> <span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ORIGINAL MESSAGE:</span><br/>&nbsp;&nbsp;" . $row[2];
                            $docType = $srcDocType;
                            $to = getPrsnEmail($frmID);
                            $nameto = getPrsnFullNm($frmID);
                            if ($docType != "" && $docType != "Login") {
                                $message = "Dear $nameto, <br/><br/>A notification has been sent to your account in the Portal as follows:"
                                        . "<br/><br/>"
                                        . $msgPart .
                                        "<br/><br/>Kindly <a href=\""
                                        . $app_url . "\">Login via this Link</a> to <strong>VIEW</strong> it!<br/>Thank you for your cooperation!<br/><br/>Best Regards,<br/>" . $admin_name;
                                $errMsg = "";
                                createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "", "Email");
                                //sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", "", "", $admin_name);
                            }
                            break;
                        }
                    }
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to $newStatus!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
//$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to $nxtApprvr!";
                        $dsply .= "<br/>$affctd4 Request Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                }
            }
        } else {
            $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Selected";
            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
        }
    }
    if ($msgBatchID > 0) {
        generateReportRun($rptID, $paramRepsNVals, -1);
    }
    return $msg;
}

function withdrawDataChngRqst($hdrid) {
    $apprvrStatus = 'Withdrawn';
    $msg = "";
    $rqstHdrStatus = get_RqstStatusUsngID($hdrid);
    if ($rqstHdrStatus == 'Approved') {
        return "<span style=\"color:red;\">|ERROR| Nothing to Withdraw!</span>";
    }
    $srcDocID = $hdrid;
    $srcDocType = "Personal Records Change";
    $inptSlctdRtngs = "";
    $actionToPrfrm = "Reject";
    $selSQL = "SELECT MAX(b.routing_id)
  FROM wkf.wkf_actual_msgs_hdr a, wkf.wkf_actual_msgs_routng b
  WHERE a.msg_id=b.msg_id and a.src_doc_type='" . $srcDocType . "' 
  and a.src_doc_id=" . $hdrid;
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        $routingID = $row[0];
        $actionToPrfrm = "Reject";
        if ($routingID > 0) {
            $msg = dataChngReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
        }
    }
    updatePrsDataChangeReq($srcDocID, $apprvrStatus);
    return $msg;
}

function prsnDataRODsply1($pkID) {
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $smplTokenWord1;
    global $fldrPrfx;
    global $app_url;

    $result = get_PrsnDetOrgnl($pkID);
//echo "PKey:".$pkID;
    $resultRqst = get_PrsnDet_Rqst($pkID);
    $colsCnt = loc_db_num_fields($result);
    $output = "<div style=\"padding:2px;\">
        <table style=\"width:100%;border-collapse: collapse;border-spacing: 0;\"class=\"gridtable\">
            <caption>PERSON'S DETAIL</caption>";
    $output .= "<thead><tr>";
    $output .= "<th width=\"170px\" style=\"font-weight:bold;\">LABEL</th>";
    $output .= "<th width=\"250px\" style=\"font-weight:bold;\">EXISTING DATA</th>";
    $output .= "<th width=\"250px\" style=\"font-weight:bold;\">REQUESTED DATA</th>";
    $output .= "</tr></thead>";
    $output .= "<tbody>";
    while ($row = loc_db_fetch_array($result)) {
        $style = "";
        $pkID = $row[0];

        $rowRqst = loc_db_fetch_array($resultRqst);
        $tblrowNo = 0;
        for ($d = 0; $d < $colsCnt; $d++) {
            $style = "";
            $style2 = "";
            if (trim(loc_db_field_name($result, $d)) == "mt") {
                $style = "style=\"display:none;\"";
            }
            $hrf1 = "";
            $hrf2 = "";
            $labl = ucwords(str_replace("_", " ", loc_db_field_name($result, $d)));
            $output .= "<tr $style>";
            $tblrowNo++;
            if (trim(loc_db_field_name($result, $d)) == "Person's Picture") {
                $temp = explode(".", $row[$d]);
                $extension = end($temp);
                if ($extension == "") {
                    $extension = "png";
                }
                $tempRqst = explode(".", $rowRqst[$d]);
                $extensionRqst = end($tempRqst);
                if ($extensionRqst == "") {
                    $extensionRqst = "png";
                }
                $nwFileName = encrypt1($row[$d], $smplTokenWord1) . "." . $extension;
                $nwFileNameRqst = encrypt1($rowRqst[$d], $smplTokenWord1) . "." . $extensionRqst;
                $img_src = $tmpDest . $nwFileName;
                $ftp_src = $ftp_base_db_fldr . "/Person/" . $row[$d];
                $img_srcRqst = $tmpDest . $nwFileNameRqst;
                $ftp_srcRqst = $ftp_base_db_fldr . "/Person/Request/" . $rowRqst[$d];
                if ($row[$d] != "") {
                    if (file_exists($ftp_src)) {
                        copy("$ftp_src", $fldrPrfx . "$img_src");
                    }
                }
                //echo $ftp_srcRqst . " | " . $rowRqst[$d];
                if ($rowRqst[$d] != "") {
                    if (file_exists($ftp_srcRqst)) {
                        copy("$ftp_srcRqst", $fldrPrfx . "$img_srcRqst");
                    }
                }
                if (file_exists($fldrPrfx . $img_src)) {
                    //image exists!
                } else {
                    //image does not exist.
                    $img_src = "cmn_images/image_up.png";
                }

                if (file_exists($fldrPrfx . $img_srcRqst)) {
//image exists!
                } else {
//image does not exist.
                    $img_srcRqst = "cmn_images/image_up.png";
                }

                $radomNo = rand(0, 500);
                $output .= "<td width=\"170px\" style=\"font-weight:bold;vertical-align:top;\" class=\"likeheader\">" . $labl . ":</td>";
                $output .= "<td  width=\"250px\" $style2 id=\"inpt$d\" name=\"inpt$d\"><img style=\"border:1px solid #eee;height:180px;padding:5px;\" src=\"" . $app_url . "$img_src?v=" . $radomNo . "\" /></td>";
                $output .= "<td  width=\"250px\" $style2 id=\"inptRqst$d\" name=\"inptRqst$d\"><img style=\"border:1px solid #eee;height:180px;padding:5px;\" src=\"" . $app_url . "$img_srcRqst?v=" . $radomNo . "\" /></td>";
            } else {
                $output .= "<td width=\"170px\" style=\"font-weight:bold;vertical-align:top;\" class=\"likeheader\">" . $labl . ":</td>";
                $output .= "<td width=\"250px\" $style2 id=\"inpt$d\" name=\"inpt$d\">$hrf1" . $row[$d] . "$hrf2</td>";
                if (($row[$d] != '') && $rowRqst[$d] == '') {
                    $output .= "<td width=\"250px\" style=\"border-left:1px solid #000;\"$style2 id=\"inptRqst$d\" name=\"inptRqst$d\">**blank**</td>";
                } else if ($row[$d] != $rowRqst[$d]) {
                    $output .= "<td width=\"250px\" style=\"border-left:1px solid #000;\"$style2 id=\"inptRqst$d\" name=\"inptRqst$d\">$hrf1" . $rowRqst[$d] . "$hrf2</td>";
                } else {
                    $output .= "<td width=\"250px\" style=\"border-left:1px solid #000;\"$style2 id=\"inptRqst$d\" name=\"inptRqst$d\">&nbsp;&nbsp;</td>";
                }
            }
            $output .= "</tr>";
        }
    }
    $output .= "</tbody>
             </table>";

    $result2 = get_AllEduc_Rqst($pkID);
    $colsCnt2 = loc_db_num_fields($result2);
    $output .= "<table style=\"width:100%;border-collapse: collapse;border-spacing: 0;margin-top:10px;\" class=\"gridtable\">
            <caption>REQUESTED DATA - PERSON EDUCATIONAL BACKGROUND</caption>";
    $tblrowNo2 = 0;
    while ($row = loc_db_fetch_array($result2)) {
        if ($tblrowNo2 == 0) {
            $output .= "<thead><tr>";
            for ($d = 0; $d < $colsCnt2; $d++) {
                if (trim(loc_db_field_name($result2, $d)) == "mt") {
                    continue;
                }
                $output .= "<th style=\"font-weight:bold;\">" . ucwords(str_replace("_", " ", loc_db_field_name($result2, $d))) . "</th>";
            }
            $output .= "</tr></thead><tbody>";
        }
        $output .= "<tr>";
        for ($d = 0; $d < $colsCnt2; $d++) {
            if (trim(loc_db_field_name($result2, $d)) == "mt") {
                continue;
            }
            $output .= "<td>" . $row[$d] . "</td>";
        }
        $output .= "</tr>";
        $tblrowNo2++;
    }
    $output .= "</tbody>
             </table>";

    $result3 = get_AllWrkExp_Rqst($pkID);
    $colsCnt3 = loc_db_num_fields($result3);
    $output .= "<table style=\"width:100%;border-collapse: collapse;border-spacing: 0;margin-top:10px;\" class=\"gridtable\">
            <caption>REQUESTED DATA - PERSON'S WORKING EXPERIENCE</caption>";
    $tblrowNo3 = 0;
    while ($row = loc_db_fetch_array($result3)) {
        if ($tblrowNo3 == 0) {
            $output .= "<thead><tr>";
            for ($d = 0; $d < $colsCnt3; $d++) {
                if (trim(loc_db_field_name($result3, $d)) == "mt") {
                    continue;
                }
                $output .= "<th style=\"font-weight:bold;\">" . ucwords(str_replace("_", " ", loc_db_field_name($result3, $d))) . "</th>";
            }
            $output .= "</tr></thead><tbody>";
        }
        $output .= "<tr>";
        for ($d = 0; $d < $colsCnt3; $d++) {
            if (trim(loc_db_field_name($result3, $d)) == "mt") {
                continue;
            }
            $output .= "<td>" . $row[$d] . "</td>";
        }
        $output .= "</tr>";
        $tblrowNo3++;
    }
    $output .= "</tbody>
             </table></div>";
    $output .= getExtraDataDsply($pkID);
    return $output;
}

function getExtraDataDsply($pkID) {
    $output = "";
    $orgID = $_SESSION['ORG_ID'];
    $result = get_PrsExtrDataGrps($orgID);
    $output .= "<table style=\"width:100%;border-collapse: collapse;border-spacing: 0;margin-top:10px;\" class=\"gridtable\">
            <caption>REQUESTED DATA - ADDITIONAL PERSON DATA</caption>";
    $output .= "<tbody><tr><td>";

    while ($row = loc_db_fetch_array($result)) {
        $output .= "<div style=\"float:left;margin-top:10px;padding:3px;width:100%;\">";
        $output .= "<fieldset style=\"width:100%;\"><legend>$row[0]</legend>";
        $output .= "<div style=\"width:100%;float:left;margin:2px;padding:3px;\">";
//$output .= "<fieldset><legend>(CHANGES REQUESTED)</legend>";
        $result1 = get_PrsExtrDataGrpCols($row[0], $orgID);
        $cntr1 = 0;
        $gcntr1 = 0;
        while ($row1 = loc_db_fetch_array($result1)) {
            /* POSSIBLE FIELDS
             * label
             * textbox (for now only this)
             * textarea (for now only this)
             * readonly textbox with button
             * readonly textbox with date
             * textbox with number validation
             */

//$label = "";
            if ($row1[7] == "Tabular") {
//<caption>$row1[2]</caption>

                $output .= "<table id=\"extDataTblCol$row1[1]\" class=\"gridtable\" style=\"margin-top:3px;\"><thead><th>No.</th>";
                $fieldHdngs = $row1[11];
                $arry1 = explode(",", $fieldHdngs);
                $cntr = count($arry1);
                for ($i = 0; $i < $row1[9]; $i++) {
                    if ($i <= $cntr - 1) {
                        $output .= "<th>$arry1[$i]</th>";
                    } else {
                        $output .= "<th>&nbsp;</th>";
                    }
                }
                $output .= "</thead><tbody>";
                $fldVal = get_PrsExtrData_Rqst($pkID, $row1[1]);
                $arry3 = explode("|", $fldVal);
                $cntr3 = count($arry3);
                $maxsze = (int) 320 / $row1[9];
                if ($maxsze > 100 || $maxsze < 80) {
                    $maxsze = 100;
                }

                for ($j = 0; $j < $cntr3; $j++) {
                    $output .= "<tr><td>" . ($j + 1) . "</td>";
                    $arry2 = explode("~", $arry3[$j]);
                    $cntr2 = count($arry2);
                    for ($i = 0; $i < $row1[9]; $i++) {
                        if ($i <= $cntr2 - 1) {
                            $output .= "<td>$arry2[$i]</td>";
                        } else {
                            $output .= "<td>&nbsp;</td>";
                        }
                    }
                    $output .= "</tr>";
                }
                $output .= "</tbody></table>";
            } else {
                if ($gcntr1 == 0) {
                    $gcntr1 += 1;
                    $output .= "<table><tbody>";
                }
                if (($cntr1 % 3) == 0) {
                    $output .= "<tr>";
                }

                $output .= "<td style=\"width:150px;background-color: #f5f5f5;\">
                <span style=\"width:150px;font-weight:bold;\">$row1[2]:&nbsp;</span></td>";
                $output .= "<td style=\"width:190px;\">";
                $output .= get_PrsExtrData_Rqst($pkID, $row1[1]);

                $output .= "</td>";
                $cntr1 += 1;
                if (($cntr1 % 3) == 2) {
                    $cntr1 = 0;
                    $output .= "</tr>";
                }
            }
        }
        if (($cntr1 % 3) == 2 || ($cntr1 % 3) == 1) {
            $cntr1 = 0;
            $output .= "</tr>";
//$output .="</tr>";
//$output .="</tbody></table>";
        }
        if ($gcntr1 == 1) {
            $gcntr1 = 0;
//$output .="</tr>";
            $output .= "</tbody></table>";
        }
        $output .= "</div></fieldset></div>";  //</fieldset>      
    }
    $output .= "</td></tr></tbody></table>";
    return $output;
}

function get_PrsnDet_RqstNw($pkID) {
    $strSql = "SELECT person_id mt, local_id_no \"ID No.\", 
          COALESCE(img_location,'') \"Person's Picture\", 
          title, first_name, sur_name \"surname\", other_names, 
          org.get_org_name(org_id) organisation, 
          gender, marital_status, 
          date_of_birth \"Date of Birth\", 
          place_of_birth, religion, 
          res_address residential_address, pstl_addrs postal_address, email, 
          cntct_no_tel tel, cntct_no_mobl mobile, 
          cntct_no_fax fax, hometown, nationality, lnkd_firm_org_id,
          lnkd_firm_site_id, new_company, new_company_loc   
          FROM self.self_prsn_names_nos a 
    WHERE (a.person_id=$pkID)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNewPrsDocAttchID() {
    $sqlStr = "select nextval('prs.prsn_doc_attchmnts_attchmnt_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function trnsfrRecsFrmSelfToPrs($pkID) {
    global $orgID;
    global $usrID;
    global $fldrPrfx;
    global $ftp_base_db_fldr;
    global $tmpDest;

    $datestr = getDB_Date_time();
    $dsply = "";
    $affctd = 0;
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    $affctd4 = 0;
    $affctd5 = 0;
    $affctd6 = 0;
    $affctd7 = 0;
    $affctd8 = 0;
    $affctd9 = 0;
    $affctd10 = 0;
    $result = get_PrsnDet($pkID);
    $resultRqst = get_PrsnDet_RqstNw($pkID);
    $updtSQL = "UPDATE prs.prsn_names_nos SET  ";

    while ($row = loc_db_fetch_array($result)) {
        $loc_id_no = $row[1];
        $row2 = loc_db_fetch_array($resultRqst);

        $temp = explode(".", $row2[2]);
        $extension = end($temp);
        if (trim($extension) == "") {
            $extension = "png";
        }
        $img_src = $ftp_base_db_fldr . "/Person/Request/" . $row2[2];
        $ftp_src = $ftp_base_db_fldr . "/Person/" . "$pkID.$extension";
        if (file_exists($img_src) && !is_dir($img_src)) {
            copy($img_src, "$ftp_src");
        }

        if ($row[2] != $row2[2]) {
            $updtSQL = $updtSQL . " img_location= '" . loc_db_escape_string($pkID . "." . $extension) . "',";
        }
        if ($row[3] != $row2[3]) {
            $updtSQL = $updtSQL . " title= '" . loc_db_escape_string($row2[3]) . "',";
        }
        if ($row[4] != $row2[4]) {
            $updtSQL = $updtSQL . " first_name= '" . loc_db_escape_string($row2[4]) . "',";
        }
        if ($row[5] != $row2[5]) {
            $updtSQL = $updtSQL . " sur_name= '" . loc_db_escape_string($row2[5]) . "',";
        }
        if ($row[6] != $row2[6]) {
            $updtSQL = $updtSQL . " other_names= '" . loc_db_escape_string($row2[6]) . "',";
        }
        if ($row[8] != $row2[8]) {
            $updtSQL = $updtSQL . " gender= '" . loc_db_escape_string($row2[8]) . "',";
        }
        if ($row[9] != $row2[9]) {
            $updtSQL = $updtSQL . " marital_status= '" . loc_db_escape_string($row2[9]) . "',";
        }
        if ($row[10] != $row2[10]) {
            $updtSQL = $updtSQL . " date_of_birth= '" . $row2[10] . "',";
        }
        if ($row[11] != $row2[11]) {
            $updtSQL = $updtSQL . " place_of_birth= '" . loc_db_escape_string($row2[11]) . "',";
        }

        if ($row[12] != $row2[12]) {
            $updtSQL = $updtSQL . " religion= '" . loc_db_escape_string($row2[12]) . "',";
        }
        if ($row[13] != $row2[13]) {
            $updtSQL = $updtSQL . " res_address= '" . loc_db_escape_string($row2[13]) . "',";
        }
        if ($row[14] != $row2[14]) {
            $updtSQL = $updtSQL . " pstl_addrs= '" . loc_db_escape_string($row2[14]) . "',";
        }

        if ($row[15] != $row2[15]) {
            $updtSQL = $updtSQL . " email= '" . loc_db_escape_string($row2[15]) . "',";
        }
        if ($row[16] != $row2[16]) {
            $updtSQL = $updtSQL . " cntct_no_tel= '" . loc_db_escape_string($row2[16]) . "',";
        }
        if ($row[17] != $row2[17]) {
            $updtSQL = $updtSQL . " cntct_no_mobl= '" . loc_db_escape_string($row2[17]) . "',";
        }
        if ($row[18] != $row2[18]) {
            $updtSQL = $updtSQL . " cntct_no_fax= '" . loc_db_escape_string($row2[18]) . "',";
        }
        if ($row[19] != $row2[19]) {
            $updtSQL = $updtSQL . " hometown= '" . loc_db_escape_string($row2[19]) . "',";
        }
        if ($row[20] != $row2[20]) {
            $updtSQL = $updtSQL . " nationality= '" . loc_db_escape_string($row2[20]) . "',";
        }
        if ($row[21] != $row2[21]) {
            $updtSQL = $updtSQL . " lnkd_firm_org_id = " . loc_db_escape_string($row2[21]) . ",";
        }
        if ($row[22] != $row2[22]) {
            $updtSQL = $updtSQL . " lnkd_firm_site_id = " . loc_db_escape_string($row2[22]) . ",";
        }
        if ($row[23] != $row2[23]) {
            $updtSQL = $updtSQL . " new_company = '" . loc_db_escape_string($row2[23]) . "',";
        }
        if ($row[24] != $row2[24]) {
            $updtSQL = $updtSQL . " new_company_loc= '" . loc_db_escape_string($row2[24]) . "'";
        }
        $whereClause = ", last_update_by=$usrID, last_update_date='$datestr' where person_id = " . $pkID;
        $updtSQL = trim($updtSQL, ",");

        if ($updtSQL != "UPDATE prs.prsn_names_nos SET ") {
            $updtSQL = $updtSQL . $whereClause;
            $affctd = execUpdtInsSQL($updtSQL);
        } else {
            $affctd = 1;
        }
    }
    //Nationality IDs
    $delSQL = "DELETE FROM prs.prsn_national_ids WHERE person_id = " . $pkID;
    execUpdtInsSQL($delSQL);

    $insSQL = "INSERT INTO prs.prsn_national_ids(
            person_id, nationality, id_number, created_by, creation_date, 
            last_update_by, last_update_date, national_id_typ, 
            date_issued, expiry_date, other_info) 
            SELECT person_id, nationality, id_number, created_by, creation_date, 
            $usrID, '$datestr', national_id_typ, 
            date_issued, expiry_date, other_info
  FROM self.self_prsn_national_ids WHERE 1=1 AND person_id = " . $pkID;

    $affctd1 += execUpdtInsSQL($insSQL);

    //Educational Background
    $delSQL = "DELETE FROM prs.prsn_education WHERE person_id = " . $pkID;
    execUpdtInsSQL($delSQL);

    $insSQL = "INSERT INTO prs.prsn_education(
            person_id, course_name, school_institution, school_location, 
            cert_obtained, course_start_date, course_end_date, date_cert_awarded, 
            created_by, creation_date, last_update_by, last_update_date, 
            cert_type) 
            SELECT person_id, course_name, school_institution, school_location, 
       cert_obtained, course_start_date, course_end_date, date_cert_awarded, 
            created_by, creation_date, $usrID, '$datestr', cert_type
  FROM self.self_prsn_education WHERE 1=1 AND person_id = " . $pkID;

    $affctd2 += execUpdtInsSQL($insSQL);

    //Working Background
    $delSQL = "DELETE FROM prs.prsn_work_experience WHERE person_id = " . $pkID;
    execUpdtInsSQL($delSQL);

    $insSQL = "INSERT INTO prs.prsn_work_experience(
            person_id, job_name_title, institution_name, job_location,
        job_start_date, job_end_date, job_description, feats_achvments, 
            created_by, creation_date, last_update_by, last_update_date)  
            SELECT person_id, job_name_title, institution_name, job_location,
        job_start_date, job_end_date, job_description, feats_achvments, 
            created_by, creation_date, $usrID, '$datestr' 
  FROM self.self_prsn_work_experience WHERE 1=1 AND person_id = " . $pkID;
    $affctd3 += execUpdtInsSQL($insSQL);

    //Skills/Nature
    $delSQL = "DELETE FROM prs.prsn_skills_nature WHERE person_id = " . $pkID;
    execUpdtInsSQL($delSQL);

    $insSQL = "INSERT INTO prs.prsn_skills_nature(
            person_id, languages, hobbies, interests, conduct, attitude, 
            valid_start_date, valid_end_date, created_by, creation_date, 
            last_update_by, last_update_date) 
            SELECT person_id, languages, hobbies, interests, conduct, attitude, 
            valid_start_date, valid_end_date, created_by, creation_date, $usrID, '$datestr' 
  FROM self.self_prsn_skills_nature WHERE 1=1 AND person_id = " . $pkID;
    $affctd4 += execUpdtInsSQL($insSQL);

    //Doc. Attachments
    $delSQL = "DELETE FROM prs.prsn_doc_attchmnts WHERE person_id = " . $pkID;
    execUpdtInsSQL($delSQL);

    $selSQL1 = "SELECT attchmnt_id, person_id, attchmnt_desc, file_name, created_by, 
       creation_date, last_update_by, last_update_date  
  FROM self.self_prsn_doc_attchmnts WHERE 1=1 AND person_id = " . $pkID;
    $reslt1 = executeSQLNoParams($selSQL1);

    while ($row1 = loc_db_fetch_array($reslt1)) {
        $nwAttchID = getNewPrsDocAttchID();
        $temp = explode(".", $row1[3]);
        $extension = end($temp);
        if (trim($extension) == "") {
            $extension = "png";
        }
        $img_src = $ftp_base_db_fldr . "/PrsnDocs/Request/" . $row1[3];
        $ftp_src = $ftp_base_db_fldr . "/PrsnDocs/" . "$nwAttchID.$extension";
        if (file_exists($img_src) && !is_dir($img_src)) {
            copy("$img_src", "$ftp_src");
        }
        $insSQL = "INSERT INTO prs.prsn_doc_attchmnts(
            attchmnt_id, person_id, attchmnt_desc, file_name, created_by, 
       creation_date, last_update_by, last_update_date) 
       VALUES ($nwAttchID,$row1[1],'$row1[2]','$nwAttchID.$extension',$row1[4],'$row1[5]',$usrID,'$datestr')";
        $affctd5 += execUpdtInsSQL($insSQL);
    }

    //Additional Person Data
    $delSQL = "DELETE FROM prs.prsn_extra_data WHERE person_id = " . $pkID;
    execUpdtInsSQL($delSQL);

    $insSQL = "INSERT INTO prs.prsn_extra_data(
             person_id, data_col1, data_col2, data_col3, data_col4, 
            data_col5, data_col6, data_col7, data_col8, data_col9, data_col10, 
            data_col11, data_col12, data_col13, data_col14, data_col15, data_col16, 
            data_col17, data_col18, data_col19, data_col20, data_col21, data_col22, 
            data_col23, data_col24, data_col25, data_col26, data_col27, data_col28, 
            data_col29, data_col30, data_col31, data_col32, data_col33, data_col34, 
            data_col35, data_col36, data_col37, data_col38, data_col39, data_col40, 
            data_col41, data_col42, data_col43, data_col44, data_col45, data_col46, 
            data_col47, data_col48, data_col49, data_col50, created_by, creation_date, 
            last_update_by, last_update_date)     
            SELECT person_id, data_col1, data_col2, data_col3, data_col4, 
       data_col5, data_col6, data_col7, data_col8, data_col9, data_col10, 
       data_col11, data_col12, data_col13, data_col14, data_col15, data_col16, 
       data_col17, data_col18, data_col19, data_col20, data_col21, data_col22, 
       data_col23, data_col24, data_col25, data_col26, data_col27, data_col28, 
       data_col29, data_col30, data_col31, data_col32, data_col33, data_col34, 
       data_col35, data_col36, data_col37, data_col38, data_col39, data_col40, 
       data_col41, data_col42, data_col43, data_col44, data_col45, data_col46, 
       data_col47, data_col48, data_col49, data_col50, 
            created_by, creation_date, $usrID, '$datestr' 
  FROM self.self_prsn_extra_data WHERE person_id = " . $pkID;
    $affctd6 += execUpdtInsSQL($insSQL);

    if ($affctd > 0) {
        $updtSQL = "UPDATE self.self_prsn_chng_rqst 
                    SET rqst_status='Approved'
                    WHERE person_id = $pkID and rqst_status = 'Approval Initiated'";

        $affctd7 = execUpdtInsSQL($updtSQL);

        $dsply .= "<br/>Change Request Approved!";
        $dsply .= "<br/>Successfully saved records of " . $loc_id_no . "";
        $dsply .= "<br/>$affctd1 National IDs Refreshed!";
        $dsply .= "<br/>$affctd2 Educational Background Refreshed!";
        $dsply .= "<br/>$affctd3 Working Exprience Refreshed!";
        $dsply .= "<br/>$affctd4 Skills/Nature Refreshed!";
        $dsply .= "<br/>$affctd5 Documents Attached!";
        $dsply .= "<br/>$affctd6 Extra Person Data Edited!";
        return "<p style = \"text-align:left; color:#32CD32;\"><b><i>$dsply</i></b></p>";
    } else {
        return "<span id = 'login_username_errorloc' class = 'error'>$dsply</span>";
    }
}

function get_AllNtnlty_Rqst($pkID) {
    $strSql = "SELECT nationality \"Country\", national_id_typ national_id_type, 
        id_number, date_issued, expiry_date, other_info other_information 
          FROM self.self_prsn_national_ids WHERE ((person_id = $pkID)) "
            . "except"
            . " SELECT nationality \"Country\", national_id_typ national_id_type, 
        id_number, date_issued, expiry_date, other_info other_information 
          FROM prs.prsn_national_ids WHERE ((person_id = $pkID)) ";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllEduc_Rqst($pkID) {
    $strSql = "SELECT person_id mt, course_name, school_institution \"School/Institution        \", school_location, 
       cert_obtained \"Certificate Obtained     \", 
       to_char(to_timestamp(course_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"From      \",
       to_char(to_timestamp(course_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"To        \", " .
            " date_cert_awarded \"Date Obtained     \", " .
            " cert_type  \"Certificate Type        \"
  FROM self.self_prsn_education 
WHERE 1=1 AND person_id = " . $pkID .
            " except " .
            " SELECT person_id mt, course_name, school_institution \"School/Institution        \", school_location, 
       cert_obtained \"Certificate Obtained     \", 
       to_char(to_timestamp(course_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"From      \",
       to_char(to_timestamp(course_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"To        \", " .
            " date_cert_awarded \"Date Obtained     \", " .
            " cert_type \"Certificate Type        \" 
  FROM prs.prsn_education 
WHERE 1=1 AND person_id = " . $pkID;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_EducBkgrd_Rqst_forUpdate($pkID) {
    $sqlStr = "SELECT person_id, course_name, school_institution, school_location, 
       cert_obtained, course_start_date, course_end_date, date_cert_awarded, cert_type, educ_id
  FROM self.self_prsn_education 
WHERE 1=1 AND person_id = " . $pkID . " ORDER BY educ_id";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_AllWrkExp_Rqst($pkID) {
    $strSql = "SELECT person_id mt, job_name_title \"Job Name/Title    \", institution_name, job_location,
        to_char(to_timestamp(job_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"From      \",
       to_char(to_timestamp(job_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"To      \", 
       job_description, feats_achvments \"Feats/Achivements      \"   
  FROM self.self_prsn_work_experience 
WHERE 1=1 AND person_id = " . $pkID .
            " except " .
            " SELECT person_id mt, job_name_title \"Job Name/Title\", institution_name, job_location,
        to_char(to_timestamp(job_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') \"From      \",
       to_char(to_timestamp(job_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY')  \"To      \", 
       job_description, feats_achvments \"Feats/Achivements      \"    
  FROM prs.prsn_work_experience 
WHERE 1=1 AND person_id = " . $pkID;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PrsnDetOrgnl($pkID) {
//org.get_org_name(org_id) organisation,
    $strSql = "SELECT person_id mt, local_id_no \"ID No.\", 
          COALESCE(img_location,'') \"Person's Picture\", 
          title, first_name, sur_name \"surname\", other_names,  
          gender, marital_status, 
          to_char(to_timestamp(date_of_birth,'YYYY-MM-DD'),'DD-Mon-YYYY') \"Date of Birth\", 
          place_of_birth, religion, 
          res_address residential_address, pstl_addrs postal_address, email, 
          cntct_no_tel tel, cntct_no_mobl mobile, 
          cntct_no_fax fax, hometown, nationality, 
          (CASE WHEN lnkd_firm_org_id>0 THEN 
          REPLACE(scm.get_cstmr_splr_name(lnkd_firm_org_id)||' (' || scm.get_cstmr_splr_site_name(lnkd_firm_site_id) || " .
            "')',' ()','') 
              ELSE 
              REPLACE(new_company || ' (' || new_company_loc || " . "')',' ()','')
              END) \"Linked Firm/ Workplace \" 
          FROM prs.prsn_names_nos a 
    WHERE (a.person_id=$pkID)";
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PrsnDet_Rqst($pkID) {
//org.get_org_name(org_id) organisation, 
    $strSql = "SELECT person_id mt, local_id_no \"ID No.\", 
          COALESCE(img_location,'') \"Person's Picture\", 
          title, first_name, sur_name \"surname\", other_names, 
          gender, marital_status, 
          to_char(to_timestamp(date_of_birth,'YYYY-MM-DD'),'DD-Mon-YYYY') \"Date of Birth\", 
          place_of_birth, religion, 
          res_address residential_address, pstl_addrs postal_address, email, 
          cntct_no_tel tel, cntct_no_mobl mobile, 
          cntct_no_fax fax, hometown, nationality, 
          (CASE WHEN lnkd_firm_org_id>0 THEN 
          REPLACE(scm.get_cstmr_splr_name(lnkd_firm_org_id)||' (' || scm.get_cstmr_splr_site_name(lnkd_firm_site_id) || " .
            "')',' ()','') 
              ELSE 
              REPLACE(new_company || ' (' || new_company_loc || " . "')',' ()','')
              END) \"Linked Firm/ Workplace \"  
          FROM self.self_prsn_names_nos a 
    WHERE (a.person_id=$pkID)";
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PrsExtrData_Rqst($pkID, $colNum = "1") {
    $colNms = array("data_col1", "data_col2", "data_col3", "data_col4",
        "data_col5", "data_col6", "data_col7", "data_col8", "data_col9", "data_col10",
        "data_col11", "data_col12", "data_col13", "data_col14", "data_col15", "data_col16",
        "data_col17", "data_col18", "data_col19", "data_col20", "data_col21", "data_col22",
        "data_col23", "data_col24", "data_col25", "data_col26", "data_col27", "data_col28",
        "data_col29", "data_col30", "data_col31", "data_col32", "data_col33", "data_col34",
        "data_col35", "data_col36", "data_col37", "data_col38", "data_col39", "data_col40",
        "data_col41", "data_col42", "data_col43", "data_col44", "data_col45", "data_col46",
        "data_col47", "data_col48", "data_col49", "data_col50");
    $strSql = "SELECT " . $colNms[$colNum - 1] . ", extra_data_id 
  FROM self.self_prsn_extra_data a WHERE ((person_id = $pkID))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function loadDataOptions($relationType) {
    global $orgID;
    $pssblItems = [];
    if ($relationType === "Relation Type") {
        $i = 0;
        $brghtStr = "";
        $isDynmyc = FALSE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Person Types"), $isDynmyc, -1, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[0];
            $i++;
        }
    } else if ($relationType === "Division/Group") {
        $i = 0;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Divisions/Groups"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Grade") {
        $i = 0;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Grades"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Job") {
        $i = 0;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Jobs"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Position") {
        $i = 0;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Positions"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Site/Location") {
        $i = 0;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Sites/Locations"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            //getGnrlRecNm("org.org_sites_locations", "location_id", "location_code_name", ((int) $titleRow[0]));
            $i++;
        }
    }
    return "All;" . join(";", $pssblItems);
}

function loadDataOptions2($relationType) {
    global $orgID;
    $pssblItems = [];
    if ($relationType === "Relation Type") {
        $i = 0;
        $pssblItems[$i] = "All";
        $i++;
        $brghtStr = "";
        $isDynmyc = FALSE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Person Types"), $isDynmyc, -1, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[0];
            $i++;
        }
    } else if ($relationType === "Division/Group") {
        $i = 0;
        $pssblItems[$i] = "All";
        $i++;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Divisions/Groups"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Grade") {
        $i = 0;
        $pssblItems[$i] = "All";
        $i++;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Grades"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Job") {
        $i = 0;
        $pssblItems[$i] = "All";
        $i++;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Jobs"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Position") {
        $i = 0;
        $pssblItems[$i] = "All";
        $i++;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Positions"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Site/Location") {
        $i = 0;
        $pssblItems[$i] = "All";
        $i++;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Sites/Locations"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            //getGnrlRecNm("org.org_sites_locations", "location_id", "location_code_name", ((int) $titleRow[0]));
            $i++;
        }
    }
    return $pssblItems;
}

function get_OneUser($pkeyID) {
    $strSql = "SELECT distinct a.user_name, "
            . "trim(b.title || ' ' || b.sur_name || ', ' || b.first_name || ' ' || b.other_names) fullname, "
            . " to_char(to_timestamp(a.valid_start_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') valid_start_date, "
            . " to_char(to_timestamp(a.valid_end_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') valid_end_date, "
            . "CASE WHEN a.is_suspended THEN '1' ELSE '0' END, "
            . "CASE WHEN a.is_pswd_temp THEN '1' ELSE '0' END, "
            . "a.failed_login_atmpts, "
            . "to_char(to_timestamp(a.last_login_atmpt_time, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') last_login_atmpt_time, "
            . "to_char(to_timestamp(a.last_pswd_chng_time, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') last_pswd_chng_time, "
            . "age(now(), to_timestamp(last_pswd_chng_time, 'YYYY-MM-DD HH24:MI:SS')) password_age, "
            . "a.user_id, "
            . "b.local_id_no, "
            . "(Select count(1) from sec.sec_users_n_roles z where a.user_id = z.user_id and to_char(now(), 'YYYY-MM-DD HH24:MI:SS') between z.valid_start_date and z.valid_end_date) active_roles,"
            . "CASE WHEN age(now(), to_timestamp(last_pswd_chng_time, 'YYYY-MM-DD HH24:MI:SS')) >= interval '" . get_CurPlcy_Pwd_Exp_Days() . " days' THEN '1' ELSE '0' END is_pswd_exprd , "
            . "a.modules_needed, a.customer_id,  scm.get_cstmr_splr_name(a.customer_id) cstmr_nm "
            . "FROM ((sec.sec_users a " .
            "LEFT OUTER JOIN prs.prsn_names_nos b ON (a.person_id = b.person_id)) LEFT OUTER JOIN " .
            "sec.sec_users_n_roles c ON a.user_id = c.user_id) LEFT OUTER JOIN sec.sec_roles d " .
            "ON d.role_id = c.role_id " .
            "WHERE a.user_id=" . $pkeyID;

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_UsersRoles($searchFor, $searchIn, $offset, $limit_size, $pkID, $sortBy, $extrWhere = "") {
    $wherecls = "";
    $strSql = "";
    $ordrBy = "";
    if ($sortBy == "Role Name") {
        $ordrBy = "ORDER BY b.role_name";
    } else if ($sortBy == "Start Date") {
        $ordrBy = "ORDER BY a.valid_start_date,a.role_id";
    } else {
        $ordrBy = "ORDER BY a.valid_end_date,a.role_id";
    }
    if ($searchIn == "Role Name") {
        $wherecls = " and (b.role_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else {
        $wherecls = " and (b.role_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "SELECT b.role_name, 
    to_char(to_timestamp(a.valid_start_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') valid_start_date, 
    to_char(to_timestamp(a.valid_end_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') valid_end_date, 
    a.role_id, 
    a.dflt_row_id " .
            "FROM sec.sec_users_n_roles a, sec.sec_roles b "
            . "WHERE ((a.role_id = b.role_id) AND (a.user_id = " . $pkID .
            ")$wherecls" . "$extrWhere) " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_TtlUsersRoles($searchFor, $searchIn, $pkID, $extrWhere = "") {
    $wherecls = "";
    $strSql = "";
    if ($searchIn == "Role Name") {
        $wherecls = " and (b.role_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else {
        $wherecls = " and (b.role_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "SELECT count(1) " .
            "FROM sec.sec_users_n_roles a, sec.sec_roles b "
            . "WHERE ((a.role_id = b.role_id) AND (a.user_id = " . $pkID .
            ")$wherecls)";


    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

?>
