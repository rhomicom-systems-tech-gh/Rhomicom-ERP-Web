
function prepareProfile(lnkArgs, htBody, targ, rspns)
{
    if (lnkArgs.indexOf("&pg=1") !== -1)
    {
        prepareProfileRO(lnkArgs, htBody, targ, rspns);
    } else if (lnkArgs.indexOf("&pg=2") !== -1)
    {
        prepareProfileEDT(lnkArgs, htBody, targ, rspns);
    } else if (lnkArgs.indexOf("&pg=4") !== -1)
    {
        $(targ).html(rspns);
        $(function () {
            $('[data-toggle="tabajxleave"]').off('click');
            $('[data-toggle="tabajxleave"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=8&typ=1' + dttrgt;
                if (targ.indexOf('planExctns') >= 0) {
                    $('#planExctns').removeClass('hideNotice');
                    $('#planExctnLns').addClass('hideNotice');
                    $('#plansLv').addClass('hideNotice');
                    return openATab(targ, linkArgs);
                } else if (targ.indexOf('planExctnLns') >= 0) {
                    $('#planExctns').addClass('hideNotice');
                    $('#planExctnLns').removeClass('hideNotice');
                    $('#plansLv').addClass('hideNotice');
                    return openATab(targ, linkArgs);
                } else {
                    $('#planExctns').addClass('hideNotice');
                    $('#planExctnLns').addClass('hideNotice');
                    $('#plansLv').removeClass('hideNotice');
                    return openATab(targ, linkArgs);
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
        $('#allOtherInputData99').val(0);
        htBody.removeClass("mdlloading");
    } else if (lnkArgs.indexOf("&pg=5") !== -1
            || lnkArgs.indexOf("&pg=6") !== -1
            || lnkArgs.indexOf("&pg=7") !== -1
            || lnkArgs.indexOf("&pg=8") !== -1)
    {
        loadScript("app/prs/prsn_admin.js?v=" + jsFilesVrsn, function () {
            prepareDataAdmin(lnkArgs, htBody, targ, rspns);
        });
    } else {
        $(targ).html(rspns);
        htBody.removeClass("mdlloading");
    }
}

function prepareProfileRO(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&vtyp=0") !== -1)
        {
            var table1 = $('#nationalIDTblRO').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#nationalIDTblRO').wrap('<div class="dataTables_scroll" />');
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="tabajxprflro"]').click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr('href');
                    var dttrgt = $this.attr('data-rhodata');
                    var linkArgs = 'grp=8&typ=1' + dttrgt;
                    return openATab(targ, linkArgs);
                });
            });
        } else if (lnkArgs.indexOf("&vtyp=1") !== -1)
        {
            var table2 = $('.extPrsnDataTblRO').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": true
            });
        } else if (lnkArgs.indexOf("&vtyp=2") !== -1)
        {
            var table2 = $('.orgAsgnmentsTblsRO').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": true
            });
        } else if (lnkArgs.indexOf("&vtyp=3") !== -1)
        {
            var table2 = $('.cvTblsRO').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": true
            });
        } else if (lnkArgs.indexOf("&vtyp=4") !== -1)
        {
            var table1 = $('#attchdDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdDocsTable').wrap('<div class="dataTables_scroll"/>');
            $('#attchdDocsTblForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            var table2 = $('.otherInfoTblsRO').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "bFilter": true,
                "scrollX": true
            });
        }
        htBody.removeClass("mdlloading");
    });
}

function prepareProfileEDT(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&vtyp=0") !== -1)
        {
            var table1 = $('#nationalIDTblEDT').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#nationalIDTblEDT').wrap('<div class="dataTables_scroll" />');
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="tabajxprfledt"]').click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr('href');
                    var dttrgt = $this.attr('data-rhodata');
                    var linkArgs = 'grp=8&typ=1' + dttrgt;
                    $(targ + 'tab').tab('show');
                    if (targ.indexOf('prflHomeEDT') >= 0) {
                        $('#prflAddPrsnDataEDT').addClass('hideNotice');
                        $('#prflOrgAsgnEDT').addClass('hideNotice');
                        $('#prflCVEDT').addClass('hideNotice');
                        $('#prflOthrInfoEDT').addClass('hideNotice');
                        $('#prflHomeEDT').removeClass('hideNotice');
                    } else {
                        openATab(targ, linkArgs);
                        if (targ.indexOf('prflAddPrsnDataEDT') >= 0) {
                            $('#prflHomeEDT').addClass('hideNotice');
                            $('#prflOrgAsgnEDT').addClass('hideNotice');
                            $('#prflCVEDT').addClass('hideNotice');
                            $('#prflOthrInfoEDT').addClass('hideNotice');
                            $('#prflAddPrsnDataEDT').removeClass('hideNotice');
                        } else if (targ.indexOf('prflOrgAsgnEDT') >= 0) {
                            $('#prflAddPrsnDataEDT').addClass('hideNotice');
                            $('#prflHomeEDT').addClass('hideNotice');
                            $('#prflCVEDT').addClass('hideNotice');
                            $('#prflOthrInfoEDT').addClass('hideNotice');
                            $('#prflOrgAsgnEDT').removeClass('hideNotice');
                        } else if (targ.indexOf('prflCVEDT') >= 0) {
                            $('#prflAddPrsnDataEDT').addClass('hideNotice');
                            $('#prflOrgAsgnEDT').addClass('hideNotice');
                            $('#prflHomeEDT').addClass('hideNotice');
                            $('#prflOthrInfoEDT').addClass('hideNotice');
                            $('#prflCVEDT').removeClass('hideNotice');
                        } else if (targ.indexOf('prflOthrInfoEDT') >= 0) {
                            $('#prflAddPrsnDataEDT').addClass('hideNotice');
                            $('#prflOrgAsgnEDT').addClass('hideNotice');
                            $('#prflCVEDT').addClass('hideNotice');
                            $('#prflHomeEDT').addClass('hideNotice');
                            $('#prflOthrInfoEDT').removeClass('hideNotice');
                        }
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
                forceParse: false
            });
        } else if (lnkArgs.indexOf("&vtyp=1") !== -1)
        {
            var table2 = $('.extPrsnDataTblEDT').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('.extPrsnDataTblEDT').wrap('<div class="dataTables_scroll"/>');
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
        } else if (lnkArgs.indexOf("&vtyp=2") !== -1)
        {
            var table2 = $('.orgAsgnmentsTblsEDT').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('.orgAsgnmentsTblsEDT').wrap('<div class="dataTables_scroll"/>');
        } else if (lnkArgs.indexOf("&vtyp=3") !== -1)
        {
            var table2 = $('.cvTblsEDT').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('.cvTblsEDT').wrap('<div class="dataTables_scroll"/>');
        } else if (lnkArgs.indexOf("&vtyp=4") !== -1)
        {
            var table1 = $('#attchdDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdDocsTable').wrap('<div class="dataTables_scroll"/>');
            $('#attchdDocsTblForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            var table2 = $('.otherInfoTblsEDT').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "bFilter": true,
                "scrollX": false
            });
            $('.otherInfoTblsEDT').wrap('<div class="dataTables_scroll"/>');
        }
        htBody.removeClass("mdlloading");
    });
}

function getNtnlIDForm(elementID, modalBodyID, titleElementID, formElementID, tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, ntnlIDPrsn)
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
                /*$('.modal-content').resizable({
                 //alsoResize: ".modal-dialog",
                 minHeight: 600,
                 minWidth: 300
                 });*/
                $('#myFormsModalDiag').draggable();
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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

                if (addOrEdit === 'EDIT')
                {
                    /*Get various field element IDs and populate values*/
                    var $tds = $('#' + tRowElementID).find('td');
                    $('#ntnlIDpKey').val(pKeyID);
                    $('#ntnlIDCardsCountry').val($.trim($tds.eq(1).text()));
                    $('#ntnlIDCardsIDTyp').val($.trim($tds.eq(2).text()));
                    $('#ntnlIDCardsIDNo').val($.trim($tds.eq(3).text()));
                    $('#ntnlIDCardsDateIssd').val($.trim($tds.eq(4).text()));
                    $('#ntnlIDCardsExpDate').val($.trim($tds.eq(5).text()));
                    $('#ntnlIDCardsOtherInfo').val($.trim($tds.eq(6).text()));
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $('#' + formElementID).submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&vtyp=" + vtyp + "&ntnlIDpKey=" + pKeyID + "&ntnlIDPersonID=" + ntnlIDPrsn + "&tRowElmntNm=" + tRowElementID);
    });
}

function saveNtnlIDForm(elementID, tRowElementID, ntnlIDpKey, ntnlIDPersonID)
{
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var nicCountry = $("#ntnlIDCardsCountry").val() ? $("#ntnlIDCardsCountry").val() : '';
        var nicIDType = $("#ntnlIDCardsIDTyp").val() ? $("#ntnlIDCardsIDTyp").val() : '';
        var nicIDNo = typeof $("#ntnlIDCardsIDNo").val() === 'undefined' ? '' : $("#ntnlIDCardsIDNo").val();
        var nicDateIssd = typeof $("#ntnlIDCardsDateIssd").val() === 'undefined' ? '' : $("#ntnlIDCardsDateIssd").val();
        var nicExpDate = typeof $("#ntnlIDCardsExpDate").val() === 'undefined' ? '' : $("#ntnlIDCardsExpDate").val();
        var nicOtherInfo = typeof $("#ntnlIDCardsOtherInfo").val() === 'undefined' ? '' : $("#ntnlIDCardsOtherInfo").val();
        var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
        var daPersonID = typeof $("#daPersonID").val() === 'undefined' ? ntnlIDPersonID : $("#daPersonID").val();

        var errMsg = "";
        if (nicCountry.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Country cannot be empty!</span></p>';
        }
        if (nicIDType.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">ID Type cannot be empty!</span></p>';
        }
        if (nicIDNo.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">ID Number cannot be empty!</span></p>';
        }
        if (nicDateIssd.trim().length === 10)
        {
            nicDateIssd = '0' + nicDateIssd;
            $("#ntnlIDCardsDateIssd").val(nicDateIssd);
        }
        if (nicExpDate.trim().length === 10)
        {
            nicExpDate = '0' + nicExpDate;
            $("#ntnlIDCardsExpDate").val(nicExpDate);
        }
        if (rhotrim(errMsg, '; ') !== '')
        {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg});
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("Error") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText});
                } else {
                    if (ntnlIDpKey <= 0) {
                        $('#nationalIDTblEDT').append(xmlhttp.responseText);
                    } else {
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(nicCountry);
                        $tds.eq(2).text(nicIDType);
                        $tds.eq(3).text(nicIDNo);
                        $tds.eq(4).text(nicDateIssd);
                        $tds.eq(5).text(nicExpDate);
                        $tds.eq(6).text(nicOtherInfo);
                    }
                    var msg = '<span style="font-weight:bold;">Status: </span>' +
                            '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                    $("#mySelfStatusBtn").html(msg);
                    $('#' + elementID).modal('hide');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&q=UPDATE&actyp=3" +
                "&ntnlIDCardsCountry=" + nicCountry +
                "&ntnlIDCardsIDTyp=" + nicIDType +
                "&ntnlIDCardsIDNo=" + nicIDNo +
                "&ntnlIDCardsDateIssd=" + nicDateIssd +
                "&ntnlIDCardsExpDate=" + nicExpDate +
                "&ntnlIDCardsOtherInfo=" + nicOtherInfo +
                "&ntnlIDPersonID=" + daPersonID +
                "&sbmtdPersonID=" + daPersonID +
                "&ntnlIDpKey=" + ntnlIDpKey +
                "&srcForm=" + srcForm);
    });
}

function delNtnlID(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var pKeyID = -1;
    if (typeof $('#ntnlIDCardsRow' + rndmNum + '_NtnlIDpKey').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#ntnlIDCardsRow' + rndmNum + '_NtnlIDpKey').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ID?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ID?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ID?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ID...Please Wait...</p>',
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 3,
                                    ntnlIDpKey: pKeyID,
                                    srcForm: srcForm,
                                    sbmtdPersonID: sbmtdPersonID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                            var msg = '<span style="font-weight:bold;">Status: </span>' +
                                                    '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                                            $("#mySelfStatusBtn").html(msg);
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

function getAddtnlDataForm(elementID, modalBodyID, titleElementID, formElementID,
        tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, pipeSprtdFieldIDs, extDtColNum, tableElmntID)
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
                $('#' + modalBodyID + 'Diag').draggable();
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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

                if (addOrEdit === 'EDIT')
                {
                    var str_flds_array = pipeSprtdFieldIDs.split('|');
                    var $tds = $('#' + tRowElementID).find('td');
                    for (var i = 0; i < str_flds_array.length; i++) {
                        // Trim the excess whitespace.
                        var fldID = str_flds_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
                        $('#' + fldID).val($.trim($tds.eq(i + 1).text()));
                    }
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $('#' + formElementID).submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&vtyp=" + vtyp + "&addtnlPrsPkey=" + pKeyID +
                "&extDtColNum=" + extDtColNum
                + "&pipeSprtdFieldIDs=" + pipeSprtdFieldIDs +
                "&tableElmntID=" + tableElmntID + "&tRowElementID=" + tRowElementID
                + "&addOrEdit=" + addOrEdit);
    });
}

