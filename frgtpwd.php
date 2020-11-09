<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num <= 0) {
        ?>   
        <link rel="STYLESHEET" type="text/css" href="cmn_scrpts/loginStyles.css"/>
        <div class="login-panel panel panel-default login" style="max-width: 370px !important;">
            <h3 class="panel-title logintitle"><img src="cmn_images/change-password.png" style="float:left;height:40px;margin-left: 10px;"/>CHANGE ACCOUNT PASSWORD</h3>
            <form method="post" action="javascript: return false;"  autocomplete="off" style="width:100%;padding:10px;"  onSubmit="return false;">
                <p class="" >
                    <label id="fullName" style="color:green;margin:10px;font-size: 15px;font-weight: bold;">
                        <?php echo "Enter your " . $placeHolder1; ?>
                    </label>
                </p>
                <div class="input-group margin-bottom-sm">
                    <span class="input-group-addon"><i class="fa fa-user fa-fw fa-border"></i></span>
                    <input class="form-control" type="text" id="usrnm" name="usrnm" value="" placeholder="<?php echo $placeHolder1; ?>" onkeyup="enterKeyFuncSL(event);">
                </div>
                <?php
                if ($lgn_num <= 0) {
                    ?>                                                                 
                    <p class="others" >
                        <button type="button" name="sendLink" onclick="chngePswdPage1('send_link');" class="btn btn-md btn-default btn-block otherButton">Send Password Reset Link</button>
                    </p>
                    <?php
                }
                ?>
                <p class="others" >
                    <button type="button" name="cancel" onclick="window.location = 'index.php';" class="btn btn-md btn-default btn-block otherButton">Return to Home Page</button>
                </p>
            </form> 
        </div>
        <!-- jQuery -->
        <script src="cmn_scrpts/jquery-1.11.3.min.js"></script>
        <script src="cmn_scrpts/bootstrap337/js/bootstrap.min.js"></script>
        <?php
    } else {
        require 'chngpwd.php';
        ?>
        <?php
    }
}?>