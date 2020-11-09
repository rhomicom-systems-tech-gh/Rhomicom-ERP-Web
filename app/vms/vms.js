function prepareVMS(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $(function () {
            $('[data-toggle="tabajxcage"]').off('click');
            $('[data-toggle="tabajxcage"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=25&typ=1' + dttrgt;
                return openATab(targ, linkArgs);
            });
        });
        if (lnkArgs.indexOf("&pg=1&vtyp=3") !== -1)
        {
            /*var table3 = $('#allCagePositionTable').DataTable({
             "paging": false,
             "ordering": false,
             "info": false,
             "bFilter": false,
             "scrollX": false
             });
             $('#allCagePositionTable').wrap('<div class="dataTables_scroll"/>');*/
            var table1;
            if (!$.fn.DataTable.isDataTable('#allCageItemsTable')) {
                table1 = $('#allCageItemsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allCageItemsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allCageItemsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            var table2;
            if (!$.fn.DataTable.isDataTable('#allCageItemTrnsTable')) {
                 table2 = $('#allCageItemTrnsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allCageItemTrnsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allCageItemsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pKeyID = typeof $('#allCageItemsRow' + rndmNum + '_ItemID').val() === 'undefined' ? '%' : $('#allCageItemsRow' + rndmNum + '_ItemID').val();
                var cageID = typeof $('#allCageItemsRow' + rndmNum + '_CageID').val() === 'undefined' ? '%' : $('#allCageItemsRow' + rndmNum + '_CageID').val();
                getOneCageTrnsForm(pKeyID, cageID, 5);
            });
            $('#allCageItemsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        } else if (lnkArgs.indexOf("&pg=1&vtyp=5") !== -1)
        {
            $('#allCageItemTrnsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable('#allCageItemTrnsTable')) {
                var table2 = $('#allCageItemTrnsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allCageItemTrnsTable').wrap('<div class="dataTables_scroll"/>');
            }
        } else if (lnkArgs.indexOf("&pg=2&vtyp=") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#allVmsTrnsHdrsTable')) {
                var table1 = $('#allVmsTrnsHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allVmsTrnsHdrsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allVmsTrnsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=3&vtyp=0") !== -1)
        {
        } else if (lnkArgs.indexOf("&pg=4&vtyp=1") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#allVmsLocsTable')) {
                var table1 = $('#allVmsLocsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allVmsLocsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allVmsLocsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=4&vtyp=2") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#allVmsVltsTable')) {
                var table1 = $('#allVmsVltsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allVmsVltsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allVmsVltsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=4&vtyp=3") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#allVmsCgsTable')) {
                var table1 = $('#allVmsCgsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allVmsCgsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allVmsCgsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=4&vtyp=4") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#allVmsItmsTable')) {
                var table1 = $('#allVmsItmsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allVmsItmsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allVmsItmsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=4&vtyp=5") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#vmsAthrzrsTable')) {
                var table1 = $('#vmsAthrzrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#vmsAthrzrsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#vmsAthrzrsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=4&vtyp=6") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#allCstmrsTable')) {
                var table1 = $('#allCstmrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allCstmrsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allCstmrsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=4&vtyp=7") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#allVmsGLIntrfcsHdrsTable')) {
                var table1 = $('#allVmsGLIntrfcsHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allVmsGLIntrfcsHdrsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allVmsGLIntrfcsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
        $(".vmsCbQty").ForceNumericOnly();
        $(".vmsCbTtl").ForceNumericOnly();
        $(".vmsFncCrncy").ForceNumericOnly();
        $(".vmsCbBndl").ForceNumericOnly();
        $(".vmsCbTray").ForceNumericOnly();
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
        htBody.removeClass("mdlloading");
    });
}

function getOneCageTrnsForm(pKeyID, cageID, vwtype)
{
    var qStrtDte = typeof $("#allCageItemTrnsStrtDate").val() === 'undefined' ? '' : $("#allCageItemTrnsStrtDate").val();
    var qEndDte = typeof $("#allCageItemTrnsEndDate").val() === 'undefined' ? '' : $("#allCageItemTrnsEndDate").val();
    var lnkArgs = 'grp=25&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdItemID=' + pKeyID + '&sbmtdCageID=' + cageID +
            "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte;
    doAjaxWthCallBck(lnkArgs, 'allCageItemsBnCrdDiv', 'PasteDirect', '', '', '', function () {
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            if (!$.fn.DataTable.isDataTable('#allCageItemTrnsTable')) {
                var table2 = $('#allCageItemTrnsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allCageItemTrnsTable').wrap('<div class="dataTables_scroll"/>');
            }

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
    });
}

function getAllVmsAthrzrs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allVmsAthrzrsSrchFor").val() === 'undefined' ? '%' : $("#allVmsAthrzrsSrchFor").val();
    var srchIn = typeof $("#allVmsAthrzrsSrchIn").val() === 'undefined' ? 'Both' : $("#allVmsAthrzrsSrchIn").val();
    var pageNo = typeof $("#allVmsAthrzrsPageNo").val() === 'undefined' ? 1 : $("#allVmsAthrzrsPageNo").val();
    var limitSze = typeof $("#allVmsAthrzrsDsplySze").val() === 'undefined' ? 10 : $("#allVmsAthrzrsDsplySze").val();
    var sortBy = typeof $("#allVmsAthrzrsSortBy").val() === 'undefined' ? '' : $("#allVmsAthrzrsSortBy").val();
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

function enterKeyFuncAllVmsAthrzrs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllVmsAthrzrs(actionText, slctr, linkArgs);
    }
}

function delVmsAthrzr(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var athrzrNm = '';
    var msgsTitle = 'VMS Authorizer';
    if (typeof $('#vmsAthrzrsRow' + rndmNum + '_LimitID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#vmsAthrzrsRow' + rndmNum + '_LimitID').val();
        athrzrNm = $('#vmsAthrzrsRow' + rndmNum + '_AthrzrNm').val();
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 5,
                                    athrzrNm: athrzrNm,
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

function saveVmsAthrzr(slctr, linkArgs)
{
    var msgsTitle = 'VMS Authorizer';
    var slctdVmsAthrzrs = "";
    var isVld = true;
    var errMsg = "";
    $('#vmsAthrzrsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var isInUse = typeof $('#vmsAthrzrsRow' + rndmNum + '_InUse').val() === 'undefined' ? '-1' : $('#vmsAthrzrsRow' + rndmNum + '_InUse').val();
                if (Number(isInUse.replace(/[^-?0-9\.]/g, '')) <= 0) {
                    var athrzrLocID = $('#vmsAthrzrsRow' + rndmNum + '_LocID').val();
                    var pKeyID = $('#vmsAthrzrsRow' + rndmNum + '_LimitID').val();
                    if (athrzrLocID.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Authorizer for Column No. ' + i + ' cannot be empty!</span></p>';
                        $('#vmsAthrzrsRow' + rndmNum + '_LocID').addClass('rho-error');
                    } else {
                        $('#vmsAthrzrsRow' + rndmNum + '_LocID').removeClass('rho-error');
                    }
                    /*var siteID = $('#vmsAthrzrsRow' + rndmNum + '_SiteID').val();
                     if (siteID <= 0)
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Branch/Location Column No. ' + i + ' cannot be empty!</span></p>';
                     $('#vmsAthrzrsRow' + rndmNum + '_SiteNm').addClass('rho-error');
                     } else {
                     $('#vmsAthrzrsRow' + rndmNum + '_SiteNm').removeClass('rho-error');
                     }
                     var trnsTyp = $('#vmsAthrzrsRow' + rndmNum + '_TrnsTyp').val();
                     if (trnsTyp.trim() === '')
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Transaction Type for Column No. ' + i + ' cannot be empty!</span></p>';
                     $('#vmsAthrzrsRow' + rndmNum + '_TrnsTyp').addClass('rho-error');
                     } else {
                     $('#vmsAthrzrsRow' + rndmNum + '_TrnsTyp').removeClass('rho-error');
                     }
                     var crncyID = $('#vmsAthrzrsRow' + rndmNum + '_CrncyID').val();
                     if (crncyID <= 0)
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Currency for Column No. ' + i + ' cannot be empty!</span></p>';
                     $('#vmsAthrzrsRow' + rndmNum + '_CrncyNm').addClass('rho-error');
                     } else {
                     $('#vmsAthrzrsRow' + rndmNum + '_CrncyNm').removeClass('rho-error');
                     }*/
                    var minAmnt = $('#vmsAthrzrsRow' + rndmNum + '_MinAmnt').val();
                    if (minAmnt === '')
                    {
                        minAmnt = 0;
                        $('#vmsAthrzrsRow' + rndmNum + '_MinAmnt').val(minAmnt);
                    }
                    var maxAmnt = $('#vmsAthrzrsRow' + rndmNum + '_MaxAmnt').val();
                    if (maxAmnt === '')
                    {
                        maxAmnt = 0;
                        $('#vmsAthrzrsRow' + rndmNum + '_MaxAmnt').val(maxAmnt);
                    }
                    if ((maxAmnt === 0 && minAmnt === 0) || maxAmnt < minAmnt)
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Ensure Both Amounts for Column No. ' + i + ' are not Zero and that the Max isn\'t less than the Min.!</span></p>';
                        $('#vmsAthrzrsRow' + rndmNum + '_MaxAmnt').addClass('rho-error');
                        $('#vmsAthrzrsRow' + rndmNum + '_MinAmnt').addClass('rho-error');
                    } else {
                        $('#vmsAthrzrsRow' + rndmNum + '_MinAmnt').removeClass('rho-error');
                        $('#vmsAthrzrsRow' + rndmNum + '_MaxAmnt').removeClass('rho-error');
                    }
                    if (isVld === false)
                    {
                        /*Do Nothing*/
                    } else {
                        var isEnabled = typeof $("input[name='vmsAthrzrsRow" + rndmNum + "_IsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
                        slctdVmsAthrzrs = slctdVmsAthrzrs + $('#vmsAthrzrsRow' + rndmNum + '_LimitID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#vmsAthrzrsRow' + rndmNum + '_LocID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#vmsAthrzrsRow' + rndmNum + '_SiteID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#vmsAthrzrsRow' + rndmNum + '_TrnsTyp').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#vmsAthrzrsRow' + rndmNum + '_CrncyNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#vmsAthrzrsRow' + rndmNum + '_MinAmnt').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#vmsAthrzrsRow' + rndmNum + '_MaxAmnt').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + isEnabled.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save ' + msgsTitle,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msgsTitle + '...Please Wait...</p>',
        callback: function () {
            openATab(slctr, linkArgs);
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
                    grp: 25,
                    typ: 1,
                    pg: 4,
                    q: 'UPDATE',
                    actyp: 5,
                    slctdVmsAthrzrs: slctdVmsAthrzrs
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function getAllVmsTrns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allVmsTrnsSrchFor").val() === 'undefined' ? '%' : $("#allVmsTrnsSrchFor").val();
    var srchIn = typeof $("#allVmsTrnsSrchIn").val() === 'undefined' ? 'Both' : $("#allVmsTrnsSrchIn").val();
    var pageNo = typeof $("#allVmsTrnsPageNo").val() === 'undefined' ? 1 : $("#allVmsTrnsPageNo").val();
    var limitSze = typeof $("#allVmsTrnsDsplySze").val() === 'undefined' ? 10 : $("#allVmsTrnsDsplySze").val();
    var sortBy = typeof $("#allVmsTrnsSortBy").val() === 'undefined' ? '' : $("#allVmsTrnsSortBy").val();
    var qStrtDte = typeof $("#allVmsTrnsStrtDate").val() === 'undefined' ? '' : $("#allVmsTrnsStrtDate").val();
    var qEndDte = typeof $("#allVmsTrnsEndDate").val() === 'undefined' ? '' : $("#allVmsTrnsEndDate").val();
    var qUnathrzdOnly = $('#allVmsTrnsShwUnaprvd:checked').length > 0;
    var qInvalidOnly = $('#allVmsTrnsShwInvld:checked').length > 0;
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
            + "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte
            + "&qUnathrzdOnly=" + qUnathrzdOnly + "&qInvalidOnly=" + qInvalidOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllVmsTrns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllVmsTrns(actionText, slctr, linkArgs);
    }
}


function getAllCageTrns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allVmsTrnsSrchFor").val() === 'undefined' ? '%' : $("#allVmsTrnsSrchFor").val();
    var srchIn = typeof $("#allVmsTrnsSrchIn").val() === 'undefined' ? 'Both' : $("#allVmsTrnsSrchIn").val();
    var pageNo = typeof $("#allVmsTrnsPageNo").val() === 'undefined' ? 1 : $("#allVmsTrnsPageNo").val();
    var limitSze = typeof $("#allVmsTrnsDsplySze").val() === 'undefined' ? 10 : $("#allVmsTrnsDsplySze").val();
    var sortBy = typeof $("#allVmsTrnsSortBy").val() === 'undefined' ? '' : $("#allVmsTrnsSortBy").val();
    var qStrtDte = typeof $("#allVmsTrnsStrtDate").val() === 'undefined' ? '' : $("#allVmsTrnsStrtDate").val();
    var qEndDte = typeof $("#allVmsTrnsEndDate").val() === 'undefined' ? '' : $("#allVmsTrnsEndDate").val();
    var qUnathrzdOnly = $('#allVmsTrnsShwUnaprvd:checked').length > 0;
    var qInvalidOnly = $('#allVmsTrnsShwInvld:checked').length > 0;
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
            + "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte
            + "&qUnathrzdOnly=" + qUnathrzdOnly + "&qInvalidOnly=" + qInvalidOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllCageTrns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllCageTrns(actionText, slctr, linkArgs);
    }
}

function getOneVmsTrnsForm(pKeyID, trnsType, vwtype, actionTxt, tblrVwTyp, rowIDAttrb, srcMenu,
        isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    if (typeof tblrVwTyp === 'undefined' || tblrVwTyp === null)
    {
        tblrVwTyp = 13;
    }
    if (typeof rowIDAttrb === 'undefined' || rowIDAttrb === null)
    {
        rowIDAttrb = '';
    }
    if (typeof sbmtdShelfID === 'undefined' || sbmtdShelfID === null) {
        sbmtdShelfID = -1;
    }
    if (trnsType.trim() === '') {
        var rndmNum = rowIDAttrb.split("_")[1];
        var rowNamePrfx = rowIDAttrb.split("_")[0];
        trnsType = typeof $('#' + rowNamePrfx + rndmNum + '_TrnsType').val() === 'undefined' ? '' : $('#' + rowNamePrfx + rndmNum + '_TrnsType').val();
    }
    var lnkArgs = 'grp=25&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdVmsTrnsHdrID=' + pKeyID + '&trnsType=' + trnsType + '&tblrVwTyp=' + tblrVwTyp;
    if (sbmtdShelfID > 0) {
        lnkArgs = lnkArgs + '&isFrmBnkng=' + isFrmBnkng + '&sbmtdShelfID=' + sbmtdShelfID + '&sbmtdSiteID=' + sbmtdSiteID + '&sbmtdStoreID=' + sbmtdStoreID + '';
    }
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'VMS Trns. Details (ID:' + pKeyID + ' Type:' + trnsType + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        $('#allOtherInputData99').val('0');
        if (sbmtdShelfID <= 0) {
            $('#oneVmsTrnsEDTForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('#myFormsModalLg').off('hidden.bs.modal');
            $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
                getAllVmsTrns('', '#allmodules', 'grp=25&typ=1&pg=2&vtyp=' + tblrVwTyp + '&srcMenu=' + srcMenu);
                $(e.currentTarget).unbind();
            });
        } else {
            createRnngBal1Flds();
            $('#oneDirectVmsTrnsFrm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('#myFormsModalLg').off('hidden.bs.modal');
            $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
                var $this = $("#cageTrnstab");
                $this.tab('show');
                getAllCageTrns('', '#cageTrns', 'grp=25&typ=1&pg=2&vtyp=38&srcMenu=' + srcMenu + '&isFrmBnkng=' + isFrmBnkng + '&sbmtdShelfID=' + sbmtdShelfID + '&sbmtdSiteID=' + sbmtdSiteID + '&sbmtdStoreID=' + sbmtdStoreID + '');
                $(e.currentTarget).unbind();
            });
        }
        if (!$.fn.DataTable.isDataTable('#oneVmsExpnsTrnsLnsTable')) {
            var table1 = $('#oneVmsExpnsTrnsLnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneVmsExpnsTrnsLnsTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#oneVmsTrnsLnsTable')) {
            var table1 = $('#oneVmsTrnsLnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneVmsTrnsLnsTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#oneVmsTrnsCoinsLnsTable')) {
            var table1 = $('#oneVmsTrnsCoinsLnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneVmsTrnsCoinsLnsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $(".vmsCbTtl").focus(function () {
            $(this).select();
        });
        $(".vmsCbQty").focus(function () {
            $(this).select();
        });
        $(".vmsFncCrncy").focus(function () {
            $(this).select();
        });
        $(".vmsTtlAmt").focus(function () {
            $(this).select();
        });
        $(".vmsCbBndl").focus(function () {
            $(this).select();
        });
        $(".vmsCbTray").focus(function () {
            $(this).select();
        });
        $('[data-toggle="tabajxdenoms"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            $(targ + 'tab').tab('show');
            if (targ.indexOf('Coin') >= 0) {
                $('#addNwNoteBtn').addClass('hideNotice');
                $('#btnGrpForNotes').addClass('hideNotice');
                $('#cashItmsNotes').addClass('hideNotice');
                $('#cashItmsExpns').addClass('hideNotice');
                $('#addNwExpnsBtn').addClass('hideNotice');
                $('#addNwCoinBtn').removeClass('hideNotice');
                $('#btnGrpForCoins').removeClass('hideNotice');
                $('#cashItmsCoins').removeClass('hideNotice');
            } else if (targ.indexOf('Note') >= 0) {
                $('#addNwNoteBtn').removeClass('hideNotice');
                $('#btnGrpForNotes').removeClass('hideNotice');
                $('#cashItmsNotes').removeClass('hideNotice');
                $('#cashItmsExpns').addClass('hideNotice');
                $('#addNwCoinBtn').addClass('hideNotice');
                $('#btnGrpForCoins').addClass('hideNotice');
                $('#cashItmsCoins').addClass('hideNotice');
                $('#addNwExpnsBtn').addClass('hideNotice');
            } else {
                $('#cashItmsExpns').removeClass('hideNotice');
                $('#addNwExpnsBtn').removeClass('hideNotice');
                $('#addNwCoinBtn').addClass('hideNotice');
                $('#btnGrpForCoins').addClass('hideNotice');
                $('#cashItmsCoins').addClass('hideNotice');
                $('#addNwNoteBtn').addClass('hideNotice');
                $('#btnGrpForNotes').addClass('hideNotice');
                $('#cashItmsNotes').addClass('hideNotice');
            }
        });
        $('#oneVmsTrnsLnsTable tr').off('click');
        $('#oneVmsTrnsLnsTable tr').click(function () {
            var rowIndex = $('#oneVmsTrnsLnsTable tr').index(this);
            $('#allOtherInputData99').val(rowIndex);
        });
        $('#oneVmsTrnsCoinsLnsTable tr').off('click');
        $('#oneVmsTrnsCoinsLnsTable tr').click(function () {
            var rowIndex = $('#oneVmsTrnsCoinsLnsTable tr').index(this);
            $('#allOtherInputData99').val(rowIndex);
        });
        calcAllRowsTtlVals();

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
}

function vmsTrnsFormKeyPress(event, rowIDAttrb)
{
    //alert(event.which);
    if (event.which === 13) {
        var nextItem;
        var nextItemVal = 0;
        var rowPrfxNm = rowIDAttrb.split("_")[0];
        var sbmtdTblRowID = 'oneVmsTrnsLnsTable';
        if (rowPrfxNm.indexOf('Coins') >= 0) {
            sbmtdTblRowID = 'oneVmsTrnsCoinsLnsTable';
        }
        var curItemVal = getRowIndx(rowIDAttrb, sbmtdTblRowID);
        if (curItemVal === getTtlRows(sbmtdTblRowID)) {
            nextItem = $('#' + sbmtdTblRowID + ' .vmsCbQty').eq(0);
        } else {
            nextItemVal = Number(curItemVal);
            nextItem = $('#' + sbmtdTblRowID + ' .vmsCbQty').eq(nextItemVal);
        }
        nextItem.focus();
    }
}

function vmsCbBndlKeyPress(event, rowIDAttrb)
{
    if (event.which === 13) {
        var nextItem;
        var nextItemVal = 0;
        var curItemVal = getRowIndx(rowIDAttrb, 'oneVmsTrnsLnsTable');
        if (curItemVal === getTtlRows('oneVmsTrnsLnsTable')) {
            nextItem = $('.vmsCbBndl').eq(0);
        } else {
            nextItemVal = Number(curItemVal);
            nextItem = $('.vmsCbBndl').eq(nextItemVal);
        }
        nextItem.focus();
    }
}

function vmsCbTrayKeyPress(event, rowIDAttrb)
{
    if (event.which === 13) {
        var nextItem;
        var nextItemVal = 0;
        var rowPrfxNm = rowIDAttrb.split("_")[0];
        var sbmtdTblRowID = 'oneVmsTrnsLnsTable';
        if (rowPrfxNm.indexOf('Coins') >= 0) {
            sbmtdTblRowID = 'oneVmsTrnsCoinsLnsTable';
        }
        var curItemVal = getRowIndx(rowIDAttrb, sbmtdTblRowID);
        if (curItemVal === getTtlRows(sbmtdTblRowID)) {
            nextItem = $('#' + sbmtdTblRowID + ' .vmsCbTray').eq(0);
        } else {
            nextItemVal = Number(curItemVal);
            nextItem = $('#' + sbmtdTblRowID + ' .vmsCbTray').eq(nextItemVal);
        }
        nextItem.focus();
    }
}

function vmsTrnsFormTtlFldKeyPress(event, rowIDAttrb)
{
    //alert(event.which);
    if (event.which === 13) {
        var nextItem;
        var nextItemVal = 0;
        var rowPrfxNm = rowIDAttrb.split("_")[0];
        var sbmtdTblRowID = 'oneVmsTrnsLnsTable';
        if (rowPrfxNm.indexOf('Coins') >= 0) {
            sbmtdTblRowID = 'oneVmsTrnsCoinsLnsTable';
        }
        var curItemVal = getRowIndx(rowIDAttrb, sbmtdTblRowID);
        if (curItemVal === getTtlRows(sbmtdTblRowID)) {
            nextItem = $('#' + sbmtdTblRowID + ' .vmsCbTtl').eq(0);
        } else {
            nextItemVal = Number(curItemVal);
            nextItem = $('#' + sbmtdTblRowID + ' .vmsCbTtl').eq(nextItemVal);
        }
        nextItem.focus();
    }
}

function vmsTrnsFormFnCrncyKeyPress(event, rowIDAttrb)
{
    //alert(event.which);
    if (event.which === 13) {
        var nextItem;
        var nextItemVal = 0;
        var rowPrfxNm = rowIDAttrb.split("_")[0];
        var sbmtdTblRowID = 'oneVmsTrnsLnsTable';
        if (rowPrfxNm.indexOf('Coins') >= 0) {
            sbmtdTblRowID = 'oneVmsTrnsCoinsLnsTable';
        }
        var curItemVal = getRowIndx(rowIDAttrb, sbmtdTblRowID);
        if (curItemVal === getTtlRows(sbmtdTblRowID)) {
            nextItem = $('#' + sbmtdTblRowID + ' .vmsFncCrncy').eq(0);
        } else {
            nextItemVal = Number(curItemVal);
            nextItem = $('#' + sbmtdTblRowID + ' .vmsFncCrncy').eq(nextItemVal);
        }
        nextItem.focus();
    }

}
function vmsPymtFormKeyPress(event, rowIDAttrb)
{
    //alert(event.which);
    if (event.which === 13) {
        var nextItem;
        var nextItemVal = 0;
        var curItemVal = getRowIndx(rowIDAttrb, 'oneVmsPymtsLnsTable');
        if (curItemVal === getTtlRows('oneVmsPymtsLnsTable')) {
            nextItem = $('.vmsPymtQty').eq(0);
        } else {
            nextItemVal = Number(curItemVal);
            nextItem = $('.vmsPymtQty').eq(nextItemVal);
        }
        nextItem.focus();
    }
}

function vmsPymtFormTtlFldKeyPress(event, rowIDAttrb)
{
    //alert(event.which);
    if (event.which === 13) {
        var nextItem;
        var nextItemVal = 0;
        var curItemVal = getRowIndx(rowIDAttrb, 'oneVmsPymtsLnsTable');
        if (curItemVal === getTtlRows('oneVmsPymtsLnsTable')) {
            nextItem = $('.vmsPymtTtl').eq(0);
        } else {
            nextItemVal = Number(curItemVal);
            nextItem = $('.vmsPymtTtl').eq(nextItemVal);
        }
        nextItem.focus();
    }
}

function getGnrlUOMBrkdwnForm(pKeyID, vwtype, sbmtdItemID, varTtlQty, sbmtdCrncyNm)
{
    var lnkArgs = 'grp=25&typ=1&pg=2&vtyp=' + vwtype + '&pKeyID=' + pKeyID +
            "&sbmtdItemID=" + sbmtdItemID + "&varTtlQty=" + varTtlQty + "&sbmtdCrncyNm=" + sbmtdCrncyNm;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', 'VMS Trns. QTY UOM Breakdown', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        if (!$.fn.DataTable.isDataTable('#oneVmsQtyBrkDwnTable')) {
            var table1 = $('#oneVmsQtyBrkDwnTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneVmsQtyBrkDwnTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#oneVmsTrnsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(".vmsUmbTtl").focus(function () {
            $(this).select();
        });
        $(".vmsUmbQty").focus(function () {
            $(this).select();
        });
    });
}

