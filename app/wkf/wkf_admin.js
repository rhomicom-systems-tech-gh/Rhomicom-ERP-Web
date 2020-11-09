
function prepareWkfAdmin(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&pg=1&vtyp=0") !== -1)
        {
            var table1 = $('#allWkfAppsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allWkfAppsTable').wrap('<div class="dataTables_scroll"/>');
            var table2 = $('#wkfAppActnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#wkfAppActnsTable').wrap('<div class="dataTables_scroll"/>');
            $('#allWkfAppsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            $('#allWkfAppsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var curAppID = typeof $('#allWkfAppsRow' + rndmNum + '_AppID').val() === 'undefined' ? '%' : $('#allWkfAppsRow' + rndmNum + '_AppID').val();
                getOneWkfAppForm(curAppID, 1);
            });
            $('#allWkfAppsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        } else if (lnkArgs.indexOf("&pg=2&vtyp=0") !== -1)
        {
            var table1 = $('#allWkfHrchysTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allWkfHrchysTable').wrap('<div class="dataTables_scroll"/>');

            var table2 = $('#wkfHrchyLvlsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#wkfHrchyLvlsTable').wrap('<div class="dataTables_scroll"/>');
            $('#allWkfHrchysForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            $('#allWkfHrchysTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pKeyID = typeof $('#allWkfHrchysRow' + rndmNum + '_HrchyID').val() === 'undefined' ? '%' : $('#allWkfHrchysRow' + rndmNum + '_HrchyID').val();
                getOneWkfHrchyForm(pKeyID, 1);
            });
            $('#allWkfHrchysTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        } else if (lnkArgs.indexOf("&pg=3&vtyp=0") !== -1)
        {
            var table1 = $('#allWkfGrpsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allWkfGrpsTable').wrap('<div class="dataTables_scroll"/>');

            var table2 = $('#wkfGrpMmbrsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#wkfGrpMmbrsTable').wrap('<div class="dataTables_scroll"/>');
            $('#allWkfGrpsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            $('#allWkfGrpsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pKeyID = typeof $('#allWkfGrpsRow' + rndmNum + '_GrpID').val() === 'undefined' ? '%' : $('#allWkfGrpsRow' + rndmNum + '_GrpID').val();
                getOneWkfGrpForm(pKeyID, 1);
            });
            $('#allWkfGrpsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        }
        htBody.removeClass("mdlloading");
    });
}

function getOneWkfAppForm(appID, vwtype)
{
    var lnkArgs = 'grp=11&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdAppID=' + appID;
    doAjaxWthCallBck(lnkArgs, 'wkfAppsDetailInfo', 'PasteDirect', '', '', '', function () {
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $(function () {
                var table2 = $('#wkfAppActnsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#wkfAppActnsTable').wrap('<div class="dataTables_scroll"/>');
            });
        });
    });
}

function getAllWkfApps(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allWkfAppsSrchFor").val() === 'undefined' ? '%' : $("#allWkfAppsSrchFor").val();
    var srchIn = typeof $("#allWkfAppsSrchIn").val() === 'undefined' ? 'Both' : $("#allWkfAppsSrchIn").val();
    var pageNo = typeof $("#allWkfAppsPageNo").val() === 'undefined' ? 1 : $("#allWkfAppsPageNo").val();
    var limitSze = typeof $("#allWkfAppsDsplySze").val() === 'undefined' ? 10 : $("#allWkfAppsDsplySze").val();
    var sortBy = typeof $("#allWkfAppsSortBy").val() === 'undefined' ? '' : $("#allWkfAppsSortBy").val();
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

function enterKeyFuncWkfApps(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllWkfApps(actionText, slctr, linkArgs);
    }
}

function getAllWkfHrchys(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allWkfHrchysSrchFor").val() === 'undefined' ? '%' : $("#allWkfHrchysSrchFor").val();
    var srchIn = typeof $("#allWkfHrchysSrchIn").val() === 'undefined' ? 'Both' : $("#allWkfHrchysSrchIn").val();
    var pageNo = typeof $("#allWkfHrchysPageNo").val() === 'undefined' ? 1 : $("#allWkfHrchysPageNo").val();
    var limitSze = typeof $("#allWkfHrchysDsplySze").val() === 'undefined' ? 10 : $("#allWkfHrchysDsplySze").val();
    var sortBy = typeof $("#allWkfHrchysSortBy").val() === 'undefined' ? '' : $("#allWkfHrchysSortBy").val();
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

function enterKeyFuncWkfHrchys(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllWkfHrchys(actionText, slctr, linkArgs);
    }
}

