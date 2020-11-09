
function prepareSysAdmin(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&pg=1&vtyp=0") !== -1)
        {
            var table1 = $('#allUsersTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allUsersTable').wrap('<div class="dataTables_scroll"/>');
            $('#allUsersForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=2&vtyp=0") !== -1)
        {
            var table1 = $('#allRolesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allRolesTable').wrap('<div class="dataTables_scroll"/>');
            $('#allRolesForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            $(function () {
                $('.form_date_tme').datetimepicker({
                    format: "dd-M-yyyy hh:ii:ss",
                    language: 'en',
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 0,
                    maxView: 4,
                    forceParse: true
                });
            });
        } else if (lnkArgs.indexOf("&pg=3&vtyp=0") !== -1)
        {
            var table1 = $('#allMdlsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allMdlsTable').wrap('<div class="dataTables_scroll"/>');
            $('#allMdlsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=4&vtyp=0") !== -1)
        {
            var table1 = $('#allSbgrpsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allSbgrpsTable').wrap('<div class="dataTables_scroll"/>');
            $('#allSbgrpsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=5&vtyp=0") !== -1)
        {
            $(document).ready(function () {
                var cntr = 0;
                var table1 = $('#allSecPlcysTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allSecPlcysTable').wrap('<div class="dataTables_scroll"/>');
                var table2 = $('#secPlcyAdtTblsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#secPlcyAdtTblsTable').wrap('<div class="dataTables_scroll"/>');

                $('#allSecPlcysForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });

                $('#allSecPlcysTable tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');

                    } else {
                        table1.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');

                    }
                    var rndmNum = $(this).attr('id').split("_")[1];
                    var curPlcyID = typeof $('#allSecPlcysRow' + rndmNum + '_PlcyID').val() === 'undefined' ? '%' : $('#allSecPlcysRow' + rndmNum + '_PlcyID').val();
                    //alert(curPlcyID);
                    getOneSecPlcyForm(curPlcyID, 1);
                });
                $('#allSecPlcysTable tbody')
                        .on('mouseenter', 'tr', function () {
                            if ($(this).hasClass('highlight')) {
                                $(this).removeClass('highlight');
                            } else {
                                table1.$('tr.highlight').removeClass('highlight');
                                $(this).addClass('highlight');
                            }
                        });
            });
        } else if (lnkArgs.indexOf("&pg=6&vtyp=0") !== -1)
        {
            $(document).ready(function () {
                var cntr = 0;
                var table1 = $('#allSrvrStngsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allSrvrStngsTable').wrap('<div class="dataTables_scroll"/>');
                var table2 = $('#srvrStngSmsApiTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#srvrStngSmsApiTable').wrap('<div class="dataTables_scroll"/>');

                $('#allSrvrStngsForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });

                $('#allSrvrStngsTable tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        table1.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    var rndmNum = $(this).attr('id').split("_")[1];
                    var curSrvrID = typeof $('#allSrvrStngsRow' + rndmNum + '_SrvrID').val() === 'undefined' ? '%' : $('#allSrvrStngsRow' + rndmNum + '_SrvrID').val();

                    getOneSrvrStngForm(curSrvrID, 1);
                });
                $('#allSrvrStngsTable tbody')
                        .on('mouseenter', 'tr', function () {
                            if ($(this).hasClass('highlight')) {
                                $(this).removeClass('highlight');
                            } else {
                                table1.$('tr.highlight').removeClass('highlight');
                                $(this).addClass('highlight');
                            }
                        });
            });
        } else if (lnkArgs.indexOf("&pg=7&vtyp=0") !== -1)
        {
            var table1 = $('#trckUsrLgnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#trckUsrLgnsTable').wrap('<div class="dataTables_scroll"/>');
            $('#trckUsrLgnsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=8&vtyp=0") !== -1)
        {
            var table1 = $('#adtTrailsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#adtTrailsTable').wrap('<div class="dataTables_scroll"/>');
            $('#adtTrailsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
        htBody.removeClass("mdlloading");
    });
}

function getAllUsers(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allUsersSrchFor").val() === 'undefined' ? '%' : $("#allUsersSrchFor").val();
    var srchIn = typeof $("#allUsersSrchIn").val() === 'undefined' ? 'Both' : $("#allUsersSrchIn").val();
    var pageNo = typeof $("#allUsersPageNo").val() === 'undefined' ? 1 : $("#allUsersPageNo").val();
    var limitSze = typeof $("#allUsersDsplySze").val() === 'undefined' ? 10 : $("#allUsersDsplySze").val();
    var sortBy = typeof $("#allUsersSortBy").val() === 'undefined' ? '' : $("#allUsersSortBy").val();
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllUsers(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllUsers(actionText, slctr, linkArgs);
    }
}

function getOneUserForm(elementID, modalBodyID, titleElementID, formElementID,
        formTitle, userID, vtyp, pgNo, actionText)
{
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");

        var srchFor = typeof $("#userRolesSrchFor").val() === 'undefined' ? '%' : $("#userRolesSrchFor").val();
        var srchIn = typeof $("#userRolesSrchIn").val() === 'undefined' ? 'Both' : $("#userRolesSrchIn").val();
        var pageNo = typeof $("#userRolesPageNo").val() === 'undefined' ? 1 : $("#userRolesPageNo").val();
        var limitSze = typeof $("#userRolesDsplySze").val() === 'undefined' ? 10 : $("#userRolesDsplySze").val();
        var sortBy = typeof $("#userRolesSortBy").val() === 'undefined' ? '' : $("#userRolesSortBy").val();
        if (actionText == 'clear')
        {
            srchFor = "%";
            pageNo = 1;
        } else if (actionText == 'next')
        {
            pageNo = parseInt(pageNo) + 1;
        } else if (actionText == 'previous')
        {
            pageNo = parseInt(pageNo) - 1;
        }
        var linkArgs = "&searchfor=" + srchFor + "&searchin=" + srchIn +
                "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
        var xmlhttp;
        if (window.XMLHttpRequest)
        {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else
        {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
            {
                $('#' + titleElementID).html(formTitle);
                $('#' + modalBodyID).html(xmlhttp.responseText);
                /*$('.modal-dialog').draggable();*/
                if (vtyp == 1)
                {
                    var table1 = $('#userRolesTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#userRolesTable').wrap('<div class="dataTables_scroll" />');

                    $('#userRolesForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                }
                $(function () {
                    $('.form_date_tme').datetimepicker({
                        format: "dd-M-yyyy hh:ii:ss",
                        language: 'en',
                        weekStart: 0,
                        todayBtn: true,
                        autoclose: true,
                        todayHighlight: true,
                        keyboardNavigation: true,
                        startView: 2,
                        minView: 0,
                        maxView: 4,
                        forceParse: true
                    });
                });

                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");

                $('#' + formElementID).submit(function (e) {
                    e.preventDefault();
                    return false;
                });
                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).on("hidden.bs.modal", function () {
                    getAllUsers('', '#allmodules', 'grp=3&typ=1&pg=1&vtyp=0');
                });

            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=3&typ=1&pg=" + pgNo + "&vtyp=" + vtyp + "&sbmtdUserID=" + userID + linkArgs);
    });
}

function saveOneUserForm()
{
    var usrPrflAccountID = typeof $("#usrPrflAccountID").val() === 'undefined' ? -1 : $("#usrPrflAccountID").val();
    var usrPrflAccountName = typeof $("#usrPrflAccountName").val() === 'undefined' ? '' : $("#usrPrflAccountName").val();
    var usrPrflAcntOwnerLocID = typeof $("#usrPrflAcntOwnerLocID").val() === 'undefined' ? '' : $("#usrPrflAcntOwnerLocID").val();
    var usrPrflLnkdCstmrID = typeof $("#usrPrflLnkdCstmrID").val() === 'undefined' ? -1 : $("#usrPrflLnkdCstmrID").val();
    var usrPrflVldtyStartDate = typeof $("#usrPrflVldtyStartDate").val() === 'undefined' ? '' : $("#usrPrflVldtyStartDate").val();
    var usrPrflVldtyEndDate = typeof $("#usrPrflVldtyEndDate").val() === 'undefined' ? '' : $("#usrPrflVldtyEndDate").val();
    var usrPrflModulesNeeded = typeof $("#usrPrflModulesNeeded").val() === 'undefined' ? '' : $("#usrPrflModulesNeeded").val();

    var errMsg = "";
    if (usrPrflAccountName.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Account Name cannot be empty!</span></p>';
    }
    if (usrPrflAcntOwnerLocID.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Account Owner cannot be empty!</span></p>';
    }
    if (usrPrflVldtyStartDate.trim() === '')
    {
        usrPrflVldtyStartDate = rhoFrmtDate();
        $("#usrPrflVldtyStartDate").val(usrPrflVldtyStartDate);
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    }
    var slctdUsrPrflRoles = "";
    $('#userRolesTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var roleID = $('#usrPrflEdtRow' + rndmNum + '_RoleID').val();
                if (roleID > 0) {
                    slctdUsrPrflRoles = slctdUsrPrflRoles + $('#usrPrflEdtRow' + rndmNum + '_RoleID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#usrPrflEdtRow' + rndmNum + '_RoleNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#usrPrflEdtRow' + rndmNum + '_DfltRowID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#usrPrflEdtRow' + rndmNum + '_StrtDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#usrPrflEdtRow' + rndmNum + '_EndDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                }
            }
        }
    });
    var dialog = bootbox.alert({
        title: 'Save User',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving User...Please Wait...</p>',
        callback: function () {
        }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 3,
                    typ: 1,
                    pg: 1,
                    q: 'UPDATE',
                    actyp: 1,
                    usrPrflAccountID: usrPrflAccountID,
                    usrPrflAccountName: usrPrflAccountName,
                    usrPrflAcntOwnerLocID: usrPrflAcntOwnerLocID,
                    usrPrflLnkdCstmrID: usrPrflLnkdCstmrID,
                    usrPrflVldtyStartDate: usrPrflVldtyStartDate,
                    usrPrflVldtyEndDate: usrPrflVldtyEndDate,
                    usrPrflModulesNeeded: usrPrflModulesNeeded,
                    slctdUsrPrflRoles: slctdUsrPrflRoles
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result.message);
                        $("#usrPrflAccountID").val(result.usrPrflAccountID);
                        if (slctdUsrPrflRoles.trim().length > 0)
                        {
                            getOneUserForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'userRolesForm', 'View/Edit User', result.usrPrflAccountID, 1, 1, '');
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }});
        });
    });
}

