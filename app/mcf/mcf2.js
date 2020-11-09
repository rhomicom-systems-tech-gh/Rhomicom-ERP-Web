/*CORE BANKING*/

function uploadCSV(elementID)
{
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var formData = new FormData();
        formData.append('inputDoc', $('#inputDoc')[0].files[0]);
        formData.append('grp', 17);
        formData.append('typ', 1);
        formData.append('q', 'UPLOAD');
        formData.append('actyp', 2);
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                $('#' + elementID).modal('hide');
                alert(data);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
}

/*CORE BANKING*/
function getCustAcctHistory(actionText, pkID)
{
    var fieldsPrfx = "trnsHstry";
    var pageNoAH = typeof $("#" + fieldsPrfx + "pageNoAH").val() === 'undefined' ? 1 : $("#" + fieldsPrfx + "pageNoAH").val();
    if (actionText == 'next')
    {
        pageNoAH = parseInt(pageNoAH) + 1;
    } else if (actionText == 'previous')
    {
        pageNoAH = parseInt(pageNoAH) - 1;
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

            var data = xmlhttp.responseText;
            var $this = $("#prflCATrnsHstryEDTtab");
            $this.tab('show');
            $("#prflCATrnsHstryEDT").html(data);
            $body.removeClass("mdlloadingDiag");
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=17&typ=1&pg=13&subPgNo=2.1&vtyp=1&vtypActn=VIEW&PKeyID=" + pkID + "&pageNoAH=" + pageNoAH);
}

function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function validateNumber(val) {

    if (isNaN(val)) {
        return val.replace(/.$/, "");
    } else {
        return val;
    }

}

function formatAmount(rawValInptID, formattedInputID) {
    var rawVal = $('#' + formattedInputID).val().replace(/,/g, "");
    var rawVal = validateNumber(rawVal);
    $('#' + rawValInptID).val(rawVal);
    var amountFormatted = addCommas($('#' + rawValInptID).val());
    $('#' + formattedInputID).val(amountFormatted);
}

function insertNewTrnsChqsRows(tableElmntID, position, inptHtml)
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
    });
}

function insertNewTrnsCashRows(tableElmntID, position, inptHtml)
{
    for (var i = 0; i < 30; i++) {
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
    $(".vmsCbTtl").focus(function () {
        $(this).select();
    });
    $(".vmsCbQty").focus(function () {
        $(this).select();
    });
    $(".vmsFncCrncy").focus(function () {
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
                $tds.eq(0).html(cntr);
            }
        }
    });
}

function insertNewExtrDstnsRows(tableElmntID, position, inptHtml)
{
    for (var i = 0; i < 10; i++) {
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

function insertNewMiscTrnsRows(tableElmntID, position, inptHtml)
{
    var dfltMiscGlAcntID = typeof $("#dfltMiscGlAcntID").val() === 'undefined' ? '-1' : $("#dfltMiscGlAcntID").val();
    var dfltMiscGlAcntNo = typeof $("#dfltMiscGlAcntNo").val() === 'undefined' ? '' : $("#dfltMiscGlAcntNo").val();
    var rwCnt = getTtlRows('oneTrnsfrDstntnsTable') + 1;
    if (rwCnt <= 10)
    {
        rwCnt = 10;
    }
    for (var i = 0; i < rwCnt; i++) {
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
    var cntr = 0;
    var trnsfrOrderDstAcNum = typeof $('#trnsfrOrderDstAcNum').val() === 'undefined' ? '' : $('#trnsfrOrderDstAcNum').val();
    var acctNoFindRawTxt1 = typeof $('#acctNoFindRawTxt1').val() === 'undefined' ? '' : $('#acctNoFindRawTxt1').val();
    var $tds2 = $('#' + tableElmntID).find('tr');
    var prfxName3 = $tds2.eq(1).attr('id').split("_")[0];
    var rndmNum3 = $tds2.eq(1).attr('id').split("_")[1];
    var lnCustAcntNo3 = typeof $('#' + prfxName3 + rndmNum3 + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + prfxName3 + rndmNum3 + '_DstAcntNo').val();
    if (lnCustAcntNo3.trim() === "" && trnsfrOrderDstAcNum.trim() !== "") {
        $('#' + prfxName3 + rndmNum3 + '_DstAcntNo').val(trnsfrOrderDstAcNum);
        $('#' + prfxName3 + rndmNum3 + '_AcRawTxt1').val(acctNoFindRawTxt1);
        $('#' + prfxName3 + rndmNum3 + '_AcntTitle').val(acctNoFindRawTxt1);
    }
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
                var lnMiscGlAcntNo = typeof $('#' + prfxName1 + rndmNum1 + '_GlAcntNum').val() === 'undefined' ? '' : $('#' + prfxName1 + rndmNum1 + '_GlAcntNum').val();
                var lnCustAcntNo = typeof $('#' + prfxName1 + rndmNum1 + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + prfxName1 + rndmNum1 + '_DstAcntNo').val();
                if (lnMiscGlAcntNo.trim() === "" && dfltMiscGlAcntNo.trim() !== "") {
                    $('#' + prfxName1 + rndmNum1 + '_GlAcntNum').val(dfltMiscGlAcntNo);
                    $('#' + prfxName1 + rndmNum1 + '_GlAcntID').val(dfltMiscGlAcntID);
                }
                if (i > 1) {
                    var $tds1 = $("#oneTrnsfrDstntnsTable").find('tr');
                    var prfxName2 = $tds1.eq(i - 1).attr('id').split("_")[0];
                    var rndmNum2 = $tds1.eq(i - 1).attr('id').split("_")[1];
                    var lnCustAcntNo2 = typeof $('#' + prfxName2 + rndmNum2 + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + prfxName2 + rndmNum2 + '_DstAcntNo').val();
                    var lnCustAcntName = typeof $('#' + prfxName2 + rndmNum2 + '_AcRawTxt1').val() === 'undefined' ? '' : $('#' + prfxName2 + rndmNum2 + '_AcRawTxt1').val();
                    if (lnCustAcntNo.trim() === "" && dfltMiscGlAcntNo.trim() !== "") {
                        $('#' + prfxName1 + rndmNum1 + '_DstAcntNo').val(lnCustAcntNo2);
                        $('#' + prfxName1 + rndmNum1 + '_AcRawTxt1').val(lnCustAcntName);
                        $('#' + prfxName1 + rndmNum1 + '_AcntTitle').val(lnCustAcntName);
                    }
                }
            }
        }
    });
}

function insertNewBlkMiscTrnsRows(tableElmntID, position, inptHtml)
{
    var rwCnt1 = getTtlRows('oneVmsBCashTrnsLnsTable');
    var rwCnt = rwCnt1 + getTtlRows('oneVmsTrnsLnsTable');
    if (rwCnt <= 10)
    {
        rwCnt = 10;
    }
    if ($('#' + tableElmntID + ' > tbody > tr').length == 1)
    {
        if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
            $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
        }
    }
    for (var a = 0; a < rwCnt; a++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        $('#' + tableElmntID).append(nwInptHtml);
    }
    $('[data-toggle="tooltip"]').tooltip();
    var dfltMiscGlAcntID = typeof $("#dfltMiscGlAcntID").val() === 'undefined' ? '-1' : $("#dfltMiscGlAcntID").val();
    var dfltMiscGlAcntNo = typeof $("#dfltMiscGlAcntNo").val() === 'undefined' ? '' : $("#dfltMiscGlAcntNo").val();
    var cntr = 0;
    var b = 0;
    var c = 0;
    $('#' + tableElmntID).find('tr').each(function (i, el) {
        if (typeof $(el).attr('id') === 'undefined')
        {
            /*Do Nothing*/
        } else {
            cntr++;
            var prfxName1 = $(el).attr('id').split("_")[0];
            var rndmNum1 = $(el).attr('id').split("_")[1];
            var $tds = $("#" + prfxName1 + "_" + rndmNum1).find('td');
            $tds.eq(0).html(cntr);
            var lnMiscGlAcntNo = typeof $('#' + prfxName1 + rndmNum1 + '_GlAcntNum').val() === 'undefined' ? '' : $('#' + prfxName1 + rndmNum1 + '_GlAcntNum').val();
            var lnCustAcntNo = typeof $('#' + prfxName1 + rndmNum1 + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + prfxName1 + rndmNum1 + '_DstAcntNo').val();
            if (lnMiscGlAcntNo.trim() === "") {
                $('#' + prfxName1 + rndmNum1 + '_GlAcntNum').val(dfltMiscGlAcntNo);
                $('#' + prfxName1 + rndmNum1 + '_GlAcntID').val(dfltMiscGlAcntID);
            }
            if (i <= rwCnt1) {
                var $tds1 = $("#oneVmsBCashTrnsLnsTable").find('tr');
                if (typeof $tds1.eq(b).attr('id') === 'undefined') {
                    b++;
                }
                if (typeof $tds1.eq(b).attr('id') === 'undefined') {
                } else {
                    var prfxName2 = $tds1.eq(b).attr('id').split("_")[0];
                    var rndmNum2 = $tds1.eq(b).attr('id').split("_")[1];
                    var lnCustAcntNo2 = typeof $('#' + prfxName2 + rndmNum2 + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + prfxName2 + rndmNum2 + '_DstAcntNo').val();
                    var lnCustAcntName = typeof $('#' + prfxName2 + rndmNum2 + '_AcntTitle').val() === 'undefined' ? '' : $('#' + prfxName2 + rndmNum2 + '_AcntTitle').val();
                    if (lnCustAcntNo.trim() === "") {
                        $('#' + prfxName1 + rndmNum1 + '_DstAcntNo').val(lnCustAcntNo2);
                        $('#' + prfxName1 + rndmNum1 + '_AcRawTxt1').val(lnCustAcntName);
                        $('#' + prfxName1 + rndmNum1 + '_AcntTitle').val(lnCustAcntName);
                    }
                    b++;
                }
            } else {
                var $tds1 = $("#oneVmsTrnsLnsTable").find('tr');
                if (typeof $tds1.eq(c).attr('id') === 'undefined') {
                    c++;
                }
                if (typeof $tds1.eq(c).attr('id') === 'undefined') {
                } else {
                    var prfxName2 = $tds1.eq(c).attr('id').split("_")[0];
                    var rndmNum2 = $tds1.eq(c).attr('id').split("_")[1];
                    var lnCustAcntNo2 = typeof $('#' + prfxName2 + rndmNum2 + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + prfxName2 + rndmNum2 + '_DstAcntNo').val();
                    var lnCustAcntName = typeof $('#' + prfxName2 + rndmNum2 + '_AcRawTxt1').val() === 'undefined' ? '' : $('#' + prfxName2 + rndmNum2 + '_AcRawTxt1').val();
                    if (lnCustAcntNo.trim() === "") {
                        $('#' + prfxName1 + rndmNum1 + '_DstAcntNo').val(lnCustAcntNo2);
                        $('#' + prfxName1 + rndmNum1 + '_AcRawTxt1').val(lnCustAcntName);
                        $('#' + prfxName1 + rndmNum1 + '_AcntTitle').val(lnCustAcntName);
                    }
                    c++;
                }
            }
        }
    });
}

function formatAmountTable(tblRowID) {
    var $tds = $('#' + tblRowID).find('td');
    var ttlRowAmntFmtd = $("#" + tblRowID + " .cbTTlAmnt").val();
    var ttlRowAmntRaw = ttlRowAmntFmtd.replace(/,/g, "");
    var rawVal = validateNumber(ttlRowAmntRaw);
    $.trim($tds.eq(8).text(rawVal));
    var amountFormatted = addCommas($tds.eq(8).text());
    $('#' + tblRowID).find("td:eq(3) input[type='text']").val(amountFormatted);
}

function formatAmountTableGen(tblRowID, elementID, elementColNo) {
    var $tds = $('#' + tblRowID).find('td');
    var ttlRowAmntFmtd = $("#" + tblRowID + " #" + elementID).val();
    var ttlRowAmntRaw = ttlRowAmntFmtd.replace(/,/g, "");
    var rawVal = validateNumber(ttlRowAmntRaw);
    var amountFormatted = addCommas(rawVal);
    $('#' + tblRowID).find("td:eq(" + elementColNo + ") input[type='text']").val(amountFormatted);
}

function delTrnsChqLine(rowIDAttrb)
{
    var msg = 'CHEQUE Transaction Line';
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var chqNo = '';
    if (typeof $('#oneVmsTrnsRow' + rndmNum + '_TrnsLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#oneVmsTrnsRow' + rndmNum + '_TrnsLnID').val();
        chqNo = $('#oneVmsTrnsRow' + rndmNum + '_ChqNo').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msg + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Cheque Line?<br/>Action cannot be Undone!</p>',
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE_CORE_BNK',
                                    actyp: 2,
                                    trnsLnID: pKeyID,
                                    chqNo: chqNo
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                            calcCshBrkdwnRowVal('oneVmsTrnsRow_1');
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
                            calcCshBrkdwnRowVal('oneVmsTrnsRow_1');
                        }, 500);
                    }
                });
            }
        }
    });
}

function getForm(elementID, modalBodyID, titleElementID, formTitle, pgNo, subPgNo, vtyp, vtypActn, pKeyID, listTableID, rowID)
{

    if (!$.fn.DataTable.isDataTable('#' + listTableID)) {
        var table1 = $('#' + listTableID).DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#' + listTableID + ' tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                table1.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
        $('#' + listTableID + ' tbody').on('mouseenter', 'tr', function () {
            if ($(this).hasClass('highlight')) {
                $(this).removeClass('highlight');
            } else {
                table1.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });
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
            $('#' + titleElementID).html(formTitle + "<span style='color:red;font-weight: bold;float:right;width:50%;'>Approved</span>");
            $('#' + modalBodyID).html(xmlhttp.responseText);
            $(function () {
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
            $('#' + elementID).on('show.bs.modal', function (e) {
                $(this).find('.modal-body').css({
                    'max-height': '100%'
                });
            });
            $body.removeClass("mdlloadingDiag");
            $('#' + elementID).modal('show', {backdrop: 'static'});
            $body = $("body");
            $(document).ready(function () {
                $('#prpsOfAcct').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonWidth: '335px'
                });
                $('#acctTrnsTyp').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonWidth: '335px'
                });
                $('#srcOfFunds').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonWidth: '335px',
                    maxHeight: 250
                });
                $('#acctTitle').val($('#bnkCustomer').val());
                var myOpts = $("#accMndte").options;
                accMandate = [];
                for (var i = 0; i < myOpts.length; i++) {
                    accMandate[i] = myOpts[i].value;
                }

            });
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=17&typ=1&pg=" + pgNo + "&subPgNo=" + subPgNo + "&vtyp=" + vtyp + "&vtypActn=" + vtypActn + "&PKeyID=" + pKeyID);
}

