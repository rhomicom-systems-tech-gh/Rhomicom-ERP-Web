
function prepareOrgAdmin(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="tabajxorg"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=5&typ=1' + dttrgt;
                return openATab(targ, linkArgs);
            });
        });

        if (lnkArgs.indexOf("&pg=1&vtyp=0") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#allOrgStpsTable')) {
                var table1 = $('#allOrgStpsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allOrgStpsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allOrgStpsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            $('#allOrgStpsTable tbody').off('click', 'tr');
            $('#allOrgStpsTable tbody').off('mouseenter', 'tr');

            $('#allOrgStpsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var curOrgID = typeof $('#allOrgStpsRow' + rndmNum + '_OrgID').val() === 'undefined' ? -1 : $('#allOrgStpsRow' + rndmNum + '_OrgID').val();
                getOneOrgStpForm(curOrgID, 1);
            });
            $('#allOrgStpsTable tbody')
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
            if (!$.fn.DataTable.isDataTable('#divsGrpsTable')) {
                var table1 = $('#divsGrpsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#divsGrpsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#divsGrpsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=1&vtyp=4") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#sitesLocsTable')) {
                var table1 = $('#sitesLocsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#sitesLocsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#sitesLocsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=1&vtyp=5") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#orgJobsTable')) {
                var table1 = $('#orgJobsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#orgJobsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#orgJobsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=1&vtyp=6") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#orgGradesTable')) {
                var table1 = $('#orgGradesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#orgGradesTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#orgGradesForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=1&vtyp=7") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#orgPositionsTable')) {
                var table1 = $('#orgPositionsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#orgPositionsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#orgPositionsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=1&vtyp=8") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#orgAcSegmentsTable')) {
                var table1 = $('#orgAcSegmentsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#orgAcSegmentsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#orgAcSegmentsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=1&vtyp=9") !== -1 || lnkArgs.indexOf("&pg=1&vtyp=10") !== -1 || lnkArgs.indexOf("&pg=1&vtyp=11") !== -1) {
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
            $('#allOtherInputData99').val(0);
            if (!$.fn.DataTable.isDataTable('#allSgmntValsTable')) {
                tableSg1 = $('#allSgmntValsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allSgmntValsTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#allSgmntClsfctnsTable')) {
                var table1 = $('#allSgmntClsfctnsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allSgmntClsfctnsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allSgmntValsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('#allSgmntValsTable tbody').off('click', 'tr');
            $('#allSgmntValsTable tbody').off('mouseenter', 'tr');
            $('#allSgmntValsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    tableSg1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var segValID = typeof $('#allSgmntValsRow' + rndmNum + '_SgmntValID').val() === 'undefined' ? -1 : $('#allSgmntValsRow' + rndmNum + '_SgmntValID').val();
                getOneSgmntValForm(segValID, 11);
            });

            $('#allSgmntValsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            tableSg1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });

            $('#myFormsModalLgY').off('hidden.bs.modal');
            $('#myFormsModalLgY').one('hidden.bs.modal', function (e) {
                $('#myFormsModalLgYTitle').html('');
                $('#myFormsModalLgYBody').html('');
                callBackFunc();
                $(e.currentTarget).unbind();
            });

        }
        htBody.removeClass("mdlloading");
    });
}

function getOneOrgStpForm(orgID, vwtype)
{
    var lnkArgs = 'grp=5&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdOrgID=' + orgID;
    doAjaxWthCallBck(lnkArgs, 'orgStpsDetailInfo', 'PasteDirect', '', '', '', function () {
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $(function () {
                $('[data-toggle="tabajxorg"]').click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr('href');
                    var dttrgt = $this.attr('data-rhodata');
                    var linkArgs = 'grp=5&typ=1' + dttrgt;
                    return openATab(targ, linkArgs);
                });
            });
        });
    });
}

