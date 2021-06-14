function prepareSelfAca(lnkArgs, htBody, targ, rspns) {
    $(targ).html(rspns);
    $(document).ready(function () {
        if (lnkArgs.indexOf("&typ=1&vtyp=10") !== -1 ||
            lnkArgs.indexOf("&typ=1&vtyp=11") !== -1) {
            if (!$.fn.DataTable.isDataTable('#reportCardsHdrsTable')) {
                var table1 = $('#reportCardsHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#reportCardsHdrsTable').wrap('<div class="table-responsive">');
            }
            $('#reportCardsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('#reportCrdHdrRmngLife1').val($('#reportCrdHdrRmngLife').val());
            var table1;
            if (!$.fn.DataTable.isDataTable('#reportCardsPMStpsTable')) {
                table1 = $('#reportCardsPMStpsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#reportCardsPMStpsTable').wrap('<div class="table-responsive">');
            }
            if (!$.fn.DataTable.isDataTable('#oneReportCardsExtrInfTable')) {
                var table2 = $('#oneReportCardsExtrInfTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneReportCardsExtrInfTable').wrap('<div class="table-responsive">');
            }
            $('#reportCrdHdrForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            if (!$.fn.DataTable.isDataTable('#oneReportCardTransLinesTable')) {
                var table2 = $('#oneReportCardTransLinesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneReportCardTransLinesTable').wrap('<div class="table-responsive">');
            }
            if (!$.fn.DataTable.isDataTable('#oneReportCardPMRecsTable')) {
                var table2 = $('#oneReportCardPMRecsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneReportCardPMRecsTable').wrap('<div class="table-responsive">');
            }
            /*$('[data-toggle="tabajxasShtdetls"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=15&typ=1' + dttrgt;
                $(targ + 'tab').tab('show');
                if (targ.indexOf('asShtDetlsTrans') >= 0) {
                    $("#reportCardHdnTabNm").val('asShtDetlsTrans');
                    if ($('#asShtDetlsTrans').text().trim().length <= 0) {
                        var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
                        var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();
                        return getReportCrdHdr('clear', '#asShtDetlsTrans', 'grp=15&typ=1&pg=1&vtyp=2' +
                            "&assessSbmtdSheetID=" + assessSbmtdSheetID +
                            "&assessSbmtdSheetNm=" + assessSbmtdSheetNm, assessSbmtdSheetID);
                    }
                } else if (targ.indexOf('asShtDetlsPMRecs') >= 0) {
                    $("#reportCardHdnTabNm").val('asShtDetlsPMRecs');
                    if ($('#asShtDetlsPMRecs').text().trim().length <= 0) {
                        var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
                        var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();
                        return getReportCrdHdr('clear', '#asShtDetlsPMRecs', 'grp=15&typ=1&pg=1&vtyp=3' +
                            "&assessSbmtdSheetID=" + assessSbmtdSheetID +
                            "&assessSbmtdSheetNm=" + assessSbmtdSheetNm, assessSbmtdSheetID);
                    }
                } else {
                    $("#reportCardHdnTabNm").val('');
                }
            });*/
            $('[data-toggle="tabajxreportcard"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=15&typ=1' + dttrgt;
                $(targ + 'tab').tab('show');
                $("#reportCardHdnTabNm").val('');
                if (targ.indexOf('reportCardDetList') >= 0) {
                    if ($('#reportCardDetList').text().trim().length <= 0) {
                        var assessSbmtdSheetID = typeof $("#reportCardID").val() === 'undefined' ? '-1' : $("#reportCardID").val();
                        var assessSbmtdSheetNm = typeof $("#reportCardNm").val() === 'undefined' ? '' : $("#reportCardNm").val();
                        return getReportCardDets('clear', '#reportCardDetList', 'grp=110&typ=1&vtyp=11' +
                            "&assessSbmtdSheetID=" + assessSbmtdSheetID +
                            "&assessSbmtdSheetNm=" + assessSbmtdSheetNm, assessSbmtdSheetID);
                    }
                }
            });
        } else if (lnkArgs.indexOf("&typ=1&vtyp=12") !== -1) {
            if (!$.fn.DataTable.isDataTable('#oneReportCardTransLinesTable')) {
                var table2 = $('#oneReportCardTransLinesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneReportCardTransLinesTable').wrap('<div class="table-responsive">');
            }
        } else if (lnkArgs.indexOf("&typ=1&vtyp=0") !== -1) {
            var table1;
            var table2;
            if (!$.fn.DataTable.isDataTable('#acaRgstratnTable')) {
                table1 = $('#acaRgstratnTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#acaRgstratnTable').wrap('<div class="table-responsive">');
            }
            if (!$.fn.DataTable.isDataTable('#oneAcaRgstratnCrsesTable')) {
                table2 = $('#oneAcaRgstratnCrsesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneAcaRgstratnCrsesTable').wrap('<div class="table-responsive">');
            }
            if (!$.fn.DataTable.isDataTable('#oneAcaRgstratnSbjctsTable')) {
                var table3 = $('#oneAcaRgstratnSbjctsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneAcaRgstratnSbjctsTable').wrap('<div class="table-responsive">');
            }
            $('#acaRgstratnForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            $('#oneAcaRgstratnCrsesTable tbody').off('click');
            $('#oneAcaRgstratnCrsesTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#oneAcaRgstratnCrsesRow' + rndmNum + '_LnID').val() === 'undefined' ? '-1' : $('#oneAcaRgstratnCrsesRow' + rndmNum + '_LnID').val();
                getOneAcaRgstratnSbjctsForm(pkeyID, 1);
            });

            $('#oneAcaRgstratnCrsesTable tbody')
                .off('mouseenter', 'tr');
            $('#oneAcaRgstratnCrsesTable tbody')
                .on('mouseenter', 'tr', function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        table2.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });

        }
        htBody.removeClass("mdlloading");
        $('.table').each(function (i, el) {
            var tblID = $(el).attr('id');
            if (!$.fn.DataTable.isDataTable('#' + tblID)) {
                $('#' + tblID).DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#' + tblID).wrap('<div class="table-responsive">');
            }
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
        $(".jbDetFuncRate").focus(function () {
            $(this).select();
        });
        $(".jbDetAccRate").focus(function () {
            $(this).select();
        });
        $(".jbDetColNo").focus(function () {
            $(this).select();
        });
        $(".assesScoreM").focus(function () {
            $(this).select();
        });
        $(".jbDetGPA").focus(function () {
            $(this).select();
        });
        $(".jbDetMin").focus(function () {
            $(this).select();
        });
        $(".jbDetMax").focus(function () {
            $(this).select();
        });
        $(".jbDetColNo").ForceNumericOnly();
        $(".assesScoreNum").ForceNumericOnly();
        $(".jbDetGPA").ForceNumericOnly();
        $(".jbDetMin").ForceNumericOnly();
        $(".jbDetMax").ForceNumericOnly();
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
    });
}


