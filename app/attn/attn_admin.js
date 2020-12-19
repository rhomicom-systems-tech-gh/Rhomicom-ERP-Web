
function getAttnVenue(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#attnVenueSrchFor").val() === 'undefined' ? '%' : $("#attnVenueSrchFor").val();
    var srchIn = typeof $("#attnVenueSrchIn").val() === 'undefined' ? 'Both' : $("#attnVenueSrchIn").val();
    var pageNo = typeof $("#attnVenuePageNo").val() === 'undefined' ? 1 : $("#attnVenuePageNo").val();
    var limitSze = typeof $("#attnVenueDsplySze").val() === 'undefined' ? 10 : $("#attnVenueDsplySze").val();
    var sortBy = typeof $("#attnVenueSortBy").val() === 'undefined' ? '' : $("#attnVenueSortBy").val();
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

function enterKeyFuncAttnVenue(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttnVenue(actionText, slctr, linkArgs);
    }
}

function getOneAttnVenueForm(tmpltID, vwtype)
{
    var lnkArgs = 'grp=16&typ=1&pg=5&vtyp=' + vwtype + '&sbmtdVenueID=' + tmpltID;
    doAjaxWthCallBck(lnkArgs, 'attnVenueDetailInfo', 'PasteDirect', '', '', '', function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function saveAttnVenueForm()
{
    var attnVenueID = typeof $("#attnVenueID").val() === 'undefined' ? '-1' : $("#attnVenueID").val();
    var attnVenueNoOfPrsns = typeof $("#attnVenueNoOfPrsns").val() === 'undefined' ? '0' : $("#attnVenueNoOfPrsns").val();
    var attnVenueName = typeof $("#attnVenueName").val() === 'undefined' ? '' : $("#attnVenueName").val();
    var attnVenueDesc = typeof $("#attnVenueDesc").val() === 'undefined' ? '' : $("#attnVenueDesc").val();
    var attnVenueType = typeof $("#attnVenueType").val() === 'undefined' ? '' : $("#attnVenueType").val();
    var attnVenueIsEnbld = typeof $("input[name='attnVenueIsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='attnVenueIsEnbld']:checked").val();
    var errMsg = "";
    if (attnVenueName.trim() === '' || attnVenueType.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Code Name and Type cannot be empty!</span></p>';
    }
    var isVld = true;
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Attendance Venue',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Attendance Venue...Please Wait...</p>',
        callback: function () {
        }
    });
    var formData = new FormData();
    formData.append('grp', 16);
    formData.append('typ', 1);
    formData.append('pg', 5);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('attnVenueID', attnVenueID);
    formData.append('attnVenueName', attnVenueName);
    formData.append('attnVenueDesc', attnVenueDesc);
    formData.append('attnVenueType', attnVenueType);
    formData.append('attnVenueNoOfPrsns', attnVenueNoOfPrsns);
    formData.append('attnVenueIsEnbld', attnVenueIsEnbld);

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
                success: function (data) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            attnVenueID = data.attnVenueID;
                            getAttnVenue('', '#allmodules', 'grp=16&typ=1&pg=5&vtyp=0');
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delAttnVenue(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_CodeID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_CodeID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_CodeNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Attendance Venue?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Attendance Venue?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Attendance Venue?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Attendance Venue...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 5,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function getAttnRegstrSrch(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#attnRegstrSrchSrchFor").val() === 'undefined' ? '%' : $("#attnRegstrSrchSrchFor").val();
    var srchIn = typeof $("#attnRegstrSrchSrchIn").val() === 'undefined' ? 'Both' : $("#attnRegstrSrchSrchIn").val();
    var pageNo = typeof $("#attnRegstrSrchPageNo").val() === 'undefined' ? 1 : $("#attnRegstrSrchPageNo").val();
    var limitSze = typeof $("#attnRegstrSrchDsplySze").val() === 'undefined' ? 10 : $("#attnRegstrSrchDsplySze").val();
    var sortBy = typeof $("#attnRegstrSrchSortBy").val() === 'undefined' ? '' : $("#attnRegstrSrchSortBy").val();
    var qStrtDte = typeof $("#attnRegstrSrchStrtDate").val() === 'undefined' ? '' : $("#attnRegstrSrchStrtDate").val();
    var qEndDte = typeof $("#attnRegstrSrchEndDate").val() === 'undefined' ? '' : $("#attnRegstrSrchEndDate").val();
    var qShwPstdOnly = $('#attnRegstrSrchShwPstdOnly:checked').length > 0;
    var qShwIntrfc = $('#attnRegstrSrchShwIntrfc:checked').length > 0;
    var qLowVal = typeof $("#attnRegstrSrchLowVal").val() === 'undefined' ? 0 : $("#attnRegstrSrchLowVal").val();
    var qHighVal = typeof $("#attnRegstrSrchHighVal").val() === 'undefined' ? 0 : $("#attnRegstrSrchHighVal").val();
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
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwPstdOnly=" + qShwPstdOnly + "&qShwIntrfc=" + qShwIntrfc +
            "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte + "&qLowVal=" + qLowVal + "&qHighVal=" + qHighVal;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAttnRegstrSrch(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttnRegstrSrch(actionText, slctr, linkArgs);
    }
}

function getAttnDfltAcnts(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#attnDfltAcntsSrchFor").val() === 'undefined' ? '%' : $("#attnDfltAcntsSrchFor").val();
    var srchIn = typeof $("#attnDfltAcntsSrchIn").val() === 'undefined' ? 'Both' : $("#attnDfltAcntsSrchIn").val();
    var pageNo = typeof $("#attnDfltAcntsPageNo").val() === 'undefined' ? 1 : $("#attnDfltAcntsPageNo").val();
    var limitSze = typeof $("#attnDfltAcntsDsplySze").val() === 'undefined' ? 10 : $("#attnDfltAcntsDsplySze").val();
    var sortBy = typeof $("#attnDfltAcntsSortBy").val() === 'undefined' ? '' : $("#attnDfltAcntsSortBy").val();
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

function enterKeyFuncAttnDfltAcnts(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttnDfltAcnts(actionText, slctr, linkArgs);
    }
}

function saveAttnDfltAcntsForm()
{
    var errMsg = "";
    var isVld = true;
    var slctdDfltAcnts = "";
    $('#attnDfltAcntsHdrsTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = typeof $('#attnDfltAcntsHdrsRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#attnDfltAcntsHdrsRow' + rndmNum + '_TrnsLnID').val();
                var ln_ChrgAcntID = typeof $('#attnDfltAcntsHdrsRow' + rndmNum + '_ChrgAcntID').val() === 'undefined' ? '-1' : $('#attnDfltAcntsHdrsRow' + rndmNum + '_ChrgAcntID').val();
                var ln_BalsAcntID = typeof $('#attnDfltAcntsHdrsRow' + rndmNum + '_BalsAcntID').val() === 'undefined' ? '-1' : $('#attnDfltAcntsHdrsRow' + rndmNum + '_BalsAcntID').val();
                var ln_CtgryNm = typeof $('#attnDfltAcntsHdrsRow' + rndmNum + '_CtgryNm').val() === 'undefined' ? '' : $('#attnDfltAcntsHdrsRow' + rndmNum + '_CtgryNm').val();
                var ln_IncrsDcrs1 = typeof $('#attnDfltAcntsHdrsRow' + rndmNum + '_IncrsDcrs1').val() === 'undefined' ? '' : $('#attnDfltAcntsHdrsRow' + rndmNum + '_IncrsDcrs1').val();
                var ln_ChrgAcntNm = typeof $('#attnDfltAcntsHdrsRow' + rndmNum + '_ChrgAcntNm').val() === 'undefined' ? '' : $('#attnDfltAcntsHdrsRow' + rndmNum + '_ChrgAcntNm').val();
                var ln_IncrsDcrs2 = typeof $('#attnDfltAcntsHdrsRow' + rndmNum + '_IncrsDcrs2').val() === 'undefined' ? '' : $('#attnDfltAcntsHdrsRow' + rndmNum + '_IncrsDcrs2').val();
                var ln_BalsAcntNm = typeof $('#attnDfltAcntsHdrsRow' + rndmNum + '_BalsAcntNm').val() === 'undefined' ? '' : $('#attnDfltAcntsHdrsRow' + rndmNum + '_BalsAcntNm').val();
                if (ln_CtgryNm.trim() !== '') {
                    if (ln_IncrsDcrs1.trim() === '' || ln_IncrsDcrs2.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Increase/Decrease for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#attnDfltAcntsHdrsRow' + rndmNum + '_IncrsDcrs1').addClass('rho-error');
                        $('#attnDfltAcntsHdrsRow' + rndmNum + '_IncrsDcrs2').addClass('rho-error');
                    } else {
                        $('#attnDfltAcntsHdrsRow' + rndmNum + '_IncrsDcrs1').removeClass('rho-error');
                        $('#attnDfltAcntsHdrsRow' + rndmNum + '_IncrsDcrs2').removeClass('rho-error');
                    }
                    if (Number(ln_ChrgAcntID.replace(/[^-?0-9\.]/g, '')) <= 0)
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Charge Account for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#attnDfltAcntsHdrsRow' + rndmNum + '_ChrgAcntNm').addClass('rho-error');
                    } else {
                        $('#attnDfltAcntsHdrsRow' + rndmNum + '_ChrgAcntNm').removeClass('rho-error');
                    }
                    if (Number(ln_BalsAcntID.replace(/[^-?0-9\.]/g, '')) <= 0)
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Balance Account for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#attnDfltAcntsHdrsRow' + rndmNum + '_BalsAcntNm').addClass('rho-error');
                    } else {
                        $('#attnDfltAcntsHdrsRow' + rndmNum + '_BalsAcntNm').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdDfltAcnts = slctdDfltAcnts
                                + ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_ChrgAcntID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_BalsAcntID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_CtgryNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_IncrsDcrs1.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_IncrsDcrs2.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
        title: 'Save Default Accounts',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Default Accounts...Please Wait...</p>',
        callback: function () {
        }
    });
    var formData = new FormData();
    formData.append('grp', 16);
    formData.append('typ', 1);
    formData.append('pg', 8);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('slctdDfltAcnts', slctdDfltAcnts);

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
                success: function (data) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            getAttnDfltAcnts('', '#allmodules', 'grp=16&typ=1&pg=8&vtyp=0');
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function insertNewAttnDfltAcntsRows(tableElmntID, position, inptHtml)
{
    for (var i = 0; i < 1; i++) {
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
    $(".jbDetRfDc").focus(function () {
        $(this).select();
    });
    $(".jbDetDesc").focus(function () {
        $(this).select();
    });
    $(".jbDetDbt").focus(function () {
        $(this).select();
    });
    $(".jbDetCrdt").focus(function () {
        $(this).select();
    });
    $(".jbDetFuncRate").focus(function () {
        $(this).select();
    });
    $(".jbDetAccRate").focus(function () {
        $(this).select();
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
                $tds.eq(0).html(cntr);
            }
        }
    });
}

