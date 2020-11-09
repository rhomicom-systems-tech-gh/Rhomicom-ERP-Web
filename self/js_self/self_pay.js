function prepareSelfPay(lnkArgs, htBody, targ, rspns) {
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&typ=1&vtyp=0") !== -1) {
            var table1;
            if (!$.fn.DataTable.isDataTable('#myPayRnsTable')) {
                table1 = $('#myPayRnsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#myPayRnsTable').wrap('<div class="table-responsive">');
            }
            $('#myPayRnsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable('#myPayRnLinesTable')) {
                var table2 = $('#myPayRnLinesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#myPayRnLinesTable').wrap('<div class="table-responsive">');
            }
            $('#myPayRnsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#myPayRnsRow' + rndmNum + '_PyReqID').val() === 'undefined' ? '%' : $('#myPayRnsRow' + rndmNum + '_PyReqID').val();
                var pkeyID1 = typeof $('#myPayRnsRow' + rndmNum + '_MspyID').val() === 'undefined' ? '%' : $('#myPayRnsRow' + rndmNum + '_MspyID').val();
                getOneMyPyRnsForm(pkeyID, pkeyID1, 1);
            });
            $('#myPayRnsTable tbody')
                .on('mouseenter', 'tr', function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        table1.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });
        } else if (lnkArgs.indexOf("&typ=1&vtyp=2") !== -1) {
            var table1 = $('#myRcvblInvcsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myRcvblInvcsTable').wrap('<div class="table-responsive">');
            $('#myRcvblInvcsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            var table2 = $('#myRcvblInvcLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myRcvblInvcLinesTable').wrap('<div class="table-responsive">');

            var table3 = $('#myRcvblInvcSmryTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myRcvblInvcSmryTable').wrap('<div class="table-responsive">');
            $('#myRcvblInvcsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#myRcvblInvcsRow' + rndmNum + '_RcvblID').val() === 'undefined' ? '%' : $('#myRcvblInvcsRow' + rndmNum + '_RcvblID').val();
                var pkeyID1 = typeof $('#myRcvblInvcsRow' + rndmNum + '_SalesID').val() === 'undefined' ? '%' : $('#myRcvblInvcsRow' + rndmNum + '_SalesID').val();
                getOneMyRcvblInvcsForm(pkeyID, pkeyID1, 3);
            });
            $('#myRcvblInvcsTable tbody')
                .on('mouseenter', 'tr', function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        table1.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });
        } else if (lnkArgs.indexOf("&typ=1&vtyp=4") !== -1) {
            var table1 = $('#myPyblInvcsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myPyblInvcsTable').wrap('<div class="table-responsive">');
            $('#myPyblInvcsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            var table2 = $('#myPyblInvcLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myPyblInvcLinesTable').wrap('<div class="table-responsive">');

            var table3 = $('#myPyblInvcSmryTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myPyblInvcSmryTable').wrap('<div class="table-responsive">');
            $('#myPyblInvcsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#myPyblInvcsRow' + rndmNum + '_PyblID').val() === 'undefined' ? '%' : $('#myPyblInvcsRow' + rndmNum + '_PyblID').val();
                getOneMyPyblInvcsForm(pkeyID, 5);
            });
            $('#myPyblInvcsTable tbody')
                .on('mouseenter', 'tr', function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        table1.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });
        } else if (lnkArgs.indexOf("&vtyp=10") !== -1 || lnkArgs.indexOf("&vtyp=12") !== -1 || lnkArgs.indexOf("&vtyp=1101") !== -1) {
            if (!$.fn.DataTable.isDataTable('#payTrnsRqstsHdrsTable')) {
                var table1 = $('#payTrnsRqstsHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#payTrnsRqstsHdrsTable').wrap('<div class="table-responsive">');
            }
            $('#payTrnsRqstsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&vtyp=11") !== -1 || lnkArgs.indexOf("&vtyp=1102") !== -1) {
            if (!$.fn.DataTable.isDataTable('#allPayRnTrnsHdrsTable')) {
                var table1 = $('#allPayRnTrnsHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allPayRnTrnsHdrsTable').wrap('<div class="table-responsive">');
            }
            $('#payItemBalsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable('#payTrnsRqstsHdrsTable')) {
                var table1 = $('#payTrnsRqstsHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#payTrnsRqstsHdrsTable').wrap('<div class="table-responsive">');
            }
            $('#payTrnsRqstsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
        $('.rho-DatePicker').datetimepicker({
            format: 'DD-MMM-YYYY'
        });
        htBody.removeClass("mdlloading");
    });
}