function saveOrgStpForm()
{
    var orgDetOrgID = typeof $("#orgDetOrgID").val() === 'undefined' ? -1 : $("#orgDetOrgID").val();
    var orgDetOrgNm = typeof $("#orgDetOrgNm").val() === 'undefined' ? '' : $("#orgDetOrgNm").val();
    var orgDetPrntOrgID = typeof $("#orgDetPrntOrgID").val() === 'undefined' ? -1 : $("#orgDetPrntOrgID").val();
    var orgDetResAdrs = typeof $("#orgDetResAdrs").val() === 'undefined' ? '' : $("#orgDetResAdrs").val();
    var orgDetPosAdrs = typeof $("#orgDetPosAdrs").val() === 'undefined' ? '' : $("#orgDetPosAdrs").val();
    var orgDetEmail = typeof $("#orgDetEmail").val() === 'undefined' ? '' : $("#orgDetEmail").val();
    var orgDetWebsites = typeof $("#orgDetWebsites").val() === 'undefined' ? '' : $("#orgDetWebsites").val();
    var orgDetOrgTyp = typeof $("#orgDetOrgTyp").val() === 'undefined' ? '' : $("#orgDetOrgTyp").val();
    var orgDetFuncCrncy = typeof $("#orgDetFuncCrncy").val() === 'undefined' ? '' : $("#orgDetFuncCrncy").val();
    var orgDetLogo = typeof $("#orgDetLogo").val() === 'undefined' ? '' : $("#orgDetLogo").val();

    var orgDetCntctNums = typeof $("#orgDetCntctNums").val() === 'undefined' ? '' : $("#orgDetCntctNums").val();
    var orgDetIsEnabled = typeof $("input[name='orgDetIsEnabled']:checked").val() === 'undefined' ? 'NO' : $("input[name='orgDetIsEnabled']:checked").val();
    var orgDetOrgDesc = typeof $("#orgDetOrgDesc").val() === 'undefined' ? '' : $("#orgDetOrgDesc").val();
    var orgDetOrgSlogan = typeof $("#orgDetOrgSlogan").val() === 'undefined' ? '' : $("#orgDetOrgSlogan").val();

    var orgDetNoOfSegmnts = typeof $("#orgDetNoOfSegmnts").val() === 'undefined' ? 1 : $("#orgDetNoOfSegmnts").val();
    var orgDetSegDelimiter = typeof $("#orgDetSegDelimiter").val() === 'undefined' ? '' : $("#orgDetSegDelimiter").val();
    var orgDetLocSgmtNum = typeof $("#orgDetLocSgmtNum").val() === 'undefined' ? 0 : $("#orgDetLocSgmtNum").val();
    var orgDetSublocSgmtNum = typeof $("#orgDetSublocSgmtNum").val() === 'undefined' ? 0 : $("#orgDetSublocSgmtNum").val();
    var errMsg = "";
    if (orgDetOrgNm.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Organisation Name cannot be empty!</span></p>';
    }
    if (orgDetOrgTyp.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Organisation Type cannot be empty!</span></p>';
    }
    if (orgDetFuncCrncy.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Functional Currency cannot be empty!</span></p>';
    }
    if (orgDetCntctNums.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Contact Numbers cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Organisation',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Organisation...Please Wait...</p>',
        callback: function () {
        }
    });
    var formData = new FormData();
    formData.append('daOrgPicture', $('#daOrgPicture')[0].files[0]);
    formData.append('grp', 5);
    formData.append('typ', 1);
    formData.append('pg', 1);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('orgDetOrgID', orgDetOrgID);
    formData.append('orgDetOrgNm', orgDetOrgNm);
    formData.append('orgDetPrntOrgID', orgDetPrntOrgID);
    formData.append('orgDetResAdrs', orgDetResAdrs);

    formData.append('orgDetPosAdrs', orgDetPosAdrs);
    formData.append('orgDetEmail', orgDetEmail);
    formData.append('orgDetWebsites', orgDetWebsites);
    formData.append('orgDetOrgTyp', orgDetOrgTyp);
    formData.append('orgDetLogo', orgDetLogo);

    formData.append('orgDetFuncCrncy', orgDetFuncCrncy);
    formData.append('orgDetCntctNums', orgDetCntctNums);
    formData.append('orgDetIsEnabled', orgDetIsEnabled);
    formData.append('orgDetOrgDesc', orgDetOrgDesc);
    formData.append('orgDetOrgSlogan', orgDetOrgSlogan);

    formData.append('orgDetNoOfSegmnts', orgDetNoOfSegmnts);
    formData.append('orgDetSegDelimiter', orgDetSegDelimiter);
    formData.append('orgDetLocSgmtNum', orgDetLocSgmtNum);
    formData.append('orgDetSublocSgmtNum', orgDetSublocSgmtNum);
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
                            getAllOrgStps('', '#allmodules', 'grp=5&typ=1&pg=1&vtyp=0');
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

