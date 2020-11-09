function prepareInbox(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        if (lnkArgs.indexOf("&pg=4&vtyp=2") !== -1)
        {
            var table1 = $('#allInbxTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allInbxTable').wrap('<div class="dataTables_scroll"/>');
            $('#allInbxForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('.form_date').datetimepicker({
                format: "dd-M-yyyy",
                language: 'en',
                weekStart: 0,
                todayBtn: true,
                autoclose: true,
                todayHighlight: true,
                keyboardNavigation: true,
                startView: 2,
                minView: 2,
                maxView: 4,
                forceParse: true
            });

            $('#allInbxTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        } else if (lnkArgs.indexOf("&vtyp=0") !== -1)
        {
            var table = $('#myInbxTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myInbxTable').wrap('<div class="dataTables_scroll"/>');

            $('.form_date').datetimepicker({
                format: "dd-M-yyyy",
                language: 'en',
                weekStart: 0,
                todayBtn: true,
                autoclose: true,
                todayHighlight: true,
                keyboardNavigation: true,
                startView: 2,
                minView: 2,
                maxView: 4,
                forceParse: true
            });
            $('#myInbxTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        }
    });
    htBody.removeClass("mdlloading");
}


function getMyInbx(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#myInbxSrchFor").val() === 'undefined' ? '%' : $("#myInbxSrchFor").val();
    var srchIn = typeof $("#myInbxSrchIn").val() === 'undefined' ? 'Both' : $("#myInbxSrchIn").val();
    var pageNo = typeof $("#myInbxPageNo").val() === 'undefined' ? 1 : $("#myInbxPageNo").val();
    var limitSze = typeof $("#myInbxDsplySze").val() === 'undefined' ? 10 : $("#myInbxDsplySze").val();
    var sortBy = typeof $("#myInbxSortBy").val() === 'undefined' ? '' : $("#myInbxSortBy").val();
    var qStrtDte = typeof $("#myInbxStrtDate").val() === 'undefined' ? '' : $("#myInbxStrtDate").val();
    var qEndDte = typeof $("#myInbxEndDate").val() === 'undefined' ? '' : $("#myInbxEndDate").val();
    var qActvOnly = $('#myInbxShwActvNtfs:checked').length > 0;
    var qNonLgn = $('#myInbxShwNonLgnNtfs:checked').length > 0;
    var qNonAknwldg = $('#myInbxShwNonAknwNtfs:checked').length > 0;
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
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy
            + "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte + "&qActvOnly=" + qActvOnly + "&qNonLgn=" + qNonLgn + "&qNonAknwldg=" + qNonAknwldg;
//alert(linkArgs);
    openATab(slctr, linkArgs);
}

function enterKeyFuncMyInbx(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getMyInbx(actionText, slctr, linkArgs);
    }
}

function getOneMyInbxForm(elementID, modalBodyID, titleElementID, formElementID,
        formTitle, routingID, vtyp, pgNo)
{
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
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

                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).off('show.bs.modal');
                $('#' + elementID).one('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                    $(e.currentTarget).unbind();
                });

                $(document).ready(function () {
                    $('#myInbxDetTable').wrap('<div class="dataTables_scroll"/>');
                    var table1 = $('#myInbxActionsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#myInbxActionsTable').wrap('<div class="dataTables_scroll"/>');
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
        xmlhttp.send("grp=40&typ=2&pg=" + pgNo + "&vtyp=" + vtyp + "&RoutingID=" + routingID);
    });
}

