<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$placeHolder12 = "";
if (strpos("mail", $placeHolder1) === FALSE) {
    $placeHolder12 = "Email Address or Username";
} else {
    $placeHolder12 = $placeHolder1;
}
$isSelfRgstrAllwdID = getEnbldPssblValID("Allow User Account Self-Registration", getLovID("All Other General Setups"));
$isSelfRgstrAllwd = getPssblValDesc($isSelfRgstrAllwdID);
?>
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
            showBootDiagMsg('System Alert!', 'User Name cannot be empty!');
            return false;
        }
        if (old_pswd === "" || old_pswd === null)
        {
            showBootDiagMsg('System Alert!', 'Password cannot be empty!');
            return false;
        }
        lnkArgs = "grp=200&typ=1&usrnm=" + usrNm + "&pwd=" + old_pswd + "&machdet=" + machdet + "&screenwdth=" + screen.width;
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
                /*alert(xmlhttp.responseText);
                 openATab('#allmodules', 'grp=210&typ=5');*/
                if (rspns.indexOf('change password') > -1 || rspns.indexOf('select role') > -1)
                {
                    window.location = 'index.php';
                } else
                {
                    document.getElementById("msgArea").innerHTML = "<span class=\"wordwrap3\" style=\"color:red;font-size:12px;font-weight:bold;text-align: center;margin-top:0px;\">&nbsp;" + rspns + "</span>";
                }
            } else
            {
                document.getElementById("msgArea").innerHTML = "<span class=\"wordwrap3\" style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><img style=\"width:145px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;\" src='../cmn_images/ajax-loader2.gif'/>Loading...Please Wait...</span>";
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(lnkArgs); //+ "&machdetls=" + machDet
    }

    function rgstrNewUser() {
        var xmlhttp;
        var pFirstName = "";
        var pSurName = "";
        var pEmail = "";
        var pPhone = "";
        var pPassword = "";
        var pCnfrmPassword = "";
        var pAgreeTerms = "";

        var lnkArgs = "";
        var machdet = "";

        pFirstName = document.getElementById("pFirstName").value;
        pSurName = document.getElementById("pSurName").value;
        pEmail = document.getElementById("pEmail").value;
        pPhone = document.getElementById("pPhone").value;
        pPassword = document.getElementById("pPassword").value;
        pCnfrmPassword = document.getElementById("pCnfrmPassword").value;
        pAgreeTerms = typeof $("input[name='pAgreeTerms']:checked").val() === 'undefined' ? 'NO' : 'YES';
        var errMsg = "";
        var msgStyle1 = 'color:red;font-family:georgia;font-weight:bold;font-size:14px;font-style:italic;';
        if (pFirstName === "" || pFirstName === null)
        {
            errMsg += '<span style="' + msgStyle1 + '">First Name cannot be empty!</span><br>';
        }
        if (pSurName === "" || pSurName === null)
        {
            errMsg += '<span style="' + msgStyle1 + '">Surname cannot be empty!</span><br>';
        }
        var isVld = true;
        var orgnlEmail = pEmail;
        if (pEmail.trim() !== '') {
            pEmail = pEmail.replace(/;/g, ',').replace(/:/g, ',').replace(/, /g, ',').replace(/ /g, ',').replace(/[, ]+/g, ",").trim();
            var tmpMails = pEmail.split(',');
            var anodaEmail = '';
            for (var i = 0; i < tmpMails.length; i++) {
                if (isEmailValid(tmpMails[i])) {
                    anodaEmail += tmpMails[i] + ', ';
                } else {
                    isVld = false;
                    errMsg += '<span style="' + msgStyle1 + '">' + tmpMails[i] + ' is an Invalid Email!</span><br>';
                }
            }
            if (anodaEmail.trim() !== '' && isVld === true) {
                pEmail = rhotrim(anodaEmail, ', ');
                $("#pEmail").val(pEmail);
            }
        }
        if (isVld === false) {
            $('#pEmail').addClass('rho-error');
            isVld = true;
        } else {
            $('#pEmail').removeClass('rho-error');
        }
        if (pEmail === "" || pEmail === null)
        {
            errMsg += '<span style="' + msgStyle1 + '">Email cannot be empty!</span><br>';
        }
        var orgnlMobileNos = pPhone;
        if (pPhone.trim() !== '') {
            pPhone = pPhone.replace(/;/g, ',').replace(/:/g, ',').replace(/, /g, ',').replace(/ /g, ',').replace(/[, ]+/g, ",").trim();
            var tmpMobileNos = pPhone.split(',');
            var anodaMobileNo = '';
            for (var i = 0; i < tmpMobileNos.length; i++) {
                if (tmpMobileNos[i].indexOf('0') === 0) {
                    tmpMobileNos[i] = tmpMobileNos[i].replace('0', '+233');
                }
                if (isMobileNumValid(tmpMobileNos[i])) {
                    anodaMobileNo += tmpMobileNos[i] + ', ';
                } else {
                    isVld = false;
                    errMsg += '<span style="' + msgStyle1 + '">' + tmpMobileNos[i] + ' is an Invalid Phone Number!</span><br>';
                }
            }
            if (anodaMobileNo.trim() !== '' && isVld === true) {
                pPhone = rhotrim(anodaMobileNo, ', ');
                $("#pPhone").val(pPhone);
            }
        }
        if (isVld === false) {
            $('#pPhone').addClass('rho-error');
            isVld = true;
        } else {
            $('#pPhone').removeClass('rho-error');
        }
        if (pPhone === "" || pPhone === null)
        {
            errMsg += '<span style="' + msgStyle1 + '">Phone Number cannot be empty!</span><br>';
        }
        if (pPassword === "" || pPassword === null || pCnfrmPassword === "" || pCnfrmPassword === null)
        {
            errMsg += '<span style="' + msgStyle1 + '">Passwords cannot be empty!</span><br>';
        }
        if (pPassword !== pCnfrmPassword)
        {
            errMsg += '<span style="' + msgStyle1 + '">Passwords must be the same!</span><br>';
        }
        if (pAgreeTerms === "NO")
        {
            errMsg += '<span style="' + msgStyle1 + '">You have to accept the Terms before you can proceed!</span><br>';
        }
        if (rhotrim(errMsg, '; ') !== '')
        {
            showBootDiagMsg('System Alert!', errMsg);
            return false;
        }
        lnkArgs = "grp=200&typ=4&q=registernew&pFirstName=" + pFirstName + "&pSurName=" + pSurName + "&pEmail=" + pEmail + "&pPhone=" + pPhone +
                "&pPassword=" + pPassword + "&pCnfrmPassword=" + pCnfrmPassword + "&pAgreeTerms=" + pAgreeTerms;
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
                if (rspns.indexOf('change password') !== -1
                        || rspns.indexOf('select role') !== -1
                        || rspns.indexOf('success') !== -1)
                {
                    document.getElementById("msgArea").innerHTML = "<span class=\"wordwrap3\" style=\"color:green;font-size:12px;font-weight:bold;text-align: center;margin-top:0px;\">&nbsp;" + rspns + "</span>";
                    setTimeout(function () {
                        window.location = 'index.php';
                    }, 2000);
                } else
                {
                    document.getElementById("msgArea").innerHTML = "<span class=\"wordwrap3\" style=\"color:red;font-size:12px;font-weight:bold;text-align: center;margin-top:0px;\">&nbsp;" + rspns + "</span>";
                }
            } else
            {
                document.getElementById("msgArea").innerHTML = "<span class=\"wordwrap3\" style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><img style=\"width:145px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;\" src='../cmn_images/ajax-loader2.gif'/>Loading...Please Wait...</span>";
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
            showBootDiagMsg('System Alert!', 'User Name cannot be empty!');
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

    function enterKeyFunc2(e)
    {
        var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
        if (charCode == 13) {
            chngePswdPage2('do_change');
        }
    }

    function chngePswdPage2(str_Cmd)
    {
        //alert("test" + myCountry() + myIP());
        var xmlhttp;
        var usrNm = typeof $("#usrnm").val() === 'undefined' ? '' : $("#usrnm").val();
        var old_pswd = typeof $("#oldpwd").val() === 'undefined' ? '' : $("#oldpwd").val();
        var new_pswd = typeof $("#newpwd").val() === 'undefined' ? '' : $("#newpwd").val();
        var cnfm_pswd = typeof $("#cnfrmpwd").val() === 'undefined' ? '' : $("#cnfrmpwd").val();
        var lnkArgs = "";
        var machdet = typeof $("#machdet").val() === 'undefined' ? '' : $("#machdet").val();
        /*var myCrptedLnk = "";
         usrNm = document.getElementById("usrnm").value;
         old_pswd = document.getElementById("oldpwd").value;
         new_pswd = document.getElementById("newpwd").value;
         cnfm_pswd = document.getElementById("cnfrmpwd").value;
         machdet = document.getElementById("machdet").value;*/
        var waitngMsg = "Changing Password...Please Wait...";
        if (usrNm === "" || usrNm === null)
        {
            showBootDiagMsg('System Alert!', 'User Name cannot be empty!');
            return;
        }
        if (str_Cmd == 'do_change')
        {
            if (new_pswd === "" || new_pswd === null)
            {
                showBootDiagMsg('System Alert!', 'New Password cannot be empty!');
                return;
            }
            if (cnfm_pswd === "" || cnfm_pswd === null)
            {
                var bmsg = 'Confirm Password cannot be empty!';
                showBootDiagMsg('System Alert!', bmsg);
                return;
            }
            if (new_pswd !== cnfm_pswd)
            {
                var bmsg = 'New Passwords must be the same!';
                showBootDiagMsg('System Alert!', bmsg);
                return;
            }
            lnkArgs = "grp=200&typ=3&username=" + usrNm + "&oldpassword=" +
                    old_pswd + "&newpassword=" + new_pswd + "&rptpassword="
                    + cnfm_pswd + "&q=" + str_Cmd + "&machdet=" + machdet + "&screenwdth=" + screen.width;
            ;
        } else if (str_Cmd == 'send_link')
        {
            lnkArgs = "grp=1&typ=11&q=SendPswdLnk&in_val=" + usrNm;
        } else if (str_Cmd == 'verify_link')
        {
            waitngMsg = "Verifying E-mail Address...Please Wait..."
            lnkArgs = "grp=1&typ=11&q=VerifyEmail&in_val=" + usrNm;
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
                    if (str_Cmd == 'verify_link') {
                        setTimeout(function () {
                            window.location = 'index.php';
                        }, 2000);
                    } else {
                        window.location = "index.php";
                    }
                }
            } else
            {
                document.getElementById("fullName").innerHTML = waitngMsg;
            }
        }
        ;

        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send(lnkArgs);//+ "&machdetls=" + machDet

    }