function saveVmsTrnsForm(funcCrncyNm, shdSbmt, tblrVwTyp, srcMenu,
        isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID)
{
    if (typeof sbmtdShelfID === 'undefined' || sbmtdShelfID === null) {
        sbmtdShelfID = -1;
    }
    var vmsTrnsHdrID = typeof $("#vmsTrnsHdrID").val() === 'undefined' ? -1 : $("#vmsTrnsHdrID").val();
    var vmsTrnsNum = typeof $("#vmsTrnsNum").val() === 'undefined' ? '' : $("#vmsTrnsNum").val();
    var vmsTrnsType = typeof $("#vmsTrnsType").val() === 'undefined' ? '' : $("#vmsTrnsType").val();
    var vmsTrnsDate = typeof $("#vmsTrnsDate").val() === 'undefined' ? '' : $("#vmsTrnsDate").val();
    var vmsTrnsClsfctn = typeof $("#vmsTrnsClsfctn").val() === 'undefined' ? '' : $("#vmsTrnsClsfctn").val();
    var vmsTrnsDesc = typeof $("#vmsTrnsDesc").val() === 'undefined' ? '' : $("#vmsTrnsDesc").val();
    var vmsTrnsPrsn = typeof $("#vmsTrnsPrsn").val() === 'undefined' ? '' : $("#vmsTrnsPrsn").val();
    var vmsOffctStaffLocID = typeof $("#vmsOffctStaffLocID").val() === 'undefined' ? '' : $("#vmsOffctStaffLocID").val();
    var vmsTrnsCrncyNm = typeof $("#vmsTrnsCrncyNm").val() === 'undefined' ? '' : $("#vmsTrnsCrncyNm").val();
    var vmsPymtCrncyNm = typeof $("#vmsPymtCrncyNm").val() === 'undefined' ? '' : $("#vmsPymtCrncyNm").val();
    var myPymntValsTtlVal = typeof $("#myPymntValsTtlVal").val() === 'undefined' ? '' : $("#myPymntValsTtlVal").val();
    var ttlVMSDocAmntVal = typeof $("#ttlVMSDocAmntVal").val() === 'undefined' ? 0 : $("#ttlVMSDocAmntVal").val();
    var myCptrdValsTtlVal = typeof $("#myCptrdValsTtlVal").val() === 'undefined' ? 0 : $("#myCptrdValsTtlVal").val();
    var myCptrdExpnsTtlVal = typeof $("#myCptrdExpnsTtlVal").val() === 'undefined' ? 0 : $("#myCptrdExpnsTtlVal").val();
    var vmsDfltSrcVltID = typeof $("#vmsDfltSrcVltID").val() === 'undefined' ? -1 : $("#vmsDfltSrcVltID").val();
    var vmsDfltSrcCageID = typeof $("#vmsDfltSrcCageID").val() === 'undefined' ? -1 : $("#vmsDfltSrcCageID").val();
    var vmsDfltDestVltID = typeof $("#vmsDfltDestVltID").val() === 'undefined' ? -1 : $("#vmsDfltDestVltID").val();
    var vmsDfltDestCageID = typeof $("#vmsDfltDestCageID").val() === 'undefined' ? -1 : $("#vmsDfltDestCageID").val();
    var vmsBrnchLocID = typeof $("#vmsBrnchLocID").val() === 'undefined' ? -1 : $("#vmsBrnchLocID").val();
    var vmsCstmrID = typeof $("#vmsCstmrID").val() === 'undefined' ? '-1' : $("#vmsCstmrID").val();
    var vmsCstmrSiteID = typeof $("#vmsCstmrSiteID").val() === 'undefined' ? -1 : $("#vmsCstmrSiteID").val();
    var vmsChequeNo = typeof $("#vmsChequeNo").val() === 'undefined' ? '' : $("#vmsChequeNo").val();
    var errMsg = "";
    if (vmsTrnsHdrID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Trns ID. cannot be empty!</span></p>';
    }
    if (vmsTrnsDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Remark/Narration cannot be empty!</span></p>';
    }
    if (vmsTrnsCrncyNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Currency cannot be empty!</span></p>';
    }
    ttlVMSDocAmntVal = fmtAsNumber('ttlVMSDocAmntVal').toFixed(2);
    myCptrdValsTtlVal = fmtAsNumber('myCptrdValsTtlVal').toFixed(2);
    myCptrdExpnsTtlVal = fmtAsNumber('myCptrdExpnsTtlVal').toFixed(2);
    if (myCptrdValsTtlVal != ttlVMSDocAmntVal)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    if (vmsTrnsType.trim() === "Deposits")
    {
        if (vmsChequeNo.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Deposit Slip Number cannot be Empty!</span></p>';
        }
    } else if (vmsTrnsType.trim() === "Withdrawals")
    {
        if (vmsChequeNo.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Cheque Number cannot be Empty!</span></p>';
        }
    }
    if (vmsTrnsType.trim() === "Currency Sale")
    {
        if (Number(vmsCstmrID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Client cannot be Empty for this Trns Type!</span></p>';
        }
        if (Number(vmsDfltSrcCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Source Cage cannot be Empty for this Trns Type!</span></p>';
        }
        if (isNumber(myPymntValsTtlVal) === false)
        {
            myPymntValsTtlVal = fmtAsNumber('myPymntValsTtlVal').toFixed(2);
        }
        if (myPymntValsTtlVal == 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Please provide a valid Non-Zero Figure for the Payment Amount!</span></p>';
        }
        if (vmsTrnsCrncyNm === vmsPymtCrncyNm) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Sold Currency and Payment Currency cannot be the same!</span></p>';
        }
        if (vmsTrnsCrncyNm === funcCrncyNm) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Sold Currency cannot be your Functional Currency for this Trns. Type!</span></p>';
        }
    } else if (vmsTrnsType.trim() === "Currency Purchase" || vmsTrnsType.trim() === "Currency Importation")
    {
        if (Number(vmsCstmrID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Vendor cannot be Empty for this Trns Type!</span></p>';
        }
        if (Number(vmsDfltDestCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Destination Cage cannot be Empty for this Trns Type!</span></p>';
        }
        if (isNumber(myPymntValsTtlVal) === false)
        {
            myPymntValsTtlVal = fmtAsNumber('myPymntValsTtlVal').toFixed(2);
        }
        if (myPymntValsTtlVal == 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Please provide a valid Non-Zero Figure for the Payment Amount!</span></p>';
        }
        if (vmsTrnsCrncyNm === vmsPymtCrncyNm) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Sold Currency and Payment Currency cannot be the same!</span></p>';
        }
        if (vmsTrnsCrncyNm === funcCrncyNm && vmsTrnsType.trim() !== "Currency Importation") {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Sold Currency cannot be your Functional Currency for this Trns. Type!</span></p>';
        }
    } else if (vmsTrnsType.trim() === "Deposits" || vmsTrnsType.trim() === "GL/Vault Account Transfers")
    {
        if (Number(vmsCstmrID) <= 0 && vmsTrnsType.trim() === "Deposits")
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Client/Vendor cannot be Empty for this Trns Type!</span></p>';
        }
        if (Number(vmsDfltDestCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Destination Cage cannot be Empty for this Trns Type!</span></p>';
        }
    } else if (vmsTrnsType.trim() === "Withdrawals")
    {
        if (Number(vmsCstmrID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Client cannot be Empty for this Trns Type!</span></p>';
        }
        if (Number(vmsDfltSrcCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Source Cage cannot be Empty for this Trns Type!</span></p>';
        }
    } else if (vmsTrnsType.trim() === "Currency Destruction" || vmsTrnsType.trim() === "Vault/GL Account Transfers")
    {
        if (Number(vmsDfltSrcCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Source Cage cannot be Empty for this Trns Type!</span></p>';
        }
        if (myCptrdExpnsTtlVal != ttlVMSDocAmntVal)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Transaction Header Total Amount must agree with GL Transaction Lines Total!</span></p>';
        }
    } else if (vmsTrnsType.trim() === "Miscellaneous Adjustments")
    {
        if (Number(vmsDfltSrcCageID) <= 0 && Number(vmsDfltDestCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Source and Destination Cages cannot be both Empty for this Trns Type!</span></p>';
        }
    } else {
        if (Number(vmsDfltSrcCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Source Vault/Cage cannot be empty!</span></p>';
        }
        if (Number(vmsDfltDestCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Destination Vault/Cage cannot be empty!</span></p>';
        }
    }
    var slctdVMSTrnsLines = "";
    var slctdExpnsTrnsLines = "";
    var isVld = true;
    $('#oneVmsExpnsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnTrnsID = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
                    var lnAccountID = typeof $('#' + rowPrfxNm + rndmNum + '_AccountID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_AccountID').val();
                    var lnEntrdCrncy = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm').val();
                    var lnEntrdRate = typeof $('#' + rowPrfxNm + rndmNum + '_ExchgRate').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_ExchgRate').val();
                    var lnLineDesc = typeof $('#' + rowPrfxNm + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_LineDesc').val();
                    var lnTtlAmnt = typeof $('#' + rowPrfxNm + rndmNum + '_TtlVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_TtlVal').val();
                    if (lnLineDesc.trim() !== "") {
                        if (Number(lnAccountID.replace(/[^-?0-9\.]/g, '')) <= 0) {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_AccountID').addClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_AccountNm').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_AccountID').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_AccountNm').removeClass('rho-error');
                        }
                        if (Number(lnTtlAmnt.replace(/[^-?0-9\.]/g, '')) <= 0)
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').removeClass('rho-error');
                        }
                        if (Number(lnEntrdRate.replace(/[^-?0-9\.]/g, '')) <= 0)
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_ExchgRate').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_ExchgRate').removeClass('rho-error');
                        }
                        if (lnLineDesc.trim() === "") {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_LineDesc').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_LineDesc').removeClass('rho-error');
                        }
                        if (lnEntrdCrncy.trim() === "") {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm1').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm1').removeClass('rho-error');
                        }
                        if (isVld === true)
                        {
                            slctdExpnsTrnsLines = slctdExpnsTrnsLines + lnTrnsID.replace(/[^-?0-9\.]/g, '') + "~"
                                    + lnAccountID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnEntrdCrncy.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnEntrdRate.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnTtlAmnt.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnLineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnItemID = typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
                    var lnQty = typeof $('#' + rowPrfxNm + rndmNum + '_Qty').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_Qty').val();
                    lnQty = lnQty.replace(/[^-?0-9\.]/g, '');
                    if (lnQty.indexOf('.') !== -1) {
                        lnQty = lnQty.substring(0, lnQty.indexOf('.'));
                    }
                    if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0 || lnItemID <= 0)
                    {
                        /*Do Nothing*/
                    } else if (lnItemID > 0) {
                        if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0
                                || Math.round((Number(lnQty.replace(/[^-?0-9\.]/g, '')) - Math.floor(Number(lnQty.replace(/[^-?0-9\.]/g, '')))), 2) > 0
                                ) {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Qty').addClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Qty').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').removeClass('rho-error');
                        }
                        var lnUntVal = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
                        if (Number(lnUntVal.replace(/[^-?0-9\.]/g, '')) <= 0)
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_UntVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_UntVal').removeClass('rho-error');
                        }
                        var lnFncCrncy = typeof $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val();
                        if (Number(lnFncCrncy.replace(/[^-?0-9\.]/g, '')) <= 0 && (vmsTrnsType.trim() === "Currency Sale" || vmsTrnsType.trim() === "Currency Purchase"))
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').removeClass('rho-error');
                        }
                        var lnSrcCgID = typeof $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val();
                        var lnDstCgID = typeof $('#' + rowPrfxNm + rndmNum + '_DstCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_DstCageID').val();
                        if ((vmsTrnsType.trim() === "Deposits" || vmsTrnsType.trim() === "Currency Purchase") && Number(vmsDfltDestCageID) > 0) {
                            $('#' + rowPrfxNm + rndmNum + '_DstCageID').val(vmsDfltDestCageID);
                        } else if ((vmsTrnsType.trim() === "Withdrawals" || vmsTrnsType.trim() === "Currency Sale") && Number(vmsDfltSrcCageID) > 0) {
                            $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val(vmsDfltSrcCageID);
                        }
                        lnSrcCgID = typeof $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val();
                        lnDstCgID = typeof $('#' + rowPrfxNm + rndmNum + '_DstCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_DstCageID').val();
                        if (Number(lnSrcCgID.replace(/[^-?0-9\.]/g, '')) <= 0 && Number(lnDstCgID.replace(/[^-?0-9\.]/g, '')) <= 0)
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_ItemNm').addClass('rho-error');
                            $('#vmsDfltDestVlt').addClass('rho-error');
                            $('#vmsDfltDestCage').addClass('rho-error');
                            $('#vmsDfltSrcVlt').addClass('rho-error');
                            $('#vmsDfltSrcCage').addClass('rho-error');
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Source and Destination Cages cannot be both empty for Row' + i + '!</span></p>';
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_ItemNm').removeClass('rho-error');
                            $('#vmsDfltDestVlt').removeClass('rho-error');
                            $('#vmsDfltDestCage').removeClass('rho-error');
                            $('#vmsDfltSrcVlt').removeClass('rho-error');
                            $('#vmsDfltSrcCage').removeClass('rho-error');
                        }
                        if (isVld === true)
                        {
                            slctdVMSTrnsLines = slctdVMSTrnsLines + $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_ItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_DstCageID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_Qty').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_UntVal').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_SrcItemState').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_DstItemState').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_LineDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_BaseUoMID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    $('#oneVmsTrnsCoinsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnItemID = typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
                    var lnQty = typeof $('#' + rowPrfxNm + rndmNum + '_Qty').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_Qty').val();
                    lnQty = lnQty.replace(/[^-?0-9\.]/g, '');
                    if (lnQty.indexOf('.') !== -1) {
                        lnQty = lnQty.substring(0, lnQty.indexOf('.'));
                    }
                    if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0 || lnItemID <= 0)
                    {
                        /*Do Nothing*/
                    } else if (lnItemID > 0) {
                        if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0
                                || Math.round((Number(lnQty.replace(/[^-?0-9\.]/g, '')) - Math.floor(Number(lnQty.replace(/[^-?0-9\.]/g, '')))), 2) > 0
                                ) {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Qty').addClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Qty').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').removeClass('rho-error');
                            var lnUntVal = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
                            if (Number(lnUntVal.replace(/[^-?0-9\.]/g, '')) <= 0)
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').removeClass('rho-error');
                            }
                            var lnFncCrncy = typeof $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val();
                            if (Number(lnFncCrncy.replace(/[^-?0-9\.]/g, '')) <= 0 && (vmsTrnsType.trim() === "Currency Sale" || vmsTrnsType.trim() === "Currency Purchase"))
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').removeClass('rho-error');
                            }
                            var lnSrcCgID = typeof $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val();
                            var lnDstCgID = typeof $('#' + rowPrfxNm + rndmNum + '_DstCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_DstCageID').val();
                            if ((vmsTrnsType.trim() === "Deposits" || vmsTrnsType.trim() === "Currency Purchase") && Number(vmsDfltDestCageID) > 0) {
                                $('#' + rowPrfxNm + rndmNum + '_DstCageID').val(vmsDfltDestCageID);
                            } else if ((vmsTrnsType.trim() === "Withdrawals" || vmsTrnsType.trim() === "Currency Sale") && Number(vmsDfltSrcCageID) > 0) {
                                $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val(vmsDfltSrcCageID);
                            }
                            lnSrcCgID = typeof $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val();
                            lnDstCgID = typeof $('#' + rowPrfxNm + rndmNum + '_DstCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_DstCageID').val();
                            if (Number(lnSrcCgID.replace(/[^-?0-9\.]/g, '')) <= 0 && Number(lnDstCgID.replace(/[^-?0-9\.]/g, '')) <= 0)
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_ItemNm').addClass('rho-error');
                                $('#vmsDfltDestVlt').addClass('rho-error');
                                $('#vmsDfltDestCage').addClass('rho-error');
                                $('#vmsDfltSrcVlt').addClass('rho-error');
                                $('#vmsDfltSrcCage').addClass('rho-error');
                                errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">Source and Destination Cages cannot be both empty for Row' + i + '!</span></p>';
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_ItemNm').removeClass('rho-error');
                                $('#vmsDfltDestVlt').removeClass('rho-error');
                                $('#vmsDfltDestCage').removeClass('rho-error');
                                $('#vmsDfltSrcVlt').removeClass('rho-error');
                                $('#vmsDfltSrcCage').removeClass('rho-error');
                            }
                            if (isVld === true)
                            {
                                slctdVMSTrnsLines = slctdVMSTrnsLines + $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_ItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_DstCageID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_Qty').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_UntVal').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_SrcItemState').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_DstItemState').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_LineDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_BaseUoMID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                            }
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var msg = 'VMS Transaction';
    var dialog = bootbox.alert({
        title: 'Save ' + msg,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
        callback: function () {
            if (shdSbmt > 0) {
                getOneVmsTrnsForm(vmsTrnsHdrID, vmsTrnsType, 20, 'ReloadDialog', tblrVwTyp, '', srcMenu,
                        isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID);
            } else {
                if (sbmtdShelfID <= 0) {
                    reloadVMSTrnsLines(vmsTrnsHdrID, vmsTrnsType, 28, -1, 'Note', 0);
                } else {
                    reloadVMSTrnsLines(vmsTrnsHdrID, vmsTrnsType, 36, -1, 'Note', 0);
                }
            }
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
                    grp: 25,
                    typ: 1,
                    pg: 2,
                    q: 'UPDATE',
                    actyp: 1,
                    vmsTrnsHdrID: vmsTrnsHdrID,
                    vmsTrnsNum: vmsTrnsNum,
                    vmsTrnsClsfctn: vmsTrnsClsfctn,
                    vmsTrnsDesc: vmsTrnsDesc,
                    vmsTrnsPrsn: vmsTrnsPrsn,
                    vmsOffctStaffLocID: vmsOffctStaffLocID,
                    vmsTrnsCrncyNm: vmsTrnsCrncyNm,
                    vmsPymtCrncyNm: vmsPymtCrncyNm,
                    myPymntValsTtlVal: myPymntValsTtlVal,
                    ttlVMSDocAmntVal: ttlVMSDocAmntVal,
                    myCptrdValsTtlVal: myCptrdValsTtlVal,
                    vmsDfltSrcVltID: vmsDfltSrcVltID,
                    vmsDfltSrcCageID: vmsDfltSrcCageID,
                    vmsDfltDestVltID: vmsDfltDestVltID,
                    vmsDfltDestCageID: vmsDfltDestCageID,
                    vmsTrnsType: vmsTrnsType,
                    vmsTrnsDate: vmsTrnsDate,
                    vmsBrnchLocID: vmsBrnchLocID,
                    vmsChequeNo: vmsChequeNo,
                    vmsCstmrID: vmsCstmrID,
                    vmsCstmrSiteID: vmsCstmrSiteID,
                    shdSbmt: shdSbmt,
                    slctdVMSTrnsLines: slctdVMSTrnsLines,
                    slctdExpnsTrnsLines: slctdExpnsTrnsLines
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result.message);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function saveVmsTrnsLines()
{
    var vmsTrnsHdrID = typeof $("#vmsTrnsHdrID").val() === 'undefined' ? -1 : $("#vmsTrnsHdrID").val();
    var vmsTrnsNum = typeof $("#vmsTrnsNum").val() === 'undefined' ? '' : $("#vmsTrnsNum").val();
    var vmsTrnsCrncyNm = typeof $("#vmsTrnsCrncyNm").val() === 'undefined' ? '' : $("#vmsTrnsCrncyNm").val();
    var vmsTrnsType = typeof $("#vmsTrnsType").val() === 'undefined' ? '' : $("#vmsTrnsType").val();
    var vmsTrnsDesc = typeof $("#vmsTrnsDesc").val() === 'undefined' ? '' : $("#vmsTrnsDesc").val();
    var vmsDfltSrcVltID = typeof $("#vmsDfltSrcVltID").val() === 'undefined' ? -1 : $("#vmsDfltSrcVltID").val();
    var vmsDfltSrcCageID = typeof $("#vmsDfltSrcCageID").val() === 'undefined' ? -1 : $("#vmsDfltSrcCageID").val();
    var vmsDfltDestVltID = typeof $("#vmsDfltDestVltID").val() === 'undefined' ? -1 : $("#vmsDfltDestVltID").val();
    var vmsDfltDestCageID = typeof $("#vmsDfltDestCageID").val() === 'undefined' ? -1 : $("#vmsDfltDestCageID").val();
    var vmsCstmrID = typeof $("#vmsCstmrID").val() === 'undefined' ? '-1' : $("#vmsCstmrID").val();
    var errMsg = "";
    if (vmsTrnsHdrID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Trns ID. cannot be empty!</span></p>';
    }
    var slctdVMSTrnsLines = "";
    var isVld = true;
    var slctdLnCnt = 0;
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnItemID = typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
                    var lnQty = typeof $('#' + rowPrfxNm + rndmNum + '_Qty').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_Qty').val();
                    lnQty = lnQty.replace(/[^-?0-9\.]/g, '');
                    if (lnQty.indexOf('.') !== -1) {
                        lnQty = lnQty.substring(0, lnQty.indexOf('.'));
                    }
                    if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0 || lnItemID <= 0)
                    {
                        /*Do Nothing*/
                    } else if (lnItemID > 0) {
                        if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0
                                || Math.round((Number(lnQty.replace(/[^-?0-9\.]/g, '')) - Math.floor(Number(lnQty.replace(/[^-?0-9\.]/g, '')))), 2) > 0
                                ) {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Qty').addClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Qty').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').removeClass('rho-error');
                            var lnUntVal = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
                            if (Number(lnUntVal.replace(/[^-?0-9\.]/g, '')) <= 0)
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').removeClass('rho-error');
                            }
                            var lnFncCrncy = typeof $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val();
                            if (Number(lnFncCrncy.replace(/[^-?0-9\.]/g, '')) <= 0 && (vmsTrnsType.trim() === "Currency Sale" || vmsTrnsType.trim() === "Currency Purchase"))
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').removeClass('rho-error');
                            }
                            var lnSrcCgID = typeof $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val();
                            var lnDstCgID = typeof $('#' + rowPrfxNm + rndmNum + '_DstCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_DstCageID').val();
                            if ((vmsTrnsType.trim() === "Deposits" || vmsTrnsType.trim() === "Currency Purchase") && Number(vmsDfltDestCageID) > 0) {
                                $('#' + rowPrfxNm + rndmNum + '_DstCageID').val(vmsDfltDestCageID);
                            } else if ((vmsTrnsType.trim() === "Withdrawals" || vmsTrnsType.trim() === "Currency Sale") && Number(vmsDfltSrcCageID) > 0) {
                                $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val(vmsDfltSrcCageID);
                            }
                            lnSrcCgID = typeof $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val();
                            lnDstCgID = typeof $('#' + rowPrfxNm + rndmNum + '_DstCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_DstCageID').val();
                            if (Number(lnSrcCgID.replace(/[^-?0-9\.]/g, '')) <= 0 && Number(lnDstCgID.replace(/[^-?0-9\.]/g, '')) <= 0)
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_ItemNm').addClass('rho-error');
                                $('#vmsDfltDestVlt').addClass('rho-error');
                                $('#vmsDfltDestCage').addClass('rho-error');
                                $('#vmsDfltSrcVlt').addClass('rho-error');
                                $('#vmsDfltSrcCage').addClass('rho-error');
                                errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">Source and Destination Cages cannot be both empty for Row' + i + '!</span></p>';
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_ItemNm').removeClass('rho-error');
                                $('#vmsDfltDestVlt').removeClass('rho-error');
                                $('#vmsDfltDestCage').removeClass('rho-error');
                                $('#vmsDfltSrcVlt').removeClass('rho-error');
                                $('#vmsDfltSrcCage').removeClass('rho-error');
                            }
                            if (isVld === true)
                            {
                                slctdVMSTrnsLines = slctdVMSTrnsLines + $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_ItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_DstCageID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_Qty').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_UntVal').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_SrcItemState').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_DstItemState').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_LineDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_BaseUoMID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                slctdLnCnt++;
                            }
                        }
                    }
                }
            }
        }
    });
    $('#oneVmsTrnsCoinsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnItemID = typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
                    var lnQty = typeof $('#' + rowPrfxNm + rndmNum + '_Qty').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_Qty').val();
                    lnQty = lnQty.replace(/[^-?0-9\.]/g, '');
                    if (lnQty.indexOf('.') !== -1) {
                        lnQty = lnQty.substring(0, lnQty.indexOf('.'));
                    }
                    if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0 || lnItemID <= 0)
                    {
                        /*Do Nothing*/
                    } else if (lnItemID > 0) {
                        if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0
                                || Math.round((Number(lnQty.replace(/[^-?0-9\.]/g, '')) - Math.floor(Number(lnQty.replace(/[^-?0-9\.]/g, '')))), 2) > 0
                                ) {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Qty').addClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Qty').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').removeClass('rho-error');
                            var lnUntVal = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
                            if (Number(lnUntVal.replace(/[^-?0-9\.]/g, '')) <= 0)
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').removeClass('rho-error');
                            }
                            var lnFncCrncy = typeof $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val();
                            if (Number(lnFncCrncy.replace(/[^-?0-9\.]/g, '')) <= 0 && (vmsTrnsType.trim() === "Currency Sale" || vmsTrnsType.trim() === "Currency Purchase"))
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').removeClass('rho-error');
                            }
                            var lnSrcCgID = typeof $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val();
                            var lnDstCgID = typeof $('#' + rowPrfxNm + rndmNum + '_DstCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_DstCageID').val();
                            if ((vmsTrnsType.trim() === "Deposits" || vmsTrnsType.trim() === "Currency Purchase") && Number(vmsDfltDestCageID) > 0) {
                                $('#' + rowPrfxNm + rndmNum + '_DstCageID').val(vmsDfltDestCageID);
                            } else if ((vmsTrnsType.trim() === "Withdrawals" || vmsTrnsType.trim() === "Currency Sale") && Number(vmsDfltSrcCageID) > 0) {
                                $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val(vmsDfltSrcCageID);
                            }
                            lnSrcCgID = typeof $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val();
                            lnDstCgID = typeof $('#' + rowPrfxNm + rndmNum + '_DstCageID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_DstCageID').val();
                            if (Number(lnSrcCgID.replace(/[^-?0-9\.]/g, '')) <= 0 && Number(lnDstCgID.replace(/[^-?0-9\.]/g, '')) <= 0)
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_ItemNm').addClass('rho-error');
                                $('#vmsDfltDestVlt').addClass('rho-error');
                                $('#vmsDfltDestCage').addClass('rho-error');
                                $('#vmsDfltSrcVlt').addClass('rho-error');
                                $('#vmsDfltSrcCage').addClass('rho-error');
                                errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">Source and Destination Cages cannot be both empty for Row' + i + '!</span></p>';
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_ItemNm').removeClass('rho-error');
                                $('#vmsDfltDestVlt').removeClass('rho-error');
                                $('#vmsDfltDestCage').removeClass('rho-error');
                                $('#vmsDfltSrcVlt').removeClass('rho-error');
                                $('#vmsDfltSrcCage').removeClass('rho-error');
                            }
                            if (isVld === true)
                            {
                                slctdVMSTrnsLines = slctdVMSTrnsLines + $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_ItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_DstCageID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_Qty').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_UntVal').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_SrcItemState').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_DstItemState').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_LineDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_BaseUoMID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                slctdLnCnt++;
                            }
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        $body = $("body");
        $body.removeClass("mdlloading");
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return 1;
    }
    if (slctdLnCnt >= 1) {
        $.ajax({
            method: "POST",
            url: "index.php",
            data: {
                grp: 25,
                typ: 1,
                pg: 2,
                q: 'UPDATE',
                actyp: 6,
                vmsTrnsHdrID: vmsTrnsHdrID,
                vmsTrnsNum: vmsTrnsNum,
                vmsTrnsCrncyNm: vmsTrnsCrncyNm,
                vmsTrnsDesc: vmsTrnsDesc,
                vmsDfltSrcVltID: vmsDfltSrcVltID,
                vmsDfltSrcCageID: vmsDfltSrcCageID,
                vmsDfltDestVltID: vmsDfltDestVltID,
                vmsDfltDestCageID: vmsDfltDestCageID,
                vmsTrnsType: vmsTrnsType,
                vmsCstmrID: vmsCstmrID,
                slctdVMSTrnsLines: slctdVMSTrnsLines
            },
            success: function (result) {
                return 5;
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                console.warn(jqXHR.responseText);
                return 1;
            }
        });
    }
    return 5;
    /*getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {});
     * var msg = 'VMS Transaction Lines';
     var dialog = bootbox.alert({
     title: 'Save ' + msg,
     size: 'small',
     message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
     callback: function () {
     }
     });
     dialog.init(function () {});*/
}

function saveVmsRvrsTrnsForm(funcCrncyNm, shdSbmt, tblrVwTyp, srcMenu,
        isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID)
{
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslVmsTrnsBtn");
    }
    if (typeof sbmtdShelfID === 'undefined' || sbmtdShelfID === null) {
        sbmtdShelfID = -1;
    }
    var vmsTrnsHdrID = typeof $("#vmsTrnsHdrID").val() === 'undefined' ? -1 : $("#vmsTrnsHdrID").val();
    var vmsTrnsNum = typeof $("#vmsTrnsNum").val() === 'undefined' ? '' : $("#vmsTrnsNum").val();
    var vmsTrnsType = typeof $("#vmsTrnsType").val() === 'undefined' ? '' : $("#vmsTrnsType").val();
    var vmsTrnsDesc = typeof $("#vmsTrnsDesc").val() === 'undefined' ? '' : $("#vmsTrnsDesc").val();
    var vmsTrnsDesc1 = typeof $("#vmsTrnsDesc1").val() === 'undefined' ? '' : $("#vmsTrnsDesc1").val();
    var vmsTrnsCrncyNm = typeof $("#vmsTrnsCrncyNm").val() === 'undefined' ? '' : $("#vmsTrnsCrncyNm").val();
    var vmsPymtCrncyNm = typeof $("#vmsPymtCrncyNm").val() === 'undefined' ? '' : $("#vmsPymtCrncyNm").val();
    var myPymntValsTtlVal = typeof $("#myPymntValsTtlVal").val() === 'undefined' ? '' : $("#myPymntValsTtlVal").val();
    var ttlVMSDocAmntVal = typeof $("#ttlVMSDocAmntVal").val() === 'undefined' ? 0 : $("#ttlVMSDocAmntVal").val();
    var myCptrdValsTtlVal = typeof $("#myCptrdValsTtlVal").val() === 'undefined' ? 0 : $("#myCptrdValsTtlVal").val();
    var vmsDfltSrcCageID = typeof $("#vmsDfltSrcCageID").val() === 'undefined' ? -1 : $("#vmsDfltSrcCageID").val();
    var vmsDfltDestCageID = typeof $("#vmsDfltDestCageID").val() === 'undefined' ? -1 : $("#vmsDfltDestCageID").val();
    var vmsCstmrID = typeof $("#vmsCstmrID").val() === 'undefined' ? '-1' : $("#vmsCstmrID").val();
    var errMsg = "";
    if (vmsTrnsHdrID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Trns ID. cannot be empty!</span></p>';
    }
    if (vmsTrnsDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Remark/Narration cannot be empty!</span></p>';
    }
    if (vmsTrnsDesc === "" || vmsTrnsDesc === vmsTrnsDesc1)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#vmsTrnsDesc").addClass('rho-error');
        $("#vmsTrnsDesc").attr("readonly", false);
        $("#fnlzeRvrslVmsTrnsBtn").attr("disabled", false);
    } else {
        $("#vmsTrnsDesc").removeClass('rho-error');
    }
    if (vmsTrnsCrncyNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Currency cannot be empty!</span></p>';
    }
    ttlVMSDocAmntVal = fmtAsNumber('ttlVMSDocAmntVal').toFixed(2);
    myCptrdValsTtlVal = fmtAsNumber('myCptrdValsTtlVal').toFixed(2);
    if (myCptrdValsTtlVal != ttlVMSDocAmntVal)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    if (vmsTrnsType.trim() === "Currency Sale")
    {
        if (Number(vmsCstmrID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Client cannot be Empty for this Trns Type!</span></p>';
        }
        if (Number(vmsDfltSrcCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Source Cage cannot be Empty for this Trns Type!</span></p>';
        }
        if (isNumber(myPymntValsTtlVal) === false)
        {
            myPymntValsTtlVal = fmtAsNumber('myPymntValsTtlVal').toFixed(2);
        }
        if (myPymntValsTtlVal == 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Please provide a valid Non-Zero Figure for the Payment Amount!</span></p>';
        }
        if (vmsTrnsCrncyNm === vmsPymtCrncyNm) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Sold Currency and Payment Currency cannot be the same!</span></p>';
        }
        if (vmsTrnsCrncyNm === funcCrncyNm) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Sold Currency cannot be your Functional Currency for this Trns. Type!</span></p>';
        }
    } else if (vmsTrnsType.trim() === "Currency Purchase" || vmsTrnsType.trim() === "Currency Importation")
    {
        if (Number(vmsCstmrID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Vendor cannot be Empty for this Trns Type!</span></p>';
        }
        if (Number(vmsDfltDestCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Destination Cage cannot be Empty for this Trns Type!</span></p>';
        }
        if (isNumber(myPymntValsTtlVal) === false)
        {
            myPymntValsTtlVal = fmtAsNumber('myPymntValsTtlVal').toFixed(2);
        }
        if (myPymntValsTtlVal == 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Please provide a valid Non-Zero Figure for the Payment Amount!</span></p>';
        }
        if (vmsTrnsCrncyNm === vmsPymtCrncyNm) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Sold Currency and Payment Currency cannot be the same!</span></p>';
        }
        if (vmsTrnsCrncyNm === funcCrncyNm && vmsTrnsType.trim() !== "Currency Importation") {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Sold Currency cannot be your Functional Currency for this Trns. Type!</span></p>';
        }
    } else if (vmsTrnsType.trim() === "Deposits" || vmsTrnsType.trim() === "GL/Vault Account Transfers")
    {
        if (Number(vmsCstmrID) <= 0 && vmsTrnsType.trim() === "Deposits")
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Client/Vendor cannot be Empty for this Trns Type!</span></p>';
        }
        if (Number(vmsDfltDestCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Destination Cage cannot be Empty for this Trns Type!</span></p>';
        }
    } else if (vmsTrnsType.trim() === "Withdrawals")
    {
        if (Number(vmsCstmrID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Client cannot be Empty for this Trns Type!</span></p>';
        }
        if (Number(vmsDfltSrcCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Source Cage cannot be Empty for this Trns Type!</span></p>';
        }
    } else if (vmsTrnsType.trim() === "Currency Destruction" || vmsTrnsType.trim() === "Vault/GL Account Transfers")
    {
        if (Number(vmsDfltSrcCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Source Cage cannot be Empty for this Trns Type!</span></p>';
        }
    } else if (vmsTrnsType.trim() === "Miscellaneous Adjustments" || vmsTrnsType.trim() === "Direct Cage/Shelve Transaction")
    {
        if (Number(vmsDfltSrcCageID) <= 0 && Number(vmsDfltDestCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Source and Destination Cages cannot be both Empty for this Trns Type!</span></p>';
        }
    } else {
        if (Number(vmsDfltSrcCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Source Vault/Cage cannot be empty!</span></p>';
        }
        if (Number(vmsDfltDestCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Destination Vault/Cage cannot be empty!</span></p>';
        }
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        $("#fnlzeRvrslVmsTrnsBtn").attr("disabled", false);
        return false;
    }
    var msgsTitle = 'VMS Transaction';
    var dialog = bootbox.confirm({
        title: 'Void ' + msgsTitle + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">VOID</span> this ' + msgsTitle + '?<br/>Action cannot be Undone!</p>',
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
                var msg = 'VMS Transaction';
                var dialog = bootbox.alert({
                    title: 'Save ' + msg,
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
                    callback: function () {
                        var newVMSHdrID = $('#newVMSHdrID').val();
                        if (shdSbmt > 0 || newVMSHdrID > 0) {
                            if (sbmtdShelfID > 0) {
                                getOneVmsTrnsForm(newVMSHdrID, vmsTrnsType, 34, 'ReloadDialog', tblrVwTyp, '', srcMenu,
                                        isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID);
                            } else {
                                getOneVmsTrnsForm(newVMSHdrID, vmsTrnsType, 20, 'ReloadDialog', tblrVwTyp, '', srcMenu,
                                        isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID);
                            }
                        }
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
                                grp: 25,
                                typ: 1,
                                pg: 2,
                                q: 'UPDATE',
                                actyp: 4,
                                vmsTrnsHdrID: vmsTrnsHdrID,
                                shdSbmt: shdSbmt,
                                vmsTrnsDesc: vmsTrnsDesc,
                                vmsTrnsNum: vmsTrnsNum
                            },
                            success: function (result) {
                                setTimeout(function () {
                                    $('#newVMSHdrID').val(result.newVMSHdrID);
                                    dialog.find('.bootbox-body').html(result.message);
                                }, 500);
                            },
                            error: function (jqXHR, textStatus, errorThrown)
                            {
                                /*dialog.find('.bootbox-body').html(errorThrown);*/
                                console.warn(jqXHR.responseText);
                            }
                        });
                    });
                });
            }
        }
    });
}