function getAllOrgStps(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allOrgStpsSrchFor").val() === 'undefined' ? '%' : $("#allOrgStpsSrchFor").val();
    var srchIn = typeof $("#allOrgStpsSrchIn").val() === 'undefined' ? 'Both' : $("#allOrgStpsSrchIn").val();
    var pageNo = typeof $("#allOrgStpsPageNo").val() === 'undefined' ? 1 : $("#allOrgStpsPageNo").val();
    var limitSze = typeof $("#allOrgStpsDsplySze").val() === 'undefined' ? 10 : $("#allOrgStpsDsplySze").val();
    var sortBy = typeof $("#allOrgStpsSortBy").val() === 'undefined' ? '' : $("#allOrgStpsSortBy").val();
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

function enterKeyFuncOrgStps(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllOrgStps(actionText, slctr, linkArgs);
    }
}

function getAllDivsGrps(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#divsGrpsSrchFor").val() === 'undefined' ? '%' : $("#divsGrpsSrchFor").val();
    var srchIn = typeof $("#divsGrpsSrchIn").val() === 'undefined' ? 'Both' : $("#divsGrpsSrchIn").val();
    var pageNo = typeof $("#divsGrpsPageNo").val() === 'undefined' ? 1 : $("#divsGrpsPageNo").val();
    var limitSze = typeof $("#divsGrpsDsplySze").val() === 'undefined' ? 10 : $("#divsGrpsDsplySze").val();
    var sortBy = typeof $("#divsGrpsSortBy").val() === 'undefined' ? '' : $("#divsGrpsSortBy").val();
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

function enterKeyFuncDivsGrps(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllDivsGrps(actionText, slctr, linkArgs);
    }
}

