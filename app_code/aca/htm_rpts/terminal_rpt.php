<?php

header("content-type:application/json");
$assessSbmtdSheetID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
$orgRslt = getOrgRptDetails($orgID);
$orgNm = "";
$pstl = "";
$cntcts = "";
$email = "";
$webSite = "";
$slogan = "";
$orgType = "";
while ($orgRw = loc_db_fetch_array($orgRslt)) {
    $orgNm = $orgRw[0];
    $pstl = $orgRw[1];
    $cntcts = $orgRw[2];
    $email = $orgRw[3];
    $webSite = $orgRw[4];
    $slogan = $orgRw[5];
    $orgType = $orgRw[7];
}


$reportCrdHdrID = -1;
$reportCrdHdrName = "";
$reportCrdHdrClassID = -1;
$reportCrdHdrClassNm = "";
$reportCrdHdrTypeID = -1;
$reportCrdHdrTypeNm = "";
$reportCrdHdrType = $assessTypeFltr;
$reportCrdHdrCrseID = -1;
$reportCrdHdrCrseNm = "";
$reportCrdHdrSbjctID = -1;
$reportCrdHdrSbjctNm = "";
$reportCrdHdrPeriodID = -1;
$reportCrdHdrPeriodNm = "";
$reportCrdHdrPrsnID = $prsnid;
$reportCrdHdrPrsnNm = getPrsnFullNm($prsnid);
$reportCrdHdrDesc = "";
$reportCrdHdrStatus = "";
$reportCrdHdrAsdPrsID = -1;
$reportCrdHdrAsdPrsNm = "";
$reportCrdHdrAsdPrsLocID = "";
$reportCrdHdrAsdPrsGender = "";
$reportCrdHdrPeriodEndDate = "";
$reportCrdHdrPeriodNxtDate = "";
$payRemarks = "";

$result1 = get_One_AssessSheetHdr1($assessSbmtdSheetID);
while ($row1 = loc_db_fetch_array($result1)) {
    $reportCrdHdrID = (float) $row1[0];
    $reportCrdHdrName = $row1[1];
    $reportCrdHdrClassID = (int) $row1[2];
    $reportCrdHdrClassNm = $row1[3];
    $reportCrdHdrTypeID = (int) $row1[4];
    $reportCrdHdrTypeNm = $row1[5];
    $reportCrdHdrCrseID = (int) $row1[6];
    $reportCrdHdrCrseNm = $row1[7];
    $reportCrdHdrSbjctID = (int) $row1[8];
    $reportCrdHdrSbjctNm = $row1[9];
    $reportCrdHdrPeriodID = (float) $row1[10];
    $reportCrdHdrPeriodNm = $row1[11];
    $reportCrdHdrPrsnID = (float) $row1[12];
    $reportCrdHdrPrsnNm = $row1[13];
    $reportCrdHdrDesc = $row1[16];
    $reportCrdHdrType = $row1[14];
    $reportCrdHdrStatus = $row1[17];
    $reportCrdHdrAsdPrsID = (float) $row1[18];
    $reportCrdHdrAsdPrsNm = $row1[19];
    $reportCrdHdrAsdPrsLocID = $row1[20];
    $reportCrdHdrAsdPrsGender = $row1[21];
    $reportCrdHdrPeriodEndDate = $row1[22];
    $reportCrdHdrPeriodNxtDate = $row1[23];
    $statusStyle = "padding:5px;color:green;font-weight:bold;";
    if ($reportCrdHdrStatus === "Closed") {
        $statusStyle = "padding:5px;color:red;font-weight:bold;";
    }
}

$dte = date('ymdhis');
$ecnptDFlNm = encrypt1("Score_Card_" . $dte . "_" . $lgn_num, $smplTokenWord1);
$nwPDFFileName = "Score_Card_" . $reportCrdHdrID . ".pdf";
$nwHtmFileName = $ecnptDFlNm . ".html";
$fullPemDest = $fldrPrfx . "dwnlds/amcharts_2100/samples/" . $nwHtmFileName;
$fullPemDest1 = $fldrPrfx . "dwnlds/amcharts_2100/samples/" . $nwPDFFileName;
$file = $fullPemDest;