function saveVmsPymtForm(funcCrncyNm)
{
    var varTtlPymtStr = typeof $("#myPymntValsTtlVal").val() === 'undefined' ? '' : $("#myPymntValsTtlVal").val();
    var varTtlExpctdPymt = Number(varTtlPymtStr.replace(/[^-?0-9\.]/g, ''));
    var sbmtdVmsPymtHdrID = typeof $("#sbmtdVmsPymtHdrID").val() === 'undefined' ? -1 : $("#sbmtdVmsPymtHdrID").val();
    var vmsTrnsType = typeof $("#vmsTrnsType").val() === 'undefined' ? '' : $("#vmsTrnsType").val();
    var vmsPymtCrncyNm = typeof $("#vmsPymtCrncyNm").val() === 'undefined' ? '' : $("#vmsPymtCrncyNm").val();
    var myPymntsTtlVal = typeof $("#myPymntsTtlVal").val() === 'undefined' ? '' : $("#myPymntsTtlVal").val();
    var vmsCstmrID = typeof $("#vmsCstmrID").val() === 'undefined' ? '' : $("#vmsCstmrID").val();
    var vmsPymtDfltDestVltID = typeof $("#vmsPymtDfltDestVltID").val() === 'undefined' ? -1 : $("#vmsPymtDfltDestVltID").val();
    var vmsPymtDfltDestCageID = typeof $("#vmsPymtDfltDestCageID").val() === 'undefined' ? -1 : $("#vmsPymtDfltDestCageID").val();
    var errMsg = "";
    if (sbmtdVmsPymtHdrID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Trns ID. cannot be empty!</span></p>';
    }
    myPymntsTtlVal = fmtAsNumber('myPymntsTtlVal').toFixed(2);
    if (myPymntsTtlVal != varTtlExpctdPymt)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    if (vmsTrnsType.trim() === "Currency Sale")
    {
        if (Number(vmsPymtDfltDestCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Vault/Cage cannot be Empty!</span></p>';
        }
    } else if (vmsTrnsType.trim() === "Currency Purchase")
    {
        if (Number(vmsPymtDfltDestCageID) <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Vault/Cage cannot be Empty!</span></p>';
        }
    } else {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Payments can only be done for Currency Sale/Purchase Transaction Types!</span></p>';
    }
    if (vmsPymtCrncyNm !== funcCrncyNm) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Payment Currency must be your Functional Currency for this Trns. Type!</span></p>';
    }
    var slctdVMSTrnsLines = "";
    var isVld = true;
    $('#oneVmsPymtsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#oneVmsPymtsRow' + rndmNum + '_ItmID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnItemID = typeof $('#oneVmsPymtsRow' + rndmNum + '_ItmID').val() === 'undefined' ? 0 : $('#oneVmsPymtsRow' + rndmNum + '_ItmID').val();
                    var lnQty = typeof $('#oneVmsPymtsRow' + rndmNum + '_Qty').val() === 'undefined' ? 0 : $('#oneVmsPymtsRow' + rndmNum + '_Qty').val();
                    lnQty = lnQty.replace(/[^-?0-9\.]/g, '');
                    if (lnQty.indexOf('.') !== -1) {
                        lnQty = lnQty.substring(0, lnQty.indexOf('.'));
                    }
                    if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0 || lnItemID <= 0)
                    {
                        /*Do Nothing*/
                    } else if (lnItemID > 0) {
                        if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0
                                || Math.round((Number(lnQty.replace(/[^-?0-9\.]/g, '')) - Math.floor(Number(lnQty.replace(/[^-?0-9\.]/g, '')))), 2) > 0
                                ) {
                            isVld = false;
                            $('#oneVmsPymtsRow' + rndmNum + '_Qty').addClass('rho-error');
                            $('#oneVmsPymtsRow' + rndmNum + '_TtlVal').addClass('rho-error');
                        } else {
                            $('#oneVmsPymtsRow' + rndmNum + '_Qty').removeClass('rho-error');
                            $('#oneVmsPymtsRow' + rndmNum + '_TtlVal').removeClass('rho-error');
                            var lnUntVal = typeof $('#oneVmsPymtsRow' + rndmNum + '_UntVal').val() === 'undefined' ? 0 : $('#oneVmsPymtsRow' + rndmNum + '_UntVal').val();
                            if (lnUntVal <= 0)
                            {
                                isVld = false;
                                $('#oneVmsPymtsRow' + rndmNum + '_UntVal').addClass('rho-error');
                            } else {
                                $('#oneVmsPymtsRow' + rndmNum + '_UntVal').removeClass('rho-error');
                            }
                            if (isVld === true)
                            {
                                slctdVMSTrnsLines = slctdVMSTrnsLines + $('#oneVmsPymtsRow' + rndmNum + '_TrnsLnID').val() + "~"
                                        + $('#oneVmsPymtsRow' + rndmNum + '_ItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#oneVmsPymtsRow' + rndmNum + '_SrcCageID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#oneVmsPymtsRow' + rndmNum + '_DstCageID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#oneVmsPymtsRow' + rndmNum + '_Qty').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#oneVmsPymtsRow' + rndmNum + '_UntVal').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#oneVmsPymtsRow' + rndmNum + '_PymntCrncyRate').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#oneVmsPymtsRow' + rndmNum + '_SrcItemState').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#oneVmsPymtsRow' + rndmNum + '_DstItemState').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#oneVmsPymtsRow' + rndmNum + '_LnTrnsType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#oneVmsPymtsRow' + rndmNum + '_BaseUoMID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                            }
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    }
    var msg = 'VMS Payment Transaction';
    var dialog = bootbox.alert({
        title: 'Save ' + msg,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
        callback: function () {
            getVMSTrnsPymtForm(vmsTrnsType, 32, 'ReloadDialog');
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
                    grp: 25,
                    typ: 1,
                    pg: 2,
                    q: 'UPDATE',
                    actyp: 3,
                    sbmtdVmsPymtHdrID: sbmtdVmsPymtHdrID,
                    vmsPymtCrncyNm: vmsPymtCrncyNm,
                    myPymntsTtlVal: myPymntsTtlVal,
                    vmsCstmrID: vmsCstmrID,
                    varTtlExpctdPymt: varTtlExpctdPymt,
                    vmsPymtDfltDestVltID: vmsPymtDfltDestVltID,
                    vmsPymtDfltDestCageID: vmsPymtDfltDestCageID,
                    vmsTrnsType: vmsTrnsType,
                    slctdVMSTrnsLines: slctdVMSTrnsLines
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result.message);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function saveVmsDrctTrnsForm(funcCrncyNm, shdSbmt, tblrVwTyp, srcMenu, isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID)
{
    if (typeof sbmtdShelfID === 'undefined' || sbmtdShelfID === null) {
        sbmtdShelfID = -1;
    }
    var vmsTrnsHdrID = typeof $("#vmsTrnsHdrID").val() === 'undefined' ? -1 : $("#vmsTrnsHdrID").val();
    var vmsTrnsNum = typeof $("#vmsTrnsNum").val() === 'undefined' ? '' : $("#vmsTrnsNum").val();
    var vmsTrnsType = typeof $("#vmsTrnsType").val() === 'undefined' ? '' : $("#vmsTrnsType").val();
    var vmsTrnsDate = typeof $("#vmsTrnsDate").val() === 'undefined' ? '' : $("#vmsTrnsDate").val();
    var vmsTrnsClsfctn = typeof $("#vmsTrnsClsfctn").val() === 'undefined' ? '' : $("#vmsTrnsClsfctn").val();
    var vmsTrnsDesc = typeof $("#vmsTrnsDesc").val() === 'undefined' ? '' : $("#vmsTrnsDesc").val();
    var vmsTrnsPrsn = typeof $("#vmsTrnsPrsn").val() === 'undefined' ? '' : $("#vmsTrnsPrsn").val();
    var vmsOffctStaffLocID = typeof $("#vmsOffctStaffLocID").val() === 'undefined' ? '' : $("#vmsOffctStaffLocID").val();
    var vmsTrnsCrncyNm = typeof $("#vmsTrnsCrncyNm").val() === 'undefined' ? '' : $("#vmsTrnsCrncyNm").val();
    var ttlVMSDocAmntVal = typeof $("#ttlVMSDocAmntVal").val() === 'undefined' ? 0 : $("#ttlVMSDocAmntVal").val();
    var myCptrdValsTtlVal = typeof $("#myCptrdValsTtlVal").val() === 'undefined' ? 0 : $("#myCptrdValsTtlVal").val();
    var vmsDfltDestVltID = typeof $("#vmsDfltDestVltID").val() === 'undefined' ? -1 : $("#vmsDfltDestVltID").val();
    var vmsDfltDestCageID = typeof $("#vmsDfltDestCageID").val() === 'undefined' ? -1 : $("#vmsDfltDestCageID").val();
    var vmsBrnchLocID = typeof $("#vmsBrnchLocID").val() === 'undefined' ? -1 : $("#vmsBrnchLocID").val();
    var vmsCstmrID = typeof $("#vmsCstmrID").val() === 'undefined' ? '-1' : $("#vmsCstmrID").val();
    var vmsCstmrSiteID = typeof $("#vmsCstmrSiteID").val() === 'undefined' ? -1 : $("#vmsCstmrSiteID").val();
    var vmsChequeNo = typeof $("#vmsChequeNo").val() === 'undefined' ? '' : $("#vmsChequeNo").val();
    var errMsg = "";
    if (vmsTrnsHdrID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Trns ID. cannot be empty!</span></p>';
    }
    if (vmsTrnsDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Remark/Narration cannot be empty!</span></p>';
    }
    if (vmsTrnsCrncyNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Currency cannot be empty!</span></p>';
    }
    ttlVMSDocAmntVal = fmtAsNumber('ttlVMSDocAmntVal').toFixed(2);
    myCptrdValsTtlVal = fmtAsNumber('myCptrdValsTtlVal').toFixed(2);
    if (myCptrdValsTtlVal != ttlVMSDocAmntVal)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    if (Number(vmsDfltDestCageID) <= 0 || Number(vmsDfltDestVltID) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Destination Vault/Cage cannot be empty!</span></p>';
    }
    var slctdVMSTrnsLines = "";
    var isVld = true;
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnItemID = typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
                    var lnQty = typeof $('#' + rowPrfxNm + rndmNum + '_Qty').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_Qty').val();
                    lnQty = lnQty.replace(/[^-?0-9\.]/g, '');
                    if (lnQty.indexOf('.') !== -1) {
                        lnQty = lnQty.substring(0, lnQty.indexOf('.'));
                    }
                    if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0 || lnItemID <= 0)
                    {
                        /*Do Nothing*/
                    } else if (lnItemID > 0) {
                        if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) < 0
                                || Math.round((Number(lnQty.replace(/[^-?0-9\.]/g, '')) - Math.floor(Number(lnQty.replace(/[^-?0-9\.]/g, '')))), 2) > 0
                                ) {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Qty').addClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Qty').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').removeClass('rho-error');
                            var lnUntVal = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
                            if (Number(lnUntVal.replace(/[^-?0-9\.]/g, '')) <= 0)
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').removeClass('rho-error');
                            }
                            var lnDesc = typeof $('#' + rowPrfxNm + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_LineDesc').val();
                            if (lnDesc.trim() === "")
                            {
                                if (vmsTrnsDesc.trim() === "") {
                                    isVld = false;
                                    $('#' + rowPrfxNm + rndmNum + '_LineDesc').addClass('rho-error');
                                } else {
                                    $('#' + rowPrfxNm + rndmNum + '_LineDesc').val(vmsTrnsDesc);
                                    $('#' + rowPrfxNm + rndmNum + '_LineDesc').removeClass('rho-error');
                                }
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_LineDesc').removeClass('rho-error');
                            }
                            if (isVld === true)
                            {
                                slctdVMSTrnsLines = slctdVMSTrnsLines + $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_ItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_LnTrnsType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_Qty').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_UntVal').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_LineDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_BaseUoMID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                            }
                        }
                    }
                }
            }
        }
    });

    $('#oneVmsTrnsCoinsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnItemID = typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
                    var lnQty = typeof $('#' + rowPrfxNm + rndmNum + '_Qty').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_Qty').val();
                    lnQty = lnQty.replace(/[^-?0-9\.]/g, '');
                    if (lnQty.indexOf('.') !== -1) {
                        lnQty = lnQty.substring(0, lnQty.indexOf('.'));
                    }
                    if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0 || lnItemID <= 0)
                    {
                        /*Do Nothing*/
                    } else if (lnItemID > 0) {
                        if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) < 0
                                || Math.round((Number(lnQty.replace(/[^-?0-9\.]/g, '')) - Math.floor(Number(lnQty.replace(/[^-?0-9\.]/g, '')))), 2) > 0
                                ) {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Qty').addClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Qty').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').removeClass('rho-error');
                            var lnUntVal = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
                            if (Number(lnUntVal.replace(/[^-?0-9\.]/g, '')) <= 0)
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').removeClass('rho-error');
                            }
                            var lnDesc = typeof $('#' + rowPrfxNm + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_LineDesc').val();
                            if (lnDesc.trim() === "")
                            {
                                if (vmsTrnsDesc.trim() === "") {
                                    isVld = false;
                                    $('#' + rowPrfxNm + rndmNum + '_LineDesc').addClass('rho-error');
                                } else {
                                    $('#' + rowPrfxNm + rndmNum + '_LineDesc').val(vmsTrnsDesc);
                                    $('#' + rowPrfxNm + rndmNum + '_LineDesc').removeClass('rho-error');
                                }
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_LineDesc').removeClass('rho-error');
                            }
                            if (isVld === true)
                            {
                                slctdVMSTrnsLines = slctdVMSTrnsLines + $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_ItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_LnTrnsType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_Qty').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_UntVal').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_LineDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_BaseUoMID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                            }
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var msg = 'VMS Transaction';
    var dialog = bootbox.alert({
        title: 'Save ' + msg,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
        callback: function () {
            if (shdSbmt > 0) {
                getOneVmsTrnsForm(vmsTrnsHdrID, vmsTrnsType, 34, 'ReloadDialog', tblrVwTyp, '', srcMenu,
                        isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID);
            } else {
                reloadVMSTrnsLines(vmsTrnsHdrID, vmsTrnsType, 36, -1, 'Note', 0);
            }
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
                    grp: 25,
                    typ: 1,
                    pg: 2,
                    q: 'UPDATE',
                    actyp: 5,
                    vmsTrnsHdrID: vmsTrnsHdrID,
                    vmsTrnsNum: vmsTrnsNum,
                    vmsTrnsClsfctn: vmsTrnsClsfctn,
                    vmsTrnsDesc: vmsTrnsDesc,
                    vmsTrnsCrncyNm: vmsTrnsCrncyNm,
                    ttlVMSDocAmntVal: ttlVMSDocAmntVal,
                    myCptrdValsTtlVal: myCptrdValsTtlVal,
                    vmsDfltDestVltID: vmsDfltDestVltID,
                    vmsDfltDestCageID: vmsDfltDestCageID,
                    vmsTrnsType: vmsTrnsType,
                    vmsTrnsDate: vmsTrnsDate,
                    vmsBrnchLocID: vmsBrnchLocID,
                    vmsCstmrID: vmsCstmrID,
                    vmsCstmrSiteID: vmsCstmrSiteID,
                    vmsTrnsPrsn: vmsTrnsPrsn,
                    vmsOffctStaffLocID: vmsOffctStaffLocID,
                    vmsChequeNo: vmsChequeNo,
                    shdSbmt: shdSbmt,
                    slctdVMSTrnsLines: slctdVMSTrnsLines
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result.message);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function saveVmsDrctTrnsLines()
{
    var vmsTrnsHdrID = typeof $("#vmsTrnsHdrID").val() === 'undefined' ? -1 : $("#vmsTrnsHdrID").val();
    var vmsTrnsNum = typeof $("#vmsTrnsNum").val() === 'undefined' ? '' : $("#vmsTrnsNum").val();
    var vmsTrnsType = typeof $("#vmsTrnsType").val() === 'undefined' ? '' : $("#vmsTrnsType").val();
    var vmsTrnsDesc = typeof $("#vmsTrnsDesc").val() === 'undefined' ? '' : $("#vmsTrnsDesc").val();
    var vmsTrnsCrncyNm = typeof $("#vmsTrnsCrncyNm").val() === 'undefined' ? '' : $("#vmsTrnsCrncyNm").val();
    var vmsDfltDestVltID = typeof $("#vmsDfltDestVltID").val() === 'undefined' ? -1 : $("#vmsDfltDestVltID").val();
    var vmsDfltDestCageID = typeof $("#vmsDfltDestCageID").val() === 'undefined' ? -1 : $("#vmsDfltDestCageID").val();
    var vmsCstmrID = typeof $("#vmsCstmrID").val() === 'undefined' ? '-1' : $("#vmsCstmrID").val();
    var errMsg = "";
    if (vmsTrnsHdrID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Trns ID. cannot be empty!</span></p>';
    }
    if (vmsTrnsDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Remark/Narration cannot be empty!</span></p>';
    }
    if (vmsTrnsCrncyNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Currency cannot be empty!</span></p>';
    }
    if (Number(vmsDfltDestCageID) <= 0 || Number(vmsDfltDestVltID) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Vault/Cage cannot be empty!</span></p>';
    }
    var slctdVMSTrnsLines = "";
    var isVld = true;
    var slctdLnCnt = 0;
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnItemID = typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
                    var lnQty = typeof $('#' + rowPrfxNm + rndmNum + '_Qty').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_Qty').val();
                    lnQty = lnQty.replace(/[^-?0-9\.]/g, '');
                    if (lnQty.indexOf('.') !== -1) {
                        lnQty = lnQty.substring(0, lnQty.indexOf('.'));
                    }
                    if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0 || lnItemID <= 0)
                    {
                        /*Do Nothing*/
                    } else if (lnItemID > 0) {
                        if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) < 0
                                || Math.round((Number(lnQty.replace(/[^-?0-9\.]/g, '')) - Math.floor(Number(lnQty.replace(/[^-?0-9\.]/g, '')))), 2) > 0
                                ) {
                            isVld = false;
                            $('#oneVmsPymtsRow' + rndmNum + '_Qty').addClass('rho-error');
                            $('#oneVmsPymtsRow' + rndmNum + '_TtlVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Qty').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').removeClass('rho-error');
                            var lnUntVal = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
                            if (Number(lnUntVal.replace(/[^-?0-9\.]/g, '')) <= 0)
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').removeClass('rho-error');
                            }
                            var lnDesc = typeof $('#' + rowPrfxNm + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_LineDesc').val();
                            if (lnDesc.trim() === "")
                            {
                                if (vmsTrnsDesc.trim() === "") {
                                    isVld = false;
                                    $('#' + rowPrfxNm + rndmNum + '_LineDesc').addClass('rho-error');
                                } else {
                                    $('#' + rowPrfxNm + rndmNum + '_LineDesc').val(vmsTrnsDesc);
                                    $('#' + rowPrfxNm + rndmNum + '_LineDesc').removeClass('rho-error');
                                }
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_LineDesc').removeClass('rho-error');
                            }
                            if (isVld === true)
                            {
                                slctdVMSTrnsLines = slctdVMSTrnsLines + $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_ItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_LnTrnsType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_Qty').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_UntVal').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_LineDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_BaseUoMID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                slctdLnCnt++;
                            }
                        }
                    }
                }
            }
        }
    });

    $('#oneVmsTrnsCoinsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnItemID = typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
                    var lnQty = typeof $('#' + rowPrfxNm + rndmNum + '_Qty').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_Qty').val();
                    lnQty = lnQty.replace(/[^-?0-9\.]/g, '');
                    if (lnQty.indexOf('.') !== -1) {
                        lnQty = lnQty.substring(0, lnQty.indexOf('.'));
                    }
                    if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) == 0 || lnItemID <= 0)
                    {
                        /*Do Nothing*/
                    } else if (lnItemID > 0) {
                        if (Number(lnQty.replace(/[^-?0-9\.]/g, '')) < 0
                                || Math.round((Number(lnQty.replace(/[^-?0-9\.]/g, '')) - Math.floor(Number(lnQty.replace(/[^-?0-9\.]/g, '')))), 2) > 0
                                ) {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Qty').addClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Qty').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_TtlVal').removeClass('rho-error');
                            var lnUntVal = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
                            if (Number(lnUntVal.replace(/[^-?0-9\.]/g, '')) <= 0)
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_UntVal').removeClass('rho-error');
                            }
                            var lnDesc = typeof $('#' + rowPrfxNm + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_LineDesc').val();
                            if (lnDesc.trim() === "")
                            {
                                if (vmsTrnsDesc.trim() === "") {
                                    isVld = false;
                                    $('#' + rowPrfxNm + rndmNum + '_LineDesc').addClass('rho-error');
                                } else {
                                    $('#' + rowPrfxNm + rndmNum + '_LineDesc').val(vmsTrnsDesc);
                                    $('#' + rowPrfxNm + rndmNum + '_LineDesc').removeClass('rho-error');
                                }
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_LineDesc').removeClass('rho-error');
                            }
                            if (isVld === true)
                            {
                                slctdVMSTrnsLines = slctdVMSTrnsLines + $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_ItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_LnTrnsType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_Qty').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_UntVal').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_LineDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_BaseUoMID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                slctdLnCnt++;
                            }
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        $body = $("body");
        $body.removeClass("mdlloading");
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return 1;
    }
    if (slctdLnCnt > 0) {
        $.ajax({
            method: "POST",
            url: "index.php",
            data: {
                grp: 25,
                typ: 1,
                pg: 2,
                q: 'UPDATE',
                actyp: 7,
                vmsTrnsHdrID: vmsTrnsHdrID,
                vmsTrnsNum: vmsTrnsNum,
                vmsTrnsDesc: vmsTrnsDesc,
                vmsTrnsCrncyNm: vmsTrnsCrncyNm,
                vmsDfltDestVltID: vmsDfltDestVltID,
                vmsDfltDestCageID: vmsDfltDestCageID,
                vmsTrnsType: vmsTrnsType,
                vmsCstmrID: vmsCstmrID,
                slctdVMSTrnsLines: slctdVMSTrnsLines
            },
            success: function (result) {
                return 5;
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                console.warn(jqXHR.responseText);
            }
        });
    }
    return 5;
    /*var msg = 'VMS Transaction';
     var dialog = bootbox.alert({
     title: 'Save ' + msg,
     size: 'small',
     message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
     callback: function () {
     if (shdSbmt > 0) {
     getOneVmsTrnsForm(vmsTrnsHdrID, vmsTrnsType, 34, 'ReloadDialog', tblrVwTyp, '', srcMenu,
     isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID);
     } else {
     reloadVMSTrnsLines(vmsTrnsHdrID, vmsTrnsType, 36, -1, 'Note', 0);
     }
     }
     });
     dialog.init(function () {
     getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
     });
     });*/
}

function delVmsTrnsHdr(rowIDAttrb)
{
    var msg = 'VMS Transaction';
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var trnsNum = "";
    if (typeof $('#allVmsTrnsHdrsRow' + rndmNum + '_HdrID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allVmsTrnsHdrsRow' + rndmNum + '_HdrID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        trnsNum = $.trim($tds.eq(3).text());
    }
    /*alert('#allVmsTrnsHdrsRow' + rndmNum + '_HdrID' + pKeyID + "|" + trnsNum);*/
    var dialog = bootbox.confirm({
        title: 'Delete ' + msg + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msg + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msg + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msg + '...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 1,
                                    trnsHdrID: pKeyID,
                                    trnsNum: trnsNum
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

function delVmsTrnsLine(rowIDAttrb)
{
    var msg = 'VMS Transaction Line';
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var itemName = '';
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        itemName = $('#' + rowPrfxNm + rndmNum + '_ItemNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msg + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this linked role set?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msg + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msg + '...Please Wait...</p>'
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 3,
                                    trnsLnID: pKeyID,
                                    itemName: itemName
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                            calcAllRowsTtlVals();
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
                            calcAllRowsTtlVals();
                        }, 500);
                    }
                });
            }
        }
    });
}

function delVmsPymtsLine(rowIDAttrb)
{
    var msg = 'VMS Payment Line';
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var itemName = '';
    if (typeof $('#oneVmsPymtsRow' + rndmNum + '_TrnsLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#oneVmsPymtsRow' + rndmNum + '_TrnsLnID').val();
        itemName = $('#oneVmsPymtsRow' + rndmNum + '_ItemNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msg + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this linked role set?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msg + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msg + '...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 4,
                                    trnsLnID: pKeyID,
                                    itemName: itemName
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                            calcPymtBrkdwnRowVal('oneVmsPymtsRow_1');
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
                            calcPymtBrkdwnRowVal('oneVmsPymtsRow_1');
                        }, 500);
                    }
                });
            }
        }
    });
}

