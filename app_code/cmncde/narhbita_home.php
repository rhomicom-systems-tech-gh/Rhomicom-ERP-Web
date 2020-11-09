<?php
/* $dte1=DateTime::createFromFormat('d-M-Y H:i:s','01-Jan-2019 12:14:56');
  echo DateTime::createFromFormat('d-M-Y H:i:s', $dte1->format('d-M-Y')." 00:00:00")->modify('+1 day')->format('d-M-Y H:i:s')."<br/>";
  echo DateTime::createFromFormat('d-M-Y H:i:s', $dte1->format('d-M-Y')." 00:00:00")->modify('+1 month')->format('d-M-Y H:i:s')."<br/>";
  echo DateTime::createFromFormat('d-M-Y H:i:s', $dte1->format('d-M-Y H:i:s'))->modify('+1 hour')->format('d-M-Y H:i:s')."<br/>"; */
$qstr = "";
$dsply = "";
$actyp = "";
$PKeyID = -1;
$vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "0";
$usrID = $_SESSION['USRID'];
$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$error = "";
$searchAll = true;

$srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
$srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'All';
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Published";
$isMaster = isset($_POST['isMaster']) ? cleanInputData($_POST['isMaster']) : "0";
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
    $srchFor = str_replace("%%", "%", $srchFor);
}

if (isset($_POST['vtyp'])) {
    $vwtyp = cleanInputData($_POST['vtyp']);
}

if (isset($_POST['actyp'])) {
    $actyp = cleanInputData($_POST['actyp']);
}

$qStrtDte = "";
$qEndDte = "";
$artCategory = "";
$isMaster = "0";

if (isset($_POST['qStrtDte'])) {
    $qStrtDte = cleanInputData($_POST['qStrtDte']);
    if (strlen($qStrtDte) == 19) {
        $qStrtDte = substr($qStrtDte, 0, 10) . " 00:00:00";
    } else {
        $qStrtDte = "";
    }
}

if (isset($_POST['qEndDte'])) {
    $qEndDte = cleanInputData($_POST['qEndDte']);
    if (strlen($qEndDte) == 19) {
        $qEndDte = substr($qEndDte, 0, 10) . " 23:59:59";
    } else {
        $qEndDte = "";
    }
}