function delDivsGrps(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_GroupID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_GroupID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_GroupNm').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(1).text());*/
    }
    var msgPrt = "Divisions/Groups";
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
                var dialog1 = bootbox.alert({
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 5,
                                    typ: 1,
                                    pg: 1,
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

function saveDivsGrpsForm()
{
    var orgDetOrgID = typeof $("#orgDetOrgID").val() === 'undefined' ? -1 : $("#orgDetOrgID").val();
    var msgsTitle = 'Divisions/Groups';
    var slctdDivsGrps = "";
    var isVld = true;
    var errMsg = "";
    if (orgDetOrgID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Organisation Must be Selected First!</span></p>';
    }
    $('#divsGrpsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var groupNm = $('#divsGrpsRow' + rndmNum + '_GroupNm').val();
                var groupType = $('#divsGrpsRow' + rndmNum + '_DivTypNm').val();
                if (isVld === false || groupNm.trim() === '')
                {
                    if (groupType.trim() === '')
                    {
                        $('#divsGrpsRow' + rndmNum + '_GroupNm').addClass('rho-error');
                    } else {
                        $('#divsGrpsRow' + rndmNum + '_GroupNm').removeClass('rho-error');
                    }
                } else {
                    if (groupType.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Division Type for Column No. ' + i + ' cannot be empty!</span></p>';
                        $('#divsGrpsRow' + rndmNum + '_DivTypNm').addClass('rho-error');
                    } else {
                        $('#divsGrpsRow' + rndmNum + '_DivTypNm').removeClass('rho-error');
                    }
                    var isEnabled = typeof $("input[name='divsGrpsRow" + rndmNum + "_IsEnabled']:checked").val() === 'undefined' ? 'No' : 'Yes';
                    slctdDivsGrps = slctdDivsGrps + $('#divsGrpsRow' + rndmNum + '_GroupID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#divsGrpsRow' + rndmNum + '_GroupNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#divsGrpsRow' + rndmNum + '_PrntID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#divsGrpsRow' + rndmNum + '_DivTypNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#divsGrpsRow' + rndmNum + '_GroupDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + isEnabled.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
                    grp: 5,
                    typ: 1,
                    pg: 1,
                    q: 'UPDATE',
                    actyp: 2,
                    orgDetOrgID: orgDetOrgID,
                    slctdDivsGrps: slctdDivsGrps
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                        if (result.indexOf("Success") !== -1) {
                            getAllDivsGrps('', '#orgDivsGrpsPage', 'grp=5&typ=1&pg=1&vtyp=3&sbmtdOrgID=' + orgDetOrgID);
                        }
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

function grpTypOrgChange(grpTypeElmntID, grpNameElmntID, grpIDElmntID, grpNmLblElmntID)
{
    var lovChkngElementVal = typeof $("#" + grpTypeElmntID).val() === 'undefined' ? '' : $("#" + grpTypeElmntID).val();
    lovNm = "";
    if (lovChkngElementVal === "Everyone")
    {
        $("#" + grpNameElmntID).attr("disabled", "true");
        $("#" + grpNameElmntID).val("");
        $("#" + grpIDElmntID).val("-1");
        $('#' + grpNmLblElmntID).attr("disabled", "true");
    } else
    {
        $("#" + grpNameElmntID).removeAttr("disabled");
        $("#" + grpNameElmntID).val("");
        $("#" + grpIDElmntID).val("-1");
        $('#' + grpNmLblElmntID).removeAttr("disabled");
    }
}

function getAllSitesLocs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#sitesLocsSrchFor").val() === 'undefined' ? '%' : $("#sitesLocsSrchFor").val();
    var srchIn = typeof $("#sitesLocsSrchIn").val() === 'undefined' ? 'Both' : $("#sitesLocsSrchIn").val();
    var pageNo = typeof $("#sitesLocsPageNo").val() === 'undefined' ? 1 : $("#sitesLocsPageNo").val();
    var limitSze = typeof $("#sitesLocsDsplySze").val() === 'undefined' ? 10 : $("#sitesLocsDsplySze").val();
    var sortBy = typeof $("#sitesLocsSortBy").val() === 'undefined' ? '' : $("#sitesLocsSortBy").val();
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

function enterKeyFuncSitesLocs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllSitesLocs(actionText, slctr, linkArgs);
    }
}

function delSitesLocs(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SiteID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SiteID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SiteDesc').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(1).text());*/
    }
    var msgPrt = "Sites/Locations";
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
                var dialog1 = bootbox.alert({
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 5,
                                    typ: 1,
                                    pg: 1,
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

function saveSitesLocsForm()
{
    var orgDetOrgID = typeof $("#orgDetOrgID").val() === 'undefined' ? -1 : $("#orgDetOrgID").val();
    var msgsTitle = 'Sites/Locations';
    var slctdSitesLocs = "";
    var isVld = true;
    var errMsg = "";
    if (orgDetOrgID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Organisation Must be Selected First!</span></p>';
    }
    $('#sitesLocsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var siteNm = $('#sitesLocsRow' + rndmNum + '_SiteNm').val();
                var siteDesc = $('#sitesLocsRow' + rndmNum + '_SiteDesc').val();
                var siteType = $('#sitesLocsRow' + rndmNum + '_SiteType').val();
                var allwdGrpType = $('#sitesLocsRow' + rndmNum + '_GrpType').val();
                var allwdGrpNm = $('#sitesLocsRow' + rndmNum + '_GrpName').val();
                if (isVld === false || siteNm.trim() === '')
                {
                    if (siteNm.trim() === '')
                    {
                        $('#sitesLocsRow' + rndmNum + '_SiteNm').addClass('rho-error');
                    } else {
                        $('#sitesLocsRow' + rndmNum + '_SiteNm').removeClass('rho-error');
                    }
                } else {
                    if (siteDesc.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Site Description for Column No. ' + i + ' cannot be empty!</span></p>';
                        $('#sitesLocsRow' + rndmNum + '_SiteDesc').addClass('rho-error');
                    } else {
                        $('#sitesLocsRow' + rndmNum + '_SiteDesc').removeClass('rho-error');
                    }
                    if (siteType.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Site Type for Column No. ' + i + ' cannot be empty!</span></p>';
                        $('#sitesLocsRow' + rndmNum + '_SiteType').addClass('rho-error');
                    } else {
                        $('#sitesLocsRow' + rndmNum + '_SiteType').removeClass('rho-error');
                    }
                    if (allwdGrpType.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Allowed Group Type for Column No. ' + i + ' cannot be empty!</span></p>';
                        $('#sitesLocsRow' + rndmNum + '_GrpType').addClass('rho-error');
                    } else {
                        $('#sitesLocsRow' + rndmNum + '_GrpType').removeClass('rho-error');
                    }
                    if (allwdGrpType !== 'Everyone' && allwdGrpNm.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Allowed Group Name for Column No. ' + i + ' cannot be empty!</span></p>';
                        $('#sitesLocsRow' + rndmNum + '_GrpName').addClass('rho-error');
                    } else {
                        $('#sitesLocsRow' + rndmNum + '_GrpName').removeClass('rho-error');
                    }
                    var isEnabled = typeof $("input[name='sitesLocsRow" + rndmNum + "_IsEnabled']:checked").val() === 'undefined' ? 'No' : 'Yes';
                    slctdSitesLocs = slctdSitesLocs + $('#sitesLocsRow' + rndmNum + '_SiteID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#sitesLocsRow' + rndmNum + '_SiteNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#sitesLocsRow' + rndmNum + '_SiteDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#sitesLocsRow' + rndmNum + '_SiteType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#sitesLocsRow' + rndmNum + '_GrpType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#sitesLocsRow' + rndmNum + '_GrpID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#sitesLocsRow' + rndmNum + '_LnkdDivID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + isEnabled.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#sitesLocsRow' + rndmNum + '_PrntID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
                    grp: 5,
                    typ: 1,
                    pg: 1,
                    q: 'UPDATE',
                    actyp: 3,
                    orgDetOrgID: orgDetOrgID,
                    slctdSitesLocs: slctdSitesLocs
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                        if (result.indexOf("Success") !== -1) {
                            getAllSitesLocs('', '#orgSitesLocsPage', 'grp=5&typ=1&pg=1&vtyp=4&sbmtdOrgID=' + orgDetOrgID);
                        }
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

function getAllOrgJobs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#orgJobsSrchFor").val() === 'undefined' ? '%' : $("#orgJobsSrchFor").val();
    var srchIn = typeof $("#orgJobsSrchIn").val() === 'undefined' ? 'Both' : $("#orgJobsSrchIn").val();
    var pageNo = typeof $("#orgJobsPageNo").val() === 'undefined' ? 1 : $("#orgJobsPageNo").val();
    var limitSze = typeof $("#orgJobsDsplySze").val() === 'undefined' ? 10 : $("#orgJobsDsplySze").val();
    var sortBy = typeof $("#orgJobsSortBy").val() === 'undefined' ? '' : $("#orgJobsSortBy").val();
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

function enterKeyFuncOrgJobs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllOrgJobs(actionText, slctr, linkArgs);
    }
}

function delOrgJobs(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_JobID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_JobID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_JobNm').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(1).text());*/
    }
    var msgPrt = "Jobs";
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
                var dialog1 = bootbox.alert({
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 5,
                                    typ: 1,
                                    pg: 1,
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

function saveOrgJobsForm()
{
    var orgDetOrgID = typeof $("#orgDetOrgID").val() === 'undefined' ? -1 : $("#orgDetOrgID").val();
    var msgsTitle = 'Jobs';
    var slctdOrgJobs = "";
    var isVld = true;
    var errMsg = "";
    if (orgDetOrgID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Organisation Must be Selected First!</span></p>';
    }
    $('#orgJobsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var jobName = $('#orgJobsRow' + rndmNum + '_JobNm').val();
                if (isVld === false || jobName.trim() === '')
                {
                    if (jobName.trim() === '')
                    {
                        $('#orgJobsRow' + rndmNum + '_JobNm').addClass('rho-error');
                    } else {
                        $('#orgJobsRow' + rndmNum + '_JobNm').removeClass('rho-error');
                    }
                } else {
                    if (jobName.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Job Name for Column No. ' + i + ' cannot be empty!</span></p>';
                        $('#orgJobsRow' + rndmNum + '_JobNm').addClass('rho-error');
                    } else {
                        $('#orgJobsRow' + rndmNum + '_JobNm').removeClass('rho-error');
                    }

                    var isEnabled = typeof $("input[name='orgJobsRow" + rndmNum + "_IsEnabled']:checked").val() === 'undefined' ? 'No' : 'Yes';
                    slctdOrgJobs = slctdOrgJobs + $('#orgJobsRow' + rndmNum + '_JobID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgJobsRow' + rndmNum + '_JobNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgJobsRow' + rndmNum + '_PrntID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgJobsRow' + rndmNum + '_JobDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + isEnabled.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
                    grp: 5,
                    typ: 1,
                    pg: 1,
                    q: 'UPDATE',
                    actyp: 4,
                    orgDetOrgID: orgDetOrgID,
                    slctdOrgJobs: slctdOrgJobs
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                        if (result.indexOf("Success") !== -1) {
                            getAllOrgJobs('', '#orgJobsPage', 'grp=5&typ=1&pg=1&vtyp=5&sbmtdOrgID=' + orgDetOrgID);
                        }
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

function getAllOrgGrades(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#orgGradesSrchFor").val() === 'undefined' ? '%' : $("#orgGradesSrchFor").val();
    var srchIn = typeof $("#orgGradesSrchIn").val() === 'undefined' ? 'Both' : $("#orgGradesSrchIn").val();
    var pageNo = typeof $("#orgGradesPageNo").val() === 'undefined' ? 1 : $("#orgGradesPageNo").val();
    var limitSze = typeof $("#orgGradesDsplySze").val() === 'undefined' ? 10 : $("#orgGradesDsplySze").val();
    var sortBy = typeof $("#orgGradesSortBy").val() === 'undefined' ? '' : $("#orgGradesSortBy").val();
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

function enterKeyFuncOrgGrades(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllOrgGrades(actionText, slctr, linkArgs);
    }
}

function delOrgGrades(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_GradeID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_GradeID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_GradeNm').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(1).text());*/
    }
    var msgPrt = "Grades";
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
                var dialog1 = bootbox.alert({
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 5,
                                    typ: 1,
                                    pg: 1,
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

function saveOrgGradesForm()
{
    var orgDetOrgID = typeof $("#orgDetOrgID").val() === 'undefined' ? -1 : $("#orgDetOrgID").val();
    var msgsTitle = 'Grades';
    var slctdOrgGrades = "";
    var isVld = true;
    var errMsg = "";
    if (orgDetOrgID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Organisation Must be Selected First!</span></p>';
    }
    $('#orgGradesTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var gradeName = $('#orgGradesRow' + rndmNum + '_GradeNm').val();
                if (isVld === false || gradeName.trim() === '')
                {
                    if (gradeName.trim() === '')
                    {
                        $('#orgGradesRow' + rndmNum + '_GradeNm').addClass('rho-error');
                    } else {
                        $('#orgGradesRow' + rndmNum + '_GradeNm').removeClass('rho-error');
                    }
                } else {
                    var isEnabled = typeof $("input[name='orgGradesRow" + rndmNum + "_IsEnabled']:checked").val() === 'undefined' ? 'No' : 'Yes';
                    slctdOrgGrades = slctdOrgGrades + $('#orgGradesRow' + rndmNum + '_GradeID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgGradesRow' + rndmNum + '_GradeNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgGradesRow' + rndmNum + '_PrntID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgGradesRow' + rndmNum + '_GradeDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + isEnabled.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
                    grp: 5,
                    typ: 1,
                    pg: 1,
                    q: 'UPDATE',
                    actyp: 5,
                    orgDetOrgID: orgDetOrgID,
                    slctdOrgGrades: slctdOrgGrades
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                        if (result.indexOf("Success") !== -1) {
                            getAllOrgGrades('', '#orgGradesPage', 'grp=5&typ=1&pg=1&vtyp=6&sbmtdOrgID=' + orgDetOrgID);
                        }
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

function getAllOrgPositions(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#orgPositionsSrchFor").val() === 'undefined' ? '%' : $("#orgPositionsSrchFor").val();
    var srchIn = typeof $("#orgPositionsSrchIn").val() === 'undefined' ? 'Both' : $("#orgPositionsSrchIn").val();
    var pageNo = typeof $("#orgPositionsPageNo").val() === 'undefined' ? 1 : $("#orgPositionsPageNo").val();
    var limitSze = typeof $("#orgPositionsDsplySze").val() === 'undefined' ? 10 : $("#orgPositionsDsplySze").val();
    var sortBy = typeof $("#orgPositionsSortBy").val() === 'undefined' ? '' : $("#orgPositionsSortBy").val();
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

function enterKeyFuncOrgPositions(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllOrgPositions(actionText, slctr, linkArgs);
    }
}

function delOrgPositions(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_PosID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_PosID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_PosNm').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(1).text());*/
    }
    var msgPrt = "Position";
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
                var dialog1 = bootbox.alert({
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 5,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 6,
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
function saveOrgPositionsForm()
{
    var orgDetOrgID = typeof $("#orgDetOrgID").val() === 'undefined' ? -1 : $("#orgDetOrgID").val();
    var msgsTitle = 'Positions';
    var slctdOrgPositions = "";
    var isVld = true;
    var errMsg = "";
    if (orgDetOrgID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Organisation Must be Selected First!</span></p>';
    }
    $('#orgPositionsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var posName = $('#orgPositionsRow' + rndmNum + '_PosNm').val();
                if (isVld === false || posName.trim() === '')
                {
                    if (posName.trim() === '')
                    {
                        $('#orgPositionsRow' + rndmNum + '_PosNm').addClass('rho-error');
                    } else {
                        $('#orgPositionsRow' + rndmNum + '_PosNm').removeClass('rho-error');
                    }
                } else {
                    var isEnabled = typeof $("input[name='orgPositionsRow" + rndmNum + "_IsEnabled']:checked").val() === 'undefined' ? 'No' : 'Yes';
                    slctdOrgPositions = slctdOrgPositions + $('#orgPositionsRow' + rndmNum + '_PosID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgPositionsRow' + rndmNum + '_PosNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgPositionsRow' + rndmNum + '_PrntID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgPositionsRow' + rndmNum + '_PosDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + isEnabled.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
                    grp: 5,
                    typ: 1,
                    pg: 1,
                    q: 'UPDATE',
                    actyp: 6,
                    orgDetOrgID: orgDetOrgID,
                    slctdOrgPositions: slctdOrgPositions
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                        if (result.indexOf("Success") !== -1) {
                            getAllOrgPositions('', '#orgPositionsPage', 'grp=5&typ=1&pg=1&vtyp=7&sbmtdOrgID=' + orgDetOrgID);
                        }
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
function delOrgSegments(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SgmntID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SgmntID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(1).text());*/
    }
    var msgPrt = "Account Segment";
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
                var dialog1 = bootbox.alert({
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 5,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 7,
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
function saveOrgSegmentsForm()
{
    var orgDetOrgID = typeof $("#orgDetOrgID").val() === 'undefined' ? -1 : $("#orgDetOrgID").val();
    var msgsTitle = 'Account Segments';
    var slctdSegments = "";
    var isVld = true;
    var errMsg = "";
    if (orgDetOrgID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Organisation Must be Selected First!</span></p>';
    }
    $('#orgAcSegmentsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var sgmntName = $('#orgAcSegmentsRow' + rndmNum + '_SgmntName').val();
                var sgmntNum = $('#orgAcSegmentsRow' + rndmNum + '_SgmntNum').val();
                var sgmntClsf = $('#orgAcSegmentsRow' + rndmNum + '_SysClsfctn').val();
                var prntsgmntNum = $('#orgAcSegmentsRow' + rndmNum + '_PrntSgmntNum').val();
                if (isVld === false || sgmntName.trim() === '')
                {
                    if (sgmntName.trim() === '')
                    {
                        $('#orgAcSegmentsRow' + rndmNum + '_SgmntName').addClass('rho-error');
                    } else {
                        $('#orgAcSegmentsRow' + rndmNum + '_SgmntName').removeClass('rho-error');
                    }
                } else {
                    if (sgmntNum.trim() === '' || parseInt(sgmntNum) <= 0 || parseInt(sgmntNum) > 10)
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Segment Number for Column No. ' + i + ' must be from 1 to 10!</span></p>';
                        $('#orgAcSegmentsRow' + rndmNum + '_SgmntNum').addClass('rho-error');
                    } else {
                        $('#orgAcSegmentsRow' + rndmNum + '_SgmntNum').removeClass('rho-error');
                    }
                    if (sgmntClsf.trim() === '')
                    {
                        $('#orgAcSegmentsRow' + rndmNum + '_SysClsfctn').addClass('rho-error');
                    } else {
                        $('#orgAcSegmentsRow' + rndmNum + '_SgmntNum').removeClass('rho-error');
                    }
                    if (prntsgmntNum.trim() === '' || parseInt(prntsgmntNum) > 10)
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Parent Segment Number for Column No. ' + i + ' cannot be above 10!</span></p>';
                        $('#orgAcSegmentsRow' + rndmNum + '_PrntSgmntNum').addClass('rho-error');
                    } else {
                        $('#orgAcSegmentsRow' + rndmNum + '_PrntSgmntNum').removeClass('rho-error');
                    }
                    slctdSegments = slctdSegments + $('#orgAcSegmentsRow' + rndmNum + '_SgmntID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgAcSegmentsRow' + rndmNum + '_SgmntNum').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgAcSegmentsRow' + rndmNum + '_SgmntName').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgAcSegmentsRow' + rndmNum + '_SysClsfctn').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#orgAcSegmentsRow' + rndmNum + '_PrntSgmntNum').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
                    grp: 5,
                    typ: 1,
                    pg: 1,
                    q: 'UPDATE',
                    actyp: 7,
                    orgDetOrgID: orgDetOrgID,
                    slctdSegments: slctdSegments
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                        if (result.indexOf("Success") !== -1) {
                            var linkArgs = 'grp=5&typ=1&pg=1&vtyp=8&sbmtdOrgID=' + orgDetOrgID;
                            openATab('#orgAcSegmentsPage', linkArgs);
                        }
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