function enterKeyFuncOneUser(e, elementID, modalBodyID, titleElementID, formElementID,
        formTitle, userID, vtyp, pgNo, actionText)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getOneUserForm(elementID, modalBodyID, titleElementID, formElementID,
                formTitle, userID, vtyp, pgNo, actionText);
    }
}


function getAllRoles(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allRolesSrchFor").val() === 'undefined' ? '%' : $("#allRolesSrchFor").val();
    var srchIn = typeof $("#allRolesSrchFor").val() === 'undefined' ? 'Both' : $("#allRolesSrchFor").val();
    var pageNo = typeof $("#allRolesPageNo").val() === 'undefined' ? 1 : $("#allRolesPageNo").val();
    var limitSze = typeof $("#allRolesDsplySze").val() === 'undefined' ? 10 : $("#allRolesDsplySze").val();
    var sortBy = typeof $("#allRolesSortBy").val() === 'undefined' ? '' : $("#allRolesSortBy").val();
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllRoles(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllRoles(actionText, slctr, linkArgs);
    }
}

function saveAllRolesForm()
{
    var lnkArgs = "grp=3&typ=1&pg=2&q=UPDATE&actyp=1";
    var slctdRoles = "";
    $('#allRolesTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            var rndmNum = $(el).attr('id').split("_")[1];
            var curRoleNm = typeof $('#allRolesEdtRow' + rndmNum + '_RoleNm').val() === 'undefined' ? '%' : $('#allRolesEdtRow' + rndmNum + '_RoleNm').val();

            if (curRoleNm === "" || curRoleNm === null)
            {
                /*$('#modal-7 .modal-body').html('Role Name cannot be empty!');
                 $('#modal-7').modal('show', {backdrop: 'static'});
                 return false;*/
            } else {
                slctdRoles = slctdRoles + $('#allRolesEdtRow' + rndmNum + '_RoleID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + curRoleNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#allRolesEdtRow' + rndmNum + '_CanAdmn').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#allRolesEdtRow' + rndmNum + '_StrtDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#allRolesEdtRow' + rndmNum + '_EndDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
            }
        }
    });
    lnkArgs = lnkArgs + "&slctdAllRoles=" + slctdRoles.slice(0, -1);
    doAjax(lnkArgs, 'myFormsModal', 'ShowDialog', 'System Alert!', 'myFormsModalTitle', 'myFormsModalBody');
}