function saveAddtnlDataForm(modalBodyID, addtnlPrsPkey, pipeSprtdFieldIDs, extDtColNum, tableElmntID, tRowElementID, addOrEdit)
{
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var str_flds_array = pipeSprtdFieldIDs.split('|');
        var str_flds_array_val = pipeSprtdFieldIDs.split('|');
        var lnkArgs = "";
        var tdsAppend = "";

        var $tds = null;
        if (tRowElementID === '') {
            addOrEdit = 'ADD';
        } else {
            addOrEdit = 'EDIT';
            $tds = $("#" + tRowElementID).find('td');
        }
        var rndmNum = Math.floor((Math.random() * 99999) + parseInt(extDtColNum));
        for (var i = 0; i < str_flds_array.length; i++) {
            // Trim the excess whitespace.
            var fldID = str_flds_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
            str_flds_array[i] = fldID;
            str_flds_array_val[i] = typeof $('#' + fldID).val() === 'undefined' ? '%' : $('#' + fldID).val();
            lnkArgs = lnkArgs + "&" + fldID + "=" + str_flds_array_val[i];
            if (addOrEdit === 'EDIT')
            {
                $tds.eq(i + 1).html(str_flds_array_val[i]);
            } else
            {
                tdsAppend = tdsAppend + "<td>" + str_flds_array_val[i] + "</td>";
            }
        }
        if (addOrEdit === 'ADD')
        {
            var btnHtml = '<td><button type="button" class="btn btn-default btn-sm" onclick="getAddtnlDataForm(\'myFormsModal\', \'myFormsModalBody\', \'myFormsModalTitle\', \'addtnlPrsnTblrDataForm\',' +
                    '\'prsExtrTblrDtCol_' + extDtColNum + '_Row' + rndmNum + '\', \'Add/Edit Data\', 12, \'EDIT\', ' + addtnlPrsPkey + ', \'' + pipeSprtdFieldIDs + '\', ' + extDtColNum + ', \'extDataTblCol_' + extDtColNum + '\');">' +
                    '<img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"></button></td>';
            $('#' + tableElmntID).append('<tr id="prsExtrTblrDtCol_' + extDtColNum + '_Row' + rndmNum + '">' + btnHtml + tdsAppend + '</tr>');
        }
        var allTblValues = "";
        $('#' + tableElmntID).find('tr').each(function (i, el) {
            var $tds1 = $(this).find('td');
            for (var i = 0; i < str_flds_array.length; i++) {
                if (i == str_flds_array.length - 1)
                {
                    allTblValues = allTblValues + $tds1.eq(i + 1).text();
                } else {
                    allTblValues = allTblValues + $tds1.eq(i + 1).text() + "~";
                }
            }
            allTblValues = allTblValues + "|";
        });
        $('#addtnlPrsnDataCol' + extDtColNum).val(allTblValues);
        var xmlhttp;
        if (window.XMLHttpRequest)
        {
            xmlhttp = new XMLHttpRequest();
        } else
        {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
            {
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                $('#' + modalBodyID).html(xmlhttp.responseText);
                var msg = '<span style="font-weight:bold;">Status: </span>' +
                        '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                $("#mySelfStatusBtn").html(msg);
                $('#myFormsModal').modal('hide');
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&q=UPDATE&actyp=4&addtnlPrsPkey=" + addtnlPrsPkey
                + "&extDtColNum=" + extDtColNum + "&tableElmntID=" + tableElmntID
                + "&allTblValues=" + allTblValues + "&sbmtdPersonID=" + sbmtdPersonID);
    });
}

function getEducBkgrdForm(elementID, modalBodyID, titleElementID, formElementID,
        tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, personID)
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
                $('#' + modalBodyID + 'Diag').draggable();
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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

                if (addOrEdit === 'EDIT')
                {
                    /*Get various field element IDs and populate values*/
                    var $tds = $('#' + tRowElementID).find('td');
                    $('#educBkgrdCourseName').val($.trim($tds.eq(1).text()));
                    $('#educBkgrdSchool').val($.trim($tds.eq(2).text()));
                    $('#educBkgrdLoc').val($.trim($tds.eq(3).text()));
                    $('#educBkgrdStartDate').val($.trim($tds.eq(4).text()));
                    $('#educBkgrdEndDate').val($.trim($tds.eq(5).text()));
                    $('#educBkgrdCertObtnd').val($.trim($tds.eq(6).text()));
                    $('#educBkgrdCertTyp').val($.trim($tds.eq(7).text()));
                    $('#educBkgrdDateAwrded').val($.trim($tds.eq(8).text()));
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $('#' + formElementID).submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&vtyp=" + vtyp + "&educBkgrdPkeyID=" + pKeyID + "&sbmtdPersonID=" + personID + "&tRowElmntNm=" + tRowElementID);
    });
}

function saveEducBkgrdForm(elementID, pKeyID, personID, tRowElementID)
{
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var educBkgrdCourseName = typeof $("#educBkgrdCourseName").val() === 'undefined' ? '' : $("#educBkgrdCourseName").val();
        var educBkgrdSchool = typeof $("#educBkgrdSchool").val() === 'undefined' ? '' : $("#educBkgrdSchool").val();
        var educBkgrdLoc = typeof $("#educBkgrdLoc").val() === 'undefined' ? '' : $("#educBkgrdLoc").val();
        var educBkgrdStartDate = typeof $("#educBkgrdStartDate").val() === 'undefined' ? '' : $("#educBkgrdStartDate").val();
        var educBkgrdEndDate = typeof $("#educBkgrdEndDate").val() === 'undefined' ? '' : $("#educBkgrdEndDate").val();
        var educBkgrdCertObtnd = $("#educBkgrdCertObtnd").val() ? $("#educBkgrdCertObtnd").val() : '';
        var educBkgrdCertTyp = $("#educBkgrdCertTyp").val() ? $("#educBkgrdCertTyp").val() : '';
        var educBkgrdDateAwrded = typeof $("#educBkgrdDateAwrded").val() === 'undefined' ? '' : $("#educBkgrdDateAwrded").val();
        var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
        var errMsg = "";
        if (educBkgrdCourseName.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Course Name cannot be empty!</span></p>';
        }
        if (educBkgrdSchool.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">School/Institution cannot be empty!</span></p>';
        }
        if (educBkgrdLoc.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Location cannot be empty!</span></p>';
        }
        if (educBkgrdStartDate.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Start Date cannot be empty!</span></p>';
        }
        if (educBkgrdStartDate.trim().length === 10)
        {
            educBkgrdStartDate = '0' + educBkgrdStartDate;
            $("#educBkgrdStartDate").val(educBkgrdStartDate);
        }
        if (educBkgrdEndDate.trim().length === 10)
        {
            educBkgrdEndDate = '0' + educBkgrdEndDate;
            $("#educBkgrdEndDate").val(educBkgrdEndDate);
        }
        if (educBkgrdDateAwrded.trim().length === 10)
        {
            educBkgrdDateAwrded = '0' + educBkgrdDateAwrded;
            $("#educBkgrdDateAwrded").val(educBkgrdDateAwrded);
        }
        if (rhotrim(errMsg, '; ') !== '')
        {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg});
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("Error") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText});
                } else {
                    if (pKeyID <= 0) {
                        $('#educBkgrdTable').append(xmlhttp.responseText);
                    } else {
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(educBkgrdCourseName);
                        $tds.eq(2).text(educBkgrdSchool);
                        $tds.eq(3).text(educBkgrdLoc);
                        $tds.eq(4).text(educBkgrdStartDate);
                        $tds.eq(5).text(educBkgrdEndDate);
                        $tds.eq(6).text(educBkgrdCertObtnd);
                        $tds.eq(7).text(educBkgrdCertTyp);
                        $tds.eq(8).text(educBkgrdDateAwrded);
                    }
                    var msg = '<span style="font-weight:bold;">Status: </span>' +
                            '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                    $("#mySelfStatusBtn").html(msg);
                    $('#' + elementID).modal('hide');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=8&typ=1&pg=2&q=UPDATE&actyp=11" +
                "&educBkgrdCourseName=" + educBkgrdCourseName +
                "&educBkgrdSchool=" + educBkgrdSchool +
                "&educBkgrdLoc=" + educBkgrdLoc +
                "&educBkgrdStartDate=" + educBkgrdStartDate +
                "&educBkgrdEndDate=" + educBkgrdEndDate +
                "&educBkgrdCertObtnd=" + educBkgrdCertObtnd +
                "&educBkgrdCertTyp=" + educBkgrdCertTyp +
                "&educBkgrdDateAwrded=" + educBkgrdDateAwrded +
                "&educBkgrdPkeyID=" + pKeyID +
                "&sbmtdPersonID=" + personID +
                "&srcForm=" + srcForm);
    });
}

function delEducID(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var pKeyID = -1;
    if (typeof $('#educBkgrdRow' + rndmNum + '_PKeyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#educBkgrdRow' + rndmNum + '_PKeyID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Educ. Background?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Educ. Background?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Educ. Background?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Educ. Background...Please Wait...</p>',
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 11,
                                    educBkgrdPkeyID: pKeyID,
                                    srcForm: srcForm,
                                    sbmtdPersonID: sbmtdPersonID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                            var msg = '<span style="font-weight:bold;">Status: </span>' +
                                                    '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                                            $("#mySelfStatusBtn").html(msg);
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

function getWorkBkgrdForm(elementID, modalBodyID, titleElementID, formElementID,
        tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, personID)
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
                $('#' + modalBodyID + 'Diag').draggable();
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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

                if (addOrEdit === 'EDIT')
                {
                    /*Get various field element IDs and populate values*/
                    var $tds = $('#' + tRowElementID).find('td');
                    $('#workBkgrdJobName').val($.trim($tds.eq(1).text()));
                    $('#workBkgrdInstitution').val($.trim($tds.eq(2).text()));
                    $('#workBkgrdLoc').val($.trim($tds.eq(3).text()));
                    $('#workBkgrdStartDate').val($.trim($tds.eq(4).text()));
                    $('#workBkgrdEndDate').val($.trim($tds.eq(5).text()));
                    $('#workBkgrdJobDesc').val($.trim($tds.eq(6).text()));
                    $('#workBkgrdAchvmnts').val($.trim($tds.eq(7).text()));
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $('#' + formElementID).submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&vtyp=" + vtyp + "&workBkgrdPkeyID=" + pKeyID + "&sbmtdPersonID=" + personID + "&tRowElmntNm=" + tRowElementID);
    });
}

function saveWorkBkgrdForm(elementID, pKeyID, personID, tRowElementID)
{
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var workBkgrdJobName = typeof $("#workBkgrdJobName").val() === 'undefined' ? '' : $("#workBkgrdJobName").val();
        var workBkgrdInstitution = typeof $("#workBkgrdInstitution").val() === 'undefined' ? '' : $("#workBkgrdInstitution").val();
        var workBkgrdLoc = typeof $("#workBkgrdLoc").val() === 'undefined' ? '' : $("#workBkgrdLoc").val();
        var workBkgrdStartDate = typeof $("#workBkgrdStartDate").val() === 'undefined' ? '' : $("#workBkgrdStartDate").val();

        var workBkgrdEndDate = typeof $("#workBkgrdEndDate").val() === 'undefined' ? '' : $("#workBkgrdEndDate").val();
        var workBkgrdJobDesc = typeof $("#workBkgrdJobDesc").val() === 'undefined' ? '' : $("#workBkgrdJobDesc").val();
        var workBkgrdAchvmnts = typeof $("#workBkgrdAchvmnts").val() === 'undefined' ? '' : $("#workBkgrdAchvmnts").val();
        var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';

        var errMsg = "";
        if (workBkgrdJobName.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Job Name cannot be empty!</span></p>';
        }
        if (workBkgrdInstitution.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Institution/Work place cannot be empty!</span></p>';
        }
        if (workBkgrdLoc.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Location cannot be empty!</span></p>';
        }
        if (workBkgrdStartDate.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Start Date cannot be empty!</span></p>';
        }
        if (workBkgrdStartDate.trim().length === 10)
        {
            workBkgrdStartDate = '0' + workBkgrdStartDate;
            $("#workBkgrdStartDate").val(workBkgrdStartDate);
        }
        if (workBkgrdEndDate.trim().length === 10)
        {
            workBkgrdEndDate = '0' + workBkgrdEndDate;
            $("#workBkgrdEndDate").val(workBkgrdEndDate);
        }
        if (rhotrim(errMsg, '; ') !== '')
        {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg});
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("Error") !== -1) {
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText});
                } else {
                    if (pKeyID <= 0) {
                        $('#workBkgrdTable').append(xmlhttp.responseText);
                    } else {
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(workBkgrdJobName);
                        $tds.eq(2).text(workBkgrdInstitution);
                        $tds.eq(3).text(workBkgrdLoc);
                        $tds.eq(4).text(workBkgrdStartDate);
                        $tds.eq(5).text(workBkgrdEndDate);
                        $tds.eq(6).text(workBkgrdJobDesc);
                        $tds.eq(7).text(workBkgrdAchvmnts);
                    }
                    var msg = '<span style="font-weight:bold;">Status: </span>' +
                            '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                    $("#mySelfStatusBtn").html(msg);
                    $('#' + elementID).modal('hide');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=8&typ=1&pg=2&q=UPDATE&actyp=12" +
                "&workBkgrdJobName=" + workBkgrdJobName +
                "&workBkgrdInstitution=" + workBkgrdInstitution +
                "&workBkgrdLoc=" + workBkgrdLoc +
                "&workBkgrdStartDate=" + workBkgrdStartDate +
                "&workBkgrdEndDate=" + workBkgrdEndDate +
                "&workBkgrdJobDesc=" + workBkgrdJobDesc +
                "&workBkgrdAchvmnts=" + workBkgrdAchvmnts +
                "&workBkgrdPkeyID=" + pKeyID +
                "&sbmtdPersonID=" + personID +
                "&srcForm=" + srcForm);
    });
}