function delAttnDfltAcnts(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_CmplntTyp').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Default Account?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Default Account?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Default Account?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Default Account...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function getAttnEvntStp(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#attnEvntStpSrchFor").val() === 'undefined' ? '%' : $("#attnEvntStpSrchFor").val();
    var srchIn = typeof $("#attnEvntStpSrchIn").val() === 'undefined' ? 'Both' : $("#attnEvntStpSrchIn").val();
    var pageNo = typeof $("#attnEvntStpPageNo").val() === 'undefined' ? 1 : $("#attnEvntStpPageNo").val();
    var limitSze = typeof $("#attnEvntStpDsplySze").val() === 'undefined' ? 10 : $("#attnEvntStpDsplySze").val();
    var sortBy = typeof $("#attnEvntStpSortBy").val() === 'undefined' ? '' : $("#attnEvntStpSortBy").val();
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

function enterKeyFuncAttnEvntStp(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttnEvntStp(actionText, slctr, linkArgs);
    }
}

function getOneAttnEvntStpForm(pKeyID, vwtype, actionTxt)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=16&typ=1&pg=4&vtyp=' + vwtype + '&sbmtdAttnEvntStpID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Event Setup Details (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="tabajxevntstp"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=12&typ=1' + dttrgt;
            $(targ + 'tab').tab('show');
            if (targ.indexOf('evntStpExtraInfo') >= 0) {
                $('#addNwAttnEvntStpMtrcsBtn').addClass('hideNotice');
                $('#addNwAttnEvntStpAccntsBtn').addClass('hideNotice');
                $('#addNwAttnEvntStpPricesBtn').removeClass('hideNotice');
            } else if (targ.indexOf('evntStpDfltAcnts') >= 0) {
                $('#addNwAttnEvntStpMtrcsBtn').addClass('hideNotice');
                $('#addNwAttnEvntStpAccntsBtn').removeClass('hideNotice');
                $('#addNwAttnEvntStpPricesBtn').addClass('hideNotice');
            } else {
                $('#addNwAttnEvntStpMtrcsBtn').removeClass('hideNotice');
                $('#addNwAttnEvntStpAccntsBtn').addClass('hideNotice');
                $('#addNwAttnEvntStpPricesBtn').addClass('hideNotice');
            }
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
        $('#allOtherInputData99').val('0');
        $('#oneAttnEvntStpEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            getAttnEvntStp('', '#allmodules', 'grp=16&typ=1&pg=4&vtyp=0');
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#oneAttnEvntStpSmryLinesTable')) {
            var table1 = $('#oneAttnEvntStpSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAttnEvntStpSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#oneAttnEvntStpSmry1Table')) {
            var table1 = $('#oneAttnEvntStpSmry1Table').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAttnEvntStpSmry1Table').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $(".jbDetDesc").focus(function () {
            $(this).select();
        });
        $(".jbDetDbt").focus(function () {
            $(this).select();
        });
        $(".jbDetCrdt").focus(function () {
            $(this).select();
        });
        $(".jbDetAccRate").off('focus');
        $(".jbDetAccRate").focus(function () {
            $(this).select();
        });
        $('#oneAttnEvntStpSmryLinesTable tr').off('click');
        $('#oneAttnEvntStpSmryLinesTable tr').click(function () {
            var rowIndex = $('#oneAttnEvntStpSmryLinesTable tr').index(this);
            $('#allOtherInputData99').val(rowIndex);
        });
    });
}

function getOneAttnActvtyRsltsForm(pKeyID, vwtype, actionTxt)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=16&typ=1&pg=9&vtyp=' + vwtype + '&sbmtdAttnEventID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Event Activities/Results (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
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
        $('#allOtherInputData99').val('0');
        $('#oneAttnEvntStpEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            getAttnEvntStp('', '#allmodules', 'grp=16&typ=1&pg=4&vtyp=0');
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#oneAttnEvntStpSmryLinesTable')) {
            var table1 = $('#oneAttnEvntStpSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAttnEvntStpSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#oneAttnEvntStpSmry1Table')) {
            var table1 = $('#oneAttnEvntStpSmry1Table').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAttnEvntStpSmry1Table').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $(".jbDetDesc").focus(function () {
            $(this).select();
        });
        $(".jbDetDbt").focus(function () {
            $(this).select();
        });
        $(".jbDetCrdt").focus(function () {
            $(this).select();
        });
        $(".jbDetAccRate").off('focus');
        $(".jbDetAccRate").focus(function () {
            $(this).select();
        });
        $('#oneAttnEvntStpSmryLinesTable tr').off('click');
        $('#oneAttnEvntStpSmryLinesTable tr').click(function () {
            var rowIndex = $('#oneAttnEvntStpSmryLinesTable tr').index(this);
            $('#allOtherInputData99').val(rowIndex);
        });
    });
}

