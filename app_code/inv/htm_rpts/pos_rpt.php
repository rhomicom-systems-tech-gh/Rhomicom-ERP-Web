<?php

$sbmtdInvoiceID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
$orgRslt = getOrgRptDetails($orgID);
$orgNm = "";
$pstl = "";
$cntcts = "";
$email = "";
$webSite = "";
$slogan = "";
$orgType = "";
$orgLogo = "";
while ($orgRw = loc_db_fetch_array($orgRslt)) {
    $orgNm = $orgRw[0];
    $pstl = $orgRw[1];
    $cntcts = $orgRw[2];
    $email = $orgRw[3];
    $webSite = $orgRw[4];
    $slogan = $orgRw[5];
    $orgType = $orgRw[7];
    $orgLogo = $orgRw[8];
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
$result = getInvoiceReport($sbmtdInvoiceID);
$docStatus = "";
$docNo = "";
$rcptNo = "";
$dateReceived = "";
$branch = "";
$Cashier = "";
$Customer = "";
$invCurncyNm = "";
$sbmtdInvoiceType = "";
$rcptAmnt = 0;
$payMthd = "";
$changeBals = 0;
$payRemarks = "";
while ($invcRw = loc_db_fetch_array($result)) {
    $docStatus = $invcRw[29];
    $docNo = $invcRw[4];
    $rcptNo = $invcRw[15];
    $dateReceived = $invcRw[23];
    $branch = $invcRw[27];
    $Cashier = $invcRw[25];
    $Customer = $invcRw[7];
    $invCurncyNm = $invcRw[12];
    $sbmtdInvoiceType = $invcRw[5];
    $rcptAmnt = (float) $invcRw[17];
    $payMthd = $invcRw[16];
    $changeBals = (float) $invcRw[18];
    $payRemarks = $invcRw[11];
    break;
}
$html = "<!DOCTYPE html>
<html lang=\"en\" >
    <head>
      <meta charset=\"UTF-8\">
      <title>POS Receipt</title>
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
          background: url(" . $app_url . "/dwnlds/amcharts_2100/images/" . $orgLogo . ") no-repeat;
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
                <strong>Doc. No.:</strong> $docNo</br>
                <strong>Rcpt. No.:</strong> $rcptNo</br>
                <strong>Date Received:</strong> $dateReceived</br>
                <strong>Branch:</strong> $branch</br>
                <strong>Cashier:</strong> $Cashier</br>
                <strong>Customer:</strong> $Customer</br>
                <strong>Currency:</strong> " . strtoupper($invCurncyNm) . "</br>
            </p>
          </div>
        </div>
        <!--End Invoice Mid-->
        <div id=\"bot\">
                        <div id=\"table\">
                            <table>
                                <tr class=\"tabletitle\" style=\"border-top: 1px solid #000;border-bottom: 1px solid #000;\">
                                    <td class=\"item\"><strong>Item</strong></td>
                                    <td class=\"Hours\" style=\"text-align:right !important;\"><strong>QTY</strong></td>
                                    <td class=\"Rate\" style=\"text-align:right !important;\"><strong>Amount</strong></td>
                                </tr>";
$invcRslt = getInvoiceRptDet($sbmtdInvoiceID);
while ($invcRw1 = loc_db_fetch_array($invcRslt)) {
    $itmDesc = $invcRw1[4];
    $itmQTY = (float) $invcRw1[0];
    $itmAMnt = (float) $invcRw1[2];
    $html .= "<tr class=\"service\">
                                    <td class=\"tableitem\"><p class=\"itemtext\">" . $itmDesc . "</p></td>
                                    <td class=\"tableitem\" style=\"text-align:right !important;\"><p class=\"itemtext\">" . $itmQTY . "</p></td>
                                    <td class=\"tableitem\" style=\"text-align:right !important;\"><p class=\"itemtext\">" . number_format($itmAMnt, 2) . "</p></td>
                                </tr>";
}
$invcSmmryRslt = null;
if ($docStatus == "Approved") {
    $invcSmmryRslt = getInvoiceRptSmmry($sbmtdInvoiceID, $sbmtdInvoiceType);
} else {
    $invcSmmryRslt = getInvoiceRptAllSmmry($sbmtdInvoiceID, $sbmtdInvoiceType);
}
$nwCntr = 0;
while ($invcRw2 = loc_db_fetch_array($invcSmmryRslt)) {
    $smmryDesc = $invcRw2[0];
    $smmryAmnt = (float) $invcRw2[1];
    $style333 = "";
    if ($nwCntr == 0) {
        $style333 = " style=\"border-top: 1px solid #555 !important;\"";
    }
    $html .= "<tr class=\"tabletitle1\"" . $style333 . ">
                                    <td colspan=\"2\" class=\"Rate\" style=\"text-align:right !important;\"><strong>" . $smmryDesc . "</strong></td>
                                    <td class=\"payment\" style=\"text-align:right !important;\"><strong>" . number_format($smmryAmnt, 2) . "</strong></td>
                              </tr>";
    $nwCntr++;
}
$html .= "<tr class=\"tableitem\" style=\"border-top: 1px solid #555;\">
                                    <td colspan=\"2\" class=\"itemtext\" style=\"text-align:right !important;\">Receipt Amount:</td>
                                    <td class=\"itemtext\" style=\"text-align:right !important;\"><strong>" . number_format($rcptAmnt, 2) . "</strong></td>
                                </tr>";
$html .= "<tr class=\"tableitem\">
                                    <td colspan=\"2\" class=\"itemtext\" style=\"text-align:right !important;\">Description:</td>
                                    <td class=\"itemtext\" style=\"text-align:right !important;\"><strong>" . $payMthd . "</strong></td>
                         </tr>";
$html .= "<tr class=\"tableitem\">
                                    <td colspan=\"2\" class=\"itemtext\" style=\"text-align:right !important;\">Change/Balance:</td>
                                    <td class=\"itemtext\" style=\"text-align:right !important;\"><strong>" . number_format($changeBals, 2) . "</strong></td>
                          </tr>";
$html .= "</table>
                        </div>
                        <!--End Table-->
                        <div id=\"legalcopy\" style=\"text-align:center !important;\">
                            <p class=\"legal\" style=\"font-style:italic;font-family: \"Times New Roman\", Times, serif;\"><strong>Thank you for your business!</strong> $payRemarks. </p>
                            <p class=\"legal\" style=\"font-style:italic;font-family: \"Times New Roman\", Times, serif;\">NB: Goods sold are not Returnable!</p>
                        </div>
                    </div><!--End InvoiceBot-->
      </div><!--End Invoice-->
    </body>
</html>";
echo urlencode($html);
?>