function delWorkID(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var pKeyID = -1;
    if (typeof $('#workBkgrdRow' + rndmNum + '_PKeyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#workBkgrdRow' + rndmNum + '_PKeyID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Work Background?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Work Background?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Work Background?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Work Background...Please Wait...</p>',
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 12,
                                    workBkgrdPkeyID: pKeyID,
                                    srcForm: srcForm,
                                    sbmtdPersonID: sbmtdPersonID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                            var msg = '<span style="font-weight:bold;">Status: </span>' +
                                                    '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                                            $("#mySelfStatusBtn").html(msg);
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

function getSkillsForm(elementID, modalBodyID, titleElementID, formElementID,
        tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, personID)
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
                $('#' + modalBodyID + 'Diag').draggable();
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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

                if (addOrEdit === 'EDIT')
                {
                    /*Get various field element IDs and populate values*/
                    var $tds = $('#' + tRowElementID).find('td');
                    $('#skillsLanguages').val($.trim($tds.eq(1).text()));
                    $('#skillsHobbies').val($.trim($tds.eq(2).text()));
                    $('#skillsInterests').val($.trim($tds.eq(3).text()));
                    $('#skillsConduct').val($.trim($tds.eq(4).text()));
                    $('#skillsAttitudes').val($.trim($tds.eq(5).text()));
                    $('#skillsStartDate').val($.trim($tds.eq(6).text()));
                    $('#skillsEndDate').val($.trim($tds.eq(7).text()));
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $('#' + formElementID).submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&vtyp=" + vtyp + "&skillsPkeyID=" + pKeyID + "&sbmtdPersonID=" + personID + "&tRowElmntNm=" + tRowElementID);
    });
}

function saveSkillsForm(elementID, pKeyID, personID, tRowElementID)
{
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var skillsLanguages = typeof $("#skillsLanguages").val() === 'undefined' ? '' : $("#skillsLanguages").val();
        var skillsHobbies = typeof $("#skillsHobbies").val() === 'undefined' ? '' : $("#skillsHobbies").val();
        var skillsInterests = typeof $("#skillsInterests").val() === 'undefined' ? '' : $("#skillsInterests").val();
        var skillsConduct = typeof $("#skillsConduct").val() === 'undefined' ? '' : $("#skillsConduct").val();

        var skillsAttitudes = typeof $("#skillsAttitudes").val() === 'undefined' ? '' : $("#skillsAttitudes").val();
        var skillsStartDate = typeof $("#skillsStartDate").val() === 'undefined' ? '' : $("#skillsStartDate").val();
        var skillsEndDate = typeof $("#skillsEndDate").val() === 'undefined' ? '' : $("#skillsEndDate").val();
        var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
        var errMsg = "";
        if (skillsLanguages.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Languages cannot be empty!</span></p>';
        }
        if (skillsStartDate.trim().length === 10)
        {
            skillsStartDate = '0' + skillsStartDate;
            $("#skillsStartDate").val(skillsStartDate);
        }
        if (skillsEndDate.trim().length === 10)
        {
            skillsEndDate = '0' + skillsEndDate;
            $("#skillsEndDate").val(skillsEndDate);
        }
        if (rhotrim(errMsg, '; ') !== '')
        {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg});
            return false;
        }

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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("Error") !== -1) {
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText});
                } else {
                    if (pKeyID <= 0) {
                        $('#skillsTable').append(xmlhttp.responseText);
                    } else {
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(skillsLanguages);
                        $tds.eq(2).text(skillsHobbies);
                        $tds.eq(3).text(skillsInterests);
                        $tds.eq(4).text(skillsConduct);
                        $tds.eq(5).text(skillsAttitudes);
                        $tds.eq(6).text(skillsStartDate);
                        $tds.eq(7).text(skillsEndDate);
                    }
                    var msg = '<span style="font-weight:bold;">Status: </span>' +
                            '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                    $("#mySelfStatusBtn").html(msg);
                    $('#' + elementID).modal('hide');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=8&typ=1&pg=2&q=UPDATE&actyp=13" +
                "&skillsLanguages=" + skillsLanguages +
                "&skillsHobbies=" + skillsHobbies +
                "&skillsInterests=" + skillsInterests +
                "&skillsConduct=" + skillsConduct +
                "&skillsAttitudes=" + skillsAttitudes +
                "&skillsStartDate=" + skillsStartDate +
                "&skillsEndDate=" + skillsEndDate +
                "&skillsPkeyID=" + pKeyID +
                "&sbmtdPersonID=" + personID +
                "&srcForm=" + srcForm);
    });
}