function delVmsExpnsTrnsLine(rowIDAttrb)
{
    var msg = 'VMS GL Transaction Line';
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var acntName = '';
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        acntName = $('#' + rowPrfxNm + rndmNum + '_AccountNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msg + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this GL Transaction Line?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msg + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msg + '...Please Wait...</p>'
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 5,
                                    trnsLnID: pKeyID,
                                    acntName: acntName
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

function calcCshBrkdwnTryBndlRowVal(tblRowID) {
    var rndmNum = tblRowID.split("_")[1];
    var rowPrfxNm = tblRowID.split("_")[0];
    var ttlQty = 0;
    var ttlDenomVal = 0;
    var cnvFctrTry = $('#oneVmsUomCnvFctr_Tray').val();
    var cnvFctrBndl = $('#oneVmsUomCnvFctr_Bndl').val();
    var nofTrys = typeof $('#' + rowPrfxNm + rndmNum + '_Tray').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_Tray').val();
    var nofBndls = typeof $('#' + rowPrfxNm + rndmNum + '_Bndl').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_Bndl').val();
    var val = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '1' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
    ttlQty = (Number(nofTrys.replace(/[^-?0-9\.]/g, '')) * Number(cnvFctrTry.replace(/[^-?0-9\.]/g, ''))) +
            (Number(nofBndls.replace(/[^-?0-9\.]/g, '')) * Number(cnvFctrBndl.replace(/[^-?0-9\.]/g, '')));
    ttlDenomVal = (ttlQty * Number(val.replace(/[^-?0-9\.]/g, '')));
    $('#' + rowPrfxNm + rndmNum + '_Qty').val(ttlQty);
    $('#' + rowPrfxNm + rndmNum + '_TtlVal').val(addCommas(ttlDenomVal.toFixed(2)));
    calcAllRowsTtlVals();
}

function setTrysBndlVals(ttlqty, expctduom)
{
    var cnvFctrTry = typeof $('#oneVmsUomCnvFctr_Tray').val() === 'undefined' ? 1000 : $('#oneVmsUomCnvFctr_Tray').val();
    var cnvFctrBndl = typeof $('#oneVmsUomCnvFctr_Bndl').val() === 'undefined' ? 500 : $('#oneVmsUomCnvFctr_Bndl').val();
    var uoms = ["tray", "bundle", "wad", "pcs"];
    var trayCnvFctr = cnvFctrTry;
    var bndlCnvFctr = cnvFctrBndl;
    var uomCnvs = [trayCnvFctr, bndlCnvFctr, 100, 1];
    var whlPrtVal = 0;
    var rmndPrtVal = Math.abs(ttlqty);
    var whlPrt = 0;
    var rngSum = 0;
    var cnvrtdQty = 0;
    var cnvsnFctr = 1;
    var rmndPrt = 0;
    var bid = 0;
    ttlqty = Math.abs(ttlqty);
    for (var i = 0; i < uoms.length; i++) {
        cnvsnFctr = uomCnvs[i];
        if (rngSum == ttlqty) {
            cnvrtdQty = 0;
            whlPrtVal = 0;
        } else {
            if (rmndPrtVal >= cnvsnFctr) {
                whlPrt = Math.floor(rmndPrtVal / cnvsnFctr);
                rmndPrt = rmndPrtVal % cnvsnFctr;
                if (whlPrt > 0) {
                    whlPrtVal = whlPrt;
                    cnvrtdQty = whlPrtVal * cnvsnFctr;
                }
                if (rmndPrt > 0) {
                    rmndPrtVal = rmndPrt;
                }
            } else {
                cnvrtdQty = 0;
                whlPrtVal = 0;
            }
            rngSum = rngSum + cnvrtdQty;
        }
        if (uoms[i] == expctduom) {
            bid = whlPrtVal;
        }
    }
    return bid;
}

function setAfterTrysBndlVals(ttlqty, afteruom)
{
    var cnvFctrTry = typeof $('#oneVmsUomCnvFctr_Tray').val() === 'undefined' ? 1000 : $('#oneVmsUomCnvFctr_Tray').val();
    var cnvFctrBndl = typeof $('#oneVmsUomCnvFctr_Bndl').val() === 'undefined' ? 500 : $('#oneVmsUomCnvFctr_Bndl').val();
    var uoms = ["tray", "bundle", "wad", "pcs"];
    var trayCnvFctr = cnvFctrTry;
    var bndlCnvFctr = cnvFctrBndl;
    var uomCnvs = [trayCnvFctr, bndlCnvFctr, 100, 1];
    var whlPrtVal = 0;
    var rmndPrtVal = Math.abs(ttlqty);
    var whlPrt = 0;
    var rngSum = 0;
    var cnvrtdQty = 0;
    var cnvsnFctr = 1;
    var rmndPrt = 0;
    var bid = 0;
    var afteruomfnd = 0;
    ttlqty = Math.abs(ttlqty);
    for (var i = 0; i < uoms.length; i++) {
        cnvsnFctr = uomCnvs[i];
        if (rngSum == ttlqty) {
            cnvrtdQty = 0;
            whlPrtVal = 0;
        } else {
            if (rmndPrtVal >= cnvsnFctr) {
                whlPrt = Math.floor(rmndPrtVal / cnvsnFctr);
                rmndPrt = rmndPrtVal % cnvsnFctr;
                if (whlPrt > 0) {
                    whlPrtVal = whlPrt;
                    cnvrtdQty = whlPrtVal * cnvsnFctr;
                }
                if (rmndPrt > 0) {
                    rmndPrtVal = rmndPrt;
                }
            } else {
                cnvrtdQty = 0;
                whlPrtVal = 0;
            }
            rngSum = rngSum + cnvrtdQty;
        }
        if (uoms[i] == afteruom) {
            afteruomfnd = afteruomfnd + 1;
        } else if (afteruomfnd > 0) {
            bid = bid + cnvrtdQty;
        }
    }
    return bid;
}

function calcCshBrkdwnRowVal(tblRowID) {
    var rndmNum = tblRowID.split("_")[1];
    var rowPrfxNm = tblRowID.split("_")[0];
    var ttlAmount = 0;
    var ttlDenomVal = 0;
    var qty = typeof $('#' + rowPrfxNm + rndmNum + '_Qty').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_Qty').val();
    /*qty = qty.replace(/\D/g, '');*/
    if (qty.indexOf('.') !== -1) {
        qty = qty.substring(0, qty.indexOf('.'));
    }
    var val = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '1' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
    ttlDenomVal = (Number(qty.replace(/[^-?0-9\.]/g, '')) * Number(val.replace(/[^-?0-9\.]/g, '')));
    $('#' + rowPrfxNm + rndmNum + '_Qty').val(Number(qty.replace(/[^-?0-9\.]/g, '')));
    $('#' + rowPrfxNm + rndmNum + '_TtlVal').val(addCommas(ttlDenomVal.toFixed(2)));
    calcAllRowsTtlVals();
}

function calcCshBrkdwnTtlVal(tblRowID) {
    var rndmNum = tblRowID.split("_")[1];
    var rowPrfxNm = tblRowID.split("_")[0];
    var ttlAmount = 0;
    var ttlDenomQty = 0;
    var ttlCptrd = typeof $('#' + rowPrfxNm + rndmNum + '_TtlVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_TtlVal').val();
    var val = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '1' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
    ttlDenomQty = (Number(ttlCptrd.replace(/[^-?0-9\.]/g, '')) / Number(val));
    /*ttlDenomQty = (ttlDenomQty).toFixed(2);*/
    var ttlDenomQty1 = '' + ttlDenomQty;
    if (ttlDenomQty1.indexOf('.') !== -1) {
        ttlDenomQty1 = ttlDenomQty1.substring(0, ttlDenomQty1.indexOf('.'));
        ttlCptrd = '' + (Number(ttlDenomQty1.replace(/[^-?0-9\.]/g, '')) * Number(val.replace(/[^-?0-9\.]/g, '')));
        ttlDenomQty = Number(ttlDenomQty1.replace(/[^-?0-9\.]/g, ''));
    }
    $('#' + rowPrfxNm + rndmNum + '_TtlVal').val(addCommas(Number(ttlCptrd.replace(/[^-?0-9\.]/g, '')).toFixed(2)));
    $('#' + rowPrfxNm + rndmNum + '_Qty').val(ttlDenomQty);
    calcAllRowsTtlVals();
}

function calcPymtAmntTtl(tblRowID) {
    var rndmNum = tblRowID.split("_")[1];
    var rowPrfxNm = tblRowID.split("_")[0];
    var ttlAmount = 0;
    var exRate = typeof $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val() === 'undefined' ? '1' : $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val();
    $('#' + rowPrfxNm + rndmNum + '_PymntCrncyRate').val(addCommas(Number(exRate.replace(/[^-?0-9\.]/g, '')).toFixed(15)));
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var rowPrfxNm1 = tblRowID.split("_")[0];
                var ttlCptrd1 = $('#' + rowPrfxNm1 + rndmNum1 + '_TtlVal').val();
                var exRate1 = $('#' + rowPrfxNm1 + rndmNum1 + '_PymntCrncyRate').val();
                ttlAmount = ttlAmount + (Number(ttlCptrd1.replace(/[^-?0-9\.]/g, '')) * Number(exRate1.replace(/[^-?0-9\.]/g, '')));
            }
        }
    });
    $('#oneVmsTrnsCoinsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var rowPrfxNm1 = tblRowID.split("_")[0];
                var ttlCptrd1 = $('#' + rowPrfxNm1 + rndmNum1 + '_TtlVal').val();
                var exRate1 = $('#' + rowPrfxNm1 + rndmNum1 + '_PymntCrncyRate').val();
                ttlAmount = ttlAmount + (Number(ttlCptrd1.replace(/[^-?0-9\.]/g, '')) * Number(exRate1.replace(/[^-?0-9\.]/g, '')));
            }
        }
    });
    $('#myPymntValsTtlBtn').val(addCommas(ttlAmount.toFixed(2)));
    $('#myPymntValsTtlVal').val(ttlAmount.toFixed(2));
}

function calcUomBrkdwnRowVal(tblRowID) {
    var rndmNum = tblRowID.split("_")[1];
    var ttlAmount = 0;
    var ttlQty = 0;
    var ttlDenomVal = 0;
    var qty = $('#oneVmsQtyBrkRow' + rndmNum + '_BaseQty').val();
    var cnvFctr = $('#oneVmsQtyBrkRow' + rndmNum + '_CnvFctr').val();
    var val = $('#oneVmsQtyBrkRow' + rndmNum + '_UntVal').val();
    ttlDenomVal = (Number(qty.replace(/[^-?0-9\.]/g, '')) * Number(val.replace(/[^-?0-9\.]/g, '')));
    $('#oneVmsQtyBrkRow' + rndmNum + '_BaseQty').val(Number(qty.replace(/[^-?0-9\.]/g, '')));
    $('#oneVmsQtyBrkRow' + rndmNum + '_EquivQty').val((Number(qty.replace(/[^-?0-9\.]/g, '')) * cnvFctr));
    $('#oneVmsQtyBrkRow' + rndmNum + '_TtlVal').val(addCommas(ttlDenomVal.toFixed(2)));
    $('#oneVmsQtyBrkDwnTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var qty1 = $('#oneVmsQtyBrkRow' + rndmNum1 + '_BaseQty').val();
                var qty2 = $('#oneVmsQtyBrkRow' + rndmNum1 + '_EquivQty').val();
                var val1 = $('#oneVmsQtyBrkRow' + rndmNum1 + '_UntVal').val();
                ttlQty = ttlQty + Number(qty2.replace(/[^-?0-9\.]/g, ''));
                ttlAmount = ttlAmount + (Number(qty1.replace(/[^-?0-9\.]/g, '')) * Number(val1.replace(/[^-?0-9\.]/g, '')));
            }
        }
    });
    $('#myCptrdQtyTtlBtn').text(ttlQty);
    $('#myCptrdQtyTtlVal').val(ttlQty);
    var crncyNm = $('#vmsTrnsCrncyNm').val();
    $('#myCptrdUmValsTtlBtn').text(crncyNm + ' ' + addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdUmValsTtlVal').val(ttlAmount.toFixed(2));
}

function applyNewVMSQtyVal(rndmNum, modalID, tblRowID)
{
    var rowPrfxNm = tblRowID.split("_")[0];
    var qtyVal = $('#myCptrdQtyTtlVal').val();
    var ttlAmnt = $('#myCptrdUmValsTtlVal').val();
    $('#' + rowPrfxNm + rndmNum + '_Qty').val(Number(qtyVal));
    $('#' + rowPrfxNm + rndmNum + '_TtlVal').val(addCommas(Number(ttlAmnt).toFixed(2)));
    calcCshBrkdwnTtlVal('' + rowPrfxNm + '_' + rndmNum);
    calcCshBrkdwnTtlVal1('' + rowPrfxNm + '_' + rndmNum);
    $('#' + modalID).modal('hide');
}

function vmsTrnsUomFormKeyPress(event, rowIDAttrb)
{
    //alert(event.which);
    if (event.which === 13) {
        var nextItem;
        var nextItemVal = 0;
        var curItemVal = getRowIndx(rowIDAttrb, 'oneVmsQtyBrkDwnTable');
        if (curItemVal === getTtlRows('oneVmsQtyBrkDwnTable') - 1) {
            nextItem = $('.vmsUmbQty').eq(0);
        } else {
            nextItemVal = Number(curItemVal);
            nextItem = $('.vmsUmbQty').eq(nextItemVal);
        }
        nextItem.focus();
    }
}

function calcPymtBrkdwnRowVal(tblRowID) {
    var rndmNum = tblRowID.split("_")[1];
    var ttlAmount = 0;
    var ttlDenomVal = 0;
    var qty = typeof $('#oneVmsPymtsRow' + rndmNum + '_Qty').val() === 'undefined' ? '0' : $('#oneVmsPymtsRow' + rndmNum + '_Qty').val();
    var val = typeof $('#oneVmsPymtsRow' + rndmNum + '_UntVal').val() === 'undefined' ? '1' : $('#oneVmsPymtsRow' + rndmNum + '_UntVal').val();
    ttlDenomVal = (Number(qty.replace(/[^-?0-9\.]/g, '')) * Number(val.replace(/[^-?0-9\.]/g, '')));
    $('#oneVmsPymtsRow' + rndmNum + '_Qty').val(Number(qty.replace(/[^-?0-9\.]/g, '')));
    $('#oneVmsPymtsRow' + rndmNum + '_TtlVal').val(addCommas(ttlDenomVal.toFixed(2)));
    calcAllPymtRowsTtlVals();
}

function calcPymtBrkdwnTtlVal(tblRowID) {
    var rndmNum = tblRowID.split("_")[1];
    var ttlAmount = 0;
    var ttlDenomQty = 0;
    var ttlCptrd = typeof $('#oneVmsPymtsRow' + rndmNum + '_TtlVal').val() === 'undefined' ? '0' : $('#oneVmsPymtsRow' + rndmNum + '_TtlVal').val();
    var val = typeof $('#oneVmsPymtsRow' + rndmNum + '_UntVal').val() === 'undefined' ? '1' : $('#oneVmsPymtsRow' + rndmNum + '_UntVal').val();
    ttlDenomQty = (Number(ttlCptrd.replace(/[^-?0-9\.]/g, '')) / Number(val));
    var ttlDenomQty1 = '' + ttlDenomQty;
    if (ttlDenomQty1.indexOf('.') !== -1) {
        ttlDenomQty1 = ttlDenomQty1.substring(0, ttlDenomQty1.indexOf('.'));
        ttlCptrd = '' + (Number(ttlDenomQty1.replace(/[^-?0-9\.]/g, '')) * Number(val.replace(/[^-?0-9\.]/g, '')));
        ttlDenomQty = Number(ttlDenomQty1.replace(/[^-?0-9\.]/g, ''));
    }
    $('#oneVmsPymtsRow' + rndmNum + '_TtlVal').val(addCommas(Number(ttlCptrd.replace(/[^-?0-9\.]/g, '')).toFixed(2)));
    $('#oneVmsPymtsRow' + rndmNum + '_Qty').val(ttlDenomQty);
    calcAllPymtRowsTtlVals();
}


function calcPymtItemTtlVal(tstItemID, idx)
{
    var vmsTrnsType = typeof $("#vmsTrnsType").val() === 'undefined' ? '' : $("#vmsTrnsType").val();
    var ttlValue = 0;
    if (typeof idx === 'undefined') {
        idx = 1000000;
    }
    $('#oneVmsPymtsLnsTable').find('tr').each(function (i, el) {
        if (i > 0 && i <= idx)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var itemID1 = $('#oneVmsPymtsRow' + rndmNum1 + '_ItmID').val();
                var lineTrnsTyp = typeof $('#oneVmsPymtsRow' + rndmNum1 + '_LnTrnsType').val() === 'undefined' ? 'NotPresent' : $('#oneVmsPymtsRow' + rndmNum1 + '_LnTrnsType').val();
                var ttlCptrd1 = $('#oneVmsPymtsRow' + rndmNum1 + '_TtlVal').val();
                if (typeof itemID1 === 'undefined' || itemID1 === null) {

                } else if (itemID1 > 0 && tstItemID === itemID1) {
                    if (lineTrnsTyp === "Payment") {
                        ttlValue = ttlValue + Number(ttlCptrd1.replace(/[^-?0-9\.]/g, ''));
                    } else if (lineTrnsTyp === "Refund") {
                        ttlValue = ttlValue - Number(ttlCptrd1.replace(/[^-?0-9\.]/g, ''));
                    }
                    /* 
                     if (vmsTrnsType.trim() == 'Currency Sale') {}else if (vmsTrnsType.trim() == 'Currency Purchase') {
                     if (lineTrnsTyp === "Payment") {
                     ttlValue = ttlValue - Number(ttlCptrd1.replace(/[^-?0-9\.]/g, ''));
                     } else if (lineTrnsTyp === "Refund") {
                     ttlValue = ttlValue + Number(ttlCptrd1.replace(/[^-?0-9\.]/g, ''));
                     }
                     }*/
                }
            }
        }
    });
    return ttlValue;
}

function calcAllPymtRowsTtlVals() {
    var ttlAmount = 0;
    var ttlDenomQty = 0;
    var vmsTrnsType = typeof $("#vmsTrnsType").val() === 'undefined' ? '' : $("#vmsTrnsType").val();
    $('#oneVmsPymtsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var lineTrnsTyp1 = typeof $('#oneVmsPymtsRow' + rndmNum + '_LnTrnsType').val() === 'undefined' ? 'NotPresent' : $('#oneVmsPymtsRow' + rndmNum + '_LnTrnsType').val();
                var newRnngBals = 0;
                var rnngBals = 0;
                var ttlCptrd = typeof $('#oneVmsPymtsRow' + rndmNum + '_TtlVal').val() === 'undefined' ? '0' : $('#oneVmsPymtsRow' + rndmNum + '_TtlVal').val();
                var val = typeof $('#oneVmsPymtsRow' + rndmNum + '_UntVal').val() === 'undefined' ? '1' : $('#oneVmsPymtsRow' + rndmNum + '_UntVal').val();
                var itemID = typeof $('#oneVmsPymtsRow' + rndmNum + '_ItmID').val() === 'undefined' ? '000' : $('#oneVmsPymtsRow' + rndmNum + '_ItmID').val();
                ttlDenomQty = (Number(ttlCptrd.replace(/[^-?0-9\.]/g, '')) / Number(val));
                var ttlDenomQty1 = '' + ttlDenomQty;
                if (ttlDenomQty1.indexOf('.') !== -1) {
                    ttlDenomQty1 = ttlDenomQty1.substring(0, ttlDenomQty1.indexOf('.'));
                    ttlCptrd = '' + (Number(ttlDenomQty1.replace(/[^-?0-9\.]/g, '')) * Number(val.replace(/[^-?0-9\.]/g, '')));
                    ttlDenomQty = Number(ttlDenomQty1.replace(/[^-?0-9\.]/g, ''));
                }
                $('#oneVmsPymtsRow' + rndmNum + '_TtlVal').val(addCommas(Number(ttlCptrd.replace(/[^-?0-9\.]/g, '')).toFixed(2)));
                $('#oneVmsPymtsRow' + rndmNum + '_Qty').val(ttlDenomQty);

                rnngBals = typeof $('#oneVmsPymtsFrmCage_' + itemID + '').val() === 'undefined' ? '0' : $('#oneVmsPymtsFrmCage_' + itemID + '').val();
                if (vmsTrnsType.trim() == 'Currency Sale') {
                    newRnngBals = Number(rnngBals.replace(/[^-?0-9\.]/g, '')) + calcPymtItemTtlVal(itemID, i);
                } else if (vmsTrnsType.trim() == 'Currency Purchase') {
                    newRnngBals = Number(rnngBals.replace(/[^-?0-9\.]/g, '')) - calcPymtItemTtlVal(itemID, i);
                }
                $('#oneVmsPymtsRow' + rndmNum + '_RnngBal').val(addCommas(newRnngBals.toFixed(2)));
                if (lineTrnsTyp1 === "Payment") {
                    ttlAmount = ttlAmount + Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                }
                if (lineTrnsTyp1 === "Refund") {
                    ttlAmount = ttlAmount - Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                }/*
                 if (vmsTrnsType.trim() == 'Currency Sale') {
                 } else if (vmsTrnsType.trim() == 'Currency Purchase') {
                 if (lineTrnsTyp1 === "Payment") {
                 ttlAmount = ttlAmount - Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                 } else if (lineTrnsTyp1 === "Refund") {
                 ttlAmount = ttlAmount + Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                 }
                 }*/
            }
        }
    });
    var crncyNm = $('#vmsPymtFrmCrncyNm').val();
    $('#myPymntsTtlBtn').text(crncyNm + ' ' + addCommas(ttlAmount.toFixed(2)));
    $('#myPymntsTtlVal').val(ttlAmount.toFixed(2));
}


function uploadFileToVMSDocs(inptElmntID, attchIDElmntID, docNmElmntID, sbmtdHdrID, rowIDAttrb)
{
    var docCtrgrName = $('#' + docNmElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Doc. Name/Description cannot be empty!</span></p>';
    }
    if (sbmtdHdrID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Attachments must be done on a saved Document/Transaction!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    }
    $("#" + inptElmntID).val('');
    $("#" + inptElmntID).off('change');
    $("#" + inptElmntID).change(function () {
        var fileName = $(this).val();
        var input = document.getElementById(inptElmntID);
        sendFileToVMSDocs(input.files[0], docNmElmntID, attchIDElmntID, sbmtdHdrID, function (data) {
            $("#" + attchIDElmntID).val(data.attchID);
            var dialog = bootbox.alert({
                title: 'Server Response!',
                size: 'small',
                message: '<div id="myInformation">' + data.message + '</div>',
                callback: function () {
                    if (data.message.indexOf("Success") !== -1) {
                    }
                }
            });
        });
    });
    performFileClick(inptElmntID);
}

function sendFileToVMSDocs(file, docNmElmntID, attchIDElmntID, sbmtdHdrID, callBackFunc) {
    var data1 = new FormData();
    data1.append('daVMSAttchmnt', file);
    data1.append('grp', 25);
    data1.append('typ', 1);
    data1.append('pg', 2);
    data1.append('q', 'UPDATE');
    data1.append('actyp', 2);
    data1.append('docCtrgrName', $('#' + docNmElmntID).val());
    data1.append('attchmentID', $('#' + attchIDElmntID).val());
    data1.append('sbmtdVmsTrnsHdrID', sbmtdHdrID);
    var dialog1 = bootbox.alert({
        title: 'Uploading File...',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Uploading File...Please Wait...</p>'
    });
    dialog1.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                type: 'POST',
                data: data1,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    dialog1.modal('hide');
                    callBackFunc(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function getAttchdVMSDocs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#attchdVMSDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdVMSDocsSrchFor").val();
    var srchIn = typeof $("#attchdVMSDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdVMSDocsSrchIn").val();
    var pageNo = typeof $("#attchdVMSDocsPageNo").val() === 'undefined' ? 1 : $("#attchdVMSDocsPageNo").val();
    var limitSze = typeof $("#attchdVMSDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdVMSDocsDsplySze").val();
    var sortBy = typeof $("#attchdVMSDocsSortBy").val() === 'undefined' ? '' : $("#attchdVMSDocsSortBy").val();
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
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs, 'myFormsModaly', 'ShowDialog', 'VMS Trns. Attached Documents', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        if (!$.fn.DataTable.isDataTable('#attchdVMSDocsTable')) {
            var table1 = $('#attchdVMSDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdVMSDocsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdVMSDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdVMSDocs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdVMSDocs(actionText, slctr, linkArgs);
    }
}

function delAttchdVMSDoc(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID = typeof $("#sbmtdHdrID").val() === 'undefined' ? -1 : $("#sbmtdHdrID").val();
    var docNum = typeof $("#vmsTrnsNum").val() === 'undefined' ? '' : $("#vmsTrnsNum").val();
    var pKeyID = -1;
    if (typeof $('#attchdVMSDocsRow' + rndmNum + '_AttchdVMSDocsID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#attchdVMSDocsRow' + rndmNum + '_AttchdVMSDocsID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Document?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Document?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Document?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Document...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 2,
                                    attchmentID: pKeyID,
                                    sbmtdHdrID: sbmtdHdrID,
                                    docTrnsNum: docNum
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

function clearVMSTrnsRows(tblID)
{
    clearTblRows(tblID);
    var crncyNm = $('#vmsTrnsCrncyNm').val();
    $('#myCptrdValsTtlBtn').text(crncyNm + ' 0.00');
    $('#myCptrdValsTtlVal').val(0);
}

function insertNewVMSTrnsRows(tableElmntID, position, inptHtml)
{
    insertNewRowBe4(tableElmntID, position, inptHtml, function () {
        $('[data-toggle="tooltip"]').tooltip();
        $(".vmsCbTtl").focus(function () {
            $(this).select();
        });
        $(".vmsCbQty").focus(function () {
            $(this).select();
        });
        $(".vmsFncCrncy").focus(function () {
            $(this).select();
        });
        $(".vmsCbRnngBal").focus(function () {
            $(this).select();
        });
        $(".vmsCbBndl").focus(function () {
            $(this).select();
        });
        $(".vmsCbTray").focus(function () {
            $(this).select();
        });
    });
}

function insertNewVmsExpnsTrnsRows(tableElmntID, position, inptHtml)
{
    for (var i = 0; i < 5; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1)
        {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID).append(nwInptHtml);
            }
        } else {
            $('#' + tableElmntID).append(nwInptHtml);
        }
    }

    $('[data-toggle="tooltip"]').tooltip();
    $(".vmsExpTtl").focus(function () {
        $(this).select();
    });
    $(".vmsExpRt").focus(function () {
        $(this).select();
    });
    var cntr = 0;
    $('#' + tableElmntID).find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                cntr++;
                var prfxName1 = $(el).attr('id').split("_")[0];
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var $tds = $("#" + prfxName1 + "_" + rndmNum1).find('td');
                //alert($tds.eq(0).text()+cntr);
                $tds.eq(0).html(cntr);
                //alert($tds.eq(0).text()+cntr);
            }
        }
    });
}

function afterVMSItemSlctn(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var dstVltID = $('#vmsDfltDestVltID').val();
    var dstCageID = $('#vmsDfltDestCageID').val();
    var dstCageNm = $('#vmsDfltDestCage').val();
    var srcVltID = $('#vmsDfltSrcVltID').val();
    var srcCageID = $('#vmsDfltSrcCageID').val();
    var srcCageNm = $('#vmsDfltSrcCage').val();
    var nwItmID = $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
    var lnDstCageID = $('#' + rowPrfxNm + rndmNum + '_DstCageID').val();
    var lnDstItmState = $('#' + rowPrfxNm + rndmNum + '_DstItemState').val();
    var lnSrcCageID = $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val();
    var lnSrcItmState = $('#' + rowPrfxNm + rndmNum + '_SrcItemState').val();
    if (lnDstCageID <= 0 && lnSrcCageID <= 0 && dstCageID > 0) {
        $('#' + rowPrfxNm + rndmNum + '_DstCageID').val(dstCageID);
        $('#' + rowPrfxNm + rndmNum + '_DstCageNm').val(dstCageNm);
        lnDstCageID = dstCageID;
    }
    if (lnDstCageID <= 0 && lnSrcCageID <= 0 && srcCageID > 0) {
        $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val(srcCageID);
        $('#' + rowPrfxNm + rndmNum + '_SrcCageNm').val(srcCageNm);
        lnSrcCageID = srcCageID;
    }
    if (Number(nwItmID) > 0) {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append('grp', 25);
            formData.append('typ', 1);
            formData.append('pg', 2);
            formData.append('q', 'VIEW');
            formData.append('vtyp', 26);
            formData.append('sbmtdItemID', nwItmID);
            formData.append('dstVltID', dstVltID);
            formData.append('lnDstCageID', lnDstCageID);
            formData.append('srcVltID', srcVltID);
            formData.append('lnSrcCageID', lnSrcCageID);
            formData.append('lnDstItmState', lnDstItmState);
            formData.append('lnSrcItmState', lnSrcItmState);
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
                        var qty = (typeof $('#' + rowPrfxNm + rndmNum + '_Qty').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_Qty').val()) + ',';
                        $('#' + rowPrfxNm + rndmNum + '_UntVal').val(data.UnitVal);
                        $('#' + rowPrfxNm + rndmNum + '_BaseUoMID').val(data.BaseUoMID);
                        $('#' + rowPrfxNm + rndmNum + '_TtlVal').val(addCommas(Number(qty.replace(/[^-?0-9\.]/g, '')) * Number(data.UnitVal)));
                        $('#' + rowPrfxNm + rndmNum + '_DstRnngBal').val(addCommas((Number(data.DstBalsB4) + (Number(qty.replace(/[^-?0-9\.]/g, '')) * Number(data.UnitVal))).toFixed(2)));
                        if (typeof $('#oneVmsTrnsFrmSrcCage_' + nwItmID + '_' + lnSrcCageID).val() === 'undefined' || $('#oneVmsTrnsFrmSrcCage_' + nwItmID + '_' + lnSrcCageID).val() === null) {
                            $("<input type='hidden' value='' />")
                                    .attr("id", 'oneVmsTrnsFrmSrcCage_' + nwItmID + '_' + lnSrcCageID)
                                    .attr("name", 'oneVmsTrnsFrmSrcCage_' + nwItmID + '_' + lnSrcCageID)
                                    .appendTo("#oneVmsTrnsEDTForm");
                        }

                        if (typeof $('#oneVmsTrnsFrmDstCage_' + nwItmID + '_' + lnDstCageID).val() === 'undefined' || $('#oneVmsTrnsFrmDstCage_' + nwItmID + '_' + lnDstCageID).val() === null) {
                            $("<input type='hidden' value='' />")
                                    .attr("id", 'oneVmsTrnsFrmDstCage_' + nwItmID + '_' + lnDstCageID)
                                    .attr("name", 'oneVmsTrnsFrmDstCage_' + nwItmID + '_' + lnDstCageID)
                                    .appendTo("#oneVmsTrnsEDTForm");
                        }
                        $('#oneVmsTrnsFrmDstCage_' + nwItmID + '_' + lnDstCageID).val(data.DstBalsB4);
                        $('#' + rowPrfxNm + rndmNum + '_DstItemState').val(data.DstItmState);
                        $('#' + rowPrfxNm + rndmNum + '_SrcRnngBal').val(addCommas((Number(data.SrcBalsB4) - (Number(qty.replace(/[^-?0-9\.]/g, '')) * Number(data.UnitVal))).toFixed(2)));
                        $('#oneVmsTrnsFrmSrcCage_' + nwItmID + '_' + lnSrcCageID).val(data.SrcBalsB4);

                        $('#' + rowPrfxNm + rndmNum + '_SrcItemState').val(data.SrcItmState);
                        calcCshBrkdwnRowVal(rowIDAttrb);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.warn(jqXHR.responseText);
                }
            });
        });
    } else {
        $('#' + rowPrfxNm + rndmNum + '_ItmID').val(-1);
        $('#' + rowPrfxNm + rndmNum + '_ItemNm').val('');
        $('#' + rowPrfxNm + rndmNum + '_Qty').val(0.00);
        $('#' + rowPrfxNm + rndmNum + '_UntVal').val(1.00);
        $('#' + rowPrfxNm + rndmNum + '_TtlVal').val(0.00);
        calcCshBrkdwnRowVal(rowIDAttrb);
    }
}