function saveAccountTrns(pgNo, subPgNo, vtyp, trnsType, shdSbmt)
{
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeAcntTrnsBtn");
    }
    var trnsAmnt = typeof $("#trnsAmount").val() === 'undefined' ? '0.00' : $("#trnsAmount").val();
    var trnsAmntRaw = typeof $("#trnsAmntRaw").val() === 'undefined' ? '0.00' : $("#trnsAmntRaw").val();
    trnsAmnt = fmtAsNumber('trnsAmount');
    var trnsAmntRaw1 = trnsAmnt;
    var ttlAmount1 = 0;
    var val1;
    var rate1;
    var crncyNm = $('#mcfPymtCrncyNm').val();
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                val1 = $("#oneVmsTrnsRow_" + rndmNum1 + " .chqValCls").val().replace(/,/g, "");
                rate1 = $("#oneVmsTrnsRow_" + rndmNum1 + " .chqRatesCls").val().replace(/,/g, "");
                ttlAmount1 = ttlAmount1 + (Number(val1) * Number(rate1));
            }
        }
    });
    var ttlDocAmnt5 = Number(trnsAmntRaw.replace(/[^-?0-9\.]/g, ''));
    if ((ttlAmount1 + trnsAmntRaw1) != 0) {
        ttlDocAmnt5 = (ttlAmount1 + trnsAmntRaw1);
    }
    $('#tllrTrnsAmntTtlFld').text(crncyNm + ' ' + addCommas((ttlDocAmnt5).toFixed(2)));
    $('#ttlDocAmntVal').val((ttlDocAmnt5).toFixed(2));
    var obj;
    $body = $("body");
    $body.addClass("mdlloadingDiag");
    var acctTrnsId = typeof $("#acctTrnsId").val() === 'undefined' ? -1 : $("#acctTrnsId").val();
    var acctID = typeof $("#acctID").val() === 'undefined' ? -1 : $("#acctID").val();
    var docType = typeof $("#docType").val() === 'undefined' ? '' : $("#docType").val();
    var docNum = typeof $("#docNum").val() === 'undefined' ? '' : $("#docNum").val();
    var miscTrnsType = typeof $("#miscTrnsType").val() === 'undefined' ? '' : $("#miscTrnsType").val();
    var miscTrnsSubType = typeof $("#miscTrnsSubType").val() === 'undefined' ? '' : $("#miscTrnsSubType").val();
    if (miscTrnsType.trim() !== '') {
        trnsType = miscTrnsType;
        docType = "Paperless";
        docNum = "";
    }
    var trnsExchngRate = typeof $("#trnsExchngRate").val() === 'undefined' ? 0.00 : $("#trnsExchngRate").val();
    var ttlDocAmntVal = typeof $("#ttlDocAmntVal").val() === 'undefined' ? 0.00 : $("#ttlDocAmntVal").val();
    var mcfPymtCrncyNm = typeof $("#mcfPymtCrncyNm").val() === 'undefined' ? '' : $("#mcfPymtCrncyNm").val();
    var trnsDesc = typeof $("#trnsDesc").val() === 'undefined' ? '' : $("#trnsDesc").val();
    var isSelf = typeof $("input[name='trnsPersonType']:checked").val() === 'undefined' ? 'Others' : $("input[name='trnsPersonType']:checked").val();
    var trnsPersonName = typeof $("#trnsPersonName").val() === 'undefined' ? '' : $("#trnsPersonName").val();
    var trnsPersonTelNo = typeof $("#trnsPersonTelNo").val() === 'undefined' ? '' : $("#trnsPersonTelNo").val();
    var trnsPersonAddress = typeof $("#trnsPersonAddress").val() === 'undefined' ? '' : $("#trnsPersonAddress").val();
    var trnsPersonIDType = typeof $("#trnsPersonIDType").val() === 'undefined' ? '' : $("#trnsPersonIDType").val();
    var trnsPersonIDNumber = typeof $("#trnsPersonIDNumber").val() === 'undefined' ? '' : $("#trnsPersonIDNumber").val();
    var disbmntHdrID = typeof $("#disbmntHdrID").val() === 'undefined' ? -1 : $("#disbmntHdrID").val();
    var disbmntDetID = typeof $("#disbmntDetID").val() === 'undefined' ? -1 : $("#disbmntDetID").val();
    var miscTrnsBalsGlAcntID = typeof $("#miscTrnsBalsGlAcntID").val() === 'undefined' ? '-1' : $("#miscTrnsBalsGlAcntID").val();
    var errMsg = "";
    if (acctID == "" || acctID == -1)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please Provide Account First!</span></p>';
    }
    if (trnsDesc == "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Transaction Narration is required!</span></p>';
    }
    if ((docType == "Cheque" || docType == "Voucher Slip") && docNum.trim() == "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Provide Document Number!</span></p>';
    }
    if (mcfPymtCrncyNm.trim() == "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Amount Currency cannot be blank!</span></p>';
    }
    if (docNum.trim() != "" && docType == "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Select Document Type!</span></p>';
    }
    /** NEW 12062017 **/
    if (trnsType.trim() == "LOAN_PYMNT") {
        if (disbmntHdrID == -1)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Select Disbursement No.!</span></p>';
        }
        if (disbmntDetID == -1)
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Select Disbursed Loan Request!</span></p>';
        }
    }
    if (typeof $("#trnsExchngRate").val() !== 'undefined') {
        trnsExchngRate = fmtAsNumber('trnsExchngRate').toFixed(5);
    }
    ttlDocAmntVal = fmtAsNumber('ttlDocAmntVal').toFixed(2);
    if (ttlDocAmntVal == 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please provide a valid Non-Zero Value for the Total Amount Involved!</span></p>';
    }
    if (isSelf === "Others" && miscTrnsSubType.trim() == "") {
        if (trnsPersonName.trim() == "")
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Person Name cannot be empty!</span></p>';
        }
        if (trnsPersonTelNo.trim() == "")
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">Contact No. cannot be empty!</span></p>';
        }
        if (trnsPersonIDType.trim() == "")
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">ID Type cannot be empty!</span></p>';
        }
        if (trnsPersonIDNumber.trim() == "")
        {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;color:red;">ID Number cannot be empty!</span></p>';
        }
    }
    if (miscTrnsBalsGlAcntID <= 0 && miscTrnsSubType.trim() == "MISC_TRANS") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">GL Account Number cannot be empty!</span></p>';
    }
    var acctSignatories = "";
    var cntaSignLines = 0;
    $('#acctSignatoriesTblAdd').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#acctSignatoriesTblAddRow_' + rndmNum).val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#acctSignatoriesTblAddRow_' + rndmNum).find('input[type="checkbox"]:checked').length == "1") {
                        acctSignatories = acctSignatories
                                + $('#acctSignatoriesTblAddRow' + rndmNum + '_ID').text().replace(/(~)+/g, "{-;-;}").replace(/(\|)+/g, "{:;:;}") + "|";
                        cntaSignLines = cntaSignLines + 1;
                    }
                }
            }
        }
    });
    if ((acctSignatories.trim() == "" || cntaSignLines <= 0) && trnsType.trim() == "WITHDRAWAL" && miscTrnsSubType.trim() == "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Mandate for Withdrawal cannot be empty!</span></p>';
    }
    var slctdTrnsChqLines = "";
    var cntaChqLines = 0;
    var isVld = true;
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#oneVmsTrnsRow' + rndmNum + '_chqNo').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnChqTypeID = typeof $('#oneVmsTrnsRow' + rndmNum + '_chqType').val() === 'undefined' ? '' : $('#oneVmsTrnsRow' + rndmNum + '_chqType').val();
                    if (lnChqTypeID.trim() == "")
                    {
                        isVld = false;
                        $('#oneVmsTrnsRow' + rndmNum + '_chqType').addClass('rho-error');
                    } else {
                        $('#oneVmsTrnsRow' + rndmNum + '_chqType').removeClass('rho-error');
                    }
                    var lnBnkID = typeof $('#oneVmsTrnsRow' + rndmNum + '_bnkID').val() === 'undefined' ? 0 : $('#oneVmsTrnsRow' + rndmNum + '_bnkID').val();
                    if (lnBnkID <= 0)
                    {
                        if (lnChqTypeID != 'In-House') {
                            isVld = false;
                            $('#oneVmsTrnsRow' + rndmNum + '_bnkID').addClass('rho-error');
                        }
                    } else {
                        $('#oneVmsTrnsRow' + rndmNum + '_bnkID').removeClass('rho-error');
                    }
                    var lnBrnchID = typeof $('#oneVmsTrnsRow' + rndmNum + '_brnchID').val() === 'undefined' ? 0 : $('#oneVmsTrnsRow' + rndmNum + '_brnchID').val();
                    if (lnBrnchID <= 0)
                    {
                        if (lnChqTypeID != 'In-House') {
                            isVld = false;
                            $('#oneVmsTrnsRow' + rndmNum + '_brnchID').addClass('rho-error');
                        }
                    } else {
                        $('#oneVmsTrnsRow' + rndmNum + '_brnchID').removeClass('rho-error');
                    }
                    var lnChqNo = typeof $('#oneVmsTrnsRow' + rndmNum + '_chqNo').val() === 'undefined' ? 0 : $('#oneVmsTrnsRow' + rndmNum + '_chqNo').val();
                    if (lnChqNo <= 0)
                    {
                        isVld = false;
                        $('#oneVmsTrnsRow' + rndmNum + '_chqNo').addClass('rho-error');
                    } else {
                        $('#oneVmsTrnsRow' + rndmNum + '_chqNo').removeClass('rho-error');
                    }
                    var lnChqDte = typeof $('#oneVmsTrnsRow' + rndmNum + '_chqDte').val() === 'undefined' ? '' : $('#oneVmsTrnsRow' + rndmNum + '_chqDte').val();
                    if (lnChqDte == '')
                    {
                        isVld = false;
                        $('#oneVmsTrnsRow' + rndmNum + '_chqDte').addClass('rho-error');
                    } else {
                        $('#oneVmsTrnsRow' + rndmNum + '_chqDte').removeClass('rho-error');
                    }
                    var lnChqVal = typeof $('#oneVmsTrnsRow' + rndmNum + '_chqVal').val() === 'undefined' ? '0' : $('#oneVmsTrnsRow' + rndmNum + '_chqVal').val();
                    if (Number(lnChqVal.replace(/[^-?0-9\.]/g, '')) <= 0)
                    {
                        isVld = false;
                        $('#oneVmsTrnsRow' + rndmNum + '_chqVal').addClass('rho-error');
                    } else {
                        $('#oneVmsTrnsRow' + rndmNum + '_chqVal').removeClass('rho-error');
                    }
                    fmtAsNumber('oneVmsTrnsRow' + rndmNum + '_chqVal');
                    var lnChqMndate = typeof $('#oneVmsTrnsRow' + rndmNum + '_chqMandate').val() === 'undefined' ? '' : $('#oneVmsTrnsRow' + rndmNum + '_chqMandate').val();
                    if (lnChqMndate.trim() == "")
                    {
                        isVld = false;
                        $('#oneVmsTrnsRow' + rndmNum + '_chqMandateLbl').addClass('rho-error');
                    } else {
                        $('#oneVmsTrnsRow' + rndmNum + '_chqMandateLbl').removeClass('rho-error');
                    }
                    var exchngRate = typeof $('#oneVmsTrnsRow' + rndmNum + '_exchngRate').val() === 'undefined' ? '0' : $('#oneVmsTrnsRow' + rndmNum + '_exchngRate').val();
                    if (Number(exchngRate.replace(/[^-?0-9\.]/g, '')) <= 0)
                    {
                        isVld = false;
                        $('#oneVmsTrnsRow' + rndmNum + '_exchngRate').addClass('rho-error');
                    } else {
                        $('#oneVmsTrnsRow' + rndmNum + '_exchngRate').removeClass('rho-error');
                    }
                    $('#oneVmsTrnsRow' + rndmNum + '_exchngRate').val(Number(exchngRate.replace(/[^-?0-9\.]/g, '')).toFixed(4));
                    var crncyName = typeof $('#oneVmsTrnsRow' + rndmNum + '_chqCurNm').val() === 'undefined' ? '' : $('#oneVmsTrnsRow' + rndmNum + '_chqCurNm').val();
                    if (crncyName.trim() == "")
                    {
                        isVld = false;
                        $('#oneVmsTrnsRow' + rndmNum + '_chqCurNm1').addClass('rho-error');
                    } else {
                        $('#oneVmsTrnsRow' + rndmNum + '_chqCurNm1').removeClass('rho-error');
                    }
                    if (isVld === true)
                    {
                        slctdTrnsChqLines = slctdTrnsChqLines + $('#oneVmsTrnsRow' + rndmNum + '_TrnsLnID').val() + "~"
                                + $('#oneVmsTrnsRow' + rndmNum + '_chqType').val().replace(/(~)/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#oneVmsTrnsRow' + rndmNum + '_bnkID').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#oneVmsTrnsRow' + rndmNum + '_brnchID').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#oneVmsTrnsRow' + rndmNum + '_chqNo').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#oneVmsTrnsRow' + rndmNum + '_chqDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#oneVmsTrnsRow' + rndmNum + '_chqVal').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                /*+ $('#oneVmsTrnsRow' + rndmNum + '_chqValDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"*/
                                + $('#oneVmsTrnsRow' + rndmNum + '_chqCurNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#oneVmsTrnsRow' + rndmNum + '_exchngRate').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#oneVmsTrnsRow' + rndmNum + '_chqMandate').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        cntaChqLines = cntaChqLines + 1;
                    }
                }
            }
        }
    });
    if (isVld === false && (trnsType.trim() == "DEPOSIT" || trnsType.trim() == "LOAN_REPAY"))
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Cheque Details Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    var isMndVld = validateAcctTrnsSignatories();
    if (isMndVld === false && trnsType.trim() == "WITHDRAWAL" && miscTrnsSubType.trim() == "") {
        return false;
    }
    $body.removeClass("mdlloadingDiag");
    var msg = 'Banking Transaction';
    var dialog = bootbox.alert({
        title: 'Save ' + msg,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
        callback: function () {
            acctTrnsId = typeof $("#acctTrnsId").val() === 'undefined' ? -1 : $("#acctTrnsId").val();
            if (acctTrnsId > 0) {
                getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', trnsType, pgNo, subPgNo, 0, 'ADD', acctTrnsId);
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
                    grp: 17,
                    typ: 1,
                    pg: pgNo,
                    q: 'UPDATE_CORE_BNK',
                    subPgNo: subPgNo,
                    vtyp: vtyp,
                    acctID: acctID,
                    docType: docType,
                    docNum: docNum,
                    miscTrnsType: miscTrnsType,
                    miscTrnsSubType: miscTrnsSubType,
                    trnsExchngRate: trnsExchngRate,
                    trnsAmnt: trnsAmnt,
                    trnsDesc: trnsDesc,
                    acctSignatories: acctSignatories,
                    slctdTrnsChqLines: slctdTrnsChqLines,
                    PKeyID: acctTrnsId,
                    trnsType: trnsType,
                    isSelf: isSelf,
                    trnsPersonName: trnsPersonName,
                    trnsPersonTelNo: trnsPersonTelNo,
                    trnsPersonAddress: trnsPersonAddress,
                    trnsPersonIDType: trnsPersonIDType,
                    trnsPersonIDNumber: trnsPersonIDNumber,
                    shdSbmt: shdSbmt,
                    ttlDocAmntVal: ttlDocAmntVal,
                    mcfPymtCrncyNm: mcfPymtCrncyNm,
                    disbmntHdrID: disbmntHdrID,
                    disbmntDetID: disbmntDetID,
                    miscTrnsBalsGlAcntID: miscTrnsBalsGlAcntID
                },
                success: function (result) {
                    var msg = "";
                    var data = result;
                    var p_acctTrnsId = -1;
                    if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                            replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                            replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                        obj = $.parseJSON(data);
                        p_acctTrnsId = obj.acctTrnsId;
                        msg = obj.sbmtMsg;
                        if (p_acctTrnsId > 0) {
                            $("#acctTrnsId").val(p_acctTrnsId);
                        }
                        if (msg.trim() === '') {
                            msg = "Transaction Saved Successfully!";
                        }
                    } else {
                        msg = data;
                    }
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(msg);
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

function saveAccountTrnsRvrs(pgNo, subPgNo, vtyp, trnsType, shdSbmt)
{
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslAcntTrnsBtn");
    }
    var obj;
    /** NEW **/
    var pgNoVoid = 1040;
    var qVoid = 'VOID_CORE_BNK';
    if (trnsType == "LOAN_REPAY") {
        pgNoVoid = 1060;
        qVoid = 'VOID';
    }
    $body = $("body");
    var acctTrnsId = typeof $("#acctTrnsId").val() === 'undefined' ? -1 : $("#acctTrnsId").val();
    var acctID = typeof $("#acctID").val() === 'undefined' ? -1 : $("#acctID").val();
    var trnsDesc = typeof $("#trnsDesc").val() === 'undefined' ? '' : $("#trnsDesc").val();
    var trnsDesc1 = typeof $("#trnsDesc1").val() === 'undefined' ? '' : $("#trnsDesc1").val();
    var errMsg = "";
    if (acctID <= 0 || acctTrnsId <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Invalid Transaction! Cannot Reverse</span></p>';
    }
    if ((trnsDesc === "" || trnsDesc === trnsDesc1) && (shdSbmt <= 0))
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#trnsDesc").addClass('rho-error');
        $("#trnsDesc").attr("readonly", false);
        $("#fnlzeRvrslAcntTrnsBtn").attr("disabled", false);
    } else {
        $("#trnsDesc").removeClass('rho-error');
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    var isMndVld = validateAcctTrnsSignatories();
    if (isMndVld === false && trnsType.trim() == "WITHDRAWAL") {
        return false;
    }
    var msgsTitle = 'Banking Transaction';
    var msgBody = "";
    if (shdSbmt > 0)
    {
        msgBody = '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">INITIATE REVERSAL</span> of this ' + msgsTitle + '?<br/>The request will be submitted automatically to an Authorizer!<br/>After authorization you must come back here to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE the REVERSAL</span>!</p>';
    } else {
        msgBody = '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">INITIATE REVERSAL</span> of this ' + msgsTitle + '?<br/>After authorization you must come back here to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE the REVERSAL</span>!</p>';
    }
    var dialog = bootbox.confirm({
        title: 'Void ' + msgsTitle + '?',
        size: 'small',
        message: msgBody,
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
                var msg = 'Banking Transaction';
                var dialog = bootbox.alert({
                    title: 'Save ' + msg,
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
                    callback: function () {
                        acctTrnsId = typeof $("#acctTrnsId").val() === 'undefined' ? -1 : $("#acctTrnsId").val();
                        if (acctTrnsId > 0) {
                            getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', trnsType, pgNo, subPgNo, 0, 'ADD', acctTrnsId);
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
                                grp: 17,
                                typ: 1,
                                pg: pgNoVoid,
                                q: qVoid,
                                subPgNo: subPgNo,
                                acctID: acctID,
                                trnsDesc: trnsDesc,
                                PKeyID: acctTrnsId,
                                trnsType: trnsType,
                                shdSbmt: shdSbmt
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_acctTrnsId = -1;
                                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                                        replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                                        replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                                    obj = $.parseJSON(data);
                                    p_acctTrnsId = obj.acctTrnsId;
                                    msg = obj.sbmtMsg;
                                    if (p_acctTrnsId > 0) {
                                        $("#acctTrnsId").val(p_acctTrnsId);
                                    }
                                    if (msg.trim() === '') {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find('.bootbox-body').html(msg);
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

function exprtMiscTrns()
{
    var fieldsPrfx = 'mcfAcntTrns';
    if (typeof fieldsPrfx === 'undefined' || fieldsPrfx === null)
    {
        fieldsPrfx = 'mcfAcntTrns';
    }
    var srchFor = typeof $("#" + fieldsPrfx + "SrchFor").val() === 'undefined' ? '%' : $("#" + fieldsPrfx + "SrchFor").val();
    var srchIn = typeof $("#" + fieldsPrfx + "SrchIn").val() === 'undefined' ? 'Both' : $("#" + fieldsPrfx + "SrchIn").val();
    var pageNo = typeof $("#" + fieldsPrfx + "PageNo").val() === 'undefined' ? 1 : $("#" + fieldsPrfx + "PageNo").val();
    var limitSze = typeof $("#" + fieldsPrfx + "DsplySze").val() === 'undefined' ? 10 : $("#" + fieldsPrfx + "DsplySze").val();
    var sortBy = typeof $("#" + fieldsPrfx + "SortBy").val() === 'undefined' ? '' : $("#" + fieldsPrfx + "SortBy").val();
    var qStrtDte = typeof $('#' + fieldsPrfx + "StrtDate").val() === 'undefined' ? '' : $('#' + fieldsPrfx + "StrtDate").val();
    var qEndDte = typeof $('#' + fieldsPrfx + "EndDate").val() === 'undefined' ? '' : $('#' + fieldsPrfx + "EndDate").val();
    var msgTitle = "Transactions";
    var exprtMsg = '<form role="form">' +
            '<p style="color:#000;">' +
            'How many ' + msgTitle + ' will you like to Export?' +
            '<br/>1=No ' + msgTitle + '(Empty Template)' +
            '<br/>2=All ' + msgTitle + '' +
            '<br/>3-Infinity=Specify the exact number of ' + msgTitle + ' to Export<br/>' +
            '</p>' +
            '<div class="form-group" style="margin-bottom:10px !important;">' +
            '<div class="input-group">' +
            '<span class="input-group-addon" id="basic-addon1">' +
            '<i class="fa fa-sort-numeric-asc fa-fw fa-border"></i></span>' +
            '<input type="number" class="form-control" placeholder="" aria-describedby="basic-addon1" id="recsToExprt" name="recsToExprt" onkeyup="" tabindex="0" autofocus>' +
            '</div>' +
            '</div>' +
            '<p style="font-size:12px;" id="msgAreaExprt">&nbsp;' +
            '</p>' +
            '</form>';
    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_SMALL,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: 'Export ' + msgTitle + '!',
        message: exprtMsg,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) {
        },
        onshown: function (dialogItself) {
            exprtBtn = dialogItself.getButton('btn_exprt_recs');
            $('#recsToExprt').focus();
        },
        buttons: [{
                label: 'Cancel',
                icon: 'glyphicon glyphicon-menu-left',
                cssClass: 'btn-default',
                action: function (dialogItself) {
                    window.clearInterval(prgstimerid2);
                    dialogItself.close();
                    ClearAllIntervals();
                }
            }, {
                id: 'btn_exprt_recs',
                label: 'Export',
                icon: 'glyphicon glyphicon-menu-right',
                cssClass: 'btn-primary',
                action: function (dialogItself) {
                    /*Validate Input and Do Ajax if OK*/
                    var inptNum = $('#recsToExprt').val();
                    if (!isNumber(inptNum))
                    {
                        var dialog = bootbox.alert({
                            title: 'Exporting ' + msgTitle + '',
                            size: 'small',
                            message: 'Please provide a valid Number!',
                            callback: function () {
                            }
                        });
                        return false;
                    } else {
                        var $button = this;
                        $button.disable();
                        $button.spin();
                        dialogItself.setClosable(false);
                        document.getElementById("msgAreaExprt").innerHTML = "<img style=\"width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;\" src='cmn_images/ajax-loader2.gif'/><br/><span style=\"color:blue;font-size:11px;text-align: left;margin-top:0px;\">Working on Export...Please Wait...</span>";
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'IMPRT_EXPRT_TRNS',
                                    actyp: 3,
                                    inptNum: inptNum,
                                    srchFor: srchFor,
                                    srchIn: srchIn,
                                    pageNo: pageNo,
                                    limitSze: limitSze,
                                    sortBy: sortBy,
                                    qStrtDte: qStrtDte,
                                    qEndDte: qEndDte
                                }
                            });
                            prgstimerid2 = window.setInterval(rfrshExprtMiscTrnsPrcs, 1000);
                        });
                    }
                }
            }]
    });
}

function rfrshExprtMiscTrnsPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 17,
            typ: 1,
            pg: 14,
            q: 'IMPRT_EXPRT_TRNS',
            actyp: 4
        },
        success: function (data) {
            if (data.percent >= 100) {
                if (data.message.indexOf('Error') !== -1)
                {
                    $("#msgAreaExprt").html(data.message);
                } else {
                    $("#msgAreaExprt").html(data.message + '<br/><a href="' + data.dwnld_url + '">Click to Download File!</a>');
                }
                exprtBtn.enable();
                exprtBtn.stopSpin();
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            } else {
                $("#msgAreaExprt").html('<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>'
                        + data.message);
                document.getElementById("msgAreaExprt").innerHTML = '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>'
                        + data.message;
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        }
    });
}

function importMiscTrns()
{
    var msgTitle = "Transactions";
    var dataToSend = "";
    var isFileValid = true;
    var dialog1 = bootbox.confirm({
        title: 'Import ' + msgTitle + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">IMPORT ' + msgTitle.toUpperCase() + '</span>?<br/>Action cannot be Undone!</p>',
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
                if (isReaderAPIAvlbl()) {
                    $("#allOtherFileInput6").val('');
                    $("#allOtherFileInput6").off('change');
                    $("#allOtherFileInput6").change(function () {
                        var fileName = $(this).val();
                        var input = document.getElementById('allOtherFileInput6');
                        var file = input.files[0];
                        var output = '';
                        output += '<span style="font-weight:bold;">' + escape(file.name) + '</span><br />\n';
                        output += ' - FileType: ' + (file.type || 'n/a') + '<br />\n';
                        output += ' - FileSize: ' + file.size + ' bytes<br />\n';
                        output += ' - LastModified: ' + (file.lastModifiedDate ? file.lastModifiedDate.toLocaleDateString() : 'n/a') + '<br />\n';
                        var reader = new FileReader();
                        BootstrapDialog.show({
                            size: BootstrapDialog.SIZE_LARGE,
                            type: BootstrapDialog.TYPE_DEFAULT,
                            title: 'Validating Selected File',
                            message: '<div id="myProgress"><div id="myBar"></div></div><div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Validating Selected File...Please Wait...</div><br/><div id="fileInformation">' + output + '</div>',
                            animate: true,
                            closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: false,
                            onshow: function (dialogItself) {
                                setTimeout(function () {
                                    var $footerButton = dialogItself.getButton('btn-srvr-prcs');
                                    $footerButton.disable();
                                    reader.onerror = function (evt) {
                                        switch (evt.target.error.code) {
                                            case evt.target.error.NOT_FOUND_ERR:
                                                alert('File Not Found!');
                                                break;
                                            case evt.target.error.NOT_READABLE_ERR:
                                                alert('File is not readable');
                                                break;
                                            case evt.target.error.ABORT_ERR:
                                                break;
                                            default:
                                                alert('An error occurred reading this file.');
                                        }
                                        ;
                                    };
                                    reader.onprogress = function (evt) {
                                        if (evt.lengthComputable) {
                                            var percentLoaded = Math.round((evt.loaded / evt.total) * 100);
                                            var elem = document.getElementById('myBar');
                                            elem.style.width = percentLoaded + '%';
                                            if (percentLoaded < 100) {
                                                $("#myInformation").html('<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>' + percentLoaded + '% Validating Selected File...Please Wait...</span>');
                                            } else {
                                                $("#myInformation").html('<span style="color:green;"><i class="fa fa-check"></i>' + percentLoaded + '% Validating Selected File Completed!</span>');
                                                var $footerButton = dialogItself.getButton('btn-srvr-prcs');
                                                if (isFileValid == true) {
                                                    $footerButton.enable();
                                                } else {
                                                    $footerButton.disable();
                                                }
                                            }
                                        }
                                    };
                                    reader.onabort = function (e) {
                                        alert('File read cancelled');
                                    };
                                    reader.onloadstart = function (e) {
                                        var elem = document.getElementById('myBar');
                                        elem.style.width = '1%';
                                        $("#myInformation").html('<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>1% Started Importing ' + msgTitle + '...Please Wait...</span>');
                                    };
                                    reader.onload = function (event) {
                                        try {
                                            var csv = event.target.result;
                                            var data = $.csv.toArrays(csv);
                                            var rwCntr = 0;
                                            var colCntr = 0;
                                            var vldRwCntr = 0;
                                            var number = "";
                                            var trnsNum = "";
                                            var trnsType = "";
                                            var trnsDesc = "";
                                            var acntNum = "";
                                            var acntTitle = "";
                                            var trnsDate = "";
                                            var crncyNm = "";
                                            var amount = "";
                                            var status = "";
                                            var balance = "";
                                            for (var row in data) {
                                                for (var item in data[row]) {
                                                    colCntr++;
                                                    switch (colCntr) {
                                                        case 1:
                                                            number = data[row][item];
                                                            break;
                                                        case 2:
                                                            trnsNum = data[row][item];
                                                            break;
                                                        case 3:
                                                            trnsType = data[row][item];
                                                            break;
                                                        case 4:
                                                            trnsDesc = data[row][item];
                                                            break;
                                                        case 5:
                                                            acntNum = data[row][item];
                                                            break;
                                                        case 6:
                                                            acntTitle = data[row][item];
                                                            break;
                                                        case 7:
                                                            trnsDate = data[row][item];
                                                            break;
                                                        case 8:
                                                            crncyNm = data[row][item];
                                                            break;
                                                        case 9:
                                                            amount = data[row][item];
                                                            break;
                                                        case 10:
                                                            status = data[row][item];
                                                            break;
                                                        case 11:
                                                            balance = data[row][item];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                title: 'Error-Validating Selected File',
                                                                size: 'small',
                                                                message: '<span style="color:red;font-weight:bold:">An error occurred reading this file.Invalid Column in File!<br/>Row No.:' + number + '</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                }
                                                            });
                                                    }
                                                    ;
                                                }
                                                if (rwCntr === 0) {
                                                    if (number.toUpperCase() === "NO." && trnsNum.toUpperCase() === "Transaction Reference No.".toUpperCase()
                                                            && trnsType.toUpperCase() === "Transaction Type**".toUpperCase())
                                                    {

                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            title: 'Error-Import ' + msgTitle + '',
                                                            size: 'small',
                                                            message: '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            }
                                                        });
                                                    }
                                                }
                                                if (trnsDesc.trim() !== "" && trnsType.trim() !== ""
                                                        && acntNum.trim() !== ""
                                                        && trnsDate.trim() !== ""
                                                        && crncyNm.trim() !== ""
                                                        && amount.trim() !== "")
                                                {
                                                    dataToSend = dataToSend + number.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + trnsNum.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + trnsType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + trnsDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + acntNum.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + acntTitle.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + trnsDate.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + crncyNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + amount.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + status.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + balance.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                                    vldRwCntr++;
                                                }
                                                colCntr = 0;
                                                rwCntr++;
                                            }
                                            output += '<br/><span style="color:blue;font-weight:bold;">No. of Valid Rows:' + vldRwCntr;
                                            output += '<br/>Total No. of Rows:' + rwCntr + '</span>';
                                            $("#fileInformation").html(output);
                                        } catch (err) {
                                            var dialog = bootbox.alert({
                                                title: 'Error-Import ' + msgTitle + '',
                                                size: 'small',
                                                message: 'Error:' + err.message,
                                                callback: function () {
                                                    isFileValid = false;
                                                    reader.abort();
                                                }
                                            });
                                        }
                                    };
                                    reader.readAsText(file);
                                }, 500);
                            },
                            buttons: [{
                                    label: 'Cancel',
                                    icon: 'glyphicon glyphicon-menu-left',
                                    cssClass: 'btn-default',
                                    action: function (dialogItself) {
                                        isFileValid = false;
                                        reader.abort();
                                        dialogItself.close();
                                    }
                                }, {
                                    id: 'btn-srvr-prcs',
                                    label: 'Start Server Processing',
                                    icon: 'glyphicon glyphicon-menu-right',
                                    cssClass: 'btn-primary',
                                    action: function (dialogItself) {
                                        if (isFileValid == true) {
                                            dialogItself.close();
                                            saveCustTrns(dataToSend);
                                        } else {
                                            var dialog = bootbox.alert({
                                                title: 'Error-Import ' + msgTitle + '',
                                                size: 'small',
                                                message: '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                callback: function () {
                                                }
                                            });
                                        }
                                    }
                                }]
                        });
                    });
                    performFileClick('allOtherFileInput6');
                }
            }
        }
    });
}

function saveCustTrns(dataToSend)
{
    if (dataToSend.trim() === '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;">No Data to Send!</span></p>'
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Importing Customer Transactions',
        size: 'small',
        message: '<div id="myProgress1"><div id="myBar1"></div></div><div id="myInformation1"><i class="fa fa-spin fa-spinner"></i> Importing Customer Transactions...Please Wait...</div>',
        callback: function () {
            window.clearInterval(prgstimerid2);
            ClearAllIntervals();
            getCustData('', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.3', 'mcfAcntTrns');
            ClearAllIntervals();
        }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            //alert(dataToSend);
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 17,
                    typ: 1,
                    pg: 14,
                    q: 'IMPRT_EXPRT_TRNS',
                    actyp: 1,
                    dataToSend: dataToSend
                },
                success: function (data) {
                    $("#myInformation1").html(data.message);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
            prgstimerid2 = window.setInterval(rfrshSaveCustTrns, 1000);
        });
    });
}

function rfrshSaveCustTrns() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 17,
            typ: 1,
            pg: 14,
            q: 'IMPRT_EXPRT_TRNS',
            actyp: 2
        },
        success: function (data) {
            var elem = document.getElementById('myBar1');
            elem.style.width = data.percent + '%';
            $("#myInformation1").html(data.message);
            if (data.percent >= 100) {
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        }
    });
}

function chckMyTillPos(loadOption, lnkArgs) {
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', loadOption, 'My Till Position', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        loadScript("app/mcf/mcf.js?v=" + jsFilesVrsn, function () {
            $(document).ready(function () {
                if (!$.fn.DataTable.isDataTable('#allCageItemsTable')) {
                    var table1 = $('#allCageItemsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#allCageItemsTable').wrap('<div class="dataTables_scroll"/>');
                    $('#allCageItemsForm').submit(function (e) {
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
                }
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
        });
    });
}

function getAllCageItems(actionText, slctr, linkArgs)
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
            '&isFrmBnkng=1' + "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte;
    doAjaxWthCallBck(linkArgs, 'myFormsModalLg', 'ReloadDialog', 'My Till Position', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        if (!$.fn.DataTable.isDataTable('#allCageItemsTable')) {
            var table1 = $('#allCageItemsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allCageItemsTable').wrap('<div class="dataTables_scroll"/>');
            $('#allCageItemsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            var table2 = $('#allCageItemTrnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allCageItemTrnsTable').wrap('<div class="dataTables_scroll"/>');
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
            var table3 = $('#oneVmsTrnsLnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneVmsTrnsLnsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#oneVmsTrnsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
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
        $('#myFormsModalLg').off('hidden.bs.modal');
    });
}

function enterKeyFuncAllCageItems(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllCageItems(actionText, slctr, linkArgs);
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
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte;
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
    });
}

function getMcfDynmcCageFnPos(pKeyIDElementID, vwtype)
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

