<?php

$menuItems = array("Self-Service Managers","Load Requirements");
$menuImages = array("lov_16x16.gif", "bb_flow.gif", "openfileicon.png", "98.png");
$vwtyp1 = 0;
//echo $vwtyp1;
if (isset($_REQUEST['vwtyp']))
    $vwtyp1 = $_REQUEST['vwtyp'];
//echo $vwtyp1;
if ($vwtyp1 <= 100) {
    require $base_dir . "rho_header.php";
}
$pgNo = 0;
$grp = 3;
$typ = 1;

$mdlNm = "Self-Service";
$ModuleName = $mdlNm;
if (isset($_REQUEST['pg']))
    $pgNo = $_REQUEST['pg'];
if (isset($_REQUEST['q']))
    $mdlNm = $_REQUEST['q'];
if (isset($_REQUEST['grp']))
    $grp = $_REQUEST['grp'];
if (isset($_REQUEST['typ']))
    $typ = $_REQUEST['typ'];


$dfltPrvldgs = array("View Person","View Basic Person Data","View Self-Service",);
$canview = TRUE; /* test_prmssns($dfltPrvldgs[0], $mdlNm); */

if ($lgn_num > 0 && $canview === true) {
    if ($pgNo == 0) {
        $cntent = "<div id='rho_form' style=\"min-height:150px;\">
            <form id='orgStpForm' action='' method='post' accept-charset='UTF-8'>
                <fieldset style=\"padding:10px 20px 20px 20px;\">
                    <legend>   $mdlNm                
                    </legend>
                    <div style=\"margin-bottom:10px;\">
                    <h2>Welcome to Organisational Setup</h2>
                    </div>                    
                    <div class='rho_form1' style=\"border: 1px solid #999;padding:10px;\" class=\"rho-postcontent rho-postcontent-0 clearfix\">                    
      <h5>FUNCTIONALITIES OF THE SELF-SERVICE MODULE</h5>
      <p>This is where Individuals in the Organisation can view and Edit Information about themselves. 
      The module has the ff areas:";
        $grpcntr = 0;
        for ($i = 0; $i < count($menuItems); $i++) {
            $No = $i + 1;
            if ($grpcntr == 0) {
                $cntent.= "<div style=\"float:left;\">"
                        . "<ul class=\"no_bullet\">";
            }
            $cntent.= "<li class=\"leaf\" style=\"background: url('cmn_images/start.png') no-repeat 3px 4px; background-size:20px 20px;\">"
                    . "<a onclick=\"modules('$mdlNm', $No);\">$menuItems[$i]</a></li>";

            if ($grpcntr == 2) {
                $cntent.= "</ul>"
                        . "</div>";
                $grpcntr = 0;
            } else {
                $grpcntr = $grpcntr + 1;
            }
        }

        $cntent.= " </p>     
    </div>
    </fieldset>
            </form>
        </div>
<div id=\"mydialog\" title=\"Basic dialog\" style=\"display:none;\">
</div>";
        echo $cntent;
        echo "<script type=\"text/javascript\"> 
        load('$mdlNm');
              </script>";
    } else if ($pgNo == 1) {
        require "my_prsnl_data.php";
    } else if ($pgNo == 2) {
        require "my_intnl_pay.php";
    } else if ($pgNo == 3) {
        require "my_pay_docs.php";
    } else if ($pgNo == 4) {
        require "my_htl_books.php";
    } else if ($pgNo == 5) {
        require "my_evnts_attn.php";
    } else if ($pgNo == 6) {
        require "my_acdmc_data.php";
    } else if ($pgNo == 7) {
        require "my_clnc_data.php";
    } else if ($pgNo == 8) {
        require "my_bank_data.php";
    } else if ($pgNo == 9) {
        require "my_surveys.php";
    } else if ($pgNo == 10) {
        require "self_service_setups.php";
    } else {
        restricted();
    }
} else {
    restricted();
}

if ($vwtyp1 <= 100) {
    require $base_dir . 'rho_footer.php';
}
?>