function getMyPayRns(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#myPayRnsSrchFor").val() === 'undefined' ? '%' : $("#myPayRnsSrchFor").val();
    var srchIn = typeof $("#myPayRnsSrchIn").val() === 'undefined' ? 'Both' : $("#myPayRnsSrchIn").val();
    var pageNo = typeof $("#myPayRnsPageNo").val() === 'undefined' ? 1 : $("#myPayRnsPageNo").val();
    var limitSze = typeof $("#myPayRnsDsplySze").val() === 'undefined' ? 10 : $("#myPayRnsDsplySze").val();
    var sortBy = typeof $("#myPayRnsSortBy").val() === 'undefined' ? '' : $("#myPayRnsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncMyPayRns(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getMyPayRns(actionText, slctr, linkArgs);
    }
}

function getOneMyPyRnsForm(pKeyID, pKeyID1, vwtype) {
    var lnkArgs = 'grp=80&typ=1&vtyp=' + vwtype + '&sbmtdPyReqID=' + pKeyID + '&sbmtdMspyID=' + pKeyID1;
    doAjaxWthCallBck(lnkArgs, 'myPayRnsDetailInfo', 'PasteDirect', '', '', '', function () {
        if (!$.fn.DataTable.isDataTable('#myPayRnLinesTable')) {
            var table1 = $('#myPayRnLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myPayRnLinesTable').wrap('<div class="table-responsive">');
        }
        $("#myPyRnTotal1").html(urldecode($("#myPyRnTotal2").val()));
    });

}

function saveMyPayRnyForm() {
    var a = 1;
}

function getMyRcvblInvcs(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#myRcvblInvcsSrchFor").val() === 'undefined' ? '%' : $("#myRcvblInvcsSrchFor").val();
    var srchIn = typeof $("#myRcvblInvcsSrchIn").val() === 'undefined' ? 'Both' : $("#myRcvblInvcsSrchIn").val();
    var pageNo = typeof $("#myRcvblInvcsPageNo").val() === 'undefined' ? 1 : $("#myRcvblInvcsPageNo").val();
    var limitSze = typeof $("#myRcvblInvcsDsplySze").val() === 'undefined' ? 10 : $("#myRcvblInvcsDsplySze").val();
    var sortBy = typeof $("#myRcvblInvcsSortBy").val() === 'undefined' ? '' : $("#myRcvblInvcsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncMyRcvblInvcs(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getMyRcvblInvcs(actionText, slctr, linkArgs);
    }
}

function getOneMyRcvblInvcsForm(pKeyID, pKeyID1, vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdRcvblInvcID=' + pKeyID + '&sbmtdSalesInvcID=' + pKeyID1;
    doAjaxWthCallBck(lnkArgs, 'myRcvblInvcsDetailInfo', 'PasteDirect', '', '', '', function () {
        var table1 = $('#myRcvblInvcLinesTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#myRcvblInvcLinesTable').wrap('<div class="table-responsive">');

        var table3 = $('#myRcvblInvcSmryTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#myRcvblInvcSmryTable').wrap('<div class="table-responsive">');
    });

}

function getMyPyblInvcs(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#myPyblInvcsSrchFor").val() === 'undefined' ? '%' : $("#myPyblInvcsSrchFor").val();
    var srchIn = typeof $("#myPyblInvcsSrchIn").val() === 'undefined' ? 'Both' : $("#myPyblInvcsSrchIn").val();
    var pageNo = typeof $("#myPyblInvcsPageNo").val() === 'undefined' ? 1 : $("#myPyblInvcsPageNo").val();
    var limitSze = typeof $("#myPyblInvcsDsplySze").val() === 'undefined' ? 10 : $("#myPyblInvcsDsplySze").val();
    var sortBy = typeof $("#myPyblInvcsSortBy").val() === 'undefined' ? '' : $("#myPyblInvcsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncMyPyblInvcs(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getMyPyblInvcs(actionText, slctr, linkArgs);
    }
}