$html = "<!DOCTYPE html>
<html>
    <head>
        <style type=\"text/css\">
            @page {
                size: auto;   /* auto is the initial value */
                margin: 0;  /* this affects the margin in the printer settings */
            }
            * {
              box-sizing: border-box;
            }
            body {
              background: #fefefe;
              font-family: \"Open Sans\", arial, times, tahoma;
              font-size:12px;
            }
            #mainreport{
                padding: 10px 25px 10px 25px;
            }             
            .orgLogoTblStyle {
                border-collapse: collapse;
                margin-bottom: 10px;
                min-width:100%;
                width:100%;
                border:1px solid #fff;
              }
            .orgLogoDivStyle {
              border: 1px solid #ddd;
              float:right;
              width:80%;
              min-height:100px;
              height:100px;
              border-radius:5px;
            }
             .hdrDivStyle{
                margin-bottom: 10px;
                min-width:100%;
                width:100%;
                border:1px solid #ddd;
                border-radius:3px;
                padding: 3px;
                }                
            .hdrTblStyle {
                border-collapse: collapse;
                min-width:100%;
                width:100%;
              }              
            .hdrTblStyle td, .hdrTblStyle th {
              padding: 1px;
            }
            .detlTblStyle {
                border-collapse: collapse;
                margin-bottom: 10px;
                min-width:100%;
                width:100%;
              }
            .detlTblStyle td, .detlTblStyle th {
              border: 1px solid #333;
              padding: 3px;
            }
            .ftrTblStyle {
                border-collapse: collapse;
                margin-bottom: 10px;
                min-width:100%;
                width:100%;
              }
            .ftrTblStyle td, .ftrTblStyle th {
              border: 1px solid #ccc;
              padding: 4px;
            } 
            th {
                height: 35px;
              }
        </style>
    </head>
    <body>
        <div id=\"mainreport\">
            <div class=\"row\">
                <table class=\"orgLogoTblStyle\">
                    <tbody>
                        <tr>
                            <td style=\"max-width:25%;width:25%;padding:5px;\">
                                <div class=\"col\">
                                    <a target=\"_blank\" href=\"$app_url\">
                                        <img src=\"../images/" . $orgID . ".png\" data-holder-rendered=\"true\" style=\"height:100px !important;width:auto;\"/>
                                    </a>
                                </div>
                            </td>
                            <td style=\"min-width:50%;width:50%;text-align:center;\">
                                <div class=\"col company-details\">
                                    <h3 class=\"name\">$orgNm</h3>
                                    <div>$pstl</div>
                                    <div>$cntcts</div>
                                    <div>$email</div>
                                    <div>$webSite</div>
                                </div>
                            </td>
                            <td align=\"right\" style=\"max-width:25%;width:25%;padding:5px;\">
                                <div class=\"orgLogoDivStyle\" >
                                    &nbsp;
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class=\"hdrDivStyle\">
                <table class=\"hdrTblStyle\">
                    <tbody>
                        <tr>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">ID No.:</td>
                            <td style=\"max-width:30%;width:30%;\">$reportCrdHdrAsdPrsLocID</td>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">$groupLabel:</td>
                            <td style=\"max-width:30%;width:30%;\">$reportCrdHdrClassNm</td>
                        </tr>
                        <tr>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">Full Name:</td>
                            <td style=\"max-width:30%;width:30%;\">$reportCrdHdrAsdPrsNm</td>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">Assessment Period:</td>
                            <td style=\"max-width:30%;width:30%;\">$reportCrdHdrPeriodNm</td>
                        </tr>
                        <tr>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">Gender:</td>
                            <td style=\"max-width:30%;width:30%;\">$reportCrdHdrAsdPrsGender</td>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">Period End Date:</td>
                            <td style=\"max-width:30%;width:30%;\">$reportCrdHdrPeriodEndDate</td>
                        </tr>
                        <tr>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">$courseLabel:</td>
                            <td style=\"max-width:30%;width:30%;\">$reportCrdHdrCrseNm</td>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">Next Period Begins:</td>
                            <td style=\"max-width:30%;width:30%;\">$reportCrdHdrPeriodNxtDate</td>
                        </tr>";
$academicSttngID = -1;
$result2 = get_AssessShtGrpCols("01-Header", $reportCrdHdrTypeID);
$cntr1 = 0;
$gcntr1 = 0;
$cntr1Ttl = loc_db_num_rows($result2);
if ($cntr1Ttl > 0) {
    while ($row2 = loc_db_fetch_array($result2)) {
        if ($gcntr1 == 0) {
            $gcntr1 += 1;
        }
        if (($cntr1 % 2) == 0) {
            $html .= "<tr>";
        }
        $columnID = (int) $row2[0];
        $columnNo = (int) $row2[15];
        $prsnDValPulld = get_AssessShtColVal($assessSbmtdSheetID, $academicSttngID, $columnNo);
        $tdCssStyle = "text-align:left;";
        $prsnDValPulld1 = str_replace("{:p_col_value}", $prsnDValPulld, $row2[19]);
        if ($row2[4] == "Number") {
            $tdCssStyle = "text-align:right;";
        }
        $html .= "<td style=\"max-width:20%;width:20%;font-weight:bold;text-align:left;\">$row2[2]:</td>
                  <td style=\"max-width:30%;width:30%;text-align:left;\">$prsnDValPulld1</td>";
        $cntr1 += 1;
        if (($cntr1 % 2) == 0 || $cntr1 == ($cntr1Ttl)) {
            $cntr1 = 0;
            $html .= "</tr>";
        }
    }
    if ($gcntr1 == 1) {
        $gcntr1 = 0;
    }
}
$html .= "</tbody>
                </table>
            </div>
            <div class=\"row\">
                <table class=\"detlTblStyle\">
                    <thead>
                        <tr>
                            <th style=\"max-width:30px !important;width:30px !important;text-align:center;\">No.</th>
                            <th style=\"min-width:175px !important;text-align:left;\">$sbjctLabel</th>";