function afterVMSItemSlctn1(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];

    var crntVltID = $('#vmsDfltDestVltID').val();
    var crntCageID = $('#vmsDfltDestCageID').val();
    var nwItmID = $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
    if (Number(nwItmID) > 0) {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append('grp', 25);
            formData.append('typ', 1);
            formData.append('pg', 2);
            formData.append('q', 'VIEW');
            formData.append('vtyp', 42);
            formData.append('sbmtdItemID', nwItmID);
            formData.append('crntVltID', crntVltID);
            formData.append('crntCageID', crntCageID);
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
                        createRnngBal1Flds();
                        var qty = $('#' + rowPrfxNm + rndmNum + '_Qty').val() + ',';
                        var lnType = $('#' + rowPrfxNm + rndmNum + '_LnTrnsType').val();
                        $('#' + rowPrfxNm + rndmNum + '_UntVal').val(data.UnitVal);
                        $('#' + rowPrfxNm + rndmNum + '_BaseUoMID').val(data.BaseUoMID);
                        $('#' + rowPrfxNm + rndmNum + '_TtlVal').val(addCommas(Number(qty.replace(/[^-?0-9\.]/g, '')) * Number(data.UnitVal)));
                        if (lnType === 'Receipts') {
                            $('#' + rowPrfxNm + rndmNum + '_RnngBal').val(addCommas((Number(data.BalsB4) + (Number(qty.replace(/[^-?0-9\.]/g, '')) * Number(data.UnitVal))).toFixed(2)));
                        } else if (lnType === 'Issues') {
                            $('#' + rowPrfxNm + rndmNum + '_RnngBal').val(addCommas((Number(data.BalsB4) - (Number(qty.replace(/[^-?0-9\.]/g, '')) * Number(data.UnitVal))).toFixed(2)));
                        }
                        if (typeof $('#oneVmsTrnsFrmItm_' + nwItmID + '').val() === 'undefined' || $('#oneVmsTrnsFrmItm_' + nwItmID + '').val() === null) {
                            $("<input type='hidden' value='' />")
                                    .attr("id", 'oneVmsTrnsFrmItm_' + nwItmID + '')
                                    .attr("name", 'oneVmsTrnsFrmItm_' + nwItmID + '')
                                    .appendTo("#oneDirectVmsTrnsFrm");
                        }
                        $('#oneVmsTrnsFrmItm_' + nwItmID + '').val(Number(data.BalsB4));
                        calcCshBrkdwnRowVal1(rowIDAttrb);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.warn(jqXHR.responseText);
                }
            });
        });
    } else {
        $('#' + rowPrfxNm + rndmNum + '_ItmID').val(-1);
        $('#' + rowPrfxNm + rndmNum + '_ItemNm').val('');
        $('#' + rowPrfxNm + rndmNum + '_Qty').val(0.00);
        $('#' + rowPrfxNm + rndmNum + '_UntVal').val(1.00);
        $('#' + rowPrfxNm + rndmNum + '_TtlVal').val(0.00);
        $('#' + rowPrfxNm + rndmNum + '_RnngBal').val(0.00);
        $('#oneVmsTrnsFrmItm_' + nwItmID + '').val(0.00);
        calcCshBrkdwnRowVal(rowIDAttrb);
    }
}

function calcCshBrkdwnRowVal1(tblRowID) {
    var rndmNum = tblRowID.split("_")[1];
    var rowPrfxNm = tblRowID.split("_")[0];

    var ttlDenomVal = 0;
    var qty = typeof $('#' + rowPrfxNm + rndmNum + '_Qty').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_Qty').val();
    var val = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '1' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
    ttlDenomVal = (Number(qty.replace(/[^-?0-9\.]/g, '')) * Number(val.replace(/[^-?0-9\.]/g, '')));
    $('#' + rowPrfxNm + rndmNum + '_Qty').val(Number(qty.replace(/[^-?0-9\.]/g, '')).toFixed(2));
    $('#' + rowPrfxNm + rndmNum + '_TtlVal').val(addCommas(ttlDenomVal.toFixed(2)));
    calcAllRowsTtlVals();
}

function calcCshBrkdwnTtlVal1(tblRowID) {
    var rndmNum = tblRowID.split("_")[1];
    var rowPrfxNm = tblRowID.split("_")[0];

    var ttlDenomQty = 0;
    var ttlCptrd = typeof $('#' + rowPrfxNm + rndmNum + '_TtlVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_TtlVal').val();
    var val = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '1' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
    ttlDenomQty = (Number(ttlCptrd.replace(/[^-?0-9\.]/g, '')) / Number(val)).toFixed(2);

    var ttlDenomQty1 = '' + ttlDenomQty;
    if (ttlDenomQty1.indexOf('.') !== -1) {
        ttlDenomQty1 = ttlDenomQty1.substring(0, ttlDenomQty1.indexOf('.'));
        ttlCptrd = '' + (Number(ttlDenomQty1.replace(/[^-?0-9\.]/g, '')) * Number(val.replace(/[^-?0-9\.]/g, '')));
        ttlDenomQty = Number(ttlDenomQty1.replace(/[^-?0-9\.]/g, ''));
    }
    $('#' + rowPrfxNm + rndmNum + '_TtlVal').val(addCommas(Number(ttlCptrd.replace(/[^-?0-9\.]/g, '')).toFixed(2)));
    $('#' + rowPrfxNm + rndmNum + '_Qty').val(ttlDenomQty);
    calcAllRowsTtlVals();
}

function createRnngBal1Flds()
{
    var dstVltID = $('#vmsDfltDestVltID').val();
    var dstCageID = $('#vmsDfltDestCageID').val();
    var dstCageNm = $('#vmsDfltDestCage').val();
    var srcVltID = $('#vmsDfltSrcVltID').val();
    var srcCageID = $('#vmsDfltSrcCageID').val();
    var srcCageNm = $('#vmsDfltSrcCage').val();
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                var itemID1 = $('#' + rowPrfxNm + rndmNum1 + '_ItmID').val();
                var lnDstCageID = $('#' + rowPrfxNm + rndmNum1 + '_DstCageID').val();
                var lnDstItmState = $('#' + rowPrfxNm + rndmNum1 + '_DstItemState').val();
                var lnSrcCageID = $('#' + rowPrfxNm + rndmNum1 + '_SrcCageID').val();
                var lnSrcItmState = $('#' + rowPrfxNm + rndmNum1 + '_SrcItemState').val();
                if (lnSrcCageID <= 0 && lnDstCageID <= 0 && dstCageID > 0) {
                    $('#' + rowPrfxNm + rndmNum1 + '_DstCageID').val(dstCageID);
                    $('#' + rowPrfxNm + rndmNum1 + '_DstCageNm').val(dstCageNm);
                    lnDstCageID = dstCageID;
                }
                if (lnSrcCageID <= 0 && lnDstCageID <= 0 && srcCageID > 0) {
                    $('#' + rowPrfxNm + rndmNum1 + '_SrcCageID').val(srcCageID);
                    $('#' + rowPrfxNm + rndmNum1 + '_SrcCageNm').val(srcCageNm);
                    lnSrcCageID = srcCageID;
                }
                if (typeof itemID1 === 'undefined' || itemID1 === null) {

                } else if (itemID1 > 0) {
                    var lineTrnsTyp = typeof $('#' + rowPrfxNm + rndmNum1 + '_LnTrnsType').val() === 'undefined' ? 'NotPresent' : $('#' + rowPrfxNm + rndmNum1 + '_LnTrnsType').val();
                    if (lineTrnsTyp === 'NotPresent') {
                        if (typeof $('#oneVmsTrnsFrmSrcCage_' + itemID1 + '_' + lnSrcCageID).val() === 'undefined' || $('#oneVmsTrnsFrmSrcCage_' + itemID1 + '_' + lnSrcCageID).val() === null) {
                            $("<input type='hidden' value='' />")
                                    .attr("id", 'oneVmsTrnsFrmSrcCage_' + itemID1 + '_' + lnSrcCageID)
                                    .attr("name", 'oneVmsTrnsFrmSrcCage_' + itemID1 + '_' + lnSrcCageID)
                                    .appendTo("#oneVmsTrnsEDTForm");
                            $("<input type='hidden' value='' />")
                                    .attr("id", 'oneVmsTrnsFrmDstCage_' + itemID1 + '_' + lnDstCageID)
                                    .attr("name", 'oneVmsTrnsFrmDstCage_' + itemID1 + '_' + lnDstCageID)
                                    .appendTo("#oneVmsTrnsEDTForm");
                        } else if (typeof $('#oneVmsTrnsFrmSrcCage1_' + itemID1 + '_' + lnSrcCageID).val() === 'undefined' || $('#oneVmsTrnsFrmSrcCage1_' + itemID1 + '_' + lnSrcCageID).val() === null) {
                            /*Do Nothing*/
                        } else {
                            $('#oneVmsTrnsFrmSrcCage_' + itemID1 + '_' + lnSrcCageID).val($('#oneVmsTrnsFrmSrcCage1_' + itemID1 + '_' + lnSrcCageID).val());
                            $('#oneVmsTrnsFrmDstCage_' + itemID1 + '_' + lnDstCageID).val($('#oneVmsTrnsFrmDstCage1_' + itemID1 + '_' + lnDstCageID).val());
                        }
                    } else {
                        if (typeof $('#oneVmsTrnsFrmItm_' + itemID1 + '').val() === 'undefined' || $('#oneVmsTrnsFrmItm_' + itemID1 + '').val() === null) {
                            $("<input type='hidden' value='' />")
                                    .attr("id", 'oneVmsTrnsFrmItm_' + itemID1 + '')
                                    .attr("name", 'oneVmsTrnsFrmItm_' + itemID1 + '')
                                    .appendTo("#oneDirectVmsTrnsFrm");
                        } else if (typeof $('#oneVmsTrnsFrmItm1_' + itemID1 + '').val() === 'undefined' || $('#oneVmsTrnsFrmItm1_' + itemID1 + '').val() === null) {
                            /*Do Nothing*/
                        } else {
                            $('#oneVmsTrnsFrmItm_' + itemID1 + '').val($('#oneVmsTrnsFrmItm1_' + itemID1 + '').val());
                        }
                    }
                }
            }
        }
    });
    $('#oneVmsTrnsCoinsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                var itemID1 = $('#' + rowPrfxNm + rndmNum1 + '_ItmID').val();
                var lnDstCageID = $('#' + rowPrfxNm + rndmNum1 + '_DstCageID').val();
                var lnDstItmState = $('#' + rowPrfxNm + rndmNum1 + '_DstItemState').val();
                var lnSrcCageID = $('#' + rowPrfxNm + rndmNum1 + '_SrcCageID').val();
                var lnSrcItmState = $('#' + rowPrfxNm + rndmNum1 + '_SrcItemState').val();
                if (lnSrcCageID <= 0 && lnDstCageID <= 0 && dstCageID > 0) {
                    $('#' + rowPrfxNm + rndmNum1 + '_DstCageID').val(dstCageID);
                    $('#' + rowPrfxNm + rndmNum1 + '_DstCageNm').val(dstCageNm);
                    lnDstCageID = dstCageID;
                }
                if (lnSrcCageID <= 0 && lnDstCageID <= 0 && srcCageID > 0) {
                    $('#' + rowPrfxNm + rndmNum1 + '_SrcCageID').val(srcCageID);
                    $('#' + rowPrfxNm + rndmNum1 + '_SrcCageNm').val(srcCageNm);
                    lnSrcCageID = srcCageID;
                }
                if (typeof itemID1 === 'undefined' || itemID1 === null) {

                } else if (itemID1 > 0) {
                    var lineTrnsTyp = typeof $('#' + rowPrfxNm + rndmNum1 + '_LnTrnsType').val() === 'undefined' ? 'NotPresent' : $('#' + rowPrfxNm + rndmNum1 + '_LnTrnsType').val();
                    if (lineTrnsTyp === 'NotPresent') {
                        if (typeof $('#oneVmsTrnsFrmSrcCage_' + itemID1 + '_' + lnSrcCageID).val() === 'undefined' || $('#oneVmsTrnsFrmSrcCage_' + itemID1 + '_' + lnSrcCageID).val() === null) {
                            $("<input type='hidden' value='' />")
                                    .attr("id", 'oneVmsTrnsFrmSrcCage_' + itemID1 + '_' + lnSrcCageID)
                                    .attr("name", 'oneVmsTrnsFrmSrcCage_' + itemID1 + '_' + lnSrcCageID)
                                    .appendTo("#oneVmsTrnsEDTForm");
                            $("<input type='hidden' value='' />")
                                    .attr("id", 'oneVmsTrnsFrmDstCage_' + itemID1 + '_' + lnDstCageID)
                                    .attr("name", 'oneVmsTrnsFrmDstCage_' + itemID1 + '_' + lnDstCageID)
                                    .appendTo("#oneVmsTrnsEDTForm");
                        } else if (typeof $('#oneVmsTrnsFrmSrcCage1_' + itemID1 + '_' + lnSrcCageID).val() === 'undefined' || $('#oneVmsTrnsFrmSrcCage1_' + itemID1 + '_' + lnSrcCageID).val() === null) {
                            /*Do Nothing*/
                        } else {
                            $('#oneVmsTrnsFrmSrcCage_' + itemID1 + '_' + lnSrcCageID).val($('#oneVmsTrnsFrmSrcCage1_' + itemID1 + '_' + lnSrcCageID).val());
                            $('#oneVmsTrnsFrmDstCage_' + itemID1 + '_' + lnDstCageID).val($('#oneVmsTrnsFrmDstCage1_' + itemID1 + '_' + lnDstCageID).val());
                        }
                    } else {
                        if (typeof $('#oneVmsTrnsFrmItm_' + itemID1 + '').val() === 'undefined' || $('#oneVmsTrnsFrmItm_' + itemID1 + '').val() === null) {
                            $("<input type='hidden' value='' />")
                                    .attr("id", 'oneVmsTrnsFrmItm_' + itemID1 + '')
                                    .attr("name", 'oneVmsTrnsFrmItm_' + itemID1 + '')
                                    .appendTo("#oneDirectVmsTrnsFrm");
                        } else if (typeof $('#oneVmsTrnsFrmItm1_' + itemID1 + '').val() === 'undefined' || $('#oneVmsTrnsFrmItm1_' + itemID1 + '').val() === null) {
                            /*Do Nothing*/
                        } else {
                            $('#oneVmsTrnsFrmItm_' + itemID1 + '').val($('#oneVmsTrnsFrmItm1_' + itemID1 + '').val());
                        }
                    }
                }
            }
        }
    });
}

function calcItemTtlVal(tstItemID, idx, lineCgID)
{
    var ttlValue = 0;
    if (typeof idx === 'undefined') {
        idx = 1000000;
    }
    if (typeof lineCgID === 'undefined') {
        lineCgID = -1;
    }
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0 && i <= idx)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                var itemID1 = $('#' + rowPrfxNm + rndmNum1 + '_ItmID').val();
                var lineTrnsTyp = typeof $('#' + rowPrfxNm + rndmNum1 + '_LnTrnsType').val() === 'undefined' ? 'NotPresent' : $('#' + rowPrfxNm + rndmNum1 + '_LnTrnsType').val();
                var ttlCptrd1 = $('#' + rowPrfxNm + rndmNum1 + '_TtlVal').val();
                var lineDstCgID = typeof $('#' + rowPrfxNm + rndmNum1 + '_DstCageID').val() === 'undefined' ? -1 : $('#' + rowPrfxNm + rndmNum1 + '_DstCageID').val();
                if (typeof itemID1 === 'undefined' || itemID1 === null) {

                } else if (itemID1 > 0 && tstItemID === itemID1) {
                    if (lineTrnsTyp === 'NotPresent' && lineDstCgID > 0 && lineCgID == lineDstCgID) {
                        ttlValue = ttlValue + Number(ttlCptrd1.replace(/[^-?0-9\.]/g, ''));
                    } else if (lineTrnsTyp === "Receipts") {
                        ttlValue = ttlValue + Number(ttlCptrd1.replace(/[^-?0-9\.]/g, ''));
                    } else if (lineTrnsTyp === "Issues") {
                        ttlValue = ttlValue - Number(ttlCptrd1.replace(/[^-?0-9\.]/g, ''));
                    }
                }
            }
        }
    });
    $('#oneVmsTrnsCoinsLnsTable').find('tr').each(function (i, el) {
        if (i > 0 && i <= idx)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                var itemID1 = $('#' + rowPrfxNm + rndmNum1 + '_ItmID').val();
                var lineTrnsTyp = typeof $('#' + rowPrfxNm + rndmNum1 + '_LnTrnsType').val() === 'undefined' ? 'NotPresent' : $('#' + rowPrfxNm + rndmNum1 + '_LnTrnsType').val();
                var ttlCptrd1 = $('#' + rowPrfxNm + rndmNum1 + '_TtlVal').val();
                var lineDstCgID = typeof $('#' + rowPrfxNm + rndmNum1 + '_DstCageID').val() === 'undefined' ? -1 : $('#' + rowPrfxNm + rndmNum1 + '_DstCageID').val();
                if (typeof itemID1 === 'undefined' || itemID1 === null) {

                } else if (itemID1 > 0 && tstItemID === itemID1) {
                    if (lineTrnsTyp === 'NotPresent' && lineDstCgID > 0 && lineCgID == lineDstCgID) {
                        ttlValue = ttlValue + Number(ttlCptrd1.replace(/[^-?0-9\.]/g, ''));
                    } else if (lineTrnsTyp === "Receipts") {
                        ttlValue = ttlValue + Number(ttlCptrd1.replace(/[^-?0-9\.]/g, ''));
                    } else if (lineTrnsTyp === "Issues") {
                        ttlValue = ttlValue - Number(ttlCptrd1.replace(/[^-?0-9\.]/g, ''));
                    }
                }
            }
        }
    });
    return ttlValue;
}

function calcItemTtlValIssues(tstItemID, idx, lineCgID)
{
    var ttlValue = 0;
    if (typeof idx === 'undefined') {
        idx = 1000000;
    }
    if (typeof lineCgID === 'undefined') {
        lineCgID = -1;
    }
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0 && i <= idx)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                var itemID1 = $('#' + rowPrfxNm + rndmNum1 + '_ItmID').val();
                var lineTrnsTyp = typeof $('#' + rowPrfxNm + rndmNum1 + '_LnTrnsType').val() === 'undefined' ? 'NotPresent' : $('#' + rowPrfxNm + rndmNum1 + '_LnTrnsType').val();
                var lineSrcCgID = typeof $('#' + rowPrfxNm + rndmNum1 + '_SrcCageID').val() === 'undefined' ? -1 : $('#' + rowPrfxNm + rndmNum1 + '_SrcCageID').val();
                var ttlCptrd1 = $('#' + rowPrfxNm + rndmNum1 + '_TtlVal').val();
                if (typeof itemID1 === 'undefined' || itemID1 === null) {

                } else if (itemID1 > 0 && tstItemID === itemID1) {
                    if (lineTrnsTyp === 'NotPresent' && lineSrcCgID > 0 && lineCgID == lineSrcCgID) {
                        ttlValue = ttlValue - Number(ttlCptrd1.replace(/[^-?0-9\.]/g, ''));
                    }
                }
            }
        }
    });
    $('#oneVmsTrnsCoinsLnsTable').find('tr').each(function (i, el) {
        if (i > 0 && i <= idx)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                var itemID1 = $('#' + rowPrfxNm + rndmNum1 + '_ItmID').val();
                var lineTrnsTyp = typeof $('#' + rowPrfxNm + rndmNum1 + '_LnTrnsType').val() === 'undefined' ? 'NotPresent' : $('#' + rowPrfxNm + rndmNum1 + '_LnTrnsType').val();
                var lineSrcCgID = typeof $('#' + rowPrfxNm + rndmNum1 + '_SrcCageID').val() === 'undefined' ? -1 : $('#' + rowPrfxNm + rndmNum1 + '_SrcCageID').val();
                var ttlCptrd1 = $('#' + rowPrfxNm + rndmNum1 + '_TtlVal').val();
                if (typeof itemID1 === 'undefined' || itemID1 === null) {

                } else if (itemID1 > 0 && tstItemID === itemID1) {
                    if (lineTrnsTyp === 'NotPresent' && lineSrcCgID > 0 && lineCgID == lineSrcCgID) {
                        ttlValue = ttlValue - Number(ttlCptrd1.replace(/[^-?0-9\.]/g, ''));
                    }
                }
            }
        }
    });
    return ttlValue;
}

function calcAllRowsTtlVals() {
    var ttlAmount = 0;
    var ttlDenomQty = 0;
    var vmsTrnsType = typeof $('#vmsTrnsType').val() === 'undefined' ? '' : $('#vmsTrnsType').val();
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                var lineTrnsTyp1 = typeof $('#' + rowPrfxNm + rndmNum + '_LnTrnsType').val() === 'undefined' ? 'NotPresent' : $('#' + rowPrfxNm + rndmNum + '_LnTrnsType').val();
                var lineSrcCgID = typeof $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val() === 'undefined' ? -1 : $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val();
                var lineDstCgID = typeof $('#' + rowPrfxNm + rndmNum + '_DstCageID').val() === 'undefined' ? -1 : $('#' + rowPrfxNm + rndmNum + '_DstCageID').val();
                var newRnngBals = 0;
                var rnngBals = 0;
                var ttlCptrd = typeof $('#' + rowPrfxNm + rndmNum + '_TtlVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_TtlVal').val();
                var val = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '1' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
                var itemID = typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined' ? '000' : $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
                ttlDenomQty = (Number(ttlCptrd.replace(/[^-?0-9\.]/g, '')) / Number(val)).toFixed(2);

                var ttlDenomQty1 = '' + ttlDenomQty;
                if (ttlDenomQty1.indexOf('.') !== -1) {
                    ttlDenomQty1 = ttlDenomQty1.substring(0, ttlDenomQty1.indexOf('.'));
                    ttlCptrd = '' + (Number(ttlDenomQty1.replace(/[^-?0-9\.]/g, '')) * Number(val.replace(/[^-?0-9\.]/g, '')));
                    ttlDenomQty = Number(ttlDenomQty1.replace(/[^-?0-9\.]/g, ''));
                }
                $('#' + rowPrfxNm + rndmNum + '_TtlVal').val(addCommas(Number(ttlCptrd.replace(/[^-?0-9\.]/g, '')).toFixed(2)));
                $('#' + rowPrfxNm + rndmNum + '_Qty').val(ttlDenomQty);
                $('#' + rowPrfxNm + rndmNum + '_Tray').val(setTrysBndlVals(ttlDenomQty, "tray"));
                $('#' + rowPrfxNm + rndmNum + '_Bndl').val(setTrysBndlVals(ttlDenomQty, "bundle"));
                if (lineTrnsTyp1 === 'NotPresent') {
                    if (lineSrcCgID > 0) {
                        rnngBals = typeof $('#oneVmsTrnsFrmSrcCage_' + itemID + '_' + lineSrcCgID).val() === 'undefined' ? '0' : $('#oneVmsTrnsFrmSrcCage_' + itemID + '_' + lineSrcCgID).val();
                        newRnngBals = Number(rnngBals.replace(/[^-?0-9\.]/g, '')) + calcItemTtlVal(itemID, i, lineSrcCgID) + calcItemTtlValIssues(itemID, i, lineSrcCgID);
                        $('#' + rowPrfxNm + rndmNum + '_SrcRnngBal').val(addCommas(newRnngBals.toFixed(2)));
                    }
                    if (lineDstCgID > 0) {
                        rnngBals = typeof $('#oneVmsTrnsFrmDstCage_' + itemID + '_' + lineDstCgID).val() === 'undefined' ? '0' : $('#oneVmsTrnsFrmDstCage_' + itemID + '_' + lineDstCgID).val();
                        newRnngBals = Number(rnngBals.replace(/[^-?0-9\.]/g, '')) + calcItemTtlVal(itemID, i, lineDstCgID) + calcItemTtlValIssues(itemID, i, lineDstCgID);
                        $('#' + rowPrfxNm + rndmNum + '_DstRnngBal').val(addCommas(newRnngBals.toFixed(2)));
                    }
                } else {
                    rnngBals = typeof $('#oneVmsTrnsFrmItm_' + itemID + '').val() === 'undefined' ? '0' : $('#oneVmsTrnsFrmItm_' + itemID + '').val();
                    newRnngBals = Number(rnngBals.replace(/[^-?0-9\.]/g, '')) + calcItemTtlVal(itemID, i);
                    $('#' + rowPrfxNm + rndmNum + '_RnngBal').val(addCommas(newRnngBals.toFixed(2)));
                }
                if (lineTrnsTyp1 === "Receipts" || lineDstCgID > 0) {
                    if (vmsTrnsType.trim() == 'Currency Sale' || vmsTrnsType.trim() == 'Withdrawals' || vmsTrnsType.trim() == 'Currency Destruction' || vmsTrnsType.trim() == 'Vault/GL Account Transfers') {
                        ttlAmount = ttlAmount - Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                    } else {
                        ttlAmount = ttlAmount + Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                    }
                }
                if (lineTrnsTyp1 === "Issues" || lineSrcCgID > 0) {
                    if (vmsTrnsType.trim() == 'Currency Sale' || vmsTrnsType.trim() == 'Withdrawals' || vmsTrnsType.trim() == 'Currency Destruction' || vmsTrnsType.trim() == 'Vault/GL Account Transfers') {
                        ttlAmount = ttlAmount + Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                    } else {
                        ttlAmount = ttlAmount - Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                    }
                }
                if (lineDstCgID > 0 && lineSrcCgID > 0 && lineSrcCgID !== lineDstCgID && lineTrnsTyp1 === 'NotPresent') {
                    ttlAmount = ttlAmount + Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                }
                if (lineDstCgID <= 0 && lineSrcCgID <= 0 && lineTrnsTyp1 === 'NotPresent') {
                    ttlAmount = ttlAmount + Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                }
            }
        }
    });
    //alert(ttlAmount);
    $('#oneVmsTrnsCoinsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                var lineTrnsTyp1 = typeof $('#' + rowPrfxNm + rndmNum + '_LnTrnsType').val() === 'undefined' ? 'NotPresent' : $('#' + rowPrfxNm + rndmNum + '_LnTrnsType').val();
                var lineSrcCgID = typeof $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val() === 'undefined' ? -1 : $('#' + rowPrfxNm + rndmNum + '_SrcCageID').val();
                var lineDstCgID = typeof $('#' + rowPrfxNm + rndmNum + '_DstCageID').val() === 'undefined' ? -1 : $('#' + rowPrfxNm + rndmNum + '_DstCageID').val();
                var newRnngBals = 0;
                var rnngBals = 0;
                var ttlCptrd = typeof $('#' + rowPrfxNm + rndmNum + '_TtlVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_TtlVal').val();
                var val = typeof $('#' + rowPrfxNm + rndmNum + '_UntVal').val() === 'undefined' ? '1' : $('#' + rowPrfxNm + rndmNum + '_UntVal').val();
                var itemID = typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined' ? '000' : $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
                ttlDenomQty = (Number(ttlCptrd.replace(/[^-?0-9\.]/g, '')) / Number(val)).toFixed(2);
                var ttlDenomQty1 = '' + ttlDenomQty;
                if (ttlDenomQty1.indexOf('.') !== -1) {
                    ttlDenomQty1 = ttlDenomQty1.substring(0, ttlDenomQty1.indexOf('.'));
                    ttlCptrd = '' + (Number(ttlDenomQty1.replace(/[^-?0-9\.]/g, '')) * Number(val.replace(/[^-?0-9\.]/g, '')));
                    ttlDenomQty = Number(ttlDenomQty1.replace(/[^-?0-9\.]/g, ''));
                }
                $('#' + rowPrfxNm + rndmNum + '_TtlVal').val(addCommas(Number(ttlCptrd.replace(/[^-?0-9\.]/g, '')).toFixed(2)));
                $('#' + rowPrfxNm + rndmNum + '_Qty').val(ttlDenomQty);
                $('#' + rowPrfxNm + rndmNum + '_Tray').val(setTrysBndlVals(ttlDenomQty, "tray"));
                $('#' + rowPrfxNm + rndmNum + '_Bndl').val(setTrysBndlVals(ttlDenomQty, "bundle"));
                if (lineTrnsTyp1 === 'NotPresent') {
                    if (lineSrcCgID > 0) {
                        rnngBals = typeof $('#oneVmsTrnsFrmSrcCage_' + itemID + '_' + lineSrcCgID).val() === 'undefined' ? '0' : $('#oneVmsTrnsFrmSrcCage_' + itemID + '_' + lineSrcCgID).val();
                        newRnngBals = Number(rnngBals.replace(/[^-?0-9\.]/g, '')) + calcItemTtlVal(itemID, i, lineSrcCgID) + calcItemTtlValIssues(itemID, i, lineSrcCgID);
                        $('#' + rowPrfxNm + rndmNum + '_SrcRnngBal').val(addCommas(newRnngBals.toFixed(2)));
                    }
                    if (lineDstCgID > 0) {
                        rnngBals = typeof $('#oneVmsTrnsFrmDstCage_' + itemID + '_' + lineDstCgID).val() === 'undefined' ? '0' : $('#oneVmsTrnsFrmDstCage_' + itemID + '_' + lineDstCgID).val();
                        newRnngBals = Number(rnngBals.replace(/[^-?0-9\.]/g, '')) + calcItemTtlVal(itemID, i, lineDstCgID) + calcItemTtlValIssues(itemID, i, lineDstCgID);
                        $('#' + rowPrfxNm + rndmNum + '_DstRnngBal').val(addCommas(newRnngBals.toFixed(2)));
                    }
                } else {
                    rnngBals = typeof $('#oneVmsTrnsFrmItm_' + itemID + '').val() === 'undefined' ? '0' : $('#oneVmsTrnsFrmItm_' + itemID + '').val();
                    newRnngBals = Number(rnngBals.replace(/[^-?0-9\.]/g, '')) + calcItemTtlVal(itemID, i);
                    $('#' + rowPrfxNm + rndmNum + '_RnngBal').val(addCommas(newRnngBals.toFixed(2)));
                }
                if (lineTrnsTyp1 === "Receipts" || lineDstCgID > 0) {
                    if (vmsTrnsType.trim() == 'Currency Sale' || vmsTrnsType.trim() == 'Withdrawals' || vmsTrnsType.trim() == 'Currency Destruction' || vmsTrnsType.trim() == 'Vault/GL Account Transfers') {
                        ttlAmount = ttlAmount - Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                    } else {
                        ttlAmount = ttlAmount + Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                    }
                }
                if (lineTrnsTyp1 === "Issues" || lineSrcCgID > 0) {
                    if (vmsTrnsType.trim() == 'Currency Sale' || vmsTrnsType.trim() == 'Withdrawals' || vmsTrnsType.trim() == 'Currency Destruction' || vmsTrnsType.trim() == 'Vault/GL Account Transfers') {
                        ttlAmount = ttlAmount + Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                    } else {
                        ttlAmount = ttlAmount - Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                    }
                }
                if (lineDstCgID > 0 && lineSrcCgID > 0 && lineSrcCgID !== lineDstCgID) {
                    ttlAmount = ttlAmount + Number(ttlCptrd.replace(/[^-?0-9\.]/g, ''));
                }
            }
        }
    });
    //alert(ttlAmount);
    var crncyNm = $('#vmsTrnsCrncyNm').val();
    $('#myCptrdValsTtlBtn').text(crncyNm + ' ' + addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdValsTtlVal').val(ttlAmount.toFixed(2));
}