function delSkillID(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var pKeyID = -1;
    if (typeof $('#skillsTblRow' + rndmNum + '_PKeyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#skillsTblRow' + rndmNum + '_PKeyID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Skill?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Skill?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Skill?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Skill...Please Wait...</p>',
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 13,
                                    skillsPkeyID: pKeyID,
                                    srcForm: srcForm,
                                    sbmtdPersonID: sbmtdPersonID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                            var msg = '<span style="font-weight:bold;">Status: </span>' +
                                                    '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                                            $("#mySelfStatusBtn").html(msg);
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

function saveBasicPrsnData(actTyp, shdSbmt, elementID, modalBodyID, titleElementID, formElementID,
        formTitle, vtyp, pgNo, addOrEdit, actionText)
{
    if (typeof elementID === 'undefined' || elementID === null)
    {
        elementID = "";
    }
    if (typeof formTitle === 'undefined' || formTitle === null)
    {
        formTitle = "";
    }
    if (typeof shdSbmt === 'undefined' || shdSbmt === null)
    {
        shdSbmt = 0;
    }
    var daPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var daPrsnCrntOrgID= typeof $("#daPrsnCrntOrgID").val() === 'undefined' ? -1 : $("#daPrsnCrntOrgID").val();
    var daPrsnLocalID = typeof $("#daPrsnLocalID").val() === 'undefined' ? '' : $("#daPrsnLocalID").val();
    var daTitle = $("#daTitle").val() ? $("#daTitle").val() : '';
    var daFirstName = typeof $("#daFirstName").val() === 'undefined' ? '' : $("#daFirstName").val();
    var daSurName = typeof $("#daSurName").val() === 'undefined' ? '' : $("#daSurName").val();
    var daOtherNames = typeof $("#daOtherNames").val() === 'undefined' ? '' : $("#daOtherNames").val();
    var daGender = $("#daGender").val() ? $("#daGender").val() : '';

    var daMaritalStatus = $("#daMaritalStatus").val() ? $("#daMaritalStatus").val() : '';
    var daDOB = typeof $("#daDOB").val() === 'undefined' ? '' : $("#daDOB").val();
    var daPOB = typeof $("#daPOB").val() === 'undefined' ? '' : $("#daPOB").val();
    var daNationality = $("#daNationality").val() ? $("#daNationality").val() : '';
    var daHomeTown = typeof $("#daHomeTown").val() === 'undefined' ? '' : $("#daHomeTown").val();
    var daReligion = typeof $("#daReligion").val() === 'undefined' ? '' : $("#daReligion").val();

    var daCompany = typeof $("#daCompany").val() === 'undefined' ? '' : $("#daCompany").val();
    var daCompanyLoc = typeof $("#daCompanyLoc").val() === 'undefined' ? '' : $("#daCompanyLoc").val();
    var daEmail = typeof $("#daEmail").val() === 'undefined' ? '' : $("#daEmail").val();
    var daTelNos = typeof $("#daTelNos").val() === 'undefined' ? '' : $("#daTelNos").val();
    var daMobileNos = typeof $("#daMobileNos").val() === 'undefined' ? '' : $("#daMobileNos").val();
    var daFaxNo = typeof $("#daFaxNo").val() === 'undefined' ? '' : $("#daFaxNo").val();

    var daPostalAddress = typeof $("#daPostalAddress").val() === 'undefined' ? '' : $("#daPostalAddress").val();
    var daResAddress = typeof $("#daResAddress").val() === 'undefined' ? '' : $("#daResAddress").val();

    var daRelType = $("#daRelType").val() ? $("#daRelType").val() : '';
    var daRelCause = $("#daRelCause").val() ? $("#daRelCause").val() : '';
    var daRelDetails = typeof $("#daRelDetails").val() === 'undefined' ? '' : $("#daRelDetails").val();
    var daRelStartDate = typeof $("#daRelStartDate").val() === 'undefined' ? '' : $("#daRelStartDate").val();
    var daRelEndDate = typeof $("#daRelEndDate").val() === 'undefined' ? '' : $("#daRelEndDate").val();
    var errMsg = "";
    if (daPrsnLocalID.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">ID No. cannot be empty!</span></p>';
    }
    /*if (daTitle.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Title cannot be empty!</span></p>';
    }*/
    if (daFirstName.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">First Name cannot be empty!</span></p>';
    }
    if (daSurName.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Surname cannot be empty!</span></p>';
    }
    if (daGender.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Gender cannot be empty!</span></p>';
    }
    if (daMaritalStatus.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Marital Status cannot be empty!</span></p>';
    }

    if (daDOB.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Date of Birth cannot be empty!</span></p>';
    }

    if (daNationality.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Nationality cannot be empty!</span></p>';
    }
    var orgnlMobileNos = daMobileNos;
    if (daMobileNos.trim() !== '') {
        daMobileNos = daMobileNos.replace(/;/g, ',').replace(/:/g, ',').replace(/, /g, ',').replace(/ /g, ',').replace(/[, ]+/g, ",").trim();
        var tmpMobileNos = daMobileNos.split(',');
        var anodaMobileNo = '';

        for (var i = 0; i < tmpMobileNos.length; i++) {
            if (tmpMobileNos[i].indexOf('0') === 0) {
                tmpMobileNos[i] = tmpMobileNos[i].replace('0', '+233');
            }
            if (isMobileNumValid(tmpMobileNos[i])) {
                anodaMobileNo += tmpMobileNos[i] + ', ';
            }
        }
        if (anodaMobileNo.trim() !== '') {
            daMobileNos = rhotrim(anodaMobileNo, ', ');
        }
        $("#daMobileNos").val(daMobileNos);
        if (daMobileNos.trim() !== ''
                && daMobileNos.trim() !== orgnlMobileNos.trim())
        {
            var errMsg1 = '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:blue;">Your Mobile Nos provided<br/>' + orgnlMobileNos
                    + ' has been formatted and replaced with <br/>' + daMobileNos + '!</span></p>';
            bootbox.alert({
                title: 'System Alert!',
                message: errMsg1});
        }
    }
    if (daMobileNos.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Mobile No. cannot be empty!</span></p>';
    }
    var orgnlEmail = daEmail;
    if (daEmail.trim() !== '') {
        daEmail = daEmail.replace(/;/g, ',').replace(/:/g, ',').replace(/, /g, ',').replace(/ /g, ',').replace(/[, ]+/g, ",").trim();
        var tmpMails = daEmail.split(',');
        var anodaEmail = '';
        for (var i = 0; i < tmpMails.length; i++) {
            if (isEmailValid(tmpMails[i])) {
                anodaEmail += tmpMails[i] + ', ';
            }
        }
        if (anodaEmail.trim() !== '') {
            daEmail = rhotrim(anodaEmail, ', ');
        }
        $("#daEmail").val(daEmail);
        if (daEmail.trim() !== ''
                && daEmail.trim() !== orgnlEmail.trim())
        {
            var errMsg1 = '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:blue;">Your Email Address provided<br/>' + orgnlEmail
                    + ' has been formatted and replaced with <br/>' + daEmail + '!</span></p>';
            bootbox.alert({
                title: 'System Alert!',
                message: errMsg1});
        }
    }
    if (daEmail.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please provide a VALID Email Address!</span></p>';
    }
    if (actTyp === 2)
    {
        if (daRelType.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Relation type cannot be empty!</span></p>';
        }

        if (daRelCause.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Relation cause cannot be empty!</span></p>';
        }
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }

    var addtnlPrsnDataCol1 = typeof $("#addtnlPrsnDataCol1").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol1").val();
    var addtnlPrsnDataCol2 = typeof $("#addtnlPrsnDataCol2").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol2").val();
    var addtnlPrsnDataCol3 = typeof $("#addtnlPrsnDataCol3").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol3").val();
    var addtnlPrsnDataCol4 = typeof $("#addtnlPrsnDataCol4").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol4").val();
    var addtnlPrsnDataCol5 = typeof $("#addtnlPrsnDataCol5").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol5").val();
    var addtnlPrsnDataCol6 = typeof $("#addtnlPrsnDataCol6").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol6").val();
    var addtnlPrsnDataCol7 = typeof $("#addtnlPrsnDataCol7").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol7").val();
    var addtnlPrsnDataCol8 = typeof $("#addtnlPrsnDataCol8").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol8").val();
    var addtnlPrsnDataCol9 = typeof $("#addtnlPrsnDataCol9").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol9").val();
    var addtnlPrsnDataCol10 = typeof $("#addtnlPrsnDataCol10").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol10").val();
    var addtnlPrsnDataCol11 = typeof $("#addtnlPrsnDataCol11").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol11").val();
    var addtnlPrsnDataCol12 = typeof $("#addtnlPrsnDataCol12").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol12").val();
    var addtnlPrsnDataCol13 = typeof $("#addtnlPrsnDataCol13").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol13").val();
    var addtnlPrsnDataCol14 = typeof $("#addtnlPrsnDataCol14").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol14").val();
    var addtnlPrsnDataCol15 = typeof $("#addtnlPrsnDataCol15").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol15").val();
    var addtnlPrsnDataCol16 = typeof $("#addtnlPrsnDataCol16").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol16").val();
    var addtnlPrsnDataCol17 = typeof $("#addtnlPrsnDataCol17").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol17").val();
    var addtnlPrsnDataCol18 = typeof $("#addtnlPrsnDataCol18").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol18").val();
    var addtnlPrsnDataCol19 = typeof $("#addtnlPrsnDataCol19").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol19").val();
    var addtnlPrsnDataCol20 = typeof $("#addtnlPrsnDataCol20").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol20").val();
    var addtnlPrsnDataCol21 = typeof $("#addtnlPrsnDataCol21").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol21").val();
    var addtnlPrsnDataCol22 = typeof $("#addtnlPrsnDataCol22").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol22").val();
    var addtnlPrsnDataCol23 = typeof $("#addtnlPrsnDataCol23").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol23").val();
    var addtnlPrsnDataCol24 = typeof $("#addtnlPrsnDataCol24").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol24").val();
    var addtnlPrsnDataCol25 = typeof $("#addtnlPrsnDataCol25").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol25").val();
    var addtnlPrsnDataCol26 = typeof $("#addtnlPrsnDataCol26").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol26").val();
    var addtnlPrsnDataCol27 = typeof $("#addtnlPrsnDataCol27").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol27").val();
    var addtnlPrsnDataCol28 = typeof $("#addtnlPrsnDataCol28").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol28").val();
    var addtnlPrsnDataCol29 = typeof $("#addtnlPrsnDataCol29").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol29").val();
    var addtnlPrsnDataCol30 = typeof $("#addtnlPrsnDataCol30").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol30").val();
    var addtnlPrsnDataCol31 = typeof $("#addtnlPrsnDataCol31").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol31").val();
    var addtnlPrsnDataCol32 = typeof $("#addtnlPrsnDataCol32").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol32").val();
    var addtnlPrsnDataCol33 = typeof $("#addtnlPrsnDataCol33").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol33").val();
    var addtnlPrsnDataCol34 = typeof $("#addtnlPrsnDataCol34").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol34").val();
    var addtnlPrsnDataCol35 = typeof $("#addtnlPrsnDataCol35").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol35").val();
    var addtnlPrsnDataCol36 = typeof $("#addtnlPrsnDataCol36").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol36").val();
    var addtnlPrsnDataCol37 = typeof $("#addtnlPrsnDataCol37").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol37").val();
    var addtnlPrsnDataCol38 = typeof $("#addtnlPrsnDataCol38").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol38").val();
    var addtnlPrsnDataCol39 = typeof $("#addtnlPrsnDataCol39").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol39").val();
    var addtnlPrsnDataCol40 = typeof $("#addtnlPrsnDataCol40").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol40").val();
    var addtnlPrsnDataCol41 = typeof $("#addtnlPrsnDataCol41").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol41").val();
    var addtnlPrsnDataCol42 = typeof $("#addtnlPrsnDataCol42").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol42").val();
    var addtnlPrsnDataCol43 = typeof $("#addtnlPrsnDataCol43").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol43").val();
    var addtnlPrsnDataCol44 = typeof $("#addtnlPrsnDataCol44").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol44").val();
    var addtnlPrsnDataCol45 = typeof $("#addtnlPrsnDataCol45").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol45").val();
    var addtnlPrsnDataCol46 = typeof $("#addtnlPrsnDataCol46").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol46").val();
    var addtnlPrsnDataCol47 = typeof $("#addtnlPrsnDataCol47").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol47").val();
    var addtnlPrsnDataCol48 = typeof $("#addtnlPrsnDataCol48").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol48").val();
    var addtnlPrsnDataCol49 = typeof $("#addtnlPrsnDataCol49").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol49").val();
    var addtnlPrsnDataCol50 = typeof $("#addtnlPrsnDataCol50").val() === 'undefined' ? '' : $("#addtnlPrsnDataCol50").val();
    var dialog = bootbox.alert({
        title: 'Save Person',
        size: 'small',
        message: '<div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Saving Person...Please Wait...</div>',
        callback: function () {
        }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append('daPrsnPicture', $('#daPrsnPicture')[0].files[0]);
            formData.append('grp', 8);
            formData.append('typ', 1);
            formData.append('pg', 2);
            formData.append('q', 'UPDATE');
            formData.append('shdSbmt', shdSbmt);
            formData.append('actyp', actTyp);
            formData.append('daPersonID', daPersonID);
            formData.append('daPrsnCrntOrgID', daPrsnCrntOrgID);
            formData.append('sbmtdPersonID', sbmtdPersonID);
            formData.append('daPrsnLocalID', daPrsnLocalID);
            formData.append('daTitle', daTitle);

            formData.append('daFirstName', daFirstName);
            formData.append('daSurName', daSurName);
            formData.append('daOtherNames', daOtherNames);
            formData.append('daGender', daGender);

            formData.append('daMaritalStatus', daMaritalStatus);
            formData.append('daDOB', daDOB);
            formData.append('daPOB', daPOB);
            formData.append('daNationality', daNationality);

            formData.append('daHomeTown', daHomeTown);
            formData.append('daReligion', daReligion);
            formData.append('daCompany', daCompany);
            formData.append('daCompanyLoc', daCompanyLoc);

            formData.append('daEmail', daEmail);
            formData.append('daTelNos', daTelNos);
            formData.append('daMobileNos', daMobileNos);
            formData.append('daFaxNo', daFaxNo);

            formData.append('daPostalAddress', daPostalAddress);
            formData.append('daResAddress', daResAddress);
            formData.append('daRelType', daRelType);
            formData.append('daRelCause', daRelCause);
            formData.append('daRelDetails', daRelDetails);
            formData.append('daRelStartDate', daRelStartDate);
            formData.append('daRelEndDate', daRelEndDate);


            formData.append('addtnlPrsnDataCol1', addtnlPrsnDataCol1);
            formData.append('addtnlPrsnDataCol2', addtnlPrsnDataCol2);
            formData.append('addtnlPrsnDataCol3', addtnlPrsnDataCol3);
            formData.append('addtnlPrsnDataCol4', addtnlPrsnDataCol4);
            formData.append('addtnlPrsnDataCol5', addtnlPrsnDataCol5);
            formData.append('addtnlPrsnDataCol6', addtnlPrsnDataCol6);
            formData.append('addtnlPrsnDataCol7', addtnlPrsnDataCol7);
            formData.append('addtnlPrsnDataCol8', addtnlPrsnDataCol8);
            formData.append('addtnlPrsnDataCol9', addtnlPrsnDataCol9);
            formData.append('addtnlPrsnDataCol10', addtnlPrsnDataCol10);
            formData.append('addtnlPrsnDataCol11', addtnlPrsnDataCol11);
            formData.append('addtnlPrsnDataCol12', addtnlPrsnDataCol12);
            formData.append('addtnlPrsnDataCol13', addtnlPrsnDataCol13);
            formData.append('addtnlPrsnDataCol14', addtnlPrsnDataCol14);
            formData.append('addtnlPrsnDataCol15', addtnlPrsnDataCol15);
            formData.append('addtnlPrsnDataCol16', addtnlPrsnDataCol16);
            formData.append('addtnlPrsnDataCol17', addtnlPrsnDataCol17);
            formData.append('addtnlPrsnDataCol18', addtnlPrsnDataCol18);
            formData.append('addtnlPrsnDataCol19', addtnlPrsnDataCol19);
            formData.append('addtnlPrsnDataCol20', addtnlPrsnDataCol20);
            formData.append('addtnlPrsnDataCol21', addtnlPrsnDataCol21);
            formData.append('addtnlPrsnDataCol22', addtnlPrsnDataCol22);
            formData.append('addtnlPrsnDataCol23', addtnlPrsnDataCol23);
            formData.append('addtnlPrsnDataCol24', addtnlPrsnDataCol24);
            formData.append('addtnlPrsnDataCol25', addtnlPrsnDataCol25);
            formData.append('addtnlPrsnDataCol26', addtnlPrsnDataCol26);
            formData.append('addtnlPrsnDataCol27', addtnlPrsnDataCol27);
            formData.append('addtnlPrsnDataCol28', addtnlPrsnDataCol28);
            formData.append('addtnlPrsnDataCol29', addtnlPrsnDataCol29);
            formData.append('addtnlPrsnDataCol30', addtnlPrsnDataCol30);
            formData.append('addtnlPrsnDataCol31', addtnlPrsnDataCol31);
            formData.append('addtnlPrsnDataCol32', addtnlPrsnDataCol32);
            formData.append('addtnlPrsnDataCol33', addtnlPrsnDataCol33);
            formData.append('addtnlPrsnDataCol34', addtnlPrsnDataCol34);
            formData.append('addtnlPrsnDataCol35', addtnlPrsnDataCol35);
            formData.append('addtnlPrsnDataCol36', addtnlPrsnDataCol36);
            formData.append('addtnlPrsnDataCol37', addtnlPrsnDataCol37);
            formData.append('addtnlPrsnDataCol38', addtnlPrsnDataCol38);
            formData.append('addtnlPrsnDataCol39', addtnlPrsnDataCol39);
            formData.append('addtnlPrsnDataCol40', addtnlPrsnDataCol40);
            formData.append('addtnlPrsnDataCol41', addtnlPrsnDataCol41);
            formData.append('addtnlPrsnDataCol42', addtnlPrsnDataCol42);
            formData.append('addtnlPrsnDataCol43', addtnlPrsnDataCol43);
            formData.append('addtnlPrsnDataCol44', addtnlPrsnDataCol44);
            formData.append('addtnlPrsnDataCol45', addtnlPrsnDataCol45);
            formData.append('addtnlPrsnDataCol46', addtnlPrsnDataCol46);
            formData.append('addtnlPrsnDataCol47', addtnlPrsnDataCol47);
            formData.append('addtnlPrsnDataCol48', addtnlPrsnDataCol48);
            formData.append('addtnlPrsnDataCol49', addtnlPrsnDataCol49);
            formData.append('addtnlPrsnDataCol50', addtnlPrsnDataCol50);
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#myInformation").html(data.message);
                    daPersonID = data.daPersonID;
                    if (data.message.indexOf("Success") !== -1) {
                        var msg = '<span style="font-weight:bold;">Status: </span>' +
                                '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                        $("#mySelfStatusBtn").html(msg);
                    }
                    if (daPersonID > 0 && elementID.trim() !== "") {
                        $("#daPersonID").val(daPersonID);
                        if (formTitle.trim() === "View/Edit My Person Basic Profile") {
                            getBscProfile1Form(elementID, modalBodyID, titleElementID, formElementID,
                                    formTitle, daPersonID, 0, pgNo, "EDIT", actionText);
                        } else {
                            getBscProfileForm(elementID, modalBodyID, titleElementID, formElementID,
                                    formTitle, daPersonID, 0, pgNo, "EDIT", actionText);
                        }
                    }
                    if (shdSbmt > 0) {
                        setTimeout(function () {
                            dialog.modal('hide');
                            var dialog2 = bootbox.alert({
                                title: 'Submit Records Change Request',
                                size: 'large',
                                message: data.sbmt_message,
                                callback: function () {
                                    if (shdSbmt > 0)
                                    {
                                        openATab('#allmodules', 'grp=8&typ=1&pg=2&vtyp=0');
                                    }
                                }
                            });
                        }, 800);
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

function wthdrwRqst()
{
    var pKeyID = typeof $("#daChngRqstID").val() === 'undefined' ? -1 : $("#daChngRqstID").val();
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
                            openATab('#allmodules', 'grp=8&typ=1&pg=2&vtyp=0');
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 40,
                                    daChngRqstID: pKeyID
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

function uploadFileToPrsnDocs(inptElmntID, attchIDElmntID, docNmElmntID, sbmtdPrsID, rowIDAttrb)
{
    var docCtrgrName = $('#' + docNmElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Doc. Name/Description cannot be empty!</span></p>';
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
        sendFileToPrsnDocs(input.files[0], docNmElmntID, attchIDElmntID, sbmtdPrsID, function (data) {
            $("#" + attchIDElmntID).val(data.attchID);
            var dialog = bootbox.alert({
                title: 'Server Response!',
                size: 'small',
                message: '<div id="myInformation">' + data.message + '</div>',
                callback: function () {
                    if (data.message.indexOf("Success") !== -1) {
                        var $tds = $('#' + rowIDAttrb).find('td');
                        $tds.eq(1).html(docCtrgrName);
                        var dwvldBtn = '<button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" ' +
                                'onclick="doAjax(\'grp=1&amp;typ=11&amp;q=Download&amp;fnm=' + data.crptpath + '\', \'\', \'Redirect\', \'\', \'\', \'\');" ' +
                                'data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Download Document">' +
                                '<img src="cmn_images/dwldicon.png" style="height:15px; width:auto; position: relative; vertical-align: middle;"> Download ' +
                                '</button>';
                        $tds.eq(2).html(dwvldBtn);
                        var msg = '<span style="font-weight:bold;">Status: </span>' +
                                '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                        $("#mySelfStatusBtn").html(msg);
                    }
                }
            });
        });
    });
    performFileClick(inptElmntID);
}

function sendFileToPrsnDocs(file, docNmElmntID, attchIDElmntID, sbmtdPrsID, callBackFunc) {
    var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
    var data1 = new FormData();
    data1.append('daPrsnAttchmnt', file);
    data1.append('grp', 8);
    data1.append('typ', 1);
    data1.append('pg', 2);
    data1.append('q', 'UPDATE');
    data1.append('actyp', 15);
    data1.append('docCtrgrName', $('#' + docNmElmntID).val());
    data1.append('attchmentID', $('#' + attchIDElmntID).val());
    data1.append('sbmtdPersonID', sbmtdPrsID);
    data1.append('srcForm', srcForm);
    $.ajax({
        url: "index.php",
        type: 'POST',
        data: data1,
        async: true,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            callBackFunc(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        }
    });
}

function getAttchdDocs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#attchdDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdDocsSrchFor").val();
    var srchIn = typeof $("#attchdDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdDocsSrchIn").val();
    var pageNo = typeof $("#attchdDocsPageNo").val() === 'undefined' ? 1 : $("#attchdDocsPageNo").val();
    var limitSze = typeof $("#attchdDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdDocsDsplySze").val();
    var sortBy = typeof $("#attchdDocsSortBy").val() === 'undefined' ? '' : $("#attchdDocsSortBy").val();
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
    doAjaxWthCallBck(linkArgs, 'attchdDocsList', 'PasteDirect', '', '', '', function () {
        var table1 = $('#attchdDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('#attchdDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdDocs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdDocs(actionText, slctr, linkArgs);
    }
}

function delAttchdDoc(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var pKeyID = -1;
    if (typeof $('#attchdDocsRow' + rndmNum + '_AttchdDocsID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#attchdDocsRow' + rndmNum + '_AttchdDocsID').val();
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 15,
                                    attchmentID: pKeyID,
                                    srcForm: srcForm,
                                    sbmtdPersonID: sbmtdPersonID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                            var msg = '<span style="font-weight:bold;">Status: </span>' +
                                                    '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                                            $("#mySelfStatusBtn").html(msg);
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

function getAllLeaveRqsts(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allLeaveRqstsSrchFor").val() === 'undefined' ? '%' : $("#allLeaveRqstsSrchFor").val();
    var srchIn = typeof $("#allLeaveRqstsSrchIn").val() === 'undefined' ? 'Both' : $("#allLeaveRqstsSrchIn").val();
    var pageNo = typeof $("#allLeaveRqstsPageNo").val() === 'undefined' ? 1 : $("#allLeaveRqstsPageNo").val();
    var limitSze = typeof $("#allLeaveRqstsDsplySze").val() === 'undefined' ? 10 : $("#allLeaveRqstsDsplySze").val();
    var sortBy = typeof $("#allLeaveRqstsSortBy").val() === 'undefined' ? '' : $("#allLeaveRqstsSortBy").val();
    var qStrtDte = typeof $("#allLeaveRqstsStrtDate").val() === 'undefined' ? '' : $("#allLeaveRqstsStrtDate").val();
    var qEndDte = typeof $("#allLeaveRqstsEndDate").val() === 'undefined' ? '' : $("#allLeaveRqstsEndDate").val();
    var qNotSentToGl = $('#allLeaveRqstsSntToGl:checked').length > 0;
    var qUnbalncdOnly = $('#allLeaveRqstsUnbalncd:checked').length > 0;
    var qUsrGnrtd = $('#allLeaveRqstsUsrTrns:checked').length > 0;
    var qLowVal = typeof $("#allLeaveRqstsLowVal").val() === 'undefined' ? 0 : $("#allLeaveRqstsLowVal").val();
    var qHighVal = typeof $("#allLeaveRqstsHighVal").val() === 'undefined' ? 0 : $("#allLeaveRqstsHighVal").val();
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

function enterKeyFuncAllLeaveRqsts(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllLeaveRqsts(actionText, slctr, linkArgs);
    }
}

function getOneLeaveRqstsForm(pKeyID, actionTxt)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=8&typ=1&pg=4&vtyp=101&sbmtdExctnID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', actionTxt, 'Leave Plan Execution (ID:' + pKeyID + ')', 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        $('#leavePlnExctnForm').submit(function (e) {
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
        $('#allOtherInputData99').val(0);
        $('#myFormsModalNrml').off('hidden.bs.modal');
        $('#myFormsModalNrml').one('hidden.bs.modal', function (e) {
            $('#myFormsModalNrmlTitle').html('');
            $('#myFormsModalNrmlBody').html('');
            getAllLeaveRqsts('clear', '#allmodules', 'grp=8&typ=1&pg=4&vtyp=0');
            $(e.currentTarget).unbind();
        });
    });
}

function saveLeaveRqstsForm(shdSbmt)
{
    var sbmtdExctnID = typeof $("#sbmtdExctnID").val() === 'undefined' ? '-1' : $("#sbmtdExctnID").val();
    var sbmtdPlanID = typeof $("#sbmtdPlanID").val() === 'undefined' ? '-1' : $("#sbmtdPlanID").val();
    var rmrksCmnts = typeof $("#rmrksCmnts").val() === 'undefined' ? '' : $("#rmrksCmnts").val();
    var lnkdPrsnID = typeof $("#lnkdPrsnID").val() === 'undefined' ? '-1' : $("#lnkdPrsnID").val();
    var errMsg = "";
    if (Number(sbmtdExctnID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Invalid Leave Plan Execution!</span></p>';
    }
    if (Number(sbmtdPlanID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Invalid Leave Plan!</span></p>';
    }
    if (Number(lnkdPrsnID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Invalid Linked Person!</span></p>';
    }
    var slctdAbscLines = "";
    var isVld = true;
    var slctdLnCnt = 0;
    $('#onePlnExctnAbsncsTable').find('tr').each(function (i, el) {
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
                    var lnID = typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_LineID').val();
                    var lnStrtDte = typeof $('#' + rowPrfxNm + rndmNum + '_StrtDte').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_StrtDte').val();
                    var lnNoDays = typeof $('#' + rowPrfxNm + rndmNum + '_NoOfDays').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_NoOfDays').val();
                    if (lnStrtDte.trim() === '')
                    {
                        /*Do Nothing*/
                    } else if (Number(lnNoDays.replace(/[^-?0-9\.]/g, '')) > 0) {
                        $('#' + rowPrfxNm + rndmNum + '_NoOfDays').removeClass('rho-error');
                        var lnDesc = typeof $('#' + rowPrfxNm + rndmNum + '_AbsRsn').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_AbsRsn').val();
                        if (lnDesc.trim() === "")
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_AbsRsn').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_AbsRsn').removeClass('rho-error');
                        }
                        if (isVld === true)
                        {
                            slctdAbscLines = slctdAbscLines + lnID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnStrtDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnNoDays.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                            slctdLnCnt++;
                        }
                    } else {
                        isVld = false;
                        $('#' + rowPrfxNm + rndmNum + '_NoOfDays').addClass('rho-error');
                    }
                }
            }
        }
    });
    if (slctdLnCnt <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Absence Lines Cannot be Empty!</span></p>';
    }
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
        return;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Plan Execution',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Plan Execution...Please Wait...</p>',
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
                    grp: 8,
                    typ: 1,
                    pg: 4,
                    q: 'UPDATE',
                    actyp: 1,
                    sbmtdExctnID: sbmtdExctnID,
                    sbmtdPlanID: sbmtdPlanID,
                    lnkdPrsnID: lnkdPrsnID,
                    rmrksCmnts: rmrksCmnts,
                    shdSbmt: shdSbmt,
                    slctdAbscLines: slctdAbscLines
                },
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        getOneLeaveRqstsForm(sbmtdExctnID, 'ReloadDialog');
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

function delLeaveRqsts(rowIDAttrb)
{
    var msgTitle = 'Leave Plan Execution';
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        pKeyNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgTitle + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgTitle + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgTitle + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgTitle + '...Please Wait...</p>',
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
                                    grp: 8,
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

function delLeaveRqstsLines(rowIDAttrb)
{
    var msgTitle = 'Absence Request';
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        pKeyNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgTitle + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgTitle + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgTitle + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgTitle + '...Please Wait...</p>',
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
                                    grp: 8,
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

function actOnLeaveRqst(acttype)
{
    var pKeyID = typeof $("#sbmtdExctnID").val() === 'undefined' ? '-1' : $("#sbmtdExctnID").val();
    var dialog = bootbox.confirm({
        title: acttype + ' Request?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">' + acttype + '</span> this Request?<br/>Action cannot be Undone!</p>',
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
                    title: acttype + ' Request?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Acting on Request...Please Wait...</p>',
                    callback: function () {
                        if (pKeyID > 0)
                        {
                            openATab('#allmodules', 'grp=8&typ=1&pg=4&vtyp=0');
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 4,
                                    q: 'UPDATE',
                                    actyp: 40,
                                    actiontyp: acttype,
                                    sbmtdExctnID: pKeyID
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
                            dialog1.find('.bootbox-body').html('<span style="color:red;">Nothing to Act On!</span>');
                        }, 500);
                    }
                });
            }
        }
    });
}

function getAllAbsences(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allAbsencesSrchFor").val() === 'undefined' ? '%' : $("#allAbsencesSrchFor").val();
    var srchIn = typeof $("#allAbsencesSrchIn").val() === 'undefined' ? 'Both' : $("#allAbsencesSrchIn").val();
    var pageNo = typeof $("#allAbsencesPageNo").val() === 'undefined' ? 1 : $("#allAbsencesPageNo").val();
    var limitSze = typeof $("#allAbsencesDsplySze").val() === 'undefined' ? 10 : $("#allAbsencesDsplySze").val();
    var sortBy = typeof $("#allAbsencesSortBy").val() === 'undefined' ? '' : $("#allAbsencesSortBy").val();
    var qStrtDte = typeof $("#allAbsencesStrtDate").val() === 'undefined' ? '' : $("#allAbsencesStrtDate").val();
    var qEndDte = typeof $("#allAbsencesEndDate").val() === 'undefined' ? '' : $("#allAbsencesEndDate").val();
    var qNotSentToGl = $('#allAbsencesSntToGl:checked').length > 0;
    var qUnbalncdOnly = $('#allAbsencesUnbalncd:checked').length > 0;
    var qUsrGnrtd = $('#allAbsencesUsrTrns:checked').length > 0;
    var qLowVal = typeof $("#allAbsencesLowVal").val() === 'undefined' ? 0 : $("#allAbsencesLowVal").val();
    var qHighVal = typeof $("#allAbsencesHighVal").val() === 'undefined' ? 0 : $("#allAbsencesHighVal").val();
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

function enterKeyFuncAllAbsences(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllAbsences(actionText, slctr, linkArgs);
    }
}

function getAllAccrlPlns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allAccrlPlnsSrchFor").val() === 'undefined' ? '%' : $("#allAccrlPlnsSrchFor").val();
    var srchIn = typeof $("#allAccrlPlnsSrchIn").val() === 'undefined' ? 'Both' : $("#allAccrlPlnsSrchIn").val();
    var pageNo = typeof $("#allAccrlPlnsPageNo").val() === 'undefined' ? 1 : $("#allAccrlPlnsPageNo").val();
    var limitSze = typeof $("#allAccrlPlnsDsplySze").val() === 'undefined' ? 10 : $("#allAccrlPlnsDsplySze").val();
    var sortBy = typeof $("#allAccrlPlnsSortBy").val() === 'undefined' ? '' : $("#allAccrlPlnsSortBy").val();
    var qStrtDte = typeof $("#allAccrlPlnsStrtDate").val() === 'undefined' ? '' : $("#allAccrlPlnsStrtDate").val();
    var qEndDte = typeof $("#allAccrlPlnsEndDate").val() === 'undefined' ? '' : $("#allAccrlPlnsEndDate").val();
    var qNotSentToGl = $('#allAccrlPlnsSntToGl:checked').length > 0;
    var qUnbalncdOnly = $('#allAccrlPlnsUnbalncd:checked').length > 0;
    var qUsrGnrtd = $('#allAccrlPlnsUsrTrns:checked').length > 0;
    var qLowVal = typeof $("#allAccrlPlnsLowVal").val() === 'undefined' ? 0 : $("#allAccrlPlnsLowVal").val();
    var qHighVal = typeof $("#allAccrlPlnsHighVal").val() === 'undefined' ? 0 : $("#allAccrlPlnsHighVal").val();
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

function enterKeyFuncAllAccrlPlns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllAccrlPlns(actionText, slctr, linkArgs);
    }
}