function getOneRoleForm(elementID, modalBodyID, titleElementID, formElementID,
        formTitle, roleID, vtyp, pgNo, actionText)
{
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");

        var srchFor = typeof $("#rolePrvldgsSrchFor").val() === 'undefined' ? '%' : $("#rolePrvldgsSrchFor").val();
        var srchIn = typeof $("#rolePrvldgsSrchIn").val() === 'undefined' ? 'Both' : $("#rolePrvldgsSrchIn").val();
        var pageNo = typeof $("#rolePrvldgsPageNo").val() === 'undefined' ? 1 : $("#rolePrvldgsPageNo").val();
        var limitSze = typeof $("#rolePrvldgsDsplySze").val() === 'undefined' ? 10 : $("#rolePrvldgsDsplySze").val();
        var sortBy = typeof $("#rolePrvldgsSortBy").val() === 'undefined' ? '' : $("#rolePrvldgsSortBy").val();
        if (actionText == 'clear')
        {
            srchFor = "%";
            pageNo = 1;
        } else if (actionText == 'next')
        {
            pageNo = parseInt(pageNo) + 1;
        } else if (actionText == 'previous')
        {
            pageNo = parseInt(pageNo) - 1;
        }
        var linkArgs = "&searchfor=" + srchFor + "&searchin=" + srchIn +
                "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
        var xmlhttp;
        if (window.XMLHttpRequest)
        {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else
        {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
            {
                if (formTitle != '')
                {
                    $('#' + titleElementID).html(formTitle);
                }
                $('#' + modalBodyID).html(xmlhttp.responseText);
                /*$('.modal-dialog').draggable();*/
                if (vtyp == 1)
                {
                    if (!$.fn.DataTable.isDataTable('#rolePrvldgsTable')) {
                        var table1 = $('#rolePrvldgsTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#rolePrvldgsTable').wrap('<div class="dataTables_scroll" />');
                    }
                    $('#rolePrvldgsForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                }
                $(function () {
                    $('.form_date_tme').datetimepicker({
                        format: "dd-M-yyyy hh:ii:ss",
                        language: 'en',
                        weekStart: 0,
                        todayBtn: true,
                        autoclose: true,
                        todayHighlight: true,
                        keyboardNavigation: true,
                        startView: 2,
                        minView: 0,
                        maxView: 4,
                        forceParse: true
                    });
                });
                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");

                $('#' + formElementID).submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=3&typ=1&pg=" + pgNo + "&vtyp=" + vtyp + "&sbmtdRoleID=" + roleID + linkArgs);
    });
}

function saveRolesPrvldgsForm()
{
    var lnkArgs = "grp=3&typ=1&pg=2&q=UPDATE&actyp=2";
    var rlPrvldgSbmtdRoleID = typeof $("#rlPrvldgSbmtdRoleID").val() === 'undefined' ? -1 : $("#rlPrvldgSbmtdRoleID").val();
    var slctdRolesPrvldgs = "";
    $('#rolePrvldgsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var curPrvldgNm = typeof $('#rolePrvldgsEdtRow' + rndmNum + '_PrvldgNm').val() === 'undefined' ? '%' : $('#rolePrvldgsEdtRow' + rndmNum + '_PrvldgNm').val();

                if (curPrvldgNm === "" || curPrvldgNm === null)
                {
                    /*$('#modal-7 .modal-body').html('Role Name cannot be empty!');
                     $('#modal-7').modal('show', {backdrop: 'static'});
                     return false;*/
                } else {
                    slctdRolesPrvldgs = slctdRolesPrvldgs + $('#rolePrvldgsEdtRow' + rndmNum + '_PrvldgID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + curPrvldgNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#rolePrvldgsEdtRow' + rndmNum + '_StrtDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#rolePrvldgsEdtRow' + rndmNum + '_EndDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                }
            }
        }
    });
    if (slctdRolesPrvldgs.trim() === "" || rlPrvldgSbmtdRoleID <= 0)
    {
        $('#modal-7 .modal-body').html('Linked Role or List of Priviledges cannot be empty!');
        $('#modal-7').modal('show', {backdrop: 'static'});
        return false;
    }
    lnkArgs = lnkArgs + "&slctdRolesPrvldgs=" + slctdRolesPrvldgs.slice(0, -1) + "&rlPrvldgSbmtdRoleID=" + rlPrvldgSbmtdRoleID;
    doAjax(lnkArgs, 'myFormsModal', 'ShowDialog', 'System Alert!', 'myFormsModalTitle', 'myFormsModalBody');
}

function enterKeyFuncOneRole(e, elementID, modalBodyID, titleElementID, formElementID,
        formTitle, userID, vtyp, pgNo, actionText)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getOneRoleForm(elementID, modalBodyID, titleElementID, formElementID,
                formTitle, userID, vtyp, pgNo, actionText);
    }
}

function getAllMdls(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allMdlsSrchFor").val() === 'undefined' ? '%' : $("#allMdlsSrchFor").val();
    var srchIn = typeof $("#allMdlsSrchFor").val() === 'undefined' ? 'Both' : $("#allMdlsSrchFor").val();
    var pageNo = typeof $("#allMdlsPageNo").val() === 'undefined' ? 1 : $("#allMdlsPageNo").val();
    var limitSze = typeof $("#allMdlsDsplySze").val() === 'undefined' ? 10 : $("#allMdlsDsplySze").val();
    var sortBy = typeof $("#allMdlsSortBy").val() === 'undefined' ? '' : $("#allMdlsSortBy").val();
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllMdls(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllMdls(actionText, slctr, linkArgs);
    }
}