function getOneMyPyblInvcsForm(pKeyID, vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=3&vtyp=' + vwtype + '&sbmtdPyblInvcID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myPyblInvcsDetailInfo', 'PasteDirect', '', '', '', function () {
        var table1 = $('#myPyblInvcLinesTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#myPyblInvcLinesTable').wrap('<div class="table-responsive">');

        var table3 = $('#myPyblInvcSmryTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#myPyblInvcSmryTable').wrap('<div class="table-responsive">');
    });
}


//Loan Requests
function getPayTrnsRqsts(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#payTrnsRqstsSrchFor").val() === 'undefined' ? '%' : $("#payTrnsRqstsSrchFor").val();
    var srchIn = typeof $("#payTrnsRqstsSrchIn").val() === 'undefined' ? 'Both' : $("#payTrnsRqstsSrchIn").val();
    var pageNo = typeof $("#payTrnsRqstsPageNo").val() === 'undefined' ? 1 : $("#payTrnsRqstsPageNo").val();
    var limitSze = typeof $("#payTrnsRqstsDsplySze").val() === 'undefined' ? 10 : $("#payTrnsRqstsDsplySze").val();
    var sortBy = typeof $("#payTrnsRqstsSortBy").val() === 'undefined' ? '' : $("#payTrnsRqstsSortBy").val();
    var qShwUsrOnly = $('#payTrnsRqstsShwUsrOnly:checked').length > 0;
    var qShwUnpstdOnly = $('#payTrnsRqstsShwUnpstdOnly:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'vious') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwUsrOnly=" + qShwUsrOnly + "&qShwUnpstdOnly=" + qShwUnpstdOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncPayTrnsRqsts(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getPayTrnsRqsts(actionText, slctr, linkArgs);
    }
}

function getOnePayTrnsRqstsForm(pKeyID, vwtype, actionTxt, payTrnsRqstsType) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof payTrnsRqstsType === 'undefined' || payTrnsRqstsType === null) {
        payTrnsRqstsType = 'LOAN';
    }
    var lnkArgs = 'grp=80&typ=1&vtyp=' + vwtype + '&sbmtdPayTrnsRqstsID=' + pKeyID + '&payTrnsRqstsType=' + payTrnsRqstsType;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalxLG', actionTxt, 'Transaction Request Details (ID:' + pKeyID + ')', 'myFormsModalxLGTitle', 'myFormsModalxLGBody', function () {
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
        }).on('hide', function (ev) {
            $('#myFormsModalxLG').css("overflow", "auto");
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
            $('#myFormsModalxLG').css("overflow", "auto");
        });
        $('#allOtherInputData99').val('0');
        $('#onePayTrnsRqstsEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalxLG').off('hidden.bs.modal');
        $('#myFormsModalxLG').one('hidden.bs.modal', function (e) {
            if (payTrnsRqstsType === 'LOAN') {
                getPayTrnsRqsts('', '#allmodules', 'grp=80&typ=1&vtyp=10');
            } else if (payTrnsRqstsType === 'PAYMENT') {
                getPayTrnsRqsts('', '#payPymntTrans', 'grp=80&typ=1&vtyp=1101');
            } else {
                getPayTrnsRqsts('', '#allmodules', 'grp=80&typ=1&vtyp=12');
            }
            $(e.currentTarget).unbind();
        });
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
        shwHidePayTrnsFlds();
    });
}