function getOneAccrlPlnsForm(pKeyID, actionTxt)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    if (pKeyID <= 0)
    {
        pKeyID = typeof $("#sbmtdPlanID").val() === 'undefined' ? -1 : parseFloat($("#sbmtdPlanID").val());
    }
    if (pKeyID <= 0 && actionTxt !== 'ShowDialog') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: 'Please save form First!'});
        return false;
    }
    var lnkArgs = 'grp=8&typ=1&pg=4&vtyp=301&sbmtdPlanID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', actionTxt, 'Leave Accrual Plan (ID:' + pKeyID + ')', 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        $('#oneAccrlPlnsForm').submit(function (e) {
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
        $('#allOtherInputData99').val(0);
        $('#myFormsModalNrml').off('hidden.bs.modal');
        $('#myFormsModalNrml').one('hidden.bs.modal', function (e) {
            $('#myFormsModalNrmlTitle').html('');
            $('#myFormsModalNrmlBody').html('');
            getAllAccrlPlns('clear', '#plansLv', 'grp=8&typ=1&pg=4&vtyp=3');
            $(e.currentTarget).unbind();
        });
    });
}

function saveAccrlPlnsForm()
{
    var sbmtdPlanID = typeof $("#sbmtdPlanID").val() === 'undefined' ? '-1' : $("#sbmtdPlanID").val();
    var plnNm = typeof $("#plnNm").val() === 'undefined' ? '' : $("#plnNm").val();
    var plnDesc = typeof $("#plnDesc").val() === 'undefined' ? '' : $("#plnDesc").val();
    var plnExctnIntrvl = typeof $("#plnExctnIntrvl").val() === 'undefined' ? '' : $("#plnExctnIntrvl").val();
    var plnStrtDte = typeof $("#plnStrtDte").val() === 'undefined' ? '' : $("#plnStrtDte").val();
    var plnEndDte = typeof $("#plnEndDte").val() === 'undefined' ? '' : $("#plnEndDte").val();
    var lnkdBalsItmID = typeof $("#lnkdBalsItmID").val() === 'undefined' ? '-1' : $("#lnkdBalsItmID").val();
    var lnkdAddItmID = typeof $("#lnkdAddItmID").val() === 'undefined' ? '-1' : $("#lnkdAddItmID").val();
    var lnkdSbtrctItmID = typeof $("#lnkdSbtrctItmID").val() === 'undefined' ? '-1' : $("#lnkdSbtrctItmID").val();
    var canExcdEntlmnt = typeof $("input[name='canExcdEntlmnt']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var errMsg = "";
    if (plnNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Plan Name cannot be empty!</span></p>';
    }
    if (plnDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Plan Description cannot be empty!</span></p>';
    }
    if (plnExctnIntrvl.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Execution Interval cannot be empty!</span></p>';
    }
    if (plnStrtDte.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Plan Start Date cannot be empty!</span></p>';
    }
    /*if (Number(lnkdBalsItmID.replace(/[^-?0-9\.]/g, '')) <= 0)
     {
     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
     'font-weight:bold;color:red;">Linked Balance Item cannot be empty!</span></p>';
     }*/
    if (Number(lnkdAddItmID.replace(/[^-?0-9\.]/g, '')) == 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Linked Add Item cannot be Empty!</span></p>';
    }
    /*if (Number(lnkdSbtrctItmID.replace(/[^-?0-9\.]/g, '')) == 0)
     {
     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
     'font-weight:bold;color:red;">Linked Subtraction Item cannot be empty!</span></p>';
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
        title: 'Save Leave Plan',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Leave Plan...Please Wait...</p>',
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
                    grp: 8,
                    typ: 1,
                    pg: 4,
                    q: 'UPDATE',
                    actyp: 3,
                    sbmtdPlanID: sbmtdPlanID,
                    plnNm: plnNm,
                    plnDesc: plnDesc,
                    plnExctnIntrvl: plnExctnIntrvl,
                    plnStrtDte: plnStrtDte,
                    plnEndDte: plnEndDte,
                    lnkdBalsItmID: lnkdBalsItmID,
                    lnkdAddItmID: lnkdAddItmID,
                    lnkdSbtrctItmID: lnkdSbtrctItmID,
                    canExcdEntlmnt: canExcdEntlmnt
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

function delAccrlPlns(rowIDAttrb)
{
    var msgTitle = 'Accrual Plan';
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        pKeyNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgTitle + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgTitle + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgTitle + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgTitle + '...Please Wait...</p>',
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
                                    grp: 8,
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

function getPDivGrpForm(elementID, modalBodyID, titleElementID, formElementID,
        tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, personID)
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
                $('#' + modalBodyID + 'Diag').draggable();
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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

                if (addOrEdit === 'EDIT')
                {
                    /*Get various field element IDs and populate values*/
                    var rndmNum = tRowElementID.split("_")[1];
                    var pDivGrpPKeyID = typeof $('#pDivGrpRow' + rndmNum + '_PKeyID').val() === 'undefined' ? '-1' : $('#pDivGrpRow' + rndmNum + '_PKeyID').val();
                    var pDivGrpDivID = typeof $('#pDivGrpRow' + rndmNum + '_DivID').val() === 'undefined' ? '-1' : $('#pDivGrpRow' + rndmNum + '_DivID').val();
                    $("#pDivGrpPKeyID").val(pDivGrpPKeyID);
                    $("#pDivGrpDivID").val(pDivGrpDivID);
                    var $tds = $('#' + tRowElementID).find('td');
                    $('#pDivGrpName').val($.trim($tds.eq(1).text()));
                    $('#pDivGrpType').val($.trim($tds.eq(2).text()));
                    $('#pDivGrpStartDate').val($.trim($tds.eq(3).text()));
                    $('#pDivGrpEndDate').val($.trim($tds.eq(4).text()));
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $('#' + formElementID).submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&vtyp=" + vtyp + "&pDivGrpPkeyID=" + pKeyID + "&sbmtdPersonID=" + personID + "&tRowElmntNm=" + tRowElementID);
    });
}