function uploadFileToMcfDocs(inptElmntID, attchIDElmntID, docNmElmntID, docFileTypeElmntID, docTrnsTypeElmntID, sbmtdHdrID)
{
    var docCtrgrName = $('#' + docNmElmntID).val();
    var docFileType = $('#' + docFileTypeElmntID).val();
    var docTrsType = $('#' + docTrnsTypeElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === ''
            || docFileType.trim() === ''
            || docTrsType.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Doc. Name/Description, File Type and Transaction Type must all be provided!</span></p>';
    }
    /*if (sbmtdHdrID <= 0)
     {
     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
     'font-weight:bold;color:red;">Attachments must be done on a saved Document/Transaction!</span></p>';
     }*/
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
        sendFileToMcfDocs(input.files[0], docNmElmntID, docFileTypeElmntID, docTrnsTypeElmntID, attchIDElmntID, sbmtdHdrID, function (data) {
            $("#" + attchIDElmntID).val(data.attchID);
            $('#attchdMCFDocsNwTrnsId').val(data.NwTrnsId);
            if (docTrsType == "DEPOSIT" || docTrsType == "WITHDRAWAL" || docTrsType == "LOAN_REPAY" || docTrsType == "LOAN_PYMNT") {
                $('#acctTrnsId').val(data.NwTrnsId);
            }
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

function sendFileToMcfDocs(file, docNmElmntID, docFileTypeElmntID, docTrnsTypeElmntID, attchIDElmntID, sbmtdHdrID, callBackFunc) {
    var data1 = new FormData();
    data1.append('daMCFAttchmnt', file);
    data1.append('grp', 17);
    data1.append('typ', 1);
    data1.append('pg', 8);
    data1.append('subPgNo', '140.1');
    data1.append('q', 'UPDATEDOC');
    data1.append('actyp', 1);
    data1.append('docCtrgrName', $('#' + docNmElmntID).val());
    data1.append('docFileType', $('#' + docFileTypeElmntID).val());
    data1.append('docTrsType', $('#' + docTrnsTypeElmntID).val());
    data1.append('attchmentID', $('#' + attchIDElmntID).val());
    if ($('#' + docTrnsTypeElmntID).val() == "DEPOSIT" || $('#' + docTrnsTypeElmntID).val() == "WITHDRAWAL" ||
            $('#' + docTrnsTypeElmntID).val() == "LOAN_REPAY" || $('#' + docTrnsTypeElmntID).val() == "LOAN_PYMNT") {
        data1.append('sbmtdHdrID', $('#acctTrnsId').val());
        data1.append('pAcctID', $('#acctID').val());
    } else {
        data1.append('sbmtdHdrID', sbmtdHdrID);
        data1.append('pAcctID', -1);
    }
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

function getAttchdMcfDocs(actionText, slctr, linkArgs)
{
    var pKeyID = $('#acctTrnsId').val();
    if (pKeyID == "" || pKeyID == undefined) {
        pKeyID = -1;
    }
    var acctTitle = $('#acctTitle').val();
    var acctID = $('#acctID').val();
    var srchFor = typeof $("#attchdMCFDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdMCFDocsSrchFor").val();
    var srchIn = typeof $("#attchdMCFDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdMCFDocsSrchIn").val();
    var pageNo = typeof $("#attchdMCFDocsPageNo").val() === 'undefined' ? 1 : $("#attchdMCFDocsPageNo").val();
    var limitSze = typeof $("#attchdMCFDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdMCFDocsDsplySze").val();
    var sortBy = typeof $("#attchdMCFDocsSortBy").val() === 'undefined' ? '' : $("#attchdMCFDocsSortBy").val();
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
    linkArgs = linkArgs.replace('sbmtdHdrID=-1', 'sbmtdHdrID=' + pKeyID) + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs, 'myFormsModaly', 'ReloadDialog', 'Banking Attached Documents', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        if (!$.fn.DataTable.isDataTable('#attchdMCFDocsTable')) {
            var table1 = $('#attchdMCFDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdMCFDocsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdMCFDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdMcfDocs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdMcfDocs(actionText, slctr, linkArgs);
    }
}

function delAttchdMcfDoc(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID = typeof $("#sbmtdHdrID").val() === 'undefined' ? -1 : $("#sbmtdHdrID").val();
    var docNum = typeof $("#mcfTrnsNum").val() === 'undefined' ? '' : $("#mcfTrnsNum").val();
    var pKeyID = -1;
    if (typeof $('#attchdMCFDocsRow' + rndmNum + '_AttchdMCFDocsID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#attchdMCFDocsRow' + rndmNum + '_AttchdMCFDocsID').val();
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETEDOC',
                                    subPgNo: '140.1',
                                    actyp: 1,
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


function getCoreBankingForm(elementID, modalBodyID, titleElementID, formTitle, pgNo, subPgNo, vtyp, vtypActn, pKeyID, otherPrsnTyp, rowID)
{
    var acctTrnsId = typeof $("#acctTrnsId").val() === 'undefined' ? -1 : $("#acctTrnsId").val();
    $("#tblRowID").val(rowID);
    if (!$.fn.DataTable.isDataTable('#indCustTable')) {
        var table1 = $('#indCustTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#indCustTable tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                table1.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
        $('#indCustTable tbody').on('mouseenter', 'tr', function () {
            if ($(this).hasClass('highlight')) {
                $(this).removeClass('highlight');
            } else {
                table1.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });
    }
    $body = $("body");
    $body.addClass("mdlloading");
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
            $('#allmodulestab').tab('show');
            $('#allmodules').html(xmlhttp.responseText);
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            $(function () {
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
            $('#allOtherInputData99').val('0');
            trnsPrsnTypeChng();
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=17&typ=1&pg=" + pgNo + "&subPgNo=" + subPgNo + "&vtyp=" + vtyp + "&vtypActn=" + vtypActn + "&PKeyID=" + pKeyID);
}

function calcCashBreadownRowVal(tblRowID, classNm) {
    var ttlDenomVal = 0;
    var qty;
    var $tds;
    var val;
    var curItemVal;
    if (classNm == "cbQty") {
        qty = $("#" + tblRowID + " .cbQty").val();
        $tds = $('#' + tblRowID).find('td');
        val = $.trim($tds.eq(5).text());
        ttlDenomVal = (Number(qty) * Number(val));
        if ($.trim($tds.eq(0).text()) == 'Coin') {
            $('#' + tblRowID).find("td:eq(3) input[type='text']").val(addCommas(ttlDenomVal.toFixed(2)));
        } else {
            $('#' + tblRowID).find("td:eq(3) input[type='text']").val(addCommas(ttlDenomVal));
        }

        curItemVal = $('#cashDenominationsTtl').val();
    } else if (classNm == "cbTTlAmnt") {
        $tds = $('#' + tblRowID).find('td');
        ttlDenomVal = Number($("#" + tblRowID + " .cbTTlAmnt").val().replace(/,/g, ""));
        $("#" + tblRowID + " .cbTTlAmnt").val(addCommas(ttlDenomVal.toFixed(2)));
        val = $.trim($tds.eq(5).text());
        qty = (Number(ttlDenomVal) / Number(val));
        $('#' + tblRowID).find("td:eq(2) input[type='number']").val(qty);
        curItemVal = $('#cashDenominationsTtl').val();
    } else if (classNm == "cbExchngRate") {
        $("#" + tblRowID + " .cbExchngRate").val(Number($("#" + tblRowID + " .cbExchngRate").val().replace(/,/g, "")).toFixed(4));
        curItemVal = $('#cashDenominationsTtl').val();
    } else {
        curItemVal = $('#cashDenominationsTtl').val();
    }
    calcAllMcfCbTtl();
}

function initCashBreakdownForm(rowNum, ttlRows) {

    $('#cashDenominationsID').val(rowNum);
    $('#cashDenominationsTtl').val(ttlRows);
}

function saveCashDenominationForm(elementID, subPgNo)
{
    var ttlAmount = 0;
    var ttlAmount1 = 0;
    var $tds;
    var val;
    var qty1;
    var rate1;
    var curItemVal;
    var isVld = false;
    var crncyNm = $('#mcfPymtCrncyNm').val();
    var errMsg = "";
    $('#cashBreakdownTblEDT').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                qty1 = $("#cashBreakdownRow_" + rndmNum1 + " .cbQty").val();
                rate1 = $("#cashBreakdownRow_" + rndmNum1 + " .cbExchngRate").val().replace(/,/g, "");
                $tds = $("#cashBreakdownRow_" + rndmNum1).find('td');
                val = $.trim($tds.eq(5).text());
                ttlAmount = ttlAmount + (Number(qty1) * Number(val));
                ttlAmount1 = ttlAmount1 + (Number(qty1) * Number(val) * Number(rate1));
                if (isInt(Number(qty1)) === false)
                {
                    errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Denomination Qty(Pieces) for Row No. ' + i + ' cannot be a Decimal Number!</span></p>';
                    $("#cashBreakdownRow_" + rndmNum1 + " .cbQty").addClass('rho-error');
                    $("#cashBreakdownRow_" + rndmNum1 + " .cbTTlAmnt").addClass('rho-error');
                } else {
                    $("#cashBreakdownRow_" + rndmNum1 + " .cbQty").removeClass('rho-error');
                    $("#cashBreakdownRow_" + rndmNum1 + " .cbTTlAmnt").removeClass('rho-error');
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
    $('#cashBreakdownLgnd').text(crncyNm + ' ' + addCommas(ttlAmount.toFixed(2)));
    $('#mcfCptrdCbValsTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#cashDenominationsTtlRaw').val(ttlAmount.toFixed(2));
    $('#cashDenominationsTtlRaw1').val(ttlAmount1.toFixed(2));
    $('#cashDenominationsTtlFmtd').val(addCommas(ttlAmount.toFixed(2)));
    var obj;
    var acctTrnsId = typeof $("#acctTrnsId").val() === 'undefined' ? -1 : $("#acctTrnsId").val();
    var bnkPymtDfltVltID = typeof $("#bnkPymtDfltVltID").val() === 'undefined' ? -1 : $("#bnkPymtDfltVltID").val();
    var bnkPymtDfltCageID = typeof $("#bnkPymtDfltCageID").val() === 'undefined' ? -1 : $("#bnkPymtDfltCageID").val();
    var bnkPymtDfltItemState = typeof $("#bnkPymtDfltItemState").val() === 'undefined' ? '' : $("#bnkPymtDfltItemState").val();
    var cashBrkdwns = "";
    var crncyNm = $('#acctCrncy').val();
    $('#cashBreakdownTblEDT').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#cashBreakdownRow_' + rndmNum).val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    cashBrkdwns = cashBrkdwns + $('#cashBreakdownRow' + rndmNum + '_denomID').text().replace(/(~)+/g, "{-;-;}").replace(/(\|)+/g, "{:;:;}") + "~"
                            + $('#cashBreakdownRow' + rndmNum + '_denomQty').val().replace(/(~)+/g, "{-;-;}").replace(/(\|)+/g, "{:;:;}") + "~"
                            + $('#cashBreakdownRow' + rndmNum + '_value').text().replace(/(~)+/g, "{-;-;}").replace(/(\|)+/g, "{:;:;}") + "~"
                            + $('#cashBreakdownRow' + rndmNum + '_cashAnalysisID').text().replace(/(~)+/g, "{-;-;}").replace(/(\|)+/g, "{:;:;}") + "~"
                            + $('#cashBreakdownRow' + rndmNum + '_ExchngRate').val().replace(/(~)+/g, "{-;-;}").replace(/(\|)+/g, "{:;:;}") + "|";
                }
            }
        }
    });
    $('#trnsAmount').val($('#cashDenominationsTtlFmtd').val());
    $('#trnsAmntRaw').val($('#cashDenominationsTtlRaw').val());
    var accntCurCashVal = Number($('#cashDenominationsTtlRaw1').val().replace(/,/g, ""));
    var accntCurChqVal = 0;
    $('#trnsAmntRaw1').val(accntCurCashVal);
    $('#tllrTrnsAmntTtlFld').html(crncyNm + ' ' + addCommas((accntCurCashVal + accntCurChqVal).toFixed(2)));
    $('#ttlDocAmntVal').val(accntCurCashVal + accntCurChqVal);
    var msgsTitle = 'Cash Breakdown';
    var dialog = bootbox.alert({
        title: 'Save ' + msgsTitle,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msgsTitle + '...Please Wait...</p>',
        callback: function () {
            /*$('#' + elementID).modal('hide');*/
        }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            var data1 = new FormData();
            data1.append('grp', 17);
            data1.append('typ', 1);
            data1.append('pg', 14);
            data1.append('q', 'UPDATE_CORE_BNK');
            data1.append('subPgNo', subPgNo);
            data1.append('vtyp', 2);
            data1.append('cashBrkdwns', cashBrkdwns);
            data1.append('PKeyID', acctTrnsId);
            data1.append('bnkPymtDfltVltID', bnkPymtDfltVltID);
            data1.append('bnkPymtDfltCageID', bnkPymtDfltCageID);
            data1.append('bnkPymtDfltItemState', bnkPymtDfltItemState);
            $.ajax({
                url: "index.php",
                method: 'POST',
                data: data1,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    setTimeout(function () {
                        var msg = "";
                        var rowValID = -1;
                        if (/^[\],:{}\s]*$/.test(result.replace(/\\["\\\/bfnrtu]/g, '@').
                                replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                                replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                            obj = $.parseJSON(result);
                            rowValID = obj.rowID;
                            msg = "Json Data";
                        } else {
                            msg = result;
                        }
                        $body.removeClass("mdlloadingDiag");
                        dialog.find('.bootbox-body').html(msg);
                        setTimeout(function () {
                            if (msg.indexOf('Success') >= 0) {
                                dialog.modal('hide');
                                $('#' + elementID).modal('hide');
                            }
                        }, 100);
                    }, 50);
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

function getAcctDetailsKD(e, pgNo, subPgNo, vtyp, vtypActn) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAcctDetails(pgNo, subPgNo, vtyp, vtypActn);
    }
}
function getAcctDetails(pgNo, subPgNo, vtyp, vtypActn)
{
    var acctNoFind = $('#acctNoFind').val();
    if (acctNoFind.trim() == "") {
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "Enter Account Number!",
            callback: function () { /* your callback code */
            }
        });
        return;
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
            var msg = "";
            var data = xmlhttp.responseText;
            if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                    replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                    replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                json = $.parseJSON(data);
                $('#acctID').val(json.accountDetails.accountID);
                $('#acctNo').val(json.accountDetails.accountNo);
                $('#acctStatus').val(json.accountDetails.status);
                $('#acctCrncy').val(json.accountDetails.currNm);
                $('#acctType').val(json.accountDetails.acctType);
                $('#acctCustomer').val(json.accountDetails.custId);
                $('#prsnTypeEntity').val(json.accountDetails.prsnTypeEntity);
                $('#acctBranch').val(json.accountDetails.branchId);
                $('#acctTitle').val(json.accountDetails.acctTitle);
                $('#mandate').val(json.accountDetails.mandate);
                if (pgNo == 15 && subPgNo == "4.3") {

                    $('#clrdBal').css({
                        'color': 'red'
                    });
                    $('#clrdBal').val(addCommas(Number((json.accountBalance.clrBal + ',').replace(/[^-?0-9\.]/g, '')).toFixed(2)));
                    $('#unclrdBal').css({
                        'color': 'red'
                    });
                    $('#unclrdBal').val(addCommas(Number((json.accountBalance.unclrBal + ',').replace(/[^-?0-9\.]/g, '')).toFixed(2)));
                } else {
                    if (Number((json.accountBalance.clrBal + ',').replace(/[^-?0-9\.]/g, '')) > 0) {
                        $('#clrdBal').css({
                            'color': 'green'
                        });
                    } else {
                        $('#clrdBal').css({
                            'color': 'red'
                        });
                    }
                    $('#clrdBal').val(addCommas(Number((json.accountBalance.clrBal + ',').replace(/[^-?0-9\.]/g, '')).toFixed(2)));
                    if (Number((json.accountBalance.unclrBal + ',').replace(/[^-?0-9\.]/g, '')) > 0) {
                        $('#unclrdBal').css({
                            'color': 'green'
                        });
                    } else {
                        $('#unclrdBal').css({
                            'color': 'red'
                        });
                    }
                    $('#unclrdBal').val(addCommas(Number((json.accountBalance.unclrBal + ',').replace(/[^-?0-9\.]/g, '')).toFixed(2)));
                }
                $('#wtdrwlLimitNo').val(json.accountWdrwlLimit.limitNo);
                $('#wtdrwlLimitAmt').val(json.accountWdrwlLimit.limitAmount);
                var signCount = json.signatories.length;
                var acctSignatories = '';
                var i = 0;
                var cntr = 0;
                for (i = 0; i < signCount; i++) {
                    cntr++;
                    acctSignatories = acctSignatories + '<tr id="acctSignatoriesTblAddRow_' + cntr + '">' +
                            '<td class="lovtd">' + cntr + '</td>' +
                            '<td  class="lovtd" id="acctSignatoriesTblAddRow' + cntr + '_name">' + json.signatories[i].name + '</td>' +
                            '<td class="lovtd" style="text-align: center !important;"><input type="checkbox" class="form-check-input"></td>' +
                            '<td class="lovtd">' +
                            '<button type="button" class="btn btn-info btn-sm" onclick="viewSignatoryForm(\'myLovModal\', \'myLovModalBody\', \'myLovModalTitle\', \'acctSignatoryForm\', \'\', \'Signatory\', 13, 2.1, 5, \'VIEW\',' + json.signatories[i].id + ');" style="padding:2px !important;">View Signatory</button>' +
                            '</td>' +
                            '<td class="lovtd">' + json.signatories[i].bioData + '</td>' +
                            '<td  class="lovtd" style="display:none;" id="acctSignatoriesTblAddRow' + cntr + '_ID">' + json.signatories[i].id + '</td>' +
                            '<td style="display:none;" id="acctSignatoriesTblAddRow' + cntr + '_MndtrySign">' + json.signatories[i].toSignMndtry + '</td>' +
                            '</tr>';
                }
                $('#acctSignatoriesTblTbody').children('tr').remove();
                $('#acctSignatoriesTblAdd').append(acctSignatories);
                var acctHistoryCount = json.acctTrnsHistory.length;
                var acctHistory = '';
                var j = 0;
                var cntr1 = 0;
                for (j = 0; j < acctHistoryCount; j++) {
                    cntr1++;
                    acctHistory = acctHistory + '<tr id="acctHistoryTblAddAddRow' + cntr1 + '">' +
                            '<td class="lovtd">' +
                            '<button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction Details" onclick="getOneVmsTrnsForm(' + json.acctTrnsHistory[j].acctTrnsId + ', \'' + json.acctTrnsHistory[j].trnsType + '\', 30, \'ShowDialog\',' + json.acctTrnsHistory[j].vwType + ');" style="padding:2px !important;">' +
                            '<img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">' +
                            '</button>' +
                            '</td>' +
                            '<td class="lovtd">' + json.acctTrnsHistory[j].trnsDate + '</td>' +
                            '<td class="lovtd">' + json.acctTrnsHistory[j].trnsDesc + '</td>' +
                            '<td class="lovtd">' + json.acctTrnsHistory[j].trnsNo + '</td>' +
                            '<td class="lovtd" style="text-align:right;font-weight:bold;">' + json.acctTrnsHistory[j].amount + '</td>' +
                            '<td class="lovtd" style="text-align:right;font-weight:bold;color:blue;">' + json.acctTrnsHistory[j].netClrdBal + '</td>' +
                            '<td class="lovtd" style="text-align:right;font-weight:bold;color:blue;">' + json.acctTrnsHistory[j].netUnclrdBal + '</td>' +
                            '<td class="lovtd">' + json.acctTrnsHistory[j].status + '</td>' +
                            '<td class="lovtd">' + json.acctTrnsHistory[j].authorizer + '</td>' +
                            '<td style="display:none;">' + json.acctTrnsHistory[j].acctTrnsId + '</td>' +
                            '</tr>';
                }

                $('#acctHistoryTblTbody').children('tr').remove();
                $('#acctHistoryTblAdd').append(acctHistory);
            } else {
                msg = data;
                if (msg == "INVALID ACCOUNT NUMBER") {
                    $('#acctID').val('');
                    $('#acctNo').val('');
                    $('#acctStatus').val('');
                    $('#acctCrncy').val('');
                    $('#acctType').val('');
                    $('#acctCustomer').val('');
                    $('#prsnTypeEntity').val('');
                    $('#acctBranch').val('');
                    $('#acctTitle').val('');
                    $('#mandate').val('');
                    $('#clrdBal').val('');
                    $('#unclrdBal').val('');
                    $('#wtdrwlLimitNo').val('');
                    $('#wtdrwlLimitAmt').val('');
                    $('#acctSignatoriesTblTbody').children('tr').remove();
                    bootbox.alert({
                        size: "small",
                        title: "Rhomicom Message",
                        message: msg,
                        callback: function () { /* your callback code */
                        }
                    });
                } else {
                    alert("Hello " + data);
                }
            }
            $body.removeClass("mdlloadingDiag");
            trnsPrsnTypeChng();
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=17&typ=1&pg=" + pgNo + "&subPgNo=" + subPgNo + "&vtyp=" + vtyp + "&vtypActn=" + vtypActn + "&PKeyID=" + acctNoFind);
}

function mcfTrnsDocTypeChng()
{
    var docType = typeof $("#docType").val() === 'undefined' ? '' : $("#docType").val();
    $('#docNum').val('');
    if (docType == "Paperless") {
        $('#docNum').attr('readonly', true);
        $('#docNum').removeClass('rqrdFld');
    } else {
        $('#docNum').removeAttr("readonly");
        $('#docNum').addClass('rqrdFld');
    }
}

function trnsPrsnTypeChng()
{
    var isSelf = typeof $("input[name='trnsPersonType']:checked").val() === 'undefined' ? 'Others' : $("input[name='trnsPersonType']:checked").val();
    var miscTrnsType = typeof $("#mcfTlrTrnsType").val() === 'undefined' ? '' : $("#mcfTlrTrnsType").val();
    var miscTrnsSubType = typeof $("#miscTrnsSubType").val() === 'undefined' ? '' : $("#miscTrnsSubType").val();
    if (isSelf === "Self") {
        var curDesc = $('#trnsDesc').val();
        if (curDesc.trim() === '' && miscTrnsType.trim() === 'DEPOSIT') {
            $('#trnsDesc').val('Deposit by Self');
        } else if (curDesc.trim() === '') {
            $('#trnsDesc').val('Withdrawal by Self');
        }
        $('#trnsPersonName').attr('readonly', true);
        $('#trnsPersonTelNo').attr('readonly', true);
        $('#trnsPersonAddress').attr('readonly', true);
        $('#trnsPersonIDType').attr('readonly', true);
        $('#trnsPersonIDNumber').attr('readonly', true);
        $('#trnsPersonName').removeClass('rqrdFld');
        $('#trnsPersonTelNo').removeClass('rqrdFld');
        /*$('#trnsPersonAddress').removeClass('rqrdFld');*/
        $('#trnsPersonIDType').removeClass('rqrdFld');
        $('#trnsPersonIDNumber').removeClass('rqrdFld');
        $('#trnsPersonName').val('');
        $('#trnsPersonTelNo').val('');
        $('#trnsPersonAddress').val('');
        $('#trnsPersonIDType').val('');
        $('#trnsPersonIDNumber').val('');
    } else {
        var curDesc = $('#trnsDesc').val();
        if ((curDesc.trim() === 'Deposit by Self'
                || curDesc.trim() === 'Withdrawal by Self') && miscTrnsSubType !== 'MISC_TRANS') {
            $('#trnsDesc').val('');
        }
        $("#trnsPersonName").removeAttr("readonly");
        $("#trnsPersonTelNo").removeAttr("readonly");
        $("#trnsPersonAddress").removeAttr("readonly");
        $("#trnsPersonIDType").removeAttr("readonly");
        $("#trnsPersonIDNumber").removeAttr("readonly");
        $('#trnsPersonName').addClass('rqrdFld');
        $('#trnsPersonTelNo').addClass('rqrdFld');
        /*$('#trnsPersonAddress').addClass('rqrdFld');*/
        $('#trnsPersonIDType').addClass('rqrdFld');
        $('#trnsPersonIDNumber').addClass('rqrdFld');
    }
}

function wthdrwMCFTrnsRqst(trnsType, vwtype, subPgNo)
{
    var pKeyID = typeof $("#acctTrnsId").val() === 'undefined' ? -1 : $("#acctTrnsId").val();
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
                            getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', trnsType, 14, subPgNo, 0, 'ADD', pKeyID);
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 1040,
                                    actyp: 1040,
                                    q: 'DELETE_CORE_BNK',
                                    mcfTrnsHdrID: pKeyID
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

function authrzeMCFTrnsRqst(trnsType, vwtype, subPgNo)
{
    var pKeyID = typeof $("#acctTrnsId").val() === 'undefined' ? -1 : $("#acctTrnsId").val();
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
                            getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', trnsType, 14, subPgNo, 0, 'ADD', pKeyID);
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 1040,
                                    q: 'FINALIZE_CORE_BNK',
                                    mcfTrnsHdrID: pKeyID
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

function finalzeMCFTrnsRqst(trnsType, vwtype, subPgNo)
{
    disableBtnFunc("payRcveAcntTrnsBtn");
    var pKeyID = typeof $("#acctTrnsId").val() === 'undefined' ? -1 : $("#acctTrnsId").val();
    var dialog = bootbox.confirm({
        title: 'Finalize Request?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE</span> this Request?<br/>Action cannot be Undone!</p>',
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
                    title: 'Finalize Request?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Finalizing Request...Please Wait...</p>',
                    callback: function () {
                        if (pKeyID > 0)
                        {
                            getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', trnsType, 14, subPgNo, 0, 'ADD', pKeyID);
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 1041,
                                    q: 'FINALIZE_CORE_BNK',
                                    mcfTrnsHdrID: pKeyID
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
                            dialog1.find('.bootbox-body').html('<span style="color:red;">Nothing to Finalize!</span>');
                        }, 500);
                    }
                });
            }
        }
    });
}

function delMCFTrnsHdr(rowIDAttrb)
{
    var msg = 'Banking Transaction';
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var trnsNum = "";
    if (typeof $('#allMcfTrnsHdrsRow' + rndmNum + '_HdrID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allMcfTrnsHdrsRow' + rndmNum + '_HdrID').val();
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
                        $("body").css("padding", "0px 0px 0px 0px");
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE_CORE_BNK',
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

function getOneMCFGLIntrfcForm(pKeyID, pRowIDAttrb)
{
    var slctdIntrfcIDs = "";
    var slctdCnt = 0;
    var rowIDAttrb = "";
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
        var lnkArgs = 'grp=17&typ=1&pg=8&vtyp=2&subPgNo=1.1&sbmtdIntrfcID=' + pKeyID;
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
                getAllGLIntrfcs('', '#allmodules', 'grp=17&typ=1&pg=8&vtyp=0&subPgNo=1.1');
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

function afterMCFIntrfcItemSlctn()
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
            formData.append('grp', 17);
            formData.append('typ', 1);
            formData.append('pg', 8);
            formData.append('subPgNo', 1.1);
            formData.append('q', 'VIEW');
            formData.append('vtyp', 3);
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

function saveMCFGLIntrfcForm()
{
    afterMCFIntrfcItemSlctn();
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
                    grp: 17,
                    typ: 1,
                    pg: 8,
                    q: 'UPDATECONF',
                    actyp: 8,
                    subPgNo: 1.1,
                    glIntrfcTrnsDate: glIntrfcTrnsDate,
                    glIntrfcTrnsID: glIntrfcTrnsID,
                    glIntrfcTrnsDesc: glIntrfcTrnsDesc,
                    intrfcAccntID: intrfcAccntID,
                    incrsDcrs: incrsDcrs,
                    enteredCrncyNm: enteredCrncyNm,
                    enteredAmount: enteredAmount.replace(/[^-?0-9\.]/g, ''),
                    funcCrncyRate: funcCrncyRate.replace(/[^-?0-9\.]/g, ''),
                    accntCrncyRate: accntCrncyRate.replace(/[^-?0-9\.]/g, ''),
                    funcCrncyAmount: funcCrncyAmount.replace(/[^-?0-9\.]/g, ''),
                    accntCrncyAmount: accntCrncyAmount.replace(/[^-?0-9\.]/g, '')
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

function delSlctdMCFIntrfcLines()
{
    var slctdIntrfcIDs = "";
    var slctdCnt = 0;
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
                                getAllGLIntrfcs('', '#allmodules', 'grp=17&typ=1&pg=8&vtyp=0&subPgNo=1.1');
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETECONF',
                                    actyp: 9,
                                    subPgNo: 1.1,
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

function getOneCrncyForm(pKeyID, vwtype)
{
    var lnkArgs = 'grp=17&typ=1&pg=8&subPgNo=1.2&vtyp=' + vwtype + '&sbmtdCrncyID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'allCrncysDetailInfo', 'PasteDirect', '', '', '', function () {
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            if (!$.fn.DataTable.isDataTable('#crncyDenomsTable')) {
                var table2 = $('#crncyDenomsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#crncyDenomsTable').wrap('<div class="dataTables_scroll"/>');
            }
        });
    });
}

function getAllCrncys(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allCrncysSrchFor").val() === 'undefined' ? '%' : $("#allCrncysSrchFor").val();
    var srchIn = typeof $("#allCrncysSrchIn").val() === 'undefined' ? 'Both' : $("#allCrncysSrchIn").val();
    var pageNo = typeof $("#allCrncysPageNo").val() === 'undefined' ? 1 : $("#allCrncysPageNo").val();
    var limitSze = typeof $("#allCrncysDsplySze").val() === 'undefined' ? 10 : $("#allCrncysDsplySze").val();
    var sortBy = typeof $("#allCrncysSortBy").val() === 'undefined' ? '' : $("#allCrncysSortBy").val();
    /*var qStrtDte = typeof $("#allCageItemTrnsStrtDate").val() === 'undefined' ? '' : $("#allCageItemTrnsStrtDate").val();
     var qEndDte = typeof $("#allCageItemTrnsEndDate").val() === 'undefined' ? '' : $("#allCageItemTrnsEndDate").val();*/
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
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy /*+
             '&isFrmBnkng=1' + "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte*/;
    doAjaxWthCallBck(linkArgs, 'allmodules', 'PasteDirect', '', '', '', function () {
        if (!$.fn.DataTable.isDataTable('#allCrncysTable')) {
            var table1 = $('#allCrncysTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allCrncysTable').wrap('<div class="dataTables_scroll"/>');
            $('#allCrncysForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            var table2 = $('#crncyDenomsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#crncyDenomsTable').wrap('<div class="dataTables_scroll"/>');
            $('#allCrncysTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pKeyID = typeof $('#allCrncysRow' + rndmNum + '_CrncyID').val() === 'undefined' ? '%' : $('#allCrncysRow' + rndmNum + '_CrncyID').val();
                getOneCrncyForm(pKeyID, 1);
            });
            $('#allCrncysTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        }
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function enterKeyFuncAllCrncys(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllCrncys(actionText, slctr, linkArgs);
    }
}

function getOneCrncyStpForm(pKeyID)
{
    var lnkArgs = 'grp=17&typ=1&pg=8&vtyp=2&subPgNo=1.2&sbmtdCrncyID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', 'ShowDialog', 'Currency Details (ID:' + pKeyID + ')', 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        if (!$.fn.DataTable.isDataTable('#crncyDenomsStpTable')) {
            var table1 = $('#crncyDenomsStpTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#crncyDenomsStpTable').wrap('<div class="dataTables_scroll"/>');
        }
    });
    $('#mcfCrncyStpForm').submit(function (e) {
        e.preventDefault();
        return false;
    });
    $('#allOtherInputData99').val(0);
}

function saveCrncyStpForm(actionText, slctr, linkArgs)
{
    var crncyISOCode = typeof $("#crncyISOCode").val() === 'undefined' ? '' : $("#crncyISOCode").val();
    var crncyID = typeof $("#crncyID").val() === 'undefined' ? '-1' : $("#crncyID").val();
    var crncyDesc = typeof $("#crncyDesc").val() === 'undefined' ? '' : $("#crncyDesc").val();
    var isCrncyEnbld = typeof $("input[name='isCrncyEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var mppdCrncyNm = typeof $("#mppdCrncyNm").val() === 'undefined' ? '' : $("#mppdCrncyNm").val();
    var errMsg = "";
    if (crncyISOCode.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Currency ISO Code cannot be empty!</span></p>';
    }
    if (crncyDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Currency Description cannot be empty!</span></p>';
    }
    if (mppdCrncyNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Currency Description cannot be empty!</span></p>';
    }

    var slctdDenoms = "";
    var isVld = true;
    $('#crncyDenomsStpTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_LnkdItmID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnkdItmID = typeof $('#' + rowPrfxNm + rndmNum + '_LnkdItmID').val() === 'undefined' ? 0 : $('#' + rowPrfxNm + rndmNum + '_LnkdItmID').val();
                    var lnDenomVal = typeof $('#' + rowPrfxNm + rndmNum + '_DenomVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_DenomVal').val();
                    if (Number(lnDenomVal.replace(/[^-?0-9\.]/g, '')) == 0 && lnkdItmID <= 0)
                    {
                        /*Do Nothing if (lnkdItmID > 0)*/
                    } else {
                        if (Number(lnDenomVal.replace(/[^-?0-9\.]/g, '')) <= 0) {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_DenomVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_DenomVal').removeClass('rho-error');
                            var lnDenomNm = typeof $('#' + rowPrfxNm + rndmNum + '_DenomNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DenomNm').val();
                            if (lnDenomNm.trim() === "")
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_DenomNm').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_DenomNm').removeClass('rho-error');
                            }
                            var lnDenomType = typeof $('#' + rowPrfxNm + rndmNum + '_DenomType').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DenomType').val();
                            if (lnDenomType.trim() === "")
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_DenomType').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_DenomType').removeClass('rho-error');
                            }
                            if (isVld === true)
                            {
                                slctdDenoms = slctdDenoms + $('#' + rowPrfxNm + rndmNum + '_DenomID').val() + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_DenomNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_DenomType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_DenomVal').val().replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + (typeof $("input[name='" + rowPrfxNm + rndmNum + "_IsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_LnkdItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
    var dialog = bootbox.alert({
        title: 'Save Currency',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Currency...Please Wait...</p>',
        callback: function () {
            $('#myFormsModalNrml').modal('hide');
            getAllCrncys(actionText, slctr, linkArgs);
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
                    grp: 17,
                    typ: 1,
                    pg: 8,
                    q: 'UPDATECONF',
                    actyp: 2,
                    subPgNo: 1.2,
                    crncyISOCode: crncyISOCode,
                    crncyID: crncyID,
                    crncyDesc: crncyDesc,
                    isCrncyEnbld: isCrncyEnbld,
                    mppdCrncyNm: mppdCrncyNm,
                    slctdDenoms: slctdDenoms
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

function delCrncyStp(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var crncyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_CrncyID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_CrncyID').val();
        crncyNm = $('#' + rowPrfxNm + rndmNum + '_CrncyNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Currency?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Currency?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Currency?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Currency...Please Wait...</p>',
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETECONF',
                                    actyp: 2,
                                    subPgNo: 1.2,
                                    crncyID: pKeyID,
                                    crncyNm: crncyNm
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

function delDenomStp(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var denomNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_DenomID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_DenomID').val();
        denomNm = $('#' + rowPrfxNm + rndmNum + '_DenomNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Denomination?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Denomination?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Denomination?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Denomination...Please Wait...</p>',
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 8,
                                    subPgNo: 1.2,
                                    q: 'DELETECONF',
                                    actyp: 3,
                                    denomID: pKeyID,
                                    denomNm: denomNm
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

function getAllVmsCgs(actionText, slctr, linkArgs)
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

function enterKeyFuncAllVmsCgs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllVmsCgs(actionText, slctr, linkArgs);
    }
}

function getOneVmsCgForm(pKeyID)
{
    var lnkArgs = 'grp=17&typ=1&pg=8&vtyp=2&subPgNo=1.3&sbmtdCageID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', 'Teller\'s Till (ID:' + pKeyID + ')', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        $('#mcfTillStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#allOtherInputData99').val(0);
        $('#myFormsModalx').off('hidden.bs.modal');
        $('#myFormsModalx').one('hidden.bs.modal', function (e) {
            $('#myFormsModalxTitle').html('');
            $('#myFormsModalxBody').html('');
            getAllVmsCgs('', '#allmodules', 'grp=17&typ=1&pg=8&vtyp=0&subPgNo=1.3');
            $(e.currentTarget).unbind();
        });
    });
}

function saveVmsCgForm()
{
    var cageShelfNm = typeof $("#cageShelfNm").val() === 'undefined' ? '' : $("#cageShelfNm").val();
    var cageLineID = typeof $("#cageLineID").val() === 'undefined' ? '-1' : $("#cageLineID").val();
    var cageShelfID = typeof $("#cageShelfID").val() === 'undefined' ? '-1' : $("#cageShelfID").val();
    var cageDesc = typeof $("#cageDesc").val() === 'undefined' ? '' : $("#cageDesc").val();
    var cageVltID = typeof $("#cageVltID").val() === 'undefined' ? '-1' : $("#cageVltID").val();
    var cageOwnersCstmrID = typeof $("#cageOwnersCstmrID").val() === 'undefined' ? '-1' : $("#cageOwnersCstmrID").val();
    var lnkdGLAccountID = typeof $("#lnkdGLAccountID").val() === 'undefined' ? '-1' : $("#lnkdGLAccountID").val();
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
                'font-weight:bold;color:red;">Cage/Till name cannot be empty!</span></p>';
    }
    if (cageDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Cage/Till Description cannot be empty!</span></p>';
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
    if (Number(cageMngrsPrsnID.replace(/[^-?0-9\.]/g, '')) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Cage Manager cannot be empty!</span></p>';
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
                    grp: 17,
                    typ: 1,
                    pg: 8,
                    q: 'UPDATECONF',
                    actyp: 7,
                    subPgNo: 1.3,
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

function delVmsCg(rowIDAttrb)
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETECONF',
                                    actyp: 8,
                                    subPgNo: 1.3,
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

function grpTypMcfChange()
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

function getAllGLIntrfcs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allGLIntrfcsSrchFor").val() === 'undefined' ? '%' : $("#allGLIntrfcsSrchFor").val();
    var srchIn = typeof $("#allGLIntrfcsSrchIn").val() === 'undefined' ? 'Both' : $("#allGLIntrfcsSrchIn").val();
    var pageNo = typeof $("#allGLIntrfcsPageNo").val() === 'undefined' ? 1 : $("#allGLIntrfcsPageNo").val();
    var limitSze = typeof $("#allGLIntrfcsDsplySze").val() === 'undefined' ? 10 : $("#allGLIntrfcsDsplySze").val();
    var sortBy = typeof $("#allGLIntrfcsSortBy").val() === 'undefined' ? '' : $("#allGLIntrfcsSortBy").val();
    var qStrtDte = typeof $("#allGLIntrfcsStrtDate").val() === 'undefined' ? '' : $("#allGLIntrfcsStrtDate").val();
    var qEndDte = typeof $("#allGLIntrfcsEndDate").val() === 'undefined' ? '' : $("#allGLIntrfcsEndDate").val();
    var qNotSentToGl = $('#allGLIntrfcsSntToGl:checked').length > 0;
    var qUnbalncdOnly = $('#allGLIntrfcsUnbalncd:checked').length > 0;
    var qUsrGnrtd = $('#allGLIntrfcsUsrTrns:checked').length > 0;
    var qLowVal = typeof $("#allGLIntrfcsLowVal").val() === 'undefined' ? 0 : $("#allGLIntrfcsLowVal").val();
    var qHighVal = typeof $("#allGLIntrfcsHighVal").val() === 'undefined' ? 0 : $("#allGLIntrfcsHighVal").val();
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

function enterKeyFuncAllGLIntrfcs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllGLIntrfcs(actionText, slctr, linkArgs);
    }
}

function getAllExchRates(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allExchRatesSrchFor").val() === 'undefined' ? '%' : $("#allExchRatesSrchFor").val();
    var srchIn = typeof $("#allExchRatesSrchIn").val() === 'undefined' ? 'Both' : $("#allExchRatesSrchIn").val();
    var pageNo = typeof $("#allExchRatesPageNo").val() === 'undefined' ? 1 : $("#allExchRatesPageNo").val();
    var limitSze = typeof $("#allExchRatesDsplySze").val() === 'undefined' ? 10 : $("#allExchRatesDsplySze").val();
    var sortBy = typeof $("#allExchRatesSortBy").val() === 'undefined' ? '' : $("#allExchRatesSortBy").val();
    var qStrtDte = typeof $("#allExchRatesStrtDate").val() === 'undefined' ? '' : $("#allExchRatesStrtDate").val();
    var qEndDte = typeof $("#allExchRatesEndDate").val() === 'undefined' ? '' : $("#allExchRatesEndDate").val();
    var qNwStrtDte = typeof $("#allExchRatesNewDate").val() === 'undefined' ? '' : $("#allExchRatesNewDate").val();
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
            "&qNwStrtDte=" + qNwStrtDte;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllExchRates(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllExchRates(actionText, slctr, linkArgs);
    }
}

function mcfCnfExRateKeyPress(event, rowIDAttrb)
{
    if (event.which === 13) {
        var nextItem;
        var nextItemVal = 0;
        var rowPrfxNm = rowIDAttrb.split("_")[0];
        var sbmtdTblRowID = 'allExchRatesTable';
        var curItemVal = getRowIndx(rowIDAttrb, sbmtdTblRowID);
        if (curItemVal === getTtlRows(sbmtdTblRowID)) {
            nextItem = $('#' + sbmtdTblRowID + ' .mcfExRate').eq(0);
        } else {
            nextItemVal = Number(curItemVal);
            nextItem = $('#' + sbmtdTblRowID + ' .mcfExRate').eq(nextItemVal);
        }
        nextItem.focus();
    }
}

function saveExchRates(actionText, slctr, linkArgs, actyp)
{
    var errMsg = "";
    var newRateDate = typeof $("#allExchRatesNewDate").val() === 'undefined' ? '' : $("#allExchRatesNewDate").val();
    var slctdRateIDs = "";
    var isVld = true;
    $('#allExchRatesTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_RateID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnRateID = typeof $('#' + rowPrfxNm + rndmNum + '_RateID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_RateID').val();
                    var lnRateVal = typeof $('#' + rowPrfxNm + rndmNum + '_ExRate').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_ExRate').val();
                    var lnRateVal1 = typeof $('#' + rowPrfxNm + rndmNum + '_ExRate1').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_ExRate1').val();
                    if (Number(lnRateID.replace(/[^-?0-9\.]/g, '')) <= 0) {
                        /*Do Nothing*/
                    } else {
                        if (Number(lnRateVal.replace(/[^-?0-9\.]/g, '')) <= 0) {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_ExRate').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_ExRate').removeClass('rho-error');
                            if (isVld === true)
                            {
                                slctdRateIDs = slctdRateIDs + lnRateID + "~"
                                        + lnRateVal.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + lnRateVal1.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
    var dialog = bootbox.alert({
        title: 'Save Exchange Rates',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Exchange Rates...Please Wait...</p>',
        callback: function () {
            $('#myFormsModalNm').modal('hide');
            getAllExchRates(actionText, slctr, linkArgs);
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
                    grp: 17,
                    typ: 1,
                    pg: 8,
                    q: 'UPDATECONF',
                    actyp: actyp,
                    subPgNo: 1.4,
                    newRateDate: newRateDate,
                    slctdRateIDs: slctdRateIDs
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
                }});
        });
    });
}

function delExchRate(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var rateIDDesc = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_RateID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_RateID').val();
        rateIDDesc = $('#' + rowPrfxNm + rndmNum + '_FromCur').val() + ' (' + $('#' + rowPrfxNm + rndmNum + '_RateDate').val() + ')';
    }
    var dialog = bootbox.confirm({
        title: 'Delete Exchange Rate?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Exchange Rate?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Exchange Rate?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Exchange Rate...Please Wait...</p>',
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETECONF',
                                    actyp: 6,
                                    subPgNo: 1.4,
                                    rateID: pKeyID,
                                    rateIDDesc: rateIDDesc
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

function delSlctdExchRates()
{
    var slctdRateIDs = "";
    var slctdCnt = 0;
    $('#allExchRatesTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_RateID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnRateID = typeof $('#' + rowPrfxNm + rndmNum + '_RateID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_RateID').val();
                    var isTcked = (typeof $("input[name='" + rowPrfxNm + rndmNum + "_CheckBox']:checked").val() === 'undefined' ? 'NO' : 'YES');
                    if (Number(lnRateID.replace(/[^-?0-9\.]/g, '')) > 0 && isTcked === "YES") {
                        slctdCnt = slctdCnt + 1;
                    }
                    $("#" + rowPrfxNm + rndmNum).remove();
                }
            }
        }
    });
    if (slctdCnt > 0) {
        var dialog = bootbox.confirm({
            title: 'Delete Selected Exchange Rates?',
            size: 'small',
            message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> the ' + slctdCnt + ' selected Exchange Rate(s)?<br/>Action cannot be Undone!</p>',
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
                $('#allExchRatesTable').find('tr').each(function (i, el) {
                    if (i > 0)
                    {
                        if (typeof $(el).attr('id') === 'undefined')
                        {
                            /*Do Nothing*/
                        } else {
                            var rndmNum = $(el).attr('id').split("_")[1];
                            var rowPrfxNm = $(el).attr('id').split("_")[0];
                            if (typeof $('#' + rowPrfxNm + rndmNum + '_RateID').val() === 'undefined')
                            {
                                /*Do Nothing*/
                            } else {
                                var lnRateID = typeof $('#' + rowPrfxNm + rndmNum + '_RateID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_RateID').val();
                                var rateIDDesc = $('#' + rowPrfxNm + rndmNum + '_FromCur').val() + ' (' + $('#' + rowPrfxNm + rndmNum + '_RateDate').val() + ')';
                                var isTcked = (typeof $("input[name='" + rowPrfxNm + rndmNum + "_CheckBox']:checked").val() === 'undefined' ? 'NO' : 'YES');
                                if (Number(lnRateID.replace(/[^-?0-9\.]/g, '')) > 0 && isTcked === "YES") {
                                    slctdRateIDs = slctdRateIDs + lnRateID + "~"
                                            + rateIDDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
                        title: 'Delete Selected Exchange Rates?',
                        size: 'small',
                        message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Selected Exchange Rates...Please Wait...</p>',
                        callback: function () {
                            $("body").css("padding-right", "0px");
                            if (result2.indexOf("Success") !== -1) {
                                getAllExchRates('', '#allmodules', 'grp=17&typ=1&pg=8&vtyp=0&subPgNo=1.4');
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETECONF',
                                    actyp: 7,
                                    subPgNo: 1.6,
                                    slctdRateIDs: slctdRateIDs
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
    }
}

function getAllDfltAcnts(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allDfltAcntsSrchFor").val() === 'undefined' ? '%' : $("#allDfltAcntsSrchFor").val();
    var srchIn = typeof $("#allDfltAcntsSrchIn").val() === 'undefined' ? 'Both' : $("#allDfltAcntsSrchIn").val();
    var pageNo = typeof $("#allDfltAcntsPageNo").val() === 'undefined' ? 1 : $("#allDfltAcntsPageNo").val();
    var limitSze = typeof $("#allDfltAcntsDsplySze").val() === 'undefined' ? 10 : $("#allDfltAcntsDsplySze").val();
    var sortBy = typeof $("#allDfltAcntsSortBy").val() === 'undefined' ? '' : $("#allDfltAcntsSortBy").val();
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

function enterKeyFuncAllDfltAcnts(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllDfltAcnts(actionText, slctr, linkArgs);
    }
}

function delDfltAcnts(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var dfltAcntDesc = '';
    var msgsTitle = 'Default Account';
    if (typeof $('#allDfltAcntsRow' + rndmNum + '_PkeyID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allDfltAcntsRow' + rndmNum + '_PkeyID').val();
        dfltAcntDesc = $('#allDfltAcntsRow' + rndmNum + '_TypeDesc').val();
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETECONF',
                                    subPgNo: '1.5',
                                    actyp: 10,
                                    dfltAcntDesc: dfltAcntDesc,
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

function saveDfltAcnts(slctr, linkArgs)
{
    var msgsTitle = 'Default Accounts';
    var slctdDfltAcnts = "";
    var isVld = true;
    var errMsg = "";
    $('#allDfltAcntsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var pKeyID = $('#allDfltAcntsRow' + rndmNum + '_PkeyID').val();
                var systmType = $('#allDfltAcntsRow' + rndmNum + '_SystemType').val();
                if (systmType.trim() === '')
                {
                    isVld = false;
                    errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">System Type for Column No. ' + i + ' cannot be empty!</span></p>';
                    $('#allDfltAcntsRow' + rndmNum + '_SystemType').addClass('rho-error');
                } else {
                    $('#allDfltAcntsRow' + rndmNum + '_SystemType').removeClass('rho-error');
                }
                var subtypeNm = $('#allDfltAcntsRow' + rndmNum + '_TypeDesc').val();
                if (subtypeNm.trim() === '')
                {
                    isVld = false;
                    errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Transaction Sub-Type for Column No. ' + i + ' cannot be empty!</span></p>';
                    $('#allDfltAcntsRow' + rndmNum + '_TypeDesc').addClass('rho-error');
                } else {
                    $('#allDfltAcntsRow' + rndmNum + '_TypeDesc').removeClass('rho-error');
                }
                if (isVld === false)
                {
                    /*Do Nothing*/
                } else {
                    var isEnabled = typeof $("input[name='allDfltAcntsRow" + rndmNum + "_IsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
                    slctdDfltAcnts = slctdDfltAcnts + $('#allDfltAcntsRow' + rndmNum + '_PkeyID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#allDfltAcntsRow' + rndmNum + '_SystemType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#allDfltAcntsRow' + rndmNum + '_TypeDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#allDfltAcntsRow' + rndmNum + '_GLAcntID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#allDfltAcntsRow' + rndmNum + '_CstmrAcntID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
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
                    grp: 17,
                    typ: 1,
                    pg: 8,
                    subPgNo: '1.5',
                    q: 'UPDATECONF',
                    actyp: 9,
                    slctdDfltAcnts: slctdDfltAcnts
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

function getAllEODPrcs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allEODPrcsSrchFor").val() === 'undefined' ? '%' : $("#allEODPrcsSrchFor").val();
    var srchIn = typeof $("#allEODPrcsSrchIn").val() === 'undefined' ? 'Both' : $("#allEODPrcsSrchIn").val();
    var pageNo = typeof $("#allEODPrcsPageNo").val() === 'undefined' ? 1 : $("#allEODPrcsPageNo").val();
    var limitSze = typeof $("#allEODPrcsDsplySze").val() === 'undefined' ? 10 : $("#allEODPrcsDsplySze").val();
    var sortBy = typeof $("#allEODPrcsSortBy").val() === 'undefined' ? '' : $("#allEODPrcsSortBy").val();
    var qStrtDte = typeof $("#allEODPrcsStrtDate").val() === 'undefined' ? '' : $("#allEODPrcsStrtDate").val();
    var qEndDte = typeof $("#allEODPrcsEndDate").val() === 'undefined' ? '' : $("#allEODPrcsEndDate").val();
    var qNwStrtDte = typeof $("#allEODPrcsNewDate").val() === 'undefined' ? '' : $("#allEODPrcsNewDate").val();
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
            "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte + "&qNwStrtDte=" + qNwStrtDte;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllEODPrcs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllEODPrcs(actionText, slctr, linkArgs);
    }
}

function getOneBankDetail(pKeyID, vwtype)
{
    var lnkArgs = 'grp=17&typ=1&pg=8&subPgNo=1.61&vtyp=0&sbmtdBankID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'allBnksDetailInfo', 'PasteDirect', '', '', '', function () {
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            if (!$.fn.DataTable.isDataTable('#allBnkBrnchsTable')) {
                var table2 = $('#allBnkBrnchsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allBnkBrnchsTable').wrap('<div class="dataTables_scroll"/>');
            }
        });
    });
}

function getAllBnks(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allBnksSrchFor").val() === 'undefined' ? '%' : $("#allBnksSrchFor").val();
    var srchIn = typeof $("#allBnksSrchIn").val() === 'undefined' ? 'Both' : $("#allBnksSrchIn").val();
    var pageNo = typeof $("#allBnksPageNo").val() === 'undefined' ? 1 : $("#allBnksPageNo").val();
    var limitSze = typeof $("#allBnksDsplySze").val() === 'undefined' ? 10 : $("#allBnksDsplySze").val();
    var sortBy = typeof $("#allBnksSortBy").val() === 'undefined' ? '' : $("#allBnksSortBy").val();
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

function enterKeyFuncAllBnks(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllBnks(actionText, slctr, linkArgs);
    }
}

function getOneBankStpForm(pKeyID)
{
    var lnkArgs = 'grp=17&typ=1&pg=8&vtyp=2&subPgNo=1.6&sbmtdBankID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNm', 'ShowDialog', 'Bank Details (ID:' + pKeyID + ')', 'myFormsModalNmTitle', 'myFormsModalNmBody', function () {
        if (!$.fn.DataTable.isDataTable('#allBnkBrnchsStpTable')) {
            var table1 = $('#allBnkBrnchsStpTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allBnkBrnchsStpTable').wrap('<div class="dataTables_scroll"/>');
        }
    });
    $('#bnkBrnchsStpForm').submit(function (e) {
        e.preventDefault();
        return false;
    });
    $('#allOtherInputData99').val(0);
}

function saveBankStpForm(actionText, slctr, linkArgs)
{
    var oneBnkDetCode = typeof $("#oneBnkDetCode").val() === 'undefined' ? '' : $("#oneBnkDetCode").val();
    var oneBnkDetID = typeof $("#oneBnkDetID").val() === 'undefined' ? '-1' : $("#oneBnkDetID").val();
    var oneBnkDetName = typeof $("#oneBnkDetName").val() === 'undefined' ? '' : $("#oneBnkDetName").val();
    var oneBnkDetCntct = typeof $("#oneBnkDetCntct").val() === 'undefined' ? '' : $("#oneBnkDetCntct").val();
    var oneBnkDetEmail = typeof $("#oneBnkDetEmail").val() === 'undefined' ? '' : $("#oneBnkDetEmail").val();
    var oneBnkDetISOCntryCode = typeof $("#oneBnkDetISOCntryCode").val() === 'undefined' ? '' : $("#oneBnkDetISOCntryCode").val();
    var oneBnkDetChkDgts = typeof $("#oneBnkDetChkDgts").val() === 'undefined' ? '' : $("#oneBnkDetChkDgts").val();
    var oneBnkDetSwiftCode = typeof $("#oneBnkDetSwiftCode").val() === 'undefined' ? '' : $("#oneBnkDetSwiftCode").val();
    var oneBnkDetFax = typeof $("#oneBnkDetFax").val() === 'undefined' ? '' : $("#oneBnkDetFax").val();
    var oneBnkDetPstl = typeof $("#oneBnkDetPstl").val() === 'undefined' ? '' : $("#oneBnkDetPstl").val();
    var oneBnkDetRes = typeof $("#oneBnkDetRes").val() === 'undefined' ? '' : $("#oneBnkDetRes").val();
    var isBnkEnbld = typeof $("input[name='isBnkEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var errMsg = "";
    if (oneBnkDetCode.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Bank Sort Code cannot be empty!</span></p>';
    }
    if (oneBnkDetName.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Currency Description cannot be empty!</span></p>';
    }
    var slctdBranches = "";
    var isVld = true;
    $('#allBnkBrnchsStpTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_BrnchCode').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnBrnchCode = typeof $('#' + rowPrfxNm + rndmNum + '_BrnchCode').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_BrnchCode').val();
                    var lnBrnchNm = typeof $('#' + rowPrfxNm + rndmNum + '_BranchNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_BranchNm').val();
                    if (lnBrnchNm.trim() === "" || lnBrnchCode.trim() === "")
                    {
                        /*Do Nothing*/
                    } else {
                        if (lnBrnchCode.trim() === "") {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_BrnchCode').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_BrnchCode').removeClass('rho-error');
                            if (lnBrnchNm.trim() === "")
                            {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_BranchNm').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_BranchNm').removeClass('rho-error');
                            }
                            if (isVld === true)
                            {
                                slctdBranches = slctdBranches + $('#' + rowPrfxNm + rndmNum + '_BranchID').val() + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_BrnchCode').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_BranchNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_PstlAdrs').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_ResAdrs').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_CntctNos').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_Swft').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + (typeof $("input[name='" + rowPrfxNm + rndmNum + "_IsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + $('#' + rowPrfxNm + rndmNum + '_BankID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
    var dialog = bootbox.alert({
        title: 'Save Bank Details',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Bank Details...Please Wait...</p>',
        callback: function () {
            $('#myFormsModalNm').modal('hide');
            getAllBnks(actionText, slctr, linkArgs);
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
                    grp: 17,
                    typ: 1,
                    pg: 8,
                    q: 'UPDATECONF',
                    actyp: 3,
                    subPgNo: 1.6,
                    oneBnkDetCode: oneBnkDetCode,
                    oneBnkDetID: oneBnkDetID,
                    oneBnkDetName: oneBnkDetName,
                    oneBnkDetCntct: oneBnkDetCntct,
                    oneBnkDetEmail: oneBnkDetEmail,
                    oneBnkDetISOCntryCode: oneBnkDetISOCntryCode,
                    oneBnkDetChkDgts: oneBnkDetChkDgts,
                    oneBnkDetSwiftCode: oneBnkDetSwiftCode,
                    oneBnkDetFax: oneBnkDetFax,
                    oneBnkDetPstl: oneBnkDetPstl,
                    oneBnkDetRes: oneBnkDetRes,
                    isBnkEnbld: isBnkEnbld,
                    slctdBranches: slctdBranches
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
                }});
        });
    });
}

function delBankStp(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var bankNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_BankID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_BankID').val();
        bankNm = $('#' + rowPrfxNm + rndmNum + '_BankNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Bank?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Bank?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Bank?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Bank...Please Wait...</p>',
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETECONF',
                                    actyp: 4,
                                    subPgNo: 1.6,
                                    bankID: pKeyID,
                                    bankNm: bankNm
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

function getAllBnkBrnchs(actionText, slctr, linkArgs, isStp)
{
    var srchFor = typeof $("#allBnkBrnchsSrchFor").val() === 'undefined' ? '%' : $("#allBnkBrnchsSrchFor").val();
    var srchIn = typeof $("#allBnkBrnchsSrchIn").val() === 'undefined' ? 'Both' : $("#allBnkBrnchsSrchIn").val();
    var pageNo = typeof $("#allBnkBrnchsPageNo").val() === 'undefined' ? 1 : $("#allBnkBrnchsPageNo").val();
    var limitSze = typeof $("#allBnkBrnchsDsplySze").val() === 'undefined' ? 10 : $("#allBnkBrnchsDsplySze").val();
    var sortBy = typeof $("#allBnkBrnchsSortBy").val() === 'undefined' ? '' : $("#allBnkBrnchsSortBy").val();
    if (isStp >= 1)
    {
        srchFor = typeof $("#allBnkBrnchsStpSrchFor").val() === 'undefined' ? '%' : $("#allBnkBrnchsStpSrchFor").val();
        srchIn = typeof $("#allBnkBrnchsStpSrchIn").val() === 'undefined' ? 'Both' : $("#allBnkBrnchsStpSrchIn").val();
        pageNo = typeof $("#allBnkBrnchsStpPageNo").val() === 'undefined' ? 1 : $("#allBnkBrnchsStpPageNo").val();
        limitSze = typeof $("#allBnkBrnchsStpDsplySze").val() === 'undefined' ? 10 : $("#allBnkBrnchsStpDsplySze").val();
        sortBy = typeof $("#allBnkBrnchsStpSortBy").val() === 'undefined' ? '' : $("#allBnkBrnchsStpSortBy").val();
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
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllBnkBrnchs(e, actionText, slctr, linkArgs, isStp)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllBnkBrnchs(actionText, slctr, linkArgs, isStp);
    }
}

function delBankBrnchStp(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var branchNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_BranchID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_BranchID').val();
        branchNm = $('#' + rowPrfxNm + rndmNum + '_BranchNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Branch?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this linked Branch?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Branch?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Branch...Please Wait...</p>',
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 8,
                                    subPgNo: 1.6,
                                    q: 'DELETECONF',
                                    actyp: 5,
                                    branchID: pKeyID,
                                    branchNm: branchNm
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

function getAllUnclrdTrns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allUnclrdTrnsSrchFor").val() === 'undefined' ? '%' : $("#allUnclrdTrnsSrchFor").val();
    var srchIn = typeof $("#allUnclrdTrnsSrchIn").val() === 'undefined' ? 'Both' : $("#allUnclrdTrnsSrchIn").val();
    var pageNo = typeof $("#allUnclrdTrnsPageNo").val() === 'undefined' ? 1 : $("#allUnclrdTrnsPageNo").val();
    var limitSze = typeof $("#allUnclrdTrnsDsplySze").val() === 'undefined' ? 10 : $("#allUnclrdTrnsDsplySze").val();
    var sortBy = typeof $("#allUnclrdTrnsSortBy").val() === 'undefined' ? '' : $("#allUnclrdTrnsSortBy").val();
    var qStrtDte = typeof $("#allUnclrdTrnsStrtDate").val() === 'undefined' ? '' : $("#allUnclrdTrnsStrtDate").val();
    var qEndDte = typeof $("#allUnclrdTrnsEndDate").val() === 'undefined' ? '' : $("#allUnclrdTrnsEndDate").val();
    var qNotCleared = $('#allUnclrdTrnsNtClrd:checked').length > 0;
    var qLowVal = typeof $("#allUnclrdTrnsLowVal").val() === 'undefined' ? 0 : $("#allUnclrdTrnsLowVal").val();
    var qHighVal = typeof $("#allUnclrdTrnsHighVal").val() === 'undefined' ? 0 : $("#allUnclrdTrnsHighVal").val();
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
            "&qNotCleared=" + qNotCleared + "&qLowVal=" + qLowVal + "&qHighVal=" + qHighVal;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllUnclrdTrns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllUnclrdTrns(actionText, slctr, linkArgs);
    }
}

function clrUnclrMCFTrnsRqst(pKeyID, actionTyp, actionTyp1)
{
    var trnsGlAcId = typeof $('#trnsGlAcId').val() === 'undefined' ? -1 : $('#trnsGlAcId').val();
    if (actionTyp === 'UnClear') {
        trnsGlAcId = -1;
    }
    var dialog = bootbox.confirm({
        title: actionTyp + 'Transaction?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">' + actionTyp + '</span> this Transaction?<br/>Action cannot be Undone!</p>',
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
                    title: actionTyp + 'Transaction?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> ' + actionTyp1 + ' Transaction...Please Wait...</p>',
                    callback: function () {
                        getAllUnclrdTrns('', '#allmodules', 'grp=17&typ=1&pg=3&vtyp=0&subPgNo=3.2');
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    subPgNo: 3.2,
                                    q: 'CLEAR_UNCLR',
                                    actionTyp: actionTyp,
                                    trnsChequeId: pKeyID,
                                    trnsGlAcId: trnsGlAcId
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

function getAllStdngOrdrs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allStdngOrdrsSrchFor").val() === 'undefined' ? '%' : $("#allStdngOrdrsSrchFor").val();
    var srchIn = typeof $("#allStdngOrdrsSrchIn").val() === 'undefined' ? 'Both' : $("#allStdngOrdrsSrchIn").val();
    var pageNo = typeof $("#allStdngOrdrsPageNo").val() === 'undefined' ? 1 : $("#allStdngOrdrsPageNo").val();
    var limitSze = typeof $("#allStdngOrdrsDsplySze").val() === 'undefined' ? 10 : $("#allStdngOrdrsDsplySze").val();
    var sortBy = typeof $("#allStdngOrdrsSortBy").val() === 'undefined' ? '' : $("#allStdngOrdrsSortBy").val();
    var qStrtDte = typeof $("#allStdngOrdrsStrtDate").val() === 'undefined' ? '' : $("#allStdngOrdrsStrtDate").val();
    var qEndDte = typeof $("#allStdngOrdrsEndDate").val() === 'undefined' ? '' : $("#allStdngOrdrsEndDate").val();
    var qRecurring = $('#allStdngOrdrsIsRcrng:checked').length > 0;
    var qNonRecurring = $('#allStdngOrdrsIsNotRcrng:checked').length > 0;
    var qNonExecuted = $('#allStdngOrdrsIsNotExctd:checked').length > 0;
    var qLowVal = typeof $("#allStdngOrdrsLowVal").val() === 'undefined' ? 0 : $("#allStdngOrdrsLowVal").val();
    var qHighVal = typeof $("#allStdngOrdrsHighVal").val() === 'undefined' ? 0 : $("#allStdngOrdrsHighVal").val();
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
            "&qRecurring=" + qRecurring + "&qNonRecurring=" + qNonRecurring + "&qNonExecuted=" + qNonExecuted + "&qLowVal=" + qLowVal + "&qHighVal=" + qHighVal;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllStdngOrdrs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllStdngOrdrs(actionText, slctr, linkArgs);
    }
}

function getOneAcntTrnsfrForm(pKeyID, trnsType, actionTxt)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    if (pKeyID <= 0 && actionTxt === 'ReloadDialog') {
        pKeyID = typeof $("#trnsfrOrderID").val() === 'undefined' ? -1 : $("#trnsfrOrderID").val();
    }
    var lnkArgs = 'grp=17&typ=1&pg=14&vtyp=0&sbmtdTrnsfrHdrID=' + pKeyID + '&subPgNo=3.5&trnsType=' + trnsType;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Transfer/Order Details (ID:' + pKeyID + ' Type:' + trnsType + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        $('#allOtherInputData99').val('0');
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
        $(function () {
            $('[data-toggle="tabajxtrnsfrs"]').off('click');
            $('[data-toggle="tabajxtrnsfrs"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=17&typ=1' + dttrgt;
                if (targ.indexOf('trnsfrOrdrExctns') >= 0) {
                    $('#trnsfrOrdrExctns').removeClass('hideNotice');
                    $('#trnsfrExtrDstntns').addClass('hideNotice');
                    $('#trnsfrMiscTrns').addClass('hideNotice');
                    $('#trnsfrExtrSources').addClass('hideNotice');
                } else if (targ.indexOf('trnsfrExtrDstntns') >= 0) {
                    $('#trnsfrOrdrExctns').addClass('hideNotice');
                    $('#trnsfrMiscTrns').addClass('hideNotice');
                    $('#trnsfrExtrDstntns').removeClass('hideNotice');
                    $('#trnsfrExtrSources').addClass('hideNotice');
                } else if (targ.indexOf('trnsfrExtrSources') >= 0) {
                    $('#trnsfrOrdrExctns').addClass('hideNotice');
                    $('#trnsfrExtrDstntns').addClass('hideNotice');
                    $('#trnsfrExtrSources').removeClass('hideNotice');
                    $('#trnsfrMiscTrns').addClass('hideNotice');
                } else if (targ.indexOf('trnsfrMiscTrns') >= 0) {
                    $('#trnsfrOrdrExctns').addClass('hideNotice');
                    $('#trnsfrExtrDstntns').addClass('hideNotice');
                    $('#trnsfrMiscTrns').removeClass('hideNotice');
                    $('#trnsfrExtrSources').addClass('hideNotice');
                }
            });
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            getAllStdngOrdrs('', '#allmodules', 'grp=17&typ=1&pg=3&vtyp=0&subPgNo=3.5');
            $('#myFormsModalBodyLg').html('');
            $('#myFormsModalTitleLg').html('');
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#trnsfrOrderExctnsTbl')) {
            var table1 = $('#trnsfrOrderExctnsTbl').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#trnsfrOrderExctnsTbl').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#allOtherInputData99').val(0);
    });
}

function onChangeTrnfrTyp()
{
    var trnsfrOrderTrsfrTyp = typeof $("#trnsfrOrderTrsfrTyp").val() === 'undefined' ? '' : $("#trnsfrOrderTrsfrTyp").val();
    if ((trnsfrOrderTrsfrTyp.trim() === "Interbank" || trnsfrOrderTrsfrTyp.trim() === "In-House"))
    {
        $("#trnsfrOrderDstTyp").val('Bank Account');
    }
    $("#trnsfrOrderDstAcNum").val('');
    $("#trnsfrOrderDstAcntID").val('-1');
}

function onChangeTrnfrTyp1(elmntPrfx)
{
    var trnsfrOrderTrsfrTyp = typeof $("#" + elmntPrfx + "_TrsfrTyp").val() === 'undefined' ? '' : $("#" + elmntPrfx + "_TrsfrTyp").val();
    if ((trnsfrOrderTrsfrTyp.trim() === "Interbank" || trnsfrOrderTrsfrTyp.trim() === "In-House"))
    {
        $("#" + elmntPrfx + "_DstTyp").val('Bank Account');
    }
    $("#" + elmntPrfx + "_DstAcntNo").val('');
    $("#" + elmntPrfx + "_DstAcntID").val('-1');
}

function saveAcntTrnsfrForm(funcCrncyNm, shdSbmt)
{
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeAcntTrnsfrBtn");
    }
    var trnsfrOrderID = typeof $("#trnsfrOrderID").val() === 'undefined' ? -1 : $("#trnsfrOrderID").val();
    var trnsfrOrderNum = typeof $("#trnsfrOrderNum").val() === 'undefined' ? '' : $("#trnsfrOrderNum").val();
    var trnsfrOrderAcntNm = typeof $("#acctNoFind").val() === 'undefined' ? '' : $("#acctNoFind").val();
    var trnsfrOrderTrsfrTyp = typeof $("#trnsfrOrderTrsfrTyp").val() === 'undefined' ? '' : $("#trnsfrOrderTrsfrTyp").val();
    var trnsfrOrderDstTyp = typeof $("#trnsfrOrderDstTyp").val() === 'undefined' ? '' : $("#trnsfrOrderDstTyp").val();
    var trnsfrOrderDstAcNum = typeof $("#trnsfrOrderDstAcNum").val() === 'undefined' ? '' : $("#trnsfrOrderDstAcNum").val();
    var trnsfrOrderDstAcntID = typeof $("#trnsfrOrderDstAcntID").val() === 'undefined' ? '-1' : $("#trnsfrOrderDstAcntID").val();
    var trnsfrOrdrTtlAmnt = typeof $("#trnsfrOrdrTtlAmnt").val() === 'undefined' ? '0' : $("#trnsfrOrdrTtlAmnt").val();
    var trnsExchngRate = typeof $("#trnsExchngRate").val() === 'undefined' ? '0' : $("#trnsExchngRate").val();
    var trnsfrOrderCrncyNm = typeof $("#trnsfrOrderCrncyNm").val() === 'undefined' ? '' : $("#trnsfrOrderCrncyNm").val();
    if (funcCrncyNm === trnsfrOrderCrncyNm) {
        $("#trnsExchngRate").val('1');
        trnsExchngRate = '1';
    }
    var trnsfrOrdrDesc = typeof $("#trnsfrOrdrDesc").val() === 'undefined' ? '' : $("#trnsfrOrdrDesc").val();
    var trnsfrOrderFrqncyNo = typeof $("#trnsfrOrderFrqncyNo").val() === 'undefined' ? '0' : $("#trnsfrOrderFrqncyNo").val();
    var trnsfrOrderFrqncyTyp = typeof $("#trnsfrOrderFrqncyTyp").val() === 'undefined' ? '' : $("#trnsfrOrderFrqncyTyp").val();
    var trnsfrOrderStrtDate = typeof $("#trnsfrOrderStrtDate").val() === 'undefined' ? '' : $("#trnsfrOrderStrtDate").val();
    var trnsfrOrderEndDate = typeof $("#trnsfrOrderEndDate").val() === 'undefined' ? '' : $("#trnsfrOrderEndDate").val();
    var trnsfrOrderBnkID = typeof $("#trnsfrOrderBnkID").val() === 'undefined' ? -1 : $("#trnsfrOrderBnkID").val();
    var trnsfrOrderBrnchID = typeof $("#trnsfrOrderBrnchID").val() === 'undefined' ? -1 : $("#trnsfrOrderBrnchID").val();
    var trnsfrOrderBnfcryNm = typeof $("#trnsfrOrderBnfcryNm").val() === 'undefined' ? '' : $("#trnsfrOrderBnfcryNm").val();
    var trnsfrOrderBnfcryAdrs = typeof $("#trnsfrOrderBnfcryAdrs").val() === 'undefined' ? '' : $("#trnsfrOrderBnfcryAdrs").val();
    var dfltMiscGlAcntID = typeof $("#dfltMiscGlAcntID").val() === 'undefined' ? '-1' : $("#dfltMiscGlAcntID").val();
    var dfltMiscGlAcntNo = typeof $("#dfltMiscGlAcntNo").val() === 'undefined' ? '' : $("#dfltMiscGlAcntNo").val();
    var errMsg = "";
    if (trnsfrOrdrDesc.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Remark/Narration cannot be empty!</span></p>';
    }
    if (trnsfrOrderCrncyNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Currency cannot be empty!</span></p>';
    }
    if (trnsfrOrderAcntNm.trim() === "" || trnsfrOrderDstAcNum.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Source and Destination Accounts cannot be empty!</span></p>';
    }
    trnsfrOrdrTtlAmnt = fmtAsNumber('trnsfrOrdrTtlAmnt').toFixed(2);
    if (typeof $("#trnsExchngRate").val() !== 'undefined') {
        trnsExchngRate = Number(trnsExchngRate.replace(/[^-?0-9\.]/g, '')).toFixed(5);
        $("#trnsExchngRate").val(trnsExchngRate);
    }
    trnsfrOrderDstAcntID = Number(trnsfrOrderDstAcntID.replace(/[^-?0-9\.]/g, ''));
    trnsfrOrderBnkID = Number(trnsfrOrderBnkID.replace(/[^-?0-9\.]/g, ''));
    trnsfrOrderBrnchID = Number(trnsfrOrderBrnchID.replace(/[^-?0-9\.]/g, ''));
    if (trnsfrOrdrTtlAmnt <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Total Amount must be above Zero!</span></p>';
    }
    if (trnsExchngRate <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Rate must be above Zero!</span></p>';
    }
    if (trnsfrOrderStrtDate.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Start Date cannot be empty!</span></p>';
    }
    if (trnsfrOrderBnfcryNm.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Beneficiary Name cannot be empty!</span></p>';
    }
    /*if (trnsfrOrderBnfcryAdrs.trim() === "")
     {
     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
     'font-weight:bold;color:red;">Beneficiary Address cannot be empty!</span></p>';
     }*/
    if ((trnsfrOrderTrsfrTyp.trim() === "Interbank" || trnsfrOrderTrsfrTyp.trim() === "In-House") && trnsfrOrderDstTyp.trim() !== "Bank Account")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Interbank/In-House Transfer Type must go with a Bank Account Destination Type!</span></p>';
    }
    if (trnsfrOrderTrsfrTyp.trim() === "In-House" && trnsfrOrderDstAcntID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please provide a valid In-House Bank Account!</span></p>';
    }
    if (trnsfrOrderTrsfrTyp.trim() === "Interbank" && (trnsfrOrderBnkID <= 0 || trnsfrOrderBrnchID <= 0))
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please provide a valid External Bank and Branch Information!</span></p>';
    }
    var slctdDstLines = "";
    var slctdSrcLines = "";
    var slctdMiscLines = "";
    var isVld = true;
    var slctdLnCnt = 0;
    var slctdLnCnt1 = 0;
    $('#oneTrnsfrDstntnsTable').find('tr').each(function (i, el) {
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
                    var lnDstAcNo = typeof $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val();
                    if (lnDstAcNo.trim() !== '')
                    {
                        var lnID = typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_LineID').val();
                        var lnTrnsfrType = typeof $('#' + rowPrfxNm + rndmNum + '_TrsfrTyp').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_TrsfrTyp').val();
                        var lnDstType = typeof $('#' + rowPrfxNm + rndmNum + '_DstTyp').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DstTyp').val();
                        var lnDstAcID = typeof $('#' + rowPrfxNm + rndmNum + '_DstAcntID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_DstAcntID').val();
                        var lnDstAmnt = typeof $('#' + rowPrfxNm + rndmNum + '_DstAmnt').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_DstAmnt').val();
                        var lnDstBnkID = typeof $('#' + rowPrfxNm + rndmNum + '_BnkID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_BnkID').val();
                        var lnDstBrnchID = typeof $('#' + rowPrfxNm + rndmNum + '_BrnchID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_BrnchID').val();
                        var lnDstBnfcryNm = typeof $('#' + rowPrfxNm + rndmNum + '_BnfcryNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_BnfcryNm').val();
                        if (lnTrnsfrType.trim() === '')
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_TrsfrTyp').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_TrsfrTyp').removeClass('rho-error');
                        }

                        if (lnDstType.trim() === '')
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_DstTyp').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_DstTyp').removeClass('rho-error');
                        }
                        if (Number(lnDstAmnt.replace(/[^-?0-9\.]/g, '')) > 0) {
                            $('#' + rowPrfxNm + rndmNum + '_DstAmnt').removeClass('rho-error');
                        } else {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_DstAmnt').addClass('rho-error');
                        }
                        if ((lnTrnsfrType.trim() === "Interbank" || lnTrnsfrType.trim() === "In-House") && lnDstType.trim() !== "Bank Account")
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_TrsfrTyp').addClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_DstTyp').addClass('rho-error');
                            /*errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                             'font-weight:bold;color:red;">Interbank/In-House Transfer Type must go with a Bank Account Destination Type!</span></p>';*/
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_TrsfrTyp').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_DstTyp').removeClass('rho-error');
                        }
                        /*if (lnTrnsfrType.trim() === "In-House" && Number(lnDstAcID.replace(/[^-?0-9\.]/g, '')) <= 0)
                         {
                         isVld = false;
                         $('#' + rowPrfxNm + rndmNum + '_TrsfrTyp').addClass('rho-error');
                         $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').addClass('rho-error');
                         } else {
                         $('#' + rowPrfxNm + rndmNum + '_TrsfrTyp').removeClass('rho-error');
                         $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').removeClass('rho-error');
                         }*/
                        if (lnTrnsfrType.trim() === "Interbank" && (Number(lnDstBnkID.replace(/[^-?0-9\.]/g, '')) <= 0 || Number(lnDstBrnchID.replace(/[^-?0-9\.]/g, '')) <= 0))
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_TrsfrTyp').addClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_BnkID').addClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_BrnchID').addClass('rho-error');
                            /*errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                             'font-weight:bold;color:red;">Please provide a valid External Bank and Branch Information!</span></p>';*/
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_TrsfrTyp').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_BnkID').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_BrnchID').removeClass('rho-error');
                        }
                        if (lnDstBnfcryNm.trim() === '')
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_BnfcryNm').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_BnfcryNm').removeClass('rho-error');
                        }
                        if (isVld === true)
                        {
                            slctdDstLines = slctdDstLines
                                    + lnID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnTrnsfrType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstAcNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstAcID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstAmnt.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstBnkID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstBrnchID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstBnfcryNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                            slctdLnCnt++;
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors Under Extra Destination Accounts!</span></p>';
    }
    isVld = true;
    $('#oneTrnsfrMiscTrnsTable').find('tr').each(function (i, el) {
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
                    var lnDstAcNo = typeof $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val();
                    if (lnDstAcNo.trim() !== '')
                    {
                        var lnID = typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_LineID').val();
                        var lnTrnsType = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').val();
                        var lnDstAcNo = typeof $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val();
                        var lnDstAmnt = typeof $('#' + rowPrfxNm + rndmNum + '_DstAmnt').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_DstAmnt').val();
                        var lnDstGLAcntID = typeof $('#' + rowPrfxNm + rndmNum + '_GlAcntID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_GlAcntID').val();
                        var lnDstRate = typeof $('#' + rowPrfxNm + rndmNum + '_Rate').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_Rate').val();
                        var lnDstRmrks = typeof $('#' + rowPrfxNm + rndmNum + '_Rmrks').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_Rmrks').val();
                        var lnDstCrncyNm = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm').val();
                        if (lnTrnsType.trim() === '')
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').removeClass('rho-error');
                        }
                        if (lnDstRmrks.trim() === '')
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Rmrks').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Rmrks').removeClass('rho-error');
                        }
                        if (Number(lnDstAmnt.replace(/[^-?0-9\.]/g, '')) > 0) {
                            $('#' + rowPrfxNm + rndmNum + '_DstAmnt').removeClass('rho-error');
                        } else {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_DstAmnt').addClass('rho-error');
                        }
                        if (Number(lnDstRate.replace(/[^-?0-9\.]/g, '')) > 0) {
                            $('#' + rowPrfxNm + rndmNum + '_Rate').removeClass('rho-error');
                        } else {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Rate').addClass('rho-error');
                        }
                        if (Number(lnDstGLAcntID.replace(/[^-?0-9\.]/g, '')) > 0) {
                            $('#' + rowPrfxNm + rndmNum + '_GlAcntNum').removeClass('rho-error');
                        } else {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_GlAcntNum').addClass('rho-error');
                        }
                        if (lnDstCrncyNm.trim() === '')
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm1').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm1').removeClass('rho-error');
                        }
                        if (isVld === true)
                        {
                            slctdMiscLines = slctdMiscLines
                                    + lnID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnTrnsType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstAcNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstAmnt.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstGLAcntID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstRate.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstRmrks.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstCrncyNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                            slctdLnCnt1++;
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors Under Miscellaneous Transactions!</span></p>';
    }
    isVld = true;
    $('#oneTrnsfrExtrSourcesTable').find('tr').each(function (i, el) {
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
                    var lnDstAcNo = typeof $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val();
                    if (lnDstAcNo.trim() !== '')
                    {
                        var lnID = typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_LineID').val();
                        var lnTrnsType = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').val();
                        var lnDstAcNo = typeof $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val();
                        var lnDstAmnt = typeof $('#' + rowPrfxNm + rndmNum + '_DstAmnt').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_DstAmnt').val();
                        var lnDstRate = typeof $('#' + rowPrfxNm + rndmNum + '_Rate').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_Rate').val();
                        var lnDstRmrks = typeof $('#' + rowPrfxNm + rndmNum + '_Rmrks').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_Rmrks').val();
                        var lnDstCrncyNm = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm').val();
                        if (lnTrnsType.trim() === '')
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').removeClass('rho-error');
                        }
                        if (lnDstRmrks.trim() === '')
                        {
                            $('#' + rowPrfxNm + rndmNum + '_Rmrks').val(trnsfrOrdrDesc);
                            lnDstRmrks = trnsfrOrdrDesc;
                        }
                        if (Number(lnDstAmnt.replace(/[^-?0-9\.]/g, '')) > 0) {
                            $('#' + rowPrfxNm + rndmNum + '_DstAmnt').removeClass('rho-error');
                        } else {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_DstAmnt').addClass('rho-error');
                        }
                        if (isVld === true)
                        {
                            slctdSrcLines = slctdSrcLines
                                    + lnID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnTrnsType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstAcNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstAmnt.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstRate.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstRmrks.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstCrncyNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                            slctdLnCnt1++;
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors Under Extra Source Accounts!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        if (shdSbmt === 1) {
            enableBtnFunc("fnlzeAcntTrnsfrBtn");
        }
        return false;
    }
    var msg = 'Transfer/Order';
    var dialog = bootbox.alert({
        title: 'Save ' + msg,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
        callback: function () {
            var trnsfrOrderID = typeof $("#trnsfrOrderID").val() === 'undefined' ? -1 : $("#trnsfrOrderID").val();
            if (trnsfrOrderID > 0) {
                getOneAcntTrnsfrForm(trnsfrOrderID, msg, 'ReloadDialog');
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
                    grp: 17,
                    typ: 1,
                    pg: 14,
                    q: 'UPDATE_CORE_BNK',
                    subPgNo: '3.5',
                    vtyp: 0,
                    trnsfrOrderID: trnsfrOrderID,
                    trnsfrOrderNum: trnsfrOrderNum,
                    trnsfrOrderAcntNm: trnsfrOrderAcntNm,
                    trnsfrOrdrDesc: trnsfrOrdrDesc,
                    trnsfrOrderTrsfrTyp: trnsfrOrderTrsfrTyp,
                    trnsfrOrderDstTyp: trnsfrOrderDstTyp,
                    trnsfrOrderDstAcNum: trnsfrOrderDstAcNum,
                    trnsfrOrdrTtlAmnt: trnsfrOrdrTtlAmnt,
                    trnsExchngRate: trnsExchngRate,
                    trnsfrOrderCrncyNm: trnsfrOrderCrncyNm,
                    trnsfrOrderFrqncyNo: trnsfrOrderFrqncyNo,
                    trnsfrOrderFrqncyTyp: trnsfrOrderFrqncyTyp,
                    trnsfrOrderStrtDate: trnsfrOrderStrtDate,
                    trnsfrOrderEndDate: trnsfrOrderEndDate,
                    trnsfrOrderBnkID: trnsfrOrderBnkID,
                    trnsfrOrderBrnchID: trnsfrOrderBrnchID,
                    trnsfrOrderBnfcryNm: trnsfrOrderBnfcryNm,
                    trnsfrOrderBnfcryAdrs: trnsfrOrderBnfcryAdrs,
                    dfltMiscGlAcntID: dfltMiscGlAcntID,
                    slctdDstLines: slctdDstLines,
                    slctdSrcLines: slctdSrcLines,
                    slctdMiscLines: slctdMiscLines,
                    shdSbmt: shdSbmt
                },
                success: function (result) {
                    var msg = "";
                    var data = result;
                    var p_trnsfrOrderID = -1;
                    if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                            replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                            replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                        obj = $.parseJSON(data);
                        p_trnsfrOrderID = obj.trnsfrOrderID;
                        msg = obj.sbmtMsg;
                        if (p_trnsfrOrderID > 0) {
                            $("#trnsfrOrderID").val(p_trnsfrOrderID);
                        }
                        if (msg.trim() === '') {
                            msg = "Transaction Saved Successfully!";
                        }
                    } else {
                        msg = data;
                    }
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(msg);
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

function authrzeAcntTrnsfrRqst(srcApp)
{
    if (typeof srcApp === 'undefined' || srcApp === null)
    {
        srcApp = 'FrmSO';
    }
    var pKeyID = typeof $("#trnsfrOrderID").val() === 'undefined' ? -1 : $("#trnsfrOrderID").val();
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
                        if (pKeyID > 0 && srcApp === 'FrmWKF') {
                            $('#myFormsModalLx').modal('hide');
                        } else {
                            getOneAcntTrnsfrForm(-1, 'Transfer/Order', 'ReloadDialog');
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 1044,
                                    q: 'FINALIZE_CORE_BNK',
                                    trnsfrOrderID: pKeyID
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
                            dialog1.find('.bootbox-body').html('<span style="color:red;">Nothing to Authorize!</span>');
                        }, 500);
                    }
                });
            }
        }
    });
}

function wthdrwAcntTrnsfrRqst(srcApp)
{
    if (typeof srcApp === 'undefined' || srcApp === null)
    {
        srcApp = 'FrmSO';
    }
    var pKeyID = typeof $("#trnsfrOrderID").val() === 'undefined' ? -1 : $("#trnsfrOrderID").val();
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
                        if (pKeyID > 0 && srcApp === 'FrmSO')
                        {
                            getOneAcntTrnsfrForm(pKeyID, 'Transfer/Order', 'ReloadDialog');
                        } else if (pKeyID > 0 && srcApp === 'FrmWKF')
                        {
                            $('#myFormsModalLx').modal('hide');
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 1041,
                                    actyp: 1041,
                                    q: 'DELETE_CORE_BNK',
                                    trnsfrOrderID: pKeyID
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
function cancelAcntTrnsfrRqst(srcApp)
{
    if (typeof srcApp === 'undefined' || srcApp === null)
    {
        srcApp = 'FrmSO';
    }
    var pKeyID = typeof $("#trnsfrOrderID").val() === 'undefined' ? -1 : $("#trnsfrOrderID").val();
    var dialog = bootbox.confirm({
        title: 'Cancel Request?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">STOP/CANCEL</span> this Request?<br/>Action cannot be Undone!</p>',
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
                    title: 'Stop/Cancel Request?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Stopping/Cancelling Request...Please Wait...</p>',
                    callback: function () {
                        if (pKeyID > 0 && srcApp === 'FrmSO')
                        {
                            getOneAcntTrnsfrForm(pKeyID, 'Transfer/Order', 'ReloadDialog');
                        } else if (pKeyID > 0 && srcApp === 'FrmWKF')
                        {
                            $('#myFormsModalLx').modal('hide');
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    actyp: 1042,
                                    q: 'DELETE_CORE_BNK',
                                    trnsfrOrderID: pKeyID
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

function delAcntTrnsfrHdr(rowIDAttrb)
{
    var msg = 'Transfer Transaction';
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var trnsNum = "";
    if (typeof $('#allStdngOrdrsHdrsRow' + rndmNum + '_StndngOrdrID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allStdngOrdrsHdrsRow' + rndmNum + '_StndngOrdrID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        trnsNum = $.trim($tds.eq(2).text());
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
                        $("body").css("padding", "0px 0px 0px 0px");
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE_CORE_BNK',
                                    actyp: 3,
                                    trnsfrOrderID: pKeyID,
                                    trnsfrOrderNum: trnsNum
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

function delAcntTrnsfrHdrDst(rowIDAttrb)
{
    var msg = 'Transfer Destination';
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var trnsNum = "";
    if (typeof $('#oneTrnsfrDstntnsRow' + rndmNum + '_LineID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#oneTrnsfrDstntnsRow' + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        trnsNum = $.trim($tds.eq(2).text());
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
                        $("body").css("padding", "0px 0px 0px 0px");
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE_CORE_BNK',
                                    actyp: 301,
                                    trnsfrOrderDstID: pKeyID,
                                    trnsfrOrderDstNum: trnsNum
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

function delAcntTrnsfrHdrSrc(rowIDAttrb)
{
    var msg = 'Transfer Source';
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var trnsNum = "";
    if (typeof $('#oneTrnsfrExtrSourcesRow' + rndmNum + '_LineID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#oneTrnsfrExtrSourcesRow' + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        trnsNum = $.trim($tds.eq(2).text());
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
                        $("body").css("padding", "0px 0px 0px 0px");
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE_CORE_BNK',
                                    actyp: 303,
                                    trnsfrOrderSrcID: pKeyID,
                                    trnsfrOrderSrcNum: trnsNum
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

function delAcntTrnsfrHdrMisc(rowIDAttrb)
{
    var msg = 'Miscellaneous Trns.';
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var trnsNum = "";
    if (typeof $('#oneTrnsfrMiscTrnsRow' + rndmNum + '_LineID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#oneTrnsfrMiscTrnsRow' + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        trnsNum = $.trim($tds.eq(2).text());
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
                        $("body").css("padding", "0px 0px 0px 0px");
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE_CORE_BNK',
                                    actyp: 302,
                                    trnsfrOrderMiscID: pKeyID,
                                    trnsfrOrderDstNum: trnsNum
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

function delBulkTrnsHdrMisc(rowIDAttrb)
{
    var msg = 'Miscellaneous Trns.';
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var trnsNum = "";
    if (typeof $('#oneTrnsfrMiscTrnsRow' + rndmNum + '_LineID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#oneTrnsfrMiscTrnsRow' + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        trnsNum = $.trim($tds.eq(2).text());
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
                        $("body").css("padding", "0px 0px 0px 0px");
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE_CORE_BNK',
                                    actyp: 402,
                                    trnsfrOrderMiscID: pKeyID,
                                    trnsfrOrderDstNum: trnsNum
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
function delBulkCashTrnsMain(rowIDAttrb)
{
    var msg = 'Miscellaneous Trns.';
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var trnsNum = "";
    if (typeof $('#oneVmsBCashTrnsRow' + rndmNum + '_LineID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#oneVmsBCashTrnsRow' + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        trnsNum = $.trim($tds.eq(2).text());
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
                        $("body").css("padding", "0px 0px 0px 0px");
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE_CORE_BNK',
                                    actyp: 401,
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
function delBulkTrnsChqLine(rowIDAttrb)
{
    var msg = 'CHEQUE Transaction Line';
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var chqNo = '';
    if (typeof $('#oneVmsTrnsRow' + rndmNum + '_TrnsLnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#oneVmsTrnsRow' + rndmNum + '_TrnsLnID').val();
        chqNo = $('#oneVmsTrnsRow' + rndmNum + '_ChqNo').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msg + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Cheque Line?<br/>Action cannot be Undone!</p>',
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE_CORE_BNK',
                                    actyp: 403,
                                    trnsLnID: pKeyID,
                                    chqNo: chqNo
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                            calcCshBrkdwnRowVal('oneVmsTrnsRow_1');
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
                            calcCshBrkdwnRowVal('oneVmsTrnsRow_1');
                        }, 500);
                    }
                });
            }
        }
    });
}

function getGnrlAcntSgntryForm(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdTrnsChqID = typeof $('#oneVmsTrnsRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? -1 : $('#oneVmsTrnsRow' + rndmNum + '_TrnsLnID').val();
    var chequeNo = typeof $('#oneVmsTrnsRow' + rndmNum + '_chqNo').val() === 'undefined' ? '' : $('#oneVmsTrnsRow' + rndmNum + '_chqNo').val();
    var chequeType = typeof $('#oneVmsTrnsRow' + rndmNum + '_chqType').val() === 'undefined' ? 'In-House' : $('#oneVmsTrnsRow' + rndmNum + '_chqType').val();
    var inMandate = typeof $('#oneVmsTrnsRow' + rndmNum + '_chqMandate').val() === 'undefined' ? '' : $('#oneVmsTrnsRow' + rndmNum + '_chqMandate').val();
    var lnkArgs = 'grp=17&typ=1&pg=16&vtyp=0&subPgNo=3.1.1&vtypActn=FIND_MANDATE&sbmtdTrnsChqID=' + sbmtdTrnsChqID +
            "&chequeNo=" + chequeNo +
            "&chequeType=" + chequeType +
            "&inMandate=" + inMandate +
            "&rowIDAttrb=" + rowIDAttrb;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', 'Mandate and Signatories', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        if (!$.fn.DataTable.isDataTable('#acctSignatoriesTblAdd')) {
            var table1 = $('#acctSignatoriesTblAdd').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#acctSignatoriesTblAdd').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function getGnrlAcntSgntryForm1(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var prfxNm = rowIDAttrb.split("_")[0];
    var sbmtdTrnsLnID = typeof $('#' + prfxNm + rndmNum + '_LineID').val() === 'undefined' ? -1 : $('#oneVmsTrnsRow' + rndmNum + '_LineID').val();
    var acountNo = typeof $('#' + prfxNm + rndmNum + '_DstAcntNo').val() === 'undefined' ? '' : $('#oneVmsTrnsRow' + rndmNum + '_DstAcntNo').val();
    var inMandate = typeof $('#' + prfxNm + rndmNum + '_chqMandate').val() === 'undefined' ? '' : $('#' + prfxNm + rndmNum + '_chqMandate').val();
    var lnkArgs = 'grp=17&typ=1&pg=16&vtyp=0&subPgNo=3.1.1&vtypActn=FIND_MANDATE1&sbmtdTrnsLnID=' + sbmtdTrnsLnID +
            "&acountNo=" + acountNo +
            "&inMandate=" + inMandate +
            "&rowIDAttrb=" + rowIDAttrb;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', 'Mandate and Signatories', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        if (!$.fn.DataTable.isDataTable('#acctSignatoriesTblAdd')) {
            var table1 = $('#acctSignatoriesTblAdd').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#acctSignatoriesTblAdd').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function getSlctdSgntries(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var inMandate = typeof $('#mandateChq').val() === 'undefined' ? '' : $('#mandateChq').val();
    var acctSignatories = "";
    var cntaSignLines = 0;
    $('#acctSignatoriesTblChq').find('tr').each(function (i, el) {
        cntaSignLines = cntaSignLines + 1;
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#acctSignatoriesTblChqRow_' + rndmNum).val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#acctSignatoriesTblChqRow_' + rndmNum).find('input[type="checkbox"]:checked').length == "1") {
                        acctSignatories = acctSignatories
                                + $('#acctSignatoriesTblChqRow' + rndmNum + '_ID').text().replace(/(~)+/g, "{-;-;}").replace(/(\|)+/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });
    var errMsg = "";
    if ((acctSignatories.trim() == "" && cntaSignLines > 0))
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please tick the appropriate Mandates!</span></p>';
    }
    if (inMandate.trim() == "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Mandate cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        /*$('#oneVmsTrnsRow' + rndmNum + '_chqMandate').val('');*/
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    } else {
        $('#oneVmsTrnsRow' + rndmNum + '_chqMandate').val(inMandate + "|" + acctSignatories);
        $('#myFormsModalx').modal('hide');
    }
}

function loadBio(page) {
    $.ajax({
        type: 'GET',
        url: page,
        success: function (data) {
            try {
                $('#content').html(data);
            } catch (err) {
                alert(err);
            }
        }
    });
}

function pushBio(page, dialog1) {
    $.ajax({
        beforeSend: function () {
            $('.help-blok').remove();
        },
        type: 'GET',
        url: page,
        success: function (data) {
            try {
                console.log('Data has been pushed..');
                var res = jQuery.parseJSON(data);
                if (res.result) {
                    $.each(res, function (key, value) {
                        if (key == 'reload') {
                            loadBio(value);
                            setTimeout(function () {
                                dialog1.find('.bootbox-body').html('Data saved..');
                            }, 100);
                        }
                    });
                } else if (res.result == false) {
                    $.each(res, function (key, value) {
                        if (key != 'result' && key != 'server' && key != 'notif') {
                            $('#' + key).after("<span class='help-blok'>" + value + "</span>");
                        } else if (key == 'server') {
                            setTimeout(function () {
                                dialog1.find('.bootbox-body').html(value);
                            }, 100);
                        }
                    });
                }
            } catch (err) {
                alert(err.message);
            }
        }
    });
}

/*DOCUMENT ATTACHMENTS*/

function editDocAttachment(attchmntID) {



}

function saveDocAttachment() {

    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var v_attchmntID = typeof $("#attchmntID").val() === 'undefined' ? '' : $("#attchmntID").val();
        var v_fileName = typeof $("#fileName").val() === 'undefined' ? '' : $("#fileName").val();
        var v_fileDesc = typeof $("#fileDesc").val() === 'undefined' ? '' : $("#fileDesc").val();
        var v_fileType = typeof $("#fileType").val() === 'undefined' ? '' : $("#fileType").val();
        var v_custID = typeof $("#custID").val() === 'undefined' ? -1 : $("#custID").val();
        var formData = new FormData();
        formData.append('grp', 17);
        formData.append('typ', 1);
        formData.append('q', 'UPLOAD');
        formData.append('actyp', 1);
        formData.append('fileName', v_fileName);
        formData.append('attchmntID', v_attchmntID);
        formData.append('fileDesc', v_fileDesc);
        formData.append('fileType', v_fileType);
        formData.append('attchmntFile', $('#attchmntFile')[0].files[0]);
        formData.append('custID', v_custID);
        /*var formData = {
         grp: 17, typ: 1, q: 'UPLOAD', actyp: 1,
         fileName: v_fileName,
         attchmntID: v_attchmntID,
         fileDesc: v_fileDesc,
         fileType: v_fileType,
         attchmntFile: v_attchmntFile            
         };  */
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                bootbox.alert({
                    size: "small",
                    title: "Rhomicom Message",
                    message: "File Uploaded Successfully!",
                    callback: function () {
                        /* your callback code */
                    }
                });
                var btnHtml = '<tr>' +
                        '<td>' +
                        '<button type="button" class="btn btn-default btn-sm" onclick="editFileDetails(' + data + ');" style="padding:2px !important;">' +
                        '<img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">' +
                        '</button>' +
                        '</td>' +
                        '<td>' +
                        '<button type="button" class="btn btn-default btn-sm" onclick="deleteFile(' + data + ');" style="padding:2px !important;">' +
                        '<img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">' +
                        '</button>' +
                        '</td>' +
                        '<td>' + v_fileName + '</td>' +
                        '<td>' + v_fileType + '</td>' +
                        '<td>' +
                        '<button type="button" class="btn btn-default btn-sm" onclick="downloadFile(' + data + ');" style="padding:2px !important;">' +
                        '<img src="cmn_images/dwldicon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">' +
                        '</button>' +
                        '</td>' +
                        '</tr>';
                $('#attchmntsTblEDT').append(btnHtml);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
}

function deleteDocAttachment(attchmntID) {

}

function prepareMcf(lnkArgs, htBody, targ, rspns)
{
    $('[data-toggle="tooltip"]').tooltip();
    if (lnkArgs.indexOf("&pg=1") !== -1 || (lnkArgs.indexOf("&pg=11") !== -1 && lnkArgs.indexOf("&vtypActn=VIEW") !== -1))
    {
        prepareCustRO(lnkArgs, htBody, targ, rspns);
    } else if (lnkArgs.indexOf("&vtypActn=EDIT") !== -1)
    {
        prepareCustEDT(lnkArgs, htBody, targ, rspns);
    } else {
        $(targ).html(rspns);
        htBody.removeClass("mdlloading");
        if (lnkArgs.indexOf("&pg=8&subPgNo=1.2") !== -1)
        {
            var table2 = null;
            if (!$.fn.DataTable.isDataTable('#allCrncysTable')) {
                table2 = $('#allCrncysTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allCrncysTable').wrap('<div class="dataTables_scroll"/>');
                $('#allCrncysForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
                if (!$.fn.DataTable.isDataTable('#crncyDenomsTable')) {
                    var table3 = $('#crncyDenomsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#crncyDenomsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#allCrncysTable tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        table2.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    var rndmNum = $(this).attr('id').split("_")[1];
                    var pKeyID = typeof $('#allCrncysRow' + rndmNum + '_CrncyID').val() === 'undefined' ? '%' : $('#allCrncysRow' + rndmNum + '_CrncyID').val();
                    getOneCrncyForm(pKeyID, 1);
                });
                $('#allCrncysTable tbody')
                        .on('mouseenter', 'tr', function () {
                            if ($(this).hasClass('highlight')) {
                                $(this).removeClass('highlight');
                            } else {
                                table2.$('tr.highlight').removeClass('highlight');
                                $(this).addClass('highlight');
                            }
                        });
            }
        } else if (lnkArgs.indexOf("&pg=8&subPgNo=1.3") !== -1)
        {
            var table2 = null;
            if (!$.fn.DataTable.isDataTable('#allVmsCgsTable')) {
                table2 = $('#allVmsCgsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allVmsCgsTable').wrap('<div class="dataTables_scroll"/>');
            }
        } else if (lnkArgs.indexOf("&pg=8&subPgNo=1.4") !== -1)
        {
            var table2 = null;
            if (!$.fn.DataTable.isDataTable('#allExchRatesTable')) {
                table2 = $('#allExchRatesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allExchRatesTable').wrap('<div class="dataTables_scroll"/>');
                $(".mcfExRate").focus(function () {
                    $(this).select();
                });
                $(".mcfExRate").change(function () {
                    var curValue = $(this).val();
                    $(this).val((Number(curValue.replace(/[^-?0-9\.]/g, '')).toFixed(15)));
                });
            }
        } else if (lnkArgs.indexOf("&pg=8&subPgNo=1.6") !== -1)
        {
            var table2 = null;
            if (!$.fn.DataTable.isDataTable('#allBnksTable')) {
                table2 = $('#allBnksTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allBnksTable').wrap('<div class="dataTables_scroll"/>');
                $('#allBnksForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
                if (!$.fn.DataTable.isDataTable('#allBnkBrnchsTable')) {
                    var table3 = $('#allBnkBrnchsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#allBnkBrnchsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#allBnksTable tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        table2.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    var rndmNum = $(this).attr('id').split("_")[1];
                    var pKeyID = typeof $('#allBnksRow' + rndmNum + '_BankID').val() === 'undefined' ? '%' : $('#allBnksRow' + rndmNum + '_BankID').val();
                    getOneBankDetail(pKeyID, 1);
                });
                $('#allBnksTable tbody')
                        .on('mouseenter', 'tr', function () {
                            if ($(this).hasClass('highlight')) {
                                $(this).removeClass('highlight');
                            } else {
                                table2.$('tr.highlight').removeClass('highlight');
                                $(this).addClass('highlight');
                            }
                        });
            }
        } else if (lnkArgs.indexOf("&pg=4&subPgNo=4.6&subVwtyp=3") !== -1)
        {
            var table2 = null;
            if (!$.fn.DataTable.isDataTable('#allRiskProfilesTable')) {
                table2 = $('#allRiskProfilesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allRiskProfilesTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allRiskProfilesForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable('#allRiskProfileFctrsTable')) {
                var table3 = $('#allRiskProfileFctrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allRiskProfileFctrsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allRiskProfilesTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pKeyID = typeof $('#allRiskProfilesRow' + rndmNum + '_RiskProfileID').val() === 'undefined' ? '%' : $('#allRiskProfilesRow' + rndmNum + '_RiskProfileID').val();
                getOneRiskProfileDetail(pKeyID, 1);
            });
            $('#allRiskProfilesTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table2.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
            $('#allOtherInputData99').val(0);
        } else if (lnkArgs.indexOf("&pg=4&subPgNo=4.6&subVwtyp=4") !== -1)
        {
            var table2 = null;
            if (!$.fn.DataTable.isDataTable('#allAssessmentSetsTable')) {
                table2 = $('#allAssessmentSetsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allAssessmentSetsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allAssessmentSetsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable('#allAssessmentSetPrflsTable')) {
                var table3 = $('#allAssessmentSetPrflsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allAssessmentSetPrflsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allAssessmentSetsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pKeyID = typeof $('#allAssessmentSetsRow' + rndmNum + '_AssessmentSetID').val() === 'undefined' ? '%' : $('#allAssessmentSetsRow' + rndmNum + '_AssessmentSetID').val();
                getOneAssessmentSetDetail(pKeyID, 1);
            });
            $('#allAssessmentSetsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table2.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
            $('#allOtherInputData99').val(0);
        } else if (lnkArgs.indexOf("&pg=4&subPgNo=4.8") !== -1)
        {
            var table2 = null;
            if (!$.fn.DataTable.isDataTable('#allSectorMajorsTable')) {
                table2 = $('#allSectorMajorsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allSectorMajorsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allSectorMajorsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable('#allSectorMinorTable')) {
                var table3 = $('#allSectorMinorTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allSectorMinorTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allSectorMajorsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pKeyID = typeof $('#allSectorMajorsRow' + rndmNum + '_SectorMajorID').val() === 'undefined' ? '%' : $('#allSectorMajorsRow' + rndmNum + '_SectorMajorID').val();
                getOneSectorMajorDetail(pKeyID, 1);
            });
            $('#allSectorMajorsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table2.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
            $('#allOtherInputData99').val(0);
        } else if (lnkArgs.indexOf("&pg=4&subPgNo=4.11") !== -1){
            displayCrdtCharts();
            //alert("Hiyaa"); 
        }
    }
    $(document).ready(function () {

        $('#mcfIndCstmrForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#allVmsCgsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#mcfCorpCstmrForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#mcfGrpCstmrForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#mcfOthPCstmrForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#mcfAcntTrnsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#mcfAcntOthPForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#dataAdminForm').submit(function (e) {
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
        var i = 0;
        $("#trnsNtAllwdDysSlt option").each(function ()
        {
            trnsNtAllwdDys[i] = $(this).val();
            i = i + 1;
        });
        var k = 0;
        $("#trnsNtAllwdDtsSlt option").each(function ()
        {
            trnsNtAllwdDys[k] = $(this).val();
            k = k + 1;
        });
    });
    $('#allOtherInputData99').val(0);
}

function prepareCustRO(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&vtyp=0") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#nationalIDTblRO')) {
                var table1 = $('#nationalIDTblRO').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": true
                });
            }
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="tabajxprflro"]').click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr('href');
                    var dttrgt = $this.attr('data-rhodata');
                    var linkArgs = 'grp=17&typ=1' + dttrgt;
                    return openATab(targ, linkArgs);
                });
            });
        } else if (lnkArgs.indexOf("&vtyp=1") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('.extPrsnDataTblRO')) {
                var table2 = $('.extPrsnDataTblRO').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": true
                });
            }
        } else if (lnkArgs.indexOf("&vtyp=2") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('.orgAsgnmentsTblsRO')) {
                var table2 = $('.orgAsgnmentsTblsRO').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": true
                });
            }
        } else if (lnkArgs.indexOf("&vtyp=3") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('.cvTblsRO')) {
                var table2 = $('.cvTblsRO').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": true
                });
            }
        } else if (lnkArgs.indexOf("&vtyp=4") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('.otherInfoTblsRO')) {
                var table2 = $('.otherInfoTblsRO').DataTable({
                    "paging": true,
                    "ordering": true,
                    "info": true,
                    "bFilter": true,
                    "scrollX": true
                });
            }
        }
        htBody.removeClass("mdlloading");
    });
}

function prepareCustEDT(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&vtyp=0") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#custSitesTblEDT')) {
                var table1 = $('#custSitesTblEDT').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#nationalIDTblEDT').wrap('<div class="dataTables_scroll" />');
                $(function () {
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
                    $('[data-toggle="tabajxprfledt"]').click(function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        var targ = $this.attr('href');
                        var dttrgt = $this.attr('data-rhodata');
                        var linkArgs = 'grp=17&typ=1' + dttrgt;
                        return openATab(targ, linkArgs);
                    });
                });
                $('#custSitesTblEDT tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        table1.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    var rndmNum = $(this).attr('id').split("_")[1];
                    var custSiteID = typeof $('#custSitesRow' + rndmNum + '_SiteID').val() === 'undefined' ? '%' : $('#custSitesRow' + rndmNum + '_SiteID').val();
                    var lnkArgs = "grp=17&typ=1&pg=11&vtyp=0&vtypActn=EDIT&siteID=" + custSiteID;
                    doAjax(lnkArgs, 'custSitesDet', 'ShowDialog', 'System Alert!', 'myFormsModalTitle', 'myFormsModalBody');
                });
                $('#custSitesTblEDT tbody').on('mouseenter', 'tr', function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        table1.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });
            }
        } else if (lnkArgs.indexOf("&vtyp=1") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('.extPrsnDataTblEDT')) {
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
            }
        } else if (lnkArgs.indexOf("&vtyp=2") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('.orgAsgnmentsTblsEDT')) {
                var table2 = $('.orgAsgnmentsTblsEDT').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('.orgAsgnmentsTblsEDT').wrap('<div class="dataTables_scroll"/>');
            }
        } else if (lnkArgs.indexOf("&vtyp=3") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('.cvTblsEDT')) {
                var table2 = $('.cvTblsEDT').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('.cvTblsEDT').wrap('<div class="dataTables_scroll"/>');
            }
        } else if (lnkArgs.indexOf("&vtyp=4") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('.otherInfoTblsEDT')) {
                var table2 = $('.otherInfoTblsEDT').DataTable({
                    "paging": true,
                    "ordering": true,
                    "info": true,
                    "bFilter": true,
                    "scrollX": false
                });
                $('.otherInfoTblsEDT').wrap('<div class="dataTables_scroll"/>');
            }
        }
        htBody.removeClass("mdlloading");
    });
}

function getOneOrgStpForm(orgID, vwtype)
{
    var lnkArgs = 'grp=5&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdOrgID=' + orgID;
    doAjax(lnkArgs, 'orgStpsDetailInfo', 'PasteDirect', '', '', '');
}

function getOneBulkTrnsForm(pKeyID, vwtype, actionTxt, rltnPrsnID, shdDoCashless)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    if (typeof rltnPrsnID === 'undefined' || rltnPrsnID === null)
    {
        rltnPrsnID = -1;
    }
    if (typeof shdDoCashless === 'undefined' || shdDoCashless === null)
    {
        shdDoCashless = 0;
    }
    var vmsTrnsCrncyNm = typeof $("#vmsTrnsCrncyNm").val() === 'undefined' ? '' : $("#vmsTrnsCrncyNm").val();
    var lnkArgs = 'grp=17&typ=1&pg=14&subPgNo=3.1.11&vtyp=0&sbmtdBatchHdrID=' + pKeyID + '&sbmtdRltnPrsnID=' + rltnPrsnID + '&sbmtdTrnsCrncyNm=' + vmsTrnsCrncyNm + "&shdDoCashless=" + shdDoCashless;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Bulk Teller Transactions (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        $('#allOtherInputData99').val('0');
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
        if (!$.fn.DataTable.isDataTable('#oneVmsBCashTrnsLnsTable')) {
            var table1 = $('#oneVmsBCashTrnsLnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneVmsBCashTrnsLnsTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#cashBreakdownTblEDT')) {
            var table1 = $('#cashBreakdownTblEDT').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#cashBreakdownTblEDT').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $(".blkCshAcnt").focus(function () {
            $(this).select();
        });
        $(".blkCshDocNm").focus(function () {
            $(this).select();
        });
        $(".blkCshAmnt").focus(function () {
            $(this).select();
        });
        $(".blkCshRate").focus(function () {
            $(this).select();
        });

        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            $('#myFormsModalTitleLg').html('');
            $('#myFormsModalBodyLg').html('');
            getCustData('', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.1.11', 'mcfAcntTrns');
            $(e.currentTarget).unbind();
        });
        $('[data-toggle="tabajxblktrns"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            $(targ + 'tab').tab('show');
            if (targ.indexOf('blkTrnsCash') >= 0) {
                $('#addChqTrnsBtn').addClass('hideNotice');
                $('#addCashTrnsBtn').removeClass('hideNotice');
                $('#addMiscTrnsBtn').addClass('hideNotice');
                $('#exprtMiscTrnsBtn').addClass('hideNotice');
                $('#imprtMiscTrnsBtn').addClass('hideNotice');
                $('#blkTrnsCash').removeClass('hideNotice');
                $('#blkTrnsCheques').addClass('hideNotice');
                $('#blkTrnsTllrTill').addClass('hideNotice');
                $('#blkTrnsMisc').addClass('hideNotice');
            } else if (targ.indexOf('blkTrnsCheques') >= 0) {
                $('#addChqTrnsBtn').removeClass('hideNotice');
                $('#addCashTrnsBtn').addClass('hideNotice');
                $('#addMiscTrnsBtn').addClass('hideNotice');
                $('#exprtMiscTrnsBtn').addClass('hideNotice');
                $('#imprtMiscTrnsBtn').addClass('hideNotice');
                $('#blkTrnsCheques').removeClass('hideNotice');
                $('#blkTrnsCash').addClass('hideNotice');
                $('#blkTrnsTllrTill').addClass('hideNotice');
                $('#blkTrnsMisc').addClass('hideNotice');
            } else if (targ.indexOf('blkTrnsTllrTill') >= 0) {
                $('#addChqTrnsBtn').addClass('hideNotice');
                $('#addCashTrnsBtn').addClass('hideNotice');
                $('#addMiscTrnsBtn').addClass('hideNotice');
                $('#exprtMiscTrnsBtn').addClass('hideNotice');
                $('#imprtMiscTrnsBtn').addClass('hideNotice');
                $('#blkTrnsCash').addClass('hideNotice');
                $('#blkTrnsCheques').addClass('hideNotice');
                $('#blkTrnsTllrTill').removeClass('hideNotice');
                $('#blkTrnsMisc').addClass('hideNotice');
            } else if (targ.indexOf('blkTrnsMisc') >= 0) {
                $('#addChqTrnsBtn').addClass('hideNotice');
                $('#addCashTrnsBtn').addClass('hideNotice');
                $('#addMiscTrnsBtn').removeClass('hideNotice');
                $('#exprtMiscTrnsBtn').removeClass('hideNotice');
                $('#imprtMiscTrnsBtn').removeClass('hideNotice');
                $('#blkTrnsCash').addClass('hideNotice');
                $('#blkTrnsCheques').addClass('hideNotice');
                $('#blkTrnsTllrTill').addClass('hideNotice');
                $('#blkTrnsMisc').removeClass('hideNotice');
            }
        });
        $('#oneVmsTrnsLnsTable tr').off('click');
        $('#oneVmsBCashTrnsLnsTable tr').off('click');
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
        calcAllMcfCbTtl();
        calcBlkCshRowsTtlVals();
        $(".cbQty").focus(function () {
            $(this).select();
        });
        $(".cbTTlAmnt").focus(function () {
            $(this).select();
        });
        $(".cbExchngRate").focus(function () {
            $(this).select();
        });
        $(".cbRnngBal").focus(function () {
            $(this).select();
        });
    });
}