function saveAttnEvntStpForm()
{
    var sbmtdAttnEvntStpID = typeof $("#sbmtdAttnEvntStpID").val() === 'undefined' ? '-1' : $("#sbmtdAttnEvntStpID").val();
    var attnEvntStpGrpID = typeof $("#attnEvntStpGrpID").val() === 'undefined' ? '-1' : $("#attnEvntStpGrpID").val();
    var attnEvntStpVnuID = typeof $("#attnEvntStpVnuID").val() === 'undefined' ? '-1' : $("#attnEvntStpVnuID").val();
    var attnEvntStpPrsnID = typeof $("#attnEvntStpPrsnID").val() === 'undefined' ? '-1' : $("#attnEvntStpPrsnID").val();
    var attnEvntStpFirmID = typeof $("#attnEvntStpFirmID").val() === 'undefined' ? '-1' : $("#attnEvntStpFirmID").val();
    var attnEvntStpFirmSiteID = typeof $("#attnEvntStpFirmSiteID").val() === 'undefined' ? '-1' : $("#attnEvntStpFirmSiteID").val();
    var attnEvntStpName = typeof $("#attnEvntStpName").val() === 'undefined' ? '' : $("#attnEvntStpName").val();
    var attnEvntStpDesc = typeof $("#attnEvntStpDesc").val() === 'undefined' ? '' : $("#attnEvntStpDesc").val();
    var attnEvntStpMtrcLOV = typeof $("#attnEvntStpMtrcLOV").val() === 'undefined' ? '' : $("#attnEvntStpMtrcLOV").val();
    var attnEvntStpScoresLOV = typeof $("#attnEvntStpScoresLOV").val() === 'undefined' ? '' : $("#attnEvntStpScoresLOV").val();
    var attnEvntStpSltPrty = typeof $("#attnEvntStpSltPrty").val() === 'undefined' ? '0' : $("#attnEvntStpSltPrty").val();
    var attnEvntStpIsEnbld = typeof $("input[name='attnEvntStpIsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var attnEvntStpEvntTypVal = typeof $("#attnEvntStpEvntTypVal").val() === 'undefined' ? '' : $("#attnEvntStpEvntTypVal").val();
    var attnEvntStpClsfctn = typeof $("#attnEvntStpClsfctn").val() === 'undefined' ? '' : $("#attnEvntStpClsfctn").val();
    var attnEvntStpTtlSsnMins = typeof $("#attnEvntStpTtlSsnMins").val() === 'undefined' ? '0' : $("#attnEvntStpTtlSsnMins").val();
    var attnEvntStpCntnsSsnMins = typeof $("#attnEvntStpCntnsSsnMins").val() === 'undefined' ? '0' : $("#attnEvntStpCntnsSsnMins").val();
    var attnEvntStpGrpTyp = typeof $("#attnEvntStpGrpTyp").val() === 'undefined' ? '' : $("#attnEvntStpGrpTyp").val();
    var attnEvntStpGrpName = typeof $("#attnEvntStpGrpName").val() === 'undefined' ? '' : $("#attnEvntStpGrpName").val();
    var attnEvntStpVnuNm = typeof $("#attnEvntStpVnuNm").val() === 'undefined' ? '' : $("#attnEvntStpVnuNm").val();
    var attnEvntStpPrsnNm = typeof $("#attnEvntStpPrsnNm").val() === 'undefined' ? '' : $("#attnEvntStpPrsnNm").val();
    var attnEvntStpFirmNm = typeof $("#attnEvntStpFirmNm").val() === 'undefined' ? '' : $("#attnEvntStpFirmNm").val();
    var errMsg = "";
    var isVld = true;
    var slctdResultMetrics = "";
    var slctdPriceCtgrys = "";
    var slctdDfltAccnts = "";
    $('#oneAttnEvntStpSmryLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = typeof $('#oneAttnEvntStpSmryRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#oneAttnEvntStpSmryRow' + rndmNum + '_TrnsLnID').val();
                var ln_LineDesc = typeof $('#oneAttnEvntStpSmryRow' + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#oneAttnEvntStpSmryRow' + rndmNum + '_LineDesc').val();
                var ln_RsltType = typeof $('#oneAttnEvntStpSmryRow' + rndmNum + '_RsltType').val() === 'undefined' ? '' : $('#oneAttnEvntStpSmryRow' + rndmNum + '_RsltType').val();
                var ln_Cmmnt = typeof $('#oneAttnEvntStpSmryRow' + rndmNum + '_Cmmnt').val() === 'undefined' ? '' : $('#oneAttnEvntStpSmryRow' + rndmNum + '_Cmmnt').val();
                var ln_Query = typeof $('#oneAttnEvntStpSmryRow' + rndmNum + '_Query').val() === 'undefined' ? '' : $('#oneAttnEvntStpSmryRow' + rndmNum + '_Query').val();
                var ln_IsEnbld = typeof $("input[name='oneAttnEvntStpSmryRow" + rndmNum + "_IsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
                if (ln_LineDesc.trim() !== '') {
                    if (ln_RsltType.trim() === '' || ln_Cmmnt.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Result Type and Description for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#attnEvntStpHdrsRow' + rndmNum + '_RsltType').addClass('rho-error');
                        $('#attnEvntStpHdrsRow' + rndmNum + '_Cmmnt').addClass('rho-error');
                    } else {
                        $('#attnEvntStpHdrsRow' + rndmNum + '_RsltType').removeClass('rho-error');
                        $('#attnEvntStpHdrsRow' + rndmNum + '_Cmmnt').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdResultMetrics = slctdResultMetrics
                                + ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_RsltType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_Cmmnt.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_Query.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_IsEnbld.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });
    $('#oneAttnEvntStpPricesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = typeof $('#oneAttnEvntStpPricesRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#oneAttnEvntStpPricesRow' + rndmNum + '_TrnsLnID').val();
                var ln_CtgryNm = typeof $('#oneAttnEvntStpPricesRow' + rndmNum + '_CtgryNm').val() === 'undefined' ? '' : $('#oneAttnEvntStpPricesRow' + rndmNum + '_CtgryNm').val();
                var ln_ItemID = typeof $('#oneAttnEvntStpPricesRow' + rndmNum + '_ItemID').val() === 'undefined' ? '-1' : $('#oneAttnEvntStpPricesRow' + rndmNum + '_ItemID').val();
                var ln_ItemNm = typeof $('#oneAttnEvntStpPricesRow' + rndmNum + '_ItemNm').val() === 'undefined' ? '' : $('#oneAttnEvntStpPricesRow' + rndmNum + '_ItemNm').val();
                var ln_PrcLsTx = typeof $('#oneAttnEvntStpPricesRow' + rndmNum + '_PrcLsTx').val() === 'undefined' ? '0' : $('#oneAttnEvntStpPricesRow' + rndmNum + '_PrcLsTx').val();
                var ln_SellPrc = typeof $('#oneAttnEvntStpPricesRow' + rndmNum + '_SellPrc').val() === 'undefined' ? '0' : $('#oneAttnEvntStpPricesRow' + rndmNum + '_SellPrc').val();
                var ln_IsEnbld = typeof $("input[name='oneAttnEvntStpPricesRow" + rndmNum + "_IsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
                if (ln_CtgryNm.trim() !== '') {
                    if (Number(ln_ItemID.replace(/[^-?0-9\.]/g, '')) <= 0)
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Item for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#attnEvntStpHdrsRow' + rndmNum + '_ItemNm').addClass('rho-error');
                    } else {
                        $('#attnEvntStpHdrsRow' + rndmNum + '_ItemNm').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdPriceCtgrys = slctdPriceCtgrys
                                + ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_CtgryNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_ItemID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_ItemNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_PrcLsTx.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_SellPrc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_IsEnbld.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });
    $('#oneAttnEvntStpDfltAcntsTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = typeof $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_TrnsLnID').val();
                var ln_CtgryNm = typeof $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_CtgryNm').val() === 'undefined' ? '' : $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_CtgryNm').val();
                var ln_IncrsDcrs1 = typeof $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_IncrsDcrs1').val() === 'undefined' ? '' : $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_IncrsDcrs1').val();
                var ln_AcntID1 = typeof $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_AcntID1').val() === 'undefined' ? '-1' : $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_AcntID1').val();
                var ln_AcntNm1 = typeof $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_AcntNm1').val() === 'undefined' ? '' : $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_AcntNm1').val();
                var ln_IncrsDcrs2 = typeof $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_IncrsDcrs2').val() === 'undefined' ? '' : $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_IncrsDcrs2').val();
                var ln_AcntID2 = typeof $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_AcntID2').val() === 'undefined' ? '-1' : $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_AcntID2').val();
                var ln_AcntNm2 = typeof $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_AcntNm2').val() === 'undefined' ? '' : $('#oneAttnEvntStpDfltAcntsRow' + rndmNum + '_AcntNm2').val();
                if (ln_CtgryNm.trim() !== '') {
                    if (Number(ln_AcntID1.replace(/[^-?0-9\.]/g, '')) <= 0 || Number(ln_AcntID2.replace(/[^-?0-9\.]/g, '')) <= 0)
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Charge and Balancing Accounts for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#attnEvntStpHdrsRow' + rndmNum + '_AcntNm1').addClass('rho-error');
                        $('#attnEvntStpHdrsRow' + rndmNum + '_AcntNm2').addClass('rho-error');
                    } else {
                        $('#attnEvntStpHdrsRow' + rndmNum + '_AcntNm1').removeClass('rho-error');
                        $('#attnEvntStpHdrsRow' + rndmNum + '_AcntNm2').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdDfltAccnts = slctdDfltAccnts
                                + ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_CtgryNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_IncrsDcrs1.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_AcntID1.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_AcntNm1.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_IncrsDcrs2.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_AcntID2.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_AcntNm2.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
        title: 'Save Event Setup',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Event Setup...Please Wait...</p>',
        callback: function () {
        }
    });
    var formData = new FormData();
    formData.append('grp', 16);
    formData.append('typ', 1);
    formData.append('pg', 4);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('sbmtdAttnEvntStpID', sbmtdAttnEvntStpID);
    formData.append('attnEvntStpGrpID', attnEvntStpGrpID);
    formData.append('attnEvntStpVnuID', attnEvntStpVnuID);
    formData.append('attnEvntStpPrsnID', attnEvntStpPrsnID);
    formData.append('attnEvntStpFirmID', attnEvntStpFirmID);
    formData.append('attnEvntStpFirmSiteID', attnEvntStpFirmSiteID);
    formData.append('attnEvntStpName', attnEvntStpName);
    formData.append('attnEvntStpDesc', attnEvntStpDesc);
    formData.append('attnEvntStpMtrcLOV', attnEvntStpMtrcLOV);
    formData.append('attnEvntStpScoresLOV', attnEvntStpScoresLOV);
    formData.append('attnEvntStpSltPrty', attnEvntStpSltPrty);
    formData.append('attnEvntStpIsEnbld', attnEvntStpIsEnbld);
    formData.append('attnEvntStpEvntTypVal', attnEvntStpEvntTypVal);
    formData.append('attnEvntStpClsfctn', attnEvntStpClsfctn);
    formData.append('attnEvntStpTtlSsnMins', attnEvntStpTtlSsnMins);
    formData.append('attnEvntStpCntnsSsnMins', attnEvntStpCntnsSsnMins);
    formData.append('attnEvntStpGrpTyp', attnEvntStpGrpTyp);
    formData.append('attnEvntStpGrpName', attnEvntStpGrpName);
    formData.append('attnEvntStpVnuNm', attnEvntStpVnuNm);
    formData.append('attnEvntStpPrsnNm', attnEvntStpPrsnNm);
    formData.append('attnEvntStpFirmNm', attnEvntStpFirmNm);
    formData.append('slctdResultMetrics', slctdResultMetrics);
    formData.append('slctdPriceCtgrys', slctdPriceCtgrys);
    formData.append('slctdDfltAccnts', slctdDfltAccnts);

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
                success: function (data) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            sbmtdAttnEvntStpID = data.sbmtdAttnEvntStpID;
                            getOneAttnEvntStpForm(sbmtdAttnEvntStpID, 3, 'ReloadDialog');
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function insertNewAttnEvntStpRows(tableElmntID, position, inptHtml)
{
    for (var i = 0; i < 1; i++) {
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
    $(".jbDetRfDc").focus(function () {
        $(this).select();
    });
    $(".jbDetDesc").focus(function () {
        $(this).select();
    });
    $(".jbDetDbt").focus(function () {
        $(this).select();
    });
    $(".jbDetCrdt").focus(function () {
        $(this).select();
    });
    $(".jbDetFuncRate").focus(function () {
        $(this).select();
    });
    $(".jbDetAccRate").focus(function () {
        $(this).select();
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
                $tds.eq(0).html(cntr);
            }
        }
    });
}