function savePDivGrpForm(elementID, pKeyID, personID, tRowElementID)
{
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var pDivGrpPKeyID = typeof $("#pDivGrpPKeyID").val() === 'undefined' ? '-1' : $("#pDivGrpPKeyID").val();
        var pDivGrpDivID = typeof $("#pDivGrpDivID").val() === 'undefined' ? '-1' : $("#pDivGrpDivID").val();
        var pDivGrpName = typeof $("#pDivGrpName").val() === 'undefined' ? '' : $("#pDivGrpName").val();
        var pDivGrpType = typeof $("#pDivGrpType").val() === 'undefined' ? '' : $("#pDivGrpType").val();
        var pDivGrpStartDate = typeof $("#pDivGrpStartDate").val() === 'undefined' ? '' : $("#pDivGrpStartDate").val();
        var pDivGrpEndDate = typeof $("#pDivGrpEndDate").val() === 'undefined' ? '' : $("#pDivGrpEndDate").val();
        var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
        var errMsg = "";
        if (pDivGrpName.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Group Name cannot be empty!</span></p>';
        }
        if (pDivGrpStartDate.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Start Date cannot be empty!</span></p>';
        }
        if (pDivGrpStartDate.trim().length === 10)
        {
            pDivGrpStartDate = '0' + pDivGrpStartDate;
            $("#pDivGrpStartDate").val(pDivGrpStartDate);
        }
        if (pDivGrpEndDate.trim().length === 10)
        {
            pDivGrpEndDate = '0' + pDivGrpEndDate;
            $("#pDivGrpEndDate").val(pDivGrpEndDate);
        }
        if (rhotrim(errMsg, '; ') !== '')
        {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg});
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("Error") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText});
                } else {
                    if (pKeyID <= 0) {
                        $('#pDivGrpTable').append(xmlhttp.responseText);
                    } else {
                        var rndmNum = tRowElementID.split("_")[1];
                        $('#pDivGrpRow' + rndmNum + '_PKeyID').val(pDivGrpPKeyID);
                        $('#pDivGrpRow' + rndmNum + '_DivID').val(pDivGrpDivID);
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(pDivGrpName);
                        $tds.eq(2).text(pDivGrpType);
                        $tds.eq(3).text(pDivGrpStartDate);
                        $tds.eq(4).text(pDivGrpEndDate);
                    }
                    /*var msg = '<span style="font-weight:bold;">Status: </span>' +
                     '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                     $("#mySelfStatusBtn").html(msg);*/
                    $('#' + elementID).modal('hide');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=8&typ=1&pg=2&q=UPDATE&actyp=5" +
                "&pDivGrpName=" + pDivGrpName +
                "&pDivGrpType=" + pDivGrpType +
                "&pDivGrpStartDate=" + pDivGrpStartDate +
                "&pDivGrpEndDate=" + pDivGrpEndDate +
                "&pDivGrpPkeyID=" + pKeyID +
                "&pDivGrpDivID=" + pDivGrpDivID +
                "&sbmtdPersonID=" + personID +
                "&srcForm=" + srcForm);
    });
}