function saveBatchTrnsForm(funcCrncyNm, shdSbmt)
{
    var vmsTrnsHdrID = typeof $("#vmsTrnsHdrID").val() === 'undefined' ? -1 : $("#vmsTrnsHdrID").val();
    var vmsTrnsNum = typeof $("#vmsTrnsNum").val() === 'undefined' ? '' : $("#vmsTrnsNum").val();
    var vmsTrnsDate = typeof $("#vmsTrnsDate").val() === 'undefined' ? '' : $("#vmsTrnsDate").val();
    var vmsTrnsDesc = typeof $("#vmsTrnsDesc").val() === 'undefined' ? '' : $("#vmsTrnsDesc").val();
    var vmsOffctStaffLocID = typeof $("#vmsOffctStaffLocID").val() === 'undefined' ? '' : $("#vmsOffctStaffLocID").val();
    var vmsTrnsCrncyNm = typeof $("#vmsTrnsCrncyNm").val() === 'undefined' ? '' : $("#vmsTrnsCrncyNm").val();
    var ttlVMSDocAmntVal = typeof $("#ttlVMSDocAmntVal").val() === 'undefined' ? 0 : $("#ttlVMSDocAmntVal").val();
    var myCptrdValsTtlVal = typeof $("#myCptrdValsTtlVal").val() === 'undefined' ? 0 : $("#myCptrdValsTtlVal").val();
    var vmsDfltDestVltID = typeof $("#vmsDfltDestVltID").val() === 'undefined' ? -1 : $("#vmsDfltDestVltID").val();
    var vmsDfltDestCageID = typeof $("#vmsDfltDestCageID").val() === 'undefined' ? -1 : $("#vmsDfltDestCageID").val();
    var vmsBrnchLocID = typeof $("#vmsBrnchLocID").val() === 'undefined' ? -1 : $("#vmsBrnchLocID").val();
    var dfltMiscGlAcntID = typeof $("#dfltMiscGlAcntID").val() === 'undefined' ? '-1' : $("#dfltMiscGlAcntID").val();
    var dfltMiscGlAcntNo = typeof $("#dfltMiscGlAcntNo").val() === 'undefined' ? '' : $("#dfltMiscGlAcntNo").val();
    var shdDoCashless = typeof $("#shdDoCashless").val() === 'undefined' ? '0' : $("#shdDoCashless").val();
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
    var slctdChqLines = "";
    var slctdCashLines = "";
    var slctdTellerLines = "";
    var slctdMiscLines = "";
    var isVld = true;
    $('#oneVmsBCashTrnsLnsTable').find('tr').each(function (i, el) {
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
                    var lnLineID = typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined' ? -1 : $('#' + rowPrfxNm + rndmNum + '_LineID').val();
                    var lnTrnsType = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsType').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_TrnsType').val();
                    var lnAcntNo = typeof $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val();
                    var lnDocTyp = typeof $('#' + rowPrfxNm + rndmNum + '_DocType').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DocType').val();
                    var lnDocNo = typeof $('#' + rowPrfxNm + rndmNum + '_DocNo').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DocNo').val();
                    var lnCrncyNm = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm').val();
                    var lnAmount = typeof $('#' + rowPrfxNm + rndmNum + '_chqVal').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_chqVal').val();
                    var lnExchngRate = typeof $('#' + rowPrfxNm + rndmNum + '_exchngRate').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_exchngRate').val();
                    var lnActMndate = typeof $('#' + rowPrfxNm + rndmNum + '_chqMandate').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_chqMandate').val();
                    if (lnAcntNo.trim() === "")
                    {
                        /*Do Nothing*/
                    } else if (lnAmount.trim() !== "") {
                        if (Number(lnAmount.replace(/[^-?0-9\.]/g, '')) <= 0) {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_chqVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_chqVal').removeClass('rho-error');
                        }
                        if (Number(lnExchngRate.replace(/[^-?0-9\.]/g, '')) <= 0)
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_exchngRate').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_exchngRate').removeClass('rho-error');
                        }
                        if ((lnDocTyp.trim() !== "Paperless" && lnDocNo.trim() === ""))
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_DocNo').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_DocNo').removeClass('rho-error');
                        }
                        if ((lnTrnsType.trim() === "WITHDRAWAL" && lnActMndate.trim() === ""))
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_chqMandate').addClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_chqMandateLbl').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_chqMandate').removeClass('rho-error');
                            $('#' + rowPrfxNm + rndmNum + '_chqMandateLbl').removeClass('rho-error');
                        }
                        if (isVld === true)
                        {
                            slctdCashLines = slctdCashLines + lnLineID + "~"
                                    + lnTrnsType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnAcntNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDocTyp.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDocNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnCrncyNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnAmount.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnExchngRate.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnActMndate.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all line Errors under Cash Transactions!</span></p>';
    }
    isVld = true;
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i, el) {
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
                    var lnLineID = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined' ? -1 : $('#' + rowPrfxNm + rndmNum + '_LineID').val();
                    var lnChqType = typeof $('#' + rowPrfxNm + rndmNum + '_chqType').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_chqType').val();
                    var lnAcntNo = typeof $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val();
                    var lnBankID = typeof $('#' + rowPrfxNm + rndmNum + '_bnkID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_bnkID').val();
                    var lnBrnchID = typeof $('#' + rowPrfxNm + rndmNum + '_brnchID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_brnchID').val();
                    var lnChqNo = typeof $('#' + rowPrfxNm + rndmNum + '_chqNo').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_chqNo').val();
                    var lnChqDte = typeof $('#' + rowPrfxNm + rndmNum + '_chqDte').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_chqDte').val();
                    var lnCrncyNm = typeof $('#' + rowPrfxNm + rndmNum + '_chqCurNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_chqCurNm').val();
                    var lnAmount = typeof $('#' + rowPrfxNm + rndmNum + '_chqVal').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_chqVal').val();
                    var lnExchngRate = typeof $('#' + rowPrfxNm + rndmNum + '_exchngRate').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_exchngRate').val();
                    var lnActMndate = typeof $('#' + rowPrfxNm + rndmNum + '_chqMandate').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_chqMandate').val();
                    if (lnAcntNo.trim() === "")
                    {
                        /*Do Nothing*/
                    } else if (lnAmount.trim() !== "") {
                        if (Number(lnAmount.replace(/[^-?0-9\.]/g, '')) <= 0) {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_chqVal').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_chqVal').removeClass('rho-error');
                        }
                        if (Number(lnExchngRate.replace(/[^-?0-9\.]/g, '')) <= 0)
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_exchngRate').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_exchngRate').removeClass('rho-error');
                        }
                        if ((lnChqNo.trim() === ""))
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_chqNo').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_chqNo').removeClass('rho-error');
                        }
                        if (lnActMndate.trim() === "")
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_chqMandate').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_chqMandate').removeClass('rho-error');
                        }
                        if (isVld === true)
                        {
                            slctdChqLines = slctdChqLines + lnLineID + "~"
                                    + lnAcntNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnChqType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnBankID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnBrnchID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnChqNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnChqDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnCrncyNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnAmount.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnExchngRate.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnActMndate.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all line Errors under Cheque Transactions!</span></p>';
    }
    isVld = true;
    $('#cashBreakdownTblEDT').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#cashBreakdownRow_' + rndmNum).val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    slctdTellerLines = slctdTellerLines + $('#cashBreakdownRow' + rndmNum + '_denomID').text().replace(/(~)+/g, "{-;-;}").replace(/(\|)+/g, "{:;:;}") + "~"
                            + $('#cashBreakdownRow' + rndmNum + '_denomQty').val().replace(/(~)+/g, "{-;-;}").replace(/(\|)+/g, "{:;:;}") + "~"
                            + $('#cashBreakdownRow' + rndmNum + '_value').text().replace(/(~)+/g, "{-;-;}").replace(/(\|)+/g, "{:;:;}") + "~"
                            + $('#cashBreakdownRow' + rndmNum + '_cashAnalysisID').text().replace(/(~)+/g, "{-;-;}").replace(/(\|)+/g, "{:;:;}") + "~"
                            + $('#cashBreakdownRow' + rndmNum + '_ExchngRate').val().replace(/(~)+/g, "{-;-;}").replace(/(\|)+/g, "{:;:;}") + "|";
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all line Errors under Teller\'s Cash Breakdown!</span></p>';
    }
    isVld = true;
    $('#oneTrnsfrMiscTrnsTable').find('tr').each(function (i, el) {
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
                    var lnDstAcNo = typeof $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val();
                    if (lnDstAcNo.trim() !== '')
                    {
                        var lnID = typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_LineID').val();
                        var lnTrnsType = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').val();
                        var lnDstAcNo = typeof $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val();
                        var lnDstAmnt = typeof $('#' + rowPrfxNm + rndmNum + '_DstAmnt').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_DstAmnt').val();
                        var lnDstGLAcntID = typeof $('#' + rowPrfxNm + rndmNum + '_GlAcntID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_GlAcntID').val();
                        var lnDstRate = typeof $('#' + rowPrfxNm + rndmNum + '_Rate').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_Rate').val();
                        var lnDstRmrks = typeof $('#' + rowPrfxNm + rndmNum + '_Rmrks').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_Rmrks').val();
                        var lnDstCrncyNm = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm').val();
                        if (lnTrnsType.trim() === '')
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').removeClass('rho-error');
                        }
                        if (lnDstRmrks.trim() === '')
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Rmrks').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Rmrks').removeClass('rho-error');
                        }
                        if (Number(lnDstAmnt.replace(/[^-?0-9\.]/g, '')) > 0) {
                            $('#' + rowPrfxNm + rndmNum + '_DstAmnt').removeClass('rho-error');
                        } else {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_DstAmnt').addClass('rho-error');
                        }
                        if (Number(lnDstRate.replace(/[^-?0-9\.]/g, '')) > 0) {
                            $('#' + rowPrfxNm + rndmNum + '_Rate').removeClass('rho-error');
                        } else {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Rate').addClass('rho-error');
                        }
                        if (Number(lnDstGLAcntID.replace(/[^-?0-9\.]/g, '')) > 0) {
                            $('#' + rowPrfxNm + rndmNum + '_GlAcntNum').removeClass('rho-error');
                        } else {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_GlAcntNum').addClass('rho-error');
                        }
                        if (lnDstCrncyNm.trim() === '')
                        {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm1').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_TrnsCurNm1').removeClass('rho-error');
                        }
                        if (isVld === true)
                        {
                            slctdMiscLines = slctdMiscLines
                                    + lnID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnTrnsType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstAcNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstAmnt.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstGLAcntID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstRate.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstRmrks.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnDstCrncyNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all line Errors under Miscellaneous Transactions!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var msg = 'Bulk Customer Transaction';
    var dialog = bootbox.alert({
        title: 'Save ' + msg,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
        callback: function () {
            getOneBulkTrnsForm(vmsTrnsHdrID, 0, 'ReloadDialog', -1);
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
                    grp: 17,
                    typ: 1,
                    pg: 14,
                    q: 'UPDATE_CORE_BNK',
                    vtyp: 0,
                    subPgNo: '3.1.11',
                    vmsTrnsHdrID: vmsTrnsHdrID,
                    vmsTrnsNum: vmsTrnsNum,
                    vmsTrnsDesc: vmsTrnsDesc,
                    vmsOffctStaffLocID: vmsOffctStaffLocID,
                    vmsTrnsCrncyNm: vmsTrnsCrncyNm,
                    ttlVMSDocAmntVal: ttlVMSDocAmntVal,
                    myCptrdValsTtlVal: myCptrdValsTtlVal,
                    vmsDfltDestVltID: vmsDfltDestVltID,
                    vmsDfltDestCageID: vmsDfltDestCageID,
                    vmsTrnsDate: vmsTrnsDate,
                    vmsBrnchLocID: vmsBrnchLocID,
                    dfltMiscGlAcntID: dfltMiscGlAcntID,
                    shdDoCashless: shdDoCashless,
                    shdSbmt: shdSbmt,
                    slctdCashLines: slctdCashLines,
                    slctdChqLines: slctdChqLines,
                    slctdTellerLines: slctdTellerLines,
                    slctdMiscLines: slctdMiscLines
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

function saveBatchRvrsTrnsForm(funcCrncyNm, shdSbmt)
{
    var vmsTrnsHdrID = typeof $("#vmsTrnsHdrID").val() === 'undefined' ? -1 : $("#vmsTrnsHdrID").val();
    var vmsTrnsNum = typeof $("#vmsTrnsNum").val() === 'undefined' ? '' : $("#vmsTrnsNum").val();
    var vmsTrnsType = typeof $("#vmsTrnsType").val() === 'undefined' ? '' : $("#vmsTrnsType").val();
    var vmsTrnsDesc = typeof $("#vmsTrnsDesc").val() === 'undefined' ? '' : $("#vmsTrnsDesc").val();
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
    } else if (vmsTrnsType.trim() === "Deposits")
    {
        if (Number(vmsCstmrID) <= 0)
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
    } else if (vmsTrnsType.trim() === "Currency Destruction")
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

function wthdrwBatchTrnsRqst(vwtype)
{
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
                            getOneBulkTrnsForm(pKeyID, vwtype, 'ReloadDialog', -1);
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE_CORE_BNK',
                                    actyp: 1072,
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

function authrzeBatchTrnsRqst(vwtype)
{
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
                if (result === true)
                {
                    var dialog1 = bootbox.alert({
                        title: 'Authorizing Request?',
                        size: 'small',
                        message: '<p><i class="fa fa-spin fa-spinner"></i> Authorizing Request...Please Wait...</p>',
                        callback: function () {
                            if (pKeyID > 0)
                            {
                                getOneBulkTrnsForm(pKeyID, vwtype, 'ReloadDialog', -1);
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
                                        grp: 17,
                                        typ: 1,
                                        pg: 1046,
                                        q: 'FINALIZE_CORE_BNK',
                                        actyp: 1,
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
                                dialog1.find('.bootbox-body').html('<span style="color:red;">Nothing to Authorize!</span>');
                            }, 500);
                        }
                    });
                }
            }
        }
    });
}