function delAttnEvntStp(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_TrnsLnNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Event Setup?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Event Setup?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Complaint/Observation?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Complaint/Observation...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function getAttnSpplrInfo()
{
    var scmSalesInvcCstmrID = typeof $("#attnEvntStpFirmID").val() === 'undefined' ? '-1' : $("#attnEvntStpFirmID").val();
    var scmSalesInvcCstmrSiteID = typeof $("#attnEvntStpFirmSiteID").val() === 'undefined' ? '-1' : $("#attnEvntStpFirmSiteID").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp', 12);
        formData.append('typ', 1);
        formData.append('pg', 1);
        formData.append('q', 'VIEW');
        formData.append('vtyp', 4);
        formData.append('hotlChckinDocSpnsrID', scmSalesInvcCstmrID);
        formData.append('hotlChckinDocSpnsrSiteID', scmSalesInvcCstmrSiteID);
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                /*$('#hotlChckinDocSpnsrSite').val(data.scmSalesInvcCstmrSiteNm);*/
                $('#attnEvntStpFirmSiteID').val(data.scmSalesInvcCstmrSiteID);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function delAttnEvntStpDetLn(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_LineDesc').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Result Metric?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Result Metric?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Result Metric?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Result Metric...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function delAttnEvntStpPriceLn(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_CtgryNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Price Category?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Price Category?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Price Category?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Price Category...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 3,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function delAttnEvntStpDfltAcntsLn(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_CtgryNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Default Account?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Default Account?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Default Account?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Default Account...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 4,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function onTimeDivChange(srcType)
{
    var attnTmeMajTmDivType = typeof $("#attnTmeMajTmDivType").val() === 'undefined' ? '' : $("#attnTmeMajTmDivType").val();
    var attnTmeMinTmDivType = typeof $("#attnTmeMinTmDivType").val() === 'undefined' ? '' : $("#attnTmeMinTmDivType").val();
    var attnTmeTblSlotDrtn = typeof $("#attnTmeTblSlotDrtn").val() === 'undefined' ? '30' : $("#attnTmeTblSlotDrtn").val();

    var attnTmeMajTmStrtValHdn = typeof $("#attnTmeMajTmStrtValHdn").val() === 'undefined' ? '' : $("#attnTmeMajTmStrtValHdn").val();
    var attnTmeMajTmEndValHdn = typeof $("#attnTmeMajTmEndValHdn").val() === 'undefined' ? '' : $("#attnTmeMajTmEndValHdn").val();
    var attnTmeMinTmStrtValHdn = typeof $("#attnTmeMinTmStrtValHdn").val() === 'undefined' ? '' : $("#attnTmeMinTmStrtValHdn").val();
    var attnTmeMinTmEndValHdn = typeof $("#attnTmeMinTmEndValHdn").val() === 'undefined' ? '' : $("#attnTmeMinTmEndValHdn").val();

    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp', 16);
        formData.append('typ', 1);
        formData.append('pg', 3);
        formData.append('q', 'VIEW');
        formData.append('vtyp', 4);
        if (srcType === 'MAJOR') {
            formData.append('attnTmeMajTmDivType', attnTmeMajTmDivType);
        } else {
            formData.append('attnTmeMinTmDivType', attnTmeMinTmDivType);
        }
        formData.append('attnTmeTblSlotDrtn', attnTmeTblSlotDrtn);
        formData.append('attnTmeSrcType', srcType);
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
                    var options = data.TimeOptions.split(";");
                    var options1 = data.TimeOptions.split(";");
                    if (srcType === 'MAJOR') {
                        $("#attnTmeMajTmStrtVal").empty();
                        $("#attnTmeMajTmEndVal").empty();
                    } else {
                        $("#attnTmeMinTmStrtVal").empty();
                        $("#attnTmeMinTmEndVal").empty();
                    }
                    for (var i = 0; i < options.length; i++) {
                        var defaultSlctd = false;
                        var isSlctd = false;
                        var defaultSlctd1 = false;
                        var isSlctd1 = false;
                        if (srcType === 'MAJOR') {
                            if (attnTmeMajTmStrtValHdn === options[i]) {
                                defaultSlctd = true;
                                isSlctd = true;
                            }
                            if (attnTmeMajTmEndValHdn === options1[i]) {
                                defaultSlctd1 = true;
                                isSlctd1 = true;
                            }
                        } else {
                            if (attnTmeMinTmStrtValHdn === options[i]) {
                                defaultSlctd = true;
                                isSlctd = true;
                            }
                            if (attnTmeMinTmEndValHdn === options1[i]) {
                                defaultSlctd1 = true;
                                isSlctd1 = true;
                            }
                        }
                        var o = new Option(options[i], options[i], defaultSlctd, isSlctd);
                        var o1 = new Option(options1[i], options1[i], defaultSlctd1, isSlctd1);
/// jquerify the DOM object 'o' so we can use the html method
                        $(o).html(options[i]);
                        $(o1).html(options1[i]);
                        if (srcType === 'MAJOR') {
                            $("#attnTmeMajTmStrtVal").append(o);
                            $("#attnTmeMajTmEndVal").append(o1);
                        } else {
                            $("#attnTmeMinTmStrtVal").append(o);
                            $("#attnTmeMinTmEndVal").append(o1);
                        }
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function getAttnTmeTbl(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#attnTmeTblSrchFor").val() === 'undefined' ? '%' : $("#attnTmeTblSrchFor").val();
    var srchIn = typeof $("#attnTmeTblSrchIn").val() === 'undefined' ? 'Both' : $("#attnTmeTblSrchIn").val();
    var pageNo = typeof $("#attnTmeTblPageNo").val() === 'undefined' ? 1 : $("#attnTmeTblPageNo").val();
    var limitSze = typeof $("#attnTmeTblDsplySze").val() === 'undefined' ? 10 : $("#attnTmeTblDsplySze").val();
    var sortBy = typeof $("#attnTmeTblSortBy").val() === 'undefined' ? '' : $("#attnTmeTblSortBy").val();
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

function enterKeyFuncAttnTmeTbl(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttnTmeTbl(actionText, slctr, linkArgs);
    }
}

function getAttnTmeTblDet(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#attnTmeTblDetSrchFor").val() === 'undefined' ? '%' : $("#attnTmeTblDetSrchFor").val();
    var srchIn = typeof $("#attnTmeTblDetSrchIn").val() === 'undefined' ? 'Both' : $("#attnTmeTblDetSrchIn").val();
    var pageNo = typeof $("#attnTmeTblDetPageNo").val() === 'undefined' ? 1 : $("#attnTmeTblDetPageNo").val();
    var limitSze = typeof $("#attnTmeTblDetDsplySze").val() === 'undefined' ? 10 : $("#attnTmeTblDetDsplySze").val();
    var sortBy = typeof $("#attnTmeTblDetSortBy").val() === 'undefined' ? '' : $("#attnTmeTblDetSortBy").val();
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
    $("#attnTmeTblDetPageNo").val(pageNo);
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAttnTmeTblDet(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttnTmeTblDet(actionText, slctr, linkArgs);
    }
}

function getOneAttnTmeTblForm(tmpltID, vwtype)
{
    var lnkArgs = 'grp=16&typ=1&pg=3&vtyp=' + vwtype + '&sbmtdAttnTmeTblID=' + tmpltID;
    doAjaxWthCallBck(lnkArgs, 'attnTmeTblDetailInfo', 'PasteDirect', '', '', '', function () {
        if (!$.fn.DataTable.isDataTable('#oneAttnTmeTblSmryLinesTable')) {
            var table2 = $('#oneAttnTmeTblSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAttnTmeTblSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#attnTmeTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });

        $(".jbDetRfDc").focus(function () {
            $(this).select();
        });
        $(".jbDetDesc").focus(function () {
            $(this).select();
        });
        $(".jbDetDbt").focus(function () {
            $(this).select();
        });
        $(".jbDetCrdt").focus(function () {
            $(this).select();
        });
    });
}

function saveAttnTmeTblForm()
{
    var attnTmeTblID = typeof $("#attnTmeTblID").val() === 'undefined' ? '-1' : $("#attnTmeTblID").val();
    var attnTmeTblName = typeof $("#attnTmeTblName").val() === 'undefined' ? '' : $("#attnTmeTblName").val();
    var attnTmeTblDesc = typeof $("#attnTmeTblDesc").val() === 'undefined' ? '' : $("#attnTmeTblDesc").val();
    var attnTmeTblEvntType = typeof $("#attnTmeTblEvntType").val() === 'undefined' ? '' : $("#attnTmeTblEvntType").val();
    var attnTmeTblSlotDrtn = typeof $("#attnTmeTblSlotDrtn").val() === 'undefined' ? '0' : $("#attnTmeTblSlotDrtn").val();
    var attnTmeMajTmDivType = typeof $("#attnTmeMajTmDivType").val() === 'undefined' ? '' : $("#attnTmeMajTmDivType").val();
    var attnTmeMajTmStrtVal = typeof $("#attnTmeMajTmStrtVal").val() === 'undefined' ? '' : $("#attnTmeMajTmStrtVal").val();
    var attnTmeMajTmEndVal = typeof $("#attnTmeMajTmEndVal").val() === 'undefined' ? '' : $("#attnTmeMajTmEndVal").val();
    var attnTmeMinTmDivType = typeof $("#attnTmeMinTmDivType").val() === 'undefined' ? '' : $("#attnTmeMinTmDivType").val();
    var attnTmeMinTmStrtVal = typeof $("#attnTmeMinTmStrtVal").val() === 'undefined' ? '' : $("#attnTmeMinTmStrtVal").val();
    var attnTmeMinTmEndVal = typeof $("#attnTmeMinTmEndVal").val() === 'undefined' ? '' : $("#attnTmeMinTmEndVal").val();

    var attnTmeTblIsEnbld = typeof $("input[name='attnTmeTblIsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='attnTmeTblIsEnbld']:checked").val();
    var errMsg = "";
    if (attnTmeTblName.trim() === '' || attnTmeTblEvntType.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Time Table Name and Classification cannot be empty!</span></p>';
    }
    var isVld = true;
    var slctdAttnTimesLns = "";
    $('#oneAttnTmeTblSmryLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TmeTblLnID = typeof $('#oneAttnTmeTblSmryRow' + rndmNum + '_TmeTblLnID').val() === 'undefined' ? '-1' : $('#oneAttnTmeTblSmryRow' + rndmNum + '_TmeTblLnID').val();
                var ln_EvntID = typeof $('#oneAttnTmeTblSmryRow' + rndmNum + '_EvntID').val() === 'undefined' ? '-1' : $('#oneAttnTmeTblSmryRow' + rndmNum + '_EvntID').val();
                var ln_VnuID = typeof $('#oneAttnTmeTblSmryRow' + rndmNum + '_VnuID').val() === 'undefined' ? '-1' : $('#oneAttnTmeTblSmryRow' + rndmNum + '_VnuID').val();
                var ln_HostID = typeof $('#oneAttnTmeTblSmryRow' + rndmNum + '_HostID').val() === 'undefined' ? '-1' : $('#oneAttnTmeTblSmryRow' + rndmNum + '_HostID').val();
                var ln_EvntNm = typeof $('#oneAttnTmeTblSmryRow' + rndmNum + '_EvntNm').val() === 'undefined' ? '' : $('#oneAttnTmeTblSmryRow' + rndmNum + '_EvntNm').val();
                var ln_MajStrt = typeof $('#oneAttnTmeTblSmryRow' + rndmNum + '_MajStrt').val() === 'undefined' ? '' : $('#oneAttnTmeTblSmryRow' + rndmNum + '_MajStrt').val();
                var ln_MinStrt = typeof $('#oneAttnTmeTblSmryRow' + rndmNum + '_MinStrt').val() === 'undefined' ? '' : $('#oneAttnTmeTblSmryRow' + rndmNum + '_MinStrt').val();
                var ln_MajEnd = typeof $('#oneAttnTmeTblSmryRow' + rndmNum + '_MajEnd').val() === 'undefined' ? '' : $('#oneAttnTmeTblSmryRow' + rndmNum + '_MajEnd').val();
                var ln_MinEnd = typeof $('#oneAttnTmeTblSmryRow' + rndmNum + '_MinEnd').val() === 'undefined' ? '' : $('#oneAttnTmeTblSmryRow' + rndmNum + '_MinEnd').val();
                var ln_VnuNm = typeof $('#oneAttnTmeTblSmryRow' + rndmNum + '_VnuNm').val() === 'undefined' ? '' : $('#oneAttnTmeTblSmryRow' + rndmNum + '_VnuNm').val();
                var ln_HostNm = typeof $('#oneAttnTmeTblSmryRow' + rndmNum + '_HostNm').val() === 'undefined' ? '' : $('#oneAttnTmeTblSmryRow' + rndmNum + '_HostNm').val();
                var ln_IsEnabled = typeof $("input[name='oneAttnTmeTblSmryRow" + rndmNum + "_IsEnabled']:checked").val() === 'undefined' ? 'NO' : 'YES';
                if (ln_EvntNm.trim() !== '' && Number(ln_EvntID.replace(/[^-?0-9\.]/g, '')) > 0) {
                    if (ln_MajStrt.trim() === '' || ln_MinStrt.trim() === '' || ln_MajEnd.trim() === '' || ln_MinEnd.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Major and Minor Start and End Times for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#oneAttnTmeTblSmryRow' + rndmNum + '_MajStrt').addClass('rho-error');
                        $('#oneAttnTmeTblSmryRow' + rndmNum + '_MinStrt').addClass('rho-error');
                        $('#oneAttnTmeTblSmryRow' + rndmNum + '_MajEnd').addClass('rho-error');
                        $('#oneAttnTmeTblSmryRow' + rndmNum + '_MinEnd').addClass('rho-error');
                    } else {
                        $('#oneAttnTmeTblSmryRow' + rndmNum + '_MajStrt').removeClass('rho-error');
                        $('#oneAttnTmeTblSmryRow' + rndmNum + '_MinStrt').removeClass('rho-error');
                        $('#oneAttnTmeTblSmryRow' + rndmNum + '_MajEnd').removeClass('rho-error');
                        $('#oneAttnTmeTblSmryRow' + rndmNum + '_MinEnd').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdAttnTimesLns = slctdAttnTimesLns
                                + ln_TmeTblLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_EvntID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_VnuID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_HostID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_EvntNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_MajStrt.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_MinStrt.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_MajEnd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_MinEnd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_VnuNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_HostNm.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_IsEnabled.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
        title: 'Save Time Table',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Time Table...Please Wait...</p>',
        callback: function () {
        }
    });
    var formData = new FormData();
    formData.append('grp', 16);
    formData.append('typ', 1);
    formData.append('pg', 3);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('attnTmeTblID', attnTmeTblID);
    formData.append('attnTmeTblName', attnTmeTblName);
    formData.append('attnTmeTblDesc', attnTmeTblDesc);
    formData.append('attnTmeTblEvntType', attnTmeTblEvntType);
    formData.append('attnTmeTblSlotDrtn', attnTmeTblSlotDrtn);
    formData.append('attnTmeMajTmDivType', attnTmeMajTmDivType);
    formData.append('attnTmeMajTmStrtVal', attnTmeMajTmStrtVal);
    formData.append('attnTmeMajTmEndVal', attnTmeMajTmEndVal);
    formData.append('attnTmeMinTmDivType', attnTmeMinTmDivType);
    formData.append('attnTmeMinTmStrtVal', attnTmeMinTmStrtVal);
    formData.append('attnTmeMinTmEndVal', attnTmeMinTmEndVal);
    formData.append('attnTmeTblIsEnbld', attnTmeTblIsEnbld);
    formData.append('slctdAttnTimesLns', slctdAttnTimesLns);
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
                success: function (data) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            attnTmeTblID = data.attnTmeTblID;
                            getAttnTmeTbl('', '#allmodules', 'grp=16&typ=1&pg=3&vtyp=0');
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function insertNewAttnTmeTblRows(tableElmntID, position, inptHtml)
{
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1)
        {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID + ' > tbody > tr').eq(0).before(nwInptHtml);
            }
        } else {
            $('#' + tableElmntID).append(nwInptHtml);
        }
    }
    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetRfDc").focus(function () {
        $(this).select();
    });
    $(".jbDetDesc").focus(function () {
        $(this).select();
    });
    $(".jbDetDbt").focus(function () {
        $(this).select();
    });
    $(".jbDetCrdt").focus(function () {
        $(this).select();
    });
    $(".jbDetFuncRate").focus(function () {
        $(this).select();
    });
    $(".jbDetAccRate").focus(function () {
        $(this).select();
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
                $tds.eq(0).html(cntr);
            }
        }
    });
}

function delAttnTmeTbl(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_AttnTmeTblID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_AttnTmeTblID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_AttnTmeTblNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete TimeTable?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this TimeTable?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Facility Type?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting TimeTable...Please Wait...</p>',
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
                                    grp: 18,
                                    typ: 1,
                                    pg: 3,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function delAttnTmeTblLne(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TmeTblLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TmeTblLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_EvntNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete TimeTable Event?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this TimeTable Event?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete TimeTable Event?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting TimeTable Event...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 3,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function getAttnRgstrLovPage(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
        criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
        selVals, tblRowElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere, callBackFunc)
{
    var rndmNum = tblRowElmntID.split("_")[1];
    var rowPrfxNm = tblRowElmntID.split("_")[0];
    //oneAttnRegisterSmryRow<?php echo $cntr; ?>_DetCstmrID
    var valueElmntID = "";
    var attnRgstrDetType = typeof $('#' + rowPrfxNm + rndmNum + '_DetType').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DetType').val();
    if (attnRgstrDetType === "Existing Person")
    {
        lovNm = "All Person IDs";
        valueElmntID = '' + rowPrfxNm + rndmNum + '_DetPrsnID';
    } else if (attnRgstrDetType === "Customer")
    {
        lovNm = "All Business/Trade Partners";
        valueElmntID = '' + rowPrfxNm + rndmNum + '_DetCstmrID';
    } else
    {
        lovNm = "Ad hoc Visitors";
    }
    getLovsPage(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
            criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
            selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere, callBackFunc);
}

function getAttnRegister(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#attnRegisterSrchFor").val() === 'undefined' ? '%' : $("#attnRegisterSrchFor").val();
    var srchIn = typeof $("#attnRegisterSrchIn").val() === 'undefined' ? 'Both' : $("#attnRegisterSrchIn").val();
    var pageNo = typeof $("#attnRegisterPageNo").val() === 'undefined' ? 1 : $("#attnRegisterPageNo").val();
    var limitSze = typeof $("#attnRegisterDsplySze").val() === 'undefined' ? 10 : $("#attnRegisterDsplySze").val();
    var sortBy = typeof $("#attnRegisterSortBy").val() === 'undefined' ? '' : $("#attnRegisterSortBy").val();
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

function enterKeyFuncAttnRegister(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttnRegister(actionText, slctr, linkArgs);
    }
}

function getAttnRegisterDet(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#attnRegisterDetSrchFor").val() === 'undefined' ? '%' : $("#attnRegisterDetSrchFor").val();
    var srchIn = typeof $("#attnRegisterDetSrchIn").val() === 'undefined' ? 'Both' : $("#attnRegisterDetSrchIn").val();
    var pageNo = typeof $("#attnRegisterDetPageNo").val() === 'undefined' ? 1 : $("#attnRegisterDetPageNo").val();
    var limitSze = typeof $("#attnRegisterDetDsplySze").val() === 'undefined' ? 10 : $("#attnRegisterDetDsplySze").val();
    var sortBy = typeof $("#attnRegisterDetSortBy").val() === 'undefined' ? '' : $("#attnRegisterDetSortBy").val();
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
    $("#attnRegisterDetPageNo").val(pageNo);
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo +
            "&limitSze=" + limitSze + "&sortBy=" + sortBy;

    openATab(slctr, linkArgs);
}

function enterKeyFuncAttnRegisterDet(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttnRegisterDet(actionText, slctr, linkArgs);
    }
}

function getOneAttnRegisterForm(tmpltID, vwtype)
{
    var lnkArgs = 'grp=16&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdAttnRegisterID=' + tmpltID;
    doAjaxWthCallBck(lnkArgs, 'attnRegisterDetailInfo', 'PasteDirect', '', '', '', function () {
        if (!$.fn.DataTable.isDataTable('#oneAttnRegisterSmryLinesTable')) {
            var table2 = $('#oneAttnRegisterSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAttnRegisterSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#attnRegisterForm').submit(function (e) {
            e.preventDefault();
            return false;
        });

        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="tabajxattnrgstr"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=16&typ=1' + dttrgt;
            $(targ + 'tab').tab('show');
            if (targ.indexOf('attnRegisterHeadCount') >= 0) {
                $('#addNwAttnRegisterSmryBtn').addClass('hideNotice');
                $('#addNwAttnRegisterHdCntBtn').removeClass('hideNotice');
                $('#addNwAttnRgstrEvntCostBtn').addClass('hideNotice');
            } else if (targ.indexOf('attnRgstrEvntCost') >= 0) {
                $('#addNwAttnRegisterSmryBtn').addClass('hideNotice');
                $('#addNwAttnRegisterHdCntBtn').addClass('hideNotice');
                $('#addNwAttnRgstrEvntCostBtn').removeClass('hideNotice');
            } else {
                $('#addNwAttnRegisterSmryBtn').removeClass('hideNotice');
                $('#addNwAttnRegisterHdCntBtn').addClass('hideNotice');
                $('#addNwAttnRgstrEvntCostBtn').addClass('hideNotice');
            }
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
        $(".jbDetRfDc").focus(function () {
            $(this).select();
        });
        $(".jbDetDesc").focus(function () {
            $(this).select();
        });
        $(".jbDetDbt").focus(function () {
            $(this).select();
        });
        $(".jbDetCrdt").focus(function () {
            $(this).select();
        });
    });
}

function getAttnRgstrBreakdown(pKeyID, actionTxt, pKeyTitle, vtypActn, trnsAmtBrkdwnSaveElID)
{
    var slctdBrkdwnLines = '';
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    if (typeof trnsAmtBrkdwnSaveElID === 'undefined' || trnsAmtBrkdwnSaveElID === null)
    {
        trnsAmtBrkdwnSaveElID = '';
    }
    if (trnsAmtBrkdwnSaveElID.trim() !== '') {
        slctdBrkdwnLines = $('#' + trnsAmtBrkdwnSaveElID).val();
    }
    var lnkArgs = 'grp=16&typ=1&pg=2&vtyp=4';
    lnkArgs = lnkArgs + "&vtypActn=" + vtypActn + "&attnRecID=" + pKeyID
            + "&trnsAmtBrkdwnSaveElID=" + trnsAmtBrkdwnSaveElID + '&slctdBrkdwnLines=' + slctdBrkdwnLines;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaly', actionTxt, pKeyTitle, 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        $('#accbTransBrkdwnDiagForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        if (!$.fn.DataTable.isDataTable('#accbTransBrkdwnDiagHdrsTable')) {
            var table1 = $('#accbTransBrkdwnDiagHdrsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#accbTransBrkdwnDiagHdrsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $(".acbBrkdwnDesc").focus(function () {
            $(this).select();
        });
        $(".acbBrkdwnQTY").focus(function () {
            $(this).select();
        });
        $(".acbBrkdwnUVl").focus(function () {
            $(this).select();
        });
        $(".acbBrkdwnTtl").focus(function () {
            $(this).select();
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
        }).on('hide', function (ev) {
            $('#myFormsModaly').css("overflow", "auto");
        });
        $('#allOtherInputData99').val(0);
        $('#myFormsModaly').off('hidden.bs.modal');
        $('#myFormsModaly').one('hidden.bs.modal', function (e) {
            $('#myFormsModalyTitle').html('');
            $('#myFormsModalyBody').html('');
            $(e.currentTarget).unbind();
        });
    });
}