function clearVMSForm(tblID)
{
    $('#vmsTrnsDesc').val('');
    $('#vmsTrnsClsfctn').val('');
    $('#ttlVMSDocAmntVal').val(0.00);
    clearVMSTrnsRows(tblID);
}

function reloadVMSTrnsLines(pKeyID, trnsType, vwtype, itmID, itmCatgry, shdSave)
{
    if (typeof itmID === 'undefined') {
        itmID = -1;
    }
    if (typeof itmCatgry === 'undefined') {
        itmCatgry = 'Note';
    }
    if (typeof shdSave === 'undefined') {
        shdSave = 1;
    }
    var crncyNm = $('#vmsTrnsCrncyNm').val();
    var vmsTrnsDesc = $('#vmsTrnsDesc').val();
    var destCageID = $('#vmsDfltDestCageID').val();
    var srcCageID = $('#vmsDfltSrcCageID').val();
    var vmsDfltSrcVltID = $('#vmsDfltSrcVltID').val();
    var vmsDfltDestVltID = $('#vmsDfltDestVltID').val();
    var lnkArgs = 'grp=25&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdVmsTrnsHdrID=' + pKeyID +
            '&trnsType=' + trnsType + "&crncyIDNm=" + crncyNm + "&destCageID=" + destCageID +
            '&srcCageID=' + srcCageID + "&vmsDfltSrcVltID=" + vmsDfltSrcVltID +
            '&vmsDfltDestVltID=' + vmsDfltDestVltID + '&vmsTrnsDesc=' + vmsTrnsDesc + '&sbmtdItmID=' + itmID;
    var shdPrcd = 0;
    if (pKeyID > 0 && vwtype == 28 && shdSave > 0) {
        shdPrcd = saveVmsTrnsLines();
    } else if (pKeyID > 0 && vwtype == 36 && shdSave > 0)
    {
        shdPrcd = saveVmsDrctTrnsLines();
    } else if (shdSave <= 0) {
        shdPrcd = 5;
    }
    if (shdPrcd >= 5) {
        doAjaxWthCallBck(lnkArgs, 'oneVmsTrnsLnsTblSctn', 'PasteDirect', '', '', '', function () {
            if (!$.fn.DataTable.isDataTable('#oneVmsExpnsTrnsLnsTable')) {
                var table1 = $('#oneVmsExpnsTrnsLnsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneVmsExpnsTrnsLnsTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#oneVmsTrnsLnsTable')) {
                var table1 = $('#oneVmsTrnsLnsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneVmsTrnsLnsTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#oneVmsTrnsCoinsLnsTable')) {
                var table1 = $('#oneVmsTrnsCoinsLnsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneVmsTrnsCoinsLnsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#oneVmsTrnsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            $('[data-toggle="tabajxdenoms"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');

                if (targ.indexOf('Coin') >= 0) {
                    $('#addNwNoteBtn').addClass('hideNotice');
                    $('#btnGrpForNotes').addClass('hideNotice');
                    $('#cashItmsNotes').addClass('hideNotice');
                    $('#cashItmsExpns').addClass('hideNotice');
                    $('#addNwExpnsBtn').addClass('hideNotice');
                    $('#addNwCoinBtn').removeClass('hideNotice');
                    $('#btnGrpForCoins').removeClass('hideNotice');
                    $('#cashItmsCoins').removeClass('hideNotice');
                } else if (targ.indexOf('Note') >= 0) {
                    $('#addNwNoteBtn').removeClass('hideNotice');
                    $('#btnGrpForNotes').removeClass('hideNotice');
                    $('#cashItmsNotes').removeClass('hideNotice');
                    $('#cashItmsExpns').addClass('hideNotice');
                    $('#addNwCoinBtn').addClass('hideNotice');
                    $('#btnGrpForCoins').addClass('hideNotice');
                    $('#cashItmsCoins').addClass('hideNotice');
                    $('#addNwExpnsBtn').addClass('hideNotice');
                } else {
                    $('#cashItmsExpns').removeClass('hideNotice');
                    $('#addNwExpnsBtn').removeClass('hideNotice');
                    $('#addNwCoinBtn').addClass('hideNotice');
                    $('#btnGrpForCoins').addClass('hideNotice');
                    $('#cashItmsCoins').addClass('hideNotice');
                    $('#addNwNoteBtn').addClass('hideNotice');
                    $('#btnGrpForNotes').addClass('hideNotice');
                    $('#cashItmsNotes').addClass('hideNotice');
                }
            });
            if (itmCatgry.indexOf('Coin') >= 0) {
                $('#cashItmsNotestab').trigger('click');
                $('#cashItmsCoinstab').trigger('click');
            } else {
                $('#cashItmsCoinstab').trigger('click');
                $('#cashItmsNotestab').trigger('click');
            }
            $('[data-toggle="tooltip"]').tooltip();
            $(".vmsCbTtl").focus(function () {
                $(this).select();
            });
            $(".vmsCbQty").focus(function () {
                $(this).select();
            });
            $(".vmsFncCrncy").focus(function () {
                $(this).select();
            });
            $(".vmsCbTray").focus(function () {
                $(this).select();
            });
            $(".vmsCbBndl").focus(function () {
                $(this).select();
            });
            $('#oneVmsTrnsLnsTable tr').off('click');
            $('#oneVmsTrnsLnsTable tr').click(function () {
                var rowIndex = $('#oneVmsTrnsLnsTable tr').index(this);
                //alert(rowIndex);
                $('#allOtherInputData99').val(rowIndex);
            });
            $('#oneVmsTrnsCoinsLnsTable tr').off('click');
            $('#oneVmsTrnsCoinsLnsTable tr').click(function () {
                var rowIndex = $('#oneVmsTrnsCoinsLnsTable tr').index(this);
                //alert(rowIndex);
                $('#allOtherInputData99').val(rowIndex);
            });
            var crncyNm = $('#vmsTrnsCrncyNm').val();
            $('#myCptrdValsTtlBtn').text(crncyNm + ' 0.00');
            $('#myCptrdValsTtlVal').val(0.00);
            createRnngBal1Flds();
            calcCshBrkdwnRowVal('oneVmsTrnsRow_1');
            calcCshBrkdwnRowVal('oneVmsTrnsCoinsRow_1');
        });
    }
}

function wthdrwVMSTrnsRqst(trnsType, vwtype, tblrVwTyp, srcMenu,
        isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID)
{
    if (typeof sbmtdShelfID === 'undefined' || sbmtdShelfID === null) {
        sbmtdShelfID = -1;
    }
    var pKeyID = typeof $("#vmsTrnsHdrID").val() === 'undefined' ? -1 : $("#vmsTrnsHdrID").val();
    var dialog = bootbox.confirm({
        title: 'Withdraw Request?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">WITHDRAW</span> this Request?<br/>Action cannot be Undone!</p>',
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
                    title: 'Withdraw Request?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Withdrawing Request...Please Wait...</p>',
                    callback: function () {
                        if (pKeyID > 0)
                        {
                            getOneVmsTrnsForm(pKeyID, trnsType, vwtype, 'ReloadDialog', tblrVwTyp, '', srcMenu,
                                    isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID);
                        }
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 40,
                                    vmsTrnsHdrID: pKeyID
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

function authrzeVMSTrnsRqst(trnsType, vwtype, tblrVwTyp, srcMenu,
        isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID)
{
    if (typeof sbmtdShelfID === 'undefined' || sbmtdShelfID === null) {
        sbmtdShelfID = -1;
    }
    var pKeyID = typeof $("#vmsTrnsHdrID").val() === 'undefined' ? -1 : $("#vmsTrnsHdrID").val();
    var dialog = bootbox.confirm({
        title: 'Authorize Request?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">AUTHORIZE</span> this Request?<br/>Action cannot be Undone!</p>',
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
                    title: 'Authorize Request?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Authorizing Request...Please Wait...</p>',
                    callback: function () {
                        if (pKeyID > 0)
                        {
                            getOneVmsTrnsForm(pKeyID, trnsType, vwtype, 'ReloadDialog', tblrVwTyp, '', srcMenu,
                                    isFrmBnkng, sbmtdShelfID, sbmtdSiteID, sbmtdStoreID);
                        }
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 2,
                                    q: 'FINALIZE',
                                    actyp: 40,
                                    vmsTrnsHdrID: pKeyID
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

function getVMSTrnsPymtForm(trnsType, vwtype, actionTxt)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    var vmsTrnsHdrID = typeof $("#vmsTrnsHdrID").val() === 'undefined' ? '-1' : $("#vmsTrnsHdrID").val();
    var vmsVoidedTrnsHdrID = typeof $("#vmsVoidedTrnsHdrID").val() === 'undefined' ? '-1' : $("#vmsVoidedTrnsHdrID").val();
    var varTtlPymtStr = typeof $("#myPymntValsTtlVal").val() === 'undefined' ? '' : $("#myPymntValsTtlVal").val();
    var varTtlPymt = Number(varTtlPymtStr.replace(/[^-?0-9\.]/g, ''));
    var sbmtdCrncyNm = typeof $("#vmsPymtCrncyNm").val() === 'undefined' ? '' : $("#vmsPymtCrncyNm").val();
    var vmsPymtDfltDestVltID = typeof $("#vmsPymtDfltDestVltID").val() === 'undefined' ? -1 : $("#vmsPymtDfltDestVltID").val();
    var vmsPymtDfltDestCageID = typeof $("#vmsPymtDfltDestCageID").val() === 'undefined' ? -1 : $("#vmsPymtDfltDestCageID").val();
    var errMsg = '';
    if (sbmtdCrncyNm.trim() === '' || varTtlPymt == 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Payment Currency and Total Amount must be specified first!</span></p>';
    }
    if (Number(vmsTrnsHdrID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please save this transaction first!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var lnkArgs = 'grp=25&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdVmsTrnsHdrID=' + vmsTrnsHdrID +
            "&varTtlPymt=" + varTtlPymt + "&sbmtdCrncyNm=" + sbmtdCrncyNm + "&trnsType=" + trnsType +
            "&vmsPymtDfltDestVltID=" + vmsPymtDfltDestVltID +
            "&vmsPymtDfltDestCageID=" + vmsPymtDfltDestCageID +
            "&vmsVoidedTrnsHdrID=" + vmsVoidedTrnsHdrID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaln', actionTxt, 'VMS Trns. Payment (Expected:' + sbmtdCrncyNm + ' ' + addCommas(varTtlPymt.toFixed(2)) + ')', 'myFormsModalnTitle', 'myFormsModalnBody', function () {
        if (!$.fn.DataTable.isDataTable('#oneVmsPymtsLnsTable')) {
            var table1 = $('#oneVmsPymtsLnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneVmsPymtsLnsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#oneVmsTrnsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(".vmsPymtTtl").focus(function () {
            $(this).select();
        });
        $(".vmsPymtQty").focus(function () {
            $(this).select();
        });
        $(".vmsPymtRnngBal").focus(function () {
            $(this).select();
        });
        $(".vmsPymtFncCrncy").focus(function () {
            $(this).select();
        });
        calcAllPymtRowsTtlVals();
    }
    );
}

function getAllVmsBrnchs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allVmsBrnchsSrchFor").val() === 'undefined' ? '%' : $("#allVmsBrnchsSrchFor").val();
    var srchIn = typeof $("#allVmsBrnchsSrchIn").val() === 'undefined' ? 'Both' : $("#allVmsBrnchsSrchIn").val();
    var pageNo = typeof $("#allVmsBrnchsPageNo").val() === 'undefined' ? 1 : $("#allVmsBrnchsPageNo").val();
    var limitSze = typeof $("#allVmsBrnchsDsplySze").val() === 'undefined' ? 10 : $("#allVmsBrnchsDsplySze").val();
    var sortBy = typeof $("#allVmsBrnchsSortBy").val() === 'undefined' ? '' : $("#allVmsBrnchsSortBy").val();
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

function enterKeyFuncAllVmsBrnchs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllVmsBrnchs(actionText, slctr, linkArgs);
    }
}

function getOneVmsBrnchsForm(pKeyID, actionTxt, frmWhere)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    if (typeof frmWhere === 'undefined' || frmWhere === null)
    {
        frmWhere = 'FROMSTP';
    }
    var lnkArgs = 'grp=25&typ=1&pg=4&vtyp=101&srcMenu=VMS&sbmtdSiteID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', actionTxt, 'Site (ID:' + pKeyID + ')', 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        $('#branchStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#allOtherInputData99').val(0);
        $('#myFormsModalNrml').off('hidden.bs.modal');
        $('#myFormsModalNrml').one('hidden.bs.modal', function (e) {
            $('#myFormsModalNrmlTitle').html('');
            $('#myFormsModalNrmlBody').html('');
            if (frmWhere === 'FROMBRNCH') {
                getAllVmsBrnchs('clear', '#allmodules', 'grp=25&typ=1&pg=1&vtyp=0&srcMenu=VMS');
            } else {
                getAllVmsBrnchs('clear', '#allmodules', 'grp=25&typ=1&pg=4&vtyp=1&srcMenu=VMS');
            }
            $(e.currentTarget).unbind();
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
}

function saveVmsBrnchsForm()
{
    var siteBrnchNm = typeof $("#siteBrnchNm").val() === 'undefined' ? '' : $("#siteBrnchNm").val();
    var sbmtdSiteID = typeof $("#sbmtdSiteID").val() === 'undefined' ? '-1' : $("#sbmtdSiteID").val();
    var siteBrnchDesc = typeof $("#siteBrnchDesc").val() === 'undefined' ? '' : $("#siteBrnchDesc").val();
    var siteBrnchType = typeof $("#siteBrnchType").val() === 'undefined' ? '' : $("#siteBrnchType").val();
    var grpType = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    var allwdGroupNm = typeof $("#allwdGroupNm").val() === 'undefined' ? '' : $("#allwdGroupNm").val();
    var allwdGroupID = typeof $("#allwdGroupID").val() === 'undefined' ? '' : $("#allwdGroupID").val();
    var isSiteEnbld = typeof $("input[name='isSiteEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var errMsg = "";
    if (siteBrnchNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Site name cannot be empty!</span></p>';
    }
    if (siteBrnchDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Site Description cannot be empty!</span></p>';
    }
    if (siteBrnchType.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Site Type cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Vault',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Site...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                /*$('#myFormsModalNrml').modal('hide');*/
                getOneVmsBrnchsForm(sbmtdSiteID, 'ReloadDialog');
            }
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
                    grp: 25,
                    typ: 1,
                    pg: 4,
                    q: 'UPDATE',
                    actyp: 1,
                    siteBrnchNm: siteBrnchNm,
                    sbmtdSiteID: sbmtdSiteID,
                    siteBrnchDesc: siteBrnchDesc,
                    siteBrnchType: siteBrnchType,
                    grpType: grpType,
                    allwdGroupNm: allwdGroupNm,
                    allwdGroupID: allwdGroupID,
                    isSiteEnbld: isSiteEnbld
                },
                success: function (result) {
                    //console.log(result);
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        sbmtdSiteID = result.sitelocid;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delVmsBrnchs(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var siteNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SiteID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SiteID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        siteNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Site?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Site?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Site?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Site...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 1,
                                    sbmtdSiteID: pKeyID,
                                    siteNm: siteNm
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

function getAllBrchVaults(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allBrchVaultsSrchFor").val() === 'undefined' ? '%' : $("#allBrchVaultsSrchFor").val();
    var srchIn = typeof $("#allBrchVaultsSrchIn").val() === 'undefined' ? 'Both' : $("#allBrchVaultsSrchIn").val();
    var pageNo = typeof $("#allBrchVaultsPageNo").val() === 'undefined' ? 1 : $("#allBrchVaultsPageNo").val();
    var limitSze = typeof $("#allBrchVaultsDsplySze").val() === 'undefined' ? 10 : $("#allBrchVaultsDsplySze").val();
    var sortBy = typeof $("#allBrchVaultsSortBy").val() === 'undefined' ? '' : $("#allBrchVaultsSortBy").val();
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

function enterKeyFuncAllBrchVaults(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllBrchVaults(actionText, slctr, linkArgs);
    }
}

function getAllVaultCages(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allVaultCagesSrchFor").val() === 'undefined' ? '%' : $("#allVaultCagesSrchFor").val();
    var srchIn = typeof $("#allVaultCagesSrchIn").val() === 'undefined' ? 'Both' : $("#allVaultCagesSrchIn").val();
    var pageNo = typeof $("#allVaultCagesPageNo").val() === 'undefined' ? 1 : $("#allVaultCagesPageNo").val();
    var limitSze = typeof $("#allVaultCagesDsplySze").val() === 'undefined' ? 10 : $("#allVaultCagesDsplySze").val();
    var sortBy = typeof $("#allVaultCagesSortBy").val() === 'undefined' ? '' : $("#allVaultCagesSortBy").val();
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

function enterKeyFuncAllVaultCages(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllVaultCages(actionText, slctr, linkArgs);
    }
}

function getAllCageItems(actionText, slctr, linkArgs, callBackFunc)
{
    var srchFor = typeof $("#allCageItemsSrchFor").val() === 'undefined' ? '%' : $("#allCageItemsSrchFor").val();
    var srchIn = typeof $("#allCageItemsSrchIn").val() === 'undefined' ? 'Both' : $("#allCageItemsSrchIn").val();
    var pageNo = typeof $("#allCageItemsPageNo").val() === 'undefined' ? 1 : $("#allCageItemsPageNo").val();
    var limitSze = typeof $("#allCageItemsDsplySze").val() === 'undefined' ? 10 : $("#allCageItemsDsplySze").val();
    var sortBy = typeof $("#allCageItemsSortBy").val() === 'undefined' ? '' : $("#allCageItemsSortBy").val();
    var qStrtDte = typeof $("#allCageItemTrnsStrtDate").val() === 'undefined' ? '' : $("#allCageItemTrnsStrtDate").val();
    var qEndDte = typeof $("#allCageItemTrnsEndDate").val() === 'undefined' ? '' : $("#allCageItemTrnsEndDate").val();
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
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy +
            "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte;
    callBackFunc(slctr, linkArgs);
}

function enterKeyFuncAllCageItems(e, actionText, slctr, linkArgs, callBackFunc)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllCageItems(actionText, slctr, linkArgs, callBackFunc);
    }
}

function getAllCageItemTrns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allCageItemTrnsSrchFor").val() === 'undefined' ? '%' : $("#allCageItemTrnsSrchFor").val();
    var srchIn = typeof $("#allCageItemTrnsSrchIn").val() === 'undefined' ? 'Both' : $("#allCageItemTrnsSrchIn").val();
    var pageNo = typeof $("#allCageItemTrnsPageNo").val() === 'undefined' ? 1 : $("#allCageItemTrnsPageNo").val();
    var limitSze = typeof $("#allCageItemTrnsDsplySze").val() === 'undefined' ? 10 : $("#allCageItemTrnsDsplySze").val();
    var sortBy = typeof $("#allCageItemTrnsSortBy").val() === 'undefined' ? '' : $("#allCageItemTrnsSortBy").val();
    var qStrtDte = typeof $("#allCageItemTrnsStrtDate").val() === 'undefined' ? '' : $("#allCageItemTrnsStrtDate").val();
    var qEndDte = typeof $("#allCageItemTrnsEndDate").val() === 'undefined' ? '' : $("#allCageItemTrnsEndDate").val();
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
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy +
            "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllCageItemTrns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllCageItemTrns(actionText, slctr, linkArgs);
    }
}

function getOneCageFnPos(pKeyID, vwtype)
{
    var lnkArgs = 'grp=25&typ=1&pg=1&vtyp=' + vwtype + "&sbmtdShelfID=" + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaly', 'ShowDialog', 'Vault Cage Stock Position', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        if (!$.fn.DataTable.isDataTable('#allCagePostnTable')) {
            var table1 = $('#allCagePostnTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allCagePostnTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
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
}

function getDynmcCageFnPos(pKeyIDElementID, vwtype)
{
    var pKeyID = typeof $("#" + pKeyIDElementID).val() === 'undefined' ? -1 : $("#" + pKeyIDElementID).val();
    var lnkArgs = 'grp=25&typ=1&pg=1&vtyp=' + vwtype + "&sbmtdShelfID=" + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaly', 'ShowDialog', 'Vault Cage Stock Position', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        if (!$.fn.DataTable.isDataTable('#allCagePostnTable')) {
            var table1 = $('#allCagePostnTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allCagePostnTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function getAllVmsGLIntrfcs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allVmsGLIntrfcsSrchFor").val() === 'undefined' ? '%' : $("#allVmsGLIntrfcsSrchFor").val();
    var srchIn = typeof $("#allVmsGLIntrfcsSrchIn").val() === 'undefined' ? 'Both' : $("#allVmsGLIntrfcsSrchIn").val();
    var pageNo = typeof $("#allVmsGLIntrfcsPageNo").val() === 'undefined' ? 1 : $("#allVmsGLIntrfcsPageNo").val();
    var limitSze = typeof $("#allVmsGLIntrfcsDsplySze").val() === 'undefined' ? 10 : $("#allVmsGLIntrfcsDsplySze").val();
    var sortBy = typeof $("#allVmsGLIntrfcsSortBy").val() === 'undefined' ? '' : $("#allVmsGLIntrfcsSortBy").val();
    var qStrtDte = typeof $("#allVmsGLIntrfcsStrtDate").val() === 'undefined' ? '' : $("#allVmsGLIntrfcsStrtDate").val();
    var qEndDte = typeof $("#allVmsGLIntrfcsEndDate").val() === 'undefined' ? '' : $("#allVmsGLIntrfcsEndDate").val();
    var qNotSentToGl = $('#allVmsGLIntrfcsSntToGl:checked').length > 0;
    var qUnbalncdOnly = $('#allVmsGLIntrfcsUnbalncd:checked').length > 0;
    var qUsrGnrtd = $('#allVmsGLIntrfcsUsrTrns:checked').length > 0;
    var qLowVal = typeof $("#allVmsGLIntrfcsLowVal").val() === 'undefined' ? 0 : $("#allVmsGLIntrfcsLowVal").val();
    var qHighVal = typeof $("#allVmsGLIntrfcsHighVal").val() === 'undefined' ? 0 : $("#allVmsGLIntrfcsHighVal").val();
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
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy +
            "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte +
            "&qNotSentToGl=" + qNotSentToGl + "&qUnbalncdOnly=" + qUnbalncdOnly +
            "&qUsrGnrtd=" + qUsrGnrtd + "&qLowVal=" + qLowVal + "&qHighVal=" + qHighVal;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllVmsGLIntrfcs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllVmsGLIntrfcs(actionText, slctr, linkArgs);
    }
}

function getOneVMSGLIntrfcForm(pKeyID, pRowIDAttrb)
{
    var slctdIntrfcIDs = "";
    var slctdCnt = 0;
    var rowIDAttrb = "";
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
                        rowIDAttrb = $(el).attr('id');
                    }
                }
            }
        }
    });
    if (slctdCnt == 1 || pKeyID > 0) {
        if (pKeyID > 0)
        {
            rowIDAttrb = pRowIDAttrb;
        }
        var $tds = $('#' + rowIDAttrb).find('td');
        var trnsDesc = $.trim($tds.eq(3).text());
        var trnsDate = $.trim($tds.eq(7).text());
        var dbtAmnt = $.trim($tds.eq(5).text());
        var crdtAmnt = $.trim($tds.eq(6).text());
        var trnsCur = $.trim($tds.eq(4).text());
        var trnsAmnt = Math.abs(Number(dbtAmnt.replace(/[^-?0-9\.]/g, '')) - Number(crdtAmnt.replace(/[^-?0-9\.]/g, ''))).toFixed(2);
        var lnkArgs = 'grp=25&typ=1&pg=4&vtyp=701&sbmtdIntrfcID=' + pKeyID;
        doAjaxWthCallBck(lnkArgs, 'myFormsModalp', 'ShowDialog', 'GL Interface Line (ID:' + pKeyID + ')', 'myFormsModalpTitle', 'myFormsModalpBody', function () {
            $('#addGLIntrfcsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('#allOtherInputData99').val(0);
            $('#glIntrfcTrnsDesc').val(trnsDesc);
            $('#glIntrfcTrnsDate').val(trnsDate);
            $('#enteredAmount').val(trnsAmnt);
            $('#enteredCrncyNm').val(trnsCur);
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
            $('#myFormsModalx').off('hidden.bs.modal');
            $('#myFormsModalx').one('hidden.bs.modal', function (e) {
                $('#myFormsModalxTitle').html('');
                $('#myFormsModalxBody').html('');
                getAllVmsGLIntrfcs('clear', '#allmodules', 'grp=25&typ=1&pg=4&vtyp=7');
                $(e.currentTarget).unbind();
            });
        });
    } else {
        var errMsg = '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please first select ONE unbalanced transaction!</span></p>';
        var dialog1 = bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
    }
}

function afterVMSIntrfcItemSlctn()
{
    fmtAsNumber('enteredAmount');
    $('#funcCrncyRate').val(1);
    $('#accntCrncyRate').val(1);
    var enteredCrncyNm = $('#enteredCrncyNm').val();
    var enteredAmount = Number($('#enteredAmount').val().replace(/[^-?0-9\.]/g, ''));
    var intrfcAccntID = $('#intrfcAccntID').val();
    var glIntrfcTrnsDate = typeof $("#glIntrfcTrnsDate").val() === 'undefined' ? '' : $("#glIntrfcTrnsDate").val();
    var funcCrncyRate = typeof $("#funcCrncyRate").val() === 'undefined' ? '0' : $("#funcCrncyRate").val();
    var accntCrncyRate = typeof $("#accntCrncyRate").val() === 'undefined' ? '0' : $("#accntCrncyRate").val();
    if (Number(intrfcAccntID) > 0) {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append('grp', 25);
            formData.append('typ', 1);
            formData.append('pg', 4);
            formData.append('q', 'VIEW');
            formData.append('vtyp', 702);
            formData.append('intrfcAccntID', intrfcAccntID);
            formData.append('enteredCrncyNm', enteredCrncyNm);
            formData.append('enteredAmount', enteredAmount);
            formData.append('glIntrfcTrnsDate', glIntrfcTrnsDate);
            formData.append('funcCrncyRate', funcCrncyRate);
            formData.append('accntCrncyRate', accntCrncyRate);
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
                        //alert(data.FuncCrncyNm);
                        $('#funcCrncyRate').val(data.FuncCrncyRate);
                        $('#accntCrncyRate').val(data.AccntCrncyRate);
                        $('#funcCrncyNm').html(data.FuncCrncyNm);
                        $('#funcCrncyAmount').val(data.FuncCrncyAmount);
                        $('#accntCrncyNm').html(data.AccntCrncyNm);
                        $('#accntCrncyAmount').val(data.AccntCrncyAmount);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.warn(jqXHR.responseText);
                }
            });
        });
    } else {
        $('#funcCrncyRate').val(1);
        $('#accntCrncyRate').val(1);
        $('#funcCrncyNm').val('');
        $('#funcCrncyAmount').val(0.00);
        $('#accntCrncyNm').val('');
        $('#accntCrncyAmount').val(0.00);
    }
}