function getOneAcaRgstratnForm(tmpltID, vwtype, actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'PasteDirect';
    }
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = 'acaRgstratnDetailInfo';
    }
    if (typeof titleMsg === 'undefined' || titleMsg === null) {
        titleMsg = '';
    }
    if (typeof titleElementID === 'undefined' || titleElementID === null) {
        titleElementID = '';
    }
    if (typeof modalBodyID === 'undefined' || modalBodyID === null) {
        modalBodyID = '';
    }
    var qShwCrntOnly = $('#acaRgstratnShwCrntOnly:checked').length > 0;
    var acaRgstratnPrsID = typeof $("#acaRgstratnPrsID").val() === 'undefined' ? '-1' : $("#acaRgstratnPrsID").val();
    if (tmpltID > 0) {
        acaRgstratnPrsID = tmpltID;
    }
    var lnkArgs = 'grp=110&typ=1&vtyp=' + vwtype + '&sbmtdAcaSttngsPrsnID=' + acaRgstratnPrsID +
        '&actionTxt=' + actionTxt + '&destElmntID=' + destElmntID +
        '&titleMsg=' + titleMsg + '&titleElementID=' + titleElementID + '&modalBodyID=' + modalBodyID +
        '&qShwCrntOnly=' + qShwCrntOnly;

    doAjaxWthCallBck(lnkArgs, destElmntID, actionTxt, titleMsg, titleElementID, modalBodyID, function () {
        var table2 = null;
        if (!$.fn.DataTable.isDataTable('#oneAcaRgstratnCrsesTable')) {
            table2 = $('#oneAcaRgstratnCrsesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAcaRgstratnCrsesTable').wrap('<div class="table-responsive">');
        }
        if (!$.fn.DataTable.isDataTable('#oneAcaRgstratnSbjctsTable')) {
            var table3 = $('#oneAcaRgstratnSbjctsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAcaRgstratnSbjctsTable').wrap('<div class="table-responsive">');
        }
        $('#acaRgstratnForm').submit(function (e) {
            e.preventDefault();
            return false;
        });

        $('#oneAcaRgstratnCrsesTable tbody').off('click');
        $('#oneAcaRgstratnCrsesTable tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                table2.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
            var rndmNum = $(this).attr('id').split("_")[1];
            var pkeyID = typeof $('#oneAcaRgstratnCrsesRow' + rndmNum + '_LnID').val() === 'undefined' ? '-1' : $('#oneAcaRgstratnCrsesRow' + rndmNum + '_LnID').val();
            getOneAcaRgstratnSbjctsForm(pkeyID, 1);
        });

        $('#oneAcaRgstratnCrsesTable tbody')
            .off('mouseenter', 'tr');
        $('#oneAcaRgstratnCrsesTable tbody')
            .on('mouseenter', 'tr', function () {
                if ($(this).hasClass('highlight')) {
                    $(this).removeClass('highlight');
                } else {
                    table2.$('tr.highlight').removeClass('highlight');
                    $(this).addClass('highlight');
                }
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

function getOneAcaRgstratnSbjctsForm(tmpltID, vwtype, actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'PasteDirect';
    }
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = 'acaRgstratnSbjctsDetailInfo';
    }
    if (typeof titleMsg === 'undefined' || titleMsg === null) {
        titleMsg = '';
    }
    if (typeof titleElementID === 'undefined' || titleElementID === null) {
        titleElementID = '';
    }
    if (typeof modalBodyID === 'undefined' || modalBodyID === null) {
        modalBodyID = '';
    }
    var lnkArgs = 'grp=110&typ=1&vtyp=' + vwtype + '&sbmtdAcaSttngsID=' + tmpltID +
        '&actionTxt=' + actionTxt + '&destElmntID=' + destElmntID +
        '&titleMsg=' + titleMsg + '&titleElementID=' + titleElementID + '&modalBodyID=' + modalBodyID;
    //alert(lnkArgs);
    doAjaxWthCallBck(lnkArgs, destElmntID, actionTxt, titleMsg, titleElementID, modalBodyID, function () {
        if (!$.fn.DataTable.isDataTable('#oneAcaRgstratnSbjctsTable')) {
            var table3 = $('#oneAcaRgstratnSbjctsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAcaRgstratnSbjctsTable').wrap('<div class="table-responsive">');
        }
        $('#acaRgstratnForm').submit(function (e) {
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

function getAcaRgstratnClassForm(elementID, modalBodyID, titleElementID, formElementID,
    tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, sbmtdRgstrPersonID) {
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                $('#' + titleElementID).html(formTitle);
                $('#' + modalBodyID).html(xmlhttp.responseText);
                /*$('#' + modalBodyID + 'Diag').draggable();*/
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
                if (addOrEdit === 'EDIT') {
                    /*Get various field element IDs and populate values*/
                    var rndmNum = tRowElementID.split("_")[1];
                    var rowPrfxNm = tRowElementID.split("_")[0];
                    /*var $tds = $('#' + tRowElementID).find('td');*/
                    $('#acaRgstrClassPkeyID').val(pKeyID);
                    $('#sbmtdRgstrPersonID').val(sbmtdRgstrPersonID);
                    $('#acaRgstrClassID').val($('#' + rowPrfxNm + rndmNum + '_ClassID').val());
                    $('#acaRgstrCrseID').val($('#' + rowPrfxNm + rndmNum + '_CrseID').val());
                    $('#acaRgstrPrdID').val($('#' + rowPrfxNm + rndmNum + '_PrdID').val());
                    $('#acaRgstrClassNm').val($('#' + rowPrfxNm + rndmNum + '_ClassNm').val());
                    $('#acaRgstrCrseName').val($('#' + rowPrfxNm + rndmNum + '_CrseName').val());
                    $('#acaRgstrPrdName').val($('#' + rowPrfxNm + rndmNum + '_PrdNm').val());
                }
                $('#' + elementID).off('show.bs.modal');
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });

                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({
                    backdrop: 'static',
                    keyboard: false
                });
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
        xmlhttp.send("grp=110&typ=1&vtyp=" + vtyp + "&acaRgstrClassPkeyID=" + pKeyID +
            "&sbmtdRgstrPersonID=" + sbmtdRgstrPersonID + "&tRowElmntNm=" + tRowElementID);
    });
}

function saveAcaRgstratnClassForm(elementID, pKeyID, sbmtdRgstrPersonID, tRowElementID) {
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var acaRgstrClassID = typeof $("#acaRgstrClassID").val() === 'undefined' ? '-1' : $("#acaRgstrClassID").val();
        var acaRgstrCrseID = typeof $("#acaRgstrCrseID").val() === 'undefined' ? '-1' : $("#acaRgstrCrseID").val();
        var acaRgstrPrdID = typeof $("#acaRgstrPrdID").val() === 'undefined' ? '-1' : $("#acaRgstrPrdID").val();
        var acaRgstrClassNm = typeof $("#acaRgstrClassNm").val() === 'undefined' ? '' : $("#acaRgstrClassNm").val();
        var acaRgstrCrseName = typeof $("#acaRgstrCrseName").val() === 'undefined' ? '' : $("#acaRgstrCrseName").val();
        var acaRgstrPrdName = typeof $("#acaRgstrPrdName").val() === 'undefined' ? '' : $("#acaRgstrPrdName").val();
        var errMsg = "";
        if (Number(acaRgstrClassID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
            Number(acaRgstrCrseID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
            Number(acaRgstrPrdID.replace(/[^-?0-9\.]/g, '')) <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Assessment Group, Programme and Period cannot be empty!</span></p>';
        }
        if (rhotrim(errMsg, '; ') !== '') {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg
            });
            return false;
        }
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("RHO-ERROR") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText
                    });
                } else {
                    if (pKeyID <= 0) {
                        $('#oneAcaRgstratnCrsesTable').append(xmlhttp.responseText);
                    } else {
                        var rndmNum = tRowElementID.split("_")[1];
                        var rowPrfxNm = tRowElementID.split("_")[0];
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(acaRgstrClassNm);
                        $tds.eq(2).text(acaRgstrCrseName);
                        $tds.eq(3).text(acaRgstrPrdName);
                        $('#' + rowPrfxNm + rndmNum + '_ClassID').val(acaRgstrClassID);
                        $('#' + rowPrfxNm + rndmNum + '_CrseID').val(acaRgstrCrseID);
                        $('#' + rowPrfxNm + rndmNum + '_PrdID').val(acaRgstrPrdID);
                        $('#' + rowPrfxNm + rndmNum + '_ClassNm').val(acaRgstrClassNm);
                        $('#' + rowPrfxNm + rndmNum + '_CrseName').val(acaRgstrCrseName);
                        $('#' + rowPrfxNm + rndmNum + '_PrdNm').val(acaRgstrPrdName);
                    }
                    $('#' + elementID).modal('hide');
                    getOneAcaRgstratnForm(sbmtdRgstrPersonID, 0, 'PasteDirect', 'allmodules', '', '', '');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=110&typ=1&q=UPDATE&actyp=11" +
            "&acaRgstrClassID=" + acaRgstrClassID +
            "&acaRgstrCrseID=" + acaRgstrCrseID +
            "&acaRgstrPrdID=" + acaRgstrPrdID +
            "&acaRgstrClassNm=" + acaRgstrClassNm +
            "&acaRgstrCrseName=" + acaRgstrCrseName +
            "&acaRgstrPrdName=" + acaRgstrPrdName +
            "&acaRgstrClassPkeyID=" + pKeyID +
            "&sbmtdRgstrPersonID=" + sbmtdRgstrPersonID);
    });
}