function getAllInbx(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allInbxSrchFor").val() === 'undefined' ? '%' : $("#allInbxSrchFor").val();
    var srchIn = typeof $("#allInbxSrchIn").val() === 'undefined' ? 'Both' : $("#allInbxSrchIn").val();
    var pageNo = typeof $("#allInbxPageNo").val() === 'undefined' ? 1 : $("#allInbxPageNo").val();
    var limitSze = typeof $("#allInbxDsplySze").val() === 'undefined' ? 10 : $("#allInbxDsplySze").val();
    var sortBy = typeof $("#allInbxSortBy").val() === 'undefined' ? '' : $("#allInbxSortBy").val();
    var qStrtDte = typeof $("#allInbxStrtDate").val() === 'undefined' ? '' : $("#allInbxStrtDate").val();
    var qEndDte = typeof $("#allInbxEndDate").val() === 'undefined' ? '' : $("#allInbxEndDate").val();
    var qActvOnly = $('#allInbxShwActvNtfs:checked').length > 0;
    var qNonLgn = $('#allInbxShwNonLgnNtfs:checked').length > 0;
    var qNonAknwldg = $('#allInbxShwNonAknwNtfs:checked').length > 0;
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
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy
            + "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte + "&qActvOnly=" + qActvOnly + "&qNonLgn=" + qNonLgn + "&qNonAknwldg=" + qNonAknwldg;

    openATab(slctr, linkArgs);
}

function enterKeyFuncAllInbx(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllInbx(actionText, slctr, linkArgs);
    }
}

function getOneAllInbxForm(elementID, modalBodyID, titleElementID, formElementID,
        formTitle, routingID, vtyp, pgNo)
{
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
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

                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).off('show.bs.modal');
                $('#' + elementID).one('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                    $(e.currentTarget).unbind();
                });

                $(document).ready(function () {
                    $('#allInbxDetTable').wrap('<div class="dataTables_scroll"/>');
                    var table1 = $('#allInbxActionsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#allInbxActionsTable').wrap('<div class="dataTables_scroll"/>');
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
        xmlhttp.send("grp=40&typ=2&pg=" + pgNo + "&vtyp=" + vtyp + "&RoutingID=" + routingID);
    });
}

function actionProcess(cmpID, RoutingID, actionNm, isResDiag,
        toPrsnLocID, toPrsNm, msgSubjct, msgDate, wkfAppNm)
{
    if (actionNm === "Reject")
    {
        onReject(RoutingID, msgSubjct, msgDate);
    } else if (actionNm === "Request for Information")
    {
        onInfoRequest(RoutingID, 1, msgSubjct, msgDate, toPrsnLocID, toPrsNm);
    } else if (actionNm === "Respond")
    {
        onResponse(RoutingID, 1, msgSubjct, msgDate, toPrsnLocID, toPrsNm);
    } else if (actionNm === "View Attachments")
    {
        onAttachment(RoutingID, msgSubjct);
    } else
    {
        onAct(RoutingID, actionNm, isResDiag,
                '', toPrsnLocID, wkfAppNm);
    }
}

function  onReject(RoutingID, msgSubjct, msgDate) {
    var lnkArgs = 'grp=1&typ=10&pg=0&vtyp=3&RoutingID=' + RoutingID + '&msgSubjct=' + msgSubjct + '&msgDate=' + msgDate;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', '  REJECTION OF SELECTED WORKFLOW DOCUMENT', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        $('#wkfActionMsg').focus();
    });
}

function  onInfoRequest(RoutingID, id, msgSubjct, msgDate, toPrsnLocID, toPrsNm) {
    var lnkArgs = 'grp=1&typ=10&pg=0&vtyp=4&RoutingID=' + RoutingID + '&msgSubjct=' + msgSubjct + '&msgDate=' + msgDate;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', '  REQUEST INFORMATION ON SELECTED WORKFLOW DOCUMENT', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        $('#wkfActionMsg').focus();
    });
}

function  onResponse(RoutingID, id, msgSubjct, msgDate, toPrsnLocID, toPrsNm) {
    var lnkArgs = 'grp=1&typ=10&pg=0&vtyp=5&RoutingID=' + RoutingID + '&msgSubjct=' + msgSubjct + '&msgDate=' + msgDate + '&toPrsnLocID=' + toPrsnLocID + '&toPrsNm=' + toPrsNm;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', '  RESPONSE TO INFORMATION REQUEST ON SELECTED WORKFLOW DOCUMENTS', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        $('#wkfActionMsg').focus();
    });
}