function blkTrnsDocTypeChng(rowIDAttrb)
{
    var prefix = rowIDAttrb.split("_")[0];
    var rndmNum = rowIDAttrb.split("_")[1];
    var docType = typeof $('#' + prefix + rndmNum + '_DocType').val() === 'undefined' ? '' : $('#' + prefix + rndmNum + '_DocType').val();
    $('#' + prefix + rndmNum + '_DocNo').val('');
    if (docType == "Paperless") {
        $('#' + prefix + rndmNum + '_DocNo').attr('readonly', true);
        $('#' + prefix + rndmNum + '_DocNo').removeClass('rqrdFld');
    } else {
        $('#' + prefix + rndmNum + '_DocNo').removeAttr("readonly");
        $('#' + prefix + rndmNum + '_DocNo').addClass('rqrdFld');
    }
}

function blkTrnsFormTtlFldKeyPress(event, elementIDAttrb, sbmtdTblRowID, classNm)
{
    if (event.which === 13) {
        var nextItem;
        var nextItemVal = 0;
        var curItemVal = 0;
        var indx = 0;
        var ttlElmnts = 0;
        $('#' + sbmtdTblRowID + ' .' + classNm).each(function (i, el) {
            ttlElmnts++;
            if ($(el).attr('id') === elementIDAttrb)
            {
                indx = i;
            }
        });
        curItemVal = indx + 1;
        if (curItemVal === ttlElmnts) {
            nextItem = $('#' + sbmtdTblRowID + ' .' + classNm).eq(0);
        } else {
            nextItemVal = Number(curItemVal);
            nextItem = $('#' + sbmtdTblRowID + ' .' + classNm).eq(nextItemVal);
        }
        nextItem.focus();
    }
}