function getOneMdlForm(elementID, modalBodyID, titleElementID, formElementID,
        formTitle, mdlID, vtyp, pgNo, actionText)
{
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");

        var srchFor = typeof $("#mdlPrvldgsSrchFor").val() === 'undefined' ? '%' : $("#mdlPrvldgsSrchFor").val();
        var srchIn = typeof $("#mdlPrvldgsSrchIn").val() === 'undefined' ? 'Both' : $("#mdlPrvldgsSrchIn").val();
        var pageNo = typeof $("#mdlPrvldgsPageNo").val() === 'undefined' ? 1 : $("#mdlPrvldgsPageNo").val();
        var limitSze = typeof $("#mdlPrvldgsDsplySze").val() === 'undefined' ? 10 : $("#mdlPrvldgsDsplySze").val();
        var sortBy = typeof $("#mdlPrvldgsSortBy").val() === 'undefined' ? '' : $("#mdlPrvldgsSortBy").val();
        if (actionText == 'clear')
        {
            srchFor = "%";
            pageNo = 1;
        } else if (actionText == 'next')
        {
            pageNo = parseInt(pageNo) + 1;
        } else if (actionText == 'previous')
        {
            pageNo = parseInt(pageNo) - 1;
        }
        var linkArgs = "&searchfor=" + srchFor + "&searchin=" + srchIn +
                "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
        var xmlhttp;
        if (window.XMLHttpRequest)
        {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else
        {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
            {
                if (formTitle != '')
                {
                    $('#' + titleElementID).html(formTitle);
                }
                $('#' + modalBodyID).html(xmlhttp.responseText);
                /*$('.modal-dialog').draggable();*/
                if (vtyp == 1)
                {
                    var table1 = $('#mdlPrvldgsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#mdlPrvldgsTable').wrap('<div class="dataTables_scroll" />');

                    $('#mdlPrvldgsForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                }
                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");

                $('#' + formElementID).submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=3&typ=1&pg=" + pgNo + "&vtyp=" + vtyp + "&sbmtdMdlID=" + mdlID + linkArgs);
    });
}

function enterKeyFuncOneMdl(e, elementID, modalBodyID, titleElementID, formElementID,
        formTitle, userID, vtyp, pgNo, actionText)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getOneMdlForm(elementID, modalBodyID, titleElementID, formElementID,
                formTitle, userID, vtyp, pgNo, actionText);
    }
}

function getAllSbgrps(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allSbgrpsSrchFor").val() === 'undefined' ? '%' : $("#allSbgrpsSrchFor").val();
    var srchIn = typeof $("#allSbgrpsSrchFor").val() === 'undefined' ? 'Both' : $("#allSbgrpsSrchFor").val();
    var pageNo = typeof $("#allSbgrpsPageNo").val() === 'undefined' ? 1 : $("#allSbgrpsPageNo").val();
    var limitSze = typeof $("#allSbgrpsDsplySze").val() === 'undefined' ? 10 : $("#allSbgrpsDsplySze").val();
    var sortBy = typeof $("#allSbgrpsSortBy").val() === 'undefined' ? '' : $("#allSbgrpsSortBy").val();
    var sbmtdMdlID = typeof $("#allMdlsToSlct").val() === 'undefined' ? -1 : $("#allMdlsToSlct").val();
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&sbmtdMdlID=" + sbmtdMdlID;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllSbgrps(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllSbgrps(actionText, slctr, linkArgs);
    }
}

function getOneSbgrpForm(elementID, modalBodyID, titleElementID, formElementID,
        formTitle, tblID, vtyp, pgNo, actionText)
{
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");

        var srchFor = typeof $("#sbgrpsLblsSrchFor").val() === 'undefined' ? '%' : $("#sbgrpsLblsSrchFor").val();
        var srchIn = typeof $("#sbgrpsLblsSrchIn").val() === 'undefined' ? 'Both' : $("#sbgrpsLblsSrchIn").val();
        var pageNo = typeof $("#sbgrpsLblsPageNo").val() === 'undefined' ? 1 : $("#sbgrpsLblsPageNo").val();
        var limitSze = typeof $("#sbgrpsLblsDsplySze").val() === 'undefined' ? 10 : $("#sbgrpsLblsDsplySze").val();
        var sortBy = typeof $("#sbgrpsLblsSortBy").val() === 'undefined' ? '' : $("#sbgrpsLblsSortBy").val();
        if (actionText == 'clear')
        {
            srchFor = "%";
            pageNo = 1;
        } else if (actionText == 'next')
        {
            pageNo = parseInt(pageNo) + 1;
        } else if (actionText == 'previous')
        {
            pageNo = parseInt(pageNo) - 1;
        }
        var linkArgs = "&searchfor=" + srchFor + "&searchin=" + srchIn +
                "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
        var xmlhttp;
        if (window.XMLHttpRequest)
        {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else
        {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
            {
                if (formTitle != '')
                {
                    $('#' + titleElementID).html(formTitle);
                }
                $('#' + modalBodyID).html(xmlhttp.responseText);
                /*$('.modal-dialog').draggable();*/
                if (vtyp == 1)
                {
                    var table1 = $('#sbgrpsLblsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#sbgrpsLblsTable').wrap('<div class="dataTables_scroll" />');

                    $('#sbgrpsLblsForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                }
                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");

                $('#' + formElementID).submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=3&typ=1&pg=" + pgNo + "&vtyp=" + vtyp + "&sbmtdTblID=" + tblID + linkArgs);
    });
}

function enterKeyFuncOneSbgrp(e, elementID, modalBodyID, titleElementID, formElementID,
        formTitle, tblID, vtyp, pgNo, actionText)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getOneSbgrpForm(elementID, modalBodyID, titleElementID, formElementID,
                formTitle, tblID, vtyp, pgNo, actionText);
    }
}
function afterOneSbgrpLblSlctn()
{
    var sbmtdTblID = typeof $("#sbmtdTblID").val() === 'undefined' ? '-1' : $("#sbmtdTblID").val();
    var sbgrpsLblsSlctdLabels = typeof $("#sbgrpsLblsSlctdLabels").val() === 'undefined' ? '-1' : $("#sbgrpsLblsSlctdLabels").val();
    if (sbmtdTblID > 0) {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append('grp', 3);
            formData.append('typ', 1);
            formData.append('pg', 4);
            formData.append('q', 'VIEW');
            formData.append('vtyp', 2);
            formData.append('sbmtdTblID', sbmtdTblID);
            formData.append('sbgrpsLblsSlctdLabels', sbgrpsLblsSlctdLabels);
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.message.indexOf("Success") !== -1) {
                        getOneSbgrpForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'sbgrpsLblsForm', '', sbmtdTblID, 1, 4, '');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.warn(jqXHR.responseText);
                }
            });
        });
    }
}