function getAcaRgstratnSbjctForm(elementID, modalBodyID, titleElementID, formElementID,
    tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, acaRgstratnSttngsID) {
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                $('#' + titleElementID).html(formTitle);
                $('#' + modalBodyID).html(xmlhttp.responseText);
                /* $('#' + modalBodyID + 'Diag').draggable();*/
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
                if (addOrEdit === 'EDIT') {
                    /*Get various field element IDs and populate values*/
                    var rndmNum = tRowElementID.split("_")[1];
                    var rowPrfxNm = tRowElementID.split("_")[0];
                    /*var $tds = $('#' + tRowElementID).find('td');*/
                    $('#sttngsSbjctPkeyID').val(pKeyID);
                    $('#acaRgstratnSttngsID').val(acaRgstratnSttngsID);
                    $('#sttngsSbjctID').val($('#' + rowPrfxNm + rndmNum + '_SbjctID').val());
                    $('#sttngsSbjctName').val($('#' + rowPrfxNm + rndmNum + '_SbjctNm').val());
                }
                $('#' + elementID).off('show.bs.modal');
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });

                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({
                    backdrop: 'static',
                    keyboard: false
                });
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
        xmlhttp.send("grp=110&typ=1&vtyp=" + vtyp + "&crseSbjctPkeyID=" + pKeyID +
            "&acaRgstratnSttngsID=" + acaRgstratnSttngsID + "&tRowElmntNm=" + tRowElementID);
    });
}