function applyNewAttnRgstrBrkdwn(modalID, trnsAmtBrkdwnSaveElID)
{
    var slctdBrkdwnLines = '';
    $('#accbTransBrkdwnDiagHdrsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];

                var ln_BrkdwnLnID = typeof $('#accbTransBrkdwnDiagHdrsRow' + rndmNum + '_BrkdwnLnID').val() === 'undefined' ? '-1' : $('#accbTransBrkdwnDiagHdrsRow' + rndmNum + '_BrkdwnLnID').val();
                var ln_IsPresent = typeof $("input[name='accbTransBrkdwnDiagHdrsRow" + rndmNum + "_IsPresent']:checked").val() === 'undefined' ? 'NO' : 'YES';
                var ln_StrtDte = typeof $('#accbTransBrkdwnDiagHdrsRow' + rndmNum + '_StrtDte').val() === 'undefined' ? '' : $('#accbTransBrkdwnDiagHdrsRow' + rndmNum + '_StrtDte').val();
                var ln_EndDte = typeof $('#accbTransBrkdwnDiagHdrsRow' + rndmNum + '_EndDte').val() === 'undefined' ? '' : $('#accbTransBrkdwnDiagHdrsRow' + rndmNum + '_EndDte').val();
                var ln_BrkdwnDesc = typeof $('#accbTransBrkdwnDiagHdrsRow' + rndmNum + '_BrkdwnDesc').val() === 'undefined' ? '' : $('#accbTransBrkdwnDiagHdrsRow' + rndmNum + '_BrkdwnDesc').val();
                slctdBrkdwnLines = slctdBrkdwnLines
                        + ln_BrkdwnLnID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "-").replace(/(\|)/g, ":") + "~"
                        + ln_IsPresent.replace(/(~)/g, "-").replace(/(\|)/g, ":") + "~"
                        + ln_StrtDte.replace(/(~)/g, "-").replace(/(\|)/g, ":") + "~"
                        + ln_EndDte.replace(/(~)/g, "-").replace(/(\|)/g, ":") + "~"
                        + ln_BrkdwnDesc.replace(/(~)/g, "-").replace(/(\|)/g, ":") + "|";
            }
        }
    });
    $('#' + trnsAmtBrkdwnSaveElID).val(slctdBrkdwnLines);
    $('#' + modalID).modal('hide');
}