function  onAttachment(RoutingID, msgSubjct) {
    var lnkArgs = 'grp=1&typ=10&pg=0&vtyp=6&RoutingID=' + RoutingID + '&msgSubjct=' + msgSubjct;
    doAjaxWthCallBck(lnkArgs, 'myFormsModal', 'ShowDialog', ' Attachments for Notification Number: ' + RoutingID, 'myFormsModalTitle', 'myFormsModalBody', function () {
        $('#wkfActionMsg').focus();
    });
}

function onReassign(elmntID) {
    var inRoutingIDs = "";
    var inCount = 0;

    var form = document.getElementById(elmntID);
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type === 'checkbox') {
            if (form.elements[i].checked === true)
            {
                inRoutingIDs = inRoutingIDs + form.elements[i].value.split(";")[0] + '|';
                inCount++;
            }
        }
    }
    if (inRoutingIDs.length > 1)
    {
        inRoutingIDs = inRoutingIDs.slice(0, -1);
    }
    var lnkArgs = 'grp=1&typ=10&pg=0&vtyp=7&routingIDs=' + inRoutingIDs + '&inCount=' + inCount;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', '  RE-ASSIGN SELECTED WORKFLOW DOCUMENTS', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        $('#wkfActionMsg').focus();
    });
}

function  onAct(RoutingID, actionNm, isResDiag, actionReason, toPrsnLocID, wkfAppNm, wkfSlctdRoutingIDs) {
    if (actionNm === 'Open'
            || actionNm === 'Reject'
            || actionNm === 'Request for Information'
            || actionNm === 'Respond')
    {
        var dialog = bootbox.alert({
            title: 'Action Pending...',
            size: 'small',
            message: '<p><i class="fa fa-spin fa-spinner"></i> Performing Action...Please Wait...</p>',
            callback: function () {
                $('#myFormsModalLg').modal('hide');
                $('#myFormsModalx').modal('hide');
            }
        });
        dialog.init(function () {
            getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                $body = $("body");
                $body.removeClass("mdlloading");

                var formData = new FormData();
                formData.append('grp', 1);
                formData.append('typ', 11);
                formData.append('q', 'action_url');
                formData.append('actyp', 1);
                formData.append('RoutingID', RoutingID);
                formData.append('actyp', actionNm);
                formData.append('actReason', actionReason);
                formData.append('toPrsLocID', toPrsnLocID);
                $.ajax({
                    method: "POST",
                    url: "index.php",
                    data: formData,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        var urlParams = result;
                        if (urlParams.indexOf('|ERROR|') > -1)
                        {
                            setTimeout(function () {
                                dialog.find('.bootbox-body').html(result);
                            }, 500);
                        } else
                        {
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: urlParams.trim(),
                                success: function (result1) {
                                    setTimeout(function () {
                                        if (isResDiag >= 1) {
                                            dialog.find('.bootbox-body').html(result1);
                                            if (!(typeof $("#allInbxSrchFor").val() === 'undefined'))
                                            {
                                                getAllInbx('', '#allmodules', 'grp=40&typ=2&pg=0&vtyp=2&qMaster=1');
                                            }
                                            if (!(typeof $("#myInbxSrchFor").val() === 'undefined'))
                                            {
                                                getMyInbx('', '#myinbox', 'grp=40&typ=2&pg=0&vtyp=0');
                                            }
                                        } else {
                                            //Show Larger DIalog
                                            dialog.modal('hide');
                                            $('#myFormsModalLxTitle').html('Action Result!');
                                            $('#myFormsModalLxBody').html(result1);

                                            $('#myFormsModalLx').off('hidden.bs.modal');
                                            $('#myFormsModalLx').off('show.bs.modal');
                                            $('#myFormsModalLx').one('show.bs.modal', function (e) {
                                                $(this).find('.modal-body').css({
                                                    'max-height': '100%'
                                                });
                                                $(e.currentTarget).unbind();
                                            });
                                            $('#myFormsModalLx').modal({backdrop: 'static', keyboard: false});
                                            afterShowActions(wkfAppNm);
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    console.warn(jqXHR.responseText);
                                }
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        /*dialog.find('.bootbox-body').html(errorThrown);*/
                        console.warn(jqXHR.responseText);
                    }});
            });
        });
    } else if (actionNm === 'Re-Assign') {
        var dialog = bootbox.alert({
            title: 'Action Pending...',
            size: 'small',
            message: '<p><i class="fa fa-spin fa-spinner"></i> Performing Action...Please Wait...</p>',
            callback: function () {
                $('#myFormsModalLg').modal('hide');
                $('#myFormsModalx').modal('hide');
            }
        });
        dialog.init(function () {
            getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                $body = $("body");
                $body.removeClass("mdlloading");

                var formData = new FormData();
                formData.append('grp', 40);
                formData.append('typ', 2);
                formData.append('q', 'UPDATE');
                formData.append('vtyp', 0);
                formData.append('actyp', 1);
                formData.append('RoutingID', RoutingID);
                formData.append('actionNm', actionNm);
                formData.append('actReason', actionReason);
                formData.append('toPrsLocID', toPrsnLocID);
                formData.append('wkfSlctdRoutingIDs', wkfSlctdRoutingIDs);
                $.ajax({
                    method: "POST",
                    url: "index.php",
                    data: formData,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        setTimeout(function () {
                            dialog.find('.bootbox-body').html(result);
                            if (!(typeof $("#allInbxSrchFor").val() === 'undefined'))
                            {
                                getAllInbx('', '#allmodules', 'grp=40&typ=2&pg=0&vtyp=2&qMaster=1');
                            }
                            if (!(typeof $("#myInbxSrchFor").val() === 'undefined'))
                            {
                                getMyInbx('', '#myinbox', 'grp=40&typ=2&pg=0&vtyp=0');
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
    } else {
        var dialog = bootbox.confirm({
            title: 'Action Pending...',
            size: 'small',
            message: '<p style="text-align:center;">Are you sure you want to Perform this Action <span style="color:red;font-weight:bold;font-style:italic;">(' + actionNm + ')</span> on this Notifications?<br/>Action cannot be Undone!</p>',
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
                    var dialog = bootbox.alert({
                        title: 'Action Pending...',
                        size: 'small',
                        message: '<p><i class="fa fa-spin fa-spinner"></i> Performing Action...Please Wait...</p>',
                        callback: function () {
                            $('#myFormsModalLg').modal('hide');
                            $('#myFormsModalx').modal('hide');
                        }
                    });
                    dialog.init(function () {
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");

                            var formData = new FormData();
                            formData.append('grp', 1);
                            formData.append('typ', 11);
                            formData.append('q', 'action_url');
                            formData.append('actyp', 1);
                            formData.append('RoutingID', RoutingID);
                            formData.append('actyp', actionNm);
                            formData.append('actReason', actionReason);
                            formData.append('toPrsLocID', toPrsnLocID);
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: formData,
                                async: true,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function (result) {
                                    var urlParams = result;
                                    if (urlParams.indexOf('|ERROR|') > -1)
                                    {
                                        setTimeout(function () {
                                            dialog.find('.bootbox-body').html(result);
                                        }, 500);
                                    } else
                                    {
                                        $.ajax({
                                            method: "POST",
                                            url: "index.php",
                                            data: urlParams.trim(),
                                            success: function (result1) {
                                                setTimeout(function () {
                                                    if (isResDiag >= 1) {
                                                        dialog.find('.bootbox-body').html(result1);
                                                        if (!(typeof $("#allInbxSrchFor").val() === 'undefined'))
                                                        {
                                                            getAllInbx('', '#allmodules', 'grp=40&typ=2&pg=0&vtyp=2&qMaster=1');
                                                        }
                                                        if (!(typeof $("#myInbxSrchFor").val() === 'undefined'))
                                                        {
                                                            getMyInbx('', '#myinbox', 'grp=40&typ=2&pg=0&vtyp=0');
                                                        }
                                                    } else {
                                                        //Show Larger DIalog
                                                        dialog.modal('hide');
                                                        BootstrapDialog.show({
                                                            size: BootstrapDialog.SIZE_WIDE,
                                                            type: BootstrapDialog.TYPE_DEFAULT,
                                                            title: 'Action Result!',
                                                            message: result1,
                                                            animate: true,
                                                            closable: true,
                                                            closeByBackdrop: false,
                                                            closeByKeyboard: false,
                                                            onshow: function (dialog) {
                                                            },
                                                            buttons: [{
                                                                    label: 'Close',
                                                                    icon: 'glyphicon glyphicon-ban-circle',
                                                                    action: function (dialogItself) {
                                                                        dialogItself.close();
                                                                    }
                                                                }]
                                                        });
                                                    }
                                                }, 500);
                                            },
                                            error: function (jqXHR1, textStatus1, errorThrown1)
                                            {
                                                console.warn(jqXHR.responseText);
                                            }
                                        });
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown)
                                {
                                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                                    console.warn(jqXHR.responseText);
                                }});
                        });
                    });
                }
            }
        });
    }
}

function directApprove(rowIDAttrb, actionsToPrfrm)
{
    var prfxNm = rowIDAttrb.split("_")[0];
    var rndmNum = rowIDAttrb.split("_")[1];
    var RoutingID = $('#' + prfxNm + '' + rndmNum + '_RoutingID').val();
    var msgTyp = $('#' + prfxNm + '' + rndmNum + '_MsgType').val();
    var actType = "Approve;Authorize;Acknowledge";
    if (msgTyp === 'Informational')
    {
        actType = "Acknowledge";
    } else if (actionsToPrfrm.indexOf("Approve") !== -1) {
        actType = "Approve";
    } else if (actionsToPrfrm.indexOf("Authorize") !== -1) {
        actType = "Authorize";
    } else if (actionsToPrfrm.indexOf("Acknowledge") !== -1) {
        actType = "Acknowledge";
    } else {
        actType = "Approve";
    }

    if (RoutingID <= 0)
    {
        var dialog = bootbox.alert({
            title: 'No Notification Selected',
            size: 'small',
            message: '<p style="text-align:center;"><i class="fa fa-exclamation-triangle" style="color:red;"></i> Please select a Notification First!</p>',
            callback: function () {
            }
        });
    } else
    {
        onAct(RoutingID, actType, 1, '', '', '');
    }
}

function directReject(rowIDAttrb)
{
    var prfxNm = rowIDAttrb.split("_")[0];
    var rndmNum = rowIDAttrb.split("_")[1];
    var RoutingID = $('#' + prfxNm + '' + rndmNum + '_RoutingID').val();
    var msgTyp = $('#' + prfxNm + '' + rndmNum + '_MsgType').val();
    var sbjct = $('#' + prfxNm + '' + rndmNum + '_MsgSubject').val();
    var dateSnt = $('#' + prfxNm + '' + rndmNum + '_DateSent').val();
    var rowCnt = 0;
    if (msgTyp === 'Informational')
    {
        var dialog = bootbox.alert({
            title: 'Informational Notification Selected',
            size: 'small',
            message: '<p style="text-align:center;"><i class="fa fa-exclamation-triangle" style="color:red;"></i> Cannot Reject an Informational Notification!</p>',
            callback: function () {
            }
        });
        return;
    } else if (RoutingID > 0)
    {
        rowCnt = 1;
    }
    if (rowCnt <= 0)
    {
        var dialog = bootbox.alert({
            title: 'No Document Based Notification Selected',
            size: 'small',
            message: '<p style="text-align:center;"><i class="fa fa-spin fa-circle"></i> Please select a Document Based Notification First!</p>',
            callback: function () {
            }
        });
    } else
    {
        onReject(RoutingID, sbjct, dateSnt);
    }
}

function directInfoRqst(rowIDAttrb)
{
    var prfxNm = rowIDAttrb.split("_")[0];
    var rndmNum = rowIDAttrb.split("_")[1];
    var RoutingID = $('#' + prfxNm + '' + rndmNum + '_RoutingID').val();
    var msgTyp = $('#' + prfxNm + '' + rndmNum + '_MsgType').val();
    var sbjct = $('#' + prfxNm + '' + rndmNum + '_MsgSubject').val();
    var dateSnt = $('#' + prfxNm + '' + rndmNum + '_DateSent').val();
    var toPrsLocID = $('#' + prfxNm + '' + rndmNum + '_FromPersonLocID').val();
    var toPrsNm = $('#' + prfxNm + '' + rndmNum + '_FromPerson').val();
    var rowCnt = 0;
    if (msgTyp === 'Informational')
    {
        var dialog = bootbox.alert({
            title: 'Informational Notification Selected',
            size: 'small',
            message: '<p style="text-align:center;"><i class="fa fa-exclamation-triangle" style="color:red;"></i> Cannot Request for Information on an Informational Notification!</p>',
            callback: function () {
            }
        });
        return;
    } else if (RoutingID > 0)
    {
        rowCnt = 1;
    }

    if (rowCnt <= 0)
    {
        var dialog = bootbox.alert({
            title: 'No Document Based Notification Selected',
            size: 'small',
            message: '<p style="text-align:center;"><i class="fa fa-exclamation-triangle" style="color:red;"></i> Please select a Document Based Notification First!</p>',
            callback: function () {
            }
        });
    } else
    {
        onInfoRequest(RoutingID, 1, sbjct, dateSnt, toPrsLocID, toPrsNm);
    }
}

function directAttachment(rowIDAttrb)
{
    var prfxNm = rowIDAttrb.split("_")[0];
    var rndmNum = rowIDAttrb.split("_")[1];
    var RoutingID = $('#' + prfxNm + '' + rndmNum + '_RoutingID').val();
    var sbjct = $('#' + prfxNm + '' + rndmNum + '_MsgSubject').val();
    if (RoutingID <= 0)
    {
        var dialog = bootbox.alert({
            title: 'No Notification Selected',
            size: 'small',
            message: '<p style="text-align:center;"><i class="fa fa-exclamation-triangle" style="color:red;"></i> Please select a Notification First!</p>',
            callback: function () {
            }
        });
    } else
    {
        onAttachment(RoutingID, sbjct);
    }
}

function be4OnAct(RoutingID, actionNm, isResDiag)
{
    var actionReason = typeof $("#wkfActionMsg").val() === 'undefined' ? "" : $("#wkfActionMsg").val();
    var toPrsnLocID = typeof $("#wkfToPrsnLocID").val() === 'undefined' ? "" : $("#wkfToPrsnLocID").val();
    var wkfSlctdRoutingIDs = typeof $("#wkfSlctdRoutingIDs").val() === 'undefined' ? "" : $("#wkfSlctdRoutingIDs").val();

    var errMsg = "";
    if (toPrsnLocID.trim() === '' && (actionNm === "Request for Information" || actionNm === "Re-Assign" || actionNm === "Respond"))
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Message Recipient cannot be empty!</span></p>';
    }
    if (wkfSlctdRoutingIDs.trim() === '' && actionNm === "Re-Assign")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Selected Notifications cannot be empty!</span></p>';
    }
    if (actionReason.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Action Message/Reason cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    }
    onAct(RoutingID, actionNm, isResDiag, actionReason, toPrsnLocID, '', wkfSlctdRoutingIDs);
}

function afterShowActions(wkfAppNm) {
    if (wkfAppNm === "Banking Transactions") {
        afterShowBNK();
    } else if (wkfAppNm === "Vault Transactions") {
        afterShowVMS();
    } else if (wkfAppNm === "Personal Records Change") {

    } else if (wkfAppNm === "Bulk/Batch Transactions") {
        afterShowBulk();
    } else if (wkfAppNm === "Transfer Transactions") {
        afterShowTrsfr();
    }
}