function saveAcaRgstratnSbjctForm(elementID, pKeyID, acaRgstratnSttngsID, tRowElementID) {
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var sttngsSbjctID = typeof $("#sttngsSbjctID").val() === 'undefined' ? '-1' : $("#sttngsSbjctID").val();
        var sttngsSbjctName = typeof $("#sttngsSbjctName").val() === 'undefined' ? '' : $("#sttngsSbjctName").val();
        var errMsg = "";
        if (sttngsSbjctName.trim().length <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Task/Subject cannot be empty!</span></p>';
        }
        if (rhotrim(errMsg, '; ') !== '') {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg
            });
            return false;
        }
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("RHO-ERROR") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText
                    });
                } else {
                    if (pKeyID <= 0) {
                        $('#oneAcaRgstratnSbjctsTable').append(xmlhttp.responseText);
                    } else {
                        var rndmNum = tRowElementID.split("_")[1];
                        var rowPrfxNm = tRowElementID.split("_")[0];
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(sttngsSbjctName);
                        $('#' + rowPrfxNm + rndmNum + '_SbjctID').val(sttngsSbjctID);
                        $('#' + rowPrfxNm + rndmNum + '_SbjctNm').val(sttngsSbjctName);
                    }
                    $('#' + elementID).modal('hide');
                    //getOneAcaRgstratnForm(sbmtdRgstrPersonID, 1, 'PasteDirect', 'acaRgstratnDetailInfo', '', '', '');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=110&typ=1&q=UPDATE&actyp=12" +
            "&sttngsSbjctID=" + sttngsSbjctID +
            "&sttngsSbjctName=" + sttngsSbjctName +
            "&sttngsSbjctPkeyID=" + pKeyID +
            "&acaRgstratnSttngsID=" + acaRgstratnSttngsID);
    });
}