function getOneWkfHrchyForm(pKeyID, vwtype)
{
    var lnkArgs = 'grp=11&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdHrchyID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'wkfHrchysDetailInfo', 'PasteDirect', '', '', '', function () {
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $(function () {
                var table2 = $('#wkfHrchyLvlsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#wkfHrchyLvlsTable').wrap('<div class="dataTables_scroll"/>');
            });
        });
    });
}

function getHrchyLovsPage(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
        criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
        selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere,
        lovChkngElement)
{
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        if (criteriaID == '')
        {
            criteriaID = "RhoUndefined";
        }
        if (criteriaID2 == '')
        {
            criteriaID2 = "RhoUndefined";
        }
        if (criteriaID3 == '')
        {
            criteriaID3 = "RhoUndefined";
        }
        var srchFor = typeof $("#lovSrchFor").val() === 'undefined' ? '%' : $("#lovSrchFor").val();
        var srchIn = typeof $("#lovSrchIn").val() === 'undefined' ? 'Both' : $("#lovSrchIn").val();
        var pageNo = typeof $("#lovPageNo").val() === 'undefined' ? 1 : $("#lovPageNo").val();
        var limitSze = typeof $("#lovDsplySze").val() === 'undefined' ? 10 : $("#lovDsplySze").val();
        var lovChkngElementVal = typeof $("#" + lovChkngElement).val() === 'undefined' ? 10 : $("#" + lovChkngElement).val();
        if (lovChkngElementVal == "Group")
        {
            lovNm = "Approver Groups";
        } else if (lovChkngElementVal == "Position Holder")
        {
            lovNm = "Positions";
        } else if (lovChkngElementVal == "Individual")
        {
            lovNm = "Active Persons";
        }
        var criteriaIDVal = typeof $("#" + criteriaID).val() === 'undefined' ? -1 : $("#" + criteriaID).val();
        var criteriaID2Val = typeof $("#" + criteriaID2).val() === 'undefined' ? '' : $("#" + criteriaID2).val();
        var criteriaID3Val = typeof $("#" + criteriaID3).val() === 'undefined' ? '' : $("#" + criteriaID3).val();
        if (colNoForChkBxCmprsn == 1)
        {
            /*Match by Description:Set both Value and Desc Element IDs*/
            selVals = typeof $("#" + descElemntID).val() === 'undefined' ? selVals : $("#" + descElemntID).val();
        }
        {
            /* Match using Possible Value itself*/
            /* For Dynamic LOVs use 0 and set both value and descElement IDs */
            /* For Static LOVs use 0 and set only valueElement ID */
            selVals = typeof $("#" + valueElmntID).val() === 'undefined' ? selVals : $("#" + valueElmntID).val();
        }
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
                $('#' + titleElementID).html(lovNm);

                $('#' + modalBodyID).html(xmlhttp.responseText);
                $('#myLovModalDiag').draggable();

                $('#' + elementID).off('show.bs.modal');
                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).one('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                    $(e.currentTarget).unbind();
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $("#lovForm").submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                    var cntr = 0;
                    var table = $('#lovTblRO').DataTable({
                        retrieve: true,
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#lovTblRO').wrap('<div class="dataTables_scroll"/>');
                    $('#lovTblRO tbody').on('dblclick', 'tr', function () {

                        table.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');

                        $checkedBoxes = $(this).find('input[type=checkbox]');
                        $checkedBoxes.each(function (i, checkbox) {
                            checkbox.checked = true;
                        });
                        $radioBoxes = $(this).find('input[type=radio]');
                        $radioBoxes.each(function (i, radio) {
                            radio.checked = true;
                        });
                        applySlctdLov(elementID, 'lovForm', valueElmntID, descElemntID);
                    });

                    $('#lovTblRO tbody').on('click', 'tr', function () {
                        if ($(this).hasClass('selected')) {
                            $(this).removeClass('selected');
                            $checkedBoxes = $(this).find('input[type=checkbox]');
                            $checkedBoxes.each(function (i, checkbox) {
                                checkbox.checked = false;
                            });
                            $radioBoxes = $(this).find('input[type=radio]');
                            $radioBoxes.each(function (i, radio) {
                                radio.checked = false;
                            });
                        } else {
                            table.$('tr.selected').removeClass('selected');
                            $(this).addClass('selected');

                            $checkedBoxes = $(this).find('input[type=checkbox]');
                            $checkedBoxes.each(function (i, checkbox) {
                                checkbox.checked = true;
                            });
                            $radioBoxes = $(this).find('input[type=radio]');
                            $radioBoxes.each(function (i, radio) {
                                radio.checked = true;
                            });
                        }
                    });
                    $('#lovTblRO tbody')
                            .on('mouseenter', 'tr', function () {
                                if ($(this).hasClass('highlight')) {
                                    $(this).removeClass('highlight');
                                } else {
                                    table.$('tr.highlight').removeClass('highlight');
                                    $(this).addClass('highlight');
                                }
                            });
                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //alert(criteriaIDVal);
        xmlhttp.send("grp=2&typ=1&lovNm=" + lovNm +
                "&criteriaID=" + criteriaID + "&criteriaID2=" + criteriaID2 +
                "&criteriaID3=" + criteriaID3 + "&criteriaIDVal=" + criteriaIDVal + "&criteriaID2Val=" + criteriaID2Val +
                "&criteriaID3Val=" + criteriaID3Val + "&chkOrRadio=" + chkOrRadio +
                "&mustSelSth=" + mustSelSth + "&selvals=" + selVals +
                "&valElmntID=" + valueElmntID + "&descElmntID=" + descElemntID +
                "&modalElementID=" + elementID + "&lovModalBody=" + modalBodyID +
                "&lovModalTitle=" + titleElementID + "&searchfor=" + srchFor + "&searchin=" + srchIn +
                "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&colNoForChkBxCmprsn=" + colNoForChkBxCmprsn + "&addtnlWhere=" + addtnlWhere + "");
    });

}