function delBatchTrnsHdr(rowIDAttrb)
{
    var msg = 'Bulk Customer Transaction';
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var trnsNum = "";
    if (typeof $('#allMcfTrnsHdrsRow' + rndmNum + '_HdrID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allMcfTrnsHdrsRow' + rndmNum + '_HdrID').val();
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
                        $("body").css("padding", "0px 0px 0px 0px");
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
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE_CORE_BNK',
                                    actyp: 4,
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

function afterBulkAcntSlctn(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var nwAcntNo = $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val();
    if (nwAcntNo.trim() !== "") {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append('grp', 17);
            formData.append('typ', 1);
            formData.append('pg', 14);
            formData.append('q', 'VIEW');
            formData.append('vtyp', 42);
            formData.append('subPgNo', '3.1.11');
            formData.append('sbmtdAcntNo', nwAcntNo);
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
                        $('#' + rowPrfxNm + rndmNum + '_BalsAfta').val(data.BalsB4);
                        $('#' + rowPrfxNm + rndmNum + '_AcntTitle').val(data.AcntNm);
                        calcBlkCshRowsTtlVals();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.warn(jqXHR.responseText);
                }
            });
        });
    } else {
        $('#' + rowPrfxNm + rndmNum + '_BalsAftaD').val(0.00);
        $('#' + rowPrfxNm + rndmNum + '_BalsAfta').val(0.00);
        $('#' + rowPrfxNm + rndmNum + '_AcntTitle').val(0.00);
        calcBlkCshRowsTtlVals();
    }
}