$colWidth = "max-width:70px !important;width:70px !important;";
$resultHdr = get_AssessShtGrpCols("02-Detail", $reportCrdHdrTypeID);
$colscntr1 = 0;
$ttlColS = loc_db_num_rows($resultHdr);
$colsIDs = array_fill(0, $ttlColS, -1);
$colNos = array_fill(0, $ttlColS, 1);
$colsNames = array_fill(0, $ttlColS, "");
$colsTypes = array_fill(0, $ttlColS, "");
$colsIsFrmlr = array_fill(0, $ttlColS, "1");
$colMinVals = array_fill(0, $ttlColS, 0);
$colMaxVals = array_fill(0, $ttlColS, 0);
$colsIsDsplyd = array_fill(0, $ttlColS, "1");
$colsHtmlCss = array_fill(0, $ttlColS, "");
$colsValSums = array_fill(0, $ttlColS, "");
while ($rwHdr = loc_db_fetch_array($resultHdr)) {
    $colsIDs[$colscntr1] = (int) $rwHdr[0];
    $colsNames[$colscntr1] = $rwHdr[2];
    $colsTypes[$colscntr1] = $rwHdr[4];
    $colsIsFrmlr[$colscntr1] = $rwHdr[13];
    $colNos[$colscntr1] = (int) $rwHdr[15];
    $colMinVals[$colscntr1] = (int) $rwHdr[16];
    $colMaxVals[$colscntr1] = (int) $rwHdr[17];
    $colsIsDsplyd[$colscntr1] = $rwHdr[18];
    $colsHtmlCss[$colscntr1] = $rwHdr[19];
    $colscntr1++;
}
$colscntr = 0;
while ($colscntr < count($colsNames)) {
    $tdStyle = "";
    if ($colsIsDsplyd[$colscntr] == "0") {
        $tdStyle = "display:none;";
    }
    if ($colsTypes[$colscntr] == "Number") {
        $tdStyle .= "text-align: right;";
    } else {
        $tdStyle .= "text-align: center;";
    }
    $html .= "<th style=\"$tdStyle" . "$colWidth\">$colsNames[$colscntr]</th>";
    $colscntr++;
}
$result = get_ReportCardLns1("%", $sbjctLabel . " Name", 0, 1000000, $assessSbmtdSheetID);
$html .= "</tr>
                    </thead>
                    <tbody>";
$cntr = 0;
$colscntr = 0;
while ($row = loc_db_fetch_array($result)) {
    $pkID = (float) $row[0];
    $academicSttngID = (float) $row[1];
    $cntr += 1;
    $html .= "<tr>
                <td style=\"text-align:left;\">$cntr</td>
                <td style=\"text-align:left;\">$row[6]</td>";

    $colscntr = 0;
    while ($colscntr < count($colsIDs)) {
        $columnID = (int) $colsIDs[$colscntr];
        $columnNo = (int) $colNos[$colscntr];
        $prsnDValPulld = $row[7 + $columnNo];
        $isRqrdFld = "rqrdFld";
        $tdClass = "lovtd555";
        $tdStyle = "padding: 0px !important;";
        if ($colsIsFrmlr[$colscntr] == "1" || $canEdt === false) {
            $tdClass = "lovtd";
            $tdStyle = "";
        }
        if ($colsIsDsplyd[$colscntr] == "0") {
            $tdStyle = "display:none;";
        }
        if ($colsTypes[$colscntr] == "Number") {
            $tdStyle .= "text-align: right;";
        } else {
            $tdStyle .= "text-align: center;";
        }
        $minValRhoData = $colMinVals[$colscntr];
        $maxValRhoData = $colMaxVals[$colscntr];
        if ($colsTypes[$colscntr] == "Number") {
            $colsValSums[$colscntr] = ((float) $colsValSums[$colscntr]) + ((float) $prsnDValPulld);
            $prsnDValPulld = number_format(((float) $prsnDValPulld), 2);
        }
        $prsnDValPulld1 = str_replace("{:p_col_value}", $prsnDValPulld, $colsHtmlCss[$colscntr]);
        $html .= "<td class=\"$tdClass\" style=\"text-align: right;$tdStyle" . "$colWidth\">$prsnDValPulld1</td>";
        $colscntr++;
    }
    $html .= "</tr>";
}
$html .= "</tbody>
                    <tfoot>                                                            
                        <tr>";