function enblDsblSbgrpLblSlctn(sbmtdCmbntnID, enableDsbl)
{
    var sbmtdTblID = typeof $("#sbmtdTblID").val() === 'undefined' ? '-1' : $("#sbmtdTblID").val();
    if (sbmtdCmbntnID > 0) {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append('grp', 3);
            formData.append('typ', 1);
            formData.append('pg', 4);
            formData.append('q', 'VIEW');
            formData.append('vtyp', 3);
            formData.append('sbmtdCmbntnID', sbmtdCmbntnID);
            formData.append('enableDsbl', enableDsbl);
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.message.indexOf("Success") !== -1) {
                        getOneSbgrpForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'sbgrpsLblsForm', '', sbmtdTblID, 1, 4, '');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.warn(jqXHR.responseText);
                }
            });
        });
    }
}
function deleteSbgrpLblSlctn(sbmtdCmbntnID)
{
    var msgPrt = "Extra Info Label";
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPrt + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPrt + '?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result === true)
            {
                var sbmtdTblID = typeof $("#sbmtdTblID").val() === 'undefined' ? '-1' : $("#sbmtdTblID").val();
                if (sbmtdCmbntnID > 0) {
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                        $body = $("body");
                        $body.removeClass("mdlloadingDiag");
                        $body.removeClass("mdlloading");
                        var obj;
                        var formData = new FormData();
                        formData.append('grp', 3);
                        formData.append('typ', 1);
                        formData.append('pg', 4);
                        formData.append('q', 'VIEW');
                        formData.append('vtyp', 4);
                        formData.append('sbmtdCmbntnID', sbmtdCmbntnID);
                        $.ajax({
                            url: 'index.php',
                            method: 'POST',
                            data: formData,
                            async: true,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                if (data.message.indexOf("Success") !== -1) {
                                    getOneSbgrpForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'sbgrpsLblsForm', '', sbmtdTblID, 1, 4, '');
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.warn(jqXHR.responseText);
                            }
                        });
                    });
                }
            }
        }
    });
}

function getAllSecPlcys(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allSecPlcysSrchFor").val() === 'undefined' ? '%' : $("#allSecPlcysSrchFor").val();
    var srchIn = typeof $("#allSecPlcysSrchIn").val() === 'undefined' ? 'Both' : $("#allSecPlcysSrchIn").val();
    var pageNo = typeof $("#allSecPlcysPageNo").val() === 'undefined' ? 1 : $("#allSecPlcysPageNo").val();
    var limitSze = typeof $("#allSecPlcysDsplySze").val() === 'undefined' ? 10 : $("#allSecPlcysDsplySze").val();
    var sortBy = typeof $("#allSecPlcysSortBy").val() === 'undefined' ? '' : $("#allSecPlcysSortBy").val();
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllSecPlcys(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllSecPlcys(actionText, slctr, linkArgs);
    }
}

function getOneSecPlcyForm(plcyID, vwtype)
{
    var lnkArgs = 'grp=3&typ=1&pg=5&vtyp=' + vwtype + '&sbmtdPlcyID=' + plcyID;
    doAjax(lnkArgs, 'secPlcsDetailInfo', 'PasteDirect', '', '', '');
}