function delAttnRgstrTmBrkdwn(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_BrkdwnLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_BrkdwnLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_BrkdwnDesc').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Time Detail?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Time Detail?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Time Detail?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Time Detail...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 5,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function insertNewAttnRgstrTmBrkdwn(tableElmntID, position, inptHtml)
{
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1)
        {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID + ' > tbody > tr').eq(0).before(nwInptHtml);
            }
        } else {
            $('#' + tableElmntID).append(nwInptHtml);
        }
    }
    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetRfDc").focus(function () {
        $(this).select();
    });
    $(".jbDetDesc").focus(function () {
        $(this).select();
    });
    $(".jbDetDbt").focus(function () {
        $(this).select();
    });
    $(".jbDetCrdt").focus(function () {
        $(this).select();
    });
    $(".jbDetFuncRate").focus(function () {
        $(this).select();
    });
    $(".jbDetAccRate").focus(function () {
        $(this).select();
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
                $tds.eq(0).html(cntr);
            }
        }
    });
}

function saveAttnRegisterForm()
{
    var sbmtdAttnRegisterID = typeof $("#sbmtdAttnRegisterID").val() === 'undefined' ? '-1' : $("#sbmtdAttnRegisterID").val();
    var attnRegisterTmTblID = typeof $("#attnRegisterTmTblID").val() === 'undefined' ? '-1' : $("#attnRegisterTmTblID").val();
    var attnRegisterTmTblDetID = typeof $("#attnRegisterTmTblDetID").val() === 'undefined' ? '-1' : $("#attnRegisterTmTblDetID").val();
    var attnRegisterEvntID = typeof $("#attnRegisterEvntID").val() === 'undefined' ? '-1' : $("#attnRegisterEvntID").val();
    var attnRegisterName = typeof $("#attnRegisterName").val() === 'undefined' ? '' : $("#attnRegisterName").val();
    var attnRegisterDesc = typeof $("#attnRegisterDesc").val() === 'undefined' ? '' : $("#attnRegisterDesc").val();
    var attnRegisterTmTblNm = typeof $("#attnRegisterTmTblNm").val() === 'undefined' ? '' : $("#attnRegisterTmTblNm").val();
    var attnRegisterEvntNm = typeof $("#attnRegisterEvntNm").val() === 'undefined' ? '' : $("#attnRegisterEvntNm").val();
    var attnRegisterStrtDte = typeof $("#attnRegisterStrtDte").val() === 'undefined' ? '' : $("#attnRegisterStrtDte").val();
    var attnRegisterEndDte = typeof $("#attnRegisterEndDte").val() === 'undefined' ? '' : $("#attnRegisterEndDte").val();
    var errMsg = "";
    if (attnRegisterName.trim() === '' || attnRegisterDesc.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Register Name and Description cannot be empty!</span></p>';
    }
    if (Number(attnRegisterTmTblID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Time Table cannot be empty!</span></p>';
    }
    if (Number(attnRegisterTmTblDetID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Event cannot be empty!</span></p>';
    }
    var isVld = true;
    var slctdIndvdlAtnds = "";
    var slctdAtndHdCnts = "";
    var slctdAtndEvntCosts = "";
    $('#oneAttnRegisterSmryLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_DetType = typeof $('#oneAttnRegisterSmryRow' + rndmNum + '_DetType').val() === 'undefined' ? '' : $('#oneAttnRegisterSmryRow' + rndmNum + '_DetType').val();
                var ln_RgstrDetID = typeof $('#oneAttnRegisterSmryRow' + rndmNum + '_RgstrDetID').val() === 'undefined' ? '-1' : $('#oneAttnRegisterSmryRow' + rndmNum + '_RgstrDetID').val();
                var ln_DetPrsnID = typeof $('#oneAttnRegisterSmryRow' + rndmNum + '_DetPrsnID').val() === 'undefined' ? '-1' : $('#oneAttnRegisterSmryRow' + rndmNum + '_DetPrsnID').val();
                var ln_DetCstmrID = typeof $('#oneAttnRegisterSmryRow' + rndmNum + '_DetCstmrID').val() === 'undefined' ? '-1' : $('#oneAttnRegisterSmryRow' + rndmNum + '_DetCstmrID').val();
                var ln_DetSpnsrID = typeof $('#oneAttnRegisterSmryRow' + rndmNum + '_DetSpnsrID').val() === 'undefined' ? '-1' : $('#oneAttnRegisterSmryRow' + rndmNum + '_DetSpnsrID').val();
                var ln_LineName = typeof $('#oneAttnRegisterSmryRow' + rndmNum + '_LineName').val() === 'undefined' ? '' : $('#oneAttnRegisterSmryRow' + rndmNum + '_LineName').val();
                var ln_LineDesc = typeof $('#oneAttnRegisterSmryRow' + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#oneAttnRegisterSmryRow' + rndmNum + '_LineDesc').val();
                var ln_SlctdAmtBrkdwns = typeof $('#oneAttnRegisterSmryRow' + rndmNum + '_SlctdAmtBrkdwns').val() === 'undefined' ? '' : $('#oneAttnRegisterSmryRow' + rndmNum + '_SlctdAmtBrkdwns').val();
                var ln_SlctdPointsScrd = typeof $('#oneAttnRegisterSmryRow' + rndmNum + '_SlctdPointsScrd').val() === 'undefined' ? '' : $('#oneAttnRegisterSmryRow' + rndmNum + '_SlctdPointsScrd').val();
                var ln_IsPrsnt = typeof $("input[name='oneAttnRegisterSmryRow" + rndmNum + "_IsPrsnt']:checked").val() === 'undefined' ? 'NO' : 'YES';
                var ln_NoPrsns = typeof $('#oneAttnRegisterSmryRow' + rndmNum + '_NoPrsns').val() === 'undefined' ? '0' : $('#oneAttnRegisterSmryRow' + rndmNum + '_NoPrsns').val();

                if (ln_LineName.trim() !== '') {
                    if (isVld === true) {
                        slctdIndvdlAtnds = slctdIndvdlAtnds
                                + ln_RgstrDetID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_DetType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_DetPrsnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_DetCstmrID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_DetSpnsrID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_LineName.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_SlctdAmtBrkdwns.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_SlctdPointsScrd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_IsPrsnt.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_NoPrsns.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });
    $('#oneAttnRgstrHeadCntTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = typeof $('#oneAttnRgstrHeadCntRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#oneAttnRgstrHeadCntRow' + rndmNum + '_TrnsLnID').val();
                var ln_PsblValID = typeof $('#oneAttnRgstrHeadCntRow' + rndmNum + '_PsblValID').val() === 'undefined' ? '-1' : $('#oneAttnRgstrHeadCntRow' + rndmNum + '_PsblValID').val();
                var ln_RsltMtrcID = typeof $('#oneAttnRgstrHeadCntRow' + rndmNum + '_RsltMtrcID').val() === 'undefined' ? '-1' : $('#oneAttnRgstrHeadCntRow' + rndmNum + '_RsltMtrcID').val();
                var ln_RsltTyp = typeof $('#oneAttnRgstrHeadCntRow' + rndmNum + '_RsltTyp').val() === 'undefined' ? '' : $('#oneAttnRgstrHeadCntRow' + rndmNum + '_RsltTyp').val();
                var ln_MtrcNm = typeof $('#oneAttnRgstrHeadCntRow' + rndmNum + '_MtrcNm').val() === 'undefined' ? '' : $('#oneAttnRgstrHeadCntRow' + rndmNum + '_MtrcNm').val();
                var ln_Result = typeof $('#oneAttnRgstrHeadCntRow' + rndmNum + '_Result').val() === 'undefined' ? '' : $('#oneAttnRgstrHeadCntRow' + rndmNum + '_Result').val();
                var ln_Comment = typeof $('#oneAttnRgstrHeadCntRow' + rndmNum + '_Comment').val() === 'undefined' ? '' : $('#oneAttnRgstrHeadCntRow' + rndmNum + '_Comment').val();

                if (ln_MtrcNm.trim() !== '') {
                    if (isVld === true) {
                        slctdAtndHdCnts = slctdAtndHdCnts
                                + ln_TrnsLnID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_PsblValID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_RsltMtrcID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_RsltTyp.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_MtrcNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_Result.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_Comment.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });
    $('#oneAttnRgstrEvntCostTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_CostLnID = typeof $('#oneAttnRgstrEvntCostRow' + rndmNum + '_CostLnID').val() === 'undefined' ? '-1' : $('#oneAttnRgstrEvntCostRow' + rndmNum + '_CostLnID').val();
                var ln_SrcDcID = typeof $('#oneAttnRgstrEvntCostRow' + rndmNum + '_SrcDcID').val() === 'undefined' ? '-1' : $('#oneAttnRgstrEvntCostRow' + rndmNum + '_SrcDcID').val();
                var ln_GLBatchID = typeof $('#oneAttnRgstrEvntCostRow' + rndmNum + '_GLBatchID').val() === 'undefined' ? '-1' : $('#oneAttnRgstrEvntCostRow' + rndmNum + '_GLBatchID').val();
                var ln_AccountID1 = typeof $('#oneAttnRgstrEvntCostRow' + rndmNum + '_AccountID1').val() === 'undefined' ? '-1' : $('#oneAttnRgstrEvntCostRow' + rndmNum + '_AccountID1').val();
                var ln_AccountID2 = typeof $('#oneAttnRgstrEvntCostRow' + rndmNum + '_AccountID2').val() === 'undefined' ? '-1' : $('#oneAttnRgstrEvntCostRow' + rndmNum + '_AccountID2').val();
                var ln_CostCtgry = typeof $('#oneAttnRgstrEvntCostRow' + rndmNum + '_CostCtgry').val() === 'undefined' ? '' : $('#oneAttnRgstrEvntCostRow' + rndmNum + '_CostCtgry').val();
                var ln_LineDesc = typeof $('#oneAttnRgstrEvntCostRow' + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#oneAttnRgstrEvntCostRow' + rndmNum + '_LineDesc').val();
                var ln_NoOfDays = typeof $('#oneAttnRgstrEvntCostRow' + rndmNum + '_NoOfDays').val() === 'undefined' ? '0' : $('#oneAttnRgstrEvntCostRow' + rndmNum + '_NoOfDays').val();
                var ln_NoPrsns = typeof $('#oneAttnRgstrEvntCostRow' + rndmNum + '_NoPrsns').val() === 'undefined' ? '0' : $('#oneAttnRgstrEvntCostRow' + rndmNum + '_NoPrsns').val();
                var ln_UnitCost = typeof $('#oneAttnRgstrEvntCostRow' + rndmNum + '_UnitCost').val() === 'undefined' ? '0' : $('#oneAttnRgstrEvntCostRow' + rndmNum + '_UnitCost').val();
                var ln_SrcDocType = typeof $('#oneAttnRgstrEvntCostRow' + rndmNum + '_SrcDocType').val() === 'undefined' ? '' : $('#oneAttnRgstrEvntCostRow' + rndmNum + '_SrcDocType').val();

                if (ln_CostCtgry.trim() !== '') {
                    if (isVld === true) {
                        slctdAtndEvntCosts = slctdAtndEvntCosts
                                + ln_CostLnID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_SrcDcID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_GLBatchID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_AccountID1.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_AccountID2.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_CostCtgry.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_NoOfDays.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_NoPrsns.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_UnitCost.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_SrcDocType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
        title: 'Save Attendance Register',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Attendance Register...Please Wait...</p>',
        callback: function () {
        }
    });
    var formData = new FormData();
    formData.append('grp', 16);
    formData.append('typ', 1);
    formData.append('pg', 2);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('sbmtdAttnRegisterID', sbmtdAttnRegisterID);
    formData.append('attnRegisterTmTblID', attnRegisterTmTblID);
    formData.append('attnRegisterTmTblDetID', attnRegisterTmTblDetID);
    formData.append('attnRegisterEvntID', attnRegisterEvntID);
    formData.append('attnRegisterName', attnRegisterName);
    formData.append('attnRegisterDesc', attnRegisterDesc);
    formData.append('attnRegisterTmTblNm', attnRegisterTmTblNm);
    formData.append('attnRegisterEvntNm', attnRegisterEvntNm);
    formData.append('attnRegisterStrtDte', attnRegisterStrtDte);
    formData.append('attnRegisterEndDte', attnRegisterEndDte);

    formData.append('slctdIndvdlAtnds', slctdIndvdlAtnds);
    formData.append('slctdAtndHdCnts', slctdAtndHdCnts);
    formData.append('slctdAtndEvntCosts', slctdAtndEvntCosts);

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
                success: function (data) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            sbmtdAttnRegisterID = data.sbmtdAttnRegisterID;
                            getAttnRegister('', '#allmodules', 'grp=16&typ=1&pg=2&vtyp=0');
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function insertNewAttnRegisterRows(tableElmntID, position, inptHtml)
{
    if (position === 0) {
        position = 1;
    }
    for (var i = 0; i < position; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1)
        {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID + ' > tbody > tr').eq(0).before(nwInptHtml);
            }
        } else {
            $('#' + tableElmntID).append(nwInptHtml);
        }
    }
    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetRfDc").focus(function () {
        $(this).select();
    });
    $(".jbDetDesc").focus(function () {
        $(this).select();
    });
    $(".jbDetDbt").focus(function () {
        $(this).select();
    });
    $(".jbDetCrdt").focus(function () {
        $(this).select();
    });
    $(".jbDetFuncRate").focus(function () {
        $(this).select();
    });
    $(".jbDetAccRate").focus(function () {
        $(this).select();
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
                $tds.eq(0).html(cntr);
            }
        }
    });
}

function delAttnRegister(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_RegisterID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_RegisterID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_RegisterNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Register?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Register?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Register?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Register...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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
function delAttnRgstrHdCnt(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_MtrcNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Register HeadCount?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Register HeadCount?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Register HeadCount?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Register HeadCount...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 3,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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
function delAttnRgstrEvntCost(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_CostLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_CostLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_CostCtgry').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Event Cost?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Event Cost?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Register Cost?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Register Cost...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 4,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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
function delAttnRegisterLne(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_RgstrDetID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_RgstrDetID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_LineName').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Register Line?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Register Line?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Register Line?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Register Line...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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
function getAttnResults(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#attnResultsSrchFor").val() === 'undefined' ? '%' : $("#attnResultsSrchFor").val();
    var srchIn = typeof $("#attnResultsSrchIn").val() === 'undefined' ? 'Both' : $("#attnResultsSrchIn").val();
    var pageNo = typeof $("#attnResultsPageNo").val() === 'undefined' ? 1 : $("#attnResultsPageNo").val();
    var limitSze = typeof $("#attnResultsDsplySze").val() === 'undefined' ? 10 : $("#attnResultsDsplySze").val();
    var sortBy = typeof $("#attnResultsSortBy").val() === 'undefined' ? '' : $("#attnResultsSortBy").val();
    var qStrtDte = typeof $("#attnResultsStrtDate").val() === 'undefined' ? '' : $("#attnResultsStrtDate").val();
    var qEndDte = typeof $("#attnResultsEndDate").val() === 'undefined' ? '' : $("#attnResultsEndDate").val();
    var qShwUnpstdOnly = $('#attnResultsReadOnly:checked').length > 0;
    var qShwPstdOnly = $('#attnResultsShwPstdOnly:checked').length > 0;
    var qShwIntrfc = $('#attnResultsShwIntrfc:checked').length > 0;
    var qLowVal = typeof $("#attnResultsLowVal").val() === 'undefined' ? 0 : $("#attnResultsLowVal").val();
    var qHighVal = typeof $("#attnResultsHighVal").val() === 'undefined' ? 0 : $("#attnResultsHighVal").val();
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
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwPstdOnly=" + qShwPstdOnly + "&qShwUnpstdOnly=" + qShwUnpstdOnly +
            "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte + "&qLowVal=" + qLowVal + "&qHighVal=" + qHighVal;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAttnResults(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttnResults(actionText, slctr, linkArgs);
    }
}

function saveAttnResultsForm(sbmtdAttnEventID, sbmtdRegisterID)
{
    var errMsg = "";
    var isVld = true;
    var slctdAttnResultIDs = "";
    $('#attnResultsHdrsTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = typeof $('#attnResultsHdrsRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#attnResultsHdrsRow' + rndmNum + '_TrnsLnID').val();
                var ln_EventID = typeof $('#attnResultsHdrsRow' + rndmNum + '_EventID').val() === 'undefined' ? '-1' : $('#attnResultsHdrsRow' + rndmNum + '_EventID').val();
                var ln_MetricID = typeof $('#attnResultsHdrsRow' + rndmNum + '_MetricID').val() === 'undefined' ? '-1' : $('#attnResultsHdrsRow' + rndmNum + '_MetricID').val();
                var ln_RgstrID = typeof $('#attnResultsHdrsRow' + rndmNum + '_RgstrID').val() === 'undefined' ? '-1' : $('#attnResultsHdrsRow' + rndmNum + '_RgstrID').val();

                var ln_MtrcNm = typeof $('#attnResultsHdrsRow' + rndmNum + '_MtrcNm').val() === 'undefined' ? '' : $('#attnResultsHdrsRow' + rndmNum + '_MtrcNm').val();
                var ln_Result = typeof $('#attnResultsHdrsRow' + rndmNum + '_Result').val() === 'undefined' ? '' : $('#attnResultsHdrsRow' + rndmNum + '_Result').val();
                var ln_StrtDte = typeof $('#attnResultsHdrsRow' + rndmNum + '_StrtDte').val() === 'undefined' ? '' : $('#attnResultsHdrsRow' + rndmNum + '_StrtDte').val();
                var ln_EndDte = typeof $('#attnResultsHdrsRow' + rndmNum + '_EndDte').val() === 'undefined' ? '' : $('#attnResultsHdrsRow' + rndmNum + '_EndDte').val();
                var ln_LineDesc = typeof $('#attnResultsHdrsRow' + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#attnResultsHdrsRow' + rndmNum + '_LineDesc').val();
                var ln_AutoCalc = typeof $("input[name='attnResultsHdrsRow" + rndmNum + "_AutoCalc']:checked").val() === 'undefined' ? 'NO' : 'YES';
                if (ln_MtrcNm.trim() !== '') {
                    if (ln_Result.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Result for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#attnResultsHdrsRow' + rndmNum + '_Result').addClass('rho-error');
                    } else {
                        $('#attnResultsHdrsRow' + rndmNum + '_Result').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdAttnResultIDs = slctdAttnResultIDs
                                + ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_EventID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_MetricID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_RgstrID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_MtrcNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_Result.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_StrtDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_EndDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + ln_AutoCalc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
        title: 'Save Activities/Event Results',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Activities/Event Results...Please Wait...</p>',
        callback: function () {
        }
    });
    var formData = new FormData();
    formData.append('grp', 16);
    formData.append('typ', 1);
    formData.append('pg', 9);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('sbmtdAttnEventID', sbmtdAttnEventID);
    formData.append('sbmtdRegisterID', sbmtdRegisterID);
    formData.append('slctdAttnResultIDs', slctdAttnResultIDs);

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
                success: function (data) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            if (sbmtdAttnEventID > 0) {
                                getOneAttnActvtyRsltsForm(sbmtdAttnEventID, 0, 'ReloadDialog');
                            } else {
                                getAttnResults('', '#allmodules', 'grp=16&typ=1&pg=9&vtyp=0&sbmtdAttnEventID=' + sbmtdAttnEventID + '&sbmtdRegisterID=' + sbmtdRegisterID);
                            }
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function insertNewAttnResultsRows(tableElmntID, position, inptHtml)
{
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1)
        {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID + ' > tbody > tr').eq(0).before(nwInptHtml);
                //$('#' + tableElmntID).append(nwInptHtml);
            }
        } else {
            $('#' + tableElmntID).append(nwInptHtml);
        }
    }
    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetRfDc").focus(function () {
        $(this).select();
    });
    $(".jbDetDesc").focus(function () {
        $(this).select();
    });
    $(".jbDetDbt").focus(function () {
        $(this).select();
    });
    $(".jbDetCrdt").focus(function () {
        $(this).select();
    });
    $(".jbDetFuncRate").focus(function () {
        $(this).select();
    });
    $(".jbDetAccRate").focus(function () {
        $(this).select();
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
                $tds.eq(0).html(cntr);
            }
        }
    });
}

function delAttnResults(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_MtrcNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Activity/Event Result?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Activity/Event Result?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Complaint/Observation?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Activity/Event Result...Please Wait...</p>',
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
                                    grp: 16,
                                    typ: 1,
                                    pg: 9,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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