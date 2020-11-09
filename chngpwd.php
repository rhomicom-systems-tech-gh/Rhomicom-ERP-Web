<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    //if ($lgn_num > 0) {  //echo $lgn_num."-LGN_NUM-".$error;
    $rdonly = "readonly=\"readonly\"";
    $hiddenstyle = "";
    $ignoreHint = "";
    $clss = "";
    $star = "*";
    if (test_prmssns($dfltPrvldgs[16], "System Administration")) {
        $rdonly = "";
        $clss = "style=\"background-color:#fff;\"";
        $star = "";
    }

    $sPrsnNm = "";
    $sUNAME = "";
    if ($gUNM != '') {
        $sPrsnNm = getPrsnFullNm(getUserPrsnID($gUNM));
        $sUNAME = $gUNM;
        $rdonly = "readonly=\"readonly\"";
        $hiddenstyle = "style=\"display:none;\"";
        $ignoreHint = "return false;";
    } else {
        $sPrsnNm = $_SESSION['PRSN_FNAME'];
        $sUNAME = $_SESSION['UNAME'];
    }
    ?>
    <link rel="STYLESHEET" type="text/css" href="cmn_scrpts/loginStyles.css"/>
    <script type="text/javascript">
        function showHint(str, destElmntID, linkArgs)
        {
            <?php echo $ignoreHint; ?>
            var xmlhttp;
            //alert(str);
            if (str.length === 0)
            {
                document.getElementById(destElmntID).innerHTML = "";
                return;
            }
            if (window.XMLHttpRequest)
            {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else
            {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function ()
            {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
                {
                    document.getElementById(destElmntID).innerHTML = xmlhttp.responseText;
                } else
                {
                    document.getElementById(destElmntID).innerHTML = "";
                }
            };
            xmlhttp.open("POST", "index.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(linkArgs + "&in_val=" + str);
        }

        function enterKeyFunc(e)
        {
            var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
            if (charCode == 13) {
                chngePswdPage('do_change');
            }
        }

        function chngePswdPage(str_Cmd)
        {
            //alert("test" + myCountry() + myIP());
            var xmlhttp;
            var usrNm = "";
            var old_pswd = "";
            var new_pswd = "";
            var cnfm_pswd = "";
            var lnkArgs = "";
            var machdet = "";
            usrNm = document.getElementById("usrnm").value;
            old_pswd = document.getElementById("oldpwd").value;
            new_pswd = document.getElementById("newpwd").value;
            cnfm_pswd = document.getElementById("cnfrmpwd").value;
            machdet = document.getElementById("machdet").value;

            if (usrNm === "" || usrNm === null)
            {
                $('#modal-7 .modal-body').html('User Name cannot be empty!');
                $('#modal-7').modal('show', {backdrop: 'static'});
                //alert('User Name cannot be empty!');
                return;
            }
            if (str_Cmd == 'do_change')
            {
                if (new_pswd === "" || new_pswd === null)
                {
                    $('#modal-7 .modal-body').html('New Password cannot be empty!');
                    $('#modal-7').modal('show', {backdrop: 'static'});
                    //alert('New Password cannot be empty!');
                    return;
                }
                if (cnfm_pswd === "" || cnfm_pswd === null)
                {
                    $('#modal-7 .modal-body').html('Confirm Password cannot be empty!');
                    $('#modal-7').modal('show', {backdrop: 'static'});
                    //alert('Confirm Password cannot be empty!');
                    return;
                }
                if (new_pswd !== cnfm_pswd)
                {
                    $('#modal-7 .modal-body').html('New Passwords must be the same!');
                    $('#modal-7').modal('show', {backdrop: 'static'});
                    //alert('New Passwords must be the same!');
                    return;
                }
                lnkArgs = "grp=1&typ=7&username=" + usrNm + "&oldpassword=" +
                        old_pswd + "&newpassword=" + new_pswd + "&rptpassword="
                        + cnfm_pswd + "&q=" + str_Cmd + "&machdet=" + machdet + "&screenwdth=" + screen.width;
            } else if (str_Cmd == 'send_link')
            {
                lnkArgs = "grp=1&typ=11&q=SendPswdLnk&in_val=" + usrNm;
            } else
            {
                lnkArgs = "grp=1&typ=11&q=Change Password Auto&in_val=" + usrNm;
            }
            if (window.XMLHttpRequest)
            {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else
            {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function ()
            {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
                {
                    var rspns = xmlhttp.responseText;
                    document.getElementById("fullName").innerHTML = rspns;
                    if (document.getElementById("usrnm").readOnly
                            && rspns.indexOf("Successfully") >= 0) {
                        if (rspns.indexOf('self') > -1)
                        {
                            window.location = 'self/index.php';
                        } else {
                            window.location = "index.php";
                        }
                    }
                } else
                {
                    document.getElementById("fullName").innerHTML = "Changing Password...Please Wait...";
                }
            };

            xmlhttp.open("POST", "index.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xmlhttp.send(lnkArgs);//+ "&machdetls=" + machDet

        }
    </script>
    </head>
    <?php /* flush();  -webkit-calc(87vh);height: -moz-calc(87vh);height: calc(87vh) */ ?>
    <body style="<?php echo $bckcolorsChngPwd; ?>min-width:360px;min-height:430px;height:100% !important;width:100% !important;">
        <div class="container-fluid" >
            <div class="row" style="min-height:65px;max-height:70px !important;height: 65px;border-bottom:0px solid #FFF;padding:0px;background-color: rgba(0,0,0,0.32);">
                <div style="max-width:25%;float:left;"><img src="cmn_images/<?php echo $app_image1; ?>" style="left: 0.5%; margin:2px; padding-right: 1em; height:60px; width:auto; position: relative; vertical-align: middle;"></div>
                <div class="hdrDiv" style="max-width:90%;color:#FFF;text-align:center;float:none;">
                    <span class="h4"><?php echo $app_name; ?></span><br/>
                    <span class="h6"><?php echo $app_slogan; ?></span>
                </div>
            </div>
            <div class="row" style="min-height:415px;height: 100% !important;background-color: rgba(0,0,0,0.22);">
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4">
                    <div class="center-block" id="loginDiv">
                        <div class="login-panel panel panel-default login" style="max-width: 370px !important;">
                            <h3 class="panel-title logintitle"><img src="cmn_images/change-password.png" style="float:left;height:40px;margin-left: 10px;"/>CHANGE ACCOUNT PASSWORD</h3>
                            <form method="post" action="" onSubmit="return false;" style="width:100%;padding:10px;" autocomplete="off">
                                <p class="">
                                    <label id="fullName" style="color:green;margin-left:10px;font-size: 15px;font-weight: bold;">
                                        <?php echo $sPrsnNm; ?>
                                    </label>
                                </p>
                                <br/>
                                <div class="form-group">
                                    <div class="input-group margin-bottom-sm">
                                        <span class="input-group-addon"><i class="fa fa-user fa-fw fa-border"></i></span>
                                        <input class="form-control"  autocomplete="off" type="text" id="usrnm" name="usrnm" value="<?php echo $sUNAME; ?>" placeholder="<?php echo $placeHolder1; ?>" <?php echo $rdonly; ?>
                                               onchange="showHint(this.value, 'fullName', 'grp=1&typ=11&q=Users Full Name');"
                                               onkeyup="showHint(this.value, 'fullName', 'grp=1&typ=11&q=Users Full Name');"
                                               onblur="showHint(this.value, 'fullName', 'grp=1&typ=11&q=Users Full Name');" />
                                    </div>
                                </div>
                                <?php
                                if ($lgn_num > 0) {
                                    if (test_prmssns($dfltPrvldgs[17], "System Administration")) {
                                        ?>
                                        <p class="others">
                                            <button type="button" name="sendLink" class="btn btn-md btn-default btn-block otherButton" onclick="window.location = 'index.php?cp=2';">Show Configuration File</button>
                                        </p>
                                        <p class="others">
                                            <button type="button" name="sendLink" class="btn btn-md btn-default btn-block otherButton" onclick="chngePswdPage('send_link');">Send Password Reset Link</button>
                                        </p>
                                        <p class="others">
                                            <button type="button" name="genPwd" class="btn btn-md btn-default btn-block otherButton" onclick="chngePswdPage('auto_chng');">Auto-Generate & Send Password</button>
                                        </p>
                                        <?php
                                    }
                                }
                                ?>
                                <br/>
                                <div class="form-group" <?php echo $hiddenstyle; ?>>
                                    <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-key fa-fw fa-border"></i></span>
                                        <input class="form-control" placeholder="Current Password<?php echo $star; ?>" id="oldpwd" name="oldpwd" type="password" value=""  onkeyup="enterKeyFunc(event);" <?php echo $clss; ?>>
                                    </div>
                                    <input type="hidden" name="machdet"  id="machdet" value="Unknown"/>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-key fa-fw fa-border"></i></span>
                                        <input class="form-control" placeholder="New Password" id="newpwd" name="newpwd" type="password" value="" onkeyup="enterKeyFunc(event);">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-key fa-fw fa-border"></i></span>
                                        <input class="form-control" placeholder="Confirm Password" id="cnfrmpwd" name="cnfrmpwd" type="password" value=""  onkeyup="enterKeyFunc(event);">
                                    </div>
                                </div>

                                <p class="label"><label style="color:red;text-align: center;">
                                        &nbsp;<?php echo str_replace('%21%0A', ' ', urldecode($error)); ?>
                                    </label>
                                </p>
                                <p class="others">
                                    <button type="button" name="commit" class="btn btn-md btn-default btn-block otherButton" onclick="chngePswdPage('do_change');">Change Password</button>
                                </p>
                                <p class="others">
                                    <button type="button" name="cancel" class="btn btn-md btn-default btn-block otherButton" onclick="window.location = 'index.php';">Return to Home Page</button>
                                </p>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">&nbsp;</div>
            </div>
            <div class="row" style="min-height:25px;height: -webkit-calc(18vh);height: -moz-calc(18vh);height: calc(18vh);background-color: rgba(0,0,0,0.22);">
                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </div>
            <div class="row" style="min-height:25px;height: 25px;background-color: rgba(0,0,0,0.32);">
                <div class="col-md-12" style="color:#FFF;font-family: Times;font-style: italic;font-size:12px;text-align:center;border-top:1px solid #999;">
                    <p class="rho-page-footer">Copyright &COPY; <?php echo date('Y'); ?> <a style="color:#FFF" href="<?php echo $about_url; ?>" target="_blank"><?php echo $app_org; ?></a>.</p>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-7" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <span class="modal-title msgtitle">System Alert!</span>
                    </div>
                    <div class="modal-body">
                        Content is loading...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery -->
        <script src="cmn_scrpts/jquery-1.11.3.min.js"></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="cmn_scrpts/bootstrap337/js/bootstrap.min.js"></script>
    </body>
    </html>
    <?php }
?>
