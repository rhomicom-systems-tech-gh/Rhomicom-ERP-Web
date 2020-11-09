
function prepareGstAdmin(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();

        $(function () {
            $('[data-toggle="tabajxgst"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=4&typ=1' + dttrgt;
                return openATab(targ, linkArgs);
            });
        });
        if (lnkArgs.indexOf("&pg=1&vtyp=0") !== -1)
        {
            var table1 = $('#allLovStpsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allLovStpsTable').wrap('<div class="dataTables_scroll"/>');
            $('#allLovStpsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            $('#allLovStpsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var curLovID = typeof $('#allLovStpsRow' + rndmNum + '_LovID').val() === 'undefined' ? '%' : $('#allLovStpsRow' + rndmNum + '_LovID').val();
                getOneLovStpForm(curLovID, 1);
            });
            $('#allLovStpsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        } else if (lnkArgs.indexOf("&pg=1&vtyp=3") !== -1)
        {
            var table1 = $('#psblValsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#psblValsTable').wrap('<div class="dataTables_scroll"/>');
            $('#psblValsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
        htBody.removeClass("mdlloading");
    });
}

function getOneLovStpForm(lovID, vwtype)
{
    var lnkArgs = 'grp=4&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdLovID=' + lovID;
    doAjaxWthCallBck(lnkArgs, 'lovStpsDetailInfo', 'PasteDirect', '', '', '', function () {
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $(function () {
                $('[data-toggle="tabajxgst"]').click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr('href');
                    var dttrgt = $this.attr('data-rhodata');
                    var linkArgs = 'grp=4&typ=1' + dttrgt;
                    return openATab(targ, linkArgs);
                });
            });
        });
    });
}

function saveLovStpForm()
{
    var lnkArgs = "";
    var lovDetLovNm = typeof $("#lovDetLovNm").val() === 'undefined' ? '' : $("#lovDetLovNm").val();
    var lovDetLovID = typeof $("#lovDetLovID").val() === 'undefined' ? -1 : $("#lovDetLovID").val();
    var lovDetIsDynmc = typeof $("input[name='lovDetIsDynmc']:checked").val() === 'undefined' ? '' : $("input[name='lovDetIsDynmc']:checked").val();
    var lovDetIsEnabled = typeof $("input[name='lovDetIsEnabled']:checked").val() === 'undefined' ? '' : $("input[name='lovDetIsEnabled']:checked").val();
    var lovDetLovDesc = typeof $("#lovDetLovDesc").val() === 'undefined' ? '' : $("#lovDetLovDesc").val();
    var lovDetDfndBy = typeof $("#lovDetDfndBy").val() === 'undefined' ? '' : $("#lovDetDfndBy").val();
    var lovDetSqlQry = typeof $("#lovDetSqlQry").val() === 'undefined' ? '' : $("#lovDetSqlQry").val();
    var lovDetOrdrByCls = typeof $("#lovDetOrdrByCls").val() === 'undefined' ? 'ORDER BY 1 ASC' : $("#lovDetOrdrByCls").val();
    if (lovDetLovNm === "" || lovDetLovNm === null)
    {
        $('#modal-7 .modal-body').html('LOV Name cannot be empty!');
        $('#modal-7').modal('show', {backdrop: 'static'});
        return false;
    }
    if (lovDetSqlQry.trim() === "" && lovDetIsDynmc === "YES")
    {
        $('#modal-7 .modal-body').html('SQL Query cannot be empty!');
        $('#modal-7').modal('show', {backdrop: 'static'});
        return false;
    }
    if (lovDetOrdrByCls.trim() === "") {
        lovDetOrdrByCls = "ORDER BY 1 ASC";
    }
    lnkArgs = "grp=4&typ=1&q=UPDATE&actyp=1" +
            "&lovDetLovID=" + lovDetLovID +
            "&lovDetLovNm=" + lovDetLovNm +
            "&lovDetLovDesc=" + lovDetLovDesc +
            "&lovDetDfndBy=" + lovDetDfndBy +
            "&lovDetIsDynmc=" + lovDetIsDynmc +
            "&lovDetIsEnabled=" + lovDetIsEnabled +
            "&lovDetSqlQry=" + lovDetSqlQry +
            "&lovDetOrdrByCls=" + lovDetOrdrByCls;
    doAjax(lnkArgs, 'myFormsModal', 'ShowDialog', 'System Alert!', 'myFormsModalTitle', 'myFormsModalBody');
}