function exprtBulkMiscTrns()
{
    var vmsTrnsHdrID = typeof $("#vmsTrnsHdrID").val() === 'undefined' ? -1 : $("#vmsTrnsHdrID").val();
    var msgTitle = "Bulk Misc. Transactions";
    var exprtMsg = '<form role="form">' +
            '<p style="color:#000;">' +
            'How many ' + msgTitle + ' will you like to Export?' +
            '<br/>1=No ' + msgTitle + '(Empty Template)' +
            '<br/>2=All ' + msgTitle + '' +
            '<br/>3-Infinity=Specify the exact number of ' + msgTitle + ' to Export<br/>' +
            '</p>' +
            '<div class="form-group" style="margin-bottom:10px !important;">' +
            '<div class="input-group">' +
            '<span class="input-group-addon" id="basic-addon1">' +
            '<i class="fa fa-sort-numeric-asc fa-fw fa-border"></i></span>' +
            '<input type="number" class="form-control" placeholder="" aria-describedby="basic-addon1" id="recsToExprt" name="recsToExprt" onkeyup="" tabindex="0" autofocus>' +
            '</div>' +
            '</div>' +
            '<p style="font-size:12px;" id="msgAreaExprt">&nbsp;' +
            '</p>' +
            '</form>';
    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_SMALL,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: 'Export ' + msgTitle + '!',
        message: exprtMsg,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) {
        },
        onshown: function (dialogItself) {
            exprtBtn = dialogItself.getButton('btn_exprt_recs');
            $('#recsToExprt').focus();
        },
        buttons: [{
                label: 'Cancel',
                icon: 'glyphicon glyphicon-menu-left',
                cssClass: 'btn-default',
                action: function (dialogItself) {
                    window.clearInterval(prgstimerid2);
                    dialogItself.close();
                    ClearAllIntervals();
                }
            }, {
                id: 'btn_exprt_recs',
                label: 'Export',
                icon: 'glyphicon glyphicon-menu-right',
                cssClass: 'btn-primary',
                action: function (dialogItself) {
                    /*Validate Input and Do Ajax if OK*/
                    var inptNum = $('#recsToExprt').val();
                    if (!isNumber(inptNum))
                    {
                        var dialog = bootbox.alert({
                            title: 'Exporting ' + msgTitle + '',
                            size: 'small',
                            message: 'Please provide a valid Number!',
                            callback: function () {
                            }
                        });
                        return false;
                    } else {
                        var $button = this;
                        $button.disable();
                        $button.spin();
                        dialogItself.setClosable(false);
                        document.getElementById("msgAreaExprt").innerHTML = "<img style=\"width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;\" src='cmn_images/ajax-loader2.gif'/><br/><span style=\"color:blue;font-size:11px;text-align: left;margin-top:0px;\">Working on Export...Please Wait...</span>";
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 17,
                                    typ: 1,
                                    pg: 14,
                                    q: 'IMPRT_EXPRT_TRNS',
                                    actyp: 5,
                                    inptNum: inptNum,
                                    batchHdrID: vmsTrnsHdrID
                                }
                            });
                            prgstimerid2 = window.setInterval(rfrshExprtBulkMiscTrnsPrcs, 1000);
                        });
                    }
                }
            }]
    });
}

function rfrshExprtBulkMiscTrnsPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 17,
            typ: 1,
            pg: 14,
            q: 'IMPRT_EXPRT_TRNS',
            actyp: 6
        },
        success: function (data) {
            if (data.percent >= 100) {
                if (data.message.indexOf('Error') !== -1)
                {
                    $("#msgAreaExprt").html(data.message);
                } else {
                    $("#msgAreaExprt").html(data.message + '<br/><a href="' + data.dwnld_url + '">Click to Download File!</a>');
                }
                exprtBtn.enable();
                exprtBtn.stopSpin();
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            } else {
                $("#msgAreaExprt").html('<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>'
                        + data.message);
                document.getElementById("msgAreaExprt").innerHTML = '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>'
                        + data.message;
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        }
    });
}

function importBulkMiscTrns()
{
    var vmsTrnsHdrID = typeof $("#vmsTrnsHdrID").val() === 'undefined' ? -1 : $("#vmsTrnsHdrID").val();
    var msgTitle = "Transactions";
    var dataToSend = "";
    var isFileValid = true;
    var dialog1 = bootbox.confirm({
        title: 'Import ' + msgTitle + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">IMPORT ' + msgTitle.toUpperCase() + '</span>?<br/>Action cannot be Undone!</p>',
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
                if (isReaderAPIAvlbl()) {
                    $("#allOtherFileInput6").val('');
                    $("#allOtherFileInput6").off('change');
                    $("#allOtherFileInput6").change(function () {
                        var fileName = $(this).val();
                        var input = document.getElementById('allOtherFileInput6');
                        var file = input.files[0];
                        var output = '';
                        output += '<span style="font-weight:bold;">' + escape(file.name) + '</span><br />\n';
                        output += ' - FileType: ' + (file.type || 'n/a') + '<br />\n';
                        output += ' - FileSize: ' + file.size + ' bytes<br />\n';
                        output += ' - LastModified: ' + (file.lastModifiedDate ? file.lastModifiedDate.toLocaleDateString() : 'n/a') + '<br />\n';
                        var reader = new FileReader();
                        BootstrapDialog.show({
                            size: BootstrapDialog.SIZE_LARGE,
                            type: BootstrapDialog.TYPE_DEFAULT,
                            title: 'Validating Selected File',
                            message: '<div id="myProgress"><div id="myBar"></div></div><div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Validating Selected File...Please Wait...</div><br/><div id="fileInformation">' + output + '</div>',
                            animate: true,
                            closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: false,
                            onshow: function (dialogItself) {
                                setTimeout(function () {
                                    var $footerButton = dialogItself.getButton('btn-srvr-prcs');
                                    $footerButton.disable();
                                    reader.onerror = function (evt) {
                                        switch (evt.target.error.code) {
                                            case evt.target.error.NOT_FOUND_ERR:
                                                alert('File Not Found!');
                                                break;
                                            case evt.target.error.NOT_READABLE_ERR:
                                                alert('File is not readable');
                                                break;
                                            case evt.target.error.ABORT_ERR:
                                                break;
                                            default:
                                                alert('An error occurred reading this file.');
                                        }
                                        ;
                                    };
                                    reader.onprogress = function (evt) {
                                        if (evt.lengthComputable) {
                                            var percentLoaded = Math.round((evt.loaded / evt.total) * 100);
                                            var elem = document.getElementById('myBar');
                                            elem.style.width = percentLoaded + '%';
                                            if (percentLoaded < 100) {
                                                $("#myInformation").html('<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>' + percentLoaded + '% Validating Selected File...Please Wait...</span>');
                                            } else {
                                                $("#myInformation").html('<span style="color:green;"><i class="fa fa-check"></i>' + percentLoaded + '% Validating Selected File Completed!</span>');
                                                var $footerButton = dialogItself.getButton('btn-srvr-prcs');
                                                if (isFileValid == true) {
                                                    $footerButton.enable();
                                                } else {
                                                    $footerButton.disable();
                                                }
                                            }
                                        }
                                    };
                                    reader.onabort = function (e) {
                                        alert('File read cancelled');
                                    };
                                    reader.onloadstart = function (e) {
                                        var elem = document.getElementById('myBar');
                                        elem.style.width = '1%';
                                        $("#myInformation").html('<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>1% Started Importing ' + msgTitle + '...Please Wait...</span>');
                                    };
                                    reader.onload = function (event) {
                                        try {
                                            var csv = event.target.result;
                                            var data = $.csv.toArrays(csv);
                                            var rwCntr = 0;
                                            var colCntr = 0;
                                            var vldRwCntr = 0;
                                            var number = "";
                                            var trnsType = "";
                                            var trnsDesc = "";
                                            var acntNum = "";
                                            var acntTitle = "";
                                            var amount = "";
                                            var glAcntNum = "";
                                            var glAcntTitle = "";
                                            for (var row in data) {
                                                for (var item in data[row]) {
                                                    colCntr++;
                                                    switch (colCntr) {
                                                        case 1:
                                                            number = data[row][item];
                                                            break;
                                                        case 2:
                                                            trnsType = data[row][item];
                                                            break;
                                                        case 3:
                                                            trnsDesc = data[row][item];
                                                            break;
                                                        case 4:
                                                            acntNum = data[row][item];
                                                            break;
                                                        case 5:
                                                            acntTitle = data[row][item];
                                                            break;
                                                        case 6:
                                                            amount = data[row][item];
                                                            break;
                                                        case 7:
                                                            glAcntNum = data[row][item];
                                                            break;
                                                        case 8:
                                                            glAcntTitle = data[row][item];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                title: 'Error-Validating Selected File',
                                                                size: 'small',
                                                                message: '<span style="color:red;font-weight:bold:">An error occurred reading this file.Invalid Column in File!<br/>Row No.:' + number + '</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                }
                                                            });
                                                    }
                                                    ;
                                                }
                                                if (rwCntr === 0) {
                                                    if (number.toUpperCase() === "NO." && trnsType.toUpperCase() === "Transaction Type**".toUpperCase() && acntNum.toUpperCase() === "Account Number**".toUpperCase() && glAcntNum.toUpperCase() === "GL Account Number**".toUpperCase())
                                                    {

                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            title: 'Error-Import ' + msgTitle + '',
                                                            size: 'small',
                                                            message: '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            }
                                                        });
                                                    }
                                                }
                                                if (trnsDesc.trim() !== "" && trnsType.trim() !== ""
                                                        && acntNum.trim() !== ""
                                                        && glAcntNum.trim() !== ""
                                                        && amount.trim() !== "")
                                                {
                                                    dataToSend = dataToSend + number.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + trnsType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + trnsDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + acntNum.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + acntTitle.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + amount.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + glAcntNum.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + glAcntTitle.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                                    vldRwCntr++;
                                                }
                                                colCntr = 0;
                                                rwCntr++;
                                            }
                                            output += '<br/><span style="color:blue;font-weight:bold;">No. of Valid Rows:' + vldRwCntr;
                                            output += '<br/>Total No. of Rows:' + rwCntr + '</span>';
                                            $("#fileInformation").html(output);
                                        } catch (err) {
                                            var dialog = bootbox.alert({
                                                title: 'Error-Import ' + msgTitle + '',
                                                size: 'small',
                                                message: 'Error:' + err.message,
                                                callback: function () {
                                                    isFileValid = false;
                                                    reader.abort();
                                                }
                                            });
                                        }
                                    };
                                    reader.readAsText(file);
                                }, 500);
                            },
                            buttons: [{
                                    label: 'Cancel',
                                    icon: 'glyphicon glyphicon-menu-left',
                                    cssClass: 'btn-default',
                                    action: function (dialogItself) {
                                        isFileValid = false;
                                        reader.abort();
                                        dialogItself.close();
                                    }
                                }, {
                                    id: 'btn-srvr-prcs',
                                    label: 'Start Server Processing',
                                    icon: 'glyphicon glyphicon-menu-right',
                                    cssClass: 'btn-primary',
                                    action: function (dialogItself) {
                                        if (isFileValid == true) {
                                            dialogItself.close();
                                            saveBulkMiscTrns(dataToSend, vmsTrnsHdrID);
                                        } else {
                                            var dialog = bootbox.alert({
                                                title: 'Error-Import ' + msgTitle + '',
                                                size: 'small',
                                                message: '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                callback: function () {
                                                }
                                            });
                                        }
                                    }
                                }]
                        });
                    });
                    performFileClick('allOtherFileInput6');
                }
            }
        }
    });
}

function saveBulkMiscTrns(dataToSend, batchHdrID)
{
    if (dataToSend.trim() === '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;">No Data to Send!</span></p>'
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Importing Customer Transactions',
        size: 'small',
        message: '<div id="myProgress1"><div id="myBar1"></div></div><div id="myInformation1"><i class="fa fa-spin fa-spinner"></i> Importing Customer Transactions...Please Wait...</div>',
        callback: function () {
            window.clearInterval(prgstimerid2);
            ClearAllIntervals();
            getOneBulkTrnsForm(batchHdrID, 0, 'ReloadDialog');
            ClearAllIntervals();
        }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            //alert(dataToSend);
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 17,
                    typ: 1,
                    pg: 14,
                    q: 'IMPRT_EXPRT_TRNS',
                    actyp: 7,
                    batchHdrID: batchHdrID,
                    dataToSend: dataToSend
                },
                success: function (data) {
                    $("#myInformation1").html(data.message);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
            prgstimerid2 = window.setInterval(rfrshSaveBulkMiscTrns, 1000);
        });
    });
}

function rfrshSaveBulkMiscTrns() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 17,
            typ: 1,
            pg: 14,
            q: 'IMPRT_EXPRT_TRNS',
            actyp: 8
        },
        success: function (data) {
            var elem = document.getElementById('myBar1');
            elem.style.width = data.percent + '%';
            $("#myInformation1").html(data.message);
            if (data.percent >= 100) {
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        }
    });
}

function afterAcntSlctn(rowIDAttrb, dstSuffix)
{
    if (typeof dstSuffix === 'undefined' || dstSuffix === null)
    {
        dstSuffix = '_AcntTitle';
    }
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var nwAcntNo = $('#' + rowPrfxNm + rndmNum + '_DstAcntNo').val();
    if (nwAcntNo.trim() !== "") {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append('grp', 17);
            formData.append('typ', 1);
            formData.append('pg', 14);
            formData.append('q', 'VIEW');
            formData.append('vtyp', 42);
            formData.append('subPgNo', '3.1.11');
            formData.append('sbmtdAcntNo', nwAcntNo);
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
                        $('#' + rowPrfxNm + rndmNum + dstSuffix + '').val(data.AcntNm);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.warn(jqXHR.responseText);
                }
            });
        });
    } else {
        $('#' + rowPrfxNm + rndmNum + dstSuffix + '').val("");
    }
}

function getCustAcctsInfo2Form(elementID, modalBodyID, titleElementID, formTitle, pgNo, subPgNo, vtyp, vtypActn, pKeyElemntID, listTableID, rowID, acctNoElmntID, callBackFunc)
{
    $('#allOtherInputData99').val(0);
    if (acctNo === "" || acctNo === "undefined") {
        acctNo = $("#acctNoFind").val();
    }
    var acctNo = typeof $("#" + acctNoElmntID).val() === 'undefined' ? '' : $("#" + acctNoElmntID).val();
    var pKeyID = typeof $("#" + pKeyElemntID).val() === 'undefined' ? '-1' : $("#" + pKeyElemntID).val();
    if (typeof callBackFunc === 'undefined' || callBackFunc === null)
    {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
//alert("acctNo "+acctNo)
    var accMandate = ["--Please Select--", "All to sign", "Anyone to sign", "Any two to sign", "Any three to sign", "Any four to sign",
        "Both to sign"];
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
            $('#' + modalBodyID).html(xmlhttp.responseText);
            var p_statusTitle = $('#status').val();
            $('#' + titleElementID).html(formTitle);
            $('#' + elementID).off('hidden.bs.modal');
            $('#' + elementID).one('hidden.bs.modal', function (e) {
                if (vtypActn !== "VIEW") {
                    getCustData('', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.1');
                }
                $('#' + titleElementID).html('');
                $('#' + modalBodyID).html('');
                $(e.currentTarget).unbind();
            });
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
            if (!$.fn.DataTable.isDataTable('#' + listTableID)) {
                var table1 = $('#' + listTableID).DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#' + listTableID + ' tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        table1.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                });
                $('#' + listTableID + ' tbody').on('mouseenter', 'tr', function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        table1.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });
            }
            $body.removeClass("mdlloadingDiag");
            $('#' + elementID).modal({backdrop: 'static', keyboard: false});
            $body = $("body");
            $(document).ready(function () {
                $('#prpsOfAcct').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonWidth: '335px'
                });
                $('#acctTrnsTyp').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonWidth: '335px'
                });
                $('#srcOfFunds').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonWidth: '335px',
                    maxHeight: 250
                });
                if (pKeyID > 0) {
                    if (p_statusTitle == "Initiated") {
                        $("#withdrawCustAccountBtn").css("display", "inline-block");
                        $("#saveCustAccountBtn").css("display", "none");
                        $("#submitCustAccountBtn").css("display", "none");
                        $("#getSigntryBtn").css("display", "none");
                    } else if (p_statusTitle == "Incomplete" || p_statusTitle == "Rejected" || p_statusTitle == "Withdrawn"
                            || p_statusTitle == "Requires Reapproval") {
                        $("#withdrawCustAccountBtn").css("display", "none");
                        $("#saveCustAccountBtn").css("display", "inline-block");
                        $("#submitCustAccountBtn").css("display", "inline-block");
                        $("#getSigntryBtn").css("display", "block");
                    } else if (p_statusTitle == "Approved") {
                        $("#withdrawCustAccountBtn").css("display", "none");
                        $("#saveCustAccountBtn").css("display", "inline-block");
                        $("#submitCustAccountBtn").css("display", "none");
                        $("#getSigntryBtn").css("display", "block");
                    }
                }
                callBackFunc();
            });
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=17&typ=1&pg=" + pgNo + "&subPgNo=" + subPgNo + "&vtyp=" + vtyp + "&vtypActn=" + vtypActn + "&PKeyID=" + pKeyID + "&acctNo=" + acctNo);
}

function getCustAcNoInfoForm(elementID, modalBodyID, titleElementID, formTitle, pgNo, subPgNo, vtyp, vtypActn, pKeyID, listTableID, rowID, acctNo, acctNoElemntID, callBackFunc)
{
    if (typeof acctNoElemntID === 'undefined' || acctNoElemntID === null)
    {
        acctNoElemntID = 'acctNoFind';
    }
    $('#allOtherInputData99').val(0);
    if (acctNo === "" || acctNo === "undefined") {
        acctNo = $("#" + acctNoElemntID).val();
    }
    if (typeof callBackFunc === 'undefined' || callBackFunc === null)
    {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
//alert("acctNo "+acctNo)
    accMandate = ["--Please Select--", "All to sign", "Anyone to sign", "Any two to sign", "Any three to sign", "Any four to sign",
        "Both to sign"];
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
            $('#' + modalBodyID).html(xmlhttp.responseText);
            var p_statusTitle = $('#status').val();
            $('#' + titleElementID).html(formTitle); // + "<span style='color:red;font-weight: bold;float:right;width:50%;' id='statusTitle'>" + p_statusTitle + "</span>");
            $('#' + elementID).off('hidden.bs.modal');
            $('#' + elementID).one('hidden.bs.modal', function (e) {
//if (noReload <= 0) {
                if (vtypActn !== "VIEW") {
                    getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.1');
                }
//getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=' + subPgNo, fieldsPrfx);
//}
                $('#' + titleElementID).html('');
                $('#' + modalBodyID).html('');
                $(e.currentTarget).unbind();
            });
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
            if (!$.fn.DataTable.isDataTable('#' + listTableID)) {
                var table1 = $('#' + listTableID).DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#' + listTableID + ' tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        table1.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                });
                $('#' + listTableID + ' tbody').on('mouseenter', 'tr', function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        table1.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });
            }
            $body.removeClass("mdlloadingDiag");
            $('#' + elementID).modal({backdrop: 'static', keyboard: false});
            $body = $("body");
            $(document).ready(function () {
                /*$('#' + formElementID).submit(function (e) {
                 e.preventDefault();
                 return false;
                 });*/
                $('#prpsOfAcct').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonWidth: '335px'
                });
                $('#acctTrnsTyp').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonWidth: '335px'
                });
                $('#srcOfFunds').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonWidth: '335px',
                    maxHeight: 250
                });
                if (vtypActn !== "VIEW") {
//$('#acctTitle').val($('#bnkCustomer').val()); //UNCOMMENT IF NECESSARY
                }
                if (pKeyID > 0) {
                    if (p_statusTitle == "Initiated") {

                        $("#withdrawCustAccountBtn").css("display", "inline-block");
                        $("#saveCustAccountBtn").css("display", "none");
                        $("#submitCustAccountBtn").css("display", "none");
                        $("#getSigntryBtn").css("display", "none");
                    } else if (p_statusTitle == "Incomplete" || p_statusTitle == "Rejected" || p_statusTitle == "Withdrawn"
                            || p_statusTitle == "Requires Reapproval") {

                        $("#withdrawCustAccountBtn").css("display", "none");
                        $("#saveCustAccountBtn").css("display", "inline-block");
                        $("#submitCustAccountBtn").css("display", "inline-block");
                        $("#getSigntryBtn").css("display", "block");
                    } else if (p_statusTitle == "Approved") {

                        $("#withdrawCustAccountBtn").css("display", "none");
                        $("#saveCustAccountBtn").css("display", "inline-block");
                        $("#submitCustAccountBtn").css("display", "none");
                        $("#getSigntryBtn").css("display", "block");
                    }
                }

                callBackFunc();
            });
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=17&typ=1&pg=" + pgNo + "&subPgNo=" + subPgNo + "&vtyp=" + vtyp + "&vtypActn=" + vtypActn + "&PKeyID=" + pKeyID + "&acctNo=" + acctNo);
}