$ttlFooters = ($colscntr + 2);
for ($z = 0; $z < $ttlFooters; $z++) {
    $tdStyle = "";
    $colscntr = $z - 2;
    if ($colscntr >= 0) {
        if ($colsIsDsplyd[$colscntr] == "0") {
            $tdStyle = "display:none;";
        }
        if ($colsTypes[$colscntr] == "Number") {
            $tdStyle .= "text-align: right;";
            $colsValSums[$colscntr] = number_format(((float) $colsValSums[$colscntr]), 2);
        } else {
            $tdStyle .= "text-align: center;";
            $colsValSums[$colscntr] = "&nbsp;";
        }
        $html .= "<th style=\"$tdStyle\">" . $colsValSums[$colscntr] . "</th>";
    } else {
        $html .= "<th style=\"$tdStyle\">&nbsp;</th>";
    }
}
$html .= "</tr>
                    </tfoot>
                </table>
            </div>
            <div class=\"row\">
                <table class=\"ftrTblStyle\">
                    <tbody>";

$result2 = get_AssessShtGrpCols("03-Footer", $reportCrdHdrTypeID);
$cntr1 = 0;
$cntr1Ttl = loc_db_num_rows($result2);
if ($cntr1Ttl > 0) {
    while ($row2 = loc_db_fetch_array($result2)) {
        $academicSttngID = -1;
        $columnID = (int) $row2[0];
        $columnNo = (int) $row2[15];
        $prsnDValPulld = get_AssessShtColVal($assessSbmtdSheetID, $academicSttngID, $columnNo);
        $tdStyle1 = "";
        $tdStyle2 = "";
        if ($row2[18] == "0") {
            $tdStyle1 = "display:none;";
        }
        if ($row2[4] == "Number") {
            $tdStyle2 .= "text-align: right;";
            $prsnDValPulld = number_format(((float) $prsnDValPulld), 2);
        } else {
            $tdStyle2 .= "text-align: left;";
        }
        $prsnDValPulld1 = str_replace("{:p_col_value}", $prsnDValPulld, $row2[19]);
        $html .= "<tr style=\"$tdStyle1\">";
        $html .= "<td style=\"max-width:30%;width:30%;font-weight:bold;text-align:left;\">$row2[2]:</td>
                  <td style=\"max-width:70%;width:70%;$tdStyle2\">$prsnDValPulld1</td>";
        $html .= "</tr>";
        $cntr1 += 1;
    }
}
$html .= "            </tbody>
            </table>
        </div>
        </div>
    </body>
</html>";


// Write the contents back to the file
file_put_contents($file, $html, FILE_APPEND);

//$cmd = $browserPDFCmd . " --headless --no-sandbox --disable-gpu --print-to-pdf=\"$fullPemDest1\" " . $fullPemDest;
//logSsnErrs($cmd);
//$exitErrMsg = shellExecInBackground($ftp_base_db_fldr . "/bin", $cmd);
 
$rslt = rhoPOSTToAPI(
    $rhoAPIUrl . '/getChromePDF',
    array(
        'browserPDFCmd' => $browserPDFCmd,
        'pdfPath' => $fullPemDest1,
        'htmPath' => $fullPemDest
    )
);
$exitErrMsg = $rslt;
/*$waitCntr = 0;
while (!file_exists($fullPemDest1) && $waitCntr < 5) {
    $waitCntr++;
    sleep(2);
}*/
if (file_exists($fullPemDest1)) {
    $exitErrMsg = "Success:" . $exitErrMsg;
}
/* $exitErrMsg = cnvrtHtmlToPDFURL($fullPemDest, $fullPemDest1); */
$arr_content['percent'] = 100;
$arr_content['mailTo'] = urlencode($email);
$arr_content['mailCc'] = urlencode($admin_email);
$arr_content['mailSubject'] = urlencode("Report/Score Card (" . $reportCrdHdrName . ")");
$message = "<p style=\"font-family: Calibri;font-size:18px;\">Dear " . $reportCrdHdrAsdPrsNm . ", <br/><br/>"
        . "Please find attached the Report Card for an Assessment done in our System: " . $app_name .
        "<br/>Please do not hesitate to contact us should any of the information in the attached be inaccurate.<br/><br/>"
        . "Thank you!</p>";
$arr_content['bulkMessageBody'] = urlencode($message);
$arr_content['URL'] = urlencode($app_url . "dwnlds/amcharts_2100/samples/" . $nwPDFFileName);
$arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
echo json_encode($arr_content);
exit();
?>