function getAllLovStps(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allLovStpsSrchFor").val() === 'undefined' ? '%' : $("#allLovStpsSrchFor").val();
    var srchIn = typeof $("#allLovStpsSrchIn").val() === 'undefined' ? 'Both' : $("#allLovStpsSrchIn").val();
    var pageNo = typeof $("#allLovStpsPageNo").val() === 'undefined' ? 1 : $("#allLovStpsPageNo").val();
    var limitSze = typeof $("#allLovStpsDsplySze").val() === 'undefined' ? 10 : $("#allLovStpsDsplySze").val();
    var sortBy = typeof $("#allLovStpsSortBy").val() === 'undefined' ? '' : $("#allLovStpsSortBy").val();
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

function enterKeyFuncLovStps(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllLovStps(actionText, slctr, linkArgs);
    }
}
function getAllPsblVals(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#psblValsSrchFor").val() === 'undefined' ? '%' : $("#psblValsSrchFor").val();
    var srchIn = typeof $("#psblValsSrchIn").val() === 'undefined' ? 'Both' : $("#psblValsSrchIn").val();
    var pageNo = typeof $("#psblValsPageNo").val() === 'undefined' ? 1 : $("#psblValsPageNo").val();
    var limitSze = typeof $("#psblValsDsplySze").val() === 'undefined' ? 10 : $("#psblValsDsplySze").val();
    var sortBy = typeof $("#psblValsSortBy").val() === 'undefined' ? '' : $("#psblValsSortBy").val();
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

function enterKeyFuncPsblVals(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllPsblVals(actionText, slctr, linkArgs);
    }
}

function savePsblValsForm()
{
    var lovDetLovID = typeof $("#lovDetLovID").val() === 'undefined' ? -1 : $("#lovDetLovID").val();
    var lnkArgs = "grp=4&typ=1&q=UPDATE&actyp=2&lovDetLovID=" + lovDetLovID;
    var slctdPsblVals = "";
    $('#psblValsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                /*var $tds1 = $(this).find('td');*/
                var isEnabled = typeof $("input[name='psblValsRow" + rndmNum + "_IsEnabled']:checked").val() === 'undefined' ? 'No' : 'Yes';
                slctdPsblVals = slctdPsblVals + $('#psblValsRow' + rndmNum + '_PValID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#psblValsRow' + rndmNum + '_PValNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#psblValsRow' + rndmNum + '_AlwdOrgs').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#psblValsRow' + rndmNum + '_PValDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + isEnabled.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
            }
        }
    });
    if (slctdPsblVals === "" || lovDetLovID <= 0)
    {
        $('#modal-7 .modal-body').html('LOV ID or Possible Values cannot be empty!');
        $('#modal-7').modal('show', {backdrop: 'static'});
        return false;
    }
    lnkArgs = lnkArgs + "&slctdPsblVals=" +encodeURIComponent(slctdPsblVals.slice(0, -1));
    doAjax(lnkArgs, 'myFormsModal', 'ShowDialog', 'System Alert!', 'myFormsModalTitle', 'myFormsModalBody');
}