function getOneWkfGrpForm(pkeyID, vwtype)
{
    var lnkArgs = 'grp=11&typ=1&pg=3&vtyp=' + vwtype + '&sbmtdGrpID=' + pkeyID;
    doAjaxWthCallBck(lnkArgs, 'wkfGrpsDetailInfo', 'PasteDirect', '', '', '', function () {
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $(function () {
                var table2 = $('#wkfGrpMmbrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#wkfGrpMmbrsTable').wrap('<div class="dataTables_scroll"/>');
            });
        });
    });
}

function getAllWkfGrps(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allWkfGrpsSrchFor").val() === 'undefined' ? '%' : $("#allWkfGrpsSrchFor").val();
    var srchIn = typeof $("#allWkfGrpsSrchIn").val() === 'undefined' ? 'Both' : $("#allWkfGrpsSrchIn").val();
    var pageNo = typeof $("#allWkfGrpsPageNo").val() === 'undefined' ? 1 : $("#allWkfGrpsPageNo").val();
    var limitSze = typeof $("#allWkfGrpsDsplySze").val() === 'undefined' ? 10 : $("#allWkfGrpsDsplySze").val();
    var sortBy = typeof $("#allWkfGrpsSortBy").val() === 'undefined' ? '' : $("#allWkfGrpsSortBy").val();
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

function enterKeyFuncWkfGrps(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllWkfGrps(actionText, slctr, linkArgs);
    }
}