function delPDivGrpID(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var pKeyID = -1;
    if (typeof $('#pDivGrpRow' + rndmNum + '_PKeyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#pDivGrpRow' + rndmNum + '_PKeyID').val();
    }
    /*alert(pKeyID);*/
    var dialog = bootbox.confirm({
        title: 'Delete Division/Group?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Division/Group?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Division/Group?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Division/Group...Please Wait...</p>',
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 5,
                                    pDivGrpPkeyID: pKeyID,
                                    srcForm: srcForm,
                                    sbmtdPersonID: sbmtdPersonID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                            var msg = '<span style="font-weight:bold;">Status: </span>' +
                                                    '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                                            $("#mySelfStatusBtn").html(msg);
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

function getPSiteLocForm(elementID, modalBodyID, titleElementID, formElementID,
        tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, personID)
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
                $('#' + modalBodyID + 'Diag').draggable();
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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

                if (addOrEdit === 'EDIT')
                {
                    /*Get various field element IDs and populate values*/
                    var rndmNum = tRowElementID.split("_")[1];
                    var pSiteLocPKeyID = typeof $('#pSiteLocRow' + rndmNum + '_PKeyID').val() === 'undefined' ? '-1' : $('#pSiteLocRow' + rndmNum + '_PKeyID').val();
                    var pSiteLocID = typeof $('#pSiteLocRow' + rndmNum + '_SiteLocID').val() === 'undefined' ? '-1' : $('#pSiteLocRow' + rndmNum + '_SiteLocID').val();
                    $("#pSiteLocPKeyID").val(pSiteLocPKeyID);
                    $("#pSiteLocID").val(pSiteLocID);
                    var $tds = $('#' + tRowElementID).find('td');
                    $('#pSiteLocName').val($.trim($tds.eq(1).text()));
                    $('#pSiteLocType').val($.trim($tds.eq(2).text()));
                    $('#pSiteLocStartDate').val($.trim($tds.eq(3).text()));
                    $('#pSiteLocEndDate').val($.trim($tds.eq(4).text()));
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $('#' + formElementID).submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&vtyp=" + vtyp + "&pSiteLocPkeyID=" + pKeyID + "&sbmtdPersonID=" + personID + "&tRowElmntNm=" + tRowElementID);
    });
}

function savePSiteLocForm(elementID, pKeyID, personID, tRowElementID)
{
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var pSiteLocPKeyID = typeof $("#pSiteLocPKeyID").val() === 'undefined' ? '-1' : $("#pSiteLocPKeyID").val();
        var pSiteLocID = typeof $("#pSiteLocID").val() === 'undefined' ? '-1' : $("#pSiteLocID").val();
        var pSiteLocName = typeof $("#pSiteLocName").val() === 'undefined' ? '' : $("#pSiteLocName").val();
        var pSiteLocType = typeof $("#pSiteLocType").val() === 'undefined' ? '' : $("#pSiteLocType").val();
        var pSiteLocStartDate = typeof $("#pSiteLocStartDate").val() === 'undefined' ? '' : $("#pSiteLocStartDate").val();
        var pSiteLocEndDate = typeof $("#pSiteLocEndDate").val() === 'undefined' ? '' : $("#pSiteLocEndDate").val();
        var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
        var errMsg = "";
        if (pSiteLocName.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Location Name cannot be empty!</span></p>';
        }
        if (pSiteLocStartDate.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Start Date cannot be empty!</span></p>';
        }
        if (pSiteLocStartDate.trim().length === 10)
        {
            pSiteLocStartDate = '0' + pSiteLocStartDate;
            $("#pSiteLocStartDate").val(pSiteLocStartDate);
        }
        if (pSiteLocEndDate.trim().length === 10)
        {
            pSiteLocEndDate = '0' + pSiteLocEndDate;
            $("#pSiteLocEndDate").val(pSiteLocEndDate);
        }
        if (rhotrim(errMsg, '; ') !== '')
        {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg});
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("Error") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText});
                } else {
                    if (pKeyID <= 0) {
                        $('#pSiteLocTable').append(xmlhttp.responseText);
                    } else {
                        var rndmNum = tRowElementID.split("_")[1];
                        $('#pSiteLocRow' + rndmNum + '_PKeyID').val(pSiteLocPKeyID);
                        $('#pSiteLocRow' + rndmNum + '_SiteLocID').val(pSiteLocID);
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(pSiteLocName);
                        $tds.eq(2).text(pSiteLocType);
                        $tds.eq(3).text(pSiteLocStartDate);
                        $tds.eq(4).text(pSiteLocEndDate);
                    }
                    /*var msg = '<span style="font-weight:bold;">Status: </span>' +
                     '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                     $("#mySelfStatusBtn").html(msg);*/
                    $('#' + elementID).modal('hide');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=8&typ=1&pg=2&q=UPDATE&actyp=6" +
                "&pSiteLocName=" + pSiteLocName +
                "&pSiteLocType=" + pSiteLocType +
                "&pSiteLocStartDate=" + pSiteLocStartDate +
                "&pSiteLocEndDate=" + pSiteLocEndDate +
                "&pSiteLocPkeyID=" + pKeyID +
                "&pSiteLocID=" + pSiteLocID +
                "&sbmtdPersonID=" + personID +
                "&srcForm=" + srcForm);
    });
}

function delPSiteLocID(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var pKeyID = -1;
    if (typeof $('#pSiteLocRow' + rndmNum + '_PKeyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#pSiteLocRow' + rndmNum + '_PKeyID').val();
    }
    /*alert(pKeyID);*/
    var dialog = bootbox.confirm({
        title: 'Delete Site/Location?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Site/Location?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Site/Location?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Site/Location...Please Wait...</p>',
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 6,
                                    pSiteLocPkeyID: pKeyID,
                                    srcForm: srcForm,
                                    sbmtdPersonID: sbmtdPersonID
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

function getPGradeForm(elementID, modalBodyID, titleElementID, formElementID,
        tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, personID)
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
                $('#' + modalBodyID + 'Diag').draggable();
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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

                if (addOrEdit === 'EDIT')
                {
                    /*Get various field element IDs and populate values*/
                    var rndmNum = tRowElementID.split("_")[1];
                    var pGradePKeyID = typeof $('#pGradeRow' + rndmNum + '_PKeyID').val() === 'undefined' ? '-1' : $('#pGradeRow' + rndmNum + '_PKeyID').val();
                    var pGradeID = typeof $('#pGradeRow' + rndmNum + '_GradeID').val() === 'undefined' ? '-1' : $('#pGradeRow' + rndmNum + '_GradeID').val();
                    $("#pGradePKeyID").val(pGradePKeyID);
                    $("#pGradeID").val(pGradeID);
                    var $tds = $('#' + tRowElementID).find('td');
                    $('#pGradeName').val($.trim($tds.eq(1).text()));
                    $('#pGradeStartDate').val($.trim($tds.eq(2).text()));
                    $('#pGradeEndDate').val($.trim($tds.eq(3).text()));
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $('#' + formElementID).submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&vtyp=" + vtyp + "&pGradePkeyID=" + pKeyID + "&sbmtdPersonID=" + personID + "&tRowElmntNm=" + tRowElementID);
    });
}

function savePGradeForm(elementID, pKeyID, personID, tRowElementID)
{
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var pGradePKeyID = typeof $("#pGradePKeyID").val() === 'undefined' ? '-1' : $("#pGradePKeyID").val();
        var pGradeID = typeof $("#pGradeID").val() === 'undefined' ? '-1' : $("#pGradeID").val();
        var pGradeName = typeof $("#pGradeName").val() === 'undefined' ? '' : $("#pGradeName").val();
        var pGradeType = typeof $("#pGradeType").val() === 'undefined' ? '' : $("#pGradeType").val();
        var pGradeStartDate = typeof $("#pGradeStartDate").val() === 'undefined' ? '' : $("#pGradeStartDate").val();
        var pGradeEndDate = typeof $("#pGradeEndDate").val() === 'undefined' ? '' : $("#pGradeEndDate").val();
        var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
        var errMsg = "";
        if (pGradeName.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Grade Name cannot be empty!</span></p>';
        }
        if (pGradeStartDate.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Start Date cannot be empty!</span></p>';
        }
        if (pGradeStartDate.trim().length === 10)
        {
            pGradeStartDate = '0' + pGradeStartDate;
            $("#pGradeStartDate").val(pGradeStartDate);
        }
        if (pGradeEndDate.trim().length === 10)
        {
            pGradeEndDate = '0' + pGradeEndDate;
            $("#pGradeEndDate").val(pGradeEndDate);
        }
        if (rhotrim(errMsg, '; ') !== '')
        {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg});
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("Error") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText});
                } else {
                    if (pKeyID <= 0) {
                        $('#pGradeTable').append(xmlhttp.responseText);
                    } else {
                        var rndmNum = tRowElementID.split("_")[1];
                        $('#pGradeRow' + rndmNum + '_PKeyID').val(pGradePKeyID);
                        $('#pGradeRow' + rndmNum + '_GradeID').val(pGradeID);
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(pGradeName);
                        $tds.eq(2).text(pGradeStartDate);
                        $tds.eq(3).text(pGradeEndDate);
                    }
                    /*var msg = '<span style="font-weight:bold;">Status: </span>' +
                     '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                     $("#mySelfStatusBtn").html(msg);*/
                    $('#' + elementID).modal('hide');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=8&typ=1&pg=2&q=UPDATE&actyp=7" +
                "&pGradeName=" + pGradeName +
                "&pGradeType=" + pGradeType +
                "&pGradeStartDate=" + pGradeStartDate +
                "&pGradeEndDate=" + pGradeEndDate +
                "&pGradePkeyID=" + pKeyID +
                "&pGradeID=" + pGradeID +
                "&sbmtdPersonID=" + personID +
                "&srcForm=" + srcForm);
    });
}

function delPGradeID(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var pKeyID = -1;
    if (typeof $('#pGradeRow' + rndmNum + '_PKeyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#pGradeRow' + rndmNum + '_PKeyID').val();
    }
    /*alert(pKeyID);*/
    var dialog = bootbox.confirm({
        title: 'Delete Grade?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Grade?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Grade?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Grade...Please Wait...</p>',
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 7,
                                    pGradePkeyID: pKeyID,
                                    srcForm: srcForm,
                                    sbmtdPersonID: sbmtdPersonID
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

function getPSuprvsrForm(elementID, modalBodyID, titleElementID, formElementID,
        tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, personID)
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
                $('#' + modalBodyID + 'Diag').draggable();
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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

                if (addOrEdit === 'EDIT')
                {
                    /*Get various field element IDs and populate values*/
                    var rndmNum = tRowElementID.split("_")[1];
                    var pSuprvsrPKeyID = typeof $('#pSuprvsrRow' + rndmNum + '_PKeyID').val() === 'undefined' ? '-1' : $('#pSuprvsrRow' + rndmNum + '_PKeyID').val();
                    var pSuprvsrID = typeof $('#pSuprvsrRow' + rndmNum + '_SuprvsrID').val() === 'undefined' ? '-1' : $('#pSuprvsrRow' + rndmNum + '_SuprvsrID').val();
                    $("#pSuprvsrPKeyID").val(pSuprvsrPKeyID);
                    $("#pSuprvsrID").val(pSuprvsrID);
                    var $tds = $('#' + tRowElementID).find('td');
                    $('#pSuprvsrName').val($.trim($tds.eq(1).text()));
                    $('#pSuprvsrStartDate').val($.trim($tds.eq(2).text()));
                    $('#pSuprvsrEndDate').val($.trim($tds.eq(3).text()));
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $('#' + formElementID).submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&vtyp=" + vtyp + "&pSuprvsrPkeyID=" + pKeyID + "&sbmtdPersonID=" + personID + "&tRowElmntNm=" + tRowElementID);
    });
}

function savePSuprvsrForm(elementID, pKeyID, personID, tRowElementID)
{
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var pSuprvsrPKeyID = typeof $("#pSuprvsrPKeyID").val() === 'undefined' ? '-1' : $("#pSuprvsrPKeyID").val();
        var pSuprvsrID = typeof $("#pSuprvsrID").val() === 'undefined' ? '-1' : $("#pSuprvsrID").val();
        var pSuprvsrName = typeof $("#pSuprvsrName").val() === 'undefined' ? '' : $("#pSuprvsrName").val();
        var pSuprvsrType = typeof $("#pSuprvsrType").val() === 'undefined' ? '' : $("#pSuprvsrType").val();
        var pSuprvsrStartDate = typeof $("#pSuprvsrStartDate").val() === 'undefined' ? '' : $("#pSuprvsrStartDate").val();
        var pSuprvsrEndDate = typeof $("#pSuprvsrEndDate").val() === 'undefined' ? '' : $("#pSuprvsrEndDate").val();
        var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
        var errMsg = "";
        if (pSuprvsrName.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Supervisor Name cannot be empty!</span></p>';
        }
        if (pSuprvsrStartDate.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Start Date cannot be empty!</span></p>';
        }
        if (pSuprvsrStartDate.trim().length === 10)
        {
            pSuprvsrStartDate = '0' + pSuprvsrStartDate;
            $("#pSuprvsrStartDate").val(pSuprvsrStartDate);
        }
        if (pSuprvsrEndDate.trim().length === 10)
        {
            pSuprvsrEndDate = '0' + pSuprvsrEndDate;
            $("#pSuprvsrEndDate").val(pSuprvsrEndDate);
        }
        if (rhotrim(errMsg, '; ') !== '')
        {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg});
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("Error") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText});
                } else {
                    if (pKeyID <= 0) {
                        $('#pSuprvsrTable').append(xmlhttp.responseText);
                    } else {
                        var rndmNum = tRowElementID.split("_")[1];
                        $('#pSuprvsrRow' + rndmNum + '_PKeyID').val(pSuprvsrPKeyID);
                        $('#pSuprvsrRow' + rndmNum + '_SuprvsrID').val(pSuprvsrID);
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(pSuprvsrName);
                        $tds.eq(2).text(pSuprvsrStartDate);
                        $tds.eq(3).text(pSuprvsrEndDate);
                    }
                    /*var msg = '<span style="font-weight:bold;">Status: </span>' +
                     '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                     $("#mySelfStatusBtn").html(msg);*/
                    $('#' + elementID).modal('hide');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=8&typ=1&pg=2&q=UPDATE&actyp=8" +
                "&pSuprvsrName=" + pSuprvsrName +
                "&pSuprvsrType=" + pSuprvsrType +
                "&pSuprvsrStartDate=" + pSuprvsrStartDate +
                "&pSuprvsrEndDate=" + pSuprvsrEndDate +
                "&pSuprvsrPkeyID=" + pKeyID +
                "&pSuprvsrID=" + pSuprvsrID +
                "&sbmtdPersonID=" + personID +
                "&srcForm=" + srcForm);
    });
}