function saveSecPlcyForm()
{
    var lnkArgs = "";
    var scPlcysPlcyNm = typeof $("#scPlcysPlcyNm").val() === 'undefined' ? '' : $("#scPlcysPlcyNm").val();
    var scPlcysPlcyID = typeof $("#scPlcysPlcyID").val() === 'undefined' ? -1 : $("#scPlcysPlcyID").val();
    var scPlcysIsDflt = typeof $("input[name='scPlcysIsDflt']:checked").val() === 'undefined' ? '' : $("input[name='scPlcysIsDflt']:checked").val();
    var scPlcysPwdExpryDys = typeof $("#scPlcysPwdExpryDys").val() === 'undefined' ? '' : $("#scPlcysPwdExpryDys").val();
    var scPlcysNoOfRecs = typeof $("#scPlcysNoOfRecs").val() === 'undefined' ? '' : $("#scPlcysNoOfRecs").val();
    var scPlcysPwdMinLen = typeof $("#scPlcysPwdMinLen").val() === 'undefined' ? '' : $("#scPlcysPwdMinLen").val();
    var scPlcysPwdMaxLen = typeof $("#scPlcysPwdMaxLen").val() === 'undefined' ? '' : $("#scPlcysPwdMaxLen").val();
    var scPlcysPwdOldAlwd = typeof $("#scPlcysPwdOldAlwd").val() === 'undefined' ? '' : $("#scPlcysPwdOldAlwd").val();
    var scPlcysPwdUnmAlwd = typeof $("input[name='scPlcysPwdUnmAlwd']:checked").val() === 'undefined' ? '' : $("input[name='scPlcysPwdUnmAlwd']:checked").val();
    var scPlcysPwdRptngChars = typeof $("input[name='scPlcysPwdRptngChars']:checked").val() === 'undefined' ? '' : $("input[name='scPlcysPwdRptngChars']:checked").val();
    var scPlcysAlwdFailedLgs = typeof $("#scPlcysAlwdFailedLgs").val() === 'undefined' ? '' : $("#scPlcysAlwdFailedLgs").val();
    var scPlcysAutoUnlkTme = typeof $("#scPlcysAutoUnlkTme").val() === 'undefined' ? '' : $("#scPlcysAutoUnlkTme").val();
    var scPlcysChckBlkLtrs = typeof $("input[name='scPlcysChckBlkLtrs']:checked").val() === 'undefined' ? '' : $("input[name='scPlcysChckBlkLtrs']:checked").val();
    var scPlcysChkSmllLtrs = typeof $("input[name='scPlcysChkSmllLtrs']:checked").val() === 'undefined' ? '' : $("input[name='scPlcysChkSmllLtrs']:checked").val();
    var scPlcysChkDgts = typeof $("input[name='scPlcysChkDgts']:checked").val() === 'undefined' ? '' : $("input[name='scPlcysChkDgts']:checked").val();
    var scPlcysChkWild = typeof $("input[name='scPlcysChkWild']:checked").val() === 'undefined' ? '' : $("input[name='scPlcysChkWild']:checked").val();
    var scPlcysCombinations = typeof $("#scPlcysCombinations").val() === 'undefined' ? '' : $("#scPlcysCombinations").val();
    var scPlcysSesnTmeOut = typeof $("#scPlcysSesnTmeOut").val() === 'undefined' ? '' : $("#scPlcysSesnTmeOut").val();

    if (scPlcysPlcyNm === "" || scPlcysPlcyNm === null)
    {
        $('#modal-7 .modal-body').html('Policy Name cannot be empty!');
        $('#modal-7').modal('show', {backdrop: 'static'});
        return false;
    }
    if (scPlcysCombinations === "" || scPlcysCombinations === null)
    {
        $('#modal-7 .modal-body').html('Combinations cannot be empty!');
        $('#modal-7').modal('show', {backdrop: 'static'});
        return false;
    }
    lnkArgs = "grp=3&typ=1&pg=5&q=UPDATE&actyp=1" +
            "&scPlcysPlcyID=" + scPlcysPlcyID +
            "&scPlcysPlcyNm=" + scPlcysPlcyNm +
            "&scPlcysIsDflt=" + scPlcysIsDflt +
            "&scPlcysPwdExpryDys=" + scPlcysPwdExpryDys +
            "&scPlcysNoOfRecs=" + scPlcysNoOfRecs +
            "&scPlcysPwdMinLen=" + scPlcysPwdMinLen +
            "&scPlcysPwdMaxLen=" + scPlcysPwdMaxLen +
            "&scPlcysPwdOldAlwd=" + scPlcysPwdOldAlwd +
            "&scPlcysPwdUnmAlwd=" + scPlcysPwdUnmAlwd +
            "&scPlcysPwdRptngChars=" + scPlcysPwdRptngChars +
            "&scPlcysAlwdFailedLgs=" + scPlcysAlwdFailedLgs +
            "&scPlcysAutoUnlkTme=" + scPlcysAutoUnlkTme +
            "&scPlcysChckBlkLtrs=" + scPlcysChckBlkLtrs +
            "&scPlcysChkSmllLtrs=" + scPlcysChkSmllLtrs +
            "&scPlcysChkDgts=" + scPlcysChkDgts +
            "&scPlcysChkWild=" + scPlcysChkWild +
            "&scPlcysCombinations=" + scPlcysCombinations +
            "&scPlcysSesnTmeOut=" + scPlcysSesnTmeOut;
    var slctdAdtTbls = "";
    $('#secPlcyAdtTblsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            var rndmNum = $(el).attr('id').split("_")[1];
            slctdAdtTbls = slctdAdtTbls + $('#secPlcyAdtTblsRow' + rndmNum + '_EnblTrckng').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                    + $('#secPlcyAdtTblsRow' + rndmNum + '_ActnsToTrck').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                    + $('#secPlcyAdtTblsRow' + rndmNum + '_MdlID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                    + $('#secPlcyAdtTblsRow' + rndmNum + '_DfltRwID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
        }
    });
    lnkArgs = lnkArgs + "&slctdAdtTbls=" + slctdAdtTbls.slice(0, -1);
    doAjax(lnkArgs, 'myFormsModal', 'ShowDialog', 'System Alert!', 'myFormsModalTitle', 'myFormsModalBody');
}

function getAllSrvrStngs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allSrvrStngsSrchFor").val() === 'undefined' ? '%' : $("#allSrvrStngsSrchFor").val();
    var srchIn = typeof $("#allSrvrStngsSrchIn").val() === 'undefined' ? 'Both' : $("#allSrvrStngsSrchIn").val();
    var pageNo = typeof $("#allSrvrStngsPageNo").val() === 'undefined' ? 1 : $("#allSrvrStngsPageNo").val();
    var limitSze = typeof $("#allSrvrStngsDsplySze").val() === 'undefined' ? 10 : $("#allSrvrStngsDsplySze").val();
    var sortBy = typeof $("#allSrvrStngsSortBy").val() === 'undefined' ? '' : $("#allSrvrStngsSortBy").val();
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllSrvrStngs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllSrvrStngs(actionText, slctr, linkArgs);
    }
}

function getOneSrvrStngForm(srvrID, vwtype)
{
    var lnkArgs = 'grp=3&typ=1&pg=6&vtyp=' + vwtype + '&sbmtdSrvrStngID=' + srvrID;
    doAjax(lnkArgs, 'srvrStngsDetailInfo', 'PasteDirect', '', '', '');
}