function saveWkfAppForm()
{
    var wkfDetAppID = typeof $("#wkfDetAppID").val() === 'undefined' ? -1 : $("#wkfDetAppID").val();
    var wkfDetAppNm = typeof $("#wkfDetAppNm").val() === 'undefined' ? "" : $("#wkfDetAppNm").val();
    var wkfDetSrcMdl = typeof $("#wkfDetSrcMdl").val() === 'undefined' ? '' : $("#wkfDetSrcMdl").val();
    var wkfDetSrcMdlID = typeof $("#wkfDetSrcMdlID").val() === 'undefined' ? -1 : $("#wkfDetSrcMdlID").val();
    var wkfDetAppDesc = typeof $("#wkfDetAppDesc").val() === 'undefined' ? '' : $("#wkfDetAppDesc").val();

    var isNew = typeof $("#isNew123").val() === 'undefined' ? 0 : $("#isNew123").val();

    var errMsg = "";
    if (wkfDetAppNm.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Application Name cannot be empty!</span></p>';
    }
    if (wkfDetSrcMdl.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Source Module cannot be empty!</span></p>';
    }
    var slctdAppActns = "";
    var isVld = true;
    $('#wkfAppActnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#wkfAppActnsRow' + rndmNum + '_ActnID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var admnOnly = typeof $("input[name='wkfAppActnsRow" + rndmNum + "_AdmnOnly']:checked").val() === 'undefined' ? 'NO' : 'YES';
                    var actnNm = $('#wkfAppActnsRow' + rndmNum + '_ActnNm').val();
                    if (actnNm.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Action Name for Row ' + i + ' cannot be empty!</span></p>';
                        $('#wkfAppActnsRow' + rndmNum + '_ActnNm').addClass('rho-error');
                    } else {
                        $('#wkfAppActnsRow' + rndmNum + '_ActnNm').removeClass('rho-error');
                    }
                    var sQLRep = $('#wkfAppActnsRow' + rndmNum + '_ActnSQL').val();
                    var actnURL = $('#wkfAppActnsRow' + rndmNum + '_ActnURL').val();
                    if (actnURL.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Action URL for Row ' + i + ' cannot be empty!</span></p>';
                        /*$('#wkfAppActnsRow' + rndmNum + '_ActnSQL').addClass('rho-error');*/
                        $('#wkfAppActnsRow' + rndmNum + '_ActnURL').addClass('rho-error');
                    } else {
                        /*$('#wkfAppActnsRow' + rndmNum + '_ActnSQL').removeClass('rho-error');*/
                        $('#wkfAppActnsRow' + rndmNum + '_ActnURL').removeClass('rho-error');
                    }
                    if (isVld === false)
                    {
                        /*Do Nothing*/
                    } else {
                        slctdAppActns = slctdAppActns + $('#wkfAppActnsRow' + rndmNum + '_ActnID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#wkfAppActnsRow' + rndmNum + '_ActnNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#wkfAppActnsRow' + rndmNum + '_ActnDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#wkfAppActnsRow' + rndmNum + '_ActnSQL').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#wkfAppActnsRow' + rndmNum + '_ActnURL').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#wkfAppActnsRow' + rndmNum + '_DsplyTyp').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + admnOnly.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });

    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Workflow App',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Workflow App...Please Wait...</p>',
        callback: function () {
        }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");

            var formData = new FormData();
            formData.append('grp', 11);
            formData.append('typ', 1);
            formData.append('pg', 1);
            formData.append('q', 'UPDATE');
            formData.append('actyp', 1);
            formData.append('wkfDetAppID', wkfDetAppID);
            formData.append('wkfDetAppNm', wkfDetAppNm);
            formData.append('wkfDetSrcMdl', wkfDetSrcMdl);
            formData.append('wkfDetSrcMdlID', wkfDetSrcMdlID);

            formData.append('wkfDetAppDesc', wkfDetAppDesc);
            formData.append('slctdAppActns', slctdAppActns);
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
                        dialog.find('.bootbox-body').html(result.message);
                        $("#wkfDetAppID").val(result.wkfDetAppID);
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

function delWkfApp(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var appNm = '';
    if (typeof $('#allWkfAppsRow' + rndmNum + '_AppID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allWkfAppsRow' + rndmNum + '_AppID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        appNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Remove Workflow App?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Workflow App?<br/>Action cannot be Undone!</p>',
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
                    title: 'Remove Workflow App?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Workflow App...Please Wait...</p>'
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
                                    grp: 11,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 1,
                                    appNm: appNm,
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

function delWkfAppAction(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var actnNm = "";
    if (typeof $('#wkfAppActnsRow' + rndmNum + '_ActnID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?>*/
    } else {
        pKeyID = $('#wkfAppActnsRow' + rndmNum + '_ActnID').val();
        actnNm = $('#wkfAppActnsRow' + rndmNum + '_ActnNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Workflow Action?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Workflow Action?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Workflow Action?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Workflow Action...Please Wait...</p>',
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
                                    grp: 11,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    actnNm: actnNm
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

function saveWkfHrchyForm()
{
    var wkfHrchyID = typeof $("#wkfHrchyID").val() === 'undefined' ? -1 : $("#wkfHrchyID").val();
    var wkfHrchyNm = typeof $("#wkfHrchyNm").val() === 'undefined' ? "" : $("#wkfHrchyNm").val();
    var wkfHrchyDesc = typeof $("#wkfHrchyDesc").val() === 'undefined' ? '' : $("#wkfHrchyDesc").val();
    var wkfHrchyIsEnbld = typeof $("input[name='wkfHrchyIsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='wkfHrchyIsEnbld']:checked").val();
    var wkfHrchyType = typeof $("#wkfHrchyType").val() === 'undefined' ? '' : $("#wkfHrchyType").val();
    var wkfHrchySQL = typeof $("#wkfHrchySQL").val() === 'undefined' ? '' : $("#wkfHrchySQL").val();

    var isNew = typeof $("#isNew123").val() === 'undefined' ? 0 : $("#isNew123").val();

    var errMsg = "";
    if (wkfHrchyNm.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Hierarchy Name cannot be empty!</span></p>';
    }
    if (wkfHrchyType.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Hierarchy Type cannot be empty!</span></p>';
    }
    var slctdHrchyLvls = "";
    var isVld = true;
    $('#wkfHrchyLvlsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#wkfHrchyLvlsRow' + rndmNum + '_MnlHrchyID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var isEnbld = typeof $("input[name='wkfHrchyLvlsRow" + rndmNum + "_Enabled']:checked").val() === 'undefined' ? 'NO' : 'YES';
                    var apprvrNm = $('#wkfHrchyLvlsRow' + rndmNum + '_ApprvrNm').val();
                    if (apprvrNm.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Approver Name for Row ' + i + ' cannot be empty!</span></p>';
                        $('#wkfHrchyLvlsRow' + rndmNum + '_ApprvrNm').addClass('rho-error');
                    } else {
                        $('#wkfHrchyLvlsRow' + rndmNum + '_ApprvrNm').removeClass('rho-error');
                    }
                    var aprvrTyp = $('#wkfHrchyLvlsRow' + rndmNum + '_AprvrTyp').val();
                    if (aprvrTyp.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Approver type for Row ' + i + ' cannot be empty!</span></p>';
                        $('#wkfHrchyLvlsRow' + rndmNum + '_AprvrTyp').addClass('rho-error');
                    } else {
                        $('#wkfHrchyLvlsRow' + rndmNum + '_AprvrTyp').removeClass('rho-error');
                    }
                    var aprvrLevel = $('#wkfHrchyLvlsRow' + rndmNum + '_Level').val();
                    if (!isNumber(aprvrLevel))
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Approver Level for Row ' + i + ' must be a Number!</span></p>';
                        $('#wkfHrchyLvlsRow' + rndmNum + '_Level').addClass('rho-error');
                    } else {
                        $('#wkfHrchyLvlsRow' + rndmNum + '_Level').removeClass('rho-error');
                    }
                    if (isVld === false)
                    {
                        /*Do Nothing*/
                    } else {
                        slctdHrchyLvls = slctdHrchyLvls + $('#wkfHrchyLvlsRow' + rndmNum + '_MnlHrchyID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#wkfHrchyLvlsRow' + rndmNum + '_ApprvrID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#wkfHrchyLvlsRow' + rndmNum + '_ApprvrNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#wkfHrchyLvlsRow' + rndmNum + '_AprvrTyp').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#wkfHrchyLvlsRow' + rndmNum + '_Level').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + isEnbld.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });

    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Workflow App',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Workflow Hierarchy...Please Wait...</p>',
        callback: function () {
        }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");

            var formData = new FormData();
            formData.append('grp', 11);
            formData.append('typ', 1);
            formData.append('pg', 2);
            formData.append('q', 'UPDATE');
            formData.append('actyp', 1);
            formData.append('wkfHrchyID', wkfHrchyID);
            formData.append('wkfHrchyNm', wkfHrchyNm);
            formData.append('wkfHrchyDesc', wkfHrchyDesc);
            formData.append('wkfHrchyIsEnbld', wkfHrchyIsEnbld);
            formData.append('wkfHrchySQL', wkfHrchySQL);

            formData.append('wkfHrchyType', wkfHrchyType);
            formData.append('slctdHrchyLvls', slctdHrchyLvls);
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
                        dialog.find('.bootbox-body').html(result.message);
                        $("#wkfDetAppID").val(result.wkfDetAppID);
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

function delWkfHrchy(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var hrchyNm = '';
    if (typeof $('#allWkfHrchysRow' + rndmNum + '_HrchyID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allWkfHrchysRow' + rndmNum + '_HrchyID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        hrchyNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Remove Workflow Hierarchy?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Workflow Hierarchy?<br/>Action cannot be Undone!</p>',
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
                    title: 'Remove Workflow Hierarchy?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Workflow Hierarchy...Please Wait...</p>'
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
                                    grp: 11,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 1,
                                    hrchyNm: hrchyNm,
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

function delWkfHrchyLvl(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var apprvNm = "";
    if (typeof $('#wkfHrchyLvlsRow' + rndmNum + '_MnlHrchyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?>*/
    } else {
        pKeyID = $('#wkfHrchyLvlsRow' + rndmNum + '_MnlHrchyID').val();
        apprvNm = $('#wkfHrchyLvlsRow' + rndmNum + '_ApprvrNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Hierarchy Level?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Hierarchy Level?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Hierarchy Level?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Hierarchy Level...Please Wait...</p>',
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
                                    grp: 11,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    apprvNm: apprvNm
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

function saveWkfAprvrGrp()
{
    var wkfGrpID = typeof $("#wkfGrpID").val() === 'undefined' ? -1 : $("#wkfGrpID").val();
    var wkfGrpNm = typeof $("#wkfGrpNm").val() === 'undefined' ? "" : $("#wkfGrpNm").val();
    var wkfGrpLinkedFirmID = typeof $("#wkfGrpLinkedFirmID").val() === 'undefined' ? -1 : $("#wkfGrpLinkedFirmID").val();
    var wkfGrpIsEnbld = typeof $("input[name='wkfGrpIsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='wkfGrpIsEnbld']:checked").val();
    var wkfGrpDesc = typeof $("#wkfGrpDesc").val() === 'undefined' ? '' : $("#wkfGrpDesc").val();

    var isNew = typeof $("#isNew123").val() === 'undefined' ? 0 : $("#isNew123").val();

    var errMsg = "";
    if (wkfGrpNm.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Approver Group Name cannot be empty!</span></p>';
    }
    if (wkfGrpLinkedFirmID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Linked Firm cannot be empty!</span></p>';
    }
    var slctdGrpMembers = "";
    var isVld = true;
    $('#wkfGrpMmbrsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#wkfGrpMmbrsRow' + rndmNum + '_MmbrID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var isEnbld = typeof $("input[name='wkfGrpMmbrsRow" + rndmNum + "_Enabled']:checked").val() === 'undefined' ? 'NO' : 'YES';
                    var apprvrNm = $('#wkfGrpMmbrsRow' + rndmNum + '_PrsnNm').val();
                    if (apprvrNm.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Approver Name for Row ' + i + ' cannot be empty!</span></p>';
                        $('#wkfGrpMmbrsRow' + rndmNum + '_PrsnNm').addClass('rho-error');
                    } else {
                        $('#wkfGrpMmbrsRow' + rndmNum + '_PrsnNm').removeClass('rho-error');
                    }
                    if (isVld === false)
                    {
                        /*Do Nothing*/
                    } else {
                        slctdGrpMembers = slctdGrpMembers + $('#wkfGrpMmbrsRow' + rndmNum + '_MmbrID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#wkfGrpMmbrsRow' + rndmNum + '_PrsnLocID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + isEnbld.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });

    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Approver Group',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Approver Group...Please Wait...</p>',
        callback: function () {
        }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");

            var formData = new FormData();
            formData.append('grp', 11);
            formData.append('typ', 1);
            formData.append('pg', 3);
            formData.append('q', 'UPDATE');
            formData.append('actyp', 1);
            formData.append('wkfGrpID', wkfGrpID);
            formData.append('wkfGrpNm', wkfGrpNm);
            formData.append('wkfGrpLinkedFirmID', wkfGrpLinkedFirmID);
            formData.append('wkfGrpIsEnbld', wkfGrpIsEnbld);
            formData.append('wkfGrpDesc', wkfGrpDesc);

            formData.append('slctdGrpMembers', slctdGrpMembers);
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
                        dialog.find('.bootbox-body').html(result.message);
                        $("#wkfDetAppID").val(result.wkfDetAppID);
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

function delWkfAprvrGrp(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var grpNm = '';
    if (typeof $('#allWkfGrpsRow' + rndmNum + '_GrpID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allWkfGrpsRow' + rndmNum + '_GrpID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        grpNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Remove Approver Group?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Approver Group?<br/>Action cannot be Undone!</p>',
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
                    title: 'Remove Approver Group?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Approver Group...Please Wait...</p>'
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
                                    grp: 11,
                                    typ: 1,
                                    pg: 3,
                                    q: 'DELETE',
                                    actyp: 1,
                                    grpNm: grpNm,
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

function delWkfAprvrGrpPrsn(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var apprvNm = "";
    if (typeof $('#wkfGrpMmbrsRow' + rndmNum + '_MmbrID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?>*/
    } else {
        pKeyID = $('#wkfGrpMmbrsRow' + rndmNum + '_MmbrID').val();
        apprvNm = $('#wkfGrpMmbrsRow' + rndmNum + '_PrsnNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Group Member?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Group Member?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Group Member?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Group Member...Please Wait...</p>',
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
                                    grp: 11,
                                    typ: 1,
                                    pg: 3,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    apprvNm: apprvNm
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