function shwHidePayTrnsFlds() {
    var payTrnsRqstsType = typeof $("#payTrnsRqstsType").val() === 'undefined' ? 'LOAN' : $("#payTrnsRqstsType").val();
    var sbmtdPayTrnsAppCODE = typeof $("#sbmtdPayTrnsAppCODE").val() === 'undefined' ? 'RHOMICOM_MAIN_ERP_APP_1' : $("#sbmtdPayTrnsAppCODE").val();
    var payTrnsRqstsItmTypID = typeof $("#payTrnsRqstsItmTypID").val() === 'undefined' ? '-1' : $("#payTrnsRqstsItmTypID").val();
    var payTrnsRqstsItmTyp = typeof $("#payTrnsRqstsItmTypID").val() === 'undefined' ? '' : $("#payTrnsRqstsItmTypID").text();
    var payTrnsRqstsClsfctn = typeof $("#payTrnsRqstsClsfctn").val() === 'undefined' ? '' : $("#payTrnsRqstsClsfctn").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp', 80);
        formData.append('typ', 1);
        formData.append('q', 'VIEW');
        formData.append('vtyp', 104);
        formData.append('payTrnsRqstsItmTypID', payTrnsRqstsItmTypID);
        formData.append('payTrnsRqstsType', payTrnsRqstsType);
        formData.append('payTrnsRqstsClsfctn', payTrnsRqstsClsfctn);
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
                    var options = data.FilterOptions.split(";");
                    $("#payTrnsRqstsClsfctn").empty();
                    for (var i = 0; i < options.length; i++) {
                        var defaultSlctd = false;
                        var isSlctd = false;
                        if (payTrnsRqstsClsfctn === options[i]) {
                            defaultSlctd = true;
                            isSlctd = true;
                        }
                        var o = new Option(options[i], options[i], defaultSlctd, isSlctd);
                        /// jquerify the DOM object 'o' so we can use the html method
                        $(o).html(options[i]);
                        $("#payTrnsRqstsClsfctn").append(o);
                    }
                }
                if (payTrnsRqstsType === 'SETTLEMENT') {
                    shwHidePayPrevLoans();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function shwHidePayPrevLoans() {
    var myPayTrnsRqstsStatusBtn = typeof $("#myPayTrnsRqstsStatusBtn").text() === 'undefined' ? '' : $("#myPayTrnsRqstsStatusBtn").text();
    var payTrnsRqstsType = typeof $("#payTrnsRqstsType").val() === 'undefined' ? 'LOAN' : $("#payTrnsRqstsType").val();
    var sbmtdPayTrnsAppCODE = typeof $("#sbmtdPayTrnsAppCODE").val() === 'undefined' ? 'RHOMICOM_MAIN_ERP_APP_1' : $("#sbmtdPayTrnsAppCODE").val();
    var payTrnsRqstsItmTypID = typeof $("#payTrnsRqstsItmTypID").val() === 'undefined' ? '-1' : $("#payTrnsRqstsItmTypID").val();
    var payTrnsRqstsItmTyp = typeof $("#payTrnsRqstsItmTypID").val() === 'undefined' ? '' : $("#payTrnsRqstsItmTypID").text();
    var lnkdPayTrnsRqstsID = typeof $("#lnkdPayTrnsRqstsID").val() === 'undefined' ? '-1' : $("#lnkdPayTrnsRqstsID").val();
    var payTrnsRqstsPrsnID = typeof $("#payTrnsRqstsPrsnID").val() === 'undefined' ? '-1' : $("#payTrnsRqstsPrsnID").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp', 80);
        formData.append('typ', 1);
        formData.append('q', 'VIEW');
        formData.append('vtyp', 105);
        formData.append('payTrnsRqstsItmTypID', payTrnsRqstsItmTypID);
        formData.append('payTrnsRqstsType', payTrnsRqstsType);
        formData.append('lnkdPayTrnsRqstsID', lnkdPayTrnsRqstsID);
        formData.append('payTrnsRqstsPrsnID', payTrnsRqstsPrsnID);
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
                    var options = data.FilterOptions.split(";");
                    $("#lnkdPayTrnsRqstsID").empty();
                    for (var i = 0; i < options.length; i++) {
                        var defaultSlctd = false;
                        var isSlctd = false;
                        var nwOptions = options[i].split("|");
                        if (lnkdPayTrnsRqstsID === nwOptions[0]) {
                            defaultSlctd = true;
                            isSlctd = true;
                        }
                        var o = new Option(nwOptions[0], nwOptions[0], defaultSlctd, isSlctd);
                        /// jquerify the DOM object 'o' so we can use the html method
                        $(o).html(nwOptions[1]);
                        $("#lnkdPayTrnsRqstsID").append(o);
                    }
                    var payTrnsRqstsAmnt = typeof $("#payTrnsRqstsAmnt").val() === 'undefined' ? '0' : $("#payTrnsRqstsAmnt").val();
                    if (Number(payTrnsRqstsAmnt.replace(/[^-?0-9\.]/g, '')) <= 0 ||
                        myPayTrnsRqstsStatusBtn.indexOf('Not Submitted') !== -1) {
                        var outDefaultAmnt = data.DefaultAmnt;
                        $("#payTrnsRqstsAmnt").val(outDefaultAmnt);
                        payTrnsRqstsAmnt = fmtAsNumber('payTrnsRqstsAmnt');
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function getOnePayTrnsRqstsDocsForm(pKeyID, vwtype) {
    var lnkArgs = 'grp=80&typ=1&vtyp=' + vwtype + '&sbmtdPayTrnsRqstsID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', 'Request Attached Documents', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        var table1 = $('#attchdTrnsRqstsDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdTrnsRqstsDocsTable').wrap('<div class="table-responsive">');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdTrnsRqstsDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function uploadFileToTrnsRqstsDocs(inptElmntID, attchIDElmntID, docNmElmntID, sbmtdHdrID, rowIDAttrb) {
    var docCtrgrName = $('#' + docNmElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Doc. Name/Description cannot be empty!</span></p>';
    }
    if (sbmtdHdrID <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Attachments must be done on a saved Document/Transaction!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    $("#" + inptElmntID).val('');
    $("#" + inptElmntID).off('change');
    $("#" + inptElmntID).change(function () {
        var fileName = $(this).val();
        var input = document.getElementById(inptElmntID);
        sendFileToTrnsRqstsDocs(input.files[0], docNmElmntID, attchIDElmntID, sbmtdHdrID, function (data) {
            $("#" + attchIDElmntID).val(data.attchID);
            var dialog = bootbox.alert({
                title: 'Server Response!',
                size: 'small',
                message: '<div id="myInformation">' + data.message + '</div>',
                callback: function () {
                    if (data.message.indexOf("Success") !== -1) {}
                }
            });
        });
    });
    performFileClick(inptElmntID);
}

function sendFileToTrnsRqstsDocs(file, docNmElmntID, attchIDElmntID, sbmtdHdrID, callBackFunc) {
    var data1 = new FormData();
    data1.append('daInvstTransAttchmnt', file);
    data1.append('grp', 80);
    data1.append('typ', 1);
    data1.append('q', 'UPDATE');
    data1.append('actyp', 2);
    data1.append('docCtrgrName', $('#' + docNmElmntID).val());
    data1.append('attchmentID', $('#' + attchIDElmntID).val());
    data1.append('sbmtdPayTrnsRqstsID', sbmtdHdrID);
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

function getAttchdTrnsRqstsDocs(actionText, slctr, linkArgs, actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var srchFor = typeof $("#attchdTrnsRqstsDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdTrnsRqstsDocsSrchFor").val();
    var srchIn = typeof $("#attchdTrnsRqstsDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdTrnsRqstsDocsSrchIn").val();
    var pageNo = typeof $("#attchdTrnsRqstsDocsPageNo").val() === 'undefined' ? 1 : $("#attchdTrnsRqstsDocsPageNo").val();
    var limitSze = typeof $("#attchdTrnsRqstsDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdTrnsRqstsDocsDsplySze").val();
    var sortBy = typeof $("#attchdTrnsRqstsDocsSortBy").val() === 'undefined' ? '' : $("#attchdTrnsRqstsDocsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs, 'myFormsModalx', actionDialog, 'Transaction Request Attached Documents', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        if (!$.fn.DataTable.isDataTable('#attchdTrnsRqstsDocsTable')) {
            var table1 = $('#attchdTrnsRqstsDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdTrnsRqstsDocsTable').wrap('<div class="table-responsive">');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdTrnsRqstsDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdTrnsRqstsDocs(e, actionText, slctr, linkArgs, actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdTrnsRqstsDocs(actionText, slctr, linkArgs, actionDialog);
    }
}

function delAttchdTrnsRqstsDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID = typeof $("#sbmtdPayTrnsRqstsID").val() === 'undefined' ? -1 : $("#sbmtdPayTrnsRqstsID").val();
    var docNum = typeof $("#payTrnsRqstsDocNum").val() === 'undefined' ? '' : $("#payTrnsRqstsDocNum").val();
    var pKeyID = -1;
    if (typeof $('#attchdTrnsRqstsDocsRow' + rndmNum + '_AttchdDocsID').val() === 'undefined') {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#attchdTrnsRqstsDocsRow' + rndmNum + '_AttchdDocsID').val();
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
            if (result === true) {
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
                                    grp: 80,
                                    typ: 1,
                                    q: 'DELETE',
                                    actyp: 5,
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

function delPayTrnsRqsts(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_HdrID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_HdrID').val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $('#' + rowIDAttrb).find('td');
        pKeyNm = $.trim($tds.eq(3).text());
    }
    var msgPrt = "Transaction Request";
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
            if (result === true) {
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
                                    grp: 80,
                                    typ: 1,
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

function savePayTrnsRqstsForm(funcur, shdSbmt) {
    var sbmtdPayTrnsRqstsID = typeof $("#sbmtdPayTrnsRqstsID").val() === 'undefined' ? '-1' : $("#sbmtdPayTrnsRqstsID").val();
    var lnkdPayTrnsRqstsID = typeof $("#lnkdPayTrnsRqstsID").val() === 'undefined' ? '-1' : $("#lnkdPayTrnsRqstsID").val();
    var payTrnsRqstsType = typeof $("#payTrnsRqstsType").val() === 'undefined' ? '' : $("#payTrnsRqstsType").val();
    var payTrnsRqstsPrsnID = typeof $("#payTrnsRqstsPrsnID").val() === 'undefined' ? '-1' : $("#payTrnsRqstsPrsnID").val();
    var payTrnsRqstsPrsnNm = typeof $("#payTrnsRqstsPrsnNm").val() === 'undefined' ? '' : $("#payTrnsRqstsPrsnNm").val();
    var payTrnsRqstsItmTypID = typeof $("#payTrnsRqstsItmTypID").val() === 'undefined' ? '-1' : $("#payTrnsRqstsItmTypID").val();
    var payTrnsRqstsItmTypNm = typeof $("#payTrnsRqstsItmTypNm").val() === 'undefined' ? '' : $("#payTrnsRqstsItmTypNm").val();
    var payTrnsRqstsClsfctn = typeof $("#payTrnsRqstsClsfctn").val() === 'undefined' ? '' : $("#payTrnsRqstsClsfctn").val();
    var payTrnsRqstsDesc = typeof $("#payTrnsRqstsDesc").val() === 'undefined' ? '' : $("#payTrnsRqstsDesc").val();
    var payTrnsRqstsDate = typeof $("#payTrnsRqstsDate").val() === 'undefined' ? '' : $("#payTrnsRqstsDate").val();

    var payTrnsRqstsAmnt = typeof $("#payTrnsRqstsAmnt").val() === 'undefined' ? '0.00' : $("#payTrnsRqstsAmnt").val();
    var payTrnsRqstsHsAgreed = typeof $("input[name='payTrnsRqstsHsAgreed']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var errMsg = "";
    if (Number(payTrnsRqstsPrsnID) <= 0 || Number(payTrnsRqstsItmTypID) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Requestor cannot be empty!</span></p>';
    }
    if (payTrnsRqstsClsfctn.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Classification cannot be empty!</span></p>';
    }
    if (payTrnsRqstsDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Remark/Narration cannot be empty!</span></p>';
    }
    payTrnsRqstsAmnt = fmtAsNumber('payTrnsRqstsAmnt').toFixed(2);
    if (payTrnsRqstsAmnt <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Amount cannot be zero or less!</span></p>';
    }
    var isVld = true;
    if (rhotrim(errMsg, '; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Transaction Request',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Transaction Request...Please Wait...</p>',
        callback: function () {}
    });
    var formData = new FormData();
    formData.append('grp', 80);
    formData.append('typ', 1);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('sbmtdPayTrnsRqstsID', sbmtdPayTrnsRqstsID);
    formData.append('lnkdPayTrnsRqstsID', lnkdPayTrnsRqstsID);
    formData.append('payTrnsRqstsType', payTrnsRqstsType);
    formData.append('payTrnsRqstsPrsnID', payTrnsRqstsPrsnID);
    formData.append('payTrnsRqstsPrsnNm', payTrnsRqstsPrsnNm);
    formData.append('payTrnsRqstsItmTypID', payTrnsRqstsItmTypID);
    formData.append('payTrnsRqstsItmTypNm', payTrnsRqstsItmTypNm);
    formData.append('payTrnsRqstsClsfctn', payTrnsRqstsClsfctn);
    formData.append('payTrnsRqstsDesc', payTrnsRqstsDesc);
    formData.append('payTrnsRqstsDate', payTrnsRqstsDate);
    formData.append('payTrnsRqstsAmnt', payTrnsRqstsAmnt);
    formData.append('payTrnsRqstsHsAgreed', payTrnsRqstsHsAgreed);
    formData.append('shdSbmt', shdSbmt);
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
                            sbmtdPayTrnsRqstsID = data.sbmtdPayTrnsRqstsID;
                            getOnePayTrnsRqstsForm(sbmtdPayTrnsRqstsID, 101, 'ReloadDialog', payTrnsRqstsType);
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

function actOnLoanRqst(acttype) {
    var pKeyID = typeof $("#sbmtdPayTrnsRqstsID").val() === 'undefined' ? '-1' : $("#sbmtdPayTrnsRqstsID").val();
    var payTrnsRqstsType = typeof $("#payTrnsRqstsType").val() === 'undefined' ? '' : $("#payTrnsRqstsType").val();
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
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: acttype + ' Request?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Acting on Request...Please Wait...</p>',
                    callback: function () {
                        if (pKeyID > 0) {
                            if (payTrnsRqstsType === 'LOAN') {
                                getPayTrnsRqsts('', '#allmodules', 'grp=80&typ=1&vtyp=10');
                            } else if (payTrnsRqstsType === 'PAYMENT') {
                                getPayTrnsRqsts('', '#payPymntTrans', 'grp=80&typ=1&vtyp=1101');
                            } else {
                                getPayTrnsRqsts('', '#allmodules', 'grp=80&typ=1&vtyp=12');
                            }
                            getOnePayTrnsRqstsForm(pKeyID, 101, 'ReloadDialog', payTrnsRqstsType);
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
                                    grp: 80,
                                    typ: 1,
                                    q: 'UPDATE',
                                    actyp: 40,
                                    actiontyp: acttype,
                                    sbmtdPayTrnsRqstsID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1) {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else {
                        setTimeout(function () {
                            dialog1.find('.bootbox-body').html('<span style="color:red;">Nothing to Act On!</span>');
                        }, 500);
                    }
                });
            }
        }
    });
}

function getMyBalsDet(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#myBalsDetSrchFor").val() === 'undefined' ? '%' : $("#myBalsDetSrchFor").val();
    var srchIn = typeof $("#myBalsDetSrchIn").val() === 'undefined' ? 'Both' : $("#myBalsDetSrchIn").val();
    var pageNo = typeof $("#myBalsDetPageNo").val() === 'undefined' ? 1 : $("#myBalsDetPageNo").val();
    var limitSze = typeof $("#myBalsDetDsplySze").val() === 'undefined' ? 10 : $("#myBalsDetDsplySze").val();
    var myBalsDetItmStID = typeof $("#myBalsDetItmStID").val() === 'undefined' ? '-1' : $("#myBalsDetItmStID").val();
    var sortBy = typeof $("#myBalsDetSortBy").val() === 'undefined' ? '' : $("#myBalsDetSortBy").val();
    var qStrtDte = typeof $("#myBalsDetStrtDate").val() === 'undefined' ? '' : $("#myBalsDetStrtDate").val();
    var qEndDte = typeof $("#myBalsDetEndDate").val() === 'undefined' ? '' : $("#myBalsDetEndDate").val();
    var qActvOnly = $('#myBalsDetShwActvNtfs:checked').length > 0;
    var qNonLgn = $('#myBalsDetShwNonLgnNtfs:checked').length > 0;
    var qNonAknwldg = $('#myBalsDetShwNonAknwNtfs:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy +
        "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte + "&qActvOnly=" + qActvOnly + "&qNonLgn=" + qNonLgn + "&myBalsDetItmStID=" + myBalsDetItmStID;
    //alert(linkArgs);
    openATab(slctr, linkArgs);
}

function enterKeyFuncMyBalsDet(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getMyBalsDet(actionText, slctr, linkArgs);
    }
}