function saveVMSGLIntrfcForm()
{
    var glIntrfcTrnsDate = typeof $("#glIntrfcTrnsDate").val() === 'undefined' ? '' : $("#glIntrfcTrnsDate").val();
    var glIntrfcTrnsID = typeof $("#glIntrfcTrnsID").val() === 'undefined' ? '-1' : $("#glIntrfcTrnsID").val();
    var glIntrfcTrnsDesc = typeof $("#glIntrfcTrnsDesc").val() === 'undefined' ? '' : $("#glIntrfcTrnsDesc").val();
    var intrfcAccntID = typeof $("#intrfcAccntID").val() === 'undefined' ? '-1' : $("#intrfcAccntID").val();
    var incrsDcrs = typeof $("#incrsDcrs").val() === 'undefined' ? '' : $("#incrsDcrs").val();
    var enteredCrncyNm = typeof $("#enteredCrncyNm").val() === 'undefined' ? '' : $("#enteredCrncyNm").val();
    var enteredAmount = typeof $("#enteredAmount").val() === 'undefined' ? '0' : $("#enteredAmount").val();
    var funcCrncyRate = typeof $("#funcCrncyRate").val() === 'undefined' ? '0' : $("#funcCrncyRate").val();
    var accntCrncyRate = typeof $("#accntCrncyRate").val() === 'undefined' ? '0' : $("#accntCrncyRate").val();
    var funcCrncyAmount = typeof $("#funcCrncyAmount").val() === 'undefined' ? '0' : $("#funcCrncyAmount").val();
    var accntCrncyAmount = typeof $("#accntCrncyAmount").val() === 'undefined' ? '0' : $("#accntCrncyAmount").val();
    var errMsg = "";
    if (glIntrfcTrnsDate.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Transaction Date cannot be empty!</span></p>';
    }
    if (glIntrfcTrnsDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Transaction Description cannot be empty!</span></p>';
    }
    if (enteredCrncyNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Entered Currency cannot be empty!</span></p>';
    }
    if (incrsDcrs.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Action cannot be empty!</span></p>';
    }
    if (Number(intrfcAccntID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">GL Account cannot be empty!</span></p>';
    }
    if (Number(enteredAmount.replace(/[^-?0-9\.]/g, '')) == 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Entered Amount cannot be Zero!</span></p>';
    }
    if (Number(funcCrncyRate.replace(/[^-?0-9\.]/g, '')) == 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Functional Currency Rate cannot be empty!</span></p>';
    }
    if (Number(accntCrncyRate.replace(/[^-?0-9\.]/g, '')) == 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Account Currency Rate cannot be empty!</span></p>';
    }
    if (Number(funcCrncyAmount.replace(/[^-?0-9\.]/g, '')) == 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Functional Currency Amount cannot be empty!</span></p>';
    }
    if (Number(accntCrncyAmount.replace(/[^-?0-9\.]/g, '')) == 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Account Currency Rate cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Transaction',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Transaction...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                $('#myFormsModalx').modal('hide');
            }
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
                    grp: 25,
                    typ: 1,
                    pg: 4,
                    q: 'UPDATE',
                    actyp: 7,
                    glIntrfcTrnsDate: glIntrfcTrnsDate,
                    glIntrfcTrnsID: glIntrfcTrnsID,
                    glIntrfcTrnsDesc: glIntrfcTrnsDesc,
                    intrfcAccntID: intrfcAccntID,
                    incrsDcrs: incrsDcrs,
                    enteredCrncyNm: enteredCrncyNm,
                    enteredAmount: enteredAmount,
                    funcCrncyRate: funcCrncyRate,
                    accntCrncyRate: accntCrncyRate,
                    funcCrncyAmount: funcCrncyAmount,
                    accntCrncyAmount: accntCrncyAmount
                },
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
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

function getAllCstmrs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allCstmrsSrchFor").val() === 'undefined' ? '%' : $("#allCstmrsSrchFor").val();
    var srchIn = typeof $("#allCstmrsSrchIn").val() === 'undefined' ? 'Both' : $("#allCstmrsSrchIn").val();
    var pageNo = typeof $("#allCstmrsPageNo").val() === 'undefined' ? 1 : $("#allCstmrsPageNo").val();
    var limitSze = typeof $("#allCstmrsDsplySze").val() === 'undefined' ? 10 : $("#allCstmrsDsplySze").val();
    var sortBy = typeof $("#allCstmrsSortBy").val() === 'undefined' ? '' : $("#allCstmrsSortBy").val();
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

function enterKeyFuncAllCstmrs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllCstmrs(actionText, slctr, linkArgs);
    }
}

function getOneCstmrsForm(pKeyID, actionTxt)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=25&typ=1&pg=4&vtyp=601&srcMenu=VMS&sbmtdCstmrSpplrID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Customer/Supplier (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        $('#vaultCstmrStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(function () {
            $('[data-toggle="tabajxvmscstmrs"]').off('click');
            $('[data-toggle="tabajxvmscstmrs"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                if (targ.indexOf('vmsCstmrsGnrl') >= 0) {
                    $('#vmsCstmrsGnrl').removeClass('hideNotice');
                    $('#vmsCstmrsSites').addClass('hideNotice');
                    $('#vmsCstmrsSrvcs').addClass('hideNotice');
                } else if (targ.indexOf('vmsCstmrsSites') >= 0) {
                    $('#vmsCstmrsGnrl').addClass('hideNotice');
                    $('#vmsCstmrsSites').removeClass('hideNotice');
                    $('#vmsCstmrsSrvcs').addClass('hideNotice');
                } else if (targ.indexOf('vmsCstmrsSrvcs') >= 0) {
                    $('#vmsCstmrsGnrl').addClass('hideNotice');
                    $('#vmsCstmrsSites').addClass('hideNotice');
                    $('#vmsCstmrsSrvcs').removeClass('hideNotice');
                }
            });
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
        $('#allOtherInputData99').val(0);
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            $('#myFormsModalTitleLg').html('');
            $('#myFormsModalBodyLg').html('');
            getAllCstmrs('clear', '#allmodules', 'grp=25&typ=1&pg=4&vtyp=6&srcMenu=VMS');
            $(e.currentTarget).unbind();
        });
    });
}

function saveCstmrsForm()
{
    var sbmtdCstmrSpplrID = typeof $("#sbmtdCstmrSpplrID").val() === 'undefined' ? '-1' : $("#sbmtdCstmrSpplrID").val();
    var cstmrSpplrNm = typeof $("#cstmrSpplrNm").val() === 'undefined' ? '' : $("#cstmrSpplrNm").val();
    var cstmrSpplrDesc = typeof $("#cstmrSpplrDesc").val() === 'undefined' ? '' : $("#cstmrSpplrDesc").val();
    var cstmrSpplrType = typeof $("#cstmrSpplrType").val() === 'undefined' ? '' : $("#cstmrSpplrType").val();
    var cstmrSpplrClsfctn = typeof $("#cstmrSpplrClsfctn").val() === 'undefined' ? '' : $("#cstmrSpplrClsfctn").val();
    var cstmrSpplrLnkdPrsnID = typeof $("#cstmrSpplrLnkdPrsnID").val() === 'undefined' ? '-1' : $("#cstmrSpplrLnkdPrsnID").val();
    var cstmrSpplrGender = typeof $("#cstmrSpplrGender").val() === 'undefined' ? '' : $("#cstmrSpplrGender").val();
    var cstmrSpplrDOB = typeof $("#cstmrSpplrDOB").val() === 'undefined' ? '' : $("#cstmrSpplrDOB").val();
    var cstmrLbltyAcntID = typeof $("#cstmrLbltyAcntID").val() === 'undefined' ? '-1' : $("#cstmrLbltyAcntID").val();
    var cstmrRcvblsAcntID = typeof $("#cstmrRcvblsAcntID").val() === 'undefined' ? '-1' : $("#cstmrRcvblsAcntID").val();
    var cstmrCmpnyBrandNm = typeof $("#cstmrCmpnyBrandNm").val() === 'undefined' ? '' : $("#cstmrCmpnyBrandNm").val();
    var isCstmrEnbld = typeof $("input[name='isCstmrEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var cstmrOrgType = typeof $("#cstmrOrgType").val() === 'undefined' ? '' : $("#cstmrOrgType").val();
    var cstmrRegNum = typeof $("#cstmrRegNum").val() === 'undefined' ? '' : $("#cstmrRegNum").val();
    var cstmrDateIncprtd = typeof $("#cstmrDateIncprtd").val() === 'undefined' ? '' : $("#cstmrDateIncprtd").val();
    var cstmrIncprtnType = typeof $("#cstmrIncprtnType").val() === 'undefined' ? '' : $("#cstmrIncprtnType").val();
    var cstmrVatNumber = typeof $("#cstmrVatNumber").val() === 'undefined' ? '' : $("#cstmrVatNumber").val();
    var cstmrTinNumber = typeof $("#cstmrTinNumber").val() === 'undefined' ? '' : $("#cstmrTinNumber").val();
    var cstmrSsnitRegNum = typeof $("#cstmrSsnitRegNum").val() === 'undefined' ? '' : $("#cstmrSsnitRegNum").val();
    var cstmrNumEmployees = typeof $("#cstmrNumEmployees").val() === 'undefined' ? '' : $("#cstmrNumEmployees").val();
    var cstmrDescSrvcs = typeof $("#cstmrDescSrvcs").val() === 'undefined' ? '' : $("#cstmrDescSrvcs").val();
    var errMsg = "";
    if (cstmrSpplrNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Customer/Supplier name cannot be empty!</span></p>';
    }
    if (cstmrSpplrDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Customer/Supplier Description cannot be empty!</span></p>';
    }
    if (cstmrSpplrGender.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Customer/Supplier Gender cannot be empty!</span></p>';
    }
    if (cstmrSpplrDOB.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Customer/Supplier Date Established cannot be empty!</span></p>';
    }
    if (Number(cstmrLbltyAcntID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Customer/Supplier Liability Account cannot be empty!</span></p>';
    }
    if (Number(cstmrRcvblsAcntID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Customer/Supplier Receivables Account cannot be empty!</span></p>';
    }
    if (cstmrSpplrType.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Customer/Supplier Type cannot be empty!</span></p>';
    }
    if (cstmrSpplrClsfctn.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Customer/Supplier Classification cannot be empty!</span></p>';
    }
    var cstmrListOfSrvcs = "";
    var isVld = true;
    $('#oneCstmrSrvcsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_SrvcNm').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if (isVld === true)
                    {
                        cstmrListOfSrvcs = cstmrListOfSrvcs + $('#' + rowPrfxNm + rndmNum + '_SrvcNm').val() + "|";
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Customer/Supplier',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Customer/Supplier...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                /*$('#myFormsModalNrml').modal('hide');*/
                getOneCstmrsForm(sbmtdCstmrSpplrID, 'ReloadDialog');
            }
        }
    });
    var formData = new FormData();
    formData.append('daCstmrPicture', $('#daCstmrPicture')[0].files[0]);
    formData.append('grp', 25);
    formData.append('typ', 1);
    formData.append('pg', 4);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 6);
    formData.append('sbmtdCstmrSpplrID', sbmtdCstmrSpplrID);
    formData.append('cstmrSpplrNm', cstmrSpplrNm);
    formData.append('cstmrSpplrDesc', cstmrSpplrDesc);
    formData.append('cstmrSpplrType', cstmrSpplrType);
    formData.append('cstmrSpplrClsfctn', cstmrSpplrClsfctn);
    formData.append('cstmrSpplrLnkdPrsnID', cstmrSpplrLnkdPrsnID);
    formData.append('cstmrSpplrGender', cstmrSpplrGender);
    formData.append('cstmrSpplrDOB', cstmrSpplrDOB);
    formData.append('isCstmrEnbld', isCstmrEnbld);
    formData.append('cstmrLbltyAcntID', cstmrLbltyAcntID);
    formData.append('cstmrRcvblsAcntID', cstmrRcvblsAcntID);
    formData.append('cstmrCmpnyBrandNm', cstmrCmpnyBrandNm);
    formData.append('cstmrOrgType', cstmrOrgType);
    formData.append('cstmrRegNum', cstmrRegNum);
    formData.append('cstmrDateIncprtd', cstmrDateIncprtd);
    formData.append('cstmrIncprtnType', cstmrIncprtnType);
    formData.append('cstmrVatNumber', cstmrVatNumber);
    formData.append('cstmrTinNumber', cstmrTinNumber);
    formData.append('cstmrSsnitRegNum', cstmrSsnitRegNum);
    formData.append('cstmrNumEmployees', cstmrNumEmployees);
    formData.append('cstmrDescSrvcs', cstmrDescSrvcs);
    formData.append('cstmrListOfSrvcs', cstmrListOfSrvcs);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        sbmtdCstmrSpplrID = result.cstmrid;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delCstmrs(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var cstmrNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_CstmrID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_CstmrID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        cstmrNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Customer/Supplier?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Customer/Supplier?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Customer/Supplier?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Customer/Supplier...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 6,
                                    pKeyID: pKeyID,
                                    cstmrNm: cstmrNm
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

function getOneCstmrSitesForm(pKeyID, actionTxt)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=25&typ=1&pg=4&vtyp=602&srcMenu=VMS&sbmtdSiteID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaln', actionTxt, 'Site (ID:' + pKeyID + ')', 'myFormsModalnTitle', 'myFormsModalnBody', function () {
        $('#cstmrSiteForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#allOtherInputData99').val(0);

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
        $('#myFormsModalNrml').off('hidden.bs.modal');
        $('#myFormsModalNrml').one('hidden.bs.modal', function (e) {
            $('#myFormsModalNrmlTitle').html('');
            $('#myFormsModalNrmlBody').html('');
            /*getAllVmsVlts('clear', '#allmodules', 'grp=25&typ=1&pg=4&vtyp=2&srcMenu=VMS');*/
            $(e.currentTarget).unbind();
        });
    });
}

function saveCstmrSitesForm()
{
    var sbmtdCstmrSpplrID = typeof $("#sbmtdCstmrSpplrID").val() === 'undefined' ? '-1' : $("#sbmtdCstmrSpplrID").val();
    var sbmtdSiteID = typeof $("#sbmtdSiteID").val() === 'undefined' ? '-1' : $("#sbmtdSiteID").val();
    var csSiteName = typeof $("#csSiteName").val() === 'undefined' ? '' : $("#csSiteName").val();
    var csSiteDesc = typeof $("#csSiteDesc").val() === 'undefined' ? '' : $("#csSiteDesc").val();
    var csSiteBllngAddress = typeof $("#csSiteBllngAddress").val() === 'undefined' ? '' : $("#csSiteBllngAddress").val();
    var csSiteShpngAddress = typeof $("#csSiteShpngAddress").val() === 'undefined' ? '' : $("#csSiteShpngAddress").val();
    var csSiteCntctPrsn = typeof $("#csSiteCntctPrsn").val() === 'undefined' ? '' : $("#csSiteCntctPrsn").val();
    var csSiteCntctNos = typeof $("#csSiteCntctNos").val() === 'undefined' ? '' : $("#csSiteCntctNos").val();
    var csSiteEmailAdrs = typeof $("#csSiteEmailAdrs").val() === 'undefined' ? '' : $("#csSiteEmailAdrs").val();
    var csSiteWthTxCodeID = typeof $("#csSiteWthTxCodeID").val() === 'undefined' ? '-1' : $("#csSiteWthTxCodeID").val();
    var csSiteDscntCodeID = typeof $("#csSiteDscntCodeID").val() === 'undefined' ? '-1' : $("#csSiteDscntCodeID").val();
    var isCsSiteEnbld = typeof $("input[name='isCsSiteEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var csSiteCountry = typeof $("#csSiteCountry").val() === 'undefined' ? '' : $("#csSiteCountry").val();
    var csSiteIDType = typeof $("#csSiteIDType").val() === 'undefined' ? '' : $("#csSiteIDType").val();
    var csSiteIDNum = typeof $("#csSiteIDNum").val() === 'undefined' ? '' : $("#csSiteIDNum").val();
    var csSiteDateIsd = typeof $("#csSiteDateIsd").val() === 'undefined' ? '' : $("#csSiteDateIsd").val();
    var csSiteExpryDate = typeof $("#csSiteExpryDate").val() === 'undefined' ? '' : $("#csSiteExpryDate").val();
    var csSiteOtherInfo = typeof $("#csSiteOtherInfo").val() === 'undefined' ? '' : $("#csSiteOtherInfo").val();
    var csSiteBankNm = typeof $("#csSiteBankNm").val() === 'undefined' ? '' : $("#csSiteBankNm").val();
    var csSiteBrnchNm = typeof $("#csSiteBrnchNm").val() === 'undefined' ? '' : $("#csSiteBrnchNm").val();
    var csSiteAcntNum = typeof $("#csSiteAcntNum").val() === 'undefined' ? '' : $("#csSiteAcntNum").val();
    var csSiteCrncy = typeof $("#csSiteCrncy").val() === 'undefined' ? '' : $("#csSiteCrncy").val();
    var csSiteSwftCode = typeof $("#csSiteSwftCode").val() === 'undefined' ? '' : $("#csSiteSwftCode").val();
    var csSiteIbanCode = typeof $("#csSiteIbanCode").val() === 'undefined' ? '' : $("#csSiteIbanCode").val();
    var errMsg = "";
    if (csSiteName.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Site name cannot be empty!</span></p>';
    }
    if (csSiteCntctPrsn.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Contact Person cannot be empty!</span></p>';
    }
    if (Number(sbmtdCstmrSpplrID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Linked Customer/Supplier cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Item',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Site...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                /*$('#myFormsModalNrml').modal('hide');*/
                getOneCstmrSitesForm(sbmtdSiteID, 'ReloadDialog');
            }
        }
    });
    var formData = new FormData();
    formData.append('grp', 25);
    formData.append('typ', 1);
    formData.append('pg', 4);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 601);
    formData.append('sbmtdCstmrSpplrID', sbmtdCstmrSpplrID);
    formData.append('sbmtdSiteID', sbmtdSiteID);
    formData.append('csSiteName', csSiteName);
    formData.append('csSiteDesc', csSiteDesc);
    formData.append('isCsSiteEnbld', isCsSiteEnbld);
    formData.append('csSiteBllngAddress', csSiteBllngAddress);
    formData.append('csSiteShpngAddress', csSiteShpngAddress);
    formData.append('csSiteCntctPrsn', csSiteCntctPrsn);
    formData.append('csSiteCntctNos', csSiteCntctNos);
    formData.append('csSiteEmailAdrs', csSiteEmailAdrs);
    formData.append('csSiteWthTxCodeID', csSiteWthTxCodeID);
    formData.append('csSiteDscntCodeID', csSiteDscntCodeID);
    formData.append('csSiteCountry', csSiteCountry);
    formData.append('csSiteIDType', csSiteIDType);
    formData.append('csSiteIDNum', csSiteIDNum);
    formData.append('csSiteDateIsd', csSiteDateIsd);
    formData.append('csSiteExpryDate', csSiteExpryDate);
    formData.append('csSiteOtherInfo', csSiteOtherInfo);
    formData.append('csSiteBankNm', csSiteBankNm);
    formData.append('csSiteBrnchNm', csSiteBrnchNm);
    formData.append('csSiteAcntNum', csSiteAcntNum);
    formData.append('csSiteCrncy', csSiteCrncy);
    formData.append('csSiteSwftCode', csSiteSwftCode);
    formData.append('csSiteIbanCode', csSiteIbanCode);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        sbmtdSiteID = result.cstmrsiteid;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delCstmrsSites(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var siteNm = '';
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SiteID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SiteID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        siteNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Site?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Site?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Site?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Site...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 601,
                                    pKeyID: pKeyID,
                                    siteNm: siteNm
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

function delCstmrsSrvcOffrd(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var stockNm = '';
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SrvcNm').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SrvcNm').val();
        var $tds = $('#' + rowIDAttrb).find('td');
    }
    var dialog = bootbox.confirm({
        title: 'Delete Service?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Service?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Service?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Service...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right", "0px");
                    }
                });
                dialog1.init(function () {
                    setTimeout(function () {
                        $("#" + rowIDAttrb).remove();
                        dialog1.find('.bootbox-body').html('Row Removed Successfully!<br/><span style="font-weight:bold;font-style:italic;color:red;">NB: You must click on SAVE for CHANGES to take EFFECT!</span>');
                    }, 500);
                    $('#allOtherInputData99').val(0);
                });
            }
        }
    });
}

function getAllVmsItms(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allVmsItmsSrchFor").val() === 'undefined' ? '%' : $("#allVmsItmsSrchFor").val();
    var srchIn = typeof $("#allVmsItmsSrchIn").val() === 'undefined' ? 'Both' : $("#allVmsItmsSrchIn").val();
    var pageNo = typeof $("#allVmsItmsPageNo").val() === 'undefined' ? 1 : $("#allVmsItmsPageNo").val();
    var limitSze = typeof $("#allVmsItmsDsplySze").val() === 'undefined' ? 10 : $("#allVmsItmsDsplySze").val();
    var sortBy = typeof $("#allVmsItmsSortBy").val() === 'undefined' ? '' : $("#allVmsItmsSortBy").val();
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

function enterKeyFuncAllVmsItms(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllVmsItms(actionText, slctr, linkArgs);
    }
}