</script>
<?php
if ($lgn_num <= 0) {
    ?>
    <?php if ($type == 2) { ?>
        <!-- Content Header (Page header) -->
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Self-Service Sign-In</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Sign-In</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content" style="padding: 16px 0.5rem !important;max-width: 400px !important;">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">Have an account already? Sign-in</p>
                        <form role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="<?php echo $placeHolder12; ?>" id="usrnm" name="usrnm"  onkeyup="enterKeyFunc(event);" autofocus>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" id="pwd" name="pwd" value=""  onkeyup="enterKeyFunc(event);">
                                <input type="hidden" id="machdet" name="machdet" value="Unknown">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <p class="label" id="msgArea" style="text-align: center;">
                                <label style="color:blue;font-size:12px;text-align: center;">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Enter Logon Credentials
                                </label>
                                <label style="color:red;font-size:12px;text-align: center;">
                                    &nbsp;<?php echo str_replace('%21%0A', ' ', urldecode($error)); ?>
                                </label>
                            </p>
                            <div class="row">
                                <div class="col-8">
                                    <p class="mb-1">
                                        <a href="javascript:openATab('#allmodules', 'grp=210&typ=3');" class="rho-link">I forgot my password</a>
                                    </p>
                                    <?php if (strtoupper($isSelfRgstrAllwd) === "YES" && $isSelfRgstrAllwdID > 0) { ?>
                                        <p class="mb-0">
                                            <a href="javascript:openATab('#allmodules', 'grp=210&typ=4');" class="text-center rho-link">Register a new Account</a>
                                        </p>
                                    <?php } ?>
                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="button" onclick="homePage();" class="btn rho-primary btn-block">Sign In</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>
    <?php } else if ($type == 3) { ?>
        <!-- Content Header (Page header) -->
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Forgot Password</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=210&typ=2');">Sign-In</a></li>
                            <li class="breadcrumb-item active">Forgot Password?</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content" style="padding: 16px 0.5rem !important;max-width: 400px !important;">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                        <p class="" >
                            <label id="fullName" style="color:green;margin:10px;font-size: 15px;font-weight: bold;">
                                <?php echo "Enter your " . $placeHolder12; ?>
                            </label>
                        </p>
                        <form role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="<?php echo $placeHolder12; ?>" id="usrnm" name="usrnm"  onkeyup="enterKeyFunc1(event);" autofocus>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" name="sendLink" onclick="chngePswdPage1('send_link');" class="btn rho-primary btn-block">Request new password</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>

                        <p class="mt-3 mb-1">
                            <a href="javascript:openATab('#allmodules', 'grp=210&typ=2');" class="rho-link">Login</a>
                        </p>
                        <?php if (strtoupper($isSelfRgstrAllwd) === "YES" && $isSelfRgstrAllwdID > 0) { ?>
                            <p class="mb-0">
                                <a href="javascript:openATab('#allmodules', 'grp=210&typ=4');" class="text-center rho-link">Register a new Account</a>
                            </p>
                        <?php } ?>
                    </div>
                    <!-- /.login-card-body -->
                </div>
            </div><!-- /.container-fluid -->
        </section>
    <?php } else if ($type == 4 && (strtoupper($isSelfRgstrAllwd) === "YES")) {
        ?>
        <!-- Content Header (Page header) -->
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Register New User Account</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Register</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content" style="padding: 16px 0.5rem !important;max-width: 400px !important;">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body register-card-body">
                        <p class="login-box-msg">Register a new User Account</p>
                        <p class="label" id="msgArea" style="text-align: center;">
                            <label style="color:blue;font-size:12px;text-align: center;">
                                <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Enter Your Details
                            </label>
                            <label style="color:red;font-size:12px;text-align: center;">
                                &nbsp;<?php echo str_replace('%21%0A', ' ', urldecode($error)); ?>
                            </label>
                        </p>
                        <form role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control rqrdFld" placeholder="First name" id="pFirstName" name="pFirstName">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control rqrdFld" placeholder="Surname" id="pSurName" name="pSurName">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control rqrdFld" placeholder="Email" id="pEmail" name="pEmail">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control rqrdFld" placeholder="Mobile Phone Number" id="pPhone" name="pPhone">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-mobile-alt"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control rqrdFld" placeholder="Password" id="pPassword" name="pPassword">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control rqrdFld" placeholder="Retype password" id="pCnfrmPassword" name="pCnfrmPassword">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="pAgreeTerms" name="pAgreeTerms" value="agree">
                                        <label for="agreeTerms">
                                            I agree to the <a href="index.php" target="_blank" class="rho-link">terms</a>
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="button" class="btn rho-primary btn-block" onclick="rgstrNewUser();">Register</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                        <a href="javascript:openATab('#allmodules', 'grp=210&typ=2');" class="text-center rho-link">I already have a user Account</a>
                    </div>
                    <!-- /.form-box -->
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <?php
    } else if ($type == 707) {
        $sPrsnNm = $_SESSION['PRSN_FNAME'];
        $sUNAME = $_SESSION['UNAME'];
        if ($gUNM != '') {
            $sPrsnNm = getPrsnFullNm(getUserPrsnID($gUNM));
            $sUNAME = $gUNM;
        }
        ?>
        <!-- Content Header (Page header) -->
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Change Your Password</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Change Password</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content" style="padding: 16px 0.5rem !important;max-width: 400px !important;">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
                        <p style="text-align: center;">
                            <label id="fullName" style="color:green;margin-left:10px;font-size: 15px;font-weight: bold;text-align: center;">
                                <?php echo $sPrsnNm; ?>
                            </label>
                        </p>
                        <form role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="<?php echo $placeHolder12; ?>" id="usrnm" name="usrnm" value="<?php echo $sUNAME; ?>" readonly="true">
                                <input type="hidden" name="machdet"  id="machdet" value="Unknown"/>
                                <input type="hidden" id="oldpwd" name="oldpwd" value="<?php echo $gDcrpt; ?>">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="New Password" id="newpwd" name="newpwd" type="password" value="" onkeyup="enterKeyFunc2(event);">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Confirm New Password" id="cnfrmpwd" name="cnfrmpwd" type="password" value=""  onkeyup="enterKeyFunc2(event);">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <p class="label"><label style="color:red;text-align: center;">
                                    &nbsp;<?php echo str_replace('%21%0A', ' ', urldecode($error)); ?>
                                </label>
                            </p>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" name="commit" class="btn rho-primary btn-block" onclick="chngePswdPage2('do_change');">Change password</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                    </div>
                    <!-- /.login-card-body -->
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <?php
    } else if ($type == 708) {
        $sPrsnNm = $_SESSION['PRSN_FNAME'];
        $sUNAME = $_SESSION['UNAME'];
        if ($gUNM != '') {
            $sPrsnNm = getPrsnFullNm(getUserPrsnID($gUNM));
            $sUNAME = $gUNM;
        }
        ?>
        <!-- Content Header (Page header) -->
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Email Verification</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Verify Email</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content" style="padding: 16px 0.5rem !important;max-width: 400px !important;">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">You are only one step a way from verifying your E-mail Address.</p>
                        <p style="text-align: center;">
                            <label id="fullName" style="color:green;margin-left:10px;font-size: 15px;font-weight: bold;text-align: center;">
                                <?php echo $sPrsnNm . " (" . $sUNAME . ")"; ?>
                            </label>
                        </p>
                        <form role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                            <div class="input-group mb-3">
                                <input type="hidden" name="machdet"  id="machdet" value="Unknown"/>
                                <input type="hidden" id="usrnm" name="usrnm" value="<?php echo $sUNAME; ?>" placeholder="<?php echo $placeHolder12; ?>" readonly="true">
                                <input type="hidden" id="oldpwd" name="oldpwd" value="<?php echo $gDcrpt; ?>">
                            </div>
                            <p class="label"><label style="color:red;text-align: center;">
                                    &nbsp;<?php echo str_replace('%21%0A', ' ', urldecode($error)); ?>
                                </label>
                            </p>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" name="commit" class="btn rho-primary btn-block" onclick="chngePswdPage2('verify_link');">Click to Verify Email</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                    </div>
                    <!-- /.login-card-body -->
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <?php
    } else {
        restricted();
    }
} else {
    if ($type == 5) {
        $sPrsnNm = $_SESSION['PRSN_FNAME'];
        $sUNAME = $_SESSION['UNAME'];
        ?>
        <!-- Content Header (Page header) -->
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Change Your Password</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Change Password</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content" style="padding: 16px 0.5rem !important;max-width: 400px !important;">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
                        <p style="text-align: center;">
                            <label id="fullName" style="color:green;margin-left:10px;font-size: 15px;font-weight: bold;text-align: center;">
                                <?php echo $sPrsnNm; ?>
                            </label>
                        </p>
                        <form role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Current Password" id="oldpwd" name="oldpwd" type="password" value=""  onkeyup="enterKeyFunc2(event);">
                                <input type="hidden" name="machdet"  id="machdet" value="Unknown"/>
                                <input type="hidden" id="usrnm" name="usrnm" value="<?php echo $sUNAME; ?>" placeholder="<?php echo $placeHolder12; ?>" readonly="true">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="New Password" id="newpwd" name="newpwd" type="password" value="" onkeyup="enterKeyFunc2(event);">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Confirm New Password" id="cnfrmpwd" name="cnfrmpwd" type="password" value=""  onkeyup="enterKeyFunc2(event);">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <p class="label"><label style="color:red;text-align: center;">
                                    &nbsp;<?php echo str_replace('%21%0A', ' ', urldecode($error)); ?>
                                </label>
                            </p>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" name="commit" class="btn rho-primary btn-block" onclick="chngePswdPage2('do_change');">Change password</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                    </div>
                    <!-- /.login-card-body -->
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <?php
    } else if ($type == 708) {
        $sPrsnNm = $_SESSION['PRSN_FNAME'];
        $sUNAME = $_SESSION['UNAME'];
        if ($gUNM != '') {
            $sPrsnNm = getPrsnFullNm(getUserPrsnID($gUNM));
            $sUNAME = $gUNM;
        }
        ?>
        <!-- Content Header (Page header) -->
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Email Verification</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Verify Email</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content" style="padding: 16px 0.5rem !important;max-width: 400px !important;">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">You are only one step a way from verifying your E-mail Address.</p>
                        <p style="text-align: center;">
                            <label id="fullName" style="color:green;margin-left:10px;font-size: 15px;font-weight: bold;text-align: center;">
                                <?php echo $sPrsnNm . " (" . $sUNAME . ")"; ?>
                            </label>
                        </p>
                        <form role="form" autocomplete="off" method="post" action="" onSubmit="return false;">
                            <div class="input-group mb-3">
                                <input type="hidden" name="machdet"  id="machdet" value="Unknown"/>
                                <input type="hidden" id="usrnm" name="usrnm" value="<?php echo $sUNAME; ?>" placeholder="<?php echo $placeHolder12; ?>" readonly="true">
                                <input type="hidden" id="oldpwd" name="oldpwd" value="<?php echo $gDcrpt; ?>">
                            </div>
                            <p class="label"><label style="color:red;text-align: center;">
                                    &nbsp;<?php echo str_replace('%21%0A', ' ', urldecode($error)); ?>
                                </label>
                            </p>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" name="commit" class="btn rho-primary btn-block" onclick="chngePswdPage2('verify_link');">Click to Verify Email</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                    </div>
                    <!-- /.login-card-body -->
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <?php
    } else {
        restricted();
    }
}
?>