if (isset($_POST['artCategory'])) {
    $artCategory = cleanInputData($_POST['artCategory']);
}
$canViewSelfsrvc = test_prmssns("View Self-Service", "Self Service");
$canViewEvote = test_prmssns("View e-Voting", "e-Voting") || test_prmssns("View Elections", "Self Service");
//$canViewElearn = test_prmssns("View e-Learning", "e-Learning") || test_prmssns("View e-Learning", "Self Service");
$canViewAcntng = test_prmssns("View Accounting", "Accounting");
$canViewPrsn = test_prmssns("View Person", "Basic Person Data");
$canViewIntrnlPay = test_prmssns("View Internal Payments", "Internal Payments");
$canViewSales = test_prmssns("View Inventory Manager", "Stores And Inventory Manager");
//$canViewVsts = test_prmssns("View Visits and Appointments", "Visits and Appointments");
$canViewEvnts = test_prmssns("View Events And Attendance", "Events And Attendance");
$canViewHotel = test_prmssns("View Hospitality Manager", "Hospitality Management");
$canViewClnc = test_prmssns("View Clinic/Hospital", "Clinic/Hospital");
$canViewBnkng = test_prmssns("View Banking", "Banking");
$canViewPrfmnc = test_prmssns("View Learning/Performance Management", "Learning/Performance Management");
$canViewProjs = test_prmssns("View Projects Management", "Projects Management");
$canViewVMS = test_prmssns("View Vault Management", "Vault Management");
//$canViewAgnt = test_prmssns("View Agent Registry", "Agent Registry");
//$canViewATrckr = test_prmssns("View Asset Tracking", "Asset Tracking");
$canViewSysAdmin = test_prmssns("View System Administration", "System Administration");
//$canViewOrgStp = test_prmssns("View Organization Setup", "Organization Setup");
//$canViewLov = test_prmssns("View General Setup", "General Setup");
//$canViewWkf = test_prmssns("View Workflow Manager", "Workflow Manager");
//$canViewArtclAdmn = test_prmssns("View Notices Admin", "System Administration");
$canViewRpts = test_prmssns("View Reports And Processes", "Reports And Processes");
?>
<div class="row">
    <div class="col-md-12">
        <!-- Carousel-->
        <?php
        $showSliderID = getEnbldPssblValID("Show Home Page Slider", getLovID("All Other General Setups"));
        $showSlider = getPssblValDesc($showSliderID);
        if (strtoupper($showSlider) == "YES") {
            ?>
            <div class="row">
                <div class="col-md-12" style="padding:0px 16px 0px 15px;margin-top:1px;">
                    <div id="myCarousel" class="carousel slide" data-ride="carousel" style="border: 1px solid #ccc;border-radius: 2px;padding:0px;background-color:#fff;">
                        <ol class="carousel-indicators">
                            <?php
                            $total1 = get_SliderNoticeTtls($srchFor, $srchIn);
                            if ($pageNo > ceil($total1 / $lmtSze)) {
                                $pageNo = 1;
                            } else if ($pageNo < 1) {
                                $pageNo = ceil($total1 / $lmtSze);
                            }

                            $curIdx1 = $pageNo - 1;
                            $result1 = get_SliderNotices($srchFor, $srchIn, $curIdx1, $lmtSze, $sortBy);
                            $cntr1 = 0;
                            $ttlRecs1 = loc_db_num_rows($result1);
                            $isactive = "active";
                            $sliderCntnt = array();

                            while ($row1 = loc_db_fetch_array($result1)) {
                                if ($cntr1 > 0) {
                                    $isactive = "";
                                }
                                array_push($sliderCntnt, str_replace("{:articleID}", $row1[0], $row1[13]));
                                ?>
                                <li data-target="#myCarousel" data-slide-to="<?php echo $cntr1; ?>" class="<?php echo $isactive; ?>"></li>
                                <?php
                                $cntr1 += 1;
                            }
                            ?>
                        </ol>
                        <div class="carousel-inner" role="listbox">
                            <?php
                            $isactive1 = "active";
                            for ($i = 0; $i < count($sliderCntnt); $i++) {
                                if ($i > 0) {
                                    $isactive1 = "";
                                }
                                ?>                        
                                <div class="item <?php echo $isactive1; ?>">
                                    <?php echo $sliderCntnt[$i]; ?>
                                </div>                        
                                <?php
                            }
                            ?>
                            <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12" style="padding:0px 14px 0px 15px;margin-top:5px;">
                <div class="introMsg" style="">Welcome, <span style="color:blue;font-weight:bold;"><?php echo strtoupper(getPrsnFullNm($prsnid) . " (" . getPersonLocID($prsnid) . ")"); ?></span> to the <?php echo $app_name; ?>!</div>
            </div>
        </div>
        <div class="row" style="padding:1px 1px 0px 1px;margin: -1px -15px 0px -15px;">
            <div class="col-lg-3" style="padding:0px 13px 0px 15px;">
                <?php
                if ($canViewSelfsrvc) {
                    $customMdlNm = getEnbldPssblValDesc("Basic Person Data", getLovID("Customized Module Names"));
                    if (trim($customMdlNm) == "") {
                        $customMdlNm = "Personal Records!";
                    }
                    ?>
                    <div class="panel" style="color:white;background-color:#5cb85c;border-radius:5px;border:1px solid #5cb85c;">
                        <a href="javascript:openATab('#allmodules', 'grp=8&typ=1');">
                            <div class="panel-heading" style="color:white;background-color:#5cb85c;border-radius:5px;border:1px solid #5cb85c;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-user fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div><?php echo $customMdlNm; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer" style="color:white;background-color:#5cb85c;border-top:1px solid #5cb85c;">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="panel" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;border:1px solid <?php echo $bckcolorOnly1; ?>;">
                        <a href="javascript:openATab('#allmodules', 'grp=40&typ=5');">
                            <div class="panel-heading" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-ban fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div>No Access!</div>
                                    </div>
                                </div>
                            </div><!--border:1px solid <?php echo $bckcolorOnly1; ?>;-->
                            <div class="panel-footer" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-top:1px solid white;">
                                <span class="pull-left"></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <div class="col-lg-3" style="padding:0px 13px 0px 15px;">                
                <?php if ($canViewClnc) { ?>
                    <div class="panel panel-default" style="color:white;background-color:#337ab7;border-radius:5px;border:1px solid #337ab7;" >
                        <a href="javascript:openATab('#allmodules', 'grp=14&typ=1&mdl=Clinic/Hospital');">
                            <div class="panel-heading" style="color:white;background-color:#337ab7;border-radius:5px;border:1px solid #337ab7;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-money fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div>Clinic/Hospital</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer" style="color:white;background-color:#337ab7;border-top:1px solid #337ab7;">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>                
                    <div class="panel" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;border:1px solid <?php echo $bckcolorOnly1; ?>;">
                        <a href="javascript:openATab('#allmodules', 'grp=40&typ=5');">
                            <div class="panel-heading" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-ban fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div>No Access!</div>
                                    </div>
                                </div>
                            </div><!--border:1px solid <?php echo $bckcolorOnly1; ?>;-->
                            <div class="panel-footer" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-top:1px solid white;">
                                <span class="pull-left"></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <div class="col-lg-3" style="padding:0px 13px 0px 15px;">                
                <?php if ($canViewRpts) { ?>
                    <div class="panel panel-default" style="color:white;background-color:#f0ad4e;border-radius:5px;border:1px solid #f0ad4e;">
                        <a href="javascript:openATab('#allmodules', 'grp=9&typ=1');">
                            <div class="panel-heading" style="color:white;background-color:#f0ad4e;border-radius:5px;border:1px solid #f0ad4e;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-gear fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div>Reports & Processes!</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer" style="color:white;background-color:#f0ad4e;border-top:1px solid #f0ad4e;">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="panel" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;border:1px solid <?php echo $bckcolorOnly1; ?>;">
                        <a href="javascript:openATab('#allmodules', 'grp=40&typ=5');">
                            <div class="panel-heading" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-ban fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div>No Access!</div>
                                    </div>
                                </div>
                            </div><!--border:1px solid <?php echo $bckcolorOnly1; ?>;-->
                            <div class="panel-footer" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-top:1px solid white;">
                                <span class="pull-left"></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <div class="col-lg-3" style="padding:0px 13px 0px 15px;">
                <div class="panel panel-default" style="color:white;background-color:#d9534f;border-radius:5px;border:1px solid #d9534f;">
                    <a href="javascript:openATab('#allmodules', 'grp=40&typ=5');">
                        <div class="panel-heading" style="color:white;background-color:#d9534f;border-radius:5px;border:1px solid #d9534f;">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-desktop fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">&nbsp;</div>
                                    <div class="huge">13</div>
                                    <div>Apps/Modules!</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer" style="color:white;background-color:#d9534f;border-top:1px solid #d9534f;">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row" style="padding:1px 1px 0px 1px;margin: -1px -15px 0px -15px;">
            <div class="col-lg-3" style="padding:0px 13px 0px 15px;">                              
                <?php if ($canViewAcntng) { ?>
                    <div class="panel panel-default" style="color:black;border-radius:5px;border:1px solid #5cb85c;">
                        <a href="javascript:openATab('#allmodules', 'grp=6&typ=1');">
                            <div class="panel-heading" style="color:black;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-book fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div>Accounting!</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer" style="color:white;background-color:#5cb85c;border-top:1px solid #5cb85c;">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="panel" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;border:1px solid <?php echo $bckcolorOnly1; ?>;">
                        <a href="javascript:openATab('#allmodules', 'grp=40&typ=5');">
                            <div class="panel-heading" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-ban fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div>No Access!</div>
                                    </div>
                                </div>
                            </div><!--border:1px solid <?php echo $bckcolorOnly1; ?>;-->
                            <div class="panel-footer" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-top:1px solid white;">
                                <span class="pull-left"></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <div class="col-lg-3" style="padding:0px 13px 0px 15px;">
                <?php if ($canViewSales) {
                    $customMdlNm = getEnbldPssblValDesc("Sales and Inventory", getLovID("Customized Module Names"));
                    if (trim($customMdlNm) == "") {
                        $customMdlNm = "Sales/Inventory!";
                    } ?>
                    <div class="panel panel-default" style="color:black;border-radius:5px;border:1px solid #337ab7;" >
                        <a href="javascript:openATab('#allmodules', 'grp=12&typ=1');">
                            <div class="panel-heading" style="color:black;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-shopping-basket fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div><?php echo $customMdlNm; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer" style="color:white;background-color:#337ab7;border-top:1px solid #337ab7;">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="panel" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;border:1px solid <?php echo $bckcolorOnly1; ?>;">
                        <a href="javascript:openATab('#allmodules', 'grp=40&typ=5');">
                            <div class="panel-heading" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-ban fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div>No Access!</div>
                                    </div>
                                </div>
                            </div><!--border:1px solid <?php echo $bckcolorOnly1; ?>;-->
                            <div class="panel-footer" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-top:1px solid white;">
                                <span class="pull-left"></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <div class="col-lg-3" style="padding:0px 13px 0px 15px;">
                <?php if (true) { ?>
                    <div class="panel panel-default" style="color:black;border-radius:5px;border:1px solid #f0ad4e;">
                        <a href="javascript:openATab('#allmodules', 'grp=40&typ=4');">
                            <div class="panel-heading" style="color:black;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="cmn_images/dashboard220.png" style="margin:1px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;">
                                        <!--<i class="fa fa-university fa-5x"></i>-->
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div>Summary Dashboard!</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer" style="color:white;background-color:#f0ad4e;border-top:1px solid #f0ad4e;">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="panel" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;border:1px solid <?php echo $bckcolorOnly1; ?>;">
                        <a href="javascript:openATab('#allmodules', 'grp=40&typ=5');">
                            <div class="panel-heading" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-ban fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div>No Access!</div>
                                    </div>
                                </div>
                            </div><!--border:1px solid <?php echo $bckcolorOnly1; ?>;-->
                            <div class="panel-footer" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-top:1px solid white;">
                                <span class="pull-left"></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <div class="col-lg-3" style="padding:0px 13px 0px 15px;">
                <?php if ($canViewEvnts) {
                    $customMdlNm = getEnbldPssblValDesc("Events and Attendance", getLovID("Customized Module Names"));
                    if (trim($customMdlNm) == "") {
                        $customMdlNm = "Events/Attendance!";
                    } ?>
                    <div class="panel panel-default" style="color:black;border-radius:5px;border:1px solid #d9534f;">
                        <a href="javascript:openATab('#allmodules', 'grp=16&typ=1');">
                            <div class="panel-heading" style="color:black;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-calendar fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">13</div>
                                        <div><?php echo $customMdlNm; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer" style="color:white;background-color:#d9534f;border-top:1px solid #d9534f;">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="panel" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;border:1px solid <?php echo $bckcolorOnly1; ?>;">
                        <a href="javascript:openATab('#allmodules', 'grp=40&typ=5');">
                            <div class="panel-heading" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-radius:5px;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-ban fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div class="huge">1</div>
                                        <div>No Access!</div>
                                    </div>
                                </div>
                            </div><!--border:1px solid <?php echo $bckcolorOnly1; ?>;-->
                            <div class="panel-footer" style="color:<?php echo $bckcolorOnly1; ?>;background-color:white;border-top:1px solid white;">
                                <span class="pull-left"></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!--
        <div class="row">
            <div class="col-md-12" style="padding:0px 1px 0px 1px;margin-top: -20px;">
                <div class="col-md-3">
                    <button type="button" class="btn btn-default btn-lg btn-block otherButton" onclick="openATab('#allmodules', 'grp=8&typ=1');">
                        <img src="cmn_images/person.png" style="margin:5px; padding-right: 1em; height:55px; width:auto; position: relative; vertical-align: middle;float:left;">
                        <span class="wordwrap1"> Personal Records</span>
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-default btn-lg btn-block otherButton" onclick="openATab('#allmodules', 'grp=19&typ=10');">
                        <img src="cmn_images/election.png" style="margin:5px; padding-right: 1em; height:55px; width:auto; position: relative; vertical-align: middle;float:left;">
                        <span class="wordwrap1"> Elections Centre</span>
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-default btn-lg btn-block otherButton" onclick="openATab('#allmodules', 'grp=40&typ=4');">
                        <img src="cmn_images/dashboard220.png" style="margin:5px; padding-right: 1em; height:55px; width:auto; position: relative; vertical-align: middle;float:left;">
                        <span class="wordwrap1"> Summary Dashboard</span>
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-default btn-lg btn-block otherButton" onclick="openATab('#allmodules', 'grp=40&typ=5');">
                        <img src="cmn_images/Home.png" style="margin:5px; padding-right: 1em; height:55px; width:auto; position: relative; vertical-align: middle;float:left;">
                        <span class="wordwrap1"> All Other Modules</span>
                    </button>
                </div>
            </div>
        </div>
        -->
        <?php
        $ltstNwsID = getLtstNoticeID("Latest News");
        $artBody = getNoticeIntroMsg($ltstNwsID);
        ?>
        <div class="jumbotron" style="border: 1px solid #ddd;border-radius: 2px;margin-top: -10px;">
            <div class="container-fluid">
                <!-- Example row of columns -->
                <div class="row">
                    <div class="col-md-6">
                        <div style="">
                            <p style="width:100% !important;min-width:100% !important;"><?php echo $artBody; ?></p>  
                        </div>
                    </div>
                    <?php
                    $usfulLinksID = getLtstNoticeID("Useful Links");
                    $artBody1 = getNoticeIntroMsg($usfulLinksID);
                    ?>
                    <div class="col-md-6">
                        <div style="">
                            <p style="width:100% !important;min-width:100% !important;"><?php echo $artBody1; ?></p>                                
                        </div>
                    </div>
                </div>
            </div> 
        </div> 

        <?php
        $total = get_NoticeTtls($srchFor, $srchIn);
        if ($pageNo > ceil($total / $lmtSze)) {
            $pageNo = 1;
        } else if ($pageNo < 1) {
            $pageNo = ceil($total / $lmtSze);
        }

        $curIdx = $pageNo - 1;
        $result = get_Notices($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
        ?>                  
        <div class="row" style="padding:0px 15px 0px 15px !important;">
            <?php
            $cntr = 0;
            $grpcntr = 0;
            $ttlRecs = loc_db_num_rows($result);
            while ($row = loc_db_fetch_array($result)) {
                $cntr += 1;
                if ($cntr == 1) {
                    ?>
                    <div class="jumbotron" style="border: 1px solid #ddd;border-radius: 2px;">
                        <div class="container-fluid">
                            <h2><a href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" style=""><i class="fa fa-certificate" aria-hidden="true"></i> <?php echo $row[2]; ?></a></h2>
                            <p style="width:100% !important;min-width:100% !important;"><?php echo $row[13]; ?></p>
                            <p><a class="btn btn-primary" href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" role="button">View Full Article &raquo;</a></p>
                        </div>
                    </div>  
                    <?php
                } else {
                    if ($grpcntr == 0) {
                        ?>
                        <div class="" style="border: 1px solid #ddd;border-radius: 2px;margin-bottom: 10px;">
                            <div class="container-fluid">
                                <div class="row">          
                                    <?php
                                }
                                ?>
                                <div class="col-md-4">
                                    <div style="">
                                        <h2><a href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" style=""><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <?php echo $row[2]; ?></a></h2>
                                        <p style="width:100% !important;min-width:100% !important;"><?php echo $row[13]; ?></p>
                                        <p><a class="btn btn-default" href="javascript: showArticleDetails(<?php echo $row[0]; ?>,'<?php echo $row[1]; ?>');" role="button">View details &raquo;</a></p>
                                    </div>
                                </div>
                                <?php
                                if ($grpcntr == 2 || $cntr == $ttlRecs) {
                                    ?>
                                </div>
                            </div> 
                        </div>
                        <?php
                        $grpcntr = 0;
                    } else {
                        $grpcntr = $grpcntr + 1;
                    }
                }
            }
            ?>
        </div>
    </div>
    <!-- /container --> 
</div>

<?php

function get_Notices($searchFor, $searchIn, $offset, $limit_size, $ordrBy) {
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;

    $extrWhr = "";
    $ordrByCls = "ORDER BY tbl1.article_id DESC";
    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (tbl1.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    $extrWhr .= " AND (tbl1.is_published = '1') 
            and (tbl1.article_category NOT IN ('Latest News','Useful Links','System Help', 'Forum Topic','Chat Room','Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,tbl1.allowed_group_id, tbl1.allowed_group_type)>0)";
    $wherecls = " AND (tbl1.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or tbl1.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $wherecls .= " AND (tbl1.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $wherecls .= " AND (tbl1.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }
    if ($ordrBy == "Date Published") {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    } else if ($ordrBy == "No. of Hits") {
        $ordrByCls = "ORDER BY tbl1.hits DESC";
    } else if ($ordrBy == "Category") {
        $ordrByCls = "ORDER BY tbl1.article_category ASC";
    } else if ($ordrBy == "Title") {
        $ordrByCls = "ORDER BY tbl1.article_header ASC";
    } else {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    }
    $sqlStr = "SELECT tbl1.* FROM (SELECT a.article_id, a.article_category, a.article_header, a.header_url, a.article_body,  
       a.is_published, a.publishing_date, a.author_name, a.author_email, prs.get_prsn_loc_id(a.author_prsn_id), 
       (select count(distinct b.created_by) from self.self_articles_hits b where a.article_id = b.article_id) hits,
       a.allowed_group_type, a.allowed_group_id, a.article_intro_msg  
  FROM self.self_articles a) tbl1  
WHERE (1=1" . $extrWhr . "$wherecls) $ordrByCls LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_NoticeTtls($searchFor, $searchIn) {
//global $usrID;
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;
    $extrWhr = "";

    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (a.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    $extrWhr .= " AND (a.is_published = '1') 
            and (a.article_category NOT IN ('Latest News','Useful Links','System Help', 'Forum Topic','Chat Room','Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,a.allowed_group_id, a.allowed_group_type)>0)";
    $wherecls = " AND (a.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or a.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $qStrtDte = cnvrtDMYTmToYMDTm($qStrtDte);
        $wherecls .= " AND (a.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $qEndDte = cnvrtDMYTmToYMDTm($qEndDte);
        $wherecls .= " AND (a.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }

    $sqlStr = "SELECT count(1) 
  FROM self.self_articles a 
  WHERE (1=1" . $extrWhr . "$wherecls)";
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_SliderNotices($searchFor, $searchIn, $offset, $limit_size, $ordrBy) {
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;

    $extrWhr = "";
    $ordrByCls = "ORDER BY tbl1.article_id DESC";
    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (tbl1.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    $extrWhr .= " AND (tbl1.is_published = '1') 
            and (tbl1.article_category IN ('Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,tbl1.allowed_group_id, tbl1.allowed_group_type)>0)";
    $wherecls = " AND (tbl1.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or tbl1.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR tbl1.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $wherecls .= " AND (tbl1.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $wherecls .= " AND (tbl1.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }
    if ($ordrBy == "Date Published") {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    } else if ($ordrBy == "No. of Hits") {
        $ordrByCls = "ORDER BY tbl1.hits DESC";
    } else if ($ordrBy == "Category") {
        $ordrByCls = "ORDER BY tbl1.article_category ASC";
    } else if ($ordrBy == "Title") {
        $ordrByCls = "ORDER BY tbl1.article_header ASC";
    } else {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    }
    $sqlStr = "SELECT tbl1.* FROM (SELECT a.article_id, a.article_category, a.article_header, a.header_url, a.article_body,  
       a.is_published, a.publishing_date, a.author_name, a.author_email, prs.get_prsn_loc_id(a.author_prsn_id), 
       (select count(distinct b.created_by) from self.self_articles_hits b where a.article_id = b.article_id) hits,
       a.allowed_group_type, a.allowed_group_id, a.article_intro_msg  
  FROM self.self_articles a) tbl1  
WHERE (1=1" . $extrWhr . "$wherecls) $ordrByCls LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_SliderNoticeTtls($searchFor, $searchIn) {
//global $usrID;
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;
    $extrWhr = "";

    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (a.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    $extrWhr .= " AND (a.is_published = '1') 
            and (a.article_category IN ('Slider')) 
            and (org.does_prsn_hv_crtria_id($prsnid,a.allowed_group_id, a.allowed_group_type)>0)";
    $wherecls = " AND (a.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
            . "or a.header_url ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_category ilike '" . loc_db_escape_string($searchFor) .
            "' OR a.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $qStrtDte = cnvrtDMYTmToYMDTm($qStrtDte);
        $wherecls .= " AND (a.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $qEndDte = cnvrtDMYTmToYMDTm($qEndDte);
        $wherecls .= " AND (a.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }

    $sqlStr = "SELECT count(1) 
  FROM self.self_articles a 
  WHERE (1=1" . $extrWhr . "$wherecls)";
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getLtstNoticeID($articleCtgry) {
    $sqlStr = "select article_id from self.self_articles "
            . "where is_published='1' and (now() >= to_timestamp(publishing_date,'YYYY-MM-DD HH24:MI:SS')) "
            . "and article_category = '" . loc_db_escape_string($articleCtgry)
            . "' ORDER BY publishing_date DESC LIMIT 1 OFFSET 0";
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_OneNotice($articleID) {
    $sqlStr = "SELECT a.article_id,
            a.article_category,
            a.article_header,
            a.header_url,
            a.article_body,
            a.is_published,
            to_char(to_timestamp(a.publishing_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') publishing_date,
            CASE WHEN a.author_prsn_id>0 THEN prs.get_prsn_name(a.author_prsn_id) ELSE a.author_name END author_name,
            a.author_email,
            prs.get_prsn_loc_id(a.author_prsn_id),
            (select count(distinct b.created_by) from self.self_articles_hits b where a.article_id = b.article_id) hits,
            a.article_intro_msg,
            a.allowed_group_type,
            a.allowed_group_id,
            org.get_criteria_name(a.allowed_group_id, a.allowed_group_type) group_name,
            a.local_classification
            FROM self.self_articles a
            WHERE article_id = " . $articleID;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getNoticeIntroMsg($articleID) {
    $sqlStr = "select article_intro_msg from self.self_articles where article_id = " . $articleID;
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        $artBody = str_replace("{:articleID}", $articleID, $row[0]);
        return $artBody;
    }
    return "";
}
?>