function getOneVmsItmsForm(pKeyID, actionTxt)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=25&typ=1&pg=4&vtyp=401&srcMenu=VMS&sbmtdItmID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', actionTxt, 'Item (ID:' + pKeyID + ')', 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        $('#vaultItmStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(function () {
            $('[data-toggle="tabajxinvitms"]').off('click');
            $('[data-toggle="tabajxinvitms"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                if (targ.indexOf('invItmsGnrl') >= 0) {
                    $('#invItmsGnrl').removeClass('hideNotice');
                    $('#invItmsGl').addClass('hideNotice');
                    $('#invItmsStores').addClass('hideNotice');
                    $('#invItmsExtInfo').addClass('hideNotice');
                    $('#invItmsUOM').addClass('hideNotice');
                    $('#invItmsDrugLbl').addClass('hideNotice');
                    $('#invItmsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('invItmsGl') >= 0) {
                    $('#invItmsGnrl').addClass('hideNotice');
                    $('#invItmsGl').removeClass('hideNotice');
                    $('#invItmsStores').addClass('hideNotice');
                    $('#invItmsExtInfo').addClass('hideNotice');
                    $('#invItmsUOM').addClass('hideNotice');
                    $('#invItmsDrugLbl').addClass('hideNotice');
                    $('#invItmsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('invItmsStores') >= 0) {
                    $('#invItmsGnrl').addClass('hideNotice');
                    $('#invItmsGl').addClass('hideNotice');
                    $('#invItmsStores').removeClass('hideNotice');
                    $('#invItmsExtInfo').addClass('hideNotice');
                    $('#invItmsUOM').addClass('hideNotice');
                    $('#invItmsDrugLbl').addClass('hideNotice');
                    $('#invItmsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('invItmsExtInfo') >= 0) {
                    $('#invItmsGnrl').addClass('hideNotice');
                    $('#invItmsGl').addClass('hideNotice');
                    $('#invItmsStores').addClass('hideNotice');
                    $('#invItmsExtInfo').removeClass('hideNotice');
                    $('#invItmsUOM').addClass('hideNotice');
                    $('#invItmsDrugLbl').addClass('hideNotice');
                    $('#invItmsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('invItmsUOM') >= 0) {
                    $('#invItmsGnrl').addClass('hideNotice');
                    $('#invItmsGl').addClass('hideNotice');
                    $('#invItmsStores').addClass('hideNotice');
                    $('#invItmsExtInfo').addClass('hideNotice');
                    $('#invItmsUOM').removeClass('hideNotice');
                    $('#invItmsDrugLbl').addClass('hideNotice');
                    $('#invItmsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('invItmsDrugLbl') >= 0) {
                    $('#invItmsGnrl').addClass('hideNotice');
                    $('#invItmsGl').addClass('hideNotice');
                    $('#invItmsStores').addClass('hideNotice');
                    $('#invItmsExtInfo').addClass('hideNotice');
                    $('#invItmsUOM').addClass('hideNotice');
                    $('#invItmsDrugLbl').removeClass('hideNotice');
                    $('#invItmsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('invItmsDrugIntrctns') >= 0) {
                    $('#invItmsGnrl').addClass('hideNotice');
                    $('#invItmsGl').addClass('hideNotice');
                    $('#invItmsStores').addClass('hideNotice');
                    $('#invItmsExtInfo').addClass('hideNotice');
                    $('#invItmsUOM').addClass('hideNotice');
                    $('#invItmsDrugLbl').addClass('hideNotice');
                    $('#invItmsDrugIntrctns').removeClass('hideNotice');
                }
            });
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
        $('#allOtherInputData99').val(0);
        $('#myFormsModalNrml').off('hidden.bs.modal');
        $('#myFormsModalNrml').one('hidden.bs.modal', function (e) {
            $('#myFormsModalNrmlTitle').html('');
            $('#myFormsModalNrmlBody').html('');
            getAllVmsItms('clear', '#allmodules', 'grp=25&typ=1&pg=4&vtyp=4&srcMenu=VMS');
            $(e.currentTarget).unbind();
        });
    });
}

function saveVmsItmsForm()
{
    var sbmtdItmID = typeof $("#sbmtdItmID").val() === 'undefined' ? '-1' : $("#sbmtdItmID").val();
    var invItemNm = typeof $("#invItemNm").val() === 'undefined' ? '' : $("#invItemNm").val();
    var invItemDesc = typeof $("#invItemDesc").val() === 'undefined' ? '' : $("#invItemDesc").val();
    var invTmpltID = typeof $("#invTmpltID").val() === 'undefined' ? '-1' : $("#invTmpltID").val();
    var invItemType = typeof $("#invItemType").val() === 'undefined' ? '' : $("#invItemType").val();
    var invItemCtgry = typeof $("#invItemCtgry").val() === 'undefined' ? '' : $("#invItemCtgry").val();
    var invItemCtgryID = typeof $("#invItemCtgryID").val() === 'undefined' ? '-1' : $("#invItemCtgryID").val();
    var invBaseUom = typeof $("#invBaseUom").val() === 'undefined' ? '' : $("#invBaseUom").val();
    var invBaseUomID = typeof $("#invBaseUomID").val() === 'undefined' ? '-1' : $("#invBaseUomID").val();
    var invTxCodeName = typeof $("#invTxCodeName").val() === 'undefined' ? '' : $("#invTxCodeName").val();
    var invTxCodeID = typeof $("#invTxCodeID").val() === 'undefined' ? '-1' : $("#invTxCodeID").val();
    var invDscntCodeName = typeof $("#invDscntCodeName").val() === 'undefined' ? '' : $("#invDscntCodeName").val();
    var invDscntCodeID = typeof $("#invDscntCodeID").val() === 'undefined' ? '-1' : $("#invDscntCodeID").val();
    var invChrgCodeName = typeof $("#invChrgCodeName").val() === 'undefined' ? '' : $("#invChrgCodeName").val();
    var invChrgCodeID = typeof $("#invChrgCodeID").val() === 'undefined' ? '-1' : $("#invChrgCodeID").val();
    var isItmEnbld = typeof $("input[name='isItmEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var isPlnngEnbld = typeof $("input[name='isPlnngEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var autoLoadInVms = typeof $("input[name='autoLoadInVms']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var invMinItmQty = typeof $("#invMinItmQty").val() === 'undefined' ? '0' : $("#invMinItmQty").val();
    var invMaxItmQty = typeof $("#invMaxItmQty").val() === 'undefined' ? '0' : $("#invMaxItmQty").val();
    var invValCrncyNm = typeof $("#invValCrncyNm").val() === 'undefined' ? '' : $("#invValCrncyNm").val();
    var invValCrncyID = typeof $("#invValCrncyID").val() === 'undefined' ? '-1' : $("#invValCrncyID").val();
    var invPriceLessTax = typeof $("#invPriceLessTax").val() === 'undefined' ? '0' : $("#invPriceLessTax").val();
    var invSllngPrice = typeof $("#invSllngPrice").val() === 'undefined' ? '0' : $("#invSllngPrice").val();
    var invNwPrftAmnt = typeof $("#invNwPrftAmnt").val() === 'undefined' ? '0' : $("#invNwPrftAmnt").val();
    var invNewSllngPrice = typeof $("#invNewSllngPrice").val() === 'undefined' ? '0' : $("#invNewSllngPrice").val();
    var invPrftMrgnPrcnt = typeof $("#invPrftMrgnPrcnt").val() === 'undefined' ? '0' : $("#invPrftMrgnPrcnt").val();
    var invPrftMrgnAmnt = typeof $("#invPrftMrgnAmnt").val() === 'undefined' ? '0' : $("#invPrftMrgnAmnt").val();
    var invAssetAcntNm = typeof $("#invAssetAcntNm").val() === 'undefined' ? '' : $("#invAssetAcntNm").val();
    var invAssetAcntID = typeof $("#invAssetAcntID").val() === 'undefined' ? '-1' : $("#invAssetAcntID").val();
    var invCogsAcntID = typeof $("#invCogsAcntID").val() === 'undefined' ? '-1' : $("#invCogsAcntID").val();
    var invCogsAcntNm = typeof $("#invCogsAcntNm").val() === 'undefined' ? '' : $("#invCogsAcntNm").val();
    var invSRvnuAcntID = typeof $("#invSRvnuAcntID").val() === 'undefined' ? '-1' : $("#invSRvnuAcntID").val();
    var invSRvnuAcntNm = typeof $("#invSRvnuAcntNm").val() === 'undefined' ? '' : $("#invSRvnuAcntNm").val();
    var invSRetrnAcntID = typeof $("#invSRetrnAcntID").val() === 'undefined' ? '-1' : $("#invSRetrnAcntID").val();
    var invSRetrnAcntNm = typeof $("#invSRetrnAcntNm").val() === 'undefined' ? '' : $("#invSRetrnAcntNm").val();
    var invPRetrnAcntID = typeof $("#invPRetrnAcntID").val() === 'undefined' ? '-1' : $("#invPRetrnAcntID").val();
    var invPRetrnAcntNm = typeof $("#invPRetrnAcntNm").val() === 'undefined' ? '' : $("#invPRetrnAcntNm").val();
    var invExpnsAcntID = typeof $("#invExpnsAcntID").val() === 'undefined' ? '-1' : $("#invExpnsAcntID").val();
    var invExpnsAcntNm = typeof $("#invExpnsAcntNm").val() === 'undefined' ? '' : $("#invExpnsAcntNm").val();
    var invItmOthrDesc = typeof $("#invItmOthrDesc").val() === 'undefined' ? '' : $("#invItmOthrDesc").val();
    var invItmExtrInfo = typeof $("#invItmExtrInfo").val() === 'undefined' ? '' : $("#invItmExtrInfo").val();
    var invItmGnrcNm = typeof $("#invItmGnrcNm").val() === 'undefined' ? '' : $("#invItmGnrcNm").val();
    var invItmTradeNm = typeof $("#invItmTradeNm").val() === 'undefined' ? '' : $("#invItmTradeNm").val();
    var invItmUslDsge = typeof $("#invItmUslDsge").val() === 'undefined' ? '' : $("#invItmUslDsge").val();
    var invItmMaxDsge = typeof $("#invItmMaxDsge").val() === 'undefined' ? '' : $("#invItmMaxDsge").val();
    var invItmCntrIndctns = typeof $("#invItmCntrIndctns").val() === 'undefined' ? '' : $("#invItmCntrIndctns").val();
    var invItmFoodIntrctns = typeof $("#invItmFoodIntrctns").val() === 'undefined' ? '' : $("#invItmFoodIntrctns").val();
    var errMsg = "";
    if (invItemNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Item name cannot be empty!</span></p>';
    }
    if (invItemDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Item Description cannot be empty!</span></p>';
    }
    if (Number(invItemCtgryID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Category cannot be empty!</span></p>';
    }
    if (Number(invBaseUomID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Base UOM cannot be empty!</span></p>';
    }
    if (Number(invValCrncyID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Item\'s Value Currency cannot be empty!</span></p>';
    }
    if (Number(invAssetAcntID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Asset Account cannot be empty!</span></p>';
    }
    /*if (Number(invSRetrnAcntID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Sales Return Account cannot be empty!</span></p>';
    }*/
    if (Number(invSRvnuAcntID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Sales Revenue Account cannot be empty!</span></p>';
    }
   /* if (Number(invPRetrnAcntID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Purchase Return Account cannot be empty!</span></p>';
    }*/
    if (Number(invExpnsAcntID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">GL Account cannot be empty!</span></p>';
    }
    /*if (Number(invCogsAcntID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Cost of Sales Account cannot be empty!</span></p>';
    }*/
    var slctdItmStores = "";
    var slctdItmUOMs = "";
    var slctdItmIntrctns = "";
    var isVld = true;
    $('#oneItmStoresTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_StockID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnStoreID = typeof $('#' + rowPrfxNm + rndmNum + '_StoreID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_StoreID').val();
                    if (Number(lnStoreID.replace(/[^-?0-9\.]/g, '')) <= 0)
                    {
                        /*Do Nothing*/
                    } else {
                        if (isVld === true)
                        {
                            slctdItmStores = slctdItmStores + $('#' + rowPrfxNm + rndmNum + '_StockID').val() + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_StoreID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_ShlvNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_ShlvIDs').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_StrtDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_EndDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    $('#oneItmUOMsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnUomID = typeof $('#' + rowPrfxNm + rndmNum + '_UOMID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_UOMID').val();
                    if (Number(lnUomID.replace(/[^-?0-9\.]/g, '')) <= 0)
                    {
                        /*Do Nothing*/
                    } else {
                        if (isVld === true)
                        {
                            slctdItmUOMs = slctdItmUOMs + $('#' + rowPrfxNm + rndmNum + '_LineID').val() + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_UOMID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_CnvrsnFctr').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_SortOrdr').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_SllgnPrce').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_PriceLsTx').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    $('#oneItmDrugIntrctnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnDrugID = typeof $('#' + rowPrfxNm + rndmNum + '_DrugID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_DrugID').val();
                    if (Number(lnDrugID.replace(/[^-?0-9\.]/g, '')) <= 0)
                    {
                        /*Do Nothing*/
                    } else {
                        var lnDrgIntrctn = typeof $('#' + rowPrfxNm + rndmNum + '_Intrctn').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_Intrctn').val();
                        if (lnDrgIntrctn.trim() === "")
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Intrctn').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Intrctn').removeClass('rho-error');
                        }
                        var lnDrgActn = typeof $('#' + rowPrfxNm + rndmNum + '_Action').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_Action').val();
                        if (lnDrgActn.trim() === "")
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Action').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Action').removeClass('rho-error');
                        }
                        if (isVld === true)
                        {
                            slctdItmIntrctns = slctdItmIntrctns + $('#' + rowPrfxNm + rndmNum + '_LineID').val() + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_DrugID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_Intrctn').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_Action').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Item',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Item...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                /*$('#myFormsModalNrml').modal('hide');*/
                getOneVmsItmsForm(sbmtdItmID, 'ReloadDialog');
            }
        }
    });
    var formData = new FormData();
    formData.append('daItemPicture', $('#daItemPicture')[0].files[0]);
    formData.append('grp', 25);
    formData.append('typ', 1);
    formData.append('pg', 4);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 4);
    formData.append('sbmtdItmID', sbmtdItmID);
    formData.append('invItemNm', invItemNm);
    formData.append('invItemDesc', invItemDesc);
    formData.append('invTmpltID', invTmpltID);
    formData.append('invItemType', invItemType);
    formData.append('invItemCtgryID', invItemCtgryID);
    formData.append('invBaseUomID', invBaseUomID);
    formData.append('invTxCodeID', invTxCodeID);
    formData.append('invDscntCodeID', invDscntCodeID);
    formData.append('invChrgCodeID', invChrgCodeID);
    formData.append('isItmEnbld', isItmEnbld);
    formData.append('isPlnngEnbld', isPlnngEnbld);
    formData.append('autoLoadInVms', autoLoadInVms);
    formData.append('invMinItmQty', invMinItmQty);
    formData.append('invMaxItmQty', invMaxItmQty);
    formData.append('invValCrncyID', invValCrncyID);
    formData.append('invPriceLessTax', invPriceLessTax);
    formData.append('invSllngPrice', invSllngPrice);
    formData.append('invNwPrftAmnt', invNwPrftAmnt);
    formData.append('invNewSllngPrice', invNewSllngPrice);
    formData.append('invPrftMrgnPrcnt', invPrftMrgnPrcnt);
    formData.append('invPrftMrgnAmnt', invPrftMrgnAmnt);
    formData.append('invAssetAcntID', invAssetAcntID);
    formData.append('invCogsAcntID', invCogsAcntID);
    formData.append('invSRvnuAcntID', invSRvnuAcntID);
    formData.append('invSRetrnAcntID', invSRetrnAcntID);
    formData.append('invPRetrnAcntID', invPRetrnAcntID);
    formData.append('invExpnsAcntID', invExpnsAcntID);
    formData.append('invItmOthrDesc', invItmOthrDesc);
    formData.append('invItmExtrInfo', invItmExtrInfo);
    formData.append('invItmGnrcNm', invItmGnrcNm);
    formData.append('invItmTradeNm', invItmTradeNm);
    formData.append('invItmUslDsge', invItmUslDsge);
    formData.append('invItmMaxDsge', invItmMaxDsge);
    formData.append('invItmCntrIndctns', invItmCntrIndctns);
    formData.append('invItmFoodIntrctns', invItmFoodIntrctns);
    formData.append('slctdItmStores', slctdItmStores);
    formData.append('slctdItmUOMs', slctdItmUOMs);
    formData.append('slctdItmIntrctns', slctdItmIntrctns);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        sbmtdItmID = result.itemid;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delVmsItms(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var itemNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_ItemID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_ItemID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        itemNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Item?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Item?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Item?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Item...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 4,
                                    sbmtdItmID: pKeyID,
                                    itemNm: itemNm
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

function delVmsItmStores(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var stockNm = '';
    if (typeof $('#' + rowPrfxNm + rndmNum + '_StockID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_StockID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        stockNm = $('#' + rowPrfxNm + rndmNum + '_StoreNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Item Store?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Item Store?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Item Store?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Item Store...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 401,
                                    sbmtdStckID: pKeyID,
                                    stockNm: stockNm
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

function delVmsItmUoMs(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var uomNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        uomNm = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        //$.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Item UOM?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Item UOM?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Item UOM?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Item UOM...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 402,
                                    sbmtdLineID: pKeyID,
                                    uomNm: uomNm
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

function delVmsItmDrgIntrctns(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var drugNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        drugNm = $('#' + rowPrfxNm + rndmNum + '_DrugNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Drug Interaction?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Drug Interaction?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Drug Interaction?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Drug Interaction...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 403,
                                    sbmtdLineID: pKeyID,
                                    drugNm: drugNm
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

function getAllVmsCgsV(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allVmsCgsSrchFor").val() === 'undefined' ? '%' : $("#allVmsCgsSrchFor").val();
    var srchIn = typeof $("#allVmsCgsSrchIn").val() === 'undefined' ? 'Both' : $("#allVmsCgsSrchIn").val();
    var pageNo = typeof $("#allVmsCgsPageNo").val() === 'undefined' ? 1 : $("#allVmsCgsPageNo").val();
    var limitSze = typeof $("#allVmsCgsDsplySze").val() === 'undefined' ? 10 : $("#allVmsCgsDsplySze").val();
    var sortBy = typeof $("#allVmsCgsSortBy").val() === 'undefined' ? '' : $("#allVmsCgsSortBy").val();
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

function enterKeyFuncAllVmsCgsV(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllVmsCgs(actionText, slctr, linkArgs);
    }
}

function getOneVmsCgFormV(pKeyID, srcApp, inVltID, sbmtdSiteID)
{
    if (typeof inVltID === 'undefined' || inVltID === null)
    {
        inVltID = -1;
    }
    if (typeof srcApp === 'undefined' || srcApp === null)
    {
        srcApp = 'Cages';
    }
    var lnkArgs = 'grp=25&typ=1&pg=4&vtyp=301&srcMenu=VMS&sbmtdCageID=' + pKeyID + '&cageVltID=' + inVltID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', 'Vault Cage (ID:' + pKeyID + ')', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        $('#mcfTillStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#allOtherInputData99').val(0);
        if (inVltID > 0 && srcApp !== 'branches') {
            $('#cageVltNm').val($('#vaultNm').val());
            $('#cageVltID').val(inVltID);
        }
        $('#myFormsModalx').off('hidden.bs.modal');
        $('#myFormsModalx').one('hidden.bs.modal', function (e) {
            $('#myFormsModalxTitle').html('');
            $('#myFormsModalxBody').html('');
            if (srcApp === 'Cages') {
                getAllVmsCgsV('clear', '#allmodules', 'grp=25&typ=1&pg=4&vtyp=3&srcMenu=VMS');
            } else if (srcApp === 'branches') {
                getAllVaultCages('', '#allmodules', 'grp=25&typ=1&pg=1&vtyp=2&sbmtdStoreID=' + inVltID + '&sbmtdSiteID=' + sbmtdSiteID + '&srcMenu=VMS');
            }
            $(e.currentTarget).unbind();
        });
        $('#mngrsWithdrawlLmt').ForceNumericOnly();
        $('#mngrsDepositLmt').ForceNumericOnly();
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
}

function saveVmsCgFormV()
{
    var cageShelfNm = typeof $("#cageShelfNm").val() === 'undefined' ? '' : $("#cageShelfNm").val();
    var cageLineID = typeof $("#cageLineID").val() === 'undefined' ? '-1' : $("#cageLineID").val();
    var cageShelfID = typeof $("#cageShelfID").val() === 'undefined' ? '-1' : $("#cageShelfID").val();
    var cageDesc = typeof $("#cageDesc").val() === 'undefined' ? '' : $("#cageDesc").val();
    var cageVltID = typeof $("#cageVltID").val() === 'undefined' ? '-1' : $("#cageVltID").val();
    var cageOwnersCstmrID = typeof $("#cageOwnersCstmrID").val() === 'undefined' ? '-1' : $("#cageOwnersCstmrID").val();
    var lnkdGLAccountID = typeof $("#lnkdGLAccountID1").val() === 'undefined' ? '-1' : $("#lnkdGLAccountID1").val();
    var grpType = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    var allwdGroupNm = typeof $("#allwdGroupNm").val() === 'undefined' ? '' : $("#allwdGroupNm").val();
    var allwdGroupID = typeof $("#allwdGroupID").val() === 'undefined' ? '' : $("#allwdGroupID").val();
    var cageMngrsPrsnID = typeof $("#cageMngrsPrsnID").val() === 'undefined' ? '-1' : $("#cageMngrsPrsnID").val();
    var mngrsWithdrawlLmt = typeof $("#mngrsWithdrawlLmt").val() === 'undefined' ? '0' : $("#mngrsWithdrawlLmt").val();
    var mngrsDepositLmt = typeof $("#mngrsDepositLmt").val() === 'undefined' ? '0' : $("#mngrsDepositLmt").val();
    var dfltItemType = typeof $("#dfltItemType").val() === 'undefined' ? '' : $("#dfltItemType").val();
    var dfltItemState = typeof $("#dfltItemState").val() === 'undefined' ? '' : $("#dfltItemState").val();
    var isCageEnbld = typeof $("input[name='isCageEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var errMsg = "";
    if (cageShelfNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Vault Cage name cannot be empty!</span></p>';
    }
    if (cageDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Vault Cage Description cannot be empty!</span></p>';
    }
    if (dfltItemType.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Default Item Type cannot be empty!</span></p>';
    }
    if (dfltItemState.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Default Item State cannot be empty!</span></p>';
    }
    if (Number(cageVltID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Linked Vault cannot be empty!</span></p>';
    }
    if (Number(lnkdGLAccountID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Linked GL Account cannot be empty!</span></p>';
    }
    /*if (Number(cageMngrsPrsnID.replace(/[^-?0-9\.]/g, '')) <= 0)
     {
     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
     'font-weight:bold;color:red;">Cage Manager cannot be empty!</span></p>';
     }*/
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Cage/Till',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Cage/Till...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                $('#myFormsModalx').modal('hide');
            }
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
                    grp: 25,
                    typ: 1,
                    pg: 4,
                    q: 'UPDATE',
                    actyp: 3,
                    cageShelfNm: cageShelfNm,
                    cageLineID: cageLineID,
                    cageShelfID: cageShelfID,
                    cageDesc: cageDesc,
                    cageVltID: cageVltID,
                    cageOwnersCstmrID: cageOwnersCstmrID,
                    lnkdGLAccountID: lnkdGLAccountID,
                    grpType: grpType,
                    allwdGroupNm: allwdGroupNm,
                    allwdGroupID: allwdGroupID,
                    cageMngrsPrsnID: cageMngrsPrsnID,
                    mngrsWithdrawlLmt: mngrsWithdrawlLmt,
                    mngrsDepositLmt: mngrsDepositLmt,
                    dfltItemType: dfltItemType,
                    dfltItemState: dfltItemState,
                    isCageEnbld: isCageEnbld
                },
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delVmsCgV(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var cageNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        cageNm = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Cage/Till?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Cage/Till?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Cage/Till?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Cage/Till...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 3,
                                    lineID: pKeyID,
                                    cageNm: cageNm
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

function grpTypMcfChangeV()
{
    var lovChkngElementVal = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    lovNm = "";
    if (lovChkngElementVal === "Everyone")
    {
        $('#allwdGroupName').attr("disabled", "true");
        $('#allwdGroupName').val("");
        $('#allwdGroupID').val("-1");
        $('#groupNameLbl').attr("disabled", "true");
    } else
    {
        $('#allwdGroupName').removeAttr("disabled");
        $('#allwdGroupName').val("");
        $('#allwdGroupID').val("-1");
        $('#groupNameLbl').removeAttr("disabled");
    }
}

function chckMyTillPosV(loadOption, lnkArgs) {
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', loadOption, 'My Till Position', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        loadScript("app/mcf/mcf2.js?v=" + jsFilesVrsn, function () {
            $(document).ready(function () {
                $('[data-toggle="tooltip"]').tooltip();
                var table1;
                if (!$.fn.DataTable.isDataTable('#allCageItemsTable')) {
                    table1 = $('#allCageItemsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#allCageItemsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#allCageItemsForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
                var table2;
                if (!$.fn.DataTable.isDataTable('#allCageItemTrnsTable')) {
                    table2 = $('#allCageItemTrnsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#allCageItemTrnsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#allCageItemsTable tbody').off('click');
                $('#allCageItemsTable tbody').off('mouseenter');
                $('#allCageItemsTable tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        table1.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    var rndmNum = $(this).attr('id').split("_")[1];
                    var pKeyID = typeof $('#allCageItemsRow' + rndmNum + '_ItemID').val() === 'undefined' ? '%' : $('#allCageItemsRow' + rndmNum + '_ItemID').val();
                    var cageID = typeof $('#allCageItemsRow' + rndmNum + '_CageID').val() === 'undefined' ? '%' : $('#allCageItemsRow' + rndmNum + '_CageID').val();
                    getOneCageTrnsForm(pKeyID, cageID, 5);
                });
                $('#allCageItemsTable tbody')
                        .on('mouseenter', 'tr', function () {
                            if ($(this).hasClass('highlight')) {
                                $(this).removeClass('highlight');
                            } else {
                                table1.$('tr.highlight').removeClass('highlight');
                                $(this).addClass('highlight');
                            }
                        });
                $('#myFormsModalLg').off('hidden.bs.modal');
                $(function () {
                    $('[data-toggle="tabajxcage"]').off('click');
                    $('[data-toggle="tabajxcage"]').click(function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        var targ = $this.attr('href');
                        var dttrgt = $this.attr('data-rhodata');
                        var linkArgs = 'grp=25&typ=1' + dttrgt;
                        return openATab(targ, linkArgs);
                    });
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
            });
        });
    });
}

function getAllVmsVlts(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allVmsVltsSrchFor").val() === 'undefined' ? '%' : $("#allVmsVltsSrchFor").val();
    var srchIn = typeof $("#allVmsVltsSrchIn").val() === 'undefined' ? 'Both' : $("#allVmsVltsSrchIn").val();
    var pageNo = typeof $("#allVmsVltsPageNo").val() === 'undefined' ? 1 : $("#allVmsVltsPageNo").val();
    var limitSze = typeof $("#allVmsVltsDsplySze").val() === 'undefined' ? 10 : $("#allVmsVltsDsplySze").val();
    var sortBy = typeof $("#allVmsVltsSortBy").val() === 'undefined' ? '' : $("#allVmsVltsSortBy").val();
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

function enterKeyFuncAllVmsVlts(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllVmsVlts(actionText, slctr, linkArgs);
    }
}

function getOneVmsVltsForm(pKeyID, actionTxt, frmWhere, sbmtdSiteID)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    if (typeof frmWhere === 'undefined' || frmWhere === null)
    {
        frmWhere = 'FROMSTP';
    }
    if (typeof sbmtdSiteID === 'undefined' || sbmtdSiteID === null)
    {
        sbmtdSiteID = -1;
    }
    var lnkArgs = 'grp=25&typ=1&pg=4&vtyp=201&srcMenu=VMS&sbmtdVltID=' + pKeyID + '&sbmtdSiteID=' + sbmtdSiteID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', actionTxt, 'Vault (ID:' + pKeyID + ')', 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        $('#vaultStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(function () {
            $('[data-toggle="tabajxstores"]').off('click');
            $('[data-toggle="tabajxstores"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                if (targ.indexOf('Cage') >= 0) {
                    $('#vaultUsers').addClass('hideNotice');
                    $('#vaultCages').removeClass('hideNotice');
                } else {
                    $('#vaultCages').addClass('hideNotice');
                    $('#vaultUsers').removeClass('hideNotice');
                }
            });
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
        $('#allOtherInputData99').val(0);
        $('#myFormsModalNrml').off('hidden.bs.modal');
        $('#myFormsModalNrml').one('hidden.bs.modal', function (e) {
            $('#myFormsModalNrmlTitle').html('');
            $('#myFormsModalNrmlBody').html('');
            if (frmWhere === 'FROMBRNCH') {
                getAllBrchVaults('', '#allmodules', 'grp=25&typ=1&pg=1&vtyp=1&sbmtdSiteID=' + sbmtdSiteID + '&srcMenu=VMS');
            } else {
                getAllVmsVlts('clear', '#allmodules', 'grp=25&typ=1&pg=4&vtyp=2&srcMenu=VMS');
            }
            $(e.currentTarget).unbind();
        });
    });
}

function saveVmsVltsForm(sbmtdSiteID)
{
    if (typeof sbmtdSiteID === 'undefined' || sbmtdSiteID === null)
    {
        sbmtdSiteID = -1;
    }
    var vaultNm = typeof $("#vaultNm").val() === 'undefined' ? '' : $("#vaultNm").val();
    var sbmtdVltID = typeof $("#sbmtdVltID").val() === 'undefined' ? '-1' : $("#sbmtdVltID").val();
    var vaultDesc = typeof $("#vaultDesc").val() === 'undefined' ? '' : $("#vaultDesc").val();
    var vaultAddress = typeof $("#vaultAddress").val() === 'undefined' ? '' : $("#vaultAddress").val();
    var lnkdSiteID = typeof $("#lnkdSiteID").val() === 'undefined' ? '-1' : $("#lnkdSiteID").val();
    var lnkdGLAccountID = typeof $("#lnkdGLAccountID").val() === 'undefined' ? '-1' : $("#lnkdGLAccountID").val();
    var grpType = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    var allwdGroupNm = typeof $("#allwdGroupNm").val() === 'undefined' ? '' : $("#allwdGroupNm").val();
    var allwdGroupID = typeof $("#allwdGroupID").val() === 'undefined' ? '' : $("#allwdGroupID").val();
    var vltMngrsPrsnID = typeof $("#vltMngrsPrsnID").val() === 'undefined' ? '-1' : $("#vltMngrsPrsnID").val();
    var isVltEnbld = typeof $("input[name='isVltEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var isSalesAllwd = typeof $("input[name='isSalesAllwd']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var errMsg = "";
    if (vaultNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Vault name cannot be empty!</span></p>';
    }
    if (vaultDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Vault Description cannot be empty!</span></p>';
    }
    if (Number(lnkdSiteID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Linked Site cannot be empty!</span></p>';
    }
    if (Number(lnkdGLAccountID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Linked GL Account cannot be empty!</span></p>';
    }
    var slctdUsers = "";
    var isVld = true;
    $('#oneStoreUsersTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_UsrID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var userID = typeof $('#' + rowPrfxNm + rndmNum + '_UsrID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_UsrID').val();
                    var lnID = typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_LineID').val();
                    if (Number(userID.replace(/[^-?0-9\.]/g, '')) <= 0)
                    {
                        /*Do Nothing if (lnkdItmID > 0)*/
                    } else {
                        var lnStartDte = typeof $('#' + rowPrfxNm + rndmNum + '_StrtDte').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_StrtDte').val();
                        if (lnStartDte.trim() === "")
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_StrtDte').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_StrtDte').removeClass('rho-error');
                        }
                        if (isVld === true)
                        {
                            slctdUsers = slctdUsers + lnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + userID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnStartDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + $('#' + rowPrfxNm + rndmNum + '_EndDte').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Vault',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Vault...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                /*$('#myFormsModalNrml').modal('hide');*/
                if (sbmtdSiteID > 0) {
                    getOneVmsVltsForm(sbmtdVltID, 'ReloadDialog', 'FROMBRNCH', sbmtdSiteID);
                } else {
                    getOneVmsVltsForm(sbmtdVltID, 'ReloadDialog');
                }
            }
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
                    grp: 25,
                    typ: 1,
                    pg: 4,
                    q: 'UPDATE',
                    actyp: 2,
                    vaultNm: vaultNm,
                    sbmtdVltID: sbmtdVltID,
                    vaultDesc: vaultDesc,
                    vaultAddress: vaultAddress,
                    lnkdSiteID: lnkdSiteID,
                    lnkdGLAccountID: lnkdGLAccountID,
                    grpType: grpType,
                    allwdGroupNm: allwdGroupNm,
                    allwdGroupID: allwdGroupID,
                    vltMngrsPrsnID: vltMngrsPrsnID,
                    isVltEnbld: isVltEnbld,
                    isSalesAllwd: isSalesAllwd,
                    slctdUsers: slctdUsers
                },
                success: function (result) {
                    //console.log(result);
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        sbmtdVltID = result.vltid;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delVmsVlts(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var vltNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_VltID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_VltID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        vltNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Vault?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Vault?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Vault?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Vault...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 2,
                                    sbmtdVltID: pKeyID,
                                    vltNm: vltNm
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

function delVmsVltUsrs(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var usrNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        usrNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Vault User?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Vault User?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Vault User?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Vault User...Please Wait...</p>',
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
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 2,
                                    lineID: pKeyID,
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

function getAllVmsLocs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allVmsLocsSrchFor").val() === 'undefined' ? '%' : $("#allVmsLocsSrchFor").val();
    var srchIn = typeof $("#allVmsLocsSrchIn").val() === 'undefined' ? 'Both' : $("#allVmsLocsSrchIn").val();
    var pageNo = typeof $("#allVmsLocsPageNo").val() === 'undefined' ? 1 : $("#allVmsLocsPageNo").val();
    var limitSze = typeof $("#allVmsLocsDsplySze").val() === 'undefined' ? 10 : $("#allVmsLocsDsplySze").val();
    var sortBy = typeof $("#allVmsLocsSortBy").val() === 'undefined' ? '' : $("#allVmsLocsSortBy").val();
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

function enterKeyFuncAllVmsLocs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllVmsLocs(actionText, slctr, linkArgs);
    }
}

function calcAllExpnsTrnsTtl() {
    var ttlAmount = 0;
    var ttlRwAmount = 0;
    var rate1;
    $('#oneVmsExpnsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var prfxName = $(el).attr('id').split("_")[0];
                ttlRwAmount = ($("#" + prfxName + rndmNum + "_TtlVal").val() + ',').replace(/,/g, "");
                rate1 = ($("#" + prfxName + rndmNum + "_ExchgRate").val() + ',').replace(/,/g, "");
                ttlAmount = ttlAmount + (Number(ttlRwAmount) * Number(rate1));
            }
        }
    });

    $('#myCptrdExpnsTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdExpnsTtlVal').val(ttlAmount.toFixed(2));
}