function delAcaRgstratnLne(rowIDAttrb) {
    var msgPart = "Course/Objective";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_ClassNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 110,
                                    typ: 1,
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
                                error: function (jqXHR1, textStatus1, errorThrown1) {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else {
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

function delAcaRgstratnSbjcts(rowIDAttrb) {
    var msgPart = "Subject/Task";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SbjctLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SbjctLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SbjctNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 110,
                                    typ: 1,
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
                                error: function (jqXHR1, textStatus1, errorThrown1) {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else {
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


//REPORT CARDS
function getReportCards(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#reportCardsSrchFor").val() === 'undefined' ? '%' : $("#reportCardsSrchFor").val();
    var srchIn = typeof $("#reportCardsSrchIn").val() === 'undefined' ? 'Both' : $("#reportCardsSrchIn").val();
    var pageNo = typeof $("#reportCardsPageNo").val() === 'undefined' ? 1 : $("#reportCardsPageNo").val();
    var limitSze = typeof $("#reportCardsDsplySze").val() === 'undefined' ? 10 : $("#reportCardsDsplySze").val();
    var sortBy = typeof $("#reportCardsSortBy").val() === 'undefined' ? '' : $("#reportCardsSortBy").val();
    var qShwUsrOnly = $('#reportCardsShwUsrOnly:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwSelfOnly=" + qShwUsrOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncReportCards(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getReportCards(actionText, slctr, linkArgs);
    }
}

function getReportCardDets(actionText, slctr, linkArgs, reportCardID) {
    if (typeof reportCardID === 'undefined' || reportCardID === null) {
        reportCardID = -1;
    }
    var tstreportCardID = "a" + reportCardID;
    if (Number(tstreportCardID.replace(/[^-?0-9\.]/g, '')) > 0) {
        $("#assessSbmtdSheetID").val(reportCardID);
        assessSbmtdSheetID = reportCardID;
    } else {
        var reportCardID = typeof $("#reportCardID").val() === 'undefined' ? '-1' : $("#reportCardID").val();
        var reportCardNm = typeof $("#reportCardNm").val() === 'undefined' ? '' : $("#reportCardNm").val();
        var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
        var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();
        if (Number(reportCardID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
            (Number(reportCardID.replace(/[^-?0-9\.]/g, '')) !== Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) &&
                Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) > 0)) {
            $("#reportCardID").val(assessSbmtdSheetID);
            $("#reportCardNm").val(assessSbmtdSheetNm);
            reportCardID = assessSbmtdSheetID;
            reportCardNm = assessSbmtdSheetNm;
        }
        if (Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
            (Number(reportCardID.replace(/[^-?0-9\.]/g, '')) !== Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) &&
                Number(reportCardID.replace(/[^-?0-9\.]/g, '')) > 0)) {
            $("#assessSbmtdSheetID").val(reportCardID);
            $("#assessSbmtdSheetNm").val(reportCardNm);
            assessSbmtdSheetID = reportCardID;
            assessSbmtdSheetNm = reportCardNm;
        }
        if (linkArgs.indexOf("&assessSbmtdSheetNm=") === -1) {
            linkArgs = linkArgs + "&assessSbmtdSheetID=" + assessSbmtdSheetID +
                "&assessSbmtdSheetNm=" + assessSbmtdSheetNm;
        }
    }
    var reportCardHdnTabNm = typeof $("#reportCardHdnTabNm").val() === 'undefined' ? '' : $("#reportCardHdnTabNm").val();
    if (reportCardHdnTabNm === 'asShtDetlsTrans') {
        getReportCrdHdr('', '#asShtDetlsTrans', 'grp=15&typ=1&pg=1&vtyp=2');
    } else if (reportCardHdnTabNm === 'asShtDetlsPMRecs') {
        getReportCrdHdr('', '#asShtDetlsPMRecs', 'grp=15&typ=1&pg=1&vtyp=3');
    } else {
        openATab(slctr, linkArgs);
    }
}

function enterKeyFuncReportCardDets(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getReportCardDets(actionText, slctr, linkArgs);
    }
}

function getReportCrdHdr(actionText, slctr, linkArgs, reportCardID) {
    if (typeof reportCardID === 'undefined' || reportCardID === null) {
        reportCardID = -1;
    }
    var srchFor = typeof $("#reportCrdHdrSrchFor").val() === 'undefined' ? '%' : $("#reportCrdHdrSrchFor").val();
    var srchIn = typeof $("#reportCrdHdrSrchIn").val() === 'undefined' ? 'Both' : $("#reportCrdHdrSrchIn").val();
    var pageNo = typeof $("#reportCrdHdrPageNo").val() === 'undefined' ? 1 : $("#reportCrdHdrPageNo").val();
    var limitSze = typeof $("#reportCrdHdrDsplySze").val() === 'undefined' ? 15 : $("#reportCrdHdrDsplySze").val();
    var sortBy = typeof $("#reportCrdHdrSortBy").val() === 'undefined' ? '' : $("#reportCrdHdrSortBy").val();
    var qShwUsrOnly = $('#reportCrdHdrShwUsrOnly:checked').length > 0;
    var qShwNonZeroOnly = $('#reportCrdHdrNonZeroOnly:checked').length > 0;
    var reportCardID1 = typeof $("#reportCardID").val() === 'undefined' ? '-1' : $("#reportCardID").val();
    var reportCardNm1 = typeof $("#reportCardNm").val() === 'undefined' ? '' : $("#reportCardNm").val();
    var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
    var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();
    if (Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        assessSbmtdSheetID = reportCardID1;
        assessSbmtdSheetNm = reportCardNm1;
    }
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwNonZeroOnly=" + qShwNonZeroOnly;
    if (reportCardID <= 0) {
        linkArgs = linkArgs + "&assessSbmtdSheetID=" + assessSbmtdSheetID +
            "&assessSbmtdSheetNm=" + assessSbmtdSheetNm;
    }
    $('#myFormsModalLg').modal('hide');
    openATab(slctr, linkArgs);
}

function enterKeyFuncReportCrdHdr(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getReportCrdHdr(actionText, slctr, linkArgs);
    }
}


function printEmailFullTermRpt(pKeyID) {
    var dialog = bootbox.alert({
        title: 'GET PDF',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Getting PDF...Please Wait...</p>',
        callback: function () {}
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 1);
    formData.append('q', 'VIEW');
    formData.append('vtyp', 701);
    formData.append('pKeyID', pKeyID);
    var dwnldURL = "";
    var mailTo = "";
    var mailCc = "";
    var mailSubject = "";
    var bulkMessageBody = "";
    var mailAttchmnts = "";
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: '../index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        console.log('RPT:::',data);
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            dialog.modal('hide');

                            var dsplyForm = '<div id="allRhoPDFDocDisplays"></div>';

                            BootstrapDialog.show({
                                size: BootstrapDialog.SIZE_WIDE,
                                type: BootstrapDialog.TYPE_DEFAULT,
                                title: 'Popup Display!',
                                message: dsplyForm,
                                animate: true,
                                closable: true,
                                closeByBackdrop: false,
                                closeByKeyboard: false,
                                onshow: function (dialogItself) {
                                    popDialogItself = dialogItself;
                                    popOKBtnSsn = dialogItself.getButton('popOKBtnSsn');
                                },
                                onshown: function (dialogItself) {
                                    popOKBtnSsn = dialogItself.getButton('popOKBtnSsn');
                                    dwnldURL = urldecode(data.URL);
                                    mailTo = data.mailTo;
                                    mailCc = data.mailCc;
                                    mailSubject = data.mailSubject;
                                    bulkMessageBody = data.bulkMessageBody;
                                    mailAttchmnts = dwnldURL + ";";
                                    var options = {
                                        height: "550px"
                                        /*,
                                                                                 page: '2',
                                                                                 pdfOpenParams: {
                                                                                 view: 'FitV',
                                                                                 pagemode: 'thumbs',
                                                                                 search: 'lorem ipsum'
                                                                                 }*/
                                    };
                                    PDFObject.embed(dwnldURL, "#allRhoPDFDocDisplays", options);
                                },
                                buttons: [{
                                        label: 'CLOSE',
                                        icon: 'glyphicon glyphicon-menu-left',
                                        cssClass: 'btn-default',
                                        action: function (dialogItself) {
                                            var $button = this;
                                            dialogItself.setClosable(true);
                                            dialogItself.close();
                                        }
                                    }, {
                                        id: 'popOKBtnSsn',
                                        label: 'RELOAD PDF',
                                        icon: 'glyphicon glyphicon glyphicon-refresh',
                                        cssClass: 'btn-default',
                                        action: function (dialogItself) {
                                            popDialogItself = dialogItself;

                                            var options = {
                                                height: "550px"
                                                /*,
                                                                                                 page: '2',
                                                                                                 pdfOpenParams: {
                                                                                                 view: 'FitV',
                                                                                                 pagemode: 'thumbs',
                                                                                                 search: 'lorem ipsum'
                                                                                                 }*/
                                            };
                                            PDFObject.embed(dwnldURL, "#allRhoPDFDocDisplays", options);
                                        }
                                    }
                                    /*, {
                                                                         id: 'popOKBtnHtml',
                                                                         label: 'VIEW HTML',
                                                                         icon: 'glyphicon glyphicon-list-alt',
                                                                         cssClass: 'btn-primary',
                                                                         action: function (dialogItself) {
                                                                         popDialogItself = dialogItself;
                                                                         window.open(dwnldURL.replace(/(.pdf)/gi, ".html"), '_blank');
                                                                         }
                                                                         }
                                    , {
                                        id: 'popOKBtnEmail',
                                        label: 'SEND MAIL',
                                        icon: 'glyphicon glyphicon-envelope',
                                        cssClass: 'btn-primary',
                                        action: function (dialogItself) {
                                            popDialogItself = dialogItself;
                                            sendGeneralMessage1('Email', mailTo, mailCc, mailSubject, bulkMessageBody, mailAttchmnts);
                                            window.open(dwnldURL.replace(/(.pdf)/gi, ".html"), '_blank');
                                            dialogItself.setClosable(true);
                                            dialogItself.close();
                                        }
                                    }*/
                                ]
                            });
                        }
                    }, 50);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function printEmailFullRgstrSlp(pKeyID, sbmtdAcaSttngsID) {
    if (typeof sbmtdAcaSttngsID === 'undefined' || sbmtdAcaSttngsID === null) {
        sbmtdAcaSttngsID = -1;
    }
    var dialog = bootbox.alert({
        title: 'GET PDF',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Getting PDF...Please Wait...</p>',
        callback: function () {}
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 3);
    formData.append('q', 'VIEW');
    formData.append('vtyp', 701);
    formData.append('pKeyID', pKeyID);
    formData.append('sbmtdAcaSttngsID', sbmtdAcaSttngsID);
    var dwnldURL = "";
    var mailTo = "";
    var mailCc = "";
    var mailSubject = "";
    var bulkMessageBody = "";
    var mailAttchmnts = "";
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: '../index.php',
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
                            dialog.modal('hide');

                            var dsplyForm = '<div id="allRhoPDFDocDisplays"></div>';

                            BootstrapDialog.show({
                                size: BootstrapDialog.SIZE_WIDE,
                                type: BootstrapDialog.TYPE_DEFAULT,
                                title: 'Popup Display!',
                                message: dsplyForm,
                                animate: true,
                                closable: true,
                                closeByBackdrop: false,
                                closeByKeyboard: false,
                                onshow: function (dialogItself) {
                                    popDialogItself = dialogItself;
                                    popOKBtnSsn = dialogItself.getButton('popOKBtnSsn');
                                },
                                onshown: function (dialogItself) {
                                    popOKBtnSsn = dialogItself.getButton('popOKBtnSsn');
                                    dwnldURL = urldecode(data.URL);
                                    mailTo = data.mailTo;
                                    mailCc = data.mailCc;
                                    mailSubject = data.mailSubject;
                                    bulkMessageBody = data.bulkMessageBody;
                                    mailAttchmnts = dwnldURL + ";";
                                    var options = {
                                        height: "550px"
                                        /*,
                                                                                 page: '2',
                                                                                 pdfOpenParams: {
                                                                                 view: 'FitV',
                                                                                 pagemode: 'thumbs',
                                                                                 search: 'lorem ipsum'
                                                                                 }*/
                                    };
                                    PDFObject.embed(dwnldURL, "#allRhoPDFDocDisplays", options);
                                },
                                buttons: [{
                                        label: 'CLOSE',
                                        icon: 'glyphicon glyphicon-menu-left',
                                        cssClass: 'btn-default',
                                        action: function (dialogItself) {
                                            var $button = this;
                                            dialogItself.setClosable(true);
                                            dialogItself.close();
                                        }
                                    }, {
                                        id: 'popOKBtnSsn',
                                        label: 'RELOAD PDF',
                                        icon: 'glyphicon glyphicon glyphicon-refresh',
                                        cssClass: 'btn-default',
                                        action: function (dialogItself) {
                                            popDialogItself = dialogItself;

                                            var options = {
                                                height: "550px"
                                                /*,
                                                                                                 page: '2',
                                                                                                 pdfOpenParams: {
                                                                                                 view: 'FitV',
                                                                                                 pagemode: 'thumbs',
                                                                                                 search: 'lorem ipsum'
                                                                                                 }*/
                                            };
                                            PDFObject.embed(dwnldURL, "#allRhoPDFDocDisplays", options);
                                        }
                                    }
                                    /*, {
                                                                         id: 'popOKBtnHtml',
                                                                         label: 'VIEW HTML',
                                                                         icon: 'glyphicon glyphicon-list-alt',
                                                                         cssClass: 'btn-primary',
                                                                         action: function (dialogItself) {
                                                                         popDialogItself = dialogItself;
                                                                         window.open(dwnldURL.replace(/(.pdf)/gi, ".html"), '_blank');
                                                                         }
                                                                         }
                                    , {
                                        id: 'popOKBtnEmail',
                                        label: 'SEND MAIL',
                                        icon: 'glyphicon glyphicon-envelope',
                                        cssClass: 'btn-primary',
                                        action: function (dialogItself) {
                                            popDialogItself = dialogItself;
                                            sendGeneralMessage1('Email', mailTo, mailCc, mailSubject, bulkMessageBody, mailAttchmnts);
                                            window.open(dwnldURL.replace(/(.pdf)/gi, ".html"), '_blank');
                                            dialogItself.setClosable(true);
                                            dialogItself.close();
                                        }
                                    }*/
                                ]
                            });
                        }
                    }, 50);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}