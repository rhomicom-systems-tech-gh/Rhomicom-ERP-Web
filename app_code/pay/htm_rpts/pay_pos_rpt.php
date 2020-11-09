<?php

$vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
$vPsblVal1 = getPssblValDesc($vPsblValID1);
$payMassPyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
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
/* @media print {
  .page-break { display: block; page-break-before: always; }
  } */
/* box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); */
/* <!--<div class=\"logo\"></div>--> */
$paperSze = (300 - 1); //80mm
if (getEnbldPssblValID("58mm", getLovID("Default POS Paper Size")) > 0) {
  $paperSze = (220 - 1);
}
$result = getIntrnlPayReport($payMassPyID);
$docStatus = "";
$docNo = "";
$rcptNo = "";
$dateReceived = "";
$branch = "";
$Cashier = "";
$Customer = "";
$invCurncyNm = "";
$sbmtdInvoiceType = "";
$rcptAmnt = "";
$payMthd = "";
$changeBals = "";
$payRemarks = "";
while ($invcRw = loc_db_fetch_array($result)) {
  $docStatus = $invcRw[1];
  $rcptNo = str_pad($invcRw[0], 10, "0");
  $dateReceived = $invcRw[3];
  $Cashier = $invcRw[5];
  $Customer = $invcRw[6];
  $invCurncyNm = $invcRw[2];
  $payRemarks = $invcRw[4];
  break;
}
$html = "<!DOCTYPE html>
<html lang=\"en\" >
    <head>
      <meta charset=\"UTF-8\">
      <title>Payment Receipt</title>
      <style>
        #invoice-POS {
          padding: 1px;
          /*margin: 0px 0px 0px 0px;*/
          width: " . $paperSze . "px;
          background: #FFF;
        }
        
        #invoice-POS ::selection {
          background: #FFF;
          color: #FFF;
        }
        
        #invoice-POS ::moz-selection {
          background: #FFF;
          color: #FFF;
        }
        #invoice-POS h1 {
          font-size: 1.2em;
          color: #000;
        }
        #invoice-POS h2 {
          margin: 0px !important;
          font-size: 0.9em;
        }
        #invoice-POS h3 {
          font-size: 0.9em;
          font-weight: 300;
        }
        #invoice-POS p {
          margin: 0px !important;
          font-size: 0.9em;
          color: #000;
        }
        
        #invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
          /* Targets all id with 'col-' */
          border-bottom: 1px solid #555;
        }
        
        #invoice-POS #top .logo {
          height: 60px;
          width: 60px;
          background: url(" . $app_url . "/dwnlds/amcharts_2100/images/" . $orgID . ".png) no-repeat;
          background-size: 60px 60px;
        }
        
        #invoice-POS .info {
          display: block;
          margin-left: 0;
        }
        
        #invoice-POS .title {
          float: right;
        }
        
        #invoice-POS .title p {
          text-align: right;
        }
        
        #invoice-POS table {
          width: 100%;
          border-collapse: collapse;
        }
        
        #invoice-POS .tabletitle {
          font-family: Tahoma, \"Times New Roman\", Times, serif;
          font-size: .7em;
          border-bottom: 1px solid #555;
          background: #EEE;
        }
        
        #invoice-POS .tabletitle1 {
          font-family: Tahoma, \"Times New Roman\", Times, serif;
          font-size: .7em;
          background: #FFF;
        }
        
        #invoice-POS .service {
          border-top: 1px solid #eee;
        }
        
        #invoice-POS .item {
          width: " . ($paperSze - 2) . "px;
        }
        
        #invoice-POS .itemtext {
          font-size: .68em;
          font-family: Tahoma, \"Times New Roman\", Times, serif;
        }
        
        #invoice-POS #legalcopy {
          margin-top: 2px;
          border-bottom: 1px solid #555;
          border-top: 1px solid #555;
        }
      </style>
        <script>
          window.console = window.console || function(t) {};
        </script>
        <script>
          if (document.location.search.match(/type=embed/gi)) {
            window.parent.postMessage(\"resize\", \"*\");
          }
        </script>
    </head>
    <body translate=\"no\" >
      <div id=\"invoice-POS\">
        <center id=\"top\">
          <div class=\"info\"> 
            <h2>$orgNm</h2>
            <p> 
                $pstl</br>
                Email : $email</br>Website : $webSite</br>
                $cntcts</br>
            </p>
          </div><!--End Info-->
        </center>
        <!--End InvoiceTop-->

        <div id=\"mid\">
          <div class=\"info\">
            <h2 style=\"text-align:center !important;border-bottom: 1px solid #555;\">Payment Receipt</h2>
            <p> 
                <strong>Payment Receipt No.:</strong> $rcptNo</br>
                <strong>Date Received:</strong> $dateReceived</br>
                <strong>Cashier:</strong> $Cashier</br>
                <strong>Rcvd From:</strong> $Customer</br>
                <strong>Currency:</strong> " . strtoupper($invCurncyNm) . "</br>
                <strong>Narration:</strong> $payRemarks</br>
            </p>
          </div>
        </div>
        <!--End Invoice Mid-->
        <div id=\"bot\">
                        <div id=\"table\">
                            <table>
                                <tr class=\"tabletitle\" style=\"border-top: 1px solid #000;border-bottom: 1px solid #000;\">";

if ($vPsblVal1 != "NARHBT_COLLEGE_APP_1") {
  $html .= "<td colspan=\"2\" class=\"item\"><strong>Pay Item Description</strong></td>
                                    <td class=\"Rate\" style=\"text-align:right !important;\"><strong>Amount</strong></td>
                                </tr>";
}
$invcRslt = get_One_MsPyDet(0, 1000000, $payMassPyID);
$rcptAmnt = 0;
while ($invcRw1 = loc_db_fetch_array($invcRslt)) {
  $itmDesc = $invcRw1[12];
  $itmAMnt = (float) $invcRw1[3];
  $rcptAmnt += $itmAMnt;
  if ($vPsblVal1 == "NARHBT_COLLEGE_APP_1") {
    continue;
  }
  $html .= "<tr class=\"service\">
                                    <td colspan=\"2\" class=\"tableitem\"><p class=\"itemtext\">" . $itmDesc . "</p></td>
                                    <td class=\"tableitem\" style=\"text-align:right !important;\"><p class=\"itemtext\">" . number_format($itmAMnt, 2) . "</p></td>
                                </tr>";
}
$html .= "<tr class=\"tableitem\" style=\"border-top: 1px solid #555;\">
                                    <td colspan=\"2\" class=\"itemtext\" style=\"text-align:right !important;\">Receipt Amount:</td>
                                    <td class=\"itemtext\" style=\"text-align:right !important;\"><strong>" . number_format($rcptAmnt, 2) . "</strong></td>
                                </tr>";
$html .= "</table>
                        </div>
                        <!--End Table-->
                        <div id=\"legalcopy\" style=\"text-align:center !important;\">
                            <p class=\"legal\" style=\"font-style:italic;font-family: \"Times New Roman\", Times, serif;\"><strong>Thank you!</strong> $slogan. </p>
                        </div>
                    </div><!--End InvoiceBot-->
      </div><!--End Invoice-->
    </body>
</html>";
echo urlencode($html);