function delPSuprvsrID(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var pKeyID = -1;
    if (typeof $('#pSuprvsrRow' + rndmNum + '_PKeyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#pSuprvsrRow' + rndmNum + '_PKeyID').val();
    }
    /*alert(pKeyID);*/
    var dialog = bootbox.confirm({
        title: 'Delete Supervisor?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Supervisor?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Supervisor?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Supervisor...Please Wait...</p>',
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 8,
                                    pSuprvsrPkeyID: pKeyID,
                                    srcForm: srcForm,
                                    sbmtdPersonID: sbmtdPersonID
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

function getPJobForm(elementID, modalBodyID, titleElementID, formElementID,
        tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, personID)
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
                $('#' + modalBodyID + 'Diag').draggable();
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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

                if (addOrEdit === 'EDIT')
                {
                    /*Get various field element IDs and populate values*/
                    var rndmNum = tRowElementID.split("_")[1];
                    var pJobPKeyID = typeof $('#pJobRow' + rndmNum + '_PKeyID').val() === 'undefined' ? '-1' : $('#pJobRow' + rndmNum + '_PKeyID').val();
                    var pJobID = typeof $('#pJobRow' + rndmNum + '_JobID').val() === 'undefined' ? '-1' : $('#pJobRow' + rndmNum + '_JobID').val();
                    $("#pJobPKeyID").val(pJobPKeyID);
                    $("#pJobID").val(pJobID);
                    var $tds = $('#' + tRowElementID).find('td');
                    $('#pJobName').val($.trim($tds.eq(1).text()));
                    $('#pJobStartDate').val($.trim($tds.eq(2).text()));
                    $('#pJobEndDate').val($.trim($tds.eq(3).text()));
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $('#' + formElementID).submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&vtyp=" + vtyp + "&pJobPkeyID=" + pKeyID + "&sbmtdPersonID=" + personID + "&tRowElmntNm=" + tRowElementID);
    });
}

function savePJobForm(elementID, pKeyID, personID, tRowElementID)
{
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var pJobPKeyID = typeof $("#pJobPKeyID").val() === 'undefined' ? '-1' : $("#pJobPKeyID").val();
        var pJobID = typeof $("#pJobID").val() === 'undefined' ? '-1' : $("#pJobID").val();
        var pJobName = typeof $("#pJobName").val() === 'undefined' ? '' : $("#pJobName").val();
        var pJobType = typeof $("#pJobType").val() === 'undefined' ? '' : $("#pJobType").val();
        var pJobStartDate = typeof $("#pJobStartDate").val() === 'undefined' ? '' : $("#pJobStartDate").val();
        var pJobEndDate = typeof $("#pJobEndDate").val() === 'undefined' ? '' : $("#pJobEndDate").val();
        var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
        var errMsg = "";
        if (pJobName.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Job Name cannot be empty!</span></p>';
        }
        if (pJobStartDate.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Start Date cannot be empty!</span></p>';
        }
        if (pJobStartDate.trim().length === 10)
        {
            pJobStartDate = '0' + pJobStartDate;
            $("#pJobStartDate").val(pJobStartDate);
        }
        if (pJobEndDate.trim().length === 10)
        {
            pJobEndDate = '0' + pJobEndDate;
            $("#pJobEndDate").val(pJobEndDate);
        }
        if (rhotrim(errMsg, '; ') !== '')
        {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg});
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("Error") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText});
                } else {
                    if (pKeyID <= 0) {
                        $('#pJobTable').append(xmlhttp.responseText);
                    } else {
                        var rndmNum = tRowElementID.split("_")[1];
                        $('#pJobRow' + rndmNum + '_PKeyID').val(pJobPKeyID);
                        $('#pJobRow' + rndmNum + '_JobID').val(pJobID);
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(pJobName);
                        $tds.eq(2).text(pJobStartDate);
                        $tds.eq(3).text(pJobEndDate);
                    }
                    /*var msg = '<span style="font-weight:bold;">Status: </span>' +
                     '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                     $("#mySelfStatusBtn").html(msg);*/
                    $('#' + elementID).modal('hide');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=8&typ=1&pg=2&q=UPDATE&actyp=9" +
                "&pJobName=" + pJobName +
                "&pJobType=" + pJobType +
                "&pJobStartDate=" + pJobStartDate +
                "&pJobEndDate=" + pJobEndDate +
                "&pJobPkeyID=" + pKeyID +
                "&pJobID=" + pJobID +
                "&sbmtdPersonID=" + personID +
                "&srcForm=" + srcForm);
    });
}

function delPJobID(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var pKeyID = -1;
    if (typeof $('#pJobRow' + rndmNum + '_PKeyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#pJobRow' + rndmNum + '_PKeyID').val();
    }
    /*alert(pKeyID);*/
    var dialog = bootbox.confirm({
        title: 'Delete Job?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Job?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Job?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Job...Please Wait...</p>',
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 9,
                                    pJobPkeyID: pKeyID,
                                    srcForm: srcForm,
                                    sbmtdPersonID: sbmtdPersonID
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

function getPPositionForm(elementID, modalBodyID, titleElementID, formElementID,
        tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, personID)
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
                $('#' + modalBodyID + 'Diag').draggable();
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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

                if (addOrEdit === 'EDIT')
                {
                    /*Get various field element IDs and populate values*/
                    var rndmNum = tRowElementID.split("_")[1];
                    var pPositionPKeyID = typeof $('#pPositionRow' + rndmNum + '_PKeyID').val() === 'undefined' ? '-1' : $('#pPositionRow' + rndmNum + '_PKeyID').val();
                    var pPositionID = typeof $('#pPositionRow' + rndmNum + '_PositionID').val() === 'undefined' ? '-1' : $('#pPositionRow' + rndmNum + '_PositionID').val();
                    var pPositionDivID = typeof $('#pPositionRow' + rndmNum + '_PositionDivID').val() === 'undefined' ? '-1' : $('#pPositionRow' + rndmNum + '_PositionDivID').val();
                    $("#pPositionPKeyID").val(pPositionPKeyID);
                    $("#pPositionID").val(pPositionID);
                    $("#pPositionDivID").val(pPositionDivID);
                    var $tds = $('#' + tRowElementID).find('td');
                    $('#pPositionName').val($.trim($tds.eq(1).text()));
                    $('#pPositionDivNm').val($.trim($tds.eq(2).text()));
                    $('#pPositionStartDate').val($.trim($tds.eq(3).text()));
                    $('#pPositionEndDate').val($.trim($tds.eq(4).text()));
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $('#' + formElementID).submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                });
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&vtyp=" + vtyp + "&pPositionPkeyID=" + pKeyID + "&sbmtdPersonID=" + personID + "&tRowElmntNm=" + tRowElementID);
    });
}

function savePPositionForm(elementID, pKeyID, personID, tRowElementID)
{
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var pPositionPKeyID = typeof $("#pPositionPKeyID").val() === 'undefined' ? '-1' : $("#pPositionPKeyID").val();
        var pPositionID = typeof $("#pPositionID").val() === 'undefined' ? '-1' : $("#pPositionID").val();
        var pPositionName = typeof $("#pPositionName").val() === 'undefined' ? '' : $("#pPositionName").val();
        var pPositionDivID = typeof $("#pPositionDivID").val() === 'undefined' ? '-1' : $("#pPositionDivID").val();
        var pPositionDivNm = typeof $("#pPositionDivNm").val() === 'undefined' ? '' : $("#pPositionDivNm").val();
        var pPositionStartDate = typeof $("#pPositionStartDate").val() === 'undefined' ? '' : $("#pPositionStartDate").val();
        var pPositionEndDate = typeof $("#pPositionEndDate").val() === 'undefined' ? '' : $("#pPositionEndDate").val();
        var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
        var errMsg = "";
        if (pPositionName.trim().length <= 0)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Position Name cannot be empty!</span></p>';
        }
        if (pPositionStartDate.trim() === '')
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Start Date cannot be empty!</span></p>';
        }
        if (pPositionStartDate.trim().length === 10)
        {
            pPositionStartDate = '0' + pPositionStartDate;
            $("#pPositionStartDate").val(pPositionStartDate);
        }
        if (pPositionEndDate.trim().length === 10)
        {
            pPositionEndDate = '0' + pPositionEndDate;
            $("#pPositionEndDate").val(pPositionEndDate);
        }
        if (rhotrim(errMsg, '; ') !== '')
        {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg});
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("Error") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText});
                } else {
                    if (pKeyID <= 0) {
                        $('#pPositionTable').append(xmlhttp.responseText);
                    } else {
                        var rndmNum = tRowElementID.split("_")[1];
                        $('#pPositionRow' + rndmNum + '_PKeyID').val(pPositionPKeyID);
                        $('#pPositionRow' + rndmNum + '_PositionID').val(pPositionID);
                        $('#pPositionRow' + rndmNum + '_PositionDivID').val(pPositionDivID);
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(pPositionName);
                        $tds.eq(2).text(pPositionDivNm);
                        $tds.eq(3).text(pPositionStartDate);
                        $tds.eq(4).text(pPositionEndDate);
                    }
                    /*var msg = '<span style="font-weight:bold;">Status: </span>' +
                     '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                     $("#mySelfStatusBtn").html(msg);*/
                    $('#' + elementID).modal('hide');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=8&typ=1&pg=2&q=UPDATE&actyp=10" +
                "&pPositionName=" + pPositionName +
                "&pPositionDivNm=" + pPositionDivNm +
                "&pPositionStartDate=" + pPositionStartDate +
                "&pPositionEndDate=" + pPositionEndDate +
                "&pPositionPkeyID=" + pKeyID +
                "&pPositionID=" + pPositionID +
                "&pPositionDivID=" + pPositionDivID +
                "&sbmtdPersonID=" + personID +
                "&srcForm=" + srcForm);
    });
}

function delPPositionID(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var srcForm = $("#allAdminsSrcForm").val() ? $("#allAdminsSrcForm").val() : '0';
    var sbmtdPersonID = typeof $("#daPersonID").val() === 'undefined' ? -1 : $("#daPersonID").val();
    var pKeyID = -1;
    if (typeof $('#pPositionRow' + rndmNum + '_PKeyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#pPositionRow' + rndmNum + '_PKeyID').val();
    }
    /*alert(pKeyID);*/
    var dialog = bootbox.confirm({
        title: 'Delete Position?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Position?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Position?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Position...Please Wait...</p>',
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 10,
                                    pPositionPkeyID: pKeyID,
                                    srcForm: srcForm,
                                    sbmtdPersonID: sbmtdPersonID
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