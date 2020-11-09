<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num <= 0) {  //echo $lgn_num."-LGN_NUM-".$error;
        ?>
        <link rel="STYLESHEET" type="text/css" href="cmn_scrpts/loginStyles.css?v=<?php echo $radomNo; ?>" />
        <script type="text/javascript">
            if ((typeof window.Worker === "function"))
            {
                //Worker Supported
                /*document.addEventListener('contextmenu', function (e) {
                    e.preventDefault();
                });
                document.addEventListener('keydown', function (e) {
                    //alert(e.which);
                    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
                    if (charCode >= 112 && charCode <= 123) {
                        e.preventDefault();
                        return false;
                    }
                });*/
            } else
            {
                window.location = 'notsupported.php';
            }
        </script>
        <script type="text/javascript">
            function enterKeyFunc(e)
            {
                var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
                if (charCode == 13) {
                    homePage();
                }
                //return false;
            }

            function homePage()
            {
                //alert("test" + myCountry() + myIP());
                var xmlhttp;
                var usrNm = "";
                var old_pswd = "";
                var lnkArgs = "";
                var machdet = "";
                usrNm = document.getElementById("usrnm").value;
                old_pswd = document.getElementById("pwd").value;
                machdet = document.getElementById("machdet").value;
                if (usrNm === "" || usrNm === null)
                {
                    $('#modal-7 .modal-body').html('User Name cannot be empty!');
                    $('#modal-7').modal('show', {backdrop: 'static'});
                    //alert('User Name cannot be empty!');
                    return false;
                }
                if (old_pswd === "" || old_pswd === null)
                {
                    $('#modal-7 .modal-body').html('Password cannot be empty!');
                    $('#modal-7').modal('show', {backdrop: 'static'});
                    //alert('Password cannot be empty!');
                    return false;
                }
                lnkArgs = "usrnm=" + usrNm + "&pwd=" + old_pswd + "&machdet=" + machdet + "&screenwdth=" + screen.width;
                if (window.XMLHttpRequest)
                {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else
                {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange = function ()
                {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
                    {
                        var rspns = xmlhttp.responseText;
                        /*alert(xmlhttp.responseText);*/
                        if (rspns.indexOf('change password') > -1
                                || rspns.indexOf('select role') > -1)
                        {
                            window.location = 'index.php';
                        } else
                        {
                            document.getElementById("msgArea").innerHTML = "<span class=\"wordwrap3\" style=\"color:red;font-size:12px;text-align: center;margin-top:0px;\">&nbsp;" + rspns + "</span>";
                        }
                    } else
                    {
                        document.getElementById("msgArea").innerHTML = "<span class=\"wordwrap3\" style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><img style=\"width:145px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;\" src='cmn_images/ajax-loader2.gif'/>Loading...Please Wait...</span>";
                    }
                };
                xmlhttp.open("POST", "index.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(lnkArgs); //+ "&machdetls=" + machDet
            }

            function forgotPwd()
            {
                var xmlhttp;
                var lnkArgs = "q=forgotpwd";
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
                    //var newDoc = document.open("text/html", "replace");
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
                    {
                        var rspns = xmlhttp.responseText;
                        document.getElementById("loginDiv").innerHTML = rspns;
                        //$body.removeClass("mdlloading");
                    } else
                    {
                        document.getElementById("loginDiv").innerHTML = "<p style=\"padding:10px;margin:50px;\"><img style=\"width:80px;height:80px;display:inline;float:none;margin-right:auto;clear: left;\" src='cmn_scrpts/images/ajax-loader7.gif'/></p>";
                    }
                };
                xmlhttp.open("POST", "index.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(lnkArgs); //+ "&machdetls=" + machDet
            }

            function enterKeyFunc1(e)
            {
                var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
                if (charCode == 13) {
                    e.preventDefault();
                    return chngePswdPage1('send_link');
                }
                //return false;
            }

            function chngePswdPage1(str_Cmd)
            {
                //alert("test" + myCountry() + myIP());
                var xmlhttp;
                var usrNm = "";
                var lnkArgs = "";
                //var machdet = "";
                usrNm = document.getElementById("usrnm").value;
                //machdet = document.getElementById("machdet").value;

                if (usrNm === "" || usrNm === null)
                {
                    $('#modal-7 .modal-body').html('User Name cannot be empty!');
                    $('#modal-7').modal('show', {backdrop: 'static'});
                    //alert('User Name cannot be empty!');
                    return false;
                }
                if (str_Cmd == 'send_link')
                {
                    lnkArgs = "grp=1&typ=11&q=SendPswdLnk&in_val=" + usrNm;
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
                        //alert(rspns);
                        document.getElementById("fullName").innerHTML = rspns;
                        return false;
                    } else
                    {
                        //alert('rspns');
                        document.getElementById("fullName").innerHTML = "Sending Password Reset Link...Please Wait...";
                    }
                };
                xmlhttp.open("POST", "index.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(lnkArgs); //+ "&machdetls=" + machDet
                return false;
            }
        </script>
        </head>
        <?php //max-height:70px !important; -webkit-calc(85vh);height: -moz-calc(85vh);height: calc(85vh); -webkit-calc(10vh);height: -moz-calc(10vh);height: calc(10vh)?>
        <body style="<?php echo $bckcolorsChngPwd; ?>min-width:300px;min-height:400px;width:100% !important;height:100% !important;width:100% !important;">
            <div class="modalLdng"></div>
            <div class="modalLdng1"></div>
            <div class="container-fluid">
                <div class="row" style="min-height:65px;height: 100%;border-bottom:0px solid #bbb;padding:0px;background-color: rgba(0,0,0,0.32);">
                    <div class="col-md-6">
                        <div style="max-width:25%;float:left;"><img src="<?php echo $app_image1; ?>" style="left: 0.5%; margin:2px; padding-right: 1em; height:60px; width:auto; position: relative; vertical-align: middle;"></div>
                        <div class="hdrDiv" style="max-width:90%;color:#FFF;text-align:center;float:none;">
                            <span class="h4 wordwrap1"><?php echo $app_name; ?></span><br/>
                            <span class="h6"><?php echo $app_slogan; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <ul class="lgnMenu">
                            <li><a class="active" href="index.php">Home</a></li>
                            <li><a href="#contact">Applicants</a></li>
                            <li><a href="#contact">Contact</a></li>
                            <li><a href="#contact">About</a></li>
                            <li><a href="#contact">Search</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row" style="min-height:415px;height: 100%;background-color: rgba(0,0,0,0.22);">
                    <div class="col-md-4">&nbsp;</div>
                    <div class="col-md-4" style="">
                        <div class="center-block" id="loginDiv">
                            <div class="login-panel panel panel-default login">
                                <h3 class="panel-title logintitle"><?php echo $loginTitle; ?></h3>
                                <div class="panel-body">
                                    <div class="loginPgForm">
                                        <form role="form" autocomplete="off">
                                            <fieldset>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1">
                                                            <i class="fa fa-user fa-fw fa-border"></i></span>
                                                        <input type="text" class="form-control" placeholder="<?php echo $placeHolder1; ?>" aria-describedby="basic-addon1" id="usrnm" name="usrnm"  onkeyup="enterKeyFunc(event);" autofocus>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-key fa-fw fa-border"></i></span>
                                                        <input class="form-control" placeholder="Password" id="pwd" name="pwd" type="password" value=""  onkeyup="enterKeyFunc(event);">
                                                        <input type="hidden" id="machdet" name="machdet" value="Unknown">
                                                    </div>
                                                </div>
                                                <p class="label" id="msgArea">
                                                    <label style="color:red;font-size:12px;text-align: center;">
                                                        &nbsp;<?php echo str_replace('%21%0A', ' ', urldecode($error)); ?>
                                                    </label>
                                                </p>
                                                <button type="button" onclick="homePage();" class="btn btn-md btn-default btn-block otherButton">Login</button>
                                                <button type="button"  onclick="forgotPwd();" class="btn btn-md btn-default btn-block otherButton">Request for New Password</button>
                                                <button type="button" class="btn btn-default btn-lg btn-block otherButton" onclick="window.open('<?php echo $app_cstmr_url; ?>', '_blank');">
                                                    <img src="<?php echo $app_image1; ?>" style="left: 0.5%; padding-right: 1em; height:60px; width:auto; position: relative; vertical-align: middle;">
                                                    <br/><?php echo $website_btn_txt; ?>
                                                </button>
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <hr>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
            </div>
            <section id="contact" class="gray-section contact" style="background-color: rgba(0, 0,0,0.22);border-top:0px solid #ddd;color:#FFF;font-size: 16px; padding:20px;">
                <div class="container">
                    <div class="row">                        
                        <div class="col-md-4 text-center" style="">
                            <div class="container" style="padding:25px;margin-bottom: 10px;background-color: rgba(0,0,0,0.32);min-height: 200px;">
                                <h1>About Portal</h1>
                                <div style="font-family: Tahoma;font-size:14px;"><?php echo $abt_portal; ?></div>
                            </div>
                        </div>                                                
                        <div class="col-md-4 text-center" style="">
                            <div class="container" style="padding:25px;margin-bottom: 10px;background-color: rgba(0,0,0,0.32);min-height: 200px;">
                                <h1>Instructions</h1>
                                <div style="text-align: left;font-family: Tahoma;font-size:14px;padding-left: 30px;"><?php echo $instructions; ?></div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center" style="">
                            <div class="container" style="padding:25px;margin-bottom: 10px;background-color: rgba(0,0,0,0.32);min-height: 200px;">
                                <div class="row">
                                    <div class="col-xs-12 text-center">
                                        <div class="navy-line"></div>
                                        <h1>Contact Us</h1>
                                        <div><?php echo $loginPgNotice ?> </div>                                       
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 text-center">
                                        <a href="mailto:<?php echo $admin_email; ?>" class="btn btn-primary">Send us a mail</a>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4" style="padding:10px;">
                            <hr>
                            <p><span style="font-family: Times;font-style: italic;font-weight: bold;font-size: 14px;padding-top: 3px;">Copyright &COPY; <?php echo date('Y'); ?> <a style="color:#FFF" href="<?php echo $about_url; ?>" target="_blank"><?php echo $app_org; ?></a>.</span></p>
                        </div>
                        <div class="col-md-4">&nbsp;</div>
                    </div>
                </div>
            </section>
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
            <script type="text/javascript">
                                                    $body = $("body");
                                                    $body.removeClass("mdlloading");
            </script>
        </body>
        </html>
        <?php
    } else {
//echo $lgn_num."-LGN_NUM-".$error;
        ?>
        <?php
    }
}?>