function delPsblValue(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var psbVlNm = '';
    var msgsTitle = 'Possible Value';
    if (typeof $('#psblValsRow' + rndmNum + '_PValID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#psblValsRow' + rndmNum + '_PValID').val();
        athrzrNm = $('#psblValsRow' + rndmNum + '_PValNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Remove ' + msgsTitle + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this ' + msgsTitle + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Remove ' + msgsTitle + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing ' + msgsTitle + '...Please Wait...</p>'
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
                                    grp: 4,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 2,
                                    psbVlNm: psbVlNm,
                                    pKeyID: pKeyID
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

function delLOV(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var lovNm = '';
    var msgsTitle = 'LOV';
    if (typeof $('#psblValsRow' + rndmNum + '_LovID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#psblValsRow' + rndmNum + '_LovID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        lovNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Remove ' + msgsTitle + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this ' + msgsTitle + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Remove ' + msgsTitle + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing ' + msgsTitle + '...Please Wait...</p>'
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
                                    grp: 4,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 1,
                                    lovNm: lovNm,
                                    pKeyID: pKeyID
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

function delSlctdVMSIntrfcLines()
{
    var slctdIntrfcIDs = "";
    var slctdCnt = 0;
    $('#allVmsGLIntrfcsHdrsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnIntrfcID = typeof $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val();
                    var isTcked = (typeof $("input[name='" + rowPrfxNm + rndmNum + "_CheckBox']:checked").val() === 'undefined' ? 'NO' : 'YES');
                    if (Number(lnIntrfcID.replace(/[^-?0-9\.]/g, '')) > 0 && isTcked === "YES") {
                        slctdCnt = slctdCnt + 1;
                    }
                }
            }
        }
    });
    if (slctdCnt > 0) {
        var dialog = bootbox.confirm({
            title: 'Delete Selected User Transactions?',
            size: 'small',
            message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> the ' + slctdCnt + ' selected User Transactions(s)?<br/>Action cannot be Undone!</p>',
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
                $('#allGLIntrfcsHdrsTable').find('tr').each(function (i, el) {
                    if (i > 0)
                    {
                        if (typeof $(el).attr('id') === 'undefined')
                        {
                            /*Do Nothing*/
                        } else {
                            var rndmNum = $(el).attr('id').split("_")[1];
                            var rowPrfxNm = $(el).attr('id').split("_")[0];
                            if (typeof $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val() === 'undefined')
                            {
                                /*Do Nothing*/
                            } else {
                                var lnIntrfcID = typeof $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val();
                                var rowIDAttrb = $(el).attr('id');
                                var $tds = $('#' + rowIDAttrb).find('td');
                                var intrfcIDDesc = $.trim($tds.eq(4).text());
                                var isTcked = (typeof $("input[name='" + rowPrfxNm + rndmNum + "_CheckBox']:checked").val() === 'undefined' ? 'NO' : 'YES');
                                if (Number(lnIntrfcID.replace(/[^-?0-9\.]/g, '')) > 0 && isTcked === "YES") {
                                    slctdIntrfcIDs = slctdIntrfcIDs + lnIntrfcID + "~"
                                            + intrfcIDDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                }
                                $("#" + rowPrfxNm + rndmNum).remove();
                            }
                        }
                    }
                });
                var result2 = "";
                if (result === true)
                {
                    var dialog1 = bootbox.alert({
                        title: 'Delete Selected User Transactions?',
                        size: 'small',
                        message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Selected User Transactions...Please Wait...</p>',
                        callback: function () {
                            $("body").css("padding-right", "0px");
                            if (result2.indexOf("Success") !== -1) {
                                getAllGLIntrfcs('clear', '#allmodules', 'grp=17&typ=1&pg=8&vtyp=0&subPgNo=1.1');
                            }
                        }
                    });
                    dialog1.init(function () {
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 7,
                                    slctdIntrfcIDs: slctdIntrfcIDs
                                },
                                success: function (result1) {
                                    result2 = result1;
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
                    });
                }
            }
        });
    } else {
        var errMsg = '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please first select transactions to Void/Delete!</span></p>';
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
    }
}