function saveSrvrStngForm()
{
    var lnkArgs = "";
    var srvrStnSmtpClnt = typeof $("#srvrStnSmtpClnt").val() === 'undefined' ? '' : $("#srvrStnSmtpClnt").val();
    var srvrStnSmtpClntID = typeof $("#srvrStnSmtpClntID").val() === 'undefined' ? -1 : $("#srvrStnSmtpClntID").val();
    var srvrStnIsDflt = typeof $("input[name='srvrStnIsDflt']:checked").val() === 'undefined' ? '' : $("input[name='srvrStnIsDflt']:checked").val();
    var srvrStnSndrsEmail = typeof $("#srvrStnSndrsEmail").val() === 'undefined' ? '' : $("#srvrStnSndrsEmail").val();
    var srvrStnSndrsPswd = typeof $("#srvrStnSndrsPswd").val() === 'undefined' ? '' : $("#srvrStnSndrsPswd").val();
    var srvrStnSmtPort = typeof $("#srvrStnSmtPort").val() === 'undefined' ? '' : $("#srvrStnSmtPort").val();
    var srvrStnDomainNm = typeof $("#srvrStnDomainNm").val() === 'undefined' ? '' : $("#srvrStnDomainNm").val();
    var srvrStnFTPUrl = typeof $("#srvrStnFTPUrl").val() === 'undefined' ? '' : $("#srvrStnFTPUrl").val();
    var srvrStnFTPUsrNm = typeof $("#srvrStnFTPUsrNm").val() === 'undefined' ? '' : $("#srvrStnFTPUsrNm").val();
    var srvrStnFTPPswd = typeof $("#srvrStnFTPPswd").val() === 'undefined' ? '' : $("#srvrStnFTPPswd").val();
    var srvrStnFTPPort = typeof $("#srvrStnFTPPort").val() === 'undefined' ? '' : $("#srvrStnFTPPort").val();
    var srvrStnFTPStrtDir = typeof $("#srvrStnFTPStrtDir").val() === 'undefined' ? '' : $("#srvrStnFTPStrtDir").val();
    var srvrStnFTPBaseDir = typeof $("#srvrStnFTPBaseDir").val() === 'undefined' ? '' : $("#srvrStnFTPBaseDir").val();
    var srvrStnEnforceFTP = typeof $("input[name='srvrStnEnforceFTP']:checked").val() === 'undefined' ? '' : $("input[name='srvrStnEnforceFTP']:checked").val();
    var srvrStnComPort = typeof $("#srvrStnComPort").val() === 'undefined' ? '' : $("#srvrStnComPort").val();
    var srvrStnBaudRate = typeof $("#srvrStnBaudRate").val() === 'undefined' ? '' : $("#srvrStnBaudRate").val();
    var srvrStnTimeout = typeof $("#srvrStnTimeout").val() === 'undefined' ? '' : $("#srvrStnTimeout").val();
    var srvrStnPGDumpDir = typeof $("#srvrStnPGDumpDir").val() === 'undefined' ? '' : $("#srvrStnPGDumpDir").val();
    var srvrStnBkpDir = typeof $("#srvrStnBkpDir").val() === 'undefined' ? '' : $("#srvrStnBkpDir").val();
    var srvrStnSmtpIP = typeof $("#srvrStnSmtpIP").val() === 'undefined' ? '' : $("#srvrStnSmtpIP").val();

    if (srvrStnSmtpClnt === "" || srvrStnSmtpClnt === null || srvrStnSmtpIP === "" || srvrStnSmtpIP === null)
    {
        $('#modal-7 .modal-body').html('SMTP Client and SMTP IP cannot be empty!');
        $('#modal-7').modal('show', {backdrop: 'static'});
        return false;
    }
    if (srvrStnSndrsEmail === "" || srvrStnSndrsEmail === null)
    {
        $('#modal-7 .modal-body').html('Sender\'s Email cannot be empty!');
        $('#modal-7').modal('show', {backdrop: 'static'});
        return false;
    }
    lnkArgs = "grp=3&typ=1&pg=6&q=UPDATE&actyp=1" +
            "&srvrStnSmtpClntID=" + srvrStnSmtpClntID +
            "&srvrStnSmtpClnt=" + srvrStnSmtpClnt +
            "&srvrStnIsDflt=" + srvrStnIsDflt +
            "&srvrStnSndrsEmail=" + srvrStnSndrsEmail +
            "&srvrStnSndrsPswd=" + srvrStnSndrsPswd +
            "&srvrStnSmtPort=" + srvrStnSmtPort +
            "&srvrStnDomainNm=" + srvrStnDomainNm +
            "&srvrStnFTPUrl=" + srvrStnFTPUrl +
            "&srvrStnFTPUsrNm=" + srvrStnFTPUsrNm +
            "&srvrStnFTPPswd=" + srvrStnFTPPswd +
            "&srvrStnFTPPort=" + srvrStnFTPPort +
            "&srvrStnFTPStrtDir=" + srvrStnFTPStrtDir +
            "&srvrStnFTPBaseDir=" + srvrStnFTPBaseDir +
            "&srvrStnEnforceFTP=" + srvrStnEnforceFTP +
            "&srvrStnComPort=" + srvrStnComPort +
            "&srvrStnBaudRate=" + srvrStnBaudRate +
            "&srvrStnTimeout=" + srvrStnTimeout +
            "&srvrStnPGDumpDir=" + srvrStnPGDumpDir +
            "&srvrStnBkpDir=" + srvrStnBkpDir +
            "&srvrStnSmtpIP=" + srvrStnSmtpIP;
    var slctdApiVals = "";
    $('#srvrStngSmsApiTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            var rndmNum = $(el).attr('id').split("_")[1];
            slctdApiVals = slctdApiVals + $('#srvrStngSmsApiRow' + rndmNum + '_Param').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                    + $('#srvrStngSmsApiRow' + rndmNum + '_Value').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
        }
    });
    lnkArgs = lnkArgs + "&slctdApiVals=" + slctdApiVals.slice(0, -1);
    doAjax(lnkArgs, 'myFormsModal', 'ShowDialog', 'System Alert!', 'myFormsModalTitle', 'myFormsModalBody');
}

function getTrckUsrLgns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#trckUsrLgnsSrchFor").val() === 'undefined' ? '%' : $("#trckUsrLgnsSrchFor").val();
    var srchIn = typeof $("#trckUsrLgnsSrchIn").val() === 'undefined' ? 'Both' : $("#trckUsrLgnsSrchIn").val();
    var pageNo = typeof $("#trckUsrLgnsPageNo").val() === 'undefined' ? 1 : $("#trckUsrLgnsPageNo").val();
    var limitSze = typeof $("#trckUsrLgnsDsplySze").val() === 'undefined' ? 10 : $("#trckUsrLgnsDsplySze").val();
    var sortBy = typeof $("#trckUsrLgnsSortBy").val() === 'undefined' ? '' : $("#trckUsrLgnsSortBy").val();
    var qShwFailedOnly = $('#trckUsrLgnsFailedOnly:checked').length > 0;
    var qShwSccflOnly = $('#trckUsrLgnsSccflOnly:checked').length > 0;
    var qShwActvOnly = $('#trckUsrLgnsActvOnly:checked').length > 0;
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
        qShwFailedOnly = "false";
        qShwSccflOnly = "false";
        qShwActvOnly = "false";
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy
            + "&qShwFailedOnly=" + qShwFailedOnly + "&qShwSccflOnly=" + qShwSccflOnly + "&qShwActvOnly=" + qShwActvOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncTrckUsrLgns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getTrckUsrLgns(actionText, slctr, linkArgs);
    }
}

function getOneUsrLgnDet(pKeyID, lnkArgs, actionTxt)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    lnkArgs = lnkArgs + '&sbmtdLgnNum=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'User Logon Activity Logs (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {});
    $('#myFormsModalLg').off('hidden.bs.modal');
}

function forceUsrLgout(pKeyID)
{
    if (pKeyID <= 0) {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: 'Please select an Active Login First!'});
        return false;
    }
    var dialog = bootbox.confirm({
        title: 'Force Logout?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FORCE LOGOUT</span> this LOGON?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Force Logout?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Logging Out Selected Logon...Please Wait...</p>',
                    callback: function () {
                        getTrckUsrLgns('', '#allmodules', 'grp=3&typ=1&pg=7&vtyp=0');
                    }
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 3,
                                    typ: 1,
                                    pg: 7,
                                    actyp: 1,
                                    q: 'DELETE',
                                    lgnUserID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
                        setTimeout(function () {
                            dialog1.find('.bootbox-body').html('<span style="color:red;">Nothing to Withdraw!</span>');
                        }, 500);
                    }
                });
            }
        }
    });

}

function getAdtTrails(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#adtTrailsSrchFor").val() === 'undefined' ? '%' : $("#adtTrailsSrchFor").val();
    var srchIn = typeof $("#adtTrailsSrchFor").val() === 'undefined' ? 'Both' : $("#adtTrailsSrchFor").val();
    var pageNo = typeof $("#adtTrailsPageNo").val() === 'undefined' ? 1 : $("#adtTrailsPageNo").val();
    var limitSze = typeof $("#adtTrailsDsplySze").val() === 'undefined' ? 10 : $("#adtTrailsDsplySze").val();
    var sortBy = typeof $("#adtTrailsSortBy").val() === 'undefined' ? '' : $("#adtTrailsSortBy").val();
    var sbmtdMdlID = typeof $("#allMdlsToSlct").val() === 'undefined' ? -1 : $("#allMdlsToSlct").val();
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&sbmtdMdlID=" + sbmtdMdlID;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAdtTrails(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAdtTrails(actionText, slctr, linkArgs);
    }
}

function lockUnlock(rowIDAttrb, action)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var usrNm = "";
    if (typeof $('#allUsersRow' + rndmNum + '_UserID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#allUsersRow' + rndmNum + '_UserID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        usrNm = $.trim($tds.eq(1).text());
    }
    var msgTxt = action + " User";
    var dialog = bootbox.confirm({
        title: msgTxt + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;font-family:georgia, times;">' + msgTxt + '</span>?<br/></p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: msgTxt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Working...Please Wait...</p>'
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 3,
                                    typ: 1,
                                    pg: 1,
                                    q: 'UPDATE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    nwStatus: action.toUpperCase(),
                                    usrNm: usrNm
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            getAllUsers('', '#allmodules', 'grp=3&typ=1&pg=1&vtyp=0');
                                            dialog1.modal('hide');
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                    dialog1.modal('hide');
                                }
                            });
                        });
                    } else
                    {
                        setTimeout(function () {
                            getAllUsers('', '#allmodules', 'grp=3&typ=1&pg=1&vtyp=0');
                            dialog1.modal('hide');
                        }, 500);
                    }
                });
            }
        }
    });
}


function suspendUnsuspend(rowIDAttrb, action)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var usrNm = "";
    if (typeof $('#allUsersRow' + rndmNum + '_UserID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#allUsersRow' + rndmNum + '_UserID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        usrNm = $.trim($tds.eq(1).text());
    }
    var msgTxt = action + " User";
    var dialog = bootbox.confirm({
        title: msgTxt + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;font-family:georgia, times;">' + msgTxt + '</span>?<br/></p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: msgTxt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Working...Please Wait...</p>'
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 3,
                                    typ: 1,
                                    pg: 1,
                                    q: 'UPDATE',
                                    actyp: 3,
                                    pKeyID: pKeyID,
                                    nwStatus: action.toUpperCase(),
                                    usrNm: usrNm
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            getAllUsers('', '#allmodules', 'grp=3&typ=1&pg=1&vtyp=0');
                                            dialog1.modal('hide');
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                    dialog1.modal('hide');
                                }
                            });
                        });
                    } else
                    {
                        setTimeout(function () {
                            getAllUsers('', '#allmodules', 'grp=3&typ=1&pg=1&vtyp=0');
                            dialog1.modal('hide');
                        }, 500);
                    }
                });
            }
        }
    });
}

function delUser(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var usrNm = "";
    if (typeof $('#allUsersRow' + rndmNum + '_UserID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#allUsersRow' + rndmNum + '_UserID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        usrNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Survey/Election?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Survey/Election?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Delete Survey/Election?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Survey/Election...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right", "0px");
                    }
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 3,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    usrNm: usrNm
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find('.bootbox-body').html('Row Removed Successfully!');
                        }, 500);
                    }
                });
            }
        }
    });
}